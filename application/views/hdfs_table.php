<div class="span10">
<div class="btn-group">
<a class="btn btn-info" href="<?php echo $this->config->base_url();?>hdfs/index/"><i class="icon-eject"></i><?php echo $common_back_to_root;?></a>
<a class="btn btn-info" href="javascript:history.back()"><i class="icon-backward"></i><?php echo $common_back;?></a>
</div>
<br>
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr class="success">
				<td><?php echo $common_file_property;?></td>
				<td><?php echo $common_file_user;?></td>
				<td><?php echo $common_file_group;?></td>
				<td><?php echo $common_file_size;?></td>
				<td><?php echo $common_file_time;?></td>
				<td><?php echo $common_file_name;?></td>
			</tr>
		</thead>
		<tbody>
			<?php for($i = 1; $i <= count($hdfs_matrix['file_name']); $i++):?>
			<tr>
				<td><?php echo $hdfs_matrix['file_property'][$i];?></td>
				<td><?php echo $hdfs_matrix['file_user'][$i];?></td>
				<td><?php echo $hdfs_matrix['file_group'][$i];?></td>
				<td><?php $this->load->helper('number'); echo byte_format($hdfs_matrix['file_size'][$i]);?></td>
				<td><?php echo $hdfs_matrix['file_time'][$i];?></td>
				<td><a href="<?php echo $this->config->base_url();?>hdfs/index/<?php echo base64_encode( $hdfs_matrix['file_name'][$i]);?>"><?php echo $hdfs_matrix['file_name'][$i];?></a></td>
			</tr>
			<?php endfor;?>
		</tbody>
	</table>
<div class="btn-group">
<a class="btn btn-info" href="<?php echo $this->config->base_url();?>hdfs/index/"><i class="icon-eject"></i><?php echo $common_back_to_root;?></a>
<a class="btn btn-info" href="javascript:history.back()"><i class="icon-backward"></i><?php echo $common_back;?></a>
</div>
<br>
</div>