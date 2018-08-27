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

$thisprog = "addusergroup.php";

if ($useraccess != "1" || $admgroupdata[12] != "1") {
    adminlogin();
} 

$usertypelist = ""; // Empty User List

list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $maxwidth, $maxheight, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point, $del_self_reth, $edit_time_limit) = explode("|", $usergroupdata[$uginfo]);
$thistype = explode("|", $usergroupdata[$uginfo]);


// Convert 1/10
$deljifen = $deljifen / 10;
$post_jifen = $post_jifen / 10;
$post_jifen_reply = $post_jifen_reply / 10;
$browse_add_point = $browse_add_point / 10;
$browse_limit_point = $browse_limit_point / 10;

$curl_check = function_exists("curl_setopt") ? $arr_ad_lng[94] : $arr_ad_lng[95];

if (!$action) {
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[77]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[78]</strong>
		</td></tr>

$table_start
    <strong>$arr_ad_lng[79]</strong><form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="process">
   </td>
  </tr>

	<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[80]</td>
   <td><INPUT size=35 value="" name=setting[a21]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[81]</td>
   <td><INPUT size=35 value="$groupimg" name=setting[a22]></td>
  </tr>
     <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[82]</td>
   <td><INPUT size=35 value="$groupimg2" name=setting[a23]></td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[83]</td>
   <td><INPUT type=radio $open_showpic value='1' name=setting[xa1]> $arr_ad_lng[84] <INPUT $close_showpic type=radio value='0' name=setting[xa1]> $arr_ad_lng[85]</td>
  </tr>
<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[86]</td>
   <td>
    <INPUT type=radio $open_canpost value='1' name=setting[a1]> $arr_ad_lng[87] <INPUT $close_canpost type=radio value='0' name=setting[a1]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$post_allow_ww" name=setting[p1]>
   </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[89]</td>
   <td>
    <INPUT type=radio $open_canreply value='1' name=setting[a2]> $arr_ad_lng[87] <INPUT $close_canreply type=radio value='0' name=setting[a2]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$re_allow_ww" name=setting[p2]>
   </td>
  </tr>
 <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[90]</td>
   <td>
    <INPUT type=radio $open_canpoll value='1' name=setting[a3]> $arr_ad_lng[87] <INPUT $close_canpoll type=radio value='0' name=setting[a3]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$poll_allow_ww" name=setting[p3]>
   </td>
  </tr> <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[91]</td>   <td>
    <INPUT type=radio $open_canvote value='1' name=setting[a4]> $arr_ad_lng[87] <INPUT $close_canvote type=radio value='0' name=setting[a4]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$vote_allow_ww" name=setting[p4]>
   </td>
  </tr> 
  			  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[92]</td>
   <td><INPUT size=35 value="$max_sign_length" name=setting[a5]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[93]</td>
   <td><INPUT type=radio value=1 $sign_bmfcode name=setting[a6]>$arr_ad_lng[94] <INPUT type=radio value=0 $sign_no_bmfcode name=setting[a6]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[96]</td>
   <td><INPUT type=radio value=1 $sign_pic_open name=setting[a7]>$arr_ad_lng[94] <INPUT type=radio value=0 $sign_pic_close name=setting[a7]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[97]</td>
   <td><INPUT type=radio value=1 $sign_flash_open name=setting[a8]>$arr_ad_lng[94] <INPUT type=radio value=0 $sign_flash_close name=setting[a8]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[98]</td>
   <td><INPUT type=radio value=1 $sign_fontsize_open name=setting[a9]>$arr_ad_lng[94] <INPUT type=radio value=0 $sign_fontsize_close name=setting[a9]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=F9FCFE>
   <td>$arr_ad_lng[1128]</td>
   <td><input type=text value="$thistype[116]" name=setting[epl1]></td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1129]</td>
   <td><input type=text value="$thistype[117]" name=setting[epl2]></td>
  </tr>
  		<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[956]</td>
   <td><INPUT size=35 value="$max_tags_num" name=setting[tag12]></td>
  </tr>
<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[954]</td>
   <td>
    <INPUT type=radio $open_set_a_tags value='1' name=setting[tag10]> $arr_ad_lng[87] <INPUT $close_set_a_tags type=radio value='0' name=setting[tag10]> $arr_ad_lng[88]
   </td>
</tr> 
<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[1043]</td>
   <td>
    <INPUT type=radio $switch_on[109] value='1' name=setting[tag13]> $arr_ad_lng[94] <INPUT $switch_off[109] type=radio value='0' name=setting[tag13]> $arr_ad_lng[95]
   </td>
</tr> 
<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1044]</td>
   <td>
    <INPUT type=radio $switch_on[110] value='1' name=setting[tag14]> $arr_ad_lng[94] <INPUT $switch_off[110] type=radio value='0' name=setting[tag14]> $arr_ad_lng[95]
   </td>
</tr> 
  	<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[955]</td>
   <td>
    <INPUT type=radio $open_see_a_tags value='1' name=setting[tag11]> $arr_ad_lng[87] <INPUT $close_see_a_tags type=radio value='0' name=setting[tag11]> $arr_ad_lng[88]
   </td>
  </tr> 
  			<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[99]</td>
   <td>
    <INPUT type=radio $open_enter_tb value='1' name=setting[a10]> $arr_ad_lng[87] <INPUT $close_enter_tb type=radio value='0' name=setting[a10]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$enter_allow_ww" name=setting[p5]>
   </td>
  </tr>  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[100]</td>
   <td><INPUT type=radio value=1 $send_msg_yes name=setting[a11]>$arr_ad_lng[87] <INPUT type=radio value=0 $send_msg_no name=setting[a11]>$arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$pri_allow_ww" name=setting[p6]></td>
  </tr>  
  			<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[101]</td>
   <td>
    <INPUT type=radio $open_view_list value='1' name=setting[an2510]> $arr_ad_lng[87] <INPUT $close_view_list type=radio value='0' name=setting[an2510]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$forum_allow_ww" name=setting[p7]>
   </td>
  </tr> 
<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[102]</td>
   <td>
    <INPUT type=radio $open_view_recybin value='1' name=setting[h04]> $arr_ad_lng[87] <INPUT $close_view_recybin type=radio value='0' name=setting[h04]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$recy_allow_ww" name=setting[p8]>
   </td>
  </tr> 
  			<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[103]</td>
   <td>
    <INPUT type=radio $open_p_read_post value='1' name=setting[an2511]> $arr_ad_lng[87] <INPUT $close_p_read_post type=radio value='0' name=setting[an2511]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$read_allow_ww" name=setting[p9]>
   </td>
  </tr> 
  			<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[104]</td>
   <td><INPUT size=35 value="$max_post_length" name=setting[a12]></td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1006]</td>
   <td><INPUT size=35 value="$min_post_length" name=setting[mpl1]></td>
  </tr>

    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[1007]</td>
   <td><INPUT size=35 value="$max_post_title" name=setting[mpl2]></td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1008]</td>
   <td><INPUT size=35 value="$max_post_des" name=setting[mpl3]></td>
  </tr>

     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[105]</td>
   <td><INPUT type=radio value=1 $post_pic_open name=setting[pa18]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_pic_close name=setting[pa18]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[106]</td>
   <td><INPUT type=radio value=1 $post_reply_open name=setting[pa85]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_reply_close name=setting[pa85]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[107]</td>
   <td><INPUT type=radio value=1 $post_jifen_open name=setting[pa86]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_jifen_close name=setting[pa86]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[108]</td>
   <td><INPUT type=radio value=1 $post_hpost_open name=setting[pnew1]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_hpost_close name=setting[pnew1]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[109]</td>
   <td><INPUT type=radio value=1 $post_hmoney_open name=setting[pnew2]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_hmoney_close name=setting[pnew2]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[110]</td>
   <td><INPUT type=radio value=1 $post_sell_open name=setting[pa87]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_sell_close name=setting[pa87]>$arr_ad_lng[95] </td>
  </tr>

  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[111]</td>
   <td><INPUT type=radio value=1 $post_flash_open name=setting[pa19]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_flash_close name=setting[pa19]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[1127]</td>
   <td><input size="35" type="text" value="$thistype[115]" name="setting[table]" /></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[112]</td>
   <td><INPUT type=radio value=1 $post_mpeg_open name=setting[pa37]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_mpeg_close name=setting[pa37]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[113]</td>
   <td><INPUT type=radio value=1 $post_iframe_open name=setting[pa38]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_iframe_close name=setting[pa38]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[114]</td>
   <td><INPUT type=radio value=1 $post_fontsize_open name=setting[pa20]>$arr_ad_lng[94] <INPUT type=radio value=0 $post_fontsize_close name=setting[pa20]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[115]</td>
   <td><INPUT type=radio value=1 $allow_forb_ub_open name=setting[fb1]>$arr_ad_lng[94] <INPUT type=radio value=0 $allow_forb_ub_close name=setting[fb1]>$arr_ad_lng[95] </td>
  </tr>
  	    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[116]</td>
   <td><INPUT size=35 value="$short_msg_max" name=setting[a13]></td>
  </tr>
  	  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[117]</td>
   <td><INPUT size=35 value="$send_msg_max" name=setting[a14]></td>
  </tr>  
  	<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[118]</td>
   <td><INPUT type=radio value=1 $use_own_portait_yes name=setting[a15]>$arr_ad_lng[94] <INPUT type=radio value=0 $use_own_portait_no name=setting[a15]>$arr_ad_lng[95] </td>
  </tr>
   	<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[119]</td>
   <td><INPUT type=radio value=1 $see_amuser_yes name=setting[uu1]>$arr_ad_lng[94] <INPUT type=radio value=0 $see_amuser_no name=setting[uu1]>$arr_ad_lng[95] </td>
  </tr>
  	  	       <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[120]</td>
   <td><INPUT type=radio value=yes $open_html name=setting[a26]>$arr_ad_lng[87] <INPUT type=radio value=no $close_html name=setting[a26]>$arr_ad_lng[88] </td>
  </tr>		
  			  	     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[121]</td>
   <td><INPUT type=text value="$post_sell_max" name=setting[a787]></td>
  </tr>
  	       <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[122]</td>
   <td><INPUT type=radio value=1 $open_swf name=setting[a16]>$arr_ad_lng[87] <INPUT type=radio value=0 $close_swf name=setting[a16]>$arr_ad_lng[88] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[915]</td>
   <td>
    <INPUT type=radio $open_down_attach value='1' name=setting[p10]> $arr_ad_lng[87] <INPUT $close_down_attach type=radio value='0' name=setting[p10]> $arr_ad_lng[88] $arr_ad_lng[912] <INPUT size=5 value="$down_attach_ww" name=setting[p11]>
   </td>
  </tr> 
  	    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[123]</td>
   <td><INPUT size=35 value="$max_upload_size" name=setting[a17]></td>
  </tr>
  			 <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[124]</td>
   <td><INPUT size=35 value="$max_daily_upload_size" name=setting[a227]></td>
  </tr>
     	    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[125]</td>
   <td><INPUT type=radio value=1 $can_visual_post_open name=setting[vcp]>$arr_ad_lng[94] <INPUT type=radio value=0 $can_visual_post_close name=setting[vcp]>$arr_ad_lng[95] </td>
  </tr>
      	    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[126]</td>
   <td><INPUT size=35 value="$logon_post_second" name=setting[a228]></td>
  </tr>
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
  			  	 <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[127]</td>
   <td><INPUT size=35 value="$max_upload_num" name=setting[a25]></td>
  </tr>
  			  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[128]</td>
   <td><INPUT type=radio value=1 $allow_upload_yes name=setting[ac34]>$arr_ad_lng[94] <INPUT type=radio value=0 $allow_upload_no name=setting[ac34]>$arr_ad_lng[95] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1206] $curl_check</td>
   <td><input type=radio value=1 $switch_on[120] name=setting[ru11]>$arr_ad_lng[94] <input type=radio value=0 $switch_off[120] name=setting[ru11]>$arr_ad_lng[95] </td>
  </tr>
  		   <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[129]</td>
   <td><INPUT size=35 value="$max_upload_post" name=setting[ac71]></td>
  </tr>
  	   <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[130]</td>
   <td><INPUT size=35 value="$upload_type_available" name=setting[a18]></td>
  </tr> 
	
   <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[131]</td>
   <td><INPUT type=radio value=1 $open_member_list name=setting[oa1]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_member_list name=setting[oa1]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[132]</td>
   <td><INPUT type=radio value=1 $open_search_fun name=setting[oa2]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_search_fun name=setting[oa2]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[133]</td>
   <td><INPUT type=radio value=1 $open_nwpost_list name=setting[oa3]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_nwpost_list name=setting[oa3]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[134]</td>
   <td><INPUT type=radio value=1 $open_porank_list name=setting[oa4]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_porank_list name=setting[oa4]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[135]</td>
   <td><INPUT type=radio value=1 $allow_open_gvf name=setting[oa5]>$arr_ad_lng[94] <INPUT type=radio value=0 $cannot_open_gvf name=setting[oa5]>$arr_ad_lng[95] </td>
  </tr>

    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[136]</td>
   <td><INPUT type=radio value=1 $open_cutusericon name=setting[ac60]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_cutusericon name=setting[ac60]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[137]</td>
   <td><INPUT type=radio value=1 $open_upusericon name=setting[ac76]>$arr_ad_lng[94] <INPUT type=radio value=0 $close_upusericon name=setting[ac76]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[138]</td>
   <td><INPUT size=35 value="$max_avatars_upload_size" name=setting[ac77]></td>
  </tr>
   <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[139]</td>
   <td><INPUT size=35 value="$max_avatars_upload_post" name=setting[ac78]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[140]</td>
   <td><INPUT size=35 value="$upload_avatars_type_available" name=setting[ac79]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[144]</td>
   <td><INPUT size=35 value="$delrmb" name=setting[an52]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[145]</td>
   <td><INPUT size=35 value="$post_money" name=setting[an51]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[146]</td>
   <td><INPUT size=35 value="$post_money_reply" name=setting[r1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[147]</td>
   <td><INPUT size=35 value="$deljifen" name=setting[an67]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[148]</td>
   <td><INPUT size=35 value="$post_jifen" name=setting[an68]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[149]</td>
   <td><INPUT size=35 value="$post_jifen_reply" name=setting[r2]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1027]</td>
   <td><INPUT size=35 value="$browse_add_point" name=setting[rap1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[1040]</td>
   <td><INPUT size=35 value="$browse_limit_point" name=setting[rap6]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1038]</td>
   <td><INPUT size=35 value="$edit_time_limit" name=setting[ds4]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[1060]</td>
   <td><input size=35 value="0" name=setting[ban_post]></td>
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
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[150]</td>
   <td><INPUT type=radio value=1 $del_self_topic_yes name=setting[ds1]>$arr_ad_lng[87] <INPUT type=radio value=0 $del_self_topic_no name=setting[ds1]>$arr_ad_lng[88] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[151]</td>
   <td><INPUT type=radio value=1 $del_self_post_yes name=setting[ds2]>$arr_ad_lng[87] <INPUT type=radio value=0 $del_self_post_no name=setting[ds2]>$arr_ad_lng[88] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1037]</td>
   <td><INPUT type=radio value=1 $del_self_reth_yes name=setting[ds3]>$arr_ad_lng[87] <INPUT type=radio value=0 $del_self_reth_no name=setting[ds3]>$arr_ad_lng[88] 
  $table_start
    <strong>$arr_ad_lng[152]</strong>
   </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[155]</td>
   <td><INPUT type=radio value=1 $mod_yes name=setting[a24]>$arr_ad_lng[153] <INPUT type=radio value=0 $mod_no name=setting[a24]>$arr_ad_lng[154] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[156]</td>
   <td><INPUT type=radio value=1 $supermod_yes name=setting[a19]>$arr_ad_lng[153] <INPUT type=radio value=0 $supermod_no name=setting[a19]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[157]</td>
   <td><INPUT type=radio value=1 $admin_yes name=setting[a20]>$arr_ad_lng[153] <INPUT type=radio value=0 $admin_no name=setting[a20]>$arr_ad_lng[154] 
$table_start
    <strong>$arr_ad_lng[158]</strong>
   </td>
  </tr>
       <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1047]</td>
   <td><INPUT type=radio value=1 $switch_on[111] name=setting[pay_back]>$arr_ad_lng[153] <INPUT type=radio value=0 $switch_off[111] name=setting[pay_back]>$arr_ad_lng[154] </td>
  </tr>

       <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[159]</td>
   <td><INPUT type=radio value=1 $lock_true_yes name=setting[b1]>$arr_ad_lng[153] <INPUT type=radio value=0 $lock_true_no name=setting[b1]>$arr_ad_lng[154] </td>
  </tr>
        <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[160]</td>
   <td><INPUT type=radio value=1 $del_reply_true_yes name=setting[b2]>$arr_ad_lng[153] <INPUT type=radio value=0 $del_reply_true_no name=setting[b2]>$arr_ad_lng[154] </td>
  </tr>
          <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[161]</td>
   <td><INPUT type=radio value=1 $edit_true_yes name=setting[b3]>$arr_ad_lng[153] <INPUT type=radio value=0 $edit_true_no name=setting[b3]>$arr_ad_lng[154] </td>
  </tr>
           <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[162]</td>
   <td><INPUT type=radio value=1 $move_true_yes name=setting[b4]>$arr_ad_lng[153] <INPUT type=radio value=0 $move_true_no name=setting[b4]>$arr_ad_lng[154] </td>
  </tr>

    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[164]</td>
   <td><INPUT type=radio value=1 $ztop_true_yes name=setting[b6]>$arr_ad_lng[153] <INPUT type=radio value=0 $ztop_true_no name=setting[b6]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[165]</td>
   <td><INPUT type=radio value=1 $ctop_true_yes name=setting[b7]>$arr_ad_lng[153] <INPUT type=radio value=0 $ctop_true_no name=setting[b7]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[166]</td>
   <td><INPUT type=radio value=1 $ttop_true_yes name=setting[b12]>$arr_ad_lng[153] <INPUT type=radio value=0 $ttop_true_no name=setting[b12]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[167]</td>
   <td><INPUT type=radio value=1 $uptop_true_yes name=setting[b8]>$arr_ad_lng[153] <INPUT type=radio value=0 $uptop_true_no name=setting[b8]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[168]</td>
   <td><INPUT type=radio value=1 $bold_true_yes name=setting[b9]>$arr_ad_lng[153] <INPUT type=radio value=0 $bold_true_no name=setting[b9]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[169]</td>
   <td><INPUT type=radio value=1 $sej_true_yes name=setting[b10]>$arr_ad_lng[153] <INPUT type=radio value=0 $sej_true_no name=setting[b10]>$arr_ad_lng[154] </td>
  </tr>
      <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[170]</td>
   <td><INPUT type=radio value=1 $autorip_true_yes name=setting[b11]>$arr_ad_lng[153] <INPUT type=radio value=0 $autorip_true_no name=setting[b11]>$arr_ad_lng[154] </td>
  </tr>
         <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[171]</td>
   <td><INPUT type=radio value=1 $modcenter_true_yes name=setting[b13]>$arr_ad_lng[153] <INPUT type=radio value=0 $modcenter_true_no name=setting[b13]>$arr_ad_lng[154] </td>
  </tr>
            <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[172]</td>
   <td><INPUT type=radio value=1 $modano_true_yes name=setting[b14]>$arr_ad_lng[153] <INPUT type=radio value=0 $modano_true_no name=setting[b14]>$arr_ad_lng[154] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[173]</td>
   <td><INPUT type=radio value=1 $modban_true_yes name=setting[b15]>$arr_ad_lng[153] <INPUT type=radio value=0 $modban_true_no name=setting[b15]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[174]</td>
   <td><INPUT type=radio value=1 $del_true_yes name=setting[a234]>$arr_ad_lng[153] <INPUT type=radio value=0 $del_true_no name=setting[a234]>$arr_ad_lng[154] </td>
  </tr>
     <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[175]</td>
   <td><INPUT type=radio value=1 $clean_true_yes name=setting[s1]>$arr_ad_lng[153] <INPUT type=radio value=0 $clean_true_no name=setting[s1]>$arr_ad_lng[154] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[176]</td>
   <td><INPUT type=radio value=1 $del_rec_yes name=setting[a139]>$arr_ad_lng[153] <INPUT type=radio value=0 $del_rec_no name=setting[a139]>$arr_ad_lng[154] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td>$arr_ad_lng[177]</td>
   <td><INPUT type=radio value=1 $can_rec_yes name=setting[a230]>$arr_ad_lng[153] <INPUT type=radio value=0 $can_rec_no name=setting[a230]>$arr_ad_lng[154] </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1049]</td>
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
   <td>$arr_ad_lng[1050]</td>
   <td><input type="radio" value="1" $switch_on[113] name="setting[split_p]" />$arr_ad_lng[153] <input type="radio" value="0" $switch_off[113] name="setting[split_p]" />$arr_ad_lng[154] 
$table_start
	   <input type=submit value="$arr_ad_lng[66]"> <input type=reset value="$arr_ad_lng[178]">
  </tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    // Safety Checks
    $bannedexist = array("php", "php4", "php3", "cgi", "pl", "asp", "aspx", "cfm", "shtml"); // Banned Files
    $tmpexitscheck = explode(" ", $setting[a18]);
    $ccoun = count($tmpexitscheck);
    for($c = 0;$c < $ccoun;$c++) {
        if (in_array($tmpexitscheck[$c], $bannedexist)) unset($tmpexitscheck[$c]);
    } 
    $setting[a18] = implode(" ", $tmpexitscheck); 
    // Avarat
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

    $new = $setting[a21] . "|" . $setting[a22] . "|" . $issysteam . "|" . $setting[a1] . "|" . $setting[a2] . "|" . $setting[a3] . "|" . $setting[a4] . "|" . $setting[a5] . "|" . $setting[a6] . "|" . $setting[a7] . "|" . $setting[a8] . "|" . $setting[a9] . "|" . $setting[a10] . "|" . $setting[a11] . "|" . $setting[a12] . "|" . $setting[a13] . "|" . $setting[a14] . "|" . $setting[a15] . "|" . $setting[a16] . "|" . $setting[a17] . "|" . $setting[a18] . "|" . $setting[a19] . "|" . $setting[a20] . "|" . $setting[a23] . "|" . $setting[a24] . "|" . $setting[a25] . "|" . $setting[a26] . "|" . $setting[a227] . "|" . $setting[a228] . "|" . $setting[a787] . "|" . $setting[a234] . "|" . $setting[a139] . "|" . $setting[a230] . "|" . $setting[an52] . "|" . $setting[an51] . "|" . $setting[an67] . "|" . $setting[an68] . "|" . $setting[ac34] . "|" . $setting[ac71] . "|" . $setting[ac60] . "|" . $setting[ac76] . "|" . $setting[ac77] . "|" . $setting[ac78] . "|" . $setting[ac79] . "|" . $setting[ac58] . "|" . $setting[ac59] . "|" . $setting[an2511] . "|" . $setting[an2510] . "|" . $setting[b1] . "|" . $setting[b2] . "|" . $setting[b3] . "|" . $setting[b4] . "|" . $setting[b5] . "|" . $setting[b6] . "|" . $setting[b7] . "|" . $setting[b8] . "|" . $setting[b9] . "|" . $setting[b10] . "|" . $setting[b11] . "|" . $setting[b12] . "|" . $setting[b13] . "|" . $setting[b14] . "|" . $setting[b15] . "|" . $setting[s1] . "|" . $setting[xa1] . "|" . $setting[r1] . "|" . $setting[r2] . "|" . $setting[ds1] . "|" . $setting[ds2] . "|" . $setting[pa18] . "|" . $setting[pa85] . "|" . $setting[pa86] . "|" . $setting[pa87] . "|" . $setting[pa19] . "|" . $setting[pa37] . "|" . $setting[pa38] . "|" . $setting[pa20] . "|" . $setting[pnew1] . "|" . $setting[pnew2] . "|" . $setting[fb1] . "|" . $setting[vcp] . "|" . $setting[oa1] . "|" . $setting[oa2] . "|" . $setting[oa3] . "|" . $setting[oa4] . "|" . $setting[oa5] . "|" . $setting[uu1] . "|" . $setting[h04] . "|" . $setting[p1] . "|" . $setting[p2] . "|" . $setting[p3] . "|" . $setting[p4] . "|" . $setting[p5] . "|" . $setting[p6] . "|" . $setting[p7] . "|" . $setting[p8] . "|" . $setting[p9] . "|" . $setting[p10] . "|" . $setting[p11] . "|" . $setting[tag10] . "|" . $setting[tag11] . "|" . $setting[tag12] . "|" . $setting[mpl1] . "|" . $setting[mpl2] . "|" . $setting[mpl3] . "|" . $setting[rap1] . "|" . $setting[ds3] . "|" . $setting[ds4] . "|" . $setting[rap6] . "|" . $setting[tag13] . "|" . $setting[tag14] . "|" . $setting[pay_back] . "|" . $setting[recycle_p] . "|" . $setting[split_p] . "|" . $setting[ban_post] . "|" . $setting[table] . "|" . $setting[epl1] . "|" . $setting[epl2] . "|" . $setting[ru11] . "|" . $setting[srl3] . "|" . $setting[srl4] . "|" . $setting[srl5] . "|" . $setting[srl6] . "|" . $setting[srl7] . "|" . $setting[srl8] . "|" . $setting[srl9] . "|" . $setting[srl10] . "|" . $setting[srl11] . "|" . $setting[srl12] ."|\n";

    if ($setting[xa1] == 1) $unshowit = 0;
    else $unshowit = 1;

    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `id` DESC  LIMIT 0,1 ";
    $result = bmbdb_query($query);
    $linexx = bmbdb_fetch_array($result);
    $newlineno = $linexx['id'] + 1;

    $nquery = "insert into {$database_up}usergroup (id,unshowit,usersets,showsort,adminsets,regwith) values ('$newlineno','$unshowit','$new','$timestamp','','')";
    $result = bmbdb_query($nquery);

    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=usergroup.php">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;

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
} 
