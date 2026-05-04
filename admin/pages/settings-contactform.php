<?php
$display_form = 'no';
$contact_form_title = "👋 Please introduce yourself";
$contact_form_description = "We'll use your contact info only if all the agents are busy at the moment and cannot immediately reply.";
$contactform_email = "yes";
$contactform_name = "yes";
$contactform_phone = "no";
$contactform_personaldata = "yes";
$contactform_require_consent = "yes";
$contact_form_email = "Email";
$contact_form_name = "Name";
$contact_form_phone = "Phone";
$contact_form_submit = "Submit";

$contact_form_personal_data = "I have read the privacy policy and accept them.";

$lbb_contactform_settings = get_option('lbb_contactform_settings');
if($lbb_contactform_settings){
    $display_form = !empty($lbb_contactform_settings['display_form']) ? $lbb_contactform_settings['display_form'] : $display_form;

    $contact_form_title = !empty($lbb_contactform_settings['contact_form_title']) ? stripslashes($lbb_contactform_settings['contact_form_title']) : $contact_form_title;
    $contact_form_description = !empty($lbb_contactform_settings['contact_form_description']) ? stripslashes($lbb_contactform_settings['contact_form_description']) : $contact_form_description;


    $contactform_email = !empty($lbb_contactform_settings['contactform_email']) ? $lbb_contactform_settings['contactform_email'] : $contactform_email;
    $contactform_name = !empty($lbb_contactform_settings['contactform_name']) ? $lbb_contactform_settings['contactform_name'] : $contactform_name;
    $contactform_phone = !empty($lbb_contactform_settings['contactform_phone']) ? $lbb_contactform_settings['contactform_phone'] : $contactform_phone;
    $contactform_personaldata = !empty($lbb_contactform_settings['contactform_personaldata']) ? $lbb_contactform_settings['contactform_personaldata'] : $contactform_personaldata;
    $contactform_require_consent = !empty($lbb_contactform_settings['contactform_require_consent']) ? $lbb_contactform_settings['contactform_require_consent'] : $contactform_require_consent;
    $contact_form_email = !empty($lbb_contactform_settings['contact_form_email']) ? $lbb_contactform_settings['contact_form_email'] : $contact_form_email;
    $contact_form_name = !empty($lbb_contactform_settings['contact_form_name']) ? $lbb_contactform_settings['contact_form_name'] : $contact_form_name;
    $contact_form_phone = !empty($lbb_contactform_settings['contact_form_phone']) ? $lbb_contactform_settings['contact_form_phone'] : $contact_form_phone;
    $contact_form_submit = !empty($lbb_contactform_settings['contact_form_submit']) ? $lbb_contactform_settings['contact_form_submit'] : $contact_form_submit;
    $contact_form_personal_data = !empty($lbb_contactform_settings['contact_form_personal_data']) ? $lbb_contactform_settings['contact_form_personal_data'] : $contact_form_personal_data;
    $lbb_contact_font_size = !empty($lbb_contactform_settings['lbb_contact_font_size']) ? $lbb_contactform_settings['lbb_contact_font_size'] : $lbb_contact_font_size;
    $lbb_contact_font_weight = !empty($lbb_contactform_settings['lbb_contact_font_weight']) ? $lbb_contactform_settings['lbb_contact_font_weight'] : $lbb_contact_font_weight;
    $lbb_contact_button_text_color = !empty($lbb_contactform_settings['lbb_contact_button_text_color']) ? $lbb_contactform_settings['lbb_contact_button_text_color'] : $lbb_contact_button_text_color;
    $lbb_contact_button_bg_color = !empty($lbb_contactform_settings['lbb_contact_button_bg_color']) ? $lbb_contactform_settings['lbb_contact_button_bg_color'] : $lbb_contact_button_bg_color;
    //$contact_form_consent_data = $lbb_contactform_settings['contact_form_consent_data'];
}
include( LBB_ABS_URL . 'admin/templates/chat/css-variables.php');
?>


<form method="POST" id="contactform-configuration">
    <input type="hidden" name="action" value="save_contactform_data">
    <div class="lbb-container">
        <div class="lbb-row">
           <div class="lbb-col-7">
            <div class="lbb-content">
                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="ai_assistant_language">Display the form when online:</label>
                                <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="display_form_yes" name="lbb_contactform_settings[display_form]" value="yes" <?php echo $display_form == 'yes'?'checked="checked"':''; ?>>
                                        <label for="display_form_yes">Yes</label>
                                        <div class="lbb-check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="display_form_no" name="lbb_contactform_settings[display_form]" value="no" <?php echo $display_form == 'no'?'checked="checked"':''; ?>>
                                        <label for="display_form_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="contact_form_title">Contact form title:</label>
                                <input type="text" id="contact_form_title" name="lbb_contactform_settings[contact_form_title]" value="<?php echo $contact_form_title; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20 hide-for-inpage">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="contact_form_description">Contact form description:</label>
                                <textarea class="lbb-input-field lbb_contact_form_description" name="lbb_contactform_settings[contact_form_description]" rows="10" cols="100"><?php echo $contact_form_description; ?></textarea>
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
                            <div class="lbb-form-group lbb-bb">

                                <div class="lbb-form-group lbb-mt-20 ">
                                    <label for="contact_form_email">Enter Title for Email: </label>
                                     <input type="text" id="contact_form_email" name="lbb_contactform_settings[contact_form_email]" value="<?php echo $contact_form_email; ?>" class="lbb-input-field">
                                </div>

                            </div>
                        </div>

                        <div class="lbb-col-12">
                            <div class="lbb-form-group lbb-bb">

                                <label>Name</label>
                                <div class="lbb-chekbox-list-wrapper">
                                 <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="contactform_name_yes" name="lbb_contactform_settings[contactform_name]" value="yes" <?php echo $contactform_name == 'yes'?'checked="checked"':''; ?>>
                                        <label for="contactform_name_yes">Yes</label>
                                        <div class="lbb-check"><div class="inside"></div></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="contactform_name_no" name="lbb_contactform_settings[contactform_name]" value="no" <?php echo $contactform_name == 'no'?'checked="checked"':''; ?>>
                                        <label for="contactform_name_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                               

                                <div class="lbb-form-group lbb-mt-20 ">
                                    <label for="contact_form_name">Enter Title for Name: </label>
                                     <input type="text" id="contact_form_name" name="lbb_contactform_settings[contact_form_name]" value="<?php echo $contact_form_name; ?>" class="lbb-input-field">
                                </div>

                            </div>
                        </div>

                        <div class="lbb-col-12">
                            <div class="lbb-form-group">

                                <label>Phone</label>
                                <div class="lbb-chekbox-list-wrapper">
                                   <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="contactform_phone_yes" name="lbb_contactform_settings[contactform_phone]" value="yes" <?php echo $contactform_phone == 'yes'?'checked="checked"':''; ?>>
                                        <label for="contactform_phone_yes">Yes</label>
                                        <div class="lbb-check"><div class="inside"></div></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="contactform_phone_no" name="lbb_contactform_settings[contactform_phone]" value="no" <?php echo $contactform_phone == 'no'?'checked="checked"':''; ?>>
                                        <label for="contactform_phone_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                               

                                <div class="lbb-form-group lbb-mt-20 ">
                                    <label for="contact_form_phone">Enter Title for Phone: </label>
                                     <input type="text" id="contact_form_phone" name="lbb_contactform_settings[contact_form_phone]" value="<?php echo $contact_form_phone; ?>" class="lbb-input-field">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Data Privacy</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label>Show notice about personal data processing</label>
                                <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="contactform_personaldata_yes" name="lbb_contactform_settings[contactform_personaldata]" value="yes" <?php echo $contactform_personaldata == 'yes'?'checked="checked"':''; ?>>
                                        <label for="contactform_personaldata_yes">Yes</label>
                                        <div class="lbb-check"><div class="inside"></div></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="contactform_personaldata_no" name="lbb_contactform_settings[contactform_personaldata]" value="no" <?php echo $contactform_personaldata == 'no'?'checked="checked"':''; ?>>
                                        <label for="contactform_personaldata_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="lbb-contact-info" style="<?php echo $contactform_personaldata == 'no'?'display:"none"':''; ?>">
                                <div class="lbb-form-group lbb-mt-20 " id="privacy-policy">
                                    <label for="contact_form_personal_data">Enter Title for Personal data: </label>
                                     <input type="text" id="contact_form_personal_data" name="lbb_contactform_settings[contact_form_personal_data]" value="<?php echo $contact_form_personal_data; ?>" class="lbb-input-field lbb-tinymce-editor">
                                </div>
                                <span class="lbb-error lbb-personal-data" style="display: none;">Please enter title for personal data</span>
                                
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group">
                                            <label>Require consent to the processing of personal data</label>
                                            <ul class="lbb-radio-btn-wrapper">
                                                <li>
                                                    <input type="radio" id="contactform_require_consent_yes" name="lbb_contactform_settings[contactform_require_consent]" value="yes" <?php echo $contactform_require_consent == 'yes'?'checked="checked"':''; ?>>
                                                    <label for="contactform_require_consent_yes">Yes</label>
                                                    <div class="lbb-check"><div class="inside"></div></div>
                                                </li>
                                                <li>
                                                    <input type="radio" id="contactform_require_consent_no" name="lbb_contactform_settings[contactform_require_consent]" value="no" <?php echo $contactform_require_consent == 'no'?'checked="checked"':''; ?>>
                                                    <label for="contactform_require_consent_no">No</label>
                                                    <div class="lbb-check">
                                                       <div class="inside"></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Button</h2>
                    </div>
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group"> 
                                <label for="contact_form_submit">Enter Title for Submit Button: </label>
                                 <input type="text" id="contact_form_submit" name="lbb_contactform_settings[contact_form_submit]" value="<?php echo $contact_form_submit; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                    <div class="lbb-row">
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label>Font Size:</label>
                                <div class="lbb-slider-outer">
                                    <div class="lbb-slider" data-slider-min="14" data-slider-max="30" data-slider-step="1" data-slider-value="<?php echo $lbb_contact_font_size; ?>" data-value-px="px" data-css-variable="lbb-contact-font-size"></div>
                                    <input id="bb_contact_font_size" name="lbb_contactform_settings[lbb_contact_font_size]" class="lbb-slider-input lbb-input-field" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label>Font Weight:</label>
                                <div class="lbb-slider-outer">
                                    <div class="lbb-slider" data-slider-min="400" data-slider-max="900" data-slider-step="100" data-slider-value="<?php echo $lbb_contact_font_weight; ?>" data-value-px="" data-css-variable="lbb-contact-font-weight"></div>
                                    <input id="lbb_contact_font_weight" name="lbb_contactform_settings[lbb_contact_font_weight]" class="lbb-slider-input lbb-input-field" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label for="lbb_contact_button_text_color">Button Text Color:</label>
                                <input type="text" name="lbb_contactform_settings[lbb_contact_button_text_color]" id="lbb_contact_button_text_color" value="<?php echo $lbb_contact_button_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_contact_button_text_color; ?>" data-css-variable="lbb-contact-button-text-color" />
                            </div>
                        </div>
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label for="lbb_contact_button_bg_color">Button Background Color Color:</label>
                                <input type="text" name="lbb_contactform_settings[lbb_contact_button_bg_color]" id="lbb_contact_button_bg_color" value="<?php echo $lbb_contact_button_bg_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_contact_button_bg_color; ?>" data-css-variable="lbb-contact-button-background-color" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lbb-col-5 lbb-col-possition-sticky">
            <div class="lbb-preview-box">
                <div class="preview-btn-wrapper">
                    <img src="<?php echo LBB_URL; ?>admin/images/header-icon.jpg">
                </div>
                <div class="lbb-live-preview-for-contact-form-admin">
                    <div class="lbb-user-info-popup">
                        <div class="lbb-popup-content">
                            <h2 class="lbb-chat-user-info-form-title"><?php echo $contact_form_title; ?></h2>
                            <p class="lbb-chat-user-info-form-description"><?php echo $contact_form_description; ?></p>
                        </div>
                        <div class="lbb-chat-user-info-form-group lbb-chat-user-info-name-outer" style="<?php echo $contactform_name == 'no' ? 'display: none;' : ''; ?>">
                            <label for="lbb-chat-user-info-full-name" class="lbb-chat-user-info-label lbb-chat-user-info-full-name"><?php echo $contact_form_name; ?></label>
                                <input type="text" id="lbb-chat-user-info-full-name" name="lbb-chat-user-info-full-name" class="lbb-chat-user-info-input">
                        </div>
                        <div class="lbb-chat-user-info-form-group">
                            <label for="lbb-chat-user-info-email" class="lbb-chat-user-info-label lbb-chat-user-info-email"><?php echo $contact_form_email; ?></label>
                                <input type="email" id="lbb-chat-user-info-email" name="lbb-chat-user-info-email" class="lbb-chat-user-info-input">
                        </div>
                        <div class="lbb-chat-user-info-form-group lbb-chat-user-info-phone-outer" style="<?php echo $contactform_phone == 'no' ? 'display: none;' : ''; ?>">
                            <label for="lbb-chat-user-info-phone" class="lbb-chat-user-info-label lbb-chat-user-info-phone"><?php echo $contact_form_phone; ?></label>
                                <input type="number" id="lbb-chat-user-info-phone" name="lbb-chat-user-info-phone" class="lbb-chat-user-info-input">
                        </div>

                        <div class="lbb-consent-processing lbb-form-group-checkbox" style="<?php echo $contactform_personaldata == 'no' ? 'display: none;' : ''; ?>">
                            <input id="lbb-chat-privacy" type="checkbox" name="" value="yes" class="custom-checkbox-input">
                           <label for="lbb-chat-privacy" class="custom--checkbox"  id="privacy_label"><?php echo $contact_form_personal_data; ?></label>
                        </div>

                        <div class="lbb-chat-user-info-form-btn">
                            <button disabled class="lbb-chat-user-info-button lbb-submit-btn"><?php echo $contact_form_submit; ?></button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer">
        <button id="lbb-save-contactform-data" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
        <button  id="lbb-save-contactform-data-up" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
    </div>
</form>
