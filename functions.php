<?php

function lbb_is_tags($conversation_id, $tags){
    global $wpdb;
    
    if(empty($tags)){
        return 0;
    }

    $findInSetConditions = array();

    // Loop through the tags to check
    foreach ($tags as $tag) {
        $findInSetConditions[] = "FIND_IN_SET('" . $tag . "', tags) > 0";
    }

    $finalCondition = implode(' OR ', $findInSetConditions);

    $query = "
        SELECT count(*) AS count FROM ".$wpdb->prefix."lbb_messages 
        WHERE tags <> '' AND (
            " . $finalCondition . "
        ) AND conversation_id = ".$conversation_id;

    return $wpdb->get_var($query);
}

function lbb_get_option($key,$default = ''){
    global $lbb_options;
    if(empty($lbb_options)){
        $lbb_options = get_option('lbb_general_settings');
    }

    return isset($lbb_options[$key]) ? $lbb_options[$key] : $default;
}

function lbb_get_meta($post_id, $key,$default = ''){
    global $lbb_meta;

    if(empty($lbb_messages)){
        $lbb_meta = get_post_meta($post_id);
    }

    if(isset($lbb_meta[$key][0])){
        return $lbb_meta[$key][0];
    }else{
        return $default;
    }
}

function lbb_get_style($post_id, $key, $globalStatus, $default = ''){

    if($globalStatus == 1){
        return get_option($key,$default);
    }else{
        return lbb_get_meta($post_id, $key,$default = '');
    }
}

function lbb_clear_conversation($conversation_id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';

    // Construct the SQL query to delete posts of the specified post type
    $query = $wpdb->prepare("DELETE FROM $table_name WHERE conversation_id = %s", $conversation_id);

    // Execute the query
    $wpdb->query($query);

}

function lbb_get_message($key,$chatflow_id = 0){
    global $lbb_messages;
    if(empty($lbb_messages)){
        $default = [
            'lbb_input_placeholder_text' => 'Enter your message',
            'lbb_input_placeholder_email' => 'Enter your email address',
            'lbb_input_placeholder_name' => 'Enter your name',
            'lbb_input_placeholder_phone' => 'Enter your phone',
            'lbb_input_placeholder_country' => 'Enter your country',
            'lbb_input_placeholder_url' => 'Enter your URL',
            'lbb_input_placeholder_date' => 'Enter your date',
            'lbb_conversation_end' => 'This conversation has ended. Click below to start again.',
            'lbb_guest_user' => 'Guest User',
            'lbb_required_message' => 'Please pick or enter a message',
            'lbb_invalid_email' => 'Please enter valid email address',
            'lbb_invalid_url' => 'Please enter valid URL address',
            'lbb_invalid_date_format' => 'Please enter valid date and format should be'
        ];
        $lbb_messages = get_option('lbb_message_data',$default);
    }

    return isset($lbb_messages[$key]) ? $lbb_messages[$key] : '';

    /*if($chatflow_id > 0){
        $lbb
    }*/
}

function lbb_is_last_question($chatflow_id,$question_id){

    $is_last = false;
    $drawflow = get_post_meta($chatflow_id, '_questions_drawflow',true);
    $drawflow = json_decode($drawflow,true);
   
    if(!empty($drawflow)){
        foreach ($drawflow['drawflow']['Home']['data'] as $key => $question) {
            $qid = $question['data']['question_id'];
            if($qid == $question_id){
                
                $output = $question['outputs'];
                if(!empty($output)){
                    $is_last = true;
                    
                    foreach ($output as $key => $o) {
                        if(empty($o['connections'])){

                            $is_last = true;
                        }else{
                            $is_last = false;
                            break;
                        }
                    }
                }else{
                    $is_last = true;
                }
            }
        }
    }
    return $is_last;
}

function lbb_check_last_message_from($conversation_id,$type, $idle_time){

    global $wpdb;

    // check if user

    if($type == 'user'){
        $query = "SELECT (message_id) FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversation_id." AND sent_time <= UTC_TIMESTAMP() - INTERVAL $idle_time SECOND AND is_bot_response = 0 AND agent_id = 0 ORDER BY `message_id` DESC LIMIT 1";
    }else if($type == 'admin'){
        $query = "SELECT (message_id) FROM `".$wpdb->prefix."lbb_messages` WHERE conversation_id = ".$conversation_id." AND sent_time <= UTC_TIMESTAMP() - INTERVAL $idle_time SECOND AND  agent_id > 0 ORDER BY `message_id` DESC LIMIT 1";
    }

    $is = $wpdb->get_var($query);
    return $is;

}

function lbb_get_score($conversation_id){
    global $wpdb;
    $query = "
        SELECT sum(points) AS count FROM ".$wpdb->prefix."lbb_messages 
        WHERE points <> '' AND conversation_id = ".$conversation_id;

    $points = $wpdb->get_var($query);
    return $points != '' ? $points : 0;
}

function lbb_get_outcome($id){
   
    $outcomeM = new OutcomesManager();
    return $outcomeM->loadById($id);
}

function lbb_get_default_outcome_id($chatflow_id){

    $outcomeM = new OutcomesManager();
    return $outcomeM->loadFirst($chatflow_id);

}

function lbb_is_custom_fields($conversation_id,$field_id,$value,$operator = 'equals'){

    global $wpdb;
    
    if(empty($value)){
        return 0;
    }

    $value = $value[0];


    $contact_id = lbb_get_contact_id($conversation_id);
    $valueToCompare = lbb_get_contact_meta($contact_id,'lbb_property_'.$field_id);
  
    $isApplied = lbb_compare_values($operator, $valueToCompare, $value);
    
    return $isApplied;
}

function lbb_get_dynamic_messages($post_id,$conversation_id){

    $status = get_post_meta( $post_id, 'dynamic_message_status', true );

    global $wpdb;
    $messages = array();
    if($status == 1){
        
        $dynamic_messages = get_post_meta( $post_id, 'dynamic_messages', true );
        
        if(!empty($dynamic_messages)){
            foreach ($dynamic_messages as $key => $message) {
                $type = $message['type'];
                $text = $message['message'];
                $value = !empty($message['value']) ? explode(',',$message['value']) : array();
                $field_id = !empty($message['field_id']) ? $message['field_id'] : 0;
                $operator = !empty($message['operator']) ? $message['operator'] : 'equals';
                
                if($type == 'tags' && lbb_is_tags($conversation_id,$value) > 0){
                    $messages[] = $text;
                }else if($type == 'custom_field' && lbb_is_custom_fields($conversation_id,$field_id,$value,$operator) > 0){
                    
                    $messages[] = $text;
                }
            }
        }
    }

    return $messages;
}

function lbb_get_bot_action($post_id, $conversation_id = 0){

    $single_post = get_post($post_id);
    $action = array();
    if($single_post){
        $type = get_post_meta( $post_id, 'question_type', true );
        $input_select = get_post_meta( $post_id, 'input_select', true );
        
        $image = get_post_meta( $post_id, 'image', true );
        $image_answer = get_post_meta( $post_id, 'image_answer', true );
        $action['action_id'] = $post_id;
        $action['type'] = $type ? $type : 'single';
        if(!empty($image)){
            $imgblock = '<div class="lbb-chat-image"><img src="'.$image.'" /></div>';
        }else{
            $imgblock = '';
        }
        
        ob_start();
        include(LBB_ABS_URL.'admin/templates/chat/agent-response.php');
        $main_admin_html = ob_get_clean();

        $field_name = '';
        if($type == 'text' || $type == 'name' || $type == 'email' || $type == 'url' || $type == 'country' || $type == 'phone'){
            if($type == 'text' && $input_select == 'number'){
                $field_name = 'number';
            }else if($type == 'text' && $input_select == 'textarea'){
                $field_name = 'textarea';
            }else if($type == 'email'){
                $field_name = 'email';
            }else{
                $field_name = 'text';
            }
        }else if($type == 'date'){
            $field_name = 'date';
        }else if($type == 'attachment'){
            $field_name = 'attachment';
        }else if($type == 'url'){
            $field_name = 'text';
            $input_select = 'url';
        }else if($type == 'audio'){
            $field_name = 'audio';
        }else if($type == 'message'){
            $field_name = 'single';
        }

        $field_html = '';
        if(file_exists(LBB_ABS_URL.'admin/templates/chat/fields/'.$field_name.'.php')){
            ob_start();
            include(LBB_ABS_URL.'admin/templates/chat/fields/'.$field_name.'.php');
            $field_html = ob_get_clean();
        }

        $field_html = str_replace('{{input_type}}',$input_select, $field_html);
        $field_html = str_replace('{{input_type}}','text', $field_html);

        $placeholders = array('text','name','email','phone','date','country','url');

        if($field_name == 'email'){

            $setting = get_option('lbb_gdpr_settings', array());

            if(isset($setting['lbb_term_checkbox']) && $setting['lbb_term_checkbox'] == 'yes'){
                $field_html = str_replace('{{show_terms}}','lbb-show-email-terms', $field_html);
            }else{
                $field_html = str_replace('{{show_terms}}','', $field_html);
            }

            if(isset($setting['lbb_accept_terms']) && $setting['lbb_accept_terms'] == 'yes'){
                $field_html = str_replace('{{require_terms}}','lbb-require-email-terms', $field_html);
            }else{
                $field_html = str_replace('{{require_terms}}','', $field_html);
            }

            if(isset($setting['accept_terms']) && $setting['accept_terms'] != ''){
                $field_html = str_replace('{{terms_label}}',$setting['accept_terms'], $field_html);
            }else{
                $field_html = str_replace('{{terms_label}}','', $field_html);
            }
            
            $lbb_options = get_option('lbb_general_settings');
            $settings_messages = get_option('lbb_message_data', array());
            $tv = !empty($settings_messages['lbb_accept_terms']) ? $settings_messages['lbb_accept_terms'] : 'Please accept the terms to proceed';
            $field_html = str_replace('{{terms_validation}}',$tv, $field_html);

            
        }

        $settings_messages = get_option('lbb_message_data', array());
        $type = get_post_meta($post_id,'question_type',true);
        if(in_array($type,$placeholders)){
            $placeholder_status = get_post_meta($post_id,'custom_placeholder',true);
            $placeholder_text = get_post_meta($post_id,'funnel_placeholder',true);
            if(!empty($placeholder_text)){
                $placeholder = get_post_meta($post_id,'funnel_placeholder',true);
                $field_html = str_replace('{{input_placeholder}}',$placeholder, $field_html);
            }else if(isset($settings_messages['lbb_input_placeholder_'.$type])){
                $field_html = str_replace('{{input_placeholder}}',@$settings_messages['lbb_input_placeholder_'.$type], $field_html);
            }else{
                $field_html = str_replace('{{input_placeholder}}','Enter your message', $field_html);
            }
        }
        
        $extra_messages = get_post_meta( $post_id, 'extra_messages', true );
        $action['extra_messages'] = array();
        $main_content = $single_post->post_content;
        if(!empty($extra_messages)){
            $action['extra_messages'][] = $single_post->post_content;
            foreach ($extra_messages as $key => $message) {
                if($key == (count($extra_messages) -1)){
                    $main_content = $message['message'];
                }else{
                    $action['extra_messages'][] = $message['message'];
                }
            }
        }

        $main_admin_html = str_replace('{{name}}', 'Bot', $main_admin_html);
        $main_admin_html = str_replace('{{message}}',$main_content.$imgblock, $main_admin_html);
        $main_admin_html = str_replace('{{type}}', $type, $main_admin_html);
        $main_admin_html = str_replace('{{action_id}}', $post_id, $main_admin_html);
        
        //$main_admin_html = str_replace('{{prev_id}}', $post_id, $main_admin_html);

        /*
                $action['text'] = '
                <div class="lbb-chat-block">
                    <div class="lbb-chat-content">'.$single_post->post_content.'</div>
                    '.$imgblock.'
                </div>';
        */

        $dynamicMessages = lbb_get_dynamic_messages($post_id,$conversation_id);


        $action['title'] = $main_content;
        $action['field_html'] = $field_html;
        //$action['title'] = '';

        $action['quick_reply'] = maybe_unserialize(get_post_meta( $post_id, 'quick_reply_buttons', true ));
        
        if($type == 'outcome' && get_post_meta( $post_id, 'enable_pdf_download', true ) == 1){
            $pdf_text = get_post_meta( $post_id, 'download_pdf_button', true );
            
            $action['pdf_button'] = array(
                'label' => $pdf_text,
                'link' => site_url().'?lbb-pdf-download=1&chatflow_id={{chatflow_id}}&sid='.$conversation_id.'&outcome_post_id='.$post_id
            );

            ob_start();
            include(LBB_ABS_URL.'admin/templates/chat/agent-pdf-button.php');
            $pdf_button_html = ob_get_clean();

            $main_admin_html = str_replace('{{pdf_buttons}}', $pdf_button_html, $main_admin_html);
            $main_admin_html = str_replace('{{pdf_link}}',$action['pdf_button']['link'], $main_admin_html);
            $main_admin_html = str_replace('{{pdf_label}}',$action['pdf_button']['label'], $main_admin_html);
        }else{
            $main_admin_html = str_replace('{{pdf_buttons}}', '', $main_admin_html);
        }


        if(get_post_meta( $post_id, 'skip_question', true ) == 1){

            ob_start();
			include(LBB_ABS_URL.'admin/templates/chat/agent-skip-button.php');
			$skip_button_html = ob_get_clean();

            $main_admin_html = str_replace('{{skip_buttons}}', $skip_button_html, $main_admin_html);
            $main_admin_html = str_replace('{{current_question}}', $post_id, $main_admin_html);
            $main_admin_html = str_replace('{{next_question}}', get_post_meta( $post_id, 'next_question_id', true ), $main_admin_html);
			$main_admin_html = str_replace('{{skip_button_text}}','Skip', $main_admin_html);

        }else{
            $main_admin_html = str_replace('{{skip_buttons}}', '', $main_admin_html);
        }

        $action['next_question_id'] = get_post_meta( $post_id, 'next_question_id', true );;

        /*$userReply = isset($_REQUEST['userReply']) ? $_REQUEST['userReply'] : '';
        if(!empty($userReply)){
            $branch_logic = lbb_check_bot_action_logic($post_id,$userReply);
        }*/

        $replyR = (($type != 'single' && $type != 'message') || empty($action['quick_reply'])) ? 'lbb-not-button' : '';

        $imageClass = '';
        
        if(!empty($action['quick_reply']) && $image_answer == 1){
            foreach ($action['quick_reply'] as $key => $value) {
                if(!empty($value['image'])){
                    $imageClass = 'lbb-has-image-buttons';
                    break;
                }
            }
        }

        $img_grid = get_post_meta( $post_id, 'answer_img_button_row_column', true );
        $img_objectfit = get_post_meta( $post_id, 'answer_image_object_fit', true );
        $img_height = get_post_meta( $post_id, 'answer_image_height', true );
        $mobile_image_answer = get_post_meta( $post_id, 'mobile_image_answer', true );

        /*$img_grid = '3';
        $img_objectfit = 'cover';
        $img_height = '50px';*/
        $mobile_class = "";
        if($mobile_image_answer == 1){
            $mobile_class = 'lbb-mobile-hide-image';
        }

        $random_box_cls = 'lbb-img-wrapper-box-'.time().'-'.rand();
        $quick_reply_html = '<div class="quick-reply-buttons '.$mobile_class.' lbb-question-answer-column-grid-'.$img_grid.' lbb-quick-replies-buttons '.$replyR.' '.$imageClass.' '.$random_box_cls.'">';

        $quick_reply_html.= '<style>#lbb-app .lbb-quick-replies-buttons.lbb-has-image-buttons.'.$random_box_cls.' .quick-reply-image > img{ object-fit: '.$img_objectfit.'; height: '.$img_height.'px; }</style>';

        if(!empty($action['quick_reply'])){
            foreach ($action['quick_reply'] as $key => $value) {

                if(empty($value['title'])){
                    continue;
                }

                $image_c = (!empty($value['image']) && $image_answer == 1) ? '<div class="quick-reply-image"><img src="'.$value['image'].'" /></div>' : '';

                ob_start();
                include(LBB_ABS_URL.'admin/templates/chat/agent-reply-buttons.php');
                $button_html = ob_get_clean();

                $value['map'] = empty($value['map'])  ? '' : $value['map'];
                $value['tag'] = empty($value['tag'])  ? '' : $value['tag'];
                $value['answer_action'] = empty($value['answer_action'])  ? '' : $value['answer_action'];
                $value['url'] = empty($value['url'])  ? '' : $value['url'];
                $value['repeat_ques'] = empty($value['repeat_ques'])  ? '' : $value['repeat_ques'];

                
                $button_html = str_replace('{{answer_type}}', $value['answer_action'], $button_html);

                if($value['answer_action'] == 'url'){
                    $button_html = str_replace('{{text}}', $image_c.'<div class="quick-reply-spn-text" data-value="'.@$value['title'].'">'.'<a href="'.$value['url'].'" target="_blank" class="lbb-answer-url-pick">'.@$value['title'].'</a></div>', $button_html);
                }else{
                    $button_html = str_replace('{{text}}', $image_c.'<div class="quick-reply-spn-text" data-value="'.@$value['title'].'">'.@$value['title'].'</div>', $button_html);
                }
                
                $diff_chatflow_id = '';
                if($value['answer_action'] == 'different_bot'){
                    $diff_chatflow_id = $value['diff_chatflow_id'];
                    $nextQ = $value['diff_question_id'];

                }else{
                    $nextQ = $value['map'];
                }

                $button_html = str_replace('{{current_question}}', $post_id, $button_html);
                $button_html = str_replace('{{next_question}}', $nextQ, $button_html);
                $button_html = str_replace('{{chatflow_id}}', $diff_chatflow_id, $button_html);
                $button_html = str_replace('{{tags}}', $value['tag'], $button_html);
                $button_html = str_replace('{{ans-action}}', $value['answer_action'], $button_html);
                $button_html = str_replace('{{ans-action-url}}', $value['url'], $button_html);
                $button_html = str_replace('{{rep_ques}}', $value['repeat_ques'], $button_html);

                $quick_reply_html .= $button_html;
                
                /*$quick_reply_html .= '
                <div class="quick-reply-button" data-currentactionid="'.$post_id.'" data-nextactionid="'.$value['map'].'" >
                    '.$image_c.'
                    <div class="quick-reply-text">'.$value['title'].'</div>
                </div>';*/
            }
        }
        $quick_reply_html .= '</div>';
        $main_admin_html = str_replace('{{reply_buttons}}', $quick_reply_html, $main_admin_html);
        if($type != 'text' && $type != 'date' && $type != 'email'){
            $main_admin_html = str_replace('{{inline_field}}', '', $main_admin_html);
        }

        if($type == 'date'){
            $action['date_format'] = str_replace('%','',get_post_meta( $post_id, 'date_format', true ));
        }

        
        
        $action['quick_reply_html'] = $quick_reply_html;
        $action['text'] = $main_admin_html;
        $action['dynamic_messages'] = $dynamicMessages;
    }
    

    return $action;
}

function lbb_get_total_points($chatflow_id){
    $total_points = get_post_meta($chatflow_id, 'lbb_total_points', true);
    return $total_points;
}

function lbb_is_conversation_ended($action_id, $userReply){

    $question_type = get_post_meta($action_id, 'question_type', true);
    $nai = 0;
    if($question_type != 'single'){
        $nai = get_post_meta($action_id, 'next_question_id', true);

        if(!empty($userReply)){
            $branch_logics = maybe_unserialize(get_post_meta( $action_id, 'advance_logic', true ));
            $branch_logic = lbb_apply_branch_logic($branch_logics,$userReply);
            if($branch_logic){
                $next_action_id = $branch_logic;
                $nai = $branch_logic;
            }
        }

    }else{
        $buttons = get_post_meta($action_id, 'quick_reply_buttons', true);

        if(!empty($buttons)){
            foreach ($buttons as $key => $button) {
                if(!empty($button['map'])){
                    $nai = $button['map'];
                    break;
                }
            }
        }
    }

    if($nai){

    }

    return $nai;

}


function lbb_is_dateValid($dateString, $format) {
    $dateTime = DateTime::createFromFormat($format, $dateString);
    return $dateTime && $dateTime->format($format) === $dateString;
}

function lbb_question_validate($type, $message,$extra = array()){

    $validate = array();
    $message = trim($message);
    $msg = '';
    if(trim($message) == '' && $type != 'attachment' && $type != 'audio'){
        $msg = 'Please pick or enter a message';
    }else if($type == 'email' && !filter_var($message, FILTER_VALIDATE_EMAIL)){
        $msg = lbb_get_message('lbb_invalid_email');
    }else if($type == 'url' && !filter_var($message, FILTER_VALIDATE_URL)){
        $msg = lbb_get_message('lbb_invalid_url');
    }else if($type == 'date'){
        if(!lbb_is_dateValid($message,$extra['format'])){
            $msg = lbb_get_message('lbb_invalid_date_format').' '.$extra['format'];
        }
    }

    if(!empty($msg)){
        $validate = array(
            'error' => 1,
            'message' => $msg
        );
    }

    return $validate;
}

function lbb_add_contact($args){

    $contact = new LBB_Contacts();
    return $contact->insertContact($args);
}

function lbb_update_contact($args,$where){

    $contact = new LBB_Contacts();
    return $contact->updateContact(0,$args,$where);
}

function lbb_get_contact_name($contact_id){
    $contact = new LBB_Contacts();
    return $contact->getName($contact_id);
}

function lbb_get_contact_email($contact_id){
    $contact = new LBB_Contacts();
    return $contact->getEmail($contact_id);
}

function lbb_get_contact_id($conversationId){
    $contact = new LBB_Contacts();
    return $contact->getContactId($conversationId);
}

function lbb_update_contact_meta($contact_id, $key, $value){
    $contact = new LBB_Contacts();
    return $contact->updateMeta($contact_id,$key,$value,true);
}

function lbb_get_contact_meta($contact_id, $key){
    $contact = new LBB_Contacts();
    return $contact->getMeta($contact_id,$key);
}

function lbb_merge_tags($string, $placeholders){
    $result = preg_replace_calbback('/%[^%]+%/', function ($match) use ($placeholders) {
        $placeholder = $match[0];
        return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
    }, $string);

    return $result;
};

function lbb_get_contact_status($contact_id){

    global $wpdb;

    $table_name = $wpdb->prefix . 'lbb_contacts';

    return $wpdb->get_var("SELECT count(*) FROM $table_name WHERE status  = '1' AND id = '".$contact_id."'");
}


function lbb_save_conversation($args){

    global $wpdb;

    $table_name = $wpdb->prefix . 'lbb_conversations';

    $setting = get_option('lbb_gdpr_settings', array());

    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($setting['lbb_gdpr_ipaddress']) && $setting['lbb_gdpr_ipaddress'] == 'yes'){
        $ip = '';
    }

    // Define default values in case any argument is missing
    $defaults = array(
        'user_id' => 0,
        'start_time' => date('Y-m-d H:i:s'),
        'end_time' => date('Y-m-d H:i:s'),
        'ip_address' => $ip,
        'is_published' => 1
    );

    $data = wp_parse_args($args, $defaults);
    

    $wpdb->insert($table_name, $data);

    if ($wpdb->last_error) {
        return false;
    }

    return $wpdb->insert_id;

}

function lbb_get_conversation_status($conversation_id){

    global $wpdb;

    $table_name = $wpdb->prefix . 'lbb_conversations';

    return $wpdb->get_var("SELECT status FROM $table_name WHERE conversation_id = ".$conversation_id."");
}

function getLBBMessages($conversation_id){

    $chatObj = new MessageManager();
    $messages = $chatObj->getMessagesByConversationId($conversation_id);
    return $messages; 
}

function lbb_compare_values($operator, $value1, $value2) {

    if(empty($value1)) return false;

    $value1 = trim($value1); 
    
    switch ($operator) {
        case 'equals':
            return $value1 === $value2;
        case 'not_equals':
            return $value1 !== $value2;
        case 'is_any_of':
            $values = array_map('trim', explode(',', $value2)); // Split multiple values into an array
            return in_array($value1, $values);
        case 'is_not_any_of':
            $values = array_map('trim', explode(',', $value2)); // Split multiple values into an array
            return !in_array($value1, $values);
        case 'greater_than':
            return is_numeric($value1) && is_numeric($value2) && $value1 > $value2;
        case 'less_than':
                echo $value1.' <  '.$value2;
                return is_numeric($value1) && is_numeric($value2) && $value1 < $value2;
        default:
            return false;
    }
}

function lbb_get_prompt($key){
    
    include(LBB_ABS_URL.'admin/prompt.php');
    return $prompts[$key];
}

function lbb_apply_branch_logic($branch_logics, $find, $conversation_id = 0) {
    if (!empty($branch_logics)) {

        $contact_id = lbb_get_contact_id($conversation_id);

        foreach ($branch_logics as $bkey => $branch_logic) {
            $isApplied = true; // Assume the logic is applied until proven otherwise

            foreach ($branch_logic['conditions'] as $key => $logic) {
                if ($logic['type'] === 'user_reply' || $logic['type'] === 'user_property') {
                    // Handle user reply and user property conditions
                    $valueToCompare = $find;

                    if($logic['type'] === 'user_property'){
                        $valueToCompare = lbb_get_contact_meta($contact_id,'save_property_'.$logic['user_property']);
                        //$valueToCompare = 'Admin';
                    }

                    $isApplied = lbb_compare_values($logic['operator'], $valueToCompare, $logic['value']);

                    if (!$isApplied) {
                        break;
                    }
                }
            }

            if ($isApplied) {
                $action_id = $branch_logic['action_map'];
                return $action_id;
            }
        }
    }

    return null; // Return null if no matching logic found
}


function lbb_check_bot_action_logic($post_id,$find){
    $branch_logics = maybe_unserialize(get_post_meta( $post_id, 'advance_logic', true ));


    $action_id = 0;
    if(!empty($branch_logics)){
        foreach ($branch_logics as $bkey => $branch_logic) {
            $isApplied = false;
            
            foreach ($branch_logic['conditions'] as $key => $logic) {
                if($logic['operator'] == 'equals'){
                    if($find == $logic['value']){
                        $isApplied = true;
                    }
                }
            }
            if($isApplied){
                
                $action_id = $branch_logic['action_map'];
                break;
            }
        }
    }

    return $action_id;
}

function lbbGetConversationName($chatflow_id, $conversation_id){

    $name = '';
    $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
    $args = array(
        'post_type'      => 'lbb-chatflow-action',
        'post__in'       => explode(',', $action_ids),
        'meta_query'     => array(
            array(
                'key'     => 'question_type',
                'value'   => 'name',
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query($args);

    if ($query->found_posts > 0) {
        $action_id = $query->posts[0]->ID;
        $messageObj = new MessageManager();
        $name = $messageObj->getMessageFromConversationByAction($conversation_id, $action_id);
    } else {
        $action_id = 0;
    }

    return $name;
}

function lbb_trigger_email($chatflow_id, $conversation_id,$extra = array()){

    $automation_platforms = get_post_meta($chatflow_id, '_lbb_email_notification_status', true);

    /*if($automation_platforms != 'yes'){
        return;
    }*/

    $contact_id = lbb_get_contact_id($conversation_id);
    $name = lbb_get_contact_name($contact_id);
    $to = lbb_get_contact_email($contact_id);
    $is_real = lbb_get_contact_status($contact_id);

    // Send User Email
    $subject = get_post_meta($chatflow_id, '_lbb_user_email_subject', true);
    $body = get_post_meta($chatflow_id, '_lbb_user_email_body', true);

    $data = array(
        'contact_id' => $contact_id,
        'name' => $name,
        'email' => $to,
        'botname' => get_the_title($chatflow_id),
        'boturl' => @$_REQUEST['current_url']
    );

    
    if(!empty($extra)){
        $data = array_merge($data, $extra);
    }

    $body = lbb_process_email_content($chatflow_id, $conversation_id, $body, $data);
    if($automation_platforms == 'yes' && $is_real){
        $subject = str_replace('%all_messages%','',$subject);
        $subject = lbb_process_email_content($chatflow_id, $conversation_id, $subject, $data);
        $gen_email = get_option('admin_email');
        $admin_name = get_post_meta($chatflow_id, '_lbb_email_admin_name', true);
        $headers = array('From: '.$admin_name.' <'.$gen_email.'>','Content-Type: text/html; charset=UTF-8');
        lbb_send_mail($to, $subject, $body,$headers);
    }

    // Send Admin Email
    $admin_emails = get_post_meta($chatflow_id, '_lbb_admin_emails', true);
    $admin_name = get_post_meta($chatflow_id, '_lbb_admin_name', true);

    $admin_subject = get_post_meta($chatflow_id, '_lbb_admin_email_subject', true);
    $admin_body = get_post_meta($chatflow_id, '_lbb_admin_email_body', true);

    $data = array(
        'contact_id' => $contact_id,
        'name' => $name,
        'email' => $to,
        'botname' => get_the_title($chatflow_id),
        'boturl' => @$_REQUEST['current_url']
    );

    $from_mail = filter_var($to, FILTER_VALIDATE_EMAIL) ? $to : $admin_emails;

    $headers = array('From: '.$name.' <'.$from_mail.'>','Content-Type: text/html; charset=UTF-8');

    $admin_body = lbb_process_email_content($chatflow_id, $conversation_id, $admin_body, $data);

    $email_copy = get_post_meta($chatflow_id, '_lbb_email_admin_notification_status', true);

    if($email_copy == 'yes'){
        $admin_subject = str_replace('%all_messages%','',$admin_subject);
        $admin_subject = lbb_process_email_content($chatflow_id, $conversation_id, $admin_subject, $data);
        lbb_send_mail($admin_emails, $admin_subject, $admin_body, $headers);
    }
    
}

function lbb_get_contactmeta_mergetag($contact_id){

    global $wpdb;

    $table_name_cf = $wpdb->prefix . 'lbb_customfields';
    $table_name_cm = $wpdb->prefix . 'lbb_contactmeta';


    $query = $wpdb->prepare("
    SELECT name, field_value FROM  $table_name_cf as cf, $table_name_cm as cm WHERE SUBSTRING(field_name, LENGTH('lbb_property_') + 1) = cf.id AND contact_id = %d
    ", $contact_id);

    $results = $wpdb->get_results($query,ARRAY_A);
    
    
    $data_array = array();

    if(!empty($results)){
        foreach ($results as $result) {
            $field_name = '%custom_' . $result['name'] . '%';
            $field_value = $result['field_value'];

            $data_array[$field_name] = $field_value;
        }

        if(!empty($data_array)){
            
        }
    }

    return $data_array;
}

function lbb_process_email_content($chatflow_id, $conversation_id, $content, $data){
    
    
    $tableHTML = '';
    if (strpos($content, '%all_messages%') !== false) {
        $messages = getLBBMessages($conversation_id);
        
        // Initialize the table HTML string
        $tableHTML = '<table border="0">';

        // Iterate through the data and create table rows
        foreach ($messages as $message) {
            $text = $message['message_text'];
            $meta = maybe_unserialize($message['message_meta']);
            
            
            $is_bot = $message['is_bot_response'];

            if($is_bot == 1){
                $text = '<strong>Bot :</strong> '.strip_tags($text);
            }else if($is_bot == 0 && @$is_agent == 0){
                $text =  '<strong>'.$data['name'].': </strong>'.strip_tags($text);

                if(!empty($meta['attachment'])){
                    $text .= '<a href="'.$meta['attachment'].'" target="_blank">View</a>';
                }
            }

            $tableHTML .= '<tr>';
            $tableHTML .= "<td>$text</td>";
            $tableHTML .= '</tr>';
        }

        // Close the table
        $tableHTML .= '</table>';
    }

    $placeholders = [
        '%name%' => $data['name'],
        '%NAME%' => $data['name'],
        '%email%' => filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? $data['email'] : '',
        '%EMAIL%' => filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? $data['email'] : '',
        '%all_messages%' => $tableHTML,
        '%score%' => @$data['score'],
        '%outcome%' => @$data['outcome'],
        '%BOTNAME%' => @$data['botname'],
        '%BOTURL%' => @$data['boturl'],
    ];
    
    $content = preg_replace_callback('/%[^%]+%/i', function ($match) use ($placeholders) {
        $placeholder = $match[0];
        return isset($placeholders[$placeholder]) ? $placeholders[$placeholder] : $placeholder;
    }, $content);
 
    return $content;    
}

function lbb_send_mail($to, $subject, $body, $headers = array()){

    if(empty($headers)){
        $headers = array('Content-Type: text/html; charset=UTF-8');
    }

    wp_mail($to, $subject, $body, $headers);
}

function lbbTriggerAutomation($chatflow_id, $conversation_id, $action_id,$extras = array()) {

    /**
     * Fire the GHL/external integration hook unconditionally — regardless of
     * whether any automation platforms are enabled in this chatflow's settings.
     * This allows external plugins to act on every completed conversation.
     *
     * Data is gathered independently here so the hook always has full context.
     */
    $lbb_hook_messageObj = new MessageManager();
    $lbb_hook_name  = lbbGetConversationName($chatflow_id, $conversation_id);
    $lbb_hook_email = '';

    // Try to get email from the conversation messages
    $lbb_hook_email_action = lbbIsAutomationAllow($chatflow_id, $conversation_id);
    if ( $lbb_hook_email_action ) {
        $lbb_hook_email = $lbb_hook_messageObj->getMessageFromConversationByAction($conversation_id, $lbb_hook_email_action);
    }

    // Fallback: check if the current request contains the email
    if ( empty($lbb_hook_email) ) {
        $question_type = get_post_meta($action_id, 'question_type', true);
        if ( $question_type == 'email' && !empty($_POST['message']) ) {
            $lbb_hook_email = $_POST['message'];
        }
    }

    // Fallback: look up from contact record
    if ( empty($lbb_hook_email) ) {
        $lbb_hook_contact_id = lbb_get_contact_id($conversation_id);
        if ( $lbb_hook_contact_id ) {
            $lbb_hook_email = lbb_get_contact_email($lbb_hook_contact_id);
        }
    }

    // Fallback for name from contact record
    if ( empty($lbb_hook_name) ) {
        $lbb_hook_contact_id = isset($lbb_hook_contact_id) ? $lbb_hook_contact_id : lbb_get_contact_id($conversation_id);
        if ( $lbb_hook_contact_id ) {
            $lbb_hook_name = lbb_get_contact_name($lbb_hook_contact_id);
        }
    }

    $lbb_hook_tags = $lbb_hook_messageObj->getTagsByConversation($conversation_id);

    /**
     * Fires after all contact data (name, email, tags, custom fields) has been
     * gathered — runs unconditionally regardless of automation platform settings.
     *
     * External plugins can hook here to perform their own integrations
     * (e.g. GHL CRM tag assignment, contact creation, etc.).
     *
     * @since 1.0.0
     * @param int    $chatflow_id     The chatflow post ID.
     * @param int    $conversation_id The conversation ID.
     * @param string $name            Contact name.
     * @param string $email           Contact email address.
     * @param string $tags            Comma-separated tag names from the conversation.
     * @param array  $extras          Extra data: score, total_points, outcome, cfields.
     * @param int    $action_id       The current action/question post ID.
     */
    do_action( 'lbb_automation_triggered', $chatflow_id, $conversation_id, $lbb_hook_name, $lbb_hook_email, $lbb_hook_tags, $extras, $action_id );

    $automation_platforms = get_post_meta($chatflow_id, '_lbb_automation_status', true);
    $automation_platforms = !empty($automation_platforms) ? explode(',', $automation_platforms) : array();

    if (!empty($automation_platforms) && $email_action = lbbIsAutomationAllow($chatflow_id, $conversation_id)) {

        $messageObj = new MessageManager();

        $name = lbbGetConversationName($chatflow_id, $conversation_id);
        $email = $messageObj->getMessageFromConversationByAction($conversation_id, $email_action);
        
        if(empty($email)){
            $question_type = get_post_meta($action_id, 'question_type', true);
            $automation_triggered = get_post_meta($chatflow_id, 'automation_triggered', true);
            if($question_type == 'email' && $automation_triggered == 'after_email'){
                $email = $_POST['message'];
            }
        }
        

        if(empty($email)){

            $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
            
            
            $args = array(
                'post_type'      => 'lbb-chatflow-action',
                'post__in'       => explode(',', $action_ids),
                'meta_query'     => array(
                    array(
                        'key'     => 'question_type',
                        'value'   => 'email',
                        'compare' => '='
                    )
                )
            );
            
            $query = new WP_Query($args);
            $e_action_id = array();
            if ($query->found_posts > 0) {
                foreach ($query->posts as $post) {
                    $e_action_id[] = $post->ID;
                }
            }

            if(!empty($e_action_id)){
                $e_action_id = implode(', ', $e_action_id);
                $email = $messageObj->getMessageFromConversationByActions($conversation_id, $e_action_id);
            }
        }

        if(empty($name)){

            $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
            
            $args = array(
                'post_type'      => 'lbb-chatflow-action',
                'post__in'       => explode(',', $action_ids),
                'meta_query'     => array(
                    array(
                        'key'     => 'question_type',
                        'value'   => 'name',
                        'compare' => '='
                    )
                )
            );
            
            $query = new WP_Query($args);
            $e_action_id = array();
            if ($query->found_posts > 0) {
                foreach ($query->posts as $post) {
                    $e_action_id[] = $post->ID;
                }
            }

            if(!empty($e_action_id)){
                $e_action_id = implode(', ', $e_action_id);
                $name = $messageObj->getMessageFromConversationByActions($conversation_id, $e_action_id);
            }

        }

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
                    $name = $user->getFirst_name();
                    $email = $user->getEmail();
                }
            }

        }


        $tags = $messageObj->getTagsByConversation($conversation_id);

        if(!empty($email)){
            
            foreach ($automation_platforms as $platform) {

                if($platform == 'webhook' || $platform == 'gohighlevel'){
                    $platform_api_details = get_post_meta($chatflow_id,'lbb_automation_' . $platform, true);
                }else{
                    $platform_api_details = get_option('lbb_automation_' . $platform, array());
                }
                
                $listDetails = lbbgetListConnected($chatflow_id,$platform);

                if (!empty($listDetails)) { // Added an empty condition check here
                    foreach ($listDetails as $list) {
                        $data = array(
                            'name' => $name,
                            'email' => $email,
                            'api_details' => $platform_api_details,
                            'data' => array(
                                'list_id' => $list,
                                'tags' => explode(',',$tags),
                                'customFields' => $extras
                            )
                        );

                        try {
                            // Add try-catch block here
                            $smatAutomation = new SmartAutomation();
                            $smatAutomation->trigger($platform, $data);
                            //echo 'Triggered successfully';exit;
                        } catch (Exception $e) {
                            // Handle exceptions specific to this part
                            error_log('Error in lbbTriggerAutomation: ' . $e->getMessage());
                            // You can choose to return an error message or perform other error handling tasks
                        }
                    }
                }else if($platform == 'webhook' || $platform == 'gohighlevel' || $platform == 'drip'){
                    $data = array(
                        'name' => $name,
                        'email' => $email,
                        'api_details' => $platform_api_details,
                        'data' => array(
                            //'list_id' => $list,
                            'tags' => explode(',',$tags),
                            'customFields' => $extras
                            //'extras' => $extras
                        )
                    );

                    try {

                        if($platform == 'gohighlevel'){
                            $platform = 'webhook';
                        }
                        // Add try-catch block here
                        $smatAutomation = new SmartAutomation();
                        $smatAutomation->trigger($platform, $data);
                        //echo 'Triggered successfully';exit;
                    } catch (Exception $e) {
                        // Handle exceptions specific to this part
                        error_log('Error in lbbTriggerAutomation: ' . $e->getMessage());
                        // You can choose to return an error message or perform other error handling tasks
                    }
                }
            }
        }
    }
}

function lbb_get_user_custom_fields($contact_id){

    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."lbb_contactmeta` WHERE contact_id = ".$contact_id."");
    $fields = array();
    if(!empty($results)){
        foreach ($results as $key => $result) {
            $fields[$result->field_name] = $result->field_value;
        }
    }

    return $fields;
}

function lbb_get_selected_outcomes($chatflow_id,$next_action_id,$conversation_id){

    $range_status = get_post_meta($next_action_id, 'outcome_range_enabled', true);
    $default_outcome = lbb_get_default_outcome_id($chatflow_id);
    
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
            return $outcome;
        }else{
            return $default_outcome;
        }
    }
}

function lbbgetListConnected($chatflow_id,$platform){
    $listDetails = get_post_meta($chatflow_id, '_lbb_automation_'.$platform, true);

    // Check if $listDetails is empty or not an array
    if (empty($listDetails) || !is_array($listDetails)) {
        return array(); // Return an empty array if it's empty or not an array
    }

    $new = array();
    foreach ($listDetails as $key => $list) {
        // Check if $list is an array and has the 'list' key
        if (is_array($list) && array_key_exists('list', $list)) {
            $new[] = $list['list'];
        }
    }
    return $new;
}


function lbbIsAutomationAllow($chatflow_id, $conversation_id){

    $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
    $args = array(
        'post_type'      => 'lbb-chatflow-action',
        'post__in'       => explode(',', $action_ids),
        'meta_query'     => array(
            array(
                'key'     => 'question_type',
                'value'   => 'email',
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query($args);

    if ($query->found_posts > 0) {
        $action_id = $query->posts[0]->ID;
    } else {
        $action_id = 0;
    }

    return $action_id;

}

function lbb_user_avatar($user_id){
    return get_avatar_url($user_id, array('size' => 64));
}


/*
function custom_api_content() {
    $content = "This content is from example.com!";
    return 'var test="f";';
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'lbb-api/v1', '/chatflow.js', array(
        'methods' => 'GET',
        'calbback' => 'custom_api_content',
    ) );
} );

function add_cors_http_header() {
    header("Access-Control-Allow-Origin: https://daptips.com/");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
}
add_action('init', 'add_cors_http_header');


function add_custom_rest_cors_headers() {
    header("Access-Control-Allow-Origin: https://mytest.com");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
}
add_action('rest_pre_serve_request', 'add_custom_rest_cors_headers');*/


function is_connect_to_agent_request($user_input) {

    $user_input = strtolower($user_input);
    $connect_keywords = get_option('lbb_livechat_words', array());
    foreach ($connect_keywords as $keyword) {
        if (!empty($keyword) && stripos($user_input, $keyword) !== false) {
            return true;
        }
    }

    return false;
}


/**
 * Save Message to Database
 */
add_action( 'wp_ajax_lbb_add_message', "lbb_add_message" );
add_action( 'wp_ajax_nopriv_lbb_add_message', "lbb_add_message" );

function lbb_add_message() {
        lbb_access_control_allow_origin();
        
        $user = wp_get_current_user();
        $user_id = $user->ID;
        
        

        $lbb_chat_id = $_REQUEST['lbb_chat_id'];

        $lbb_chatflow_id = $_REQUEST['lbb_chatflow_id'];
        $text_message = $_REQUEST['text_message'];
        $admin_send = $_REQUEST['admin_send'];
        $is_conversation_id = $_REQUEST['is_conversation_id'];
        $is_read = isset($_REQUEST['is_read'])? $_REQUEST['is_read'] : 0;
       
        if ($admin_send) {
            $admin_send = 1;
        }else{
            $admin_send = 0;
             
        }

        if ($is_conversation_id) {
            $conversation_id = $lbb_chatflow_id;
        }else{
            $conversation_id = $_COOKIE['lbbcf_'.$lbb_chatflow_id.'_conversation_id'];
        }
        $args = array(
            'conversation_id' => $conversation_id,
            'user_id' => $user_id,
            'message_text' => $text_message,
            'message_meta' => '',
            'sent_time' => gmdate('Y-m-d H:i:s'),
            'agent_id' => $admin_send,
            'is_read' => $is_read,
        );

        $conversation_link = admin_url( 'admin.php?page=listbuildingbot-conversation&mode=L&conversation_id='.$conversation_id );

        $messageManager = new MessageManager();
        $inserted_id = $messageManager->insertMessage($args);

        $notification = get_post_meta($lbb_chat_id,'_lbb_email_admin_livechat_notification_status',true);
        $admin_emails = get_post_meta($lbb_chat_id,'_lbb_livechat_admin_emails',true);

        if($notification == 'yes' && !empty($admin_emails)){
            global $wpdb;
            $table = $wpdb->prefix.'lbb_messages';

            $sql = "SELECT message_id FROM `$table` WHERE `conversation_id` = ".$conversation_id." AND agent_id = 1 ORDER BY `$table`.`message_id` ASC LIMIT 1";


            $message_id = $wpdb->get_var($sql);
            $messageC = 0;
            if($message_id){

                $sql1 = "SELECT count(*) as count FROM `$table` WHERE `message_id` > ".$message_id." AND `conversation_id` = ".$conversation_id." AND `agent_id` = 0 ORDER BY `message_id` ASC LIMIT 1";

                $messageC = $wpdb->get_var($sql1);
            }
        
            if($messageC == 1){
                
                $contact_id = lbb_get_contact_id($conversation_id);
                $name = lbb_get_contact_name($contact_id);
                $from_mail = lbb_get_contact_email($contact_id);

                $admin_subject = 'New Live Chat conversation from '.$name;
                $admin_body = '<p>A visitor has initiated live chat conversation: </p>
                <p>User Message:<p>
                %text_message%
                <p>You can view conversation and respond here:<p>
                %conversation_link%';

                $admin_body = str_replace('%text_message%', $text_message, $admin_body);
                $admin_body = str_replace('%conversation_link%', '<a target="_BLANK" href="'.$conversation_link.'">View Conversation</a>', $admin_body);

                $headers = array('From: '.$name.' <'.$from_mail.'>','Content-Type: text/html; charset=UTF-8');
                
                lbb_send_mail($admin_emails, $admin_subject, $admin_body, $headers);
            }
        }

        echo $inserted_id;
        wp_die();
    }


function get_lbb_header(){
    include(LBB_ABS_URL.'admin/pages/chatflow-header.php');
}

function get_lbb_footer(){
    include(LBB_ABS_URL.'admin/pages/chatflow-footer.php');
}

function lbb_get_max_outcome($values){
    
    $values = explode(',',$values);
    // Initialize an empty array to store counts
    $valueCounts = array();

    // Loop through the list of values and count their occurrences
    foreach ($values as $value) {
        if (isset($valueCounts[$value])) {
            $valueCounts[$value]++;
        } else {
            $valueCounts[$value] = 1;
        }
    }

    // Find the maximum count and the corresponding value
    $maxCount = 0;
    $maxValue = null;

    foreach ($valueCounts as $value => $count) {
        if ($count > $maxCount) {
            $maxCount = $count;
            $maxValue = $value;
        }
    }

    return $maxValue;

}

add_action('wp_ajax_lbblicense_save_wcp', 'lbblicense_save_wcp');

/**
 * License save handler — telemetry removed.
 * Always accepts the key without phoning home.
 */
function lbblicense_save_wcp(){
    if (!current_user_can( 'manage_options' ) ) {
        echo 0;exit;
    }

    $key = $_REQUEST['key'];
    update_option( 'lbb_licenseKey', $key );
    update_option( 'activated_lbb_licenseKey ', 'Y' );
    setcookie('lbb_data_'.lbb_generate_hash(), true, time()+86400,'/');
    echo 1;exit;
}

/**
 * License check — telemetry/phone-home removed.
 * Always returns true (valid). No external connections.
 */
if (!function_exists('LBBCheckFOpen')) {
    function LBBCheckFOpen($pn, $wcplicense) {
        return true;
    }
}

/**
 * Error email — disabled. No longer emails site data to vendor.
 */
if(!function_exists('sendLicensingErrorEmail')) {
    function sendLicensingErrorEmail($subject, $body) {
        return;
    }
}

if(!function_exists('getHost')) {
  function getHost($Address) {
     $parseUrl = parse_url(trim($Address));
     $sqb_host = '';
     if(isset($parseUrl['host'])){
          $sqb_host =  $parseUrl['host'];
     }else if(isset($parseUrl['path'])){
         $sqb_host_array =  explode('/', $parseUrl['path'], 2);
         if(is_array($sqb_host_array)){
             $sqb_host =  array_shift($sqb_host_array);
         }
         
     }
     return trim($sqb_host);
  } 
}


add_action('admin_init', 'lbb_check_is_admin_page', 10, 2);
/**
 * Admin page check — telemetry removed. Always sets the valid cookie.
 */
function lbb_check_is_admin_page() {
  if(is_admin() && isset($_GET['page']) && $_GET['page'] === 'listbuildingbot') {
    setcookie('lbb_data_'.lbb_generate_hash(), true, time()+86400,'/');
  }
}


function lbb_generate_hash() {
    $currentDate = date('Y-m-d');
    return md5($currentDate);
}


function lbb_chat_btn_common($chatflow_id,$how_to_show, $when_to_show, $is_admin = false, $lbb_chat_background_video = ""){
   
    $minimized_type = (get_post_meta($chatflow_id,'minimized_type',true)) ? get_post_meta($chatflow_id,'minimized_type',true): 'icon';
    $lbb_minimized_type_option = (get_post_meta($chatflow_id,'lbb_minimized_type_option',true)) ? get_post_meta($chatflow_id,'lbb_minimized_type_option',true): '';
    $video_text = get_post_meta($chatflow_id,'video_text',true);
    $video_text = ($video_text != '')? $video_text : 'Talk Now!';  
    $how_to_show = get_post_meta($chatflow_id, 'how_to_show', true);
    ?>

    <div class="lbb-chat-icon-container <?php //echo ($how_to_show == 'inline') ? 'lbb-hide' : ''; ?>  lbb-widget-type-<?php echo $minimized_type; ?>">
	

      <?php 

        if ($minimized_type == 'video' || $minimized_type == 'image' || $is_admin) { ?>

<div class="lbb-chat-first-question-main lbb-conversation" style="display:none">

                    <div class="lbb-video-bg-container">
                        <video autoplay loop muted playsinline class="lbb-bg-video-tag-common-btn">
                            <source src="<?php echo $lbb_chat_background_video ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <div class="lbb-close-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></div>

                <div class="lbb-chat-first-question-inner minimize-type-<?php echo $lbb_minimized_type_option ?>"></div>
            </div>

           <div class="lbb-example-widget lbb-widget-video <?php echo ($when_to_show != 'visitor_visit') ? 'lbb-hide' : '' ?>">
            <div class="lbb-example-widget-inner iconInner">
              <div class="lbb-example-widget-background-media">
               
                <?php if ($minimized_type == 'video' || $is_admin) { ?>
                  <video id="lbb-livechat-video-player" class="lbb-example-widget-background-media-video lbb-example-widget-background-media-video-playing" autoplay loop muted playsinline>
                     <?php $maximized_chatbot_video = get_post_meta($chatflow_id,'maximized_chatbot_video',true); ?>
                    <source src="<?php echo $maximized_chatbot_video; ?>" type="video/mp4">
                  </video>
                <?php }

                if($minimized_type == 'image' || $is_admin){ ?>
                   <?php $maximized_chatbot_icon = get_post_meta($chatflow_id,'maximized_chatbot_image',true); ?>
                  <img class="lbb-example-widget-background-media-img" src="<?php echo $maximized_chatbot_icon; ?>">
                <?php } ?>
              </div>
              <div class="lbb-example-widget-inner-transition">
                <div class="lbb-example-widget-collapsed-content-text"><?php echo $video_text; ?></div>
              </div>
            </div>

             <div class="lbb-chat-icon-close lbb-chat-close-event">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
            </div>

            <div class="lbb-chat-icon-close lbb-chat-video-image-close-event">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
            </div>
            
          </div>
        <?php } 

        if($minimized_type == 'icon' || $is_admin){
            $maximized_chatbot_icon = get_post_meta($chatflow_id,'maximized_chatbot_icon',true); 

            $lbb_empty_icon_class = 'lbb-img-show-img';
            if($maximized_chatbot_icon == ''){
                $lbb_empty_icon_class = 'lbb-img-show-svg';
            }
            ?>

            
            <div class="lbb-chat-first-question-main lbb-conversation lbb-first-<?php echo $lbb_minimized_type_option; ?>" style="display:none">

            <div class="lbb-video-bg-container">
                        <video autoplay loop muted playsinline id="lbb-bg-video-tag">
                            <source src="<?php echo $lbb_chat_background_video ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                <div class="lbb-close-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg></div>

                <div class="lbb-chat-first-question-inner minimize-type-<?php echo $lbb_minimized_type_option ?>"></div>
            </div>
            
          <div class="lbb-chat-icon-inner iconInner <?php echo ($when_to_show != 'visitor_visit' && !$is_admin) ? 'lbb-hide' : '' ?> <?php echo $lbb_empty_icon_class; ?>" title="<?php echo strip_tags($video_text); ?>">
            <span class="lbb-notification-bell-icon"><i class="lbb-bell-icon-svg">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="m5.705 3.71-1.41-1.42C1 5.563 1 7.935 1 11h1l1-.063C3 8.009 3 6.396 5.705 3.71zm13.999-1.42-1.408 1.42C21 6.396 21 8.009 21 11l2-.063c0-3.002 0-5.374-3.296-8.647zM12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.184 4.073 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path></svg>
            </i></span>
             <?php
             
             
             if ($maximized_chatbot_icon == '' || $is_admin) { ?>
               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="30" viewBox="0 0 39 37" class="conversations-visitor-open-icon"><defs><path id="conversations-visitor-open-icon-path-1:r0:" d="M31.4824242 24.6256121L31.4824242 0.501369697 0.476266667 0.501369697 0.476266667 24.6256121z"></path></defs><g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1"><g transform="translate(-1432 -977) translate(1415.723 959.455)"><g transform="translate(17 17)"><g transform="translate(6.333 .075)"><path fill="#33475b" d="M30.594 4.773c-.314-1.943-1.486-3.113-3.374-3.38C27.174 1.382 22.576.5 15.36.5c-7.214 0-11.812.882-11.843.889-1.477.21-2.507.967-3.042 2.192a83.103 83.103 0 019.312-.503c6.994 0 11.647.804 12.33.93 3.079.462 5.22 2.598 5.738 5.728.224 1.02.932 4.606.932 8.887 0 2.292-.206 4.395-.428 6.002 1.22-.516 1.988-1.55 2.23-3.044.008-.037.893-3.814.893-8.415 0-4.6-.885-8.377-.89-8.394"></path></g><g fill="#33475b" transform="translate(0 5.832)"><path d="M31.354 4.473c-.314-1.944-1.487-3.114-3.374-3.382-.046-.01-4.644-.89-11.859-.89-7.214 0-11.813.88-11.843.888-1.903.27-3.075 1.44-3.384 3.363C.884 4.489 0 8.266 0 12.867c0 4.6.884 8.377.889 8.393.314 1.944 1.486 3.114 3.374 3.382.037.007 3.02.578 7.933.801l2.928 5.072a1.151 1.151 0 001.994 0l2.929-5.071c4.913-.224 7.893-.794 7.918-.8 1.902-.27 3.075-1.44 3.384-3.363.01-.037.893-3.814.893-8.414 0-4.601-.884-8.378-.888-8.394"></path></g></g></g></g></svg>
             <?php }
             if($maximized_chatbot_icon != '' || $is_admin){ ?>
              <img src="<?php echo $maximized_chatbot_icon; ?>">
             <?php }
             ?>
          </div>
          
        <?php }?>
    </div>
<?php }

function lbb_chat_main_common($chat_mode, $how_to_show, $lbb_image_upload, $lbb_admin_name, $lbb_chatbot_description, $atts = array(), $conversation_messages = '', $lbb_chat_background_video="", $lbb_chat_header='yes'){
    
    $lbb_custom_trained_bot = (get_post_meta( @$atts['id'], 'lbb_custom_trained_bot', true ))? get_post_meta( @$atts['id'], 'lbb_custom_trained_bot', true ) : '';

    $lbb_select_trained_bot = (get_post_meta( @$atts['id'], 'lbb_select_trained_bot', true ))? get_post_meta( @$atts['id'], 'lbb_select_trained_bot', true ) : '';

    
    $allow_train_bot = '';
    if($lbb_custom_trained_bot == 'yes' && !empty($lbb_select_trained_bot)){
        $allow_train_bot = 'lbb-allow-bot-to-trained';
    }

    ?>
<div class="lbb-max-width-auto">
    <div class="lbb-preview">
        <div id="lbb-app" class="<?php echo $allow_train_bot ?> <?php if(@$atts['lbb_made_with'] == 'yes'){ echo 'lbb-made-with-on'; } ?> lbb-<?php echo $chat_mode; ?>-mode lbb-dynamic-mode-set <?php echo @$atts['lbb_enable_search'] == 'yes'  ? 'lbb-document-setting-on' : '';  ?> <?php if($lbb_chat_header == "no"){ echo 'lbb-no-header'; } ?>" data-align="right" <?php echo ($how_to_show != 'inline') ? 'style="display:none;"' : ''; ?>>
            <div class="lbb-app-wrapper lbb-app-responsive">
                <?php if($lbb_chat_header == "no"){
                    echo '<div class="lbb-no-header-close lbb-close">
                            <div class="lbb-close-inner">
                                <svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                  <line x1="30" y1="30" x2="70" y2="70" stroke="black" stroke-width="6"></line>
                                  <line x1="70" y1="30" x2="30" y2="70" stroke="black" stroke-width="6"></line>
                                </svg>
                            </div>
                        </div>';
                } ?>
                <div class="lbb-opened" >

                    <div class="lbb-video-bg-container">
                        <video autoplay loop muted playsinline id="lbb-bg-video-tag-common">
                            <source src="<?php echo $lbb_chat_background_video; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <!---->
                    <div class="lbb-chat">
                        
                        
                        <div class="lbb-chat-header-part">

                            <div class="lbb-action-btn">
                                <a data-mode="chat" class="lbb-chat-switch-btn lbb-chat-switch-active" tabindex="0" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 14h.5c.827 0 1.5-.673 1.5-1.5v-9c0-.827-.673-1.5-1.5-1.5h-13C2.673 2 2 2.673 2 3.5V18l5.333-4H16zm-9.333-2L4 14V4h12v8H6.667z"></path><path d="M20.5 8H20v6.001c0 1.1-.893 1.993-1.99 1.999H8v.5c0 .827.673 1.5 1.5 1.5h7.167L22 22V9.5c0-.827-.673-1.5-1.5-1.5z"></path></svg>
                                    <span><?php echo isset($atts['lbb_menu_item_chat']) ? $atts['lbb_menu_item_chat'] : 'Chat' ?></span>
                                </a>
                                <a data-mode="helpdesk" class="lbb-chat-switch-btn" tabindex="0" role="button" data-disabled="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19.023 16.977a35.13 35.13 0 0 1-1.367-1.384c-.372-.378-.596-.653-.596-.653l-2.8-1.337A6.962 6.962 0 0 0 16 9c0-3.859-3.14-7-7-7S2 5.141 2 9s3.14 7 7 7c1.763 0 3.37-.66 4.603-1.739l1.337 2.8s.275.224.653.596c.387.363.896.854 1.384 1.367l1.358 1.392.604.646 2.121-2.121-.646-.604c-.379-.372-.885-.866-1.391-1.36zM9 14c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5z"></path></svg>
                                    <span><?php echo isset($atts['lbb_menu_item_search']) ? $atts['lbb_menu_item_search'] : 'Helpdesk' ?></span>
                                </a>
                            </div>

                            <div class="lbb-inline-header-elements">
                                <div class="lbb-close">
                                    <div class="lbb-close-inner">
                                        <svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                          <line x1="30" y1="30" x2="70" y2="70" stroke="black" stroke-width="6" />
                                          <line x1="70" y1="30" x2="30" y2="70" stroke="black" stroke-width="6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="lbb-avatar-outer">
                                    <div class="lbb-avatar">
                                        <div class="lbb-avatar-image">
                                            <img src="<?php echo $lbb_image_upload ?>" alt="" class="lbb-lazy-img-loaded">
                                        </div>
                                    </div>
                                </div>
                                <div class="lbb-admin-info-wrapper">
                                    <div class="lbb-header">
                                        <?php echo $lbb_admin_name; ?>
                                    </div>
                                    <div class="lbb-admin-bio">
                                        <?php echo $lbb_chatbot_description; ?>
                                    </div>
                                </div>

                                <div class="lbb-admin-document-wrapper">
                                    <div class="lbb-admin-docuemnt-heading">
                                        Questions? I'm here to help!
                                    </div>
                                </div>
                                
                            </div>
                            <div class="lbb-curved-shape-wrapper"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 372 15" class="color-change-svg">
                                  <path d="M349.8 1.4C334.5.4 318.5 0 302 0h-2.5c-9.1 0-18.4.1-27.8.4-34.5 1-68.3 3-102.3 4.7-14 .5-28 1.2-41.5 1.6C84 7.7 41.6 5.3 0 2.2v8.4c41.6 3 84 5.3 128.2 4.1 13.5-.4 27.5-1.1 41.5-1.6 33.9-1.7 67.8-3.6 102.3-4.7 9.4-.3 18.7-.4 27.8-.4h2.5c16.5 0 32.4.4 47.8 1.4 8.4.3 15.6.7 22 1.2V2.2c-6.5-.5-13.8-.5-22.3-.8z"></path>
                                </svg>
                            </div>
                            <!---->
                        </div>

                        <?php 
                        
                        $message_ddata = get_option('lbb_message_data', array());

                        $are_you_sure = !empty($message_ddata['lbb_sure_reset']) ? $message_ddata['lbb_sure_reset'] : 'Are you sure you want to Reset?';
                        
                        $are_you_sure_yes = !empty($message_ddata['lbb_sure_reset_yes']) ? $message_ddata['lbb_sure_reset_yes'] : 'Yes';
                        $are_you_sure_no = !empty($message_ddata['lbb_sure_reset_no']) ? $message_ddata['lbb_sure_reset_no'] : 'No';

                        ?>

                        <div class="lbb-user-popup">
                            <div class="lbb-confirmation">
                                <p><?php echo $are_you_sure ?></p>
                                <div class="lbb-buttons">
                                    <button id="yesBtn" class="lbb-btn lbb-btn-yes"><?php echo $are_you_sure_yes ?></button>
                                    <button id="noBtn" class="lbb-btn lbb-btn-no"><?php echo $are_you_sure_no ?></button>
                                </div>
                            </div>
                        </div>

                        <?php
                        
                        $contact_settings = get_option('lbb_contactform_settings',array());

                        $contact_form_title = isset($contact_settings['contact_form_title']) ? $contact_settings['contact_form_title'] : 'Please introduce yourself';

                        $contact_form_description = isset($contact_settings['contact_form_description']) ? $contact_settings['contact_form_description'] : "We'll use your contact info only if all the agents are busy at the moment and cannot immediately reply.";

                        $contact_form_email = isset($contact_settings['contact_form_email']) ? $contact_settings['contact_form_email'] : "Email";

                        $contact_form_personal_data = isset($contact_settings['contact_form_personal_data']) ? $contact_settings['contact_form_personal_data'] : "I have read the privacy policy and accept them.";

                        $contact_form_name = isset($contact_settings['contact_form_name']) ? $contact_settings['contact_form_name'] : "Name";

                        $contactform_phone = isset($contact_settings['contactform_phone']) ? $contact_settings['contactform_phone'] : "no";
                        $contact_form_phone = isset($contact_settings['contact_form_phone']) ? $contact_settings['contact_form_phone'] : "Phone";

                        $contactform_require_consent = isset($contact_settings['contactform_require_consent']) ? $contact_settings['contactform_require_consent'] : "no";

                        $contactform_personaldata = isset($contact_settings['contactform_personaldata']) ? $contact_settings['contactform_personaldata'] : "no";
                        

                        $message_ddata = get_option('lbb_message_data', array());

                        $lbb_please_wait = isset($message_ddata['lbb_please_wait']) ? $message_ddata['lbb_please_wait'] : 'Please Wait...';

                        $name_validate = isset($message_ddata['contact_form_name']) ? $message_ddata['contact_form_name'] : 'Please Enter Name';

                        $email_validate = isset($message_ddata['contact_form_email']) ? $message_ddata['contact_form_email'] : 'Please Enter Email';

                        $phone_validate = isset($message_ddata['contact_form_phone']) ? $message_ddata['contact_form_phone'] : 'Please Enter Phone Number';

                        $terms_validate = isset($message_ddata['contact_form_privacy_check']) ? $message_ddata['contact_form_privacy_check'] : 'Please select the checkbox';

                        $submit_text = isset($message_ddata['lbb_submit_button_text']) ? $message_ddata['lbb_submit_button_text'] : 'Submit';

                        $lbb_write_message_text = isset($message_ddata['lbb_write_message_text']) ? $message_ddata['lbb_write_message_text'] : 'Write your message here';
                        

                        $lbb_back_to_main_menu = isset($message_ddata['lbb_main_menu']) ? $message_ddata['lbb_main_menu'] : 'Main Menu';

                        $reset_chat = isset($message_ddata['reset_chat']) ? $message_ddata['reset_chat'] : 'Reset';

                        $lbb_back_button_text = isset($message_ddata['lbb_back_button_text']) ? $message_ddata['lbb_back_button_text'] : 'Back';

                        
                        ?>

                        <div id="contactform-configuration">
                            <div class="lbb-user-info-popup">
                                <form class="lbb-chat-user-info-nice-form">
                                    <div class="lbb-popup-content">
                                        <h2 class="lbb-chat-user-info-form-title"><?php echo stripslashes($contact_form_title); ?></h2>
                                        <p class="lbb-chat-user-info-form-description"><?php echo stripslashes($contact_form_description); ?></p>
                                    </div>
                                    <div class="lbb-chat-user-info-form-group lbb-chat-user-info-name-outer">
                                        <label for="lbb-chat-user-info-full-name" class="lbb-chat-user-info-label"><?php echo $contact_form_name ?></label>
                                        <input type="text" id="lbb-chat-user-info-full-name" name="lbb-chat-user-info-full-name" class="lbb-chat-user-info-input">
                                        <div class="lbb-contact-form-error lbb-contact-form-error-fullname" style="display:none"><?php echo $name_validate ?></div>
                                    </div>
                                    <div class="lbb-chat-user-info-form-group">
                                        <label for="lbb-chat-user-info-email" class="lbb-chat-user-info-label"><?php echo $contact_form_email ?></label>
                                        <input type="email" id="lbb-chat-user-info-email" name="lbb-chat-user-info-email" class="lbb-chat-user-info-input">
                                        <div class="lbb-contact-form-error lbb-contact-form-error-email" style="display:none"><?php echo $email_validate ?></div>
                                    </div>

                                    <?php  ?>
                                    <div class="lbb-chat-user-info-form-group" style="<?php echo ($contactform_phone != 'yes') ? 'display:none' : ''; ?>">
                                        <label for="lbb-chat-user-info-phone" class="lbb-chat-user-info-label"><?php echo $contact_form_phone ?></label>
                                        <input type="text" id="lbb-chat-user-info-phone" name="lbb-chat-user-info-phone" class="lbb-chat-user-info-input">
                                        <div class="lbb-contact-form-error lbb-contact-form-error-phone" style="display:none"><?php echo $phone_validate ?></div>
                                    </div>

                                    <div class="lbb-consent-processing  lbb-form-group-checkbox" style="<?php echo ($contactform_personaldata != 'yes') ? 'display:none' : ''; ?>">
                                        <input id="chat-privacy" type="checkbox" name="" value="yes" class="custom-checkbox-input">
                                       <label for="chat-privacy" class="custom--checkbox" id="privacy_label"><?php echo $contact_form_personal_data ?></label>
                                       <div class="lbb-contact-form-error lbb-contact-form-error-term" style="display:none"><?php echo $terms_validate ?></div>
                                    </div>

                                    <div class="lbb-chat-user-info-form-btn" >
                                        <input type="hidden" id="lbb-chat-user-privacy" name="lbb-chat-user-privacy" value="<?php echo $contactform_require_consent; ?>">
                                        <input type="hidden" id="lbb-chat-user-req-privacy" name="lbb-chat-user-req-privacy" value="<?php echo $contactform_personaldata; ?>">
                                        <input type="hidden" id="lbb-chat-user-phone-req" name="lbb-chat-user-phone-req" value="<?php echo $contactform_phone; ?>">
                                        <input type="hidden" id="lbb_please_wait" name="lbb_please_wait" value="<?php echo $lbb_please_wait; ?>">
                                        
                                        <button type="submit" class="lbb-chat-user-info-button"><?php echo $submit_text ?></button>
                                    </div>

                                    <div class="lbb-reset-conversation lbb-liveform-reset-conv" title="<?php echo $reset_chat; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"></path></svg>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="lbb-kb-wrapper">
                            <div class="lbb-kb-main">
                                <p class="lbb-helpdesk-search-heading"><?php echo $atts['lbb_show_text']; ?></p>
                                <?php 
                                if($atts['lbb_show_results'] == 'yes'){
                                    echo lbb_search_filter('',$atts['chatflow_id']);
                                }
                                ?>
                            </div>
                            
                        </div>

                        <div class="lbb-conversation Messages_list" id="chat-messages">
                        
                            <?php echo $conversation_messages; ?>

                        </div>

                        <div class="lbb-kb-search-form">
                            <form name="lbb-kb-search" action="#" method="post" class="lbb-kb-search-form"><span class="lbb-kb-search-icon"></span><input type="text" name="lbb_kb_search" placeholder="Search our help center..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" maxlength="100" class="lbb-kb-search-input-text"></form>
                        </div>

                        <?php if($chat_mode == 'trained_ai'){
                            
                            global $wpdb;
                            $faqs_connected = get_post_meta($atts['id'], '_lbb_faqs', true);
                            $faqs = array();
                            if(!empty($faqs_connected)){
                                $faq_table = $wpdb->prefix."lbb_faq_master";
                                $faqs = $wpdb->get_results("SELECT * FROM ".$faq_table." WHERE id IN(".$faqs_connected.")", ARRAY_A);
                            }
                            ?>
                        <div class="lbb-kb-faq">
                            <ul>
                                <?php 
                                foreach($faqs as $faq){ ?>
                                    <li class="trained-ai-faq"><?php echo $faq['question']; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>

                        <?php

                        $lbb_custom_trained_bot = (get_post_meta( $atts['id'], 'lbb_custom_trained_bot', true ))? get_post_meta( $atts['id'], 'lbb_custom_trained_bot', true ) : '';

                        $lbb_select_trained_bot = (get_post_meta( $atts['id'], 'lbb_select_trained_bot', true ))? get_post_meta( $atts['id'], 'lbb_select_trained_bot', true ) : '';


                        
                        $show_status = 0;
                        
                        if($lbb_custom_trained_bot == 'yes' && !empty($lbb_select_trained_bot)){
                            $show_status = 1;
                        }else{
                            if($how_to_show == 'inline'){
                                $show_status = 0;
                            }
                        }

                        ?>

                        <div class="lbb-typing <?php echo (!$show_status) ? 'lbb-user-input-hide' : ''; ?>">
                            <input type="text" name="lbb_input_message" class="lbb_input_message" maxlength="256" placeholder="<?php echo $lbb_write_message_text ?> ">
                            <?php if (isset($atts['id'])) { ?>
                                <input id="lbb_chatflow_id" type="hidden" value="<?php echo $atts['id']; ?>">
                            <?php } ?>

                            <?php if (isset($_REQUEST['lbb-embed'])) { ?>
                                <input id="lbb_embed" type="hidden" value="1">
                            <?php } ?>

                            <?php
                            
                            

                            ?>

                            <div class="lbb-fe-footer-action">
                                <div class="lbb-backbutton-conversation-div">
                                    <?php if(isset($atts['back_button']) && $atts['back_button'] == 'yes'){ ?>
                                    <div class="lbb-back-conversation" title="<?php //echo $atts['reset_chat']; ?>" style="display:none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path></svg> <?php echo $lbb_back_button_text; ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                
                                <div class="lbb-made-with-wrapper">
                                    <?php if(@$atts['lbb_made_with'] == 'yes'){ ?>
                                        <a class="lbb-made-with-link" href="<?php echo urldecode(@$atts['lbb_made_with_link']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo @$atts['lbb_made_with_hover_text'] ?>">
                                            <div class="lbb-made-with-text"><?php echo @$atts['lbb_made_with_text'] ?></div>
                                        </a>
                                    <?php } ?>
                                </div>
                                
                                <div class="lbb-reset-restart-conversation">

                                    <?php if($lbb_custom_trained_bot == 'yes' && !empty($lbb_select_trained_bot)){ ?>
                                        

                                        <input id="lbb_allow_bot_to_trained" type="hidden" value="1">

                                        <div class="lbb-listing-dots-click-wrapper">
                                            <a href="javascript:void(0);" class="lbb-click-icon-for-listing">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                            </a> 
                                            <div class="lbb-listing-dots-click">
                                            <div class="lbb-back-to-main-menu-conversation" title=""><?php echo $lbb_back_to_main_menu ?></div>
                                            </div>
                                        </div>


                                    <?php }else{ ?>
                                        <input id="lbb_allow_bot_to_trained" type="hidden" value="0">
                                    <?php } ?>

                                    <div class="lbb-reset-conversation" title="<?php echo $atts['reset_chat']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M48.5 224H40c-13.3 0-24-10.7-24-24V72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8H48.5z"></path></svg>
                                    </div>
                                    <?php if($chat_mode != 'ai'){ ?>
                                    <div class="lbb-restart-conversation" title="<?php echo $atts['restart_chat']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" style=""><path d="M12 16c1.671 0 3-1.331 3-3s-1.329-3-3-3-3 1.331-3 3 1.329 3 3 3z"></path><path d="M20.817 11.186a8.94 8.94 0 0 0-1.355-3.219 9.053 9.053 0 0 0-2.43-2.43 8.95 8.95 0 0 0-3.219-1.355 9.028 9.028 0 0 0-1.838-.18V2L8 5l3.975 3V6.002c.484-.002.968.044 1.435.14a6.961 6.961 0 0 1 2.502 1.053 7.005 7.005 0 0 1 1.892 1.892A6.967 6.967 0 0 1 19 13a7.032 7.032 0 0 1-.55 2.725 7.11 7.11 0 0 1-.644 1.188 7.2 7.2 0 0 1-.858 1.039 7.028 7.028 0 0 1-3.536 1.907 7.13 7.13 0 0 1-2.822 0 6.961 6.961 0 0 1-2.503-1.054 7.002 7.002 0 0 1-1.89-1.89A6.996 6.996 0 0 1 5 13H3a9.02 9.02 0 0 0 1.539 5.034 9.096 9.096 0 0 0 2.428 2.428A8.95 8.95 0 0 0 12 22a9.09 9.09 0 0 0 1.814-.183 9.014 9.014 0 0 0 3.218-1.355 8.886 8.886 0 0 0 1.331-1.099 9.228 9.228 0 0 0 1.1-1.332A8.952 8.952 0 0 0 21 13a9.09 9.09 0 0 0-.183-1.814z"></path></svg>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="lbb-send-icon">
                                
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none" class="h-4 w-4 m-1 md:m-0" stroke-width="2"><path d="M.5 1.163A1 1 0 0 1 1.97.28l12.868 6.837a1 1 0 0 1 0 1.766L1.969 15.72A1 1 0 0 1 .5 14.836V10.33a1 1 0 0 1 .816-.983L8.5 8 1.316 6.653A1 1 0 0 1 .5 5.67V1.163Z" fill="currentColor"></path></svg>
                            </div>
                        </div>

                        <div class="lbb-made-with-wrapper lbb-made-with-outside-wrapper">
                            <?php if(@$atts['lbb_made_with'] == 'yes'){ ?>
                                <a class="lbb-made-with-link" href="<?php echo urldecode(@$atts['lbb_made_with_link']); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo @$atts['lbb_made_with_hover_text'] ?>">
                                    <div class="lbb-made-with-text"><?php echo @$atts['lbb_made_with_text'] ?></div>
                                </a>
                            <?php } ?>
                        </div>

                    </div>
                    <!---->
                </div>
            </div>

            
        </div>
    </div>
</div>
<?php }

function lbb_access_control_allow_origin(){
    // Enable CORS for all origins
    header("Access-Control-Allow-Origin: *");

    // Allow specific HTTP methods
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Allow specific headers in requests
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    // Allow credentials to be sent with the request (if needed)
    header("Access-Control-Allow-Credentials: true");

}

function lbb_dynamic_script_for_embed() {
   if (isset($_GET['embed']) && $_GET['embed'] == true && isset($_GET['chat_id']) && $_GET['chat_id'] != '') {

    
    $chat_id = $_GET['chat_id'];
    $atts = array('id' => $chat_id);
    $shortcode_class = new Listbuildingbot_Public('listbuildingbot', '1.0.0');
    $chat_output = $shortcode_class->fireShortcode($atts);
    $plugin_url = LBB_URL;
    $jquery_url = get_site_url().'/wp-includes/js/jquery/jquery.min.js';

    $admin_name = (get_post_meta( $chat_id, 'admin_name', true ))? get_post_meta( $chat_id, 'admin_name', true ) : '';

    $con_key = 'lbbcf_'.$chat_id.'_conversation_id';
    $conversation_id = isset($_COOKIE[$con_key]) ? $_COOKIE[$con_key] : '';

    $chat_mode = 'bot';
    if(!empty($conversation_id)){
        $conversation = new ConversationManager();
        $res = $conversation->getConversationById($conversation_id);
        
        if($res['status'] == 'L'){
            $chat_mode = 'live';
        }
    }

    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

    if($chatflow_type == 'ai_assistant'){
        $chat_mode = 'ai';
    }

    if($chatflow_type == 'trained_ai'){
        $chat_mode = 'trained_ai';
    }

    $chatbot_style_global_status = lbb_get_style($chat_id,'chatbot_style_global_status',0);
        $lbb_container_width = lbb_get_style($chat_id,'lbb_container_width',$chatbot_style_global_status);
        $lbb_common_font_family = lbb_get_style($chat_id,'lbb_common_font_family',$chatbot_style_global_status);
        $lbb_heading_background_color = lbb_get_style($chat_id,'lbb_heading_background_color',$chatbot_style_global_status);

        $lbb_bot_image_select = lbb_get_style($chat_id,'lbb_bot_image_select',$chatbot_style_global_status);

        if($lbb_bot_image_select == 'image-one'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-1.png";
        }else if($lbb_bot_image_select == 'image-two'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-2.png";
        }else if($lbb_bot_image_select == 'image-three'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-3.png";
        }else if($lbb_bot_image_select == 'image-four'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-4.png";
        }else{
            $lbb_image_upload = lbb_get_style($chat_id,'lbb_image_upload',$chatbot_style_global_status);
            if($lbb_image_upload == ''){
                $lbb_image_upload = LBB_URL."/admin/images/person-image-1.png";
            }
        }

        $lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

    echo "var chatWidgetStylesheet = document.createElement('link');\n";
        echo "chatWidgetStylesheet.rel = 'stylesheet';\n";
        echo "chatWidgetStylesheet.href = '".$plugin_url."admin/css/chat-fe.css'\n";

        echo "var chatWidgetStylesheet_public = document.createElement('link');\n";
        echo "chatWidgetStylesheet_public.rel = 'stylesheet';\n";
        echo "chatWidgetStylesheet_public.href = '".$plugin_url."public/css/listbuildingbot-public.css'\n";

        echo "document.head.appendChild(chatWidgetStylesheet_public) \n";
        echo "document.head.appendChild(chatWidgetStylesheet) \n";

    echo "document.addEventListener('DOMContentLoaded', function () {\n";

    echo "if (typeof jQuery === 'undefined') {";
        echo "var script = document.createElement('script');";
        echo "script.src = '".$jquery_url."';";
        echo "script.type = 'text/javascript';";
        echo "script.onload = function() {";
            echo "lbb_scipt_load_main();";
        echo "};";

        echo "document.head.appendChild(script); ";
    echo "}else{";
        echo "lbb_scipt_load_main();";
    echo "}";


    echo "function lbb_scipt_load_main(){";
        echo "var chatWidgetContainer = document.createElement('div');\n";
        echo "chatWidgetContainer.id = 'chat-widget-container'; \n";

        
        


        echo "var chatWidgetHTML = `$chat_output` \n"; ;
        

        
        echo "document.body.appendChild(chatWidgetContainer) \n"; 
        $how_to_show = get_post_meta($chat_id, 'how_to_show', true);
        
        if($how_to_show == 'inline'){
            echo "document.getElementById('lbb-inline-app').innerHTML = chatWidgetHTML \n"; ;
        }else{
            echo "chatWidgetContainer.innerHTML = chatWidgetHTML \n"; ;
        }
        
        $settings_messages = get_option('lbb_message_data', array());
        $settings_messages = json_encode($settings_messages);
        
        $lbb_emoji = lbb_get_option('lbb_emoji',"grinning smiley smile grin laughing sweat_smile joy rofl relaxed blush innocent slight_smile upside_down wink relieved crazy_face star_struck heart_eyes kissing_heart kissing kissing_smiling_eyes kissing_closed_eyes yum stuck_out_tongue_winking_eye stuck_out_tongue_closed_eyes stuck_out_tongue money_mouth hugging nerd sunglasses cowboy smirk unamused disappointed pensive worried face_with_raised_eyebrow face_with_monocle confused slight_frown grinning smiley smile grin laughing sweat_smile joy rofl relaxed blush innocent slight_smile upside_down");
        $lbb_emoji_enable = lbb_get_option('lbb_emoji_enable','yes');

        
        $cookie_val = isset($_COOKIE['lbbcf_'.$chatflow_id.'_conversation_id']) ? $_COOKIE['lbbcf_'.$chatflow_id.'_conversation_id'] : '';

        $lbb_general_settings = get_option('lbb_general_settings', array());

        $lbb_image_upload_live = !empty($lbb_general_settings['lbb_image_upload_live']) ? $lbb_general_settings['lbb_image_upload_live'] : '';

        $lbb_joined_chat = !empty($lbb_general_settings['lbb_joined_chat']) ? $lbb_general_settings['lbb_joined_chat'] : 'Admin has Joined chat';
        $admin_name_live = !empty($lbb_general_settings['lbb_livechat_admin_name']) ? $lbb_general_settings['lbb_livechat_admin_name'] : '';

        $mode_l = lbb_get_chat_mode($chatflow_id,$cookie_val);

        if($mode_l == 'live'){
            if(!empty($admin_name_live)){
                $admin_name = $admin_name_live;
            }
        }

        if($mode_l == 'live'){
            
            if(!empty($lbb_image_upload_live)){
                $lbb_image_upload = $lbb_image_upload_live;
            }
        }

        if(empty($lbb_image_upload_live)){
            $lbb_image_upload_live = $lbb_image_upload;
        }
        
        global $wpdb;
        if($cookie_val > 0){
            $cnt = $wpdb->get_var('SELECT count(*) FROM `'.$wpdb->prefix.'lbb_messages` WHERE action_id <> 0 AND conversation_id = ' . $cookie_val .'');
        }else{
            $cnt = 0;
        }

        $lbb_minimized_type_option = get_post_meta($atts['id'], 'lbb_minimized_type_option', true);
        
        $skip_message = isset($settings_messages['lbb_skip_question']) ? $settings_messages['lbb_skip_question'] : 'Skip this Message';
        $back_message = isset($settings_messages['lbb_skip_question']) ? $settings_messages['lbb_skip_question'] : 'Back';

		$lbb_first_disappear_options = get_post_meta( $atts['id'], 'lbb_first_disappear_options', true );
		$lbb_first_popout_options = get_post_meta( $atts['id'], 'lbb_first_popout_options', true );
		$lbb_first_popout_how_many_seconds = get_post_meta( $atts['id'], 'lbb_first_popout_how_many_seconds', true );
		$lbb_first_disappear_how_many_seconds = get_post_meta( $atts['id'], 'lbb_first_disappear_how_many_seconds', true );

        $user_time_mesage = isset($general_settings['livechat_user_message']) ? $general_settings['livechat_user_message'] : "Thank you for contacting us. Resolving your concern is important to us. Due to inactivity, we will have to close out this chat. When it is convenient for you, please initiate another chat session with us. We are here to assist you 24x7.";

        echo "var lbb_static_data = document.createElement('script'); \n";
         // echo "customScript_$key.innerHTML = `$url_encoded` \n";
        echo "lbb_static_data.innerHTML = `
            var adminImg = '".$lbb_image_upload."';
            var lbbAdminName = '".$admin_name."';
            var lbbAdminLiveName = '".$admin_name_live."';
			var lbb_first_disappear_options = '".$lbb_first_disappear_options."';
			var lbb_first_popout_options = '".$lbb_first_popout_options."';
			var lbb_first_popout_how_many_seconds = '".$lbb_first_popout_how_many_seconds."';
			var lbb_first_disappear_how_many_seconds = '".$lbb_first_disappear_how_many_seconds."';
            var userImg = '".$user_image."';
            var lbbUserName = '".$lbbUserName."';
            var chat_mode = '".$chat_mode."';
            var chatflow_type = '".$chatflow_type."';
            var lbb_emoji = '".$lbb_emoji."';
            var lbb_emoji_enable = '".$lbb_emoji_enable ."';
            var lbb_minimized_type_option = '".$lbb_minimized_type_option."';
            var lbb_live_agent_image = '".$lbb_image_upload_live ."';
            var lbb_is_fresh =  ".$cnt.";
            var lbb_start_button = 'Start now';
            var lbb_skip_question = '".$skip_message."';
            var lbb_back_question = '".$back_message."';
            var lbb_livechat_options = '".$lbb_livechat_options."';
            var chat_id = '".$chat_id."';
            var fieldPlaceholder = ".$settings_messages.";
            var admin_has_joined = '".$lbb_joined_chat."';
            var adminTimer = ".get_option('livechat_agent_timer', 60).";
            var userTimer = ".get_option('livechat_user_timer', 300).";
            var userTimeoutMessage = '".addslashes($user_time_mesage)."';
            var adminBusyMessage = '".addslashes(get_option('livechat_agent_message', "Sorry, all agents are currently busy. Please leave a message below and we\'ll get back to you asap"))."';
            var listbuildingbot = {'ajax_url':'".admin_url('admin-ajax.php')."','nonce':'3a2b77a2bd'}` \n";
            
            

        echo "document.head.appendChild(lbb_static_data); \n";

        
        $lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

        if ($lbb_livechat_options == 'ajax_based') {         

            $conversation_url = admin_url('admin.php?page=listbuildingbot-conversation' );
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


           $admin_name_live  = 'Admin';

            $site_config = array(
            "plugin_url"=>LBB_URL,
            'lbb_current_page' => get_permalink(),
            "ajaxurl"=>admin_url('admin-ajax.php'),  
            "conversationurl"=>$conversation_url,  
            "welcome_message"=>$welcome_message, 
            "notify_play_audio"=>$notify_play_audio, 
            "notify_notification"=>$admin_name_live, 
            "lbbAdminLiveName"=>$lbbAdminLiveName, 
            "is_wp_admin"=>$is_wp_admin, 
            'nonce' => wp_create_nonce('listbuildingbot_ajax_nonce'),
            "is_have_any_live_chat"=> false,
            "enable_prompt"=> $prompt);

            $localize_script = array(
                'siteConfig' => $site_config,
            );

            $script_array = array(
                'moment' => includes_url('js/dist/vendor/moment.min.js'),
                'lbb_listbuildingbot_public' => LBB_URL ."public/js/listbuildingbot-public.js?ver=".rand(1,10000),
                'lbb_ajaxbased_chat_custom' => LBB_URL ."public/js/ajaxbased-chat-custom.js??ver=".rand(1,10000),
            );

            $final_array = array(
                'script_array' => $script_array,
                'localize_script' => $localize_script
            );

             $php_array_for_script = $final_array;
            foreach ($php_array_for_script['localize_script'] as $key => $url_array) {
              $url_encoded = json_encode($url_array); 
              echo "var customScript_$key = document.createElement('script'); \n";
             // echo "customScript_$key.innerHTML = `$url_encoded` \n";
              echo "customScript_$key.innerHTML = `var $key = $url_encoded;` \n";
              echo "document.head.appendChild(customScript_$key); \n";

            }

            foreach ($php_array_for_script['script_array'] as $key => $url) {
                echo "var chatWidgetScript_$key = document.createElement('script');\n";
                echo "chatWidgetScript_$key.src = '$url';\n";
                echo "document.head.appendChild(chatWidgetScript_$key);\n";
            }


        }else{
            $php_array_for_script = lbb_firebase_enqueue(true);
            foreach ($php_array_for_script['localize_script'] as $key => $url_array) {
              $url_encoded = json_encode($url_array); 
              echo "var customScript_$key = document.createElement('script'); \n";
             // echo "customScript_$key.innerHTML = `$url_encoded` \n";
              echo "customScript_$key.innerHTML = `var $key = $url_encoded;` \n";
              echo "document.head.appendChild(customScript_$key); \n";

            }

            foreach ($php_array_for_script['script_array'] as $key => $url) {
                echo "var chatWidgetScript_$key = document.createElement('script');\n";
                echo "chatWidgetScript_$key.src = '$url';\n";
                echo "document.head.appendChild(chatWidgetScript_$key);\n";
            }
        }


        


    echo "}";

echo "});";
    
    exit;
   }
}
add_action('init', 'lbb_dynamic_script_for_embed');

function limit_content_by_characters($content, $limit) {
    if (strlen($content) > $limit) {
        $content = substr($content, 0, $limit);
        $content .= '...'; // You can add an ellipsis or any other indicator to show that the content has been truncated
    }
    return $content;
}

function lbb_search_filter($keyword = '',$chatflow_id = 0){

        $results = array();
        
        $search_ = get_post_meta($chatflow_id, 'lbb_livechat_exact_match', true);

        $lbb_how_many = get_post_meta($chatflow_id, 'lbb_how_many', true);

        $search_options = get_option('lbb_fuzzy_search_options');
    
        if($keyword != ''){
            $number_of_results = !empty($search_options['number_of_results']) ? $search_options['number_of_results'] : 8;

            
            
        }else{
            $number_of_results = !empty($lbb_how_many) ? $lbb_how_many : 8;
        }
        

        $post_type = array('post');

       
        
        
        if(!empty($search_options['search_pages'])){
            if(in_array('search_pages', $search_options['search_pages'])){
                $post_type[] = 'page';
            }

            if(in_array('search_posts', $search_options['search_pages'])){
                $post_type[] = 'post';
            }


        }

        if($keyword != ''){
            if(!empty($search_options['custom_posts'])){
                $post_type = array_merge($post_type, $search_options['custom_posts']);
            }
        }

        $orderby = !empty($search_options['order_by']) ? $search_options['order_by'] : 'title';
        $order = !empty($search_options['order']) ? $search_options['order'] : 'ASC';

        $args = array(
            //'s' => $keyword,
            'post_type' => $post_type,
            'posts_per_page' => $number_of_results,
            'orderby' => $orderby,
            'order' => $order,
            'post_status' => 'publish',
        );

        $how_old_days = !empty($search_options['old_content_be']) ? $search_options['old_content_be'] : 0;

        if($how_old_days > 0){
            $args['date_query'] = array(
                array(
                    'after' => '-' . $how_old_days . ' days'
                )
            );
        }

        if(!empty($keyword)){
            
            if($search_ == 'exact_match'){
                add_action( 'posts_where', 'lbb_pre_post_query_exact', 10, 2 );
            }else if($search_ == 'one_word_match'){
                add_action( 'posts_where', 'lbb_pre_post_query', 10, 2 );
            }else if($search_ == 'all_words_match'){
                add_action( 'posts_where', 'lbb_pre_post_query_all', 10, 2 );
            }
        }
        

        $query = new WP_Query($args);
        $html = '';
        if ($query->have_posts()) {
            $html .= '<ul class="lbb-kb-list">';
                
            while ($query->have_posts()) {
                $query->the_post();
                $title = get_the_title();
                if(!empty($title)){
                    $html .= '
                    <li >
                        <a rel="noopener noreferrer" target="_blank" href="'.get_the_permalink().'">
                        <span class="lbb-kb-heading-list">
                            <span class="lbb-kb-icon"></span>
                            <strong title="'.$title.'">'.get_the_title().'</strong>
                        </span>
                        <span class="lbb-kb-content">'.get_the_permalink().'</span>
                        </a>
                    </li>';
                }else{
                    $html .= '
                    <li>
                        <a rel="noopener noreferrer" target="_blank" href="'.get_the_permalink().'">
                        <span class="lbb-kb-heading-list">
                            <span class="lbb-kb-icon"></span>
                            <span class="lbb-kb-content">'.get_the_permalink().'</span>
                        </span>
                        </a>
                    </li>';
                }
                
            }

            $html .= '</ul>';
        }
        wp_reset_postdata();
    return $html;
}

/*function lbb_pre_post_query_exact($where, $wp_query){
    global $wpdb;
    $search_terms = $_REQUEST['keyword'];
    $search_terms = trim($search_terms);
    $search_terms = preg_replace('/\s+/', ' ', $search_terms); // Remove extra spaces

    if (!empty($search_terms)) {
       
        $title_where .= "$wpdb->posts.post_title LIKE '" . $wpdb->esc_like($search_terms) . "'";
        //$content_where .= " OR ($wpdb->posts.post_content LIKE '%" . $wpdb->esc_like($term) . "%')";
        

        $where .= " AND ($title_where)";

    }

    
    return $where;

}*/

function lbb_pre_post_query_exact($where, $wp_query){
    global $wpdb;
    $search_terms = $_REQUEST['keyword'];
    $search_terms = trim($search_terms);
    $search_terms = preg_replace('/\s+/', ' ', $search_terms); // Remove extra spaces

    if (!empty($search_terms)) {
        $words = explode(' ', $search_terms);

        $title_where = '';
        foreach ($words as $word) {
            $title_where .= " AND $wpdb->posts.post_title LIKE '%" . $wpdb->esc_like(trim($word)) . "%'";
        }

        $where .= " AND (" . ltrim($title_where, ' AND') . ")";
    }

    return $where;
}


function lbb_pre_post_query($where, $wp_query){

    global $wpdb;
    $search_terms = $_REQUEST['keyword'];
    $search_terms = trim($search_terms);
    $search_terms = preg_replace('/\s+/', ' ', $search_terms); // Remove extra spaces

    if (!empty($search_terms)) {
        // Split search terms into individual words
        $search_terms_array = explode(' ', $search_terms);

        // Create a custom WHERE clause to prioritize post titles
        $title_where = '';
        $content_where = '';
        foreach ($search_terms_array as $term) {
            $title_where .= " OR ($wpdb->posts.post_title LIKE '%" . $wpdb->esc_like($term) . "%')";
            $content_where .= " OR ($wpdb->posts.post_content LIKE '%" . $wpdb->esc_like($term) . "%')";
        }

        // Remove the leading " OR "
        $title_where = ltrim($title_where, ' OR ');
        $content_where = ltrim($content_where, ' OR ');

        $where .= " AND ($title_where OR $content_where)";

    }
    
    return $where;

}

function lbb_pre_post_query_all($where, $wp_query){

    global $wpdb;
    $search_terms = $_REQUEST['keyword'];
    $search_terms = trim($search_terms);
    $search_terms = preg_replace('/\s+/', ' ', $search_terms);

    if (!empty($search_terms)) {
        // Split search terms into individual words
        $search_terms_array = explode(' ', $search_terms);

        // Create a custom WHERE clause to prioritize post titles
        $title_where = '';
        foreach ($search_terms_array as $term) {
            $title_where .= " AND ($wpdb->posts.post_title LIKE '%" . $wpdb->esc_like($term) . "%')";
        }

        // Remove the leading " OR "
        $title_where = ltrim($title_where, ' AND ');

    }

    if($title_where != ''){
        $where .= ' AND '.$title_where;
    }

    return $where;
}

function lbb_get_chat_mode($chatflow_id,$conversation_id = ''){
    
    $chat_mode = 'bot';
    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);

    if($chatflow_type == 'livechat'){
        $chat_mode = 'live';
    }else if(!empty($conversation_id)){
        $conversation = new ConversationManager();
        $res = $conversation->getConversationById($conversation_id);
        
        if(@$res['status'] == 'L'){
            $chat_mode = 'live';
        }
    }

    return $chat_mode;
}

function llb_get_country_code_byid($ipaddress) {

    $return_data = '';
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ipaddress));
    if ($ip_data && $ip_data->geoplugin_countryName != null) {
        $return_data= $ip_data->geoplugin_countryCode;
    }else{
        $ip_data = @json_decode(file_get_contents("https://ipfind.co?ip=" . $ipaddress));
        if ($ip_data && $ip_data->country != null) {
            $return_data = $ip_data->country_code;
        }
    }

    return $return_data;

}

function lbb_format_sentence($content){

    return $content;

    // Use regular expression to split the content into sentences
    $sentences = preg_split('/(?<!\w\.\w.)(?<![A-Z][a-z]\.)(?<=\.|\?)\s/', $content);

    // Insert two newlines after every two sentences for shorter sentences
    // Insert two newlines after every sentence for longer sentences
    $outputText = "";
    foreach ($sentences as $index => $sentence) {
        $outputText .= $sentence;

        // Add two newlines after every two sentences for shorter sentences
        // Add two newlines after every sentence for longer sentences
        if ((strlen($sentence) > 40 && ($index + 1) !== count($sentences)) || (($index + 1) % 2 === 0 && $index + 1 !== count($sentences))) {
            $outputText .= "<br /><br />";
        }
    }

    return $outputText;
}

function lbb_get_chatflow_id_from_action($action_id){

    global $wpdb;

    $meta_key = 'action_ids';
    $meta_value_to_find = $action_id;

    $post_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT post_id 
            FROM {$wpdb->prefix}postmeta 
            WHERE meta_key = %s 
            AND FIND_IN_SET(%s, CONCAT(',', meta_value, ','))",
            $meta_key,
            $meta_value_to_find
        )
    );
    
 
    return $post_id;

}

function LBBreplaceLinks($paragraph) {
    // Regular expression to match URLs
    $pattern = '/\b(?:https?:\/\/|www\.)\S+\b/';

    // Callback function to replace matched URLs with <a> tags
    $callback = function($matches) {
        $url = $matches[0];
        // Check if the URL starts with "http://" or "https://"
        if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
            $url = 'http://' . $url;
        }
        // Replace the URL with an <a> tag
        return '<a href="' . $url . '" target="_blank" class="lbb-trained-openai-link">' . $url . '</a>';
    };

    // Perform the replacement
    $result = preg_replace_callback($pattern, $callback, $paragraph);

    return $result;
}

function lbbTPStyleRender($arr,$gdpr_status){
	
	if($gdpr_status == 'yes' && defined('WCGD_ASSESTS')){
		return $arr['local'];
	}else{
		return $arr['cdn'];
	}

}