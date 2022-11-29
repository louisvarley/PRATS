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
	
	
	public static function newRandomPassword($userId){
		
		$newPassword = randomPassword();	
		
		$user = Entities::findEntity("user", $userId);	
		$user->setPassword($newPassword);
		$user->generateApiKey();
		
		Entities::persist($user);
		Entities::flush();
		
		return $newPassword;
		
	}
	
	public static function newRandomPasswordEmail($userId,$password){
		
		$user = Entities::findEntity("user", $userId);	
		Emailer::sendTemplate('temporary_password',$user->getEmail(),'Your New Temporary Password', array('password' => $password, 'link' => _URL_ROOT . '/login'));
		
	}
	
	public static function newUserEmail($userId,$password){
		
		$user = Entities::findEntity("user", $userId);	
		Emailer::sendTemplate('temporary_password',$user->getEmail(),'Your New Temporary Password', array('password' => $password, 'link' => _URL_ROOT . '/login'));
		
	}	
	
	
		
}