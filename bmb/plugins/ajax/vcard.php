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

require_once("include/template.php");
require("lang/$language/topic.php");

if(!$_GET['uid'] && !$_GET['username']) {
	$_GET['uid'] = $userid;
}

if($_GET['uid']) {
	$user = get_user_info($_GET['uid'], 'usrid');
} else {
	$user = get_user_info($_GET['username']);
}
if(!$user) {
	die();
}
$avatar = get_user_portait($user['avarts'], true, $user['mailadd']);
$checkuid = $user['userid'];

$userAdd = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}user_add WHERE uid='$checkuid'"));
$user['r_userlevel'] = getUserLevel($user['postamount'], $user['point'], $user['username'], $user['ugnum']);
$user['r_userIcon'] = getUserIcon($user['postamount'], $user['point'], $user['username'], $user['ugnum']);
list($regdate, $regip) = explode("_", $user['regdate']);
$user['regdate']= get_date($regdate);
$user['bym'] = floor($user['point'] / 10);
$user['urlname'] = urlencode($user['username']);

$lang_zone = array("otherimages" => $otherimages, "unreguser" => $unreguser);
$template_name = newtemplate("vcard", $temfilename, $styleidcode, $lang_zone);
require($template_name);