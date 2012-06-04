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
	echo "<a href=dbStructure.php?database=".$_POST['database'].">".$lang['back']."</a><br><br>";
	
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
			$parttype = array('STRING' => 'STRING', 'INT' => 'INT');
			#make columns
			if(is_numeric($_POST['fieldnums']) && $_POST['fieldnums'] != 0)
			{
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
				echo "<input type=hidden name=tablecomment value=\"".$_POST['tablecomment']."\">";
				echo "<input type=hidden name=newtablename value=".$_POST['newtablename'].">";
				echo "<input type=hidden name=fieldnums value=".$_POST['fieldnums'].">";
				#echo "<input type=hidden name=extenal value=".$_POST['extenal'].">";
				echo "</table><br>";
			}
			else
			{
				die("<script>alert('".$lang['invalidFieldNums']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>");
			}
			#make partitions
			if($_POST['partitions'] != "")
			{
				if(is_numeric($_POST['partitions']))
				{
					if($_POST['partitions'] != 0)
					{
						echo "<table border=1 cellspacing=1 cellpadding=3>";
						echo "<tr bgcolor=\"#FFFF99\">
						  	<td>".$lang['partitionName']."</td>
						  	<td>".$lang['partitionType']."</td>
						  	<td>".$lang['partitionComment']."</td>
					  		</tr>";
						for ($i = 0; $i < $_POST['partitions']; $i++)
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
							echo "<input type=text name=partition_name[]>\n";
							echo "</td>\n";
							//-------------
							echo "<td>\n";
							echo "<select name=partition_type[]>";
							foreach($parttype as $kk => $vv)
							{
								echo "<option value=".$kk.">".$vv."</option>";
							}
							echo "</select>";
							echo "</td>\n";
							//-------------
							echo "<td>\n";
							echo "<input type=text name=partition_comment[]>\n";
							echo "</td>\n";
							//-------------
							echo "</tr>\n";
						}
						echo "</table><br>";
					}
					else
					{
						die("<script>alert('".$lang['invalidPartitionNums']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>");
					}
				}
				else
				{
					die("<script>alert('".$lang['invalidPartitionNums']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>");
				}
			}
			#
			if(@$_POST['external'] == 1)
			{
				echo "<table border=1 cellspacing=1 cellpadding=3>";
				//echo "<tr><td>".$lang['Partition']."</td><td></td></tr>";
				echo "<tr><td>".$lang['externalPath']."</td><td><input type=text name=external value=\"hdfs://\"></td></tr>";
				echo "<tr><td>".$lang['delimiter']."</td><td><input type=text name=delimiter value=\",\"></td></tr>";
				echo "<tr><td>";
				echo $lang['dataFormat']."</td><td><select name=format>
				<option value=text>".$lang['textFile']."</option>
				<option value=lzop>".$lang['lzoped']."</option>
				<option value=sequence>".$lang['sequenced']."</option>
				<option value=rcfile>".$lang['rcfile']."</option>
				<option value=bzip2 disabled>".$lang['bzip2ed']."</option>
				<option value=gzip disabled>".$lang['gziped']."</option>
				</select></td></tr>";
			}
			if(@$_POST['external'] == 1)
			{
				echo "<tr><td>".$lang['asRcfile']."</td><td><input type=text name=as disabled></td></tr>";
			}
			else
			{
				echo "<tr><td>".$lang['asRcfile']."<input type=text name=as></td></tr>";
			}
			echo "</table><br><br>";
			echo "<input type=submit name=submit value=".$lang['submit'].">";
			echo "<input type=button name=cancel value=".$lang['cancel']." onclick=\"javascript:window.location='dbStructure.php?database=".$_POST['database']."'\">";
			echo "</form>";
		}
		else
		{
			if(@$_POST['external'] != '' && @$_POST['delimiter'] != '')
			{
				$ext = " EXTERNAL ";
				$tablecomment = " COMMENT '".$_POST['tablecomment']."' ";
				if(isset($_POST['partition_type']))
				{
					$i = 0;
					$str = "";						
					$partition = " PARTITIONED BY (";
					while ("" != @$_POST['partition_name'][$i])
					{
						$str .= "`".$_POST['partition_name'][$i]."` ".$_POST['partition_type'][$i]." COMMENT '".$_POST['partition_comment'][$i]."',";
						$i++;
					}
					$str = substr($str,0,-1);
					$partition = $partition.$str.")";
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
					case 'lzop':
						$stored = " STORED AS INPUTFORMAT \"com.hadoop.mapred.DeprecatedLzoTextInputFormat\" OUTPUTFORMAT \"org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat\" ";
						break;
					case 'sequence':
						$stored = " STORED AS SEQUENCEFILE ";
						break;
					case 'rcfile':
						$stored = " STORED AS RCFILE ";
						break;
					case 'gzip':
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
				$as = "";
			}
			else
			{
				$ext = '';
				$tablecomment = ''; #" COMMENT ".$_POST['tablecomment']." ";
				$partition = '';
				$limit = '';
				$stored = " ";
				$path = '';
				if($_POST['as'] != "")
				{
					$as = "AS ".str_replace("\"", "'", $_POST['as']);
				}
				else
				{
					$as = '';
				}
			}
			$sql = "CREATE ".$ext." TABLE IF NOT EXISTS `".$_POST['database']."`.`".$_POST['newtablename']."` (";
			$i = 0;
			$str = "";
			while ("" != @$_POST['field_name'][$i])
			{
				$str .= "`".$_POST['field_name'][$i]."` ".$_POST['field_type'][$i]." COMMENT '".$_POST['comment'][$i]."',";
				$i++;
			}
			$str = substr($str,0,-1);
			$sql = $sql.$str.")";
			$sql = $sql . $tablecomment . $partition .$limit . $stored . $path . $as;
			echo "<br>".$sql."<br>";
			$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
		}
	}
	$transport->close();
	
}