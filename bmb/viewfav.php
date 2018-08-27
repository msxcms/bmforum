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
include("include/template.php");
require("lang/$language/usercp.php");
require("include/common.inc.php");

$navi_bar_des = $info[7];
$navi_bar_l2 = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
if ($login_status != 1) {
    $status = $error[3];
	error_page($navi_bar_des, $navi_bar_l2, $gl[53], $step2_error[16], $gl[53]);
} 
if ($action == "del") {
	eval(load_hook('int_viewfav_del'));
    if ($filename == "") {
	    bmbdb_query("DELETE FROM {$database_up}favorites WHERE `owner`='$userid'");
    } else {
	    bmbdb_query("DELETE FROM {$database_up}favorites WHERE `tid`='$filename' and `owner`='$userid' ");
    } 
    
	jump_page("viewfav.php", "$gl[2]",
            "<strong>$gl[2]</strong><br /><br />$gl[3] <a href='viewfav.php'>$navbarshow[3]</a> | <a href='index.php'>$gl[5]</a>", 3);
} elseif ($action == "subscribe") {
	if (!$step) {
		if ($send_mail) {
			$add_types = "<option value=\"2\">$gl[518]</option><option value=\"3\">$gl[519]</option>";
		}
		eval(load_hook('int_viewfav_subscribe_step_1'));

$det=<<<BMF
$gl[522]<br />
<form action="viewfav.php?action=subscribe&amp;step=2&amp;target=$target" method="post" style="margin:0px;" />
<div class="quote_dialog">
$gl[523] <select name="subscribemethod">
<option value="0">$gl[516]</option>
<option value="1">$gl[517]</option>
$add_types
</select>
<br />
<br />
<input type="submit" name="submit" value="$gl[521]" />
</div>
</form>

BMF;

		error_page($gl[521], $gl[520], $gl[520], $det);
	} else {
		eval(load_hook('int_viewfav_subscribe_step_2'));
		if ($_POST['subscribemethod'] >= 0 && $_POST['subscribemethod'] <= 3) {
			$subscribemethod = floor($_POST['subscribemethod']);
			bmbdb_query("UPDATE {$database_up}favorites SET subscribe='{$subscribemethod}' WHERE `tid`='$target' and `owner`='$userid' ");
		}
		
		jump_page("topic.php?filename=$target", "$gl[2]",
	            "<strong>$gl[2]</strong><br /><br />$gl[3] <a href='topic.php?filename=$target'>$gl[8]</a> | <a href='viewfav.php'>$navbarshow[3]</a> | <a href='index.php'>$gl[5]</a>", 3);
	}
} elseif ($action == "add") {
    if (empty($username) || empty($filename)) exit;
	$filename = abs($filename);
	
	$check_exists = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}favorites WHERE `tid`='$filename' AND `owner`='$userid'");
	eval(load_hook('int_viewfav_add'));
	if ($check_exists['COUNT(`id`)'] < 1) {
		$echoInfo = $gl[6];
    	bmbdb_query("INSERT INTO {$database_up}favorites (`tid`,`owner`,`subscribe`) VALUES ('$filename','$userid','0')");
    } else {
    	$echoInfo = $gl[7];
    }

    jump_page("topic.php?filename=$filename", $echoInfo,
        "<strong>$echoInfo</strong><br /><br />$gl[3] <a href='topic.php?filename=$filename'>$gl[8]</a> | <a href='viewfav.php?action=subscribe&target=$filename'>$gl[520]</a> | <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='index.php'>$gl[5]</a>", 3);
    exit;
} 
if (empty($page)) $page = 1;
$forum_name = $info[9];
$add_title = " &gt; $forum_name";
require("header.php");

$add_sql = "";
for($i = 0;$i < $forumscount;$i++) {
    if (!(!check_forum_permission(0, 1, $sxfourmrow[$i])) && $sxfourmrow[$i][type] != "category" && check_permission($username, $sxfourmrow[$i][type]) && !$sxfourmrow[$i][forumpass] && $sxfourmrow[$i][forumpass] <> "d41d8cd98f00b204e9800998ecf8427e") {
        $forumnum["{$sxfourmrow[$i][id]}"] = $sxfourmrow[$i][bbsname];
    } else {
        $add_sql .= ",'{$sxfourmrow[$i][id]}'";
    } 
} 
if ($add_sql != "") $add_sql = "and !(t.forumid in('xxxxx'" . $add_sql ."))";

$result = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}favorites where `owner`='$userid'");

$count = $amount = $result['COUNT(`id`)'];

require("lang/$language/usercp.php");
$des = $info[7];
navi_bar($des, $forum_name);

$lang_zone = array("mmssms"=>$mmssms, "gl"=>$gl, "smlng"=>$smlng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);

$currentMod['viewfav'] = true;

$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
eval(load_hook('int_viewfav_usercp'));
require($template_name['usercp']);
$template_name['viewfav'] = newtemplate("viewfav", $temfilename, $styleidcode, $lang_zone);

$bmf_view = "";

if ($count % $perpage == 0) $fav_maxpageno = $count / $perpage;
else $fav_maxpageno = floor($count / $perpage) + 1;
if ($page > $fav_maxpageno) $page = $fav_maxpageno;
$pagemin = min(($page-1) * $perpage , $count-1);
$pagemax = min($pagemin + $perpage-1, $count-1);

$readed_t = explode("|", $_SESSION['readpost']);

if ($pagemin < 0) $pagemin = 0;

$result = bmbdb_query("SELECT f.*,t.* FROM {$database_up}favorites f LEFT JOIN {$database_up}threads t ON f.tid=t.tid where f.`owner`='$userid' {$add_sql} ORDER BY t.`changetime` DESC LIMIT $pagemin,$perpage");
if ($count > 0) {
    for ($i = $pagemin; $i <= $pagemax; $i++) {
	    $row = bmbdb_fetch_array($result);
        article_line();
    } 
} else {
    $fav_maxpageno = 1;
} 

$pagerLink = "viewfav.php?page={page}";



eval(load_hook('int_viewfav_op'));

require($template_name['viewfav']);
require($template_name['usercp_foot']);
require("footer.php");
exit;

function article_line()
{
    global $aimetoshow, $title, $multipage, $stats, $icon, $row, $aimetoshow, $lmdtime, $lmdauthor, $forumnum, $forum_name, $readed_t, $bmf_view, $gl, $database_up, $username, $read_perpage, $otherimages, $maxpageno, $titlelink, $urllmdauthor;
    $id = $row['id'];
    $filename = $row['tid'];
    $owner = $row['owner'];
    $subscribe = $row['subscribe'];

    $title = stripslashes($row['title']);

    if (empty($row['tid'])) {
		bmbdb_query("DELETE FROM {$database_up}favorites WHERE id='$id'");
        return;
    } 

    $viewauthor = $row['author'];
    $author = $viewauthor;

    if ($subscribe == 1) {
    	$subscribestatus = $gl[517];
    } elseif ($subscribe == 2) {
    	$subscribestatus = $gl[518];
    } elseif ($subscribe == 3) {
    	$subscribestatus = $gl[519];
    } else {
    	$subscribestatus = $gl[516];
    }

	run_thread_list($row);
	
    $urlauthor = urlencode($viewauthor);
    
    $bmf_view[]=array($row['tid'], $stats, $icon, $title, $multipage, $row['forumid'], $forum_name, $urlauthor, $row['author'], $aimetoshow, $row['replys'], $row['hits'], $lmdtime, $lmdauthor, $subscribestatus, $id, 'maxpageno' => $maxpageno, 'titlelink' => $titlelink, 'urllmdauthor' => $urllmdauthor);
//                      0            1       2       3        4              5               6               7     8              9             10              11           12       13            14               15
} 
// ---------Display the Error Message Table-------------------
