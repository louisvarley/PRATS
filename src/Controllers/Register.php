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
class Register extends \App\Controller
{

	public $page_data = ["title" => "Register", "description" => ""];

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

			$user = Entities::findBy("User", ['email' => $this->post['email']]);
			 
			if(count($user) > 0 && $user[0]->validatePassword($this->post['password'])){
				
				Authentication::login($user[0]);
				if(isset($this->get['redirect'])){
					header('Location:' . urldecode($this->get['redirect']));
					die();
				}else{
					header('Location: /');
				}
				
				
			}else{
			
				Toast::throwError("Error...", "Your login details were incorrect or not found");
			
			}
		}
		

		$this->render('Register/index.html');

    }
}
