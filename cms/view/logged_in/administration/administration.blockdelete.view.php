

<h2><a href="#" title="Dashboard">Template Management</a></h2>

<br />
<p>
<strong>Are you sure you want to delete the block <?=$templateBlockLookup->name;?></strong>?<br />
<br />
<br />
</p>

<a href="<?=base_path_rewrite;?>/administration/templates/<?=$templateLookup->template_set_id;?>" class="btn">Cancel</a>
<a href="<?=base_path_rewrite;?>/administration/templates/do_blockdelete/<?=$templateBlockLookup->id;?>" class="btn btn-danger">Delete this block</a>
