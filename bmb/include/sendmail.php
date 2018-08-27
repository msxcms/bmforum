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

$add_title = " &gt; $gl[36]";

if ($login_status == 0) {
	error_page($gl[37], $gl[36], $gl[46], $gl[47]."<ul><li><a href=\"javascript:history.go(-1);\">$gl[15]</a><li><a href=login.php>$gl[48]</a></ul>");
} 
require("header.php");
navi_bar("$gl[37]", "$gl[36]", '', 'no');

$action = $_POST['action'];

if (strpos($target, "/") !== false) die;
if ($target) {
    $line = get_user_info($target);
    if (empty($line['username'])) exit;
    $usermail = $line['mailadd'];
    $receiver = $line['username'];
} else {
    $usermail = $admin_email;
    $receiver = $admin_name;
} 

if (!empty($action) && $action == "send") {
    if (empty($emailcontent) || utf8_strlen($emailcontent) <= 20) {
        $action = "fail";
        $status = "$gl[38]";
    } 
} 

if (empty($action) || $action == "fail") {
	require("include/template.php");
	eval(load_hook('int_sendmail_start'));

	$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("sendmail", $temfilename, $styleidcode, $lang_zone);
	require($template_name);
} 
if (!empty($action) && $action == "send") {
	if ($target) {
        $temp = get_user_info($username);
        $email = $temp['mailadd'];
    } else {
    	$email = $admin_email;
        $temp['mailtype'] = "";
    }
    

	$results = $gl[31];
    include_once("include/sendmail.inc.php");
	eval(load_hook('int_sendmail_prepare'));
    if ($temp['mailtype'] != "none") {
        if (BMMailer($usermail, $title . nl2br($emailcontent), '', '', $username, $email)) $results= $gl[35];
        else $results= "$gl[44]";
    } else {
        $results= "$gl[44]";
    }
    msg_box($gl[39], $results);

} 

include("footer.php");
