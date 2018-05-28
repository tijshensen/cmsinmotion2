<link href="<?=base_path_rewrite;?>/templates/<?=$globalSettings->template;?>/page.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?=base_path_rewrite;?>/css/editor.css"/>
<link rel="stylesheet" href="<?=base_path_rewrite;?>/css/font-awesome.min.css"/>
<link rel="stylesheet" href="<?=base_path_rewrite;?>/css/custom.css"/>

<script type='text/javascript' src="<?=base_path_rewrite;?>/js/themis.js"></script>
<script type='text/javascript'>
	var basePath = '<?=base_path_rewrite;?>';
</script>
<?php

	$fetchTemplate = new ActiveRecord("template");
	$fetchTemplate->waar("id = '".$page->template_id."'");
	
	if (strpos("jquery", $fetchTemplate->core) !== false) {
	
		echo '
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>';
			
	}
	
	// alle JS en CSS matches
	preg_match_all('/\[css_(.*?)]/i', $fetchTemplate->core, $cssMatches);
	
	preg_match_all('/\[js_(.*?)]/i', $fetchTemplate->core, $jsMatches);
	
	foreach($cssMatches[1] as $css) {
		
		$fetchCSS = new ActiveRecord("template_css");
		$fetchCSS->waar("id = '".$css."'");
		
		$compiledCSS = $compiledCSS."\n".$fetchCSS->content;
		
	}
		
	foreach($jsMatches[1] as $js) {
		
		$fetchJS = new ActiveRecord("template_js");
		$fetchJS->waar("id = '".$js."'");
		
		$compiledJS = $compiledJS."\n".$fetchJS->content;
		
	}
	
	
	$fetchPageBlocks = new ActiveRecord("page_block");
	$fetchPageBlocks->query("SELECT page_block.*, template_block.content as template, template_block.repeat_id FROM page_block LEFT JOIN template_block ON page_block.template_block_id = template_block.id WHERE page_block.page_id = '".$requestParameter."' ORDER BY page_block.`priority` ASC");
	
	if ($fetchPageBlocks->returnRows() == 0) {
		
		$fetchTemplateDefaultBlock = new ActiveRecord("template_block");
		$fetchTemplateDefaultBlock->query("SELECT *, id as template_block_id FROM template_block WHERE template_id = '".$globalSettings->template."' AND `default` = 1");
		$fetchPageBlocks = $fetchTemplateDefaultBlock;
		
		$thisBlockID = 0;
		
		foreach($fetchPageBlocks->returnComplete() as $pageBlock) {
		
			$thisBlockID++;
		
			$newPageBlock = new ActiveRecord("page_block");
			$newPageBlock->page_id = $requestParameter;
			$newPageBlock->template_block_id = $pageBlock['template_block_id'];
			$newPageBlock->content = "{}";
			$newPageBlock->priority = 0;
			$newPageBlock->insert();
		
		}
		
		$fetchPageBlocks = new ActiveRecord("page_block");
		$fetchPageBlocks->query("SELECT page_block.*, template_block.content as template, template_block.repeat_id FROM page_block LEFT JOIN template_block ON page_block.template_block_id = template_block.id WHERE page_block.page_id = '".$requestParameter."' ORDER BY page_block.`priority` ASC");
	
	} 
	
	foreach($fetchPageBlocks->returnComplete() as $pageBlock) {
			
		if ($pageBlock['hidden'] == 1) {
		
			$lastJavascript .= "$('#editBlock_".$pageBlock['id']."_".$pageBlock['template_block_id']."').css('opacity', '0.2');";
		
		} 		
		
		$currentBlock = '<div class="CMS_edit_container editBlock'.$pageBlock['id'].'" id="editBlock_'.$pageBlock['id'].'_'.$pageBlock['template_block_id'].'">'.$pageBlock['template']."</div>";
		
		
		$templater = new Templater($currentBlock, $pageBlock['id']);
		$templater->setContent($pageBlock['content']);
		
		$templater->viewCMS();
		$outPut[$pageBlock['repeat_id']] .= $templater->generate();
		
		if ($pageBlock['repeat_id'] > $maxRepeatID) { $maxRepeatID = $pageBlock['repeat_id']; }
		
		$css = json_decode($pageBlock['css'], true);
		
		foreach ($css as $cssValue) {
			
			if ($cssValue['value'] != "") {
			
				$generatedCSS .= ' .editBlock_'.$pageBlock['id'].' '.$cssValue['name'].' { '.$cssValue['value'].' }';
			
			}
			
		}
					
	}
	
	for ($repeats = 0; $repeats <= $maxRepeatID; $repeats++) {
	
		$outPut[$repeats] = "<div id='repeat".$repeats."' class='cmsinmotion_repeatblock'>".$outPut[$repeats]."</div>";
		
		$fetchTemplate->core = str_replace("[repeatBlock_".$repeats."]", $outPut[$repeats], $fetchTemplate->core);
		
	}
	
	// Zoeken van Inserts
	
	preg_match_all("/(\[.*?\])/", $fetchTemplate->core, $insertMatches);
	
	foreach($insertMatches[1] as $insertTag) {
		
		$insertLookup = new ActiveRecord("inserts");
		$insertLookup->waar("tag = '".$insertTag."'");
		if ($insertLookup->onlyInRender != 1) {
			$fetchTemplate->core = str_replace($insertTag, $insertLookup->content, $fetchTemplate->core);
		}
		
	}	
	
	echo $fetchTemplate->core;
	
	if (strpos("jquery", $fetchTemplate->core) === false) {
		
		echo '
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>';
		
	}
	
?>

<script type="text/javascript">

	<?=$compiledJS;?>

</script>

<style type="text/css">

	<?=$compiledCSS;?>

</style>



<menu style="position: absolute; top: 20px; left: 20px; z-index: 5000;" id="editorTools">
					
						<ul class="cms-inmotion-editor">
							<?php if ($fetchPageBlocks->returnRows() != 0) { ?>
							<li class="edit" onclick='editBlock(); return false;'>
								<i class="fa fa-edit fa-2x"></i> <span>Edit</span>
							</li>
							<li onclick='moveBlock(); return false;'>
								<i class="fa fa-arrows-v fa-2x"></i><span id="moveBlockText">Move mode off</span>
							</li>
							<li class="animate" onclick='hideBlock(); return false;'>
								<i class="fa fa-eye fa-2x"></i><span>Hide</span>
							</li>
							<li class="delete" onclick='deleteBlock(); return false;'>
								<i class="fa fa-trash-o fa-2x"></i><span>Delete</span>
							</li><?php } ?>
							<li class="add" onclick='addBlock(); return false;'>
								<i class="fa fa-plus fa-2x"></i><span>Add new block</span>
							</li>
						</ul>
					</menu>



<input type="hidden" name="currentEditBlock" id="currentEditBlock" value="" />

<script type="text/javascript">


	$('.CMS_edit_container').each(function(e) { 
		
		$(this).on('mouseenter', function() {
			
			blockOffset = $(this).children(":first").offset();
			
			$('#currentEditBlock').val($(this).attr('id'));
				
			$('#editorTools').css('top', blockOffset.top);
			$('#editorTools').css('left', blockOffset.left);
			
		});
		
	});
	
	function addBlock() {
		
		parent.addBlock(<?=$page->template_id;?>);
		
	}
	
	function editBlock() {
		
		parent.startEdit($('#currentEditBlock').val());
		parent.openSidebar();
		
	}
	
	function hideBlock() {
	
		if ($('#'+$('#currentEditBlock').val()).css('opacity') < 1) {
			
			$('#'+$('#currentEditBlock').val()).animate({opacity: 1});
			$.post(basePath+"/ajax/unhideBlock", {block_id: $('#currentEditBlock').val()});
			
		} else {
		
			$('#'+$('#currentEditBlock').val()).animate({opacity: 0.2});
			$.post(basePath+"/ajax/hideBlock", {block_id: $('#currentEditBlock').val()});
		
		}
		
	}
	
	function deleteBlock() {
	
		resultDelete = confirm("Do you really want to delete this block from the page? All content will be removed!");
		
		if (resultDelete == true) {
		
			$('#'+$('#currentEditBlock').val()).hide();
			$.post(basePath+"/ajax/doDeleteBlock", {block_id: $('#currentEditBlock').val()});
			
		}
			
	}
	
	function moveBlock() {
		
		if ($('#moveBlockText').html() == "Move mode off") {
			$('#moveBlockText').html("Move mode on");
			$('.cmsinmotion_repeatblock').each(function() { 
				
				$(this).sortable({ stop: function() { saveCurrentSortOrder() } } );
				$(this).sortable("enable", { stop: function() { saveCurrentSortOrder() } } );
					
			 });
			
			
		} else {
			$('#moveBlockText').html("Move mode off");
			$('.cmsinmotion_repeatblock').each(function() { 
				
				$(this).sortable("disable");
					
			 });

			
		}
		
	}
	
	function saveCurrentSortOrder() {
		
		var sortArray = new Array();
		
		$('.cmsinmotion_repeatblock').each(function() { 

			$(this).children().each(function() {
				
				if ($(this).attr('id') != "undefined") {
					sortArray.push($(this).attr('id'));
				}
				
			});
		
		});
		
		$.post(basePath+"/ajax/doSaveSortOrderBlocks", { sortArray: sortArray }, function(data) { console.log(data); });
		
	}
	
	
	$(document).ready(function() {
				
		<?=$lastJavascript;?>
		
	});
	
		
</script>
<script type='text/javascript' src="<?=base_path_rewrite;?>/js/crop.js"></script>
<style type="text/css" id="cssStyles">
<?=$generatedCSS;?>
</style>