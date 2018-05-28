
<h2>Global Site Settings</h2>

<form action="<?=base_path_rewrite;?>/administration/sitesettings/save/0/commit" class="form-horizontal" method="POST">

	<div class="form-group">
		<label for="fieldName" class="col-sm-3 control-label">Site Title</label>
		<div class="col-sm-9">
	    	<input type="text" name="siteTitle" class="form-control" id="fieldName" placeholder="" value="<?=$settings['siteTitle'];?>" required />
	    </div>
	</div>
	<div class="form-group">
		<label for="fieldName" class="col-sm-3 control-label">Template</label>
		<div class="col-sm-9">
	    	<select name="template" class="form-control">
	    		<?php foreach($templates as $template) { ?>
	    		<option value="<?=$template['id'];?>" <?=($settings['template'] == $template['id'] ? 'selected' : '');?>><?=$template['id'];?> - <?=ucfirst($template['name']);?></option>
	    		<?php } ?>
	    	</select>
	    </div>
	</div>
	<div class="modal-footer">
		<input type="submit" value="Save settings" name="submit" class="btn btn-primary" />
	</div>
</form>  

  
  
  
  
  
  