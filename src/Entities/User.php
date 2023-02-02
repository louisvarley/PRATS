<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use \App\Classes as Classes;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
	
	/**************************/
	/* STANDARD FIELDS */
	/**************************/
	
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/**
    * @ORM\Column(name="email", type="string", nullable="true")
    */
    protected $email;	
	
	/**
    * @ORM\Column(name="api_key", type="string", nullable="true")
    */
    protected $apikey;	
	
	/**
    * @ORM\Column(name="password_hash", type="string", nullable="true")
    */
    private $passwordHash;		
	
	/**
    * @ORM\Column(name="user_role", type="string", nullable="false", enumType="\App\Enums\UserRoles")
    */	
    protected $userRole;		
		
	 /**
     * One User has Many Rats.
     * @ORM\OneToMany(targetEntity="Rat", mappedBy="owner")
     */ 
    protected $rats;	

	 /**
     * One User has Many Litters.
     * @ORM\OneToMany(targetEntity="Litter", mappedBy="breeder")
     */ 
    protected $litters;

    /**
     * Many Users have One Affiliation
     * @ORM\ManyToOne(targetEntity="affiliation", inversedBy="users")
     * @ORM\JoinColumn(name="affiliation_id", referencedColumnName="id")
     */
    protected $affiliation;	

	/**
    * @ORM\Column(name="breeder_code", type="string", nullable="true")
    */
    protected $code;

	/**
    * @ORM\Column(name="first_name", type="string", nullable="true")
    */
    protected $firstName;		
	
	/**
    * @ORM\Column(name="last_name", type="string", nullable="true")
    */
    protected $lastName;		
	
	/**
    * @ORM\Column(name="address_line_1", type="string", nullable="true")
    */
    protected $addressLine1;	
	
	/**
    * @ORM\Column(name="address_line_2", type="string", nullable="true")
    */
    protected $addressLine2;	

	/**
    * @ORM\Column(name="town", type="string", nullable="true")
    */
    protected $town;	

	/**
    * @ORM\Column(name="county", type="string", nullable="true")
    */
    protected $county;	

	/**
    * @ORM\Column(name="country", type="string", nullable="true", enumType="\App\Enums\Countries")
    */
    protected $country;	

	/**
    * @ORM\Column(name="postcode", type="string", nullable="true")
    */
    protected $postcode;	
	
	/**
    * @ORM\Column(name="telephone", type="string", nullable="true")
    */
    protected $telephone;		
		
	/**
	 * @ORM\OneToOne(targetEntity="Blob", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
	 */	
    protected $image;	
		
	public function __construct()
    {
		$this->rats = new ArrayCollection();
		$this->litters = new ArrayCollection();
    }	
	
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
	
    public function setPassword($password)
    {
		$this->passwordHash = \password_hash ($password,   PASSWORD_DEFAULT  );
    }
	
    public function getApiKey()
    {
        return $this->apikey;
    }

    public function generateApiKey()
    {
        $this->apikey = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
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
			return WWW_IMG . '/default.png';
		}else{
			return $this->getImage()->getThumbnailUrl();
		}

	}
	
	public function validatePassword($password){
		
		if(password_verify($password, $this->passwordHash)){
			return true;
		};
		return false;
	}
	
    public function getUserRole()
    {
        return $this->userRole;
    }	
	
	public function setUserRole($userRole)
	{
		$this->userRole = $userRole;
	}
	
    public function getAffiliation()
    {
        return $this->affiliation;
    }	
	
	public function setAffiliation($affiliation)
	{
	
		$this->affiliation = $affiliation;
	}	
	
    public function getRats()
    {
        return $this->rats;
    }		
	
    public function getLitters()
    {
        return $this->litters;
    }		
	
    public function getFirstName()
    {
        return $this->firstName;
    }		

    public function getLastName()
    {
        return $this->lastName;
    }
	
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }		

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

	public function getFullName(){
		
		return $this->getFirstName() . ' ' . $this->getLastName();
	}
	
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }	
	
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }	
	
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }	
	
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }	

    public function getTown()
    {
        return $this->town;
    }	
	
    public function setTown($town)
    {
        $this->town = $town;
    }		

    public function getCounty()
    {
        return $this->county;
    }	
	
    public function setCounty($county)
    {
        $this->county = $county;
    }	
	
    public function getCountry()
    {
        return $this->country;
    }	
	
    public function setCountry($country)
    {
        $this->country = $country;
    }	
	
    public function setCode($code)
    {
        $this->code = $code;
    }	

    public function getCode()
    {
        return $this->code;
    }		
		
    public function getPostcode()
    {
        return $this->postcode;
    }	
	
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }	

    public function getTelephone()
    {
        return $this->telephone;
    }	
	
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }		


}