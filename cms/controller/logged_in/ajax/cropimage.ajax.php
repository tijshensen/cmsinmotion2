<?php
 
if($_SERVER['REQUEST_METHOD'] == "POST") {

	$_POST['image'] = ".".str_replace(base_path_rewrite, "", $_POST['image']);
	
	$dst_x = 0;
	$dst_y = 0;
 
	$src_x = ceil($_POST['x']); // crop Start x
	$src_y = ceil($_POST['y']); // crop Srart y
 
	$src_w = ceil($_POST['w']); // $src_x + $dst_w
	$src_h = ceil($_POST['h']); // $src_y + $dst_h
 
	// resize image
	// currentlty the default width and height of image from aspect ratio
	$dst_w = $requestThirdParameter - 80;
	$dst_h = $requestFourthParameter - 80;
 
	// resize image variable
	$image = imagecreatetruecolor($dst_w,$dst_h);
	
	$type = exif_imagetype($_POST['image']);
	
	switch($type) {
		
		case IMAGETYPE_JPEG:
			$source = imagecreatefromjpeg($_POST['image']);
			break;
		case IMAGETYPE_PNG:
			$source = imagecreatefrompng($_POST['image']);
			break;
		case IMAGETYPE_GIF:
			$source = imagecreatefromgif($_POST['image']);
			break;
		
	}
	// crop
	imagecopyresampled($image, $source, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
 
	// image name
	$name = sha1(uniqid(mt_rand(), true));
 
	// location to save cropped image
	$url = './content/images/c'.$name.'.jpg';
 
	// save image
	$returner = imagejpeg($image, $url, 100);
	
	if ($returner == true) { $returner = "yes"; } else { $returner = "no"; }
 
	// update image in DB
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$requestParameter."'");
	$saveImage = json_decode($savePageBlock->content, true);
	$set = 0;
	
	$saveFilename = base_path_rewrite."/content/images/c".$name.".jpg";
	
	$requestSecondParameter = explode("_", $requestSecondParameter)[0];
	
	foreach($saveImage as $key => &$content) {
		
		if ($content['name'] == $requestSecondParameter) {
			
			$content['value'] = $saveFilename;
			$set = 1;
			
		}
		
	}
	
	if ($set == 0) {
		
		$newItem['name'] = $requestSecondParameter;
		$newItem['value'] = $saveFilename;
		$saveImage[] = $newItem;
		
	}
	
	
	$saveImage = json_encode($saveImage);
	
	$savePageBlock->content = $saveImage;
	$savePageBlock->update();
 
	// return URL
	$validation = array (
		'url'     => $saveFilename,
		'success' => $returner
	);
	echo json_encode($validation);
	
}