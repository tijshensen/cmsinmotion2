<div class="container">

	<?=flash();?>

    <h2 style="margin-top: 25px;">My <strong style="color: #333;">CMSinMotion</strong> account</h2>
    
    <p>This is your account information. You can change your profile here.</p>
    
    <p>If you want to change your password, please enter a new password on this page. If you don't want to change your password, you can leave the fields blank.</p>
    
    <p>Should you need any assistance, don't hesitate to contact us at support@cmsinmotion.com</p>
    
    <form action="<?=base_path_rewrite;?>/profile/edit" class="form-horizontal" method="POST">
    	<input type="hidden" value="<?=$myProfile->user_id;?>" name="user_id" />
    	<h3>Group Membership and Access Control</h3>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">My group</label>
			<div class="col-sm-9" style="padding-top: 7px;">
		    	You're currently in the <strong><?=$myProfile->name;?></strong> group.
		    </div>
		    
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Access Control</label>
			<div class="col-sm-9" style="padding-top: 7px;">
		    	You have access to the following features:<br />
		    	<ul>
		    		<?=($myProfile->accessAdvanced == 1 ? '<li>Advanced Access</li>' : '');?>
		    	</ul>
		    </div>
		    
		</div>
    	<h3>Personal information</h3>
    	<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Name</label>
			<div class="col-sm-9">
		    	<input type="text" name="voornaam" class="form-control" id="fieldName" placeholder="" value="<?=$myProfile->voornaam;?>" required />
		    </div>
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Surname</label>
			<div class="col-sm-9">
		    	<input type="text" name="achternaam" class="form-control" id="fieldName" placeholder="" value="<?=$myProfile->achternaam;?>" required />
		    </div>
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">E-mailaddress</label>
			<div class="col-sm-9">
		    	<input type="text" name="emailadres" class="form-control" id="fieldEmail" placeholder="" value="<?=$myProfile->emailadres;?>" required />
		    </div>
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Password</label>
			<div class="col-sm-9">
		    	<input type="password" name="password" class="form-control" id="fieldPassword" placeholder="" value="" />
		    </div>
		</div>
		<div class="form-group">
			<label for="fieldName" class="col-sm-3 control-label">Confirm password</label>
			<div class="col-sm-9">
		    	<input type="password" name="password2" class="form-control" id="fieldPasswordConfirm" placeholder="" value="" />
		    </div>
		</div>
		<div class="modal-footer">
	
			<input type="submit" value="Save my changes" name="submit" class="btn btn-success" />
			
		</div>
		
	</form>
    
</div>