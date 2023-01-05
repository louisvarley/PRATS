<?php

use \App\Services\NotificationService as Notifications;
use \App\Services\SessionService as Session;
use \App\Services\UpdateService as Update;
use \App\Services\PluginService as Plugins;
use \App\Services\EntityService as Entities;
use \App\Services\PropertyService as Properties;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * Config
 */
 
if(!defined('STDIN') ) {
	define("_URL_ROOT", (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://") . $_SERVER['HTTP_HOST']); 
}

/* Directories */
define("DIR_ROOT", dirname(dirname(__FILE__)));
define("DIR_APP", DIR_ROOT . '/src');	
define("DIR_PUBLIC", DIR_ROOT . '/');
define("DIR_STATIC", DIR_PUBLIC  . '/static');	
define("DIR_PROXIES", DIR_APP  . '/Proxies');
define("DIR_VENDOR", DIR_ROOT . '/vendor');

define("DIR_VIEWS", DIR_APP . '/Views');	
define("DIR_CONTROLLERS", DIR_APP . '/Controllers');	
define("DIR_MODELS", DIR_APP . '/Models');	

define("DIR_PLUGINS", DIR_APP . '/Plugins');	

define("WWW_STATIC", '/static');	
define("WWW_JS", WWW_STATIC  . '/js');		
define("WWW_CSS", WWW_STATIC  . '/css');	
define("WWW_IMG", WWW_STATIC  . '/img');	

/* Name Spaces */
define("_MODELS", "\\App\\Models\\");
define("_ENUMS", "\\App\\Enums\\");
define("_CONTROLLERS", "\\App\\Controllers\\");
define("_VIEWS", "\\App\\Views\\");	

define("_CONFIG_FILE",DIR_APP . '/Config.php');

/* Version / Build */
define("_BUILD", file_get_contents(DIR_ROOT . '/build'));

if(file_exists(_CONFIG_FILE)){
	define("_IS_SETUP", true);
}else{
	define("_IS_SETUP", false);	
}

/* CLI Mode */
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
 * First Launch
 */
 
if(!_IS_SETUP){
	
	if($_SERVER['REQUEST_URI'] != "/setup"){
		header('Location: /setup');
		die();
	}
	return false;
}

Plugins::load();
Entities::load();

/* Create Proxies and Database Schema if not set */
Entities::generateSchema();
Entities::generateProxies();
Entities::initialUserCheck();

Session::start();

//if(Update::hasNewVersion()){
	//Notifications::addNotification("New Update Available: " .  Update::remoteVersion(),"/update","globe-europe");
//}

/* Default Properties */

Properties::add('DEFAULT_USER_AVATAR','/static/img/default.png');
Properties::add('DEFAULT_RAT_AVATAR','/static/img/default.png');		
Properties::add('SMTP_FROM_NAME','PRATS');				
Properties::add('SMTP_FROM','');			
Properties::add('SMTP_PASSWORD','');	
Properties::add('SMTP_USERNAME','');	
Properties::add('SMTP_PORT','');	
Properties::add('SMTP_HOST','');


/* Global Options */

define("_IMAGE_SIZES",[
	"THUMBNAIL" => ["width" => 300, "height" => 300],
	"SMALL" => ["width" => 300, "height" => 300],
	"MEDIUM" => ["width" => 300, "height" => 300],
	"LARGE" => ["width" => 300, "height" => 300],	
]);
