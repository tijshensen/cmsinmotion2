
<h2><a href="#" title="Dashboard">Language Management</a></h2>
<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>
				Language
			</th>
			<th>
				Actions
			</th>
   		</tr>
	</thead>
	<tbody>
		<?php foreach($languages as $language) { ?>
		<tr>
			<td><a href="<?=base_path_rewrite;?>/administration/languages/edit/<?=$language['id'];?>"><?=$language['name'];?></a></td>
			<td><a href="<?=base_path_rewrite;?>/administration/languages/edit/<?=$language['id'];?>">Edit</a> - <a href="<?=base_path_rewrite;?>/administration/languages/delete/<?=$language['id'];?>">Delete</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
        