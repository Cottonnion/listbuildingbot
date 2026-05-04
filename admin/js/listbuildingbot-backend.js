var responses = [];
var selectedValues = [];

function lbbOpenPopup(html){
	jQuery('#propwrap').addClass('itson');
	jQuery('#propwrap').addClass('expanded');
	jQuery('#properties').html(html);
	jQuery('body').addClass('lbb-popup-opened');
}

function lbbEmbedCodeChatflow(chatflow_id = 0){
	var embedCode = "<div class='slb-inline-app'><script type='text/javascript' src='"+site_url+"/?embed=true&chat_id="+chatflow_id+"'></script></div>";
	jQuery('#lbb-embedd-shortcode').addClass('expanded');
	jQuery('#copyable_chatflow_embed_shortcode').text(embedCode); 
	//jQuery('.embed-code-copy').attr('data-quiz-id',quiz_id);
	//jQuery('.embed-code-copy').html('<i class="fa fa-files-o" aria-hidden="true"></i> Copy');
	}


function lbbIsValidUrl(url) {
 	var urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;
 	return url.match(urlRegex) !== null;
}


function preview_minimized_style(){
	var get_checked_val = jQuery('input[name="lbb_meta[minimized_type]"]:checked').val();
	if(get_checked_val == 'image'){
		var chatbotImage = jQuery("#maximized_chatbot_image").val();
		if(chatbotImage){
			jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-image');
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-icon');
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-video');
			jQuery('.lbb-example-widget-background-media-img').attr('src', chatbotImage);
		}else{
			swal("Please Upload Image");
			jQuery('.lbb-header-tabs-part ul li:eq(1)').trigger('click');
			return false;
		}
	}else if(get_checked_val == 'icon'){
		var chatbotIcon = jQuery("#maximized_chatbot_icon").val();
		if(chatbotIcon){
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-image');
			jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-icon');
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-video');
			jQuery('.lbb-chat-icon-inner img').attr('src', chatbotIcon);
			jQuery('.lbb-chat-icon-container .lbb-chat-icon-inner').addClass('lbb-img-show-img');
			jQuery('.lbb-chat-icon-container .lbb-chat-icon-inner').removeClass('lbb-img-show-svg');
		}else{
			jQuery('.lbb-chat-icon-container .lbb-chat-icon-inner').removeClass('lbb-img-show-img');
			jQuery('.lbb-chat-icon-container .lbb-chat-icon-inner').addClass('lbb-img-show-svg');
			//swal("Please Upload Icon");
			//jQuery('.lbb-header-tabs-part ul li:eq(3)').trigger('click');
			//return false;
		}
	}else{
		var chatbotVideo = jQuery("#maximized_chatbot_video").val();
		if(chatbotVideo){
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-image');
			jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-icon');
			jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-video');
			jQuery('#lbb-livechat-video-player source').attr('src', chatbotVideo);
			jQuery("#lbb-livechat-video-player")[0].load();
		}else{
			swal("Please Upload Video");
			jQuery('.lbb-header-tabs-part ul li:eq(1)').trigger('click');
			return false;
		}
	}
}

function lbbShowLoader(text=""){
	jQuery('.lbb-loading-mian').addClass('lbb-active');
	jQuery('.lbb-loader-content').text(text);
}

function lbbHideLoader(){
	jQuery('.lbb-loading-mian').removeClass('lbb-active');
}

function lbbClosePopup(){
	jQuery('#propwrap').removeClass('itson');
	jQuery('#propwrap').removeClass('expanded');
	lbbTinyMceRemove('#properties');
	jQuery('#properties').html('');
	jQuery('#properties').removeClass('is-fullmode');
	jQuery('body').removeClass('lbb-popup-opened');
}

function lbbCloseGPopup(id){
	jQuery('#'+id+'-propwrap').removeClass('itson');
	jQuery('#'+id+'-propwrap').removeClass('expanded');
	jQuery('#'+id+'-properties').html('');
	jQuery('#'+id+'-properties').removeClass('is-fullmode');
	jQuery('body').removeClass('lbb-popup-opened');
}

/*function save_form_settings(){
	jQuery(".lbb-form-settings").submit(function(e) {
		e.preventDefault();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_form_settings_data',
				'settings': jQuery('.lbb-form-settings').serialize(),
			},
			success: function () {
				
			}
		});
	});
}*/




function lbb_load_chatflow_styles(){
	var get_chatflow_id = jQuery('input[name="chatflow_id"]').val();
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'lbb_load_chatflow_styles',
			'chatflow_id': get_chatflow_id
				},
		success: function (response) {
			response = JSON.parse(response);	
			if(response){
				var px_values = ['heading-font-size', 'sub-heading-font-size', 'content-font-size', 'answer-button-font-size', 'button-border-radius', 'answer-btn-border-radius', 'icon-padding', 'icon-btn-size', 'answer-btn-font-size', 'sub-heading-text-color']
				jQuery.each(response, function(key, value) {
					var show_px = "";
					if (px_values.includes(key)) {
					    show_px = "px";
					}
					jQuery("body").get(0).style.setProperty("--lbb-chat-"+key, value+show_px);
				});

			}
		}
	});
}

function lbb_load_global_styles(){
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'lbb_load_global_styles'
				},
		success: function (response) {
			response = JSON.parse(response);	
			if(response){
				var px_values = ['heading-font-size', 'sub-heading-font-size', 'content-font-size', 'answer-button-font-size', 'button-border-radius', 'answer-btn-border-radius', 'icon-padding', 'icon-btn-size', 'answer-btn-font-size', 'sub-heading-text-color'];
				jQuery.each(response, function(key, value) {
					var show_px = "";
					if (px_values.includes(key)) {
					    show_px = "px";
					}
					jQuery("body").get(0).style.setProperty("--lbb-chat-"+key, value+show_px);
				});

			}
		}
	});
}



function save_automation_data(){
	jQuery(".form-automation").submit(function(e) {
		e.preventDefault();
	    var automation_type = jQuery('input[name="automation-type"]').val();
	    jQuery('.lbb-error').remove();
		if(automation_type == 'activecampaign'){
			var api_url = jQuery('#lbb-api-url').val();
			if(api_url == ""){
				jQuery('#lbb-api-url').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API URL</span>');
				return false;
			}

			var api_key = jQuery('#lbb-api-key').val();
			if(api_key == ""){
				jQuery('#lbb-api-key').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API Key</span>');
				return false;
			}
		}else if(automation_type == 'aweber' || automation_type == "mailchimp" || automation_type == "convertkit" || automation_type == "sendinblue" || automation_type == "getresponse" || automation_type == "mailerlite" || automation_type == "moosend" || automation_type == "vbout" || automation_type == "klaviyo" || automation_type == "acumbamail" || automation_type == "hubspot"){
			var api_key = jQuery('#lbb-api-key').val();
			if(api_key == ""){
				jQuery('#lbb-api-key').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API Key</span>');
				return false;
			}
		}else if(automation_type == 'drip'){
			var client_id = jQuery('#lbb-api-client-id').val();
			if(client_id == ""){
				jQuery('#lbb-api-client-id').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter Client ID</span>');
				return false;
			}

			var api_token = jQuery('#lbb-api-token').val();
			if(api_token == ""){
				jQuery('#lbb-api-token').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API Token</span>');
				return false;
			}
		}else if(automation_type == 'sendfox'){
			var api_token = jQuery('#lbb-api-token').val();
			if(api_token == ""){
				jQuery('#lbb-api-token').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API Token</span>');
				return false;
			}
		}else if(automation_type == 'webhook'){
			var enter_url = jQuery('#lbb-enter-url').val();
			if(enter_url == ""){
				jQuery('#lbb-enter-url').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter URL</span>');
				return false;
			}
		}else if(automation_type == 'gohighlevel'){
			var enter_url = jQuery('#lbb-enter-url').val();
			if(enter_url == ""){
				jQuery('#lbb-enter-url').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter URL</span>');
				return false;
			}
		}else if(automation_type == 'kartra'){
			var api_key = jQuery('#lbb-api-key').val();
			if(api_key == ""){
				jQuery('#lbb-api-key').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter API Key</span>');
				return false;
			}

			var client_id = jQuery('#lbb-api-client-id').val();
			if(client_id == ""){
				jQuery('#lbb-api-client-id').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter Client ID</span>');
				return false;
			}

			var password = jQuery('#lbb-api-password').val();
			if(password == ""){
				jQuery('#lbb-api-password').closest('.lbb-form-group').append('<span class="lbb-error">PLease enter Password</span>');
				return false;
			}
		}

		jQuery('.lbb-save-automation-btn').text('Saving...');
		var dataArray = {};
	 	var formData = jQuery(this).serializeArray();
	    jQuery.each(formData, function (index, value) {
	        var data_name = formData[index].name;
	        var data_value = formData[index].value;
	        if (data_value !== "") {
	            dataArray[data_name] = data_value;
	        }
	    });


		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_automation_data',
				'automation_type': automation_type,
				'chatflow_id': chatflow_id,
				'dataArray': dataArray
			},
			success: function (response) {
				jQuery('.lbb-save-automation-btn').text('Save');
				jQuery('.lbb-close').trigger('click');
				jQuery('.lbb-automation-outer li').each(function(){
					if(jQuery(this).find('.add-more-automation').attr('data-automation-name') == automation_type){
						if(automation_type == 'webhook' || automation_type == 'gohighlevel'){

						}else{
							jQuery(this).find('.add-more-automation').show();
						}
						jQuery(this).find('.click-to-connect-btn').text('Connected');
					}
				});
			}
		});
	});
}

function lbb_copy_to_clipboard(obj) {
	jQuery(obj).html('<i class="fa fa-files-o" aria-hidden="true"></i> Copied');
	var elementId = jQuery(obj).attr("data-id");
	var aux = document.createElement("input");
	aux.setAttribute("value", document.getElementById(elementId).innerHTML);
	document.body.appendChild(aux);
	aux.select();
	document.execCommand("copy"); 
	document.body.removeChild(aux);
	setTimeout(function() {
		jQuery(obj).html('<i class="fa fa-files-o" aria-hidden="true"></i> Copy');
	}, 2000);
}

function lbbCopyToClipboardInput(text) {

   var textArea = document.createElement( "textarea" );
   textArea.value = text;
   document.body.appendChild( textArea );       
   textArea.select();

   try {
      var successful = document.execCommand( 'copy' );
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Copying text command was ' + msg);
   } catch (err) {
      console.log('Oops, unable to copy',err);
   }    
   document.body.removeChild( textArea );
}

function lbb_autoresponder_test_webhook(obj){
	var sqb_text = jQuery(obj).text();
	var webhook  = jQuery("input[name='webhook_url']").val();
	if(webhook == ''){
		swal("Please enter URL.");
		return false;
	}

	jQuery(obj).text('Please wait...');
	jQuery.post(ajaxurl, {
		action: 'lbb_autoresponder_test_webhook',
		webhook: webhook,
		chatflow_id: chatflow_id,
	}, function(response) {
		response = JSON.parse(response);	
		if(response.success){
			jQuery(obj).html('<span style="color: green;" class="testzap_msg">Connection established </span>');
			setTimeout(function(){
				jQuery(obj).html('Click Here to Test Connection');
			}, 1000);
			return false;
		}
		jQuery(obj).text('Connection fail');
	});
}

function lbb_shortcode_copy_to_clipboard(obj){
	jQuery(obj).text('Copied');
	var elementId = jQuery(obj).attr("data-id");
	var aux = document.createElement("input");
	var externalScript = jQuery('#copyable_chatflow_embed_shortcode').text();
	aux.setAttribute("value", externalScript);
	document.body.appendChild(aux);
	aux.select();
	aux.focus({preventScroll: true});
	document.execCommand("copy"); 
	document.body.removeChild(aux);
}

function lbb_import_export_hide(){
	jQuery('.lbb_create_quiz_using_import').hide('slow');
	jQuery('.lbb_select_main_option').show('slow');
}

function lbb_prebuilt_hide(){
	jQuery('.lbb_select_prebuilt').hide('slow');
	jQuery('.lbb_select_main_option').show('slow');
} 

function lbb_ai_hide(){
	jQuery('.lbb_create_quiz_using_ai').hide('slow');
	jQuery('.lbb_select_main_option').show('slow');
	jQuery('body').removeClass('lbb-ai-option-selected');
} 

function lbb_main_section_types_hide(){
	jQuery('.lbb_select_main_option').hide('slow');
	jQuery('.lbb-datatable').show('slow');
	jQuery('body').removeClass('lbb-add-new-popup-open');
}

function lbb_scratch_section_types_hide(){
	jQuery('.lbb_select_scratch_options').hide('slow');
	jQuery('.lbb_select_main_option').show('slow');
}

/*function lbb_customBot_section_types_hide(){
	jQuery('.lbb_select_customBot_options').hide('slow');
	jQuery('.lbb_select_main_option').show('slow');
}*/

jQuery(document).ready(function(){
	//lbb_load_global_styles();
	lbb_content_text_tiny_mce_editor();
	save_automation_data();
	lbb_datatable();

	const fileInput = document.getElementById("file");
    const selectedFile = document.getElementById("selected-file");

    if(jQuery('#tabs-nav li:eq(0)').hasClass('active')){
    	jQuery('.lbb-back-chatflow').hide();
    }

    if(fileInput != null && selectedFile != null){
    	fileInput.addEventListener("change", function () {
	        selectedFile.textContent = this.files[0].name;
	    });
	}

	var tab = lbbUrlVars()["tab"];
	if(tab == 'create_bot_funnel'){
		setTimeout(function(){
			jQuery('#lbb-create-new-chatflow').trigger('click');
		},500);
	}

	jQuery(document).on('click','#lbb_hide_connection_line',function(){
		if(jQuery(this).prop('checked') == true){
			jQuery('.lbb-main-drawflow-wrapper').addClass('lbb-hide-node');
		}else{
			jQuery('.lbb-main-drawflow-wrapper').removeClass('lbb-hide-node');
		}
	});

	/*jQuery(document).on('mouseenter mouseleave', '.lbb-switch', function() {
    	var isChecked = jQuery(this).find('.lbb-status').prop('checked');
    	var statusText = isChecked ? 'Currently Active' : 'Currently Deactivated';
    	jQuery(this).find('.lbb-status').siblings('.status-text').text(statusText);
  	});*/

	jQuery(document).on('click', '.lbb-status', function(){
		var chatflow_id = jQuery(this).attr('data-id');
		var status = "N";
		if(jQuery(this).prop('checked') == true){
			var status = 'Y';
		}
		if(chatflow_id){
			lbbShowLoader();
	   	jQuery.ajax({
	          type: "POST",
	          url: lbb_ajax.ajaxurl,
	          data: { 
	          	action: 'lbb_update_status',
	          	chatflow_id: chatflow_id,
	          	status: status,
	          },
	          success: function(data) {
	          	lbbHideLoader();
	          }
	      });
		}
	});

	jQuery(document).on('click', '.lbb-delete-contact', function(){
		var conversation_id = jQuery(this).attr('data-conversationId');
		var tr_this = jQuery(this)
		swal({
	      title: "Are you sure you want to delete this contact? All the conversations related to this contact will be deleted as well and you won't be able to recover it.",
	      icon: "warning",
	      buttons: [
	        'Cancel',
	        'Yes, delete it'
	      ],
	      dangerMode: true,
	    }).then(function(isConfirm) {
	      	if (isConfirm) {
	      		lbbShowLoader();
		      	jQuery.ajax({
	                type: "POST",
	                url: lbb_ajax.ajaxurl,
	                data: { 
	                	action: 'lbb_delete_conversation',
	                	conversation_id: conversation_id 
	                },
	                success: function(data) {
	                	lbbHideLoader();
						tr_this.closest('tr').remove();
	                	
	                }
	                	
	            });
	      	}
      	});
	});

	jQuery(document).on('click','.lbb-delete-conversation',function() {
		var conversation_id = jQuery(this).closest('.user-admin-list-left-side').attr('data-id');

		var parent = jQuery(this).closest('.user-admin-list-left-side');
		swal({
	      title: "Are you sure you want to delete this conversation? You won't be able to recover it.",
	      icon: "warning",
	      buttons: [
	        'No, cancel it!',
	        'Yes, I am sure!'
	      ],
	      dangerMode: true,
	    }).then(function(isConfirm) {
	      	if (isConfirm) {
	      		lbbShowLoader();
		      	jQuery.ajax({
	                type: "POST",
	                url: lbb_ajax.ajaxurl,
	                data: { 
	                	action: 'lbb_delete_conversation',
	                	conversation_id: conversation_id 
	                },
	                success: function(data) {
	                	lbbHideLoader();
	                	parent.remove();
	                }
	                	
	            });
	      	}
      	});
	});

	jQuery(document).on('click','.create-bot-funnel',function() {
		var activeLi = jQuery('.lbb-menu-list .lbb-active');
		var activeIndex = activeLi.index();
		var site_url = jQuery('#website_url').val();
		if(activeIndex == 0){
			jQuery('#lbb-create-new-chatflow').trigger('click');
		}else{
			window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&tab=create_bot_funnel";
		}
	});

	jQuery( '.lbbCopyToClipboardInput' ).click( function(){
	    var clipboardText = "";
	    var data_id = jQuery(this).attr('data-id');
	    clipboardText = jQuery( '#'+data_id ).val(); 
	    jQuery(this).html('<i class="fa fa-files-o" aria-hidden="true"></i> Copied');

	    setTimeout(function() {
			jQuery('.lbbCopyToClipboardInput').html('<i class="fa fa-files-o" aria-hidden="true"></i> Copy');
		}, 2000);
	    lbbCopyToClipboardInput( clipboardText );
 	});

    jQuery(".lbb-add-new-content-btn").on("click", function() {
    	jQuery('.lbb-ai-document-main-wrapper').addClass('lbb-show-add-content')
    });

    jQuery("#lbb-get-links").on("click", function() {
    	lbbShowLoader();
        var siteURL = jQuery("#lbb-site-url").val();
        if (siteURL) {
            jQuery.ajax({
                type: "POST",
                url: lbb_ajax.ajaxurl,
                data: { 
                	action: 'lbb_fetching_url',
                	url: siteURL 
                },
                success: function(data) {
                	lbbHideLoader();
                	if (data == 0){
                    	alert('No links found. Please check the URL');
                    	return;
                    }else{
                    	if (data) {
							response = JSON.parse(data);
							
							jQuery.each(response, function(i, item) {
								//console.log(item);
								if (!document.getElementById('lbb-url-list-'+i)) {
									 jQuery("#lbb-links-list").append(item);
								}
							});

						}
                    }
                   
                    jQuery("#lbb-links-container").show();
                    
                }
            });
        }
    });
    
	
	jQuery(document).on('click','input[name="lbb_meta[lbb_enable_search]"]',function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.lbb-enable-search-outer').show();
		}else{
			jQuery('.lbb-enable-search-outer').hide();
		}
	});

	

	jQuery(document).on('click','input[name="lbb_meta[lbb_made_with]"]',function() {
		var get_val = jQuery(this).val();
		if(get_val == 'yes'){
			jQuery('.made-with-options').show('slow');
		}else{
			jQuery('.made-with-options').hide('slow');
		}
	});

	jQuery(document).on('click','input[name="lbb_meta[lbb_display_bot_callout]"]',function() {
		var get_val = jQuery(this).val();
		if(get_val == 'yes'){
			jQuery('.lbb-callout-section').show('slow');
		}else{
			jQuery('.lbb-callout-section').hide('slow');
		}
	});

	jQuery(document).on('click','.lbb-clone-question',function() {
		var question_id = jQuery(this).attr('data-question_id');
		//lbbSaveQuestion(question_id);
		lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'question_id': question_id,
				'action': 'lbb_clone_question'
			},
			success: function (response) {
				lbbHideLoader();

				if(response.success){
					var data = response.data;
					var question = data.post;
					var question_id = question.id;
					var html = data.html;
					var output_node = data.output_node;
					if(question.type == 'single' || question.type == 'message'){
						lbbAddNode(question_id,1,output_node,html);
					}else{
						lbbAddNode(question_id,1,1,html);
					}
					
					lbb_questions.push(question);
					var questionEditform = jQuery('#questionEditForm').html();
					jQuery('#properties').html(questionEditform);
					jQuery('#lbb_question_id').val(question_id);
					//lbbEditQuestionForm(question_id);
					
					scrollToElementByClass('node-question-'+question_id);
				}
			}
		});
	});


	jQuery(document).mouseup(function(e) 
	{
	    var container = jQuery(".lbb-mergetag-content-wrapper");

	    // if the target of the click isn't the container nor a descendant of the container
	    if (!container.is(e.target) && container.has(e.target).length === 0) 
	    {
	        container.removeClass('lbb-show-personalize');
	    }
	});

	jQuery('#lbb_chatbot_description').on('input', function(){
    	var text = jQuery(this).val();
    	jQuery('.lbb-admin-bio').html(text);
    });

    jQuery('#lbb_admin_name').on('input', function(){
    	var text = jQuery(this).val();
    	jQuery('.lbb-header').html(text);
    });
    
	

	jQuery("#where_to_show").select2().on('change', function (e) {
    var selected_url = '';
    var getID = jQuery(this).select2('data');
    if (getID.length !== 0) {
        getID.forEach(function (item) {
            selected_url += '<li><a target="_blank" href="' + site_url + '' + item.text + '">' + site_url + '' + item.text + '</a></li>';
        });
        jQuery('.lbb-page-list-wrapper ul').html(selected_url);
        jQuery('.shortcode-show-for-maximized').show();
    } else {
        // No items selected, remove the existing list
        jQuery('.lbb-page-list-wrapper ul').empty();
        jQuery('.shortcode-show-for-maximized').hide();
    }
});


	jQuery(document).on('click', '.create_tag', function(){
		var get_title = jQuery(this).parents('.create-tag-section').find('.save_tag').val();
		if(get_title == ''){
			swal('Please enter Tag name');
			return false;
		}
		var tags_id = jQuery(this).parents('.lbb-tags-outer-section').find('select').attr('id');
		var parent_obj = jQuery(this).parents('.lbb-tags-outer-section');
		lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'lbb_tags_name': get_title,
				'lbb_tags_description': '',
				'action': 'save_tags_data'
			},
			success: function (response) {
				lbbHideLoader();
				jQuery('.save_tag').val('');
				response = JSON.parse(response);
				if(response){
					parent_obj.find('select').remove();
					parent_obj.find('.tags-section-outer').append('<select id="'+tags_id+'" name="tag[]" class="lbb-input-field js-select2" multiple>'+response.load_tags_data+'</select>');
					jQuery('.select-tag-section').html('<select id="'+tags_id+'" name="tag[]" class="lbb-input-field js-select2" multiple>'+response.load_tags_data+'</select>');

					lbb_tags.push(response.tagsdata);
					//var qid = jQuery('#lbb_question_id').val();
					//lbbEditQuestionForm(qid);
					jQuery(".js-select2").select2({
						placeholder: "Choose a filter",
						minimumResultsForSearch: -1
					});	
					jQuery('.tag-save-message').show();
					setTimeout(function(){
						jQuery('.tag-save-message').hide();
					},1000);
					
				}
			}
		});
	});

	jQuery(document).on('click', '#lbb-create-new-chatflow', function(){
		jQuery('.lbb-datatable').hide('slow');
		jQuery('.lbb_select_main_option').show('slow');
		jQuery('body').addClass('lbb-add-new-popup-open');
	});

	jQuery(document).on('click', '.lbb-template-main-selection-item', function(){
		var template_value = jQuery(this).attr('data-value');
		if(template_value == 'use_builtin'){
			jQuery('.lbb_select_main_option').hide('slow');
			jQuery('.lbb_select_prebuilt').show('slow');
		}else if(template_value == 'use_import_export'){
			jQuery('.lbb_select_main_option').hide('slow');
			jQuery('.lbb_create_quiz_using_import').show('slow');
		}else if(template_value == 'custom_bot'){
			var site_url = jQuery('#site_url').val();
			window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=create&type=ai_assistant";
			//jQuery('.lbb_select_main_option').hide('slow');
			//jQuery('.lbb_select_customBot_options').show('slow');
			//jQuery('body').addClass('lbb-ai-option-selected');
		}else{
			jQuery('.lbb_select_scratch_options').show('slow');
			jQuery('.lbb_select_main_option').hide('slow');
			//var site_url = jQuery('#site_url').val();
			//window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=create";
		}
	});

	jQuery(document).on('click', '.lbb-template-scratch-selection-item', function(){
		var template_value = jQuery(this).attr('data-value');
		if(template_value == 'create'){
			var site_url = jQuery('#site_url').val();
			window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=create";
		}else if(template_value == 'use_ai'){
			jQuery('.lbb_select_scratch_options').hide('slow');
			jQuery('.lbb_create_quiz_using_ai').show('slow');
		}
	});

	jQuery(document).on('click', '.lbb-template-customBot-selection-item', function(){
		var template_value = jQuery(this).attr('data-value');
		if(template_value == 'use_ai'){
			var site_url = jQuery('#site_url').val();
			window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=create&type=ai_assistant";
		}else if(template_value == 'custom_bot'){
			//jQuery('.lbb_select_main_option').hide('slow');
			//jQuery('.lbb_create_quiz_using_import').show('slow');
		}
	});

	jQuery(document).on('click', '.show-otherConversations li', function(){
		var conversation_id = jQuery(this).attr('data-conversationid');
		jQuery('.lbb-user-profile-popup').removeClass('expanded');
		jQuery('.user-admin-list-left-side[data-id="'+conversation_id+'"]').get(0).click();
	});

	jQuery(document).on('click', '.lbb-update-pages', function(){
		var checkedValues = jQuery('input[name="pages_checkbox"]:checked').map(function() {
        return this.value;
      }).get().join(',');

      var chatflow_id = jQuery('input[name="pages_chatflow_id"]').val();
		lbbShowLoader();
	    	jQuery.ajax({
				type: 'post',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'lbb_update_selected_url',
					'chatflow_id': chatflow_id,
					'checkedValues': checkedValues
				},
				success: function (response) {
					lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						var parts = checkedValues.split(',');

					    if (parts.length >= 3) {
					      var resultArray = parts.slice(0, 3);
					      resultArray.push('...');
					      var resultString = resultArray.join(', ');
					    } else {
					      var resultString = parts.join(', ');
					    }
						 jQuery('tr[data-chatflow-id="'+chatflow_id+'"] .td-selected-url').html('<a class="lbb-show-pages-chatflow" href="javascript:void(0)">'+resultString+'</a>');
						 jQuery('.lbb-selectedpages-listing .lbb-delete-icon').trigger('click');
					}
					
				}
		});
	});
	jQuery(document).on('click', '.lbb-show-pages-chatflow', function(){
		var chatflow_id = jQuery(this).parents('tr').attr('data-chatflow-id');
		lbbShowLoader();
	    	jQuery.ajax({
				type: 'post',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'lbb_load_all_pages',
					'chatflow_id': chatflow_id
				},
				success: function (response) {
					lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						jQuery('.lbb-selectedpages-listing').addClass('expanded');
						jQuery('.all-selected-pages-url').html(response);
					}
					
				}
		});
	});
	jQuery(document).on('click', '#lbb-select-pages-chatflow', function(){
		lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_load_selected_pages'
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					jQuery('.lbb-selectedpages-listing-popup .selected-pages-url').html(response);
					jQuery('.lbb-selectedpages-listing-popup').addClass('expanded');

					jQuery('.lbb-selected-pages').DataTable({ 
					 	order: [],
						bLengthChange: false,
						pageLength : 20,
						scrollX: true,
						autoWidth: false,
						bAutoWidth: false,
						autoWidth: false,
						language: {
							search: "",
							searchPlaceholder: "Search..."
						},
						"fnInitComplete": function() {
							 
						}
					});
				}
			}
		});

		
	});

	jQuery(document).on('change', 'input[name="lbb_meta[live_chat_idle_time_enable]"]', function(){
		if(jQuery(this).val() == 'no'){
			jQuery('.idle-time-option').show('slow');
		}else{
			jQuery('.idle-time-option').hide('slow');

		}
	});

	jQuery(document).on('change', 'input[name="lbb_meta[lbb_show_results]"]', function(){
		if(jQuery(this).val() == 'no'){
			jQuery('.how-many-outer').hide('slow');
		}else{
			jQuery('.how-many-outer').show('slow');

		}
	});

	jQuery('#view-user-profile').on('click', function(){

		var user_id = jQuery('#conversation-chat-left .user-admin-list-left-side.active-chat').attr('data-id');

		lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'user_id': user_id,
				'action': 'lbb_load_user_data'
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					jQuery('.show-name').html(response.name);
					jQuery('.show-email').html(response.email);
					jQuery('.show-otherConversations').html(response.otherContactHtml);
					jQuery('.lbb-user-profile-popup').addClass('expanded');

				}
			}
		});
		
	});

	jQuery('input[name="lbb_meta[lbb_common_font_family]"]').on('change',function(){
		jQuery("body").get(0).style.setProperty("--lbb-chat-heading-font-family", this.value);
		var font_url = 'https://fonts.googleapis.com/css2?family='+this.value;
							var stylesheet = jQuery("<link>", {
								rel: "stylesheet",
								type: "text/css",
								href: font_url
							});
							stylesheet.appendTo("head");
	});

	jQuery(document).on('click', '.lbb-personalize-close', function(){
		jQuery('.lbb-mergetag-content-wrapper').removeClass('lbb-show-personalize');
	});

	jQuery(document).on('click', '.lbb-show-merge-tags', function(){
		jQuery(this).parents('.lbb-personalize-options').find('.lbb-mergetag-content-wrapper').toggleClass('lbb-show-personalize');
	});
	
	jQuery(document).on('click', '.lbb-clone-btn', function(){
		var post_id = jQuery(this).attr('data-id');
		lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'post_id': post_id,
				'action': 'lbb_duplicate_post'
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					location.reload();
				}
			}
		});
	});

	jQuery(document).on('click', 'input[name="lbb_meta[_lbb_email_notification_status]"]', function(){
		if(jQuery(this).val() == "no"){
			jQuery('.lbb-user-options').hide('slow');
		}else{
			jQuery('.lbb-user-options').show('slow');
		}
	});

	jQuery(document).on('click', 'input[name="lbb_meta[_lbb_email_admin_notification_status]"]', function(){
		if(jQuery(this).val() == "no"){
			jQuery('.lbb-admin-options').hide('slow');
		}else{
			jQuery('.lbb-admin-options').show('slow');
		}
	});

	jQuery(document).on('click', 'input[name="lbb_meta[_lbb_email_admin_livechat_notification_status]"]', function(){
		if(jQuery(this).val() == "no"){
			jQuery('.lbb-admin-livechat-options').hide('slow');
		}else{
			jQuery('.lbb-admin-livechat-options').show('slow');
		}
	});

	jQuery(document).on('click', '.global-check-style', function(){
		var checkbox = jQuery(this);
	    var accordion = checkbox.closest(".lbb-chatbot-preview-form-wrapper .lbb-accordion-item");

		if(jQuery(this).prop('checked') == true){
			jQuery(this).closest('.lbb-switch').find('input').val('1');
			var globalClass = jQuery(this).attr('data-global-class');
    		jQuery('.lbb-chat-start').addClass(globalClass);
			lbb_disable_accordion(accordion);
		}else{
			var globalClass = jQuery(this).attr('data-global-class');
	    	jQuery('.lbb-chat-start').removeClass(globalClass);
			jQuery(this).closest('.lbb-switch').find('input').val('0');
			lbb_enable_accordion(accordion);
		}

	});

	jQuery(document).on('click', '.iconInner', function(e) {
	    // jQuery('#lbb-app').show();
	    jQuery('.lbb-preview-chatflow').trigger('click');
	});
	jQuery(document).on('click', '.lbb-close', function(e) {
	    jQuery('#lbb-app').hide();
	});

	jQuery(document).on('click','.lbb-delete-answer',function() {

		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var question_id = $this.attr('data-question_id');
		var answer_id = $this.attr('data-id');
		lbbConfirmationDialog(
			"Are you sure?", 
			"If you choose to delete this question, it will be removed. However, please note that saving the chatbot is required.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				//var ques_id = jQuery("#lbb_question_id").val();
				//var id = $this.attr('data-key');
				deleteChoice(question_id,answer_id);
			}
		});
		
	});

	/*Accordion Start*/
	jQuery(document).on('click', '.lbb-accordion-header', function(e) {
		
			
		var thisCheckbox = jQuery(this).find('.global-check-style');
		if (thisCheckbox.prop("checked")){
			return;
		}
        const $accordionItem = jQuery(this).parent();
        const $accordionContent = $accordionItem.find('.lbb-accordion-content');
        const $allAccordionContent = jQuery('.lbb-accordion-content');

        if ($accordionContent.is(':visible')) {
            $accordionContent.slideUp();
        } else {
            $allAccordionContent.slideUp();
            $accordionContent.slideDown();
        }
    });	

	/*Accordion End*/


	/*Contacts Pagination Start */

	var encodedEmail = lbbUrlVars()["email"];
	decodedEmail = "";
	if(encodedEmail){
		var decodedEmail = decodeURIComponent(encodedEmail);
	}

	if (jQuery('#contactsTable').length){
		lbbShowLoader();
		jQuery('#contactsTable').DataTable({
	        'processing': true,
	        'serverSide': true,
	        'scrollX': true,
	        'language': {
				search: "",
				searchPlaceholder: "Search..."
			},
	        'serverMethod': 'post',
	        'ajax': {
	            'url': ajaxurl+'?action=lbb_show_pagination',
	            'data': function (d) {
			            d.searchValue = decodedEmail; // Get the search value from the DataTables search input
			        }
	        },
	        'columns': json_data_field,
	        'initComplete': function () {
	        	lbbHideLoader();
		     	customfield_auto_hide_show();
		    }
	    });
	}

	jQuery(document).on('change', 'input[name="lbb_meta[personalized_pdf_option]"]', function(){
		if(jQuery(this).val() == 'yes'){
			jQuery('.personalized-pdf-wrapper').show();
		}else{
			jQuery('.personalized-pdf-wrapper').hide();
		}
	});
	
	
	/*Contacts Pagination End */

	

jQuery(document).on('click','#lbb-pdf-mapping',function() {
    	lbbShowLoader();
    	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'chatflow_id': chatflow_id,
				'action': 'chatflow_mapping_data'
			},
			success: function (response) {
				lbbHideLoader()
				jQuery('.lbb-mapping-popup').addClass('expanded');
				response = JSON.parse(response);
				jQuery('.lbb-popup-pdf-wrapper').html(response.pdf_builder);
				jQuery('.lbb-popup-outcome-wrapper').html(response.chatflow_data);
			}
		});
    });



	jQuery(document).on('change', 'input[name="lbb_meta[notify_chat]"]', function(){
		if(jQuery(this).val() == 'email_admin'){
			jQuery('.livechat-admin-emails').show('slow');
		}else{
			jQuery('.livechat-admin-emails').hide('slow');
		}
	});

	//save_form_settings();

	jQuery(document).on('change', 'select[name="lbb_meta[lbb_chat_alignment]"]', function(){
		if(jQuery(this).val() == 'left'){
			jQuery('.right-spacing-outer').hide();
			jQuery('.left-spacing-outer').show();
		}else{
			jQuery('.right-spacing-outer').show();
			jQuery('.left-spacing-outer').hide();
		}
	});

	jQuery(document).on('change', 'input[name="lbb_meta[livechat_status]"]', function(){

		var data = lbb_questions;
		var liveChat = 0
		// Loop through the array of objects
		jQuery.each(data, function(index, item) {
			var itemType = item.type;

			// Perform actions based on the 'type' property
			switch (itemType) {
				case "single":
					
					jQuery.each(item.choices, function(choiceIndex, choice) {
						var livechatStatus = choice.livechat;
						if (livechatStatus === 1) {
							liveChat = 1;
							return false;
						}
					});
					break;
			}
		});

		if(liveChat && jQuery(this).val() == 'no'){

			lbbAlertDialog(
				"Disable Live Chat", 
				"It seems that you have live chat enabled by answer choces, so you can't able disabled", 
				"Ok");
			jQuery('#livechat_status_yes').prop('checked', true);
			
		}else{
			if(jQuery(this).val() == 'yes'){
				jQuery('.lbb-livechat-section').show('slow');
				jQuery('.liveChatIdleTime').show('slow');
			}else{
				jQuery('.lbb-livechat-section').hide('slow');
				jQuery('.liveChatIdleTime').hide('slow');
			}
		}

	})

	jQuery(document).on('click', '.chatflow-style', function(){
		alert('eded');
	});

	jQuery(document).on('click', 'input[name="lbb_meta[minimized_type]"]', function(){
		var get_val = jQuery(this).val();
		if(get_val == "icon"){
			jQuery('.change-color-text').text('Icon Background Color:');
			jQuery('.lbb-chatboat-img').hide('slow');
			jQuery('.lbb-chatboat-video').hide('slow');
			jQuery('.lbb-chatboat-icon').show('slow');
			jQuery('.expanded-options-outer').hide();
			if(jQuery('#maximized_chatbot_icon').val() != ''){
				preview_minimized_style();
			}else{
				jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-icon');
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-image');
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-video');
			}
			jQuery('.chat-hide-for-icon').show('slow');
			jQuery('.chat-hide-for-image').hide('slow');
		}else if(get_val == "image"){
			jQuery('.change-color-text').text('Icon Background Color:');
			jQuery('.chat-hide-for-image').show('slow');
			jQuery('.chat-hide-for-icon').hide('slow');
			jQuery('.lbb-chatboat-img').show('slow');
			jQuery('.lbb-chatboat-video').hide('slow');
			jQuery('.lbb-chatboat-icon').hide('slow');
			jQuery('.expanded-options-outer').show();
			if(jQuery('#maximized_chatbot_image').val() != ''){
				preview_minimized_style();
			}else{
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-icon');
				jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-image');
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-video');
			}
		}else{
			jQuery('.change-color-text').text('Border Color:');
			jQuery('.chat-hide-for-image').show('slow');
			jQuery('.chat-hide-for-icon').hide('slow');
			jQuery('.lbb-chatboat-img').hide('slow');
			jQuery('.lbb-chatboat-video').show('slow');
			jQuery('.lbb-chatboat-icon').hide('slow');
			jQuery('.expanded-options-outer').show();
			if(jQuery('#maximized_chatbot_video').val() != ''){
				preview_minimized_style();
			}else{
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-icon');
				jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-image');
				jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-video');
			}
		}
	});

	jQuery(document).on('click', 'input[name="lbb_meta[lbb_style_chatbot_background]"]', function(){
		var get_val = jQuery(this).val();
		if(get_val == "image"){
			jQuery('.lbb-style-color').hide('slow');
			jQuery('.lbb-style-video').hide('slow');
			jQuery('.lbb-style-image').show('slow');

			jQuery('.lbb-chat-start').addClass('lbb-template-image');
			jQuery('.lbb-chat-start').removeClass('lbb-template-video');
			jQuery('.lbb-chat-start').removeClass('lbb-template-color');

		}else if(get_val == "video"){
			jQuery('.lbb-style-color').hide('slow');
			jQuery('.lbb-style-video').show('slow');
			jQuery('.lbb-style-image').hide('slow');

			jQuery('.lbb-chat-start').addClass('lbb-template-video');
			jQuery('.lbb-chat-start').removeClass('lbb-template-image');
			jQuery('.lbb-chat-start').removeClass('lbb-template-color');
		}else{
			jQuery('.lbb-style-color').show('slow');
			jQuery('.lbb-style-video').hide('slow');
			jQuery('.lbb-style-image').hide('slow');

			jQuery('.lbb-chat-start').addClass('lbb-template-color');
			jQuery('.lbb-chat-start').removeClass('lbb-template-image');
			jQuery('.lbb-chat-start').removeClass('lbb-template-video');
		}
	});

	jQuery(document).on('click', 'input[name="lbb_meta[lbb_enable_search]"]', function(){
		if(jQuery(this).val() == 'yes'){
			jQuery('.knowledge-button-outer').show();
		}else{
			jQuery('.knowledge-button-outer').hide();
		}
	});

	jQuery(document).on('click', 'input[name="lbb_meta[how_to_show]"]', function(){
		var get_val = jQuery(this).val();
		if(get_val == "minimized"){
			jQuery('.shortcode-show-for-inpage').hide();
			jQuery('.shortcode-show-for-maximized').show();
			jQuery('.inpage-to-popup-message').show('slow');
			jQuery('.show-hide-heading-subheading').show();
			jQuery('.minimized-wrapper-show-hide').show('slow');
			//jQuery('.lbb-chat-start').removeClass('lbb-chattype-inline inline');

			jQuery('input[name="lbb_meta[lbb_container_padding]"]').val(15);
			jQuery(".lbb-container-padding").slider("value", 15);
			jQuery("body").get(0).style.setProperty("--lbb-chat-container-padding", '15px');

			jQuery('input[name="lbb_meta[lbb_container_width]"]').val(450);
			jQuery(".lbb-container-width").slider("value", 450);
			jQuery("body").get(0).style.setProperty("--lbb-chat-container-width", '450px');

			jQuery('.hide-for-inpage').hide('slow');
			jQuery('.lbb-where-to-show').show('slow');
			jQuery('.show_hide_icon_styles').show();
			jQuery('.when-to-show').show('slow');
			jQuery('.lbb-chat-start').removeClass('lbb-chattype-inline');
			jQuery('.inpage-message').hide('slow');
			if(jQuery('input[name="lbb_meta[how_to_show]"]').val() == 'icon'){
				jQuery('.expanded-options-outer').hide();
			}else{
				jQuery('.expanded-options-outer').show();
			}
			jQuery('.callout-options').show();
		}else{
			jQuery('.lbb-popup-timer').hide('slow');
			jQuery('.shortcode-show-for-maximized').hide();
			jQuery('.shortcode-show-for-inpage').show();
			jQuery('.show-hide-heading-subheading').hide();
			jQuery('.inpage-to-popup-message').hide('slow');
			jQuery('.expanded-options-outer').hide();
			jQuery('.minimized-wrapper-show-hide').hide('slow');
			jQuery('.lbb-chat-start').addClass('lbb-chattype-inline inline');
			jQuery('.show_hide_icon_styles').hide();
			jQuery('input[name="lbb_meta[lbb_container_padding]"]').val(25);
			jQuery(".lbb-container-padding").slider("value", 25);
			jQuery("body").get(0).style.setProperty("--lbb-chat-container-padding", '25px');

			jQuery('input[name="lbb_meta[lbb_container_width]"]').val(700);
			jQuery(".lbb-container-width").slider("value", 700);
			jQuery("body").get(0).style.setProperty("--lbb-chat-container-width", '700px');

			jQuery('.hide-for-inpage').show('slow');
			jQuery('.lbb-where-to-show').hide('slow');

			jQuery('.when-to-show').hide('slow');

			jQuery('#lbb-chat-main-wrapper .lbb-chat-icon-inner').addClass('lbb-img-show-svg');
			jQuery('#lbb-chat-main-wrapper .lbb-chat-icon-inner').removeClass('lbb-img-show-img');

			jQuery('#lbb-chat-main-wrapper .lbb-chat-icon-container').removeClass('lbb-widget-type-image');
			jQuery('#lbb-chat-main-wrapper .lbb-chat-icon-container').removeClass('lbb-widget-type-video');
			jQuery('#lbb-chat-main-wrapper .lbb-chat-icon-container').addClass('lbb-widget-type-icon');
			jQuery('.inpage-message').show('slow');
			jQuery('.callout-options').hide();
		}

		/*if(get_val == "minimized"){
			jQuery('.lbb-chat-box-container').hide('slow');
			jQuery('.lbb-chat-icon-container').show('slow');
			jQuery('.inpage-message').hide('slow');
			
			jQuery('.lbb-icon-styles').show('slow');
			jQuery('.upload-icon-image').show('slow');
			jQuery('.maximized-options').hide('slow');
		}else if(get_val == "maximized"){
			jQuery('.upload-icon-image').hide('slow');
			jQuery('.maximized-options').show('slow');
		}else{
			if(get_val == 'inline'){
				jQuery('.inpage-message').show('slow');
				jQuery('.hide-for-inpage').hide('slow');
			}else{
				jQuery('.inpage-message').hide('slow');
				jQuery('.hide-for-inpage').show('slow');
			}
			jQuery('.lbb-chat-box-container').show('slow');
			jQuery('.lbb-chat-icon-container').hide('slow');
			jQuery('.lbb-icon-styles').hide('slow');
			jQuery('.upload-icon-image').hide('slow');
			jQuery('.maximized-options').hide('slow');
		}*/
	});

	jQuery(document).on('click', '#lbb-save-automation-listing', function(){
		jQuery('#lbb-save-automation-listing').text('Saving...');
		var automation_type = jQuery('input[name="automation_type"]').val();
		var list = jQuery('#sqb_select_list').val();
		var automation_data = []; 
		if(automation_type == 'dap'){
			var object = {
			    	'action' : 'add',
			        'action_type' : '',
			        'list':list
			        }
			    automation_data.push(object);

		}else{
			jQuery('.autoresponder_table_class tbody tr').each(function(i){
			    var action = jQuery(this).find('.action').attr('data-action');
			    var action_type = jQuery(this).find('.action_type').text();
			    var list = jQuery(this).find('.list_value').attr('data-list-value');
			    var object = {
			    	'action' : action,
			        'action_type' : action_type,
			        'list':list
			        }
			    automation_data.push(object);
			});
			
		}

		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_automation_listings_data',
				'chatflow_id': chatflow_id,
				'automation_type': automation_type,
				'automation_data': automation_data,
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				jQuery('#lbb-save-automation-listing').text('Saved');
				setTimeout(function(){
					jQuery('#lbb-save-automation-listing').text('Save');
				},1000);
			}
		});
	});


    jQuery('.lbb-model-open-btn').click(function() {
        var lbb_model_selector = jQuery(this).data('lbbmodel')
        jQuery(lbb_model_selector).css("display", "flex");
    });

    jQuery(".lbb-close").click(function() {
        jQuery('.lbb-modal-container').css("display", "none");

        /*For Automation platform form fields*/
        jQuery('input[name="lbb_api_url"]').val('');
        jQuery('input[name="lbb_api_key"]').val('');
        jQuery('input[name="lbb_api_secret_key"]').val('');
        jQuery('input[name="lbb_api_client_id"]').val('');
        jQuery('input[name="lbb_api_token"]').val('');
        jQuery('input[name="lbb_api_password"]').val('');
        jQuery('input[name="lbb_auth_token"]').val('');
        jQuery('input[name="lbb_enter_url"]').val('');
    });

    jQuery('.click-to-connect-btn').on('click', function(){
    	// var platform_val = jQuery(this).closest('li').find('input[name="add_user_in_your_email_platform"]').val().toLowerCase();
    	var platform_val = jQuery(this).attr('data-api-name');
    	var label_name = jQuery(this).attr('data-label-name');
    	jQuery('.platform-name').html(label_name);
    	var api_url = jQuery(this).attr('data-api-url');
    	var authorize_url = jQuery(this).attr('data-authorize-url');
    	var api_key = jQuery(this).attr('data-api-key');
    	var api_secret = jQuery(this).attr('data-api-secret');
    	var api_client_id = jQuery(this).attr('data-api-client-id');
    	var api_token = jQuery(this).attr('data-api-token');
    	var api_password = jQuery(this).attr('data-api-password');
    	var auth_token = jQuery(this).attr('data-auth-token');
    	var enter_url = jQuery(this).attr('data-enter-url');
    	if(api_url == 'Y'){
    		jQuery('.show-hide-api-url').show();
    	}else{
    		jQuery('.show-hide-api-url').hide();
    	}
    	if(authorize_url == 'Y'){
    		jQuery('.show-hide-authorize-url').show();
    	}else{
    		jQuery('.show-hide-authorize-url').hide();
    	}

    	if(api_key == 'Y'){
    		jQuery('.show-hide-api-key').show();
    	}else{
    		jQuery('.show-hide-api-key').hide();
    	}

    	if(api_secret == 'Y'){
    		jQuery('.show-hide-api-secret-key').show();
    	}else{
    		jQuery('.show-hide-api-secret-key').hide();
    	}

    	if(api_client_id == 'Y'){
    		jQuery('.show-hide-api-client-id').show();
    	}else{
    		jQuery('.show-hide-api-client-id').hide();
    	}

    	if(api_token == 'Y'){
    		jQuery('.show-hide-api-token').show();
    	}else{
    		jQuery('.show-hide-api-token').hide();
    	}

    	if(api_password == 'Y'){
    		jQuery('.show-hide-api-password').show();
    	}else{
    		jQuery('.show-hide-api-password').hide();
    	}
    	if(auth_token == 'Y'){
    		jQuery('.show-hide-auth-token').show();
    	}else{
    		jQuery('.show-hide-auth-token').hide();
    	}

    	if(enter_url == 'Y'){
    		jQuery('.show-hide-enter-url').show();
    	}else{
    		jQuery('.show-hide-enter-url').hide();
    	}
    	var platform_name = platform_val.toLowerCase();
    	jQuery('input[name="automation-type"]').val(platform_name);
    	lbbShowLoader();
    	jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'load_automation_data',
				'chatflow_id': chatflow_id,
				'platform_name': platform_name,
			},
			success: function (response) {
				lbbHideLoader();
				jsonData = JSON.parse(response);
				if(jsonData){
					jQuery.each(jsonData , function(key, value) {
						jQuery('input[name="'+key+'"]').val(value);
					});
				}
			}
		});
    });

    jQuery('input[name="lbb_meta[_lbb_automation_status][]"]').on('change',function(){
		var automation_value = jQuery(this).val();
    	if(jQuery(this).prop('checked')){
    		if(jQuery(this).hasClass('need-plugin')){
    			jQuery(this).prop('checked', false);
    			var automation_name = jQuery(this).parents('li').attr('data-automation-name');
    			swal('You need to have '+automation_name+' plugin activated to use this integration');
    			return false;
    		}

    		if(jQuery(this).hasClass('plugin-active')){
    			jQuery(this).parents('li').find('.add-more-automation').show();
    		}
    		if(automation_value != 'dap'){
				jQuery(this).closest('li').find('.check_email_has').show();
    		}
			if(jQuery(this).closest('li').find('.check_email_has').hasClass('btn-success')){
				jQuery(this).closest('li').find('.autoresonder_details_fields_outer').show();
			}else{
				jQuery(this).closest('li').find('.autoresonder_details_fields_outer').hide();
			}

			var first_automation_value = automation_value;
			first_automation_value = automation_value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
			    return letter.toUpperCase();
			});

			jQuery('.automation-listing').append('<li data-automation-name="'+automation_value+'">'+first_automation_value+'</li>');
		}else{
			jQuery('.automation-listing li[data-automation-name="'+automation_value+'"]').remove();
			jQuery(this).closest('li').find('.check_email_has').hide();
			jQuery(this).closest('li').find('.autoresonder_details_fields_outer').hide();
		}
    });

    jQuery(document).on('click','.add-more-automation',function() {
    	jQuery('.autoresponder_table_class tbody tr').remove();
    	jQuery('.show-add-automation-listing').hide();
    	var automation_name = jQuery(this).attr('data-automation-name');
    	jQuery('input[name="automation_type"]').val(automation_name);
    	var obj = jQuery(this);
    	lbbShowLoader();
    	jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
					'automation_name': automation_name,
					'chatflow_id': chatflow_id,
					'action': 'load_automation_listing',
				},
			success: function (response) {
					response = JSON.parse(response);
					
					lbbHideLoader();
					jQuery('.lbb-automation-listing-popup').addClass('expanded');
					if(response){
						jQuery('.autoresonder_details_fields_outer .autoresponder_listing_data').html(response.show_listing);

						if(response.automation_listing){
							jQuery('.show-add-automation-listing').show();
							jQuery('.autoresponder_table_class tbody').html(response.automation_listing);
						}
						
					}
			}
		});
    });


    jQuery(document).on('click','.lbb-accordion-heading',function() {
	    var $content = jQuery(this).next(".lbb-accordion-content");
	    if ($content.is(":visible")) {
	        $content.slideUp(200);
	        jQuery(this).removeClass("lbb-accordion-active");
	    } else {
	        jQuery(".lbb-accordion-content").slideUp(200);
	        jQuery(".lbb-accordion-heading").removeClass("accordion-active");
	        $content.slideDown(200);
	        jQuery(this).addClass("lbb-accordion-active");
	    }

	   /* var $accordionItem = jQuery(this).closest("li");
        
        // Toggle the "active" class to expand/collapse
        $accordionItem.toggleClass("lbb-active");

        // Toggle the "hidden" class to show/hide content
        $accordionItem.find(".lbb-input-wrapper, .lbb-form-group").toggleClass("lbb-hidden");
        
        // Close other accordion items
        jQuery(".lb-list-answer li").not($accordionItem).removeClass("lbb-active");
        jQuery(".lb-list-answer li").not($accordionItem).find(".lbb-form-group").addClass("lbb-hidden");*/
	});


    jQuery(document).on('click','.automation_action_btn',function() {
		var autoresponder_name = jQuery(this).attr('data-action');
		var obj = jQuery(this);
		var new_table_tr = '';
		
		var sqb_dt = new Date();
		var sqb_time_class = sqb_dt.getHours() + "_" + sqb_dt.getMinutes() + "_" + sqb_dt.getSeconds()+'_'+Math.floor(Math.random() * 10000); ;
		
		
		var parent_select = jQuery('.automation_save_outer_div');
		//if(autoresponder_name == 'activecampaign'){
			var  sqb_auto_action = jQuery(parent_select).find('select.sqb_auto_action').val();
			var  sqb_auto_type = jQuery(parent_select).find('select.sqb_auto_type').val();
			var  sqb_select_list = jQuery(parent_select).find('select#sqb_select_list').val();
			var  sqb_select_list_text = jQuery(parent_select).find('select#sqb_select_list option:selected').text();
			
			//var  sqb_tags1 = jQuery(parent_select).find('input[name="sqb_tags1"]').val();
			if(sqb_auto_action == '' || sqb_auto_action == 0  ){
				swal("","Plese select action","");
				return false;
			}
			if(sqb_auto_type == '' || sqb_auto_type == 0){
				swal("","Plese select type","");
				return false;
			}
			
			if(sqb_select_list == ''){
				swal("","Plese select list","");
				return false;
			}
			
			var data_info = {
				
				'autoresponder_name':'activecampaign',
				'sqb_auto_action':sqb_auto_action,
				'sqb_auto_type':sqb_auto_type,
				'sqb_select_list':sqb_select_list,
				//'automation_tags_name':sqb_tags1,
				//'sqb_select_list_text':sqb_select_list_text,
				};
			
			data_info = JSON.stringify(data_info);			
			
			new_table_tr  = '<tr class="add_new_automation_tr '+sqb_time_class+'">';
			new_table_tr += "<td class='action' data-action='"+sqb_auto_action+"'>"+sqb_auto_action+"<input type='hidden' name='data_info' value='"+data_info+"'></td>";
			if(sqb_auto_type){
				new_table_tr += '<td class="action_type">'+sqb_auto_type+'</td>';
			}
			new_table_tr += '<td class="list_value" data-list-value="'+sqb_select_list+'">'+sqb_select_list_text+'</td>';
			
			
			new_table_tr += '<td class="text-center delete_autoresponder_td"><span class="dashicons dashicons-trash sqb_autoresponder_delete_tabl_tr" aria-hidden="true" delete_id="'+sqb_time_class+'" ></span></td>';
			new_table_tr += '</tr>'		
			
			jQuery('.lbb-automation-listing-popup').find('tbody').append(new_table_tr);
			jQuery('.lbb-automation-listing-popup').find('.show-add-automation-listing').show();
			//jQuery('.autoresponder_table_details.'+autoresponder_name).find('tbody').append(new_table_tr);
		//}
    });

    jQuery(document).on('click','.sqb_autoresponder_delete_tabl_tr',function() {
		jQuery(this).closest('tr').remove();
    });
    jQuery(document).on('click','.lbb-customfield-edit-btn',function() {
		var customfield_id = jQuery(this).attr('data-id');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
					'customfield_id': customfield_id,
					'action': 'load_customfield_data_by_id',
				},
			success: function (response) {
					lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						jQuery('.lbb-customfield-popup').addClass('expanded');
						jQuery('#lbb_name').attr("readonly", true);
						jQuery('#lbb_customfield_id').val(response.id);
						jQuery('#lbb_label').val(response.label);
						jQuery('#lbb_name').val(response.name);
						jQuery('#lbb_field_type').val(response.field_type);
						jQuery('#lbb_required_field').val(response.required);
					}
			}
		});
    });

    jQuery(document).on('click','.lbb-tags-edit-btn',function() {
		var tags_id = jQuery(this).attr('data-id');
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
					'tags_id': tags_id,
					'action': 'load_tags_data_by_id',
				},
			success: function (response) {
				lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						jQuery('.lbb-tags-popup').addClass('expanded');
						jQuery('#lbb_tags_id').val(response.id);
						jQuery('#lbb_tags_description').val(response.description);
						jQuery('#lbb_tags_name').val(response.name);
					}
			}
		});
    });

    jQuery(document).on('click','#lbb-create-new-customfield',function() {
    	jQuery('#lbb_label').val('');	
		jQuery('#lbb_name').val('');	
		jQuery('#lbb_name').removeAttr('readonly');	
		jQuery('#lbb_field_type').val('textbox');	
		jQuery('#lbb_required_field').val('N');	
    	jQuery('#lbb_customfield_id').val('');
    	jQuery('.lbb-customfield-popup').addClass('expanded');

    });

    jQuery(document).on('click','#lbb-create-new-tags',function() {
    	jQuery('#lbb_tags_id').val('');
    	jQuery('#lbb_tags_name').val('');
    	jQuery('#lbb_tags_description').val('');
    	jQuery('.lbb-tags-popup').addClass('expanded');
    });

    jQuery(document).on('click','.delete-customfield',function() {
    	var customfield_id = jQuery(this).attr('data-id');
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
							'customfield_id': customfield_id,
							'action': 'delete_customfield_data',
						},
					success: function (response) {
							response = JSON.parse(response);
							lbbHideLoader();
							if(response.success){
								tr_this.closest('tr').remove();
							}
					}
				});
			}
		});
    });

    jQuery(document).on('click','.delete-tags',function() {
    	var tags_id = jQuery(this).attr('data-id');
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
							'tags_id': tags_id,
							'action': 'delete_tags_data',
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

	jQuery(document).on('click', '#global-check', function(){
		var get_chatflow_id = jQuery('input[name="chatflow_id"]').val();
		if(jQuery(this).prop('checked') == true){
			jQuery('#global_style_value').val('Y');
			jQuery('.lbb-custom-styles').addClass('lbb-hide');
			lbb_load_global_styles();
			jQuery('.global-style-enable').show('slow');
		}else{
			jQuery('#global_style_value').val('N');
			jQuery('.lbb-custom-styles').removeClass('lbb-hide');
			jQuery('.global-style-enable').hide('slow');
			lbb_load_chatflow_styles(get_chatflow_id);
		}
	});

	jQuery(document).on('click','.lbb-next-chatflow', function(){
        lbbSaveChatflow('next');
		
	});

	jQuery(document).on('click','.lbb-back-chatflow', function(){
		var selected = jQuery('#tabs-nav');
		var back = selected.find('li.active');
    	var backVisible = back.prevAll('li:visible').first();
	    if (backVisible.length > 0) {
	        backVisible.find('a').trigger('click');
	    }
	});
	
	jQuery(document).on('click','.lbb-edit-logic-rule', function(){
		var question_id = jQuery(this).attr('data-questionid');
		var uid = jQuery(this).attr('data-id');
		jQuery(".lbb-edit-question[data-question_id='"+question_id+"']").trigger('click');
		jQuery('#chatflow-branch').trigger('click');
		jQuery(".lbb-condition-edit[data-uid='"+uid+"']").trigger('click');
	});

	jQuery(document).on('change', '#when_to_show', function(){
		var get_val = jQuery(this).val();
		if(get_val == 'certain_time'){
			jQuery('.lbb-show-if-upon_scroll').addClass('lbb-hide');
			jQuery('.lbb-show-if-enter_time').removeClass('lbb-hide');
			jQuery('.when-to-show-summary').html('After certain time');
		}else if(get_val == 'upon_scroll'){
			jQuery('.lbb-show-if-upon_scroll').removeClass('lbb-hide');
			jQuery('.lbb-show-if-enter_time').addClass('lbb-hide');
			jQuery('.when-to-show-summary').html('Upon scroll');
		}else{
			jQuery('.lbb-show-if-upon_scroll').addClass('lbb-hide');
			jQuery('.lbb-show-if-enter_time').addClass('lbb-hide');
			jQuery('.when-to-show-summary').html('When visitors visit the page');
		}
	});

	jQuery(document).on('click', '.delete-chatflow', function(){
		var get_chatflow_id = jQuery(this).attr('data-id');
		var tr_this = jQuery(this);

		lbbConfirmationDialog(
			"Are you sure?", 
			"", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				lbbShowLoader();
				jQuery.ajax({
					type: 'post',
					url: lbb_ajax.ajaxurl,
					data: {
						'chatflow_id': get_chatflow_id,
						'action': 'delete_posttype_chatflow',
					},
					success: function (response) {
						lbbHideLoader();
						response = JSON.parse(response);
						if(response.success){
							tr_this.closest('tr').remove();
							swal("Successfully deleted the bot!"); 
						}
					}
				});
			}
		});
	});

	jQuery(document).on('click', '.export-chatflow', function(){
		var get_chatflow_id = jQuery(this).attr('data-id');
		var tr_this = jQuery(this);
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'chatflow_id': get_chatflow_id,
				'action': 'export_posttype_chatflow',
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					var jsonData  = response.success;
					var jsonString = JSON.stringify(jsonData, null, 2);
					var blob = new Blob([jsonString], { type: 'application/json' });
					var a = document.createElement('a');
					a.href = window.URL.createObjectURL(blob);
					a.download = response.chatflow_name+'.json';
					a.style.display = 'none';
					document.body.appendChild(a);
					a.click();
					document.body.removeChild(a);
				}
			}
		});
	});

	jQuery(document).on('click', 'input[name="lbb_meta[_chatflow_type]"]', function(){
		var chatflow_type = jQuery('#chatflow_type').val();
		var selected_chatflow_type = jQuery(this).val();
		var flag = 0;
		var current_chatflow_type = jQuery(this).val();
		jQuery('.hide-for-trainedai').show();
		if(current_chatflow_type == "livechat" || current_chatflow_type == 'botlivechat'){
			jQuery('.lbb-livechat-image').show();

			/*if(jQuery('#firebase_configuration').val() == 0){
				jQuery('.lbb-firebase-configuration').show();
				jQuery('input[name="lbb_meta[_chatflow_type]"][value="logicbot"]').prop('checked', true);
				return false;
			}*/

			/*lbbShowLoader();
			jQuery.ajax({
				type: 'post',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'lbb_check_firebase',
				},
				success: function (response) {
					lbbHideLoader();
					response = JSON.parse(response);
					if(response == 0){
						jQuery('.lbb-firebase-configuration').show();
						jQuery('input[name="lbb_meta[_chatflow_type]"][value="logicbot"]').prop('checked', true);
						return false;
					}
					
				}
			});*/

			if (Notification.permission !== 'granted') {
		      Notification.requestPermission();
		    }

		}else if(current_chatflow_type == 'trained_ai'){
			jQuery('.hide-for-trainedai').hide();
		}
		if((chatflow_id != 0 || chatflow_id != '')){

			if(((chatflow_type == "botlivechat" || chatflow_type == "logicbot" || chatflow_type == "livechat") && jQuery(this).val() == 'ai_assistant') || ((jQuery(this).val() == "botlivechat" || jQuery(this).val() == "logicbot" || jQuery(this).val() == "livechat") && chatflow_type == 'ai_assistant')){
				flag = 1;
				lbbConfirmationDialog(
					"Are you sure?", 
					" If you switch, you'll lose your current settings.", 
					"Yes, I am sure!", 
					"No, cancel it!").then(function(isConfirm) {
					if (isConfirm) {
						select_chatflow_type(selected_chatflow_type);
					}else{
						jQuery('input[name="lbb_meta[_chatflow_type]"][value="'+chatflow_type+'"]').prop('checked', true);
						flag = 1;
					}
				});
			}


			if((chatflow_type == "logicbot" || chatflow_type == 'botlivechat') && jQuery(this).val() == 'livechat'){
				flag = 1;
				lbbConfirmationDialog(
					"Are you sure?", 
					" If you switch, you'll lose your bot settings.", 
					"Yes, I am sure!", 
					"No, cancel it!").then(function(isConfirm) {
					if (isConfirm) {
						select_chatflow_type(selected_chatflow_type);
					}else{
						jQuery('input[name="lbb_meta[_chatflow_type]"][value="'+chatflow_type+'"]').prop('checked', true);
						flag = 1;
					}
				});
			}
		}
		
		if(flag == 1){
			return false;
		}

		select_chatflow_type(selected_chatflow_type);
	});

	jQuery(document).on('click', '.lbb-how-it-works-title', function(){
		jQuery(this).parents('.lbb-how-it-works').toggleClass("lbb-how-it-works-show");
	});

	jQuery(document).on('click','.lbb-change-tabs',function() {
		if(jQuery('#chatflow-question').hasClass('lbb-active')){
			if(!lbbValidateQuestion()){
				return false;
			}
		}
		if(jQuery('#chatflow-answers').hasClass('lbb-active')){
			if(!lbbValidateAnswer()){
				return false;
			}
		}
		var tab = jQuery(this).attr('data-tab');
		jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
		jQuery('.lbb-popup-'+tab+'-wrapper').css('display', 'block');

		jQuery('.lbb-change-tabs').removeClass('lbb-active');
		jQuery(this).addClass('lbb-active');
		if(tab == 'answer'){
			jQuery('#lbb-single-back').show();
			jQuery('#lbb-single-next').show();
		}else if(tab == 'advancedRule'){
			jQuery('#lbb-single-back').show();
			jQuery('#lbb-single-next').hide();
		}else if(tab == 'answers'){
			jQuery('#lbb-single-back').show();
			jQuery('#lbb-single-next').hide();
		}else{
			jQuery('#lbb-single-back').hide();
			jQuery('#lbb-single-next').show();
		}
		
	});

	jQuery(document).on('click', '.lbb-create-chatflow', function(){
		var chatflow_title = jQuery('.lbb-input-field').val();
		var site_url = jQuery('#site_url').val();
		var chatflow_type = jQuery('input[name="lbb_meta[_chatflow_type]"]:checked').val();
		if(chatflow_title == ''){
			jQuery('.lbb-form-group').append('<span class="title-error">Please Enter Title</span>');
			return false;
		}
		jQuery('.title-error').remove();
		jQuery(this).text('Creating...');
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'chatflow_title': chatflow_title,
				'chatflow_type': chatflow_type,
				'action': 'save_action_data',
			},
			success: function (response) {
				response = JSON.parse(response);
				if(response != 0){
					 window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=edit&id="+response;
				}
			}
		});
	});

	jQuery(document).on('click','.lbb-delete-question', function(){
		var $this = jQuery(this);
		lbbConfirmationDialog(
			"Are you sure?", 
			"If you choose to delete this question, please be aware that the action is irreversible and cannot be undone.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				var question_id = $this.attr('data-question_id');
				lbbRemoveQuestion(question_id);
			}
		});
	});

	jQuery(document).on('click','.lbb-add-new-question', function(){
		lbbAddNewQuestionForm();
	});

	jQuery(document).on('click','.lbb-question-type', function(){
		var type = jQuery(this).attr('data-type');
		lbbAddNewQuestion(type);
	});

	jQuery(document).on('change','#image-object-fit', function(){
		var value = jQuery(this).val();
		jQuery("body").get(0).style.setProperty("--lbb-chat-answer-image-object-fit", value);
	});

	jQuery(document).on('change','#image-object-fit', function(){
		var value = jQuery(this).val();
		jQuery("body").get(0).style.setProperty("--lbb-chat-answer-button-row-column", value);
	});

	jQuery(document).on('click','.lbb-auto-prompt-popup', function(){
		
		jQuery('#autoprompt-propwrap').addClass('itson');
		jQuery('#autoprompt-propwrap').addClass('expanded');

		var promptForm = jQuery('#autoPromtForm').html();
		jQuery('#autoprompt-properties').html(promptForm);
		jQuery('body').addClass('lbb-popup-opened');

	});

	jQuery(document).on('click', '.lbb-update-open-ai-ass', function(){

		lbbShowLoader();
		jQuery.ajax({
			url: lbb_ajax.ajaxurl+'?action=lbb_ass_content_save',
			data: {
				action: 'lbb_ass_content_save',
				chatflow_id: chatflow_id,
				ai_assistant_id: jQuery('#lbb_assistant_id').val(),
				respone_msg: jQuery('#no_response_msg').val(),
				aiassistant_rules: jQuery('#lbb_main_aiassistant_rules').val()
			},
			type: 'POST',
			success: function(response) {
				
				lbbHideLoader();
			}
		});

	});

	jQuery(document).on('change','#prompt-message', function(){
		
		if(jQuery(this).val() == ''){
			jQuery('#lbb-generate-prompt').attr('disabled', 'true');
		}else{
			jQuery('#lbb-generate-prompt').removeAttr('disabled');
		}

	});

	jQuery(document).on('click','#lbb-generate-prompt', function(){
		var val = jQuery('#prompt-message').val();
		lbbGenerateAutoPrompt(val);
	});

	jQuery(document).on('click','#lbb-append-prompt', function(){
		var pText = jQuery('.lbb-auto-prompt-contents').html();
		if(main_prompt_text != ''){
			jQuery('[name="lbb_meta\\[aiassistant_main_prompt\\]"]').val(main_prompt_text);
		}
		lbbCloseGPopup('autoprompt');
	});

	

	jQuery(document).on('click','#close', function(){
		//jQuery('#propwrap').removeClass('itson');
	    //jQuery('#propwrap').removeClass('expanded');
	    jQuery(this).parents('.lbb-popup-main').removeClass('itson');
	    jQuery(this).parents('.lbb-popup-main').removeClass('expanded');
		lbbClosePopup('');
	});

	jQuery(document).on('click','#firebase-close', function(){
		jQuery(this).parents('.lbb-popup-main').removeClass('expanded');
	});

	
	jQuery(document).on('click','.lbb-open-document-static', function(){
	    jQuery('#propwrapfirebase').addClass('expanded');
	});


	jQuery(document).on('click','.lbb-select-template', function(){
		var selected_template = jQuery('input[name="templateSelection"]:checked').val();
		ImportTemplate(selected_template);
	});

	jQuery(document).on('click','.lbb-use-template', function(){
		var selected_template = jQuery(this).attr('data-template');
		ImportTemplate(selected_template);
	});


	jQuery(document).on('click','.lbb-url-listing-delete-icon', function(){
		var thisvar = jQuery(this);
		swal({
	      title: "Are you sure you want to remove?",
	      icon: "warning",
	      buttons: [
	        'No, cancel it!',
	        'Yes, I am sure!'
	      ],
	      dangerMode: true,
	    }).then(function(isConfirm) {
	      if (isConfirm) {
				
				if (thisvar.parents('#lbb-selected-links-list').length){

						file_id = thisvar.attr('data-fileid');
						lbbShowLoader();
	                    jQuery.ajax({
	                    type: 'POST',
			            url: lbb_ajax.ajaxurl,
						data: {
							action: 'lbb_delete_ass_file',
							chatflow_id: chatflow_id,
							file_id: file_id
						},
	                    success: function(response) {
	                        lbbHideLoader();
	                    }
	                });
               }

               thisvar.parents('.lbb-url-listing-item').remove();

			}
		});
	});



	jQuery(document).on('click','.lbb-next-selected-page', function(){
        
		var beforelenthcheckbox = jQuery(".lbb-url-listing-item").not('.lbb-trained').find(".lbb-url-listing-inside input[type='checkbox']:checked").length;
		var beforehastrained = jQuery(".lbb-url-listing-item").hasClass('lbb-trained');
		selectedValues = [];

        jQuery(".lbb-url-listing-item").not('.lbb-trained').find(".lbb-url-listing-inside input[type='checkbox']:checked").each(function () {
        	jQuery(this).parents('.lbb-url-listing-item').addClass('lbb-trained');
            selectedValues.push(jQuery(this).val());
        });

         /*var selectedValues = jQuery(".lbb-url-listing-inside input[type='checkbox']:checked").map(function () {
            return this.value;
        }).get();*/

        if (jQuery(".lbb-url-listing-item").find(".lbb-url-listing-inside input[type='checkbox']:checked").length == 0) {
        	swal('Please select at least one');
	  		return false;
        }else if (beforelenthcheckbox != 0){
        	lbbSendValue(0);
        	jQuery('.lbb-popup-info-wrapper').show();
        }else if (beforehastrained){
        	jQuery('#lbb-link-tab-1').hide();
			jQuery('#lbb-link-tab-2').show();
        }
        
    });

    

    jQuery(document).on('click','.lbb-url-listing-edit-icon', function(){
    	var editbtn = jQuery(this).data('edit');
    	jQuery(editbtn).addClass('active-url-item-update')
    }); 

    jQuery(document).on('click','.lbb-save-exit-text-for-url', function(){
    	var editbtn = jQuery(this).data('edit');
    	jQuery(editbtn).removeClass('active-url-item-update')
    });

    jQuery(document).on('click','.lbb-back-link-page', function(){
    	var backid = jQuery(this).data('backid');
    	jQuery(this).parents('.lbb-sub-tabs-wrapper').hide()
    	jQuery(backid).show()
    });

    jQuery(document).on('click','.lbb-submit-text-content', function(){
    	var page_name = jQuery('#lbb_ai_url_content_title').val();
    	var page_text = jQuery('#lbb_ai_url_content_text').val();
    	var lbb_ai_text_hiden = jQuery('#lbb_ai_text_hiden').val();
    	lbbShowLoader(); 
    	jQuery.ajax({
            type: 'POST',
            url: lbb_ajax.ajaxurl,
			data: {
				action: 'lbb_ai_text_content',
				page_name: page_name,
				page_text: page_text,
				lbb_ai_text_hiden: lbb_ai_text_hiden,
				chatflow_id: chatflow_id
			},
            success: function (response){
            	lbbHideLoader();

				try {
					response = JSON.parse(response);
					if(response){

						if(response.error != undefined){
							swal(response.error.message);
							return false;
						}
					}
				} catch (error) {
					
				}
				
            	/*jQuery('#lbb-link-tab-1').show();
				jQuery('#lbb-link-tab-2').hide();*/
				jQuery('.lbb-thankyou-textbox').show();
				if (!isNaN(response)) {
					jQuery('#lbb_ai_text_hiden').val(response);
				}
				/*jQuery('li.lbb-url-listing-item').addClass('lbb-url-added');*/
            }
    	});

    });

    jQuery(document).on('click','.lbb-submit-selected-page', function(){
    	let dataArray = [];

    	jQuery('li.lbb-url-listing-item').not('.lbb-url-added').find('.lbb_ai_url_title').each(function(i) {
		  dataArray.push({
		    title: jQuery(this).val(),
		    content: jQuery(this).parents('.lbb-edit-content-wrapper').find('.lbb_ai_url_content').val()
		  });
		});
    	//console.log(dataArray);

    	swal({
	      title: "Are you sure you want to send content to Open AI?",
	      text: "You will not be able to edit this content file.",
	      icon: "warning",
	      buttons: [
	        'No, cancel it!',
	        'Yes, I am sure!'
	      ],
	      dangerMode: true,
	    }).then(function(isConfirm) {
	      if (isConfirm) {
	      	lbbShowLoader(); 
	        jQuery.ajax({
	            type: 'POST',
	            url: lbb_ajax.ajaxurl,
				data: {
					action: 'lbb_upload_document_loop',
					dataArray: dataArray,
					chatflow_id: chatflow_id
				},
	            success: function (response){

					response = JSON.parse(response);
					if(response){
						lbbHideLoader();
						if(response.error != undefined){
							swal(response.error.message);
							return false;
						}
						jQuery('#lbb-link-tab-1').show();
						jQuery('#lbb-link-tab-2').hide();
						jQuery('li.lbb-url-listing-item').addClass('lbb-url-added');

					}
	            	
	            }
        	});
	      }
	    })

    });
		
});

function lbbSendValue(index) {
    if (index < selectedValues.length) {
    	lbbShowLoader();
        jQuery.ajax({
            type: 'POST',
            url: lbb_ajax.ajaxurl,
			data: {
				action: 'lbb_fetching_single_url',
				url: selectedValues[index]
			},
            success: function (response) {
            	lbbHideLoader();
            	/*responses.push({
				  title: selectedValues[index],
				  description: response
				});*/

				jQuery('#lbb-selected-links-list').append(response);

            	responses.push(response);

                // Handle the response as needed

                var progress = ((index + 1) / selectedValues.length) * 100;
                lbbUpdateProgress(progress);

                // Proceed to the next value
                lbbSendValue(index + 1);
            },
            error: function (error) {
            	lbbHideLoader();
                // Handle errors if necessary
                console.error(error);

                // Proceed to the next value
                lbbSendValue(index + 1);
            }
        });
    }else{
    	jQuery('.lbb-popup-info-wrapper').hide();


		
		//jQuery('#lbb-selected-links-list').append(accordionHtml);
		jQuery('#lbb-link-tab-1').hide();
		jQuery('#lbb-link-tab-2').show();


    }
}

function lbbUpdateProgress(percentage) {
    jQuery('.lbb-progressbar-caption').text(percentage.toFixed(0) + '%');
}

function lbb_datatable(){
	jQuery('.lbb-table').DataTable({ 
	 	order: [],
		bLengthChange: false,
		pageLength : 20,
		scrollX: true,
		autoWidth: false,
		bAutoWidth: false,
		autoWidth: false,
		language: {
			search: "",
			searchPlaceholder: "Search..."
		},
		"fnInitComplete": function() {
			 
		}
	});
}

function lbb_disable_accordion(accordion) {
    accordion.find(".lbb-accordion-content").slideUp(); // Hide the accordion content
    accordion.addClass("disabled"); // Add a disabled class to the accordion item
  }

  // Function to enable a specific accordion
  function lbb_enable_accordion(accordion) {
    //accordion.find(".lbb-accordion-content").show(); // Show the accordion content
    accordion.removeClass("disabled"); // Remove the disabled class
  }


  function ImportTemplate(template){
  	lbbShowLoader();
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'lbb_import_template',
			'template': template
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);	
			if(response){
				
				window.location.href = response.edit_link;
			}
			//location.reload();
		}
	});
	
}

function lbb_import_chatflow(){
	var input_type = jQuery('#frmjsonImport').find('input[name="json_file"]');
  	var files = jQuery(input_type)[0].files;
  
  	if(files.length < 1 ){
	  	swal('Please select a json file');
	  	return false;
  	}
  	lbbShowLoader();
  	var form = jQuery('#frmjsonImport')[0];
    var varform = new FormData(form);
  	varform.append("action", "lbb_import_json");
  	lbbImportjsonfile(varform);
}

function lbbImportjsonfile(varform) {
	lbbShowLoader();
	jQuery.ajax({
		type: "POST",
		url: lbb_ajax.ajaxurl,
		dataType: "JSON",
		data: varform,
		processData: false,
		contentType: false,
		cache: false,
		success: lbbonSuccesJsonImport,
		crossDomain:true
	});
}

function lbbonSuccesJsonImport(data, status) {
	jQuery('#frmjsonImport').trigger("reset");
	lbbHideLoader();
	//response = JSON.parse(response);	
	var site_url = jQuery('#site_url').val();
	if(data['success']){
		window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=edit&id="+data['success'];
	}else{
		swal('Their is issue with the data');
	}
	//location.reload();
	// swal('',data.message,"success");
	//window.location.replace(jQuery('a#manage_quiz_tab').attr('href'));
}