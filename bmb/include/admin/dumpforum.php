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

if ($useraccess != "1" || $admgroupdata[17] != "1") {
    adminlogin();
} 

@set_time_limit(300);
if (empty($username)) $username = $_COOKIE['bmfadminid'];
if (empty($password)) $password = $_COOKIE['bmfadminpwd'];

$t = time();
// ------送出header -------------
header("Content-disposition: attachment; filename=forum$id_$t.bmb.sql");
header("Content-type: application/octetstream");
header("Pragma: no-cache");
header("Expires: 0");

echo "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}posts {$database_up}threads {$database_up}polls\n\n\n";

$query = "SELECT * FROM {$database_up}posts WHERE forumid='$id'";
$result = bmbdb_query($query);
while (false !== ($row = bmbdb_fetch_array($result))) {
    echo str_replace("[/img]\n\n", "[/img]<br /><br />", "INSERT INTO `{$database_up}posts` VALUES ('" . implode("','", $row) . "')\n");
} 

$query = "SELECT * FROM {$database_up}threads WHERE forumid='$id'";
$result = bmbdb_query($query);
while (false !== ($row = bmbdb_fetch_array($result))) {
    echo str_replace("[/img]\n\n", "[/img]<br /><br />", "INSERT INTO `{$database_up}threads` VALUES ('" . implode("','", $row) . "')\n");
} 

$query = "SELECT * FROM {$database_up}polls WHERE forumid='$id'";
$result = bmbdb_query($query);
while (false !== ($row = bmbdb_fetch_array($result))) {
    echo "INSERT INTO `{$database_up}polls` VALUES ('" . implode("','", $row) . "')\n";
} 
