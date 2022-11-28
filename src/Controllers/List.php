<?php

namespace App\Controllers;

use \App\View;

/**
 * Home controller
 *
 * 
 */
class List extends \App\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		

        View::renderTemplate('Home/index.html');
    }
}
