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

			$note = new \App\Entities\PurchaseNote();
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

		$rat = new \App\Entities\rat();
		
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
	
	/* Really not efficent way to do this, lots of jumping around */
	public function getTreeWithChildren($id, $familyTree){
		
		/* Get the Rat */
		$rat = Entities::findEntity("rat", $id);
		
		/* Is In Tree Already? */
		if(!isset($familyTree[$rat->getId()])){
		
		/* Add this rat */
			$familyTree[$rat->getId()] = new \App\Classes\FamilyNode($rat->getName(), $rat->getGender()->value);
			$familyTree[$rat->getId()]->image = ($rat->getProfileImage());
			$familyTree[$rat->getId()]->status = ($rat->getStatus()->value);			
		
		}
		
		/* If this rat has any litters */
		if($rat->getLitters()){
	
			/* Loop Litters */
			foreach($rat->getLitters() as $litter){
				
				/* Is our rat a Buck */
				if($rat->getGender()->value == "M"){
			
					/* Add This Bucks Mate */
					if(!isset($familyTree[$litter->getDam()->getId()])) $familyTree[$litter->getDam()->getId()] = new \App\Classes\FamilyNode($litter->getDam()->getName(), $litter->getDam()->getGender()->value);
					
					/* Her Info */
					$familyTree[$rat->getId()]->mate = ($litter->getDam()->getId());
					$familyTree[$litter->getDam()->getId()]->mate = ($rat->getId());
					$familyTree[$litter->getDam()->getId()]->status = ($litter->getDam()->getStatus()->value);
					$familyTree[$litter->getDam()->getId()]->image = ($litter->getDam()->getProfileImage());
				}
				
				/* Is our rat a Doe */
				if($rat->getGender()->value == "F"){		

					/* Add This Doe's Mate */
					if(!isset($familyTree[$litter->getSire()->getId()])) $familyTree[$litter->getSire()->getId()] = new \App\Classes\FamilyNode($litter->getSire()->getName(), $litter->getSire()->getGender()->value);
					
					/* His Info */
					$familyTree[$rat->getId()]->mate = ($litter->getSire()->getId());
					$familyTree[$litter->getSire()->getId()]->mate = ($rat->getId());
					$familyTree[$litter->getSire()->getId()]->status = ($litter->getSire()->getStatus()->value);
					$familyTree[$litter->getSire()->getId()]->image = ($litter->getSire()->getProfileImage());
				}
				
				/* Now Add Children From This Litter */
				foreach($litter->getRats() as $childRat){
					
					
					if(!isset($familyTree[$childRat->getId()])) $familyTree[$childRat->getId()] = new \App\Classes\FamilyNode($childRat->getName(), $childRat->getGender()->value);
					$familyTree[$childRat->getId()]->father = ($litter->getSire()->getId());
					$familyTree[$childRat->getId()]->mother = ($litter->getDam()->getId());	
					$familyTree[$childRat->getId()]->status = ($childRat->getStatus()->value);
					$familyTree[$childRat->getId()]->image = ($childRat->getProfileImage());
					
					/* If child went on to have a litter, run loop again for them first */
					if($childRat->getLitters())					
						$familyTree = $this->getTreeWithChildren($childRat->getId(), $familyTree);
								
				}
				
			}	
		
		/* If not then we have to calculate everything manually */
		}else{
			
			if($rat->getGender()->value == "M"){
				$rats = Entities::findBy("Rat", ['sire' => $rat->getId()]);
			}else{
				$rats = Entities::findBy("Rat", ['dam' => $rat->getId()]);		
			}
						
			foreach($rats as $childRat){
				
				if(!isset($familyTree[$childRat->getId()])) $familyTree[$childRat->getId()] = new \App\Classes\FamilyNode($childRat->getName(), $childRat->getGender()->value);
				
				$familyTree = $this->getTreeWithChildren($childRat->getId(), $familyTree);
				$familyTree[$childRat->getId()]->father = ($childRat->getSire()->getId());
				$familyTree[$childRat->getId()]->mother = ($childRat->getDam()->getId());	
				$familyTree[$childRat->getId()]->image = $childRat->getProfileImage();
				$familyTree[$childRat->getId()]->status = $childRat->getStatus()->value;
				
				if($rat->getGender()->value == "M"){
					
					if(!isset($familyTree[$childRat->getDam()->getId()])){
						$familyTree[$childRat->getDam()->getId()] = new \App\Classes\FamilyNode($childRat->getDam()->getName(), $childRat->getDam()->getGender()->value);
					}
					$familyTree[$rat->getId()]->mate = $childRat->getDam()->getId();
					$familyTree[$childRat->getDam()->getId()]->mate = $rat->getId();
					$familyTree[$childRat->getDam()->getId()]->status = $childRat->getDam()->getStatus()->value;	
					$familyTree[$childRat->getDam()->getId()]->image = $childRat->getDam()->getProfileImage();					
					
				}else{
					if(!isset($familyTree[$childRat->getSire()->getId()])){
						$familyTree[$childRat->getSire()->getId()] = new \App\Classes\FamilyNode($childRat->getSire()->getName(), $childRat->getSire()->getGender()->value);
					}
					$familyTree[$rat->getId()]->mate = $childRat->getSire()->getId();
					$familyTree[$childRat->getSire()->getId()]->mate = $rat->getId();	
					$familyTree[$childRat->getSire()->getId()]->status = $childRat->getSire()->getStatus()->value;
					$familyTree[$childRat->getSire()->getId()]->image = $childRat->getSire()->getProfileImage();
				}				
				
			}
			
		}

		return $familyTree;
		
	}
	
	public function treeAction(){
		
		$rat = Entities::findEntity($this->route_params['controller'], $this->route_params['id']);
		
		$familyTree = [];
		
		/* follow back the fathers family */

		$x = $rat->getSire();
		
		if($x){
		
			do {			
				
				$father = $x;
				$x = $father->getSire();
				

			} while ($x);
		
		}else{
			
			$father = $rat;
		}
	
		
		$familyTree = $this->getTreeWithChildren($father->getId(), $familyTree);



		$x = $rat->getDam();
				
		if($x){
		
			do {			
				
				$mother = $x;
				$x = $mother->getDam();
				

			} while ($x);
		
		}else{
			
			$mother = $rat;
		}
	
		
		$familyTree = $this->getTreeWithChildren($mother->getId(), $familyTree);
	


		//$familyTree = $this->getTreeWithChildren($this->route_params['id'], $familyTree, );
		
		$treeJson = "setupDiagram(myDiagram, [";
		
		foreach($familyTree as $key => $node){
			
			$treeJson .= "\r\n";
			$code = $node->getGender() == 'M' ? 'ux' : 'vir';
		
			$treeJson .= "
			{ 
			key:$key,
			n: \"$node->name\", 
			s: \"$node->gender\", 
			i: \"$node->image\",
			x: \"$node->status\",			
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
