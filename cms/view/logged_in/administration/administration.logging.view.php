
<h2><a href="#" title="Dashboard">Logging</a></h2>

<form action="<?=base_path_rewrite;?>/administration/logging/filter" method="POST" class="form-horizontal well">
	Apply filter to logging:<br /><br />
	<select name="severity" class="form-control-filter">
		<option value="">Severity (ALL)</option>
		<option value="INFO">Informational</option>
		<option value="DEBUG">Debugging</option>
		<option value="CRITICAL">Critical</option>
	</select>
    <input type="text" value="" name="module" placeholder="Module contains" class="form-control-filter" />
    <input type="text" value="" name="action" placeholder="Action contains" class="form-control-filter" />
    <input type="text" value="" name="user_id" style="width: 100px;" placeholder="UserID" class="form-control-filter" />
    <input type="text" value="" name="session_id" style="width: 100px;" placeholder="Session ID" class="form-control-filter" />
    <input type="submit" value="Apply filter" class="btn btn-primary" />
</form>

<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>
				Timestamp
			</th>
			<th>
				Severity
			</th>
			<th>
				Module
			</th>
			<th>
				Action
			</th>
			<th>
				UserID
			</th>
			<th>
				Session ID
			</th>
  		</tr>
	</thead>
	<tbody>
		<?php foreach($loggingData as $logRule) { ?>
		<tr>
			<td><?=$logRule['log_timestamp'];?><?=$logRule['log_timestamp_microtime'];?></td>
			<td><?=$logRule['severity'];?></td>
			<td><?=$logRule['module'];?></td>
			<td><?=$logRule['action'];?></td>
			<td><?=$logRule['user_id'];?></td>
			<td><?=$logRule['session_id'];?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

  
  
  
  
  