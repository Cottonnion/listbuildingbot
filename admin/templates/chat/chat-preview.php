<?php
$conversation_messages = '<div class="lbb-message">
	<div class="lbb-avatar-outer lbb-avatar-left-side">
			<img src="'.$lbb_image_upload.'" alt="" class="lbb-lazy-img-loaded">
	</div>
	<div class="chat-user-info-wrapper">
		<div class="chat-user-name">Freshchat</div>
        <div class="lbb-anchors lbb-bot-response lbb-text">Hi! I\'m Mr. Chatbot 😎 Nice to meet you! 👋</div>
	</div>
</div>

<div class="lbb-message lbb-message-user">
	<div class="lbb-avatar-outer">
			<img src="'.$lbb_image_upload.'" alt="" class="lbb-lazy-img-loaded">
	</div>
	<div class="chat-user-info-wrapper">
		<div class="chat-user-name">Freshchat</div>
        <div class="lbb-anchors lbb-bot-response lbb-text">Hi! I\'m Mr. Chatbot 😎 Nice to meet you! 👋</div>
	</div>
</div>

<div class="lbb-message">
	<div class="lbb-avatar-outer lbb-avatar-left-side">
			<img src="'.$lbb_image_upload.'" alt="" class="lbb-lazy-img-loaded">
	</div>
	<div class="chat-user-info-wrapper">
		<div class="chat-user-name">Freshchat</div>
        <div class="lbb-anchors lbb-bot-response lbb-text">What brought you here today?</div>
	</div>
	<div class="lbb-quick-replies-buttons">
        <div class="lbb-single-button" data-conversation-quick-reply-title="Using ChatBot 👉">
            <span>Using ChatBot 👉</span>
        </div>
        <div class="lbb-single-button" data-conversation-quick-reply-title="I have questions 😊">
            <span>I have questions 😊</span>
        </div>
        <div class="lbb-single-button" data-conversation-quick-reply-title="Just browsing 👀">
            <span>Just browsing 👀</span>
        </div>
    </div>
</div>';

if($lbb_style_chatbot_background == 'image') {
    $template_num = 'lbb-template-wrapper lbb-template-image';
}else if($lbb_style_chatbot_background == 'video') {
    $template_num = 'lbb-template-wrapper lbb-template-video';
}else{
    $template_num = 'lbb-template-wrapper lbb-template-color';
}

?>
<div class="lbb-chat-start <?php echo ($how_to_show != 'inline') ? '' : 'lbb-chattype-inline'; ?> <?php echo $how_to_show ?> <?php echo $template_num; ?>" id="lbb-chat-main-wrapper" data-whentoshow="<?php echo $when_to_show ?>" data-time="<?php echo $time_input_value ?>" data-page_scroll="<?php echo $page_scroll_value ?>" style="<?php echo $_REQUEST['action'] == 'create' ? 'display:none;' : ''; ?>">
	
	<?php 

	$settings_messages = get_option('lbb_message_data', array());
	$atts['reset_chat'] = !empty($settings_messages['reset_chat']) ? $settings_messages['reset_chat'] : 'Reset Chat';
    $atts['restart_chat'] = !empty($settings_messages['restart_chat']) ? $settings_messages['restart_chat'] : 'Restart Chat';
	//$atts = array('reset_chat' => 'Reset', 'restart_chat' => 'Restart');

	lbb_chat_main_common($chat_mode, $how_to_show, $lbb_image_upload, $lbb_admin_name, $lbb_chatbot_description, $atts, $conversation_messages, $lbb_chat_background_video); ?>

	
	<?php lbb_chat_btn_common($chatflow_id,$how_to_show, $when_to_show, true); ?>
</div>