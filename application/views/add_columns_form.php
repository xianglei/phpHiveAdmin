<div class="span10">

<form method="post" action="<?php echo $this->config->base_url();?>table/addcolumnsaction/">
	<!--columns area-->
	<table class="table table-bordered table-striped">
		<tr class="info">
			<td><?php echo $common_column_name;?></td>
			<td><?php echo $common_column_type;?></td>
			<td><?php echo $common_comment;?></td>
		</tr>
		<?php for($i = 0; $i < $cols_num; $i++):?>
			<tr>
				<td><input type="text" name="cols_name[]" placeholder="column name" /></td>
				<td>
					<select name="cols_type[]">
						<?php foreach($type as $k => $v):?>
						<option value="<?php echo $k;?>" <?php echo ($k == "map" || $k == 'arrays' || $k == 'structs') ? "disabled" : "";?>><?php echo $v;?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td><input type="text" name="cols_comment[]" placeholder="column comment" /></td>
			</tr>
		<?php endfor;?>
	</table>
	<input type="hidden" name="db_name" value="<?php echo $var_db_name;?>" />
	<input type="hidden" name="table_name" value="<?php echo $table_name;?>" />
	<input type="submit" value="<?php echo $common_submit?>" class="btn btn-primary" />
</form>

</div>