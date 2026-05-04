<!-- Chatbot -->
<?php 
$how_to_show = get_post_meta($atts['id'], 'how_to_show', true);

if($how_to_show == 'inline'){

}

    include( LBB_ABS_URL . 'admin/pages/default-variables.php');

   $selected_url = "";
   $enter_url = "";
   $page_scroll_value = "";
   $when_to_show = "visitor_visit";
   $chat_alignment = "left";
   $time_input_value = "";
   $who_should_see = "all_visitor";
   $automation_triggered = "after_email";
   $how_to_show = "inline";
   $admin_name = "";
   $start_again = "";
   $chatbot_description = "This is description about ADMIN";
   $heading_font_family = "DM Sans,sans-serif";
   $content_font_family = "DM Sans,sans-serif";
   $sub_heading_font_family = "DM Sans,sans-serif";
   
   $global_style = "N";
   
   $chat_background_color = "#fff";
   $button_text_color = "#0066ff";
   $button_font_size = "16";
   $answer_button_font_size = "16";
   $button_border_radius = "5";
   //$icon_color = "0066ff";

   /*Question Styles Default Start*/
   /*Question Styles Default End*/

   $ans_bg_color = "#000000";
   $icon_padding = "15";
   $icon_box_size = "60";
   $icon_background_color = "#2000f0";
   $icon_size = "30";
   $icon_border_radius = "5";
   $lbb_right_spacing = "20";
   $lbb_left_spacing = "20";
   $lbb_bottom_spacing = "20";
   $submit_button_icon = LBB_URL.'admin/images/avatar.png';
   $bot_user_image = LBB_URL.'admin/images/avatar.png';
   $icon_image = LBB_URL.'admin/images/chat.png';
   $video_text = "How can I help?";
   
   /**/
   $maximized_type = 'icon';
   /**/
   $maximized_chatbot_image = "";
   $maximized_chatbot_video = "";
   $maximized_chatbot_icon = "";

   /*Ai Assistant Values*/

    $welcome_message = "";
    $input_token_limit = "3400";
    $output_token_limit = "600";
    $limit_threads = "10000";
    $allow_embed_domains = "";
    $open_ai_key = "";
   
    $api_model = "gpt-4o-mini";
    $aiassistant_main_prompt = "You are Bot and your primary purpose is to assist users in a variety of tasks and offer valuable guidance. Whether it's helping users plan their fitness routines or suggesting travel destinations, you're equipped to provide insightful assistance.

    When engaging with users, begin by asking them about the specific task they need help with. Depending on their response, you can either follow up with further inquiries to gather more details or provide relevant information to address their request. Keep the conversation engaging by introducing fresh and intriguing insights, avoiding repetitive responses.

    Moreover, always consider the chat's history to offer responses that are contextually appropriate. By asking insightful questions and delivering practical tips and advice when it makes sense, you can enhance the overall user experience.";

    $aiassistant_rules = "Maintain a respectful and courteous tone in all interactions.

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

    limit based on token but make sure to complete the sentence";
    $lang = 'English';
$lbb_chat_header = 'yes';
$chatflow_id = $atts['id'];
if(!empty($chatflow_id)){

        $chatbot_style_global_status = lbb_get_style($chatflow_id,'chatbot_style_global_status',0);
        $lbb_chat_header = lbb_get_style($chatflow_id,'lbb_chat_header',$lbb_chat_header);
        $lbb_container_width = lbb_get_style($chatflow_id,'lbb_container_width',$chatbot_style_global_status);
        $lbb_common_font_family = lbb_get_style($chatflow_id,'lbb_common_font_family',$chatbot_style_global_status);
        $lbb_heading_background_color = lbb_get_style($chatflow_id,'lbb_heading_background_color',$chatbot_style_global_status);

        $lbb_bot_image_select = lbb_get_style($chatflow_id,'lbb_bot_image_select',$chatbot_style_global_status);

        if($lbb_bot_image_select == 'image-one'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-1.png";
        }else if($lbb_bot_image_select == 'image-two'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-2.png";
        }else if($lbb_bot_image_select == 'image-three'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-3.png";
        }else if($lbb_bot_image_select == 'image-four'){
            $lbb_image_upload = LBB_URL."/admin/images/person-image-4.png";
        }else{
            $lbb_image_upload = lbb_get_style($chatflow_id,'lbb_image_upload',$chatbot_style_global_status);
            if($lbb_image_upload == ''){
                $lbb_image_upload = LBB_URL."/admin/images/person-image-1.png";
            }
        }


        $cookie_val = isset($_COOKIE['lbbcf_'.$chatflow_id.'_conversation_id']) ? $_COOKIE['lbbcf_'.$chatflow_id.'_conversation_id'] : '';
        $mode = lbb_get_chat_mode($chatflow_id,$cookie_val);

        $lbb_general_settings = get_option('lbb_general_settings', array());

        /*echo '<pre>';
        print_r($lbb_general_settings);
        exit;*/

        $lbb_image_upload_live = !empty($lbb_general_settings['lbb_image_upload_live']) ? $lbb_general_settings['lbb_image_upload_live'] : '';

        $lbb_joined_chat = !empty($lbb_general_settings['lbb_joined_chat']) ? $lbb_general_settings['lbb_joined_chat'] : 'Admin has Joined chat';

        //$lbb_image_upload_live = lbb_get_style($chatflow_id,'lbb_image_upload_live',$lbb_image_upload);
        if($mode == 'live'){
            
            if(!empty($lbb_image_upload_live)){
                $lbb_image_upload = $lbb_image_upload_live;
            }
        }

        if(empty($lbb_image_upload_live)){
            $lbb_image_upload_live = $lbb_image_upload;
        }

        $lbb_container_padding = lbb_get_style($chatflow_id,'lbb_container_padding',$chatbot_style_global_status);
        $lbb_chatbot_height = lbb_get_style($chatflow_id,'lbb_chatbot_height',$chatbot_style_global_status);
        

        $heading_style_global_status = lbb_get_style($chatflow_id,'heading_style_global_status',0);
        $lbb_heading_font_weight = lbb_get_style($chatflow_id,'lbb_heading_font_weight',$heading_style_global_status);
        $lbb_heading_font_size = lbb_get_style($chatflow_id,'lbb_heading_font_size',$heading_style_global_status);
        $lbb_heading_text_color = lbb_get_style($chatflow_id,'lbb_heading_text_color',$heading_style_global_status);
        $lbb_sub_heading_text_color = lbb_get_style($chatflow_id,'lbb_sub_heading_text_color',$heading_style_global_status);
        $lbb_sub_heading_font_size = lbb_get_style($chatflow_id,'lbb_sub_heading_font_size',$heading_style_global_status);
        $lbb_sub_heading_font_weight = lbb_get_style($chatflow_id,'lbb_sub_heading_font_weight',$heading_style_global_status);

        $question_style_global_status = lbb_get_style($chatflow_id,'question_style_global_status',0);
        $lbb_question_font_size = lbb_get_style($chatflow_id,'lbb_question_font_size',$question_style_global_status);
        $lbb_question_font_weight = lbb_get_style($chatflow_id,'lbb_question_font_weight',$question_style_global_status);
        $lbb_question_text_color = lbb_get_style($chatflow_id,'lbb_question_text_color',$question_style_global_status);
        $lbb_question_background_color = lbb_get_style($chatflow_id,'lbb_question_background_color',$question_style_global_status);
        $lbb_question_input_border_color = lbb_get_style($chatflow_id,'lbb_question_input_border_color',$question_style_global_status);
        $lbb_question_image_height = lbb_get_style($chatflow_id,'lbb_question_image_height',$question_style_global_status);

        $user_answer_style_global_status = lbb_get_style($chatflow_id,'user_answer_style_global_status',0);
        $lbb_user_answer_font_size = lbb_get_style($chatflow_id,'lbb_user_answer_font_size',$user_answer_style_global_status);
        $lbb_user_answer_font_weight = lbb_get_style($chatflow_id,'lbb_user_answer_font_weight',$user_answer_style_global_status);
        $lbb_user_answer_text_color = lbb_get_style($chatflow_id,'lbb_user_answer_text_color',$user_answer_style_global_status);
        $lbb_user_answer_background_color = lbb_get_style($chatflow_id,'lbb_user_answer_background_color',$user_answer_style_global_status);

        $back_style_global_status = lbb_get_style($chatflow_id,'back_style_global_status',0);
        $lbb_back_button_font_size = lbb_get_style($chatflow_id,'lbb_back_button_font_size',$back_style_global_status);
        $lbb_back_button_font_weight = lbb_get_style($chatflow_id,'lbb_back_button_font_weight',$back_style_global_status);
        $lbb_back_button_text_color = lbb_get_style($chatflow_id,'lbb_back_button_text_color',$back_style_global_status);
        $lbb_back_button_background_color = lbb_get_style($chatflow_id,'lbb_back_button_background_color',$back_style_global_status);

        $answer_style_global_status = lbb_get_style($chatflow_id,'answer_style_global_status',0);
        $lbb_ans_bg_color = lbb_get_style($chatflow_id,'lbb_ans_bg_color',$answer_style_global_status);
        $lbb_ans_border_color = lbb_get_style($chatflow_id,'lbb_ans_border_color',$answer_style_global_status);
        $lbb_ans_text_color = lbb_get_style($chatflow_id,'lbb_ans_text_color',$answer_style_global_status);
        $lbb_ans_border_radius = lbb_get_style($chatflow_id,'lbb_ans_border_radius',$answer_style_global_status);
        $lbb_ans_font_size = lbb_get_style($chatflow_id,'lbb_ans_font_size',$answer_style_global_status);
        $lbb_ans_font_weight = lbb_get_style($chatflow_id,'lbb_ans_font_weight',$answer_style_global_status);

        $lbb_display_bot_callout = (get_post_meta( $chatflow_id, 'lbb_display_bot_callout', true ))? get_post_meta( $chatflow_id, 'lbb_display_bot_callout', true ) : $lbb_display_bot_callout;
        $lbb_callout_text = (get_post_meta( $chatflow_id, 'lbb_callout_text', true ))? get_post_meta( $chatflow_id, 'lbb_callout_text', true ) : $lbb_callout_text;
        $lbb_callout_text_color = (get_post_meta( $chatflow_id, 'lbb_callout_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_callout_text_color', true ) : $lbb_callout_text_color;
        $lbb_callout_font_size = (get_post_meta( $chatflow_id, 'lbb_callout_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_callout_font_size', true ) : $lbb_callout_font_size;

        $lbb_knowledge_background_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_background_color', true ) : $lbb_knowledge_background_color;
    $lbb_knowledge_active_background_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_active_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_active_background_color', true ) : $lbb_knowledge_active_background_color;
    $lbb_knowledge_active_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_active_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_active_color', true ) : $lbb_knowledge_active_color;
    $lbb_knowledge_text_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_text_color', true ) : $lbb_knowledge_text_color;
    $lbb_last_chatted_text_color = (get_post_meta( $chatflow_id, 'lbb_last_chatted_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_last_chatted_text_color', true ) : $lbb_last_chatted_text_color;

        $alignment_style_global_status = lbb_get_style($chatflow_id,'alignment_style_global_status',0);
        $chat_alignment = lbb_get_style($chatflow_id,'lbb_chat_alignment',$alignment_style_global_status);
        
        $lbb_right_spacing = lbb_get_style($chatflow_id,'lbb_right_spacing',$alignment_style_global_status);
        $lbb_left_spacing = lbb_get_style($chatflow_id,'lbb_left_spacing',$alignment_style_global_status);
        $lbb_bottom_spacing = lbb_get_style($chatflow_id,'lbb_bottom_spacing',$alignment_style_global_status);

        $expanded_style_global_status = lbb_get_style($chatflow_id,'expanded_style_global_status',0);
        $max_border_color = lbb_get_style($chatflow_id,'max_border_color',$expanded_style_global_status);
        $max_border_width = lbb_get_style($chatflow_id,'max_border_width',$expanded_style_global_status);
        $max_border_radius = lbb_get_style($chatflow_id,'max_border_radius',$expanded_style_global_status);
        $max_width = lbb_get_style($chatflow_id,'max_width',$expanded_style_global_status);
        $max_height = lbb_get_style($chatflow_id,'max_height',$expanded_style_global_status);
        $max_bg_color = lbb_get_style($chatflow_id,'max_bg_color',$expanded_style_global_status);

        $backgroun_style_global_status = lbb_get_style($chatflow_id,'backgroun_style_global_status',0);
        $lbb_style_chatbot_background = lbb_get_style($chatflow_id,'lbb_style_chatbot_background',$expanded_style_global_status);
        $lbb_chat_background_color = lbb_get_style($chatflow_id,'lbb_chat_background_color',$expanded_style_global_status);
        $lbb_chat_background_image = lbb_get_style($chatflow_id,'lbb_chat_background_image',$expanded_style_global_status);
        $lbb_chat_background_video = lbb_get_style($chatflow_id,'lbb_chat_background_video',$expanded_style_global_status);

       //$lbb_common_font_family = (get_post_meta( $chatflow_id, 'lbb_common_font_family', true ))? get_post_meta( $chatflow_id, 'lbb_common_font_family', true ) : $lbb_common_font_family;
       //$lbb_container_padding = (get_post_meta( $chatflow_id, 'lbb_container_padding', true ))? get_post_meta( $chatflow_id, 'lbb_container_padding', true ) : $lbb_container_padding;
       //$lbb_question_input_border_color = (get_post_meta( $chatflow_id, 'lbb_question_input_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_input_border_color', true ) : $lbb_question_input_border_color;
        //$lbb_container_width = (get_post_meta( $chatflow_id, 'lbb_container_width', true ))? get_post_meta( $chatflow_id, 'lbb_container_width', true ) : $lbb_container_width;
        //$lbb_heading_background_color = (get_post_meta( $chatflow_id, 'lbb_heading_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_heading_background_color', true ) : $lbb_heading_background_color;
        //$lbb_image_upload = (get_post_meta( $chatflow_id, 'lbb_image_upload', true ))? get_post_meta( $chatflow_id, 'lbb_image_upload', true ) : $lbb_image_upload;
        //$lbb_heading_font_size = (get_post_meta( $chatflow_id, 'lbb_heading_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_heading_font_size', true ) : $lbb_heading_font_size;
        //$lbb_heading_font_weight = (get_post_meta( $chatflow_id, 'lbb_heading_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_heading_font_weight', true ) : $lbb_heading_font_weight;
        //$lbb_heading_text_color = (get_post_meta( $chatflow_id, 'lbb_heading_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_heading_text_color', true ) : $lbb_heading_text_color;
        //$lbb_sub_heading_font_size = (get_post_meta( $chatflow_id, 'lbb_sub_heading_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_font_size', true ) : $lbb_sub_heading_font_size;
        //$lbb_sub_heading_font_weight = (get_post_meta( $chatflow_id, 'lbb_sub_heading_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_font_weight', true ) : $lbb_sub_heading_font_weight;
        //$lbb_sub_heading_text_color = (get_post_meta( $chatflow_id, 'lbb_sub_heading_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_text_color', true ) : $lbb_sub_heading_text_color;
        //$lbb_question_font_size = (get_post_meta( $chatflow_id, 'lbb_question_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_question_font_size', true ) : $lbb_question_font_size;
        //$lbb_question_font_weight = (get_post_meta( $chatflow_id, 'lbb_question_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_question_font_weight', true ) : $lbb_question_font_weight;
        //$lbb_question_text_color = (get_post_meta( $chatflow_id, 'lbb_question_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_text_color', true ) : $lbb_question_text_color;
        //$lbb_question_background_color = (get_post_meta( $chatflow_id, 'lbb_question_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_background_color', true ) : $lbb_question_background_color;
        //$lbb_ans_bg_color = (get_post_meta( $chatflow_id, 'lbb_ans_bg_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_bg_color', true ) : $lbb_ans_bg_color;
        //$lbb_ans_border_color = (get_post_meta( $chatflow_id, 'lbb_ans_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_border_color', true ) : $lbb_ans_border_color;
        //$lbb_ans_text_color = (get_post_meta( $chatflow_id, 'lbb_ans_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_text_color', true ) : $lbb_ans_text_color;
        //$lbb_ans_border_radius = (get_post_meta( $chatflow_id, 'lbb_ans_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_ans_border_radius', true ) : $lbb_ans_border_radius;
        //$lbb_ans_font_size = (get_post_meta( $chatflow_id, 'lbb_ans_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_ans_font_size', true ) : $lbb_ans_font_size;
        //$lbb_ans_font_weight = (get_post_meta( $chatflow_id, 'lbb_ans_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_ans_font_weight', true ) : $lbb_ans_font_weight;
        //$lbb_max_border_color = (get_post_meta( $chatflow_id, 'lbb_max_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_color', true ) : $lbb_max_border_color;
        //$lbb_max_border_width = (get_post_meta( $chatflow_id, 'lbb_max_border_width', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_width', true ) : $lbb_max_border_width;
        //$lbb_max_border_radius = (get_post_meta( $chatflow_id, 'lbb_max_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_radius', true ) : $lbb_max_border_radius;
        //$lbb_max_width = (get_post_meta( $chatflow_id, 'lbb_max_width', true ))? get_post_meta( $chatflow_id, 'lbb_max_width', true ) : $lbb_max_width;
        //$lbb_max_height = (get_post_meta( $chatflow_id, 'lbb_max_height', true ))? get_post_meta( $chatflow_id, 'lbb_max_height', true ) : $lbb_max_height;
        //$lbb_max_bg_color = (get_post_meta( $chatflow_id, 'lbb_max_bg_color', true ))? get_post_meta( $chatflow_id, 'lbb_max_bg_color', true ) : $lbb_max_bg_color;


        /*Not in Use*/
        
        $lbb_container_image_width = (get_post_meta( $chatflow_id, 'lbb_container_image_width', true ))? get_post_meta( $chatflow_id, 'lbb_container_image_width', true ) : $lbb_container_image_width;
       $selected_url = get_post_meta( $chatflow_id, 'selected_url', true );
       $enter_url = (get_post_meta( $chatflow_id, 'enter_url', true ))? get_post_meta( $chatflow_id, 'enter_url', true ) : '';
       $page_scroll_value = (get_post_meta( $chatflow_id, 'page_scroll_value', true ))? get_post_meta( $chatflow_id, 'page_scroll_value', true ) : '';
       $when_to_show = (get_post_meta( $chatflow_id, 'when_to_show', true ))? get_post_meta( $chatflow_id, 'when_to_show', true ) : '';
       $livechat_status = (get_post_meta( $chatflow_id, 'livechat_status', true ))? get_post_meta( $chatflow_id, 'livechat_status', true ) : 'no';
       //$chat_alignment = (get_post_meta( $chatflow_id, 'chat_alignment', true ))? get_post_meta( $chatflow_id, 'chat_alignment', true ) : '';
       $time_input_value = (get_post_meta( $chatflow_id, 'time_input_value', true ))? get_post_meta( $chatflow_id, 'time_input_value', true ) : '';
       $who_should_see = (get_post_meta( $chatflow_id, 'who_should_see', true ))? get_post_meta( $chatflow_id, 'who_should_see', true ) : 'all_visitor';
       $automation_triggered = (get_post_meta( $chatflow_id, 'automation_triggered', true ))? get_post_meta( $chatflow_id, 'automation_triggered', true ) : 'after_email';
       $how_to_show = (get_post_meta( $chatflow_id, 'how_to_show', true ))? get_post_meta( $chatflow_id, 'how_to_show', true ) : $how_to_show;
       $admin_name = (get_post_meta( $chatflow_id, 'lbb_admin_name', true ))? get_post_meta( $chatflow_id, 'lbb_admin_name', true ) : '';

       $admin_name_live = !empty($lbb_general_settings['lbb_livechat_admin_name']) ? $lbb_general_settings['lbb_livechat_admin_name'] : '';
       //$admin_name_live = lbb_get_style($chatflow_id,'lbb_livechat_admin_name','');
        if($mode == 'live'){
            if(!empty($admin_name_live)){
                $admin_name = $admin_name_live;
            }
        }

       $start_again = (get_post_meta( $chatflow_id, 'start_again', true ))? get_post_meta( $chatflow_id, 'start_again', true ) : '';
       $chatbot_description = (get_post_meta( $chatflow_id, 'lbb_chatbot_description', true ))? get_post_meta( $chatflow_id, 'lbb_chatbot_description', true ) : '';
       $heading_font_family = (get_post_meta( $chatflow_id, 'heading_font_family', true ))? get_post_meta( $chatflow_id, 'heading_font_family', true ) : 'DM Sans,sans-serif';
       $content_font_family = (get_post_meta( $chatflow_id, 'content_font_family', true ))? get_post_meta( $chatflow_id, 'content_font_family', true ) : 'DM Sans,sans-serif';
       
       $content_font_weight = (get_post_meta( $chatflow_id, 'content_font_weight', true ))? get_post_meta( $chatflow_id, 'content_font_weight', true ) : '400';
       $global_style = (get_post_meta( $chatflow_id, 'global_style', true ))? get_post_meta( $chatflow_id, 'global_style', true ) : 'N';
       $bot_user_image = (get_post_meta( $chatflow_id, 'bot_user_image', true ))? get_post_meta( $chatflow_id, 'bot_user_image', true ) : LBB_URL.'admin/images/avatar.png';
       $icon_image = (get_post_meta( $chatflow_id, 'icon_image', true ))? get_post_meta( $chatflow_id, 'icon_image', true ) : LBB_URL.'admin/images/chat.png';
       $submit_button_icon = (get_post_meta( $chatflow_id, 'submit_button_icon', true ))? get_post_meta( $chatflow_id, 'submit_button_icon', true ) : LBB_URL.'admin/images/avatar.png';
       $lbb_automation_status = (get_post_meta( $chatflow_id, '_lbb_automation_status', true ))? get_post_meta( $chatflow_id, '_lbb_automation_status', true ) : [];
       $ques_bg_color = (get_post_meta( $chatflow_id, 'ques_bg_color', true ))? get_post_meta( $chatflow_id, 'ques_bg_color', true ) : '#ffffff';
       $ques_text_color = (get_post_meta( $chatflow_id, 'ques_text_color', true ))? get_post_meta( $chatflow_id, 'ques_text_color', true ) : '#000000';
       $ans_bg_color = (get_post_meta( $chatflow_id, 'ans_bg_color', true ))? get_post_meta( $chatflow_id, 'ans_bg_color', true ) : '#000000';
       



       $icon_style_global_status = (get_post_meta( $chatflow_id, 'icon_style_global_status', true )) ? get_post_meta( $chatflow_id, 'icon_style_global_status', true ) : '0';
       if($icon_style_global_status == 1){
        $lbb_icon_background_color = (get_option('lbb_icon_background_color' ))? get_option('lbb_icon_background_color', true ):'#effeff';
        $lbb_icon_border_radius = (get_option('lbb_icon_border_radius' ))? get_option('lbb_icon_border_radius', true ):'5';
        $lbb_chat_icon_color = (get_option('lbb_chat_icon_color' ))? get_option('lbb_chat_icon_color', true ):'#ffffff';
        $lbb_icon_box_size = (get_option('lbb_icon_box_size' ))? get_option('lbb_icon_box_size', true ):'60';
        $lbb_icon_padding = (get_option('lbb_icon_padding' ))? get_option('lbb_icon_padding', true ):'15';
       }else{
        $lbb_icon_background_color = (get_post_meta( $chatflow_id, 'lbb_icon_background_color', true )) ? get_post_meta( $chatflow_id, 'lbb_icon_background_color', true ) : '#2000f0';
        $lbb_icon_border_radius = (get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ) : $lbb_icon_border_radius;
        $lbb_chat_icon_color = (get_post_meta( $chatflow_id, 'lbb_chat_icon_color', true ))? get_post_meta( $chatflow_id, 'lbb_chat_icon_color', true ) : $lbb_chat_icon_color;
        $lbb_icon_box_size = (get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ) : '60';
        $lbb_icon_padding = (get_post_meta( $chatflow_id, 'lbb_icon_padding', true ))? get_post_meta( $chatflow_id, 'lbb_icon_padding', true ) : $lbb_icon_padding;
       }


       
       
       $lbb_icon_size = (get_post_meta( $chatflow_id, 'lbb_icon_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_size', true ) : '30';
       
       
       $sub_heading_font_family = (get_post_meta( $chatflow_id, 'sub_heading_font_family', true ))? get_post_meta( $chatflow_id, 'sub_heading_font_family', true ) : 'DM Sans,sans-serif';
       $button_border_radius = (get_post_meta( $chatflow_id, 'button_border_radius', true ))? get_post_meta( $chatflow_id, 'button_border_radius', true ) : '5';
       $button_text_color = (get_post_meta( $chatflow_id, 'button_text_color', true ))? get_post_meta( $chatflow_id, 'button_text_color', true ) : '#0066ff';
       $chat_background_color = (get_post_meta( $chatflow_id, 'chat_background_color', true ))? get_post_meta( $chatflow_id, 'chat_background_color', true ) : '#eaeef3';

       $lbb_ans_image_height = (get_post_meta( $chatflow_id, 'lbb_ans_image_height', true ))? get_post_meta( $chatflow_id, 'lbb_ans_image_height', true ) : $lbb_ans_image_height;
        $lbb_ans_image_object_fit = (get_post_meta( $chatflow_id, 'lbb_ans_image_object_fit', true ))? get_post_meta( $chatflow_id, 'lbb_ans_image_object_fit', true ) : $lbb_ans_image_object_fit;
        $lbb_ans_button_row_column = (get_post_meta( $chatflow_id, 'lbb_ans_button_row_column', true ))? get_post_meta( $chatflow_id, 'lbb_ans_button_row_column', true ) : $lbb_ans_button_row_column;

        $lbb_shadow_spread_radius = (get_post_meta( $chatflow_id, 'lbb_shadow_spread_radius', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_spread_radius', true ) : $lbb_shadow_spread_radius;
      $lbb_shadow_blur_radius = (get_post_meta( $chatflow_id, 'lbb_shadow_blur_radius', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_blur_radius', true ) : $lbb_shadow_blur_radius;
      $lbb_shadow_horizontal_length = (get_post_meta( $chatflow_id, 'lbb_shadow_horizontal_length', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_horizontal_length', true ) : $lbb_shadow_horizontal_length;
      $lbb_shadow_vertical_length = (get_post_meta( $chatflow_id, 'lbb_shadow_vertical_length', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_vertical_length', true ) : $lbb_shadow_vertical_length;
      $lbb_shadow_background_color = (get_post_meta( $chatflow_id, 'lbb_shadow_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_background_color', true ) : $lbb_shadow_background_color;

       $lbb_bottom_spacing = (get_post_meta( $chatflow_id, 'lbb_bottom_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_bottom_spacing', true ) : '20';
       $lbb_right_spacing = (get_post_meta( $chatflow_id, 'lbb_right_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_right_spacing', true ) : '20';
       $lbb_left_spacing = (get_post_meta( $chatflow_id, 'lbb_left_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_left_spacing', true ) : '20';
       $button_font_size = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
       $answer_button_font_size = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
       
       $lbb_icon_height = (get_post_meta( $chatflow_id, 'lbb_icon_height', true ))? get_post_meta( $chatflow_id, 'lbb_icon_height', true ) : $lbb_icon_height;
       $lbb_icon_width = (get_post_meta( $chatflow_id, 'lbb_icon_width', true ))? get_post_meta( $chatflow_id, 'lbb_icon_width', true ) : $lbb_icon_width;
       
       $aiassistant_main_prompt = (get_post_meta( $chatflow_id, 'aiassistant_main_prompt', true ))? get_post_meta( $chatflow_id, 'aiassistant_main_prompt', true ) : $aiassistant_main_prompt;
       $aiassistant_rules = (get_post_meta( $chatflow_id, 'aiassistant_rules', true ))? get_post_meta( $chatflow_id, 'aiassistant_rules', true ) : $aiassistant_rules;
       $input_token_limit = (get_post_meta( $chatflow_id, 'input_token_limit', true ))? get_post_meta( $chatflow_id, 'input_token_limit', true ) : $input_token_limit;
       $output_token_limit = (get_post_meta( $chatflow_id, 'output_token_limit', true ))? get_post_meta( $chatflow_id, 'output_token_limit', true ) : $output_token_limit;
       $limit_threads = (get_post_meta( $chatflow_id, 'limit_threads', true ))? get_post_meta( $chatflow_id, 'limit_threads', true ) : $limit_threads;
       $api_model = (get_post_meta( $chatflow_id, 'api_model', true ))? get_post_meta( $chatflow_id, 'api_model', true ) : $api_model;
       $allow_embed_domains = (get_post_meta( $chatflow_id, 'allow_embed_domains', true ))? get_post_meta( $chatflow_id, 'allow_embed_domains', true ) : $allow_embed_domains;
       $maximized_type = (get_post_meta( $chatflow_id, 'maximized_type', true ))? get_post_meta( $chatflow_id, 'maximized_type', true ) : $maximized_type;
       $maximized_chatbot_image = (get_post_meta( $chatflow_id, 'maximized_chatbot_image', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_image', true ) : $maximized_chatbot_image;
       $maximized_chatbot_icon = (get_post_meta( $chatflow_id, 'maximized_chatbot_icon', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_icon', true ) : $maximized_chatbot_icon;
       $maximized_chatbot_video = (get_post_meta( $chatflow_id, 'maximized_chatbot_video', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_video', true ) : $maximized_chatbot_video;

       $chatbot_background = (get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ))? get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ) : $lbb_style_chatbot_background;

       $chatbot_back_button = (get_post_meta( $chatflow_id, 'lbb_back_button', true ))? get_post_meta( $chatflow_id, 'lbb_back_button', true ) : 'no';


       /*$lbb_style_chatbot_background = (get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ))? get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ) : $lbb_style_chatbot_background;
       $lbb_chat_background_color = (get_post_meta( $chatflow_id, 'lbb_chat_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_color', true ) : $lbb_chat_background_color;
       $lbb_chat_background_image = (get_post_meta( $chatflow_id, 'lbb_chat_background_image', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_image', true ) : '';
       $lbb_chat_background_video = (get_post_meta( $chatflow_id, 'lbb_chat_background_video', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_video', true ) : $lbb_chat_background_video;*/

   }
    
    
$lbb_contactform_settings = get_option('lbb_contactform_settings');
if($lbb_contactform_settings){
    $lbb_contact_font_size = $lbb_contactform_settings['lbb_contact_font_size'];
    $lbb_contact_font_weight = $lbb_contactform_settings['lbb_contact_font_weight'];
    $lbb_contact_button_text_color = $lbb_contactform_settings['lbb_contact_button_text_color'];
    $lbb_contact_button_bg_color = $lbb_contactform_settings['lbb_contact_button_bg_color'];
}

$user_image = 'https://www.gravatar.com/avatar/HASH';
$user_id = get_current_user_id();
$lbbUserName = 'User';
if($user_id){
    $user_image = get_avatar_url($user_id, array('size' => 64));
    $lbbUserName = get_the_author_meta('nickname', $user_id);
}

if($when_to_show == 'upon_scroll'){
    $show_scroll = '';
    $show_timer = 'lbb-hide';
}else if($when_to_show == 'certain_time'){
    $show_scroll = 'lbb-hide';
    $show_timer = '';
}else{
    $show_scroll = 'lbb-hide';
    $show_timer = 'lbb-hide';
}

$con_key = 'lbbcf_'.$chatflow_id.'_conversation_id';
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

$lbb_mobile_heading_font_size = "20";
$lbb_mobile_subheading_font_size = "17";
$lbb_mobile_question_font_size = "17";
$lbb_mobile_answer_font_size = "17";
$lbb_mobile_answer_image_height = "40";
$lbb_mobile_selected_answer_font_size = "17";
$lbb_minimized_text_size = "14";
$lbb_mobile_answer_btn_font_size = "16";

/*Mobile Customizers Start*/
$lbb_mobile_heading_font_size = (get_option('lbb_mobile_heading_font_size' ))? get_option('lbb_mobile_heading_font_size', true ): $lbb_mobile_heading_font_size;
$lbb_mobile_subheading_font_size = (get_option('lbb_mobile_subheading_font_size' ))? get_option('lbb_mobile_subheading_font_size', true ): $lbb_mobile_subheading_font_size;
$lbb_mobile_question_font_size = (get_option('lbb_mobile_question_font_size' ))? get_option('lbb_mobile_question_font_size', true ): $lbb_mobile_question_font_size;
$lbb_mobile_answer_font_size = (get_option('lbb_mobile_answer_font_size' ))? get_option('lbb_mobile_answer_font_size', true ): $lbb_mobile_answer_font_size;
$lbb_mobile_answer_image_height = (get_option('lbb_mobile_answer_image_height' ))? get_option('lbb_mobile_answer_image_height', true ): $lbb_mobile_answer_image_height;
$lbb_mobile_selected_answer_font_size = (get_option('lbb_mobile_selected_answer_font_size' ))? get_option('lbb_mobile_selected_answer_font_size', true ): $lbb_mobile_selected_answer_font_size;
$lbb_minimized_text_size = (get_option('lbb_minimized_text_size' ))? get_option('lbb_minimized_text_size', true ): $lbb_minimized_text_size;
$lbb_mobile_answer_btn_font_size = (get_option('lbb_mobile_answer_btn_font_size' ))? get_option('lbb_mobile_answer_btn_font_size', true ): $lbb_mobile_answer_btn_font_size;
/*Mobile Customizers End*/

include( LBB_ABS_URL . 'admin/templates/chat/css-variables.php');

/*
$template_num = 'lbb-template-wrapper1 lbb-template-1';
if (isset($_GET['template'])) {
 $template_num = 'lbb-template-wrapper '.$_GET['template'];
}*/

if($lbb_style_chatbot_background == 'image') {
    $template_num = 'lbb-template-wrapper lbb-template-image';
}else if($lbb_style_chatbot_background == 'video') {
    $template_num = 'lbb-template-wrapper lbb-template-video';
}else{
    $template_num = 'lbb-template-wrapper lbb-template-color';
}

$lbb_emoji = lbb_get_option('lbb_emoji',"grinning smiley smile grin laughing sweat_smile joy rofl relaxed blush innocent slight_smile upside_down wink relieved crazy_face star_struck heart_eyes kissing_heart kissing kissing_smiling_eyes kissing_closed_eyes yum stuck_out_tongue_winking_eye stuck_out_tongue_closed_eyes stuck_out_tongue money_mouth hugging nerd sunglasses cowboy smirk unamused disappointed pensive worried face_with_raised_eyebrow face_with_monocle confused slight_frown grinning smiley smile grin laughing sweat_smile joy rofl relaxed blush innocent slight_smile upside_down");
$lbb_emoji_enable = lbb_get_option('lbb_emoji_enable','yes');

$lbb_gdpr_settings = get_option('lbb_gdpr_settings');
$lbb_google_font_enable = isset($lbb_gdpr_settings['lbb_google_font_enable']) ? $lbb_gdpr_settings['lbb_google_font_enable'] : 'yes';




if($lbb_google_font_enable == 'yes'){
?>
 <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=<?php echo $lbb_common_font_family; ?>">
<?php } ?>
<?php $is_iframe = isset($_GET['lbb-embed']) ? : ''; ?>

<?php
$minimized_type = (get_post_meta($chatflow_id,'minimized_type',true)) ? get_post_meta($chatflow_id,'minimized_type',true): 'icon';
?>
<div class="lbb-chat-start  <?php echo 'lbb-chat-icon-style-'.$minimized_type; ?> <?php echo 'lbb-chat-align-'.$chat_alignment; ?> <?php echo $is_iframe ? 'lbb-iframe-start' : ''; ?> <?php echo $is_iframe ? 'lbb-is-loading' : ''; ?> <?php echo ($how_to_show != 'inline') ? '' : 'lbb-chattype-inline'; ?> <?php echo $how_to_show ?> <?php echo $template_num; ?>" id="lbb-chat-main-wrapper" data-whentoshow="<?php echo $when_to_show ?>" data-time="<?php echo $time_input_value ?>" data-page_scroll="<?php echo $page_scroll_value ?>" style="<?php echo $is_iframe ? 'display:none' : ''; ?>">

<?php if($lbb_display_bot_callout == 'yes' && $how_to_show == 'minimized'){ ?>
<div class="lbb-notification-co <?php echo ($when_to_show != 'visitor_visit') ? 'lbb-hide' : '' ?>" style="<?php echo ($when_to_show != 'visitor_visit') ? '' : 'display: block;' ?>">
  <div class="lbb-notification-co-arrow">
    <img width="77" height="130" src="<?php echo LBB_URL ?>/admin/images/lbb-arrow-pointing-down-reversed.png">
  </div>
  <div class="lbb-notification-co-text">
    <?php echo $lbb_callout_text; ?>
  </div>
  <div class="lbb-notification-co-underline">
    <img width="150" height="15" src="<?php echo LBB_URL ?>/admin/images/lbb-underscore.svg" >
  </div>
</div>
<?php } ?>
<?php if($is_iframe){ ?> 
<script>
    window.addEventListener('load', function () {
        jQuery('.lbb-chat-start').show();
    });
   
</script>
<?php } ?>

<?php 
$lbb_audio = LBB_URL.'/admin/audio/audio_one.mp3';
if(get_option('lbb_general_settings') ){
    $lbb_general_settings = get_option('lbb_general_settings');
    $audio_options = !empty($lbb_general_settings['audio_options']) ? $lbb_general_settings['audio_options'] : 'audio_one';
    $lbb_audio_upload = !empty($lbb_general_settings['lbb_audio_upload']) ? $lbb_general_settings['lbb_audio_upload'] : '';
    if($audio_options == 'audio_two'){
        $lbb_audio = LBB_URL.'/admin/audio/audio_two.mp3';
    }else if($audio_options == 'audio_custom'){
        $lbb_audio = $lbb_audio_upload;
    }else{
        $lbb_audio = LBB_URL.'/admin/audio/audio_one.mp3';
    }
}



?>

<div style="display:none"><audio id="lbb-bell-audio" src="<?php echo $lbb_audio; ?>" preload="auto"></audio>
</div>

<div class="lbb-loading-mian" style="display:<?php echo $is_iframe ? 'block' : 'none'; ?>">
    <div class="lbb-loader-wrapper">
        <div class="lbb-loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>


<a id="lbbRedirectLink" href="#" style="display:none;" target="_blank"></a>
  
    <?php //include(LBB_ABS_URL.'admin/templates/chat/layout.php'); ?>

    
    
    <?php


    $settings_messages = get_option('lbb_message_data', array());

    $atts['reset_chat'] = !empty($settings_messages['reset_chat']) ? $settings_messages['reset_chat'] : 'Reset Chat';
    $atts['restart_chat'] = !empty($settings_messages['restart_chat']) ? $settings_messages['restart_chat'] : 'Restart Chat';
    $atts['back_button'] = $chatbot_back_button;

    $general_settings = get_option('lbb_general_settings',array());

    $lbb_made_with = (get_post_meta( $chatflow_id, 'lbb_made_with', true ))? get_post_meta( $chatflow_id, 'lbb_made_with', true ) : 'no';
    $atts['lbb_made_with'] = $lbb_made_with;

    $lbb_made_with_text = (get_post_meta( $chatflow_id, 'lbb_made_with_text', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_text', true ) : '';
    $atts['lbb_made_with_text'] = $lbb_made_with_text;

    $lbb_made_with_link = (get_post_meta( $chatflow_id, 'lbb_made_with_link', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_link', true ) : '';
    $atts['lbb_made_with_link'] = $lbb_made_with_link;

    $lbb_made_with_hover_text = (get_post_meta( $chatflow_id, 'lbb_made_with_hover_text', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_hover_text', true ) : '';
    $atts['lbb_made_with_hover_text'] = $lbb_made_with_hover_text;

    
    $lbb_minimized_type_option = get_post_meta($chatflow_id, 'lbb_minimized_type_option', true);

    

    $lbb_enable_search = get_post_meta($chatflow_id, 'lbb_enable_search', true);
    
    $atts['lbb_menu_item_chat'] = !empty($settings_messages['lbb_menu_item_chat']) ? $settings_messages['lbb_menu_item_chat'] : 'Chat';
    $atts['lbb_menu_item_search'] = !empty($settings_messages['lbb_menu_item_search']) ? $settings_messages['lbb_menu_item_search'] : 'Helpdesk';

    $atts['lbb_minimized_type_option'] = !empty($lbb_minimized_type_option) ? $lbb_minimized_type_option : 'show_minimized';

    $atts['lbb_enable_search'] = !empty($lbb_enable_search) ? $lbb_enable_search : 'no';
    
    $lbb_show_results = get_post_meta( $chatflow_id, 'lbb_show_results', true );
    $lbb_how_many = get_post_meta( $chatflow_id, 'lbb_how_many', true );
    $lbb_show_text = get_post_meta( $chatflow_id, 'lbb_show_text', true );

    $atts['lbb_show_results'] = !empty($lbb_show_results) ? $lbb_show_results : 'yes';
    $atts['lbb_how_many'] = !empty($lbb_how_many) ? $lbb_how_many : '5';
    $atts['lbb_show_text'] = !empty($lbb_show_text) ? $lbb_show_text : 'You can enter whatever you want to search for in the search box below and our bot will help find it for you from our resources collection!';

    $atts['chatflow_id'] = $chatflow_id;
    lbb_chat_main_common($chat_mode, $how_to_show, $lbb_image_upload, $admin_name, $chatbot_description, $atts, '', $lbb_chat_background_video,$lbb_chat_header);
    lbb_chat_btn_common($chatflow_id,$how_to_show, $when_to_show,false,$lbb_chat_background_video);


        $placeholders = array('text','name');
        

        $action_ids = get_post_meta($chatflow_id,'action_ids',true);
       
        if(!empty($action_ids)){
            $action_ids = explode(',',$action_ids);
            foreach ($action_ids as $key => $ques_id) {
                $type = get_post_meta($ques_id,'question_type',true);
                if(in_array($type,$placeholders)){
                    $placeholder_status = get_post_meta($ques_id,'custom_placeholder',true);
                    $placeholder_text = get_post_meta($ques_id,'funnel_placeholder',true);
                    if(!empty($placeholder_text)){
                        $settings_messages['lbb_input_placeholder_'.$type] = get_post_meta($ques_id,'funnel_placeholder',true);
                    }
                }
            }
        }

    ?>

    <?php
    
    global $wpdb;
    if($cookie_val > 0){
        $cnt = $wpdb->get_var('SELECT count(*) FROM `'.$wpdb->prefix.'lbb_messages` WHERE action_id <> 0 AND conversation_id = ' . $cookie_val .'');
    }else{
        $cnt = 0;
    }

    $lbb_livechat_options = get_option('lbb_livechat_options', 'ajax_based');

    $lbb_first_disappear_options = get_post_meta( $chatflow_id, 'lbb_first_disappear_options', true );
    $lbb_first_popout_options = get_post_meta( $chatflow_id, 'lbb_first_popout_options', true );
    $lbb_first_popout_how_many_seconds = get_post_meta( $chatflow_id, 'lbb_first_popout_how_many_seconds', true );
    $lbb_first_disappear_how_many_seconds = get_post_meta( $chatflow_id, 'lbb_first_disappear_how_many_seconds', true );

    ?>

    <script id="lbb-main-thing" type="text/javascript">
        var adminImg = '<?php echo $lbb_image_upload ?>';
        var lbbAdminName = "<?php echo $admin_name ?>";
        var lbbAdminLiveName = '<?php echo $admin_name_live ?>';
        var lbb_first_disappear_options = '<?php echo $lbb_first_disappear_options ?>';
        var lbb_first_popout_options = '<?php echo $lbb_first_popout_options ?>';
        var lbb_first_popout_how_many_seconds = '<?php echo $lbb_first_popout_how_many_seconds ?>';
        var lbb_first_disappear_how_many_seconds = '<?php echo $lbb_first_disappear_how_many_seconds ?>';
        var userImg = '<?php echo $user_image ?>';
        var lbbUserName = '<?php echo $lbbUserName ?>';
        var chat_mode = '<?php echo $chat_mode ?>';
        var chatflow_type = '<?php echo $chatflow_type ?>';
        var fieldPlaceholder = <?php echo json_encode($settings_messages) ?>;
        var adminTimer = <?php echo isset($general_settings['livechat_agent_timer']) ? $general_settings['livechat_agent_timer'] : 300; ?>;
        var userTimer = <?php echo isset($general_settings['livechat_user_timer']) ? $general_settings['livechat_user_timer'] : 300; ?>;
        var adminBusyMessage = '<?php echo get_option('livechat_agent_message', "Sorry, all agents are currently busy. Please leave a message below and we\'ll get back to you asap"); ?>';
        var userTimeoutMessage = '<?php echo  isset($general_settings['livechat_user_message']) ? addslashes($general_settings['livechat_user_message']) : "Thank you for contacting us. Resolving your concern is important to us. Due to inactivity, we will have to close out this chat. When it is convenient for you, please initiate another chat session with us. We are here to assist you 24x7."; ?>';
        var chat_id = '<?php echo $chatflow_id ?>';
        var lbb_emoji = '<?php echo $lbb_emoji ?>';
        var lbb_emoji_enable = '<?php echo $lbb_emoji_enable ?>';
        var lbb_minimized_type_option = '<?php echo $lbb_minimized_type_option ?>';
        var lbb_live_agent_image = '<?php echo $lbb_image_upload_live ?>';
        var lbb_is_fresh = <?php echo $cnt; ?>;
        var lbb_start_button = '<?php echo isset($general_settings['livechat_button_text']) ? $general_settings['livechat_button_text'] : 'Start now' ?>';
        var lbb_skip_question = '<?php echo isset($general_settings['lbb_skip_question']) ? $general_settings['lbb_skip_question'] : 'Skip this Message' ?>';
        var lbb_back_question = '<?php echo isset($settings_messages['lbb_back_button_text']) ? $settings_messages['lbb_back_button_text'] : 'Back' ?>';
        var lbb_livechat_options = '<?php echo !empty($lbb_livechat_options) ? $lbb_livechat_options : 'ajax_based' ?>';
        var admin_has_joined = '<?php echo $lbb_joined_chat ?>';
    </script>

    <script type="text/html" id="lbb-agent-response">
        <?php include(LBB_ABS_URL.'admin/templates/chat/agent-response.php'); ?>
    </script>

    <script type="text/html" id="lbb-user-response">
        <?php include(LBB_ABS_URL.'admin/templates/chat/user-response.php'); ?>
    </script>

    <?php

    lbb_firebase_hidden_elements($chatflow_id);

     /*
    <div class="Layout Layout-open Layout-expand Layout-right">
        <div class="Messenger_messenger">
            <div class="Messenger_header">
                <h4 class="Messenger_prompt">How can we help you?</h4> <span class="chat_close_icon"><i class="fa fa-window-close" aria-hidden="true"></i></span>
            </div>
            <div class="Messenger_content">
                <div class="Messages" id="chat-messages">
                    <div class="Messages_list"></div>
                </div>
                <form id="lbb_chatflow_messenger">
                    <div class="Input Input-blank">
<!--                            <textarea name="msg" class="Input_field" placeholder="Send a message..."></textarea> -->
                        <input name="lbb_input_message" class="Input_field lbb_input_message" placeholder="Send a message...">
                        <input id="lbb_chatflow_id" type="hidden" value="<?php echo $atts['id']; ?>">
                        <button type="submit" class="lbb-submit-message Input_button Input_button-send">
                            <div class="Icon">
                                <svg viewBox="1496 193 57 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Group-9-Copy-3" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)">
                                        <path d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C5.08414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z" id="sendicon" fill="#96AAB4" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    */ ?>
</div>
<!-- Chatbot -->