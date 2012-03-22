<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$meta = new MysqlMeta();

$sql = "select count(*) as count from DBS where NAME != 'default'";
$arr = $meta->GetResult($sql);
echo $lang['metaDbs']."<a href=metaDetail.php?detail=dbs>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from TBLS";
$arr = $meta->GetResult($sql);
echo $lang['metaTables']."<a href=metaDetail.php?detail=tables>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from COLUMNS";
$arr = $meta->GetResult($sql);
echo "Columns: ".$arr[0]['count'] . "<br>\n";

$sql = "select count(*) as count from PARTITIONS";
$arr = $meta->GetResult($sql);
echo $lang['metaPartitions']."<a href=metaDetail.php?detail=partitions>".$arr[0]['count'] . "</a><br>\n";

$sql = "select count(*) as count from IDXS";
$arr = $meta->GetResult($sql);
echo $lang['metaIndexes']."<a href=metaDetail.php?detail=indexes>".$arr[0]['count'] . "</a><br>\n";

$meta->__destruct();
?>