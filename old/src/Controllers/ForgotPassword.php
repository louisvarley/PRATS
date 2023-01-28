<?php

namespace App\Controllers;

use \App\View;
use \App\Services\AuthenticationService as Authentication;
use \App\Services\ToastService as Toast;
use \App\Services\EntityService as Entities;
use \App\Services\EmailService as Emailer;

/**
 * Home controller
 *
 * 
 */
class ForgotPassword extends \App\Controller
{

	public $page_data = ["title" => "Forgot Password", "description" => ""];

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		
		if(Authentication::loggedIn())
			header('Location: /');
		
		if($this->isPOST()){

			$users = Entities::findBy("User", ['email' => $this->post['email']]);
			 
			if(count($users) > 0){
				
				$user = $users[0];
					
				$token = Authentication::generateUserToken($user->getId());
						
				Emailer::resetPasswordEmail($user->getId(), $token);
			
				Toast::throwSuccess("Success...", "If an account with your email was found, a password reset email will be sent to your email address.");
				
			}else{
			
				Toast::throwSuccess("Failure...", "No user with these details was found. Please check and try again.");
		
			}
			
			header('Location: /');
			die();
		}
		
		$this->render('ForgotPassword/index.html');

    }
}
