
var lbb_last_chat = {};
var lbb_prev_message = 0;
var lbb_first_question = 0;
var lbb_first_opened = 0;
/*
const inactivityThreshold = 10000;

let inactivityTimer;

function startInactivityTimer() {
	console.log("Starting inactivity timer");
  inactivityTimer = setTimeout(closeChat, inactivityThreshold);
}

// Function to reset the inactivity timer
function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  startInactivityTimer();
}

// Function to close the chat
function closeChat() {
  console.log("Chat closed due to inactivity");
}

function onMessageReceived() {
  resetInactivityTimer();
}*/

let lbb_inactive_timer;
let lbb_admin_timer;
var isAdminBusy = false;
var isUserBusy = false;

let mediaRecorder;
let audioChunks = [];
let startTime; // Variable to store the start time of recording
let timerInterval; // Variable to store the timer interval

function lbb_updateTimer() {
	const elapsedTime = Math.floor((Date.now() - startTime) / 1000); // Calculate elapsed time in seconds
	const minutes = Math.floor(elapsedTime / 60);
	const seconds = elapsedTime % 60;
	const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
	jQuery('#recordingTimer').text(formattedTime);
}

function lbb_init_audio() {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function(stream) {
            mediaRecorder = new MediaRecorder(stream);

            // Event handler when data is available
            mediaRecorder.ondataavailable = function(event) {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            };

			mediaRecorder.onstart = function() {
                startTime = Date.now();
                timerInterval = setInterval(lbb_updateTimer, 1000);
            };

            // Event handler when recording stops
            mediaRecorder.onstop = function() {
				clearInterval(timerInterval); 
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);

                // Display the recorded audio
                //jQuery('#audioPlayer').attr('src', audioUrl);
                jQuery('#audioPlayer').css('display', 'block');
            };

            jQuery('#startRecording').on('click', function() {
                audioChunks = [];
                mediaRecorder.start();
                jQuery('#startRecording').hide();
                jQuery('.lbb-recording-contorl-buttons-container').show();
            });

			jQuery('#cancelRecording').on('click', function() {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
					jQuery('#startRecording').show();
                	jQuery('.lbb-recording-contorl-buttons-container').hide();
					jQuery('#recordingTimer').html('00:00');
                }
            });

            jQuery('#stopRecording').on('click', function() {
                mediaRecorder.stop();
                //jQuery('#startRecording').show();
                jQuery('.lbb-recording-contorl-buttons-container').hide();

				setTimeout(function() {

					// Convert audioChunks to a Blob and set it as the input value
					const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
					const audioDataInput = jQuery('#audioData');
					audioDataInput.val(audioBlob);

					const formData = new FormData();
					formData.append('audio', audioBlob);

					jQuery.ajax({
						type: 'POST',
						url: listbuildingbot.ajax_url+'?action=lbb_handle_audio_upload',
						data: formData,
						processData: false,
						contentType: false,
						success: function(response) {
						
							var response = JSON.parse(response);
							const attachment = response.audio;
							var attachment_type = response.type;

							message_meta = {};
							message_meta['audio'] = attachment;
							message_meta['attachment_type'] = attachment_type;
							message_meta['name'] = name;
						
							// Display the uploaded image in the chat interface
							//const imgElement = `<img src="`+imageUrl+`" alt="Uploaded Image" />`;
							var html = response.download_box;
							//jQuery('#chat-messages').append(imgElement);

							var current_action_id = lbb_last_chat.action_id;
							var next_action_id = lbb_last_chat.next_question_id;
							var conversation_id = getConversationID();
							type = lbb_last_chat.type;
							jQuery('#lbb-audio-question-form').remove();
							lbbHandleUserResponse(html,false);
							scrollToBottom();
							lbbSubmitUserReply({
								message : '',
								message_meta : message_meta,
								conversation_id : conversation_id,
								current_action_id: current_action_id,
								next_action_id : next_action_id,
								chatflow_id : jQuery('#lbb_chatflow_id').val(),
								current_url : current_url
							});
						}
					});
				}, 1000);

            });
        })
        .catch(function(error) {
            console.error('Error accessing microphone:', error);
        });
}

function lbb_chat_activity_timer(){	
	
	if(lbb_livechat_options == 'ajax_based'){
		lbb_inactive_timer = setInterval(function() {

			var con_id = getConversationID();
			jQuery.ajax({
				url: listbuildingbot.ajax_url,
				type: 'POST',
				async: true,
				data: {
					action: 'lbb_get_last_message_from',
					conversation_id : con_id,
					
				},
				success: function(response) {
					if(response) {
						userT = response;
	
						if(userT == 'admin'){
							var con_id = getConversationID();
							jQuery.ajax({
								url: listbuildingbot.ajax_url,
								type: 'POST',
								data: {
									action: 'lbb_check_last_message',
									conversation_id : con_id,
									type : userT,
									idle_time : userTimer
								},
								success: function(response) {
									if(response == 'idle') {
										if(!isAdminBusy){
											console.log('user Idle');
											lbb_reset_timer();
											lbb_conversation_end();
											jQuery('.lbb-typing').addClass('lbb-user-input-hide');	
										}
									}
								}
							});
						}else if(userT == 'user'){
							var con_id = getConversationID();
							jQuery.ajax({
								url: listbuildingbot.ajax_url,
								type: 'POST',
								data: {
									action: 'lbb_check_last_message',
									conversation_id : con_id,
									type : userT,
									idle_time : adminTimer
								},
								success: function(response) {
									if(response == 'idle') {
										
										console.log('Admin Idle');
										if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-admin-busy')){
											lbbCustomReponse(adminBusyMessage,'lbb-conversation-admin-busy');
										}
										lbb_reset_timer();
										isAdminBusy = true;
										//lbb_conversation_end();
	
									}
								}
							});
						}
					}
				}
			});
		}, 30000);
	}else{

		lbb_inactive_timer = setInterval(function() {
		
			var userT = lbb_get_last_message_from_user_firebase();
	
			//console.log('Active timer....');
			console.log(userT);
	
			if(userT == 'admin'){
				var isIdle = lbb_check_last_message_from_firebase('user',userTimer);
				if(isIdle){
					if(!isAdminBusy){
						console.log('user Idle');
						lbb_reset_timer();
						lbb_conversation_end();
						jQuery('.lbb-typing').addClass('lbb-user-input-hide');	
					}
				}else{
					console.log('No Idle');
				}
			}else if(userT == 'user'){
				var isIdle = lbb_check_last_message_from_firebase('admin',adminTimer);
				if(isIdle){
					console.log('Admin Idle');
					if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-admin-busy')){
					lbbCustomReponse(adminBusyMessage,'lbb-conversation-admin-busy');
					}
					lbb_reset_timer();
					isAdminBusy = true;
					//lbb_conversation_end();
				}else{
					console.log('No Idle');
				}
			}
	
		}, 60000);

	}
  
}


function lbb_chat_activity_timer_ajax(){

	if(lbb_livechat_options == 'firebase_based'){
		return false;
	}

	lbb_inactive_timer = setInterval(function() {

		var con_id = getConversationID();
		jQuery.ajax({
			url: listbuildingbot.ajax_url,
			type: 'POST',
			async: true,
			data: {
				action: 'lbb_get_last_message_from',
				conversation_id : con_id,
				
			},
			success: function(response) {
				if(response) {
					userT = response;

					if(userT == 'admin'){
						var con_id = getConversationID();
						jQuery.ajax({
							url: listbuildingbot.ajax_url,
							type: 'POST',
							data: {
								action: 'lbb_check_last_message',
								conversation_id : con_id,
								type : userT,
								idle_time : userTimer
							},
							success: function(response) {
								if(response == 'idle') {
									if(!isAdminBusy){
										console.log('user Idle');
										lbb_reset_timer();
										lbb_conversation_end();
										jQuery('.lbb-typing').addClass('lbb-user-input-hide');	
									}
								}
							}
						});
					}else if(userT == 'admin'){
						var con_id = getConversationID();
						jQuery.ajax({
							url: listbuildingbot.ajax_url,
							type: 'POST',
							data: {
								action: 'lbb_check_last_message',
								conversation_id : con_id,
								type : userT,
								idle_time : adminTimer
							},
							success: function(response) {
								if(response == 'idle') {
									
									console.log('Admin Idle');
									if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-admin-busy')){
										lbbCustomReponse(adminBusyMessage,'lbb-conversation-admin-busy');
									}
									lbb_reset_timer();
									isAdminBusy = true;
									//lbb_conversation_end();

								}
							}
						});
					}
				}
			}
		});
		
		/*var userT = lbb_get_last_message_from_user_firebase();

		//console.log('Active timer....');
		console.log(userT);

		if(userT == 'admin'){
			var isIdle = lbb_check_last_message_from_firebase('user',userTimer);
			if(isIdle){
				if(!isAdminBusy){
					console.log('user Idle');
					lbb_reset_timer();
					lbb_conversation_end();
					jQuery('.lbb-typing').addClass('lbb-user-input-hide');	
				}
			}else{
				console.log('No Idle');
			}
		}else if(userT == 'user'){
			var isIdle = lbb_check_last_message_from_firebase('admin',adminTimer);
			if(isIdle){
				console.log('Admin Idle');
				if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-admin-busy')){
				lbbCustomReponse(adminBusyMessage,'lbb-conversation-admin-busy');
				}
				lbb_reset_timer();
				isAdminBusy = true;
				//lbb_conversation_end();
			}else{
				console.log('No Idle');
			}
		}*/

	}, 60000);
  
}


function lbb_check_last_message(conversation_id,type, idle_time){
	
	var con_id = getConversationID();
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'lbb_check_last_message',
            conversation_id : con_id,
			type : type,
			idle_time : idle_time
        },
        success: function(response) {
			if(response) {

			}
		}
	});
}

function lbb_conversation_end(){

	var con_id = getConversationID();
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_end_conversation',
            conversation_id : con_id
        },
        success: function(response) {
			if(response.success) {
				if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-end-msg')){

					var start = `<div class="start-new-conversation-div">
						<a href="javascript:void(0)" class="lbb-start-new-conversation">`+lbb_start_button+`</a>
						</div>
						`;
					lbbCustomReponse(response.data.message, 'lbb-conversation-end-msg',start);
				}
			}else{

			}
		}
	});
}

function lbb_reset_timer() {
	clearInterval(lbb_inactive_timer);
}

function lbbHandleDynamicMessage(messages){
	if(messages.length > 0){
		jQuery.each(messages, function(index, message) {
			message = message.replace(/\\/g, '');
			lbbCustomReponse(message, 'lbb-admin-response');
		});
	}
}

function lbbHandleExtraMessages(messages,cls = ''){
	if(messages.length > 0){
		jQuery.each(messages, function(index, message) {
			message = message.replace(/\\/g, '');
			lbbCustomReponse(message, 'lbb-admin-response '+cls);
		});
	}
}

function lbbhandleAdminFirstMessage(message,action_data){
	message = message.replace('{{image}}', adminImg);
	message = message.replace('{{name}}', lbbAdminName);
	message = message.replace('{{prev_id}}', lbb_prev_message);

	if(action_data.field_html != undefined){
		//message = message.replace('{{inline_field}}', action_data.field_html);
		message = message.replace('{{inline_field}}', '');
	}else{
		message = message.replace('{{inline_field}}', '');
	}

	if(action_data.time_format != undefined){
		message = message.replace('{{time}}', action_data.time_format);
	}else{
		message = message.replace('{{time}}', getCurrentTime());
	}
	message = message.replace('{{chatflow_id}}', chatflow_id);

	jQuery('.lbb-chat-first-question-inner').append(message);
	jQuery('.lbb-chat-first-question-main').show();
}	

function lbbHandleAdminResponse(message,action_data = []){

	jQuery('.Messages_list .lbb-agent-typing').remove();
	var messageTemplate = jQuery('#lbb-agent-response').html();

	var hide_auth = false;
	if(jQuery('.Messages_list .lbb-message').last().hasClass('lbb-admin-response')){
		jQuery('.Messages_list .lbb-message').last().addClass('lbb-field-type-'+action_data.type);
		hide_auth = true;
	}

	message = message.replace('{{image}}', adminImg);
	message = message.replace('{{name}}', lbbAdminName);
	message = message.replace('{{prev_id}}', lbb_prev_message);
	
	if(action_data.field_html != undefined){
		//message = message.replace('{{inline_field}}', action_data.field_html);
		message = message.replace('{{inline_field}}', '');
	}else{
		message = message.replace('{{inline_field}}', '');
	}

	if(action_data.time_format != undefined){
		message = message.replace('{{time}}', action_data.time_format);
	}else{
		message = message.replace('{{time}}', getCurrentTime());
	}
	message = message.replace('{{chatflow_id}}', chatflow_id);

	/*if(action_data.field_html != undefined){
		message = message + action_data.field_html;
	}*/

	jQuery('.Messages_list').append(message);

	if(action_data.field_html != undefined){
		jQuery('.Messages_list .lbb-message').last().find('.lbb-text').append(action_data.field_html);
		if(jQuery(".lbb-datepicker").length > 0){
			console.log(action_data);
			/*jQuery("#lbb-datepicker").flatpickr({
				dateFormat: action_data.date_format,
			});*/

			var lbbdate = {
				'Y/m/d' : 'yy/mm/dd',
				'd/m/Y' : 'dd/mm/yy',
				'm/d/y' : 'mm/dd/yy',
			}

			jQuery( ".lbb-datepicker" ).datepicker({
				dateFormat: lbbdate[action_data.date_format],
				firstDay: 1,
				beforeShow: function(input, inst) {
					inst.dpDiv.removeClass('lbb-datepicker-popup');
					inst.dpDiv.addClass('lbb-datepicker-popup');
				}
			});

			jQuery(".lbb-datepicker").on("change",function(){
				var $me = jQuery(this);
				$selected = $me.val();
				jQuery(".lbb-datepicker").val($selected);
			});
			
		}
	}

	if(action_data.type == 'audio'){
		lbb_init_audio();
	}

	if((jQuery(".lbb-field-type-text input[type='text']").length > 0 || jQuery(".lbb-field-type-text textarea").length > 0) && lbb_emoji_enable == 'yes'){
		try {
			jQuery(".lbb-field-type-text input[type='text'], .lbb-field-type-text textarea").emojioneArea({
				search: false,
				tones: false,
				filters: {
					smileys_people: {
						icon: "yum",
						title: "",
						emoji: lbb_emoji
					},
					recent : false,
					objects: false,
					symbols: false,
					flags : false,
					animals_nature : false,
					food_drink : false,
					activity : false,
					travel_places : false,
				}
			});
		} catch (error) {
				
		}
	}

	if(hide_auth){
		jQuery('.Messages_list .lbb-message').last().addClass('hide-author');
	}

	return message;
}

function lbbHandleEndConversationResponse(message){
	
	var messageTemplate = jQuery('#lbb-agent-response').html();

	messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{skip_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', message);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-admin-response').addClass('lbb-agent-conversation-ended');

	jQuery('.Messages_list').append(messageTemplateObject.html());
}

function lbbValidateReponse(message){

	jQuery('.Messages_list .lbb-agent-typing').remove();

	var messageTemplate = jQuery('#lbb-agent-response').html();

	messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{skip_buttons}}', '');
	
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	messageTemplate = messageTemplate.replace('{{pdf_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', message);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-admin-response').addClass('lbb-agent-error');

	jQuery('.Messages_list').append(messageTemplateObject.html());
	scrollToBottom();
}

function lbbCustomReponse(message,classname,button = ''){

	jQuery('.Messages_list .lbb-agent-typing').remove();

	var hide_auth = false;
	if(jQuery('.Messages_list .lbb-message').last().hasClass('lbb-admin-response')){
		jQuery('.Messages_list .lbb-message').last().addClass('lbb-field-type-extramessage');
		hide_auth = true;
	}
	
	var messageTemplate = jQuery('#lbb-agent-response').html();


	if(button != ''){
		messageTemplate = messageTemplate.replace('{{reply_buttons}}', button);
	}else{
		messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	}

	//messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{pdf_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{skip_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', message);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);
	messageTemplate = messageTemplate.replace('{{time}}', getCurrentTime());

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-admin-response').addClass(classname);

	jQuery('.Messages_list').append(messageTemplateObject.html());
	scrollToBottom();

	if(hide_auth){
		jQuery('.Messages_list .lbb-message').last().addClass('hide-author');
	}
}

function slgHandleTyping(){
	var messageTemplate = jQuery('#lbb-agent-response').html();

	var typingM = `<div class="lbb-typing-animation"> <div class="lbb-dot-container"> <div class="lbb-dot"></div> <div class="lbb-dot"></div> <div class="lbb-dot"></div> </div> </div>`;

	messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{skip_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{pdf_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', typingM);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);
	messageTemplate = messageTemplate.replace('{{time}}', getCurrentTime());

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-admin-response').addClass('lbb-agent-typing');

	setTimeout(() => {
		jQuery('.Messages_list').append(messageTemplateObject.html());
		scrollToBottom();
	}, 1000);
	
}



function lbbHandleUserResponse(message, server,image = '',messageObj = [], classn = '') {

	if(image != ''){
		var ImgEl = '<div class="quick-reply-useranswer-image"><div class="quick-reply-spn-text">'+message+'</div>'+'<div class="quick-reply-spn-img"><img src="'+image+'" /></div></div>';
		var imageClass = 'lbb-has-image';
	}else{
		var ImgEl = message;
		var imageClass = 'lbb-has-no-image';
	}

	imageClass = imageClass + ' ' + classn;

	if(!server){
		var messageTemplate = jQuery('#lbb-user-response').html();
		messageTemplate = messageTemplate.replace('{{message}}', ImgEl);
		messageTemplate = messageTemplate.replace('{{name}}', lbbUserName);
	}else{
		var messageTemplate = message;
		messageTemplate = messageTemplate.replace('{{name}}', lbbUserName);
	}
	
	messageTemplate = messageTemplate.replace('{{image}}', userImg);
	
	if(messageObj.time_format != undefined){
		messageTemplate = messageTemplate.replace('{{time}}', messageObj.time_format);
	}else{
		messageTemplate = messageTemplate.replace('{{time}}', getCurrentTime());
	}

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-message-user').addClass(imageClass);
	jQuery('.Messages_list').append(messageTemplateObject.html());

	return messageTemplateObject.html();
}

function scrollToBottom() {
    var chatBox = document.getElementById("chat-messages");
    chatBox.scrollTop = chatBox.scrollHeight;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function lbbDeleteCookie(cookieName) {
	// Set the expiration date in the past
	document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

function lbbcloseconversation(conversation_id){

	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_end_conversation',
            conversation_id : conversation_id
        },
        success: function(response) {

		}
	});

}

function lbbStartConversation(conversation_id,isRestart = false) {

	if(chat_mode == 'live'){
		lbb_send_event();
		jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
	}else{

		if(jQuery('#lbb_allow_bot_to_trained').val() > 0){
			jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
		}

		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline') && jQuery('#lbb_allow_bot_to_trained').val() < 1){
			jQuery('.lbb-typing').addClass('lbb-user-input-hide');
		}
		
	}

	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}
	jQuery('#lbb-app').addClass('lbb-inside-loader-active');
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_chatbot_start_conversation',
			timezone : Intl.DateTimeFormat().resolvedOptions().timeZone,
            chatflow_id : jQuery('#lbb_chatflow_id').val(),
            conversation_cookie_val : conversation_cookie_val,
			lbb_embed : jQuery('#lbb_embed').val(),
			lbb_current_page:listbuildingbot.lbb_current_page
        },
        success: function(response) {
        	jQuery('#lbb-app').removeClass('lbb-inside-loader-active');
			jQuery('.lbb-reset-restart-conversation').css('pointer-events', 'auto');
			if(chat_mode == 'live'){
				lbb_chat_activity_timer();
			}

			if(chat_mode == 'bot' && jQuery('#lbb_allow_bot_to_trained').val() > 0){
				jQuery('#lbb-app').addClass('lbb-allow-bot-to-trained');
			}

			var conversation_id = getConversationID();
            if (response.success) {

				if(response.data.block != undefined){
					lbb_last_chat = response.data.block;
				}

				var messages = response.data.conversations;
				var conversation_id = response.data.conversation_id;
				var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
				var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
				if (!getCookie(conversation_cookie_key) && conversation_id > 0){
					setCookie(conversation_cookie_key, conversation_id, 365);
				}

				var conversation_tmp_cookie_key = 'conversation_tmp_data_'+lbb_chatflow_id;
				if (response.data.conversation_tmp != undefined && response.data.conversation_tmp != ''){
					var cooktmp = cooktmp = response.data.conversation_tmp.replace(/\\/g, '');
					setCookie(conversation_tmp_cookie_key, encodeURIComponent(cooktmp), 365);
				}

				if(messages.length > 0){
					jQuery.each(messages, function(index, message) {

						if(message.is_bot_response == 1){

							if(message.extra_messages != undefined){
								lbbHandleExtraMessages(message.extra_messages);
							}

							message.text = message.text.replace(/\\/g, '');
							// show all bot messages
							console.log(message.text);
							if(message.type == 'welcome' && message.title == ''){
							}else{
								lbbHandleAdminResponse(message.text, message);
							}
							
							if(message.dynamic_messages != undefined){
								lbbHandleDynamicMessage(message.dynamic_messages);
							}
						}else if(message.agent_id > 0){	
							var img = '';
							if(message.image != undefined && message.image != ''){
								img = message.image;
							}
							message.text = message.text.replace(/\\/g, '');
							lbbHandleAdminResponse(message.text,message);
						}else{
							var img = '';
							if(message.image != undefined && message.image != ''){
								img = message.image;
							}

							
							message.text = message.text.replace(/\\/g, '');

							lbbHandleUserResponse(message.text, true,img,message);
						}
					});
				}

				if(response.data.end_status == 1){
					if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-end-msg')){
						var start = `<div class="start-new-conversation-div">
						<a href="javascript:void(0)" class="lbb-start-new-conversation">`+lbb_start_button+`</a>
						</div>
						`;
						lbbCustomReponse(userTimeoutMessage,'lbb-conversation-end-msg',start);
					}
					lbb_reset_timer();
					jQuery('.lbb-typing').addClass('lbb-user-input-hide');
				}

				if(chat_mode == 'live'){
					scrollToBottom();
					return false;
				}

				if(response.data.action_required == false){
					if(response.data.end_status < 1){



						jQuery.ajax({
							url: listbuildingbot.ajax_url,
							type: 'POST',
							data: {
								action: 'sbl_chatbot_action',
								chatflow_id : chatflow_id,
								conversation_id : conversation_id
							},
							success: function(response) {
				
								if (response.success) {
									
									jQuery('.lbb-back-conversation').hide();
									
									

									/*if(response.data.redirect){
										window.open(response.data.url, "_blank");
										return false;
									}*/

									var block = response.data.block;
									if(lbb_minimized_type_option  == 'show_first_question' || lbb_minimized_type_option == 'show_first_welcome'){
										
										var fptimer = 3000;
										if(lbb_first_popout_options == 'yes' && lbb_first_popout_how_many_seconds != ''){
											fptimer = parseInt(lbb_first_popout_how_many_seconds) * 1000;
										}

										setTimeout(function() {
											
											jQuery('.lbb-chat-first-question-inner').html('');

											if(response.data.welcome_block.title != '' && response.data.welcome_block.title != undefined){
												lbbhandleAdminFirstMessage(response.data.welcome_block.text,response.data.welcome_block);
											}
											if(lbb_minimized_type_option == 'show_first_question'){
												lbbhandleAdminFirstMessage(block.text,block);
											}

											if(lbb_first_disappear_options == 'yes' && lbb_first_disappear_how_many_seconds != ''){
												setTimeout(function() {
													jQuery('.lbb-chat-first-question-main').hide();
												},parseInt(lbb_first_disappear_how_many_seconds)*1000);
											}

										},fptimer);
									}

									var block = response.data.block;
									var min = 1;
									var max = 1000000;

									var randomValue = Math.floor(Math.random() * (max - min + 1)) + min;

									if(block.extra_messages != undefined){
										lbbHandleExtraMessages(block.extra_messages, 'question-extra-messages-'+randomValue);
									}

									lbbHandleAdminResponse(block.text,block);

									if(block.dynamic_messages != undefined){
										lbbHandleDynamicMessage(block.dynamic_messages);
									}

									if(lbb_is_fresh < 1){
										var target = document.querySelector(".lbb-admin-response:first-child");
										if (target) {
											var scrollTop = target.offsetTop - target.parentElement.offsetTop;
											target.parentElement.scrollTop = scrollTop - 10;
										}
									}else{
										var target = document.querySelector(".question-extra-messages-"+randomValue);
										if (target) {
											var scrollTop = target.offsetTop - target.parentElement.offsetTop;
											target.parentElement.scrollTop = scrollTop - 10;
										}else{
											var target = document.querySelector(".lbb-admin-response:last-child");
											if (target) {
												var scrollTop = target.offsetTop - target.parentElement.offsetTop;
												target.parentElement.scrollTop = scrollTop - 10;
											}else{
												scrollToBottom();
											}
										}
									}

									lbb_last_chat = block;
									if (jQuery.inArray(block.type, ['name', 'text', 'email', 'phone', 'country','url', 'date', 'outcome']) !== -1) {
										lbbDisableUserInput(false, block.type);
									}else{
										lbbDisableUserInput(true);
									}

								} else {
									// The AJAX request failed
									console.error(response.data.message);
								}
							},
							error: function(error) {
								console.error('AJAX error:', error);
							}
						});
					}
				}else{
					
					if (jQuery.inArray(response.data.block.type, ['name', 'text', 'email', 'phone', 'country','url', 'date', 'outcome']) !== -1) {
						lbbDisableUserInput(false,response.data.block.type);
					}else{
						lbbDisableUserInput(true);
					}
				}
            } else {
                
                console.error(response.data.message);
            }
        },
        error: function(error) {
        	jQuery('#lbb-app').removeClass('lbb-inside-loader-active');
            console.error('AJAX error:', error);
        }
    });
}

function getConversationID(){
	
	var chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_key = 'lbbcf_'+chatflow_id+'_conversation_id';
	var conversation_id = getCookie(conversation_key);
	if(conversation_id && conversation_id != undefined){
		return conversation_id;
	}else{
		return 0;
	}	
}

function lbbDisableUserInput(status, type = '') {

	if(status  == true){
		//jQuery('.lbb-typing').addClass('lbb-user-input-hide');
	}else{
		if(type == 'text' || type == 'name' || type == 'email' || type == 'date' || type == 'url' || type == 'phone' || type == 'country' || type == 'outcome'){
		//jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
		}else{
			jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
		}
	}

	//var fieldPlaceholder = {'text': 'Enter your message', 'name' : 'Enter your name'};

	/*if(fieldPlaceholder['lbb_input_placeholder_'+type] != undefined){
		var text = fieldPlaceholder['lbb_input_placeholder_'+type]
	}else{
		var text = 'Enter your message';
	}

	jQuery('.lbb_input_message').attr('placeholder',text);
	jQuery('.lbb_inline_input').attr('placeholder',text);*/
	
	//jQuery('.lbb_input_message').prop('disabled', status);
	//jQuery('.lbb-submit-message').prop('disabled', status);

}

function lbbSubmitUserReply(data){

	data['action'] = 'sbl_chatbot_submit_reply';
	var current_action_id = data['current_action_id'];
	var next_action_id = data['next_action_id'];
	var btnText = data['message'];
	
	jQuery('.lbb-inline-input-field').remove();
	slgHandleTyping();
	jQuery('.skip-button-div').remove();
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: data,
		success: function(response) {

			if(data.forceType != undefined && data.forceType == 'trainedBot' && response.data.warning == undefined){
				setTimeout(() => {
					jQuery('.lbb-kb-faq').removeClass('lbb-kb-faq-processing');
					jQuery('.Messages_list .lbb-agent-typing').remove();
					data = response.data;
					if(data.status == 'ok'){
						if(data.message != undefined){
							var string = data.message;
						}else if(data.object != undefined){
							var string = data.object;
						}
						
						string.replace(/\\/g, '');
						lbbCustomReponse(string);
						if(data.limit == undefined){
							data.limit = 0;
						}
						document.cookie = 'lbb_'+jQuery('#lbb_chatflow_id').val()+'_ai_response_limit='+data.limit+'; path=/';
						
					}else if(data.status == 'error'){

						if(data.message != undefined){
							var string = data.message;
						}else if(data.object != undefined){
							var string = data.object;
						}
						string.replace(/\\/g, '');
						lbbCustomReponse(string);
						if(data.limit == undefined){
							data.limit = 0;
						}
						document.cookie = 'lbb_'+jQuery('#lbb_chatflow_id').val()+'_ai_response_limit='+data.limit+'; path=/';

					}

				}, 1500);
			}else{

				if(response.data.error != undefined && response.data.error == 1){
					setTimeout(() => {
						lbbValidateReponse(response.data.message);
						lbbHandleAdminResponse(lbb_last_chat.text,lbb_last_chat);
						scrollToBottom();
					}, 1500);
				}else if(response.data.warning != undefined && response.data.warning == 1){
					lbbCustomReponse(response.data.message,'lbb-live-chat');
					lbb_activate_chat_live_mode();
					jQuery('.lbb-restart-conversation').hide();
				}else{
					setTimeout(() => {
						//lbb_prev_message = current_action_id;
						var btnmessageType = data['messageType'];
						if(btnmessageType != 'back'){
							jQuery('.lbb-admin-response.lbb-last-back').removeClass('lbb-last-back');
						}

						var conversation_cookie_key = 'lbbcf_'+jQuery('#lbb_chatflow_id').val()+'_conversation_id';
						if (!getCookie(conversation_cookie_key) && response.data.conversation_id != undefined && response.data.conversation_id != ''){
							setCookie(conversation_cookie_key, response.data.conversation_id, 365);
						}

						lbbCheckAction(current_action_id,next_action_id, btnmessageType);
					}, 1500);
				}
			}	
		},
		error: function(error) {
			console.error('AJAX error:', error);
		}
	});
}

function lbbCheckScrollPercentage(percentage) {
	jQuery(window).scroll(function () {
		var windowHeight = jQuery(window).height();
		var scrollHeight = jQuery(document).height();
		var scrollPosition = jQuery(window).scrollTop();
		
		// Calculate the scroll position for the specified percentage
		var targetScrollPosition = (scrollHeight - windowHeight) * (percentage / 100);
		
		if (scrollPosition >= targetScrollPosition) {
			// User has scrolled to the specified percentage of the page
			
			if(jQuery('#lbb-chat-main-wrapper').hasClass('minimized') && jQuery('#lbb-chat-main-wrapper').attr('data-whentoshow') == 'upon_scroll'){
				var scroll = jQuery('#lbb-chat-main-wrapper').attr('data-page_scroll');
				if(!jQuery('#lbb-chat-main-wrapper').hasClass('lbb-scroll-opened')){
					lbbCheckScrollPercentage(scroll);
					jQuery('#lbb-chat-main-wrapper').addClass('lbb-scroll-opened');

					jQuery('.lbb-notification-co').removeClass('lbb-hide');
					
					jQuery('.lbb-notification-co').css('display', 'block');
					jQuery('.lbb-chat-icon-inner').removeClass('lbb-hide');
					jQuery('.lbb-widget-video').removeClass('lbb-hide');

				}
			}
			
			// You can add your desired actions here
		}
	});
}

function lbbCheckAction(current_action_id,next_action_id,user_reply,diff_chat_id = 0){

	var newtmp_chat_id = chatflow_id;

	if(diff_chat_id > 0){
		newtmp_chat_id = diff_chat_id;
	}
	
	var messageType = '';

	if(user_reply == 'back'){
		messageType = 'back';
		user_reply = lbb_back_question;
	}

	var conversation_id = getConversationID();
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_chatbot_action',
            current_action_id : current_action_id,
            next_action_id : next_action_id,
            userReply:user_reply,
			messageType : messageType,
			conversation_id : conversation_id,
			chatflow_id : newtmp_chat_id,
			current_url : current_url
        },
        success: function(response) {

            if (response.success) {

				lbb_prev_message = current_action_id;

				if(lbb_first_question == 0){
					lbb_first_question = lbb_prev_message;
					jQuery('.lbb-back-conversation').show();
				}else{
					if(next_action_id != lbb_first_question){
						jQuery('.lbb-back-conversation').show();
					}
				}



				if(response.data.redirect){
					lbbCustomReponse(response.data.message);
					jQuery('#lbbRedirectLink').attr("href", response.data.url);
					//setTimeout(function() {
						document.getElementById("lbbRedirectLink").click();
					//}, 3000);
					return false;
				}

                var block = response.data.block;
				var min = 1;
				var max = 1000000;

				var randomValue = Math.floor(Math.random() * (max - min + 1)) + min;
				if(block.extra_messages != undefined){
					lbbHandleExtraMessages(block.extra_messages,'question-extra-messages-'+randomValue);
				}

                lbbHandleAdminResponse(block.text,block);

				if(block.dynamic_messages != undefined){
					lbbHandleDynamicMessage(block.dynamic_messages);
				}

				var end_messsage = response.data.end;
				if(end_messsage != undefined && end_messsage != '') {
					lbbHandleEndConversationResponse(end_messsage);
				}

				var target = document.querySelector(".question-extra-messages-"+randomValue);
				if (target) {
					var scrollTop = target.offsetTop - target.parentElement.offsetTop;
					target.parentElement.scrollTop = scrollTop - 10;
				}else{
					var target = document.querySelector(".lbb-admin-response:last-child");
					if (target) {
						var scrollTop = target.offsetTop - target.parentElement.offsetTop;
						target.parentElement.scrollTop = scrollTop - 10;
					}else{
						scrollToBottom();
					}
				}

				lbb_last_chat = block;
				if (jQuery.inArray(block.type, ['name', 'text', 'email', 'phone', 'country','url', 'date','outcome']) !== -1) {
					lbbDisableUserInput(false,block.type);
				}else{
					lbbDisableUserInput(true);
				}

            } else {
                // The AJAX request failed
                console.error(response.data.message);
            }
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
    });
}

var lbb_sent_mail = false;

function lbbSendMail(){

	var conversation_cookie_key = 'lbbcf_'+jQuery('#lbb_chatflow_id').val()+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	if(!lbb_sent_mail){

		lbb_sent_mail = true;
		jQuery.ajax({
			url: listbuildingbot.ajax_url,
			type: 'POST',
			data: {
				action: 'lbb_send_bot_mail',
				chatflow_id : jQuery('#lbb_chatflow_id').val(),
				conversation_cookie_val : conversation_cookie_val,
				current_url : current_url
			},
			success: function(response) {

			}
		});
	}
}

function startTrainedAiConversation(reset = 0){

	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_trained_ai_start_conversation',
            chatflow_id : jQuery('#lbb_chatflow_id').val(),
			conversation_cookie_val : conversation_cookie_val,
			reset : reset
        },
        success: function(response) {
			if (response.success) {

				var messages = response.data.conversations;
				jQuery.each(messages, function(index, message) {

					message.text = message.text.replace(/\\/g, '');
					if(message.is_bot_response == 1){
						// show all bot messages
						lbbHandleAdminResponse(message.text,message);
						
					}else{
						lbbHandleUserResponse(message.text, true);
					}
				});
				scrollToBottom();
			}
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
    });
}

function startAiConversation(){

	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_ai_start_conversation',
            chatflow_id : jQuery('#lbb_chatflow_id').val(),
			conversation_cookie_val : conversation_cookie_val
        },
        success: function(response) {
			jQuery('.lbb-reset-restart-conversation').css('pointer-events', 'auto');
			if (response.success) {

				var messages = response.data.conversations;

				jQuery.each(messages, function(index, message) {
					message.text = message.text.replace(/\\/g, '');
					if(message.is_bot_response == 1){
						// show all bot messages
						lbbHandleAdminResponse(message.text,message);
						
					}else{
						lbbHandleUserResponse(message.text, true);
					}
				});
				scrollToBottom();
			}
        },
        error: function(error) {
            console.error('AJAX error:', error);
        }
    });
}

function submitAI(messge){
	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	jQuery("[name=lbb_input_message]").val('');
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: {
			action: 'sbl_chatbot_ai_submit',
			message : messge,
			chatflow_id : jQuery('#lbb_chatflow_id').val(),
			conversation_cookie_val : conversation_cookie_val

		},
		success: function(response) {
			if(response.success != undefined){
				data = response.data;
				if(data.status == 'ok'){
					lbbCustomReponse(data.object);
				}else if(data.status == 'error'){
					lbbCustomReponse(data.message);
				}
			}
		},
		error: function(error) {
			lbbCustomReponse(error);
		}
	});
}

function submitTrainedAIContact(fname,email){

	jQuery('.lbb-chat-user-info-button').attr('disabled', true);
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: {
			action: 'sbl_chatbot_trained_ai_submit_contact',
			fname : fname,
			email : email,
			chatflow_id : jQuery('#lbb_chatflow_id').val()
		},
		success: function(response) {
			jQuery('.lbb-chat-user-info-button').attr('disabled', false);
			if(response.success != undefined){
				data = response.data;
				if(data.status == 'ok'){
					lbbCustomReponse(data.message);
					jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
				}
			}
		}
	});
}

function lbbbacktoBot(){

	jQuery('.lbb-inline-input-field').remove();
	jQuery('.quick-reply-buttons').remove();
	slgHandleTyping();
	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	setTimeout(() => {
		
		jQuery.ajax({
			url: listbuildingbot.ajax_url,
			type: 'POST',
			data: {
				action: 'lbb_trained_to_logicbot',
				chatflow_id : jQuery('#lbb_chatflow_id').val(),
				conversation_cookie_val : conversation_cookie_val
			},
			success: function(response) {
				jQuery('.lbb-back-to-main-menu-conversation').addClass('lbb-back-main-processing');
				lbbCheckAction(0,0,'');
			}
		});
	}, 1001);

}

function submitBotReply(forceType = 'inline'){


	if(forceType == 'trainedBot'){

		jQuery('.lbb-inline-input-field').remove();
		jQuery('.quick-reply-buttons').remove();

	}

	var val = jQuery("[name=lbb_input_message]").val().toLowerCase();
	var message = jQuery("[name=lbb_input_message]").val();

	if(val.trim() == ''){
		return false;
	}

	if(jQuery('.lbb-show-email-terms').hasClass('lbb-show-email-terms')){
		jQuery(".lbb-email-terms-error").hide();
		if(jQuery('.lbb-inline-input-field').hasClass('lbb-require-email-terms')){

			if (!jQuery("#lbb_accept_terms").prop("checked")) {
				jQuery(".lbb-email-terms-error").show();
				return false;
			}

		}
	}


	var current_action_id = lbb_last_chat.action_id;
	var next_action_id = lbb_last_chat.next_question_id;
	var conversation_id = getConversationID();
	type = lbb_last_chat.type;

	if (jQuery.inArray(type, ['name', 'text', 'email', 'phone', 'country','single','welcome', 'url', 'date', 'pdf','lastmessage']) !== -1) {
		lbbHandleUserResponse(message,false);
		scrollToBottom();
		lbbSubmitUserReply({
			message : message,
			conversation_id : conversation_id,
			current_action_id: current_action_id,
			next_action_id : next_action_id,
			chatflow_id : jQuery('#lbb_chatflow_id').val(),
			forceType : forceType,
			current_url : current_url
		});
		jQuery("[name=lbb_input_message]").val('');
	}else{
		
	}

}

function submitTrainedAI(messge){
	
	var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	jQuery("[name=lbb_input_message]").val('');
	jQuery('.lbb-kb-faq').addClass('lbb-kb-faq-processing');
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: {
			action: 'sbl_chatbot_trained_ai_submit',
			message : messge,
			chatflow_id : jQuery('#lbb_chatflow_id').val(),
			conversation_cookie_val : conversation_cookie_val
		},
		success: function(response) {
			
			if(response.success != undefined){
				setTimeout(() => {
					jQuery('.lbb-kb-faq').removeClass('lbb-kb-faq-processing');
					jQuery('.Messages_list .lbb-agent-typing').remove();
					data = response.data;
					if(data.status == 'ok'){
						if(data.message != undefined){
							var string = data.message;
						}else if(data.object != undefined){
							var string = data.object;
						}
						string.replace(/\\/g, '');
						lbbCustomReponse(string);
						if(data.limit == undefined){
							data.limit = 0;
						}
						document.cookie = 'lbb_'+jQuery('#lbb_chatflow_id').val()+'_ai_response_limit='+data.limit+'; path=/';
						
					}else if(data.status == 'error'){

						if(data.message != undefined){
							var string = data.message;
						}else if(data.object != undefined){
							var string = data.object;
						}
						string.replace(/\\/g, '');
						lbbCustomReponse(string);
						if(data.limit == undefined){
							data.limit = 0;
						}
						//document.cookie = 'lbb_'+jQuery('#lbb_chatflow_id').val()+'_ai_response_limit='+data.limit+'; path=/';

					}

				}, 1500);
	
				
			}
		},
		error: function(error) {
			jQuery('.lbb-kb-faq').removeClass('lbb-kb-faq-processing');
			lbbCustomReponse(error.statusText);
		}
	});
}

var chatflow_id = 0;
jQuery(document).ready(function($) {

	/*var video = document.getElementById("lbb-livechat-video-player");
	if (video !== null) {
		video.addEventListener("ended", function() {
			video.play();
		});
		video.play();
	}*/

	chatflow_id = jQuery('#lbb_chatflow_id').val();

	if (!jQuery('.lbb-dynamic-mode-set').hasClass('lbb-ai-mode') && !jQuery('.lbb-dynamic-mode-set').hasClass('lbb-trained_ai-mode')){
		console.log('145');
		var conversation_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_id';
		var conversation_cookie_val = '';
		if (getCookie(conversation_cookie_key)){
			conversation_cookie_val = getCookie(conversation_cookie_key);
		}

		jQuery.ajax({
	        url: listbuildingbot.ajax_url,
	        type: 'POST',
	        data: {
	            action: 'lbb_get_chat_mode',
	            conversation_cookie_val : conversation_cookie_val,
	            chatflow_id : chatflow_id
	        },
	        success: function(response) {
				if(response != '') {
					jQuery('.lbb-dynamic-mode-set').removeClass('lbb-ai-mode lbb-bot-mode lbb-live-mode');
					jQuery('.lbb-dynamic-mode-set').addClass('lbb-'+response+'-mode');
					chat_mode = response;

					if (chat_mode == 'live'){
						jQuery('#lbb-app').removeClass('lbb-allow-bot-to-trained');
						setTimeout(() => {
							
							var conversation_chat_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_chat_token';
							var chat_token = getCookie(conversation_chat_cookie_key);

							if(chat_token != '' && chat_token != null && chat_token != undefined){

								if (listbuildingbot.lbb_livechat_options == 'ajax_based'){
									lbb_activate_chat_live_mode();
									lbb_init_firebase();
									jQuery('.lbb-typing').removeClass('lbb-user-input-hide');

								}else{
									firebase.auth().signInWithCustomToken(chat_token)
										.then((userCredential) => {
											console.log('Connected');
											lbb_activate_chat_live_mode();
											lbb_init_firebase();
											jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
										}).catch((error) => {
										console.error('Authentication error:', error);
										});

								}




							}else{
								lbb_activate_chat_live_mode();
								lbb_init_firebase();
								jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
							}
							
							
						}, 800);
						
						
						
					}else if(chat_mode == 'trained_ai' && jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
						startTrainedAiConversation();
					}

					/*if(chat_mode == 'bot' && !jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline') && (lbb_minimized_type_option == 'show_first_question') || lbb_minimized_type_option == 'show_first_welcome'){
						lbbStartConversation();
					}*/

					if (chat_mode == 'bot' && !jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline') && 
					    (typeof lbb_minimized_type_option !== 'undefined' && 
					    (lbb_minimized_type_option == 'show_first_question' || lbb_minimized_type_option == 'show_first_welcome'))) {
					        lbbStartConversation();
					}
				}
				if((chat_mode == 'live' || chat_mode == 'bot') && jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline') && chatflow_id){
					lbbStartConversation();
					jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
					if(chat_mode == 'live'){
						setTimeout(() => {
							jQuery('.lbb-chat-start').removeClass('lbb-processing-live-chat');
							lbb_activate_chat_live_mode();
						}, 2000);
					}
				}
				
			}
		});
	}else if(jQuery('.lbb-dynamic-mode-set').hasClass('lbb-trained_ai-mode')){
		console.log('0');
		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
			startTrainedAiConversation();
		}
		jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
	}else{
		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
			startAiConversation();
		}
		jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
	}

	if(chatflow_id){
		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
			//lbbStartConversation();
		}

		if(jQuery('#lbb-chat-main-wrapper').hasClass('minimized') && jQuery('#lbb-chat-main-wrapper').attr('data-whentoshow') == 'certain_time'){
			var time = jQuery('#lbb-chat-main-wrapper').attr('data-time');
			time = parseInt(time)*1000;
			setTimeout(() => {
				jQuery('.lbb-notification-co').removeClass('lbb-hide');
				jQuery('.lbb-notification-co').css('display', 'block');
				jQuery('.lbb-chat-icon-inner').removeClass('lbb-hide');
			}, time);
		}

		if(jQuery('#lbb-chat-main-wrapper').hasClass('minimized') && jQuery('#lbb-chat-main-wrapper').attr('data-whentoshow') == 'upon_scroll'){
			var scroll = jQuery('#lbb-chat-main-wrapper').attr('data-page_scroll');
			lbbCheckScrollPercentage(scroll);
			jQuery(window).trigger('scroll');
		}
	}

	var $inputMessage = jQuery('.lbb_input_message');
    var $sendIcon = jQuery('.lbb-bot-mode .lbb-send-icon');
	$sendIcon.addClass('lbb-no-submit-btn');

    $inputMessage.on('input', function () {
        // Check if the text field has any value
        if ($inputMessage.val().trim() !== '') {
            $sendIcon.removeClass('lbb-no-submit-btn');
        } else {
            $sendIcon.addClass('lbb-no-submit-btn');
        }
    });


	jQuery(document).on('keypress','.lbb-bot-mode .lbb_input_message', function(event) {
		// Check if the pressed key is Enter (key code 13)
		if (event.which === 13) {
		  // Prevent the default form submission behavior
		  event.preventDefault();
			if(chat_mode == 'bot'){
				submitBotReply('trainedBot');
			}else{
				jQuery('.lbb-'+chat_mode+'-mode .lbb-send-icon').trigger('click');
			}
		}
	});

	jQuery(document).on('keydown','.lbb_inline_input', function(event) {

		if(event.which === 13 && event.target.tagName !== 'TEXTAREA') {
			event.preventDefault();
			
				if (event.target.tagName === 'DIV') {
					var lbb_emoji_content = jQuery(this).find('.emojionearea-editor').text();
					if (lbb_emoji_content != '') {
						jQuery('.lbb_input_message').val(lbb_emoji_content);
					}else{
						jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
					}
					
				}else{
					jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
				}
				
				submitBotReply();
				//jQuery('.lbb-bot-mode .lbb-send-icon').trigger('click');	
			
		}
	});

	

	jQuery(document).on('keypress','.lbb-trained_ai-mode .lbb_input_message', function(event) {
		if(event.which === 13) {
			event.preventDefault();
			//jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
			jQuery('.lbb-trained_ai-mode .lbb-send-icon').trigger('click');
		}
	});

	jQuery(document).on('keypress','.lbb-ai-mode .lbb_input_message', function(event) {
		if(event.which === 13) {
			event.preventDefault();
			//jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
			jQuery('.lbb-ai-mode .lbb-send-icon').trigger('click');
		}
	});


	
	jQuery(document).on('click', '.lbb-answer-url-pick', function(e) {
		e.preventDefault();
		lbbSendMail();

		var link = jQuery(this).attr('href');
		window.open(link, '_blank');
	});

	jQuery(document).on('click', '.lbb-back-to-main-menu-conversation', function(e) {
		jQuery('.lbb-listing-dots-click-wrapper').removeClass('lbb-sub-listing-show');
		jQuery('.lbb-back-to-main-menu-conversation').addClass('lbb-back-main-processing');
		lbbbacktoBot();
	});
	
	jQuery(document).on('click', '.lbb-click-icon-for-listing', function(e) {
		jQuery('.lbb-listing-dots-click-wrapper').toggleClass('lbb-sub-listing-show');
	});
	

	jQuery(document).on('click', '.lbb-first-show_first_welcome', function(e) {
		if (!jQuery(e.target).closest('.lbb-close-icon').length) {
			jQuery('.iconInner').trigger('click');
		}
	});
	
	

	jQuery(document).on('click', '.lbb-close', function(e) {
		jQuery('#lbb-app').hide();
		jQuery('.lbb-chat-start').removeClass('lbb-chat-popup-opened');

		if(jQuery('.lbb-notification-co').length > 0) {
			jQuery('.lbb-notification-co').show();
		}
	});

	jQuery(document).on('click', '.lbb-chat-video-image-close-event', function(e) {
		jQuery('.lbb-close').trigger('click');
	});

	jQuery(document).on('click', '.lbb-contact-us-trained-ai', function(e) {
		jQuery('#lbb-chat-main-wrapper').addClass('lbb-chat-user-input');
	});

	jQuery(document).on('keyup','.lbb-kb-search-input-text', function() {

		jQuery.ajax({
			url: listbuildingbot.ajax_url,
			type: 'POST',
			data: {
				action: 'sbl_helpdesk_filter',
				chatflow_id: chatflow_id,
				keyword : jQuery(this).val()
			},
			success: function(response) {
				jQuery('.lbb-kb-main').html(response);
			}
		});

	});

	jQuery(document).on('click', '.lbb-chat-switch-btn', function(e) {

		jQuery('.lbb-chat-switch-btn').removeClass('lbb-chat-switch-active');
		if(jQuery(this).attr('data-mode') == 'helpdesk'){
			jQuery('#lbb-app').addClass('lbb-help-desk-active-tab');
			jQuery(this).addClass('lbb-chat-switch-active');
		}else{
			jQuery('#lbb-app').removeClass('lbb-help-desk-active-tab');
			jQuery(this).addClass('lbb-chat-switch-active');
		}

	});
	jQuery(document).on('click', '.iconInner', function(e) {

		jQuery('.lbb-chat-first-question-inner').html('');
		jQuery('.lbb-chat-first-question-main').remove();
		jQuery('.lbb-chat-start').addClass('lbb-chat-popup-opened');
		var lbb_app_oo = '';
		if(!jQuery('#lbb-app').hasClass('lbb-app-oo')){
			jQuery('#lbb-app').addClass('lbb-app-oo');
			lbb_app_oo = '1';
		}
		if (jQuery("#lbb-app").is(":visible")) {
			jQuery('#lbb-app').hide();
			if(jQuery('.lbb-notification-co').length > 0) {
				jQuery('.lbb-notification-co').show();
			}
		}else{
			jQuery(this).parents('.botIcon').addClass('showBotSubject');
			jQuery('#lbb-app').show();
			if(jQuery('.lbb-notification-co').length > 0) {
				jQuery('.lbb-notification-co').hide();
			}
			$("[name='msg']").focus();
			if(chat_mode != 'ai' && chat_mode != 'trained_ai'){
				if(lbb_app_oo == '1'){
					if((lbb_minimized_type_option != 'show_first_question' && lbb_minimized_type_option != 'show_first_welcome') && chat_mode != 'live'){
						lbbStartConversation();
					}
					if(chat_mode == 'live'){
						jQuery('.lbb-chat-start').addClass('lbb-processing-live-chat');
						jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
						lbbStartConversation();
						setTimeout(() => {
							jQuery('.lbb-chat-start').removeClass('lbb-processing-live-chat');
							lbb_activate_chat_live_mode();
						}, 2000);
						
					}else{
						//jQuery('.lbb-typing').addClass('lbb-user-input-hide');
					}
				}
			}else if(chat_mode == 'live'){
				
			}else{
				if(lbb_app_oo == '1'){
					if(chat_mode == 'ai'){
						startAiConversation();
					}else{
						startTrainedAiConversation();
					}
				}
			}
		}
	});


	

	jQuery(document).on('click', '.lbb-start-new-conversation', function(e) {
		jQuery('.lbb-confirmation #yesBtn').trigger('click');
	});

	jQuery(document).on('click', '.lbb-confirmation #yesBtn', function(e) {
		var chatflow_id = jQuery('#lbb_chatflow_id').val();
		var cookiename = 'lbbcf_'+chatflow_id+'_conversation_id';
		var conversation_chat_id = 'lbbcf_'+chatflow_id+'_conversation_chat_id';
		var conversation_id = getConversationID();
		jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
		lbbDeleteCookie(cookiename);
		lbbDeleteCookie(conversation_chat_id);
		jQuery('.Messages_list').html('');
		if(chatflow_type == 'botlivechat'){
			chat_mode = 'bot';
			jQuery('#lbb-app').removeClass('lbb-live-mode');
			jQuery('#lbb-app').addClass('lbb-bot-mode');
		}


		lbb_reset_timer();

		jQuery('.lbb-reset-restart-conversation').css('pointer-events', 'none');

		lbbcloseconversation(conversation_id);

		if(chat_mode == 'live'){
			jQuery('.lbb-chat-start').addClass('lbb-processing-live-chat');
			setTimeout(() => {
				jQuery('.lbb-chat-start').addClass('lbb-processing-live-chat');
				lbb_activate_chat_live_mode();
			}, 2000);
		}

		if(chat_mode == 'trained_ai'){
			startTrainedAiConversation(1);
		}else if(chat_mode == 'ai'){
			startAiConversation();
		}else{
			lbbStartConversation();
		}
		
		jQuery('.lbb-user-popup').removeClass('lbb-active');
		//jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
	});

	jQuery(document).on('click', '.lbb-confirmation #noBtn', function(e) {
		jQuery('.lbb-user-popup').removeClass('lbb-active');
	});

	jQuery(document).on('click', '.lbb-reset-conversation', function(e) {
		jQuery('.lbb-user-popup').addClass('lbb-active');
	});

	jQuery(document).on('click', '.lbb-restart-conversation', function(e) {
		var conversation_id = getConversationID();
		jQuery('.lbb-inline-input-field').remove();
		lbbStartConversation(conversation_id, true);
	});

	
	jQuery(document).on('click', '.lbb-chat-first-question-main .quick-reply-button', function(e) {
		jQuery('.iconInner').trigger('click');
	});

	jQuery(document).on('click', '.quick-reply-button', function(e) {

		
		var ansAction = jQuery(this).attr('data-ans-action');
		var ansUrl = jQuery(this).attr('data-ans-action-url');

		if(ansAction != 'url') {

			var btnText = jQuery(this).find('.quick-reply-text .quick-reply-spn-text').attr('data-value');
			if(!jQuery(this).find('img').hasClass('emoji')){
				var btnImage = jQuery(this).find('img').attr('src');
			}

			var classnam = '';
			if(jQuery(this).closest('.quick-reply-buttons').hasClass('lbb-mobile-hide-image')){
				classnam = 'lbb-mobile-hide-image';
			}
			
			jQuery(this).closest('.quick-reply-buttons').remove();
			
			

			lbbHandleUserResponse(btnText,false, btnImage,[],classnam);
			slgHandleTyping();
			
			var current_action_id = jQuery(this).attr('data-currentactionid');
			var next_action_id = jQuery(this).attr('data-nextactionid');

			if(ansAction == 'start_over'){
				var repeat_ques = jQuery(this).attr('data-repeat-ques');
				if(repeat_ques !== undefined && repeat_ques != ''){
					next_action_id = repeat_ques;
				}
			}

			var chflowid = 0;
			if(ansAction == 'different_bot'){
				chflowid = jQuery(this).attr('data-diffchatflow');
			}

			var tags = jQuery(this).attr('data-tags');
			var conversation_id = getConversationID();

			message_meta = {};
			if(btnImage){
				message_meta['image'] = btnImage;
			}
			console.log(lbb_last_chat);

			jQuery('.skip-button-div').remove();

			jQuery.ajax({
				url: listbuildingbot.ajax_url,
				type: 'POST',
				data: {
					action: 'sbl_chatbot_submit_reply',
					message : btnText,
					message_meta : message_meta,
					conversation_id : conversation_id,
					current_action_id: current_action_id,
					next_action_id : next_action_id,
					tags : tags,
					chatflow_id : jQuery('#lbb_chatflow_id').val(),
					type : 'single',
					current_url : current_url
				},
				success: function(response) {

					if(response.data.warning != undefined && response.data.warning == 1){
						if(response.data.available == 1){
							
							var chatflow_id_for_welcome = jQuery('#lbb_chatflow_id').val();
							var conversation_cookie_key = 'lbbcf_'+chatflow_id_for_welcome+'_conversation_id';
							var conversation_cookie_val = '';
							if (getCookie(conversation_cookie_key)){
								conversation_cookie_val = getCookie(conversation_cookie_key);
							}
							var welcome_message = response.data.welcome_message;
							if (welcome_message == ''){
								welcome_message = siteConfig.welcome_message;
							}
							lbbCustomReponse(welcome_message,'lbb-live-chat');
							setTimeout(function() {
								store_messsage_in_db(welcome_message, conversation_id, 0, 1)
							}, 500);

							setTimeout(function() {
								store_messsage_in_db(response.data.message, conversation_id, 0, 1);
								lbbCustomReponse(response.data.message,'lbb-live-chat');
							}, 500);

							lbb_chat_activity_timer();
							



							//lbbCustomReponse('Please wait... transferring to an agent');

							jQuery('.lbb-admin-response .lbb-avatar-outer img').attr('src',lbb_live_agent_image);
							jQuery('.lbb-avatar-image img').attr('src',lbb_live_agent_image);

							jQuery('.lbb-admin-info-wrapper .lbb-header').html(lbbAdminLiveName);


							

							//lbbCustomReponse(response.data.message,'lbb-live-chat');
							lbb_activate_chat_live_mode();
							jQuery('.lbb-restart-conversation').hide();
						}else{
							setTimeout(() => {
								if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-end-msg')){
									lbbCustomReponse(response.data.message,'lbb-conversation-end-msg');
								}
							}, 1600);
						}
					}else{
						setTimeout(() => {
							//lbb_prev_message = current_action_id;
							//jQuery('.lbb-admin-response.lbb-last-back').removeClass('lbb-last-back');
							if(btnText != 'Back'){
								jQuery('.lbb-admin-response.lbb-last-back').removeClass('lbb-last-back');
							}
							var conversation_cookie_key = 'lbbcf_'+jQuery('#lbb_chatflow_id').val()+'_conversation_id';
							if (!getCookie(conversation_cookie_key) && response.data.conversation_id != undefined && response.data.conversation_id != ''){
								setCookie(conversation_cookie_key, response.data.conversation_id, 365);
							}
							lbbCheckAction(current_action_id,next_action_id, btnText,chflowid);
						}, 1500);
					}
					
				},
				error: function(error) {
					console.error('AJAX error:', error);
				}
			});
		}

	});

	jQuery(document).on('click', '.closeBtn, .chat_close_icon', function(e) {
		jQuery(this).parents('.botIcon').removeClass('showBotSubject');
		jQuery(this).parents('.botIcon').removeClass('showMessenger');
	});

	jQuery(document).on('click', '.lbb-ai-mode .lbb-send-icon', function(e) {
		var val = jQuery("[name=lbb_input_message]").val();

		if(val == ''){
			return false;
		}

		lbbHandleUserResponse(val,false);
		scrollToBottom();
		slgHandleTyping();
		submitAI(val);
	});

	jQuery(document).on('click', '.lbb-trained_ai-mode .lbb-send-icon', function(e) {
		var val = jQuery("[name=lbb_input_message]").val();
		lbbHandleUserResponse(val,false);
		scrollToBottom();
		slgHandleTyping();
		submitTrainedAI(val);
	});



	jQuery(document).on('click', '.lbb-inline-send-icon', function(e) {
		jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
		submitBotReply();
		//jQuery('.lbb-bot-mode .lbb-send-icon').trigger('click');
	});
	

	jQuery(document).on('click', '.lbb-back-conversation', function(e) {

		var current_action_id = lbb_last_chat.action_id;
		var next_action_id = lbb_prev_message;
		var conversation_id = getConversationID();

		if(!jQuery('.lbb-admin-response').hasClass('lbb-last-back')){
			jQuery('.lbb-action-' + next_action_id).addClass('lbb-last-back');
		}else{
			lbb_prev_message = jQuery('.lbb-admin-response.lbb-last-back').attr('data-prev');
			jQuery('.lbb-admin-response.lbb-last-back').removeClass('lbb-last-back');
			next_action_id = lbb_prev_message;
			jQuery('.lbb-action-' + next_action_id).addClass('lbb-last-back');
		}

		if(lbb_prev_message == 0){
			jQuery('.lbb-back-conversation').hide();
		}

		if(lbb_prev_message == lbb_first_question){
			jQuery('.lbb-back-conversation').hide();
		}

		lbbHandleUserResponse(lbb_back_question,false);
		lbbSubmitUserReply({
			message : lbb_back_question,
			messageType : 'back',
			conversation_id : conversation_id,
			current_action_id: current_action_id,
			next_action_id : next_action_id,
			chatflow_id : jQuery('#lbb_chatflow_id').val(),
			current_url : current_url
		});
		
	});

	jQuery(document).on('click', '.lbb-skip-message-link', function(e) {

		var curQ = jQuery(this).attr('data-currentquestion');
		var nextQ = jQuery(this).attr('data-nextquestion');

		var conversation_id = getConversationID();
		lbbHandleUserResponse(lbb_skip_question,false);
		lbbSubmitUserReply({
			message : lbb_skip_question,
			messageType : 'skip',
			conversation_id : conversation_id,
			current_action_id: curQ,
			next_action_id : nextQ,
			chatflow_id : jQuery('#lbb_chatflow_id').val(),
			current_url : current_url
		});

		jQuery('.skip-button-div').remove();

	});
	
	/*jQuery(document).on("click","#lbbRedirectLink",function(event) {
        event.preventDefault();
        setTimeout(function() {
			var link = jQuery(this).attr("href");
            window.open(link, "_blank");
        }, 3000);
    });*/

	jQuery(document).on('click', '.lbb-kb-faq .trained-ai-faq', function(e) {
		var val = jQuery(this).text();

		jQuery("[name=lbb_input_message]").val(val);
		jQuery('.lbb-trained_ai-mode .lbb-send-icon').trigger('click');

	});

	jQuery(document).on('click', '.lbb-chat-first-question-main .lbb-close-icon', function(e) {
		jQuery('.lbb-chat-first-question-main').hide();
	});

	jQuery(document).on('click', '.lbb-bot-mode .lbb-send-icon', function(e) {
		e.preventDefault();


		submitBotReply('trainedBot');

		return false;

		/*var val = jQuery("[name=lbb_input_message]").val().toLowerCase();
		var message = jQuery("[name=lbb_input_message]").val();

		if(val.trim() == ''){
			return false;
		}

		if(jQuery('.lbb-show-email-terms').hasClass('lbb-show-email-terms')){
			jQuery(".lbb-email-terms-error").hide();
			if(jQuery('.lbb-inline-input-field').hasClass('lbb-require-email-terms')){

				if (!jQuery("#lbb_accept_terms").prop("checked")) {
					jQuery(".lbb-email-terms-error").show();
					return false;
				}

			}
		}


		var current_action_id = lbb_last_chat.action_id;
		var next_action_id = lbb_last_chat.next_question_id;
		var conversation_id = getConversationID();
		type = lbb_last_chat.type;

		if (jQuery.inArray(type, ['name', 'text', 'email', 'phone', 'country','single','welcome', 'url', 'date', 'pdf','lastmessage']) !== -1) {
			lbbHandleUserResponse(message,false);
			scrollToBottom();
			lbbSubmitUserReply({
				message : message,
				conversation_id : conversation_id,
				current_action_id: current_action_id,
				next_action_id : next_action_id,
				chatflow_id : jQuery('#lbb_chatflow_id').val()
	        });
			jQuery("[name=lbb_input_message]").val('');
		}else{
			
		}

		return false;*/
	});

	jQuery(document).on('change','#lbb-file-upload-input', function() {
        const selectedFile = jQuery(this)[0].files[0];
		const progressBar = jQuery('.lbb-progress-bar');
        if (selectedFile) {
            const formData = new FormData();
            formData.append('image', selectedFile);

			var next_q_id = jQuery(this).closest('.lbb-field-type-attachment').attr('data-action');

            // Send the image data to the server via AJAX
            jQuery.ajax({
                type: 'POST',
                url: listbuildingbot.ajax_url+'?action=lbb_handle_attachment_upload&action_id='+next_q_id,
                data: formData,
                processData: false,
                contentType: false,
				xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            const percentComplete = (event.loaded / event.total) * 100;
                            progressBar.css('width', percentComplete + '%');
                            progressBar.html(percentComplete + '%');
                            progressBar.attr('aria-valuenow', percentComplete);
                            progressBar.text(percentComplete.toFixed(2) + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
					var response = JSON.parse(response);

					var errorMessage = '';
					if(response.status != undefined && response.status == 'error'){
						errorMessage = response.message;
					}

                    const attachment = response.attachment;
                    var attachment_type = response.attachment_type;

					progressBar.css('width', '0%');
                    progressBar.attr('aria-valuenow', '0');
                    progressBar.text('');

					message_meta = {};
					message_meta['attachment'] = attachment;
					message_meta['attachment_type'] = attachment_type;
					message_meta['name'] = name;
				
                    // Display the uploaded image in the chat interface
                    //const imgElement = `<img src="`+imageUrl+`" alt="Uploaded Image" />`;
					var html = response.download_box;
                    //jQuery('#chat-messages').append(imgElement);

					var current_action_id = lbb_last_chat.action_id;
					var next_action_id = lbb_last_chat.next_question_id;
					var conversation_id = getConversationID();
					type = lbb_last_chat.type;
					jQuery('.lbb-upload-box').remove();
					jQuery('.lbb-progress-bar').remove();

					if(html != undefined) {
						lbbHandleUserResponse(html,false);
					}

					scrollToBottom();
					
					lbbSubmitUserReply({
						message : '',
						message_meta : message_meta,
						conversation_id : conversation_id,
						current_action_id: current_action_id,
						next_action_id : next_action_id,
						chatflow_id : jQuery('#lbb_chatflow_id').val(),
						errorMessage : errorMessage,
						current_url : current_url
					});

                },
                error: function() {
                    console.error('Image upload failed.');
                }
            });
        }
    });

	jQuery(window).on('load', function () {
		if(jQuery('.lbb-chat-start.minimized').hasClass('lbb-is-loading')) {
			jQuery('.iconInner').trigger('click');
		}
		jQuery('.lbb-chat-start').removeClass('lbb-is-loading');
		jQuery('.lbb-loading-mian').hide();
	});
	  
	jQuery('.lbb-kb-search-form').on('submit', function(event){
		return false;		
	});

	/* Chatboat Code */
})