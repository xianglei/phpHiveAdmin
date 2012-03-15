<?php
include_once "config.inc.php";
include_once "templates/style.css";

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
			echo "<textarea name=content  cols=\"60\" rows=\"30\">".$str."</textarea><br>";
			echo "<input type=hidden name=filename value=".$_GET['filename'].">";
			echo "<input type=submit value=".$lang['submit'].">&nbsp;&nbsp;<input type=button value=\"".$lang['cancel']."\" onclick=\"javascript:history.back()\">";
			echo "</form>";
		}
		else
		{
			$fp = fopen("./etl/".$_POST['fielname'],"w");
			fwrite($fp,$_POST['content']);
			fclose($fp);
			echo "<script>alert(".$lang['success'].");window.location='execEtl.php'";
		}
	}
	else
	{
		echo "<script>alert('File not exists???');history.back();</script>";
	}
}
?>