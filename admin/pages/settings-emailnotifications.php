<?php
$lbb_email_notification_status = "no";
$lbb_email_admin_notification_status = "no";
$lbb_user_email_subject = "Thank you for visiting our site!";
$lbb_admin_emails = "";
$lbb_user_email_body = "<p>Hi %NAME%!<p>

<p>Thank you for chatting with us!</p>

<p>Here's a detailed report with the chat message:<br>
%all_messages%</p>";
$lbb_admin_email_body = "<p>This user just completed conversation with your bot.
Details below:</p>
<p>
Email: %EMAIL%<br>
Name: %NAME%<br>
Bot Name: %BOTNAME%<br>
Bot URL: %BOTURL%<br>
</p>
<p>Here's a detailed report with the chat message:<br>
%all_messages%</p>";
$lbb_admin_name = "";
$lbb_admin_email_subject = "User (%NAME%) just completed conversation using this bot: %BOTNAME%";

if(get_option('_lbb_email_notification_status') ){
    $lbb_email_notification_status = get_option('_lbb_email_notification_status');
}
if(get_option('_lbb_email_admin_notification_status') ){
    $lbb_email_admin_notification_status = get_option('_lbb_email_admin_notification_status');
}
if(get_option('_lbb_user_email_subject') ){
    $lbb_user_email_subject = get_option('_lbb_user_email_subject');
}
if(get_option('_lbb_user_email_body') ){
    $lbb_user_email_body = get_option('_lbb_user_email_body');
}
if(get_option('_lbb_admin_emails') ){
    $lbb_admin_emails = get_option('_lbb_admin_emails');
}
if(get_option('_lbb_admin_name') ){
    $lbb_email_admin_name = get_option('_lbb_admin_name');
}
if(get_option('_lbb_admin_email_subject') ){
    $lbb_admin_email_subject = get_option('_lbb_admin_email_subject');
}
if(get_option('_lbb_admin_email_body') ){
    $lbb_admin_email_body = get_option('_lbb_admin_email_body');
}

?>


<form method="POST" id="save-email-notifications-data">
    <div class="lbb-container">
        <div class="lbb-content">
            <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                    <h2>Send email to subscribers</h2>
                </div>
                <div class="lbb-row">
                    <div class="lbb-col-12">
                        <div class="lbb-form-group">
                            <label>Do you want to send an email to your subscribers with the chat history after they complete the conversation?</label>
                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                <li>
                                    <input type="radio" id="nt-status-yes" name="lbb_email_notification_status" value="yes" <?php echo $lbb_email_notification_status == 'yes'?'checked="checked"':''; ?>>
                                    <label for="nt-status-yes">Yes</label>
                                    <div class="lbb-check">
                                       <div class="inside"></div>
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" id="nt-status-no" name="lbb_email_notification_status" value="no" <?php echo $lbb_email_notification_status == 'no'?'checked="checked"':''; ?>>
                                    <label for="nt-status-no">No</label>
                                    <div class="lbb-check"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="lbb-user-options" style="<?= $lbb_email_notification_status == 'no' ? 'display: none;' : ''; ?>">
                    <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20 lbb-mt-20">
                        <h2>Customize the content that will be sent to the subscribers:</h2>
                    </div> -->
                    <div class="lbb-row">
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="lbb_email_admin_name">From Name: </label>
                                <input type="text" id="lbb_email_admin_name" name="lbb_admin_name" value="<?php echo $lbb_email_admin_name; ?>" class="lbb-input-field">
                            </div>
                        </div>
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="lbb_user_email_subject">Email Subject: </label>
                                <input type="text" id="lbb_user_email_subject" name="lbb_user_email_subject" value="<?php echo stripslashes($lbb_user_email_subject); ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-form-group  lbb-bb-0 lbb-left-right-text ">
                                    <label for="lbb_user_email_body">Email Body:</label>
                                    <div class="lbb-personalize-options lbb-email-personalize-tags">
                                        <a class="lbb-show-merge-tags lbb-btn lbb-small"> Personalize <span class="dashicons dashicons-arrow-down-alt2"></span></a>
                                        <div class=" lbb-mergetag-content-wrapper" style="display: none;">
                                            <div class="lbb-mergetags-content">
                                                <div class="lbb-mergetags-sidebar">
                                                    <ul class="lbb-mergetags-list-wrapper">
                                                        <li class="lbb-mergetags-header lbb-available-tags-heading">Available Tags <span class="lbb-personalize-close">X</span></li>
                                                        <li class="lbb-mergetags-header">User Info</li>
                                                        <li class="lbb-mergetags-tag">%NAME%</li>
                                                        <li class="lbb-mergetags-tag">%EMAIL%</li>
                                                        <li class="lbb-mergetags-tag">%BOTNAME%</li>
                                                        <li class="lbb-mergetags-tag">%BOTURL%</li>
                                                        <li class="lbb-mergetags-tag">%all_messages%</li>
                                                        <li class="lbb-mergetags-header showForOutcome">Outcome</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%SCORE%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%OUTCOME%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%OUTCOME_TITLE%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%TOTAL_SCORE%</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <textarea class="lbb-input-field lbb_tiny_text_editor" id="lbb_user_email_body" ><?php echo stripslashes($lbb_user_email_body); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-mt-20">
                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                    <h2>Send email to admin</h2>
                </div>

                <div class="lbb-row">
                    <div class="lbb-col-12">
                        <div class="lbb-form-group">
                            <label>Do you want to send an email with conversation details to the admin?</label>
                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                <li>
                                    <input type="radio" id="nt-admin-status-yes" name="lbb_email_admin_notification_status" value="yes" <?php echo $lbb_email_admin_notification_status == 'yes'?'checked="checked"':''; ?>>
                                    <label for="nt-admin-status-yes">Yes</label>
                                    <div class="lbb-check">
                                       <div class="inside"></div>
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" id="nt-admin-status-no" name="lbb_email_admin_notification_status" value="no" <?php echo $lbb_email_admin_notification_status == 'no'?'checked="checked"':''; ?>>
                                    <label for="nt-admin-status-no">No</label>
                                    <div class="lbb-check"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="lbb-admin-options" style="<?= $lbb_email_admin_notification_status == 'no' ? 'display: none;' : ''; ?>">
                    <div class="lbb-row">
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="lbb_admin_emails">Enter comma-separated list of email ids to which this email needs to be sent: </label>
                                <input type="text" id="lbb_admin_emails" name="lbb_admin_emails" value="<?php echo $lbb_admin_emails; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="lbb_admin_email_subject">Email Subject: </label>
                                <input type="text" id="lbb_admin_email_subject" name="lbb_admin_email_subject" value="<?php echo stripslashes($lbb_admin_email_subject); ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-form-group  lbb-bb-0 lbb-left-right-text ">
                                    <label for="lbb_admin_email_body">Email Body:</label>
                                    <div class="lbb-personalize-options lbb-email-personalize-tags">
                                        <a class="lbb-show-merge-tags lbb-btn lbb-small"> Personalize <span class="dashicons dashicons-arrow-down-alt2"></span></a>
                                        <div class=" lbb-mergetag-content-wrapper" style="display: none;">
                                            <div class="lbb-mergetags-content">
                                                <div class="lbb-mergetags-sidebar">
                                                    <ul class="lbb-mergetags-list-wrapper">
                                                        <li class="lbb-mergetags-header lbb-available-tags-heading">Available Tags <span class="lbb-personalize-close">X</span></li>
                                                        <li class="lbb-mergetags-header">User Info</li>
                                                        <li class="lbb-mergetags-tag">%NAME%</li>
                                                        <li class="lbb-mergetags-tag">%EMAIL%</li>
                                                        <li class="lbb-mergetags-tag">%BOTNAME%</li>
                                                        <li class="lbb-mergetags-tag">%BOTURL%</li>
                                                        <li class="lbb-mergetags-tag">%all_messages%</li>
                                                        <li class="lbb-mergetags-header showForOutcome">Outcome</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%SCORE%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%OUTCOME%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%OUTCOME_TITLE%</li>
                                                        <li class="lbb-mergetags-tag showForOutcome">%TOTAL_SCORE%</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <textarea class="lbb-input-field lbb_tiny_text_editor" id="lbb_admin_email_body" ><?php echo stripslashes($lbb_admin_email_body); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lbb-popup-btn-footer lbb-text-center lbb-chatflow-footer-action">
        <button id="lbb-save-settings-btn" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
        <button id="lbb-save-settings-btn-top" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
    </div>
</form>