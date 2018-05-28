<?php

	$newBlock = new ActiveRecord("page_block");
	$newBlock->page_id = $_POST['page_id'];
	$newBlock->template_block_id = $_POST['block_id'];
	$newBlock->content = "{}";
	$newBlock->insert();

?>