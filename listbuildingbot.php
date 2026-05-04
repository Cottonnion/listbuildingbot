<?php

/**
 * Plugin Name:       ListBuildingBot
 * Plugin URI:        https://listbuildingbot.com
 * Description:       Gather Qualified Leads, Recommend Products To Shoppers, And Wow Your Website Visitors With A Personalized Experience - Using Conversational Bots!
 * Version:           7.0
 * Author:            Veena Prashanth
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'LISTBUILDINGBOT_VERSION', '7.0' );

define( 'LBB_ABS_URL', plugin_dir_path( __FILE__ ) );
define( 'LBB_URL', plugin_dir_url( __FILE__ ) );
define( 'LBB_VERSION', '6.0' );
// WCPDOMAIN removed — no longer phoning home to vendor.
// define( 'WCPDOMAIN', 'www.wickedcoolplugins.com' );
define( 'WCPDOMAIN', '' );
define('LBB_PLUGIN_FILE', __FILE__);


require plugin_dir_path( __FILE__ ) . 'admin/doInstall.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-listbuildingbot-activator.php
 */
function activate_listbuildingbot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-listbuildingbot-activator.php';
	Listbuildingbot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-listbuildingbot-deactivator.php
 */
function deactivate_listbuildingbot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-listbuildingbot-deactivator.php';
	Listbuildingbot_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_listbuildingbot' );
register_deactivation_hook( __FILE__, 'deactivate_listbuildingbot' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-listbuildingbot.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-chatflow.php';

/*Firebase functions*/
require plugin_dir_path( __FILE__ ) . 'lib/firebase/smartboat-firebase-functions.php';

/*PDF Builder functions*/
require plugin_dir_path( __FILE__ ) . 'lib/pdf-builder/pdf-builder.php';

/* Lib functions*/
require plugin_dir_path( __FILE__ ) . 'lib/ai/listbuildingbot-ai.php';
require plugin_dir_path( __FILE__ ) . 'lib/ai/listbuildingbot-openai.php';

/* GHL CRM Integration Bridge — by Yahya */
require plugin_dir_path( __FILE__ ) . 'includes/class-lbb-ghl-integration.php';


//require_once(plugin_dir_path(__FILE__)."listbuildingbot-update/listbuildingbot.php");


// Update checker disabled — was sending site domain, license key, and version to wickedcoolplugins.com
// if ( file_exists( plugin_dir_path( __FILE__ ) . '/listbuildingbot-update/plugin-update-checker/plugin-update-checker.php' ) ) {
//   if (!class_exists('Puc_v4_Factory')) {
//     require_once dirname( __FILE__ ).'/listbuildingbot-update/plugin-update-checker/plugin-update-checker.php';
//   }
//   require_once(plugin_dir_path(__FILE__)."listbuildingbot-update/listbuildingbot.php");
// }


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_listbuildingbot() {
	$plugin = new Listbuildingbot();
	$plugin->run();
}
run_listbuildingbot();

function lbb_update_db_check() {
	
	global $lbb_db_version; 
   
	if( !function_exists('get_plugin_data') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	$plugin_data = get_plugin_data(  LBB_ABS_URL.'listbuildingbot.php');
	$lbb_db_version = $plugin_data['Version'];

	$lbb_custom_version = get_site_option( 'lbb_db_version' );
	
	if($lbb_custom_version == "") {
		LBBDoInstall();
	}else{
		if ( $lbb_custom_version  != $lbb_db_version ) {
		LBBDoInstall();
		}
	}
}
add_action( 'init', 'lbb_update_db_check',0 );

add_action( 'init', 'lbb_set_user_cookies' );
function lbb_set_user_cookies() {

    if ( ! is_user_logged_in() ) {
        return;
    }

    // Prevent resetting on every request
    if ( isset($_COOKIE['lbb_firstname']) && isset($_COOKIE['lbb_email']) ) {
        return;
    }

    $user = wp_get_current_user();

    if ( ! $user || empty( $user->ID ) ) {
        return;
    }

    $expire = time() + ( 30 * DAY_IN_SECONDS ); // 30 days

    setcookie(
        'lbb_firstname',
        sanitize_text_field( $user->first_name ),
        $expire,
        COOKIEPATH,
        COOKIE_DOMAIN,
        is_ssl(),
        false // HttpOnly = false (JS access allowed)
    );

    setcookie(
        'lbb_email',
        sanitize_email( $user->user_email ),
        $expire,
        COOKIEPATH,
        COOKIE_DOMAIN,
        is_ssl(),
        false
    );
}