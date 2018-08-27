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
include("include/bmbcodes.php");
include("include/template.php");
require("lang/$language/usercp.php");

if ($login_status == 0) {
	error_page($tip[1], $pgn2, $programname, $error[1]);
} 

include("header.php");
naviBar($pgn2, $tip[1]);

$userinfo = get_user_info($username);
if (!empty($userinfo['username'])) {
    $usertype = $userinfo['ugnum'];
    $usertype = explode("|", $usergroupdata[$usertype]);
} 
$money = $userinfo['money'];
$level = getUserLevel($userinfo['postamount'], $userinfo['point'], $username, $userinfo['ugnum']);
if (!$money) $money = "0";
$bym = floor($userinfo['point'] / 10);

$usericon = get_user_portait($userinfo['avarts'], false, $userinfo['mailadd']);

if ($usertype[21] == "1" && $supmotitle) $level = $supmotitle;
if ($usertype[22] == "1" && $admintitle) $level = $admintitle;


$lang_zone = array("mmssms"=>$mmssms, "gl[512]"=>$gl[512], "info"=>$info, "tip"=>$tip, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
$template_name['usercp_home'] = newtemplate("usercp_home", $temfilename, $styleidcode, $lang_zone);
$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);


if ($act != "active") {
    if ($level_score_mode == 1) $level_score = $bym;
    elseif ($level_score_mode == 2) {
    	$score = $bym;
    	$amount = $userinfo['postamount'];
        eval($level_score_php);
        $level_score = $amount;
    } else {
    	$level_score = $userinfo['postamount'];
    }
	$usercp_play = array("uid" => $userinfo['userid'], "level_score" => $level_score, "level" => $level, "honor" => $userinfo['headtitle'], "usericon" => $usericon, "money" => $money, "bym" => $bym, "bbs_money" => $bbs_money, "name" => $username, "pgn3" => $pgn3);
	eval(load_hook('int_usercp_home'));
}
if ($act != "active") {
	$currentMod['usercp'] = true;
} else {
	$currentMod['active'] = true;
}
require($template_name['usercp']);
if ($act == "active") {
	eval(load_hook('int_usercp_acitve'));
	include_once("include/active.php");
	require($template_name['usercp_foot']);
	include("footer.php");
}

require($template_name['usercp_home']);
require($template_name['usercp_foot']);
include("footer.php");

