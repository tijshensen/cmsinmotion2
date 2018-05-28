<?php

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: 0");

	if ($_GET['url'] == "sitemap.xml") {
		
		$fetch = file_get_contents("../cms/docroot/content/sitemap.xml");
		
	} else {
		
		if (file_exists("../cms/renders/infrastructure_old.json")) { 
			
			$oldNavigation = json_decode(file_get_contents("../cms/renders/infrastructure_old.json"), true);
			
		}
		$navigation = json_decode(file_get_contents("../cms/renders/infrastructure.json"), true);
		
		if ($navigation['CMSinMotionSettings']['multiLanguage'] == "1") {
			
			$getRealDomain = explode(".", $_SERVER['HTTP_HOST']);
			$getRealDomain = $getRealDomain[count($getRealDomain)-2] . "." . $getRealDomain[count($getRealDomain)-1];
			
			if ($navigation[$getRealDomain][$_GET['url']] != "" && $_GET['url'] != "") {
		
				
				if (strpos($_GET['url'], ".css") !== false && file_exists("../cms/renders/".$navigation[$_GET['url']].".css")) {
					
					header("Content-Type: text/css");
					$fetch = file_get_contents("../cms/renders/".$navigation[$_GET['url']].".css");
				
				} else {
				
					$fetch = file_get_contents("../cms/renders/".$navigation[$getRealDomain][$_GET['url']].".html");
		
				}
		
			} else {
		
				if ($oldNavigation[$getRealDomain][$_GET['url']] != "" && $_GET['url'] != "") {
		
					// Search array
					$foundKey = array_search($oldNavigation[$getRealDomain][$_GET['url']], $navigation[$getRealDomain]);
					
					if ($foundKey != "") { 
		
						include("../cms/settings.inc.php");
						header("HTTP/1.1 301 Moved Permanently"); 
						header("Location: ".base_path_site_url."/".$foundKey); 
						die();
		
					}
		
					$fetch = file_get_contents("../cms/renders/".$navigation[$getRealDomain]['defaultPage'].".html");
		
				} else {
				
					$fetch = file_get_contents("../cms/renders/".$navigation[$getRealDomain]['defaultPage'].".html");
		
				}
		
			}

			
		} else {
		
	
			if ($navigation[$_GET['url']] != "" && $_GET['url'] != "") {
		
				
				if (strpos($_GET['url'], ".css") !== false && file_exists("../cms/renders/".$navigation[$_GET['url']].".css")) {
					
					header("Content-Type: text/css");
					$fetch = file_get_contents("../cms/renders/".$navigation[$_GET['url']].".css");
				
				} else {
					
					$fetch = file_get_contents("../cms/renders/".$navigation[$_GET['url']].".html");
		
				}
		
			} else {
		
				if ($oldNavigation[$_GET['url']] != "" && $_GET['url'] != "") {
		
					// Search array
					$foundKey = array_search($oldNavigation[$_GET['url']], $navigation);
					
					if ($foundKey != "") { 
		
						include("../cms/settings.inc.php");
						header("HTTP/1.1 301 Moved Permanently"); 
						header("Location: ".base_path_site_url."/".$foundKey); 
						die();
		
					}
		
					$fetch = file_get_contents("../cms/renders/".$navigation['defaultPage'].".html");
		
				} else {
					
					$fetch = file_get_contents("../cms/renders/".$navigation['defaultPage'].".html");
		
				}
		
			}
		
		}
	
	}

	echo $fetch;
	
?>
