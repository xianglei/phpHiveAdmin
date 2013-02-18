<div id="clone_table_<?php echo $table_name?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="post" action="<?php echo $this->config->base_url() . "table/clonetable/";?>">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_clone_table;?></h3>
	</div>
	<div class="modal-body">

			<?php echo $common_clone_table;?> <?php echo $table_name;?>
			
				<table class="table table-bordered">
					<tr class="info">
						<td><?php echo $common_table_name;?></td>
						<td><input type="text" name="new_tbl_name"></td>
					</tr>
					<tr>
						<td><?php echo $common_table_type;?></td>
						<td>
							<select name="external">
								<option value="EXTERNAL_TABLE"><?php echo $common_external;?></option>
								<option value="MANAGED_TABLE"><?php echo $common_managed;?></option>
								<option value="INDEX_TABLE" disabled><?php echo $common_index_table;?></option>
								<option value="VIRTUAL_VIEW" disabled><?php echo $common_virtual_view;?></option>
							</select>
						</td>
					</tr>
				</table>
				<input type="hidden" name="db_name" value="<?php echo $var_db_name;?>">
				<input type="hidden" name="tbl_name" value="<?php echo $table_name;?>">
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<input type="submit" class="btn btn-primary" value="<?php echo $common_submit;?>">
	</div>
	</form>
</div>