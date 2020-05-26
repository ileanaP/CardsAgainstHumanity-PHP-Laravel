<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Http\Controllers\ApiController; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends ApiController
{
    private $successStatus = 200;

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    {

        $input = $request->all(); 
        
        $input['password'] = bcrypt($input['password']); 
        
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'verification_token' => User::generateVerificationCode(),
            'api_token' => str_random(80)
        ]);
        
        $user->token =  $user->createToken('MyApp')->accessToken; 

        return $this->showOne($user, $this->successStatus); 
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $user['token'] =  $user->createToken('MyApp')->accessToken; 
            return $this->showOne($user, $this->successStatus); 
        } 
        else { 
            return $this->errorResponse('Unauthorised', 401); 
        }
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}
