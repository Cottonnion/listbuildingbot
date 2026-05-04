<?php
class Listbuildingbot_Import {
    public function __construct() {

    }

    public function import($post) {
        $title = $post['post_title'];
        $content = $post['post_content'];
        $post_type = 'lbb-chatflow';

        // Generate Post type
        $args = array(
            'post_type' => $post_type,
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id()
		);
        $post_id = wp_insert_post($args);
        if($post['metadata']){
            foreach ($post['metadata'] as $meta_key => $meta_value) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
            // Question creation
        }
        if(!empty($post['questions'])){
           foreach ($post['questions'] as $question){
                $args = array(
                    'post_title'   => $question['post_title'],
                    'post_content' => $question['post_content'],
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id()
                );
                // Create a new question post
                $q_id = LBB_Questions::addNewQuestion($args);
                if(isset($question['quick_reply_buttons'])){
                    update_post_meta($q_id, 'quick_reply_buttons', $question['quick_reply_buttons']);
                }
                if(isset($question['image'])){
                    update_post_meta($q_id, 'image', $question['image']);
                }
                update_post_meta($q_id, 'question_type', $question['type']);
                update_post_meta($q_id, 'next_question_id', $question['next_question_id']);
                if(isset($question['funnel_placeholder'])){
                    update_post_meta($q_id, 'funnel_placeholder', $question['funnel_placeholder']);
                }
                if(isset($question['custom_placeholder'])){
                    update_post_meta($q_id, 'custom_placeholder', $question['custom_placeholder']);
                }
                $question_id[] = $q_id;
            }
            update_post_meta($post_id, 'action_ids', implode(',',array_reverse($question_id)));
            $new_question_ids = $question_id;
        }
        update_post_meta($post_id, 'start_action_id', $question_id[0]);
        if(!empty($post['_questions_drawflow'])){
            $serializedJson = $post['_questions_drawflow'];
            $dataArray = json_decode($serializedJson, true);
            $oldQuestionId = $post['oldQuestionId'];
            //echo '<pre>';print_r($oldQuestionId);die();

            $newQuestionId = $question_id;

            if (count($oldQuestionId) === count($newQuestionId)) {
                foreach ($oldQuestionId as $key => $value) {
                    $questionIdMapping[$key] = array_shift($newQuestionId);
                }
            }
            lbbReplaceQuestionIds($dataArray['drawflow']['Home']['data'], $questionIdMapping);
            
            /*Replacing Class ids Start*/
            $classQuestionIdMapping = [];
            $classQuestionId = $question_id;
            foreach ($oldQuestionId as $oldClass => $newClass) {
                $classQuestionIdMapping[$oldClass] = 'node-question-' . array_shift($classQuestionId);
            }

            foreach ($dataArray['drawflow']['Home']['data'] as &$node) {
                lbbUpdateClassNames($node, $classQuestionIdMapping);
            }

            /*Replacing Class ids End*/
            // Encode the updated array back to JSON
            $updatedSerializedJson = json_encode($dataArray);
            update_post_meta($post_id, '_questions_drawflow', $updatedSerializedJson);
        }

        if(!empty($post['questions'])){
            foreach ($oldQuestionId as $key => $value) {
               $questionIdMapping[$key] = array_shift($question_id);
            }

            foreach($new_question_ids as $q_id){
                $get_old_id = get_post_meta($q_id, 'next_question_id', true);

                if($get_old_id){
                    if (array_key_exists($get_old_id, $questionIdMapping)) {
                        update_post_meta($q_id, 'next_question_id', $questionIdMapping[$get_old_id]);
                    } 

                    /* Quick reply Buttons*/

                    $quick_reply_buttons = get_post_meta($q_id, 'quick_reply_buttons', true);
                    if($quick_reply_buttons){
                        foreach ($quick_reply_buttons as &$subArray) {
                            if($subArray["map"]){
                                if (array_key_exists($subArray["map"], $questionIdMapping)) {
                                    $subArray["map"] = $questionIdMapping[$get_old_id];
                                }
                            }
                            
                        }
                        update_post_meta($q_id, 'quick_reply_buttons', $quick_reply_buttons);
                    }
                }
               
            }
        }


        update_post_meta($post_id, 'title_name', $post['title_name']);
        update_post_meta($post_id, 'lbb_admin_name', $post['lbb_admin_name']);
        update_post_meta($post_id, 'lbb_chatbot_description', $post['lbb_chatbot_description']);
        update_post_meta($post_id, 'livechat_status', $post['livechat_status']);
        update_post_meta($post_id, 'welcome_message', $post['welcome_message']);
        update_post_meta($post_id, 'live_chat_idle_time_enable', $post['live_chat_idle_time_enable']);
        update_post_meta($post_id, 'how_to_show', $post['how_to_show']);
        update_post_meta($post_id, 'minimized_type', $post['minimized_type']);
        update_post_meta($post_id, 'video_text', $post['video_text']);
        update_post_meta($post_id, 'when_to_show', $post['when_to_show']);
        update_post_meta($post_id, 'page_scroll_value', $post['page_scroll_value']);
        update_post_meta($post_id, 'who_should_see', $post['who_should_see']);
        update_post_meta($post_id, 'set_timeout', $post['set_timeout']);
        update_post_meta($post_id, 'email_capture', $post['email_capture']);
        update_post_meta($post_id, 'lbb_notify_chat', $post['lbb_notify_chat']);
        update_post_meta($post_id, 'chatbot_style_global_status', $post['chatbot_style_global_status']);
        update_post_meta($post_id, 'lbb_container_width', $post['lbb_container_width']);
        update_post_meta($post_id, 'lbb_common_font_family', $post['lbb_common_font_family']);
        update_post_meta($post_id, 'lbb_heading_background_color', $post['lbb_heading_background_color']);
        update_post_meta($post_id, 'lbb_image_upload', $post['lbb_image_upload']);
        update_post_meta($post_id, 'heading_style_global_status', $post['heading_style_global_status']);
        update_post_meta($post_id, 'lbb_heading_font_size', $post['lbb_heading_font_size']);
        update_post_meta($post_id, 'lbb_heading_font_weight', $post['lbb_heading_font_weight']);
        update_post_meta($post_id, 'lbb_heading_text_color', $post['lbb_heading_text_color']);
        update_post_meta($post_id, 'lbb_sub_heading_font_size', $post['lbb_sub_heading_font_size']);
        update_post_meta($post_id, 'lbb_sub_heading_font_weight', $post['lbb_sub_heading_font_weight']);
        update_post_meta($post_id, 'lbb_sub_heading_text_color', $post['lbb_sub_heading_text_color']);
        update_post_meta($post_id, 'question_style_global_status', $post['question_style_global_status']);
        update_post_meta($post_id, 'lbb_question_font_size', $post['lbb_question_font_size']);
        update_post_meta($post_id, 'lbb_question_font_weight', $post['lbb_question_font_weight']);
        update_post_meta($post_id, 'lbb_question_text_color', $post['lbb_question_text_color']);
        update_post_meta($post_id, 'lbb_question_background_color', $post['lbb_question_background_color']);
        update_post_meta($post_id, 'answer_style_global_status', $post['answer_style_global_status']);
        update_post_meta($post_id, 'lbb_ans_bg_color', $post['lbb_ans_bg_color']);
        update_post_meta($post_id, 'lbb_ans_border_color', $post['lbb_ans_border_color']);
        update_post_meta($post_id, 'lbb_ans_text_color', $post['lbb_ans_text_color']);
        update_post_meta($post_id, 'lbb_ans_border_radius', $post['lbb_ans_border_radius']);
        update_post_meta($post_id, 'lbb_ans_font_size', $post['lbb_ans_font_size']);
        update_post_meta($post_id, 'lbb_ans_font_weight', $post['lbb_ans_font_weight']);
        update_post_meta($post_id, 'alignment_style_global_status', $post['alignment_style_global_status']);
        update_post_meta($post_id, 'chat_alignment', $post['chat_alignment']);
        update_post_meta($post_id, 'lbb_right_spacing', $post['lbb_right_spacing']);
        update_post_meta($post_id, 'lbb_left_spacing', $post['lbb_left_spacing']);
        update_post_meta($post_id, 'lbb_bottom_spacing', $post['lbb_bottom_spacing']);
        update_post_meta($post_id, 'expanded_style_global_status', $post['expanded_style_global_status']);
        update_post_meta($post_id, 'backgroun_style_global_status', $post['backgroun_style_global_status']);
        update_post_meta($post_id, 'lbb_style_chatbot_background', $post['lbb_style_chatbot_background']);
        update_post_meta($post_id, 'lbb_chat_background_color', $post['lbb_chat_background_color']);
        update_post_meta($post_id, 'lbb_chat_background_video', $post['lbb_chat_background_video']);
        update_post_meta($post_id, 'automation_triggered', $post['automation_triggered']);
        update_post_meta($post_id, 'lbb_container_padding', $post['lbb_container_padding']);
        update_post_meta($post_id, 'lbb_chat_background_image', $post['lbb_chat_background_image']);
        update_post_meta($post_id, 'lbb_question_input_border_color', $post['lbb_question_input_border_color']);


        $data = array('edit_link' => admin_url( 'admin.php?page=listbuildingbot&action=edit&id='.$post_id));
        return $data;

    }

    
}