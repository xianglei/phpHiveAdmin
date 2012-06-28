<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_POST['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_POST['database']);
	
	if(!@$_POST['table'])
	{
		echo "<script>window.location=dbStructure.php?database=".$_POST['database']."</script>";
	}
	else
	{
		echo "<a href=dbStructure.php?database=".$_POST['database'].">".$lang['back']."</a><br><br>";
		if(!@$_POST['fieldnums'])
		{
			echo "<form method=post>";
			echo $lang['fieldNums']."<input type=text name=fieldnums>";
			echo "<input type=hidden name=database value=".$_POST["database"].">";
			echo "<input type=hidden name=table value=".$_POST["table"].">";
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
						<!--<td>".$lang['partitionName']."</td>-->
						<td>".$lang['partitionSet']."</td>
						<td>".$lang['partitionLocation']."</td>
					</tr>";
				$type = array('string'=>'STRING','int'=>'INT');
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
					#echo "<td>\n";
					#echo "<input type=text name=field_name[]>\n";
					#echo "</td>\n";
					//-------------
					echo "<td>\n";
					/*echo "<select name=field_type[]>";
					foreach($type as $kk => $vv)
					{
						echo "<option value=".$kk.">".$vv."</option>";
					}
					echo "</select>";*/
					echo "<input type=text name=partition_set[]>\n";
					echo "</td>\n";
					//-------------
					echo "<td>\n";
					echo "<input type=text name=partition_location[]>\n";
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
				$i = 0;
				while ("" != @$_POST['field_name'][$i])
				{
					$sql = "ALTER TABLE ".$_POST['table']." ADD IF NOT EXISTS PARTITION ";
				
					$str = "";
					$str .= $_POST['field_name'][$i]." ".$_POST['partition_set'][$i]." LOCATION '".$_POST['partition_location'][$i]."',";

					$str = substr($str,0,-1);
					$sql = $sql.$str."";
					echo $sql."<br>";
					#$client->execute($sql);
					$i++;
				}
				#echo "<script>alert('".$lang['alterTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
			}	
		}
	}
	$transport->close();
}