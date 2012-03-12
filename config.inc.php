<?php
/*$fa = file('access_list');
foreach($fa as $k => $v)
{
	$fb[$k] = trim($v);
}
if(!in_array(trim($_SERVER['REMOTE_ADDR']),$fb))
{
	die('Can not access');
}*/

$fa = parse_ini_file("access_list.ini",true);
foreach($fa as $k => $v)
{
	foreach ($v as $kk => $vv)
	{
		if(!in_array(trim($_SERVER['REMOTE_ADDR']),$vv))
		{
			die('Can not access');
		}
	}
}


$GLOBALS['THRIFT_ROOT'] = './libs/';
// load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/ExecutorService/ExecutorService.php';
// Set up the transport/protocol/client
require_once 'langs/lang_en.php';
//langs path en file to use chinese, modify to 'langs/lang_cn.php'


define('HOST','192.168.1.49');
define('PORT','10000');

define('ESHOST','192.168.1.49');
define('ESPORT','2012');

$env['hive_jar'] = '/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar';
#------------------server env
$env['hadoop_home'] = '/opt/modules/hadoop/hadoop-0.20.203.0';
$env['hive_home'] = '/opt/modules/hive/hive-0.7.1';
$env['java_home'] = '/usr/java/jdk1.6.0_21';
#------------------http env
$env['http_ip'] = "192.168.1.43";
$env['http_url'] = '/phpHiveAdmin/cliQuery.php';
$env['http_port'] = '80';
#------------------
$env['etl'] = './etl/';
#------------------
$env['output_path'] = '/tmp/phpHiveAdmin';

$env['showTables'] = 'show tables';
$env['bodyColor'] = '#EFEFEF';
$env['trColor1'] = '#AFAFAF';
$env['trColor2'] = '#DFDFDF';
// Important: varius below is being used only if you mount hdfs to a local filesystem with fusefs tool!!!Unless it will cause fatal error
$env['hdfsToHiveDir'] = '/hdfs/data/dw/';
//'/hdfs' is a libhdfs mount point to localize directory 

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$estrans = new TSocket(ESHOST,ESPORT);
$esprot = new TBinaryProtocol($estrans);
$escl = new ExecutorServiceClient($esprot);

$timer = new Timer;
//Create ThriftHive object