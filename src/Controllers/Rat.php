<?php

namespace App\Controllers;

use \App\View;
use \App\Classes;
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
			"ratStatus" => Entities::getEnumArray("RatStatus"),
			"males" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'm']]),		
			"females" => Entities::createOptionSet('rat', 'id', ['name','gender'], ['gender' => ['comparison' => '=', 'match' => 'f']]),			
			"users" => Entities::createOptionSet('User', 'id',['firstName','lastName']),			
			"genders" => Entities::getEnumArray("Genders"),
			"countries" => Entities::getEnumArray("Countries"),
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
		$rat->setStatus(Entities::getEnum('RatStatus', $data['rat']['status']));	
		$rat->setGender(Entities::getEnum('Genders', $data['rat']['gender']));
		
		$rat->setLitter($litter);
		$rat->setOwner($owner);

		if(empty($data['rat']['litter'])){
			
			$rat->setDam(empty($data['rat']['dam']) ? null : Entities::findEntity("rat", $data['rat']['dam']));
			$rat->setSire(empty($data['rat']['sire']) ? null : Entities::findEntity("rat", $data['rat']['sire']));			
			$rat->setCountry(empty($data['rat']['country']) ? null : Entities::getEnum('Countries', $data['rat']['country']));		
			$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));		
		}else{
			$rat->setDam(null);
			$rat->setSire(null);
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

		$rat->setStatus(Entities::getEnum('RatStatus', $data['rat']['status']));	
		$rat->setGender(Entities::getEnum('Genders', $data['rat']['gender']));
		$rat->setOwner($owner);
		
		$rat->setLitter($litter);
		$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));

		if(empty($data['rat']['litter'])){
			
			$rat->setDam(empty($data['rat']['dam']) ? null : Entities::findEntity("rat", $data['rat']['dam']));
			$rat->setSire(empty($data['rat']['sire']) ? null : Entities::findEntity("rat", $data['rat']['sire']));			
			$rat->setCountry(empty($data['rat']['country']) ? null : Entities::getEnum('Countries', $data['rat']['country']));		
			$rat->setBirthDate(date_create_from_format('d/m/Y', $data['rat']['birthDate']));		
		}else{
			$rat->setDam(null);
			$rat->setSire(null);
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
			array("entities" => Entities::findAll($this->route_params['controller'], $orderBy, $order), 'ratStatuses' => Entities::getEnumArray("RatStatus"))
			
		);

	}	
	
	public function getTreeWithChildren($id, $familyTree){
		
		$rat = Entities::findEntity("rat", $id);
		
		
		if(!isset($familyTree[$rat->getId()])){
		
		/* Add this rat */
			$familyTree[$rat->getId()] = new \App\Classes\FamilyNode($rat->getName(), $rat->getGender()->value);
		
		}

		/* Add any Mates */
	
		foreach($rat->getLitters() as $litter){
			
			if($rat->getGender()->value == "M"){
		
				$familyTree[$litter->getDam()->getId()] = new \App\Classes\FamilyNode($litter->getDam()->getName(), $litter->getDam()->getGender()->value);
				$familyTree[$rat->getId()]->mate = ($litter->getDam()->getId());
				$familyTree[$litter->getDam()->getId()]->mate = ($rat->getId());
			}
			
			if($rat->getGender()->value == "F"){		

				$familyTree[$litter->getSire()->getId()] = new \App\Classes\FamilyNode($litter->getSire()->getName(), $litter->getSire()->getGender()->value);
				$familyTree[$rat->getId()]->mate = ($litter->getSire()->getId());
				$familyTree[$litter->getSire()->getId()]->mate = ($rat->getId());
			}
			
			/* Add Children */
			
			foreach($litter->getRats() as $childRat){
				
				$familyTree[$childRat->getId()] = new \App\Classes\FamilyNode($childRat->getName(), $childRat->getGender()->value);
				$familyTree[$childRat->getId()]->father = ($litter->getSire()->getId());
				$familyTree[$childRat->getId()]->mother = ($litter->getDam()->getId());	
				
				
				if($childRat->getLitters()){
					
					$familyTree = $this->getTreeWithChildren($childRat->getId(), $familyTree);
				};
				
			}
			
		}		

		
		return $familyTree;
		
	}
	
	public function treeAction(){
		
		$rat = Entities::findEntity($this->route_params['controller'], $this->route_params['id']);

		$familyTree = [];

		$familyTree = $this->getTreeWithChildren($this->route_params['id'], $familyTree, );
		
		$treeJson = "setupDiagram(myDiagram, [";
		
		foreach($familyTree as $key => $node){
			
			$treeJson .= "\r\n";
			$code = $node->getGender() == 'M' ? 'ux' : 'vir';
			//$treeJson .= '{ key: ' . $key . ', n: "' . $node->getName() . '", s: "' . $node->getGender() .' ", m: ' . $node->getMother() . ' , f: ' . $node->getFather() . ', ' . $code . ': ' . $node->getMate() . ', a: ["C", "F", "K"] },';
		
			$treeJson .= "
			{ 
			key:$key,
			n: \"$node->name\", 
			s: \"$node->gender\", 
			";	
			if($node->mate){
			$treeJson .= "
			$code:$node->mate,		
			";
			}
			if($node->mother){
			$treeJson .= "			
			m:$node->mother, 
			";
			}		
			if($node->father){			
			$treeJson .= "			
			f:$node->father, 
			";
			}		
			$treeJson .= "			
			},
			";

		}
		
		$treeJson .= "\r\n";
		$treeJson .= '],';
		$treeJson .= $rat->getId();
		$treeJson .= ');';		
		$this->render("Rat/tree.html", array("rat" => $rat, "treeJson" => htmlspecialchars_decode($treeJson)));
			
	}
		
}
