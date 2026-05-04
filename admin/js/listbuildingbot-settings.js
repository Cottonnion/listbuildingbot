function save_global_form_settings(){
	jQuery(".lbb-form-global-settings").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery('.global-style-save').text('Saving...');
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_global_form_settings_data',
				'settings': jQuery('.lbb-form-global-settings').serialize(),
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('.global-style-save').text('Save');
				//jQuery('.global-style-save-outer').after('<span class="saved-success-msg">Saved Successfully</span>');

				/*setTimeout(function(){
					jQuery('.saved-success-msg').remove();
				}, 3000);*/
				
			}
		});
	});
}

function lbb_content_text_tiny_mce_editor_mini() {
	tinymce.init({
	 	mode : "specific_textareas",
		editor_selector : "lbb_tiny_text_editor_mini",
  		paste_as_text: true,
		resize: "both",
		height: 100,
		fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px",
			lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.6 1.7 1.8 1.9 2", 
		toolbar1: 'bold italic | link',
		toolbar2: '',
		relative_urls: false,
		remove_script_host: false,
		convert_urls:false,
		plugins: [
		    'link',
		    'paste'
		  ],
		extended_valid_elements: 'a[href|target=_blank|rel|id]', 
		templates: [
		   { title: 'Test template 1', content: 'Test 1' },
		   { title: 'Test template 2', content: 'Test 2' }
		   ],
		menubar: false,
	   	setup : function(ed) {
			ed.on('init', function() {
				//ed.execCommand("fontName", false, "'open sans', sans-serif");
				jQuery(ed.getDoc()).contents().find('body').blur(function(){
				   
				});  
			});
		}
	});
}

function load_outcome_data_popup(outcomes_id){
	lbbShowLoader();
	jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
					'outcomes_id': outcomes_id,
					'action': 'load_outcomes_data_by_id',
				},
			success: function (response) {
				lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						lbb_content_text_tiny_mce_editor();
						jQuery('.lbb-outcomes-popup').addClass('expanded');
						jQuery('#lbb_outcomes_id').val(response.id);
						tinymce.get("lbb_outcomes_description").setContent(response.content);
						jQuery('#lbb_outcomes_name').val(response.name);
						jQuery('#lbb_chatflow_id').val(response.chatflow_id).trigger('change');

						if(response.outcome_image){
							jQuery('#save-outcomes-data .lbb-image-upload-container-with-preview').removeClass('lbb-no-img');
							jQuery('#save-outcomes-data #lbb_outcome_image_upload').val(response.outcome_image);
							jQuery('#save-outcomes-data .lbb-preview-image').attr('src',response.outcome_image);
						}else{
							jQuery('#save-outcomes-data .lbb-image-upload-container-with-preview').addClass('lbb-no-img');
							jQuery('#save-outcomes-data #lbb_outcome_image_upload').val('');
							jQuery('#save-outcomes-data .lbb-preview-image').attr('src','');
						}

					}
			}
		});
}

function save_customfield_data(){
	jQuery("#save-customfield-data").submit(function(e) {
		e.preventDefault();
		jQuery('.lbb-invalid-characters').hide();
		if(jQuery('#lbb_label').val() == ''){
			jQuery('.empty-label-error').show();
			return false;
		}
		if(jQuery('#lbb_name').val() == ''){
			jQuery('.empty-name-error').show();
			return false;
		}

		var inputString = jQuery('#lbb_name').val();
        var regex = /[ \\\!@#$%^&*()+{}\[\]:;<>,.?~\\/-]/;

        if (regex.test(inputString)) {
            
            jQuery('.lbb-invalid-characters').show();
            return false;
        }
		jQuery('.empty-label-error').hide();
		jQuery('.empty-name-error').hide();
		jQuery('#lbb-save-customfield').text('Saving...');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#save-customfield-data').serialize(),
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				console.log(response);
				jQuery('#lbb-save-customfield').text('Save');
				if(response == 'already_exist'){
					swal('Name Already Exist');
					return false;
				}
				load_customfields_data();
				jQuery('.lbb-customfield-popup').removeClass('expanded');
			}
		});
	});
}

//function save_outcomes_data(){
	//jQuery("#save-outcomes-data").submit(function(e) {
		//e.preventDefault();
		
	//});
//}

function load_outcomes_data(){
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'load_outcomes_data',
		},
		success: function (response) {
			response = JSON.parse(response);
			//if(response){
				jQuery('.outcomes-data').html('<table id="lbb-outcome-table" class="lbb-table-style"> <thead> <tr><th>Bot Funnel Name</th> <th>Outcome Title</th><th>Description</th> <th class="lbb-w-200p">Action</th> </tr> </thead> <tbody>'+response+'</tbody> </table>'); 
				if (jQuery.fn.DataTable.isDataTable('#lbb-outcome-table')) {
			      	outcome_table.clear().destroy(); 
			    }
			    	outcome_table = jQuery('#lbb-outcome-table').DataTable({
				        order: [],
				        bLengthChange: false,
				        pageLength: 20,
				        autoWidth: false,
				        bAutoWidth: false,
				        language: {
				          search: "",
				          searchPlaceholder: "Search...",
			          		emptyTable: "No Outcome found"
				        },
				        fnInitComplete: function() {
				          
				        }
				  	});
			  	
			  	
			//}
		}
	});
}

function save_tags_data(){
	jQuery("#save-tags-data").submit(function(e) {
		e.preventDefault();
		if(jQuery('#lbb_tags_name').val() == ''){
			jQuery('.empty-tagname-error').show();
			return false;
		}
		jQuery('.empty-tagname-error').hide();
		jQuery('#lbb-save-tags').text('Saving...');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#save-tags-data').serialize(),
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-tags').text('Save');
				jQuery('#lbb_label').val('');	
				jQuery('#lbb_name').val('');	
				jQuery('#lbb_field_type').val('textbox');	
				jQuery('#lbb_required_field').val('N');	
				load_tags_data();
				jQuery('.lbb-tags-popup').removeClass('expanded');
			}
		});
	});
}

function save_message_customizer_data(){
	jQuery("#save-message-customizer-data").submit(function(e) {
		e.preventDefault();
		jQuery('#lbb-save-message-customizer-btn').text('Saving...');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_message_customizer_data',
				'settings': jQuery('#save-message-customizer-data').serialize(),
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-message-customizer-btn').text('Saved Successfully');
				setTimeout(function(){
					jQuery('#lbb-save-message-customizer-btn').text('Save');
				}, 1000);
			}
		});
	});
}

function load_tags_data(){
	lbbShowLoader();
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'load_tags_data',
		},
		success: function (response) {
			response = JSON.parse(response);
			//if(response){
				jQuery('.tags-data').html('<div class="lbb-box lbb-section-bg-box lbb-mb-20 lbb-w-800p"><div class="lbb-datatable"><div class="lbb-datatable-btn"> <a class="lbb-btn lbb-btn-primary" id="lbb-create-new-tags" href="javascript:void(0);">Create a New tag</a> </div> <div class="lbb-no-scoll-x"><table class="lbb-tags-table lbb-table-style"> <thead> <tr> <th class="lbb-w-40">Name</th><th class="lbb-text-center lbb-w-12">Action</th> </tr> </thead> <tbody>'+response+'</tbody></table></div></div></div>');
			  	if (jQuery.fn.DataTable.isDataTable('.lbb-tags-table')) {
			      table.clear().destroy(); 
			    }
			  	table = jQuery('.lbb-tags-table').DataTable({
			        order: [],
			        bLengthChange: false,
			        pageLength: 20,
			        autoWidth: false,
			        bAutoWidth: false,
			        autoWidth: false,
			        language: {
			          search: "",
			          searchPlaceholder: "Search...",
			          emptyTable: "No tags found"
			        },
			        fnInitComplete: function() {
			          lbbHideLoader();
			        }
			  	});
			//}
		}
	});
}

function load_customfields_data(){
	jQuery.ajax({
	  	type: 'POST',
	  	url: lbb_ajax.ajaxurl,
		data: {
		    'action': 'load_customfield_data',
	  	},
	  	success: function (response) {
		    response = JSON.parse(response);
		    //if (response) {
		      jQuery('.customfield-data').html('<table class="lbb-customfield-table lbb-table-style"> <thead> <tr> <th class="lbb-w-40">Name</th> <th class="lbb-w-12">Type</th> <th class="lbb-text-center lbb-w-12">Action</th> </tr> </thead> <tbody>'+response+'</tbody> </table>'); 

			  	if (jQuery.fn.DataTable.isDataTable('.lbb-customfield-table')) {
			      table.clear().destroy(); 
			    }
			  	table = jQuery('.lbb-customfield-table').DataTable({
			        order: [],
			        bLengthChange: false,
			        pageLength: 20,
			        autoWidth: false,
			        bAutoWidth: false,
			        autoWidth: false,
			        language: {
			          search: "",
			          searchPlaceholder: "Search...",
			          emptyTable: "No Custom fields found"
			        },
			        fnInitComplete: function() {
			          
			        }
			  	});
		    //}
	  	}
	});
}

function open_outcome_popup(){
		var outcome_id = lbbUrlVars()["outcome_id"];
		load_outcome_data_popup(outcome_id);
}

jQuery(document).ready(function(){
	//save_outcomes_data();
	load_outcomes_data();
	save_tags_data();
	load_tags_data();
	save_global_form_settings();
	save_customfield_data();
	load_customfields_data();
	lbb_content_text_tiny_mce_editor_mini();
	save_message_customizer_data();
	open_outcome_popup();

	setTimeout(function(){
		var tab = lbbUrlVars()["tab"];
		if(tab == 'outcome'){
			jQuery("#outcomes-sublink").trigger('click');
		}

		if(tab == 'ai_assistant'){
			jQuery("#tabs-nav li:eq(5) a").trigger('click');
			
		 	jQuery('html, body').animate({
		        scrollTop: jQuery("#lbb-aiassistant-keys").offset().top
		    }, 2000);
		}

		if(tab == 'search_options'){
			jQuery("#search-sublink").trigger('click');
		}

		if(tab == 'tags'){
			setTimeout(function(){
				jQuery("#tags-sublink").trigger('click');
			}, 1000);
		}

		if(tab == 'firebase'){
			jQuery("#tabs-nav li:eq(2) a").trigger('click');
			setTimeout(function(){
				jQuery('.lbb-open-document-static').trigger('click');
			}, 500);
		}

		if(tab == 'live_chat'){
			jQuery("#tabs-nav li:eq(2) a").trigger('click');
		}


	}, 500);

	jQuery(document).on('change', 'input[name="lbb_gdpr_settings[lbb_thirdParty_status]"]',function(){
		var gdpr_plugin = jQuery('#gdpr-plugin').val();
		if(jQuery(this).val() == 'yes' && gdpr_plugin == 'notactive'){
			jQuery('.gdpr-library-msg').show();
			jQuery('input[name="lbb_gdpr_settings[lbb_thirdParty_status]"][value="no"]').prop('checked', true);
		}else{
			jQuery('.gdpr-library-msg').hide();
		}
		
	});

	jQuery(document).on('change','input[name="lbb_livechat_options"]',function() {
		if(jQuery(this).val() == 'firebase_based'){

			lbbConfirmationDialog(
				"", 
				"Please note: if you switch from LBB mode to the Google Firebase Mode, while you can still view the past conversations (created using LBB mode), you won't able to respond to the ones created via the LBB mode. You can setup the firebase mode for livechat and all new chats will be via Firebase mode.", 
				"Yes", 
				"No").then(function(isConfirm) {
				if (isConfirm) {
					jQuery('.lbb-firebase-options').show('slow');
					jQuery('.livechat-feature').hide('slow');
					jQuery('.firebase-feature').show('slow');
				}else{
					jQuery('input[name="lbb_livechat_options"][value="ajax_based"]').prop('checked', true);
				}
			});

			
		}else{
			jQuery('.lbb-firebase-options').hide('slow');
			jQuery('.livechat-feature').show('slow');
			jQuery('.firebase-feature').hide('slow');
		}
	});
	jQuery(document).on('change','#search_custom_posts',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.lbb-custom-post-type').show();
		}else{
			jQuery('.lbb-custom-post-type').hide();
		}
	});

	jQuery(document).on('change','input[name="lbb_gdpr_settings[lbb_term_checkbox]"]',function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-accept-terms-outer').show('slow');
		}else{
			jQuery('.lbb-accept-terms-outer').hide('slow');
		}
	});

	jQuery(document).on('change','input[name="lbb_contactform_settings[contactform_personaldata]"]',function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-contact-info').show('slow');
		}else{
			jQuery('.lbb-contact-info').hide('slow');
		}
	});

	jQuery(document).on('change','input[name="lbb_general_settings[agent_response]"]',function() {
		console.log(jQuery(this).val());
		if(jQuery(this).val() == 'yes'){
			jQuery('.agent-response-yes-outer').show();
		}else{
			jQuery('.agent-response-yes-outer').hide();
		}
	});

	jQuery(document).on('change','input[name="lbb_general_settings[audio_options]"]',function() {
		var audioElements = jQuery('audio[controls]');
        audioElements.each(function() {
            this.pause();
        });
	});


	jQuery(document).on('change', 'input[name="lbb_contactform_settings[contactform_name]"]', function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-chat-user-info-name-outer').show();
		}else{
			jQuery('.lbb-chat-user-info-name-outer').hide();
		}
	});

	jQuery(document).on('change', 'input[name="lbb_contactform_settings[contactform_phone]"]', function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-chat-user-info-phone-outer').show();
		}else{
			jQuery('.lbb-chat-user-info-phone-outer').hide();
		}
	});

	jQuery(document).on('change', 'input[name="lbb_contactform_settings[contactform_personaldata]"]', function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-consent-processing').show();
		}else{
			jQuery('.lbb-consent-processing').hide();
		}
	});

	/*jQuery(document).on('change', 'input[name="lbb_contactform_settings[contactform_require_consent]"]', function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-consent-processing').show();
		}else{
			jQuery('.lbb-consent-processing').hide();
		}
	});*/

	jQuery(document).on('input', '#contact_form_title', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-chat-user-info-form-title').text(get_val);
	});

	jQuery(document).on('input', '.lbb_contact_form_description', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-chat-user-info-form-description').text(get_val);
	});

	jQuery(document).on('input', '#contact_form_email', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-chat-user-info-email').text(get_val);
	});

	jQuery(document).on('input', '#contact_form_phone', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-chat-user-info-phone').text(get_val);
	});
	jQuery(document).on('input', '#contact_form_name', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-chat-user-info-full-name').text(get_val);
	});
	jQuery(document).on('input', '#contact_form_personal_data', function() {
		var get_val = jQuery(this).val();
		jQuery('#privacy_label').text(get_val);
	});

	jQuery(document).on('input', '#contact_form_submit', function() {
		var get_val = jQuery(this).val();
		jQuery('.lbb-submit-btn').text(get_val);
	});

	jQuery(document).on('click', '#lbb-save-outcomes', function(e) {
		e.preventDefault();
		console.log('rff');
		if(jQuery('#lbb_outcomes_name').val() == ''){
			jQuery('.empty-tagname-error').show();
			return false;
		}
		var lbb_outcomes_description = tinymce.get('lbb_outcomes_description').getContent();
		var lbb_outcomes_name = jQuery('#lbb_outcomes_name').val();
		var lbb_chatflow_id = jQuery('#lbb_chatflow_id').val();
		var lbb_outcomes_id = jQuery('#lbb_outcomes_id').val();
		var lbb_outcome_image_upload = jQuery('#lbb_outcome_image_upload').val();
		jQuery('.empty-tagname-error').hide();
		jQuery('#lbb-save-outcomes').text('Saving...');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_outcomes_data',
				'lbb_outcomes_id': lbb_outcomes_id,
				'lbb_chatflow_id': lbb_chatflow_id,
				'lbb_outcomes_name': lbb_outcomes_name,
				'lbb_outcomes_description': lbb_outcomes_description,
				'lbb_outcome_image_upload': lbb_outcome_image_upload,
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-outcomes').text('Save');
				jQuery('#lbb_label').val('');	
				jQuery('#lbb_name').val('');	
				jQuery('#lbb_field_type').val('textbox');	
				jQuery('#lbb_required_field').val('N');	
				load_outcomes_data();
				jQuery('.lbb-outcomes-popup').removeClass('expanded');
			}
		});
	});

	jQuery(document).on('click', '.lbb-audio-controls', function() {
		console.log('eded');
		var audio = jQuery(this)[0]; // Get the clicked audio element

            // Pause all other audio elements
            jQuery('.myAudio').not(this).each(function() {
                this.pause();
            });

            // Toggle play/pause for the clicked audio element
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
	});

	jQuery(document).on('click', '.iconInner', function(e) {
	    jQuery('#lbb-app').show();
	});

	jQuery(document).on('click', 'input[name="lbb_email_notification_status"]', function(){
		if(jQuery(this).val() == "no"){
			jQuery('.lbb-user-options').hide('slow');
		}else{
			jQuery('.lbb-user-options').show('slow');
		}
	});

	jQuery(document).on('click', 'input[name="lbb_email_admin_notification_status"]', function(){
		if(jQuery(this).val() == "no"){
			jQuery('.lbb-admin-options').hide('slow');
		}else{
			jQuery('.lbb-admin-options').show('slow');
		}
	});

	jQuery(document).on('change', 'input[name="lbb_general_settings[lbb_emoji_enable]"]', function(){
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-emoji-section').show('slow');
		}else{
			jQuery('.lbb-emoji-section').hide('slow');
		}
	});

	jQuery("#save-settings-data").submit(function(e) {
		e.preventDefault();
		jQuery('#lbb-save-firebase-configuration-up').trigger('click');
		jQuery('#lbb-save-settings-btn').text('Saving...');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_notification_setting_data',
				'settings': jQuery('#save-settings-data').serialize(),
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-settings-btn').text('Saved Successfully');
				setTimeout(function(){
					jQuery('#lbb-save-settings-btn').text('Save');
				}, 1000);
			}
		});
	});

	jQuery("#save-email-notifications-data").submit(function(e) {
		e.preventDefault();
		jQuery('#lbb-save-settings-btn').text('Saving...');
		var lbb_user_email_body = tinymce.get('lbb_user_email_body').getContent();
		var lbb_admin_email_body = tinymce.get('lbb_admin_email_body').getContent();
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_email_notifications_data',
				'lbb_user_email_body': lbb_user_email_body,
				'lbb_admin_email_body': lbb_admin_email_body,
				'settings': jQuery('#save-email-notifications-data').serializeArray(),
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-settings-btn').text('Saved Successfully');
				setTimeout(function(){
					jQuery('#lbb-save-settings-btn').text('Save');
				}, 1000);
			}
		});
	});

	jQuery(document).on('click','.preview-header-btn',function() {
		var header_title = jQuery('#lbb_pdf_header_title').html();
		var logo_img = jQuery('#lbb_pdf_logo_upload').val();
		var background_color = jQuery('#lbb_pdf_header').val();
		jQuery('.preview-header-section').css('background-color', background_color);
		jQuery('.logo-section img').attr('src', logo_img);
		jQuery('.header-title').html(header_title)
		jQuery('.preview-header-section').show();
	});

	jQuery(document).on('click','.preview-footer-btn',function() {
		var footer_text = jQuery('#lbb_pdf_footer_content').html();
		jQuery('.pdf-footer-text').html(footer_text);
		var footer_bg = jQuery('#lbb_pdf_footer').val();
		jQuery('.preview-footer-section').css('background-color', footer_bg);
		jQuery('.preview-footer-section').show();
	});

	jQuery('.header-close').on('click', function(){
  		jQuery('.preview-header-section').hide();
   	});

   	jQuery('.footer-close').on('click', function(){
		jQuery('.preview-footer-section').hide();
	});

	jQuery(document).on('click','#lbb-create-new-outcomes',function() {
    	jQuery('#lbb_outcomes_id').val('');
    	jQuery('#lbb_outcomes_name').val('');
    	tinymce.get("lbb_outcomes_description").setContent('');
    	jQuery('.lbb-outcomes-popup').addClass('expanded');
    	jQuery('#save-outcomes-data .lbb-image-upload-container-with-preview').addClass('lbb-no-img');
		jQuery('#save-outcomes-data #lbb_outcome_image_upload').val('');
		jQuery('#save-outcomes-data .lbb-preview-image').attr('src','');
    	lbb_content_text_tiny_mce_editor();
    });

    jQuery("#save-pdf-header-footer-data").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery('#lbb-save-pdf-settings-btn').text('Saving...');
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_save_pdf_headerfooter_settings_data',
				'settings': jQuery('#save-pdf-header-footer-data').serialize(),
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('#lbb-save-pdf-settings-btn').text('Save');
				
			}
		});
	});

    jQuery(document).on('click','.lbb-outcomes-edit-btn',function() {
		var outcomes_id = jQuery(this).attr('data-id');
		console.log(outcomes_id);
		load_outcome_data_popup(outcomes_id);
    });

    jQuery(document).on('click','.delete-outcomes',function() {
    	var outcomes_id = jQuery(this).attr('data-id');
    	var tr_this = jQuery(this)
    		
    	lbbConfirmationDialog(
			"Are you sure?", 
			"", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				lbbShowLoader();
				jQuery.ajax({
					type: 'POST',
					url: lbb_ajax.ajaxurl,
					data: {
							'outcomes_id': outcomes_id,
							'action': 'delete_outcomes_data',
						},
					success: function (response) {
						lbbHideLoader();
						response = JSON.parse(response);
						if(response.success){
							tr_this.closest('tr').remove();
						}
					}
				});
			}
		});

    	
    });

	jQuery("#firebase-configuration").submit(function(e) {
		e.preventDefault();

		var jsonString = jQuery('textarea[name="firebase_app_configuration"]').val();

		if(jQuery("input[name='lbb_livechat_options']:checked").val() == 'firebase_based'){
			try {
	            var jsonData = JSON.parse(jsonString);
	        } catch (e) {
	            swal('Error parsing JSON:', e.message);
	        	return false;
	        }

	        jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_close_livechat_ajax_requests',
			},
			success: function (response) {
			}
		});
		}
        


		/*if(jQuery('input[name="lbb_admin_firebase_id"]').val() == ''){
			swal('Please enter user UUID');
			return false;
		}

		if(jQuery('input[name="firebase_app_configuration"]').val() == ''){
			swal('Please enter App Details');
			return false;
		}

		if(jQuery('input[name="firebase_db_configuration"]').val() == ''){
			swal('Please enter DB Details');
			return false;
		}*/
		
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#firebase-configuration').serialize(),
			success: function (response) {
				lbbHideLoader();
				swal('Saved Successfully');
			}
		});
	});

	jQuery("#aiassistant-configuration").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#aiassistant-configuration').serialize(),
			success: function (response) {
				lbbHideLoader()
				swal('Saved Successfully');
			}
		});
	});

	jQuery("#contactform-configuration").submit(function(e) {
		e.preventDefault();
		jQuery('.lbb-personal-data').hide();
		var contactform_personaldata = jQuery('input[name="lbb_contactform_settings[contactform_personaldata]"]:checked').val();
		if(contactform_personaldata == 'yes' && jQuery('input[name="lbb_contactform_settings[contact_form_personal_data]"]').val() == ''){
			jQuery('.lbb-personal-data').show();
			return false;
		}

		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#contactform-configuration').serialize(),
			success: function (response) {
				lbbHideLoader();
				swal('Saved Successfully');
			}
		});
	});

	jQuery("#fuzzysearch-configuration").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#fuzzysearch-configuration').serialize(),
			success: function (response) {
				lbbHideLoader();
				swal('Saved Successfully');
			}
		});
	});

	jQuery("#gdpr-configuration").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		/*var lbb_gdpr_ipaddress = jQuery('input[name="lbb_gdpr_settings[lbb_gdpr_ipaddress]"]:checked').val();
		var lbb_google_font_enable = jQuery('input[name="lbb_gdpr_settings[lbb_google_font_enable]"]:checked').val();
		var lbb_term_checkbox = jQuery('input[name="lbb_gdpr_settings[lbb_term_checkbox]"]:checked').val();
		var lbb_accept_terms = jQuery('input[name="lbb_gdpr_settings[lbb_accept_terms]"]:checked').val();
		var accept_terms = tinyMCE.get('lbb_accept_terms_title').getContent();*/
		
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			/*data: {
				'action': 'save_gdpr_data',
				'lbb_gdpr_ipaddress': lbb_gdpr_ipaddress,
				'lbb_google_font_enable': lbb_google_font_enable,
				'lbb_term_checkbox': lbb_term_checkbox,
				'lbb_accept_terms': lbb_accept_terms,
				'accept_terms': accept_terms,
			},*/
			data: jQuery('#gdpr-configuration').serialize(),
			success: function (response) {
				lbbHideLoader();
				swal('Saved Successfully');
			}
		});
	});
});