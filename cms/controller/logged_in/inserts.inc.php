<?php

	if ($requestSecondParameter == "commit") {
		
		$returnPath = "/inserts";
		
		switch ($requestAction) {
		
			case "delete":
				$insert = new ActiveRecord("inserts");
				$insert->waar("id = '".$requestParameter."'");
				$insert->delete();
				
				break;
		
			case "inserts":
				$insert = new ActiveRecord("inserts");
				$insert->tag = $_POST['tag'];
				$insert->content = $_POST['content'];
				$newInsertID = $insert->insert();
				$returnPath = "/inserts/edit/".$newInsertID;
				break;
		
		}
		
		header("Location: ".base_path_rewrite.$returnPath);
		die();
		
	} else {
		
		switch ($requestAction) {
			default:
				// Fetch all pages
				$inserts = new ActiveRecord("inserts");
				$inserts->fetchAll();
				$allInserts = $inserts->returnComplete();
				echo "<body class='cmsinmotion inserts'>";
				include("../view/logged_in/menu.view.php");
				include("../view/logged_in/inserts/inserts.dashboard.view.php");
				break;
			
		}
		
		include("../view/logged_in/inserts/inserts.js.view.php");
		
	}

?>