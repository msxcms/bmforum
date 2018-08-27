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
include("include/bmbcodes.php");
include("include/common.inc.php");

header("Cache-Control: public"); 

if(!$_GET['uid'] && !$_GET['name']) {
	$_GET['uid'] = $userid;
}

if($_GET['uid']) {
	$user = get_user_info($_GET['uid'], 'usrid');
} else {
	$user = get_user_info($_GET['name']);
}
$avatar = get_user_portait($user['avarts'], true, $user['mailadd']);
header("Location: {$avatar['url']}");
exit;
