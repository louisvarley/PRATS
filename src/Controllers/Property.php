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
 

class Property extends \App\Controllers\ManagerController
{

	protected $authentication = true;	
	public $page_data = ["title" => "Properties", "description" => "App Properties and Defines"];	

	public function updateEntity($id, $data){
		
		$property = Entities::findEntity($this->route_params['controller'], $id);

		$property->setKey($data['property']['key']);
		$property->setName($data['property']['name']);
		$property->setValue($data['property']['value']);
		
		Entities::persist($property);
		Entities::flush();
		
		return $property->getId();		
	}
	
	public function insertEntity($data){

		$property = new \App\Models\Property();
		
		$property->setKey($data['property']['key']);
		$property->setName($data['property']['name']);
		$property->setValue($data['property']['value']);
		
		Entities::persist($property);
		Entities::flush();
		
		return $property->getId();
		
	}	
	
	
}
