
<h2>Templates</h2>


<table class="table table-bordered table-striped table-condensed">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($allTemplates as $template) { ?>
		<tr>
			<td><?=$template['id'];?></td>
			<td><a href="<?=base_path_rewrite;?>/administration/templates/<?=$template['id'];?>"><?=$template['name'];?></a> </td>
			<td><a href="<?=base_path_rewrite;?>/administration/templates/delete/<?=$template['id'];?>">Delete</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>



<div class="panel panel-default">
  <div class="panel-heading">Upload a new template</div>
  <div class="panel-body">
	<form action="<?=base_path_rewrite;?>/administration/load/save/0/commit" class="form-horizontal" method="POST" enctype="multipart/form-data">
		
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Please select your .zip file</label>
			<div class="col-sm-6">
				<input type="file" name="loadModule" class="form-control">
			</div>
		</div>
	
		 <div class="form-group">
		    <div class="col-sm-offset-3 col-sm-6">
		      <input type="submit" value="Upload" name="submit" class="btn btn-primary" />
		    </div>
		  </div>

	</form>
  </div>
</div>



  
  
  
  
  
  