<div class="span10">

	<a href="#create_user" class="btn btn-primary" data-toggle="modal"><?php echo $common_add_user;?></a>
	<br><br>
	<table class="table table-bordered table-striped table-hover">
		<tr>
			<td>Username:</td>
			<td>Role:</td>
			<td>Description:</td>
			<td></td>
			<td></td>
		</tr>
		<?php foreach($user_list as $row):?>
		<tr>
			<td><?php echo $row->username;?></td>
			<td><?php echo $row->role;?></td>
			<td><?php echo $row->description;?></td>
			<td><a href="#update_user_<?php echo $row->id;?>" class="btn" data-toggle="modal"><?php echo $common_update_user;?></a></td>
			<td><a class="btn btn-danger" href="#drop_user_<?php echo $row->id;?>" data-toggle="modal"><?php echo $common_drop_user;?></a></td>
		</tr>
		<?php endforeach;?>
	</table>

</div>