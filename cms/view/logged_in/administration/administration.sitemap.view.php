
<h2>Load sitemap</h2>

<p class="lead">Load a new sitemap into CMSinMotion</p>

<form action="<?=base_path_rewrite;?>/administration/sitemap/save" class="form-horizontal" method="POST" enctype="multipart/form-data">
	<div class="form-group">
		<label for="fieldName" class="col-sm-3 control-label">Please select a file</label>
		<div class="col-sm-9">
	    	<input type="file" name="loadSitemap" class="form-control">
	    </div>
	</div>
	<div class="modal-footer">
		<input type="submit" value="Upload extension" name="submit" class="btn btn-primary" />
	</div>
</form>  

  
  
  
  
  