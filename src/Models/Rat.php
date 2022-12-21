<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use \App\Services\EntityService as Entities;

/**
 * @ORM\Entity
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
    * @ORM\Column(type="string")
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
    * @ORM\ManyToOne(targetEntity="RatStatus")
    * @ORM\JoinColumn(name="rat_status_id", referencedColumnName="id")
    */	
    protected $status;
	
    /**
     * Many Rats have One Owner
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rats")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $owner;	
	
	/**
    * @ORM\ManyToMany(targetEntity="Blob")
	* @ORM\JoinTable(name="rats_images",
    *      joinColumns={@ORM\JoinColumn(name="purchase_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="blob_id", referencedColumnName="id", unique=true)}
    *      )
    */
    protected $images;	
  
	public function __construct() {
      
	  $this->images = new ArrayCollection();
	  
    }	

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
	
    public function getOwner()
    {
        return $this->owner;
    }		
	
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }		
		
	public function resetCode(){

		if($this->getLitter()){

			$breedercode = $this->getLitter()->getBreeder()->getCode();
			$doeCode = substr($this->getLitter()->getDoe()->getName(),0,1);
			$buckCode = substr($this->getLitter()->getBuck()->getName(),0,1);
			$genderCode = substr($this->getGender(),0,1);
			
			$compCode = strtoupper($breedercode . $buckCode . $doeCode . $genderCode);			

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
			
			$this->setCode(strtoupper($breedercode . $buckCode . $doeCode . $genderCode) . str_pad($next, 2, '0', STR_PAD_LEFT));
		
		}
		
	}		
	
}