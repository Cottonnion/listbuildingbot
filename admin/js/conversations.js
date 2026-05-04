
function lbbSortChat(){
	var $listItems = jQuery('.user-admin-list-left-side');

	// Sort the list items based on their data-count attribute in descending order
	$listItems.sort(function(a, b) {
		var countA = parseInt(jQuery(a).data('time'));
		var countB = parseInt(jQuery(b).data('time'));
		
		return countB - countA; // Sort in descending order
	});

	//console.log($listItems);

	// Append the sorted elements back to their parent container
	$listItems.appendTo(jQuery('.list'));
}

function lbbConvUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}


function lbbConversationMotifications(filterMode="", add_active_id = 0){

	//setTimeout(() => {
		
		var conversation_id = jQuery(this).attr('data-id');
		var current_conv = 0;
		if (filterMode == 'L'){
			current_conv = jQuery('#message-conversation-id').val();
		}
		
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'user_id' : jQuery('#req-user-id').val(),
				'conversation_id': conversation_id,
				'filterMode': filterMode,
				'conv_ids' : JSON.stringify(conversation_ids),
				'current_conv' : current_conv,
				'action': 'lbb_notifications_heartbeat',
				'timezone' : Intl.DateTimeFormat().resolvedOptions().timeZone,
			},
			success: function (response) {
				jQuery('.lbb-message-count-main').remove();
				response = JSON.parse(response);
				var messageCounts = (response.messageCounts);
				var newConversations = (response.newConversations);
				var unread_current_user = (response.unread_current_user);

				jQuery.each(newConversations, function(conversationId, message) {
					var conversationDiv = jQuery('#chat-list-user-' + conversationId);
					if (conversationDiv.length < 1) {
						jQuery('#conversation-chat-left').prepend(message);
						conversation_ids.push(conversationId);
					}
				});
				if (conversations.lbb_livechat_options == 'ajax_based'){
					var message_ids = [];

					if (unread_current_user != undefined) {
						jQuery.each(unread_current_user, function(key, value) {
							var msg_data = value.message_text;
							var sent_time = value.sent_time;
							//var conversation_id = value.conversation_id;

							message_ids.push(value.message_id);
							msg_data = msg_data.replace(/\\/g, '');
							if (current_conv == jQuery('.user-admin-list-left-side.active-chat').attr('data-id')){
								lbbAdminChatHTML(sent_time, msg_data );
							}
							
							jQuery('#chat-list-user-'+value.conversation_id).find('.status').html(msg_data);
						});

						lbbUpdateMessageInDb(message_ids, current_conv);
						lbbAdminscrollToBottom();

					}
				}
				


				if(response.ai_chat_count[0].row_count != 0){
					jQuery('.lbb-chat-type-ai').append('<span class="lbb-message-count-main">'+response.ai_chat_count[0].row_count+'</span>');
				}
				if(response.bot_chat_count[0].row_count != 0){
					jQuery('.lbb-chat-type-bot').append('<span class="lbb-message-count-main">'+response.bot_chat_count[0].row_count+'</span>');
				}
				if(response.live_chat_count[0].row_count != 0){
					jQuery('.lbb-chat-type-live').append('<span class="lbb-message-count-main">'+response.live_chat_count[0].row_count+'</span>');

				}

				jQuery.each(messageCounts, function(conversationId, messageCount) {

					if (messageCount > 0) {
						var conversationDiv = jQuery('#chat-list-user-' + conversationId);
						var firebaseSpecificUser = conversationDiv.attr('data-firebaseid');
						load_user_message_for_admin(firebaseSpecificUser);
						
                        if (conversationDiv.length > 0) {
							if(messageCount != conversationDiv.attr('data-count')){
								conversationDiv.find('.lbb-message-count').html(messageCount);
								conversationDiv.attr('data-count',messageCount);
								conversationDiv.addClass('chat-new-msg');
								lbbSortChat();
								console.log('New Message Found');
								//showNotification(); 
								//lbb_notification_ding();
							}
						}else{
							console.log(conversationId);
							//conversationDiv.find('.lbb-message-count').prepend(conversationsHtml);
						}
						
					}

				});

				if (add_active_id != 0){
						var lbb_active_user_id = '#chat-list-user-'+add_active_id;
						jQuery(lbb_active_user_id).addClass('active-chat');
				}
			}
		});

	//}, 5000);

}

function lbbAdminscrollToBottom() {
    var chatBox = document.getElementById("chat-history");
    chatBox.scrollTop = chatBox.scrollHeight;
}

function checkFirebaseId() {

	var con_id = jQuery('#message-conversation-id').val();

	if(jQuery('#message-to-send-user-id').val() != 0){
		return false;
	}

	jQuery.ajax({
		type: 'post',
		url: lbb_ajax.ajaxurl,
		data: {
			'action' : 'lbb_load_firebase_id',
			'con_id': con_id,
		},
		success: function (response) {
			response = JSON.parse(response);

			if(response.firebase_id != undefined){
				jQuery('#message-to-send-user-id').val(response.firebase_id);
				jQuery('#chat-list-user-'+con_id).val(response.firebase_id);
			}
		}
	});
}

function lbbMarkAsMessages(conversation_id){
	// Make an AJAX request to mark a message as read
    jQuery.ajax({
        url: lbb_ajax.ajaxurl,
        type: 'POST',
        data: {
            action: 'lbb_mark_as_messages',
            conversation_id: conversation_id
        },
        success: function(response) {
			jQuery('#chat-list-user-'+conversation_id).removeClass('chat-new-msg');
			
            // Update the UI to indicate that the message has been read
        }
    });
}

var moffset = 10;
var mloading = false;
var mfinished = false;
var mpostsContainer = '';
function lbbLoaMoreMessages(){
	conversation_id = jQuery('.user-admin-list-left-side.active-chat').attr('data-id');
	firebase_id = jQuery('.user-admin-list-left-side.active-chat').attr('data-firebaseid');

	if (mloading || mfinished) return;
	
	//return false;
	mloading = true;
	jQuery.ajax({
		url: lbb_ajax.ajaxurl,
		type: 'POST',
		data: {
			action: 'lbb_load_more_messages',
			conversation_id : conversation_id,
			moffset: moffset,
		},
		success: function(response) {
			if (response) {
				response = JSON.parse(response);
				mpostsContainer.prepend(response.userchat_list_html);

				moffset = moffset + 10;


			} else {
				mfinished = true;
			}
			mloading = false;
		},
	});
}

var offset = 50;
var loading = false;
var finished = false;
var postsContainer = '';

function lbbLoadMoreConversations() {
	if (loading || finished) return;
	
	loading = true;
	lbbShowLoader('Loading more conversations...');
	jQuery.ajax({
		url: lbb_ajax.ajaxurl,
		type: 'POST',
		data: {
			action: 'lbb_load_more_conversations',
			user_id : jQuery('#req-user-id').val(),
			filter :{
				'mode' : jQuery('input[name="filter-mode"]').val()
			},
			offset: offset,
		},
		success: function(response) {
			setTimeout(function(){
				lbbHideLoader();
			},1000);
			if (response) {
				response = JSON.parse(response);
				postsContainer.append(response.html);

				jQuery.each(response.conv_ids, function(index,conversationId) {
					conversation_ids.push(conversationId);
				});

				offset = offset + 50;


			} else {
				finished = true;
			}
			loading = false;
		},
	});
}

function lbb_load_user_message(conversation_id, firebase_id=''){
	lbbShowLoader();
	jQuery.ajax({
		type: 'post',
		url: lbb_ajax.ajaxurl,
		data: {
			'user_id' : jQuery('#req-user-id').val(),
			'conversation_id': conversation_id,
			'timezone' : Intl.DateTimeFormat().resolvedOptions().timeZone,
			'action': 'lbb_load_message_data'
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);
			if(response.userchat_list_html){
				jQuery('.chat-history #chat-data-append').html(response.userchat_list_html);
			}else{
				jQuery('.chat-history #chat-data-append').html('<div class="chat-history-empty-msg" style="display:block;"> <h3>No messages found currently</h3> </div>'); }

			if(response.name){
				jQuery('.chat-with-user-name').html(response.name);
			}if(response.avatar){
				jQuery('.chat-header .chat-icon img').attr('src', response.avatar);
			}

			setTimeout(() => {
				load_user_message_for_admin();
			}, 1000);
			
			if(response.firebase_id != undefined && response.firebase_id != ''){
				firebase_id = response.firebase_id;
			}

			if(response.side_popup_data){
				jQuery('.lbb-user-info-chat').html(response.side_popup_data);
			}
			
			if(firebase_id == '' || response.is_closed == '1'){
				jQuery('.chat-main-wrapper-start').addClass('lbb-disable-form');
			}else{
				jQuery('.chat-main-wrapper-start').removeClass('lbb-disable-form');
			}

			jQuery('.user-admin-list-left-side').removeClass('active-chat');
			jQuery('#chat-list-user-'+conversation_id).addClass('active-chat');

			jQuery('#message-conversation-id').val(conversation_id);
			jQuery('#message-to-send-user-id').val(firebase_id);
			lbbAdminscrollToBottom();
			lbbMarkAsMessages(conversation_id);
		}
	});
}

function LBBShowNotification(title, body) {
  new Notification(title, { body });
}

jQuery(document).ready(function(){

	setTimeout(function(){
		//var mode = lbbConvUrlVars()["mode"];
		//if(mode == 'L'){
			jQuery('#conversation-chat-left li:eq(0)').trigger('click');
		//}
	}, 500);

	jQuery('#message-to-send').keypress(function(event) {
    	if (event.keyCode === 13) {
     	 	jQuery('.lbb-send-mail-btn').click();
     	 	return false;
    	}
  	});
	var filterMode = jQuery('#filter-mode').val();

	if(filterMode == 'L' && jQuery('#conversation-chat-left li').length == 0){
		jQuery('#conversation-form input[name="mode"]').val('B');

		jQuery('#conversation-form').submit();
	}
	//console.log(filterMode);
	if (conversations.lbb_livechat_options == 'ajax_based'){
		if (typeof current_conversation_id !== 'undefined' && current_conversation_id != ''){
			jQuery('.user-admin-list-left-side').removeClass('active-chat');
			jQuery('#chat-list-user-'+current_conversation_id).trigger('click');

			jQuery('.lbb-chat-widget-admin .chat').removeClass('empty-box-style');
			setTimeout(function(){
				lbb_load_user_message(current_conversation_id, '');

				console.log(current_conversation_id);
				lbbConversationMotifications(filterMode, current_conversation_id);
			}, 2000);
			
		}else{
			console.log(filterMode);
			lbbConversationMotifications(filterMode);
		}
	}else{
		jQuery.ajax({
		url: siteConfig.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				action: "lbb_auth_current_user",
				type: "POST",
				uid: jQuery('#admin-firebase-id').val(),
			},
			success: function(response) {
	            console.log(response)
	            var  token = response.token;
				
	            
				firebase.auth().signInWithCustomToken(token)
				.then((userCredential) => {
					console.log('Connected');
					if (typeof current_conversation_id !== 'undefined' && current_conversation_id != ''){
						jQuery('.user-admin-list-left-side').removeClass('active-chat');
						jQuery('#chat-list-user-'+current_conversation_id).trigger('click');

					jQuery('.lbb-chat-widget-admin .chat').removeClass('empty-box-style');

					lbb_load_user_message(current_conversation_id,'');

					console.log(current_conversation_id);
					lbbConversationMotifications(filterMode, current_conversation_id);
				}else{
					console.log(filterMode);
					lbbConversationMotifications(filterMode);
				}
				

				}).catch((error) => {
				  console.error('Authentication error:', error);
				});
			
	        }
		})
	}

	
	
	


	if(jQuery('#conversation-chat-left').length > 0) {
		setInterval(() => {
			lbbConversationMotifications(filterMode);
		}, 5000);
		
	}
	postsContainer = jQuery('#conversation-chat-left');
	postsContainer.scroll(function() {
        if (postsContainer.scrollTop() + postsContainer.height() >= postsContainer[0].scrollHeight - 100) {
			console.log('Scroll to bottom');
            lbbLoadMoreConversations();
        }
    });

	mpostsContainer = jQuery('#chat-history');
	
	mpostsContainer.scroll(function() {
        if (mpostsContainer.scrollTop() === 0) {
			console.log('Scroll up');
            lbbLoaMoreMessages();
        }
    });

	jQuery(document).on('change','.lbb-mode-change input',function() {
		jQuery('#conversation-form').submit();
	});


	jQuery(document).on('click','.lbb-load-more-conversations',function() {
		lbbLoadMoreConversations();
	});
	jQuery(document).on('click','.lbb-popup-top-close',function() {
	    jQuery('.lbb-popup-top-wrapper').removeClass('lbb-active-block');
	});

	jQuery(document).on('click','.lbb-grant-notification-permision-btn',function() {
	    Notification.requestPermission().then(permission => {
		  if (permission === 'granted') {
		    // Notification permission granted, you can now show notifications
		    LBBShowNotification('Permission Granted', 'You can now receive notifications.');
		  } else if (permission === 'denied') {
		    // Notification permission denied
		    alert('Notifications permission has been blocked as the user has ignored the permission prompt several times. This can be reset in Page Info which can be accessed by clicking the lock icon next to the URL.');
		  } else if (permission === 'default') {
		    // The user closed the permission prompt without making a choice
		    alert('The user closed the permission prompt without making a choice.');
		  }
		}).catch(error => {
		  // Handle errors that might occur during the permission request
		  alert('Error requesting notification permission:', error);
		});

		
	});

	
	if (jQuery('.lbb-popup-notication-permision').hasClass('lbb-notification-is-on') &&  Notification.permission !== 'granted') {
		jQuery('.lbb-popup-top-wrapper').addClass('lbb-active-block');
	}

	jQuery(document).on('click','.user-admin-list-left-side',function() {
		jQuery('.chat-main-wrapper-start').addClass('lbb-conversation-edit-mode');
		jQuery('.user-admin-list-left-side').removeClass('active-chat');
		jQuery(this).addClass('active-chat');
    	//jQuery('#chat-list-user-'+user_id).addClass('active-chat');
		var conversation_id = jQuery(this).attr('data-id');
		var firebase_id = jQuery(this).attr('data-firebaseid');
		
		jQuery('.lbb-chat-widget-admin .chat').removeClass('empty-box-style');
		lbb_load_user_message(conversation_id, firebase_id)
	});

	jQuery("#searchStudentChat").on("keyup", function() {
	  	var value = this.value.toLowerCase().trim();
	  	jQuery("ul.list li").show().filter(function() {
	    	return jQuery(this).text().toLowerCase().trim().indexOf(value) == -1;
	  	}).hide();
	});

});

