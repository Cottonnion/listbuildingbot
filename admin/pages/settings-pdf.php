<?php
$lbb_pdf_header = "#d33838";
$lbb_pdf_footer = "#f9a3a3";
$lbb_pdf_logo_upload = "";
$lbb_pdf_global_font_val = 'sans-serif';
$lbb_pdf_footer_content = '%%DOMAIN%% ©%%YEAR%% All Rights Reserved';
$lbb_pdf_header_title = '%%HEADERTITLE%%';
$supported_font = array(
                'sans-serif' => 'Sans Seirif',
                'roboto' => 'Roboto',
                'times-roman' => 'Times Roman',
                'courier' => 'courier',
                'dejavu sans' => 'dejavu sans',
                'dejavu sans mono' => 'dejavu sans mono',
                'dejavu serif' => 'dejavu serif',
                'DejaVu Sans, sans-serif' => 'DejaVu Sans, sans-serif',
                'BeVietnamPro, sans-serif' => 'BeVietnamPro',
            );
$plugin_path = 'gdprlibrary/gdprlibrary.php';
if (is_plugin_active($plugin_path)) {
    $supported_font['mgenplus'] = 'Mgen+ (Japanese font)';
} 


if(get_option('lbb_pdf_header_footer') ){
    $lbb_pdf_header_footer = get_option('lbb_pdf_header_footer');
    $lbb_pdf_header = (!empty($lbb_pdf_header_footer['lbb_pdf_header'])) ? $lbb_pdf_header_footer['lbb_pdf_header']: $lbb_pdf_header;
    $lbb_pdf_footer = (!empty($lbb_pdf_header_footer['lbb_pdf_footer'])) ? $lbb_pdf_header_footer['lbb_pdf_footer'] : $lbb_pdf_footer;
    $lbb_pdf_logo_upload = (!empty($lbb_pdf_header_footer['lbb_pdf_logo_upload'])) ? $lbb_pdf_header_footer['lbb_pdf_logo_upload'] : $lbb_pdf_logo_upload;
    $lbb_pdf_global_font_val = (!empty($lbb_pdf_header_footer['lbb_pdf_global_font_val'])) ? $lbb_pdf_header_footer['lbb_pdf_global_font_val'] : $lbb_pdf_global_font_val;
    $lbb_pdf_footer_content = (!empty($lbb_pdf_header_footer['lbb_pdf_footer_content'])) ? $lbb_pdf_header_footer['lbb_pdf_footer_content'] : $lbb_pdf_footer_content;
    $lbb_pdf_header_title = (!empty($lbb_pdf_header_footer['lbb_pdf_header_title'])) ? $lbb_pdf_header_footer['lbb_pdf_header_title'] : $lbb_pdf_header_title;
}

?>


<form method="POST" id="save-pdf-header-footer-data">
    <div class="lbb-container">
        <div class="lbb-content">
            <div class="lbb-box lbb-section-bg-box lbb-mb-20">
                <!-- <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                    <h2>When a new chat request comes in, how do you want to be notified</h2>
                </div> -->
                <div class="lbb-row">
                    <div class="lbb-col-12">
                        <div class="lbb-row">
                            <div class="lbb-col-3">
                                <div class="lbb-form-group">
                                    <label for="lbb_pdf_header">Header</label>
                                    <input type="text" name="lbb_pdf_header" id="lbb_pdf_header" value="<?php echo $lbb_pdf_header; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_pdf_header; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="lbb-row">

                            <div class="lbb-col-3">
                                <div class="lbb-form-group">
                                    <label for="lbb_pdf_footer">Footer</label>
                                    <input type="text" name="lbb_pdf_footer" id="lbb_pdf_footer" value="<?php echo $lbb_pdf_footer; ?>" class="lbb-input-field lbb-color-picker" data-default-color="<?php echo $lbb_pdf_footer; ?>" data-css-variable="lbb-chat-heading-bg-color" />
                                </div>
                            </div>
                        </div>
                        <div class="lbb-row">

                            <div class="lbb-col-3">
                                <div class="lbb-form-group js-select2-wrapper">
                                    <label>Global PDF Font family: <span class="lbb-box-icon" data-tooltip="These are the only fonts supported by the PDF library"><span class="dashicons dashicons-warning"></span></span></label>

                                    <select name="lbb_pdf_global_font_val" class="js-select2 lbb-input-field">
                                        <?php foreach ($supported_font as $key => $fontlist) {
                                            $sf_selected = ($lbb_pdf_global_font_val == $key) ? 'selected' : '';
                                         ?>
                                            <option value="<?php echo $key; ?>" <?php echo $sf_selected; ?>><?php echo $fontlist; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="lbb-row">
                            
                            <div class="lbb-col-3">
                                <div class="lbb-form-group">
                                    <label>Logo Image: <span class="lbb-box-icon" data-tooltip="It'll be displayed in the header"><span class="dashicons dashicons-warning"></span></span></label>
                                    <div class="lbb-common-image-upload-outer lbb-image-upload-container lbb-image-upload-container-with-preview <?php echo (!empty($lbb_pdf_logo_upload)) ? '' : 'lbb-no-img' ?>">
                                        <div class="lbb-bot-user-image ">
                                            <a class="lbb-image-upload-button lbb-common-image-upload" href="javascript:void(0)">Upload Image</a>
                                            <input type="hidden" id="lbb_pdf_logo_upload" name="lbb_pdf_logo_upload" value="<?php echo $lbb_pdf_logo_upload; ?>">
                                        </div>
                                        <div class="lbb-image-preview-container">
                                            <img src="<?php echo $lbb_pdf_logo_upload; ?>" alt="Preview Image" class="lbb-preview-image">
                                            <div class="lbb-image-actions">
                                                <span class="edit-icon"><span class="dashicons dashicons-edit"></span></span>
                                                <span class="delete-icon"><span class="dashicons dashicons-trash"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <div class="lbb-row">
                            <div class="lbb-col-6">
                                <div class="lbb-form-group">
                                    <label for="lbb_pdf_footer_content">Enter Footer Copyright Content</label>
                                    <textarea id="lbb_pdf_footer_content" name="lbb_pdf_footer_content" class="lbb-input-field" type="text" ><?php echo $lbb_pdf_footer_content; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="lbb-row">
                            <div class="lbb-col-6">
                                <div class="lbb-form-group">
                                    <label for="lbb_pdf_header_title">Enter Header Title:</label>
                                    <textarea id="lbb_pdf_header_title" name="lbb_pdf_header_title" class="lbb-input-field" type="text" ><?php echo $lbb_pdf_header_title; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="lbb-row">
                            <div class="pdf-content pdf_buttons">
                                <a href="javascript:void(0)" class="preview-header-btn preview_btn lbb-btn lbb-black-btn">Preview Header</a>
                                <a href="javascript:void(0)" class="preview-footer-btn preview_btn lbb-btn lbb-black-btn" >Preview Footer</a>
                            </div>

                            <div class="preview-header-section" style="display: none;">
                                <div class="logo-section"><img style="width: 80px;" src=""></div>
                                <div class="header-title"></div>
                                <div class="close-btn header-close">X</div>
                            </div>
                            <div class="preview-footer-section" style="display: none;">
                                <div class="py-4">
                                  <div class="container text-center">
                                    <p class="text-muted mb-0 py-2 pdf-footer-text"></p>
                                  </div>
                                </div>
                                <div class="close-btn footer-close">X</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lbb-popup-btn-footer lbb-text-center lbb-chatflow-footer-action">
        <button id="lbb-save-pdf-settings-btn" class="lbb-btn lbb-btn-primary" type="submit">Save</button>
        <button id="lbb-save-pdf-settings-btn-top" class="lbb-btn lbb-btn-post-fix-top-right lbb-btn-primary" type="submit">Save</button>
    </div>
</form>