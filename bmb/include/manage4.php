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
include_once("include/template.php");

if (empty($action) || (($action == "move" || $action == "copy") && $step == 2 && !is_numeric($destforum))) {
    $content = "$gl[233]<br /><br />$gl[229]";
	error_page("$gl[230]", "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 
// ---------check-----------
$filename = $filenamebym[0];
$check_user = 0;
$adminlist = $tid_sql = "";
$aquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$nline = bmbdb_fetch_array($xresult);
$forumid = $nline["forumid"];
if (!$nline['tid']) exit;
$checktrash = $nline['ttrash'];
// *** Check more threads
if ($usertype[22] != "1" && $usertype[21] != "1") {
	$count_bym = count($filenamebym);
	for ($i = 0; $i < $count_bym; $i++){
		if ($i != 0) $addcoo = ",";
		$tid_sql .= $addcoo."'".$filenamebym[$i]."'";
	}
	$result = bmbdb_query("SELECT forumid FROM {$database_up}threads WHERE tid in($tid_sql)");
	while (false !== ($check_line = bmbdb_fetch_array($result))) {
		if ($forumid != $check_line['forumid']) die("Error Permission");
	}
}
// **********************

tbuser();
get_forum_info("");

$xfourmrow = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i]['id'] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i]['id'] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i]['id'] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i]['id'] == $destforum) $newforumname = $xfourmrow[$i]['bbsname'];
} 

if ($login_status == 1 && $nline['author'] == $username && $del_self_topic == 1 && $action == "del") $check_user = 1;

if ($login_status == 1 && $check_user == 0 && $adminlist != "") {
    $arrayal = explode("|", $adminlist);
    $admincount = count($arrayal);
    for ($i = 0; $i < $admincount; $i++) {
        if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
    } 
} 

if ($usertype[22] == "1" || $usertype[21] == "1") $check_user = 1;
if ($del_true != "1" && $action == 'del' && $author != $username) $check_user = 0;
if ($move_true != "1" && $action == 'move') $check_user = 0;
if ($copy_true != "1" && $action == 'copy') $check_user = 0;
if ($can_rec != "1" && $action == 'resume') $check_user = 0;
if ($lock_true != "1" && ($action == 'lock' || $action == 'unlock')) $check_user = 0;
if ($del_rec != "1" && $checktrash == "yes" && $action == 'del') $check_user = 0;
if ($usertype[112] != "1" && $action == "recycle") $check_user = 0;

if ($verify != $log_hash && $step == 2) $check_user = 0;

// ----Check Admin----------
if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page("$gl[230]", "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 
if ($action) {
    $chooser_t = explode("\n", $choose_reason);
    $cou = count($chooser_t);
    $chooser_c = "<select name='reasonselection' onchange='document.reasons.actionreason.value=document.reasons.reasonselection.options[document.reasons.reasonselection.selectedIndex].value;'>";
    for($i = 0;$i < $cou;$i++) {
        $chooser_c .= "<option value='{$chooser_t[$i]}'>{$chooser_t[$i]}</option>";
    } 
    $chooser_c .= "</select>";
    $selectreason = "<script type=\"text/javascript\">
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
$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><br />

";
}
if ($action == "del") {
    if (!$step) {

        $count = count($filenamebym);

        $content_title = $gl[349] . $gl[348] . $gl[350] . "<form onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=del&step=2' method='post'>";
        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);

        
        $title = $gl[173];
        
        $content .= "<br />$selectreason ";
        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");
    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        bmbdb_query("DELETE FROM {$database_up}threads WHERE tid in($fnsql)");
        bmbdb_query("DELETE FROM {$database_up}posts WHERE tid in($fnsql)");
        bmbdb_query("DELETE FROM {$database_up}polls WHERE id in($fnsql)");
        bmbdb_query("DELETE FROM {$database_up}beg WHERE tid in($fnsql)");

        
        // === Log File
        $showinfo = "{$gl[482]}: $count {$gl[485]}";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET  topicnum = topicnum-$count,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
        refresh_forumcach();
        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "recycle") {
    if ($checktrash) die("Error: Recycled cannot recycle again");
    if (!$step) {
		
        $count = count($filenamebym);

        $content_title = $gl[349] . $gl[453] . $gl[350] . "<form  onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=recycle&step=2' method='post'>";
        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
        
        
        
        
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);
        
        $title = $gl[173];
        
        $content .= "<br />$selectreason";

        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");
        
    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "UPDATE {$database_up}threads SET ttrash='1',ordertrash='1' WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        // === Log File
        $showinfo = "{$gl[491]}: $count {$gl[485]}";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET trashcount=trashcount+$count,topicnum = topicnum-$count,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
        refresh_forumcach();
        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "resume") {
    if (!$checktrash) die("Error: Resuming only for recycled threads");
    if (!$step) {

        $count = count($filenamebym);
        $content_title = $gl[349] . $gl[490] . "? <form  onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=resume&step=2' method='post'>";

        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
        
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);

        
        $title = $gl[173];
		$content .= "<br />$selectreason";
        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");

    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "UPDATE {$database_up}threads SET ttrash=0,ordertrash=0 WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        // === Log File
        $showinfo = "{$gl[492]}: $count {$gl[485]}";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET trashcount=trashcount-$count,topicnum = topicnum+$count,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
        refresh_forumcach();
        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "lock" || $action == "top" || $action == "jinhua") {
    if (!$step) {

        $count = count($filenamebym);
        
        if ($action == "top") {
        	$act = $gl[471];
    	} elseif ($action == "jinhua") {
        	$act = $gl[433];
    	} else {
        	$act = $gl[431];
        }
        
        $content_title = $gl[349] . $act . $gl[350] . "<form  onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=$action&step=2' method='post'>";

        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);

        $title = $gl[173];
        
        $content .= "<br />$selectreason";
        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");
        
    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        if ($action == "top") {
			$showinfo = "{$gl[471]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET type=type+3,toptype=1 WHERE tid in ($fnsql) and toptype<2 and type!=3";

        	bmbdb_query("UPDATE {$database_up}forumdata SET pincount=pincount+$count WHERE id='$forumid'");
    	} elseif ($action == "jinhua") {
			$showinfo = "{$gl[433]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET islock=islock+2 WHERE `tid` in ($fnsql) and (`islock`=0 OR `islock` = 1)";
			
	        $result = bmbdb_query("SELECT * FROM {$database_up}threads WHERE tid in($fnsql)");
	        $topicsn = bmbdb_num_rows($result);
	        for ($x = 0;$x < $topicsn;$x++) {
	            $row = bmbdb_fetch_array($result);
	            bmbdb_query("UPDATE {$database_up}userlist SET digestmount=digestmount+1 WHERE userid='{$row['authorid']}' LIMIT 1");
	        } 

        	bmbdb_query("UPDATE {$database_up}forumdata SET digestcount=digestcount+$count WHERE id='$forumid'");
			
        } else {
        	$showinfo = "{$gl[493]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET islock=islock+1 WHERE tid in ($fnsql) and islock!= 1 and islock!=3 ";
        }
        $result = bmbdb_query($query);
        // === Log File
        
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);

        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "unlock" || $action == "untop" || $action == "unjinhua") {
    if (!$step) {

        $count = count($filenamebym);
        
        
        if ($action == "untop") {
        	$act = $gl[472];
    	} elseif ($action == "unjinhua") {
        	$act = $gl[434];
    	} else {
        	$act = $gl[432];
        }

        
        $content_title = $gl[349] . $act . $gl[350] . "<form  onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=$action&step=2' method='post'>";

        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);
        
        $title = $gl[173];
        
        $content .= "<br />$selectreason";

        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");
    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        
        if ($action == "untop") {
        	$showinfo = "{$gl[472]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET type=type-3,toptype=0 WHERE tid in ($fnsql) AND toptype<2 AND type>=3";

			bmbdb_query("UPDATE {$database_up}forumdata SET pincount=pincount-$count WHERE id='$forumid'");
    	} elseif ($action == "unjinhua") {
			$showinfo = "{$gl[434]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET islock=islock-2 WHERE `tid` in ($fnsql) and `islock`!=0 and `islock` != 1";
			
	        $result = bmbdb_query("SELECT * FROM {$database_up}threads WHERE tid in($fnsql)");
	        $topicsn = bmbdb_num_rows($result);
	        for ($x = 0;$x < $topicsn;$x++) {
	            $row = bmbdb_fetch_array($result);
	            bmbdb_query("UPDATE {$database_up}userlist SET digestmount=digestmount-1 WHERE userid='{$row['authorid']}' LIMIT 1");
	        } 

        	bmbdb_query("UPDATE {$database_up}forumdata SET digestcount=digestcount-$count WHERE id='$forumid'");

        } else {
        	$showinfo = "{$gl[494]}: $count {$gl[485]}";
        	$query = "UPDATE {$database_up}threads SET islock=islock-1 WHERE tid in($fnsql) and (islock=1 OR islock=3)";
        }
        
        $result = bmbdb_query($query);
        // === Log File
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);

        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "move") {
    if (!$step) {
        $count = count($filenamebym);
        $content_title = $gl[349] . $gl[311] . $gl[350] . "<form onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=move&step=2' method='post'>";

        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            article_line($showto[$x]);
        } 
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);
        
        $title = $gl[173];
        
        $content .= "<br />$gl[460]<select name='destforum'>";
        $nquery = "SELECT * FROM {$database_up}forumdata WHERE type!='category' ORDER BY `showorder` ASC";
        $nresult = bmbdb_query($nquery);
        while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
            $content .= "<option value={$fourmrow['id']}>{$fourmrow['bbsname']}</option>";
        } 
        $content .= "</select><br />$selectreason";

        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");

    } else {
        $count = count($filenamebym);
        for($i = 0;$i < $count;$i++) {
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            if ($row['toptype'] == 1) {
            	bmbdb_query("UPDATE {$database_up}threads SET toptype=0,type=type-3 WHERE `tid`='$row[tid]'");
            }
		}
        
        
        $query = "UPDATE {$database_up}threads SET forumid='$destforum' WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $query = "UPDATE {$database_up}posts SET forumid='$destforum' WHERE tid in($fnsql)";
        $result = bmbdb_query($query); 
        
        // === Log File
        $showinfo = "{$gl[483]}: $count {$gl[485]}<br /> $gl[489] ". str_replace("'", "`", $forum_name);
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$destforum}','$action')";
        $result = bmbdb_query($nquery);
        
        $showinfo = "{$gl[483]}: $count {$gl[485]}<br /> $gl[460] ". str_replace("'", "`", $newforumname);
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET  topicnum = topicnum-$count,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END   dest Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$destforum}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET  topicnum = topicnum+$count,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$destforum}'";
        $result = bmbdb_query($nquery); 
        // dest Lastest Reply == END
        refresh_forumcach();
        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='forums.php?forumid=$destforum'>$gl[500]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} elseif ($action == "hebing") {
    if (!$step) {

        $count = count($filenamebym);
        $content_title = $gl[349] . $gl[311] . $gl[350] . "<form onsubmit=\"return validate(this)\" name='reasons' action='misc.php?p=manage4&action=hebing&step=2' method='post'>";

        for($i = 0;$i < $count;$i++) {
            $content .= "<input type='hidden' name='filenamebym[]' value='$filenamebym[$i]' />";
            $fnsql .= $fnsql ? ",'$filenamebym[$i]'" : "'$filenamebym[$i]'";
        } 
        $query = "SELECT * FROM {$database_up}threads WHERE tid in($fnsql)";
        $result = bmbdb_query($query);
        $topicsn = bmbdb_num_rows($result);
        $countt = 0;
        $replyer = "";
        for ($x = 0;$x < $topicsn;$x++) {
            $row = bmbdb_fetch_array($result);
            $showto[$x] = $row;
            if ($mainid == "") $mainid = $row['tid'];
            $replyer .= $replyer . $row['replyer'];
            article_line($showto[$x]);
            $countt++;
        } 
		$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("manage4_form", $temfilename, $styleidcode, $lang_zone);

        $title = $gl[173];
        
        $totalre = $totalre + $countt-1;
        $content .= "$gl[343] $countt $gl[344] <input type='hidden' name='totalre' value='$totalre' /><input type='hidden' name='mainid' value='$mainid' /><input type='text' name='newtitle' /><br /><br />$selectreason";

        require("header.php");
        navi_bar($gl[230], "<a href='forums.php?forumid=$forumid'>$forum_name</a>");
        require($template_name);
        require("footer.php");
    } else {
        $count = count($filenamebym);
        for($i = $s_true = $i_true = 0;$i < $count;$i++) {
            if ($mainid != $filenamebym[$i]) {
                if ($filenamebym[$i]) {
                    if ($i_true == 0) $xfnsql .= "'$filenamebym[$i]'";
                        else $xfnsql .= ",'$filenamebym[$i]'";
                    $i_true++;
               }
            } 
            if ($filenamebym[$i]) {
                if ($s_true == 0) $fnsql .= "'$filenamebym[$i]'";
                    else $fnsql .= " ,'$filenamebym[$i]'";
                    $s_true++;
            }
        } 
        $countxx = $count-1;
        bmbdb_query("DELETE FROM {$database_up}threads WHERE tid in($xfnsql)");
        bmbdb_query("DELETE FROM {$database_up}polls WHERE id in($xfnsql)");
        bmbdb_query("DELETE FROM {$database_up}beg WHERE tid in($xfnsql)");

        $query = "UPDATE {$database_up}posts SET tid='$mainid' WHERE tid in($fnsql)";
        $result = bmbdb_query($query); 


        $aaquery = "SELECT articletitle,username,usrid,timestamp FROM {$database_up}posts WHERE tid='$mainid' ORDER BY `changtime` DESC LIMIT 0,1";
        $aaresult = bmbdb_query($aaquery);
        $aaline = bmbdb_fetch_array($aaresult);
        
        $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $newtitle));
        
        if (empty($addinfos)) $addinfos = ($aaline['articletitle'] ? $aaline['articletitle'] : $articletitle_reply) . "," . $aaline['username'] . "," . $aaline['timestamp'];
        if (empty($truechangtime)) $truechangtime = $aaline['timestamp'];

        if ($noheldtop != 1) $changadd = ",changetime=$truechangtime";

        $query = "UPDATE {$database_up}threads SET title='$newtitle'{$changadd},replyer='$replyer',lastreply='$addinfos',replys='$totalre' WHERE tid='$mainid'";
        $result = bmbdb_query($query);
        // === Log File
        $showinfo = "{$gl[484]}: $count {$gl[485]}";
        $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$forumid}','m4$action')";
        $result = bmbdb_query($nquery);
        // Lastest Reply == START


        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$forumid}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET  topicnum = topicnum-$countxx,replysnum = replysnum+$countxx,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$forumid}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
        refresh_forumcach();
        jump_page("forums.php?forumid=$forumid", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    } 
} else {
    exit;
} 

function article_line($a_info)
{
    global $atrlistat, $bmf_manage, $totalre, $allinfooutput, $forum_cid, $topinfooutput, $quinfooutput, $database_up, $atrlistt, $atrlistvt, $forumid, $time_2, $filetopn, $forum_mang_t, $coninfo, $forum_cid, $listfilename, $username, $read_perpage, $timestamp, $login_status, $forum_admin, $admin_name, $idpath, $otherimages, $usertype;
    // list($title,$author,$date,$des,$icon,$filename,$reply,$hit,$last_mod_data,$islock,$topic_type)=explode("|",$a_info);
    $topinfooutput = "";
    $quinfooutput = "";
    $allinfooutput = "";
    $filetopn = "topic.php";
    $filename = $a_info['id'];
    $reply = $a_info['replys'];
    $totalre = $totalre + $reply;
    $topic_type = trim($a_info['type']);
    $topic_islock = trim($a_info['islock']);
    if ($a_info['addinfo']) {
        list($moveinfo, $isjztitle) = explode("|", $a_info['addinfo']);
        list($isjztitle, $isjzcolor, $jiacu, $shanchu, $xiahuau, $xietii) = explode(",", $isjztitle);
        $moveinfo = "<strong><span class='jiazhongcolor'>$moveinfo</span></strong>";
    } 
    // ///
    if (utf8_strlen($a_info['author']) >= 12) $viewauthor = substrfor($a_info['author'], 0, 9) . '...';
    else $viewauthor = $a_info['author'];
    if ($topic_type == 1) {
        $stats = "<img border='0' src='$otherimages/system/statistic.gif' alt='' />";
        if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/closesta.gif' alt='' />";
    } elseif ($topic_type == 2) {
        $stats = "<img border='0' src='$otherimages/system/ucommend.gif' alt='' />";
        if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/closeu.gif' alt='' />";
    } elseif ($topic_type >= 3) {
        $stats = "<img border='0' src='$otherimages/system/holdtopic.gif' alt='' />";
    } else {
        if ($username != $a_info['author']) {
            $stats = "<img border='0' src='$otherimages/system/topicnew.gif' alt='' />";
            if ($reply >= 10) $stats = "<img border='0' src='$otherimages/system/topichot.gif' alt='' />";
            if ($topic_islock == 2) $stats = "<img border='0' src='$otherimages/system/topcool.gif' alt='' />";
            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' src='$otherimages/system/topiclocked.gif' alt='' />";
        } else {
            $stats = "<img border='0' title='($forum_mang_t[14])' src='$otherimages/system/mytopicnew.gif' alt='' />";
            if ($reply >= 10) $stats = "<img border='0' title='($forum_mang_t[14])' src='$otherimages/system/mytopichot.gif' alt='' />";
            if ($topic_islock == 1 || $topic_islock == 3) $stats = "<img border='0' title='($forum_mang_t[14])' src='$otherimages/system/mytopiclocked.gif' alt='' />";
        } 
    } 

    $titlelong = stripslashes($a_info['title']);
    $title = stripslashes($a_info['title']);
    
    if ($a_info['other3']) $title = '<img src="images/attach/attach.gif" border="0" alt="" />' . $title;


    if (!empty($a_info['newdesc'])) $desshow = "\n$a_info[newdesc]";
    $title = "<a href='$filetopn?filename=$filename' title='$coninfo[4]\n$titlelong$desshow'>$title</a>";
    $lmd = explode(",", $a_info['lastreply']);
    $g = $timestamp - $lmd[2];
    if ($topic_islock == 2 || $topic_islock == 3) $title .= "  <img title=\"$forum_mang_t[17]\" src=\"$otherimages/system/jhinfo.gif\">";
    if ($lmd[2] == $date) $lmdauthor = "$forum_mang_t[18]";
    else $lmdauthor = "<a href=\"profile.php?job=show&target=" . urlencode($lmd[1]) . "\">$lmd[1]</a>";
    $lmdtime_tmp = get_date($lmd[2]) . ' ' . get_time($lmd[2]);
    $cmdtime_tmp = get_date($a_info['time']);
    if ($time_2) {
        $timetmp_a = $timestamp - $lmd[2];
        $timetoshow = get_add_date($timetmp_a);
        if ($timetoshow == "getfulldate") {
            $timetoshow = $lmdtime_tmp;
        } 
        $timedmp_b = $timestamp - $a_info['time'];
        $aimetoshow = get_add_date($timedmp_b);
        if ($aimetoshow == "getfulldate") {
            $aimetoshow = $cmdtime_tmp;
        } 
    } else {
        $timetoshow = $lmdtime_tmp;
        $aimetoshow = $cmdtime_tmp;
    } 
    $hit = $a_info['hits'];
    $urlauthor = urlencode($a_info['author']);

    if ($a_info['toptype'] == 9) {
        $stats = "<img border='0' src='$otherimages/announce.gif' alt='' />";
    } elseif ($a_info['toptype'] == 8) {
        $stats = "<img border='0' src='$otherimages/system/lockcattop.gif' alt='' />";
    } 


    if ($a_info['toptype'] == 9) {
        $topinfooutput .= $outputbs;
    } elseif ($a_info['toptype'] == 8) {
        $quinfooutput .= $outputbs;
    } else {
        $allinfooutput .= $outputbs;
    } 

    $icon = $a_info['face'];

    if (($icon == "ran" || $icon == "") && $emotrand == 1) {
        $icon = mt_rand(0, 52) . '.gif';
        $icon = "<a target='_blank' href='$filetopn?filename=$filename'><img src='images/emotion/$icon' alt='$forum_mang_t[13]' border='0' /></a>";
    } elseif (($icon == "ran" || $icon == "") && $emotrand != 1) {
        $icon = "&nbsp;";
    } else {
        $icon = "<a target='_blank' href='$filetopn?filename=$filename'><img src='images/emotion/$icon' alt='$forum_mang_t[13]' border='0' /></a>";
    } 

    $bmf_manage[] = array("filename" => $filename, "stats" => $stats, "icon" => $icon, "urlauthor" => $urlauthor, "hit" => $hit, "multipage" => $multipage, "title" => $title, "toplangg" => $toplangg, "aimetoshow" => $aimetoshow, "moveinfo" => $moveinfo, "viewauthor" => $viewauthor, "reply" => $reply, "lmdauthor" => $lmdauthor, "timetoshow" => $timetoshow);
} 

function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}
