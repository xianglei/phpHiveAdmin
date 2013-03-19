<?php foreach($templates_list as $lists):?>
<div id="drop_template_<?php echo $lists->id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="post" action="<?php echo $this->config->base_url();?>templates/droptemplateaction/">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?php echo $common_templates_delete;?></h3>
	</div>
	<div class="modal-body">
		<table class="table">
			<tr>
				<td>
					<?php echo $common_templates_name;?>
				</td>
				<td>
					<input type="text" placeholder="<?php echo $common_templates_name;?>" name="d_t_name" id="d_t_name_<?php echo $lists->id;?>" disabled />
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $common_templates_content;?>
				</td>
				<td>
					<textarea placeholder="<?php echo $common_templates_content;?>" name="d_t_content" id="d_t_content_<?php echo $lists->id;?>" disabled></textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" name="d_t_id" id="d_t_id_<?php echo $lists->id;?>" />
		<input type="hidden" name="d_user_id" id="d_user_id_<?php echo $lists->id;?>" />
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $common_close;?></button>
		<input type="submit" class="btn btn-danger" value="<?php echo $common_templates_delete;?>" >
	</div>
	</form>
</div>
<?php endforeach;?>
<script>
function drop_template(t_id)
{
	$.getJSON('<?php echo $this->config->base_url();?>templates/gettemplate/' + t_id,{}, function(json){
		var t_id = json.id;
		var t_name = json.t_name;
		var t_content = json.t_content;
		var user_id = json.user_id;
		$('#d_t_id_' + t_id).val(t_id);
		$('#d_user_id_' + t_id).val(user_id);
		$('#d_t_name_' + t_id).val(t_name);
		$('#d_t_content_' + t_id).val(t_content);
	});
}
</script>