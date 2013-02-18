<div class="span10">

	<form action="<?php echo $this->config->base_url();?>user/changepasswordaction/" method="post">
		<table class="table table-bordered table-striped table-hover">
			<tr>
				<td><?php echo $common_password;?></td>
				<td><input type="password" name="password" placeholder="Password"></td>
			</tr>
			<tr>
				<td><?php echo $common_password;?></td>
				<td><input type="password" name="repassword" placeholder="Retype password"></td>
			</tr>
		</table>
		<input type="hidden" name="user_id" value="<?php echo $result->id;?>" />
		<input type="submit" value="<?php echo $common_submit;?>" class="btn btn-primary" />
	</form>

</div>