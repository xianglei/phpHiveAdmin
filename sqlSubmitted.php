<?php
ignore_user_abort(true);
set_time_limit(0);
include_once 'config.inc.php';
include_once 'templates/style.css';

$etc = new Etc;
$auth = new Authorize;

$sha1 = $etc->FingerPrintMake();

#$sql = $auth->AuthSql($_SESSION['role'],@$_POST['sql']);
if($sql == FALSE)
{
	die("<script>alert('".$lang['permissionDenied']."');history.back()</script>");
}
			
$slots = "";
if(substr($sql,-1) != ";")
{
	$sql = "use ".@$_POST['database'].";".$slots.$sql.";";
}
else
{
	$sql = "use ".@$_POST['database'].";".$slots.$sql;
}
$cookie = sha1($mtime);
$sql = str_replace("%", "\000", $sql);//encode for like %

echo "<input class=\"btn btn-success\" type=button value=\"".$lang['getResult']."\" onclick=\"window.open('getResult.php?str=".$sha1."')\">";
echo "<br><br>".$lang['fingerprintOfMapReduce']." ".$sha1;
echo "<br><br>";
echo "SQL: ".$sql;
echo "<br><br>";
echo "<div id=\"stderr\" width=700 height=400 align=left></div>";
echo "
<script>
function RunSql()
{
	$.get(\"cliQuery.php?time=".$sha1."&query=".rawurlencode($sql)."\");
}
function GetResults()
{
	$(\"#stderr\").load(\"refresh.php?str=".$sha1."\");
}
RunSql();
GetResults();
setInterval(GetResults, 2000)
</script>
";

?>