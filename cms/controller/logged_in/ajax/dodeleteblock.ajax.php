<?php

	$checkID = explode("_", $_POST['block_id']);

	$deleteBlock = new ActiveRecord("page_block");
	$deleteBlock->waar("id = '".$checkID[1]."'");
	$deleteBlock->delete();
	
?>