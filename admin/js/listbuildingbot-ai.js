var lbb_ai_quesitons = [];

function generateOutcomeList(response){
    var checkboxList = jQuery("#lbb_checkboxList");
    checkboxList.html('');
    jQuery.each(response, function(index, item) {
        var checkbox = jQuery("<input type='checkbox' value='"+item.outcome_title+"'>");
        var label = jQuery("<label>").text(item.outcome_title);
        var description = jQuery("<p>").text(item.outcome_description);

        var listItem = jQuery("<div class='list-item'>").append(checkbox, label, description);
        checkboxList.append(listItem);
    });

    lbb_ai_outcome_list = response;

    jQuery('.lbb-outcome-titles-step1').hide();
    jQuery('.lbb-outcome-titles-step2').show();
}

function createLBBOutcomes(prompt){

    lbbShowLoader();
    jQuery.ajax({
        url: lbb_ajax.ajaxurl,
        type: "POST",
        data : {
            'action' : 'lbb_generate_outcomes',
            'prompt' : prompt
        },

        success: function(object_){
            /*jQuery('.llb-bg-box-with-spacing').animate({
                    scrollTop: jQuery(".lbb-ai-active-screen").offset().top - 100
            }, 2000);*/
            lbbHideLoader();

            var object = JSON.parse(object_);
            if(object.status == 'ok'){

                try {
                    //lbb_ai_quesitons = object.data;
                    
                    generateOutcomeList(object.data);
                } catch (error) {
                    swal('Invalid Response');
                    return false;
                }

            }else if(object.status == 'error'){

                if(object.code == 'invalid_api_key'){

                    swal('','Invalid API Key',"");

                }else{

                    var msg = '';
                    if(object.html == undefined){
                        msg = object.message;
                    }else{
                        msg = object.html;
                    }

                    lbbConfirmationDialog(

                        "Are you sure?", 
                        msg, 
                        "Yes", 
                        "No, cancel it!").then(function(isConfirm) {
                        if (isConfirm) {
                            createLBBQuestions(prompt);
                        }
                    });
                }
            }

        },

        error: function(jqXHR, textStatus, errorThrown) {

            lbbHideLoader();
            alert('Something went wrong please try again');
            console.log("Request failed:", textStatus, errorThrown);
        }

    }); 
}

function createLBBQuestions(prompt){



    lbbShowLoader()

    jQuery.ajax({

        url: lbb_ajax.ajaxurl,

        type: "POST",

        data : {

            'action' : 'lbb_create_questions',

            'prompt' : prompt

        },

        success: function(object_){
            jQuery('.llb-bg-box-with-spacing').animate({
                    scrollTop: jQuery(".lbb-ai-active-screen").offset().top - 100
            }, 2000);
            lbbHideLoader();

            var object = JSON.parse(object_);

            if(object.status == 'ok'){

            try {

                lbb_ai_quesitons = object.data;

                llb_preview_questions();

                lbb_next_screen_move('lbb-ai-preview-actions');

                //jQuery('#dap_ai_lesson_content_prompt_format_json').val(string);

                //jQuery('.dap-ai-generate-save-update-lesson').trigger('click');

            } catch (error) {

                console.error('Error parsing JSON data:', error);

            }

            }else if(object.status == 'error'){



                if(object.code == 'invalid_api_key'){

                    swal('','Invalid API Key',"");

                }else{

                    var msg = '';

                    if(object.html == undefined){

                    msg = object.message;

                    }else{

                    msg = object.html;

                    }



                    lbbConfirmationDialog(

                        "Are you sure?", 

                        msg, 

                        "Yes", 

                        "No, cancel it!").then(function(isConfirm) {

                        if (isConfirm) {

                            createLBBQuestions(prompt);

                        }

                    });

                }

            }



        },

        error: function(jqXHR, textStatus, errorThrown) {

            lbbHideLoader();

            alert('Something went wrong please try again');

            console.log("Request failed:", textStatus, errorThrown);

        }

    }); 

}

var lbb_ai_outcome = false;

var lbb_ai_add_contact = true;

var lbb_ai_outcome_type = 'use_comma';

var lbb_ai_outcomes = '';

var lbb_ai_outcome_list = [];

jQuery(document).ready(function() {



    jQuery(".js-select2-dropdown").select2({

        placeholder: "Select Option",

    });

    
    jQuery(document).on('click','.generate_outcome_titles_save',function(){

        var selectedValues = jQuery("#lbb_checkboxList input[type='checkbox']:checked").map(function() {
            return jQuery(this).val();
          }).get();

        if(selectedValues.length < 1){
            swal("Please select at least one outcome");
            return false;
        }

        lbb_ai_outcomes = selectedValues.join(", ");
        jQuery('.ai_outcome_titles_div').html('');

        if (lbb_ai_outcomes != ''){
            jQuery('.ai_outcome_titles_div').addClass('lbb-outcome-title-active');
        }else{
            jQuery('.ai_outcome_titles_div').removeClass('lbb-outcome-title-active');
        }

        jQuery('.ai_outcome_titles_div').html(lbb_ai_outcomes);

        jQuery('.lbb-ai-outcome-type-example-popup').removeClass('expanded');

    });

    jQuery(document).on('click','.generate_outcome_titles_back',function(){
        jQuery('.lbb-outcome-titles-step1').show();
        jQuery('.lbb-outcome-titles-step2').hide();
    });

    

    jQuery(document).on('click','.generate_outcome_titles',function(){

        if(api_flow == 'api'){
            var prompt = jQuery('#lbb_ai_ot_outline_prompt').html();
            createLBBOutcomes(prompt);
        }else{

            try {
                var response = jQuery('#lbb_ai_outcome_json_titles').val();
                response = JSON.parse(response);
            } catch (error) {
                swal('Invalid JSON response');
                return false;
            }
            
    
            var checkboxList = jQuery("#lbb_checkboxList");

            if(response.length > 0) {
                checkboxList.html('');
                jQuery.each(response, function(index, item) {
                    var checkbox = jQuery("<input type='checkbox' value='"+item.outcome_title+"'>");
                    var label = jQuery("<label>").text(item.outcome_title);
                    var description = jQuery("<p>").text(item.outcome_description);
        
                    var listItem = jQuery("<div class='list-item'>").append(checkbox, label, description);
                    checkboxList.append(listItem);
                });
        
                lbb_ai_outcome_list = response;
            }
    
            jQuery('.lbb-outcome-titles-step1').hide();
            jQuery('.lbb-outcome-titles-step2').show();
        }

    });
    jQuery(document).on('click','.use_ai_to_genetate_outcome',function(){


        jQuery('.lbb-ai-error').hide();

        var is_valid = true;

        if(jQuery('#lbb_ai_goal').val() == ''){
            jQuery('.lbb-ai-goal-error').show();
            is_valid = false;
        }



        if(jQuery('#lbb_ai_target_audience').val() == ''){
            jQuery('.lbb-ai-target-audience-error').show();
            is_valid = false;
        }

        if(!is_valid){
            return false;
        }

        var lbb_ai_goal = jQuery('#lbb_ai_goal').val();
        var lbb_ai_target_audience = jQuery('#lbb_ai_target_audience').val();

        var lbb_ai_lang_product = jQuery('#lbb_ai_lang_product').val();

        var lbb_outcome_prompt_tmp = lbb_outcome_prompt.replaceAll('%%GOAL%%',lbb_ai_goal);

        lbb_outcome_prompt_tmp = lbb_outcome_prompt_tmp.replaceAll('%%TARGET_AUDIENCE%%',lbb_ai_target_audience);

        lbb_outcome_prompt_tmp = lbb_outcome_prompt_tmp.replaceAll('%%TOTAL_QUESTION%%',lbb_ai_total_question);
        lbb_outcome_prompt_tmp = lbb_outcome_prompt_tmp.replaceAll('%%LANG%%',lbb_ai_lang_product);

        if(api_flow == 'api'){
            lbb_outcome_prompt_tmp = lbb_outcome_prompt_tmp.replaceAll('%%OUTCOME_DESC_LIMIT%%','');
            jQuery('.ai-outcom-type-manual').hide();
            jQuery('.ai-outcom-type-api').show();
        }else{
            lbb_outcome_prompt_tmp = lbb_outcome_prompt_tmp.replaceAll('%%OUTCOME_DESC_LIMIT%%','\n\nThe description for each outcome should be at least 500 words.');
            jQuery('.ai-outcom-type-manual').show();
            jQuery('.ai-outcom-type-api').hide();

        }

        jQuery('#lbb_ai_ot_outline_prompt').html(lbb_outcome_prompt_tmp);

        jQuery('.lbb-ai-outcome-type-example-popup').addClass('expanded');

    });

    jQuery('.lbb_ai_add_outcome_type_product').change(function() {
        var selectedValue = jQuery('.lbb_ai_add_outcome_type_product:checked').val();

        if(selectedValue == 'use_ai'){
            jQuery('.ai_outcome_type_use_ai').show();
            jQuery('.ai_outcome_wrapper_product').hide();
            lbb_ai_outcome_type = 'use_ai';

        }else{
            jQuery('.ai_outcome_wrapper_product').show();
            jQuery('.ai_outcome_type_use_ai').hide();
            lbb_ai_outcome_type = 'use_comma';

        }
    });

    jQuery('.lbb_ai_add_outcome_product').change(function() {

        var selectedValue = jQuery('.lbb_ai_add_outcome_product:checked').val();

        if(selectedValue == 'Y'){

            lbb_ai_outcome = true;

            jQuery('.ai_outcome_wrapper_product').show();
            jQuery('.lbb_outcome_type').show();
            //jQuery('.ai_outcome_type_use_ai').show();
            jQuery('.lbb_ai_add_outcome_type_product').trigger('change');
            

        }else{

            lbb_ai_outcome = false;
            jQuery('.lbb_outcome_type').hide();
            jQuery('.ai_outcome_wrapper_product').hide();
            jQuery('.ai_outcome_type_use_ai').hide();
        }

    });



    jQuery('.lbb_ai_add_contact').change(function() {

        var selectedValue = jQuery('.lbb_ai_add_contact:checked').val();

        if(selectedValue == 'Y'){

            lbb_ai_add_contact = true;

        }else{

            lbb_ai_add_contact = false;

        }

    });



    jQuery(document).on('click','.product-lbb-ai_manual_flow',function(){

        jQuery('.lbb-ai-screen-list-product').removeClass('lbb-ai-active-screen');

        jQuery('.lbb-ai-manual-basic-screen-product').addClass('lbb-ai-active-screen');

        jQuery('.lbb-ai-create-sales-page').addClass('ai-flow-manual').removeClass('ai-flow-api');

        api_flow = 'normal';

      });



    jQuery(document).on('click','.lbb-ai_auto_flow',function(){

		jQuery('.lbb-ai-screen-list-product').removeClass('lbb-ai-active-screen');

		jQuery('.lbb-ai-manual-basic-screen-product').addClass('lbb-ai-active-screen');

        jQuery('.lbb-ai-create-sales-page').addClass('ai-flow-api').removeClass('ai-flow-manual');

        api_flow = 'api';



	});

    jQuery(document).on('click','.lbb-no-ai-api',function(){

        swal('Looks like you have not entered the OpenAI Key in the settings page. Please enter it to use this feature.');

	});

    jQuery(document).on('click','.lbb-ai-goal-popup',function(){

        jQuery('.lbb-ai-goal-example-popup').addClass('expanded');

    });

    

    jQuery(document).on('click','.lbb-ai-target-audience-popup',function(){

        jQuery('.lbb-ai-target-audience-example-popup').addClass('expanded');

    });



    jQuery(document).on('click','.lbb-create-ai-bot',function(){

        

        var val = jQuery('input[name="ai_chatflow_mode"]:checked').val();



        if(val == 'popup'){

            var is_valid = false;

            if(jQuery('#ai_page_name').val() != ''){
                is_valid = true;

            }

            if(jQuery('#ai_page_url').val() != ''){

                is_valid = true;

            }



            if(!is_valid){

                swal('Please enter name of page or add URL');
                return false;

            }

        }



        var tmp = lbb_ai_quesitons;



        var d = {

            'chatflow_mode' : jQuery('input[name="ai_chatflow_mode"]:checked').val(),

            'page_name' :  jQuery('#ai_page_name').val(),

            'page_url' :  jQuery('#ai_page_url').val(),
            

        };

        tmp['page_action'] = [];

        tmp['page_action'].push(d);


        tmp['outcome_data'] = [];

        tmp['outcome_data'] = lbb_ai_outcome_list;

        var jsonData = JSON.stringify(tmp);

        lbbShowLoader();

        var lbb_ai_outcome_t = lbb_ai_outcome;
        if(lbb_ai_outcome_type == 'use_comma' && jQuery('#lbb_ai_outcome_titles').val() == ''){
            lbb_ai_outcome_t = false;
        }else if(lbb_ai_outcome_type == 'use_ai' && jQuery('.ai_outcome_titles_div').html() == ''){
            lbb_ai_outcome_t = false;
        }


        jQuery.ajax({

			type: 'post',

			url: lbb_ajax.ajaxurl+'?action=lbb_create_ai_bot&add_contact='+lbb_ai_add_contact+'&add_outcome='+lbb_ai_outcome_t+'&outcome_type='+lbb_ai_outcome_type,

            contentType: 'application/json',

			data: jsonData,

			success: function (response) {

                lbbHideLoader();

				var response = JSON.parse(response);

                if(response.edit_id != undefined && response.edit_id > 0){

                    jQuery('#copyable_chatflow_shortcode').val('[ListBuildingBot id="'+response.edit_id+'"]');

                    jQuery('#copyable_chatflow_shortcode').val('[ListBuildingBot id='+response.edit_id+']');

                    jQuery('.lbb-edit-funnel-link').attr('href',response.edit_link);

                    jQuery('.btn-view-page').attr('href',response.page_link);



                    jQuery('#copyable_chatflow_embed_shortcode').val('<div id="slb-inline-app"><script type="text/javascript" src="'+lbb_ai_site_url+'?embed=true&chat_id='+response.edit_id+'"></script></div>');

                    if(jQuery('input[name="ai_chatflow_mode"]:checked').val() == 'inline'){

                        jQuery('bb-ai-shortcode-main').show();

                        jQuery('.btn-view-page').hide();

                    }else{

                        jQuery('lbb-ai-shortcode-main').hide();

                        jQuery('.btn-view-page').show();

                    }

                    

                    

                    lbb_next_screen_move('lbb-ai-congratulation-screen-product');

                }

			}

		});



    });



    jQuery(document).on('change', 'input[name="ai_chatflow_mode"]', function() {

        var selectedValue = jQuery(this).val();

        if(selectedValue == 'popup'){

            jQuery('.lbb-ai-page-action').show();

        }else{

            jQuery('.lbb-ai-page-action').hide();

        }

    });

      

    

    jQuery(document).on('click','.lbb-ai-back-btn',function(){



        var prevbtn =  jQuery(this).attr('data-prev');



        if(prevbtn == 'lbb-ai-manual-outline-prompt-sales-screen-product' && api_flow == 'api'){

            jQuery('.lbb-ai-screen-list-product').removeClass('lbb-ai-active-screen');

            jQuery('.lbb-ai-manual-basic-screen-product').addClass('lbb-ai-active-screen');

        }else if(prevbtn == 'lbb-ai-sales-pageb'){

            jQuery('.lbb-ai-page-actions').removeClass('lbb-ai-active-screen');

            jQuery('.lbb-ai-preview-actions').addClass('lbb-ai-active-screen');

        }else if (typeof prevbtn !== "undefined" && prevbtn != '') {

            jQuery('.lbb-ai-screen-list-product').removeClass('lbb-ai-active-screen');

            jQuery('.'+prevbtn).addClass('lbb-ai-active-screen');

        }

    });



    jQuery(document).on('click','.lbb-ai-next-btn',function(){

		var nextbtn =  jQuery(this).attr('data-next');

		var screen =  jQuery(this).attr('data-screen');

        var is_api = false;



		if (typeof nextbtn !== "undefined" && nextbtn != '') {



			if (screen == 'generate-sales-product') {



                jQuery('.lbb-ai-error').hide();

                var is_valid = true;

                if(jQuery('#lbb_ai_goal').val() == ''){

                    jQuery('.lbb-ai-goal-error').show();

                    is_valid = false;

                }



                if(jQuery('#lbb_ai_target_audience').val() == ''){

                    jQuery('.lbb-ai-target-audience-error').show();

                    is_valid = false;

                }



                /*if(jQuery('#lbb_ai_product_description').val() == ''){

                    jQuery('.lbb-ai-topic-error').show();

                    is_valid = false;

                }*/



                var selectedValue = jQuery('.lbb_ai_add_outcome_product:checked').val();

                /*if(selectedValue == 'Y' && jQuery('#lbb_ai_outcome_titles').val() == ''){

                    is_valid = false;

                    jQuery('.lbb-ai-outcome-titles-error').show();

                }*/



                if(!is_valid){

                    return false;

                }



                generate_prompt = lbb_generate_prompt_outline();



                if(api_flow == 'normal'){

                    jQuery('#lbb_ai_outline_prompt').html(generate_prompt);

                    jQuery('.llb-bg-box-with-spacing').animate({
                        scrollTop: jQuery(".lbb-ai-active-screen").offset().top - 100
                    }, 2000);
                }else{

                    is_api = true;

                    if(lbb_ai_quesitons['questions'] != undefined && lbb_ai_quesitons['questions'].length > 0){



                        lbbConfirmationDialog(

                            "Alert?", 

                            'Are you sure you want to regenerate?', 

                            "Yes", 

                            "No, cancel it!").then(function(isConfirm) {

                                if (isConfirm) {

                                    createLBBQuestions(generate_prompt);

                                }else{

                                    lbb_next_screen_move('lbb-ai-preview-actions');

                                }

                        });

                    }else{

                        createLBBQuestions(generate_prompt);

                    }

                    //createLBBQuestions(generate_prompt);

                }

            }else if(screen == 'generate-sales-title-product'){

                var json = jQuery('#lbb_ai_outline_sales_title_product').val();

                var parsed = JSON.parse(json);

                lbb_ai_quesitons = parsed;

                llb_preview_questions();
                jQuery('.llb-bg-box-with-spacing').animate({
                    scrollTop: jQuery(".lbb-ai-active-screen").offset().top - 100
                }, 2000);
            }else if(screen == 'lbb-ai-page-actions'){



                var val = jQuery('input[name="ai_chatflow_mode"]:checked').val();



                if(val == 'popup'){

                    var is_valid = true;

                    if(jQuery('#ai_page_name').val() == '' || jQuery('#ai_page_url').val() == ''){

                        swal('Please enter name of page or add URL');

                        is_valid = false;

                    }



                    if(!is_valid){

                        return false;

                    }



                }



            }

        }

        if(!is_api){

            lbb_next_screen_move(nextbtn);

        }

    });



});



function lbb_next_screen_move(nextbtn){

    jQuery('.lbb-ai-screen-list-product').removeClass('lbb-ai-active-screen');

    jQuery('.'+nextbtn).addClass('lbb-ai-active-screen');

}



function llb_preview_questions(){



    jQuery('.lbb-ai-question-list').html('');

    jQuery.each(lbb_ai_quesitons.questions, function (index, question) {



        var html = `<li class="gpt-questiontype-single">

            <input class="quiz_checkbox" name="chatgpt_question_list[]" type="checkbox" value="1">

            <div class="chagpt-single_quiz_card">

                <div class="sqb-cg-question">`+question.question+`</div><div class="sqb-cg-qa">

                <div class="sqb-cg-qa-lbl">Answers : </div><div class="sqb-cg-qa-data">`;



        var ansHtml = '';

        jQuery.each(question.answers, function (answerIndex, answer) {

            ansHtml += `<div class="sqb-cg-qa-item">`+ (answerIndex + 1) +`. `+answer.text+`</div>`;

        });    

        html += ansHtml;         

        html +=  `</div></div></div></li>`;

        jQuery('.lbb-ai-question-list').append(html);

    });



    

}



function lbb_generate_prompt_outline(){

    var lbb_ai_goal = jQuery('#lbb_ai_goal').val();

    var lbb_ai_target_audience = jQuery('#lbb_ai_target_audience').val();

    var lbb_ai_product_description = jQuery('#lbb_ai_product_description').val();

    var lbb_ai_lang_product = jQuery('#lbb_ai_lang_product').val();

    var lbb_ai_total_question = jQuery('#lbb_ai_total_question').val();

    

    if(lbb_ai_outcome == true && jQuery('#lbb_ai_outcome_titles').val() != ''){
        prompt = lbb_prompt_outcome;
    }else if(lbb_ai_outcome == true && jQuery('.ai_outcome_titles_div').html() != ''){
        prompt = lbb_prompt_outcome;
    }else{

        prompt = lbb_prompt;

    }

  

    var lbb_ai_prompt = prompt.replaceAll('%%GOAL%%',lbb_ai_goal);

    lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%TARGET_AUDIENCE%%',lbb_ai_target_audience);

    if(lbb_ai_product_description != ''){

        lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%PRODUCT_DESC%%',''+lbb_ai_product_description+'');

    }else{

        lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%PRODUCT_DESC%%','');

    }



    lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%LANG%%',lbb_ai_lang_product);

   

    

    if(lbb_ai_outcome == true){

        if(lbb_ai_outcome_type == 'use_ai'){
            var lbb_ai_outcome_titles = jQuery('.ai_outcome_titles_div').html();
        }else{
            var lbb_ai_outcome_titles = jQuery('#lbb_ai_outcome_titles').val();
        }

        

        //var items = lbb_ai_outcome_titles.split(',');


        var items = lbb_ai_outcome_titles.split(',').map(function(title) {
            return title.trim();
          });
          
          // Join the array back into a string
          var cleanedString = items.join(',');

        var replacedString = cleanedString.replace(/,/g, '\n');

        lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%outcome_count%%',items.length);

        lbb_ai_prompt = lbb_ai_prompt.replaceAll('%%outcome_titles%%',replacedString);

    }



    if(lbb_ai_outcome == true && lbb_ai_outcome_titles != ''){

        lbb_bot_json_outcome = lbb_bot_json_outcome.replaceAll('%%TOTAL_QUESTION%%',lbb_ai_total_question);

        return lbb_ai_prompt+"\n"+lbb_bot_json_outcome;

    }else{

        lbb_bot_json = lbb_bot_json.replaceAll('%%TOTAL_QUESTION%%',lbb_ai_total_question);

        return lbb_ai_prompt+"\n"+lbb_bot_json;

    }

}



function lbb_copy_to_clipboardNew(obj) {

	var elementId = jQuery(obj).attr("data-id");

	var code = jQuery('#'+elementId).text();

    var code = code.replace(/<br>/g, "\n");

    copyToClipboardNew(code);

    jQuery(obj).find('i').text("Copied!");

    setTimeout(function() {

      jQuery(obj).find('i').text("Copy Code");

    }, 2000);



    return false;

}



function copyToClipboardNew(text) {

    var tempInput = jQuery("<textarea>");

    

      jQuery("body").append(tempInput);

    

    

    tempInput.val(text).select();

    document.execCommand("copy");

    tempInput.remove();

  }