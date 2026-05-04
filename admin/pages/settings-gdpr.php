<?php
$lbb_gdpr_ipaddress = "no";
$lbb_google_font_enable = "yes";
$lbb_term_checkbox = "no";
$lbb_accept_terms = "no";
$lbb_thirdParty_status = "no";
$lbb_accept_terms_title = "I accept these terms";

$lbb_gdpr_settings = get_option('lbb_gdpr_settings');
if($lbb_gdpr_settings){
    $lbb_gdpr_ipaddress = !empty($lbb_gdpr_settings['lbb_gdpr_ipaddress']) ? $lbb_gdpr_settings['lbb_gdpr_ipaddress'] : $lbb_gdpr_ipaddress;
    $lbb_google_font_enable = !empty($lbb_gdpr_settings['lbb_google_font_enable']) ? $lbb_gdpr_settings['lbb_google_font_enable'] : $lbb_google_font_enable;
    $lbb_thirdParty_status = !empty($lbb_gdpr_settings['lbb_thirdParty_status']) ? $lbb_gdpr_settings['lbb_thirdParty_status'] : $lbb_thirdParty_status;
    $lbb_term_checkbox = !empty($lbb_gdpr_settings['lbb_term_checkbox']) ? $lbb_gdpr_settings['lbb_term_checkbox'] : $lbb_term_checkbox;
    $lbb_accept_terms = !empty($lbb_gdpr_settings['lbb_accept_terms']) ? $lbb_gdpr_settings['lbb_accept_terms'] : $lbb_accept_terms;
    $lbb_accept_terms_title = !empty($lbb_gdpr_settings['accept_terms']) ? $lbb_gdpr_settings['accept_terms'] : stripslashes($lbb_accept_terms_title);
}

if(defined('WCGD_ASSESTS')){
    echo '<input type="hidden" id="gdpr-plugin" value="active">';
} else {
    echo '<input type="hidden" id="gdpr-plugin" value="notactive">';
}

?>


  <div class="lbb-tab-inner-start">
    <form method="POST" id="gdpr-configuration">
        <input type="hidden" name="action" value="save_gdpr_data">
        <div class="lbb-container">
            <div class="lbb-content">
                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>IP address for GDPR</h2>
                        <span class="lbb-sub-heading">Don't collect IP address</span>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-chekbox-list-wrapper">
                                    <ul class="lbb-radio-btn-wrapper">
                                        <li>
                                            <input type="radio" id="gdpr-ipaddress-no" name="lbb_gdpr_settings[lbb_gdpr_ipaddress]" value="no" <?php echo $lbb_gdpr_ipaddress == 'no'?'checked="checked"':''; ?>>
                                            <label for="gdpr-ipaddress-no">No</label>
                                            <div class="lbb-check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="gdpr-ipaddress-yes" name="lbb_gdpr_settings[lbb_gdpr_ipaddress]" value="yes" <?php echo $lbb_gdpr_ipaddress == 'yes'?'checked="checked"':''; ?>>
                                            <label for="gdpr-ipaddress-yes">Yes</label>
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

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Google Font</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-chekbox-list-wrapper">
                                    <ul class="lbb-radio-btn-wrapper">
                                        <li>
                                            <input type="radio" id="google-font-no" name="lbb_gdpr_settings[lbb_google_font_enable]" value="no" <?php echo $lbb_google_font_enable == 'no'?'checked="checked"':''; ?>>
                                            <label for="google-font-no">No</label>
                                            <div class="lbb-check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="google-font-yes" name="lbb_gdpr_settings[lbb_google_font_enable]" value="yes" <?php echo $lbb_google_font_enable == 'yes'?'checked="checked"':''; ?>>
                                            <label for="google-font-yes">Yes</label>
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

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Do not use external libraries</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-chekbox-list-wrapper">
                                    <ul class="lbb-radio-btn-wrapper">
                                        <li>
                                            <input type="radio" id="thirdParty-status-no" name="lbb_gdpr_settings[lbb_thirdParty_status]" value="no" <?php echo $lbb_thirdParty_status == 'no'?'checked="checked"':''; ?>>
                                            <label for="thirdParty-status-no">No</label>
                                            <div class="lbb-check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="thirdParty-status-yes" name="lbb_gdpr_settings[lbb_thirdParty_status]" value="yes" <?php echo $lbb_thirdParty_status == 'yes'?'checked="checked"':''; ?>>
                                            <label for="thirdParty-status-yes">Yes</label>
                                            <div class="lbb-check">
                                               <div class="inside"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gdpr-library-msg lbb-alert lbb-alert-error" style="display: none;">
                        <p>For this feature to work, you need to first download and activate this plugin - GDPRLibrary from your members area. </p>

                        <p>1. Login here: <a href="https://wickedcoolplugins.com/login" target="_blank">https://wickedcoolplugins.com/login</a></p>

                        <p>2. Visit this page to download: <a href="http://wickedcoolplugins.com/sqb-download" target="_blank">http://wickedcoolplugins.com/sqb-download/</a></p>

                        <p>Please complete these steps and refresh this page. Then you can enable this setting.</p>
                        
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>For email question, add a terms checkbox</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-chekbox-list-wrapper">
                                    <ul class="lbb-radio-btn-wrapper">
                                        <li>
                                            <input type="radio" id="term-checkbox-no" name="lbb_gdpr_settings[lbb_term_checkbox]" value="no" <?php echo $lbb_term_checkbox == 'no'?'checked="checked"':''; ?>>
                                            <label for="term-checkbox-no">No</label>
                                            <div class="lbb-check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="term-checkbox-yes" name="lbb_gdpr_settings[lbb_term_checkbox]" value="yes" <?php echo $lbb_term_checkbox == 'yes'?'checked="checked"':''; ?>>
                                            <label for="term-checkbox-yes">Yes</label>
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

                <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-accept-terms-outer" style="<?php echo $lbb_term_checkbox == 'no' ? 'display: none;' : ''; ?>">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Accept Terms</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-chekbox-list-wrapper">
                                    <ul class="lbb-radio-btn-wrapper">
                                        <li>
                                            <input type="radio" id="accept-terms-no" name="lbb_gdpr_settings[lbb_accept_terms]" value="no" <?php echo $lbb_accept_terms == 'no'?'checked="checked"':''; ?>>
                                            <label for="accept-terms-no">No</label>
                                            <div class="lbb-check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="accept-terms-yes" name="lbb_gdpr_settings[lbb_accept_terms]" value="yes" <?php echo $lbb_accept_terms == 'yes'?'checked="checked"':''; ?>>
                                            <label for="accept-terms-yes">Yes</label>
                                            <div class="lbb-check">
                                               <div class="inside"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="lbb-row lbb-accept-terms">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="lbb_accept_terms_title">Accept terms title:</label>
                                <textarea type="text" id="lbb_accept_terms_title" name="lbb_gdpr_settings[lbb_accept_terms_title]" class="lbb-input-field lbb_tiny_text_editor_mini"><?php echo $lbb_accept_terms_title; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer">
            <button id="lbb-save-gdpr-data" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
            <button  id="lbb-save-gdpr-data-up" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
