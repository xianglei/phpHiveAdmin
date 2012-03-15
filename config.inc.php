<?php
$GLOBALS['THRIFT_ROOT'] = './libs/';
// load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.auth.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/ExecutorService/ExecutorService.php';
// Set up the transport/protocol/client
require_once 'langs/lang_en.php';
//langs path en file to use chinese, modify to 'langs/lang_cn.php'

define('HOST','192.168.1.49');
define('PORT','10000');

#------------------
$env['hive_jar'] = '/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar';
#------------------server env
$env['hadoop_home'] = '/opt/modules/hadoop/hadoop-0.20.203.0';# hadoop root path
$env['hive_home'] = '/opt/modules/hive/hive-0.7.1';# hive root path
$env['java_home'] = '/usr/java/jdk1.6.0_21';# jdk root path
#------------------http env
$env['http_ip'] = "192.168.1.43";# Server IP
$env['http_url'] = '/phpHiveAdmin/cliQuery.php';# cliQuery.php uri link not modify it
$env['http_port'] = '80';# server port
#------------------
$env['etl'] = './etl/';# path to etl configuration files
#------------------
$env['output_path'] = '/data2/tmp/phpHiveAdmin';# For cliQuery.php, where to put stderr output log file and original result file
$env['download_path'] = './tmp';# For download.php, where to put result file.
#------------------

$env['showTables'] = 'show tables';
$env['bodyColor'] = '#EFEFEF';
$env['trColor1'] = '#AFAFAF';
$env['trColor2'] = '#DFDFDF';
# Important: varius below is being used only if you mount hdfs to a local filesystem with fusefs tool!!!Unless it will cause fatal error
$env['hdfsToHiveDir'] = '/hdfs/data/dw/';
# '/hdfs' is a libhdfs mount point to localize directory 

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$estrans = new TSocket(ESHOST,ESPORT);
$esprot = new TBinaryProtocol($estrans);
$escl = new ExecutorServiceClient($esprot);

$timer = new Timer;

session_start();
$session_id = session_id();
$session_name = session_name();
session_register("username");
session_register("password");
session_register("onlydb");

$auth = new Authorize;
$_SESSION['username'] = @$_GET['username'];
$_SESSION['password'] = @$_GET['password'];


$_SESSION['onlydb'] = $auth->AuthUser("accesslist",$username,$password);

echo $_SESSION['onlydb']; 
if($onlydb != "")
{       
        echo $onlydb = $auth->AuthUser("accesslist",$username,$password);
}
else
{       
        die("Cannot access");
}
//Create ThriftHive object