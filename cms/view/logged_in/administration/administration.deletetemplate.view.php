
<h2>Template Management</h2>

<div class="modal-warning">
Are you sure you want to delete the <u>template</u> <?=$templateLookup->name;?>?
</div>

<a href="<?=base_path_rewrite;?>/administration/load/" class="btn btn-flat">Cancel</a>
<a href="<?=base_path_rewrite;?>/administration/templates/do_templatedelete/<?=$templateLookup->id;?>" class="btn btn-danger">Delete this template</a>

