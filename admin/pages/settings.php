<?php
?>
<script type="text/javascript">
    var chatflow_id = 1;
</script>
<?php get_lbb_header(); ?>
<section class="lbb-settings-outer-section">
    <div class="lbb-settings-no-container">
        <div class="lbb-content-start lbb-main-wrapper">
            <div class="tabs">
                <div class="lbb-tab-main" role="group">
                    <div class="lbb-tab-tool-bar">
                        <div class="lbb-header-tabs-part">
                            <ul id="tabs-nav">
                                <li><a href="#tab1">General</a></li>
                                <li><a href="#tab2">Global Style</a></li>
                                <li><a href="#tab4">Live Chat </a></li>
                                <li><a href="#tab6">Message Customizer</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="tabs-content">
                    <div id="tab1" class="tab-content">
                        <div class="lbb-page-heading-main">
                            <div class="lbb-page-heading lbb-page-with-subheading">
                                <i class="bx bxs-info-square"></i>
                                <h3>Settings<small>General Information</small></h3>
                            </div>
                        </div>


                        



                        
                         <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
                              <div class="lbb-container lbb-vertical-container">
                                  <div class="lbb-vertical-content-up">
                                      <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">

                                        <div id="lbb-settings-tabs-menu" class="lbb-settings-tabs-navigation lbb-sub-tab-wrapper">
                                            
                                            <div id="aiassistant-sublink" class="lbb-change-tab lbb-sub-tab lbb-active" data-tab="aiassistant-sub">AI Credentials</div>
                                            <div id="tags-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="tags-sub">Tags</div>
                                            <div id="outcomes-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="outcomes-sub">Outcomes</div>
                                            <div id="customfield-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="customField-sub">Custom Fields</div>
                                            <div id="pdf-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="pdf-sub">PDF</div>
                                            <div id="emails-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="emails-sub">Emails</div>
                                            <div id="search-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="search-sub">Search Options</div>
                                            <!-- <div id="general-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="general-sub">Live Chat</div> -->
                                            <div id="general-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="gdpr-sub">GDPR</div>
                                        </div>

                                        <div class="lbb-content-main-div">
                                            <div class="lbb-popup-aiassistant-sub-wrapper lbb-popup-tab-wrapper">
                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Setup OpenAI credentials Here</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-ai-assistant.php'); ?>
                                                </div>
                                            </div>

                                            <div class="lbb-popup-tags-sub-wrapper lbb-popup-tab-wrapper" style="display:none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Create tags! You can assign "answer tags" in the bot funnel builder. You can give users different tags based on responses. It'll be sent to the email platform.</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-tags.php'); ?>
                                                </div>
                                            </div>

                                            <!-- <div class="lbb-popup-general-sub-wrapper lbb-popup-tab-wrapper" style="display:none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Using the "Live Chat" option? You can setup the options here. You can enable/disable the live chat feature at bot level in the bot editor. </p>
                                                </div>

                                                
                                            </div> -->

                                            <div class="lbb-popup-customField-sub-wrapper lbb-popup-tab-wrapper"  style="display:none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Create custom fields! You can assign "answers" to a "custom field" in the bot funnel builder.</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-customfield.php'); ?>
                                                </div>
                                            </div>

                                            <div class="lbb-popup-outcomes-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Create different outcomes. You can map answers to outcomes in the funnel builder. To use this feature, add an outcome question type in your bot funnel. Show this question at the end. Use %outcome_title% tag in message area of this question. That's it! LBB will replace the  %outcome_title% tag with the right outcome title depending on the user outcome.</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-outcome.php'); ?>
                                                </div>
                                            </div>

                                            <div class="lbb-popup-pdf-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>You can get LBB to generate an automated PDF report based on responses. You can setup the header/footer content here. You can create the PDF in the PDF Builder page.</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-pdf.php'); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="lbb-popup-emails-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>You can send your subscribers an email after the chat ends. You can send conversation details in the email. You can also send a copy of this to yourself/admin.</p>
                                                </div>

                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-emailnotifications.php'); ?>
                                                </div>
                                            </div>

                                            <div class="lbb-popup-search-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>Want to add a "search" option in your bot to allow users to search for content? You can setup the options here. You can enable/disable the search feature at bot level in the bot editor.</p>
                                                </div>
                                                
                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-enablesearch.php'); ?>
                                                </div>
                                            </div>

                                            <div class="lbb-popup-gdpr-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">

                                                <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                                                    <p>GDPR</p>
                                                </div>
                                                
                                                <div class="lbb-tab-inner-start">
                                                    <?php  include( plugin_dir_path( __FILE__ ) . '/settings-gdpr.php'); ?>
                                                </div>
                                            </div>

                                        </div>


                                        
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div id="tab2" class="tab-content">
                        <?php  include( plugin_dir_path( __FILE__ ) . '/settings-global.php'); ?>
                        </div>
                    
                    <div id="tab4" class="tab-content">
                        <div class="lbb-page-heading-main">
                            <div class="lbb-page-heading lbb-page-with-subheading">
                                <i class="bx bxs-info-square"></i>
                                <h3>Firebase<small>This is used by LBB's Live Chat feature. LBB requires users to provide contact info first before they are transferred to an agent.</small></h3>
                            </div>
                        </div>

                        <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
                          <div class="lbb-container lbb-vertical-container">
                              <div class="lbb-vertical-content-up">
                                  <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                                    <!-- <div class="lbb-alert lbb-alert-warning lbb-mb-20">
                                        <p>To use the Live Chat feature, you need to setup a FREE "Google Firebase" account.  Firebase is used to build real-time features in applications such as live chat functionality. LBB uses it for live chat features.</p>
                                    </div> -->
                                    

                                    <div id="lbb-livechat-tabs-menu" class="lbb-livechat-tabs-navigation lbb-sub-tab-wrapper">
                                        <div id="live-chat-sublink" class="lbb-change-tab lbb-sub-tab lbb-active" data-tab="live-chat-sub">Live chat options</div>
                                        <div id="contact-form-sublink" class="lbb-change-tab lbb-sub-tab" data-tab="contact-form-sub">Contact Form</div>
                                    </div>

                                    <div class="lbb-popup-live-chat-sub-wrapper lbb-popup-tab-wrapper">
                                        <form method="POST" id="firebase-configuration">
                                            <input type="hidden" name="action" value="save_firebase_data">
                                            <div class="lbb-container">
                                                <div class="lbb-content">

                                                    

                                                    <?php
                                                    $lbb_livechat_options = "ajax_based";
                                                    if(get_option('lbb_livechat_options')){
                                                        $lbb_livechat_options = get_option('lbb_livechat_options');
                                                    }

                                                    ?>
                                                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="display:none;">
                                                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                            <h2>Live Chat Options:</h2>
                                                        </div>
                                                        <div class="lbb-row">
                                                            <div class="lbb-col-6">
                                                                <div class="lbb-form-group js-select2-wrapper">
                                                                    <ul class="lbb-radio-btn-wrapper">
                                                                        <li>
                                                                            <input type="radio" id="livechat_option_ajax" name="lbb_livechat_options" <?php if($lbb_livechat_options == "ajax_based"){ echo 'checked'; } ?> value="ajax_based">
                                                                            <label for="livechat_option_ajax">Use LBB's Live Chat Feature</label>
                                                                            <div class="lbb-check">
                                                                               <div class="inside"></div>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" id="livechat_option_firebase" name="lbb_livechat_options">
                                                                            <label for="livechat_option_firebase">Use Google Firebase </label>
                                                                            <div class="lbb-check"></div>
                                                                        </li>
                                                                        
                                                                    </ul>
                                                                </div>
                                                            </div>

                                                            <div class="livechat-feature lbb-alert-no-sb lbb-alert-success" style="<?php if($lbb_livechat_options == "ajax_based"){ echo ''; }else{ echo 'display: none;'; } ?>">
                                                                <p>This is easy to set up and use! Everything is managed by LBB. You won't have to set up anything externally to activate live chat on your site.</p>

                                                                <p>Please note: While not entirely real-time, it's close. There is a 5-second delay (for messages to be relayed from the user to the admin and vice versa). When users enter a message in the chat, the admin will be notified in approximately 5 seconds.</p>
                                                            </div>

                                                            <div class="firebase-feature lbb-alert-no-sb lbb-alert-success" style="<?php if($lbb_livechat_options == "firebase_based"){ echo ''; }else{ echo 'display: none;'; } ?>">
                                                                <p>Use this if you want true real-time chat, where messages are instantly relayed between the admin and the user without any delay. </p>

                                                                <p>Please NOTE: To use this feature, you need to setup a FREE "Google Firebase" account but the steps to setup a google firebase account for realtime chat is a bit complex. If you want an easier live chat setup, just use LBB's Live Chat option which is not truly a real-time chat but very close (just 5 seconds delay).</p>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-firebase-options" style="<?php echo ($lbb_livechat_options == 'firebase_based') ? '' : 'display: none;'; ?>">
                                                        <div class="lbb-row">
                                                            <div class="lbb-col-12">
                                                                <div class="lbb-open-ai-popup-btn lbb-text-center lbb-mb-20">
                                                                    <a href="javascript:void(0);" class="lbb-open-document-static lbb-btn lbb-btn-primary lbb-btn-small lbb-pos-abs-tr">View Document</a>
                                                                </div>
                                                            </div>
                                                            <div class="lbb-col-12">
                                                                <div class="lbb-form-group">
                                                                    <label for="lbb_admin_firebase_id">Admin User UID</label>
                                                                    <input type="text" id="lbb_admin_firebase_id" name="lbb_admin_firebase_id" value="<?php echo get_option('lbb_admin_firebase_id') != ""? stripslashes(get_option('lbb_admin_firebase_id')): '' ?>" class="lbb-input-field">
                                                                </div>
                                                                <div class="lbb-form-group">
                                                                    <label for="">Firebase APP Configuration</label>
                                                                    <textarea class="lbb-input-field" name="firebase_app_configuration" rows="10" cols="100"><?php echo get_option('firebase_app_configuration') != ""? stripslashes(get_option('firebase_app_configuration')): '' ?></textarea>
                                                                </div>
                                                                <div class="lbb-form-group">
                                                                    <label for="">Firebase DB Configuration</label>
                                                                    <textarea class="lbb-input-field" name="firebase_db_configuration" rows="20" cols="100"><?php echo get_option('firebase_db_configuration') != ""? stripslashes(get_option('firebase_db_configuration')): '' ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer" style="display:none;">
                                                <!-- <button id="lbb-save-firebase-configuration" class="lbb-btn lbb-btn-primary" type="submit">Save</button> -->
                                                <button  id="lbb-save-firebase-configuration-up" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
                                            </div>
                                        </form>
                                        <div class="lbb-tab-inner-start">
                                            <?php  include( plugin_dir_path( __FILE__ ) . '/settings-general.php'); ?>
                                        </div>
                                    </div>

                                    <div class="lbb-popup-contact-form-sub-wrapper lbb-popup-tab-wrapper">
                                        <?php  include( plugin_dir_path( __FILE__ ) . '/settings-contactform.php'); ?>
                                    </div>

                                        
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div id="tab6" class="tab-content">
                        <?php 
                        $lbb_message_data = array();
                        if(get_option('lbb_message_data') ){
                            $lbb_message_data = get_option('lbb_message_data');
                        }

                        $message_customizer_array = array(
                            array(
                                'name' => 'lbb_input_placeholder_text',
                                'id' => 'lbb_input_placeholder_text',
                                'label' => 'Enter your message',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_text','Enter your message')
                            ),
                            array(
                                'name' => 'lbb_input_placeholder_email',
                                'id' => 'lbb_input_placeholder_email',
                                'label' => 'Enter your email',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_email','Enter your email')
                            ),array(
                                'name' => 'lbb_input_placeholder_name',
                                'id' => 'lbb_input_placeholder_name',
                                'label' => 'Enter your name',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_name','Enter your name')
                            ),array(
                                'name' => 'lbb_input_placeholder_phone',
                                'id' => 'lbb_input_placeholder_phone',
                                'label' => 'Enter your phone',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_phone','Enter your phone')
                            ),array(
                                'name' => 'lbb_input_placeholder_country',
                                'id' => 'lbb_input_placeholder_country',
                                'label' => 'Enter your country',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_country','Enter your country')
                            ),array(
                                'name' => 'lbb_input_placeholder_url',
                                'id' => 'lbb_input_placeholder_url',
                                'label' => 'Enter your URL',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_url','Enter your URL')
                            ),array(
                                'name' => 'lbb_input_placeholder_date',
                                'id' => 'lbb_input_placeholder_date',
                                'label' => 'Enter your date',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_input_placeholder_date','Enter your date')
                            ),array(
                                'name' => 'lbb_conversation_end',
                                'id' => 'lbb_conversation_end',
                                'label' => 'This conversation has ended. Click below to start again.',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_conversation_end','This conversation has ended. Click below to start again.')
                            ),array(
                                'name' => 'lbb_guest_user',
                                'id' => 'lbb_guest_user',
                                'label' => 'Guest User',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_guest_user','Guest User')
                            ),array(
                                'name' => 'lbb_required_message',
                                'id' => 'lbb_required_message',
                                'label' => 'Please pick or enter a message',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_required_message','Please pick or enter a message')
                            ),array(
                                'name' => 'lbb_invalid_email',
                                'id' => 'lbb_invalid_email',
                                'label' => 'Please enter valid email address',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_invalid_email','Please enter valid email address')
                            ),array(
                                'name' => 'lbb_invalid_url',
                                'id' => 'lbb_invalid_url',
                                'label' => 'Please enter valid URL address',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_invalid_url','Please enter valid URL address')
                            ),array(
                                'name' => 'lbb_invalid_date_format',
                                'id' => 'lbb_invalid_date_format',
                                'label' => 'Please enter valid date and format should be ',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_invalid_date_format','Please enter valid date and format should be '),
                            ),
                            array(
                                'name' => 'lbb_reset_text',
                                'id' => 'lbb_reset_text',
                                'label' => 'Reset Chat',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_reset_text','Reset Chat'),
                            ),
                            array(
                                'name' => 'lbb_restart_text',
                                'id' => 'lbb_restart_text',
                                'label' => 'Restart Chat',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_restart_text','Restart Chat'),
                            ),array(
                                'name' => 'lbb_back_button_text',
                                'id' => 'lbb_back_button_text',
                                'label' => 'Back',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_back_button_text','Back'),
                            ),array(
                                'name' => 'lbb_write_message_text',
                                'id' => 'lbb_write_message_text',
                                'label' => 'Write your message here',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_write_message_text','Write your message here'),
                            ),array(
                                'name' => 'lbb_sure_reset',
                                'id' => 'lbb_sure_reset',
                                'label' => 'Are you sure you want to reset?',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_sure_reset','Are you sure you want to reset?'),
                            ),array(
                                'name' => 'lbb_enter_information_text',
                                'id' => 'lbb_enter_information_text',
                                'label' => 'Enter your information',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_enter_information_text','Enter your information'),
                            ),array(
                                'name' => 'lbb_full_name_text',
                                'id' => 'lbb_full_name_text',
                                'label' => 'Full Name',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_full_name_text','Full Name'),
                            ),array(
                                'name' => 'lbb_email_text',
                                'id' => 'lbb_email_text',
                                'label' => 'Email',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_email_text','Email'),
                            ),array(
                                'name' => 'lbb_submit_button_text',
                                'id' => 'lbb_submit_button_text',
                                'label' => 'Submit',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_submit_button_text','Submit'),
                            ),array(
                                'name' => 'lbb_agent_available',
                                'id' => 'lbb_agent_available',
                                'label' => 'Agent is available',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_agent_available','Agent is available'),
                            ),array(
                                'name' => 'lbb_agent_offline',
                                'id' => 'lbb_agent_offline',
                                'label' => 'Agent is offline',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_agent_offline','Agent is offline'),
                            ),array(
                                'name' => 'lbb_menu_item_chat',
                                'id' => 'lbb_menu_item_chat',
                                'label' => 'Menu Item: Chat',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_menu_item_chat','Chat'),
                            ),array(
                                'name' => 'lbb_menu_item_search',
                                'id' => 'lbb_menu_item_search',
                                'label' => 'Menu Item: Search',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_menu_item_search','Search'),
                            ),array(
                                'name' => 'lbb_have_more_questions',
                                'id' => 'lbb_have_more_questions',
                                'label' => 'AI: Default Value',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_have_more_questions','Sorry, looks like you have more questions on this topic. Please reach out to us below so we can send you a detailed response.'),
                            ),array(
                                'name' => 'lbb_ai_timeout_message',
                                'id' => 'lbb_ai_timeout_message',
                                'label' => 'AI: Timeout Message',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_ai_timeout_message','It looks like the call timed out. Please try again.'),
                            ),array(
                                'name' => 'contact_form_name',
                                'id' => 'contact_form_name',
                                'label' => 'Contact form name validation',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'contact_form_name','Please enter name'),
                            ),array(
                                'name' => 'contact_form_email',
                                'id' => 'contact_form_email',
                                'label' => 'Contact form email validation',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'contact_form_email','Please enter email'),
                            ),array(
                                'name' => 'contact_form_phone',
                                'id' => 'contact_form_phone',
                                'label' => 'Contact form phone validation',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'contact_form_phone','Please enter phone number'),
                            ),array(
                                'name' => 'contact_form_privacy_check',
                                'id' => 'contact_form_privacy_check',
                                'label' => 'Contact form privacy check validation',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'contact_form_privacy_check','Please select the checkbox'),
                            ),array(
                                'name' => 'incorrect_file_extension',
                                'id' => 'incorrect_file_extension',
                                'label' => 'Incorrect file extension',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'incorrect_file_extension','Incorrect file extension'),
                            ),array(
                                'name' => 'maximum_file_size',
                                'id' => 'maximum_file_size',
                                'label' => 'Maximum file size',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'maximum_file_size','Maximum file size is %%LIMIT%% MB'),
                            ),array(
                                'name' => 'lbb_please_wait',
                                'id' => 'lbb_please_wait',
                                'label' => 'Please Wait',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_please_wait','Please Wait...'),
                            ),array(
                                'name' => 'lbb_skip_question',
                                'id' => 'lbb_skip_question',
                                'label' => 'Skip this Message',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_skip_question','Skip this Message'),
                            ),array(
                                'name' => 'lbb_accept_terms',
                                'id' => 'lbb_accept_terms',
                                'label' => 'Please accept the terms to proceed',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_accept_terms','Please accept the terms to proceed'),
                            ),array(
                                'name' => 'lbb_main_menu',
                                'id' => 'lbb_main_menu',
                                'label' => 'Main Menu',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_main_menu','Main Menu'),
                            ),array(
                                'name' => 'lbb_sure_reset_yes',
                                'id' => 'lbb_sure_reset_yes',
                                'label' => 'Reset text yes',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_sure_reset_yes','Yes'),
                            ),array(
                                'name' => 'lbb_sure_reset_no',
                                'id' => 'lbb_sure_reset_no',
                                'label' => 'Reset text no',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_sure_reset_no','No'),
                            ),array(
                                'name' => 'lbb_thank_you_message',
                                'id' => 'lbb_thank_you_message',
                                'label' => 'Thank You Message',
                                'type' => 'text',
                                'value' => lbb_get_array_value($lbb_message_data,'lbb_thank_you_message','Thank you for submitting your contact details'),
                            )
                            
                        )

                        ?>


                        <div class="lbb-page-heading-main">
                            <div class="lbb-page-heading lbb-page-with-subheading">
                                <i class="bx bxs-info-square"></i>
                                <h3>Message Customizer<small>Customize your messages</small></h3>
                            </div>
                        </div>
                        <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
                              <div class="lbb-container lbb-vertical-container">
                                  <div class="lbb-vertical-content-up">
                                      <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                                        <form method="POST" id="save-message-customizer-data">
                                            <div class="lbb-container">
                                                <div class="lbb-content">
                                                    <div class="lbb-box lbb-section-bg-box lbb-where-to-show lbb-mb-20">
                                                        <div class="lbb-row">
                                                           <?php echo sqbGenerateCustomizerHtml($message_customizer_array); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer">
                                                <button id="lbb-save-message-customizer-btn" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                                                <button  id="lbb-save-message-customizer-btn-up" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>


<?php 
$args = array(
    'post_type' => 'lbb-chatflow',
    'posts_per_page' => -1
);
$obituary_query = new WP_Query($args);
$chatflow_names = "";
if($obituary_query){
    while ($obituary_query->have_posts()) : $obituary_query->the_post();
        $chatflow_names .= "<option value=".get_the_ID().">".get_the_title()."</option>";
    endwhile;
    wp_reset_postdata();
}

?>

<?php /* Outcome popup*/ ?>
<div id="propwrapoutcomes" class="lbb-popup-main lbb-outcomes-popup">
    <form method="POST" id="save-outcomes-data">
        <div class="lbb-popup-container">
            <div class="lbb-modal-start">
                <header class="lbb-modal-header">
                    <div class="lbb-modal-header-inner">
                        <h2>Add/Edit Outcomes</h2>
                        <div id="close" class="lbb-delete-icon">
                            <span class="dashicons dashicons-no-alt"></span>
                        </div>
                    </div>
                </header>
            
                <!-- <input type="hidden" name="action" value="save_outcomes_data"> -->
                <div class="lbb-popup-body-wrapper">
                    <div class="lbb-popup-question-wrapper">
                        <div class="lbb-form-group lbb-chatflow-field">
                            <label for="lbb_chatflow_id">Select Chatflow:</label>
                            <select id="lbb_chatflow_id" name="lbb_chatflow_id" class="lbb-input-field">
                                <?php echo $chatflow_names; ?>
                            </select>
                        </div>
                        <div class="lbb-form-group">
                            <label for="lbb_outcomes_name">Outcome Name</label>
                            <input type="text" id="lbb_outcomes_name" name="lbb_outcomes_name" class="lbb-input-field" placeholder="Enter Name...">
                            <div class="empty-tagname-error lbb-error" style="display:none;">Please enter name</div>
                        </div>
                        <div class="lbb-form-group">
                            <label for="lbb_outcomes_description">Outcome Description / Message</label>
                            <textarea id="lbb_outcomes_description" name="lbb_outcomes_description" class="lbb-input-field lbb_tiny_text_editor"></textarea>
                        </div>
                        <div class="lbb-form-group">
                            <label>Upload Image</label>
                            <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview lbb-no-img">
                                <div class="lbb-bot-user-image ">
                                  <a class="lbb-image-upload-button lbb-common-image-upload" href="javascript:void(0)" data-type="lbb_outcome_image_upload">Upload</a>
                                  <input type="hidden" id="lbb_outcome_image_upload" name="" value="">
                                </div>
                                <div class="lbb-image-preview-container">
                                    <img src="" alt="Preview Image" class="lbb-preview-image">
                                    <div class="lbb-image-actions">
                                        <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                        <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <footer class="lbb-popup-footer-wrapper">
                    <div class="lbb-popup-btn-footer">
                        <input type="hidden" name="lbb_outcomes_id" id="lbb_outcomes_id" value="">
                        <button id="lbb-save-outcomes" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                    </div>
                </footer>
                       
            </div>
        </div>
    </form>      
</div>


<?php /* Tag popup*/ ?>
<div id="propwrapTags" class="lbb-popup-main lbb-tags-popup">
    <form method="POST" id="save-tags-data">
        <div class="lbb-popup-container">
            <div class="lbb-modal-start">
                <header class="lbb-modal-header">
                    <div class="lbb-modal-header-inner">
                        <h2>Add/Edit Tag</h2>
                        <div id="close" class="lbb-delete-icon">
                            <span class="dashicons dashicons-no-alt"></span>
                        </div>
                    </div>
                </header>
                
                <input type="hidden" name="action" value="save_tags_data">
                <div class="lbb-popup-body-wrapper">
                    <div class="lbb-popup-question-wrapper">
                        <div class="lbb-form-group">
                            <label for="lbb_tags_name">Name</label>
                            <input type="text" id="lbb_tags_name" name="lbb_tags_name" class="lbb-input-field" placeholder="Enter Name...">
                            <div class="empty-tagname-error lbb-error" style="display:none;">Please enter name</div>
                        </div>
                        <div class="lbb-form-group">
                            <label for="lbb_tags_description">Description</label>
                            <textarea id="lbb_tags_description" name="lbb_tags_description" class="lbb-input-field"></textarea>
                        </div>
                    </div>
                </div>
                <footer class="lbb-popup-footer-wrapper">
                    <div class="lbb-popup-btn-footer">
                        <input type="hidden" name="lbb_tags_id" id="lbb_tags_id" value="">
                        <button id="lbb-save-tags" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                    </div>
                </footer>
                                 
            </div>
        </div>
    </form>
</div>

<?php /* Custom Field*/ ?>
<div id="propwrap" class="lbb-popup-main lbb-customfield-popup">
    <form method="POST" id="save-customfield-data">
        <div class="lbb-popup-container">
            <div class="lbb-modal-start">
                <header class="lbb-modal-header">
                    <div class="lbb-modal-header-inner">
                        <h2>Add/Edit Custom Field</h2>
                        <div id="close" class="lbb-delete-icon">
                            <span class="dashicons dashicons-no-alt"></span>
                        </div>
                    </div>
                </header>
                
                <input type="hidden" name="action" value="save_customfield_data">
                <div class="lbb-popup-body-wrapper">
                    <div class="lbb-popup-question-wrapper">
                        <div class="lbb-form-group">
                            <label for="lbb_name">Name (For Database Use)</label>
                            <input type="text" id="lbb_name" name="lbb_name" class="lbb-input-field">
                            <div class="empty-name-error lbb-error" style="display:none;">Please enter name</div>
                        </div>

                        <div class="lbb-form-group">
                            <label for="lbb_label">Label</label>
                            <input type="text" id="lbb_label" name="lbb_label" class="lbb-input-field">
                            <div class="empty-label-error lbb-error" style="display:none;">Please enter label</div>
                        </div>

                        <div class="lbb-form-group js-select2-wrapper">
                            <label for="lbb_field_type">Field Type:</label>
                            <select name="lbb_field_type" id="lbb_field_type" class="js-select2">
                                <option value="textbox">Textbox</option>
                                <option value="textarea">Textarea</option>
                            </select>
                        </div>

                        <div class="lbb-form-group js-select2-wrapper">
                            <label for="lbb_required_field">Required Field:</label>
                            <select name="lbb_required_field" id="lbb_required_field" class="js-select2">
                                <option value="N">N</option>
                                <option value="Y">Y</option>
                            </select>
                        </div>
                        
                        <div class="lbb-invalid-characters lbb-error">Sorry, these characters are not allowed: spaces, slash, any special characters</div>
                    </div>
                </div>
                <footer class="lbb-popup-footer-wrapper">
                    <div class="lbb-popup-btn-footer">
                        <input type="hidden" name="lbb_customfield_id" id="lbb_customfield_id" value="">
                        <button id="lbb-save-customfield-settings" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                    </div>
                </footer>
            </div>
        </div>
    </form>    
</div>

<div id="propwrapfirebase" class="lbb-popup-main">
    <div id="properties" class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>FIREBASE CONFIGURATION STEPS</h2>
                    <div id="firebase-close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>

            <div class="lbb-popup-body-wrapper">
                <div id="chatflow-properties" class="lbb-btn-wrapper">
                   
                   <div class="firebase-step-process-wrapper">
                       <div class="lbb-step-process">
                        <h2>STEP 1: CREATE FIREBASE PROJECT</h2>
                        <ol>
                            <li>Go to <a href="https://console.firebase.google.com/" target="_blank">Google Console</a></li>
                            <li>Click on <b>Add Project</b></li>
                            <li>Enter your Project name and click on <b>Contine</b> button</li>
                            <li>Be sure to Disable the Google Analytics Option</li>
                            <li>Click on Continue => This will redirect you to Project Overview Dashboard.</li>
                        </ol>
                    </div>

                    <div class="lbb-step-process">
                        <h2>STEP 2: CREATE REALTIME DATABASE</h2>
                        <ol>
                            <li>Navigate to <b>Build<</b> Menu in left side and Click on  <b>Realtime Database</b></li>
                            <li>Click on <b>Create Database</b> Button</li>
                            <li>Choose the Realtime Database Location and press the <b>Next</b> button</li>
                            <li>Chooe this option "Start in Locked(Live) mode"</li>
                            <li>
                            <div class="lbb-firebase-demo-code">
                                Click on <b>Rules</b> Tab and update rules below
                                <code>{
  "rules": {
    ".read": "auth != null",  // Allow read access to authenticated users
    ".write": "auth != null", // Allow write access to authenticated users

    "fcmTokens": {
      "$uid": {
        // Only the authenticated user can read and write their own FCM token
        ".read": "auth != null && auth.uid === $uid",
        ".write": "auth != null && auth.uid === $uid"
      }
    }
  }
}</code><br>
    Ones you replace above rules then click on <b>Publish</b> button
                            </div></li>
                        </ol>
                    </div>

                    <div class="lbb-step-process">
                        <h2>STEP 3: CREATE WEB APP</h2>
                        <ol>
                            <li>Click to Project Overview Settings Icon ⚙️ >> Project Settings => General Tab => <b>Your Apps</b> Section (At the bottom)</li>
                            <li>Click on Web app platform</li>
                            <li>Enter My Web App Name <br><small>Do NOT check this box: Also set up Firebase Hosting for this app.</small></li>
                            <li>Click on "Register App" button</li>
                            <li>Click on "Continue to Console" button</li>
                            <li>Navigate to Project Settings => General Tab => Your Apps => SDK setup and Configuration => Pick the "Config" Option</li>
                            <li>Copy the content with curly braces in text editor and enclose JSON object key with "".<br>
                            <div class="lbb-firebase-demo-code">
                                Copy the content within the curly braces in a text editor and enclose each JSON object key with double quotation marks. For example, change apiKey: to "apiKey", "your-project-key": to "your-project-key".  (enclose the key names with double quotes), Adjust all keys accordingly with the associated project values.
                                <br />The adjusted snippet should look like this:
                                <code>{
    "apiKey": "your-project-key",
    "authDomain": "your-project-authdomain",
    "databaseURL": "your-project-databaseurl",
    "projectId": "your-projectId",
    "storageBucket": "your-project-storageBucket",
    "messagingSenderId": "your-project-senderId",
    "appId": "your-project-appId",
    "measurementId": "your-project-measurementId"
}</code><br>
    Copy the above sinppet code and put in our LBB plugin(Plugin Settings Page => General Tab => Firebase App Configuration field)
                            </div></li>
                        </ol>
                    </div>

                    <div class="lbb-step-process">
                        <h2>STEP 4: CONFIGURE FIREBASE DATABASE & SECRET KEYS</h2>
                        <ol>
                            <li>Click to Project Overview Settings Icon ⚙️ => Project Settings => Service Accounts (tab)</li>
                            <li>Click on "Generate new private key" button</li>
                            <li>Confirm and click on "Generate Key" button</li>
                            <li>JSON file will be downloaded automatically</li>
                            <li>Copy the file content and paste it into the LBB Setting Page  >> Firebase Tab General Tab >> Firebase DB Configuration Textarea.</li>
                        </ol>
                    </div>

                    <div class="lbb-step-process">
                        <h2>STEP 5: CONFIGURE AUTHENTICATION TO ADD AN ADMIN USER FROM A PLUGIN ADMIN DASHBOARD PAGE</h2>
                        <ol>
                            <li>Navigate to Build Menu => Authentication</li>   
                            <li>Click on "Get Started" button</li>
                            <li>Go to <b>Native Providers</b> and select "Email/Password" Option</li>
                            <li><b>Enable</b> Email/Password option and click on the <b>Save<b> button</li>
                            <li>Go to the Users tab and create a new user by entering the admin email address and password. and click on <b>add user button<b></li>
                            <li>Copy <b>User UID</b> from table for that created user</li>
                            <li>Paste it into the LBB Setting Page  >> Firebase Tab General Tab >> Admin User UID field </li>
                            <li>Click On Save button to save values in LBB </li>

                        </ol>
                    </div>
                    <?php /*
                    <div class="lbb-step-process">
                        <h2>STEP 6: ENABLE FIREBASE STORAGE <small>If you want allow your customers to append a document/file, need to enable firebase storage from</small></h2>
                        <ol>
                            <li>Navigate to Google Console => Build => Storage</li>
                            <li>Click on Get Started</li>
                            <li>Select start in test mode</li>
                            <li>Choose the Storage Location</li>
                            <li>Open Rules tab</li>
                            <li>Set date to Next year to avoid adjusting the rules again and again.</li>
                        </ol>
                    </div>
                    */ ?>


                   </div>

                </div>
                <div id="divisionthing"></div>
            </div>
        </div>
    </div>
</div>


<?php 
function sqbGenerateCustomizerHtml($field_array){
    foreach ($field_array as $key => $value) {
        ?>
        <div class="lbb-col-6">
            <div class="lbb-form-group">
                <label for="<?= $value['id'] ?>"><?= $value['label'] ?></label>
                <input type="text" id="<?= $value['id'] ?>" name="<?= $value['name'] ?>" value="<?= $value['value'] ?>" class="lbb-input-field">
            </div>
        </div>
        <?php
    }
}

?>

<?php get_lbb_footer(); ?>