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
class ResetPassword extends \App\Controller
{

	public $page_data = ["title" => "Reset Password", "description" => ""];

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		
		/* Execute Password Reset */
		if($this->isPOST()){
			
			/* If Logged In - Reset Without Token */
			if(Authentication::loggedIn()){
		
				Authentication::userPasswordReset($this->post['reset-password']['password']);
				Toast::throwSuccess("Success...", "Your password has been changed");
			
			/* If Logged Out - Reset Using Token */
			}else{
				
				if(Authentication::userTokenPasswordReset($_GET['token'], $this->post['reset-password']['password'])){
					
					Toast::throwSuccess("Success...", "Your password has been successfully reset.");
					
				}else{
					
					Toast::throwError("Failure...", "Your password reset link was either invalid or has expired.");
				}
				
			}

			header('Location: /');
			
		} else {
			
			/* If Logged In*/
			if(Authentication::loggedIn()){
				
				header('Location: /');

			}
		
			/* Otherwise has a token */
			if(isset($_GET['token'])){

				if(Authentication::userTokenValid($_GET['token'])){
					
					$this->render('ResetPassword/index.html');
					
				}else{
					
					Toast::throwError("Failure...", "The provided token has either expired or was not found...");
					header('Location: /');
					
				}
				
			}
			
		}
		
    }
	
}
