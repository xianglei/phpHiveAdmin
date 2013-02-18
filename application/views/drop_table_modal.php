<div id="drop_table_<?php echo $table_name?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_drop_table;?></h3>
	</div>
	<div class="modal-body">

			<?php echo $common_drop_table;?> <?php echo $table_name;?> ?

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<a class="btn btn-danger" href="<?php echo $this->config->base_url() . "table/droptable/" . $var_db_name . "/" . $table_name;?>"><?php echo $common_submit;?></a>
	</div>
</div>