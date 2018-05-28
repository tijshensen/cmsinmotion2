<?php

	if ($requestSecondParameter == "commit") {
		
		$returnPath = "/pages";
		
		switch ($requestAction) {
		
			case "new":
				$page = new ActiveRecord("page");
				
				// Format to SEO name
				$seoName = preg_replace('/\s+/', ' ', $_POST['title']);
				preg_match_all("([A-z|a-z|0-9| ])", $seoName, $matches);
				$seoName = implode($matches[0]);
				$seoName = strtolower(str_replace(" ", "-", $seoName));
				
				$page->title = $_POST['title'];
				$page->menu_title = $_POST['title'];
				$page->page_seo_name = $seoName;
				$page->user_id = $_SESSION['id'];
				$page->created_at = date("Y-m-d H:i:s");
				$page->template_id = $_POST['template_id'];
				if ($globalSettings->isMultiLanguage == 1) {
				
					if ($_SESSION['language'] != "") {
					
						$page->language_id = $_SESSION['language']; 
						
					} else {
					
						$fetchDefaultLanguage = new ActiveRecord("languages");
						$fetchDefaultLanguage->waar("is_default = 1");
						if ($fetchDefaultLanguage->returnRows() == 0) {
							
							$page->language_id = 0;
							
						} else {
							
							$page->language_id = $fetchDefaultLanguage->id;
							
						}
						
					}
					
				}
				$newPageID = $page->insert();
				$returnPath = "/pages/edit/".$newPageID;
				break;
		
		}
		
		header("Location: ".base_path_rewrite.$returnPath);
		die();
		
	} else {
		
		switch ($requestAction) {
			case "savemetadata":
				$pageLookup = new ActiveRecord("page");
				$pageLookup->waar("id = '".$requestParameter."'");
				$pageLookup->title = $_POST['title'];
				$pageLookup->meta_description = $_POST['meta_description'];
				$pageLookup->update();
				$returnPath = "/pages/edit/".$pageLookup->id;
				header("Location: ".base_path_rewrite.$returnPath);
				break;
			case "render":
				if ($requestSecondParameter == "execute") {
					include("../view/logged_in/pages/pages.navbar.render.view.php");
					include("../view/logged_in/pages/pages.render.view.php");
				} else {
					include("../view/logged_in/pages/pages.navbar.render.view.php");
					$renderURL = base_path_rewrite."/pages/render/".$requestParameter."/execute";
					include("../view/logged_in/pages/pages.preparerender.view.php");
				}
				break;
			case "view":
				ob_clean();
				$page = new ActiveRecord("page");
				$page->waar("id = '".$requestParameter."'");
				// fetch all blocks
				$fetchBlocks = new ActiveRecord("template_block");
				$fetchBlocks->waar("template_id = '".$page->template_id."'");
				include("../view/logged_in/pages/pages.view.view.php");
				die();			
				break;
			case "edit":
				$page = new ActiveRecord("page");
				$page->waar("id = '".$requestParameter."'");
				// fetch all blocks
				$fetchBlocks = new ActiveRecord("template_block");
				$fetchBlocks->waar("template_id = '".$page->template_id."'");
				include("../view/logged_in/pages/pages.navbar.view.php");
				include("../view/logged_in/pages/pages.edit.view.php");
				break;
			case "new":
				// fetch all blocks
				$page = new stdClass();
				$page->title = 'New page';
				
				$fetchBlocks = new ActiveRecord("template_block");
				$fetchBlocks->waar("template_id = '".$requestParameter."'");
				include("../view/logged_in/pages/pages.navbar.view.php");
				include("../view/logged_in/pages/pages.new.view.php");
				break;
			default:
				
				if ($globalSettings->isMultiLanguage == 1) {
				
					
				
					$fetchLanguages = new ActiveRecord("languages");
					$fetchLanguages->fetchAll();
					$languages = $fetchLanguages->returnComplete();
					
					if ($_SESSION['language'] != "") {
						
						$pages = new ActiveRecord("page");
						$pages->waar("language_id = '".$_SESSION['language']."' ORDER BY prio ASC");
						$allPages = $pages->returnComplete();
						
						$fetchLanguage = new ActiveRecord("languages");
						$fetchLanguage->waar("id = '".$_SESSION['language']."'");
						$selectedLanguage = $fetchLanguage->name;
						
					} else { 
					
						$fetchDefaultLanguage = new ActiveRecord("languages");
						$fetchDefaultLanguage->waar("is_default = 1");
						if ($fetchDefaultLanguage->returnRows() == 0) {
							
							setFlash("This site is has a license for multilanguage, but is not prepared yet. <a href='#siteMultilanguageSetup' data-toggle='modal'>Click here to setup your first language</a>");
							
							$MLhasLicenseNoSupport = 1;
							
							// Fetch all pages for this language
							$pages = new ActiveRecord("page");
							$pages->fetchAll("prio ASC");
							$allPages = $pages->returnComplete();
						
						} else {
						
							// Fetch all pages for this language
							$pages = new ActiveRecord("page");
							$pages->waar("language_id = '".$fetchDefaultLanguage->id."' ORDER BY prio ASC");
							$allPages = $pages->returnComplete();
							
							$selectedLanguage = $fetchDefaultLanguage->name;
						
						}
					
					}
				
				
				} else {
					
					// Fetch all pages
					$pages = new ActiveRecord("page");
					$pages->fetchAll("prio ASC");
					$allPages = $pages->returnComplete();
				
					
				}
				
				// Fetch all pageTypes
				$allPageTypes = new ActiveRecord("template");
				$allPageTypes->waar("template_set_id = '".$globalSettings->template."'");
				$pageTypes = $allPageTypes->returnComplete(true);
				
				foreach($pageTypes as &$changePageType) {
					
					$changePageType['name'] = str_replace(".html", "", $changePageType['name']);
					$changePageType['name'] = str_replace("_", " ", $changePageType['name']);
					$changePageType['name'] = ucfirst($changePageType['name']);
					
				}
				
				$pages->fetchAll("prio ASC");
				$allPagesWithID = $pages->returnComplete(true);			
				include("../view/logged_in/navbar.inc.php");
				include("../view/logged_in/pages/pages.dashboard.view.php");
				break;
			
		}
		
	}

?>