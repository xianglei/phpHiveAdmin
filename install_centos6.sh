#!/bin/sh
yum install -y php php-cli php-devel php-common httpd httpd-devel php-mbstring php-mysql php-pdo php-process mysql mysql-devel mysql-server mysql-libs wget lrzsz dos2unix pexpect libxml2 libxml2-devel
service mysqld start
mysql -e "create database if not exists easyhadoop;"
mysql -hlocalhost -uroot easyhadoop < phpHiveAdmin.sql
mkdir -p /var/www/html/phpHiveAdmin
cp -R * /var/www/html/phpHiveAdmin/
mkdir -p /var/www/html/phpHiveAdmin/logs
mkdir -p /var/www/html/phpHiveAdmin/etl
mkdir -p /var/www/html/phpHiveAdmin/results
chmod 777 /var/www/html/phpHiveAdmin/logs /var/www/html/phpHiveAdmin/etl /var/www/html/phpHiveAdmin/results
cd /var/www/html/phpHiveAdmin

echo "/*************************************************************/"
echo "Install basic environment complete, setup config.inc.php for access."
echo "/*************************************************************/"
