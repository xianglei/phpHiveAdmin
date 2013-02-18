<div class="span10">
	<form method="post" action="<?php echo $this->config->base_url();?>table/loaddataaction/">
	<?php echo $common_load_data_comment;?><br>
		<table class="table table-bordered">
			
			<tr class="info">
				<td><?php echo $common_choose_file_system;?></td>
				<td><?php echo $common_path_to_load;?></td>
				<td><?php echo $common_if_partition;?></td>
				<td><?php echo $warn_overwrite_confirm;?></td>
			<tr>
			
			<tr class="error">
				<td><select name="local"><option value="LOCAL"><?php echo $common_local;?></option><option value="HDFS"><?php echo $common_hdfs;?></option></select></td>
				<td><input type="text" name="path"></td>
				<td><input type="text" name="partition"></td>
				<td><input type="checkbox" name="overwrite" value="1"></td>
			<tr>
			
		</table>
		<input type="hidden" name="db_name" value="<?php echo $db_name;?>">
		<input type="hidden" name="table_name" value="<?php echo $table_name;?>">
		<input class="btn btn-danger" type="submit" value="<?php echo $common_submit;?>">
	</form>
</div>