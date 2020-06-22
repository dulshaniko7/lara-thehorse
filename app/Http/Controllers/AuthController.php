<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	public function login(Request $request) {

		$http = new \GuzzleHttp\Client;

		try {
			$response = $http->post('http://lara-thehorse.com/oauth/token', [
					'form_params' => [
							'grant_type' => 'password',
							'client_id' => 2,
							'client_secret' => '0jI9uJB2tCsECPHlMIiVvrcO8d2Fvh6NdBrZzIML',
							'username' => $request->username,
							'password' => $request->password,
					]
			]);
			return $response->getBody();
	    } catch (BadResponseException $e){
			return response()->json('cant login', $e->getCode());
		}

    }

    public function register(Request $request){

	    $request->validate([
			    'name' => ['required', 'string', 'max:255'],
			    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			    'password' => ['required', 'string', 'min:8'],
	    ]);
	    return User::create([
			    'name' => $request->name,
			    'email' => $request->email,
			    'password' => Hash::make($request->password)
	    ]);
    }

    public function logout(){

		auth()->user()->tokens->each(function ($token, $key){
			$token->delete();
		});

		return response()->json('Logged out successfully', 200);
    }
}
