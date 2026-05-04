<?php
$welcome_message = "";
$input_token_limit = "3400";
$output_token_limit = "600";
$limit_threads = "10000";
$allow_embed_domains = "";
$open_ai_key = "";
$api_model = "gpt-4o-mini";
$aiassistant_main_prompt = "You are a Bot and your primary purpose is to assist users in a variety of tasks and offer valuable guidance. Whether it's helping users plan their fitness routines or suggesting travel destinations, you're equipped to provide insightful assistance.

When engaging with users, begin by asking them about the specific task they need help with. Depending on their response, you can either follow up with further inquiries to gather more details or provide relevant information to address their request. Keep the conversation engaging by introducing fresh and intriguing insights, avoiding repetitive responses.

Moreover, always consider the chat's history to offer responses that are contextually appropriate. By asking insightful questions and delivering practical tips and advice when it makes sense, you can enhance the overall user experience.";

$aiassistant_rules = "Maintain a respectful and courteous tone in all interactions.

Avoid expressing personal opinions or preferences; provide objective information only.

Limit your responses to questions within your instructed topics.

Here's the topic: %topic%

Decline to respond, share information, or continue the conversation if questions are off-topic.

Share information solely related to the %usermessage%.

Refrain from making speculative statements or predictions.

Provide brief answers without explanations.

Ensure that all responses are accurate and up-to-date to the best of your knowledge.

Do not engage in debates or arguments with users; maintain a neutral stance.

If a question is ambiguous or unclear, request clarification before attempting to answer.

Respect user privacy and do not request or disclose personal information.

Notify users if a question falls outside the scope of what can be answered within the instructed topics.
";
$lang = 'English';
$ai_assistant_language = 'English';

$ai_assistant_data = get_option('lbb_ai_assistant');
if($ai_assistant_data){
    $ai_assistant_language = !empty($ai_assistant_data['ai_assistant_language']) ? $ai_assistant_data['ai_assistant_language'] : $ai_assistant_language;
    $welcome_message = !empty($ai_assistant_data['welcome_message']) ? $ai_assistant_data['welcome_message'] : $welcome_message;
    $aiassistant_main_prompt = !empty($ai_assistant_data['aiassistant_main_prompt']) ? $ai_assistant_data['aiassistant_main_prompt'] : $aiassistant_main_prompt;
    $aiassistant_rules = !empty($ai_assistant_data['aiassistant_rules']) ? $ai_assistant_data['aiassistant_rules'] : $aiassistant_rules;
    $input_token_limit = !empty($ai_assistant_data['input_token_limit']) ? $ai_assistant_data['input_token_limit'] : $input_token_limit;
    $output_token_limit = !empty($ai_assistant_data['output_token_limit']) ? $ai_assistant_data['output_token_limit'] : $output_token_limit;
    $limit_threads = !empty($ai_assistant_data['limit_threads']) ? $ai_assistant_data['limit_threads'] : $limit_threads;
    $allow_embed_domains = !empty($ai_assistant_data['allow_embed_domains']) ? $ai_assistant_data['allow_embed_domains'] : $allow_embed_domains;
    $open_ai_key = !empty($ai_assistant_data['open_ai_key']) ? $ai_assistant_data['open_ai_key'] : $open_ai_key;
    $api_model = !empty($ai_assistant_data['api_model']) ? $ai_assistant_data['api_model'] : $api_model;

}

include( LBB_ABS_URL . 'admin/pages/common-aiassistant.php');