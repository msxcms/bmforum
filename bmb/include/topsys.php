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
require("lang/$language/announcesys.php");


$idm_unique = $data_path;
$announceadmin = 0;
if (empty($job)) {
    echo "Nothing to do.无事可做";
    exit;
} 
$msgg_max = 100; #系统记录的最大总置顶数目
$pagesize = 10; #每页显示数目
if ($job == "") $job = show;
if ($usertype[22] == "1") $announceadmin = 1;
if ($usertype[21] == "1") $announceadmin = 1;
$musia = 0;
if ($announceadmin == 1) $musia = 1;
if ($usertype[12] == "1") $musia = 1;
if ($ztop_true != "1") $announceadmin = 0;
if ($login_status != 1 || $musia != 1) $not_member = 1;
if (!isset($page)) $page = 1;

$user = $username;
if ($job == "write") {
    @include("datafile/cache/pin_thread.php");
    if ($count_pint["ALL"] >= $max_zzd) error_page($gl[230], $gl[475], $gl[475], $ggl[48]);
}
if (empty($process)) {
    include("header.php");
    navi_bar($gl[230], $gl[475]);
    print_confirm();
    include("footer.php");
    exit;
} 

$query = "SELECT * FROM {$database_up}threads WHERE tid='$topid' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);
if (!$row['tid']) exit;
$forumid = $row['forumid'];
get_forum_info("");

if ($verify != $log_hash && $process == 2) $announceadmin = 0;

if ($job == "write") {
    if ($announceadmin != 1) {
        error_page($gl[230], $gl[475], $gl[475], $ggl[18]);
    } 
    if ($process == 2) {
        if (empty($topid) || $announceadmin != 1) {
	        error_page($gl[230], $gl[475], $gl[475], $ggl[18]);
        } 
        
        if ($row['toptype'] == 8) update_pincache($topid, 1, $forum_cid);

        $query = "UPDATE {$database_up}threads SET toptype=9 WHERE tid='$topid' LIMIT 1";
        $result = bmbdb_query($query);
        
        update_pincache($topid, 0, 0);

        // log this action
        if ($beforeactionmess == "yes") {
            mtou($row['author'], $job, $row['title']);
        } 

        $showinfo = "{$row['title']}({$row['author']})";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$row['forumid']}','top$job')";
        $result = bmbdb_query($nquery);
        //finish

        jump_page("forums.php?forumid=$foruid", "$ggl[41]",
            "<strong>$ggl[42]</strong><br /><br />
			$gl[3] <a href='forums.php?forumid=$foruid'>$gl[4]</a> | <a href='topic.php?forumid=$foruid&filename=$topid'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);

        exit;
    } 
} 

if ($job == "delone") {
    if ($announceadmin != 1) {
    	error_page($gl[230], $gl[475], $gl[475], $ggl[18]);
    } 
    
    if ($row['type'] >= 3) $toptype = 1; else $toptype = 0;

    $query = "UPDATE {$database_up}threads SET toptype='$toptype' WHERE tid='$topid' LIMIT 1";
    $result = bmbdb_query($query);
    
    update_pincache($topid, 1, 0);
    
    // log this action
    if ($beforeactionmess == "yes") {
        mtou($row['author'], $job, $row['title']);
    } 

    $showinfo = "{$row['title']}({$row['author']})";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$row['forumid']}','top$job')";
    $result = bmbdb_query($nquery);
    //finish

    jump_page("forums.php?forumid=$row[forumid]", "$ggl[22]",
        "<strong>$ggl[22]</strong><br /><br />
			$gl[3] <a href='forums.php?forumid=$row[forumid]'>$gl[4]</a> | <a href='topic.php?forumid=$row[forumid]&filename=$topid'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
    exit;
} 
function print_confirm()
{
    global $foruid, $topid, $author, $log_hash, $choose_reason, $username, $article, $job, $gl;
    $title = "$gl[173]";
    $chooser_t = explode("\n", $choose_reason);
    $cou = count($chooser_t);
    $chooser_c = "<select name='reasonselection' onchange='document.reasons.actionreason.value=document.reasons.reasonselection.options[document.reasons.reasonselection.selectedIndex].value;'>";
    for($i = 0;$i < $cou;$i++) {
        $chooser_c .= "<option value='{$chooser_t[$i]}'>{$chooser_t[$i]}</option>";
    } 
    $chooser_c .= "</select>";
    $content = "<script type=\"text/javascript\">
//<![CDATA[ 
function validate(theform) {
if (theform.actionreason.value==\"\" || theform.actionreason.value==\"\") {
alert(\"$gl[455]\");
return false; } }
function change(theoption) {
this.reasons.actionreason.value=theoption;
}
//]]>>
</script>
";
// process action
    $content .= "
<form name='reasons' onsubmit=\"return validate(this)\"  action=\"misc.php?p=topsys\" method='post'>$gl[234]<br /><br />$gl[235]<br /><br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
    <input type='hidden' name='job' value='$job' />
    <input type='hidden' name='topid' value='$topid' />
    <input type='hidden' name='foruid' value='$foruid' />
    <input type='hidden' name='process' value='2' />
    <input type='hidden' name='verify' value='$log_hash' />
    </form>";
    	

    msg_box($title, $content);
} 

function mtou($ruser, $action, $topic)
{
    global $userid, $filename, $username, $actionreason, $database_up, $tfshow, $gl, $timestamp, $bbs_title, $short_msg_max;
    if ($action == "delone") {
        $actionshow = "$gl[476]";
    } elseif ($action == "write") {
        $actionshow = "$gl[475]";
    } 
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
