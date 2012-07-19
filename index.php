<?php

include_once 'config.inc.php';

if($_SESSION['role'] != "")
{
	if(@$_GET['frame'])
	{
		include_once 'templates/'.$_GET['frame'].'.html';
	}
	else
	{
		include_once 'templates/index.html';
	}
}
else
{
	include_once 'templates/login.html';
}
?>
