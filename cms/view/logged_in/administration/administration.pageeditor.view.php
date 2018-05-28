<h2>Templates</h2>


<ol class="breadcrumb">
  <li><a href="<?=base_path_rewrite;?>/administration/load">Templates</a></li>
 
  
<?=($templateLookup->name != '' ? '<li class="active">'.$templateLookup->name.'</li>' : '<li class="active">New page</li>');?>
</ol>

			
			<h6><input type="text" value="<?=$templateLookup->name;?>" class="form-control" id="pageName" /></h6>
			<span class="pull-right" style="margin-right: 100px; display:none;" id="savedChanges"><span class="glyphicon glyphicon-ok"></span> Your changes have been saved</span>
					<div>
					
					<div class="form-control" rows="10" id="contentInsert" style="height: 500px;"></div>
					</div>
					
					
							<button type="button" class="btn btn-primary" onClick="saveChanges();">Save</button>
							<button type="button" class="btn btn-primary" onClick="deletePage();">Delete page</button>
		</div>
						

<script src="<?=base_path_rewrite;?>/js/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
	<script>
	
		insertType = "new";
	
		//Controleer de input, zodat alleen leestekens gebruikt worden
		//Strip automatisch de [] weg
		//Maak standaard hoofdletters
		var editor = ace.edit("contentInsert");
		editor.getSession().setMode("ace/mode/html");
		editorValue = "<?=mysql_real_escape_string(str_replace("</script>", "</cmsinmotionscript>", $templateLookup->core));?>";
		editor.getSession().setValue(editorValue.replace(/cmsinmotionscript/gi, "script"));
				
		function saveChanges() {
			
			content = editor.getSession().getValue();
			
			$.post(basePath+"/ajax/savePageEditor", { id: '<?=$templateLookup->id;?>', content: content, title: $('#pageName').val(), tsi: '<?=$templateSetID;?>' }, function(data) {
				
				$('#savedChanges').fadeIn();
				setTimeout('removeChangesIndication()', 3000);
				
			});
			
		}
		
		function removeChangesIndication() {
			
			$('#savedChanges').fadeOut();
			
		}
		
		function deletePage() {
    		
    		reallyDelete = confirm("Are you sure you want to delete this pagetype?");
    		if(reallyDelete == true) {
        		
        		window.location.href=basePath+"/administration/pagetypedelete/<?=$templateLookup->id;?>/0/commit";
        		
    		}
    		
		}
		
	</script>
	