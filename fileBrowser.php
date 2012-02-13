<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['dir'])
{
	$dir = $env['hdfsToHiveDir'];
}
else
{
	$dir = $_GET['dir'];
}

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
		echo "<table border=1>";
        while (($file = readdir($dh)) !== false)
		{
			echo "<tr>";
			if(($file == '.') || ($file == '..'))
			{
				continue;
			}
			else
			{
				echo "<td>filename: <a href=fileBrowser.php?dir=".$env['hdfsToHiveDir'].$file."/>$file </td>\n";
				echo "<td>filetype: ".filetype($dir.$file)."</td>\n";
				echo "<td>filesize: ".filesize($dir.$file)."</td>\n";
			}
			echo "</tr>";
        }
        closedir($dh);
		echo "</table>";
    }
}

?>