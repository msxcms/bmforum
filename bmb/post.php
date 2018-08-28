<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
define("INPOST", true);
include_once("datafile/config.php");
include_once("getskin.php");
include_once("include/bmbcodes.php");
include_once("include/post_global.php");
include_once("lang/$language/post.php");
include_once("newtem/$temfilename/post_global.php");
$qbgcolor = "article_color2";
if ($login_status != 2 && $login_status != 0) {
    $lastuploadtime = gmdate("zY", $clastupload + $bbsdetime * 60 * 60);
    $lastuploadtime_a = gmdate("zY", $timestamp + $bbsdetime * 60 * 60);
    if ($lastuploadtime != $lastuploadtime_a) {
        $uploadfiletoday = 0;
        $nquery = "UPDATE {$database_up}userlist SET uploadfiletoday=0,lastupload='$timestamp' WHERE userid='$userid'";
        $result = bmbdb_query($nquery);
    } 
} 
$pbqq100 = $qadtvar = $uploaded_num = 0;
if (!$forumid || !$forum_type || $forum_type == "category") exit;

check_forum_permission(1);

if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e" && $job <> "login" && $_COOKIE['b' . $forumid . 'mb'] <> $forum_pwd) {
    echo "<meta http-equiv=\"Refresh\" content=\"0; URL={$prefix_file}$forumid\">";
    exit;
} 
if ($forum_style <> "") {
    if (file_exists("datafile/style/" . basename($forum_style))) include("datafile/style/" . basename($forum_style));
} 

$bcode_post = $bmfcode_post;
$bcode_post['table'] = $usertype[115];

if ($usericon) $usericon = safe_convert($usericon);

if ($form_name == "__bmbForm") $convert_visual = 1;
eval(load_hook('int_post_int'));

if (!$action) $action = "new";
if ($action == "new" || $action == "reply" || $action == "quote") {
    // 灌水预防-----------
    if ($flood_control && $username && !flood_limit($username, $flood_control)) {
		eval(load_hook('int_post_flood_ban'));
    	if ($ajax_reply == 1) ajax_reply_error($war[3]);
    	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[3]<br />", $war[2], 1);

        $step = 0;
    } 
} 
if ($_COOKIE['can_visual_post'] == 'cancel') $can_visual_post = 0;
// ============================
if ($usertype[120] == 1 && $allow_upload && $remote_upload) {
	remote_upload($remote_upload, $remote_referer);
}
// ============================

$filetopn = ($aviewpost == "openit") ? "article.php" : "topic.php";
$pagelast = ($aviewpost == "openit") ? "" : "&amp;page=last";

$alert_error_ext = $gl[527];
if ($action == "new") {
    if (($canpost != "1" || $userpoint < $post_allow_ww) && $login_status == "1") {
    	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[5]<br />", $war[4]);
    } 
    if ($type == "vote" && ($canpoll != "1" || $userpoint < $poll_allow_ww) && $login_status == "1") {
		error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[5]<br />", $war[4]);
	} 
    if ($step == 2 && $preview != $print_form[76] && $mupload != $print_form[18]) {
        // ----Check-------
        $check = check_data($type);
		eval(load_hook('int_post_new_int'));

        for($ia = 0;$ia < $max_upload_num;$ia++) {
        	if (!is_uploaded_file($_FILES['attachment']['tmp_name'][$ia]))
        	{
        		$upload[$ia] = 0;
        		continue;
        	}
        	$FILE_URL[$ia] = $_FILES['attachment']['tmp_name'][$ia];
            $FILE_NAME[$ia] = safe_upload_name($_FILES['attachment']['name'][$ia]);
            $FILE_SIZE[$ia] = $_FILES['attachment']['size'][$ia];
            $FILE_TYPE[$ia] = safe_upload_name($_FILES['attachment']['type'][$ia]);
            $FILE_ERROR[$ia] = $_FILES['attachment']['error'][$ia];
            if ($allow_upload && $FILE_NAME[$ia] && $FILE_NAME[$ia] != "none") $upload[$ia] = 1;
            else $upload[$ia] = 0;

            if ($login_status != 2 && $login_status != 0) {
                $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
            } 

            if ($leftuploadnum == 0) $upload[$ia] = 0;
            if ($nopostpic == 1 && $usertype[22] != "1" && $usertype[24] != "1" && $usertype[21] != "1" && $usertype[12] != "1") {
                $check	= 0;
                $status	= $errora[4444];
            } 
            if ($check && $upload[$ia]) {
                if ($postamount < $max_upload_post && $usertype[22] != "1") {
                    $check	= 0;
                    $status	= $others[3];
                } 
                if ($FILE_ERROR[$ia] != 0) {
                	$check	= 0;
                	$status	= $print_form[122] . $FILE_ERROR[$ia];
                	$upload[$ia] = 0;
                }
                // -----User has uploaded some file-----
                if ($FILE_SIZE[$ia] > $max_upload_size && $usertype[22] != "1") {
                    $check = 0;
                    $status = $others[4];
                } else {
                	$is_ext_allowed = check_upload_ext ($FILE_NAME[$ia], $upload_type_available, $FILE_URL[$ia], $remote_is_file[$FILE_NAME[$ia]]);
                    if (!$is_ext_allowed) {
                        $check	= 0;
                        $status = $others[5];
                    } 
                } 
            } 
			eval(load_hook('int_post_new_upload'));
            if ($check == 0) @unlink($FILE_URL[$ia]);
        } 
        
        if ($usertype[110] == 1 && !str_replace(" ", "", $tags)) {
            $check	= 0;
            $status	= $print_form[tags];
        }

        if ($check) {
            if (!$usericon) $usericon = "";
            if (!isset($articledes)) $articledes = "";
            else $articledes = addslashes($articledes); 
            // -----Check the input------
            for($axd = 0;$axd < $max_upload_num;$axd++) {
                $attachdes[$axd] = addslashes(safe_convert(badwords($attachdes[$axd]), 0, $convert_visual));
            } 

            $articlecontent = addslashes(badwords($articlecontent));
            $articlecontent = safe_convert($articlecontent, 0, $convert_visual);
            if ($autourl == "yes") $articlecontent = autourl($articlecontent);
            if ($actioneot == "yes") $articlecontent = dongzuo($articlecontent, $username);
            $articletitle = addslashes(safe_convert(badwords($articletitle), 1, $convert_visual));
            $articletitle = str_replace(",", "，", $articletitle);
            $articledes = safe_convert($articledes, 0, $convert_visual);
            if ($inreplytopic == 'yes') $articlecontent = "[post]" . $articlecontent . "[/post]";
            if ($inhiddentopic == 'yes') $articlecontent = "[hide=" . $postweiwang . "]" . $articlecontent . "[/hide]";
            if ($assell == 'yes') $articlecontent = "[pay=" . $sellmoney . "]" . $articlecontent . "[/pay]";
            if ($asgift == 'yes') $articlecontent = "[gift=" . $giftmoney . "]" . $articlecontent . "[/gift]";
            if ($asbeg == 'yes') $articlecontent = "[beg]" . $articlecontent . "[/beg]";
            
            if ($type == "vote") {
		        $selections = str_replace("|", "│", safe_convert(badwords($selections)));
		        $sellist = explode("<br />", $selections);
		        $maturedate = explode("-", $maturedate);
		        $maturedate = mktime (0, 0, 0, $maturedate[1], $maturedate[2], $maturedate[0]);
		        if ($votetype == "m") {
		            $votetype = "m_" . $vtm . "_" . $viewafter . "_" . $maturetrue . "_" . $maturedate . "_" . $maturepatrue . "_" . $maturepa;
		        } else {
		            $votetype = "s_1_" . $viewafter . "_" . $maturetrue . "_" . $maturedate . "_" . $maturepatrue . "_" . $maturepa;
		        } 
		    }
            
            $newrand = getCode(7);
            
            for($ia = 0;$ia < $max_upload_num;$ia++) {
                if ($upload[$ia]) {
                    if ($saveattbyym == 1) {
                        if (!file_exists("upload/$monthdir")) @mkdir("upload/$monthdir", 0777);
                    } 
                    $upload_aname = "upload/{$monthdir}forum${forumid}_{$newrand}_{$ia}_{$timestamp}.{$currentext}";
                    $upload_bname .= str_replace("×", "[BMDESBõ]", "{$monthdir}forum${forumid}_{$newrand}_{$ia}_{$timestamp}.{$currentext}◎". str_replace("◎", "[BMDESAõ]", $attachdes[$ia]) ."◎0◎". str_replace("◎", "[BMDESAõ]", $FILE_NAME[$ia]) ."◎$FILE_SIZE[$ia]") ."◎{$bmfopt['gzip_attachment']}×";
                    attach_upload($FILE_URL[$ia], $upload_aname, $FILE_SIZE[$ia]);
                    $uploaded_num++;
                } 
            }
            
            if ($uploaded_num > 0) {
    			bmbdb_query("UPDATE {$database_up}userlist SET uploadfiletoday=uploadfiletoday+$uploaded_num,lastupload='$timestamp' WHERE userid='$userid'");
            }

            $lm = $articletitle . "," . $username . "," . $timestamp;

            $axsount = count($usergnshow);
            if ($allow_forb_ub) {
                for($axs = 0;$axs < $axsount;$axs++) $writetlist .= $usergnshow[$axs] . "§";
            } 
            $options = $usesignature . "_" . $openbmbcode . "_" . $openbmbemot . "___" . $hidepicat . "_" . $writetlist . "_" . $openhtmlcode . "_" . $autourl;
            $other1 = "";
            $other2 = "";
            $other3 = $upload_bname;
            $other4 = "";
            $other5 = 0;
            
            $addalimit = "";
            
            if ($usertype[126] == 1 || $block_posts == 1) {
                $qadtvar = "1";
                $addalimit = "trashcount=trashcount+1,";
                $addlinestipis = ($block_posts == 1) ? "<br />$errc[12]" : "<br />$others[40]";
            } 
            $userid = $userid ? $userid : 0;
			eval(load_hook('int_post_new_beforesql'));
            $nquery = "insert into {$database_up}posts (articletitle,username,usrid,ip,usericon,options,other1,other2,other3,other4,other5,addin,articlecontent,timestamp,forumid,changtime,sellbuyer) values ('$articletitle','$username','$userid','$ip','$usericon','$options','$other1','$other2','$other3','$other4','$other5','','$articlecontent','$timestamp','$forumid','$timestamp','')";
            $result = bmbdb_query($nquery);
            
            $newlineno = bmbdb_insert_id();

            $nquery = "UPDATE {$database_up}posts SET tid='$newlineno' WHERE id='$newlineno' LIMIT 1";
            $result = bmbdb_query($nquery);

            if ($set_a_tags && $tags) {
                $tags_ex = tags_set(badwords($tags), $newlineno);
            } else {
            	$tags_ex = array("");
            }
            
            $ttagname = implode(" ", array_slice( $tags_ex, 0, $max_tags_num));
            
            $posttype = ($type == "vote") ? 1 : 0;
            
            $nquery = "insert into {$database_up}threads (id,tid,toptype,ttrash,ordertrash,lastreply,topic,forumid,hits,replys,changetime,itsre,type,islock,title,newdesc,author,authorid,content,time,ip,face,options,other1,other2,other3,other4,other5,statdata,addinfo,alldata,ttagname,replyer) values ('$newlineno','$newlineno',0,'$qadtvar','$qadtvar','$lm','','$forumid','0','0','$timestamp','0','$posttype','0','$articletitle','$articledes','$username','$userid','$articlecontent','$timestamp','$ip','$usericon','$options','$other1','$other2','$other3','$other4','$other5','','',0,'$ttagname','')";
            $result = bmbdb_query($nquery);

            if ($type == "vote") {
		        $selcount = min(count($sellist), $max_poll);
		        for ($k = 0; $k < $selcount; $k++) if ($sellist[$k] === "") unset($sellist[$k]);
		        else $sellist[$k] = "$sellist[$k],0";
		        $list = implode("|", $sellist);

		        $nquery = "insert into {$database_up}polls (id,options,polluser,setting,forumid) values ('$newlineno','$list','|','$votetype','$forumid')";
		        $result = bmbdb_query($nquery);
		        
		        $others[7] = $poll_tip[6];
		        $others[8] = $poll_tip[7];
            }

            send_suc();
			eval(load_hook('int_post_new_sucpost'));
			
            if ($aviewpost == "openit" || !$bmfopt['rewrite'])
            {
            	$topic_jump = "{$filetopn}?filename=$newlineno";
            } else {
            	$topic_jump = "topic_{$newlineno}";
            }
            
            if ($postjumpurl == 0) $jumpun = "{$prefix_file}$forumid";
            else {
				$jumpun = $topic_jump;
            }
            jump_page("$jumpun", $others[7],
                "<strong>$others[8]</strong>$addlinestipis<br />
			<br />$others[9] <a href='{$prefix_file}$forumid'>$others[10]</a> | <a href='{$topic_jump}'>$others[11]</a> | <a href='index.php'>$others[12]</a>", 3);
        } else {
        	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$status<br />", $error[0], 1);

            $step = 0;
        } 
    } 
    if (!$step || $preview == $print_form[76] || $mupload == $print_form[18]) {
        if ($can_visual_post && $visual != "off") $articlecontent = str_replace("\n", "<br />", $articlecontent);
        elseif (!$can_visual_post || $visual == "off") $articlecontent = str_replace("<br />", "\n", $articlecontent);
        
        $articlecontent = badwords($articlecontent);

        include_once("header.php");
        
        navi_bar($navi_bar_des, $navi_bar_l2, ($type == "vote" ? $poll_tip[5] : $others[1]), 0, 0);
        $status = $others[2];
        print_form();
        include_once("footer.php");
        exit;
    } 
} 

if ($action == "reply" || $action == "quote") {
    if (($canreply != "1" || $userpoint < $re_allow_ww) && $login_status == "1") {
    	if ($ajax_reply == 1) ajax_reply_error($war[6]);
    	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[6]<br />", $war[4]);
    } 

    $xresult = bmbdb_query("SELECT * FROM {$database_up}threads WHERE id='$filename'");
    $uresult = bmbdb_fetch_array($xresult);
    
    if ($usertype[116] > 0 || $usertype[117] > 0) {
    	$check_t = 1;

    	if ($usertype[116] > 0) {
    		$last_array = bmbdb_fetch_array(bmbdb_query("SELECT timestamp FROM {$database_up}posts WHERE tid='$filename' ORDER BY `id` DESC LIMIT 1"));
    		$days_count = ($timestamp-$last_array['timestamp'])/86400;
    		if ($days_count >= $usertype[116]) $check_t = 0;
    	}

    	if ($usertype[117] > 0) {
    		$days_count = ($timestamp-$uresult['time'])/86400;
    		if ($days_count >= $usertype[117]) $check_t = 0;
    	}
    	
    	if ($check_t == 0) {
    		if ($ajax_reply == 1) ajax_reply_error($war[15]);
    		error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[15]<br />", $war[4]);
    	}
    } 
    
    if (!$uresult['tid']) exit;
    if ($uresult['islock'] == 1 || $uresult['islock'] == 3) {
    	if ($ajax_reply == 1) ajax_reply_error($war[9]);
    	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$war[8]<br />", $war[9]);

    } 
    if ($quickquote == "yes") {
        $oldstep = $step;
        $step = "";
        $action = "quote";
        $tid = $article;
        if (empty($tid)) $tid = $filename;
    } else {
    	if ($ajax_reply == 1) {
    		$_SESSION['notificationUserId'] = array();
    	}
    }

    eval(load_hook('int_post_reply_prepare'));

    if (!$step || $preview == $print_form[76] || $mupload == $print_form[18]) {
        $articlecontent = str_replace("\n", "<br />", $articlecontent);
        //elseif (!$can_visual_post || $visual == "off") $articlecontent = str_replace("<br />", "\n", $articlecontent);
        
        $articlecontent = badwords($articlecontent);

        $status = $war[10];
        if ($action == "quote") {
            eval(load_hook('int_post_reply_quote'));

            $status = $war[11];
            if ($mutilequote == "yes") {
            	$quotein = "";
            	for($q = 0; $q < count($quotes); $q++) {
            		$quotein .= $quotein ? ",'".$quotes[$q]."'" : "'".$quotes[$q]."'";
            	}
            	$quotein = $quotein ? $quotein : "'".$filename."'";
                $aquery = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}posts p LEFT JOIN {$database_up}userlist u ON u.userid=p.usrid WHERE id in($quotein)";
                $result = bmbdb_query($aquery);
                $_SESSION['notificationUserId'] = array();
                while (false !== ($row = bmbdb_fetch_array($result))) {
                    $f_content[$row['id']] = $row;
                    $_SESSION['notificationUserId'][] = $row['usrid'];
                } 
                $counsa = count($quotes);
                for($acs = 0;$acs < $counsa;$acs++) {
                    $tmpnum = $quotes[$acs];
                    if ($f_content[$quotes[$acs]]) {
                        $aresult = $f_content[$quotes[$acs]];
                        $oruser = $aresult['username'];
                        $ortitle = $aresult['articletitle'];
                        $ortime = get_date($aresult['timestamp']) . " " . get_time($aresult['timestamp']);
                        $orcontent = $aresult['articlecontent'];
                        
                        $orcontent = ban_user_post($orcontent, $oruser, $aresult);

                    	$orcontent = str_replace("\n", "<br />", strip_tags($orcontent));
                    	$orcontent = str_replace("&amp;", "&", $orcontent);
                        $orcontent = preg_replace("/\[img\](.*)\[\/img\]/is", "", $orcontent);
                        $contentall = explode("<br />", $orcontent);

	                    if(count($contentall) > 4 || utf8_strlen(strip_tags($orcontent)) > 140) {
	                    	$orcontent = substrfor(strip_tags($orcontent), 0, 140, false).' .......';
	                    }
                        $articlecontent .= "[quote][i][b]$others[13][u]{$oruser}[/u]$others[14] [i]{$ortime}[/i] $others[15][/b][/i]<br />{$orcontent}[/quote]<br />";
                        //elseif (!$can_visual_post || $visual == "off")  $articlecontent .= "[quote][i][b]$others[13][u]{$oruser}[/u]$others[14] [i]{$ortime}[/i] $others[15][/b][/i]\n{$orcontent}[/quote]\n";
                        //if ($ortitle) $articletitle = stripslashes(strreply("RE:", "RE:" . $ortitle));
                    } 
                } 
            } else {
                $aquery = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}posts p LEFT JOIN {$database_up}userlist u ON u.userid=p.usrid WHERE id='$tid'";
                $result = bmbdb_query($aquery);
                $aresult = bmbdb_fetch_array($result);
                if ($aresult) {
                	$_SESSION['notificationUserId'] = array($aresult['usrid']);
                	
                    $oruser = $aresult['username'];
                    $ortitle = $aresult['articletitle'];
                    $ortime = get_date($aresult['timestamp']) . " " . get_time($aresult['timestamp']);
                    $orcontent = $aresult['articlecontent'];
                    
                    $orcontent = ban_user_post($orcontent, $oruser, $aresult);
                    
					$orcontent = preg_replace("/\[img\](.*)\[\/img\]/is", "", strip_tags($orcontent));
                    
                	if ($ajax_reply == 1) {
						$orcontent = restore_post_htmt($orcontent);
                	}
                	$orcontent = str_replace("\n", "<br />", $orcontent);
                	$contentall = explode("<br />", $orcontent);

                    if(count($contentall) > 4 || utf8_strlen(strip_tags($orcontent)) > 140) {
                    	$orcontent = substrfor(strip_tags($orcontent), 0, 140, false).' .......';
                    }
                    
                    $articlecontent = "[quote][i][b]$others[13][u]{$oruser}[/u]$others[14] [i]{$ortime}[/i] $others[15][/b][/i]<br />{$orcontent}[/quote]<br />";
                    //elseif (!$can_visual_post || $visual == "off")  $articlecontent = "[quote][i][b]$others[13][u]{$oruser}[/u]$others[14] [i]{$ortime}[/i] $others[15][/b][/i]\n{$orcontent}[/quote]\n\n";

                    //if ($ortitle) $articletitle = stripslashes(strreply("RE:", "RE:" . $ortitle));
                } 
            } 
            if ($quickquote == "yes") {
                $step = $oldstep;
                $articlecontent = str_replace("<br />", "\r", $articlecontent) . $_POST['articlecontent'];
                $articletitle = $_POST['articletitle'];
            } 
        } else {
        	$_SESSION['notificationUserId'] = array();
        }
    } 
    if ($step == 2 && $preview != $print_form[76] && $mupload != $print_form[18]) {
        // ----Check-------
        eval(load_hook('int_post_reply_step_2'));
        $check = check_data();
        for($ia = 0;$ia < $max_upload_num;$ia++) {
        	if (!is_uploaded_file($_FILES['attachment']['tmp_name'][$ia]))
        	{
        		$upload[$ia] = 0;
        		continue;
        	}
            $FILE_URL[$ia] = $_FILES['attachment']['tmp_name'][$ia];
            $FILE_NAME[$ia] = safe_upload_name($_FILES['attachment']['name'][$ia]);
            $FILE_SIZE[$ia] = $_FILES['attachment']['size'][$ia];
            $FILE_TYPE[$ia] = safe_upload_name($_FILES['attachment']['type'][$ia]);
            $FILE_ERROR[$ia] = $_FILES['attachment']['error'][$ia];
            if ($allow_upload && $FILE_NAME[$ia] && $FILE_NAME[$ia] != "none") $upload[$ia] = 1;
            else $upload[$ia] = 0;
            if ($login_status != 2 && $login_status != 0) {
                $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
            } 
            if ($leftuploadnum == 0) $upload[$ia] = 0;
            if ($nopostpic == 1 && $usertype[22] != "1" && $usertype[24] != "1" && $usertype[21] != "1" && $usertype[12] != "1") {
                $check = 0;
                $status = $errora[4444];
            } 
            if ($check && $upload[$ia]) {
                if ($postamount < $max_upload_post && $usertype[22] != "1") {
                    $check = 0;
                    $status = $others[3];
                } 
                if ($FILE_ERROR[$ia] != 0) {
                	$check	= 0;
                	$status	= $print_form[122] . $FILE_ERROR[$ia];
                	$upload[$ia] = 0;
                }
                // -----User has uploaded some file-----
                if ($FILE_SIZE[$ia] > $max_upload_size && $usertype[22] != "1") {
                    $check = 0;
                    $status = $others[4];
                } else {
					$is_ext_allowed = check_upload_ext ($FILE_NAME[$ia], $upload_type_available, $FILE_URL[$ia], $remote_is_file[$FILE_NAME[$ia]]);
                    if (!$is_ext_allowed) {
                        $check = 0;
                        $status = $others[5];
                    } 
                } 
            } 
			eval(load_hook('int_post_reply_upload'));
            if ($check == 0) unlink($FILE_URL[$ia]);
        } 
        if ($check) {
            for($axd = 0;$axd < $max_upload_num;$axd++) {
                $attachdes[$axd] = addslashes(safe_convert(badwords($attachdes[$axd]), 0, $convert_visual));
            } 
            if (!$usericon) $usericon = "";
            if (!isset($articledes)) $articledes = "";
            else $articledes = addslashes(badwords($articledes)); 
            // -----Check the input------
            $articlecontent = addslashes(badwords($articlecontent));
            $articletitle = addslashes(badwords($articletitle));
            $articletitle = str_replace(",", "，", $articletitle);
            $articletitle = safe_convert($articletitle, 1, $convert_visual);
            $articlecontent = safe_convert($articlecontent, 0, $convert_visual);
            if ($autourl == "yes") $articlecontent = autourl($articlecontent);
            $articledes = safe_convert(badwords($articledes), 0, $convert_visual);
            if ($actioneot == "yes") $articlecontent = dongzuo($articlecontent, $username);

            if ($inreplytopic == 'yes') $articlecontent = "[post]" . $articlecontent . "[/post]";
            if ($assell == 'yes') $articlecontent = "[pay=" . $sellmoney . "]" . $articlecontent . "[/pay]";
            if ($inhiddentopic == 'yes') $articlecontent = "[hide=" . $postweiwang . "]" . $articlecontent . "[/hide]"; 
            if ($asgift == 'yes') $articlecontent = "[gift=" . $giftmoney . "]" . $articlecontent . "[/gift]";
            if ($asbeg == 'yes') $articlecontent = "[beg]" . $articlecontent . "[/beg]";

            // -----Do some preparation---
            
            $newrand = getCode(7);
            
            for($ia = 0;$ia < $max_upload_num;$ia++) {
                if ($upload[$ia]) {
                    if ($saveattbyym == 1) {
                        if (!file_exists("upload/$monthdir")) @mkdir("upload/$monthdir", 0777);
                    } 
                    $upload_aname = "upload/{$monthdir}forum${forumid}_{$newrand}_{$ia}_{$timestamp}.{$currentext}";
                    $upload_bname .= str_replace("×", "[BMDESBõ]", "{$monthdir}forum${forumid}_{$newrand}_{$ia}_{$timestamp}.{$currentext}◎". str_replace("◎", "[BMDESAõ]", $attachdes[$ia]) ."◎0◎". str_replace("◎", "[BMDESAõ]", $FILE_NAME[$ia]) ."◎$FILE_SIZE[$ia]") ."◎{$bmfopt['gzip_attachment']}×";
                    attach_upload($FILE_URL[$ia], $upload_aname, $FILE_SIZE[$ia]);
                    $uploaded_num++;
                } 
            }
            
            if ($uploaded_num > 0) {
    			bmbdb_query("UPDATE {$database_up}userlist SET uploadfiletoday=uploadfiletoday+$uploaded_num,lastupload='$timestamp' WHERE userid='$userid'");
            }

            $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $uresult['title']));

            $lm = ($articletitle ? $articletitle : $articletitle_reply). "," . $username . "," . $timestamp;
            
            $axsount = count($usergnshow);
            if ($allow_forb_ub) {
                for($axs = 0;$axs < $axsount;$axs++) $writetlist .= $usergnshow[$axs] . "§";
            } 
            $options = $usesignature . "_" . $openbmbcode . "_" . $openbmbemot . "___" . $hidepicat . "_" . $writetlist . "_" . $openhtmlcode . "_" . $autourl;
            $other1 = "";
            $other2 = "";
            $other3 = $upload_bname;
            $other4 = "";
            $other5 = 0;
            

            $query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
            $result = bmbdb_query($query);
            $line = bmbdb_fetch_array($result);
            
            if (!strstr($line['replyer'], $userid ."|")) $adduserline = "$userid|" . $line['replyer'];
                else $adduserline = $line['replyer'];
            
            if ($usertype[127] == 1 || $block_posts == 1) {
                $qadtvar = 1;
                $addlinestipis = ($block_posts == 1) ? "<br />$errc[12]" : "<br />$others[40]";
            } 
            $userid = $userid ? $userid : 0;
			eval(load_hook('int_post_reply_before_sql'));
            if ($noheldtop != 1) $changadd = ", changetime = '$timestamp'";
            $nquery = "UPDATE {$database_up}threads SET replyer='$adduserline',replys = replys+1 , lastreply = '$lm' $changadd WHERE tid = '$filename'";
            $result = bmbdb_query($nquery);
            
            $nquery = "insert into {$database_up}posts (tid,articletitle,username,usrid,ip,usericon,options,other1,other2,other3,other4,other5,addin,articlecontent,timestamp,forumid,changtime,sellbuyer,posttrash) values ('$filename','$articletitle','$username','$userid','$ip','$usericon','$options','$other1','$other2','$other3','$other4','$other5','','$articlecontent','$timestamp','$forumid','$timestamp','','$qadtvar')";
            $result = bmbdb_query($nquery); 
            if ($ajax_reply == 1) $lastid = bmbdb_insert_id();
            
            // ------OK,send e-mail to the latest user--------
            $subscribemail = ($send_mail == 1) ? ",2,3" : "";
            
            $aquery = "SELECT f.owner,f.subscribe,t.author,t.tid,t.title,u.mailadd FROM {$database_up}favorites f LEFT JOIN {$database_up}userlist u ON f.owner=u.userid LEFT JOIN {$database_up}threads t ON f.tid=t.tid WHERE f.tid = '$filename' AND f.subscribe in(1{$subscribemail})";
            $result = bmbdb_query($aquery);
            while (false !== ($row = bmbdb_fetch_array($result))) {

                $reply_page_no = floor(($uresult['replys']+1) / $read_perpage) + 1;
                
                if (!$lastid) $lastid = bmbdb_insert_id();
                
                if (($row['subscribe'] == 1 || $row['subscribe'] == 3) && !@in_array($row['owner'], $pmedlist) && $row['owner'] != $userid) {
                	$pmedlist[] = $row['owner'];
                    mtou($row['owner'], $filename, $row['author'], $row['title']);
                } 
                if ($send_mail == 1 && ($row['subscribe'] == 2 || $row['subscribe'] == 3) && !@in_array($row['owner'], $mailedlist) && $row['owner'] != $userid) {
                	$mailedlist[] = $row['owner'];
                    $old_title = $row['title'];
                    $send_address .= $row['mailadd'] . ",";
                    $textmessage = "$gl[524]\n  $others[19] {$bbs_title} $others[20]\n    {$others[21]}$old_title\n    $others[22]\n    {$script_pos}{$filetopn}?filename=$filename&amp;page=$reply_page_no#p$lastid\n\n___________________________________\n$others[23] {$script_pos}";
                    $senditmail = 1;
                } 
            } 
            if ($senditmail == 1) {
                include_once("include/sendmail.inc.php");
                BMMailer($send_address, $others[24], nl2br($textmessage), '', '', $bbs_title, $admin_email);
            } 
            
            //发送通知
            if($uresult['authorid'] != $userid) {
            	insertNotification($uresult['authorid'], $uresult['tid'], 'replyTopic', array('replier' => $username, 'title' => $uresult['title'], 'filename' => $uresult['tid']));
            }
        	if($_SESSION['notificationUserId']) {
        		foreach($_SESSION['notificationUserId'] as $nUIKey=>$nUIValue) {
        			if($nUIValue == $uresult['authorid']) {
        				unset($_SESSION['notificationUserId'][$nUIKey]);
        			}
        		}
        		if($_SESSION['notificationUserId']) {
        			insertNotification($_SESSION['notificationUserId'], $uresult['tid'], 'quotePost', array('replier' => $username, 'title' => $uresult['title'], 'filename' => $uresult['tid']));
        			unset($_SESSION['notificationUserId']);
        		}
        	}
            
            if (empty($articletitle)) $articletitle = $articletitle_reply;


            send_suc();
            
            if ($ajax_reply == 1) {
            	if ($qadtvar == 1) ajax_reply_error((($block_posts == 1) ? $errc[12] : $others[40]));
            	else {
            		echo $lastid;
            		exit;
            	}
            }
            
            // display options of jumping
        	if ($aviewpost == "openit" || !$bmfopt['rewrite'])
        	{
        		$topic_jump = "{$filetopn}?filename=$filename{$pagelast}#postend";
        	} else {
        		$topic_jump = "topic_{$filename}_last#postend";
        	}
            if ($postjumpurl == 0) $jumpun = "{$prefix_file}$forumid";
            else {
				$jumpun = $topic_jump;
            }
			eval(load_hook('int_post_reply_suc'));

            jump_page("$jumpun", $others[25],
                "<strong>$others[26]</strong>{$addlinestipis}<br /><br />
			$others[9] <a href='{$prefix_file}$forumid'>$others[10]</a> | <a href='{$topic_jump}'>$others[11]</a> | <a href='index.php'>$others[12]</a>", 3);
        } else {
        	if ($ajax_reply == 1) ajax_reply_error($status);
        	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$status<br />", $others[27], 1);

            $step = 0;
        } 
    } 
    if (!$step || $preview == $print_form[76] || $mupload == $print_form[18]) {
        include_once("header.php");
        navi_bar($navi_bar_des, $navi_bar_l2, $others[16], 0, 0);
        print_form();
        print_old($file_content);
        include_once("footer.php");
        exit;
    } 
} 

if ($action == "modify") {
    $query = "SELECT p.*,m.ttagname,m.newdesc FROM {$database_up}posts p LEFT JOIN {$database_up}threads m ON m.tid=p.tid or m.tid=p.id  WHERE p.id='$article' LIMIT 0,1";
    $result = bmbdb_query($query);
    $row = bmbdb_fetch_array($result);
    @extract($row, EXTR_PREFIX_SAME, "rw");
    $oldarticletitle = $articletitle;
    $olddesc = $row['newdesc'];
    $author = $rw_username;
    $tid = $rw_tid;
    $other3 = $row['other3'];
    $oldarticlecontent = $articlecontent;
    $p = $row['options'];
    $forumid = $row['forumid'];
    get_forum_info("");
    if (!$id) exit;
    $oldtags = $row['ttagname'];
    // list(,,,,$bym,$pfuser,$oldupload,$oldedit,$oldsellmoney)=explode("|",$articlelist[$article]);
    $newoldupload = $other3;
    // ------Check if the user got the right to modify--------
    $check_user = 0;
    $timeleft_edit = $timestamp - ($usertype[107] * 3600);
    if ($login_status == 1 && (($usertype[107] == "" && $row['usrid'] == $userid) || $usertype[22] == "1" || $usertype[21] == "1" || ($forum_admin && in_array($username, $forum_admin)))) $check_user = 1;
    if ($login_status == 1 && $usertype[107] >= 0 && $usertype[107] != "" && $timeleft_edit <= $row['timestamp'] && $row['usrid'] == $userid && $usertype[22] != "1" && $usertype[21] != "1") $check_user = 1;
    if ($login_status == 1 && $row['usrid'] != $userid && $edit_true != "1") $check_user = 0;
    if (!$is_rec && $row['posttrash'] == 1) $check_user = 0;
    if ($login_status == 1 && $check_user == 0) {
        $xfourmrow = $sxfourmrow;
        for($i = 0;$i < $forumscount;$i++) {
            if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
            if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
        } 
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
        } 
    } 
	eval(load_hook('int_post_modify_start'));
    if ($check_user == 0) {
    	if ($timeleft_edit > $row['timestamp'] && $usertype[107] != "") $status = $others[41]. $usertype[107];
        else $status = $others[28];
        
        error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$status<br />", $others[29]);

    } 
    if ($step == 2 && $preview != $print_form[76] && $mupload != $print_form[18]) {
        if (!$usericon) $usericon = $usericon; 
        // ----Check-------
        $check = check_data();
        if (strpos($newoldupload, "×")) {
            $attachshow = explode("×", $newoldupload);
            $cc = count($attachshow)-1;
        } else {
            $attachshow[0] = $newoldupload;
            $cc = 1;
        } 
        $ccc = $max_upload_num - $cc;
		eval(load_hook('int_post_modify_upload_org'));

        for($ia = 0;$ia < $ccc;$ia++) {
        	if (!is_uploaded_file($_FILES['attachment']['tmp_name'][$ia]))
        	{
        		$upload[$ia] = 0;
        		continue;
        	}
            $FILE_URL[$ia] = $_FILES['attachment']['tmp_name'][$ia];
            $FILE_NAME[$ia] = safe_upload_name($_FILES['attachment']['name'][$ia]);
            $FILE_SIZE[$ia] = $_FILES['attachment']['size'][$ia];
            $FILE_TYPE[$ia] = safe_upload_name($_FILES['attachment']['type'][$ia]);
            $FILE_ERROR[$ia] = $_FILES['attachment']['error'][$ia];
            if ($allow_upload && $FILE_NAME[$ia] && $FILE_NAME[$ia] != "none") $upload[$ia] = 1;
            else $upload[$ia] = 0;
            if ($login_status != 2 && $login_status != 0) {
                $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
            } 
            if ($leftuploadnum == 0) $upload[$ia] = 0;
            if ($nopostpic == 1 && $usertype[22] != "1" && $usertype[24] != "1" && $usertype[21] != "1" && $usertype[12] != "1") {
                $check = 0;
                $status = $errora[4444];
            } 

            if ($check && $upload[$ia]) {
                if ($postamount < $max_upload_post && $usertype[22] != "1") {
                    $check = 0;
                    $status = $others[3];
                } 
                if ($FILE_ERROR[$ia] != 0) {
                	$check	= 0;
                	$status	= $print_form[122] . $FILE_ERROR[$ia];
                	$upload[$ia] = 0;
                }
                // -----User has uploaded some file-----
                if ($FILE_SIZE[$ia] > $max_upload_size && $usertype[22] != "1") {
                    $check = 0;
                    $status = $others[4];
                } else {
                	$is_ext_allowed = check_upload_ext ($FILE_NAME[$ia], $upload_type_available, $FILE_URL[$ia], $remote_is_file[$FILE_NAME[$ia]]);
                    if (!$is_ext_allowed) {
                        $check = 0;
                        $status = $others[5];
                    } 
                } 
            } 
			eval(load_hook('int_post_modify_upload'));
            if ($check == 0) unlink($FILE_URL[$ia]);
        } 
        
        if ($usertype[110] == 1 && !str_replace(" ", "", $tags) && $article == $filename) {
            $check = 0;
            $status = $print_form[tags];
        }
            
        if ($check) {
            // -----安全检查------
            for($axd = 0;$axd < $max_upload_num;$axd++) {
                $attachdes[$axd] = addslashes(safe_convert(badwords($attachdes[$axd]), 0, $convert_visual));
            } 

            $articlecontent = addslashes(badwords($articlecontent));
            $articletitle = addslashes(badwords($articletitle));
            $articletitle = str_replace(",", "，", $articletitle);
            $articletitle = safe_convert($articletitle, 1, $convert_visual);
            $articlecontent = safe_convert($articlecontent, 0, $convert_visual);
            if ($autourl == "yes") $articlecontent = autourl($articlecontent);
            $articledes = safe_convert(badwords($articledes), 0, $convert_visual);
            if ($actioneot == "yes") $articlecontent = dongzuo($articlecontent, $username);

            $attachcshow = explode("×", $newoldupload);
            for($ia = 0;$ia < $cc;$ia++) {
                $ishow = "a" . $ia;
                if ($delactupload[$ishow] == "checkbox") {
                    $showdes = explode("◎", $attachshow[$ia]);
                    unlink("upload/$showdes[0]");
                } else {
                    if (!empty($attachcshow[$ia])) {
                        $attachdess = explode("◎", $attachcshow[$ia]);
                        $upload_bname .= $attachdess[0] . "◎" . $attachdess[1] . "◎" . $attachdess[2] . "◎" . $attachdess[3] . "◎" . $attachdess[4] . "◎" . $attachdess[5] . "×";
                    } 
                } 
            } 
            
            $newrand = getCode(7);

            for($ia = 0;$ia < $ccc;$ia++) {
                if ($upload[$ia]) {
                    if ($saveattbyym == 1) {
                        if (!file_exists("upload/$monthdir")) @mkdir("upload/$monthdir", 0777);
                    } 
                    $upload_aname = "upload/{$monthdir}forum${forumid}_{$newrand}_{$filename}_{$ia}_{$timestamp}.{$currentext}";
                    $upload_bname .= str_replace("×", "[BMDESBõ]", "{$monthdir}forum${forumid}_{$newrand}_{$filename}_{$ia}_{$timestamp}.{$currentext}◎". str_replace("◎", "[BMDESAõ]", $attachdes[$ia]) ."◎0◎". str_replace("◎", "[BMDESAõ]", $FILE_NAME[$ia]) ."◎$FILE_SIZE[$ia]") ."◎{$bmfopt['gzip_attachment']}×";
                    attach_upload($FILE_URL[$ia], $upload_aname, $FILE_SIZE[$ia]);
                    $uploaded_num++;
                } 
            } 
            	
            if ($uploaded_num > 0) {
    			bmbdb_query("UPDATE {$database_up}userlist SET uploadfiletoday=uploadfiletoday+$uploaded_num,lastupload='$timestamp' WHERE userid='$userid'");
            }
            
            if ($inreplytopic == 'yes') $articlecontent = "[post]" . $articlecontent . "[/post]";
            if ($assell == 'yes') $articlecontent = "[pay=" . $sellmoney . "]" . $articlecontent . "[/pay]";
            if ($inhiddentopic == 'yes') $articlecontent = "[hide=" . $postweiwang . "]" . $articlecontent . "[/hide]";
            if ($asgift == 'yes') $articlecontent = "[gift=" . $giftmoney . "]" . $articlecontent . "[/gift]";
            if ($asbeg == 'yes') $articlecontent = "[beg]" . $articlecontent . "[/beg]";


            $axsount = count($usergnshow);
            if ($allow_forb_ub) {
                for($axs = 0;$axs < $axsount;$axs++) $writetlist .= $usergnshow[$axs] . "§";
            } 

            $options = $usesignature . "_" . $openbmbcode . "_" . $openbmbemot . "___" . $hidepicat . "_" . $writetlist . "_" . $openhtmlcode . "_" . $autourl;
            $editinfosql = $timestamp."%".$username;
            
            if ($oldtags) {
            	$oldtags_sql = implode("' OR tagname='", explode(" ", $oldtags));
				eval(load_hook('int_post_modify_tags'));
	            $tquery = "UPDATE {$database_up}tags SET filename=replace(filename,',$article',''),threads=threads-1 WHERE tagname='{$oldtags_sql}'";
	            $tresult = bmbdb_query($tquery);
            }
            
            if ($set_a_tags && $tags && $article == $tid) {
				$tags_ex = tags_set(badwords($tags), $article);
            } else {
            	$tags_ex = array("");
            }
            
            $ttagname = implode(" ", array_slice( $tags_ex, 0, $max_tags_num));
			
			eval(load_hook('int_post_modify_beforesql'));
			if ($filename == $article) {
            	$nquery = "UPDATE {$database_up}threads SET alldata=0,ttagname='$ttagname',other5='',other4='$editinfosql',other3='$upload_bname',options='$options',face='$usericon',newdesc='$articledes',content='$articlecontent',title='$articletitle' WHERE tid = '$article'";
            	$result = bmbdb_query($nquery);
            }

            $nquery = "UPDATE {$database_up}posts SET changtime='$timestamp',other5='',other4='$editinfosql',other3='$upload_bname',options='$options',usericon='$usericon',articlecontent='$articlecontent',articletitle='$articletitle' WHERE id = '$article'";
            $result = bmbdb_query($nquery); 

        	if ($aviewpost == "openit" || !$bmfopt['rewrite'])
        	{
        		$topic_jump = "{$filetopn}?filename=$filename&page={$page}#p{$article}";
        	} else {
        		$topic_jump = "topic_{$filename}_{$page}#p{$article}";
        	}

            if ($postjumpurl == 0) $jumpun = "{$prefix_file}$forumid";
            else {
				$jumpun = $topic_jump;
            }

			eval(load_hook('int_post_modify_suc'));
            jump_page("$jumpun", $others[33],
                "<strong>$others[34]</strong><br /><br />
			$others[9] <a href='{$prefix_file}$forumid'>$others[10]</a> | <a href='{$topic_jump}'>$others[11]</a> | <a href='index.php'>$others[12]</a>", 3);
        } else {
        	error_page($navi_bar_des, $navi_bar_l2, $errc[0], "$errc[9]<br /><br />$status<br />", $others[27], 1);

            $step = 0;
        } 
    } 
    if (!$step || $preview == $print_form[76] || $mupload == $print_form[18]) {
        if ($preview != $print_form[76] && $mupload != $print_form[18]) {
            $articletitle = $oldarticletitle;
        } 
        $articletitle = stripslashes($articletitle);
        $oldarticlecontent = stripslashes($oldarticlecontent);
        if ($can_visual_post && $visual != "off") $articlecontent = str_replace("\n", "<br />", $oldarticlecontent);
        elseif (!$can_visual_post || $visual == "off") $articlecontent = str_replace("<br />", "\n", $oldarticlecontent);
        $articlecontent = badwords($articlecontent);
        
        $status = $others[30];
        include_once("header.php");
        navi_bar($navi_bar_des, $navi_bar_l2, $others[31], 0, 0);
        print_form();
        include_once("footer.php");
        exit;
    } 
} 
function autourl($messagetext)
{ 
    // -----Auto URL Converter-----------
    $urlSearchArray = array(
	"/(?<=[^\]a-z0-9-=\"'\\/])(http:\/\/[a-z0-9\/\-_+=.~!%@?#%&;:$\\|]+\.(jpg|gif|png|bmp))/i",
	"/(?<=[^\]\)a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|rtsp|mms|callto):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i",
	"/(?<=[^\]\)a-z0-9\/\-_.~?=:.])([_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4}))/i"
	);
    $urlReplaceArray = array("[img]\\1[/img]",
	"[url]\\1\\3[/url]",
	"[email]\\0[/email]"
	);

    if (!preg_match("/\[code\].+?\[\/code\]/is", $messagetext)) {
	    $text = substr(preg_replace($urlSearchArray, $urlReplaceArray, ' '.$messagetext), 1);
    } else $text = $messagetext;

	eval(load_hook('int_post_autourl'));
    
    return $text;
} 
function dongzuo($post, $author)
{
    global $others;
    $str = "[color=red]$others[35][/color]";
    $sign = $author;
    if (file_exists("datafile/actinfo.php")) {
        $filedata = file("datafile/actinfo.php");
        $count = count($filedata);
        for ($i = 0;$i < $count;$i++) {
            list($act, $actinfo) = explode("|", $filedata[$i]);
            $actinfo = str_replace("\n", "", $actinfo);
            $post = str_replace($act, "$str $sign {$actinfo}", $post);
        } 
    } 
	eval(load_hook('int_post_dongzuo'));
    return $post;
} 

function print_old($oldlist)
{
    // -----取出旧文章供参考-----------
    global $echo_reply, $bm_skin, $forumid, $usergroupdata, $username, $others, $banuserposts, $database_up, $filename, $logonutnum, $temfilename, $bmfcode_echo;
    require("newtem/$temfilename/global.php");
    $printcache = $pgo[2];
    $allow = array('pic' => 'yes', 'flash' => 'no', 'fontsize' => 'no');
    $printcache .= "<br /><center>$others[36]</center>";

    $query = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}posts p LEFT JOIN {$database_up}userlist u ON u.userid=p.usrid where tid='$filename' ORDER BY 'changtime' DESC  LIMIT 0,5 ";
    $result = bmbdb_query($query);
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $printcache .= "<div class='quote_dialog'>";
        $somepostinfo = explode("_", $row['options']);
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
        $orcontent = stripslashes($row['articlecontent']);
        $contentall = explode("<br />", $orcontent);
		if (count($contentall) > 4) {
			$orcontent = "$contentall[0]<br />$contentall[1]<br />$contentall[2]<br />$contentall[3] .......";
		} 
        
        if (@in_array($logonutnum, $usergnshow)) {
            $orcontent = "$others[38]";
        } 
	    if (!@in_array($row["username"], $iguserlist)) {
	    	$author_type = getLevelGroup($row['ugnum'], $usergroupdata, $forumid, $row['postamount'], $row['point']);
	        include_once("datafile/banuserposts.php");
	        if ((($banuserposts && in_array($row["username"], $banuserposts)) || $row["point"] < $author_type[114]) && $username != $row["username"]) {
	            $orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
	        } 
	    } 
	    if ($row['posttrash'] == 1) {
	    	$orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
	    }
        $orcontent = preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is", $others[38], $orcontent); //加密提示。 
        $orcontent = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", $others[37], $orcontent); //加密提示。 
        $orcontent = preg_replace("/\[upload\](.+?)\[\/upload\]/is", "", $orcontent); //加密提示。 
        $orcontent = preg_replace("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", $others[38], $orcontent); //加密提示。 
        $orcontent = preg_replace("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", $others[38], $orcontent); //加密提示。 
        $orcontent = preg_replace("/\[post\](.+?)\[\/post\]/is", $others[39], $orcontent); //隐藏提示。 
        $orcontent = preg_replace("/\[quote\](.*)\[\/quote\]/is", "", $orcontent);
        $printcache .=("<strong>{$row['username']}： {$row['articletitle']}</strong><br />" . bmbconvert($orcontent, $bmfcode_echo, '', $bmfcode_echo));
        $printcache .= "</div><br />";
    } 
	eval(load_hook('int_post_print_old'));
	echo $printcache;
} 
function attach_upload($attach, $source, $attach_size)
{
    global $login_status, $username, $bmfopt, $database_up, $timestamp, $deltopic, $delreply, $userid, $id_unique, $uploadfiletoday;

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
	eval(load_hook('int_post_attach_upload'));
	if ($bmfopt['watermark'] && $attach_saved === true) {
		if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $source)) {
			include_once('include/markpic.php');
			$debug = makethumb($source, $source);
//			$debug = $watermark_err ? $watermark_err : $debug;
//			echo $debug;
		}
	}
	if ($bmfopt['gzip_attachment'] && $attach_saved === true) {
		gzipattachment($source, 9, "{$source}.gz");
	}
	eval(load_hook('int_post_attach_upload_done'));
	@unlink ($attach);
	@chmod ($source, 0777);
} 
function gzipattachment($src, $level = 5, $dst){

   if(file_exists($src)){
       $filesize = filesize($src);
       $src_handle = fopen($src, "r");
       if(!file_exists($dst)){
           $dst_handle = gzopen($dst, "w$level");
           while(!feof($src_handle)){
               $chunk = fread($src_handle, 2048);
               gzwrite($dst_handle, $chunk);
           }
           fclose($src_handle);
           gzclose($dst_handle);
           unlink($src);
           rename($dst, $src);
           return true;
       } else {
           error_log("$dst already exists");
       }
   } else {
       error_log("$src doesn't exist");
   }
   return false;
}
function mtou($ruser, $filename, $author, $old_title)
{
    global $id_unique, $username, $database_up, $detail, $script_pos, $others, $gl, $timestamp, $forumid, $filetopn, $bbs_title, $short_msg_max, $reply_page_no, $lastid;
    $user = "$gl[426]";
    $content = "$gl[524] <br />{$others[21]} $old_title<br />$others[22]<br /><br /><a href=\"{$filetopn}?filename=$filename&amp;page=$reply_page_no#p$lastid\">{$script_pos}{$filetopn}?filename=$filename</a>";
    $title = "$others[24]";
	eval(load_hook('int_post_mtou'));

    $nquery = "insert into {$database_up}primsg (belong,sendto,prtitle,prtime,prcontent,prread,prother,prtype,prkeepsnd,stid) values ('$user','$ruser','$title','$timestamp','$content','0','','r','','$ruser')";
    $result = bmbdb_query($nquery);
    $nquery = "UPDATE {$database_up}userlist SET newmess=newmess+1 WHERE userid='$ruser'";
    $result = bmbdb_query($nquery);
} 
function print_form()
{
    global $page, $max_post_title, $max_post_des, $somepostinfo, $alert_error_ext, $remote_upload, $remote_referer, $login_status, $max_tags_num, $database_up, $oldtags, $set_a_tags, $openstylereplace, $tid, $bbs_money, $actioneot, $autourl, $bmfcode_post, $preview, $print_form, $oldsellmoney, $status, $articletitle, $articlecontent, $action, $forumid,
    $filename, $article, $type, $olddesc, $selections, $max_poll, $poll_form, $html_codeinfo, $post_sell_max, $send_mail, $max_upload_post, $max_post_length, $min_post_length,
    $qbgcolor, $temfilename, $bmfcode_post, $po, $timestamp, $p, $postjs, $bmfemote, $panelbar, $language, $article,
    $loginform, $log_va, $replymail, $usertype, $emot_lines, $emot_every, $template, $iblock, $block, $icount, $allow_upload, $styleidcode, $can_visual_post, $visual, $styleidcode, $cachedstyle, $allow_forb_ub, $usergroupdata, $uploadfiletoday, $max_daily_upload_size, $newoldupload, $max_upload_size, $max_upload_num, $upload_num, $upload_type_available, $otherimages, $forum_name;
    // 开始模块
    include_once("include/template.php");

	$lang_zone = array("poll_form"=>$poll_form, "print_form"=>$print_form, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);

	//废止旧版编辑器
	$template_name = newtemplate("post", $temfilename, $styleidcode, $lang_zone);
	$form_name = "__bmbForm";
    
	eval(load_hook('int_post_print_form'));

    $todaydate = get_date($timestamp);

    $leftuploadnum = $max_daily_upload_size - $uploadfiletoday;
    $thisdatashow = get_date($timestamp);
    $thistimeshow = get_time($timestamp);
    if (($action == "new") || ($action == "modify" && $filename == $tid)) $infoones = "<input maxlength='$max_post_des' size='59' name='articledes' value='$olddesc' />";
    else $infoones = $print_form[0];

    if ($login_status == 0) {
        $loginshows = $loginform;
        if ($log_va) $loginshows.=$po['v']; 
    } 

    mt_srand($timestamp);
    $list = mt_rand(1, 2);
    if ($list == 1) {
        $listmin = 0;
        $listmax = 26;
    } 
    if ($list == 2) {
        $listmin = 27;
        $listmax = 52;
    } 
    for ($j = $listmin; $j <= $listmax; $j++) {
        $emoypicshos .= ("<input type='radio' value='$j.gif' name='usericon' /><img src=\"images/emotion/$j.gif\" border='0' alt='' />&nbsp;");
        if ($j - $listmin == 12) $emoypicshos .= "<br />";
    } 
    // 12:15 2004-12-26
    $somepostinfo[0] = "checked='checked'";
    if ($action == "modify") {
        $somepostinfo = explode("_", $p);
        if (strstr($somepostinfo[6], "§")) {
            $usergnshowtmp = explode("§", $somepostinfo[6]);
            $fdcoun = count($usergnshowtmp);
            for ($fds = 0;$fds < $fdcoun;$fds++) {
                if ($usergnshowtmp[$fds] != "") {
                    $usergnshow[] = str_replace("new", "", $usergnshowtmp[$fds]);
                } 
            } 
        } else {
            $usergnshow = "nonono";
        } 
        if ($somepostinfo[5] == "yes") $somepostinfo[5] = "checked='checked'";
        if ($somepostinfo[1] == "checkbox") $somepostinfo[1] = "checked='checked'";
        if ($somepostinfo[0] == "checkbox") $somepostinfo[0] = "checked='checked'";
        if ($somepostinfo[2] == "checkbox") $somepostinfo[2] = "checked='checked'";
    } 
    // end
    if ($action == "new" || ($action == "modify" && $article == $tid)) $this_is_topic_p = 1;
    
    if ($allow_forb_ub) {
        $showulist = "<br />$print_form[81]";
        $ccscount = count($usergroupdata);
        for($axd = 0;$axd < $ccscount;$axd++) {
            $usergroupname = explode("|", $usergroupdata[$axd]);
            $checkugns = "";
            if (@in_array($axd, $usergnshow)) $checkugns = "checked='checked'";
            $showulist .= "<input type='checkbox' $checkugns name='usergnshow[]' value='new$axd' id='userg$axd' /><label for='userg$axd'>$usergroupname[0]</label>&nbsp;";
        } 
    } 
    if ($html_codeinfo != "yes") {
        $echotwosysye = "<input disabled='disabled' type='checkbox' id=\"open_ss1\" /><label for=\"open_ss1\">$print_form[1]</label><br />";
    } else {
    	if ($somepostinfo[7] == "checkbox") $checked_html = "checked='checked'";
        $echotwosysye = ' <input type="checkbox" '.$checked_html.' name="openhtmlcode" value="checkbox" id="open_1" /><label for="open_1">' . $print_form[2] . '<strong>HTML Code</strong></label><br />';
    } 

    $showsinfotwo .= '[img] &nbsp; -';
    if ($bmfcode_post['pic']) $showsinfotwo .= ' ' . $print_form[3];
    else $showsinfotwo .= ' ' . $print_form[2];
    $showsinfotwo .= '<br />[flash] -';
    if ($bmfcode_post['flash']) $showsinfotwo .= ' ' . $print_form[3];
    else $showsinfotwo .= ' ' . $print_form[2];
    $showsinfotwo .= '<br />[size]&nbsp; -';
    if ($bmfcode_post['fontsize']) $showsinfotwo .= ' ' . $print_form[3];
    else $showsinfotwo .= ' ' . $print_form[2];

    if (file_exists("datafile/actinfo.php")) {
        $filedata = file("datafile/actinfo.php");
        $count = count($filedata);
        for ($i = 0;$i < $count;$i++) {
            list($act, $actinfo) = explode("|", $filedata[$i]);
            $acta = str_replace("/", "", $act);
            $actinfoshows .= "<option value=\"$act\">$acta</option>";
        } 
    } 
    $outputinfo .= '<input type="checkbox" ' . $somepostinfo[5] . ' name="hidepicat" value="yes"  id="open_xd" /><label for="open_xd">' . $print_form[79] . '</label><br />';

    $checked_url = ($action == "modify" && $somepostinfo[8] != "yes") ? "" : "checked='checked'";
    $output2info .= "<input type='checkbox' name=\"autourl\" value=\"yes\" {$checked_url} id=\"open_6\" /><label for=\"open_6\">$print_form[5]</label><br />";
    if ($bmfcode_post['jifen']) {
        $output2info .= "<input type='checkbox' name=\"inhiddentopic\" value=\"yes\"  id=\"open_7\" /><label for=\"open_7\">$print_form[6]</label> <input maxlength='6' size='3' name='postweiwang' value=\"5\" />$print_form[7]<br />";
    } 

    if ($bmfcode_post['reply']) {
        $outputinfo .= '<input type="checkbox" name="inreplytopic" value="yes" id="open_8" /><label for="open_8">' . $print_form[8] . '</label><br />';
    } 
    if ($bmfcode_post['sell']) {
        $outputinfo .= "<input type='checkbox' name=\"assell\" value=\"yes\" id=\"open_9\" /><label for=\"open_9\">$print_form[9]({$print_form[10]}$post_sell_max)</label><input size=\"6\" name=\"sellmoney\" value=\"0\" />$bbs_money<br />";
        if ($this_is_topic_p) $outputinfo .= "<input type='checkbox' name=\"asgift\" value=\"yes\" id=\"open_gift\" /><label for=\"open_gift\">$print_form[117] </label><input size=\"6\" name=\"giftmoney\" value=\"0\" />$bbs_money<br />";
        $outputinfo .= "<input type='checkbox' name=\"asbeg\" value=\"yes\" id=\"open_beg\" /><label for=\"open_beg\">$print_form[118]</label><br />";
    } 

    if ($allow_upload) {
        $showa .= "";
        if ($upload_num > $max_upload_num) $upload_num = $max_upload_num;
        if (empty($upload_num)) $upload_num = 1;
        $available_ext = explode(' ', $upload_type_available);
        $extcount = count($available_ext);
        $showtype = "<select><option>$print_form[12]</option><option>---------</option>";

        for ($i = 0; $i < $extcount; $i++) {
            $showtype .= "<option>$available_ext[$i]</option>";
        } 
        $showtype .= "</select>";
        if ($action == "new" || $action == "reply" || $action == "quote") {
        	if ($form_name == "__bmbForm") $uploadfileshow .= "<a href='#m' onclick=\"FTB_InsertText('[upload]1[/upload]'); return false;\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br /><span id='upload_forum'></span>";
            else $uploadfileshow .= "<a href='#m' onclick=\"javascript:AddText('[upload]1[/upload]');\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br /><span id='upload_forum'></span>";
        } elseif ($action == "modify") {
            if (strpos($newoldupload, "×")) {
                $attachcshow = explode("×", $newoldupload);
                $cc = count($attachcshow)-1;
            } else {
                if (!empty($newoldupload)) {
                    $attachcshow[0] = $newoldupload;
                    $cc = 1;
                } 
            } 
            if ($cc > 0) $uploadfileshow .= "($print_form[13])<br />";
            for($c = 0;$c < $cc;$c++) {
            	$thisatt = $c + 1;
                $showdes = explode("◎", $attachcshow[$c]);
                if ($showdes[1] != "") $showdesa = "$print_form[80]{$showdes[1]}";
                if ($form_name == "__bmbForm") $uploadfileshow .= "<input type='checkbox' name=\"delactupload[a$c]\" value=\"checkbox\"  /><a href='#m' onclick=\"FTB_InsertText('[upload]{$thisatt}[/upload]'); return false;\" title='$print_form[116] $showdesa {$showdes[3]}'>{$showdes[3]}</a><br />";
                else $uploadfileshow .= "<input type='checkbox' name=\"delactupload[a$c]\" value=\"checkbox\"  /><a href='#m' onclick=\"javascript:AddText('[upload]{$thisatt}[/upload]');\" title='$print_form[116] $showdesa {$showdes[3]}'>{$showdes[3]}</a><br />";
            } 
            if ($max_upload_num - $cc > 0) {
            	$thisatt = $cc + 1;
                if ($form_name == "__bmbForm") $uploadfileshow .= "<a href='#m' onclick=\"FTB_InsertText('[upload]{$thisatt}[/upload]'); return false;\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br /><span id='upload_forum'></span>";
                else $uploadfileshow .= "<a href='#m' onclick=\"javascript:AddText('[upload]{$thisatt}[/upload]');\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br /><span id='upload_forum'></span>";
            }
        } 
    } 
	
	for ($ui = 1; $ui < $max_upload_num; $ui++)
	{
	    if ($form_name == "__bmbForm") $javaupload = "<a href=\"#m\" onclick=\"FTB_InsertText('[upload]".($ui+1)."[/upload]'); return false;\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br />";
	    else $javaupload = "<a href=\"#m\" onclick=\"javascript:AddText('[upload]".($ui+1)."[/upload]');\">$print_form[115]</a> $print_form[80]<input size=\"33\" type=\"text\" name=\"attachdes[]\" />&nbsp;&nbsp;<input size=\"33\" onchange=\"javascript:check_file_ext(this,$extcount);\" type=\"file\" name=\"attachment[]\" /><br />";
		$uploadfileshow .="<span id='uploadfrom{$ui}' style='display:none;'>{$javaupload}</span>\n";
	}
	
    $max_upload_num = $max_upload_num - $cc;
    $thisatt = $cc + 2;
    $showaaash = "
<script type=\"text/javascript\">
//<![CDATA[ 
	totalupload_num=$max_upload_num-1;
	function makeupload(){
		max_upload_num={$max_upload_num};
		upload_show_num='';
		uploaded=$thisatt;
		total_real_count=1;
		upload_num=document.{$form_name}.upload_num.value-1;
		if(upload_num>totalupload_num){	upload_num=totalupload_num;	}
		for(i=0;i<upload_num;i++){
			thisuid = uploaded+i;
			nowfuid = i+1;
			if (nowfuid!=0 && nowfuid!=(max_upload_num)) {
				document.getElementById('uploadfrom'+nowfuid).style.display='';
				total_real_count++;
			}
		}
		for(i=upload_num;i<max_upload_num;i++){
			thisuid = uploaded+i;
			nowfuid = i+1;
			if (nowfuid!=0 && nowfuid!=(max_upload_num)) document.getElementById('uploadfrom'+nowfuid).style.display='none';
		}
		document.{$form_name}.upload_num.value = total_real_count;
	}
//]]>>
</script>
";
    if (isset($article)) $echoinfotwo .= "<input type='hidden' value='$article' name='article' />";
    if ($filename) $echoinfotwo .= "<input type='hidden' value='$filename' name='filename' />";
    if (isset($tid)) $echoinfotwo .= "<input type='hidden' value='$tid' name='tid' />";
    
    if (isset($showa)) $showaaash .= "<br />{$showa} $print_form[14] <input type='text' name='upload_num' value='1' size='1' /> $print_form[15] ($print_form[16] $max_upload_num $print_form[17]) <input name='mupload' onclick='javascript:makeupload();' type='button' value='$print_form[18]' /><input name='mraupload' onclick='javascript:document.{$form_name}.upload_num.value++;makeupload();' type='button' value='+' /><input name='mreupload' onclick='javascript:document.{$form_name}.upload_num.value--;makeupload();' type='button' value='-' />";
    if ($preview == $print_form[76]) {
    	
        $articlecontent = stripslashes($articlecontent);
        $articletitle = stripslashes($articletitle);
        $previewshow = str_replace("<br />", "\n", $articlecontent);
        $previewshow = safe_convert($previewshow, 0, $convert_visual);
        $previewshow = $previewshow;
        if ($actioneot == "yes") $previewshow = dongzuo($previewshow, $username);
        if ($autourl == "yes") $previewshow = autourl($previewshow);
        $previewshow = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "", $previewshow);
        $previewshow = preg_replace("/\[upload\](.+?)\[\/upload\]/is", "", $previewshow);
        $previewshow = bmbconvert($previewshow, $bmfcode_post);


    } 
    echo "
<script type=\"text/javascript\">
//<![CDATA[ 
var html_codeinfo;
html_codeinfo = \"$html_codeinfo\";
//]]>>
</script>";
	$trueaction = $action;
    if (($action != "modify" && $action != "quote") || $form_name == "__bmbForm") $articlecontent = htmlspecialchars($articlecontent);
    if ($form_name == "__bmbForm") $articlecontent = wysiwyg_convert($articlecontent);
    
    if ($action == "quote") $action = "reply";
    
    if ($_POST['tags']) $oldtags = $_POST['tags'];
    
    if ($this_is_topic_p) $usertype_z = $usertype[110];
        else $usertype_z = 0;
        
    @include("datafile/cache/tags_topic.php");
    $tags_topic_list = explode("\n", ($tags_tlist[$forumid] ? $tags_tlist[$forumid] : $tags_tlist['tags_solid']));
    for($til = 0;$til<count($tags_topic_list);$til++){
    	$t_t_option .= "<option value='$tags_topic_list[$til]'>$tags_topic_list[$til]</option>";
    }

	if (!($set_a_tags && ($action == "new" || ($action == "modify" && $article == $tid)))) $set_a_tags = 0;
	

    if ($set_a_tags == 1) {
        $query = "SELECT * FROM {$database_up}tags ORDER BY 'threads' DESC LIMIT 0,20";
        $result = bmbdb_query($query);
        if (bmbdb_num_rows($result) > 0) {
            $chooser_c = "<select name='tagsse' onchange='tagls()'><option value=''></option>";
            while (false !== ($row = bmbdb_fetch_array($result))) {
                $chooser_c .= "<option value=\"". htmlspecialchars($row['tagname']) ."\">{$row['tagname']} </option>";
            } 
            $chooser_c .= "</select>&nbsp;\n";
	    }

    }
	eval(load_hook('int_post_print_form_done'));
    require($template_name);

    $for2 = "<a href='{$prefix_file}$forumid&amp;jinhua=$jinhua'>$forum_name</a>";
    navi_bar($navi_bar_des, $for2, "POST");
} 
function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}
function ban_user_post ($orcontent, $author, $aresult)
{
	global $username, $forumid, $usergroupdata, $logonutnum, $database_up, $war;
	
	$nresult_friend = bmbdb_query("SELECT * FROM {$database_up}contacts WHERE `type`=2 and `owner`='$userid'");
	while (false !== ($line_friend = bmbdb_fetch_array($nresult_friend))) {
		$iguserlist[] = $line_friend['conname'];
	} 
	
    $somepostinfo = explode("_", $aresult['options']);
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
    if (@in_array($logonutnum, $usergnshow)) {
        $orcontent = "[color=red]{$war[12]}[/color]";
    } 
    $orcontent = stripslashes($orcontent);
    $orcontent = preg_replace("/<pre>(.*)<\/pre>/is", "", $orcontent);
    $orcontent = preg_replace("/\[quote\](.*)\[\/quote\]/is", "", $orcontent);
    $orcontent = preg_replace("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", "[color=red]{$war[12]}[/color]", $orcontent);
    $orcontent = preg_replace("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", "[color=red]{$war[12]}[/color]", $orcontent);
    $orcontent = preg_replace("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "[color=red]{$war[12]}[/color]", $orcontent);
    $orcontent = preg_replace("/\[pay=(.+?)\](.+?)\[\/pay\]/is", "[color=red]{$war[13]}[/color]", $orcontent);
    $orcontent = preg_replace("/\[upload\](.+?)\[\/upload\]/is", "", $orcontent);
    $orcontent = preg_replace("/\[post\](.+?)\[\/post\]/is", "[color=red]{$war[14]}[/color]", $orcontent);

    if (!@in_array($aresult["username"], $iguserlist)) {
    	$author_type = getLevelGroup($aresult["ugnum"], $usergroupdata, $forumid, $aresult['postamount'], $aresult['point']);
        include_once("datafile/banuserposts.php");
        if ((($banuserposts && in_array($aresult["username"], $banuserposts)) || $aresult["point"] < $author_type[114]) && $username != $aresult["username"]) {
            $orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
        } 
    } else {
    	$orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
    }
	if ($aresult['posttrash'] == 1) {
		$orcontent = "<span class=\"jiazhongcolor\">Banned Post</span>";
	}
	
    return $orcontent;
}
function remote_filesize($uri, $remote_referer)
{
   $headers = my_get_headers($uri, $remote_referer);
   if (!$headers['content-length']) $size = -1;
   else $size = $headers['content-length'];
   return $size;
}
function remote_getfile ($remote_upload, $remote_referer)
{
	$cUrl = curl_init();
	curl_setopt($cUrl, CURLOPT_URL, $remote_upload);
	curl_setopt($cUrl, CURLOPT_HEADER, 0); 
	curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($cUrl, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; BMForum-Fetcher/1.0; +http://www.bmforum.com)");
	curl_setopt($cUrl, CURLOPT_REFERER, $remote_referer); 
	$data = curl_exec($cUrl);
	curl_close($cUrl);
	return $data;
}
function my_get_headers ($url, $remote_referer) {
   $url_info=parse_url($url);
   if (isset($url_info['scheme']) && $url_info['scheme'] == 'https') {
       $port = 443;
       @$fp=fsockopen('ssl://'.$url_info['host'], $port, $errno, $errstr, 10);
   } else {
       $port = isset($url_info['port']) ? $url_info['port'] : 80;
       @$fp=fsockopen($url_info['host'], $port, $errno, $errstr, 10);
   }
   if($fp) {
       @stream_set_timeout($fp, 10);
       $head = "HEAD ".@$url_info['path']."?".@$url_info['query'];
       $head .= " HTTP/1.0\r\nHost: ".@$url_info['host']."\r\nReferer: $remote_referer\r\n\r\n";
       fputs($fp, $head);
       while(!feof($fp)) {
           if($header=trim(fgets($fp, 1024))) {
                   $sc_pos = strpos( $header, ':' );
                   if( $sc_pos === false ) {
                       $headers['status'] = $header;
                   } else {
                       $label = substr( $header, 0, $sc_pos );
                       $value = substr( $header, $sc_pos+1 );
                       $headers[strtolower($label)] = trim($value);
                   }
           }
       }
       return $headers;
   }
   else {
       return false;
   }
}

function check_upload_ext ($FILE_NAME, $upload_type_available, $FILE_URL, $is_remote_upload = "0") 
{
	global $currentext, $ia;
	
    $available_ext = explode(' ', strtolower($upload_type_available));
    
    $pathname = pathinfo($FILE_NAME);
    
    $pathname["extension"] = strtolower($pathname["extension"]);
    
    $currentext = $pathname["extension"];
    
    if (in_array($pathname["extension"], $available_ext))
	{
		$is_ext_allowed = 1;
    } 
    
	if (!is_uploaded_file($FILE_URL) && $is_remote_upload == "0") $is_ext_allowed = 0;
	if ($is_ext_allowed != 1) @unlink($FILE_URL);
    return $is_ext_allowed;
}
function remote_upload ($remote_upload, $remote_referer)
{
	global $max_upload_size, $remote_is_file, $timestamp, $others, $upload_type_available;
	
	$_FILES['attachment']['name'][] = $filename = safe_upload_name($remote_upload);
	
	$remote_is_file[$filename] = 1;
	
	$is_ext_allowed = check_upload_ext ($filename, $upload_type_available, "", "1");

	if (!$is_ext_allowed) {
		$size = -1;
		$_FILES['attachment']['error'][] = $others[5];
	} else {
		$size = remote_filesize($remote_upload, $remote_referer);
	}

	if ($size <= $max_upload_size && $size >= 0 && $size != 'unknown') {
	 	$remote_upload_files = remote_getfile($remote_upload, $remote_referer);
	 	if ($remote_upload_files) {
			$session_upload = "tmp/remote_".$timestamp.".bmb";
			writetofile($session_upload, $remote_upload_files);
			@chmod ($session_upload, 0777);
			$size = filesize("tmp/remote_".$timestamp.".bmb");
			$_FILES['attachment']['error'][] = 0;
		} else {
			$_FILES['attachment']['error'][] = 4;
		}
		$_FILES['attachment']['tmp_name'][] = $session_upload;
		
	} elseif ($size != -1) {
		$_FILES['attachment']['error'][] = 2;
	}
	$_FILES['attachment']['size'][] = $size;
}