<?php get_lbb_header(); ?>

<?php 
$args = array(
    'post_type' => 'lbb-chatflow',
    'posts_per_page' => -1
);
$obituary_query = new WP_Query($args); ?>

<div class="lbb-header-part-content">
	<h1 class="wp-heading-inline">Manage Bot Funnels</h1>
</div>
<section class="lbb-outer-section lbb-vertical-section">
	<div class="lbb-container lbb-vertical-container <?php echo ($obituary_query->have_posts())? '' : 'lbb-vertical-container-center'; ?> ">
		<div class="lbb-vertical-content-up">
			<div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40 llb-bg-box-with-spacing">

				<?php
				
				$ai_assistant = get_option('lbb_ai_assistant');
				$openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';
				
				
				if($openaiModel == 'gpt-3.5-turbo-16k' || $openaiModel == 'gpt-3.5-turbo'){
					?>
					<div class="lbb-notice-warn lbb-alert lbb-alert-warning lbb-mb-20">
					<p>You are currently using Open AI GPT model '<?php echo $openaiModel ?>'. It's deprecated by OpenAI.</p>
					<p>Please go to the LBB > <a href="<?php echo admin_url('admin.php?page=listbuildingbot-settings'); ?>" target="_BLANK">settings</a> page and update to a newer model.</p>
					</div>
				<?php } ?>

				<?php if ($obituary_query->have_posts()) { ?>
					<div class="lbb-datatable">
						<div class="lbb-datatable-btn">
							<a class="lbb-btn lbb-btn-primary" id="lbb-select-pages-chatflow" href="javascript:void(0);">Selected Pages</a>
							<a class="create-chatflow lbb-btn lbb-btn-primary" id="lbb-create-new-chatflow" href="javascript:void(0);">Create a new List Building Bot</a>
							<!-- <a class="create-chatflow lbb-model-open-btn lbb-btn lbb-btn-primary" id="lbb-create-new-chatflow" data-lbbmodel="#lbb-modal-create-new" href="javascript:void(0);">Create a new List Building Bot</a>
							<a class="create-chatflow lbb-model-open-btn lbb-btn lbb-btn-primary" id="lbb-import-chatflow" data-lbbmodel="#lbb-modal-import" href="javascript:void(0);">Import</a>
							<a class="lbb-model-open-btn lbb-btn lbb-btn-primary" data-lbbmodel="#lbb-select-template" href="javascript:void(0);">Select Template</a> -->
						</div>
						<table class="lbb-table lbb-table-style" >
							<thead>
								<tr>
									<th class="lbb-w-180p-old">Name</th>
									<th class="lbb-w-100p-old">Type</th>
									<th class="lbb-w-100p-old">Page</th>
									<?php /*<th class="lbb-text-center lbb-w-100p">Date</th>*/ ?>
									<th class="lbb-text-center lbb-w-200p-old">Shortcode</th>
									<th class="lbb-text-center lbb-w-70p">Status</th>
									<th class="lbb-text-center lbb-w-100p-old">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i=1;
								while ($obituary_query->have_posts()) : $obituary_query->the_post();
									$chatflow_name = "";
									if(get_post_meta(get_the_ID(), '_chatflow_type', true)){
										$chatflow_type = get_post_meta(get_the_ID(), '_chatflow_type', true);
										if($chatflow_type == 'livechat'){
											$chatflow_name = "Live Chat";
										}else if($chatflow_type == "botlivechat"){
											$chatflow_name = "Logic Bot + Live Chat";
										}else if($chatflow_type == "logicbot"){
											$chatflow_name = "Logic Bot";
										}else{
											$chatflow_name = "AI Assistant";
										}
									}

									$chatflow_status = "Y";
									if(get_post_meta(get_the_ID(), 'lbb_chatflow_status', true)){
										$chatflow_status = get_post_meta(get_the_ID(), 'lbb_chatflow_status', true);
									}

									$selected_url = "";
									//if(get_post_meta(get_the_ID(), 'selected_url', true)){
										if(get_post_meta(get_the_ID(), 'how_to_show', true)){
											$how_to_show = get_post_meta(get_the_ID(), 'how_to_show', true);
											if($how_to_show == 'minimized'){
												$selected_url = get_post_meta(get_the_ID(), 'selected_url', true);
												$enter_url = get_post_meta(get_the_ID(), 'enter_url', true);
												$enter_url_id = "";
										        if(!empty($enter_url)){
										            $enter_url_id = url_to_postid($enter_url);
										        }
										        if(!empty($selected_url)){
										        	$parts = explode(',', $selected_url);
													if(!empty($enter_url_id)){
											            $parts[] = $enter_url_id;
											        }
													// Check if there are three or more elements in the array
													if (count($parts) >= 3) {
													    $resultArray = array_slice($parts, 0, 3);
													    $resultArray[] = '...';
													    $resultString = implode(', ', $resultArray);
													} else {
													    $resultString = implode(', ', $parts);
													}

													$selected_url = '<a Class="lbb-show-pages-chatflow" href="javascript:void(0);">'.$resultString.'</a>';
										        }else if(!empty($enter_url_id)){
										        	$selected_url = '<a Class="lbb-show-pages-chatflow" href="javascript:void(0);">'.$enter_url_id.'</a>';
										        }
												
											}
										}
									//}

								    ?>
								    <tr data-chatflow-id="<?php echo get_the_ID(); ?>">
								    	<td class="lbb-strong-link-type "><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot&action=edit&id=<?php echo get_the_ID(); ?>"><?php the_title(); ?></a></td>
								    	<td><?php echo $chatflow_name; ?></td>
								    	<td class="td-selected-url"><?php echo $selected_url; ?></td>
								    	<?php /*<td class="lbb-text-center"><span class="lbb-date-format"><?php echo get_the_date( 'M-d-Y' ); ?></span></td>*/?>
								    	<td class="lbb-text-center">
								    		<div class="lbb-shortcode-copy">
				                                <input disable id="copyable_chatflow_shortcode_<?php echo get_the_ID(); ?>" class="copyable-shortcode-text" value='[ListBuildingBot id="<?php echo get_the_ID(); ?>"]'> <span data-id="copyable_chatflow_shortcode_<?php echo get_the_ID(); ?>" class="copy-btn lbbCopyToClipboardInput"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
				                            </div>
							    		</td>

							    		
								    	<td class="lbb-text-center lbb-status-outer">
								    		<div class="lbb-switch">
											  <input type="checkbox" data-id="<?php echo get_the_ID(); ?>" id="check-menu-<?php echo $i; ?>" <?php echo $chatflow_status == 'Y' ? 'checked' : ''; ?> class="checkstyle lbb-status"/>
											  <label for="check-menu-<?php echo $i; ?>"><span><span></span></span></label>
											  <div class="status-text"></div>
											</div>
								    	</td> 
								    	<td class="lbb-text-center">
								    		<div class="lbb-action-btn-wrapper">
								    			<a title="Edit" class="lbb-icon-transparent-btn lbb-edit-btn" href="<?php echo home_url(); ?>/wp-admin/admin.php?page=listbuildingbot&action=edit&id=<?php echo get_the_ID(); ?>" data-id="<?php echo get_the_ID(); ?>">
									    			<span class="dashicons dashicons-edit"></span> Edit
									    		</a>

									    		<div class="lbb-sub-menu-hover">
									    			<a title="Action" class="lbb-icon-transparent-btn lbb-edit-btn lbb-moreaction-icon" href="javascript:void(0);">
										    			<i class='bx bx-dots-vertical-rounded'></i>
										    		</a>
										    		<div class="lbb-submenu">
											    		<a title="Clone" class="lbb-clone-btn" href="javascript:void(0)" data-id="<?php echo get_the_ID(); ?>"> 
											    			<span class="dashicons dashicons-admin-page"></span> Clone
											    		</a>
											    		<a title="Delete" class=" lbb-delete-btn delete-chatflow" data-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)">
											    			<span class="dashicons dashicons-trash"></span> Delete
											    		</a>
											    		<a title="Export" class=" lbb-export-btn export-chatflow" data-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)">
											    			<span class="dashicons dashicons-database-export"></span> Export
											    		</a>
											    		<a title="Preview" class=" lbb-preview-btn preview-chatflow" data-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)">
											    			<span class="dashicons dashicons-visibility"></span> Preview
											    		</a>
										    		</div>
									    		</div>
								    		</div>
								    	</td>
									</tr>
								    <?php
								    $i++;
								endwhile;
								wp_reset_postdata();
								?>
							</tbody>
						</table>
					</div>
				<?php } else{ ?>
					<div class="lbb-datatable">
						<div class="lbb-no-answers-found lbb-empty-box">
		                    <div class="lbb-box-container">
		                        <span class="dashicons dashicons-warning lbb-box-icon"></span>
		                        <p class="lbb-box-text">You don't have any List Building Bots yet!</p>
		                        <a class="create-chatflow lbb-btn lbb-btn-primary" id="lbb-create-new-chatflow" href="javascript:void(0);">Click here to create a bot</a>
		                    </div>
		                </div>
	                </div>
	            <?php } ?>
				<div class="lbb_template_selection_popup-wrapper">
					<div class="lbb_template_selection_option_section lbb_select_main_option" style="display: none;">
						<span class="lbb-template-selection-close lbb-popup-close-btn" onclick="lbb_main_section_types_hide()">Close <i class="bx bx-x"></i></span>
						<div class="lbb-template-selection-options">
							<div class="lbb-template-selection-item lbb-template-main-selection-item" data-value="create"> 
								<i class="bx bx-category"></i> 
								<span>Create from Scratch</span>
							</div>
							<div class="lbb-template-selection-item lbb-template-main-selection-item" data-value="use_builtin"> 
								<i class="bx bx-cube-alt"></i> 
								<span>Pre-programmed Bot</span>
							</div>
							<div class="lbb-template-selection-item lbb-template-main-selection-item" data-value="custom_bot"> 
								<i class='bx bx-atom' ></i>
								<span>Custom Bot for your site (train the bot)</span>
							</div>
							<div class="lbb-template-selection-item lbb-template-main-selection-item" data-value="use_import_export"> 
								<i class="bx bx-import"></i> 
								<span>Import Bot Funnel</span>
							</div>
						</div>
					</div>

					<div class="lbb_template_selection_option_section lbb_select_scratch_options" style="display: none;">
						<span class="lbb-template-selection-close lbb-popup-close-btn" onclick="lbb_scratch_section_types_hide()">Close <i class="bx bx-x"></i></span>
						<div class="lbb-template-selection-options lbb-justify-content-center">
							<div class="lbb-template-selection-item lbb-template-scratch-selection-item" data-value="create"> 
								<i class="bx bx-category"></i> 
								<span>I'll build the funnel</span>
							</div>
							<div class="lbb-template-selection-item lbb-template-scratch-selection-item" data-value="use_ai"> 
								<i class="bx bx-cube-alt"></i> 
								<span>Use AI to Build it</span>
								<small>Even if you use AI to build the bot funnel, you can update it </small>
							</div>
							
						</div>
					</div>
					<?php /*
					<div class="lbb_template_selection_option_section lbb_select_customBot_options" style="display: none;">
						<span class="lbb-template-selection-close lbb-popup-close-btn" onclick="lbb_customBot_section_types_hide()">Close <i class="bx bx-x"></i></span>
						<div class="lbb-template-selection-options">
							<div class="lbb-template-selection-item lbb-template-customBot-selection-item" data-value="use_ai"> 
								<i class="bx bx-category"></i> 
								<span>AI</span>
							</div>
							<div class="lbb-template-selection-item lbb-template-customBot-selection-item" data-value="custom_bot"> 
								<i class="bx bx-cube-alt"></i> 
								<span>Custom Bot (train the bot) </span>
							</div>
							<span>(upload your documents or content. Bot will use your documents to respond to queries)</span>
						</div>
					</div> */ ?>

					<div class="lbb_select_prebuilt " style="display: none;">
						<span class="lbb-prebuilt-selection-close lbb-popup-close-btn" onclick="lbb_prebuilt_hide()">Close <i class="bx bx-x"></i></span>
						<div class="lbb_select_quiz_grid">
							<div class="lbb_sq_grid_items "> 
								<div class="lbb-section-templates">
									<div class="lbb-section-heading-wrapper">
										<div class="lbb-section-heading-flex">
											<div class="lbb-logo-templates-part">
												<i class='bx bx-slideshow'></i></div>
											<div class="lbb-section-content-part">
												<h3>Pre-Built Bot Templates</h3>
												<p>You can use any of the prebuilt funnels to get started. You'll have the option to edit and make updates to it as needed.</p>
											</div>
										</div>
									</div>

									<div class="lbb-section-contentbox-wrapper">
										<div class="lbb-section-contentbox-flex">
											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/lead-qualification-min.jpg">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Lead Qualification</h3>
														<p>Qualify leads into prospects that convert with this free bot template.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="lead-qualification">Use Template</a>
													</div>
													
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/lead-capture-min.jpg">
														</a>
													</div>
													
														<div class="lbb-content-wrapper">
															<h3>Lead Capture</h3>
															<p>Offer your lead magnet and collect contact details.</p>
															<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="lead-capture">Use Template</a>
														</div>
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/resource-recommendation-min.jpg">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Resource Recommendation</h3>
														<p>Recommend the right resources based on answers.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="resource-recommendation">Use Template</a>
													</div>
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/contact-form-min.jpg">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Contact Us</h3>
														<p>Give your users an easy way to reach out to you.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="contact-us">Use Template</a>
													</div>
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/customer-feedback-survey-min.jpg">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Customer Feedback Survey</h3>
														<p>Collect feedback on any page from your prospects or customers.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="customer-feedback-survey">Use Template</a>
													</div>
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/agency-funnel-min.jpg">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Agency Funnel</h3>
														<p>Are you an agency or a coach? Use this template to get more leads.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="agency-funnel">Use Template</a>
													</div>
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/import-demos/images/sales-page-questions.png">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Sales Page Bot</h3>
														<p>Use this bot on your sales page! Answer questions and convert prospects into paying clients.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="sales-page-questions">Use Template</a>
													</div>
													
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/import-demos/images/sales-page-bot.png">
														</a>
													</div>
													
													<div class="lbb-content-wrapper">
														<h3>Live Chat + Bot - for Sales Page</h3>
														<p>Use this bot + live chat on your sales page.</p>
														<a href="javascript:void(0)" class="lbb-use-template lbb-btn lbb-btn-primary" data-template="sales-page-bot-questions">Use Template</a>
													</div>
													
												</div>
											</div>

										</div>
									</div>
									

								</div>

								<?php /*
								<div class="lbb-section-templates">
									<div class="lbb-section-heading-wrapper">
										<div class="lbb-section-heading-flex">
											<div class="lbb-logo-templates-part">
												<i class="bx bx-pie-chart-alt-2"></i></div>
											<div class="lbb-section-content-part">
												<h3>Lead Generation</h3>
												<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley</p>
											</div>
										</div>
									</div>

									<div class="lbb-section-contentbox-wrapper">
										<div class="lbb-section-contentbox-flex">
											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/contact-form-min.jpg">
														</a>
													</div>
													<a href="javascript:void(0)" class="lbb-link-title-description">
														<div class="lbb-content-wrapper">
															<h3>Lead Qualification</h3>
															<p>Qualify leads into prospects that convert with this free bot template.</p>
														</div>
													</a>
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/contact-form-min.jpg">
														</a>
													</div>
													<a href="javascript:void(0)" class="lbb-link-title-description">
														<div class="lbb-content-wrapper">
															<h3>Lead Qualification</h3>
															<p>Qualify leads into prospects that convert with this free bot template.</p>
														</div>
													</a>
												</div>
											</div>

											<div class="lbb-templates-item">
												<div class="lbb-individual-template">
													<div class="lbb-img-card">
														<a href="javascript:void(0)">
															<img src="<?php echo LBB_URL; ?>admin/images/contact-form-min.jpg">
														</a>
													</div>
													<a href="javascript:void(0)" class="lbb-link-title-description">
														<div class="lbb-content-wrapper">
															<h3>Lead Qualification</h3>
															<p>Qualify leads into prospects that convert with this free bot template.</p>
														</div>
													</a>
												</div>
											</div>
										</div>
									</div>

								</div> */
								?>
							</div>	
						</div>
					</div>

					<div class="lbb_template_selection_option_section lbb_create_quiz_using_import" style="display: none;">
						<span class="lbb-template-selection-close lbb-popup-close-btn" onclick="lbb_import_export_hide()">Close <i class="bx bx-x"></i></span>
						  <div class="lbb-file-upload-box-with-input">
						        <h3 class="quiz--sub-title ml-3">Import Bot Funnel</h3>
						        <form class="form-horizontal" action="#" method="post" name="frmjsonImport" id="frmjsonImport" enctype="multipart/form-data">
						            <div class="lbb-file-upload-row">
						                <div id="selected-file"><span>Click the 'Choose File' button to upload a file</span></div><label id="file-label" for="file">Choose File</label>
						                <input type="file" name="json_file" id="file">
						                
						            </div>
						            <div class="lbb-quiz-import-actions" style="justify-content: space-around;">
						                <a href="javascript:void(0);" class="lbb-btn lbb-btn-primary" onclick="lbb_import_chatflow()">Click HERE To Import </a>
						            </div>
						        </form>
						    </div>
					</div>

					<div class="lbb_template_selection_option_section lbb_create_quiz_using_ai" style="display: none;">
						<span class="lbb-template-selection-close lbb-popup-close-btn" onclick="lbb_ai_hide()">Close <i class="bx bx-x"></i></span>
						  <div class="lbb-ai-box-wrapper">
						        <h3 class="quiz--sub-title ml-3">AI-Generated</h3>
						        <div class="lbb-ai-create-sales-page lbb-ai-sales-pageb">
						        	<section class="lbb-ai-screen-list-product lbb-ai-sales-screen-product lbb-ai-type lbb-ai-active-screen">
						                <div class="lbb-ai-sales-selection-screen lbb-ai-screen" style="" id="lbb-ai-sales-selection-screen" data-screen="title">
						                    <div class="lbb-ai_form lbb-ai-quiz-common-info">
						                        <h2 class="lbb-ai-section-heading aid-mt-30 aid-mb-10">Pick an Option</h2>
						                        <div class="lbb-ai_form_input">
						                            <p class="control-label lbb-ai-section-content aid-mb-30">OpenAI charges a fee for the API calls. While it's about $0.002/token (please check current pricing on their <br><a href="https://openai.com/pricing" target="_blank">site</a>), it's not free. If you don't want to spend on the API calls, you can use the manual option. Details below.</p>
						                            <div class="lbb-ai_form_template_wrapper">
						                                <div class="lbb-ai-item-main-box">
						                                    <div class="lbb-ai-item-inner">
						                                        <div class="lbb-ai-item-text">
						                                            <h2>No API call (FREE)</h2>
						                                            <p>As OpenAI APIs are not free, give us the title, we'll give you the prompts for it.
						                                                Enter it in ChatGPT directly. And then enter the response from ChatGPT here.
						                                            </p>
						                                            <button class="product-lbb-ai_manual_flow lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green">Use This</button>
						                                        </div>
						                                    </div>
						                                    <div class="lbb-ai-item-inner">
						                                        <div class="lbb-ai-item-text">
						                                            <h2>API call</h2>
						                                            <div class="lbb-ai-item-text">
						                                                If you have signed up for the API,<br>
						                                                you can enter the key <a href="<?php echo admin_url( 'admin.php?page=listbuildingbot-settings' ); ?>" target="_blank"> here.</a><br>
						                                                You can get the key from <a href="https://platform.openai.com/account/api-keys" target="_blank"> here.</a>
						                                                <div class="tool-tip ai-tooltip-description">
						                                                    <div class="tool-tip-outer">
						                                                        <a href="javascript:void(0)" class="ai-plan-popup-btn">Details here:</a>
						                                                        
						                                                    </div>
						                                                </div>
						                                            </div>

																	<?php
																	 $ai_assistant = get_option('lbb_ai_assistant');
																	 $OPENAI_API_KEY = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
																	 if($OPENAI_API_KEY == ''){ ?>

<button class="lbb-no-ai-api  lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green">Use This</button>

																	 <?php }else{ ?>
																		<button class="lbb-ai_auto_flow  lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green">Use This</button>
																	 <?php }
																	?>

						                                            
						                                        </div>
						                                    </div>
						                                </div>
						                            </div>
						                        </div>
						                    </div>
						                </div>
						            </section>
									<script>
										var lbb_prompt = '<?php echo lbb_get_prompt('lbb_bot'); ?>';
										var lbb_bot_json = `<?php echo lbb_get_prompt('lbb_bot_json'); ?>`;

										var lbb_prompt_outcome = '<?php echo lbb_get_prompt('lbb_bot_outcome'); ?>';
										var lbb_bot_json_outcome = `<?php echo lbb_get_prompt('lbb_bot_json_outcome'); ?>`;

										var lbb_outcome_prompt = `<?php echo lbb_get_prompt('lbb_outcome_prompt'); ?>`;
									</script>
									<style>
										
									</style>
						            <section class="lbb-ai-screen-list-product lbb-ai-manual-basic-screen-product lbb-ai-type">
						                <div class="lbb-ai-quiz-start-screen lbb-ai-screen lbb-ai-quiz-common-info">
						                    <div class="lbb-ai-quiz-heading lbb-ai-page-heading ">
						                        <img src="<?php echo LBB_URL; ?>admin/images/artificial-intelligence.png"> 
						                        <h2 class="lbb-ai-section-heading aid-mt-30 aid-mb-10">Please answer these questions about your Bot</h2>
						                    </div>
						                    <div class="lbb-ai-field-main-wrapper aid-mt-30">
						                        <div class="lbb-ai-field-group-for-show-ui-product">
						                            <div class="lbb-ai-field-row aid-max-700-off">
						                                <label class="control-label">What is the primary goal of your bot?</label>
						                               
														<textarea name="lbb_ai_goal" id="lbb_ai_goal" placeholder="" class="lbb-ai-field-input" rows="7">I want to create a conversational bot funnel for 'Where Are You Stuck?' – a resource recommendation bot aimed at helping prospects in marketing, sales, leads, conversions, customer journey, and engagement. The bot should initiate the conversation by asking general questions about the prospect's challenges in these areas. Based on their responses, the bot should dynamically generate follow-up questions and provide tailored resource recommendations to guide prospects toward solutions for their specific problems. Ensure that the bot maintains a user-friendly and engaging tone throughout the interaction.</textarea>
														<div class="lbb-ai-error lbb-ai-goal-error" style="display:none"><p>Please enter value here<p></div>
														<div class="lbb-ai-title-wrapper">
						                                    <button class="lbb-ai-btn-underline lbb-ai-btn lbb-btn lbb-btn-primary  lbb-ai-goal-popup">Not sure? See examples</button>
						                                </div>
						                            </div>

						                            <div class="lbb-ai-field-row aid-max-700-off">
						                                <label class="control-label">Who is your target audience?</label>
						                                <textarea name="lbb_ai_target_audience" id="lbb_ai_target_audience" placeholder="" class="lbb-ai-field-input"></textarea>
														<div class="lbb-ai-error lbb-ai-target-audience-error" style="display:none"><p>Please enter value here<p></div>
						                                <div class="lbb-ai-title-wrapper">
						                                    <button class="lbb-ai-btn-underline lbb-ai-btn lbb-btn lbb-btn-primary  lbb-ai-target-audience-popup">Not sure? See examples</button>
						                                </div>
						                            </div>
													
													<div class="lbb-ai-field-row aid-max-700-off" style="display:none;">
						                                <label class="control-label">Describe your product or service<br>This is the product or service you are creating the bot for. Or just describe your business / niche here.</label>
						                                <input type="text" name="lbb_ai_product_description" id="lbb_ai_product_description" placeholder="For e.g., How to build a profitable sales funnel" class="lbb-ai-field-input" />
						                                <div class="lbb-ai-error lbb-ai-topic-error" style="display:none"><p>Please enter value here<p></div>
						                            </div>

													<div class="lbb-ai-field-row lbb-ai-field-inline aid-max-700-off" style="display:none">
						                                <label class="control-label">Total number of questions</label>
						                                <input type="number" name="lbb_ai_total_question" id="lbb_ai_total_question" placeholder="" max="15" min="1" value="5" class="lbb-ai-field-input" />
						                                <div class="lbb-ai-error lbb-ai-total-ques-error" style="display:none"><p>Please enter value here<p></div>
						                            </div>

													<div class="lbb-ai-field-row aid-max-700-off aid-mt-20">
														<label class="control-label">Do you want to collect contact details? (name/email)</label>
														<div class="paid_free_product show-in-same-line">

															<ul class="lbb-radio-btn-wrapper">
					                                            <li>
					                                                <input name="lbb_ai_add_contact" id="lbb_ai_add_contact_yes" class="lbb_ai_add_contact" type="radio" value="Y" checked="">
					                                                <label for="lbb_ai_add_contact_yes">Yes</label>
					                                                <div class="lbb-check"></div>
					                                            </li>
					                                            <li>
					                                                <input name="lbb_ai_add_contact" id="lbb_ai_add_contact_no" class="lbb_ai_add_contact" type="radio" value="N">
					                                                <label for="lbb_ai_add_contact_no">No</label>
					                                                <div class="lbb-check">
					                                                   <div class="inside"></div>
					                                                </div>
					                                            </li>
					                                        </ul>

														</div>
													</div>

						                            <div class="lbb-ai-field-row aid-max-700-off aid-mt-20">
														<label class="control-label">Do you want to place users in different buckets or show them different outcomes, based on responses? It'll be similar to a personality quiz with different outcomes.</label>
														<div class="paid_free_product show-in-same-line">

															<ul class="lbb-radio-btn-wrapper">
					                                            <li>
					                                                <input name="lbb_ai_add_outcome" id="lbb_ai_add_outcome_product_yes" class="lbb_ai_add_outcome_product" type="radio" value="Y">
					                                                <label for="lbb_ai_add_outcome_product_yes">Yes</label>
					                                                <div class="lbb-check"></div>
					                                            </li>
					                                            <li>
					                                                <input name="lbb_ai_add_outcome" id="lbb_ai_add_outcome_product_no" class="lbb_ai_add_outcome_product" type="radio" value="N" checked="">
					                                                <label for="lbb_ai_add_outcome_product_no">No</label>
					                                                <div class="lbb-check">
					                                                   <div class="inside"></div>
					                                                </div>
					                                            </li>
					                                        </ul>

														</div>
													</div>

													<div class="lbb-ai-field-row aid-max-700-off aid-mt-20 lbb_outcome_type" style="display:none;">
														<label class="control-label">Do you have the outcome titles or want to use AI to generate it?</label>
														<div class="paid_free_product show-in-same-line">

															<ul class="lbb-radio-btn-wrapper">
					                                            <li>
					                                                <input name="lbb_ai_add_outcome_type" id="lbb_ai_add_outcome_type_product_yes" class="lbb_ai_add_outcome_type_product" type="radio" value="use_ai">
					                                                <label for="lbb_ai_add_outcome_type_product_yes">Use AI</label>
					                                                <div class="lbb-check"></div>
					                                            </li>
					                                            <li>
					                                                <input name="lbb_ai_add_outcome_type" id="lbb_ai_add_outcome_type_product_no" class="lbb_ai_add_outcome_type_product" type="radio" value="use_comma" checked="">
					                                                <label for="lbb_ai_add_outcome_type_product_no">Yes, I have it</label>
					                                                <div class="lbb-check">
					                                                   <div class="inside"></div>
					                                                </div>
					                                            </li>
					                                        </ul>

														</div>
													</div>

													

													<div class="lbb-ai-field-row aid-max-700-off ai_outcome_wrapper_product aid-mt-20" style="display: none;">
														<label class="control-label">Enter outcome titles below (comma-separated list)</label>
														<textarea name="lbb_ai_outcome_titles" id="lbb_ai_outcome_titles" placeholder="" class="lbb-ai-field-input"></textarea>
														<div class="lbb-ai-error lbb-ai-outcome-titles-error" style="display:none"><p>Please enter value here<p></div>
													</div>

													<div class="lbb-ai-field-row aid-max-700-off ai_outcome_type_use_ai aid-mt-20" style="display: none;">
													<a href="javascript:void(0);" class="lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green use_ai_to_genetate_outcome">Click here to generate outcome ideas using AI</a>
													</div>

													<div class="lbb-ai-field-row aid-max-700-off ai_outcome_type_use_ai aid-mt-20" style="display: none;">
														<div class="ai_outcome_titles_div"></div>
													</div>

						                            

						                            <div class="lbb-ai-field-row aid-max-700-off aid-mt-20 js-select2-wrapper">
						                                <label class="control-label">Language</label>
						                                <select id="lbb_ai_lang_product" name="lbb_ai_lang_product" class="form-control js-select2-dropdown lbb_ai_course_lang">
						                                    <option value="Afar">Afar</option>
						                                    <option value="Abkhazian">Abkhazian</option>
						                                    <option value="Avestan">Avestan</option>
						                                    <option value="Afrikaans">Afrikaans</option>
						                                    <option value="Akan">Akan</option>
						                                    <option value="Amharic">Amharic</option>
						                                    <option value="Aragonese">Aragonese</option>
						                                    <option value="Arabic">Arabic</option>
						                                    <option value="Assamese">Assamese</option>
						                                    <option value="Avaric">Avaric</option>
						                                    <option value="Aymara">Aymara</option>
						                                    <option value="Azerbaijani">Azerbaijani</option>
						                                    <option value="Bashkir">Bashkir</option>
						                                    <option value="Belarusian">Belarusian</option>
						                                    <option value="Bulgarian">Bulgarian</option>
						                                    <option value="Bihari languages">Bihari languages</option>
						                                    <option value="Bislama">Bislama</option>
						                                    <option value="Bambara">Bambara</option>
						                                    <option value="Bengali">Bengali</option>
						                                    <option value="Tibetan">Tibetan</option>
						                                    <option value="Breton">Breton</option>
						                                    <option value="Bosnian">Bosnian</option>
						                                    <option value="Catalan">Catalan</option>
						                                    <option value="Chechen">Chechen</option>
						                                    <option value="Chamorro">Chamorro</option>
						                                    <option value="Corsican">Corsican</option>
						                                    <option value="Cree">Cree</option>
						                                    <option value="Czech">Czech</option>
						                                    <option value="Church Slavic">Church Slavic</option>
						                                    <option value="Chuvash">Chuvash</option>
						                                    <option value="Welsh">Welsh</option>
						                                    <option value="Danish">Danish</option>
						                                    <option value="German">German</option>
						                                    <option value="Divehi">Divehi</option>
						                                    <option value="Dzongkha">Dzongkha</option>
						                                    <option value="Ewe">Ewe</option>
						                                    <option value="Greek">Greek</option>
						                                    <option value="English" selected="">English</option>
						                                    <option value="Esperanto">Esperanto</option>
						                                    <option value="Spanish">Spanish</option>
						                                    <option value="Estonian">Estonian</option>
						                                    <option value="Basque">Basque</option>
						                                    <option value="Persian">Persian</option>
						                                    <option value="Fulah">Fulah</option>
						                                    <option value="Finnish">Finnish</option>
						                                    <option value="Fijian">Fijian</option>
						                                    <option value="Faroese">Faroese</option>
						                                    <option value="French">French</option>
						                                    <option value="Western Frisian">Western Frisian</option>
						                                    <option value="Irish">Irish</option>
						                                    <option value="Scottish Gaelic">Scottish Gaelic</option>
						                                    <option value="Galician">Galician</option>
						                                    <option value="Guarani">Guarani</option>
						                                    <option value="Gujarati">Gujarati</option>
						                                    <option value="Manx">Manx</option>
						                                    <option value="Hausa">Hausa</option>
						                                    <option value="Hebrew">Hebrew</option>
						                                    <option value="Hindi">Hindi</option>
						                                    <option value="Hiri Motu">Hiri Motu</option>
						                                    <option value="Croatian">Croatian</option>
						                                    <option value="Haitian">Haitian</option>
						                                    <option value="Hungarian">Hungarian</option>
						                                    <option value="Armenian">Armenian</option>
						                                    <option value="Herero">Herero</option>
						                                    <option value="Interlingua">Interlingua</option>
						                                    <option value="Indonesian">Indonesian</option>
						                                    <option value="Interlingue">Interlingue</option>
						                                    <option value="Igbo">Igbo</option>
						                                    <option value="Sichuan Yi">Sichuan Yi</option>
						                                    <option value="Inupiaq">Inupiaq</option>
						                                    <option value="Ido">Ido</option>
						                                    <option value="Icelandic">Icelandic</option>
						                                    <option value="Italian">Italian</option>
						                                    <option value="Inuktitut">Inuktitut</option>
						                                    <option value="Japanese">Japanese</option>
						                                    <option value="Javanese">Javanese</option>
						                                    <option value="Georgian">Georgian</option>
						                                    <option value="Kongo">Kongo</option>
						                                    <option value="Kikuyu">Kikuyu</option>
						                                    <option value="Kuanyama">Kuanyama</option>
						                                    <option value="Kazakh">Kazakh</option>
						                                    <option value="Kalaallisut">Kalaallisut</option>
						                                    <option value="Central Khmer">Central Khmer</option>
						                                    <option value="Kannada">Kannada</option>
						                                    <option value="Korean">Korean</option>
						                                    <option value="Kanuri">Kanuri</option>
						                                    <option value="Kashmiri">Kashmiri</option>
						                                    <option value="Kurdish">Kurdish</option>
						                                    <option value="Komi">Komi</option>
						                                    <option value="Cornish">Cornish</option>
						                                    <option value="Kirghiz">Kirghiz</option>
						                                    <option value="Latin">Latin</option>
						                                    <option value="Luxembourgish">Luxembourgish</option>
						                                    <option value="Ganda">Ganda</option>
						                                    <option value="Limburgish">Limburgish</option>
						                                    <option value="Lingala">Lingala</option>
						                                    <option value="Lao">Lao</option>
						                                    <option value="Lithuanian">Lithuanian</option>
						                                    <option value="Luba-Katanga">Luba-Katanga</option>
						                                    <option value="Latvian">Latvian</option>
						                                    <option value="Malagasy">Malagasy</option>
						                                    <option value="Marshallese">Marshallese</option>
						                                    <option value="Maori">Maori</option>
						                                    <option value="Macedonian">Macedonian</option>
						                                    <option value="Malayalam">Malayalam</option>
						                                    <option value="Mongolian">Mongolian</option>
						                                    <option value="Marathi">Marathi</option>
						                                    <option value="Malay">Malay</option>
						                                    <option value="Maltese">Maltese</option>
						                                    <option value="Burmese">Burmese</option>
						                                    <option value="Nauru">Nauru</option>
						                                    <option value="Norwegian Bokmål">Norwegian Bokmål</option>
						                                    <option value="North Ndebele">North Ndebele</option>
						                                    <option value="Nepali">Nepali</option>
						                                    <option value="Ndonga">Ndonga</option>
						                                    <option value="Dutch">Dutch</option>
						                                    <option value="Norwegian Nynorsk">Norwegian Nynorsk</option>
						                                    <option value="Norwegian">Norwegian</option>
						                                    <option value="South Ndebele">South Ndebele</option>
						                                    <option value="Navajo">Navajo</option>
						                                    <option value="Chichewa">Chichewa</option>
						                                    <option value="Occitan">Occitan</option>
						                                    <option value="Ojibwa">Ojibwa</option>
						                                    <option value="Oromo">Oromo</option>
						                                    <option value="Oriya">Oriya</option>
						                                    <option value="Ossetian">Ossetian</option>
						                                    <option value="Panjabi">Panjabi</option>
						                                    <option value="Pali">Pali</option>
						                                    <option value="Polish">Polish</option>
						                                    <option value="Pushto">Pushto</option>
						                                    <option value="Portuguese">Portuguese</option>
						                                    <option value="Quechua">Quechua</option>
						                                    <option value="Romansh">Romansh</option>
						                                    <option value="Rundi">Rundi</option>
						                                    <option value="Romanian">Romanian</option>
						                                    <option value="Russian">Russian</option>
						                                    <option value="Kinyarwanda">Kinyarwanda</option>
						                                    <option value="Sanskrit">Sanskrit</option>
						                                    <option value="Sardinian">Sardinian</option>
						                                    <option value="Sindhi">Sindhi</option>
						                                    <option value="Northern Sami">Northern Sami</option>
						                                    <option value="Sango">Sango</option>
						                                    <option value="Sinhala">Sinhala</option>
						                                    <option value="Slovak">Slovak</option>
						                                    <option value="Slovenian">Slovenian</option>
						                                    <option value="Samoan">Samoan</option>
						                                    <option value="Shona">Shona</option>
						                                    <option value="Somali">Somali</option>
						                                    <option value="Albanian">Albanian</option>
						                                    <option value="Serbian">Serbian</option>
						                                    <option value="Swati">Swati</option>
						                                    <option value="Sotho, Southern">Sotho, Southern</option>
						                                    <option value="Sundanese">Sundanese</option>
						                                    <option value="Swedish">Swedish</option>
						                                    <option value="Swahili">Swahili</option>
						                                    <option value="Tamil">Tamil</option>
						                                    <option value="Telugu">Telugu</option>
						                                    <option value="Tajik">Tajik</option>
						                                    <option value="Thai">Thai</option>
						                                    <option value="Tigrinya">Tigrinya</option>
						                                    <option value="Turkmen">Turkmen</option>
						                                    <option value="Tagalog">Tagalog</option>
						                                    <option value="Tswana">Tswana</option>
						                                    <option value="Tonga (Tonga Islands)">Tonga (Tonga Islands)</option>
						                                    <option value="Turkish">Turkish</option>
						                                    <option value="Tsonga">Tsonga</option>
						                                    <option value="Tatar">Tatar</option>
						                                    <option value="Twi">Twi</option>
						                                    <option value="Tahitian">Tahitian</option>
						                                    <option value="Uighur">Uighur</option>
						                                    <option value="Ukrainian">Ukrainian</option>
						                                    <option value="Urdu">Urdu</option>
						                                    <option value="Uzbek">Uzbek</option>
						                                    <option value="Venda">Venda</option>
						                                    <option value="Vietnamese">Vietnamese</option>
						                                    <option value="Volapük">Volapük</option>
						                                    <option value="Walloon">Walloon</option>
						                                    <option value="Wolof">Wolof</option>
						                                    <option value="Xhosa">Xhosa</option>
						                                    <option value="Yiddish">Yiddish</option>
						                                    <option value="Yoruba">Yoruba</option>
						                                    <option value="Zhuang">Zhuang</option>
						                                    <option value="Chinese">Chinese</option>
						                                    <option value="Zulu">Zulu</option>
						                                </select>
						                            </div>
						                        </div>
						                        
						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb-justify-content-space-between">
						                            <button class="lbb-ai-back-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-ai-btn-gray " data-prev="lbb-ai-sales-screen-product">Back</button>
						                            
						                            <button class="lbb-ai-next-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green" data-next="lbb-ai-manual-outline-prompt-sales-screen-product" data-screen="generate-sales-product">Next</button>
						                        </div>
						                    </div>
						                </div>
						                <input name="ai_course_id" type="hidden" class="" id="ai_product_id" size="3" maxlength="4" value="">
						            </section>

						            <section class="lbb-ai-screen-list-product lbb-ai-manual-outline-prompt-sales-screen-product lbb-ai-type">
						                <div class="lbb-ai-quiz-start-screen lbb-ai-screen lbb-ai-quiz-common-info">
						                   <div class="lbb-ai-quiz-heading lbb-ai-page-heading ">
						                        <img src="<?php echo LBB_URL; ?>admin/images/artificial-intelligence.png"> 
						                       
						                        <div class="ai-with-description lbb-ai-title-description-wrapper">
						                            <h2 class="lbb-ai-section-heading aid-mt-30 aid-mb-10">Use AI/ChatGPT to build the Bot Funnel</h2>
						                            <p class="lbb-ai-section-descriptin aid-mt-0 aid-mb-0 ai-showonly-manual">Copy/paste this in ChatGPT. It'll give you back questions and answers for your bot funnel.<br>Do NOT remove the sections highlighted in red from the prompt.</p>
						                            <p class="lbb-ai-section-descriptin aid-mt-0 aid-mb-0 ai-showonly-api">This is the prompt LBB uses to generate the headline for your sales page. If you want to update it below, you can do that. Click on the next button to continue. Do NOT remove the sections highlighted in red from the prompt.</p>
						                        </div>
						                    </div>
						                    <div class="lbb-ai-field-main-wrapper aid-mt-30">
						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb_ai_prompt_response_wrapper">
						                            <div class="lbb_ai_prompt_format_wrapper lbb_ai_outcome_prompt_code_show ai-showonly-manual">
						                                <div class="code-container">
						                                    <div class="aiq-copy-btn-wrapper lbb-flex-field-left-right">
						                                        <h3>ChatGPT Prompt</h3>
						                                        <span class="copy-icon" data-id="lbb_ai_outline_prompt" onclick="lbb_copy_to_clipboardNew(this)">
						                                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
						                                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
						                                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
						                                            </svg>
						                                            <i>Copy Code</i>
						                                        </span>
						                                    </div>
						                                    <code id="lbb_ai_outline_prompt" class="lbb_ai_prompt_format">
						                                    	<span>Don't add comma for the last array entry. Can you generate title for me in  below format and add backslash before quotes if string have any quotation:
						json[{"content":""}, {"content":""}]</span></code>
						                                </div>
						                            </div>
						                            

						                            <div class="slb-content-sub-prompt ai-showonly-api">
						                                <button class="lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-show-outline-prompt-btn lbb-ai-btn-underline ai-showonly-api lbb_ai_outline_prompt_sales-product" data-type="title" style="display:none">Show Prompt</button>
						                                <div class="lbb-ai-field-group-for-show-text-product">
						                                    <div class="lbb-ai-field-row aid-max-700-off aid-mt-0">
						                                        <label class="control-label">Prompt</label>
						                                        <textarea name="lbb_ai_sales_prompt_product" class="lbb_ai_sales_prompt_product lbb-ai-field-input ai-showonly-api"></textarea>
						                                    </div>
						                                </div>
						                            </div>

						                            <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb_ai_prompt_response_wrapper ai-showonly-manual">
						                                <div class="lbb_ai_form_input_wrapper">
						                                    <label class="lbb-ai-label">Enter the ChatGPT Response Below</label>
						                                    <textarea name="lbb_ai_outline_sales_title_product" id="lbb_ai_outline_sales_title_product" placeholder="Enter the ChatGPT response here" class="lbb_ai_prompt_textarea"></textarea>
						                                </div>
						                            </div>
						                        </div>

						                        

						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb-justify-content-space-between">
						                            <button class="lbb-ai-back-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-ai-btn-gray " data-prev="lbb-ai-manual-basic-screen-product">Back</button>
						                            <button class="lbb-ai-next-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green" data-screen="generate-sales-title-product" data-next="lbb-ai-preview-actions">Next</button>
						                        </div>
						                    </div>
						                </div>
						            </section>


						            <section class="lbb-ai-screen-list-product lbb-ai-preview-actions lbb-ai-type">
						                <div class="lbb-ai-quiz-start-screen lbb-ai-screen lbb-ai-quiz-common-info">
						                   <div class="lbb-ai-quiz-heading lbb-ai-page-heading ">
						                        <img src="<?php echo LBB_URL; ?>admin/images/artificial-intelligence.png"> 
						                        <div class="ai-with-description lbb-ai-title-description-wrapper">
						                            <h2 class="lbb-ai-section-heading aid-mt-30 aid-mb-10">Bot Question Preview</h2>
						                            <p class="lbb-ai-section-descriptin aid-mt-0 aid-mb-0">Please NOTE: This is just a preview. Can't edit the contents here. You can edit the funnel after LBB creates it in the backend. Click the button below to generate the bot funnel</p>
						                        </div>

						                    </div>
						                    <div class="lbb-ai-field-main-wrapper aid-mt-30">
						                        
						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb_ai_prompt_response_wrapper">
						                            <div class="chagpt-quiz-questions-response">
						                            	<ul class="lbb-ai-question-list">
						                            		<?php /*<li class="gpt-questiontype-single">
												                <input class="quiz_checkbox" name="chatgpt_question_list[]" type="checkbox" value="1">
												                <div class="chagpt-single_quiz_card">
												                	<div class="sqb-cg-question">How often do you engage in physical activity? <i>(Single)</i></div>
												                	<div class="sqb-cg-qa">
												                		<div class="sqb-cg-qa-lbl">Answers : </div>
												                		<div class="sqb-cg-qa-data">
												                			<div class="sqb-cg-qa-item">c. Regularly</div>
												                			<div class="sqb-cg-qa-item">b. Occasionally</div>
												                			<div class="sqb-cg-qa-item">a. Rarely or never</div>
												                		</div>
												                	</div>
												                </div>
											                </li> */ ?>
											            </ul>
										            </div>
						                        </div>

						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb-justify-content-space-between">
						                            <button class="lbb-ai-back-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-ai-btn-gray " data-prev="lbb-ai-manual-outline-prompt-sales-screen-product">Back</button>
						                            <button class="lbb-ai-next-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green"  data-next="lbb-ai-page-actions">Next</button>
						                        </div>
						                    </div>
						                </div>
						            </section>


						            <section class="lbb-ai-screen-list-product lbb-ai-page-actions lbb-ai-type">
						                <div class="lbb-ai-quiz-start-screen lbb-ai-screen lbb-ai-quiz-common-info">
						                   <div class="lbb-ai-quiz-heading lbb-ai-page-heading ">
						                        <img src="<?php echo LBB_URL; ?>admin/images/artificial-intelligence.png"> 
						                       
						                        <div class="ai-with-description lbb-ai-title-description-wrapper">
						                            <h2 class="lbb-ai-section-heading aid-mt-30 aid-mb-10">Almost there! One last question</h2>
						                            <p class="lbb-ai-section-descriptin aid-mt-0 aid-mb-0">Copy/paste this in ChatGPT. It'll give you back questions and answers for your bot funnel.<br>Do NOT remove the sections highlighted in red from the prompt.</p>						                        </div>
						                    </div>
						                    <div class="lbb-ai-field-main-wrapper aid-mt-30">
						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb_ai_prompt_response_wrapper">
						                            <label class="control-label lbb-mb-10">Select the type of bot:</label>
						                            <div class="lbb-form-group">
		                                                <div class="sqb-template-selection-options">
		                                                    <label class="sqb-template-selection-item">
		                                                        <input type="radio" name="ai_chatflow_mode" value="inline" checked="">
		                                                        <span class="lbb-radio-icon"></span>
		                                                        <img src="<?php echo LBB_URL; ?>admin/images/inpage-icon.png" alt="Option 1">
		                                                        <strong>In-Page</strong><small>publish it anywhere on your page</small>
		                                                    </label>

		                                                    <label class="sqb-template-selection-item">
		                                                        <input type="radio" name="ai_chatflow_mode" value="popup">
		                                                        <span class="lbb-radio-icon"></span>
		                                                        <img src="<?php echo LBB_URL; ?>admin/images/popup-icon.png" alt="Option 2">
		                                                        <strong>Popup</strong><small>publish it anywhere on your page</small>
		                                                    </label>
		                                                </div>
		                                            </div>

						                        </div>


						                        <div class="lbb-ai-field-row lbb-ai-box-white lbb-ai-page-action" style="display:none;">
								                     <label class="control-label lbb-mb-20">Target your visitors by choosing the web pages where you’d like your Bot Funnel to appear</label>
								                     <div class="lbb-row lbb-align-items-center">
								                        <div class="lbb-col-5">
								                           <div class="lbb-form-group js-select2-wrapper js-select2-multiple">
								                              <label for="where_to_show">Select pages:</label>
															  <select name="ai_page_name" id="ai_page_name" multiple class="js-select2-with-search">
																<?php echo lbbGetPagesPostsURLListHtml(); ?>
																</select>
								                              
								                           </div>
								                        </div>

								                        <div class="lbb-col-2">
								                           <span class="lbb-or lbb-mt-20"></span>
								                        </div>
								                        <div class="lbb-col-5">
								                           <div class="lbb-form-group">
								                              <label for="ai_page_url">Enter URL of the webpage:</label>
								                              <input type="text" id="ai_page_url" name="ai_page_url" class="lbb-input-field" placeholder="Enter URL">
								                           </div>
								                        </div>
								                    </div>
								                </div>
						                        

						                        <div class="lbb-ai-field-row aid-max-700-off aid-mt-30 lbb-justify-content-space-between">
						                        	<button class="lbb-ai-back-btn lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-ai-btn-gray " data-prev="lbb-ai-sales-pageb">Back</button>

						                            <button class="lbb-create-ai-bot lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green" data-screen="lbb-ai-page-actions" data-next1="lbb-ai-congratulation-screen-product" >Create</button>
						                        </div>
						                    </div>
						                </div>
						            </section>


						            <section class="lbb-ai-screen-list-product lbb-ai-congratulation-screen-product lbb-ai-field-row aid-max-700-off">
						                <div class="lbb-ai-quiz-start-screen lbb-ai-screen lbb-ai-quiz-common-info">
						                    
						                    

						                    <div class="lbb-ai-field-main-wrapper lbb-ai-congratulation-text-wrapper">
						                        <h2 class="lbb-ai-section-heading aid-mt-10 aid-mb-10 dynamic-change-module-lesson-type">Congrats! Your list building bot funnel is ready! 🎉🙂</h2>

						                        <p class="sd">Use shortcode if you are publishing it on this site. Otherwise use the embed code</p>

						                        <div class="lbb-ai-field-row lbb-ai-field-edit-input-wrapper lbb-ai-shortcode-main aid-mb-20">
							                        <label class="control-label" for="sales-url">This is the shortcode:</label>
							                        <div class="shortcode-copy">
						                                <input id="copyable_chatflow_shortcode" class="copyable-shortcode-text" value=''> <span data-id="copyable_chatflow_shortcode" class="copy-btn lbbCopyToClipboardInput"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
						                            </div>
							                    </div>
												<script>var lbb_ai_site_url = '<?php echo site_url() ?>';</script>
							                    <div class="lbb-ai-field-row lbb-ai-field-edit-input-wrapper lbb-ai-embed-main aid-mb-20">
							                        <label class="control-label" for="sales-url">This is the embed:</label>
							                        <div class="shortcode-copy">
						                                <input id="copyable_chatflow_embed_shortcode" class="copyable-shortcode-text" value=''>
						                                <span data-id="copyable_chatflow_embed_shortcode" class="copy-btn lbbCopyToClipboardInput"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
						                            </div>
							                    </div>

						                        <div class="lbb-ai-final-btn lbb-ai-btn-center">
						                            <a href="javascript:void(0)" target="_blank" class="lbb-btn lbb-btn-primary lbb-btn-green lbb-btr-black btn-sales-page lbb-edit-funnel-link">Edit the Bot Funnel</a>
						                            <a href="javascript:void(0)" target="_blank" class="lbb-btn  lbb-btn-primary lbb-btr-black btn-view-page">View the Bot Funnel</a>
						                        </div>
						                    </div>
						                    <div class="lbb-animation-prompt">
						                    	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;" xml:space="preserve">
											       

											        <rect x="113.7" y="135.7" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -99.5348 209.1582)" class="conf7" width="178" height="178"></rect>
											      <line class="conf7" x1="76.8" y1="224.7" x2="328.6" y2="224.7"></line>
											      <polyline class="conf7" points="202.7,350.6 202.7,167.5 202.7,98.9  "></polyline>
											<!-- here comes the confettis-->

											        <circle class="conf2" id="b1" cx="195.2" cy="232.6" r="5.1"></circle>
											        <circle class="conf0" id="b2" cx="230.8" cy="219.8" r="5.4"></circle>
											        <circle class="conf0" id="c2" cx="178.9" cy="160.4" r="4.2"></circle>
											        <circle class="conf6" id="d2" cx="132.8" cy="123.6" r="5.4"></circle>
											        <circle class="conf0" id="d3" cx="151.9" cy="105.1" r="5.4"></circle>

											        <path class="conf0" id="d1" d="M129.9,176.1l-5.7,1.3c-1.6,0.4-2.2,2.3-1.1,3.5l3.8,4.2c1.1,1.2,3.1,0.8,3.6-0.7l1.9-5.5
											          C132.9,177.3,131.5,175.7,129.9,176.1z"></path>
											        <path class="conf6" id="b5" d="M284.5,170.7l-5.4,1.2c-1.5,0.3-2.1,2.2-1,3.3l3.6,3.9c1,1.1,2.9,0.8,3.4-0.7l1.8-5.2
											          C287.4,171.9,286.1,170.4,284.5,170.7z"></path>
											        <circle class="conf6" id="c3" cx="206.7" cy="144.4" r="4.5"></circle>
											        <path class="conf2" id="c1" d="M176.4,192.3h-3.2c-1.6,0-2.9-1.3-2.9-2.9v-3.2c0-1.6,1.3-2.9,2.9-2.9h3.2c1.6,0,2.9,1.3,2.9,2.9v3.2
											          C179.3,191,178,192.3,176.4,192.3z"></path>
											        <path class="conf2" id="b4" d="M263.7,197.4h-3.2c-1.6,0-2.9-1.3-2.9-2.9v-3.2c0-1.6,1.3-2.9,2.9-2.9h3.2c1.6,0,2.9,1.3,2.9,2.9v3.2
											          C266.5,196.1,265.2,197.4,263.7,197.4z"></path>
											        <!-- yellow-strip-1-->
											    </svg>
						                    </div>
						                </div>
						            </section>

						        </div>
						    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="propwrap_iframe" class="lbb-popup-main">
    <div id="properties_iframe" class="lbb-popup-container"></div>
</div>

<div id="propwrap" class="lbb-popup-main lbb-selectedpages-listing-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>Selected Pages</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
        		<div class="lbb-popup-content-wrapper selected-pages-url">
        		</div>
        	</div>
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
                   
                </div>
            </footer>
            
        </div>
    </div>
</div>

<div id="propwrap" class="lbb-popup-main lbb-selectedpages-listing">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>Selected Pages</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
        		<div class="lbb-popup-content-wrapper all-selected-pages-url">
        		</div>
        	</div>
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
                   <button class="lbb-btn lbb-btn-secondary lbb-update-pages">Update</button>
                </div>
            </footer>
            
        </div>
    </div>
</div>


<div id="propwrap" class="lbb-popup-main lbb-ai-outcome-type-example-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>Outcome Title Ideas</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
        		<div class="lbb-popup-content-wrapper">
					<div class="aiq-popup-field-outer">
						





						<div class="lbb-ai-field-main-wrapper aid-mt-0">
							<div class="lbb-ai-field-row aid-max-700-off aid-mt-0 lbb_ai_prompt_response_wrapper">
								<div class="lbb-outcome-titles-step1">
								<div class="lbb_ai_prompt_format_wrapper lbb_ai_outcome_prompt_code_show ai-showonly-manual ai-outcom-type-manual">
									<h3>You can copy and paste this prompt in ChatGPT. It'll give you response in JSON format. Copy it back here for in the text area below.</h3>
									<div class="code-container">
										<div class="aiq-copy-btn-wrapper lbb-flex-field-left-right">
											<h3>ChatGPT Prompt</h3>
											<span class="copy-icon" data-id="lbb_ai_ot_outline_prompt" onclick="lbb_copy_to_clipboardNew(this)">
												<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
													<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
													<rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
												</svg>
												<i>Copy Code</i>
											</span>
										</div>
										<code id="lbb_ai_ot_outline_prompt" class="lbb_ai_prompt_format">
											<span>Don't add comma for the last array entry. Can you generate title for me in  below format and add backslash before quotes if string have any quotation:</span></code>
									</div>
								</div>

								<div class="lbb-ai-field-row aid-max-700-off aid-mt-20 ai-outcom-type-manual" style="">
									<label class="control-label">JSON resonse here:</label>
									<textarea name="lbb_ai_outcome_json_titles" id="lbb_ai_outcome_json_titles" placeholder="" class="lbb-ai-field-input"></textarea>
								</div>

								<div class="lbb-ai-field-row aid-max-700-off aid-mt-0 ai-outcom-type-api" style="">
									<div class=" lbb-empty-box">
										<div class="lbb-box-container">
											<i class="bx bx-bulb lbb-box-icon"></i>
											<p class="lbb-box-text">You can use AI for outcome ideas.</p>
											<a href="javascript:void(0);" class="lbb-add-new-rep box-button lbb-btn generate_outcome_titles lbb-btn-black">Click here for Outcome Title Ideas</a>
										</div>
									</div>
								</div>

								<div class="lbb-ai-field-row aid-max-700-off aid-mt-20 ai-outcom-type-manual" style="">
									<a href="javascript:void(0);" class="lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green generate_outcome_titles">Generate</a>
								</div>

								</div>
								
								<div class="lbb-outcome-titles-step2" style="display:none;">
								<div class="lbb-ai-field-row aid-max-700-off  aid-mt-20 " style="">
									<h3>Pick outcomes</h3>
									<div id="lbb_checkboxList"></div>
								</div>

								<div class="lbb-ai-field-row aid-max-700-off  aid-mt-20" style="">
									<a href="javascript:void(0);" class="generate_outcome_titles_back lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green lbb-ai-btn-gray">Back</a>
									<a href="javascript:void(0);" class="lbb-ai-btn lbb-btn lbb-btn-primary lbb-btn-green generate_outcome_titles_save">I'm ready for next step</a>
								</div>

								</div>
							</div>
						</div>



																	

					</div>
        		</div>
        	</div>
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
				<p></p>
                </div>
            </footer>
            
        </div>
    </div>
</div>


<div id="propwrap" class="lbb-popup-main lbb-ai-goal-example-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>See the example</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
        		<div class="lbb-popup-content-wrapper">
					<div class="aiq-popup-field-outer">

						<div class="lbb-ai-course-reference">
							<strong>Where are you stuck currently</strong>
							<p class="lbb-ai-course-reference-goal">I help people with their online marketing challenges and show them how they can easily get their products in front of more people, qualify leads, build an audience, make more sales, not be overwhelmed by tech - by using the right tools, keeping things simple, and offering the right types of product and services (lead magnets, memberships, courses, digital products, quizzes, coaching, etc.).<br /><br />I want to create a bot funnel to understand where are they stuck currently in their online business journey and help them get un-stuck in these areas through my coaching, products and services. <br/><br/>Help me build a conditional bot funnel where the questions are based on their previous responses in the funnel. </p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Lead Qualification</strong>
							<p class="lbb-ai-course-reference-goal">I want to create a bot to qualify my leads. I want to understand their barriers to purchase by asking them different questions related to my product, their goals, and remove any purchase barriers by handling their objections.<br /><br />

I want to add conditional bot funnel and ask questions based on their previous responses.<br /><br />

I want to learn about:<br />
What has brought them to my site?<br />	
What questions do they have about my product?<br />
What concerns do they have about my product?<br />
What are they main pain points that's stopping them from accomplishing their goals?<br />
What's their budget? </p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Lead Qualification Bot</strong>
							<p class="lbb-ai-course-reference-goal">I need a bot to help me qualify leads by asking a series of questions and segmenting them based on their responses. The bot should identify high-quality leads for our sales team.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Product Recommendation Bot:</strong>
							<p class="lbb-ai-course-reference-goal">I'm looking for a bot that can recommend the best products or services to our website visitors based on their preferences, browsing behavior, and previous purchases.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Customer Support Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We want a bot to provide instant customer support on our website. It should answer common customer queries, guide users to resources, and escalate complex issues to a human agent.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Appointment Scheduling Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We need a bot that allows clients to schedule appointments with our team. The bot should check availability, book appointments, and send reminders.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Survey and Feedback Bot:</strong>
							<p class="lbb-ai-course-reference-goal">I'm interested in creating a bot that can conduct surveys and gather feedback from our customers or website visitors. It should be able to analyze the responses and provide insights.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>E-commerce Sales Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We want a bot to assist users in the online shopping process, answer product-related questions, provide recommendations, and facilitate the purchase of products in our e-commerce store</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Event Registration Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We're hosting an event and need a bot that can handle event registrations. It should collect attendee information, send confirmations, and provide event details.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Language Translation Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We need a bot that can translate text from one language to another. Users should be able to input text, and the bot should provide translations in real-time.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Social Media Engagement Bot:</strong>
							<p class="lbb-ai-course-reference-goal">I'm looking for a bot to manage our social media presence. It should schedule posts, interact with followers, and gather analytics on social media engagement.</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>Learning and Training Bot:</strong>
							<p class="lbb-ai-course-reference-goal">We want to create a bot for onboarding and training purposes. It should provide information, quizzes, and interactive lessons to help employees or users learn about a specific topic or product.</p>
						</div>
					</div>
        		</div>
        	</div>
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
				<p></p>
                </div>
            </footer>
            
        </div>
    </div>
</div>

<div id="propwrap" class="lbb-popup-main lbb-ai-target-audience-example-popup">
    <div class="lbb-popup-container">
        <div class="lbb-modal-start">
            <header class="lbb-modal-header">
                <div class="lbb-modal-header-inner">
                    <h2>See the example</h2>
                    <div id="close" class="lbb-delete-icon">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
            </header>
            <div class="lbb-popup-body-wrapper">
        		<div class="lbb-popup-content-wrapper">
					<div class="aiq-popup-field-outer">
						<div class="lbb-ai-course-reference">
							<strong>Target Audience:</strong>
							<p class="lbb-ai-course-reference-goal">Online shoppers</p>
						</div>
						<hr class="lbb-ai-course-reference-divider">

						<div class="lbb-ai-course-reference">
							<strong>E-commerce Product Recommendation Bot:</strong>
							<p class="lbb-ai-course-reference-goal">Existing customers seeking support</p>
						</div>
						
					</div>
																	
        		</div>
        	</div>
            <footer class="lbb-popup-footer-wrapper">
                <div class="lbb-popup-btn-footer">
                   
                </div>
            </footer>
            
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
                <label class="lbb-preview-iframe-heading">This is a preview of how the bot will look in the frontend</label>
                <iframe class="lbb-iframe-main" src="{{IFRAMEURL}}" width="100%" height="700" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
</script>
<input type="hidden" id="site_url" name="site_url" value="<?php echo home_url(); ?>">
<?php get_lbb_footer(); ?>
