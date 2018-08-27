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
include("include/template.php");
include("include/common.inc.php");
require("lang/$language/usercp.php");
if (!$openupusericon) {
	error_page($tip[1], $info[6], $programname, $info[10]);

} 

$add_title = $show_form_lng[116];
if (empty($job)) {
    $job = "modify";
} 
if ($login_status == 0) {
	error_page($tip[1], $info[6], $programname, $error[1]);
} 
if ($job == "delete") {
	if ($verify != $log_hash) die("Access Denied");
	eval(load_hook('int_ua_delete'));
    include("header.php");
    navi_bar($tip[1], $info[6]);
    $portait = explode('%', $thisavarts);
    $icon = "no_portait.gif%%%";
    $nquery = "UPDATE {$database_up}userlist SET avarts='$icon' WHERE userid='$userid'";
    $result = bmbdb_query($nquery);
    msg_box($programname, $ua[0]);
    include("footer.php");
    exit;
} 
if ($job == "modify") {
	eval(load_hook('int_ua_modify'));
    getUserInfo();
    if ($postamount < $max_avatars_upload_post) {
    	error_page($tip[1], $info[6], $programname, $ua[1]);

    } 
    include("header.php");
    navi_bar($tip[1], $info[6]);
    if (empty($step)) {
		eval(load_hook('int_ua_step0'));
        show_form();
        include("footer.php");
        exit;
    } elseif ($step == 2) {
        // -----------check---------
        $check = 1;
    	if (!is_uploaded_file($_FILES['attachment']['tmp_name']))
    	{
    		$FILE_NAME = "none";
    	} else {
        	$FILE_URL = $_FILES['attachment']['tmp_name'];
        	$FILE_NAME = safe_upload_name($_FILES['attachment']['name']);
        	$FILE_SIZE = $_FILES['attachment']['size'];
        	$FILE_TYPE = safe_upload_name($_FILES['attachment']['type']);
        }

        if ($openupusericon && $FILE_NAME != "none") {
            $upload = 1;
        } else {
            $upload = 0;
            $check = 0;
            $reason = $ua[2];
        } 
        if ($upload) {
            // -----User has uploaded some file-----
            if ($FILE_SIZE > $max_avatars_upload_size) {
                $check = 0;
                $reason = $ua[3];
            } else {
                $available_ext = explode(' ', $upload_avatars_type_available);
                $extcount = count($available_ext);
                $is_ext_allowed = 0;
                for ($i = 0; $i < $extcount; $i++) {
                    $currentext = $available_ext[$i];
                    if (eregi("\.\\$currentext$", $FILE_NAME)) {
                        $is_ext_allowed = 1;
                        break;
                    } 
                } 
                
                if (!is_uploaded_file($FILE_URL)) $is_ext_allowed = 0;
                
                if (!$is_ext_allowed) {
                    $check = 0;
                    $reason = $ua[4];
                } 
                
				eval(load_hook('int_ua_upload'));
                if ($is_ext_allowed != 1) @unlink($FILE_URL);
            } 
        } 

        if ($upload && $check) {
            $upload_tmpurl = "tmp/" . $timestamp . $currentext;
            move_uploaded_file($FILE_URL, $upload_tmpurl);
            $size = getimagesize($upload_tmpurl);
            
            if (($size[0] > $maxwidth || $size[1] > $maxheight) && eregi("\.(gif|jpg|jpeg|swf|bmp|png)$", $FILE_NAME)) {
            	imageshow($upload_tmpurl, $maxwidth);
        		$size[0] = $auto_width;
        		$size[1] = $auto_height;
            }

            if ($size[0] > $maxwidth || $size[1] > $maxheight) {
                $check = 0;
                $reason = $ua[5];
            } else {
                $upload_aname = "upload/usravatars/" . $timestamp . "." . $currentext;
                copy($upload_tmpurl, $upload_aname);
            } 
            $todelportait = explode('%', $thisavarts);
			eval(load_hook('int_ua_upload_process'));
            
            @unlink($upload_tmpurl);
        } 

        if ($check == 0) {
            msg_box($programname, "<br />$gl[15], $reason<br /><ul><li><a href=\"javascript:history.back(1)\">$gl[15]</a></li></ul>");
        } else {
			eval(load_hook('int_ua_sql'));
            $icon = "%$upload_aname%$size[0]%$size[1]";
            $nquery = "UPDATE {$database_up}userlist SET avarts='$icon' WHERE userid='$userid'";
            $result = bmbdb_query($nquery);
			eval(load_hook('int_ua_suc'));
            msg_box($programname, $step2_suc[0]);
        } 
        include("footer.php");
        exit;
    } 
} 
// ------------------------------------------------------------------------------------------
// +--------------------show the form to modify own information-----------------
function show_form()
{
    global $username, $template, $mmssms, $iblock, $block, $icount, $temfilename, $styleidcode, $language, $openstylereplace, $navbarshow, $use_honor, $max_avatars_upload_size, $admin_name, $sign_use_bmfcode, $bmfcode_sign, $show_form_lng, $ua, $gl, $po, $use_own_portait, $upload_avatars_type_available, $icon_upload_size, $openupusericon, $upload_type_icon, $bbs_title, $otherimages, $maxheight, $maxwidth, $opencutusericon;
    list($name, $pwd, $usericon, $email, $oicq, $regdate, $sign, $homepage, $area, $comment, $honor, $lastpost, $postamount, $pe, $none, $bym, $passask, $passanswer, $usertype, $money, $born, $group, $sex, $national) = get_user_info($username);
	$lang_zone = array("mmssms"=>$mmssms, "ua"=>$ua, "show_form_lng"=>$show_form_lng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
	$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
	require($template_name['usercp']);
	$template_name['ua'] = newtemplate("ua", $temfilename, $styleidcode, $lang_zone);


    $available_ext = explode(' ', $upload_avatars_type_available);
    $extcount = count($available_ext);
    $showtype = "<select><option>$ua[8]</option><option>---------</option>";
    for ($i = 0; $i < $extcount; $i++) {
        $showtype .= "<option>$available_ext[$i]</option>";
    } 
    $showtype .= "</select>";
	
	eval(load_hook('int_ua_form'));

	require($template_name['ua']);
	require($template_name['usercp_foot']);

} 
