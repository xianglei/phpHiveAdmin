<div class="span6">
	<pre class="alert alert-error">
<?php echo $common_add_table;?>: <?php echo $var_db_name?> . <?php echo $table_name;?>
	</pre>
	<form method="post" action="<?php echo $this->config->base_url();?>table/createtableaction">
	<!--columns area-->
	<div>
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
	</div>
	<!--columns end-->
	
	<!--partitions area-->
	<?php if($partitions_num > 0):?>
	<div>
	<table class="table table-bordered table-striped">
		<tr class="info">
			<td><?php echo $common_partition_name;?></td>
			<td><?php echo $common_partition_type;?></td>
			<td><?php echo $common_comment;?></td>
		</tr>
		<?php for($i = 0; $i < $partitions_num; $i++):?>
			<tr>
				<td><input type="text" name="part_name[]" placeholder="partition name" /></td>
				<td>
					<select name="part_type[]">
						<?php foreach($type as $k => $v):?>
						<option value="<?php echo $k;?>" <?php echo ($k == "map" || $k == 'arrays' || $k == 'structs') ? "disabled" : "";?>><?php echo $v;?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td><input type="text" name="part_comment[]" placeholder="partition comment" /></td>
			</tr>
		<?php endfor;?>
	</table>
	</div>
	<?php endif;?>
	<!--partitions end-->
	
	<!--etc options area-->
	<div>
	<table class="table table-bordered table-striped">
	<?php if($external == "EXTERNAL_TABLE"):?>
		<tr>
			<td>
				<?php echo $common_if_external_path;?></td><td><input type="text" name="location" value="hdfs:///user/hive/warehouse/<?php echo $var_db_name;?>.db/<?php echo $table_name;?>">
			</td>
		</tr>
	<?php else:?>
		<tr>
			<td>
				<?php echo $common_if_external_path;?></td><td><input type="text" name="location" value="hdfs:///user/hive/warehouse/<?php echo $var_db_name;?>.db/<?php echo $table_name;?>" disabled>
			</td>
		</tr>
	<?php endif;?>
		<tr>
			<td><?php echo $common_column_terminator;?></td><td><input type="text" name="cols_term" value="\t"></td>
		</tr>
		<tr>
			<td><?php echo $common_line_terminator;?></td><td><input type="text" name="line_term" value="\n"></td>
		</tr>
		<tr>
			<td><?php echo $common_data_format;?></td>
			<td>
				<select name="data_format">
					<?php foreach ($data_format as $k => $v):?>
					<option value="<?php echo $k;?>"><?php echo $v;?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
	</table>
	</div>
	<!--etc options end-->
	<input type="hidden" name="db_name" value="<?php echo $var_db_name;?>" />
	<input type="hidden" name="external" value="<?php echo $external;?>" />
	<input type="hidden" name="tbl_name" value="<?php echo $table_name;?>" />
	<input type="hidden" name="tbl_comment" value="<?php echo $table_comment;?>" />
	<div>
		<input type="submit" name="submit" value="<?php echo $common_submit;?>" class="btn btn-primary"/>
		<a href="<?php echo $this->config->base_url()?>table/index/<?php echo $var_db_name;?>" class="btn btn-inverse"><?php echo $common_cancel?></a>
	</div>
	</form>
</div>