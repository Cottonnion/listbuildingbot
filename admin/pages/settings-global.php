<?php
include( LBB_ABS_URL . 'admin/pages/default-variables.php');

$lbb_mobile_heading_font_size = "20";
$lbb_mobile_subheading_font_size = "17";
$lbb_mobile_question_font_size = "17";
$lbb_mobile_answer_font_size = "17";
$lbb_mobile_answer_image_height = "40";
$lbb_mobile_selected_answer_font_size = "17";
$lbb_minimized_text_size = "14";
$lbb_mobile_answer_btn_font_size = "16";

$icon_size = "30";
/* Styles Start */
$lbb_icon_background_color = (get_option('lbb_icon_background_color' ))? get_option('lbb_icon_background_color', true ): $lbb_icon_background_color;
$lbb_icon_border_radius = (get_option('lbb_icon_border_radius' ))? get_option('lbb_icon_border_radius', true ): $icon_border_radius;
$icon_size = (get_option('lbb_icon_size' ))? get_option('lbb_icon_size', true ): $lbb_icon_size;
$lbb_icon_box_size = (get_option('lbb_icon_box_size' ))? get_option('lbb_icon_box_size', true ): $lbb_icon_box_size;
$lbb_icon_padding = (get_option('lbb_icon_padding' ))? get_option('lbb_icon_padding', true ): $lbb_icon_padding;

$lbb_admin_name = (get_option('lbb_admin_name' ))? get_option('lbb_admin_name', true ): $lbb_admin_name;
$lbb_chatbot_description = (get_option('lbb_chatbot_description' ))? get_option('lbb_chatbot_description', true ): $lbb_chatbot_description;
$lbb_container_width = (get_option('lbb_container_width' ))? get_option('lbb_container_width', true ): $lbb_container_width;
$lbb_chatbot_height = (get_option('lbb_chatbot_height' ))? get_option('lbb_chatbot_height', true ): $lbb_chatbot_height;
$lbb_bot_image_select = (get_option('lbb_bot_image_select' ))? get_option('lbb_bot_image_select', true ): $lbb_bot_image_select;
$lbb_container_padding = (get_option('lbb_container_padding' ))? get_option('lbb_container_padding', true ): $lbb_container_padding;
$lbb_style_chatbot_background = (get_option('lbb_style_chatbot_background' ))? get_option('lbb_style_chatbot_background', true ): $lbb_style_chatbot_background;
$lbb_chat_background_color = (get_option('lbb_chat_background_color' ))? get_option('lbb_chat_background_color', true ): $lbb_chat_background_color;
$lbb_chat_background_image = (get_option('lbb_chat_background_image' ))? get_option('lbb_chat_background_image', true ): $lbb_chat_background_image;
$lbb_chat_background_video = (get_option('lbb_chat_background_video' ))? get_option('lbb_chat_background_video', true ): $lbb_chat_background_video;
$lbb_ans_image_object_fit = (get_option('lbb_ans_image_object_fit' ))? get_option('lbb_ans_image_object_fit', true ): $lbb_ans_image_object_fit;
$lbb_ans_button_row_column = (get_option('lbb_ans_button_row_column' ))? get_option('lbb_ans_button_row_column', true ): $lbb_ans_button_row_column;
$lbb_back_button_font_size = (get_option('lbb_back_button_font_size' ))? get_option('lbb_back_button_font_size', true ): $lbb_back_button_font_size;
$lbb_back_button_font_weight = (get_option('lbb_back_button_font_weight' ))? get_option('lbb_back_button_font_weight', true ): $lbb_back_button_font_weight;
$lbb_back_button_text_color = (get_option('lbb_back_button_text_color' ))? get_option('lbb_back_button_text_color', true ): $lbb_back_button_text_color;
$lbb_back_button_background_color = (get_option('lbb_back_button_background_color' ))? get_option('lbb_back_button_background_color', true ): $lbb_back_button_background_color;

$lbb_container_image_width = (get_option('lbb_container_image_width' ))? get_option('lbb_container_image_width', true ): $lbb_container_image_width;
$lbb_common_font_family = (get_option('lbb_common_font_family' ))? get_option('lbb_common_font_family', true ): $lbb_common_font_family;
$lbb_heading_background_color = (get_option('lbb_heading_background_color' ))? get_option('lbb_heading_background_color', true ): $lbb_heading_background_color;
$lbb_image_upload = (get_option('lbb_image_upload' ))? get_option('lbb_image_upload', true ): $lbb_image_upload;

$lbb_heading_font_size = (get_option('lbb_heading_font_size' ))? get_option('lbb_heading_font_size', true ): $lbb_heading_font_size;
$lbb_heading_font_weight = (get_option('lbb_heading_font_weight' ))? get_option('lbb_heading_font_weight', true ): $lbb_heading_font_weight;
$lbb_heading_text_color = (get_option('lbb_heading_text_color' ))? get_option('lbb_heading_text_color', true ): $lbb_heading_text_color;
$lbb_sub_heading_font_size = (get_option('lbb_sub_heading_font_size' ))? get_option('lbb_sub_heading_font_size', true ): $lbb_sub_heading_font_size;
$lbb_sub_heading_font_weight = (get_option('lbb_sub_heading_font_weight' ))? get_option('lbb_sub_heading_font_weight', true ): $lbb_sub_heading_font_weight;
$lbb_sub_heading_text_color = (get_option('lbb_sub_heading_text_color' ))? get_option('lbb_sub_heading_text_color', true ): $lbb_sub_heading_text_color;

$lbb_question_font_size = (get_option('lbb_question_font_size' ))? get_option('lbb_question_font_size', true ): $lbb_question_font_size;
$lbb_question_font_weight = (get_option('lbb_question_font_weight' ))? get_option('lbb_question_font_weight', true ): $lbb_question_font_weight;
$lbb_question_text_color = (get_option('lbb_question_text_color' ))? get_option('lbb_question_text_color', true ): $lbb_question_text_color;
$lbb_question_background_color = (get_option('lbb_question_background_color' ))? get_option('lbb_question_background_color', true ): $lbb_question_background_color;
$lbb_question_input_border_color = (get_option('lbb_question_input_border_color' ))? get_option('lbb_question_input_border_color', true ): $lbb_question_input_border_color;
$lbb_question_image_height = (get_option('lbb_question_image_height' ))? get_option('lbb_question_image_height', true ): $lbb_question_image_height;

$lbb_user_answer_font_size = (get_option('lbb_user_answer_font_size' ))? get_option('lbb_user_answer_font_size', true ): $lbb_user_answer_font_size;
$lbb_user_answer_font_weight = (get_option('lbb_user_answer_font_weight' ))? get_option('lbb_user_answer_font_weight', true ): $lbb_user_answer_font_weight;
$lbb_user_answer_text_color = (get_option('lbb_user_answer_text_color' ))? get_option('lbb_user_answer_text_color', true ): $lbb_user_answer_text_color;
$lbb_user_answer_background_color = (get_option('lbb_user_answer_background_color' ))? get_option('lbb_user_answer_background_color', true ): $lbb_user_answer_background_color;

$lbb_ans_bg_color = (get_option('lbb_ans_bg_color' ))? get_option('lbb_ans_bg_color', true ): $lbb_ans_bg_color;
$lbb_ans_border_color = (get_option('lbb_ans_border_color' ))? get_option('lbb_ans_border_color', true ): $lbb_ans_border_color;
$lbb_ans_text_color = (get_option('lbb_ans_text_color' ))? get_option('lbb_ans_text_color', true ): $lbb_ans_text_color;
$lbb_ans_border_radius = (get_option('lbb_ans_border_radius' ))? get_option('lbb_ans_border_radius', true ): $lbb_ans_border_radius;
$lbb_ans_font_size = (get_option('lbb_ans_font_size' ))? get_option('lbb_ans_font_size', true ): $lbb_ans_font_size;
$lbb_ans_font_weight = (get_option('lbb_ans_font_weight' ))? get_option('lbb_ans_font_weight', true ): $lbb_ans_font_weight;

$lbb_max_border_color = (get_option('lbb_max_border_color' ))? get_option('lbb_max_border_color', true ): $lbb_max_border_color;
$lbb_max_border_width = (get_option('lbb_max_border_width' ))? get_option('lbb_max_border_width', true ): $lbb_max_border_width;
$lbb_max_border_radius = (get_option('lbb_max_border_radius' ))? get_option('lbb_max_border_radius', true ): $lbb_max_border_radius;
$lbb_max_width = (get_option('lbb_max_width' ))? get_option('lbb_max_width', true ): $lbb_max_width;
$lbb_max_height = (get_option('lbb_max_height' ))? get_option('lbb_max_height', true ): $lbb_max_height;
$lbb_max_bg_color = (get_option('lbb_max_bg_color' ))? get_option('lbb_max_bg_color', true ): $lbb_max_bg_color;

$lbb_knowledge_background_color = (get_option('lbb_knowledge_background_color' ))? get_option('lbb_knowledge_background_color', true ): $lbb_knowledge_background_color;
$lbb_knowledge_active_background_color = (get_option('lbb_knowledge_active_background_color' ))? get_option('lbb_knowledge_active_background_color', true ): $lbb_knowledge_active_background_color;
$lbb_knowledge_active_color = (get_option('lbb_knowledge_active_color' ))? get_option('lbb_knowledge_active_color', true ): $lbb_knowledge_active_color;
$lbb_knowledge_text_color = (get_option('lbb_knowledge_text_color' ))? get_option('lbb_knowledge_text_color', true ): $lbb_knowledge_text_color;
$lbb_last_chatted_text_color = (get_option('lbb_last_chatted_text_color' ))? get_option('lbb_last_chatted_text_color', true ): $lbb_last_chatted_text_color;


$lbb_shadow_spread_radius = (get_option('lbb_shadow_spread_radius' ))? get_option('lbb_shadow_spread_radius', true ): $lbb_shadow_spread_radius;
$lbb_shadow_blur_radius = (get_option('lbb_shadow_blur_radius' ))? get_option('lbb_shadow_blur_radius', true ): $lbb_shadow_blur_radius;
$lbb_shadow_horizontal_length = (get_option('lbb_shadow_horizontal_length' ))? get_option('lbb_shadow_horizontal_length', true ): $lbb_shadow_horizontal_length;
$lbb_shadow_vertical_length = (get_option('lbb_shadow_vertical_length' ))? get_option('lbb_shadow_vertical_length', true ): $lbb_shadow_vertical_length;
$lbb_shadow_background_color = (get_option('lbb_shadow_background_color' ))? get_option('lbb_shadow_background_color', true ): $lbb_shadow_background_color;


$lbb_chat_alignment = (get_option('lbb_chat_alignment' ))? get_option('lbb_chat_alignment', true ): $lbb_chat_alignment;
$lbb_right_spacing = (get_option('lbb_right_spacing' ))? get_option('lbb_right_spacing', true ): $lbb_right_spacing;
$lbb_left_spacing = (get_option('lbb_left_spacing' ))? get_option('lbb_left_spacing', true ): $lbb_left_spacing;
$lbb_bottom_spacing = (get_option('lbb_bottom_spacing' ))? get_option('lbb_bottom_spacing', true ): $lbb_bottom_spacing;

$lbb_chat_background_video = (get_option('lbb_chat_background_video' ))? get_option('lbb_chat_background_video', true ): $lbb_chat_background_video;

$chatflow_id = "";



$sub_heading_font_family = (get_option('lbb_sub_heading_font_family' ))? get_option('lbb_sub_heading_font_family', true ):'DM Sans,sans-serif';
$content_font_family = (get_option('lbb_content_font_family' ))? get_option('lbb_content_font_family', true ):'DM Sans,sans-serif';
$content_font_size = (get_option('lbb_content_font_size' ))? get_option('lbb_content_font_size', true ):'16';
$content_font_weight = (get_option('lbb_content_font_weight' ))? get_option('lbb_content_font_weight', true ):'400';
$message_background_color = (get_option('lbb_message_background_color' ))? get_option('lbb_message_background_color', true ):'#ffffff';
$message_text_color = (get_option('lbb_message_text_color' ))? get_option('lbb_message_text_color', true ):'#00000';
$chat_background_color = (get_option('lbb_chat_background_color' ))? get_option('lbb_chat_background_color', true ):'#eaeef3';
$button_background_color = (get_option('lbb_button_background_color' ))? get_option('lbb_button_background_color', true ):'#ffffff';
$button_text_color = (get_option('lbb_button_text_color' ))? get_option('lbb_button_text_color', true ):'#0066ff';
$button_border_color = (get_option('lbb_button_border_color' ))? get_option('lbb_button_border_color', true ):'#0066ff';
$answer_button_font_size = (get_option('lbb_answer_button_font_size' ))? get_option('lbb_answer_button_font_size', true ):'16';
$button_border_radius = (get_option('lbb_button_border_radius' ))? get_option('lbb_button_border_radius', true ):'5';

$ques_bg_color = (get_option('lbb_ques_bg_color' ))? get_option('lbb_ques_bg_color', true ):'#ffffff';
$ques_text_color = (get_option('lbb_ques_text_color' ))? get_option('lbb_ques_text_color', true ):'#000000';

$submit_button_icon = (get_option('lbb_submit_button_icon' ))? get_option('lbb_submit_button_icon', true ): LBB_URL.'admin/images/avatar.png';
$bot_user_image = (get_option('lbb_bot_user_image' ))? get_option('lbb_bot_user_image', true ): LBB_URL.'admin/images/avatar.png';
$start_again = (get_option('lbb_start_again' ))? get_option('lbb_start_again', true ): $start_again;
$icon_color = (get_option('lbb_icon_color' ))? get_option('lbb_icon_color', true ): $icon_color;

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

$how_to_show = "minimized";
$when_to_show = "visitor_visit";


?>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=<?php echo $lbb_common_font_family; ?>">
<div class="lbb-page-heading-main">
    <div class="lbb-page-heading lbb-page-with-subheading">
        <i class="bx bxs-info-square"></i>
        <h3>Global Style<small>Setup the Bot Style Options Globally! You can override the global settings for each bot in the bot editor >> style tab. </small></h3>
    </div>
</div>
 <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section lbb-chatflow-style-section">
      <div class="lbb-container lbb-vertical-container">
          <div class="lbb-vertical-content-up">
              <div class="lbb-content lbb-vertical-content lbb-w-100-important">
                <form method="POST" class="lbb-form-global-settings">
                    <div class="lbb-row lbb-align-items-stretch lbb-m-0 lbb-bg-light-gray lbb-height-100vh-120">
                        <div class="lbb-chatbot-preview-form-wrapper">
                            <!-- Heading/Subheading -->
        


                                <div class="lbb-accordion">
                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Chat Icon </h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: block;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_chat_heading">Icon Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_icon_background_color]" id="lbb_chat_heading" value="<?php echo $lbb_icon_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_icon_background_color; ?>" data-css-variable="lbb-chat-icon-background-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_icon_border_radius">Icon Border Radius:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo $lbb_icon_border_radius; ?>" data-value-px="px" data-css-variable="lbb-chat-icon-btn-border-radius"></div>
                                                            <input id="lbb_icon_border_radius" name="lbb_meta[lbb_icon_border_radius]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                 <div class="lbb-col-4">
                                                    <div class="lbb-form-group lbb-style-color">
                                                        <label for="lbb_chat_icon_color">Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_chat_icon_color]" id="lbb_chat_icon_color" value="<?php echo $lbb_chat_icon_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_chat_icon_color; ?>" data-css-variable="lbb-chat-icon-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_icon_box_size">Icon Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="35" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo $lbb_icon_box_size; ?>" data-value-px="px" data-css-variable="lbb-chat-icon-btn-box-size"></div>
                                                            <input id="lbb_icon_box_size" name="lbb_meta[lbb_icon_box_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_icon_padding">Icon Padding:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="5" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_icon_padding; ?>" data-value-px="px" data-css-variable="lbb-chat-icon-padding"></div>
                                                            <input id="lbb_icon_padding" name="lbb_meta[lbb_icon_padding]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Chatbot</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-6">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_admin_name">Chatbot Display Name </label>
                                                        <input id="lbb_admin_name" name="lbb_meta[lbb_admin_name]" class="lbb-input-field" type="text" value="<?php echo $lbb_admin_name; ?>">
                                                    </div>
                                                </div>

                                                <div class="lbb-col-6">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_chatbot_description">Chatbot Description</label>
                                                        <textarea id="lbb_chatbot_description" name="lbb_meta[lbb_chatbot_description]" class="lbb-input-field" type="text" ><?php echo $lbb_chatbot_description; ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-6">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_font_size">Chatbot Width:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="200" data-slider-max="2000" data-slider-step="1" data-slider-value="<?php echo $lbb_container_width; ?>" data-value-px="px" data-css-variable="lbb-chat-container-width"></div>
                                                            <input id="lbb_lbb_container_width" name="lbb_meta[lbb_container_width]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-6">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_font_size">Chatbot Height:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="200" data-slider-max="1000" data-slider-step="1" data-slider-value="<?php echo $lbb_chatbot_height; ?>" data-value-px="px" data-css-variable="lbb-chatbot-height"></div>
                                                            <input id="lbb_chatbot_height" name="lbb_meta[lbb_chatbot_height]" class="lbb-slider-input lbb-input-field" type="text" data-css-variable="lbb-chatbot-height">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-12 lbb-logic-bot-options" style="<?php echo $chatflow_type == 'livechat' ? 'display: none;':''; ?>">
                                                <div class="lbb-form-group">
                                                    <label>Select a Bot Image</label>
                                                    <ul class="lbb-radio-btn-wrapper bot-image-outer">
                                                        <li>
                                                            <input type="radio" id="image_one" name="lbb_meta[lbb_bot_image_select]" value="image-one" <?php echo $lbb_bot_image_select == 'image-one'?'checked="checked"':''; ?>>
                                                            <label for="image_one"><img src="<?php echo LBB_URL; ?>/admin/images/person-image-1.png"></label>
                                                            <div class="lbb-check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="image_two" type="radio" name="lbb_meta[lbb_bot_image_select]" value="image-two" <?php echo $lbb_bot_image_select == 'image-two'?'checked="checked"':''; ?>>
                                                            <label for="image_two"><img for="image_two" src="<?php  echo LBB_URL; ?>/admin/images/person-image-2.png"></label>
                                                            <div class="lbb-check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="image_three" type="radio" name="lbb_meta[lbb_bot_image_select]" value="image-three" <?php echo $lbb_bot_image_select == 'image-three'?'checked="checked"':''; ?>>
                                                            <label for="image_three"><img src="<?php echo LBB_URL; ?>/admin/images/person-image-3.png"></label>
                                                            <div class="lbb-check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="image_four" type="radio" name="lbb_meta[lbb_bot_image_select]" value="image-four" <?php echo $lbb_bot_image_select == 'image-four'?'checked="checked"':''; ?>>
                                                            <label for="image_four"><img src="<?php echo LBB_URL; ?>/admin/images/person-image-4.png"></label>
                                                            <div class="lbb-check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="image_custom" type="radio" name="lbb_meta[lbb_bot_image_select]" value="image-custom" <?php echo $lbb_bot_image_select == 'image-custom'?'checked="checked"':''; ?>>
                                                            <?php 
                                                            if($lbb_bot_image_select == 'image-custom'){
                                                                $show_custom_class = "";
                                                                $lbb_image_upload = $lbb_image_upload;
                                                            }else{
                                                                $show_custom_class = "lbb-no-img";
                                                                $lbb_image_upload = "";
                                                            }
                                                            ?>
                                                            <label for="image_custom">
                                                                <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo $show_custom_class; ?>">
                                                                    <div class="lbb-bot-user-image ">
                                                                        <a class="lbb-image-upload-button lbb-common-image-upload" data-type="lbb_image_upload" href="javascript:void(0)">Upload Image</a>
                                                                        <input type="hidden" id="lbb_image_upload" name="lbb_meta[lbb_image_upload]" value="<?php echo $lbb_image_upload; ?>">
                                                                    </div>
                                                                    <div class="lbb-image-preview-container">
                                                                        <img src="<?php echo $lbb_image_upload; ?>" alt="Preview Image" class="lbb-preview-image">
                                                                        <div class="lbb-image-actions">
                                                                            <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                                            <span class="delete-icon"><span class="dashicons dashicons-trash" data-type="lbb_image_upload"></span></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <div class="lbb-check"></div>
                                                        </li>
                                                  </ul>

                                                    
                                                </div>
                                            </div>

                                            <div class="lbb-col-6 lbb-livechat-image" style="<?php echo $chatflow_type == 'livechat' || $chatflow_type == 'botlivechat' ?'':'display: none;'; ?>">
                                                <div class="lbb-form-group">
                                                    <label>Upload Image</label>
                                                    <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo (!empty($lbb_image_upload_live)) ? '' : 'lbb-no-img' ?>">
                                                        <div class="lbb-bot-user-image ">
                                                          <a class="lbb-image-upload-button lbb-common-image-upload" href="javascript:void(0)" data-type="lbb_image_upload_live">Upload</a>
                                                          <input type="hidden" id="lbb_image_upload_live" name="lbb_meta[lbb_image_upload_live]" value="<?php echo $lbb_image_upload_live; ?>">
                                                        </div>
                                                        <div class="lbb-image-preview-container">
                                                            <img src="<?php echo $lbb_image_upload_live; ?>" alt="Preview Image" class="lbb-preview-image">
                                                            <div class="lbb-image-actions">
                                                                <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                                <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                                <div class="lbb-col-4 lbb-logic-bot-options" style="<?php echo $chatflow_type == 'livechat' ? 'display: none;':''; ?>">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_image_width">Bot Image Width:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider lbb-container-image-width" data-slider-min="30" data-slider-max="60" data-slider-step="1" data-slider-value="<?php echo $lbb_container_image_width; ?>" data-value-px="px" data-css-variable="lbb-container-image-width"></div>
                                                        <input id="lbb_container_image_width" name="lbb_meta[lbb_container_image_width]" class="lbb-slider-input lbb-input-field" type="text" data-css-variable="lbb-container-image-width">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_chat_heading">Message Background:</label>
                                                    <input type="text" name="lbb_meta[lbb_heading_background_color]" id="lbb_chat_heading" value="<?php echo $lbb_heading_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_heading_background_color; ?>" data-css-variable="lbb-chat-heading-bg-color" />
                                                </div>
                                            </div>

                                            <div class="lbb-col-6">
                                                <div class="lbb-form-group js-select2-wrapper">
                                                    <label for="common-font-family">Font Family:</label>
                                                    <select name="lbb_meta[lbb_common_font_family]" id="common-font-family" class="js-select2-with-search lbb-font-family" data-css-variable="lbb-chat-content-font-family">
                                                        <?php $common_font = str_replace("value='".$lbb_common_font_family."'", "value='".$lbb_common_font_family."' selected" ,$global_font_family_list);
                                                             echo $common_font; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_padding">Padding:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider lbb-container-padding" data-slider-min="10" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_container_padding; ?>" data-value-px="px" data-css-variable="lbb-chat-container-padding"></div>
                                                        <input id="lbb_container_padding" name="lbb_meta[lbb_container_padding]" class="lbb-slider-input lbb-input-field" type="text" data-css-variable="lbb-chat-container-padding">
                                                    </div>
                                                </div>
                                            </div>

                                                

                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Background</h2>
                                            <div class="lbb-accordion-right-content">
                                                
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="minimized-options">
                                                   <ul>
                                                      <li>
                                                         <div class="lbb-form-group lbb-radio-buttons">
                                                            <input type="radio" name="lbb_meta[lbb_style_chatbot_background]" id="background_type_icon" value="color" <?php echo $lbb_style_chatbot_background == 'color'?'checked="checked"':''; ?>>
                                                            <label for="background_type_icon">Color</label>
                                                            <div class="lbb-check"></div>
                                                         </div>
                                                      </li>

                                                      <li>
                                                         <div class="lbb-form-group lbb-radio-buttons">
                                                            <input type="radio" name="lbb_meta[lbb_style_chatbot_background]" id="background_type_image" value="image" <?php echo $lbb_style_chatbot_background == 'image'?'checked="checked"':''; ?>>
                                                            <label for="background_type_image">Image</label>
                                                            <div class="lbb-check"></div>
                                                         </div>
                                                      </li>
                                                      <li>
                                                         <div class="lbb-form-group lbb-radio-buttons">
                                                            <input type="radio" name="lbb_meta[lbb_style_chatbot_background]" id="background_type_video" value="video" <?php echo $lbb_style_chatbot_background == 'video'?'checked="checked"':''; ?>>
                                                            <label for="background_type_video">Video</label>
                                                            <div class="lbb-check"></div>
                                                         </div>
                                                      </li>
                                                      
                                                   </ul>


                                                    <div class="lbb-col-5">
                                                        <div class="lbb-form-group lbb-style-color" style="<?php echo $lbb_style_chatbot_background == 'color'?'':'display: none;'; ?>">
                                                            <label for="lbb_max_bg_color">Background Color:</label>
                                                            <input type="text" name="lbb_meta[lbb_chat_background_color]" id="lbb_chat_background_color" value="<?php echo $lbb_chat_background_color; ?>" class="lbb-input-field lbb-color-picker" data-other-options="lbb_inner_chat_background_color" data-default-color="<?php echo $lbb_chat_background_color; ?>" data-css-variable="lbb-chat-background-color" />
                                                        </div>
                                                    </div>

                                                    <div class="lbb-form-group lbb-style-image" style="<?php echo $lbb_style_chatbot_background == 'image'?'':'display: none;'; ?>">
                                                      <label>Upload Image</label>
                                                      <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo (!empty($lbb_chat_background_image)) ? '' : 'lbb-no-img' ?>">
                                                        <div class="lbb-bot-user-image ">
                                                          <a class="lbb-image-upload-button lbb-common-image-upload" href="javascript:void(0)" data-type="lbb_chat_background_image">Upload</a>
                                                          <input type="hidden" id="lbb_chat_background_image" name="lbb_meta[lbb_chat_background_image]" value="<?php echo $lbb_chat_background_image; ?>">
                                                        </div>
                                                        <div class="lbb-image-preview-container">
                                                            <img src="<?php echo $lbb_chat_background_image; ?>" alt="Preview Image" class="lbb-preview-image">
                                                            <div class="lbb-image-actions">
                                                                <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                                <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>

                                                    <div class="lbb-form-group lbb-style-video" style="<?php echo $lbb_style_chatbot_background == 'video'?'':'display: none;'; ?>">
                                                      <label>Upload Video</label>
                                                        <div class="lbb-common-video-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo (!empty($lbb_chat_background_video)) ? '' : 'lbb-no-img' ?>">
                                                            <div class="lbb-bot-user-image ">
                                                              <a class="lbb-image-upload-button lbb-common-video-upload" href="javascript:void(0)" data-class="show_video" data-type="lbb_chat_background_video">Upload</a>
                                                              <input type="hidden" id="lbb_chat_background_video" name="lbb_meta[lbb_chat_background_video]" value="<?php echo $lbb_chat_background_video; ?>">
                                                            </div>
                                                            <div class="lbb-image-preview-container">
                                                                <img src="<?php echo $multimedia_image; ?>" alt="Preview Image" class="lbb-preview-image">
                                                                <div class="lbb-image-actions">
                                                                    <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                                    <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Heading/Subheading</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_size">Heading Font size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="14" data-slider-max="30" data-slider-step="1" data-slider-value="<?php echo $lbb_heading_font_size; ?>" data-value-px="px" data-css-variable="lbb-chat-heading-font-size"></div>
                                                            <input id="lbb_font_size" name="lbb_meta[lbb_heading_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_heading_font_size; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_weight">Heading Font weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_heading_font_weight; ?>" data-value-px="" data-css-variable="lbb-chat-heading-font-weight"></div>
                                                            <input id="lbb_font_weight" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_heading_font_weight]" type="text" value="<?php echo $lbb_heading_font_weight; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_color_picker">Heading Font Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_heading_text_color]" id="lbb_color_picker" value="<?php echo $lbb_heading_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_heading_text_color; ?>" data-css-variable="lbb-chat-heading-text-color" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_size">Subheading Font size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_sub_heading_font_size; ?>" data-value-px="px" data-css-variable="lbb-chat-sub-heading-font-size"></div>
                                                            <input id="lbb_font_size" name="lbb_meta[lbb_sub_heading_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_sub_heading_font_size; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_subheading_font_weight">Subheading Font weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_sub_heading_font_weight; ?>" data-value-px="" data-css-variable="lbb-chat-sub-heading-font-weight"></div>
                                                            <input id="lbb_subheading_font_weight" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_sub_heading_font_weight]" type="text" value="<?php echo $lbb_sub_heading_font_weight; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_color_picker">Subheading Font Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_sub_heading_text_color]" id="lbb_color_picker" value="<?php echo $lbb_sub_heading_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_sub_heading_text_color; ?>" data-css-variable="lbb-chat-sub-heading-text-color" />
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- Question Style -->
                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Question Style</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_size">Font size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_question_font_size; ?>" data-value-px="px" data-css-variable="lbb-question-font-size"></div>
                                                            <input id="lbb_font_size" name="lbb_meta[lbb_question_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_question_font_size; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_subheading_font_weight">Font weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_question_font_weight; ?>" data-value-px="" data-css-variable="lbb-question-font-weight"></div>
                                                            <input id="lbb_subheading_font_weight" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_question_font_weight]" type="text" value="<?php echo $lbb_question_font_weight; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_question_text_color">Text Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_question_text_color]" id="lbb_question_text_color" value="<?php echo $lbb_question_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_question_text_color; ?>" data-css-variable="lbb-question-text-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ques_bg_color">Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_question_background_color]" id="lbb_question_background_color" value="<?php echo $lbb_question_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_question_background_color; ?>" data-css-variable="lbb-question-background-color" />
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ques_bg_color">Input Border Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_question_input_border_color]" id="lbb_question_input_border_color" value="<?php echo $lbb_question_input_border_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_question_input_border_color; ?>" data-css-variable="lbb-question-input-border-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_question_image_height">Image Height:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="50" data-slider-max="400" data-slider-step="1" data-slider-value="<?php echo $lbb_question_image_height; ?>" data-value-px="px" data-css-variable="lbb-question-image-height"></div>
                                                            <input id="lbb_question_image_height" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_question_image_height]" type="text" value="<?php echo $lbb_question_image_height; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Answer / Button Style -->
                                    

                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Answer / Button Style</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ans_bg_color">Background color:</label>
                                                        <input type="text" name="lbb_meta[lbb_ans_bg_color]" id="lbb_ans_bg_color" value="<?php echo $lbb_ans_bg_color; ?>" class="lbb-input-field lbb-color-picker" data-other-options="lbb_inner_ans_bg_color" data-default-color="<?php echo $lbb_ans_bg_color; ?>" data-css-variable="lbb-chat-answer-btn-background-color" />
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ans_border_color">Border Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_ans_border_color]" id="lbb_ans_border_color" value="<?php echo $lbb_ans_border_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_ans_border_color; ?>" data-css-variable="lbb-chat-answer-btn-border-color" />
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ans_text_color">Text Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_ans_text_color]" id="lbb_ans_text_color" value="<?php echo $lbb_ans_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_ans_text_color; ?>" data-css-variable="lbb-chat-answer-btn-text-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_border_radius">Border Radius:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_ans_border_radius; ?>" data-value-px="px" data-css-variable="lbb-chat-answer-btn-border-radius"></div>
                                                            <input id="lbb_ans_border_radius" name="lbb_meta[lbb_ans_border_radius]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_font_size">Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_ans_font_size; ?>" data-value-px="px" data-css-variable="lbb-chat-answer-btn-font-size"></div>
                                                            <input id="lbb_ans_font_size" name="lbb_meta[lbb_ans_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_font_weight">Font Weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_ans_font_weight; ?>" data-value-px="" data-css-variable="lbb-chat-answer-btn-font-weight"></div>
                                                            <input id="lbb_ans_font_weight" name="lbb_meta[lbb_ans_font_weight]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ans_image_height">Answer Image Height:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="50" data-slider-max="200" data-slider-step="1" data-slider-value="<?php echo $lbb_ans_image_height; ?>" data-value-px="px" data-css-variable="lbb-chat-answer-image-height"></div>
                                                            <input id="lbb_ans_image_height" name="lbb_meta[lbb_ans_image_height]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group js-select2-wrapper">
                                                        <label for="image-object-fit">Image object fit:</label>
                                                        <select name="lbb_meta[lbb_ans_image_object_fit]" id="image-object-fit" class="js-select2">
                                                             <option value="cover" <?php echo $lbb_ans_image_object_fit == 'cover'?'selected':''; ?>>Cover</option>
                                                            <option value="contain" <?php echo $lbb_ans_image_object_fit == 'contain'?'selected':''; ?>>Contain</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group js-select2-wrapper">
                                                        <label for="ans-button-row-column">Button per row:</label>
                                                        <select name="lbb_meta[lbb_ans_button_row_column]" id="ans-button-row-column" class="js-select2">
                                                             <option value="100%" <?php echo $lbb_ans_button_row_column == '100%'?'selected':''; ?>>1 column</option>
                                                            <option value="50%" <?php echo $lbb_ans_button_row_column == '50%'?'selected':''; ?>>2 column</option>
                                                            <option value="33.33%" <?php echo $lbb_ans_button_row_column == '33.33%'?'selected':''; ?>>3 column</option>
                                                            <option value="25%" <?php echo $lbb_ans_button_row_column == '25%'?'selected':''; ?>>4 column</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Selected Answer Style</h2>
                                            <div class="lbb-accordion-right-content">
                                                
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_size">Font size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_user_answer_font_size; ?>" data-value-px="px" data-css-variable="lbb-user-answer-font-size"></div>
                                                            <input id="lbb_font_size" name="lbb_meta[lbb_user_answer_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_user_answer_font_size; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_subheading_font_weight">Font weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_user_answer_font_weight; ?>" data-value-px="" data-css-variable="lbb-user-answer-font-weight"></div>
                                                            <input id="lbb_subheading_font_weight" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_user_answer_font_weight]" type="text" value="<?php echo $lbb_user_answer_font_weight; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_user_answer_text_color">Text Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_user_answer_text_color]" id="lbb_user_answer_text_color" value="<?php echo $lbb_user_answer_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_user_answer_text_color; ?>" data-css-variable="lbb-user-answer-text-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ques_bg_color">Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_user_answer_background_color]" id="lbb_user_answer_background_color" value="<?php echo $lbb_user_answer_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_user_answer_background_color; ?>" data-css-variable="lbb-user-answer-background-color" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Alignment</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group js-select2-wrapper">
                                                        <label for="select-chat-alignment">Select Alignment:</label>
                                                        <select name="lbb_meta[lbb_chat_alignment]" id="select-chat-alignment" class="js-select2">
                                                             <option value="left" <?php echo $lbb_chat_alignment == 'left'?'selected':''; ?>>Left</option>
                                                            <option value="right" <?php echo $lbb_chat_alignment == 'right'?'selected':''; ?>>Right</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4 right-spacing-outer" style="<?php echo $lbb_chat_alignment == 'left'? 'display: none;':''; ?>">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_right_spacing">Right Spacing:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="0" data-slider-max="200" data-slider-step="1" data-slider-value="<?php echo $lbb_right_spacing; ?>" data-value-px="px" data-css-variable="lbb-chat-content-right-spacing"></div>
                                                            <input id="lbb_right_spacing" name="lbb_meta[lbb_right_spacing]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4 left-spacing-outer" style="<?php echo $lbb_chat_alignment == 'right'? 'display: none;':''; ?>">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_left_spacing">Left Spacing:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="0" data-slider-max="200" data-slider-step="1" data-slider-value="<?php echo $lbb_left_spacing; ?>" data-value-px="px" data-css-variable="lbb-chat-content-left-spacing"></div>
                                                            <input id="lbb_left_spacing" name="lbb_meta[lbb_left_spacing]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_content_bottom_spacing">Bottom Spacing:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="50" data-slider-max="200" data-slider-step="1" data-slider-value="<?php echo $lbb_bottom_spacing; ?>" data-value-px="px" data-css-variable="lbb-chat-content-bottom-spacing"></div>
                                                            <input id="lbb_bottom_spacing" name="lbb_meta[lbb_bottom_spacing]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Expanded</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_border_color">Border Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_max_border_color]" id="lbb_max_border_color" value="<?php echo $lbb_max_border_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_max_border_color; ?>" data-css-variable="lbb-max-border-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_border_width">Border Width:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo $lbb_max_border_width; ?>" data-value-px="px" data-css-variable="lbb-max-border-width"></div>
                                                            <input id="lbb_max_border_width" name="lbb_meta[lbb_max_border_width]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_border_radius">Border Radius:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo $lbb_max_border_radius; ?>" data-value-px="px" data-css-variable="lbb-max-border-radius"></div>
                                                            <input id="lbb_max_border_radius" name="lbb_meta[lbb_max_border_radius]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_width">Width:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="500" data-slider-step="1" data-slider-value="<?php echo $lbb_max_width; ?>" data-value-px="px" data-css-variable="lbb-max-width"></div>
                                                            <input id="lbb_max_width" name="lbb_meta[lbb_max_width]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_height">Height:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="500" data-slider-step="1" data-slider-value="<?php echo $lbb_max_height; ?>" data-value-px="px" data-css-variable="lbb-max-height"></div>
                                                            <input id="lbb_max_height" name="lbb_meta[lbb_max_height]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_max_bg_color">Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_max_bg_color]" id="lbb_max_bg_color" value="<?php echo $lbb_max_bg_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_max_bg_color; ?>" data-css-variable="lbb-max-bg-color" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item">
                                        <div class="lbb-accordion-header">
                                           <h2>Shadow</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                             <div class="lbb-row">

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_icon_border_radius">Spread Radius:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_shadow_spread_radius; ?>" data-value-px="px" data-css-variable="lbb-shadow-spread-radius"></div>
                                                        <input id="lbb_shadow_spread_radius" name="lbb_meta[lbb_shadow_spread_radius]" class="lbb-slider-input lbb-input-field" type="text">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_icon_border_radius">Blur Radius:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_shadow_blur_radius; ?>" data-value-px="px" data-css-variable="lbb-shadow-blur-radius"></div>
                                                        <input id="lbb_shadow_blur_radius" name="lbb_meta[lbb_shadow_blur_radius]" class="lbb-slider-input lbb-input-field" type="text">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_icon_border_radius">Horizontal Length:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_shadow_horizontal_length; ?>" data-value-px="px" data-css-variable="lbb-shadow-horizontal-length"></div>
                                                        <input id="lbb_shadow_horizontal_length" name="lbb_meta[lbb_shadow_horizontal_length]" class="lbb-slider-input lbb-input-field" type="text">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_content_icon_border_radius">Vertical Length:</label>
                                                    <div class="lbb-slider-outer">
                                                        <div class="lbb-slider" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo $lbb_shadow_vertical_length; ?>" data-value-px="px" data-css-variable="lbb-shadow-vertical-length"></div>
                                                        <input id="lbb_shadow_vertical_length" name="lbb_meta[lbb_shadow_vertical_length]" class="lbb-slider-input lbb-input-field" type="text">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="lbb-col-4">
                                                <div class="lbb-form-group">
                                                    <label for="lbb_chat_heading">Background Color:</label>
                                                    <input type="text" name="lbb_meta[lbb_shadow_background_color]" id="lbb_chat_heading" value="<?php echo $lbb_shadow_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_shadow_background_color; ?>" data-css-variable="lbb-shadow-background-color" />
                                                </div>
                                            </div>
                                             
                                            
                                        </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item back-button-outer">
                                        <div class="lbb-accordion-header">
                                           <h2>Back Button</h2>
                                            <div class="lbb-accordion-right-content">
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_font_size">Font size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_back_button_font_size; ?>" data-value-px="px" data-css-variable="lbb-back-button-font-size"></div>
                                                            <input id="lbb_font_size" name="lbb_meta[lbb_back_button_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_back_button_font_size; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_subheading_font_weight">Font weight:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_back_button_font_weight; ?>" data-value-px="" data-css-variable="lbb-back-button-font-weight"></div>
                                                            <input id="lbb_subheading_font_weight" class="lbb-slider-input lbb-input-field" name="lbb_meta[lbb_back_button_font_weight]" type="text" value="<?php echo $lbb_back_button_font_weight; ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_back_button_text_color">Text Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_back_button_text_color]" id="lbb_back_button_text_color" value="<?php echo $lbb_back_button_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_back_button_text_color; ?>" data-css-variable="lbb-back-button-text-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_ques_bg_color">Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_back_button_background_color]" id="lbb_back_button_background_color" value="<?php echo $lbb_back_button_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_back_button_background_color; ?>" data-css-variable="lbb-back-button-background-color" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="lbb-accordion-item knowledge-button-outer" >
                                        <div class="lbb-accordion-header">
                                           <h2>Knowledge Base</h2>
                                            <span class="lbb-arrow"></span>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">


                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_knowledge_background_color">Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_knowledge_background_color]" id="lbb_knowledge_background_color" value="<?php echo $lbb_knowledge_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_knowledge_background_color; ?>" data-css-variable="lbb-knowledge-background-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_knowledge_active_background_color">Active Background Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_knowledge_active_background_color]" id="lbb_knowledge_active_background_color" value="<?php echo $lbb_knowledge_active_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_knowledge_active_background_color; ?>" data-css-variable="lbb-knowledge-active-background-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_knowledge_active_color">Active Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_knowledge_active_color]" id="lbb_knowledge_active_color" value="<?php echo $lbb_knowledge_active_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_knowledge_active_color; ?>" data-css-variable="lbb-knowledge-active-color" />
                                                    </div>
                                                </div>

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_knowledge_text_color">Text Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_knowledge_text_color]" id="lbb_knowledge_text_color" value="<?php echo $lbb_knowledge_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_knowledge_text_color; ?>" data-css-variable="lbb-knowledge-text-color" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item" >
                                        <div class="lbb-accordion-header">
                                           <h2>Other Styles</h2>
                                            <span class="lbb-arrow"></span>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">


                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_last_chatted_text_color">Last Chatted Color:</label>
                                                        <input type="text" name="lbb_meta[lbb_last_chatted_text_color]" id="lbb_last_chatted_text_color" value="<?php echo $lbb_last_chatted_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_last_chatted_text_color; ?>" data-css-variable="lbb-last-chatted-text-color" />
                                                    </div>
                                                </div>

                                                

                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-accordion-item" >
                                        <div class="lbb-accordion-header">
                                           <h2>Mobile Customizer</h2>
                                            <span class="lbb-arrow"></span>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_heading_font_size">Heading Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_heading_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-heading-font-size"></div>
                                                            <input id="lbb_mobile_heading_font_size" name="lbb_meta[lbb_mobile_heading_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_subheading_font_size">Subheading Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_subheading_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-subheading-font-size"></div>
                                                            <input id="lbb_mobile_subheading_font_size" name="lbb_meta[lbb_mobile_subheading_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_question_font_size">Question Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_question_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-question-font-size"></div>
                                                            <input id="lbb_mobile_question_font_size" name="lbb_meta[lbb_mobile_question_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_answer_font_size">Answer Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_answer_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-answer-font-size"></div>
                                                            <input id="lbb_mobile_answer_font_size" name="lbb_meta[lbb_mobile_answer_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_answer_btn_font_size">Answer Button Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_answer_btn_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-answer-btn-font-size"></div>
                                                            <input id="lbb_mobile_answer_btn_font_size" name="lbb_meta[lbb_mobile_answer_btn_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_answer_image_height">Answer Image Height:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="200" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_answer_image_height; ?>" data-value-px="px" data-css-variable="lbb-mobile-answer-image-height"></div>
                                                            <input id="lbb_mobile_answer_image_height" name="lbb_meta[lbb_mobile_answer_image_height]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_mobile_selected_answer_font_size">Selected Answer Font Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_mobile_selected_answer_font_size; ?>" data-value-px="px" data-css-variable="lbb-mobile-selected-answer-font-size"></div>
                                                            <input id="lbb_mobile_selected_answer_font_size" name="lbb_meta[lbb_mobile_selected_answer_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="lbb-col-4">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_minimized_text_size">Minimized Text Size:</label>
                                                        <div class="lbb-slider-outer">
                                                            <div class="lbb-slider" data-slider-min="1" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo $lbb_minimized_text_size; ?>" data-value-px="px" data-css-variable="lbb-minimized-text-size"></div>
                                                            <input id="lbb_minimized_text_size" name="lbb_meta[lbb_minimized_text_size]" class="lbb-slider-input lbb-input-field" type="text">
                                                        </div>
                                                    </div>
                                                </div> 

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                    </div>

                    <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer">
                        <button type="submit" class="lbb-btn lbb-btn-primary">Save</button>
                        <button  class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
                    </div>
                </form>
                <?php  
                //include( LBB_ABS_URL . 'admin/templates/chat/css-variables.php');
                //include( LBB_ABS_URL . 'admin/templates/chat/chat-preview.php'); ?>
            </div>
        </div>
    </div>
</section>