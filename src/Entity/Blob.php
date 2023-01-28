<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use \Core\Services\EntityService as Entities;
use Intervention\Image\ImageManager;

/**
 * @ORM\Entity
 * @ORM\Table(name="blobs")
 */
class Blob
{
	/**
    * @ORM\Id
    * @ORM\Column(type="integer", name="id")
    * @ORM\GeneratedValue
    */
    protected $id;	
	
	/** @ORM\Column(type="blob", name="data") **/
    protected $data;	

	protected $manager;

	public function __construct(){
		
		$this->manager = new ImageManager(['driver' => 'gd']);

	}

    public function getId()
    {
        return $this->id;
    }

    public function getData()
    {
		if(is_resource($this->data)){
			return stream_get_contents($this->data);
		}else{
			return $this->data;
		}
					
    }  

    public function setData($file)
    {
		$this->manager = new ImageManager(['driver' => 'gd']);
		
        $img = $this->manager->make($file);
		$this->data = (string) $img->encode('jpg');		
    }  
	
	/* Resizes and saves back */
	public function setResize($width, $height){
			
		$dataResized =  $this->getResized($width, $height);	
		$this->data = $dataResized;
	}

	/* Resizes and Returns */
	public function getResized($width, $height){
	
		$this->manager = new ImageManager(['driver' => 'gd']);
	
		$img = $this->manager->make($this->getData());
		$img->resize($width, $height);
		return (string) $img->encode('jpg');
	}
	
	public function getUrl(){
		return "/blob/" . $this->getId() . ".jpg";
	}
	
	public function getThumbnailUrl(){
		return "/blob/thumbnail/" . $this->getId() . ".jpg";
	}	
	
	public function getSmallUrl(){
		return "/blob/small/" . $this->getId() . ".jpg";
	}	
	

}