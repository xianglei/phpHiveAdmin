<div class="span10">

<a class="btn btn-success" href="<?php echo $this->config->base_url()?>index.php/manage/downloadresult/<?php echo $this->uri->segment(3,0);?>" target="_blank"><?php echo $common_download_result;?></a>

<br><br>

<table class="table table-striped table-hover table-bordered table-condensed">
	<tr class="info">
		<?php foreach ($sql_columns as $k => $v):?>
		<td>
			<?php echo $v;?>
		</td>
		<?php endforeach;?>
	</tr>
	<?php foreach ($data_matrix as $k => $v):?>
	<tr>
		<?php foreach($v as $kk => $vv):?>
		<td>
			<?php echo $vv;?>
		</td>
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>
</div>