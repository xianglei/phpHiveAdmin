<?php

include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$status = $client->getClusterStatus();
//var_dump($status);
echo "<<< <a href=index.php?frame=right>".$lang['back']."</a><br><br>";
echo 'Task Trackers: '.$status->taskTrackers.'<br />';
echo 'Map Tasks: '.$status->mapTasks.'<br />';
echo 'Reduce Tasks: '.$status->reduceTasks.'<br />';
echo 'Max Map Tasks: '.$status->maxMapTasks.'<br />';
echo 'Max Reduce Tasks: '.$status->maxReduceTasks.'<br />';
echo 'State: '.$status->state.'<br />';

$transport->close();

?>
