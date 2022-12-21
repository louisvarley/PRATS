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
class Login extends \App\Controller
{

	public $page_data = ["title" => "Login", "description" => ""];

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
				
				if($user->validatePassword($this->post['password'])){
				
					Authentication::login($user);
					
					if($user->getPasswordResetFlag()){
						header('Location: /reset-password');
						die();
						
					}elseif(isset($this->get['redirect'])){
						header('Location:' . urldecode($this->get['redirect']));
						die();
					}else{
						header('Location: /');
						die();
					}
				
				}else{
			
					Toast::throwError("Error...", "Your login details were incorrect");
				
				}
				
			}else{
			
				Toast::throwError("Error...", "No User with your details was found");
			}
		
		}

		$this->render('Login/index.html');

    }
}
