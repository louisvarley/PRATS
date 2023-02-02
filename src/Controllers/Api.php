<?php

namespace App\Controllers;

use \App\Services\AuthenticationService as Authentication;
use \App\Services\SessionService as Session;

/**
 * Home controller
 *
 * 
 */
class Api extends \App\Controller
{

	protected function before()
	{
		header('Content-type: application/json');
	}
	
	public function apiCheck(){
		
		if(Authentication::validApiKey()){
			return true;
		}
		
	}
	
    public function __call($name, $args)
    {

		if($this->isPOST()){
			$method = $name . "Post";
		}

		if($this->isGET()){
			$method = $name . "Get";
		}

		if($this->isPUT()){
			$method = $name . "Put";
		}		

		if($this->isDELETE()){
			$method = $name . "Delete";
		}	
		
        $method .= 'Action';
		$reflection = new \ReflectionMethod($this, $method);		
		
		/* Update Last Activity Time */
		Session::activity();

		/* Method Not Found */
        if (!method_exists($this, $method)) {
			$response = new \App\Classes\ApiResponse(404, 404, ['message' => "API Method $method not found in " . get_class($this)]);
			
		/* Method found, but protected and no authentication or API Key */	
		}elseif($reflection->isProtected() && (!$this->apiCheck() && !Authentication::loggedIn())){
			$response = new \App\Classes\ApiResponse(401, 401, ['message' => "Unauthorised or invalid API Key "]);
			
		/* Fine to Run */
		}else{
			
			$response = call_user_func_array([$this, $method], $args);
		}
		
		if ($this->before() !== false) {
			
			if(method_exists($response,"as_json")){
				echo $response->asJson();
			}else{
				echo json_encode($response);
			}
		
			$this->after();
		}				
    }
}