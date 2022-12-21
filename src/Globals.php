<?php

use \App\Services\NotificationService as Notifications;
use \App\Services\SessionService as Session;
use \App\Services\UpdateService as Update;
use \App\Services\PluginService as Plugins;
use \App\Services\EntityService as Entities;

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
define("_CONTROLLERS", "\\App\\Controllers\\");
define("_VIEWS", "\\App\\Views\\");	

define("_CONFIG_FILE",DIR_APP . '/Config.php');

/* Version / Build */
define("_BUILD", file_get_contents(DIR_ROOT . '/build'));

/* Global Image Sizes */
define("_IMAGE_SIZES",[
	"thumbnail" => ["width" => 300, "height" => 300],
	"small" => ["width" => 600, "height" => 600],	
	"medium" => ["width" => 1000, "height" => 1000],	
	"large" => ["width" => 1500, "height" => 1500],
	]
);

/* Rat Statuses */
define("_RAT_STATUS", array(
	'ACTIVE' => array('id' => 1, 'name' => 'Active'),
	'DECEASED' => array('id' => 2, 'name' => 'Deceased'),
	'UNKNOWN' => array('id' => 3, 'name' => 'Unknown')
	)
);

/* Rat Genders */
define("_RAT_GENDERS", array(
	array('value' => 'male', 'text' => 'Male'),
	array('value' => 'female', 'text' => 'Female'),
	)
);

/* User Roles*/
define("_USER_ROLES", array(
	'ADMIN' => array('id' => 1, 'name' => 'Administrator', 'level' => 1),
	'BREEDER' => array('id' => 2, 'name' => 'Breeder', 'level' => 2),
	'OWNER' => array('id' => 3, 'name' => 'Owner', 'level' => 3),
	'USER' => array('id' => 4, 'name' => 'User', 'level' => 4)	
	)
);

/* Doctrine Booleans */
define("_BOOLS", array(
	array('value' => '0', 'text' => 'No'),
	array('value' => '1', 'text' => 'Yes'),
	)
);


/* Countries */
define("_COUNTRIES",json_decode(file_get_contents(DIR_APP . '/Countries.json'), true));

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

function getMetadata($key){

	$meta = Entities::findBy("metadata",["key" => $key]);

	if($meta){
		return $meta[0]->getValue();
	}else{
		return null;
	}

}

function setMetadata($key, $value){

	if(Entities::findBy("metadata",["key" => $key])){

		$meta = Entities::findBy("metadata",["key" => $key])[0];
		$meta->setValue($value);
	}else{

		$meta = new \App\Models\Metadata();
		$meta->setValue($value);
		$meta->setKey($key);
	}

	Entities::persist($meta);
	Entities::flush();

}




Session::start();

if(Update::hasNewVersion()){
	Notifications::addNotification("New Update Available: " .  Update::remoteVersion(),"/update","globe-europe");
}


