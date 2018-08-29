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
include("datafile/reginfo.php");
require("include/common.inc.php");

$authinput = strtoupper($authinput);

if ($_COOKIE['frommember']) $tuijianren = $_COOKIE['frommember'];

if (file_exists("datafile/regipbans.php")) $term_bannedmembers = file("datafile/regipbans.php");
if (!empty($term_bannedmembers)) {
    $count = count($term_bannedmembers);
	eval(load_hook('int_register_banip'));
    for ($i = 0; $i < $count; $i++) {
        $bannedip = trim($term_bannedmembers[$i]);
        if (!$bannedip) continue;
        if (strpos($ip , $bannedip) === 0) {
        	error_page($reglang[45], $reglang[2], $reglang[0], $reglang[1]);
        } 
    } 
} 
if ($logonutnum == 6) {
	$cancel_guestfile = "reglog";
} elseif ($usertype[118] == 1 || $userpoint < $usertype[119]) {
	$action = "";
}

$add_title = " &gt; $reglang[2]";

$nowhours = gmdate("H", $datestime);
$ddwtail = explode("|", $recclose);
if ($reg_stop == 0 || ($nowhours < $ddwtail[0] || $nowhours >= $ddwtail[1])) {
	error_page($reglang[45], $reglang[2], $reglang[3], $reglang[4]);
} 

if ($action == "check") {
	eval(load_hook('int_register_checkname'));
    if (file_exists("datafile/bannames.php")) {
        include("datafile/bannames.php");
        if ($bannames && array_search_value($addusername, $bannames) == "banned") {
            echo "$reglang[5]";
            exit;
        } 
    } 
    if (check_user_exi($addusername) != 0) {
        echo "$reglang[6]";
    } elseif(!check_name_valid($addusername)) {
        echo "$reglang[8]";
    } else {
        echo "$reglang[7]";
    } 
    exit;
} 

if ($login_status == 1 && !$step) {
	error_page($reglang[45], $reglang[2], $bbs_title, $reglang[40]);
} 

if (file_exists('datafile/reg_custom.php')) {
	$reg_c = file("datafile/reg_custom.php"); 

	if (is_array($reg_c)) {
		foreach ($reg_c as $key => $value){
			$reg_sc[]=explode("|", $value);
		}
	}
} else $reg_sc = "";

    
$query = "SELECT * FROM {$database_up}usergroup WHERE regwith=1 LIMIT 0,1";
$result = bmbdb_query($query);
$line = bmbdb_fetch_array($result);
$usergroupreg = $line['id'];

if ($usergroupreg == 4) {
	include_once("datafile/cache/levels/level_fid_0.php");
	$level = getUserLevel(0, 0, $addusername, $usergroupreg);
	$usertype = explode("|", $levelgroupdata[0][$level_id]); 
} else {
	$usertype = explode("|", $usergroupdata[$usergroupreg]); 
}
eval(load_hook('int_register_loadusergroup'));

list(, , , , , , , $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], , , , , , $use_own_portait, $swf, , , , , , , , $html_codeinfo, , , , , , , , , , , , , $opencutusericon, $openupusericon, , , , $maxwidth, $maxheight) = $usertype;


if ($login_status == 0 && $_POST['step'] == 2) {
    // --------进行注册-----------
    $check = 1; 
    // --------check--------

    
    $homepage = safe_convert($homepage);
    $signature = safe_convert($signature);
    $fromwhere = safe_convert($_POST['fromwhere']);
    $addcomment = safe_convert($addcomment);
    $addusername = str_replace("　", "", $addusername);
    $addusername = trim($addusername);
    $oicqnumber = $qqnumber . "※" . $msnurl . "※" . $icqnumber;
    
    if (!check_name_valid($addusername)) {
        $reason = "$reglang[8]";
        $check = 0;
    } 
    if ($check && check_user_exi($addusername) != 0) {
        $reason = "$reglang[9]";
        $check = 0;
    }
    if ($check && empty($addusername)) {
        $reason = "$reglang[10]";
        $check = 0;
    } 
    $addusername = str_replace("\t", "", $addusername);
    $addusername = str_replace("\r", "", $addusername);
    $addusername = str_replace("\n", "", $addusername);
    if ($send_pass == 1) {
        $addpassword = rand(100000, 999999);
        $passask = "";
        $passanswer = "";
    } else {
        if ($check && utf8_strlen($addusername) < $min_regname_length) {
            $reason = "$reglang[12]";
            $check = 0;
        } 
        if ($check && utf8_strlen($addusername) > $max_regname_length) {
            $reason = "$reglang[11]";
            $check = 0;
        } 
        if ($check && utf8_strlen($addpassword) < 4) {
            $reason = "$reglang[13]";
            $check = 0;
        } 
        if ($check && $addpassword != $addpassword2) {
            $reason = "$reglang[14]";
            $check = 0;
        } 
        if ($check && empty($addpassword)) {
            $reason = "$reglang[15]";
            $check = 0;
        } 
        $addpassword = str_replace("\t", "", $addpassword);
        $addpassword = str_replace("\r", "", $addpassword);
        $addpassword = str_replace("\n", "", $addpassword);
        if ($check && (strrpos($addpassword, "|") !== false || strrpos($addpassword, "<") !== false || strrpos($addpassword, ">") !== false)) {
            $reason = "$reglang[16]";
            $check = 0;
        } 
        if ($check && (strrpos($passask, "|") !== false || strrpos($passask, "<") !== false || strrpos($passask, ">") !== false)) {
            $reason = "$reglang[17]";
            $check = 0;
        } 
        if ($check && (strrpos($passanswer, "|") !== false || strrpos($passanswer, "<") !== false || strrpos($passanswer, ">") !== false)) {
            $reason = "$reglang[18]";
            $check = 0;
        } 
    } 
    $addpasswordsend = $addpassword;
    if ($check && $reg_va && $_SESSION["checkauthnum"] != $authinput) {
        $reason = $gl[440];
        $check = 0;
    } 
    $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
    $_SESSION['checkauthnum'] = $authnum;
    if ($check && !preg_match("/^[0-9]{0,}$/", $qqnumber)) {
        $reason = "$reglang[19]";
        $check = 0;
    } 
    if ($check && !preg_match("/^[0-9]{0,}$/", $icqnumber)) {
        $reason = "$reglang[20]";
        $check = 0;
    } 
    if ($check && (strrpos($addborn, "|") !== false || strrpos($addborn, "<") !== false || strrpos($addborn, ">") !== false)) {
        $reason = "$reglang[21]";
        $check = 0;
    } 
    $bornchenck = explode("-", $addborn);
    if ($bornchenck[1] != "") $bornchenck[1] = @floor($bornchenck[1]);
    if ($bornchenck[2] != "") $bornchenck[2] = @floor($bornchenck[2]);
    $addborn = @implode("-", $bornchenck);
    if ($check && ($bornchenck[1] > "12" || $bornchenck[2] > "31")) {
        $reason = "$reglang[21]";
        $check = 0;
    } 
    if (empty($addemail)) {
        $reason = "$reglang[22]";
        $check = 0;
    } 
    if ($check && !preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,4}$/", $addemail)) {
        $reason = $step2_error[1];
        $check = 0;
    } 
    if ($check && check_mail($addemail) && $reg_oneemail) {
        $reason = $step2_error[19];
        $check = 0;
    } 
    
    if ($reg_invit == 1 || $invitecode) {
    	if ($check && !check_invite($invitecode)){
            $reason = $step2_error[20];
            $check = 0;
            $invitecode = "";
        }
    } 
    
    if (!empty($publicmail) && $publicmail == "yes") $publicmail = 1;
    else $publicmail = 0;
    if (empty($receivemail)) $receivemail = "text";
    elseif ($receivemail == "html") $receivemail = "html";
    elseif ($receivemail == "none") $receivemail = "none";
    else $receivemail = "text";
    if ($check && utf8_strlen($addcomment) > 100) {
        $reason = "$reglang[23]";
        $check = 0;
    } 
    if ($check && utf8_strlen($signature) > $max_sign_length) {
        $reason = "$reglang[24]";
        $check = 0;
    } 
    if (file_exists("datafile/bannames.php")) {
        include("datafile/bannames.php");
        if ($check && $bannames && array_search_value($addusername, $bannames) == "banned") {
            $reason = "$reglang[25]";
            $check = 0;
        } 
    } 
    // member custom information.
    
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

    if (!$homepage) $homepage = "";
    if (!$signature) $signature = "";
    if (!$fromwhere) $fromwhere = "";
    $addnational = safe_convert($addnational);
    $addsex = safe_convert($addsex);
    $addgroup = "";
    $newhonor = safe_convert($newhonor);
    $addusername = $addusername;
    $addpassword = $addpassword;
    $signature = $signature;
    $homepage = $homepage;
    $addcomment = $addcomment;
    $fromwhere = $fromwhere;
    $own_portait['a0'] = $own_portait['a0'];
    if ($check && strrpos($sysusericon, '|') !== false) {
        $reason = "$reglang[26]";
        $check = 0;
    } 
    if ($own_portait['a0']) {
        $own_portait['a0'] = safe_convert($own_portait['a0']);
        if ($check && (strrpos($own_portait['a0'], '%') !== false)) {
            $reason = "$reglang[26]";
            $check = 0;
        } 
    	
    	if ((!preg_match("/^[0-9]{2,3}$/", $own_portait['a1']) || !preg_match("/^[0-9]{2,3}$/", $own_portait['a2'])) && preg_match("/\.(gif|jpg|jpeg|swf|bmp|png)$/i", $own_portait['a0'])) {
    		imageshow($own_portait['a0'], 120);
    		$own_portait['a1'] = $auto_width;
    		$own_portait['a2'] = $auto_height;
    	}
    	
        if ($check && (!preg_match("/^[0-9]{2,3}$/", $own_portait['a2']) || $own_portait['a2'] > 120)) {
            $reason = "$reglang[27]";
            $check = 0;
        } 
        if ($check && (!preg_match("/^[0-9]{2,3}$/", $own_portait['a2']) || $own_portait['a2'] > 120)) {
            $reason = "$reglang[28]";
            $check = 0;
        } 

    } 
    
    eval(load_hook('int_register_check'));

    // --------------check complete---------
    if ($check == 1) {
        // -----防止恶意注册开始----//
        if ($reg_allowed) {
            if ($timestamp - $_COOKIE['regvisit_fr'] < $reg_allowed) {
				error_page($reglang[45], $reglang[2], $reglang[3], $reglang[29]);
            } else {
                setCookie('regvisit_fr', $timestamp, $cookietime, $cookie_p, $cookie_d);
            } 
        } 
        // -----防止恶意注册结束----//
        // --------进行注册--
        if (check_user_exi($tuijianren)) {
            $query = "UPDATE {$database_up}userlist SET money=money+$reg_r_money WHERE username='$tuijianren' LIMIT 1";
            $result = bmbdb_query($query);
        }
        $usericon = $sysusericon;
        if (!$usericon) $usericon = "no_portait.gif";
        if ($own_portait) $usericon .= "%$own_portait[a0]%$own_portait[a1]%$own_portait[a2]";
        $date_reg = $timestamp . "_" . $ip;
        $addpassword = md5($addpassword);
        $passanswer = md5($passanswer);

        $addusername = safe_convert($addusername);
        $usericon = safe_convert($usericon);
        $addemail = safe_convert($addemail);
        $oicqnumber = safe_convert($oicqnumber);
        $date_reg = safe_convert($date_reg);
        $publicmail = safe_convert($publicmail);
        $receivemail = safe_convert($receivemail);
        $passask = safe_convert($passask);
        $passanswer = safe_convert($passanswer);
        
        if ($send_pass == 2) $atid=rand(100000000,999999999); // Active Code
        
        if ($new_custom) {
          	$new_custom_sql = base64_encode(serialize($new_custom));
        }
	    eval(load_hook('int_register_beforesql'));

        if ($reg_invit && $invitecode) {
            $query = "UPDATE {$database_up}invite SET newmember='$addusername',accepted = 1 WHERE invitecode='$invitecode' LIMIT 1";
            $result = bmbdb_query($query);
            $usergroupreg = $bmfopt['invitesugroup'];
        } 
        
        $newsalt = geneSalt(); //debug
        
        $nquery = "insert into {$database_up}userlist (username,pwd,avarts,mailadd,qqmsnicq,regdate,signtext,homepage,fromwhere,desper,headtitle,lastpost,postamount,publicmail,mailtype,point,pwdask,pwdanswer,ugnum,money,birthday,team,sex,national,tlastvisit,lastlogin,activestatus,hisipa,foreuser,baoliu2,personug,baoliu1,parusrid,salt) values ('$addusername','$addpassword','$usericon','$addemail','$oicqnumber','$date_reg','$signature','$homepage','$fromwhere','$addcomment','',0,'0','$publicmail','$receivemail','0','$passask','$passanswer','$usergroupreg','$reg_money','$addborn','','$addsex','$addnational','$timestamp','$timestamp','$atid','$ip','','$new_custom_sql','','',0,'$newsalt')";
        $result = bmbdb_query($nquery);
        $ruserid = bmbdb_insert_id();

        $nquery = "UPDATE {$database_up}lastest SET lastreged='$addusername',regednum = regednum+1 WHERE pageid='index'";
        $result = bmbdb_query($nquery);

        if ($bornchenck[1]) writetofile("datafile/birthday/{$bornchenck[1]}_$bornchenck[2]", "$addusername|$bornchenck[2]|$bornchenck[0]|\n", "a");
        if ($send_pass != 1) {
        	$auth = makeauth($newsalt, $bmfopt['sitekey'], $addpassword);
            $_SESSION["bmfUsrAuth"] = $auth;
            $_SESSION["bmfUsrId"] = $ruserid;
            bmb_setcookie("bmfUsrId", $ruserid, 0);
            bmb_setcookie("bmfUsrAuth", $auth, 0, true);
            $lastlogin = $timestamp;
            getUserInfo();
        } 

        if ($regduan) {
            mtou($ruserid, $addusername);
        } 

        // send mail
        include_once("include/sendmail.inc.php");
        $sendmail = "";
        
        $title = $addusername . " $reglang[30] $bbs_title";
        
        if($send_pass != 2) {
            $ms = $addusername . ",$reglang[31]\n\n";
            $ms .= $bbs_title . " {$reglang[32]}\n";
            $ms .= "{$reglang[33]}" . $addusername;
            $ms .= "\n$reglang[34]" . $addpasswordsend;
            $ms .= "\n$reglang[35]\n";
            $ms .= "$reglang[36]\n";
            $ms .= "{$reglang[37]}$site_url\n";
            $ms .= "\n\n-----------------------------------\n";
            
        } else {
            
            $ms = str_replace("{user}", $addusername, $reg_active);
            $ms = str_replace("{usrid}", $ruserid, $ms);
            $ms = str_replace("{atid}", $atid, $ms);
        }
        
        $frommail = $admin_email;
        
        if($send_pass != 3) {
        	@BMMailer($addemail, $title, nl2br($ms), '', '', $bbs_title, $admin_email);
        }
        
        if ($send_pass == 2 ) 
        	$success_tip = $reglang[71];
        elseif ($send_pass == 1)
        	$success_tip = $reglang[72];
        else 
        	$success_tip = $reglang[39];
        
        // send sendmail
		eval(load_hook('int_register_suc'));

        if ($send_pass == 2 || $send_pass == 1) {
        	error_page($reglang[45], $reglang[2], $reglang[38], $success_tip, $reglang[38], 1);
        } else {
        	if ($_SESSION['oauth']) {
           		jump_page('oauth.php', $reglang[38], "$success_tip<br /><br /> <a href='oauth.php'>{$bindLang['toBind']}</a>", 3);
			} else {
	        	jump_page("index.php", $reglang[38], $success_tip, 3);
	        }
        }
    } 
    if ($check == 0) {
        include_once("header.php");
        print_bar();
        print_fail();
        $step = 0;
        $reg = "bym";
    } 
} 

if ($login_status == 0 && !$reg && !$step) {
    include("header.php");
    print_bar();
    
	$lang_zone = array("reglang"=>$reglang, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("register_start", $temfilename, $styleidcode, $lang_zone);
	eval(load_hook('int_register_start'));
	
	require($template_name);

    include("footer.php");
    exit;
} 

if ($login_status == 0 && !$step && $reg == 'bym') {
    // --------输出注册页面--------
    include_once("header.php");
    print_bar();
    print_form();
    include_once("footer.php");
    exit;
} 
function print_bar()
{
    global $reglang;
    navi_bar($reglang[45], $reglang[2], 0, 0, 0);
} 
function print_form()
{
    require("datafile/config.php");
    
    global $gd_auth, $reg_sc, $custom_var, $invitecode, $template, $iblock, $block, $icount, $styleidcode, $openstylereplace, $gl,
    $method, $temfilename, $reg_va, $send_pass, $show_form_lng, $reglang, $otherimages, 
    $use_own_portait, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign, $swf, $html_codeinfo, $opencutusericon, $openupusericon, $maxwidth, $maxheight;

	if ($custom_var) $custom = $custom_var;

    if (is_array($custom)) {
    	foreach ($custom as $key => $value) {
    		$custom[$key] = htmlspecialchars($value);
    	}
    }

    if ($reg_va) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION['checkauthnum'] = $authnum;
        $showautonum = $gd_auth;
    } 
    global $language, $cachedstyle;
    
	$lang_zone = array("gl"=>$gl, "show_form_lng"=>$show_form_lng, "reglang"=>$reglang, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("register_form", $temfilename, $styleidcode, $lang_zone);

    global $addborn, $addusername, $method, $addpassword, $tuijianren, $addemail, $addpassword2, $passask, $passanswer;

    if ($method) {
        global $addcomment, $qqnumber, $fromwhere, $msnurl, $signature, $own_portait, $homepage, $icqnumber;
    } 
	eval(load_hook('int_register_form'));

    require($template_name);
} 
function print_fail()
{
    global $reason, $reglang;
    msg_box($reglang[66], "$reglang[67]<br /><br />$reason<br /><br />$reglang[68]");
} 
function mtou($ruser, $addusername)
{
    global $id_unique, $username, $reglang, $database_up, $timestamp, $bbs_title, $short_msg_max;

    if (file_exists('datafile/welcome.php')) require("datafile/welcome.php");
    $msg[0] = "";
    $user = "$reglang[69]";
    $content = $welcome;
    $title = "$reglang[70]";
	eval(load_hook('int_register_mtou'));

    $nquery = "insert into {$database_up}primsg (belong,sendto,prtitle,prtime,prcontent,prread,prother,prtype,prkeepsnd,stid) values ('$user','$addusername','$title','$timestamp','$content','0','','r','','$ruser')";
    $result = bmbdb_query($nquery);
    $nquery = "UPDATE {$database_up}userlist SET newmess=newmess+1 WHERE userid='$ruser'";
    $result = bmbdb_query($nquery);
} 
function array_search_value($search, $array_in)
{
    foreach ($array_in as $value) {
        if (@strstr($search, $value) !== false)
            return "banned";
    } 
    return false;
} 
function check_mail($email)
{
    global $database_up;
	eval(load_hook('int_register_check_mail'));
    $query = "SELECT COUNT(*) FROM {$database_up}userlist WHERE mailadd='$email'  LIMIT 0,1";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $row = $fcount['COUNT(*)'];
    return $row;
} 
function check_invite($code)
{
    global $database_up;
	eval(load_hook('int_register_check_invite'));
    $query = "SELECT COUNT(*) FROM {$database_up}invite WHERE invitecode ='$code'  LIMIT 0,1";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $row = $fcount['COUNT(*)'];
    return $row;
} 
