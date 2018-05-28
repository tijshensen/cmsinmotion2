<?php

	ini_set('session.gc_maxlifetime', 3600);
	ini_set('memory_limit','256M');
	session_set_cookie_params(3600);
	session_start();
	ob_start();
	
	ini_set("display_errors", "on");
	error_reporting(E_ERROR | E_PARSE);
	
	include("../inc/version.inc.php");
	
	include("../settings.inc.php");
	
	include("../inc/functions.inc.php");
	
	include("../inc/activerecord5.inc.php");
	
	include("../inc/finediff.php");
	
	include("../inc/templater.inc.php");
		
	if ($_SESSION['loginRouting'] != "" && explode("/", $_GET['url'])[0] != "authenticate") {
	
		$routes = $_SESSION['loginRouting'];
		if ($_SESSION['XMLstreet'] != "") {
			
			$_SESSION['loginRouting'] = "";
		
		}
	
	} else {
		
		$routes = explode("/", $_GET['url']);
	
	}
	// Determine pathing
	$requestType = $routes[0];
	$requestAction = $routes[1];
	$requestParameter = $routes[2]; 
	$requestSecondParameter = $routes[3];
	$requestThirdParameter = $routes[4];
	$requestFourthParameter = $routes[5];
	
	include("../view/header.view.php");
	
	if ($_SESSION['XMLstreet'] == "") {
	
		if ($requestType != "") { 
			
			switch(strtolower($requestType)) {
				
				case "authenticate":
					include("../controller/authenticate.inc.php");	
					break;
				default:
					if ($_COOKIE['cookieCMSinMotion'] != "") {
						include("../controller/authenticate.inc.php");	
					} else { 	
						$_SESSION['loginRouting'] = $routes;
						include("../view/login.view.php");
					}
			}
			
		} else { 
			
			include("../view/login.view.php");
			
		}
		
	} else {
	
		// Update this sesison
		$updateSession = new ActiveRecord("sessions");
		$updateSession->waar("id = '".$_SESSION['session-db-id']."'");
		$updateSession->timeout = date("Y-m-d H:i:s", strtotime("now + 24 minutes"));
		$updateSession->update();
		
		// Fetch Site Settings
		$globalSettings = new ActiveRecord("settings");
		$globalSettings->waar("id = 1");
		
		if ($requestType != "") { 
		
			switch(strtolower($requestType)) {
				
				case "logout":
					include("../controller/logout.inc.php");	
					break;
				case "ajax":
					ob_clean();
					include("../controller/logged_in/ajax.inc.php");	
					die();
					break;
				case "pages":
					include("../controller/logged_in/pages.inc.php");	
					break;
				case "administration":
					include("../view/logged_in/navbar.inc.php");
					include("../controller/logged_in/administration.inc.php");
					break;
				case "profile":
					include("../view/logged_in/navbar.inc.php");
					include("../controller/logged_in/profile.inc.php");
					break;
				case "inserts":
					include("../controller/logged_in/inserts.inc.php");
					break;
				case "license":
					include("../controller/logged_in/license.inc.php");
					break;
				case "dev":
					include("../controller/logged_in/dev.inc.php");
					break;
				case "saasinstall":
					if ($requestAction == "0b33818e13c77b57611d7e12c0133a0ba7bf27bc") {
						include("../controller/logged_in/saas_install.php");
					} else {
						include("../view/logged_in/navbar.inc.php");
						include("../controller/logged_in/dashboard.controller.php");
					}
					break;
				default:
					header("Location: ".base_path_rewrite."/pages");
					die();
			
					
			}
		
		} else {
			
			header("Location: ".base_path_rewrite."/pages");
			die();
			
		}
		
	}
	
	include("../view/footer.view.php");
	
	ob_end_flush();
	
?>
