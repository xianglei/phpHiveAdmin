<?php
$fa = file('access_list');
if(FALSE == in_array($_SERVER['REMOTE_ADDR'],$fa))
{
	die('Can not access');
}


$GLOBALS['THRIFT_ROOT'] = './libs/';
// load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';
// Set up the transport/protocol/client
require_once 'langs/lang_en.php';
//langs path en file to use chinese, modify to 'langs/lang_cn.php'


define('HOST','192.168.1.49');
define('PORT','10000');

$env['hive_jar'] = '/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar';
$env['showTables'] = 'show tables';
$env['bodyColor'] = '#EFEFEF';
$env['trColor1'] = '#AFAFAF';
$env['trColor2'] = '#DFDFDF';
// Important: varius below is being used only if you mount hdfs to a local filesystem!!!Unless it will cause fatal error
$env['hdfsToHiveDir'] = '/hdfs/data/dw';
//'/hdfs' is a libhdfs mount point to localize directory 

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);
$timer = new Timer;
//Create ThriftHive object

echo "<body bgcolor=\"".$env['bodyColor']."\">";
?>
