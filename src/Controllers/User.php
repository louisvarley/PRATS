<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use \App\Services\ToastService as Toast;
use \App\Services\EntityService as Entities;
use \App\Services\AuthenticationService as Authentication;
use \App\Services\EmailService as Emailer;

/**
 * Home controller
 *
 * 
 */
 

class User extends \App\Controllers\ManagerController
{
	
	public $page_data = ["title" => "Your Account", "description" => "Change and amend your account details"];	
	
	public function getEntity($id = 0){
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id),
			'countries' => _COUNTRIES,
			"booleans" => _BOOLS,
			"userRoles" => Entities::createOptionSet('UserRole', 'id','name'),			
		);	
	} 

	public function updateEntity($id, $data){
		
		$user = Entities::findEntity($this->route_params['controller'], $id);
		
		
		$userRole = Entities::findEntity("UserRole", $data['user']['userRole']);
		
		$user->setUserRole($userRole);
		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);

		if(!empty($data['user']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['user']['image']['id']);
			$user->setImage($image);
		}

		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCountry($data['user']['address']['country']);
		$user->setCode($data['user']['code']);
		$user->setPasswordResetFlag($data['user']['passwordResetFlag']);
		$user->setTelephone($data['user']['telephone']);
		
		/* Password if yours */
		if(!empty($data['user']['password-confirm']) && !empty($data['user']['password']) && strlen($data['user']['password']) > 5){
			
			if($data['user']['password'] != $data['user']['password_confirm']){
				toast::throwError("Error...", "Password Mismatch");
				return;
			}
			
			$user->setPassword($data['user']['password']);
			
		}
		
		/* If As Admin
		
		if(isset($data['user']['newPassword'])){
		
			$newPassword = randomPassword();	
			$user->setPassword($newPassword);
			$user->generateApiKey();
		
		}
		
		*/

		Entities::persist($user);
		Entities::flush();
		
		Authentication::refresh();
		
	}
	
	public function insertEntity($data){

		$user = new \App\Models\User();

		$userRole = Entities::findEntity("UserRole", $data['user']['userRole']);
		
		$user->setUserRole($userRole);
		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);
		
		if(!empty($data['user']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['user']['image']['id']);
			$user->setImage($image);
		}		
		
		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCountry($data['user']['address']['country']);
		$user->setCode($data['user']['code']);		
		$user->setTelephone($data['user']['telephone']);
		$user->setCountry($data['user']['address']['country']);		

		/* Sets a Random Password */
		$user->generateTemporaryPassword();		
				
		Entities::persist($user);
		Entities::flush();

		/* Email user welcome email */
		if($data['user']['welcomeEmail']){
			$token = Authentication::generateUserToken($user->getId());
			Emailer::newUserEmail($user->getId(), $token); 
			
		}				
		
		
		return $user->getId();
		
	}	
	
}
