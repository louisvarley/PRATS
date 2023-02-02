<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="options")
 */
class Option
{
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/**
    * @ORM\Column(type="text", nullable="false", name="option_value", length=65535)
    */
    protected $value;	

	/**
    * @ORM\Column(type="text", nullable="false", name="option_text", length=65535)
    */
    protected $text;		

    /**
     * Many Options have One Optionset
     * @ORM\ManyToOne(targetEntity="Optionset", inversedBy="options")
     * @ORM\JoinColumn(name="optionset_id", referencedColumnName="id")
     */
    protected $optionset;	

    public function getId()
    {
        return $this->id;
    }
	
	public function getOptionset()
	{
		return $this->optionset;
	}
	
	public function setOptionset($optionset)
	{
		$this->optionset = $optionset;
	}

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }	
	
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }  

}