<?php

$configure = array();

/*
The path in system that where phpHiveAdmin put, please do not change it.

phpHiveAdmin所在文件系统路径，自动获取，请不要修改。
*/
$configure['root_path'] = __DIR__;

/*
Setting up hiveserver thrift host and port,
and metastore thrift host and port, if needed.
Please see `hive --service -help` for more help

设置hiveserver的thrift主机IP和端口
如果需要的话，设置metastore访问的主机和端口
请查看 `hive --service -help`获取thrift帮助和metastore帮助
*/
$configure['hive_thrift_host'] = '127.0.0.1';
$configure['hive_thrift_port'] = '10000';

/*
Setting up languages you need in phpHiveAdmin user interface,
now support chinese or english

设置phpHiveAdmin用户界面所使用的语言
目前支持中文(chinese)和英文(english)
*/
$configure['language'] = 'chinese';

/*
Setting up the system environment that used by phpHiveAdmin

设置phpHiveAdmin所使用的系统环境变量
*/
$configure['hadoop_home'] = '/opt/modules/hadoop/hadoop-1.0.3';
$configure['java_home'] = '/usr/java/default';
$configure['hive_home'] = '/opt/modules/hive/hive-0.9.0';

$configure['lang_set'] = 'zh_CN.UTF-8' ;
//Or en_US.UTF-8, if you are in english countries.
//如果你处在英语国家，请使用en_US.UTF-8

$configure['output_seperator'] = "\t"; 
// The result data columns seperating character.
// 用来分隔结果集数据列的分隔符.

/*
Setting up phpHiveAdmin output path,
please use linux commandline console to chmod these path below to 777

设置phpHiveAdmin的输出路径
请使用Linux命令行终端来将下列路径chmod为777
*/


?>
