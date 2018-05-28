<?php
	
	$prio = 0;

	foreach($_POST['sortArray'] as $sortable) {
		
		$prio++;
		
		$id = explode("_", $sortable);
		$id = $id[1];
		
		$lookupPageBlock = new ActiveRecord("page_block");
		$lookupPageBlock->waar("id = '".$id."'");
		$lookupPageBlock->priority = $prio;
		$lookupPageBlock->update();
				
	}
?>