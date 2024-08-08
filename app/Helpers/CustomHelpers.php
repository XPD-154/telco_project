<?php

use App\Models\RequestLog;

if(!function_exists('generateRandomString')){

    function generateRandomString($lenght=10){
        $charaters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for($i=0; $i<$length; $i++){
            $randomString .= $charaters[rand(0, strlen($charaters)-1)];
        }

        return $randomString;
    }
}

if(!function_exists('logSimRequest')){

    function logSimRequest($user, $body, $response){
        
        $log_request = new RequestLog;

        $log_request->user = $user;
        $log_request->request_payload = $body;
        $log_request->respond_payload = $response;

        $log_request->save();
    }
}