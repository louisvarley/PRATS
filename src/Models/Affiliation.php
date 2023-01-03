<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="affiliation")
 */
class Affiliation
{
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/**
    * @ORM\Column(type="string", nullable="false")
    */
    protected $name;
	
	/**
    * @ORM\Column(type="string", nullable="false")
    */
    protected $code;	
	
	/**
    * @ORM\Column(type="string", nullable="false")
    */
    protected $color;	

    /**
     * One Role has many Users.
     * @ORM\OneToMany(targetEntity="User", mappedBy="affiliation")
     */ 
    protected $breeders;	
	
	/**
	 * @ORM\OneToOne(targetEntity="Blob", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
	 */	
    protected $logo;	
	
	public function __construct()
    {
		$this->breeders = new ArrayCollection();
    }		
	
	
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }
	
    public function getCode()
    {
        return $this->code;
    }
		
    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
		
        $this->color = $color;
    }	
	
    public function getBreeders()
    {
        return $this->breeders;
    }			

	public function setImage($image)
	{
		 $this->image = $image;
	}
	
	public function getLogo()
	{
		return $this->logo;
	}
	
	public function setLogo(){

		if($this->getLogo() == null){
			return WWW_IMG . '/default.png';
		}else{
			return $this->getLogo()->getThumbnailUrl();
		}

	}	

}