<?php

namespace App\Http\Controllers\OAuth;

use App\Models\OauthClients;
use App\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @Method: Login Oauth user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Psr\Http\Message\StreamInterface
     */
    public function login(Request $request)
    {

        $http = new \GuzzleHttp\Client();

        try {
            $port = ':8000';
//            // get an appropriate client for the auth flow
            $passportCredential = OauthClients::where([
                'password_client' => true,
                'revoked' => false
            ])->first();

            $responseToken = Request::create($passportCredential->redirect . $port . '/oauth/token', 'post', [
                'grant_type' => 'password',
                'client_id' => $passportCredential->id,
                'client_secret' => $passportCredential->secret,
                'username' => $request->email,
                'password' => $request->password,
            ]);

            return app()->handle($responseToken)->getContent();

        } catch (\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json('Invalid Request. Please enter a username or a password', $e->getCode());
            } else if ($e->getCode() == 401) {
                return response()->json('Credencials are incorrect. Please try again', $e->getCode());
            }
        }

        return response()->json('Something went wrong on server', $e->getCode());

    }

    /**
     * @Method: register Oauth user
     * @param Request $request
     * @return User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->photo = empty($request->photo) ? 'user.png' : $request->photo;
        $user->role_id = 1;
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;


    }

    /**
     * @Method: Delete token Oauth user and logout
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete;
        });

        return response()->json('Logged out successfully', 200);


    }

    public function redirecTo(){

        return view('layouts.master');//TODO
    }
}
