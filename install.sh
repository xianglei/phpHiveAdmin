#!/bin/sh
chmod 777 results etl logs
echo "Set phpHiveAdmin runtime enviroment"
read -p "Please input Hadoop Home path: " HADOOPHOME
read -p "Please input Hive Home path: " HIVEHOME
read -p "Please input Java Home Path: " JAVAHOME

echo $HADOOPHOME
echo $HIVEHOME
echo $JAVAHOME

sed -i -e "/^\$env\['hadoop_home'\]/{ s@'';@'$HADOOPHOME';@; }" config.inc.php
sed -i -e "/^\$env\['hive_home'\]/{ s@'';@'$HIVEHOME';@; }" config.inc.php
sed -i -e "/^\$env\['java_home'\]/{ s@'';@'$JAVAHOME';@; }" config.inc.php
