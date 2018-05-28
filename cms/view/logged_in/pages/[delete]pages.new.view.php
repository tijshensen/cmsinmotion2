<div class="container" style="height: 100% !important;">
	<div class="col-sm-8" style="padding-top: 100px;">
		<form action="<?=base_path_rewrite;?>/pages/new/0/commit" class="form-horizontal" method="POST">
		<input type="hidden" value="<?=mysql_real_escape_string( htmlspecialchars($requestParameter) );?>" name="template_id" />
		<h3>Enter the title of the page</h3><br />
		<input type="text" value="" name="title" class="form-control" /><br />
		
		<input type="submit" value="Start editing" class="btn btn-primary"/>
		</form>
	</div>
</div>