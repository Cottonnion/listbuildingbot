<?php
get_lbb_header();

$id = "";
$content = "";
$pdf_content_name = "";
$page_view = "portrait";

if(isset($_GET['id'])){
	$id = $_GET['id'];

	$pdfcontent = new PDFContentManager();
	$pdf_data = $pdfcontent->loadById($id);
	//echo '<pre>';print_r($pdf_data);
	// $pdf_data = cpb_PdfContent::loadById($id);

	if(isset($pdf_data) && !empty($pdf_data)){
		$pdf_content_name = $pdf_data['name'];
		$pdf_content = $pdf_data['content'];
		$pdf_contents = unserialize($pdf_content);

		$other_options = $pdf_data['other_options'];

		if(!empty($other_options)){
			$other_options_unserialize = unserialize($other_options);

			if(!empty($other_options_unserialize["page_view"])){
  				$page_view = $other_options_unserialize["page_view"];
  			}
		}
		
		$i = 1;
		foreach($pdf_contents as $pdf_content){
			$active_class = "";
			if($i == 1){
				$active_class = "active";
			}

			if($pdf_content['type'] == 'image'){
				$content .= '<div class="pdf-slider-box pdf-slide-img '.$active_class.'" style=" background-image: url('.$pdf_content['data'].'); "><input type="hidden" value="'.$pdf_content['data'].'" class="pdf-slide-hidden-image">
					</div>';
			}else if($pdf_content['type'] == 'text'){
				$content .= '<div class="pdf-slider-box pdf-slide-text '.$active_class.'"><textarea class="pdf-content-data" style="display:none;">'.stripslashes($pdf_content['data']).'</textarea></div>';
			}
			$i++;
		}
	}
}
?>
<input type="hidden" id="pdf_id" value="<?php echo $id; ?>">
<input type="hidden" id="get_home_url" value="<?php echo get_home_url(); ?>">

<div class="lbb-tab-main" role="group">
    <div class="lbb-tab-tool-bar">
        <div class="lbb-header-tabs-part">
			<h1 class="wp-heading-inline">PDF Builder</h1>
            <div class="lbb-chatflow-save-action chatflow-save-action-400">

            	<a href="javascript:void(0)" class="lbb-btn pdf-content-back lbb-btn-black" onclick="cpb_back_btn()" style="display:none;"> Back </a>
				<a href="javascript:void(0)" class="lbb-btn pdf-content-save lbb-btn-black" onclick="cpb_save_pdf_content_data()"> Save </a>
				<a href="javascript:void(0)" class="lbb-btn pdf-content-save lbb-btn-black" onclick="cpb_save_pdf_content_data('next')"> Save and Next </a>

            </div>
        </div>
    </div>
</div>

            <div class="cpb-pdf-generator-main">
	<div class="cpb-pdf-generator-main-container <?php echo $page_view == 'landscape' ? 'pdf_landscape_view':''; ?>">
		<div class="cpb-ai-style-quiz-start-screen cpb-ai-style-screen cpb-ai-style-quiz-common-info cpb-ai-active-screen">
			<div class="pdf-content-wrapper">
				<img src="<?php echo LBB_PDF_URL ?>assets/images/pdf.png"> 
				<h2 class="cpb-ai-style-section-heading">Smart PDF Builder</h2>
				<p class="cpb-ai-style-section-content mb-1"> Enter the PDF name. Add the images/content. LBB will create the PDF for you!</p>

				<div class="cpb-ai-style_form_input">
					<div class="cpb-ai-style_form_input_wrapper">
						<div class="lbb-form-group lbb-chatboat-video-img-text">
                            <label for="lbb_video_text change-with-survey">What is the PDF title?</label>
                            <input type="text" placeholder="Enter PDF name" id="pdf_content_name" class="lbb-input-field" name="pdf_content_name" value="<?php echo $pdf_content_name; ?>">
							<div class="empty-name-error" style="display:none;">Please enter name</div>
                        </div>


					</div>
				</div>


				<div class="cpb-ai-style_form_input">
					<div class="cpb-ai-style_form_input_wrapper">
						<div class="gpt-field-main-wrapper cpb-ai-style-normal">
							<label class="control-label change-with-survey">Select Type</label>


							<div class="inline-bg-options" style="">
	                           <ul>
	                              <li>
	                                 <div class="lbb-form-group lbb-radio-buttons">
	                                 	<input type="radio" name="page_view_option" id="page_view_option_portrait" value="portrait" <?php echo $page_view == 'portrait' ? 'checked':''; ?> >

	                                    <label for="page_view_option_portrait">Portrait</label>
	                                    <div class="lbb-check"></div>
	                                 </div>
	                              </li>

	                              <li>
	                                 <div class="lbb-form-group lbb-radio-buttons">
	                                   <input type="radio" name="page_view_option" id="page_view_option_landscape" value="landscape" <?php echo $page_view == 'landscape' ? 'checked':''; ?>>
	                                    <label for="page_view_option_landscape">Landscape</label>
	                                    <div class="lbb-check"></div>
	                                 </div>
	                              </li>
	                           </ul>
	                        </div>

						</div>
					</div>
				</div>
				<?php /* <button class="cpb-new-btn pdf-back-btn">Back</button> */?>
				<button class="cpb-new-btn pdf-next-btn" data-next="cpb-pdf-generator-screen">Next</button>
			</div>
		</div>

		<div id="cpb-pdf-generator-screen" class="cpb-ai-style-screen cpb-ai-style-quiz-common-info">
			
			<div class="pdf-slide-append-main">
				<div class="pdf-slide-box-create-box cpb-ai-active">
					<div class="cpb-ai-style-form-template-wrapper">
						<div class="cpb-ai-style-item-main-box">
							<div class="cpb-ai-style-item-inner">
								<div class="cpb-ai-style-item-text">
									<h2>Add an Image</h2>
									<p>Upload Image</p>
									<button class="cpb_add_image cpb-new-btn">Add an Image</button>
								</div>
							</div>
							<div class="cpb-ai-style-item-inner">
								<div class="cpb-ai-style-item-text">
									<h2>Add Content</h2>
									<p>Add Content to PDF</p>
									<button class=" cpb_add_content cpb-new-btn">Add Content</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="pdf-slide-sticky-bottom-page">
				<div class="pdf-slide-sticky-bottom-page-preview-box" id="sortable">
					<?php echo $content; ?>
					<div class="pdf-slider-box pdf-slide-img pdf-slider-box-btn">
						<input type="hidden" value="" class="pdf-slide-hidden-image">
						<button class="add-slide-btn"><i class="fa fa-plus"></i></button>
					</div>
				</div>
			</div>
			<?php /*<div class="save-pdf-btn-outer">
				<div class="cpb-header-empty-box-space"></div>
				<div class="cpb-header-how-it-work">
					<div class="cpb-shortcode-heading"><span>How this works!</span> <!-- <div class="tool-tip custom-field-personadivze user-tags"><i class="fa fa-info-circle" aria-hidden="true"></i> <div class="toll-tip-desc rank_tag custom-field">
						<p>If you want to create a customized PDF for each outcome of your quiz and allow participants to download it, just create the PDF here.</p>
						<p>You'll be able to select this PDF in the cpb Quiz >> Outcome Screen and connect it to your outcome.</p>
						<p>You can create a unique PDF here for each outcome. Or use the same PDF for all outcomes. </p>
					</div> </div> --></div>
				</div>
				<div class="btn-right-side">
					<a href="javascript:void(0)" class="pdf-content-back cpb-new-btn"> Back </a>
					<a href="javascript:void(0)" class="pdf-content-save cpb-new-btn" onclick="cpb_save_pdf_content_data()"> Save </a>
					<a href="javascript:void(0)" class="pdf-content-save cpb-new-btn" onclick="cpb_save_pdf_content_data('next')"> Save and Next </a>
				</div>
			</div>
		</div> */ ?>

		

	</div>
	<div id="cpb-pdf-thankyou-screen" class="cpb-ai-style-screen cpb-ai-style-quiz-common-info">
		<div class="pdf-content-wrapper">
			<img src="<?php echo LBB_PDF_URL ?>assets/images/pdf.png"> 
			<h2 class="cpb-ai-style-section-heading">Congrats your PDF is ready!</h2>
			
			<div class="preview-wrapper">
				<a class="cpb-goback-btn lbb-btn lbb-btn-primary" href="javascript:void(0);">
					Go Back
				</a>
				<a class="cpb-preview-pdf lbb-btn lbb-btn-black" href="<?php echo site_url().'?lbb-pdf-download=1&preview=1&pdf-id='.$id; ?>" target="_blank">
					Click to Preview
				</a>
				<a class="cpb-gotolist lbb-btn lbb-btn-secondary" href="<?php echo admin_url( 'admin.php?page=listbuildingbot-pdf-builder' ); ?>">Go to list</a>
			</div>

			<p class="cpb-ai-style-section-content cpb-alert">
				<strong>Please note:</strong>
				This is just a preview.<br />
				The merge tags will not be replaced in the Preview but will be replaced when chatbot takers download it.</p>
		</div>
	</div>

	

</div>
<div class="pdf-slide-box-create-box-a4-size-wrapper  pdf-for-img" style="display: none;">
	<div class="pdf-slide-box-create-box-a4-size cpb-ai-active  pdf-screen-img">
		<div class="action-btn-wrapper">
			<button class="edit-img"><i class="fa fa-pencil" aria-hidden="true" title="Edit"></i></button>
			<button class="delete-img"><i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i></button>
		</div>
		<div class="cpb-pdf-img">
			%%DYNAMIC_IMAGE%%
		</div>
	</div>
</div>
<div class="pdf-slide-box-create-box-a4-size-wrapper pdf-for-content" style="display: none;">
	<div class="pdf-slide-box-create-box-a4-size cpb-ai-active pdf-screen-content">
		<div class="cpb-pdf-content">
			<div class="action-btn-wrapper">
				<button class="delete-img"><i class="fa fa-trash-o" aria-hidden="true" title="Delete"></i></button>
			</div>
			<div class="pdf-content-page-name">
				<div class="d-flex align-items-center pdf_content-page-title ">
					<h3 class="section_heading"> Content</h3>
				</div>
				<!-- <div class="personalize_options pdf-personalize-btn">
					<a class=" cpb-new-btn btn-pdf-ai-popup">Use AI for Content</a>
					<a class=" cpb-new-btn btn-pdf-avalible-tags-popup"> Personalize</a>
				</div> -->
				<div class="form-group row mb-4 ml-4">
				  	<div class="col-sm-10">
						
					   	<textarea class="pdf-content-area" id="pdf_content_data_%%UNIQUEID%%" style="height: 225px;">%%DYNAMIC_TEXTAREA%%</textarea> </div>
				</div>
				<div class="save-button"><button type="button" class="btn btn-info save-content-data cpb-new-btn">Save</button></div>
			</div>
		</div>
	</div>
</div>

<?php get_lbb_footer(); ?>