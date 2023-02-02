<?php

use \App\Services\NotificationService as Notifications;
use \App\Services\SessionService as Session;
use \App\Services\EntityService as Entities;
use \App\Services\PropertyService as Properties;
use \App\Services\RouteService as Route;

use Doctrine\ORM\Query\ResultSetMapping;
 
if(!defined('STDIN') ) {
	define("_URL_ROOT", (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST']); 
}

/**
 * Directories
 */

define("DIR_ROOT", dirname(dirname(__FILE__)));
define("DIR_APP", DIR_ROOT . '/src');	
define("DIR_PUBLIC", DIR_ROOT . '/public');
define("DIR_STATIC", DIR_PUBLIC  . '/static');	
define("DIR_PROXIES", DIR_APP  . '/Proxies');
define("DIR_VENDOR", DIR_ROOT . '/vendor');
define("DIR_ROUTES", DIR_APP . '/Routes');

define("DIR_VIEWS", DIR_APP . '/Views');	
define("DIR_CONTROLLERS", DIR_APP . '/Controllers');	
define("DIR_ENTITIES", DIR_APP . '/Entities');	

define("WWW_STATIC", '/static');	
define("WWW_JS", WWW_STATIC  . '/js');		
define("WWW_CSS", WWW_STATIC  . '/css');	
define("WWW_IMG", WWW_STATIC  . '/img');	

define("_ENTITIES", "\\App\\Entities\\");
define("_ENUMS", "\\App\\Enums\\");
define("_CONTROLLERS", "\\App\\Controllers\\");
define("_VIEWS", "\\App\\Views\\");	

define("_CONFIG_FILE",DIR_APP . '/Config.php');

/**
 * Error and Exception handling
 */

require DIR_APP . '/Error.php';

error_reporting(E_ALL);
set_error_handler('App\Error::errorHandler');
set_exception_handler('App\Error::exceptionHandler');


/**
 * Version Build for CI
 */

define("_BUILD", file_get_contents(DIR_ROOT . '/build'));

if(file_exists(_CONFIG_FILE)){
	define("_IS_SETUP", true);
}else{
	define("_IS_SETUP", false);	
}

/**
 * CLI Mode
 */
if(php_sapi_name() !== 'cli'){
	define("_URL", ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

if(file_exists(dirname(dirname(__FILE__)) . "/src/Config.php")){
	require(dirname(dirname(__FILE__)) . "/src/Config.php");
}

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');   
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}


/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Env Vars
 */
$dotenv = \Dotenv\Dotenv::createImmutable(DIR_ROOT);
$dotenv->load();

/**
 * First Launch
 */
 
Entities::load();
//Entities::generateSchema();
Entities::generateProxies();
Entities::initialUserCheck();

Session::start();

/* Global Options */

define("_IMAGE_SIZES",[
	"THUMBNAIL" => ["width" => 150, "height" => 150],
	"SMALL" => ["width" => 300, "height" => 300],
	"MEDIUM" => ["width" => 500, "height" => 500],
	"LARGE" => ["width" => 900, "height" => 900],	
]);

foreach (glob(DIR_ROUTES . "/*.php") as $filename)
{
    include $filename;
}

/* Process the dispatch */	
Route::dispatch($_SERVER['QUERY_STRING']);
