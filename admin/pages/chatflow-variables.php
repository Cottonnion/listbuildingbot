<?php
include( LBB_ABS_URL . 'admin/pages/default-variables.php');
/*CSS varaiables Start*/
$live_chat_idle_time_enable = "yes";
$start_time = "";
$end_time = "";
$bot_live_chat_option = 'display:none;';
$live_chat_option = 'display:none;';
/*CSS varaiables End*/

$selected_url = "";
$enter_url = "";
$page_scroll_value = "";
$when_to_show = "visitor_visit";

$time_input_value = "";
$who_should_see = "all_visitor";
$automation_triggered = "after_email";
$how_to_show = "inline";
$allow_users_to_contact = "no";
$lbb_ai_admin_email = "";
$lbb_ai_from_name = "";
$lbb_ai_email_subject = "";
$lbb_ai_email_body = "";
$lbb_ai_reponse_limit = "10";

$heading_font_family = "DM Sans,sans-serif";
$content_font_family = "DM Sans,sans-serif";
$sub_heading_font_family = "DM Sans,sans-serif";

$global_style = "N";
$button_text_color = "#0066ff";
$button_font_size = "16";
$answer_button_font_size = "16";
$button_border_radius = "5";

$open_ai_settings = "use_the_content";
$chatflow_title = "";
$ans_bg_color = "#000000";
$multimedia_image = LBB_URL.'admin/images/multimedia.png';
$submit_button_icon = LBB_URL.'admin/images/avatar.png';
$bot_user_image = LBB_URL.'admin/images/avatar.png';
$icon_image = LBB_URL.'admin/images/chat.png';
$video_text = "How can I help?";
$lbb_minimized_type_option = "show_minimized";
$chatflow_id = 0;
if(isset($_REQUEST['action']) == 'edit'){
  $chatflow_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
}
$minimized_type = 'icon';
$lbb_chat_header = 'yes';
$maximized_chatbot_image = "";
$maximized_chatbot_video = "";
$maximized_chatbot_icon = "";

/*Ai Assistant Values*/

$welcome_message = "";
$your_content_res = "yes";
$main_aiassistant_rules = "You are a Bot and your primary purpose is to assist users in a variety of tasks and offer valuable guidance. You have access to files to answer user questions. Don't answer questions not related to the list building bot plugin. Respond in a courteous, respectful way. ";
$no_response_msg = "Sorry, I don't have an answer to that question but send us a ticket and we'll respond to it promptly.";
$input_token_limit = "3400";
$output_token_limit = "600";
$limit_threads = "10000";
$allow_embed_domains = "";
$open_ai_key = "";
$personalized_pdf_option = "no";

$api_model = "gpt-4o-mini";

$aiassistant_main_prompt = "You are a Bot and your primary purpose is to assist users in a variety of tasks and offer valuable guidance. Whether it's helping users plan their fitness routines or suggesting travel destinations, you're equipped to provide insightful assistance.

When engaging with users, begin by asking them about the specific task they need help with. Depending on their response, you can either follow up with further inquiries to gather more details or provide relevant information to address their request. Keep the conversation engaging by introducing fresh and intriguing insights, avoiding repetitive responses.

Moreover, always consider the chat's history to offer responses that are contextually appropriate. By asking insightful questions and delivering practical tips and advice when it makes sense, you can enhance the overall user experience.";

$aiassistant_rules = "Maintain a respectful and courteous tone in all interactions.

Avoid expressing personal opinions or preferences; provide objective information only.

Limit your responses to questions within your instructed topics.

Decline to respond, share information, or continue the conversation if questions are off-topic.

Share information solely related to the [[CONTEXT]].

Refrain from making speculative statements or predictions.

Provide brief answers without explanations.

Ensure that all responses are accurate and up-to-date to the best of your knowledge.

Do not engage in debates or arguments with users; maintain a neutral stance.

If a question is ambiguous or unclear, request clarification before attempting to answer.

Respect user privacy and do not request or disclose personal information.

Notify users if a question falls outside the scope of what can be answered within the instructed topics.

limit based on token but make sure to complete the sentence";
$lang = 'English';
$livechat_status = "yes";
$language_customizer_array = array(
                    array(
                        'value' => 'Afar',
                        'selected' => lbb_get_main_lang_value($lang, 'Afar')
                    ),
                   array(
                        'value' => 'Abkhazian',
                        'selected' => lbb_get_main_lang_value($lang, 'Abkhazian')
                    ),array(
                        'value' => 'Avestan',
                        'selected' => lbb_get_main_lang_value($lang, 'Avestan')
                    ),array(
                        'value' => 'Afrikaans',
                        'selected' => lbb_get_main_lang_value($lang, 'Afrikaans')
                    ),array(
                        'value' => 'Akan',
                        'selected' => lbb_get_main_lang_value($lang, 'Akan')
                    ),array(
                        'value' => 'Amharic',
                        'selected' => lbb_get_main_lang_value($lang, 'Amharic')
                    ),array(
                        'value' => 'Aragonese',
                        'selected' => lbb_get_main_lang_value($lang, 'Aragonese')
                    ),array(
                        'value' => 'Arabic',
                        'selected' => lbb_get_main_lang_value($lang, 'Arabic')
                    ),array(
                        'value' => 'Assamese',
                        'selected' => lbb_get_main_lang_value($lang, 'Assamese')
                    ),array(
                        'value' => 'Avaric',
                        'selected' => lbb_get_main_lang_value($lang, 'Avaric')
                    ),array(
                        'value' => 'Aymara',
                        'selected' => lbb_get_main_lang_value($lang, 'Aymara')
                    ),array(
                        'value' => 'Azerbaijani',
                        'selected' => lbb_get_main_lang_value($lang, 'Azerbaijani')
                    ),array(
                        'value' => 'Bashkir',
                        'selected' => lbb_get_main_lang_value($lang, 'Bashkir')
                    ),array(
                        'value' => 'Belarusian',
                        'selected' => lbb_get_main_lang_value($lang, 'Belarusian')
                    ),array(
                        'value' => 'Bulgarian',
                        'selected' => lbb_get_main_lang_value($lang, 'Bulgarian')
                    ),array(
                        'value' => 'Bihari languages',
                        'selected' => lbb_get_main_lang_value($lang, 'Bihari languages')
                    ),array(
                        'value' => 'Bislama',
                        'selected' => lbb_get_main_lang_value($lang, 'Bislama')
                    ),array(
                        'value' => 'Bambara',
                        'selected' => lbb_get_main_lang_value($lang, 'Bambara')
                    ),array(
                        'value' => 'Bengali',
                        'selected' => lbb_get_main_lang_value($lang, 'Bengali')
                    ),array(
                        'value' => 'Tibetan',
                        'selected' => lbb_get_main_lang_value($lang, 'Tibetan')
                    ),array(
                        'value' => 'Breton',
                        'selected' => lbb_get_main_lang_value($lang, 'Breton')
                    ),array(
                        'value' => 'Bosnian',
                        'selected' => lbb_get_main_lang_value($lang, 'Bosnian')
                    ),array(
                        'value' => 'Catalan',
                        'selected' => lbb_get_main_lang_value($lang, 'Catalan')
                    ),array(
                        'value' => 'Chechen',
                        'selected' => lbb_get_main_lang_value($lang, 'Chechen')
                    ),array(
                        'value' => 'Chamorro',
                        'selected' => lbb_get_main_lang_value($lang, 'Chamorro')
                    ),array(
                        'value' => 'Corsican',
                        'selected' => lbb_get_main_lang_value($lang, 'Corsican')
                    ),array(
                        'value' => 'Cree',
                        'selected' => lbb_get_main_lang_value($lang, 'Cree')
                    ),array(
                        'value' => 'Czech',
                        'selected' => lbb_get_main_lang_value($lang, 'Czech')
                    ),array(
                        'value' => 'Church Slavic',
                        'selected' => lbb_get_main_lang_value($lang, 'Church Slavic')
                    ),array(
                        'value' => 'Chuvash',
                        'selected' => lbb_get_main_lang_value($lang, 'Chuvash')
                    ),array(
                        'value' => 'Welsh',
                        'selected' => lbb_get_main_lang_value($lang, 'Welsh')
                    ),array(
                        'value' => 'Danish',
                        'selected' => lbb_get_main_lang_value($lang, 'Danish')
                    ),array(
                        'value' => 'German',
                        'selected' => lbb_get_main_lang_value($lang, 'German')
                    ),array(
                        'value' => 'Divehi',
                        'selected' => lbb_get_main_lang_value($lang, 'Divehi')
                    ),array(
                        'value' => 'Dzongkha',
                        'selected' => lbb_get_main_lang_value($lang, 'Dzongkha')
                    ),array(
                        'value' => 'Ewe',
                        'selected' => lbb_get_main_lang_value($lang, 'Ewe')
                    ),array(
                        'value' => 'Greek',
                        'selected' => lbb_get_main_lang_value($lang, 'Greek')
                    ),array(
                        'value' => 'English',
                        'selected' => lbb_get_main_lang_value($lang, 'English')
                    ),array(
                        'value' => 'Esperanto',
                        'selected' => lbb_get_main_lang_value($lang, 'Esperanto')
                    ),array(
                        'value' => 'Spanish',
                        'selected' => lbb_get_main_lang_value($lang, 'Spanish')
                    ),array(
                        'value' => 'Estonian',
                        'selected' => lbb_get_main_lang_value($lang, 'Estonian')
                    ),array(
                        'value' => 'Basque',
                        'selected' => lbb_get_main_lang_value($lang, 'Basque')
                    ),array(
                        'value' => 'Persian',
                        'selected' => lbb_get_main_lang_value($lang, 'Persian')
                    ),array(
                        'value' => 'Fulah',
                        'selected' => lbb_get_main_lang_value($lang, 'Fulah')
                    ),array(
                        'value' => 'Finnish',
                        'selected' => lbb_get_main_lang_value($lang, 'Finnish')
                    ),array(
                        'value' => 'Fijian',
                        'selected' => lbb_get_main_lang_value($lang, 'Fijian')
                    ),array(
                        'value' => 'Faroese',
                        'selected' => lbb_get_main_lang_value($lang, 'Faroese')
                    ),array(
                        'value' => 'French',
                        'selected' => lbb_get_main_lang_value($lang, 'French')
                    ),
                      array('value' => 'Western Frisian', 'selected' => lbb_get_main_lang_value($lang, 'Western Frisian')),
    array('value' => 'Irish', 'selected' => lbb_get_main_lang_value($lang, 'Irish')),
    array('value' => 'Scottish Gaelic', 'selected' => lbb_get_main_lang_value($lang, 'Scottish Gaelic')),
    array('value' => 'Galician', 'selected' => lbb_get_main_lang_value($lang, 'Galician')),
    array('value' => 'Guarani', 'selected' => lbb_get_main_lang_value($lang, 'Guarani')),
    array('value' => 'Gujarati', 'selected' => lbb_get_main_lang_value($lang, 'Gujarati')),
    array('value' => 'Manx', 'selected' => lbb_get_main_lang_value($lang, 'Manx')),
    array('value' => 'Hausa', 'selected' => lbb_get_main_lang_value($lang, 'Hausa')),
    array('value' => 'Hebrew', 'selected' => lbb_get_main_lang_value($lang, 'Hebrew')),
    array('value' => 'Hindi', 'selected' => lbb_get_main_lang_value($lang, 'Hindi')),
    array('value' => 'Hiri Motu', 'selected' => lbb_get_main_lang_value($lang, 'Hiri Motu')),
    array('value' => 'Croatian', 'selected' => lbb_get_main_lang_value($lang, 'Croatian')),
    array('value' => 'Haitian', 'selected' => lbb_get_main_lang_value($lang, 'Haitian')),
    array('value' => 'Hungarian', 'selected' => lbb_get_main_lang_value($lang, 'Hungarian')),
    array('value' => 'Armenian', 'selected' => lbb_get_main_lang_value($lang, 'Armenian')),
    array('value' => 'Herero', 'selected' => lbb_get_main_lang_value($lang, 'Herero')),
    array('value' => 'Interlingua', 'selected' => lbb_get_main_lang_value($lang, 'Interlingua')),
    array('value' => 'Indonesian', 'selected' => lbb_get_main_lang_value($lang, 'Indonesian')),
    array('value' => 'Interlingue', 'selected' => lbb_get_main_lang_value($lang, 'Interlingue')),
    array('value' => 'Igbo', 'selected' => lbb_get_main_lang_value($lang, 'Igbo')),
    array('value' => 'Sichuan Yi', 'selected' => lbb_get_main_lang_value($lang, 'Sichuan Yi')),
    array('value' => 'Inupiaq', 'selected' => lbb_get_main_lang_value($lang, 'Inupiaq')),
    array('value' => 'Ido', 'selected' => lbb_get_main_lang_value($lang, 'Ido')),
    array('value' => 'Icelandic', 'selected' => lbb_get_main_lang_value($lang, 'Icelandic')),
    array('value' => 'Italian', 'selected' => lbb_get_main_lang_value($lang, 'Italian')),
    array('value' => 'Inuktitut', 'selected' => lbb_get_main_lang_value($lang, 'Inuktitut')),
    array('value' => 'Japanese', 'selected' => lbb_get_main_lang_value($lang, 'Japanese')),
    array('value' => 'Javanese', 'selected' => lbb_get_main_lang_value($lang, 'Javanese')),
    array('value' => 'Georgian', 'selected' => lbb_get_main_lang_value($lang, 'Georgian')),
    array('value' => 'Kongo', 'selected' => lbb_get_main_lang_value($lang, 'Kongo')),
    array('value' => 'Kikuyu', 'selected' => lbb_get_main_lang_value($lang, 'Kikuyu')),
    array('value' => 'Kuanyama', 'selected' => lbb_get_main_lang_value($lang, 'Kuanyama')),
    array('value' => 'Kazakh', 'selected' => lbb_get_main_lang_value($lang, 'Kazakh')),
    array('value' => 'Kalaallisut', 'selected' => lbb_get_main_lang_value($lang, 'Kalaallisut')),
    array('value' => 'Central Khmer', 'selected' => lbb_get_main_lang_value($lang, 'Central Khmer')),
    array('value' => 'Kannada', 'selected' => lbb_get_main_lang_value($lang, 'Kannada')),
    array('value' => 'Korean', 'selected' => lbb_get_main_lang_value($lang, 'Korean')),
    array('value' => 'Kanuri', 'selected' => lbb_get_main_lang_value($lang, 'Kanuri')),
    array('value' => 'Kashmiri', 'selected' => lbb_get_main_lang_value($lang, 'Kashmiri')),
    array('value' => 'Kurdish', 'selected' => lbb_get_main_lang_value($lang, 'Kurdish')),
    array('value' => 'Komi', 'selected' => lbb_get_main_lang_value($lang, 'Komi')),
    array('value' => 'Cornish', 'selected' => lbb_get_main_lang_value($lang, 'Cornish')),
    array('value' => 'Kirghiz', 'selected' => lbb_get_main_lang_value($lang, 'Kirghiz')),
    array('value' => 'Latin', 'selected' => lbb_get_main_lang_value($lang, 'Latin')),
    array('value' => 'Luxembourgish', 'selected' => lbb_get_main_lang_value($lang, 'Luxembourgish')),
    array('value' => 'Ganda', 'selected' => lbb_get_main_lang_value($lang, 'Ganda')),
    array('value' => 'Limburgish', 'selected' => lbb_get_main_lang_value($lang, 'Limburgish')),
    array('value' => 'Lingala', 'selected' => lbb_get_main_lang_value($lang, 'Lingala')),
    array('value' => 'Lao', 'selected' => lbb_get_main_lang_value($lang, 'Lao')),
    array('value' => 'Lithuanian', 'selected' => lbb_get_main_lang_value($lang, 'Lithuanian')),
    array('value' => 'Luba-Katanga', 'selected' => lbb_get_main_lang_value($lang, 'Luba-Katanga')),
    array('value' => 'Latvian', 'selected' => lbb_get_main_lang_value($lang, 'Latvian')),
    array('value' => 'Malagasy', 'selected' => lbb_get_main_lang_value($lang, 'Malagasy')),
    array('value' => 'Marshallese', 'selected' => lbb_get_main_lang_value($lang, 'Marshallese')),
    array('value' => 'Maori', 'selected' => lbb_get_main_lang_value($lang, 'Maori')),
    array('value' => 'Macedonian', 'selected' => lbb_get_main_lang_value($lang, 'Macedonian')),
    array('value' => 'Malayalam', 'selected' => lbb_get_main_lang_value($lang, 'Malayalam')),
    array('value' => 'Mongolian', 'selected' => lbb_get_main_lang_value($lang, 'Mongolian')),
    array('value' => 'Marathi', 'selected' => lbb_get_main_lang_value($lang, 'Marathi')),
    array('value' => 'Malay', 'selected' => lbb_get_main_lang_value($lang, 'Malay')),
    array('value' => 'Maltese', 'selected' => lbb_get_main_lang_value($lang, 'Maltese')),
    array('value' => 'Burmese', 'selected' => lbb_get_main_lang_value($lang, 'Burmese')),
    array('value' => 'Nauru', 'selected' => lbb_get_main_lang_value($lang, 'Nauru')),
    array('value' => 'Norwegian Bokmål', 'selected' => lbb_get_main_lang_value($lang, 'Norwegian Bokmål')),
    array('value' => 'North Ndebele', 'selected' => lbb_get_main_lang_value($lang, 'North Ndebele')),
    array('value' => 'Nepali', 'selected' => lbb_get_main_lang_value($lang, 'Nepali')),
    array('value' => 'Ndonga', 'selected' => lbb_get_main_lang_value($lang, 'Ndonga')),
    array('value' => 'Dutch', 'selected' => lbb_get_main_lang_value($lang, 'Dutch')),
    array('value' => 'Norwegian Nynorsk', 'selected' => lbb_get_main_lang_value($lang, 'Norwegian Nynorsk')),
    array('value' => 'Norwegian', 'selected' => lbb_get_main_lang_value($lang, 'Norwegian')),
    array('value' => 'South Ndebele', 'selected' => lbb_get_main_lang_value($lang, 'South Ndebele')),
    array('value' => 'Navajo', 'selected' => lbb_get_main_lang_value($lang, 'Navajo')),
    array('value' => 'Chichewa', 'selected' => lbb_get_main_lang_value($lang, 'Chichewa')),
    array('value' => 'Occitan', 'selected' => lbb_get_main_lang_value($lang, 'Occitan')),
    array('value' => 'Ojibwa', 'selected' => lbb_get_main_lang_value($lang, 'Ojibwa')),
    array('value' => 'Oromo', 'selected' => lbb_get_main_lang_value($lang, 'Oromo')),
    array('value' => 'Oriya', 'selected' => lbb_get_main_lang_value($lang, 'Oriya')),
    array('value' => 'Ossetian', 'selected' => lbb_get_main_lang_value($lang, 'Ossetian')),
    array('value' => 'Panjabi', 'selected' => lbb_get_main_lang_value($lang, 'Panjabi')),
    array('value' => 'Pali', 'selected' => lbb_get_main_lang_value($lang, 'Pali')),
    array('value' => 'Polish', 'selected' => lbb_get_main_lang_value($lang, 'Polish')),
    array('value' => 'Pushto', 'selected' => lbb_get_main_lang_value($lang, 'Pushto')),
    array('value' => 'Portuguese', 'selected' => lbb_get_main_lang_value($lang, 'Portuguese')),
    array('value' => 'Quechua', 'selected' => lbb_get_main_lang_value($lang, 'Quechua')),
    array('value' => 'Romansh', 'selected' => lbb_get_main_lang_value($lang, 'Romansh')),
    array('value' => 'Rundi', 'selected' => lbb_get_main_lang_value($lang, 'Rundi')),
    array('value' => 'Romanian', 'selected' => lbb_get_main_lang_value($lang, 'Romanian')),
    array('value' => 'Russian', 'selected' => lbb_get_main_lang_value($lang, 'Russian')),
    array('value' => 'Kinyarwanda', 'selected' => lbb_get_main_lang_value($lang, 'Kinyarwanda')),
    array('value' => 'Sanskrit', 'selected' => lbb_get_main_lang_value($lang, 'Sanskrit')),
    array('value' => 'Sardinian', 'selected' => lbb_get_main_lang_value($lang, 'Sardinian')),
    array('value' => 'Sindhi', 'selected' => lbb_get_main_lang_value($lang, 'Sindhi')),
    array('value' => 'Northern Sami', 'selected' => lbb_get_main_lang_value($lang, 'Northern Sami')),
    array('value' => 'Sango', 'selected' => lbb_get_main_lang_value($lang, 'Sango')),
    array('value' => 'Sinhala', 'selected' => lbb_get_main_lang_value($lang, 'Sinhala')),
    array('value' => 'Slovak', 'selected' => lbb_get_main_lang_value($lang, 'Slovak')),
    array('value' => 'Slovenian', 'selected' => lbb_get_main_lang_value($lang, 'Slovenian')),
    array('value' => 'Samoan', 'selected' => lbb_get_main_lang_value($lang, 'Samoan')),
    array('value' => 'Shona', 'selected' => lbb_get_main_lang_value($lang, 'Shona')),
    array('value' => 'Somali', 'selected' => lbb_get_main_lang_value($lang, 'Somali')),
    array('value' => 'Albanian', 'selected' => lbb_get_main_lang_value($lang, 'Albanian')),
    array('value' => 'Serbian', 'selected' => lbb_get_main_lang_value($lang, 'Serbian')),
    array('value' => 'Swati', 'selected' => lbb_get_main_lang_value($lang, 'Swati')),
    array('value' => 'Sotho, Southern', 'selected' => lbb_get_main_lang_value($lang, 'Sotho, Southern')),
    array('value' => 'Sundanese', 'selected' => lbb_get_main_lang_value($lang, 'Sundanese')),
    array('value' => 'Swedish', 'selected' => lbb_get_main_lang_value($lang, 'Swedish')),
    array('value' => 'Swahili', 'selected' => lbb_get_main_lang_value($lang, 'Swahili')),
    array('value' => 'Tamil', 'selected' => lbb_get_main_lang_value($lang, 'Tamil')),
    array('value' => 'Telugu', 'selected' => lbb_get_main_lang_value($lang, 'Telugu')),
    array('value' => 'Tajik', 'selected' => lbb_get_main_lang_value($lang, 'Tajik')),
    array('value' => 'Thai', 'selected' => lbb_get_main_lang_value($lang, 'Thai')),
    array('value' => 'Tigrinya', 'selected' => lbb_get_main_lang_value($lang, 'Tigrinya')),
    array('value' => 'Turkmen', 'selected' => lbb_get_main_lang_value($lang, 'Turkmen')),
    array('value' => 'Tagalog', 'selected' => lbb_get_main_lang_value($lang, 'Tagalog')),
    array('value' => 'Tswana', 'selected' => lbb_get_main_lang_value($lang, 'Tswana')),
    array('value' => 'Tonga (Tonga Islands)', 'selected' => lbb_get_main_lang_value($lang, 'Tonga (Tonga Islands)')),
    array('value' => 'Turkish', 'selected' => lbb_get_main_lang_value($lang, 'Turkish')),
    array('value' => 'Tsonga', 'selected' => lbb_get_main_lang_value($lang, 'Tsonga')),
    array('value' => 'Tatar', 'selected' => lbb_get_main_lang_value($lang, 'Tatar')),
    array('value' => 'Twi', 'selected' => lbb_get_main_lang_value($lang, 'Twi')),
    array('value' => 'Tahitian', 'selected' => lbb_get_main_lang_value($lang, 'Tahitian')),
    array('value' => 'Uighur', 'selected' => lbb_get_main_lang_value($lang, 'Uighur')),
    array('value' => 'Ukrainian', 'selected' => lbb_get_main_lang_value($lang, 'Ukrainian')),
    array('value' => 'Urdu', 'selected' => lbb_get_main_lang_value($lang, 'Urdu')),
    array('value' => 'Uzbek', 'selected' => lbb_get_main_lang_value($lang, 'Uzbek')),
    array('value' => 'Venda', 'selected' => lbb_get_main_lang_value($lang, 'Venda')),
    array('value' => 'Vietnamese', 'selected' => lbb_get_main_lang_value($lang, 'Vietnamese')),
    array('value' => 'Volapük', 'selected' => lbb_get_main_lang_value($lang, 'Volapük')),
    array('value' => 'Walloon', 'selected' => lbb_get_main_lang_value($lang, 'Walloon')),
    array('value' => 'Wolof', 'selected' => lbb_get_main_lang_value($lang, 'Wolof')),
    array('value' => 'Xhosa', 'selected' => lbb_get_main_lang_value($lang, 'Xhosa')),
    array('value' => 'Yiddish', 'selected' => lbb_get_main_lang_value($lang, 'Yiddish')),
    array('value' => 'Yoruba', 'selected' => lbb_get_main_lang_value($lang, 'Yoruba')),
    array('value' => 'Zhuang', 'selected' => lbb_get_main_lang_value($lang, 'Zhuang')),
    array('value' => 'Chinese', 'selected' => lbb_get_main_lang_value($lang, 'Chinese')),
    array('value' => 'Zulu', 'selected' => lbb_get_main_lang_value($lang, 'Zulu'))

                );

function lbb_get_main_lang_value($lang, $value){
    if($value == $lang){
        return 'selected';
    }
}

function lbbMainGenerateHtml($field_array){
    foreach ($field_array as $key => $value) {
        echo '<option value="'.$value['value'].'" '.$value['selected'].'>'.$value['value'].'</option>';
    }
}
  if(get_option('lbb_general_settings') ){
    $lbb_general_settings = get_option('lbb_general_settings');
    $lbb_livechat_admin_name = !empty($lbb_general_settings['lbb_livechat_admin_name']) ? $lbb_general_settings['lbb_livechat_admin_name'] : 'Bot';
    $lbb_image_upload_live = !empty($lbb_general_settings['lbb_image_upload_live']) ? $lbb_general_settings['lbb_image_upload_live'] : '';
  }

  if(get_option('lbb_fuzzy_search_options') ){
    $lbb_fuzzy_search_options = get_option('lbb_fuzzy_search_options');
    $lbb_show_text = !empty($lbb_general_settings['show_this_text']) ? $lbb_general_settings['show_this_text'] : $lbb_show_text;
  }

   if(!empty($chatflow_id)){
    /* Style Start */
    $lbb_admin_name = (get_post_meta( $chatflow_id, 'lbb_admin_name', true ))? get_post_meta( $chatflow_id, 'lbb_admin_name', true ) : '';
    $lbb_livechat_admin_name = (get_post_meta( $chatflow_id, 'lbb_livechat_admin_name', true ))? get_post_meta( $chatflow_id, 'lbb_livechat_admin_name', true ) : $lbb_livechat_admin_name;
    $lbb_image_upload_live = (get_post_meta( $chatflow_id, 'lbb_image_upload_live', true ))? get_post_meta( $chatflow_id, 'lbb_image_upload_live', true ) : $lbb_image_upload_live;
    
    

    /* Search Feature options */

    $lbb_enable_search = (get_post_meta( $chatflow_id, 'lbb_enable_search', true ))? get_post_meta( $chatflow_id, 'lbb_enable_search', true ) : $lbb_enable_search;
    $lbb_custom_trained_bot = (get_post_meta( $chatflow_id, 'lbb_custom_trained_bot', true ))? get_post_meta( $chatflow_id, 'lbb_custom_trained_bot', true ) : $lbb_custom_trained_bot;
    $create_guest_account = (get_post_meta( $chatflow_id, 'create_guest_account', true ))? get_post_meta( $chatflow_id, 'create_guest_account', true ) : $create_guest_account;
    $search_feature_option = (get_post_meta( $chatflow_id, 'search_feature_option', true ))? get_post_meta( $chatflow_id, 'search_feature_option', true ) : $search_feature_option;
    $lbb_livechat_exact_match = (get_post_meta( $chatflow_id, 'lbb_livechat_exact_match', true ))? get_post_meta( $chatflow_id, 'lbb_livechat_exact_match', true ) : $lbb_livechat_exact_match;
    $lbb_show_results = (get_post_meta( $chatflow_id, 'lbb_show_results', true ))? get_post_meta( $chatflow_id, 'lbb_show_results', true ) : $lbb_show_results;
    $lbb_ai_topic_rules = (get_post_meta( $chatflow_id, 'lbb_ai_topic_rules', true ))? get_post_meta( $chatflow_id, 'lbb_ai_topic_rules', true ) : $lbb_ai_topic_rules;
    $lbb_general_rules = (get_post_meta( $chatflow_id, 'lbb_general_rules', true ))? get_post_meta( $chatflow_id, 'lbb_general_rules', true ) : $lbb_general_rules;

    $lbb_how_many = (get_post_meta( $chatflow_id, 'lbb_how_many', true ))? get_post_meta( $chatflow_id, 'lbb_how_many', true ) : $lbb_how_many;
    $lbb_show_text = (get_post_meta( $chatflow_id, 'lbb_show_text', true ))? get_post_meta( $chatflow_id, 'lbb_show_text', true ) : $lbb_show_text;


    $lbb_chatbot_description = (get_post_meta( $chatflow_id, 'lbb_chatbot_description', true ))? get_post_meta( $chatflow_id, 'lbb_chatbot_description', true ) : '';
    $open_ai_settings = (get_post_meta( $chatflow_id, 'open_ai_settings', true ))? get_post_meta( $chatflow_id, 'open_ai_settings', true ) : $open_ai_settings;
    $lbb_container_width = (get_post_meta( $chatflow_id, 'lbb_container_width', true ))? get_post_meta( $chatflow_id, 'lbb_container_width', true ) : $lbb_container_width;
    $lbb_container_padding = (get_post_meta( $chatflow_id, 'lbb_container_padding', true ))? get_post_meta( $chatflow_id, 'lbb_container_padding', true ) : $lbb_container_padding;
    $lbb_chatbot_height = (get_post_meta( $chatflow_id, 'lbb_chatbot_height', true ))? get_post_meta( $chatflow_id, 'lbb_chatbot_height', true ) : $lbb_chatbot_height;
    $lbb_common_font_family = (get_post_meta( $chatflow_id, 'lbb_common_font_family', true ))? get_post_meta( $chatflow_id, 'lbb_common_font_family', true ) : $lbb_common_font_family;
    $lbb_heading_background_color = (get_post_meta( $chatflow_id, 'lbb_heading_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_heading_background_color', true ) : $lbb_heading_background_color;
    $lbb_image_upload = (get_post_meta( $chatflow_id, 'lbb_image_upload', true ))? get_post_meta( $chatflow_id, 'lbb_image_upload', true ) : $lbb_image_upload;
    $lbb_container_image_width = (get_post_meta( $chatflow_id, 'lbb_container_image_width', true ))? get_post_meta( $chatflow_id, 'lbb_container_image_width', true ) : $lbb_container_image_width;

    $lbb_heading_font_size = (get_post_meta( $chatflow_id, 'lbb_heading_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_heading_font_size', true ) : $lbb_heading_font_size;
    $lbb_heading_font_weight = (get_post_meta( $chatflow_id, 'lbb_heading_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_heading_font_weight', true ) : $lbb_heading_font_weight;
    $lbb_heading_text_color = (get_post_meta( $chatflow_id, 'lbb_heading_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_heading_text_color', true ) : $lbb_heading_text_color;
    $lbb_sub_heading_font_size = (get_post_meta( $chatflow_id, 'lbb_sub_heading_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_font_size', true ) : $lbb_sub_heading_font_size;
    $lbb_sub_heading_font_weight = (get_post_meta( $chatflow_id, 'lbb_sub_heading_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_font_weight', true ) : $lbb_sub_heading_font_weight;
    $lbb_sub_heading_text_color = (get_post_meta( $chatflow_id, 'lbb_sub_heading_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_sub_heading_text_color', true ) : $lbb_sub_heading_text_color;

    $lbb_question_font_size = (get_post_meta( $chatflow_id, 'lbb_question_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_question_font_size', true ) : $lbb_question_font_size;
    $lbb_question_font_weight = (get_post_meta( $chatflow_id, 'lbb_question_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_question_font_weight', true ) : $lbb_question_font_weight;
    $lbb_question_text_color = (get_post_meta( $chatflow_id, 'lbb_question_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_text_color', true ) : $lbb_question_text_color;
    $lbb_question_background_color = (get_post_meta( $chatflow_id, 'lbb_question_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_background_color', true ) : $lbb_question_background_color;
    $lbb_question_input_border_color = (get_post_meta( $chatflow_id, 'lbb_question_input_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_question_input_border_color', true ) : $lbb_question_input_border_color;
    $lbb_question_image_height = (get_post_meta( $chatflow_id, 'lbb_question_image_height', true ))? get_post_meta( $chatflow_id, 'lbb_question_image_height', true ) : $lbb_question_image_height;
    
    

    $lbb_user_answer_font_size = (get_post_meta( $chatflow_id, 'lbb_user_answer_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_user_answer_font_size', true ) : $lbb_user_answer_font_size;
    $lbb_user_answer_font_weight = (get_post_meta( $chatflow_id, 'lbb_user_answer_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_user_answer_font_weight', true ) : $lbb_user_answer_font_weight;
    $lbb_user_answer_text_color = (get_post_meta( $chatflow_id, 'lbb_user_answer_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_user_answer_text_color', true ) : $lbb_user_answer_text_color;
    $lbb_user_answer_background_color = (get_post_meta( $chatflow_id, 'lbb_user_answer_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_user_answer_background_color', true ) : $lbb_user_answer_background_color;

    $lbb_back_button_font_size = (get_post_meta( $chatflow_id, 'lbb_back_button_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_back_button_font_size', true ) : $lbb_back_button_font_size;
    $lbb_back_button_font_weight = (get_post_meta( $chatflow_id, 'lbb_back_button_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_back_button_font_weight', true ) : $lbb_back_button_font_weight;
    $lbb_back_button_text_color = (get_post_meta( $chatflow_id, 'lbb_back_button_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_back_button_text_color', true ) : $lbb_back_button_text_color;
    $lbb_back_button_background_color = (get_post_meta( $chatflow_id, 'lbb_back_button_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_back_button_background_color', true ) : $lbb_back_button_background_color;


    $lbb_display_bot_callout = (get_post_meta( $chatflow_id, 'lbb_display_bot_callout', true ))? get_post_meta( $chatflow_id, 'lbb_display_bot_callout', true ) : $lbb_display_bot_callout;
    $lbb_callout_text = (get_post_meta( $chatflow_id, 'lbb_callout_text', true ))? get_post_meta( $chatflow_id, 'lbb_callout_text', true ) : $lbb_callout_text;
    $lbb_callout_text_color = (get_post_meta( $chatflow_id, 'lbb_callout_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_callout_text_color', true ) : $lbb_callout_text_color;
    $lbb_callout_font_size = (get_post_meta( $chatflow_id, 'lbb_callout_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_callout_font_size', true ) : $lbb_callout_font_size;

    $lbb_knowledge_background_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_background_color', true ) : $lbb_knowledge_background_color;
    $lbb_knowledge_active_background_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_active_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_active_background_color', true ) : $lbb_knowledge_active_background_color;
    $lbb_knowledge_active_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_active_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_active_color', true ) : $lbb_knowledge_active_color;
    $lbb_knowledge_text_color = (get_post_meta( $chatflow_id, 'lbb_knowledge_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_knowledge_text_color', true ) : $lbb_knowledge_text_color;
    $lbb_last_chatted_text_color = (get_post_meta( $chatflow_id, 'lbb_last_chatted_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_last_chatted_text_color', true ) : $lbb_last_chatted_text_color;

    $lbb_ans_bg_color = (get_post_meta( $chatflow_id, 'lbb_ans_bg_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_bg_color', true ) : $lbb_ans_bg_color;
    $lbb_ans_border_color = (get_post_meta( $chatflow_id, 'lbb_ans_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_border_color', true ) : $lbb_ans_border_color;
    $lbb_ans_text_color = (get_post_meta( $chatflow_id, 'lbb_ans_text_color', true ))? get_post_meta( $chatflow_id, 'lbb_ans_text_color', true ) : $lbb_ans_text_color;
    $lbb_ans_border_radius = (get_post_meta( $chatflow_id, 'lbb_ans_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_ans_border_radius', true ) : $lbb_ans_border_radius;
    $lbb_ans_font_size = (get_post_meta( $chatflow_id, 'lbb_ans_font_size', true ))? get_post_meta( $chatflow_id, 'lbb_ans_font_size', true ) : $lbb_ans_font_size;
    $lbb_ans_font_weight = (get_post_meta( $chatflow_id, 'lbb_ans_font_weight', true ))? get_post_meta( $chatflow_id, 'lbb_ans_font_weight', true ) : $lbb_ans_font_weight;
    $lbb_ans_image_height = (get_post_meta( $chatflow_id, 'lbb_ans_image_height', true ))? get_post_meta( $chatflow_id, 'lbb_ans_image_height', true ) : $lbb_ans_image_height;
    $lbb_ans_image_object_fit = (get_post_meta( $chatflow_id, 'lbb_ans_image_object_fit', true ))? get_post_meta( $chatflow_id, 'lbb_ans_image_object_fit', true ) : $lbb_ans_image_object_fit;
    $lbb_ans_button_row_column = (get_post_meta( $chatflow_id, 'lbb_ans_button_row_column', true ))? get_post_meta( $chatflow_id, 'lbb_ans_button_row_column', true ) : $lbb_ans_button_row_column;

    $lbb_max_border_color = (get_post_meta( $chatflow_id, 'lbb_max_border_color', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_color', true ) : $lbb_max_border_color;
    $lbb_max_border_width = (get_post_meta( $chatflow_id, 'lbb_max_border_width', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_width', true ) : $lbb_max_border_width;
    $lbb_max_border_radius = (get_post_meta( $chatflow_id, 'lbb_max_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_max_border_radius', true ) : $lbb_max_border_radius;
    $lbb_max_width = (get_post_meta( $chatflow_id, 'lbb_max_width', true ))? get_post_meta( $chatflow_id, 'lbb_max_width', true ) : $lbb_max_width;
    $lbb_max_height = (get_post_meta( $chatflow_id, 'lbb_max_height', true ))? get_post_meta( $chatflow_id, 'lbb_max_height', true ) : $lbb_max_height;
    $lbb_max_bg_color = (get_post_meta( $chatflow_id, 'lbb_max_bg_color', true ))? get_post_meta( $chatflow_id, 'lbb_max_bg_color', true ) : $lbb_max_bg_color;

    $lbb_chat_alignment = (get_post_meta( $chatflow_id, 'lbb_chat_alignment', true ))? get_post_meta( $chatflow_id, 'lbb_chat_alignment', true ) : $lbb_chat_alignment;
   $lbb_right_spacing = (get_post_meta( $chatflow_id, 'lbb_right_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_right_spacing', true ) : $lbb_right_spacing;
   $lbb_left_spacing = (get_post_meta( $chatflow_id, 'lbb_left_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_left_spacing', true ) : $lbb_left_spacing;
   $lbb_bottom_spacing = (get_post_meta( $chatflow_id, 'lbb_bottom_spacing', true ))? get_post_meta( $chatflow_id, 'lbb_bottom_spacing', true ) : $lbb_bottom_spacing;
   
   $lbb_style_chatbot_background = (get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ))? get_post_meta( $chatflow_id, 'lbb_style_chatbot_background', true ) : $lbb_style_chatbot_background;
   $lbb_chat_background_color = (get_post_meta( $chatflow_id, 'lbb_chat_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_color', true ) : $lbb_chat_background_color;
   $lbb_chat_background_image = (get_post_meta( $chatflow_id, 'lbb_chat_background_image', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_image', true ) : $lbb_chat_background_image;
   $lbb_chat_background_video = (get_post_meta( $chatflow_id, 'lbb_chat_background_video', true ))? get_post_meta( $chatflow_id, 'lbb_chat_background_video', true ) : $lbb_chat_background_video;


    /* Style End */
          
    $lbb_email_notification_status = (get_post_meta( $chatflow_id, '_lbb_email_notification_status', true ))? get_post_meta( $chatflow_id, '_lbb_email_notification_status', true ) : $lbb_email_notification_status;      
    $lbb_email_admin_notification_status = (get_post_meta( $chatflow_id, '_lbb_email_admin_notification_status', true ))? get_post_meta( $chatflow_id, '_lbb_email_admin_notification_status', true ) : $lbb_email_admin_notification_status;
    $lbb_email_admin_livechat_notification_status = (get_post_meta( $chatflow_id, '_lbb_email_admin_livechat_notification_status', true ))? get_post_meta( $chatflow_id, '_lbb_email_admin_livechat_notification_status', true ) : $lbb_email_admin_livechat_notification_status;
    $lbb_user_email_subject = (get_post_meta( $chatflow_id, '_lbb_user_email_subject', true ))? get_post_meta( $chatflow_id, '_lbb_user_email_subject', true ) : $lbb_user_email_subject;      
    $lbb_user_email_body = (get_post_meta( $chatflow_id, '_lbb_user_email_body', true ))? get_post_meta( $chatflow_id, '_lbb_user_email_body', true ) : $lbb_user_email_body;      
    $lbb_admin_emails = (get_post_meta( $chatflow_id, '_lbb_admin_emails', true ))? get_post_meta( $chatflow_id, '_lbb_admin_emails', true ) : $lbb_admin_emails;      
    $lbb_livechat_admin_emails = (get_post_meta( $chatflow_id, '_lbb_livechat_admin_emails', true ))? get_post_meta( $chatflow_id, '_lbb_livechat_admin_emails', true ) : $lbb_livechat_admin_emails;      
    $lbb_livechat_bell_notification = (get_post_meta( $chatflow_id, 'lbb_livechat_bell_notification', true ))? get_post_meta( $chatflow_id, 'lbb_livechat_bell_notification', true ) : $lbb_livechat_bell_notification;      
    $lbb_email_admin_name = (get_post_meta( $chatflow_id, '_lbb_email_admin_name', true ))? get_post_meta( $chatflow_id, '_lbb_email_admin_name', true ) : $lbb_email_admin_name;
    $lbb_admin_email_subject = (get_post_meta( $chatflow_id, '_lbb_admin_email_subject', true ))? get_post_meta( $chatflow_id, '_lbb_admin_email_subject', true ) : $lbb_admin_email_subject;
    $lbb_admin_email_body = (get_post_meta( $chatflow_id, '_lbb_admin_email_body', true ))? get_post_meta( $chatflow_id, '_lbb_admin_email_body', true ) : $lbb_admin_email_body;
        
        


       

      $chatbot_style_global_status = (get_post_meta( $chatflow_id, 'chatbot_style_global_status', true ))? get_post_meta( $chatflow_id, 'chatbot_style_global_status', true ) : $chatbot_style_global_status;
      $heading_style_global_status = (get_post_meta( $chatflow_id, 'heading_style_global_status', true ))? get_post_meta( $chatflow_id, 'heading_style_global_status', true ) : $heading_style_global_status;
      $question_style_global_status = (get_post_meta( $chatflow_id, 'question_style_global_status', true ))? get_post_meta( $chatflow_id, 'question_style_global_status', true ) : $question_style_global_status;
      $user_answer_style_global_status = (get_post_meta( $chatflow_id, 'user_answer_style_global_status', true ))? get_post_meta( $chatflow_id, 'user_answer_style_global_status', true ) : $user_answer_style_global_status;
      $answer_style_global_status = (get_post_meta( $chatflow_id, 'answer_style_global_status', true ))? get_post_meta( $chatflow_id, 'answer_style_global_status', true ) : $answer_style_global_status;
      $icon_style_global_status = (get_post_meta( $chatflow_id, 'icon_style_global_status', true ))? get_post_meta( $chatflow_id, 'icon_style_global_status', true ) : $icon_style_global_status;
      $shadow_style_global_status = (get_post_meta( $chatflow_id, 'shadow_style_global_status', true ))? get_post_meta( $chatflow_id, 'shadow_style_global_status', true ) : $shadow_style_global_status;
      $back_style_global_status = (get_post_meta( $chatflow_id, 'back_style_global_status', true ))? get_post_meta( $chatflow_id, 'back_style_global_status', true ) : $back_style_global_status;
      $knowledge_style_global_status = (get_post_meta( $chatflow_id, 'knowledge_style_global_status', true ))? get_post_meta( $chatflow_id, 'knowledge_style_global_status', true ) : $knowledge_style_global_status;
      $alignment_style_global_status = (get_post_meta( $chatflow_id, 'alignment_style_global_status', true ))? get_post_meta( $chatflow_id, 'alignment_style_global_status', true ) : $alignment_style_global_status;
      $expanded_style_global_status = (get_post_meta( $chatflow_id, 'expanded_style_global_status', true ))? get_post_meta( $chatflow_id, 'expanded_style_global_status', true ) : $expanded_style_global_status;
      $lbb_background_style_global_status = (get_post_meta( $chatflow_id, 'lbb_background_style_global_status', true ))? get_post_meta( $chatflow_id, 'lbb_background_style_global_status', true ) : $lbb_background_style_global_status;
      $other_style_global_status = (get_post_meta( $chatflow_id, 'other_style_global_status', true ))? get_post_meta( $chatflow_id, 'other_style_global_status', true ) : $other_style_global_status;
      
      $lbb_icon_size = (get_post_meta( $chatflow_id, 'lbb_icon_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_size', true ) : $lbb_icon_size;
      $lbb_icon_box_size = (get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ))? get_post_meta( $chatflow_id, 'lbb_icon_box_size', true ) : $lbb_icon_box_size;
      $live_chat_idle_time_enable = (get_post_meta( $chatflow_id, 'live_chat_idle_time_enable', true ))? get_post_meta( $chatflow_id, 'live_chat_idle_time_enable', true ) : $live_chat_idle_time_enable;
      $start_time = (get_post_meta( $chatflow_id, 'start_time', true ))? get_post_meta( $chatflow_id, 'start_time', true ) : $start_time;
      $end_time = (get_post_meta( $chatflow_id, 'end_time', true ))? get_post_meta( $chatflow_id, 'end_time', true ) : $end_time;
      $lbb_icon_padding = (get_post_meta( $chatflow_id, 'lbb_icon_padding', true ))? get_post_meta( $chatflow_id, 'lbb_icon_padding', true ) : $lbb_icon_padding;
      $lbb_icon_height = (get_post_meta( $chatflow_id, 'lbb_icon_height', true ))? get_post_meta( $chatflow_id, 'lbb_icon_height', true ) : $lbb_icon_height;
      $lbb_icon_width = (get_post_meta( $chatflow_id, 'lbb_icon_width', true ))? get_post_meta( $chatflow_id, 'lbb_icon_width', true ) : $lbb_icon_width;

      $lbb_shadow_spread_radius = (get_post_meta( $chatflow_id, 'lbb_shadow_spread_radius', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_spread_radius', true ) : $lbb_shadow_spread_radius;
      $lbb_shadow_blur_radius = (get_post_meta( $chatflow_id, 'lbb_shadow_blur_radius', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_blur_radius', true ) : $lbb_shadow_blur_radius;
      $lbb_shadow_horizontal_length = (get_post_meta( $chatflow_id, 'lbb_shadow_horizontal_length', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_horizontal_length', true ) : $lbb_shadow_horizontal_length;
      $lbb_shadow_vertical_length = (get_post_meta( $chatflow_id, 'lbb_shadow_vertical_length', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_vertical_length', true ) : $lbb_shadow_vertical_length;
      $lbb_shadow_background_color = (get_post_meta( $chatflow_id, 'lbb_shadow_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_shadow_background_color', true ) : $lbb_shadow_background_color;

      $aiassistant_main_prompt = (get_post_meta( $chatflow_id, 'aiassistant_main_prompt', true ))? get_post_meta( $chatflow_id, 'aiassistant_main_prompt', true ) : $aiassistant_main_prompt;
      $ai_welcome_message = (get_post_meta( $chatflow_id, 'ai_welcome_message', true ))? get_post_meta( $chatflow_id, 'ai_welcome_message', true ) : $ai_welcome_message;
      $aiassistant_rules = (get_post_meta( $chatflow_id, 'aiassistant_rules', true ))? get_post_meta( $chatflow_id, 'aiassistant_rules', true ) : $aiassistant_rules;
      $input_token_limit = (get_post_meta( $chatflow_id, 'input_token_limit', true ))? get_post_meta( $chatflow_id, 'input_token_limit', true ) : $input_token_limit;
      $no_response_msg = (get_post_meta( $chatflow_id, 'no_response_msg', true ))? get_post_meta( $chatflow_id, 'no_response_msg', true ) : $no_response_msg;
      $lbb_ai_welcome_message = (get_post_meta( $chatflow_id, '_trained_ai_welcome_message', true ))? get_post_meta( $chatflow_id, '_trained_ai_welcome_message', true ) : $lbb_ai_welcome_message;
      $main_aiassistant_rules = (get_post_meta( $chatflow_id, 'main_aiassistant_rules', true ))? get_post_meta( $chatflow_id, 'main_aiassistant_rules', true ) : $main_aiassistant_rules;
      $your_content_res = (get_post_meta( $chatflow_id, 'your_content_res', true ))? get_post_meta( $chatflow_id, 'your_content_res', true ) : $your_content_res;
      $output_token_limit = (get_post_meta( $chatflow_id, 'output_token_limit', true ))? get_post_meta( $chatflow_id, 'output_token_limit', true ) : $output_token_limit;
      $limit_threads = (get_post_meta( $chatflow_id, 'limit_threads', true ))? get_post_meta( $chatflow_id, 'limit_threads', true ) : $limit_threads;
      $api_model = (get_post_meta( $chatflow_id, 'api_model', true ))? get_post_meta( $chatflow_id, 'api_model', true ) : $api_model;
      $allow_embed_domains = (get_post_meta( $chatflow_id, 'allow_embed_domains', true ))? get_post_meta( $chatflow_id, 'allow_embed_domains', true ) : $allow_embed_domains;
      $minimized_type = (get_post_meta( $chatflow_id, 'minimized_type', true ))? get_post_meta( $chatflow_id, 'minimized_type', true ) : $minimized_type;
      $lbb_chat_header = (get_post_meta( $chatflow_id, 'lbb_chat_header', true ))? get_post_meta( $chatflow_id, 'lbb_chat_header', true ) : $lbb_chat_header;
      $maximized_chatbot_image = (get_post_meta( $chatflow_id, 'maximized_chatbot_image', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_image', true ) : $maximized_chatbot_image;
      $maximized_chatbot_icon = (get_post_meta( $chatflow_id, 'maximized_chatbot_icon', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_icon', true ) : $maximized_chatbot_icon;
      $maximized_chatbot_video = (get_post_meta( $chatflow_id, 'maximized_chatbot_video', true ))? get_post_meta( $chatflow_id, 'maximized_chatbot_video', true ) : $maximized_chatbot_video;
      $personalized_pdf_option = (get_post_meta( $chatflow_id, 'personalized_pdf_option', true ))? get_post_meta( $chatflow_id, 'personalized_pdf_option', true ) : $personalized_pdf_option;

      $how_to_show = (get_post_meta( $chatflow_id, 'how_to_show', true ))? get_post_meta( $chatflow_id, 'how_to_show', true ) : $how_to_show; 
      $lbb_ai_admin_email = (get_post_meta( $chatflow_id, 'lbb_ai_admin_email', true ))? get_post_meta( $chatflow_id, 'lbb_ai_admin_email', true ) : $lbb_ai_admin_email; 
      $lbb_ai_from_name = (get_post_meta( $chatflow_id, 'lbb_ai_from_name', true ))? get_post_meta( $chatflow_id, 'lbb_ai_from_name', true ) : $lbb_ai_from_name; 
      $lbb_ai_email_subject = (get_post_meta( $chatflow_id, 'lbb_ai_email_subject', true ))? get_post_meta( $chatflow_id, 'lbb_ai_email_subject', true ) : $lbb_ai_email_subject; 
      $lbb_ai_email_body = (get_post_meta( $chatflow_id, 'lbb_ai_email_body', true ))? get_post_meta( $chatflow_id, 'lbb_ai_email_body', true ) : $lbb_ai_email_body; 
      $allow_users_to_contact = (get_post_meta( $chatflow_id, 'allow_users_to_contact', true ))? get_post_meta( $chatflow_id, 'allow_users_to_contact', true ) : $allow_users_to_contact; 
      $lbb_ai_reponse_limit = (get_post_meta( $chatflow_id, '_lbb_ai_reponse_limit', true ))? get_post_meta( $chatflow_id, '_lbb_ai_reponse_limit', true ) : $lbb_ai_reponse_limit; 
      $video_text = (get_post_meta( $chatflow_id, 'video_text', true ))? get_post_meta( $chatflow_id, 'video_text', true ) : $video_text; 
      $lbb_minimized_type_option = (get_post_meta( $chatflow_id, 'lbb_minimized_type_option', true ))? get_post_meta( $chatflow_id, 'lbb_minimized_type_option', true ) : $lbb_minimized_type_option; 
      $lbb_first_disappear_options = (get_post_meta( $chatflow_id, 'lbb_first_disappear_options', true ))? get_post_meta( $chatflow_id, 'lbb_first_disappear_options', true ) : $lbb_first_disappear_options; 
      $lbb_first_popout_options = (get_post_meta( $chatflow_id, 'lbb_first_popout_options', true ))? get_post_meta( $chatflow_id, 'lbb_first_popout_options', true ) : $lbb_first_popout_options; 
      $lbb_first_popout_how_many_seconds = (get_post_meta( $chatflow_id, 'lbb_first_popout_how_many_seconds', true ))? get_post_meta( $chatflow_id, 'lbb_first_popout_how_many_seconds', true ) : $lbb_first_popout_how_many_seconds; 
      $lbb_first_disappear_how_many_seconds = (get_post_meta( $chatflow_id, 'lbb_first_disappear_how_many_seconds', true ))? get_post_meta( $chatflow_id, 'lbb_first_disappear_how_many_seconds', true ) : $lbb_first_disappear_how_many_seconds; 

      
        /*Not in Use*/

      $heading_font_family = (get_post_meta( $chatflow_id, 'heading_font_family', true ))? get_post_meta( $chatflow_id, 'heading_font_family', true ) : 'DM Sans,sans-serif';
      $selected_url = get_post_meta( $chatflow_id, 'selected_url', true );
      $enter_url = (get_post_meta( $chatflow_id, 'enter_url', true ))? get_post_meta( $chatflow_id, 'enter_url', true ) : '';
      $page_scroll_value = (get_post_meta( $chatflow_id, 'page_scroll_value', true ))? get_post_meta( $chatflow_id, 'page_scroll_value', true ) : '';
      $when_to_show = (get_post_meta( $chatflow_id, 'when_to_show', true ))? get_post_meta( $chatflow_id, 'when_to_show', true ) : '';
      

      $livechat_status = (get_post_meta( $chatflow_id, 'livechat_status', true ))? get_post_meta( $chatflow_id, 'livechat_status', true ) : 'yes';
      $time_input_value = (get_post_meta( $chatflow_id, 'time_input_value', true ))? get_post_meta( $chatflow_id, 'time_input_value', true ) : '';
      $who_should_see = (get_post_meta( $chatflow_id, 'who_should_see', true ))? get_post_meta( $chatflow_id, 'who_should_see', true ) : 'all_visitor';
      $automation_triggered = (get_post_meta( $chatflow_id, 'automation_triggered', true ))? get_post_meta( $chatflow_id, 'automation_triggered', true ) : 'after_email';
       
       
      $start_again = (get_post_meta( $chatflow_id, 'start_again', true ))? get_post_meta( $chatflow_id, 'start_again', true ) : '';
       
      $content_font_family = (get_post_meta( $chatflow_id, 'content_font_family', true ))? get_post_meta( $chatflow_id, 'content_font_family', true ) : 'DM Sans,sans-serif';
       
      $content_font_weight = (get_post_meta( $chatflow_id, 'content_font_weight', true ))? get_post_meta( $chatflow_id, 'content_font_weight', true ) : '400';
      $global_style = (get_post_meta( $chatflow_id, 'global_style', true ))? get_post_meta( $chatflow_id, 'global_style', true ) : 'N';
      $bot_user_image = (get_post_meta( $chatflow_id, 'bot_user_image', true ))? get_post_meta( $chatflow_id, 'bot_user_image', true ) : LBB_URL.'admin/images/avatar.png';
      $icon_image = (get_post_meta( $chatflow_id, 'icon_image', true ))? get_post_meta( $chatflow_id, 'icon_image', true ) : LBB_URL.'admin/images/chat.png';
      $submit_button_icon = (get_post_meta( $chatflow_id, 'submit_button_icon', true ))? get_post_meta( $chatflow_id, 'submit_button_icon', true ) : LBB_URL.'admin/images/avatar.png';
      $lbb_automation_status = (get_post_meta( $chatflow_id, '_lbb_automation_status', true ))? get_post_meta( $chatflow_id, '_lbb_automation_status', true ) : [];
      $ques_bg_color = (get_post_meta( $chatflow_id, 'ques_bg_color', true ))? get_post_meta( $chatflow_id, 'ques_bg_color', true ) : '#ffffff';
      $ques_text_color = (get_post_meta( $chatflow_id, 'ques_text_color', true ))? get_post_meta( $chatflow_id, 'ques_text_color', true ) : '#000000';
      $ans_bg_color = (get_post_meta( $chatflow_id, 'ans_bg_color', true ))? get_post_meta( $chatflow_id, 'ans_bg_color', true ) : '#000000';
      $lbb_icon_background_color = (get_post_meta( $chatflow_id, 'lbb_icon_background_color', true ))? get_post_meta( $chatflow_id, 'lbb_icon_background_color', true ) : $lbb_icon_background_color;
      $lbb_icon_border_radius = (get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ))? get_post_meta( $chatflow_id, 'lbb_icon_border_radius', true ) : $lbb_icon_border_radius;
      $lbb_chat_icon_color = (get_post_meta( $chatflow_id, 'lbb_chat_icon_color', true ))? get_post_meta( $chatflow_id, 'lbb_chat_icon_color', true ) : $lbb_chat_icon_color;
      $lbb_bot_image_select = (get_post_meta( $chatflow_id, 'lbb_bot_image_select', true ))? get_post_meta( $chatflow_id, 'lbb_bot_image_select', true ) : $lbb_bot_image_select;
      
      $lbb_image_upload_live = (get_post_meta( $chatflow_id, 'lbb_image_upload_live', true ))? get_post_meta( $chatflow_id, 'lbb_image_upload_live', true ) : $lbb_image_upload_live;
       
      $icon_height = (get_post_meta( $chatflow_id, 'icon_height', true ))? get_post_meta( $chatflow_id, 'icon_height', true ) : '96';
       
      $sub_heading_font_family = (get_post_meta( $chatflow_id, 'sub_heading_font_family', true ))? get_post_meta( $chatflow_id, 'sub_heading_font_family', true ) : 'DM Sans,sans-serif';
      $button_border_radius = (get_post_meta( $chatflow_id, 'button_border_radius', true ))? get_post_meta( $chatflow_id, 'button_border_radius', true ) : '5';
      $button_text_color = (get_post_meta( $chatflow_id, 'button_text_color', true ))? get_post_meta( $chatflow_id, 'button_text_color', true ) : '#0066ff';
      $chat_background_color = (get_post_meta( $chatflow_id, 'chat_background_color', true ))? get_post_meta( $chatflow_id, 'chat_background_color', true ) : '#eaeef3';
       
      $button_font_size = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
      $answer_button_font_size = (get_post_meta( $chatflow_id, 'button_font_size', true ))? get_post_meta( $chatflow_id, 'button_font_size', true ) : '16';
   }else{
    if(get_option('_lbb_email_notification_status') ){
        $lbb_email_notification_status = get_option('_lbb_email_notification_status');
    }
    if(get_option('_lbb_email_admin_notification_status') ){
        $lbb_email_admin_notification_status = get_option('_lbb_email_admin_notification_status');
    }
    if(get_option('_lbb_user_email_subject') ){
        $lbb_user_email_subject = get_option('_lbb_user_email_subject');
    }
    if(get_option('_lbb_user_email_body') ){
        $lbb_user_email_body = get_option('_lbb_user_email_body');
    }
    if(get_option('_lbb_admin_emails') ){
        $lbb_admin_emails = get_option('_lbb_admin_emails');
    }
    if(get_option('_lbb_admin_name') ){
        $lbb_email_admin_name = get_option('_lbb_admin_name');
    }
    if(get_option('_lbb_admin_email_subject') ){
        $lbb_admin_email_subject = get_option('_lbb_admin_email_subject');
    }
    if(get_option('_lbb_admin_email_body') ){
        $lbb_admin_email_body = get_option('_lbb_admin_email_body');
    }
   }
   
   if($when_to_show == 'upon_scroll'){
       $show_scroll = '';
       $show_timer = 'lbb-hide';
   }else if($when_to_show == 'certain_time'){
       $show_scroll = 'lbb-hide';
       $show_timer = '';
   }else{
       $show_scroll = 'lbb-hide';
       $show_timer = 'lbb-hide';
   }
   
   $livechat_section = ($livechat_status == 'no') ? 'display:none' : '';

   $welcome_message = "Hello 👋 Nice to see you here!";
   $email_capture = "just_email";
   $lbb_notify_chat = 'no';
   $page_load_options = 'yes';
   $skip_name_email = 'yes';
   $lbb_back_button = 'yes';

   $admin_emails = "";
   $set_timeout = "60";


   $welcome_message = (get_post_meta( $chatflow_id, 'welcome_message', true ))? get_post_meta( $chatflow_id, 'welcome_message', true ) : '';
    if(get_option('lbb_general_settings') ){
       if($welcome_message == ""){
         $lbb_general_settings = get_option('lbb_general_settings');
         $welcome_message = $lbb_general_settings['livechat_welcome_message'];
       }
   }

    $email_capture = (get_post_meta( $chatflow_id, 'email_capture', true ))? get_post_meta( $chatflow_id, 'email_capture', true ) : 'just_email';
    $lbb_notify_chat = (get_post_meta( $chatflow_id, 'lbb_notify_chat', true ))? get_post_meta( $chatflow_id, 'lbb_notify_chat', true ) : 'no';
    $skip_name_email = (get_post_meta( $chatflow_id, 'skip_name_email', true ))? get_post_meta( $chatflow_id, 'skip_name_email', true ) : $skip_name_email;
    $page_load_options = (get_post_meta( $chatflow_id, 'page_load_options', true ))? get_post_meta( $chatflow_id, 'page_load_options', true ) : 'yes';
    $lbb_back_button = (get_post_meta( $chatflow_id, 'lbb_back_button', true ))? get_post_meta( $chatflow_id, 'lbb_back_button', true ) : $lbb_back_button;
    $lbb_made_with = (get_post_meta( $chatflow_id, 'lbb_made_with', true ))? get_post_meta( $chatflow_id, 'lbb_made_with', true ) : $lbb_made_with;
    $lbb_made_with_text = (get_post_meta( $chatflow_id, 'lbb_made_with_text', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_text', true ) : $lbb_made_with_text;
    $lbb_made_with_link = (get_post_meta( $chatflow_id, 'lbb_made_with_link', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_link', true ) : $lbb_made_with_link;
    $lbb_made_with_link = urldecode($lbb_made_with_link);
    
    $lbb_made_with_hover_text = (get_post_meta( $chatflow_id, 'lbb_made_with_hover_text', true ))? get_post_meta( $chatflow_id, 'lbb_made_with_hover_text', true ) : $lbb_made_with_hover_text;
    $admin_emails = (get_post_meta( $chatflow_id, 'admin_emails', true ))? get_post_meta( $chatflow_id, 'admin_emails', true ) : '';
    $set_timeout = (get_post_meta( $chatflow_id, 'set_timeout', true ))? get_post_meta( $chatflow_id, 'set_timeout', true ) : '60';

    $chatflow_type = get_post_meta($chatflow_id, '_chatflow_type', true);
        
    if($chatflow_type == 'botlivechat' || $chatflow_type == 'livechat'){
        $bot_live_chat_option = '';
    }

    if($chatflow_type == 'botlivechat'){
        $live_chat_option = '';
    }


   // Global Data (Options Table)

   
    $livechat_default = array('agent', 'live agent', 'connect to an agent', 'transfer to an agent' );
    $livechat_words = get_option('lbb_livechat_words', $livechat_default);
    $livechat_words = !empty($livechat_words) ? implode ("\n", $livechat_words) : '';
    $livechat_connecting_message = get_option('lbb_livechat_connecting_message','Please wait... checking if an agent is available');

   
  


    $global_heading_background_color = (get_option('lbb_heading_background_color' ))? get_option('lbb_heading_background_color', true ): $lbb_heading_background_color;
    $global_lbb_container_width = (get_option('lbb_container_width' ))? get_option('lbb_container_width', true ): $lbb_container_width;
    $global_lbb_common_font_family = (get_option('lbb_common_font_family' ))? get_option('lbb_common_font_family', true ): $lbb_common_font_family;

    $global_lbb_heading_font_size = (get_option('lbb_heading_font_size' ))? get_option('lbb_heading_font_size', true ): $lbb_heading_font_size;
    $global_lbb_heading_font_weight = (get_option('lbb_heading_font_weight' ))? get_option('lbb_heading_font_weight', true ): $lbb_heading_font_weight;
    $global_lbb_heading_text_color = (get_option('lbb_heading_text_color' ))? get_option('lbb_heading_text_color', true ): $lbb_heading_text_color;
    $global_lbb_sub_heading_font_size = (get_option('lbb_sub_heading_font_size' ))? get_option('lbb_sub_heading_font_size', true ): $lbb_sub_heading_font_size;
    $global_lbb_sub_heading_font_weight = (get_option('lbb_sub_heading_font_weight' ))? get_option('lbb_sub_heading_font_weight', true ): $lbb_sub_heading_font_weight;
    $global_lbb_sub_heading_text_color = (get_option('lbb_sub_heading_text_color' ))? get_option('lbb_sub_heading_text_color', true ): $lbb_sub_heading_text_color;

    $global_lbb_question_font_size = (get_option('lbb_question_font_size' ))? get_option('lbb_question_font_size', true ): $lbb_question_font_size;
    $global_lbb_question_font_weight = (get_option('lbb_question_font_weight' ))? get_option('lbb_question_font_weight', true ): $lbb_question_font_weight;
    $global_lbb_question_text_color = (get_option('lbb_question_text_color' ))? get_option('lbb_question_text_color', true ): $lbb_question_text_color;
    $global_lbb_question_background_color = (get_option('lbb_question_background_color' ))? get_option('lbb_question_background_color', true ): $lbb_question_background_color;

    $global_lbb_user_answer_font_size = (get_option('lbb_user_answer_font_size' ))? get_option('lbb_user_answer_font_size', true ): $lbb_user_answer_font_size;
    $global_lbb_user_answer_font_weight = (get_option('lbb_user_answer_font_weight' ))? get_option('lbb_user_answer_font_weight', true ): $lbb_user_answer_font_weight;
    $global_lbb_user_answer_text_color = (get_option('lbb_user_answer_text_color' ))? get_option('lbb_user_answer_text_color', true ): $lbb_user_answer_text_color;
    $global_lbb_user_answer_background_color = (get_option('lbb_user_answer_background_color' ))? get_option('lbb_user_answer_background_color', true ): $lbb_user_answer_background_color;

    $global_lbb_ans_bg_color = (get_option('lbb_ans_bg_color' ))? get_option('lbb_ans_bg_color', true ): $lbb_ans_bg_color;
    $global_lbb_ans_border_color = (get_option('lbb_ans_border_color' ))? get_option('lbb_ans_border_color', true ): $lbb_ans_border_color;
    $global_lbb_ans_text_color = (get_option('lbb_ans_text_color' ))? get_option('lbb_ans_text_color', true ): $lbb_ans_text_color;
    $global_lbb_ans_border_radius = (get_option('lbb_ans_border_radius' ))? get_option('lbb_ans_border_radius', true ): $lbb_ans_border_radius;
    $global_lbb_ans_font_size = (get_option('lbb_ans_font_size' ))? get_option('lbb_ans_font_size', true ): $lbb_ans_font_size;
    $global_lbb_ans_font_weight = (get_option('lbb_ans_font_weight' ))? get_option('lbb_ans_font_weight', true ): $lbb_ans_font_weight;

    $global_lbb_max_border_color = (get_option('lbb_max_border_color' ))? get_option('lbb_max_border_color', true ): $lbb_max_border_color;
    $global_lbb_max_border_width = (get_option('lbb_max_border_width' ))? get_option('lbb_max_border_width', true ): $lbb_max_border_width;
    $global_lbb_max_border_radius = (get_option('lbb_max_border_radius' ))? get_option('lbb_max_border_radius', true ): $lbb_max_border_radius;
    $global_lbb_max_width = (get_option('lbb_max_width' ))? get_option('lbb_max_width', true ): $lbb_max_width;
    $global_lbb_max_height = (get_option('lbb_max_height' ))? get_option('lbb_max_height', true ): $lbb_max_height;
    $global_lbb_max_bg_color = (get_option('lbb_max_bg_color' ))? get_option('lbb_max_bg_color', true ): $lbb_max_bg_color;

    $global_lbb_chat_alignment = (get_option('lbb_chat_alignment' ))? get_option('lbb_chat_alignment', true ): $lbb_chat_alignment;
    $global_lbb_right_spacing = (get_option('lbb_right_spacing' ))? get_option('lbb_right_spacing', true ): $lbb_right_spacing;
    $global_lbb_left_spacing = (get_option('lbb_left_spacing' ))? get_option('lbb_left_spacing', true ): $lbb_left_spacing;
    $global_lbb_bottom_spacing = (get_option('lbb_bottom_spacing' ))? get_option('lbb_bottom_spacing', true ): $lbb_bottom_spacing;

    $global_lbb_shadow_spread_radius = (get_option('lbb_shadow_spread_radius' ))? get_option('lbb_shadow_spread_radius', true ): $lbb_shadow_spread_radius;
    $global_lbb_shadow_blur_radius = (get_option('lbb_shadow_blur_radius' ))? get_option('lbb_shadow_blur_radius', true ): $lbb_shadow_blur_radius;
    $global_lbb_shadow_horizontal_length = (get_option('lbb_shadow_horizontal_length' ))? get_option('lbb_shadow_horizontal_length', true ): $lbb_shadow_horizontal_length;
    $global_lbb_shadow_vertical_length = (get_option('lbb_shadow_vertical_length' ))? get_option('lbb_shadow_vertical_length', true ): $lbb_shadow_vertical_length;
    $global_lbb_shadow_background_color = (get_option('lbb_shadow_background_color' ))? get_option('lbb_shadow_background_color', true ): $lbb_shadow_background_color;


    $lbb_contactform_settings = get_option('lbb_contactform_settings');
    if($lbb_contactform_settings){
        $lbb_contact_font_size = $lbb_contactform_settings['lbb_contact_font_size'];
        $lbb_contact_font_weight = $lbb_contactform_settings['lbb_contact_font_weight'];
        $lbb_contact_button_text_color = $lbb_contactform_settings['lbb_contact_button_text_color'];
        $lbb_contact_button_bg_color = $lbb_contactform_settings['lbb_contact_button_bg_color'];
    }

    

?>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=<?php echo $lbb_common_font_family; ?>">
<style type="text/css">
    body .lbb-global-style-chatbot{
        --lbb-chat-container-width: <?= $global_lbb_container_width ?>;
        --lbb-chat-heading-bg-color: <?= $global_heading_background_color ?>;
        --lbb-chat-content-font-family: <?= $global_lbb_common_font_family ?>;
    }

    body .lbb-global-style-heading{
        --lbb-chat-heading-font-size:<?= $global_lbb_heading_font_size.'px'; ?>;
        --lbb-chat-heading-font-weight:<?= $global_lbb_heading_font_weight; ?>;
        --lbb-chat-heading-text-color:<?= $global_lbb_heading_text_color; ?>;
        --lbb-chat-sub-heading-font-size:<?= $global_lbb_sub_heading_font_size; ?>;
        --lbb-chat-sub-heading-font-weight:<?= $global_lbb_sub_heading_font_weight; ?>;
        --lbb-chat-sub-heading-text-color:<?= $global_lbb_sub_heading_text_color; ?>;
    }

    body .lbb-global-style-question{
        --lbb-question-font-size: <?= $global_lbb_question_font_size.'px'; ?>;
        --lbb-question-font-weight: <?= $global_lbb_question_font_weight; ?>;
        --lbb-question-text-color: <?= $global_lbb_question_text_color; ?>;
        --lbb-question-background-color: <?= $global_lbb_question_background_color; ?>;
    }

    body .lbb-global-style-user-answer{
        --lbb-user-answer-font-size: <?= $global_lbb_user_answer_font_size.'px'; ?>;
        --lbb-user-answer-font-weight: <?= $global_lbb_user_answer_font_weight; ?>;
        --lbb-user-answer-text-color: <?= $global_lbb_user_answer_text_color; ?>;
        --lbb-user-answer-background-color: <?= $global_lbb_user_answer_background_color; ?>;
    }

    body .lbb-global-style-answer{
        --lbb-chat-answer-btn-border-color:<?= $global_lbb_ans_border_color; ?>;
        --lbb-chat-answer-btn-text-color:<?= $global_lbb_ans_text_color; ?>;
        --lbb-chat-answer-btn-border-radius: <?= $global_lbb_ans_border_radius.'px'; ?>;
        --lbb-chat-answer-btn-background-color:<?= $global_lbb_ans_bg_color; ?>;
        --lbb-chat-answer-btn-font-size: <?= $global_lbb_ans_font_size.'px'; ?>;
        --lbb-chat-answer-btn-font-weight: <?= $global_lbb_ans_font_weight; ?>;
    }

    body .lbb-global-style-expanded{
        --lbb-max-border-color: <?= $global_lbb_max_border_color; ?>;
        --lbb-max-border-width: <?= $global_lbb_max_border_width.'px'; ?>;
        --lbb-max-border-radius: <?= $global_lbb_max_border_radius.'px'; ?>;
        --lbb-max-width: <?= $global_lbb_max_width.'px'; ?>;
        --lbb-max-height: <?= $global_lbb_max_height.'px'; ?>;
        --lbb-max-bg-color: <?= $global_lbb_max_bg_color; ?>;
    }

    body .lbb-global-style-alignment{
        --lbb-chat-aligmnet: <?= $global_lbb_chat_alignment; ?>;
        --lbb-chat-right-spacing: <?= $global_lbb_right_spacing; ?>;
        --lbb-chat-left-spacing: <?= $global_lbb_left_spacing; ?>;
        --lbb-chat-bottom-spacing: <?= $global_lbb_bottom_spacing; ?>;
    }

    body .lbb-global-style-shadow{
        --lbb-shadow-spread-radius: <?= $global_lbb_shadow_spread_radius; ?>;
        --lbb-shadow-blur-radius: <?= $global_lbb_shadow_blur_radius; ?>;
        --lbb-shadow-horizontal-length: <?= $global_lbb_shadow_horizontal_length; ?>;
        --lbb-shadow-vertical-length: <?= $global_lbb_shadow_vertical_length; ?>;
        --lbb-shadow-background-color: <?= $global_lbb_shadow_background_color; ?>;
    }

</style>