<?php
include_once 'templates/style.css';
include_once 'config.inc.php';

if(!@$_POST['filename'])
{
	$dir = dir("./etl");
	
	$i = 0;
	while (false !== ($entry = $dir->read())) 
	{
		if($entry != '.')
		{
			if($entry != '..')
			{
				$filename[$i] = $entry;
			}
		}
		$i++;
	}
	$dir->close();

	echo "<<< <a href=\"javascript:history.back();\">".$lang['back']."</a><br><br>";
	echo "<form method=post>";
	echo "<table border=1 cellspacing=1 cellpadding=3>";
	$i = 0;
	foreach($filename as $k => $v)
	{
		echo "<tr>
		<td>
		<input type=checkbox name=filename[] value=".$v.">
		</td>
		<td>
		".$v."
		</td>
		<td>
		<a href=editEtl.php?filename=".$v.">".$lang['edit']."</a>
		</td>
		<td>
		<a href=# onclick=\"javascript:realconfirm('".$lang['dropEtlConfirm']."','dropEtl.php?filename=".$v."');\">".$lang['delete']."</a>
		</td>
		</tr>";
		$i++;
	}
	echo "</table><br>";
	echo "<input type=submit value=".$lang['execEtl'].">";
	echo "</form>";
}
else
{
	var_dump($_POST);
}