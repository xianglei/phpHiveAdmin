<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$hql = $_GET['sql'];
if(substr($hql,-1) == ";")
{
	$hql = substr($hql,0,-1);
}
$hql = "EXPLAIN EXTENDED ".$hql;
echo "<br>";
echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";
echo "<hr>";
try 
{
	$res = $client->execute($hql);
	$array = $client->fetchAll();
	foreach($array as $k => $v)
	{
		$echo .= str_replace(" ","&nbsp;",$v)."<br />";
	}
	echo "<font color=red>HQL Syntax OK!!!<br><br></font>";
	echo $echo;
}
catch (Exception $e)
{
	$echo = $e->getMessage();
	$tmp = explode("FAILED",$echo);
	$echo = $tmp[0]."FAILED <font color=red>".$tmp[1]."<font>";
	
	echo "Exception: ".$echo;
}
echo "<hr>";
echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";

//echo 'Field Schemas: '.$status->fieldSchemas.'<br />';
//echo 'Properties: '.$status->properties.'<br />';

$transport->close();

?>
