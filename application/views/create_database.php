<div class="span4">
<img src="<?php echo $this->config->base_url();?>img/phphiveadmin.jpg" />
<br>
<br>
<?php if($this->session->userdata('role') == 'admin'):?>
<form method="post" action="<?php echo $this->config->base_url();?>hive/createdatabase">
	<table class="table-bordered table-striped table-hover">
		<tr>
			<td>
				<?php echo $common_add_database;?>
			</td>
			<td>
				<input type="text" name="db_name" placeholder="DB name here...">
			</td>
		</tr>
		<tr>
			<td>
				<?php echo $common_comment;?>
			</td>
			<td>
				<input type="text" name="db_comment" placeholder="DB comment here...">
			</td>
		</tr>
</table><br>
<input type=submit name=submit class="btn btn-primary btn-small" value=<?php echo $common_submit;?>>
</form>
<?php endif;?>
</div>