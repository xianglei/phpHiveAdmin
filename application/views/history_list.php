<div class="span10">

	<script>
	function CheckUnCheckAll()
	{
		var checkAll = document.getElementById('chkAll');
		var checkBox = document.getElementsByName("history_id[]");
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
	<form method="post" action="<?php echo $this->config->base_url();?>index.php/history/batchdrophistory/">
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr class="success">
			<?php if($this->session->userdata('role') == "admin"):?>
				<td></td>
			<?php endif;?>
				<td><?php echo $common_file_name;?></td>
				<td><?php echo $common_file_content;?></td>
				<td><?php echo $common_file_size;?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach($results as $item):?>
			<tr>
			<?php if($this->session->userdata('role') == "admin"):?>
				<td>
					<input type="checkbox" name="history_id[]" value="<?php echo $item->id;?>" />
				</td>
			<?php endif;?>
				<?php
				$filename['log_with_path'] = $this->config->item('log_path') . $item->username . "_" . $item->fingerprint . ".log";
				$filename['log'] = $item->username . "_" . $item->fingerprint . ".log";
				?>
				<td><a href="<?php echo $this->config->base_url();?>index.php/manage/getresult/<?php echo $item->fingerprint;?>" target="_blank"><?php echo $filename['log'];?></td>
				<?php
				$this->load->helper('file');
				try
				{
					$content = read_file($filename['log_with_path']);
				}
				catch (Exception $e)
				{
					echo 'Caught exception: '.  $e->getMessage(), "\n";
				}
				?>
				<td><?php echo $content;?></td>
				<td><?php $this->load->helper('number'); echo byte_format(filesize($filename['log_with_path']));?></td>
			</tr>
			<?php endforeach;?>
			<?php if($this->session->userdata('role') == "admin"):?>
			<tr>
				<td>
					<input type="checkbox" id="chkAll" onClick="CheckUnCheckAll()" />
				</td>
				<td></td>
				<td></td>
				<td><button class="btn btn-danger" type="submit"><?php echo $common_delete;?></button></td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
	</form>
	<div>
		<h3><?php echo $pagination;?></h3>
	</div>
</div>