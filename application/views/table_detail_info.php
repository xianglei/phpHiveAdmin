<div class="span8">
	<pre class="alert alert-error">
	Table: <?php echo $var_db_name;?> . <?php echo $table_name;?>
	</pre>
	<div>
	<table class="table table-condensed table-bordered table-striped table-hover">
		<tr class="info">
			<td> <?php echo $common_column_name;?> </td>
			<td> <?php echo $common_column_type;?> </td>
			<td> <?php echo $common_comment;?> </td>
		</tr>
		<?php for($i = 0; $i < count($cols['name']); $i++):?>
		<tr>
			<td> <?php echo $cols['name'][$i];?> </td>
			<td> <?php echo $cols['type'][$i];?> </td>
			<td> <?php echo $cols['comment'][$i];?> </td>
		</tr>
		<?php endfor;?>
	</table>
	</div>
	
	<?php if(count($partitionKeys) > 0):?>
	<div>
	<table class="table table-condensed table-bordered table-striped table-hover">
		<tr class="info">
			<td> <?php echo $common_partition_name;?> </td>
			<td> <?php echo $common_partition_type;?> </td>
			<td> <?php echo $common_comment;?> </td>
		</tr>
		<?php for($i = 0; $i < count($partitionKeys['name']); $i++):?>
		<tr>
			<td> <?php echo $partitionKeys['name'][$i];?> </td>
			<td> <?php echo $partitionKeys['type'][$i];?> </td>
			<td> <?php echo $partitionKeys['comment'][$i];?> </td>
		</tr>
		<?php endfor;?>
	</table>
	</div>
	<?php endif;?>
	
	<div>
	<table class="table table-condensed table-bordered table-striped table-hover">
		<tr class="info">
			<td> <?php echo $common_detailed_name;?> </td>
			<td> <?php echo $common_detailed_type;?> </td>
		</tr>
		<?php foreach($properties as $key => $value):?>
		<tr>
			<td> <?php echo $key;?> </td>
			<td> 
			<?php 
			echo str_replace("\n", '\n', str_replace("\t", '\t', $value));
			?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
	</div>
	
	<a href="<?php echo $this->config->base_url();?>index.php/table/index/<?php echo $var_db_name;?>" class="btn btn-primary"><?php echo $common_back;?></a>
</div>