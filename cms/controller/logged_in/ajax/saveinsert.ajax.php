<?php

	$saveInsert = new ActiveRecord("inserts");
	$saveInsert->waar("tag = '".$_POST['id']."'");
	
	if ($saveInsert->returnRows() == 0) {
		
		$saveInsert->emptyValues();
		$saveInsert->tag = $_POST['id'];
		$saveInsert->content = $_POST['content'];
		$saveInsert->onlyInRender = $_POST['onlyRender'];
		echo $saveInsert->insert();
		
	} else {
		
		$saveInsert->tag = $_POST['title'];
		$saveInsert->content = $_POST['content'];
		$saveInsert->onlyInRender = $_POST['onlyRender'];
		$saveInsert->update();
		
	}
	
?>