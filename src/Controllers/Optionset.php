<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use \App\Services\EntityService as Entities;

/**
 * Home controller
 *
 * PHP version 7.0
 */
 

class Optionset extends \App\Controllers\ManagerController
{

	protected $authentication = true;	
	public $page_data = ["title" => "Optionsets", "description" => "Options with Multiple values"];	

	public function getEntity($id = 0){
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id),
			"options" => ($id ? Entities::findEntity($this->route_params['controller'], $id)->getOptions() : null),
		);	
	} 

	public function updateEntity($id, $data){
		
		$optionset = Entities::findEntity($this->route_params['controller'], $id);

		$optionset->setKey($data['optionset']['key']);
		$optionset->setName($data['optionset']['name']);
		
		Entities::persist($optionset);
		Entities::flush();
		
		return $optionset->getId();		
	}
	
	public function insertEntity($data){

		$optionset = new \App\Models\Optionset();
		$optionset->setKey($data['optionset']['key']);
		$optionset->setName($data['optionset']['name']);
		
		Entities::persist($optionset);
		Entities::flush();
		
		return $optionset->getId();
		
	}	
	
	
}
