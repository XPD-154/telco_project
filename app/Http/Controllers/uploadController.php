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

class uploadController extends Controller
{
    //

    public function index(Request $request)
    {
        return view("/upload"); 
    }

    public function import(Request $request)
    {
        
        if($request->has('file')){

            //////parameter//////
            $file = $request->file('file');
            $fileContents = file($file->getPathname());
            //////end of parameter//////

            //////declare and fill empty array//////
            $result = array();

            $result += [
                "status"=>"success", 
                "message"=>"CSV file imported successfully",
            ];
            //////declare and fill empty array//////

            /*structure of csv file*/
            /*1 - Custom PWGNS-Univasa - 310240465219131 - 8901240467152191310 - 1234 - 40055205 - 5678 - 79136217 - 1*/

            /////loop through the csv file////////
            foreach ($fileContents as $line) {
                
                $data = str_getcsv($line);

                /*
                //view data in array format
                echo "<pre>";
                print_r($data);
                echo "</pre>";
                
                //pass csv data into model
                Product::create([
                    'name' => $data[0],
                    'price' => $data[1],
                    // Add more fields as needed
                ]);
                */

                
                ////Fetch API call////
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => env("FRONT_END_API")
                ];

                try {

                    $request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.univasa.com/api:GEqFqMTq/inventory/get_imei?token='.env("FRONT_END_TOKEN").'&search='.$data[3], $headers);

                    $res = $client->sendAsync($request)->wait();
                    $response = json_decode($res->getBody(),true); //convert to php array

                    if($response['itemsReceived']==1){

                        /*
                        ////Edit API call////
                        $client = new Client();
                        $headers = [
                            'Content-Type' => 'application/json',
                            'Authorization' => env("FRONT_END_API")
                        ];

                        $body_array = [
                            "imei"=> $data[3],
                            "sku"=> $data[1],
                            "imsi"=> $data[2],
                            "sim"=> $data[3],
                            "pin_1"=> $data[4],
                            "pin_2"=> $data[6],
                            "puk_1"=> $data[5],
                            "puk_2"=> $data[7]
                        ];
                        
                        $body = json_encode($body_array);

                        try {

                            $request = new \GuzzleHttp\Psr7\Request('POST', 'https://api.univasa.com/api:GEqFqMTq/inventory/edit_imei?token='.env("FRONT_END_TOKEN"), $headers, $body);

                            $res = $client->sendAsync($request)->wait();
                            $response = $res->getBody();
                            $result += [
                                "check"=>$response
                            ];

                        } catch (ConnectException $e) {
                            
                            $response = $e->getMessage();
                            return ["status"=>"error", "message"=>$response];
                            
                        }
                        ////end of Edit API call////
                        */

                        ////pass into an array////
                        $result += [
                            $data[0]=>[
                                "data fetched count"=>$response['itemsReceived'],
                                "data fetched"=>$response['items']['0']['imei'],
                                "sku"=>$data[1],
                                "imel"=>$data[3],
                                "imsi"=>$data[2],
                                "sim"=>$data[3],
                                "pin1"=>$data[4],
                                "puk1"=>$data[5],
                                "pin2"=>$data[6],
                                "puk2"=>$data[7],
                                "box"=>$data[8],
                            ]
                        ];
                        ////end of pass into an array////

                    }else{
                        $result += [ 
                            "response"=>"data not found",
                        ];
                    }

                } catch (ConnectException $e) {
                    
                    $response = $e->getMessage();
                    $result += [ 
                        "response"=>$response,
                    ];
                    
                }
                ////end of Fetch API call////

            }
            /////end of loop through the csv file////////

            return $result;

        }else{

            return ["status"=>"error", "message"=>"incomplete data"];
        }

    }
}
