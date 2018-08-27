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

if (empty($target)) {
    $content = "$gl[233]<br /><br />$gl[229]";
	error_page($gl[230], "Management", $gl[53], $content);

} 
$forumid = $target;
include("lang/$language/global.php");
get_forum_info("");
$check_user = 0;
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########
if ($usertype[21] == "1") $check_user = 1;
if ($usertype[22] == "1") $check_user = 1;
if (!$modcenter_true) $check_user = 0;

if ($verify != $log_hash && $action) $check_user = 0;


if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 

include("header.php");
navi_bar($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>");


$t = time();

if (empty($action)) {
    // Load Forum Rules
    @include("datafile/rules/$target.php");
    $ourrule = str_replace("<br />", "\n", $ourrule); 
    
$info=<<<EOT
    $gl[244]
$content
<strong>$gl[495]</strong><br /><br />
$gl[496]<br /><br /></form>
<form action="misc.php?p=modfix" method="post"><input type="hidden" name="action" value="rules" />
    <input type="hidden" name="target" value="$target" />
<textarea cols="60" rows="6" name="forumrules">$ourrule</textarea>
      <br />
   <input type="submit" value="$gl[497]" />
    <input type='hidden' name='verify' value='$log_hash' />
</form>
EOT;

	msg_box($gl[230], $info, 0);
    include("footer.php");
    exit;
} elseif ($action == "rules") {
    // ------Forum Rules-----------
    $fp = fopen("datafile/rules/$target.php", "w");
    fputs($fp, '<?php  $ourrule=\'' . str_replace("\n", "<br />", str_replace("'", "\'", stripslashes(safe_convert($forumrules)))) . '\';');
    fclose($fp);
    
    // Log
    $showinfo = "$gl[498]";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','','$timestamp','{$target}','rules')";
    $result = bmbdb_query($nquery);
    
} elseif ($action == "cleanup") {
    if (($method == "byamount" && empty($limitnum)) || ($method == "bydate" && empty($limitdate))) {
    	msg_box($gl[245], $gl[246]);
        include("footer.php");
        exit;
    } 

    clean_up_forum($target);
    update_sum();
    refresh_forumcach();
} elseif ($action == "updatecount") {
    update_count_forum($target);
    update_sum();
    refresh_forumcach();
} 
msg_box($gl[247], $gl[248]);
include("footer.php");
exit;
function clean_up_forum($id)
{
    // --------Clear the old posts in a forum-----------
    global $method, $limitnum, $username, $timestamp, $limitdate, $gl, $t, $database_up;

    $addquery = " AND forumid='$id'";
    $getquery = " WHERE forumid='$id'";
    
    $target = $id;

    if ($method == "byamount") {
        $query = "SELECT COUNT(*) FROM {$database_up}threads $getquery";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $limitnum = $fcount['COUNT(*)'] - $limitnum;
        if ($limitnum > 0) {
            $byquery = " tid<'$limitnum'";
            $query = "DELETE FROM {$database_up}threads $getquery ORDER BY `changetime` ASC LIMIT $limitnum";
            $result = bmbdb_query($query);
            $query = "DELETE FROM {$database_up}posts WHERE $byquery $addquery";
            $result = bmbdb_query($query);
        } 
    } elseif ($method == "bydate") {
        $limitdate = $timestamp - $limitdate * 86400;
        $byquery = " changtime<'$limitdate'";
        $tbyquery = " changetime<'$limitdate'";

        $query = "DELETE FROM {$database_up}threads WHERE $tbyquery $addquery";
        $result = bmbdb_query($query);
        $query = "DELETE FROM {$database_up}posts WHERE $byquery $addquery";
        $result = bmbdb_query($query);
    } 
    // Log
    if ($method != "repairlate") {
	    $showinfo = "$gl[486]";
	    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','','$timestamp','{$id}','clean')";
	    $result = bmbdb_query($nquery);
	}
    // Lastest Reply == START
    $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$target}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
    $cxresult = bmbdb_query($cxquery);
    $cxline = bmbdb_fetch_array($cxresult);

    $lastinfos = explode(",", $cxline['lastreply']);
    $nquery = "UPDATE {$database_up}forumdata SET  fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$target}'";
    $result = bmbdb_query($nquery);
    // Lastest Reply == END
    // ----12:52 2003-11-8 done
} 
function update_count_forum($id)
{
    global $database_up;
    // -----------重新统计版块文章数目-----------
    $query = "SELECT COUNT(*) FROM {$database_up}posts WHERE forumid='$id'";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $amoutnum = $fcount['COUNT(*)'];

    $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE type>=3 AND forumid='$id'";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $pinnum = $fcount['COUNT(tid)'];

    $query = "SELECT COUNT(*) FROM {$database_up}threads WHERE forumid='$id' AND ttrash!='1'";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $topicnum = $fcount['COUNT(*)'];

    $replynum = $amoutnum - $topicnum;

    $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE islock !=1 AND islock!=0 AND forumid='$id' AND ttrash!='1'"));
    $digestcount = $countrow['COUNT(tid)'];

    $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$id' AND ttrash='1'"));
    $trashcount = $countrow['COUNT(tid)'];

    $nquery = "UPDATE {$database_up}forumdata SET digestcount=$digestcount,trashcount=$trashcount,pincount='$pinnum',topicnum='$topicnum',replysnum='$replynum' WHERE id='$id'";
    $result = bmbdb_query($nquery);
} 
function update_sum()
{
    // ----------各版块文章数目求和------------
    global $database_up;
    $result = bmbdb_query("SELECT COUNT(id) FROM {$database_up}posts", 0);
    $fcount = bmbdb_fetch_array($result);
    $amoutnum = $fcount['COUNT(id)'];
    
    $result = bmbdb_query("SELECT COUNT(id) FROM {$database_up}threads", 0);
    $fcount = bmbdb_fetch_array($result);
    $threads = $fcount['COUNT(id)'];

    $nquery = "UPDATE {$database_up}lastest SET threadnum='{$threads}',postsnum='$amoutnum' WHERE pageid='index'";
    $result = bmbdb_query($nquery);
} 

