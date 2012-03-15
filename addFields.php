<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport->open();

	$client->execute('use '.$_GET['database']);
	
	if(!@$_GET['table'])
	{
		echo "<script>window.location=dbStructure.php?database=".$_GET['database']."</script>";
	}
	else
	{
		if(!@$_POST['fieldnums'])
		{
			echo "<form method=post>";
			echo $lang['fieldNums']."<input type=text name=fieldnums>";
			echo "<input type=hidden name=database value=".$_GET["database"].">";
			echo "<input type=hidden name=table value=".$_GET["table"].">";
			echo "<input type=submit name=submit value=".$lang['submit'].">";
			echo "</form>";
		}
		else
		{
			if(!@$_POST['field_name'] || !@$_POST['field_type'])
			{
				echo "<form name=newTable method=post>";
				echo "<table border=1 cellspacing=1 cellpadding=3>";
				echo "<tr bgcolor=\"#FFFF99\">
						<td>".$lang['fieldName']."</td>
						<td>".$lang['fieldType']."</td>
						<td>".$lang['comment']."</td>
					</tr>";
				$type = array('string'=>'String','tinyint'=>'Tiny int(3)','smallint'=>'Small int(5)','int'=>'Int(10)','bigint'=>'Big int(19)','double'=>'Double',
							//'map'=>'Map','structs'=>'Structs','arrays'=>'Arrays',
							'float'=>'Float','boolean'=>'Boolean');
				for ($i = 0; $i < $_POST['fieldnums']; $i++)
				{
					if(($i % 2) == 0)
					{
						$color = $env['trColor1'];
					}
					else
					{
						$color = $env['trColor2'];
					}
					echo "<tr bgcolor=".$color.">\n";
					//-------------
					echo "<td>\n";
					echo "<input type=text name=field_name[]>\n";
					echo "</td>\n";
					//-------------
					echo "<td>\n";
					echo "<select name=field_type[]>";
					foreach($type as $kk => $vv)
					{
						echo "<option value=".$kk.">".$vv."</option>";
					}
					echo "</select>";
					echo "</td>\n";
					//-------------
					echo "<td>\n";
					echo "<input type=text name=comment[]>\n";
					echo "</td>\n";
					//-------------
					echo "</tr>\n";
				}
				echo "<input type=hidden name=database value=".$_POST['database'].">";
				echo "<input type=hidden name=table value=".$_POST['table'].">";
				echo "<input type=hidden name=fieldnums value=".$_POST['fieldnums'].">";
				echo "</table><br>";
				echo "<input type=submit name=submit value=".$lang['submit'].">";
				echo "&nbsp;&nbsp;";
				echo "<input type=button value=".$lang['cancel']." onclick=\"javascript:window.location=dbStructure.php?database=".$_POST['database']."\">";
				echo "</form>";
			}
			else
			{
				$sql = "ALTER TABLE ".$_POST['table']." ADD COLUMNS (";
				$i = 0;
				$str = "";
				while ("" != @$_POST['field_name'][$i])
				{
					$str .= $_POST['field_name'][$i]." ".$_POST['field_type'][$i]." COMMENT '".$_POST['comment'][$i]."',";
					$i++;
				}
				$str = substr($str,0,-1);
				$sql = $sql.$str.")";
				echo $sql."<br>";
				$client->execute($sql);
				echo "<script>alert('".$lang['alterTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
			}	
		}
	}
	
	$transport->close();
}