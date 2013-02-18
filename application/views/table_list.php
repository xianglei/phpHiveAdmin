<div class="span2">
<table class="table table-hover">
<?php foreach ($table_list as $item):?>
	<tr>
		<td>
			<i class="icon-zoom-in"></i>
			<a href="<?php echo $this->config->base_url();?>manage/query/<?php echo $var_db_name;?>/<?php echo $item;?>">
				<?php echo $item;?>
			</a>
		</td>
	</tr>
<?php endforeach;?>
</table>
</div>