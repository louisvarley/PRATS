<?php

namespace App\Services;

use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;

class PropertyService{
		
	public static function getAllProperties(){
		
		$properties = Entities::findAll("Property","key");
		
		$arr = [];
		
		foreach($properties as $property){
			
			$arr[strtolower($property->getKey())] = $property->getValue();
		}
		
		return $arr;
		
	}
	
	/* Get a Property */
	public static function getProperty($key){

		$prop = Entities::findBy("property",["key" => $key]);

		if(count($prop) > 1){
			
			return $prop;
			
		}elseif(count($prop) == 1){

			return $prop[0];
			
		}else{
			
			return new \App\Models\Property();
		}
		
	}
	


	
	
}