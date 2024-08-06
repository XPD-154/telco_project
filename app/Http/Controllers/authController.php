<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\User;

class authController extends Controller
{

    //separate these functions from auth
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'newPassword']]);
    }

    //method for customer register
    public function register(Request $request){

        //collect field values and validate
        $credentials = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required'
        ]);

        //select the password value and encryth
        $credentials['password'] = bcrypt($credentials['password']);

        //create a user in  the User model
        $user = User::create($credentials);

        $token = Auth::login($user);

        return [
            "status"=>"success", 
            "message"=>"user registration successful",
            "user"=>$user,
            "token"=>$token
        ];
    }
    
    //method the login of customer
    public function login(Request $request)
    {
        
        //collect field values and validate
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        
        //verify the user and produce a token
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'login failed'
            ], 401);
        }

        return [
            "status"=>"success", 
            "message"=>"login successful",
            "token"=>$token
        ];
    }

    //method for setting a new password
    public function newPassword(Request $request){
        
        //collect field values and validate
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ]);
        
        //validate the accuracy of passwords
        if($credentials['password']==$credentials['confirm_password']){

            //select the password value and encryth
            $credentials['password'] = bcrypt($credentials['password']);

            //find customer in the database
            $user_email = $credentials['email'];

            $password_change_request = User::where('email', '=', $user_email)->first();

            //verify if the customer exists in the system
            if($password_change_request){

                //change the password
                $password_change_request->password = $credentials['password'];

                $result = $password_change_request->save();

                if($result){
                    return ["status"=>"success", "message"=>"password changed successfully"];
                }else{
                    return ["status"=>"error", "message"=>"failed password update"];
                }

            }else{
                return ["status"=>"error", "message"=>"user doesn't exist"];
            }

        }else{
            return ["status"=>"error", "message"=>"incorrect password parameters"];
        }
    }

    //fetch information associated with user
    public function me()
    {
        return response()->json(auth()->user());
    }

    //logout user out of system
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    //Refresh a token
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
