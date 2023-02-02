<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="optionsets")
 */
class Optionset
{
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/**
    * @ORM\Column(type="string", nullable="false", name="option_key")
    */
    protected $key;	

	/**
    * @ORM\Column(type="text", nullable="false", name="option_name", length=65535)
    */
    protected $name;	

	 /**
     * One Optionset has Many Options.
     * @ORM\OneToMany(targetEntity="Option", mappedBy="optionset")
     */ 
    protected $options;	
	
	public function __construct()
    {
		$this->options = new ArrayCollection();
    }		

    public function getId()
    {
        return $this->id;
    }
	
	public function getOptions()
	{
		return $this->options;
	}

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }
	
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }	
	
}