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
include_once("include/common.inc.php");

if (empty($filename) || empty($action)) {
    $content = "$gl[233]<br /><br />$gl[229]";
	error_page("$gl[230]", "Management", $gl[53], $content);

} 
// ---------check-----------
$check_user = 0;
$aquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$line = bmbdb_fetch_array($xresult);
$forumid = $line['forumid'];
$topic_type = $line['type'];
$topic_islock = $line['islock'];
if (!$line['tid']) exit;
get_forum_info("");
tbuser();
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########
if ($usertype[22] == "1") $check_user = 1;
if ($usertype[21] == "1") $check_user = 1;
if ($can_rec != "1") $check_user = 0;

if ($verify != $log_hash && $step == 2) $check_user = 0;

if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>", $gl[53], $content);


} 
$pincancel = "";
if (empty($step)) {
    include("header.php");
    navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
    print_confirm();
    include("footer.php");
    exit;
} 

if ($action == "move" || $action == "copy") {
    if ($action == "move") {
        $query = "UPDATE {$database_up}threads SET ttrash=0,ordertrash=0 WHERE tid='$filename'";
        $result = bmbdb_query($query);
    } 
    // Lastest Reply == START
    if ($topic_type >= 3) $pincancel = "pincount=pincount+1,";
    if ($topic_islock != 0 && $topic_islock != 1) $addalimit = "digestcount=digestcount+1,";

    $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
    $cxresult = bmbdb_query($cxquery);
    $cxline = bmbdb_fetch_array($cxresult);
    $lastinfos = explode(",", $cxline['lastreply']);

    if ($beforeactionmess == "yes") {
        mtou($line['author'], "rtrash", $line['title']);
    } 
    $showinfo = "{$line['title']}({$line['author']})";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$line['forumid']}','rtrash')";
    $result = bmbdb_query($nquery); 
    
    $nquery = "UPDATE {$database_up}forumdata SET  {$pincancel} {$addalimit} trashcount=trashcount-1,topicnum = topicnum+1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
    $result = bmbdb_query($nquery); 
    // Lastest Reply == END
    refresh_forumcach();
    jump_page("forums.php?forumid=$forumid", "$gl[2]",
        "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
} 

function print_confirm()
{
    global $action, $forumid, $log_hash, $filename, $choose_reason, $gl;
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
<form name='reasons' onsubmit=\"return validate(this)\" action=\"misc.php?p=rtrash&action=move&forumid=$forumid&newforumid=trash&filename=$filename&step=2\" method='post'>$gl[234]<br />
<br />
$gl[235]<br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><input type='submit' value='$gl[173]' /><br /><br />
<input type='hidden' name='verify' value='$log_hash' /></form>";


    msg_box($title, $content);
} 
function mtou($ruser, $action, $topic)
{
    global $userid, $filename, $username, $actionreason, $gl, $database_up, $timestamp, $bbs_title, $short_msg_max;
    $actionshow = "$gl[490]";
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
