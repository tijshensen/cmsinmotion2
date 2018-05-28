<?php

	$deleteInsert = new ActiveRecord("inserts");
	$deleteInsert->waar("tag = '".$_POST['id']."'");
	$deleteInsert->delete();
    echo $deleteInsert->id;
    
?>