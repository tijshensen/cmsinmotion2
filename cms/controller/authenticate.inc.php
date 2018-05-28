<?php

	writeToLog("INFO", "AUTH", $_SERVER['REMOTE_ADDR']." attempted to login with ".$_POST['emailaddress'], 0, json_encode(array("emailadres" => $_POST['emailaddress'], "ip_address" => $_SERVER['REMOTE_ADDR'])));
	
	$allowCookieEnter = false;
	if ($_COOKIE['cookieCMSinMotion'] != "") {
	
		$checkPassword = new ActiveRecord("users");
		$checkPassword->waar("id = '".mysql_real_escape_string($_COOKIE['cookieCMSinMotion'])."'");
		if ($checkPassword->cookiekey == $_COOKIE['cookieKeyCMSinMotion']) {
		
			$allowCookieEnter = true;
			$_POST['username'] = $checkPassword->username;
		
		}
	
	} 
	
	$checkPassword = new ActiveRecord("users");
	$checkPassword->waar("emailadres = '".mysql_real_escape_string($_POST['emailaddress'])."'");

	if (sha1($_POST['password']."CMSLOGIN!1") == $checkPassword->wachtwoord || $allowCookieEnter == true) {
	
	
		if (isset($_POST['cookieSetter'])) {
			if ($checkPassword->cookiekey == "") {
				$checkPassword->cookiekey = sha1(time()."CMSinMotionIngelogdBlijven");
			}
			setcookie("cookieCMSinMotion", $checkPassword->id, time()+1209600);
			setcookie("cookieKeyCMSinMotion", $checkPassword->cookiekey, time()+1209600);
		
		}
		
		// Register this session
		$_SESSION['XMLstreet'] = $checkPassword->id;	
		$_SESSION['id'] = $checkPassword->id;	
		$_SESSION['voornaam'] = $checkPassword->voornaam;
		$_SESSION['acthernaam'] = $checkPassword->acthernaam;
		$_SESSION['volledigenaam'] = $checkPassword->voornaam." ".$checkPassword->achternaam;
		$_SESSION['firstUse'] = $checkPassword->firstuse;
		
		// Register this session
		$registerNewSession = new ActiveRecord("sessions");
		$registerNewSession->user_id = $checkPassword->id;
		$registerNewSession->ip_address = $_SERVER['REMOTE_ADDR'];
		$registerNewSession->last_login = date("Y-m-d H:i:s");
		$registerNewSession->timeout = date("Y-m-d H:i:s", strtotime("now + 24 minutes"));
		$sessionRegistered = $registerNewSession->insert();
		
		writeToLog("INFO", "AUTH", $_SERVER['REMOTE_ADDR']." login succeeded, assigned session id ".$sessionRegistered, 0, json_encode(array("session-db-id" => $sessionRegistered, "ip_address" => $_SERVER['REMOTE_ADDR'])));

		// My rights
		$sessionRights = new ActiveRecord("groups");
		$sessionRights->waar("id = '".$checkPassword->group_id."'");
		$_SESSION['rights'] = $sessionRights->activeRecord;
		
		$_SESSION['session-db-id'] = $sessionRegistered;
		
		$checkPassword->firstuse = 0;
		$checkPassword->update();
		
				
	} else {

		writeToLog("CRITICAL", "AUTH", $_SERVER['REMOTE_ADDR']." login failed with ".$_POST['emailaddress'], 0, json_encode(array("emailadres" => $_POST['emailaddress'], "ip_address" => $_SERVER['REMOTE_ADDR'])));
		
		setFlash("Your login credentials were incorrect. Please try again.".$checkPassword->wachtwoord, "danger");
		
	}
	
	header("Location: ".base_path_rewrite);
	
?>
