<?php

namespace App\Controllers;

use \App\View;
use \App\Services\EntityService as Entities;
/**
 * Home controller
 *
 * 
 */
class Home extends \App\Controller
{

	protected $authentication = true;

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		$data = null;
		$this->render('Home/index.html', $data);	
    }
}
