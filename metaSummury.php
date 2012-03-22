<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

$sql = "select count(*) as count from DBS where NAME != 'default'";
$arr = $meta->GetResultKey($sql);
echo $lang['metaDbs']."<a href=metaDetails.php?detail=dbs>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) from TBLS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaTables']."<a href=metaDetails.php?detail=tables>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from COLUMNS";
$arr = $meta->GetResultKey($sql);
echo "Columns: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from PARTITIONS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaPartitions']."<a href=metaDetails.php?detail=partitions>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from IDXS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaIndexes']."<a href=metaDetails.php?detail=indexes>".$arr[0]['count'] . "</a><br>\n";

?>