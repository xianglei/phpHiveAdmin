<?php
session_id();
session_start();

include "static.inc.php";
#comment below if you didn't wanna use authenticate
$auth = new Authorize;

#-----------defination of language--------------

require_once 'langs/lang_en.php';

# langs path en file to use chinese, modify to 'langs/lang_cn.php'

# for my company use only below, you can change it to standard authrize as you wish
#--------------------------

$env["privFile"] = "accesslist.ini";

if($_GET['username'] && $_GET['password'])
{
	$user = $_GET['username'];
	$pass = $_GET['password'];
}
else
{
	if($_POST['username'] && $_POST['password'])
	{
		$user = $_POST['username'];
		$pass = $_POST['password'];
	}
	else
	{
		$user = $_SESSION['username'];
		$pass = $_SESSION['password'];
	}
}


if(($user == "") || ($pass == ""))
{
	include_once "templates/login.html";
	die('');
}
else
{

	$priv = $auth->AuthUser($env["privFile"],$user,$pass);
	if(($priv == FALSE) || ($priv['privdb'] == ""))
	{
		include_once "templates/login.html";
		die('');
	}
	else
	{
		$_SESSION['username'] = $user;
		$_SESSION['password'] = $pass;
		$_SESSION['onlydb'] = $priv['privdb'];
		$_SESSION['role'] = $priv['role'];
	}
}

#comment up if you didn't wanna use authenticate

#-----------defination of HIVE Server and port-----

define('HOST','127.0.0.1');
define('PORT','10000');

#----------defination of meta type and connection variables-------

define('METATYPE', 'mysql');

# METATYPE can set to mysql pgsql derby, derby may need unixODBC of php to connect;
#----------------
define('METADB','127.0.0.1');
define('METAPORT', '3306');
define('METAUSER', 'hive');
define('METAPASS', 'hive');
define('METANAME', 'hive');

define('METASTORE_HOST',"127.0.0.1");
define('METASTORE_PORT',"9083");

#------------------------------------------------------------------
$env['hive_jar'] = '';
#------------------server env important: you must hive a executable hive-cli on this machine----------------------
$env['hadoop_home'] = '';# hadoop root path
$env['hive_home'] = '';# hive root path
$env['java_home'] = '';# jdk root path
$env['lang_set'] = 'zh_CN.UTF-8';#system language set
$env['udf'] = '';#user defined function load command. it should be a jar, if not have an udf , set it to ""
#$env['lang_set'] = 'en_US.UTF-8';
$env['seperator'] = "\t";#\t
#set default columns seperator for your data

#------------------definations of log path and results path, give these path to 777 mode------------
$env['etl'] = './etl/';# need slash end path to put etl configuration files
$env['output_path'] = './results';# not need slash end. For cliQuery.php, where to put stderr output log file and original result file
$env['logs_path'] = './logs/';# need slash end
#--------------------------------------------------------------------------------------------------

$env['showTables'] = 'show tables';

