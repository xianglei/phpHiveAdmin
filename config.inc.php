<?php
session_id();
session_start();
$GLOBALS['THRIFT_ROOT'] = './libs/';

require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.auth.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.etc.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.etl.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.hive.php';

#comment below if you didn't wanna use authenticate
$auth = new Authorize;

#-----------defination of language--------------
#-----------定义所使用语言----------------------

require_once 'langs/lang_en.php';

# langs path en file to use chinese, modify to 'langs/lang_cn.php'
# 语言包所使用的路径，中文请使用'lang/lang_cn.php'

# for my company use only below, you can change it to standard authrize as you wish
# 仅仅是我公司内部使用所需要的验证，你可以自己改造成自己的标准验证方式

#--------------------------

$env["privFile"] = "accesslist.ini";

if($_GET['username'] && $_GET['password'])
{
	$user = $_GET['username'];
	$pass = $_GET['password'];
}
elseif($_POST['username'] && $_POST['password'])
{
	$user = $_SESSION['username'];
	$pass = $_SESSION['password'];
}
else
{
	$user = $_SESSION['username'];
	$pass = $_SESSION['password'];
}

if(($user == "") || ($pass == ""))
{
	include_once "templates/login.html";
}
else
{

	$onlydb = $auth->AuthUser($env["privFile"],$user,$pass);
	if(($onlydb == FALSE) || ($onlydb == ""))
	{
		include_once "templates/login.html";
	}
	else
	{
		$_SESSION['username'] = $user;
		$_SESSION['password'] = $pass;
		$_SESSION['onlydb'] = $onlydb;
	}
}

#comment up if you didn't wanna use authenticate
#如果不需要验证，请注释掉以上部分

# load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';


#-----------defination of HIVE Server and port-----
#-----------定义Hive Server连接地址与端口----------

define('HOST','192.168.1.49');
define('PORT','10000');

#----------defination of meta type and connection variables-------
#----------定义元数据管理的类型相关变量---------------------------

define('METATYPE', 'mysql');

# METATYPE can set to mysql pgsql derby, derby may need unixODBC of php to connect;
# METATYPE可以设置为mysql,pgsql,derby,但是derby可能会需要php的unixODBC去连接

#----------------
define('METADB','192.168.1.28');
define('METAPORT', '3306');
define('METAUSER', 'hive');
define('METAPASS', 'hive');
define('METANAME', 'hive');

#----------------
#define('METATYPE', 'pgsql');
#define('METADB','192.168.1.28');
#define('METAPORT', '5432');
#define('METAUSER', 'hive');
#define('METAPASS', 'hive');
#define('METANAME', 'hive');
#------------------------------------------------------------------
$env['hive_jar'] = '/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar';
#------------------server env important: you must hive a executable hive-cli on this machine----------------------
#------------------定义环境变量 重要：你必须在本机有一个可执行的hive命令行程序--------------------
$env['hadoop_home'] = '/opt/modules/hadoop/hadoop-0.20.203.0';# hadoop root path
$env['hive_home'] = '/opt/modules/hive/hive-0.7.1';# hive root path
$env['java_home'] = '/usr/java/jdk1.6.0_21';# jdk root path
$env['lang_set'] = 'zh_CN.UTF-8';#system language set
#$env['lang_set'] = 'en_US.UTF-8';
$env['setenv'] = 'export';
#may set to export and setenv
$env['seperator'] = "\t";#\t
#set columns seperator for select
#设置原始数据的分隔符

#------------------definations of log path and results path, give these path to 777 mode------------
#------------------定义日志和结果输出的文件路径，请赋予777权限------------------------------------
$env['etl'] = './etl/';# path to put etl configuration files
$env['output_path'] = '/data2/tmp/phpHiveAdmin';# For cliQuery.php, where to put stderr output log file and original result file
$env['logs_path'] = './logs/';
#--------------------------------------------------------------------------------------------------

$env['showTables'] = 'show tables';
$env['bodyColor'] = '#EFEFEF';
$env['trColor1'] = '#AFAFAF';
$env['trColor2'] = '#DFDFDF';

