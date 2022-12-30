<?php

namespace App\Controllers;

use \App\View;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;

use \App\Services\EntityService as Entities;
use \App\Services\PropertyService as Properties;
/**
 * Home controller
 *
 * PHP version 7.0
 */
 

class Settings extends \App\Controller
{

	protected $authentication = true;	
	public $page_data = ["title" => "Settings", "description" => "Config Settings"];	
	
	public function editAction(){


		if($this->isPOST()){
			$this->save($this->post);
		}


		$properties = Properties::getAll();

		$settings = [];

		foreach($properties as $property){
			$settings[$property->getKey()] = $property->getValue();
		}

		View::renderTemplate('Settings/form.html', array_merge(
				$this->route_params, 
				$this->page_data,
				['settings' => $settings],					
			));
	} 

	public function save($data){
		
		foreach($data['settings'] as $key => $value){
			
			/* TODO */
			Properties::getAll();

		}

	}
		
}
