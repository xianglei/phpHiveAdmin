<div class="span8">

	<ul class="nav nav-tabs" id="templates_list_tab">
		<li class="active"><a href="#list" data-toggle="tab" data-placement="top" rel="tooltip" title=""><?php echo $common_templates_list;?></a></li>
		<li><a href="#admin" data-toggle="tab" data-placement="top" rel="tooltip" title=""><?php echo $common_templates_admin;?></a></li>
	</ul>
	
	<div id="settings_tab_content" class="tab-content">
		<!--Templats list-->
		<div class="tab-pane fade in active" id="list">
			<table class="table">
			<?php foreach ($templates_list as $v):?>
				<tr>
					<?php if($this->session->userdata('role') == "admin"):?>
					<td>
						<?php echo $v->username;?>
					</td>
					<?php endif;?>
					<td>
					<?php echo $v->t_name;?>
					</td>
					<td>
					<?php echo $v->t_content;?>
					</td>
				</tr>
			<?php endforeach;?>
			</table>
		</div>
		<!--Templates admin-->
		<div class="tab-pane fade in" id="admin">
			<a href="#create_template" role="button" class="btn" data-toggle="modal"><?php echo $common_templates_add;?></a><br />
			<table class="table">
			<?php foreach ($templates_list as $v):?>
				<tr>
					<?php if($this->session->userdata('role') == "admin"):?>
					<td>
						<?php echo $v->username;?>
					</td>
					<?php endif;?>
					<td>
					<?php echo $v->t_name;?>
					</td>
					<td>
					<?php echo $v->create_time;?>
					</td>
					<td>
					<div class="btn-group">
						<a href="#update_template_<?php echo $v->id;?>" role="button" class="btn" data-toggle="modal" onclick="javascript:update_template(<?php echo $v->id;?>)"><?php echo $common_templates_update;?></a>
						<a href="#drop_template_<?php echo $v->id;?>" role="button" class="btn btn-danger" data-toggle="modal" onclick="javascript:drop_template(<?php echo $v->id;?>)"><?php echo $common_templates_delete;?></a>
					</div>
					</td>
				</tr>
			<?php endforeach;?>
			</table>
		</div>
	</div>

</div>