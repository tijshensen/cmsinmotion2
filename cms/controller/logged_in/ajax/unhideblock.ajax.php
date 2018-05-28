<?php

	$checkID = explode("_", $_POST['block_id']);

	$unhideBlock = new ActiveRecord("page_block");
	$unhideBlock->waar("id = '".$checkID[1]."'");
	$unhideBlock->hidden = 0;
	$unhideBlock->update();
	
?>