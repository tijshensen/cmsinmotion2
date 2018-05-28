<div class="container">
	<div class="row" style="margin-top: 50px;">
		<form action="<?=base_path_rewrite;?>/saasinstall/<?=$requestAction;?>/install/" method="POST">
		<h3>Configure a new installation of CMSinMotion</h3>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Site title</label>
				<div class="col-sm-9">
			    	<input type="text" name="siteTitle" class="form-control" id="fieldSite" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Licensekey</label>
				<div class="col-sm-9">
			    	<input type="text" name="siteKey" class="form-control" id="fieldKey" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Database</label>
				<div class="col-sm-9">
			    	<input type="text" name="dbName" class="form-control" id="fieldName" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Database username</label>
				<div class="col-sm-9">
			    	<input type="text" name="dbUsername" class="form-control" id="fieldEmail" placeholder="" value="" required />
			    </div>
			</div>
			<div class="form-group">
				<label for="fieldName" class="col-sm-3 control-label">Database password</label>
				<div class="col-sm-9">
			    	<input type="password" name="dbPassword" class="form-control" id="fieldPasswordStrength" placeholder="" value="" />
			    </div>
			</div>
			<div class="modal-footer">
				<br />
				<strong>Warning:</strong> Configuring a new instance will reset all data within the instance!
				<input type="submit" value="Configure instance" name="submit" id="saveButton" class="btn btn-danger" />
				
			</div>
	
		</form>
	</div>
</div>