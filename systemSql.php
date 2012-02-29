<?php
$env['hive'] = "/opt/modules/hive/hive-0.7.1/bin/hive -e";
$sql = "select * from asf.asf_20120224 limit 10";

$exec = $env['hive']."\"".$sql."\"";
echo $exec;
?>