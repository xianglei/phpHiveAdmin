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
			echo "<input type=hidden name=tablecomment value=".$_POST['tablecomment'].">";
			echo "<input type=hidden name=newtablename value=".$_POST['newtablename'].">";
			echo "<input type=hidden name=fieldnums value=".$_POST['fieldnums'].">";
			//echo "<input type=hidden name=extenal value=".$_POST['extenal'].">";
			echo "</table><br>";
			if(@$_POST['external'] == 1)
			{
				echo "<table border=1 cellspacing=1 cellpadding=3>";
				echo "<tr><td>".$lang['Partition']."</td><td><input type=text name=partition value=\"\"></td></tr>";
				echo "<tr><td>".$lang['externalPath']."</td><td><input type=text name=external value=\"hdfs://\"></td></tr>";
				echo "<tr><td>".$lang['delimiter']."</td><td><input type=text name=delimiter></td></tr>";
				echo "</table>";
				echo $lang['dataFormat']."<select name=format>
				<option value=text>".$lang['textFile']."</option>
				<option value=lzop>".$lang['lzoped']."</option>
				<option value=sequence>".$lang['sequenced']."</option>
				<option value=bzip2 disabled>".$lang['bzip2ed']."</option>
				<option value=gzip disabled>".$lang['gziped']."</option>
				</select><br><br>";
			}			
			echo "<input type=submit name=submit value=".$lang['submit'].">";
			echo "<input type=button name=cancel value=".$lang['cancel']." onclick=\"javascript:window.location='dbStructure.php?database=".$_POST['database']."'\">";
			echo "</form>";
		}
		else
		{
			if(@$_POST['external'] != '' && @$_POST['delimiter'] != '')
			{
				$ext = " EXTERNAL ";
				$tablecomment = $_POST['tablecomment'];
				if($_POST['partition'] != "")
				{
					$partition = " PARTITION BY (".$_POST['partition'].") ";
				}
				else
				{
					$partition = " ";
				}
				
				switch (@$_POST['format'])
				{
					case 'text':
						$stored = " STORED AS TEXTFILE ";
						break;
					case 'lzoped':
						$stored = " STORED AS INPUTFORMAT \"com.hadoop.mapred.DeprecatedLzoTextInputFormat\" OUTPUTFORMAT \"org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat\" ";
						break;
					case 'sequence':
						$stored = " STORED AS SEQUENCEFILE ";
						break;
					case 'bzip2':
						$stored = " STORED AS INPUTFORMAT \"org.apache.hadoop.io.compress.GzipCodec\" OUTPUTFORMAT \"org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat\" ";
						break;
					case 'bzip2':
						$stored = " STORED AS INPUTFORMAT \"org.apache.hadoop.io.compress.BZip2Codec\" OUTPUTFORMAT \"org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat\" ";
						break;
					default:
						$stored = " STORED AS TEXTFILE ";
						break;
				}
				$limit = " ROW FORMAT DELIMITED FIELDS TERMINATED BY \"".$_POST['delimiter']."\" ";
				$path = " LOCATION '".$_POST['external']."' ";
			}
			else
			{
				$ext = '';
				$tablecomment = $_POST['tablecomment'];
				$partition = '';
				$limit = '';
				$stored = " ";
				$path = '';
			}
			$sql = "CREATE ".$ext." TABLE IF NOT EXISTS ".$_POST['database'].".".$_POST['newtablename']." (";
			$i = 0;
			$str = "";
			while ("" != @$_POST['field_name'][$i])
			{
				$str .= $_POST['field_name'][$i]." ".$_POST['field_type'][$i]." COMMENT '".$_POST['comment'][$i]."',";
				$i++;
			}
			$str = substr($str,0,-1);
			$sql = $sql.$str.")";
			$sql = $sql . $tablecomment . $partition .$limit . $stored . $path;
			echo "<br>".$sql."<br>";
			//$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
		}
	}
	$transport->close();
}