<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Giunashvili\XMLParser\Parse;

class linkedlnLoginController extends Controller
{
    
    public function redirectToLinkedln(){

        //$url="https://ucall.ng/mvno/";
        $url='https://www.linkedin.com/oauth/v2/authorization?client_id='.env("LINKEDIN_CLIENT_ID").'&response_type=code&redirect_uri='.env("LINKEDIN_CALLBACK_URL").'&state='.env("LINKEDIN_STATE").'&scope=email profile openid';
        
        return Redirect::to($url);
    }

    //Linkedln Auth function
    public function linkedlnSubmit(Request $request){
        
        //declare a result array
        $result = array();

        ///////check for request code and state////////
        if($request['code'] && $request['state']){

            $code = $request['code'];
            $state = $request['state'];

            /////////verify state////////////
            if($state!=env("LINKEDIN_STATE")){

                $result += [
                    "status"=>"error", 
                    "message"=>"state mis-match",
                ];

                return $result;

            }
            /////////end of verify state////////////

            ////Auth code API call////
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.linkedin.com/oauth/v2/accessToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  'code='.$code.'&client_id='.env("LINKEDIN_CLIENT_ID").'&client_secret='.env("LINKEDIN_CLIENT_SECRET").'&redirect_uri='.env("LINKEDIN_CALLBACK_URL").'&grant_type=authorization_code',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $tran = json_decode($response, true);

            if($tran['access_token']){

                $access_token = strval('Bearer '.$tran['access_token']);

                $result += [
                    "access_token"=>$access_token
                ];

                //////user info API/////
                $client = new Client();
                $headers = [
                    'Authorization' => $access_token
                ];

                try {

                    $request = new \GuzzleHttp\Psr7\Request('GET', 'https://api.linkedin.com/v2/userinfo', $headers);

                    $res = $client->sendAsync($request)->wait();
                    $response = json_decode($res->getBody(),true); //convert to php array

                    if($response['email']){

                        $linkdln_email =strval($response['email']);
                        $linkdln_name =strval($response['name']);

                        ////login////////
                        $user = User::where('email', $linkdln_email)->first();
                        if(!$user)
                        {
                            $user = User::create(['name' => $linkdln_name, 'email' => $linkdln_email, 'provider' => 'linkedin', 'password' => \Hash::make(rand(100000,999999))]);
                        }

                        Auth::login($user);

                        $result += [
                            "status"=>"success", 
                            "message"=>"user login successful",
                            "name"=>$response['name'],
                            "email"=>$response['email']
                        ];

                        return $result;
                        ////login////////

                    }else{

                        $result += [
                            "status"=>"error", 
                            "message"=>$response,
                        ];

                        return $result;

                    }

                } catch (ClientException $e) {
                    
                    $response = $e->getMessage();
                    $result += [
                        "status"=>"error", 
                        "message"=>$response,
                    ];

                    return $result;
                    
                }
                //////end of user info API///////

            }else{

                $result += [
                    "status"=>"error", 
                    "message"=>$tran['error'],
                    "error"=>$tran['error_description'],
                ];

                return $result;
            }
            
            ////end of Auth code API////
        }
        ///////end of check for request code and state////////

        
        $result += [
            "status"=>"error", 
            "message"=>"parameters missing",
        ];

        return $result;

    }
    
}
