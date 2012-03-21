<?php
include_once "config.inc.php";
include_once "templates/style.css";

$etl = new Etl;

if(!@$_GET['filename'])
{
	die($lang['noFileChoose']);
}
else
{
	if(file_exists($env['etl'].$_GET['filename']))
	{
		if(!@$_POST['filename'])
		{
			$str = $etl->GetEtl($_GET['filename']);
			echo "<form method=post>";
			echo "<textarea name=content  cols=\"60\" rows=\"30\">".$str."</textarea><br>";
			echo "<input type=hidden name=filename value=".$_GET['filename'].">";
			echo "<input type=submit value=".$lang['submit'].">&nbsp;&nbsp;<input type=button value=\"".$lang['cancel']."\" onclick=\"javascript:history.back()\">";
			echo "</form>";
		}
		else
		{
			$etl->PutEtl($_POST['content'], $_POST['filename']);
			echo "<script>alert(".$lang['success'].");window.location='execEtl.php'";
		}
	}
	else
	{
		echo "<script>alert($lang['noSuchFile']);history.back();</script>";
	}
}
?>