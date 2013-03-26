<script>

function GetQueryPlan()
{
	var hsql = document.getElementById('sql').value;
	
	$.post('<?php echo $this->config->base_url();?>index.php/manage/getqueryplan/' , {sql:hsql}, function(html){
		html = html;
		$('#query_plan').empty();
		$('#query_plan').html(hsql + '<br /><br />' + html);
	});
}

</script>

<div id="get_query_plan" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $var_db_name;?>.<?php echo $table_name;?></h3>
	</div>
	<div class="modal-body">

			<div id="query_plan">
			</div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
	</div>
</div>