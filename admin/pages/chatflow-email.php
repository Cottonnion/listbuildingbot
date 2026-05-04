<?php

if(!empty($chatflow_id)){
    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);
}

?>
<section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
    <div class="lbb-container lbb-vertical-container">
        <div class="lbb-vertical-content-up">
            <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                <form method="POST" class="lbb-form-settings">                    
                    <div class="lbb-container">
                        <div class="lbb-content">
                            <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-pb-20">
                                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                    <h2>Send email to subscribers</h2>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group">
                                            <label>Do you want to send an email to your subscribers with the chat history after they complete the conversation?</label>
                                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                                <li>
                                                    <input type="radio" id="nt-status-yes" name="lbb_meta[_lbb_email_notification_status]" value="yes" <?php echo $lbb_email_notification_status == 'yes'?'checked="checked"':''; ?>>
                                                    <label for="nt-status-yes">Yes</label>
                                                    <div class="lbb-check">
                                                       <div class="inside"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="radio" id="nt-status-no" name="lbb_meta[_lbb_email_notification_status]" value="no" <?php echo $lbb_email_notification_status == 'no'?'checked="checked"':''; ?>>
                                                    <label for="nt-status-no">No</label>
                                                    <div class="lbb-check"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="lbb-user-options lbb-mt-20" style="<?php echo $lbb_email_notification_status == 'yes' ? '' : 'display: none;'; ?>">
                                    <!-- <div class="lbb-bb lbb-mb-20"></div>
                                    <div class="lbb-heading-subheading-wrapper lbb-mb-10">
                                        <h2>Customize the message</h2>
                                    </div> -->
                                    <div class="lbb-row">
                                        <div class="lbb-col-6">
                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="lbb_email_admin_name">From Name: </label>
                                                <input type="text" id="lbb_email_admin_name" name="lbb_meta[_lbb_email_admin_name]" value="<?php echo $lbb_email_admin_name; ?>" class="lbb-input-field">
                                            </div>
                                        </div>
                                        <div class="lbb-col-6">
                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="lbb_user_email_subject">Email Subject: </label>
                                                <input type="text" id="lbb_user_email_subject" name="lbb_meta[_lbb_user_email_subject]" value="<?php echo $lbb_user_email_subject; ?>" class="lbb-input-field">
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
                                                <textarea class="lbb-input-field lbb_tiny_text_editor" id="lbb_user_email_body" name="_lbb_user_email_body"><?php echo stripslashes($lbb_user_email_body); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="lbb-bb lbb-mb-20 lbb-mt-20"></div> -->
                            
                            <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-pb-20">
                                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                    <h2>Bot Email Notification to Admin</h2>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group">
                                            <label>Do you want to send an email with conversation details to the admin?</label>
                                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                                <li>
                                                    <input type="radio" id="nt-admin-status-yes" name="lbb_meta[_lbb_email_admin_notification_status]" value="yes" <?php echo $lbb_email_admin_notification_status == 'yes'?'checked="checked"':''; ?>>
                                                    <label for="nt-admin-status-yes">Yes</label>
                                                    <div class="lbb-check">
                                                       <div class="inside"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="radio" id="nt-admin-status-no" name="lbb_meta[_lbb_email_admin_notification_status]" value="no" <?php echo $lbb_email_admin_notification_status == 'no'?'checked="checked"':''; ?>>
                                                    <label for="nt-admin-status-no">No</label>
                                                    <div class="lbb-check"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="lbb-admin-options lbb-mt-20" style="<?= $lbb_email_admin_notification_status == 'no' ? 'display: none;' : ''; ?>">
                                    <div class="lbb-row">
                                        <div class="lbb-col-6">
                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="lbb_admin_emails">Enter comma-separated list of email ids to which this email needs to be sent: </label>
                                                <input type="text" id="lbb_admin_emails" name="lbb_meta[_lbb_admin_emails]" value="<?php echo $lbb_admin_emails; ?>" class="lbb-input-field">
                                            </div>
                                        </div>
                                    
                                        <div class="lbb-col-6">
                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="lbb_admin_email_subject">Email Subject: </label>
                                                <input type="text" id="lbb_admin_email_subject" name="lbb_meta[_lbb_admin_email_subject]" value="<?php echo stripslashes($lbb_admin_email_subject); ?>" class="lbb-input-field">
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
                                                <textarea class="lbb-input-field lbb_tiny_text_editor" id="lbb_admin_email_body" name="_lbb_admin_email_body"><?php echo stripslashes($lbb_admin_email_body); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-pb-20 livechat-options" style="<?= $chatflow_type == 'botlivechat' || $chatflow_type == 'livechat' ? '' : 'display: none;'; ?>">
                                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                    <h2>Live Chat Email Notification to Admin</h2>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group">
                                            <label>Do you want to send an email to the admin when a visitor initiates a live chat?</label>
                                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                                <li>
                                                    <input type="radio" id="nt-live-admin-status-yes" name="lbb_meta[_lbb_email_admin_livechat_notification_status]" value="yes" <?php echo $lbb_email_admin_livechat_notification_status == 'yes'?'checked="checked"':''; ?>>
                                                    <label for="nt-live-admin-status-yes">Yes</label>
                                                    <div class="lbb-check">
                                                       <div class="inside"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="radio" id="nt-live-admin-status-no" name="lbb_meta[_lbb_email_admin_livechat_notification_status]" value="no" <?php echo $lbb_email_admin_livechat_notification_status == 'no'?'checked="checked"':''; ?>>
                                                    <label for="nt-live-admin-status-no">No</label>
                                                    <div class="lbb-check"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="lbb-admin-livechat-options lbb-mt-20" style="<?= $lbb_email_admin_livechat_notification_status == 'no' ? 'display: none;' : ''; ?>">
                                    <div class="lbb-row">
                                        <div class="lbb-col-6">
                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="lbb_livechat_admin_emails">Enter comma-separated list of email ids to which this email needs to be sent: </label>
                                                <input type="text" id="lbb_livechat_admin_emails" name="lbb_meta[_lbb_livechat_admin_emails]" value="<?php echo $lbb_livechat_admin_emails; ?>" class="lbb-input-field">
                                            </div>
                                        </div>
                                    
                                    </div>

                                    
                                </div>
                            </div>
                            <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-pb-20">
                                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                    <h2>Bell Notifications <span class="lbb-box-icon" data-tooltip="A bell notification is displayed in the WP admin header area to notify when there is a new message."><span class="dashicons dashicons-warning"></span></span></h2>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group">
                                            <label>Do you want to show bell notification even for "bot messages"? Set it to no if you want this to show only for live chat.</label>
                                            <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                                <li>
                                                    <input type="radio" id="lbb-bell-notification-yes" name="lbb_meta[lbb_livechat_bell_notification]" value="yes" <?php echo $lbb_livechat_bell_notification == 'yes'?'checked="checked"':''; ?>>
                                                    <label for="lbb-bell-notification-yes">Yes</label>
                                                    <div class="lbb-check">
                                                       <div class="inside"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="radio" id="lbb-bell-notification-no" name="lbb_meta[lbb_livechat_bell_notification]" value="no" <?php echo $lbb_livechat_bell_notification == 'no'?'checked="checked"':''; ?>>
                                                    <label for="lbb-bell-notification-no">No</label>
                                                    <div class="lbb-check"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="lbb-chatflow-save-action lbb-chatflow-footer-action">
                    <button class="lbb-btn lbb-btn-black lbb-back-chatflow" style="display: none;">Back</button>
                    <button class="lbb-btn lbb-btn-black lbb-save-chatflow">Save</button>
                    <button class="lbb-btn lbb-btn-black lbb-next-chatflow">Save &amp; Next</button>
                </div>
            </div>
        </div>
    </div>
</section>
