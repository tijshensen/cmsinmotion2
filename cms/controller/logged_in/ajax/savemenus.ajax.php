<?php

	$pages = new ActiveRecord("page");
	
	$currentPrio = 0;
	
	foreach($_REQUEST['menuStruct'] as $field) {
		
		if(strpos($field['name'], "menu-item-db") !== false) {
			$currentPrio++;
			$pages->waar("id = '".mysql_real_escape_string($field['value'])."'");
			$pages->prio = $currentPrio;
			$pages->update();
		}
		
		if (strpos($field['name'], "menu-item-parent-id") !== false) {
			
			preg_match("/\[(.*?)\]/", $field['name'], $getID);
			$getID = $getID[1];
			$pages->waar("id = '".mysql_real_escape_string($getID)."'");
			$pages->parent_id = $field['value'];
			$pages->update();
			
		}
		
	}
	
	

?>