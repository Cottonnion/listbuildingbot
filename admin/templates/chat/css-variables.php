<style type="text/css">
	:root{
		--lbb-chat-content-font-family:<?php echo $lbb_common_font_family; ?>;
		--lbb-chat-container-width: <?php echo $lbb_container_width.'px'; ?>;
		--lbb-chat-heading-bg-color:<?php echo $lbb_heading_background_color; ?>;
		--lbb-chat-heading-font-size:<?php echo $lbb_heading_font_size.'px'; ?>;
		--lbb-chat-heading-font-weight:<?php echo $lbb_heading_font_weight; ?>;
		--lbb-chat-heading-text-color:<?php echo $lbb_heading_text_color; ?>;
		--lbb-chat-sub-heading-font-size:<?php echo $lbb_sub_heading_font_size.'px'; ?>;
		--lbb-chat-sub-heading-font-weight:<?php echo $lbb_sub_heading_font_weight; ?>;
		--lbb-chat-sub-heading-text-color:<?php echo $lbb_sub_heading_text_color; ?>;
		--lbb-question-font-size: <?php echo $lbb_question_font_size.'px'; ?>;
		--lbb-question-font-weight: <?php echo $lbb_question_font_weight; ?>;
		--lbb-question-text-color: <?php echo $lbb_question_text_color; ?>;
		--lbb-question-background-color: <?php echo $lbb_question_background_color; ?>;
		--lbb-question-input-border-color:<?php echo $lbb_question_input_border_color; ?>;
		--lbb-question-image-height:<?php echo $lbb_question_image_height.'px'; ?>;
		--lbb-container-image-width:<?php echo $lbb_container_image_width.'px'; ?>;

		--lbb-user-answer-font-size: <?php echo $lbb_user_answer_font_size.'px'; ?>;
		--lbb-user-answer-font-weight: <?php echo $lbb_user_answer_font_weight; ?>;
		--lbb-user-answer-text-color: <?php echo $lbb_user_answer_text_color; ?>;
		--lbb-user-answer-background-color: <?php echo $lbb_user_answer_background_color; ?>;

		--lbb-back-button-font-size: <?php echo $lbb_back_button_font_size.'px'; ?>;
		--lbb-back-button-font-weight: <?php echo $lbb_back_button_font_weight; ?>;
		--lbb-back-button-text-color: <?php echo $lbb_back_button_text_color; ?>;
		--lbb-back-button-background-color: <?php echo $lbb_back_button_background_color; ?>;
		
		--lbb-knowledge-background-color: <?php echo $lbb_knowledge_background_color; ?>;
		--lbb-knowledge-active-background-color: <?php echo $lbb_knowledge_active_background_color; ?>;
		--lbb-knowledge-active-color: <?php echo $lbb_knowledge_active_color; ?>;
		--lbb-knowledge-text-color: <?php echo $lbb_knowledge_text_color; ?>;
		--lbb-last-chatted-text-color: <?php echo $lbb_last_chatted_text_color; ?>;

		--lbb-chat-answer-btn-border-color:<?php echo $lbb_ans_border_color; ?>;
		--lbb-chat-answer-btn-text-color:<?php echo $lbb_ans_text_color; ?>;
		--lbb-chat-answer-btn-border-radius: <?php echo $lbb_ans_border_radius.'px'; ?>;
		--lbb-chat-answer-btn-background-color:<?php echo $lbb_ans_bg_color; ?>;
		--lbb-chat-answer-btn-font-size: <?php echo $lbb_ans_font_size.'px'; ?>;
		--lbb-chat-answer-btn-font-weight: <?php echo $lbb_ans_font_weight; ?>;
		--lbb-chat-answer-image-height: <?php echo $lbb_ans_image_height.'px'; ?>;
		--lbb-chat-answer-image-object-fit: <?php echo $lbb_ans_image_object_fit; ?>;
		--lbb-chat-answer-button-row-column: <?php echo $lbb_ans_button_row_column; ?>;
		--lbb-max-border-color: <?php echo $lbb_max_border_color; ?>;
		--lbb-max-border-width: <?php echo $lbb_max_border_width.'px'; ?>;
		--lbb-max-border-radius: <?php echo $lbb_max_border_radius.'px'; ?>;
		--lbb-max-width: <?php echo $lbb_max_width.'px'; ?>;
		--lbb-max-height: <?php echo $lbb_max_height.'px'; ?>;
		--lbb-max-bg-color: <?php echo $lbb_max_bg_color; ?>;
		--lbb-chat-background-image: <?php echo 'url('.$lbb_chat_background_image.')'; ?>;
		--lbb-chat-background-color:<?php echo $lbb_chat_background_color; ?>;
		--lbb-chat-container-padding:<?php echo $lbb_container_padding.'px'; ?>;
		--lbb-chatbot-height:<?php echo $lbb_chatbot_height.'px'; ?>;

		--lbb-chat-icon-background-color:<?php echo $lbb_icon_background_color; ?>;
		--lbb-chat-icon-padding:<?php echo $lbb_icon_padding.'px'; ?>;
		--lbb-chat-icon-width:<?php echo $lbb_icon_width.'px'; ?>;
		--lbb-chat-icon-height:<?php echo $lbb_icon_height.'px'; ?>;
		--lbb-chat-icon-btn-size:<?php echo $lbb_icon_size.'px'; ?>;
		--lbb-chat-icon-btn-border-radius:<?php echo $lbb_icon_border_radius.'px'; ?>;
		--lbb-chat-icon-btn-box-size:<?php echo $lbb_icon_box_size.'px'; ?>;
		--lbb-chat-icon-color:<?php echo $lbb_chat_icon_color; ?>;

		--lbb-shadow-spread-radius:<?php echo $lbb_shadow_spread_radius.'px'; ?>;
		--lbb-shadow-blur-radius:<?php echo $lbb_shadow_blur_radius.'px'; ?>;
		--lbb-shadow-horizontal-length:<?php echo $lbb_shadow_horizontal_length.'px'; ?>;
		--lbb-shadow-vertical-length:<?php echo $lbb_shadow_vertical_length.'px'; ?>;
		--lbb-shadow-background-color:<?php echo $lbb_shadow_background_color; ?>;



		
		--lbb-chat-ans-bg-color: <?php echo $lbb_ans_bg_color; ?>;
		--lbb-chat-content-right-spacing:<?php echo $lbb_right_spacing; ?>;
		--lbb-chat-content-left-spacing:<?php echo $lbb_left_spacing; ?>;
		--lbb-chat-content-bottom-spacing:<?php echo $lbb_bottom_spacing; ?>;
		/*--lbb-chat-answer-btn-font-size:<?php //echo $answer_button_font_size.'px'; ?>;*/
		--lbb-chat-sub-heading-font-family:<?php echo $sub_heading_font_family; ?>;
		--lbb-chat-ques-bg-color: <?php echo $ques_bg_color; ?>;
		--lbb-chat-ques-text-color: <?php echo $ques_text_color; ?>;		

		/*--lbb-chat-content-font-size:<?php //echo $content_font_size.'px'; ?>;
		--lbb-chat-content-font-weight:<?php //echo $content_font_weight; ?>;
		--lbb-chat-alignment:<?php //echo $chat_alignment; ?>;
		--lbb-message-background-color:<?php //echo $message_background_color; ?>;
		--lbb-message-text-color:<?php //echo $message_text_color; ?>;
		
		--lbb-chat-icon-color:<?php //echo $icon_color; ?>;*/

		--lbb-mobile-heading-font-size: <?php echo $lbb_mobile_heading_font_size.'px'; ?>;
		--lbb-mobile-subheading-font-size: <?php echo $lbb_mobile_subheading_font_size.'px'; ?>;
		--lbb-mobile-question-font-size: <?php echo $lbb_mobile_question_font_size.'px'; ?>;
		--lbb-mobile-answer-font-size: <?php echo $lbb_mobile_answer_font_size.'px'; ?>;
		--lbb-mobile-answer-image-height: <?php echo $lbb_mobile_answer_image_height.'px'; ?>;
		--lbb-mobile-selected-answer-font-size: <?php echo $lbb_mobile_selected_answer_font_size.'px'; ?>;
		--lbb-minimized-text-size: <?php echo $lbb_minimized_text_size.'px'; ?>;
		--lbb-mobile-answer-btn-font-size: <?php echo $lbb_mobile_answer_btn_font_size.'px'; ?>;

		--lbb-contact-font-size: <?php echo $lbb_contact_font_size; ?>;
        --lbb-contact-font-weight: <?php echo $lbb_contact_font_weight; ?>;
        --lbb-contact-button-text-color: <?php echo $lbb_contact_button_text_color; ?>;
        --lbb-contact-button-background-color: <?php echo $lbb_contact_button_bg_color; ?>;

        --lbb-callout-text-color: <?php echo $lbb_callout_text_color; ?>;
        --lbb-callout-font-size: <?php echo $lbb_callout_font_size.'px'; ?>;
	}
</style>