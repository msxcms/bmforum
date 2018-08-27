<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
require_once("datafile/config.php");
require_once("getskin.php");
require_once("include/common.inc.php");
require_once("lang/$language/topic.php");
get_forum_info("");

if ($login_status == 0) {
	error_page($gl[368], $gl[368], $gl[368], $gl[51]);
} 

if (strtolower("贵妃") == "贵妃") $username = strtolower($username); 
$buyer = $username;
/* SQL Query */
$line = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}posts WHERE id='$article' LIMIT 0,1"));

if (!$line['id']) die("Access Denied");

$seller = $line['username'];
$seller_id = $line['usrid'];
$forumid = $line['forumid'];

if ($verify != $log_hash) die("Access Denied");

if ($type == "beg") {
	if ($seller_id == $userid) {
		error_page($sellit_lng[11], $sellit_lng[11], $sellit_lng[11], $sellit_lng[27]);
	}
	$auoget = get_user_info($line['usrid'], "usrid");
	
	$begmoney = floor($begmoney);
	
	if ($usermoney < $begmoney || !is_numeric($begmoney) || $begmoney <= 0) {
		error_page($sellit_lng[11], $sellit_lng[11], $sellit_lng[11], $sellit_lng[13].$begmoney." ".$bbs_money);
	}
	
	
	$query_id = $line['id']."3";
	$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
	$gift_row = bmbdb_fetch_array($result);

	
	$beggar_list = explode(",", substr($gift_row['giftid'], 1));
	if (!in_array($userid, $beggar_list)) {
		$gift_row['begers']++;
		$gift_row['giftid'] = ",$userid".$gift_row['giftid'];
	}

	$begmoneys = $gift_row['begmoneys'] + $begmoney;
	$beglog = ",$username|$begmoney".$gift_row['beglog'];
	
	eval(load_hook('int_sell_beg'));

    bmbdb_query("REPLACE INTO {$database_up}beg (id,tid,beglog,giftid,begers,begmoneys) VALUES ($query_id,$line[tid],'$beglog','{$gift_row['giftid']}',{$gift_row['begers']}, $begmoneys)");
    bmbdb_query("UPDATE {$database_up}userlist SET money=money-'$begmoney' WHERE userid='$userid'");
    bmbdb_query("UPDATE {$database_up}userlist SET money=money+'$begmoney' WHERE userid='$seller_id'");
    
	jump_page("topic.php?forumid=$forumid&filename=$filename&page=$page#p$article", "$sellit_lng[14]",
	    "<strong>$sellit_lng[14]</strong><br /><br />
	$gl[3] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?filename=$filename&page=$page#p$article'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
	
} elseif ($type == "gift") {
	$tid_line = bmbdb_fetch_array(bmbdb_query("SELECT articlecontent,usrid FROM {$database_up}posts WHERE id='$line[tid]' LIMIT 0,1"));
	
	if ($tid_line['usrid'] != $userid) error_page($sellit_lng[18], $sellit_lng[18], $sellit_lng[18], $sellit_lng[22]);
	
	$check_gift = preg_replace_callback("/\[gift=(.+?)\](.+?)\[\/gift\]/is", function ($matches) { return giftcheck($matches[1]); }, $tid_line['articlecontent']);
	
	$p_usrid = $line['usrid'];
	
	$auoget = get_user_info($line['usrid'], "usrid");
	
	if ($giftmoney == 0) error_page($sellit_lng[18], $sellit_lng[18], $sellit_lng[18], $sellit_lng[21]);
	if ($usermoney < $giftmoney || $giftmoney <= 0) error_page($sellit_lng[18], $sellit_lng[18], $sellit_lng[18], $sellit_lng[19].$giftmoney." ".$bbs_money);
	
	$query_id = $line['tid']."2";
	$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
	$gift_row = bmbdb_fetch_array($result);
	
	$beggar_list = explode(",", substr($gift_row['giftid'], 1));
	if (!in_array($p_usrid, $beggar_list)) {
		$gift_row['begers']++;
		$gift_row['giftid'] = ",$p_usrid".$gift_row['giftid'];
	} else error_page($sellit_lng[18], $sellit_lng[18], $sellit_lng[18], $sellit_lng[25]);

	$begmoneys = $gift_row['begmoneys'] + $giftmoney;
	$beglog = ",".$line['username'].$gift_row['beglog'];
	
	eval(load_hook('int_sell_gift'));
	
    bmbdb_query("REPLACE INTO {$database_up}beg (id,tid,beglog,giftid,begers,begmoneys) VALUES ($query_id,$line[tid],'$beglog','{$gift_row['giftid']}',{$gift_row['begers']}, $begmoneys)");
    bmbdb_query("UPDATE {$database_up}userlist SET money=money-'$giftmoney' WHERE userid='$userid'");
    bmbdb_query("UPDATE {$database_up}userlist SET money=money+'$giftmoney' WHERE userid='$p_usrid'");
    
	jump_page("topic.php?forumid=$forumid&filename=$filename&page=$page#p$article", "$sellit_lng[23]",
	    "<strong>$sellit_lng[23]</strong><br /><br />
	$gl[3] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?filename=$filename&page=$page#p$article'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);

} else {
	$tmp = preg_replace_callback("/\[pay=(.+?)\](.+?)\[\/pay\]/is", function ($matches) { return sellcheck($matches[1]); }, $line['articlecontent']);

	$auoget = get_user_info($line['usrid'], "usrid");
	$useddrtype = $auoget['ugnum'];
	$tmpusergroup = getLevelGroup($useddrtype, $usergroupdata, $forumid, $auoget['postamount'], $auoget['point']);
	$post_sell_max = $tmpusergroup[29];

	if ($asellmoney > $post_sell_max) $asellmoney = $post_sell_max;
	if ($sellmoney > $post_sell_max) $sellmoney = $post_sell_max;
	
	$query_id = $line['id']."1";

	if ($act != "refund") {
	    if ($buyer != $username || $asellmoney < 0) {
			error_page($gl[368], $gl[368], $gl[368], $gl[369]);
	    } 
	    if ($usermoney < $sellmoney) {
	    	error_page($gl[368], $gl[368], $gl[368], $gl[371]);
	    } 
	    $erlists = explode(",", $line['sellbuyer']);
	    if (@in_array($userid, $erlists)) {
	    	error_page($gl[368], $gl[368], $gl[368], $gl[468]);
	    } 

	    $sellbuyer = $line['sellbuyer'] . $userid . ",";
	    

		
		$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
		$gift_row = bmbdb_fetch_array($result);

	    $buyer_moneys = $gift_row['giftid'] ? $userid."|".$sellmoney . "," . $gift_row['giftid'] : $userid."|".$sellmoney;
		$begmoneys = $gift_row['begmoneys'] + $sellmoney;
		$gift_row['begers']++;
		$beglog = ",$username".$gift_row['beglog'];
		
		eval(load_hook('int_sell_sell'));

	    bmbdb_query("REPLACE INTO {$database_up}beg (id,tid,beglog,giftid,begers,begmoneys) VALUES ($query_id,$line[tid],'$beglog','$buyer_moneys',{$gift_row['begers']}, $begmoneys)");


		bmbdb_query("UPDATE {$database_up}userlist SET money=money-'$sellmoney' WHERE userid='$userid'");
	    bmbdb_query("UPDATE {$database_up}userlist SET money=money+'$sellmoney' WHERE userid='$seller_id'");
	    bmbdb_query("UPDATE {$database_up}posts SET sellbuyer='$sellbuyer' WHERE id='$article' LIMIT 1");
	} else {
	    get_forum_info("");
	    tbuser();
	  
	    $check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
	    
	    if ($usertype[22] == "1") $check_user = 1;
	    if ($usertype[21] == "1") $check_user = 1;
	    if ($seller == $username) $check_user = 1;
	    if ($usertype[111] != "1" && $seller != $username) $check_user = 0;
	    if ($check_user == 0) {
	    	error_page($gl[233], "<a href='forums.php?forumid=$forumid'>$forum_name</a>", $gl[53], $gl[217]);
	    } 
	    
	    
		$result = bmbdb_query("SELECT * FROM {$database_up}beg where `id`=$query_id");
		$gift_row = bmbdb_fetch_array($result);

	    $erlists = explode(",", $gift_row['giftid']);
	    
	    for ($i = $del_money = 0;$i < count($erlists); $i++) {
	    	$detail = explode("|", $erlists[$i]);
	    	if ($detail[0] && is_numeric($detail[0])) {
	            bmbdb_query("UPDATE {$database_up}userlist SET money=money+'$detail[1]' WHERE userid='$detail[0]'");
	            $del_money+=$detail[1];
	        }
	    }
		eval(load_hook('int_sell_refund'));

	    bmbdb_query("DELETE FROM {$database_up}beg WHERE id='$query_id'");
	    bmbdb_query("UPDATE {$database_up}userlist SET money=money-'$del_money' WHERE userid='$seller_id'");
	    bmbdb_query("UPDATE {$database_up}posts SET sellbuyer='' WHERE id='$article' LIMIT 1");
	    
	    $showinfo = "{$line['articletitle']}(AUTHOR:{$line['username']}, PID:$article)";
	    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','','$timestamp','{$forumid}','refund')";
	    $result = bmbdb_query($nquery); 
	}

	jump_page("topic.php?forumid=$forumid&filename=$filename&page=$page#p$article", "$gl[372]",
	    "<strong>$gl[373]</strong><br /><br />
	$gl[3] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?filename=$filename&page=$page#p$article'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
}
function sellcheck($code)
{
    global $asellmoney, $sellmoney;
    $code = stripslashes($code);
    $code = trim($code); 
    if (preg_match("/^[0-9]{1,}$/", $code)) {
        $asellmoney = $sellmoney = $code;
    } else {
        $asellmoney = $sellmoney = 0;
    } 
    
} 
function giftcheck($code)
{
	global $giftmoney;
    $code = stripslashes($code);
    $code = trim($code); 
    if (preg_match("/^[0-9]{1,}$/", $code)) {
        $giftmoney = $code;
    } else {
        $giftmoney = 0;
    } 
    
} 
