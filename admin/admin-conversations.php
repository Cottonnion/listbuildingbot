<?php
function lbbgetCountryName($countryCode) {
    $countries = array
    (
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    );
    return isset($countries[$countryCode]) ? $countries[$countryCode] : 'Unknown';
}

function lbb_get_user_name($user_id){
    $user_data = get_userdata($user_id);
    if ($user_data) {
        $name = $user_data->display_name;
    }else{
        $name = '';
    }
    return $name;
}

function lbb_get_user_email($user_id){
    $user_data = get_userdata($user_id);
    if ($user_data) {
        $email = $user_data->email;
    }else{
        $email = '';
    }
    return $email;
}

function lbb_print_tags($tag_ids){
    $tag_ids = explode(',',$tag_ids);
    $tagM = new TagsManager();
    $results = $tagM->loadByIds($tag_ids);
    $names = [];
    if(!empty($results)){
        foreach ($results as $key => $result) {
            $names[] = $result['name'];
        }
    }
    return !empty($names) ? implode(', ', $names) : '';
}
add_action('wp_ajax_lbb_load_firebase_id', 'lbb_load_firebase_id');
function lbb_load_firebase_id(){
  $conversation_id = $_POST["conversation_id"];
  $con = new ConversationManager();
  $rs = $con->getConversationById($conversation_id);
  echo json_encode(array('firebase_id' => $rs['firebase_id']));exit;
}
add_action('wp_ajax_lbb_load_message_data', 'lbb_load_message_data');
function lbb_load_message_data(){

    if(isset($_POST["conversation_id"])){
        $conversation_id = $_POST["conversation_id"];
        $messageManager = new MessageManager();
        $chat_list = $messageManager->getMessagesByConversationIdWithOffset($conversation_id,50,0);
       
        
        $chat_list = array_reverse($chat_list);
        $userchat_list_html = "";
        $prev_msg_date = 0;

        ob_start();
        include(LBB_ABS_URL.'admin/templates/chat/attachment.php');
        $attachment_html = ob_get_clean();
        
        foreach($chat_list as $single_msg){
          $message = stripslashes($single_msg['message_text']);
          $message = strip_tags($message);

          $message_meta = maybe_unserialize($single_msg['message_meta']);
          $image = isset($message_meta['image']) ? $message_meta['image'] : '';
          $attachment = isset($message_meta['attachment']) ? $message_meta['attachment'] : '';
          $attachment_name = isset($message_meta['name']) ? $message_meta['name'] : 'Download';

          $audio = isset($message_meta['audio']) ? $message_meta['audio'] : '';
          ob_start();
                include(LBB_ABS_URL.'admin/templates/chat/audio.php');
                $audio_html = ob_get_clean();

          if(!empty($attachment)){
            $message = str_replace('{{url}}',$attachment , $attachment_html);
            $message = str_replace('{{text}}', $attachment_name, $message);
          }else if(!empty($audio)){
            $message = str_replace('{{url}}',$audio , $audio_html);
          }

          $post_id = $single_msg['action_id'];
          $type = get_post_meta( $post_id, 'question_type', true );
          
          $main_admin_html = '';
          if($type == 'outcome' && get_post_meta( $post_id, 'enable_pdf_download', true ) == 1){
            
            $pdf_text = get_post_meta( $post_id, 'download_pdf_button', true );
            
                $action['pdf_button'] = array(
                    'label' => $pdf_text,
                    'link' => site_url().'?lbb-pdf-download=1&sid='.$conversation_id.'&outcome_post_id='.$post_id
                );

                ob_start();
                include(LBB_ABS_URL.'admin/templates/chat/agent-pdf-button.php');
                $pdf_button_html = ob_get_clean();

                //$main_admin_html = str_replace('{{pdf_buttons}}', $pdf_button_html, $main_admin_html);
                $main_admin_html = str_replace('{{pdf_link}}',$action['pdf_button']['link'], $pdf_button_html);
                $main_admin_html = str_replace('{{pdf_label}}',$action['pdf_button']['label'], $main_admin_html);
            }else{
                $main_admin_html = str_replace('{{pdf_buttons}}', '', $main_admin_html);
            }

          $time = $single_msg['sent_time'];
          $is_bot_response = $single_msg['is_bot_response'];
          $user_id = $single_msg['user_id'];
          $agent_id = $single_msg['agent_id'];


         /* $newTZ = new DateTimeZone("UTC");
          $date = new DateTime( $time , $UTC );
          $date->setTimezone( $newTZ );*/

          $new_timezone = $_REQUEST['timezone'];
          $UTC = new DateTimeZone("UTC");
          $newTZ = new DateTimeZone($new_timezone);
          $date = new DateTime( $time , $UTC );
          $date->setTimezone( $newTZ );
          $time_time = $date->format('H:i');
          $msg_date = $date->format('F d Y');
          if($is_bot_response == 1){
            $response = 'user-admin-list';
            $name = "Bot";
            $avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g';
          }else if($agent_id > 0){
            $response = 'user-admin-list';
          }else{
            $response = 'user-visitor-list chat-top-msg';
            if($user_id == 0){
                $name = "Unknown Visitor";
            }else{
                

                $contact_id = lbb_get_contact_id($conversation_id);
                $contact_status = lbb_get_contact_status($contact_id);
                if($contact_status > 0){
                    $name = lbb_get_contact_name($contact_id);
                }else{
                    $name = lbb_get_user_name($user_id);
                }
            }
            $avatar = lbb_user_avatar($user_id);
          }
          if ($prev_msg_date != $msg_date) {
            $userchat_list_html.= '<li class="msg-day-wrapper"> <div class="msg-date-show"> <div class="msg-date-border"></div> <span>'.$msg_date.'</span> <div class="msg-date-border"></div></div> </li>';
          }
            $tag_ids = $single_msg['tags'];
            $tags = lbb_print_tags($tag_ids);
          $userchat_list_html .= '<li class="'.$response.'">
          <div class="message-box-img">
            <span class="chat-icon"><img src="'.$avatar.'"></span>
          </div>
              <div class="message-box-data">
                <div class="message-data">
                  <span class="message-data-name"> '.$name.'</span>
                  <span class="message-data-time">'.$time_time.'</span>';
                  if($tags){
                  $userchat_list_html .= '<span class="message-data-tags">'.$tags.'</span>';

                  }
                $userchat_list_html .= '</div>
                <div class="message my-message">
                  <div class="lbb-user-message">'.$message.$main_admin_html.'</div>
                </div>
              </div>
        </li>';
        $prev_msg_date = $date->format('F d Y');
        }
    
    }
    $con = new ConversationManager();
    $rs = $con->getConversationById($conversation_id);
    $user_id = $rs['user_id'];
    $phone = "";

    $contacts = new LBB_Contacts;
    $contacts = $contacts->getContactByConversationId($conversation_id);
    if($user_id == 0){
        if($contacts){
            $name = $contacts['firstname'];
            $email = $contacts['email'];
            $phone = $contacts['phone'];
        }else{
            $name = "Unknown Visitor";
            $email = '-';
        }
        $avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&d=mp&r=g';
    }else{
        $contact_id = lbb_get_contact_id($conversation_id);
        $contact_status = lbb_get_contact_status($contact_id);
        if($contact_status > 0){
            $name = lbb_get_contact_name($contact_id);
            $phone = $contacts['phone'];
        }else{
            $name = lbb_get_user_name($user_id);
            $email = lbb_get_user_email($user_id);
        }
        //$name = lbb_get_user_name($user_id);
        
        $avatar = lbb_user_avatar($user_id);
    }

    $custom_field_data = "";
    /*Custom Field Data */
    if($contacts){
        $contact_id = $contacts['id'];
        $contactsMeta = new LBB_Contacts;
        $contactsMeta = $contactsMeta->loadContactmetaByContactId($contact_id);
        if($contactsMeta){
            foreach($contactsMeta as $contactMeta){
                $string = $contactMeta['field_name'];
                $parts = explode("_", $string);
                $cf_id = end($parts);

                $customField = new CustomFieldManager();
                $customField = $customField->loadById($cf_id);
                if($customField){
                    $custom_field_data .= '<div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>'.$customField['label'].':</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$contactMeta['field_value'].'</div>
                            </div>
                        </div>';
                }
            }
        }
    }

    //echo $custom_field_data;

    $extra_info = $rs['extra_info'];
    $ip_address = $rs['ip_address'];
    $start_time = $rs['start_time'];
    $date = new DateTime($dateString);

    // Format the date
    $formattedDate = $date->format('D, M jS');
    $formattedTime = $date->format('H:i');


    $location = "";
    $browser = "";
    $url = "";
    
    if($extra_info){
        $extra_info = unserialize( $extra_info, [ 'allowed_classes' => false ] );

        if ( ! is_array( $extra_info ) ) { $extra_info = []; }

        
        $location = $extra_info['location'];
        
        $location = lbbgetCountryName($location);;

        $browser = $extra_info['browser'];
        $url = $extra_info['url'];
    }

    $encodedEmail = urlencode($email);

    $side_popup_data .= '<div class="lbb-avtar-fin-flex">
                    <div class="user-info-main">
                        <div class="user-avtar-icon-info">
                            <i class="bx bx-user-circle"></i>
                        </div>
                    </div>
                    <div class="lbb-user-name-btn-wrapper">
                        <div class="lbb-user-info-name">'.$name.'</div>
                        <a target="_blank" href="'.site_url().'/wp-admin/admin.php?page=listbuildingbot-contacts&email='.$encodedEmail.'" class="lbb-btn lbb-primary-btn lbb-small">Associate contact</a>
                    </div>
                </div>

                <div class="lbb-user-personal-info-heading lbb-mt-20">
                    <h3>Other Info</h3>
                </div>

                <div class="lbb-user-info-meta-container">';
                if($location){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-main">
                            <div class="user-info-meta-icon">
                                <i class="bx bx-world"></i>
                            </div>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">Location <small>'.$location.'</small></div>
                        </div>
                    </div>';
                }

                if($browser){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-main">
                            <div class="user-info-meta-icon">
                                <i class="bx bx-window-alt"></i>
                            </div>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">Browser <small>'.$browser.'</small></div>
                        </div>
                    </div>';
                }
                if($url){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-main">
                            <div class="user-info-meta-icon">
                                <i class="bx bx-link"></i>
                            </div>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">URL <small>'.$url.'</small></div>
                        </div>
                    </div>';
                }

                if($ip_address){
                     $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-main">
                            <div class="user-info-meta-icon">
                                <i class="bx bx-current-location"></i>
                            </div>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">IP Address <small>'.$ip_address.'</small></div>
                        </div>
                    </div>';
                }

                $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-main">
                            <div class="user-info-meta-icon">
                                <i class="bx bx-user-circle"></i>
                            </div>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">Created <small>'.$formattedDate.' at '.$formattedTime.'</small></div>
                        </div>
                    </div>
                </div>

                <div class="lbb-user-personal-info-heading">
                    <h3>User Personal Info</h3>
                </div>
                <div class="lbb-user-info-meta-container">
                    <div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                            <label>Name:</label>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">'.$name.'</div>
                        </div>
                    </div>';

                    if($email){
                        $side_popup_data .= '<div class="lbb-user-info-meta-row">
                            <div class="user-info-field-lbl">
                                <label>Email:</label>
                            </div>

                            <div class="lbb-info-content">
                                <div class="lbb-user-info-meta-value">'.$email.'</div>
                            </div>
                        </div>';
                    }
                    

                    if($phone){
                        $side_popup_data .= '<div class="lbb-user-info-meta-row">
                        <div class="user-info-field-lbl">
                            <label>Phone:</label>
                        </div>

                        <div class="lbb-info-content">
                            <div class="lbb-user-info-meta-value">'.$phone.'</div>
                        </div>
                    </div>';
                    }
                    

                $side_popup_data .= $custom_field_data;
                $side_popup_data .= '</div>';
    $output['side_popup_data'] = $side_popup_data;
    $output['avatar'] = $avatar;
    $output['name'] = $name;
    $output['firebase_id'] = $rs['firebase_id'];
    $output['is_closed'] = $rs['is_closed'];
    $output['userchat_list_html'] = $userchat_list_html;
    echo json_encode($output);die;  
}

add_action('wp_ajax_lbb_check_notifications', 'lbb_check_notifications');
function lbb_check_notifications(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';
    $cids = $wpdb->get_var("SELECT count(*) as count FROM $table_name WHERE is_bot_response = 0 AND agent_id = 0 AND is_read = 0");
    echo $cids;
    wp_die();
}


add_action('wp_ajax_lbb_notifications_firebase', 'lbb_notifications_firebase');
add_action('wp_ajax_lbb_notifications_firebase', 'lbb_notifications_firebase');
function lbb_notifications_firebase(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';
    $table_conversation_name = $wpdb->prefix . 'lbb_conversations';
    $conversations = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT
                g.firebase_id
            FROM
                $table_name t
            JOIN
                $table_conversation_name g ON t.conversation_id = g.conversation_id
            WHERE
                t.is_read = %d
                AND t.is_bot_response = 0
                AND t.agent_id = 0
                AND t.sent_time >= UTC_TIMESTAMP() - INTERVAL 5 MINUTE
                AND g.firebase_id != ''
            GROUP BY
                t.conversation_id
            ORDER BY
                t.sent_time DESC",
            0 
        )
    );
   // $messageCounts = array();
    $messageCounts = 0;
   echo json_encode($conversations);
   exit;
}

add_action('wp_ajax_lbb_read_unseen_messages', 'lbb_read_unseen_messages');
function lbb_read_unseen_messages(){

    global $wpdb;

    $user_browser_info = sanitize_text_field( $_POST['browser_uniq_id'] ?? '' );
    $utc_time = gmdate('Y-m-d H:i:s');

    $query_other = $wpdb->prepare(
        "SELECT m.*
        FROM {$wpdb->prefix}lbb_messages as m JOIN {$wpdb->prefix}lbb_conversations as c ON c.conversation_id = m.conversation_id
        WHERE TIMESTAMPDIFF(MINUTE, m.sent_time, %s) <= 5 and m.conversation_id != 0 and m.is_read = 0 and  m.is_bot_response <> 1 AND m.agent_id = 0 and c.status != 'L' AND c.is_published=1
        ORDER BY m.message_id ASC",
        $utc_time
    );

     $unread_message_other = $wpdb->get_results($query_other);
     
     foreach ($unread_message_other as $result) {
        // Access individual result properties, e.g., $result->message_id

        $notification_delivered = $result->notification_delivered;
        $notification_delivered_array = array();
        if ($notification_delivered != '') {
            $notification_delivered_array = explode(',', $notification_delivered);
        }
        if ($user_browser_info != '') {
           $notification_delivered_array[] = $user_browser_info;
        }
        


        array_filter($notification_delivered_array);

        $notification_delivered_string = implode(',', $notification_delivered_array);


        $table_name = $wpdb->prefix . 'lbb_messages';

        $data = array(
            'notification_delivered' => $notification_delivered_string,
            // Add more columns and values as needed
        );

        $where = array(
            'message_id' => $result->message_id, // Specify the condition for updating, for example, where column_id equals 1
        );


       

        $wpdb->update($table_name, $data, $where);



    }


    $query = $wpdb->prepare(
        "SELECT m.*
        FROM {$wpdb->prefix}lbb_messages as m JOIN {$wpdb->prefix}lbb_conversations as c ON c.conversation_id = m.conversation_id
        WHERE TIMESTAMPDIFF(MINUTE, m.sent_time, %s) <= 2 and m.conversation_id != 0 and m.is_read = 0 and  m.is_bot_response <> 1 AND m.agent_id = 0 and c.status = 'L' and 
        m.notification_delivered NOT LIKE %s
        ORDER BY m.message_id ASC",
        $utc_time,
        '%' . $wpdb->esc_like( $user_browser_info ) . '%'
    );


    $unread_message = $wpdb->get_results($query);

    // Output or use the results as needed
   foreach ($unread_message as $result) {
        // Access individual result properties, e.g., $result->message_id

        $notification_delivered = $result->notification_delivered;
        $notification_delivered_array = array();
        if ($notification_delivered != '') {
            $notification_delivered_array = explode(',', $notification_delivered);
        }
        if ($user_browser_info != '') {
           $notification_delivered_array[] = $user_browser_info;
        }
        


        array_filter($notification_delivered_array);

        $notification_delivered_string = implode(',', $notification_delivered_array);


        $table_name = $wpdb->prefix . 'lbb_messages';

        $data = array(
            'notification_delivered' => $notification_delivered_string,
            // Add more columns and values as needed
        );

        $where = array(
            'message_id' => $result->message_id, // Specify the condition for updating, for example, where column_id equals 1
        );


       

        $wpdb->update($table_name, $data, $where);



    }

    echo json_encode($unread_message);
    exit;

}


add_action('wp_ajax_lbb_notifications_count_heartbeat', 'lbb_notifications_count_heartbeat');
function lbb_notifications_count_heartbeat(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'lbb_messages';
    $conversations_table_name = $wpdb->prefix . 'lbb_conversations';
    $posts = "SELECT GROUP_CONCAT(post_id) as post_ids FROM ".$wpdb->prefix ."postmeta WHERE meta_key = 'lbb_livechat_bell_notification' AND meta_value = 'yes'";

    $all_posts = $wpdb->get_var($posts);
    
    if(!empty($all_posts)){
        $conversations = $wpdb->get_var("SELECT COUNT(*) as message_count FROM $conversations_table_name JOIN $table_name ON $conversations_table_name.conversation_id = $table_name.conversation_id WHERE is_read = 0 AND agent_id = 0 AND is_bot_response <> 1 AND ($conversations_table_name.chatflow_id IN ($all_posts) OR $conversations_table_name.status = 'L')");

    }else{
        $conversations = $wpdb->get_var("SELECT COUNT(*) as message_count FROM $conversations_table_name JOIN $table_name ON $conversations_table_name.conversation_id = $table_name.conversation_id WHERE is_read = 0 AND agent_id = 0 AND is_bot_response <> 1 AND $conversations_table_name.status = 'L'");
    }
    

    echo $conversations;
    wp_die();
}

add_action('wp_ajax_lbb_notifications_heartbeat', 'lbb_notifications_heartbeat');
function lbb_notifications_heartbeat(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';
    $conversation_table = $wpdb->prefix . 'lbb_conversations';
    $offset = $_POST['offset'];
    $filterMode = isset($_POST['filterMode']) ? $_POST['filterMode'] : array();
    $current_conv = isset($_POST['current_conv']) ? $_POST['current_conv'] : 0;
    $limit = 10;
    $req_user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $userQ = ' AND user_id = '.$req_user_id;
    $conversation_ids = stripslashes($_REQUEST['conv_ids']);
    $ids = json_decode($conversation_ids, true);
   
    $max = max($ids);
    $min = min($ids);
    
    $cids = $wpdb->get_var("SELECT GROUP_CONCAT(conversation_id) as ids FROM $table_name WHERE conversation_id > $max AND is_bot_response <> 1 AND conversation_id IN (
        SELECT conversation_id 
        FROM $conversation_table
        WHERE status = '".$filterMode."' ) AND agent_id = 0 AND is_published=1 ORDER BY `conversation_id` DESC");
    if($cids){
        $cids = explode(",", $cids);
    }
    $newConversaions = array();
    
    if($cids){
        $ids = array_merge($ids, $cids);
        $new_C_M = "";
        if($filterMode == 'L'){
            $new_C_M = $wpdb->get_results("SELECT *, (SELECT firebase_id 
            FROM $conversation_table WHERE $conversation_table.conversation_id = $table_name.conversation_id) AS firebase_id FROM $table_name WHERE conversation_id > $max AND conversation_id IN (SELECT conversation_id FROM $conversation_table WHERE status = 'L' ) AND is_published=1 AND is_bot_response <> 1 ".$userQ."" , ARRAY_A);
        }

        if(!empty($new_C_M)){
            foreach($new_C_M as $single_msg){
                $cd = $single_msg['conversation_id'];
                $firebase_id = $single_msg['firebase_id'];
                $message = stripslashes($single_msg['message_text']);
                $message = strip_tags($message);
                $user_id = $single_msg['user_id'];
                $conversation_id = $single_msg['conversation_id'];
                $time = $single_msg['sent_time'];
                $conv_ids[] = $conversation_id;
                $name = "Unknown Visitor";
                if($user_id == 0){ 
                    $contacts = new LBB_Contacts;
                    $contacts = $contacts->getContactByConversationId($conversation_id);
                    if($contacts){
                        $name = $contacts['firstname'];
                    }else{
                        $name = "Unknown Visitor";
                    }
                }else{

                    $contact_id = lbb_get_contact_id($conversation_id);
                    $contact_status = lbb_get_contact_status($contact_id);
                    if($contact_status > 0){
                        $name = lbb_get_contact_name($contact_id);
                    }else{
                        $name = lbb_get_user_name($user_id);
                    }

                    //$name = lbb_get_user_name($user_id);
                }
                $newConversaions[$cd] = '<li class="clearfix user-admin-list-left-side" id="chat-list-user-'.$conversation_id.'" data-id="'.$conversation_id.'" data-count="0" data-time="'.strtotime($time).'" data-nnn="'.$firebase_id.'" data-firebaseid="'.$firebase_id.'"><a href="javascript:void(0);" class="user-list-left-sidebar" data-userid="'.$user_id.'" ><div class="lbb-delete-conversation-main"><span class="lbb-delete-conversation"><i class="bx bxs-trash-alt"></i></span></div>
                        <span class="chat-icon"><img src="https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g"></span>
                        <div class="about">
                            <div class="name" data-msgcount="0">'.$name.'</div>
                                <div class="status">'.$message.'</div>
                        </div>
                        <span class="lbb-message-count"></span> 
                        <span class="lbb-notification-time-text">'.LBBTimeElapsedString($time).'</span> 
                    </a></li>';
            }
        }
    }
    

    // Query to fetch the count of new messages for each conversation (adjust the condition as needed)
    $conversations = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT conversation_id, COUNT(*) as message_count FROM $table_name WHERE is_read = %d AND is_bot_response <> 1 AND agent_id = 0 AND conversation_id IN(".implode(',',$ids).") GROUP BY conversation_id ORDER BY sent_time DESC",
            0 // 0 represents unread messages; adjust as needed
        )
    );

    $conversations_unread = array();
    if ($current_conv != 0) {
           $conversations_unread = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT message_id, conversation_id, message_text, sent_time FROM $table_name WHERE is_read = %d AND is_bot_response <> 1 AND agent_id = 0 AND conversation_id = $current_conv ORDER BY sent_time DESC",
                0 // 0 represents unread messages; adjust as needed
            )
        );

    }
    

   
    $live_chat_count = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT COUNT(*) AS row_count
            FROM $table_name AS m
            INNER JOIN $conversation_table AS c ON m.conversation_id = c.conversation_id
            WHERE c.status = 'L' AND is_read = 0 AND is_bot_response = 0 AND m.agent_id = 0;"
        )
    );

    $ai_chat_count = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT COUNT(*) AS row_count
            FROM $table_name AS m
            INNER JOIN $conversation_table AS c ON m.conversation_id = c.conversation_id
            WHERE c.status IN ('A', 'T') AND is_read = 0 AND is_bot_response = 0 AND m.agent_id = 0 AND c.is_published=1;"
        )
    );

    $bot_chat_count = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT COUNT(*) AS row_count
            FROM $table_name AS m
            INNER JOIN $conversation_table AS c ON m.conversation_id = c.conversation_id
            WHERE c.status = '' AND is_read = 0 AND is_bot_response = 0 AND m.agent_id = 0 AND c.is_published=1;"
        )
    );

    /*$unread_message_data = array();
    foreach ($conversations_unread as $conversation) {
        $unread_message_data[] = $conversation;
    }*/

    $new_timezone = $_REQUEST['timezone'];
    $unread_message_data = array();
    foreach ($conversations_unread as $conversation) {
        $UTC = new DateTimeZone("UTC");
        $newTZ = new DateTimeZone($new_timezone);
        $date = new DateTime( $conversation->sent_time , $UTC );
        $date->setTimezone( $newTZ );
        $time_time = $date->format('H:i');

        $conversation->sent_time = $time_time;
        $unread_message_data[] = $conversation;
    }


    $messageCounts = array();
    foreach ($conversations as $conversation) {
        $conversationId = $conversation->conversation_id;
        $messageCount = $conversation->message_count;
        $messageCounts[$conversationId] = $messageCount;
    }
    echo json_encode(
        array(
        'messageCounts' => $messageCounts,
        'newConversations' => $newConversaions,
        'live_chat_count' => $live_chat_count,
        'ai_chat_count' => $ai_chat_count,
        'bot_chat_count' => $bot_chat_count,
        'unread_current_user' => $unread_message_data,
        )
    );
    wp_die();
}


add_action('wp_ajax_lbb_user_notification_heartbeat', 'lbb_user_notification_heartbeat');
add_action('wp_ajax_nopriv_lbb_user_notification_heartbeat', 'lbb_user_notification_heartbeat');
function lbb_user_notification_heartbeat(){

    lbb_access_control_allow_origin();
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';
    $current_conv = isset( $_POST['conversation_id'] ) ? intval( $_POST['conversation_id'] ) : 0;


    $conversations_unread = array();
    if ($current_conv != 0) {
           $conversations_unread = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT message_id, conversation_id, message_text, sent_time FROM $table_name WHERE is_read = %d AND (is_bot_response = 0 AND agent_id > 0) AND conversation_id = %d ORDER BY sent_time DESC",
                0,
                $current_conv
            )
        );

    }



    $allowed_timezones = timezone_identifiers_list();
    $new_timezone = ( isset( $_REQUEST['timezone'] ) && in_array( $_REQUEST['timezone'], $allowed_timezones, true ) )
        ? $_REQUEST['timezone']
        : 'UTC';
    $unread_message_data = array();
    $messages_id_new = array();
    foreach ($conversations_unread as $conversation) {
        $UTC = new DateTimeZone("UTC");
        $newTZ = new DateTimeZone($new_timezone);
        $date = new DateTime( $conversation->sent_time , $UTC );
        $date->setTimezone( $newTZ );
        $time_time = $date->format('H:i');

        $conversation->sent_time = $time_time;
        $unread_message_data[] = $conversation;
        $messages_id_new[] = $conversation->message_id;
    }

    if ($current_conv != 0 && !empty($messages_id_new)) {
        lbb_messages_update($current_conv, $messages_id_new);
    }
    
    /*$new_timezone = $_REQUEST['timezone'];
    foreach ($conversations_unread as $key => $result) {

        
        $UTC = new DateTimeZone("UTC");
        $newTZ = new DateTimeZone($new_timezone);
        $date = new DateTime( $result['time'] , $UTC );
        $date->setTimezone( $newTZ );

        $time_time = $date->format('H:i');
       // $conversations_unread[$key]['time'] =  $time_time;
    }*/

    


    echo json_encode(
        array(
        'unread_current_user' => $unread_message_data,
        )
    );
    wp_die();

}
add_action('wp_ajax_nopriv_lbb_messages_update', 'lbb_messages_update');
add_action('wp_ajax_lbb_messages_update', 'lbb_messages_update');
function lbb_messages_update($convers_id = '', $messages_id_new = array()){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';

    $conversation_id = intval($_POST['conversation_id']);

    if ($convers_id != '') {
        $conversation_id = $convers_id;
    }

    $message_ids = isset( $_POST['message_ids'] ) ? (array) $_POST['message_ids'] : [];

    if (!empty($convers_id)) {
        $message_ids = $messages_id_new;
    }

    $escapedIds = array_filter( array_map( 'intval', $message_ids ) );


    /*$whereCondition = array(
        'is_read' => 0,
        'conversation_id' => $conversation_id,
        '' => 'message_id IN (' . implode(',', $escapedIds) . ')'
    );*/

   if ( empty( $escapedIds ) ) {
       return;
   }
   $placeholders = implode( ',', array_fill( 0, count( $escapedIds ), '%d' ) );
   $wpdb_update_query = $wpdb->prepare(
       "UPDATE $table_name SET `is_read` = '1' WHERE `is_read` = '0' AND `conversation_id` = %d AND message_id IN ($placeholders)",
       array_merge( [ $conversation_id ], $escapedIds )
   );
   $wpdb->query( $wpdb_update_query );

    
    // Update the 'is_read' column for the specified message
    /*$wpdb->update(
        $table_name,
        array(
            'is_read' => 1
        ),
        $whereCondition
    );*/


    return;
    
}


add_action('wp_ajax_lbb_mark_as_messages', 'lbb_mark_as_messages');
function lbb_mark_as_messages(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'lbb_messages';
    $conversation_id = intval($_POST['conversation_id']);
    
    // Update the 'is_read' column for the specified message
    $wpdb->update(
        $table_name,
        array(
            'is_read' => 1
        ),
        array(
            'is_read' => 0,
            'conversation_id' => $conversation_id
        )
    );
    echo 'Message marked as read';
    die();
}
add_action('wp_ajax_lbb_load_more_conversations', 'lbb_load_more_conversations');
function lbb_load_more_conversations(){
    $offset = $_POST['offset'];
    $filter = isset($_POST['filter']) ? $_POST['filter'] : array();
    $limit = 50;
    $req_user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $messageManager = new MessageManager();
    $userchat_list = $messageManager->loadUserChatList($limit, $offset,$req_user_id, $filter);
    $html = '';
    $conv_ids = array();
    foreach($userchat_list as $single_msg){
        $user_id = $single_msg['user_id'];
        $firebaseid = $single_msg['firebase_id'];
        $conversation_id = $single_msg['conversation_id'];
        $time = $single_msg['sent_time'];
        $conv_ids[] = $conversation_id;
        $name = "Unknown Visitor";
        if($user_id == 0){
            $contacts = new LBB_Contacts;
            $contacts = $contacts->getContactByConversationId($conversation_id);
            if($contacts){
                $name = $contacts['firstname'];
            }else{
                $name = "Unknown Visitor";
            }
        }else{

            $contact_id = lbb_get_contact_id($conversation_id);
            $contact_status = lbb_get_contact_status($contact_id);
            if($contact_status > 0){
                $name = lbb_get_contact_name($contact_id);
            }else{
                $name = lbb_get_user_name($user_id);
            }

        }
        $last_msg = $messageManager->getMessagesByConversationIdWithOffset($conversation_id, 50, 0);
        $message = "";
        
        //$time = "";
        if($last_msg){
            $message = stripslashes($last_msg[0]['message_text']);
            $message = strip_tags($message);
            $time = $last_msg[0]['sent_time'];
        }
        $html .= '<li class="clearfix user-admin-list-left-side" id="chat-list-user-'.$conversation_id.'" data-id="'.$conversation_id.'" data-count="0" data-time="'.strtotime($time).'" data-firebaseid="'.$firebaseid.'"><a href="javascript:void(0);" class="user-list-left-sidebar" data-userid="'.$user_id.'" ><div class="lbb-delete-conversation-main"><span class="lbb-delete-conversation"><i class="bx bxs-trash-alt"></i></span></div>
                <span class="chat-icon"><img src="https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g"></span>
                <div class="about">
                    <div class="name" data-msgcount="0">'.$name.'</div>
                        <div class="status">'.$message.'</div>
                </div>
                <span class="lbb-message-count"></span> 
                <span class="lbb-notification-time-text">'.LBBTimeElapsedString($time).'</span> 
            </a></li>';
    }
    echo json_encode(
        array(
        'html' => $html,
        'conv_ids' => $conv_ids
        )
    );
    wp_die();
}
add_action('wp_ajax_lbb_load_more_messages', 'lbb_load_more_messages');
function lbb_load_more_messages(){
    if(isset($_POST["conversation_id"])){
        $offset = $_POST["conversation_id"];
        $offset = $_POST["moffset"];
        $limit = 10;
        $messageManager = new MessageManager();
        $chat_list = $messageManager->getMessagesByConversationIdWithOffset($conversation_id,10,$offset);
        $chat_list = array_reverse($chat_list);
        $userchat_list_html = "";
        $prev_msg_date = 0;
        foreach($chat_list as $single_msg){
          $message = stripslashes($single_msg['message_text']);
          $time = $single_msg['sent_time'];
          $is_bot_response = $single_msg['is_bot_response'];
          $user_id = $single_msg['user_id'];
    
          $newTZ = new DateTimeZone("UTC");
          $date = new DateTime( $time , $UTC );
          $date->setTimezone( $newTZ );
    
          $time_time = $date->format('H:i');
          $msg_date = $date->format('F d Y');
    
          if($is_bot_response == 1){
            $response = 'user-admin-list';
            $name = "Bot";
            $avatar = 'https://www.gravatar.com/avatar/ee0c0ef685181becc5ebce6314b0d851?s=80&amp;d=mp&amp;r=g';
          }else{
            $response = 'user-visitor-list chat-top-msg';
            if($user_id == 0){
                $name = "Unknown Visitor";
            }else{
                //$name = lbb_get_user_name($user_id);

                $contact_id = lbb_get_contact_id($conversation_id);
                $contact_status = lbb_get_contact_status($contact_id);
                if($contact_status > 0){
                    $name = lbb_get_contact_name($contact_id);
                }else{
                    $name = lbb_get_user_name($user_id);
                }
            }
            $avatar = lbb_user_avatar($user_id);
          }
            $tag_ids = $single_msg['tags'];
            $tags = lbb_print_tags($tag_ids);
    
          if ($prev_msg_date != $msg_date) {
            $userchat_list_html.= '<li class="msg-day-wrapper"> <div class="msg-date-show"> <div class="msg-date-border"></div> <span>'.$msg_date.'</span> <div class="msg-date-border"></div></div> </li>';
          }
          
          $userchat_list_html .= '<li class="'.$response.'">
          <div class="message-box-img">
            <span class="chat-icon">
                
            </span>
          </div>
              <div class="message-box-data">
                <div class="message-data">
                  <span class="message-data-name"> '.$name.'</span>
                  <span class="message-data-time">'.$time_time.'</span>
                  <span class="message-data-tag">'.$tags.'</span>
                </div>
                <div class="message my-message">
                  <div class="lbb-user-message">'.$message.'</div>
                </div>
              </div>
        </li>';
        $prev_msg_date = $date->format('F d Y');
        }
        
      }
      $output['name'] = $name;
      $output['userchat_list_html'] = $userchat_list_html;
      echo json_encode($output);die;
}