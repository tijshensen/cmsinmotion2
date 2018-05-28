<?php

	$lookupPage = new ActiveRecord("template");
	$lookupPage->waar("id = '".$_POST['id']."'");
	if ($lookupPage->returnRows() > 0) {
		
		$lookupPage->name = $_POST['title'];
		$lookupPage->core = $_POST['content'];
		$lookupPage->update();
		
	} else {
		
		$lookupPage->template_set_id = $_POST['tsi'];
		$lookupPage->name = $_POST['title'];
		$lookupPage->core = $_POST['content'];
		$lookupPage->insert();
		
	}

?>