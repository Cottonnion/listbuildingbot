<?php
?>
<section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
    <div class="lbb-container lbb-vertical-container">
        <div class="lbb-vertical-content-up">
            <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                <input type="hidden" id="multimedia_image" name="multimedia_image" value="<?php echo $multimedia_image; ?>">
                <form method="POST" class="lbb-form-settings">

                    <!-- Main Options Start -->
                   
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>How do you want the chat to appear?</h2>
                        </div>
                        <div class="lbb-row lbb-align-items-center">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                     <li>
                                        <input type="radio" id="inline" name="lbb_meta[how_to_show]" value="inline" <?php echo $how_to_show == 'inline' ? 'checked="checked"':''; ?>>
                                        <label for="inline">In-Page</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                     </li>
                                     <li>
                                        <input type="radio" id="minimized" name="lbb_meta[how_to_show]" value="minimized" <?php echo $how_to_show == 'minimized'?'checked="checked"':''; ?>>
                                        <label for="minimized">Minimized</label>
                                        <div class="lbb-check"></div>

                                        <div class="minimized-options minimized-wrapper-show-hide" style="<?php echo $how_to_show == 'minimized'?'':'display: none'; ?>">
                                            


                                           <div class="lbb-upload-box-dynamic">
                                                

                                            
                                            

                                            

                                            <div class="lbb-form-group lbb-chatboat-video-img-text" id="lbb-chatboat-video-img-text">
                                                <label for="video_text">Placeholder Text</label>
                                                <input id="video_text" name="video_text" class="lbb-input-field lbb-tinymce-editor" type="text" value="<?php echo $video_text; ?>">
                                            </div>

                                            <ul>
                                              <li>
                                                <div class="lbb-form-group lbb-radio-buttons"> 
                                                    <input type="radio" name="lbb_meta[lbb_minimized_type_option]" id="show_minimized" value="show_minimized" <?php echo $lbb_minimized_type_option == 'show_minimized'?'checked="checked"':''; ?>>
                                                    <label class="lbb-pl-30" for="show_minimized">Show minimized </label>
                                                    <div class="lbb-check"></div>
                                                </div>
                                              </li>

                                              <li>
                                                 <div class="lbb-form-group lbb-radio-buttons">
                                                    <input type="radio" name="lbb_meta[lbb_minimized_type_option]" id="show_first_welcome" value="show_first_welcome" <?php echo $lbb_minimized_type_option == 'show_first_welcome'?'checked="checked"':''; ?>>
                                                    <label class="lbb-pl-30" for="show_first_welcome">Show minimized but also show the welcome message</label>
                                                    <div class="lbb-check"></div>
                                                 </div>
                                              </li>

                                              <li>
                                                 <div class="lbb-form-group lbb-radio-buttons">
                                                    <input type="radio" name="lbb_meta[lbb_minimized_type_option]" id="show_first_question" value="show_first_question" <?php echo $lbb_minimized_type_option == 'show_first_question'?'checked="checked"':''; ?>>
                                                    <label class="lbb-pl-30" for="show_first_question">Show minimized but also show the first question</label>
                                                    <div class="lbb-check"></div>
                                                 </div>
                                              </li>
                                              
                                           </ul>

                                           </div>
                                        </div>
                                     </li>
                                  </ul>
                                  <div class="inpage-message lbb-mt-20 lbb-mb-20 lbb-alert lbb-alert-warning" style="<?php echo ($how_to_show=='inline') ? '': 'display: none;' ?>"><p>Use this option if you want the chat box to appear in a specific area of your page. In this case, just publish the shortcode (that you'll get on the last tab) on the page. When you use the in-page mode, you need to publish the shortcode on any WordPress page where you want to display the chatbot.</p> </div>

                                <div class="inpage-to-popup-message lbb-mt-20 lbb-alert lbb-alert-info" style="display: none;"><p>As you are switching to popup (minimized view) from in-page (using shortcode), please make sure to remove the shortcode from the pages where you have published it, and select the URL where you want the popup appear.</p> </div>
                                <?php 
                                $hide_popup_option = '';
                                if($lbb_minimized_type_option == 'show_minimized' || $chatflow_type == 'ai_assistant' || $chatflow_type == 'trained_ai' || $chatflow_type == 'livechat'){
                                    $hide_popup_option = 'display:none;';
                                }
                                ?>
                                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-popup-timer" style="<?php echo $hide_popup_option; ?>">
                                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                            <h2>Popup Timer:</h2>
                                        </div>

                                        <div class="lbb-section-bg-box">
                                            <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <p>Do you want the welcome or first question to <b>popout</b> after some time?</p>
                                            </div> -->
                                            <div class="lbb-row">
                                                <div class="lbb-col-12">
                                                    <div class="lbb-form-group">
                                                        <label>Do you want the welcome or first question to popout after some time?</label>
                                                        <div class="lbb-chekbox-list-wrapper">
                                                            <ul class="lbb-radio-btn-wrapper">
                                                                <li>
                                                                    <input type="radio" id="popout-yes" name="lbb_meta[lbb_first_popout_options]" value="yes" <?php echo $lbb_first_popout_options == 'yes'?'checked="checked"':''; ?>>
                                                                    <label for="popout-yes">Yes</label>
                                                                    <div class="lbb-check">
                                                                       <div class="inside"></div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <input type="radio" id="popout-no" name="lbb_meta[lbb_first_popout_options]" value="no" <?php echo $lbb_first_popout_options == 'no'?'checked="checked"':''; ?>>
                                                                    <label for="popout-no">No</label>
                                                                    <div class="lbb-check"></div>
                                                                </li>
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lbb-row lbb_popout_how_many_seconds" style="<?php echo $lbb_first_popout_options == 'no' ? 'display: none;': ''; ?>">
                                                <div class="lbb-col-3">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_first_popout_how_many_seconds">After how many seconds?</label>
                                                        <input type="text" id="lbb_first_popout_how_many_seconds" name="lbb_meta[lbb_first_popout_how_many_seconds]" value="<?php echo $lbb_first_popout_how_many_seconds; ?>" class="lbb-input-field">
                                                        <small>seconds</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lbb-section-bg-box">
                                            <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                                <p>Do you want the welcome or first question to <b>disappear</b> after some time?</p>
                                            </div> -->
                                            <div class="lbb-row">
                                                <div class="lbb-col-12">
                                                    <div class="lbb-form-group">
                                                        <label>Do you want the welcome or first question to disappear after some time?</label>
                                                        <div class="lbb-chekbox-list-wrapper">
                                                            <ul class="lbb-radio-btn-wrapper">
                                                                <li>
                                                                    <input type="radio" id="disappear-yes" name="lbb_meta[lbb_first_disappear_options]" value="yes" <?php echo $lbb_first_disappear_options == 'yes'?'checked="checked"':''; ?>>
                                                                    <label for="disappear-yes">Yes</label>
                                                                    <div class="lbb-check">
                                                                       <div class="inside"></div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <input type="radio" id="disappear-no" name="lbb_meta[lbb_first_disappear_options]" value="no" <?php echo $lbb_first_disappear_options == 'no'?'checked="checked"':''; ?>>
                                                                    <label for="disappear-no">No</label>
                                                                    <div class="lbb-check"></div>
                                                                </li>
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lbb-row lbb_disappear_how_many_seconds" style="<?php echo $lbb_first_disappear_options == 'no' ? 'display: none;': ''; ?>">
                                                <div class="lbb-col-3">
                                                    <div class="lbb-form-group">
                                                        <label for="lbb_first_disappear_how_many_seconds">After how many seconds?</label>
                                                        <input type="text" id="lbb_first_disappear_how_many_seconds" name="lbb_meta[lbb_first_disappear_how_many_seconds]" value="<?php echo $lbb_first_disappear_how_many_seconds; ?>" class="lbb-input-field">
                                                        <small>seconds</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                               </div>
                            </div>
                         </div>
                    </div>
                    <div class="lbb-box lbb-section-bg-box lbb-where-to-show lbb-mb-20" style="<?php echo ($how_to_show!='inline') ? '': 'display: none;' ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Where to show</h2>
                            <p class="website-explanation">Target your visitors by choosing the web pages where you’d like your Bot Funnel to appear</p>
                        </div>
                        <div class="lbb-row lbb-align-items-center">
                            <div class="lbb-col-5">
                               <div class="lbb-form-group js-select2-wrapper js-select2-multiple">
                                  <label for="where_to_show">Select URL:</label>
                                  <select name="lbb_meta[selected_url][]" id="where_to_show" multiple class="js-select2-with-search">
                                    <?php echo lbbGetPagesPostsURLListHtml($chatflow_id); ?>
                                  </select>
                               </div>
                            </div>
                            <div class="lbb-col-2">
                               <span class="lbb-or lbb-mt-20"></span>
                            </div>
                            <div class="lbb-col-5">
                               <div class="lbb-form-group">
                                  <label for="enter_url">Enter URL of the webpage:</label>
                                  <input type="text" id="enter_url" name="lbb_meta[enter_url]" value="<?php echo $enter_url; ?>" class="lbb-input-field" placeholder="Enter URL">
                               </div>
                            </div>
                         </div>
                    </div>
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 when-to-show" style="<?= $how_to_show == 'minimized' ? '' : 'display: none;'; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>When to show</h2>
                            <p class="website-explanation">Have more control over who sees your chatflow by adding rules based on your visitors' identity or behavior</p>
                        </div>
                        <div class="lbb-row">
                            <div class="lbb-col-4">
                               <div class="lbb-form-group js-select2-wrapper">
                                <label>Select option</label>
                                  <select name="lbb_meta[when_to_show]" id="when_to_show" class="js-select2">
                                     <option value="visitor_visit" <?php echo $when_to_show == 'visitor_visit'?'selected':''; ?>>When visitors visit the page</option>
                                     <option value="upon_scroll" <?php echo $when_to_show == 'upon_scroll'?'selected':''; ?>>Upon scroll</option>
                                     <option value="certain_time" <?php echo $when_to_show == 'certain_time'?'selected':''; ?>>After certain time</option>
                                  </select>
                               </div>
                            </div>
                            <div class="lbb-col-3">
                               <div class="lbb-form-group js-select2-wrapper lbb-show-if-upon_scroll <?php echo $show_scroll; ?>">
                                  <label for="select_page_scroll">Select Page scroll:</label>
                                  <select name="lbb_meta[page_scroll_value]" id="select_page_scroll" class="js-select2">
                                     <option value="10" <?php echo $page_scroll_value == '10'?'selected':''; ?>>10%</option>
                                     <option value="20" <?php echo $page_scroll_value == '20'?'selected':''; ?>>20%</option>
                                     <option value="30" <?php echo $page_scroll_value == '30'?'selected':''; ?>>30%</option>
                                     <option value="40" <?php echo $page_scroll_value == '40'?'selected':''; ?>>40%</option>
                                     <option value="50" <?php echo $page_scroll_value == '50'?'selected':''; ?>>50%</option>
                                     <option value="60" <?php echo $page_scroll_value == '60'?'selected':''; ?>>60%</option>
                                     <option value="70" <?php echo $page_scroll_value == '70'?'selected':''; ?>>70%</option>
                                     <option value="80" <?php echo $page_scroll_value == '80'?'selected':''; ?>>80%</option>
                                     <option value="90" <?php echo $page_scroll_value == '90'?'selected':''; ?>>90%</option>
                                     <option value="100" <?php echo $page_scroll_value == '100'?'selected':''; ?>>100%</option>
                                  </select>
                               </div>
                               <div class="lbb-form-group js-select2-wrapper lbb-show-if-enter_time <?php echo $show_timer; ?>">
                                <label>In Seconds</label>
                                  <input type="text" value="<?php echo $time_input_value; ?>" id="certain_type_value" class="lbb-input-field" name="lbb_meta[time_input_value]" placeholder="Enter time in seconds">
                               </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Who should see it?</h2>
                            <p class="website-explanation">Target your visitors by choosing the web pages where you’d like your Bot Funnel to appear</p>
                        </div>
                        <div class="lbb-row">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <ul class="lbb-radio-btn-wrapper">
                                     <li>
                                        <input type="radio" id="all_visitor" name="lbb_meta[who_should_see]" value="all_visitor" <?php echo $who_should_see == 'all_visitor'?'checked="checked"':''; ?>>
                                        <label for="all_visitor">All Visitors</label>
                                        <div class="lbb-check"></div>
                                     </li>
                                     <li>
                                        <input type="radio" id="logged_in" name="lbb_meta[who_should_see]" value="logged_in" <?php echo $who_should_see == 'logged_in'?'checked="checked"':''; ?>>
                                        <label for="logged_in">logged-in members</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                     </li>
                                  </ul>
                               </div>
                            </div>
                        </div>
                    </div>
                    <!-- Trained ai -->

                    <div class="trained-ai-outer trained-ai-options" style="<?php echo $chatflow_type == 'trained_ai' ? '': 'display: none;'; ?>">
                        <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                <h2>Limit</h2>
                            </div>
                            <div class="lbb-row">
                                <div class="lbb-col-12">
                                   <div class="lbb-form-group">
                                        <input type="number" id="lbb_ai_reponse_limit" name="lbb_meta[_lbb_ai_reponse_limit]" value="<?php echo $lbb_ai_reponse_limit; ?>" class="lbb-input-field" placeholder="Enter Limit">
                                   </div>
                                </div>
                            </div>
                        </div>

                        <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                            <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                <h2>Do you want to allow users to contact you and send you a message after they reach the response limit? </h2>
                            </div>
                            <div class="lbb-row">
                                <div class="lbb-col-12">
                                   <div class="lbb-form-group">
                                      <ul class="lbb-radio-btn-wrapper">
                                         <li>
                                            <input type="radio" id="allow_users_no" name="lbb_meta[allow_users_to_contact]" value="no" <?php echo $allow_users_to_contact == 'no'?'checked="checked"':''; ?>>
                                            <label for="allow_users_no">No</label>
                                            <div class="lbb-check"></div>
                                         </li>
                                         <li>
                                            <input type="radio" id="allow_users_yes" name="lbb_meta[allow_users_to_contact]" value="yes" <?php echo $allow_users_to_contact == 'yes'?'checked="checked"':''; ?>>
                                            <label for="allow_users_yes">Yes</label>
                                            <div class="lbb-check">
                                               <div class="inside"></div>
                                            </div>
                                         </li>
                                      </ul>
                                   </div>
                                </div>
                            </div>
                            <div class="lbb-user-options lbb-mt-20 allow-users-to-contact" style="<?= $allow_users_to_contact == 'no' ? 'display: none;' : ''; ?>">
                                        <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-10">
                                            <h2>Customize the content that will be sent to the subscribers:</h2>
                                        </div> -->
                                <div class="lbb-row">
                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group js-select2-wrapper">
                                            <label for="lbb_ai_admin_email">Admin Email: </label>
                                            <input type="text" id="lbb_ai_admin_email" name="lbb_meta[_lbb_ai_admin_email]" value="<?php echo $lbb_ai_admin_email; ?>" class="lbb-input-field">
                                        </div>
                                    </div>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group js-select2-wrapper">
                                            <label for="lbb_ai_from_name">From Name: </label>
                                            <input type="text" id="lbb_ai_from_name" name="lbb_meta[_lbb_ai_from_name]" value="<?php echo $lbb_ai_from_name; ?>" class="lbb-input-field">
                                        </div>
                                    </div>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group js-select2-wrapper">
                                            <label for="lbb_ai_email_subject">Subject: </label>
                                            <input type="text" id="lbb_ai_email_subject" name="lbb_meta[_lbb_ai_email_subject]" value="<?php echo $lbb_ai_email_subject; ?>" class="lbb-input-field">
                                        </div>
                                    </div>
                                </div>
                                <div class="lbb-row">
                                    <div class="lbb-col-6">
                                        <div class="lbb-form-group">
                                            <label for="lbb_ai_email_body">Body:</label>
                                            <textarea class="lbb-input-field" id="lbb_ai_email_body" name="lbb_meta[_lbb_ai_email_body]"><?php echo $lbb_ai_email_body; ?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="<?php echo $chatflow_type == 'trained_ai' ? 'display: none;': ''; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Do you want to hide past conversations on page load/refresh?</h2>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="lbb-chekbox-list-wrapper">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="page-load-yes" name="lbb_meta[page_load_options]" value="yes" <?php echo $page_load_options == 'yes'?'checked="checked"':''; ?>>
                                                <label for="page-load-yes">Yes</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" id="page-load-no" name="lbb_meta[page_load_options]" value="no" <?php echo $page_load_options == 'no'?'checked="checked"':''; ?>>
                                                <label for="page-load-no">No</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="<?php echo $chatflow_type == 'trained_ai' ? 'display: none;': ''; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Back button</h2>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="lbb-chekbox-list-wrapper">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="back-button-yes" name="lbb_meta[lbb_back_button]" value="yes" <?php echo $lbb_back_button == 'yes'?'checked="checked"':''; ?>>
                                                <label for="back-button-yes">Yes</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" id="back-button-no" name="lbb_meta[lbb_back_button]" value="no" <?php echo $lbb_back_button == 'no'?'checked="checked"':''; ?>>
                                                <label for="back-button-no">No</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Made with</h2>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="lbb-chekbox-list-wrapper">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="made-with-yes" name="lbb_meta[lbb_made_with]" value="yes" <?php echo $lbb_made_with == 'yes' ? 'checked="checked"' : ''; ?>>
                                                <label for="made-with-yes">Yes</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" id="made-with-no" name="lbb_meta[lbb_made_with]" value="no" <?php echo $lbb_made_with == 'no' ? 'checked="checked"' : ''; ?>>
                                                <label for="made-with-no">No</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="made-with-options" style="<?php echo $lbb_made_with == 'no' ? 'display: none;' : ''; ?>">
                            <div class="lbb-form-group lbb-chatflow-data">
                                <label for="lbb_made_with_text">Text</label>
                                <input type="text" id="lbb_made_with_text" name="lbb_meta[lbb_made_with_text]" value="<?php echo $lbb_made_with_text; ?>" class="lbb-input-field" placeholder="Enter Text">
                            </div>

                            <div class="lbb-form-group lbb-chatflow-data">
                                <label for="lbb_made_with_link">Link to</label>
                                <input type="text" id="lbb_made_with_link" name="lbb_made_with_link" value="<?php echo $lbb_made_with_link; ?>" class="lbb-input-field" placeholder="Enter Text">
                            </div>

                            <div class="lbb-form-group lbb-chatflow-data">
                                <label for="lbb_made_with_hover_text">On Hover Text</label>
                                <input type="text" id="lbb_made_with_hover_text" name="lbb_meta[lbb_made_with_hover_text]" value="<?php echo $lbb_made_with_hover_text; ?>" class="lbb-input-field" placeholder="Enter Text">
                            </div>
                        </div>
                    </div>
                    <!-- Main Options End -->
                    
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 callout-options" style="<?php echo $how_to_show == 'inline' ? 'display: none;' : ''; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Callout Option</h2>
                        </div>
                        <div class="lbb-row" >
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <label>Display bot callout <span class="lbb-box-icon" data-tooltip="Want to bring extra attention to your bot? Enable this feature. A callout text and arrow pointing to the bot icon/image will show in the frontend."><span class="dashicons dashicons-warning"></span></span></label>
                                </div>
                            </div>

                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <ul class="lbb-radio-btn-wrapper">
                                     <li>
                                        <input type="radio" id="lbb_display_bot_callout_yes" name="lbb_meta[lbb_display_bot_callout]" value="yes" <?php echo $lbb_display_bot_callout == 'yes'?'checked="checked"':''; ?>>
                                        <label for="lbb_display_bot_callout_yes">Yes</label>
                                        <div class="lbb-check"></div>
                                     </li>
                                     <li>
                                        <input type="radio" id="lbb_display_bot_callout_no" name="lbb_meta[lbb_display_bot_callout]" value="no" <?php echo $lbb_display_bot_callout == 'no'?'checked="checked"':''; ?>>
                                        <label for="lbb_display_bot_callout_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                     </li>
                                  </ul>
                                  
                               </div>
                            </div>
                        </div>

                        <div class="lbb-callout-section" style="<?php echo $lbb_display_bot_callout == 'no' ? 'display: none;' : ''; ?>">
                            <div class="lbb-row">
                                <div class="lbb-col-12">
                                   <div class="lbb-form-group">
                                      <div class="lbb-form-group">
                                            <label for="lbb_callout_text">Callout Text:  </label>
                                            <input id="lbb_callout_text" name="lbb_meta[lbb_callout_text]" class="lbb-input-field" type="text" value="<?php echo $lbb_callout_text; ?>">
                                        </div>
                                   </div>
                                </div>
                            </div>

                            <div class="lbb-row">
                                <div class="lbb-col-4">
                                    <div class="lbb-form-group">
                                        <label for="lbb_callout_text_color">Callout Text Color:</label>
                                        <input type="text" name="lbb_meta[lbb_callout_text_color]" id="lbb_callout_text_color" value="<?php echo $lbb_callout_text_color; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_callout_text_color; ?>" data-css-variable="lbb-callout-text-color" />
                                    </div>
                                </div>
                                <div class="lbb-col-4">
                                    <div class="lbb-form-group">
                                        <label for="lbb_font_size">Callout Font Size:</label>
                                        <div class="lbb-slider-outer">
                                            <div class="lbb-slider" data-slider-min="14" data-slider-max="30" data-slider-step="1" data-slider-value="<?php echo $lbb_callout_font_size; ?>" data-value-px="px" data-css-variable="lbb-callout-font-size"></div>
                                            <input id="lbb_font_size" name="lbb_meta[lbb_callout_font_size]" class="lbb-slider-input lbb-input-field" type="text" value="<?php echo $lbb_callout_font_size; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live chat options -->
                    <div class="lbb-divider"></div>
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 livechat-options" style="<?php echo $bot_live_chat_option; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Live Chat</h2>
                            <p class="website-explanation">Do you want to enable live chat option and allow users to be transferred to an agent?</p>
                        </div>
                        <div class="lbb-row" style="<?php echo $live_chat_option; ?>">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <ul class="lbb-radio-btn-wrapper">
                                     <li>
                                        <input type="radio" id="livechat_status_yes" name="lbb_meta[livechat_status]" value="yes" <?php echo $livechat_status == 'yes'?'checked="checked"':''; ?>>
                                        <label for="livechat_status_yes">Yes</label>
                                        <div class="lbb-check"></div>
                                     </li>
                                     <li>
                                        <input type="radio" id="livechat_status_no" name="lbb_meta[livechat_status]" value="no" <?php echo $livechat_status == 'no'?'checked="checked"':''; ?>>
                                        <label for="livechat_status_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                     </li>
                                  </ul>
                                  
                               </div>
                            </div>
                        </div>

                        <div class="lbb-livechat-section show-for-livechat" style="<?php echo $chatflow_type == 'botlivechat' ? '': 'display: none;' ?>">
                            <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                <h2>Admin Display Name </h2>
                            </div> -->
                            <div class="lbb-row">
                                <div class="lbb-col-12">
                                   <div class="lbb-form-group">
                                      <div class="lbb-form-group">
                                            <label for="lbb_livechat_admin_name">Admin Display Name </label>
                                            <input id="lbb_livechat_admin_name" name="lbb_meta[lbb_livechat_admin_name]" class="lbb-input-field" type="text" value="<?php echo $lbb_livechat_admin_name; ?>">
                                        </div>
                                   </div>
                                </div>
                            </div>
                        </div>

                        <div class="lbb-row lbb-livechat-section" style="<?php echo $livechat_section ?>">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group js-select2-wrapper lbb-show-if-enter_time">
                                  <label for="certain_type_value">Transfer to an agent <span class="lbb-box-icon" data-tooltip="Please add one message per line"><span class="dashicons dashicons-warning"></span></span><!-- <a href="javascript:void(0);" class="open-option-form">Click here</a> -->
                                    <br>
                                    <small style="font-weight:400;">In the bot mode, if users want to be transferred to an agent instead of answering the bot questions, what should they type?</small>
                                  </label>
                                  <textarea id="lbb_livechat_words" class="lbb-input-field" name="lbb_optionmeta[lbb_livechat_words]" rows="10"><?php echo $livechat_words ?></textarea>
                               </div>
                            </div>
                        </div>
                        <div class="lbb-row lbb-livechat-section" style="<?php echo $livechat_section ?>">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group js-select2-wrapper lbb-show-if-enter_time">
                                  <label for="certain_type_value">What message to show before they are transferred: <!-- <a href="javascript:void(0);" class="open-option-form">Click here</a> --></label>
                                  <textarea id="lbb_livechat_connect_message" class="lbb-input-field" name="lbb_optionmeta[lbb_livechat_connecting_message]" rows="2"><?php echo $livechat_connecting_message ?></textarea>
                               </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 welcome-message-outer" style="display: none;">
                        <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Display Settings</h2>
                        </div> -->
                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <label for="welcome_message">Welcome Message:</label>
                                    <input type="text" id="welcome_message" name="lbb_meta[welcome_message]" value="<?php echo $welcome_message; ?>" class="lbb-input-field" placeholder="Enter message...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 liveChatIdleTime" style="<?php echo $chatflow_type == 'botlivechat' || $chatflow_type == 'livechat' ? '' : 'display:none;'; ?>">

                        <div class="lbb-sub-tab-description lbb-alert lbb-alert-info lbb-mb-20">
                            <a target="_blank" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=listbuildingbot-settings&tab=live_chat">Click here to configure live chat settings</a>
                        </div>

                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Do you want the live chat to always be available?</h2>
                        </div>
                        <div class="lbb-row">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="idle-time-yes" name="lbb_meta[live_chat_idle_time_enable]" value="yes" <?php echo $live_chat_idle_time_enable == 'yes'?'checked="checked"':''; ?>>
                                        <label for="idle-time-yes">Yes</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <input type="radio" id="idle-time-no" name="lbb_meta[live_chat_idle_time_enable]" value="no" <?php echo $live_chat_idle_time_enable == 'no'?'checked="checked"':''; ?>>
                                        <label for="idle-time-no">No</label>
                                        <div class="lbb-check"></div>
                                     </li>
                                  </ul>

                                    <div class="idle-time-option" style="<?php if($live_chat_idle_time_enable == 'yes'){ echo 'display:none'; } ?>">
                                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                                            <h2>Set live chat availability time period (uses WordPress timezone)</h2>
                                        </div>
                                        <div class="lbb-row">
                                            <div class="lbb-col-6">
                                                <div class="lbb-form-group js-select2-wrapper">
                                                    <label for="start_time">Start Time</label>
                                                    <!-- <input type="text" id="start_time" name="lbb_meta[start_time]" value="<?php //echo $start_time; ?>" class="lbb-input-field"> -->
                                                    <select id="start_time" name="lbb_meta[start_time]" class="js-select2">
                                                        <?php
                                                            for ($hour = 0; $hour < 24; $hour++) {
                                                                $formattedHour = sprintf("%02d", $hour);
                                                                for ($minute = 0; $minute < 60; $minute += 60) {
                                                                    $formattedMinute = sprintf("%02d", $minute);
                                                                    $timeValue = "$formattedHour:$formattedMinute";
                                                                    if($start_time == $timeValue){
                                                                        $startSelected = 'selected';
                                                                    }else{
                                                                        $startSelected = "";
                                                                    }
                                                                    echo "<option ".$startSelected." value=".$timeValue.">$timeValue</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="lbb-col-6">
                                                <div class="lbb-form-group js-select2-wrapper">
                                                    <label for="end_time">End Time</label>
                                                    <!-- <input type="text" id="end_time" name="lbb_meta[end_time]" value="<?php //echo $end_time; ?>" class="lbb-input-field"> -->
                                                    <select id="end_time" name="lbb_meta[end_time]" class="js-select2">
                                                        <?php
                                                            for ($hour = 0; $hour < 24; $hour++) {
                                                                $formattedHour = sprintf("%02d", $hour);
                                                                for ($minute = 0; $minute < 60; $minute += 60) {
                                                                    $formattedMinute = sprintf("%02d", $minute);
                                                                    $timeValue = "$formattedHour:$formattedMinute";
                                                                    if($end_time == $timeValue){
                                                                        $endSelected = 'selected';
                                                                    }else{
                                                                        $endSelected = "";
                                                                    }
                                                                    echo "<option ".$endSelected." value=".$timeValue.">$timeValue</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="display: none;">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Email / Notification</h2>
                            <p class="website-explanation">In the live chat mode, LBB will collect contact details before agent takes over. Select what details you want to collect below: </p>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <ul class="lbb-radio-btn-wrapper">
                                      <li>
                                        <input type="radio" id="just_email" name="lbb_meta[email_capture]" value="just_email" <?php echo $email_capture == 'just_email'?'checked="checked"':''; ?>>
                                        <label for="just_email">Just Email</label>
                                        <div class="lbb-check"></div>
                                      </li>
                                      
                                      <li>
                                        <input type="radio" id="name_and_email" name="lbb_meta[email_capture]" value="name_and_email" <?php echo $email_capture == 'name_and_email'?'checked="checked"':''; ?>>
                                        <label for="name_and_email">Name and Email</label>
                                        <div class="lbb-check"></div>
                                      </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -------- -->
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Skip name and email question if user has already provided email in the bot funnel.</h2>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="lbb-chekbox-list-wrapper">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="skip-name-email-yes" name="lbb_meta[skip_name_email]" value="yes" <?php echo $skip_name_email == 'yes'?'checked="checked"':''; ?>>
                                                <label for="skip-name-email-yes">Yes</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" id="skip-name-email-no" name="lbb_meta[skip_name_email]" value="no" <?php echo $skip_name_email == 'no'?'checked="checked"':''; ?>>
                                                <label for="skip-name-email-no">No</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -------- -->
                    <?php /*
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 livechat-options" style="<?= $chatflow_type == 'botlivechat' || $chatflow_type == 'livechat' ? '' : 'display: none;'; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>When a new chat request comes in, send email to admin</h2>
                        </div>

                        <div class="lbb-row">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="lbb-chekbox-list-wrapper">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="notify-chat-yes" name="lbb_meta[lbb_notify_chat]" value="yes" <?php echo $lbb_notify_chat == 'yes'?'checked="checked"':''; ?>>
                                                <label for="notify-chat-yes">Yes</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" id="notify-chat-no" name="lbb_meta[lbb_notify_chat]" value="no" <?php echo $lbb_notify_chat == 'no'?'checked="checked"':''; ?>>
                                                <label for="notify-chat-no">No</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lbb-row livechat-admin-emails" style="<?php echo $lbb_notify_chat == 'email_admin'?'':'display: none;'; ?>">
                            <div class="lbb-col-12">
                                <div class="lbb-form-group">
                                    <div class="admin-emails">
                                        <div class="lbb-form-group">
                                            <label for="welcome_message">Enter Admin Email Ids (comma-separated)</label>
                                            <input type="text" id="admin_emails" name="lbb_meta[admin_emails]" value="<?php echo $admin_emails; ?>" class="lbb-input-field" placeholder="Enter emails...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    */ ?>

                    <!-- Search Options -->
                    <div class="lbb-divider"></div>
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Search Feature</h2>
                            <span>Want to allow users to search for content in your posts/pages? <span class="lbb-box-icon" data-tooltip="Search for content in your WordPress pages/posts. If you want to allow users to type in their questions and have LBB search your WordPress content (pages/posts) to find a match and respond with the right links, enable this feature."><span class="dashicons dashicons-warning"></span></span></span>
                        </div>
                        <div class="lbb-row">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <div class="lbb-form-group">
                                        <label for="lbb_livechat_admin_name">Enable Search <span class="lbb-box-icon" data-tooltip="If you want to add a tab where users can type in what they are looking for and you want LBB to search your posts/pages, you can do enable this feature."><span class="dashicons dashicons-warning"></span></span></label>
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="lbb_enable_search_yes" name="lbb_meta[lbb_enable_search]" value="yes" <?php echo $lbb_enable_search == 'yes'?'checked="checked"':''; ?>>
                                                <label for="lbb_enable_search_yes">Yes</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="lbb_enable_search_no" name="lbb_meta[lbb_enable_search]" value="no" <?php echo $lbb_enable_search == 'no'?'checked="checked"':''; ?>>
                                                <label for="lbb_enable_search_no">No</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                               </div>
                            </div>
                            <div class="lbb-enable-search-outer" style="<?php echo $lbb_enable_search == 'yes' ? '' : 'display: none;' ?>">
                                  <div class="lbb-col-12">
                                      <div class="lbb-form-group">
                                          <div class="lbb-chekbox-list-wrapper">
                                                <div class="search_options lbb_search_options_wrapper">
                                                    <div class="lbb-col-12 lbb-bb lbb-mt-10">
                                                        <div class="lbb-form-group">
                                                            <div class="lbb-form-group">
                                                                <label for="lbb_livechat_exact_match">Exact Match</label>
                                                          <ul class="lbb-radio-btn-wrapper lbb-radio-column">
                                                              <li>
                                                                  <input type="radio" id="lbb_livechat_exact_match_same" name="lbb_meta[lbb_livechat_exact_match]" value="exact_match" <?php echo $lbb_livechat_exact_match == 'exact_match'?'checked="checked"':''; ?>>
                                                                  <label for="lbb_livechat_exact_match_same">Exact Match: Same Phrase</label>
                                                                  <div class="lbb-check"></div>
                                                              </li>
                                                              <li>
                                                                  <input type="radio" id="lbb_livechat_exact_match_all_word" name="lbb_meta[lbb_livechat_exact_match]" value="all_words_match" <?php echo $lbb_livechat_exact_match == 'all_words_match'?'checked="checked"':''; ?>>
                                                                  <label for="lbb_livechat_exact_match_all_word">All Words Match:  All words should be there but does not to be together. It can be anywhere in the content</label>
                                                                  <div class="lbb-check">
                                                                     <div class="inside"></div>
                                                                  </div>
                                                              </li>
                                                              <li>
                                                                  <input type="radio" id="lbb_livechat_exact_match_any_word" name="lbb_meta[lbb_livechat_exact_match]" value="one_word_match" <?php echo $lbb_livechat_exact_match == 'one_word_match'?'checked="checked"':''; ?>>
                                                                  <label for="lbb_livechat_exact_match_any_word">Any One Word Found</label>
                                                                  <div class="lbb-check">
                                                                     <div class="inside"></div>
                                                                  </div>
                                                              </li>
                                                          </ul>
                                                      </div>
                                                 </div>
                                             </div>
                                              <div class="lbb-col-12 lbb-bb lbb-mt-10">
                                                 <div class="lbb-form-group">
                                                    <div class="lbb-form-group">
                                                          <label for="lbb_livechat_admin_name">Show Latest Posts</label>
                                                          <ul class="lbb-radio-btn-wrapper">
                                                              <li>
                                                                  <input type="radio" id="lbb_show_results_yes" name="lbb_meta[lbb_show_results]" value="yes" <?php echo $lbb_show_results == 'yes'?'checked="checked"':''; ?>>
                                                                  <label for="lbb_show_results_yes">Yes</label>
                                                                  <div class="lbb-check"></div>
                                                              </li>
                                                              <li>
                                                                  <input type="radio" id="lbb_show_results_no" name="lbb_meta[lbb_show_results]" value="no" <?php echo $lbb_show_results == 'no'?'checked="checked"':''; ?>>
                                                                  <label for="lbb_show_results_no">No</label>
                                                                  <div class="lbb-check">
                                                                     <div class="inside"></div>
                                                                  </div>
                                                              </li>
                                                          </ul>
                                                      </div>
                                                 </div>
                                              </div>
                                                  
                                              <div class="lbb-col-12 lbb-bb lbb-mt-10 how-many-outer" style="<?= $lbb_show_results == 'no' ? 'display: none;' : ''; ?>">
                                                  <div class="lbb-form-group">
                                                      <label for="lbb_how_many">How many:</label>
                                                      <input type="number" id="lbb_how_many" name="lbb_meta[lbb_how_many]" value="<?php echo $lbb_how_many; ?>" class="lbb-input-field">
                                                  </div>
                                              </div>

                                              <div class="lbb-col-12">
                                                  <div class="lbb-form-group">
                                                      <label for="lbb_show_text">Show this text:</label>
                                                      <input type="text" id="lbb_show_text" name="lbb_meta[lbb_show_text]" value="<?php echo $lbb_show_text; ?>" class="lbb-input-field">
                                                  </div>
                                              </div>
                                          </div>
                                          
                                         
                                      </div>
                                  </div>
                              </div>
                            </div>
                            
                            
                        </div>


                    </div>

                    <?php 
                    $hide_for_ai_assistant = "";
                    if((isset($_REQUEST['type']) && $_REQUEST['type'] == 'ai_assistant') || $chatflow_type == 'trained_ai') {
                        $hide_for_ai_assistant = "display:none;";
                    } ?>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="<?php echo $hide_for_ai_assistant; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Custom Trained Bot / Knowledge Base</h2>
                            <span>Do you want to allow users to search the knowledge base (trained bot) for answers to their questions?</span>
                        </div>

                        <?php 
                        $lbb_check_for_trained_bot = "Y";
                        $args = array(
                            'post_type' => 'lbb-chatflow',
                            'posts_per_page' => -1
                        );
                        $obituary_query = new WP_Query($args);
                        if ($obituary_query->have_posts()) {
                            while ($obituary_query->have_posts()) : $obituary_query->the_post();
                                if(get_post_meta(get_the_ID(), '_chatflow_type', true)){
                                    $chatflow_type = get_post_meta(get_the_ID(), '_chatflow_type', true);
                                    if($chatflow_type == 'trained_ai'){
                                        $lbb_check_for_trained_bot = "Y";
                                    }
                                }
                            endwhile;
                            wp_reset_postdata();
                        }
                        ?>

                        <input type="hidden" name="lbb_check_for_trained_bot" value="<?php echo $lbb_check_for_trained_bot; ?>">
                        <div class="row">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <div class="lbb-form-group">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="lbb_custom_trained_bot_yes" name="lbb_meta[lbb_custom_trained_bot]" value="yes" <?php echo $lbb_custom_trained_bot == 'yes'?'checked="checked"':''; ?>>
                                                <label for="lbb_custom_trained_bot_yes">Yes</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="lbb_custom_trained_bot_no" name="lbb_meta[lbb_custom_trained_bot]" value="no" <?php echo $lbb_custom_trained_bot == 'no'?'checked="checked"':''; ?>>
                                                <label for="lbb_custom_trained_bot_no">No</label>
                                                <div class="lbb-check">
                                                   <div class="inside"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="lbb-error lbb-no-trainedbot" style="display: none;">Do you want to allow users to search the knowledge base for answers to their questions?</div>

                                    <div class="lbb-col-5 lbb-select-trained-bot-outer" style="<?php echo $lbb_custom_trained_bot == 'no'?'display: none;':''; ?>">
                                       <div class="lbb-form-group js-select2-wrapper">
                                          <label for="lbb_select_trained_bot">Select Trained AI:</label>
                                          <select name="lbb_meta[lbb_select_trained_bot]" id="lbb_select_trained_bot" class="js-select2-with-search">
                                            <?php echo lbbGetAiAssistant($chatflow_id); ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="lbb-error lbb-select-trainedbot" style="display: none;">Please select Trained AI</div>
                               </div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $show_for_funnel = "display:none;";
                    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);
                    if($chatflow_type == 'logicbot') {
                        $show_for_funnel = "";
                    } ?>
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-show-for-funnel" style="<?php echo $show_for_funnel; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Do not create guest account on page load <span class="lbb-box-icon" data-tooltip="By default, LBB will create a guest account to store all the chat messages. If users opt-in, it'll replace guest account details with the contact details of the user. If you don't want LBB to create guest accounts, set this to YES."><span class="dashicons dashicons-warning"></span></span></h2>
                            <span>Only create account after users opt-in. Do not create guest accounts.</span>
                        </div>

                        

                        <div class="row">
                            <div class="lbb-col-12">
                               <div class="lbb-form-group">
                                  <div class="lbb-form-group">
                                        <ul class="lbb-radio-btn-wrapper">
                                            <li>
                                                <input type="radio" id="create_guest_account_yes" name="lbb_meta[create_guest_account]" value="yes" <?php echo $create_guest_account == 'yes'?'checked="checked"':''; ?>>
                                                <label for="create_guest_account_yes">Yes</label>
                                                <div class="lbb-check"></div>
                                            </li>
                                            <li>
                                                <input type="radio" id="create_guest_account_no" name="lbb_meta[create_guest_account]" value="no" <?php echo $create_guest_account == 'no'?'checked="checked"':''; ?>>
                                                <label for="create_guest_account_no">No</label>
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

                </form>

                <div class="lbb-chatflow-save-action lbb-chatflow-footer-action">
                    <button class="lbb-btn lbb-btn-black lbb-back-chatflow" style="display: none;">Back</button>
                    <button class="lbb-btn lbb-btn-black lbb-save-chatflow">Save</button>
                    <button class="lbb-btn lbb-btn-black lbb-next-chatflow">Save &amp; Next</button>
                </div>
            </div>

            <div id="option-propwrap" class="lbb-popup-main">
                <div id="option-properties" class="lbb-popup-container">
                </div>
            </div>

            <div id="propwrapmapping" class="lbb-popup-main lbb-mapping-popup">
                <div class="lbb-popup-container">
                    <div class="lbb-modal-start">
                        <header class="lbb-modal-header">
                            <div class="lbb-modal-header-inner">
                                <h2>Add/Edit Mapping</h2>
                                <div id="close" class="lbb-delete-icon">
                                    <span class="dashicons dashicons-no-alt"></span>
                                </div>
                            </div>
                        </header>

                        <div class="lbb-popup-question-wrapper">
                            <div class="lbb-popup-body-wrapper">
                                <div class="lbb-popup-outcome-wrapper">
                                </div>
                            </div>

                            <div class="lbb-popup-body-wrapper">
                                <div class="lbb-popup-pdf-wrapper">
                                </div>
                            </div>
                        </div>

                        <footer class="lbb-popup-footer-wrapper">
                            <div class="lbb-popup-btn-footer">
                                <button id="lbb-save-mapping" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>