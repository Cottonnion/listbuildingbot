var chatListenersNew = {};

function lbb_notification_count(){
	jQuery.ajax({
			type: 'post',
			url: adminConfig.ajaxurl,
			data: {
				'action': 'lbb_notifications_count_heartbeat',
			},
			success: function (response) {
				jQuery('#lbb-ab-icon-top').append('<span class="lbb-count-number">'+response+'</span>');
			}
	});
}

function lbb_notification_firebase(){
	jQuery.ajax({
			type: 'post',
			url: adminConfig.ajaxurl,
			data: {
				'action': 'lbb_notifications_firebase',
			},
			success: function (response) {
				
				response = JSON.parse(response);
				for (var i = 0; i < response.length; i++) {
			      var newItemsAdmin = false;
			      var friend_id = response[i].firebase_id;
			    
			    

				if (chatListenersNew[friend_id]) {
				    return; // Listener already set up, no need to do it again
				}

				var chat_db = firebase.database().ref('chat_data/' + friend_id + '/message');
				chat_db.on('child_added', (snapshot, prevChild) => {
					load_new_admin_child(snapshot);
				});

				chat_db.on('child_removed', (snapshot) => {
					remove_child(snapshot);
				});

				chat_db.once('value', function(messages) {
				  newItemsAdmin = true;
				});

				// Store the listener in the chatListeners object
			  	chatListenersNew[friend_id] = chat_db;

			  	}


			}
	});
}

jQuery(document).ready(function(){
	
lbb_notification_count();


	jQuery.ajax({
	url: adminConfig.ajaxurl,
		type: "POST",
		dataType: "JSON",
		data: {
			action: "lbb_auth_current_user",
			type: "POST",
			uid: adminConfig.admin_firebase_id,
		},
		success: function(response) {
            console.log(response)
            var  token = response.token;
			firebase.auth().signInWithCustomToken(token)
			.then((userCredential) => {
				console.log('Connected');
				//lbb_notification_firebase();
				setInterval(lbb_notification_firebase, 5000);
				
			}).catch((error) => {
			  console.error('Authentication error:', error);
			});
		}
	});

});