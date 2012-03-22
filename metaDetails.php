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
			echo "<table border=1 cellspacing=1 cellpadding=3>";
			$sql = "select * from DBS";
			$arr = $meta->GetResultRow($sql);
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
			echo "</table>";
			break;
		
		default:
			echo ($lang['invalidEntry']);
			break;
	}
}
?>