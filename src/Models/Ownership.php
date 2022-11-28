<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="ownerships")
 */
class Ownership
{
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/**
    * @ORM\ManyToOne(targetEntity="User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */	
    protected $user;
	
	/**
    * @ORM\ManyToOne(targetEntity="Rat")
    * @ORM\JoinColumn(name="rat_id", referencedColumnName="id")
    */	
    protected $rat;
	
	
    public function getId()
    {
        return $this->id;
    }	
	
    public function getOwner()
    {
        return $this->owner;
    }	
	
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function getRat()
    {
        return $this->rat;
    }	
	
    public function setRat($rat)
    {
        $this->rat = $rat;
    }
	
}