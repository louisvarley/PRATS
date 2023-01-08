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
			"countries" => Entities::getEnumArray("Countries"),
			"userRoles" => Entities::getEnumArray("UserRoles"),	
			"affiliations" => Entities::createOptionSet('Affiliation', 'id',['name']),			
		);	
	} 

	public function updateEntity($id, $data){
		
		$user = Entities::findEntity($this->route_params['controller'], $id);
		
		
		$user->setUserRole(Entities::getEnum('UserRoles', $data['user']['userRole']));
		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);

		if(!empty($data['user']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['user']['image']['id']);
			$user->setImage($image);
		}

		$affiliation = Entities::findEntity("Affiliation", $data['user']['affiliation']);
		$user->setAffiliation($affiliation);

		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCountry(Entities::getEnum('Countries', $data['user']['address']['country']));
		$user->setCode($data['user']['code']);
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
		
		$user->setUserRole(Entities::getEnum('UserRoles', $data['user']['userRole']));
		
		$user->setEmail($data['user']['email']);
		$user->setFirstName($data['user']['firstName']);
		$user->setLastName($data['user']['lastName']);
		
		if(!empty($data['user']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['user']['image']['id']);
			$user->setImage($image);
		}		
		
		$affiliation = Entities::findEntity("Affiliation", $data['user']['affiliation']);
		$user->setAffiliation($affiliation);
		
		$user->setAddressLine1($data['user']['address']['addressLine1']);
		$user->setAddressLine2($data['user']['address']['addressLine2']);
		$user->setTown($data['user']['address']['town']);
		$user->setCounty($data['user']['address']['county']);
		$user->setPostcode($data['user']['address']['postcode']);		
		$user->setCode($data['user']['code']);		
		$user->setTelephone($data['user']['telephone']);
		$user->setCountry(Entities::getEnum('Countries', $data['user']['address']['country']));	

		Entities::persist($user);
		Entities::flush();

		return $user->getId();
		
	}	
	
    /**
     * Show the index page
     *
     * @return void
     */
    public function inviteAction()
    {

		if(!Authentication::loggedIn())
			header('Location: /');
		
		if($this->isPOST()){

			$data = $this->post;
		
			$users = Entities::findBy("User", ['email' => $data['user']['email']]);
			 
			if(count($users) > 0){
				
				Toast::throwWarning("Warning...", "User is already a member of PRATS.");
				
			}else{
			
				$user = new \App\Models\User();
		
				$user->setUserRole(Entities::getEnum('UserRoles', "U"));
				
				$user->setEmail($data['user']['email']);
				$user->setFirstName($data['user']['firstName']);
				$user->setLastName($data['user']['lastName']);
				
				Entities::persist($user);
				Entities::flush();
		

				$token = Authentication::generateUserToken($user->getId());
				Emailer::newUserEmail($user->getId(), $token); 
		
				Toast::throwSuccess("Success...", "User has been invited to Join PRATS.");
		
		
			}
			
		}
		
		$this->render('User/invite.html');

    }	
	
}
