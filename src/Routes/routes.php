<?php

use \App\Services\RouteService as Route;

/**
 * Routing - Add any Routes, Described using Regex etc
 */

/* Reporting */
Route::add('report/{controller}/{action}', ['namespace' => 'Report']);

/* API */
Route::add('api/{controller}/{action}', ['namespace' => 'Api']);

/* Controller Action With ID and File */
Route::add('{controller}/{id:\d+}.jpg', ['action' => 'index']);  

/* Controller Action With ID and File */
Route::add('{controller}/{size}/{id:\d+}.jpg', ['action' => 'index']);  

/* Fetching a Purchase first Blob Image */
Route::add('{controller}/purchase/{size}/{id:\d+}.jpg', ['action' => 'purchase']);  

/* Index Route */
Route::add('', ['controller' => 'Home', 'action' => 'index']);

/* Controller Index Route */
Route::add('{controller}', ['controller' => '{controller}', 'action' => 'index']);

/* Controller Action No ID */
Route::add('{controller}/{action}');

/* Controller Action With ID */
Route::add('{controller}/{action}/{id:\d+}');   

/* Controller Action With ID */
Route::add('{controller}/{action}/{id:\d+}');   

/* Controller Action With ID Dashed */
Route::add('{controller}/{action}/{id:[0-9-]*$}');   

/* Controller Index With ID */
Route::add('{controller}/{id:\d+}', ['controller' => '{controller}', 'action' => 'index']);
