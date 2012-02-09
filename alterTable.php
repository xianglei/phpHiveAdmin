<?php
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);

	if(!@$_GET['table'])
	{
		die ($lang['dieTableChoose']);
	}
	else
	{
		if(!@$_POST['submit'])
		{
			$sql = 'desc '.$_GET['table'];
			$client->execute($sql);
			$array_desc_table = $client->fetchAll();
			//get table description and explode the desc into a multi-dimensional array
			//获取表说明，并放入二维数组$array_desc_desc
			
			$select = "<select name=field_type[]>
			<option value=string>String</option>
			<option value=tinyint>Tiny int(3)</option>
			<option value=smallint>Small int(5)</option>
			<option value=int>Int(10)</option>
			<option value=bigint>Big int(19)</option>
			<option value=double>Double</option>
			<option value=sep>------</option>
			<option value=map>Map</option>
			<option value=structs>Structs</option>
			<option value=arrays>Arrays</option>
			</select>
			";
			$i = 0;
			while ('' != @$array_desc_table[$i])
			{
				$array_desc = explode('	',$array_desc_table[$i]);
				$array_desc_desc['name'][$i] = $array_desc[0];
				$array_desc_desc['type'][$i] = $array_desc[1];
				$array_desc_desc['select'][$i] = $select;
				$i++;
			}
			echo "<body bgcolor=\"".$env['bodyColor']."\">";
			echo '<table border=1>';
			$i = 0;
			foreach ($array_desc_desc as $value)
			{
				if(0 == $i)
				{
					$color = "bgcolor=\"#FFFF99\"";
				}
				else
				{
					$color = "bgcolor=\"#99FFFF\"";
				}
				echo '<tr '.$color.'>';
				foreach($value as $v)
				{
					echo '<td>'.$v.'</td>';
					$i++;
				}
				echo '<tr>'
			}
			$i = 0;
			echo '</table>';
		}
	}
}
?>