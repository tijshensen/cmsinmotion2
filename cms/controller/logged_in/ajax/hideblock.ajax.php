<?php

	$checkID = explode("_", $_POST['block_id']);

	$hideBlock = new ActiveRecord("page_block");
	$hideBlock->waar("id = '".$checkID[1]."'");
	$hideBlock->hidden = 1;	
	$hideBlock->update();
	
?>