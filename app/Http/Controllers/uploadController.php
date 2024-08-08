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

        /////loop through the csv file////////
        foreach ($fileContents as $line) {
            
            $data = str_getcsv($line);

            /*
            //view data in array format
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            */

            /*
            //pass csv data into model
            Product::create([
                'name' => $data[0],
                'price' => $data[1],
                // Add more fields as needed
            ]);
            */

            /*
            ////API call////
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => env("FRONT_END_API")
            ];

            $body = '{
                "imei": "89012404671521999999999",
                "sku": "string",
                "imsi": "3102404652191311111111",
                "sim": "89012404671521999999999",
                "pin_1": "123333334",
                "pin_2": "123333334",
                "puk_1": "123333334",
                "puk_2": "123333334"
            }';

            try {

                $request = new \GuzzleHttp\Psr7\Request('POST', 'https://api.univasa.com/api:GEqFqMTq/inventory/add_msisdn?token='.env("FRONT_END_TOKEN"), $headers, $body);

                $res = $client->sendAsync($request)->wait();
                $response = $res->getBody();
                $output = Parse :: xmlAsArray($response);
                return $output;

            } catch (ConnectException $e) {
                
                $response = $e->getMessage();
                return ["status"=>"error", "message"=>$response];
                
            }
            ////end of API call////
            */

            $result += [
                $data[0]=>[
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

        }
        /////end of loop through the csv file////////

        return $result;
        
    }
}
