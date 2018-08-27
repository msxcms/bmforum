<?php
/*
 BMForum Forums Systems
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

if (!defined('INBMFORUM')) die("Access Denied");

if (!defined('ADMINCENTER')) {
	eval(substr(readfromfile("datafile/cache/schedule.php"), 5));
	if ($s_count > 0) runschedule();
}

function runschedule($scheduleid = 0) {
global $timestamp, $s_item, $s_count;
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);
	
	foreach($s_item as $id => $schedule) {
		if($schedule['next'] <= $timestamp || $schedule['id'] == $scheduleid) {
			if ($first_load != 1) {
				if(!file_exists("tmp/running.info")) {
					writetofile("tmp/running.info", "Running");
				} else {
					if((filemtime("tmp/running.info") + 1000)<$timestamp || defined('ADMINCENTER')) writetofile("tmp/running.info", "Running");
						else return;
				}
				$first_load = 1;
			}
			
			
			$scheduleids = array($id);
			if ($schedule['available'] == 1) {
				@include("include/schedule/".basename($schedule['fname']));
			}
			
			schedulenextrun($scheduleids);
		}
	}

	
	
	if ($first_load == 1) {
		@unlink("tmp/running.info");
	}
}

function schedulenextrun($scheduleids) {
	global $database_up, $timestamp, $time_1, $s_item, $s_count;

	if(!is_array($scheduleids) || !$scheduleids) {
		return false;
	}
	
	$hour_offset = $time_1 * 3600;

	$minnow = gmdate('i', $timestamp + $hour_offset);
	$hournow = gmdate('H', $timestamp + $hour_offset);
	$daynow = gmdate('d', $timestamp + $hour_offset);
	$monthnow = gmdate('m', $timestamp + $hour_offset);
	$yearnow = gmdate('Y', $timestamp + $hour_offset);
	$weeknow = gmdate('w', $timestamp + $hour_offset);

	foreach($s_item as $nowid => $scheduleid) {
		if(!@in_array($nowid, $scheduleids)) {
			continue;
		}
		$scheduleid['min'] = explode(" ", $scheduleid['min']);

		if($scheduleid['week'] == -1) {
			if($scheduleid['day'] == -1) {
				$firstday = $daynow;
				$secondday = $daynow + 1;
			} else {
				$firstday = $scheduleid['day'];
				$secondday = $scheduleid['day'] + gmdate('t', $timestamp + $hour_offset);
			}
		} else {
			$firstday = $daynow + ($scheduleid['week'] - $weeknow);
			$secondday = $firstday + 7;
		}

		if($firstday < $daynow) {
			$firstday = $secondday;
		}

		if($firstday == $daynow) {
			$todaytime = scheduletodaynextrun($scheduleid);
			if($todaytime['hour'] == -1 && $todaytime['min'] == -1) {
				$scheduleid['day'] = $secondday;
				$nexttime = scheduletodaynextrun($scheduleid, 0, -1);
				$scheduleid['hour'] = $nexttime['hour'];
				$scheduleid['min'] = $nexttime['min'];
			} else {
				$scheduleid['day'] = $firstday;
				$scheduleid['hour'] = $todaytime['hour'];
				$scheduleid['min'] = $todaytime['min'];
			}
		} else {
			$scheduleid['day'] = $firstday;
			$nexttime = scheduletodaynextrun($scheduleid, 0, -1);
			$scheduleid['hour'] = $nexttime['hour'];
			$scheduleid['min'] = $nexttime['min'];
		}

		$nextrun = gmmktime($scheduleid['hour'], $scheduleid['min'], 0, $monthnow, $scheduleid['day'], $yearnow) - $hour_offset;
//		echo getfulldate($nextrun);

		bmbdb_query("UPDATE {$database_up}schedule SET last='$timestamp', next='$nextrun' WHERE `id`='$scheduleid[id]'"); 
		eval(load_hook('int_schedule_suc'));

	}

	refresh_forumcach("schedule", "s_item", "s_count", "id");

}

function scheduletodaynextrun($schedule, $hour = -2, $min = -2) {
	global $timestamp, $time_1, $hour_offset;

	$hour_offset = $time_1 * 3600;

	$hour = $hour == -2 ? gmdate('H', $timestamp + $hour_offset) : $hour;
	$min = $min == -2 ? gmdate('i', $timestamp + $hour_offset) : $min;

	$nexttime = array();
	if($schedule['hour'] == -1 && !$schedule['min']) {
		$nexttime['hour'] = $hour;
		$nexttime['min'] = $min + 1;
	} elseif($schedule['hour'] == -1 && $schedule['min'] != '') {
		$nexttime['hour'] = $hour;
		if(($nextmin = schedulenextmin($schedule['min'], $min)) === false) {
			++$nexttime['hour'];
			$nextmin = $schedule['min'][0];
		}
		$nexttime['min'] = $nextmin;
	} elseif($schedule['hour'] != -1 && $schedule['min'] == '') {
		if($schedule['hour'] < $hour) {
			$nexttime['hour'] = $nexttime['min'] = -1;
		} else if ($schedule['hour'] == $hour) {
			$nexttime['hour'] = $schedule['hour'];
			$nexttime['min'] = $min + 1;
		} else {
			$nexttime['hour'] = $schedule['hour'];
			$nexttime['min'] = 0;
		}
	} elseif($schedule['hour'] != -1 && $schedule['min'] != '') {

		$nextmin = schedulenextmin($schedule['min'], $min);
		if($schedule['hour'] < $hour || ($schedule['hour'] == $hour && $nextmin === false)) {
			$nexttime['hour'] = -1;
			$nexttime['min'] = -1;
		} else {
			$nexttime['hour'] = $schedule['hour'];
			$nexttime['min'] = $nextmin;
		}
	}

	return $nexttime;
}

function schedulenextmin($nextmins, $minnow) {
	foreach($nextmins as $nextmin) {
		if($nextmin > $minnow) {
			return $nextmin;
		}
	}
	return false;
}
