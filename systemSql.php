<?php
set_time_limit(0);
$env['hive'] = "/opt/modules/hive/hive-0.7.1";
$env['hadoop'] = "/opt/modules/hadoop/hadoop-0.20.203.0";
$env['java'] = "/usr/java/jdk1.6.0_21";
$sql = "select * from asf.asf_20120224 limit 10";

$exec = $env['hive']." \"".$sql."\"";
echo $exec;
echo exec("export HADOOP_HOME=".$env['hadoop']);
echo exec("export JAVA_HOME=".$env['java']);
echo exec("export HIVE_HOME=".$env['hive']);
echo exec($env['hive']."/bin/hive -e \"".$sql."\"");
?>