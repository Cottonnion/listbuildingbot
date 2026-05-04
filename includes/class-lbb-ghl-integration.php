<?php
/**
 * ListBuildingBot → GoHighLevel CRM Integration Bridge
 *
 * Bridges the ListBuildingBot chatbot plugin with the GoHighLevel CRM Integration
 * plugin (ghl-crm-integration). When a chatbot conversation triggers automations
 * (after email capture, after last question, or after a specific answer), this file
 * intercepts the custom hooks fired by ListBuildingBot and calls the GHL CRM plugin's
 * public API functions to:
 *
 *   1. Ensure a WordPress user exists for the contact (creates one if anonymous).
 *   2. Queue GHL tag assignment via the GHL CRM plugin's async queue system.
 *   3. Optionally store the GHL contact mapping for future lookups.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * HOOKS CONSUMED (fired by ListBuildingBot):
 *
 *   • lbb_automation_triggered
 *     Fires inside lbbTriggerAutomation() in functions.php, after name/email/tags
 *     are gathered and BEFORE the platform loop sends webhooks.
 *     Params: $chatflow_id, $conversation_id, $name, $email, $tags, $extras, $action_id
 *
 *   • lbb_contact_updated
 *     Fires in submitUserReply() after a contact's name or email is saved.
 *     Params: $conversationId, $updateData, $type, $chatflow_id
 *
 *   • lbb_after_automation_trigger
 *     Fires after lbbTriggerAutomation() completes (both "after email" and
 *     "after last question / after answer pick" paths).
 *     Params: $chatflow_id, $conversation_id, $action_id, $extras, $trigger_type
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * DEPENDENCIES:
 *
 *   • GoHighLevel CRM Integration plugin (ghl-crm-integration) must be active.
 *     Specifically, the following public functions must exist:
 *       - ghl_crm_add_tags_to_user( int $user_id, array $tags ): bool
 *       - ghl_crm_remove_tags_from_user( int $user_id, array $tags ): bool
 *       - ghl_crm_get_user_contact_id( int $user_id ): ?string
 *       - ghl_crm_get_user_tag_ids( int $user_id ): array
 *       - ghl_crm_get_user_tag_names( int $user_id ): array
 *       - ghl_crm_user_has_tag( int $user_id, $tags ): bool
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * DATA FLOW:
 *
 *   Visitor fills out chatbot
 *     → submitUserReply() saves email/name
 *       → lbb_contact_updated hook fires (we log it)
 *     → lbbTriggerAutomation() gathers name, email, tags, custom fields
 *       → lbb_automation_triggered hook fires
 *         → THIS FILE: resolve/create WP user → call ghl_crm_add_tags_to_user()
 *       → Platform loop runs (webhooks, etc.)
 *     → lbb_after_automation_trigger hook fires (we log completion)
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * @package    ListBuildingBot
 * @subpackage GHL_Integration
 * @author     Yahya
 * @since      1.0.0
 *
 * Created by Yahya — Also created GHL CRM bridge integration that ListBuildingBot is using.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class LBB_GHL_Integration
 *
 * Handles the bridge between ListBuildingBot chatbot events and the
 * GoHighLevel CRM Integration plugin's tag/contact management system.
 *
 * This class:
 *   - Listens to LBB's custom action hooks.
 *   - Resolves or creates a WordPress user from chatbot contact data.
 *   - Delegates tag operations to the GHL CRM plugin's public API.
 *   - Logs every step via error_log() for debugging.
 *
 * All GHL API calls are queued asynchronously by the GHL CRM plugin —
 * this class never makes direct HTTP requests to GoHighLevel.
 *
 * @since  1.0.0
 * @author Yahya
 */
class LBB_GHL_Integration {

    /**
     * Prefix for all error_log messages from this integration.
     *
     * @var string
     */
    const LOG_PREFIX = '[LBB-GHL Integration]';

    /**
     * User meta key to store the LBB conversation ID that created this user.
     * Useful for tracing which chatbot conversation originated a WP user.
     *
     * @var string
     */
    const META_CREATED_BY_CONVERSATION = '_lbb_ghl_created_by_conversation';

    /**
     * User meta key to store the LBB chatflow ID associated with user creation.
     *
     * @var string
     */
    const META_CREATED_BY_CHATFLOW = '_lbb_ghl_created_by_chatflow';

    /**
     * Singleton instance.
     *
     * @var self|null
     */
    private static $instance = null;

    /**
     * Get or create the singleton instance.
     *
     * @return self
     */
    public static function get_instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor — registers all hook listeners.
     *
     * Hook priorities are set to 10 (default) so they run alongside other listeners.
     * To run AFTER other listeners, increase the priority number.
     */
    private function __construct() {
        $this->register_hooks();
    }

    /**
     * Register all WordPress action hooks.
     *
     * Each hook maps to a method in this class. The parameter counts (last arg
     * to add_action) must match the do_action() calls in ListBuildingBot.
     *
     * @return void
     */
    private function register_hooks(): void {

        /**
         * PRIMARY HOOK — This is where the main GHL tag assignment happens.
         *
         * Fired inside lbbTriggerAutomation() after name, email, tags, and
         * custom fields are all resolved. At this point we have everything
         * needed to create/find a WP user and queue GHL tags.
         *
         * @see functions.php — lbbTriggerAutomation()
         */
        add_action( 'lbb_automation_triggered', [ $this, 'handle_automation_triggered' ], 10, 7 );

        /**
         * CONTACT UPDATE HOOK — Fires when a visitor submits their name or email.
         *
         * Useful for early logging or pre-creating the WP user before the
         * full automation trigger fires. Currently used for logging only.
         *
         * @see public/class-listbuildingbot-public.php — submitUserReply()
         */
        add_action( 'lbb_contact_updated', [ $this, 'handle_contact_updated' ], 10, 4 );

        /**
         * POST-TRIGGER HOOK — Fires after lbbTriggerAutomation() completes.
         *
         * Useful for logging, cleanup, or triggering follow-up actions
         * that should only run after all platform automations have fired.
         *
         * @see public/class-listbuildingbot-public.php — submitUserReply() & chatbotAction()
         */
        add_action( 'lbb_after_automation_trigger', [ $this, 'handle_after_automation' ], 10, 5 );

        /**
         * MESSAGE TAGS HOOK — Fires when a message with tags is saved.
         *
         * Catches tags assigned AFTER the main automation already fired.
         * For example, if the automation fires at the name question but a later
         * question assigns additional tags, this hook picks them up.
         *
         * @see public/class-listbuildingbot-public.php — submitUserReply()
         */
        add_action( 'lbb_message_tags_saved', [ $this, 'handle_message_tags_saved' ], 10, 4 );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HOOK HANDLERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Handle the main automation trigger — assign GHL tags.
     *
     * This is the core method. It:
     *   1. Validates that the GHL CRM plugin is available.
     *   2. Resolves or creates a WordPress user from the contact email.
     *   3. Parses the LBB tag names from the conversation.
     *   4. Calls ghl_crm_add_tags_to_user() to queue tag assignment.
     *
     * Called by: do_action( 'lbb_automation_triggered', ... )
     * Fired from: lbbTriggerAutomation() in functions.php
     *
     * @param int    $chatflow_id     The chatflow post ID.
     * @param int    $conversation_id The conversation ID.
     * @param string $name            Contact name (may be empty).
     * @param string $email           Contact email address.
     * @param string $tags            Comma-separated tag names from the conversation.
     * @param array  $extras          Extra data: score, total_points, outcome, cfields.
     * @param int    $action_id       The current action/question post ID.
     * @return void
     */
    public function handle_automation_triggered( $chatflow_id, $conversation_id, $name, $email, $tags, $extras, $action_id ): void {

        self::log( "─── Automation Triggered ───" );
        self::log( "Chatflow ID: {$chatflow_id}" );
        self::log( "Conversation ID: {$conversation_id}" );
        self::log( "Action ID: {$action_id}" );
        self::log( "Contact Name: " . ( ! empty( $name ) ? $name : '(empty)' ) );
        self::log( "Contact Email: " . ( ! empty( $email ) ? $email : '(empty)' ) );
        self::log( "Tags (raw): " . ( ! empty( $tags ) ? $tags : '(none)' ) );
        self::log( "Extras keys: " . ( ! empty( $extras ) ? implode( ', ', array_keys( $extras ) ) : '(none)' ) );

        // ── Step 1: Check if GHL CRM plugin is available ──
        if ( ! self::is_ghl_plugin_available() ) {
            self::log( 'SKIPPED — GHL CRM Integration plugin is not active or functions not available.' );
            return;
        }

        // ── Step 2: Validate email ──
        if ( empty( $email ) || ! is_email( $email ) ) {
            self::log( "SKIPPED — Invalid or empty email: '{$email}'" );
            return;
        }

        // ── Step 3: Parse tags ──
        $tag_array = self::parse_tags( $tags );
        if ( empty( $tag_array ) ) {
            self::log( 'SKIPPED — No tags to assign.' );
            return;
        }
        self::log( 'Parsed tags (' . count( $tag_array ) . '): ' . implode( ', ', $tag_array ) );

        // ── Step 4: Route based on mode ──
        // When lbb_ghl_create_wp_user = false, push directly to GHL via the API
        // (no WordPress user created). The webhook guard in WebhookHandler::handle_contact_create()
        // will swallow the echo ContactCreate webhook using the transient we set.
        $create_wp_user = (bool) get_option( 'lbb_ghl_create_wp_user', true );

        if ( ! $create_wp_user ) {
            self::log( 'Mode: contact-only — pushing directly to GHL without creating WP user.' );
            self::push_contact_only_to_ghl( $email, $name, $tag_array );
            self::log( "─── Automation Triggered — DONE (contact-only) ───" );
            return;
        }

        $user_id = self::resolve_or_create_wp_user( $email, $name, $chatflow_id, $conversation_id );
        if ( ! $user_id ) {
            self::log( 'FAILED — Could not resolve or create WordPress user for email: ' . $email );
            return;
        }
        self::log( "Resolved WP user ID: {$user_id}" );

        // ── Step 5: Queue GHL tag assignment ──
        try {
            $result = ghl_crm_add_tags_to_user( $user_id, $tag_array );
            self::log( 'ghl_crm_add_tags_to_user() result: ' . ( $result ? 'QUEUED' : 'FAILED/EMPTY' ) );
        } catch ( \Exception $e ) {
            self::log( 'EXCEPTION in ghl_crm_add_tags_to_user(): ' . $e->getMessage() );
        }

        // ── Step 5b: If user has no GHL contact ID for the active location,
        //    the tags just went to pending meta. Fire profile_update to trigger
        //    the GHL sync pipeline, which will:
        //      1. Create/find the GHL contact
        //      2. Store the contact ID
        //      3. Flush pending tags (via UserMetaSync::flush_pending_tags)
        //    This mimics clicking "Update Profile" in wp-admin. ──
        if ( function_exists( 'ghl_crm_get_user_contact_id' ) ) {
            $contact_id = ghl_crm_get_user_contact_id( $user_id );
            if ( empty( $contact_id ) ) {
                $user_data = get_userdata( $user_id );
                if ( $user_data ) {
                    self::log( "No GHL contact ID for user {$user_id} — firing profile_update to trigger sync + flush pending tags." );
                    do_action( 'profile_update', $user_id, $user_data );
                    self::log( "profile_update fired for user {$user_id}." );
                }
            } else {
                self::log( "User {$user_id} already has GHL contact ID: {$contact_id} — no profile_update needed." );
            }
        }

        // ── Step 6: Log extra context for debugging ──
        if ( ! empty( $extras['score'] ) ) {
            self::log( "Score: {$extras['score']}" );
        }
        if ( ! empty( $extras['outcome'] ) ) {
            self::log( "Outcome: {$extras['outcome']}" );
        }
        if ( ! empty( $extras['cfields'] ) && is_array( $extras['cfields'] ) ) {
            self::log( 'Custom fields: ' . implode( ', ', array_keys( $extras['cfields'] ) ) );
        }

        self::log( "─── Automation Triggered — DONE ───" );
    }

    /**
     * Handle tags saved on a message — catch tags assigned after the main automation.
     *
     * When the chatflow assigns tags on a question that comes AFTER the automation
     * trigger point, the main handle_automation_triggered handler misses them.
     * This handler picks up those late tags by:
     *   1. Looking up the conversation's contact email.
     *   2. Finding the WP user (already created by the earlier automation).
     *   3. Resolving tag IDs to names and queuing them via ghl_crm_add_tags_to_user().
     *
     * Called by: do_action( 'lbb_message_tags_saved', ... )
     * Fired from: submitUserReply() in class-listbuildingbot-public.php
     *
     * @param int    $conversation_id The conversation ID.
     * @param string $tag_ids         Comma-separated tag IDs from the message.
     * @param int    $chatflow_id     The chatflow post ID.
     * @param int    $action_id       The action/question post ID.
     * @return void
     */
    public function handle_message_tags_saved( $conversation_id, $tag_ids, $chatflow_id, $action_id ): void {

        self::log( "─── Message Tags Saved ───" );
        self::log( "Conversation ID: {$conversation_id}" );
        self::log( "Chatflow ID: {$chatflow_id}" );
        self::log( "Action ID: {$action_id}" );
        self::log( "Tag IDs (raw): {$tag_ids}" );

        if ( ! self::is_ghl_plugin_available() ) {
            self::log( 'SKIPPED — GHL CRM plugin not available.' );
            return;
        }

        // Get the contact email from the LBB contact record.
        $contact_id = lbb_get_contact_id( $conversation_id );
        if ( empty( $contact_id ) ) {
            self::log( 'SKIPPED — No LBB contact for this conversation.' );
            return;
        }

        $email = lbb_get_contact_email( $contact_id );
        if ( empty( $email ) || ! is_email( $email ) ) {
            self::log( "SKIPPED — No valid email yet for contact {$contact_id}." );
            return;
        }

        // Resolve the tag IDs to tag names via the LBB tags table.
        global $wpdb;
        $id_array = array_filter( array_map( 'intval', explode( ',', $tag_ids ) ) );
        if ( empty( $id_array ) ) {
            self::log( 'SKIPPED — No valid tag IDs.' );
            return;
        }

        $placeholders = implode( ',', array_fill( 0, count( $id_array ), '%d' ) );
        $tag_names = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT name FROM {$wpdb->prefix}lbb_tags WHERE id IN ({$placeholders})",
                ...$id_array
            )
        );

        if ( empty( $tag_names ) ) {
            self::log( 'SKIPPED — Tag IDs did not resolve to any names.' );
            return;
        }

        self::log( 'Resolved tag names: ' . implode( ', ', $tag_names ) );

        // ── Route based on mode ──
        $create_wp_user = (bool) get_option( 'lbb_ghl_create_wp_user', true );

        if ( ! $create_wp_user ) {
            // Contact-only mode: push tags straight to GHL, no WP user.
            self::log( 'Mode: contact-only — pushing late tags directly to GHL.' );
            self::push_contact_only_to_ghl( $email, '', $tag_names );
            self::log( "─── Message Tags Saved — DONE (contact-only) ───" );
            return;
        }

        // Find the WP user (should already exist from earlier automation trigger).
        $wp_user = get_user_by( 'email', $email );
        if ( ! $wp_user ) {
            // If no user yet (email came after tags?), create one.
            $user_id = self::resolve_or_create_wp_user( $email, '', (int) $chatflow_id, (int) $conversation_id );
            if ( ! $user_id ) {
                self::log( 'FAILED — Could not resolve or create WP user for ' . $email );
                return;
            }
        } else {
            $user_id = $wp_user->ID;
        }

        self::log( "WP user ID: {$user_id}" );

        // Queue the tags.
        try {
            $result = ghl_crm_add_tags_to_user( $user_id, $tag_names );
            self::log( 'ghl_crm_add_tags_to_user() result: ' . ( $result ? 'QUEUED' : 'FAILED/EMPTY' ) );
        } catch ( \Exception $e ) {
            self::log( 'EXCEPTION: ' . $e->getMessage() );
        }

        self::log( "─── Message Tags Saved — DONE ───" );
    }

    /**
     * Handle contact update events (name or email saved).
     *
     * Currently used for logging. You can extend this to pre-create the WP user
     * as soon as the email is captured, before the full automation trigger fires.
     *
     * Called by: do_action( 'lbb_contact_updated', ... )
     * Fired from: submitUserReply() in class-listbuildingbot-public.php
     *
     * @param int    $conversation_id The conversation ID.
     * @param array  $update_data     The data that was updated (keys: 'email', 'firstname', 'status').
     * @param string $type            The question type ('email' or 'name').
     * @param int    $chatflow_id     The chatflow post ID.
     * @return void
     */
    public function handle_contact_updated( $conversation_id, $update_data, $type, $chatflow_id ): void {

        self::log( "─── Contact Updated ───" );
        self::log( "Conversation ID: {$conversation_id}" );
        self::log( "Chatflow ID: {$chatflow_id}" );
        self::log( "Update type: {$type}" );
        self::log( "Update data: " . wp_json_encode( $update_data ) );
        self::log( "─── Contact Updated — DONE ───" );
    }

    /**
     * Handle post-automation completion.
     *
     * Fires after lbbTriggerAutomation() has finished running all platform
     * triggers (webhooks, email automations, etc.). Useful for cleanup,
     * followup actions, or confirming the full pipeline completed.
     *
     * Called by: do_action( 'lbb_after_automation_trigger', ... )
     * Fired from: submitUserReply() & chatbotAction() in class-listbuildingbot-public.php
     *
     * @param int    $chatflow_id     The chatflow post ID.
     * @param int    $conversation_id The conversation ID.
     * @param int    $action_id       The action/question post ID.
     * @param array  $extras          Extra data: score, total_points, outcome, cfields.
     * @param string $trigger_type    What triggered this: 'email' or 'completion'.
     * @return void
     */
    public function handle_after_automation( $chatflow_id, $conversation_id, $action_id, $extras, $trigger_type ): void {

        self::log( "─── After Automation ───" );
        self::log( "Chatflow ID: {$chatflow_id}" );
        self::log( "Conversation ID: {$conversation_id}" );
        self::log( "Action ID: {$action_id}" );
        self::log( "Trigger type: {$trigger_type}" );
        self::log( "─── After Automation — DONE ───" );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // USER RESOLUTION
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Resolve an existing WP user by email, or create a new one.
     *
     * For logged-in users:
     *   → Returns the current user ID immediately.
     *
     * For anonymous visitors with an email:
     *   → Checks if a WP user with that email already exists.
     *   → If yes, returns that user's ID.
     *   → If no, creates a new WP user with role 'subscriber', using the
     *     email as username and a randomly generated password.
     *
     * The newly created user gets meta tags storing which conversation/chatflow
     * originated the account, for traceability.
     *
     * @param string $email           Contact email address.
     * @param string $name            Contact name (used as display_name if creating).
     * @param int    $chatflow_id     Chatflow ID (stored as user meta on creation).
     * @param int    $conversation_id Conversation ID (stored as user meta on creation).
     * @return int|false WordPress user ID on success, false on failure.
     */
    public static function resolve_or_create_wp_user( string $email, string $name, int $chatflow_id, int $conversation_id ) {

        // ── If visitor is logged in, use their user ID directly ──
        if ( is_user_logged_in() ) {
            $current_user_id = get_current_user_id();
            self::log( "User is logged in — using existing user ID: {$current_user_id}" );
            return $current_user_id;
        }

        // ── Validate email ──
        if ( empty( $email ) || ! is_email( $email ) ) {
            self::log( "Cannot resolve user — invalid email: '{$email}'" );
            return false;
        }

        // ── Check if a WP user with this email already exists ──
        $existing_user = get_user_by( 'email', $email );
        if ( $existing_user ) {
            self::log( "Found existing WP user for {$email} — user ID: {$existing_user->ID}" );
            return $existing_user->ID;
        }

        // ── Create a new WP user ──
        self::log( "No WP user found for {$email} — creating new subscriber account." );

        // Generate a sanitized username from the email (before the @)
        $base_username = sanitize_user( strtolower( strstr( $email, '@', true ) ), true );
        $username      = $base_username;

        // Ensure username uniqueness by appending a number if needed
        $counter = 1;
        while ( username_exists( $username ) ) {
            $username = $base_username . $counter;
            $counter++;
        }

        // Generate a random password (user won't need it — they came from a chatbot)
        $password = wp_generate_password( 24, true, true );

        // Parse name into first/last if possible
        $name_parts = self::parse_name( $name );

        $user_id = wp_insert_user( [
            'user_login'   => $username,
            'user_email'   => $email,
            'user_pass'    => $password,
            'first_name'   => $name_parts['first'],
            'last_name'    => $name_parts['last'],
            'display_name' => ! empty( $name ) ? $name : $username,
            'role'         => 'subscriber',
        ] );

        if ( is_wp_error( $user_id ) ) {
            self::log( 'FAILED to create WP user: ' . $user_id->get_error_message() );
            return false;
        }

        // Store traceability meta
        update_user_meta( $user_id, self::META_CREATED_BY_CONVERSATION, $conversation_id );
        update_user_meta( $user_id, self::META_CREATED_BY_CHATFLOW, $chatflow_id );

        self::log( "Created new WP user — ID: {$user_id}, username: {$username}, email: {$email}" );

        /**
         * Fires after a new WordPress user is created by the LBB-GHL integration
         * from a chatbot conversation (anonymous visitor → new subscriber).
         *
         * Other plugins can hook here to perform additional setup for the new user
         * (e.g. enroll in a course, assign membership, send welcome email).
         *
         * @since 1.0.0
         * @param int    $user_id         The new WordPress user ID.
         * @param string $email           The user's email address.
         * @param string $name            The user's name from the chatbot.
         * @param int    $chatflow_id     The chatflow that triggered creation.
         * @param int    $conversation_id The conversation that triggered creation.
         */
        do_action( 'lbb_ghl_user_created', $user_id, $email, $name, $chatflow_id, $conversation_id );

        return $user_id;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONTACT-ONLY GHL PUSH
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Push a contact + tags directly to GHL without creating a WordPress user.
     *
     * Used when the WP option 'lbb_ghl_create_wp_user' is set to false.
     *
     * Flow:
     *   1. Set a short-lived transient keyed by md5(email).
     *      WebhookHandler::handle_contact_create() checks this before calling
     *      GHLToWordPressSync — if found it skips sync entirely, preventing the
     *      echo ContactCreate webhook from creating a WP user.
     *   2. Upsert the GHL contact (create or update) via ContactResource::upsert().
     *   3. Add tags to the contact via ContactResource::add_tags().
     *
     * To enable contact-only mode, set the WP option:
     *   update_option( 'lbb_ghl_create_wp_user', false );
     * Or via WP CLI:
     *   wp option update lbb_ghl_create_wp_user 0
     *
     * @param string $email     Contact email address.
     * @param string $name      Contact full name.
     * @param array  $tag_array Tag names to assign.
     * @return void
     */
    private static function push_contact_only_to_ghl( string $email, string $name, array $tag_array ): void {

        // ── Guard: set transient BEFORE the API call so the echo webhook is
        //    caught even if GHL responds faster than the PHP process continues. ──
        $guard_key = 'ghl_skip_inbound_create_' . md5( strtolower( trim( $email ) ) );
        set_transient( $guard_key, 1, 2 * MINUTE_IN_SECONDS );
        self::log( "Set webhook guard transient: {$guard_key}" );

        // ── Get the GHL API client ──
        if ( ! class_exists( '\GHL_CRM\API\Client\Client' ) || ! class_exists( '\GHL_CRM\API\Resources\ContactResource' ) ) {
            self::log( 'FAILED — GHL API classes not available.' );
            delete_transient( $guard_key ); // clean up — we won't call the API
            return;
        }

        try {
            $client   = \GHL_CRM\API\Client\Client::get_instance();
            $resource = new \GHL_CRM\API\Resources\ContactResource( $client );

            // ── Parse name ──
            $name_parts = self::parse_name( $name );

            // ── Upsert contact (creates if new, updates if existing) ──
            // Only include name fields if we actually have them — avoids
            // overwriting an existing contact's name with empty strings.
            $upsert_data = [ 'email' => $email ];
            if ( ! empty( $name_parts['first'] ) ) {
                $upsert_data['firstName'] = $name_parts['first'];
            }
            if ( ! empty( $name_parts['last'] ) ) {
                $upsert_data['lastName'] = $name_parts['last'];
            }

            $result = $resource->upsert( $upsert_data );
            $ghl_contact_id = $result['contact']['id'] ?? $result['id'] ?? null;

            self::log( 'ContactResource::upsert() result — contact ID: ' . ( $ghl_contact_id ?: '(none)' ) . ', created: ' . ( ( $result['created'] ?? false ) ? 'yes' : 'no' ) );

            if ( empty( $ghl_contact_id ) ) {
                self::log( 'FAILED — No GHL contact ID returned from upsert.' );
                return;
            }

            // ── Add tags ──
            $tag_result = $resource->add_tags( $ghl_contact_id, $tag_array );
            self::log( 'ContactResource::add_tags() — tags sent: ' . implode( ', ', $tag_array ) );

        } catch ( \Exception $e ) {
            self::log( 'EXCEPTION in push_contact_only_to_ghl(): ' . $e->getMessage() );
            // Leave transient in place — better to suppress a ghost webhook than create a duplicate user.
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // UTILITY METHODS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Check if the GoHighLevel CRM Integration plugin is active and its
     * public API functions are available.
     *
     * We check for the primary function ghl_crm_add_tags_to_user — if that
     * exists, all other public functions from the GHL plugin should too.
     *
     * @return bool True if the GHL CRM plugin is loaded and ready.
     */
    public static function is_ghl_plugin_available(): bool {
        return function_exists( 'ghl_crm_add_tags_to_user' );
    }

    /**
     * Parse the comma-separated tag string from ListBuildingBot into a clean array.
     *
     * LBB stores tags as a GROUP_CONCAT of tag names, e.g. "hot-lead,interested,buyer".
     * This method splits, trims, and removes empty values.
     *
     * @param string $tags_string Comma-separated tag names.
     * @return array Clean array of tag name strings.
     */
    public static function parse_tags( string $tags_string ): array {
        if ( empty( $tags_string ) ) {
            return [];
        }

        $tags = explode( ',', $tags_string );
        $tags = array_map( 'trim', $tags );
        $tags = array_filter( $tags, function ( $tag ) {
            return '' !== $tag;
        } );

        return array_values( $tags );
    }

    /**
     * Parse a full name string into first and last name parts.
     *
     * Handles:
     *   - "John"          → first: "John",  last: ""
     *   - "John Doe"      → first: "John",  last: "Doe"
     *   - "John van Doe"  → first: "John",  last: "van Doe"
     *   - ""              → first: "",       last: ""
     *
     * @param string $full_name The full name string.
     * @return array{first: string, last: string}
     */
    public static function parse_name( string $full_name ): array {
        $full_name = trim( $full_name );

        if ( empty( $full_name ) ) {
            return [ 'first' => '', 'last' => '' ];
        }

        $parts = explode( ' ', $full_name, 2 );

        return [
            'first' => $parts[0],
            'last'  => isset( $parts[1] ) ? $parts[1] : '',
        ];
    }

    /**
     * Log a message with the integration prefix.
     *
     * All messages are sent to error_log() with the [LBB-GHL Integration] prefix
     * so they're easy to find in wp-content/debug.log or your server's error log.
     *
     * To enable logging, add these lines to wp-config.php:
     *   define( 'WP_DEBUG', true );
     *   define( 'WP_DEBUG_LOG', true );
     *
     * @param string $message The message to log.
     * @return void
     */
    public static function log( string $message ): void {
        return; // Disable logging by default — uncomment to enable.
        error_log( self::LOG_PREFIX . ' ' . $message );
    }
}

/**
 * Initialize the LBB → GHL integration.
 *
 * Runs on 'plugins_loaded' at priority 20 to ensure both ListBuildingBot
 * and the GHL CRM plugin have fully loaded before we wire up hooks.
 *
 * If the GHL CRM plugin is not active, the integration silently skips
 * initialization (no errors, no side effects).
 */
add_action( 'plugins_loaded', function () {

    // Only initialize if the GHL CRM plugin's functions are available.
    // This prevents fatal errors if the GHL plugin is deactivated.
    if ( ! function_exists( 'ghl_crm_add_tags_to_user' ) ) {
        // Silent skip — the GHL plugin isn't active, nothing to bridge.
        return;
    }

    LBB_GHL_Integration::get_instance();

}, 20 );
