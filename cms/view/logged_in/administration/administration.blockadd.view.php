<h2>Templates</h2>
<ol class="breadcrumb">
  <li><a href="#">Templates</a></li>
  <li><a href="#"><?=$templateSetLookup->name;?></a></li>
  <li class="active"><?=$templateLookup->name;?></li>
</ol>
			
<input type="text" value="" name="blockname" id="blockName"/>

<span class="pull-right" style="margin-right: 100px; display:none;" id="savedChanges"><span class="glyphicon glyphicon-ok"></span> Your changes have been saved</span>
<div>
	<div class="form-control" rows="10" id="contentInsert" style="height: 500px;"></div>
</div>

<button type="button" class="btn btn-primary" onClick="saveChanges();">Save</button>

						

<script src="<?=base_path_rewrite;?>/js/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script>
	
		insertType = "new";
	
		//Controleer de input, zodat alleen leestekens gebruikt worden
		//Strip automatisch de [] weg
		//Maak standaard hoofdletters
		var editor = ace.edit("contentInsert");
		editor.getSession().setMode("ace/mode/html");
		editor.getSession().setValue("<div></div>");
	
				
		function saveChanges() {
			
			content = editor.getSession().getValue();
			
			$.post(basePath+"/ajax/savePageNewBlock", { id: <?=$templateLookup->id;?>, content: content, name: $('#blockName').val() }, function(data) {
				
				if (data != ""){
				window.location.href = basePath+"/administration/templates/blockedit/"+data;
				}
			});
			
		}
		
		function removeChangesIndication() {
			
			$('#savedChanges').fadeOut();
			
		}
		
	</script>
	