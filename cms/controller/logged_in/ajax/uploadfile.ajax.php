<?php

foreach($_REQUEST as $requestParameter => $content) {
	
	if (strpos($requestParameter, "uploadfile") !== false) {
		
		$arrayKeyUpload = $requestParameter;
		$dbKey = explode("_", $arrayKeyUpload);
		$dbKey = $dbKey[1];
		
	}
	
}
 
include("../inc/fileupload.inc.php");

$upload_dir = './content/uploads';
$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'mp3', 'mp4', 'mov', 'wmv');

$Upload = new FileUpload($arrayKeyUpload);
$result = $Upload->handleUpload($upload_dir, $valid_extensions);

if (!$result) {
    echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));   
} else {

	echo $_REQUEST['pageID'];
	
	$savePageBlock = new ActiveRecord("page_block");
	$savePageBlock->waar("id = '".$_GET['postID']."'");
	
	print_r($_POST);
	
	$saveImage = json_decode($savePageBlock->content, true);
	
	$set = 0;
	
	$saveFilename = base_path_rewrite."/content/uploads/".$Upload->getFileName();
	
	foreach($saveImage as $key => &$content) {
		
		if ($content['name'] == $dbKey."_file") {
			
			$content['value'] = $saveFilename;
			$set = 1;
			
		}
		
	}
	
	if ($set == 0) {
		
		$newItem['name'] = $dbKey."_file";
		$newItem['value'] = $saveFilename;
		$saveImage[] = $newItem;
		
	}
	
	
	$saveImage = json_encode($saveImage);
	
	print_r($saveImage);	
	
	$savePageBlock->content = $saveImage;
	$savePageBlock->update();

    echo json_encode(array('success' => true, 'file' => $Upload->getFileName()));
}