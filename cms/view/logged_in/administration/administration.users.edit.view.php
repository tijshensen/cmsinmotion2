<?=flash();?>

<?php if ($requestParameter == "new") { ?>
		
<form action="<?=base_path_rewrite;?>/administration/users/new/0/commit" class="form-horizontal" method="POST">

<?php } else { ?>

<form action="<?=base_path_rewrite;?>/administration/users/edit/<?=$fetchUser->id;?>/commit" class="form-horizontal" method="POST">

<?php } ?>
<input type="hidden" value="<?=$fetchUser->user_id;?>" name="user_id" />
<h3>Group Membership and Access Control</h3>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">Member of</label>
	<div class="col-sm-9" style="padding-top: 7px;">
		<select name="group_id">
			<?php foreach($groupData as $group) { ?>
				<option value="<?=$group['id'];?>" <?=($group['id'] == $fetchUser->group_id ? 'selected' : '');?>><?=$group['name'];?></option>
			<?php } ?>
		</select>
    </div>
</div>
<h3>Personal information</h3>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">Name</label>
	<div class="col-sm-9">
    	<input type="text" name="voornaam" class="form-control" id="fieldName" placeholder="" value="<?=$fetchUser->voornaam;?>" required />
    </div>
</div>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">Surname</label>
	<div class="col-sm-9">
    	<input type="text" name="achternaam" class="form-control" id="fieldName" placeholder="" value="<?=$fetchUser->achternaam;?>" required />
    </div>
</div>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">E-mailaddress</label>
	<div class="col-sm-9">
    	<input type="text" name="emailadres" class="form-control" id="fieldEmail" placeholder="" value="<?=$fetchUser->emailadres;?>" required />
    </div>
</div>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">Password</label>
	<div class="col-sm-9">
    	<input type="password" name="password" class="form-control" id="fieldPasswordStrength" placeholder="" value="" />
    </div>
</div>
<div class="form-group">
	<label for="fieldName" class="col-sm-3 control-label">Confirm password</label>
	<div class="col-sm-9">
    	<input type="password" name="password2" class="form-control" id="fieldPasswordConfirm" placeholder="" value="" />
    	<div id="confirmInfo">
    	</div>
    </div>
</div>
<div class="modal-footer">
	
	<?php if ($requestParameter == "edit") { ?>
		<input type="button" data-toggle="modal" href="#deleteDialog" value="Delete user" name="submit" class="btn btn-danger btn-flat" />
	<?php } ?>
	<input type="submit" value="Save" name="submit" id="saveButton" class="btn btn-primary" />
	
</div>

</form>


<div class="modal fade" id="deleteDialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete user <strong><?=$fetchUser->emailadres;?></strong></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="window.location.href = '<?=base_path_rewrite;?>/administration/users/delete/<?=$fetchUser->id;?>/commit';" class="btn btn-danger">Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
