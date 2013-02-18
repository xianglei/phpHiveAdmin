<div class="span2">

	<div class="btn-group btn-group-vertical">
	<?php if($this->session->userdata('role') == 'admin'):?>
		<a href="<?php echo $this->config->base_url();?>user/index/" class="btn btn-info"><i class="icon-user"></i> <?php echo $common_user_list;?> </a>
	<?php endif;?>
		<a href="<?php echo $this->config->base_url();?>user/changepassword/" class="btn btn-info"><i class="icon-tasks"></i> <?php echo $common_update_password;?> </a>
	</div>

</div>