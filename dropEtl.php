<?php
include_once "templates/style.css";
include_once "config.inc.php";

$etl = new Etl;

if(!@$_GET['filename'])
{
	die($lang['noFileChoose']);
}
else
{
	if($etl->DropEtl($_GET['filename']))
	{
		echo "<script>alert(".$lang['success'].");window.location='execEtl.php'";
	}
	else
	{
		die ($lang['unknownError']);
	}
}
?>