<?php 
include(LBB_ABS_URL.'/includes/simple_html_dom.php');

if (isset($_POST['url'])) {
    $siteURL = $_POST['url'];

    // Create a new simple_html_dom object
    $html = file_get_html($siteURL);

   try {
        if ($html) {
            // Find all anchor links and display them
            $cleanedString = $html->find('p');
            $paragraph_plain = array();

            
            $final_string = '';
            $paragraph_plain = array();
            foreach($cleanedString as $paragraph) {
                $paragraph_plain[] = trim(strip_tags($paragraph));
            }

            foreach ($paragraph_plain as $outputArray) {
               $paragraph_words = explode(' ', $outputArray);

                if(count($paragraph_words) > 3){
                    $final_string .= $outputArray . "\n\n";
                }
            }



           /* $paragraph_plain = array();
            foreach($cleanedString as $paragraph) {
                $paragraph_plain[] = trim(strip_tags($paragraph));
            }

            $final_string = '';
            foreach ($paragraph_plain as $outputArray) {
                $words = explode(' ', $str);

                if(count($words) > 3){
                    $final_string = $outputArray . "\n\n";
                }
            }*/


           

            $random_id = time().''.$key; ?>

            <li class="lbb-url-listing-item" id="lbb-url-listing-item-<?php echo $random_id; ?>">
                <div class="lbb-url-listing-inside">
                    <div class="lbb-root url-listing-trained-wrapper">
                        <span class="lbb-url-listing-trained">Trained</span>
                    </div>
                    <div class="lbb-url-listing-text">
                        <span class="lbb-url-listing-url-text"><?php echo $siteURL; ?></span>
                    </div>
                    <div class="lbb-url-listing-action">
                        <button class="lbb-url-listing-edit-icon" tabindex="0" type="button" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>" aria-label="delete">
                            <i class='bx bx-message-square-edit'></i>  Edit Content
                        </button>
                        <button class="lbb-url-listing-delete-icon" tabindex="0" type="button" aria-label="delete">
                            <i class='bx bxs-trash-alt'></i>
                        </button>
                    </div>
                </div>
                <div class="lbb-textarea-edit-url-content">
                    <div class="lbb-edit-content-wrapper">
                        <div class="lbb-form-group">
                            <label for="ai_url_message_<?php echo $random_id; ?>">Update Content:</label>
                            <input type="hidden" class="lbb_ai_url_title" id="ai_url_message_<?php echo $random_id; ?>" name="lbb_ai_url_content[<?php echo $random_id; ?>]['title']"  value="<?php echo $siteURL; ?>">

                            <textarea class="lbb-input-field lbb_ai_url_content" id="ai_url_message_<?php echo $random_id; ?>" name="lbb_ai_url_content[<?php echo $random_id; ?>]['content']"><?php echo $final_string; ?></textarea>

                            <div class="lbb-popup-listing-edit-text-action">
                                 <button class="lbb-btn lbb-btn-gray lbb-save-exit-text-for-url" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>">
                                    Close
                                </button>
                                <button class="lbb-btn lbb-btn-secondary  lbb-save-exit-text-for-url" data-edit="#lbb-url-listing-item-<?php echo $random_id; ?>">
                                    Update Content
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <?php /*$outputArrays = lbbSplitAndCombineArrays($paragraph_plain);

            
            foreach ($outputArrays as $outputArray) {
                echo implode("\n", $outputArray) . "<br><hr><br>";
            }*/

        }
    } catch (ParseException $e) {
        echo 0;
    }
}
/*else if (isset($_GET['uf'])) {
    
    $siteURL = 'https://newdemo.membershipsitechallenge.com/test-15/';

    // Create a new simple_html_dom object
    $html = file_get_html($siteURL);

    if ($html) {
        // Find all anchor links and display them
        $cleanedString = $html->find('p');
        $paragraph_plain = array();

        
        
        $paragraph_plain = array();
        foreach($cleanedString as $paragraph) {
            $paragraph_plain[] = trim(strip_tags($paragraph));
        }

        foreach ($paragraph_plain as $outputArray) {
           $paragraph_words = explode(' ', $outputArray);

            if(count($paragraph_words) > 3){
                $final_string.= $outputArray . "\n\n";
            }
        }
    }
} */?>