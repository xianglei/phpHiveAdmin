<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

$sql = "select count(*) as count from DBS where NAME != 'default'";
$arr = $meta->GetResult($sql);
echo "Databases: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from TBLS";
$arr = $meta->GetResult($sql);
echo "Tables: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from COLUMNS";
$arr = $meta->GetResult($sql);
echo "Columns: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from PARTITIONS";
$arr = $meta->GetResult($sql);
echo "Partitions: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from IDXS";
$arr = $meta->GetResult($sql);
echo "indexes: ".$arr[0]['count'] . "<br>\n";
?>