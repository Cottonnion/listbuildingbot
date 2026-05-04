<?php
$livechat_agent_timer = 30;
$livechat_user_timer = 300;
$livechat_agent_message = "Sorry, all agents are currently busy. Please leave a message below and we'll get back to you asap.";
$livechat_user_message = "Thank you for contacting us. Resolving your concern is important to us. Due to inactivity, we will have to close out this chat. When it is convenient for you, please initiate another chat session with us. We are here to assist you 24x7.";
$livechat_button_text = "Start New";
$livechat_welcome_message = "Welcome to live chat!";
$agent_response = "yes";
$lbb_livechat_admin_name = "Agent";
$lbb_joined_chat = "An agent has joined chat";
$lbb_livechat_admin_image = "";
$lbb_image_upload_live = "";
$audio_options = "audio_one";
$lbb_audio_upload = "";

$lbb_emoji_enable = 'no';
$lbb_emoji = "grinning smiley smile grin laughing sweat_smile joy rofl relaxed blush innocent slight_smile upside_down wink relieved crazy_face star_struck heart_eyes kissing_heart kissing kissing_smiling_eyes kissing_closed_eyes yum stuck_out_tongue_winking_eye stuck_out_tongue_closed_eyes stuck_out_tongue money_mouth hugging nerd sunglasses cowboy smirk unamused disappointed pensive worried face_with_raised_eyebrow face_with_monocle confused slight_frown";


$show_emoji = ($lbb_emoji_enable == 'no') ? 'display:none' : '';
$notify_chat = array('play_audio', 'show_popup');
if(get_option('lbb_general_settings') ){
    $lbb_general_settings = get_option('lbb_general_settings');
    $livechat_agent_timer = $lbb_general_settings['livechat_agent_timer'];
    $livechat_user_timer = $lbb_general_settings['livechat_user_timer'];
    $livechat_agent_message = stripslashes($lbb_general_settings['livechat_agent_message']);
    $livechat_user_message = stripslashes($lbb_general_settings['livechat_user_message']);
    $livechat_button_text = $lbb_general_settings['livechat_button_text'];
    $livechat_welcome_message = $lbb_general_settings['livechat_welcome_message'];
    $lbb_livechat_admin_name = !empty($lbb_general_settings['lbb_livechat_admin_name']) ? $lbb_general_settings['lbb_livechat_admin_name'] : '';
    $lbb_joined_chat = !empty($lbb_general_settings['lbb_joined_chat']) ? $lbb_general_settings['lbb_joined_chat'] : '';
    $lbb_image_upload_live = !empty($lbb_general_settings['lbb_image_upload_live']) ? $lbb_general_settings['lbb_image_upload_live'] : '';
    $lbb_livechat_admin_image = !empty($lbb_general_settings['lbb_livechat_admin_image']) ? $lbb_general_settings['lbb_livechat_admin_image'] : '';
    $agent_response = !empty($lbb_general_settings['agent_response']) ? $lbb_general_settings['agent_response'] : 'yes';
    $audio_options = !empty($lbb_general_settings['audio_options']) ? $lbb_general_settings['audio_options'] : 'audio_one';
    $lbb_audio_upload = !empty($lbb_general_settings['lbb_audio_upload']) ? $lbb_general_settings['lbb_audio_upload'] : '';
    $lbb_emoji_enable = $lbb_general_settings['lbb_emoji_enable'];
    $lbb_emoji = $lbb_general_settings['lbb_emoji'];
    $notify_chat = (!empty($lbb_general_settings['notify_chat'])) ? $lbb_general_settings['notify_chat'] : array();
}
?>
<form method="POST" id="save-settings-data">
    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>When a new chat request comes in, how do you want to be notified</h2>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <div class="lbb-chekbox-list-wrapper">
                        <?php 
                            $notify_chat_array = array(
                                array(
                                    'label' => 'Play audio',
                                    'id' => 'play_audio',
                                    'value' => 'play_audio',
                                    'checked' => in_array('play_audio', $notify_chat) ? 'checked':''
                                ),array(
                                    'label' => 'Show popup to notify on all admin pages',
                                    'id' => 'show_popup',
                                    'value' => 'show_popup',
                                    'checked' => in_array('show_popup', $notify_chat) ? 'checked':''
                                )
                            );

                            
                            foreach($notify_chat_array as $notify_chat_options){
                                ?>

                                <div class="lbb-notify-chat-wrapper">
                                    <span class="checkbox-custom-style">
                                    <input id="<?php echo $notify_chat_options['id']; ?>" type="checkbox" name="lbb_general_settings[notify_chat][]" value="<?php echo $notify_chat_options['value']; ?>" class="custom-checkbox-input" <?php echo $notify_chat_options['checked']; ?>> <label for="<?php echo $notify_chat_options['id']; ?>" class="custom--checkbox"></label>
                                    </span>
                                    <span class="lbb-notify-chat-name"><?php echo $notify_chat_options['label']; ?></span>
                                </div>



                                <?php
                            }

                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>Play audio when an agent responds</h2>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <div class="lbb-chekbox-list-wrapper">
                        <ul class="lbb-radio-btn-wrapper">
                            <li>
                                <input type="radio" id="agent_response_yes" name="lbb_general_settings[agent_response]" value="yes" <?php echo $agent_response == 'yes'?'checked="checked"':''; ?>>
                                <label for="agent_response_yes">Yes</label>
                                <div class="lbb-check"></div>
                            </li>
                            <li>
                                <input type="radio" id="agent_response_no" name="lbb_general_settings[agent_response]" value="no" <?php echo $agent_response == 'no'?'checked="checked"':''; ?>>
                                <label for="agent_response_no">No</label>
                                <div class="lbb-check">
                                   <div class="inside"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="lbb-chekbox-list-wrapper lbb-mt-20 agent-response-yes-outer" style="<?php echo $agent_response == 'no' ? 'display: none;': ''; ?>">
                        <ul class="lbb-radio-btn-wrapper lbb-radio-audio-wrapper">
                            <li>
                                <input type="radio" id="audio_options_yes" name="lbb_general_settings[audio_options]" value="audio_one" <?php echo $audio_options == 'audio_one'?'checked="checked"':''; ?>>
                                <label for="audio_options_yes">Audio 1</label>
                                <div class="lbb-check"></div>
                                <audio class="lbb-audio-controls" controls>
                                      <source src="<?php echo LBB_URL; ?>/admin/audio/audio_one.mp3" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </li>
                            <li>
                                <input type="radio" id="audio_options_no" name="lbb_general_settings[audio_options]" value="audio_two" <?php echo $audio_options == 'audio_two'?'checked="checked"':''; ?>>
                                <label for="audio_options_no">Audio 2</label>
                                <div class="lbb-check">
                                   <div class="inside"></div>
                                </div>
                                <audio class="lbb-audio-controls" controls>
                                      <source src="<?php echo LBB_URL; ?>/admin/audio/audio_two.mp3" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>

                            </li>
                            <li>
                                <input type="radio" id="audio_options_custom" name="lbb_general_settings[audio_options]" value="audio_custom" <?php echo $audio_options == 'audio_custom'?'checked="checked"':''; ?>>
                                <label for="audio_options_custom">Upload Audio</label>
                                <div class="lbb-check">
                                   <div class="inside"></div>
                                </div>

                                <div class="lbb-form-group custom-audio-outer">
                                    <div class="lbb-common-audio-upload-outer lbb-audio-upload-container lbb-audio-upload-container-with-preview <?php echo (!empty($lbb_audio_upload)) ? '' : 'lbb-no-img' ?> <?php echo $audio_options == 'audio_custom' ? 'lbb-common-audio-upload-has-item': 'display: none;'; ?>">
                                        <div class="lbb-bot-user-audio ">
                                            <a class="lbb-audio-upload-button lbb-common-audio-upload" href="javascript:void(0)">Upload audio</a>
                                            <input type="hidden" id="lbb_audio_upload" name="lbb_general_settings[lbb_audio_upload]" value="<?php echo $lbb_audio_upload; ?>">
                                        </div>
                                        <div class="lbb-audio-preview-container">
                                            <audio controls style="<?php echo !empty($lbb_audio_upload) ? '': 'display: none;' ?>">
                                                  <?php if($lbb_audio_upload){
                                                    echo '<source src="'.$lbb_audio_upload.'" type="audio/mpeg">';
                                                  } ?>
                                            </audio>
                                            <div class="lbb-audio-actions">
                                                <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    

                </div>
            </div>
        </div>
    </div>

    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>Live Chat Settings</h2>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <label for="livechat_welcome_message">Welcome Message:</label>
                    <input type="text" id="livechat_welcome_message" name="lbb_general_settings[livechat_welcome_message]" value="<?php echo $livechat_welcome_message; ?>" class="lbb-input-field" placeholder="Enter message...">
                </div>
            </div>

            <div class="lbb-col-12">
               <div class="lbb-form-group">
                  <div class="lbb-form-group">
                        <label for="lbb_livechat_admin_name">Admin Display Name </label>
                        <input id="lbb_livechat_admin_name" name="lbb_general_settings[lbb_livechat_admin_name]" class="lbb-input-field" type="text" value="<?php echo $lbb_livechat_admin_name; ?>">
                    </div>

                    
               </div>
            </div>

            <div class="lbb-col-12">
               <div class="lbb-form-group">
                  <div class="lbb-form-group">
                        <label for="lbb_livechat_admin_name">Admin has Joined Chat </label>
                        <input id="lbb_livechat_admin_name" name="lbb_general_settings[lbb_joined_chat]" class="lbb-input-field" type="text" value="<?php echo $lbb_joined_chat; ?>">
                    </div>

                    
               </div>
            </div>

            <div class="lbb-col-6">
                <div class="lbb-form-group">
                    <label>Chatbot Admin Image</label>
                    <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo (!empty($lbb_image_upload_live)) ? '' : 'lbb-no-img' ?>">
                        <div class="lbb-bot-user-image ">
                            <a class="lbb-image-upload-button lbb-common-image-upload" href="javascript:void(0)">Upload Image</a>
                            <input type="hidden" id="lbb_image_upload_live" name="lbb_general_settings[lbb_image_upload_live]" value="<?php echo $lbb_image_upload_live; ?>">
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
        </div>
    </div>

    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>Agent Busy / Not available:</h2>
            <p class="website-explanation">If the agent is not available for the configured number of seconds, show a message.</p>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-6">
                <div class="lbb-form-group js-select2-wrapper">
                    <label for="livechat_agent_timer">Timer: (in seconds)</label>
                    <input type="text" id="livechat_agent_timer" name="lbb_general_settings[livechat_agent_timer]" value="<?php echo $livechat_agent_timer; ?>" class="lbb-input-field">
                </div>
            </div>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-6">
                <div class="lbb-form-group">
                    <label for="livechat_agent_message">Agent Busy Message:</label>
                    <textarea class="lbb-input-field" id="livechat_agent_message" name="lbb_general_settings[livechat_agent_message]"><?php echo $livechat_agent_message; ?></textarea>
                </div>
            </div>

        </div>
    </div>

    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>What message to show if no response from user:</h2>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-6">
                <div class="lbb-form-group js-select2-wrapper">
                    <label for="livechat_user_timer">Timer: (in seconds)</label>
                    <input type="text" id="livechat_user_timer" name="lbb_general_settings[livechat_user_timer]" value="<?php echo $livechat_user_timer; ?>" class="lbb-input-field">
                </div>
            </div>
            <div class="lbb-col-6">
                <div class="lbb-form-group">
                    <label for="livechat_agent_message">Button Text:</label>
                    <input type="text" id="livechat_button_text" name="lbb_general_settings[livechat_button_text]" value="<?php echo $livechat_button_text; ?>" class="lbb-input-field" placeholder="Enter message...">
                </div>
            </div>
        </div>
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>Upon timeout:</h2>
        </div>
        <div class="lbb-row">
            <div class="lbb-col-6">
                <div class="lbb-form-group">
                    <!-- <label for="livechat_agent_message">Agent Busy message :</label> -->
                    <textarea class="lbb-input-field" id="livechat_user_message" name="lbb_general_settings[livechat_user_message]" style="height:115px;"><?php echo $livechat_user_message; ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
            <h2>Emoji with text area</h2>
        </div>

        <div class="lbb-row">
            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <div class="lbb-chekbox-list-wrapper">
                        <ul class="lbb-radio-btn-wrapper">
                            <li>
                                <input type="radio" id="enable-emoji-no" name="lbb_general_settings[lbb_emoji_enable]" value="no" <?php echo $lbb_emoji_enable == 'no'?'checked="checked"':''; ?>>
                                <label for="enable-emoji-no">No</label>
                                <div class="lbb-check"></div>
                            </li>
                            <li>
                                <input type="radio" id="enable-emoji-yes" name="lbb_general_settings[lbb_emoji_enable]" value="yes" <?php echo $lbb_emoji_enable == 'yes'?'checked="checked"':''; ?>>
                                <label for="enable-emoji-yes">Yes</label>
                                <div class="lbb-check">
                                   <div class="inside"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="lbb-row lbb-emoji-section" style="<?php echo $show_emoji; ?>">
            <div class="lbb-col-12">
                <div class="lbb-form-group js-select2-wrapper">
                  <label for="certain_type_value">Emoji Options</label>
                  <textarea id="lbb_emoji_textarea" class="lbb-input-field" name="lbb_general_settings[lbb_emoji]" rows="10"><?php echo $lbb_emoji ?></textarea>
               </div>
            </div>
        </div>
    </div>
    <div class="lbb-popup-btn-footer lbb-text-center lbb-chatflow-footer-action">
        <button id="lbb-save-settings-btn" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
        <button id="lbb-save-settings-btn-top" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
    </div>
</form>