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

if ($useraccess != "1" || $admgroupdata[16] != "1") {
    adminlogin();
} 
@set_time_limit(300);

$t = time();
// ------送出header -------------
header("Content-disposition: attachment; filename=user_$t.bmb.sql");
header("Content-type: application/octetstream");
header("Pragma: no-cache");
header("Expires: 0");

echo "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}userlist\n\n\n";

$query = "SELECT * FROM {$database_up}userlist";
$result = bmbdb_query($query);
while (false !== ($row = bmbdb_fetch_array($result))) {
    echo str_replace("\n%", "%", "INSERT INTO `{$database_up}userlist` VALUES ('" . implode("','", $row) . "')\n");
} 

?>