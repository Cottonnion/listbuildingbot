<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wickedcoolplugins.com
 * @since      1.0.0
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/public
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class Listbuildingbot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->init();
	}

	public function init() {

		if(!isset($_REQUEST['elementor-preview']) && $GLOBALS['pagenow'] !== 'post.php'){
		
			add_shortcode('ListBuildingBot', array($this,'fireShortcode'));
			add_action('wp_footer', array($this,'renderChatbot'));
		}

		add_action('wp_ajax_sbl_chatbot_action', array($this,'chatbotAction'));
		add_action('wp_ajax_nopriv_sbl_chatbot_action', array($this,'chatbotAction'));

		add_action('wp_ajax_sbl_helpdesk_filter', array($this,'helpDeskFilter'));
		add_action('wp_ajax_nopriv_sbl_helpdesk_filter', array($this,'helpDeskFilter'));

		add_action('wp_ajax_sbl_chatbot_submit_reply', array($this,'submitUserReply'));
		add_action('wp_ajax_nopriv_sbl_chatbot_submit_reply', array($this,'submitUserReply'));

		add_action('wp_ajax_sbl_chatbot_ai_submit', array($this,'submitAIReply'));
		add_action('wp_ajax_nopriv_sbl_chatbot_ai_submit', array($this,'submitAIReply'));

		add_action('wp_ajax_sbl_chatbot_trained_ai_submit', array($this,'submitTrainedAIReply'));
		add_action('wp_ajax_nopriv_sbl_chatbot_trained_ai_submit', array($this,'submitTrainedAIReply'));

		add_action('wp_ajax_sbl_chatbot_trained_ai_submit_contact', array($this,'submitTrainedAIContact'));
		add_action('wp_ajax_nopriv_sbl_chatbot_trained_ai_submit_contact', array($this,'submitTrainedAIContact'));


		add_action('wp_ajax_sbl_chatbot_start_conversation', array($this,'startConversation'));
		add_action('wp_ajax_nopriv_sbl_chatbot_start_conversation', array($this,'startConversation'));

		add_action('wp_ajax_lbb_trained_to_logicbot', array($this,'lbb_trained_to_logicbot'));
		add_action('wp_ajax_nopriv_lbb_trained_to_logicbot', array($this,'lbb_trained_to_logicbot'));

		add_action('wp_ajax_sbl_ai_start_conversation', array($this,'startAIConversation'));
		add_action('wp_ajax_nopriv_sbl_ai_start_conversation', array($this,'startAIConversation'));

		add_action('wp_ajax_sbl_trained_ai_start_conversation', array($this,'startTrainedAIConversation'));
		add_action('wp_ajax_nopriv_sbl_trained_ai_start_conversation', array($this,'startTrainedAIConversation'));
		
		add_action('wp_ajax_sbl_end_conversation', array($this,'endConversation'));
		add_action('wp_ajax_nopriv_sbl_end_conversation', array($this,'endConversation'));

		add_action('wp_ajax_lbb_get_chat_mode', array($this,'GetChatMode'));
		add_action('wp_ajax_nopriv_lbb_get_chat_mode', array($this,'GetChatMode'));

		add_action('wp_ajax_lbb_handle_attachment_upload', array($this,'handleAttachmentUpload'));
		add_action('wp_ajax_nopriv_lbb_handle_attachment_upload', array($this,'handleAttachmentUpload'));

		add_action('wp_ajax_lbb_handle_audio_upload', array($this,'handleAudioUpload'));
		add_action('wp_ajax_nopriv_lbb_handle_audio_upload', array($this,'handleAudioUpload'));

		add_action('wp_ajax_lbb_check_agent_status', array($this,'checkAgentStatus'));
		add_action('wp_ajax_nopriv_lbb_check_agent_status', array($this,'checkAgentStatus'));

		add_action('wp_ajax_lbb_check_last_message', array($this,'lbb_check_last_message'));
		add_action('wp_ajax_nopriv_lbb_check_last_message', array($this,'lbb_check_last_message'));

		add_action('wp_ajax_lbb_get_last_message_from', array($this,'lbb_get_last_message_from'));
		add_action('wp_ajax_nopriv_lbb_get_last_message_from', array($this,'lbb_get_last_message_from'));

		add_action('wp_ajax_lbb_send_bot_mailm', array($this,'lbb_send_bot_mail'));
		add_action('wp_ajax_nopriv_lbb_send_bot_mail', array($this,'lbb_send_bot_mail'));
		
		if(isset($_REQUEST['lbb-pdf-download'])){
			add_action('init', array($this,'downloadPDF'));
		}

		if(isset($_GET['lbb-embed'])){
			add_filter('wp', array($this,'runEmbed'),9999);
		}
		if(isset($_GET['is-test'])){
			add_filter('init', array($this,'isTest'));
		}
		
	}

	public function lbb_send_bot_mail(){

		lbb_access_control_allow_origin();
		$conversation_id = $_REQUEST['conversation_cookie_val'];
		$chatflow_id = $_REQUEST['chatflow_id'];

		lbb_trigger_email($chatflow_id, $conversation_id,array());
	}

	public function lbb_trained_to_logicbot(){

		$conversation_id = $_REQUEST['conversation_cookie_val'];
		$cm = new ConversationManager();
		$cm->setLogicBotConversation($conversation_id);
		echo 'Success';	
		exit;
	}

	public function lbb_check_last_message(){

		lbb_access_control_allow_origin();
		$conversation_id = $_REQUEST['conversation_id'];
		$type = $_REQUEST['type'];
		$idle_time = $_REQUEST['idle_time'];

	 	$count = lbb_check_last_message_from($conversation_id,$type, $idle_time);

		echo ($count > 0) ? 'idle' : 'active';
		exit;

	}

	public function lbb_get_last_message_from(){

		lbb_access_control_allow_origin();
		global $wpdb;
		$conversation_id = $_REQUEST['conversation_id'];

		$query = "SELECT * FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversation_id." ORDER BY `message_id` DESC LIMIT 1";

		$last_message = $wpdb->get_row($query);

		if(!empty($last_message)){
			if($last_message->agent_id > 0){
				echo 'admin';
			}else{
				echo 'user';
			}
		}else{
			echo '';
		}
		exit;

	}


	public function helpDeskFilter(){

		$chatflow_id = !empty($_REQUEST['chatflow_id']) ? $_REQUEST['chatflow_id'] : '0';
		$keyword = !empty($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		$html = lbb_search_filter($keyword,$chatflow_id);

		echo $html;exit;

	}

	public function checkAgentStatus(){

		lbb_access_control_allow_origin();
		
		$chatflow_id = isset($_POST['lbb_chatflow_id']) ? $_POST['lbb_chatflow_id'] : 0;
		$idle_status = get_post_meta($chatflow_id, 'live_chat_idle_time_enable', true);
		if($idle_status == 'no'){
			$start_time = get_post_meta($chatflow_id, 'start_time', true);
			$end_time = get_post_meta($chatflow_id, 'end_time', true);

			$current_time = current_time('H:i', true);

			if ($current_time >= $start_time && $current_time <= $end_time) {
				$warning = array(
					'warning' => 1,
					'available' => 1,
					'message' => 'Agent is available'
				);
			}else{
				$warning = array(
					'warning' => 1,
					'available' => 0,
					'message' => 'Agent is not available'
				);
			}

		}else{
			$warning = array(
				'warning' => 1,
				'available' => 1,
				'message' => 'Agent is available'
			);
		}



		$welcome_message = get_post_meta($chatflow_id, 'welcome_message', true);

		$lbb_general_settings = get_option('lbb_general_settings', array());

		$welcome_message = !empty($lbb_general_settings['livechat_welcome_message']) ? $lbb_general_settings['livechat_welcome_message'] : 'Welcome to live chat!';

		$warning["welcome_message"] = $welcome_message;
		echo json_encode($warning);exit;
	}

	public function downloadPDF(){
		include LBB_ABS_URL.'public/pdf.php';
	}

	public function runEmbed(){
		$template = LBB_ABS_URL.'public/embed.php';
		include $template;
		exit();
	}

	public function handleAudioUpload(){
		lbb_access_control_allow_origin();
		$audioData = $_POST['audio'];
		$upload_dir = wp_upload_dir();

		ob_start();
		include(LBB_ABS_URL.'admin/templates/chat/audio.php');
		$downloadBox = ob_get_clean();

		// Generate a unique filename for the audio file
		$filename = 'recorded_audio_' . time() . '.wav';

		$filepath = $upload_dir['basedir'] . '/listbuildingbot/audio/' . $filename;

		$attachment_url = $upload_dir['baseurl'] . '/listbuildingbot/audio/' . $filename;

		$downloadBox = str_replace('{{url}}', $attachment_url, $downloadBox);

		$audioTmpPath = $_FILES['audio']['tmp_name'];

		if (move_uploaded_file($audioTmpPath, $filepath)) {
		//if (file_put_contents($filepath, $audioData) !== false) {
			echo json_encode([
				'audio' => $attachment_url, 
				'a_name' => $filename,
				'type' => '.wav',
				'download_box' => $downloadBox
			]);

		} else {
			echo 'Error saving audio.';
		}
		exit;
	}

	public function handleAttachmentUpload($chatflow_id){

		lbb_access_control_allow_origin();
		// Check if a file was uploaded
		if (isset($_FILES['image'])) {
			$upload_dir = wp_upload_dir();

			$action_id = $_REQUEST['action_id'];
			
			$upload_file = $upload_dir['basedir'] . '/listbuildingbot/attachment/' . basename($_FILES['image']['name']);
			$extension = pathinfo($upload_file, PATHINFO_EXTENSION);
			$uniqueString = $uniqueString = uniqid(mt_rand(), true) . bin2hex(random_bytes(16));
			$filename = $uniqueString.'.'.$extension;
			$upload_file = $upload_dir['basedir'] . '/listbuildingbot/attachment/' . $filename;

			$meta_ext = get_post_meta($action_id, 'question_upload_type', true);
			$allowedExtensions = !empty($meta_ext) ? explode(',', $meta_ext) : array();


			//$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif','pdf','doc','txt'];

			$message_ddata = get_option('lbb_message_data', array());
				
			$incorrect_file_extension = !empty($message_ddata['incorrect_file_extension']) ? $message_ddata['incorrect_file_extension'] : 'Incorrect file extension';
			$maximum_file_size = !empty($message_ddata['maximum_file_size']) ? $message_ddata['maximum_file_size'] : 'Maximum file size is %%LIMIT%% MB';

			$meta_fs = get_post_meta($action_id, 'maxFileUploadSize', true);
			$meta_fs = !empty($meta_fs) ? (int)$meta_fs : 10;
			$maxFileSize = $meta_fs * 1024 * 1024;

			$fileSize = $_FILES['image']['size'];

			if ($fileSize <= $maxFileSize) {}else{
				$maximum_file_size = str_replace('%%LIMIT%%',$maxFileSize,$maximum_file_size);
				echo json_encode([
					'status' => 'error',
					'message' => $maximum_file_size
				]);	
				exit;
			}

			if (in_array(strtolower($extension), $allowedExtensions)) {
			}else{
				echo json_encode([
					'status' => 'error',
					'message' => 'Incorrect file extension'
				]);
				exit;
			}

			if (!file_exists($upload_dir['basedir'] . '/listbuildingbot/attachment/')) {
				wp_mkdir_p($upload_dir['basedir'] . '/listbuildingbot/attachment/');
			}

			if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {

				ob_start();
				include(LBB_ABS_URL.'admin/templates/chat/attachment.php');
				$downloadBox = ob_get_clean();

				$attachment_url = $upload_dir['baseurl'] . '/listbuildingbot/attachment/' . $filename;

				$downloadBox = str_replace('{{url}}',$attachment_url , $downloadBox);
				$downloadBox = str_replace('{{text}}',basename($_FILES['image']['name']) , $downloadBox);

				echo json_encode([
					'attachment' => $attachment_url, 
					'name' => basename($_FILES['image']['name']),
					'type' => $extension ,
					'download_box' => $downloadBox
				]);
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'Something went wrong'
				]);
				exit;
			}
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Something went wrong'
			]);
			exit;
		}
	
		// Always exit to prevent further output
		exit;

	}

	public function renderChatbot(){
		
		global $wpdb;
		$current_page_id = get_queried_object_id();
		// Get the current page ID
		//$current_page_id = get_the_ID(); // or $post->ID;

		// Query to find posts with the current page ID in the 'selected_page' meta
		$query = $wpdb->prepare(
			"SELECT post_id
			FROM $wpdb->postmeta
			WHERE meta_key = 'selected_url'
			AND FIND_IN_SET(%s, REPLACE(meta_value, ' ', ''))",
			$current_page_id
		);

		$matching_post_ids = $wpdb->get_col($query);
		
		if(!empty($matching_post_ids[0])){
			
			$who_should_see = (get_post_meta( $matching_post_ids[0], 'who_should_see', true ))? get_post_meta( $matching_post_ids[0], 'who_should_see', true ) : 'all_visitor';

			if($who_should_see != 'all_visitor'){

				if(!is_user_logged_in()){
					return '';
				}

			}

			$how_to_show = get_post_meta($matching_post_ids[0], 'how_to_show', true);
			if($how_to_show != 'inline'){
				echo do_shortcode('[ListBuildingBot id="'.$matching_post_ids[0].'"]');
			}
		}else{
			$current_page_url = get_permalink($current_page_id);
			if (substr($current_page_url, -1) !== '/') {
				$current_page_url1 = $current_page_url.'/';
			}else{
				$current_page_url1 = rtrim($current_page_url, '/');
			}
			
			$query = $wpdb->prepare(
				"SELECT post_id
				FROM $wpdb->postmeta
				WHERE meta_key = 'enter_url'
				AND (FIND_IN_SET(%s, REPLACE(meta_value, ' ', '')) OR FIND_IN_SET(%s, REPLACE(meta_value, ' ', '')))",
				$current_page_url,
				$current_page_url1
			);
			
			$matching_post_ids = $wpdb->get_col($query);
			
			if(!empty($matching_post_ids[0])){

				$who_should_see = (get_post_meta( $matching_post_ids[0], 'who_should_see', true ))? get_post_meta( $matching_post_ids[0], 'who_should_see', true ) : 'all_visitor';

				if($who_should_see != 'all_visitor'){

					if(!is_user_logged_in()){
						return '';
					}

				}

				$how_to_show = get_post_meta($matching_post_ids[0], 'how_to_show', true);
				if($how_to_show != 'inline'){
					echo do_shortcode('[ListBuildingBot id="'.$matching_post_ids[0].'"]');
				}
			}
		}
		
	}

	public function fireShortcode($atts) {

		global $lbb_messages;

		if(is_admin()){
			return false;
		}

		$atts = shortcode_atts( array(
			'id' => '0'
		), $atts, 'listbuildingbot' );

		$who_should_see = (get_post_meta( $atts['id'], 'who_should_see', true ))? get_post_meta( $atts['id'], 'who_should_see', true ) : 'all_visitor';
	
		$chatflow_status = (get_post_meta( $atts['id'], 'lbb_chatflow_status', true ))? get_post_meta( $atts['id'], 'lbb_chatflow_status', true ) : 'Y';

		if($chatflow_status == 'N' && !isset($_GET['lbb-embed'])){
			return '';
		}

		if($who_should_see != 'all_visitor'){

			if(!is_user_logged_in()){
				return '';
			}

		}

		//if(isset($_GET['lbb-embed'])){
			$this->enqueue_styles();
			$this->enqueue_scripts();
		//}
		
	
		ob_start();
		require_once(LBB_ABS_URL.'/public/partials/listbuildingbot-public-display.php');
		$html = ob_get_clean();

		return $html;
	}



	public function startConversation(){

		lbb_access_control_allow_origin();

		function lbb_check_if_conversation_end($conversation_id){
			$cm = new ConversationManager();
			return $cm->isConversationEnd($conversation_id);
		}
		
		$chatflow_id = !empty($_REQUEST['chatflow_id']) ? $_REQUEST['chatflow_id'] : '';
		$conversation_cookie_val = !empty($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$conversation_key = 'lbbcf_'.$chatflow_id.'_conversation_id';
		$data = array();
		$data['end_status'] = 0;

		
		// Check if conversation ID exists in the cookie

		$page_load = get_post_meta($chatflow_id, 'page_load_options', true);

		$page_load = !empty($page_load) ? $page_load : 'yes';

		if(isset($_REQUEST['lbb_embed']) && $_REQUEST['lbb_embed'] == 1){
			$page_load = 'yes';
		}


		

		if (isset($_COOKIE[$conversation_key])) {
			$cookie_values = $_COOKIE[$conversation_key];
		}else{
			$cookie_values = $conversation_cookie_val;
		}


		if ($cookie_values != ''){
			$conversation_status = lbb_get_conversation_status($cookie_values);
			if($conversation_status == 'L'){


				global $wpdb;
				$page_load = 'no';
				$message_table = $wpdb->prefix.'lbb_messages';

				$sql = "UPDATE $message_table SET is_read = 1 WHERE agent_id > 0 AND conversation_id = '".$cookie_values."'";

				$result = $wpdb->query($sql);

				//echo $wpdb->last_query;

			}
		}

		if ($cookie_values != '' && $page_load != 'yes' && @$isRestart == false) {

			
			
		    // If conversation ID exists, load the conversation from the database
		    $conversationId = $cookie_values;
		    $data['conversation_id'] = $conversationId;
		    $conversations = getLBBMessages($conversationId);
			
			$isEnd = lbb_check_if_conversation_end($conversationId);
			$data['end_status'] = $isEnd;
			ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/agent-response.php');
			$main_admin_html = ob_get_clean();

			ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/user-response.php');
			$main_user_html = ob_get_clean();
			$data['action_required'] = false;

			ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/agent-pdf-button.php');
			$pdf_button_html = ob_get_clean();

			ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/attachment.php');
			$attachment_html = ob_get_clean();

			ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/audio.php');
			$audio_html = ob_get_clean();

			$new_timezone = $_REQUEST['timezone'];
			foreach ($conversations as $key => $con) {

				
				$lastMessage = ($con['is_bot_response']) ? 1 : 0;
				
				$time = $con['sent_time'];
				$UTC = new DateTimeZone("UTC");
				$newTZ = new DateTimeZone($new_timezone);
				$date = new DateTime( $time , $UTC );
				$date->setTimezone( $newTZ );

				$time_time = $date->format('H:i');
				$conversations[$key]['time_format'] = $time_time;
				//$conversations[$key]['is_bot_response'] = $con['is_bot_response'];
				if($con['is_bot_response'] == 1){

					$message_meta = maybe_unserialize($con['message_meta']);

					$image = isset($message_meta['image']) ? $message_meta['image'] : '';

					if(!empty($image)){
						$me = '<div class="lbb-anchors lbb-bot-response lbb-text">'.$con['message_text'].'<div class="lbb-chat-image"><img src="'.$image.'"></div></div>';
					}else{
						$me = $con['message_text'];
					}

					$conversations[$key]['image'] = $me;

					$conversations[$key]['dynamic_messages'] = !empty($con['action_id']) ? lbb_get_dynamic_messages($con['action_id'],$conversationId) : array();

					//$html = str_replace('{{name}}', 'Bot', $main_admin_html);
					$html = str_replace('{{message}}',$me, $main_admin_html);
					$previousID = null;

					if ($con['is_bot_response'] !== $previousID) {
						$conversations[$key]['show-author'] = 1;
					}else{
						$conversations[$key]['show-author'] = 0;
					}

					if(count($conversations) == ($key+1)){
						if(!empty($con['action_id'])){
							/*$qype = get_post_meta($con['action_id'], 'question_type', true);
							if($qype != 'pdf'){	
								$data['action_required'] = true;
							}*/
							
							$data['action_required'] = true;
							$htm = lbb_get_bot_action($con['action_id'],$conversationId);

							$question_type = get_post_meta($con['action_id'], 'question_type', true);

							$contact_id = lbb_get_contact_id($conversationId);
							$name = lbb_get_contact_name($contact_id);
							$score = lbb_get_score($conversationId);
							$email = lbb_get_contact_email($contact_id);

							if($question_type == 'outcome'){
								
								$score = lbb_get_score($conversationId);

								$range_status = get_post_meta($con['action_id'], 'outcome_range_enabled', true);
								$default_outcome = lbb_get_default_outcome_id($chatflow_id);
								
								$outcome_title = '';
								$outcome_content = '';

								
								if(!empty($default_outcome)){

									if($range_status == 1){
										$outcome_range = get_post_meta($con['action_id'], 'outcome_range', true);
										if(!empty($outcome_range)){
										
											foreach($outcome_range as $range){
												if ($score >= $range['start'] && $score <= $range['end']) {
													$outcome_id = $range['outcome_id'];
													break;
												}
											}
										}
									}else{
										global $wpdb;
										$outcome_ids = $wpdb->get_var("SELECT GROUP_CONCAT(outcomes) FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversationId." and outcomes <> ''");

										$max_outcome = lbb_get_max_outcome($outcome_ids);
										if($max_outcome){
											$outcome_id = $max_outcome;
										}
									}
						
									$outcome = lbb_get_outcome($outcome_id);
									
									if(!empty($outcome)){
										$outcome_title = $outcome['name'];
										$outcome_content = $outcome['content'];
										if(!empty($outcome['outcome_image'])){
											$outcome_image = '<div><img src="'.$outcome['outcome_image'].'" /></div>';
										}
									}
								}
								
								$total_points = lbb_get_total_points($chatflow_id);
								$placeholders = [
									'%name%' => $name,
									'%NAME%' => $name,
									'%FIRST_NAME%' => $name,
									'%first_name%' => $name,
									'%FIRSTNAME%' => $name,
									'%firstname%' => $name,
									'%email%' => $email,
									'%EMAIL%' => $email,
									'%score%' => $score,
									'%SCORE%' => $score,
									'%total_score%' => $total_points,
									'%TOTAL_SCORE%' => $total_points,
									'%outcome%' => $outcome_content,
									'%OUTCOME%' => $outcome_content,
									'%outcome_title%' => $outcome_title,
									'%OUTCOME_TITLE%' => $outcome_title,
									'%outcome_image%' => $outcome_image,
									'%OUTCOME_IMAGE%' => $outcome_image,
								];

								$data_array = lbb_get_contactmeta_mergetag($contact_id);
								if(!empty($data_array)){
									$placeholders = array_merge($placeholders,$data_array);
								}

								$htm['text'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
									$placeholder = $match[0];
									return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
								}, $htm['text']);

								$htm['title'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
									$placeholder = $match[0];
									return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
								}, $htm['title']);

								if(!empty($htm['extra_messages'])){
									foreach ($htm['extra_messages'] as $key => $value) {
										
										$htm['extra_messages'][$key] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
											$placeholder = $match[0];
											return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
										}, $value);
									}
								}
								

							}else{
								$placeholders = [
									'%name%' => $name,
									'%NAME%' => $name,
									'%FIRST_NAME%' => $name,
									'%first_name%' => $name,
									'%FIRSTNAME%' => $name,
									'%firstname%' => $name,
									'%email%' => $email,
									'%EMAIL%' => $email,
									'%SCORE%' => $score,
								];

								$data_array = lbb_get_contactmeta_mergetag($contact_id);
								if(!empty($data_array)){
									$placeholders = array_merge($placeholders,$data_array);
								}

								$htm['text'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
									$placeholder = $match[0];
									return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
								}, $htm['text']);

								$htm['title'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
									$placeholder = $match[0];
									return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
								}, $htm['title']);

								if(!empty($htm['extra_messages'])){
									foreach ($htm['extra_messages'] as $key => $value) {
										
										$htm['extra_messages'][$key] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
											$placeholder = $match[0];
											return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
										}, $value);
									}
								}
							}



							$htm = str_replace('{{reply_buttons}}','', $htm);
							$htm = str_replace('{{skip_buttons}}','', $htm);
							$htm = str_replace('{{inline_field}}','', $htm);
							$htm = str_replace('{{pdf_buttons}}','', $htm);
							$conversations[$key]['text'] = $htm['text'];
							$conversations[$key]['field_html'] = $htm['field_html'];
							$conversations[$key]['type'] = $htm['type'];
							$conversations[$key]['date_format'] = $htm['date_format'];
							
							
							$data['block'] = $htm;
						}else{
							$html = str_replace('{{reply_buttons}}','', $html);
							$html = str_replace('{{skip_buttons}}','', $html);
							$html = str_replace('{{inline_field}}','', $html);
							$html = str_replace('{{pdf_buttons}}','', $html);
							$conversations[$key]['text'] = $html;
						}
					}else{
						$html = str_replace('{{reply_buttons}}','', $html);
						$html = str_replace('{{skip_buttons}}','', $html);
						$html = str_replace('{{inline_field}}','', $html);
						$html = str_replace('{{pdf_buttons}}','', $html);
						
						$conversations[$key]['text'] = $html;
					}
				}else if($con['agent_id'] > 0){	

					$html = str_replace('{{message}}',$con['message_text'], $main_admin_html);
					$html = str_replace('{{reply_buttons}}','', $html);
					$html = str_replace('{{skip_buttons}}','', $html);
					$html = str_replace('{{inline_field}}','', $html);
					$html = str_replace('{{pdf_buttons}}','', $html);
					$conversations[$key]['text'] = $html;	
					
				}else{
					//$html = str_replace('{{name}}', 'User', $main_user_html);

					$message_meta = maybe_unserialize($con['message_meta']);

					$image = isset($message_meta['image']) ? $message_meta['image'] : '';
					$attachment = isset($message_meta['attachment']) ? $message_meta['attachment'] : '';
					$attachment_name = isset($message_meta['name']) ? $message_meta['name'] : 'Download';

					$audio = isset($message_meta['audio']) ? $message_meta['audio'] : '';
					$audio_name = isset($message_meta['a_name']) ? $message_meta['a_name'] : 'Audio';

					if(!empty($image)){
						$me = '<div class="quick-reply-useranswer-image"><div class="quick-reply-spn-text">'.$con['message_text'].'</div><div class="quick-reply-spn-img"><img src="'.$image.'"></div></div>';
					}else if(!empty($attachment)){
						
						$me = str_replace('{{url}}',$attachment , $attachment_html);
						$me = str_replace('{{text}}', $attachment_name, $me);
					}else if(!empty($audio)){
						$me = str_replace('{{url}}',$audio , $audio_html);
					}else{
						$me = $con['message_text'];
					}

					$html = str_replace('{{message}}',$me, $main_user_html);
					$conversations[$key]['text'] = $html;
					$conversations[$key]['image'] = $image;
				}

				$previousID = $con['is_bot_response'];
			}
			
			$data['conversations'] = $conversations;
		}else if($cookie_values != '' && $page_load == 'yes' && @$isRestart == false){
			
			
			
			$conversationId = $cookie_values;
			//lbb_trigger_email($chatflow_id, $conversationId);
			$user_id = is_user_logged_in() ? get_current_user_id() : '0';

			
			
			lbb_clear_conversation($conversationId);
		    $data['conversation_id'] = $conversationId;
			$welcome_ques_id = get_post_meta($chatflow_id, 'start_action_id', true);
			if(!empty($welcome_ques_id)){
				$htm = lbb_get_bot_action($welcome_ques_id);
				$htm['is_bot_response'] = 1;
				$data['action_required'] = false;
				$args = array(
					'conversation_id' => $conversationId,
					'user_id' => $user_id,
					'message_text' => $htm['title'],
					'message_meta' => '',
					'sent_time' => gmdate('Y-m-d H:i:s'),
					'is_bot_response' => 1,
				);
				$messageManager = new MessageManager();
		
				$inserted_id1 = $messageManager->insertMessage($args);

				$next_ques_id = get_post_meta($welcome_ques_id, 'next_question_id', true);

				$image = get_post_meta($next_ques_id, 'image', true);
				$meta = !empty($image) ? maybe_serialize(array('image' => $image)) : maybe_serialize(array());

				if(!empty($next_ques_id)){
					$htmm = lbb_get_bot_action($next_ques_id);
					$args = array(
						'conversation_id' => $conversationId,
						'user_id' => $user_id,
						'message_text' => $htmm['title'],
						'message_meta' => $meta,
						'sent_time' => gmdate('Y-m-d H:i:s'),
						'is_bot_response' => 1,
					);
					$messageManager = new MessageManager();
			
					$inserted_id = $messageManager->insertMessage($args);
				}

			}

			$data['conversations'] = array(0 => $htm);
		
		}else if($cookie_values != '' && @$isRestart == true){

			

			$user_id = is_user_logged_in() ? get_current_user_id() : '0';
			$conversationId = $cookie_values;
			//lbb_clear_conversation($conversationId);
		    $data['conversation_id'] = $conversationId;
			$welcome_ques_id = get_post_meta($chatflow_id, 'start_action_id', true);
			if(!empty($welcome_ques_id)){
				$htm = lbb_get_bot_action($welcome_ques_id);
				$data['action_required'] = false;
				$args = array(
					'conversation_id' => $conversationId,
					'user_id' => $user_id,
					'message_text' => $htm['title'],
					'message_meta' => '',
					'sent_time' => gmdate('Y-m-d H:i:s'),
					'is_bot_response' => 1,
				);
				$messageManager = new MessageManager();
		
				$inserted_id1 = $messageManager->insertMessage($args);

				$next_ques_id = get_post_meta($welcome_ques_id, 'next_question_id', true);

				$image = get_post_meta($next_ques_id, 'image', true);
				$meta = !empty($image) ? maybe_serialize(array('image' => $image)) : maybe_serialize(array());

				if(!empty($next_ques_id)){
					$htmm = lbb_get_bot_action($next_ques_id);
					$args = array(
						'conversation_id' => $conversationId,
						'user_id' => $user_id,
						'message_text' => $htmm['title'],
						'message_meta' => $meta,
						'sent_time' => gmdate('Y-m-d H:i:s'),
						'is_bot_response' => 1,
					);
					$messageManager = new MessageManager();
			
					$inserted_id = $messageManager->insertMessage($args);
				}

			}

			$data['conversations'] = array(0 => $htm);

		} else {
		   
		    $conversationId = uniqid();

			$user_id = is_user_logged_in() ? get_current_user_id() : '0';

		    // Assuming you have the necessary data for the conversation

			if(isset($_COOKIE['llb_country'])){
				$location = $_COOKIE['llb_country'];
				
			}else{
				$location = llb_get_country_code_byid($_SERVER['REMOTE_ADDR']);
				if(!empty($location)){
					setcookie('llb_country',$location , time() + (3600 * 24),'/');
				}
			}

			$userAgent = $_SERVER['HTTP_USER_AGENT'];

			if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
				$browser = 'Internet Explorer';
			} elseif (strpos($userAgent, 'Edge') !== false) {
				$browser = 'Microsoft Edge';
			} elseif (strpos($userAgent, 'Chrome') !== false) {
				$browser = 'Google Chrome';
			} elseif (strpos($userAgent, 'Firefox') !== false) {
				$browser = 'Mozilla Firefox';
			} elseif (strpos($userAgent, 'Safari') !== false) {
				$browser = 'Safari';
			} elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
				$browser = 'Opera';
			} else {
				$browser = 'Unknown';
			}

			$extra_info = array(
				'browser' => $browser,
				'url' => $_REQUEST['lbb_current_page'],
				'location' => $location
			);

			
			
			$no_guest_account = get_post_meta($chatflow_id, 'create_guest_account', true);

			$args = array(
			    'user_id' => $user_id,
			    'end_time' => '',
				'extra_info' => maybe_serialize($extra_info),
				'chatflow_id' => $chatflow_id
			);

			if($no_guest_account == 'yes'){
				$args['is_published'] = 0;
			}
			

			$serialized_array = serialize($args);

			// Set the cookie with the serialized array
			$conversation_tmp_key = 'conversation_tmp_data_'.$chatflow_id;
			setcookie($conversation_tmp_key, $serialized_array, time() + (3600 * 24), '/');
			$data['action_required'] = false;
			$data['conversation_tmp'] = $serialized_array;
			// Insert the conversation into the database
			
			

			//if($no_guest_account == 'yes'){
				$inserted_id = 0;
			/*}else{
				$inserted_id = lbb_save_conversation($args);
			}*/

			$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

			if($chatflow_type == 'logicbot'){}else{
				$inserted_id = lbb_save_conversation($args);
			}

			if ($inserted_id) {
			    // Insertion successful
			    //setcookie($conversation_key, $inserted_id, time() + (3600 * 24),'/');
			    $data['conversation_id'] = $inserted_id;


				$parsed_url = wp_parse_url(get_site_url());

				if (isset($parsed_url['host'])) {
					// Use parse_url to extract the domain
					$domain = parse_url($parsed_url['host'], PHP_URL_HOST);
				}

				$unique_number = uniqid();
				$contact = array(
					'conversation_id' => $inserted_id,
					'firstname' => 'guest_' . $unique_number,
					'lastname' => '',
					'email' => 'guest_' . $unique_number . '@'.$domain,
					'status' => '0',
					'created_date' => gmdate('Y-m-d')
				);

				lbb_add_contact($contact);

			} else {
			   $data['conversation_id'] = 0;
			}

			$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

			if($chatflow_type == 'logicbot' || $chatflow_type == 'botlivechat'){

				$welcome_ques_id = get_post_meta($chatflow_id, 'start_action_id', true);
				if(!empty($welcome_ques_id)){
					$htm = lbb_get_bot_action($welcome_ques_id);
					$htm['is_bot_response'] = 1;
					$meta = array();
					if(!empty($htm['extra_messages'])){
						$meta['extra_messages'] = $htm['extra_messages'];
					}
					$meta = !empty($meta) ? maybe_serialize($meta) : '';

					if(!empty($htm['title'])){

						if($inserted_id > 0){
							$args = array(
								'conversation_id' => $inserted_id,
								'user_id' => $user_id,
								'message_text' => $htm['title'],
								'message_meta' => $meta,
								'sent_time' => gmdate('Y-m-d H:i:s'),
								'is_bot_response' => 1,
							);
							$messageManager = new MessageManager();
					
							$inserted_id1 = $messageManager->insertMessage($args);
						}
					}

					$next_ques_id = get_post_meta($welcome_ques_id, 'next_question_id', true);

					$image = get_post_meta($next_ques_id, 'image', true);
					$meta = !empty($image) ? array('image' => $image) : array();
					
					/*$extra_messages = get_post_meta($next_ques_id, 'extra_messages', true);
					if(!empty($extra_messages)){
						$meta['extra_messages'] = $extra_messages;
					}*/

					if(!empty($next_ques_id)){

						$htmm = lbb_get_bot_action($next_ques_id);
						$htmm['is_bot_response'] = 1;
						if(!empty($htmm['extra_messages'])){
							$meta['extra_messages'] = $htmm['extra_messages'];
						}
						$meta = !empty($meta) ? maybe_serialize($meta) : maybe_serialize(array());
						
						$qtitle = isset($htmm['title']) ? $htmm['title'] : '';
						if($inserted_id > 0){
							$args = array(
								'conversation_id' => $inserted_id,
								'user_id' => $user_id,
								'message_text' => $qtitle,
								'message_meta' => $meta,
								'sent_time' => gmdate('Y-m-d H:i:s'),
								'is_bot_response' => 1,
							);
							$messageManager = new MessageManager();
						}
				
						//$inserted_id = $messageManager->insertMessage($args);
					}

				}
			}

			if(!empty($htm)){
				$data['conversations'] = array(0 => $htm);
			}else{
				$data['conversations'] = array();
			}

		}

		wp_send_json_success($data);
	    exit;
	}

	public function endConversation(){
		lbb_access_control_allow_origin();

		$general_settings = get_option('lbb_general_settings',array());

		$conversation_id = $_REQUEST['conversation_id'];
		$message = isset($general_settings['livechat_user_message']) ? $general_settings['livechat_user_message'] : "Thank you for contacting us. Resolving your concern is important to us. Due to inactivity, we will have to close out this chat. When it is convenient for you, please initiate another chat session with us. We are here to assist you 24x7.";

		$data = array();

		$data['message'] = $message;

		$conversationM = new ConversationManager();
		$conversationM->endConversation($conversation_id);

		wp_send_json_success($data);
	    exit;
	}

	public function startTrainedAIConversation(){
		lbb_access_control_allow_origin();
		$ip = $_SERVER['REMOTE_ADDR'];
		$chatflow_id = $_REQUEST['chatflow_id'];
		//$message = $_REQUEST['message'];

		$lbb_ai_assistant = get_option('lbb_ai_assistant', array());
		$welcome_message = get_post_meta($chatflow_id, '_trained_ai_welcome_message', true);


		/*$conversation = new ConversationManager();
		$conversationData = $conversation->getConversationByIP($ip,'T', $chatflow_id);*/

		$conversation_cookie_val = !empty($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$conversation_key = 'lbbcf_'.$chatflow_id.'_conversation_id';

		if (isset($_COOKIE[$conversation_key])) {
			$cookie_values = $_COOKIE[$conversation_key];
			$conversationData = getLBBMessages($cookie_values);
		}else{
			$cookie_values = $conversation_cookie_val;
			$conversationData = array();
		}

		

		if(isset($_REQUEST['reset']) && $_REQUEST['reset'] == 1){
			lbb_clear_conversation($conversationData['conversation_id']);

			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversationData['conversation_id'],
				'user_id' => 0,
				'message_text' => $welcome_message,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);

			/*$conversation = new ConversationManager();
			$conversationData = $conversation->getConversationByIP($ip,'T', $chatflow_id);*/

		}

		if(empty($conversationData)){
			$args = array(
				'user_id' => 0,
				'end_time' => '',
				'status' => 'T',
				'chatflow_id' => $chatflow_id
			);
			
			$conversation_id = lbb_save_conversation($args);

			setcookie($conversation_key, $conversation_id, time() + (3600 * 24),'/');

			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => $welcome_message,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);

		}else{
			$conversation_id = $cookie_values;
		}

		$conversations = getLBBMessages($conversation_id);

		$data['conversations'] = array();

		ob_start();
		include(LBB_ABS_URL.'admin/templates/chat/agent-response.php');
		$main_admin_html = ob_get_clean();

		ob_start();
		include(LBB_ABS_URL.'admin/templates/chat/user-response.php');
		$main_user_html = ob_get_clean();
		$data['action_required'] = false;

		foreach ($conversations as $key => $con) {
			$lastMessage = ($con['is_bot_response']) ? 1 : 0;
			if($con['is_bot_response'] == 1){
				//$html = str_replace('{{name}}', 'Bot', $main_admin_html);

				/*$pattern = '/(https?:\/\/[^\s]+)/';
				$replacement = '<a href="$1" class="lbb-trained-openai-link" target="_BLANK">$1</a>';
				$con['message_text'] = preg_replace($pattern, $replacement, $con['message_text'] );*/

				//$con['message_text'] = LBBreplaceLinks($con['message_text']);
				
				$html = str_replace('{{message}}',($con['message_text']), $main_admin_html);
				$previousID = null;

				if ($con['is_bot_response'] !== $previousID) {
					$conversations[$key]['show-author'] = 1;
				}else{
					$conversations[$key]['show-author'] = 0;
				}

				$html = str_replace('{{reply_buttons}}','', $html);
				$html = str_replace('{{skip_buttons}}','', $html);
				$html = str_replace('{{pdf_buttons}}','', $html);
				$conversations[$key]['text'] = $html;
				
			}else{
	
				$html = str_replace('{{message}}',stripslashes($con['message_text']), $main_user_html);
				$conversations[$key]['text'] = $html;
			}

			$previousID = $con['is_bot_response'];
		}
		
		$data['conversations'] = $conversations;

		wp_send_json_success($data);
	    exit;

	}
	public function startAIConversation(){
		lbb_access_control_allow_origin();
		
		$chatflow_id = $_REQUEST['chatflow_id'];
		//$message = $_REQUEST['message'];

		$lbb_ai_assistant = get_option('lbb_ai_assistant', array());

		$ai_welcome_message = get_post_meta($chatflow_id, '_ai_welcome_message', true);
		$welcome_message = !empty($ai_welcome_message) ? $ai_welcome_message : 'Welcome to AI System';

		//$conversation = new ConversationManager();
		//$conversationData = $conversation->getConversationByIP($ip);

		$conversation_cookie_val = !empty($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$conversation_key = 'lbbcf_'.$chatflow_id.'_conversation_id';

		if (isset($_COOKIE[$conversation_key])) {
			$cookie_values = $_COOKIE[$conversation_key];
			$conversationData = getLBBMessages($cookie_values);
		}else{
			$cookie_values = $conversation_cookie_val;
			$conversationData = array();
		}


		
		if(empty($conversationData)){
			$args = array(
				'user_id' => 0,
				'end_time' => '',
				'status' => 'A',
				'chatflow_id' => $chatflow_id
			);
			
			$conversation_id = lbb_save_conversation($args);

			setcookie($conversation_key, $conversation_id, time() + (3600 * 24),'/');

			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => $welcome_message,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);

		}else{
			$conversation_id = $cookie_values;
		}

		$conversations = getLBBMessages($conversation_id);

		$data['conversations'] = array();

		ob_start();
		include(LBB_ABS_URL.'admin/templates/chat/agent-response.php');
		$main_admin_html = ob_get_clean();

		ob_start();
		include(LBB_ABS_URL.'admin/templates/chat/user-response.php');
		$main_user_html = ob_get_clean();
		$data['action_required'] = false;

		foreach ($conversations as $key => $con) {
			$lastMessage = ($con['is_bot_response']) ? 1 : 0;
			if($con['is_bot_response'] == 1){
				//$html = str_replace('{{name}}', 'Bot', $main_admin_html);
				$html = str_replace('{{message}}',stripslashes($con['message_text']), $main_admin_html);
				$previousID = null;

				if ($con['is_bot_response'] !== $previousID) {
					$conversations[$key]['show-author'] = 1;
				}else{
					$conversations[$key]['show-author'] = 0;
				}

				$html = str_replace('{{reply_buttons}}','', $html);
				$html = str_replace('{{skip_buttons}}','', $html);
				$html = str_replace('{{pdf_buttons}}','', $html);
				$conversations[$key]['text'] = $html;
				
			}else{
	
				$html = str_replace('{{message}}',stripslashes($con['message_text']), $main_user_html);
				$conversations[$key]['text'] = $html;
			}

			$previousID = $con['is_bot_response'];
		}
		
		$data['conversations'] = $conversations;

		wp_send_json_success($data);
	    exit;

	}

	public function submitTrainedAIContact(){

		$name = $_POST['fname'];
		$email = $_POST['email'];
		$chatflow_id = $_POST['chatflow_id'];

		$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

		if($chatflow_type == 'botlivechat'){

			$chatflow_id = (get_post_meta( $chatflow_id, 'lbb_select_trained_bot', true ))? get_post_meta( $chatflow_id, 'lbb_select_trained_bot', true ) : '';
		}

		$admin_emails = get_post_meta($chatflow_id, '_lbb_ai_admin_email', true);
		$subject = get_post_meta($chatflow_id, '_lbb_ai_email_subject', true);
		$body = get_post_meta($chatflow_id, '_lbb_ai_email_body', true);

		//$subject = 'You got new message from Trained Ai';
		//$body = 'Hi Admin, First: %name% and email : %email%';

		$headers = array('From: '.$name.' <'.$email.'>','Content-Type: text/html; charset=UTF-8');

		//$admin_emails = 'mariatheithub@gmail.com';
		
		$placeholders = [
			'%name%' => $name,
			'%FIRST_NAME%' => $name,
			'%first_name%' => $name,
			'%FIRSTNAME%' => $name,
			'%firstname%' => $name,
			'%NAME%' => $name,
			'%email%' => $email,
			'%EMAIL%' => $email,
		];
		
		$body = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
			$placeholder = $match[0];
			return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
		}, $body);

		$subject = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
			$placeholder = $match[0];
			return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
		}, $subject);

		lbb_send_mail($admin_emails, $subject, $body, $headers);

		$message_ddata = get_option('lbb_message_data', array());

		$lbb_thank_you_message = isset($message_ddata['lbb_thank_you_message']) ? $message_ddata['lbb_thank_you_message'] : 'Thank you for submitting your contact details';

		$ajaxResponse = array(
			'status' => 'ok',
			'code' => '',
			'message' => $lbb_thank_you_message,
		);
		
		wp_send_json_success($ajaxResponse);die;
		exit;
		
	}

	public function submittrainedAIReply($force_chatflow_id = 0){

		$chatflow_id = $_REQUEST['chatflow_id'];
		$user_message = $_REQUEST['message'];

		$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

		if($chatflow_type == 'botlivechat'){

			$force_chatflow_id = (get_post_meta( $chatflow_id, 'lbb_select_trained_bot', true ))? get_post_meta( $chatflow_id, 'lbb_select_trained_bot', true ) : '';
		}
		
		/*$conversation = new ConversationManager();
		$conversationData = $conversation->getConversationByIP($ip,'T',$chatflow_id);*/

		if($force_chatflow_id){
			$allow = get_post_meta($force_chatflow_id, 'allow_users_to_contact', true);
		}else{
			$allow = get_post_meta($chatflow_id, 'allow_users_to_contact', true);
		}
		
		
		$conversation_cookie_val = !empty($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$conversation_key = 'lbbcf_'.$chatflow_id.'_conversation_id';

		if (isset($_COOKIE[$conversation_key])) {
			$cookie_values = $_COOKIE[$conversation_key];
			$conversationData = getLBBMessages($cookie_values);
			$conversation_id = $cookie_values;
		}else{
			$cookie_values = $conversation_cookie_val;
			$conversationData = array();
			$conversation_id = 0;
		}

		if($force_chatflow_id){
			$chatflow_id = $force_chatflow_id;
		}

		if(empty($conversationData)){
			$args = array(
				'user_id' => 0,
				'end_time' => '',
				'status' => 'T',
				'chatflow_id' => $chatflow_id
			);
			
			/*$conversation_id = lbb_save_conversation($args);

			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => 'Welcome message',
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);*/

		}else{
			//$conversation_id = $conversationData['conversation_id'];
		}

		global $wpdb;
		$faqs_connected = get_post_meta($chatflow_id, '_lbb_faqs', true);
		$faqs = array();
		if(!empty($faqs_connected)){
			$faq_table = $wpdb->prefix."lbb_faq_master";
			$faqs = $wpdb->get_var("SELECT Answer FROM ".$faq_table." WHERE id IN(".$faqs_connected.") AND question = '".$user_message."'");

			if(!empty($faqs)){

				$ajaxResponse = array(
					'status' => 'ok',
					'run_status' => '',
					'html' => '',
					'object' => $faqs,
					'limit' => $_COOKIE['lbb_'.$chatflow_id.'_ai_response_limit'] + 1
				);

				$messageManager = new MessageManager();
				$args = array(
					'conversation_id' => $conversation_id,
					'user_id' => 0,
					'message_text' => $user_message,
					'sent_time' => date('Y-m-d H:i:s'),
					'is_bot_response' => 0,
				);
				$inserted_id = $messageManager->insertMessage($args);

				
				$messageManager = new MessageManager();
				$args = array(
					'conversation_id' => $conversation_id,
					'user_id' => 0,
					'message_text' => LBBreplaceLinks($faqs),
					'sent_time' => date('Y-m-d H:i:s'),
					'is_bot_response' => 1,
				);
				$inserted_id = $messageManager->insertMessage($args);

				/*$pattern = '/(https?:\/\/[^\s]+)/';
				$replacement = '<a href="$1" class="lbb-trained-openai-link" target="_BLANK">$1</a>';
				$ajaxResponse['object'] = preg_replace($pattern, $replacement, $ajaxResponse['object'] );*/

				$ajaxResponse['object'] = LBBreplaceLinks($ajaxResponse['object']);

				wp_send_json_success($ajaxResponse);
				exit;
			}
		}


		$lbb_ai_assistant = get_option('lbb_ai_assistant', array());
		$api_key = isset($lbb_ai_assistant) ? $lbb_ai_assistant['open_ai_key'] : '';

		$model = get_post_meta($chatflow_id, 'api_model', true);
		$model = !empty($model) ? $model : 'gpt-4o';

		$headers = array(
			"Content-Type: application/json",
			"OpenAI-Beta: assistants=v2",
			"Authorization: Bearer " . $api_key
		);

		$assistant_id = get_post_meta($chatflow_id, '_lbb_assistant_id', true);
		$ai_response_limit = get_post_meta($chatflow_id, '_lbb_ai_reponse_limit', true);

		if($assistant_id == ''){
			//$assistant_id = 'asst_pMtgErbyIUz5hhmyqR6Tomvp';
			setcookie('lbb_'.$chatflow_id.'_assistant_id',$assistant_id , time() + (3600 * 24),'/');
		}else{
			if(isset($_COOKIE['lbb_'.$chatflow_id.'_assistant_id'])){
				if($assistant_id != $_COOKIE['lbb_'.$chatflow_id.'_assistant_id']){
					setcookie('lbb_'.$chatflow_id.'_assistant_id',$$assistant_id , time() + (3600 * 24),'/');
				}
			}else{
				setcookie('lbb_'.$chatflow_id.'_assistant_id',$assistant_id , time() + (3600 * 24),'/');
			}
		}

		$ai_response_limit = !empty($ai_response_limit) ? $ai_response_limit : '10';

		if(!empty($force_chatflow_id)){
			$tmp_chatflow_id = $_REQUEST['chatflow_id'];
		}else{
			$tmp_chatflow_id = $chatflow_id;
		}

		if(!isset($_COOKIE['lbb_'.$tmp_chatflow_id.'_ai_response_limit'])){
			setcookie('lbb_'.$tmp_chatflow_id.'_ai_response_limit', 0, time() + (2 * 24 * 3600),'/');
		}else{
			if($_COOKIE['lbb_'.$tmp_chatflow_id.'_ai_response_limit'] >= $ai_response_limit){

				$message_ddata = get_option('lbb_message_data', array());
				
				$msg = !empty($message_ddata['lbb_have_more_questions']) ? $message_ddata['lbb_have_more_questions'] : 'Sorry, looks like you have more questions on this topic. Please reach out to us below so we can send you a detailed response.';

				if($allow == 'yes'){
					$msg .= '<a href="javascript:void(0);" class="lbb-contact-us-trained-ai">Click here</a>';
				}

				$ajaxResponse = array(
					'status' => 'error',
					'run_status' => 'no_limit',
					'code' => '',
					'message' => $msg
				);
				wp_send_json_success($ajaxResponse);die;
			}
		}
		

		if(!isset($_COOKIE['lbb_'.$chatflow_id.'_thread_id'])){
			$thread = curl_init();
			curl_setopt($thread, CURLOPT_URL, "https://api.openai.com/v1/threads");
			curl_setopt($thread, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($thread, CURLOPT_POST, 1);
			curl_setopt($thread, CURLOPT_HTTPHEADER, $headers);

			$thread_response = curl_exec($thread);
			$thread_data = json_decode($thread_response);
			$thread_id = $thread_data->id;
			setcookie('lbb_'.$chatflow_id.'_thread_id',$thread_id , time() + (3600 * 24),'/');
		}else{
			$thread_id = $_COOKIE['lbb_'.$chatflow_id.'_thread_id'];
		}



		/*$thread = curl_init();
		curl_setopt($thread, CURLOPT_URL, "https://api.openai.com/v1/threads");
		curl_setopt($thread, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($thread, CURLOPT_POST, 1);
		curl_setopt($thread, CURLOPT_HTTPHEADER, $headers);

		$thread_response = curl_exec($thread);
		$thread_data = json_decode($thread_response);
		$thread_id = $thread_data->id;
		
		curl_close($thread);*/

		$message = curl_init();
		curl_setopt($message, CURLOPT_URL, "https://api.openai.com/v1/threads/$thread_id/messages");


		curl_setopt($message, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($message, CURLOPT_POST, 1);
		curl_setopt($message, CURLOPT_HTTPHEADER, $headers);

		$message_data = array(
			"role" => "user",
			"content" => $user_message
		);

		
		

		$message_data = json_encode($message_data);
		curl_setopt($message, CURLOPT_POSTFIELDS, $message_data);

		$message_response = curl_exec($message);


		$run = curl_init();
		curl_setopt($run, CURLOPT_URL, "https://api.openai.com/v1/threads/$thread_id/runs");
		curl_setopt($run, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($run, CURLOPT_POST, 1);
		curl_setopt($run, CURLOPT_HTTPHEADER, $headers);

		$instruction = get_post_meta($chatflow_id, 'main_aiassistant_rules', true);
		$run_data = array(
			"assistant_id" => $assistant_id,
			"instructions" => $instruction
		);

		$run_data = json_encode($run_data);
		curl_setopt($run, CURLOPT_POSTFIELDS, $run_data);

		$run_response = curl_exec($run);
		$run_data = json_decode($run_response);
		$run_id = $run_data->id;

		curl_close($run);

		

		// Function to check run status
		function checkRunStatus($api_key, $thread_id, $run_id) {
			$url = "https://api.openai.com/v1/threads/$thread_id/runs/$run_id";
			
			$headers = array(
				"Content-Type: application/json",
				"OpenAI-Beta: assistants=v2",
				"Authorization: Bearer " . $api_key
			);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$response = curl_exec($ch);
			curl_close($ch);

			$run_status = json_decode($response, true);

			return $run_status["status"];
		}


		/*$run = curl_init();
		curl_setopt($run, CURLOPT_URL, "https://api.openai.com/v1/threads/$thread_id/runs/$run_id");
		curl_setopt($run, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($run, CURLOPT_HTTPHEADER, $headers);

		$run_response = curl_exec($run);

		curl_close($run);*/

		// Wait for the run to complete
		$i = 1;
		$status = '';
		while ($i <= 30) {
			$status = checkRunStatus($api_key, $thread_id, $run_id);
			
			if ($i == 30 || $status == 'completed') {
				break;
			}

			$i++;

			sleep(2);
			
		}

		//echo 'Status : '.$status;

		$messages = curl_init();
		curl_setopt($messages, CURLOPT_URL, "https://api.openai.com/v1/threads/$thread_id/messages");
		curl_setopt($messages, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($messages, CURLOPT_HTTPHEADER, $headers);

		$messages_response = curl_exec($messages);
		$messages_data = json_decode($messages_response, true);

		curl_close($messages);

		/*echo '<pre>';
		print_r($messages_data);
		exit;*/
		
		
		if(isset($messages_data['data']['0']['content'][0]['text']['value'])){
			$reply = $messages_data['data']['0']['content'][0]['text']['value'];

			$message_ddata = get_option('lbb_message_data', array());
				
			$nnmsg = !empty($message_ddata['lbb_ai_timeout_message']) ? $message_ddata['lbb_ai_timeout_message'] : 'It looks like the call timed out. Please try again.';

			$reply = !empty($reply) ? $reply : $nnmsg;

			$reply = LBBreplaceLinks($reply);

			$reply = !empty($reply) ? nl2br(lbb_format_sentence($reply)) : $reply;

			$reply = preg_replace('/【\d+†source】/', '', $reply);
			
			if(!empty($force_chatflow_id)){
				$chatflow_id = $_REQUEST['chatflow_id'];
			}

			$ajaxResponse = array(
				'status' => 'ok',
				'run_status' => $status,
				'html' => '',
				'object' => $reply,
				'limit' => @$_COOKIE['lbb_'.$chatflow_id.'_ai_response_limit'] + 1
			);

			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => $user_message,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 0,
			);
			$inserted_id = $messageManager->insertMessage($args);

			
			$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => $reply,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);
			
			

			wp_send_json_success($ajaxResponse);
			exit;
		}else{
			$ajaxResponse = array(
				'status' => 'error',
				'run_status' => $status,
				'code' => '',
				'message' => 'Error',
				'limit' => $_COOKIE['lbb_'.$chatflow_id.'_ai_response_limit'] + 1
			);
			
			wp_send_json_success($ajaxResponse);die;
			exit;
		}

	}

	public function submitAIReply(){

		lbb_access_control_allow_origin();
		$ip = $_SERVER['REMOTE_ADDR'];
		$chatflow_id = $_REQUEST['chatflow_id'];
		$user_message = $_REQUEST['message'];

		$welcomeMessage = get_post_meta($chatflow_id,'welcome_message',true);

		/*$conversation = new ConversationManager();
		$conversationData = $conversation->getConversationByIP($ip);*/

		$conversation_cookie_val = !empty($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$conversation_key = 'lbbcf_'.$chatflow_id.'_conversation_id';

		if (isset($_COOKIE[$conversation_key])) {
			$cookie_values = $_COOKIE[$conversation_key];
			$conversation_id = $cookie_values;
		}else{
			$cookie_values = $conversation_cookie_val;
			$conversation_id = $cookie_values;
		}


		if(empty($conversationData)){
			/*$args = array(
				'user_id' => 0,
				'end_time' => '',
				'status' => 'A',
			);*/
			
			//$conversation_id = lbb_save_conversation($args);

			/*$messageManager = new MessageManager();
			$args = array(
				'conversation_id' => $conversation_id,
				'user_id' => 0,
				'message_text' => $welcomeMessage,
				'sent_time' => date('Y-m-d H:i:s'),
				'is_bot_response' => 1,
			);
			$inserted_id = $messageManager->insertMessage($args);*/

		}else{
			$conversation_id = $cookie_values;
		}

		$lbb_ai_assistant = get_option('lbb_ai_assistant', array());
		$openai_key = isset($lbb_ai_assistant) ? $lbb_ai_assistant['open_ai_key'] : '';

		$model = get_post_meta($chatflow_id, 'api_model', true);
		$model = !empty($model) ? $model : 'gpt-4o';
		
		$openAi = new LBB_OpenAI($openai_key, $model);
	
		
		$maxtoken = get_post_meta($chatflow_id, 'output_token_limit', true);
		if($maxtoken){
			$openAi->setMaxToken($maxtoken);
		}
		

		$inputtoken = get_post_meta($chatflow_id, 'input_token_limit', true);
		if(empty($inputtoken)){
			$inputtoken = 3600;
		}

		$temperature = get_post_meta($chatflow_id, 'temperature', true);
		if($temperature){
			$openAi->setTemperature($temperature);
		}

		$main_prompt = get_post_meta($chatflow_id, 'aiassistant_main_prompt', true);
		$rules = get_post_meta($chatflow_id, 'aiassistant_rules', true);
		$lang_meta = get_post_meta($chatflow_id, 'ai_assistant_language', true);

		$lang = 'Language is'.$lang_meta;
		$prompt = $main_prompt."\n".$rules."\n".$lang;

		$messageManager = new MessageManager();
		$conversation_msgs = $messageManager->getMessagesByConversationId($conversation_id);
		
		$messages[] = array('role' => 'system', 'content' => $prompt);
		$allMessages = '';
		if(!empty($conversation_msgs)){
			foreach ($conversation_msgs as $key => $message) {
				
				$role = $message['is_bot_response'] ? 'ai' : '';
				
				$allMessages .= $message['message_text'];

				if(strlen($allMessages) > ($inputtoken*2)){
					continue;
				}

				if($role == 'ai'){
					$messages[] = array('role' => 'system', 'content' => $message['message_text']);
				}else{
					$messages[] = array('role' => 'user', 'content' => $message['message_text']);
				}
			}
		}
		
		$messages[] = array('role' => 'user', 'content' => $user_message);
		

		$response = $openAi->sendRequest($messages);

		if(isset($response['error'])){
			$ajaxResponse = array(
				'status' => 'error',
				'code' => $response['error']['code'],
				'message' => $response['error']['message']
			);
			wp_send_json_success($ajaxResponse);die;
		}


		$reply = $response['choices'][0]['message']['content'];

		$messageManager = new MessageManager();
		$args = array(
			'conversation_id' => $conversation_id,
			'user_id' => 0,
			'message_text' => $user_message,
			'sent_time' => date('Y-m-d H:i:s'),
			'is_bot_response' => 0,
		);
		$inserted_id = $messageManager->insertMessage($args);

		$args = array(
			'conversation_id' => $conversation_id,
			'user_id' => 0,
			'message_text' => $reply,
			'sent_time' => date('Y-m-d H:i:s'),
			'is_bot_response' => 1,
		);
		$inserted_id = $messageManager->insertMessage($args);

		$ajaxResponse = array(
			'status' => 'ok',
			'html' => '',
			'object' => $reply
		);

		wp_send_json_success($ajaxResponse);
		exit;
	}

	public function submitUserReply() {

		
		lbb_access_control_allow_origin();

		$data = array(
	        'status' => 'ok',
	        'block' => '',
			'chat_id' => 0
	    );
		
		$conversationId = $_REQUEST['conversation_id'];
		$message = sanitize_text_field($_REQUEST['message']);
		$messageType = isset($_REQUEST['messageType']) ? $_REQUEST['messageType'] : '';
		$curr_ques_id = !empty($_REQUEST['current_action_id']) ? $_REQUEST['current_action_id'] : 0;
		$chatflow_id = !empty($_REQUEST['chatflow_id']) ? $_REQUEST['chatflow_id'] : 0;
		$next_action_id = isset($_REQUEST['next_action_id']) ? $_REQUEST['next_action_id'] : 0;
		$welcome_message = get_post_meta($chatflow_id, 'welcome_message', true);
		$forceType = isset($_REQUEST['forceType']) ? $_REQUEST['forceType'] : 'inline';

		if(empty($conversationId)){
			
			$tmp_conversation = maybe_unserialize(stripslashes($_COOKIE['conversation_tmp_data_'.$chatflow_id]));
			$conversationId = lbb_save_conversation($tmp_conversation);
			$conversation_cookie_key = 'lbbcf_'.$chatflow_id.'_conversation_id';
			setcookie($conversation_cookie_key, $conversationId, time() + (3600 * 24), '/');
			$data['conversation_id'] = $conversationId;

			$parsed_url = wp_parse_url(get_site_url());

			if (isset($parsed_url['host'])) {
				// Use parse_url to extract the domain
				$domain = parse_url($parsed_url['host'], PHP_URL_HOST);
			}

			$unique_number = uniqid();
			$contact = array(
				'conversation_id' => $conversationId,
				'firstname' => 'guest_' . $unique_number,
				'lastname' => '',
				'email' => 'guest_' . $unique_number . '@'.$domain,
				'status' => '0',
				'created_date' => gmdate('Y-m-d')
			);

			lbb_add_contact($contact);

			$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

			if($chatflow_type == 'logicbot' || $chatflow_type == 'botlivechat'){
				$user_id = is_user_logged_in() ? get_current_user_id() : '0';
			
				$welcome_ques_id = get_post_meta($chatflow_id, 'start_action_id', true);
				if(!empty($welcome_ques_id)){
					
					$htm = lbb_get_bot_action($welcome_ques_id);
					$htm['is_bot_response'] = 1;
					$meta = array();
					if(!empty($htm['extra_messages'])){
						$meta['extra_messages'] = $htm['extra_messages'];
					}
					$meta = !empty($meta) ? maybe_serialize($meta) : '';

					if(!empty($htm['title'])){

						if($conversationId > 0){
							$args = array(
								'conversation_id' => $conversationId,
								'user_id' => $user_id,
								'message_text' => $htm['title'],
								'message_meta' => $meta,
								'sent_time' => gmdate('Y-m-d H:i:s'),
								'is_bot_response' => 1,
							);
							$messageManager = new MessageManager();
					
							$inserted_id1 = $messageManager->insertMessage($args);
						}
					}

					$next_ques_id = get_post_meta($welcome_ques_id, 'next_question_id', true);

					$image = get_post_meta($next_ques_id, 'image', true);
					$meta = !empty($image) ? array('image' => $image) : array();
					
					/*$extra_messages = get_post_meta($next_ques_id, 'extra_messages', true);
					if(!empty($extra_messages)){
						$meta['extra_messages'] = $extra_messages;
					}*/

					if(!empty($next_ques_id)){
						$htmm = lbb_get_bot_action($next_ques_id);
						$htmm['is_bot_response'] = 1;
						if(!empty($htmm['extra_messages'])){
							$meta['extra_messages'] = $htmm['extra_messages'];
						}
						$meta = !empty($meta) ? maybe_serialize($meta) : maybe_serialize(array());
						
						$qtitle = isset($htmm['title']) ? $htmm['title'] : '';
						if($conversationId > 0){
							$args = array(
								'conversation_id' => $conversationId,
								'user_id' => $user_id,
								'message_text' => $qtitle,
								'message_meta' => $meta,
								'sent_time' => gmdate('Y-m-d H:i:s'),
								'is_bot_response' => 1,
							);
							$messageManager = new MessageManager();
							$inserted_id2 = $messageManager->insertMessage($args);
						}
				
						//$inserted_id = $messageManager->insertMessage($args);
					}
				}
			}
		}



		if($forceType == 'trainedBot'){

			if (is_connect_to_agent_request($message)) {

				$connecting_message = get_option('lbb_livechat_connecting_message', 'Please wait... connecting to an agent');
				$idle_status = get_post_meta($chatflow_id, 'live_chat_idle_time_enable', true);
				if($idle_status == 'no'){
					$start_time = get_post_meta($chatflow_id, 'start_time', true);
					$end_time = get_post_meta($chatflow_id, 'end_time', true);
	
					$current_time = current_time('H:i', true);
	
					if ($current_time >= $start_time && $current_time <= $end_time) {
						$warning = array(
							'warning' => 1,
							'available' => 1,
							'welcome_message' => $welcome_message,
							'message' => 'Agent is available'
						);
					}else{
						$warning = array(
							'warning' => 1,
							'available' => 0,
							'welcome_message' => $welcome_message,
							'message' => 'Agent is not available'
						);
					}
	
				}else{
					$warning = array(
						'warning' => 1,
						'available' => 1,
						'welcome_message' => $welcome_message,
						'message' => $connecting_message
					);
				}
				
				wp_send_json_success($warning);
				exit;
			}else{

				$cm = new ConversationManager();
				$cm->setTrainedBotConversation($conversationId);
				
				$trained_bot_id = get_post_meta($chatflow_id, 'lbb_select_trained_bot', true);

				$this->submitTrainedAIReply($trained_bot_id);
			}
		}


		$type = get_post_meta($curr_ques_id, 'question_type', true);
		$extra = array();
		if($type == 'date'){
			$format = get_post_meta($curr_ques_id, 'date_format', true);
			$format = !empty($format) ? str_replace('%','',$format) : ''; 
			$extra = array('format' => $format);
		}

		$validate = lbb_question_validate($type,$message,$extra);
		if($messageType != 'back' && $messageType != 'skip'){
			if(isset($validate['error'])){
				wp_send_json_success($validate);
				exit;
			}
		}

		if(isset($_REQUEST['errorMessage']) && $_REQUEST['errorMessage'] != ''){
			$validate = array(
				'error' => 1,
				'welcome_message' => $welcome_message,
				'message' => $_REQUEST['errorMessage']
			);
			wp_send_json_success($validate);
			exit;
		}

		$livechat_status = get_post_meta($chatflow_id, 'livechat_status', true);

		$connecting_message = get_option('lbb_livechat_connecting_message', 'Please wait... connecting to an agent');

		$btn_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

		if ($type == 'email' || !lbbIsAutomationAllow($chatflow_id,$conversationId)) {
			$conversationManager = new ConversationManager();
			$change_status = $conversationManager->setPublishedConversation($conversationId);
		}
		if ($livechat_status == 'yes' && is_connect_to_agent_request($message)) {

			$idle_status = get_post_meta($chatflow_id, 'live_chat_idle_time_enable', true);
			if($idle_status == 'no'){
				$start_time = get_post_meta($chatflow_id, 'start_time', true);
				$end_time = get_post_meta($chatflow_id, 'end_time', true);

				$current_time = current_time('H:i', true);

				if ($current_time >= $start_time && $current_time <= $end_time) {
					$warning = array(
						'warning' => 1,
						'available' => 1,
						'welcome_message' => $welcome_message,
						'message' => 'Agent is available'
					);
				}else{
					$warning = array(
						'warning' => 1,
						'available' => 0,
						'welcome_message' => $welcome_message,
						'message' => 'Agent is not available'
					);
				}

			}else{
				lbb_trigger_email($chatflow_id, $conversationId,array());
				$warning = array(
					'warning' => 1,
					'available' => 1,
					'welcome_message' => $welcome_message,
					'message' => $connecting_message
				);
			}

			
			wp_send_json_success($warning);
	    	exit;
		}elseif ($livechat_status == 'yes' && ($btn_type == 'single' || $btn_type == 'message')){

			$buttons = get_post_meta($curr_ques_id, 'quick_reply_buttons', true);
			$liv_chat = false;
			if(!empty($buttons)){
				foreach ($buttons as $key => $button) {
					if(sanitize_text_field($button['title']) == $message && $button['answer_action'] == 'support'){
						
						lbb_trigger_email($chatflow_id, $conversationId,array());
						$liv_chat = true;break;
					}
				}
			}

			if($liv_chat){
				$idle_status = get_post_meta($chatflow_id, 'live_chat_idle_time_enable', true);
				if($idle_status == 'no'){
					$start_time = get_post_meta($chatflow_id, 'start_time', true);
					$end_time = get_post_meta($chatflow_id, 'end_time', true);

					$current_time = current_time('H:i', true);

					if ($current_time >= $start_time && $current_time <= $end_time) {
						$warning = array(
							'warning' => 1,
							'available' => 1,
							'welcome_message' => $welcome_message,
							'message' => 'Agent is available'
						);
					}else{
						$warning = array(
							'warning' => 1,
							'available' => 0,
							'welcome_message' => $welcome_message,
							'message' => 'Agent is not available'
						);
					}

				}else{
					$warning = array(
						'warning' => 1,
						'available' => 1,
						'welcome_message' => $welcome_message,
						'message' => $connecting_message
					);
				}
				wp_send_json_success($warning);
				exit;
			}
		}

		$outcomes = '';
		if($type == 'single' || $type == 'message'){
			//$outcome_maping = get_post_meta($chatflow_id, 'pdf_outcome_mapping', true);
			$buttons = get_post_meta($curr_ques_id, 'quick_reply_buttons', true);

		
			if(!empty($buttons)){
				foreach ($buttons as $key => $button) {
					$message = stripslashes($message);
					if((sanitize_text_field($button['title']) == $message || $button['title'] == $message) && !empty($button['outcome'])){
						$outcomes = $button['outcome'];	
						break;
					}
				}
			}
		}

		$points = '';
		if($type == 'single' || $type == 'message'){
			$buttons = get_post_meta($curr_ques_id, 'quick_reply_buttons', true);
			if(!empty($buttons)){
				
				foreach ($buttons as $key => $button) {
					$message = stripslashes($message);
					if((sanitize_text_field($button['title']) == $message || $button['title'] == $message) && !empty($button['point'])){
						$points = $button['point'];
						break;
					}
				}
			}
		}
		
		

		/*if(!empty($curr_ques_id) && is_user_logged_in()){
			$user_id = get_current_user_id();
			$field_id = get_post_meta($curr_ques_id, 'save_property', true);

			if(!empty($field_id)){
				update_user_meta($user_id, 'lbb_property_'.$field_id,$message);
			}
		}*/

		if(!empty($curr_ques_id)){
			$field_id = get_post_meta($curr_ques_id, 'save_property', true);
			if(!empty($field_id)){
				$contact_id = lbb_get_contact_id($conversationId);
				lbb_update_contact_meta($contact_id, 'lbb_property_'.$field_id,$message);
			}
		}

	    $messageManager = new MessageManager();
		
		$user_id = is_user_logged_in() ? get_current_user_id() : 0;

		$message_meta = !empty($_REQUEST['message_meta']) ? maybe_serialize($_REQUEST['message_meta']) : '';

		$tags = !empty($_REQUEST['tags']) ? $_REQUEST['tags'] : '';
		$args = array(
		    'conversation_id' => $conversationId,
		    'user_id' => $user_id,
		    'message_text' => $message,
			'message_meta' => $message_meta,
		    'sent_time' => date('Y-m-d H:i:s'),
			'action_id' => $curr_ques_id,
			'tags' => $tags,
			'outcomes' => $outcomes,
			'points' => $points,
		    'is_bot_response' => 0,
		);

		$inserted_id = $messageManager->insertMessage($args);
		$data['chat_id'] = $inserted_id;

		// Fire hook when a message with tags is saved.
		// This allows the GHL bridge to catch tags assigned AFTER the automation trigger fires.
		if ( ! empty( $tags ) ) {
			do_action( 'lbb_message_tags_saved', $conversationId, $tags, $chatflow_id, $curr_ques_id );
		}
		
		$automation_triggered = get_post_meta($chatflow_id, 'automation_triggered', true);

		// Update contact information if found name / email address

		$updateData = array();
		if ($type == 'email') {
			if($message != 'Skip'){
				$updateData['email'] = $message;
				setcookie('lbb_email',$message , time() + (3600 * 24),'/');
				$updateData['status'] = 1;
			}
			
		} elseif ($type == 'name') {
			if($message != 'Skip'){
			$updateData['firstname'] = $message;
			setcookie('lbb_firstname',$message , time() + (3600 * 24),'/');
			}
		}
		
		if (!empty($updateData)) {
			lbb_update_contact($updateData, array('conversation_id' => $conversationId));

			/**
			 * Fires after a contact's name or email is saved/updated from a chatbot reply.
			 *
			 * @since 1.0.0
			 * @param int    $conversationId The conversation ID.
			 * @param array  $updateData     The data that was updated (keys: 'email', 'firstname', 'status').
			 * @param string $type           The question type ('email' or 'name').
			 * @param int    $chatflow_id    The chatflow post ID.
			 */
			do_action( 'lbb_contact_updated', $conversationId, $updateData, $type, $chatflow_id );
		}

		

		if(($type == 'email' && $automation_triggered == 'after_email')){

			$contact_id = lbb_get_contact_id($conversationId);

			$score = lbb_get_score($conversationId);
			$total_points = lbb_get_total_points($conversationId);
			$outcome = lbb_get_selected_outcomes($chatflow_id,$conversationId,$next_action_id);
			$custom_fields = lbb_get_user_custom_fields($contact_id);

			$messages = getLBBMessages($conversationId);

			foreach ($messages as $message) {
				$text = $message['message_text'];
				$meta = maybe_unserialize($message['message_meta']);
				$is_bot = $message['is_bot_response'];

				if($is_bot == 1){
					$text = '<strong>Bot :</strong> '.$text;
				}else if($is_bot == 0 && @$is_agent == 0){
					$text =  '<strong>'.@$data['name'].': </strong>'.$text;

					if(!empty($meta['attachment'])){
						$text .= '<a href="'.$meta['attachment'].'" target="_blank">View</a>';
					}
				}

				$text .= "\n";
			}

			$custom_fields['all_messages'] = $text;

			$extras = array(
				'score' => $score,
				'total_points' => $total_points,
				'outcome' => @$outcome['name'],
				'cfields' => $custom_fields
			);

			$posst_id = lbb_get_chatflow_id_from_action($curr_ques_id);
			if(empty($posst_id)){
				$posst_id = $chatflow_id;
			}
			
			lbbTriggerAutomation($posst_id, $conversationId, $curr_ques_id,$extras);

			/**
			 * Fires after the "after email" automation trigger completes.
			 *
			 * @since 1.0.0
			 * @param int    $chatflow_id     The chatflow post ID.
			 * @param int    $conversationId  The conversation ID.
			 * @param int    $curr_ques_id    The current question/action post ID.
			 * @param array  $extras          Extra data: score, total_points, outcome, cfields.
			 * @param string $type            The question type that triggered this ("email").
			 */
			do_action( 'lbb_after_automation_trigger', $posst_id, $conversationId, $curr_ques_id, $extras, $type );
		}

	    // Return the data in JSON format
	    wp_send_json_success($data);
	    exit;
	}

	public function chatbotAction($c_ques_id = 0, $n_ques_id = 0) {
		/*ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);*/

		lbb_access_control_allow_origin();
		$chatflow_id = isset($_REQUEST['chatflow_id']) ? $_REQUEST['chatflow_id'] : 0;

		if(!$c_ques_id){
			$current_action_id = isset($_REQUEST['current_action_id']) ? $_REQUEST['current_action_id'] : 0;
			$next_action_id = isset($_REQUEST['next_action_id']) ? $_REQUEST['next_action_id'] : 0;
		}else{
			$current_action_id = $c_ques_id;
			$next_action_id = $n_ques_id;
			
		}

		$userReply = isset($_REQUEST['userReply']) ? $_REQUEST['userReply'] : '';
		$messageType = isset($_REQUEST['messageType']) ? $_REQUEST['messageType'] : '';
		$conversation_id = isset($_REQUEST['conversation_id']) ? $_REQUEST['conversation_id'] : '';

		$action_ids = get_post_meta( $chatflow_id, 'action_ids', true );
		$start_action = get_post_meta( $chatflow_id, 'start_action_id', true );
		$action_ids = explode(',', $action_ids);

		if(!$current_action_id){
			$current_action_id = $start_action;
		}

		$nai = 0;
		if(!$next_action_id){
			$next_action_id = $start_action;
		}

		$question_type = get_post_meta($current_action_id, 'question_type', true);

		if($question_type != 'single' && $question_type != 'message'){
			if($messageType != 'back'){
				$next_action_id = get_post_meta($current_action_id, 'next_question_id', true);
				$nai = get_post_meta($current_action_id, 'next_question_id', true);
			}
		}else{
			$buttons = get_post_meta($current_action_id, 'quick_reply_buttons', true);

			if(!empty($buttons)){
				foreach ($buttons as $key => $button) {
					if(!empty($button['map'])){
						if($button['map'] != $next_action_id){
							$nai = $button['map'];
							break;
						}
					}
				}
			}
		}
		
		

		// Check advance logic
		if(!empty($userReply)){
			$branch_logics = maybe_unserialize(get_post_meta( $current_action_id, 'advance_logic', true ));
			$branch_logic = lbb_apply_branch_logic($branch_logics,$userReply, $conversation_id);
			if($branch_logic){
				$next_action_id = $branch_logic;
				$nai = $branch_logic;
			}
		}


		$next_question_type = get_post_meta($next_action_id, 'question_type', true);

		if($next_question_type == 'name' || $next_question_type == 'email'){
			
			$skip_name_email = get_post_meta($chatflow_id, 'skip_name_email', true);
			
			if($skip_name_email == 'yes'){
				
				// Check if DAP logged in user
				$is_skip_name_email = false;
				
				$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
				if( isset($_SESSION["lldocroot"]) && ($_SESSION["lldocroot"] != "") ) {
					$lldocroot = $_SESSION["lldocroot"];
				}

				if(file_exists($lldocroot . "/dap/dap-config.php")) {
					include_once ($lldocroot . "/dap/dap-config.php");
					if( Dap_Session::isLoggedIn() ) {
						$session = Dap_Session::getSession();
						$user = $session->getUser();
						$first_name = $user->getFirst_name();
						$email = $user->getEmail();
						$is_skip_name_email = true;
						$updateData = array();
						$updateData['firstname'] = $first_name;
						$updateData['email'] = $email;
						lbb_update_contact($updateData, array('conversation_id' => $conversation_id));
				  	}
				}

				if($is_skip_name_email){
					$ne_ques_id = get_post_meta($next_action_id, 'next_question_id', true);
					$this->chatbotAction($next_action_id, $ne_ques_id);
				}
			}
		}

		$end_message = '';
		$is_last = lbb_is_last_question($chatflow_id,$next_action_id);


		$is_trigger = get_post_meta($next_action_id, 'trigger_automation', true);

		if($is_last){
			$end_message = lbb_get_message('lbb_conversation_end');;
			$end_message = '';
		}
			$automation_triggered = get_post_meta($chatflow_id, 'automation_triggered', true);

		

		if(($is_last && $automation_triggered == 'after_last_question') || ($is_trigger == 1 && $automation_triggered == 'after_answer_pick')){
			
			$contact_id = lbb_get_contact_id($conversation_id);

			$score = lbb_get_score($conversation_id);
			$total_points = lbb_get_total_points($conversation_id);
			$outcome = lbb_get_selected_outcomes($chatflow_id,$conversation_id,$next_action_id);
			$custom_fields = lbb_get_user_custom_fields($contact_id);

			$messages = getLBBMessages($conversation_id);
			$text = '';
			$finalText = array();
			foreach ($messages as $message) {
				$text = $message['message_text'];
				$meta = maybe_unserialize($message['message_meta']);
				$is_bot = $message['is_bot_response'];

				if($is_bot == 1){
					$text = '<strong>Bot :</strong> '.$text;
				}else if($is_bot == 0 && @$is_agent == 0){
					$text =  '<strong>User: </strong>'.$text;

					if(!empty($meta['attachment'])){
						$text .= ' '.$meta['attachment'];
					}
				}
				

				$finalText[] = strip_tags($text);
			}

			$custom_fields['all_messages'] = $finalText;

			$extras = array(
				'score' => $score,
				'total_points' => $total_points,
				'outcome' => @$outcome['name'],
				'cfields' => $custom_fields
			);
			
			$posst_id = lbb_get_chatflow_id_from_action($current_action_id);
			if(empty($posst_id)){
				$posst_id = $chatflow_id;
			}
			
			lbbTriggerAutomation($posst_id, $conversation_id, @$current_action_id, $extras);

			/**
			 * Fires after the "after last question" or "after answer pick" automation trigger.
			 *
			 * @since 1.0.0
			 * @param int   $chatflow_id     The chatflow post ID.
			 * @param int   $conversation_id The conversation ID.
			 * @param int   $current_action_id The current action post ID.
			 * @param array $extras          Extra data: score, total_points, outcome, cfields.
			 */
			do_action( 'lbb_after_automation_trigger', $posst_id, $conversation_id, @$current_action_id, $extras, 'completion' );

			lbb_trigger_email($chatflow_id, $conversation_id,$extras);

		}

			
		$bot_action = lbb_get_bot_action($next_action_id, $conversation_id);
		
		$next_question_type = get_post_meta($next_action_id, 'question_type', true);

		$contact_id = lbb_get_contact_id($conversation_id);
		$name = lbb_get_contact_name($contact_id);
		$email = lbb_get_contact_email($contact_id);

		if($next_question_type == 'outcome'){
			

			$name = lbb_get_contact_name($contact_id);
			$score = lbb_get_score($conversation_id);
			$email = lbb_get_contact_email($contact_id);

			$range_status = get_post_meta($next_action_id, 'outcome_range_enabled', true);
			$default_outcome = lbb_get_default_outcome_id($chatflow_id);

			
			$outcome_title = '';
			$outcome_content = '';
			$outcome_id = 0;
			if(!empty($default_outcome)){

				
				

				if($range_status == 1){
					$outcome_range = get_post_meta($next_action_id, 'outcome_range', true);
					if(!empty($outcome_range)){
					
						foreach($outcome_range as $range){
							if ($score >= $range['start'] && $score <= $range['end']) {
								$outcome_id = $range['outcome_id'];
								break;
							}
						}
					}
				}else{
					global $wpdb;
					$outcome_ids = $wpdb->get_var("SELECT GROUP_CONCAT(outcomes) FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversation_id." and outcomes <> ''");
					
					
					$max_outcome = lbb_get_max_outcome($outcome_ids);

					if($max_outcome){
						$outcome_id = $max_outcome;
					}
				}
	
				$outcome = lbb_get_outcome($outcome_id);
				
			

				if(!empty($outcome)){
					$outcome_title = $outcome['name'];
					$outcome_content = $outcome['content'];
					if(!empty($outcome['outcome_image'])){
						$outcome_image = '<div><img src="'.$outcome['outcome_image'].'" /></div>';
					}
				}
			}
			
			// Outcome redirect code

			$redirect_status = get_post_meta($next_action_id, '_lbb_outcome_redirect_status', true);

			if($redirect_status){

				$redirect_map = get_post_meta($next_action_id, '_lbb_outcome_redirect', true);

				$desiredUrl = '';
				foreach ($redirect_map as $entry) {
					if ($entry['outcome'] == $outcome_id) {
						$desiredUrl = $entry['url'];
						break;
					}
				}

				if(!empty($desiredUrl)){
					$redirect = array(
						'redirect' => 1,
						'url' => $desiredUrl,
						'outcome' => $outcome_id,
						'message' => 'Redirecting to...'
					);
					wp_send_json_success($redirect);
					exit;
				}
			}
			
			$total_points = lbb_get_total_points($chatflow_id);
			$placeholders = [
				'%name%' => $name,
				'%NAME%' => $name,
				'%FIRST_NAME%' => $name,
				'%first_name%' => $name,
				'%FIRSTNAME%' => $name,
				'%firstname%' => $name,
				'%email%' => $email,
				'%EMAIL%' => $email,
				'%score%' => $score,
				'%SCORE%' => $score,
				'%total_score%' => $total_points,
				'%TOTAL_SCORE%' => $total_points,
				'%outcome%' => $outcome_content,
				'%OUTCOME%' => $outcome_content,
				'%outcome_title%' => $outcome_title,
				'%OUTCOME_TITLE%' => $outcome_title,
				'%outcome_image%' => $outcome_image,
				'%OUTCOME_IMAGE%' => $outcome_image
			];
			
			
			$data_array = lbb_get_contactmeta_mergetag($contact_id);
			if(!empty($data_array)){
				$placeholders = array_merge($placeholders,$data_array);
			}
			

			$bot_action['text'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
				$placeholder = $match[0];
				return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
			}, $bot_action['text']);

			$bot_action['title'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
				$placeholder = $match[0];
				return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
			}, $bot_action['title']);

			if(!empty($bot_action['extra_messages'])){
				foreach ($bot_action['extra_messages'] as $key => $value) {
					
					$bot_action['extra_messages'][$key] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
						$placeholder = $match[0];
						return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
					}, $value);
				}
			}
			

		}else{
			$placeholders = [
				'%name%' => $name,
				'%FIRST_NAME%' => $name,
				'%first_name%' => $name,
				'%FIRSTNAME%' => $name,
				'%firstname%' => $name,
				'%NAME%' => $name,
				'%email%' => $email,
				'%EMAIL%' => $email
			];
			
			$data_array = lbb_get_contactmeta_mergetag($contact_id);
			if(!empty($data_array)){
				$placeholders = array_merge($placeholders,$data_array);
			}

			$bot_action['placeholders'] = $placeholders;

			$bot_action['text'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
				$placeholder = $match[0];
				return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
			}, $bot_action['text']);

			$bot_action['title'] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
				$placeholder = $match[0];
				return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
			}, $bot_action['title']);

			if(!empty($bot_action['extra_messages'])){
				foreach ($bot_action['extra_messages'] as $key => $value) {
					
					$bot_action['extra_messages'][$key] = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
						$placeholder = $match[0];
						return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
					}, $value);
				}
			}
		}

		// Save the bot action in the conversation table	
		$messageManager = new MessageManager();

		$last_bot_message = $messageManager->getLastBotMessageByConversationId($conversation_id);
		$user_id = is_user_logged_in() ? get_current_user_id() : 0;

		if($next_action_id){

			$content = get_the_content(null,false,$next_action_id);
			
			$image = get_post_meta($next_action_id, 'image', true);
			$meta = !empty($image) ? maybe_serialize(array('image' => $image)) : maybe_serialize(array());
			$contact_id = lbb_get_contact_id($conversation_id);
			$name = lbb_get_contact_name($contact_id);
			$email = lbb_get_contact_email($contact_id);

			if(!$last_bot_message){

				$content = get_the_content(null,false,$next_action_id);
				$next_question_type = get_post_meta($next_action_id, 'question_type', true);
				if($next_question_type == 'outcome'){
					
					$name = lbb_get_contact_name($contact_id);
					$email = lbb_get_contact_email($contact_id);
					$score = lbb_get_score($conversation_id);

					$range_status = get_post_meta($next_action_id, 'outcome_range_enabled', true);
					$default_outcome = lbb_get_default_outcome_id($chatflow_id);
					
					$outcome_title = '';
					$outcome_content = '';

					if(!empty($default_outcome)){

						if($range_status == 1){
							$outcome_range = get_post_meta($next_action_id, 'outcome_range', true);
							if(!empty($outcome_range)){
							
								foreach($outcome_range as $range){
									if ($score >= $range['start'] && $score <= $range['end']) {
										$outcome_id = $range['outcome_id'];
										break;
									}
								}
							}
						}else{
							global $wpdb;
							$outcome_ids = $wpdb->get_var("SELECT GROUP_CONCAT(outcomes) FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversation_id." and outcomes <> ''");

							$max_outcome = lbb_get_max_outcome($outcome_ids);
							if($max_outcome){
								$outcome_id = $max_outcome;
							}
						}
			
						$outcome = lbb_get_outcome($outcome_id);
						
						if(!empty($outcome)){
							$outcome_title = $outcome['name'];
							$outcome_content = $outcome['content'];
							if(!empty($outcome['outcome_image'])){
								$outcome_image = '<div><img src="'.$outcome['outcome_image'].'" /></div>';
							}
						}
					}

					$total_points = lbb_get_total_points($chatflow_id);
					$placeholders = [
						'%name%' => $name,
						'%NAME%' => $name,
						'%FIRST_NAME%' => $name,
						'%first_name%' => $name,
						'%FIRSTNAME%' => $name,
						'%firstname%' => $name,
						'%email%' => $email,
						'%EMAIL%' => $email,
						'%score%' => $score,
						'%SCORE%' => $score,
						'%total_score%' => $total_points,
						'%TOTAL_SCORE%' => $total_points,
						'%outcome%' => $outcome_content,
						'%OUTCOME%' => $outcome_content,
						'%outcome_title%' => $outcome_title,
						'%OUTCOME_TITLE%' => $outcome_title,
						'%outcome_image%' => $outcome_image,
						'%OUTCOME_IMAGE%' => $outcome_image,
					];

					$data_array = lbb_get_contactmeta_mergetag($contact_id);
					if(!empty($data_array)){
						$placeholders = array_merge($placeholders,$data_array);
					}

					$content = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
						$placeholder = $match[0];
						return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
					}, $content);

					//$content = lbb_merge_tags($content, $placeholders);
				}else{
					$placeholders = [
						'%name%' => $name,
						'%NAME%' => $name,
						'%FIRST_NAME%' => $name,
						'%first_name%' => $name,
						'%FIRSTNAME%' => $name,
						'%firstname%' => $name,
						'%email%' => $email,
						'%EMAIL%' => $email
					];

					$data_array = lbb_get_contactmeta_mergetag($contact_id);
					if(!empty($data_array)){
						$placeholders = array_merge($placeholders,$data_array);
					}

					$bot_action['placeholders'] = $placeholders;

					$content = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
						$placeholder = $match[0];
						return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
					}, $content);
				}

				$args = array(
					'conversation_id' => $conversation_id,
					'user_id' => $user_id,
					'message_text' => $content,
					'message_meta' => $meta,
					'sent_time' => date('Y-m-d H:i:s'),
					'is_bot_response' => 1,
					'action_id' => $next_action_id
				);

				$inserted_id = $messageManager->insertMessage($args);
			}

			global $wpdb;
			if($conversation_id > 0){
			$prev_id = $wpdb->get_var("SELECT * FROM `".$wpdb->prefix."lbb_messages` WHERE is_bot_response = 1 AND conversation_id = '".		$conversation_id."' order BY message_id DESC");
			}else{
				$prev_id = 0;
			}

			$welcome_ques_id = get_post_meta($chatflow_id, 'start_action_id', true);
			if(!empty($welcome_ques_id)){
				$htmv = lbb_get_bot_action($welcome_ques_id);
				$htmv['is_bot_response'] = 1;
				$metav = array();
				if(!empty($htmv['extra_messages'])){
					$metav['extra_messages'] = $htmv['extra_messages'];
				}
			}

			// Example: Retrieve data from the server-side
			$data = array(
				'status' => 'ok',
				'action_id' => $next_action_id,
				'prev_id' => $prev_id,
				'block' => $bot_action,
				'welcome_block' => $htmv,
				'end' => $end_message
			);

		}else{
			$data = array(
				'status' => 'ok',
				'action_id' => 0,
				'block' => array()
			);
		}

	    // Return the data in JSON format
	    wp_send_json_success($data);
	    exit;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Listbuildingbot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Listbuildingbot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$lbb_gdpr_settings = get_option('lbb_gdpr_settings');
		$gdprStatus = isset($lbb_gdpr_settings['lbb_thirdParty_status']) ? $lbb_gdpr_settings['lbb_thirdParty_status'] : 'yes';
		
		if (defined('WCGD_ASSESTS') && $gdprStatus == 'yes') {
			$asset_url = WCGD_ASSESTS.'';
			$thirdparty_css = $asset_url.'css/';
			$thirdparty_js = $asset_url.'js/';
		}else{
			$asset_url = plugin_dir_url(__FILE__).'includes/';
			$thirdparty_css = $asset_url.'third-party';
			$thirdparty_js = $asset_url.'third-party';
		}

		 $cssLib = array(
			'font-awesome' => array(
				'cdn' => '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
				'local' => $thirdparty_css.'/font-awesome-4.7.0//css/font-awesome.min.css'
			),
			'font-awesome' => array(
				'cdn' => '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
				'local' => $thirdparty_css.'/font-awesome-4.7.0//css/font-awesome.min.css'
			),
			'jquery-ui' => array(
				'cdn' => '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
				'local' => $thirdparty_css.'/jquery-ui.css'
			),
			'emojionearea' => array(
				'cdn' => 'https://cdn.jsdelivr.net/npm/emojionearea@3.4.2/dist/emojionearea.min.css',
				'local' => $thirdparty_css.'/emojionearea.min.css'
			)
		);

		wp_enqueue_style('lbb-awesome', lbbTPStyleRender($cssLib['font-awesome'],$gdprStatus));
		wp_enqueue_style('flatpickr', plugin_dir_url( __FILE__ ) . 'css/flatpickr.min.css', array(), $this->version, 'all' );
		wp_enqueue_style('jquery-ui-theme', lbbTPStyleRender($cssLib['jquery-ui'],$gdprStatus));
		if($gdprStatus != 'yes'){
			wp_enqueue_style('emojionearea', lbbTPStyleRender($cssLib['emojionearea'],$gdprStatus), array(), $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/listbuildingbot-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'chat-fe', LBB_URL.'admin/css/chat-fe.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Listbuildingbot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Listbuildingbot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$this->version = rand(1,1000000);

		$lbb_gdpr_settings = get_option('lbb_gdpr_settings');
		$gdprStatus = isset($lbb_gdpr_settings['lbb_thirdParty_status']) ? $lbb_gdpr_settings['lbb_thirdParty_status'] : 'yes';
		
		if (defined('WCGD_ASSESTS') && $gdprStatus == 'yes') {
			$asset_url = WCGD_ASSESTS.'';
			$thirdparty_css = $asset_url.'css/';
			$thirdparty_js = $asset_url.'js/';
		}else{
			$asset_url = plugin_dir_url(__FILE__).'includes/';
			$thirdparty_css = $asset_url.'third-party';
			$thirdparty_js = $asset_url.'third-party';
		}

		$jsLib = array(
			'emojionearea' => array(
				'cdn' => 'https://cdn.jsdelivr.net/npm/emojionearea@3.4.2/dist/emojionearea.min.js',
				'local' => $thirdparty_css.'/emojionearea.min.js'
			)
		);

		//$this->version = '5113';
		wp_enqueue_script('flatpickr', plugin_dir_url( __FILE__ ) . 'js/flatpickr.js', array( 'jquery' ), $this->version, false );
		if($gdprStatus != 'yes'){
			wp_enqueue_script('emojionearea', lbbTPStyleRender($jsLib['emojionearea'],$gdprStatus), array( 'jquery' ), $this->version, false );
		}
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		//wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '1.12.1', true);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-public.js', array( 'jquery' ), $this->version, false );

		$lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');
		wp_localize_script('listbuildingbot', 'listbuildingbot', array(
			'lbb_current_page' => get_permalink(),
	        'ajax_url' => admin_url('admin-ajax.php'),
	        'nonce' => wp_create_nonce('listbuildingbot_ajax_nonce'),
	        'lbb_livechat_options' => $lbb_livechat_options,
	    ));



	    /*Firebase Chat API*/
		//lbb_firebase_enqueue();

	    $lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

		if ($lbb_livechat_options == 'ajax_based') {
			wp_enqueue_script( 'ajaxbased-chat-custom', plugin_dir_url( __FILE__ ) . 'js/ajaxbased-chat-custom.js', array( 'jquery' ), $this->version, false );

			wp_localize_script('ajaxbased-chat-custom', 'siteConfig', array(
				'lbb_current_page' => get_permalink(),
		        'ajaxurl' => admin_url('admin-ajax.php'),
		        'nonce' => wp_create_nonce('listbuildingbot_ajax_nonce'),
		    ));
		}else{
			lbb_firebase_enqueue();
		}

		


	}

	/**
     * Get Firebase APP Credentials
     */


    public function lbb_auth_current_user() {
    		require_once LBB_ABS_URL.'lib/firebase/db/firebase-db.php';
			
			$users = new Firebase_Users();
			$current_user_chat_key = $users->lbbAuth($_POST['uid']);

			echo json_encode(['token' => $current_user_chat_key]);
			exit;


    }
    public function lbb_check_current_user() {
    	lbb_access_control_allow_origin();
    	
		$lbb_chatflow_id = $_REQUEST['lbb_chatflow_id'];

		$fname = (isset($_REQUEST['fname']) && $_REQUEST['fname'] != '')? $_REQUEST['fname'] : 'Guest User';
		$conversation_chat_cookie_val = (isset($_REQUEST['conversation_chat_cookie_val']) && $_REQUEST['conversation_chat_cookie_val'] != '')? $_REQUEST['conversation_chat_cookie_val'] : '';
		$conversation_cookie_val = (isset($_REQUEST['conversation_cookie_val']) && $_REQUEST['conversation_cookie_val'] != '')? $_REQUEST['conversation_cookie_val'] : '';
		$email = $_REQUEST['email'];

		$phone = (isset($_REQUEST['phone']) && $_REQUEST['phone'] != '')? $_REQUEST['phone'] : '';

		$conversation_chat_id = "lbbcf_".$lbb_chatflow_id."_conversation_chat_id";
		$user = wp_get_current_user();
		$return_data = array();
		/*if( $user->ID ) {	
			$current_user_chat_key = get_user_meta($user->ID, "chat_id", true);
			if($current_user_chat_key === "") {
				//user is logged in before this plugin was installed/configured
				$current_user_chat_key = add_user_to_firebase( $user->ID,$user);
			}

		} else */


		if (isset($_COOKIE[$conversation_chat_id])) {
			$cookie_values = $_COOKIE[$conversation_chat_id];
		}else{
			$cookie_values = $conversation_chat_cookie_val;
		}

		if($cookie_values != '') {
			$current_user_chat_key = sanitize_text_field(wp_unslash($cookie_values));
			$is_existing_user = true;
		} else {
			//User is Annonymus temp user
			//$current_user_chat_key = "-Ne2YUvdhCANJOCjt9wf";

			/*ini_set("display_errors", "1");
			error_reporting(E_ALL);*/

			
			//$name = sanitize_text_field(wp_unslash($_POST["name"]));
			//$email = sanitize_email($_POST["email"]);

			$conversation_key = 'lbbcf_'.$lbb_chatflow_id.'_conversation_id';

			if (isset($_COOKIE[$conversation_chat_id])) {
				$conversation_id = $_COOKIE[$conversation_key];
			}else{
				$conversation_id = $conversation_cookie_val;
			}


			$lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

			if ($lbb_livechat_options == 'ajax_based') {
				$current_user_chat_key = 'lbb-ajax-user-'.time().'-'.rand();
				$token = $current_user_chat_key;
			}else{

				require_once LBB_ABS_URL.'lib/firebase/db/firebase-db.php';
				$users = new Firebase_Users();
				$send_data["name"] = $fname;
				$send_data["email"] = $email;
				$send_data["user_registered"] = current_time("mysql");
				$send_data["message"] = "";

				$current_user_chat_key = $users->insert($send_data);

				$token = $users->lbbAuth($current_user_chat_key);

			}
			
			//$token_content = json_encode(['token' => $token]);

			//setcookie($conversation_chat_id, $current_user_chat_key, time() + (3600 * 24),'/');


			$contact_arg = array(
			    'firstname' => $fname,
			    'email' => $email,
				'phone' => $phone,
				'status' => 1,
			);

			$contact_where = array(
			'conversation_id' => $conversation_id
			);
			
			lbb_update_contact($contact_arg,$contact_where);



			$conversationManager = new ConversationManager();
			$all_conversations = $conversationManager->setUserFirebaseId($current_user_chat_key, $conversation_id);
			$change_status = $conversationManager->setLiveConversation($conversation_id);

			$is_existing_user = false;
	
			
		}

		if (isset($user)) {
			$user_id = $user->ID;
		}else{
			$user_id = 0;
		}


		$welcome_message = get_post_meta($lbb_chatflow_id, 'welcome_message', true);

		
		$lbb_general_settings = get_option('lbb_general_settings', array());

		$welcome_message = !empty($lbb_general_settings['livechat_welcome_message']) ? $lbb_general_settings['livechat_welcome_message'] : 'Welcome to live chat!';
		
		$return_data["id"] = $user->ID;
		$return_data["key"] = $current_user_chat_key;
		$return_data["token"] = $token;
		$return_data["is_existing_user"] = $is_existing_user;
		$return_data["welcome_message"] = $welcome_message;
		$return_data = json_encode( $return_data );

		wp_send_json_success($return_data);
		wp_die();
	}


	/**
     * Get Firebase APP Credentials
     */

    public function GetChatMode() {
    	lbb_access_control_allow_origin();

		$conversation_id = isset($_REQUEST['conversation_cookie_val']) ? $_REQUEST['conversation_cookie_val'] : '';
		$chatflow_id = $_REQUEST['chatflow_id'];

		$chat_mode = 'bot';
		$chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

		if($chatflow_type == 'livechat'){
			$chat_mode = 'live';
		}else if(!empty($conversation_id)){
		    $conversation = new ConversationManager();
		    $res = $conversation->getConversationById($conversation_id);

		    if($res['status'] == 'L'){
		        $chat_mode = 'live';
		    }else if($res['status'] == 'T'){
				$chat_mode = 'trained_ai';
			}
		}

		echo $chat_mode;
		wp_die();
	}

}