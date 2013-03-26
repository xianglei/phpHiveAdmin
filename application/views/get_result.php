<div class="span10">
<script>
$.get('<?php echo $this->config->base_url()?>index.php/manage/getresultsize/<?php echo $this->uri->segment(3,0);?>',{},function(html){
	$('#filesize').val(html);
});
</script>
<a class="btn btn-success" href="<?php echo $this->config->base_url()?>index.php/manage/downloadresult/<?php echo $this->uri->segment(3,0);?>" target="_blank"><?php echo $common_download_result;?></a><br />
<input type="text" id="filesize" value="" disabled />

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