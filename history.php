<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(preg_match('/'.str_replace("/","\/",$_GET['dir']).'/i',$env['logs_path']) == '0')
{
	$dir = $_GET['dir'];
}
else
{
	$dir = $env['logs_path'];
}

if ($dh = opendir($dir))
{
	echo "<a href=history.php?dir=".$env['logs_path'].">Back to Root</a><br><br>";

	include_once "templates/search_history.html";

	#Read file list in ./logs/ directory
	$i = 0;
   	while (($file = readdir($dh)) !== false)
	{
		if(($file == '.') || ($file == '..'))
		{
			continue;
		}
		else
		{
			if(!is_dir($dir.$file))
			{
				$file_array[$i] = $file; 
			}
		}
		$i++;
	}
	closedir($dh);
	
	#Filename quick sort by date desc
	$etc = new Etc;
	$file_array = $etc->QuickSortForLogFile($file_array);
	#
	
	#Make Pagination object
	$records_per_page = 20;
	$pagination = new Zebra_Pagination();
	$pagination->records(count($file_array));
	$pagination->records_per_page($records_per_page);
	
	$file_array = array_slice(
    $file_array,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
	);
	
	echo "<table border=1 cellspacing=1 cellpadding=3>";
	echo "<tr bgcolor=\"#FFFF99\">";
	echo "<td>".$lang['filename']."</td><td>".$lang['fileContent']."</td><td>".$lang['filetype']."</td><td>".$lang['filesize']."</td>";
	echo "</tr>";
	
	foreach ($file_array as $index => $file)
	{
		if(($index % 2) == 0)
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
			$tmp = explode("_", $file);
			$tmp = substr($tmp[1]."_".$tmp[2],0,-4);
			if(file_exists($env['output_path']."/hive_res.".$tmp.".out"))
			{
				echo "<td><a href=getResult.php?str=".$tmp.">$file</a></td>\n";
			}
			else
			{
				echo "<td>$file</td>\n";
			}

			echo "<td>";
			$fp = fopen($dir.$file,"r");
			while(!feof($fp)):
				$str .= fgets($fp,1024);
			endwhile;
			echo $str;
			unset ($str);
			echo "</td>";

			echo "<td>".filetype($dir.$file)."</td>\n";
			echo "<td>".filesize($dir.$file)."</td>\n";
		}
		echo "</tr>";
	}
	echo "</table>";
	$pagination->render();
	
}
?>