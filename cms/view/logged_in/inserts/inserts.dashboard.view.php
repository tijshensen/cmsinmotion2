
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active"><a href="#">HTML inserts</a></li>
				<!--<li><a href="#">Photogallery</a></li>
				<li><a href="#">Table builder</a></li>-->

			</ul>
		</div>
		<div class="col-sm-9 col-md-10 main">
			<form>
			<span class="pull-right" style="margin-right: 100px; display:none;" id="savedChanges"><span class="glyphicon glyphicon-ok"></span> Your changes have been saved</span>
					
			<div class="row">
				<div class="col-md-3">
					<a href="#" type="button" class="btn btn-primary" id="newInsert">New HTML insert</a>
					<ul class="nav nav-sidebar-sub" id="sidebarList">
						<li><input type="text" class="form-control" id="addInsert" /></li>
						<?php foreach($allInserts as $insert) { ?>
							<li id="insert<?=$insert['id'];?>"><a href="javascript:loadInsert(<?=$insert['id'];?>);"><?=$insert['tag'];?></a></li>
						<?php } ?>

					</ul>
				</div>
				<div class="col-md-9" style="display: none;" id="insertField">
					<h1 id="showInsertTitle">[*empty*]</h1>
					<input type="checkbox" value="1" id="onlyInRender" /> Show only on render
					<input type="hidden" value="" id="insertEditID" />
					<div>
							<div class="form-control" rows="10" id="contentInsert" style="height: 500px;"></div>
							<button type="button" class="btn btn-primary" onClick="saveChanges();">Save</button>
							<button type="button" class="btn btn-warning btn-flat" onClick="deleteItem();">Delete</button>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>


<script src="<?=base_path_rewrite;?>/js/ace/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    
</script>


	<script>
	
		insertType = "new";
	
		//Controleer de input, zodat alleen leestekens gebruikt worden
		//Strip automatisch de [] weg
		//Maak standaard hoofdletters
		var editor = ace.edit("contentInsert");
		editor.getSession().setMode("ace/mode/html");
	
		$('#newInsert').click(function(){
			$('#addInsert').addClass('active');
			$('#showInsertTitle').html('[*empty*]');
			$('#insertField').show();
			editor.getSession().setValue('');
			insertType = "new";
			return false;
		});
		
		$('#addInsert').bind('input', function(){
			
			var title = $(this).val();
			title = title.toUpperCase();
			title = title.replace(/\W/g, '');
			$('#showInsertTitle').html('['+title+']');
			$('#insertEditID').val('['+title+']');
			
		});
		
		function loadInsert(id) {
			
			$('#addInsert').removeClass('active');
			
			insertType = "edit";
			
			$.post(basePath+'/ajax/loadInsert', { id: id }, function(data) {
				
				newData = data.split(':_insertSplit_:');
				theData = newData[1].split(":_renderSplit_:");
				$('#insertField').show();
				$('#addInsert').val(newData[0]);
				$('#insertEditID').val(newData[0]);
				$('#showInsertTitle').html('<input type="text" id="insertName" class="form-control" value="'+newData[0]+'" />');
				editor.getSession().setValue(theData[0]);
				
				if (theData[1] == 1) {
					$('#onlyInRender').prop('checked', true);
				} else {
					$('#onlyInRender').prop('checked', false);
				}
			
				
			});
			
		}
		
		function deleteItem() {
			
			id = $('#insertName').val();
			checkDelete = confirm("Are you sure you want to delete this insert?");
			if (checkDelete == true) {
				
				$.post(basePath+"/ajax/deleteInsert", { id: id }, function(data) {
					
					$('#addInsert').addClass('active');
					$('#showInsertTitle').html('[*empty*]');
					$('#insertField').show();
                    $('#insert'+data).hide();
					
					editor.getSession().setValue('');
					insertType = "new";
					return false;
					
				});
				
			}
			
		}
		
		function saveChanges() {
			
			id = $('#insertEditID').val();
			content = editor.getSession().getValue();
			onlyRender = $('#onlyInRender:checked').val();
			
			$.post(basePath+"/ajax/saveInsert", { id: id, content: content, title: $('#insertName').val(), onlyRender: onlyRender }, function(data) {
				
				$('#savedChanges').fadeIn();
				setTimeout('removeChangesIndication()', 3000);
				
				if (insertType == "new") {
				
					$('#sidebarList').append('<li id="insert'+data+'"><a href="javascript:loadInsert('+data+');">'+id+'</a></li>');
				
				} else {
					
					$('#insertEditID') = $('#insertName').val();
					
				}
				
			});
			
		}
		
		function removeChangesIndication() {
			
			$('#savedChanges').fadeOut();
			
		}
		
	</script>
	