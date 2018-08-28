<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

// ===================================
// Display Article
// ===================================
function showarticle($print_info_2, $post_list)
{
    global $bgcolor, $bmf_post_list, $line, $thispageurl, $byms, $banuserposts, $nowfloor, $author_point, $author_type, $ads_tag_bar, $sign_img_bar, $tag_url_bar, $logonutnum, $myusertype, $showartall, $read_post, $po, $iguserlist, $usergnshow, $temfilename, $titleline, $author, $contentconverted, $editinfoed, $sign_text_line, $postbinfo, $showsimage, $icon, $pingbiuser, $title, $time, $uploadfileshow, $username;

	$lowerauthor = strtolower($author);

    if (!@in_array($lowerauthor, $iguserlist)) {
        include_once("datafile/banuserposts.php");
        if ((($banuserposts && in_array($lowerauthor, $banuserposts)) || $author_point < $author_type[114]) && $username != $author) {
            $uploadfileshow = "";
            $contentconverted = "<span class=\"jiazhongcolor\">" . $pingbiuser[0] . "</span>";
        } 
    } 
    if (@in_array($lowerauthor, $iguserlist)) {
        $title = "";
        $uploadfileshow = "";
        $contentconverted = $author . "&nbsp;" . $pingbiuser[2];
        $ads_tag_bar = array();
        $tag_url_bar = array();
        $sign_img_bar = array();
    } 
    if (@in_array($logonutnum, $usergnshow) && $myusertype[22] != "1" && $myusertype[21] != "1") {
        $uploadfileshow = "";
        $contentconverted = "<strong><span class=\"jiazhongcolor\">" . $read_post[41] . "</span></strong>";
    } 
    if ($postbinfo) 
    {
    	$postbinfo = "<br />".str_replace("{pageurl}", $thispageurl, $postbinfo)."<br />";
    }
    $tmp_post_list = array("nowfloor" => $nowfloor, "i" => $line['id'], "byms" => $byms, "icon" => $icon, "showsimage" => $showsimage, "postbinfo" => $postbinfo, "sign_text_line" => $sign_text_line, "editinfoed" => $editinfoed, "print_info" => $print_info_2, "print_info_2" => $print_info_2, "bgcolor" => $bgcolor, "title" => $title, "uploadfileshow" => $uploadfileshow, "time" => $time, "ads_tag_bar" => $ads_tag_bar, "sign_img_bar" => $sign_img_bar, "tag_url_bar" => $tag_url_bar, "contentconverted" => $contentconverted);
	$bmf_post_list[] = array_merge((array)$tmp_post_list, (array)$post_list);
	
	eval(load_hook('int_topic_content'));
} 
// View Members' Information
function view_user_info($author, $getuserinfo)
{
    global $id_unique, $html_codeinfo, $level_id, $author_type, $notext_button, $reg_c, $infopics, $author_type, $querynum, $author_point, $usergroupdata, $sign, $line, $id, $gl, $database_up, $forumid, $unreguser, $pagemax, $onlineww, $i, $bbs_money, $otherimages, $admintitle, $admingraphic, $admin_name;


    if ($getuserinfo['pwd'] == "" || $author == "") {
        $starname = "<strong>$unreguser[0]</strong>";
        $amount = 0;
        $sign = "";
    } else {
    	if ($infopics) {
        	if (check_online($author)) $check_on = "<img alt='$unreguser[1]' src='$otherimages/system/online3.gif' />";
        	else $check_on = "<img alt='$unreguser[2]' src='$otherimages/system/offline.gif' />";
        }
    } 

    list($oicq, $msn, $icq) = explode('※', $getuserinfo['qqmsnicq']);
    $author_point = $getuserinfo['point'];
    $getuserinfo['bym'] = floor($author_point / 10);

    $getuserinfo['r_userlevel'] = getUserLevel($getuserinfo['postamount'], $getuserinfo['point'], $author, $getuserinfo['ugnum']);
    $getuserinfo['r_userIcon'] = getUserIcon($getuserinfo['postamount'], $getuserinfo['point'], $author, $getuserinfo['ugnum']);

    $author_type = $usertype = getLevelGroup($getuserinfo['ugnum'], $usergroupdata, $forumid, $getuserinfo['postamount'], $getuserinfo['point'], $level_id);
    list(, , , , , , , , , $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], , , , , , , $swf, , , , , , , ,$html_codeinfo , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney']) = $usertype;
    $bmfcode_sign['table'] = $bmfcode_post['table'] = $author_type[115];

    if ($infopics && !empty($usertype[23])) $userpic = "<img alt='{$usertype[0]}$unreguser[3]' src='$otherimages/system/{$usertype[23]}' />&nbsp;";
    list($regdate, $regip) = explode("_", $getuserinfo['regdate']);
    $getuserinfo['rr_usericon'] = get_user_portait($getuserinfo['avarts'], true, $getuserinfo['mailadd']);



    list($y, $m, $d) = explode("-", $getuserinfo['birthday']);

    if ($getuserinfo['fromwhere'] == "") $getuserinfo['fromwhere'] = $unreguser[7];

    if ($infopics) {
	    if ($m != "" && $d != "") {
	        $starname = astrology($m, $d);
	        $starname .= "&nbsp;";
	    } 
	    if ($getuserinfo['sex'] == "Male") {
	        $seximages = "&nbsp;<img src='$otherimages/system/mal.gif' width='20' alt='$unreguser[8]' />";
	    } elseif ($getuserinfo['sex'] == "Female") {
	        $seximages = "&nbsp;<img src='$otherimages/system/fem.gif' width='20' alt='$unreguser[9]' />";
	    } else {
	        $seximages = "";
	    } 
	    if ($y != 0 && $m != "" && $d != "") {
	        $shengxiao = robertsx($y, $m, $d);
	        $shengxiao .= "&nbsp;";
	    } 
    }
    if (!$getuserinfo['postamount']) $getuserinfo['postamount'] = 0;
    if (!$getuserinfo['digestmount']) $getuserinfo['digestmount'] = 0;
    if (!$getuserinfo['money']) $getuserinfo['money'] = "0";
    $getuserinfo['national'] = nationalget($getuserinfo['national']);
    if ($infopics) $getuserinfo['sureget'] = "$unreguser[10]{$starname}{$shengxiao}$userpic$check_on$seximages<br />";
	
    if ($getuserinfo['homepage']) {
        if (strpos($getuserinfo['homepage'], "://") === false) $getuserinfo['homepage'] = "http://{$getuserinfo[homepage]}";
    }
    
    if ($regdate) $getuserinfo['regdate']= get_date($regdate);
	if(is_array($reg_c)){
		$count_rc = count($reg_c);
	}
	if ($count_rc > 0 && $line['baoliu2']) {
		for($sr = 0;$sr < $count_rc; $sr++){
			$detail = explode("|", $reg_c[$sr]);
			$reg_ssc["$detail[0]"] = $detail[1];
			$reg_type["$detail[0]"] = $detail[3];
			$reg_hide["$detail[0]"] = $detail[9];
			$reg_select["$detail[0]"] = $detail[3] == 3 ? unserialize(base64_decode($detail[4])) : "";
		}
		
		$custom = unserialize(base64_decode($getuserinfo['baoliu2']));
		if (is_array($custom)) {
			foreach ($custom as $key => $value){
				if ($reg_hide["$key"] != 1) {
					$getuserinfo['cutsom_info'] .= $reg_ssc["$key"];
					$getuserinfo['cutsom_info'] .= " ".($reg_type["$key"] == 3 ? $reg_select["$key"]["$value"] : $value)."<br />";
				}
			}
		}
	}
    $print_info = $getuserinfo;
    
    $urlauthor = urlencode($author);
    $replaces = array("ugnum" => $ugnum, "level_id" => $level_id, "publicmail" => $getuserinfo['publicmail'], "author_id" => $getuserinfo['userid'], "homepage" => $getuserinfo['homepage'], "icq" => $icq, "msn" => $msn, "oicq" => $oicq, "useremail" => $getuserinfo['mailadd'], "author" => $author, "urlauthor" => $urlauthor);
    if ($notext_button == 1) {
    	$replaces_l = array("text_8" => "", "text_7" => "", "text_6" => "", "text_5" => "", "text_4" => "", "text_4" => "", "text_3" => "", "text_2" => "", "text_1" => "");
    } else {
    	$replaces_l = array("text_8" => $unreguser[31], "text_7" => "ICQ", "text_6" => "MSN", "text_5" => "QQ", "text_4" => $unreguser[26], "text_4" => $unreguser[26], "text_3" => $unreguser[24], "text_2" => $unreguser[22], "text_1" => $unreguser[20]);
    }
    $post_list = array_merge((array)$replaces, (array)$replaces_l);
    


    eval(load_hook('int_topic_profile'));

    return array($print_info, $post_list, $bmfcode_sign, $bmfcode_post, $getuserinfo['signtext']);
} 
// ===================================
// Polls
// ===================================
function get_sta()
{
    global $forumid, $row, $poll_display, $usertype, $bmfopt, $t, $autorip_true, $filename, $timestamp, $addhead, $username, $get_sta_lng, $threadrow, $login_status, $username,
    $bgcolor, $qbgcolor, $postamount, $forum_admin, $database_up, $temfilename, $styleConfig, $topic_author, $topic_name, $topic_date, $topic_content, $print_info_2, $idpath, $max_poll, $otherimages;
    $status = "";

    $nquery = "SELECT * FROM {$database_up}polls WHERE id='$filename'";
    $nresult = bmbdb_query($nquery);
    $nrow = bmbdb_fetch_array($nresult);
    $topic_author = $row['author'];
    $topic_area = $row['ip'];
    if ($bmfopt["ip_address"]) $topic_area = "<a href='{$bmfopt[ip_address]}$topic_area' target='_blank'>$topic_area</a>";
    $line = get_user_info($topic_author);
    list($print_info[$topic_author], $post_list, , $bmfcode_post, $amountuser) = view_user_info($row['author'], $line);
    $amountuser = $postamount; 
    // NEW CHECK PEOPLE
    $cdetail = explode("_", $nrow['setting']);
    $detailq34234 = explode("|", $nrow['options']); 
    // ==================
    $vote_sel = explode("|", $nrow['options']);

    $amountsum = 0;
    $nrow['polluser'] = substr($nrow['polluser'], 1);
    $vote_user_sel = explode("|", $nrow['polluser']);
    $iacount = count($vote_user_sel)-1;
    $scountq = $iacount;
    if ($iacount > 0) {
        $polluserlist = "<select><option>$get_sta_lng[0]</option><option>------</option>";
        for($iac = 0;$iac < $iacount;$iac++) {
            $polluserlist .= "<option>$vote_user_sel[$iac]</option>";
        } 
        $polluserlist .= "</select>";
    } 
    $count = min(count($vote_sel), $max_poll);
    for ($i = 0; $i < $count; $i++) {
        list($name[$i], $amount[$i]) = explode(",", $vote_sel[$i]);
        $amountsum += $amount[$i];
    } 
    $zero = 0;
    if ($amountsum == 0) {
        $zero = 1;
        $amountsum = 1;
    } 
    if ($cdetail[3] == 1 && $timestamp > $cdetail[4]) {
        $leftdateisend = 1;
    } 
    if ($cdetail[2] == 1 && $leftdateisend != "1") {
        if (strpos($nrow['polluser'], strtolower($username)."|") !== false && $login_status == 1) {
            for ($i = 0; $i < $count; $i++) {
                $pic[$i]['numshow'] = rand(1, 10);
                $pic[$i]['thisrate'] = @round($amount[$i] / $scountq, 3) * 100;
                $pic[$i]['width'] = floor(300 * $amount[$i] / $amountsum);
                if ($cdetail[0] == "m") $pic[$i]['staxtus'] = (" <input type='checkbox' value='$i' name='mychoice[$i]' id=\"open_poll_$i\" /><label for=\"open_poll_$i\">$name[$i]</label> ");
                else $pic[$i]['staxtus'] = (" <input type='radio' value='$i' name='mychoice' id=\"mag_poll_$i\" /><label for=\"mag_poll_$i\">$name[$i]</label> ");
                $pic[$i]['amount']= "{$amount[$i]} $get_sta_lng[1]";
            } 
        } else {
            $status = "<strong>* $get_sta_lng[2]</strong> ";
            for ($i = 0; $i < $count; $i++) {
                $pic[$i]['width'] = floor(300 * $amount[$i] / $amountsum);
                if ($cdetail[0] == "m") $pic[$i]['staxtus'] = (" <input type='checkbox' value='$i' name='mychoice[$i]' id=\"open_poll_$i\" /><label for=\"open_poll_$i\">$name[$i]</label> ");
                else $pic[$i]['staxtus'] = (" <input type='radio' value='$i' name='mychoice' id=\"mag_poll_$i\" /><label for=\"mag_poll_$i\">$name[$i]</label> ");

            } 
        } 
    } else {
        for ($i = 0; $i < $count; $i++) {
            $pic[$i]['numshow'] = rand(1, 10);
            $pic[$i]['thisrate'] = @round($amount[$i] / $scountq, 3) * 100;
            $pic[$i]['width'] = floor(300 * $amount[$i] / $amountsum);
            if ($cdetail[0] == "m") $pic[$i]['staxtus'] = (" <input type='checkbox' value='$i' name='mychoice[$i]' id=\"open_poll_$i\" /><label for=\"open_poll_$i\">$name[$i]</label> ");
            else $pic[$i]['staxtus'] = (" <input type='radio' value='$i' name='mychoice' id=\"mag_poll_$i\" /><label for=\"mag_poll_$i\">$name[$i]</label> ");

            $pic[$i]['amount'] = "{$amount[$i]} $get_sta_lng[1]";
        } 
    } 
    if ($zero == 1) $amountsum = 0;
    getUserInfo();
    if ($login_status == 1 && $autorip_true == "1" && (($forum_admin && in_array($username, $forum_admin)) || ($usertype[22] == "1" || $usertype[21] == "1"))) {
        $picinfo = "$get_sta_lng[3] <strong>$scountq</strong> $get_sta_lng[4] {$polluserlist} $get_sta_lng[5] $topic_date {$get_sta_lng[6]} $topic_area";
    } else {
        $picinfo = "$get_sta_lng[3] <strong>$scountq</strong> $get_sta_lng[4] {$polluserlist} $get_sta_lng[5] $topic_date";
    } 
    if ($cdetail[3] == 1) {
        $leftdate = get_date($cdetail[4]);
        $picinfo .= " $get_sta_lng[14] $leftdate";
    } 
    if ($login_status == 1) {
        if (strpos($nrow['polluser'], strtolower($username)."|") !== false) {
            $status = "&nbsp;* <strong>$get_sta_lng[7]</strong>";
        } else {
            $status .= "<input type='hidden' value=\"$forumid\" name='forumid' /><input type='hidden' value=\"$filename\" name='filename' />";
            $maxchooseitem = $cdetail[1];
            if ($cdetail[1] > $count) $maxchooseitem = $count;
            if ($leftdateisend != "1") {
                if ($cdetail[5] != "1" || $amountuser >= $cdetail[6]) {
                	$button = 1;
                } else {
                    $status .= "<strong>$get_sta_lng[16]{$cdetail[6]}$get_sta_lng[17]</strong>";
                } 
            } else {
                $status .= "<strong>$get_sta_lng[15]</strong>";
            } 
        } 
    } else $status = "&nbsp;* <strong>$get_sta_lng[11]</strong>";
    $qbgcolor = "article_color1";
	
	$poll_display = array("maxchooseitem"=> $maxchooseitem, "button"=> $button, "pic"=> $pic, "filename" => $filename, "article" => $filename, "forumid" => $forumid, "picinfo" => $picinfo, "status" => $status, "pic" => $pic, "outputtotme" => $outputtotme, "topic_name" => $topic_name, "print_info_poll" => $print_info[$topic_author]);
	eval(load_hook('int_topic_poll'));

} 
// ===================================
// Pay and Sell post
// ===================================
function sellit($sellmoney, $code, $outall = "yes", $m_type)
{
    global $username, $author_type, $this_is_gift, $giftid_r_array, $this_is_gift, $page, $line, $userid, $database_up, $usergroupdata, $myusertype, $post_sell_max, $id_unique, $sellit_lng, $code1, $article, $username, $bbs_money, $idpath, $author, $forumid, $filename, $admin_name, $login_status;
    
    if ($m_type == "gift" && $line[tid] != $line[id]) return $code;
    
    if ($m_type == "sell") {
		$query_id = $line[id]."1";
	    
		$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
		$gift_row = bmbdb_fetch_array($result);
	    $gift_row['begmoneys'] = $gift_row['begmoneys'] ? $gift_row['begmoneys'] : 0;
    	
	    $postamount = $line['postamount'];
	    $usertype = $line['ugnum'];
	    $userposmt = $author_type;
	    $post_sell_max = $userposmt[29];
	    $qqmm = 0;
	    
	    if ($sellmoney < 0) $sellmoney = 0;
	    if ($sellmoney > $post_sell_max) $sellmoney = $post_sell_max;
	    if ($sellmoney && !preg_match("/^[0-9]{0,}$/", $sellmoney)) $sellmoney = 0;

		$buyeres = explode(",", $line['sellbuyer']);
	    $count = $gift_row['begers'] ? $gift_row['begers'] : 0;
	    
	    
	    if ($count > 0) {
	    	$buyerlist = "<select name='buyerlist'><option value=''>$sellit_lng[0]</option><option value=''>";
	    	$buyerlist .= implode("</option><option value=''>", explode(",", substr($gift_row['beglog'], 1)));
	    	$buyerlist .= "</option></select>";
		}

	    if ($outall != "NO") $buyitbutton = "&nbsp;<input name='buybutton' type='button' value='$sellit_lng[4]'  onclick='javascript:BuyPost(". $line['id'] .",$page);' />";
	    if ($buyeres && in_array($userid, $buyeres) || $username == $author || $myusertype[21] == "1" || $myusertype[22] == "1") $qqmm = 1;
	    if ($username == $author || $myusertype[111] == "1") $refund_true = "&nbsp;<input id='refundbutton' type='button' value='$sellit_lng[5]' onclick='javascript:refundPost(". $line['id'] .",$page);' />";
	    if ($login_status == 1 && $qqmm == 1) $code1 = "<div class='quote_dialog'><strong><span class='jiazhongcolor'>[$sellit_lng[1] $sellmoney {$bbs_money}$sellit_lng[2] $count {$sellit_lng[3]} $sellit_lng[16]{$gift_row['begmoneys']} $bbs_money {$buyerlist}]</span></strong>{$refund_true}<hr width='100%' class='bordercolor' size='1' />" . $code . "</div>";
	    else $code1 = "<br /><div class='quote_dialog'><strong><span class='jiazhongcolor'>[$sellit_lng[1] $sellmoney {$bbs_money}$sellit_lng[2] $count {$sellit_lng[3]} $sellit_lng[16]{$gift_row['begmoneys']} $bbs_money {$buyerlist}]</span></strong>{$refund_true}<br /><br />$buyitbutton</div><br />";
	} elseif ($m_type == "gift") {
		if (empty($this_is_gift)) {
			$this_is_gift = $line['usrid'];
			bmbdb_query("UPDATE {$database_up}threads SET `alldata`=$this_is_gift WHERE `tid`=$filename");
		}
		
		$query_id = $line[id]."2";
		
		$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
		$gift_row = bmbdb_fetch_array($result);
		
		if ($gift_row['begers'] > 0) {
			$gift_r_array = explode(",", substr($gift_row['beglog'], 1));
			$gift_r_list = implode("<br />\n", $gift_r_array);
			$giftid_r_array = explode(",", substr($gift_row['giftid'], 1));

			$giftgers = "<br /><div class='quote_dialog'><strong><span class='jiazhongcolor'>$sellit_lng[20] $gift_row[begmoneys] $bbs_money $sellit_lng[24] {$gift_row['begers']}</span></strong><hr width='100%' class='bordercolor' size='1' />" . $gift_r_list . "</div>";
		}
		
		$code1  = "<div class='quote_dialog'><strong><span class='jiazhongcolor'>[$sellit_lng[6] $sellmoney $bbs_money]</span></strong><hr width='100%' class='bordercolor' size='1' />" . $code ."</div>";
		$code1 .= "{$giftgers}";

	} elseif ($m_type == "beg") {

		$query_id = $line[id]."3";
		
		$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
		$gift_row = bmbdb_fetch_array($result);
		
		if ($gift_row['begers'] > 0) {
			$beggar_list = explode(",", substr($gift_row['beglog'], 1));
			for ($beggar = 0;$beggar < count($beggar_list);$beggar++){
				$b_detail = explode("|", $beggar_list[$beggar]);
				$beglist .= "$b_detail[0] - $b_detail[1] $bbs_money<br />\n";
			}
			$beggars = "<br /><div class='quote_dialog'><strong><span class='jiazhongcolor'>$sellit_lng[8] $sellit_lng[15] $gift_row[begmoneys] $bbs_money $sellit_lng[26] $gift_row[begers]</span></strong><hr width='100%' class='bordercolor' size='1' />" . $beglist . "</div>";
		}
		
		$code1 = "<div class='quote_dialog'><strong><span class='jiazhongcolor'>[$sellit_lng[7]]</span></strong><hr width='100%' class='bordercolor' size='1' />" . $sellmoney;
		if ($userid != $line['usrid']) $code1 .= "<hr width='100%' class='bordercolor' size='1' />$sellit_lng[10] <input type='text' size='5' value='' id='beg_money[{$line[id]}]' /> $bbs_money <input onclick=javascript:BegPost($line[id],$page,document.getElementById('beg_money[{$line[id]}]').value); type='button' id='begpost_button' value='$sellit_lng[11]' />";
		$code1 .= "</div> {$beggars}";


	}
	
	eval(load_hook('int_topic_sell'));
    return $code1;
} 
// ===================================
// Ban Bad Man's post
// ===================================
function ban_user($topic_content, $author)
{
    global $logonutnum, $iguserlist, $author_point, $pingbiuser, $myusertype, $author_type, $usergnshow, $username, $read_post;

    if (!@in_array($author, $iguserlist)) {
        if (file_exists("datafile/banuserposts.php")) {
            include("datafile/banuserposts.php");
            if ((($banuserposts && in_array($author, $banuserposts)) || $author_point < $author_type[114]) && $username != $author) {
                $topic_content = "$pingbiuser[0]";
            } 
        } 
    } 
    if (@in_array($author, $iguserlist)) {
        $topic_content = $author . "&nbsp;" . $pingbiuser[2];
    } 
    if (@in_array($logonutnum, $usergnshow) && $myusertype[22] != "1" && $myusertype[21] != "1") {
        $topic_content = "<br /><strong>$read_post[41]</strong>";
    } 
	eval(load_hook('int_topic_ban_user'));
    return $topic_content;
} 
function display_posts() 
{
global $bcode_sign, $ttag_ex, $bmfopt, $nowfloor, $time, $page, $count, $ajax_display, $pid, $is_numpid, $topattachshow, $checkattachpic,
 $attachshow, $somepostinfo, $code14, $code4, $code1, $line, $checktrash, $userpoint, $countpostads, $time_2, $frep_select,
 $postads, $emotrand, $can_rec, $notext_button, $checkattachpic_true, $contentconverted, $sign_text_line, $editinfoed, $topic_type, $bmfcode_sign, $ads_tag_bar, $sign_img_bar, $tag_url_bar, $topic_islock, $aresult, $pagemin, $pagemax, $logonutnum, $myusertype, $showartall, $read_post, $po, $iguserlist, $usergnshow, $temfilename, $titleline, $author, $postbinfo, $showsimage, $icon, $pingbiuser, $title, $byms, $uploadfileshow, $admintitle, $admingraphic, $admin_name, $forumid, $unreguser, $pagemax, $onlineww, $i, $id, $gl, $sign, $querynum, $infopics, $max_poll, $otherimages, $topic_author, $topic_name, $topic_date, $topic_content, $print_info_2, $qbgcolor, $postamount, $forum_admin, $database_up, $temfilename, $styleConfig, $bgcolor, $threadrow, 
$row, $usertype, $view_recybin, $recy_allow_ww, $del_self_post, $allow_ajax_reply, $userid, $t, $autorip_true, $timestamp, $addhead, $get_sta_lng, $logonutnum, $iguserlist, $pingbiuser, $usergnshow, $post_sell_max, $sellit_lng, $code1, $article, $bbs_money, $author, $login_status, $database_up, $bmfcode_post, $verandproname, $bcode_post, $forum_line, $bbs_title, $username, $myusertype, $read_post, $usergroupdata, $forum_pos, $forumid, $filename;


    for ($i = $pagemin; $i <= $pagemax; $i++) {
        $line = bmbdb_fetch_array($aresult); // Fetch Array from SQL result
        // ===================================
        // Pass the empty content
        // ===================================
        if ($line['id'] == "") continue;
        if ($line['posttrash'] == 1 && ($view_recybin != "1" || $userpoint < $recy_allow_ww)) continue;
        
        // ===================================
        // Post Colors
        // ===================================
        if (is_numeric($pid)) {
        	$count_nowfloor = bmbdb_query_fetch("SELECT COUNT(id) FROM {$database_up}posts WHERE id < $pid AND tid='$filename'");
        	$count_nowfloor = $count_nowfloor['COUNT(id)'] + 1;
        }
        
        if ($pid) $icolor = $count_nowfloor - 1;
            else $icolor = $i;
            
        if ($icolor % 2 == 0) $bgcolor = "article_color1";
        else $bgcolor = "article_color2";
        if ($icolor % 2 == 0) $qbgcolor = "article_color2";
        else $qbgcolor = "article_color1";

        // ===================================
        // Vars.
        // ===================================

        $title = stripslashes($line['articletitle']);
        $author = $line['username'];
        $content = $line['articlecontent'];
        $time = $line['timestamp'];
        $aaa = $line['ip'];
        $icon = $line['usericon'];
        $usesign = $line['options'];
        $bym = $line['other1'];
        $bymuser = $line['other2'];
        $uploadfilename = $line['other3'];
        $editinfo = $line['other4'];
        $sellmoney = $line['other5'];
        
        // ===================================
        // Check Attachments' Permission
        // ===================================
        $somepostinfo = explode("_", $usesign);

        $checkattachpic = $del_posts = $del_b_posts = 0;
        list($print_info_2[$author], $post_list, $bcode_sign, $bcode_post, $sign_text) = view_user_info($author, $line);
        if ($somepostinfo[1] != "checkbox" && $uploadfilename != "" && (preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/eis", $content) || preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/eis", $content) || preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/eis", $content) || preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/eis", $content) || preg_match("/\[post\](.+?)\[\/post\]/eis", $content))) {
            // 付费帖部分
            if ($myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['sell'] && preg_match("/\[pay=(.+?)\](.+?)\[\/pay\]/eis", $content)) {
                $checkattachpi1 = preg_replace_callback("/\[pay=(.+?)\](.+?)\[\/pay\]/is", function ($matches) { return checkpaid($matches[1]); }, $content);
                $checkattachpi1 = $code14;
            } else {
                $checkattachpi1 = 1;
            } 
            // 威望值部分
            if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['jifen'] && preg_match("/\[hide=(.+?)\](.+?)\[\/hide\]/eis", $content)) {
                $checkattachpi2 = preg_replace_callback("/\[hide=(.+?)\](.+?)\[\/hide\]/is", function ($matches) { return checkhiden($matches[1]); }, $content);
                $checkattachpi2 = $code4;
            } else {
                $checkattachpi2 = 1;
            } 
            // 回复查看部分
            if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['reply'] && preg_match("/\[post\](.+?)\[\/post\]/eis", $content)) {
                $checkattachpi3 = preg_replace_callback("/\[post\](.+?)\[\/post\]/is", function ($matches) { return checkpost($matches[1]); }, $content);
                $checkattachpi3 = $code1;
            } else {
                $checkattachpi3 = 1;
            } 
            // 帖子查看部分
            if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['hpost'] && preg_match("/\[hpost=(.+?)\](.+?)\[\/hpost\]/eis", $content)) {
                $checkattachpi4 = preg_replace_callback("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", function ($matches) { return checkhiden($matches[1], 'hpost'); }, $content);
                $checkattachpi4 = $code4;
           } else {
                $checkattachpi4 = 1;
            } 
            // 金钱查看部分
            if ($author && $myusertype[21] != "1" && $myusertype[22] != "1" && $bcode_post['hmoney'] && preg_match("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/eis", $content)) {
                $checkattachpi5 = preg_replace_callback("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", function ($matches) { return checkhiden($matches[1], 'hmoney') ;}, $content);
                $checkattachpi5 = $code4;
            } else {
                $checkattachpi5 = 1;
            } 

            if ($checkattachpi1 == 1 && $checkattachpi2 == 1 && $checkattachpi3 == 1 && $checkattachpi4 == 1 && $checkattachpi5 == 1) {
                $checkattachpic = 1;
            } 
            if ($login_status == 0) $checkattachpic = 0; 
            
            // 全部完成
        } else {
            $checkattachpic = 1;
        } 
        if ($checkattachpic_true == 1) $checkattachpic = 0;

        $content = "<div class='content_div' id='text{$line['id']}'>" . $content . "</div>";
        $area = $aaa;
        if ($is_numpid && $ajax_display == 1) {
            $nowfloor = $_GET['floor'] ? $_GET['floor'] : $count_nowfloor;
        } else $nowfloor = $i + 1;
        // ===================================
        // Fast quote
        // ===================================
        if ($notext_button == 1) $read_post[12] = $read_post[10] = $read_post[15] = "";
        

        $bar_add = $ajaxscript = $s_reasons = "";
        if ($login_status == 1) {
            $standfor = array("allow_edit" => 1, "text_9" => $read_post[15]);
        }
        if ($topic_islock != 1 && $topic_islock != 3) {
            $insteads = array("text_10" => $read_post[12], "text_11" => $read_post[10], "qrlink" => $qrlink);
        } 

        $time_tmp = getfulldate($time);
        if ($time_2) {
            $timetmp_a = $timestamp - $time;
            $time = get_add_date($timetmp_a);
            if ($time == "getfulldate") {
                $time = $time_tmp;
            } 
        } else {
            $time = $time_tmp;
        } 
        if (!preg_match("/[0-9]{1,3}$/", $bym) || $bym == "none" || $bym == "") {
            $byms = "";
        } else {
            $bym = $bym / 10;
            $bymuser	= substr($bymuser, 1);
            $scores_ex	= explode("|", $bymuser);
            $s_reasons	= array();
            for ($si=0;$si < count($scores_ex);$si++){
            	$detail_si = explode("_", $scores_ex[$si]);
            	$s_reasons[] = array('urlname' => urlencode($detail_si[0]), 'name' => $detail_si[0], 'money' => $detail_si[3], 'point' => floor($detail_si[2]/10), 'date' => getfulldate($detail_si[4]), 'comment' => $detail_si[1]);
            }
            
            $byms = "$read_post[20]:{$bym}$read_post[21]";
        } 
        
        if ($bmfopt["ip_address"]) $area = $aaa;


        if ($login_status == 1 && $autorip_true == "1" && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) $display_ip = 1;
        if ($login_status == 1 && $can_rec == "1" && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) $recover_posts = 1;
        if ($login_status == 1 && $usertype[128] == "1" && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) $recycle_posts = 1;
        if ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1" || $username == $author) && ($i != "0" || ($is_numpid && $ajax_display == 1)) && $del_self_post == 1) $del_posts = 1;
        if ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1") && ($i != -1 || ($is_numpid && $ajax_display == 1))) $del_b_posts = 1;

        if (strpos($uploadfilename, "×")) {
            $attachshow = explode("×", $uploadfilename);
            $countas = count($attachshow)-1;
        } else {
            $attachshow[0] = $uploadfilename;
            $countas = 1;
        } 
        $topattachshow = $attachshow;
        $usergnshow = "";
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
        
        // ===================================
        // Score Display
        // ===================================

        $scoreisi = "";
        $scoreisi = strpos($bym, "-");
        $showsocre = "";
        $showsocre = min(abs($bym), 5);

        
        $editinfoed = "";

        if (strstr($editinfo, "%")) {
            $editinfoed = explode("%", $editinfo);
            $editinfoed[0] = getfulldate($editinfoed[0]);
        } 
        
        $content = stripslashes($content);
        if ($somepostinfo[1] != "checkbox") $contentconverted = bmbconvert($content, $bmfcode_post);
        else $contentconverted = $content;
        $contentconverted = str_replace($highlight, '<strong><span class="jiazhongcolor">' . $highlight . '</span></strong>', $contentconverted);
        
        if ($bmfopt['text_wm']) {
			$contentconverted = text_watermark($contentconverted);
        }
        
	    if ($allow_ajax_reply && $login_status == 1 && ($userid == $line["usrid"] || ($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1")) {
	        $ajaxscript = "title=\"$read_post[54]\" ondblclick=\"if (event.altKey) bmb_ajax_tablecontent('$line[id]','$read_post[15]');\" ";
	    }
        
        // ===================================
        // Attachments
        // ===================================
        $uploadfileshow = diplay_attachment(0, $countas, $line['id'], 1);
        if ($uploadfilename == "") $uploadfileshow = "";
        
        // ===================================
        // Signature
        // ===================================

        $sign_text_line = "";
        $ads_tag_bar = $sign_img_bar = $tag_url_bar = array();
        if ($somepostinfo[0] == "checkbox" && $sign_text) {
            $sign_text_line = bmbconvert($sign_text, $bmfcode_sign, "sign");
            $sign_img_bar = array("sign_text_line" => $sign_text_line);
        }
        
        $nowadsnum = @rand(0, $countpostads-1);
        if ($postads[0] != "") {
            $postads_npw = $postads[$nowadsnum];
            $ads_tag_bar = array("postads_npw" => $postads_npw);
        }
        // ===================================
        // Display Tags of this thread
        // ===================================
        $tags_url = ""; 
        
        if ($row['ttagname'] && $tid == $i && !$pid) {
            $t_count = count($ttag_ex);
            for ($ti = 0; $ti < $t_count; $ti ++) {
                $tags_url .= "<a href=\"plugins.php?p=tags&amp;tagname=". urlencode($ttag_ex[$ti]) ."\">". $ttag_ex[$ti] ."</a>&nbsp;";
            }
            $tag_url_bar = array("tags_url" => $tags_url);
        }

        $post_array = array("diggcount" => $row['diggcount'], "recycle_posts" => $recycle_posts, "recover_posts" => $recover_posts, "s_reasons" => $s_reasons, "posttrash" => $line['posttrash'], "now_i" => $i, "ajaxscript" => $ajaxscript, "display_ip" => $display_ip, "del_b_posts" => $del_b_posts, "del_posts" => $del_posts, "qbgcolor" => $qbgcolor, "bgcolor" => $bgcolor, "area" => $area, "aaa" => $aaa);
        
        $post_list = array_merge((array)$standfor, (array)$insteads, (array)$post_array, (array)$post_list, (array)$tag_url_bar, (array)$ads_tag_bar, (array)$sign_img_bar);
        // ===================================
        // Permission Check and call the funtion
        // ===================================
       	eval(load_hook('int_topic_showarticle'));

        showarticle($print_info_2[$author], $post_list);
    } 

}
function diplay_attachment($start, $countas, $lineid, $getcode)
{
	global $read_post, $topattachshow, $bcode_post, $attachshow, $checkattachpic, $somepostinfo, $filename;
    if ($start > 0) $start = $start - 1;
    
    if (defined("checkattachpic")) $checkattachpic = 1;
    
    for ($ias = $start;$ias < $countas;$ias++) {
    	if (!$topattachshow[$ias] && $getcode == 1) continue;
    	if (!$attachshow[$ias]) continue;
        $showdesa = $loaded = "";
        if ($getcode == 1) $showdes = explode("◎", str_replace("[BMDESBõ]", "×", $topattachshow[$ias])); 
        else $showdes = explode("◎", str_replace("[BMDESBõ]", "×", $attachshow[$ias]));
        $showdes[3] = str_replace("[BMDESAõ]", "◎", $showdes[3]);
        $showdes[1] = str_replace("[BMDESAõ]", "◎", $showdes[1]);
        $showdtim = "$read_post[42]$showdes[2])";
        $showdes[4] = @round($showdes[4] / 1024, 2);
        if ($showdes[4] == "") $showdes[4] = $read_post[44];
        $showdesb = "$showdes[3] ($read_post[43]$showdes[4]kb,";
        if ($showdes[1] != "") $showdesa = "($read_post[40]{$showdes[1]})";
        if (preg_match ("/\.(gif|jpg|jpeg|png|pcx|wmf|bmp)$/is", $showdes[0])) {
            if ($checkattachpic == 1 && $somepostinfo[5] != "yes") {
                $uploadfileshow .= "<a title='$showdesa' target='_blank' href='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}' class='forumimgattach'><img border='0' style='max-width:700px;' src='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}' onload='javascript:if(this.width>700){this.width=700;}if(this.width<400){\$(this).parent().attr(\"class\",\"\");}' onmousewheel='return bbimg(this);' /></a><br />";
            } else {
                $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}'><i class='icon-picture'></i> $read_post[23] $showdesa$showdesb $showdtim</a><br />";
            } 
            $loaded = 1;
        } elseif (preg_match("/\.(zip|rar|ace|bz2|tar|gz)$/is", $showdes[0])) {
            $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}'><img border='0' src='images/attach/zip.gif' alt='' /> $read_post[24] $showdesa$showdesb $showdtim</a><br />";
            $loaded = 1;
        } elseif (preg_match("/\.(txt|doc|pdf|log|ini|inf)$/is", $showdes[0])) {
            $uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}'><img border='0' src='images/attach/txt.gif' alt='' /> $read_post[25] $showdesa$showdesb $showdtim</a><br />";
            $loaded = 1;
	        //By Arbir 2006-5-31 加入自动显示视频附件 
        } elseif (preg_match("/\.(wmv|asf|mpg|avi|mpeg)$/is", $showdes[0])) { 
        	if ($checkattachpic == 1 && $somepostinfo[5] != "yes" && $bcode_post['mpeg']) { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/asf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />".bmbconvert("[asf=400,370]attachment.php?am=$ias&filename=$filename&article={$lineid}[/asf]", $bmfcode_post)."<br />"; 
			} else { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/asf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />"; 
			} 
			$loaded = 1;
		} elseif (preg_match("/\.(wma|mp3)$/is", $showdes[0])) { 
			if ($checkattachpic == 1 && $somepostinfo[5] != "yes" && $bcode_post['mpeg']) { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/asf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />".bmbconvert("[asf=500,70]attachment.php?am=$ias&filename=$filename&article={$lineid}[/asf]", $bmfcode_post)."<br />"; 
			} else { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/asf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />"; 
			} 
			$loaded = 1;
		} elseif (preg_match("/\.(swf)$/is", $showdes[0])) { 
			if ($checkattachpic == 1 && $somepostinfo[5] != "yes" && $bcode_post['flash']) { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/swf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />".bmbconvert("[swf=400,300]attachment.php?am=$ias&filename=$filename&article={$lineid}[/swf]", $bmfcode_post)."<br />"; 
			} else { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/swf.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />"; 
			} 
			$loaded = 1;
		} elseif (preg_match("/\.(rm|rmvb|ram)$/is", $showdes[0])) { 
			if ($checkattachpic == 1 && $somepostinfo[5] != "yes" && $bcode_post['mpeg']) { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/real.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />".bmbconvert("[rm=400,300]upload/$showdes[0][/rm]", $bmfcode_post)."<br />"; 
			} else { 
				$uploadfileshow .= "<a target='_blank' href='attachment.php?am=$ias&filename=$filename&article={$lineid}'><img src='images/attach/real.gif' border='0' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />"; 
			} 
			$loaded = 1;
			//By Arbir 2006-5-31 加入自动显示视频附件 
		}
		$uploadfileshow .= process_attach($showdes, basename(pathinfo($showdes[0], PATHINFO_EXTENSION)), $ias, $filename, $lineid, $read_post, $showdesa, $showdesb, $showdtim, $checkattachpic, $somepostinfo, $bcode_post, $loaded);
		
        $topattachshow[$ias] = "";
    } 
   	eval(load_hook('int_topic_uploadfileshow'));
    return $uploadfileshow;
}
function manage_op()
{
	global $row, $topic_reply, $topic_type, $topic_islock, $thtoptype, $forum_cid, $login_status, $forum_admin, $username, $usertype, $forumid, $filename, $manageops, $reader_mang, $checktrash;
    $manageops .= "<br /><strong>$reader_mang[2]</strong>";

    if ($row['addinfo']) {
        list($moveinfoold, $isjztitle) = explode("|", $row['addinfo']);
    } 
    if ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1") && $checktrash != "yes") {
        if ($topic_islock != 1 && $topic_islock != 3) $manageops .= "[<a href='misc.php?p=manage&amp;action=lock&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[3]</a>]";
        else $manageops .= "[<a href='misc.php?p=manage&amp;action=unlock&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[4]</a>]";
        $manageops .= "[<a href='misc.php?p=manage&amp;action=del&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[5]</a>][<span style=\"cursor:pointer;\" onClick=\"javascript:DelReply();\">$reader_mang[6]</span>][<span style=\"cursor:pointer;\" onClick=\"javascript:DelReply('t');\">$reader_mang[24]</span>][<span style=\"cursor:pointer;\" onClick=\"javascript:DelReply('r');\">$reader_mang[25]</span>][<a href='misc.php?p=manage2&amp;action=del&amp;forumid=$forumid&amp;article=all&amp;filename=$filename'>$reader_mang[22]</a>][<a href='misc.php?p=manage&amp;action=move&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[7]</a>][<a href='misc.php?p=manage3&amp;action=move&amp;forumid=$forumid&amp;newforumid=trash&amp;filename=$filename'>$reader_mang[9]</a>]";
        if ($thtoptype == 9) $manageops .= "[<a href='misc.php?p=topsys&amp;job=delone&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'><strike>$reader_mang[10]</strike></a>]";
        else $manageops .= "[<a href='misc.php?p=topsys&amp;job=write&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$reader_mang[10]</a>]";
        if ($thtoptype == 8) $manageops .= "[<a href='misc.php?p=catesys&amp;cateid=$forum_cid&amp;job=delone&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'><strike>$reader_mang[11]</strike></a>]";
        else $manageops .= "[<a href='misc.php?p=catesys&amp;cateid=$forum_cid&amp;job=write&amp;step=2&amp;foruid=$forumid&amp;topid=$filename'>$reader_mang[11]</a>]";
        $manageops .= "[<a href='misc.php?p=manage2&amp;action=btfront&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[12]</a>]";
        if ($topic_type >= 3) $manageops .= "[<a href='misc.php?p=manage2&amp;action=unhold&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[13]</a>]";
        else $manageops .= "[<a href='misc.php?p=manage2&amp;action=holdfront&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[14]</a>]";
        if ($isjztitle == "0" || $isjztitle == "") $manageops .= "[<a href='misc.php?p=manage5&amp;action=add&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[15]</a>]";
        else $manageops .= "[<a href='misc.php?p=manage5&amp;action=cancel&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[16]</a>]";
        if ($topic_islock == 0 || $topic_islock == 1) $manageops .= "[<a href='misc.php?p=manage&amp;action=jihua&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[17]</a>]";
        else $manageops .= "[<a href='misc.php?p=manage&amp;action=unjihua&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[18]</a>]";
        if ($topic_reply > 0) $manageops .= "[<a href='misc.php?p=manage3&amp;action=split&amp;filename=$filename'>$reader_mang[23]</a>]";
    } elseif ($login_status == 1 && (($forum_admin && in_array($username, $forum_admin)) || $usertype[22] == "1" || $usertype[21] == "1") && $checktrash == "yes") {
        $manageops .= "[<a href='misc.php?p=rtrash&amp;action=move&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[19]</a>]";
        $manageops .= "[<a href='misc.php?p=manage&amp;action=del&amp;forumid=$forumid&amp;filename=$filename'>$reader_mang[20]</a>]";
    } else {
        $manageops .= "$reader_mang[21]";
    } 
   	eval(load_hook('int_topic_manageops'));
}
