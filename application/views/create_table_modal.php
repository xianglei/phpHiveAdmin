<div id="create_table" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<style type="text/css">
	#error_cols
	{
		padding-left:15px;
		color:Red;
	}
	#error_parts
	{
		padding-left:15px;
		color:Red;
	}
	</style>
	<form name="newTable" method="post" action="<?php echo $this->config->base_url();?>index.php/table/createtable/">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_add_table;?></h3>
	</div>
	<div class="modal-body">
			<table class="table table-bordered">
				<tr>
					<td><?php echo $common_table_name;?></td>
					<td><input type="text" name="table_name" placeholder="testing_table"></td>
				</tr>
				<tr>
					<td><?php echo $common_field_numbers;?></td>
					<td><input id="cols_num" type="text" name="cols_num" placeholder="2"></td>
				</tr>
				<tr>
					<td><?php echo $common_table_comment;?></td>
					<td><input type="text" name="table_comment" placeholder="example comment"></td>
				</tr>
				<tr>
					<td><?php echo $common_partition_numbers;?><br><?php echo $common_blank_for_no_partition;?></td>
					<td><input id="partitions_num" type="text" name="partitions_num" placeholder="0"></td>
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
			
			<span id="error_cols"><?php echo $error_invalid_column_numbers;?></span>
			<span id="error_parts"><?php echo $error_invalid_partition_numbers;?></span>
			
			<script type="text/javascript">
			$(document).ready(function () {
				$("#error_cols").hide();
					$("#cols_num").blur(function () {
						var $val = $("#cols_num").val();
						var code;
						for (var i = 0; i < $val.length; i++) {
						var code = $val.charAt(i).charCodeAt(0);
						if (code < 48 || code > 57) {
							$("#error_cols").show();
							break;
						}
						else {
							$("#error_cols").hide();
						}
					}
				});
			});
			
			$(document).ready(function () {
				$("#error_parts").hide();
					$("#partitions_num").blur(function () {
						var $val = $("#partitions_num").val();
						var code;
						for (var i = 0; i < $val.length; i++) {
						var code = $val.charAt(i).charCodeAt(0);
						if (code < 48 || code > 57) {
							$("#error_parts").show();
							break;
						}
						else {
							$("#error_parts").hide();
						}
					}
				});
			});
			</script>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<input type="hidden" name="db_name" value="<?php echo $var_db_name;?>">
		<input type="submit" name="submit" class="btn btn-primary" value=<?php echo $common_submit;?>>
	</div>
	</form>
</div>