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

if(Update::hasNewVersion()){
	Notifications::addNotification("New Update Available: " .  Update::remoteVersion(),"/update","globe-europe");
}

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

define("_GENDERS", [
	new \App\Classes\Option("Male", "Male"),
	new \App\Classes\Option("Female", "Female"),
]);

define("_BOOLS", [
	new \App\Classes\Option("Yes", "Y"),
	new \App\Classes\Option("No", "N"),
]);


define("_USER_ROLES", [
	new \App\Classes\Option("Administrator", "Administrator"),
	new \App\Classes\Option("Breeder", "Breeder"),
	new \App\Classes\Option("Owner", "Owner"),
	new \App\Classes\Option("User", "User"),	
]);

define("_RAT_STATUS", [
	new \App\Classes\Option("Alive", "Alive"),
	new \App\Classes\Option("Deceased", "Deceased"),
	new \App\Classes\Option("Unknown", "Unknown"),	
]);

define("_COUNTRIES", [
new \App\Classes\Option("United Kingdom","GB"),
new \App\Classes\Option("Albania","AL"),
new \App\Classes\Option("Åland Islands","AX"),
new \App\Classes\Option("Algeria","DZ"),
new \App\Classes\Option("American Samoa","AS"),
new \App\Classes\Option("Andorra","AD"),
new \App\Classes\Option("Angola","AO"),
new \App\Classes\Option("Anguilla","AI"),
new \App\Classes\Option("Antarctica","AQ"),
new \App\Classes\Option("Antigua and Barbuda","AG"),
new \App\Classes\Option("Argentina","AR"),
new \App\Classes\Option("Armenia","AM"),
new \App\Classes\Option("Aruba","AW"),
new \App\Classes\Option("Australia","AU"),
new \App\Classes\Option("Austria","AT"),
new \App\Classes\Option("Azerbaijan","AZ"),
new \App\Classes\Option("Bahamas","BS"),
new \App\Classes\Option("Bahrain","BH"),
new \App\Classes\Option("Bangladesh","BD"),
new \App\Classes\Option("Barbados","BB"),
new \App\Classes\Option("Belarus","BY"),
new \App\Classes\Option("Belgium","BE"),
new \App\Classes\Option("Belize","BZ"),
new \App\Classes\Option("Benin","BJ"),
new \App\Classes\Option("Bermuda","BM"),
new \App\Classes\Option("Bhutan","BT"),
new \App\Classes\Option("Bolivia","BO"),
new \App\Classes\Option("Bonaire, Sint Eustatius and Saba","BQ"),
new \App\Classes\Option("Bosnia and Herzegovina","BA"),
new \App\Classes\Option("Botswana","BW"),
new \App\Classes\Option("Bouvet Island","BV"),
new \App\Classes\Option("Brazil","BR"),
new \App\Classes\Option("British Indian Ocean Territory","IO"),
new \App\Classes\Option("Brunei Darussalam","BN"),
new \App\Classes\Option("Bulgaria","BG"),
new \App\Classes\Option("Burkina Faso","BF"),
new \App\Classes\Option("Burundi","BI"),
new \App\Classes\Option("Cabo Verde","CV"),
new \App\Classes\Option("Cambodia","KH"),
new \App\Classes\Option("Cameroon","CM"),
new \App\Classes\Option("Canada","CA"),
new \App\Classes\Option("Cayman Islands","KY"),
new \App\Classes\Option("Central African Republic","CF"),
new \App\Classes\Option("Chad","TD"),
new \App\Classes\Option("Chile","CL"),
new \App\Classes\Option("China","CN"),
new \App\Classes\Option("Christmas Island","CX"),
new \App\Classes\Option("Cocos Islands","CC"),
new \App\Classes\Option("Colombia","CO"),
new \App\Classes\Option("Comoros","KM"),
new \App\Classes\Option("Congo (Democratic Republic)","CD"),
new \App\Classes\Option("Congo","CG"),
new \App\Classes\Option("Cook Islands","CK"),
new \App\Classes\Option("Costa Rica","CR"),
new \App\Classes\Option("Croatia","HR"),
new \App\Classes\Option("Cuba","CU"),
new \App\Classes\Option("Curaçao","CW"),
new \App\Classes\Option("Cyprus","CY"),
new \App\Classes\Option("Czechia","CZ"),
new \App\Classes\Option("Côte d'Ivoire","CI"),
new \App\Classes\Option("Denmark","DK"),
new \App\Classes\Option("Djibouti","DJ"),
new \App\Classes\Option("Dominica","DM"),
new \App\Classes\Option("Dominican Republic","DO"),
new \App\Classes\Option("Ecuador","EC"),
new \App\Classes\Option("Egypt","EG"),
new \App\Classes\Option("El Salvador","SV"),
new \App\Classes\Option("Equatorial Guinea","GQ"),
new \App\Classes\Option("Eritrea","ER"),
new \App\Classes\Option("Estonia","EE"),
new \App\Classes\Option("Eswatini","SZ"),
new \App\Classes\Option("Ethiopia","ET"),
new \App\Classes\Option("Falkland Islands","FK"),
new \App\Classes\Option("Faroe Islands","FO"),
new \App\Classes\Option("Fiji","FJ"),
new \App\Classes\Option("Finland","FI"),
new \App\Classes\Option("France","FR"),
new \App\Classes\Option("French Guiana","GF"),
new \App\Classes\Option("French Polynesia","PF"),
new \App\Classes\Option("French Southern Territories","TF"),
new \App\Classes\Option("Gabon","GA"),
new \App\Classes\Option("Gambia","GM"),
new \App\Classes\Option("Georgia","GE"),
new \App\Classes\Option("Germany","DE"),
new \App\Classes\Option("Ghana","GH"),
new \App\Classes\Option("Gibraltar","GI"),
new \App\Classes\Option("Greece","GR"),
new \App\Classes\Option("Greenland","GL"),
new \App\Classes\Option("Grenada","GD"),
new \App\Classes\Option("Guadeloupe","GP"),
new \App\Classes\Option("Guam","GU"),
new \App\Classes\Option("Guatemala","GT"),
new \App\Classes\Option("Guernsey","GG"),
new \App\Classes\Option("Guinea","GN"),
new \App\Classes\Option("Guinea-Bissau","GW"),
new \App\Classes\Option("Guyana","GY"),
new \App\Classes\Option("Haiti","HT"),
new \App\Classes\Option("Heard Island and McDonald Islands","HM"),
new \App\Classes\Option("Holy See","VA"),
new \App\Classes\Option("Honduras","HN"),
new \App\Classes\Option("Hong Kong","HK"),
new \App\Classes\Option("Hungary","HU"),
new \App\Classes\Option("Iceland","IS"),
new \App\Classes\Option("India","IN"),
new \App\Classes\Option("Indonesia","ID"),
new \App\Classes\Option("Iran","IR"),
new \App\Classes\Option("Iraq","IQ"),
new \App\Classes\Option("Ireland","IE"),
new \App\Classes\Option("Isle of Man","IM"),
new \App\Classes\Option("Israel","IL"),
new \App\Classes\Option("Italy","IT"),
new \App\Classes\Option("Jamaica","JM"),
new \App\Classes\Option("Japan","JP"),
new \App\Classes\Option("Jersey","JE"),
new \App\Classes\Option("Jordan","JO"),
new \App\Classes\Option("Kazakhstan","KZ"),
new \App\Classes\Option("Kenya","KE"),
new \App\Classes\Option("Kiribati","KI"),
new \App\Classes\Option("Korea South","KP"),
new \App\Classes\Option("Korea North)","KR"),
new \App\Classes\Option("Kuwait","KW"),
new \App\Classes\Option("Kyrgyzstan","KG"),
new \App\Classes\Option("Lao People's Democratic Republic","LA"),
new \App\Classes\Option("Latvia","LV"),
new \App\Classes\Option("Lebanon","LB"),
new \App\Classes\Option("Lesotho","LS"),
new \App\Classes\Option("Liberia","LR"),
new \App\Classes\Option("Libya","LY"),
new \App\Classes\Option("Liechtenstein","LI"),
new \App\Classes\Option("Lithuania","LT"),
new \App\Classes\Option("Luxembourg","LU"),
new \App\Classes\Option("Macao","MO"),
new \App\Classes\Option("Madagascar","MG"),
new \App\Classes\Option("Malawi","MW"),
new \App\Classes\Option("Malaysia","MY"),
new \App\Classes\Option("Maldives","MV"),
new \App\Classes\Option("Mali","ML"),
new \App\Classes\Option("Malta","MT"),
new \App\Classes\Option("Marshall Islands","MH"),
new \App\Classes\Option("Martinique","MQ"),
new \App\Classes\Option("Mauritania","MR"),
new \App\Classes\Option("Mauritius","MU"),
new \App\Classes\Option("Mayotte","YT"),
new \App\Classes\Option("Mexico","MX"),
new \App\Classes\Option("Micronesia","FM"),
new \App\Classes\Option("Moldova","MD"),
new \App\Classes\Option("Monaco","MC"),
new \App\Classes\Option("Mongolia","MN"),
new \App\Classes\Option("Montenegro","ME"),
new \App\Classes\Option("Montserrat","MS"),
new \App\Classes\Option("Morocco","MA"),
new \App\Classes\Option("Mozambique","MZ"),
new \App\Classes\Option("Myanmar","MM"),
new \App\Classes\Option("Namibia","NA"),
new \App\Classes\Option("Nauru","NR"),
new \App\Classes\Option("Nepal","NP"),
new \App\Classes\Option("Netherlands","NL"),
new \App\Classes\Option("New Caledonia","NC"),
new \App\Classes\Option("New Zealand","NZ"),
new \App\Classes\Option("Nicaragua","NI"),
new \App\Classes\Option("Niger","NE"),
new \App\Classes\Option("Nigeria","NG"),
new \App\Classes\Option("Niue","NU"),
new \App\Classes\Option("Norfolk Island","NF"),
new \App\Classes\Option("Northern Mariana Islands","MP"),
new \App\Classes\Option("Norway","NO"),
new \App\Classes\Option("Oman","OM"),
new \App\Classes\Option("Pakistan","PK"),
new \App\Classes\Option("Palau","PW"),
new \App\Classes\Option("Palestine, State of","PS"),
new \App\Classes\Option("Panama","PA"),
new \App\Classes\Option("Papua New Guinea","PG"),
new \App\Classes\Option("Paraguay","PY"),
new \App\Classes\Option("Peru","PE"),
new \App\Classes\Option("Philippines","PH"),
new \App\Classes\Option("Pitcairn","PN"),
new \App\Classes\Option("Poland","PL"),
new \App\Classes\Option("Portugal","PT"),
new \App\Classes\Option("Puerto Rico","PR"),
new \App\Classes\Option("Qatar","QA"),
new \App\Classes\Option("Republic of North Macedonia","MK"),
new \App\Classes\Option("Romania","RO"),
new \App\Classes\Option("Russian Federation","RU"),
new \App\Classes\Option("Rwanda","RW"),
new \App\Classes\Option("Réunion","RE"),
new \App\Classes\Option("Saint Barthélemy","BL"),
new \App\Classes\Option("Saint Helena, Ascension and Tristan da Cunha","SH"),
new \App\Classes\Option("Saint Kitts and Nevis","KN"),
new \App\Classes\Option("Saint Lucia","LC"),
new \App\Classes\Option("Saint Martin","MF"),
new \App\Classes\Option("Saint Pierre and Miquelon","PM"),
new \App\Classes\Option("Saint Vincent and the Grenadines","VC"),
new \App\Classes\Option("Samoa","WS"),
new \App\Classes\Option("San Marino","SM"),
new \App\Classes\Option("Sao Tome and Principe","ST"),
new \App\Classes\Option("Saudi Arabia","SA"),
new \App\Classes\Option("Senegal","SN"),
new \App\Classes\Option("Serbia","RS"),
new \App\Classes\Option("Seychelles","SC"),
new \App\Classes\Option("Sierra Leone","SL"),
new \App\Classes\Option("Singapore","SG"),
new \App\Classes\Option("Sint Maarten","SX"),
new \App\Classes\Option("Slovakia","SK"),
new \App\Classes\Option("Slovenia","SI"),
new \App\Classes\Option("Solomon Islands","SB"),
new \App\Classes\Option("Somalia","SO"),
new \App\Classes\Option("South Africa","ZA"),
new \App\Classes\Option("South Georgia and the South Sandwich Islands","GS"),
new \App\Classes\Option("South Sudan","SS"),
new \App\Classes\Option("Spain","ES"),
new \App\Classes\Option("Sri Lanka","LK"),
new \App\Classes\Option("Sudan","SD"),
new \App\Classes\Option("Suriname","SR"),
new \App\Classes\Option("Svalbard and Jan Mayen","SJ"),
new \App\Classes\Option("Sweden","SE"),
new \App\Classes\Option("Switzerland","CH"),
new \App\Classes\Option("Syrian Arab Republic","SY"),
new \App\Classes\Option("Taiwan","TW"),
new \App\Classes\Option("Tajikistan","TJ"),
new \App\Classes\Option("Tanzania, United Republic of","TZ"),
new \App\Classes\Option("Thailand","TH"),
new \App\Classes\Option("Timor-Leste","TL"),
new \App\Classes\Option("Togo","TG"),
new \App\Classes\Option("Tokelau","TK"),
new \App\Classes\Option("Tonga","TO"),
new \App\Classes\Option("Trinidad and Tobago","TT"),
new \App\Classes\Option("Tunisia","TN"),
new \App\Classes\Option("Turkey","TR"),
new \App\Classes\Option("Turkmenistan","TM"),
new \App\Classes\Option("Turks and Caicos Islands","TC"),
new \App\Classes\Option("Tuvalu","TV"),
new \App\Classes\Option("Uganda","UG"),
new \App\Classes\Option("Ukraine","UA"),
new \App\Classes\Option("United Arab Emirates","AE"),
new \App\Classes\Option("United Kingdom","GB"),
new \App\Classes\Option("United States Minor Outlying Islands","UM"),
new \App\Classes\Option("United States of America","US"),
new \App\Classes\Option("Uruguay","UY"),
new \App\Classes\Option("Uzbekistan","UZ"),
new \App\Classes\Option("Vanuatu","VU"),
new \App\Classes\Option("Venezuela","VE"),
new \App\Classes\Option("Viet Nam","VN"),
new \App\Classes\Option("Virgin Islands","VG"),
new \App\Classes\Option("Virgin Islands U.S.","VI"),
new \App\Classes\Option("Wallis and Futuna","WF"),
new \App\Classes\Option("Western Sahara","EH"),
new \App\Classes\Option("Yemen","YE"),
new \App\Classes\Option("Zambia","ZM"),
new \App\Classes\Option("Zimbabwe","ZW")

]);

