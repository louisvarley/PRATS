<?php

namespace App\Controllers\Api;

use \App\View;
use \App\Models\Purchase;
use \App\Services\UpdateService as Updater

/**
 * Home controller
 *
 * 
 */

class Update extends \App\Controllers\Api
{

	public function CurrentVersionGetAction(){


		return new \App\Classes\ApiResponse(200, 0, ['version' => Updater::currentVersion()]);

	}
	
	public function RemoteVersionGetAction(){


		return new \App\Classes\ApiResponse(200, 0, ['version' => Updater::remoteVersion()]);

	}	


}
