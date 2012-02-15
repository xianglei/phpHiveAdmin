<?php
include_once 'templates/style.css';

if(!@$_POST['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_POST['database']);
	
	 if("" == $_POST['newtablename'] || "" == $_POST['fieldnums'])
	 {
		echo "<script>alert('".$lang['addTableAlert']."'); history.back()</script>";
	 }
	 else
	 {
		if(!@$_POST['field_name'] || !@$_POST['field_type'])
		{
			echo "<form name=newTable method=post>";
			echo "<table border=1>";
			echo "<tr bgcolor=\"#FFFF99\">
					  <td>".$lang['fieldName']."</td>
					  <td>".$lang['fieldType']."</td>
				  </tr>";
			$type = array('string'=>'String','tinyint'=>'Tiny int(3)','smallint'=>'Small int(5)','int'=>'Int(10)','bigint'=>'Big int(19)','double'=>'Double',
						'map'=>'Map','structs'=>'Structs','arrays'=>'Arrays','float'=>'Float','boolean'=>'Boolean');
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
				echo "</tr>\n";
			}
			echo "<input type=hidden name=database value=".$_POST['database'].">";
			echo "<input type=hidden name=newtablename value=".$_POST['newtablename'].">";
			echo "<input type=hidden name=fieldnums value=".$_POST['fieldnums'].">";
			echo "</table><br>";
			echo "<input type=submit name=submit value=".$lang['submit'].">";
			echo "</form>";
		}
		else
		{
			$sql = "CREATE TABLE ".$_POST['newtablename']." (";
			$i = 0;
			$str = "";
			while ("" != @$_POST['field_name'][$i])
			{
				$str .= $_POST['field_name'][$i]." ".$_POST['field_type'][$i].",";
				$i++;
			}
			$str = substr($str,0,-1);
			$sql = $sql.$str.")";
			$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');showsd('dbStructure.php?database=".$_POST['database'].",'tableList.php?database=".$_POST['database']."');'</script>";
		}
	 }
}