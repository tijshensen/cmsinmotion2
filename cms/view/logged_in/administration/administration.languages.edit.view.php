
<h2><a href="#" title="Dashboard">Language Management</a></h2>

<br />
<p>
    <form action="<?=base_path_rewrite;?>/administration/languages/do_edit/<?=$languagesShow->id;?>" method="POST">
    
    	<strong>Name</strong><br /><br />
    	<input type="text" value="<?=$languagesShow->name;?>" name="name" class="form-control" /><Br />
    	<br/>
    	
    	<strong>Domain</strong><br /><br />
    	<input type="text" value="<?=$languagesShow->url;?>" name="url" class="form-control" />
    	
        <br />
        <br />
        
		<a href="<?=base_path_rewrite;?>/administration/languages" class="btn">Cancel</a>
		<input type="submit" class="btn btn-success" value="Save language" />
		
    </form>
</p>
 