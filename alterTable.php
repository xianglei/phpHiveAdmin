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
			
			$selected = " ";
			$type = array('string'=>'String','tinyint'=>'Tiny int(3)','smallint'=>'Small int(5)','int'=>'Int(10)','bigint'=>'Big int(19)','double'=>'Double',
						'map'=>'Map','structs'=>'Structs','arrays'=>'Arrays','float'=>'Float','boolean'=>'Boolean');
			$i = 0;
			while ('' != @$array_desc_table[$i])
			{
				$array_desc = explode('	',$array_desc_table[$i]);
				$array_desc_desc['name'][$i] = $array_desc[0];
				$array_desc_desc['type'][$i] = $array_desc[1];
				$i++;
			}
			echo "<body bgcolor=\"".$env['bodyColor']."\">";
			echo $lang['alterTableWarning'];
			echo "<form method=post>";
			echo '<table border=1>';
			$i = 0;
			foreach ($array_desc_desc as $key => $value)
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
				foreach($value as $k => $v)
				{
					echo '<td>';
					if($key == 'type')
					{
						//echo $v."<br>";
						echo "<select name=field_type[]>";
						foreach($type as $kk => $vv)
						{
							if ($v == $kk)
							{
								$selected = "selected";
							}
							else
							{
								$selected ="";
							}
							echo "<option value=".$kk." ".$selected.">".$vv."</option>";
						}
						echo "</select>";
					}
					if($key == 'name')
					{
						echo $v;
						echo "<input type=hidden name=field_name[] value=".$v." />\n";
					}
					echo '</td>';
					$i++;
				}
				echo '<tr>';
			}
			$i = 0;
			echo '</table><br>';
			echo '<input type=hidden name=database value='.$_GET['database'].'><input type=hidden name=table value='.$_GET['table'].'>';
			echo '<input type=submit name=submit value='.$lang['submit'].'>';
			echo "</form>";
		}
		else
		{
			//var_dump($_POST['field_name']);var_dump($_POST['field_type']);
			$i = 0;
			echo "<body bgcolor=\"".$env['bodyColor']."\">";
			while ($i < count($_POST['field_type']))
			{
				$sql = "ALTER TABLE ".$_POST['table']." CHANGE ".$_POST['field_name'][$i]." ".$_POST['field_name'][$i]." ".$_POST['field_type'][$i]."<br>";
				echo $sql;
				$i++;
			}
		}
	}
	
	$transport->close();
}
?>