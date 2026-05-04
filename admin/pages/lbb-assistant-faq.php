<?php
    $chatflow_id = $_GET['id'];
    global $wpdb;
    $faqs_connected = get_post_meta($chatflow_id, '_lbb_faqs', true);
    $faqs = array();
    if(!empty($faqs_connected)){
        $faq_table = $wpdb->prefix."lbb_faq_master";
        $faqs = $wpdb->get_results("SELECT * FROM ".$faq_table." WHERE id IN(".$faqs_connected.")", ARRAY_A);
    }
?>

<section class="lbb-faq-section-start <?php echo empty($faqs) ? 'llb-faq-empty' : ''; ?>">
    <div class="lbb-faq-ai-container">
        <div class="lbb-faq-empty-box lbb-empty-box">
            <div class="lbb-box-container">
                <span class="dashicons dashicons-warning lbb-box-icon"></span>
                <p class="lbb-box-text">Currently you don't have any FAQ.</p>
                <a href="javascript:void(0);" class="lbb-add-more-faq box-button lbb-btn lbb-btn-black">Click here to add</a>
            </div>
        </div>
                
        <div class="lbb-faq-ai-container-up">
            <div class="lbb-faq-ai-full">
                <div class="lbb-faq-ai-row">
                    <div class="lbb-faq-ai-preview-form-wrapper">
                        <div class="lbb-accordion lbb-faq-items">
                            <?php

                            if(!empty($faqs)){

                                foreach ($faqs as $key => $faq) { ?>
                                    
                                    <div class="lbb-accordion-item show_hide_icon_styles lbb-faq-item" id="ff-<?php echo $faq['id'] ?>">
                                        <div class="lbb-accordion-header">
                                            <h2>Question : <?php echo $faq['question']; ?></h2>
                                            <div class="lbb-accordion-right-content">
                                                <button class="lbb-faq-delete" tabindex="0" type="button" aria-label="delete"><i class="bx bxs-trash-alt"></i></button>
                                                <span class="lbb-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="lbb-accordion-content" style="display: none;">
                                            <div class="lbb-row">
                                                <div class="lbb-col-12">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_chat_heading">Question:</label>
                                                        <input type="text" id="faq_question" name="faq_question" value="<?php echo $faq['question']; ?>" class="lbb-input-field" placeholder="Enter Question">
                                                        <div class="lbb-faq-error lbb-faq-error-question" style="display:none;">Please enter question title</div>
                                                    </div>
                                                </div>

                                                <div class="lbb-col-12">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_chat_heading">Answer:</label>
                                                        <textarea id="faq_answer" name="faq_answer" class="lbb-input-field" type="text" placeholder="Enter Answer"><?php echo (isset($faq['Answer'])) ? $faq['Answer'] : $faq['answer']; ?></textarea>
                                                        <input type="hidden" id="" name="lbb_faq_id[]" value="<?php echo $faq['id']; ?>" class="lbb_faq_ids lbb-input-field" placeholder="Enter">
                                                        <div class="lbb-faq-error lbb-faq-error-answer" style="display:none;">Please enter answer</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                <?php 
                                }
                            }
                            ?>
                            <?php /*
                            <div class="lbb-accordion-item show_hide_icon_styles lbb-faq-items">
                                <div class="lbb-accordion-header">
                                   <h2>Question 1</h2>
                                    <div class="lbb-accordion-right-content">
                                        <span class="lbb-arrow"></span>
                                    </div>
                                </div>
                                <div class="lbb-accordion-content" style="display: none;">
                                    <div class="lbb-row">
                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group">
                                                <label for="lbb_chat_heading">Question:</label>
                                                <input type="text" id="enter_url" name="faq_question" value="How are you?" class="lbb-input-field" placeholder="Enter">
                                            </div>
                                        </div>

                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group">
                                                <label for="lbb_chat_heading">Answer:</label>
                                                <textarea id="lbb_chatbot_description" name="faq_answer" class="lbb-input-field" type="text">We are happy to answer your questions</textarea>
                                            </div>
                                        </div>

                                        
                                    </div>
                                </div>
                            </div>
                            <div class="lbb-accordion-item show_hide_icon_styles">
                                <div class="lbb-accordion-header">
                                   <h2>Question 2</h2>
                                    <div class="lbb-accordion-right-content">
                                        <span class="lbb-arrow"></span>
                                    </div>
                                </div>
                                <div class="lbb-accordion-content" style="display: none;">
                                    <div class="lbb-row">
                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group">
                                                <label for="lbb_chat_heading">Question:</label>
                                                <input type="text" id="enter_url" name="faq_question" value="What did you do on Saturday evening?" class="lbb-input-field" placeholder="Enter">
                                            </div>
                                        </div>

                                        <div class="lbb-col-12">
                                            <div class="lbb-form-group">
                                                <label for="lbb_chat_heading">Answer:</label>
                                                <textarea id="lbb_chatbot_description" name="faq_answer" class="lbb-input-field" type="text">We went to see a film.</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> */ ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lbb-link-footer-part">
        <button class="lbb-btn lbb-btn-black lbb-add-more-faq" title="Save">Add More</button>
        <button class="lbb-btn lbb-btn-black lbb-save-faq" title="Save">Save</button>
    </div>
    
</section>

<script type="text/html" id="lbbFaqForm">
<div class="lbb-accordion-item show_hide_icon_styles lbb-faq-item" id="ff-{{QId}}">
    <div class="lbb-accordion-header">
        <h2>Question : {{QVal}}</h2>
        <div class="lbb-accordion-right-content">
            <button class="lbb-faq-delete" tabindex="0" type="button" aria-label="delete"><i class="bx bxs-trash-alt"></i></button>
            <span class="lbb-arrow"></span>
        </div>
    </div>
    <div class="lbb-accordion-content" style="display: none;">
        <div class="lbb-row">
            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <label for="lbb_chat_heading">Question:</label>
                    <input type="text" id="faq_question" name="faq_question" value="{{QVal}}" class="lbb-input-field" placeholder="Enter Question">
                    <div class="lbb-faq-error lbb-faq-error-question" style="display:none;">Please enter question title</div>
                </div>
            </div>

            <div class="lbb-col-12">
                <div class="lbb-form-group">
                    <label for="lbb_chat_heading">Answer:</label>
                    <textarea id="faq_answer" name="faq_answer" class="lbb-input-field" type="text" placeholder="Enter Answer">{{AVal}}</textarea>
                    <input type="hidden" id="" name="lbb_faq_id[]" value="{{QId}}" class="lbb_faq_ids lbb-input-field" placeholder="Enter">
                    <div class="lbb-faq-error lbb-faq-error-answer" style="display:none;">Please enter answer</div>
                    
                </div>
            </div>

           

        </div>
    </div>
</div>
</script>

