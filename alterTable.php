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
						//'map'=>'Map','structs'=>'Structs','arrays'=>'Arrays',
						'float'=>'Float','boolean'=>'Boolean');
			$i = 0;
			while ('' != @$array_desc_table[$i])
			{
				$array_desc = explode('	',$array_desc_table[$i]);
				$array_desc_desc[$i]['name'] = $array_desc[0];
				$array_desc_desc[$i]['type'] = $array_desc[1];
				$i++;
			}
			echo $lang['alterTableWarning'];
			echo "<form method=post>";
			echo $lang['renameTable']."(Only rooted-php can do this)<input type=text name=new_table_name value=".$_GET['table']."><br>";
			echo '<table border=1 cellspacing=1 cellpadding=1>';
			$i = 0;
			foreach ($array_desc_desc as $key => $value)
			{
				if(($i % 2) == 0)
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
					//echo '<tr '.$color.'>';
					//echo '<td>';
					if($k == 'type')
					{
						echo "<td>";
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
						echo "</td>";
					}
					if($k == 'name')
					{
						echo "<td>";
						echo "<input type=text name=field_name[] value=".$v.">\n";
						echo "<input type=hidden name=old_field_name[] value=".$v.">\n";
						//echo "<input type=hidden name=field_name[] value=".$v." />\n";
						echo "</td>";
					}
					//echo '</td>';
					$i++;
					//echo '</tr>';
				}
				echo '</tr>';
			}
			$i = 0;
			echo '</table><br>';
			echo '<input type=hidden name=database value='.$_GET['database'].'><input type=hidden name=table value='.$_GET['table'].'>';
			echo '<input type=submit name=submit value='.$lang['submit'].'>';
			echo "</form>";
		}
		else
		{
			if(!@$_POST['confirm'])
			{
				//var_dump($_POST['field_name']);var_dump($_POST['field_type']);
				$i = 0;
				echo "<form name=confirm method=post>";
				echo $sql = "ALTER TABLE ".$_POST['table']." RENAME TO ".$_POST['new_table_name'];
				echo "<input type=hidden name=sql[] value=\"".$sql."\">";
				echo "<br><br>";
				while ($i < count($_POST['field_type']))
				{
					$sql = "ALTER TABLE ".$_POST['table']." CHANGE ".$_POST['old_field_name'][$i]." ".$_POST['field_name'][$i]." ".$_POST['field_type'][$i];
					echo "<br>";
					echo $sql;
					echo "<input type=hidden name=sql[] value=\"".$sql."\">";
					$i++;
				}
				echo "<input type=hidden name=confirm value=1><br>";
				echo "<input type=hidden name=table value=".$_POST['table'].">";
				echo "<input type=hidden name=database value=".$_POST['database'].">";
				echo "Sure???<br>";
				echo "<input type=submit name=submit value=".$lang['submit'].">";
				echo "<input type=button name=cancel value=".$lang['cancel']." onclick=\"window.location='index.php?frame=right'\">";
				echo "</form>";
			}
			else
			{
				foreach ($_POST['sql'] as $k => $v)
				{
					echo @$client->execute($v);
				}
				echo "<script>alert('success');window.location='sqlQuery.php?database=".$_POST['database']."&table=".$_POST['table']."';</script>";
			}
		}
	}
	
	$transport->close();
}
?>