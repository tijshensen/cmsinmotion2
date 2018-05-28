<?php

	// update image in DB
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_POST['block_id']."'");
	$saveImage = json_decode($savePageBlock->content, true);
	$set = 0;
	
	$requestSecondParameter = $_POST['id']."_alt";
	
	foreach($saveImage as $key => &$content) {
		
		if ($content['name'] == $requestSecondParameter) {
			
			$content['value'] = $_POST['link'];
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