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
     * Many User Roles have Many Users.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="userRoles")
     * @ORM\JoinTable(name="users_user_roles")	 
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
	
    public function getUsers()
    {
        return $this->users;
    }			

}