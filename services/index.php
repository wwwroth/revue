<?php

/*
 *	Request controller for Revue.
 *
 *	First file the web service hits. File breaks down the URL, validates class and method and calls appropriate action.
 *
 *	@package - revueApi
 *  @author - Phillip Roth <philliproth@gmail.com>
 *  
 */

include_once("config/config.php");
include_once("modules/base/mysqli.class.php");
include_once("modules/base/base.class.php");
include_once("modules/venue/venue.class.php");
include_once("modules/rating/rating.class.php");
include_once("modules/review/review.class.php");

// Break down the URL and get class and method.
$route = new base($_SERVER["REQUEST_URI"], $revueMode);

// Make sure class exists within system.
if (class_exists($route->routeClass)) {
	
	// Create a new object for requested class.
	$class = new $route->routeClass;
	
	// Had to concat a prefix due to 'list' being a reserved word.
	$action = $route->routeClass . $route->routeAction;
	
	// Make sure method exists within the requested class.
	if (method_exists($class, $action)) {
	
		// Call action!		
		$actionResponse = $class->$action($route->routeParams);		
		if($actionResponse === false) {
			$this->outputError(__FILE__, __LINE__, __METHOD__, __CLASS__);
		} else {
			echo json_encode($actionResponse);
		}
	
	}
} else {
	$route->outputError(__FILE__, __LINE__, __METHOD__, __CLASS__);	
}

?>