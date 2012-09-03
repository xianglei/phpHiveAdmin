<?php

include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

if(!@$_GET['detail'])
{
	die($lang['invalidEntry']);
}
else
{
	echo '<div class="container">';
	echo '<div class="span10">';
	switch (@$_GET['detail']) {
		case 'dbs':
			$sql = "select * from DBS";
			break;
			
		case 'tables':
			$sql = "select * from TBLS";
			break;
			
		case 'partitions':
			$sql = "select * from PARTITIONS";
			break;
			
		case 'indexes':
			$sql = "select * from IDXS";
			break;
		
		default:
			echo ($lang['invalidEntry']);
			break;
	}

	$arr = $meta->GetResultRow($sql);
	
	$records_per_page = 50;
	$pagination = new Zebra_Pagination();
	$pagination->records(count($arr));
	$pagination->records_per_page($records_per_page);
	
	$arr = array_slice(
    $arr,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
	);
	
	echo "<a href=metaSummury.php><i class=icon-backward></i> ".$lang['back']."</a><br><br>";
	echo "<table class=\"table table-bordered table-striped table-hover\">";
	$i = 0;
	foreach (@$arr as $k => $v)
	{
		echo "<tr>\n";
		foreach ($v as $kk => $vv)
		{
			echo "<td>";
			echo $vv;
			echo "</td>";
		}
		echo "</tr>";
		$i++;
	}
	echo "</table>";
	$pagination->render();
	echo "<br>";
	echo "<a href=metaSummury.php><i class=icon-backward></i> ".$lang['back']."</a><br>";
	echo "</div>";
	echo "</div>";
}
?>