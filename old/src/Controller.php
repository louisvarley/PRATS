<?php
namespace App;

use \App\Services\AuthenticationService as Authentication;
use \App\Services\NotificationService as Notifications;
use \App\Services\SessionService as Session;
use \App\Services\FilterService as Filter;

use \App\Services\OptionService as OptionService;
use \App\Services\PropertyService as PropertyService;

/**
 * Base controller
 *
 * 
 */
abstract class Controller
{

	public $post;
	public $get;
	public $put;
	public $delete;

    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];
	
    /**
     * Parameters for the title and description of this page
     * @var array
     */	
	public $page_data = ["title" => "", "description" => "", "nav" => []];
	
    /**
     * Notifications for nav bar
     * @var array
     */		
	public $notifications = [];

    /**
     * Page requires authentication
     * @var bool
     */
	protected $authentication = false;

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
		
		if(_IS_SETUP){
			$this->notifications = array('notifications' => Notifications::fetch());
		}
		
		$this->inputData();
		$this->navigation();
    }
	
	public function navigation(){
		
		require(DIR_APP . '/Navigation.php');
	}
	
	
	public function authenticationCheck(){
		
		
		if($this->authentication){

			if(!Authentication::loggedIn()){
			
				header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
				die();
			}
		}
		
	}

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';
		
        if (method_exists($this, $method)) {
			
			/* Handle Authentication Requests */
			$this->authenticationCheck();
			
			/* Update Last Activity Time */
			Session::activity();
			
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before()
    {
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after()
    {
    }
	
	public function inputData(){
		
		if($this->isPUT()){
			mb_parse_str(file_get_contents("php://input"),$this->put);
		}
		
		if($this->isPOST()){
			$this->post = $_POST;
		}		
		
		//$this->files = $_FILES;
		$this->get = $_GET;		
		
	}
	
	public function isGET(){
		if($_SERVER['REQUEST_METHOD'] == 'GET')
			return true;
	}
	
	public function isPUT(){
		if($_SERVER['REQUEST_METHOD'] == 'PUT')
			return true;		
	}
	
	public function isPOST(){
		if($_SERVER['REQUEST_METHOD'] == 'POST')
			return true;		
	}
	
	public function isDELETE(){
		if($_SERVER['REQUEST_METHOD'] == 'DELETE')
			return true;		
	}	

	public function requestMethod(){

		return $_SERVER['REQUEST_METHOD'];
	}
	
	public function render($template, $array = null){

		View::renderTemplate($template, array_merge(
				$this->route_params, //Comes from the Route
				$this->page_data, //Title and Description
				array('me' => Authentication::me()), //Logged In User
				$this->notifications, //Notifications
				array('build' => _BUILD), //Build	
				array('properties' => PropertyService::getAllProperties()), 				
				
				
				empty($array) ? [] : $array) //Additional
		);
		
	}

}
