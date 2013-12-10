<?php

class Hive_model extends CI_Model
{
	public $hive_host;
	public $hive_port;
	public $socket;
	public $transport;
	public $protocol;
	public $hive;


	public function __construct()
	{ 
		parent::__construct();
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
		
		$this->hive_host = $this->config->item('hive_host');
		$this->hive_port = $this->config->item('hive_port');
		$this->socket = new TSocket($this->hive_host, $this->hive_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->hive = new ThriftHiveClient($this->protocol);
	}


	public function show_databases()
	{
		try
		{
			$this->transport->open();
			$db_array = $this->hive->get_all_databases();
			$this->transport->close();
			$onlydb = $this->session->userdata('onlydb');
			$role = $this->session->userdata('role');
			if($role == "admin")
			{
				$db_array = $db_array;
			}
			else
			{
				if( (count($onlydb) > 0) && $onlydb[0] != "" )
				{
					for($i = 0; $i < count($db_array); $i++)
					{
							if(in_array($db_array[$i], $onlydb))
							{
								$arr[$i] = $db_array[$i];
							}
					}
				}
				else
				{
					$arr = $db_array;
				}
				$this->load->model('utilities_model', 'utils');
				$db_array = $this->utils->array_reindex($arr);
			}
			return $db_array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function create_database($db_name, $db_desc = '')
	{
		$sql = "CREATE DATABASE IF NOT EXISTS ".$db_name." COMMENT '".$db_desc."'";
		try
		{
			$this->transport->open();
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function drop_database($db_name, $del = FALSE)
	{
		try
		{
			$this->transport->open();
			$this->hive->drop_database($db_name, $del);
			$this->transport->close();
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	

/**************************************database*****************************************************/
/***************************************************************************************************/
/***************************************************************************************************/
/**************************************table********************************************************/

	public function create_table($db_name, $tbl_name, $tbl_comment = " ", $cols_name = array(), $cols_type = array(), $cols_comment = array(), 
								$part_name = array(), $part_type = array(), $part_comment = array(),
								$external = ' EXTERNAL_TABLE ', $store_type = 'text', $line_term = '\n', $cols_term = '\t',
								$location = 'hdfs:///user/hive/warehouse/')
	{
		#external = true external_table, external = false, managed_table, other input table_name | MANAGED_TABLE, EXTERNAL_TABLE, VIRTUAL_VIEW, INDEX_TABLE
		#store_type :
		#text => ' STORED AS TEXTFILE ', 
		#lzo => ' STORED AS INPUTFORMAT \"com.hadoop.mapred.DeprecatedLzoTextInputFormat\" OUTPUTFORMAT \"org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat\" '
		#sequence => ' STORED AS SEQUENCEFILE '
		#rcfile => ' STORED AS RCFILE '
		#gzip => ' STORED AS TEXTFILE '
		#bzip2 => ' STORED AS TEXTFILE '
		#default => ' STORED AS TEXTFILE '
		
		switch($external)
		{
			case "MANAGED_TABLE":
				$extern = " ";
				$on_table = " ";
				$if = " IF NOT EXISTS ";
				$location = " ";
				break;
			case "EXTERNAL_TABLE":
				$extern = " EXTERNAL ";
				$on_table = " ";
				$if = " IF NOT EXISTS ";
				if($location != 'hdfs:///user/hive/warehouse/')
					$location = ' LOCATION "' . $location . '" ';
				else
					$location = ' LOCATION "' . $location . $db_name . '.db/' . $tbl_name . '" ';
				break;
			#not support index and view yet
			/*case "INDEX_TABLE":
				$extern = " INDEX ";
				$on_table = " ON ";
				$if = " ";
				$location = " ";
				break;
			case "VIRTUAL_VIEW":
				$extern = " VIEW ";
				$on_table = " ON ";
				$if = " ";
				$location = " ";
				break;*/
		}
		###################
		$sql = "CREATE ";
		$sql = $sql . $extern . " TABLE " . $on_table . $if ." `". $db_name . "`.`" . $tbl_name . "` ";
		###################
		#create external table if not exist `db_name`.`table_name` (
		#create table if not exist `db_name`.`table_name` (
		#create index on `db_name`.`table_name`
		#create view on `db_name`.`table_name`
		$cols = "";
		for($i = 0; $i < count($cols_name); $i++)
		{
			#generate `cols1_name` INT COMMENT 'cols1_comment', `cols2_name` INT COMMENT 'cols2_comment', 
			$cols .= "`".$cols_name[$i]."` ".$cols_type[$i]." COMMENT '".$cols_comment[$i]."',";
		}
		$cols = substr($cols, 0 , -1);
		$cols = "(". $cols . ")";
		
		$tbl_comment = " COMMENT '". $tbl_comment ."'";
		##############
		$sql = $sql . $cols . $tbl_comment;
		##############
		#generate partitions hql
		if($part_name[0] != "")
		{//var_dump($part_name);
			$part = "";
			for($i = 0; $i < count($part_name); $i++)
			{
				$part .= "`".$part_name[$i]."` ".$part_type[$i]." COMMENT '".$part_comment[$i]."',";
			}
			$part = substr($part, 0, -1);
			$part = " PARTITIONED BY (" . $part . ")";
		}
		else
		{
			$part = " ";
		}
		#############
		$sql = $sql . $part;
		#############
		switch($store_type)
		{
			case "text":
				$stored = ' STORED AS TEXTFILE ';
				break;
			case "lzo":
				$stored = ' STORED AS INPUTFORMAT "com.hadoop.mapred.DeprecatedLzoTextInputFormat" OUTPUTFORMAT "org.apache.hadoop.hive.ql.io.HiveIgnoreKeyTextOutputFormat" ';
				break;
			case "sequence":
				$stored = ' STORED AS SEQUENCEFILE ';
				break;
			case "rcfile":
				$stored = ' STORED AS RCFILE ';
				break;
			case "gzip":
				$stored = ' STORED AS TEXTFILE ';
				break;
			case "bzip2":
				$stored = ' STORED AS TEXTFILE ';
				break;
			default:
				$stored = ' STORED AS TEXTFILE ';
				break;
		}
		
		$cols_term = stripcslashes($cols_term);
		$line_term = stripcslashes($line_term);
		$cols_term = " ROW FORMAT DELIMITED FIELDS TERMINATED BY \"".$cols_term."\" ";
		$line_term = " LINES TERMINATED BY \"".$line_term."\" ";
		####################
		$sql = $sql . $cols_term . $line_term . $stored . $location;
		####################
		
		try
		{
			echo $sql;
			$this->transport->open();
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function clone_table($db_name, $tbl_name, $stbl_name, $external = TRUE, $location = 'hdfs:///user/hive/warehouse/')
	{
		if($location == 'hdfs:///user/hive/warehouse/')
		{
			$location = ' LOCATION "hdfs:///user/hive/warehouse/'.$db_name.'.db/'.$tbl_name.'" ';
		}
		else
		{
			$location = ' LOCATION "'. $location.'"';
		}
		if($external == TRUE)
		{
			$sql = "CREATE EXTERNAL TABLE IF NOT EXISTS `".$db_name."`.`".$tbl_name."` LIKE `". $db_name ."`.`" . $stbl_name . "` " . $location;
		}
		else
		{
			$sql = "CREATE TABLE IF NOT EXISTS `".$db_name."`.`".$tbl_name."` LIKE `". $db_name ."`.`" . $stbl_name . "` ";
		}
		
		try
		{
			echo $sql;
			$this->transport->open();
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function drop_table($db_name = 'default', $tbl_name = '', $del = FALSE)
	{
		try
		{
			$this->transport->open();
			$this->hive->drop_table($db_name, $tbl_name, $del);
			$this->transport->close();
			//return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function rename_table($db_name, $src_tbl_name, $dest_tbl_name)
	{
		$sql = "ALTER TABLE ".$src_tbl_name." RENAME TO ".trim($dest_tbl_name);
		try
		{
			$this->transport->open();
			$this->hive->execute("USE ".$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function change_table_external($db_name, $tbl_name, $external)
	{
		if($external == "EXTERNAL_TABLE")
		{
			$external = "TRUE";
		}
		else
		{
			$external = "FALSE";
		}
		
		$sql = 'ALTER TABLE '.$tbl_name.' SET TBLPROPERTIES ("EXTERNAL" = "'.$external.'")';
		try
		{
			$this->transport->open();
			$this->hive->execute("USE ".$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function get_table_detail($db_name = 'default', $tbl_name = '', $key = 'cols')
	{
		# key=cols means columns detail
		# key=bucketCols means bucketCols detail
		# key=sortCols means sortCols detail
		# key=partitionKeys means partitions detail
		# key=properties means other table description

		try
		{
			$this->transport->open();

			$tbl_obj = $this->hive->get_table($db_name, $tbl_name);
			$array = array();
			switch($key)
			{
				case 'cols':
					$cols = $tbl_obj->sd->cols;
					$i = 0;
					foreach($cols as $k=>$v)
					{
						$array['name'][$i] = $v->name;
						$array['type'][$i] = $v->type;
						$array['comment'][$i] = $v->comment;
						$i++;
					}
					break;
				case 'bucketCols':
					$cols = $tbl_obj->sd->bucketCols;
					$i = 0;
					foreach($cols as $k=>$v)
					{
						$array['name'][$i] = $v->name;
						$array['type'][$i] = $v->type;
						$array['comment'][$i] = $v->comment;
						$i++;
					}
					break;
				case 'sortCols':
					$cols = $tbl_obj->sd->bucketCols;
					$i = 0;
					foreach($cols as $k=>$v)
					{
						$array['name'][$i] = $v->name;
						$array['type'][$i] = $v->type;
						$array['comment'][$i] = $v->comment;
						$i++;
					}
					break;
				case 'partitionKeys':
					$cols = $tbl_obj->partitionKeys;
					$i = 0;
					foreach($cols as $k=>$v)
					{
						$array['name'][$i] = $v->name;
						$array['type'][$i] = $v->type;
						$array['comment'][$i] = $v->comment;
						$i++;
					}
					break;
				case 'properties':
					$array = array();
					$array['tableName'] = $tbl_obj->tableName;
					$array['dbName'] = $tbl_obj->dbName;
					$array['owner'] = $tbl_obj->owner;
					$array['createTime'] = $tbl_obj->createTime;
					$array['lastAccessTime'] = $tbl_obj->lastAccessTime;
					$array['retention'] = $tbl_obj->retention;
					$array['location'] = $tbl_obj->sd->location;
					$array['inputFormat'] = $tbl_obj->sd->inputFormat;
					$array['outputFormat'] = $tbl_obj->sd->outputFormat;
					$array['compressed'] = $tbl_obj->sd->compressed;
					$array['numBuckets'] = $tbl_obj->sd->numBuckets;
					$array['serdeInfo_name'] = $tbl_obj->sd->serdeInfo->name;
					$array['serdeInfo_serializationLib'] = $tbl_obj->sd->serdeInfo->serializationLib;
					$array['serialization_format'] = $tbl_obj->sd->serdeInfo->parameters['serialization.format'];
					//$array['line_delim'] = $tbl_obj->sd->serdeInfo->parameters['line.delim'];
					//$array['field_delim'] = $tbl_obj->sd->serdeInfo->parameters['field.delim'];
					//$array['parameters_EXTERNAL'] = $tbl_obj->parameters['EXTERNAL'];
					$array['parameters_transient_lastDdlTime'] = $tbl_obj->parameters['transient_lastDdlTime'];
					$array['viewOriginalText'] = $tbl_obj->viewOriginalText;
					$array['viewExpandedText'] = $tbl_obj->viewExpandedText;
					$array['tableType'] = $tbl_obj->tableType;
					$array['privileges'] = $tbl_obj->privileges;
					break;
				default:
					break;
			}
			$this->transport->close();
			
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function get_table_cols($db_name = 'default', $tbl_name = '')
	{
		try
		{
			$this->transport->open();
			$obj = $this->hive->get_schema($db_name, $tbl_name);
			$array_cols = array();
			$i = 0;
			foreach($obj as $k => $v)
			{
				$array_cols['name'][$i] = $v->name;
				$array_cols['type'][$i] = $v->type;
				$array_cols['comment'][$i] = $v->comment;
				$i++;
			}
			$this->transport->close();
			
			return $array_cols;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function get_example_data($db_name = 'default', $tbl_name = '', $limit = '2')
	{
		try
		{
			$this->transport->open();
			$this->hive->execute('use ' . $db_name);
			$this->hive->execute('select * from ' . $tbl_name . ' limit ' . $limit);
			$arr_tmp = $this->hive->fetchAll();
			$i = 0;
			$array = array(array());
			foreach(@$arr_tmp as $k => $v)
			{
				$arr = explode('	',$v);
				foreach($arr as $key => $value)
				{
					#Replace html tags to special characters
					$value = str_replace('<','&lt;', trim($value));
					$value = str_replace('>','&gt;', trim($value));
					#data split into a matrix
					$array[$i][$key] = $value;
				}
				$i++;
			}
			$this->transport->close();
			if(count($array) > 0)
				return $array;
			else
				return array();
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function desc_table_hiveudfs($db_name = 'default', $tbl_name = '')
	{
		$str = "";
		try
		{
			#Hql language defination file
			$file = __DIR__ . "/../../js/hiveudfs.txt";
			
			if($tbl_name == "" || !$tbl_name)
			{
				#Use default defination as an array if not set table name
				$array = file($file);
			}
			else
			{
				#insert Table name and column names into Hql language defination arrays
				$cols = $this->get_table_cols($db_name, $tbl_name);
				
				$array_col_names=array();
				for($i = 0; $i<count($cols['name']); $i++)
				{
					$arr_col_names[$i] = trim($cols['name'][$i]);
				}
				$array_table = array($tbl_name,$db_name);
				$array = file($file);
				$array = array_merge($array, $array_table, $arr_col_names);
			}
			foreach ($array as $key => $value)
			{
				$str .= trim($value)."\n";
			}
			return substr($str,0,-1);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function show_tables($db_name = 'default')
	{
		try
		{
			$this->transport->open();
			$tbl_array = $this->hive->get_all_tables($db_name);
			$this->transport->close();
			$tbl_array = array_reverse($tbl_array);
			return $tbl_array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
/**************************************table********************************************************/
/***************************************************************************************************/
/***************************************************************************************************/
/**************************************columns******************************************************/
	
	public function add_columns($db_name = 'default', $tbl_name = '', $cols_name = array(), $cols_type = array(), $cols_comment = array())
	{
		$cols = "";
		for($i = 0; $i < count($cols_name); $i++)
		{
			#generate `cols1_name` INT COMMENT 'cols1_comment', `cols2_name` INT COMMENT 'cols2_comment', 
			$cols .= "`".$cols_name[$i]."` ".$cols_type[$i]." COMMENT '".$cols_comment[$i]."',";
		}
		$cols = substr($cols, 0 , -1);
		$cols = "(". $cols . ")";
		
		$sql = "ALTER TABLE ".$tbl_name." ADD COLUMNS " . $cols;
		
		try
		{
			$this->transport->open();
			$this->hive->execute("USE ".$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function drop_columns($db_name = 'default', $tbl_name = '', $drop_column_name)
	{
		$cols = $this->get_table_detail($db_name, $tbl_name, 'cols');
		
		$str = '';
		for($i = 0; $i < count($cols['name']); $i++)
		{
			if($cols['name'][$i] == $drop_column_name)
			{
				$cols['name'][$i] = '';
				$cols['type'][$i] = '';
				$cols['comment'][$i] = '';
			}
			if($cols['name'][$i] != "")
			{
				$str .= " `" . $cols['name'][$i] . "` " . $cols['type'][$i] . " COMMENT '" . $cols['comment'][$i] . "',";
			}
			else
			{
				continue;
			}
		}
		
		$str = substr($str, 0, -1);
		$str = " ( ". $str . " ) ";
		$sql = "ALTER TABLE ".$tbl_name." REPLACE COLUMNS " . $str;
		
		try
		{
			$this->transport->open();
			$this->hive->execute("USE ".$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function change_column($db_name = 'default', $tbl_name = '', $src_col_name, 
									$dest_col_name, $dest_col_type, $dest_col_comment)
	{
		$sql = "ALTER TABLE ". $tbl_name ." CHANGE `".$src_col_name."` `".$dest_col_name."` ".$dest_col_type." COMMENT '".$dest_col_comment."'" ;
		try
		{
			$this->transport->open();
			$this->hive->execute("USE ".$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function move_column($db_name = 'default', $tbl_name = '', $src_col_name, $src_col_type, $pos = 'AFTER', $dest_col_name)
	{
		#$pos = AFTER | FIRST
		if($pos == "FIRST")
		{
			$dest_col_name = '';
		}
		$sql = "ALTER TABLE `".$db_name."`.`". $tbl_name ."` CHANGE `".$src_col_name."` `".$src_col_name."` ".$pos." ". $dest_col_name;
		try
		{
			$this->transport->open();
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
/**************************************columns******************************************************/
/***************************************************************************************************/
/***************************************************************************************************/
/**************************************partitions***************************************************/
	
/**************************************partitions***************************************************/
/***************************************************************************************************/
/***************************************************************************************************/
/**************************************load data****************************************************/
	
	public function load_data($db_name = 'default', $tbl_name = '', $local = 'HDFS', $path = '/tmp', $partition = '', $overwrite = FALSE)
	{
		# $local = LOCAL | HDFS
		# $path default is /tmp
		if($local == "HDFS")
		{
			$local = " ";
		}
		else
		{
			$local = $local;
		}
		
		if($path == "")
		{
			die('Must enter path');
		}
		
		if($partition == "")
		{
			$partition = " ";
		}
		else
		{
			$partition = " PARTITION " . $partition;
		}
		
		if($overwrite == FALSE)
		{
			$overwrite = " ";
		}
		else
		{
			$overwrite = " OVERWRITE ";
		}
		$sql = "LOAD DATA " . $local . " INPATH '" . $path . "' " .$overwrite . " INTO TABLE " . $tbl_name . $partition;
		try
		{
			$this->transport->open();
			$this->hive->execute('USE '.$db_name);
			$this->hive->execute($sql);
			$this->transport->close();
			return $sql;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
/**************************************load data****************************************************/
/***************************************************************************************************/
/***************************************************************************************************/
/**************************************etcs*********************************************************/

	public function get_query_plan($sql)
	{
		$sql = "EXPLAIN EXTENDED ". $sql;
		try
		{
			$this->transport->open();
			$this->hive->execute($sql);
			$array = $this->hive->fetchAll();
			$this->transport->close();
			$str = "";
			foreach($array as $k => $v)
			{
				$str .= str_replace(" ","&nbsp;",$v)."<br />";
			}
			
			return $str;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_cluster_status()
	{
		try
		{
			$this->transport->open();
			$status = $this->hive->getClusterStatus();
			$this->transport->close();
			$json = json_encode($status);
			return $json;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}


	public function async_execute_hql($command, $file_name, $type, &$code)
	{
		$descriptorspec = array(
			0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			2 => array("pipe", "w") // stderr is a file to write to
		);
		
		$pipes= array();
		
		$process = proc_open($command, $descriptorspec, $pipes);
		
		$output= "";
		
		if (!is_resource($process))
		{
			return false;
		}
		
		#close child's input imidiately
		fclose($pipes[0]);
		
		stream_set_blocking($pipes[1],0);
		stream_set_blocking($pipes[2],0);
		
		$todo= array($pipes[1],$pipes[2]);
		try
		{
			$fp = fopen($file_name, "w");
			#fwrite($fp,$time_stamp."\n\n");
			while( true )
			{
				$read= array(); 
				#if( !feof($pipes[1]) ) $read[]= $pipes[1];
				if( !feof($pipes[$type]) )
					$read[]= $pipes[$type];// get system stderr on real time
				
				if (!$read)
				{
					break;
				}
				
				$ready= stream_select($read, $write=NULL, $ex= NULL, 2);
				
				if ($ready === false)
				{
					break; #should never happen - something died
				}
				
				foreach ($read as $r)
				{
					$s= fread($r,128);
					$output .= $s;
					fwrite($fp,$s);
				}
			
			}
			
			fclose($fp);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
		
		fclose($pipes[1]);
		fclose($pipes[2]);
		
		$code= proc_close($process);
		
		return $output;
	}


	public function cli_query($sql, $finger_print)
	{
		$this->load->model('utilities_model', 'utils');
		$LANG = " export LANG=" . $this->config->item('lang_set') . "; ";
		$JAVA_HOME = " export JAVA_HOME=" . $this->config->item('java_home') . "; ";
		$HADOOP_HOME = " export HADOOP_HOME=" . $this->config->item('hadoop_home') . "; ";
		$HIVE_HOME = " export HIVE_HOME=" . $this->config->item('hive_home'). "; ";
		
		$filename = $this->utils->make_filename($finger_print);
		$log_file = $filename['log_with_path'];
		$out_file = $filename['out_with_path'];
		$run_file = $filename['run_with_path'];
		
		$this->load->helper('file');
		try
		{
			write_file($log_file, $sql);
			$this->load->model('history_model', 'history');
			$this->history->create_history($this->session->userdata('username'), $finger_print);
			
			echo $run_file;
			
			$cmd = $LANG . $JAVA_HOME . $HADOOP_HOME . $HIVE_HOME . $this->config->item('hive_home') . "/bin/hive -f " . $log_file . " > " . $out_file;
			
			$this->async_execute_hql($cmd, $run_file, 2, $code);
			$this->utils->export_csv($finger_print);
			sleep(1);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_query_status($finger_print)
	{
		$this->load->model('utilities_model', 'utils');
		$filename = $this->utils->make_filename($finger_print);
		$run_file = $filename['run_with_path'];
		try
		{
			$array = file($run_file);
		
			if(is_array($array))
			{
				$array = array_reverse($array);
				$text = "";
				foreach($array as $k => $v)
				{
					$text .= trim($v)."<br>";
				}

				$str = $array[0];
				$start_map = strpos($str, "map = ")+6;
				$end_map = strpos($str, "%");
				$len_map = $end_map - $start_map;

				$start_reduce = strpos($str, "reduce = ")+9;
				$end_reduce = strrpos($str, "%");
				$len_reduce = $end_reduce - $start_reduce;

				$map_per = substr($str, $start_map, $len_map);
				$reduce_per = substr($str, $start_reduce, $len_reduce);
			
				if(!is_numeric($map_per) || !is_numeric($reduce_per))
				{
					$map_per = 100;
					$reduce_per = 100;
				}

				$json = '{"map_percent":"'.$map_per.'","reduce_percent":"'.$reduce_per.'","text":"'.$text.'"}';
				return $json;
			}
			else
			{
				die('Do not re-submit!!!');
			}
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_result($finger_print)
	{
		$this->load->model('utilities_model', 'utils');
		$filename = $this->utils->make_filename($finger_print);
		$csv_file = $filename['csv_with_path'];
		
		$this->load->helper('file');
		if(file_exists($csv_file))
		{
			try
			{
				$fp = fopen($csv_file,"r");
				$i = 0;
				$string = "";
				while($i != 30)
				{
					$string .= fgets($fp,4096);
					$i++;
				}
				fclose($fp);
				if(strlen($string) > 0)
				{
					$data_tmp = explode("\n", $string);
					$data_matrix = "";
					for($i = 0; $i < count($data_tmp); $i++)
					{
						$data_matrix[$i] = explode(',', $data_tmp[$i]);
					}
					return $data_matrix; // return a data matrix
				}
				else
				{
					die('No result found');
				}
			}
			catch (Exception $e)
			{
				echo 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		else
		{
			die('Not complete yet, wait and goto history to find.');
		}
	}
	
}
?>
