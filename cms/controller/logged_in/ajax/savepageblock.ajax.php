<?php

	$lookupPage = new ActiveRecord("template_block");
	$lookupPage->waar("id = '".$_POST['id']."'");
	if ($lookupPage->returnRows() > 0) {
		
		$lookupPage->name = $_POST['blockname'];
		$lookupPage->content = $_POST['content'];
		$lookupPage->update();
		
	}

?>