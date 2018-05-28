<?php

	$addPageBlock = new ActiveRecord("template_block");

	$addPageBlock->template_id = $_POST['id'];
	$addPageBlock->name = $_POST['name'];
	$addPageBlock->content = $_POST['content'];
	echo $addPageBlock->insert();
	

?>