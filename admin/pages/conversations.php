<?php
get_lbb_header(); 

$show_popup = 'lbb-notification-is-off';
if(get_option('lbb_general_settings') ){
    $lbb_general_settings = get_option('lbb_general_settings');

    $show_popup  = (!empty($lbb_general_settings['notify_chat']) && in_array('show_popup', $lbb_general_settings['notify_chat'])) ? 'lbb-notification-is-on' : 'lbb-notification-is-off';
}
?>
<div class="lbb-chat-main-wrapper chat-msg-found">
	<div class="lbb-popup-top-wrapper lbb-popup-notication-permision <?php echo $show_popup; ?>">
	    <div class="lbb-popup-top">
	      <div class="lbb-popup-top-content">
	        <div class="lbb-popup-top-message">Please be sure to enable desktop notification. This way, if there is a live chat from a user, you'll receive desktop notification (even if you are not in the List Building Bot admin interface). <a href="javascript:void(0);" class="lbb-grant-notification-permision-btn"> Click here to enable notification.</a></div>
	        <div class="lbb-popup-top-close"><i class="bx bx-x-circle"></i></div>
	      </div>
	    </div>
	</div>

	<div class="lbb-chat-widget-admin" style="display:block">
		<div class="chat-main-wrapper-admin clearfix">
	    <div class="people-list" id="people-list">

	    	<div class="user-left-heading">
				<div class="lbb-messages-page-header">
		          <span class="lbb-messages-page-title">Chats</span>
		          <div class="lbb-messages-page-dark-mode-toogler">
		            <span class="dashicons dashicons-admin-generic"></span>
		          </div>
		        </div>
			</div>

			<?php $mode = isset($_GET['mode']) ? $_GET['mode'] : 'L';
					if (isset($_GET['mode']) && $_GET['mode'] == 'B') {
						$mode = '';
					}
				 $current_conversation_id = isset($_GET['conversation_id']) ? $_GET['conversation_id'] : '';  ?>
			<div class="left-side-header" id="">
				<form name="conversation-form" action="<?php echo admin_url('admin.php'); ?>" method="get" id="conversation-form">
				<input type="hidden" name="page" value="listbuildingbot-conversation" />
					<div class="search">
						<div class="lbb-form-group lbb-mb-0">
							<input class="lbb-input-field" type="text" id="searchStudentChat" placeholder="search">
							<svg xmlns="http://www.w3.org/2000/svg" class="svg-inline--fa fa-search fa-w-16" viewBox="0 0 46.6 46.6">
								<path d="M46.1,43.2l-9-8.9a20.9,20.9,0,1,0-2.8,2.8l8.9,9a1.9,1.9,0,0,0,1.4.5,2,2,0,0,0,1.5-.5A2.3,2.3,0,0,0,46.1,43.2ZM4,21a17,17,0,1,1,33.9,0A17.1,17.1,0,0,1,33,32.9h-.1A17,17,0,0,1,4,21Z" fill="#f68b3c"></path>
							</svg>
						</div>
						
						<div class="lbb-form-group lbb-mb-0">
							<ul class="lbb-radio-btn-wrapper lbb-mode-change" style="height:auto;">
							<li  class="lbb-chat-type-live lbb-chat-type-main">
								<input type="radio" id="lbb_live" name="mode" value="L" <?php echo ($mode == 'L') ? 'checked' : ''; ?>>
								<label for="lbb_live">Live</label>
								<div class="lbb-check">
								<div class="inside"></div>
								</div>
							</li>
							<li class="lbb-chat-type-bot lbb-chat-type-main">

								<input type="radio" id="lbb_bot_live" name="mode" value="B" <?php echo ($mode == '') ? 'checked' : ''; ?>>
								<label for="lbb_bot_live">Bot</label>
								<div class="lbb-check"></div>
							</li>
							
							<li  class="lbb-chat-type-ai lbb-chat-type-main">
								<input type="radio" id="lbb_ai" name="mode" value="A" <?php echo ($mode == 'A') ? 'checked' : ''; ?>>
								<label for="lbb_ai">AI</label>
								<div class="lbb-check">
								<div class="inside"></div>
								</div>
							</li>
						</ul>
						</div>
						
					</div>
					
				</form>
			</div>
			<input type="hidden" id="filter-mode" name="filter-mode" value="<?php echo $mode; ?>">
	      <ul class="list" id="conversation-chat-left">
		        <?php 

						$req_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
						$filter['mode'] = $mode;
		        		$messageManager = new MessageManager();
		        		$userchat_list = $messageManager->loadUserChatList(50, 0,$req_user_id,$filter);

		        		//echo '<pre>';print_r($userchat_list);die();
						$conv_ids = array();
		        		foreach($userchat_list as $single_msg){
		        			$user_id = $single_msg['user_id'];
		        			$firebaseid = $single_msg['firebase_id'];
							$conversation_id = $single_msg['conversation_id'];
							$time = $single_msg['sent_time'];
							$status = $single_msg['status'];
							$conv_ids[] = $conversation_id;
							$avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g';
							if($user_id == 0){
								$contact_id = lbb_get_contact_id($conversation_id);
								$name = lbb_get_contact_name($contact_id);
							}else{

								$contact_id = lbb_get_contact_id($conversation_id);
								$contact_status = lbb_get_contact_status($contact_id);
								if($contact_status > 0){
									$name = lbb_get_contact_name($contact_id);
								}else{
									$name = lbb_get_user_name($user_id);
								}
								$avatar = lbb_user_avatar($user_id);
							}

							$last_msg = $messageManager->getMessagesByConversationIdWithOffset($conversation_id, 10, 0);
							$message = "";
							$trained_class = "";
							if($status == "T"){
								$trained_class = "lbb-trained-bot";
							}

							//$time = "";
							if($last_msg){
								$message = stripslashes($last_msg[0]['message_text']);
								$message = strip_tags($message);
								$time = $last_msg[0]['sent_time'];
							}
							echo '<li class="clearfix user-admin-list-left-side '.$trained_class.'" id="chat-list-user-'.$conversation_id.'" data-firebaseid="'.$firebaseid.'" data-id="'.$conversation_id.'" data-count="0" data-time="'.strtotime($time).'"><a href="javascript:void(0);" class="user-list-left-sidebar" data-userid="'.$user_id.'" data-firebaseid="'.$firebaseid.'" >
							<div class="lbb-delete-conversation-main"><span class="lbb-delete-conversation"><i class="bx bxs-trash-alt"></i></span></div>
									<span class="chat-icon"><img src="'.$avatar.'"></span>
									<div class="about">
										<div class="name" data-msgcount="0">'.$name.'</div>
											<div class="status">'.$message.'</div>
									</div>
									<span class="lbb-message-count"></span> 
									<span class="lbb-notification-time-text">'.LBBTimeElapsedString($time).'</span> 
								</a></li>';

		        		}
					?>
		      </ul>
	    </div>
	    <?php
	    ?>

	    <script>
			var conversation_ids = <?php echo json_encode($conv_ids) ?>;
			var current_conversation_id = '<?php echo $current_conversation_id; ?>';
		</script>
	    <div class="chat-main-wrapper-start">
		    <div class="chat empty-box-style">
		      	<div class="chat-header clearfix">
			      	<div class="chat-header-left-part">
				        <span class="chat-icon"><img src="https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g"></span>
				        
				        <div class="chat-about">
				          <div class="chat-with">Chat with <span class="chat-with-user-name">Guest</span></div>
				        </div>

			        </div>
					<div class="chat-header-right-part lbb-chat-inner-main-wrapper lbb-user-offline ">
						
						<!-- <a href="javascript:void(0)" id="view-user-profile">View Profile</a> -->

						<div class="chat-box-radio-switch chat-onoff-wrapper" style="display:none">
			            	<label class="switch">
			            		<div class="tool-tip-v2 tooltips-button tool-tip-for-offline"> <div class="toll-tip-desctool-tip-v2">You'll receive an email when someone leaves a message.</div> </div>
			            		
			            		<div class="tool-tip-v2 tooltips-button tool-tip-for-online"> <div class="toll-tip-desctool-tip-v2">You'll not receive emails when chat is in online mode. You can switch to offline if you want to receive email notifications of each message.</div> </div>
							  <input type="checkbox" id="admin_online_status" name="chat_config[admin_online_status]" value="Y">
							  <span class="chat-switch-slider"></span>
							</label>
			            </div>
					</div>
		      	</div> <!-- end chat-header -->
		      
		      	<div class="chat-history" id="chat-history">
		        	<ul id="chat-data-append">
		        	
		        	</ul>
		      	</div> <!-- end chat-history -->

				<input type="hidden" id="req-user-id" value="<?php echo isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;  ?>">
				
				<div class="chat-message clearfix">
					<form class="chat-submit-forms" method="post">
						
						<input type="hidden" id="message-conversation-id" name="message-conversation-id" value="0">
						<input type="hidden" id="message-to-send-user-id" name="message-to-send-user-id" value="0">
						<input type="hidden" id="admin-name" name="admin-name" value="Admin">
						<input type="hidden" id="admin-firebase-id" name="" value="<?php echo get_option('lbb_admin_firebase_id') ?>">
						
						<textarea name="message-to-send" id="message-to-send" placeholder="Type your message"></textarea>

						<div class="form-bottom-submit-with-checkbox">
							<button type="submit" class="lbb-send-mail-btn"><i class='bx bx-send'></i></button>
						</div>
					</form>
				</div>

				<div class="lbb-empty-box clearfix">
					<div class="lbb-empty-boxcontainer">
						<div class="lbb-empty-boxicon"><img draggable="false" role="img" class="emoji" alt="💬" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4ac.svg"></div>
						<div class="lbb-empty-boxtext">Select Message from left side</div>
					</div>
				</div>

		    </div> <!-- end chat -->
		    <div class="lbb-user-info-chat">
		    	

		    </div>
	    </div> <!-- end chat -->
	  </div>
	</div>
</div>

<?php get_lbb_footer(); ?>