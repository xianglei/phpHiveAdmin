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

//if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
		echo "<table border=1>";
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
    }
//}

?>