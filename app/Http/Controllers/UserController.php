<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Traits\Utillity;
use Auth;
use Validator;
use JWTAuth;

class UserController extends Controller
{
    use Utillity;

    /**
     * Function for login
     * * @param {*} email 
     * * @param {*} password 
     * @returns 
    */
    public function login(Request $request) {

        try {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return $response = $this->getReponse(false, 400, 'Validation Error',$validator->errors());
            }
            // to check the email and password
            if (Auth::attempt($credentials)){                
                if (!$token = JWTAuth::attempt($credentials)){
                    return $response = $this->getReponse(false, 500, 'could_not_create_token'); 
                } 
                $user = Auth::user();
                $user['token'] = $token;
                $response = $this->getReponse(true, 200, 'Successfully Logged In',$user);
            }else{
                $response = $this->getReponse(false, 400, 'Entered Email or Password is wrong');
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
    }

    /**
     * Function for register
     * * @param {*} first_name 
     * * @param {*} last_name 
     * * @param {*} email 
     * * @param {*} password 
     * * @param {*} password_confirmation
     * @returns 
    */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return $response = $this->getReponse(false, 400, 'Validation Error',$validator->errors());
            }

            $user = User::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            $user['token'] = $token;

            $response = $this->getReponse(true, 200, 'Successfully Registered', $user);
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }
        
        return $response;
    }

    /**
     * Function to check email is already registered or not
     * * @param {*} email 
     * @returns 
    */
    public function checkEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
            ]);

            if ($validator->fails()) {
                return $response = $this->getReponse(false, 400, 'Validation Error',$validator->errors());
            }

            $email = $request->input('email');

            if(User::where('email', $email)->first()){
                $response = $this->getReponse(false, 400, 'User Already Registered'); 
            } else {
                $response = $this->getReponse(true, 200, 'User Not Registered');  
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }
        
        return $response;
    }

    /**
     * Function to return logined user using authentication
     * * @param {*} token 
     * @returns 
    */
    public function getAuthenticatedUser()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                $response = $this->getReponse(true, 200, 'User Success', $user);  
            }
        } catch (\Exception $e) {
            $response = $this->getReponse(false, 500, $e->getMessage());  
        }

        return $response;
    }
}
