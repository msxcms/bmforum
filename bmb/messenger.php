<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
define("BMF_MESSSYSTEM", 1);
require("datafile/config.php");
require("getskin.php");
include("include/template.php");
include("include/bmbcodes.php");
require("lang/$language/usercp.php");
require("lang/$language/global.php");
require("lang/$language/post.php");

$perpage = 20;

if ($job == "ajax") 
{
	$msg = intval($msg);
    $query = "SELECT prcontent,prother,blid,prread FROM {$database_up}primsg WHERE stid='$userid' AND id='$msg'";
    $result = bmbdb_query($query);
    $row = bmbdb_fetch_array($result);

    $uploadfile = str_replace("\n", "", $row['prother']);

    $userinfoget = get_user_info($row['blid'], "usrid");
    $usertype = $userinfoget['ugnum'];
    $u_t = getLevelGroup($usertype, $usergroupdata, 0, $userinfoget['postamount'], $userinfoget['point']);
    
    list(, , , , , , , , , , , , , , , , , , , , , , , , , ,$html_codeinfo , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney']) = $u_t;

    $bcode_post['table'] = $u_t[115];
    $qbgcolor = "article_color2";
    $content = bmbconvert($row['prcontent'], $bmfcode_post);
    if (!empty($uploadfile)) {
        $attaches = "<br /><a href='upload/$uploadfile'>$gl[441]$uploadfile</a>";
    } 

    if ($row['prread'] != 1) {
        $nquery = "UPDATE {$database_up}primsg SET prread = 1 WHERE stid='$userid' AND id='$msg'";
        $result = bmbdb_query($nquery);
        $nquery = "UPDATE {$database_up}userlist SET newmess=newmess-1 WHERE userid='$userid'";
        $result = bmbdb_query($nquery);
    } 
    
	eval(load_hook('int_messenger_ajax'));

    echo "{$content}{$attaches}";
    exit;
} else {
	include("header.php");
}

navi_bar("", $gl[72]);
if (!$login_status) {
    msg_box($gl[72], $gl[73]);
    include("footer.php");
} 
if (!$send_msg || $userpoint < $pri_allow_ww) {
	msg_box($gl[72], $gl[74]);
    include("footer.php");
} 

$user = $username;
$query = "SELECT COUNT(*) FROM {$database_up}primsg WHERE stid='$userid'";
$result = bmbdb_query($query);
$countdb = bmbdb_fetch_array($result);
$count = $countdb['COUNT(*)'];

if ($count > $short_msg_max) {
    $moremsg = $count - $short_msg_max;
    $query = "DELETE FROM {$database_up}primsg WHERE stid='$userid' ORDER BY `prtime` ASC LIMIT $moremsg";
    $result = bmbdb_query($query);
    $count = $short_msg_max;
    $query = "SELECT * FROM {$database_up}primsg WHERE stid='$userid' AND prread='0'";
    $result = bmbdb_query($query);
    $gotNewMessage = bmbdb_num_rows($result);
    $nquery = "UPDATE {$database_up}userlist SET newmess='$gotNewMessage' WHERE userid='$userid'";
    $result = bmbdb_query($nquery);
} 
$usage = floor($count / $short_msg_max * 100) . "%";
if ($usage == "0%") $categorycolor_pro = "status_progress_blank_pm";
else $categorycolor_pro = "status_progress_pm";

if ($job == "read" || $job == "receivebox") {
	$currentMod['receivebox'] = true;
} elseif ($job == "readsnd" || $job == "sendbox") {
	$currentMod['sendbox'] = true;
} elseif ($job == "write") {
	$currentMod['writepm'] = true;
} elseif ($job == "clear") {
	$currentMod['clearpm'] = true;
}

$lang_zone = array("print_form"=>$print_form, "gl"=>$gl, "mmssms"=>$mmssms, "smlng"=>$smlng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
require($template_name['usercp']);
eval(load_hook('int_messenger_base'));

if ($job == "read") {	
    $query = "SELECT * FROM {$database_up}primsg WHERE stid='$userid' AND id='$msg'";
    $result = bmbdb_query($query);
    $row = bmbdb_fetch_array($result);

    $uploadfile = str_replace("\n", "", $row['prother']);
    $ctime = getfulldate($row['prtime']);

    $userinfoget = get_user_info($row['blid'], "usrid");
    $usertype = $userinfoget['ugnum'];
    $u_t = getLevelGroup($usertype, $usergroupdata, 0, $userinfoget['postamount'], $userinfoget['point']);
    
    list(, , , , , , , , , , , , , , , , , , , , , , , , , ,$html_codeinfo , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney']) = $u_t;

    $bcode_post['table'] = $u_t[115];
    $qbgcolor = "article_color2";
    $content = bmbconvert($row['prcontent'], $bmfcode_post);
    if (empty($row[prtitle])) $row[prtitle] = "($gl[97])";
    if (!empty($uploadfile)) {
        $attaches = "<br /><a href='upload/$uploadfile'>$gl[441]$uploadfile</a>";
    } 

    if ($row['prread'] != 1) {
        $nquery = "UPDATE {$database_up}primsg SET prread = 1 WHERE stid='$userid' AND id='$msg'";
        $result = bmbdb_query($nquery);
        $nquery = "UPDATE {$database_up}userlist SET newmess=newmess-1 WHERE userid='$userid'";
        $result = bmbdb_query($nquery);
    } 
    eval(load_hook('int_messenger_read'));

    $template_name['mms'] = newtemplate("pm_read", $temfilename, $styleidcode, $lang_zone);
    require($template_name['mms']);
} 

if ($job == "readsnd") {
    $query = "SELECT * FROM {$database_up}primsg WHERE blid='$userid' AND id='$msg'";
    $result = bmbdb_query($query);
    $row = bmbdb_fetch_array($result);
    $uploadfile = str_replace("\n", "", $row['prother']);
    if (!empty($uploadfile)) {
        $attaches = "<br /><a href='upload/$uploadfile'>$gl[441]$uploadfile</a>";
    } 
    $uploadfile = str_replace("\n", "", $row['other']);
    $ctime = getfulldate($row['prtime']);
    $bmfcode_post['table'] = $usertype[115];
    $bcode_post = $bmfcode_post;
    $qbgcolor = "article_color2";
    $content = bmbconvert($row['prcontent'], $bmfcode_post);
    if (empty($row[prtitle])) $row[prtitle] = "($gl[97])";
    eval(load_hook('int_messenger_readsnd'));

	$template_name['mms'] = newtemplate("pm_readsnd", $temfilename, $styleidcode, $lang_zone);
    require($template_name['mms']);

} 
if ($job == "receivebox") {
	$template_name['mms'] = newtemplate("pm_receivebox", $temfilename, $styleidcode, $lang_zone);

    if ($count == 0) {
    	require($template_name['mms']);
        include("footer.php");
    } 
    
	$maxpage = ceil($count / $perpage);
	$page = (int)$_GET['page'];
	$page = ($page <= $maxpage && $page) ? $page : 1;
	$startline = ($page-1)*$perpage;
	$pagerLink = 'messenger.php?job=receivebox&page={page}';

	$query = "SELECT * FROM {$database_up}primsg WHERE stid='$userid' ORDER BY `prtime` DESC LIMIT {$startline},$perpage";
	$result = bmbdb_query($query);

	$bmf_pms = "";
    $counts = 0;
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $counts++;
        $title = $row['prtitle'];
        if (empty($title)) $title = "($gl[97])";
        $bmf_pms[]= array($row['id'], $row['belong'], $title, $row['prread'], getfulldate($row['prtime']));

    } 
    eval(load_hook('int_messenger_receivebox'));
	require($template_name['mms']);

} 
if ($job == "sendbox") {
	$template_name['mms'] = newtemplate("pm_sendbox", $temfilename, $styleidcode, $lang_zone);

	$query = "SELECT COUNT(*) FROM {$database_up}primsg WHERE blid='$userid' AND prkeepsnd='1'";
	$result = bmbdb_query($query);
	$countdb = bmbdb_fetch_array($result);
	$count = $countdb['COUNT(*)'];

    if ($count == 0) {
    	require($template_name['mms']);
        include("footer.php");
    } 

	$maxpage = ceil($count / $perpage);
	$page = (int)$_GET['page'];
	$page = ($page <= $maxpage && $page) ? $page : 1;
	$startline = ($page-1)*$perpage;
	$pagerLink = 'messenger.php?job=sendbox&page={page}';

    $query = "SELECT * FROM {$database_up}primsg WHERE blid='$userid' AND prkeepsnd='1' ORDER BY `prtime` DESC LIMIT {$startline},$perpage";
    $result = bmbdb_query($query);
	$bmf_pms = "";

    $counts = 0;
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $counts++;
        if (empty($row['prtitle'])) $row['prtitle'] = "($gl[97])";
        
        $bmf_pms[]= array($row['id'], $row['sendto'], $row['prtitle'], getfulldate($row['prtime']));
    } 
    eval(load_hook('int_messenger_sendbox'));

	require($template_name['mms']);

} 
if ($job == "write") {
    if (empty($step)) {
        	if ($fwid) {
	    	$tmp_check = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}primsg WHERE blid='$userid' AND id='$fwid'"));
	    	if ($tmp_check['prcontent']) {
	    		if (!$sendagain) $tmp_check['prcontent'] = "$gl[75]-" . $tmp_check['belong'] . ":" . $tmp_check['prcontent'];
	    		$neirong = $tmp_check['prcontent'];
	    	}
	    }
		eval(load_hook('int_messenger_write_step_0'));

    	print_form();
    }elseif ($_POST['step'] == 2) {
    	
    	$curr_len = utf8_strlen($content);

        if (($curr_len >= $max_post_length) || ($curr_len < $min_post_length) || ($flood_control && $username && !flood_limit($username, $flood_control))) {
            if ($curr_len >= $max_post_length || $curr_len < $min_post_length) {
            	$tips_error = $print_form[21].$max_post_length.$print_form[22].$curr_len.$print_form[23];
            } else {
            	$tips_error = $war[3];
            }
            $target = $pruser;
            $neirong = $content;
            $timu = $title;
            print_form();
            include("footer.php");
            exit;
        } 

      	$template_name['mms'] = newtemplate("pm_write", $temfilename, $styleidcode, $lang_zone);

      	
        $prruser = explode(";", $pruser);
        $ccount = count($prruser);
        for ($i = 0; $i < $ccount; $i++) {
            $ruser = $prruser[$i];
            
		    $lines = bmbdb_query_fetch("SELECT userid FROM {$database_up}userlist WHERE username='$ruser' LIMIT 0,1");
		    $ruserid = $lines['userid'];
        	
            if (empty($_POST['content']) || empty($_POST['pruser']) || $ccount > $send_msg_max || !$ruserid) {
				$error_error = $gl[104];
				require($template_name['mms']);
                include("footer.php");
                exit;
            } 
	     	if (!is_uploaded_file($_FILES['attachment']['tmp_name']))
	    	{
	    		$FILE_NAME = "";
	    	} else {
	            $FILE_URL = $_FILES['attachment']['tmp_name'];
	            $FILE_NAME = safe_upload_name($_FILES['attachment']['name']);
	            $FILE_SIZE = $_FILES['attachment']['size'];
	            $FILE_TYPE = safe_upload_name($_FILES['attachment']['type']);
	        }
            $check = 1;

            if ($allow_upload && $FILE_NAME && $FILE_NAME != "none") {
                $upload = 1;
                if ($login_status != 2 && $login_status != 0) {
                    $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
                } 
                if ($leftuploadnum == 0) {
                    $upload = 0;
                    $check = 0;
                } 
                if ($postamount < $max_upload_post && $usertype[22] != "1") {
                    $upload = 0;
                    $check = 0;
                } 
                $available_ext = explode(' ', $upload_type_available);
                $extcount = count($available_ext);
                $is_ext_allowed = 0;
                for ($i = 0; $i < $extcount; $i++) {
                    $currentext = $available_ext[$i];
                    if (preg_match("/\.\\$currentext$/i", $FILE_NAME)) {
                        $is_ext_allowed = 1;
                        break;
                    } 
                } 
                if (!is_uploaded_file($FILE_URL)) $is_ext_allowed = 0;

                if (!$is_ext_allowed) {
                    $upload = 0;
                    $check = 0;
                } 
				eval(load_hook('int_messenger_write_upload'));

				if ($is_ext_allowed != 1) @unlink($FILE_URL);
                
            } 
            if ($upload && $check) {
				if ($saveattbyym == 1) {
					if (!file_exists("upload/$monthdir")) @mkdir("upload/$monthdir", 0777);
				} 
                $upload_aname = "upload/{$monthdir}pm{$userid}_{$timestamp}.{$currentext}";
                $upload_bname .= "{$monthdir}pm{$userid}_{$timestamp}.{$currentext}";
                attach_upload($FILE_URL, $upload_aname, $FILE_SIZE);
            } elseif (!$check) {
            	$error_error = $gl[104];
            	require($template_name['mms']);
                include("footer.php");
                exit;
            } 

            $content = preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "", $content); 
            $content = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "", $content);
            $content = preg_replace("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", "", $content); 
            $content = preg_replace("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", "", $content); 
            $content = preg_replace("/\[post\](.+?)\[\/post\]/is", "", $content); 

            $content = safe_convert($content);
            $title = safe_convert($title);
            
            $title = strreply("RE:",$title);


            $uisbadu = "no";
            $banrusere = strtolower($ruser);

			$result = bmbdb_query_fetch("SELECT COUNT(`id`) FROM {$database_up}contacts where `type`=1 and `owner`='$userid' and `contacts`='$ruserid'");

		    $uisbadu = ($result['COUNT(`id`)'] > 0) ? "yes" : "";

			eval(load_hook('int_messenger_write_beforesql'));

            if ($uisbadu != "yes") {
                $nquery = "insert into {$database_up}primsg (belong,sendto,prtitle,prtime,prcontent,prread,prother,prtype,prkeepsnd,stid,blid) values ('$user','$ruser','$title','$timestamp','$content','0','$upload_bname','r','$ssnd','$ruserid','$userid')";
                $result = bmbdb_query($nquery);
                $nquery = "UPDATE {$database_up}userlist SET newmess=newmess+1 WHERE userid='$ruserid'";
                $result = bmbdb_query($nquery);
                $nquery = "UPDATE {$database_up}userlist SET lastpost='$timestamp' WHERE userid='$userid'";
                $result = bmbdb_query($nquery);
            } 
			eval(load_hook('int_messenger_write_suc'));
        } 
        $_SESSION['lastsendpm'] = $timestamp;


        $error_error = $gl[105];
        require($template_name['mms']);
        include("footer.php");
        exit;
    } 
} 
if ($job == "clear") {
	if ($log_hash != $verify) die("Access Denied");
	eval(load_hook('int_messenger_clear'));
    $query = "DELETE FROM {$database_up}primsg WHERE stid='$userid'";
    $result = bmbdb_query($query);
    $nquery = "UPDATE {$database_up}userlist SET newmess=0 WHERE userid='$userid'";
    $result = bmbdb_query($nquery);
    
    $info = $gl[106];
    
	$template_name['mms'] = newtemplate("pm_msg", $temfilename, $styleidcode, $lang_zone);
    require($template_name['mms']);

    include("footer.php");
    exit;
} 
if ($job == "delone") {
	eval(load_hook('int_messenger_delone'));
    if ($_POST['n'] == "more") {
        $count = count($delmore);
        $mode = "DELETE FROM {$database_up}primsg";
        $addquery = " stid='$userid' AND (";
        if ($deltype == snd) {
            $mode = "UPDATE {$database_up}primsg SET prkeepsnd = ''";
            $addquery = " blid='$userid' AND (";
        } 

        for ($a = 0; $a < $count; $a++) {
            if ($delmore[$a] != "") {
                $addquery .= " id='{$delmore[$a]}' OR ";
            } 
        } 
        $addquery .= " id='XXXXXXXXXXX' )";
        $query = "$mode WHERE $addquery";

        $result = bmbdb_query($query);
    } else {
    	if ($verify != $log_hash) die("Access Denied");
        $mode = "DELETE FROM {$database_up}primsg";
        $addquery = " stid='$userid' ";
        if ($deltype == snd) {
            $mode = "UPDATE {$database_up}primsg SET prkeepsnd = ''";
            $addquery = " blid='$userid' ";
        } 
        $addquery .= " AND id='$msg'";
        $query = "$mode WHERE $addquery";
        $result = bmbdb_query($query);
    } 
    $query = "SELECT * FROM {$database_up}primsg WHERE stid='$userid' AND prread='0'";
    $result = bmbdb_query($query);
    $gotNewMessage = bmbdb_num_rows($result);

    $nquery = "UPDATE {$database_up}userlist SET newmess='$gotNewMessage' WHERE userid='$userid'";
    $result = bmbdb_query($nquery);
    
    $info = $gl[108];
    
	$template_name['mms'] = newtemplate("pm_msg", $temfilename, $styleidcode, $lang_zone);
    require($template_name['mms']);

    include("footer.php");
    exit;
} 

include("footer.php");

function attach_upload($attach, $source, $attach_size)
{
    global $login_status, $username, $bmfopt, $database_up, $timestamp, $deltopic, $delreply, $userid, $id_unique, $uploadfiletoday;

    $nquery = "UPDATE {$database_up}userlist SET uploadfiletoday=uploadfiletoday+1,lastupload='$timestamp' WHERE userid='$userid'";
    $result = bmbdb_query($nquery);

    if (@move_uploaded_file($attach, $source)) {
        $attach_saved = true;
    } elseif (@copy($attach, $source)) {
		$attach_saved = true;
    } 

    if (!$attach_saved && is_readable($attach)) {
        @$fp = fopen($attach, "rb");
        @flock($fp, 2);
        @$attachedfile = fread($fp, $attach_size);
        @fclose($fp);

        @$fp = fopen($source, "wb");
        @flock($fp, 3);
        if (@fwrite($fp, $attachedfile)) {
            $attach_saved = true;
        } 
        @fclose($fp);
    } 
	eval(load_hook('int_messenger_attachupload'));
	if ($bmfopt['watermark'] && $attach_saved === true) {
		if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $source)) {
			include_once('include/markpic.php');
			$debug = makethumb($source, $source);
//			$debug = $watermark_err ? $watermark_err : $debug;
//			echo $debug;
		}
	}
	eval(load_hook('int_messenger_attachupload_done'));
	@unlink ($attach);
	@chmod ($source, 0777);
} 
function print_form()
{
    global $userid, $gl, $database_up, $target, $log_hash, $send_msg_max, $tips_error, $print_form, $max_daily_upload_size, $temfilename, $styleidcode, $lang_zone, $uploadfiletoday, $max_upload_size, $leftuploadnum, $max_upload_post, $allow_upload, $upload_num, $upload_type_available, $max_upload_num, $id_unique, $gl, $timu, $username, $neirong;
    $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
    if ($allow_upload) {
        $available_ext = explode(' ', $upload_type_available);
        $extcount = count($available_ext);
        $showtype = "<select><option>$print_form[12]</option><option>---------</option>";
        for ($i = 0; $i < $extcount; $i++) {
            $showtype .= "<option>$available_ext[$i]</option>";
        } 
        $uploadfileshow .= "<input size=\"76\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment\" /><br />";
        $showtype .= "</select>";
    } 
	$template_name['mms'] = newtemplate("pm_write", $temfilename, $styleidcode, $lang_zone);
	
	$neirong = str_replace("<br />", "\n", $neirong);

    
	$nquery = "SELECT * FROM {$database_up}contacts WHERE `type`=0 and `owner`='$userid'";
	$nresult = bmbdb_query($nquery);
	while (false !== ($line = bmbdb_fetch_array($nresult))) {
		$friend_l.= "<option value=\"{$line['conname']}\">{$line['conname']}</option>";
    } 
	eval(load_hook('int_messenger_print_form'));

	require($template_name['mms']);

} 
function strreply ($reprefix, $content, $length = 3 )
{
	eval(load_hook('int_messenger_strreply'));
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}
function flood_limit($name, $limit)
{
    global $timestamp, $uginfo, $userddata, $usertype;
	eval(load_hook('int_messenger_flood_limit'));

    if ($usertype[22] == "1") return 1;
    if ($usertype[21] == "1") return 1;
    if ($usertype[24] == "1") return 1;
    if ($timestamp - $userddata['lastpost'] >= $limit) return 1;
    return 0;


} 