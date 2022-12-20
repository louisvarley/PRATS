<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_roles")
 */
class UserRole
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
    * @ORM\Column(type="integer", nullable="false")
    */
    protected $level;	

    /**
     * One Role has many Users.
     * @ORM\OneToMany(targetEntity="User", mappedBy="userRole")
     */ 
    protected $users;	
	
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setname($name)
    {
        $this->name = $name;
    }
	
    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }	
	
    public function getUsers()
    {
        return $this->users;
    }			

}