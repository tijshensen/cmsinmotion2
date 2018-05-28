
<ol class="breadcrumb">
  <li><a href="<?=base_path_rewrite;?>/administration/load">Templates</a></li>
  <li class="active"><?=$templateSetLookup->name;?></li>
</ol>
			
<h1 id="showInsertTitle"><input type="text" value="<?=$templateBlockLookup->name;?>" id="blockName" class="form-control"/></h1>
<h6><?=$templateLookup->name;?></h6>
<span class="pull-right" style="margin-right: 100px; display:none;" id="savedChanges"><span class="glyphicon glyphicon-ok"></span> Your changes have been saved</span>
<div>
	<div class="form-control" rows="10" id="contentInsert" style="height: 500px;"></div>
</div>

<button type="button" class="btn btn-primary" onClick="saveChanges();">Save</button> &nbsp;&nbsp;<strong>If you edit an element within a block, changes made in pages will be lost!</strong>
	
	

<script src="<?=base_path_rewrite;?>/js/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script>
	
		insertType = "new";
	
		//Controleer de input, zodat alleen leestekens gebruikt worden
		//Strip automatisch de [] weg
		//Maak standaard hoofdletters
		var editor = ace.edit("contentInsert");
		editor.getSession().setMode("ace/mode/html");
		editor.getSession().setValue("<?=mysql_real_escape_string($templateBlockLookup->content);?>");
				
		function saveChanges() {
			
			blockname = $('#blockName').val();
			content = editor.getSession().getValue();
			
			$.post(basePath+"/ajax/savePageBlock", { id: <?=$templateBlockLookup->id;?>, content: content, blockname: blockname }, function(data) {
				
				$('#savedChanges').fadeIn();
				setTimeout('removeChangesIndication()', 3000);
				
			});
			
		}
		
		function removeChangesIndication() {
			
			$('#savedChanges').fadeOut();
			
		}
		
	</script>
	