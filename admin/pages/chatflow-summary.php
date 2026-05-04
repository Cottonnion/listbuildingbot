<?php
$chatflow_data = get_post_meta( $chatflow_id, 'selected_url', true );
$page_name = "";
if($chatflow_data){
    $page_post_ids = explode(',',$chatflow_data); 
    if($page_post_ids){
        foreach($page_post_ids as $page_post_id){
            $get_name = get_post_field( 'post_name', $page_post_id );
            $page_name .= '<li><a target="_blank" href="'.site_url().'/'.$get_name.'">'.site_url().'/'.$get_name.'</a></li>';
        }
    }
}

$lbb_hide_popup = "display:none;";
if($how_to_show == "minimized" && ($chatflow_data == '' && $enter_url == "")){
    $lbb_hide_popup = '';
}

?>
<section class="lbb-outer-section lbb-edit-mode has-page-title lbb-vertical-section">
      <div class="lbb-container lbb-vertical-container">
          <div class="lbb-vertical-content-up">
              <div class="lbb-content lbb-vertical-content lbb-w-100-important lbb-ml-40 lbb-mr-40 lbb-mt-40">
                <div class="lbb-container">
                 	<div class="lbb-thank-you-box lbb-mb-20">
                        <div class="lbb-heading-subheading-wrapper congrats-section">
                          <h1>🎉Congrats! The Chatbot is now ready!🎉</h1>
                        </div>

                        <div class="lbb-heading-subheading-wrapper lbb-mb-20 lbb-mt-20 lbb-alert lbb-alert-error lbb-popup-pages" style="<?php echo $lbb_hide_popup; ?>">
                            <span><b>IMPORTANT:</b> Please note you are using a popup bot but you have not configured the pages where it should appear. </br>Please go to the settings tab and add the URLs of the pages where you want to show the popup bot.</span>
                        </div>
                    </div>
                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 shortcode-show-for-inpage" style="<?php echo $how_to_show == "minimized" ? 'display: none;' : ''; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>In-page Bot</h2>
                            <p class="website-explanation">This is an in-page bot. This means you can publish the shortcode below on any WordPress page.</p>
                            <div class="lbb-shortcode-wrapper lbb-mt-10">
                                <div class="shortcode-copy">
                                <span id="copyable_chatflow_shortcode" class="copyable-shortcode-text">[ListBuildingBot id="<?php echo $chatflow_id; ?>"]</span> <span data-id="copyable_chatflow_shortcode" class="copy-btn" onclick="lbb_copy_to_clipboard(this)"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
                            </div> 
                            </div>
                        </div>
                    </div>

                    

                    

                    

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 shortcode-show-for-maximized" style="<?= ($chatflow_data != '' || $enter_url != "") ? '': 'display: none;'; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>You've configured it to show on these pages.</span> </h2>
                            <div class="lbb-page-list-wrapper">
                                <ul>
                                    <?php echo $page_name; ?>
                                    <li class="shortcode-enter-url"><a href="<?php echo $enter_url; ?>"><?php echo $enter_url; ?></a></li>
                                </ul> 
                            </div>
                            <div class="lbb-enter-page-url" style="display: none;">
                                <ul>
                                    <li></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20" style="<?php // echo $how_to_show == "minimized" ? 'display: none;' : ''; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>Embed Code:</h2>
                            <p class="website-explanation">This is the embed code. You can publish this on any page, even if it's not a WordPress site.</p>
                            <div class="lbb-shortcode-wrapper lbb-mt-10">
                                <div class="shortcode-copy">
                                    <span id="copyable_chatflow_embed_shortcode" class="copyable-embed-text">&lt;div id="lbb-inline-app"&gt;  &lt;script type='text/javascript' src='<?php echo home_url(); ?>/?embed=true&amp;chat_id=<?php echo $chatflow_id; ?>'&gt;&lt;/script&gt; &lt;/div&gt;</span>
                                    <span data-id="copyable_chatflow_embed_shortcode" class="copy-btn" onclick="lbb_shortcode_copy_to_clipboard(this)"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
                                </div> 

                            </div>
                        </div>
                    </div>

                    <?php 
                    $show_automation_options = "display:none";
                    $automation_listing = "";
                    $explode_automation_status = ($lbb_automation_status) ?  explode(',',$lbb_automation_status) : '';
                    if(!empty($explode_automation_status)){
                        $show_automation_options = "";
                        foreach($explode_automation_status as $explode_automation){
                            $automation_listing .= '<li data-automation-name="'.$explode_automation.'">'.ucfirst($explode_automation).'</li>';
                        }
                    }
                    ?>

                    <div class="lbb-box lbb-section-bg-box lbb-mb-20 shortcode-show-for-platform" style="<?php echo $show_automation_options; ?>">
                        <div class="lbb-heading-subheading-wrapper lbb-mb-20">
                            <h2>You've connected this Chatbot to these platforms:</h2>
                            <ul class="automation-listing">
                                <?php echo $automation_listing; ?>
                            </ul> 
                        </div>
                    </div>

                </div>



                <div class="lbb-modal-container" id="lbb-embedd-shortcode">
                    <div class="lbb-modal-content">
                        <div class="lbb-modal-body">
                            <!-- <div class="shortcode-copy">
                                <span id="copyable_chatflow_embed_shortcode" class="copyable-embed-text">
                        
                                </span>
                                <span data-id="copyable_chatflow_embed_shortcode" class="copy-btn" onclick="lbb_shortcode_copy_to_clipboard(this)"> <i class="fa fa-files-o" aria-hidden="true"></i> Copy </span>
                            </div>  -->
                        </div>
                        <footer class="lbb-header-wrapper">
                            <button class="lbb-btn lbb-btn-secondary lbb-close">Close</button>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>