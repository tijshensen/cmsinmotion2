<?php

	include("../view/logged_in/saas_install/navbar.view.php");
	if ($requestParameter == "") {
		include("../view/logged_in/saas_install/first.view.php");
	} elseif ($requestParameter == "install") {
		
		// Remove all templates, pages, page_blocks, template_blocks and reset the title
		$pageSettings = new ActiveRecord("settings");
		$pageSettings->waar("id = 1");
		if ($pageSettings->returnRows() == 0) {
			
			$pageSettings->id = 1;
			$pageSettings->siteTitle = $_POST['siteTitle'];
			$pageSettings->siteKey = $_POST['siteKey'];
			$pageSettings->template = 0;
			$pageSettings->insert();
			
		} else {
			
			$pageSettings->siteTitle = $_POST['siteTitle'];
			$pageSettings->siteKey = $_POST['siteKey'];
			$pageSettings->template = 0;
			$pageSettings->update();
			
		}
		
		$page = new ActiveRecord("page");
		$page->fetchAll();
		
		for ($loopPages = 0; $loopPages <= $page->returnRows(); $loopPages++) {
			
			$page->delete();
			$page->volgende();
			
		}
		
		$page = new ActiveRecord("page_block");
		$page->fetchAll();
		
		for ($loopPages = 0; $loopPages <= $page->returnRows(); $loopPages++) {
			
			$page->delete();
			$page->volgende();
			
		}
		
		$template = new ActiveRecord("template");
		$template->fetchAll();
		
		for ($loopTemplates = 0; $loopTemplates <= $template->returnRows(); $loopTemplates++) {
			
			$template->delete();
			$template->volgende();
			
		}
		
		$template = new ActiveRecord("template_block");
		$template->fetchAll();
		
		for ($loopTemplates = 0; $loopTemplates <= $template->returnRows(); $loopTemplates++) {
			
			$template->delete();
			$template->volgende();
			
		}
		
		$template = new ActiveRecord("template_js");
		$template->fetchAll();
		
		for ($loopTemplates = 0; $loopTemplates <= $template->returnRows(); $loopTemplates++) {
			
			$template->delete();
			$template->volgende();
			
		}


		$template = new ActiveRecord("template_css");
		$template->fetchAll();
		
		for ($loopTemplates = 0; $loopTemplates <= $template->returnRows(); $loopTemplates++) {
			
			$template->delete();
			$template->volgende();
			
		}
		
		$template = new ActiveRecord("template_sets");
		$template->fetchAll();
		
		for ($loopTemplates = 0; $loopTemplates <= $template->returnRows(); $loopTemplates++) {
			
			$template->delete();
			$template->volgende();
			
		}

		
		header("Location: ".base_path_rewrite."/saasinstall/".$requestAction."/user");
		
	} elseif ($requestParameter == "user") {
		
		include("../view/logged_in/saas_install/second.view.php");
		
	} elseif ($requestParameter == "user_install") {
		
		$newUser = new ActiveRecord("users");
		$newUser->voornaam = $_POST['voornaam'];
		$newUser->achternaam = $_POST['achternaam'];
		$newUser->emailadres = $_POST['emailadres'];
		$newUser->wachtwoord = sha1($_POST['password']."CMSLOGIN!1");
		$newUser->group_id = 1;
		$newUser->insert();
		
		header("Location: ".base_path_rewrite."/saasinstall/".$requestAction."/done");
		
	} elseif ($requestParameter == "done") {
		
		include("../view/logged_in/saas_install/done.view.php");
		
	}

?>