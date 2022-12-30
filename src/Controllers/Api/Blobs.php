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

			$imageLocation = ($_FILES['image']['tmp_name']);
			$imageData = file_get_contents($imageLocation);
			$imageBase64 = base64_encode($imageData);

			$image = new \App\Models\Blob();
			$image->setData($imageBase64);
			Entities::persist($image);

			Entities::flush();

			return new \App\Classes\ApiResponse(200, 0, ['blobId' => $image->getId(), 'message' => 'Image Saved']);
	
		}
		catch (Exception $e) {
			return new \App\Classes\ApiResponse(500, 0, ['error' => $e->getMessage()]);
		}
	}


	protected function purchaseImageDeleteAction(){

		try{

			$purchase = Entities::findEntity("purchase", $this->get['purchaseId']);
			$image = Entities::findEntity("blob", $this->get['blobId']);

			$purchase->getImages()->removeElement($image);

			Entities::remove($image);
			Entities::flush();

			
			return new \App\Classes\ApiResponse(200, 0, ['message' => 'Image deleted']);

		}
		catch (Exception $e) {
			return new \App\Classes\ApiResponse(500, 0, ['error' => $e->getMessage()]);

		}
	}


	protected function purchaseImageRotateGetAction(){
		

		
		$blobId = $this->get['blobId'];
		$image = Entities::findEntity("blob", $blobId);
		$image->rotate();
	
		return new \App\Classes\ApiResponse(200, 0, ['message' => 'Image Saved']);
		
	}


	protected function datatableGetAction(){
		
	foreach($_GET['columns'] as $key => $column){
		$_GET['columns'][$key]['data'] = substr($_GET['columns'][$key]['data'], 2);
	}

	$datatables = (new \Doctrine\DataTables\Builder())
    ->withColumnAliases([
        'id' => 'u.id',
		'name' => 'u.name',
		'status' => 'u.status',
		'category' => 'u.category',
    ])
    ->withIndexColumn('u.id')
    ->withQueryBuilder(
        Entities::createQueryBuilder()
            ->select('u')
            ->from(_MODELS . "purchase", 'u')
			->join('u.status', 's')
			->join('u.category', 'c')
			->addSelect('s,c')
		)
    ->withRequestParams($_GET);

	return ($datatables->getResponse());		
	

	}

}
