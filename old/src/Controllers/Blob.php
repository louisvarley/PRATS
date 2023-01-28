<?php

namespace App\Controllers;

use \App\View;
use \App\Services\EntityService as Entities;
/**
 * Home controller
 *
 * 
 */
 

class Blob extends \App\Controllers\ManagerController
{

	protected $authentication = false;	

	public function indexAction(){
		
		header('Content-type:image/jpg');

		$blob = Entities::findEntity($this->route_params['controller'], $this->route_params['id']);

		if(array_key_exists("size", $this->route_params)){
			
			$imageSize = _IMAGE_SIZES[strtoupper($this->route_params['size'])];
			
			echo $blob->getResized($imageSize['width'], $imageSize['height']);
			
		}else{ /* Full Size */
			
			echo $blob->getData();
			
		}
		
		die();

	} 
	
	

}
