<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

require("datafile/config.php");
require("getskin.php");
include_once("include/bmbcodes.php");

if (empty($forumid) || empty($filename)) {
	ajax_poll_error($ajax_poll, $gl[250]);
	error_page($gl[53], $gl[249], $gl[53], $gl[250]);
} 

get_forum_info("");

if ($login_status == 0) {
	ajax_poll_error($ajax_poll, $gl[254]);
	error_page($navi_bar_des, $navi_bar_l2, $gl[253], $gl[254], $gl[253]);
} 
if ($canvote != "1" || $userpoint < $vote_allow_ww) {
	ajax_poll_error($ajax_poll, $gl[252]);
	error_page($navi_bar_des, $navi_bar_l2, $gl[54], $gl[252], $gl[251]);
} 
if ($_POST['mychoice'] == "") {
	ajax_poll_error($ajax_poll, $gl[256]);
	error_page($navi_bar_des, $gl[255], $gl[255], $gl[256]);
} 
$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);
if ($row['islock'] == 1) {
	ajax_poll_error($ajax_poll, $gl[262]);
	error_page($gl[263], "<a href='forums.php?forumid=$forumid'>$forum_name</a>", $gl[53], $gl[262], $gl[260]);
}



if (strtolower("贵妃") == "贵妃") $username = strtolower($username);

$add_title = " &gt; $gl[257]";
$amountuser = $postamount;

$nquery = "SELECT * FROM {$database_up}polls WHERE id='$filename' LIMIT 0,1";
$nresult = bmbdb_query($nquery);
$nrow = bmbdb_fetch_array($nresult);

$cdetail = explode("_", $nrow['setting']);
if ($cdetail[0] == "m") $countc = count($mychoice);
else $countc = 0;
if ($countc > $cdetail[1]) {
	ajax_poll_error($ajax_poll, $gl[258]);
	error_page($navi_bar_des, $gl[255], $gl[255], $gl[258] . $cdetail[1]);
} 
if ($cdetail[3] == 1 && $timestamp > $cdetail[4]) {
	ajax_poll_error($ajax_poll, $gl[435]);
	error_page($navi_bar_des, $gl[255], $gl[255], $gl[435]);
} 

if ($cdetail[5] == "1" && $amountuser < $cdetail[6]) {
	$err_des = $gl[461] . $cdetail[6] . $gl[462];
	ajax_poll_error($ajax_poll, $err_des);
	error_page($navi_bar_des, $gl[255], $gl[255], $err_des);
} 

if (strpos($nrow['polluser'], "|$username|") !== false) {
	ajax_poll_error($ajax_poll, $gl[259]);
	error_page($gl[53], $gl[257], $gl[53], $gl[259]);
} 
$sta_data_polluser = $nrow['polluser'] . "$username|";
if ($cdetail[0] == "m") {
    $detail = explode("|", $nrow['options']);
    for ($i = 0; $i < $max_poll; $i++) {
        $mychoc = $mychoice[$i];
        if (!empty($detail[$mychoc])) {
            list($name, $value) = explode(",", $detail[$mychoc]);
            $value++;
            $detail[$mychoc] = "$name,$value";
        } 
    } 
} else {
    $detail = explode("|", $nrow['options']);
    if (!empty($detail[$mychoice])) {
        list($name, $value) = explode(",", $detail[$mychoice]);
        $value++;
        $detail[$mychoice] = "$name,$value";
    } 
} 
$sta_data_write = implode("|", $detail);

eval(load_hook('int_vote'));

$nquery = "UPDATE {$database_up}polls SET options = '$sta_data_write',polluser = '$sta_data_polluser' WHERE id = '$filename'";
$result = bmbdb_query($nquery);

if ($postjumpurl == 0) $jumpun = "forums.php?forumid=$forumid";
else $jumpun = "topic.php?filename=$filename";

if ($ajax_poll == 1) {
	echo "1";
} else {
	jump_page("$jumpun", "$gl[261]",
    "<strong>$gl[261]</strong><br /><br />$gl[3] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
}
function ajax_poll_error($ajax_poll, $info = "Access Denied")
{
	if ($ajax_poll == 1) {
    	header("HTTP/1.0 689 Access Denied");
    	echo $info;
    	exit;
    }
}