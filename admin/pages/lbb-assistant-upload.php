<?php
?>
<div class="lbb-upload-ass-main">

<div class="lbb-scrape-files-input">
    <div class="lbb-form-group lbb-mb-20">
        <label for="lbb-site-url">Upload File:</label>
        <div class="lbb-input-with-btn">
            <input type="file" id="lbb-content-file" class="lbb-input-field" name="lbb-content-file">
            <button id="lbb-ass-upload" class="lbb-btn lbb-btn-primary">Upload</button>
        </div>
    </div>
</div>

<div class="lbb-thankyou-ass" style="display:none;">
    <div class="lbb-sub-tab-description lbb-alert lbb-alert-success lbb-mb-20">
        <p>Uploaded successfully!</p>
    </div>
</div>

<h2>Select Files to Train Your Bot and Click NEXT</h2>
<ul id="lbb-upload-files-list" class="lbb-ass-table lbb-links-list-wrapper lbb-trained">
    <?php
    if(isset($_REQUEST['id'])){
        $chat_id = $_GET['id'];
        $aiassistantmanager= new AiassistantManager();
        $is_edit_mode = $aiassistantmanager->loadByMapping($chat_id, 'upload');

        //echo '<pre>';
        //print_r($is_edit_mode);
        
        
        $lbb_files = array();

        if(!empty($is_edit_mode)){
            
            foreach ($is_edit_mode as $selected_key => $selected_value) {
                $random_id = time().'3'.$selected_key; 

                $lbb_files[] = array(
                    'path' => $selected_value['source'],
                    'filename' => basename($selected_value['source']),
                    'is_update_required' => 0,
                    'file_id' => $selected_value['ai_file_id']
                ); 

               
                 ?>

                <li class="lbb-url-listing-item-desn lbb-trained" id="lbb-file-upload-?php echo $random_id; ?>">
                    <div class="lbb-url-listing-inside">
                        <div class="lbb-root checkbox">
                            <span class="checkbox-custom-style">
                                <input id="lbb-file-upload-checkbox-<?php echo $random_id; ?>" type="checkbox" name="lbb_ass_paths[]" class="custom-checkbox-input lbb-url-listing-checkbox" value="<?php echo $selected_value['source']; ?>" />
                                <label for="lbb-file-upload-checkbox-<?php echo $random_id; ?>" class="custom--checkbox"></label>
                            </span>
                        </div>
                        <div class="lbb-url-listing-text">
                            <span class="lbb-url-listing-url-text"><?php echo $selected_value['source']; ?>/</span>
                        </div>
                        <div class="lbb-url-listing-action">
                            <button class="lbb-upload-listing-delete-icon" tabindex="0" type="button" aria-label="delete">
                                <i class="bx bxs-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                </li>

                <?php 
            }
        }
        
    } ?>
</ul>
<div class="lbb-link-footer-part">
    <div></div>
    <button id="lbb-ass-generate" class="lbb-btn lbb-btn-primary">Generate</button>
</div>
</div>

<div class="">
    <?php /*
    <table class="lbb-ass-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Path</th>
            </tr>
        </thead>
        <tbody>

        <?php
            if(isset($_REQUEST['id'])){
                $chat_id = $_GET['id'];
                $aiassistantmanager= new AiassistantManager();
                $is_edit_mode = $aiassistantmanager->loadByMapping($chat_id, 'upload');
                $lbb_files = array();

                if(!empty($is_edit_mode)){
                    
                    foreach ($is_edit_mode as $selected_key => $selected_value) {

                        $lbb_files[] = array(
                            'path' => $selected_value['source'],
                            'filename' => basename($selected_value['source']),
                            'is_update_required' => 0
                        );



                        echo '<tr class="lbb-row-file" data-index="'.$selected_key.'"><td><input type="checkbox" name="lbb_ass_paths[]" value="'.$selected_value['source'].'" /></td><td>'.$selected_value['source'].'</td></tr>';
                    }
                }
                
            }
        ?>

        </tbody>
    </table>
    */ ?>
    

</div>

<script>
    var lbb_files = <?php echo (!empty($lbb_files)) ? json_encode($lbb_files) : json_encode(array()); ?>;

    function getFilesByTitle(name){
        for (const item of lbb_files) {
            if (item.filename === name) {
                return item.filename;
            }
        }
        return '';
    }

    function findIndexByFilesName(filename) {
        for (var i = 0; i < lbb_files.length; i++) {
            if (lbb_files[i].filename === filename) {
                return i;
            }
        }
        return null;
    }

    jQuery(document).ready(function () {

        

        jQuery(document).on('click','.lbb-upload-listing-delete-icon', function (e) {
            
            var index_id = jQuery(this).closest('li').index();

            jQuery(this).closest('li').remove();
            var file_id = '';
            if(lbb_files[index_id]['file_id'] != undefined){
                file_id = lbb_files[index_id]['file_id'];
            }

            lbbConfirmationDialog(
                "Confirm?", 
                "Are you sure want to DELETE?", 
                "Yes, I am sure!", 
                "No, cancel it!").then(function(isConfirm) {
                if (isConfirm) {
                    lbb_files.splice(index_id, 1);
                    lbbShowLoader();
                    jQuery.ajax({
                        url: lbb_ajax.ajaxurl+'?action=lbb_delete_ass_file&chatflow_id='+chatflow_id,
                        type: 'POST',
                        data: {file_id : file_id},
                        success: function(response) {
                            lbbHideLoader();
                        }
                    });
                }
            });

        });

        

        jQuery(document).on('click','#lbb-ass-next', function (e) {
            jQuery('#tabs-nav li').eq(2).trigger('click');
        });

        jQuery(document).on('click','#lbb-ass-generate', function (e) {
           
            var filedata = jQuery('input[name="lbb_ass_paths[]"]').serializeArray();

            if(filedata.length < 1) {
                swal('Please select a path to generate');
            }

            lbbConfirmationDialog(
                "Confirm?", 
                "Are you sure you want to use this file to train the bot?", 
                "Yes, I am sure!", 
                "No, cancel it!").then(function(isConfirm) {
                if (isConfirm) {
                    lbbShowLoader();
                    jQuery.ajax({
                        url: lbb_ajax.ajaxurl+'?action=lbb_ass_files_save&chatflow_id='+chatflow_id,
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(lbb_files),
                        success: function(response) {
                            lbbHideLoader();
                            response = JSON.parse(response);
                            
                            if(response.error != undefined){
                                swal(response.error.message);
                                return false;
                            }

                            //jQuery('.lbb-upload-ass-main').hide();
                            jQuery('.lbb-thankyou-ass').show();
                            jQuery('.lbb-url-listing-item-desn').addClass('lbb-trained');

                            if(lbb_files.length > 0) {
                                jQuery.each(lbb_files, function (index, filed) {
                                    lbb_files[index]['is_update_required'] = 0;
                                });
                            }
                            if(response.success){

                            }
                            
                        }
                    });
                }
            });
     
    });
        
    
    function lbb_upload_ass_file_upload(file){
        // Create a FormData object and append the file
        var formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'lbb_upload_ass_file');

        lbbShowLoader();
        // Perform AJAX request
        jQuery.ajax({
            url: lbb_ajax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                lbbHideLoader();
                if(response.success != undefined){
                    
                    data = response.data;
                    
                    if(data.status == 'ok'){
                      

                        var update = findIndexByFilesName(data.filename);

                        if(update == null){
                            lbb_files.push({
                                'filename' : data.filename,
                                'path' : data.path,
                                'is_update_required' : 1,
                                'file_id' : data.file_id,
                            });
                            var update = findIndexByFilesName(data.filename);

                            var random_id = '3'+generateUniqueId();
                            jQuery('ul.lbb-ass-table').append(`<li class="lbb-url-listing-item-desn" id="lbb-file-upload-`+random_id+`">
                                <div class="lbb-url-listing-inside">
                                    <div class="lbb-root checkbox">
                                        <span class="checkbox-custom-style">
                                            <input id="lbb-file-upload-checkbox-`+random_id+`" type="checkbox" name="lbb_ass_paths[]" class="custom-checkbox-input lbb-url-listing-checkbox" value="`+data.path+`" />
                                            <label for="lbb-file-upload-checkbox-`+random_id+`" class="custom--checkbox"></label>
                                        </span>
                                    </div>
                                    <div class="lbb-url-listing-text">
                                        <span class="lbb-url-listing-url-text">`+data.path+`</span>
                                    </div>
                                    <div class="lbb-url-listing-action">
                                        <button class="lbb-upload-listing-delete-icon" tabindex="0" type="button" aria-label="delete">
                                            <i class="bx bxs-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                
                            </li>`);

                            //jQuery('.lbb-ass-table tbody').append('<tr class="lbb-row-file" data-index="'+update+'"><td><input type="checkbox" name="lbb_ass_paths[]" value="'+data.path+'" /></td><td>'+data.path+'</td></tr>');
                        }else{
                            /*lbb_files[index] = {
                                'filename' : data.filename,
                                'path' : data.path,
                                'is_update_required' : 1
                            };*/
                        }

                        
                    }
                }
                //console.log(response);
                //alert('File uploaded successfully');
            },
            error: function (error) {
                console.log(error);
                alert('Error uploading file');
            }
        });
    }

    jQuery(document).on('click','#lbb-ass-upload', function (e) {

        e.preventDefault();

        if(jQuery('#lbb-content-file').val() == ''){
            swal('Please select a file');
            return false;
        }

        var fileInput = jQuery('#lbb-content-file')[0];
        var file = fileInput.files[0];

        if(lbb_files.length > 0) {

            var is_exist = false;
            jQuery.each(lbb_files, function (index, filed) {
                
                if(filed.filename == file.name){
                    is_exist = true;
                    lbbConfirmationDialog(
                    "Confirm?", 
                    "File already exists. Do you want to overwrite it?", 
                    "Yes, I am sure!", 
                    "No, cancel it!").then(function(isConfirm) {
                        if (isConfirm) {
                            lbb_upload_ass_file_upload(file);
                            var index = findIndexByFilesName(file.name);
                            lbb_files[index]['is_update_required'] = 1;
                        }
                    });
                }
            });

            if(!is_exist){
                lbb_upload_ass_file_upload(file);
            }

        }else{
            lbb_upload_ass_file_upload(file);
        }

        
    });
});

</script>