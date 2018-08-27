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
include("include/common.inc.php");
require("lang/$language/usercp.php");

if (file_exists("datafile/admin.php")) {
    $adminlist = file("datafile/admin.php");
    $count = count($adminlist);
    for ($i = 0; $i < $count; $i++) {
        $detail = explode("|", trim($adminlist[$i]));
        $forum_admin[] = $detail[1];
    } 
} 

if (file_exists('datafile/reg_custom.php')) {
	$reg_c = file("datafile/reg_custom.php"); 

	if (is_array($reg_c)) {
		foreach ($reg_c as $key => $value){
			$reg_sc[]=explode("|", $value);
		}
	}
} else $reg_sc = "";


$add_title = " - $programname";
if (empty($job)) {
    $job = "modify";
} 
if ($job == "show") {
    if ($gvf == 0) {
    	error_page($tip[1], $info[6], $programname, $gl[277]);
    } 
    include("header.php");
    navi_bar($tip[1], $info[6]);
    if ($memberid && check_user_exi($memberid, 1)) {
    	show_member($memberid);
    } else {
	    if (!$target || !check_user_exi($target)) {
	        msg_box($programname,$error[3]);
	    } else {
	        show_member();
	    }
	}
    include("footer.php");
    exit;
} 
if ($job == "modify") {
    if ($login_status == 0) {
    	error_page($tip[1], $info[6], $programname, $error[1]);
    } 
    if (empty($step)) {
    	eval(load_hook('int_profile_step_1'));
        include("header.php");
        navi_bar($tip[1], $info[6]);
        show_form();
        include("footer.php");
        exit;
    } elseif ($_POST['step'] == 2) {
        // -----------check---------
        $check = 1;
        $timestamp_cookie = time();
        if (empty($_COOKIE[cookie_time_bmb])) $timestamp_cookie = 0;
        $line = get_user_info($username);
        @extract($line, EXTR_OVERWRITE);
        $name = $username;
        $oldmdf = md5($oldpassword);
        $change_paid = "";
        if (($newpassanswer != "") && !empty($oldpassword) && checkpass($name, $oldmdf)) {
            if (md5($oldpassanswer) != $pwdanswer) {
                $reason = "$step2_error[18]";
                $check = 0;
                $nocare = 1;
            } else {
                $newpassanswer = md5($newpassanswer);
                $nocare = 1;
            } 
        } elseif (($newpassanswer != "") && !empty($oldpassword) && !checkpass($name, $oldmdf)) {
            $reason = "$step2_error[17]";
            $check = 0;
            $nocare = 1;
        } else {
            $newpassanswer = $pwdanswer;
            $newpassask = $pwdask;
        } 
        if (!empty($addpassword) && !empty($addpassword_s) && $addpassword != $addpassword_s) {
			$reason = $reglang[14];
			$check = 0;
        } else {
	        if (!empty($addpassword) && !empty($oldpassword) && checkpass($name, $oldmdf)) {
	            $pwd = md5($addpassword);
	            $newsalt = geneSalt(); //debug
	            $change_paid = "`salt`='$newsalt',`parusrid`=0,";
	            
	            $auth = makeauth($newsalt, $bmfopt['sitekey'], $pwd);
	            @bmb_setcookie("bmfUsrAuth", $auth, false, true);
	            $_SESSION["bmfUsrAuth"] = $auth;
	        } elseif (!checkpass($name, $oldmdf) && (!empty($addpassword) && !empty($oldpassword))) {
	            $reason = "$step2_error[17]";
	            $check = 0;
	        } elseif ((!empty($addpassword) && empty($oldpassword)) || (empty($addpassword) && !empty($oldpassword) && $nocare != 1)) {
	            $reason = "$step2_error[17]";
	            $check = 0;
	        } 
	    }
        $email = $addemail;
        $oicq = $oicqnumber . "※" . $msnurl . "※" . $icqnumber;
        $sign = $signature;
        if (!empty($usericon)) $avarts = $usericon;
        if (!empty($addsex)) $sex = $addsex;
        if (!empty($addnational)) $national = $addnational;
        if ($addnational == dont) $national = "";
        $oldbornck = explode("-", $birthday);
        if (!empty($addborn)) $birthday = $addborn;
        $page = $newhomepage;

        $area = $_POST['fromwhere'];

        $comment = $addcomment;

        $group = safe_convert(badwords($addgroup));
        $newhonor = safe_convert(badwords($newhonor));
        
        if ($postamount < $use_honor && $usertype[22] != 1) $newhonor = $line['headtitle'];
        if ($postamount < $use_group && $usertype[22] != 1) $group = $line['team'];

        $pwd = str_replace("\t", "", $pwd);
        $pwd = str_replace("\r", "", $pwd);
        $pwd = str_replace("\n", "", $pwd);
        if (strrpos($pwd, "|") !== false || strrpos($pwd, "<") !== false || strrpos($pwd, ">") !== false) {
            $reason = $step2_error[0];
            $check = 0;
        } 
        if (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,4}$/", $email)) {
            $reason = $step2_error[1];
            $check = 0;
        } 
        if (!empty($publicmail) && $publicmail == "yes") $publicmail = 1;
        else $publicmail = 0;
        if (!preg_match("/^[0-9]{0,}$/", $oicqnumber)) {
            $reason = "$step2_error[2]";
            $check = 0;
        } 
        if (!preg_match("/^[0-9]{0,}$/", $icqnumber)) {
            $reason = "$step2_error[3]";
            $check = 0;
        } 
        if (utf8_strlen($comment) > 100) {
            $reason = $step2_error[4];
            $check = 0;
        } 
        if (utf8_strlen($sign) > $max_sign_length) {
            $reason = $step2_error[5];
            $check = 0;
        } 
        if (utf8_strlen($passask) > 30) {
            $reason = $step2_error[6];
            $check = 0;
        } 
        if (utf8_strlen($passanswer) > 30) {
            $reason = $step2_error[7];
            $check = 0;
        } 
        if (strrpos($passask, "|") !== false || strrpos($passask, "<") !== false || strrpos($passask, ">") !== false) {
            $reason = "$step2_error[8]";
            $check = 0;
        } 
        if (strrpos($passanswer, "|") !== false || strrpos($passanswer, "<") !== false || strrpos($passanswer, ">") !== false) {
            $reason = "$step2_error[9]";
            $check = 0;
        } 
        if (strrpos($addborn, "|") !== false || strrpos($addborn, "<") !== false || strrpos($addborn, ">") !== false) {
            $reason = "$step2_error[10]";
            $check = 0;
        } 
        
        // member custom information.
        
        $custom_info = unserialize(base64_decode($line['baoliu2']));
        
        $new_custom = $new_custom_sql = "";
        
        if (is_array($reg_sc)) {
        	foreach ($reg_sc as $key => $value) {

        		if ($custom_var["$value[0]"] == "" && $value[6] == 1) {
        			$reason = $value[1] . $step2_error[22];
        			$check = 0;
        		}

        		if ($value[3] != 3) {
        			if ($value[10] != "" && utf8_strlen($custom_var["$value[0]"]) > $value[10]) {
        				$reason = $value[1] . $step2_error[21] . $value[10];
        				$check = 0;
        			}
        		} else {
        			$count_total = @count(unserialize(base64_decode($value[4])));
        			if ($custom_var["$value[0]"] < 0  || $custom_var["$value[0]"] >= $count_total) {
        				$reason = $step2_error[23];
        				$check = 0;
        			}
        		}
				if ($value[7] == 1 && $custom_info["$value[0]"]) {
        			$custom_var["$value[0]"] = $custom_info["$value[0]"];
        		}
        		if ($check != 0 && $custom_var["$value[0]"] != "") {
        			$new_custom["$value[0]"] = stripslashes(safe_convert($custom_var["$value[0]"]));
      		  	}
        	}
        }

        $bornchenck = explode("-", $addborn);
        if ($bornchenck[1] != "") $bornchenck[1] = @floor($bornchenck[1]);
        if ($bornchenck[2] != "") $bornchenck[2] = @floor($bornchenck[2]);
        $addborn = @implode("-", $bornchenck);
        if ($bornchenck[1] > "12" || $bornchenck[2] > "31") {
            $reason = "$step2_error[10]";
            $check = 0;
        } 
        if ($addsex == "") $addsex = $sex;
        $addnational = safe_convert(badwords($addnational));
        $addsex = safe_convert($addsex);
        $national = safe_convert(badwords($national));
        $sex = safe_convert($sex);
        $page = safe_convert($page);
        $comment = safe_convert(badwords($comment));
        $sign = safe_convert(badwords($sign));
        $area = safe_convert(badwords($area));
        $skinselectname = safe_convert($skinselectname);

        $sign = preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "", $sign); //加密提示。 
        $sign = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "", $sign); //加密提示。 
        $sign = preg_replace("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", "", $sign); //加密提示。 
        $sign = preg_replace("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", "", $sign); //加密提示。 
        $sign = preg_replace("/\[post\](.+?)\[\/post\]/is", "", $sign); //隐藏提示。 


        if (empty($receivemail)) $receivemail = "text";
        elseif ($receivemail == "html") $receivemail = "html";
        elseif ($receivemail == "none") $receivemail = "none";
        else $receivemail = "text";
        $receive = $receivemail;
        if (!empty($publicemail)) $pe = 1;
        else $pe = 0;

        if ($own_portait[a0]) {
            $own_portait[a0] = safe_convert(badwords($own_portait[a0]));
            if (strrpos($own_portait[a0], '%') !== false) {
                $reason = "$step2_error[13]";
                $check = 0;
            } 
        	
        	if ((!preg_match("/^[0-9]{2,3}$/", $own_portait[a1]) || !preg_match("/^[0-9]{2,3}$/", $own_portait[a2])) && preg_match("/\.(gif|jpg|jpeg|swf|bmp|png)$/i", $own_portait[a0])) {
        		imageshow($own_portait[a0], $maxwidth);
        		$own_portait[a1] = $auto_width;
        		$own_portait[a2] = $auto_height;
        	}
        	
			if (!preg_match("/^[0-9]{2,3}$/", $own_portait[a1]) || $own_portait[a1] > $maxwidth) {
				$reason = "$step2_error[11]";
				$check = 0;
			} 
			if (!preg_match("/^[0-9]{2,3}$/", $own_portait[a2]) || $own_portait[a2] > $maxheight) {
				$reason = "$step2_error[12]";
				$check = 0;
			} 
        } 
        if (strrpos($sysusericon, '|') !== false) {
            $reason = "$step2_error[13]";
            $check = 0;
        } 
        
		eval(load_hook('int_profile_step_2_check'));


        if ($check == 0) {
            include("header.php");
            navi_bar($tip[1], $info[6]);
            msg_box($programname, "<br />{$step2_error[14]}$reason<br /><ul><li><a href=\"javascript:history.back(1)\">$step2_error[15]</a></li></ul>");
            $step = 0;
        } else {
            if ($fnew_skin == 1 && $skinselectname != "notchange!") {
                $bmblogonskin = $skinselectname;
                $_SESSION['bmblogonskin'] = $bmblogonskin;
                bmb_setcookie("bmbskin", $bmblogonskin);
            } 
            if ($usecutlangname != "notchange!") {
                bmb_setcookie("userlanguage", $usecutlangname);
                $_SESSION['userlanguage'] = $usecutlangname;
            } 
            if ($new_date_format != $_COOKIE['bmf_date_format'] && !($_COOKIE['bmf_date_format'] == "" && $new_date_format == $time_f)) {
            	bmb_setcookie("bmf_date_format", $new_date_format);
            }
            bmb_setcookie("customshowtime", $timeshowmode);
            bmb_setcookie("usertimezone", $s_timezone);
            bmb_setcookie("can_visual_post", $userchpirmode);
            if ($own_portait) $avarts = "%$own_portait[a0]%$own_portait[a1]%$own_portait[a2]";

            $icon = safe_convert(badwords($icon));
            $email = safe_convert(badwords($email));
            $oicq = safe_convert(badwords($oicq));
            $regdate = safe_convert($regdate);
            $honor = safe_convert($honor);
            $postamount = safe_convert($postamount);
            $receive = safe_convert($receive);
            $pe = safe_convert($pe);
            $bym = safe_convert($bym);
            $passask = safe_convert($passask);
            $passanswer = safe_convert($passanswer);
            
            if ($new_custom) {
            	$new_custom_sql = ",baoliu2='".base64_encode(serialize($new_custom))."'";
            }
            if ($oldbornck[1]) {
                $obornfile = @file("datafile/birthday/{$oldbornck[1]}_$oldbornck[2]");
                $oount = count($obornfile);
                $odnline = "";
                for($od = 0;$od < $oount;$od++) {
                    $detailod = explode("|", $obornfile[$od]);
                    if ($detailod[0] != $name) {
                        $odnline .= $obornfile[$od];
                    } 
                } 
                writetofile("datafile/birthday/{$oldbornck[1]}_$oldbornck[2]", $odnline);
            } 
            if ($bornchenck[1]) {
                $bornfile = @file("datafile/birthday/{$bornchenck[1]}_$bornchenck[2]");
                $jount = count($bornfile);
                $nline = "";
                $findout = "";
                $ntline = "$name|$bornchenck[2]|$bornchenck[0]|\n";
                for($jd = 0;$jd < $jount;$jd++) {
                    $detailjd = explode("|", $bornfile[$jd]);
                    if ($detailjd[0] == $name) {
                        $findout = 1;
                        $nline .= "$name|$bornchenck[2]|$bornchenck[0]|\n";
                    } else {
                        $nline .= $bornfile[$jd];
                    } 
                } 
                if ($findout == 1) {
                    writetofile("datafile/birthday/{$bornchenck[1]}_$bornchenck[2]", $nline);
                } else {
                    writetofile("datafile/birthday/{$bornchenck[1]}_$bornchenck[2]", $ntline . $nline);
                } 
            } 

			eval(load_hook('int_profile_step_2'));
            $nquery = "UPDATE {$database_up}userlist SET {$change_paid}pwdanswer='$newpassanswer',pwdask='$newpassask',national='$national',sex='$addsex',team='$group',birthday='$addborn',mailtype='$receive',publicmail='$pe',headtitle='$newhonor',desper='$comment',fromwhere ='$area',pwd ='$pwd',homepage ='$page',signtext ='$sign',qqmsnicq ='$oicq',mailadd ='$email',avarts='$avarts'{$new_custom_sql} WHERE userid='$userid'";
            $result = bmbdb_query($nquery);
            $name = strtolower($name);
			eval(load_hook('int_profile_suc'));
            error_page($tip[1], $info[6], $programname, "<br />$step2_suc[0]<br /><ul><li><a href=\"index.php\">$step2_suc[1]</a></li><li><a href=\"profile.php?job=testsign\">$step2_suc[2]</a></li></ul>");
        } 
    } 
    if (empty($step)) {
    	eval(load_hook('int_profile_step_1'));
        include_once("header.php");
        navi_bar($tip[1], $info[6], 0, 0, 0);
        show_form();
        include_once("footer.php");
        exit;
    } 
} 

if ($job == "testsign") {
	eval(load_hook('int_profile_testsign'));
    include("header.php");
    navi_bar($tip[1], $info[6]);
    if ($login_status == 0) {
        msg_box($programname, $error[1]);
    } else {
        $temp = get_user_info($username);
        $sign = $temp['signtext'];

        list(, , , , , , , , , $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], , , , , , , , , , , , , , , $html_codeinfo) = $usertype;
        $bcode_sign['table'] = $usertype[115];
        msg_box($programname, "<br />$step2_suc[4]<br /><hr width=340 size=1><table width=340 align=center><tr><td>" . bmbconvert($sign, $allowaaa, $type = "sign") . "</td></tr></table><br /><hr width=340 size=1><ul><li><a href='javascript:history.back(1)'>$step2_error[15]</a></li></ul>");
    } 
    echo $pgo[2];
    include("footer.php");
    exit;
} 
// ------------------------------------------------------------------------------------------
// +--------------------show the form to modify own information-----------------
function show_form()
{
    global $username, $mmssms, $time_f, $custom_var, $reg_sc, $usertype, $usergroupdata, $openupusericon, $openstylereplace, $navbarshow, $show_form_lng, $po, $use_honor, $admin_name, $sign_use_bmfcode, $bmfcode_sign,
    $use_own_portait, $cachedstyle, $styleidcode, $template, $iblock, $block, $icount, $language, $temfilename, $language, $time_2, $time_1,  $_COOKIE, $fnew_skin, $use_own_portait, $icon_upload_size, $openupusericon, $upload_type_icon, $use_group, $use_honor, $bbs_title, $otherimages, $maxheight, $maxwidth, $opencutusericon;
    
	$lang_zone = array("mmssms"=>$mmssms, "navbarshow"=>$navbarshow, "show_form_lng"=>$show_form_lng, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);

	$template_name = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
	$template_name_mpro = newtemplate("usercp_mpro", $temfilename, $styleidcode, $lang_zone);
	$template_name_foot = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
	
	$currentMod['profile'] = true;
	
	include($template_name);


    $line = get_user_info($username);
    @extract($line, EXTR_PREFIX_SAME, "user");
    
    $custom = unserialize(base64_decode($line['baoliu2']));
    
    if ($custom_var) $custom = $custom_var;
    
    if (is_array($custom)) {
    	foreach ($custom as $key => $value) {
    		$custom[$key] = htmlspecialchars(str_replace("<br />", "\n", $value));
    	}
    }
    
    if ($postamount < $use_honor && $usertype[22] != 1) $disabledhonor = "disabled='disabled'";
    if ($postamount < $use_group && $usertype[22] != 1) $disabledgroup = "disabled='disabled'";

    $bym = floor($point / 10);
    list($oldoicq, $msn, $icq) = explode('※', $qqmsnicq);
    $usericon = explode('%', $avarts);
    $sign = str_replace("<br />", "\r\n", $signtext);
    if ($publicmail) $pe = "checked='checked'";
    // /////////////////////////////////////////////
    $style = "";
    
    $genorselect[$sex] = "selected='selected'";
    $nownational = nationalget($line['national'], 1);


    if ($sign_use_bmfcode) {
        $bmfcode_info .= ' ' . $show_form_lng[100];
        $bmfcode_info .= '<br />&nbsp;[img] &nbsp; -';
        if ($bmfcode_sign['pic']) $bmfcode_info .= " $show_form_lng[100]";
        else $bmfcode_info .= " $show_form_lng[101]";
        $bmfcode_info .= '<br />&nbsp;[flash] -';
        if ($bmfcode_sign['flash']) $bmfcode_info .= " $show_form_lng[100]";
        else $bmfcode_info .= " $show_form_lng[101]";
        $bmfcode_info .= '<br />&nbsp;[size]&nbsp; -';
        if ($bmfcode_sign['fontsize']) $bmfcode_info .= " $show_form_lng[100]";
        else $bmfcode_info .= " $show_form_lng[101]";
    } else $bmfcode_info .= ' ' . $show_form_lng[101];
    
    $sysusericon = explode("%", $avarts);


    if ($fnew_skin == 1) {
		$usecutskin = "";
		$selectskin[$_COOKIE['bmbskin']] = "selected='selected'";
	
        $dh = file("datafile/stylelist.php");
        $cdh = count($dh);
        for($cid = 0;$cid < $cdh;$cid++) {
            $cdhtail = explode("|", $dh[$cid]);
            $usecutskin .= "<option value=\"$cdhtail[1]\" {$selectskin[$cdhtail[1]]}>$cdhtail[2]</option>";
        } 
    } 
    
    if ($_COOKIE["usertimezone"] != "") {
        $time_1 = $_COOKIE["usertimezone"];
    } 
    if ($_COOKIE["customshowtime"] != "") {
        $time_2 = $_COOKIE["customshowtime"];
    } 
    
    $time_f = $_COOKIE["bmf_date_format"] ? $_COOKIE["bmf_date_format"] : $time_f;
 
    if ($time_2) $time_2_a = "checked='checked'";
    else $time_2_b = "checked='checked'";
    
    if ($_COOKIE['can_visual_post'] == "cancel") $uv_b = "checked='checked'";
    else $uv_a = "checked='checked'";
    
    $checktimezone[$time_1] = "selected='selected'";
	$usecutlang = "";
    $langlist = @file("datafile/langlist.php");
    $count = count($langlist);
    for($i = 0;$i < $count;$i++) {
    	$selectlang[$_COOKIE['userlanguage']] = "selected='selected'";
    	
        $xlangshow = explode("|", $langlist[$i]);
        $usecutlang .= "<option value=\"$xlangshow[1]\" {$selectlang[$xlangshow[1]]}>$xlangshow[3]</option>";
    } 



    if ($sysusericon[0] == "") $sysusericon[0] = "no_portait.gif";
    $desper = str_replace("<br />", "\n", $desper);
	eval(load_hook('int_profile_form_output'));
    require($template_name_mpro);
    require($template_name_foot);
} 
// ------------------------------------------------------------------------------------------
// +--------------------show another member's information-----------------
function show_member($view_by = 0)
{
    global $target, $bm_skin, $database_up, $userAdd, $html_codeinfo, $reg_c, $gl, $level_score_mode, $level_score_php, $bbs_title, $styleidcode, $template, $iblock, $block, $icount, $cachedstyle, $language, $id_unique, $usergroupdata, $openstylereplace, $temfilename, $timestamp, $show_form_lng, $smlng, $supmotitle, $online_limit, $bbs_money, $temfilename, $login_status, $otherimages, $admintitle;

    $line = ($view_by ? get_user_info($view_by, "usrid") : get_user_info($target));
    @extract($line, EXTR_PREFIX_SAME, "user");
    
	$count_rc = count($reg_c);
	
	for($i = 0;$i < $count_rc; $i++){
		$detail = explode("|", $reg_c[$i]);
		$reg_ssc["$detail[0]"] = $detail[1];
		$reg_type["$detail[0]"] = $detail[3];
		$reg_hide["$detail[0]"] = $detail[9];
		$reg_select["$detail[0]"] = $detail[3] == 3 ? unserialize(base64_decode($detail[4])) : "";
	}
    
    $custom = unserialize(base64_decode($line['baoliu2']));
    
	if (is_array($custom)) {
		$i = 0;
		foreach ($custom as $key => $value){
			if ($reg_hide["$key"] != 1) {
				if ($reg_ssc["$key"]) {
					$i++; $resets++;
					$custom_here[$i][$resets]['name'] = $reg_ssc["$key"];
					$custom_here[$i][$resets]['value'] = $reg_type["$key"] == 3 ? $reg_select["$key"]["$value"] : $value;
					$made = 0;
					
					if ($i/2 == floor($i/2)) {
						$custom_made[] = array_merge((array)$custom_here[$i-1], (array)$custom_here[$i]);
						$made = 1;
						$resets = 0;
					}
				}
			}
		}
		if ($made == 0) {
			$custom_made[] = array_merge((array)$custom_here[$i]);
		}
	}
    
    if (check_online($line['username'])) $check_on = "<img alt='$smlng[2]' src='$otherimages\system\online3.gif' />";
    else $check_on = "<img alt='$smlng[3]' src='$otherimages\system\offline.gif' />";

    $tlastvisit = get_date_chi($tlastvisit) . get_time($tlastvisit);
    $lastlogin = get_date_chi($lastlogin) . get_time($lastlogin);
    $last_post_time = ($lastpost) ? (get_date_chi($lastpost) . get_time($lastpost)) : "-";
    if (empty($deltopic)) $deltopic = "0";
    if (empty($delreply)) $delreply = "0";
    
    //$usericon = get_user_portait($avarts, false, $mailadd);
    if ($homepage && strpos($homepage, "://") === false) $homepage = "http://$homepage";
    list($regdate, $regip) = explode("_", $regdate);
    $regdate = get_date_chi($regdate) . get_time($regdate);
    $fileuname = urlencode($username);
    if ($publicmail) {
        $email = "<a href='misc.php?p=sendmail&amp;target=$fileuname'>$mailadd</a>";
        $sendaction = "<a href='misc.php?p=sendmail&amp;target=$fileuname'><img src='$otherimages/system/email.gif' border='0' alt=''/>$smlng[5]</a>";
    } else {
        $email = "$smlng[4]";
        $sendaction = "";
    } 
    if ($birthday) {
        list($y, $m, $d) = explode("-", $birthday);
    } 
    if ($m != "" && $d != "") {
        $starname = astrology($m, $d). "&nbsp;";
    } else {
    	$starname = "$smlng[7]";
    }
    $bym = floor($point / 10);
    $u_t = getLevelGroup($ugnum, $usergroupdata, 0, $postamount, $line['point']);
    list($groupname, , , , , , , , , $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], , , , , , , , , , , , , , , $html_codeinfo) = $u_t;
    $bcode_sign['table'] = $u_t[115];
    $level = getUserLevel($postamount, $point, $username, $line['ugnum']);
    $level = $level . "&nbsp;(" . $groupname . ")";
    $sign = bmbconvert($signtext, "", "sign", $bcode_sign);
    list($oicq, $msn, $icq) = explode('※', $qqmsnicq);
    if (empty($oicq)) {
        $oicq = "$smlng[6]";
        $qqshows = "$otherimages/system/oicq.gif";
    } else {
        $qqshows = "http://wpa.qq.com/pa?p=1:$oicq:4";
    } 
    if (empty($msn)) $msn = "$smlng[6]";
    if (empty($icq)) $icq = "$smlng[6]";
    if (empty($headtitle)) $headtitle = "$smlng[6]";
    if (empty($homepage)) {
        $homepage = "$smlng[6]";
    } else {
        $homepage = "<a href='$homepage' target=_blank>$homepage</a>";
    } 
    if (empty($team)) $team = "$smlng[6]";
    if (empty($fromwhere)) $fromwhere = "$smlng[7]";
    if ($national) {
        $nationalname = nationalget($national);
    } 
    if ($y != 0 && $m != "" && $d != "") {
        $shengxiao = robertsx($y, $m, $d). "&nbsp;";
    } else {
        $shengxiao = "$smlng[7]";
    } 
    if ($sex == "Male") {
        $seximages = "<img src='$otherimages/system/mal.gif' width='20' alt='$show_form_lng[0]' /> $show_form_lng[0]";
    } elseif ($sex == "Female") {
        $seximages = "<img src='$otherimages/system/fem.gif' width='20' alt='$show_form_lng[1]' /> $show_form_lng[1]";
    } else {
        $seximages = "$show_form_lng[2]";
    } 
    
    $userAdd = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}user_add WHERE uid='{$line['userid']}'"));

	$lang_zone = array("show_form_lng"=>$show_form_lng, "smlng"=>$smlng, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("usercp_usershows", $temfilename, $styleidcode, $lang_zone);
    
    if ($level_score_mode == 1) $level_score = $bym;
    elseif ($level_score_mode == 2) {
    	$score = $bym;
    	$amount = $postamount;
        eval($level_score_php);
        $level_score = $amount;
    } else {
    	$level_score = $postamount;
    }
    $url_bbs_title = urlencode($bbs_title);
    eval(load_hook('int_profile_detail_output'));
    require("newtem/$temfilename/global.php");
    echo $pgo[2];
	require($template_name);
} 
