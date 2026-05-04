<?php
$fuzzy_search = "on";
$better_search_results = "yes";
$search_weight = "";
$old_content_be = "";
$search_pages = array();
$number_of_results = "8";
$order_by = "title";
$order = "ASC";
$search_heading_for_pages = "We have found [number] pages.";
$search_heading_for_posts = "We have found [number] posts.";
$show_this_text = "You can enter whatever you want to search for in the search box below and our bot will help find it for you from our resources collection!";
$lbb_ai_topic_rules = "";
$lbb_general_rules = "Maintain a respectful and courteous tone in all interactions.

Avoid expressing personal opinions or preferences; provide objective information only.

Limit your responses to questions within your instructed topics.

Decline to respond, share information, or continue the conversation if questions are off-topic.

Share information solely related to the [[CONTEXT]].

Refrain from making speculative statements or predictions.

Provide brief answers without explanations.

Ensure that all responses are accurate and up-to-date to the best of your knowledge.

Do not engage in debates or arguments with users; maintain a neutral stance.

If a question is ambiguous or unclear, request clarification before attempting to answer.

Respect user privacy and do not request or disclose personal information.

Notify users if a question falls outside the scope of what can be answered within the instructed topics.

limit based on token but make sure to complete the sentence";

$custom_posts = array();
$lbb_fuzzy_search_options = get_option('lbb_fuzzy_search_options');
if($lbb_fuzzy_search_options){
    $fuzzy_search = !empty($lbb_fuzzy_search_options['fuzzy_search']) ? $lbb_fuzzy_search_options['fuzzy_search'] : $fuzzy_search;
    $better_search_results = !empty($lbb_fuzzy_search_options['better_search_results']) ? $lbb_fuzzy_search_options['better_search_results'] : $better_search_results;
    $search_weight = !empty($lbb_fuzzy_search_options['search_weight']) ? $lbb_fuzzy_search_options['search_weight'] : $search_weight;
    $old_content_be = !empty($lbb_fuzzy_search_options['old_content_be']) ? $lbb_fuzzy_search_options['old_content_be'] : $old_content_be;
    $number_of_results = !empty($lbb_fuzzy_search_options['number_of_results']) ? $lbb_fuzzy_search_options['number_of_results'] : $number_of_results;
    $search_pages = !empty($lbb_fuzzy_search_options['search_pages']) ? $lbb_fuzzy_search_options['search_pages'] : $search_pages;
    $custom_posts = !empty($lbb_fuzzy_search_options['custom_posts']) ? $lbb_fuzzy_search_options['custom_posts'] : $custom_posts;
    $order_by = !empty($lbb_fuzzy_search_options['order_by']) ? $lbb_fuzzy_search_options['order_by'] : $order_by;
    $order = !empty($lbb_fuzzy_search_options['order']) ? $lbb_fuzzy_search_options['order'] : $order;
    $search_heading_for_pages = !empty($lbb_fuzzy_search_options['search_heading_for_pages']) ? $lbb_fuzzy_search_options['search_heading_for_pages'] : $search_heading_for_pages;
    $search_heading_for_posts = !empty($lbb_fuzzy_search_options['search_heading_for_posts']) ? $lbb_fuzzy_search_options['search_heading_for_posts'] : $search_heading_for_posts;
    $show_this_text = !empty($lbb_fuzzy_search_options['show_this_text']) ? $lbb_fuzzy_search_options['show_this_text'] : $show_this_text;
    $lbb_ai_topic_rules = !empty($lbb_fuzzy_search_options['lbb_ai_topic_rules']) ? $lbb_fuzzy_search_options['lbb_ai_topic_rules'] : $lbb_ai_topic_rules;
    $lbb_general_rules = !empty($lbb_fuzzy_search_options['lbb_general_rules']) ? $lbb_fuzzy_search_options['lbb_general_rules'] : $lbb_general_rules;
    
}
?>


  <div class="lbb-tab-inner-start">
    <form method="POST" id="fuzzysearch-configuration">
        <input type="hidden" name="action" value="save_fuzzysearch_data">
        <div class="lbb-container">
            <div class="lbb-content">
                <?php 
                /*
                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="ai_assistant_language">Fuzzy Search:</label>
                                <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="fuzzy_search_yes" name="lbb_fuzzy_search_options[fuzzy_search]" value="on" <?php echo $fuzzy_search == 'on'?'checked="checked"':''; ?>>
                                        <label for="fuzzy_search_yes">On</label>
                                        <div class="lbb-check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="fuzzy_search_no" name="lbb_fuzzy_search_options[fuzzy_search]" value="off" <?php echo $fuzzy_search == 'off'?'checked="checked"':''; ?>>
                                        <label for="fuzzy_search_no">Off</label>
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
                        <div class="lbb-col-6">
                            <div class="lbb-form-group js-select2-wrapper">
                                <label for="ai_assistant_language">Alternative titles for better search results:</label>
                                <ul class="lbb-radio-btn-wrapper">
                                    <li>
                                        <input type="radio" id="better_search_results_yes" name="lbb_fuzzy_search_options[better_search_results]" value="yes" <?php echo $better_search_results == 'yes'?'checked="checked"':''; ?>>
                                        <label for="better_search_results_yes">Yes</label>
                                        <div class="lbb-check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="better_search_results_no" name="lbb_fuzzy_search_options[better_search_results]" value="no" <?php echo $better_search_results == 'no'?'checked="checked"':''; ?>>
                                        <label for="better_search_results_no">No</label>
                                        <div class="lbb-check">
                                           <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                 */
                ?>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="display:none;">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="search_weight">Search Weight:</label>
                                <input type="text" id="search_weight" name="lbb_fuzzy_search_options[search_weight]" value="<?php echo $search_weight; ?>" class="lbb-input-field">
                                <small>Enter a number from 1 to 100 where 1 = exact match.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="old_content_be">How old can the content (post/page) be?</label>
                                <input type="text" id="old_content_be" name="lbb_fuzzy_search_options[old_content_be]" value="<?php echo $old_content_be; ?>" class="lbb-input-field">
                                <small>In Days</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="contact_form_title">Search in:</label>
                                
                                <div class="lbb-chekbox-list-wrapper">
                                <?php 
                                    $search_page_options = array(
                                        array(
                                            'label' => 'Pages',
                                            'id' => 'search_pages',
                                            'value' => 'search_pages',
                                            'checked' => in_array('search_pages', $search_pages) ? 'checked':''
                                        ),array(
                                            'label' => 'Posts',
                                            'id' => 'search_posts',
                                            'value' => 'search_posts',
                                            'checked' => in_array('search_posts', $search_pages) ? 'checked':''
                                        ),array(
                                            'label' => 'Custom Posts',
                                            'id' => 'search_custom_posts',
                                            'value' => 'search_custom_posts',
                                            'checked' => in_array('search_custom_posts', $search_pages) ? 'checked':''
                                        )
                                    );

                                    
                                    foreach($search_page_options as $search_page_option){
                                        ?>

                                        <div class="lbb-notify-chat-wrapper">
                                            <span class="checkbox-custom-style">
                                            <input id="<?php echo $search_page_option['id']; ?>" type="checkbox" name="lbb_fuzzy_search_options[search_pages][]" value="<?php echo $search_page_option['value']; ?>" class="custom-checkbox-input" <?php echo $search_page_option['checked']; ?>> <label for="<?php echo $search_page_option['id']; ?>" class="custom--checkbox"></label>
                                            </span>
                                            <span class="lbb-notify-chat-name"><?php echo $search_page_option['label']; ?></span>
                                        </div>



                                        <?php
                                    }


                                    ?>


                                </div>
                                <?php 
                                    $show_custom_post = "display:none;";
                                    if(in_array('search_custom_posts', $search_pages)){
                                        $show_custom_post = "";
                                    }
                                    $custom_post_types = get_post_types(array('public' => true, '_builtin' => false));
                                    $exclude_post_types = array('lbb-chatflow', 'lbb-chatflow-action');

                                    echo '<div class="lbb-custom-post-type" style="'.$show_custom_post.'">';
                                    foreach ($custom_post_types as $custom_post_type) {
                                        if (in_array($custom_post_type, $exclude_post_types)) {
                                            continue;
                                        }
                                        ?>
                                        <div class="lbb-notify-chat-wrapper">
                                            <span class="checkbox-custom-style">
                                            <input id="<?php echo $custom_post_type; ?>" type="checkbox" name="lbb_fuzzy_search_options[custom_posts][]" value="<?php echo $custom_post_type; ?>" class="custom-checkbox-input" <?php echo in_array($custom_post_type, $custom_posts) ? 'checked':'' ?>> <label for="<?php echo $custom_post_type; ?>" class="custom--checkbox"></label>
                                            </span>
                                            <span class="lbb-notify-chat-name"><?php echo $custom_post_type; ?></span>
                                        </div>
                                        <?php
                                    }
                                    echo '</div>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="number_of_results">Number of results:</label>
                                <input type="text" id="number_of_results" name="lbb_fuzzy_search_options[number_of_results]" value="<?php //echo $number_of_results; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <div class="lbb-form-group">
                                    <label for="order_by">Order by:</label>
                                    <select id="order_by" name="lbb_fuzzy_search_options[order_by]" class="lbb-input-field">
                                        <option value="title">Title</option>
                                        <option value="id">ID</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                                <div class="lbb-form-group">
                                    <label for="order">Order:</label>
                                    <select id="order" name="lbb_fuzzy_search_options[order]" class="lbb-input-field">
                                        <option value="ASC">Ascending</option>
                                        <option value="DESC">Descending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php /*
                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="search_heading_for_pages">Search Heading for Pages:</label>
                                <input type="text" id="search_heading_for_pages" name="lbb_fuzzy_search_options[search_heading_for_pages]" value="<?php echo $search_heading_for_pages; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="search_heading_for_posts">Search Heading for Posts:</label>
                                <input type="text" id="search_heading_for_posts" name="lbb_fuzzy_search_options[search_heading_for_posts]" value="<?php echo $search_heading_for_posts; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                </div>
                */ ?>
                <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="show_this_text">Show this text :</label>
                                <input type="text" id="show_this_text" name="lbb_fuzzy_search_options[show_this_text]" value="<?php echo $show_this_text; ?>" class="lbb-input-field">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ai Search Options -->
                <?php /*
                <div class="lbb-box lbb-section-bg-box lbb-mb-20" id="ai-assistant-search">
                    <div class="lbb-row">
                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="show_this_text">AI Assistant Search Options:</label>
                            </div>
                        </div>

                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="lbb_ai_topic_rules">Topic Rule:</label>
                                <span>Only answer questions related to these topics</span>
                                <input type="text" id="lbb_ai_topic_rules" name="lbb_fuzzy_search_options[lbb_ai_topic_rules]" value="<?php echo $lbb_ai_topic_rules; ?>" class="lbb-input-field" placeholder="For e.g. health and fitness">
                            </div>
                        </div>

                        <div class="lbb-col-12">
                            <div class="lbb-form-group">
                                <label for="lbb_general_rules">General Rule:</label>
                                <textarea class="lbb-input-field" name="lbb_fuzzy_search_options[lbb_general_rules]" rows="10" cols="100"><?php echo $lbb_general_rules; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                */ ?>
            </div>
        </div>

        <div class="lbb-footer-action-btn lbb-text-center lbb-chatflow-footer-action lbb-popup-btn-footer">
            <button id="lbb-save-fuzzysearch-data" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
            <button  id="lbb-save-fuzzysearch-data-up" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
