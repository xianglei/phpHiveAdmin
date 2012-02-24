<?php
include_once "templates/style.css";
include_once "config.inc.php";

if(!@$_GET['filename'])
{
	die($lang['noFileChoose']);
}
else
{
	unlink("./etl/".$_GET['filename']);
	echo "<script>alert(".$lang['success'].");window.location='execEtl.php'";
}
?>