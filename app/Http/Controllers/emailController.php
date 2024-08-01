<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class emailController extends Controller
{
    //function to collect variable and pass it to template
    public function email(Request $request){
        
        if($request['subject']){
            $subject = $request['subject'];
        }else{
            $subject = "Welcome to UNIVASA";
        }

        if($request['subject']){
            $message = $request['message'];
        }else{
            $message = "Thank you for signing up with Univasa, We hope you enjoy your time with us. Check out some of our newest products below or click on the button below to visit us";
        }

        return view("/welcome", ['message'=>$message, 'subject'=>$subject]);

    }
}
