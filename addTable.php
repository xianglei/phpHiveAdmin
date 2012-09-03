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
	
	echo '<div class="container">';
	echo '<div class="span10">';
	
	echo "<br /><a href=dbStructure.php?database=".$_POST['database']."><i class=icon.backward></i>".$lang['back']."</a><br><br>";
	
	if("" == $_POST['newtablename'] || "" == $_POST['fieldnums'])
	{
		echo "<script>alert('".$lang['addTableAlert']."'); history.back()</script>";
	}
	else
	{
		if(!@$_POST['field_name'] || !@$_POST['field_type'])
		{
			echo "<form name=newTable method=post>";
			echo "<table class=\"table table-bordered table-striped\">";
			echo "<tr class=\"info\">
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
					echo "<tr>\n";
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
						echo "<table class=\"table table-bordered table-striped\">";
						echo "<tr class=\"info\">
						  	<td>".$lang['partitionName']."</td>
						  	<td>".$lang['partitionType']."</td>
						  	<td>".$lang['partitionComment']."</td>
					  		</tr>";
						for ($i = 0; $i < $_POST['partitions']; $i++)
						{
							echo "<tr>\n";
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
				echo "<table class=\"table table-bordered table-striped\">";
				//echo "<tr><td>".$lang['Partition']."</td><td></td></tr>";
				echo "<tr><td>".$lang['externalPath']."</td><td><input type=text name=external value=\"hdfs://\"></td></tr>";
				echo "<tr><td>".$lang['columnTerminator']."</td><td><input type=text name=columnTerminator value=\"\\t\"></td></tr>";
				echo "<tr><td>".$lang['lineTerminator']."</td><td><input type=text name=lineTerminator value=\"\\n\"></td></tr>";
				echo "<tr><td>";
				echo $lang['dataFormat']."</td><td><select name=format>
				<option value=text>".$lang['textFile']."</option>
				<option value=lzop>".$lang['lzoped']."</option>
				<option value=sequence>".$lang['sequenced']."</option>
				<option value=rcfile>".$lang['rcfile']."</option>
				<option value=bzip2>".$lang['bzip2ed']."</option>
				<option value=gzip>".$lang['gziped']."</option>
				</select></td></tr>";
			}
			if(@$_POST['external'] == 1)
			{
				echo "<tr><td>".$lang['asRcfile']."</td><td><input type=text name=as disabled></td></tr>";
			}
			else
			{
				echo "<table class=\"table table-bordered table-striped\"><tr><td>".$lang['asRcfile']."</td><td><input type=text name=as></td></tr>";
				echo "<tr><td>".$lang['columnTerminator']."</td><td><input type=text name=columnTerminator value=\"\\t\"></td></tr>";
				echo "<tr><td>".$lang['lineTerminator']."</td><td><input type=text name=lineTerminator value=\"\\n\"></td></tr>";
			}
			echo "</table><br><br>";
			echo "<input class=\"btn btn-primary\" type=submit name=submit value=".$lang['submit'].">";
			echo "<input type=button class=\"btn btn-success\" name=cancel value=".$lang['cancel']." onclick=\"javascript:window.location='dbStructure.php?database=".$_POST['database']."'\">";
			echo "</form>";
		}
		else
		{
			if(@$_POST['external'] != '' && @$_POST['columnTerminator'] != '')
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
						$stored = " STORED AS TEXTFILE ";
						break;
					case 'bzip2':
						$stored = " STORED AS TEXTFILE ";
						break;
					default:
						$stored = " STORED AS TEXTFILE ";
						break;
				}
				$columnTerminator = stripcslashes($_POST['columnTerminator']);
				$columnTerminator = " ROW FORMAT DELIMITED FIELDS TERMINATED BY \"".$columnTerminator."\" ";
				$lineTerminator = stripcslashes($_POST['lineTerminator']);
				$lineTerminator = " LINES TERMINATED BY \"".$lineTerminator."\" ";
				$path = " LOCATION '".$_POST['external']."' ";
				$as = "";
			}
			elseif(@$_POST['external'] == '' && @$_POST['columnTerminator'] != "")
			{
				$ext = '';
				$tablecomment = ''; #" COMMENT ".$_POST['tablecomment']." ";
				$partition = '';
				$columnTerminator = stripcslashes($_POST['columnTerminator']);
				$columnTerminator = " ROW FORMAT DELIMITED FIELDS TERMINATED BY \"".$columnTerminator."\" ";
				$lineTerminator = stripcslashes($_POST['lineTerminator']);
				$lineTerminator = " LINES TERMINATED BY \"".$lineTerminator."\" ";
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
			$sql = $sql . $tablecomment . $partition .$columnTerminator . $lineTerminator . $stored . $path . $as;
			echo "<br>".$sql."<br>";
			$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');showsd('tableList.php?database=".$_POST['database']."','dbStructure.php?database=".$_POST['database']."');</script>";
		}
	}
	echo "</div>";
	echo "</div>";
	$transport->close();
	
}