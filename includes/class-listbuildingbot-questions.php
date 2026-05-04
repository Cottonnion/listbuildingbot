<?php



class LBB_Questions {



    public static function get_questions($question_ids) {

        $args = array(

            'post_type' => 'lbb-chatflow-action',

            'posts_per_page' => -1,

            'post__in' => $question_ids,

        );



        $questionsObj = get_posts($args);

        $questions = array();



        foreach ($questionsObj as $key => $question) {

            $questions[$key] = self::get_question_data($question);

        }



        return $questions;

    }



    public static function getQuestion($id) {

        $question = array();

        $post = get_post($id);



        if ($post) {

            $question = self::get_question_data($post);

        }



        return $question;

    }



    private static function get_question_data($post) {

        $extra_messages = get_post_meta($post->ID, 'extra_messages', true);
        $choices = get_post_meta($post->ID, 'quick_reply_buttons', true);
        $advance_logic = get_post_meta($post->ID, 'advance_logic', true);
        $smart_automation = get_post_meta($post->ID, 'smart_automation', true);
        $dynamic_messages = get_post_meta($post->ID, 'dynamic_messages', true);
        $dynamic_message_status = get_post_meta($post->ID, 'dynamic_message_status', true);
        $input_select = get_post_meta($post->ID, 'input_select', true);
        $question_upload_type = get_post_meta($post->ID, 'question_upload_type', true);
        $maxFileUploadSize = get_post_meta($post->ID, 'maxFileUploadSize', true);
        $outcome_range = get_post_meta($post->ID, 'outcome_range', true);
        $outcome_range_enabled = get_post_meta($post->ID, 'outcome_range_enabled', true);
        $custom_placeholder = get_post_meta($post->ID, 'custom_placeholder', true);
        $funnel_placeholder = get_post_meta($post->ID, 'funnel_placeholder', true);
        $answer_image_height = get_post_meta($post->ID, 'answer_image_height', true);
        $answer_image_object_fit = get_post_meta($post->ID, 'answer_image_object_fit', true);
        $answer_img_button_row_column = get_post_meta($post->ID, 'answer_img_button_row_column', true);
        $enable_pdf_download = get_post_meta($post->ID, 'enable_pdf_download', true);
        $download_pdf_button = get_post_meta($post->ID, 'download_pdf_button', true);
        $download_pdf_button = !empty($download_pdf_button) ? $download_pdf_button : 'Download PDF';

        $pdfmap = get_post_meta($post->ID, 'pdfmap', true);

        return array(
            'id' => $post->ID,
            'title' => get_the_title($post->ID),
            'content' => get_the_content(null, false, $post->ID),
            'image' => get_post_meta($post->ID, 'image', true),
            'extra_messages' => !empty($extra_messages) ? $extra_messages : array(),
            'choices' => !empty($choices) ? $choices : array(),
            'advance_logic' => !empty($advance_logic) ? $advance_logic : array(),
            'save_property' => get_post_meta($post->ID, 'save_property', true),
            'image_answer' => get_post_meta($post->ID, 'image_answer', true),
            'skip_question' => get_post_meta($post->ID, 'skip_question', true),
            'trigger_automation' => get_post_meta($post->ID, 'trigger_automation', true),
            'mobile_image_answer' => get_post_meta($post->ID, 'mobile_image_answer', true),
            'smart_automation' => (!empty($smart_automation) && is_numeric($smart_automation)) ? intval($smart_automation) : 0,
            'dynamic_messages' => !empty($dynamic_messages) ? $dynamic_messages : array(),
            'dynamic_message_status' => (!empty($dynamic_message_status) && is_numeric($dynamic_message_status)) ? intval($dynamic_message_status) : 0,
            'answer_image_height' => (!empty($answer_image_height) && is_numeric($answer_image_height)) ? intval($answer_image_height) : 60,
            'answer_image_object_fit' => !empty($answer_image_object_fit) ? $answer_image_object_fit : 'contain',
            'answer_img_button_row_column' => !empty($answer_img_button_row_column) ? $answer_img_button_row_column : 3,
            'input_select' => !empty($input_select) ? $input_select : 'text',
            'question_upload_type' => !empty($question_upload_type) ? $question_upload_type : 'text',
            'maxFileUploadSize' => !empty($maxFileUploadSize) ? $maxFileUploadSize : '2',
            'outcome_range' => !empty($outcome_range) ? $outcome_range : array(),
            'outcome_range_enabled' => (!empty($outcome_range_enabled) && is_numeric($outcome_range_enabled)) ? intval($outcome_range_enabled) : 0,
            'custom_placeholder' => (!empty($custom_placeholder) && is_numeric($custom_placeholder)) ? intval($custom_placeholder) : 0,
            'funnel_placeholder' => !empty($funnel_placeholder) ? $funnel_placeholder : '',
            'enable_pdf_download' => (!empty($enable_pdf_download) && is_numeric($enable_pdf_download)) ? intval($enable_pdf_download) : 0,
            'download_pdf_button' => !empty($download_pdf_button) ? $download_pdf_button : '',
            'pdfmap' => !empty($pdfmap) ? $pdfmap : array(),
            'type' => get_post_meta($post->ID, 'question_type', true),
        );
    }



    public static function addNewQuestion($args){



        $args['post_type'] = 'lbb-chatflow-action';

        $post_id = wp_insert_post($args);

        return $post_id;

    }



    public static function update($args){



        $args['post_type'] = 'lbb-chatflow-action';

        $post_id = wp_update_post($args);

        return $post_id;

    }



    public static function delete($post_id){

        return wp_delete_post($post_id, true);

    }

}