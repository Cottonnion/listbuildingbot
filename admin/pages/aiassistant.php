<?php
?><div class="lbb-popup-info-wrapper">
   <div class="lbb-popup-info-wrapper-root">
      <div class="lbb-progressbar-determinate" role="progressbar" aria-valuenow="0" style="">
         <div class="lbb-progressbar-caption">0%</div>
      </div>
   <div class="lbb-progressbar-info" role="progressbar" aria-valuenow="0" style="">
         <p>Larger websites might have more links, so this step might take a tad longer. Hang tight! ⏳...</p>
      </div></div>
   <p class="MuiTypography-root MuiTypography-body1 css-1jbkxwz">Larger websites might have more links, so this step might take a tad longer. Hang tight! <img draggable="false" role="img" class="emoji" alt="⏳" src="https://s.w.org/images/core/emoji/14.0.0/svg/23f3.svg">...</p>
</div>


<div class="lbb-container lbb-vertical-container lbb-ai-document-main-wrapper">
	<div class="lbb-vertical-content-up">
		<div class="lbb-content lbb-text-center lbb-ml-40 lbb-mr-40 lbb-mt-40 ">
			<?php /*<div class="lbb-empty-box-container">
			    <div class="lbb-empty-message">
			    	<h2>You don't have any Assistant yet.</h2>
			    	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
			    </div>
			    <button class="lbb-btn lbb-btn-primary lbb-add-new-content-btn">Click here</button>
			</div> */ ?>


			<?php
			$is_edit_mode = array();
			$is_text_mode = array();
			if(isset($_REQUEST['id'])){
			$chat_id = $_GET['id'];
			$aiassistantmanager= new AiassistantManager();
 			$is_edit_mode = $aiassistantmanager->loadByMapping($chat_id, 'website');
 			$is_text_mode = $aiassistantmanager->loadByMapping($chat_id, 'text');
 			} ?>

			<div class="lbb-upload-content-add-new lbb-upload-content-add-new">
				<div class="lbb-template-selection-options tabs-navigation">
					<div class="lbb-template-selection-item lbb-template-main-selection-item lbb-change-tab lbb-sub-tab lbb-active" id="scrape-sublink" data-tab="scrape-sub"> 
						<i class="bx bx-category"></i> 
						<span>Website</span>
					</div>
					<div class="lbb-template-selection-item lbb-template-main-selection-item lbb-change-tab lbb-sub-tab" id="upload-sublink" data-tab="upload-sub"> 
						<i class="bx bx-cube-alt"></i> 
						<span>Upload files</span>
					</div>
					<div class="lbb-template-selection-item lbb-template-main-selection-item lbb-change-tab lbb-sub-tab" id="textbox-sublink" data-tab="textbox-sub"> 
						<i class="bx bx-cube-alt"></i> 
						<span>Textbox</span>
					</div>
					<div class="lbb-template-selection-item lbb-template-main-selection-item lbb-change-tab lbb-sub-tab" id="faq-sublink" data-tab="faq-sub"> 
						<i class="bx bx-import"></i> 
						<span>FAQ</span>
					</div>
				</div>


				<div id="lbb-content" class="lbb-content-main-div">
			        <div class="lbb-popup-scrape-sub-wrapper lbb-popup-tab-wrapper">
				        <div class="lbb-sub-tab-content">
				        	<div class="lbb-sub-tabs-wrapper lbb-sub-tab-active" id="lbb-link-tab-1">
				        		<div class="lbb-sub-tab-description lbb-alert lbb-alert-warning lbb-mb-20">
                                    <p>Create a custom bot trained with your content by scraping URLs, uploading files or add text content to create a custom bot. Train it with your content. LBB will use your content first and if it does not find the answers to questions users ask, it'll then use OpenAI (configurable). </p>
                                </div>


					        	<div class="lbb-scrape-files-input">
					        		<div class="lbb-form-group">
				                        <label for="lbb-site-url">Start by Fetching Your Website Pages </label>
				                        
				                        <div class="lbb-input-with-btn">
					                        <input id="lbb-site-url" name="lbb-site-url" class="lbb-input-field" type="text" placeholder="https://example.com">
					                        <button id="lbb-get-links" class="lbb-btn lbb-btn-primary">Get Links</button>
					                    </div>
				                    </div>
							        
					        	</div>
						        <div id="lbb-links-container" class="lbb-links-container-selection" <?php echo (!empty($is_edit_mode))? '' : ' style="display: none;"' ?>>
						            <h2>Select Pages to Train Your Bot and Click NEXT</h2>
						            <ul id="lbb-links-list" class="lbb-links-list-wrapper lbb-trained">
						                <?php foreach ($is_edit_mode as $selected_key => $selected_value) { 

						                	$random_id = time().''.$selected_key;
						                	$url = $selected_value['source'];
						                	
						                	 $random_section_id = md5($url);
						                	 echo '<li class="lbb-url-listing-item lbb-trained lbb-url-added" id="lbb-url-list-'.$random_section_id.'">
							                	<div class="lbb-url-listing-inside">
							                		<div class="lbb-root checkbox">
							                			<span class="checkbox-custom-style">
							                                <input id="lbb_scrape_url_'.$random_id.'" type="checkbox" name="lbb_scrape_urls[]" value="'.$url.'" class="custom-checkbox-input lbb-url-listing-checkbox" checked>
							                                <label for="lbb_scrape_url_'.$random_id.'" class="custom--checkbox"></label>
							                            </span>
							                		</div>
							                		<div class="lbb-url-listing-text">
							                			<span class="lbb-url-listing-url-text">'.$url.'</span>
							                		</div>
							                		<div class="lbb-url-listing-action">
								                		<button class="lbb-url-listing-delete-icon"  tabindex="0" type="button" aria-label="delete">
								                			<i class="bx bxs-trash-alt"></i>
								                		</button>
								                	</div>
							                	</div>
							                	
							                </li>';
						                } ?>
						            </ul>

						            <div class="lbb-link-footer-part">
						            	<div></div>
						            	<a href="javascript:void(0);" class="lbb-btn lbb-btn-primary lbb-next-selected-page">Next</a>
						            </div>
						        </div>
					        </div>

					        <div class="lbb-sub-tabs-wrapper lbb-links-container-selection" id="lbb-link-tab-2">
					            <h2>Select Pages to Train Your Bot and Click NEXT</h2>
					            <ul id="lbb-selected-links-list" class="lbb-links-list-wrapper">
					            	<?php foreach ($is_edit_mode as $selected_key => $selected_value) {  

					            		$random_id = time().'2'.$selected_key; 
					            		
					            		$siteURL = $selected_value['source'];
					            		$final_string = $selected_value['content'];
					            		$ai_file_id = $selected_value['ai_file_id'];
					            	?>

						                <li class="lbb-url-listing-item lbb-url-added" id="lbb-url-listing-item-<?php echo $random_id; ?>">
							                <div class="lbb-url-listing-inside">
							                    <div class="lbb-root url-listing-trained-wrapper">
							                        <span class="lbb-url-listing-trained">Trained</span>
							                    </div>
							                    <div class="lbb-url-listing-text">
							                        <span class="lbb-url-listing-url-text"><?php echo $siteURL; ?></span>
							                    </div>
							                    <div class="lbb-url-listing-action">
							                        <button class="lbb-url-listing-edit-icon" tabindex="0" type="button" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>" aria-label="delete">
							                            <i class='bx bx-message-square-edit'></i>  Edit Content
							                        </button>
							                        <button class="lbb-url-listing-delete-icon" data-fileid="<?php echo $ai_file_id; ?>" tabindex="0" type="button" aria-label="delete">
							                            <i class='bx bxs-trash-alt'></i>
							                        </button>
							                    </div>
							                </div>
							                <div class="lbb-textarea-edit-url-content">
							                    <div class="lbb-edit-content-wrapper">
							                        <div class="lbb-form-group">
							                            <label for="ai_url_message_<?php echo $random_id; ?>">Update Content:</label>
							                            <input type="hidden" class="lbb_ai_url_title" id="ai_url_message_<?php echo $random_id; ?>" name="lbb_ai_url_content[<?php echo $random_id; ?>]['title']"  value="<?php echo $siteURL; ?>">

							                            <textarea class="lbb-input-field lbb_ai_url_content" id="ai_url_message_<?php echo $random_id; ?>" name="lbb_ai_url_content[<?php echo $random_id; ?>]['content']"><?php echo $final_string; ?></textarea>

							                            <div class="lbb-popup-listing-edit-text-action">
							                                 <button class="lbb-btn lbb-btn-gray lbb-save-exit-text-for-url" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>">
							                                    Close
							                                </button>
							                                <button class="lbb-btn lbb-btn-secondary  lbb-save-exit-text-for-url" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>">
							                                    Update Content
							                                </button>
							                            </div>
							                        </div>
							                    </div>
							                </div>
							            </li>
							        <?php } ?>
					            </ul>

					            <div class="lbb-link-footer-part">
					            	<a href="javascript:void(0);" class="lbb-btn lbb-btn-gray lbb-back-link-page" data-backid="#lbb-link-tab-1">Back</a>
					            	<a href="javascript:void(0);" class="lbb-btn lbb-btn-primary lbb-submit-selected-page">Generate</a>
					            </div>

					            
					        </div>
				        </div>
			        </div>

			        <div class="lbb-popup-upload-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">
			        	<div class="lbb-sub-tab-description lbb-alert lbb-alert-warning lbb-mb-20">
                                    <p>Create a custom bot trained with your content by scraping URLs, uploading files or add text content to create a custom bot. Train it with your content. LBB will use your content first and if it does not find the answers to questions users ask, it'll then use OpenAI (configurable). </p>
                                </div>

				        <div class="lbb-sub-tab-content">
				        	<?php include(LBB_ABS_URL.'/admin/pages/lbb-assistant-upload.php'); ?>
				        </div>
			        </div>

		        	<div class="lbb-popup-textbox-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">
		        		<div class="lbb-sub-tab-description lbb-alert lbb-alert-warning lbb-mb-20">
                                    <p>Create a custom bot trained with your content by scraping URLs, uploading files or add text content to create a custom bot. Train it with your content. LBB will use your content first and if it does not find the answers to questions users ask, it'll then use OpenAI (configurable). </p>
                                </div>
				        <div class="lbb-sub-tab-content">
				        	<?php
		                		$text_title = isset($is_text_mode[0]['title'])? $is_text_mode[0]['title'] : '';
		                		$text_content = isset($is_text_mode[0]['content'])? $is_text_mode[0]['content'] : '';
		                		$text_id = isset($is_text_mode[0]['id'])? $is_text_mode[0]['id'] : '';


		                	?>

		                	<div class="lbb-thankyou-textbox" style="display: none;">
							    <div class="lbb-sub-tab-description lbb-alert lbb-alert-success lbb-mb-20">
							        <p>Uploaded successfully!</p>
							    </div>
							</div>


					       <div class="lbb-custom-text-content">
						       <div class="lbb-form-group">
	                                <label for="lbb_ai_url_content_title">Enter Name </label>
	                                <input id="lbb_ai_url_content_title" name="lbb_ai_url_content_title" class="lbb-input-field" type="text" value="<?php echo $text_title; ?>">
	                            </div>
						       <div class="lbb-form-group">
	                                <label for="lbb_ai_url_content_text">Enter Description</label>
	                                <textarea id="lbb_ai_url_content_text" name="lbb_ai_url_content_text" class="lbb-input-field"><?php echo $text_content; ?></textarea>
	                            </div>
	                            <input id="lbb_ai_text_hiden" name="lbb_ai_text_hiden" class="lbb-input-field" type="hidden" value="<?php echo $text_id; ?>">

	                            <div class="lbb-link-footer-part">
							        <a href="javascript:void(0);" class="lbb-btn lbb-btn-primary lbb-submit-text-content">Upload Content</a>
							    </div>
						    </div>
				        </div>
			        </div>
			        

			        <div class="lbb-popup-faq-sub-wrapper lbb-popup-tab-wrapper" style="display: none;">
			        	<div class="lbb-sub-tab-description lbb-alert lbb-alert-warning lbb-mb-20">
                                    <p>You can add questions/answers (FAQ) here. It'll be added as quick links to your bot. </p>
                                </div>
				        <div class="lbb-sub-tab-content">
				        		<?php include(LBB_ABS_URL.'/admin/pages/lbb-assistant-faq.php'); ?>
				        </div>
			        </div>
			    </div>
		    </div>

		</div>
	</div>
</div>

<?php /*<section class="lbb-outer-section">
	<div class="lbb-container">
		<div class="lbb-content">


				


			<div class="lbb-datatable">
				<div class="lbb-datatable-btn lbb-datatable-with-column-filter">
					<div class="dropdown lbb-checkbox-dropdown" data-control="checkbox-dropdown">
					  	<label class="dropdown-label">Select</label>
					  	<div class="dropdown-list">
					    	<?php echo $custom_field_dropdown; ?>
					  	</div>
					</div>
				</div>
				<div class="lbb-table-data datatable-contacts">
					<table id='contactsTable' class='lbb-table-style show-table-data display dataTable'>
				        <thead>
				        <tr>
				            <th>Name</th>
				            <th>Email</th>
				            <th>Conversations ID</th>
				            <th>Register Date</th>
				            <?php
				            	if($custom_fields){
									foreach($custom_fields as $custom_field){
										$lbb_field_visiblity = 'lbb-hide-field1';
										if (in_array($custom_field['id'], $explode_custom_field_ids)){
											$lbb_field_visiblity = 'lbb-show-field';
										
										}

											echo "<th class='lbb-field-".$custom_field['id']." ".$lbb_field_visiblity."'>".$custom_field['name'].'</th>';
									}
								}
				             ?>
				        </tr>
				        </thead>
				        
				    </table>
				</div>
			</div>
		</div>
	</div>
</section> */ ?>

<div class="lbb-modal-container" id="lbb-modal-create-new-faq" style="display: none;">
    <div class="lbb-modal-content">
        
        
            <header class="lbb-header-wrapper">
                <h2><span class="platform-name"></span> FAQ</h2>
            </header>
            <div class="lbb-modal-body">
                
                <div class="lbb-faq-screen1">
                    <div class="lbb-faq-selection-options lbb-template-selection-options">
                        <div class="lbb-faq-selection-item lbb-faq-selection-item-cmn lbb-question-fresh lbb-template-selection-item"> 
                            <i class="bx bx-category"></i> 
                            <span>Add from scratch</span>
                        </div>
                        <div class="sqlbb-faq-selection-item lbb-faq-selection-item-cmn lbb-question-bank lbb-template-selection-item"> 
                            <i class="bx bx-category"></i> 
                            <span>Add from question bank</span>
                        </div>
                    </div>
                </div>

				<div class="lbb-faq-screen-create" style="display:none;">
				<div class="lbb-row">
					<div class="lbb-col-12">
						<div class="lbb-form-group">
							<label for="lbb_chat_heading">Question:</label>
							<input type="text" id="faql_question" name="faq_question" value="" class="lbb-input-field" placeholder="Enter Question">
							<div class="lbb-faq-error lbb-faq-error-question"  style="display: none;">Please enter question title</div>
						</div>
					</div>

					<div class="lbb-col-12">
						<div class="lbb-form-group">
							<label for="lbb_chat_heading">Answer:</label>
							<textarea id="faql_answer" name="faq_answer" class="lbb-input-field" type="text" placeholder="Enter Answer"></textarea>
							<div class="lbb-faq-error lbb-faq-error-answer" style="display: none;">Please enter answer</div>
						</div>
					</div>
				</div>
				</div>

                <div class="lbb-faq-screen2" style="display:none">
                        
                            <?php

							global $wpdb;
                            $faq_table = $wpdb->prefix."lbb_faq_master";

                            $faqs = $wpdb->get_results("SELECT * FROM ".$faq_table."", ARRAY_A);

                            if(!empty($faqs)){
                            	echo '<ul>';
                                foreach ($faqs as $key => $faq) { 
                                $id = $faq['id'];    
                                ?>
                            <li>
                                <input type="checkbox" class="lbb-faq-checkbox" name="" id="lbb-faq-checkbox-<?php echo $id ?>" value="<?php echo $id ?>" />
								<textarea style="display:none" id="lbb-faq-answer-<?php echo $id ?>"><?php echo $faq['Answer'] ?></textarea>
								<input style="display:none" type="hidden" id="lbb-faq-question-<?php echo $id ?>" value="<?php echo $faq['question'] ?>" />
                                <label for="lbb-faq-checkbox-<?php echo $id ?>" class="custom--checkbox"><?php echo $faq['question'] ?></label>
                            </li>
                            <?php } 
                            echo '</ul>';
                            }else{
                            	echo '<div class="lbb-alert lbb-alert-warning">There are currently no FAQs in your question bank.  You can click on "Add from scratch" to add a FAQ.</div>';
                            }
                            ?>
                        
                </div>
                
            </div>
            <footer class="lbb-header-wrapper">
                <a href="javascript:void(0)" class="lbb-btn lbb-btn-secondary lbb-faq-close">Close</a>
                <button type="submit" class="lbb-btn lbb-btn-primary lbb-faq-add-btn" style="display:none;">Add</button>
            </footer>
        
    </div>
</div>