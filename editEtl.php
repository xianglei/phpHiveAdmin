<?php
include_once "templates/style.css";
include_once "config.inc.php";

if(!@$_GET['filename'])
{
	die($lang['noFileChoose']);
}
else
{
	if(file_exists("./etl/".$_GET['filename']))
	{
		if(!@$_POST['filename'])
		{
			$fp = fopen("./etl/".$_GET['filename'],"r");
			while (!feof($fp))
			{
				$str .= fread($fp,128);
			}
			fclose($fp);
			echo "<form method=post>";
			echo "<textarea name=content  cols=\"50\" rows=\"30\">".$str."</textarea><br>";
			echo "<input type=hidden name=filename value=".$_GET['filename'].">";
			echo "<input type=submit value=".$lang['submit'].">&nbsp;&nbsp;<input type=button value=\"".$lang['cancel']."\" onclick=\"javascript:history.back()\">";
			echo "</form>";
		}
		else
		{
			var_dump($_POST);
		}
	}
	else
	{
		echo "<script>alert('File not exists???');history.back();</script>";
	}
}
?>