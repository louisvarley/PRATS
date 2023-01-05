<?php

namespace App\Controllers;

use \App\View;

use \App\Services\ToastService as Toast;
use \App\Services\UpdateService as Updater;
use \App\Services\EntityService as Entities;
use \App\Services\AuthenticationService as Authentication;

/**
 * Home controller
 *
 * 
 */
class Setup extends \App\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction(){
		
		/* Config File Exists, Update */
		if(_IS_SETUP){
			
			if(Authentication::loggedIn()){
				$this->update();
			}
			
			header('Location: /');
			
		}else{
			
			if($this->isPOST()){			 
				$this->install();
			}
		
			View::renderTemplate('Setup/index.html');
			
		}
				
		
	}
	
	public function install(){
		
		/* Delete Old Config */
		if(file_exists(_CONFIG_FILE))
		unlink(_CONFIG_FILE);

		/* Build New Config */
		$config = "<?php			

/* Database Config */
define('_DB_HOST','" . $this->post['db_host'] . "');
define('_DB_NAME','" . $this->post['db_name'] . "');
define('_DB_USER','" . $this->post['db_user'] . "');
define('_DB_PASSWORD','" . $this->post['db_password'] . "');
define('_DB_PORT','" . $this->post['db_port'] . "');
define('_DB_DUMPER','mysqldump');";
			
		file_put_contents(_CONFIG_FILE, $config);

		require(_CONFIG_FILE);
		
		
		/* Check DB Server Connection */
		if(!Entities::dbServerExists()){
			toast::throwError("Error...", "MySQL Connection failed: Host Server not Found");
			return ;
		}
		

		
		/* Create the Database */
		if(!Entities::dbExists()){
			Entities::createDatabase();
		}
				
		/* Generate all Schema and Proxies */
		Entities::generateSchema();
		Entities::generateProxies();
		Entities::generateStaticData();	
		
		/* Add new User */
		Entities::initialUserCheck();
			
		toast::throwSuccess("Ready to Rock and Roll...", "You are setup and ready to go");
		header('Location: /login');
		
	}
	
	
}
