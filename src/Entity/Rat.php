<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use \App\Services\EntityService as Entities;
use \App\Classes as Classes;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RatRepository")
 * @ORM\Table(name="rats")
*/
class Rat
{

	/**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue
    */
    protected $id;	

	/**
    * @ORM\Column(type="string")
    */
    protected $name;
	
	/**
    * @ORM\Column(name="gender", type="string", enumType="\App\Enums\Genders")
    */
    protected $gender;	
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $code;	
	
    /**
     * Many Rats have One Litter
     * @ORM\ManyToOne(targetEntity="Litter", inversedBy="rats")
     * @ORM\JoinColumn(name="litter_id", referencedColumnName="id")
     */
    protected $litter;	

	/**
    * @ORM\Column(name="status", type="string", enumType="\App\Enums\RatStatus")
    */
    protected $status;
	
	/**
    * @ORM\Column(name="birth_date", type="date", nullable="true")
    */
    protected $birthDate;		
	
	/**
    * @ORM\Column(type="string", enumType="\App\Enums\Countries", nullable="true")
    */
    protected $country;		
	
    /**
     * Many Rats have One Owner
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rats")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $owner;	
	
	/**
	 * @ORM\OneToOne(targetEntity="Blob", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
	 */	
    protected $image;	
	
	/**
    * @ORM\ManyToOne(targetEntity="rat")
    * @ORM\JoinColumn(name="sire_id", referencedColumnName="id")
    */	
    protected $sire;		
	
	/**
    * @ORM\ManyToOne(targetEntity="rat")
    * @ORM\JoinColumn(name="dam_id", referencedColumnName="id")
    */	
    protected $dam;		
  
  
    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }	

    public function getName()
    {
        return $this->name;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }	

    public function getGender()
    {
        return $this->gender;
    }		
	
    public function setCode($code)
    {
        $this->code = $code;
    }	

    public function getCode()
    {
        return $this->code;
    }	
	
    public function getLitter()
    {
        return $this->litter;
    }	
	
    public function setLitter($litter)
    {
        $this->litter = $litter;
    }	
	
    public function getStatus()
    {
		return $this->status;
    }
	
    public function setStatus($status)
    {
        $this->status = $status;
    }		
	
	public function setImage($image)
	{
		 $this->image = $image;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function getProfileImage(){

		if($this->getImage() == null){
			return '/default.png';
		}else{
			return $this->getImage()->getThumbnailUrl();
		}

	}	
	
    public function getOwner()
    {
        return $this->owner;
    }		
	
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }		
		
    public function setCountry($country)
    {
		

		if(!$this->getLitter()){
			$this->country = $country;
		}		  
    }	
	
    public function getCountry()
    {
		
		if($this->getLitter()){
			return $this->getLitter()->getBreeder()->getCountry();
		}else{
			return $this->country;
		}
    }	
	
	public function getOrigin(){

		if($this->getLitter()){
			return $this->getLitter()->getBreeder()->getCountry();
		}elseif($this->country){
			return $this->country;
		}
		
	}
	
	public function getResidence(){

		if($this->getOwner()){
			return $this->getOwner()->getCountry();
		}else{
			return $this->getOrigin();
		}
		
	}
	
    public function getBirthDate()
    {        
		if($this->getLitter()){
			$this->getLitter()->getBirthDate();
		}else{
			return $this->birthDate;
		}
    }	
	
    public function setBirthDate($date)
    {
		if(!$this->getLitter()){
			$this->birthDate = $date;
		}
    }	

    public function setDam($dam)
    {
		if(!$this->getLitter()){		
			$this->dam = $dam;
		}
    }		

    public function getDam()
	{
		if($this->getLitter()){
			return $this->getLitter()->getDam();
		}else{
			return $this->dam;
		}
		
    }	
	
    public function setSire($sire)
    {
		if(!$this->getLitter()){
			$this->sire = $sire;
		}
    }		

    public function getSire()
	{
		if($this->getLitter()){
			return $this->getLitter()->getSire();
		}else{
			return $this->sire;
		}
    }	

	/*
	public function getLitters(){
		
		return Entities::findBy("Litter", [($this->getGender()->value == 'M' ? 'sire' : 'dam') => $this->getId()]);
		
	}
	
	public function getChildren(){
		
		$children = new ArrayCollection();
		
		foreach($this->getLitters() as $litter){

			foreach($litter->getRats() as $rat){
				$children[] = $rat;
			}
		}
		
		return $children;
		
	}
	
	public function resetCode(){

		if($this->getLitter()){

			$breedercode = $this->getLitter()->getBreeder()->getCode();
			$damCode = substr($this->getLitter()->getDam()->getName(),0,1);
			$sireCode = substr($this->getLitter()->getSire()->getName(),0,1);

			$genderCode = $this->getGender()->value;
			
			$compCode = strtoupper($breedercode . $sireCode . $damCode . $genderCode);			

			$nextQuery = Entities::em()->getRepository(_MODELS . 'Rat')
				->createQueryBuilder('r')
				->select('r.code')
				->where('r.code like :compCode')
				->setParameter('compCode', '%'.$compCode.'%')
				->orderBy("r.id", 'ASC');
			
			if($this->getId()){
				$nextQuery->andWhere( 'r.id < ' . $this->getId() );
			}

			$next =	count($nextQuery->getQuery()
					->getArrayResult()) + 1;			
			
			$this->setCode(strtoupper($breedercode . $sireCode . $damCode . $genderCode) . str_pad($next, 2, '0', STR_PAD_LEFT));
		
		}
		
	}	

	*/	
	
}