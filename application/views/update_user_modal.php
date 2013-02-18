<?php foreach($user_list as $row):?>

<div id="update_user_<?php echo $row->id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="post" action="<?php echo $this->config->base_url();?>user/updateuseraction/">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_update_user;?>: <?php echo $row->username;?></h3>
	</div>
	<div class="modal-body">
		<table class="table table-bordered">
			<tr>
				<td><?php echo $common_username;?></td>
				<td><input type="text" name="username" placeholder="Username" value="<?php echo $row->username;?>"></td>
			</tr>
			<tr>
				<td><?php echo $common_password;?></td>
				<td><input type="password" name="password" placeholder="Password"></td>
			</tr>
			<tr>
				<td><?php echo $common_password;?></td>
				<td><input type="password" name="repassword" placeholder="Retype password"></td>
			</tr>
			<tr>
				<td><?php echo $common_onlydb;?></td>
				<td><input type="text" name="onlydb" placeholder="default,test1,test2" value="<?php echo $row->onlydb;?>"></td>
			</tr>
			<tr>
				<td><?php echo $common_role;?></td>
				<td>
					<select name="role">
						<option value="user" <?php echo ($row->role == 'user') ? "selected" : "";?>><?php echo $common_user;?></option>
						<option value="admin" <?php echo ($row->role == 'admin') ? "selected" : "";?>><?php echo $common_admin;?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $common_reduce;?></td>
				<td><input type="text" name="reduce" placeholder="Reduce num for user" value="<?php echo $row->reduce;?>" disabled></td>
			</tr>
			<tr>
				<td><?php echo $common_description;?></td>
				<td><input type="text" name="description" value="<?php echo $row->description;?>" placeholder="User description"></td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<input type="hidden" name="user_id" value="<?php echo $row->id;?>">
		<input type="submit" name="submit" class="btn btn-primary" value=<?php echo $common_submit;?>>
	</div>
	</form>
</div>

<?php endforeach;?>