<?php
ignore_user_abort(true);
set_time_limit(0);


if(!$_GET['database'] || '' == $_GET['database'])
{
	die($lang['dieTableChoose']);
}
else
{

	echo $_GET['database'].' --  <a href=dbStructure.php?database='.$_GET['database'].' target="right">Back</a><br /><br />';
	
	include_once 'config.inc.php';
	include_once 'templates/sql_query_navi.html';
	include_once 'templates/style.css';
	echo "<br /><br />";
	
	$transport->open();

	$sql = 'use '.$_GET['database'];
	//echo $sql.'<br /><br />';
	$client->execute($sql);
	//$client->fetchOne();
	
	$sql = 'desc '.$_GET['table'];
	$client->execute($sql);
	$array_desc_table = $client->fetchAll();
	//get table description and explode the desc into a multi-dimensional array
	//获取表说明，并放入二维数组$array_desc_desc
	$i = 0;
	while ('' != @$array_desc_table[$i])
	{
		$array_desc = explode('	',$array_desc_table[$i]);
		$array_desc_desc['name'][$i] = $array_desc[0];
		$array_desc_desc['type'][$i] = $array_desc[1];
		$array_desc_desc['comment'][$i] = $array_desc[2];
		$i++;
	}
	//var_dump($array_desc_desc);

	if(!@$_POST['sql'] || '' == @$_POST['sql'])
	{
		echo '<table border=1 cellspacing=1 cellpadding=3>';
		$i = 0;
		foreach ($array_desc_desc as $value)
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
			foreach($value as $v)
			{
				echo '<td>'.$v.'</td>';
				$i++;
			}
			echo '</tr>';
		}
		echo '</table>';
		include_once 'templates/sql_query.html';
	}
	else
	{
		/*$fp = fsockopen($env['http_ip'], $env['http_port'], $errno, $errstr, 30);
		if (!$fp)
		{
			echo "$errstr ($errno)<br />\n";
		}
		else
		{*/
			$mtime = explode(" ",microtime());
			$date = date("Y-m-d",$mtime[1]);
			$mtime = (float)$mtime[1] + (float)$mtime[0];
			$sha1 = $date."_".sha1($mtime);
			
			$path = $env['http_url']."?time=".$sha1."&query=".base64_encode(@$_POST['sql']);
			$cookie = sha1($mtime);
			
			$ch = curl_init();
			echo $url = 'http://'.$env['http_ip'].":".$env['http_port'].$path;
			$curl_opt = array(CURLOPT_URL, 'http://'.$env['http_ip'].":".$env['http_port'].$path, CURLOPT_RETURNTRANSFER, 1, CURLOPT_TIMEOUT, 1,);
			curl_setopt_array($ch, $curl_opt);
			curl_exec($ch);
			curl_close($ch);
			
			/*echo $fp = stream_socket_client("tcp://".$env['http_ip'].":".$env['http_port'], $errno, $errstr, 30);
			stream_set_blocking($fp,1);
			echo $out = "GET ".$path." \r\n\r\n";
			//$out .= "Host: ".$env['http_ip']."\r\n";
			//$out .= "Content-Length: " . strlen($path) . "\r\n\r\n";
			//$out .= "Connection: Close\r\n\r\n";
			//$out .= "Cookie: ".$cookie."\r\n\r\n";
			//echo $out;
			fputs($fp, $out);
			//while(!feof($fp))
			//{
				//echo $str = fread($fp,1024);
			//}
			fclose($fp);*/
		//}
		
		echo "<iframe width=600 height=400 align=left src=refresh.php?str=".$sha1."></iframe>";

		/*
		$timer->start();
		$sql = $_POST['sql'];
		//add limit to standard sql
		
		echo $sql.'<br /><br />';
		$client->execute($sql);
		$array = $client->fetchAll();

		//construct table desc table
		echo "<table border=1 cellspacing=1 cellpadding=3>\n";
		$i = 0;
		foreach ($array_desc_desc as $value)
		{
			if(0 == $i)
			{
				$color = "bgcolor=\"#FFFF99\"";
			}
			else
			{
				$color = "bgcolor=\"#99FFFF\"";
			}
			echo "<tr ".$color.">\n";
			foreach($value as $v)
			{
				echo "<td>".$v."</td>\n";
				$i++;
			}
			echo "</tr>\n";
			$i++;
		}
		//construct result table
		$i = 0;		
		while ('' != @$array[$i])
		{
			if(($i % 2) == 0)
			{
				$color = "bgcolor=\"".$env['trColor1']."\"";
			}
			else
			{
				$color = "bgcolor=\"".$env['trColor2']."\"";
			}
			echo "<tr ".$color.">\n";
			$arr = explode('	',$array[$i]);
			foreach ($arr as $key => $value)
			{
					$value = str_replace('<','&lt;',$value);
					$value = str_replace('>','&gt;',$value);
					echo "<td>".$value."</td>\n";
			}
			//echo '<td>'.$array[$i].'</td>';
			echo "</tr>\n";
			$i++;
		}
		echo "</table>\n";
		include_once 'templates/sql_query.html';
		$timer->stop();
		echo 'Excution time: '.$timer->spent().'s';
		unset($timer);
	}
	*/
	}
	$transport->close();
}
?>