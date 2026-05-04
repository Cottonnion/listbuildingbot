<?php

class LBB_OpenAI extends LBB_AI{

    private $apiKey;
    public $headers;
    public $url;
    public $model;
    public $max_token;
    public $temperature;

    public function __construct($apiKey,$model = 'gpt-4o-mini') {
        $this->apiKey = $apiKey;
        $this->url = 'https://api.openai.com/v1/chat/completions';
        $this->model = $model;
        $this->max_token = 600;
        $this->temperature = 1;
    }

    public function setApiUrl($u){
        $this->url = $u;
    }

    public function setHeader(){
        $this->headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        );
    }

    public function setMaxToken($v){
        $this->max_token = $v;
    }

    public function setTemperature($v){
        $this->temperature = $v;
    }

    public function sendRequest($messages){

        $data = array(
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => (int) $this->max_token,
            'temperature' => $this->temperature,
            'stop' => null
        );
        $dataString = json_encode($data);

        $this->setHeader();

        $respone = $this->callAPI('POST', $this->url, $dataString,$this->headers);

        return $respone;
        
    }

}