jQuery(document).ready(function(){

	jQuery('input[name="page_view_option"]').on('change', function(){
		var get_value = jQuery(this).val();
		if(get_value == 'landscape'){
			jQuery('.cpb-pdf-generator-main-container').addClass('pdf_landscape_view');
		}else{
			jQuery('.cpb-pdf-generator-main-container').removeClass('pdf_landscape_view');
		}
	});

	jQuery('.pdf-next-btn').on('click', function() {
		if(jQuery('#pdf_content_name').val() == ''){
			jQuery('.empty-name-error').show();
			return false;
		}
		jQuery('.pdf-content-back').show();
		jQuery('.empty-name-error').hide();
	  	const nextDivId = jQuery(this).attr('data-next');
	  	jQuery('.cpb-ai-style-quiz-common-info').removeClass('cpb-ai-active-screen');
	  	const targetDiv = jQuery('#' + nextDivId);
	  	if (targetDiv.length) {
	    	targetDiv.addClass('cpb-ai-active-screen');
	 	}
	 	jQuery('.pdf-slider-box.active').trigger('click');
	});

jQuery(document).on('click','.cpb_add_image',function() {
		var data = jQuery(this);
	   	var cpb_mediauploader;
	   	window.cpb_img_class = jQuery(this).attr('data-class');	 
		if (cpb_mediauploader) {
			cpb_mediauploader.open();
			return;
		}
		cpb_mediauploader = wp.media.frames.file_frame = wp.media({
			title: 'Add Image',
			button: {
				text: 'Add Image'
			},
			library: {
		            type: [ 'image' ]
		    },
			multiple: true
		});
		cpb_mediauploader.on('select', function() {
			gallerySelection = cpb_mediauploader.state().get('selection').toJSON();
			gallerySelection.map(function(attachment) {
				var attachment_url = attachment.url;
				var originalHtml = jQuery('.pdf-slide-box-create-box-a4-size-wrapper.pdf-for-img').html();
				jQuery('.pdf-slide-box-create-box').removeClass('cpb-ai-active');
				jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box.active').removeClass('active');
				var newImageTag = '<img src="'+attachment_url+'">';
				var modifiedHtml = originalHtml.replace('%%DYNAMIC_IMAGE%%', newImageTag);
		    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').append(modifiedHtml);
		    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main .pdf-slide-box-create-box-a4-size').removeClass('cpb-ai-active');
				jQuery('<div class="pdf-slider-box pdf-slide-img active" style=" background-image: url('+attachment_url+'); "><input type="hidden" value="'+attachment_url+'" class="pdf-slide-hidden-image"></div>').insertBefore('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box-btn');
			});

			jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main .pdf-slide-box-create-box-a4-size:last-child').addClass('cpb-ai-active');
			jQuery( function() {jQuery( "#sortable" ).sortable(); } ); 
		});
		cpb_mediauploader.open();
	});

	jQuery(document).on('click','.cpb_add_content',function() {
		var get_html = jQuery('.pdf-for-content').html();
		var modifiedHtml = get_html.replace('%%DYNAMIC_TEXTAREA%%', '<p><span style="font-family: "open sans", sans-serif; font-size: 15pt;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce in consequat orci. Proin congue cursus eros, sit amet hendrerit sapien. Nulla vitae velit sit amet magna hendrerit rutrum vitae ac purus. Praesent viverra ultrices rhoncus. Nam eu tempus sem, vitae molestie erat. Nunc ut nibh vitae libero varius hendrerit. Sed in odio odio. Nullam at tempus nibh.</span></p>');
		modifiedHtml = modifiedHtml.replace('%%UNIQUEID%%', generateUniqueId());

		jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').append(modifiedHtml);
		jQuery('.pdf-slide-box-create-box').removeClass('cpb-ai-active');
		jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box.active').removeClass('active');
		jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').find('.pdf-content-area').addClass('cpb_text_editor');
		cpb_pdf_content_text_tiny_mce_editor();
		jQuery('<div class="pdf-slider-box pdf-slide-text active"><textarea class="pdf-content-data" style="display:none;"><p><span style="font-family: "open sans", sans-serif; font-size: 15pt;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce in consequat orci. Proin congue cursus eros, sit amet hendrerit sapien. Nulla vitae velit sit amet magna hendrerit rutrum vitae ac purus. Praesent viverra ultrices rhoncus. Nam eu tempus sem, vitae molestie erat. Nunc ut nibh vitae libero varius hendrerit. Sed in odio odio. Nullam at tempus nibh.</span></p></textarea></div>').insertBefore('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box-btn');
	});

	/*jQuery('.pdf-content-back').on('click', function() {
		save_pdf_content_data_on_click();
	  	jQuery('.cpb-ai-style-quiz-common-info').addClass('cpb-ai-active-screen');
	  	jQuery('#cpb-pdf-generator-screen').addClass('cpb-ai-active-screen');
	});*/

	jQuery(document).on('click','.save-content-data',function() {
		var get_id = jQuery('.pdf-slide-append-main .pdf-content-area').attr('id');
		var get_pdf_data = tinymce.get(get_id).getContent();
		jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box.active').find('.pdf-content-data').val(get_pdf_data);
		jQuery(this).text('Saving...');
		setTimeout(function(){
			jQuery('.save-content-data').text('Save');
		}, 1000);
	});

	jQuery(document).on('click','.add-slide-btn',function() {
		save_pdf_content_data_on_click();
		jQuery('#cpb-pdf-generator-screen .pdf-slide-box-create-box-a4-size').removeClass('cpb-ai-active');
		jQuery('#cpb-pdf-generator-screen .pdf-slide-box-create-box').addClass('cpb-ai-active');
		jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main .pdf-screen-img').remove();
		jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main .pdf-screen-content').remove();
	});

	jQuery(document).on('click','.delete_pdf_content_by_id',function(e){
		e.preventDefault();
		var current_obj = this;
		var id = jQuery(this).attr('data-id'); 

		lbbConfirmationDialog(
			"Are you sure?", 
			"If you choose to delete this PDF, please be aware that the action is irreversible and cannot be undone.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				jQuery.ajax({
				type: 'POST',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'cpbDeletePdfContentByIdAjax',
					'id': id
				},
				success: function (response) {
					jQuery('.sqb-page-delete-alert').show();
					setTimeout(function() {
				        jQuery('.sqb-page-delete-alert').hide();
				    }, 5000);
				    
					//sqb_pdf_content_hide_loader();
					current_obj.closest('tr').remove();
					//var table = jQuery(current_obj).closest('.cpb_member_page_table_class').DataTable();
					//table.row(jQuery(current_obj).closest('.cpb_member_page_table_class').find('tr.cpb_member_manage_page_row_id_'+id)).remove().draw();
				}
			});
			}
		});
	});	
	
	jQuery(document).on('click','.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box',function() {
		save_pdf_content_data_on_click();
	    jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box.active').removeClass('active');
	    jQuery(this).addClass('active');
	    if(jQuery(this).find('.add-slide-btn').length != 0){
	    	return false;
	    }
	    jQuery('#cpb-pdf-generator-screen .pdf-slide-box-create-box-a4-size').remove();
	    if(jQuery(this).hasClass('pdf-slide-text')){
	    	var html_data = jQuery(this).find('.pdf-content-data').val();
	    	var get_html = jQuery('.pdf-for-content').html();
	    	if(html_data){
	    		html_data = html_data;
	    	}else{
	    		html_data = '';
	    	}
	    	var modifiedHtml = get_html.replace('%%DYNAMIC_TEXTAREA%%', html_data);
	    	modifiedHtml = modifiedHtml.replace('%%UNIQUEID%%', generateUniqueId());
	    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').append(modifiedHtml);
			jQuery('.pdf-slide-box-create-box').removeClass('cpb-ai-active');
			jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').find('.pdf-content-area').addClass('cpb_text_editor');
			cpb_pdf_content_text_tiny_mce_editor();
	    }else if(jQuery(this).hasClass('pdf-slide-img')){
	    	var bg = jQuery(this).css('background-image');
			var attachment_url = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
	    	var originalHtml = jQuery('.pdf-slide-box-create-box-a4-size-wrapper.pdf-for-img').html();
			jQuery('.pdf-slide-box-create-box').removeClass('cpb-ai-active');
			var newImageTag = '<img src="'+attachment_url+'">';
			var modifiedHtml = originalHtml.replace('%%DYNAMIC_IMAGE%%', newImageTag);
	    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').append(modifiedHtml);
	    }
	});

	jQuery(document).on('click','.edit-img',function() {
		var data = jQuery(this);
	   	var cpb_mediauploader;
	   	window.cpb_img_class = jQuery(this).attr('data-class');	 
		if (cpb_mediauploader) {
			cpb_mediauploader.open();
			return;
		}
		cpb_mediauploader = wp.media.frames.file_frame = wp.media({
			title: 'Add Image',
			button: {
				text: 'Add Image'
			},
			library: {
		            type: [ 'image' ]
		    },
			multiple: true
		});
		cpb_mediauploader.on('select', function() {
			attachment = cpb_mediauploader.state().get('selection').first().toJSON();
			var attachment_url = attachment.url;
			var originalHtml = jQuery('.pdf-slide-box-create-box-a4-size-wrapper.pdf-for-img').html();
			var newImageTag = '<img src="'+attachment_url+'">';
			var modifiedHtml = originalHtml.replace('%%DYNAMIC_IMAGE%%', newImageTag);
	    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main .pdf-slide-box-create-box-a4-size').remove();
	    	jQuery('#cpb-pdf-generator-screen .pdf-slide-append-main').append(modifiedHtml);
	    	jQuery('.pdf-slider-box.active').css('background-image', 'url(' + attachment_url + ')');
	    	jQuery('.pdf-slider-box.active').find('.pdf-slide-hidden-image').val(attachment_url);
		});
		cpb_mediauploader.open();
	});

	jQuery(document).on('click','.delete-img',function() {
		var next_div = jQuery('.pdf-slide-sticky-bottom-page .pdf-slider-box.active').next();

		jQuery('.pdf-slide-append-main .pdf-slide-box-create-box-a4-size').remove();
		jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box.active').remove();
		if(next_div.find('.add-slide-btn').length == 1){
			jQuery('.add-slide-btn').trigger('click');
		}else{
			next_div.addClass('active').trigger('click');
		}
		// jQuery('.pdf-slide-append-main .pdf-slide-box-create-box').addClass('cpb-ai-active');
	});

	jQuery(document).on('click','.cpb-goback-btn',function() {
		jQuery('.cpb-ai-style-screen').removeClass('cpb-ai-active-screen');
		jQuery('#cpb-pdf-generator-screen').addClass('cpb-ai-active-screen');
	});

	jQuery(document).on('click','.cpb-preview-pdf',function() {
		var link = jQuery(this).attr('href');
		var pdf_id = jQuery('#pdf_id').val();
		//jQuery(this).attr('href',link+pdf_id);
		/*var downloadText = 'Please wait...';
		var formdata = [];
		var pdf_id = jQuery('#pdf_id').val();
        formdata.push({name: "pdf_id", value: pdf_id});
		jQuery(this).html(downloadText);
		jQuery(this).addClass('downloading-pdf');
		jQuery.ajax({
			type: "POST",
			url: '?cpb_pdf_preview_v2=1',
			data: formdata,
			xhrFields: {
				responseType: 'blob'
			},
			success: function(blob, status, xhr) {
				jQuery('.cpb-preview-pdf').html('Click to Preview');
				jQuery('.cpb-preview-pdf').removeClass('downloading-pdf');
			
				

				var filename = "";
				var disposition = xhr.getResponseHeader('Content-Disposition');
				if (disposition && disposition.indexOf('attachment') !== -1) {
					var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
					var matches = filenameRegex.exec(disposition);
					if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
				}

				if (typeof window.navigator.msSaveBlob !== 'undefined') {
					window.navigator.msSaveBlob(blob, filename);
				} else {
					var URL = window.URL || window.webkitURL;
					var downloadUrl = URL.createObjectURL(blob);
					if (filename) {
						var a = document.createElement("a");
						if (typeof a.download === 'undefined') {
							window.location.href = downloadUrl;
						} else {
							a.href = downloadUrl;
							a.download = filename;
							document.body.appendChild(a);
							a.click();
						}
					} else {
						window.location.href = downloadUrl;
					}
					setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100);
				}
			},error: function(XMLHttpRequest, textStatus, errorThrown) { 
				jQuery('.cpb-preview-pdf').html('Click to Preview');
				jQuery('.cpb-preview-pdf').removeClass('downloading-pdf');
			}   
		});*/
	});

});

function save_pdf_content_data_on_click(){
	if(jQuery('.cpb-pdf-generator-main-container .pdf-slide-box-create-box-a4-size.pdf-screen-content').hasClass('cpb-ai-active')){
		jQuery('.save-content-data').trigger('click');
	}
}

function cpb_pdf_content_text_tiny_mce_editor() {
	tinymce.init({
	 	mode : "specific_textareas",
		editor_selector : "cpb_text_editor",
		resize: "both",
		fontsize_formats: '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 20pt 24pt 30pt 36pt',
	   	font_formats: "Andale Mono=andale mono,times;" + "Arial=arial,helvetica,sans-serif;" + "Arial Black=arial black,avant garde;" + "Book Antiqua=book antiqua,palatino;" + "Comic Sans MS=comic sans ms,sans-serif;" + "Courier New=courier new,courier;" + "Georgia=georgia,palatino;" + "Helvetica=helvetica;" + "Impact=impact,chicago;" + "Montserrat=Montserrat,sans-serif;Open Sans=open sans,sans-serif;Poppins=Poppins,sans-serif;Lato=Lato,sans-serif;Nunito=Nunito,sans-serif;Noto Serif=Noto Serif,sans-serif;Noto Sans=Noto Sans,sans-serif;Raleway=Raleway,sans-serif;" + "Symbol=symbol;" + "Tahoma=tahoma,arial,helvetica,sans-serif;" + "Terminal=terminal,monaco;" + "Times New Roman=times new roman,times;" + "Trebuchet MS=trebuchet ms,geneva;" + "Verdana=verdana,geneva;" + "Webdings=webdings;" + "Wingdings=wingdings,zapf dingbats",
		content_style:"@import url('https://fonts.googleapis.com/css?family=Open+Sans');@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');@import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');@import url('https://fonts.googleapis.com/css2?family=Nunito&display=swap');@import url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');@import url('https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap');@import ur('https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap');",  
		toolbar1: 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		toolbar2: 'print preview media | forecolor backcolor emoticons ',      
		relative_urls: false,
		remove_script_host: false,
		convert_urls:false,
		templates: [
		   { title: 'Test template 1', content: 'Test 1' },
		   { title: 'Test template 2', content: 'Test 2' }
		   ],
	   	setup : function(ed) {
			ed.on('init', function() {
				//ed.execCommand("fontName", false, "'open sans', sans-serif");
				jQuery(ed.getDoc()).contents().find('body').blur(function(){
				   
				});  
			});
		}
	});
}

function cpb_back_btn(){
	if(jQuery('#cpb-pdf-thankyou-screen').hasClass('cpb-ai-active-screen')){

		jQuery('#cpb-pdf-thankyou-screen').removeClass('cpb-ai-active-screen');
		jQuery('#cpb-pdf-generator-screen').addClass('cpb-ai-active-screen');
		
			
	}else if(jQuery('#cpb-pdf-generator-screen').hasClass('cpb-ai-active-screen')){
		
		jQuery('#cpb-pdf-generator-screen').removeClass('cpb-ai-active-screen');
		jQuery('.cpb-ai-style-quiz-start-screen').addClass('cpb-ai-active-screen');
	}

	if(jQuery('.cpb-ai-style-quiz-start-screen').hasClass('cpb-ai-active-screen')){
		jQuery('.pdf-content-back').hide();
	}

}

function cpb_save_pdf_content_data(next){

	if(jQuery('.cpb-ai-style-quiz-start-screen').hasClass('cpb-ai-active-screen')){

		if(next == 'next'){
			if(jQuery('#pdf_content_name').val() == ''){
				jQuery('.empty-name-error').show();
				return false;
			}
			jQuery('.empty-name-error').hide();
		  	jQuery('.cpb-ai-style-quiz-common-info').removeClass('cpb-ai-active-screen');
		  	const targetDiv = jQuery('#cpb-pdf-generator-screen');
		  	if (targetDiv.length) {
		    	targetDiv.addClass('cpb-ai-active-screen');
		 	}
		 	jQuery('.pdf-slider-box.active').trigger('click');
			 jQuery('.pdf-content-back').show();
		}
			
	}else if(jQuery('#cpb-pdf-generator-screen').hasClass('cpb-ai-active-screen')){
		if(jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box').length == 1){
			swal('Please add Image or Content');
			return false;
		}
		jQuery('#cpb-pdf-generator-screen').removeClass('cpb-ai-active-screen');
		jQuery('#cpb-pdf-thankyou-screen').addClass('cpb-ai-active-screen');
	}

	var get_home_url = jQuery('#get_home_url').val();

	save_pdf_content_data_on_click();
	var data = [];
	var name = jQuery('#pdf_content_name').val();
	var page_view = jQuery('input[name="page_view_option"]:checked').val();
	var pdf_id = jQuery('#pdf_id').val();

	var other_optionss = [];
	other_options = {'page_view': page_view};

	jQuery('.pdf-slide-sticky-bottom-page-preview-box .pdf-slider-box').each(function(){

		if(jQuery(this).find('.add-slide-btn').length != 0){
	    	
	    }else{
	    	if(jQuery(this).hasClass('pdf-slide-text')){
	    		var html_data = jQuery(this).find('.pdf-content-data').val();
		    	var get_html = jQuery('.pdf-for-content').html();
		    	if(html_data){
		    		html_data = html_data;
		    	}else{
		    		html_data = '';
		    	}
		    	data.push({
				    id: generateUniqueId(),
				    type: 'text',
				    data: html_data
				  });
	    	}else if(jQuery(this).hasClass('pdf-slide-img')){
	    		//var bg = jQuery(this).css('background-image');
				//var img_url = bg.replace('url(','').replace(')','').replace(/\"/gi, "");
				var img_url = jQuery(this).find('.pdf-slide-hidden-image').val();
				data.push({
				    id: generateUniqueId(),
				    type: 'image',
				    data: img_url
				  });
	    	}
	    }
	});

	
	lbbShowLoader();
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'cpb_save_pdf_content_data',
			'data': data,
			'name': name,
			'other_options' : other_options,
			'pdf_id' : pdf_id,
		},
		success: function (response) {
			lbbHideLoader();
			var result = jQuery.parseJSON(response);
			if(result.id){
				jQuery('#pdf_id').val(result.id);
				jQuery('.cpb-preview-pdf').attr('href', get_home_url+"/?lbb-pdf-download=1&preview=1&pdf-id="+result.id)
				if(next == 'next'){
					//jQuery('.cpb-ai-style-screen').removeClass('cpb-ai-active-screen');
					//jQuery('#cpb-pdf-thankyou-screen').addClass('cpb-ai-active-screen');
				}
			}
		}
	});
}