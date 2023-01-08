<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use \App\Services\EntityService as Entities;
use \App\Services\EmailService as Emailer;

/**
 * Litter controller
 *
 * 
 */
 

class Litter extends \App\Controllers\ManagerController
{
	
	public $page_data = ["title" => "Litters", "description" => "Litters registered on PRATS"];		

	public function getEntity($id = 0){
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id),
			
			"males" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'm']]),		
			"females" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'f']]),				
			"breeders" => Entities::createOptionSet('user', 'id',['firstName','lastName']),			
		);	
	} 

	public function updateEntity($id, $data){
		
		$litter = Entities::findEntity($this->route_params['controller'], $id);
		
		$dam = Entities::findEntity("rat", $data['litter']['dam']);
		$sire = Entities::findEntity("rat", $data['litter']['sire']);
		$breeder = Entities::findEntity("user", $data['litter']['breeder']);
		
		$litter->setBirthDate(date_create_from_format('d/m/Y', $data['litter']['birthDate']));
		$litter->setDam($dam);
		$litter->setSire($sire);
		$litter->setBreeder($breeder);

		Entities::persist($litter);
		
		if(isset($data['note']) &&  $data['note'] != ""){

			$note = new \App\Models\PurchaseNote();
			$note->setPurchase($purchase);
			$note->setNote($data['note']);
			$note->setDate(new \DateTime('now'));
			$note->setUser();
			Entities::persist($note);

		}	


		$litter->resetCode();
		
		Entities::flush();
		
	}
	
	public function insertEntity($data){

		$litter = new \App\Models\litter();
		
		$dam = Entities::findEntity("rat", $data['litter']['dam']);
		$sire = Entities::findEntity("rat", $data['litter']['sire']);
		$breeder = Entities::findEntity("user", $data['litter']['breeder']);
		
		$litter->setBirthDate(date_create_from_format('d/m/Y', $data['litter']['birthDate']));
		$litter->setDam($dam);
		$litter->setSire($sire);
		$litter->setBreeder($breeder);
		
		
		$litter->resetCode();
		
		Entities::persist($litter);
		Entities::flush();
		
		return $litter->getId();
		
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
			array("entities" => Entities::findAll($this->route_params['controller'], $orderBy, $order))
			
		);

	}	

	
}
