<?php

namespace App\Controllers\Api;

use \App\View;
use \App\Services\EntityService as Entities;

/**
 * Home controller
 *
 * 
 */
class Blobs extends \App\Controllers\Api
{
	
	protected function uploadPostAction(){

		try{

			$image = new \App\Models\Blob();
			
			$image->setData($_FILES['image']['tmp_name']);
			
			$image->setResize(_IMAGE_SIZES['LARGE']['width'], _IMAGE_SIZES['LARGE']['height']);
			
			Entities::persist($image);

			Entities::flush();

			return new \App\Classes\ApiResponse(200, 0, ['blobId' => $image->getId(), 'message' => 'Image Saved']);
	
		}
		catch (Exception $e) {
			return new \App\Classes\ApiResponse(500, 0, ['error' => $e->getMessage()]);
		}
	}

}
