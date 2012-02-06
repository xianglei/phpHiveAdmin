<?php

include_once 'config.inc.php';

$transport->open();

$client->execute('add jar '.$env['hive_jar']);

$status = $client->getClusterStatus();
//var_dump($status);

echo 'Task Trackers: '.$status->taskTrackers.'<br />';
echo 'Map Tasks: '.$status->mapTasks.'<br />';
echo 'Reduce Tasks: '.$status->reduceTasks.'<br />';
echo 'Max Map Tasks: '.$status->maxMapTasks.'<br />';
echo 'Max Reduce Tasks: '.$status->maxReduceTasks.'<br />';
echo 'State: '.$status->state.'<br />';

$transport->close();

?>
