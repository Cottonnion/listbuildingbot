<?php
function lbb_get_lang_value($lang, $value){
    if($value == $lang){
        return 'selected';
    }
}
?>

<form method="POST" class="lbb-form-settings">
<section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
    <div class="lbb-container lbb-vertical-container">
        <div class="lbb-vertical-content-up">
            <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">


                <div class="lbb-box lbb-bb lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="ai_assistant_language">Select language:</label>
                                <select name="lbb_meta[ai_assistant_language]" id="ai_assistant_language" class="js-select2">

                                    <?php echo lbbGenerateHtml($language_customizer_array); ?>

                                    
                                </select>
                            </div>
                        </div>
                        <div class="lbb-col-6">
                            <div class="lbb-form-group">
                                <label for="ai_welcome_message">Welcome Message:</label>
                                <input type="text" id="ai_welcome_message" name="lbb_meta[_ai_welcome_message]" value="<?php echo $ai_welcome_message; ?>" class="lbb-input-field" placeholder="Enter message...">
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="lbb-box lbb-bb lbb-mb-20 hide-for-inpage">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-left-right-text lbb-form-group lbb-bb-0">
                                    <label for="welcome_message">Main Prompt:</label>
                                    <a class="lbb-auto-prompt-popup lbb-btn lbb-btn-primary" href="javascript:void(0);">Auto Prompt</a>
                                </div>
                                <textarea name="lbb_meta[aiassistant_main_prompt]" class="lbb-input-field" rows="10" cols="100"><?php echo stripslashes($aiassistant_main_prompt); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-bb lbb-mb-20 hide-for-inpage">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="welcome_message">Rules:</label>
                                <textarea class="lbb-input-field" name="lbb_meta[aiassistant_rules]" rows="10" cols="100"><?php echo $aiassistant_rules; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-bb lbb-mb-20">
                    <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Limits</h2>
                    </div>

                    <div class="lbb-row">
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label for="input_token_limit">Input token limit:</label>
                                <span>Note: Input = Prompt + History + Embedding + User's message</span>
                                <input type="text" id="input_token_limit" name="lbb_meta[input_token_limit]" value="<?php echo $input_token_limit; ?>" class="lbb-input-field">
                            </div>
                        </div>
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label for="output_token_limit">Output token limit:</label>
                                <input type="text" id="output_token_limit" name="lbb_meta[output_token_limit]" value="<?php echo $output_token_limit; ?>" class="lbb-input-field" >
                            </div>
                        </div>
                        <div class="lbb-col-4">
                            <div class="lbb-form-group">
                                <label for="limit_threads">Limit threads:</label>
                                <span>Limit threads 10000 every 30 days</span> 
                                <input type="text" id="limit_threads" name="lbb_meta[limit_threads]" value="<?php echo $limit_threads; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lbb-box lbb-bb lbb-mb-20">
                     <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                        <h2>Select Modal</h2>
                     </div>
                     <div class="lbb-row">
                        <div class="lbb-col-3">
                           <div class="lbb-form-group js-select2-wrapper">
                              <!-- <label for="when_to_show">Select URL:</label> -->
                                <select name="lbb_meta[api_model]" id="api_model" class="js-select2">
                                    <option value="gpt-4o" <?php echo $api_model == 'gpt-4o'?'selected':''; ?>>GPT-4o</option>
                                    <option value="gpt-4o-mini" <?php echo $api_model == 'gpt-4o-mini'?'selected':''; ?>>GPT-4o-mini</option>
                                    <option value="gpt-4-turbo" <?php echo $api_model == 'gpt-4-turbo'?'selected':''; ?>>GPT-4o-turbo</option>
                                    <option value="gpt-4" <?php echo $api_model == 'gpt-4'?'selected':''; ?>>gpt-4</option>
                                    <option value="gpt-4-32k" <?php echo $api_model == 'gpt-4-32k'?'selected':''; ?>>gpt-4-32k</option>
                                    <option value="gpt-4-1106-preview" <?php echo $api_model == 'gpt-4-1106-preview'?'selected':''; ?>>gpt-4-1106-preview</option>
                                    <option value="gpt-4-1106-vision-preview" <?php echo $api_model == 'gpt-4-1106-vision-preview'?'selected':''; ?>>gpt-4-1106-vision-preview</option>
                                    <option value="gpt-3.5-turbo-16k" <?php echo $api_model == 'gpt-3.5-turbo-16k'?'selected':''; ?>>gpt-3.5-turbo-16k  (deprecated)</option>
                                    <option value="gpt-3.5-turbo" <?php echo $api_model == 'gpt-3.5-turbo'?'selected':''; ?>>gpt-3.5-turbo  (deprecated)</option>
                                    
                                </select>
                           </div>
                        </div>
                        
                     </div>
                  </div>

                <div class="lbb-box lbb-bb lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="allow_embed_domains">Allow embed to these domains:</label>
                                <textarea class="lbb-input-field" name="lbb_meta[allow_embed_domains]" rows="5" cols="100"><?php echo $allow_embed_domains; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</form>

<div id="autoprompt-propwrap" class="lbb-popup-main">
    <div id="autoprompt-properties" class="lbb-popup-container">
        
    </div>
</div>

<script type="text/html" id="autoPromtForm">
    <div class="lbb-modal-start">
    <header class="lbb-modal-header">
        <div class="lbb-modal-header-inner">
            <h2>Generate Auto prompt</h2>
            <div id="close" class="lbb-delete-icon">
                <span class="dashicons dashicons-no-alt"></span>
            </div>
        </div>
    </header>
    <div class="lbb-popup-body-wrapper">
        <div class="lbb-popup-content-wrapper">
            <div class="lbb-form-group">
                <label for="prompt-title">Write a short description of the role of your chatbot. Our AI will automagically generate a detailed prompt for you!</label>
                <textarea id="prompt-message" name="prompt-message" rows="2" class="lbb-input-field" data-target-error="#prompt-message-error"></textarea>
            </div>
            <div class="lbb-auto-prompt-contents"></div>
            <div class="lbb-form-group">
                <button id="lbb-generate-prompt" class="lbb-btn lbb-btn-primary" type="button" disabled>Generate</button>
            </div>
        </div>
    </div>
    
    <footer class="lbb-popup-footer-wrapper">
        <div class="lbb-popup-btn-footer">
            <button id="lbb-append-prompt" class="lbb-btn lbb-btn-primary" type="button">Append</button>
        </div>
    </footer>
    </div>
</script>

<?php 
function lbbGenerateHtml($field_array){
    foreach ($field_array as $key => $value) {
        echo '<option value="'.$value['value'].'" '.$value['selected'].'>'.$value['value'].'</option>';
    }
}

?>

