<?php

function getLBBAPPCredentials() {
    $app_config = array();
    
    $firebase_db_configuration = get_option('firebase_db_configuration') != ""? stripslashes(get_option('firebase_db_configuration')): '';
    $firebase_app_configuration = get_option('firebase_app_configuration') != ""? stripslashes(get_option('firebase_app_configuration')): '';
    $options = get_option("firebase-chat-settings");

    
    if(!empty($firebase_db_configuration) ) {
        $app_config['firebase_db_config'] = $firebase_db_configuration;
    }
    if(!empty($firebase_app_configuration) ) {
        $app_config['firebase_app_config'] = $firebase_app_configuration;
     }
    return $app_config;
}

function add_user_to_firebase( $user_id, $user) {

    
    require_once LBB_ABS_URL.'lib/firebase/db/firebase-db.php';
		$users = new Firebase_Users();
		$user_data = $user;
		$avatar_args = array("class"=>"wp_chatee_rao_user_avatar_default");
		$avatar = get_avatar($user_data->user_email,25,404,"",$avatar_args);
		$send_data["name"] = $user_data->display_name;
		$send_data["email"] = $user_data->user_email;
		$send_data["user_registered"] = $user_data->user_registered;
		$send_data["message"] = "";
		$send_data["photoURL"] = $avatar;
		$chat_key = $users->insert($send_data);

		update_user_meta( $user_id, 'chat_id', $chat_key);
        return $chat_key;
}

function lbb_firebase_enqueue($return_array  = false){
	//wp_enqueue_script( "lbb-firebase-cookie", LBB_URL .'public/js/lbb-cookie.min.js',array( 'jquery' ), "", true );

	wp_enqueue_script( "firebase-app", "https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js","","",true );
	wp_enqueue_script( "firebase-auth", "https://www.gstatic.com/firebasejs/8.2.10/firebase-auth.js","", "", true );
	wp_enqueue_script( "firebase-database", "https://www.gstatic.com/firebasejs/8.2.10/firebase-database.js", "", "", true );
	wp_enqueue_script( "firebase-storage", "https://www.gstatic.com/firebasejs/8.2.10/firebase-storage.js", "", "", true ); 

	
	$app_credentials = getLBBAPPCredentials();
		if (isset($app_credentials["firebase_app_config"])) {
			$firebase_credentials = $app_credentials["firebase_app_config"];
			$firebase_credentials = json_decode($firebase_credentials, true);
		}
		
		
		if(isset($firebase_credentials) && is_array($firebase_credentials)) {
			delete_transient("rao_auth_error");
		}
		else {
			update_option("rao_firebase_user_credentials", false);
			set_transient("rao_auth_error", "yes");
			$firebase_credentials = array();
		}

		/*Temp Cred*/
		$firebase_credentials = get_option('firebase_app_configuration') != ""? stripslashes(get_option('firebase_app_configuration')): '';
		$firebase_credentials = json_decode($firebase_credentials, true);


		$options = get_option("firebase-chat-settings");

		$welcome_message = __("How can I help you?");
		$prompt = '';
		if(is_array($options)) {
			$welcome_message = $options["welcome_message"];
			$prompt = $options["enable_disable_prompt"];
		}
		
		$notify_play_audio = true; 
		$notify_notification = true; 

	    if(get_option('lbb_general_settings') ){
            $lbb_general_settings = get_option('lbb_general_settings');
            $lbb_notify_chat = (!empty($lbb_general_settings['notify_chat'])) ? $lbb_general_settings['notify_chat'] : array();
            
            if (!in_array('play_audio', $lbb_notify_chat)) {
	            	$notify_play_audio = false; 
	            }

	            if (!in_array('show_popup', $lbb_notify_chat)) {
	            	$notify_notification = false; 
	            }
        } 

        $is_wp_admin = is_admin();

        if ($return_array == true) {
        	$conversation_url = '';
        }else{
        	$conversation_url = admin_url('admin.php?page=listbuildingbot-conversation' );
        }
		$site_config = array(
			"plugin_url"=>LBB_URL,
			"ajaxurl"=>admin_url('admin-ajax.php'),  
			"conversationurl"=>$conversation_url,  
			"welcome_message"=>$welcome_message, 
			"notify_play_audio"=>$notify_play_audio, 
			"notify_notification"=>$notify_notification, 
			"is_wp_admin"=>$is_wp_admin, 
			"enable_prompt"=> $prompt);

		if(empty($firebase_credentials)){
			$firebase_credentials = array();
		}
		
		wp_localize_script( 'listbuildingbot', "firebaseConfig", $firebase_credentials );	
		wp_localize_script( 'listbuildingbot', "siteConfig", $site_config );	


		if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
				wp_enqueue_script( "lbb-firebase-custom", LBB_URL ."public/js/firebase-custom.js", array( 'jquery','jquery','moment', 'firebase-app', 'firebase-auth', 'firebase-database', 'firebase-storage', 'listbuildingbot'), 586, true );
			}else{
				wp_enqueue_script( "lbb-firebase-custom", LBB_URL ."public/js/firebase-custom.js", array( 'jquery','jquery','moment', 'firebase-app', 'firebase-auth', 'firebase-database', 'firebase-storage'), 586, true );
			}
		wp_localize_script( "lbb-firebase-custom", "firebaseConfig", $firebase_credentials );	
		wp_localize_script( "lbb-firebase-custom", "siteConfig", $site_config );

		if ($return_array == true) {
			$script_array = array(
				'firebase_app' => 'https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js',
				'firebase_auth' => 'https://www.gstatic.com/firebasejs/8.2.10/firebase-auth.js',
				'firebase_database' => 'https://www.gstatic.com/firebasejs/8.2.10/firebase-database.js',
				'firebase_storage' => 'https://www.gstatic.com/firebasejs/8.2.10/firebase-storage.js',
				'moment' => 'https://dapdemo.membershipsitechallenge.com/wp-includes/js/dist/vendor/moment.min.js',
				'lbb_listbuildingbot_public' => LBB_URL ."public/js/listbuildingbot-public.js",
				'lbb_firebase_custom' => LBB_URL ."public/js/firebase-custom.js",
			);

			$localize_script = array(
				'firebaseConfig' => $firebase_credentials,
				'siteConfig' => $site_config,
			);

			$final_array = array(
				'script_array' => $script_array,
				'localize_script' => $localize_script
			);

			return $final_array;
		}
}


function lbb_firebase_hidden_elements($chatflow_id = 0){
	$chat_admin_id = get_option("lbb_admin_firebase_id"); 
	$chat_user_id = '';
	/*if ($chatflow_id != 0 && isset($_COOKIE['lbbcf_'.$chatflow_id.'_conversation_chat_id'])) {
		$chat_user_id = $_COOKIE['lbbcf_'.$chatflow_id.'_conversation_chat_id'];
	}*/
	
	 ?>

	<input type="hidden" placeholder="Role" id="current_role_id" class="tbxset"  value="<?php echo $chat_user_id; ?>" />
	<input type="hidden" placeholder="UserId" id="current_id" class="tbxset"  value="0" />
	<input type="hidden" placeholder="UserId" id="current_admin_id" class="tbxset"  value="<?php echo  esc_attr($chat_admin_id); ?>" />

<?php }