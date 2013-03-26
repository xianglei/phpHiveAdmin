<div class="span10">
<?php if($this->session->userdata('role') == "admin"):?>
<div>

<table>
	<tr>
	<td>
	<div class="btn-group">
	<?php if(count($table_list) != 0):?>

	<a class="btn disabled"><i class=icon-remove></i><?php echo $common_drop_database;?></a>

	<?php else:?>

	<a class="btn btn-danger" href="#drop_database" data-toggle="modal"><i class=icon-remove></i><?php echo $common_drop_database;?></a>

	<?php endif;?>
	<a class="btn btn-primary" href="#create_table" data-toggle="modal"><?php echo $common_add_table;?></a>
	</div>
	</td>
	</tr>
</table>
</div>
<?php endif;?>
<br>
<div>
	<script>
	function CheckUnCheckAll()
	{
		var checkAll = document.getElementById('chkAll');
		var checkBox = document.getElementsByName("selected_table_name[]");
		var arr = checkBox;
		if(checkAll.checked==true){
			checkAll.value = "";
			for(i=0; i<arr.length; i++){
				arr[i].checked = true;
				checkAll.value += arr[i].value + ';';
			}
			var json = '{"tables":"' + checkAll.value.substring(0, (checkAll.value.length-1)) + '"}';
			//alert(json);
		}
		if(checkAll.checked==false){
			for(i=0; i<arr.length; i++){
				if(arr[i].checked == true){
					arr[i].checked = false;
				}else {
					arr[i].checked = true ;
				}
			}
		}
	}
	
	</script>
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr class="info">
			<?php if($this->session->userdata('role') == "admin"):?>
				<td>
				</td>
			<?php endif;?>
				<td>
				<?php echo $common_table_name;?>
				</td>
			<?php if($this->session->userdata('role') == "admin"):?>
				<td>
				<?php echo $common_alter_table;?>
				</td>
				<td>
				<?php echo $common_load_data;?>
				</td>
				<td>
				<?php echo $common_clone_table;?>
				</td>
				<td>
				<?php echo $common_table_detail;?>
				</td>
				<td>
				<?php echo $common_drop_table;?>
				</td>
			<?php endif;?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($table_list as $item):?>
			<tr>
			<?php if($this->session->userdata('role') == "admin"):?>
				<td>
				<input type="checkbox" name="selected_table_name[]" value="<?php echo $item;?>">
				</td>
			<?php endif;?>
				<td>
				<i class="icon-th-list"></i><a href="<?php echo $this->config->base_url();?>index.php/manage/query/<?php echo $var_db_name;?>/<?php echo $item;?>"><?php echo $item;?></a>
				</td>
			<?php if($this->session->userdata('role') == "admin"):?>
				<td>
				<i class="icon-pencil"></i><a href="<?php echo $this->config->base_url();?>index.php/table/altertable/<?php echo $var_db_name;?>/<?php echo $item;?>"><?php echo $common_alter_table;?></a>
				</td>
				<td>
				<i class="icon-chevron-right"></i><a href="<?php echo $this->config->base_url();?>index.php/table/loaddata/<?php echo $var_db_name;?>/<?php echo $item;?>"><?php echo $common_load_data;?></a>
				</td>
				<td>
				<i class="icon-random"></i><a href="#clone_table_<?php echo $item;?>" data-toggle="modal"><?php echo $common_clone_table;?></a>
				
				<?php
				$data['table_name'] = $item;
				$this->load->view('clone_table_modal', $data);
				?>
				
				</td>
				<td>
				<i class="icon-zoom-in"></i><a href="<?php echo $this->config->base_url();?>index.php/table/tabledetailinfo/<?php echo $var_db_name;?>/<?php echo $item;?>"><?php echo $common_table_detail;?></a>
				</td>
				<td>
				<i class="icon-remove"></i><a href="#drop_table_<?php echo $item;?>" data-toggle="modal"><?php echo $common_drop_table;?></a>
				
				<?php
				$data['table_name'] = $item;
				$this->load->view('drop_table_modal', $data);
				?>
				
				</td>
			<?php endif;?>
			</tr>
		<?php endforeach;?>
		<?php if($this->session->userdata('role') == "admin"):?>
			<tr>
				<td>
					<input type="hidden" name="db_name" value=<?php echo $var_db_name;?>>
					<input type="checkbox" id="chkAll" onClick="CheckUnCheckAll()" /><br /><?php echo $common_select?> / <?php echo $common_deselect;?>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><a href="#batch_drop_table" data-toggle="modal" class="btn btn-danger btn-small" onclick="BatchTableDropView()"><?php echo $common_delete;?></a></td>
			</tr>
		<?php endif;?>
		</tbody>
	</table>
</div>
</div>