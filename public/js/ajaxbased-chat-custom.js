var notification_admin_id;
var current_url = jQuery(location).attr('href');
var session_html = '';


function scrollToBottomLive() {
	if(jQuery('#chat-messages').length){
	    var chatBox = document.getElementById("chat-messages");
	    chatBox.scrollTop = chatBox.scrollHeight;
    }
}

jQuery(document).ready(function ($) {

	if (jQuery('body').hasClass('wp-admin') && siteConfig.is_have_any_live_chat){
		setInterval(() => {
			lbbLoadChat();
		}, 10000);
	}

	jQuery(window).on('storage', lbb_message_receive);
	jQuery(window).on('storage', lbb_admin_message_receive);

	jQuery("#lbb-chat-main-wrapper.lbb-template-wrapper .lbb-conversation").on('scroll', function() {
	    if (jQuery(this).scrollTop() + jQuery(this).innerHeight() + 2 >= jQuery(this)[0].scrollHeight) {
	    	jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
	    }
	});

	
	jQuery(document).on('keyup','.lbb-live-mode .lbb_input_message', function(key){
		if (key.keyCode == 13) {
			if (jQuery('#lbb-app').hasClass('lbb-live-mode')) {
				SendMessage();
				jQuery('.lbb-live-mode .lbb_input_message').val('');
			}
		}
	});

	jQuery(document).on('click', '.lbb-live-mode  .lbb-send-icon', function(e){
		if (jQuery('#lbb-app').hasClass('lbb-live-mode')) {
			SendMessage();
			jQuery('.lbb-live-mode .lbb_input_message').val('');
		}
	});

if (typeof chat_id !== 'undefined') {
	var conversation_chat_cookie_key = 'lbbcf_'+chat_id+'_conversation_chat_id';
	var conversation_chat_cookie_val = '';
	if (getCookie(conversation_chat_cookie_key)){
		conversation_chat_cookie_val = getCookie(conversation_chat_cookie_key);
		jQuery('#current_role_id').val(conversation_chat_cookie_val);
	}
}



var current_admin_id = jQuery("#current_admin_id").val();


jQuery('.form-bottom-submit-with-checkbox  button').on('click', function(e){

	
	var admin_send_msg  = jQuery('#message-to-send').val();

	jQuery('.user-admin-list-left-side.active-chat .status').text(admin_send_msg);
	jQuery('.user-admin-list-left-side.active-chat').removeClass('chat-new-msg');

	LBBMoveLiToFirst('.user-admin-list-left-side.active-chat');
	lbbAdminChatHTML('','','A');


	jQuery('#message-to-send').val('');
	lbbAdminscrollToBottom();
	e.preventDefault();
});


jQuery('.lbb-chat-user-info-nice-form').on('submit', function(e){
	e.preventDefault();
	var fname = jQuery('#lbb-chat-user-info-full-name').val();
	var email = jQuery('#lbb-chat-user-info-email').val();

	if(jQuery('#lbb-app').hasClass('lbb-trained_ai-mode')){

		submitTrainedAIContact(fname,email);

	}else{

		jQuery('.lbb-contact-form-error').hide();
		var req_privacy =  jQuery('#lbb-chat-user-privacy').val();
		var show_privacy =  jQuery('#lbb-chat-user-req-privacy').val();
		var phone =  jQuery('#lbb-chat-user-info-phone').val();
		
		var req_phone =  jQuery('#lbb-chat-user-phone-req').val();
		var is_valid = true;
		
		if(fname == ''){
			jQuery('.lbb-contact-form-error-fullname').show();
			is_valid = false;
		}else if (email == '') {
			jQuery('.lbb-contact-form-error-email').show();
			is_valid = false;
		}else if(req_phone == 'yes' && phone == ''){
			jQuery('.lbb-contact-form-error-phone').show();
			is_valid = false;
		}else if(show_privacy == 'yes' && req_privacy == 'yes' && !jQuery('#chat-privacy').is(':checked')){
			jQuery('.lbb-contact-form-error-term').show();
			is_valid = false;
		}else{
			//alert('Please enter email address');
		}

		if(is_valid){
			
			get_current_user_data(fname, email,phone);
		}
	}
});


/*Notification*/
	jQuery(document).on('DOMContentLoaded', function () {
    // Request desktop notifications permission on page load

    
  });

	/*if (jQuery('#lbb-livechat-video-player').length) {
	    jQuery('#lbb-livechat-video-player')[0].play();
	}

	if (jQuery('#lbb-bg-video-tag').length) {
	    jQuery('#lbb-bg-video-tag')[0].play();
	}

	if (jQuery('#lbb-bg-video-tag-common').length) {
	    jQuery('#lbb-bg-video-tag-common')[0].play();
	}

	if (jQuery('#lbb-bg-video-tag-common-btn').length) {
	    jQuery('#lbb-bg-video-tag-common-btn')[0].play();
	}*/
		
	
});



jQuery(window).on("load", function(){
	if (siteConfig.is_wp_admin) {
	    if (!Notification) {
	      console.log('Desktop notifications are not available in your browser.');
	      return;
	    }

	    /*if (Notification.permission !== 'granted') {
	      Notification.requestPermission();
	    }*/
	}

});


function lbbConversationMotificationsFrontend(){

	var conversation_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_id';
	var conversation_id = '';
	if (getCookie(conversation_cookie_key)){
		conversation_id = getCookie(conversation_cookie_key);
	}
	
	jQuery.ajax({
		type: 'post',
		url: siteConfig.ajaxurl,
		data: {
			'conversation_id': conversation_id,
			'timezone' : Intl.DateTimeFormat().resolvedOptions().timeZone,
			'action': 'lbb_user_notification_heartbeat',
		},
		success: function (response) {
			response = JSON.parse(response);
			var unread_current_user = (response.unread_current_user);
			var message_ids = [];

			if (unread_current_user != undefined) {
				jQuery.each(unread_current_user, function(key, value) {
					var msg_data = value.message_text;
					var sent_time = value.sent_time;
					//var conversation_id = value.conversation_id;
					msg_data = msg_data.replace(/\\/g, '');
					message_ids.push(value.message_id);

					SendMessage(sent_time, msg_data,'A');
					
				});

				//lbbUpdateMessageInDb(message_ids, conversation_id, sender_type = 'U');
				
			}
		}
	});
}
function showNotification(last_msg = '', firebase_user_id = '') {

	const dynamic_key = last_msg+'-'+firebase_user_id;
	const notificationKey = 'notificationDisplayed_'+dynamic_key;

    if (Notification.permission !== 'granted') {
      Notification.requestPermission();
    } else {
    	if (last_msg == ''){
    		last_msg = 'You have received a new message';
    	}
      const options = {
        body: last_msg,
        tag : notificationKey,
        dir: 'ltr',
       /* image: 'image.jpg'*/
      };
      const notification = new Notification('New Message', options);

      notification.onshow = () => {
         if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio){
			//lbb_notification_ding();
        }
         
     };

      if (firebase_user_id != ''){
		notification.onclick = function () {
	        window.open(siteConfig.conversationurl+'&mode=L&firebase_id='+firebase_user_id);
		}
      }
      
    }
  }

  function lbb_send_event(){
	/*jQuery('.lbb-live-mode .lbb_input_message').keyup(function (key) {
		if (key.keyCode == 13) {
			SendMessage();
			jQuery(this).val('');
		}
	});

	jQuery('.lbb-live-mode  .lbb-send-icon').click(function (key) {
		SendMessage();
		jQuery(this).val('');
	});*/

}

function lbbConnectingAgent(){

	var conversation_chat_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_chat_id';
	var conversation_chat_cookie_val = '';
	if (getCookie(conversation_chat_cookie_key)){
		conversation_chat_cookie_val = getCookie(conversation_chat_cookie_key);
	}

	var conversation_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	jQuery.ajax({
		url: siteConfig.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				action: "lbb_check_agent_status",
				lbb_chatflow_id: chatflow_id,
				conversation_chat_cookie_val: conversation_chat_cookie_val,
				conversation_cookie_val: conversation_cookie_val
			},
			success: function(response) {

				var response_data = (response);

				if(response_data.available == 1){
					jQuery('.connecting-agent').remove();
					/*lbbCustomReponse(admin_has_joined,'lbb-special-messages-old');
					store_messsage_in_db(admin_has_joined, conversation_cookie_val, 1, 1, 1);*/

					var welcome_message = response_data.welcome_message;
					if (welcome_message == ''){
						welcome_message = siteConfig.welcome_message;
					}

					jQuery('.lbb-admin-response .lbb-avatar-outer img').attr('src',lbb_live_agent_image);
					jQuery('.lbb-avatar-image img').attr('src',lbb_live_agent_image);

					jQuery('.lbb-admin-info-wrapper .lbb-header').html(lbbAdminLiveName);


					/*setTimeout(function() {
						store_messsage_in_db(welcome_message, conversation_cookie_val, 1, 1);
						lbbConversationMotificationsFrontend();
					}, 500);
					setTimeout(function() {
						sendMessageFromAdmin(current_role_id, welcome_message);
						lbbConversationMotificationsFrontend();
						lbb_send_event();
					}, 2000);*/

				}else{
					jQuery('.connecting-agent').remove();
					lbbCustomReponse('Agent is not available at momment','lbb-special-messages-old');
				}

				lbbConversationMotificationsFrontend();
			}
		});
}
function get_current_user_data(fname = '', email = '',phone = '') {
	lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();

	let conversation_chat_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_chat_id';
	var conversation_chat_cookie_val = '';
	if (getCookie(conversation_chat_cookie_key)){
		conversation_chat_cookie_val = getCookie(conversation_chat_cookie_key);
	}

	var conversation_cookie_key = 'lbbcf_'+lbb_chatflow_id+'_conversation_id';
	var conversation_cookie_val = '';
	if (getCookie(conversation_cookie_key)){
		conversation_cookie_val = getCookie(conversation_cookie_key);
	}

	var orgval = jQuery('.lbb-chat-user-info-button').html();
	jQuery('.lbb-chat-user-info-button').html('Please wait..');
	jQuery('.lbb-chat-user-info-button').attr('disabled', 'true');

	jQuery.ajax({
		url: siteConfig.ajaxurl,
			type: "POST",
			dataType: "JSON",
			data: {
				action: "lbb_check_current_user",
				type: "GET",
				lbb_chatflow_id: lbb_chatflow_id,
				fname: fname,
				email: email,
				phone: phone,
				conversation_chat_cookie_val: conversation_chat_cookie_val,
				conversation_cookie_val: conversation_cookie_val
			},
			success: function(response) {
				var newItems = false;
				var response_data = JSON.parse(response.data);
				jQuery('.lbb-chat-user-info-button').removeAttr('disabled');
				jQuery('.lbb-chat-user-info-button').html(orgval);
				if( response_data.key !== "" ) {
					//user is logged in

					if (conversation_chat_cookie_val == ''){
						setCookie(conversation_chat_cookie_key, response_data.key, 365);
					}


					jQuery("#current_role_id").val(response_data.key);

					var current_id_new = response_data.id;
					if (current_id_new == '1'){
						current_id_new = 0;
					}
					jQuery("#current_id").val(current_id_new);

					current_role_id = response_data.key;

					/*var chat_id = siteConfig.chat_id;
					var email = siteConfig.email;*/

					
					//setCookie('lbb_firstname', fname, 365);
					//setCookie('lbb_email', email, 365);


					
						//var chat_token = response_data.token;
						//var conversation_chat_cookie_key = 'lbbcf_'+chatflow_id+'_conversation_chat_token';
						//setCookie(conversation_chat_cookie_key, chat_token, 365);


						lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
						
						if (!response_data.is_existing_user) {

							jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
							//lbbCustomReponse('Connecting to agent...','connecting-agent');
							lbbConnectingAgent();
							lbbConversationMotificationsFrontend();
							/*var welcome_message = response_data.welcome_message;
							if (welcome_message == ''){
								welcome_message = siteConfig.welcome_message;
							}

							store_messsage_in_db(welcome_message, conversation_cookie_val, 1, 1);
							setTimeout(function() {
							    sendMessageFromAdmin(current_role_id, welcome_message);
								lbb_send_event();
						  }, 2000);*/	
						}else{
							isAdminBusy = false;
							//jQuery('.lbb-typing').addClass('lbb-user-input-hide');
							lbb_reset_timer();
							lbb_chat_activity_timer()
							jQuery('.lbb-agent-typing').hide();
							lbb_send_event();
						}
						jQuery('.lbb-typing').removeClass('lbb-user-input-hide');
					
					


				}

				jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');

				
			}
	})
}


function load_user_message_for_admin(firebase_dynamic_id = ''){
	var newItemsAdmin = false;



}

function load_new_admin_child(snapshot) {

	alert('load_new_admin_child');
	return;

	chat_key_data = snapshot.key;
	chat_val_data = snapshot.val();
	var admin_user = 1;
	var msg = chat_val_data.msg;
	var sender_id = chat_val_data.sender_id;
	var date = chat_val_data.date;
	var msg_html = msg;
	var time_format = moment(chat_val_data.date).format('HH:mm:ss');
	var latest_chat_message = "";

	//console.log(chat_val_data);
	var inputDateTime = new Date(date);

	// Extract hours and minutes from the Date object
	var hours = inputDateTime.getHours();
	var minutes = inputDateTime.getMinutes();

	// Format the time as "HH:mm" (hours:minutes)
	var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;

	var user_avtar = jQuery('.user-admin-list-left-side.active-chat').attr('data-admin-img');

	

	if(admin_user !== parseInt(chat_val_data.user_id)) {
		var user_avtar = jQuery('.user-admin-list-left-side.active-chat').attr('data-admin-img');
		var user_name = jQuery('.user-admin-list-left-side.active-chat .about .name').text();
		//chat message from user
		latest_chat_message = `<li class="user-visitor-list chat-top-msg"  id="`+chat_key_data+`">
	      <div class="message-box-img">
	        <span class="chat-icon"><img src="`+user_avtar+`"></span>
	      </div>
	          <div class="message-box-data">
	            <div class="message-data">
	              <span class="message-data-name"> `+user_name+`</span>
	              <span class="message-data-time">`+formattedTime+`</span>
	            </div>
	            <div class="message my-message">
	              <div class="lbb-user-message">${msg_html}</div>
	            </div>
	          </div>
	    </li>`;
		

	    var old_count = parseInt(jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"] ').find('.lbb-message-count').text());

	    var final_count = 0;
	    if (!isNaN(old_count)){
	    	final_count = old_count;
	    }

	    var checkuser = jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"]').hasClass('active-chat');
	    jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"] .status').text(msg_html);
	    jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"]').find('.lbb-message-count').text(final_count + 1);
		jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"]').addClass('chat-new-msg');


		//jQuery('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"]').prependTo('#people-list');

		LBBMoveLiToFirst('.user-admin-list-left-side[data-firebaseid="' + sender_id + '"]');
		

		if (typeof siteConfig.notify_notification !== 'undefined' && siteConfig.notify_notification){
	      showNotification(msg_html, sender_id);
        }
        if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio && siteConfig.notify_notification == false){
			lbb_notification_ding();
        }


	} else {
		//chat messahe from admin
		//latest_chat_message = `<span id="`+chat_key_data+`" class="chat_msg_item chat_msg_item_admin"><span class="msg">${msg_html}</span><span class="time_status">${time_format}</span></span>`;
		

		

	}

	
	

	var scrollToBottom = LBBCheckScroll();

	if (jQuery('#message-to-send-user-id').val() != '' && checkuser){	
		jQuery('#chat-data-append').append(latest_chat_message);
		if (scrollToBottom){
			var chat_history_div = jQuery('#chat-history');
    		chat_history_div.animate({ scrollTop: chat_history_div.prop('scrollHeight') }, 1000);
		}

	}

	//console.log(snapshot.key);


	if(admin_user !== parseInt(chat_val_data.user_id)) {
		setTimeout(function() {
			if (typeof checkFirebaseId === 'function') {
				checkFirebaseId();
			}
		}, 1000);
		
	}
}

function LBBMoveLiToFirst(selector) {
    var element = jQuery(selector);
    element.prependTo(element.parent());
}

function LBBCheckScroll(scrolldiv = 'chat-history') {
    var scrollableDiv = document.getElementById(scrolldiv);

    if (!scrollableDiv) {
        //console.error("Element with ID '" + scrolldiv + "' not found.");
        return false;
    }

    // Check if the user has scrolled to the bottom
    if (scrollableDiv.scrollTop + scrollableDiv.clientHeight + 10 >= scrollableDiv.scrollHeight) {
      return true;
      // You can perform additional actions here
    } else {
      return false;
    }
  }


function getCurrentTime() {
	const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();

    // Ensure hours and minutes are displayed with leading zero if needed
    const formattedHours = String(hours).padStart(2, '0');
    const formattedMinutes = String(minutes).padStart(2, '0');

    // Combine the formatted time components
    const formattedTime = `${formattedHours}:${formattedMinutes}`;

    return formattedTime;
}

function load_new_child(snapshot) {

	alert('load_new_child');
	return;

	chat_key_data = snapshot.key;
	chat_val_data = snapshot.val();
	var admin_user = 1;
	var msg = chat_val_data.msg;
	var msg_html = msg;
	var time_format = moment(chat_val_data.date).format('HH:mm:ss');
	var latest_chat_message = "";
	
	if(admin_user !== parseInt(chat_val_data.user_id)) {
		//chat message from user
		

	} else {
		//chat messahe from admin
		//latest_chat_message = `<span id="`+chat_key_data+`" class="chat_msg_item chat_msg_item_admin"><span class="msg">${msg_html}</span><span class="time_status">${time_format}</span></span>`;

		//latest_chat_message = `<span id="`+chat_key_data+`" class="chat_msg_item chat_msg_item_user"><span class="msg">${msg_html}</span></span>`;
		//lbbHandleAdminResponse(latest_chat_message);
		var scrollToBottomFrontend = LBBCheckScroll('chat-messages');

		if (!jQuery('.lbb-dynamic-mode-set').is(':visible')) {
			jQuery('#lbb-chat-main-wrapper').addClass('lbb-new-message-found');
		}else{
			var cthis = "#lbb-chat-main-wrapper.lbb-template-wrapper .lbb-conversation";
			if (jQuery(cthis).scrollTop() + jQuery(cthis).innerHeight() + 2 >= jQuery(cthis)[0].scrollHeight) {
			    jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
			}else{
				jQuery('#lbb-chat-main-wrapper').addClass('lbb-new-message-found');
			}
		}

		isAdminBusy = false;
		lbb_reset_timer();
		lbb_chat_activity_timer();

		var cur_time = '';
		latest_chat_message = `<div class="lbb-message lbb-admin-response"  id="`+chat_key_data+`">
			<div class="lbb-avatar-outer">
			    <img src="{{image}}" alt="" class="lbb-lazy-img-loaded">
			</div>
		    <div class="chat-user-info-wrapper">
		        <div class="chat-user-name">Bot</div>
		        <div class="lbb-anchors lbb-bot-response lbb-text"">${msg_html}</div>
				<div class="chat-user-submit-time">`+getCurrentTime()+`</div>
		    </div>
		</div>`;
		lbbHandleAdminResponse(latest_chat_message, false);

		if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio){
			lbb_notification_ding();
	    }

	    
	    if (scrollToBottomFrontend){
			var chat_history_div = jQuery('#chat-messages');
    		chat_history_div.animate({ scrollTop: chat_history_div.prop('scrollHeight') }, 1000);
		}



	}
}


function lbb_activate_chat_live_mode() {
	jQuery('#lbb-app').removeClass('lbb-bot-mode');
	jQuery('#lbb-app').addClass('lbb-live-mode');

	jQuery('#lbb-app').removeClass('lbb-allow-bot-to-trained');

	if(jQuery('#lbb-app').length > 0) {
		setInterval(() => {
			lbbConversationMotificationsFrontend();
		}, 5000);
		
	}

	var check_firebase_user = getCookie('lbbcf_'+chatflow_id+'_conversation_chat_id');
	var lbb_firstname = getCookie('lbb_firstname');
	var lbb_email = getCookie('lbb_email');
	
	if (check_firebase_user != null && check_firebase_user != undefined && check_firebase_user != '') {
		return;
	}

	if ((lbb_firstname != null && lbb_firstname != undefined && lbb_firstname != '') && (lbb_email != null && lbb_email != undefined && lbb_email != '')) {
		lbb_email = lbb_email.replace("@", "%40");
		get_current_user_data(lbb_firstname, lbb_email);
	}else if (check_firebase_user == null || check_firebase_user == undefined || check_firebase_user == '') {
		jQuery('#lbb-chat-main-wrapper').addClass('lbb-chat-user-input')
	}else{
		get_current_user_data();
	}
	
}


function sendMessageFromAdmin(current_role_id, message_text) {

	isAdminBusy = false;
	//jQuery('.lbb-typing').addClass('lbb-user-input-hide');
	lbb_reset_timer();
	lbb_chat_activity_timer();
	lbbAdminChatHTML('A');
	jQuery('.lbb-agent-typing').hide();
}


function lbbAdminChatHTML(time = '', text = '', sendder_type = 'U' ) {
   
	var admin_avtar = jQuery('.user-admin-list-left-side.active-chat').attr('data-admin-img');
	if (sendder_type == 'U'){

	// Convert to Date object

    // Format the date-time to "HH:mm"
    var formattedTime = time;


		latest_chat_message = `<li class="user-visitor-list chat-top-msg">
          <div class="message-box-img">
            <span class="chat-icon"></span>
          </div>
              <div class="message-box-data">
                <div class="message-data">
                  <span class="message-data-name"> Unknown Visitor</span>
                  <span class="message-data-time">`+formattedTime+`</span></div>
                <div class="message my-message">
                  <div class="lbb-user-message">${text}</div>
                </div>
              </div>
        </li>`;
        var checkuser = 1;
	    jQuery('#chat-data-append').append(latest_chat_message);

	    lbb_chatflow_id = jQuery('#message-conversation-id').val();

	    var jsonData = {
		  "conversation_id": lbb_chatflow_id,
		  "msg": latest_chat_message
		};

		var jsonString = JSON.stringify(jsonData);

	    lbb_admin_message_broadcast({'command':'lbbsetadminsendmsg', 'lbb_admin_user_msg': jsonString});

	}else{

		var admin_send = 1;
	    var is_conversation_id = 1;
	    lbb_chatflow_id = jQuery('#message-conversation-id').val();
	    isAdminBusy = false;


			var currentDate = new Date();
		    // Get the current hours and minutes
		    var currentHours = currentDate.getHours();
		    var currentMinutes = currentDate.getMinutes();

		    // Format hours and minutes with leading zeros if needed
		    currentHours = ('0' + currentHours).slice(-2);
		    currentMinutes = ('0' + currentMinutes).slice(-2);

			var formattedTime = currentHours + ":" + currentMinutes;
			var text_message = jQuery('#message-to-send').val();
	   
	   		var admin_name = jQuery('#admin-name').val();

		latest_chat_message = `<li class="user-admin-list">
	      <div class="message-box-img">
	        <span class="chat-icon"></span>
	      </div>
	          <div class="message-box-data">
	            <div class="message-data">
	              <span class="message-data-name"> Admin</span>
	              <span class="message-data-time">`+formattedTime+`</span>
	            </div>
	            <div class="message my-message">
	              <div class="lbb-user-message">${text_message}</div>
	            </div>
	          </div>
	    </li>`;

	    var checkuser = 1;
	    if (jQuery('#chat-list-user-'+lbb_chatflow_id).hasClass('active-chat')){
	    	jQuery('#chat-data-append').append(latest_chat_message);

	    	var jsonData = {
			  "conversation_id": lbb_chatflow_id,
			  "msg": latest_chat_message
			};


			var jsonString = JSON.stringify(jsonData);

	    	lbb_admin_message_broadcast({'command':'lbbsetadminunreadmsg', 'lbb_admin_user_msg': jsonString});
	    }
	    

	    store_messsage_in_db(text_message, lbb_chatflow_id, admin_send, is_conversation_id)

		scrollToBottomLive();
	}

    	


}

function lbb_message_broadcast(message)
{
    localStorage.setItem('lbb_user_msg',JSON.stringify(message));
    localStorage.removeItem('lbb_user_msg');
}


// receive message
//
function lbb_message_receive(ev)
{
    if (ev.originalEvent.key!='lbb_user_msg') return; // ignore other keys
    var message=JSON.parse(ev.originalEvent.newValue);
    if (!message) return; // ignore empty msg or msg reset

    // here you act on messages.
    // you can send objects like { 'command': 'doit', 'data': 'abcd' }
    if (message.command == 'lbbsetunreadmsg'){
    	jQuery('.lbb-conversation.Messages_list').append(message.lbb_user_msg);
    }

    if (message.command == 'lbbsetsendmsg'){
    	jQuery('.lbb-conversation.Messages_list').append(message.lbb_user_msg);
    }

    // etc.
}

function lbb_admin_message_broadcast(message)
{
    localStorage.setItem('lbb_admin_user_msg',JSON.stringify(message));
    localStorage.removeItem('lbb_admin_user_msg');
}


// receive message
//
function lbb_admin_message_receive(ev)
{
    if (ev.originalEvent.key!='lbb_admin_user_msg') return; // ignore other keys
    var message=JSON.parse(ev.originalEvent.newValue);
    if (!message) return; // ignore empty msg or msg reset

    var chatflow_brodcast_id = jQuery('.user-admin-list-left-side.active-chat').attr('data-id');


    var jsonData = JSON.parse(message.lbb_admin_user_msg);

	// Access values
	var conversationId = jsonData.conversation_id;
	var message_data = jsonData.msg;



    // here you act on messages.
    // you can send objects like { 'command': 'doit', 'data': 'abcd' }
    if (message.command == 'lbbsetadminunreadmsg'){

    	if (conversationId == chatflow_brodcast_id){
    		jQuery('#chat-data-append').append(message_data);
    	}
    	
    }

    if (message.command == 'lbbsetadminsendmsg'){
    	if (conversationId == chatflow_brodcast_id){
    		jQuery('#chat-data-append').append(message_data);
    	}
    }

    // etc.
}



function SendMessage(time = '', text = '', sender_type = 'U') {
	
	if (sender_type == 'U'){

		var currentUserId = jQuery('#current_id').val();
		var text_message = jQuery('.lbb_input_message').val();
		
		var admin_send = 0;
		var is_conversation_id = 1;
		//lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
		lbb_chatflow_id = getConversationID();



		latest_chat_message = `<span class="chat_msg_item chat_msg_item_user"><span class="msg">${text_message}</span></span>`;

		latest_chat_message = latest_chat_message.replace('{{time}}',getCurrentTime());
		var latest_chat_message_html = lbbHandleUserResponse(latest_chat_message, false);
		lbb_message_broadcast({'command':'lbbsetsendmsg', 'lbb_user_msg': latest_chat_message_html});



		isUserBusy = false;
		lbb_reset_timer();
		lbb_chat_activity_timer();
		
		
		
		store_messsage_in_db(text_message, lbb_chatflow_id, admin_send, is_conversation_id)
		

		scrollToBottomLive();

	}else{
		var scrollToBottomFrontend = LBBCheckScroll('chat-messages');

		if (!jQuery('.lbb-dynamic-mode-set').is(':visible')) {
			jQuery('#lbb-chat-main-wrapper').addClass('lbb-new-message-found');
		}else{
			var cthis = "#lbb-chat-main-wrapper.lbb-template-wrapper .lbb-conversation";
			if (jQuery(cthis).scrollTop() + jQuery(cthis).innerHeight() + 2 >= jQuery(cthis)[0].scrollHeight) {
			    jQuery('#lbb-chat-main-wrapper').removeClass('lbb-new-message-found');
			}else{
				jQuery('#lbb-chat-main-wrapper').addClass('lbb-new-message-found');
			}
		}

		isAdminBusy = false;
		lbb_reset_timer();
		lbb_chat_activity_timer();

	    var formattedTime = time;


		var cur_time = '';
		latest_chat_message = `<div class="lbb-message lbb-admin-response"">
			<div class="lbb-avatar-outer">
			    <img src="{{image}}" alt="" class="lbb-lazy-img-loaded">
			</div>
		    <div class="chat-user-info-wrapper">
		        <div class="chat-user-name">Bot</div>
		        <div class="lbb-anchors lbb-bot-response lbb-text"">${text}</div>
				<div class="chat-user-submit-time">`+formattedTime+`</div>
		    </div>
		</div>`;
		var latest_chat_message_html = lbbHandleAdminResponse(latest_chat_message, false);
		lbb_message_broadcast({'command':'lbbsetunreadmsg', 'lbb_user_msg': latest_chat_message_html});

		if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio){
			lbb_notification_ding();
	    }

	    
	    if (scrollToBottomFrontend){
			var chat_history_div = jQuery('#chat-messages');
    		chat_history_div.animate({ scrollTop: chat_history_div.prop('scrollHeight') }, 1000);
		}
	}
    
	
}


function store_messsage_in_db(text_message, lbb_chatflow_id, admin_send, is_conversation_id = 0, default_is_read = 0){

	var tmp_cid = 0;
	if(typeof chat_id !== 'undefined'){
		tmp_cid = chat_id;
	}

	jQuery.ajax({
		url: siteConfig.ajaxurl,
		type: "POST",
		dataType: "JSON",
		data: {
			action: "lbb_add_message",
			type: "GET",
			lbb_chatflow_id: lbb_chatflow_id,
			text_message: text_message,
			admin_send: admin_send,
			is_read: default_is_read,
			is_conversation_id: is_conversation_id,
			lbb_chat_id : tmp_cid
		},
		success: function(response) {

		}
	});
}



/*const lbbAudioContext = new AudioContext();*/

function LBBPlaySound() {
  /*const oscillatorNode = lbbAudioContext.createOscillator();
  const gainNode = lbbAudioContext.createGain();

  oscillatorNode.type = 'sine';
  oscillatorNode.frequency.setValueAtTime(150, lbbAudioContext.currentTime);
  oscillatorNode.frequency.exponentialRampToValueAtTime(500, lbbAudioContext.currentTime + 0.5);

  gainNode.gain.setValueAtTime(0.3, lbbAudioContext.currentTime);
  gainNode.gain.exponentialRampToValueAtTime(0.01, lbbAudioContext.currentTime + 0.5);

  oscillatorNode.connect(gainNode);
  gainNode.connect(lbbAudioContext.destination);

  oscillatorNode.start();
  oscillatorNode.stop(lbbAudioContext.currentTime + 0.5);*/

  if (jQuery('#lbb-bell-audio-admin').length){
		setTimeout(function() {
			var bellAudio = document.getElementById('lbb-bell-audio-admin');
  			bellAudio.play();

		}, 100);
	}
}

function lbb_notification_ding() {
    
	if (jQuery('#lbb-bell-audio').length){
		setTimeout(function() {
			var bellAudio = document.getElementById('lbb-bell-audio');
  			bellAudio.play();

		}, 100);
	}else{
		var sound = new  Audio("data:audio/mp3;base64,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yXmEk2Q8gY0pKFk92tQYWWFVOWpINDQzxEPt/bWkIOwUPCjCvaoZcM6ZpC9z8tnDU71XJ0MY6wygGSwPGKUTyhUVXAVQXHVhdBFB4RwuSVcnY1wuqxQ48J2iyChDvLrSL20iAgZS3aouOes1ZYRoh2tt1bIG/Ds3ubWzEMTQZhToanns/TekyGTZPfpl+5G1yZoSkEVEsOCuP5TNSZQ2VGuU/zpLyZW38VWNWzL/f7arkemyet8uFD/h+hRRu+ZTLWZmXkT/+1LE5oAMdUMf54xrwWUYI/SCiTBZd8r5nk7U8SH4uQUl222222B1GXqtRJ29XotjFfKkKpZIwlbj5uDbO2Mv9MI5rerZHVWm2VGc9b/8he+e3jf/Hyv/07mrO/k0vWFfwdRBMd3z7M2t3+v/HPfMGHGsqgpHHJJHJGiQN7MgMiO5qe72rxFxmXUdVo7scyVGk8WqhhedkfbphkEnXWx1JbHUBrEfcxmZ2R4Mszzh1o5GeZqcpsc/17PP0ndL3W8eRdqhs6U+WTcX7p//Wb1yKf/7UsTmAAv05ynhmGuhWYzk/DGM4Ee0HXiLoODqAmwIJJbZW0QIQhCEIXqq/8bjdX2Y1XwqqupNqX1dS//USTM3V6rNV9nCszMfG1VVUtS9S9YbNw1UBAWqqX/+u3AxMKAoi6w11u1B0N/3eJRKs7g09QNVTEFNRTMuOTcgKGJldGEpVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV//tSxOkATLGnH+MMbclKiiQ0UQzhVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVMQU1FMy45NyAoYmV0YSlVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVX/+1LE6oAMqVkfowxt2ViiovQRjXJVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/7UsTqg8AAAaQAAAAgAAA0gAAABFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV");  
    
	    setTimeout(function() {
				sound.play();
		}, 100);

	}

    
}


function lbbUpdateMessageInDb(message_ids, conversation_id, sender_type = 'A'){
		
		if (message_ids != '' && conversation_id.length >= 1) {
			
			if (sender_type == 'A'){
				var ajax_admin_url = lbb_ajax.ajaxurl;
			}else{
				var ajax_admin_url = siteConfig.ajaxurl;
			}
			jQuery.ajax({
				type: 'post',
				url: ajax_admin_url,
				data: {
					'conversation_id': conversation_id,
					'message_ids' : message_ids,
					'action': 'lbb_messages_update',
				},
				success: function (response) {
					//response = JSON.parse(response);
				}
			});
		}else{
			
		}
		

}



function lbb_firebase_notifincation_init(){


	alert('attach_thread');
	return;

	const chat_data_ref = firebase.database().ref("chat_data");
    chat_data_ref.on("child_changed", (snapshot, prevChildKey) => {
        var open_friend_id = jQuery('#message-to-send-user-id').val();

       // const sender_id = snapshot.val().sender_id;

        

        

        
        const messages = snapshot.val().message;

	    // Get the keys (message IDs) of the messages
	    const messageKeys = Object.keys(messages);

	    // Find the last message ID
	    const lastMessageKey = messageKeys[messageKeys.length - 1];

	    // Get the last message object
	    const lastMessage = messages[lastMessageKey];

	    // Retrieve the text of the last message
	    const lastMessageText = lastMessage.msg;


	    if(open_friend_id != lastMessage.sender_id){
	    	
	    	/*if (typeof siteConfig.notify_notification !== 'undefined' && siteConfig.notify_notification){
		        showNotification(lastMessageText, snapshot.keym);
	        }
	        if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio && siteConfig.notify_notification == false){
				lbb_notification_ding();
	        } */
        	
        }



	    // Now, you have the last message text in the `lastMessageText` variable
	    //console.log("Last Message Text:", lastMessageText);

	    /*const dynamic_key = lastMessageText+'-'+snapshot.key;
	    const notificationKey = 'notificationDisplayed_'+dynamic_key;*/


        
		

       
    });
}

function lbb_is_page_in_focus() {
    // Check if the Page Visibility API is supported
    if (typeof document.hidden !== "undefined") {
        return !document.hidden;
    } else if (typeof document.msHidden !== "undefined") {
        return !document.msHidden;
    } else if (typeof document.webkitHidden !== "undefined") {
        return !document.webkitHidden;
    }
    
    // If the Page Visibility API is not supported, assume the page is in focus
    return true;
}

function lbb_check_last_message_from_firebase(user_type = 'user', idle_time = 500){


	alert('lbb_check_last_message_from_firebase');
	return;
	var friend_id = jQuery('#current_role_id').val();
	var response_return = false;
	var user_has_no_msg = true;
	var get_specific_user_message = friend_id;

	if (user_type == 'admin'){
		var get_specific_user_message = jQuery('#current_admin_id').val();

	}

	var chatRef = firebase.database().ref('chat_data/' + friend_id + '/message');
	chatRef.orderByChild('sender_id').equalTo(get_specific_user_message).limitToLast(1).once('value', function(snapshot) {
	    snapshot.forEach(function(childSnapshot) {
	    	user_has_no_msg = false;
	    	
	        // This will give you the last message data
	        var lastMessageData = childSnapshot.val();
			
	        var sender_id = lastMessageData.sender_id;
	        console.log(lastMessageData);



            var currentTime = new Date().getTime();
            var messageTime = new Date(lastMessageData.date).getTime();
            var timeDifferenceInSeconds = (currentTime - messageTime) / 1000; // in seconds
            //console.log(lastMessageData.date);
           // console.log(timeDifferenceInSeconds);
            // Check if the time difference is greater than 5 minutes
            if (timeDifferenceInSeconds >= idle_time) {
                response_return = true;
            }
	        
	    });
	});

	if(user_type == 'user' && user_has_no_msg){
		return lbb_check_last_message_from_firebase('admin',idle_time);
	}

	if (user_has_no_msg){
		return user_has_no_msg;
	}else{
		return response_return;
	}
	
}

function lbb_get_last_message_from_user_firebase(){

	alert('lbb_get_last_message_from_user_firebase');
	return;

	var friend_id = jQuery('#current_role_id').val();
	var response_return = false;
	var get_specific_user_message = friend_id;

	
	var sender_type = '';
	var chatRef = firebase.database().ref('chat_data/' + friend_id + '/message');
	chatRef.orderByKey().limitToLast(1).once('value', function(snapshot) {
	    snapshot.forEach(function(childSnapshot) {
	    	var lastMessageData = childSnapshot.val();
        	var sender_id = lastMessageData.sender_id;
        	if (friend_id == sender_id){
        		sender_type = 'user';
        	}else{
        		sender_type = 'admin';
        	}
        	return sender_type;
		});
	});

	return sender_type;

}

function lbb_init_firebase(){
	jQuery('#lbb-chat-main-wrapper').removeClass('lbb-chat-user-input');
}

function lbbLoadChat() {

	/*var current_user_id = 0;
	if (jQuery('#message-to-send-user-id').val() != ''){
		current_user_id = jQuery('#message-to-send-user-id').val();
	}
	*/
	if (typeof siteConfig.notify_notification !== 'undefined' && siteConfig.notify_notification){
	    jQuery.ajax({
		url: siteConfig.ajaxurl,
			type: "POST",
			data: {
				action: "lbb_read_unseen_messages",
				/*lbb_user_id: current_user_id,*/
				browser_uniq_id: lbbGetMyBrowserUniqId(),
			},
			success: function(response) {

				response = JSON.parse(response);
				var message_ids = [];

				if (response != undefined && response.length != 0) {
					jQuery.each(response, function(key, value) {
						var msg_data = value.message_text;
						var sent_time = value.sent_time;
						var sender_id = value.conversation_id;
					

					    jQuery('.user-admin-list-left-side[data-id="' + sender_id + '"] .status').text(msg_data);
						jQuery('.user-admin-list-left-side[data-id="' + sender_id + '"]').addClass('chat-new-msg');

						LBBMoveLiToFirst('.user-admin-list-left-side[data-id="' + sender_id + '"]');




						showNotification(msg_data);
					});	

					if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio){
						//lbb_notification_ding();
						LBBPlaySound();
			        }			
				}

		        
	        }
	    });
	}
}

function lbbGetUTCTime(){
	var utcDate = new Date();

// Get the UTC methods from the Date object
var utcFullYear = utcDate.getUTCFullYear();
var utcMonth = lbbAddLeadingZero(utcDate.getUTCMonth() + 1); // Months are zero-based, so add 1
var utcDay = lbbAddLeadingZero(utcDate.getUTCDate());
var utcHours = lbbAddLeadingZero(utcDate.getUTCHours());
var utcMinutes = lbbAddLeadingZero(utcDate.getUTCMinutes());
var utcSeconds = lbbAddLeadingZero(utcDate.getUTCSeconds());

// Create a string representation of the UTC time
var utcTimeString =
  utcFullYear +
  '-' +
  utcMonth +
  '-' +
  utcDay +
  ' ' +
  utcHours +
  ':' +
  utcMinutes +
  ':' +
  utcSeconds;

	return utcTimeString;

}

function lbbAddLeadingZero(number) {
  return number < 10 ? '0' + number : number;
}

function lbbGetMyBrowserUniqId(){
	var current = window.localStorage.getItem("_DEVICEID_")
	if (current) return current;
	var id = lbbUUId();
	window.localStorage.setItem("_DEVICEID_",id);   
	return id;
}

lbbUUId = function(){
	var u = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,
	function(c) {
		var r = Math.random() * 16 | 0,
		v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
		});
	return u;
}