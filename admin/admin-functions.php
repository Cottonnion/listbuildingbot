<?php
function lbb_remove_admin_notices_on_custom_page() {
    if (is_admin() && isset($_GET['page']) && ($_GET['page'] === 'listbuildingbot' || $_GET['page'] === 'listbuildingbot-settings' || $_GET['page'] === 'listbuildingbot-conversation' || $_GET['page'] === 'listbuildingbot-contacts' || $_GET['page'] === 'listbuildingbot-pdf-builder' || $_GET['page'] === 'listbuildingbot-aiassistant' || $_GET['page'] === 'lbb_create_pdf_content_page')) {
        remove_all_actions('admin_notices');
    }
}

add_action('admin_init', 'lbb_remove_admin_notices_on_custom_page');

function lbb_list_chatflows(){

  // if(isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])){
  if(isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'create')){
    require_once LBB_ABS_URL . '/admin/pages/edit-chatflow.php';
  }else{
    require_once LBB_ABS_URL . '/admin/pages/chatflows.php';
  }
}

function lbb_settings(){
  require_once LBB_ABS_URL . '/admin/pages/settings.php';
}

function lbb_aiassistant(){
  require_once LBB_ABS_URL . '/admin/pages/aiassistant.php';
}

function lbb_admin_add_new_page(){
  require_once LBB_ABS_URL . '/admin/pages/chatflow.php';
}

function lbb_admin_edit_page(){
  require_once LBB_ABS_URL . '/admin/pages/edit-chatflow.php';
}

function lbb_admin_conversation_page(){
  require_once LBB_ABS_URL . '/admin/pages/conversations.php';
}

function lbb_admin_contacts_page(){
  require_once LBB_ABS_URL . '/admin/pages/contacts.php';
}

function lbb_admin_pdfbuilder_page(){
  require_once LBB_ABS_URL . 'lib/pdf-builder/pages/list.php';
}

function lbb_create_pdf_content_page(){
  require_once LBB_ABS_URL . 'lib/pdf-builder/pages/create-page.php';
}

function add_custom_admin_body_class($classes) {
  $pages = array("listbuildingbot", "listbuildingbot-conversation", "listbuildingbot-settings", "listbuildingbot-contacts", "listbuildingbot-pdf-builder", "lbb_create_pdf_content_page", "listbuildingbot-aiassistant");

  if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], $pages)){
    $classes .= ' lbb-wp-page-screen'; // Add your custom class here
    return $classes;
  }
  return $classes;
}

add_filter('admin_body_class', 'add_custom_admin_body_class');

add_action('wp_ajax_save_action_data', 'save_action_data');
function save_action_data(){
 
  if(isset($_POST["chatflow_title"])){
    $chatflow_title = sanitize_text_field($_POST["chatflow_title"]);
    $admin_name = sanitize_text_field($_POST["admin_name"]);
    $chatbot_description = $_POST["chatbot_description"];
    $chatflow_type = $_POST["chatflow_type"];

    if($_POST['chatflow_type'] == 'trained_ai'){
      $ai_assistant = lbb_openai_create_assistant(true);
      $ai_assistant_id = '';
      if(isset($ai_assistant['error'])){
        $output = $ai_assistant;
        echo json_encode($output);die; 
      }else{
        
        $ai_assistant_id = $ai_assistant['id'];
      }
    }

    $chatflow_id = LBB_ChatFlow::save($chatflow_title);
    
    $args = array(
      'post_title'   => 'Welcome Screen',
      'post_content' => 'How can I help you? 👋',
      'post_status'  => 'publish',
      'post_author'  => get_current_user_id()
    );
  
    $post_id = LBB_Questions::addNewQuestion($args);
    

    if ($post_id && $chatflow_id) {
      update_post_meta($post_id, 'question_type', 'welcome');
      update_post_meta($chatflow_id, 'start_action_id', $post_id);
      update_post_meta($chatflow_id, 'action_ids', $post_id);
      update_post_meta($chatflow_id, 'lbb_admin_name', $admin_name);
      update_post_meta($chatflow_id, 'lbb_chatbot_description', $chatbot_description);

      
      if($_POST['chatflow_type'] == 'trained_ai'){
          //$ai_assistant_id = get_post_meta($chatflow_id, '_lbb_assistant_id', true);
          //if(empty($ai_assistant_id)){
            //$ai_assistant_id = lbb_openai_create_assistant();
            //if(!empty($ai_assistant_id)){
              update_post_meta($chatflow_id, '_lbb_assistant_id', $ai_assistant_id);

                
              
           //}
          //}
      }

      $data = array("drawflow" => array("Home" => array("data" => array(1 => array("id" => 1, "name" => "multiple", "data" => array("question_id" => $post_id ), "class" => "multiple node-question-".$post_id, "html" => "", "typenode" => false, "inputs" => array(), "outputs" => array("output_1" => array("connections" => array() ) ), "pos_x" => 13, "pos_y" => 300 ) ) ) ) );
      update_post_meta($chatflow_id, '_questions_drawflow', json_encode($data));
      update_post_meta($chatflow_id, '_chatflow_type', $chatflow_type);

      $questions_drawflow = get_post_meta($chatflow_id, '_questions_drawflow', true);

      $drawflow = LBB_ChatFlow::getDrawflow($chatflow_id);

      $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
      $questions = array();
      if(!empty($action_ids)){
          $question_ids = explode(',', $action_ids);
          $questions = LBB_Questions::get_questions($question_ids);
      }

      $CFManager = new CustomFieldManager();
      $custom_fields = $CFManager->loadAll();

      $TagManager = new TagsManager();
      $tags = $TagManager->loadAll();

      global $wpdb;
      $outcome_tbl = $wpdb->prefix . 'lbb_outcomes';
      $query = "SELECT * FROM $outcome_tbl WHERE chatflow_id = '".$chatflow_id."'";
      $outcomes = $wpdb->get_results($query, ARRAY_A);

      $pdf_tbl = $wpdb->prefix . 'lbb_pdfbuilder';
      $query = "SELECT * FROM $pdf_tbl";
      $pdfs = $wpdb->get_results($query, ARRAY_A);

    }
  }
  $output = array('chatflow_id' => $chatflow_id, 'questions_drawflow' => $drawflow, 'questions' => $questions, 'custom_fields' => $custom_fields, 'tags' => $tags, 'outcomes' => $outcomes, 'pdfs' => $pdfs,'ai_assistant_id' => $ai_assistant_id);
  echo json_encode($output);die;  
}

add_action('wp_ajax_lbb_fetching_url', 'lbb_fetching_url');
function lbb_fetching_url(){
   include(LBB_ABS_URL.'/lib/ai/listbuildingbot-scrape-url.php');
   exit;
}

add_action('wp_ajax_lbb_fetching_single_url', 'lbb_fetching_single_url');
function lbb_fetching_single_url(){
   include(LBB_ABS_URL.'/lib/ai/listbuildingbot-scrape-single-url.php');
   exit;
}


add_action('wp_ajax_lbb_load_chatflows', 'lbb_load_chatflows');
function lbb_load_chatflows(){
    $args = array(
        'post_type' => 'lbb-chatflow',
        'posts_per_page' => -1
    );
    $obituary_query = new WP_Query($args);
    $output = "";
    if ($obituary_query->have_posts()) {
        $output .= '<option value="">Please select a bot</option>';
        $i=1;
        while ($obituary_query->have_posts()) : $obituary_query->the_post();
            $chatflow_name = "";
            if(get_post_meta(get_the_ID(), '_chatflow_type', true)){
                $chatflow_type = get_post_meta(get_the_ID(), '_chatflow_type', true);
                if($chatflow_type == 'botlivechat' || $chatflow_type == 'logicbot'){
                    $output .= '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
                }
            }
        $i++;
        endwhile;
        wp_reset_postdata();
    }

    echo json_encode($output);die;
}

add_action('wp_ajax_lbb_load_questions', 'lbb_load_questions');
function lbb_load_questions(){
    $chatflow_id = $_POST['chatflow_id']; 
    $output = "";
    if($chatflow_id){
        $start_action_id = get_post_meta($chatflow_id, 'start_action_id', true);
        $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
        $questions = array();
        if(!empty($action_ids)){
            $output .= '<option value="">Please select a message</option>';
            $question_ids = explode(',', $action_ids);
            
            foreach($question_ids as $question_id){
                if($start_action_id != $question_id){
                    $output .= '<option value="'.$question_id.'">'.get_the_title($question_id).'</option>';
                }
            }
        }
    }
    echo json_encode($output);die;
}

add_action('wp_ajax_lbb_ai_text_content', 'lbb_ai_text_content');
function lbb_ai_text_content(){
  $chatflow_id = $_POST['chatflow_id']; 
  $page_text = $_POST['page_text']; 
  $page_name = $_POST['page_name']; 
  $lbb_ai_text_hiden = $_POST['lbb_ai_text_hiden']; 
  $returnData = array();
  // Loop through data


    $filename = sanitize_title($page_name).rand().'.txt';

    $filepath = ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/' . $filename;

    $custom_folder_path = ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/';

    if (!file_exists($custom_folder_path)) {
      wp_mkdir_p($custom_folder_path);
    }

    $file = fopen(ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/' . $filename, 'w');
    fwrite($file, $page_text);
    fclose($file); 

    // Upload file
    require_once(ABSPATH . 'wp-admin/includes/file.php');  
    $upload_file = wp_upload_bits($filename, null, file_get_contents($filepath));

    $file_id = lbb_openai_upload_file($filepath, true);

    if(isset($file_id['error'])){
      echo json_encode($file_id);
      exit;
    }else{
      $file_id = $file_id['id'];
    }

  

    $file_ids = array();
    $file_ids[] = $file_id;
    if (!$upload_file['error']) {

      if ($lbb_ai_text_hiden != '') {


        $aiassistantmanager = new AiassistantManager();
        $text = $aiassistantmanager->loadByMapping($chatflow_id,'text');


      if(!empty($text)){
        foreach ($text as $key => $t) {
          lbb_delete_assistant_file($t['ai_file_id']);
        }
      }


        $args = array(
          'source' => $filepath,
          'content' => $page_text,
          'title' => $page_name,
          'description' => '',
          'type' => 'text',
          'ai_file_id' => $file_id,
        );
        $aiassistantmanager= new AiassistantManager();
        $id = $aiassistantmanager->update($args, $lbb_ai_text_hiden);
        $returnData = $lbb_ai_text_hiden;


        


      }else{
        $args = array(
          'source' => $filepath,
          'content' => $page_text,
          'title' => $page_name,
          'description' => '',
          'type' => 'text',
          'ai_file_id' => $file_id,
        );
        $aiassistantmanager= new AiassistantManager();
        $id = $aiassistantmanager->insert($args);

        $aiassistantmanager->insertMapping($id, $chatflow_id);

        $returnData = $id;
      }

    }


    $aiassistantmanager = new AiassistantManager();
    $uploads = $aiassistantmanager->loadByMapping($chatflow_id,'website');

    $aiassistantmanager = new AiassistantManager();
    $text = $aiassistantmanager->loadByMapping($chatflow_id,'upload');


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
      $instruction = get_post_meta($chatflow_id, 'main_aiassistant_rules', true);
      $file_ids = array_unique($file_ids);

      $instruction = get_post_meta($chatflow_id, 'main_aiassistant_rules', true);
      $no_response_msg  = get_post_meta($chatflow_id, 'no_response_msg', true);

      $instruction = $instruction."\n\n"."Important:\nYou are assisting real users with a helpful response. Do not mention anything about the keyword missing in the uploaded files because users are not aware of file upload. That's something users do. That's what admins do in the backend to uploaded data to the custom trained bot.\n\n";

      $aiassistant_rules = $instruction . " If you don't find any valid response in the document, then check your database. If you don't find any valid response, respond back with '".$no_response_msg."'";

      //lbb_openai_upload_file_to_assistant($assistantId,$file_ids,$aiassistant_rules);
      lbb_openai_attach_filestore($assistantId,$file_ids, $aiassistant_rules);
    }


  echo $returnData;
  exit;

}


add_action('wp_ajax_lbb_upload_document_loop', 'lbb_upload_document_loop');
function lbb_upload_document_loop(){
  $chatflow_id = $_POST['chatflow_id']; 
  $data = $_POST['dataArray']; 
  $returnData = array();
  // Loop through data

  $files = array();


  

  $aiassistantmanager = new AiassistantManager();
  $websites = $aiassistantmanager->loadByMapping($chatflow_id,'website');

  if(!empty($websites)){
    foreach ($websites as $key => $w) {
      $files[] = $w['ai_file_id'];
    }
  }
 
  foreach ($data as $item) {
    $url =  $item['title'];
    $content = $item['content']; 

    $filename = lbb_get_slug_from_url($url);
    $custom_folder_path = ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/';
    $filepath = ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/' . $filename;


    if (!file_exists($custom_folder_path)) {
      wp_mkdir_p($custom_folder_path);
    }

    $file = fopen(ABSPATH . 'wp-content/uploads/listbuildingbot/assistant-files/' . $filename, 'w');
    fwrite($file, $content);
    fclose($file); 

    // Upload file
    require_once(ABSPATH . 'wp-admin/includes/file.php');  
    $upload_file = wp_upload_bits($filename, null, file_get_contents($filepath));

    if (!$upload_file['error']) {
      $file_id = lbb_openai_upload_file($filepath,true);

      if(isset($file_id['error'])){
        echo json_encode($file_id);
        exit;
      }else{
        $file_id = $file_id['id'];
      }
      

      $args = array(
          'source' => $url,
          'content' => $content,
          'title' => '',
          'description' => '',
          'type' => 'website',
          'ai_file_id' => $file_id,
      );
      $aiassistantmanager= new AiassistantManager();
      $id = $aiassistantmanager->insert($args);

      $aiassistantmanager->insertMapping($id, $chatflow_id);
      $returnData[] = $id;

      $files[] = $file_id;

    }

  }

  $aiassistantmanager = new AiassistantManager();
  $uploads = $aiassistantmanager->loadByMapping($chatflow_id,'upload');

  $aiassistantmanager = new AiassistantManager();
  $text = $aiassistantmanager->loadByMapping($chatflow_id,'text');

  if(!empty($uploads)){
    foreach ($uploads as $key => $u) {
      $files[] = $u['ai_file_id'];
    }
  }

  if(!empty($text)){
    foreach ($text as $key => $t) {
      $files[] = $t['ai_file_id'];
    }
  }


  

  if(!empty($files)){
    $assistantId = get_post_meta($chatflow_id, '_lbb_assistant_id', true);

    if(empty($assistantId)){
      $ai_assistant_id = lbb_openai_create_assistant();
      update_post_meta($chatflow_id, '_lbb_assistant_id', $ai_assistant_id);
    }

    $assistantId = get_post_meta($chatflow_id, '_lbb_assistant_id', true);

    $instruction = get_post_meta($chatflow_id, 'main_aiassistant_rules', true);
    $no_response_msg  = get_post_meta($chatflow_id, 'no_response_msg', true);


    $instruction = $instruction."\n\n"."Important:\nYou are assisting real users with a helpful response. Do not mention anything about the keyword missing in the uploaded files because users are not aware of file upload. That's something users do. That's what admins do in the backend to uploaded data to the custom trained bot.\n\n";

    $aiassistant_rules = $instruction . " If you don't find any valid response in the document, then check your database. If you don't find any valid response, respond back with '".$no_response_msg."'";


    //lbb_openai_upload_file_to_assistant($assistantId,$files,$aiassistant_rules);

    lbb_openai_attach_filestore($assistantId,$files, $aiassistant_rules);

  }

  echo json_encode(array('status' => 'ok'));
  exit;

}


function lbb_get_slug_from_url($url) {

  // Parse URL and get host
  $parts = parse_url($url);
  $host = $parts['host'];
  
  // Remove http(s):// and extract hostname 
  $host = str_replace('www.', '', $host);
  $host = str_replace('http://', '', $host);
  $host = str_replace('https://', '', $host);

  // Replace periods with underscores
  $slug = str_replace('.', '_', $host);

  // Append .txt extension 
  $random = time().'-'.rand();
  $slug .= $random.'.txt';
  //$slug .= '.txt';

  return $slug;

}



add_action('wp_ajax_lbb_save_chatflow_postmeta', 'lbb_save_chatflow_postmeta');
function lbb_save_chatflow_postmeta(){
  if(isset($_POST["chatflow_id"])){
    $chatflow_id = $_POST["chatflow_id"];
    $chatflow_options = $_POST["chatflow_options"];
    $target_options = $_POST["target_options"];
    $pages_options = $_POST["pages_options"];

    add_post_meta($chatflow_id, 'chatflow_options', $chatflow_options);
    add_post_meta($chatflow_id, 'target_options', $target_options);
    add_post_meta($chatflow_id, 'pages_options', $pages_options);
    /*$post_title = sanitize_text_field($_POST["post_title"]);
    $chatflow = LBB_ChatFlow::save($post_title);*/
  }
  echo json_encode($chatflow);die;  
}

add_action('wp_ajax_save_chatflow_actions', 'save_chatflow_actions');
function save_chatflow_actions(){
  if(isset($_POST["chatflow_id"])){
    $chatflow_post_title = sanitize_text_field($_POST["chatflow_post_title"]);
    $chatflow_id = sanitize_text_field($_POST["chatflow_id"]);
  }
  echo json_encode($chatflow_id);die;  
}

add_action('wp_ajax_save_form_settings_data', 'save_form_settings_data');
function save_form_settings_data(){
  $settingsData = $_POST['settings'];
  if(!empty($settingsData)){
    $decodedData = urldecode($settingsData);
    parse_str($decodedData, $dataArray);
    if(!empty($dataArray['lbb_meta']['selected_url'])){
      $implode_url = implode(',', $dataArray['lbb_meta']['selected_url']);
      $dataArray['lbb_meta']['selected_url'] = $implode_url;
    }

    if(!empty($dataArray['lbb_meta']['_lbb_automation_status'])){
      $implode_values = implode(',', $dataArray['lbb_meta']['_lbb_automation_status']);
      $dataArray['lbb_meta']['_lbb_automation_status'] = $implode_values;
    }
    save_lbb_settings($dataArray['lbb_meta'], $dataArray['chatflow_id']);
  }    
}

add_action('wp_ajax_save_global_form_settings_data', 'save_global_form_settings_data');
function save_global_form_settings_data(){
    $settingsData = $_POST['settings'];
    if($settingsData){
      $decodedData = urldecode($settingsData);
      parse_str($decodedData, $dataArray);
      foreach($dataArray['lbb_meta'] as $name => $data){
        update_option($name, $data);
      }
    }
    
}

add_action('wp_ajax_save_automation_data', 'save_automation_data');
function save_automation_data(){
    $automationType = $_POST['automation_type'];
    $chatflow_id = $_POST['chatflow_id'];
    $dataArray = $_POST['dataArray'];
    if($automationType == 'webhook'){
      update_post_meta($chatflow_id, 'lbb_automation_webhook', $dataArray);
    }else if($automationType == 'gohighlevel'){
      update_post_meta($chatflow_id, 'lbb_automation_gohighlevel', $dataArray);
    }else if($automationType == 'aweber'){

        include(SMART_AUTOMATION_PATH.'/includes/aweber/aweber_api/aweber_api.php');
        // require_once(plugin_dir_path(__FILE__) . '../plugins/smartautomation/includes/aweber_api/aweber_api.php');
        //$aweber_obj = new SmartAutomation_Aweber();
        update_option('lbb_automation_'.$automationType, $dataArray);

        $dataNew['api_keys'] = $dataArray['api_key'];
        $code = addslashes($dataNew['api_keys']);
        try{
          $credentials = AWeberAPI::getDataFromAweberID($code);
        }
        catch (Exception $e) {
          echo $e->getMessage();
        }
          
        
        $dataNew = explode('|' , $dataNew['api_keys']);
        $data['aweber_consumer_key'] = $credentials[0];
        $data['aweber_consumer_secret'] = $credentials[1];
        $data['aweber_request_token'] = $credentials[2];
        $data['aweber_token_secret'] = $credentials[3];
        

        update_option('lbb_automation_'.$automationType.'_code', $data);

       


    }else{
      update_option('lbb_automation_'.$automationType, $dataArray);
    }
}

add_action('wp_ajax_load_automation_data', 'load_automation_data');
function load_automation_data(){
    $platform_name = $_POST['platform_name'];
    $chatflow_id = $_POST['chatflow_id'];
    $automation_data = [];
    if($platform_name == 'webhook'){
      $automation_data = get_post_meta($chatflow_id,'lbb_automation_webhook', true);
    }else if($platform_name == 'gohighlevel'){
      $automation_data = get_post_meta($chatflow_id,'lbb_automation_gohighlevel', true);
    }else{
      $automation_data = get_option('lbb_automation_'.$platform_name);
    }
    echo json_encode($automation_data);die;
}

add_action('wp_ajax_lbb_close_livechat_ajax_requests', 'lbb_close_livechat_ajax_requests');
function lbb_close_livechat_ajax_requests(){
  global $wpdb;
  $tableName = $wpdb->prefix . 'lbb_conversations';
  $update_query = "UPDATE ".$tableName." SET is_closed = '1' WHERE status = 'L'";
  $wpdb->query($update_query);
  die;
}

add_action('wp_ajax_load_automation_listing', 'load_automation_listing');
function load_automation_listing(){
    $automation_name = $_POST['automation_name'];
    $chatflow_id = $_POST['chatflow_id'];
    
    if($automation_name == 'activecampaign'){
      $automation_data = get_option('lbb_automation_'.$automation_name);
    
    $url = $automation_data['api_url'];
    $key = $automation_data['api_key'];
    $auto_html_select = '';
      if($url != '' && $key != ''){
         
        $lists = LBBgetActiveCampaignLists($url , $key);
        $lists_sort_by_id = array();
        if(is_array($lists)){
          foreach($lists as $new_list){
            $lists_sort_by_id[$new_list['id']] =  $new_list['name']; 
            
          }
        }
        
        if(is_array($lists) && count($lists)){
          foreach($lists as $auto_list){
            $auto_html_select .="<option  value='".$auto_list['id']."'>".$auto_list['name']."</option>";
          }
        }
           
          $select_list = '<select class="lbb-input-field automation_select1 add_to_value_activecampaign" id="sqb_select_list"><option value="">Select List</option>'.$auto_html_select.'</select>';
          
      }

      $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_activecampaign">
    <option value="">Select Action</option>
    <option value="add">Add</option> 
    <option value="remove">Remove</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="activecampaign">Add</a>
</div>';
      
      /* Listing Data*/

    $activecampaign_list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_activecampaign', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $activecampaign_list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$activecampaign_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }

    /**/

      $output = array('url'=>$url , 'key'=>$key , 'lists' => $lists,'auto_html_select'=>$auto_html_select,'lists_sort_by_id'=>$lists_sort_by_id,'customFields_array_select'=>$customFields_array_select,'customFields_array'=>$customFields_array,'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'aweber'){
      
      $automation_data = get_option('lbb_automation_'.$automation_name.'_code');
      $aweber_consumer_key = $automation_data['aweber_consumer_key'];
      $aweber_consumer_secret = $automation_data['aweber_consumer_secret'];
      $aweber_request_token = $automation_data['aweber_request_token'];
      $aweber_token_secret = $automation_data['aweber_token_secret'];

      $lists = '';
      $auto_html_select = '';
      $lists_sort_by_id = array();
      if($aweber_consumer_key != '' && $aweber_consumer_secret != '' && $aweber_request_token != '' && $aweber_token_secret != ''){
        $lists = LBBgetAweberLists($aweber_consumer_key , $aweber_consumer_secret,$aweber_request_token, $aweber_token_secret );
        
        foreach($lists as $new_list){
          $lists_sort_by_id[$new_list['id']] =  $new_list['name'];
          
        }
        
        if(!empty($lists)){
          foreach($lists as $auto_list){
            $auto_html_select .="<option  value='".$auto_list['id']."'>".$auto_list['name']."</option>";
          }
        }
        $auto_html_select = "<select id='sqb_select_list' class='lbb-input-field form-control'><option value=''>Select List</option>".$auto_html_select."</select>";
      }
      $key = $aweber_consumer_key.'|'.$aweber_consumer_secret.'|'.$aweber_request_token.'|'.$aweber_token_secret;
        
      $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Action:</label>
            <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_mailchimp">
              <option value="">Select Action</option>
              <option value="add">Add</option> 
              <option value="remove">Remove</option> 
            </select>
          </div>
          <!--<div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Action Type:</label>
            <select class="lbb-input-field automation_type">
              <option value="">Select Type</option>
              <option value="list">Audience</option>
            </select>
          </div>-->
          <div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Group:</label>
            '.$auto_html_select.'
          </div>
          <div class="lbb-form-group question-input-field-outer" style="">
            <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="mailchimp">Add</a>
          </div>';

        /* Listing Data*/

    $aweber_list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_aweber', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $aweber_list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$aweber_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }

    /**/
      $output =  array('show_listing'=>$show_listing , 'lists'=>$lists,"auto_html_select"=>$auto_html_select,'lists_sort_by_id'=>$lists_sort_by_id, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'mailchimp'){
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $key = $automation_data['api_key'];

      $lists_sort_by_id = array();
      $auto_html_select = '';
        if($key != ''){
          $lists = LBBgetMailchimpLists($key);
          
          foreach($lists as $new_list){
            
            $lists_sort_by_id[$new_list['id']] =  $new_list['name'];
          
          }
          
          if(count($lists)){
            foreach($lists as $auto_list){
              $auto_html_select .="<option  value='".$auto_list['id']."'>".$auto_list['name']."</option>";
            }
          }
          $select_list = '<select class="lbb-input-field automation_select1 add_to_value_mailchimp" id="sqb_select_list"><option value="">Select List</option>'.$auto_html_select.'</select>';

          
          $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Action:</label>
            <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_mailchimp">
              <option value="">Select Action</option>
              <option value="add">Add</option> 
              <option value="remove">Remove</option> 
            </select>
          </div>
          <!--<div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Action Type:</label>
            <select class="lbb-input-field automation_type">
              <option value="">Select Type</option>
              <option value="list">Audience</option>
            </select>
          </div>-->
          <div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Group:</label>
            '.$select_list.'
          </div>
          <div class="lbb-form-group question-input-field-outer" style="">
            <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="mailchimp">Add</a>
          </div>';
          
        }

          /* Listing Data*/

        $mailchimp_list_name = '';
        $automation_data = get_post_meta($chatflow_id,'_lbb_automation_mailchimp', true);
        $automation_listing = "";
        if ($automation_data) {
          foreach($automation_data as $data){
            if(isset($lists_sort_by_id[$data['list']])){
              $mailchimp_list_name = $lists_sort_by_id[$data['list']];
            } 
            $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$mailchimp_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
          }
        }

        /**/

         $output = array('key'=>$key , 'lists' => $lists,'auto_html_select'=>$auto_html_select,'lists_sort_by_id'=>$lists_sort_by_id, 'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'convertkit'){
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $key = $automation_data['api_key'];
      $secret = $automation_data['api_secret'];

      
      $auto_html_sequence_select = "";
      $auto_html_forms_select = "";
      $lists_sort_by_id_form = array();
      $lists_sort_by_id_sequences = array();
      $lists_sort_by_id = array();
        if($key != ''){
          $sequence = LBBgetConvertkitSequence($key , $secret);
          
          foreach($sequence as $new_list){
            
            $lists_sort_by_id_sequences[$new_list['id']] =  $new_list['name'];
          
          }
          if(count($sequence)){
            foreach($sequence as $auto_list){
              $auto_html_sequence_select .="<option  value='".$auto_list['id']."'>".$auto_list['name']."</option>";
            } 
          }
          $auto_html_sequence_select = "<select id='sqb_select_sequence' class='form-control'><option value=''>Select Sequence</option>".$auto_html_sequence_select."</select>";
          
          $forms = LBBgetConvertkitForms($key , $secret);
          
          foreach($forms as $new_list){
            
            $lists_sort_by_id_form[$new_list['id']] =  $new_list['name'];
          
          }
          
          if(count($forms)){
            foreach($forms as $auto_list){
              $auto_html_forms_select .="<option  value='".$auto_list['id']."'>".$auto_list['name']."</option>";
            } 
          }
          $select_list = "<select id='sqb_select_list' class='lbb-input-field automation_select1 add_to_value_convertkit'><option value=''>Select Form</option>".$auto_html_forms_select."</select>";

          
        }
        
        $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Action:</label>
            <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_convertkit">
              <option value="">Select Action</option>
              <option value="add">Add</option> 
              <option value="remove">Remove</option> 
            </select>
          </div>
          <div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Action:</label>
            <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_convertkit">
              <option value="">Select Type</option>
              <option value="form">Form</option> 
              <option value="sequence">Sequence</option> 
            </select>
          </div>
          <div class="lbb-form-group question-input-field-outer" style="">
            <label for="question-input-field">Select Group:</label>
            '.$select_list.'
          </div>
          <div class="lbb-form-group question-input-field-outer" style="">
            <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="convertkit">Add</a>
          </div>';

      $output = array('key'=>$key , 'secret'=>$secret , 'forms' => $forms , 'sequence' => $sequence,"auto_html_forms_select"=>$auto_html_forms_select,"show_listing"=>$show_listing,'lists_sort_by_id'=>$lists_sort_by_id,'lists_sort_by_id_sequences'=>$lists_sort_by_id_sequences,'lists_sort_by_id_form'=>$lists_sort_by_id_form);
    }else if($automation_name == 'sendinblue'){
      $api_key = '';
      $lists_sort_by_id = array();
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $api_key = $automation_data['api_key'];

      $sendInBlueList = LBBgetSendInBlueList($api_key);
      if($sendInBlueList != ''){
        $sendInBlueList = json_decode($sendInBlueList);
        $sendInBlueList = $sendInBlueList->lists;
        
        
      }
      $select_list = '<select id="sqb_select_list" class="lbb-input-field automation_select1 add_to_value_sendinblue" id="add_to_value1" >';
      $select_list .= '<option value="">Select List</option>';
      if(!empty($sendInBlueList)){
        foreach($sendInBlueList AS $key) { 
          $select_list .=  '<option value="'.$key->id.'">'.$key->name.'</option>';
          $lists_sort_by_id[$key->id] =  $key->name;
        }   
      }
      $select_list .= '</select>';
      

     $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
          <label for="question-input-field">Select Action:</label>
          <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_sendinblue">
            <option value="">Select Action</option>
            <option value="add">Add to List</option> 
            <option value="remove">Remove From List</option> 
          </select>
        </div>
        <div class="lbb-form-group question-input-field-outer" style="">
          <label for="question-input-field">Select List:</label>
          '.$select_list.'
        </div>
        <div class="lbb-form-group question-input-field-outer" style="">
          <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="sendinblue">Add</a>
        </div>';

        /* Listing Data*/

    $activecampaign_list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_sendinblue', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $activecampaign_list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$activecampaign_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }
      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id,'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'getresponse'){
      $api_key = '';
      $lists_sort_by_id = array();
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $api_key = $automation_data['api_key'];

      $select_list = '<select id="sqb_select_list" class="lbb-input-field automation_select1 add_to_value_getresponse" >';
      $select_list .= '<option value="">Select Campaign</option>';
      
      $campaigns_list = LBBgetGETRESPONSEList($api_key);

      if(is_object($campaigns_list) && !empty($campaigns_list)){
        foreach($campaigns_list as $campaign_info){
          $campaign_name = $campaign_info->name; 
          $campaign_id = $campaign_info->campaignId; 
          $select_list .=  '<option value="'.$campaign_id.'">'.$campaign_name.'</option>';
          $lists_sort_by_id[$campaign_id] =  $campaign_name;
        }
      } 
      $select_list .= '</select>';

      

     $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_getresponse">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="getresponse">Add</a>
</div>';
       /* Listing Data*/

    $activecampaign_list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_getresponse', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $activecampaign_list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$activecampaign_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }
      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id, 'show_listing' => $show_listing, 'automation_listing' => $automation_listing);
    
    }else if($automation_name == 'sendfox'){
      $api_key = '';
      $lists_sort_by_id = array();
      
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $api_key = $automation_data['api_token'];
      $select_list = '<select id="sqb_select_list" class="lbb-input-field automation_select1 add_to_value_sendfox" id="add_to_value1" >';
      $select_list .= '<option value="">Select List</option>';
      
      $lists = LBBgetSendfoxList($api_key);
      
      if(is_array($lists) && count($lists)){
        foreach($lists as $list_info){
          $list_id = $list_info->id;
          $list_name = $list_info->name;
          $select_list .=  '<option value="'.$list_id.'">'.$list_name.'</option>';
          $lists_sort_by_id[$list_id] =  $list_name;
        }
      } 
      $select_list .= '</select>';
      

 $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_sendfox">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="sendfox">Add</a>
</div>';
      /* Listing Data*/

    $activecampaign_list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_sendfox', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $activecampaign_list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$activecampaign_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }


      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id,'show_listing' => $show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'moosend'){
      $api_key = '';
      $lists_sort_by_id = array();
      
      $automation_data = get_option('lbb_automation_'.$automation_name);
      $api_key = $automation_data['api_key'];
      $select_list = '<select id="sqb_select_list" class="lbb-input-field automation_select1 add_to_value_moosend" id="add_to_value1" >';
      $select_list .= '<option value="">Select List</option>';
      
      $lists = LBBgetMoosendList($api_key);
      
      if(is_array($lists) && count($lists)){
        foreach($lists as $list_info){
          $list_id = $list_info['ID'];
          $list_name = $list_info['Name'];
          $select_list .=  '<option value="'.$list_id.'">'.$list_name.'</option>';
          $lists_sort_by_id[$list_id] =  $list_name;
        }
      } 
      $select_list .= '</select>';
      

     $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_moosend">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="moosend">Add</a>
</div>';

    $list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_moosend', true);
    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }

      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id,'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'vbout' || ($automation_name == 'klaviyo') || ($automation_name == 'acumbamail') || ($automation_name == 'hubspot')){
      $autoresponder_small_letter = ucfirst($automation_name);
      $api_key = '';
      $lists_sort_by_id = array();
      
      $automation_data = get_option('lbb_automation_'.$automation_name);
      
      $api_key = $automation_data['api_key'];

      $select_list = '<select id="sqb_select_list" class="lbb-input-field automation_select1 add_to_value_'.$autoresponder_small_letter.'" >';
      $select_list .= '<option value="">Select List</option>';
      
      $lists = LBBgetCommonList($api_key, $autoresponder_small_letter);
      
      if(is_array($lists) && count($lists)){
        foreach($lists as $list_info){
          $list_id  = '0';
          if(isset($list_info['ID'])){
            $list_id = $list_info['ID'];
            
          }else if(isset($list_info['id'])){
            $list_id = $list_info['id'];
          }else if(isset($list_info['list_id'])){
            $list_id = $list_info['list_id'];
          }
          
          
          $list_name = '';
          if(isset($list_info['Name'])){
            $list_name = $list_info['Name'];
            
          }else if(isset($list_info['name'])){
            $list_name = $list_info['name'];
          }else if(isset($list_info['list_name'])){
            $list_name = $list_info['list_name'];
          }
          
          $select_list .=  '<option value="'.$list_id.'">'.$list_name.'</option>';
          $lists_sort_by_id[$list_id] =  $list_name;
        }
      } 
      $select_list .= '</select>';
      
      
     $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_vbout">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="vbout">Add</a>
</div>';


      $list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_'.$automation_name, true);

    $automation_listing = "";
    if ($automation_data) {
      foreach($automation_data as $data){
        if(isset($lists_sort_by_id[$data['list']])){
          $list_name = $lists_sort_by_id[$data['list']];
        } 
        $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
      }
    }
      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id,'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'fluentcrm'){
      /*Need Plugin*/
      $lists_sort_by_id = array();
      if(function_exists('FluentCrmApi')){
          $fluent_crm_enable = true;
          $fluent_crm_list = FluentCrmApi('lists');
          $fluent_crm_list_all = $fluent_crm_list->all();
          $fluentcrm_lists_sort_by_id = array();
          $fluentcrm_select_list = '<select class="lbb-input-field automation_select1 add_to_value_fluentcrm" id="sqb_select_list" >';
          $fluentcrm_select_list .= '<option value="">Select List</option>';

          
          if(isset($fluent_crm_list_all)){
            foreach($fluent_crm_list_all as $fluent_crm_single_list_info){
              
              $fluentcrm_select_list .= '<option value="'.$fluent_crm_single_list_info->id.'">'.$fluent_crm_single_list_info->title.'</option>';
              $fluentcrm_lists_sort_by_id[$fluent_crm_single_list_info->id] = $fluent_crm_single_list_info->title;
              
            }
          }
          $fluentcrm_select_list .= '</select>';
          $lists_sort_by_id[$fluent_crm_single_list_info->id] =  $fluent_crm_single_list_info->title;
          
        }
        $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_mailerlite">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$fluentcrm_select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="mailerlite">Add</a>
</div>';
 $list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_'.$automation_name, true);
         $automation_listing = "";
        if ($automation_data) {
          foreach($automation_data as $data){
            if(isset($fluentcrm_lists_sort_by_id[$data['list']])){
              $list_name = $fluentcrm_lists_sort_by_id[$data['list']];
            } 
            $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
          }
        }
        $output =  array('show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }else if($automation_name == 'mailpoet'){
      $mailpoet_enable = NULL;

              if (class_exists(\MailPoet\API\API::class)) {
                $mailpoet_enable = true;
                 $mailpoet_api = \MailPoet\API\API::MP('v1');
                $mailpoet_list = $mailpoet_api->getLists();
                $mailpoet_lists_sort_by_id = array();
                $mailpoet_select_list = '<select class="lbb-input-field automation_select1 add_to_value_mailpoet" id="sqb_select_list" >';
                $mailpoet_select_list .= '<option value="">Select List</option>';

                
                if(isset($mailpoet_list)){
                  foreach($mailpoet_list as $mailpoet_list_single){
                    $mailpoet_list_id = $mailpoet_list_single['id'];
                    $mailpoet_list_name = $mailpoet_list_single['name'];
                    $mailpoet_select_list .= '<option value="'.$mailpoet_list_id.'">'.$mailpoet_list_name.'</option>';
                    $mailpoet_lists_sort_by_id[$mailpoet_list_id] = $mailpoet_list_name;
                    
                  }
                }
                $mailpoet_select_list .= '</select>';
                
                
              }

              

   $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_mailpoet">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$mailpoet_select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="mailpoet">Add</a>
</div>';
 $list_name = '';
    $automation_data = get_post_meta($chatflow_id,'_lbb_automation_'.$automation_name, true);
         $automation_listing = "";
        if ($automation_data) {
          foreach($automation_data as $data){
            if(isset($mailpoet_lists_sort_by_id[$data['list']])){
              $list_name = $mailpoet_lists_sort_by_id[$data['list']];
            } 
            $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
          }
        }

        $output =  array('show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    
    }else if($automation_name == 'dap'){
      $lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
                
      if(file_exists($lldocroot . "/dap/dap-config.php")){ 
          include_once ($lldocroot . "/dap/dap-config.php");   
      }

      $ProductsList = '';
      if(class_exists('Dap_Product')){
          $dap_select_list = '<select class="lbb-input-field automation_select1 add_to_value_dap" id="sqb_select_list" >';
          $dap_select_list .= '<option value="">Select List</option>';
          $dap_products_list = Dap_Product::loadProducts("","A");

          if(is_array($dap_products_list)){
              $dap_obj_exists_product_id = '';
              $automation_data = get_post_meta($chatflow_id,'_lbb_automation_dap', true);
              if(!empty($automation_data)){
                  $dap_obj_exists_product_id = $automation_data[0]['list'];
              }
              
              foreach($dap_products_list as $dap_product_list){
                  $dap_obj_exists_selected = '';
                  if($dap_obj_exists_product_id == $dap_product_list->getId()){
                      $dap_obj_exists_selected = 'selected';
                  }
                  $dap_select_list .= "<option ".$dap_obj_exists_selected." value='".$dap_product_list->getId()."'>".$dap_product_list->getName()."</option>";
                  
              }
          }

          $dap_select_list .= '</select>';
      }



      $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
                        <label for="question-input-field">Select Group:</label>
                        '.$dap_select_list.'
                      </div>';
      $output =  array('show_listing'=>$show_listing);
    }else if($automation_name == 'mailerlite'){
      $api_key = '';
      $lists_sort_by_id = array();

      $automation_data = get_option('lbb_automation_'.$automation_name);
      $api_key = $automation_data['api_key'];
      $select_list = '<select class="lbb-input-field automation_select1 add_to_value_mailerlite" id="sqb_select_list" >';
      $select_list .= '<option value="">Select Group</option>';
      
      $group_list = LBBgetMailerliteGroupList($api_key);
      
      if(is_array($group_list) && count($group_list)){
        foreach($group_list as $group_info){
          $group_id = $group_info->id;
          $group_name = $group_info->name;
          $select_list .=  '<option value="'.$group_id.'">'.$group_name.'</option>';
          $lists_sort_by_id[$group_id] =  $group_name;
        }
      } 
      $select_list .= '</select>';
      
      $show_listing = '<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Action:</label>
  <select class="lbb-input-field automation_select1 sqb_auto_action add_to_id_mailerlite">
    <option value="">Select Action</option>
    <option value="add">Add to List</option> 
    <option value="remove">Remove From List</option> 
  </select>
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <label for="question-input-field">Select Group:</label>
  '.$select_list.'
</div>
<div class="lbb-form-group question-input-field-outer" style="">
  <a href="javascript:void(0)" class="lbb-btn lbb-btn-black automation_action_btn add_action_btn automation_action_btn add_action_btn" type="button" data-action="mailerlite">Add</a>
</div>';

  /* Listing Data*/

  $mailerlite_list_name = '';
  $automation_data = get_post_meta($chatflow_id,'_lbb_automation_mailerlite', true);
  $automation_listing = "";
  if ($automation_data) {
    foreach($automation_data as $data){
      if(isset($lists_sort_by_id[$data['list']])){
        $mailerlite_list_name = $lists_sort_by_id[$data['list']];
      } 
      $automation_listing .= '<tr><td class="action" data-action="'.$data['action'].'">'.$data['action'].'</td><td class="list_value" data-list-value="'.$data['list'].'">'.$mailerlite_list_name.'</td><td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'.$data['list'].'" ></span></td></tr>';
    }
  }

  /**/
      $output =  array('api_key'=>$api_key , 'lists'=>$select_list,'lists_sort_by_id'=>$lists_sort_by_id,'show_listing'=>$show_listing, 'automation_listing' => $automation_listing);
    }

    echo json_encode($output);die;
}

add_action('wp_ajax_save_message_customizer_data', 'save_message_customizer_data');
function save_message_customizer_data(){
    $settingsData = $_POST['settings'];
    $decodedData = urldecode($settingsData);
    if($settingsData){
      parse_str($decodedData, $dataArray);
      update_option('lbb_message_data', $dataArray);
      $output = 'Success';
    }
    echo json_encode($output);die;
}

add_action('wp_ajax_lbb_save_pdf_headerfooter_settings_data', 'lbb_save_pdf_headerfooter_settings_data');
function lbb_save_pdf_headerfooter_settings_data(){
    $settingsData = $_POST['settings'];
    $decodedData = urldecode($settingsData);
    if($settingsData){
      parse_str($decodedData, $dataArray);
      update_option('lbb_pdf_header_footer', $dataArray);
      $output = 'Success';
    }
    echo json_encode($output);die;
}

add_action('wp_ajax_save_notification_setting_data', 'save_notification_setting_data');
function save_notification_setting_data(){
    $settingsData = $_POST['settings'];
    $decodedString = urldecode($settingsData);
    $resultArray  = array();
    parse_str($decodedString, $resultArray);
    $lbb_general_settings = $resultArray['lbb_general_settings'];
    update_option('lbb_general_settings', $lbb_general_settings);
    $output = 'Success';
    echo json_encode($output);die;
}

add_action('wp_ajax_save_email_notifications_data', 'save_email_notifications_data');
function save_email_notifications_data(){
    $settingsData = $_POST['settings'];
    $lbb_admin_email_body = $_POST['lbb_admin_email_body'];
    $lbb_user_email_body = $_POST['lbb_user_email_body'];
    if($settingsData != ''){
      foreach($settingsData as $data){
        update_option('_'.$data['name'], $data['value']);
      }  
    }
    update_option('_lbb_admin_email_body', $lbb_admin_email_body);
    update_option('_lbb_user_email_body', $lbb_user_email_body);

    $output = 'Success';
    echo json_encode($output);die;
}

function lbb_get_array_value($array,$key,$default = ''){

  if(isset($array[$key])){
    return $array[$key];
  }

  return $default;
}

add_action('wp_ajax_load_customfield_data_by_id', 'load_customfield_data_by_id');
function load_customfield_data_by_id(){
  $output = "";
  $customfield_id = $_REQUEST['customfield_id'];
  if($customfield_id){
    $customfieldmanager = new CustomFieldManager();
    $customfield_data = $customfieldmanager->loadById($customfield_id);
    if($customfield_data){
      $output = $customfield_data;
    }
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_delete_customfield_data', 'delete_customfield_data');
function delete_customfield_data(){
  $output= array();
  $customfield_id = $_REQUEST['customfield_id'];
  if($customfield_id){
    $customfieldmanager = new CustomFieldManager();
    $id = $customfieldmanager->deleteById($customfield_id);
    $output['success'] = 'Deleted';
    $output['id'] = $id;
  }else{
    $output['error'] = 'something wrong!';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_load_customfield_data', 'load_customfield_data');
function load_customfield_data(){
  $custom_fields_data = new CustomFieldManager();
  $args = $custom_fields_data->loadAll();
  $html = "";
  if($args){
    $i=0;
    foreach($args as $arg){
    $html .= '
    <tr>
      <td>'.$arg['label'].'</td>
      <td>'.$arg['field_type'].'</td>
      <td class="lbb-text-center">
        <div class="lbb-action-btn-wrapper">
          <a class="lbb-icon-btn lbb-customfield-edit-btn" data-id="'.$arg['id'].'">
            <span class="dashicons dashicons-edit"></span>
          </a>
          <a class="lbb-icon-btn lbb-delete-btn delete-customfield" data-id="'.$arg['id'].'" href="javascript:void(0)">
            <span class="dashicons dashicons-trash"></span>
          </a>
        </div>
      </td>
    </tr>';
    }
  }
  echo json_encode($html);die;
}

add_action('wp_ajax_save_customfield_data', 'save_customfield_data');
function save_customfield_data(){

  $lbb_customfield_id = $_REQUEST['lbb_customfield_id'];

  $label = sanitize_text_field($_REQUEST['lbb_label']);
  $name = sanitize_text_field($_REQUEST['lbb_name']);
  $field_type = $_REQUEST['lbb_field_type'];
  $required_field = $_REQUEST['lbb_required_field'];
  
  $customfieldmanager = new CustomFieldManager();
  if(empty($lbb_customfield_id)){
    $load_all = $customfieldmanager->loadAll();
    foreach($load_all as $load){
      $all_name = $load['name'];
      if($all_name == $name){
        $output = "already_exist";
        echo json_encode($output);die;
      }
    }
  }
  $output = array();
  $args = array(
      'name' => $name,
      'label' => $label,
      'field_type' => $field_type,
      'required' => $required_field,
  );
  if($lbb_customfield_id){
    $customfield_id = $customfieldmanager->update($args, $lbb_customfield_id);
  }else{
    $customfield_id = $customfieldmanager->insertcustomfield($args);
  }

  $customfieldmanager = new CustomFieldManager();
  $customfield = $customfieldmanager->loadById($customfield_id);

  $output = array('customfield_id' => $customfield_id, 'customfield' => $customfield);
  echo json_encode($output);die;
}

add_action('wp_ajax_lbb_check_firebase', 'lbb_check_firebase');
function lbb_check_firebase(){
  $firebase_configuration = 0;
  if(get_option('lbb_admin_firebase_id') && get_option('firebase_app_configuration') && get_option('firebase_db_configuration')){
      $firebase_configuration = 1;
  }
  echo json_encode($firebase_configuration);die;
}

/*Firebase Data*/
add_action('wp_ajax_save_firebase_data', 'save_firebase_data');
function save_firebase_data(){
  $output = 'no';
  $lbb_livechat_options = $_REQUEST['lbb_livechat_options'];
  update_option('lbb_livechat_options', $lbb_livechat_options);
  $firebase_app_configuration = $_REQUEST['firebase_app_configuration'];
  $firebase_db_configuration = $_REQUEST['firebase_db_configuration'];
  $lbb_admin_firebase_id = $_REQUEST['lbb_admin_firebase_id'];
  if(!empty($firebase_app_configuration) && !empty($firebase_db_configuration)){
    update_option('firebase_app_configuration', $firebase_app_configuration);
    update_option('firebase_db_configuration', $firebase_db_configuration);
    update_option('lbb_admin_firebase_id', $lbb_admin_firebase_id);
    $output = 'Success';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_save_aiassistant_data', 'save_aiassistant_data');
function save_aiassistant_data(){
  $output = 'no';
  $lbb_ai_assistant = $_REQUEST['lbb_ai_assistant'];
  if(!empty($lbb_ai_assistant)){
    update_option('lbb_ai_assistant', $lbb_ai_assistant);
    $output = 'Success';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_save_contactform_data', 'save_contactform_data');
function save_contactform_data(){
  $output = 'no';
  $lbb_contactform_settings = $_REQUEST['lbb_contactform_settings'];
  if(!empty($lbb_contactform_settings)){
    update_option('lbb_contactform_settings', $lbb_contactform_settings);
    $output = 'Success';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_save_fuzzysearch_data', 'save_fuzzysearch_data');
function save_fuzzysearch_data(){
  $output = 'no';
  $lbb_fuzzy_search_options = $_REQUEST['lbb_fuzzy_search_options'];
  if(!empty($lbb_fuzzy_search_options)){
    update_option('lbb_fuzzy_search_options', $lbb_fuzzy_search_options);
    $output = 'Success';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_save_gdpr_data', 'save_gdpr_data');
function save_gdpr_data(){
    $output = 'no';
    $lbb_gdpr_settings = $_REQUEST['lbb_gdpr_settings'];
    
    if(!empty($lbb_gdpr_settings)){
        update_option('lbb_gdpr_settings', $lbb_gdpr_settings);
        $output = 'Success';
      }
  echo json_encode($output);die;
}

/*Outcome Start */

add_action('wp_ajax_save_load_outcomes_data', 'save_load_outcomes_data');
function save_load_outcomes_data(){
  $lbb_outcomes_id = $_REQUEST['lbb_outcomes_id'];

  $outcomes_name = sanitize_text_field($_REQUEST['lbb_outcomes_name']);
  $outcomes_description = stripslashes($_REQUEST['lbb_outcomes_description']);
  $chatflow_id = $_REQUEST['lbb_chatflow_id'];
  
  $outcomesmanager = new OutcomesManager();
  $args = array(
      'name' => $outcomes_name,
      'content' => $outcomes_description,
      'chatflow_id' => $chatflow_id,
      'outcome_image' => '',
  );
  
  $outcomesData = new OutcomesManager();
  $load_by_ids = $outcomesData->loadByChatflowId($chatflow_id);

  foreach($load_by_ids as $load_by_id){
    if($outcomes_name == $load_by_id['name']){
      $output = array('samename' => 'samename');
      echo json_encode($output);die;
    }
  }
  if($lbb_outcomes_id){
    $outcomes_id = $outcomesmanager->update($args, $lbb_outcomes_id);
  }else{
    $outcomes_id = $outcomesmanager->insert($args);
  }

  $outcomesmanager = new OutcomesManager();
  $outcomedata = $outcomesmanager->loadById($outcomes_id);

  
  $outcomesData = new OutcomesManager();
  $args = $outcomesData->loadByChatflowId($chatflow_id);

    $load_outcome_data = "";
    if($args){
      foreach($args as $arg){
        $show_selected = "";
        if($outcomes_id == $arg['id']){
          $show_selected = 'selected';
        }
      $load_outcome_data .= '<option '.$show_selected.' value="'.$arg['id'].'">'.$arg['name'].'</option>';
      }
    }

  $output = array('outcomes_id' => $outcomes_id, 'load_outcome_data' => $load_outcome_data,'outcomedata' => $outcomedata);

  echo json_encode($output);die;
}

add_action('wp_ajax_save_outcomes_data', 'save_outcomes_data');
function save_outcomes_data(){
  $lbb_outcomes_id = $_REQUEST['lbb_outcomes_id'];

  $outcomes_name = sanitize_text_field($_REQUEST['lbb_outcomes_name']);
  $outcomes_description = stripslashes($_REQUEST['lbb_outcomes_description']);
  $lbb_outcome_image_upload = stripslashes($_REQUEST['lbb_outcome_image_upload']);


  $chatflow_id = $_REQUEST['lbb_chatflow_id'];
  
  $outcomesmanager = new OutcomesManager();
  $args = array(
      'name' => $outcomes_name,
      'content' => $outcomes_description,
      'chatflow_id' => $chatflow_id,
      'outcome_image' => $lbb_outcome_image_upload,
  );
 
  if($lbb_outcomes_id){
    $outcomes_id = $outcomesmanager->update($args, $lbb_outcomes_id);
  }else{
    $outcomes_id = $outcomesmanager->insert($args);
  }

  $outcomesmanager = new OutcomesManager();
  $outcome = $outcomesmanager->loadById($outcomes_id);

  echo json_encode($outcome);die;
}

add_action('wp_ajax_load_outcomes_data', 'load_outcomes_data');
function load_outcomes_data(){
  $outcomes_data = new outcomesManager();
  $args = $outcomes_data->loadAll();
  $html = "";
  if($args){
    foreach($args as $arg){
      $content = strip_tags($arg['content']);
      $chatflow_id = $arg['chatflow_id'];
      if(strlen($content) > 30) {
        $content = substr($content, 0, 30).'...';
      }

    $html .= '
    <tr>
    <td>'.get_the_title($chatflow_id).'</td>
      <td>'.$arg['name'].'</td>
      <td>'.$content.'</td>
      <td class="lbb-text-center">
        <div class="lbb-action-btn-wrapper">
          <a class="lbb-icon-transparent-btn lbb-edit-btn lbb-outcomes-edit-btn" data-id="'.$arg['id'].'" href="javascript:void(0)">
            <span class="dashicons dashicons-edit"></span> Edit
          </a>
          <a class="lbb-icon-transparent-btn  lbb-delete-btn delete-outcomes" data-id="'.$arg['id'].'" href="javascript:void(0)">
            <span class="dashicons dashicons-trash"></span> Delete
          </a>
          
        </div>
      </td>
    </tr>';
    }
  }
  echo json_encode($html);die;
}

add_action('wp_ajax_delete_outcomes_data', 'delete_outcomes_data');
function delete_outcomes_data(){
  $output= array();
  $outcomes_id = $_REQUEST['outcomes_id'];
  if($outcomes_id){
    $outcomesmanager = new OutcomesManager();
    $id = $outcomesmanager->deleteById($outcomes_id);
    $output['success'] = 'Deleted';
    $output['id'] = $id;
  }else{
    $output['error'] = 'something wrong!';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_load_outcomes_data_by_id', 'load_outcomes_data_by_id');
function load_outcomes_data_by_id(){
  $output = "";
  $outcomes_id = $_REQUEST['outcomes_id'];
  if($outcomes_id){
    $outcomesmanager = new OutcomesManager();
    $outcomes_data = $outcomesmanager->loadById($outcomes_id);
    if($outcomes_data){
      $output = $outcomes_data;
    }
  }
  echo json_encode($output);die;
}

/* Tags Start */
add_action('wp_ajax_save_tags_data', 'save_tags_data');
function save_tags_data(){ 
    $lbb_tags_id = $_REQUEST['lbb_tags_id'];

    $tags_name = sanitize_text_field($_REQUEST['lbb_tags_name']);
    $tags_description = sanitize_text_field($_REQUEST['lbb_tags_description']);
    
    $tagsmanager = new TagsManager();
    $args = array(
        'name' => $tags_name,
        'description' => $tags_description,
    );
    if($lbb_tags_id){
      $tags_id = $tagsmanager->update($args, $lbb_tags_id);
    }else{
      $tags_id = $tagsmanager->insert($args);
    }

    $tags_data = new TagsManager();
    $args = $tags_data->loadAll();
    $load_tags_data = "";
    if($args){
      foreach($args as $arg){
        $show_selected = "";
        if($tags_id == $arg['id']){
          $show_selected = 'selected';
        }
      $load_tags_data .= '<option '.$show_selected.' value="'.$arg['id'].'">'.$arg['name'].'</option>';
      }
    }

    $tagsfieldmanager = new TagsManager();
    $tagsdata = $tagsfieldmanager->loadById($tags_id);

    $output = array('tags_id' => $tags_id, 'load_tags_data' => $load_tags_data, 'tagsdata' => $tagsdata);

    echo json_encode($output);die;
}

add_action('wp_ajax_load_tags_data', 'load_tags_data');
function load_tags_data(){
  $tags_data = new TagsManager();
  $args = $tags_data->loadAll();
  $html = "";
  if($args){
    $i=0;
    foreach($args as $arg){
    $html .= '
    <tr>
      <td>'.$arg['name'].'</td>
      <td class="lbb-text-center">
        <div class="lbb-action-btn-wrapper">
          <a class="lbb-icon-btn lbb-tags-edit-btn" data-id="'.$arg['id'].'">
            <span class="dashicons dashicons-edit"></span>
          </a>
          <a class="lbb-icon-btn lbb-delete-btn delete-tags" data-id="'.$arg['id'].'" href="javascript:void(0)">
            <span class="dashicons dashicons-trash"></span>
          </a>
        </div>
      </td>
    </tr>';
    }
  }
  echo json_encode($html);die;
}

add_action('wp_ajax_delete_tags_data', 'delete_tags_data');
function delete_tags_data(){
  $output= array();
  $tags_id = $_REQUEST['tags_id'];
  if($tags_id){
    $tagsmanager = new TagsManager();
    $id = $tagsmanager->deleteById($tags_id);
    $output['success'] = 'Deleted';
    $output['id'] = $id;
  }else{
    $output['error'] = 'something wrong!';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_delete_posttype_chatflow', 'delete_posttype_chatflow');
function delete_posttype_chatflow(){
  $output= array();
  $chatflow_id = $_REQUEST['chatflow_id'];
  if($chatflow_id){
    wp_delete_post($chatflow_id);

    $outcome_data = new OutcomesManager;
    $outcome_data = $outcome_data->deleteByChatflowId($chatflow_id);

    $output['success'] = 'Deleted';
    $output['id'] = $id;
  }else{
    $output['error'] = 'something wrong!';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_export_posttype_chatflow', 'export_posttype_chatflow');
function export_posttype_chatflow(){
  $output= array();
  $post_id = $_REQUEST['chatflow_id'];
  if($post_id){
    $post = get_post($post_id);
    if ($post) {
        $post_data = array(
            'ID' => $post->ID,
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
            'post_type' => $post->post_type,
        );

        $postmeta_data = array();
        $postmetaData = get_post_custom($post_id);
        foreach ($postmetaData as $key => $values) {
            if (count($values) == 1) {
              $postmeta_data[$key] = $values[0];
            } else {
              $postmeta_data[$key] = $values;
            }
        }

        $question_ids = get_post_meta($post_id,'action_ids', true);
        $question_ids = explode(',',$question_ids); 
        $question_ids = array_reverse($question_ids); 
        $questions = array();
        foreach($question_ids as $question_id){
          $question_post = get_post($question_id);
          $question_post_data = array(
            'ID' => $question_post->ID,
            'post_title' => $question_post->post_title,
            'post_content' => $question_post->post_content,
            'post_type' => $question_post->post_type,
          );

          $questionsPostmetaData = array();
          $questionsPostmeta = get_post_custom($question_id);
          foreach ($questionsPostmeta as $key => $values) {
              if (count($values) == 1) {
                $questionsPostmetaData[$key] = $values[0];
              } else {
                  $questionsPostmetaData[$key] = $values;
              }
          }

          $new_questions = $question_post_data;
          $new_questions['question_postmeta'] = $questionsPostmetaData;
          $questions[] = $new_questions;
        }

        $outcome_data = new OutcomesManager;
        $outcome_data = $outcome_data->loadByChatflowId($post_id);

        $combined_data = array(
            'post' => $post_data,
            'postmeta' => $postmeta_data,
            'questions' => $questions,
            'outcome_data' => $outcome_data,
        );

        $chatflow_name = get_the_title($post_id);
        $output['success'] = $combined_data;
        $output['chatflow_name'] = $chatflow_name;

    } else {
        $output['error'] = 'something wrong!';
    }
  }else{
    $output['error'] = 'something wrong!';
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_lbb_update_status', 'lbb_update_status');
function lbb_update_status(){
    $chatflow_id = $_REQUEST['chatflow_id'];
    $status = $_REQUEST['status'];
    if(!empty($chatflow_id)){
        update_post_meta($chatflow_id, 'lbb_chatflow_status', $status);
    }
    echo json_encode($chatflow_id);die;
}

add_action('wp_ajax_lbb_update_selected_url', 'lbb_update_selected_url');
function lbb_update_selected_url(){
    $chatflow_id = $_REQUEST['chatflow_id'];
    $checkedValues = $_REQUEST['checkedValues'];
    if(!empty($chatflow_id)){
        update_post_meta($chatflow_id, 'selected_url', $checkedValues);
    }
    echo json_encode($chatflow_id);die;
}

add_action('wp_ajax_lbb_load_all_pages', 'lbb_load_all_pages');
function lbb_load_all_pages(){
    $chatflow_id = $_REQUEST['chatflow_id'];
    $html = "";
    if(!empty($chatflow_id)){
        $html .= '<input type="hidden" name="pages_chatflow_id" value="'.$chatflow_id.'"><h2 class="lbb-popup-sub-heading">This popup is active on these pages</h2><ul class="all-pages-listing">';
        $html .= lbbGetPagesPostsURLListHtmlCheckbox($chatflow_id);
        $html .= "</ul>";
    }
    echo json_encode($html);die;
}

function lbbGetPagesPostsURLListHtmlCheckbox($chatflow_id = 0){
  $page_post_ids = array();

  if(is_numeric($chatflow_id) && $chatflow_id != 0){
    if($chatflow_id){
        $chatflow_data = get_post_meta( $chatflow_id, 'selected_url', true );
        $enter_url = get_post_meta($chatflow_id, 'enter_url', true);
        $enter_url_id = "";
        if(!empty($enter_url)){
            $enter_url_id = url_to_postid($enter_url);
        }
        

        $page_post_ids = explode(',',$chatflow_data); 
        if(!empty($enter_url_id)){
            $page_post_ids[] = $enter_url_id;
        }
        //echo '<pre>';print_r($page_post_ids);
    }
  }
  
  $html = '';           
    $active_page_posts_url = "";
    if(in_array(get_option('page_on_front'),$page_post_ids)){
      $active_page_posts_url = "checked";
    } 
    //$html .= '<optgroup label="-----------(Home Page)----------">';
    
    $page_id = get_option('page_on_front');
    $page_name = get_post_field( 'post_name', get_option('page_on_front'));
    $page_url = get_post_field( 'post_name', get_option('page_on_front'));
    
    
    $html .= '<li> <span class="checkbox-custom-style"> <input type="checkbox" id="checkbox-0" '.$active_page_posts_url.' name="pages_checkbox" value="'.$page_id.'" class="custom-checkbox-input "> <label class="custom--checkbox" for="checkbox-0"></label></span><span>/'.$page_url.'</span></li>';
    
    
    //$html .=  '<optgroup label="----------- Pages----------">';
    
    $page_id = get_option('page_on_front');

    $wpb_all_page = get_pages(array('exclude' => array($page_id),'post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>500));
    $i = '1';
    foreach($wpb_all_page as $page){ // $pages is array of object
        $active_page_posts_url = "";
        if(in_array($page->ID,$page_post_ids)){
          $active_page_posts_url = "checked";
        }

      $page_id = get_option('page_on_front');
      $page_name = get_post_field( 'post_name', $page->ID );
      $page_url = get_post_field( 'post_name', $page->ID );
      
      

      $html .= '<li> <span class="checkbox-custom-style"> <input type="checkbox" id="checkbox-'.$i.'" '.$active_page_posts_url.' name="pages_checkbox" value="'.$page->ID.'" class="custom-checkbox-input "> <label class="custom--checkbox" for="checkbox-'.$i.'"></label></span><span>/'.$page_url.'</span></li>';
      $i++;
    }
    
    
    //$html .=  '<optgroup label="----------- Post ----------">';
    
    $wpb_all_posts = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>500));
    if ( $wpb_all_posts->have_posts() ) {
        $i = 500;
        while ( $wpb_all_posts->have_posts() ){ 
            $wpb_all_posts->the_post();
            $active_page_posts_url = "";
            if(in_array(get_the_ID(),$page_post_ids)){
              $active_page_posts_url = "checked";
            }
            $page_id = get_the_ID();
            $page_name = get_post_field( 'post_name', get_the_ID() );
            $page_url = get_post_field( 'post_name', get_the_ID() );
      
     
            $html .= '<li> <span class="checkbox-custom-style"> <input type="checkbox" id="checkbox-'.$i.'" '.$active_page_posts_url.' name="pages_checkbox" value="'.$page_id.'" class="custom-checkbox-input "> <label class="custom--checkbox" for="checkbox-'.$i.'"></label></span><span>/'.$page_url.'</span></li>';
        $i++;
        } 
        wp_reset_postdata();
    }

    $args = array(
        'public' => true,
        '_builtin' => false,
    );
    $post_types = get_post_types( $args, 'objects' );
    if(!empty($post_types)){
        $i = 1000;
        foreach($post_types as $post_type){
            /*Exclude custom posts used in chatflow*/
            $exclude = array( 'lbb-chatflow-action', 'lbb-chatflow' );
            if( TRUE === in_array( $post_type->name, $exclude ) )
            continue;
            $post_type_name = $post_type->name;
            //$html .=  '<optgroup label="----------- Custom Post - '.$post_type->label.' ----------">';
            $wpb_all_posts = new WP_Query(array('post_type'=> $post_type_name, 'post_status'=>'publish', 'posts_per_page'=>500));
            if ( $wpb_all_posts->have_posts() ) {
                
                while ( $wpb_all_posts->have_posts() ){ 
                    $wpb_all_posts->the_post();
                    $active_page_posts_url = "";
                    if(in_array(get_the_ID(),$page_post_ids)){
                        $active_page_posts_url = "active_page_posts_url";
                    }
                    $page_id = get_the_ID();
                    $page_name = get_post_field( 'post_name', get_the_ID() );
                    $page_url = get_post_field( 'post_name', get_the_ID() );
                  
                    $html .= '<li> <span class="checkbox-custom-style"> <input type="checkbox" id="checkbox-'.$i.'" '.$active_page_posts_url.' name="pages_checkbox" value="'.$page_id.'" class="custom-checkbox-input "> <label class="custom--checkbox" for="checkbox-'.$i.'"></label></span><span>/'.$page_url.'</span></li>';
                    $i++;
                }
            } 
            wp_reset_postdata();
        }
    }
  return $html;            
}

add_action('wp_ajax_load_tags_data_by_id', 'load_tags_data_by_id');
function load_tags_data_by_id(){
  $output = "";
  $tags_id = $_REQUEST['tags_id'];
  if($tags_id){
    $tagsmanager = new TagsManager();
    $tags_data = $tagsmanager->loadById($tags_id);
    if($tags_data){
      $output = $tags_data;
    }
  }
  echo json_encode($output);die;
}

/* Tags End */

add_action('wp_ajax_save_automation_listings_data', 'save_automation_listings_data');
function save_automation_listings_data(){
  $output = "";
  $chatflow_id = $_REQUEST['chatflow_id'];
  $automation_type = $_REQUEST['automation_type'];
  $automation_data = $_REQUEST['automation_data'];
  if($chatflow_id){
    update_post_meta($chatflow_id, '_lbb_automation_'.$automation_type, $automation_data);
  }
  echo json_encode($output);die;
}


function save_lbb_settings($dataArray, $chatflow_id){
  foreach($dataArray as $name => $data){
    if($name == '_lbb_assistant_id' && $data == ''){

    }else{
      update_post_meta($chatflow_id, $name, $data);
    }
  }
}

function getLBBQuestionHTML($question_id){

  $type = get_post_meta($question_id,'question_type', true);
 
  $title = get_the_title($question_id);
  $content = get_the_content(null,false,$question_id);

  $editLink = '<a href="#" class="lbb-edit-question" data-question_id="'.$question_id.'">Edit</a>';
  $deleteLink = '<a href="#" class="lbb-delete-question" data-question_id="'.$question_id.'"><i class="bx bxs-trash-alt"></i></a>';
  $cloneLink = '<a href="#" class="lbb-clone-question" data-question_id="'.$question_id.'">Clone</a>';
  $actionClass = '';
  $answer_html = '';
  $options_html  = '';
  if($type == 'text' || $type == 'email' || $type == 'name' || $type == 'phone' || $type == 'country'){

    $options = array();
    $options = get_post_meta($question_id, 'advance_logic', true);
    
    if(!empty($options)){
      $options_html = '<div class="default" data-id="">Default</div>';
      foreach($options as $key => $option){
          $options_html .= '<div class="" data-id="'.@$option['id'].'">Rule '.($key+1).' <span class="lbb-edit-logic-rule" data-id="'.@$option['id'].'" data-questionid="'.$question_id.'"><span class="dashicons dashicons-edit"></span>
          </span></div>';
      }
    }

      $answer_html  = '
      <div class="chat-message-buttons">
          <input type="text" placeholder="" />
          '.$options_html.'
      </div>';
  }else if($type == 'single' || $type == 'message'){
      $options = array();
      $options = get_post_meta($question_id, 'quick_reply_buttons', true);
      $options_html = '';
      if(!empty($options)){
        $dis = 'display:none;';
        foreach($options as $option){
            $different_bot = '';
            if (isset($option['id'])) {
                if($option['answer_action'] == "different_bot"){
                    $different_bot = 'lbb-different-bot';
                }
                $options_html .= '<div href="#" class="llb-chatflow-quick-action lbb-content-box-action '.$different_bot.'" data-id="'.@$option['id'].'" data-question_id="'.$question_id.'"><input type="text" value="'.$option['title'].'" class="quick-input-choice" /> 
                <div class="lbb-menu-icon-container">
                  <i class="bx bx-dots-vertical-rounded"></i><div class=" lbb-menu-popup">
                      <ul>
                        <li><a class="lbb-link lbb-new-node">Add a New Node</a></li>
                        <li><a class="lbb-link lbb-next-action">Next Action</a></li>
                        <li><a class="lbb-link lbb-edit-answer">Edit Answer</a></li>
                        <li><a class="lbb-link lbb-delete-answer">Delete</a></li>
                        <li><a class="lbb-link lbb-assign-tag">Assign Tag</a></li>
                        <li><a class="lbb-link lbb-clone-answer">Clone</a></li>
                        <li><a class="lbb-link lbb-map-outcome">Map to outcome</a></li>
                      </ul>
                  </div>
              </div>
                </div>';
            }
            
        }
      }else{
        $dis = '';
      }
      
      $no_answers = '<div class="lbb-no-answer" style="'.$dis.'"><p>No Answer found :</p> <a href="javascript:void(0);" class="lbb-underline-btn lbb-small lbb-add-n-answer" >Add New</a></div>';
      
      $answer_html = $no_answers.'
      <div class="chat-message-buttons">
          '.$options_html.'
      </div>';
  }else if($type == 'welcome'){
    $answer_html = '';
    $actionClass = 'lbb-action-full';
    $deleteLink = '';
    $cloneLink = '';
  }
  $extra_content_class = "";
  $contenteditable = "true";
  $content_without_tags = $content;
  $content_without_tags = strip_tags($content_without_tags);
  if(strlen($content_without_tags) > 165) {
    //$content = substr($content, 0, 165) . '...';
    $extra_content_class = "lbb-edit-content";
    $contenteditable = 'false';
  }
  $question_html  = '
      <h5 class="node-tab-heading">'.$title.'</h5>
      <div class="node-content-area-main">
          <div class="node-content-area">
              <div class="node-description '.$extra_content_class.'" contenteditable="'.$contenteditable.'" data-question_id="'.$question_id.'">
                  '.($content).'
              </div>
          </div>
          
          '.$answer_html.'
          
      </div>';

  $main_html = '
      <div class="action-node-wrapper">
          <div class="action-node">
              <div role="button" aria-disabled="false" class="clickable-node ui-clickable" tabindex="0">
                  <div class="node-card-wrapper">
                      <div class="node-card-body">
                          '.$question_html.'
                          <div class="lbb-node-action action '.$actionClass.'">
                            '.$editLink.$deleteLink.$cloneLink.'
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>';

  return $main_html;
}

function lbb_admin_template($template,$data){
  include(LBB_ABS_URL.'/admin/templates/'.$template.'.php');
}



function getWelcomeHtml($question_id){

  
  $main_html = '
      <div class="action-node-wrapper">
          <div class="action-node">
              <div role="button" aria-disabled="false" class="clickable-node ui-clickable" tabindex="0">
                  <div class="node-card-wrapper">
                      <div class="node-card-body">
                          '.$question_html.'
                          <div class="action"><a href="#" data-question_id="'.$question_id.'">Edit</a></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>';

    return $main_html;
}


function LBBTimeElapsedString($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function lbbGetAiAssistant($chatflow_id = 0){
    $args = array(
        'post_type' => 'lbb-chatflow',
        'posts_per_page' => -1
    );
    $obituary_query = new WP_Query($args);
    $html = "";
    
    
    if ($obituary_query->have_posts()) {
        $html .= '<option value="0">Please Select</option>';
        while ($obituary_query->have_posts()) : $obituary_query->the_post();
            $chatflow_name = "";
            $lbb_select_trained_bot = "";
            $active_trained_ai = "";
            if(is_numeric($chatflow_id) && $chatflow_id != 0){
                if($chatflow_id){
                  $lbb_select_trained_bot = get_post_meta( $chatflow_id, 'lbb_select_trained_bot', true );
                }
              }
            if(get_post_meta(get_the_ID(), '_chatflow_type', true)){
                $chatflow_type = get_post_meta(get_the_ID(), '_chatflow_type', true);
                if($lbb_select_trained_bot == get_the_ID()){
                    $active_trained_ai = "selected";
                }
                if($chatflow_type == 'trained_ai'){
                    $html .= '<option value="'.get_the_ID().'" '.$active_trained_ai.'>'.get_the_title().'</option>'; 
                }
            }

            endwhile;
            wp_reset_postdata();
    }
    return $html;  
}
function lbbGetPagesPostsURLListHtml($chatflow_id = 0){
  $page_post_ids = array();

  if(is_numeric($chatflow_id) && $chatflow_id != 0){
    if($chatflow_id){
      $chatflow_data = get_post_meta( $chatflow_id, 'selected_url', true );
      $page_post_ids = explode(',',$chatflow_data); 
    }
  }
  
  $html = '';           
    $active_page_posts_url = "";
    if(in_array(get_option('page_on_front'),$page_post_ids)){
      $active_page_posts_url = "selected";
    } 
    $html .= '<optgroup label="-----------(Home Page)----------">';
    
    $page_id = get_option('page_on_front');
    $page_name = get_post_field( 'post_name', get_option('page_on_front'));
    $page_url = get_post_field( 'post_name', get_option('page_on_front'));
    
    
    $html .= '<option value="'.$page_id.'" data-id="'.$page_id.'" data-value="/'.$page_name.'" class="sqb_urls_list" '.$active_page_posts_url.'>/'.$page_url.'</option>'; 
    
    
    $html .=  '<optgroup label="----------- Pages----------">';
    
    $page_id = get_option('page_on_front');

    $wpb_all_page = get_pages(array('exclude' => array($page_id),'post_type'=>'page', 'post_status'=>'publish', 'posts_per_page'=>500));
    foreach($wpb_all_page as $page){ // $pages is array of object
        $active_page_posts_url = "";
        if(in_array($page->ID,$page_post_ids)){
          $active_page_posts_url = "selected";
        }

      $page_id = get_option('page_on_front');
      $page_name = get_post_field( 'post_name', $page->ID );
      $page_url = get_post_field( 'post_name', $page->ID );
      
      $html .=  '<option  value="'.$page->ID.'" data-id="'.$page->ID.'" data-value="/'.$page_name.'" class="sqb_urls_list" '.$active_page_posts_url.'>/'.$page_url.'</option>';

    }
    
    
    $html .=  '<optgroup label="----------- Post ----------">';
    
    $wpb_all_posts = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>500));
    if ( $wpb_all_posts->have_posts() ) {
      while ( $wpb_all_posts->have_posts() ){ $wpb_all_posts->the_post();
        $active_page_posts_url = "";
        if(in_array(get_the_ID(),$page_post_ids)){
          $active_page_posts_url = "selected";
        }
      $page_id = get_the_ID();
      $page_name = get_post_field( 'post_name', get_the_ID() );
      $page_url = get_post_field( 'post_name', get_the_ID() );
      
      $html .= '<option value="'.$page_id.'" data-id="'.$page_id.'" data-value="/'.$page_name.'" class="sqb_urls_list " '.$active_page_posts_url.'>/'.$page_url.'</option>';
     } 
    wp_reset_postdata();
  }

  $args       = array(
    'public' => true,
    '_builtin' => false,
  );
  $post_types = get_post_types( $args, 'objects' );
  if(!empty($post_types)){
    foreach($post_types as $post_type){
      /*Exclude custom posts used in chatflow*/
      $exclude = array( 'lbb-chatflow-action', 'lbb-chatflow' );
      if( TRUE === in_array( $post_type->name, $exclude ) )
      continue;
      $post_type_name = $post_type->name;
      $html .=  '<optgroup label="----------- Custom Post - '.$post_type->label.' ----------">';
      $wpb_all_posts = new WP_Query(array('post_type'=> $post_type_name, 'post_status'=>'publish', 'posts_per_page'=>500));
      if ( $wpb_all_posts->have_posts() ) {
        while ( $wpb_all_posts->have_posts() ){ $wpb_all_posts->the_post();
          $active_page_posts_url = "";
          if(in_array(get_the_ID(),$page_post_ids)){
            $active_page_posts_url = "active_page_posts_url";
          }
          $page_id = get_the_ID();
          $page_name = get_post_field( 'post_name', get_the_ID() );
          $page_url = get_post_field( 'post_name', get_the_ID() );
          
          $html .= '<option value="'.$page_id.'" data-id="'.$page_id.'" data-value="/'.$page_name.'" class="sqb_urls_list '.$active_page_posts_url.' ">/'.$page_url.'</option>';
        }
      } 
      wp_reset_postdata();
    }
  }
  return $html;            
}


function LBBgetActiveCampaignLists($url , $key){

  if(!current_user_can('administrator')) {
    echo 'Invalid request';die;
  }

  $url = $url;
  $api_key = $key;
  
  $params = array(
    'api_key'      => $api_key,
    'api_action'   => 'list_list',
    'api_output'   => 'serialize',
    'ids'          => 'all',
  );

  $query = "";
  foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
  $query = rtrim($query, '& ');
  
  $url = rtrim($url, '/ ');

  if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

  if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
    die('JSON not supported. (introduced in PHP 5.2.0)');
  }

  $api = $url . '/admin/api.php?' . $query;

  $request = curl_init($api); 
  curl_setopt($request, CURLOPT_HEADER, 0); 
  curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
  $response = (string)curl_exec($request);

  curl_close($request); 

  if ( !$response ) {
    return;
  }
  else{
    $result = unserialize($response);
    /* Unsetting extra data from array so that only list of lists is returned */
    unset($result['result_code']);
    unset($result['result_message']);
    unset($result['result_output']);
  }
  return $result;
}

function LBBgetAweberLists($consumerKey ,$consumerSecret ,$accessKey, $accessSecret){
  
  //  require_once(plugin_dir_path(__FILE__) . '../plugins/aweberSQB/aweber_api/aweber_api.php');
  include(SMART_AUTOMATION_PATH.'/includes/aweber/aweber_api/aweber_api.php');
  try{
    $aweber = new AWeberAPI($consumerKey, $consumerSecret);
    $account = $aweber->getAccount($accessKey, $accessSecret);
    $aweber_user = $aweber->loadFromUrl('https://api.aweber.com/1.0/accounts');
    $id = $aweber_user->data['entries'][0]['id'];
    $lists = $aweber->loadFromUrl('https://api.aweber.com/1.0/accounts/'.$id.'/lists');
    
    $list_data = $lists->data['entries'];
    $count = count($list_data);
    $listt = array();
    for($i=0;$i<$count;$i++){
      $listt[$i]['id'] = $list_data[$i]['id'];
      $listt[$i]['name'] = $list_data[$i]['name'];
    }
    
    if(isset($lists->data['next_collection_link'])){
      $listt = LBBgetAweberListsMore($listt,$aweber,$lists);
    }
    
    return $listt;
  }
  catch(Exception $e){
    return array();
  }
}

function LBBgetAweberListsMore($old_list,$aweber,$lists){
  
  if(isset($lists->data['next_collection_link'])){
      
      $lists = $aweber->loadFromUrl($lists->data['next_collection_link']);
      $list_data = $lists->data['entries'];
      
      $count = count($list_data);
      $k = count($old_list)-1;
      for($i=0;$i<$count;$i++){
        $k++;
        $old_list[$k]['id'] = $list_data[$i]['id'];
        $old_list[$k]['name'] = $list_data[$i]['name'];
        
      }
      if(isset($lists->data['next_collection_link'])){
        $lists_data = LBBgetAweberListsMore($old_list,$aweber,$lists);
      }
    } 
    
  return $old_list;
  
}

function LBBgetConvertkitSequence($key , $secret){
  $api_key = $key;
  $api_secret = $secret;
  
  $url = "https://api.convertkit.com/v3/sequences?api_key=".$api_key;
  $curl = curl_init ( $url );
  curl_setopt ( $curl, CURLOPT_USERAGENT, 'STWR-CONVERTKIT' );
  curl_setopt ( $curl, CURLOPT_HEADER, TRUE );
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
  curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
  curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, TRUE );
  curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
  curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec ( $curl ); 
  if(!$response){
  }
  else{
    $str = strpos($response,'{');
    $res_string = substr($response,$str);
    $result = json_decode($res_string);
    $courses = $result->courses;
    $count = 0;
    if(is_array($courses)){
      $count = count($courses);
    }
    
    $course = array();
    for($i=0;$i<$count;$i++){
      $course[$i]['id'] = $courses[$i]->id;
      $course[$i]['name'] = $courses[$i]->name;
    }
    return $course;
  }
}

function LBBgetConvertkitForms($key , $secret){
  $api_key = $key;
  $api_secret = $secret;
  
  $url = "https://api.convertkit.com/v3/forms?api_key=".$api_key;
  $curl = curl_init ( $url );
  curl_setopt ( $curl, CURLOPT_USERAGENT, 'STWR-CONVERTKIT' );
  curl_setopt ( $curl, CURLOPT_HEADER, TRUE );
  curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
  curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
  curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, TRUE );
  curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
  curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec ( $curl ); 
  if(!$response){
  }
  else{
    $str = strpos($response,'{'); 
    $res_string = substr($response,$str);
    $result = json_decode($res_string);
    $forms = $result->forms;
    $count = 0;
    if(is_array($forms)){
      $count = count($forms);
    }
    $form = array();
    for($i=0;$i<$count;$i++){
      $form[$i]['id'] = $forms[$i]->id;
      $form[$i]['name'] = $forms[$i]->name;
    }
    return $form;
  }
}

function LBBgetMailchimpLists($key){
  $api_key = $key;
  
  
  $list = array();
  $dataCenter = substr($api_key,strpos($api_key,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists?count=500';

  $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                                                                              
    $result = curl_exec($ch);
    $result1 = json_decode($result);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
   
    if($httpCode == "200"){
    $lists = $result1->lists;
    $i = 0;
    
    foreach($lists as $key){
       $list[$i]['id'] = $key->id;
       $list[$i]['name'] = $key->name;
       $i++;
    }
  }
  else{
  }
  
  return $list;
  
}

function LBBgetSendInBlueList($api_key = ''){
  
    if($api_key == ''){
      $response = '';
      return $response;
    }
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists?offset=0&sort=desc",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Api-Key: ".$api_key
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
     //"cURL Error #:" . $err;
    } else {
      return $response;
    }
  
}

function LBBgetGETRESPONSEList($api_key = ''){
  if($api_key == ''){
      $response = '';
      return $response;
  }

  //require_once plugin_dir_path(__FILE__) . '../plugins/getresponseSQB/getresponseSQB.class.php';
  

  $get_reponse_obj  = new SmartAutomation_Getresponse($api_key);
  $campaigns_list = $get_reponse_obj->getCampaigns();
  
  return $campaigns_list;

}

function LBBgetMailerliteGroupList($api_key = ''){
  if($api_key == ''){
      $response = '';
      return $response;
  }

  //require_once plugin_dir_path(__FILE__) . '../plugins/mailerliteSQB/mailerliteSQB.class.php';
  
  $get_reponse_obj  = new SmartAutomation_Mailerlite($api_key);
  $campaigns_list = $get_reponse_obj->getGroupList();
  
  return $campaigns_list;

}

function LBBgetSendfoxList($api_key = ''){
  if($api_key == ''){
      $response = '';
      return $response;
  }

  // require_once plugin_dir_path(__FILE__) . '../plugins/sendfoxSQB/sendfoxSQB.class.php';
  
  $get_reponse_obj  = new SmartAutomation_Sendfox($api_key);
  $list = $get_reponse_obj->getLists();
  
  return $list;

}

function LBBgetMoosendList($api_key = ''){
  if($api_key == ''){
      $response = '';
      return $response;
  }

  // require_once plugin_dir_path(__FILE__) . '../plugins/moosendSQB/moosendSQB.class.php';
  
  $get_reponse_obj  = new SmartAutomation_Moosend($api_key);
  $list = $get_reponse_obj->getLists();
  
  return $list;

}

function LBBgetCommonList($api_key = '', $autoresponder_small_letter = ''){
  if($api_key == ''){
      $response = '';
      return $response;
  }
  

//  require_once plugin_dir_path(__FILE__) . '../plugins/'.$autoresponder_small_letter.'SQB/'.$autoresponder_small_letter.'SQB.class.php';
  $autoresponder_class = 'SmartAutomation_'.$autoresponder_small_letter;
  $get_reponse_obj  = new $autoresponder_class($api_key);
  $list = $get_reponse_obj->getLists();
  
  return $list;

}

add_action('wp_ajax_lbb_autoresponder_test_webhook', 'LBBAutoresponderTestWebhookAjax');

function LBBAutoresponderTestWebhookAjax(){
  //$automation_data = get_option('lbb_automation_'.$automation_name);
  $url = $_REQUEST['webhook'];
  $email = get_option('admin_email');
  
  if($email == ''){
    $email = "test@".$_SERVER['SERVER_NAME'];
  }

  $fields['first_name'] =  'Joy Test';
  $fields['email']    =  $email;
  $fields['chatflow_id']    =  '59';
  $fields['chatflow_name']  =  'Demo';
  $fields['chatflow_type']  =  'logicbot';
    
  $apiparams = array('action'=>'REGISTRATON','fields'=> $fields );
   
  $output = array();
  try{
    $curl = curl_init ( $url );
    if ($curl) {
      curl_setopt ( $curl, CURLOPT_USERAGENT, 'stwrconnect' );
      curl_setopt ( $curl, CURLOPT_HEADER, FALSE );
      curl_setopt ( $curl, CURLOPT_POST, TRUE );
      curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
      curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
      curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, TRUE );
      curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, 30 );
      curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 );
      curl_setopt ( $curl, CURLOPT_POSTFIELDS, http_build_query ( $apiparams ) );
      $response = curl_exec ( $curl );
      if (curl_errno ( $curl ) > 0) {
        $output['error'] =  "Sorry, could not connect. Please check your Webhook URL";
      } else {
        
        $data['autoresponder'] = 'STWRCONNECT';
        $data['zap_url'] = $url;
        
        $output['success'] = "Webhook Tested Successfully";
        $output['data'] = $data;
        $output['fields'] = $fields;
        //$output['save_return'] = stwr_save_autoresponder_data($data);;
      }
      curl_close ( $curl );
    }
  } /* try*/
  catch(Exception $e){
  }
  echo json_encode($output);
  die;
}

include(LBB_ABS_URL.'admin/admin-conversations.php');

add_action('wp_ajax_lbb_save_checked_customfield_ids', 'lbb_save_checked_customfield_ids');
function lbb_save_checked_customfield_ids(){
  $checked_ids = $_REQUEST['checked_ids'];
  update_option('lbb_checked_customfield_ids', $checked_ids);
  $output = "success";
  $data = lbb_load_custom_fields_data();
  echo json_encode($data);die;
}

add_action('wp_ajax_lbb_load_chatflow_styles', 'lbb_load_chatflow_styles');
function lbb_load_chatflow_styles(){
  $chatflow_id = $_REQUEST['chatflow_id'];
  if(!empty($chatflow_id)){
      $output['heading-font-family'] = (get_post_meta( $chatflow_id, 'heading_font_family', true ))? get_post_meta( $chatflow_id, 'heading_font_family', true ) : 'DM Sans,sans-serif';
       $output['heading-font-size'] = (get_post_meta( $chatflow_id, 'heading_font_size', true ))? get_post_meta( $chatflow_id, 'heading_font_size', true ) : '20';
       $output['heading-font-weight'] = (get_post_meta( $chatflow_id, 'heading_font_weight', true ))? get_post_meta( $chatflow_id, 'heading_font_weight', true ) : '600';
       $output['heading-bg-color'] = (get_post_meta( $chatflow_id, 'heading_background_color', true ))? get_post_meta( $chatflow_id, 'heading_background_color', true ) : '#effeff';
       $output['heading-text-color'] = (get_post_meta( $chatflow_id, 'heading_text_color', true ))? get_post_meta( $chatflow_id, 'heading_text_color', true ) : '#000000';
       $output['content-font-family'] = (get_post_meta( $chatflow_id, 'content_font_family', true ))? get_post_meta( $chatflow_id, 'content_font_family', true ) : 'DM Sans,sans-serif';
       $output['content-font-size'] = (get_post_meta( $chatflow_id, 'content_font_size', true ))? get_post_meta( $chatflow_id, 'content_font_size', true ) : '16';
       $output['content-font-weight'] = (get_post_meta( $chatflow_id, 'content_font_weight', true ))? get_post_meta( $chatflow_id, 'content_font_weight', true ) : '400';
       $output['image-upload'] = (get_post_meta( $chatflow_id, 'image_upload', true ))? get_post_meta( $chatflow_id, 'image_upload', true ) : LBB_URL.'admin/images/avatar.png';
   
       $output['bot-user-image'] = (get_post_meta( $chatflow_id, 'bot_user_image', true ))? get_post_meta( $chatflow_id, 'bot_user_image', true ) : LBB_URL.'admin/images/avatar.png';
       $output['submit-button-icon'] = (get_post_meta( $chatflow_id, 'submit_button_icon', true ))? get_post_meta( $chatflow_id, 'submit-button-icon', true ) : LBB_URL.'admin/images/avatar.png';
       $output['lbb-automation-status'] = (get_post_meta( $chatflow_id, '_lbb_automation_status', true ))? get_post_meta( $chatflow_id, '_lbb_automation_status', true ) : [];
       $output['container-width'] = (get_post_meta( $chatflow_id, 'container_width', true ))? get_post_meta( $chatflow_id, 'container_width', true ) : '450';
       /*Ques Settings*/
   
       $output['ques-bg-color'] = (get_post_meta( $chatflow_id, 'ques_bg_color', true ))? get_post_meta( $chatflow_id, 'ques_bg_color', true ) : '#ffffff';
       $output['ques-text-color'] = (get_post_meta( $chatflow_id, 'ques_text_color', true ))? get_post_meta( $chatflow_id, 'ques_text_color', true ) : '#000000';
       $output['ans-bg-color'] = (get_post_meta( $chatflow_id, 'ans_bg_color', true ))? get_post_meta( $chatflow_id, 'ans_bg_color', true ) : '#000000';
       $output['ans-text-color'] = (get_post_meta( $chatflow_id, 'ans_text_color', true ))? get_post_meta( $chatflow_id, 'ans_text_color', true ) : '#ffffff';
   
       /*Icon Settings*/
       $output['icon-color'] = (get_post_meta( $chatflow_id, 'lbb_icon_color', true ))? get_post_meta( $chatflow_id, 'lbb_icon_color', true ) : '#0066ff';
       $output['icon-background-color'] = (get_post_meta( $chatflow_id, 'lbb_icon_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_icon_background_color', true ) : '#effeff';
       $output['icon-border-radius'] = (get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ) : '5';
       $output['icon-size'] = (get_post_meta( $chatflow_id, 'lbb_icon_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_size', true ) : '30';
       $output['icon-box-size'] = (get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ) : '60';
       $output['icon-height'] = (get_post_meta( $chatflow_id, 'lbb_icon_height', true ))? get_post_meta( $chatflow_id, 'lbb_icon_height', true ) : '96';
   
       $output['right-spacing'] = (get_post_meta( $chatflow_id, 'right_spacing', true ))? get_post_meta( $chatflow_id, 'right_spacing', true ) : '20';
       $output['left-spacing'] = (get_post_meta( $chatflow_id, 'left_spacing', true ))? get_post_meta( $chatflow_id, 'left_spacing', true ) : '20';
   
       $output['sub-heading-font-size'] = (get_post_meta( $chatflow_id, 'sub_heading_font_size', true ))? get_post_meta( $chatflow_id, 'sub_heading_font_size', true ) : '15';
       $output['sub-heading-font-weight'] = (get_post_meta( $chatflow_id, 'sub_heading_font_weight', true ))? get_post_meta( $chatflow_id, 'sub_heading_font_weight', true ) : '600';
       $output['sub-heading-font-family'] = (get_post_meta( $chatflow_id, 'sub_heading_font_family', true ))? get_post_meta( $chatflow_id, 'sub_heading_font_family', true ) : 'DM Sans,sans-serif';
       $output['answer-btn-border-radius'] = (get_post_meta( $chatflow_id, 'button_border_radius', true ))? get_post_meta( $chatflow_id, 'button_border_radius', true ) : '5';
       $output['button-text-color'] = (get_post_meta( $chatflow_id, 'button_text_color', true ))? get_post_meta( $chatflow_id, 'button_text_color', true ) : '#0066ff';
       $output['button-background-color'] = (get_post_meta( $chatflow_id, 'button_background_color', true ))? get_post_meta( $chatflow_id, 'button_background_color', true ) : '#ffffff';
       $output['chat-background-color'] = (get_post_meta( $chatflow_id, 'chat_background_color', true ))? get_post_meta( $chatflow_id, 'chat_background_color', true ) : '#eaeef3';
       $output['message-background-color'] = (get_post_meta( $chatflow_id, 'message_background_color', true ))? get_post_meta( $chatflow_id, 'message_background_color', true ) : '#ffffff';
       $output['message-text=color'] = (get_post_meta( $chatflow_id, 'message_text_color', true ))? get_post_meta( $chatflow_id, 'message_text_color', true ) : '#00000';
       $output['bottom-spacing'] = (get_post_meta( $chatflow_id, 'bottom_spacing', true ))? get_post_meta( $chatflow_id, 'bottom_spacing', true ) : '20';
       $output['button-border-color'] = (get_post_meta( $chatflow_id, 'button_border_color', true ))? get_post_meta( $chatflow_id, 'button_border_color', true ) : '#0066ff';
       $output['button-font-size'] = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
       $output['answer-button-font-size'] = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
       $output['icon-padding'] = (get_post_meta( $chatflow_id, 'lbb_icon_padding', true ))? get_post_meta( $chatflow_id, 'lbb_icon_padding', true ) : '15';
       $output['icon-width'] = (get_post_meta( $chatflow_id, 'lbb_icon_width', true ))? get_post_meta( $chatflow_id, 'lbb_icon_width', true ) : '100';
       $output['icon-height'] = (get_post_meta( $chatflow_id, 'lbb_icon_height', true ))? get_post_meta( $chatflow_id, 'lbb_icon_height', true ) : '150';
       $output['sub-heading-text-color'] = (get_post_meta( $chatflow_id, 'sub_heading_text_color', true ))? get_post_meta( $chatflow_id, 'sub_heading_text_color', true ) : '#000000';
       echo json_encode($output);die;
  }
}

add_action('wp_ajax_lbb_load_global_styles', 'lbb_load_global_styles');
function lbb_load_global_styles(){
  
  $output = lbb_load_all_global_styles();
  echo json_encode($output);die;

}

function lbb_load_all_global_styles(){
  $output['heading-font-size'] = (get_option('lbb_heading_font_size' ))? get_option('lbb_heading_font_size', true ):'20';
  $output['heading-font-weight'] = (get_option('lbb_heading_font_weight' ))? get_option('lbb_heading_font_weight', true ):'600';
  $output['heading-font-family'] = (get_option('lbb_heading_font_family' ))? get_option('lbb_heading_font_family', true ):'DM Sans,sans-serif';

  $output['heading-bg-color'] = (get_option('lbb_heading_background_color' ))? get_option('lbb_heading_background_color', true ):'#effeff';
  $output['heading-text-color'] = (get_option('lbb_heading_text_color' ))? get_option('lbb_heading_text_color', true ):'#000000';
  $output['sub-heading-font-size'] = (get_option('lbb_sub_heading_font_size' ))? get_option('lbb_sub_heading_font_size', true ):'15';
  $output['sub-heading-font-weight'] = (get_option('lbb_sub_heading_font_weight' ))? get_option('lbb_sub_heading_font_weight', true ):'600';
  $output['sub-heading-font-family'] = (get_option('lbb_sub_heading_font_family' ))? get_option('lbb_sub_heading_font_family', true ):'DM Sans,sans-serif';
  $output['sub-heading-text-color'] = (get_option('lbb_sub_heading_text_color' ))? get_option('lbb_sub_heading_text_color', true ):'#000000';
  $output['content-font-family'] = (get_option('lbb_content_font_family' ))? get_option('lbb_content_font_family', true ):'DM Sans,sans-serif';
  $output['content-font-size'] = (get_option('lbb_content_font_size' ))? get_option('lbb_content_font_size', true ):'16';
  $output['content-font-weight'] = (get_option('lbb_content_font_weight' ))? get_option('lbb_content_font_weight', true ):'400';
  $output['message-background-color'] = (get_option('lbb_message_background_color' ))? get_option('lbb_message_background_color', true ):'#ffffff';
  $output['message-text-color'] = (get_option('lbb_message_text_color' ))? get_option('lbb_message_text_color', true ):'#00000';
  $output['chat-background-color'] = (get_option('lbb_chat_background_color' ))? get_option('lbb_chat_background_color', true ):'#eaeef3';
  $output['button-background-color'] = (get_option('lbb_button_background_color' ))? get_option('lbb_button_background_color', true ):'#ffffff';
  $output['button-text-color'] = (get_option('lbb_button_text_color' ))? get_option('lbb_button_text_color', true ):'#0066ff';
  $output['button-border-color'] = (get_option('lbb_button_border_color' ))? get_option('lbb_button_border_color', true ):'#0066ff';
  $output['answer-btn-font-size'] = (get_option('lbb_answer_button_font_size' ))? get_option('lbb_answer_button_font_size', true ):'16';
  $output['answer-btn-border-radius'] = (get_option('lbb_button_border_radius' ))? get_option('lbb_button_border_radius', true ):'5';
  $output['chat-alignment'] = (get_option('lbb_chat_alignment' ))? get_option('lbb_chat_alignment', true ):'left';
  $output['right-spacing'] = (get_option('lbb_right_spacing' ))? get_option('lbb_right_spacing', true ):'20';
  $output['left-spacing'] = (get_option('lbb_left_spacing' ))? get_option('lbb_left_spacing', true ):'20';
  $output['bottom-spacing'] = (get_option('lbb_bottom_spacing' ))? get_option('lbb_bottom_spacing', true ):'20';
  $output['ques-bg-color'] = (get_option('lbb_ques_bg_color' ))? get_option('lbb_ques_bg_color', true ):'#ffffff';
  $output['ques-text-color'] = (get_option('lbb_ques_text_color' ))? get_option('lbb_ques_text_color', true ):'#000000';
  $output['ans-bg-color'] = (get_option('lbb_ans_bg_color' ))? get_option('lbb_ans_bg_color', true ):'#000000';
  $output['ans-text-color'] = (get_option('lbb_ans_text_color' ))? get_option('lbb_ans_text_color', true ):'#ffffff';
  $output['submit-button-icon'] = (get_option('lbb_submit_button_icon' ))? get_option('lbb_submit_button_icon', true ): LBB_URL.'admin/images/avatar.png';
  $output['bot-user-image'] = (get_option('lbb_bot_user_image' ))? get_option('lbb_bot_user_image', true ): LBB_URL.'admin/images/avatar.png';
  $output['start-again'] = (get_option('lbb_start_again' ))? get_option('lbb_start_again', true ):'';
  $output['icon-color'] = (get_option('lbb_icon_color' ))? get_option('lbb_icon_color', true ):'#0066ff';
  $output['icon-background-color'] = (get_option('lbb_icon_background_color' ))? get_option('lbb_icon_background_color', true ):'#effeff';
  $output['icon-border-radius'] = (get_option('lbb_icon_border_radius' ))? get_option('lbb_icon_border_radius', true ):'5';
  $output['icon-btn-size'] = (get_option('lbb_icon_size' ))? get_option('lbb_icon_size', true ):'30';
  $output['icon-box-size'] = (get_option('lbb_icon_box_size' ))? get_option('lbb_icon_box_size', true ):'60';
  $output['icon-padding'] = (get_option('lbb_icon_padding' ))? get_option('lbb_icon_padding', true ):'15';
  $output['icon-height'] = (get_option('lbb_icon_height' ))? get_option('lbb_icon_height', true ):'150';
  $output['icon-width'] = (get_option('lbb_icon_width' ))? get_option('lbb_icon_width', true ):'100';
  $output['image-upload'] = (get_option('lbb_image_upload' ))? get_option('lbb_image_upload', true ): LBB_URL.'admin/images/avatar.png';
  $output['container-width'] = (get_option('lbb_container_width' ))? get_option('lbb_container_width', true ):'450';
  return $output;
}

function lbb_load_custom_fields_data(){
  $checked_custom_fields = get_option('lbb_checked_customfield_ids');
  $explode_custom_field_ids = [];
  if($checked_custom_fields){
    $explode_custom_field_ids = explode(',', $checked_custom_fields);
  }

  /*Custom Fields Data Start*/
  $custom_field_dropdown = "";
  $custom_field_name = "";
  $get_reponse_obj  = new CustomFieldManager();
  $custom_fields = $get_reponse_obj->loadAll();
  if($custom_fields){
    foreach($custom_fields as $custom_field){
      $show_checked = "";
      $show_hide_row = "display:none;";
      if (in_array($custom_field['id'], $explode_custom_field_ids)){
        $show_checked = "checked";
        $show_hide_row = "";
      }

      $custom_field_dropdown .=   '<label class="dropdown-option">
                <input type="checkbox" name="dropdown-group" '.$show_checked.' value="'.$custom_field['id'].'" />
                '.$custom_field['name'].'
              </label>';

        $custom_field_name .= '<th style="'.$show_hide_row.'">'.$custom_field['name'].'</th>';
    } 
  }
  /*Custom Fields Data End*/

  $chatObj = new ConversationManager();
  $conversation_ids = $chatObj->getAllUniqueConversation();
  if($conversation_ids){
    $userId = '';
    foreach($conversation_ids as $conversation_id){
      $userId .= $conversation_id['user_id'].',';
    } 
  }
  $userId = trim($userId, ',');
 
  $contacts_data = new LBB_Contacts;
  $users = $contacts_data->loadAllByStatus('1');

  $contact_details = "";
  foreach($users as $user){
      $name = $user['firstname'];
      $email = $user['email'];

      $custom_field_td = "";
      if($custom_fields){
        
        foreach($custom_fields as $custom_field){
          $show_hide_row = "display:none";
          if (in_array($custom_field['id'], $explode_custom_field_ids)){
            $show_hide_row = "";
          }

          $custom_field_data =  lbb_get_contact_meta($user['id'], 'lbb_property_'.$custom_field['id'] ,true);
          $custom_field_td .= '<td style="'.$show_hide_row.'">'.$custom_field_data.'</td>';
        } 
      }
      $contact_details .= "<tr><td>".$name."</td><td>".$email."</td>".$custom_field_td."</tr>";
    //}
  }

  $all_data = '<table class="lbb-table show-table-data"><thead>
              <tr>
                <th class="lbb-tw-40">Name</th>
                <th class="lbb-tw-12">Email</th>
                '.$custom_field_name.'
              </tr>
            </thead>
            <tbody class="customfield-data">
              '.$contact_details.'
            </tbody></table>';
  return $all_data;
}

add_action('wp_ajax_lbb_load_contacts_data', 'lbb_load_contacts_data');
function lbb_load_contacts_data(){
    if(isset($_POST["conversationid"])){
        $conversation_id = $_POST['conversationid'];
        $messageManager = new MessageManager();
        $chat_list = $messageManager->getMessagesByConversationIdWithoutOffset($conversation_id);


        $chat_list = array_reverse($chat_list);
        $userchat_list_html = "";
        $side_popup_data = "";
        $prev_msg_date = 0;

        ob_start();
        include(LBB_ABS_URL.'admin/templates/chat/attachment.php');
        $attachment_html = ob_get_clean();

        foreach($chat_list as $single_msg){
          $message = stripslashes($single_msg['message_text']);
          $message = strip_tags($message);

          $message_meta = maybe_unserialize($single_msg['message_meta']);
          $image = isset($message_meta['image']) ? $message_meta['image'] : '';
          $attachment = isset($message_meta['attachment']) ? $message_meta['attachment'] : '';
          $attachment_name = isset($message_meta['name']) ? $message_meta['name'] : 'Download';

          $audio = isset($message_meta['audio']) ? $message_meta['audio'] : '';
          ob_start();
                include(LBB_ABS_URL.'admin/templates/chat/audio.php');
                $audio_html = ob_get_clean();

          if(!empty($attachment)){
            $message = str_replace('{{url}}',$attachment , $attachment_html);
            $message = str_replace('{{text}}', $attachment_name, $message);
          }else if(!empty($audio)){
            $message = str_replace('{{url}}',$audio , $audio_html);
          }

          $post_id = $single_msg['action_id'];
          $type = get_post_meta( $post_id, 'question_type', true );
          
          $main_admin_html = '';
          if($type == 'outcome' && get_post_meta( $post_id, 'enable_pdf_download', true ) == 1){
            
            $pdf_text = get_post_meta( $post_id, 'download_pdf_button', true );
            
                $action['pdf_button'] = array(
                    'label' => $pdf_text,
                    'link' => site_url().'?lbb-pdf-download=1&sid='.$conversation_id.'&outcome_post_id='.$post_id
                );

                ob_start();
                include(LBB_ABS_URL.'admin/templates/chat/agent-pdf-button.php');
                $pdf_button_html = ob_get_clean();

                //$main_admin_html = str_replace('{{pdf_buttons}}', $pdf_button_html, $main_admin_html);
                $main_admin_html = str_replace('{{pdf_link}}',$action['pdf_button']['link'], $pdf_button_html);
                $main_admin_html = str_replace('{{pdf_label}}',$action['pdf_button']['label'], $main_admin_html);
            }else{
                $main_admin_html = str_replace('{{pdf_buttons}}', '', $main_admin_html);
            }

          $time = $single_msg['sent_time'];
          $is_bot_response = $single_msg['is_bot_response'];
          $user_id = $single_msg['user_id'];
          $agent_id = $single_msg['agent_id'];


         /* $newTZ = new DateTimeZone("UTC");
          $date = new DateTime( $time , $UTC );
          $date->setTimezone( $newTZ );*/

          $new_timezone = "Asia/Calcutta";
          $new_timezone = date_default_timezone_get();
          $UTC = new DateTimeZone("UTC");
          $newTZ = new DateTimeZone($new_timezone);
          $date = new DateTime( $time , $UTC );
          $date->setTimezone( $newTZ );
          $time_time = $date->format('H:i');
          $msg_date = $date->format('F d Y');
          if($is_bot_response == 1){
            $response = 'user-admin-list';
            $name = "Bot";
            $avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g';
          }else if($agent_id > 0){
            $response = 'user-admin-list';
          }else{
            $response = 'user-visitor-list chat-top-msg';
            if($user_id == 0){
                $name = "Unknown Visitor";
            }else{
                

                $contact_id = lbb_get_contact_id($conversation_id);
                $contact_status = lbb_get_contact_status($contact_id);
                if($contact_status > 0){
                    $name = lbb_get_contact_name($contact_id);
                }else{
                    $name = lbb_get_user_name($user_id);
                }
            }
            $avatar = lbb_user_avatar($user_id);
          }
          if ($prev_msg_date != $msg_date) {
            $userchat_list_html.= '<li class="msg-day-wrapper"> <div class="msg-date-show"> <div class="msg-date-border"></div> <span>'.$msg_date.'</span> <div class="msg-date-border"></div></div> </li>';
          }
            $tag_ids = $single_msg['tags'];
            $tags = lbb_print_tags($tag_ids);
          $userchat_list_html .= '<li class="'.$response.'">
          <div class="message-box-img">
            <span class="chat-icon"><img src="'.$avatar.'"></span>
          </div>
              <div class="message-box-data">
                <div class="message-data">
                  <span class="message-data-name"> '.$name.'</span>
                  <span class="message-data-time">'.$time_time.'</span>';
                  if($tags){
                  $userchat_list_html .= '<span class="message-data-tags">'.$tags.'</span>';

                  }
                $userchat_list_html .= '</div>
                <div class="message my-message">
                  <div class="lbb-user-message">'.$message.$main_admin_html.'</div>
                </div>
              </div>
        </li>';
        $prev_msg_date = $date->format('F d Y');
        }

    $con = new ConversationManager();
    $rs = $con->getConversationById($conversation_id);
    $user_id = $rs['user_id'];
    $chatflow_id = $rs['chatflow_id'];


    $contacts = new LBB_Contacts;
    $contacts = $contacts->getContactByConversationId($conversation_id);
    if($user_id == 0){
        if($contacts){
            $name = $contacts['firstname'];
            $email = $contacts['email'];
        }else{
            $name = "Unknown Visitor";
            $email = '-';
        }
        $avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&d=mp&r=g';
    }else{
        $contact_id = lbb_get_contact_id($conversation_id);
        $contact_status = lbb_get_contact_status($contact_id);
        if($contact_status > 0){
            $name = lbb_get_contact_name($contact_id);
            if($contacts){
                $email = $contacts['email'];
            }
        }else{
            $name = lbb_get_user_name($user_id);
            $email = lbb_get_user_email($user_id);
            if(empty($email) && $contacts){
                $email = $contacts['email'];
            }
        }
        //$name = lbb_get_user_name($user_id);
        
        $avatar = lbb_user_avatar($user_id);
    }

   
    if (strpos($email, "guest_") === 0 && substr($email, -1) === "@") {
        $email = "";
    } 

    $custom_field_data = "";
    /*Custom Field Data */
    if($contacts){
        $contact_id = $contacts['id'];
        $contactsMeta = new LBB_Contacts;
        $contactsMeta = $contactsMeta->loadContactmetaByContactId($contact_id);
        if($contactsMeta){
            foreach($contactsMeta as $contactMeta){
                $string = $contactMeta['field_name'];
                $parts = explode("_", $string);
                $cf_id = end($parts);

                $customField = new CustomFieldManager();
                $customField = $customField->loadById($cf_id);
                if($customField){
                    $custom_field_data .= '<div class="lbb-user-info-meta-row">

                            <div class="user-info-field-lbl">
                                <label>'.$customField['label'].':</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$contactMeta['field_value'].'</div>
                            </div>
                            
                        </div>';
                }
            }
        }
    }

    //echo $custom_field_data;

    $extra_info = $rs['extra_info'];
    $ip_address = $rs['ip_address'];
    $start_time = $rs['start_time'];
    $date = new DateTime();

    // Format the date
    $formattedDate = $date->format('D, M jS');
    $formattedTime = $date->format('H:i');


    $location = "";
    $browser = "";
    $url = "";
    $contact = "";
    if($extra_info){
        $extra_info = unserialize($extra_info);

        
        $location = $extra_info['location'];
        
        $location = lbbgetCountryName($location);;

        $browser = $extra_info['browser'];
        $url = $extra_info['url'];
    }

    $encodedEmail = urlencode($email);
    $conversation_url = home_url().'/wp-admin/admin.php?page=listbuildingbot-conversation&conversation_id='.$conversation_id;
    $side_popup_data .= '<div class="lbb-user-personal-info-heading">
                        <h3>User Details</h3>
                    </div>
                <div class="lbb-user-info-meta-container">

                    <div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>Bot Name:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.get_the_title($chatflow_id).'</div>
                            </div>
                    </div>

                    <div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>User Name:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$name.'</div>
                            </div>
                    </div>

                    <div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>Email:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$email.'</div>
                            </div>
                    </div>';

                if($location){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                                <label>Location:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$location.'</div>
                            </div>

                    </div>';
                }

                if($browser){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>Browser:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$browser.'</div>
                            </div>
                    </div>';
                }
                if($url){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                                <label>URL:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$url.'</div>
                            </div>
                    </div>';
                }

                if($ip_address){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">

                            <div class="user-info-field-lbl">
                                <label>IP Address:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$ip_address.'</div>
                            </div>
                    </div>';
                }

                $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                                <label>Created:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$formattedDate.' at '.$formattedTime.'</div>
                            </div>
                    </div>
                    <div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                                <label>Conversation Id:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value"><a target="_blank" href="'.$conversation_url.'">'.$conversation_id.'</a></div>
                            </div>
                    </div>
                    '.$custom_field_data.'
                </div><div class="lbb-user-personal-info-heading">
                    <h3>Chat History</h3>
                </div><div class="lbb-user-info-meta-container lbb-contacts-chat-history"><ul>'.$userchat_list_html.'</ul></div>';

                    

                //$side_popup_data .= $custom_field_data;
                $side_popup_data .= '</div>';
    $output['side_popup_data'] = $side_popup_data;
    }
    echo json_encode($output);die;
}
add_action('wp_ajax_lbb_show_pagination', 'lbb_show_pagination');
function lbb_show_pagination(){
  $draw = $_POST['draw'];
  $row = $_POST['start'];
  $rowperpage = $_POST['length']; // Rows display per page
  $columnIndex = $_POST['order'][0]['column']; // Column index
  $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
  $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
  $searchValue = $_POST['search']['value']; // Search value
  $custom_search = $_POST['searchValue']; // Search value
  if($custom_search){
    $searchValue = $custom_search;
  }
  /*Custom Fields Data*/
  $checked_custom_fields = get_option('lbb_checked_customfield_ids');
  $explode_custom_field_ids = [];
  if($checked_custom_fields){
    $explode_custom_field_ids = explode(',', $checked_custom_fields);
  }

  $get_reponse_obj  = new CustomFieldManager();
  $custom_fields = $get_reponse_obj->loadAll();
  /**/
  ## Search 
  $searchQuery = " ";
  if($searchValue != ''){
    $searchQuery = " AND (firstname LIKE '%".$searchValue."%' OR email LIKE '%".$searchValue."%'";
    $searchQuery .= ")";
  }
  global $wpdb;
  $tableName = $wpdb->prefix . 'lbb_contacts';

  $sql = "SELECT COUNT(*) AS allcount FROM " . $tableName." where (`status` = '0' OR `status` = '1')";  
  $records = $wpdb->get_var($sql);
  $totalRecords = $records;

  $sql = "SELECT COUNT(*) AS allcount FROM " . $tableName." where (`status` = '0' OR `status` = '1')".$searchQuery;  
  $records = $wpdb->get_var($sql);
  $totalRecordwithFilter = $records;

  ## Fetch records
  $sql = "SELECT * FROM " . $tableName." WHERE (`status` = '0' OR `status` = '1') ".$searchQuery. " ORDER BY id desc LIMIT $row,$rowperpage";
  $allRecords = $wpdb->get_results($sql, ARRAY_A);

  if($allRecords){
    $data = array();
  foreach($allRecords as $key => $allRecord){

    

        $conversation_data = new ConversationManager();
        $conversation_data = $conversation_data->getConversationById($allRecord['conversation_id']);
        $chatflow_id = $conversation_data['chatflow_id'];
        $extra_info = $conversation_data['extra_info'];
        $ip_address = $conversation_data['ip_address'];
        $status = $conversation_data['status'];
        $extra_info = unserialize($extra_info);

        if($status == ''){
            $status = 'B';
        }

        $conversation_url = home_url().'/wp-admin/admin.php?page=listbuildingbot-conversation&mode='.$status.'&conversation_id='.$allRecord['conversation_id'];

        $url = $extra_info['url'];
        $location = $extra_info['location'];

        /*if(strlen($url) > 15) {
            $trun_url = substr($url, 0, 15) . '...';
          }*/

        $chatflow_title = "";
        if($chatflow_id && $chatflow_id != 0){
            $chatflow_title = get_the_title($chatflow_id);
        }

      $allRecord_email = urldecode($allRecord['email']);
      if (strpos($allRecord_email, "guest_") === 0 && substr($allRecord_email, -1) === "@") {
            // String starts with "guest_" and ends with "@"
            $allRecord_email = "-";
        } 

      $data = array(
          "firstname"=>$allRecord['firstname'].' '.$allRecord['lasttname'],
          "email"=>$allRecord_email,
          "chatflow_title"=>$chatflow_title,
          "url"=>'<a class="lbb-link-text-table" href="'.$url.'" target="_blank" title="'.$url.'">'.$url.'</a>',
          "conversations_id"=> '<div class="lbb-strong-link-type-left-right"><a class="lbb-conversation-id" href="'.$conversation_url.'" target="_blank">'.$allRecord['conversation_id'].'</a> <a class="lbb-contacts-details lbb-icon-transparent-btn" href="javascript:void(0)" data-conversationId="'.$allRecord['conversation_id'].'">View</a></div>',
          "date"=>$allRecord['created_date'],
          "action"=>'<a title="Delete" class="lbb-delete-contact lbb-icon-transparent-btn lbb-delete-btn" href="javascript:void(0);"> <span class="dashicons dashicons-trash"></span> Delete </a>',
          "location"=>$location,
          "ip_address"=>$ip_address,
        );

        if($custom_fields){
        
          foreach($custom_fields as $custom_field){
            /*$show_hide_row = "display:none";
            if (in_array($custom_field['id'], $explode_custom_field_ids)){
              $show_hide_row = "";
            }*/

            $custom_field_data = lbb_get_contact_meta($allRecord['id'], 'lbb_property_'.$custom_field['id']);
            $data['lbb_custom_field_'.$custom_field['id']] =  $custom_field_data;
          }
        }

        $dataFinal[$key] = $data;

  }
  }else{
    $dataFinal = array();
  }
  
        //die();
  ## Response
  $response = array(
      "draw" => intval($draw),
      "iTotalRecords" => $totalRecords,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $dataFinal
  );
  echo json_encode($response);die;
}

add_action('wp_ajax_save_mapping_data', 'save_mapping_data');
function save_mapping_data(){
  $chatflow_id = $_REQUEST['chatflow_id'];
  $pdf_id = $_REQUEST['pdf_id'];
  $outcome_id = $_REQUEST['outcome_id'];

  $pdf_outcome_mapping = get_post_meta($chatflow_id, 'pdf_outcome_mapping', true);
  
  if($pdf_outcome_mapping){
    //$pdf_outcome_mapping = unserialize($pdf_outcome_mapping);
    $outcomeIdExists = false;

    foreach ($pdf_outcome_mapping as &$subArray) {
        if ($subArray['outcome_id'] == $outcome_id) {
            $outcomeIdExists = true;
            $subArray['pdf_id'] = $pdf_id;
            break;
        }
    }

    if (!$outcomeIdExists) {
        $newArray = array(
            'pdf_id' => $pdf_id,
            'outcome_id' => $outcome_id
        );
        $pdf_outcome_mapping[] = $newArray;
    }
    update_post_meta($chatflow_id, 'pdf_outcome_mapping', $pdf_outcome_mapping);
  }else{
    $mapping_data[] = array(
                    'pdf_id' => $pdf_id,
                    'outcome_id' => $outcome_id,
                    );
    update_post_meta($chatflow_id, 'pdf_outcome_mapping', $mapping_data);
  }
}

add_action('wp_ajax_load_mapping_data', 'load_mapping_data');
function load_mapping_data(){
  $chatflow_id = $_REQUEST['chatflow_id'];
  $outcome_id = $_REQUEST['outcome_id'];
  if($chatflow_id && $outcome_id){
    $pdf_outcome_mapping = get_post_meta($chatflow_id, 'pdf_outcome_mapping', true);
    if($pdf_outcome_mapping){
      foreach($pdf_outcome_mapping as $pdf_outcome){
        if ($pdf_outcome['outcome_id'] == $outcome_id) {
            $pdf_id = $pdf_outcome['pdf_id'];
            break;
        }
      }
      $output['pdf_id'] = $pdf_id;
    }else{
      $output['no_data'] = "No Data";  
    }
  }else{
    $output['no_data'] = "No data";
  }
  echo json_encode($output);die;
}

add_action('wp_ajax_chatflow_mapping_data', 'chatflow_mapping_data');
function chatflow_mapping_data(){
  $chatflow_id = $_REQUEST['chatflow_id'];

  /*Outcome Data */

  $outcome_data = new OutcomesManager;
  $outcome_data = $outcome_data->loadByChatflowId($chatflow_id);
    
  
          if($outcome_data){
            $chatflow_data .= '<div class="lbb-form-group lbb-chatflow-field">
              <label for="lbb_outcome_id">Select an outcome:</label>
              <select id="lbb_outcome_id" name="lbb_outcome_id" class="lbb-input-field">
              ';
            $chatflow_data .= "<option>Please Select Outcome</option>";
            foreach($outcome_data as $outcome){
                $chatflow_data .= '<option value="'.$outcome['id'].'">'.$outcome['name'].'</option>';
            }
            $chatflow_data .= '</select>
              </div>';
          }else{
            $chatflow_data .= '<div class="no-outcome">No outcomes found. Please create one or more outcomes.<a href="'.site_url().'/wp-admin/admin.php?page=listbuildingbot-settings&tab=outcome> Click here </a>to create outcome.</div>';
          }
            

  /*PDF Data */

  $pdfcontent = new PDFContentManager();
  $pdf_data = $pdfcontent->loadAll();
  $pdf_builder .= '<div class="lbb-form-group lbb-chatflow-field">
              <label for="lbb_pdf_id">Connect PDF to Outcome: (users will be able to download PDF based on outcome)</label>
              <select id="lbb_pdf_id" name="lbb_pdf_id" class="lbb-input-field">
              
              ';
            if($pdf_data){
              $pdf_builder .= '<option value="0">Select a PDF</option>';
              foreach($pdf_data as $pdf){
                  $pdf_builder .= '<option value="'.$pdf['id'].'">'.$pdf['name'].'</option>';
              }
            }else{
              $pdf_builder .= '<option>Please create PDF</option>';
            }
              $pdf_builder .= '</select>
          </div>';

  

    $output = array('pdf_builder' => $pdf_builder, 'chatflow_data' => $chatflow_data);
    echo json_encode($output);die;
}

function duplicate_post($post_id) {
 
    $post = get_post($post_id);
    if (!isset($post) || $post == null) {
        return;
    }
    $new_post = array(
        'post_title' => $post->post_title . ' (Copy)',
        'post_content' => $post->post_content,
        'post_status' => $post->post_status,
        'post_type' => $post->post_type,
    );
    $new_post_id = wp_insert_post($new_post);
    if ($new_post_id) {
        $post_meta = get_post_custom($post_id);
        foreach ($post_meta as $key => $value) {
            if($key == "selected_url"){

            }else{
                 $unserialized_result = maybe_unserialize($value[0]);
                if ($unserialized_result !== false) {
                    update_post_meta($new_post_id, $key, $unserialized_result);
                }else{
                    update_post_meta($new_post_id, $key, $value[0]);

                }
            }
        }
    }

    $jsonData = get_post_meta($new_post_id, '_questions_drawflow', true);;

    $dataArray = json_decode($jsonData, true);

    // Access the question IDs
    $questionIds = [];

    foreach ($dataArray['drawflow']['Home']['data'] as $node) {
        if (isset($node['data']['question_id'])) {
            $questionIds[] = $node['data']['question_id'];
        }
    }

    $question_ids = get_post_meta($new_post_id, 'action_ids', true);
    if($question_ids){
      $question_ids = explode(',',$question_ids); 
      $correct_sequence = $questionIds;

      $questionsOrder = array();
      foreach ($correct_sequence as $id) {
          foreach ($question_ids as $question_id) {
              if ($question_id == $id) {
                  $questionsOrder[] = $id;
                  break;
              }
          }
      }

      $new_question_ids = array();
      foreach($questionsOrder as $question_id){
        $oldQuestionId[] = $question_id;
        $question_post = get_post($question_id);
        $new_question = array(
          'post_title' => $question_post->post_title,
          'post_content' => $question_post->post_content,
          'post_status' => $question_post->post_status,
          'post_type' => $question_post->post_type,
        );
        $get_question_id = wp_insert_post($new_question);
        if ($get_question_id) {
          $question_post_meta = get_post_custom($question_id);
          foreach ($question_post_meta as $key => $value) {
              $get_value = get_post_meta($question_id, $key, true);
              update_post_meta($get_question_id, $key, $get_value);
          }
        }
        $new_question_id[] = $get_question_id;
      }
      $load_question = $new_question_id[0];
      update_post_meta($new_post_id, 'start_action_id', $new_question_id[0]);
      /*Drawflow Data*/
      $questions_drawflow = get_post_meta($new_post_id, '_questions_drawflow', true);
      $dataArray = json_decode($questions_drawflow, true);

      if (!is_array($oldQuestionId) && !empty($oldQuestionId)) {
        $oldQuestionId = explode(',', $oldQuestionId);
    }

      $questionIdMapping = array_combine($oldQuestionId, $new_question_id);
      lbbReplaceQuestionIds($dataArray['drawflow']['Home']['data'], $questionIdMapping);

      /*Replacing Class ids*/
      $classQuestionIdMapping = [];
      $classQuestionId = $new_question_id;
      foreach ($questionIdMapping as $oldClass => $newClass) {
          $classQuestionIdMapping[$oldClass] = 'node-question-' . array_shift($classQuestionId);
      }

      foreach ($dataArray['drawflow']['Home']['data'] as &$node) {
          lbbUpdateClassNames($node, $classQuestionIdMapping);
      }

      $updatedSerializedJson = json_encode($dataArray);
      update_post_meta($new_post_id, '_questions_drawflow', $updatedSerializedJson);
      /*Updating next Question Ids*/
      foreach($new_question_id as $new_id){
        $getOldId = get_post_meta($new_id, 'next_question_id', true);
        if (array_key_exists($getOldId, $questionIdMapping)) {
          $value = $questionIdMapping[$getOldId];
          update_post_meta($new_id, 'next_question_id', $value);
        }

        $quick_reply_buttons = get_post_meta($new_id, 'quick_reply_buttons', true);

        if (!empty($quick_reply_buttons) && is_array($quick_reply_buttons)) {

            foreach ($quick_reply_buttons as &$button) {

                // Replace map ID
                if (!empty($button['map']) && isset($questionIdMapping[$button['map']])) {
                    $button['map'] = $questionIdMapping[$button['map']];
                }

                // Replace repeat_ques ID
                if (!empty($button['repeat_ques']) && isset($questionIdMapping[$button['repeat_ques']])) {
                    $button['repeat_ques'] = $questionIdMapping[$button['repeat_ques']];
                }

                // Replace diff_question_id if used
                if (!empty($button['diff_question_id']) && isset($questionIdMapping[$button['diff_question_id']])) {
                    $button['diff_question_id'] = $questionIdMapping[$button['diff_question_id']];
                }

            }

            unset($button); // important safety

            update_post_meta($new_id, 'quick_reply_buttons', $quick_reply_buttons);
        }
      }
      
      $get_question_id = $new_question_id;
      
      $new_question_id = implode(',',array_reverse($new_question_id));
      
      update_post_meta($new_post_id, 'action_ids', $new_question_id);
    }


    $outcomesmanager = new OutcomesManager();
    $args = $outcomesmanager->loadAll();
    if($args){
      foreach($args as $arg){
        $args = array(
          'name' => $arg['name'],
          'content' => $arg['content'],
          'chatflow_id' => $new_post_id,
        );
      }
      
      $outcomes_id = $outcomesmanager->insert($args);
    }
    
    return $new_post_id;
}


function lbbFindOutcomenameByID($array, $id) {
  foreach ($array as $item) {
      if(isset($item['id'])) {
        if ($item['id'] == $id) {
            return $item['name'];
        }
      }else if(isset($item['outcome_title'])){
        if ($item['outcome_title'] == $id) {
          return $item['outcome_title'];
      }
      }
  }
  return '';
}

function lbbFindOutcomeDescByID($array, $id) {
  foreach ($array as $item) {
    if(isset($item['id'])) {
      if ($item['id'] == $id) {
          return $item['name'];
      }
    }else if(isset($item['outcome_title'])){
      if ($item['outcome_title'] == $id) {
        return $item['outcome_description'];
    }
    }
  }
  return '';
}

add_action('wp_ajax_lbb_duplicate_post', 'lbb_duplicate_post');
function lbb_duplicate_post(){
  $post_id = $_REQUEST['post_id'];
  $output = duplicate_post($post_id);
  echo json_encode($output);die;
}

function lbb_importJson($jsonData, $noDecode){



  if($noDecode == 'noDecode'){
    $data = $jsonData;
  }else{
    
    $data = json_decode($jsonData, true);
  }



  $new_post = array(
    'post_title' => $data['post']['post_title'],
    'post_content' => $data['post']['post_content'],
    'post_status' => 'publish',
    'post_type' => $data['post']['post_type'],
  );

  $new_post_id = wp_insert_post($new_post);
  if ($new_post_id) {
    $post_meta = $data['postmeta'];
    foreach ($post_meta as $key => $value) {

      $value = str_replace('%%SITEURL%%',site_url(),$value);

      update_post_meta($new_post_id, $key, $value);
      
    }
  }



    if(!empty($data['questions'])){
      $oldQuestionId = array();
      $post_meta = $data['postmeta'];
      $jsonData = $post_meta['_questions_drawflow'];
      if($jsonData){
        $dataArray = json_decode($jsonData, true);

        // Access the question IDs
        $questionIds = [];

        foreach ($dataArray['drawflow']['Home']['data'] as $node) {
            if (isset($node['data']['question_id'])) {
                $questionIds[] = $node['data']['question_id'];
            }
        }

        $correct_sequence = $questionIds;
          $questions_data = $data['questions'];

          $questionsOrder = array();
          foreach ($correct_sequence as $id) {
              foreach ($questions_data as $item) {
                  if ($item['ID'] == $id) {
                      $questionsOrder[] = $item;
                      break;
                  }
              }
          }
          foreach ($questionsOrder as $question){
            $oldQuestionId[] = $question['ID'];
              $args = array(
                  'post_title'   => $question['post_title'],
                  'post_content' => $question['post_content'],
                  'post_status'  => 'publish',
                  'post_type'  => $question['post_type'],
              );
              // Create a new question post
              $q_id = LBB_Questions::addNewQuestion($args);
              
              if($question['question_postmeta']){
                foreach($question['question_postmeta'] as $key => $value){
                  if($key != "" && ($key == 'quick_reply_buttons' || $key == 'extra_messages' || $key == 'dynamic_messages' || $key == 'outcome_range')){
                    $unserialize_data = unserialize($value);

                    if($key == 'quick_reply_buttons'){
                      foreach ($unserialize_data as $tkey => &$vvalue) {
                        if(!empty($vvalue['url'])){
                          $vvalue['url'] = str_replace('%%SITEURL%%',site_url(),$vvalue['url']);
                        }
                        if(empty($vvalue['answer_action'])){
                          $vvalue['answer_action'] = '';
                        }

                        if(!empty($vvalue['image'])){
                          $vvalue['image'] = str_replace('%%SITEURL%%',site_url(),$vvalue['image']);
                        }
                        
                      }
                    }
                  
                    update_post_meta($q_id, $key, $unserialize_data);
                  }else{
                   
                    $value = str_replace('%%SITEURL%%',site_url(),$value);
                    

                    update_post_meta($q_id, $key, $value);
                  }
                }
              }
              $question_id[] = $q_id;
          }
      }else{
        //$oldQuestionId[] = 1;
        foreach ($data['questions'] as $question){
          $oldQuestionId[] = $question['ID'];
            $args = array(
                'post_title'   => $question['post_title'],
                'post_content' => $question['post_content'],
                'post_status'  => 'publish',
                'post_type'  => $question['post_type'],
            );
            // Create a new question post
            $q_id = LBB_Questions::addNewQuestion($args);
            
            if($question['question_postmeta']){
              foreach($question['question_postmeta'] as $key => $value){
                if($key != "" && ($key == 'quick_reply_buttons' || $key == 'extra_messages' || $key == 'dynamic_messages' || $key == 'outcome_range')){
                  $unserialize_data = unserialize($value);
                  update_post_meta($q_id, $key, $unserialize_data);
                }else{
                  update_post_meta($q_id, $key, $value);
                }
              }
            }
            $question_id[] = $q_id;
        }
      }


      
      
          
      $new_question_id = $question_id;
      
      
      /*Drawflow Data*/
      $questions_drawflow = get_post_meta($new_post_id, '_questions_drawflow', true);
      $dataArray = json_decode($questions_drawflow, true);

      //$oldQuestionId = get_post_meta($new_post_id, 'action_ids', true);
      //$oldQuestionId = explode(',',$oldQuestionId); 
      //$oldQuestionId = array_reverse($oldQuestionId); 

      $questionIdMapping = array_combine($oldQuestionId, $new_question_id);
      lbbReplaceQuestionIds($dataArray['drawflow']['Home']['data'], $questionIdMapping);

      /*Replacing Class ids*/
      $classQuestionIdMapping = [];
      $classQuestionId = $new_question_id;
      foreach ($questionIdMapping as $oldClass => $newClass) {
          $classQuestionIdMapping[$oldClass] = 'node-question-' . array_shift($classQuestionId);
      }

      foreach ($dataArray['drawflow']['Home']['data'] as &$node) {
          lbbUpdateClassNames($node, $classQuestionIdMapping);
      }

      $updatedSerializedJson = json_encode($dataArray);
      update_post_meta($new_post_id, '_questions_drawflow', $updatedSerializedJson);

      /*Updating next Question Ids*/
      foreach($new_question_id as $new_id){
        $getOldId = get_post_meta($new_id, 'next_question_id', true);
        if (array_key_exists($getOldId, $questionIdMapping)) {
          $value = $questionIdMapping[$getOldId];
          
          update_post_meta($new_id, 'next_question_id', $value);
        }

        $quick_reply_buttons = get_post_meta($new_id, 'quick_reply_buttons', true);
        
      
        
        if($quick_reply_buttons){
            foreach ($quick_reply_buttons as &$subArray) {
                if($subArray["map"]){
                    if (isset($subArray['map']) && isset($questionIdMapping[$subArray['map']])) {
                      $subArray['map'] = $questionIdMapping[$subArray['map']];
                    }
                }
                
                if($subArray["repeat_ques"]){
                    if (isset($subArray['repeat_ques']) && isset($questionIdMapping[$subArray['repeat_ques']])) {
                        $subArray['repeat_ques'] = $questionIdMapping[$subArray['repeat_ques']];
                    }
                }

                if(isset($subArray['outcome'])){
               
                  if(!is_numeric($subArray['outcome'])){
                   
                    if(!empty($data['outcome_data'])){
                      $outcomeName = lbbFindOutcomenameByID($data['outcome_data'],$subArray['outcome']);
                      
                      if(!empty($outcomeName)){
                        $outcomeDesc = lbbFindOutcomeDescByID($data['outcome_data'],$subArray['outcome']);
                        $subArray['outcome'] = (string) lbb_create_get_outcome($outcomeName,$new_post_id,$outcomeDesc);

                      }else{
                        $subArray['outcome'] = '';
                      }
                    }else{
                      $subArray['outcome'] = (string) lbb_create_get_outcome($subArray['outcome'],$new_post_id);
                    }

                  }else if(!empty($subArray['outcome'])){
                      $outcomeName = lbbFindOutcomenameByID($data['outcome_data'],$subArray['outcome']);
                      
                      if(!empty($outcomeName)){
                        $outcomeDesc = lbbFindOutcomeDescByID($data['outcome_data'],$subArray['outcome']);
                        $subArray['outcome'] = (string) lbb_create_get_outcome($outcomeName,$new_post_id,$outcomeDesc);
                      }else{
                        $subArray['outcome'] = '';
                      }
                  }else{
                    $subArray['outcome'] = '';
                  }
                }
            }
            update_post_meta($new_id, 'quick_reply_buttons', $quick_reply_buttons);
        }
      }

      if($noDecode == 'noDecode'){
        $get_question_id = $new_question_id;
      }else{
        $get_question_id = array_reverse($new_question_id);
      }
      $new_question_id = implode(',',array_reverse($new_question_id));
      update_post_meta($new_post_id, 'action_ids', $new_question_id);

      
      update_post_meta($new_post_id, 'start_action_id', min($get_question_id));
      //$output['success'] = $new_question_id;
       $output['edit_link'] = site_url().'/wp-admin/admin.php?page=listbuildingbot&action=edit&id='.$new_post_id;
       $output['edit_id'] = $new_post_id;
    }
      

    if ($data !== null) {
        $postID = $data['post']['ID'];
        $postTitle = $data['post']['post_title'];
    } else {
        $output['error'] = "Failed to decode JSON data.";
    }

    return $output;
}

function lbb_create_get_outcome($name,$chatflow_id,$content = ''){

  $outcome_obj = new OutcomesManager();
  $outcome = $outcome_obj->loadByName($name,$chatflow_id);

  if(!empty($outcome)){
    return $outcome['id'];
  }else{
    $outcome_obj = new OutcomesManager();
    if(!empty($name)){
      return $outcome_obj->insert(array(
            'name' => $name,
            'content' => $content,
            'chatflow_id' => $chatflow_id,
        )
      );
    }else{
      return '';
    }
  }
}

add_action('wp_ajax_lbb_import_json', 'lbb_import_json');
function lbb_import_json(){

  if (isset($_FILES['json_file']) && $_FILES['json_file']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['json_file']['tmp_name'];
    $fileType = $_FILES['json_file']['type'];
    if ($fileType === 'application/json') {
      $jsonData = file_get_contents($tmpFilePath);
      $data = json_decode($jsonData, true);
      
      

      $new_post = array(
        'post_title' => $data['post']['post_title'],
        'post_content' => $data['post']['post_content'],
        'post_status' => 'publish',
        'post_type' => $data['post']['post_type'],
      );

      $post_meta = $data['postmeta'];
      $jsonData = $post_meta['_questions_drawflow'];

      $dataArray = json_decode($jsonData, true);

      // Access the question IDs
      $questionIds = [];

      foreach ($dataArray['drawflow']['Home']['data'] as $node) {
          if (isset($node['data']['question_id'])) {
              $questionIds[] = $node['data']['question_id'];
          }
      }

      $correct_sequence = $questionIds;
      $questions_data = $data['questions'];

      $questionsOrder = array();
      foreach ($correct_sequence as $id) {
          foreach ($questions_data as $item) {
              if ($item['ID'] == $id) {
                  $questionsOrder[] = $item;
                  break;
              }
          }
      }
      

      $new_post_id = wp_insert_post($new_post);
      if ($new_post_id) {
          $post_meta = $data['postmeta'];
          foreach ($post_meta as $key => $value) {
              update_post_meta($new_post_id, $key, $value);
          }

          $outcome_data = $data['outcome_data'];
          if(!empty($outcome_data)){
            foreach($outcome_data as $outcome){
              $outcomesmanager = new OutcomesManager();
              $args = array(
                  'name' => $outcome['name'],
                  'content' => $outcome['content'],
                  'chatflow_id' => $new_post_id,
              );
              $outcomes_id = $outcomesmanager->insert($args);
            }
          }
          
          
          
      }

      if(!empty($questionsOrder)){
        foreach ($questionsOrder as $question){
            $oldQuestionId[] = $question['ID'];
            $args = array(
                'post_title'   => $question['post_title'],
                'post_content' => $question['post_content'],
                'post_status'  => 'publish',
                'post_type'  => $question['post_type'],
            );
            // Create a new question post
            $q_id = LBB_Questions::addNewQuestion($args);
            
            if($question['question_postmeta']){
              foreach($question['question_postmeta'] as $key => $value){
                if($key != "" && ($key == 'quick_reply_buttons' || $key == 'extra_messages' || $key == 'dynamic_messages' || $key == 'outcome_range')){
                  $unserialize_data = unserialize($value);
                  update_post_meta($q_id, $key, $unserialize_data);
                }else{
                  update_post_meta($q_id, $key, $value);
                }
              }
            }
            $question_id[] = $q_id;
        }
            
        $new_question_id = $question_id;
        update_post_meta($new_post_id, 'start_action_id', $new_question_id[0]);
        
        /*Drawflow Data*/
        $questions_drawflow = get_post_meta($new_post_id, '_questions_drawflow', true);
        $dataArray = json_decode($questions_drawflow, true);

        $questionIdMapping = array_combine($oldQuestionId, $new_question_id);
        lbbReplaceQuestionIds($dataArray['drawflow']['Home']['data'], $questionIdMapping);

        /*Replacing Class ids*/
        $classQuestionIdMapping = [];
        $classQuestionId = $new_question_id;
        foreach ($questionIdMapping as $oldClass => $newClass) {
            $classQuestionIdMapping[$oldClass] = 'node-question-' . array_shift($classQuestionId);
        }

        foreach ($dataArray['drawflow']['Home']['data'] as &$node) {
            lbbUpdateClassNames($node, $classQuestionIdMapping);
        }

        $updatedSerializedJson = json_encode($dataArray);
        update_post_meta($new_post_id, '_questions_drawflow', $updatedSerializedJson);

        /*Updating next Question Ids*/
        foreach($new_question_id as $new_id){
          $getOldId = get_post_meta($new_id, 'next_question_id', true);
          if (array_key_exists($getOldId, $questionIdMapping)) {
            $value = $questionIdMapping[$getOldId];
            update_post_meta($new_id, 'next_question_id', $value);
          }

          $quick_reply_buttons = get_post_meta($new_id, 'quick_reply_buttons', true);
          if($quick_reply_buttons){
              foreach ($quick_reply_buttons as &$subArray) {
                  if($subArray["map"]){
                      if (array_key_exists($subArray["map"], $questionIdMapping)) {
                          $subArray["map"] = $questionIdMapping[$subArray["map"]];
                      }
                  }
                  if($subArray["repeat_ques"]){
                      if (array_key_exists($subArray["repeat_ques"], $questionIdMapping)) {
                          $subArray["repeat_ques"] = $questionIdMapping[$subArray["repeat_ques"]];
                      }
                  }
              }
              update_post_meta($new_id, 'quick_reply_buttons', $quick_reply_buttons);
          }
        }
        $new_question_id = implode(',',array_reverse($new_question_id));
        update_post_meta($new_post_id, 'action_ids', $new_question_id);
        $output['success'] = $new_post_id;
      }
        

      if ($data !== null) {
         $postID = $data['post']['ID'];
          $postTitle = $data['post']['post_title'];
      } else {
          $output['error'] = "Failed to decode JSON data.";
      }
    } else {
        $output['error'] = "The uploaded file is not a JSON file.";
    }
  } else {
    $output['error'] = "No file uploaded or an error occurred during upload.";
  }
  
  //$output = duplicate_post($post_id);
  echo json_encode($output);die;
}

add_action('wp_ajax_lbb_load_user_data', 'lbb_load_user_data');
function lbb_load_user_data(){
  $user_id = $_REQUEST['user_id'];
  
  if($user_id == 0){
      $name = "Guest";
      $email = "guest@gmail.com";
  }else{
    $contacts_data = new LBB_Contacts;
    $name = $contacts_data->getName($user_id);
    $email = $contacts_data->getEmail($user_id);

    /*other conversations*/

    $other_contacts = $contacts_data->loadAllByEmail($email);

    foreach($other_contacts as $other_contact){
      $otherContactHtml .= '<li data-conversationId="'.$other_contact['conversation_id'].'">'.$other_contact['conversation_id'].'</li>';
    }
    /*other conversations*/

    //$message_data = new MessageManager;
    //$tags = $message_data->getTagsByUserId();
  }
  $output = array(
    'name' => $name,
    'email' => $email,
    'otherContactHtml' => $otherContactHtml,
  );
  echo json_encode($output);die;
}

function lbbReplaceQuestionIds(&$dataArray, $replacementMapping) {
    foreach ($dataArray as &$node) {
        if (isset($node['data']['question_id'])) {
            $oldQuestionId = $node['data']['question_id'];
            if (isset($replacementMapping[$oldQuestionId])) {
                $node['data']['question_id'] = $replacementMapping[$oldQuestionId];
            }
        }
    }
}

function lbbUpdateClassNames(&$node, $mapping) {
    if (isset($node['class'])) {
        foreach ($mapping as $oldClass => $newClass) {
            $node['class'] = str_replace($oldClass, $newClass, $node['class']);
        }
    }

    if (isset($node['inputs'])) {
        foreach ($node['inputs'] as &$input) {
            if (isset($input['connections'])) {
                foreach ($input['connections'] as &$connection) {
                    lbbUpdateClassNames($connection['node'], $mapping);
                }
            }
        }
    }
}

function download_and_upload_image_from_url($image_url) {
    // Check if the URL is valid.
    if (filter_var($image_url, FILTER_VALIDATE_URL)) {

        // Get the file name from the URL.
        $filename = basename($image_url);

        // Set the upload directory and create a full path to the file.
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/' . $filename;

        // Use the WordPress function media_sideload_image to download and sideload the image.
        $attachment_id = media_sideload_image($image_url, 0, '', 'id');

        if (!is_wp_error($attachment_id)) {
            // The image was successfully sideloaded.
          $attachment_url = wp_get_attachment_url($attachment_id);
            return $attachment_url;
        } else {
            // Handle errors if the image download or sideloading fails.
            return $attachment_id->get_error_message();
        }
    } else {
        return 'Invalid URL';
    }
}

add_action('wp_ajax_lbb_clone_question', 'lbb_clone_question');
function lbb_clone_question(){
  $post_id = $_REQUEST['question_id'];
  $post = get_post($post_id);
  if (!isset($post) || $post == null) {
    return;
  }
  $new_post = array(
    'post_title' => $post->post_title . ' (Clone)',
    'post_content' => $post->post_content,
    'post_status' => $post->post_status,
    'post_type' => $post->post_type,
  );
  $new_post_id = wp_insert_post($new_post);

  if ($new_post_id) {
    $post_meta = get_post_custom($post_id);
    $get_aid_value = 0;
    foreach ($post_meta as $key => $value) {
      if (strpos($key, '_') !== 0) {

        if($key == 'quick_reply_buttons' && $key != ''){
          $get_aid_value = get_post_meta($post_id, $key, true);
          $get_aid_value_c = count($get_aid_value);
          foreach($get_aid_value as &$data){
            $randomNumber = rand(0, PHP_INT_MAX);
            $data['id'] = 'id-'.$randomNumber;
            $data['map'] = '';
            $data['repeat_ques'] = '';
          }
          update_post_meta($new_post_id, $key, $get_aid_value);
        }else if($key == 'dynamic_messages' && $key != ''){
          $get_dm_value = get_post_meta($post_id, $key, true);
          foreach($get_dm_value as &$data){
            $randomNumber = rand(0, PHP_INT_MAX);
            $data['id'] = 'dm-'.$randomNumber;
          }
          update_post_meta($new_post_id, $key, $get_dm_value);
        }else if($key == 'extra_messages' && $key != ''){
          $get_dm_value = get_post_meta($post_id, $key, true);
          foreach($get_dm_value as &$data){
            $randomNumber = rand(0, PHP_INT_MAX);
            $data['id'] = 'em-'.$randomNumber;
          }
          update_post_meta($new_post_id, $key, $get_dm_value);
        }else{
          $get_value = get_post_meta($post_id, $key, true);

          if ($get_value !== '') {
            update_post_meta($new_post_id, $key, $get_value);
          }
        }

       
      }
    }

    $response = array(
      'message' => '',
      'post'    => LBB_Questions::getQuestion($new_post_id),
      'html'    => getLBBQuestionHTML($new_post_id),
      'output_node' => $get_aid_value_c
    );
    wp_send_json_success($response);
  }else{
    $response = array('message' => 'Error inserting post.');
    wp_send_json_error($response);
  }
}
add_action('wp_ajax_lbb_delete_conversation', 'lbb_delete_conversation');
function lbb_delete_conversation(){
    $conversation_id = $_REQUEST['conversation_id'];

    $message_data = new MessageManager;
    $delete = $message_data->deleteByConversationId($conversation_id);
}

add_action('wp_ajax_lbb_load_selected_pages', 'lbb_load_selected_pages');
function lbb_load_selected_pages(){

  $args = array('post_type' => 'lbb-chatflow', 'posts_per_page' => -1);
  $the_query = new WP_Query($args);
  $selected_pages = array();

  if ($the_query->have_posts()) {
      while ($the_query->have_posts()) {
          $the_query->the_post(); // This line is important to set up the post data.
          $chatflow_id = get_the_ID();
          $selected_url = get_post_meta($chatflow_id, 'selected_url', true);
          if($selected_url){
            $selected_pages[$chatflow_id] = $selected_url;
          }
      }

      wp_reset_postdata();
  }
  $data = "";
  if($selected_pages){
    foreach($selected_pages as $key => $value){
      $pages = explode(',', $value);
      $page_name = "";
      $chatflow_title = get_the_title($key);
      foreach($pages as $page){
        $data .= '<tr>';
        $title = get_the_title($page);
        $page_url = get_permalink($page);
        $data .= '<td><strong>'.$title.'</strong></td>';
        $data .= '<td><a href="'.$page_url.'" target="_blank">'.$page_url.'</a></td>';
        $data .= '<td><a href="'.site_url().'/wp-admin/admin.php?page=listbuildingbot&action=edit&id='.$key.'" target="_blank">'.$chatflow_title.'(ID: '.$key.')</a></td>';
        $data .= '</tr>';
      }
    }
  }

  $output = '<div class="lbb-datatable"><table class="lbb-table lbb-table-style show-table-data lbb-selected-pages"><thead>
              <tr>
                <th class="lbb-tw-40">Page Name</th>
                <th class="lbb-tw-40">Page URL</th>
                <th class="lbb-tw-12">Chatbot Name</th>
              </tr>
            </thead>
            <tbody class="customfield-data">
              '.$data.'
            </tbody></table></div>';

  echo json_encode($output);die;
}


function lbbIsValidURLWithDomain($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;

   /* $parsedUrl = parse_url($url);
    return filter_var($url, FILTER_VALIDATE_URL) !== false && isset($parsedUrl['host']) && $parsedUrl['host'] === $_POST['url'];*/
}


function lbbUrlContainsFragment($url) {
    $parsedUrl = parse_url($url);
    return isset($parsedUrl['fragment']) ;
}

function lbbUrlFromMainDomain($url, $main_domain) {
    $parsedUrl = parse_url($url);
    $parsedUrlMain = parse_url($main_domain);
    return (!isset($parsedUrl['query'])) && isset($parsedUrl['host']) && isset($parsedUrlMain['host']) && $parsedUrl['host'] === $parsedUrlMain['host'];
}

function lbbSplitAndCombineArrays($inputArray) {
    $outputArrays = array();
    $currentArray = array();
    $currentWordCount = 0;

    foreach ($inputArray as $element) {
        $elementWords = str_word_count($element);
        
        if ($currentWordCount + $elementWords >= 200) {
            // Add the element to the current array
            $currentArray[] = $element;
            $currentWordCount += $elementWords;
            // Add the current array to the output arrays
            $outputArrays[] = $currentArray;
            // Reset the current array and word count
            $currentArray = array();
            $currentWordCount = 0;
        } else {
            // Combine the element with the current array
            $currentArray[] = $element;
            $currentWordCount += $elementWords;
        }
    }

    // If there's anything left in the current array, add it to the output
    if (!empty($currentArray)) {
        $outputArrays[] = $currentArray;
    }

    return $outputArrays;
}

add_action('wp_ajax_lbb_check_pages_in_minimized', 'lbb_check_pages_in_minimized');
function lbb_check_pages_in_minimized(){
    $output = "";
    $where_to_show = "";
    $enter_url = "";
    $chatflow_id[] = $_REQUEST['chatflow_id'];
    if (isset($_REQUEST['where_to_show'])) {
        $where_to_show = $_REQUEST['where_to_show'];
    }
    if (isset($_REQUEST['enter_url'])) {
        $enter_url = $_REQUEST['enter_url'];
    }
    

    $key_to_search = 'selected_url'; 
    $args = array(
        'post_type'      => 'lbb-chatflow', 
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => $key_to_search,
                'compare' => 'EXISTS',
            ),
        ),
        'post__not_in'   => $chatflow_id,
    );

    $query = new WP_Query($args);

    $meta_value = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            if(get_post_meta(get_the_ID(), $key_to_search, true)){
                $meta_value[] = get_post_meta(get_the_ID(), $key_to_search, true);
            }
        }
        wp_reset_postdata();
    }

    $flattenedArray1 = array_map('trim', explode(',', implode(',', $meta_value)));

    $valuesExist = "";
    if($enter_url){
        $parsed_url = parse_url($enter_url);
        $path_segments = explode('/', trim($parsed_url['path'], '/'));
        $post_slug = end($path_segments);
        $post_slug = trim($post_slug);

        if (!empty($post_slug)) {
            $post_id[] = url_to_postid($post_slug);
            if ($post_id) {
                $valuesExist = array_intersect($post_id, $flattenedArray1);
            }
        }
    }else if($where_to_show){
        $valuesExist = array_intersect($where_to_show, $flattenedArray1);
    }

    if($valuesExist){
        foreach($valuesExist as $valueExist){
            $page_url = get_permalink($valueExist);
            $output .= "<p>".$page_url."</p>";
        }
    }
    echo json_encode($output);die;
}