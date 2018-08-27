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
include("include/common.inc.php");

if (empty($filename) || (($action == "move" || $action == "copy") && $step == 2 && !is_numeric($newforumid)) || empty($action)) {
    $content = "$gl[233]<br /><br />$gl[229]";
	error_page("$gl[230]", "Management", $gl[53], $content);
} 
// ---------check-----------
$check_user = 0;
$adminlist = $pincancel = $addalimit = "";
$aquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$nline = bmbdb_fetch_array($xresult);
$forumid = $nline[forumid];
$oldtags = $nline['ttagname'];
$topic_islock = $nline['islock'];
$topic_type = $nline['type'];
if (!$nline['tid']) exit;

tbuser();
get_forum_info("");

$xfourmrow = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $newforumid) $newforumname = $xfourmrow[$i]['bbsname'];
} 
if ($login_status == 1 && $nline['replys'] == 0 && $nline['author'] == $username && $del_self_topic == 1 && $action == "del") $check_user = 1;
if ($login_status == 1 && $nline['replys'] > 0 && $nline['author'] == $username && $usertype[106] == 1 && $action == "del") $check_user = 1;
if ($login_status == 1 && $check_user == 0 && $adminlist != "") {
    $arrayal = explode("|", $adminlist);
    $admincount = count($arrayal);
    for ($i = 0; $i < $admincount; $i++) {
        if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
    } 
} 
if ($usertype[22] == "1" || $usertype[21] == "1") $check_user = 1;
if ($del_true != "1" && $action == del && $nline['author'] != $username) $check_user = 0;
if ($lock_true != "1" && ($action == lock || $action == unlock)) $check_user = 0;
if ($move_true != "1" && $action == move) $check_user = 0;
if ($copy_true != "1" && $action == copy) $check_user = 0;
if ($sej_true != "1" && ($action == jihua || $action == unjihua)) $check_user = 0;

$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$line = bmbdb_fetch_array($result);

if ($line['ttrash'] == 1) {
    $checktrash = "yes";
} 
if ($del_rec != "1" && $checktrash == "yes" && $action == del) $check_user = 0;

if ($checktrash == "yes" && $action != del) {
    // ---to check if the user have got the permission to post-------
    $content = "$gl[233]<br /><br />$gl[310]";
	error_page("$gl[230]", "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 

if ($verify != $log_hash && $step == 2) $check_user = 0;


if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page("$gl[230]", "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 

if (empty($step)) {
    include("header.php");
    navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
    print_confirm();
    include("footer.php");
    exit;
} 

if ($action == "move") {
    if ($action == "move") {
        // ------Change the posts' forumid-------
        $query = "UPDATE {$database_up}posts SET forumid='$newforumid' WHERE tid='$filename' ";
        $result = bmbdb_query($query);
    } 
    $setquery = "";
    list($old_moveinfo, $old_isjztitle) = explode("|", $line['addinfo']);
    if ($moveinfo == yes) {
        $stime = get_date($timestamp) . " " . get_time($timestamp);
        if ($action == "move") $showmc = "$gl[311]";
        $mmoveinfo = "{$username} $gl[313] {$stime} {$showmc}$gl[314] {$forum_name}";
        $setquery = ",addinfo='$mmoveinfo|{$old_isjztitle}'";
    } else {
    	$setquery = ",addinfo='|{$old_isjztitle}'";
    }
    if ($action == "move") {
    	$tfshow="<br /> $gl[460] ". str_replace("'", "`", $newforumname);
    	
    	if ($line['toptype'] == 1) $setquery.=",toptype=0,type=type-3";
    	
        $query = "UPDATE {$database_up}threads SET forumid='$newforumid' $setquery  WHERE tid='$filename'";
        $result = bmbdb_query($query);
        
        $showinfo = "{$line['title']}({$line['author']}) <br /> $gl[489] ". str_replace("'", "`", $forum_name);
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$newforumid}','$action')";
        $result = bmbdb_query($nquery);
    } 

    $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$newforumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
    $cxresult = bmbdb_query($cxquery);
    $cxline = bmbdb_fetch_array($cxresult);
    $lastinfos = explode(",", $cxline['lastreply']);

    $nquery = "UPDATE {$database_up}forumdata SET topicnum = topicnum+1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='$newforumid'";
    $result = bmbdb_query($nquery);

    $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
    $cxresult = bmbdb_query($cxquery);
    $cxline = bmbdb_fetch_array($cxresult);
    $lastinfos = explode(",", $cxline['lastreply']);

    $nquery = "UPDATE {$database_up}forumdata SET topicnum = topicnum-1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='$forumid'";
    $result = bmbdb_query($nquery); 
    // ----11:11 2003-11-9 done
    if ($beforeactionmess == "yes") {
        mtou($line['author'], $action, $line['title']);
    } 
    
    $showinfo = "{$line['title']}({$line['author']}) $tfshow";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$line['forumid']}','$action')";
    $result = bmbdb_query($nquery);
    refresh_forumcach();
    jump_page("forums.php?forumid=$forumid", "$gl[2]",
        "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> |  <a href='forums.php?forumid=$newforumid'>$gl[500]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
} 

if ($line['author'] == $username) $delusernum = "yes";
if ($action == "del" && $delusernum == "yes") {
    bmfwwz($line['author'], "-$delrmb", "-$deljifen", "-1", "1", ""); //--删贴后扣积分，发贴数--
} 
// 记入被删除主题数结束
if ($action == "lock") {
	// === Add and cancel lock
	if ($topic_islock != 1 && $topic_islock != 3) {
        $query = "UPDATE {$database_up}threads SET islock=islock+1 WHERE tid='$filename'";
        $result = bmbdb_query($query);
    }
} elseif ($action == "unlock") {
	if ($topic_islock == 1 || $topic_islock == 3) {
        $query = "UPDATE {$database_up}threads SET islock=islock-1 WHERE tid='$filename'";
        $result = bmbdb_query($query);
    }
} elseif ($action == "jihua") {
	// === Add and cancel digest
	if ($topic_islock == 0 || $topic_islock == 1) {
        $query = "UPDATE {$database_up}threads SET islock=islock+2 WHERE tid='$filename' LIMIT 1";
        $result = bmbdb_query($query);
        
        $query = "UPDATE {$database_up}userlist SET digestmount=digestmount+1 WHERE username='{$line['author']}' LIMIT 1";
        $result = bmbdb_query($query);
        
        $nquery = "UPDATE {$database_up}forumdata SET digestcount=digestcount+1 WHERE id='{$line['forumid']}'";
        $result = bmbdb_query($nquery);
	}
} elseif ($action == "unjihua") {
	if ($topic_islock != 0 && $topic_islock != 1) {
        $query = "UPDATE {$database_up}threads SET islock=islock-2 WHERE tid='$filename' AND islock=2 OR islock=3";
        $result = bmbdb_query($query);
        
        $query = "UPDATE {$database_up}userlist SET digestmount=digestmount-1 WHERE username='{$line['author']}' LIMIT 1";
        $result = bmbdb_query($query);
        
        $nquery = "UPDATE {$database_up}forumdata SET digestcount=digestcount-1 WHERE id='{$line['forumid']}'";
        $result = bmbdb_query($nquery);
	}
} elseif ($action == "del") {
    $aquery = "SELECT * FROM {$database_up}posts WHERE tid='$filename' ";
    $aresult = bmbdb_query($aquery);
    while (false !== ($xline = bmbdb_fetch_array($aresult))) {
        $uploadfilename = $xline['other3'];
        if (strpos($uploadfilename, "×")) {
            $attachshow = explode("×", $uploadfilename);
            $countas = count($attachshow)-1;
        } else {
            $attachshow[0] = $uploadfilename;
            $countas = 1;
        } 
        $uploadfileshow = "";
        for ($ias = 0;$ias < $countas;$ias++) {
            $showdes = explode("◎", $attachshow[$ias]);
            @unlink("upload/{$showdes[0]}");
        } 
    } 

    bmbdb_query("DELETE FROM {$database_up}posts WHERE tid='$filename' ");
    bmbdb_query("DELETE FROM {$database_up}polls WHERE id='$filename' ");
    bmbdb_query("DELETE FROM {$database_up}threads WHERE tid='$filename'");
    bmbdb_query("DELETE FROM {$database_up}beg WHERE tid='$filename'");

    if ($oldtags) {
        $oldtags_sql = implode("' OR tagname='", explode(" ", $oldtags));
        $tquery = "UPDATE {$database_up}tags SET threads=threads-1,filename=replace(filename,',$filename','') WHERE tagname='{$oldtags_sql}'";
        $tresult = bmbdb_query($tquery);
    }
    if ($checktrash != "yes") {
    	if ($topic_type >= 3) $pincancel = "pincount=pincount-1,";
    	if ($topic_islock != 0 && $topic_islock != 1) $addalimit = "digestcount=digestcount-1,";
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$line['forumid']}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET {$addalimit} $pincancel topicnum = topicnum-1,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$line['forumid']}'";
        $result = bmbdb_query($nquery);
    } else bmbdb_query("UPDATE {$database_up}forumdata SET trashcount=trashcount-1 WHERE id='{$line['forumid']}'");
    // Lastest Reply == END
} 

if ($beforeactionmess == "yes") {
    mtou($line['author'], $action, $line['title']);
} 

$showinfo = "{$line['title']}({$line['author']})";
$nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$line['forumid']}','$action')";
$result = bmbdb_query($nquery);

refresh_forumcach();
if ($action != "del") $jumptothread = " | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a>";
jump_page("forums.php?forumid=$forumid", "$gl[2]",
    "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a>{$jumptothread} | <a href='index.php'>$gl[5]</a>", 3);


function print_confirm()
{
    global $action, $database_up, $log_hash, $forumid, $gl, $choose_reason, $author, $username, $filename;
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
</script><form name='reasons' onsubmit=\"return validate(this)\"  action=\"misc.php?p=manage\" method=\"post\">$gl[234]<br />
<br />
$gl[235]<br />";
    if ($action == "move" || $action == "copy") {
        $content .= "<select name='newforumid'>";
        $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
        $nresult = bmbdb_query($nquery);
        while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
            if ($fourmrow['type'] != "category" && $fourmrow['id'] != $forumid) $content .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
        } 
        $content .= "</select><br /><input type='checkbox' name='moveinfo' value='yes' />$gl[315]";
    } 
    if ($author != $username && $action == "del") {
        $content .= "<br /><input type='checkbox' name='delusernum' value='yes' checked='checked' />$gl[450]";
    } 
    $content .= "<br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
<input type='hidden' name='action' value='$action' />
<input type='hidden' name='filename' value='$filename' />
<input type='hidden' name='forumid' value='$forumid' />
<input type='hidden' name='step' value='2' />
<input type='hidden' name='verify' value='$log_hash' />
</form>";

    msg_box($title, $content);
} 

function mtou($ruser, $action, $topic)
{
    global $userid, $username, $actionreason, $filename, $database_up, $tfshow, $gl, $timestamp, $bbs_title, $short_msg_max;
    if ($action == "move") {
        $actionshow = "$gl[311]";
    } elseif ($action == "copy") {
        $actionshow = "$gl[312]";
    } elseif ($action == "del") {
        $actionshow = "$gl[430]";
    } elseif ($action == "lock") {
        $actionshow = "$gl[431]";
    } elseif ($action == "unlock") {
        $actionshow = "$gl[432]";
    } elseif ($action == "jihua") {
        $actionshow = "$gl[433]";
    } elseif ($action == "unjihua") {
        $actionshow = "$gl[434]";
    } 
    
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
