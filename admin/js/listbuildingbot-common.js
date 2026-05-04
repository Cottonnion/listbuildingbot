function lbbCheckotifications(){
        
    var conversation_id = jQuery(this).attr('data-id');
    jQuery.ajax({
        type: 'post',
        url: lbb_ajax.ajaxurl,
        data: {
            'action': 'lbb_check_notifications',
        },
        success: function (response) {
            var messagesCountDiv = jQuery('.lbb-messages-unread');
            if(messagesCountDiv.length > 0) {
                messagesCountDiv.html(response);
            }
        }
    });
}

function lbbUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

jQuery(document).ready(function() {

    if(jQuery('.lbb-messages-unread').length > 0){
        setInterval(() => {
            lbbCheckotifications();
        }, 10000);
    }

    jQuery(document).on('input','#lbb_name',function() {
        jQuery(this).val(jQuery(this).val().toLowerCase());
    });

    /* Image Upload Start*/

    jQuery(document).on('click','.lbb-common-image-upload-outer .dashicons-edit',function() {
        jQuery(this).parents('.lbb-common-image-upload-outer').find('.lbb-common-image-upload').trigger('click');
    });

    jQuery(document).on('click','.lbb-common-video-upload-outer .dashicons-edit',function() {
        jQuery(this).parents('.lbb-common-video-upload-outer').find('.lbb-common-video-upload').trigger('click');
    });

    jQuery(document).on('click','.lbb-common-audio-upload-outer .dashicons-edit',function() {
        jQuery(this).parents('.lbb-common-audio-upload-outer').find('.lbb-common-audio-upload').trigger('click');
    });

    jQuery(document).on('click','.lbb-common-audio-upload-outer .dashicons-trash',function() {

        jQuery(this).parents('.lbb-common-audio-upload-outer').find('.lbb-audio-preview-container audio').remove();
        jQuery(this).parents('.lbb-common-audio-upload-outer').removeClass('lbb-common-audio-upload-has-item');
        jQuery(this).parents('.lbb-common-audio-upload-outer').find('input[name="lbb_general_settings[lbb_audio_upload]"]').val('');
    });
    jQuery(document).on('click','.lbb-common-image-upload-outer .dashicons-trash',function() {
        var data = jQuery(this);
        var type = data.attr('data-type');
        console.log(type);
        if(type == 'lbb_image_upload'){
            jQuery('input[name="lbb_meta[lbb_bot_image_select]"][value="image-one"]').prop('checked', true)
        }
        jQuery(this).parents('.lbb-common-image-upload-outer').addClass('lbb-no-img');
        jQuery(this).parents('.lbb-common-image-upload-outer').find('input').val('');
        jQuery(this).parents('.lbb-common-image-upload-outer').find('img').attr('src', '');
        if(jQuery(this).attr('data-type') == 'lbb_chat_icon_image'){
            jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-icon');
            jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-image');
            jQuery('.lbb-chat-icon-inner').removeClass('lbb-img-show-img');
            jQuery('.lbb-chat-icon-inner img').attr('src', '');
        }
    });

    jQuery(document).on('click','.lbb-common-video-upload-outer .dashicons-trash',function() {
        jQuery(this).parents('.lbb-common-video-upload-outer').addClass('lbb-no-img');
        jQuery(this).parents('.lbb-common-video-upload-outer').find('input').val('');
        jQuery(this).parents('.lbb-common-video-upload-outer').find('img').attr('src', '');
        if(jQuery(this).attr('data-type') == 'lbb_chat_icon_video'){
            jQuery('.lbb-chat-icon-container').addClass('lbb-widget-type-icon');
            jQuery('.lbb-chat-icon-container').removeClass('lbb-widget-type-video');
            jQuery('.lbb-chat-icon-inner').removeClass('lbb-img-show-img');
            jQuery('.lbb-chat-icon-inner img').attr('src', '');
        }
    });

    jQuery(document).on('click','.lbb-common-image-upload',function() {
        var data = jQuery(this);
        var type = data.attr('data-type');
        console.log(type);
        var lbb_mediauploader;   
        if (lbb_mediauploader) {
            lbb_mediauploader.open();
            return;
        }
        lbb_mediauploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        
        lbb_mediauploader.on('select', function() {
            attachment = lbb_mediauploader.state().get('selection').first().toJSON();
                data.parents('.lbb-common-image-upload-outer').find('input').val(attachment.url);
                data.parents('.lbb-common-image-upload-outer').find('.lbb-image-preview-container img').attr('src',attachment.url);
                data.parents('.lbb-common-image-upload-outer').removeClass('lbb-no-img');

                if(type == 'lbb_chat_background_image'){
                    jQuery("body").get(0).style.setProperty("--lbb-chat-background-image", 'url('+attachment.url+')');
                }

                if(type == 'lbb_image_upload'){
                   jQuery('input[name="lbb_meta[lbb_bot_image_select]"][value="image-custom"]').prop('checked', true)
                }
                
                if(type == 'lbb_chatbot_icon_options'){
                    preview_minimized_style();
                }
            });
        
        lbb_mediauploader.open();
    });

    jQuery(document).on('click','.lbb-common-video-upload',function() {
        var data = jQuery(this);
        var type = data.attr('data-type');
        console.log(type);
        var lbb_mediauploader;   
        if (lbb_mediauploader) {
            lbb_mediauploader.open();
            return;
        }
        lbb_mediauploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Video',
            button: {
                text: 'Choose Video'
            },
            library: {
                type: [ 'video' ]
            },
            multiple: false
        });
        
        lbb_mediauploader.on('select', function() {
            var multimedia_image = jQuery("#multimedia_image").val();
            attachment = lbb_mediauploader.state().get('selection').first().toJSON();
                data.parents('.lbb-common-video-upload-outer').find('input').val(attachment.url);
                data.parents('.lbb-common-video-upload-outer').find('.lbb-image-preview-container img').attr('src',multimedia_image);
                data.parents('.lbb-common-video-upload-outer').removeClass('lbb-no-img');
                if(type == 'lbb_chat_background_video'){
                    jQuery('.lbb-video-bg-container').find('source').attr('src', attachment.url);
                    jQuery('.lbb-video-bg-container video')[0].load();
                }
                if(type == 'lbb_chatbot_icon_options'){
                    preview_minimized_style();
                }
                
            });
        
        lbb_mediauploader.open();
    });

    jQuery(document).on('click','.lbb-common-audio-upload',function() {
        var data = jQuery(this);
        var lbb_mediauploader;   
        if (lbb_mediauploader) {
            lbb_mediauploader.open();
            return;
        }
        lbb_mediauploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Audio',
            button: {
                text: 'Choose Audio'
            },
            library: {
                type: [ 'audio' ]
            },
            multiple: false
        });
        
        lbb_mediauploader.on('select', function() {
            data.parents('.lbb-common-audio-upload-outer').find('.lbb-audio-preview-container audio').remove();
            var multimedia_image = jQuery("#multimedia_image").val();
            attachment = lbb_mediauploader.state().get('selection').first().toJSON();
            data.parents('.lbb-common-audio-upload-outer').addClass('lbb-common-audio-upload-has-item');
            data.parents('.lbb-common-audio-upload-outer').find('input').val(attachment.url);
            data.parents('.lbb-common-audio-upload-outer').find('.lbb-audio-preview-container').append('<audio controls><source src="'+attachment.url+'" type="audio/mpeg"></audio>');
        });
    
        lbb_mediauploader.open();
    });

    /* Image Upload End*/

    jQuery('.lbb-model-open-btn').click(function() {
        var lbb_model_selector = jQuery(this).data('lbbmodel')
        jQuery(lbb_model_selector).css("display", "flex");
    });

    jQuery(".lbb-close").click(function() {
        jQuery('.lbb-modal-container').css("display", "none");
    });
    
    jQuery(".js-select2-with-search").select2({
        placeholder: "Please Select"
    });

    jQuery(".js-select2").select2({
        placeholder: "Choose a filter",
        minimumResultsForSearch: -1
    });

    jQuery(".lbb-font-family").on('change',function(){
        var css_variable =jQuery(this).attr('data-css-variable');
        jQuery("body").get(0).style.setProperty("--"+css_variable, this.value);
        var font_url = 'https://fonts.googleapis.com/css2?family='+this.value;
        var stylesheet = jQuery("<link>", {rel: "stylesheet", type: "text/css", href: font_url }); 
        stylesheet.appendTo("head");
    });

    jQuery('.lbb-color-picker').wpColorPicker({
        change: function(event, ui) {
            var selectedColor = ui.color.toString();
            var css_var = jQuery(this).attr('data-css-variable');
            var other_options = jQuery(this).attr('data-other-options');
            jQuery(this).parents("body").get(0).style.setProperty("--"+css_var, selectedColor);
            if(other_options == 'lbb_chat_background_color' || other_options == 'lbb_inner_chat_background_color'){
                jQuery('input[name="lbb_meta[lbb_chat_background_color]"]').val(selectedColor);
            }else if(other_options == 'lbb_question_background_color' || other_options == 'lbb_inner_question_background_color'){
                jQuery('input[name="lbb_meta[lbb_question_background_color]"]').val(selectedColor);
            }else if(other_options == 'lbb_home_ans_bg_color'){
                jQuery('input[name="lbb_meta[lbb_ans_bg_color]"]').val(selectedColor);
            }
        }
    });

    jQuery('.lbb-slider-outer').each(function(){
        var slider = jQuery(this).find('.lbb-slider');
        var slider_min = jQuery(this).find('.lbb-slider').attr('data-slider-min');
        var slider_max = jQuery(this).find('.lbb-slider').attr('data-slider-max');
        var slider_value = jQuery(this).find('.lbb-slider').attr('data-slider-value');
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
                jQuery(this).parent('.lbb-slider-outer').find('.lbb-slider-input').val(ui.value);
                jQuery(this).parents("body").get(0).style.setProperty("--"+css_var, ui.value+data_px);
            }
        });
    });

    jQuery('#tabs-nav li:first-child').addClass('active');
    jQuery('.tab-content').hide();
    jQuery('.tab-content:first').show();

    jQuery('#tabs-nav li').click(function(){
        if(chatflow_id == ''){
            if(jQuery(this).index() != 0){
                jQuery('#tabs-nav li:eq(0)').trigger('click');
                swal('Please Save the chatflow');
                return false;
            }
        }

        if(jQuery(this).index() == 0){
            jQuery('.lbb-back-chatflow').hide();   
        }else{
            jQuery('.lbb-back-chatflow').show();   
        }

        if(jQuery(this).index() == 8){
            jQuery('.lbb-next-chatflow').hide();
        }else{
            jQuery('.lbb-next-chatflow').show();
        }

        var video_text = tinymce.get('video_text').getContent();
        jQuery('#lbb-chat-main-wrapper .lbb-example-widget-collapsed-content-text').html(video_text);
        jQuery('#tabs-nav li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tab-content').hide();

        var activeTab = jQuery(this).find('a').attr('href');
        jQuery(activeTab).fadeIn();
        return false;
    });
});