<?php

namespace App\Classes;

/**
 * Error and exception handler
 *
 * 
 */
class ApiResponse
{

	public $code;
	public $response;

	public function __construct($responseCode, $code, $response){

		http_response_code($responseCode);
		$this->code = $code;
		$this->response = $response;

	}

	public function asJson(){

		return json_encode($this);

	}


}
