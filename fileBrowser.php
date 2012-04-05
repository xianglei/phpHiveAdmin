<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

	/*
	//echo str_replace("/","\/",$_GET['dir']);
	if(preg_match('/'.str_replace("/","\/",$_GET['dir']).'/i',$env['hdfsToHiveDir']) == '0')
	{
		$dir = $_GET['dir'];
	}
	else
	{
		$dir = $env['hdfsToHiveDir'];
	}
	*/
	$socket = new TSocket(HADOOP_FS_HOST, HADOOP_FS_PORT);
	$socket->setSendTimeout(10000);
	$socket->setRecvTimeout(20000);
	$transport = new TBufferedTransport($socket);
	$protocol = new TBinaryProtocol($transport);
	$client = new hadoopfs_ThriftHadoopFileSystemClient($protocol);
	$transport->open();
	
	try
	{  
		// create directory
		$dirpathname = new hadoopfs_Pathname(array("pathname" => "/user/root/hadoop"));
		var_dump($dirpathname);
		if($client->exists($dirpathname) == TRUE)
		{
			echo $dirpathname->pathname . " exists.\n";
		}
		else
		{
			$result = $client->mkdirs($dirpathname);
		}
		// put file
		$filepathname = new hadoopfs_Pathname(array("pathname" => $dirpathname->pathname . "/hello.txt"));
		$localfile = fopen("hello.txt", "rb");
		$hdfsfile = $client->create($filepathname);
		while(true)
		{
			$data = fread($localfile, 1024);
			if(strlen($data) == 0)
			{
				break;
			}
			$client->write($hdfsfile, $data);
		}
		$client->close($hdfsfile);
		fclose($localfile);
		// get file
		echo "read file:\n";
		print_r($filepathname);
		$data = "";
		$hdfsfile = $client->open($filepathname);
		print_r($hdfsfile);
		while(true)
		{
			$data = $client->read($hdfsfile, 0, 1024);
			if(strlen($data) == 0)
			{
				break;
			}
			print $data;
		}
		$client->close($hdfsfile);
		echo "listStatus:\n";
		$result = $client->listStatus($dirpathname);
		print_r($result);
		foreach($result as $key => $value)
		{
			if($value->isdir == "1")
			{
				print "dir\t";
			}
			else
			{
				print "file\t";
			}
			print $value->block_replication. "\t" .$value->length. "\t" .$value->modification_time. "\t" .$value->permission. "\t" .$value->owner. "\t" .$value->group. "\t" .$value->path. "\n";
		}
		$hadoop_transport->close();
	}
	catch(Exception $e)
	{
		print_r($e);
	}

//if (is_dir($dir)) {
	/*
    if ($dh = opendir($dir)) {
		echo "<a href=fileBrowser.php?dir=".$env['hdfsToHiveDir'].">Back to Root</a><br><br>";
		echo "<table border=1 cellspacing=1 cellpadding=3>";
		echo "<tr bgcolor=\"#FFFF99\">";
		echo "<td>".$lang['filename']."</td><td>".$lang['filetype']."</td><td>".$lang['filesize']."</td>";
		echo "</tr>";
		$i = 0;
        while (($file = readdir($dh)) !== false)
		{
			
			if(($i % 2) == 0)
			{
				$color = $env['trColor1'];
			}
			else
			{
				$color = $env['trColor2'];
			}
			echo "<tr bgcolor=\"".$color."\">";
			
			if(($file == '.') || ($file == '..'))
			{
				continue;
			}
			else
			{
				if(is_dir($dir.$file))
				{
					echo "<td><a href=fileBrowser.php?dir=".$dir.$file."/>$file </td>\n";
				}
				else
				{
					echo "<td><a href=fileBrowser.php?dir=".$dir.$file.">$file </td>\n";
				}
				echo "<td>".filetype($dir.$file)."</td>\n";
				echo "<td>".filesize($dir.$file)."</td>\n";
			}
			echo "</tr>";
			$i++;
        }
        closedir($dh);
		echo "</table>";
		echo "Files: ".$i;
    }
	*/
//}

?>