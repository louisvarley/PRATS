<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use \App\Services\ToastService as Toast;
use \App\Services\EntityService as Entities;
use \App\Services\AuthenticationService as Authentication;

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
			'countries' => _COUNTRIES
		);	
	} 

	public function updateEntity($id, $data){
		
		$user = Entities::findEntity($this->route_params['controller'], $id);
		
		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);

		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCountry($data['user']['address']['country']);
		$user->setCode($data['user']['code']);
		
		$user->setTelephone($data['user']['telephone']);
		
		/* Password if yours */
		if(isset($data['user']['password']) && strlen($data['user']['password']) > 5){
			
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
		
	}
	
	public function insertEntity($data){

		$user = new \App\Models\User();

		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);
		
		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCountry($data['user']['address']['country']);
		$user->setCode($data['user']['code']);		
		$user->setTelephone($data['user']['telephone']);
		
		$user->setCountry($data['user']['address']['country']);				
		
		
		Entities::persist($user);
		Entities::flush();
		
		/* Sets Initial Password and Sends Email */
		Authentication::newUserEmail($user->getId(), 
		Authentication::newRandomPassword($user->getId()));

		return $user->getId();
		
	}	
	
}
