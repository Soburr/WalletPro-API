<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
       $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
           'email' => 'required|email',
           'password' => 'required|min:6',
           'confirm_password' => 'required|same:password'
       ]);

       if ($validator->fails()) {
           return response()->json([
              'status' => 0,
              "message" => 'Invalid input, check and retry!'
           ], 504);
       }

       $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
       ]);

       $response = [];
       $response['token'] = $user->createToken('MyApp')->accessToken;
       $response['name'] = $user->name;
       $response['email'] = $user->email;

       return response()->json([
        'status' => 1,
        'message' => 'Registration Successful!',
        'data' => $response
     ], 200);
    }

    public function login (Request $request) {

       if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();

        $response = [];
        $response['token'] = $user->createToken("MyApp")->accessToken;
        $response['name'] = $user->name;
        $response['email'] = $user->email;

           return response()->json([
              'status' => 1,
              'message' => 'Login Successful!',
              'data' => $response
           ], 200);
       }

       return response()->json([
        'status' => 0,
        'message' => 'Invalid Credentials!',
     ], 504);


    }
}
