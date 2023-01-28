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
 

class Affiliation extends \App\Controllers\ManagerController
{
	
	public $page_data = ["title" => "Your Account", "description" => "Change and amend your account details"];	
	
	public function getEntity($id = 0){
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id)
		);	
	} 

	public function updateEntity($id, $data){
		
		$affiliation = Entities::findEntity($this->route_params['controller'], $id);
		
		$affiliation->setColor($data['affiliation']['color']);
		$affiliation->setName($data['affiliation']['name']);
		$affiliation->setCode($data['affiliation']['code']);		

		if(!empty($data['affiliation']['logo']['id'])){
			
			$image = Entities::findEntity("blob", $data['affiliation']['logo']['id']);
			$affiliation->setLogo($image);
		}

		Entities::persist($affiliation);
		Entities::flush();		
		
	}
	
	public function insertEntity($data){

		$affiliation = new \App\Models\Affiliation();

		$affiliation->setColor($data['affiliation']['color']);
		$affiliation->setName($data['affiliation']['name']);
		$affiliation->setCode($data['affiliation']['code']);	
		
		if(!empty($data['affiliation']['logo']['id'])){
			
			$image = Entities::findEntity("blob", $data['affiliation']['logo']['id']);
			$affiliation->setLogo($image);
		}
		
		Entities::persist($affiliation);
		Entities::flush();
			
		return $affiliation->getId();
		
	}	
	
}
