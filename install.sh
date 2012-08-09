#!/bin/sh
if [ ! -f "./install.lock" ]; then
	chmod -R 777 results etl logs
	echo "Set phpHiveAdmin runtime enviroment\n"
	echo "If you are using Cloudera Hadoop/Hive edition, just hit enter for next step\n"
	read -p "Please input Hadoop Home path: " HADOOPHOME
	read -p "Please input Hive Home path: " HIVEHOME
	read -p "Please input Java Home Path: " JAVAHOME

	echo $HADOOPHOME
	echo $HIVEHOME
	echo $JAVAHOME

	sed -i -e "/^\$env\['hadoop_home'\]/{ s@'';@'$HADOOPHOME';@; }" config.inc.php
	sed -i -e "/^\$env\['hive_home'\]/{ s@'';@'$HIVEHOME';@; }" config.inc.php
	sed -i -e "/^\$env\['java_home'\]/{ s@'';@'$JAVAHOME';@; }" config.inc.php
	
	echo "Had been installed, open config.inc.php for more configurations and accesslist.ini for user settings\n"
	
	echo "Lamia Slaytanic" > install.lock
else
	echo "Had been installed, open config.inc.php for more configurations and accesslist.ini for user settings"
fi
