<?php 
include(LBB_ABS_URL.'/includes/simple_html_dom.php');

if (isset($_POST['url'])) {
    $siteURL = $_POST['url'];

    // Create a new simple_html_dom object
    $html = file_get_html($siteURL);

    if ($html) {
        // Find all anchor links and display them
        $links = $html->find('a');
        $visitedLinks = array();

        $visitedLinks[] = $siteURL;
        foreach ($links as $link) {
           $href = $link->href;
           $visitedLinks[] = $href;
           

           continue;

            // Check if the href is not empty and does not start with 'http' or 'https'
            if (!empty($href) && !preg_match('/^(http|https):\/\//', $href)) {
                // Check for unwanted links
                if ($href != '#' && $href != 'javascript:void(0)') {
                    // Check if the link has not been visited before
                    //if (!in_array($href, $visitedLinks)) {
                        echo '<li><a href="' . $href . '">' . $link->plaintext . '</a></li>';
                        $visitedLinks[] = $href;
                    //}
                }
            }
        }

        $validUrls = array_filter($visitedLinks, 'lbbIsValidURLWithDomain');
        $final_urls = array();
        $final_return_urls = array();
        foreach ($validUrls as $key =>  $url) {
            if (!lbbUrlContainsFragment($url) && lbbUrlFromMainDomain($url, $_POST['url']) && !in_array($url, $final_urls)) {

                $check_url = rtrim($URL, '/');

                if (in_array($check_url, $final_urls)) {
                   continue;
                }

                $final_urls[] = $url;
                //echo '<li data-url="'.$url.'"><a href="' . $url . '">' . $url  . '</a></li>';

                $random_id = time().''.$key;
                $random_section_id = md5($url);
                
               

                $final_return_html = '<li class="lbb-url-listing-item" id="lbb-url-list-'.$random_section_id.'">
                	<div class="lbb-url-listing-inside">
                		<div class="lbb-root checkbox">
                			<span class="checkbox-custom-style">
                                <input id="lbb_scrape_url_'.$random_id.'" type="checkbox" name="lbb_scrape_urls[]" value="'.$url.'" class="custom-checkbox-input lbb-url-listing-checkbox">
                                <label for="lbb_scrape_url_'.$random_id.'" class="custom--checkbox"></label>
                            </span>
                		</div>
                		<div class="lbb-url-listing-text">
                			<span class="lbb-url-listing-url-text">'.$url.'</span>
                		</div>
                		<div class="lbb-url-listing-action">
	                		<button class="lbb-url-listing-delete-icon" tabindex="0" type="button" aria-label="delete">
	                			<i class="bx bxs-trash-alt"></i>
	                		</button>
	                	</div>
                	</div>
                	
                </li>';

                $final_return_urls[$random_section_id] = $final_return_html;
                
            }
        }

        echo json_encode($final_return_urls);

        $html->clear();
    } else {
        echo 0;
    }
}



?>