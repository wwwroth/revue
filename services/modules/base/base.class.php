<?php

/**
 * Base class for base methods.
 *
 * Consturct with request URL parameters and platform mode.
 * Consturct will break apart the URL into class and action,
 * set them as obj vars, and then connect to the database.
 *
 * @package - revueApi
 */ 
 
class base
{
	public $dbHost = "localhost";				// Database host.
	public $dbUser = "root";					// Datbase username.
	public $dbPass = "root";					// Database password.
	public $dbDatabase = "nf_revue";			// Database.
	
	private $revueMode; 						// Platform mode (development, live).
	private $routeUrl;							// URL parameters passed in from index.php.
	public $routeClass;							// Class parsed from routeUrl.
	public $routeAction;						// Action parsed from routeUrl.
	public $routeParams;						// URL parameters from routeUrl.
	public $db;									// db object for mysqli class.
	
	public function __construct($routeUrl, $revueMode) {
		$this->revueMode = $revueMode;		
		$regex = '~^/(?P<base>.*?)/(?P<class>.*?)/(?P<action>.*?)/(?P<params>.*?)/?$~';
		
		if (preg_match($regex, $routeUrl, $route)) {
			
			if ($route['base'] == "services") {
							
				// Our method of parsing the url query string doesn't like ?'s so lets strip them out
				// Then parse the query string and set the class var.			
				$route['params'] = str_replace("?", "", $route['params']);				
				parse_str($route['params'], $this->routeParams);
								
				$this->routeClass = $route['class'];
				$this->routeAction = $route['action'];	
								
			} else {
				$this->outputError(__FILE__, __LINE__, __METHOD__, __CLASS__);
			}
		} else {
			$this->outputError(__FILE__, __LINE__, __METHOD__, __CLASS__);
		}
	}
		
	public function validEmail($emailAddress) {
		if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;	
		}
	}
	
	public function outputError($file, $line, $method, $class) {
		
		echo json_encode(array("status" => "Error", "errmsg" => "Error message."));
				
		// If we're in development mode lets output where the error is on the screen.
		if($this->revueMode == "development") {
			echo "<br /><br />";
			echo "File: " . $file . "<br />";
			echo "Line: " . $line . "<br />";
			echo "Method: " . $method . "<br />";
			echo "Class: " . $class . "<br />";
		}
		
		exit;
	}
	
	public function outputSuccess() {	
		echo json_encode(array("status" => "OK"));
	}
	
	public function mysqliConnect() {
		$this->db = new Mysqlidb($this->dbHost, $this->dbUser, $this->dbPass, $this->dbDatabase);		
	}
	
}

?>