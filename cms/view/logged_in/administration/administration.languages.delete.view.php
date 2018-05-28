

<h2><a href="#" title="Dashboard">Language Management</a></h2>

<br />
<p>
<strong>Are you sure you want to delete the language <?=$languagesShow->name;?></strong>?<br />
You will lose all the content stored in this language!
<br />
<br />
</p>

<a href="<?=base_path_rewrite;?>/administration/languages" class="btn">Cancel</a>
<a href="<?=base_path_rewrite;?>/administration/languages/do_delete/<?=$languagesShow->id;?>" class="btn btn-danger">Delete this language</a>
