<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

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
    * @ORM\Column(type="string", nullable="true")
    */
    protected $email;	
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $apikey;	
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    private $password_hash;		
	
    /**
    * Many Users have One User Roles.
    * @ORM\ManyToOne(targetEntity="UserRole")
    * @ORM\JoinColumn(name="role_id", referencedColumnName="id")	
     */ 	
    protected $userRole;		
	
	 /**
     * One User has Many Rats.
     * @ORM\OneToMany(targetEntity="Rat", mappedBy="owner")
     */ 
    protected $rats;	

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $code;

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $firstName;		
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $lastName;		
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $addressLine1;	
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $addressLine2;	

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $town;	

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $county;	

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $country;	

	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $postcode;	
	
	/**
    * @ORM\Column(type="string", nullable="true")
    */
    protected $telephone;		
	
	
	/**
    * @ORM\Column(type="boolean", nullable="true")
    */
    protected $passwordResetFlag;		
	
		
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
		$this->password_hash = \password_hash ($password,   PASSWORD_DEFAULT  );
    }
	
    public function getApiKey()
    {
        return $this->apikey;
    }

    public function generateApiKey()
    {
        $this->apikey = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
    }	

	public function validatePassword($password){
		
		if(password_verify($password, $this->password_hash)){
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


    public function getPasswordResetFlag()
    {
        return $this->passwordResetFlag;
    }	
	
    public function setPasswordResetFlag($passwordResetFlag)
    {
        $this->passwordResetFlag = $passwordResetFlag;
    }	

}