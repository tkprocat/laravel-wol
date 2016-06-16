<?php

namespace LaravelWOL\Api\Controllers;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use LaravelWOL\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends BaseController
{
    use ResetsPasswords;

    public function me()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function validateToken() 
    {
        // Our routes file should have already authenticated this token, so we just return success here
        return API::response()->array(['status' => 'success'])->statusCode(200);
    }

    public function register(Request $request)
    {
        $newPassword = str_random(16);

        $newUser = User::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => Hash::make($newPassword)
        ]);

        $title = 'A new account has been created for you.';

        Mail::send('auth.emails.new_user', ['user' => $newUser, 'password' => $newPassword], function ($message) use ($request, $title)
        {
            $message->from(env('MAIL_SENDER_EMAIL'),env('MAIL_SENDER_NAME'));
            $message->to($request->get('email'));
            $message->subject($title);
        });
    }
}