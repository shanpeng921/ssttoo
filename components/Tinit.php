<?php

class Tinit {

	public static function checkinUrl($max_eventid, $end_time)
	{
		//echo "http://10.130.130.99/events.php?start_event_id=".$max_eventid."&end=".$end_time."";
		//die;
		return  "http://10.130.130.99/events.php?start_event_id=".$max_eventid."&end=".$end_time."";
	}

	public static function emailStatus()
	{
		return [
			1 => '未发送',
			2 => '已发送',
		];
	}

}