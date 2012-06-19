<?php

include_once 'config.inc.php';

if(@$_GET['frame'])
{
	include_once 'templates/'.$_GET['frame'].'.html';
}
else
{
	include_once 'templates/index.html';
}

?>
