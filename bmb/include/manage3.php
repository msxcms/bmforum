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
// Load Thread
$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$line = bmbdb_fetch_array($result);
$forumid = $line['forumid'];
$topic_type = $line['type'];
$topic_islock = $line['islock'];
if (!$line['tid']) exit;
// END - Get Forum Info
get_forum_info("");
// Forbid
tbuser();
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########
if ($usertype[21] == "1") $check_user = 1;
if ($usertype[22] == "1") $check_user = 1;
if ($usertype[112] != "1" && $action == "move") $check_user = 0;
if ($usertype[113] != "1" && $action == "split") $check_user = 0;

if ($verify != $log_hash && $step == 2) $check_user = 0;

if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 

if (empty($step)) {
    include("header.php");
    navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
    if ($action == "split") print_split_confirm();
      else print_confirm();
    include("footer.php");
    exit;
} 
$pincancel = $replyer = $addalimit = "";

$forumid = $line['forumid'];
$ruser = $line['author'];
$topicname = $line['title'];
$checktrash = $line['ttrash'];

if ($action == "split") {
	$count = count($split_choose);
	$last_number = $count - 1;
	
	$info_first = explode("|", $split_choose[0]);
	$info_last = explode("|", $split_choose[$last_number]);
	
	$first_post = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}posts WHERE id='$info_first[0]' LIMIT 0,1"));
	$last_post = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}posts WHERE id='$info_last[0]' LIMIT 0,1"));
	$split_title = stripslashes($split_title);
	$articletitle_reply = strreply("RE:", "RE:" . $split_title);
	
	$lm = ($last_post['articletitle'] ? $last_post['articletitle'] : $articletitle_reply) . "," . $last_post['username'] . "," . $last_post['timestamp'];
	
	// make SQL
	for($i = 0; $i < $count; $i++) {
		if ($i != 0) $addcoo = ",";
		$info = explode("|", $split_choose[$i]);
		$tid_sql .= $addcoo.$info[0];
		
		if (!strstr($replyer, $info[1] ."|")) $replyer .= "$info[1]|";
	}
	
    $result = bmbdb_query("insert into {$database_up}threads (id,tid,toptype,ttrash,ordertrash,lastreply,topic,forumid,hits,replys,changetime,itsre,type,islock,title,newdesc,author,content,time,ip,face,options,other1,other2,other3,other4,other5,statdata,addinfo,alldata,ttagname,replyer) values ('$info_first[0]','$info_first[0]',0,'0','0','$lm','','$forumid','0','$last_number','$first_post[timestamp]','0','0','0','$split_title','','$first_post[username]','$first_post[articlecontent]','$first_post[changtime]','$first_post[ip]','$first_post[usericon]','$first_post[options]','$first_post[other1]','$first_post[other2]','$first_post[other3]','$first_post[other4]','$first_post[other5]','','',0,'','$replyer')");
    $result = bmbdb_query("UPDATE {$database_up}posts SET tid='$info_first[0]' WHERE id in($tid_sql)");

    // Modify old topic
    $aaquery = "SELECT articletitle,username,usrid,timestamp FROM {$database_up}posts WHERE tid='{$line['tid']}' ORDER BY `changtime` DESC LIMIT 0,1";
    $aaresult = bmbdb_query($aaquery);
    
    $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $topicname));
    
    while (false !== ($aaline = bmbdb_fetch_array($aaresult))) {
        if (empty($addinfos)) $addinfos = ($aaline['articletitle'] ? $aaline['articletitle'] : $articletitle_reply) . "," . $aaline['username'] . "," . $aaline['timestamp'];
        if (empty($truechangtime)) $truechangtime = $aaline['timestamp'];
    } 

	if ($noheldtop != 1) $changadd = ",changetime=$truechangtime";

    $nquery = "UPDATE {$database_up}threads SET  replys=replys-$count{$changadd},lastreply='$addinfos' WHERE tid='{$line['tid']}'";
    $result = bmbdb_query($nquery);

// log info
    $showinfo = "{$topicname}({$line['author']})";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','$forumid','split')";
    $result = bmbdb_query($nquery); 
    

    
    // Lastest Reply == START
    $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
    $cxresult = bmbdb_query($cxquery);
    $cxline = bmbdb_fetch_array($cxresult);
    $lastinfos = explode(",", $cxline['lastreply']);
    
    $nquery = "UPDATE {$database_up}forumdata SET topicnum = topicnum+1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
    $result = bmbdb_query($nquery); 
    // Lastest Reply == END
    refresh_forumcach();

	jump_page("forums.php?forumid=$forumid", "$gl[2]", "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?filename=$info_first[0]'>$gl[506]</a> | <a href='topic.php?filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
}elseif ($action == "move" || $action == "copy") {
    
    if ($checktrash != 1) {
        if ($newforumid == "trash") {
            $query = "UPDATE {$database_up}threads SET ttrash='1',ordertrash='1' WHERE tid='$filename'";
            $result = bmbdb_query($query);
        } 

        if ($beforeactionmess == "yes") {
            mtou($ruser, "recycle", $topicname);
        } 

        $showinfo = "{$line['title']}({$line['author']})";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$line['forumid']}','recycle')";
        $result = bmbdb_query($nquery); 
        
        if ($topic_type >= 3) $pincancel = "pincount=pincount-1,";
    	if ($topic_islock != 0 && $topic_islock != 1) $addalimit = "digestcount=digestcount-1,";
        // Lastest Reply == START
     
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);
        $lastinfos = explode(",", $cxline['lastreply']);
        
        $nquery = "UPDATE {$database_up}forumdata SET {$pincancel} {$addalimit} trashcount=trashcount+1,topicnum = topicnum-1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
        refresh_forumcach();
    }
	jump_page("forums.php?forumid=$forumid", "$gl[2]", "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
} 

exit;


function print_split_confirm()
{ // split form
    global $action, $log_hash, $forumid, $filename, $gl, $choose_reason, $database_up;
    $title = "$gl[173]";
    //reason
    $chooser_t = explode("\n", $choose_reason);
    $cou = count($chooser_t);
    $chooser_c = "<select name='reasonselection' onchange='document.reasons.actionreason.value=document.reasons.reasonselection.options[document.reasons.reasonselection.selectedIndex].value;'>";
    for($i = 0;$i < $cou;$i++) {
        $chooser_c .= "<option value='{$chooser_t[$i]}'>{$chooser_t[$i]}</option>";
    } 
    $chooser_c .= "</select>";
    // list replies list
    
    $i = 0;
    $result = bmbdb_query("SELECT articletitle,articlecontent,usrid,username,id FROM {$database_up}posts WHERE tid='$filename' ORDER BY 'changtime' ASC");
    while (false !== ($line = bmbdb_fetch_array($result))) {
    	$i++;
    	if ($i == 1) continue;
    	if ($line['articletitle']) $line['articletitle'] = "({$line['articletitle']})";
    	$Split_Post_List .= "<input type='checkbox' value='{$line['id']}|{$line['usrid']}' name='split_choose[]' id='split_radio[$i]' /><label for='split_radio[$i]' onmouseover='javascript:checkhide($i);'>$gl[503]:$i - {$line['username']}{$line['articletitle']}</label><div class='quote_dialog' onmouseout='javascript:checkshow($i);' id='preview_$i' style=\"display: none;\">{$line['articlecontent']}</div><br/>";
    }
    
    // pre-output
    $content=<<<EOT
<script type="text/javascript">
//<![CDATA[ 
function checkhide(num){
	div_object = document.getElementById("preview_"+num);
	for(i=2;i<$i+1;i++){
		if(i!=num) document.getElementById("preview_"+i).style.display="none";
	}
	div_object.style.display="";
}
function checkshow(num){
	div_object = document.getElementById("preview_"+num);
	div_object.style.display="none";
}
function validate(theform) {
if (theform.actionreason.value=="" || theform.actionreason.value=="") {
alert("$gl[455]");
return false; } }
function change(theoption) {
this.reasons.actionreason.value=theoption;
}
//]]>>
</script>
<form name='reasons' onsubmit="return validate(this)" action="misc.php?p=manage3&amp;action=split&amp;filename=$filename&amp;step=2" method='post'>$gl[234]<br />
<br /><input type='hidden' name='verify' value='$log_hash' />
$gl[235]<br /><br />
<div class="quote_dialog">$gl[501]</div>
<div class="quote_dialog">$gl[504] <br/>$Split_Post_List <br/> $gl[502] <input type='text' size='40' value='' name='split_title'></div>

<br />$gl[452]
$chooser_c<input type='text' name='actionreason' /><br /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
</form>

EOT;

    $content .= "";
    msg_box($title, $content);
} 
function print_confirm()
{
    global $action, $forumid, $log_hash, $filename, $gl, $choose_reason;
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
<form name='reasons' onsubmit=\"return validate(this)\" action=\"misc.php?p=manage3&amp;action=move&amp;forumid=$forumid&amp;newforumid=trash&amp;filename=$filename&amp;step=2\" method='post'>$gl[234]<br />
<br /><input type='hidden' name='verify' value='$log_hash' />
$gl[235]<br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
</form>";

    $content .= "";
    msg_box($title, $content);
} 
function mtou($ruser, $action, $topic)
{
    global $id_unique, $userid, $filename, $username, $actionreason, $gl, $database_up, $timestamp, $bbs_title, $short_msg_max;
    $actionshow = "$gl[453]";
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}
