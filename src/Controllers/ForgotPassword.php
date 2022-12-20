<?php

namespace App\Controllers;

use \App\View;
use \App\Services\AuthenticationService as Authentication;
use \App\Services\ToastService as Toast;
use \App\Services\EntityService as Entities;

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
					
				$token = Authentication::newResetToken($user->getId());
						
				Authentication::resetPasswordEmail($user->getId(), $token);
			
				Toast::throwSuccess("Success...", "If an account with your email was found, a password reset email will be sent to your email address.");

				
			}else{
			
				Toast::throwSuccess("Success...", "If an account with your email was found, a password reset email will be sent to your email address.");
			
			}
			
			header('Location: /');
		}
		
		$this->render('ForgotPassword/index.html');

    }
}
