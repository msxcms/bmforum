<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
function imageshow ($url, $imgcort = "")
{
	global $auto_width, $auto_height;
	
	$size = getimagesize($url);
	$width = $size[0];
	$height = $size[1];
	 
	if($imgcort!="") {
		if($width > $imgcort) {
			$temp = $imgcort/$width;
			$auto_width = $imgcort;
			$auto_height = $height*$temp;
		} else {
			$auto_width = $width;
			$auto_height = $height;
		}
	} else {
		$auto_width = $width;
		$auto_height = $height;
	}
	$auto_height = floor($auto_height);
	$auto_width = floor($auto_width);
   	eval(load_hook('int_topic_autowidth_height'));
}
function check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username)
{
	global $forum_upid, $forum_cid;
    $xfourmrow = $sxfourmrow;
    for($i = 0;$i < $forumscount;$i++) {
        if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
        if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
        if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    } 
    if ($login_status == 1 && $check_user == 0 && $adminlist != "") {
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
        } 
    } 
   	eval(load_hook('int_topic_checkadmin'));
    return $check_user;
}
function update_pincache($tid, $act, $cateid)
{
    @include("datafile/cache/pin_thread.php");
    
    $content = "<?php \n";
    
    $cateid = $cateid ? $cateid : "ALL";

    if ($act == 0) { // 0 stands for add a thread, 1 stands for remove.
    	if (!strstr($topthread["$cateid"], "$tid,")) {
            $content .= "\$topthread[\"$cateid\"]='$tid,{$topthread[$cateid]}';\n";
            $content .= "\$count_pint[\"$cateid\"]=". max(0, $count_pint["$cateid"]+1) .";\n";
        } else {
            $content .= "\$topthread[\"$cateid\"]='{$topthread[$cateid]}';\n";
            $content .= "\$count_pint[\"$cateid\"]=". $count_pint["$cateid"] .";\n";
        }
    } else {
    	if (strstr($topthread["$cateid"], "$tid,")) {
            $content .= "\$topthread[\"$cateid\"]='". str_replace("$tid,", "", $topthread["$cateid"]) ."';\n";
            $content .= "\$count_pint[\"$cateid\"]=". max(0, $count_pint["$cateid"]-1) .";\n";
        } else {
            $content .= "\$topthread[\"$cateid\"]='". $topthread["$cateid"] ."';\n";
            $content .= "\$count_pint[\"$cateid\"]=". $count_pint["$cateid"] .";\n";
        }
    }
    $addarray[] = "$cateid";
    
    if (@count($topthread) > 0) {
        foreach ($topthread as $key => $value) {
            if (@!in_array($key ,$addarray)) {
            	$count = count(explode(",", $value)) - 1;
                $content .= "\$topthread[\"$key\"]='{$value}';\n";
                $content .= "\$count_pint[\"$key\"]={$count};\n";
            }
        }
    }
    
    writetofile("datafile/cache/pin_thread.php", $content);
}
function check_name_valid ($addusername) 
{
	$check = 1;
    for ($asc = 1;$asc <= 47;$asc++) {
    	if ($asc == 46) continue;
        if (strrpos($addusername, chr($asc)) !== false && $check!=0) {
            $check = 0;
        } 
    } 
    for ($asc = 58;$asc <= 64;$asc++) {
        if (strrpos($addusername, chr($asc)) !== false && $check!=0) {
            $check = 0;
        } 
    } 
    for ($asc = 91;$asc <= 96;$asc++) {
		if ($asc == 95) continue;
        if (strrpos($addusername, chr($asc)) !== false && $check!=0) {
            $check = 0;
        } 
    } 
    for ($asc = 123;$asc <= 127;$asc++) {
        if (strrpos($addusername, chr($asc)) !== false && $check!=0) {
            $check = 0;
        } 
    } 
   	eval(load_hook('int_topic_checkname'));
    return $check;

}
function announce_user($ruser, $actionshow, $user, $content, $title, $topic = ""){
    global $userid, $username, $actionreason, $filename, $database_up, $tfshow, $gl, $timestamp, $bbs_title, $short_msg_max;

    $lines = bmbdb_query_fetch("SELECT userid FROM {$database_up}userlist WHERE username='$ruser' LIMIT 0,1");
    $ruserid = $lines['userid'];

	$result = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}contacts where `type`=1 and `owner`='$userid' and `contacts`='$ruserid'");

    $uisbadu = ($result['COUNT(`id`)'] > 0) ? "yes" : "";

   	eval(load_hook('int_topic_announce_user'));
    if ($uisbadu != "yes") {

	    if (!$content) $content = str_replace("'","\'", "$gl[428] <strong><a href='topic.php?filename=$filename'>$topic</a></strong><br />$gl[429] $username <u>$actionshow </u><br /><br /><strong>$gl[452]</strong>:$actionreason <br />$tfshow");
		
	    $nquery = "insert into {$database_up}primsg (belong,sendto,prtitle,prtime,prcontent,prread,prother,prtype,prkeepsnd,stid,blid) values ('$username','$ruser','$title','$timestamp','$content','0','','r','','$ruserid','$userid')";
	    $result = bmbdb_query($nquery);
	    $nquery = "UPDATE {$database_up}userlist SET newmess=newmess+1 WHERE userid='$ruserid'";
	    $result = bmbdb_query($nquery);
	}
	
}
function run_thread_list($row)
{
	global $maxpageno, $titlelink, $forumnum, $forumid, $gl, $read_perpage, $username, $timestamp, $stats, $otherimages, $bmfopt, $emotrand, $icon, $title, $multipage, $row, $forum_name, $urlauthor, $aimetoshow, $lmdtime, $urllmdauthor, $lmdauthor;
    $multipage = ''; 

    $reply = $row['replys'];
    $topic_type = @trim($row['type']);
    $topic_islock = @trim($row['islock']);

    $toplang = utf8_strlen($row['content']);
    
    if (utf8_strlen($row['author']) >= 12) $viewauthor = substr($row['author'], 0, 9) . '...';
    else $viewauthor = $row['author'];

    // -------if more than one page-----------
    if ($row['replys'] + 1 > $read_perpage) {
        if (($row['replys'] + 1) % $read_perpage == 0) $maxpageno = ($reply + 1) / $read_perpage;
        else $maxpageno = floor(($reply + 1) / $read_perpage) + 1;
        $multipage = ($bmfopt['rewrite'] ? "topic_{$row['tid']}_{page}" : "topic.php?filename={$row['tid']}&amp;page={page}");
    } 
    $row['title'] = stripslashes($row['title']);
    $titlelink = ($bmfopt['rewrite'] ? "topic_{$row['tid']}" : "topic.php?filename={$row['tid']}");
    $title = $row['title'];
    $lmd = explode(",", $row['lastreply']);
    $g = $timestamp - $lmd[2];

    $lmdauthor = $lmd[1];
    $urllmdauthor = urlencode($lmd[1]);
    
    $lmdtime_tmp = getfulldate($lmd[2]);
    $cmdtime_tmp = get_date($row['time']);
    if ($time_2) {
        $timetmp_a = $timestamp - $lmd[2];
        $lmdtime = get_add_date($timetmp_a);
        if ($lmdtime == "getfulldate") {
            $lmdtime = $lmdtime_tmp;
        } 
        $timedmp_b = $timestamp - $row[time];
        $aimetoshow = get_add_date($timedmp_b);
        if ($aimetoshow == "getfulldate") {
            $aimetoshow = $cmdtime_tmp;
        } 
    } else {
        $lmdtime = $lmdtime_tmp;
        $aimetoshow = $cmdtime_tmp;
    } 
    
	$forum_name = $forumnum["$row[forumid]"];
   	eval(load_hook('int_topic_list_thread'));
}

