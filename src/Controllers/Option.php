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
 

class Option extends \App\Controllers\ManagerController
{

	protected $authentication = true;	
	public $page_data = ["title" => "Option", "description" => "Options with Multiple values"];	

	public function getEntity($id = 0){
		
		if($this->route_params['action'] == 'new'){ //New needs $_GET optionset id
			
			$optionset = Entities::findEntity("optionset", $_GET['optionset_id']);
		}else{
			
			$optionset = Entities::findEntity("optionset", Entities::findEntity($this->route_params['controller'], $id)->getOptionset()->getId());
		}
		
		return array(
			$this->route_params['controller'] => Entities::findEntity($this->route_params['controller'], $id),
			'optionset' => $optionset,
		);	
	} 

	public function updateEntity($id, $data){
		
		$option = Entities::findEntity($this->route_params['controller'], $id);

		$option->setText($data['option']['text']);
		$option->setValue($data['option']['value']);
		
		Entities::persist($option);
		Entities::flush();
		
		return $option->getId();		
	}
	
	public function insertEntity($data){

		$option = new \App\Models\Option();

		$optionset = Entities::findEntity("optionset", $data['option']['optionset']['id']);
		
		$option->setText($data['option']['text']);
		$option->setValue($data['option']['value']);
		$option->setOptionset($optionset);
		
		Entities::persist($option);
		Entities::flush();
		
		return $option->getId();
		
	}	
	
	
}
