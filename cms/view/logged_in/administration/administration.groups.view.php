
 
<h2><a href="#" title="Dashboard">Group Management and Access Control</a></h2>
<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>
				Groupname
			</th>
			<th>
				Advanced Access
			</th>
   		</tr>
	</thead>
	<tbody>
		<?php foreach($themisGroups as $group) { ?>
		<tr>
			<td><a href="<?=base_path_rewrite;?>/administration/groups/edit/<?=$group['id'];?>"><?=$group['name'];?></a></td>
			<td><?=($group['accessAdvanced'] == 1 ? '<span class="glyphicon glyphicon-ok"></span>' : '');?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<a href="<?=base_path_rewrite;?>/administration/groups/new" class="btn btn-primary">Create new group</a>

