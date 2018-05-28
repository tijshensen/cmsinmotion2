<?php

	
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_POST['pageID']."'");

	$saveContent = json_decode($savePageBlock->content, true);
	
	foreach ($_POST['data'] as &$content) {
		
		if (strpos($content['name'], "_") !== false) {
			
			$new = explode("_", $content['name']);
			$content['name'] = $new[0];
			
		}
		
	}
	
	foreach ($saveContent as &$contentLoop) {
		
		$hasSet = 0;
		
		foreach ($_POST['data'] as $content2) {
		
			if ($contentLoop['name'] == $content2['name']) {
				
				$hasSet = 1;
				
			}
		
		}
		
		if ($hasSet == 0) {
			
			$addArray['name'] = $contentLoop['name'];
			$addArray['value'] = $contentLoop['value'];
			$_POST['data'][] = $addArray;
			
		}
		
	}
		
	$savePageBlock->content = json_encode($_POST['data']);
	$savePageBlock->update();

	
?>