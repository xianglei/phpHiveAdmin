<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

$sql = "select count(*) as count from DBS where NAME != 'default'";
$arr = $meta->GetResult($sql);
echo "Databases: ".$arr[0]['count'] . "<br>\n";
unset($arr);

$sql = "select count(*) as count from TBLS";
echo "Tables: ".$arr[0]['count'] . "<br>\n";
?>