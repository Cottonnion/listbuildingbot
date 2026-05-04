<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wickedcoolplugins.com
 * @since      1.0.0
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/admin
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class Listbuildingbot_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this,'adminMenus'));

		add_action('wp_ajax_lbb_add_new_question', array($this,'addNewQuestion'));
		add_action('wp_ajax_lbb_save_chatflow', array($this,'saveChatflow'));
		add_action('wp_ajax_lbb_delete_question', array($this,'deleteQuestion'));
		add_action('wp_ajax_lbb_update_options', array($this,'updateOptions'));
		add_action('wp_ajax_lbb_import_template', array($this,'ImportTemplate'));

		add_action('wp_ajax_lbb_generate_auto_prompt', array($this,'generateAutoPrompt'));

		add_action('admin_bar_menu', array($this,'lbb_admin_bar_notification'), 999);
		//add_action('admin_enqueue_scripts', array($this,'lbb_inline_styles_to_admin'));

		add_action('wp_ajax_lbb_create_ai_bot', array($this,'createAiBot'));
		add_action('wp_ajax_nopriv_lbb_create_ai_bot', array($this,'createAiBot'));

		add_action('wp_ajax_lbb_upload_ass_file', array($this,'uploadAssFile'));
		add_action('wp_ajax_nopriv_lbb_upload_ass_file', array($this,'uploadAssFile'));

		add_action('wp_ajax_lbb_ass_files_save', array($this,'assSaveFile'));
		add_action('wp_ajax_nopriv_lbb_ass_files_save', array($this,'assSaveFile'));

		add_action('wp_ajax_lbb_ass_content_save', array($this,'assUpdateContent'));
		add_action('wp_ajax_nopriv_lbb_ass_content_save', array($this,'assUpdateContent'));

		add_action('wp_ajax_lbb_delete_ass_file', array($this,'assDeleteFile'));
		add_action('wp_ajax_nopriv_lbb_delete_ass_file', array($this,'assDeleteFile'));

		add_action('wp_ajax_lbb_save_faqs', array($this,'saveFaqs'));
		add_action('wp_ajax_nopriv_lbb_save_faqs', array($this,'saveFaqs'));

		add_action('wp_ajax_lbb_faq_add', array($this,'addNewFaqs'));
		add_action('wp_ajax_nopriv_lbb_faq_add', array($this,'addNewFaqs'));
		

		
	}

	public function addNewFaqs(){

		global $wpdb;
		$question = $_POST['question'];
		$answer = $_POST['answer'];
		$id = $wpdb->insert($wpdb->prefix.'lbb_faq_master', array(
			'question' => $question,
			'answer' => $answer
		));

		wp_send_json_success(array('status' => 'ok','qid' => $wpdb->insert_id));
		exit;
	}

	public function saveFaqs(){

		global $wpdb;
		$post = json_decode(file_get_contents("php://input"), true);

		$chatflow_id = $_REQUEST['chatflow_id'];

		
		$ids = array();
		if(!empty($post)){
			
			foreach ($post as $key => $p) {
				
				$result = $wpdb->update($wpdb->prefix.'lbb_faq_master',array(
					'question' => $p['title'],
					'answer' => $p['content']
				) , array('id' => $p['id']));

				$ids[] = $p['id'];

				/*$wpdb->insert($wpdb->prefix.'lbb_faq_master', array(
					'question' => $p['title'],
					'answer' => $p['content']
				));*/
			}
		}

		if(!empty($ids)){
			update_post_meta($chatflow_id, '_lbb_faqs', implode(',',$ids));
		}else{
			update_post_meta($chatflow_id, '_lbb_faqs', '');
		}

	}

	public function assDeleteFile(){

		$chatflow_id = $_REQUEST['chatflow_id'];
		$file_id = $_POST['file_id'];
		


		lbb_delete_assistant_file($file_id);

		$aiassistantmanager= new AiassistantManager();

		$aiassistantmanager->deleteByFileId($file_id,$chatflow_id);
		
		wp_send_json_success(array('status' => 'ok'));

		exit;
	}

	public function assUpdateContent(){
		
		$assistantId = $_REQUEST['ai_assistant_id'];
		$respone_msg = $_REQUEST['respone_msg'];
		$aiassistant_rules = $_REQUEST['aiassistant_rules'];

		$aiassistant_rules = $aiassistant_rules."\n\n"."Important:\nYou are assisting real users with a helpful response. Do not mention anything about the keyword missing in the uploaded files because users are not aware of file upload. That's something users do. That's what admins do in the backend to uploaded data to the custom trained bot.\n\n";

		$aiassistant_rules = $aiassistant_rules . " If you don't find any valid response in the document, then check your database. If you don't find any valid response, respond back with '".$respone_msg."'";

		$content = stripslashes($aiassistant_rules);

		$respone = update_ass_instructions($assistantId,$content);

		echo 'sucessfully updated';
		exit;
	}
	

	public function assSaveFile(){

		ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

		$input = json_decode(file_get_contents("php://input"), true);

		$file_ids = [];
		$chatflow_id = $_REQUEST['chatflow_id'];
	

		foreach ($input as $key => $value) {
			
			$path = $value['path'];
			$filename = $value['filename'];
			$is_update_required = $value['is_update_required'];

			if($is_update_required == 1){


				$file_id = lbb_openai_upload_file($path,true);

				if(isset($file_id['error'])){
					echo json_encode($file_id);
					exit;
				}else{
					$file_id = $file_id['id'];
				}

				$file_ids[] = $file_id;
				if(!empty($file_id)){
					$fileContent = file_get_contents($path);
					$args = array(
						'source' => $path,
						'content' => $fileContent,
						'title' => $filename,
						'description' => '',
						'type' => 'upload',
						'ai_file_id' => $file_id,
					);
					$aiassistantmanager= new AiassistantManager();
					$id = $aiassistantmanager->insert($args);
					$aiassistantmanager->insertMapping($id, $chatflow_id);
				}

			}else{
				$file_ids[] = $value['file_id'];
			}
		}


		$aiassistantmanager = new AiassistantManager();
		$uploads = $aiassistantmanager->loadByMapping($chatflow_id,'website');

		$aiassistantmanager = new AiassistantManager();
		$text = $aiassistantmanager->loadByMapping($chatflow_id,'text');


		if(!empty($uploads)){
			foreach ($uploads as $key => $u) {
				$file_ids[] = $u['ai_file_id'];
			}
		}
	  
		if(!empty($text)){
			foreach ($text as $key => $t) {
				$file_ids[] = $t['ai_file_id'];
			}
		}

		if(!empty($file_ids)){
			$assistantId = get_post_meta($chatflow_id, '_lbb_assistant_id', true);
			if(empty($assistantId)){
				$ai_assistant_id = lbb_openai_create_assistant();
				update_post_meta($chatflow_id, '_lbb_assistant_id', $ai_assistant_id);
			}

			$assistantId = get_post_meta($chatflow_id, '_lbb_assistant_id', true);
			//$assistantId = $_COOKIE['lbb_'.$chatflow_id.'_assistant_id'];
			
			$instruction = get_post_meta($chatflow_id, 'main_aiassistant_rules', true);
			$no_response_msg  = get_post_meta($chatflow_id, 'no_response_msg', true);

			$instruction = $instruction."\n\n"."Important:\nYou are assisting real users with a helpful response. Do not mention anything about the keyword missing in the uploaded files because users are not aware of file upload. That's something users do. That's what admins do in the backend to uploaded data to the custom trained bot.\n\n";

			$aiassistant_rules = $instruction . "If you don't find any valid response in the document, then check your database. If you don't find any valid response, respond back with '".$no_response_msg."'";

			//lbb_openai_upload_file_to_assistant($assistantId,$file_ids, $aiassistant_rules);
			lbb_openai_attach_filestore($assistantId,$file_ids, $aiassistant_rules);
		}


		echo json_encode(array('status' => 'ok'));exit;

	}

	public function uploadAssFile(){
		if (!empty($_FILES['file']['name'])) {
			$file = $_FILES['file'];
	
			// Customize the upload folder path
			$upload_dir = wp_upload_dir();
			$custom_folder_path = $upload_dir['basedir'] . '/listbuildingbot/assistant-files/';
	
			// Create the custom folder if it doesn't exist
			if (!file_exists($custom_folder_path)) {
				wp_mkdir_p($custom_folder_path);
			}
	
			$file_path = $custom_folder_path . $file['name'];
	
			// Move the uploaded file to the custom folder
			move_uploaded_file($file['tmp_name'], $file_path);
	
			//$file_id = lbb_openai_upload_file($file_path);

			// Send a response
			wp_send_json_success(
				array('status' => 'ok', 'path' => $file_path,'filename' => basename($file['name']))
			);


		} else {
			// No file was uploaded
			wp_send_json_error('No file uploaded');
		}
	}

	public function createAiBot(){
		

		$request_data = file_get_contents('php://input');
		$data = json_decode($request_data, true);

		//$question = array();
		$questions = $data['questions'];

		$ques_count = count($questions) + 1;
		foreach($questions as $key => $question){
			if(!empty($question['answers'])){
				foreach($question['answers'] as $ans_key => $answer){
					if(empty($answer['next_question'])){
						$questions[$key]['answers'][$ans_key]['next_question'] = $ques_count;
					}
				}
			}else if(empty($question['next_question'])){
				$questions[$key]['next_question'] = $ques_count;
			}else{

			}
		}

		
		
		if($_REQUEST['add_contact'] == 'true'){
			
			$questions[] = array(
				'id' => $ques_count,
				'question' => 'Thank you for your answering these questions! Please let us know the best way to reach out to you with detailed answers: <br></br>What\'s your name?',
				'type' => 'name',
				'next_question' => $ques_count+1
			);

			$questions[] = array(
				'id' => $ques_count+1,
				'question' => 'What\'s the best email address to reach out to you?',
				'type' => 'email',
				'next_question' => $ques_count+2
			);

			if($_REQUEST['add_outcome'] == 'true'){

				if($_REQUEST['outcome_type'] == 'use_ai'){
					$outcomedesc = 'Your outcome is %outcome_title%. <br /><br />%outcome%';
				}else{
					$outcomedesc = 'Your outcome is %outcome_title%.';
					
				}

				$questions[] = array(
					'id' => $ques_count+2,
					'question' => $outcomedesc,
					'type' => 'outcome',
					'next_question' => 0
				);
			}else{
				$questions[] = array(
					'id' => $ques_count+2,
					'question' => 'Thank you so much for taking the time to respond to these questions! We\'ll be in touch! If you have any other questions, please do not hesitate to contact us! ',
					'type' => 'lastmessage',
					'next_question' => 0
				);
			}
			
		}else{
			if($_REQUEST['add_outcome'] == 'true'){

				if($_REQUEST['outcome_type'] == 'use_ai'){
					$outcomedesc = 'Your outcome is %outcome_title%. <br /><br />%outcome%';
				}else{
					$outcomedesc = 'Your outcome is %outcome_title%.';
					
				}
				$questions[] = array(
					'id' => $ques_count,
					'question' => $outcomedesc,
					'type' => 'outcome',
					'next_question' => 0
				);
			}else{
				$questions[] = array(
					'id' => $ques_count,
					'question' => 'Thank you so much for taking the time to respond to these questions! We\'ll be in touch! If you have any other questions, please do not hesitate to contact us! ',
					'type' => 'lastmessage',
					'next_question' => 0
				);
			}
		}	
		
		$page_id = '';
		$page_url = '';
		$page_action = $data['page_action'][0];
		$chatflow_type = $page_action['chatflow_mode'];

		if($chatflow_type == 'popup'){
			$chatflow_type = 'minimized';
		}
		


		if(!empty($page_action['page_name']) && $page_action['chatflow_mode'] == 'popup'){
			/*$page_content = '';

			// Define the page title
			$page_title = $page_action['page_name'];
			
			// Create a new post (page)
			$page = array(
				'post_title'    => $page_title,
				'post_content'  => $page_content,
				'post_status'   => 'publish',
				'post_type'     => 'page',
			);
			
			// Insert the page into the database
			$page_id = wp_insert_post($page);*/
			$page_id = implode(',',$page_action['page_name']);
		}else if(!empty($page_action['page_url']) && $page_action['chatflow_mode'] == 'popup'){
			$page_url = $page_action['page_url'];
        }
		
		if($page_action['chatflow_mode'] == 'popup'){
			$container_width = "450";
		}else{
			$container_width = "700";
		}

		$chatflow = array();
		$chatflow = array(
				    'post' => array(
				        'ID' => 0,
				        'post_title' => 'Created with AI',
				        'post_content' => '',
				        'post_type' => 'lbb-chatflow',
				    ),
				    'postmeta' => array(
				        'start_action_id' => '3273',
			            'action_ids' => '3279,3273,3274,3275,3276,3277,3278',
			            '_questions_drawflow' => '',
			            '_chatflow_type' => 'logicbot',
			            'lbb_total_points' => '0',
			            '_tve_js_modules_gutenberg' => 'a:0:{}',
			            'title_name' => 'Lead Qualification',
			            'lbb_admin_name' => 'Bot',
			            'lbb_chatbot_description' => 'Questions? I\'m here to help!',
			            'livechat_status' => 'yes',
			            'welcome_message' => 'Welcome to live chat!',
			            'live_chat_idle_time_enable' => 'yes',
			            'start_time' => '00:00',
			            'end_time' => '00:00',
			            'how_to_show' => $chatflow_type,
			            'minimized_type' => 'icon',
			            'maximized_chatbot_image' => 'https://newdemo.membershipsitechallenge.com/wp-content/uploads/2023/02/AboutPage-Team_01.jpg',
			            'maximized_chatbot_video' => 'https://newdemo.membershipsitechallenge.com/wp-content/uploads/2023/10/White-Creative-Doodle-Brainstorming-Presentation-4.mp4',
			            'maximized_chatbot_icon' => '',
			            'video_text' => 'How can I help?',
			            'enter_url' => $page_url,
			            'when_to_show' => 'visitor_visit',
			            'page_scroll_value' => '10',
			            'time_input_value' => '10',
			            'who_should_see' => 'all_visitor',
			            'email_capture' => 'just_email',
			            'lbb_notify_chat' => 'no',
			            'admin_emails' => '',
			            'page_load_options' => 'no',
			            'ai_assistant_language' => 'English',
			            'aiassistant_main_prompt' => 'You are Bot and your primary purpose is to assist users in a variety of tasks and offer valuable guidance. Whether it\'s helping users plan their fitness routines or suggesting travel destinations, you\'re equipped to provide insightful assistance.

    When engaging with users, begin by asking them about the specific task they need help with. Depending on their response, you can either follow up with further inquiries to gather more details or provide relevant information to address their request. Keep the conversation engaging by introducing fresh and intriguing insights, avoiding repetitive responses.

    Moreover, always consider the chat\'s history to offer responses that are contextually appropriate. By asking insightful questions and delivering practical tips and advice when it makes sense, you can enhance the overall user experience.
            [aiassistant_rules] => Maintain a respectful and courteous tone in all interactions.

    Avoid expressing personal opinions or preferences; provide objective information only.

    Limit your responses to questions within your instructed topics.

    Decline to respond, share information, or continue the conversation if questions are off-topic.

    Share information solely related to the [[CONTEXT]].

    Refrain from making speculative statements or predictions.

    Provide brief answers without explanations.

    Ensure that all responses are accurate and up-to-date to the best of your knowledge.

    Do not engage in debates or arguments with users; maintain a neutral stance.

    If a question is ambiguous or unclear, request clarification before attempting to answer.

    Respect user privacy and do not request or disclose personal information.

    Notify users if a question falls outside the scope of what can be answered within the instructed topics.

    limit based on token but make sure to complete the sentence',
			            'input_token_limit' => '3400',
			            'output_token_limit' => '600',
			            'limit_threads' => '10000',
			            'api_model' => 'gpt-4o',
			            'allow_embed_domains' => '',
			            'chatbot_style_global_status' => '0',
			            'lbb_container_width' => $container_width,
			            'lbb_common_font_family' => 'Nunito',
			            'lbb_heading_background_color' => '#5865f2',
			            'lbb_image_upload' => 'https://newdemo.membershipsitechallenge.com/wp-content/uploads/2023/10/person-image-3.png',
			            'lbb_container_padding' => '15',
			            'lbb_chatbot_height' => '580',
			            'heading_style_global_status' => '0',
			            'lbb_heading_font_size' => '20',
			            'lbb_heading_font_weight' => '600',
			            'lbb_heading_text_color' => '#fff',
			            'lbb_sub_heading_font_size' => '17',
			            'lbb_sub_heading_font_weight' => '600',
			            'lbb_sub_heading_text_color' => '#ffffff',
			            'question_style_global_status' => '0',
			            'lbb_question_font_size' => '17',
			            'lbb_question_font_weight' => '400',
			            'lbb_question_text_color' => '#282d58',
			            'lbb_question_background_color' => '#f6f7fe',
			            'lbb_question_input_border_color' => '#00ccc8',
			            'lbb_question_image_height' => '140',
			            'user_answer_style_global_status' => '0',
			            'lbb_user_answer_font_size' => '17',
			            'lbb_user_answer_font_weight' => '400',
			            'lbb_user_answer_text_color' => '#0c0c0c',
			            'lbb_user_answer_background_color' => '#00ccc8',
			            'answer_style_global_status' => '0',
			            'lbb_ans_bg_color' => '#00ccc8',
			            'lbb_ans_border_color' => '#00ccc8',
			            'lbb_ans_text_color' => '#ffffff',
			            'lbb_ans_border_radius' => '5',
			            'lbb_ans_font_size' => '16',
			            'lbb_ans_font_weight' => '400',
			            'alignment_style_global_status' => '0',
			            'chat_alignment' => 'left',
			            'lbb_right_spacing' => '20',
			            'lbb_left_spacing' => '20',
			            'lbb_bottom_spacing' => '20',
			            'expanded_style_global_status' => '0',
			            'lbb_max_border_color' => '#607D8B',
			            'max_border_width' => '5',
			            'lbb_max_border_radius' => '15',
			            'lbb_max_width' => '100',
			            'lbb_max_height' => '150',
			            'lbb_max_bg_color' => 'rgba(0,0,0,0.3)',
			            'lbb_background_style_global_status' => '0',
			            'lbb_style_chatbot_background' => 'color',
			            'lbb_chat_background_color' => '#ffffff',
			            'lbb_chat_background_image' => 'https://newdemo.membershipsitechallenge.com/wp-content/uploads/2023/10/demo.jpg',
			            'lbb_chat_background_video' => 'https://newdemo.membershipsitechallenge.com/wp-content/uploads/2023/10/business_-_107425-540p.mp4',
			            'lbb_icon_background_color' => '#00ccc8',
			            'lbb_icon_border_radius' => '100',
			            'lbb_icon_size' => '40',
			            'lbb_icon_box_size' => '40',
			            'lbb_icon_padding' => '15',
			            'automation_triggered' => 'after_email',
			            'selected_url' => $page_id,
			            'lbb_back_button' => 'yes',
			            'lbb_container_image_width' => '40',
			            'shadow_style_global_status' => '0',
			            'lbb_shadow_spread_radius' => '-5',
			            'lbb_shadow_blur_radius' => '18',
			            'lbb_shadow_horizontal_length' => '0',
			            'lbb_shadow_vertical_length' => '0',
			            'lbb_shadow_background_color' => '#949494',
			            '_lbb_email_notification_status' => 'no',
			            '_lbb_email_admin_name' => '',
			            '_lbb_user_email_subject' => 'Thank you for visting our site!',
			            '_lbb_user_email_body' => "<p>Hi %NAME%!</p><p>Thank you for visiting our site!<br>Here's a detailed report with the chat message:<br>%all_messages%</p>",
			            '_lbb_email_admin_notification_status' => 'no',
			            '_lbb_admin_emails' => '',
			            '_lbb_admin_email_subject' => 'This is a copy of the email sent to your users!',
			            'lbb_made_with' => 'yes',
			            'lbb_made_with_text' => 'Made with LBB',
			            'lbb_made_with_link' => 'https://ListBuildingBot.com',
			            'lbb_made_with_hover_text' => 'Made with ListBuildingBot.com',
			            'lbb_ans_image_height' => '60',
			            'lbb_ans_image_object_fit' => 'contain',
			            'lbb_ans_button_row_column' => '33.33%',
			            'lbb_livechat_admin_name' => 'Agent',
			            'lbb_livechat_exact_match' => 'exact_match',
			            'lbb_enable_search' => 'no',
			            'lbb_show_results' => 'yes',
			            'lbb_how_many' => '5',
			            'lbb_show_text' => 'You can enter whatever you want to search for in the search box below and our bot will help find it for you from our resources collection!',
			            'lbb_minimized_type_option' => 'show_minimized',
			            'lbb_chat_icon_color' => '#ffffff',
			            'lbb_bot_image_select' => 'image-one',
			            'lbb_image_upload_live' => '',
			            'back_style_global_status' => '0',
			            'lbb_back_button_font_size' => '17',
			            'lbb_back_button_font_weight' => '400',
			            'lbb_back_button_text_color' => '#0c0c0c',
			            'lbb_back_button_background_color' => '#eeeeee',
			            'knowledge_style_global_status' => '0',
			            'lbb_knowledge_background_color' => '#00ccc8',
			            'lbb_knowledge_active_background_color' => '#6ddad8',
			            'lbb_knowledge_active_color' => '#ffffff',
			            'lbb_knowledge_text_color' => '#ffffff',
			            'other_style_global_status' => '0',
			            'lbb_last_chatted_text_color' => '#9c9c9c',
				    ),
				    'questions' => array(),
				);
		

		$new_question['ID'] = 1;
		$new_question['post_title'] = 'Welcome';
		$new_question['post_content'] = !empty($data['welcome_message']['message']) ? $data['welcome_message']['message'] : 'Welcome to List Building Bot!';
		$new_question['post_type'] = 'lbb-chatflow-action';
		$new_question['question_postmeta'] = array(
													'question_type' => 'welcome',
													'next_question_id' => 2
												);
		$chatflow['questions'][] = $new_question;

		//echo '<pre>';print_r($questions);

		$c = 2;

		/*echo '<pre>';
		print_r($questions);
		exit;*/
		
		
		foreach ($questions as $question) {

			$answer_data = array();
			if(!empty($question['answers'])){
				foreach($question['answers'] as $answer){
					$randomNumber = rand(0, PHP_INT_MAX);
	             	$randomNumber = 'id-'.$randomNumber;

	             	if(!empty($answer['next_question'])){
	             		$next_question = ((int) $answer['next_question']) + 1;
	             	}else{
	             		$next_question = 0;
	             	}

	             	if(isset($answer['outcome'])){
				    	$answer_data[] = array(
					        'id' => $randomNumber,
					        'title' => $answer['text'],
					        'map' => $next_question,
					        'outcome' => $answer['outcome']
					    );
					}else if(isset($answer['outcome_title'])){
						$answer_data[] = array(
					        'id' => $randomNumber,
					        'title' => $answer['text'],
					        'map' => $next_question,
					        'outcome' => $answer['outcome_title'],
					    );

				    }else{
				    	$answer_data[] = array(
					        'id' => $randomNumber,
					        'title' => $answer['text'],
					        'map' => $next_question,
					    );
				    }

				    $question_type = 'text';

					if(!empty($question['next_question'])){
		         		$next_question_id = $question['next_question'] + 1;
		         	}else{
		         		$next_question_id = 0;
		         	}

					if(!empty($question['answers'][0]['text'])){
						$question_type = "single";
						$next_question_id = 0;
					}

				}
			}else{
				$question_type = 'text';
				if(!empty($question['next_question'])){
					$next_question_id = $question['next_question']+1;
				}else{
					$next_question_id = 0;
				}
			}
			
			if($question['type'] == 'lastmessage' || $question['type'] == 'name' || $question['type'] == 'email' || $question['type'] == 'outcome'){
				$question_type = $question['type'];
			}

			$answer_data = maybe_serialize($answer_data);

			$new_question = array();
			$new_question['ID'] = $question['id'] + 1;
			$new_question['post_title'] = 'Question '.$c;
			$new_question['post_content'] = $question['question'];
			$new_question['post_type'] = 'lbb-chatflow-action';
			$new_question['question_postmeta'] = array(
													'question_type' => $question_type,
													'quick_reply_buttons' => $answer_data,
													'next_question_id' => $next_question_id
												);
			$chatflow['questions'][] = $new_question;
			$c++;
		}



		$drawflow = new stdClass();
		$drawflow->drawflow = new stdClass();
		$drawflow->drawflow->Home = new stdClass();
		$drawflow->drawflow->Home->data = new stdClass();

		$question_temp = array();
		$question_temp['id'] = 1;
		$question_temp['name'] = 'multiple';
		$question_temp['pos_x'] = (1 * 300);
		$question_temp['pos_y'] = 243;
		$question_temp['inputs'] = array();
		$question_temp['outputs'] = array();
		$question_temp['class'] = 'multiple';
		$question_temp['html'] = '';
		$question_temp['typenode'] = false;

		$question_temp['outputs'] = new stdClass();
		$question_temp['outputs']->output_1 = new stdClass();
		$question_temp['outputs']->output_1->connections = array();
		$question_temp['outputs']->output_1->connections[0] = (object) array(
																	'node' => 2,
																	'output' => 'input_1',
																	);

		$question_temp['data'] = new stdClass();
		$question_temp['data']->question_id = 1;

		$drawflow->drawflow->Home->data->{1} = $question_temp;

		foreach($questions as $key => $question){
			$question_temp = array();
			$question_temp['id'] = $question['id'] + 1;
			$question_temp['name'] = 'multiple';
			$question_temp['pos_x'] = ($question['id'] + 1) * 300;
			$question_temp['pos_y'] = 243;
			$question_temp['inputs'] = array();
			$question_temp['outputs'] = array();
			$question_temp['class'] = 'multiple';
			$question_temp['html'] = '';
			$question_temp['typenode'] = false;

			
			$question_temp['outputs'] = new stdClass();
			$i=0;
			if(!empty($question['answers'])){
				
				foreach($question['answers'] as $answer){
					if(!empty($answer['next_question'])){
						$question_temp['outputs']->{"output_" . ($i + 1)} = new stdClass();
						$question_temp['outputs']->{"output_" . ($i + 1)}->connections = array();
						$question_temp['outputs']->{"output_" . ($i + 1)}->connections[$i] = (object) array(
																				'node' => $answer['next_question'] + 1,
																				'output' => 'input_1',
																				);
					}
					$i++;
				}
			}else{
				$question_temp['outputs']->{"output_" . ($i + 1)} = new stdClass();
				$question_temp['outputs']->{"output_" . ($i + 1)}->connections = array();
				$question_temp['outputs']->{"output_" . ($i + 1)}->connections[$i] = (object) array(
																				'node' => $question['next_question'] + 1,
																				'output' => 'input_1',
																				);
			}
			
			
			
			

			$question_temp['data'] = new stdClass();
			$question_temp['data']->question_id = $question['id'] + 1;

			$drawflow->drawflow->Home->data->{$key + 2} = $question_temp;
		}



		$drawflow->drawflow->Home->data->{'2'}['inputs'] = new stdClass();
		$drawflow->drawflow->Home->data->{'2'}['inputs']->{"input_1"} = new stdClass();
		$drawflow->drawflow->Home->data->{'2'}['inputs']->{"input_1"}->connections = array();
		$drawflow->drawflow->Home->data->{'2'}['inputs']->{"input_1" }->connections[0] = (object) array(
																	'node' => 1,
																	'input' => 'output_1'
																	);

		foreach($questions as $key => $question){
			if($key == 0){
				$drawflow->drawflow->Home->data->{$key + 1}['inputs'] = array();
			}else{
				$outputs = $drawflow->drawflow->Home->data->{$key + 1}['outputs'];
				
				
				//echo '<pre>';print_r($outputs->output_1);exit;
				$okey = 0;	
				
				
				foreach($outputs as $outkey => $output){
					$connections = $output->connections;
					$output_no = str_replace('output_','',$outkey);
					$ckey = 0;
					foreach($connections as $connection){
						$input_id = $connection->node;
						$input_no = $connection->output;
						//$drawflow->drawflow->Home->data->{$input_id}['inputs'][] = $key + 1;

						
						
						if(!empty($drawflow->drawflow->Home->data->{$input_id}['inputs'])){

							$cnt = count($drawflow->drawflow->Home->data->{$input_id}['inputs']->{'input_1'}->connections);

							$drawflow->drawflow->Home->data->{$input_id}['inputs']->{'input_1'}->connections[] = (object) array(
																					'node' => $key + 1,
																					'input' => 'output_'.$output_no,
																					);
						}else{
							
							$drawflow->drawflow->Home->data->{$input_id}['inputs'] = new stdClass();
							$drawflow->drawflow->Home->data->{$input_id}['inputs']->{'input_1'} = new stdClass();
							$drawflow->drawflow->Home->data->{$input_id}['inputs']->{'input_1'}->connections = array();
							$drawflow->drawflow->Home->data->{$input_id}['inputs']->{'input_1'}->connections[$ckey] = (object) array(
																						'node' => $key + 1,
																						'input' => 'output_'.$output_no,
																						);
						}
						

						$ckey++;
					}
					$okey++;
				}
			}
		}

		
		foreach ($drawflow->drawflow->Home->data as &$node) {
			
			foreach ($node['outputs'] as $key => &$value) {
				
				$value->connections = (array) $value->connections;
				if(!empty($value->connections)){
					$value->connections = array_values($value->connections);
				}
			}
		}

		
		
		if(isset($data['outcome_data'])){
			$chatflow['outcome_data'] = $data['outcome_data'];
		}

		$chatflow['postmeta']['_questions_drawflow'] = json_encode($drawflow);

		$data = lbb_importJson($chatflow, 'noDecode');

		if($page_id != ''){
			$data['page_link'] = get_permalink($page_id);
		}else if($page_url != ''){
			$data['page_link'] = $page_url;
		}else{
			$data['page_link'] = '';
		}

		
		
		echo json_encode($data);exit;
		

	}

	/*public function lbb_inline_styles_to_admin() {
		$custom_styles = "
	        #lbb-ab-icon-top span.lbb-ab-icon-btn { font-family: dashicons; font-size: 22px; line-height: 32px; height: 32px; color: #9ca2a7; }
	        #lbb-ab-icon-top .lbb-count-number { position: absolute; top: 2px; right: -1px; background-color: red; color: white; border-radius: 50%; padding: 0; font-size: 11px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; line-height: 1; font-weight: 600; }
	    ";
	    echo '<style type="text/css">' . $custom_styles . '</style>';
	}*/

	public function lbb_admin_bar_notification() {
	    global $wp_admin_bar;

	    $notification_html = '<span id="lbb-ab-icon-top" ><span class="dashicons lbb-ab-icon-btn dashicons-bell" ></span></span><div style="display:none"><audio id="lbb-bell-audio-admin" src="'.LBB_URL.'admin/audio/audio_one.mp3" preload="auto"></audio></div>';

	    $admin_page_url = menu_page_url('listbuildingbot-conversation', false);


	    $wp_admin_bar->add_menu(array(
	        'id'    => 'custom-notification',
			
	        'title' => $notification_html,
	        'href'  => $admin_page_url.'&mode=L', // Replace with your notification page URL.
	    ));
	}



	public function addNewQuestion() {
		// Get input data from JSON payload
		$post = json_decode(file_get_contents("php://input"), true);
	
		// Determine question type (default to 'single' if not provided)
		$type = !empty($post['type']) ? sanitize_text_field($post['type']) : 'single';
		$numb_ques = !empty($post['numb_ques']) ? $post['numb_ques'] : '1';
		
		$title = 'Message '. $numb_ques;
		if($type == 'pdf'){
			$title = 'PDF Question';
			$content = 'Thank you for answering the questions. You can click the button below to download PDF.';
		}else if($type == 'outcome'){
			$title = 'Display Outcome';
			$content = "Your results is given below\n\n%outcome%";
		}else{
			$content = '';
		}

		// Prepare post data
		$args = array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id()
		);
	
		// Create a new question post
		$post_id = LBB_Questions::addNewQuestion($args);
	
		if ($post_id) {
			// Update question type meta
			update_post_meta($post_id, 'question_type', $type);
	
			// Prepare the response data
			$response = array(
				'message' => '',
				'post'    => LBB_Questions::getQuestion($post_id),
				'html'    => getLBBQuestionHTML($post_id)
			);
	
			// Send success response
			wp_send_json_success($response);
		} else {
			// Prepare the error response
			$response = array('message' => 'Error inserting post.');
	
			// Send error response
			wp_send_json_error($response);
		}
	}	

	public function deleteQuestion(){

		$input = json_decode(file_get_contents("php://input"), true);
		if (!$input || !isset($input['question_id'])) {
			wp_send_json_error('Invalid input data.', 422);
		}

		$deleted = LBB_Questions::delete($input['question_id'],true);

		$chatflow_id = $input['chatflow_id'];
		$question_id = $input['question_id'];
		$drawflow = $input['drawflow'];

		if (!empty($drawflow)) {

			unset($drawflow['drawflow']['Home']['data'][0]);
			foreach($drawflow['drawflow']['Home']['data'] as $key => $draw){
				if(isset($drawflow['drawflow']['Home']['data'][$key]['html'])){
					$drawflow['drawflow']['Home']['data'][$key]['html'] = '';
				}
			}
		}

		if($deleted){
			update_post_meta($chatflow_id, '_questions_drawflow', json_encode($drawflow));
			$question_ids = get_post_meta($chatflow_id, 'action_ids', true);

			if($question_ids){
				$ids = explode(',',$question_ids);
			}else{
				$ids = array();
			}
			
			$key = array_search($question_id, $ids);
			if ($key !== false) {
				unset($ids[$key]);
				// Re-index the array if needed
				$ids = array_values($ids);
				update_post_meta($chatflow_id, 'action_ids', implode(',',$ids));
			}
			
		}
		
		wp_send_json_success('Question deleted successfully.');

	}

	public function updateOptions(){

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			wp_send_json_error('Invalid request.', 400);
		}

		$input = json_decode(file_get_contents("php://input"), true);

		if(!empty($input)){
			foreach($input as $key => $option){
				update_option($key, $option);
			}
		}

		wp_send_json_success('Options successfully.');
		
	}

	public function generateAutoPrompt(){
		
		$search = $_REQUEST['topic'];

		$ai_assistant = get_option('lbb_ai_assistant', '');
		$openai_key = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
		$model = !empty($model) ? $model : 'gpt-4o-mini';
		$openAi = new LBB_OpenAI($openai_key, $model);

		$prompt = lbb_get_prompt('generate_auto_prompt');
		$prompt = str_replace('{{topic}}', $search , $prompt);
		$messages[] = array('role' => 'system', 'content' => 'You are a helpful assistant.');
		$messages[] = array('role' => 'user', 'content' => $prompt);

		$response = $openAi->sendRequest($messages);

		if(isset($response['error'])){
			$ajaxResponse = array(
				'status' => 'error',
				'code' => $response['error']['code'],
				'message' => $response['error']['message']
			);
			echo json_encode($ajaxResponse);die;
		}

		$reply = $response['choices'][0]['message']['content'];
		$reply = str_replace('Prompt:','',$reply);
		$reply = trim($reply, "\n");
		/*$reply = str_replace('Prompt: \n','',$reply);
		$reply = str_replace('Prompt:\n\n','',$reply);
		$reply = str_replace('Prompt: \n\n','',$reply);*/

		$ajaxResponse = array(
			'status' => 'ok',
			'html' => '',
			'object' => $reply
		);
		echo json_encode($ajaxResponse);die;
	}

	public function saveChatflow() {

		function searchQuestionByNodeId($drawflow, $id) {
			foreach ($drawflow as $node) {
				if ($node['id'] == $id) {
					return $node['data']['question_id'];
				}
			}
			return null; // If not found
		}

		function searchQuestionById($array, $id) {
			foreach ($array as $question) {
				if ($question['id'] == $id) {
					return $question;
				}
			}
			return null; // If not found
		}

		function searchKeyByQuestionId($array, $id) {
			foreach ($array as $key => $question) {
				if ($question['id'] == $id) {
					return $key;
				}
			}
			return null; // If not found
		}

		// Ensure the request has data
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			wp_send_json_error('Invalid request.', 400);
		}
	
		// Read and validate input data
		$input = json_decode(file_get_contents("php://input"), true);

		if($input['chatflow_type'] == 'botlivechat' || $input['chatflow_type'] == 'logicbot'){

			if (!$input || !isset($input['chatflow_id']) || !isset($input['drawflow']) || !isset($input['questions']) || !isset($input['settings'])) {
				wp_send_json_error('Invalid input data.', 422);
			}
		
			$chatflow_id = $input['chatflow_id'];
			$chatflow_title = $input['chatflow_title'];
			$drawflow = $input['drawflow'];
			
			$questions = $input['questions'];
			
			$no_button_action = array();
			// Update drawflow data
			if (!empty($drawflow)) {

				unset($drawflow['drawflow']['Home']['data'][0]);
		
				foreach($drawflow['drawflow']['Home']['data'] as $key => $draw){
					if(isset($drawflow['drawflow']['Home']['data'][$key]['html'])){
						$drawflow['drawflow']['Home']['data'][$key]['html'] = '';
					}
				}

				foreach($drawflow['drawflow']['Home']['data'] as $key => $node){
					$nodeOutputs = $node['outputs'];
					$nodeId = $node['id'];
					$question_id = $node['data']['question_id'];

					//$question_id = searchQuestionByNodeId($drawflow['drawflow']['Home']['data'], $nodeId);

					$squestion = searchQuestionById($questions, $question_id);
					$question_key = searchKeyByQuestionId($questions, $question_id);

					if($squestion['type'] == 'single' || $squestion['type'] == 'message'){
						$choices = $squestion['choices'];
						$output_map = array();
						$i = 0;
						foreach($nodeOutputs as $key => $output){

							$output_node_id = isset($output['connections'][0]['node']) ? $output['connections'][0]['node'] : 0;
							$questions[$question_key]['choices'][$i]['map'] = searchQuestionByNodeId($drawflow['drawflow']['Home']['data'], $output_node_id);
							$i++;
						}
					}else{
						$output_map = array();
						
						$i = 0;
						foreach($nodeOutputs as $key => $output){

							$output_node_id = isset($output['connections'][0]['node']) ? $output['connections'][0]['node'] : 0;
							
							$no_button_action[$question_id] = searchQuestionByNodeId($drawflow['drawflow']['Home']['data'], $output_node_id);
							break;
							$i++;
						}
					}
				}

				update_post_meta($chatflow_id, '_questions_drawflow', json_encode($drawflow));
			}
		
			// Update question IDs
			if (!empty($questions)) {

				foreach($questions as $question){
					$args = array(
						'ID' => $question['id'],
						'post_title' => $question['title'],
						'post_content' => $question['content']
					);

					$updated_id = LBB_Questions::update($args);
					if(isset($question['question_type'])){
						update_post_meta($question['id'], 'question_type', $question['question_type']);
					}

					if(isset($question['image'])){
						update_post_meta($question['id'], 'image', $question['image']);
					}

					//if($question['extra_messages']){
						update_post_meta($question['id'], 'extra_messages', $question['extra_messages']);
					//}

					if(!empty($question['choices']) && ($question['type'] == 'single' || $question['type'] == 'message')){
						update_post_meta($question['id'], 'quick_reply_buttons', $question['choices']);
					}else{
						$nona = isset($no_button_action[$question['id']]) ? $no_button_action[$question['id']]  : 0;
						update_post_meta($question['id'], 'next_question_id', $nona);
					}

					if(!empty($question['advance_logic']) && ($question['type'] != 'single' || $question['type'] != 'message')){
						update_post_meta($question['id'], 'advance_logic', $question['advance_logic']);
					}
					
					if(isset($question['save_property'])){
						update_post_meta($question['id'], 'save_property', $question['save_property']);
					}

					if(isset($question['input_select'])){
						update_post_meta($question['id'], 'input_select', $question['input_select']);
					}

					if(isset($question['question_upload_type'])){
						update_post_meta($question['id'], 'question_upload_type', $question['question_upload_type']);
					}

					if(isset($question['maxFileUploadSize'])){
						update_post_meta($question['id'], 'maxFileUploadSize', $question['maxFileUploadSize']);
					}

					if(isset($question['image_answer'])){
						update_post_meta($question['id'], 'image_answer', $question['image_answer']);
					}

					if(isset($question['skip_question'])){
						update_post_meta($question['id'], 'skip_question', $question['skip_question']);
					}

					if(isset($question['trigger_automation'])){
						update_post_meta($question['id'], 'trigger_automation', $question['trigger_automation']);
					}

					if(isset($question['mobile_image_answer'])){
						update_post_meta($question['id'], 'mobile_image_answer', $question['mobile_image_answer']);
					}

					if(isset($question['answer_image_height'])){
						update_post_meta($question['id'], 'answer_image_height', $question['answer_image_height']);
					}

					if(isset($question['answer_image_object_fit'])){
						update_post_meta($question['id'], 'answer_image_object_fit', $question['answer_image_object_fit']);
					}

					if(isset($question['answer_img_button_row_column'])){
						update_post_meta($question['id'], 'answer_img_button_row_column', $question['answer_img_button_row_column']);
					}

					if(isset($question['smart_automation'])){
						update_post_meta($question['id'], 'smart_automation', $question['smart_automation']);
					}

					if(isset($question['date_format'])){
						update_post_meta($question['id'], 'date_format', $question['date_format']);
					}

					if(isset($question['dynamic_message_status'])){
						update_post_meta($question['id'], 'dynamic_message_status', $question['dynamic_message_status']);
					}
					if(isset($question['outcome_range_enabled'])){
						update_post_meta($question['id'], 'outcome_range_enabled', $question['outcome_range_enabled']);
					}

					if(isset($question['dynamic_messages'])){
						update_post_meta($question['id'], 'dynamic_messages', $question['dynamic_messages']);
					}

					if(isset($question['outcome_range'])){
						update_post_meta($question['id'], 'outcome_range', $question['outcome_range']);
					}

					if(isset($question['custom_placeholder'])){
						update_post_meta($question['id'], 'custom_placeholder', $question['custom_placeholder']);
					}

					if(isset($question['funnel_placeholder'])){
						update_post_meta($question['id'], 'funnel_placeholder', $question['funnel_placeholder']);
					}

					if(isset($question['enable_pdf_download'])){
						update_post_meta($question['id'], 'enable_pdf_download', $question['enable_pdf_download']);
					}

					if(isset($question['download_pdf_button'])){
						update_post_meta($question['id'], 'download_pdf_button', $question['download_pdf_button']);
					}

					if(!empty($question['pdfmap']) && $question['type'] == 'outcome'){
						update_post_meta($question['id'], 'pdfmap', $question['pdfmap']);
					}

				}

				$total_points = $input['total_points'];
				update_post_meta($chatflow_id, 'lbb_total_points', $total_points);

				$question_ids = array_map(function ($question) {
					return isset($question['id']) ? $question['id'] : null;
				}, $questions);
		
				$question_ids = array_filter($question_ids); // Remove null values
				
				update_post_meta($chatflow_id, 'action_ids', implode(',', $question_ids));

				$post_data = array(
					'ID'         => $chatflow_id,
					'post_title' => $chatflow_title,
				);

			
				wp_update_post( $post_data );
			}

		}

		$settingsData = $input['settings'];
		$lbb_user_email_body = $input['lbb_user_email_body'];
		$lbb_admin_email_body = $input['lbb_admin_email_body'];
		$lbb_livechat_words = $input['lbb_livechat_words'];
		$video_text = $input['video_text'];
		$lbb_made_with_link = $input['lbb_made_with_link'];
		$lbb_hide_connection_line = $input['lbb_hide_connection_line'];

		
		if(!empty($settingsData)){
			$decodedData = urldecode($settingsData);
			parse_str($decodedData, $dataArray);
			$chatflow_id = $input['chatflow_id'];


			if (!empty($dataArray['lbb_meta']['_lbb_admin_emails'])) {

			    // Split by comma
			    $emails = explode(',', $dataArray['lbb_meta']['_lbb_admin_emails']);

			    $cleanEmails = [];

			    foreach ($emails as $email) {

			        $email = trim($email);

			        // Restore + only inside the local-part (before @)
			        if (strpos($email, '@') !== false) {

			            list($local, $domain) = explode('@', $email, 2);

			            // parse_str converted + → space in local part
			            $local = str_replace(' ', '+', $local);

			            $email = $local . '@' . $domain;
			        }

			        $cleanEmails[] = sanitize_email($email);
			    }

			    $dataArray['lbb_meta']['_lbb_admin_emails'] = implode(',', $cleanEmails);
			}
			
			if(!empty($dataArray['lbb_meta']['selected_url'])){
				$implode_url = implode(',', $dataArray['lbb_meta']['selected_url']);
				$dataArray['lbb_meta']['selected_url'] = $implode_url;
			}else{
				$dataArray['lbb_meta']['selected_url'] = '';
			}

			if(!empty($lbb_livechat_words)){
				$keywordsArray = explode("\n", $lbb_livechat_words);
				update_option( 'lbb_livechat_words', $keywordsArray );
			}
			if(!empty($dataArray['lbb_optionmeta']['lbb_livechat_connecting_message'])){
				update_option( 'lbb_livechat_connecting_message', $dataArray['lbb_optionmeta']['lbb_livechat_connecting_message'] );
			}

			if(!empty($dataArray['lbb_meta']['_lbb_automation_status'])){
				$implode_values = implode(',', $dataArray['lbb_meta']['_lbb_automation_status']);
				$dataArray['lbb_meta']['_lbb_automation_status'] = $implode_values;
			}else{
				$dataArray['lbb_meta']['_lbb_automation_status'] = '';
			}

			if(!empty($dataArray['lbb_meta']['title_name'])){
			      $postTitle = $dataArray['lbb_meta']['title_name'];
			      $post_update = array(
			        'ID'         => $chatflow_id,
			        'post_title' => $postTitle
			      );

			      wp_update_post( $post_update );
		  	}

			save_lbb_settings($dataArray['lbb_meta'], $chatflow_id);

			update_post_meta($chatflow_id, '_lbb_user_email_body', $lbb_user_email_body);
			update_post_meta($chatflow_id, '_lbb_admin_email_body', $lbb_admin_email_body);
			update_post_meta($chatflow_id, 'video_text', $video_text);
			update_post_meta($chatflow_id, 'lbb_made_with_link', $lbb_made_with_link);
			update_post_meta($chatflow_id, 'lbb_hide_connection_line', $lbb_hide_connection_line);
		}  
		
		

		wp_send_json_success('Chatflow data saved successfully.');
	}	

	public function adminMenus(){
		add_menu_page('ListBuildingBot', 'List Building Bot', 'manage_options', 'listbuildingbot', 'lbb_list_chatflows');

		add_submenu_page('listbuildingbot', 'Manage Bot Funnel', 'Manage Bot Funnels', 'manage_options', 'listbuildingbot', 'lbb_list_chatflows');

		add_submenu_page('null', 'Edit Bot Funnel', '', 'manage_options', 'listbuildingbot-edit', 'lbb_admin_edit_page');

		add_submenu_page('listbuildingbot', 'Conversations', 'Conversations', 'manage_options', 'listbuildingbot-conversation', 'lbb_admin_conversation_page');

		add_submenu_page('listbuildingbot', 'Contacts', 'Contacts', 'manage_options', 'listbuildingbot-contacts', 'lbb_admin_contacts_page');

		add_submenu_page('listbuildingbot', 'PDF Builder', 'PDF Builder', 'manage_options', 'listbuildingbot-pdf-builder', 'lbb_admin_pdfbuilder_page');

		add_submenu_page('', 'Create PDF Content', 'Create PDF Content', 'manage_options', 'lbb_create_pdf_content_page', 'lbb_create_pdf_content_page');
		
		/*$edit = add_submenu_page( 'listbuildingbot',
			__( 'Edit', 'listbuildingbot' ),
			__( 'LBB', 'listbuildingbot' ),
			'manage_options',
			'listbuildingbot',
			'lbb_list_chatflows'
		);

		add_action( 'load-' . $edit, 'lbb_load_quiz_admin', 10, 0 );*/

		/*$addnew = add_submenu_page(
			'listbuildingbot', 
			__( 'Add New', 'listbuildingbot' ),
			__( 'Add New', 'listbuildingbot' ),
			'manage_options', 
			'listbuildingbot-new',
			'lbb_admin_add_new_page'
		);*/

		add_submenu_page('listbuildingbot', 'Settings', 'Settings', 'manage_options', 'listbuildingbot-settings', 'lbb_settings');
		/*add_submenu_page('listbuildingbot', 'AI Assistant', 'AI Assistant', 'manage_options', 'listbuildingbot-aiassistant', 'lbb_aiassistant');*/
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$this->version = rand(11111111,999999999);
		wp_enqueue_style('wp-color-picker');
		if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
			wp_enqueue_style('lbb-drawflow', 'https://cdn.jsdelivr.net/gh/jerosoler/Drawflow@0.0.48/dist/drawflow.min.css', array(), '0.0.48', 'all');
			wp_enqueue_style('lbb-datatable', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css', array(), '5.13.0', 'all');
			if ( is_plugin_active( 'mycred/mycred.php' ) ) {
				
			}else{
				wp_enqueue_style( 'lbb-select2-css','//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', false );
			}
			wp_enqueue_style( 'lbb-jquery-ui','//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css', false );
			wp_enqueue_style( 'lbb-drawflow-beautiful', plugin_dir_url( __FILE__ ) . 'css/beautiful.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'lbb-ai', plugin_dir_url( __FILE__ ) . 'css/lbb-ai.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'lbb-chat', plugin_dir_url( __FILE__ ) . 'css/lbb-chat.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'lbb-chat-fe', plugin_dir_url( __FILE__ ) . 'css/chat-fe.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'boxicons',  'https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css', array(), $this->version, 'all' );
			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/listbuildingbot-admin.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'lbb-allpages', plugin_dir_url( __FILE__ ) . 'css/lbb-allpages.css', array(), $this->version, 'all' );
		if(isset($_GET['page']) && $_GET['page'] == 'listbuildingbot-contacts'){
			wp_enqueue_style( 'lbb-contacts', plugin_dir_url( __FILE__ ) . 'css/lbb-contacts.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function all_pages_enqueue_scripts() {
		$this->version = rand(11111111,999999999);
		wp_enqueue_script( 'listbuildingbot-admin-all-pages', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-admin-all-pages.js', array( 'jquery' ), $this->version, false );
		$chat_admin_firebase_id = get_option("lbb_admin_firebase_id"); 
		$admin_config = array(
			"admin_firebase_id"=> $chat_admin_firebase_id,
			"ajaxurl"=> admin_url( 'admin-ajax.php' ),
		);

		wp_localize_script( 'listbuildingbot-admin-all-pages', "adminConfig", $admin_config );

		wp_localize_script('listbuildingbot-admin-all-pages', 'lbb_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'lbb_path' => LBB_URL) ); 


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

	public function enqueue_scripts() {

		$this->version = rand(11111111,999999999);
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_media();
		
		//wp_enqueue_script('lbb-drawflow', 'https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.js', array(), '3.6.0', true);
		
		
		if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
			wp_enqueue_editor();
			wp_enqueue_script('lbb-drawflow', plugin_dir_url( __FILE__ ) . 'js/drawflow.js', array(), '3.6.6', true);
			wp_enqueue_script('lbb-datatable', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), '5.13.0', true);
			wp_enqueue_script( 'listbuildingbot-validate', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-validate.js', array( 'jquery' ), $this->version, false );
		}
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-admin.js', array( 'jquery' ), $this->version, false );
		if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
			
			wp_enqueue_script( 'lbb-ai', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-ai.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'lbb-backend', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-backend.js', array( 'jquery' ), $this->version, false );
		}

		$chat_admin_firebase_id = get_option("lbb_admin_firebase_id"); 
		$admin_config = array(
			"admin_firebase_id"=> $chat_admin_firebase_id,
		);

		wp_localize_script( $this->plugin_name, "adminConfig", $admin_config );

		if(isset($_GET['page']) && $_GET['page'] == 'listbuildingbot-settings'){
			wp_enqueue_script( 'lbb-settings', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-settings.js', array( 'jquery' ), $this->version, false );
		}

		if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
			wp_enqueue_script( 'lbb-common', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-common.js', array( 'jquery' ), $this->version, false );
			if ( is_plugin_active( 'mycred/mycred.php' ) ) {
				
			}else{
				wp_enqueue_script( 'lbb-select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery' ), $this->version, false );
			}
			wp_enqueue_script( 'lbb-sweetalert', '//cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js', array( 'jquery' ), $this->version, false );
		}
		wp_localize_script($this->plugin_name, 'lbb_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'lbb_path' => LBB_URL) ); 
		if(isset($_GET['page']) && $_GET['page'] == 'listbuildingbot-conversation'){
			wp_enqueue_script( 'lbb-conversations', plugin_dir_url( __FILE__ ) . 'js/conversations.js', array( 'jquery' ), $this->version, false );
		}
		if(isset($_GET['page']) && $_GET['page'] == 'listbuildingbot-contacts'){
			wp_enqueue_script( 'lbb-contacts', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-contacts.js', array( 'jquery' ), $this->version, false );
		}
		

		$lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');
		$is_have_any_live_chat = true;
		wp_localize_script('lbb-conversations', 'conversations', array(
	        'lbb_livechat_options' => $lbb_livechat_options,
	    ));

		wp_localize_script('listbuildingbot', 'conversations', array(
	        'lbb_livechat_options' => $lbb_livechat_options,
	    ));


		//wp_enqueue_script( 'listbuildingbot-admin-all-pages', plugin_dir_url( __FILE__ ) . 'js/listbuildingbot-admin-all-pages.js', array( 'jquery' ), $this->version, false );
		$chat_admin_firebase_id = get_option("lbb_admin_firebase_id"); 
		$admin_config = array(
			"admin_firebase_id"=> $chat_admin_firebase_id,
			"ajaxurl"=> admin_url( 'admin-ajax.php' ),
		);

		//wp_localize_script( 'listbuildingbot-admin-all-pages', "adminConfig", $admin_config );

		/*Firebase Chat API*/
		//lbb_firebase_enqueue();

		 $lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

		if ($lbb_livechat_options == 'ajax_based') {

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

	        	/*foreach ($lbb_notify_chat as $key => $value) {
	            	if ($value != 'play_audio') {
	            		$notify_play_audio = false; 
	            	}
	            	if ($value != 'show_popup') {
	            		$notify_notification = false; 
	            	}
	            }*/
	        } 


			$is_wp_admin = is_admin();

	       
	        $conversation_url = admin_url('admin.php?page=listbuildingbot-conversation' );

			



			wp_enqueue_script( 'ajaxbased-chat-custom', LBB_URL . 'public/js/ajaxbased-chat-custom.js', array( 'jquery' ), $this->version, false );


			global $wpdb;
		    $conversation_table = $wpdb->prefix . 'lbb_conversations';
			$conversation_data = $wpdb->get_row("SELECT count(conversation_id) as total_count FROM $conversation_table WHERE status = 'L'" , ARRAY_A);


			$is_have_any_live_chat = ($conversation_data['total_count'] > 0)? true : false;


			wp_localize_script('ajaxbased-chat-custom', 'siteConfig', array(
				'lbb_current_page' => get_permalink(),
		        'ajaxurl' => admin_url('admin-ajax.php'),
		        'nonce' => wp_create_nonce('listbuildingbot_ajax_nonce'),
				"plugin_url"=>LBB_URL,
				"conversationurl"=>$conversation_url,  
				"welcome_message"=>$welcome_message, 
				"notify_play_audio"=>$notify_play_audio, 
				"notify_notification"=>$notify_notification, 
				"is_wp_admin"=>$is_wp_admin, 
				"enable_prompt"=> $prompt,
				"is_have_any_live_chat"=> $is_have_any_live_chat,
				
		    ));
		}else{
			lbb_firebase_enqueue();
		}

	}

	public function ImportTemplate(){
		$template = $_REQUEST['template'];

		//include(LBB_ABS_URL.'/admin/import-demos/'.$template.'.php');

		$importManager = new Listbuildingbot_Import();
		//$data = $importManager->import($template_array);
		$jsonData = file_get_contents(LBB_ABS_URL.'/admin/import-demos/'.$template.'.json');

		$data = lbb_importJson($jsonData,'');

		echo json_encode($data);exit;
	}
}
