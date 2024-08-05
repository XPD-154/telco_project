<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
//use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;


class usageController extends Controller
{
    //
    public function usage(Request $request)
    {   
        if($request->has('mdn')) {

            $mdn = $request['mdn'];

            /*
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oss.vcarecorporation.com:22712/api/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?>
            <wholeSaleApi xmlns="http://www.oss.vcarecorporation.com/oss" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <session>
                    <clec>
                        <id>1004</id>
                        <agentUser>
                            <username>Univasa LLC</username>
                            <token>03wFjL8MnE0Lpyz1</token>
                            <pin>Univasa LLC5630</pin>
                        </agentUser>
                    </clec>
                    </session>
                    <request type="QueryUsage">
                    <esn></esn>
                    <mdn>4436533896</mdn>
                    </request>
            </wholeSaleApi>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            if($response){
                echo $response;
            }else{
                echo "no response";
            }
            */
            
            
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
                //echo $res->getBody();

                $xmlObject = simplexml_load_string($res);
                $json = json_encode($xmlObject);
                $phpArray = json_decode($json, true); 

                return $phpArray;

                /*
                $request = $client->request('POST', 'https://oss.vcarecorporation.com:22712/api/', $headers, $body);

                $status = $request->getStatusCode();
                $header = $request->getHeader('content-type')[0];
                $body = $request->getBody();

                return ["status"=>"success", "status code"=>$status, "header"=>$header, "data"=>$body];
                */

            } catch (ConnectException $e) {
                
                $response = $e->getMessage();
                return ["status"=>"error", "message"=>$response];
                
            }

        }else{

            return ["status"=>"error", "message"=>"incomplete data"];
        }
 
    }
}
