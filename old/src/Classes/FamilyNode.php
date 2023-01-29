<?php

namespace App\Classes;

class FamilyNode
{

	public $name; 
	public $gender;
	public $mother;
	public $father;
	public $mate;
	public $image;
	public $status;

    function __construct($name, $gender) {
        $this->name     = $name;
        $this->gender 	= $gender;
    }

    function setMother($mother){
		$this->mother = $mother;
	}

    function setFather($father){
		$this->father = $father;
	}

    function setMate($mate){
		$this->mate = $mate;
	}
	
	function setImage($image){
		$this->image = $image;
	}
	
	function setStatus($status){
		$this->status = $status;
	}
	
	function getFather(){
		return $this->father;
	}
	
	function getMother(){
		return $this->mother;
	}
	
	function getStatus(){
		return $this->status;
	}
	
	function getGender(){
		return $this->gender;
	}
	
	function getMate(){
		return $this->mate;
	}
	
	function getName(){
		return $this->name;
	}
	
	function getImage(){
		return $this->image;
	}
}