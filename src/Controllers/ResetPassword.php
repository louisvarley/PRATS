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
		

		/* Doing password reset */
		if($this->isPOST()){
			
			if(Authentication::loggedIn()){
			
				$user = Authentication::me();			
				Authentication::userPasswordReset($user->getId(), $this->post['reset-password']['password']);
				Toast::throwSuccess("Success...", "Your password has been changed");
			
			}elseif(Authentication::userTokenPasswordReset($_GET['token'], $this->post['reset-password']['password'])){
				
				Toast::throwSuccess("Success...", "Your password has been reset");
				
			}else{
				Toast::throwError("Failure...", "There was a problem reseting your password");
			}
			
			header('Location: /');
			
		}else{
			
			/* Logged In and Has Reset Flag Set */
			if(Authentication::loggedIn()){
				
				$user = Authentication::me();
				
				if($user->getPasswordResetFlag()){
					
					Toast::throwWarning("Warning...", "You are required to change your password");
					$this->render('ResetPassword/index.html');
					
				}else{
					header('Location: /');
				}

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
	
	/**
     * Show the index page
     *
     * @return void
     */
    public function resetAction()
    {
		
		var_dump($this->route_params['token']);
		die();
		
	}
}
