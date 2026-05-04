<?php 
if(defined('SMART_AUTOMATION_PATH')){ 
    $automation_class = "";
}else{
    $automation_class = "lbb-vertical-container-center lbb-mb-40";
}
?>

<section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
      <div class="lbb-container lbb-vertical-container">
          <div class="lbb-vertical-content-up">
              <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40 <?php echo $automation_class; ?>">
                <form method="POST" class="lbb-form-settings">
                    <div class="lbb-container">
                        <div class="lbb-content">

                            <?php if(defined('SMART_AUTOMATION_PATH')){ ?>
                                <div class="lbb-box-unset">
                                    <div class="lbb-row-unset">
                                        <div class="lbb-col-12-unset">

                                            <div class="lbb-box lbb-section-bg-box lbb-where-to-show lbb-mb-20">
                            
                                                <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                    <h2>When should the automation be triggered?</h2>
                                                    
                                                </div>

                                                <div class="lbb-row lbb-mt-20">
                                                    <div class="lbb-col-12">
                                                        <div class="lbb-form-group">
                                                            <ul class="lbb-radio-btn-wrapper lbb-overflow-unset">
                                                              <li>
                                                                <input type="radio" id="after_email" name="lbb_meta[automation_triggered]" value="after_email" <?php echo $automation_triggered == 'after_email'?'checked="checked"':''; ?>>
                                                                <label for="after_email">After users enter their email</label>
                                                                <div class="lbb-check"></div>
                                                              </li>
                                                              
                                                              <li>
                                                                <input type="radio" id="after_last_question" name="lbb_meta[automation_triggered]" value="after_last_question" <?php echo $automation_triggered == 'after_last_question'?'checked="checked"':''; ?>>
                                                                <label for="after_last_question">After they answer the last question <span class="lbb-box-icon lbb-tooltip-html-main"><span class="dashicons dashicons-warning"></span> <div class="lbb-tooltip-html">If you have a 'back to main menu' option where you send users back to another node, then automation won't get triggered as LBB won't know if it's the last question. In this case, it's better to use the next option (I'll set it at question level).</div></span></label>
                                                                <div class="lbb-check"><div class="inside"></div></div>
                                                              </li>
                                                              <li>
                                                                <input type="radio" id="after_answer_pick" name="lbb_meta[automation_triggered]" value="after_answer_pick" <?php echo $automation_triggered == 'after_answer_pick'?'checked="checked"':''; ?>>
                                                                <label for="after_answer_pick">I'll set it at question level in the funnel <span class="lbb-box-icon lbb-tooltip-html-main"><span class="dashicons dashicons-warning"></span> <div class="lbb-tooltip-html">By default LBB triggers automation only after the users enter email or after it determines the node to be a last node in the funnel.<br/> If you want to force LBB to trigger automation after a specific question, you can enable it here.<br/> You can enable automations in the Funnel Tab >> Edit Question and enable automation for each question as needed.</div></span></label>
                                                                <div class="lbb-check"><div class="inside"></div></div>
                                                              </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="lbb-box lbb-section-bg-box">
                                                <div class="lbb-form-group">
                                                      <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                        <h2>Automation Platform</h2>
                                                        <p class="website-explanation">Target your visitors by choosing the web pages where you’d like your chatflow to appear</p>
                                                    </div>  
                                                        
                                                        <?php 
                                                        
                                                        $mailpoet_enable = 'need-plugin';
                                                        $fluentcrm_enable = 'need-plugin';
                                                        if (class_exists(\MailPoet\API\API::class)) {
                                                            $mailpoet_enable = 'plugin-active';
                                                        }

                                                        if(function_exists('FluentCrmApi')){
                                                            $fluentcrm_enable = 'plugin-active';
                                                        }
                                                        $explode_automation_status = array();
                                                        if(!empty($lbb_automation_status)){
                                                            $explode_automation_status = explode(',',$lbb_automation_status);
                                                        }
                                                        //echo '<pre>';print_r($explode_automation_status);

                                                        $automation_array = array(
                                                            array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'ActiveCampaign',
                                                                'data-api-url' => 'Y',
                                                                'data-api-key' => 'Y',
                                                                'data-api-token' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_activecampaign') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_activecampaign') != ""? '': 'display:none',
                                                                'value' => 'activecampaign',
                                                                'checked' => in_array('activecampaign', $explode_automation_status) ? 'checked':''
                                                            ),
                                                            array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'AWeber',
                                                                'data-api-url' => 'N',
                                                                'data-authorize-url' => 'Y',
                                                                'data-api-key' => 'Y',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_aweber') != "" ? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_aweber') != ""? '': 'display:none',
                                                                'value' => 'aweber',
                                                                'checked' => in_array('aweber', $explode_automation_status) ? 'checked':''
                                                            ),
                                                            array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Mailchimp',
                                                                'data-api-url' => 'N',
                                                                'data-api-key' => 'Y',
                                                                'data-api-token' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_mailchimp') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_mailchimp') != ""? '': 'display:none',
                                                                'value' => 'mailchimp',
                                                                'checked' => in_array('mailchimp', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'ConvertKit',
                                                                'data-api-url' => 'N',
                                                                'data-api-key' => 'Y',
                                                                'data-api-secret' => 'Y',
                                                                'data-api-token' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_convertkit') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_convertkit') != ""? '': 'display:none',
                                                                'value' => 'convertkit',
                                                                'checked' => in_array('convertkit', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'show_automation' => class_exists('Dap_Product') ? '': 'display:none',
                                                                'label' => 'DAP',
                                                                'data-api-url' => 'N',
                                                                'data-api-key' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_dap') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => '',
                                                                'value' => 'dap',
                                                                'checked' => in_array('dap', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Drip',
                                                                'data-api-url' => 'N',
                                                                'data-api-key' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'Y',
                                                                'data-api-client-id' => 'Y',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_drip') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_drip') != ""? '': 'display:none',
                                                                'value' => 'drip',
                                                                'checked' => in_array('drip', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Brevo',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_sendinblue') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_sendinblue') != ""? '': 'display:none',
                                                                'value' => 'sendinblue',
                                                                'checked' => in_array('sendinblue', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'GetResponse',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_getresponse') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_getresponse') != ""? '': 'display:none',
                                                                'value' => 'getresponse',
                                                                'checked' => in_array('getresponse', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'MailerLite',
                                                                'data-api-url' => 'N',
                                                                'data-api-key' => 'Y',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_mailerlite') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_mailerlite') != ""? '': 'display:none',
                                                                'value' => 'mailerlite',
                                                                'checked' => in_array('mailerlite', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'SendFox',
                                                                'data-api-key' => 'N',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'Y',
                                                                'data-auth-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_sendfox') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_sendfox') != ""? '': 'display:none',
                                                                'value' => 'sendfox',
                                                                'checked' => in_array('sendfox', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Moosend',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_moosend') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_moosend') != ""? '': 'display:none',
                                                                'value' => 'moosend',
                                                                'checked' => in_array('moosend', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'VBOUT',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_vbout') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_vbout') != ""? '': 'display:none',
                                                                'value' => 'vbout',
                                                                'checked' => in_array('vbout', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Klaviyo',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_klaviyo') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_klaviyo') != ""? '': 'display:none',
                                                                'value' => 'klaviyo',
                                                                'checked' => in_array('klaviyo', $explode_automation_status) ? 'checked':''
                                                            ),/*array(
                                                            'connect-btn-class' => '',
                                                                'label' => 'Kartra',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-api-client-id' => 'Y',
                                                                'data-api-password' => 'Y',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_kartra') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_kartra') != ""? '': 'display:none',
                                                                'value' => 'kartra',
                                                                'checked' => in_array('kartra', $explode_automation_status) ? 'checked':''
                                                            ),*/array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Acumbamail',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_acumbamail') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_acumbamail') != ""? '': 'display:none',
                                                                'value' => 'acumbamail',
                                                                'checked' => in_array('acumbamail', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'HubSpot',
                                                                'data-api-key' => 'Y',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_option('lbb_automation_hubspot') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => get_option('lbb_automation_hubspot') != ""? '': 'display:none',
                                                                'value' => 'hubspot',
                                                                'checked' => in_array('hubspot', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'Zapier, Integrately, Pabbly Connect, Encharge, KonnectzIT, SyncSpider, Autonami',
                                                                'data-api-key' => 'N',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'Y',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_post_meta($chatflow_id,'lbb_automation_webhook',true) == "" ? 'Click Here to Connect': 'Connected',
                                                                'style' => get_option('lbb_automation_webook') != ""? '': 'display:none',
                                                                'value' => 'webhook',
                                                                'checked' => in_array('webhook', $explode_automation_status) ? 'checked':'',
                                                                'connect-btn-class' => in_array('webhook', $explode_automation_status) ? 'lbb-zapier':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'GoHighLevel',
                                                                'data-api-key' => 'N',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'Y',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => 'check_email_has',
                                                                'need-plugin' => '',
                                                                'text' => get_post_meta($chatflow_id,'lbb_go_high_level',true) == "" ? 'Click Here to Connect': 'Connected',
                                                                'style' => get_option('lbb_go_high_level') != ""? '': 'display:none',
                                                                'value' => 'gohighlevel',
                                                                'checked' => in_array('gohighlevel', $explode_automation_status) ? 'checked':'',
                                                                'connect-btn-class' => in_array('gohighlevel', $explode_automation_status) ? 'lbb-zapier':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'FluentCRM',
                                                                'data-api-key' => 'N',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => '',
                                                                'need-plugin' => $fluentcrm_enable,
                                                                'text' => get_option('lbb_automation_fluentcrm') != ""? 'Connected': 'Click Here to Connect',
                                                                'style' => function_exists('FluentCrmApi') != ""? '': 'display:none',
                                                                'value' => 'fluentcrm',
                                                                'checked' => in_array('fluentcrm', $explode_automation_status) ? 'checked':''
                                                            ),array(
                                                                'connect-btn-class' => '',
                                                                'label' => 'MailPoet',
                                                                'data-api-key' => 'N',
                                                                'data-api-url' => 'N',
                                                                'data-api-secret' => 'N',
                                                                'data-api-token' => 'N',
                                                                'data-api-client-id' => 'N',
                                                                'data-api-password' => 'N',
                                                                'data-auth-token' => 'N',
                                                                'data-enter-url' => 'N',
                                                                'data-authorize-url' => 'N',
                                                                'check-email-has' => '',
                                                                'need-plugin' => $mailpoet_enable,
                                                                'text' => 'Click here to Connect',
                                                                'style' => get_option('lbb_automation_mailpoet') != ""? '': 'display:none',
                                                                'value' => 'mailpoet',
                                                                'checked' => in_array('mailpoet', $explode_automation_status) ? 'checked':''
                                                            ),
                                                        )
                                                        ?>
                                                        <div class="lbb-intigration-wrapper lbb-automation-outer">
                                                            <ul>
                                                                <?php echo sqbGenerateAutomationHtml($chatflow_id, $automation_array); ?>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                    </div>
                                </div>
                            <?php }else{ ?>
                            <div class="lbb-no-answers-found lbb-empty-box lbb-automation-section-empty-box">
                                <div class="lbb-box-container">
                                        <span class="dashicons dashicons-warning lbb-box-icon"></span>
                                        <p class="lbb-box-text">Please download and activate "Smart Automation Engine Plugin" to use the automation feature.</p>
                                        <p class="lbb-box-text">You can download it from the same place as LBB. 
                                            </p><a class="lbb-btn lbb-btn-primary" href="https://wickedcoolplugins.com/login/" target="_blank">Click here to login and download the plugin.</a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            
                        </div>
                    </div><!-- 
                    <div class="lbb-popup-btn-footer">
                        <button id="lbb-save-message-customizer-btn" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                    </div> -->
                </form>

                <?php if(defined('SMART_AUTOMATION_PATH')){ ?>

                    <div class="lbb-chatflow-save-action lbb-chatflow-footer-action">
                        <button class="lbb-btn lbb-btn-black lbb-back-chatflow" style="display: none;">Back</button>
                        <button class="lbb-btn lbb-btn-black lbb-save-chatflow">Save</button>
                        <button class="lbb-btn lbb-btn-black lbb-next-chatflow">Save &amp; Next</button>
                    </div>
                <?php } ?>
                
            </div>



            <div class="lbb-modal-container" id="lbb-modal-create-new-platform" style="display: none;">
                <div class="lbb-modal-content">
                    <input type="hidden" name="automation-type" value="">
                    <form method="POST" class="form-automation">
                        <header class="lbb-header-wrapper">
                            <h2><span class="platform-name"></span> Details</h2>
                        </header>
                        <div class="lbb-modal-body">
                            <!-- <input type="hidden" name="action" value="save_automation_data"> -->
                            <div class="lbb-form-group show-hide-api-url">
                                <label for="title">Enter API URL:</label>
                                <input type="text" id="lbb-api-url" class="lbb-input-field" name="api_url" placeholder="Enter key">
                            </div>
                            <div class="lbb-form-group show-hide-api-key">
                                <label for="title">Enter API key:</label>
                                <input type="text" id="lbb-api-key" class="lbb-input-field" name="api_key" placeholder="Enter API key">
                            </div>
                            <div class="lbb-form-group show-hide-api-secret-key">
                                <label for="title">Enter Secret key:</label>
                                <input type="text" id="lbb-api-secret-key" class="lbb-input-field" name="api_secret_key" placeholder="Enter Secret key">
                            </div>
                            <div class="lbb-form-group show-hide-api-client-id">
                                <label for="title">Enter Client ID:</label>
                                <input type="text" id="lbb-api-client-id" class="lbb-input-field" name="api_client_id" placeholder="Enter Client ID">
                            </div>
                            <div class="lbb-form-group show-hide-api-token">
                                <label for="title">Enter API Token:</label>
                                <input type="text" id="lbb-api-token" class="lbb-input-field" name="api_token" placeholder="API Token">
                            </div>
                            <div class="lbb-form-group show-hide-api-password">
                                <label for="title">Enter Password:</label>
                                <input type="text" id="lbb-api-password" class="lbb-input-field" name="api_password" placeholder="Enter Password">
                            </div>
                            <div class="lbb-form-group show-hide-auth-token">
                                <label for="title">Auth Token:</label>
                                <input type="text" id="lbb-auth-token" class="lbb-input-field" name="auth_token" placeholder="Auth Token">
                            </div>
                            <div class="lbb-form-group show-hide-enter-url" style="display:none;">
                                <label for="title">Enter URL:</label>
                                <input type="text" id="lbb-enter-url" class="lbb-input-field" name="webhook_url" placeholder="Enter URL">
                            </div>
                            <div class="test-connection-btn show-hide-enter-url"><a href="#" class="testzap" style="box-shadow: none;" id="test_zapier_url" onclick="lbb_autoresponder_test_webhook(this)">Click Here to Test Connection</a></div>

                            <div class="test-connection-btn show-hide-authorize-url"><a style="color:blue;" target="_blank" href="https://auth.aweber.com/1.0/oauth/authorize_app/06825be9">Click Here to Authorize "List Building Bot" to Connect To Your AWeber Account</a></div>
                        </div>
                        <footer class="lbb-header-wrapper">
                            <a href="javascript:void(0)" class="lbb-btn lbb-btn-secondary lbb-close">Close</a>
                            <button type="submit" class="lbb-btn lbb-btn-primary lbb-save-automation-btn">Save</button>
                        </footer>
                    </form>
                </div>
            </div>

            
        </div>
    </div>
</section>

<?php function sqbGenerateAutomationHtml($chatflow_id, $field_array){
    foreach ($field_array as $key => $value) {
        
        $disabled = ($value['value'] == 'drip' || $value['value'] == 'acumbamail') ? 'disabled' : '';

        ?>
        <li style="<?php echo isset($value['show_automation']) ? $value['show_automation'] : ''; ?>" class="add_user_in_your_email_platform_inner <?php echo $value['connect-btn-class']; ?>" data-automation-name="<?php echo $value['label']; ?>">
            <div class="lbb-automation-left">
                <span class="checkbox-custom-style">
                <input type="checkbox" id="automation-platform-checkbox-<?php echo $key; ?>" name="lbb_meta[_lbb_automation_status][]" value="<?php echo $value['value']; ?>" class="custom-checkbox-input <?php echo $value['need-plugin']; ?>" <?php echo $value['checked']; ?> <?php echo $disabled ?>> <label class="custom--checkbox" for="automation-platform-checkbox-<?php echo $key; ?>" ></label>
                </span>
                <span class="lbb-platform-name"><?php echo $value['label']; ?></span>
            </div>
             <div class="lbb-automation-right">
                <a class="lbb-model-open-btn lbb-btn lbb-btn-primary <?php echo $value['check-email-has']; ?> click-to-connect-btn" id="lbb-connect-email-platform" data-lbbmodel="#lbb-modal-create-new-platform" href="javascript:void(0);" data-authorize-url="<?php echo $value['data-authorize-url'] ?>" data-api-key="<?php echo $value['data-api-key']; ?>" data-api-url="<?php echo $value['data-api-url']; ?>" data-api-name="<?php echo $value['value']; ?>" data-api-secret="<?php echo $value['data-api-secret']; ?>" data-api-token="<?php echo $value['data-api-token']; ?>" data-api-client-id="<?php echo $value['data-api-client-id']; ?>" data-api-password="<?php echo $value['data-api-password']; ?>" data-auth-token="<?php echo $value['data-auth-token']; ?>" data-label-name="<?php echo $value['label']; ?>" data-enter-url="<?php echo $value['data-enter-url']; ?>" style="<?php echo $value['value']; ?>"><?php echo $value['text']; ?></a>
                <?php if($value['value'] == 'drip' || $value['value'] == 'acumbamail' || $value['value'] == 'getresponse'){ ?>
                    <div><strong>Coming Soon</strong></div>
                <?php }else{ ?>
                    <a href="javascript:void(0)" class="add-more-automation lbb-btn lbb-btn-black" data-automation-name="<?php echo $value['value']; ?>" style="<?php echo $value['style']; ?>">Show Listing</a>
                <?php } ?>
            </div>
           
        </li>
        <?php
    }
} ?>