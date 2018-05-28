<?php
	
	foreach($_POST['id'] as $searchTag) {
		
		$insert = new ActiveRecord("inserts");
		$insert->waar("tag = '".mysql_real_escape_string($searchTag)."'");
		
		echo $insert->tag.":_SPLITTAG_:";
		echo $insert->content.":_SPLITFETCH_:";
		
	}

?>