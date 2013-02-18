<?php foreach($user_list as $row):?>

<div id="drop_user_<?php echo $row->id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="post" action="<?php echo $this->config->base_url();?>user/dropuseraction/">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $common_drop_user;?>: <?php echo $row->username;?></h3>
	</div>
	<div class="modal-body">
		Drop User? <?php echo $row->username;?>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<input type="hidden" name="user_id" value="<?php echo $row->id;?>">
		<input type="submit" name="submit" class="btn btn-danger" value=<?php echo $common_delete;?>>
	</div>
	</form>
</div>

<?php endforeach;?>