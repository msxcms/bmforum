<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
// Included: Interface and Language, headers,etc.
// -----------------------------------------------------------
if (!defined('CONFIGLOADED')) die("Access Denied");
@define("INBMFORUM", 1);
if ($gzip_compress == "1" && !defined("DISABLED_GZIP")) {
    if(!ob_start("ob_gzhandler")) {
    	ob_start();
    }
} else {
    ob_start();
} 
$sqltype = basename($sqltype);
$skin = $useraccess = $admgroupdata = $hook_list = ""; 
require_once("datafile/language.php");

if (!empty($_COOKIE["userlanguage"]) && file_exists("lang/" . basename($_COOKIE["userlanguage"]) . "/global.php")) $language = $_COOKIE["userlanguage"];
elseif (empty($_COOKIE["userlanguage"]) && $autolang) {
    $langlist = @file("datafile/langlist.php");
    $count_language = count($langlist);
    $useraccept = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $useraccept = $useraccept[0];
    for($i = 0;$i < $count_language;$i++) {
        $xlangshow = explode("|", $langlist[$i]);
        $langarray[$xlangshow[1]] = $xlangshow[2];
    } 
    $langkeyname = array_search_bit($useraccept, $langarray);
    if (!empty($langkeyname)) {
    	bmb_setcookie("userlanguage", $langkeyname);
        $_SESSION['userlanguage'] = $langkeyname;
        $language = $langkeyname;
    } 
} 
// ================== Show List Cookie
if ($_COOKIE['view_online'] == "show") $view_index_online = 1;
elseif ($_COOKIE['view_online'] == "hide") $view_index_online = 0;

if ($_GET["online_show"] == "show" || $_GET["online_show"] == "hide") {
    bmb_setcookie("view_online", $_GET["online_show"]);
}
//......... No AJAX option
if ($_COOKIE['close_ajax'] == 1) $allow_ajax_reply = $allow_ajax_browse = 0;
//
@include_once("datafile/hooks/list.php");
require_once("datafile/time.php");
require_once("include/global.php");
require_once("datafile/style.php");

$skinname = basename($_COOKIE['bmbskin']);
$styleopen = file("datafile/stylelist.php");
$stylecount = count($styleopen);
for($styi = 0;$styi < $stylecount;$styi++) {
	$styledetail = explode("|", $styleopen[$styi]);
	if ($styledetail[1] == $skinname) {
		$styledetail[3] = basename($styledetail[3]);
		if (file_exists("datafile/style/" . $styledetail[3])) include("datafile/style/" . $styledetail[3]);
		else include("datafile/style/" . basename($skin));
	} 
} 

@header("Forum-Powered-By: $verandproname / BMForum.com");
@header("Content-Type: $encode_info");

$bbsdetime = $time_1;
if ($_COOKIE["usertimezone"] != "") {
    $time_1 = $_COOKIE["usertimezone"];
} 
if ($_COOKIE["customshowtime"] != "") {
    $time_2 = $_COOKIE["customshowtime"];
} 
$time_f = $_COOKIE["bmf_date_format"] ? $_COOKIE["bmf_date_format"] : $time_f;

// Schedule
include_once("include/schedule.php");

get_forum_info("");
if ($forum_style <> "") {
    if (file_exists("datafile/style/" . basename($forum_style))) include("datafile/style/" . basename($forum_style));
} 
require("newtem/$temfilename/global.php");
$error = $cancel_guestfile = "";

if (($bbs_is_open == 0 && empty($thisprog) && $usertype[21] != 1 && $usertype[22] != 1) || (($nowhours < $ddwtail[0] || $nowhours >= $ddwtail[1]) && empty($thisprog))) {
	error_page($gl[469], $gl[469], $gl[469], $bbs_close_msg);
} 
@include_once("datafile/pluginclude.php");
// Forum permission check of some types
if (!defined("canceltbauto")) tbuser();

eval(load_hook('int_getskin_bottom'));

function array_search_bit($search, $array_in)
{
    foreach ($array_in as $key => $value) {
        if (@strpos($value, $search) !== false)
            return $key;
    } 
    return false;
} 
// BMForum Cookie
function bmb_setcookie($varname, $varcookie, $cookietime = false, $httponly = false)
{
	global $cookie_p, $cookie_d;
 	eval(load_hook('int_getskin_bmb_setcookie'));
 	
 	$cookie_p = $httponly && PHP_VERSION < '5.2.0' ? $cookie_p.'; HttpOnly' : $cookie_p;
 	
	if ($cookietime === false) {
		if($_COOKIE["cookie_time_bmb"] > 0 && $_COOKIE["cookie_time_bmb"] != "") {
        	$cookietime = time() + $_COOKIE["cookie_time_bmb"];
        } else {
        	$cookietime = 0;
        }
    } 
    
    $cookiesecure = ($_SERVER['HTTPS']) ? true : false;
    
    if(PHP_VERSION < '5.2.0') {
    	setcookie($varname, $varcookie, $cookietime, $cookie_p, $cookie_d, $cookiesecure);
    } else {
    	setcookie($varname, $varcookie, $cookietime, $cookie_p, $cookie_d, $cookiesecure, $httponly);
    }
}
function load_hook($hookname)
{
	global $hook_list, $hook_list_disabled;
	if (!$hook_list || !$hookname) {
		return $evalcode;
	}
	$evalcode = "";
	if ($hook_list[$hookname]) {
	    foreach($hook_list[$hookname] as $value)
	    {
	    	if ($hook_list_disabled[$hookname][$value] != 1) $evalcode .= "include('include/hooks/".basename($hookname).".".basename($value).".php');";
	    }
	}
	return $evalcode;
}