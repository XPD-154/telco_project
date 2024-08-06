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

class changePlanController extends Controller
{
    //change SIM plan function
    public function changePlan(Request $request){


        ///////////check for variables//////////////
        if($request->has('mdn') && $request->has('plan_id')) {

            //////parameters//////
            $mdn = $request['mdn'];
            $plan_id = $request['plan_id'];

            $zip = isset($request['zip'])?$request['zip']:"";
            $sim = isset($request['sim'])?$request['sim']:"";
            $street1 = isset($request['street1'])?$request['street1']:"";
            $street2 = isset($request['street2'])?$request['street2']:"";
            $city = isset($request['city'])?$request['city']:"";
            $state = isset($request['state'])?$request['state']:"";
            //////end of parameters//////


            ////API call////
            $client = new Client();
            
            $headers = [
                'Content-Type' => 'text/xml',
                'Accept' => 'application/xml',
                'Connection' => 'keep-alive'
            ];

            $body = '<?xml version="1.0" encoding="utf-8"?>
            <wholeSaleApi xmlns="http://www.oss.vcarecorporation.com/oss" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" >
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
                <request type="ChangePlan">
                    <mdn>'.$mdn.'</mdn>
                    <sim>'.$sim.'</sim> 
                    <newplanID>'.$plan_id.'</newplanID>
                <E911ADDRESS>
                    <STREET1>'.$street1.'</STREET1>
                    <STREET2>'.$street2.'</STREET2>
                    <CITY>'.$city.'</CITY>
                    <STATE>'.$state.'</STATE>
                    <ZIP>'.$zip.'</ZIP>
                </E911ADDRESS>
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
