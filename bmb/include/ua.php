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

$uploadImgUsing = $_GET['cover'] ? 1 : 0;
if($uploadImgUsing) {
	$maxwidth = 384;
	$maxheight = 65;
	checkUserAdd($userid);
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
    naviBar($info[6], $tip[1]);
	$path = $uploadImgUsing ? "upload/usrcover/" : "upload/usravatars/";
    $afilename = $path . $userid . ".jpg";
    @unlink($afilename);
    if($uploadImgUsing) {
	    $nquery = "UPDATE {$database_up}user_add SET cover='' WHERE uid='$userid'";
	    $result = bmbdb_query($nquery);
		@unlink("upload/usrcover/{$userid}.jpg.large");
	} else {
	    $portait = explode('%', $thisavarts);
	    $icon = "no_portait.gif%%%";
	    $nquery = "UPDATE {$database_up}userlist SET avarts='$icon' WHERE userid='$userid'";
	    $result = bmbdb_query($nquery);
	}
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
    naviBar($info[6], $tip[1]);
    if (empty($step)) {
		eval(load_hook('int_ua_step0'));
        show_form();
        include("footer.php");
        exit;
    } elseif ($step == 2) {
        // -----------check---------
        $check = 1;
        if ($_POST['imgdata']) {
        	$data = substr(strstr($_POST['imgdata'], ','), 1);
        	$FILE_URL = 'tmp/'.$userid.".jpg";
        	writetofile($FILE_URL, base64_decode($data));
        	$FILE_NAME = $userid.".jpg";
        	$FILE_SIZE = filesize('tmp/'.$userid.".jpg");
        } else {
	    	if (!file_exists($_FILES['attachment']['tmp_name']))
	    	{
	    		$FILE_NAME = "none";
	    	} else {
	        	$FILE_URL = $_FILES['attachment']['tmp_name'];
	        	$FILE_NAME = safe_upload_name($_FILES['attachment']['name']);
	        	$FILE_SIZE = $_FILES['attachment']['size'];
	        	$FILE_TYPE = safe_upload_name($_FILES['attachment']['type']);
	        }
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
                    if (preg_match("/\.\\$currentext$/i", $FILE_NAME)) {
                        $is_ext_allowed = 1;
                        break;
                    } 
                } 
                
                if (!is_uploaded_file($FILE_URL) && !$_POST['imgdata']) $is_ext_allowed = 0;
                
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
            if($_POST['imgdata']) {
        	    rename($FILE_URL, $upload_tmpurl);
            } else {
            	move_uploaded_file($FILE_URL, $upload_tmpurl);
            }
            $size = getimagesize($upload_tmpurl);
//            if (($size[0] > $maxwidth || $size[1] > $maxheight) && eregi("\.(gif|jpg|jpeg|swf|bmp|png)$", $FILE_NAME)) {
//            	imageshow($upload_tmpurl, $maxwidth);
//        		$size[0] = $auto_width;
//        		$size[1] = $auto_height;
//            }
			if($uploadImgUsing) {
				cropSpecSize($upload_tmpurl, 970, 164, "upload/usrcover/".$userid.".jpg.large");
				cropSpecSize($upload_tmpurl, $maxwidth, $maxheight);
			} else {
				cropSquare($upload_tmpurl, $maxwidth, $maxheight);
			}
			$size[0] = $maxwidth;
			$size[1] = $maxheight;

            if ($size[0] > $maxwidth || $size[1] > $maxheight) {
                $check = 0;
                $reason = $ua[5];
            } else {
            	$path = $uploadImgUsing ? "upload/usrcover/" : "upload/usravatars/";
                $upload_aname = $path . $userid . ".jpg";
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
            if($uploadImgUsing) {
            	$nquery = "UPDATE {$database_up}user_add SET cover='$upload_aname' WHERE uid='$userid'";
             	$result = bmbdb_query($nquery);
           } else {
	            $icon = "%$upload_aname%$size[0]%$size[1]";
            	$nquery = "UPDATE {$database_up}userlist SET avarts='$icon' WHERE userid='$userid'";
            	$result = bmbdb_query($nquery);
            }
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
    global $username, $uploadImgUsing, $template, $uaUpload, $mmssms, $iblock, $block, $icount, $temfilename, $styleidcode, $language, $openstylereplace, $navbarshow, $use_honor, $max_avatars_upload_size, $admin_name, $sign_use_bmfcode, $bmfcode_sign, $show_form_lng, $ua, $gl, $po, $use_own_portait, $upload_avatars_type_available, $icon_upload_size, $openupusericon, $upload_type_icon, $bbs_title, $otherimages, $maxheight, $maxwidth, $opencutusericon;
    list($name, $pwd, $usericon, $email, $oicq, $regdate, $sign, $homepage, $area, $comment, $honor, $lastpost, $postamount, $pe, $none, $bym, $passask, $passanswer, $usertype, $money, $born, $group, $sex, $national) = get_user_info($username);
	$lang_zone = array("uaUpload"=>$uaUpload, "mmssms"=>$mmssms, "ua"=>$ua, "show_form_lng"=>$show_form_lng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	
	$currentMod[$uploadImgUsing ? 'uacover' :'ua'] = true;
	
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
function cropSquare($filename, $width = 150, $height = 150)
{
	$imgstream = readfromfile($filename);
	$im = imagecreatefromstring($imgstream);
	$x = imagesx($im);
	$y = imagesy($im);

	if ($x > $y)
	{
	    $sx = abs(($y-$x)/2);
	    $sy = 0;
	    $thumbw = $y; 
	    $thumbh = $y; 
	} else {
	    $sy = abs(($x-$y)/2);
	    $sx = 0;
		$thumbw = $x; 
		$thumbh = $x; 
	}
	if(function_exists("imagecreatetruecolor")) {
		$dim = imagecreatetruecolor($width, $height); 
	} else {
		$dim = imagecreate($width, $height);
	}

	imagecopyresampled($dim, $im, 0, 0, $sx, $sy, $height, $width, $thumbw, $thumbh);
	imagejpeg($dim, $filename, 90);
}
function cropSpecSize($filename, $width = 384, $height = 65, $newfilename = '')
{
	$imgstream = readfromfile($filename);
	$im = imagecreatefromstring($imgstream);
	$x = imagesx($im);
	$y = imagesy($im);
	
	if(function_exists("imagecreatetruecolor")) {
		$dim = imagecreatetruecolor($width, $height); 
	} else {
		$dim = imagecreate($width, $height);
	}
	imagecopyresampled($dim, $im, 0, 0, 0, 0, $width, $height, $x, $y);
	imagejpeg($dim, $newfilename ? $newfilename : $filename, 90);
}