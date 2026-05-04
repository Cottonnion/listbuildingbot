<div class="lbb-popup-question-wrapper lbb-popup-tab-wrapper">
    
    <div class="lbb-form-group lbb-alert lbb-alert-warning lbb-question-field lbb-gray-bg-box lbb-heading-section-popup lbb-simple-alert-with-hiw-btn" data-support="outcome">
        <h3 class="lbb-how-it-works-title">How It Works? <span class="dashicons dashicons-arrow-down-alt2"></span></h3>
        <!-- <h3>Here is the question type explaination text, You can change the <br>font and other style elements in the "Styles" tab.</h3> -->
        <div class="lbb-how-it-works lbb-mb-20 lbb-text-right lbb-question-field" data-support="outcome">
            
            <div class="lbb-how-it-works-description">
            <p>Step 1: You need to create the outcomes.<br />
                <a href="<?php echo admin_url( 'admin.php?page=listbuildingbot-settings&tab=outcome' ); ?>" target="_BLANK">Create Outcomes</a>
            </p>
            <p>Step 2: Connect responses/answers to outcomes in the builder (for single choice questions only).</p>
            <p>Step 3: You can then display different messages based on outcome using %outcome% merge tag in the "display outcome" question type.</p>
            </div>
        </div>
    </div>


    <div class="lbb-form-group lbb-text-left lbb-no-answers-found lbb-no-answers-error lbb-no-outcome lbb-empty-box"  style="display:none;">
        <div class="lbb-box-container">
            <span class="dashicons dashicons-warning lbb-box-icon"></span>
            <p>We notice you don't have any outcomes yet.<br />Please create outcomes first. <a href="javascript:void(0);" class="create-new-outcome">Click to create</a> outcomes.</p>
            
        </div>
    </div>

    <div class="lbb-form-group lbb-text-left lbb-no-answers-found lbb-no-answers-error lbb-no-answeroutcome lbb-empty-box"  style="display:none;">
        <div class="lbb-box-container">
            <span class="dashicons dashicons-warning lbb-box-icon"></span>
            <p class="lbb-box-text">We notice you have not connected any answers to outcomes in the funnel builder for single choice questions. Please edit questions and connect answers to outcomes.</p>
            
        </div>
    </div>

    <div class="lbb-popup-body-wrapper lbb-form-group-single">
        <div class="lbb-sub-tab-wrapper lbb-form-group-sub-tab">
            <div id="chatflow-question" class="lbb-change-tabs lbb-sub-tab lbb-active" data-tab="question">Question</div>
            <div id="chatflow-answers" class="lbb-change-tabs lbb-sub-tab" data-tab="answers">Answer</div>
            <!-- <div id="chatflow-advancedRule" class="lbb-change-tabs lbb-sub-tab" data-tab="advancedRule">Advanced Rule</div> -->
        </div>

     

        <div class="lbb-popup-question-wrapper lbb-popup-tab-wrappers lbb-p-0">

            <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper lbb-mt-20">
                <label for="question-name">Message Title (internal use only) * </label>
                <input type="text" id="question-name" name="question-name" class="lbb-input-field" data-target-error="#question-name-error">
                <span class="lbb-error" id="question-name-error">Please enter message name</span>
            </div>
            <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper">
                <div class="lbb-left-right-text ">
                    <h2 for="question-message">Message (displayed in the frontend) *</h2>
                    <div class="lbb-personalize-options">
                        <a href="javascript:void(0);" class="lbb-small lbb-underline-btn lbb-see-example" data-src="<?php echo LBB_URL ?>/admin/images/question-example.jpg">See Example</a>
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
                </div>

                <textarea id="question-message" name="question-message" rows="4" class="lbb-input-field lbb-tinymce-editor" data-target-error="#question-message-error"></textarea>
                <span class="lbb-error" id="question-message-error">Please enter message</span>
            </div>

            <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper" id="question-message-{{uid}}">
                <div id="question-extra-messages"></div>
                <button id="lbb-add-more-question" class="lbb-btn lbb-btn-primary lbb-btn-green" type="button">Add Another Message</button>
            </div>

            <div class="lbb-choice-media lbb-choice-input-image lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper" id="lbb-cm-ques_image">
                <div class="lbb-label-with-notice">
                    <label for="question-message">Message Image  (optional)</label>
                    <div class="lbb-notice">Please note: This image will only show in the frontend (and in the preview) but not in the funnel builder.</div>
                </div>
                <div class="lbb-media-wrapper-main">
                    <div class="lbb-media-wrapper">
                        <div class="lbb-media-img">
                            <img src="" width="100" height="100" id="image_upload_ques_image_src" />
                        </div>
                        <div class="lbb-media-action">
                            <a href="javascript:void(0);" class="lbb-select-media lbb-icon-btn lbb-edit-btn" data-target-input="#image_upload_ques_image" data-key="ques_image"><span class="dashicons dashicons-edit"></span></a>
                            <a href="javascript:void(0);" class="lbb-remove-media lbb-icon-btn lbb-delete-btn" data-target-input="#image_upload_ques_image" data-key="ques_image"><span class="dashicons dashicons-trash"></span></a>
                        </div>
                        <input type="hidden" class="lbb-input-field" id="image_upload_ques_image" name="image_upload_ques_image" value="" />
                    </div>
                </div>
            </div>
            <div class="lbb-heading-with-switch lbb-justify-content-end lbb-show-trigger-automation lbb-inline-radio-right lbb-overflow-unset " data-support="single,text,name,email,phone,country,url,date,attachment,audio" style="display:none">
                <h2>Trigger Automation <span class="lbb-box-icon lbb-tooltip-html-main lbb-tooltip-html-top-right"><span class="dashicons dashicons-warning"></span> <div class="lbb-tooltip-html">If you enable "trigger automation", LBB will automatically trigger your automation rules (set in the automations tab) when users arrive on this question / node.<br/>
                    <br/>
                <b>Please note:</b><br/>
                LBB triggers external automations based on your settings in the "automations" tab. It appears you have used this option:<br/>

                "I'll set it up at question level in the funnel"</div></span></h2>
                <div class="lbb-switch">
                    <input type="checkbox" id="trigger_automation" name="trigger_automation" class="checkstyle">
                    <label for="trigger_automation"><span><span></span></span></label>
                </div>
            </div>
            <div class="lbb-heading-with-switch lbb-justify-content-end lbb-question-field" data-support="single,text,name,email,phone,country,url,date,attachment,audio">
                <h2>Skip Question</h2>
                <div class="lbb-switch">
                    <input type="checkbox" id="skip_question" name="skip_question" class="checkstyle">
                    <label for="skip_question"><span><span></span></span></label>
                </div>
            </div>
            <div class="lbb-heading-with-switch lbb-justify-content-end lbb-question-field" data-support="single,text,name,email,phone,country,url,date,attachment,audio">
                <h2>Hide Connection Line</h2>
                <div class="lbb-switch">
                    <input type="checkbox" id="lbb_hide_connection_line" name="lbb_hide_connection_line" class="checkstyle">
                    <label for="lbb_hide_connection_line"><span><span></span></span></label>
                </div>
            </div>
        </div>

        <div class="lbb-popup-answers-wrapper lbb-mt-20 lbb-popup-tab-wrappers" style="display:none;">
            <div class="lbb-gray-bg-box lbb-question-field" data-support="single,message">
                <div class="lbb-form-group lbb-question-field  lbb-field-image-answer lbb-field-wrapper lbb-mt-20" data-support="single,message">
                    <div class="lbb-left-right-text">
                        <div class="lbb-text-left-side ">
                            <h2>Answer Choices Below <span class="lbb-box-icon" data-tooltip='Use this section to add different responses/answers to your question. The "action" below can be used to trigger the next step.'><span class="dashicons dashicons-warning"></span></span></h2>
                            <a href="javascript:void(0);" class="lbb-underline-btn lbb-small lbb-see-example" data-src="<?php echo LBB_URL ?>/admin/images/answer-example.jpg">See Example</a>
                        </div>
                        <div class="lbb-form-group lbb-text-right lbb-add-new-obj">
                            <div class="lbb-input-wrapper">
                                <button id="addButton" class="lbb-btn lbb-btn-green" >Add More Answers</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="lbb-form-group lbb-question-field  lbb-field-repeater lbb-field-wrapper lbb-single-question-outer lbb-mb-0" data-support="single,message">
                    <ul id="objectList" class="lbb-list-answer"></ul>
                    <div class="lbb-heading-with-switch lbb-justify-content-end lbb-add-new-obj lbb-show-image-ans">
                        <h2>Show Image for Answers</h2>
                        <div class="lbb-switch">
                            <input type="checkbox" id="question-ans-image" name="question-ans-image" class="checkstyle">
                            <label for="question-ans-image"><span><span></span></span></label>
                        </div>
                    </div>
                    <div class="lbb-heading-with-switch lbb-justify-content-end lbb-add-new-obj lbb-show-image-ans">
                        <h2>Hide answer images on mobile <span class="lbb-box-icon lbb-tooltip-left" data-tooltip="Depending on the size of the image, you may want to use this option"><span class="dashicons dashicons-warning"></span></span></h2>
                        <div class="lbb-switch">
                            <input type="checkbox" id="question-ans-mobile-image" name="question-ans-mobile-image" class="checkstyle">
                            <label for="question-ans-mobile-image"><span><span></span></span></label>
                        </div>
                    </div>
                    <div class="answer-per-row" style="display:none;">
                        <div class="lbb-form-group">
                            <div class="lbb-make-box-input-field">
                                <div class="lbb-row">
                                    <div class="lbb-col-12">
                                        <div class="lbb-form-group js-select2-wrapper">
                                            <label for="ans-button-row-column">Answers Per Row:</label>
                                            <select name="lbb_img_answer_button_row_column" id="lbb_img_answer_button_row_column" class="js-select2">
                                                <option value="1">1 column</option>
                                                <option value="2">2 column</option>
                                                <option value="3">3 column</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="answer-image-options" style="display:none;">
                        <div class="lbb-form-group">
                            <div class="lbb-make-box-input-field">
                                <div class="lbb-row">
                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group">
                                            <label for="lbb_answer_image_height">Answer Image Height:</label>
                                            <div class="lbb-answer-slider-outer">
                                                <div class="lbb-slider" data-slider-min="50" data-slider-max="200" data-slider-step="1" data-slider-value="50" data-value-px="px" data-css-variable="lbb-chat-answer-image-height"></div>
                                                <input id="lbb_answer_image_height" name="lbb_answer_image_height" class="lbb-slider-input lbb-input-field" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group js-select2-wrapper">
                                            <label for="image-object-fit">Image Object Fit:</label>
                                            <select name="lbb_answer_image_object_fit" id="lbb_answer_image_object_fit" class="js-select2">
                                                <option value="cover">Cover</option>
                                                <option value="contain">Contain</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-form-group lbb-text-left lbb-no-answers-found lbb-empty-box">
                        <div class="lbb-box-container">
                            <span class="dashicons dashicons-warning lbb-box-icon"></span>
                            <p class="lbb-box-text">Currently you don't have any answer choices.</p>
                            <a href="javascript:void(0);" class="lbb-add-new-rep box-button lbb-btn lbb-btn-black">Click here to add</a>
                        </div>
                    </div>

                    
                </div>

            </div>

            <div class="lbb-add-custom-field-wrapper-v2 lbb-cf-for-single">
                    <div class="lbb-form-group lbb-question-field  lbb-field-wrapper" data-support="single,message">
                        <label for="question-save-property-single" class="lbb-pt-0">Save selected answer to this custom field</label>

                        <div class="lbb-same-row-wrapper lbb-gray-bg-box lbb-pt-15">
                            <div class="lbb-mb-0 bb-left-text-center lbb-form-group">
                                <div class="create-customfield-button">
                                    <a href="javascript:void(0);" class="lbb-btn lbb-btn-primary lbb-w-100 lbb-text-center create-new-customfield">Create a Custom Field</a>
                                </div>
                            </div>
                            
                            <div class="lbb-or lbb-mt-20  lbb-mb-20 lbb-w-100"></div>
                            <div class="lbb-form-group lbb-question-field lbb-mb-0 lbb-p-0 lbb-field-wrapper" data-support="text,name,email,phone,country,url,date,single">
                                <select id="question-save-property-single" name="question-save-property" class="lbb-input-field">
                                </select>                    
                            </div>
                        </div>
                        <div class="lbb-custom-field-edit-link lbb-text-right">
                            <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listbuildingbot-settings&tab=tags" target="_blank" class=" lbb-btn-secondary lbb-btn ">Edit Custom Field</a>
                        </div>
                    </div>
                </div>
            
            
            <!--  -->

            <div class="lbb-left-right-text lbb-justify-content-start lbb-field-wrapper pdf-download-option" style="display:none;">
               <h2>PDF Download <span class="lbb-box-icon" data-tooltip="Do you want to allow users to download a PDF for each outcome?"><span class="dashicons dashicons-warning"></span></span></h2>
                <div class="lbb-heading-with-switch">
                    <div class="lbb-switch">
                        <input type="checkbox" id="enable_pdf_download" name="enable_pdf_download" class="checkstyle">
                        <label for="enable_pdf_download"><span><span></span></span></label>
                    </div>
                </div>
            </div>

            <div class="download-pdf-section lbb-field-wrapper" style="display:none;">
                <div class="lbb-form-group lbb-alert lbb-simple-alert-info lbb-no-pdf" data-support="outcome" style="display:none;">
                    <p>We notice you have not created any PDFs yet. Please create PDFs first to use this feature.</p>
                </div>

                <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-question-field-downloadpdf" data-support="outcome">
                    <label for="download-pdf-button">Button Text</label>
                    <input type="text" id="download-pdf-button" name="download-pdf-button" class="lbb-input-field" data-target-error="#download-pdf-button-error" value="Download PDF">
                    <span class="lbb-error" id="download-pdf-button-error">Please enter placeholder</span>
                </div>

                <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-alert lbb-alert-warning" data-support="outcome">
                    <p>You can customize the download button in the style tab.</p>
                </div>

                <div class="lbb-form-group lbb-question-field lbb-gray-bg-box">
                    <label for="lbb_outcome_id">Select an Outcome:</label>
                    <select id="lbb_outcome_id" name="lbb_outcome_id" class="lbb-input-field">
                        <option value="">Select Outcome</option>
                    </select>
                </div>

                <div class="lbb-form-group lbb-question-field lbb-gray-bg-box">
                    <label for="lbb_pdf_id">Select a PDF:</label>
                    <select id="lbb_pdf_id" name="lbb_pdf_id" class="lbb-input-field">
                        <option value="">Select PDF</option>
                    </select>
                </div>

                <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-outcome-pdf-map-table lbb-text-right">
                    <button id="lbb-connect-pdf-outcome" class="lbb-btn" type="button">Connect PDF to Outcome</button>
                </div>

                <div class="lbb-form-group lbb-outcome-pdf-table">

                </div>
            </div>

            <div class="lbb-left-right-text lbb-field-wrapper outcome-points-section lbb-outcome-listing" style="display:none;">
                <h2>You currently have these outcomes:</h2>
                <ul class="lbb-list-outcome">
                    
                </ul>
                <div class="create-outome-button">
                    <p><a href="javascript:void(0);" class="create-new-outcome">Click to create</a> more outcomes.</p>
                </div>
            </div>

            <div class="lbb-left-right-text lbb-field-wrapper lbb-justify-content-start outcome-points-section" style="display:none;">
                <h2>Do you want to calculate the %outcome% based on the points scored?</h2>
                <div class="lbb-heading-with-switch">
                    <div class="lbb-switch">
                        <input type="checkbox" id="outcome-points-status" name="outcome-points-status" class="checkstyle">
                        <label for="outcome-points-status"><span><span></span></span></label>
                    </div>
                </div>
            </div>

            <div class="lbb-form-group lbb-outcome-field lbb-field-repeater lbb-field-wrapper" style="display:none;">
                <div class="lbb-row lbb-scoring-outcome">
                    <div class="lbb-scoring-outcome-label">
                        <label>Connect scoring range to outcome</label>
                    </div>
                    <div class="lbb-scoring-outcome-btn lbb-text-right">
                        <button id="add-outcome-range" class="lbb-btn lbb-btn-black" >Add More</button>
                    </div>
                </div>

                <table class="table table-bordered outcome-range-table lbb-table">
                    <thead>
                        <tr>
                            <th>Scoring Range</th>
                            <th>Select Outcome</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                                           
                    </tbody> 
                </table>
                <ul id="lbb-dynamic-message-list" class="lbb-list-answer"></ul>
                <div class="lbb-form-group lbb-text-right lbb-add-new-dynamic-message">
                    <div class="lbb-input-wrapper">
                    </div>
                </div>
            </div>

            <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper" data-support="date">
                <label for="lbb-date-format">Select a Format:</label>
                <select id="lbb-date-format" name="lbb-date-format" class="lbb-input-field">
                    <option value="%Y/%m/%d">yy/mm/dd - 1970/09/19</option>
                    <option value="%d/%m/%Y">dd/mm/yy - 19/09/1970</option>
                    <option value="%m/%d/%Y">mm/dd/yy - 09/19/1970</option>
                    <!-- <option value="%d/%m/%y">DD/MM/YY - 19/09/70</option>
                    <option value="%m/%d/%y">MM/DD/YY - 09/19/70</option>
                    <option value="%m/%d/%Y">MM/DD/YYYY - 09/19/1970</option> -->
                </select>
            </div>

            <!--  -->
        </div>
    </div>

</div>

<div class="lbb-popup-answer-wrapper lbb-popup-tab-wrapper" style="display:none">
    <div class="lbb-form-group question-input-field-outer" style="display:none;">
        <label for="question-input-field">Select an option:</label>
        <select id="question-input-field" name="question-input-field" class="lbb-input-field">
            <option value="text">Text</option>
            <option value="textarea">Textarea</option>
            <option value="url">URL</option>
            <option value="number">Number</option>
        </select>
    </div>
    <div class="lbb-form-group lbb-question-field lbb-gray-bg-box lbb-field-wrapper lbb-custom-placeholder" data-support="text,name,email,phone,country,url,date">
        <label for="funnel-placeholder">Placeholder</label>
        <input type="text" id="funnel-placeholder" placeholder="Name" name="funnel-placeholder" class="lbb-input-field" data-target-error="#funnel-placeholder-error">
        <span class="lbb-error" id="funnel-placeholder-error">Please enter placeholder</span>
    </div>

    <div class="lbb-gray-bg-box-off">
        <div class="lbb-form-group lbb-question-field  lbb-gray-bg-box lbb-field-wrapper lbb-outcome-info-card" data-support="attachment" style="display:none;">
            <div class="file-upload-option">
                <h3>Select File Types</h3>
                <div class="fileUploadCommonClass">
                    
                    <label class="file_type_heading">Documents</label>
                    <ul>
                        <li class="file_type_doc_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_doc" id="questionUploadDoc" name="question_upload_type" value="doc" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadDoc"></label>
                            </span>
                            <span class="lbb-platform-name">.doc</span>
                        </li>

                        <li class="file_type_doc_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_xls" id="questionUploadXls" name="question_upload_type" value="xls" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadXls"></label>
                            </span>
                            <span class="lbb-platform-name">.xls</span>
                        </li>

                        <li class="file_type_doc_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_ppt" id="questionUploadPpt" name="question_upload_type" value="ppt" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadPpt"></label>
                            </span>
                            <span class="lbb-platform-name">.ppt</span>
                        </li>

                        <li class="file_type_doc_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_pdf" id="questionUploadPdf" name="question_upload_type" value="pdf" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadPdf"></label>
                            </span>
                            <span class="lbb-platform-name">.pdf</span>
                        </li>

                        <li class="file_type_doc_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_txt" id="questionUploadtxt" name="question_upload_type" value="txt" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadtxt"></label>
                            </span>
                            <span class="lbb-platform-name">.txt</span>
                        </li>
                    </ul>

                    
                </div>

                <div class="fileUploadCommonClass">
                    <label class="file_type_heading">Images</label>
                    <ul>
                        <li class="file_type_image_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_png" id="questionUploadpng" name="question_upload_type" value="png" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadpng"></label>
                            </span>
                            <span class="lbb-platform-name">.png</span>
                        </li>

                        <li class="file_type_image_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_gif" id="questionUploadgif" name="question_upload_type" value="gif" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadgif"></label>
                            </span>
                            <span class="lbb-platform-name">.gif</span>
                        </li>

                        <li class="file_type_image_common">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_jpg" id="questionUploadjpg" name="question_upload_type" value="jpg" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadjpg"></label>
                            </span>
                            <span class="lbb-platform-name">.jpg</span>
                        </li>
                    </ul>
                </div>

                <div class="fileUploadCommonClass">
                    <label class="file_type_heading">Video</label>
                    <ul>
                        <li class="videoTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_mpg" id="questionUploadmpg" name="question_upload_type" value="mpg" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadmpg"></label>
                            </span>
                            <span class="lbb-platform-name">.mpg</span>
                        </li>

                        <li class="videoTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_mov" id="questionUploadmov" name="question_upload_type" value="mov" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadmov"></label>
                            </span>
                            <span class="lbb-platform-name">.mov</span>
                        </li>

                        <li class="videoTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_wmv" id="questionUploadwmv" name="question_upload_type" value="wmv" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadwmv"></label>
                            </span>
                            <span class="lbb-platform-name">.wmv</span>
                        </li>

                        <li class="videoTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_mp4" id="questionUploadmp4" name="question_upload_type" value="mp4" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadmp4"></label>
                            </span>
                            <span class="lbb-platform-name">.mp4</span>
                        </li>
                    </ul>
                </div>

                <div class="fileUploadCommonClass">
                    <label class="file_type_heading">Audio</label>
                    <ul>
                        <li class="audioTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_wav" id="questionUploadwav" name="question_upload_type" value="wav" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadwav"></label>
                            </span>
                            <span class="lbb-platform-name">.wav</span>
                        </li>

                        <li class="audioTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_mp3" id="questionUploadmp3" name="question_upload_type" value="mp3" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadmp3"></label>
                            </span>
                            <span class="lbb-platform-name">.mp3</span>
                        </li>

                        <li class="audioTypeFileCommon">
                            <span class="checkbox-custom-style">
                            <input type="checkbox" data-id="file_type_m4a" id="questionUploadm4a" name="question_upload_type" value="m4a" class="custom-checkbox-input"> <label class="custom--checkbox" for="questionUploadm4a"></label>
                            </span>
                            <span class="lbb-platform-name">.m4a</span>
                        </li>
                    </ul>
                </div>

                <div class="fileUploadCommonClass">
                    <div class="d-flex align-items-center">
                        <label class="file_type_heading">Max Upload Size </label>
                        <div class="lbb-maxFileUploadSize-input d-inline-flex align-items-center">
                            <input type="number" id="maxFileUploadSize" class="maxFileUploadSize form-control" value="2" max="10" min="0">
                            <span style="font-size: 12px;">(In MB)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lbb-add-custom-field-wrapper-v2 lbb-cf-for-other">
        <div class="lbb-form-group lbb-question-field  lbb-field-wrapper" data-support="text,name,email,phone,country,url,date,single">
            <label for="question-save-property" class="lbb-pt-0">Save selected answer to this custom field</label>

            <div class="lbb-same-row-wrapper lbb-gray-bg-box lbb-pt-15">
                <div class="lbb-mb-0 bb-left-text-center lbb-form-group">
                    <div class="create-customfield-button">
                        <a href="javascript:void(0);" class="lbb-btn lbb-btn-primary lbb-w-100 lbb-text-center create-new-customfield">Create a Custom Field</a>
                    </div>
                </div>
                
                <div class="lbb-or lbb-mt-20  lbb-mb-20 lbb-w-100"></div>
                <div class="lbb-form-group lbb-question-field lbb-mb-0 lbb-p-0 lbb-field-wrapper" data-support="text,name,email,phone,country,url,date,single">
                    <select id="question-save-property" name="question-save-property" class="lbb-input-field">
                    </select>                    
                </div>
            </div>
            <div class="lbb-custom-field-edit-link lbb-text-right">
                <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listbuildingbot-settings&tab=tags" target="_blank" class=" lbb-btn-secondary lbb-btn ">Edit Custom Field</a>
            </div>
        </div>
    </div>
    
</div>


<div class="lbb-popup-question-conditions-wrapper lbb-popup-tab-wrapper" style="display:none">
    <div class="lbb-alert lbb-alert-warning">
        <p>For a text question where users can type in responses, if you want to drive different action (branching logic) based on what they enter, you can setup the condition here.<br>
        Each condition will become a node in the funnel builder.</p>
    </div>
    <div class="adv-logic-main lbb-mt-20">
        <div id="branch-if-main"></div>
        <div class="lbb-rule-action lbb-text-right lbb-mt-20">
            <a href="javascript:void(0);" data-uid="{{uid}}" data-isnew="1" class="lbb-condition-edit lbb-btn lbb-btn-primary">Add New Rule</a>
        </div>
    </div>
    <div id="branch-if-main-editform" class="lbb-mt-20" style="display:none;">
        <div id="branch-if-editform-conditions"></div>
        <div id="branch-if-editform-action" class="lbb-text-right lbb-mt-20">
            <a href="javascript:void(0);" id="branch-if-add-condition" class="lbb-btn lbb-btn-secondary ">Add New</a>
        </div>

        <div id="branch-if-editform-question-dropdown">
            <div class="lbb-form-group lbb-condition-field">
                <label for="lbbc_question_map">Next Question</label>
                <select id="lbbc_question_map" name="lbbc_question_map" class="lbb-input-field" data-target-error="#lbbc_question_map_error">
                    <option value="1">Question 1</option>
                    <option value="2">Question 2</option>
                </select>
                <span class="lbb-error" id="lbbc_question_map_error">Please enter message</span>
            </div>
        </div>
    </div>
</div>   