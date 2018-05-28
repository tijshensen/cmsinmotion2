<?php

	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_POST['pageID']."'");
	$savePageBlock->css = json_encode($_POST['data']);
	$savePageBlock->update();
	
?>