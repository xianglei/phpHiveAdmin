<?php
class Timer
{
	var $StartTime = 0;
	var $StopTime = 0;
	var $TimeSpent = 0;

	function start()
	{
		$this->StartTime = microtime();
	}

	function stop()
	{
		$this->StopTime = microtime();
	}

	function spent() 
	{
		if ($this->TimeSpent) 
		{
			return $this->TimeSpent;
		} else 
		{
			$StartMicro = substr($this->StartTime,0,10);
			$StartSecond = substr($this->StartTime,11,10);
			$StopMicro = substr($this->StopTime,0,10);
			$StopSecond = substr($this->StopTime,11,10);
			$start = floatval($StartMicro) + $StartSecond;
			$stop = floatval($StopMicro) + $StopSecond;
			$this->TimeSpent = $stop - $start;
			return round($this->TimeSpent,8);
		}
	}
} //end class timer;
/*
$foo='abcd';

$timer = new timer; 
$timer->start();

if (!isset($foo{5})) { echo 'Foo is too short';}

$timer->stop();
echo '运行时间为 ',$timer->spent()
unset($timer);
*/
?>