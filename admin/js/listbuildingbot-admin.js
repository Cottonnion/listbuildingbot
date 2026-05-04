var lbb_editor;
var lbb_questions = {};
var lbb_faqs = [];
var lbb_settings = {};
var chatListenersNew = {};

function scrollToElementById(elementId) {
	const element = document.getElementById(elementId);

	if (element) {
		element.scrollIntoView({
			behavior: 'smooth', // You can use 'auto' for instant scrolling
			block: 'start',     // Scroll to the top of the element
		});
	}
}

function lbb_question_answer_choices(question_id, qindex){

        var choices = [];

        jQuery('.lbb-choice-input').each(function(index, element) {
            var key = jQuery(this).attr('data-key');
            if(key != 0){
            	var value = jQuery(this).val();
				var image = jQuery('#image_upload_'+key).val();
				var point = jQuery('#point_'+key).val();

				var tagValArray = jQuery("#tag_"+key).val();
				var tagValue = '';
				if(tagValArray.length){
					tagValue = tagValArray.join(',');
				}

				var outcomeValArray = jQuery("#outcome_"+key).val();
				var outcomeValue = '';
				if(outcomeValArray.length){
					outcomeValue = outcomeValArray.join(',');
				}

				var answer_action = jQuery("#ansaction_"+key).val();
				var url = '';
				if(answer_action == 'url'){
					url = jQuery("#url_"+key).val();
				}

				var diff_chatflow_id = "";
				var diff_question_id = "";
				if(answer_action == 'different_bot'){
					diff_chatflow_id = jQuery("#start_bot_"+key).val();
					diff_question_id = jQuery("#start_message_"+key).val();
				}

				var start_ques = jQuery("#start_ques_"+key).val();

	            var answer = getChoiceById(question_id, key);

	            if (answer) {
	                answer.title = value;
	                answer.image = image;
	                answer.tag = tagValue;
	                answer.outcome = outcomeValue;
	                answer.answer_action = answer_action;
	                answer.url = url;
	                answer.point = point;
	                answer.repeat_ques = start_ques;
	                answer.diff_chatflow_id = diff_chatflow_id;
	                answer.diff_question_id = diff_question_id;

					//answer.livechat = jQuery('#connect_to_agent_'+key).prop('checked') ? 1 : 0;
	                choices.push(answer);
	            }

	            if (jQuery('.node-question-' + question_id).find('.chat-message-buttons > div').eq(index).length < 1) {
	                lbbAddNodeOutput(question_id, value, answer.id);
	            }else{
					jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').eq(index).find('.quick-input-choice').val(value);
					jQuery('.node-question-'+question_id).find('.chat-message-buttons > .llb-chatflow-quick-action').eq(index).attr('data-id', key);
					//console.log(key);
				}

				var anstype = jQuery('#ansaction_'+key).val();
				if(anstype == 'start_over'){
					var next_question_id = jQuery('#start_ques_'+answer.id).val();
					output_index  = findIndexOfChoice(question_id,answer.id);
					//console.log(output_index);
					output_index = parseInt(output_index) + 1;
					if(lbb_questions.length > 0) {
						lbb_questions.forEach(question => {
							try{
								removeNodeConnection(question_id,question.id,output_index);
							} catch (error) {
								
							}
						});
					}
					setNondeConnection(question_id, next_question_id, output_index);
					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.removeClass('lbb-different-bot');
						}
					});
				}else if(anstype == 'different_bot'){
					output_index  = findIndexOfChoice(question_id,answer.id);
					output_index = parseInt(output_index) + 1;
					lbb_questions.forEach(question => {
						try{
							removeNodeConnection(question_id,question.id,output_index);
						} catch (error) {
							
						}
					});

					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.addClass('lbb-different-bot');
						}
					});
				}else{
					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.removeClass('lbb-different-bot');
						}
					});
				}
            }
            
        });
        
        if (lbb_questions[qindex]) {
            lbb_questions[qindex]['choices'] = choices;
        }


		if(lbb_questions[qindex]['choices'].length > 0) {
			jQuery('.node-question-'+question_id).find('.lbb-no-answer').hide();
		}else{
			jQuery('.node-question-'+question_id).find('.lbb-no-answer').show();
		}

    
}

function lbb_load_questions(diff_chatflow_id,diff_question_id,answer_id){
	lbbShowLoader();
	jQuery.ajax({
		type: 'POST',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'lbb_load_questions',
			'chatflow_id': diff_chatflow_id,
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);
			if(response){
				jQuery('#start_message_'+answer_id).closest('.answertype-info-different_bot_message').show();
				//jQuery('.answertype-info-different_bot_message').show();
				jQuery('#start_message_'+answer_id).html(response);
				jQuery('select[name="start_bot_'+answer_id+'"]').val(diff_chatflow_id);
				jQuery('select[name="start_message_'+answer_id+'"]').val(diff_question_id);
				
			}
		}
	});
}
function scrollToElementByClass(className) {
    const elements = document.getElementsByClassName(className);

    if (elements.length > 0) {
        elements[0].scrollIntoView({
            behavior: 'smooth', // You can use 'auto' for instant scrolling
            block: 'start',     // Scroll to the top of the element
        });
    }
}


function lbbScrollTo(target){
	jQuery('.lbb-popup-body-wrapper').animate({
		scrollTop: jQuery("#"+target).position().top
	}, 1000);
}

function findEMIndex(question_id,choice_id) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.extra_messages.length; i++) {
        if (question.extra_messages[i].id === choice_id) {
            return i;
        }
    }
    return null; // If record with specified ID is not found
}


function deleteExtraMessage(ques_id,id){
	var index = findEMIndex(parseInt(ques_id),id);
	var ques_index = findIndexByQuestion(parseInt(ques_id),id);
	lbb_questions[ques_index]['extra_messages'].splice(index,1);
	jQuery('#'+id).remove();
}

function lbb_next_tab(){
 	var selected = jQuery('#tabs-nav');
    var next = selected.find('li.active');
    var nextVisible = next.nextAll('li:visible').first();
    if (nextVisible.length > 0) {
        nextVisible.find('a').trigger('click');
    }
	jQuery('.lbb-back-chatflow').show();
 	if(jQuery('#tabs-nav li.active').index() == 8){
        jQuery('.lbb-next-chatflow').hide();
    }else{
        jQuery('.lbb-next-chatflow').show();
    }
}

function lbbConfirmationDialog(title, text, confirmText, cancelText) {
	return swal({
	  title: title,
	  text: text,
	  icon: "warning",
	  buttons: [cancelText, confirmText],
	  dangerMode: true,
	});
}

function lbbAlertDialog(title, text, cancelText) {
	return swal({
	  title: title,
	  text: text,
	  icon: "warning",
	  dangerMode: true,
	});
}

function lbbAddNodeOutput(question_id,text, id = 0){

	jQuery('.node-question-'+question_id).find('.chat-message-buttons').append('<div class="llb-chatflow-quick-action lbb-content-box-action" data-id="'+id+'" data-question_id="'+question_id+'"><input type="text" value="'+text+'" class="quick-input-choice" /> <div class="lbb-menu-icon-container"> <i class="bx bx-dots-vertical-rounded"></i><div class=" lbb-menu-popup"> <ul> <li><a class="lbb-link lbb-new-node">Add a New Node</a></li> <li><a class="lbb-link lbb-next-action">Next Action</a></li> <li><a class="lbb-link lbb-edit-answer">Edit Answer</a></li> <li><a class="lbb-link lbb-delete-answer">Delete</a></li> <li><a class="lbb-link lbb-assign-tag">Assign Tag</a></li> <li><a class="lbb-link lbb-clone-answer">Clone</a></li> <li><a class="lbb-link lbb-map-outcome">Map to outcome</a></li> </ul> </div> </div></div>'); var node_id = jQuery('.node-question-'+question_id).attr('id').replace('node-','');
	lbb_editor.addNodeOutput(node_id);

}

function lbbAddNodeOutputOnly(question_id){

	var node_id = jQuery('.node-question-'+question_id).attr('id').replace('node-','');
	lbb_editor.addNodeOutput(node_id);

}

function getQuestionById(questionId) {
    for (const item of lbb_questions) {
        if (item.id === parseInt(questionId)) {
            return item;
        }
    }
    return null;
}

function getOutcomeById(id) {
    for (const item of lbb_outcomes) {
        if (item.id === (id)) {
            return item;
        }
    }
    return null;
}

function getPDFById(id) {
    for (const item of lbb_pdfs) {
        if (item.id === (id)) {
            return item;
        }
    }
    return null;
}

function getQuestionTitleById(id){
	for (const item of lbb_questions) {
        if (item.id === parseInt(id)) {
            return item.title;
        }
    }
    return '';
}

function removeNodeConnection(q_id,nq_id,output) {
	var S_Question = getNodeIdByQuestionId(q_id);
	var E_Question = getNodeIdByQuestionId(nq_id);
	lbb_editor.removeSingleConnection(S_Question,E_Question,'output_'+output,'input_1');
}

function getQuestionDropdown(label = 'Select Message') {
    let options = `<option value="">${label}</option>`;
    if (lbb_questions.length > 0) {
        lbb_questions.forEach(question => {
			if(question.type != 'welcome'){
            	options += `<option value="${question.id}">${question.title}</option>`;
			}
        });
    }
    return options;
}

function getTagsDropdown(label = 'Select Tags') {
    let options = ``;
    if (lbb_tags.length > 0) {
        lbb_tags.forEach(tag => {
            options += `<option value="${tag.id}">${tag.name}</option>`;
        });
    }
    return options;
}

function getOutcomesDropdown(label = '') {
    let options = label;
    if (lbb_outcomes.length > 0) {
        lbb_outcomes.forEach(outcome => {
            options += `<option value="${outcome.id}">${outcome.name}</option>`;
        });
    }
    return options;
}

function getOutcomesListing(label = '') {
    let options = label;
    let c = 1;
    if (lbb_outcomes.length > 0) {
        lbb_outcomes.forEach(outcome => {
            options += `<li><a href="${site_url}/wp-admin/admin.php?page=listbuildingbot-settings&tab=outcome&outcome_id=${outcome.id}" target="_blank">Outcome ${c}: ${outcome.name}</a></li>`;
            c++; 
        });
    }
    return options;
}

function getPDFsDropdown(label = '') {
    let options = label;
    if (lbb_pdfs.length > 0) {
        lbb_pdfs.forEach(pdf => {
            options += `<option value="${pdf.id}">${pdf.name}</option>`;
        });
    }
    return options;
}

function getCFieldsDropdown(label = 'Select Field') {
    let options = `<option value="">${label}</option>`;
    if (lbb_custom_fields.length > 0) {
        lbb_custom_fields.forEach(field => {
            options += `<option value="${field.id}">${field.label}</option>`;
        });
    }
    return options;
}

function isAnswersOutcomeMapped() {

	var isFound = false;
	lbb_questions.forEach(function(question) {
		if(question.type == 'single' || question.type == 'message'){
			question['choices'].forEach(function(answer) {
				if(answer.outcome != ''){
					isFound = true;
					return false;
				}
			});
		}
	});
	return isFound;
}

function isAnswerHasPoint() {

	var isFound = false;
	lbb_questions.forEach(function(question) {
		if(question.type == 'single' || question.type == 'message'){
			question['choices'].forEach(function(answer) {
				if(answer.point != undefined && parseInt(answer.point) > 0){
					isFound = true;
					return false;
				}
			});
		}
	});
	return isFound;
}

function getTotalPoints() {

	var points = 0;
	if(lbb_questions != null){
		lbb_questions.forEach(function(question) {
			if(question.type == 'single' || question.type == 'message'){
				let maxPoint = -Infinity; 
				question['choices'].forEach(function(answer) {
					if (answer.point !== undefined && parseInt(answer.point) > maxPoint) {
						maxPoint = parseInt(answer.point);
						points = points + maxPoint;
					}
				});
			}
		});
	}
	
	return points;
}

function isOutcomeFound(){
	var found = false;
	if (lbb_outcomes.length > 0) {
       found = true;
    }
	return found;
}

function lbbTinyMceMini(selector){

	//tinymce.get('new-textarea').remove(); 
	
	if (wp.editor && wp.editor.initialize) {
		wp.editor.initialize(selector, {
		  tinymce: {
		  	plugins: 'paste link',
	  		paste_as_text: true,
			toolbar1: 'bold italic underline link | styleselect',
			toolbar2: '',
			//inline: true,
			menubar: false,
			force_br_newlines : true,
			force_p_newlines : false,
			paste_as_text: true,
			fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px",
			lineheight_formats: "1 1.1 1.2 1.3 1.4 1.5 1.6 1.7 1.8 1.9 2", 
			style_formats: [
	                { title: 'Heading 2', format: 'h2' },
	                { title: 'Heading 3', format: 'h3' },
	                { title: 'Heading 4', format: 'h4' },
	                { title: 'Paragraph', format: 'p' }
	            ],
	            style_formats_autohide: true
		  },
		  quicktags: false
		});
	}
}

function lbb_content_text_tiny_mce_editor() {
	try {
	tinymce.init({
	 	mode : "specific_textareas",
		editor_selector : "lbb_tiny_text_editor",
  		paste_as_text: true,
		resize: "both",
		height: 250,
		fontsize_formats: '8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 20pt 24pt 30pt 36pt',
	   	font_formats: "Andale Mono=andale mono,times;" + "Arial=arial,helvetica,sans-serif;" + "Arial Black=arial black,avant garde;" + "Book Antiqua=book antiqua,palatino;" + "Comic Sans MS=comic sans ms,sans-serif;" + "Courier New=courier new,courier;" + "Georgia=georgia,palatino;" + "Helvetica=helvetica;" + "Impact=impact,chicago;" + "Montserrat=Montserrat,sans-serif;Open Sans=open sans,sans-serif;Poppins=Poppins,sans-serif;Lato=Lato,sans-serif;Nunito=Nunito,sans-serif;Noto Serif=Noto Serif,sans-serif;Noto Sans=Noto Sans,sans-serif;Raleway=Raleway,sans-serif;" + "Symbol=symbol;" + "Tahoma=tahoma,arial,helvetica,sans-serif;" + "Terminal=terminal,monaco;" + "Times New Roman=times new roman,times;" + "Trebuchet MS=trebuchet ms,geneva;" + "Verdana=verdana,geneva;" + "Webdings=webdings;" + "Wingdings=wingdings,zapf dingbats",
		content_style:"@import url('https://fonts.googleapis.com/css?family=Open+Sans');@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');@import url('https://fonts.googleapis.com/css2?family=Lato&display=swap');@import url('https://fonts.googleapis.com/css2?family=Nunito&display=swap');@import url('https://fonts.googleapis.com/css2?family=Raleway&display=swap');@import url('https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap');@import ur('https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap');",  
		toolbar1: 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
		toolbar2: 'print preview media | forecolor backcolor emoticons ',      
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
	   	setup : function(ed) {
			ed.on('init', function() {
				//ed.execCommand("fontName", false, "'open sans', sans-serif");
				jQuery(ed.getDoc()).contents().find('body').blur(function(){
				   
				});  
			});
		}
	});
	} catch (error) {
			
	}
}

function lbbTinyMceRemove(selector){

	jQuery(selector+" .lbb-tinymce-editor").each(function () {
		// Get the ID of each element
		var editorId = jQuery(this).attr("id");
  
		// Check if the TinyMCE editor exists
		if (tinymce.get(editorId)) {
			// Remove the TinyMCE editor
			tinymce.get(editorId).remove();
		}
	});

}

function isPDFFound(){
	var found = false;
	if (lbb_pdfs.length > 0) {
       found = true;
    }
	return found;
}

// Assuming you have a Drawflow instance named 'editor'
function reorderOutputs(nodeId, oldIndex, newIndex){
	
}

function getNodeIdByQuestionId(question_id) {
    const nodeElement = jQuery('.node-question-' + question_id);
    
    if (nodeElement.length > 0) {
        return nodeElement.attr('id').replace('node-', '');
    } else {
        // Handle the case when no node element is found
        return null; // Or another appropriate value
    }
}


function lbbRemoveNodeOutput(question_id,output_id) {
	//jQuery('.lbb-single-question-outer .lbb-list-answer li').length
	if(jQuery('#objectList>li').length == 0){
		jQuery('.lbb-single-question-outer').append('<div class="lbb-form-group lbb-text-left lbb-no-answers-found lbb-empty-box"> <div class="lbb-box-container"> <span class="dashicons dashicons-warning lbb-box-icon"></span> <p class="lbb-box-text">Currently you don\'t have any answer choices.</p> <a href="javascript:void(0);" class="lbb-add-new-rep box-button lbb-btn lbb-btn-black">Click here to add</a> </div> </div>');
		jQuery('.lbb-field-image-answer').hide();
		jQuery('.lbb-show-image-ans').hide();
	 }
	var index = output_id - 1;
	jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').eq(index).remove();
	var node_id = jQuery('.node-question-'+question_id).attr('id').replace('node-','');
	lbb_editor.removeNodeOutput(node_id,'output_'+output_id);


}

function decodeHtmlEntities(text) {
    const parser = new DOMParser();
    const decoded = parser.parseFromString(text, "text/html").body.textContent;
    return decoded;
}

function lbbOutcomePDFMap(outcome_id,pdf_id){

	var question_id = jQuery('#lbb_question_id').val();
	var question = getQuestionById(parseInt(question_id));
	var index = findIndexByQuestion(parseInt(question_id));

	var newObj = {
        "outcome_id": outcome_id,
		'pdf_id' : pdf_id
    };

	var fid = findIndexByOutcomePDF(question_id, outcome_id, pdf_id);

	if(fid == null){
    	lbb_questions[index]['pdfmap'].push(newObj);
	}else{
		lbb_questions[index]['pdfmap'][fid] = newObj;
	}

	updateMapList(question_id);
}

function slbUpateExtraMessages(question_id){
	var question = getQuestionById(parseInt(question_id));

	if(question.extra_messages.length > 0){
		question.extra_messages.forEach(function(obj) {
			var questionMessagesform = jQuery('#questionMessages').html();
			questionMessagesform = questionMessagesform.replaceAll('{{uid}}',obj.id);
			jQuery('#question-extra-messages').append('<div id="'+obj.id+'">'+questionMessagesform+'</div>');
			jQuery('#input-question-message-'+obj.id).val(obj.message);
		});
		lbbTinyMceMini('question-extra-messages .lbb-tinymce-editor');
	}
}

function updateMapList(question_id){
	
	var question = getQuestionById(parseInt(question_id));
	var tr = '';
	var i = 0;
	if(question.pdfmap.length > 0){
		question.pdfmap.forEach(function(obj) {
			
			var outcome = getOutcomeById(obj.outcome_id);
			var pdf = getPDFById(obj.pdf_id);
			
			tr += '<tr class="lbb-map-list-tr"><td>'+outcome.name+'</td><td>'+pdf.name+'</td><td><a href="javascript:void(0);" class="lbb-icon-transparent-btn lbb-delete-btn lbb-remove-outcome-pdf" data-index='+i+'><span class="dashicons dashicons-trash"></span> Delete</a></td></tr>';
			i++;
		});
		var maplisttable = jQuery('#mapListTable').html();
		maplisttable = maplisttable.replace('{{data}}',tr);
		jQuery('.lbb-outcome-pdf-table').html(maplisttable);
	}
}

function removeOutcomePDFMap(question_id,index){
	var question = getQuestionById(parseInt(question_id));
	var qindex = findIndexByQuestion(parseInt(question_id));
	lbb_questions[qindex]['pdfmap'].splice(index,1);
}

function lbbCreateOutcome(){
	jQuery('#propwrap_outcome').addClass('itson');
	jQuery('#propwrap_outcome').addClass('expanded');
	var iframe = jQuery('#outcomeCreateForm').html();
	jQuery('#properties_outcome').html(iframe);
	jQuery('#properties_outcome').addClass('is-fullmode');
}

function lbbCreateCustomfield(){
	jQuery('#propwrap_customfield').addClass('itson');
	jQuery('#propwrap_customfield').addClass('expanded');
	var iframe = jQuery('#customfieldCreateForm').html();
	jQuery('#properties_customfield').html(iframe);
	jQuery('#properties_customfield').addClass('is-fullmode');
}

function lbb_close_mini(id){
	jQuery('#propwrap_'+id).removeClass('itson');
	jQuery('#propwrap_'+id).removeClass('expanded');
}

function lbbCreateNewCustomfield(){
	var lbb_label = jQuery('#lbb_label').val();
	var lbb_name = jQuery('#lbb_name').val();
	var field_type = jQuery('#lbb_field_type').val();
	var required_field = jQuery('#lbb_required_field').val();

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
		data: {
			'action': 'save_customfield_data',
			'lbb_label': lbb_label,
			'lbb_name': lbb_name,
			'lbb_field_type': field_type,
			'lbb_required_field': required_field,
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);
			jQuery('#lbb-save-customfield').text('Save');
			if(response == 'already_exist'){
				swal('Name Already Exist');
				return false;
			}
			
			lbb_custom_fields.push(response.customfield);
			var qid = jQuery('#lbb_question_id').val();
			lbbEditQuestionForm(qid);
			lbb_close_mini('customfield');
			var question_type = jQuery('#message_type').find(":selected").val();
			if(question_type == 'single' || question_type == 'message'){
				jQuery('.lbb-cf-for-single select[name="question-save-property"]').val(response.customfield_id);
			}else{
				jQuery('.lbb-cf-for-other select[name="question-save-property"]').val(response.customfield_id);
			}
		}
	});
}

function lbbCreateNewOutcome(){

	var outcome_name = jQuery('#outcome-title').val();
	var outcome_descrition = jQuery('#outcome-description').val();
	lbbShowLoader();
	jQuery.ajax({
		type: 'post',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'save_outcomes_data',
			'lbb_outcomes_name': outcome_name,
			'lbb_outcomes_description': outcome_descrition,
			'lbb_chatflow_id' : chatflow_id
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);
			if(response){
				lbb_outcomes.push(response);
				var qid = jQuery('#lbb_question_id').val();
				lbbEditQuestionForm(qid);
			}
			lbb_close_mini('outcome');
		}
	});
}
function lbbEditQuickTagForm(question_id, answer_id) {
	var question = getQuestionById(parseInt(question_id));
	var lbbQuickEditTagForm = jQuery('#lbbQuickEditTagForm').html();
	var lbbQuickEditTagForm = lbbQuickEditTagForm.replaceAll('{{answer_id}}', answer_id);
	var obj = getChoiceById(question_id,answer_id);
	lbbQuickEditTagForm = lbbQuickEditTagForm.replaceAll('{{text}}', obj.title);
	jQuery('#quick-edit-form-main').html(lbbQuickEditTagForm);

	jQuery('#lbb-quick-questionid').val(question_id);
	jQuery('#lbb-quick-answerid').val(answer_id);

	var options = getTagsDropdown();
	jQuery('#tag_'+answer_id).html(options);

	if(obj.tag != undefined && obj.tag != ''){
		var selectedValues = obj.tag.split(',').map(function(item) {
			return item.trim();
		});
		jQuery("#tag_"+answer_id).val(selectedValues).change();
	}

	jQuery(".js-select2").select2({
		placeholder: "Choose a filter",
		minimumResultsForSearch: -1
	});	
}

function setNondeConnection(q_id,nq_id,output){
	var S_Question = getNodeIdByQuestionId(q_id);
	var E_Question = getNodeIdByQuestionId(nq_id);
	lbb_editor.addConnection(S_Question,E_Question,'output_'+output,'input_1');
}

function lbbEditQuickForm(question_id, answer_id) {
	var question = getQuestionById(parseInt(question_id));
	var lbbQuickEditForm = jQuery('#lbbQuickEditForm').html();
	var lbbQuickEditForm = lbbQuickEditForm.replaceAll('{{answer_id}}', answer_id);
	var obj = getChoiceById(question_id,answer_id);
	lbbQuickEditForm = lbbQuickEditForm.replaceAll('{{text}}', obj.title);
	jQuery('#quick-edit-form-main').html(lbbQuickEditForm);

	var ques_options = getQuestionDropdown();	
	jQuery('#start_ques_'+answer_id).html(ques_options);
	jQuery('.answertype-inf').hide();
	jQuery('.answertype-info-'+obj.answer_action).show();
	jQuery('#lbb-quick-questionid').val(question_id);
	jQuery('#lbb-quick-answerid').val(answer_id);
	
	if(obj.answer_action != undefined && obj.answer_action != ''){
		jQuery("#ansaction_"+answer_id).val(obj.answer_action).change();
	}else{
		jQuery("#ansaction_"+answer_id).val('').change();
	}

	if(obj.answer_action == 'start_over' && obj.repeat_ques != ''){
		jQuery("#start_ques_"+answer_id).val(obj.repeat_ques);
	}

	if(obj.url != ''){
		jQuery("#url_"+answer_id).val(obj.url);
	}


	if(obj.answer_action == "start_over" || obj.answer_action == "" || obj.answer_action == null){
		jQuery(".lbb-answer-action").val('start_over');
		jQuery(".lbb-answer-action").change();
		jQuery('.lbb-answer-start-ques').val(obj.map);
		jQuery(".lbb-answer-start-ques").change();
	}else if(obj.answer_action == "different_bot"){
		jQuery(".lbb-answer-action").val(obj.answer_action);
		var diff_chatflow_id = obj.diff_chatflow_id;
		var diff_question_id = obj.diff_question_id;
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_load_chatflows',
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					jQuery('.lbb-bot-start-ques').html(response);
					
					lbb_load_questions(diff_chatflow_id, diff_question_id, answer_id);
				}
			}
		});
	}
	
}

function lbbEditQuestionForm(question_id) {
	var question = getQuestionById(parseInt(question_id));
	if(question != null){
		var type = question.type;
		jQuery('#message_type').val(type);
		if(type == 'outcome' && !isOutcomeFound()){
			jQuery('#chatflow-answer').hide();
			//jQuery('.lbb-sub-tab-wrapper').addClass('lbb-edit-only');
			jQuery('#lbb-single-next').hide();
			jQuery('.lbb-no-outcome').show();
			jQuery('.lbb-field-wrapper').hide();
			jQuery('#lbb-save-question').hide();
			jQuery('#lbb-preview-question').hide();
			jQuery('.lbb-form-group-single').hide();
			return false;
		}else if(type == 'outcome' /*&& !isAnswersOutcomeMapped()*/){
			jQuery('#chatflow-answer').hide();
			if(!isAnswersOutcomeMapped()){
				jQuery('#lbb-single-next').hide();
				jQuery('.lbb-form-group-single').hide();
				jQuery('.lbb-no-outcome').hide();
				jQuery('.lbb-no-answeroutcome').show();
				jQuery('.lbb-field-wrapper').hide();
				jQuery('#lbb-save-question').hide();
				jQuery('#lbb-preview-question').hide();
				return false;
			}
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.outcome-points-section').show();
			jQuery('.lbb-popup-answers-wrapper').show();
			jQuery('#lbb-single-next').hide();
			jQuery('.pdf-download-option').show();
		}else if(type == 'outcome' && !isPDFFound()){
			jQuery('.lbb-no-pdf').show();
			jQuery('.lbb-form-group-single').hide();
		}else if(type == 'text'){
			jQuery('.question-input-field-outer').show();
			jQuery('.lbb-custom-placeholder').show();
			jQuery('.lbb-form-group-sub-tab').hide();
		}else if(type == 'name' || type == 'email'){
			jQuery('.lbb-custom-placeholder').show();
			jQuery('.lbb-form-group-sub-tab').hide();
		}else if(type == 'attachment'){
			jQuery('.question-type-fileupload').show();
			jQuery('.select-format-section').show();
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-popup-answer-wrapper').show();
			jQuery('.lbb-outcome-info-card').show();
		}else if(type == 'single' || type == 'message'){
			jQuery('.dynamic-message-section').show();
			jQuery('.lbb-form-group-single').show();
		}else if(type == 'welcome'){
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-change-message-type').hide();
			jQuery('#lbb-single-next').hide();
		}else if(type == 'lastmessage'){
			jQuery('#chatflow-properties').hide();
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-change-message-type').hide();
			jQuery('#lbb-single-next').hide();
		}else if(type == 'date'){
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-no-pdf').hide();
			jQuery('.lbb-no-outcome').hide();
			jQuery('.question-input-field-outer').hide();
			jQuery('.question-type-fileupload').hide();
			jQuery('.lbb-popup-answers-wrapper').show();
		}else{
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-no-pdf').hide();
			jQuery('.lbb-no-outcome').hide();
			jQuery('.question-input-field-outer').hide();
			jQuery('.question-type-fileupload').hide();
		}
		
		if(type == 'attachment' || type == 'audio'){
			jQuery('#lbb-single-next').hide();
		}

		if(type != 'welcome'){
			if(jQuery('input[name="lbb_meta[automation_triggered]"]:checked').val() == 'after_answer_pick'){
	        	jQuery('.lbb-show-trigger-automation').show();
	        }else{
	        	jQuery('.lbb-show-trigger-automation').hide();       	
	        }
		}

		jQuery('.edit-question-heading').html('Edit Message : '+type.toUpperCase());
		
		updateFieldSupport(type);
		jQuery('#chatflow-properties').removeClass('lbb-edit-only');
		if(type == 'single' || type == 'message' || type == 'welcome' || type == 'outcome' || type == 'attachment' || type == 'audio'){
			jQuery('#chatflow-properties').addClass('lbb-edit-only')
		}

		jQuery('#question-name').val(decodeHtmlEntities(question.title));
		jQuery('#question-message').val((question.content));
		jQuery('#question-input-field').val(question.input_select);
		jQuery('#maxFileUploadSize').val(question.maxFileUploadSize);
		lbbTinyMceMini('properties .lbb-tinymce-editor');

		var valuesToCheck = question.question_upload_type.split(',');
		jQuery('input[name="question_upload_type"]').each(function() {
	      	var checkboxValue = jQuery(this).val();
	      	if (valuesToCheck.includes(checkboxValue)) {
	        	jQuery(this).prop('checked', true);
	      	}
    	});

		var options = getCFieldsDropdown();
		if(type == 'single' || type == 'message'){
			jQuery('.lbb-cf-for-single select[name="question-save-property"]').html(options).val(question.save_property);
		}else{
			jQuery('.lbb-cf-for-other select[name="question-save-property"]').html(options).val(question.save_property);
		}

		//if(type == 'outcome'){
			var outcomeOptions = getOutcomesDropdown('<option value="">Select Outcome</option>');
			jQuery('#lbb_outcome_id').html(outcomeOptions);

			var pdfOptions = getPDFsDropdown('<option value="">Select PDF</option>');
			jQuery('#lbb_pdf_id').html(pdfOptions);

			updateMapList(question_id);
		//}

		if(question.image){
			jQuery('.lbb-list-answer').removeClass('lbb-ans-no-image');
			jQuery('#image_upload_ques_image').val(question.image);
			jQuery('#image_upload_ques_image_src').attr('src',question.image);
		}else{
			var defaultImageUrl = lbb_ajax.lbb_path+'admin/images/no-image.png';
			jQuery('#image_upload_ques_image').val('');
			
			jQuery('.lbb-list-answer').addClass('lbb-ans-no-image');
			jQuery('#image_upload_ques_image_src').attr('src',defaultImageUrl).addClass('lbb-no-img-found');
		}
		
		var imgans = question.image_answer == 1 ? true : false;
		var skip_question = question.skip_question == 1 ? true : false;
		var trigger_automation = question.trigger_automation == 1 ? true : false;
		var mobileimgans = question.mobile_image_answer == 1 ? true : false;
		if(imgans == true){
			jQuery('.answer-image-options').show();
			jQuery('.lbb-list-answer').removeClass('lbb-ans-no-image');
			jQuery('.lbb-choice-input-image').addClass('show-image-answer');
			setTimeout(function(){
				jQuery('.lbb-answer-image-box').show();
			}, 500);
		}else{
			jQuery('.answer-image-options').hide();
			jQuery('.lbb-list-answer').addClass('lbb-ans-no-image');
			jQuery('.lbb-choice-input-image').removeClass('show-image-answer');
			setTimeout(function(){
				jQuery('.lbb-answer-image-box').hide();
			}, 500);
		}

		/*setTimeout(function(){
			if(mobileimgans == true){
				jQuery('.answer-image-options').show();
			}else{
				jQuery('.answer-image-options').hide();
			}
		}, 500);*/
		jQuery('#question-ans-image').prop('checked',imgans);
		jQuery('#skip_question').prop('checked',skip_question);
		jQuery('#trigger_automation').prop('checked',trigger_automation);
		jQuery('#question-ans-mobile-image').prop('checked',mobileimgans);

		jQuery('#lbb_answer_image_object_fit').val(question.answer_image_object_fit).trigger('change');
		jQuery('#lbb_img_answer_button_row_column').val(question.answer_img_button_row_column).trigger('change');

		jQuery('.lbb-answer-slider-outer').each(function(){
	        var slider = jQuery(this).find('.lbb-slider');
	        var slider_min = jQuery(this).find('.lbb-slider').attr('data-slider-min');
	        var slider_max = jQuery(this).find('.lbb-slider').attr('data-slider-max');
	        var slider_value = question.answer_image_height;
	        var slider_step = jQuery(this).find('.lbb-slider').attr('data-slider-step');
	        var css_var = jQuery(this).find('.lbb-slider').attr('data-css-variable');
	        var data_px = jQuery(this).find('.lbb-slider').attr('data-value-px');
	        jQuery(this).find('.lbb-slider-input').val(slider_value);
	        slider.slider({
	            value: parseInt(slider_value),
	            min: parseInt(slider_min),
	            max: parseInt(slider_max),
	            step: parseInt(slider_step),
	            slide: function(event, ui) {
	                jQuery(this).parent('.lbb-answer-slider-outer').find('.lbb-slider-input').val(ui.value);
	            }
	        });
	    });

		jQuery('#question-smartautomation').prop('checked',question.smart_automation);

		var dynmsg = question.dynamic_message_status == 1 ? true : false;
		var otrange = question.outcome_range_enabled == 1 ? true : false;
		jQuery('#dynamic-message-status').prop('checked',dynmsg);
		jQuery('#outcome-points-status').prop('checked',otrange);

		setTimeout(function(){
			if(otrange == true){
				jQuery('.lbb-outcome-field').show();
			}else{
				jQuery('.lbb-outcome-field').hide();
			}
		}, 500);
		

		var cp = question.custom_placeholder == 1 ? true : false;
		/*jQuery('#custom-placeholder').prop('checked',cp);
		if(cp){
			jQuery('.lbb-question-field-placeholder').show();
		}else{
			jQuery('.lbb-question-field-placeholder').hide();
		}*/

		var dp = question.enable_pdf_download == 1 ? true : false;
		jQuery('#enable_pdf_download').prop('checked',dp);
		if(dp){
			jQuery('.download-pdf-section').show();
		}else{
			jQuery('.download-pdf-section').hide();
		}

		jQuery('#funnel-placeholder').val(question.funnel_placeholder);
		jQuery('#download-pdf-button').val(question.download_pdf_button);
		
		if(type == 'single' || type == 'message'){
			updateUI(question_id);

			
		}
		slbUpateExtraMessages(question_id);
			
		dynamicData(question_id);

		outcomeData(question_id);
 
		//setTimeout(() => {
			jQuery(".js-select2").select2({
				placeholder: "Choose a filter",
				minimumResultsForSearch: -1
			});	
		//}, 500);
		

		lbbAdvanceLogicList(question_id);
	}
}

function getConditionByUID(question_id,uid) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.advance_logic.length; i++) {
        if (question.advance_logic[i].id === uid) {
            return question.advance_logic[i];
        }
    }
    return null; // If record with specified ID is not found
}

function lbbEditAdvanceLogicForm(question_id,uid) {
	var index = findIndexByQuestion(parseInt(question_id));
	var logicItem = getConditionByUID(question_id,uid);
	var template = jQuery('#questionBranchForm').html();
	var i = 0;
	jQuery('#branch-if-editform-conditions').html('');
	var options = getQuestionDropdown();
	var cOptions = getCFieldsDropdown();
	jQuery('#lbbc_question_map').html(options);

	if(logicItem != null && logicItem.conditions.length > 0) {
		logicItem.conditions.forEach(function(item) {
			
			var divId = logicItem.id+'_'+i;

			if((logicItem.conditions.length - 1) == i) {
				var andText = '';
			}else{
				var andText = '<div class="rule-condition-exp">And</div>';
			}
			i++;
			Editform = template.replaceAll('{{uid}}', divId);

			jQuery('#branch-if-editform-conditions').append(Editform+andText);

			var form = jQuery('#rule-'+divId);
			form.find('#lbbc_type').val(item.type);
			form.find('#lbbc_operator').val(item.operator);
			form.find('#lbbc_value').val(item.value);

			form.find('#lbbc_user_property').html(cOptions);
			if(item.type == 'user_property'){
				form.find('.lbb-user-property-field').addClass('show-save-property');
			}
			if(item.user_property != undefined){
				form.find('#lbbc_user_property').val(item.user_property);
			}

			jQuery('#branch-if-add-condition').attr('data-uid',logicItem.id);

		});
		
		jQuery('#lbbc_question_map').val(logicItem.action_map);
	}else{
		Editform = template.replaceAll('{{uid}}', uid);
		jQuery('#branch-if-editform-conditions').append(Editform);
		jQuery('#branch-if-editform-conditions #lbbc_user_property').html(cOptions);
		
	}
}

function generateUniqueId() {
    return Math.floor(Math.random() * Number.MAX_SAFE_INTEGER);
}

function cloneAnswer(question_id,answer_id,quick = false) {
	var index = findIndexByQuestion(parseInt(question_id));
	var objo = getChoiceById(question_id,answer_id);
	var value = jQuery('#titleInput_'+answer_id).val();
	if(quick == false){
		updateChoice(answer_id,value);
	}

	var objo = getChoiceById(question_id,answer_id);
	var obj = Object.assign({}, objo);
	var new_id = 'id-'+generateUniqueId();
	obj.id = new_id;
	lbb_questions[index].choices.push(obj);

	if(quick == false){
		var a_index = findIndexOfChoice(question_id,new_id);
		var item = jQuery('#'+answer_id).html();
		item = item.replaceAll(answer_id,new_id);
		var htmlItem = jQuery("<li id='"+new_id+"'>").html(item);
		jQuery("#objectList").append(htmlItem);

		var options = getTagsDropdown();
		var ques_options = getQuestionDropdown();
		var outcome_options = getOutcomesDropdown();

		jQuery("#tag_"+new_id).change();
		jQuery("#outcome_"+new_id).change();
		jQuery("#ansaction_"+new_id).change();

		// Create an array of values you want to set
		if(obj.tag != undefined && obj.tag != ''){
			var selectedValues = obj.tag.split(',').map(function(item) {
				return item.trim();
			});
			jQuery("#tag_"+new_id).val(selectedValues).change();
		}

		if(obj.outcome != undefined && obj.outcome != ''){
			var selectedValues = obj.outcome.split(',').map(function(item) {
				return item.trim();
			});
			jQuery("#outcome_"+new_id).val(selectedValues).change();
		}

		if(obj.answer_action != undefined && obj.answer_action != ''){
			jQuery("#ansaction_"+new_id).val(obj.answer_action).change();
		}else{
			jQuery("#ansaction_"+new_id).val('').change();
		}

		if(obj.answer_action == 'start_over' && obj.repeat_ques != ''){
			jQuery("#start_ques_"+new_id).val(obj.repeat_ques);
		}

		if (jQuery('.node-question-' + question_id).find('.chat-message-buttons > div').eq(a_index).length < 1) {
			lbbAddNodeOutput(question_id, value, new_id);
		}else{
			jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').eq(a_index).html(value);
		}

		var get_length = jQuery('.lbb-list-answer>li').length;
		get_length = get_length - 1;
		jQuery(".lbb-list-answer .lbb-accordion-single-wrapper .lbb-accordion-heading:eq("+get_length+")").trigger('click');
	}else{
		//var a_index = findIndexOfChoice(question_id,new_id);
		//if (jQuery('.node-question-' + question_id).find('.chat-message-buttons div').eq(a_index).length < 1) {
			lbbAddNodeOutput(question_id, obj.title,new_id);
		//}else{
			//jQuery('.node-question-'+question_id).find('.chat-message-buttons div').eq(a_index).html(obj.title);
		//}
	}
}

function updateUI(ques_id){
	// Clear the existing list
    jQuery("#objectList").empty();
	var index = findIndexByQuestion(parseInt(ques_id));
	var image_answer = lbb_questions[index]['image_answer'];
	var skip_question = lbb_questions[index]['skip_question'];
	var trigger_automation = lbb_questions[index]['trigger_automation'];
	var mobile_image_answer = lbb_questions[index]['mobile_image_answer'];
	var answer_image_height = lbb_questions[index]['answer_image_height'];
	var answer_image_object_fit = lbb_questions[index]['answer_image_object_fit'];
	var answer_img_button_row_column = lbb_questions[index]['answer_img_button_row_column'];
	var options = getTagsDropdown();
	var ques_options = getQuestionDropdown();
	var outcome_options = getOutcomesDropdown();

    // Rebuild the list using the updated data
	if(lbb_questions[index]['choices'].length > 0){
		jQuery('.lbb-no-answers-found').hide();
		jQuery('.lbb-add-new-obj').show();
		jQuery('.lbb-field-image-answer').show();
		jQuery('.answertype-inf').hide();
		jQuery('.answertype-info-node').show();
		jQuery('.answer-per-row').show();
		
		lbb_questions[index]['choices'].forEach(function(obj) {
			if(obj.id == undefined){
				return false;
			}
			if(obj.id != undefined){
				ukey = obj.id;
			}else{
				ukey = 0;
			}
			var src = '';
			var point = 0;
			if(obj.image != undefined){src = obj.image;}
			if(obj.point != undefined){point = obj.point;}

			

			var answerform = jQuery('#answerRepeater').html();
			answerform = answerform.replaceAll('%%UKEY%%',ukey);
			answerform = answerform.replaceAll('%%TITLE%%',obj.title);
			answerform = answerform.replaceAll('%%POINT%%',point);

			if(obj.url != undefined){
				answerform = answerform.replaceAll('%%URL%%',obj.url);
			}else{
				answerform = answerform.replaceAll('%%URL%%','');
			}

			if(src == ''){
				var defaultImageUrl = lbb_ajax.lbb_path+'admin/images/no-image.png';
				answerform = answerform.replaceAll('%%IMAGE%%',defaultImageUrl);
				jQuery('#image_upload_'+ukey+'_src').addClass('lbb-no-img-found');
			}else{
				answerform = answerform.replaceAll('%%IMAGE%%',src);
			}

			var listItem = jQuery("<li id='"+ukey+"'>").html(answerform);
			jQuery("#objectList").append(listItem);
			
			jQuery('#tag_'+ukey).html(options);
			jQuery('#outcome_'+ukey).html(outcome_options);
			jQuery('#start_ques_'+ukey).html(ques_options);

			
			// Create an array of values you want to set
			if(obj.tag != undefined && obj.tag != ''){
				var selectedValues = obj.tag.split(',').map(function(item) {
					return item.trim();
				});
				jQuery("#tag_"+ukey).val(selectedValues).change();
			}

			if(obj.outcome != undefined && obj.outcome != ''){
				var selectedValues = obj.outcome.split(',').map(function(item) {
					return item.trim();
				});
				jQuery("#outcome_"+ukey).val(selectedValues).change();
			}

			
			/*if(obj.livechat != undefined && obj.livechat != '' && obj.livechat > 0){
				jQuery('#connect_to_agent_'+ukey).prop('checked', true);
			}*/

			if(obj.answer_action != undefined && obj.answer_action != ''){
				jQuery("#ansaction_"+ukey).val(obj.answer_action).change();
			}else{
				jQuery("#ansaction_"+ukey).val('start_over').change();
			}

			if(obj.answer_action == 'start_over' && obj.repeat_ques != ''){
				jQuery("#start_ques_"+ukey).val(obj.repeat_ques);
			}
			if((obj.answer_action == 'start_over' || obj.answer_action == '') && obj.map != ""){
				jQuery("#start_ques_"+ukey).val(obj.map);
			}

			if(obj.answer_action == 'different_bot'){
				var diff_chatflow_id = obj.diff_chatflow_id;
				var diff_question_id = obj.diff_question_id;
				
				jQuery.ajax({
					type: 'POST',
					url: lbb_ajax.ajaxurl,
					data: {
						'action': 'lbb_load_chatflows',
					},
					success: function (response) {
						lbbHideLoader();
						response = JSON.parse(response);
						if(response){
							jQuery('#start_bot_'+ukey).html(response);
							lbb_load_questions(diff_chatflow_id, diff_question_id, obj.id);
						}
					}
				});
			}

		});
		if(image_answer){
			jQuery('.lbb-choice-input-image').addClass('show-image-answer');
		}
	}else{
		jQuery('.lbb-no-answers-found').show();
		jQuery('.lbb-add-new-obj').hide();
		jQuery('.lbb-no-answers-error').hide();
		jQuery('.lbb-field-image-answer').hide();
	}

	
}

function outcomeData(ques_id){
	var index = findIndexByQuestion(parseInt(ques_id));
	var oCOptions = getOutcomesListing();
	jQuery('.lbb-list-outcome').html(oCOptions);
	if(lbb_questions[index]['outcome_range'].length > 0){
	 	jQuery('.lbb-outcome-field').show();
		
		lbb_questions[index]['outcome_range'].forEach(function(obj) {
			var okey = obj.id;
			var dynamic_data = jQuery('#OutcomeRangeRepeater').html();
			dynamic_data = dynamic_data.replaceAll('{{KEY}}',okey);
			jQuery('.outcome-range-table tbody').append('<tr data-key="'+okey+'">'+dynamic_data+'</tr>');

			var oOptions = getOutcomesDropdown('<option value="">Select Outcome</option>');
			jQuery('#outcome_name_'+okey).append(oOptions);

			if(obj.start != undefined && obj.start != ''){
				jQuery('#start_'+okey).val(obj.start);
			}
			if(obj.end != undefined && obj.end != ''){
				jQuery('#end_'+okey).val(obj.end);
			}

			if(obj.outcome_id != undefined && obj.outcome_id != ''){
					
					jQuery("#outcome_name_"+okey).val(obj.outcome_id).change();
				}

		});
	}else{
		jQuery('.lbb-outcome-field').hide();
	}
}

function dynamicData(ques_id){
	var index = findIndexByQuestion(parseInt(ques_id));
	if(lbb_questions[index]['dynamic_messages'].length > 0){
	 	jQuery('.lbb-no-dynamic-message-found').hide();
    	jQuery('.lbb-add-new-dynamic-message').show();
		if(lbb_questions[index]['dynamic_messages'].length > 0){
			try {
				lbb_questions[index]['dynamic_messages'].forEach(function(obj) {
					var dkey = obj.id;
					var dynamic_data = jQuery('#dynamicMessageRepeater').html();
					dynamic_data = dynamic_data.replaceAll('{{KEY}}',dkey);
					jQuery('#lbb-dynamic-message-list').append('<li id="'+dkey+'">'+dynamic_data+'</li>');
	
					var tOptions= getTagsDropdown();
					jQuery('#msg_tag_'+dkey).append(tOptions);
	
					var cOptions = getCFieldsDropdown();
					jQuery('#lbb_dm_cf_'+dkey).html(cOptions);
	
					if(obj.type == 'tags'){
						if(obj.value != undefined && obj.value != ''){
							var selectedValues = obj.value.split(',').map(function(item) {
								return item.trim();
							});
							jQuery("#msg_tag_"+dkey).val(selectedValues).change();
						}
					}else{
						if(obj.value != undefined && obj.value != ''){
							jQuery("#lbb_cf_value_"+dkey).val(obj.value);
						}
	
						if(obj.field_id != undefined && obj.field_id != ''){
							
							jQuery("#lbb_dm_cf_"+dkey).val(obj.field_id).change();
						}
					}
	
					if(obj.type != undefined && obj.type != ''){
						jQuery("#type_"+dkey).val(obj.type).change();
					}
	
					if(obj.operator != undefined && obj.operator != ''){
						jQuery("#lbb_cf_operator_"+dkey).val(obj.operator).change();
					}
	
					
	
					if(obj.message != undefined && obj.message != ''){
						jQuery('#lbb-dynamicText_'+dkey).val(obj.message);
					}
				});
			} catch (error) {
				
			}
			
		}
		lbbTinyMceMini('lbb-dynamic-message-list .lbb-tinymce-editor');
	}else{
		jQuery('.lbb-no-dynamic-message-found').show();
    	jQuery('.lbb-add-new-dynamic-message').hide();
	}
}

function getChoiceById(question_id,choice_id) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.choices.length; i++) {
        if (question.choices[i].id === choice_id) {
            return question.choices[i];
        }
    }
    return null; // If record with specified ID is not found
}

function getExtraMessagesById(question_id,key) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.extra_messages.length; i++) {
        if (question.extra_messages[i].id === key) {
            return question.extra_messages[i];
        }
    }
    return null; // If record with specified ID is not found
}

function findIndexOfChoice(question_id,choice_id) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.choices.length; i++) {
        if (question.choices[i].id === choice_id) {
            return i;
        }
    }
    return null; // If record with specified ID is not found
}

function findIndexOfAdvanceRule(question_id,id) {
	var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.advance_logic.length; i++) {
        if (question.advance_logic[i].id === id) {
            return i;
        }
    }
    return null; // If record with specified ID is not found
}

function findIndexByQuestion(question_id) {
    for (var i = 0; i < lbb_questions.length; i++) {
        if (lbb_questions[i].id === question_id) {
            return i;
        }
    }
    return null;
}

function findIndexByOutcomePDF(question_id,outcome_id,pdf_id) {
    var question = getQuestionById(parseInt(question_id));
    for (var i = 0; i < question.pdfmap.length; i++) {
        if (question.pdfmap[i].outcome_id === outcome_id) {
            return i;
        }
    }
    return null;
}

function updateFieldSupport(type){
	jQuery('.lbb-question-field').each(function(index, element) {
        var element = jQuery(element);
        var support = element.attr('data-support');

        // Check if support is defined before further processing
        if (typeof support !== 'undefined') {
            var supportArray = support.split(','); // Split the string into an array

            if (jQuery.inArray(type, supportArray) !== -1) {
                element.show(); // Show the element if 'single' is present in the array
            }else{
				element.hide();
			}
        } else {
            element.show(); // Show the element if data-support is undefined
        }
    });
}

function lbbAddNewQuestion(type){
	existing_nodes = jQuery('.drawflow .parent-node').length;
	if(jQuery('#question-message').length != 0){
		tinyMCE.get('question-message').setContent(''); 
	}
	var jsonData = {
		title: '',
		content: '',
		type : type,
		numb_ques : lbb_questions.length
	};
	lbbShowLoader();
	jQuery.ajax({
		url: ajaxurl+'?action=lbb_add_new_question',
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(jsonData),
		success: function(response) {
			// Handle the response
			lbbHideLoader();
			if(response.success){
				var data = response.data;
				var question = data.post;
				var question_id = question.id;
				var html = data.html;
				if(question.type == 'single' || question.type == 'message'){
					lbbAddNode(question_id,1,0,html);
				}else{
					lbbAddNode(question_id,1,1,html);
				}
				
				lbb_questions.push(question);
				var questionEditform = jQuery('#questionEditForm').html();
				jQuery('#properties').html(questionEditform);
				jQuery('#lbb_question_id').val(question_id);
				lbbEditQuestionForm(question_id);
				
				jQuery('.node-question-'+question_id).addClass('qt-'+type);

				
					if(question.type == 'message'){
						jQuery('.lbb-add-new-rep').trigger('click');
						jQuery('.lbb-list-answer').find('.lbb-choice-input').val('Next');
					}

					
				
			}
		}
	});
	//lbbAddNode(100,1,3,html);
}

function lbbValidateQuestion(){

	var name = jQuery('#question-name');
	var message = tinyMCE.get('question-message').getContent();
	var isValid = true;
	lbbHideAllerrors();
	if(lbbIsEmpty(name)){
		showError(name);
		isValid = false;
	}else if(message == ''){
		showError(jQuery('#question-message'));
		isValid = false;
		setTimeout(function(){
			jQuery('#chatflow-question').trigger('click');
			jQuery('html, body').animate({
		        scrollTop: jQuery("#question-message").offset().top
		    }, 2000);
		}, 500);
		
	}

	
	return isValid;
}

function lbbValidateAnswer(){
	var isValid = true;
	if(jQuery(".lbb-choice-input").length > 0){
		jQuery(".lbb-choice-input").each(function() {
			var jid = jQuery(this);
			if(lbbIsEmpty(jid)){
				var get_id = jQuery(this).attr('data-key');
				jQuery('#'+get_id).find('.lbb-accordion-heading').trigger('click');
				jid.parents('.lbb-accordion-heading').trigger('click');
				jQuery("#chatflow-question").removeClass('lbb-active');
				jQuery("#chatflow-answers").addClass('lbb-active');

				jQuery('.lbb-form-group-single .lbb-popup-question-wrapper').hide();
				jQuery('.lbb-form-group-single .lbb-popup-answers-wrapper').show();
				showError(jid);
				isValid = false;
				//return false;
			}
		});
	}
	return isValid;
}

function updateChoice(key, value){
	
	var ques_id = jQuery('#lbb_question_id').val();
	var image = jQuery('#image_upload_'+key).val();
	var point = jQuery('#point_'+key).val();
	var index = findIndexByQuestion(parseInt(ques_id));
	var a_index = findIndexOfChoice(ques_id,key);
	var tagValArray = jQuery("#tag_"+key).val();
	var tagValue = '';
	if(tagValArray.length){
		tagValue = tagValArray.join(',');
	}

	var outcomeValArray = jQuery("#outcome_"+key).val();
	var outcomeValue = '';
	if(outcomeValArray.length){
		outcomeValue = outcomeValArray.join(',');
	}

	var answer_action = jQuery("#ansaction_"+key).val();
	var url = '';
	if(answer_action == 'url'){
		url = jQuery("#url_"+key).val();
	}

	var start_ques = jQuery("#start_ques_"+key).val();

	var answer = getChoiceById(ques_id, key);

	if (answer) {
		answer.title = value;
		answer.image = image;
		answer.tag = tagValue;
		answer.outcome = outcomeValue;
		answer.answer_action = answer_action;
		answer.url = url;
		answer.point = point;
		answer.repeat_ques = start_ques;
		lbb_questions[index].choices[a_index] = answer;
	}
	
}
function saveQuickTagForm(question_id,answer_id) {
	var tagValArray = jQuery("#tag_"+answer_id).val();
	var tagValue = '';
	if(tagValArray.length){
		tagValue = tagValArray.join(',');
	}

	var answer = getChoiceById(question_id, answer_id);
	var qindex = findIndexByQuestion(parseInt(question_id));
	var aindex = findIndexOfChoice(question_id,answer_id);
	lbb_questions[qindex].choices[aindex]['tag'] = tagValue;
}
function saveQuickForm(question_id,answer_id) {


	var answer_action = jQuery("#ansaction_"+answer_id).val();
	var url = '';
	if(answer_action == 'url'){
		url = jQuery("#url_"+answer_id).val();
	}

	var diff_chatflow_id = "";
	var diff_question_id = "";
	if(answer_action == 'different_bot'){
		diff_chatflow_id = jQuery(".lbb-bot-start-ques").val();
		diff_question_id = jQuery(".lbb-bot-start-ques_message").val();
	}


	var start_ques = jQuery("#start_ques_"+answer_id).val();
	var answer = getChoiceById(question_id, answer_id);
	var qindex = findIndexByQuestion(parseInt(question_id));
	var aindex = findIndexOfChoice(question_id,answer_id);
	/*answer.answer_action = answer_action;
	answer.url = url;
	answer.repeat_ques = start_ques;*/
	lbb_questions[qindex].choices[aindex]['answer_action'] = answer_action;
	lbb_questions[qindex].choices[aindex]['url'] = url;
	lbb_questions[qindex].choices[aindex]['diff_chatflow_id'] = diff_chatflow_id;
	lbb_questions[qindex].choices[aindex]['diff_question_id'] = diff_question_id;
	lbb_questions[qindex].choices[aindex]['repeat_ques'] = start_ques;
	lbb_questions[qindex].choices[aindex]['map'] = start_ques;
}

function lbbSaveQuestion(question_id, next) {

    var question_type = jQuery('#message_type').find(":selected").val();
    if(question_type != 'welcome'){
    	if(!lbbValidateQuestion()){
			return false;
		}
    }
	
	if(!lbbValidateAnswer()){
		return false;
	}
    var question = getQuestionById(question_id);
	var qindex = findIndexByQuestion(parseInt(question_id));
    lbb_questions[qindex]['type'] = question_type;

	if(jQuery('.llb-question-message-item').length > 0){
		var messages = [];
		jQuery('.llb-question-message-item').each(function(index, element) {
			var key = jQuery(this).attr('data-key');
			var message = getExtraMessagesById(question_id, key);
			var value = tinyMCE.get('input-question-message-'+key).getContent();
			if (message) {
                message.message = value;
				messages.push(message);
			}
		});
	
		if (lbb_questions[qindex]) {
			lbb_questions[qindex]['extra_messages'] = messages;
		}
	}
	
    if (question && (question.type === 'single' || question.type === 'message')) {
    	lbb_question_answer_choices(question_id,qindex)
    }

    var outcome_range = [];
    jQuery('.outcome-range-table tbody tr').each(function(index, element) {
    	var key = jQuery(this).attr('data-key');
    	var start = jQuery('#start_'+key).val();
    	var end = jQuery('#end_'+key).val();
    	var outcome_id = jQuery('#outcome_name_'+key).val();
    	outcome_range.push({
	                    'id':key,
	                    'start':start,
	                    'end':end,
	                    'outcome_id':outcome_id,
	    	});
    });

    var dynamic_messages = [];
    jQuery('.dynamic-message-data').each(function(index, element) {
    	var key = jQuery(this).attr('data-key');
    	var type = jQuery('#type_'+key).val();
    	var tags_array = jQuery('#msg_tag_'+key).val();
    	var message = tinyMCE.get('lbb-dynamicText_'+key).getContent();
		var field_id = jQuery('#lbb_dm_cf_'+key).val();
		var cf_value = jQuery('#lbb_cf_value_'+key).val();
		var operator = jQuery('#lbb_cf_operator_'+key).val();

		if(type == 'tags'){
			var tagsValue = '';
			if(tags_array.length){
				tagsValue = tags_array.join(',');
			} 
	    	dynamic_messages.push({
	                    'id':key,
	                    'type':type,
	                    'value':tagsValue,
	                    'message':message,
	    	});
		}else{
			dynamic_messages.push({
	                    'id':key,
	                    'type':type,
	                    'field_id':field_id,
	                    'value':cf_value,
	                    'operator':operator,
	                    'message':message,
	    	});
		}
    	

    });
	// update UI
	lbb_questions[qindex]['title'] = jQuery('#question-name').val();
	lbb_questions[qindex]['question_type'] = question_type;
	lbb_questions[qindex]['content'] = tinyMCE.get('question-message').getContent();
	lbb_questions[qindex]['image'] = jQuery('#image_upload_ques_image').val();
	lbb_questions[qindex]['input_select'] = jQuery('#question-input-field').val();
	lbb_questions[qindex]['maxFileUploadSize'] = jQuery('#maxFileUploadSize').val();
	lbb_questions[qindex]['question_upload_type'] = jQuery('input[name="question_upload_type"]:checked').map(function () {return this.value; }).get().join(",");
	if(question.type == 'single' || question.type == 'message'){
		lbb_questions[qindex]['save_property'] = jQuery('.lbb-cf-for-single select[name="question-save-property"]').val();
	}else{
		lbb_questions[qindex]['save_property'] = jQuery('.lbb-cf-for-other select[name="question-save-property"]').val();
	}
	lbb_questions[qindex]['image_answer'] = jQuery('#question-ans-image').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['skip_question'] = jQuery('#skip_question').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['trigger_automation'] = jQuery('#trigger_automation').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['mobile_image_answer'] = jQuery('#question-ans-mobile-image').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['answer_image_height'] = jQuery('#lbb_answer_image_height').val();
	lbb_questions[qindex]['answer_image_object_fit'] = jQuery('#lbb_answer_image_object_fit').val();
	lbb_questions[qindex]['answer_img_button_row_column'] = jQuery('#lbb_img_answer_button_row_column').val();
	lbb_questions[qindex]['smart_automation'] = jQuery('#question-smartautomation').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['date_format'] = jQuery('#lbb-date-format').val();
	lbb_questions[qindex]['dynamic_message_status'] = jQuery('#dynamic-message-status').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['outcome_range_enabled'] = jQuery('#outcome-points-status').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['dynamic_messages'] = dynamic_messages;
	lbb_questions[qindex]['outcome_range'] = outcome_range;
	lbb_questions[qindex]['custom_placeholder'] = jQuery('#custom-placeholder').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['funnel_placeholder'] = jQuery('#funnel-placeholder').val();
	lbb_questions[qindex]['enable_pdf_download'] = jQuery('#enable_pdf_download').prop('checked') ? 1 : 0;
	lbb_questions[qindex]['download_pdf_button'] = jQuery('#download-pdf-button').val();

	jQuery('.node-question-'+question_id+' .node-tab-heading').html(lbb_questions[qindex]['title']);
	var maxLength = 165;
	var content = lbb_questions[qindex]['content'];
    if (content.length > maxLength) {
        content = content.substring(0, maxLength - 3) + '...';
        jQuery('.node-question-'+question_id+' .node-description').addClass('lbb-edit-content');
        jQuery('.node-question-'+question_id+' .node-description').attr('contenteditable', false);
    }else{
    	jQuery('.node-question-'+question_id+' .node-description').removeClass('lbb-edit-content');
        jQuery('.node-question-'+question_id+' .node-description').attr('contenteditable', true);
    }
	jQuery('.node-question-'+question_id+' .node-description').html(content);

	if(next == 'next'){
		if(jQuery('#chatflow-properties').hasClass('lbb-edit-only')){
			var data_tab = jQuery('.lbb-change-tabs.lbb-active').attr('data-tab');

			if(data_tab == 'question'){
				jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
				jQuery('.lbb-popup-answers-wrapper').css('display', 'block');
				jQuery('#chatflow-question').removeClass('lbb-active');
				jQuery('#chatflow-answers').addClass('lbb-active');
				jQuery('#lbb-single-back').show();
				jQuery('#lbb-single-next').hide();
			}else if(data_tab == 'answers'){
				jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
				jQuery('.lbb-popup-advancedRule-wrapper').css('display', 'block');
				jQuery('#chatflow-answers').removeClass('lbb-active');
				//jQuery('#chatflow-advancedRule').addClass('lbb-active');
				jQuery('#lbb-single-next').hide();
			}
		}else{
			if(jQuery('#chatflow-basic').hasClass('lbb-active')){
				jQuery('#chatflow-answer').trigger('click');
				jQuery('#lbb-single-back').show();
			}else if(jQuery('#chatflow-answer').hasClass('lbb-active')){
				jQuery('#chatflow-branch').trigger('click');
				jQuery('#lbb-single-back').show();
				jQuery('#lbb-single-next').hide();
			}
		}
	}
	
	scrollToElementByClass('node-question-'+question_id);
	var new_nodes = jQuery('.drawflow .parent-node').length;
	if(next != 'next'){
    	lbbClosePopup();
    	/*if(existing_nodes < new_nodes){
			swal('Please scroll to the right to find the new node.');
		}*/
	}

	
	
	existing_nodes = jQuery('.drawflow .parent-node').length;
	//jQuery('.lbb-save-chatflow').trigger('click');
	lbbAdvanceLogicList(question_id);
	lbbSaveChatflow();

}

function lbbRemoveRuleCondition(question_id,id,index){
	var question = getQuestionById(question_id);
	var ruleIndex = findIndexOfAdvanceRule(question_id,id);
	var qindex = findIndexByQuestion(parseInt(question_id));
	lbb_questions[qindex]['advance_logic'][ruleIndex]['conditions'].splice(index,1);
	jQuery('#rule-'+id+'_'+index).prev('.rule-condition-exp').remove();
	jQuery('#rule-'+id+'_'+index).remove();

}

function lbbRemoveRule(question_id,id){
	var question = getQuestionById(question_id);
	var ruleIndex = findIndexOfAdvanceRule(question_id,id);
	var qindex = findIndexByQuestion(parseInt(question_id));
	lbb_questions[qindex]['advance_logic'].splice(ruleIndex,1);
	jQuery('#ruleif-'+id).remove();
	lbbRemoveNodeOutput(question_id,parseInt(ruleIndex+2));

	if(lbb_questions[qindex]['advance_logic'].length < 1){
		jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').remove();
	}else{
		if(jQuery('.node-question-'+question_id).find('.chat-message-buttons > div.default').length < 1){
			jQuery('.node-question-'+question_id).find('.chat-message-buttons').prepend('<div class="default" data-id="">Default</div>');
		}
	}
}

function lbbValidateRule(){

	var isValid = true;
	lbbHideAllerrors();
	
	if(jQuery(".lbb-branch-condition-fields").length > 0){
		jQuery(".lbb-branch-condition-fields").each(function() {
			var group = jQuery(this);
			var type = group.find('#lbbc_type');
			var value = group.find('#lbbc_value');
			var cfield = group.find('#lbbc_user_property');

			if(type.val() == 'user_property'){
				if(lbbIsEmpty(cfield)){
					showError(group.find('#lbbc_user_property_error'),true);
					isValid = false;
					return false;
				}
			}else if(lbbIsEmpty(value)){
				showError(group.find('#lbbc_value'),true);
				isValid = false;
				return false;
			}
		});
	}

	var sel_quesiton = jQuery('#lbbc_question_map');
	if(lbbIsEmpty(sel_quesiton)){
		showError(sel_quesiton);
		isValid = false;
	}

	return isValid;
}

function lbbSaveLogicRule(question_id,id){

	if(!lbbValidateRule()){
		return false
	}

	var question = getQuestionById(question_id);
	var advance_logic_rule = question.advance_logic;
	var qindex = findIndexByQuestion(parseInt(question_id));
	var ruleIndex = findIndexOfAdvanceRule(question_id,id);

	if(lbb_questions[qindex]['advance_logic'][ruleIndex] == undefined){
		lbb_questions[qindex]['advance_logic'].push({
			"id":id,
			"conditions": [],
			"action_map": 0
		});
		var ruleIndex = findIndexOfAdvanceRule(question_id,id);
		lbbAddNodeOutput(question_id,'Rule'+(ruleIndex+1));
	}

    //if (question && question.type === 'single') {
        var conditions = [];
		
        jQuery('.lbb-branch-condition-fields').each(function(index, element) {

			var fieldWrapper  = jQuery(element);
			var lbbc_type = fieldWrapper.find('#lbbc_type').val();
			var lbbc_operator = fieldWrapper.find('#lbbc_operator').val();
			var lbbc_value = fieldWrapper.find('#lbbc_value').val();
			var user_property = fieldWrapper.find('#lbbc_user_property').val();

			var newObj = {
				"type":lbbc_type,
				"operator": lbbc_operator,
				"value": lbbc_value,
				"user_property": user_property
			};

			if(lbb_questions[qindex]['advance_logic'][ruleIndex]['conditions'][index] != undefined){
				lbb_questions[qindex]['advance_logic'][ruleIndex]['conditions'][index] = newObj;
			}else{
				lbb_questions[qindex]['advance_logic'][ruleIndex]['conditions'].push(newObj);
			}

		});

		var questionMap = jQuery('#lbbc_question_map').val();
		lbb_questions[qindex]['advance_logic'][ruleIndex]['action_map'] = parseInt(questionMap);

		lbbAdvanceLogicList(question_id);
		backToAdvanceRules();
	//}
	
}

function select_chatflow_type(selected_chatflow_type) {
	jQuery('input[name="lbb_meta[_chatflow_type]"][value="'+selected_chatflow_type+'"]').prop('checked', true);
	if(selected_chatflow_type == 'ai_assistant'){
		//jQuery('.create-chatflow-message').html('Ai assistant chat message');
		jQuery('.lbb-funnel-tab').hide();
		jQuery('.ai-assistant-tab').show();
		jQuery('#chatflow_type').val('ai_assistant');
		jQuery('.welcome-message-outer').show();
		jQuery('.liveChatIdleTime').hide();
		jQuery('.livechat-options').hide();
		jQuery('.trained-ai-options').hide();
	}else if(selected_chatflow_type == 'livechat'){
		jQuery('.ai-assistant-tab').hide();
		jQuery('.lbb-funnel-tab').hide();
		//jQuery('.create-chatflow-message').html('Livechat chat message');
		jQuery('#chatflow_type').val('livechat');
		jQuery('.welcome-message-outer').show();
		jQuery('.liveChatIdleTime').show();
		jQuery('.livechat-options').show();
		jQuery('.trained-ai-options').hide();
		jQuery('.lbb-show-for-funnel').hide();
	}else if(selected_chatflow_type == 'logicbot'){
		jQuery('.ai-assistant-tab').hide();
		jQuery('.lbb-funnel-tab').show();
		//jQuery('.create-chatflow-message').html('LogicBot chat message')
		jQuery('#chatflow_type').val('logicbot');
		jQuery('.welcome-message-outer').hide();
		jQuery('.liveChatIdleTime').hide();
		jQuery('.livechat-options').hide();
		jQuery('.trained-ai-options').hide();
		jQuery('.lbb-show-for-funnel').show();
	}else if(selected_chatflow_type == 'trained_ai'){
		jQuery('#chatflow_type').val('trained_ai');
		jQuery('.ai-assistant-tab').hide();
		jQuery('.trained-ai-options').show();

	}else{
		jQuery('.lbb-funnel-tab').show();
		jQuery('.ai-assistant-tab').hide();
		//jQuery('.create-chatflow-message').html('LogicBotlive chat message')
		jQuery('#chatflow_type').val('botlivechat');
		jQuery('.welcome-message-outer').hide();
		jQuery('.liveChatIdleTime').show();
		jQuery('.livechat-options').show();
		jQuery('.trained-ai-options').hide();
		jQuery('.lbb-show-for-funnel').hide();
	}
}

function lbbSaveChatflow(next){
	if(jQuery('input[name="lbb_meta[_chatflow_type]"]:checked').val() == 'trained_ai' || jQuery('input[name="lbb_meta[_chatflow_type]"]:checked').val() == 'ai_assistant'){
		if(jQuery('#lbb_no_api_key').val() == ''){
			jQuery('.no-api-key-block').show();
			scrollToElementById('chatbot-info');
			return false;
		}
	}

	jQuery('.lbb-select-trainedbot').hide();
	if(jQuery('input[name="lbb_meta[_chatflow_type]"]:checked').val() == 'botlivechat' ){
		if(jQuery('.lbb-header-tabs-part ul li:eq(2)').hasClass('active') && jQuery('input[name="lbb_meta[lbb_custom_trained_bot]"]:checked').val() == 'yes' && jQuery('#lbb_select_trained_bot').val() == 0){
			jQuery('.lbb-select-trainedbot').show();
			return false;
		}
	}
	var next_tab = next; 
	var chatflow_type = jQuery('input[name="lbb_meta[_chatflow_type]"]:checked').val();
	//var site_url = jQuery('#site_url').val();

	if(jQuery('.lbb-header-tabs-part ul li:eq(2)').hasClass('active') && jQuery('input[name="lbb_meta[how_to_show]"]:checked').val() == 'minimized'){
		if(jQuery('#where_to_show').val() == '' && jQuery('input[name="lbb_meta[enter_url]"]').val() == ''){
			if(next_tab == 'next'){
				jQuery('.lbb-header-tabs-part ul li:eq(2)').trigger('click');
			}
			swal('Please select the URL of page(s) where you want the bot (popup) to appear.');
		}

		var enter_url = jQuery('input[name="lbb_meta[enter_url]"]').val();
		if(enter_url != null && enter_url !== ""){
			if (lbbIsValidUrl(enter_url)) {
			    
			} else {
				if(next_tab == 'next'){
					jQuery('.lbb-header-tabs-part ul li:eq(2)').trigger('click');
				}
			    swal({content: {element: 'div', attributes: {innerHTML: 'Invalid URL. You have entered: '+ enter_url+' <br/>The URL needs to be in this format: https://yoursite/pagename' }, }});
			    return false;
			}
		}
		
	}

	if(jQuery('.lbb-header-tabs-part ul li:eq(0)').hasClass('active')){
		var chatflow_title = jQuery('.lbb-input-field').val();
		if(chatflow_title == ''){
			scrollToElementById('chatbot-info');
			jQuery('.title-error').css('display', 'block');
			return false;
		}
		if(chatflow_id == ''){
			
			
			jQuery('.title-error').hide();
			var admin_name = jQuery('#lbb_admin_name').val();
			var chatbot_description = jQuery('#lbb_chatbot_description').val();
			lbbShowLoader();
			jQuery('.title-error').remove();
			jQuery.ajax({
				type: 'post',
				url: lbb_ajax.ajaxurl,
				data: {
					'chatflow_title': chatflow_title,
					'admin_name': admin_name,
					'chatbot_description': chatbot_description,
					'chatflow_type': chatflow_type,
					'action': 'save_action_data',
				},
				success: function (response) {
					lbbHideLoader();
					jQuery('.lbb-preview-chatflow').show();
					jQuery('.lbb-chat-start').show();
					response = JSON.parse(response);
					if(response){

						if(response.error != undefined){
							swal(response.error.message);
							return false;
						}
							
						jQuery('input[name="chatflow_id"]').val(response.chatflow_id);
						jQuery('#lbb_assistant_id').val(response.ai_assistant_id);
						chatflow_id = response.chatflow_id;

						jQuery('.copyable-shortcode-text').html('[ListBuildingBot id="'+chatflow_id+'"]');
						//jQuery('.lbb-popup-body-wrapper').html('<iframe class="lbb-iframe-main" src="'+site_url+'?lbb-embed=1&id='+chatflow_id+'" width="100%" height="700" frameborder="0"></iframe>');
						jQuery('.copyable-embed-text').html("&lt;div id='slb-inline-app'&gt;&lt;script type='text/javascript' src='"+site_url+"/?embed=true&amp;chat_id="+ chatflow_id+"'&gt;&lt;/script&gt;&lt;/div&gt;"); 
						jQuery('#lbbIFrame').find('.lbb-iframe-main').attr('src', site_url+"?lbb-embed=1&id="+chatflow_id);
						questions_drawflow = response.questions_drawflow;
						dataToImport = questions_drawflow;
						lbb_editor.import(dataToImport);
						
						lbb_editor.on('nodeCreated', function(id) {
							var node = lbb_editor.getNodeFromId(id);
							var ques_id = node.data.question_id;
							jQuery('#node-' + id).addClass('node-question-'+ques_id);
							jQuery('#node-' + id).attr('data-question',ques_id);
						});

						lbb_editor.on('addReroute', function(id) {
							console.log('addReroute');
						});

						lbb_editor.on('nodeDataChangeded', function(id) {
							console.log('nodeDataChanged');
						});

						lbb_editor.on('click', function(e) {
							//console.log(e);
						});

						lbb_editor.on('connectionStart', function(id) {
							console.log('connectionStart');
						});

						lbb_questions = response.questions;
						lbb_custom_fields = response.custom_fields;
						lbb_tags = response.tags;
						lbb_outcomes = response.outcomes;
						lbb_pdfs = response.pdfs;	

						if(next_tab == 'next'){
							lbb_next_tab();
						}
						var lbb_user_email_body = tinymce.get('lbb_user_email_body').getContent();
						var lbb_admin_email_body = tinymce.get('lbb_admin_email_body').getContent();
						var video_text = tinymce.get('video_text').getContent();
						var lbb_made_with_link = jQuery('#lbb_made_with_link').val();
						var lbb_made_with_link = encodeURIComponent(lbb_made_with_link);
						var lbb_livechat_words = jQuery('#lbb_livechat_words').val();
						
						var jsonData = {
							chatflow_id:chatflow_id,
							total_points:getTotalPoints(),
							chatflow_type : chatflow_type,
							chatflow_title:jQuery('#lbb-chatflow-name').val(),
							questions: lbb_questions,
							drawflow: lbb_editor.export(),
							lbb_user_email_body: lbb_user_email_body,
							lbb_admin_email_body: lbb_admin_email_body,
							lbb_livechat_words: lbb_livechat_words,
							video_text: video_text,
							lbb_made_with_link: lbb_made_with_link,
							settings : jQuery('.lbb-form-settings').serialize()
						};
						save_chatflow_postmeta(jsonData, '');
					}
					
				}
			});
			return false;
		}
		
	}
	var lbb_user_email_body = tinymce.get('lbb_user_email_body').getContent();
	var lbb_admin_email_body = tinymce.get('lbb_admin_email_body').getContent();
	var video_text = tinymce.get('video_text').getContent();
	var lbb_made_with_link = jQuery('#lbb_made_with_link').val();
	var lbb_livechat_words = jQuery('#lbb_livechat_words').val();
	var lbb_made_with_link = encodeURIComponent(lbb_made_with_link);

	var lbb_hide_connection_line = 'N';
	if(jQuery('#lbb_hide_connection_line').prop('checked') == true){
		lbb_hide_connection_line = 'Y';
	}

	var jsonData = {
		chatflow_id:chatflow_id,
		total_points:getTotalPoints(),
		chatflow_type : chatflow_type,
		chatflow_title:jQuery('#lbb-chatflow-name').val(),
		questions: lbb_questions,
		lbb_user_email_body: lbb_user_email_body,
		lbb_admin_email_body: lbb_admin_email_body,
		video_text: video_text,
		lbb_made_with_link: lbb_made_with_link,
		lbb_hide_connection_line: lbb_hide_connection_line,
		lbb_livechat_words: lbb_livechat_words,
		drawflow: lbb_editor.export(),
		settings : jQuery('.lbb-form-settings').serialize()
	};
	
	var how_to_show = jQuery('input[name="lbb_meta[how_to_show]"]:checked').val();
	if(how_to_show == "minimized"){
		var where_to_show = jQuery('#where_to_show').val();
		var enter_url = jQuery('input[name="lbb_meta[enter_url]"]').val();
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'action' : 'lbb_check_pages_in_minimized',
				'where_to_show' : where_to_show,
				'enter_url' : enter_url,
				'chatflow_id' : chatflow_id,
			},
			success: function (response) {
				response = JSON.parse(response);
				if(response){
					lbbHideLoader();
					swal({content: {element: 'div', attributes: {innerHTML: "Sorry this page is already being used in a different bot funnel. Can't use multiple bots on a single page." +response }, }});
				}else{
					save_chatflow_postmeta(jsonData, next_tab);
				}
			}
		});
	}else{
		save_chatflow_postmeta(jsonData, next_tab);
	}

	//save_chatflow_postmeta(jsonData, next_tab);
}
function save_chatflow_postmeta(jsonData, next_tab){
	lbbShowLoader();
	jQuery.ajax({
		url: ajaxurl+'?action=lbb_save_chatflow',
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(jsonData),
		success: function(response) {
			lbbHideLoader();
			if(next_tab == 'next'){
				lbb_next_tab();
			}
		}
	});
}

function deleteChoice(ques_id,id){
	var index = findIndexOfChoice(ques_id,id);
	var ques_index = findIndexByQuestion(parseInt(ques_id));
	lbb_questions[ques_index]['choices'].splice(index,1);
	jQuery('#'+id).remove();
	lbbRemoveNodeOutput(ques_id,parseInt(index+1));
	
}

function lbbAddNewLiveMessage() {
	
	var randomkey = 'id-'+generateUniqueId();
    
    //lbb_questions[index]['choices'].push('');

	var optionsform = jQuery('#lbbLiveChatRepeater').html();

	optionsform = optionsform.replaceAll('%%UKEY%%',randomkey);
	optionsform = optionsform.replaceAll('%%TITLE%%','');
	jQuery('#lbb_livechat_messages').append('<li id="'+randomkey+'">'+optionsform+'</li>');

}

function lbbSaveOptionsForm(){
	
	var options = {
		'lbb_livechat_words' : [],
		'lbb_livechat_connecting_message' : ''
	};

	if(jQuery('.lbb-livechatwords-input').length > 0){
		jQuery('.lbb-livechatwords-input').each(function(index, element) {
			var value = jQuery(this).val();
			options.lbb_livechat_words.push(value);
		});

		jQuery('#lbb_livechat_words').val(options.lbb_livechat_words.join('\n'));
		jQuery('#lbb_livechat_connect_message').val(jQuery('#option-connecting-message').val());
		options.lbb_livechat_connecting_message = jQuery('#option-connecting-message').val();

		lbb_options = options;
	}

	jQuery.ajax({
		url: ajaxurl+'?action=lbb_update_options',
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(options),
		success: function(response) {
			// Handle the response
			if(response.success){
				
			}
		}
	});

	jQuery('#option-propwrap').removeClass('itson');
	jQuery('#option-propwrap').removeClass('expanded');
	jQuery('#option-properties').html('');
	jQuery('#option-properties').removeClass('is-fullmode');
	jQuery('body').removeClass('lbb-popup-opened');
}

function lbbEditOptionsForm(){
	
	var optionsform = jQuery('#lbbOptionsEditForm').html();
	jQuery('#option-properties').html(optionsform);
	jQuery('#option-propwrap').addClass('itson');
	jQuery('#option-propwrap').addClass('expanded');
	jQuery('body').addClass('lbb-popup-opened');
	
	if(lbb_options.lbb_livechat_words.length > 0){
		lbb_options.lbb_livechat_words.forEach(function(obj) {
			
			var ukey = 'id-'+generateUniqueId();
			var optinForm = jQuery('#lbbLiveChatRepeater').html();
			optinForm = optinForm.replaceAll('%%UKEY%%',ukey);
			optinForm = optinForm.replaceAll('%%TITLE%%',obj);
		
			var listItem = jQuery("<li id='"+ukey+"'>").html(optinForm);
			jQuery("#lbb_livechat_messages").append(listItem);

		});
	}

	jQuery('#option-connecting-message').val(lbb_options.lbb_livechat_connecting_message);
}

function lbbAddNewQuestionForm(){
	//alert();
	var questionform = jQuery('#questionTypeSelection').html();
	jQuery('#properties').html(questionform);
	lbbOpenPopup(questionform);
}

function lbbRemoveQuestion(question_id){

	var nodeId = getNodeIdByQuestionId(question_id);
	var index = findIndexByQuestion(parseInt(question_id));
	lbb_questions.splice(index,1);
	lbb_editor.removeNodeId('node-'+nodeId);

	var jsonData = {
		chatflow_id : chatflow_id,
		question_id:question_id,
		drawflow : lbb_editor.export()
	};
	lbbShowLoader();
	jQuery.ajax({
		url: ajaxurl+'?action=lbb_delete_question',
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(jsonData),
		success: function(response) {
			lbbHideLoader();
			// Handle the response
			if(response.success){
				
			}
		}
	});

}

function lbbAddNewDynMsg(ques_id,title = '') {

	var index = findIndexByQuestion(parseInt(ques_id));
	var randomkey = 'dm-'+generateUniqueId();

	var newObj = {
		"id":randomkey,
        "message": ''
    };

	var html = jQuery('#dynamicMessageRepeater').html();

	html = html.replaceAll('{{KEY}}',randomkey);

	jQuery('#lbb-dynamic-message-list').append('<li id="'+randomkey+'">'+html+'</li>');

	var cOptions = getCFieldsDropdown();
	jQuery('#lbb_dm_cf_'+randomkey).html(cOptions);

	var tOptions = getTagsDropdown();
	jQuery('#msg_tag_'+randomkey).append(tOptions);

	jQuery(".js-select2").select2({
		placeholder: "Choose a filter",
		minimumResultsForSearch: -1
	});	
}

function lbbAddNewOutcomeRange(ques_id) {
	var index = findIndexByQuestion(parseInt(ques_id));
	var randomkey = 'or-'+generateUniqueId();
	var html = jQuery('#OutcomeRangeRepeater').html();
	html = html.replaceAll('{{KEY}}',randomkey);
	jQuery('.outcome-range-table tbody').append('<tr id="'+randomkey+'" data-key="'+randomkey+'">'+html+'</tr>');

	var oOptions = getOutcomesDropdown('<option value="">Select Outcome</option>');
	jQuery('#outcome_name_'+randomkey).html(oOptions);
	jQuery("#"+randomkey+ ".js-select2").select2({
		placeholder: "Choose a filter",
		minimumResultsForSearch: -1
	});
}

function llbAddMoreMessage(ques_id){

	var randomkey = 'em-'+generateUniqueId();
	var questionMessagesform = jQuery('#questionMessages').html();
	questionMessagesform = questionMessagesform.replaceAll('{{uid}}',randomkey);
	jQuery('#question-extra-messages').append('<div id="'+randomkey+'">'+questionMessagesform+'</div>');

	var messages = {
		"id":randomkey,
        "message": ''
    };

	var index = findIndexByQuestion(parseInt(ques_id));
	lbb_questions[index]['extra_messages'].push(messages);

	lbbTinyMceMini('question-extra-messages .lbb-tinymce-editor');
}

function lbbAddNewObject(ques_id,title = '') {
	/*if(jQuery('#question-ans-image').prop('checked') == false){
		jQuery('#question-ans-image').prop('checked', true);
	}*/
	var index = findIndexByQuestion(parseInt(ques_id));
	var ques_options = getQuestionDropdown();
	var randomkey = 'id-'+generateUniqueId();
    var newObj = {
		"id":randomkey,
        "title": '',
		'image' : '',
		'tags' : []
    };
    lbb_questions[index]['choices'].push(newObj);
 	var options = getTagsDropdown();
	var answerRepeaterform = jQuery('#answerRepeater').html();
	var defaultImageUrl = lbb_ajax.lbb_path+'admin/images/no-image.png';
	answerRepeaterform = answerRepeaterform.replaceAll('%%UKEY%%',randomkey);
	answerRepeaterform = answerRepeaterform.replaceAll('%%TITLE%%','');
	answerRepeaterform = answerRepeaterform.replaceAll('%%IMAGE%%',defaultImageUrl);
	answerRepeaterform = answerRepeaterform.replaceAll('%%URL%%','');
	answerRepeaterform = answerRepeaterform.replaceAll('%%POINT%%',0);

	jQuery('#objectList').append('<li id="'+randomkey+'">'+answerRepeaterform+'</li>');
	jQuery('#'+randomkey+' .answertype-inf').hide();
	jQuery('#'+randomkey+' .answertype-info-node').show();

	jQuery('#'+randomkey+' .answertype-info-node').show();
	
	jQuery('#tag_'+randomkey).html(options);
	jQuery('#start_ques_'+randomkey).html(ques_options);

	if(jQuery('#question-ans-image').prop('checked')){
		jQuery('.lbb-choice-input-image').addClass('show-image-answer');
		jQuery('.lbb-answer-image-box').show();
	}

	jQuery(".js-select2").select2({
		placeholder: "Choose a filter",
		minimumResultsForSearch: -1
	});	
	
	jQuery('.lbb-field-image-answer').show();
	
	var get_length = jQuery('.lbb-list-answer>li').length;
	get_length = get_length - 1;
	jQuery(".lbb-list-answer .lbb-accordion-single-wrapper .lbb-accordion-heading:eq("+get_length+")").trigger('click');

	var oCOptions = getOutcomesDropdown();
	if(oCOptions){
		jQuery('#outcome_'+randomkey).html(oCOptions);
	}

	jQuery('#ansaction_'+randomkey).val('start_over');
	jQuery('#ansaction_'+randomkey).change();
	//lbbAddNodeOutput(ques_id,title);
    // Update the UI with the new data
    //updateUI(ques_id);
}

function lbbAddNewCondition(question_id, uid) {
	var index = findIndexByQuestion(parseInt(question_id));
	
    var newObj = {
		"type":'',
        "operator": '',
        "value": '',
		"save_property":''
    };

	var conditionForm = jQuery('#questionBranchForm').html();
	var last_div = jQuery('.lbb-branch-condition-fields').length;
	var divId = uid+'_'+(last_div);
	conditionForm = conditionForm.replaceAll('{{uid}}',divId);
	jQuery('#branch-if-editform-conditions').append('<div class="rule-condition-exp">And</div>'+conditionForm);
}

function lbbSaveConditionForRule(){
	
}

function lbbRuleConnectionDraw(question_id){
	jQuery.each(question.advance_logic, function(index, conditions) {

	});
}

function lbbAdvanceLogicList(question_id){

	var question = getQuestionById(question_id);
	jQuery('#branch-if-main').html('');
	var output = jQuery('#branch-if-main');

	var action_template = `
	<div class="lbb-conditions-text">
		<a href="javascript:void(0);" data-uid="{{uid}}" class="lbb-condition-edit lbb-btn lbb-small lbb-btn-primary">Edit</a>
		<a href="javascript:void(0);" data-uid="{{uid}}" class="lbb-condition-delete lbb-btn lbb-small lbb-btn-danger">Delete</a>
	</div>
	`;
	var j = 0;
	try {
		jQuery.each(question.advance_logic, function(index, conditions) {
			var conditionText = "";

			if(j == 0){
				var conditionText1 = '<div class="rule-heading">Rule <span class="lbb-rule-index"></span></div>';
				j++;
			}else{
				var conditionText1 = '<div class="rule-heading">Rule <span class="lbb-rule-index"></span></div>';
			}

			var conditionText = '';
			jQuery.each(conditions.conditions, function(k, condition) {
			
				conditionText += '<div class="rule-single-condition"><span class="rule-condition-text rule-con-response">Response </span><span class="rule-condition-text rule-con-type">' + condition.type + '</span> <span class="rule-condition-text rule-con-operator">' + condition.operator + '</span> <span class="rule-condition-text rule-con-value">' + condition.value + '</span></div>';
				if (k < conditions.conditions.length - 1) {
					conditionText += '<div class="rule-condition-exp">And</div>';
				}
			});
			action_html = action_template.replaceAll('{{uid}}',conditions.id);
			var question_title = getQuestionTitleById(conditions.action_map);
			
			question_label = '<div class="rule-condition-exp"></div><div class="rule-condition-question">'+question_title+'</div>';
			output.append('<div class="if-condition-inner" id="ruleif-'+conditions.id+'">'+conditionText1+'<div class="rule-condition-main">'+conditionText+question_label+action_html+'</div></div>');

			var c_id = index + 2;
			var S_Question = getNodeIdByQuestionId(question_id);
			var E_Question = getNodeIdByQuestionId(conditions.action_map);

			if(jQuery('.node-question-'+question_id).find('.outputs .output_'+c_id).length < 1) {
				lbbAddNodeOutputOnly(question_id);
			}

			lbb_editor.addConnection(S_Question,E_Question,'output_'+c_id,'input_1');

		});
		if(question.type == 'message'){

		}else{
			if(question.type != 'single' ){
				if(question.advance_logic.length < 1){
					jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').remove();
				}else{
					if(jQuery('.node-question-'+question_id).find('.chat-message-buttons > div.default').length < 1){
						jQuery('.node-question-'+question_id).find('.chat-message-buttons').prepend('<div class="default" data-id="">Default</div>');
					}
				}
			}
		}
		

	} catch (error) {
		console.log(error);
	}

}

function lbbAddNode(question_id,input,output,html){

	let maxX = Number.NEGATIVE_INFINITY;
	var data = lbb_editor.drawflow.drawflow.Home.data;
	for (const key in data) {
		if (data.hasOwnProperty(key)) {
			const node = data[key];
			maxX = Math.max(maxX, node.pos_x);
		}
	}

	var pos_x = maxX+350;
	var pos_y = 100;
	lbb_editor.addNode('multiple', input, output, pos_x, pos_y, 'multiple', {'question_id' : question_id}, html );

}

function backToAdvanceRules(){

	jQuery('#branch-if-main-editform').hide();
	jQuery('.adv-logic-main').show();

	jQuery('#lbb-save-question').show();
	jQuery('#lbb-preview-question').show();
	jQuery('#lbb-condition-save-question').hide();
	jQuery('.lbb-popup-container').removeClass('is-fullmode');
}

var main_prompt_text = '';
function lbbGenerateAutoPrompt(val){
	var $thhis = jQuery('#lbb-generate-prompt');
	$thhis.text('Please wait...');
	jQuery.ajax({
		type: 'post',
		url: lbb_ajax.ajaxurl,
		data: {
			'action' : 'lbb_generate_auto_prompt',
			'topic' : val,
		},
		success: function (response) {
			$thhis.text('Generate');
			response = JSON.parse(response);
			if(response.status == 'ok'){
				var content = response.object;
				main_prompt_text = content;
				content = content.replace(/\n/g, '<br>');
				jQuery('.lbb-auto-prompt-contents').html(content);
			}else if(response.status == 'error'){
				swal(response.message);
			}
		}
	});
}


function lbb_notification_count(){
	
	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_notifications_count_heartbeat',
			},
			success: function (response) {

				jQuery('#lbb-ab-icon-top').append('<span class="lbb-count-number">'+response+'</span>');
				
				if(parseInt(response) < 1){
					jQuery('#lbb-ab-icon-top .lbb-count-number').hide();
				}else{
					jQuery('#lbb-ab-icon-top .lbb-count-number').show();
				}
				
			}
	});
}

function lbb_notification_firebase(){
	jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_notifications_firebase',
			},
			success: function (response) {
				
				response = JSON.parse(response);
				for (var i = 0; i < response.length; i++) {
			      var newItemsAdmin = false;
			      var friend_id = response[i].firebase_id;
			    
			    

				if (chatListenersNew[friend_id]) {
				    return; // Listener already set up, no need to do it again
				}

				var chat_db = firebase.database().ref('chat_data/' + friend_id + '/message');
				chat_db.on('child_added', (snapshot, prevChild) => {
					//load_new_admin_child(snapshot);
					/*if (!newItemsAdmin) return;
					chat_val_data = snapshot.val();
					var msg = chat_val_data.msg;
					var sender_id = chat_val_data.sender_id;


					if (typeof siteConfig.notify_notification !== 'undefined' && siteConfig.notify_notification){
				      showNotification(msg, sender_id);
			        }
			        if (typeof siteConfig.notify_play_audio !== 'undefined' && siteConfig.notify_play_audio && siteConfig.notify_notification == false){
						lbb_notification_ding();
			        }*/


				});

				chat_db.on('child_removed', (snapshot) => {
					remove_child(snapshot);
				});

				chat_db.once('value', function(messages) {
				  newItemsAdmin = true;
				});

				// Store the listener in the chatListeners object
			  	chatListenersNew[friend_id] = chat_db;

			  	}


			}
	});
}
var existing_nodes = "";
jQuery(document).ready(function(){
	
lbb_notification_count();

lbbTinyMceMini('privacy-policy .lbb-tinymce-editor');

jQuery(document).on('click', '.lbb-edit-content', function(){
	jQuery(this).parents('.node-card-body').find('.lbb-edit-question').trigger('click');
});

jQuery(document).on('change', 'input[name="lbb_meta[lbb_minimized_type_option]"]', function(){
	var chatflow_type =  jQuery('input[name="lbb_meta[_chatflow_type]:checked"]').val();
	if(chatflow_type == "logicbot" || chatflow_type == "botlivechat"){
		if(jQuery(this).val() == 'show_minimized'){
			jQuery('.lbb-popup-timer').hide('slow');
		}else{
			jQuery('.lbb-popup-timer').show('slow');
		}	
	}
	
});

jQuery(document).on('change', 'input[name="lbb_meta[lbb_first_popout_options]"]', function(){
	if(jQuery(this).val() == 'no'){
		jQuery('.lbb_popout_how_many_seconds').hide('slow');
	}else{
		jQuery('.lbb_popout_how_many_seconds').show('slow');
	}
});

jQuery(document).on('change', 'input[name="lbb_meta[lbb_first_disappear_options]"]', function(){
	if(jQuery(this).val() == 'no'){
		jQuery('.lbb_disappear_how_many_seconds').hide('slow');
	}else{
		jQuery('.lbb_disappear_how_many_seconds').show('slow');
	}
});



jQuery(document).on('click', '.lbb-contacts-details', function(){
	var conversationid = jQuery(this).attr('data-conversationId');
	lbbShowLoader();
	jQuery.ajax({
		type: 'post',
		url: lbb_ajax.ajaxurl,
		data: {
			'action': 'lbb_load_contacts_data',
			'conversationid': conversationid,
		},
		success: function (response) {
			lbbHideLoader();
			response = JSON.parse(response);
			if(response.side_popup_data){
				jQuery('.lbb-popup-body-wrapper').html(response.side_popup_data);
			}
			jQuery('#propwrapContacts').addClass('expanded');
		}
	});

	
});

if (!jQuery('body').hasClass('list-building-bot_page_listbuildingbot-conversation')){
	if (conversations.lbb_livechat_options != 'ajax_based'){
		if (firebaseConfig.length !== 0) {
			jQuery.ajax({
			url: siteConfig.ajaxurl,
				type: "POST",
				dataType: "JSON",
				data: {
					action: "lbb_auth_current_user",
					type: "POST",
					uid: adminConfig.admin_firebase_id,
				},
				success: function(response) {
		            var  token = response.token;
					firebase.auth().signInWithCustomToken(token)
					.then((userCredential) => {
						console.log('Connected');
						//lbb_notification_firebase();
						setInterval(lbb_notification_firebase, 5000);
						
					}).catch((error) => {
					  console.error('Authentication error:', error);
					});
				}
			});
		}
	}
}

	lbbTinyMceMini('lbb-chatboat-video-img-text .lbb-tinymce-editor');
	try {
		var id = document.getElementById("drawflow");
		lbb_editor = new Drawflow(id);
		

		lbb_editor.reroute = true;
		lbb_editor.start();
		lbb_editor.import(dataToImport);
		

		lbb_editor.on('nodeCreated', function(id) {
			var node = lbb_editor.getNodeFromId(id);
			var ques_id = node.data.question_id;
			jQuery('#node-' + id).addClass('node-question-'+ques_id);
			jQuery('#node-' + id).attr('data-question',ques_id);
		});

		lbb_editor.on('addReroute', function(id) {
			console.log('addReroute');
		});

		lbb_editor.on('nodeDataChanged', function(id) {
			console.log('nodeDataChanged');
		});

		lbb_editor.on('click', function(e) {
			//console.log(e);
		});

		lbb_editor.on('connectionStart', function(id) {
			console.log('connectionStart');
			var output_question_id = jQuery("#node-"+id.output_id).find('.lbb-edit-question').attr('data-question_id');
			var input_question_id = jQuery("#node-"+id.input_id).find('.lbb-edit-question').attr('data-question_id');
			
			var output_class = id.output_class;
			var lastInteger = parseInt(output_class.match(/\d+$/)[0]);
			lastInteger = lastInteger - 1;
			lbb_questions.forEach(item => {
				if (item.id == output_question_id && item.choices && item.choices[lastInteger]) {
		        	item.choices[lastInteger].map = input_question_id;
		        	item.choices[lastInteger].repeat_ques = '';
			    }
			});
		});

		lbb_editor.on('connectionCreated', function(id) {
			var output_question_id = jQuery("#node-"+id.output_id).find('.lbb-edit-question').attr('data-question_id');
			var input_question_id = jQuery("#node-"+id.input_id).find('.lbb-edit-question').attr('data-question_id');
			
			var output_class = id.output_class;
			var lastInteger = parseInt(output_class.match(/\d+$/)[0]);
			lastInteger = lastInteger - 1;
			lbb_questions.forEach(item => {
				if (item.id == output_question_id && item.choices && item.choices[lastInteger]) {
		        	item.choices[lastInteger].map = input_question_id;
			    }
			});
		});
		
		const zoomOutCount = 6;
		for (let i = 0; i < zoomOutCount; i++) {
		  lbb_editor.zoom_out();
		}

	} catch (error) {
		
	}
	
	/*jQuery(".drawflow").css({
        transform: "translate(-201px, 250px) scale(0.6)"
    });*/

	jQuery(document).on('click','.lbb-faq-delete',function() {

		var $fthis = jQuery(this);
		lbbConfirmationDialog(
			"Are you sure?", 
			"Are you sure you want to delete this FAQ? Yon won't be able to recover it.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				$fthis.closest('.lbb-faq-item').remove();
			}
		});
		
	});
	
	jQuery(document).on('click','.lbb-save-faq',function() {


		var ffaqs = [];
		var is_valid = true;
		jQuery('.lbb-faq-error').hide();
		jQuery('.lbb-faq-item').each(function() {

			var $this = jQuery(this);

			if($this.find('#faq_question').val() == ''){
				$this.find('.lbb-accordion-content').show();
				$this.find('.lbb-faq-error-question').show();
				is_valid = false;
				return false;
			}

			if($this.find('#faq_answer').val() == ''){
				$this.find('.lbb-accordion-content').show();
				$this.find('.lbb-faq-error-answer').show();
				is_valid = false;
				return false;
			}

			var newObj = {
				"id": $this.find('.lbb_faq_ids').val(),
				"title": $this.find('#faq_question').val(),
				"content": $this.find('#faq_answer').val(),
			};

			ffaqs.push(newObj);
		});

		if(!is_valid){
			return false;
		}

		lbbShowLoader();
		jQuery.ajax({
			url: ajaxurl+'?action=lbb_save_faqs&chatflow_id='+chatflow_id,
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(ffaqs),
			success: function(response) {
				lbbHideLoader();
			}
		});
		/*jQuery('.lbb-faq-ai-preview-form-wrapper .lbb-accordion-item').each(function(){
			var question = jQuery(this).find('input[name="faq_question"]').val();
			var answer = jQuery(this).find('textarea[name="faq_answer"]').val();
			console.log(question);
			console.log(answer);
		});*/
	});

	jQuery(document).on('click','.read-how-work',function() {
		jQuery('.lbb-read-more-popup').addClass('expanded');
	});

	jQuery(document).on('change','#question-ans-image',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.answer-image-options').show('slow');
		}else{
			jQuery('.answer-image-options').hide('slow');
		}
	});
	jQuery(document).on('click','input[name="lbb_meta[allow_users_to_contact]"]',function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.allow-users-to-contact').show('slow');
		}else{
			jQuery('.allow-users-to-contact').hide('slow');
		}
	});

	jQuery(document).on('click','input[name="lbb_meta[lbb_custom_trained_bot]"]',function() {
		jQuery('.lbb-no-trainedbot').hide();
		if(jQuery(this).val() == 'yes'){
			if(jQuery('input[name="lbb_check_for_trained_bot"]').val() == 'N'){
				jQuery('.lbb-no-trainedbot').show();
				jQuery('input[name="lbb_meta[lbb_custom_trained_bot]"][value="no"]').prop('checked', true);
				return false;
			}
			jQuery('.lbb-select-trained-bot-outer').show('slow');
		}else{
			jQuery('.lbb-select-trained-bot-outer').hide('slow');
		}
	});	

	jQuery(document).on('click','.lbb-delete-multiple-message',function() {
		var id = jQuery(this).attr('data-id');
		var question_id = jQuery('#lbb_question_id').val();
		deleteExtraMessage(question_id,id);
	});

	jQuery(document).on('click','.create_outcome',function() {
		var outcome_name = jQuery(this).closest('.create-outcome-section').find('input[name="save_outcome"]').val();
		var outcome_id = jQuery(this).closest('.lbb-outcome-outer-section').find('select').attr('id');
		var parent_obj = jQuery(this).closest('.lbb-outcome-outer-section');
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'save_load_outcomes_data',
				'lbb_outcomes_name': outcome_name,
				'lbb_chatflow_id' : chatflow_id
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);

				if(response.samename){
					parent_obj.find('.save_outcome').val();
					jQuery('.outcome-error-message').show();
					setTimeout(function(){
						jQuery('.outcome-error-message').hide();
					},2000 );
					return false;
				}

				if(response){
					parent_obj.find('.save_outcome').val();
					parent_obj.find('select').remove();
					jQuery('.outcome-save-message').show();
					setTimeout(function(){
						jQuery('.outcome-save-message').hide();
					},2000 );
					parent_obj.find('.outcome-section-outer').append('<select id="'+outcome_id+'" name="outcome[]" class="lbb-input-field js-select2" multiple>'+response.load_outcome_data+'</select>');
					jQuery(".js-select2").select2({
						placeholder: "Choose a filter",
						minimumResultsForSearch: -1
					});	
					lbb_outcomes.push(response.outcomedata);
					jQuery('.lbb-outcome-outer-section select').append('<option value="'+response.outcomes_id+'">'+outcome_name+'</option>');
				}
			}
		});
	});

	jQuery(document).on('click','#lbb-single-next',function() {
		var question_id = jQuery('#lbb_question_id').val();
		lbbSaveQuestion(question_id, 'next');
	});

	jQuery(document).on('click','.lbb-add-n-answer',function() {
		var question_id = jQuery(this).closest('.drawflow-node').attr('data-question');
		jQuery('.node-question-'+question_id+' .lbb-edit-question').trigger('click');

		/*setTimeout(() => {
			jQuery('#chatflow-answer').trigger('click');
		}, 500);*/
	});
	

	jQuery(document).on('click','.lbb-see-example',function() {
		var src = jQuery(this).attr('data-src');
		var modal_see_example = jQuery('#lbbSeeExample').html();
		jQuery('#quick-edit-form-main').html(modal_see_example);
		jQuery('#lbb-modal-see-example').attr('src',src);	
	});
	
	jQuery(document).on('click','#lbb-single-back',function() {

		if(jQuery('#chatflow-properties').hasClass('lbb-edit-only')){
			/*if(jQuery('#chatflow-advancedRule').hasClass('lbb-active')){
				jQuery('#chatflow-answer').trigger('click');
				jQuery('#lbb-single-next').show();
			}else if(jQuery('#chatflow-answer').hasClass('lbb-active')){
				jQuery('#chatflow-question').trigger('click');
				jQuery('#lbb-single-back').hide();
			}*/
			var data_tab = jQuery('.lbb-change-tabs.lbb-active').attr('data-tab');

			if(data_tab == 'advancedRule'){
				jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
				jQuery('.lbb-popup-answers-wrapper').css('display', 'block');
				//jQuery('#chatflow-advancedRule').removeClass('lbb-active');
				jQuery('#chatflow-answers').addClass('lbb-active');
				jQuery('#lbb-single-next').show();
			}else if(data_tab == 'answers'){
				jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
				jQuery('.lbb-popup-question-wrapper').css('display', 'block');
				jQuery('#chatflow-answers').removeClass('lbb-active');
				jQuery('#chatflow-question').addClass('lbb-active');
				jQuery('#lbb-single-back').hide();
				jQuery('#lbb-single-next').show();
			}

		}else{
			if(jQuery('#chatflow-branch').hasClass('lbb-active')){
				jQuery('#chatflow-answer').trigger('click');
				jQuery('#lbb-single-back').show();
				jQuery('#lbb-single-next').show();
			}else if(jQuery('#chatflow-answer').hasClass('lbb-active')){
				jQuery('#chatflow-basic').trigger('click');
				jQuery('#lbb-single-back').hide();
				jQuery('#lbb-single-next').show();
			}
		}

		
	});

	jQuery(document).on('change','#dynamic-message-status',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.lbb-add-new-dynamic-message').show('slow');
			if(jQuery('#lbb-dynamic-message-list li').length == 0){
				jQuery('#add-dynamic-message-rule').trigger('click');
			}
		}else{
			jQuery('.lbb-add-new-dynamic-message').hide('slow');
		}
	});

	jQuery(document).on('click','.lbb-edit-question', function(){
	    jQuery('#propwrap').addClass('itson');
	    jQuery('#propwrap').addClass('expanded');
		jQuery('body').addClass('lbb-popup-opened');
		var questionEditform = jQuery('#questionEditForm').html();
		jQuery('#properties').html(questionEditform);
		var question_id = jQuery(this).attr('data-question_id');
		jQuery('#lbb_question_id').val(question_id);
		lbbEditQuestionForm(question_id);
		existing_nodes = jQuery('.drawflow .parent-node').length;
		jQuery("#objectList").sortable({
	        update: function(event, ui) {
	        	var qindex = findIndexByQuestion(parseInt(question_id));
	            

        var choices = [];

        jQuery('.lbb-choice-input').each(function(index, element) {
            var key = jQuery(this).attr('data-key');
            if(key != 0){
            	var value = jQuery(this).val();
				var image = jQuery('#image_upload_'+key).val();
				var point = jQuery('#point_'+key).val();

				var tagValArray = jQuery("#tag_"+key).val();
				var tagValue = '';
				if(tagValArray.length){
					tagValue = tagValArray.join(',');
				}

				var outcomeValArray = jQuery("#outcome_"+key).val();
				var outcomeValue = '';
				if(outcomeValArray.length){
					outcomeValue = outcomeValArray.join(',');
				}

				var answer_action = jQuery("#ansaction_"+key).val();
				var url = '';
				if(answer_action == 'url'){
					url = jQuery("#url_"+key).val();
				}

				var diff_chatflow_id = "";
				var diff_question_id = "";
				if(answer_action == 'different_bot'){
					diff_chatflow_id = jQuery("#start_bot_"+key).val();
					diff_question_id = jQuery("#start_message_"+key).val();
				}

				var start_ques = jQuery("#start_ques_"+key).val();

	            var answer = getChoiceById(question_id, key);

	            if (answer) {
	                answer.title = value;
	                answer.image = image;
	                answer.tag = tagValue;
	                answer.outcome = outcomeValue;
	                answer.answer_action = answer_action;
	                answer.url = url;
	                answer.point = point;
	                answer.repeat_ques = start_ques;
	                answer.diff_chatflow_id = diff_chatflow_id;
	                answer.diff_question_id = diff_question_id;

					//answer.livechat = jQuery('#connect_to_agent_'+key).prop('checked') ? 1 : 0;
	                choices.push(answer);
	            }

	            /*if (jQuery('.node-question-' + question_id).find('.chat-message-buttons > div').eq(index).length < 1) {
	                lbbAddNodeOutput(question_id, value, answer.id);
	            }else{
					jQuery('.node-question-'+question_id).find('.chat-message-buttons > div').eq(index).find('.quick-input-choice').val(value);
				}*/

				var anstype = jQuery('#ansaction_'+key).val();
				if(anstype == 'start_over'){
					var next_question_id = jQuery('#start_ques_'+answer.id).val();
					output_index  = findIndexOfChoice(question_id,answer.id);
					//console.log(output_index);
					output_index = parseInt(output_index) + 1;
					/*if(lbb_questions.length > 0) {
						lbb_questions.forEach(question => {
							try{
								removeNodeConnection(question_id,question.id,output_index);
							} catch (error) {
								
							}
						});
					}
					setNondeConnection(question_id, next_question_id, output_index);*/
					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.removeClass('lbb-different-bot');
						}
					});
				}else if(anstype == 'different_bot'){
					output_index  = findIndexOfChoice(question_id,answer.id);
					output_index = parseInt(output_index) + 1;
					/*lbb_questions.forEach(question => {
						try{
							removeNodeConnection(question_id,question.id,output_index);
						} catch (error) {
							
						}
					});*/

					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.addClass('lbb-different-bot');
						}
					});
				}else{
					jQuery('.llb-chatflow-quick-action').each(function() {
						var $element = jQuery(this);
						if ($element.attr('data-id') == answer.id && $element.attr('data-question_id') == question_id) {
						    $element.removeClass('lbb-different-bot');
						}
					});
				}
            }
            
        });
        
        if (lbb_questions[qindex]) {
            lbb_questions[qindex]['choices'] = choices;
        }


		if(lbb_questions[qindex]['choices'].length > 0) {
			jQuery('.node-question-'+question_id).find('.lbb-no-answer').hide();
		}else{
			jQuery('.node-question-'+question_id).find('.lbb-no-answer').show();
		}

    

	        }
	    });
		
	});

	jQuery(document).on('click','#lbb-save-outcome', function(){
		lbbCreateNewOutcome();
	});

	jQuery(document).on('click','#lbb-save-customfield', function(){
		lbbCreateNewCustomfield();
	});

	jQuery(document).on('click','#slb-hid-close', function(){
		lbb_close_mini('outcome');
	});

	jQuery(document).on('click','#slb-close-customfield', function(){
		lbb_close_mini('customfield');
	});
	
	jQuery(document).on('click','.create-new-outcome', function(){
		lbbCreateOutcome();
	});

	jQuery(document).on('click','.create-new-customfield', function(){
		lbbCreateCustomfield();
	});

	jQuery(document).on('click','.lbb-menu-icon-container', function(){
		jQuery('.llb-chatflow-quick-action').removeClass('lbb-active');
		jQuery(this).closest('.llb-chatflow-quick-action').addClass('lbb-active');
	});

	jQuery(document).on('click','.lbb-new-node', function(){
		jQuery('.lbb-add-new-question').trigger('click');
	});
	jQuery(document).on('click','.lbb-next-action', function(){
		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var question_id = $this.attr('data-question_id');
		var answer_id = $this.attr('data-id');
		lbbEditQuickForm(question_id,answer_id);
	});

	jQuery(document).on('click','.lbb-assign-tag', function(){
		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var question_id = $this.attr('data-question_id');
		var answer_id = $this.attr('data-id');
		lbbEditQuickTagForm(question_id,answer_id);
	});

	jQuery(document).on('click','.lbb-edit-answer', function(){
		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var question_id = $this.attr('data-question_id');
		var answer_id = $this.attr('data-id');
		jQuery('.node-question-'+question_id+' .lbb-edit-question').trigger('click');
 		existing_nodes = jQuery('.drawflow .parent-node').length;
		setTimeout(() => {
			jQuery('.lbb-form-group-sub-tab .lbb-change-tabs').removeClass('lbb-active');
			jQuery('#chatflow-answers').addClass('lbb-active');
			jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
			jQuery('.lbb-popup-answers-wrapper').css('display', 'block');
			var a_index = findIndexOfChoice(question_id,answer_id);
			jQuery(".lbb-list-answer .lbb-accordion-single-wrapper .lbb-accordion-heading:eq("+a_index+")").trigger('click');
			jQuery('#lbb-single-next').hide();
		}, 500);
	});

	jQuery(document).on('click','.lbb-map-outcome', function(){
		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var question_id = $this.attr('data-question_id');
		var answer_id = $this.attr('data-id');
		jQuery('.node-question-'+question_id+' .lbb-edit-question').trigger('click');
		setTimeout(() => {
			jQuery('.lbb-form-group-sub-tab .lbb-change-tabs').removeClass('lbb-active');
			jQuery('#chatflow-answers').addClass('lbb-active');
			jQuery('.lbb-popup-tab-wrappers').css('display', 'none');
			jQuery('.lbb-popup-answers-wrapper').css('display', 'block');
			var a_index = findIndexOfChoice(question_id,answer_id);
			jQuery(".lbb-list-answer .lbb-accordion-single-wrapper .lbb-accordion-heading:eq("+a_index+")").trigger('click');
			//scrollToElementById('outcome_'+answer_id);
			jQuery('.lbb-popup-body-wrapper').animate({
		        scrollTop: jQuery('.outcome_'+answer_id).offset().top
		    }, 2000);
		}, 500);
	});

	jQuery(document).on('click','.lbb-quiz-chatflow-save', function(){
		var question_id = jQuery('#lbb-quick-questionid').val();
		var answer_id = jQuery('#lbb-quick-answerid').val();
		saveQuickForm(question_id,answer_id);

		var anstype = jQuery('.lbb-answer-action').val();
		if(anstype == 'start_over'){
			var next_question_id = jQuery('#start_ques_'+answer_id).val();
			output_index  = findIndexOfChoice(question_id,answer_id);
			output_index = parseInt(output_index) + 1;
			if(lbb_questions.length > 0) {
				lbb_questions.forEach(question => {
					try{
						removeNodeConnection(question_id,question.id,output_index);
					} catch (error) {
						
					}
				});
			}
			setNondeConnection(question_id, next_question_id, output_index);
			jQuery('.llb-chatflow-quick-action').each(function() {
				var $element = jQuery(this);
				if ($element.attr('data-id') == answer_id && $element.attr('data-question_id') == question_id) {
				    $element.removeClass('lbb-different-bot');
				}
			});
		}else if(anstype == 'different_bot'){
			output_index  = findIndexOfChoice(question_id,answer_id);
			output_index = parseInt(output_index) + 1;
			var chatflow_id = jQuery('.lbb-bot-start-ques').val();
			if(chatflow_id == ""){
				jQuery('.lbb-bot-error').show();
				return false;
			}
			var message_id = jQuery('.lbb-bot-start-ques_message').val();
			if(message_id == ""){
				jQuery('.lbb-message-error').show();
				return false;
			}
			lbb_questions.forEach(question => {
				try{
					removeNodeConnection(question_id,question.id,output_index);
				} catch (error) {
					
				}
			});

			jQuery('.llb-chatflow-quick-action').each(function() {
				var $element = jQuery(this);
				if ($element.attr('data-id') == answer_id && $element.attr('data-question_id') == question_id) {
				    $element.addClass('lbb-different-bot');
				}
			});

			jQuery('.lbb-bot-error').hide();
			jQuery('.lbb-message-error').hide();
		}else{
			jQuery('.llb-chatflow-quick-action').each(function() {
				var $element = jQuery(this);
				if ($element.attr('data-id') == answer_id && $element.attr('data-question_id') == question_id) {
				    $element.removeClass('lbb-different-bot');
				}
			});
		}
		

		jQuery('#quick-edit-form-main').html('');
	});
	jQuery(document).on('click','.lbb-quiz-tag-chatflow-save', function(){
		var question_id = jQuery('#lbb-quick-questionid').val();
		var answer_id = jQuery('#lbb-quick-answerid').val();
		saveQuickTagForm(question_id,answer_id);
		jQuery('#quick-edit-form-main').html('');
	});
	

	jQuery(document).on('click','.lbb-quick-close', function(){
		jQuery('#quick-edit-form-main').html('');
	});
	
  
	jQuery(document).on('click', function(event) {
	// Check if the click event was triggered from outside of the popup
	if (!jQuery(event.target).closest('.llb-chatflow-quick-action').length && jQuery('.llb-chatflow-quick-action').hasClass('lbb-active')) {
		// If the click event was triggered from outside of the popup, hide the popup
		jQuery('.llb-chatflow-quick-action').removeClass('lbb-active');
	}
	});	
	
	jQuery(document).on('change','#outcome-points-status',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.lbb-outcome-field').show();
			if(jQuery('.outcome-range-table tbody td').length == 0){
				jQuery('#add-outcome-range').trigger('click');
			}
		}else{
			jQuery('.lbb-outcome-field').hide();
		}
	});

	/*jQuery(document).on('change','#custom-placeholder',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.lbb-question-field-placeholder').show();
		}else{
			jQuery('.lbb-question-field-placeholder').hide();
		}
	});*/

	jQuery(document).on('change','input[name="lbb_meta[lbb_back_button]"]',function() {
		if(jQuery(this).val() == 'yes'){
			jQuery('.back-button-outer').show();
		}else{
			jQuery('.back-button-outer').hide();
		}
	});

	jQuery(document).on('change','#enable_pdf_download',function() {
		if(jQuery(this).prop('checked') == true){
			jQuery('.download-pdf-section').show();
		}else{
			jQuery('.download-pdf-section').hide();
		}
	});
	
	jQuery(document).on('change','#lbb_outcome_id',function() {
		var outcome_id = jQuery(this).val();
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'outcome_id': outcome_id,
				'chatflow_id': chatflow_id,
				'action': 'load_mapping_data'
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response.pdf_id){
					jQuery('#lbb_pdf_id').val(response.pdf_id).trigger('change');
				}else{
					jQuery('#lbb_pdf_id').val('0').trigger('change');
				}
			}
		});
	});

	jQuery(document).on('click','#lbb-save-mapping',function() {
		var pdf_id = jQuery('#lbb_pdf_id').val();
		var outcome_id = jQuery('#lbb_outcome_id').val();
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: {
				'pdf_id': pdf_id,
				'outcome_id': outcome_id,
				'chatflow_id': chatflow_id,
				'action': 'save_mapping_data'
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				
			}
		});

	});
	
	
	
	jQuery(document).on('click','#lbb-connect-pdf-outcome',function() {
		var question_id = jQuery('#lbb_question_id').val();
		var outcome_id = jQuery('#lbb_outcome_id').val();
		var pdf_id = jQuery('#lbb_pdf_id').val();

		if(outcome_id == ''){
			swal('Please select an outcome');
			return false;
		}

		if(pdf_id == ''){
			swal('Please select a PDF');
			return false;
		}
		
		lbbOutcomePDFMap(outcome_id, pdf_id);
	});

	jQuery(document).on('click','.lbb-remove-outcome-pdf',function() {
		var index = jQuery(this).attr('data-index');
		var question_id = jQuery('#lbb_question_id').val();
		removeOutcomePDFMap(question_id, index);
		updateMapList(question_id);
	});

	jQuery(document).on('click','#lbb-save-question',function() {
		var question_id = jQuery('#lbb_question_id').val();
		lbbSaveQuestion(question_id);
	});

	jQuery(document).on('click','.lbb-save-chatflow',function() {
		lbbSaveChatflow();
	});

	jQuery(document).on('click','.open-option-form',function() {
		lbbEditOptionsForm();
	});

	jQuery(document).on('click','#lbb-save-options',function() {
		lbbSaveOptionsForm();
	});

	jQuery(document).on('click','.lbb-add-new-rep',function() {
		jQuery('#addButton').trigger('click');
		jQuery('.lbb-no-answers-found').remove();
		jQuery('.lbb-add-new-obj').show();
		jQuery('.answer-per-row').show();
		jQuery('#lbb_img_answer_button_row_column').val('1').trigger('change');
	});
	

	jQuery(document).on('click','.lbb-change-tab',function() {
		var tab = jQuery(this).attr('data-tab');
		jQuery('.lbb-popup-tab-wrapper').css('display', 'none');
		jQuery('.lbb-popup-'+tab+'-wrapper').css('display', 'block');

		if(tab == 'answer'){
			jQuery('#lbb-single-next').hide();
			jQuery('#lbb-single-back').show();
		}else{
			jQuery('#lbb-single-next').show();
			jQuery('#lbb-single-back').hide();
		}
		jQuery('.lbb-change-tab').removeClass('lbb-active');
		jQuery(this).addClass('lbb-active');
		
	});

	jQuery(document).on('click','.lbb-condition-delete',function() {
		
		var $this = jQuery(this);
		lbbConfirmationDialog(
			"Are you sure?", 
			"If you choose to delete this question, it will be removed. However, please note that saving the chatbot is required.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				var id =$this.attr('data-uid');
				var ques_id = jQuery("#lbb_question_id").val();
				lbbRemoveRule(ques_id,id);
			}
		});
		
	});

	jQuery(document).on('click','.lbb-delete-dynamic-message',function() {
		var key = jQuery(this).attr('data-id');
		jQuery('#'+key).remove();
		if(jQuery('#lbb-dynamic-message-list li').length == 0){
			jQuery('#dynamic-message-status').prop('checked', false);
		}
	});
	jQuery(document).on('click','.lbb-delete-outcome-range',function() {
		var key = jQuery(this).attr('data-id');
		jQuery('#'+key).remove();
	});
	
	jQuery(document).on('click','.lbb-delete-single-condition',function() {
		
		//var id = jQuery(this).attr('data-uid');
		var ques_id = jQuery("#lbb_question_id").val();
		var cDivId = jQuery(this).closest('.lbb-branch-condition-fields').attr('id');
		cDivId = cDivId.replace('rule-','');
		var parts = cDivId.split('_'); // Split the string at the underscore
		var index = parts[1]; 
		var id = parts[0];
		lbbRemoveRuleCondition(ques_id,id,index);
	});

	jQuery(document).on('click','.lbb-condition-edit',function() {
		jQuery('#branch-if-main-editform').show();
		jQuery('.adv-logic-main').hide();
		jQuery('#lbb-single-next').hide();
		jQuery('#lbb-single-back').hide();
		jQuery('#lbb-save-question').hide();
		jQuery('#lbb-preview-question').hide();
		jQuery('#lbb-condition-save-question').show();

		jQuery('.lbb-popup-container').addClass('is-fullmode');
		
		var isNew = jQuery(this).attr('data-isnew');
		if(isNew == 1) {
			var id = 'r'+generateUniqueId();
		}else{
			var id = jQuery(this).attr('data-uid');
		}

		//var id = jQuery(this).attr('data-uid');
		var ques_id = jQuery("#lbb_question_id").val();
		jQuery('#lbb-condition-save-question').attr('data-id',id);
		lbbEditAdvanceLogicForm(ques_id, id);
	});

	jQuery(document).on('click','#lbb-condition-save-question',function() {
		var id = jQuery(this).attr('data-id');
		var ques_id = jQuery("#lbb_question_id").val();
		lbbSaveLogicRule(ques_id,id);
	});
	

	jQuery(document).on('click','#lbb-condition-back-question',function() {
		backToAdvanceRules();
	});
	
	jQuery(document).on('change', 'select[name="lbbc_type"]', function() {
		var user_property = jQuery(this).closest('.lbb-branch-condition-fields').find('.lbb-user-property-field');
		if(jQuery(this).val() == 'user_property'){
			user_property.addClass('show-save-property');
		}else{
			user_property.removeClass('show-save-property');
		}
	});	

	jQuery(document).on('change', 'select[name="dm_type"]', function() {
		var tag_type = jQuery(this).closest('.dynamic-message-data').find('.lbb-tag-dynamic-message-field');
		var cf_type = jQuery(this).closest('.dynamic-message-data').find('.lbb-cf-dynamic-message-field');
		if(jQuery(this).val() == 'tags'){
			tag_type.addClass('show-tag-property');
			cf_type.removeClass('show-cf-property');
		}else{
			tag_type.removeClass('show-tag-property');
			cf_type.addClass('show-cf-property');
		}
	});	

	jQuery(document).on('change', '.lbb-answer-action', function() {

		var par = jQuery(this).closest('.lbb-choice-main');
		var val = jQuery(this).val();
		par.find('.answertype-inf').hide();
		if(val != ''){
			par.find('.answertype-info-'+val).show();
		}else{
			par.find('.answertype-info-node').show();
		}

		if(val == 'url'){
			par.find('.lbb-choice-input.lbb-field-url').attr('placeholder','Url');
		}else{
			par.find('.lbb-choice-input.lbb-field-url').attr('placeholder','Title');
		}
		jQuery('.lbb-different-bot-message').hide();
		if(val == 'different_bot'){
			lbbShowLoader();
			jQuery.ajax({
				type: 'POST',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'lbb_load_chatflows',
				},
				success: function (response) {
					lbbHideLoader();
					response = JSON.parse(response);
					if(response){
						jQuery('.lbb-bot-start-ques').html(response);
					}
				}
			});
			jQuery('.lbb-different-bot-message').show();
		}

	});
	

	jQuery(document).on('change', '.lbb-bot-start-ques', function() {
		var chatflow_id = jQuery(this).val();
		if(chatflow_id == ''){
			jQuery('.answertype-info-different_bot_message').hide();
			return false;
		}
		lbbShowLoader();
		jQuery.ajax({
			type: 'POST',
			url: lbb_ajax.ajaxurl,
			data: {
				'action': 'lbb_load_questions',
				'chatflow_id': chatflow_id,
			},
			success: function (response) {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response){
					jQuery('.answertype-info-different_bot_message').show();
					jQuery('.lbb-bot-start-ques_message').html(response);
				}
			}
		});
	});

	jQuery(document).on('change', '#question-ans-image', function() {
		var user_property = jQuery(this).prop('checked');
		if(jQuery(this).prop('checked')){
			jQuery('.lbb-list-answer').removeClass('lbb-ans-no-image');
			jQuery('.lbb-choice-input-image').addClass('show-image-answer');
			jQuery('.lbb-answer-image-box').show();
		}else{
			jQuery('.lbb-list-answer').addClass('lbb-ans-no-image');
			jQuery('.lbb-choice-input-image').removeClass('show-image-answer');
			jQuery('.lbb-answer-image-box').hide();
		}
	});	


	
	jQuery(document).on('click','#branch-if-add-condition',function() {
		var id = jQuery(this).attr('data-uid');
		var ques_id = jQuery("#lbb_question_id").val();
		lbbAddNewCondition(ques_id,id);
	});
	
	jQuery(document).on('click','#lbb_livechat_addnew',function() {
        lbbAddNewLiveMessage();
    });


	jQuery(document).on('click','#add-dynamic-message-rule',function() {
		var ques_id = jQuery("#lbb_question_id").val();
		lbbAddNewDynMsg(ques_id);
		var get_length = jQuery('#lbb-dynamic-message-list>li').length;
		get_length = get_length - 1;
		jQuery("#lbb-dynamic-message-list .lbb-accordion-single-wrapper .lbb-accordion-heading:eq("+get_length+")").trigger('click');
		lbbTinyMceMini('lbb-dynamic-message-list .lbb-tinymce-editor');
	});

	jQuery(document).on('click','#add-outcome-range',function() {
		var ques_id = jQuery("#lbb_question_id").val();
		lbbAddNewOutcomeRange(ques_id);
	});

	
	jQuery(document).on('click','#lbb-add-more-question',function() {
		var ques_id = jQuery("#lbb_question_id").val();
        llbAddMoreMessage(ques_id);
    });
	jQuery(document).on('click','#addButton',function() {
		var ques_id = jQuery("#lbb_question_id").val();
        lbbAddNewObject(ques_id);

        // Clear the input fields
        //jQuery("#titleInput").val("");
    });

	jQuery(document).on('click','.lbb-delete-choice',function() {

		var $this = jQuery(this);
		lbbConfirmationDialog(
			"Are you sure?", 
			"If you choose to delete this question, it will be removed. However, please note that saving the chatbot is required.", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {
				var ques_id = jQuery("#lbb_question_id").val();
				var id = $this.attr('data-key');
				deleteChoice(ques_id,id);
			}
		});
		
	});

	jQuery(document).on('click','.lbb-clone-choice',function(e) {
		e.stopPropagation();
		var $this = jQuery(this);
		var answer_id = $this.attr('data-key');
		var question_id = jQuery('#lbb_question_id').val();
		cloneAnswer(question_id,answer_id);
	});

	jQuery(document).on('click','.lbb-clone-answer',function() {
		var $this = jQuery(this).closest('.llb-chatflow-quick-action');
		var answer_id = $this.attr('data-id');
		var question_id = $this.attr('data-question_id');
		cloneAnswer(question_id,answer_id,true);
		jQuery('.chatflow-quick-action').removeClass('lbb-active');
	});

	jQuery(document).on('blur','.node-description',function() {
		var question_id = jQuery(this).attr('data-question_id');
		var values = jQuery(this).html();
		var index = findIndexByQuestion(parseInt(question_id));
		lbb_questions[index]['content'] = values;
	});

	jQuery(document).on('blur','.quick-input-choice',function() {
		var $this = jQuery(this).parent();
		var question_id = $this.attr('data-question_id');
		var values = jQuery(this).val();
		var index = findIndexByQuestion(parseInt(question_id));
		var answer_id = $this.attr('data-id');
		var a_index = findIndexOfChoice(question_id,answer_id);
		lbb_questions[index]['choices'][a_index]['title'] = values;
	});

	jQuery(document).on('click','.lbb-delete-livechatword',function() {

		var $this = jQuery(this);
		/*lbbConfirmationDialog(
			"Are you sure?", 
			"", 
			"Yes, I am sure!", 
			"No, cancel it!").then(function(isConfirm) {
			if (isConfirm) {*/
				var id = $this.attr('data-key');
				jQuery('#'+id).remove()
			//}
		//});
		
	});
	

	

	/*jQuery(document).on('click','.add-drawflow-node', function () {
		lbbAddNode(100,1,3,'<div class="action-node-wrapper"> <div class="action-node"> <div role="button" aria-disabled="false" class="clickable-node ui-clickable" tabindex="0"> <div class="node-card-wrapper"> <div class="node-card-body"> <h5 class="node-tab-heading"> Question title here </h5><div class="node-content-area-main"> <div class="node-content-area"> <p class="node-description"> Question 2 content here.. </p> </div> <div class="chat-message-buttons"> <a href="#" class="">Option 1</a> <a href="#" class="">Option 2</a> <a href="#" class="">Option 3</a> </div> </div> </div> </div> </div> </div> </div>');
	});*/

	var mediaUploader;
	jQuery(document).on('click', '.lbb-remove-media', function() {
		var target = jQuery(this).data('target-input');
		jQuery(target).val('');
		var defaultImageUrl = lbb_ajax.lbb_path+'admin/images/no-image.png';
		jQuery(target+'_src').attr('src',defaultImageUrl).addClass('lbb-no-img-found');
	});
	jQuery(document).on('click', '.lbb-select-media', function() {
		var data = jQuery(this);
		var targetInputSelector = data.data('target-input'); // Get the data attribute for target input
		
		mediaUploader = null;

		//if (!mediaUploader) {
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: false
			});

			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				jQuery(targetInputSelector).val(attachment.url);
				jQuery(targetInputSelector+'_src').attr('src',attachment.url).removeClass('lbb-no-img-found');
			});
		//}

		mediaUploader.open();
	});


	jQuery("#listbuildingbot-form").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#listbuildingbot-form').serialize(),
			success: function () {
				lbbHideLoader();
				response = JSON.parse(response);
				if(response != 0){
					alert("Data Saved!");
				}
			}
		});
	});

	jQuery("#editListbuildingbot").submit(function(e) {
		e.preventDefault();
		lbbShowLoader();
		jQuery.ajax({
			type: 'post',
			url: lbb_ajax.ajaxurl,
			data: jQuery('#editListbuildingbot').serialize(),
			success: function () {
				lbbHideLoader();
				/*response = JSON.parse(response);
				if(response != 0){
					alert("Data Saved!");
				}*/
			}
		});
	});

    jQuery("#listbuildingbot-form").submit(function(e) {
        e.preventDefault();
     	var site_url = jQuery('#site_url').val();
     	lbbShowLoader();
        jQuery.ajax({
            type: 'post',
            url: lbb_ajax.ajaxurl,
            data: jQuery('#listbuildingbot-form').serialize(),
            success: function (response) {
            	lbbHideLoader()
                response = JSON.parse(response);
                if(response != 0){
                    window.location.href = site_url+"/wp-admin/admin.php?page=listbuildingbot&action=edit&id="+response;
                }
            }
        });
    });

	// Show the first tab and hide the rest
	jQuery('#tabs-nav li:first-child').addClass('active');
	//jQuery('.tab-content').hide();
	jQuery('.tab-content:first').show();

	// Click function
	jQuery('#tabs-nav li').click(function(){
		jQuery('#tabs-nav li').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.tab-content').hide();
		if(jQuery(this).text() == "Summary"){
			var how_to_show = jQuery('input[name="lbb_meta[how_to_show]"]:checked').val();
			var selected_url = jQuery('#where_to_show :selected').val();
			var enter_url = jQuery('#enter_url').val();
			if(how_to_show == "minimized"){
				if((selected_url == '' || selected_url == undefined) && enter_url == ''){
					jQuery('.lbb-popup-pages').show();
				}else{
					jQuery('.lbb-popup-pages').hide();
				}
				jQuery('.shortcode-show-for-maximized').hide();
				
				if((selected_url != '' && selected_url != undefined) || enter_url != ''){
					
					jQuery('.shortcode-show-for-maximized').show();
				}
			}else{
				jQuery('.lbb-popup-pages').hide();
				jQuery('.shortcode-show-for-maximized').hide();
			}

			if(enter_url){
				jQuery('.shortcode-enter-url').remove();
				jQuery('.enter-page-url').show();
				jQuery('.lbb-page-list-wrapper ul').append('<li class="shortcode-enter-url"><a href="'+enter_url+'">'+enter_url+'</a></li>');
			}else{
				jQuery('.shortcode-enter-url').remove();
				jQuery('.enter-page-url').hide();
			}
		}else if(jQuery(this).text() == "General"){
			jQuery('#aiassistant-sublink').trigger('click');
		}else if(jQuery(this).text() == "Live Chat "){
			jQuery('#live-chat-sublink').trigger('click');
		}else if(jQuery(this).text() == "Basic"){
			var answer_background_color = jQuery('input[name="lbb_meta[lbb_ans_bg_color]"]').val();
			jQuery('#lbb_home_ans_bg_color').wpColorPicker('color', answer_background_color);

			var lbb_chat_background_color = jQuery('input[name="lbb_meta[lbb_chat_background_color]"]').val();
			jQuery('#lbb_home_chat_background_color').wpColorPicker('color', lbb_chat_background_color);

			var lbb_question_background_color = jQuery('input[name="lbb_meta[lbb_question_background_color]"]').val();
			jQuery('#lbb_home_question_background_color').wpColorPicker('color', lbb_question_background_color);
		}else if(jQuery(this).text() == "Style"){
			var answer_background_color = jQuery('input[name="lbb_meta[lbb_ans_bg_color]"]').val();
			jQuery('#lbb_ans_bg_color').wpColorPicker('color', answer_background_color);

			var lbb_chat_background_color = jQuery('input[name="lbb_meta[lbb_chat_background_color]"]').val();
			jQuery('#lbb_chat_background_color').wpColorPicker('color', lbb_chat_background_color);

			var lbb_question_background_color = jQuery('input[name="lbb_meta[lbb_question_background_color]"]').val();
			jQuery('#lbb_question_background_color').wpColorPicker('color', lbb_question_background_color);
		}
		var activeTab = jQuery(this).find('a').attr('href');
		jQuery(activeTab).fadeIn();
		return false;
	});

	jQuery('.next-tab').on('click', function(){
		if(jQuery('#post_title').val() == ''){
			jQuery('.title-error').show();
			return false;
		}else{
			jQuery('.title-error').hide();
		}

		jQuery('#tabs-nav li').removeClass('active');
		jQuery('#tabs-nav li:eq(1)').trigger('click');
	});

	jQuery("#lbb-menu-toggle").on('click', function(){
        jQuery(".lbb-page-start-container").toggleClass('lbb-menu-collapse');
    });

    jQuery(document).on('click', '.lbb-add-new-dyn-msg', function() {
    	jQuery('#add-dynamic-message-rule').trigger('click');
    	jQuery('.lbb-no-dynamic-message-found').hide('slow');
    	jQuery('.lbb-add-new-dynamic-message').show('slow');
    });

	var faql = 'scratch';
	jQuery(document).on('click', '.lbb-add-more-faq', function() {
		jQuery('#lbb-modal-create-new-faq').css('display', 'flex');
	});

	jQuery(document).on('click','.lbb-question-fresh',function() {
		
		jQuery('.lbb-faq-screen1').hide();
		jQuery('.lbb-faq-screen2').hide();
		jQuery('.lbb-faq-screen-create').show();
		jQuery('.lbb-faq-add-btn').show();
		faql = 'scratch';

		
	});

	jQuery(document).on('click','.lbb-question-bank',function() {
		faql = 'bank';
		jQuery('.lbb-faq-screen1').hide();
		jQuery('.lbb-faq-screen2').show();
		jQuery('.lbb-faq-screen-create').hide();
		jQuery('.lbb-faq-add-btn').show();
	});
	

	jQuery(document).on('click','.lbb-faq-close',function() {
		faql = 'scratch';
		jQuery('#lbb-modal-create-new-faq').hide();
		jQuery('.lbb-faq-screen1').show();
		jQuery('.lbb-faq-screen-create').hide();
		jQuery('.lbb-faq-screen2').hide();
		jQuery('.lbb-faq-add-btn').hide();
		jQuery('.lbb-faq-error').hide();

		jQuery('#faql_question').val('');
		jQuery('#faql_answer').val('');

		if(jQuery('.lbb-accordion-item').length < 1){
			jQuery('.lbb-faq-section-start').addClass('llb-faq-empty');
		}else{
			jQuery('.lbb-faq-section-start').removeClass('llb-faq-empty');
		}

	});

	var lbb_faqs_chatflow = [];
	jQuery(document).on('click','.lbb-faq-add-btn',function() {
		
		if(faql == 'scratch'){

			var question = jQuery('#faql_question').val();
			var answer = jQuery('#faql_answer').val();

			jQuery('.lbb-faq-error').hide();

			if(question == ''){
				jQuery('.lbb-faq-error-question').show();
				return false;
			}

			if(answer == ''){
				jQuery('.lbb-faq-error-answer').show();
				return false;
			}

			lbbShowLoader();
			jQuery.ajax({
				type: 'POST',
				url: lbb_ajax.ajaxurl,
				data: {
					'action': 'lbb_faq_add',
					'question': question,
					'answer': answer
				},
				success: function (response) {
					lbbHideLoader();
					var data = response.data;
					var template = jQuery('#lbbFaqForm').html();
					template = template.replace('{{KEY}}','ff-'+generateUniqueId());
					/*var newObj = {
						"id":'',
						"title": '',
						"content": '',
					};*/
				
					//lbb_faqs.push(newObj);

					template = template.replaceAll('{{QId}}',data.qid);
					template = template.replaceAll('{{QVal}}',question);
					template = template.replaceAll('{{AVal}}',answer);

					jQuery('.lbb-faq-items').append(template);
					jQuery('.lbb-faq-close').trigger('click');
					//jQuery('#lbb-modal-create-new-faq').hide();
				}
			});

			
		}else{
			var selectedValues = [];
			jQuery(".lbb-faq-checkbox:checked").each(function() {

				var id = jQuery(this).val();
				var question = jQuery('#lbb-faq-question-'+id).val();
				var answer = jQuery('#lbb-faq-answer-'+id).val();

				selectedValues.push(jQuery(this).val());
				lbb_faqs_chatflow.push(jQuery(this).val());

				var template = jQuery('#lbbFaqForm').html();
				template = template.replace('{{KEY}}','ff-'+generateUniqueId());
				var newObj = {
					"id":'',
					"title": '',
					"content": '',
				};
			
				lbb_faqs.push(newObj);

				template = template.replaceAll('{{QId}}',id);
				template = template.replaceAll('{{QVal}}',question);
				template = template.replaceAll('{{AVal}}',answer);

				jQuery('.lbb-faq-items').append(template);

			});

			if (selectedValues.length > 0) {
				//alert("Selected values: " + selectedValues.join(", "));
				//jQuery('#lbb-modal-create-new-faq').hide();
				jQuery('.lbb-faq-close').trigger('click');
			} else {
				swal("No question(s) selected");
			}
			
		}
		

	});

	


    jQuery(document).on('click', '.lbb-preview-chatflow', function() {
		lbbShowLoader();
		jQuery('#propwrap_iframe').addClass('itson');
		jQuery('#propwrap_iframe').addClass('expanded');
		var iframe = jQuery('#lbbIFrame').html();
		iframeUrl = site_url+"?lbb-embed=1&id="+chatflow_id;
		iframe = iframe.replace('{{IFRAMEURL}}', iframeUrl);
		jQuery('#properties_iframe').html(iframe);
		jQuery('#properties_iframe').addClass('is-fullmode');
		jQuery('body').addClass('lbb-popup-opened');

		if(jQuery('input[name="lbb_meta[how_to_show]"]:checked').val() == 'inline'){
			jQuery('.lbb-iframe-main').addClass('lbb-iframe-inline');
		}else{
			jQuery('.lbb-iframe-main').addClass('lbb-iframe-popup');
		}

		 // Get the reference to the iframe element
		 var iframe = jQuery('.lbb-iframe-main');
		 iframe.on('load', function() {
			lbbHideLoader();
			
			iframe.contents().find('.lbb-kb-list a, #chat-messages a').on('click', function(event) {
				event.preventDefault();
				
				var href = jQuery(this).attr('href');
		
				if (href && href !== '#') {
				window.open(href, '_blank');
				event.preventDefault();
				}
			});
		 });
	});

	jQuery(document).on('click', '.lbb-preview-btn', function() {
		var id = jQuery(this).attr('data-id');
		var site_url = jQuery('#site_url').val();
		jQuery('#propwrap_iframe').addClass('itson');
		jQuery('#propwrap_iframe').addClass('expanded');
		var iframe = jQuery('#lbbIFrame').html();
		iframeUrl = site_url+"?lbb-embed=1&id="+id;
		iframe = iframe.replace('{{IFRAMEURL}}', iframeUrl);
		jQuery('#properties_iframe').html(iframe);
		jQuery('#properties_iframe').addClass('is-fullmode');
		jQuery('body').addClass('lbb-popup-opened');

		if(jQuery('input[name="lbb_meta[how_to_show]"]:checked').val() == 'inline'){
			jQuery('.lbb-iframe-main').addClass('lbb-iframe-inline');
		}else{
			jQuery('.lbb-iframe-main').addClass('lbb-iframe-popup');
		}
	});

	jQuery(document).on('click', '#lbb-preview-question', function() {
		swal('Please save the question first, then close this popup and click on preview button to see a full preview.');
	});

	jQuery(document).on('change', '#message_type', function() {
		var type = jQuery(this).val();
		if(type == 'text' || type == 'phone' || type == 'country' || type == 'name' || type == 'email' || type == 'url' || type == 'date'){
			jQuery('#chatflow-properties').removeClass('lbb-edit-only');
			jQuery('#chatflow-basic').trigger('click');
			jQuery('.lbb-popup-answers-wrapper').hide();
		}else{
			jQuery('#chatflow-properties').addClass('lbb-edit-only');
			jQuery('.lbb-form-group-sub-tab.lbb-sub-tab-wrapper').show();
			jQuery('.lbb-popup-question-wrapper').show();
			jQuery('.lbb-popup-answer-wrapper').hide();
		}

		jQuery('.lbb-no-answeroutcome').hide();
		jQuery('#lbb-save-question').show();
		jQuery('#lbb-preview-question').show();
		if(type != 'outcome'){
			jQuery('.lbb-single-question-outer').show();
		}

		if(type == 'outcome' && !isOutcomeFound()){
			jQuery('#chatflow-answer').hide();
			//jQuery('.lbb-sub-tab-wrapper').addClass('lbb-edit-only');
			jQuery('#lbb-single-next').hide();
			jQuery('.lbb-no-outcome').show();
			jQuery('.lbb-field-wrapper').hide();
			jQuery('#lbb-save-question').hide();
			jQuery('#lbb-preview-question').hide();
			jQuery('.lbb-form-group-single').hide();
			return false;
		}else if(type == 'outcome' /*&& !isAnswersOutcomeMapped()*/){
			jQuery('#chatflow-answer').hide();
			if(!isAnswersOutcomeMapped()){
				jQuery('#lbb-single-next').hide();
				jQuery('.lbb-form-group-single').hide();
				jQuery('.lbb-no-outcome').hide();
				jQuery('.lbb-no-answeroutcome').show();
				jQuery('.lbb-field-wrapper').hide();
				jQuery('#lbb-save-question').hide();
				jQuery('#lbb-preview-question').hide();
				return false;
			}
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.outcome-points-section').show();
			jQuery('.lbb-popup-answers-wrapper').show();
			jQuery('#lbb-single-next').hide();
			jQuery('.pdf-download-option').show();
		}else if(type == 'outcome' && !isPDFFound()){
			jQuery('.lbb-no-pdf').show();
			jQuery('.lbb-form-group-single').hide();
		}else if(type == 'text'){
			jQuery('.question-input-field-outer').show();
			jQuery('.lbb-custom-placeholder').show();
			jQuery('.lbb-form-group-sub-tab').hide();
		}else if(type == 'name' || type == 'email'){
			jQuery('.lbb-custom-placeholder').show();
			jQuery('.lbb-form-group-sub-tab').hide();
		}else if(type == 'attachment'){
			jQuery('.question-type-fileupload').show();
			jQuery('.select-format-section').show();
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-popup-answer-wrapper').show();
			jQuery('.lbb-outcome-info-card').show();
		}else if(type == 'single' || type == 'message'){
			jQuery('.dynamic-message-section').show();
			jQuery('.lbb-form-group-single').show();
			jQuery('.pdf-download-option').hide();
			jQuery('.lbb-outcome-listing').hide();
			jQuery('.outcome-points-section').hide();
		}else if(type == 'welcome'){
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-change-message-type').hide();
			jQuery('#lbb-single-next').hide();
		}else if(type == 'lastmessage'){
			jQuery('#chatflow-properties').hide();
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-change-message-type').hide();
			jQuery('#lbb-single-next').hide();
		}else if(type == 'date'){
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-no-pdf').hide();
			jQuery('.lbb-no-outcome').hide();
			jQuery('.question-input-field-outer').hide();
			jQuery('.question-type-fileupload').hide();
			jQuery('.lbb-popup-answers-wrapper').show();
		}else{
			jQuery('.lbb-form-group-sub-tab').hide();
			jQuery('.lbb-no-pdf').hide();
			jQuery('.lbb-no-outcome').hide();
			jQuery('.question-input-field-outer').hide();
			jQuery('.question-type-fileupload').hide();
		}

		if(type == 'url' || type == 'country' || type == 'date' || type == 'audio' || type == 'lastmessage'){
			jQuery('#lbb-single-next').hide();
		}else if(type == 'single'){
			jQuery('#lbb-single-next').show();
		}
		updateFieldSupport(type);

		if(type == 'single' || type == 'message'){
			jQuery('#chatflow-question').trigger('click');
			if(jQuery('#objectList li').length == 0){
				jQuery('.lbb-add-new-obj').hide();
			}else{
				jQuery('.lbb-add-new-obj').show();
			}
		}
		

	});

	
    

	

});



