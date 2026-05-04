<?php
get_lbb_header();
include( LBB_ABS_URL . 'admin/pages/chatflow-variables.php');

if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'ai_assistant'){
    $chatflow_type = 'ai_assistant';
}else{
    $chatflow_type = 'logicbot';
}

$show_preview = "display:none";
$lbb_hide_connection_line = "N";
if($chatflow_id && $chatflow_id != 0){
    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);
    $ai_assistant_id = get_post_meta($chatflow_id, '_lbb_assistant_id', true);
    $lbb_hide_connection_line = get_post_meta($chatflow_id, 'lbb_hide_connection_line', true);
    $chatflow_title = get_the_title($chatflow_id);
    $show_preview = "";
}

$firebase_configuration = 0;
if(get_option('lbb_admin_firebase_id') && get_option('firebase_app_configuration') && get_option('firebase_db_configuration')){
    $firebase_configuration = 1;
} 

?>
<input type="hidden" name="firebase_configuration" id="firebase_configuration" value="<?= $firebase_configuration; ?>">

<input type="hidden" name="chatflow_type" id="chatflow_type" value="<?= $chatflow_type; ?>">

<div class="lbb-content">
    <div class="lbb-content-start lbb-main-wrapper ">
        <div class="tabs">
            <div class="lbb-tab-main" role="group">
                <div class="lbb-tab-tool-bar">
                    <div class="lbb-header-tabs-part">
                        <ul id="tabs-nav">
                            <li><a href="#tab1">Basic</a></li>
                            <li class="trained-ai-options" style="<?php echo $chatflow_type == 'trained_ai' ? '' : 'display: none;';  ?>"><a href="#tab9"  >Train the Bot</a></li>
                            <li><a href="#tab2">Settings</a></li>
                            <li class="lbb-funnel-tab" style="<?php echo ($chatflow_type == 'botlivechat' || $chatflow_type == 'logicbot') ? '': 'display: none;'; ?>"><a href="#tab3">Funnel</a></li>
                            <li class="ai-assistant-tab" style="<?php echo $chatflow_type == 'ai_assistant' ? '' : 'display: none;'; ?>"><a href="#tab4">AI Assistant Settings</a></li>
                            <li><a href="#tab5">Style</a></li>
                            <li class="hide-for-trainedai" style="<?php echo $chatflow_type == 'trained_ai' ? 'display: none;': ''; ?>"><a href="#tab6">Automations</a></li>
                            <li class="hide-for-trainedai" style="<?php echo $chatflow_type == 'trained_ai' ? 'display: none;': ''; ?>"><a href="#tab8" >Notifications </a></li>
                            <li><a href="#tab7">Summary</a></li>
                        </ul>
                        <div class="lbb-chatflow-save-action">
                            <button class="lbb-btn lbb-btn-secondary lbb-preview-chatflow" style="<?php echo $show_preview; ?>" title="Preview">Preview</button>
                            <button class="lbb-btn lbb-btn-light-gray lbb-back-chatflow" title="Back">Back</button>
                            <button class="lbb-btn lbb-btn-black lbb-save-chatflow" title="Save">Save</button>
                            <button class="lbb-btn lbb-btn-secondary lbb-next-chatflow" title="Save & Next">Save & Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tabs-content">
                <div id="tab1" class="tab-content">
                    <div class="lbb-page-heading-main" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? 'display: none;' : '';  ?>">
                        <div class="lbb-page-heading">
                            <i class="bx bxs-info-square"></i>
                            <h3>Basic Information <small class="lbb-this-text-for-normal">Give your bot a name and select the type of bot you want to create. <span class="lbb-box-icon-in-page"><span class="dashicons dashicons-warning"></span> <span class="lbb-inline-info-box">You can create 3 types of bots here. <br>1) Create a bot funnel with questions, answers, and thank you message. <br>2) Use a combination of bot funnel + live chat <br>3) Just create a live chatbot.</span></span></small> <small class="lbb-this-text-for-ai" style="display: none;">Give your bot a name and select the type of bot you want to create. <span class="lbb-box-icon-in-page"><span class="dashicons dashicons-warning"></span> <span class="lbb-inline-info-box">Subheading: You can create a custom bot trained with your content or create a AI-powered bot here.</span></span></small></h3>
                        </div>
                    </div>

                    <div class="lbb-page-heading-main"  style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? '' : 'display: none;';  ?>">
                        <div class="lbb-page-heading">
                            <i class="bx bxs-info-square"></i>
                            <h3>Basic Information <small class="lbb-this-text-for-normal">You can create a custom bot trained with your content or create a AI-powered bot here. </small></h3>
                        </div>
                    </div>

                    <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
                        <div class="lbb-container lbb-vertical-container">
                            <div class="lbb-vertical-content-up">
                                <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                                    <form method="POST" class="lbb-form-settings">
                                        

                                        <div class="lbb-box lbb-section-bg-box lbb-mb-20" id="chatbot-info">

                                        <div class="inpage-message lbb-mt-20 lbb-mb-20 lbb-alert lbb-alert-warning no-api-key-block" style="display:none">
                                            <p>Please enter your OpenAI Credentials to use this feature. <a href="<?php echo admin_url( 'admin.php?page=listbuildingbot-settings' ); ?>" target="_BLANK">Click here</a> to get the keys.<br />Looks like you have not entered the OpenAI Key in the settings page. Please enter it to use this feature.</p>
                                        </div>

                                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <h2>Chatbot Information</h2>
                                            </div>
                                            <div class="lbb-form-group lbb-chatflow-data">
                                                <label for="title_name">Name (internal use only)</label>
                                                <input type="text" id="title_name" name="lbb_meta[title_name]" value="<?php echo $chatflow_title; ?>" class="lbb-input-field" placeholder="For e.g., Lead Qualification Bot">
                                                <span class="title-error lbb-error" style="display:none;">Please Enter Title</span>
                                            </div>

                                            <div class="lbb-form-group">
                                                <label for="lbb_admin_name">Chatbot Display Name </label>
                                                <input id="lbb_admin_name" name="lbb_meta[lbb_admin_name]" class="lbb-input-field" type="text" value="<?php echo $lbb_admin_name; ?>">
                                            </div>

                                            <div class="lbb-form-group">
                                                <label for="lbb_chatbot_description">Chatbot Description</label>
                                                <textarea id="lbb_chatbot_description" name="lbb_meta[lbb_chatbot_description]" class="lbb-input-field" type="text" ><?php echo $lbb_chatbot_description; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <h2>Pick Theme Colors</h2>
                                            </div>
                                            <div class="lbb-form-group">
                                                <div class="lbb-row">
                                                    <div class="lbb-col-3 lbb-style-color" style="<?php echo $lbb_style_chatbot_background == 'color' ? '':'display: none;'; ?>">
                                                        <div class="lbb-form-group">
                                                            <label for="lbb_max_bg_color">Background</label>
                                                            <input type="text" name="" id="lbb_home_chat_background_color" value="<?php echo $lbb_chat_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_chat_background_color; ?>" data-other-options="lbb_chat_background_color" data-css-variable="lbb-chat-background-color" />
                                                        </div></div>

                                                    <div class="lbb-col-3">
                                                        <div class="lbb-form-group">
                                                            <label for="lbb_ques_bg_color">Question Background</label>
                                                            <input type="text" name="" id="lbb_home_question_background_color" value="<?php echo $lbb_question_background_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_question_background_color; ?>" data-other-options="lbb_question_background_color" data-css-variable="lbb-question-background-color" />
                                                        </div>
                                                    </div>

                                                    <div class="lbb-col-3">
                                                        <div class="lbb-form-group">
                                                            <label for="lbb_ques_bg_color">Answer Background</label>
                                                            <input type="text" name="" id="lbb_home_ans_bg_color" value="<?php echo $lbb_ans_bg_color; ?>" class="lbb-input-field lbb-color-picker" data-other-options="lbb_home_ans_bg_color" data-default-color="<?php echo $lbb_ans_bg_color; ?>" data-css-variable="lbb-chat-answer-btn-background-color" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="lbb-box lbb-section-bg-box lbb-mb-20">

                                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <h2>Chatbot Type</h2>
                                            </div>
                                            <div class="lbb-form-group">
                                                <div class="sqb-template-selection-options">
                                                    <label class="sqb-template-selection-item" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? 'display: none;' : '';  ?>">
                                                        <input type="radio" name="lbb_meta[_chatflow_type]" value="logicbot" <?= $chatflow_type == 'logicbot' ? 'checked': ''; ?>>
                                                        <span class="lbb-radio-icon"></span>
                                                        
                                                        <?php /*<img src="<?php echo LBB_URL; ?>admin/images/lbb-bot-icon.png" alt="Option 1">*/ ?>
                                                        <i class="bx bx-bot"></i>
                                                        <strong>Logic Bot Funnel <span class="lbb-box-icon" data-tooltip="Add a logic bot funnel where you can preprogram the questions/answers"><span class="dashicons dashicons-warning"></span></span></strong>
                                                    </label>

                                                    <label class="sqb-template-selection-item" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? 'display: none;' : '';  ?>">
                                                        <input type="radio" name="lbb_meta[_chatflow_type]" value="botlivechat" <?=  $chatflow_type == 'botlivechat' ? 'checked': ''; ?>>
                                                        <span class="lbb-radio-icon"></span>
                                                        <?php /*<img src="<?php echo LBB_URL; ?>admin/images/lbb-bot-icon.png" alt="Option 2">*/ ?>
                                                        <i class="bx bx-conversation"></i>
                                                        <strong>Logic Bot + Live Chat <span class="lbb-box-icon" data-tooltip="Add a logic bot funnel where you can preprogram the questions/answers, with an option to transfer to an agent."><span class="dashicons dashicons-warning"></span></span></strong>
                                                    </label>

                                                    <label class="sqb-template-selection-item" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? 'display: none;' : '';  ?>">
                                                        <input type="radio" name="lbb_meta[_chatflow_type]" value="livechat" <?=  $chatflow_type == 'livechat' ? 'checked': ''; ?>>
                                                        <span class="lbb-radio-icon"></span>
                                                        <?php /*<img src="<?php echo LBB_URL; ?>admin/images/lbb-live-chat-icon.png" alt="Option 3">*/ ?>
                                                        <i class="bx bx-comment-dots"></i>
                                                        <strong>Live Chat <span class="lbb-box-icon" data-tooltip="Allow users to chat with you/agent, or send you a message"><span class="dashicons dashicons-warning"></span></span></strong>
                                                    </label>
                                                    
                                                    <label class="sqb-template-selection-item" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? '' : 'display: none;';  ?>">
                                                        <input type="radio" name="lbb_meta[_chatflow_type]" value="trained_ai" <?=  $chatflow_type == 'trained_ai' ? 'checked': ''; ?>>
                                                        <span class="lbb-radio-icon"></span>
                                                        <?php /*<img src="<?php echo LBB_URL; ?>admin/images/lbb-ai-chat-icon.png" alt="Option 4">*/ ?>
                                                        <i class='bx bx-book-content' ></i>
                                                        <strong>Train the Bot (using your documents + AI)</strong>
                                                    </label> 

                                                    <label class="sqb-template-selection-item" style="<?= ($chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai') ? '' : 'display: none;';  ?>">
                                                        <input type="radio" name="lbb_meta[_chatflow_type]" value="ai_assistant" <?=  $chatflow_type == 'ai_assistant' ? 'checked': ''; ?>>
                                                        <span class="lbb-radio-icon"></span>
                                                        <?php /*<img src="<?php echo LBB_URL; ?>admin/images/lbb-ai-chat-icon.png" alt="Option 4"> */?>
                                                        <i class='bx bxl-graphql' ></i>
                                                        <strong>Use OpenAI to Respond To Questions </strong>
                                                    </label> 
                                                </div>
                                                <!-- <div class="create-chatflow-message inpage-message lbb-mt-20 lbb-alert lbb-info">
                                                    LogicBot chat message
                                                </div> -->
                                            </div>
                                        </div>

                                        <div class="lbb-firebase-configuration lbb-alert lbb-alert-warning" style="display:none">
                                            <p>Please note:</p>

                                            <p>To use the Live Chat feature, you need to setup a FREE "Google Firebase" account.  Firebase is used to build real-time features in applications such as live chat functionality. LBB uses it for live chat features.</p>

                                            <p>Please <a target="_BLANK" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listbuildingbot-settings&tab=firebase">click here</a> to refer the documentation and to setup Firebase account first and then you can use the live chat features.</p>
                                        </div>

                                        <div class="lbb-box lbb-section-bg-box lbb-mb-20 trained-ai-options" style="<?= $chatflow_type == 'trained_ai' ? '' : 'display: none;';  ?>">
                                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <div class="lbb-text-spacebetween">
                                                    <h2>Configure OpenAI settings below: </h2>
                                                    <a href="javascript:void(0)" class="lbb-btn lbb-small lbb-btn-black read-how-work">Read how it works</a>
                                                </div>
                                            </div>
                                            <div class="lbb-row lbb-align-items-center lbb-bb lbb-mb-20">
                                                <div class="lbb-col-12">
                                                   <div class="lbb-form-group">
                                                      <ul class="lbb-radio-btn-wrapper lbb-radio-column lbb-ml-0">
                                                         <li>
                                                            <input type="radio" id="use_the_content" name="lbb_meta[open_ai_settings]" value="use_the_content" <?php echo $open_ai_settings == 'use_the_content'?'checked="checked"':''; ?>>
                                                            <label for="use_the_content">Use the content I've uploaded but if no match found, use OpenAI</label>
                                                            <div class="lbb-check">
                                                               <div class="inside"></div>
                                                            </div>
                                                         </li>
                                                         <li>
                                                            <input type="radio" id="get_open_ai" name="lbb_meta[open_ai_settings]" value="get_open_ai" <?php echo $open_ai_settings == 'get_open_ai'?'checked="checked"':''; ?>>
                                                            <label for="get_open_ai">Get OpenAI to only use my uploaded content for a response.</label>
                                                            <div class="lbb-check"></div>
                                                         </li>
                                                      </ul>
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="lbb-form-group lbb-chatflow-data">
                                                <label for="lbb_ai_welcome_message">Welcome Message</label>
                                                <input type="text" id="lbb_ai_welcome_message" name="lbb_meta[_trained_ai_welcome_message]" value="<?php echo $lbb_ai_welcome_message; ?>" class="lbb-input-field" placeholder="Enter Text">
                                            </div>

                                            <div class="lbb-form-group lbb-chatflow-data">
                                                <label for="no_response_msg">If no response found, what message you want to show:</label>
                                                <input type="text" id="no_response_msg" name="lbb_meta[no_response_msg]" value="<?php echo $no_response_msg; ?>" class="lbb-input-field" placeholder="Enter Text">
                                            </div>

                                            
                                            <div class="lbb-form-group">
                                                <label for="welcome_message">Main Instructions / Rules:</label>
                                                <textarea class="lbb-input-field" id="lbb_main_aiassistant_rules" name="lbb_meta[main_aiassistant_rules]" rows="10" cols="100"><?php echo $main_aiassistant_rules; ?></textarea>
                                            </div>

                                            <a class="lbb-btn lbb-btn-black lbb-update-open-ai-ass" href="javascript:void(0);">Update in OpenAI</a>

                                            <div class="lbb-form-group js-select2-wrapper">
                                                <label for="main_ai_assistant_language">Select language:</label>
                                                <select name="lbb_meta[main_ai_assistant_language]" id="main_ai_assistant_language" class="js-select2">

                                                    <?php echo lbbMainGenerateHtml($language_customizer_array); ?>

                                                    
                                                </select>
                                            </div>
                                        </div>
                                        

                                        <?php
                                        $ai_assistant = get_option('lbb_ai_assistant');
                                        $OPENAI_API_KEY = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
                                        ?>
                                        <input type="hidden" name="" id="lbb_no_api_key" value="<?= $OPENAI_API_KEY; ?>">

                                        <input type="hidden" name="lbb_meta[_lbb_assistant_id]" id="lbb_assistant_id" value="<?= $ai_assistant_id; ?>">
                                    </form>
                                    <div class="lbb-chatflow-save-action lbb-chatflow-footer-action">
                                            <!-- <button class="lbb-btn lbb-btn-black lbb-back-chatflow" style="display: none;">Back</button> -->
                                            <button class="lbb-btn lbb-btn-black lbb-save-chatflow">Save</button>
                                            <button class="lbb-btn lbb-btn-black lbb-next-chatflow">Save &amp; Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div id="tab9" class="tab-content">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                            <i class="bx bxs-info-square"></i>
                            <h3>Train the Bot</h3>
                        </div>
                    </div>
                    <section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
                        <?php  include( plugin_dir_path( __FILE__ ) . '/aiassistant.php'); ?>
                    </section>
                </div>

                <?php
                    if($chatflow_id && $chatflow_id != 0){
                        $drawflow = LBB_ChatFlow::getDrawflow($chatflow_id);

                        $action_ids = get_post_meta($chatflow_id, 'action_ids', true);
                        $questions = array();
                        if(!empty($action_ids)){
                            $question_ids = explode(',', $action_ids);
                            $questions = LBB_Questions::get_questions($question_ids);
                        }

                        $CFManager = new CustomFieldManager();
                        $custom_fields = $CFManager->loadAll();

                        $TagManager = new TagsManager();
                        $tags = $TagManager->loadAll();
                        global $wpdb;
                        $outcome_tbl = $wpdb->prefix . 'lbb_outcomes';
                        $query = "SELECT * FROM $outcome_tbl WHERE chatflow_id = '".$chatflow_id."'";
                        $outcomes = $wpdb->get_results($query, ARRAY_A);

                        $pdf_tbl = $wpdb->prefix . 'lbb_pdfbuilder';
                        $query = "SELECT * FROM $pdf_tbl";
                        $pdfs = $wpdb->get_results($query, ARRAY_A);

                        $lbb_livechat_words = get_option('lbb_livechat_words','');
                        $lbb_livechat_connecting_message = get_option('lbb_livechat_connecting_message','');
                        $lbb_options['lbb_livechat_words'] = $lbb_livechat_words;
                        $lbb_options['lbb_livechat_connecting_message'] = $lbb_livechat_connecting_message;

                    }
                   
                ?>

                <div id="tab3" class="tab-content" style="<?php echo ($chatflow_type == 'botlivechat' || $chatflow_type == 'logicbot') ? '' : 'display: none;'; ?>">
                    
                    <div class="drawflow-main">
                        <?php /*<div class="drawflow-action">
                            <div class="lbb-bar-zoom">
                                <i class="bx bx-zoom-out" onclick="lbb_editor.zoom_out()" title="Zoom out"></i>
                                <i class="bx bx-search" onclick="lbb_editor.zoom_reset()" title="Zoom search"></i>
                                <i class="bx bx-zoom-in" onclick="lbb_editor.zoom_in()" title="Zoom In"></i>
                            </div>
                            <a href="#" class="lbb-btn lbb-btn-secondary lbb-add-new-question">Add Message</a>
                        </div> */?>

                        <div class="drawflow-action">
                            <div class="lbb-to-area-drawflow-message">
                                <div class="lbb-alert lbb-alert-info"><p>To disconnect nodes, right-click the mouse next to the line connecting nodes and click on the 'X' icon. <br>
                                To connect 2 nodes, drag the line from the connector to the next node and drop it where it says "connect".</p></div>
                            </div>
                            <div class="lbb-bar-zoom-wrapper"></div>
                            <div class="lbb-action-drawflow-btn">
                                <div class="lbb-heading-with-switch lbb-justify-content-end">
                                    <h2>Hide Connection Line <span class="lbb-box-icon" data-tooltip="If the funnel appears cluttered with too many connection lines, you can hide them here. The nodes will remain connected, but the lines won't be visible in the funnel builder."><span class="dashicons dashicons-warning"></span></span></h2>
                                    <div class="lbb-switch">
                                        <input <?php echo $lbb_hide_connection_line == 'Y' ? 'checked' : ''; ?> type="checkbox" id="lbb_hide_connection_line" name="lbb_hide_connection_line" class="checkstyle">
                                        <label for="lbb_hide_connection_line"><span><span></span></span></label>
                                    </div>
                                </div>
                                <div class="lbb-bar-zoom">
                                    <i class="bx bx-zoom-out" onclick="lbb_editor.zoom_out()" title="Zoom out"></i>
                                    <i class="bx bx-search" onclick="lbb_editor.zoom_reset()" title="Zoom search"></i>
                                    <i class="bx bx-zoom-in" onclick="lbb_editor.zoom_in()" title="Zoom In"></i>
                                </div>
                                <a href="#" class="lbb-btn lbb-btn-secondary lbb-add-new-question">Add Message</a>
                            </div>
                        </div>

                        <div class="bot-canvas" id="botCanvas">
                            <div class="action-node-wrapper lbb-main-drawflow-wrapper <?php echo $lbb_hide_connection_line == 'Y' ? 'lbb-hide-node' : ''; ?>">
                                <div id="drawflow">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="propwrap" class="lbb-popup-main">
                        <div id="properties" class="lbb-popup-container">
                        </div>
                    </div>

                    <script type="text/html" id="lbbOptionsEditForm">
                        <div class="lbb-modal-start">
                            <header class="lbb-modal-header">
                                <div class="lbb-modal-header-inner">
                                    <h2>Options settings</h2>
                                    <div id="close" class="lbb-delete-icon">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </div>
                                </div>
                            </header>

                            <div class="lbb-popup-body-wrapper">
                                <div class="lbb-popup-content-wrapper">
                                    <div class="lbb-form-group">
                                    <label for="option-connecting-message">What message to show before they are transferred:</label>
                                        <textarea id="option-connecting-message" name="option-connecting-message" rows="4" class="lbb-input-field" data-target-error="#option-connecting-message"></textarea>
                                        <span class="lbb-error" id="option-connecting-message">Please enter message</span>
                                    </div>

                                    <div class="lbb-form-group">
                                        <label for="lbb_livechat_messages">What words will lead to transfer:</label>

                                        <div class="lbb-accordion-container">
                                            <ul id="lbb_livechat_messages" class="lbb-list-answer"></ul>
                                        </div>
                                        <div class="lbb-form-group lbb-text-right">
                                            <div class="lbb-input-wrapper">
                                                <button id="lbb_livechat_addnew" class="lbb-btn lbb-btn-black" >Add New</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <footer class="lbb-popup-footer-wrapper">
                                <div class="lbb-popup-btn-footer">
                                    <button id="lbb-save-options" class="lbb-btn lbb-btn-primary" type="button">Save</button>
                                </div>
                            </footer>
                            
                        </div>
                    </script>

                    <script type="text/html" id="lbbLiveChatRepeater">
                        <div class="lbb-form-group">
                            
                            <div class="lbb-input-wrapper">
                                <div class="lbb-row lbb-choice-main lbb-align-items-start">
                                    <div class="lbb-col-11">
                                        <input type="text" id="livechatword_%%UKEY%%" placeholder="Title" class="lbb-input-field lbb-livechatwords-input" data-key="%%UKEY%%" value="%%TITLE%%" data-target-error="#livechatword_%%UKEY%%_error">
                                        <span class="lbb-error" id="livechatword_%%UKEY%%_error">Please enter any value</span>
                                    </div>
                                    <div class="lbb-col-1">
                                        <a href="javascript:void(0);" class="lbb-delete-livechatword lbb-icon-btn lbb-delete-btn" data-key="%%UKEY%%">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </script>

                    <script type="text/html" id="questionEditForm">
                        <div class="lbb-modal-start">
                            <header class="lbb-modal-header">
                                <div class="lbb-modal-header-inner">
                                    <h2 class="edit-question-heading">Edit Message : </h2>
                                    <div id="close" class="lbb-delete-icon">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </div>
                                </div>
                            </header>

                            <div class="lbb-popup-body-wrapper">
                                <div class="lbb-popup-question-wrapper lbb-change-message-type lbb-form-group lbb-mb-0 lbb-bg-lb">
                                    <div class="lbb-left-right-text lbb-template-switch-wrapper lbb-template-inline-field lbb-pb-0">
                                        <label for="message_type">Change a message type below:</label>
                                        <select id="message_type" name="message_type" class="lbb-input-field">
                                            <option value="single">Single</option>
                                            <option value="text">Text</option>
                                            <option value="name">Name</option>
                                            <option value="email">Email</option>
                                            <option value="phone">Phone</option>
                                            <option value="country">Country</option>
                                            <option value="url">URL</option>
                                            <option value="date">Date</option>
                                            <option value="attachment">Attachment</option>
                                            <option value="audio">Audio</option>
                                            <option value="outcome">Outcome</option>
                                            <option value="message">Message (No Answers Required)</option>
                                            <option value="lastmessage">Last Message (No Answers Required)</option>
                                            <option value="welcome" style="display:none;">Last Message (No Answers Required)</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="chatflow-properties" class="lbb-sub-tab-wrapper">
                                    <div id="chatflow-basic" class="lbb-change-tab lbb-sub-tab lbb-active" data-tab="question">Message</div>
                                    <div id="chatflow-answer" class="lbb-change-tab lbb-sub-tab" data-tab="answer">Answer</div>
                                    <!-- <div id="chatflow-branch" class="lbb-change-tab lbb-sub-tab" data-tab="question-conditions">Branch</div> -->
                                </div>
                                <!-- <div class="lbb-popup-question-wrapper lbb-popup-tab-wrapper"> -->
                                <?php lbb_admin_template('questions/type/single',array()); ?>
                                <!-- </div> -->
                                
                                 
                                <div id="divisionthing"></div>
                            </div>
                            <?php lbb_admin_template('questions/footer',array()); ?>
                        </div>
                    </script>
                    <script type="text/html" id="questionTypeSelection">
                        <div class="lbb-modal-start">
                            <header class="lbb-modal-header">
                                <div class="lbb-modal-header-inner">
                                    <h2>Add a New Message</h2>
                                    <div id="close" class="lbb-delete-icon">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </div>
                                </div>
                            </header>

                            <div class="lbb-popup-body-wrapper">
                                <div id="chatflow-properties" class="lbb-btn-wrapper">
                                <h3>Select a message type below</h3>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="single"><span class="dashicons dashicons-align-wide"></span> <strong>Single</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="text"><span class="dashicons dashicons-align-wide"></span> <strong>Text</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="name"><span class="dashicons dashicons-align-wide"></span> <strong>Name</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="email"><span class="dashicons dashicons-align-wide"></span> <strong>Email</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="phone"><span class="dashicons dashicons-align-wide"></span> <strong>Phone</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="country"><span class="dashicons dashicons-align-wide"></span> <strong>Country</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="url"><span class="dashicons dashicons-align-wide"></span> <strong>URL</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="date"><span class="dashicons dashicons-align-wide"></span> <strong>Date</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="attachment"><span class="dashicons dashicons-align-wide"></span> <strong>File Attachment</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="audio"><span class="dashicons dashicons-align-wide"></span> <strong>Audio</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="outcome"><span class="dashicons dashicons-align-wide"></span> <strong>Outcome</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="message"><span class="dashicons dashicons-align-wide"></span> <strong>Message (No Answers Required)</strong></a></div>
                                    <div class="lbb-btn-text"><a href="#" class="lbb-question-type" data-type="lastmessage"><span class="dashicons dashicons-align-wide"></span> <strong>Last Message (No Answers Required)</strong></a></div>
                                </div>
                                <div id="divisionthing"></div>
                            </div>
                        </div>
                    </script>

                    <script type="text/html" id="dynamicMessageRepeater">
                        <div class="lbb-accordion-single-wrapper">
                            <h3 class="lbb-accordion-heading">Dynamic/Smart Message <span class="lbb-auto-count"></span></h3>
                            <div class="lbb-form-group lbb-accordion-content dynamic-message-data" data-key="{{KEY}}">
                                <div class="lbb-input-wrapper">
                                    <div class="lbb-row lbb-choice-main lbb-align-items-start">
                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group js-select2-wrapper ">
                                                <label for="type_{{KEY}}" >Type</label>
                                                <select id="type_{{KEY}}" name="dm_type" class="js-select2-old lbb-input-field lbb-dm-type-action">
                                                    <option value="tags">Tags</option>
                                                    <option value="custom_field">Custom Field</option>
                                                </select>
                                            </div>
                                            <div class="lbb-form-group js-select2-wrapper js-select2-multiple lbb-tag-dynamic-message-field show-tag-property">
                                                <label for="msg_tag_{{KEY}}">Assign a Tag <span class="lbb-box-icon" data-tooltip="You can assign tags to each answer choice in the answer section."><span class="dashicons dashicons-warning"></span></span></label>
                                                <select id="msg_tag_{{KEY}}" name="tag[]" class="lbb-input-field js-select2" multiple>
                                                </select>
                                            </div>
                                            

                                            <div class="lbb-form-group lbb-condition-field lbb-cf-dynamic-message-field">
                                                <label for="lbb_dm_cf_{{KEY}}">Custom Field:</label>
                                                <select id="lbb_dm_cf_{{KEY}}" name="lbb_dm_cf_{{KEY}}" class="lbb-input-field" data-target-error="#lbbc_dynamic_message_error"></select>
                                                <span class="lbb-error" id="lbbc_dynamic_message_error">Please enter message</span>
                                            </div>
                                            <div class="lbb-form-group lbb-condition-field lbb-cf-dynamic-message-field">
                                                <label for="lbb_cf_operator_{{KEY}}">Operator:</label>
                                                <select id="lbb_cf_operator_{{KEY}}" name="lbb_cf_operator_{{KEY}}" class="lbb-input-field">
                                                    <option value="equals">equals</option>
                                                    <option value="not_equals">not_equals</option>
                                                    <option value="is_any_of">is_any_of</option>
                                                    <option value="is_not_any_of">is_not_any_of</option>
                                                    <option value="greater_than">greater_than</option>
                                                    <option value="less_than">less_than</option>
                                                </select>
                                            </div>
                                            <div class="lbb-form-group lbb-condition-field lbb-cf-dynamic-message-field">
                                                <label for="lbb_cf_value_{{KEY}}">Value</label>
                                                <input type="text" id="lbb_cf_value_{{KEY}}" name="lbb_cf_value_{{KEY}}" class="lbb-input-field" data-target-error="lbbc_value_error">
                                                <span class="lbb-error" id="lbbc_value_error">Please enter message</span>
                                            </div>
                                            <div class="lbb-form-group lbb-condition-field">
                                                <label for="lbb-dynamicText_{{KEY}}">Enter Message</label>
                                                <textarea id="lbb-dynamicText_{{KEY}}" name="lbb-dynamicText_{{KEY}}" class="lbb-input-field lbb-tinymce-editor"></textarea>
                                            </div>
                                            

                                        </div>
                                        <div class="lbb-single-condition-action">
                                            <a href="javascript:void(0);" data-id="{{KEY}}" class="lbb-delete-dynamic-message"><span class="dashicons dashicons-trash"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </script>

                    <script type="text/html" id="OutcomeRangeRepeater">
                        
                            <td>
                               <div class="lbb-outcome-range-wrapper">
                                    <div class="lbb-outcome-range-input">
                                        <label>Start Range</label>
                                        <input type="number" id="start_{{KEY}}" class="lbb-input-field"  name="start_range">
                                    </div>
                                    <div class="lbb-outcome-range-input">
                                        <label>End Range</label>
                                        <input type="number" class="lbb-input-field" id="end_{{KEY}}" name="end_range">
                                    </div>
                               </div>
                            </td>
                            <td>
                                <div class="lbb-outcome-range-outcome">
                                    <div class="lbb-outcome-range-input">
                                        <label for="outcome_name_{{KEY}}">Select Outcome</label>
                                        <select id="outcome_name_{{KEY}}" name="outcome" class="lbb-input-field js-select2"></select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="lbb-outcome-delete-action lbb-outcome-range-input">
                                    <label>&nbsp;</label>
                                    <a href="javascript:void(0);" data-id="{{KEY}}" class="lbb-delete-outcome-range lbb-icon-transparent-btn lbb-delete-btn"> <span class="dashicons dashicons-trash"></span> Delete</a>
                                </div>
                            </td>
                        
                    </script>

                    <script type="text/html" id="mapListTable">
                        <table class="lbb-table">
                            <thead>
                                <tr>
                                    <th class="lbb-w-40">Outcome</th>
                                    <th class="lbb-w-40">PDF</th>
                                    <th char="lbb-w-14"></th>
                                </tr>
                            </thead>
                            <tbody>{{data}}</tbody>
                        </table>
                    </script>
                    <script type="text/html" id="answerRepeater">
                        <div class="lbb-accordion-single-wrapper">
                            <h3 class="lbb-accordion-heading">Answer <span class="lbb-auto-count"></span>
                                <div class="lbb-answer-actions">
                                        <a href="javascript:void(0);" class="lbb-delete-choice" data-key="%%UKEY%%">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                        <a href="javascript:void(0);" class="lbb-clone-choice" data-key="%%UKEY%%">
                                            <span class="dashicons dashicons-admin-page"></span>
                                        </a>
                                </div>
                            </h3>
                            <div class="lbb-form-group lbb-accordion-content">
                                <div class="lbb-input-wrapper">
                                    <div class="lbb-row lbb-choice-main lbb-align-items-start">
                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group">
                                                <label for="">Answer Title *<span class="lbb-answer-choice"></span></label>
                                                <input type="text" id="titleInput_%%UKEY%%" placeholder="Title" class="lbb-input-field lbb-choice-input" data-key="%%UKEY%%" value="%%TITLE%%" data-target-error="#titleInput_%%UKEY%%_error">
                                                <span class="lbb-error" id="titleInput_%%UKEY%%_error">Please enter name</span>
                                            </div>

                                            <div class="lbb-form-group lbb-pt-15 lbb-answer-image-box" style="display:none;">
                                                <div class="lbb-choice-media lbb-choice-input-image" id="lbb-cm-%%UKEY%%">
                                                    <div class="lbb-media-wrapper">
                                                        <div class="lbb-media-img">
                                                            <img src="%%IMAGE%%" width="100" height="100" id="image_upload_%%UKEY%%_src" class="lbb-no-img-found" />
                                                        </div>
                                                        <div class="lbb-media-action">
                                                            <a href="javascript:void(0);" class="lbb-select-media lbb-icon-btn lbb-edit-btn" data-target-input="#image_upload_%%UKEY%%" data-key="%%UKEY%%" ><span class="dashicons dashicons-edit"></span></a>
                                                            <a href="javascript:void(0);" class="lbb-remove-media lbb-icon-btn lbb-delete-btn" data-target-input="#image_upload_%%UKEY%%" data-key="%%UKEY%%"><span class="dashicons dashicons-trash"></span></a>
                                                        </div>
                                                        <input type="hidden" class="lbb-input-field" id="image_upload_%%UKEY%%" name="image_upload_%%UKEY%%" value="%%IMAGE%%" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="lbb-form-group js-select2-wrapper ">
                                                <div class="lbb-field-with-subheading">
                                                    <label for="ansaction_%%UKEY%%" >Action</label>
                                                    <span class="lbb-sub-heading">(what should happen when users select this answer)</span>
                                                </div>
                                                <select id="ansaction_%%UKEY%%" name="anstype" class="js-select2-old lbb-input-field lbb-answer-action">
                                                    <option value="start_over">Next Node/Message</option>
                                                    <option value="url">URL</option>
                                                    <option value="support">Connect to an Agent</option>
                                                    <option value="different_bot">Connect to a different bot</option>
                                                </select>
                                                <div class="answertype-inf answertype-info-node answertype-info-text lbb-alert lbb-alert-warning" style="display:none">
                                                    <p>No action required here. Connect to next node in the funnel builder.</p>
                                                </div>
                                                <div class="answertype-inf answertype-info-url answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                                                    <p>Users will be redirected to this page when they pick this answer choice</p>
                                                </div>
                                                <div class="answertype-inf answertype-info-support answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                                                    <p>It'll transfer to a live agent.</p>
                                                </div>  
                                                <div class="answertype-inf answertype-info-start_over answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                                                    <p>It'll start over the chat from the selected question. You can select the question below.</p>
                                                </div>    
                                                <div class="answertype-inf answertype-info-different_bot answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                                                    <p>This node is connected to a different bot funnel.</p>
                                                </div>                                        
                                            </div>
                                            <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-start_over">
                                                <label for="start_ques_%%UKEY%%">Which Message <span class="lbb-box-icon" data-tooltip="Only show for Start Over"></span></label>
                                                <select id="start_ques_%%UKEY%%" name="start_ques_" class="js-select2-old lbb-input-field lbb-answer-start-ques">
                                                </select>                                     
                                            </div>

                                            <div class="lbb-form-group answertype-inf answertype-info-url">
                                                <label for="">URL</label>
                                                <input type="text" id="url_%%UKEY%%" placeholder="URL" class="lbb-input-field lbb-field-url" data-key="%%UKEY%%" value="%%URL%%">
                                                <span class="lbb-error" id="url_%%UKEY%%_error">Please enter name</span>
                                            </div>
                                            <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-different_bot" style="display: none;">
                                                <label for="start_bot_%%UKEY%%">Select a bot to connect to <span class="lbb-box-icon" data-tooltip="Pick the bot to which you want to connect this node"><span class="dashicons dashicons-warning"></span></span></label>
                                                <select id="start_bot_%%UKEY%%" name="start_bot_%%UKEY%%" class="js-select2-old lbb-input-field lbb-bot-start-ques"></select>
                                                <div class="lbb-bot-error lbb-error" style="display:none;">Please select a bot</div>
                                            </div>

                                            <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-different_bot_message" style="display: none;">
                                                <label for="start_message_%%UKEY%%">Select a message in this bot <span class="lbb-box-icon" data-tooltip="Upon connection to this bot, this will be first message that's displayed"><span class="dashicons dashicons-warning"></span></span></label>
                                                <select id="start_message_%%UKEY%%" name="start_message_%%UKEY%%" class="js-select2-old lbb-input-field lbb-bot-start-ques_message"></select>      
                                                <div class="lbb-message-error lbb-error" style="display:none;">Please select a message</div>
                                            </div>

                                            <div class="lbb-form-group js-select2-wrapper answertype-inf lbb-different-bot-message answertype-info-different_bot_message" style="display: none;">
                                                <div class="lbb-alert lbb-alert-warning">
                                                    <p>Please note: This answer choice will be color coded "green" in the funnel.</p>
                                                </div>
                                            </div>

                                            <div class="lbb-same-row-wrapper js-select2-wrapper js-select2-multiple lbb-tags-outer-section">
                                                
                                                <div class="lbb-left-text-center lbb-form-group">
                                                    <label for="tag_%%UKEY%%">Assign a Tag <span class="lbb-box-icon" data-tooltip="Assigned tags will be sent to the connected email platform"><span class="dashicons dashicons-warning"></span></span></label>

                                                    <div class="create-tag-section">
                                                        <div class="lbb-form-same-align">
                                                            <input class="lbb-input-field save_tag" name="save_tag" value="" placeholder="Enter a Tag Name">
                                                            <button type="button" class="create_tag lbb-btn lbb-btn-primary">Create</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lbb-or lbb-mt-30  lbb-mb-30 lbb-w-100"></div>

                                                <div class="lbb-left-text-center lbb-form-group tags-section-outer">
                                                    <label for="tag_%%UKEY%%">Select a Tag</label>
                                                    <!-- <div class="tags-section-inner"> -->
                                                    <select id="tag_%%UKEY%%" name="tag[]" class="lbb-input-field js-select2" multiple>
                                                    </select>
                                                </div>
                                                <!-- </div> -->
                                            </div>
                                            <div class="tag-save-message lbb-alert lbb-alert-success lbb-mb-15" style="display:none;">Saved Successfully</div>
                                            <div class="tag-error-message lbb-alert lbb-alert-warning lbb-mb-15" style="display:none;">Tag with same name is already created</div>
                                            <div class="lbb-same-row-wrapper js-select2-wrapper js-select2-multiple lbb-outcome-outer-section outcome_%%UKEY%%">
                                                

                                                <!-- <div class="lbb-form-group lbb-w-100 lbb-align-items-center">
                                                    <a href="javascript:void(0);" class="create-new-outcome lbb-btn lbb-btn-green ">Click to create</a>
                                                </div> -->

                                                <div class="create-outcome-section bb-left-text-center lbb-form-group">
                                                    <label for="outcome_%%UKEY%%">Map to Outcomes  (optional) <span class="lbb-box-icon" data-tooltip="If you want to segment users based on responses, you can create different outcomes  and allow them to download a personalized PDF based on outcome."><span class="dashicons dashicons-warning"></span></span></label>
                                                    
                                                    <div class="lbb-form-same-align">
                                                        <input class="lbb-input-field save_outcome" name="save_outcome" value="" placeholder="Enter an Outcome Name">
                                                        <button type="button" class="create_outcome lbb-btn lbb-btn-primary">Create</button>
                                                    </div>
                                                </div>
                                                
                                                <div class="lbb-or lbb-mt-30  lbb-mb-30 lbb-w-100"></div>
                                                <div class="bb-left-text-center lbb-form-group outcome-section-outer">
                                                    <label for="outcome_%%UKEY%%">Select an Outcome</label>
                                                    <select id="outcome_%%UKEY%%" name="outcome[]" class="lbb-input-field js-select2" multiple></select>
                                                </div>
                                            </div>
                                            <div class="outcome-save-message lbb-alert lbb-alert-success lbb-mb-15" style="display:none;">Saved Successfully</div>
                                            <div class="outcome-error-message lbb-alert lbb-alert-warning lbb-mb-15" style="display:none;">Outcome with same name is already created</div>
                                            <div class="lbb-form-group">
                                                <label for="">Do you want to assign points to this answer? If not, leave it as 0.<span class="lbb-answer-choice"></span></label>
                                                <input type="text" id="point_%%UKEY%%" placeholder="Point" class="lbb-input-field lbb-point-input" data-key="%%UKEY%%" value="%%POINT%%" data-target-error="#point_%%UKEY%%_error">
                                                <span class="lbb-error" id="point_%%UKEY%%_error">Please enter Point</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </script>
                    <script type="text/html" id="questionBranchList">
                        <div class="lbb-branch-condition">
                            <div class="lbb-conditions-text">
                                If Visitor Response<span>{{type}}</span> <span>{{operator}}</span> <span>{{value}}</span>
                                And
                                Else If Visitor Response<span>{{type}}</span> <span>{{operator}}</span> <span>{{value}}</span>
                            </div> 
                            <div class="lbb-conditions-text">
                                <a href="javascript:void(0);" data-id="{{uid}}">Edit</a>
                                <a href="javascript:void(0);" data-id="{{uid}}">Delete</a>
                            </div> 
                        </div>
                    </script>
                    <script type="text/html" id="questionMessages">
                        <div class="llb-question-message-item" id="question-message-{{uid}}" data-key="{{uid}}">
                            <div class="lbb-left-right-text ">
                                <h2 for="question-message">Message (displayed in the frontend) *</h2>
                                <div class="lbb-right-side-options">
                                    <div class="lbb-personalize-options">
                                        
                                        <a class="lbb-show-merge-tags lbb-btn lbb-small"> Personalize <span class="dashicons dashicons-arrow-down-alt2"></span></a>
                                        <div class=" lbb-mergetag-content-wrapper" style="display: none;">
                                            <div class="lbb-mergetags-content">
                                                <div class="lbb-mergetags-sidebar">
                                                    <ul class="lbb-mergetags-list-wrapper">
                                                        <li class="lbb-mergetags-header lbb-available-tags-heading">Available Tags <span class="lbb-personalize-close">X</span></li>
                                                        <li class="lbb-mergetags-header">User Info</li>
                                                        <li class="lbb-mergetags-tag">%NAME%</li>
                                                        <li class="lbb-mergetags-tag">%EMAIL%</li>
                                                        <li class="lbb-mergetags-tag">%custom_[custom_field_name]% <span class="lbb-box-icon lbb-tooltip-left" data-tooltip='(for e.g., say you have added a custom field with name "favorite_sport" and you have configured LBB to save the open text answer from your users in this custom field. Say you want to use that value to personalize the next message, you can use a personalization tag like this:  "I see that your favorite sport is %custom_favorite_sport%. Just add the word "custom_" before the custom field name and wrap it with %.'><span class="dashicons dashicons-warning"></span></span></li>
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
                                    <a href="javascript:void(0);" data-id="{{uid}}" class="lbb-delete-multiple-message lbb-delete-icon-wrapper"><span class="dashicons dashicons-trash"></span></a>

                                </div>
                            </div>
                            <textarea id="input-question-message-{{uid}}" name="input-question-message-{{uid}}" rows="4" class="lbb-input-field lbb-tinymce-editor" data-target-error="#question-message-error"></textarea>
                            <span class="lbb-error" id="input-question-message-{{uid}}">Please enter message</span>
                            
                        </div>
                    </script>
                    <script type="text/html" id="questionBranchForm">
                        <div class="lbb-branch-condition-fields" id="rule-{{uid}}" >
                            <div class="lbb-form-group lbb-condition-field">
                                <label for="lbbc_type">Type:</label>
                                <select id="lbbc_type" name="lbbc_type" class="lbb-input-field">
                                    <option value="user_reply">User Reponse</option>
                                    <option value="user_property">Custom Field</option>
                                </select>
                                
                            </div>
                            <div class="lbb-form-group lbb-condition-field lbb-user-property-field">
                                <label for="lbbc_user_property">Custom Field:</label>
                                <select id="lbbc_user_property" name="lbbc_user_property" class="lbb-input-field" data-target-error="#lbbc_user_property_error"></select>
                                <span class="lbb-error" id="lbbc_user_property_error">Please enter message</span>
                            </div>
                            <div class="lbb-form-group lbb-condition-field">
                                <label for="lbbc_operator">Operator:</label>
                                <select id="lbbc_operator" name="lbbc_operator" class="lbb-input-field">
                                    <option value="equals">equals</option>
                                    <option value="not_equals">not_equals</option>
                                    <option value="is_any_of">is_any_of</option>
                                    <option value="is_not_any_of">is_not_any_of</option>
                                    <option value="greater_than">greater_than</option>
                                    <option value="less_than">less_than</option>
                                </select>
                            </div>
                            <div class="lbb-form-group lbb-condition-field">
                                <label for="lbbc_value">Value</label>
                                <input type="text" id="lbbc_value" name="lbbc_value" class="lbb-input-field" data-target-error="lbbc_value_error">
                                <span class="lbb-error" id="lbbc_value_error">Please enter message</span>
                            </div>
                            <div class="lbb-single-condition-action">
                                <a href="javascript:void(0);" data-id="{{uid}}" class="lbb-delete-single-condition">Delete</a>
                            </div>
                        </div>
                    </script>
                   
                    <!-- <button class="lbb-btn lbb-btn-secondary next-tab">Next</button> -->
                </div>
                <div id="tab2" class="tab-content"  style="display: none;">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                            <i class='bx bx-cog' ></i>
                            <h3>Bot Display Settings
                                <small>You can select the type (popup or in-page), configure the pages where it should appear, search options, etc.</small>
                            </h3>
                        </div>
                    </div>
                    <?php include( plugin_dir_path( __FILE__ ) . '/livechat-settings.php'); ?>

                    <?php  

                    $chatflow_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

                    ?>

                    <script>
                        var dataToImport = <?php echo json_encode($drawflow); ?>;
                        var lbb_questions = <?php echo json_encode($questions); ?>;
                        var lbb_custom_fields = <?php echo json_encode($custom_fields); ?>;
                        var lbb_tags = <?php echo json_encode($tags); ?>;
                        var lbb_outcomes = <?php echo json_encode($outcomes); ?>;
                        var lbb_pdfs = <?php echo json_encode($pdfs); ?>;
                        var lbb_options = <?php echo json_encode($lbb_options); ?>;
                        var chatflow_id = <?php echo $chatflow_id ?>;
                        var chatflow_type = '<?php echo $chatflow_type; ?>';
                        var site_url = '<?php echo home_url(); ?>';
                    </script>
                </div>
                <div id="tab4" class="tab-content"  style="<?php echo $chatflow_type == 'ai_assistant' ? '': 'display: none;'; ?>">
                    <?php  include( plugin_dir_path( __FILE__ ) . '/chatflow-aiassistant.php'); ?>
                </div>
                <div id="tab5" class="tab-content"  style="display: none;">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                            <i class='bx bx-palette' ></i>
                            <h3>Chatbot Style</h3>
                        </div>
                    </div>
                    <?php  include( plugin_dir_path( __FILE__ ) . '/chatflow-styles.php'); ?>
                </div>
                <div id="tab6" class="tab-content"  style="display: none;">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                           <i class='bx bxs-cog' ></i>
                            <h3>Automations <small>You can add qualified leads to your email platform below.</small></h3>
                        </div>
                    </div>
                    <?php  include( plugin_dir_path( __FILE__ ) . '/chatflow-automations.php'); ?>
                </div>
                <div id="tab8" class="tab-content"  style="display: none;">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                           <i class='bx bxs-cog' ></i>
                            <h3>Email <small>Send your prospects an email after they complete chatting with the bot or agent with the chat details.</small></h3>
                        </div>
                    </div>
                    <?php  include( plugin_dir_path( __FILE__ ) . '/chatflow-email.php'); ?>
                </div>
                <div id="tab7" class="tab-content"  style="display: none;">
                    <div class="lbb-page-heading-main">
                        <div class="lbb-page-heading">
                           <i class='bx bx-check-shield' ></i>
                            <h3>Summary</h3>
                        </div>
                    </div>
                    <?php  include( plugin_dir_path( __FILE__ ) . '/chatflow-summary.php'); ?>
                </div>
            </div>
        </div>

        <?php include( LBB_ABS_URL . 'admin/templates/chat/chat-preview.php'); ?>
    </div>
</div>

<div id="propwrap" class="lbb-popup-main lbb-automation-listing-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>Automation Listing</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
                <div class="lbb-popup-question-wrapper lbb-popup-tab-wrapper">
                    <div class="autoresonder_details_fields_outer automation_save_outer_div">
                        <input type="hidden" name="automation_type" value="">
                        <div class="form-group form-row show-add-automation-table">
                            <div class="autoresponder_listing_data">
                                
                            </div>
                            
                            <div class="form-group form-row show-add-automation-listing" style="display: none;"> 
                                <div class="table-responsive autoresponder_table_details mailerlite" style="">
                                    <table class="autoresponder_table_class autoresponder_table_class  lbb-table-style dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>List Name</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody> 
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
                   <!--  <input type="hidden" name="lbb_customfield_id" id="lbb_customfield_id" value=""> -->
                    <button id="lbb-save-automation-listing" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                </div>
            </footer>
        </div>
    </div>
</div>

<div id="propwrap" class="lbb-popup-main lbb-read-more-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>How it works</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
                <div class="lbb-popup-question-wrapper lbb-popup-tab-wrapper">
                    <p>The way the trained bot works by default it:</p>
                    <ul>
                        <li>1. You can upload documents / content that you want to use to respond to train the bot.</li>
                        <li>2. Users ask a question.</li>
                        <li>3. Trained bot will contact OpenAI (with your uploaded files) to get a well-structured response from your content.</li>
                        <li>4. If openAI does not find a response, it'll look at it's own database fetch a relevant response.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="lbbIFrame">
<div class="lbb-modal-start">
    <header class="lbb-modal-header">
        <div class="lbb-modal-header-inner">
            <h2 class="edit-preview-heading">Preview Bot Funnel</h2>
            <div id="close" class="lbb-delete-icon">
                <span class="dashicons dashicons-no-alt"></span>
            </div>
        </div>
    </header>
    <div class="lbb-popup-body-wrapper">
        <!-- <iframe class="lbb-iframe-main" src="<?php //echo site_url().'?lbb-embed=1&id='.$chatflow_id; ?>" width="100%" height="700" frameborder="0"></iframe> -->
        <div class="lbb-ifram-main-div">
            <div class="slb-iframe-container">
                <div class="lbb-iframe-inner-center">
                    <label class="lbb-preview-iframe-heading">This is a preview of how the bot will look in the frontend</label>
                    <div class="lbb-iframe-main-wrapper"><iframe class="lbb-iframe-main" src="{{IFRAMEURL}}" width="100%" height="700" frameborder="0"></iframe></div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<div id="propwrap_iframe" class="lbb-popup-main">
    <div id="properties_iframe" class="lbb-popup-container"></div>
</div>

<div id="propwrap_outcome" class="lbb-popup-main">
    <div id="properties_outcome" class="lbb-popup-container"></div>
</div>



<script type="text/html" id="outcomeCreateForm">
    <div class="lbb-modal-start">
        <header class="lbb-modal-header">
            <div class="lbb-modal-header-inner">
                <h2 class="edit-preview-heading">Create Outcome</h2>
                <div id="slb-hid-close" class="lbb-delete-icon">
                    <span class="dashicons dashicons-no-alt"></span>
                </div>
            </div>
        </header>
        <div class="lbb-popup-body-wrapper">
            <div class="lbb-popup-question-wrapper">
                <div class="lbb-form-group">
                    <label for="outcome-title">Title * </label>
                    <input type="text" id="outcome-title" name="outcome-title" class="lbb-input-field" data-target-error="#outcome-title-error">
                    <span class="lbb-error" id="outcome-title-error">Please enter question name</span>
                </div>
                <div class="lbb-form-group lbb-question-field">
                <label for="question-name">Description * </label>
                    <textarea id="outcome-description" name="outcome-description" rows="4" class="lbb-input-field" data-target-error="#outcome-description-error"></textarea>
                    <span class="lbb-error" id="outcome-description-error">Please enter message</span>
                </div>
            </div>
        </div>

        <footer class="lbb-popup-footer-wrapper">
            <div class="lbb-popup-btn-footer">
                <button id="lbb-save-outcome" class="lbb-btn lbb-btn-primary" type="button">Save</button>
            </div>
        </footer>
    </div>
</script>
<div id="propwrap_customfield" class="lbb-popup-main">
    <div id="properties_customfield" class="lbb-popup-container"></div>
</div>
<script type="text/html" id="customfieldCreateForm">
    <div class="lbb-modal-start">
        <header class="lbb-modal-header">
            <div class="lbb-modal-header-inner">
                <h2 class="edit-preview-heading">Create Custom Field</h2>
                <div id="slb-close-customfield" class="lbb-delete-icon">
                    <span class="dashicons dashicons-no-alt"></span>
                </div>
            </div>
        </header>
        

        <div class="lbb-popup-body-wrapper">
            <div class="lbb-popup-question-wrapper">
                <div class="lbb-form-group">
                    <label for="lbb_label">Label</label>
                    <input type="text" id="lbb_label" name="lbb_label" class="lbb-input-field">
                    <div class="empty-label-error lbb-error" style="display:none;">Please enter label</div>
                </div>

                <div class="lbb-form-group">
                    <label for="lbb_name">Name</label>
                    <input type="text" id="lbb_name" name="lbb_name" class="lbb-input-field">
                    <div class="empty-name-error lbb-error" style="display:none;">Please enter name</div>
                </div>

                <div class="lbb-form-group js-select2-wrapper">
                    <label for="lbb_field_type">Field Type:</label>
                    <select name="lbb_field_type" id="lbb_field_type" class="js-select2 lbb-input-field">
                        <option value="textbox">Textbox</option>
                        <option value="textarea">Textarea</option>
                    </select>
                </div>

                <div class="lbb-form-group js-select2-wrapper">
                    <label for="lbb_required_field">Required Field:</label>
                    <select name="lbb_required_field" id="lbb_required_field" class="js-select2 lbb-input-field">
                        <option value="N">N</option>
                        <option value="Y">Y</option>
                    </select>
                </div>
                <div class="lbb-invalid-characters lbb-error">Sorry, these characters are not allowed: spaces, slash, any special characters</div>
            </div>
        </div>

        <footer class="lbb-popup-footer-wrapper">
            <div class="lbb-popup-btn-footer">
                <button id="lbb-save-customfield" class="lbb-btn lbb-btn-primary" type="button">Save</button>
            </div>
        </footer>
    </div>
</script>
<div id="quick-edit-form-main"></div>
<script type="text/html" id="lbbQuickEditForm">
    <div class="lbb-modal-container" id="lbb-modal-create-new" style="display: flex;">
        <div class="lbb-modal-content">
            <header class="lbb-header-wrapper">
                <h2>Edit Answer : {{text}}</h2>
            </header>
            <div class="lbb-modal-body lbb-choice-main">
                
                <div class="lbb-form-group js-select2-wrapper ">
                    <div class="lbb-field-with-subheading">
                        <label for="ansaction_id-{{answer_id}}">Next Action</label>
                        <span class="lbb-sub-heading">What should happen when users select this answer?</span>
                    </div>
                    <select id="ansaction_{{answer_id}}" name="anstype" class="js-select2-old lbb-input-field lbb-answer-action">
                        <!-- <option value="" style="display:none;">Go to a Specific Node </option> -->
                        <option value="start_over">Next Node/Message</option>
                        <option value="url">URL</option>
                        <option value="support">Connect to an Agent</option>
                        <option value="different_bot">Connect to a different bot</option>
                    </select>
                    <div class="answertype-inf answertype-info-node answertype-info-text lbb-alert lbb-alert-warning" style="display:none">
                    <p>No action required here. Connect to next node in the funnel builder.</p>
                    </div>
                    <div class="answertype-inf answertype-info-url answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                    <p>Users will be redirected to this page when they pick this answer choice</p>
                    </div>
                    <div class="answertype-inf answertype-info-support answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                    <p>It'll transfer to a live agent.</p>
                    </div>  
                    <div class="answertype-inf answertype-info-start_over answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                    <p>It'll start over the chat from the selected question. You can select the question below.</p>
                    </div> 
                    <div class="answertype-inf answertype-info-different_bot answertype-info-text lbb-alert lbb-alert-warning" style="display: none;">
                    <p>This node is connected to a different bot funnel.</p>
                    </div>                                            
                </div>

                <div class="lbb-form-group answertype-inf answertype-info-url" style="display:none">
                    <label for="">Redirect to a URL</label>
                    <input type="text" id="url_{{answer_id}}" placeholder="Enter URL" class="lbb-input-field lbb-field-url" data-key="{{answer_id}}" value="">
                    <span class="lbb-error" id="url_{{answer_id}}_error" style="display: none;">Please enter name</span>
                </div>

                <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-start_over" style="display: none;">
                    <label for="start_ques_{{answer_id}}">Which Message <span class="lbb-box-icon" data-tooltip="Only show for Start Over"><span class="dashicons dashicons-warning"></span></span></label>
                    <select id="start_ques_{{answer_id}}" name="start_ques_" class="js-select2-old lbb-input-field lbb-answer-start-ques"></select>                                     
                </div>

                <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-different_bot" style="display: none;">
                    <label for="start_bot_{{answer_id}}">Select a bot to connect to <span class="lbb-box-icon" data-tooltip="Pick the bot to which you want to connect this node"><span class="dashicons dashicons-warning"></span></span></label>
                    <select id="start_bot_{{answer_id}}" name="start_bot_{{answer_id}}" class="js-select2-old lbb-input-field lbb-bot-start-ques"></select>
                    <div class="lbb-bot-error lbb-error" style="display:none;">Please select a bot</div>
                </div>

                <div class="lbb-form-group js-select2-wrapper answertype-inf answertype-info-different_bot_message" style="display: none;">
                    <label for="start_message_{{answer_id}}">Select a message in this bot <span class="lbb-box-icon" data-tooltip="Upon connection to this bot, this will be first message that's displayed"><span class="dashicons dashicons-warning"></span></span></label>
                    <select id="start_message_{{answer_id}}" name="start_message_{{answer_id}}" class="js-select2-old lbb-input-field lbb-bot-start-ques_message"></select>      
                    <div class="lbb-message-error lbb-error" style="display:none;">Please select a message</div>
                </div>

                <div class="lbb-form-group js-select2-wrapper answertype-inf lbb-different-bot-message answertype-info-different_bot_message" style="display: none;">
                    <div class="lbb-alert lbb-alert-warning">
                        <p>Please note: This answer choice will be color coded "green" in the funnel.</p>
                    </div>
                </div>

            </div>
            <footer class="lbb-header-wrapper">
                <input type="hidden" name="" id="lbb-quick-questionid" />
                <input type="hidden" name="" id="lbb-quick-answerid" />
                <button class="lbb-btn lbb-btn-secondary lbb-quick-close">Close</button>
                <button class="lbb-btn lbb-btn-primary lbb-quiz-chatflow-save">Save</button>
            </footer>
        </div>
    </div>
</script>
<script type="text/html" id="lbbQuickEditTagForm">
    <div class="lbb-modal-container" id="lbb-modal-create-new" style="display: flex;">
        <div class="lbb-modal-content">
            <header class="lbb-header-wrapper">
                <h2>Edit Answer : {{text}}</h2>
            </header>
            <div class="lbb-modal-body">
                <div class="lbb-form-group js-select2-wrapper js-select2-multiple lbb-tags-outer-section">
                    <label for="tag_{{answer_id}}">Assign a Tag <span class="lbb-box-icon" data-tooltip="Assigned tags will be sent to the connected email platform"><span class="dashicons dashicons-warning"></span></span></label>

                    <div class="create-tag-section">
                        <div class="lbb-form-same-align">
                            <input class="lbb-input-field save_tag" name="save_tag" value="" placeholder="Enter a Tag Name">
                            <button type="button" class="create_tag lbb-btn lbb-btn-primary">Create</button>
                        </div>
                    </div>

                    <div class="lbb-or lbb-mt-30  lbb-mb-30 lbb-w-100"></div>

                    <label for="tag_{{answer_id}}">Select a Tag</label>
                    <div class="select-tag-section">
                        <select id="tag_{{answer_id}}" name="tag[]" class="lbb-input-field js-select2" multiple>
                    </div>
                    </select>
                </div>
            </div>
            <footer class="lbb-header-wrapper">
                <input type="hidden" name="" id="lbb-quick-questionid" />
                <input type="hidden" name="" id="lbb-quick-answerid" />
                <button class="lbb-btn lbb-btn-secondary lbb-quick-close">Close</button>
                <button class="lbb-btn lbb-btn-primary lbb-quiz-tag-chatflow-save">Save</button>
            </footer>
        </div>
    </div>
</script>
<script type="text/html" id="lbbSeeExample">
    <div class="lbb-modal-container" id="lbb-modal-create-new" style="display: flex;">
        <div class="lbb-modal-content">
            <header class="lbb-header-wrapper">
                <h2>How it works</h2>
            </header>
            <div class="lbb-modal-body">
                <img src="" id="lbb-modal-see-example" />
            </div>
            <footer class="lbb-header-wrapper">
                <button class="lbb-btn lbb-btn-secondary lbb-quick-close">Close</button>
            </footer>
        </div>
    </div>
</script>
<?php echo get_lbb_footer(); ?>
<style>
    .lbb-tag-dynamic-message-field, .lbb-cf-dynamic-message-field{
        display: none !important;
    }
    .lbb-tag-dynamic-message-field.show-tag-property,.lbb-cf-dynamic-message-field.show-cf-property {
        display: block !important;
    }
</style>