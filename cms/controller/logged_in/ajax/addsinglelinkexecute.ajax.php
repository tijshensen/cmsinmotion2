<?php

	// update link in DB
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_POST['block_id']."'");
	$saveLink = json_decode($savePageBlock->content, true);
	$set = 0;
	
	$requestSecondParameter = $_POST['id']."_link";
	
	foreach($saveLink as $key => &$content) {
		
		if ($content['name'] == $requestSecondParameter) {
			
			if ($_POST['internalLink'] != "") {
				$content['value'] = "#internalURI".$_POST['internalLink'];
			} else {
				$content['value'] = $_POST['link'];
			}
			$content['value_target'] = $_POST['link_target'];
			$content['value_title'] = $_POST['link_title'];
			$set = 1;
			
		}
		
	}
	
	if ($set == 0) {
		
		$newItem['name'] = $requestSecondParameter;
		if ($_POST['internalLink'] != "") {
			$newItem['value'] = "#internalURI".$_POST['internalLink'];
		} else {
			$newItem['value'] = $_POST['link'];
		}
		$saveLink[] = $newItem;
		
	}
	
	$saveLink = json_encode($saveLink);
	
	$savePageBlock->content = $saveLink;
	
	$savePageBlock->update();

?>