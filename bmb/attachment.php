<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
define("DISABLED_GZIP", 1);
require("datafile/config.php");
require("getskin.php");
require("lang/$language/forums.php");
@set_time_limit(300);

if ($fdattach == 1) {
    if ((!strstr($_COOKIE['lastpath'], "attachment.php") && !strstr($_SERVER['HTTP_REFERER'], "attachment.php")) && (!strstr($_COOKIE['lastpath'], "topic.php") && !strstr($_SERVER['HTTP_REFERER'], "topic.php")) && (!strstr($_COOKIE['lastpath'], "article.php") && !strstr($_SERVER['HTTP_REFERER'], "article.php"))) {
        echo "<meta http-equiv=\"Refresh\" content=\"0; URL=index.php\">";
        exit;
    } 
} 

$line = bmbdb_fetch_array(bmbdb_query("SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}posts p LEFT JOIN {$database_up}userlist u ON u.userid=p.usrid WHERE id='$article' LIMIT 0,1"));
$forumid = $line['forumid'];

tbuser();
get_forum_info("");
check_forum_permission();

if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php?forumid=$forumid\">";
    exit;
} 
if ($down_attach == 0 || $userpoint < $down_attach_ww) {
	error_page($gl[53], $gl[273], $gl[192], $gl[277]);
}

$check = 0;

$title	= $line["articletitle"];
$author	= $line["username"];
$time	= $line["timestamp"];
$aaa	= $line["ip"];
$icon	= $line["usericon"];
$bym	= $line["other1"];
$usesign	= $line["options"];
$bymuser	= $line["other2"];
$editinfo	= $line["other4"];
$content	= $line["articlecontent"];
$sellmoney	= $line["other5"];
$uploadfilename = $line["other3"];
$authorusertype = $line["ugnum"];
$author_point	= $line["point"];

$query = "SELECT * FROM {$database_up}threads WHERE tid='{$line['tid']}' LIMIT 0,1";
$result = bmbdb_query($query);
$threadrow = bmbdb_fetch_array($result);
$replyerlist = explode("|", $threadrow['replyer']);

$somepostinfo = explode("_", $usesign);
if (strstr($somepostinfo[6], "§")) {
	$usergnshowtmp = explode("§", $somepostinfo[6]);
	$fdcoun = count($usergnshowtmp);
	for ($fds = 0;$fds < $fdcoun;$fds++) {
		if ($usergnshowtmp[$fds] != "") {
			$usergnshow[] = str_replace("new", "", $usergnshowtmp[$fds]);
		}
	}
} else {
	$usergnshow[0] = "nonono";
}
$nresult_friend = bmbdb_query("SELECT * FROM {$database_up}contacts WHERE `type`=2 and `owner`='$userid'");
while (false !== ($line_friend = bmbdb_fetch_array($nresult_friend))) {
	$iguserlist[] = $line_friend['conname'];
} 
$myusertype = $usertype;
if (@in_array($logonutnum, $usergnshow)) exit;

if (@in_array($author, $iguserlist) && $myusertype[22] != "1" && $myusertype[21] != "1") {
    exit;
} 

$xusertype = getLevelGroup($authorusertype, $usergroupdata, $forumid, $line['postamount'], $line['point']);
if (!@in_array($author, $iguserlist)) {
    include_once("datafile/banuserposts.php");
    if ((($banuserposts && in_array($author, $banuserposts)) || $author_point < $xusertype[114]) && $username != $author) {
        exit;
    } 
} 
list(, , , , , , , , , $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney']) = $xusertype;

if ($somepostinfo[1] != "checkbox" && (preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/eis", $content) || preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/eis", $content) || preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/eis", $content) || preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/eis", $content) || preg_match("/\[post\](.+?)\[\/post\]/eis", $content))) {
    // 付费帖部分
    if ($myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['sell'] && preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/eis", $content)) {
        $check1 = preg_replace_callback("/\[pay=(.+?)\](.+?)\[\/pay\]/is", function ($matches) { return checkpaid($matches[1]); }, $content);
        $check1 = $code14;
    } else {
        $check1 = 1;
    }
    // 威望值部分
    if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['jifen'] && preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/eis", $content)) {
        $check2 = preg_replace_callback("/\[hide=(.+?)\](.+?)\[\/hide\]/is", function ($matches) { return checkhiden($matches[1]); }, $content);
        $check2 = $code4;
    } else {
        $check2 = 1;
    }
    // 回复查看部分
    if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['reply'] && preg_match("/\[post\](.+?)\[\/post\]/eis", $content)) {
        $check3 = preg_replace_callback("/\[post\](.+?)\[\/post\]/is", function ($matches) { return checkpost($matches[1]); }, $content);
        $check3 = $code1;
   } else {
        $check3 = 1;
    }
    // 帖子查看部分
    if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['hpost'] && preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/eis", $content)) {
        $check4 = preg_replace_callback("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", function ($matches) { return checkhiden($matches[1], 'hpost');}, $content);
        $check4 = $code4;
   } else {
        $check4 = 1;
    }
    // 金钱查看部分
    if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['hmoney'] && preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/eis", $content)) {
        $check5 = preg_replace_callback("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", function ($matches) { return checkhiden($matches[1], 'hmoney');}, $content);
        $check5 = $code4;
    } else {
        $check5 = (!$is_rec && $line['posttrash'] == 1) ? 0 : 1;
    }

    if ($check1 == 1 && $check2 == 1 && $check3 == 1 && $check4 == 1 && $check5 == 1) {
        $check = 1;
    } 
    if ($login_status == 0) $check = 0; 
    // 全部完成
} else {
    $check = 1;
}

if ($check == 0) {
	error_page($gl[53], $gl[273], $gl[53], $gl[274]);
}

if (strpos($uploadfilename, "×")) {
    $attachshow = explode("×", $uploadfilename);
} else {
    $attachshow[0] = $uploadfilename;
}
$showdes = explode("◎", $attachshow[$am]);
$showdes[2]++;
$attachshow[$am] = implode("◎", $showdes);
$uploadfilename = implode("×", $attachshow);
eval(load_hook('int_attachment'));

if (file_exists("upload/$showdes[0]") && !is_dir("upload/$showdes[0]")) {
	//@error_reporting(E_ALL ^ E_NOTICE);
	if ($showdes[0] != "") {
		$readed = explode("|", $_SESSION['readpost']);
		if (!in_array($showdes[0], $readed)) {
			$_SESSION['readpost'] = $showdes[0] . "|" . $_SESSION['readpost'];
			$aquery = "UPDATE {$database_up}posts SET other3='$uploadfilename' WHERE id='$article' LIMIT 1";
			$aresult = bmbdb_query($aquery);
		}
	}
	$showdes[3] = str_replace("[BMDESAõ]", "◎", str_replace("[BMDESBõ]", "×", $showdes[3]));
	$downloadfile = "upload/$showdes[0]";
	if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) $showdes[3] = rawurlencode($showdes[3]);
	$sfilesize = filesize($downloadfile); 
	$sfilemtime = filemtime($downloadfile); 
	$etag = "{$sfilesize}_{$sfilemtime}";

	if ($tfdattach && isset($_SERVER['HTTP_IF_NONE_MATCH']) && $etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
		header('Etag: $etag',true,304);
		exit;
	}
	$modifiedTime = gmdate('D, d M Y H:i:s', $sfilemtime) . ' GMT';
	if ($showdes[5] == 1 && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $etag == $_SERVER['HTTP_IF_MODIFIED_SINCE']) {
		header("Last-Modified: $modifiedTime",true,304);
		exit;
	}
	if ($showdes[5] == 1) {
		@header("Content-Disposition: attachment; filename=\"$showdes[3]\"");
		@header("Content-Type: unknown/unknown");
		@header('Content-Length: '.$sfilesize);
		@header("Etag: $etag");
		@header("Last-Modified: $modifiedTime");
		@header("Content-Encoding: gzip");
		echo readfromfile($downloadfile);
	} else {
		if ($tfdattach) {
			if (preg_match("/\.(gif|jpg|jpeg|bmp|png)$/i", $showdes[0])) {
				@header("Content-Disposition: inline; filename=\"$showdes[3]\"");
			} else {
				@header("Content-Disposition: attachment; filename=\"$showdes[3]\"");
			}
			@header("Etag: $etag");
			@header("Last-Modified: $modifiedTime");
			@header("Content-Type: unknown/unknown");
			@header('Content-Length: '.$sfilesize);
			echo readfromfile($downloadfile);
		} else @header("Location: $downloadfile");
	}
} else {
	die("BMForum System Tips: Error Filename");
}
