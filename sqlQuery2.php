<?php
ignore_user_abort(true);
set_time_limit(0);


if(!$_GET['database'] || '' == $_GET['database'])
{
	die($lang['dieTableChoose']);
}
else
{

	echo $_GET['database'].' --  <a href=dbStructure.php?database='.$_GET['database'].' target="right">Back</a><br /><br />';
	
	include_once 'config.inc.php';
	include_once 'templates/sql_query_navi.html';
	include_once 'templates/style.css';
	echo "<br /><br />";
	
	passthru("./execSql.php \"select count(*) from asf.asf_20122004\"");
}
?>
