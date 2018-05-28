<?php

	$fetchTemplateBlock = new ActiveRecord("template_block");
	$checkID = explode("_", $_POST['id']);
	
	if (is_numeric($checkID[2])) {
		
		$fetchTemplateBlock->waar("id = '".$checkID[2]."'");
		
		$fetchPageBlock = new ActiveRecord("page_block");
		$fetchPageBlock->waar("id = '".$checkID[1]."'");
		
		$templater = new Templater($fetchTemplateBlock->content, $checkID[1]);
		$templater->setContent($fetchPageBlock->content);
		echo '<input type="hidden" value="'.$checkID[1].'" id="pageBlockID" />';
		echo '<form id="currentEditForm" method="POST" onsubmit="return false;">';
		echo $templater->edit();
		echo '</form>';

		
	} else {
		
		die("An error has occurred.");
		
	}
	

?>