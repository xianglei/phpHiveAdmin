<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$hql = $_GET['sql'];
echo $hql = "EXPLAIN EXTENDED ".$hql;
echo "<br>";
echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";
$res = $client->execute($hql);
$array = $client->fetchAll();
if(count($array[0]) != "")
{
	foreach($array as $k => $v)
	{
		$echo .= str_replace(" ","&nbsp;",$v)."<br />";
	}
}
else
{
	$echo = "FAILED: Error in semantic analysis";
}
echo $echo;
echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";

//echo 'Field Schemas: '.$status->fieldSchemas.'<br />';
//echo 'Properties: '.$status->properties.'<br />';

$transport->close();

?>
