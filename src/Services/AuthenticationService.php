<?php

namespace App\Services;

use \App\Services\ToastService as Toast;
use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;
use \App\Services\EmailService as Emailer;

class AuthenticationService{
		
	/* Validate an API Key */	
	public static function validApiKey(){
			
		if(!isset($_GET['apikey'])) return false;
		
		if(count(Entities::findBy("user", ["apikey" => $_GET['apikey']])) > 0){
			return true;
		}
		
		return false;
		
	}
	
	/* Check if logged in, forces log out if time expired */
	public static function loggedIn(){
		
		
		if(false == Session::isset("user"))
			return false;
			
		if(Session::isset("user") && time() - Session::load("activity") > 1800){
			self::logout();
			Toast::throwError("Logged Out", "Logged Out due to idle activity");
		}
		
		if(Session::isset("user")){
			return true;
		}
		
	}
	
	/* Saves given user to Session */
	public static function login($user){
		Session::save("user", $user);		
	}
	
	public static function refresh(){
		if(self::loggedIn()){
			Session::save("user", self::me());	
		}		
	}
	
	/* Destroys Session */
	public static function logout(){
		Session::destroy();
	}

	/* Gets the current logged in user */
	public static function me(){

		if(self::loggedIn()){
			
			return Entities::findEntity("user",Session::load("user")->getId());
		}

	}
	
	/* Generates a user token for a user */
	public static function generateUserToken($userId){
		
		$user = Entities::findEntity("user", $userId);	
		
		$token = new \App\Models\UserToken();
		$token->generateToken();
		$token->setUser($user);
			
		Entities::save($token);
		
		return $token->getToken();
		
	}	
	
	public static function userTokenValid($token){
		
		$tokens = Entities::findBy("userToken", ["token" => $token]);
		$token = $tokens[0];
		
		if($token->isValid()){
			return true;
		}
		
	}	
	
	/* Change password of a user using a token */
	public static function userTokenPasswordReset($token, $password){
		
		$tokens = Entities::findBy("userToken", ["token" => $token]);
		$token = $tokens[0];
		
		if($token && $token->isValid()){
			
			$user = Entities::findEntity("user", $token->getUser());	
			$user->generateApiKey();
			$user->setPasswordResetFlag(false);
			$user->setPassword($password);
			
			/* Password is reset, make this user the session user */
			self::login($user);
			
			$token->expire();
			
			Entities::save($user);
			Entities::save($token);
		
			return true;
			
		}else{

			return false;
		}			

		
		
	}
	
	/* Change password of the authenticated user */
	public static function userPasswordReset($password){
		
		$user = Authentication::me();
		
		if($user){
			
			$user->generateApiKey();
			$user->setPasswordResetFlag(false);
			$user->setPassword($password);
			
		}		

		Entities::save($user);
		
	}	


	

	
}