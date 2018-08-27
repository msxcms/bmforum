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
	error_page("$gl[230]", "MA Program", $gl[53], $content);

} 
// ---------check-----------
$check_user = 0;

$aquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$line = bmbdb_fetch_array($xresult);
$forumid = $line[forumid];
$topic_name = $line['title'];
if (!$line['tid']) exit;

get_forum_info("");
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########

if ($usertype[21] == "1") $check_user = 1;
if ($usertype[22] == "1") $check_user = 1;
if ($bold_true != "1" && ($action == add || $action == cancel)) $check_user = 0;

if ($verify != $log_hash && $step == 2) $check_user = 0;

if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 

if (empty($step)) {
    include("header.php");
    navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
    print_confirm();
    include("footer.php");
    exit;
} 

if ($step == "2") {
    list($moveinfo, $isjztitle) = explode("|", $line['addinfo']);

    if ($action == "cancel") {
        if ($moveinfo != "") {
            $mmoveinfo = safe_convert("$moveinfo|0|");
        } else {
            $mmoveinfo = "";
        } 
    } elseif ($action == "add") {
    	if ($fontsize > 18) $fontsize = 18;
    	if ($fontsize < 9) $fontsize = 9;
    	
        $mmoveinfo = safe_convert("$moveinfo|1,$colorcode,$jiacu,$shanchu,$xiahuau,$xietii,$bgcolorcode,$fontsize|");
    } 

    $query = "UPDATE {$database_up}threads SET addinfo='$mmoveinfo' WHERE tid='$filename'";
    $result = bmbdb_query($query);
    
    // log this action
    if ($beforeactionmess == "yes") {
        mtou($line['author'], $action, $line['title']);
    } 

    $showinfo = "{$line['title']}({$line['author']})";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$line['forumid']}','m5$action')";
    $result = bmbdb_query($nquery);
    //finish

    jump_page("forums.php?forumid=$forumid", "$gl[2]",
        "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
} 

function print_confirm()
{
    global $action, $topic_name, $log_hash, $forumid, $filename, $gl, $choose_reason;
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
<form name='reasons' onsubmit=\"return validate(this)\"  action=\"misc.php?p=manage5\" method='post'>$gl[234]<br /><br />$gl[235]<br /><br /><ul><li><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><br /></li>

";
// process action
    if ($action != "cancel") {
        $conput = "
        <li>$gl[448]<select onchange='javascript:document.reasons.colorcode.value=this.value;document.getElementById(\"preview_div\").style.color=this.value;'><option value=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option><option value=\"#000000\" style=\"background-color: Black; color: rgb(255, 255, 255);\"></option><option value=\"#FFFFFF\" style=\"background-color: #FFFFFF;\"></option><option value=\"#464646\" style=\"background-color: #464646;\"></option><option value=\"#787878\" style=\"background-color: #787878;\"></option><option value=\"#B4B4B4\" style=\"background-color: #B4B4B4;\"></option><option value=\"#DCDCDC\" style=\"background-color: #DCDCDC;\"></option><option value=\"#990030\" style=\"background-color: #990030;\"></option><option value=\"#ED1C24\" style=\"background-color: #ED1C24;\"></option><option value=\"#FF7E00\" style=\"background-color: #FF7E00;\"></option><option value=\"#FFC20E\" style=\"background-color: #FFC20E;\"></option><option value=\"#FFF200\" style=\"background-color: #FFF200;\"></option><option value=\"#A8E61D\" style=\"background-color: #A8E61D;\"></option><option value=\"#22B14C\" style=\"background-color: #22B14C;\"></option><option value=\"#00B7EF\" style=\"background-color: #00B7EF;\"></option><option value=\"#4D6DF3\" style=\"background-color: #4D6DF3;\"></option><option value=\"#2F3699\" style=\"background-color: #2F3699;\"></option><option value=\"#6F3198\" style=\"background-color: #6F3198;\"></option><option value=\"#B5A5D5\" style=\"background-color: #B5A5D5;\"></option><option value=\"#546D8E\" style=\"background-color: #546D8E;\"></option><option value=\"#709AD1\" style=\"background-color: #709AD1;\"></option><option value=\"#99D9EA\" style=\"background-color: #99D9EA;\"></option><option value=\"#9DBB61\" style=\"background-color: #9DBB61;\"></option><option value=\"#D3F9BC\" style=\"background-color: #D3F9BC;\"></option><option value=\"#FFF9BD\" style=\"background-color: #FFF9BD;\"></option><option value=\"#F5E49C\" style=\"background-color: #F5E49C;\"></option><option value=\"#E5AA7A\" style=\"background-color: #E5AA7A;\"></option><option value=\"#F5E49C\" style=\"background-color: #F5E49C;\"></option><option value=\"#FFA3B1\" style=\"background-color: #FFA3B1;\"></option><option value=\"#9C5A3C\" style=\"background-color: #9C5A3C;\"></option></optgroup></select><input type='text' value='' onchange='javascript:document.getElementById(\"preview_div\").style.color=this.value;' name='colorcode' /><br /><br /></li>
        <li>$gl[507]<select onchange='javascript:document.reasons.bgcolorcode.value=this.value;document.getElementById(\"preview_div\").style.backgroundColor=this.value;'><option value=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option><option value=\"#000000\" style=\"background-color: Black; color: rgb(255, 255, 255);\"></option><option value=\"#FFFFFF\" style=\"background-color: #FFFFFF;\"></option><option value=\"#464646\" style=\"background-color: #464646;\"></option><option value=\"#787878\" style=\"background-color: #787878;\"></option><option value=\"#B4B4B4\" style=\"background-color: #B4B4B4;\"></option><option value=\"#DCDCDC\" style=\"background-color: #DCDCDC;\"></option><option value=\"#990030\" style=\"background-color: #990030;\"></option><option value=\"#ED1C24\" style=\"background-color: #ED1C24;\"></option><option value=\"#FF7E00\" style=\"background-color: #FF7E00;\"></option><option value=\"#FFC20E\" style=\"background-color: #FFC20E;\"></option><option value=\"#FFF200\" style=\"background-color: #FFF200;\"></option><option value=\"#A8E61D\" style=\"background-color: #A8E61D;\"></option><option value=\"#22B14C\" style=\"background-color: #22B14C;\"></option><option value=\"#00B7EF\" style=\"background-color: #00B7EF;\"></option><option value=\"#4D6DF3\" style=\"background-color: #4D6DF3;\"></option><option value=\"#2F3699\" style=\"background-color: #2F3699;\"></option><option value=\"#6F3198\" style=\"background-color: #6F3198;\"></option><option value=\"#B5A5D5\" style=\"background-color: #B5A5D5;\"></option><option value=\"#546D8E\" style=\"background-color: #546D8E;\"></option><option value=\"#709AD1\" style=\"background-color: #709AD1;\"></option><option value=\"#99D9EA\" style=\"background-color: #99D9EA;\"></option><option value=\"#9DBB61\" style=\"background-color: #9DBB61;\"></option><option value=\"#D3F9BC\" style=\"background-color: #D3F9BC;\"></option><option value=\"#FFF9BD\" style=\"background-color: #FFF9BD;\"></option><option value=\"#F5E49C\" style=\"background-color: #F5E49C;\"></option><option value=\"#E5AA7A\" style=\"background-color: #E5AA7A;\"></option><option value=\"#F5E49C\" style=\"background-color: #F5E49C;\"></option><option value=\"#FFA3B1\" style=\"background-color: #FFA3B1;\"></option><option value=\"#9C5A3C\" style=\"background-color: #9C5A3C;\"></option></optgroup></select><input type='text' value='' onchange='javascript:document.getElementById(\"preview_div\").style.backgroundColor=this.value;' name='bgcolorcode' /><br /><br /></li>
        <li>$gl[508]<select onchange='javascript:document.reasons.fontsize.value=this.value;document.getElementById(\"preview_div\").style.fontSize=this.value+\"pt\";'><option value=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option><option value=\"9\">9pt</option><option value=\"12\">12pt</option><option value=\"14\">14pt</option><option value=\"16\">16pt</option><option value=\"18\">18pt</option></select><input type='text' value='' onchange='javascript:document.getElementById(\"preview_div\").style.fontSize=this.value+\"pt\";' name='fontsize' /><br /><br /></li>
        <li><input type='checkbox' value='1' name='jiacu' checked='checked' /><strong>$gl[464]</strong> <input type='checkbox' value='1' name='shanchu' /><strike>$gl[465]</strike> <input type='checkbox' value='1' name='xiahuau' /><u>$gl[466]</u> <input type='checkbox' value='1' name='xietii' /><em>$gl[467]</em><br /><br /></li>";
    } 
    $content .= "
$conput
<li><span id='preview_div' style='font-weight:bold;'>{$topic_name}</span><br/><br/></li>
<li><input type='submit' value='$gl[173]' class='btn btn-primary' /></li></ul>
    <input type='hidden' name='action' value='$action' />
    <input type='hidden' name='filename' value='$filename' />
    <input type='hidden' name='forumid' value='$forumid' />
    <input type='hidden' name='step' value='2' />
    <input type='hidden' name='verify' value='$log_hash' />
    </form>
";
    msg_box($title, $content);
} 
function mtou($ruser, $action, $topic)
{
    global $id_unique, $userid, $filename, $username, $actionreason, $database_up, $tfshow, $gl, $timestamp, $bbs_title, $short_msg_max;
    if ($action == "add") {
        $actionshow = "$gl[479]";
    } elseif ($action == "cancel") {
        $actionshow = "$gl[480]";
    } 
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
