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

$usergroupfile = "datafile/usergroup.php";
$thisprog = "editusergroup.php";

if ($useraccess != "1" || $admgroupdata[13] != "1") {
    adminlogin();
} 
$thistype = explode("|", $usergroupdata[$id]);

$level = $_GET['level'] ? $_GET['level'] : $_POST['level'];

if ($level == 1) {
	
	if ($targetid != "") { // Forum Level Group
	    $result = bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='$targetid'");
	} else {
		$result = bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='0'");
	}
	
    $line = bmbdb_fetch_array($result);
    if (!empty($line['maccess'])) {
    	$usergroupdata = $line['maccess'];
    	$thistype = explode("|", $line['maccess']);
    } else {
    	$thistype = explode("|", $usergroupdata[4]);
    }
    
} else {
	if ($targetid != "") { // Forum UserGroup
	    $query = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid'";
	    $result = bmbdb_query($query);
	    $line = bmbdb_fetch_array($result);
	    $usergroupdata = explode("\n", $line['usergroup']);
	    $thistype = explode("|", $usergroupdata[$id]);
	} 
}
if ($id == 4 && $level != 1) { 
$tips_membergroups = "<tr bgcolor=\"#DB5541\"><td colspan='2' style=\"color:#FFFFFF;border: #AE4F5C 1px soild;\">
$arr_ad_lng[1177]
</td></tr>";
}
$usertypelist = "";
// Easier Vars
list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $maxwidth, $maxheight, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point, $del_self_reth, $edit_time_limit) = $thistype;

if (!$action) {
    if ($canpost) $open_canpost = "checked='checked'";
    else $close_canpost = "checked='checked'";
    if ($canreply) $open_canreply = "checked='checked'";
    else $close_canreply = "checked='checked'";
    if ($canpoll) $open_canpoll = "checked='checked'";
    else $close_canpoll = "checked='checked'";
    if ($canvote) $open_canvote = "checked='checked'";
    else $close_canvote = "checked='checked'";
    if ($bmfcode_sign['pic']) $sign_pic_open = "checked='checked'";
    else $sign_pic_close = "checked='checked'";
    if ($bmfcode_sign['flash']) $sign_flash_open = "checked='checked'";
    else $sign_flash_close = "checked='checked'";
    if ($bmfcode_sign['fontsize']) $sign_fontsize_open = "checked='checked'";
    else $sign_fontsize_close = "checked='checked'";
    if ($sign_use_bmfcode) $sign_bmfcode = "checked='checked'";
    else $sign_no_bmfcode = "checked='checked'";
    if ($enter_tb) $open_enter_tb = "checked='checked'";
    else $close_enter_tb = "checked='checked'";
    if ($send_msg) $send_msg_yes = "checked='checked'";
    else $send_msg_no = "checked='checked'";
    if ($use_own_portait) $use_own_portait_yes = "checked='checked'";
    else $use_own_portait_no = "checked='checked'";
    if ($swf) $open_swf = "checked='checked'";
    else $close_swf = "checked='checked'";
    if ($supermod) $supermod_yes = "checked='checked'";
    else $supermod_no = "checked='checked'";
    if ($admin) $admin_yes = "checked='checked'";
    else $admin_no = "checked='checked'";
    if ($mod) $mod_yes = "checked='checked'";
    else $mod_no = "checked='checked'";
    if ($html_codeinfo == "yes") $open_html = "checked='checked'";
    else $close_html = "checked='checked'";
    if ($del_true) $del_true_yes = "checked='checked'";
    else $del_true_no = "checked='checked'";
    if ($del_rec) $del_rec_yes = "checked='checked'";
    else $del_rec_no = "checked='checked'";
    if ($can_rec) $can_rec_yes = "checked='checked'";
    else $can_rec_no = "checked='checked'";
    if ($opencutusericon) $open_cutusericon = "checked='checked'";
    else $close_cutusericon = "checked='checked'";
    if ($openupusericon) $open_upusericon = "checked='checked'";
    else $close_upusericon = "checked='checked'";
    if ($allow_upload) $allow_upload_yes = "checked='checked'";
    else $allow_upload_no = "checked='checked'";
    if ($p_read_post) $open_p_read_post = "checked='checked'";
    else $close_p_read_post = "checked='checked'";
    if ($view_list) $open_view_list = "checked='checked'";
    else $close_view_list = "checked='checked'";
    if ($lock_true) $lock_true_yes = "checked='checked'";
    else $lock_true_no = "checked='checked'";
    if ($del_reply_true) $del_reply_true_yes = "checked='checked'";
    else $del_reply_true_no = "checked='checked'";
    if ($edit_true) $edit_true_yes = "checked='checked'";
    else $edit_true_no = "checked='checked'";
    if ($view_recybin) $open_view_recybin = "checked='checked'";
    else $close_view_recybin = "checked='checked'";
    if ($move_true) $move_true_yes = "checked='checked'";
    else $move_true_no = "checked='checked'";
    if ($copy_true) $copy_true_yes = "checked='checked'";
    else $copy_true_no = "checked='checked'";
    if ($ztop_true) $ztop_true_yes = "checked='checked'";
    else $ztop_true_no = "checked='checked'";
    if ($ctop_true) $ctop_true_yes = "checked='checked'";
    else $ctop_true_no = "checked='checked'";
    if ($uptop_true) $uptop_true_yes = "checked='checked'";
    else $uptop_true_no = "checked='checked'";
    if ($bold_true) $bold_true_yes = "checked='checked'";
    else $bold_true_no = "checked='checked'";
    if ($sej_true) $sej_true_yes = "checked='checked'";
    else $sej_true_no = "checked='checked'";
    if ($autorip_true) $autorip_true_yes = "checked='checked'";
    else $autorip_true_no = "checked='checked'";
    if ($ttop_true) $ttop_true_yes = "checked='checked'";
    else $ttop_true_no = "checked='checked'";
    if ($modcenter_true) $modcenter_true_yes = "checked='checked'";
    else $modcenter_true_no = "checked='checked'";
    if ($modano_true) $modano_true_yes = "checked='checked'";
    else $modano_true_no = "checked='checked'";
    if ($modban_true) $modban_true_yes = "checked='checked'";
    else $modban_true_no = "checked='checked'";
    if ($clean_true) $clean_true_yes = "checked='checked'";
    else $clean_true_no = "checked='checked'";
    if ($showpic) $open_showpic = "checked='checked'";
    else $close_showpic = "checked='checked'";
    if ($del_self_topic) $del_self_topic_yes = "checked='checked'";
    else $del_self_topic_no = "checked='checked'";
    if ($del_self_post) $del_self_post_yes = "checked='checked'";
    else $del_self_post_no = "checked='checked'";
    if ($del_self_reth) $del_self_reth_yes = "checked='checked'";
    else $del_self_reth_no = "checked='checked'";
    if ($bmfcode_post['pic']) $post_pic_open = "checked='checked'";
    else $post_pic_close = "checked='checked'";
    if ($bmfcode_post['reply']) $post_reply_open = "checked='checked'";
    else $post_reply_close = "checked='checked'";
    if ($bmfcode_post['jifen']) $post_jifen_open = "checked='checked'";
    else $post_jifen_close = "checked='checked'";
    if ($bmfcode_post['sell']) $post_sell_open = "checked='checked'";
    else $post_sell_close = "checked='checked'";
    if ($bmfcode_post['hmoney']) $post_hmoney_open = "checked='checked'";
    else $post_hmoney_close = "checked='checked'";
    if ($bmfcode_post['hpost']) $post_hpost_open = "checked='checked'";
    else $post_hpost_close = "checked='checked'";
    if ($bmfcode_post['flash']) $post_flash_open = "checked='checked'";
    else $post_flash_close = "checked='checked'";
    if ($bmfcode_post['mpeg']) $post_mpeg_open = "checked='checked'";
    else $post_mpeg_close = "checked='checked'";
    if ($bmfcode_post['iframe']) $post_iframe_open = "checked='checked'";
    else $post_iframe_close = "checked='checked'";
    if ($bmfcode_post['fontsize']) $post_fontsize_open = "checked='checked'";
    else $post_fontsize_close = "checked='checked'";
    if ($allow_forb_ub) $allow_forb_ub_open = "checked='checked'";
    else $allow_forb_ub_close = "checked='checked'";
    if ($can_visual_post) $can_visual_post_open = "checked='checked'";
    else $can_visual_post_close = "checked='checked'";
    if ($member_list) $open_member_list = "checked='checked'";
    else $close_member_list = "checked='checked'";
    if ($search_fun) $open_search_fun = "checked='checked'";
    else $close_search_fun = "checked='checked'";
    if ($nwpost_list) $open_nwpost_list = "checked='checked'";
    else $close_nwpost_list = "checked='checked'";
    if ($porank_list) $open_porank_list = "checked='checked'";
    else $close_porank_list = "checked='checked'";
    if ($set_a_tags) $open_set_a_tags = "checked='checked'";
    else $close_set_a_tags = "checked='checked'";
    if ($see_a_tags) $open_see_a_tags = "checked='checked'";
    else $close_see_a_tags = "checked='checked'";
    if ($down_attach) $open_down_attach = "checked='checked'";
    else $close_down_attach = "checked='checked'";
    if ($gvf) $allow_open_gvf = "checked='checked'";
    else $cannot_open_gvf = "checked='checked'";
    if ($see_amuser) $see_amuser_yes = "checked='checked'";
    else $see_amuser_no = "checked='checked'"; 
    // Convert all switch
    for ($x = 0;$x < count($thistype); $x++){
        if ($thistype[$x]) $switch_on[$x] = "checked='checked'";
            else $switch_off[$x] = "checked='checked'";
    }
    
    $curl_check = function_exists("curl_setopt") ? $arr_ad_lng[94] : $arr_ad_lng[95];
    
    // Convert Point 1/10
    $deljifen = $deljifen / 10;
    $post_jifen = $post_jifen / 10;
    $post_jifen_reply = $post_jifen_reply / 10;
    $browse_add_point = $browse_add_point / 10;
    $browse_limit_point = $thistype[108] / 10;
    $thistype[114] = $thistype[114] / 10;
    
    if ($id == 6 && $level != 1) $g_g = 1;
    
    $thistype[115] = $thistype[115] * 1;
    
    $level_group = ($level == 1) ? "$arr_ad_lng[340] [$levelname] $arr_ad_lng[1175]" : "$arr_ad_lng[340] [$groupname] $arr_ad_lng[234]";
    
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[339]</strong>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$level_group</strong>
		</td></tr>

		</tr>
  <tr>
  <td bgcolor=#FFFFFF valign=middle align=left colspan=2 style="border-bottom: #C47508 1px soild;">
  <font color=#333333><strong><a name="top">$arr_ad_lng[964]</a>
  </td></tr>
  <tr bgcolor=#FFC96B>
   <td colspan=2 style="border: #C47508 1px soild;"><a href="#basicinfo">$arr_ad_lng[987]</a> | <a href="#postallow">$arr_ad_lng[989]</a> | <a href="#profile">$arr_ad_lng[991]</a> | <a href="#browseallow">$arr_ad_lng[993]</a> | <a href="#functionallow">$arr_ad_lng[995]</a> | <a href="#postjifen">$arr_ad_lng[996]</a><br /><a href="#manage">$arr_ad_lng[997]</a> | <a href="#admin">$arr_ad_lng[998]</a></td>
  </tr>
$tips_membergroups
  	   
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='basicinfo'>$arr_ad_lng[986]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

	   
<form action="admin.php?bmod=$thisprog" method="post"><input type=hidden name="targetid" value="$targetid"><input type=hidden name="action" value="process"><input type=hidden name="id" value="$id"><input type=hidden name="issysteam" value="$systemg">
EOT;

    if ($targetid == "" && $level != 1) {
        print <<<EOT
	<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[80]</td>
   <td><input size=35 value="$groupname" name=setting[a21]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[81]</td>
   <td><input size=35 value="$groupimg" name=setting[a22]></td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[82]</td>
   <td><input size=35 value="$groupimg2" name=setting[a23]></td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[83]</td>
   <td><input type=radio $open_showpic value='1' name=setting[xa1]> $arr_ad_lng[84] <input $close_showpic type=radio value='0' name=setting[xa1]> $arr_ad_lng[85]</td>
  </tr>
EOT;
    } else {
        print <<<EOT
<input type=hidden name="setting[a21]" value="$groupname">
<input type=hidden name="setting[a22]" value="$groupimg">
<input type=hidden name="setting[a23]" value="$groupimg2">
EOT;
    } 
print<<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='postallow'>$arr_ad_lng[988]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

EOT;
    if ($g_g != 1) {
        print <<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[86]</td>
   <td>
    <input type=radio $open_canpost value='1' name=setting[a1]> $arr_ad_lng[87] <input $close_canpost type=radio value='0' name=setting[a1]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$post_allow_ww" name=setting[p1]>
   </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[89]</td>
   <td>
    <input type=radio $open_canreply value='1' name=setting[a2]> $arr_ad_lng[87] <input $close_canreply type=radio value='0' name=setting[a2]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$re_allow_ww" name=setting[p2]>
   </td>
  </tr>

 <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[90]</td>
   <td>
    <input type=radio $open_canpoll value='1' name=setting[a3]> $arr_ad_lng[87] <input $close_canpoll type=radio value='0' name=setting[a3]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$poll_allow_ww" name=setting[p3]>
   </td>
  </tr> <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[91]</td>   <td>
    <input type=radio $open_canvote value='1' name=setting[a4]> $arr_ad_lng[87] <input $close_canvote type=radio value='0' name=setting[a4]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$vote_allow_ww" name=setting[p4]>
   </td>
  </tr> 

    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[121]</td>
   <td><input type=text value="$post_sell_max" name=setting[a787]></td>
  </tr>
      	    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[126]</td>
   <td><input size=35 value="$logon_post_second" name=setting[a228]></td>
  </tr>

EOT;
	}
print<<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1210]</td>
   <td>
    <input type=radio $switch_on[126] value='1' name=setting[srl8]> $arr_ad_lng[94] <input $switch_off[126] type=radio value='0' name=setting[srl8]> $arr_ad_lng[95]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1211]</td>
   <td>
    <input type=radio $switch_on[127] value='1' name=setting[srl9]> $arr_ad_lng[94] <input $switch_off[127] type=radio value='0' name=setting[srl9]> $arr_ad_lng[95]
   </td>
</tr> 
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1128]</td>
   <td><input type=text value="$thistype[116]" name=setting[epl1]></td>
  </tr>
    <tr bgcolor=F9FCFE>
   <td>$arr_ad_lng[1129]</td>
   <td><input type=text value="$thistype[117]" name=setting[epl2]></td>
  </tr>
  		<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[956]</td>
   <td><input size=35 value="$max_tags_num" name=setting[tag12]></td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[954]</td>
   <td>
    <input type=radio $open_set_a_tags value='1' name=setting[tag10]> $arr_ad_lng[87] <input $close_set_a_tags type=radio value='0' name=setting[tag10]> $arr_ad_lng[88]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1043]</td>
   <td>
    <input type=radio $switch_on[109] value='1' name=setting[tag13]> $arr_ad_lng[94] <input $switch_off[109] type=radio value='0' name=setting[tag13]> $arr_ad_lng[95]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1044]</td>
   <td>
    <input type=radio $switch_on[110] value='1' name=setting[tag14]> $arr_ad_lng[94] <input $switch_off[110] type=radio value='0' name=setting[tag14]> $arr_ad_lng[95]
   </td>
</tr> 

<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[127]</td>
   <td><input size=35 value="$max_upload_num" name=setting[a25]></td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[128]</td>
   <td><input type=radio value=1 $allow_upload_yes name=setting[ac34]>$arr_ad_lng[94] <input type=radio value=0 $allow_upload_no name=setting[ac34]>$arr_ad_lng[95] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1206] $curl_check</td>
   <td><input type=radio value=1 $switch_on[120] name=setting[ru11]>$arr_ad_lng[94] <input type=radio value=0 $switch_off[120] name=setting[ru11]>$arr_ad_lng[95] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[129]</td>
   <td><input size=35 value="$max_upload_post" name=setting[ac71]></td>
  </tr>
  	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[130]</td>
   <td><input size=35 value="$upload_type_available" name=setting[a18]></td>
  </tr> 
  	    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[123]</td>
   <td><input size=35 value="$max_upload_size" name=setting[a17]></td>
  </tr>
    	    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[124]</td>
   <td><input size=35 value="$max_daily_upload_size" name=setting[a227]></td>
  </tr>
 <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[120]</td>
   <td><input type=radio value=yes $open_html name=setting[a26]>$arr_ad_lng[87] <input type=radio value=no $close_html name=setting[a26]>$arr_ad_lng[88] </td>
  </tr>	
     	    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[125]</td>
   <td><input type=radio value=1 $can_visual_post_open name=setting[vcp]>$arr_ad_lng[94] <input type=radio value=0 $can_visual_post_close name=setting[vcp]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[104]</td>
   <td><input size=35 value="$max_post_length" name=setting[a12]></td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1006]</td>
   <td><input size=35 value="$min_post_length" name=setting[mpl1]></td>
  </tr>

    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1007]</td>
   <td><input size=35 value="$max_post_title" name=setting[mpl2]></td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1008]</td>
   <td><input size=35 value="$max_post_des" name=setting[mpl3]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[105]</td>
   <td><input type=radio value=1 $post_pic_open name=setting[pa18]>$arr_ad_lng[94] <input type=radio value=0 $post_pic_close name=setting[pa18]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[106]</td>
   <td><input type=radio value=1 $post_reply_open name=setting[pa85]>$arr_ad_lng[94] <input type=radio value=0 $post_reply_close name=setting[pa85]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[107]</td>
   <td><input type=radio value=1 $post_jifen_open name=setting[pa86]>$arr_ad_lng[94] <input type=radio value=0 $post_jifen_close name=setting[pa86]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[108]</td>
   <td><input type=radio value=1 $post_hpost_open name=setting[pnew1]>$arr_ad_lng[94] <input type=radio value=0 $post_hpost_close name=setting[pnew1]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[109]</td>
   <td><input type=radio value=1 $post_hmoney_open name=setting[pnew2]>$arr_ad_lng[94] <input type=radio value=0 $post_hmoney_close name=setting[pnew2]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[110]</td>
   <td><input type=radio value=1 $post_sell_open name=setting[pa87]>$arr_ad_lng[94] <input type=radio value=0 $post_sell_close name=setting[pa87]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[111]</td>
   <td><input type=radio value=1 $post_flash_open name=setting[pa19]>$arr_ad_lng[94] <input type=radio value=0 $post_flash_close name=setting[pa19]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1127]</td>
   <td><input size="35" type="text" value="$thistype[115]" name="setting[table]" /></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[112]</td>
   <td><input type=radio value=1 $post_mpeg_open name=setting[pa37]>$arr_ad_lng[94] <input type=radio value=0 $post_mpeg_close name=setting[pa37]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[113]</td>
   <td><input type=radio value=1 $post_iframe_open name=setting[pa38]>$arr_ad_lng[94] <input type=radio value=0 $post_iframe_close name=setting[pa38]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[114]</td>
   <td><input type=radio value=1 $post_fontsize_open name=setting[pa20]>$arr_ad_lng[94] <input type=radio value=0 $post_fontsize_close name=setting[pa20]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[115]</td>
   <td><input type=radio value=1 $allow_forb_ub_open name=setting[fb1]>$arr_ad_lng[94] <input type=radio value=0 $allow_forb_ub_close name=setting[fb1]>$arr_ad_lng[95] </td>
  </tr>
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='profile'>$arr_ad_lng[990]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

EOT;
    if ($targetid == "") {
        print <<<EOT
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[135]</td>
   <td><input type=radio value=1 $allow_open_gvf name=setting[oa5]>$arr_ad_lng[94] <input type=radio value=0 $cannot_open_gvf name=setting[oa5]>$arr_ad_lng[95] </td>
  </tr>
EOT;
	}
    if ($targetid == "" && $g_g != 1) {
        print <<<EOT
   
  			  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[92]</td>
   <td><input size=35 value="$max_sign_length" name=setting[a5]></td>
  </tr>
  	       <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[122]</td>
   <td><input type=radio value=1 $open_swf name=setting[a16]>$arr_ad_lng[87] <input type=radio value=0 $close_swf name=setting[a16]>$arr_ad_lng[88] </td>
  </tr>
    	<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[118]</td>
   <td><input type=radio value=1 $use_own_portait_yes name=setting[a15]>$arr_ad_lng[94] <input type=radio value=0 $use_own_portait_no name=setting[a15]>$arr_ad_lng[95] </td>
  </tr>
EOT;
    } 
echo <<<EOT
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[93]</td>
   <td><input type=radio value=1 $sign_bmfcode name=setting[a6]>$arr_ad_lng[94] <input type=radio value=0 $sign_no_bmfcode name=setting[a6]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[96]</td>
   <td><input type=radio value=1 $sign_pic_open name=setting[a7]>$arr_ad_lng[94] <input type=radio value=0 $sign_pic_close name=setting[a7]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[97]</td>
   <td><input type=radio value=1 $sign_flash_open name=setting[a8]>$arr_ad_lng[94] <input type=radio value=0 $sign_flash_close name=setting[a8]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[98]</td>
   <td><input type=radio value=1 $sign_fontsize_open name=setting[a9]>$arr_ad_lng[94] <input type=radio value=0 $sign_fontsize_close name=setting[a9]>$arr_ad_lng[95] </td>
  </tr>
EOT;
    if ($targetid == "" && $g_g != 1) {
        print <<<EOT
        	
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[136]</td>
   <td><input type=radio value=1 $open_cutusericon name=setting[ac60]>$arr_ad_lng[94] <input type=radio value=0 $close_cutusericon name=setting[ac60]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[137]</td>
   <td><input type=radio value=1 $open_upusericon name=setting[ac76]>$arr_ad_lng[94] <input type=radio value=0 $close_upusericon name=setting[ac76]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[138]</td>
   <td><input size=35 value="$max_avatars_upload_size" name=setting[ac77]></td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[139]</td>
   <td><input size=35 value="$max_avatars_upload_post" name=setting[ac78]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[140]</td>
   <td><input size=35 value="$upload_avatars_type_available" name=setting[ac79]></td>
  </tr>
EOT;
    } 

print<<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='browseallow'>$arr_ad_lng[992]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1173]</td>
   <td>
    <input type="radio" $switch_off[118] value='0' name="setting[noeb]" /> $arr_ad_lng[87] <input $switch_on[118] type="radio" value='1' name="setting[noeb]" /> $arr_ad_lng[88] $arr_ad_lng[912] <input size="5" value="$thistype[119]" name="setting[peb]" />
   </td>
  </tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[101]</td>
   <td>
    <input type=radio $open_view_list value='1' name=setting[an2510]> $arr_ad_lng[87] <input $close_view_list type=radio value='0' name=setting[an2510]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$forum_allow_ww" name=setting[p7]>
   </td>
  </tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[102]</td>
   <td>
    <input type=radio $open_view_recybin value='1' name=setting[h04]> $arr_ad_lng[87] <input $close_view_recybin type=radio value='0' name=setting[h04]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$recy_allow_ww" name=setting[p8]>
   </td>
  </tr> 
  			<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[103]</td>
   <td>
    <input type=radio $open_p_read_post value='1' name=setting[an2511]> $arr_ad_lng[87] <input $close_p_read_post type=radio value='0' name=setting[an2511]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$read_allow_ww" name=setting[p9]>
   </td>
  </tr> 
  	<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[955]</td>
   <td>
    <input type=radio $open_see_a_tags value='1' name=setting[tag11]> $arr_ad_lng[87] <input $close_see_a_tags type=radio value='0' name=setting[tag11]> $arr_ad_lng[88]
   </td>
  </tr> 
EOT;
    if ($targetid == "") {
        print <<<EOT
  			<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[99]</td>
   <td>
    <input type=radio $open_enter_tb value='1' name=setting[a10]> $arr_ad_lng[87] <input $close_enter_tb type=radio value='0' name=setting[a10]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$enter_allow_ww" name=setting[p5]>
   </td>
  </tr>  
EOT;
    } else {
        print <<<EOT
  			<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[341]</td>
   <td>
    <input type=radio $open_enter_tb value='1' name=setting[a10]> $arr_ad_lng[87] <input $close_enter_tb type=radio value='0' name=setting[a10]> $arr_ad_lng[88]
   </td>
  </tr> 
EOT;
    } 
    if ($targetid == "") {
        print <<<EOT
	
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[131]</td>
   <td><input type=radio value=1 $open_member_list name=setting[oa1]>$arr_ad_lng[94] <input type=radio value=0 $close_member_list name=setting[oa1]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[132]</td>
   <td><input type=radio value=1 $open_search_fun name=setting[oa2]>$arr_ad_lng[94] <input type=radio value=0 $close_search_fun name=setting[oa2]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[133]</td>
   <td><input type=radio value=1 $open_nwpost_list name=setting[oa3]>$arr_ad_lng[94] <input type=radio value=0 $close_nwpost_list name=setting[oa3]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[134]</td>
   <td><input type=radio value=1 $open_porank_list name=setting[oa4]>$arr_ad_lng[94] <input type=radio value=0 $close_porank_list name=setting[oa4]>$arr_ad_lng[95] </td>
  </tr>

EOT;
    } 

print <<<EOT
  			<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[915]</td>
   <td>
    <input type=radio $open_down_attach value='1' name=setting[p10]> $arr_ad_lng[87] <input $close_down_attach type=radio value='0' name=setting[p10]> $arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$down_attach_ww" name=setting[p11]>
   </td>
  </tr> 

	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='functionallow'>$arr_ad_lng[994]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

  	  
EOT;
    if ($targetid == "" && $g_g != 1) {
        print <<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[100]</td>
   <td><input type=radio value=1 $send_msg_yes name=setting[a11]>$arr_ad_lng[87] <input type=radio value=0 $send_msg_no name=setting[a11]>$arr_ad_lng[88] $arr_ad_lng[912] <input size=5 value="$pri_allow_ww" name=setting[p6]></td>
  </tr> 
EOT;
	}
    print <<<EOT

  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[150]</td>
   <td><input type=radio value=1 $del_self_topic_yes name=setting[ds1]>$arr_ad_lng[87] <input type=radio value=0 $del_self_topic_no name=setting[ds1]>$arr_ad_lng[88] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1037]</td>
   <td><input type=radio value=1 $del_self_reth_yes name=setting[ds3]>$arr_ad_lng[87] <input type=radio value=0 $del_self_reth_no name=setting[ds3]>$arr_ad_lng[88] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[151]</td>
   <td><input type=radio value=1 $del_self_post_yes name=setting[ds2]>$arr_ad_lng[87] <input type=radio value=0 $del_self_post_no name=setting[ds2]>$arr_ad_lng[88] </td>
  </tr>
EOT;
    if ($targetid == "" && $g_g != 1) {
        print <<<EOT
  	    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[116]</td>
   <td><input size=35 value="$short_msg_max" name=setting[a13]></td>
  </tr>
  	  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[117]</td>
   <td><input size=35 value="$send_msg_max" name=setting[a14]></td>
  </tr>  
   	<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[119]</td>
   <td><input type=radio value=1 $see_amuser_yes name=setting[uu1]>$arr_ad_lng[94] <input type=radio value=0 $see_amuser_no name=setting[uu1]>$arr_ad_lng[95] </td>
  </tr>

EOT;
    } 


    print <<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='postjifen'>$arr_ad_lng[143]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>


  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[144]</td>
   <td><input size=35 value="$delrmb" name=setting[an52]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[145]</td>
   <td><input size=35 value="$post_money" name=setting[an51]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[146]</td>
   <td><input size=35 value="$post_money_reply" name=setting[r1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[147]</td>
   <td><input size=35 value="$deljifen" name=setting[an67]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[148]</td>
   <td><input size=35 value="$post_jifen" name=setting[an68]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[149]</td>
   <td><input size=35 value="$post_jifen_reply" name=setting[r2]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1027]</td>
   <td><input size=35 value="$browse_add_point" name=setting[rap1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1040]</td>
   <td><input size=35 value="$browse_limit_point" name=setting[rap6]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1038]</td>
   <td><input size=35 value="$edit_time_limit" name=setting[ds4]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1060]</td>
   <td><input size=35 value="$thistype[114]" name=setting[ban_post]></td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[913]</td>
   <td><input size=4 value="$thistype[121]" name=setting[srl3]> - <input size=4 value="$thistype[122]" name=setting[srl4]></td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[914]</td>
   <td><input size=4 value="$thistype[123]" name=setting[srl5]> - <input size=4 value="$thistype[124]" name=setting[srl6]></td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1207]</td>
   <td>
    <input type=radio $switch_on[125] value='1' name=setting[srl7]> $arr_ad_lng[94] <input $switch_off[125] type=radio value='0' name=setting[srl7]> $arr_ad_lng[95]
    <br />
    <input type="checkbox" $switch_on[130] value='1' name="setting[srl12]" /> $arr_ad_lng[1212]
   </td>
</tr> 

	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='manage'>$arr_ad_lng[152]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>


    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[155]</td>
   <td><input type=radio value=1 $mod_yes name=setting[a24]>$arr_ad_lng[153] <input type=radio value=0 $mod_no name=setting[a24]>$arr_ad_lng[154] </td>
  </tr>
EOT;
    if ($targetid == "" && $g_g != 1) {
        print <<<EOT

  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[156]</td>
   <td><input type=radio value=1 $supermod_yes name=setting[a19]>$arr_ad_lng[153] <input type=radio value=0 $supermod_no name=setting[a19]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[157]</td>
   <td><input type=radio value=1 $admin_yes name=setting[a20]>$arr_ad_lng[153] <input type=radio value=0 $admin_no name=setting[a20]>$arr_ad_lng[154] </td>
  </tr>
EOT;
    } else {
        print <<<EOT
<input type=hidden name="setting[a19]" value="$supermod"><input type=hidden name="setting[a20]" value="$admin">
EOT;
    } 
    print <<<EOT

	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='admin'>$arr_ad_lng[158]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

       <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1047]</td>
   <td><input type=radio value=1 $switch_on[111] name=setting[pay_back]>$arr_ad_lng[153] <input type=radio value=0 $switch_off[111] name=setting[pay_back]>$arr_ad_lng[154] </td>
  </tr>
       <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[159]</td>
   <td><input type=radio value=1 $lock_true_yes name=setting[b1]>$arr_ad_lng[153] <input type=radio value=0 $lock_true_no name=setting[b1]>$arr_ad_lng[154] </td>
  </tr>
        <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[160]</td>
   <td><input type=radio value=1 $del_reply_true_yes name=setting[b2]>$arr_ad_lng[153] <input type=radio value=0 $del_reply_true_no name=setting[b2]>$arr_ad_lng[154] </td>
  </tr>
          <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[161]</td>
   <td><input type=radio value=1 $edit_true_yes name=setting[b3]>$arr_ad_lng[153] <input type=radio value=0 $edit_true_no name=setting[b3]>$arr_ad_lng[154] </td>
  </tr>
           <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[162]</td>
   <td><input type=radio value=1 $move_true_yes name=setting[b4]>$arr_ad_lng[153] <input type=radio value=0 $move_true_no name=setting[b4]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[164]</td>
   <td><input type=radio value=1 $ztop_true_yes name=setting[b6]>$arr_ad_lng[153] <input type=radio value=0 $ztop_true_no name=setting[b6]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[165]</td>
   <td><input type=radio value=1 $ctop_true_yes name=setting[b7]>$arr_ad_lng[153] <input type=radio value=0 $ctop_true_no name=setting[b7]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[166]</td>
   <td><input type=radio value=1 $ttop_true_yes name=setting[b12]>$arr_ad_lng[153] <input type=radio value=0 $ttop_true_no name=setting[b12]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[167]</td>
   <td><input type=radio value=1 $uptop_true_yes name=setting[b8]>$arr_ad_lng[153] <input type=radio value=0 $uptop_true_no name=setting[b8]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[168]</td>
   <td><input type=radio value=1 $bold_true_yes name=setting[b9]>$arr_ad_lng[153] <input type=radio value=0 $bold_true_no name=setting[b9]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[169]</td>
   <td><input type=radio value=1 $sej_true_yes name=setting[b10]>$arr_ad_lng[153] <input type=radio value=0 $sej_true_no name=setting[b10]>$arr_ad_lng[154] </td>
  </tr>
      <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[170]</td>
   <td><input type=radio value=1 $autorip_true_yes name=setting[b11]>$arr_ad_lng[153] <input type=radio value=0 $autorip_true_no name=setting[b11]>$arr_ad_lng[154] </td>
  </tr>
         <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[171]</td>
   <td><input type=radio value=1 $modcenter_true_yes name=setting[b13]>$arr_ad_lng[153] <input type=radio value=0 $modcenter_true_no name=setting[b13]>$arr_ad_lng[154] </td>
  </tr>
          <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[172]</td>
   <td><input type=radio value=1 $modano_true_yes name=setting[b14]>$arr_ad_lng[153] <input type=radio value=0 $modano_true_no name=setting[b14]>$arr_ad_lng[154] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[173]</td>
   <td><input type=radio value=1 $modban_true_yes name=setting[b15]>$arr_ad_lng[153] <input type=radio value=0 $modban_true_no name=setting[b15]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[174]</td>
   <td><input type=radio value=1 $del_true_yes name=setting[a234]>$arr_ad_lng[153] <input type=radio value=0 $del_true_no name=setting[a234]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[175]</td>
   <td><input type=radio value=1 $clean_true_yes name=setting[s1]>$arr_ad_lng[153] <input type=radio value=0 $clean_true_no name=setting[s1]>$arr_ad_lng[154] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[176]</td>
   <td><input type=radio value=1 $del_rec_yes name=setting[a139]>$arr_ad_lng[153] <input type=radio value=0 $del_rec_no name=setting[a139]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[177]</td>
   <td><input type=radio value=1 $can_rec_yes name=setting[a230]>$arr_ad_lng[153] <input type=radio value=0 $can_rec_no name=setting[a230]>$arr_ad_lng[154] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1049]</td>
   <td><input type="radio" value="1" $switch_on[112] name="setting[recycle_p]" />$arr_ad_lng[153] <input type="radio" value="0" $switch_off[112] name="setting[recycle_p]" />$arr_ad_lng[154] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1208]</td>
   <td>
    <input type=radio $switch_on[128] value='1' name=setting[srl10]>$arr_ad_lng[153] <input $switch_off[128] type=radio value='0' name=setting[srl10]>$arr_ad_lng[154]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1209]</td>
   <td>
    <input type=radio $switch_on[129] value='1' name=setting[srl11]>$arr_ad_lng[153] <input $switch_off[129] type=radio value='0' name=setting[srl11]>$arr_ad_lng[154]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1050]</td>
   <td><input type="radio" value="1" $switch_on[113] name="setting[split_p]" />$arr_ad_lng[153] <input type="radio" value="0" $switch_off[113] name="setting[split_p]" />$arr_ad_lng[154] 
   	   $table_start <input type="hidden" name="levelname" value="$levelname" /><input type="hidden" name="level" value="$level" /><input type="submit" value="$arr_ad_lng[66]" /> <input type="reset" value="$arr_ad_lng[178]" />
  </tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process" || $action == "delete") {
	
	if ($action == "delete" && $step != 2) {
		
    for ($i = 0; $i < $ugsocount ; $i++) {
        // Print usergroup table
        $getit = $ugshoworder[$i];
        $detail = explode("|", $usergroupdata[$getit]);
        if (!empty($detail[0]) && $getit != $id) {
        	$selecter.= "<option value='$getit'>$detail[0]</option>";
        }
    }

		
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
	<form action="admin.php?bmod=$thisprog&step=2&id=$id&action=delete&targetid=$targetid" method="post">
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[821]</strong>
    </td>
    </tr>          
$table_start
		$arr_ad_lng[1169]
$table_stop
		<input type="radio" name="dtype" checked="checked" value="1" />$arr_ad_lng[1171] <select name="newgroup">{$selecter}</select>
		<br />
		<input type="radio" name="dtype" value="2" />$arr_ad_lng[1170]
		<br />
		<input type="radio" name="dtype" value="3" />$arr_ad_lng[1172]
		<br /><br />
		<input type="submit" value="$arr_ad_lng[774]" />
		</td></tr></table></td></tr></table></form></body></html>
EOT;
		exit;
	}
	
	if ($action == "delete") {
		if ($dtype == 2) {
			bmbdb_query("DELETE FROM {$database_up}userlist WHERE `ugnum` = '$id'");
		} elseif ($dtype == 1) { 
			bmbdb_query("UPDATE {$database_up}userlist SET `ugnum` = '$newgroup' WHERE `ugnum` = '$id'");
		}
	}
	
    // Safety Checks
    $bannedexist = array("php", "php4", "php3", "cgi", "pl", "asp", "aspx", "cfm", "shtml"); // Ban List
    
    $tmpexitscheck = explode(" ", $setting[a18]);
    $ccoun = count($tmpexitscheck);
    for($c = 0;$c < $ccoun;$c++) {
        if (in_array($tmpexitscheck[$c], $bannedexist)) unset($tmpexitscheck[$c]);
    } 
    $setting[a18] = implode(" ", $tmpexitscheck); 
    // Avarta.
    $tmpexitscheck = explode(" ", $setting[ac79]);
    $ccoun = count($tmpexitscheck);
    for($c = 0;$c < $ccoun;$c++) {
        if (in_array($tmpexitscheck[$c], $bannedexist)) unset($tmpexitscheck[$c]);
    } 
    $setting[ac79] = implode(" ", $tmpexitscheck); 
    // S.END
    $setting[an67] = $setting[an67] * 10;
    $setting[an68] = $setting[an68] * 10;
    $setting[r2] = $setting[r2] * 10;
    $setting[rap1] = $setting[rap1] * 10;
    $setting[rap6] = $setting[rap6] * 10;
    $setting[ban_post] = $setting[ban_post] * 10;

    if (!empty($targetid) && $level != 1) {
        foreach ($usergroupdata as $key=>$value) {
            $de = explode("|", $value);
            $cous = count($de);
            for($ax = 0;$ax < $cous;$ax++) {
                $de[$ax] = str_replace("\n", "", $de[$ax]);
            } 
            if ($id == $key) {
                if ($action != "delete") {
                    $new .= $setting[a21] . "|" . $setting[a22] . "|" . $de[2] . "|" . $setting[a1] . "|" . $setting[a2] . "|" . $setting[a3] . "|" . $setting[a4] . "|" . $setting[a5] . "|" . $setting[a6] . "|" . $setting[a7] . "|" . $setting[a8] . "|" . $setting[a9] . "|" . $setting[a10] . "|" . $setting[a11] . "|" . $setting[a12] . "|" . $setting[a13] . "|" . $setting[a14] . "|" . $setting[a15] . "|" . $setting[a16] . "|" . $setting[a17] . "|" . $setting[a18] . "|" . $setting[a19] . "|" . $setting[a20] . "|" . $setting[a23] . "|" . $setting[a24] . "|" . $setting[a25] . "|" . $setting[a26] . "|" . $setting[a227] . "|" . $setting[a228] . "|" . $setting[a787] . "|" . $setting[a234] . "|" . $setting[a139] . "|" . $setting[a230] . "|" . $setting[an52] . "|" . $setting[an51] . "|" . $setting[an67] . "|" . $setting[an68] . "|" . $setting[ac34] . "|" . $setting[ac71] . "|" . $setting[ac60] . "|" . $setting[ac76] . "|" . $setting[ac77] . "|" . $setting[ac78] . "|" . $setting[ac79] . "|" . $setting[ac58] . "|" . $setting[ac59] . "|" . $setting[an2511] . "|" . $setting[an2510] . "|" . $setting[b1] . "|" . $setting[b2] . "|" . $setting[b3] . "|" . $setting[b4] . "|" . $setting[b5] . "|" . $setting[b6] . "|" . $setting[b7] . "|" . $setting[b8] . "|" . $setting[b9] . "|" . $setting[b10] . "|" . $setting[b11] . "|" . $setting[b12] . "|" . $setting[b13] . "|" . $setting[b14] . "|" . $setting[b15] . "|" . $setting[s1] . "|" . $setting[xa1] . "|" . $setting[r1] . "|" . $setting[r2] . "|" . $setting[ds1] . "|" . $setting[ds2] . "|" . $setting[pa18] . "|" . $setting[pa85] . "|" . $setting[pa86] . "|" . $setting[pa87] . "|" . $setting[pa19] . "|" . $setting[pa37] . "|" . $setting[pa38] . "|" . $setting[pa20] . "|" . $setting[pnew1] . "|" . $setting[pnew2] . "|" . $setting[fb1] . "|" . $setting[vcp] . "|" . $setting[oa1] . "|" . $setting[oa2] . "|" . $setting[oa3] . "|" . $setting[oa4] . "|" . $setting[oa5] . "|" . $setting[uu1] . "|" . $setting[h04] . "|" . $setting[p1] . "|" . $setting[p2] . "|" . $setting[p3] . "|" . $setting[p4] . "|" . $setting[p5] . "|" . $setting[p6] . "|" . $setting[p7] . "|" . $setting[p8] . "|" . $setting[p9] . "|" . $setting[p10] . "|" . $setting[p11] . "|" . $setting[tag10] . "|" . $setting[tag11] . "|" . $setting[tag12] . "|" . $setting[mpl1] . "|" . $setting[mpl2] . "|" . $setting[mpl3] . "|" . $setting[rap1] . "|" . $setting[ds3] . "|" . $setting[ds4] . "|" . $setting[rap6] . "|" . $setting[tag13] . "|" . $setting[tag14] . "|" . $setting[pay_back] . "|" . $setting[recycle_p] . "|" . $setting[split_p] . "|" . $setting[ban_post] . "|" . $setting[table] . "|" . $setting[epl1] . "|" . $setting[epl2] . "|" . $setting[noeb] . "|" . $setting[peb] . "|" . $setting[ru11] . "|" . $setting[srl3] . "|" . $setting[srl4] . "|" . $setting[srl5] . "|" . $setting[srl6] . "|" . $setting[srl7] . "|" . $setting[srl8] . "|" . $setting[srl9] . "|" . $setting[srl10] . "|" . $setting[srl11] . "|" . $setting[srl12] . "|\n";
                } 
            } else {
                $new .= $de[0] . "|" . $de[1] . "|" . $de[2] . "|" . $de[3] . "|" . $de[4] . "|" . $de[5] . "|" . $de[6] . "|" . $de[7] . "|" . $de[8] . "|" . $de[9] . "|" . $de[10] . "|" . $de[11] . "|" . $de[12] . "|" . $de[13] . "|" . $de[14] . "|" . $de[15] . "|" . $de[16] . "|" . $de[17] . "|" . $de[18] . "|" . $de[19] . "|" . $de[20] . "|" . $de[21] . "|" . $de[22] . "|" . $de[23] . "|" . $de[24] . "|" . $de[25] . "|" . $de[26] . "|" . $de[27] . "|" . $de[28] . "|" . $de[29] . "|" . $de[30] . "|" . $de[31] . "|" . $de[32] . "|" . $de[33] . "|" . $de[34] . "|" . $de[35] . "|" . $de[36] . "|" . $de[37] . "|" . $de[38] . "|" . $de[39] . "|" . $de[40] . "|" . $de[41] . "|" . $de[42] . "|" . $de[43] . "|" . $de[44] . "|" . $de[45] . "|" . $de[46] . "|" . $de[47] . "|" . $de[48] . "|" . $de[49] . "|" . $de[50] . "|" . $de[51] . "|" . $de[52] . "|" . $de[53] . "|" . $de[54] . "|" . $de[55] . "|" . $de[56] . "|" . $de[57] . "|" . $de[58] . "|" . $de[59] . "|" . $de[60] . "|" . $de[61] . "|" . $de[62] . "|" . $de[63] . "|" . $de[64] . "|" . $de[65] . "|" . $de[66] . "|" . $de[67] . "|" . $de[68] . "|" . $de[69] . "|" . $de[70] . "|" . $de[71] . "|" . $de[72] . "|" . $de[73] . "|" . $de[74] . "|" . $de[75] . "|" . $de[76] . "|" . $de[77] . "|" . $de[78] . "|" . $de[79] . "|" . $de[80] . "|" . $de[81] . "|" . $de[82] . "|" . $de[83] . "|" . $de[84] . "|" . $de[85] . "|" . $de[86] . "|" . $de[87] . "|" . $de[88] . "|" . $de[89] . "|" . $de[90] . "|" . $de[91] . "|" . $de[92] . "|" . $de[93] . "|" . $de[94] . "|" . $de[95] . "|" . $de[96] . "|" . $de[97] . "|" . $de[98] . "|" . $de[99] . "|" . $de[100] . "|" . $de[101] . "|" . $de[102]  . "|" . $de[103]  . "|" . $de[104]  . "|" . $de[105]  . "|" . $de[106] . "|" . $de[107] . "|" . $de[108] . "|" . $de[109] . "|" . $de[110] . "|" . $de[111] . "|" . $de[112] . "|" . $de[113] . "|" . $de[114] . "|" . $de[115] . "|" . $de[116] . "|" . $de[117] . "|" . $de[118] . "|" . $de[119] . "|" . $de[120] . "|" . $de[121] . "|" . $de[122] . "|" . $de[123] . "|" . $de[124] . "|" . $de[125] . "|" . $de[126] . "|" . $de[127] . "|" . $de[128] . "|" . $de[129] . "|" . $de[130] . "|\n";
            } 
        } 
        $nquery = "UPDATE {$database_up}forumdata SET usergroup = '$new' WHERE id = '$targetid'";
        $result = bmbdb_query($nquery);
        refresh_forumcach();
    } else {
        if ($action != "delete") {
        	if ($level == 1) {
        		$setting[a21] = $levelname;
        	}
        	
            $new = $setting[a21] . "|" . $setting[a22] . "|" . $issysteam . "|" . $setting[a1] . "|" . $setting[a2] . "|" . $setting[a3] . "|" . $setting[a4] . "|" . $setting[a5] . "|" . $setting[a6] . "|" . $setting[a7] . "|" . $setting[a8] . "|" . $setting[a9] . "|" . $setting[a10] . "|" . $setting[a11] . "|" . $setting[a12] . "|" . $setting[a13] . "|" . $setting[a14] . "|" . $setting[a15] . "|" . $setting[a16] . "|" . $setting[a17] . "|" . $setting[a18] . "|" . $setting[a19] . "|" . $setting[a20] . "|" . $setting[a23] . "|" . $setting[a24] . "|" . $setting[a25] . "|" . $setting[a26] . "|" . $setting[a227] . "|" . $setting[a228] . "|" . $setting[a787] . "|" . $setting[a234] . "|" . $setting[a139] . "|" . $setting[a230] . "|" . $setting[an52] . "|" . $setting[an51] . "|" . $setting[an67] . "|" . $setting[an68] . "|" . $setting[ac34] . "|" . $setting[ac71] . "|" . $setting[ac60] . "|" . $setting[ac76] . "|" . $setting[ac77] . "|" . $setting[ac78] . "|" . $setting[ac79] . "|" . $setting[ac58] . "|" . $setting[ac59] . "|" . $setting[an2511] . "|" . $setting[an2510] . "|" . $setting[b1] . "|" . $setting[b2] . "|" . $setting[b3] . "|" . $setting[b4] . "|" . $setting[b5] . "|" . $setting[b6] . "|" . $setting[b7] . "|" . $setting[b8] . "|" . $setting[b9] . "|" . $setting[b10] . "|" . $setting[b11] . "|" . $setting[b12] . "|" . $setting[b13] . "|" . $setting[b14] . "|" . $setting[b15] . "|" . $setting[s1] . "|" . $setting[xa1] . "|" . $setting[r1] . "|" . $setting[r2] . "|" . $setting[ds1] . "|" . $setting[ds2] . "|" . $setting[pa18] . "|" . $setting[pa85] . "|" . $setting[pa86] . "|" . $setting[pa87] . "|" . $setting[pa19] . "|" . $setting[pa37] . "|" . $setting[pa38] . "|" . $setting[pa20] . "|" . $setting[pnew1] . "|" . $setting[pnew2] . "|" . $setting[fb1] . "|" . $setting[vcp] . "|" . $setting[oa1] . "|" . $setting[oa2] . "|" . $setting[oa3] . "|" . $setting[oa4] . "|" . $setting[oa5] . "|" . $setting[uu1] . "|" . $setting[h04] . "|" . $setting[p1] . "|" . $setting[p2] . "|" . $setting[p3] . "|" . $setting[p4] . "|" . $setting[p5] . "|" . $setting[p6] . "|" . $setting[p7] . "|" . $setting[p8] . "|" . $setting[p9] . "|" . $setting[p10] . "|" . $setting[p11] . "|" . $setting[tag10] . "|" . $setting[tag11] . "|" . $setting[tag12] . "|" . $setting[mpl1] . "|" . $setting[mpl2] . "|" . $setting[mpl3] . "|" . $setting[rap1] . "|" . $setting[ds3] . "|" . $setting[ds4] . "|" . $setting[rap6] . "|" . $setting[tag13] . "|" . $setting[tag14] . "|" . $setting[pay_back] . "|" . $setting[recycle_p] . "|" . $setting[split_p] . "|" . $setting[ban_post] . "|" . $setting[table] . "|" . $setting[epl1] . "|" . $setting[epl2] . "|" . $setting[noeb] . "|" . $setting[peb] . "|" . $setting[ru11] . "|" . $setting[srl3] . "|" . $setting[srl4] . "|" . $setting[srl5] . "|" . $setting[srl6] . "|" . $setting[srl7] . "|" . $setting[srl8] . "|" . $setting[srl9] . "|" . $setting[srl10] . "|" . $setting[srl11] . "|" . $setting[srl12];
            
        	if ($level == 1) { 
				if ($targetid != "") { // Forum Level Group
				    $result = bmbdb_fetch_array(bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='$targetid'"));
				} else {
					$result = bmbdb_fetch_array(bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='0'"));
				}
				if ($result['maccess']) {
					bmbdb_query("UPDATE {$database_up}levels SET `maccess`='$new' WHERE `id`='$id' and `fid`='$targetid'");
				} else {
					bmbdb_query("INSERT INTO {$database_up}levels (`id`,`fid`,`maccess`) VALUES ('$id','$targetid','$new')");
				}
				
        	} else {
	            if ($setting[xa1] == 1) $unshowit = 0;
	            else $unshowit = 1;
	            $nquery = "UPDATE {$database_up}usergroup SET unshowit='$unshowit',usersets = '$new' WHERE id = '$id'";
	            $result = bmbdb_query($nquery);
            }
        } else {
            $nquery = "DELETE FROM {$database_up}usergroup WHERE id = '$id'";
            $result = bmbdb_query($nquery);
        } 
    } 

    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=usergroup.php&targetid=$targetid">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
	
	if ($level != 1) {
	    // Refresh Cache
	    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
	    $result = bmbdb_query($query);
	    $ugsocount = "";
	    $wrting = "<?php ";
	    while (false !== ($line = bmbdb_fetch_array($result))) {
	        $line['usersets'] = str_replace('"', '\"', $line['usersets']);
	        $wrting .= "
			\$usergroupdata[{$line['id']}]=\"{$line['usersets']}\";
			\$unshowit[{$line['id']}]=\"{$line['unshowit']}\";
			\$ugshoworder[]=\"{$line['id']}\";
			";
	        $ugsocount++;
	    } 
	    $wrting .= "\$ugsocount='$ugsocount';";
	    writetofile("datafile/cache/usergroup.php", $wrting);
	    exit;
    } else {
    	$targetid = $targetid ? $targetid : 0;
    	
	    $query = "SELECT * FROM {$database_up}levels WHERE `fid`='$targetid' ORDER BY `id` ASC";
	    $result = bmbdb_query($query);
	    $ugsocount = "";
	    $wrting = "<?php ";
	    while (false !== ($line = bmbdb_fetch_array($result))) {
	        $line['maccess'] = str_replace('"', '\"', $line['maccess']);
	        $wrting .= "
\$levelgroupdata[{$targetid}][{$line['id']}]=\"{$line['maccess']}\";
";
	    } 

	    writetofile("datafile/cache/levels/level_fid_{$targetid}.php", $wrting);
	    exit;

    }
} 
