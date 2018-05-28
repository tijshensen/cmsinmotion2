

	<?=flash();?>

	<?php if ($requestParameter == "new") { ?>
		
		<form action="<?=base_path_rewrite;?>/administration/groups/new/0/commit" class="form-horizontal" method="POST">
    
	<?php } else { ?>
		
		<form action="<?=base_path_rewrite;?>/administration/groups/edit/<?=$fetchGroup->id;?>/commit" class="form-horizontal" method="POST">
    
	<?php } ?>
		<input type="hidden" value="<?=$fetchUser->user_id;?>" name="user_id" />
    	<h3>Group Information</h3>
    	<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Name</label>
			<div class="col-sm-9">
		    	<input type="text" name="name" class="form-control" id="fieldName" placeholder="" value="<?=$fetchGroup->name;?>" required />
		    </div>
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Can access advanced features</label>
			<div class="col-sm-9">
		    	<input type="checkbox" name="accessAdvanced" class="form-control" id="fieldName" value="1" <?=($fetchGroup->accessAdvanced == 1 ? 'checked' :'');?>   />
		    </div>
		</div>
		
		<div class="modal-footer">
			
			<?php if ($requestParameter == "edit") { ?>
				<input type="button" data-toggle="modal" href="#deleteDialog" value="Delete group" name="submit" class="btn btn-danger" />
			<?php } ?>
			<input type="submit" value="Save my changes" name="submit" class="btn btn-success" />
			
		</div>
		
	</form>
    

<div class="modal fade" id="deleteDialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete group <strong><?=$fetchGroup->name;?></strong></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this group?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="window.location.href = '<?=base_path_rewrite;?>/administration/groups/delete/<?=$fetchGroup->id;?>/commit';" class="btn btn-danger">Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
