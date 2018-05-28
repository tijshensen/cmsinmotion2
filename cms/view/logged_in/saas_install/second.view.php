<div class="container">
	<div class="row" style="margin-top: 50px;">
		<form action="<?=base_path_rewrite;?>/saasinstall/<?=$requestAction;?>/user_install/" method="POST">
		<h3>Add a administrative user</h3>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Firstname</label>
				<div class="col-sm-9">
			    	<input type="text" name="voornaam" class="form-control" id="fieldSite" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Surname</label>
				<div class="col-sm-9">
			    	<input type="text" name="achternaam" class="form-control" id="fieldName" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">E-mailaddress</label>
				<div class="col-sm-9">
			    	<input type="text" name="emailadres" class="form-control" id="fieldEmail" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Password</label>
				<div class="col-sm-9">
			    	<input type="password" name="password" class="form-control" id="fieldPasswordStrength" placeholder="" value="" />
			    </div>
			</div>
			<div class="modal-footer">
				<br />
				<a href="<?=base_path_rewrite;?>/saasinstall/<?=$requestAction;?>/done" class="btn btn-success">Skip user creation</a>
				<input type="submit" value="Configure instance" name="submit" id="saveButton" class="btn btn-danger" />
				
			</div>
	
		</form>
	</div>
</div>