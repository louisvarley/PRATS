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

			$users = Entities::findBy("User", ['email' => $this->post['register']['email']]);

			if(count($users) == 0){

				$user = new \App\Models\User();
				
				$user->setEmail($this->post['register']['email']);
				$user->setFirstName($this->post['register']['firstName']);
				$user->setLastName($this->post['register']['lastName']);
				$user->setPassword($this->post['register']['lastName']);
				
				/* Set Users Role to USER */
				$user->setUserRole(Entities::findEntity("UserRole", _USER_ROLES['USER']['id']));
				
				//Entities::persist($user);
				//Entities::flush();
				
				/* Log the User In */
				//Authentication::login($user);
				
				Toast::throwSuccess("Welcome", "Hi $user->getFirstName() welcome to PRATS");

			}else{
				
				Toast::throwError("Error", "Your email address is already in use! please <a href='/forgot-password'></a> if you are not sure of your password!");
				
			}
			
		}
		
		$this->render('Register/index.html');

    }
}
