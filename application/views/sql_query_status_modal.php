<script>

function SqlQuery()
{
	$('#query_submit').addClass('disabled');
	$('#query_submit').attr('href','javascript:void(0)');
	$.post('<?php echo $this->config->base_url();?>index.php/manage/sqlquery/' , {sql:$('#sql').val(), finger_print:$('#finger_print').val(),db_name:$('#db_name').val() }, function(html){
		html = html;
		$('#sql_query_status').html(html);
	});
}

function QueryStatus()
{
	$.getJSON('<?php echo $this->config->base_url();?>index.php/manage/getquerystatus/' + $('#finger_print').val(), function(json){
		json = json;
		map_per = json.map_percent;
		reduce_per = json.reduce_percent;
		text = json.text;
		$('#map_percent').attr("style", "width: " + map_per + "%;")
		$('#reduce_percent').attr("style", "width: " + reduce_per + "%;")
		$('#sql_query_status').html('<small>' + text + '</small>');
	});
	
}

function RefreshStatus(ctrl)
{
	if(ctrl == true)
	{
		self.timer = setInterval(QueryStatus,2000);
	}
	/*else
	{
		clearInterval(self.timer);
	}*/
}

function GetResult()
{
	var finger_print = $('#finger_print').val();
	var href = '<?php echo $this->config->base_url();?>index.php/manage/getresult/' + finger_print;
	window.location = href;
}

function ConfirmClose()
{
	var confirm = window.confirm("请确认查询完成后离开本页");
	if(confirm)
	{
		GetResult();
	}
	else
	{
		return false;
	}
}

</script>

<div id="sql_query_status_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="RefreshStatus(false)">&times;</button>
		<h3 id="myModalLabel"><?php echo $var_db_name;?>.<?php echo $table_name;?></h3>
	</div>
	<div class="modal-body">

			<div class="progress progress-info progress-striped active">
				<div class="bar" style="" id="map_percent">MAP</div>
			</div>
			<div class="progress progress-success progress-striped active">
				<div class="bar" style="" id="reduce_percent">REDUCE</div>
			</div>
			
			<div id="sql_query_status">
			</div>
			<input type="hidden" id="finger_print" value="" />
			<input type="hidden" id="db_name" value="<?php echo $var_db_name;?>" />

	</div>
	<div class="modal-footer">
		<a href="javascript:ConfirmClose()" class="btn"><?php echo $common_cli_done;?></a>
		<a href="javascript:SqlQuery();RefreshStatus(true);" id="query_submit" class="btn btn-primary"><?php echo $common_submit;?></a>
	</div>
</div>