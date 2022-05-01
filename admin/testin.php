<?php


function converttoSec($str_time){
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
	return  $time_seconds;
}

function diffbtwTime($time1, $time2){
	 $remtimesec = $time1  - $time2;
	// $remtime =  gmdate("i:s", $remtimesec);
	return $remtimesec;
}

$str_time = "03:50";
$str_time2 = "03:40";

 $time1 = converttosec($str_time);
 $time2 =  converttosec($str_time2);

echo diffbtwTime($time1, $time2);

?>