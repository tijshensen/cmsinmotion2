<?php

	// Load insert
	$loadInsert = new ActiveRecord("inserts");
	$loadInsert->waar("id = '".$_POST['id']."'");
	echo $loadInsert->tag.":_insertSplit_:".$loadInsert->content.":_renderSplit_:".$loadInsert->onlyInRender;


?>