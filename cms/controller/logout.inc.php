<?php

	writeToLog("INFO", "AUTH", $_SERVER['REMOTE_ADDR']." destroyed session ".$_SESSION['session-db-id'], 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "ip_address" => $_SERVER['REMOTE_ADDR'])));

	$sessionUpdate = new ActiveRecord("sessions");
	$sessionUpdate->waar("id = ".$_SESSION['session-db-id']);
	$sessionUpdate->timeout = date("Y-m-d H:i:s");
	$sessionUpdate->update();	
	
	if ($_COOKIE['cookieCMSinMotion'] != "") {
		setCookie("cookieCMSinMotion", "", time()-3600);
		setCookie("cookieKeyCMSinMotion", "", time()-3600);
	}

	session_destroy();
	header("Location: ".base_path_rewrite);
	
?>