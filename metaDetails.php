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
	switch (@$_GET['detail']) {
		case 'dbs':
			$sql = "select * from DBS limit ".@$_GET['offset'].",100";
			break;
			
		case 'tables':
			$sql = "select * from TBLS limit ".@$_GET['offset'].",100";
			break;
			
		case 'partitions':
			$sql = "select * from PARTITIONS limit ".@$_GET['offset'].",100";
			break;
			
		case 'indexes':
			$sql = "select * from IDXS limit ".@$_GET['offset'].",100";
			break;
		
		default:
			echo ($lang['invalidEntry']);
			break;
	}

	$arr = $meta->GetResultRow($sql);
	$offset = @$_GET['offset'];
	$starter = ($offset*100)+1;
	if($starter < 0)
	{
		$starter = 0;
	}
	echo "<a href=metaDetails.php?detail=dbs&offset=".$starter.">Next</a> - <a href=metaDetails.php?detail=dbs&offset=".$starter.">Previous</a><br>";
	echo "<table border=1 cellspacing=1 cellpadding=3>";
	$i = 0;
	foreach ($arr as $k => $v)
	{
		if(($i % 2) == 0)
		{
			$color = "bgcolor=\"".$env['trColor1']."\"";
		}
		else
		{
			$color = "bgcolor=\"".$env['trColor2']."\"";
		}
		echo "<tr ".$color.">\n";
		foreach ($v as $kk => $vv)
		{
			echo "<td>";
			echo $vv;
			echo "</td>";
		}
		echo "</tr>";
		$i++;
	}
	echo "</table><br>";
	echo "<a href=metaDetails.php?detail=dbs&offset=".$starter.">Next</a> - <a href=metaDetails.php?detail=dbs&offset=".$starter.">Previous</a>";
}
?>