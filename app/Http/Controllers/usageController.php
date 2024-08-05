<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Giunashvili\XMLParser\Parse;


class usageController extends Controller
{
    //usage check function
    public function usage(Request $request)
    {   
        ///////////check for variables//////////////
        if($request->has('mdn')) {

            //////parameter//////
            $mdn = $request['mdn'];
            //////end of parameter//////

            ////API call////
            $client = new Client();
            
            $headers = [
                'Content-Type' => 'text/xml',
                'Accept' => 'application/xml',
                'Connection' => 'keep-alive'
            ];

            $body = '<?xml version="1.0" encoding="utf-8"?>
            <wholeSaleApi xmlns="http://www.oss.vcarecorporation.com/oss" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <session>
                    <clec>
                        <id>'.env("PWGNS_ID").'</id>
                        <agentUser>
                            <username>'.env("PWGNS_USERNAME").'</username>
                            <token>'.env("PWGNS_TOKEN").'</token>
                            <pin>'.env("PWGNS_PIN").'</pin>
                        </agentUser>
                    </clec>
                    </session>
                    <request type="QueryUsage">
                    <esn></esn>
                    <mdn>'.$mdn.'</mdn>
                    </request>
            </wholeSaleApi>';

            try {

                $request = new \GuzzleHttp\Psr7\Request('POST', 'https://oss.vcarecorporation.com:22712/api/', $headers, $body);

                $res = $client->sendAsync($request)->wait();
                $result = $res->getBody();
                

                $output = Parse :: xmlAsArray($result);
                return $output;

            } catch (ConnectException $e) {
                
                $response = $e->getMessage();
                return ["status"=>"error", "message"=>$response];
                
            }
            ////end of API call////

        }else{

            return ["status"=>"error", "message"=>"incomplete data"];
        }
        ///////////end of check for variables//////////////
 
    }
}
