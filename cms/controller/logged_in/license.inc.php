<?php

	include("../view/logged_in/navbar.inc.php");
	echo "<br/><br/><br/><br/>This is version ".cmsinmotion_version.".";
	
	if ($globalSettings->isMultiLanguage == 1) {
		
		echo "<br />A multilingual site license has been installed.";
		
	} else {
		
		echo "<br />You do not have a multilingual site license.";
		
	}

?>