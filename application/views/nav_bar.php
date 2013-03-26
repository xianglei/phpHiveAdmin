<!--Nav bar area-->
<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="<?php echo $this->config->base_url();?>"><?php echo $common_title;?></a>
		<div class="container">
			<!--<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>-->
			<div class="nav-collapse collapse">
				<ul class="nav">
				<li <?php if($this->router->class == "manage" || $this->router->class == "table"){ echo "class=\"active\"";}?>>
					<a class="active" href="<?php echo $this->config->base_url();?>">
						<?php echo $common_hql_query;?>
					</a>
				</li>
				<!--<li <?php if($this->router->class == "etl"){ echo "class=\"active\"";}?>>
					<a href="<?php echo $this->config->base_url();?>index.php/etl/index/">
						<?php echo $common_etl;?>
					</a>
				</li>-->
				<li <?php if($this->router->class == "hdfs"){ echo "class=\"active\"";}?>>
					<a href="<?php echo $this->config->base_url();?>index.php/hdfs/index/">
						<?php echo $common_hdfs_browser;?>
					</a>
				</li>
				<li <?php if($this->router->class == "templates"){ echo "class=\"active\"";}?>>
					<a href="<?php echo $this->config->base_url();?>index.php/templates/index/">
						<?php echo $common_templates;?>
					</a>
				</li>
				<li <?php if($this->router->class == "user"){ echo "class=\"active\"";}?>>
					<a href="<?php echo $this->config->base_url();?>index.php/user/index/">
						<?php echo $common_user_admin;;?>
					</a>
				</li>
				<li <?php if($this->router->class == "history"){ echo "class=\"active\"";}?>>
					<a href="<?php echo $this->config->base_url();?>index.php/history/index/">
						<?php echo $common_history;?>
					</a>
				</li>
				<li>
					<a href="<?php echo $this->config->base_url();?>index.php/user/logout/">
						<?php echo $common_log_out;?>
					</a>
				</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!--Nav bar area end-->