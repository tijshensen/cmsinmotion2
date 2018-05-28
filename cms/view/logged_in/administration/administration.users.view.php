
<h2><a href="#" title="Dashboard">User Management</a></h2>
<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>
				Username
			</th>
			<th>
				Firstname
			</th>
			<th>
				Surname
			</th>
			<th>
				Group
			</th>
  		</tr>
	</thead>
	<tbody>
		<?php foreach($themisUsers as $user) { ?>
		<tr>
			<td><a href="<?=base_path_rewrite;?>/administration/users/edit/<?=$user['id'];?>"><?=$user['emailadres'];?></a></td>
			<td><?=$user['voornaam'];?></td>
			<td><?=$user['achternaam'];?></td>
			<td><?=$user['name'];?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<a href="<?=base_path_rewrite;?>/administration/users/new" class="btn btn-primary">Create new user</a>
  

  
  
  
  
  
  