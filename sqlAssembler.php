<?php

include_once 'config.inc.php';
include_once 'templates/style.css';

if(!$_GET['database'] || '' == $_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	if(!$_GET['table'] || '' == $_GET['table'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		
	}
}
?>