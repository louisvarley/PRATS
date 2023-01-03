<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use \App\Services\EntityService as Entities;
use \App\Services\EmailService as Emailer;

/**
 * Home controller
 *
 * 
 */
 

class Rat extends \App\Controllers\ManagerController
{
	
	public $page_data = ["title" => "Rats", "description" => "Gambian Pouched Rats, Registered on PRATS"];		

	public function getEntity($id = 0){
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id),
			"ratStatus" => _RAT_STATUS,
			"males" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'male']]),		
			"females" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'female']]),			
			"users" => Entities::createOptionSet('User', 'id',['firstName','lastName']),			
			"genders" => _GENDERS,
			"countries" => _COUNTRIES,
			"litters" => Entities::createOptionSet('litter', 'id', ['code']),	
		);	
	} 

	public function updateEntity($id, $data){
		
		$rat = Entities::findEntity($this->route_params['controller'], $id);
		$litter = Entities::findEntity("litter", $data['rat']['litter']);
		$owner = Entities::findEntity("user", $data['rat']['owner']);
		
		if(!empty($data['rat']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['rat']['image']['id']);
			$rat->setImage($image);
		}
		
		$rat->setName($data['rat']['name']);
		$rat->setStatus($data['rat']['status']);	
		$rat->setGender($data['rat']['gender']);
		
		$rat->setLitter($litter);
		$rat->setOwner($owner);

		if(empty($data['rat']['litter'])){
			
			$rat->setDoe(empty($data['rat']['doe']) ? null : Entities::findEntity("rat", $data['rat']['doe']));
			$rat->setBuck(empty($data['rat']['buck']) ? null : Entities::findEntity("rat", $data['rat']['buck']));
			$rat->setCountry(empty($data['rat']['country']) ? null : $data['rat']['country']);
			$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));		
		}else{
			$rat->setDoe(null);
			$rat->setBuck(null);
			$rat->setCountry(null);
			$rat->setBirthDate(null);
		}
		
		Entities::persist($rat);
		
		if(isset($data['note']) &&  $data['note'] != ""){

			$note = new \App\Models\PurchaseNote();
			$note->setPurchase($purchase);
			$note->setNote($data['note']);
			$note->setDate(new \DateTime('now'));
			$note->setUser();
			Entities::persist($note);

		}	

		$rat->resetCode();		
		
		Entities::flush();
		
	}
	
	public function insertEntity($data){

		$rat = new \App\Models\rat();
		
		$litter = Entities::findEntity("litter", $data['rat']['litter']);
		$owner = Entities::findEntity("user", $data['rat']['owner']);
		
		if(!empty($data['rat']['image']['id'])){
			
			$image = Entities::findEntity("blob", $data['rat']['image']['id']);
			$rat->setImage($image);
		}
				
		$rat->setName($data['rat']['name']);
		$rat->setStatus($data['rat']['status']);
		$rat->setGender($data['rat']['gender']);
		$rat->setLitter($litter);
		$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));

		if(empty($data['rat']['litter'])){
			$rat->setDoe(empty($data['rat']['doe']) ? null : Entities::findEntity("rat", $data['rat']['doe']));
			$rat->setBuck(empty($data['rat']['buck']) ? null : Entities::findEntity("rat", $data['rat']['buck']));
			$rat->setCountry(empty($data['rat']['country']) ? null : $data['rat']['country']);
			$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));		
		}else{
			$rat->setDoe(null);
			$rat->setBuck(null);
			$rat->setCountry(null);
			$rat->setBirthDate(null);
		}
				
		$rat->resetCode();
		
		Entities::persist($rat);
		Entities::flush();
		
		return $rat->getId();
		
	}	
	
    /**
     * When the list action is called
     *
     * @return void
     */
	public function listAction(){

		$orderBy = isset($_GET['orderby']) ? $_GET['orderby'] : "id";
		$order = isset($_GET['orderby']) ? $_GET['order'] : "desc";		
		
		$this->render($this->route_params['controller'] . '/list.html', 
			array("entities" => Entities::findAll($this->route_params['controller'], $orderBy, $order), 'ratStatuses' => _RAT_STATUS)
			
		);

	}	

	
}
