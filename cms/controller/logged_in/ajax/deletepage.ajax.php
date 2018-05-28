<?php

	$pages = new ActiveRecord("page");
	$pages->waar("id = '".$_POST['menuID']."'");
	$pages->delete();

?>