<?php
$GLOBALS['THRIFT_ROOT'] = './libs/';

require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.auth.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.etc.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.etl.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.hive.php';

require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'classes/class.timer.php';
?>
