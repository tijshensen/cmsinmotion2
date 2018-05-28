<?php
	// MotionCMS 3 Instellingen

	// Database verbinding instellingen
	define("cmsinmotion_debug", 1);
	define("motioncms3_dbconf_db", "cmsinmotion_kinderdagverblijfkiekeboe");
	define("motioncms3_dbconf_username", "cim_kinder");
	define("motioncms3_dbconf_password", "ooFied9U");
	define("motioncms3_dbconf_server", "localhost");
	
	define("current_version", "1.0");
	
	// Debug mode - 0 = debugging aan, 1 = debugging uit
	define("motioncms3_debug", 0);
	
	// Directories
	define("motioncms3_directory_base", "./");
	define("motioncms3_directory_modules", motioncms3_directory_base."./modules/");
	define("base_path_rewrite", "http://".$_SERVER['HTTP_HOST']);
	define("base_path_site_url", "http://".str_replace("cms", "www", $_SERVER['HTTP_HOST']));
	
	define("base_path_admin", base_path_rewrite."/beheer/");
	
?>
