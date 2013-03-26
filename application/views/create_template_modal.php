<div id="create_template" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="post" action="<?php echo $this->config->base_url();?>index.php/templates/createtemplatesaction/">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo $common_templates_add;?></h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<tr>
				<td>
					<?php echo $common_templates_name;?>
				</td>
				<td>
					<input type="text" placeholder="<?php echo $common_templates_name;?>" name="t_name" />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $common_templates_content;?>
				</td>
				<td>
					<textarea placeholder="<?php echo $common_templates_content;?>" name="t_content"></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $common_close;?></button>
		<button class="btn btn-primary"><?php echo $common_submit;?></button>
	</div>
	</form>
</div>