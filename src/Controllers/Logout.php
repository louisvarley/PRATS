<?php

namespace App\Controllers;

use \App\View;
use \App\Services\AuthenticationService as Authentication;

/**
 * Home controller
 *
 * 
 */
class Logout extends \App\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		
		
		Authentication::logout();
		
		header('Location: /');
		
	}
}
