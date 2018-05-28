<?php

	$fetchTemplateBlock = new ActiveRecord("template_block");
	$checkID = explode("_", $_POST['id']);
	
	if (is_numeric($checkID[2])) {
		
		$fetchTemplateBlock->waar("id = '".$checkID[2]."'");
		
		$fetchPageBlock = new ActiveRecord("page_block");
		$fetchPageBlock->waar("id = '".$checkID[1]."'");
		
		$templater = new Templater($fetchTemplateBlock->content, $checkID[1]);
		$templater->setCSSContent($fetchPageBlock->css);
		echo '<input type="hidden" value="'.$checkID[1].'" id="pageBlockID" />';
		echo '<form id="currentCSSEditForm">';
		echo $templater->editCSS($fetchPageBlock->id, $fetchPageBlock->template_block_id);
		echo '</form>';
		
	} else {
		
		die("An error has occurred.");
		
	}
	

?>