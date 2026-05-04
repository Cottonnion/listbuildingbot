<?php
add_action('wp_ajax_lbb_generate_outcomes', 'lbb_generate_outcomes');
add_action('wp_ajax_nopriv_lbb_generate_outcomes', 'lbb_generate_outcomes');

function lbb_generate_outcomes(){

    $prompt = !empty($_POST['prompt']) ? $_POST['prompt'] : '';
    
    $prompt = str_replace('json[','```json[',$prompt);
    $prompt = str_replace('json{','```json{',$prompt);
    $response = lbb_send_ai_request($prompt);
    if(isset($response['error'])){
        $ajaxResponse = array(
            'status' => 'error',
            'code' => $response['error']['code'],
            'message' => $response['error']['message']
        );
        echo json_encode($ajaxResponse);die;
    }
    $reply = $response['choices'][0]['message']['content'];
    $orignal = $reply;
    $reply = str_replace(array("\n","\r"), '', $reply);
    
    $reply = trim($reply);
    $reply = stripslashes($reply);
   
    $d = 0;
    if(strpos($reply, "```json") !== false){
        $pattern = "/```json(.*?)```/";
        $d=1;
    }else if(strpos($reply, "```") !== false){
        $pattern = "/```(.*?)```/";
        $d=1;
    }else{
        $pattern = '/(\{.*?}\)/s';
    }
    // Perform the regular expression match
    if($d == 1){
        if (preg_match($pattern, $reply, $matches)) {
            $capturedString = $matches[1];
        }else{
            $capturedString = $reply;
        }
    }else{
        $capturedString = $reply;
    }

        $data = json_decode($capturedString,true);
        if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
            $ajaxResponse = array(
                'status' => 'ok',
                'prompt' => $prompt,
                'data' => $data,
                'orignal' => $orignal,
            );
        } else {
            $ajaxResponse = array(
                'status' => 'error',
                'prompt' => $prompt,
                'orignal' => $orignal,
                'html' => 'It looks like the call timed out. Please try again!'
            );
        }
  
    echo json_encode($ajaxResponse);
    exit;
}

add_action('wp_ajax_lbb_create_questions', 'lbb_create_questions');
add_action('wp_ajax_nopriv_lbb_create_questions', 'lbb_create_questions');

function lbb_create_questions(){
    
    $prompt = !empty($_POST['prompt']) ? $_POST['prompt'] : '';
    
    $prompt = str_replace('json[','```json[',$prompt);
    $prompt = str_replace('json{','```json{',$prompt);
    $response = lbb_send_ai_request($prompt);
    if(isset($response['error'])){
        $ajaxResponse = array(
            'status' => 'error',
            'code' => $response['error']['code'],
            'message' => $response['error']['message']
        );
        echo json_encode($ajaxResponse);die;
    }
    $reply = $response['choices'][0]['message']['content'];
    $orignal = $reply;
    $reply = str_replace(array("\n","\r"), '', $reply);
    
    $reply = trim($reply);
    $reply = stripslashes($reply);
   
    $d = 0;
    if(strpos($reply, "```json") !== false){
        $pattern = "/```json(.*?)```/";
        $d=1;
    }else if(strpos($reply, "```") !== false){
        $pattern = "/```(.*?)```/";
        $d=1;
    }else{
        $pattern = '/(\{.*?}\)/s';
    }
    // Perform the regular expression match
    if($d == 1){
        if (preg_match($pattern, $reply, $matches)) {
            $capturedString = $matches[1];
        }else{
            $capturedString = $reply;
        }
    }else{
        $capturedString = $reply;
    }

        $data = json_decode($capturedString,true);
        if ($data !== null && json_last_error() === JSON_ERROR_NONE) {
            $ajaxResponse = array(
                'status' => 'ok',
                'prompt' => $prompt,
                'data' => $data,
                'orignal' => $orignal,
            );
        } else {
            $ajaxResponse = array(
                'status' => 'error',
                'prompt' => $prompt,
                'orignal' => $orignal,
                'html' => 'It looks like the call timed out. Please try again!'
            );
        }
    /*}else{
        $ajaxResponse = array(
            'status' => 'error',
            'prompt' => $prompt,
            'orignal' => $orignal,
            'html' => 'It looks like the call timed out. Please try again!'
        );
    } */  
    echo json_encode($ajaxResponse);
    exit;
}

function update_ass_instructions($assistantId,$content){

    $ai_assistant = get_option('lbb_ai_assistant');
    $openaiToken = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';

    $url = "https://api.openai.com/v1/assistants/$assistantId";

    $data = array(
        "instructions" => $content,
        "tools" => array(array("type" => "retrieval")),
        "model" => $openaiModel,
    );

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer $openaiToken",
            "OpenAI-Beta: assistants=v1"
        ),
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data)
    );

    $curl = curl_init();
    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);

    return $response;

    curl_close($curl);

}

function lbb_send_ai_request($message){


    $ai_assistant = get_option('lbb_ai_assistant');
    $openaiToken = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['open_ai_model']) ? $ai_assistant['open_ai_model'] : '';
    if(empty($openaiModel)){
        $openaiModel = 'gpt-4o-mini';
        //$openaiModel = 'gpt-4-1106-preview';
    }
    
    $model = $openaiModel;
    $url = 'https://api.openai.com/v1/chat/completions';
    $data = array(
        'model' => $model,
        'messages' => array(
            array('role' => 'system', 'content' => 'You are a helpful assistant.'),
            array('role' => 'user', 'content' => $message)
        ),
        'temperature' => 1,
        //'max_tokens' => 256,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    );
    

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openaiToken
    );
    $dataString = json_encode($data);
    try {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        if ($status_code === 200) {
            $responseData = json_decode($response, true);
        } else if($status_code === 504){
            throw new Exception("Gateway Timeout Error: " . $status_code);
        }else{
            $responseData = json_decode($response, true);
           
            if(isset($responseData['error'])){
                return $responseData;
            }else{
                throw new Exception("Something went to wrong: " . $status_code);
            }
        }
    } catch (Exception $e) {
        // Handle the exception or log the error
        $errorMessage = $e->getMessage();
        $responseData = array();
        $responseData['error'] = array(
            'status' => 'error',
            'code' => $status_code,
            'message' => $errorMessage
        );
    }
    return $responseData;
}

function lbb_openai_create_assistant($with_error = false){

    $ai_assistant = get_option('lbb_ai_assistant');
    $openaiApiKey = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';


    $url = "https://api.openai.com/v1/assistants";

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer $openaiApiKey",
        "OpenAI-Beta: assistants=v2"
    );

    $data = array(
        "instructions" => "",
        "name" => "default chatbot",
        "tools" => array(
            array("type" => "file_search")
        ),
        "model" => "gpt-4o"
    );

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    
    $assistant_response = curl_exec($ch);
    $assistant_data = json_decode($assistant_response);
    $assistant_id = $assistant_data->id;

    curl_close($ch);

    if($with_error){
        return (array) $assistant_data;
    }else{
        return $assistant_id;
    }

}

function lbb_openai_attach_filestore($assistantId, $file_ids, $instruction = ''){



    $ai_assistant = get_option('lbb_ai_assistant');
    $openaiApiKey = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';


    $vector_id = get_option('vectorid_'.$assistantId, '');

    $headers = array(
        'Authorization: Bearer ' . $openaiApiKey,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v2'
    );

    if(empty($vector_id)){

        // 1. Create Vector Store
        $vector_store_url = 'https://api.openai.com/v1/vector_stores';
        $vector_store_data = array(
            'name' => 'Assistant Vector Store - ' . date('Y-m-d H:i:s')
        );

        
        

        $vector_store_ch = curl_init($vector_store_url);
        curl_setopt_array($vector_store_ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($vector_store_data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true
        ));

        $vector_store_response = curl_exec($vector_store_ch);


        $vector_store_http_code = curl_getinfo($vector_store_ch, CURLINFO_HTTP_CODE);
        

        if ($vector_store_http_code !== 200) {
            error_log('Vector Store Creation Failed: ' . $vector_store_response);
            curl_close($vector_store_ch);
            return false;
        }

        $vector_store_result = json_decode($vector_store_response, true);
        $vector_store_id = $vector_store_result['id'];
        update_option('vectorid_'.$assistantId, $vector_store_id);
        curl_close($vector_store_ch);
    }else{
        $vector_store_id = $vector_id;
    }

    

    // 2. Upload Files to Vector Store
    $file_upload_url = "https://api.openai.com/v1/vector_stores/{$vector_store_id}/files";
    $uploaded_file_ids = [];
    
    
    foreach ($file_ids as $file_id) {

        if(empty($file_id)){
            continue;
        }
      
        $file_upload_ch = curl_init($file_upload_url);
        curl_setopt_array($file_upload_ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{"file_id": "'.$file_id.'"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $openaiApiKey,
                'Content-Type: application/json',
                'OpenAI-Beta: assistants=v2'
            ),
            CURLOPT_RETURNTRANSFER => true
        ));

        $file_upload_response = curl_exec($file_upload_ch);

        $file_upload_http_code = curl_getinfo($file_upload_ch, CURLINFO_HTTP_CODE);

        
        if ($file_upload_http_code !== 200) {
            error_log("File upload failed for {$file_path}: " . $file_upload_response);
            curl_close($file_upload_ch);
            continue;
        }

        $file_upload_result = json_decode($file_upload_response, true);
        $uploaded_file_ids[] = $file_upload_result['id'];

        curl_close($file_upload_ch);
    }



    // 3. Update Assistant with Vector Store
    $assistant_update_url = "https://api.openai.com/v1/assistants/{$assistantId}";
    $assistant_update_data = array(
        'tool_resources' => array(
            'file_search' => array(
                'vector_store_ids' => [$vector_store_id]
            )
        )
    );

    if (!empty($instruction)) {
        $assistant_update_data['instructions'] = $instruction;
    }

    $assistant_update_ch = curl_init($assistant_update_url);
    curl_setopt_array($assistant_update_ch, array(
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($assistant_update_data),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true
    ));

    $assistant_update_response = curl_exec($assistant_update_ch);

    $assistant_update_http_code = curl_getinfo($assistant_update_ch, CURLINFO_HTTP_CODE);

    curl_close($assistant_update_ch);

    if ($assistant_update_http_code !== 200) {
        error_log("Assistant update failed: " . $assistant_update_response);
        return false;
    }

}

function lbb_openai_upload_file_to_assistant($assistantId,$file_ids,$instruction = ''){

    $ai_assistant = get_option('lbb_ai_assistant');
    $openaiApiKey = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';
    
    // Request headers
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openaiApiKey,
        'OpenAI-Beta: assistants=v2',
    );

    // Request data
    $data = array(
        'instructions' => $instruction,
        'tools' => array(
            array('type' => 'file_search'),
        ),
        'model' => $openaiModel,
        'file_ids' => $file_ids,
    );

    // cURL options
    $options = array(
        CURLOPT_URL => 'https://api.openai.com/v1/assistants/' . $assistantId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
    );

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt_array($ch, $options);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);

}

function lbb_openai_upload_file($file_path,$with_error = false){


    $ai_assistant = get_option('lbb_ai_assistant');
    $api_key = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';
    $openaiModel = isset($ai_assistant['api_model']) ? $ai_assistant['api_model'] : '';

    $headers = array(
        "Content-Type: application/json",
        "OpenAI-Beta: assistants=v2",
        "Authorization: Bearer " . $api_key
    );

    $file_upload_url = 'https://api.openai.com/v1/files';

    $headers_files = array(
        'Authorization: Bearer ' . $api_key,
    );

    $data_file = array(
        'purpose' => 'assistants',
        'file' => new CURLFile($file_path),
    );

    $ch = curl_init($file_upload_url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_file);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_files);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        die('Curl error: ' . curl_error($ch));
    }

    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $file_id = '';
    
    $result = json_decode($response, true);
    if($with_error){
        return (array) $result;
    }else{
        $file_id = $result['id'];
    }
    
    return $file_id;
}

function lbb_delete_assistant_file($fileId){
    

    $ai_assistant = get_option('lbb_ai_assistant');
    $OPENAI_API_KEY = isset($ai_assistant['open_ai_key']) ? $ai_assistant['open_ai_key'] : '';

    //$fileId = 'file-9F1ex49ipEnKzyLUNnCA0Yzx'; // Replace with the actual file ID

    $ch = curl_init();

    $url = "https://api.openai.com/v1/assistants/asst_DUGk5I7sK0FpKeijvrO30z9J/files/$fileId";

    $headers = array(
        'Authorization: Bearer ' . $OPENAI_API_KEY,
        'Content-Type: application/json',
        'OpenAI-Beta: assistants=v1',
    );

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response;

}