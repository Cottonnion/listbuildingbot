
var lbb_last_chat = {};
var lbb_prev_message = 0;
var lbb_first_question = 0;
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
								chatflow_id : jQuery('#lbb_chatflow_id').val()
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
	
	lbb_inactive_timer = setInterval(function() {
		
		if(chat_mode != 'live'){
			return false;
		}	
		var userT = lbb_get_last_message_from_user_firebase();
		console.log('Active timer....');
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

	/*lbb_admin_timer = setInterval(function() {
		var isAdminBusy = lbb_check_last_message_from_firebase('admin',60);
		if(isAdminBusy){
			console.log('Admin busy');
			clearInterval(lbb_admin_timer);
			lbbCustomReponse("Sorry, all agents are currently busy. Please leave a message below and we'll get back to you asap.", 'lbb-conversation-end-msg');
			//lbb_conversation_end();
		}else{
			console.log('No Idle');
		}

	}, 5000);*/
  
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
					lbbCustomReponse(response.data.message, 'lbb-conversation-end-msg');
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
			lbbCustomReponse(message, 'lbb-admin-response');
		});
	}
}

function lbbHandleExtraMessages(messages){
	if(messages.length > 0){
		jQuery.each(messages, function(index, message) {
			lbbCustomReponse(message, 'lbb-admin-response');
		});
	}
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
	message = message.replace('{{chatflow_id}}', chatflow_id);

	/*if(action_data.field_html != undefined){
		message = message + action_data.field_html;
	}*/

	jQuery('.Messages_list').append(message);

	if(action_data.field_html != undefined){
		jQuery('.Messages_list .lbb-message').last().find('.lbb-text').append(action_data.field_html);
		if(jQuery("#lbb-datepicker").length > 0){
			console.log(action_data);
			jQuery("#lbb-datepicker").flatpickr({
				dateFormat: action_data.date_format,
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
}

function lbbHandleEndConversationResponse(message){
	
	var messageTemplate = jQuery('#lbb-agent-response').html();

	messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
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

function lbbCustomReponse(message,classname){

	jQuery('.Messages_list .lbb-agent-typing').remove();

	var hide_auth = false;
	if(jQuery('.Messages_list .lbb-message').last().hasClass('lbb-admin-response')){
		jQuery('.Messages_list .lbb-message').last().addClass('lbb-field-type-extramessage');
		hide_auth = true;
	}
	
	var messageTemplate = jQuery('#lbb-agent-response').html();

	messageTemplate = messageTemplate.replace('{{reply_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{pdf_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', message);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);

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
	messageTemplate = messageTemplate.replace('{{pdf_buttons}}', '');
	messageTemplate = messageTemplate.replace('{{inline_field}}', '');
	messageTemplate = messageTemplate.replace('{{message}}', typingM);
	messageTemplate = messageTemplate.replace('{{name}}', lbbAdminName);
	messageTemplate = messageTemplate.replace('{{image}}', adminImg);

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-admin-response').addClass('lbb-agent-typing');

	setTimeout(() => {
		jQuery('.Messages_list').append(messageTemplateObject.html());
		scrollToBottom();
	}, 1000);
	
}



function lbbHandleUserResponse(message, server,image = '',messageObj = []) {

	if(image != ''){
		var ImgEl = '<div class="quick-reply-useranswer-image"><div class="quick-reply-spn-text">'+message+'</div>'+'<div class="quick-reply-spn-img"><img src="'+image+'" /></div></div>';
		var imageClass = 'lbb-has-image';
	}else{
		var ImgEl = message;
		var imageClass = 'lbb-has-no-image';
	}

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
	}

	var messageTemplateObject = jQuery('<div>');
	messageTemplateObject.html(messageTemplate);
	messageTemplateObject.find('.lbb-message-user').addClass(imageClass);
	jQuery('.Messages_list').append(messageTemplateObject.html());
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


function lbbStartConversation(conversation_id,isRestart = false) {

	if(chat_mode == 'live'){
		lbb_send_event();
		jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
	}else{
		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
			jQuery('.lbb-typing').addClass('lbb-user-input-hide');
		}
	}

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
            action: 'sbl_chatbot_start_conversation',
            chatflow_id : jQuery('#lbb_chatflow_id').val(),
            conversation_cookie_val : conversation_cookie_val
        },
        success: function(response) {
			if(chat_mode == 'live'){
				lbb_chat_activity_timer();
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
				if (!getCookie(conversation_cookie_key)){
					setCookie(conversation_cookie_key, conversation_id, 365);
				}
				if(messages.length > 0){
					jQuery.each(messages, function(index, message) {

						if(message.is_bot_response == 1){

							if(message.extra_messages != undefined){
								lbbHandleExtraMessages(message.extra_messages);
							}

							// show all bot messages
							lbbHandleAdminResponse(message.text, message);
							
							if(message.dynamic_messages != undefined){
								lbbHandleDynamicMessage(message.dynamic_messages);
							}
						}else if(message.agent_id > 0){
							var img = '';
							if(message.image != undefined && message.image != ''){
								img = message.image;
							}
							lbbHandleAdminResponse(message.text);
						}else{
							var img = '';
							if(message.image != undefined && message.image != ''){
								img = message.image;
							}
							lbbHandleUserResponse(message.text, true,img,message);
						}
					});
				}

				if(response.data.end_status == 1){
					if(!jQuery('.lbb-admin-response').hasClass('lbb-conversation-end-msg')){
						lbbCustomReponse(userTimeoutMessage,'lbb-conversation-end-msg');
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

									if(block.extra_messages != undefined){
										lbbHandleExtraMessages(block.extra_messages);
									}

									lbbHandleAdminResponse(block.text,block);

									if(block.dynamic_messages != undefined){
										lbbHandleDynamicMessage(block.dynamic_messages);
									}

									scrollToBottom();
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
		jQuery('.lbb-typing').addClass('lbb-user-input-hide');
	}else{
		if(type == 'text' || type == 'name' || type == 'email' || type == 'date' || type == 'url' || type == 'phone' || type == 'country' || type == 'outcome'){
		//jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
		}else{
			jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
		}
	}

	//var fieldPlaceholder = {'text': 'Enter your message', 'name' : 'Enter your name'};

	if(fieldPlaceholder['lbb_input_placeholder_'+type] != undefined){
		var text = fieldPlaceholder['lbb_input_placeholder_'+type]
	}else{
		var text = 'Enter your message';
	}

	jQuery('.lbb_input_message').attr('placeholder',text);
	jQuery('.lbb_inline_input').attr('placeholder',text);
	
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
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: data,
		success: function(response) {
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
					if(btnText != 'Back'){
						jQuery('.lbb-admin-response.lbb-last-back').removeClass('lbb-last-back');
					}
					lbbCheckAction(current_action_id,next_action_id, btnText);
				}, 1500);
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
				}
			}
			
			// You can add your desired actions here
		}
	});
}

function lbbCheckAction(current_action_id,next_action_id,user_reply){

	var conversation_id = getConversationID();
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_chatbot_action',
            current_action_id : current_action_id,
            next_action_id : next_action_id,
            userReply:user_reply,
			conversation_id : conversation_id,
			chatflow_id : chatflow_id
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

				if(block.extra_messages != undefined){
					lbbHandleExtraMessages(block.extra_messages);
				}

                lbbHandleAdminResponse(block.text,block);

				if(block.dynamic_messages != undefined){
					lbbHandleDynamicMessage(block.dynamic_messages);
				}

				var end_messsage = response.data.end;
				if(end_messsage != undefined && end_messsage != '') {
					lbbHandleEndConversationResponse(end_messsage);
				}

                scrollToBottom();

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

function startAiConversation(){
	jQuery.ajax({
        url: listbuildingbot.ajax_url,
        type: 'POST',
        data: {
            action: 'sbl_ai_start_conversation',
            chatflow_id : jQuery('#lbb_chatflow_id').val()
        },
        success: function(response) {
			if (response.success) {

				var messages = response.data.conversations;
				jQuery.each(messages, function(index, message) {

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
	
	jQuery("[name=lbb_input_message]").val('');
	jQuery.ajax({
		url: listbuildingbot.ajax_url,
		type: 'POST',
		data: {
			action: 'sbl_chatbot_ai_submit',
			message : messge,
			chatflow_id : jQuery('#lbb_chatflow_id').val()
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

	if (!jQuery('.lbb-dynamic-mode-set').hasClass('lbb-ai-mode')){

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
						lbb_activate_chat_live_mode();
						lbb_init_firebase();
						lbb_send_event();
					}
				}
				if((chat_mode == 'live' || chat_mode == 'bot') && jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline') && chatflow_id){
					lbbStartConversation();
				}
				
			}
		});
	}else{
		startAiConversation();
		jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
	}

	if(chatflow_id){
		if(jQuery('#lbb-chat-main-wrapper').hasClass('lbb-chattype-inline')){
			//lbbStartConversation();
		}

		if(jQuery('#lbb-chat-main-wrapper').hasClass('minimized') && jQuery('#lbb-chat-main-wrapper').attr('data-whentoshow') == 'certain_time'){
			var time = jQuery('#lbb-chat-main-wrapper').attr('data-time');
			setTimeout(() => {
				jQuery('.lbb-chat-icon-inner').removeClass('lbb-hide');
			}, time);
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
			jQuery('.lbb-'+chat_mode+'-mode .lbb-send-icon').trigger('click');
		}
	});

	jQuery(document).on('keypress','.lbb_inline_input', function(event) {
		if(event.which === 13) {
			event.preventDefault();
			jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
			jQuery('.lbb-bot-mode .lbb-send-icon').trigger('click');
		}
	});

	jQuery(document).on('click', '.lbb-close', function(e) {
		jQuery('#lbb-app').hide();
	});
	jQuery(document).on('click', '.iconInner', function(e) {

		var lbb_app_oo = '';
		if(!jQuery('#lbb-app').hasClass('lbb-app-oo')){
			jQuery('#lbb-app').addClass('lbb-app-oo');
			lbb_app_oo = '1';
		}
		if (jQuery("#lbb-app").is(":visible")) {
			jQuery('#lbb-app').hide();
		}else{
			jQuery(this).parents('.botIcon').addClass('showBotSubject');
			jQuery('#lbb-app').show();
			$("[name='msg']").focus();
			if(chat_mode != 'ai'){
				if(lbb_app_oo == '1'){
					lbbStartConversation();
					if(chat_mode == 'live'){
						lbb_activate_chat_live_mode();
						jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
					}
				}
			}else if(chat_mode == 'live'){
			}else{
				if(lbb_app_oo == '1'){
					startAiConversation();
				}
			}
		}
	});

	jQuery(document).on('click', '.lbb-confirmation #yesBtn', function(e) {

		lbb_reset_timer();
		var chatflow_id = jQuery('#lbb_chatflow_id').val();
		var cookiename = 'lbbcf_'+chatflow_id+'_conversation_id';
		var conversation_chat_id = 'lbbcf_'+chatflow_id+'_conversation_chat_id';
		lbbDeleteCookie(cookiename);
		lbbDeleteCookie(conversation_chat_id);
		jQuery('.Messages_list').html('');
		lbbStartConversation();


		jQuery('.lbb-user-popup').removeClass('lbb-active');
		jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
	});

	jQuery(document).on('click', '.lbb-confirmation #noBtn', function(e) {
		jQuery('.lbb-user-popup').removeClass('lbb-active');
	});

	jQuery(document).on('click', '.lbb-reset-conversation', function(e) {
		jQuery('.lbb-user-popup').addClass('lbb-active');
	});

	jQuery(document).on('click', '.lbb-restart-conversation', function(e) {
		var conversation_id = getConversationID();
		lbbStartConversation(conversation_id, true);
	});

	

	jQuery(document).on('click', '.quick-reply-button', function(e) {

		
		var ansAction = jQuery(this).attr('data-ans-action');
		var ansUrl = jQuery(this).attr('data-ans-action-url');

		if(ansAction != 'url') {

			var btnText = jQuery(this).find('.quick-reply-text .quick-reply-spn-text').attr('data-value');
			if(!jQuery(this).find('img').hasClass('emoji')){
				var btnImage = jQuery(this).find('img').attr('src');
			}
			jQuery(this).closest('.quick-reply-buttons').remove();
		
			lbbHandleUserResponse(btnText,false, btnImage);
			slgHandleTyping();
			
			var current_action_id = jQuery(this).attr('data-currentactionid');
			var next_action_id = jQuery(this).attr('data-nextactionid');

			if(ansAction == 'start_over'){
				var repeat_ques = jQuery(this).attr('data-repeat-ques');
				next_action_id = repeat_ques;
			}

			var tags = jQuery(this).attr('data-tags');
			var conversation_id = getConversationID();

			message_meta = {};
			if(btnImage){
				message_meta['image'] = btnImage;
			}
			console.log(lbb_last_chat);
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
					type : 'single'
				},
				success: function(response) {

					if(response.data.warning != undefined && response.data.warning == 1){
						if(response.data.available == 1){
							lbbCustomReponse(response.data.message,'lbb-live-chat');
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
							lbbCheckAction(current_action_id,next_action_id, btnText);
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
		lbbHandleUserResponse(val,false);
		scrollToBottom();
		slgHandleTyping();
		submitAI(val);
	});

	jQuery(document).on('click', '.lbb-inline-send-icon', function(e) {
		jQuery('.lbb_input_message').val(jQuery('.lbb_inline_input').val());
		jQuery('.lbb-bot-mode .lbb-send-icon').trigger('click');
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

		lbbHandleUserResponse('Back',false);
		lbbSubmitUserReply({
			message : 'Back',
			conversation_id : conversation_id,
			current_action_id: current_action_id,
			next_action_id : next_action_id,
			chatflow_id : jQuery('#lbb_chatflow_id').val()
		});
		
	});
	
	/*jQuery(document).on("click","#lbbRedirectLink",function(event) {
        event.preventDefault();
        setTimeout(function() {
			var link = jQuery(this).attr("href");
            window.open(link, "_blank");
        }, 3000);
    });*/

	jQuery(document).on('click', '.lbb-bot-mode .lbb-send-icon', function(e) {
		e.preventDefault();

		var val = jQuery("[name=lbb_input_message]").val().toLowerCase();
		var message = jQuery("[name=lbb_input_message]").val();

		if(val.trim() == ''){
			return false;
		}

		var current_action_id = lbb_last_chat.action_id;
		var next_action_id = lbb_last_chat.next_question_id;
		var conversation_id = getConversationID();
		type = lbb_last_chat.type;

		if (jQuery.inArray(type, ['name', 'text', 'email', 'phone', 'country','single','next','welcome', 'url', 'date', 'pdf','lastmessage']) !== -1) {
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

		return false;
	});

	jQuery(document).on('change','#lbb-file-upload-input', function() {
        const selectedFile = jQuery(this)[0].files[0];
		const progressBar = jQuery('.progress-bar');
        if (selectedFile) {
            const formData = new FormData();
            formData.append('image', selectedFile);

            // Send the image data to the server via AJAX
            jQuery.ajax({
                type: 'POST',
                url: listbuildingbot.ajax_url+'?action=lbb_handle_attachment_upload',
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

					lbbHandleUserResponse(html,false);
					
					scrollToBottom();
					
					lbbSubmitUserReply({
						message : '',
						message_meta : message_meta,
						conversation_id : conversation_id,
						current_action_id: current_action_id,
						next_action_id : next_action_id,
						chatflow_id : jQuery('#lbb_chatflow_id').val()
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
	  

	/* Chatboat Code */
})