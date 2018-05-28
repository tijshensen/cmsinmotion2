<?php

	// update image in DB
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_POST['block_id']."'");
	$saveImage = json_decode($savePageBlock->content, true);
	$set = 0;
	
	$requestSecondParameter = $_POST['id']."_link";
	
	foreach($saveImage as $key => &$content) {
		
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
		$newItem['value'] = $_POST['link'];
		$saveImage[] = $newItem;
		
	}
	
	$saveImage = json_encode($saveImage);
	
	$savePageBlock->content = $saveImage;
	$savePageBlock->update();

?>