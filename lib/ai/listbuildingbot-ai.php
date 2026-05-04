<?php

class LBB_AI {

    public function __construct() {
        
    }

    public function callAPI($method,$url,$dataString,$headers){

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    
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

}