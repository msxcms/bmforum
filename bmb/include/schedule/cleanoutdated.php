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

global $database_up, $timestamp, $bbsdetime;

//更新今日发帖数
$thelastest = bmbdb_query_fetch("SELECT * FROM {$database_up}lastest WHERE pageid='index'");

$lasttodaytime = gmdate("zY", $thelastest['lasttodaytime'] + $bbsdetime * 3600);
$lasttodaytime_a = gmdate("zY", $timestamp + $bbsdetime * 3600);
if ($lasttodaytime != $lasttodaytime_a) {
    $thelastest['ydaynew'] = $thelastest['todaynew'];
    $thelastest['todaynew'] = 0;
    $nquery = "UPDATE {$database_up}lastest SET lasttodaytime='$timestamp',ydaynew='{$thelastest['ydaynew']}',todaynew=0 WHERE pageid='index'";
    $result = bmbdb_query($nquery);
}

//删除过期通知
$removeTimestamp = $timestamp - 86400*30; //清理30天的通知
bmbdb_query("SELECT * FROM {$database_up}notification WHERE timestamp < $removeTimestamp");
