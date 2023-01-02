<?php

namespace App\Services;

use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;

class PropertyService{
		
	/* Adds new only if not present */
	public static function add($key, $value){

		if(!self::propertyExists($key)){
			
			$property = new \App\Models\Property();
			
			$property->setKey($key);
			$property->setValue($value);
			
			Entities::persist($property);
			Entities::flush();			
			
		}
		
	}

	/* Adds or Edits, new only if not present */
	public static function edit($key, $value){

		if(!self::propertyExists($key)){
			
			self::add($key, $value);

		}else{

			$property = self::getProperty($key);
			$property->setKey($key);
			$property->setValue($value);
			
			Entities::persist($property);
			Entities::flush();	
			
		}
		
	}		
		
	public static function propertyExists($key){

		$property = Entities::findBy("property",["key" => $key]);
		
		if(count($property) > 0){
			
			return true;
			
		}
		
	}		
		
	public static function getAllProperties(){
		
		$properties = Entities::findAll("Property","key");
		
		$arr = [];
		
		foreach($properties as $property){
			
			$arr[($property->getKey())] = $property->getValue();
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