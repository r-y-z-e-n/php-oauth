<?php

namespace Ryzen\Oauth;

class Oauth
{
    private string $app_url;
    private string $app_id;
    private string $app_secret;

    public function __construct( array $configuration = [] ) {
        $this->app_id     = $configuration['app_id'];
        $this->app_url    = $configuration['app_url'];
        $this->app_secret = $configuration['app_secret'];
    }

    private function getAccessToken(string $code_from_url = ''){
        $request_access_token = $this->app_url."/authorize?app_id=".$this->app_id."&app_secret=".$this->app_secret."&code={$code_from_url}";
        return json_decode(file_get_contents($request_access_token), true);
    }

    private function getUserData($array){
        $access_token   = (!empty($array['access_token'])) ? $array['access_token'] : '';
        $request_url    =  $this->app_url. "/app_api?access_token=".$access_token."&type=get_user_data";
        return json_decode(file_get_contents($request_url), true);
    }

    public function init(){
        if(empty($this->app_secret) || empty($this->app_id) || empty($this->app_url) || !filter_var($this->app_url,FILTER_VALIDATE_URL)){
            exit('Invalid Configuration Passed');
        }
        $code_from_url  = (isset($_GET['code']) && !empty($_GET['code'])) ? strip_tags($_GET['code']) : '';
        $getAccessToken = $this->getAccessToken($code_from_url);
        return $this->getUserData($getAccessToken);
    }
}