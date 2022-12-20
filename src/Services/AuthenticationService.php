<?php

namespace App\Services;

use \App\Services\ToastService as Toast;
use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;
use \App\Services\EmailService as Emailer;

class AuthenticationService{
		
	public static function validApiKey(){
			
		if(!isset($_GET['apikey'])) return false;
		
		if(count(Entities::findBy("user", ["apikey" => $_GET['apikey']])) > 0){
			return true;
		}
		
		return false;
		
	}
	
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
	
	public static function login($user){
		Session::save("user", $user);		
	}
	
	public static function logout(){
		Session::destroy();
	}

	public static function me(){

		if(self::loggedIn()){
			
			return Entities::findEntity("user",Session::load("user")->getId());
		}

	}
	
	/* Resets a users password to a random password */
	public static function newRandomPassword($userId){
		
		$newPassword = randomPassword();	
		$user = Entities::findEntity("user", $userId);	
		$user->setPassword($newPassword);
		$user->setPasswordResetFlag(true);
		$user->generateApiKey();
		
		Entities::persist($user);
		Entities::flush();
		
		return $newPassword;
		
	}
	
	/* Generates a reset token for a user */
	public static function newResetToken($userId){
		
		$user = Entities::findEntity("user", $userId);	
		
		$token = new \App\Models\UserToken();
		
		$token->generateToken();
		$token->setUser($user);
			
		Entities::save($token);
		
		$user->setPasswordResetFlag(true);

		Entities::save($user);
		
		return $token->getToken();
		
	}	
	
	public static function userTokenValid($token){
		
		$tokens = Entities::findBy("userToken", ["token" => $token]);
		$token = $tokens[0];
		
		if($token->isValid()){
			return true;
		}
		
	}	
	
	/* Reset using a token */
	public static function userTokenPasswordReset($token, $password){
		
		$tokens = Entities::findBy("userToken", ["token" => $token]);
		$token = $tokens[0];
		
		if($token->isValid()){
			
			$user = Entities::findEntity("user", $token->getUser());	
			$user->generateApiKey();
			$user->setPasswordResetFlag(false);
			$user->setPassword($password);
			$token->expire();
			
		}		

		Entities::save($user);
		Entities::save($token);
		
		return true;
		
	}
	
	/* Reset using a token */
	public static function userPasswordReset($userId, $password){
		
		$user = Entities::findEntity("user", $userId);
		
		if($user){
			
			$user->generateApiKey();
			$user->setPasswordResetFlag(false);
			$user->setPassword($password);
			
		}		

		Entities::save($user);
		
	}	

	/* Sends an email to the user to reset password as a new user*/
	public static function newUserEmail($userId, $token){
		
		$user = Entities::findEntity("user", $userId);	
		Emailer::sendTemplate('new_user',$user->getEmail(),'You\'ve been invited to join PRATS', array('name' => $user->getFirstName(), 'link' => _URL_ROOT . '/reset-password?token=' . $token));
		
	}	
	
	/* Sends an email to the user with a link to reset their password*/
	public static function resetPasswordEmail($userId, $token){
		
		$user = Entities::findEntity("user", $userId);	
		Emailer::sendTemplate('reset_password',$user->getEmail(),'Password Reset', array('name' => $user->getFirstName(), 'link' => _URL_ROOT . '/reset-password?token=' . $token));
		
	}	
	

	
}