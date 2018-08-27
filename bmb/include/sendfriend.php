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
require("lang/$language/forums.php");
require("newtem/$temfilename/global.php");
require("include/readpost.func.php");

if (empty($username) || $login_status == 0) {
    $content = "$gl[233]<br /><br />$gl[73]";
	error_page($navi_bar_des, $gl[190], $gl[53], $content, $gl[53]);

} 
$add_title = " &gt; $gl[190]";

$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$line = bmbdb_fetch_array($result);
$threadrow = $line;
$replyerlist = explode("|", $line['replyer']);
$forumid = $line[forumid];
$line['title'] = stripslashes($line['title']);
get_forum_info("");
tbuser();
check_forum_permission();

if ($row['ttrash'] == 1) {
    $checktrash = "yes";
} 
if ($checktrash == "yes" && $view_recybin != "1") {
	error_page($error[3], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[192], $gl[438], $gl[192]);
} 
if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=forums.php?forumid=$forumid\">";
    exit;
} 
if ($p_read_post == 0 || $userpoint < $read_allow_ww) include("footer.php");
if ($step != 2) {
    include("header.php");
    print_bar();
    print_form();
    include("footer.php");
    exit;
} elseif ($_POST['step'] == 2) {
    $check = 1;
    if (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,4}$/", $fmail)) {
        $reason = "$gl[195]";
        $check = 0;
    } 
    if (empty($postname) || empty($incept) || empty($fmail) || empty($fcontent)) {
        $reason = "$gl[196]";
        $check = 0;
    } 
    if ($check == 0) {
	    $content = "$gl[233]<br /><br />$reason";
		error_page($navi_bar_des, $gl[190], $gl[53], $content, $gl[53]);

    } 
    $yoptitle = $line["title"];
    $yopauthor = $line["author"];
    $textmessage = "Hi, $incept ,\n    $fcontent \n\n";
    $textmessage .= "$gl[197]\n";
    $textmessage .= " $yopauthor \n\n";
    $textmessage .= "$gl[198]\n";
    $textmessage .= "$yoptitle \n\n";
    $textmessage .= "$gl[199]\n";
    $textmessage .= "$script_pos/topic.php?filename=$filename \n\n\n";
    if ($method == 'all') {
        $textmessage .= "$gl[200]\n";
        $bcode_post[bmfcodes] = 1;
        $bcode_post[pic] = 0;
        $aquery = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}posts p LEFT JOIN {$database_up}userlist u ON u.userid=p.usrid WHERE p.tid='$filename' ORDER BY 'id' ASC";
        $aresult = bmbdb_query($aquery);
        while (false !== ($xline = bmbdb_fetch_array($aresult))) {
            $title = stripslashes($xline['articletitle']);
            $author = $xline[username];
            $content = $xline[articlecontent];
            $time = $xline[timestamp];
            $aaa = $xline[ip];
            $icon = $xline[usericon];
            $usesign = $xline[options];
            $bym = $xline[other1];
            $bymuser = $xline[other2];
            $uploadfilename = $xline[other3];
            $editinfo = $xline[other4];
            $sellmoney = $xline[other5];
            
		    if (!@in_array($aresult["username"], $iguserlist)) {
		    	$author_type = getLevelGroup($aresult["ugnum"], $usergroupdata, $forumid, $aresult['postamount'], $aresult['point']);
		        include_once("datafile/banuserposts.php");
		        if ((($banuserposts && in_array($aresult["username"], $banuserposts)) || $aresult["point"] < $author_type[114]) && $username != $aresult["username"]) {
		            $orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
		        } 
		    } else {
		    	$orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
		    }
            
            $contentline = bmbconvert(stripslashes($content), $bmfcode_post);
		    if (!@in_array($xline["username"], $iguserlist)) {
		    	$author_type = getLevelGroup($xline["ugnum"], $usergroupdata, $forumid, $xline['postamount'], $xline['point']);
		        include_once("datafile/banuserposts.php");
		        if ((($banuserposts && in_array($xline["username"], $banuserposts)) || $xline["point"] < $author_type[114]) && $username != $xline["username"]) {
		            $contentline = "<span class=\"jiazhongcolor\">Banned Post</span>";
		        } 
		    }
			if ($xline['posttrash'] == 1) {
				$orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
			}
            $contentline = preg_replace("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", "$gl[162]", $contentline);
            $contentline = preg_replace("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", "$gl[162]", $contentline);
            $contentline = preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "$gl[162]", $contentline);
            $contentline = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "$gl[161]", $contentline);
            $contentline = preg_replace("/\[post\](.+?)\[\/post\]/is", "$gl[163]", $contentline);
            $contentline = str_replace("&lt;", "<", $contentline);
            $contentline = str_replace("&gt;", ">", $contentline);
            
			if ($bmfopt['text_wm']) {
				$contentline = text_watermark($contentline);
			}
            
            $contentline = str_replace("<br />", "\n", $contentline);
            $contentline = str_replace("│", "|", $contentline);
            $contentline = str_replace(" &nbsp;", "  ", $contentline);
            $contentline = strip_tags($contentline);
            $time = getfulldate($time);
            $textmessage .= "-------------------------------\n";
            $textmessage .= "$author -  $title $time \n";
            $textmessage .= " $contentline \n\n";
        } 
    } 
    $t = time();
    $t = getfulldate($t);
    $textmessage .= "=================================\n  $postname ( $username )\n  $t \n";
    $textmessage .= "\n-----------Welcome -------------\n";
    $temp = get_user_info($username);
    $send_address = $temp['mailadd'];
    include("header.php");
    print_bar();
    
    $results = "$gl[31]<br/>";
	eval(load_hook('int_sendfriend_send'));

    include_once("include/sendmail.inc.php");
    if (BMMailer($fmail, "$gl[201] $postname $gl[202]", nl2br($textmessage), '', '', $username, $send_address)) $results.= "$gl[203] $incept $fmail<br/>Done";
    else $results.= "$gl[203] $incept $fmail<br/>Error";
    
	msg_box($gl[216], $results);
    include("footer.php");
    exit;
} 
function print_form()
{
    global $username, $filename, $forumid, $yoptitle, $yopauthor, $bm_skin, $otherimages, $temfilename, $styleidcode, $lang_zone, $gl, $mode;

	require("include/template.php");
	eval(load_hook('int_sendfriend_form'));

	$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("sendfriend", $temfilename, $styleidcode, $lang_zone);
	require($template_name);

} 

function print_bar()
{
    global $forum_name, $line, $forumid, $yoptitle, $filename, $gl, $bbs_title, $mode;
    navi_bar($gl[216], "<a href='forums.php?forumid=$forumid'>$forum_name</a>", "<a href='topic.php?filename=$filename'>{$line['title']}</a>", $gl[190], 'no');
} 
