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

# for my company use only below, you can change it to standard authrize as you wish
if($_GET['username'] != "" && $_GET['password'] != "")
{
	$_SESSION['username'] = $_GET['username'];
	$_SESSION['password'] = $_GET['password'];
}
else
{
	$_SESSION['username'] = $_SESSION['username'];
	$_SESSION['password'] = $_SESSION['password'];
}
#--------------------------

$_SESSION['onlydb'] = $auth->AuthUser("accesslist",$_SESSION['username'],$_SESSION['password']);
if(($_SESSION['onlydb'] == "") && ($_GET['username'] == "" || $_GET['password'] == ""))
{
	die('No access');
}
#comment up if you didn't wanna use authenticate

# load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';
# Set up the transport/protocol/client
require_once 'langs/lang_en.php';
# langs path en file to use chinese, modify to 'langs/lang_cn.php'

define('HOST','192.168.1.49');
define('PORT','10000');
define('METATYPE', 'mysql');
#can set to mysql pgsql derby, derby may need unixODBC to connect;
define('METADB','192.168.1.28');
define('METAPORT', '3306');
define('METAUSER', 'hive');
define('METAPASS', 'hive');
define('METANAME', 'hive');

#------------------
$env['hive_jar'] = '/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar';
#------------------server env
$env['hadoop_home'] = '/opt/modules/hadoop/hadoop-0.20.203.0';# hadoop root path
$env['hive_home'] = '/opt/modules/hive/hive-0.7.1';# hive root path
$env['java_home'] = '/usr/java/jdk1.6.0_21';# jdk root path
#------------------http env
#$env['http_ip'] = "192.168.1.43";# Server IP
#$env['http_url'] = '/phpHiveAdmin/cliQuery.php';# cliQuery.php uri link not modify it
#$env['http_port'] = '80';# server port
#------------------
$env['etl'] = './etl/';# path to etl configuration files
#------------------
$env['output_path'] = '/data2/tmp/phpHiveAdmin';# For cliQuery.php, where to put stderr output log file and original result file
$env['logs_path'] = './logs/';
#------------------
$env['showTables'] = 'show tables';
$env['bodyColor'] = '#EFEFEF';
$env['trColor1'] = '#AFAFAF';
$env['trColor2'] = '#DFDFDF';
# Important: varius below is being used only if you mount hdfs to a local filesystem with fusefs tool!!!Unless it will cause fatal error
$env['hdfsToHiveDir'] = '/hdfs/data/dw/';
# '/hdfs' is a libhdfs mount point to localize directory 
