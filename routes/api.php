<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/rats', function () {

	$filter = [];
	$limit = null;
	$order = [];
	$offset = null;

	if(request()->get('filter'))
		$filter = (request()->get('filter'));

	$order = [];
	if(request()->get('order'))
		$order = (request()->get('order'));
	
	if(request()->get('limit'))
		$limit = (request()->get('limit')->get());	

	if(request()->get('offset'))
		$offset = (request()->get('offset')->get());	

	return \EntityManager::getRepository(\App\Entities\Rat::class)->findBy($filter, $order, $limit, $offset);

});

Route::get('/rats/{id}', function ($id) {
    return \EntityManager::getRepository(\App\Entities\Rat::class)->find($id);
});

