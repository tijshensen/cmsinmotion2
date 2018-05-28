<?php

	if ($_SESSION['rights']['accessAdvanced'] == 0) {
		
		setFlash("You don't have access to this module.");
		header("Location: ".base_path_rewrite);
		die();
		
	}

	if ($requestThirdParameter == "commit") {
		
		$returnPath = "/administration";
		
		switch(strtolower($requestAction)) {
    		
    		case "pagetypedelete":
    		    $template = new ActiveRecord("template");
    		    $template->waar("id = '".$requestParameter."'");
    		    $template->delete();
    		    
    		    setFlash("The pagetype ".$template->name." was deleted!");
    		    header("Location: ".base_path_rewrite."/administration/templates/".$template->template_set_id);
    		    break;
    		
			case "load":
			
				echo "<br /><br /><br />";
				
				echo "<div class='row container'><div class='col-sm-9'>";
				echo "<h2>CMSinMotion Module Import</h2>";
				include("../inc/jshrink.inc.php");
				include("../inc/cssmin.inc.php");
				//array_map('unlink', glob("temp/*"));
				// Upload the zip archive to a temporary directory
				move_uploaded_file($_FILES["loadModule"]["tmp_name"], "temp/" . $_FILES["loadModule"]["name"]);
				
				$zip = new ZipArchive;
				$zip->open('temp/'.$_FILES["loadModule"]["name"]);
				$zip->extractTo('./temp/');
				$zip->close();
				
				$templateName = explode(".zip", $_FILES['loadModule']['name'])[0];
				
				if (!is_dir("temp/".$templateName)) {
				
					echo "Error during import: Template file was not valid.";
					die();
				
				}
				
				echo "<strong>Clearing old content directory!</strong><br />";
				recursiveDelete("content/");
				echo "-Done<br /><br />";
				
				echo "<strong>Creating content directory!</strong><br />";
				mkdir("content");
				echo "-Done<br /><br />";
								
				// Scan the temporary directory for templates
				$entireDirectoryTemp = scandir("temp/".$templateName);
				
				$newTemplateSet = new ActiveRecord("template_sets");
				$newTemplateSet->name = ucfirst($templateName);
				$newTemplateSetID = $newTemplateSet->insert();
				
				foreach($entireDirectoryTemp as $file) {
				
					if (strpos($file, "inc.menu.php") !== false) {
						
						// Hoofdmenu
						echo "<strong>Found Menufile</strong><br />";
						$menuFile = file_get_contents("temp/".$templateName."/".$file);
						echo "-Scheduled for saving<br /><br />";
						
					}
					
					if (strpos($file, "inc.submenu.php") !== false) {
					
						// Submenu
						echo "<strong>Found Submenufile</strong><br />";
						$submenuFile = file_get_contents("temp/".$templateName."/".$file);
						echo "-Scheduled for saving<br /><br />";
						
					}
					
					if (strpos($file, ".html") !== false) {
						
						echo "<strong>Starting import on HTML template ".$file."</strong><br />";
						
						// This is a template file
						$newTemplate = new ActiveRecord("template");
						$newTemplate->template_set_id = $newTemplateSetID;
						$newTemplate->name = $file;
						$newTemplate->core = file_get_contents("temp/".$templateName."/".$file);
						echo "-Transforming relevant CSS<br />";
						
						preg_match_all('/rel="stylesheet".*?href="(.*?)"/im', $newTemplate->core, $stylesheetLookup);
												
						foreach($stylesheetLookup[1] as $cssFile){
							
							
							echo "--".$cssFile."<br />";
							
							$filename = explode("/", $cssFile);
							$filename = $filename[count($filename) - 1];
							
							if (strpos($cssFile, "://") === false) {
							
								// Check if stylesheets exist in the css folder
								if (!file_exists("temp/".$templateName."/".$cssFile)) {
									
									die("Fatal error during template import: css file ".$filename." was not found in the css folder.");
									
								} else {
									
									$saveCSS = new ActiveRecord("template_css");
									$result = CssMin::minify(file_get_contents("temp/css/".$filename));
									$saveCSS->content = $result;
									$saveCSS->name = sha1("temp/css/".$filename);
									$saveCSS->template_set_id = $newTemplateSetID;
									$savedCSSID = $saveCSS->insert();
									
									//echo "---Rewriting ".$cssFile." to ".base_path_rewrite."/content/".$cssFile."<br />";
									$newTemplate->core = str_replace($cssFile, base_path_rewrite."/content/".$cssFile, $newTemplate->core);
							
									
								}
							
							}
							
							
						}
						
						
						preg_match_all('/href="(.*?)".*?rel="stylesheet"/im', $newTemplate->core, $stylesheetLookup);
												
						foreach($stylesheetLookup[1] as $cssFile){
							
							
							echo "--".$cssFile."<br />";
							
							$filename = explode("/", $cssFile);
							$filename = $filename[count($filename) - 1];
							
							if (strpos($cssFile, "://") === false) {
							
								// Check if stylesheets exist in the css folder
								if (!file_exists("temp/".$templateName."/".$cssFile)) {
									
									die("Fatal error during template import: css file ".$filename." was not found in the css folder.");
									
								} else {
									
									$saveCSS = new ActiveRecord("template_css");
									$result = CssMin::minify(file_get_contents("temp/css/".$filename));
									$saveCSS->content = $result;
									$saveCSS->name = sha1("temp/css/".$filename);
									$saveCSS->template_set_id = $newTemplateSetID;
									$savedCSSID = $saveCSS->insert();
									
									//echo "---Rewriting ".$cssFile." to ".base_path_rewrite."/content/".$cssFile."<br />";
									$newTemplate->core = str_replace($cssFile, base_path_rewrite."/content/".$cssFile, $newTemplate->core);
							
									
								}
							
							}
							
							
						}

						
						echo "-Transforming relevant Javascript<br />";
						
						preg_match_all('/<script.*?src="(.*?)"/im', $newTemplate->core, $scriptLookup);
						foreach($scriptLookup[1] as $jsFile){
						
							
							echo "--".$jsFile."<br />";
							
							$filename = explode("/", $jsFile);
							$filename = $filename[count($filename) - 1];
							
							if (strpos($jsFile, "//") === false) {
							
								// Check if scripts exist in the js folder
								if (!file_exists("temp/".$templateName."/".$jsFile)) {
									
									die("Fatal error during template import: js file ".$filename." was not found in the js folder.");
									
								} else {
									
									$minifiedCode = \JShrink\Minifier::minify(file_get_contents("temp/js/".$filename));
									$saveJS = new ActiveRecord("template_js");
									$saveJS->content = $minifiedCode;
									$saveJS->name = sha1("temp/css/".$filename);
									$saveJS->template_set_id = $newTemplateSetID;
									$savedJSID = $saveJS->insert();
									
									//=echo "---Rewriting ".$jsFile." to ".base_path_rewrite."/content/".$jsFile."<br />";
									$newTemplate->core = str_replace($jsFile, base_path_rewrite."/content/".$jsFile, $newTemplate->core);
								
								}
								
							}
							
						} 
						
						echo "-Transforming relevant Images<br />";
						
						preg_match_all('/<img.*?src="(.*?)"/im', $newTemplate->core, $imageLookup);
						
						foreach($imageLookup[1] as $imgFile){
						
							
							echo "--".$imgFile."<br />";
							
							$filename = explode("/", $imgFile);
							$filename = $filename[count($filename) - 1];
							
							if (strpos($imgFile, "://") === false) {
							
								// Check if scripts exist in the js folder
								if (!file_exists("temp/".$templateName."/".$imgFile)) {
									
									die("Fatal error during template import: img file ".$filename." was not found in the images folder.");
									
								} else {
									
									if ($processedImage[$imgFile] != 1) {
								
										//=echo "---Rewriting ".$jsFile." to ".base_path_rewrite."/content/".$jsFile."<br />";
										$newTemplate->core = str_replace($imgFile, base_path_rewrite."/content/".$imgFile, $newTemplate->core);
								
									}
								
									$processedImage[$imgFile] = 1;
								
								}
								
							}
							
						}
						
						echo "<br />";
						$newTemplate->core = html_entity_decode($newTemplate->core);
						file_put_contents("temp/".$templateName."/".$file, $newTemplate->core);
						$breakTemplate = new Templater("temp/".$templateName."/".$file);
						
						$newTemplate->core = $breakTemplate->strippedTemplate;
						$templateID = $newTemplate->insert();
						
						echo "<strong>Inserting pageblocks</strong><br />";
						
						// Insert page blocks	
						foreach ($breakTemplate->templatePage_blocks as $repeatKey => $repeatBlock) {
						
							foreach ($repeatBlock[0] as $key => $templateBlock) {
							
								$newTemplateBlock = new ActiveRecord("template_block");
								$newTemplateBlock->template_id = $templateID;
								$newTemplateBlock->name = $breakTemplate->templatePage_blocks_metadata_name[$repeatKey][$key];
								$newTemplateBlock->repeatable = $breakTemplate->templatePage_blocks_metadata_repeatable[$repeatKey][$key];
								$newTemplateBlock->default = $breakTemplate->templatePage_blocks_metadata_default[$repeatKey][$key];
								$newTemplateBlock->content = $breakTemplate->templatePage_blocks[$repeatKey][2][$key];
								$newTemplateBlock->repeat_id = $repeatKey;
								$newTemplateBlock->insert();
								
							} 
							
						}
						
						echo "<br />";
						
						
						
					} else {
					
						if(is_dir("temp/".$templateName."/".$file) && $file != "." && $file != ".." && $file != ".DS_Store" && $file != "__MACOSX") {
						
							$createMoveArray['source'] 		= "temp/".$templateName."/".$file;
							$createMoveArray['destination'] = "content/".$file;
							$moveArray[] = $createMoveArray;
							echo "Scheduling moving other content to content-directory: ".$file."<br /><br />";
 
							
						}
					
					}
					
				}
				
				if (file_exists("temp/".$templateName."/logic.php")) {
				    $createMoveArray['source'] 		= "temp/".$templateName."/logic.php";
					$createMoveArray['destination'] = "content/logic.php";
				}
				
				echo "<strong>Saving menu and submenu</strong><br />";
				$newTemplateSet->waar("id = '".$newTemplateSetID."'");
				$newTemplateSet->menu = $menuFile;
				$newTemplateSet->submenu = $submenuFile;
				$newTemplateSet->update();
				echo "<br />";
				
				echo "<strong>Moving miscellaneous content to content-directory</strong><br />";
				foreach ($moveArray as $moveFile) {
					
					echo "-Source: ".$moveFile['source'].", destination: ".$moveFile['destination']."<br />";
					// Move to docroot
					
					if (file_exists($moveFile['source'])) {
					
						$result = rename($moveFile['source'], $moveFile['destination']);
						
						if ($result == false ) { 
						
							echo( "-Fatal error during moving<br />"); 
							$errors= error_get_last();
							dprint_r($errors);
							die();
							
						} else {
							
							echo "-Move successful<br />";
							
						}
					
					} else {
						
						echo "-File not found?<br />";
						
					}
					
				}
				
				echo "Creating images directory<br/ >";
				mkdir("content/images");
				
				echo "Creating uploads directory<br/ >";
				mkdir("content/uploads");
				
				echo "<h1>Import succeeded.</h1>";
				$returnPath = "/administration/sitesettings";
				break;
			case "sitesettings":
				$settings = new ActiveRecord("settings");
				$settings->waar("id = 1");
				$settings->siteTitle = $_POST['siteTitle'];
				$settings->template = $_POST['template'];
				$settings->update();
				setFlash("The site settings have been modified.");
				$returnPath = "/administration/sitesettings";
				
				break;
			case "users":
				switch($requestParameter) {
					case "new":
						$newUser = new ActiveRecord("users");
						$newUser->voornaam = $_POST['voornaam'];
						$newUser->achternaam = $_POST['achternaam'];
						$newUser->emailadres = $_POST['emailadres'];
						$newUser->group_id = $_POST['group_id'];
						$newUser->wachtwoord = sha1($_POST['password']."CMSLOGIN!1");
						$newUserID = $newUser->insert();
						setFlash("The user ".$newUser->emailadres." has been created.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." created user ".$newUserID, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "users.id" => $newUserID)));
						
						$returnPath = "/administration/users";
						break;
					case "edit":
						$updateUser = new ActiveRecord("users");
						$updateUser->waar("id = ".$requestSecondParameter);
						$updateUser->voornaam = $_POST['voornaam'];
						$updateUser->achternaam = $_POST['achternaam'];
						$updateUser->emailadres = $_POST['emailadres'];
						$updateUser->group_id = $_POST['group_id'];
						if ($_POST['password'] == $_POST['password2'] && $_POST['password'] != "") {
							
							$updateUser->wachtwoord = sha1($_POST['password']."CMSLOGIN!1");
							
						}
						$updateUser->update();
						setFlash("The user ".$updateUser->emailadres." has been updated.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." updated user ".$requestSecondParameter, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "users.id" => $requestSecondParameter)));
						
						$returnPath = "/administration/users";
						break;
					case "delete":
						$removeUser = new ActiveRecord("users");
						$removeUser->waar("id = ".$requestSecondParameter);
						$removeUser->delete();
						
						setFlash("The user ".$removeUser->emailadres." is deleted.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." deleted user ".$requestSecondParameter, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "users.id" => $requestSecondParameter)));
						
						$returnPath = "/administration/users";
						break;
				}
				break;
			case "groups":
			
				if ($_SESSION['rights']['accessAdvanced'] == 0) {
		
					setFlash("You don't have access to this module.");
					header("Location: ".base_path_rewrite);
					die();
					
				}
			
				switch($requestParameter) {
					case "new":
						$newGroup = new ActiveRecord("groups");
						$newGroup->name = $_POST['name'];
						if ($_POST['accessAdvanced'] == 1) {
							
							$newGroup->accessAdvanced = 1;
							
						} else {
							
							$newGroup->accessAdvanced = 0;
							
						}
						$newGroupID = $newGroup->insert();
						setFlash("The group ".$newGroup->name." has been created.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." created group ".$newGroupID, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "groups.id" => $newGroupID)));
						
						$returnPath = "/administration/groups";
						break;

					case "edit":
						$updateGroup = new ActiveRecord("groups");
						$updateGroup->waar("id = ".$requestSecondParameter);
						$updateGroup->name = $_POST['name'];
						if ($_POST['accessAdvanced'] == 1) {
							
							$updateGroup->accessAdvanced = 1;
							
						} else {
							
							$updateGroup->accessAdvanced = 0;
							
						}
						$updateGroup->update();
						setFlash("The group ".$updateGroup->name." has been updated.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." updated group ".$requestSecondParameter, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "groups.id" => $requestSecondParameter)));
						
						$returnPath = "/administration/groups";
						break;
					case "delete":
						$removeGroup = new ActiveRecord("groups");
						$removeGroup->waar("id = ".$requestSecondParameter);
						$removeGroup->delete();
						
						setFlash("The group	".$updateGroup->name." is deleted.");
						
						writeToLog("INFO", "ADMIN", $_SESSION['id']." deleted group ".$requestSecondParameter, 0, json_encode(array("session-db-id" => $_SESSION['session-db-id'], "groups.id" => $requestSecondParameter)));
						
						$returnPath = "/administration/groups";
						break;
				}
				break;
		}
		
		header("Location: ".base_path_rewrite.$returnPath);
		
	} else {
		
		include("../view/logged_in/menu.view.php");
		
		echo "<body class='cmsinmotion'>";
		echo "	<div class='container-fluid'>";
		echo "		<div class='row'>";
		echo "			<div class='col-sm-3 col-md-2 sidebar'>";
		
		include("../view/logged_in/administration/administration.submenu.view.php");

		echo "			</div>";
		echo "			<div class='col-sm-9 col-md-10'>";
		echo "				<div class='administration'>";
		
		switch(strtolower($requestAction)) {
			case "sitemap":
				if ($requestParameter == "save") {
					move_uploaded_file($_FILES["loadSitemap"]["tmp_name"], "temp/" . "sitemap.xml");
					rename("temp/sitemap.xml", "content/sitemap.xml");
					setFlash("The sitemap has been updated.");
					header("Location: ".base_path_rewrite."/administration/sitemap");
				} else {
					include("../view/logged_in/administration/administration.sitemap.view.php");					
				}
				break;
			case "languages":
				if ($globalSettings->isMultiLanguage == 1) {	
					if ($requestParameter == "delete") { 
						$languagesShow = new ActiveRecord("languages");
						$languagesShow->waar("id = ".$requestSecondParameter);
					
						include("../view/logged_in/administration/administration.languages.delete.view.php");
					} elseif ($requestParameter == "edit") { 
						$languagesShow = new ActiveRecord("languages");
						$languagesShow->waar("id = ".$requestSecondParameter);
						include("../view/logged_in/administration/administration.languages.edit.view.php");
					} elseif ($requestParameter == "do_delete") { 
						$languagesDelete = new ActiveRecord("languages");
						$languagesDelete->waar("id = ".$requestSecondParameter);
						$languagesDelete->delete();
						
						setFlash("The language ".$languagesEdit->name." has been deleted.");
						header("Location: ".base_path_rewrite."/administration/languages");
					} elseif ($requestParameter == "do_edit") { 
						$languagesEdit = new ActiveRecord("languages");
						$languagesEdit->waar("id = ".$requestSecondParameter);
						$languagesEdit->name = $_POST['name'];
						$languagesEdit->url = $_POST['url'];
						$languagesEdit->update();
						
						setFlash("The language ".$languagesEdit->name." has been updated.");
						header("Location: ".base_path_rewrite."/administration/languages");
					} else {
						$languagesShow = new ActiveRecord("languages");
						$languagesShow->fetchAll();
						$languages = $languagesShow->returnComplete();
						
						include("../view/logged_in/administration/administration.languages.view.php");
					}
				} else {
					header("Location: ".base_path_rewrite."/");
				}				
				break;
			case "changelanguage":
				$_SESSION['language'] = $requestParameter;
				header("Location: ".base_path_rewrite);
				break;
			case "add_multilingual":
				$languageAdd = new ActiveRecord("languages");
				$languageAdd->name = $_POST['firstLanguage'];
				$languageAdd->site_title = $_POST['sitetitle'];
				$languageAdd->is_default = 0;
				$languageAdd->url = $_POST['url'];
				$languageID = $languageAdd->insert();
				$_SESSION['language'] = $languageID;
				setFlash("You have successfully added a new language. Please use the menu on the right to navigate through your sites languages.");
				header("Location: ".base_path_rewrite);
				break;
			
			case "setup_multilingual":
				$languageAdd = new ActiveRecord("languages");
				$languageAdd->name = $_POST['firstLanguage'];
				$languageAdd->site_title = $globalSettings->siteTitle;
				$languageAdd->is_default = 1;
				$languageAdd->url = $_POST['url'];
				$languageID = $languageAdd->insert();
				
				$pages = new ActiveRecord("page");
				$pages->query("UPDATE page SET language_id = '".$languageID."'");
				
				setFlash("You have successfully initialized the multilingual site license. You will no longer receive this message. Please use the menu on the right to navigate through your sites languages.");
				header("Location: ".base_path_rewrite);
				break;
			case "templates":
				if ($requestParameter == "blockedit") { 
					$templateBlockLookup = new ActiveRecord("template_block");
					$templateBlockLookup->waar("id = '".$requestSecondParameter."'");					
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("id = '".$templateBlockLookup->template_id."'");
					include("../view/logged_in/administration/administration.blockeditor.view.php");
				} elseif ($requestParameter == "blockdelete") {
					$templateBlockLookup = new ActiveRecord("template_block");
					$templateBlockLookup->waar("id = '".$requestSecondParameter."'");
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("id = '".$templateBlockLookup->template_id."'");
					include("../view/logged_in/administration/administration.blockdelete.view.php");
				} elseif ($requestParameter == "do_blockdelete") {
					$templateBlockLookup = new ActiveRecord("template_block");
					$templateBlockLookup->waar("id = ".$requestSecondParameter);
					$templateBlockLookup->delete();
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("id = '".$templateBlockLookup->template_id."'");
						
					setFlash("The block ".$templateBlockLookup->name." has been deleted.");
					header("Location: ".base_path_rewrite."/administration/templates/".$templateLookup->template_set_id);

				} elseif ($requestParameter == "pageedit") {
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("id = '".$requestSecondParameter."'");
					include("../view/logged_in/administration/administration.pageeditor.view.php");
				} elseif ($requestParameter == "pageadd") {
					$templateSetID = $requestSecondParameter;
					include("../view/logged_in/administration/administration.pageeditor.view.php");
				} elseif ($requestParameter == "blockadd") {
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("id = '".$requestSecondParameter."'");
					include("../view/logged_in/administration/administration.blockadd.view.php");
				} elseif ($requestParameter == "delete") {
					$templateLookup = new ActiveRecord("template_sets");
					$templateLookup->waar("id = '".$requestSecondParameter."'");
					include("../view/logged_in/administration/administration.deletetemplate.view.php");
				} elseif ($requestParameter == "do_templatedelete") {
					$templateLookup = new ActiveRecord("template_sets");
					$templateLookup->waar("id = '".$requestSecondParameter."'");
					$templateLookup->delete();
					header("Location: ".base_path_rewrite."/administration/load");
				} else {
					// Get the requested template
					$templateSetLookup = new ActiveRecord("template_sets");
					$templateSetLookup->waar("id = '".$requestParameter."'");
					
					$templateLookup = new ActiveRecord("template");
					$templateLookup->waar("template_set_id = '".$requestParameter."'");
					$allTemplates = $templateLookup->returnComplete();
									
					include("../view/logged_in/administration/administration.templateeditor.view.php");
					
				}
				
				break;
			case "load":
				$templateFetch = new ActiveRecord("template_sets");
				$templateFetch->fetchAll();
				$allTemplates = $templateFetch->returnComplete();
				
				include("../view/logged_in/administration/administration.load.view.php");
				break;
	
			case "sitesettings":
				// Fetch alle templates
				$templateFetch = new ActiveRecord("template_sets");
				$templateFetch->fetchAll();
				$templates = $templateFetch->returnComplete();
				$settingsFetch = new ActiveRecord("settings");
				$settingsFetch->waar("id = 1");
				$settings = $settingsFetch->activeRecord;
				include("../view/logged_in/administration/administration.sitesettings.view.php");
				break;
			
			case "users":
				switch($requestParameter) {
					case "new":
						$fetchGroups = new ActiveRecord("groups");
						$fetchGroups->fetchAll();
						$groupData = $fetchGroups->returnComplete();
						include("../view/logged_in/administration/administration.users.edit.view.php");
						break;
					case "edit":
						$fetchUser = new ActiveRecord("users");
						$fetchUser->waar("id = '".$requestSecondParameter."'");
						
						$fetchGroups = new ActiveRecord("groups");
						$fetchGroups->fetchAll();
						$groupData = $fetchGroups->returnComplete();
						include("../view/logged_in/administration/administration.users.edit.view.php");
						
						break;
		
					default:
						$fetchUsers = new ActiveRecord("users");
						$fetchUsers->query("SELECT  users.*, groups.name FROM users LEFT JOIN groups ON users.group_id = groups.id");
						$themisUsers = $fetchUsers->returnComplete();
						include("../view/logged_in/administration/administration.users.view.php");
						break;
						
				}
				break;
			case "groups":
				if ($_SESSION['rights']['accessAdvanced'] == 0) {
		
					setFlash("You don't have access to this module.");
					header("Location: ".base_path_rewrite);
					die();
					
				}

				switch($requestParameter) {
					case "new":
						include("../view/logged_in/administration/administration.groups.edit.view.php");
						break;
					case "edit":
						$fetchGroup = new ActiveRecord("groups");
						$fetchGroup->waar("id = '".$requestSecondParameter."'");
						include("../view/logged_in/administration/administration.groups.edit.view.php");
						break;
					default:
						$fetchGroups = new ActiveRecord("groups");
						$fetchGroups->fetchAll();
						$themisGroups = $fetchGroups->returnComplete();
						include("../view/logged_in/administration/administration.groups.view.php");
						break;
				}
				break;
			case "logging":
				$fetchLogging = new ActiveRecord("logging");
				if ($requestParameter == "filter") {
					
					$fetchLogging->query("SELECT * FROM logging WHERE severity LIKE '%".$_POST['severity']."%' AND module LIKE '%".$_POST['module']."%' AND action LIKE '%".$_POST['action']."%' AND user_id LIKE '%".$_POST['user_id']."%' AND session_id LIKE '%".$_POST['session_id']."%' ORDER BY log_timestamp DESC, log_timestamp_microtime DESC LIMIT 1000");
					
				} else {
				
					$fetchLogging->query("SELECT * FROM logging ORDER BY log_timestamp DESC, log_timestamp_microtime DESC LIMIT 100");
									
				}
				$loggingData = $fetchLogging->returnComplete();
				include("../view/logged_in/administration/administration.logging.view.php");
				break;
			default:
				$fetchCurrentSessions = new ActiveRecord("sessions");
				$fetchCurrentSessions->query("SELECT sessions.*, users.* FROM sessions LEFT JOIN users ON sessions.user_id = users.id WHERE sessions.timeout > '".date("Y-m-d H:i:s")."'");
				$currentSessions = $fetchCurrentSessions->returnComplete();
			
				include("../view/logged_in/administration/administration.dashboard.view.php");
				break;
			
		}
		
		echo "				</div>";
		echo "			</div>";
		echo "		</div>";
		echo "	</div>";
	}
	
?>