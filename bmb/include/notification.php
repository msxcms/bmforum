<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (!defined('INBMFORUM')) die("Access Denied");
require("include/template.php");
require("include/common.inc.php");
require("lang/$language/notification.php");
require("lang/$language/usercp.php");

if ($login_status == 0)	error_page('', $notificationLang['_navbar'], $gl[53], $gl[73], $gl[53]);

$currentMod['notification'] = true;
$msgBox = false;

$firstNid = $lastNid = 0;
$getMessageNum = 30;

$tplName = "notification";

if($_GET['act'] == 'block') {
	if($_POST['save'] == 1) {
		if($_POST['nFilter']) {
			foreach($_POST['nFilter'] as $key=>$value) {
				if(!$notificationTitle[$key]) {
					unset($_POST['nFilter'][$key]);
				}
			}
			$userBlockData = addslashes(serialize($_POST['nFilter']));
			bmbdb_query("REPLACE INTO {$database_up}noticefilter VALUES('$userid', '$userBlockData')");
		} else {
			bmbdb_query("DELETE FROM {$database_up}noticefilter WHERE uid='$userid'");
		}
		$msgBox = true;
	}
	$tplName = "notification_filter";
	$userBlockResult = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}noticefilter WHERE uid='$userid'"));
	$userBlockData = $userBlockResult['filterrule'] ? unserialize($userBlockResult['filterrule']) : array();
} else {

	$result = bmbdb_query("SELECT * FROM {$database_up}notification WHERE receiverid='$userid' ORDER BY timestamp DESC LIMIT $getMessageNum");
	while (false !== ($row = bmbdb_fetch_array($result))) {
		if(!$firstNid) {
			$firstNid = $row['nid'];
		}
		if(!$row['senderid']) {
			$row['sendername'] = $notificationLang['_system'];
		}
		$row['parsedData'] = parseNotification($notificationLang[$row['ntype']], $row['nvalue']);
		$row['date'] = getfulldate($row['timestamp']);
		$lastNid = $row['nid'] ? $row['nid'] : $lastNid;
		$notificationList[] = $row;
	}

	if($firstNid > $userddata['lastreadnote'] || $userddata['unreadnote']) {
		bmbdb_query("UPDATE {$database_up}userlist SET `lastreadnote`='$firstNid',`unreadnote` = 0 WHERE userid='$userid'");
		if(count($notificationList) >= $getMessageNum) {
			bmbdb_query("DELETE FROM {$database_up}notification WHERE nid < '$lastNid' AND receiverid='$userid'");
		}
	}
}

$lang_zone = array("notificationLang"=>$notificationLang, "mmssms"=>$mmssms, "gl"=>$gl, "smlng"=>$smlng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name['notification'] = newtemplate($tplName, $temfilename, $styleidcode, $lang_zone);
require("header.php");
navi_bar('', $notificationLang['_navbar']);
require($template_name['notification']);
eval(load_hook('int_notification_end'));
require("footer.php");

function parseNotification($text, $nvalue)
{
	$nvalue = unserialize($nvalue);
	if($nvalue) {
		foreach($nvalue as $sKey=>$sValue) {
			$text = str_replace('{'.$sKey.'}', $sValue, $text);
		}
	}
	return $text;
}