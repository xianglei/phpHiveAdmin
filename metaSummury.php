<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

echo "<<< <a href=index.php?frame=right>".$lang['back']."</a><br><br>";

$sql = "select count(*) as count from DBS where NAME != 'default'";
$arr = $meta->GetResultKey($sql);
echo $lang['metaDbs']."<a href=metaDetails.php?detail=dbs&offset=0>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from TBLS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaTables']."<a href=metaDetails.php?detail=tables&offset=0>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from COLUMNS";
$arr = $meta->GetResultKey($sql);
echo "Columns: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from PARTITIONS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaPartitions']."<a href=metaDetails.php?detail=partitions&offset=0>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from IDXS";
$arr = $meta->GetResultKey($sql);
echo $lang['metaIndexes']."<a href=metaDetails.php?detail=indexes&offset=0>".$arr[0]['count'] . "</a><br>\n";

?>