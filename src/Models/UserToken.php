<?php
namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use \App\Services\EntityService as Entities;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_tokens")
*/
class UserToken
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
    protected $token;

	/**
    * @ORM\Column(type="datetime")
    */
    protected $expires;		
	
    /**
     * Many Rats have One Owner
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rats")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;	
	
	public function __construct() {
      
	  $this->ownerships = new ArrayCollection();
	  $this->images = new ArrayCollection();
	  
    }	

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
		return $this->token;
    }
	
    private function setToken($token)
    {
        $this->token = $token;
    }		
	
	public function generateToken(){
		
		$bytes = random_bytes(20);
		$this->setToken(bin2hex($bytes));
		
		$expires = new \datetime();
		$expires->modify('+1 day');
		
		$this->setExpires($expires);
		
		return $this->getToken();
		
	}
	
    public function getUser()
    {
        return $this->user;
    }		
	
    public function setUser($user)
    {
        $this->user = $user;
    }		
		
	private function setExpires($expires){
		
		$this->expires = $expires;
	}
	
	public function expire(){
		
		$now = new \datetime();
		$this->setExpires($now);
	}
	
	public function isValid(){
		
		if($this->expires > new \datetime()){
			return true;
		}
			
	}
			
}