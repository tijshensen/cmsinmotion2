<?php

	$lookupMenu = new ActiveRecord("page");
	$lookupMenu->waar("id = '".$_POST['menuID']."'");
	if ($lookupMenu->returnRows() > 0) {
		
		if ($_POST['isDefault'] == 1) {
			
			$runQuery = new ActiveRecord("page");
			if ($globalSettings->isMultiLanguage == 1) {
				$runQuery->query("UPDATE page SET `isDefault` = 0 WHERE language_id = '".$lookupMenu->language_id."'");
			} else {
				$runQuery->query("UPDATE page SET `isDefault` = 0");
			}
			
		}
		
		$lookupMenu->isHidden = $_POST['isHidden'];
		$lookupMenu->isDefault = $_POST['isDefault'];
		$lookupMenu->page_seo_name = $_POST['menuLocation'];
		$lookupMenu->menu_title = $_POST['menuTitle'];
		$lookupMenu->meta_description = $_POST['menuMeta'];
		$lookupMenu->title = $_POST['menuPage'];
		$lookupMenu->update();
		
	}

?>