<?php
if(!@$_GET['filename'])
{
	die($lang['noFileChoose']);
}
else
{
	if(file_exists("./etl/".$_GET['filename']))
	{
		if(!@$_POST['submit'])
		{
			$fp = fopen("./etl/".$_GET['filename']);
			while (!feof($fp))
			{
				$str .= fread($fp,128);
			}
			fclose($fp);
			echo $str;
		}
	}
	else
	{
		echo "<script>alert('File not exists???');history.back();</script>";
	}
}
?>