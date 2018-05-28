
<h2><a href="#" title="Dashboard">Active Sessions</a></h2>

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
				IP-address
			</th>
			<th>
				Session Start
			</th>
			<th>
				Session Timeout
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($currentSessions as $session) { ?>
		<tr>
			<td><?=$session['emailadres'];?></td>
			<td><?=$session['voornaam'];?></td>
			<td><?=$session['achternaam'];?></td>
			<td><?=$session['ip_address'];?></td>
			<td><?=date("d-m-Y H:i:s", strtotime($session['last_login']));?></td>
			<td><?=date("d-m-Y H:i:s", strtotime($session['timeout']));?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>


  
  
  
  
  