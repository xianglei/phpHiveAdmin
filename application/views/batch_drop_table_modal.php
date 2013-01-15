<script>

function BatchTableDropView()
{
	var checkBox = document.getElementsByName("selected_table_name[]");
	var checked = "";
	for (i=0; i<checkBox.length; i++)
	{
		if(checkBox[i].checked == true)
		{
			checked += checkBox[i].value + ';';
		}
	}
	var html = 'Drop: ' + checked.substring(0, (checked.length-1)).replace(/;/g, '<br />') + '!!??';
	//alert(json);
	/*$.post('<?php echo $this->config->base_url();?>index.php/table/batchdroptable/<?php echo $var_db_name;?>/',{tables:checked.substring(0, (checked.length-1)) },function (html){
		html = html;
	});*/
	
	$('#table_list').html(html);
}

function ConfirmDropTables()
{
	var checkBox = document.getElementsByName("selected_table_name[]");
	var checked = "";
	for (i=0; i<checkBox.length; i++)
	{
		if(checkBox[i].checked == true)
		{
			checked += checkBox[i].value + ';';
		}
	}
	var html = checked.substring(0, (checked.length-1));
	$.post('<?php echo $this->config->base_url();?>index.php/table/batchdroptable/<?php echo $var_db_name;?>/', {tables:html}, function (html){
		html = html;
		$('#table_list').empty();
		$('#table_list').html(html);
	});
}

</script>

<div id="batch_drop_table" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo $var_db_name;?></h3>
	</div>
	<div class="modal-body">

			<div id="table_list">
			</div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php echo $common_close;?></button>
		<a href="#" class="btn btn-danger" onclick="ConfirmDropTables()"><?php echo $common_delete;?></a>
	</div>
</div>