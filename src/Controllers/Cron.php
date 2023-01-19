<?php

namespace App\Controllers;

use \App\View;
use \App\Services\ToastService as Toast;
use \App\Services\UpdateService as Updater;
use \App\Services\EntityService as Entities;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Cron extends \App\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
		Entities::generateStaticData();	
		Entities::generateSchema();

		Toast::throwSuccess("Success...", "Cron has been run");

		header('Location: /');
		
		
    }
	
}
