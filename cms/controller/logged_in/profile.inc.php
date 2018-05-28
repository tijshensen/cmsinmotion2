<?php

	switch(strtolower($requestAction)) {
		
		case "edit":
			$myProfileUpdate = new ActiveRecord("users");
			$myProfileUpdate->waar("id = ".$_SESSION['id']);
			$myProfileUpdate->voornaam = $_POST['voornaam'];
			$myProfileUpdate->achternaam = $_POST['achternaam'];
			$myProfileUpdate->emailadres = $_POST['emailadres'];
			
			if ($_POST['password'] == $_POST['password2'] && $_POST['password'] != "") {
				
				$myProfileUpdate->wachtwoord = sha1($_POST['password']."THEMISLOGIN");
				$addFlash = "Your password has been updated as well.";
				
			}
			$myProfileUpdate->update();
			
			setFlash("Your account details have been saved. ".$addFlash);
			
			header("Location: ".base_path_rewrite."/profile");
			break;
		default:
			$myProfile = new ActiveRecord("users");
			$myProfile->query("SELECT users.*, groups.*, users.id as user_id FROM users LEFT JOIN groups ON users.group_id = groups.id WHERE users.id = ".$_SESSION['id']);
			
			include("../view/logged_in/profile.dashboard.view.php");
			break;
		
	}
	
?>