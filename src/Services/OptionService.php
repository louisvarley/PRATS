<?php

namespace App\Services;

use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;

class OptionService{
		
	
	/* Get a Property */
	public static function getAllOptions(){

		$optionsets = Entities::findAll("optionset","key");
		
		$arr = [];
		
		
		foreach($optionsets as $optionset){
			
			$arr[strtolower($optionset->getKey())] = $optionset->getOptions();
		}

		return $arr;
		
	}
	
	/* Get a Property */
	public static function getOptions($key){

		$optionsets = Entities::findBy("optionset",["key" => $key]);

		return $optionsets[0]->getOptions();
		
	}

	
	
}