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
$thisprog = "setoptions.php";
if ($useraccess != "1" || $admgroupdata[0] != "1") {
    adminlogin();
} 

if ($action != "process") {
    // Form Page
    include("datafile/config.php");

    $online_limit /= 60;

    if ($bmfcode_sign['pic']) $sign_pic_open = "checked='checked'";
    else $sign_pic_close = "checked='checked'";
    if ($bmfcode_sign['flash']) $sign_flash_open = "checked='checked'";
    else $sign_flash_close = "checked='checked'";
    if ($bmfcode_sign['fontsize']) $sign_fontsize_open = "checked='checked'";
    else $sign_fontsize_close = "checked='checked'";
    if ($bmfcode_post['pic']) $post_pic_open = "checked='checked'";
    else $post_pic_close = "checked='checked'";
    if ($bmfcode_post['reply']) $post_reply_open = "checked='checked'";
    else $post_reply_close = "checked='checked'";
    if ($bmfcode_post['jifen']) $post_jifen_open = "checked='checked'";
    else $post_jifen_close = "checked='checked'";
    if ($bmfcode_post['sell']) $post_sell_open = "checked='checked'";
    else $post_sell_close = "checked='checked'";
    if ($bmfcode_post['flash']) $post_flash_open = "checked='checked'";
    else $post_flash_close = "checked='checked'";
    if ($bmfcode_post['mpeg']) $post_mpeg_open = "checked='checked'";
    else $post_mpeg_close = "checked='checked'";
    if ($bmfcode_post['iframe']) $post_iframe_open = "checked='checked'";
    else $post_iframe_close = "checked='checked'";
    if ($bmfcode_post['fontsize']) $post_fontsize_open = "checked='checked'";
    else $post_fontsize_close = "checked='checked'";
    if ($bmfcode_echo['pic']) $echo_pic_open = "checked='checked'";
    else $echo_pic_close = "checked='checked'";
    if ($bmfcode_echo['flash']) $echo_flash_open = "checked='checked'";
    else $echo_flash_close = "checked='checked'";
    if ($bmfcode_echo['fontsize']) $echo_fontsize_open = "checked='checked'";
    else $echo_fontsize_close = "checked='checked'";
    if ($sign_use_bmfcode) $sign_bmfcode = "checked='checked'";
    else $sign_no_bmfcode = "checked='checked'";
    if ($send_mail) $send_mail_yes = "checked='checked'";
    else $send_mail_no = "checked='checked'";
    if ($send_msg) $send_msg_yes = "checked='checked'";
    else $send_msg_no = "checked='checked'";
    if ($send_pass==1) $send_pass_yes = "checked='checked'";
    elseif ($send_pass==2) $send_pass_two = "checked='checked'";
    elseif ($send_pass==3) $send_pass_t = "checked='checked'";
    else $send_pass_no = "checked='checked'";
    
    if ($showtime) $showtime_yes = "checked='checked'";
    else $showtime_no = "checked='checked'";
    if ($view_forum_online) $view_forum_online_yes = "checked='checked'";
    else $view_forum_online_no = "checked='checked'";
    if ($use_own_portait) $use_own_portait_yes = "checked='checked'";
    else $use_own_portait_no = "checked='checked'";
    if ($allow_upload) $allow_upload_yes = "checked='checked'";
    else $allow_upload_no = "checked='checked'";
    if ($ads_select) $open_ads = "checked='checked'";
    else $close_ads = "checked='checked'";
    if ($reg_stop) $reg_stopopen = "checked='checked'";
    else $reg_stopclose = "checked='checked'";
    if ($bbs_is_open) $bbs_stopopen = "checked='checked'";
    else $bbs_stopclose = "checked='checked'";
    if ($frep_select) $open_frep = "checked='checked'";
    else $close_frep = "checked='checked'";
    if ($fnew_select) $open_fnew = "checked='checked'";
    else $close_fnew = "checked='checked'";
    if ($fnew_skin) $open_skin = "checked='checked'";
    else $close_skin = "checked='checked'";
    if ($page_regtips) $open_page_regtips = "checked='checked'";
    else $close_page_regtips = "checked='checked'";
    if ($index_regtips) $open_index_regtips = "checked='checked'";
    else $close_index_regtips = "checked='checked'";
    if ($cancel_guest) $close_cancel_guest = "checked='checked'";
    else $open_cancel_guest = "checked='checked'";
    if ($opencutusericon) $open_cutusericon = "checked='checked'";
    else $close_cutusericon = "checked='checked'";
    if ($openupusericon) $open_upusericon = "checked='checked'";
    else $close_upusericon = "checked='checked'";
    if ($gvf) $open_gvf = "checked='checked'";
    else $close_gvf = "checked='checked'";
    if ($frdduan) $open_frdduan = "checked='checked'";
    else $close_frdduan = "checked='checked'";
    if ($regduan) $open_regduan = "checked='checked'";
    else $close_regduan = "checked='checked'";
    if ($all_regtips) $close_all_regtips = "checked='checked'";
    if ($usemarquee[0]) $open_usemarquee[0] = "checked='checked'";
    if ($usemarquee[1]) $open_usemarquee[1] = "checked='checked'";
    if ($postjumpurl) $top_postjumpurl = "checked='checked'";
    else $forum_postjumpurl = "checked='checked'";
    if ($bbs_news) $bbs_news_yes = "checked='checked'";
    else $bbs_news_no = "checked='checked'";
    if ($gzip_compress) $open_gzip_compress = "checked='checked'";
    else $close_gzip_compress = "checked='checked'";
    if ($sess_cust) $close_sess_cust = "checked='checked'";
    else $open_sess_cust = "checked='checked'";
    if ($reg_va) $reg_va_open = "checked='checked'";
    else $reg_va_close = "checked='checked'";
    if ($gd_auth) $gd_auth_open = "checked='checked'";
    else $gd_auth_close = "checked='checked'";
    if ($log_va) $log_va_open = "checked='checked'";
    else $log_va_close = "checked='checked'";
    if ($view_index_online) $view_index_online_open = "checked='checked'";
    else $view_index_online_no = "checked='checked'";
    if ($todayb_show) $open_todayb_show = "checked='checked'";
    else $close_todayb_show = "checked='checked'";
    if ($openbbimg) $open_openbbimg = "checked='checked'";
    else $close_openbbimg = "checked='checked'";
    if ($fdattach) $open_fdattach = "checked='checked'";
    if ($tfdattach) $two_tfdattach = "checked='checked'";
    if ($autolang) $open_autolang = "checked='checked'";
    else $close_autolang = "checked='checked'";
    if ($saveattbyym) $open_saveattbyym = "checked='checked'";
    else $close_saveattbyym = "checked='checked'";
    if ($emotrand) $open_emotrand = "checked='checked'";
    else $close_emotrand = "checked='checked'";
    if ($fastmanage) $open_fastmanage = "checked='checked'";
    else $close_fastmanage = "checked='checked'";
    if ($fastlogin) $open_fastlogin = "checked='checked'";
    else $close_fastlogin = "checked='checked'";
    if ($cachejs) $open_cachejs = "checked='checked'";
    else $close_cachejs = "checked='checked'";
    if ($infopics) $open_infopics = "checked='checked'";
    else $close_infopics = "checked='checked'";
    if ($fjsjump) $open_fjsjump = "checked='checked'";
    else $close_fjsjump = "checked='checked'";
    if ($rcordosbr) $open_rcordosbr = "checked='checked'";
    else $close_rcordosbr = "checked='checked'";
    if ($listmmlist) $open_listmmlist = "checked='checked'";
    else $close_listmmlist = "checked='checked'";
    if ($cacheclick) $open_cacheclick = "checked='checked'";
    else $close_cacheclick = "checked='checked'";
    if ($reg_invit == 1) $reg_invit_open = "checked='checked'";
    elseif ($reg_invit == 2) $reg_invit_choose = "checked='checked'";
    else $reg_invit_close = "checked='checked'";
    if ($reg_oneemail) $reg_oneemail_open = "checked='checked'";
    else $reg_oneemail_close = "checked='checked'";
    if ($allow_ajax_reply) $oallow_ajax_reply = "checked='checked'";
    else $callow_ajax_reply = "checked='checked'";
    if ($allow_ajax_browse) $oallow_ajax_browse = "checked='checked'";
    else $callow_ajax_browse = "checked='checked'";
	$ulists = "";
	$showulist = "<select name='setugactive'>";
    $ccscount = count($usergroupdata);
    if ($bmfopt['inviteallow'] != "") $inviteallow = explode(",", $bmfopt['inviteallow']);
    for($axd = 0;$axd < $ccscount;$axd++) {
        $usergroupname = explode("|", $usergroupdata[$axd]);
        $checkugns = $icheckugns = $ucheckugns = "";
        if ($axd == $ugactive) $checkugns = "selected='selected'";
        if ($axd == $bmfopt['invitesugroup'] && $bmfopt['invitesugroup']) $ucheckugns = "selected='selected'";
        if (@in_array($axd, $inviteallow)) $icheckugns = "selected='selected'";
        $ulists .= "<option $checkugns value='$axd'>$usergroupname[0]</option>";
        $invites .= "<option $icheckugns value='$axd'>$usergroupname[0]</option>";
        $uinvites .= "<option $ucheckugns value='$axd'>$usergroupname[0]</option>";
    } 
    $showulist .= $ulists."</select>";
    
    @include("datafile/cache/tags_topic.php");
    @include("datafile/cache/eplist.php");
    $selectbox_ep = '';
    foreach($enlist as $key=>$value)
    {
    	$selected_ep = ($bmfopt['default_ep'] == $key)  ? 'selected="selected"' : '';
    	$selectbox_ep .= "<option value='$key' $selected_ep>$value</option>";
    }
    
    $footer_copyright = htmlspecialchars($footer_copyright);
    $reg_close_msg = htmlspecialchars($reg_close_msg);
    $choose_reason = htmlspecialchars($choose_reason);
    $exist_tags_solid = htmlspecialchars($tags_tlist['tags_solid']);
    
    if (is_array($bmfopt)) {
        foreach ($bmfopt as $key => $value) {
        	if ($value) $switchon[$key] = "checked='checked'";
        	    else $switchoff[$key] = "checked='checked'";
        }
    }
    $gd_check = function_exists("imageCreate") ? $arr_ad_lng[94] : $arr_ad_lng[95];
    
    if ($bmfopt['wmposition']) $switchon_wmp[$bmfopt['wmposition']] = "selected='selected'";
    
    $wm_polang = explode("|", $arr_ad_lng[1205]);
	if($oauthLang['provider']) {
		$oauthMenu = ' | <a href="#oauthsetting">'.$oauthLang['menu'].'</a>';
	}
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[183]</strong>    
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
    
  </td></tr>  


  <tr>
  <td bgcolor=#FFFFFF valign=middle align=left colspan=2 style="border-bottom: #C47508 1px soild;">
  <font color=#333333><strong><a name="top">$arr_ad_lng[964]</a>
  </td></tr>
  <tr bgcolor=FFC96B>
   <td colspan=2 style="border: #C47508 1px soild;"><a href="#openclose">$arr_ad_lng[974]</a> $oauthMenu | <a href="#basicinfo">$arr_ad_lng[976]</a> | <a href="#habit">$arr_ad_lng[977]</a> | <a href="#register">$arr_ad_lng[978]</a> | <a href="#userper">$arr_ad_lng[979]</a> | <a href="#bbsper">$arr_ad_lng[983]</a><br /><a href="#bbsreason">$arr_ad_lng[984]</a> | <a href="#tags">$arr_ad_lng[1064]</a> | <a href="#watermark">$arr_ad_lng[1074]</a></td>
  </tr>
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name='openclose'>$arr_ad_lng[183]</a></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>




  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[583]</td>
   <td>
    <input type=radio $bbs_stopopen value=1 name=setting[a1]> $arr_ad_lng[584] <input type=radio $bbs_stopclose value=0 name=setting[a1]> $arr_ad_lng[585]
   </td>
  </tr>
 <tr bgcolor=#F9FCFE>
    <td>$arr_ad_lng[586]</td>
   <td>
    <input type=text value="$scclose" name=setting[a2221]>
   </td>
 </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[587]</td>
   <td>
    <textarea name=setting[a2] rows=6 cols=50>$bbs_close_msg</textarea>
   </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[588]</td>
   <td>
    <input type=radio $reg_stopopen value=1 name=setting[a40]> $arr_ad_lng[584] <input type=radio value=0 $reg_stopclose name=setting[a40]> $arr_ad_lng[585]
   </td>
  </tr>
  </tr>
 <tr bgcolor=F9FAFE>
    <td>$arr_ad_lng[589]</td>
   <td>
    <input type=text value="$recclose" name=setting[a2v21]>
   </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[590]</td>
   <td>
    <textarea name=setting[a41] rows=6 cols=50>$reg_close_msg</textarea>
   </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[592]</td>
   <td><input size=35 value="$maxlogintry" name=setting[mlt]></td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[593]</td>
   <td>
    <input type=radio $open_gzip_compress value='1' name=setting[a1256]> $arr_ad_lng[94] <input $close_gzip_compress type=radio value='0' name=setting[a1256]> $arr_ad_lng[95]
   </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[594]</td>
   <td>
    <input type=radio $open_sess_cust value='0' name=setting[sesscut]> $arr_ad_lng[596] <input $close_sess_cust type=radio value='1' name=setting[sesscut]> $arr_ad_lng[597]
   </td>
  </tr>
  	<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[595]</td>
   <td>
    <input type=radio $open_todayb_show value='1' name=setting[tod1]> $arr_ad_lng[94] <input $close_todayb_show type=radio value='0' name=setting[tod1]> $arr_ad_lng[95]
   </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[598]</td>
   <td>$arr_ad_lng[599]<br />
    <input type=radio $open_page_regtips value=1 name=setting[a53]> $arr_ad_lng[600] <input type=radio value=0 $close_page_regtips name=setting[a53]> $arr_ad_lng[95]
      <br />$arr_ad_lng[601]<br /><input type=radio $open_index_regtips value=1 name=setting[a54]> $arr_ad_lng[600] <input type=radio value=0 $close_index_regtips name=setting[a54]> $arr_ad_lng[95]
      <br />-------<br /><input type=checkbox value=1 $close_all_regtips name=setting[a55]> $arr_ad_lng[602]
   </td>
      
  </tr>
  </tr>

EOT;

if($oauthLang['provider']) {
print <<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name='oauthsetting'>{$oauthLang['menu']}</a></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr> 

EOT;

	foreach($oauthLang['provider'] as $providerId => $providerName) {
	
print <<<EOT
  <tr bgcolor=#F9FAFE>
   <td width=50%>$providerName AppKey</td>
   <td><input size=35 value="{$bmfopt['oauth'][$providerId]['appKey']}" name='setting[oauth][$providerId][appKey]'></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$providerName AppSecret</td>
   <td><input size=35 value="{$bmfopt['oauth'][$providerId]['appSecret']}" name='setting[oauth][$providerId][appSecret]'></td>
  </tr>
  </td></tr>
EOT;
	}
}

print <<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name='basicinfo'>$arr_ad_lng[603]</a></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr> 


  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[604]</td>
   <td><input size=35 value="$cookie_d" onclick="javascript:alert('$arr_ad_lng[874]');" name=setting[cookie1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[605]</td>
   <td><input size=35 value="$cookie_p" onclick="javascript:alert('$arr_ad_lng[874]');" name=setting[cookie2]></td>
  </tr>
  </td></tr>
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name='basicinfo'>$arr_ad_lng[606]</a></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1251]</td>
   <td><input size=35 value="$short_title" name=setting[short_title]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[607]</td>
   <td><input size=35 value="$bbs_title" name=setting[a3]></td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[608]</td>
   <td><input size=35 value="$bbs_money" name=setting[a39]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[609]</td>
   <td><input size=35 value="$script_pos" name=setting[a4]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[610]</td>
   <td><input size=35 value="$site_name" name=setting[a5]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[611]</td>
   <td><input size=35 value="$site_url" name=setting[a6]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[612]</td>
   <td><input size=35 value="$keyword_site" name=setting[a7]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[613]</td>
   <td><input size=35 value="$admin_email" name=setting[a8]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1042]</td>
   <td><input size=35 value="$admin_idname" name=setting[b1]></td>
  </tr>
   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[614]</td>
   <td><input size=35 value="$bbs_des" name=setting[a64]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[615]</td>
   <td><input size=35 value="$bbslogo83" name=setting[a65]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1057]</td>
   <td><input size=35 value="$bmfopt[ip_address]" name=setting[ip_address]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1048]</td>
   <td><textarea name="setting[ft]" rows="6" cols="50">$footer_copyright</textarea>
	</td>
  </tr>
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name='habit'>$arr_ad_lng[616]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[618]</td>
   <td><input size=35 value="$postbinfo" name=setting[a61]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1245]</td>
   <td><input type=radio value=1 $switchon[hide_copyright] name=setting[hide_copyright]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[hide_copyright] name=setting[hide_copyright]>$arr_ad_lng[95] 
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[619]</td>
   <td><input size=35 value="$perpage" name=setting[a14]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1052]</td>
   <td><input size="35" value="$bmfopt[view_newpost]" name="setting[view_newpost]" /></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1053]</td>
   <td><input size="35" value="$bmfopt[view_ranking]" name="setting[view_ranking]" /></td>
  </tr>
 <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[620]</td>
   <td><input size=35 value="$read_perpage" name=setting[a15]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[621]</td>
   <td><input size=35 value="$max_poll" name=setting[a47]></td>
  </tr>
  	  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[622]</td>
   <td><input size=35 value="$max_zzd" name=setting[a48]></td>
  </tr>
    	  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[623]</td>
   <td><input size=35 value="$max_qzd" name=setting[a648]></td>
  </tr>

  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[626]</td>
   <td><input size=35 value="$echo_reply" name=setting[a21]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[884]</td>
   <td><input type=radio value=1 $open_fastlogin name=setting[fa1]>$arr_ad_lng[94] <input type=radio value=0 $close_fastlogin name=setting[fa1]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1221]</td>
   <td><input type=radio value=1 $switchon[article_desc] name=setting[article_desc]>$arr_ad_lng[1222] <input type=radio value=0 $switchoff[article_desc] name=setting[article_desc]>$arr_ad_lng[1223] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1039]</td>
   <td><input type=radio value=1 $switchon[showsubforum] name=setting[fa5]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[showsubforum] name=setting[fa5]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[627]</td>
   <td><input type=radio value=1 $echo_pic_open name=setting[a22]>$arr_ad_lng[94] <input type=radio value=0 $echo_pic_close name=setting[a22]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[628]</td>
   <td><input type=radio value=1 $echo_flash_open name=setting[a23]>$arr_ad_lng[94] <input type=radio value=0 $echo_flash_close name=setting[a23]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[629]</td>
   <td><input type=radio value=1 $echo_fontsize_open name=setting[a24]>$arr_ad_lng[94] <input type=radio value=0 $echo_fontsize_close name=setting[a24]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[892]</td>
   <td><input size=35 value="$set_forinfo" name=setting[i25]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[911]</td>
   <td><input type=radio value=1 $open_listmmlist name=setting[mm4]>$arr_ad_lng[94] <input type=radio value=0 $close_listmmlist name=setting[mm4]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[951]</td>
   <td>$arr_ad_lng[952] <input size=4 value="$emot_every" name=setting[erl1]> $arr_ad_lng[953] <input size=4 value="$emot_lines" name=setting[erl2]></td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1250]</td>
   <td><select name='setting[eil1]'>$selectbox_ep</select> </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[644]</td>
   <td><input size=35 value="$userperpage" name=setting[a29]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[898]</td>
   <td><input type=radio value=1 $open_fjsjump name=setting[f2a2]>$arr_ad_lng[94] <input type=radio value=0 $close_fjsjump name=setting[f2a2]>$arr_ad_lng[95] </td>
  </tr>
  	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[634]</td>
   <td><input type=radio value=1 $bbs_news_yes name=setting[a90]>$arr_ad_lng[94] <input type=radio value=0 $bbs_news_no name=setting[a90]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[646]</td>
   <td><input type=radio value=1 $showtime_yes name=setting[a31]>$arr_ad_lng[94] <input type=radio value=0 $showtime_no name=setting[a31]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[647]</td>
   <td><input type=radio value=1 $view_forum_online_yes name=setting[a32]>$arr_ad_lng[94] <input type=radio value=0 $view_forum_online_no name=setting[a32]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[648]</td>
   <td><input type=radio value=1 $view_index_online_open name=setting[ba32]>$arr_ad_lng[94] <input type=radio value=0 $view_index_online_no name=setting[ba32]>$arr_ad_lng[95] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[876]</td>
   <td><input type=radio value=1 $open_fastmanage name=setting[f56]>$arr_ad_lng[153] <input type=radio value=0 $close_fastmanage name=setting[f56]>$arr_ad_lng[154] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[660]</td>
   <td><input type=radio value=1 $open_ads name=setting[a43]>$arr_ad_lng[94] <input type=radio value=0 $close_ads name=setting[a43]>$arr_ad_lng[95] </td>
  </tr>
  	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[652]</td>
   <td><input type=radio value=1 $open_frep name=setting[a44]>$arr_ad_lng[481]<input type=radio value=0 $close_frep name=setting[a44]>$arr_ad_lng[482] </td>
  </tr>
  	    	   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[653]</td>
   <td><input type=radio value=1 $open_fnew name=setting[a45]>$arr_ad_lng[481] <input type=radio value=0 $close_fnew name=setting[a45]>$arr_ad_lng[482] </td>
  </tr>			   
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[658]</td>
   <td><input type="checkbox" value="1" $open_usemarquee[0] name=setting[a56] />$arr_ad_lng[1055] <input type="checkbox" value="1" $open_usemarquee[1] name=setting[a56b]>$arr_ad_lng[1056] </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[655]</td>
   <td><input type=radio value=1 $top_postjumpurl name=setting[a57]>$arr_ad_lng[656] <input type=radio value=0 $forum_postjumpurl name=setting[a57]>$arr_ad_lng[657] </td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1059]</td>
   <td><input type="radio" value="1" {$switchon['return_opage']} name="setting[return_opage]" />$arr_ad_lng[153] <input type="radio" value="0" {$switchoff['return_opage']} name="setting[return_opage]" />$arr_ad_lng[154] </td>
  </tr>

  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1213]</td>
   <td><input size=35 value="$bmfopt[newpms]" name=setting[newpms]></td>
  </tr>

   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[640]</td>
   <td><input size=35 value="$reauto" name=setting[a81]></td>
  </tr>
  	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name="tags">$arr_ad_lng[1064]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1100]</td>
   <td><input type="radio" value="1" {$switchon['display_ftag']} name="setting[display_ftag]" />$arr_ad_lng[153] <input type="radio" value="0" {$switchoff['display_ftag']} name="setting[display_ftag]" />$arr_ad_lng[154] </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1061]</td>
   <td><input type="radio" value="1" {$switchon['hot_tags']} name="setting[hot_tags]" />$arr_ad_lng[153] <input type="radio" value="0" {$switchoff['hot_tags']} name="setting[hot_tags]" />$arr_ad_lng[154] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1063]</td>
   <td><input size="35" value="{$bmfopt['tags_max_similar']}" name="setting[tags_max_similar]" /></td>
  </tr>
 <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1101]</a></td>
   <td><textarea name="setting[tags_solid]" rows="6" cols="50">$exist_tags_solid</textarea></td>
  </tr>
  	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name="watermark">$arr_ad_lng[1074]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1075]{$gd_check}</td>
   <td><input type="radio" value="1" {$switchon['watermark']} name="setting[watermark]" />$arr_ad_lng[153] <input type="radio" value="0" {$switchoff['watermark']} name="setting[watermark]" />$arr_ad_lng[154] </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1076]</td>
   <td><input size="35" value="{$bmfopt['wmsize']}" name="setting[wmsize]" /></td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1077]</td>
   <td>
   	   <select name="setting[wmposition]">
   	   <option value="0" {$switchon_wmp[0]}>{$wm_polang[0]}</option>
   	   <option value="1" {$switchon_wmp[1]}>{$wm_polang[1]}</option>
   	   <option value="2" {$switchon_wmp[2]}>{$wm_polang[2]}</option>
   	   <option value="3" {$switchon_wmp[3]}>{$wm_polang[3]}</option>
   	   <option value="4" {$switchon_wmp[4]}>{$wm_polang[4]}</option>
   	   <option value="5" {$switchon_wmp[5]}>{$wm_polang[5]}</option>
   	   </select>
   </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1078]</td>
   <td><input size="35" value="{$bmfopt['wmpadding']}" name="setting[wmpadding]" /></td>
  </tr>
<tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1204]</td>
   <td><input size="35" value="{$bmfopt['wmtrans']}" name="setting[wmtrans]" /></td>
  </tr>
<tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1178]</td>
   <td><input type="radio" value="1" {$switchon['text_wm']} name="setting[text_wm]" />$arr_ad_lng[153] <input type="radio" value="0" {$switchoff['text_wm']} name="setting[text_wm]" />$arr_ad_lng[154] </td>
  </tr>
  	  
  	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name="register">$arr_ad_lng[917]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>
  	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[632]</td>
   <td><input type=radio value=1 $reg_va_open name=setting[arr87]>$arr_ad_lng[94] <input type=radio value=0 $reg_va_close name=setting[arr87]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[633]</td>
   <td><input type=radio value=1 $log_va_open name=setting[arr88]>$arr_ad_lng[94] <input type=radio value=0 $log_va_close name=setting[arr88]>$arr_ad_lng[95] </td>
  </tr>
   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1030] $gd_check</td>
   <td><input type=radio value=1 $gd_auth_open name=setting[auen]>$arr_ad_lng[94] <input type=radio value=0 $gd_auth_close name=setting[auen]>$arr_ad_lng[95] </td>
  </tr>
  	  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[920]</td>
   <td><input type=radio value=2 $send_pass_two name=setting[a82]>$arr_ad_lng[918]<br />
   		<input type=radio value=1 $send_pass_yes name=setting[a82]>$arr_ad_lng[635]<br />
   		<input type=radio value=0 $send_pass_no name=setting[a82]>$arr_ad_lng[1198] <br />
   		<input type=radio value=3 $send_pass_t name=setting[a82]>$arr_ad_lng[919]
   	</td>
  </tr>  
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[925]</td>
   <td>$showulist</td>
  </tr>  
  	   <tr bgcolor=#F9FAFE>
   <td width=50%>$arr_ad_lng[922]</td>
   <td><input type=radio value=1 $reg_invit_open name=setting[a120]>$arr_ad_lng[94] <input type=radio value=2 $reg_invit_choose name=setting[a120]>$arr_ad_lng[1202] <input type=radio value=0 $reg_invit_close name=setting[a120]>$arr_ad_lng[95] </td>
  </tr>  	
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1199]</td>
   <td><select name="inviteug[]" multiple size=8 style="width: 50%;" style="width: 120px">$invites</select></td>
  </tr>  
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1201]</td>
   <td><select name="setting[aig]">$uinvites</select></td>
  </tr>  
    <tr bgcolor=#F9FAFE>
   <td width=50%>$arr_ad_lng[1200]</td>
   <td><input size=35 value="$bmfopt[invite_past]" name=setting[aip]></td>
  </tr>  
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[923]</td>
   <td><input size=35 value="$invit_del_point" name=setting[a119]></td>
  </tr>  
    <tr bgcolor=#F9FAFE>
   <td width=50%>$arr_ad_lng[924]</td>
   <td><input size=35 value="$invit_send_point" name=setting[a122]></td>
  </tr>  
  	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1045]</td>
   <td><input type=radio value=1 $switchon[invite_type] name=setting[invite_type]>$bbs_money <input type=radio value=0 $switchoff[invite_type] name=setting[invite_type]>$arr_ad_lng[1046] </td>
  </tr>

  	   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[921]</td>
   <td><input type=radio value=1 $reg_oneemail_open name=setting[a121]>$arr_ad_lng[94] <input type=radio value=0 $reg_oneemail_close name=setting[a121]>$arr_ad_lng[95] </td>
  </tr>
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[636]</td>
   <td><input size=35 value="$reg_money" name=setting[a69]></td>
  </tr>  
    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[908]</td>
   <td><input size=4 value="$min_regname_length" name=setting[srl1]> - <input size=4 value="$max_regname_length" name=setting[srl2]></td>
  </tr>
 <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[637]</td>
   <td><input size=35 value="$reg_r_money" name=setting[a75]></td>
  </tr>
   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[638]</td>
   <td><input size=35 value="$reg_allowed" name=setting[a70]></td>
  </tr>  		
      <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[650]</td>
   <td><input type=radio value=1 $open_regduan name=setting[a83]>$arr_ad_lng[87] <input type=radio value=0 $close_regduan name=setting[a83]>$arr_ad_lng[88]  </td>
  </tr>


  	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><a name="userper">$arr_ad_lng[631]</a></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1197]</td>
   <td><input type="radio" value="1" $switchon[noswitchuser] name="setting[noswitchuser]" />$arr_ad_lng[153]<input type="radio" value="0" $switchoff[noswitchuser] name="setting[noswitchuser]" />$arr_ad_lng[154]</td>
  </tr>
    <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1072]</td>
   <td><input type="radio" value="1" $switchon[hidebyug] name="setting[hidebyug]" />$arr_ad_lng[153]<input type="radio" value="0" $switchoff[hidebyug] name="setting[hidebyug]" />$arr_ad_lng[154]</td>
  </tr>
  	 
    <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[617]</td>
   <td><input type=radio value=1 $open_openbbimg name=setting[g1]>$arr_ad_lng[153]<input type=radio value=0 $close_openbbimg name=setting[g1]>$arr_ad_lng[154]</td>
  </tr>

  <tr bgcolor=F9FAFE>
   <td width=50%><strong>$arr_ad_lng[881]</strong><br />$arr_ad_lng[882]</td>
   <td><input size=35 value="$reg_posting" name=setting[sre2]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[641]</td>
   <td><input size=35 value="$use_honor" name=setting[a27]></td>
  </tr>
   <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[642]</td>
   <td><input size=35 value="$use_group" name=setting[a337]></td>
  </tr>

  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[649]</td>
   <td><input type=radio value=1 $use_own_portait_yes name=setting[a33]>$arr_ad_lng[94] <input type=radio value=0 $use_own_portait_no name=setting[a33]>$arr_ad_lng[95] </td>
  </tr>
 <tr bgcolor=F9FAFE>
   <td width=50%><strong><A name="bbsreason">$arr_ad_lng[877]</a></strong><br />$arr_ad_lng[878]</td>
   <td><textarea name=setting[dt] rows=6 cols=50>$choose_reason</textarea></td>
  </tr>
        <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[651]</td>
   <td><input type=radio value=1 $open_frdduan name=setting[a84]>$arr_ad_lng[87] <input type=radio value=0 $close_frdduan name=setting[a84]>$arr_ad_lng[88]  </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[624]</td>
   <td><input type=radio value=1 $send_mail_yes name=setting[a16]>$arr_ad_lng[153] <input type=radio value=0 $send_mail_no name=setting[a16]>$arr_ad_lng[154]</td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[625]</td>
   <td><input size=35 value="$max_post_length" name=setting[a17]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1073]</td>
   <td><input size=35 value="{$bmfopt['block_keywords']}" name="setting[block_keywords]" /></td>
  </tr>
  	    	    	   <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[654]</td>
   <td><input type=radio value=1 $open_skin name=setting[a46]>$arr_ad_lng[87] <input type=radio value=0 $close_skin name=setting[a46]>$arr_ad_lng[88]  </td>
  </tr>
  			  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[141]</td>
   <td><input size=35 value="$maxwidth" name=setting[a58]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[142]</td>
   <td><input size=35 value="$maxheight" name=setting[a59]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1252]</td>
   <td><input type=radio value=1 $switchon[gravatar] name=setting[gravatar]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[gravatar] name=setting[gravatar]>$arr_ad_lng[95] 
  </tr>
  	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong><A name="bbsper">$arr_ad_lng[659]</A></strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>

  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[661]</td>
   <td><input type=checkbox value=1 $open_fdattach value=1 name=setting[tt1]>$arr_ad_lng[900] <input type=checkbox value=1 $two_tfdattach name=setting[tt2]>$arr_ad_lng[901] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[662]</td>
   <td><input type=radio value=1 $open_autolang name=setting[at1]>$arr_ad_lng[94] <input type=radio value=0 $close_autolang name=setting[at1]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[645]</td>
   <td><input size=35 value="$online_limit" name=setting[a30]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[663]</td>
   <td><input type=radio value=1 $open_saveattbyym name=setting[am1]>$arr_ad_lng[94] <input type=radio value=0 $close_saveattbyym name=setting[am1]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[875]</td>
   <td><input type=radio value=1 $open_emotrand name=setting[em1]>$arr_ad_lng[94] <input type=radio value=0 $close_emotrand name=setting[em1]>$arr_ad_lng[95] </td>
  </tr>


  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[899]</td>
   <td><input type=radio value=1 $open_rcordosbr name=setting[f3a2]>$arr_ad_lng[94] <input type=radio value=0 $close_rcordosbr name=setting[f3a2]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[890]</td>
   <td><input type=radio value=1 $open_cachejs name=setting[fa2]>$arr_ad_lng[94] <input type=radio value=0 $close_cachejs name=setting[fa2]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[891]</td>
   <td><input type=radio value=1 $open_infopics name=setting[fa4]>$arr_ad_lng[94] <input type=radio value=0 $close_infopics name=setting[fa4]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[916]</td>
   <td><input type=radio value=1 $open_cacheclick name=setting[sr7]>$arr_ad_lng[94] <input type=radio value=0 $close_cacheclick name=setting[sr7]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1028]</td>
   <td><input type=radio value=1 $oallow_ajax_reply name=setting[ajax_o]>$arr_ad_lng[94] <input type=radio value=0 $callow_ajax_reply name=setting[ajax_o]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[1029]</td>
   <td><input type=radio value=1 $oallow_ajax_browse name=setting[ajax_b]>$arr_ad_lng[94] <input type=radio value=0 $callow_ajax_browse name=setting[ajax_b]>$arr_ad_lng[95] </td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[630]</td>
   <td><input size=35 value="$flood_control" name=setting[a25]></td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td width=50%><strong>$arr_ad_lng[879]</strong><br />$arr_ad_lng[880]</td>
   <td><input size=35 value="$search_renums" name=setting[sre1]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[643]</td>
   <td><input size=35 value="$refresh_allowed" name=setting[a28]></td>
  </tr>

  <tr bgcolor=F9FAFE>
   <td width=50%>$arr_ad_lng[639]</td>
   <td><input size=35 value="$sea_allowed" name=setting[a73]></td>
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1220]</td>
   <td><input type=radio value=1 $switchon[advanced_headers] name=setting[advanced_headers]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[advanced_headers] name=setting[advanced_headers]>$arr_ad_lng[95] 
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1244]</td>
   <td><input type=radio value=1 $switchon[gzip_attachment] name=setting[gzip_attachment]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[gzip_attachment] name=setting[gzip_attachment]>$arr_ad_lng[95] 
  </tr>
  <tr bgcolor=#F9FCFE>
   <td width=50%>$arr_ad_lng[1065]</td>
   <td><input type=radio value=1 $switchon[rewrite] name=setting[rewrite]>$arr_ad_lng[94] <input type=radio value=0 $switchoff[rewrite] name=setting[rewrite]>$arr_ad_lng[95] 
   	   
   	   
	$table_start<input type=submit value="$arr_ad_lng[66]"> <input type=reset value="$arr_ad_lng[178]">
  </tr>
</form>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    // Process Form
    if (!$setting[a9]) $setting[a9] = 0;
    if (!$setting[a14]) $setting[a14] = 0;
    if (!$setting[a15]) $setting[a15] = 0;
    if (!$setting[a17]) $setting[a17] = 0;
    if (!$setting[a21]) $setting[a21] = 0;
    if (!$setting[a25]) $setting[a25] = 0;
    if (!$setting[a26]) $setting[a26] = 0;
    if (!$setting[a27]) $setting[a27] = 0;
    if (!$setting[a28]) $setting[a28] = 0;
    if (!$setting[a29]) $setting[a29] = 0;
    if (!$setting[a30]) $setting[a30] = 0;
    if (!$setting[a31]) $setting[a31] = 0;
    if (!$setting[a35]) $setting[a35] = 0;
    
    $setting[ft] = str_replace('\"', '"', $setting[ft]);
    $setting[a2] = str_replace('\"', '"', $setting[a2]);
    $setting[a41] = str_replace('\"', '"', $setting[a41]);
    $setting[dt] = str_replace('\"', '"', $setting[dt]);
    $setting['tags_solid'] = strtolower(str_replace('\"', '"', $setting['tags_solid']));
    $setting[ip_address] = str_replace('\"', '"', $setting[ip_address]);
    
    if (!$setting[a4]) {
    	$setting[a4] = ($_SERVER['HTTPS'] ? "https://" : "http://").$_SERVER['HTTP_HOST'].(($_SERVER['SERVER_PORT'] != 443 && $_SERVER['SERVER_PORT'] != 80) ? ":".$_SERVER['SERVER_PORT'] : "").dirname($_SERVER['REQUEST_URI'])."/";
    }
    
    if (!$setting[a6]) {
    	$setting[a6] = ($_SERVER['HTTPS'] ? "https://" : "http://").$_SERVER['HTTP_HOST'].(($_SERVER['SERVER_PORT'] != 443 && $_SERVER['SERVER_PORT'] != 80) ? ":".$_SERVER['SERVER_PORT'] : "").dirname($_SERVER['REQUEST_URI'])."/";
    }
    
    $inviteallow = @implode(",", $inviteug);

    $setting[a30] *= 60;

    $filecontent = "<?php
define(\"CONFIGLOADED\", 1);
error_reporting(E_ALL ^ E_NOTICE);
\$database_up=	'$database_up';
\$sqltype=	'$sqltype';
\$db_name=	'$db_name';
\$db_username=	'$db_username';
\$db_password=	'$db_password';
\$db_server=	'$db_server';
\$mysqlchar=	'$mysqlchar';
\$bbs_is_open	=	'$setting[a1]';
\$bbs_close_msg	=	'$setting[a2]';
\$bbs_title	=	'$setting[a3]';
\$short_title	=	'$setting[short_title]';
\$script_pos	=	'$setting[a4]';
\$site_name	=	'$setting[a5]';
\$site_url	=	'$setting[a6]';
\$keyword_site	=	'$setting[a7]';
\$admin_email	=	'$setting[a8]';
\$max_sign_length=	'$setting[a9]';
\$sign_use_bmfcode=	'$setting[a10]';
\$bmfcode_sign['pic']=	'$setting[a11]';
\$bmfcode_sign['flash']='$setting[a12]';
\$bmfcode_sign['fontsize']='$setting[a13]';
\$perpage	=	'$setting[a14]';
\$read_perpage	=	'$setting[a15]';
\$send_mail	=	'$setting[a16]';
\$max_post_length=	'$setting[a17]';
\$bmfcode_post['pic']=	'$setting[a18]';
\$bmfcode_post['reply']=	'$setting[a85]';
\$bmfcode_post['jifen']=	'$setting[a86]';
\$bmfcode_post['sell']=	'$setting[a87]';
\$bmfcode_post['flash']='$setting[a19]';
\$bmfcode_post['fontsize']='$setting[a20]';
\$echo_reply	=	'$setting[a21]';
\$bmfcode_echo['pic']=	'$setting[a22]';
\$bmfcode_echo['flash']='$setting[a23]';
\$bmfcode_echo['fontsize']='$setting[a24]';
\$flood_control	=	'$setting[a25]';
\$short_msg_max	=	'$setting[a26]';
\$use_honor	=	'$setting[a27]';
\$refresh_allowed=	'$setting[a28]';
\$userperpage	=	'$setting[a29]';
\$online_limit	=	'$setting[a30]';
\$showtime	=	'$setting[a31]';
\$view_forum_online=	'$setting[a32]';
\$use_own_portait=	'$setting[a33]';
\$allow_upload	=	'$setting[a34]';
\$max_upload_size=	'$setting[a35]';
\$upload_type_available='$setting[a36]';
\$bmfcode_post['mpeg']='$setting[a37]';
\$bmfcode_post['iframe']='$setting[a38]';
\$bbs_money=	'$setting[a39]';
\$reg_stop=	'$setting[a40]';
\$reg_close_msg	=	'$setting[a41]';
\$ads_select='$setting[a43]';
\$frep_select='$setting[a44]';
\$fnew_select='$setting[a45]';
\$fnew_skin='$setting[a46]';
\$max_poll='$setting[a47]';
\$max_zzd='$setting[a48]';
\$max_qzd='$setting[a648]';
\$delrmb='$setting[a52]';
\$post_money='$setting[a51]';
\$page_regtips='$setting[a53]';
\$index_regtips='$setting[a54]';
\$all_regtips='$setting[a55]';
\$maxwidth='$setting[a58]';
\$maxheight='$setting[a59]';
\$opencutusericon='$setting[a60]';
\$postbinfo='$setting[a61]';
\$bbs_des='$setting[a64]';
\$bbslogo83='$setting[a65]';
\$usemarquee[0]='$setting[a56]';
\$usemarquee[1]='$setting[a56b]';
\$postjumpurl='$setting[a57]';
\$deljifen='$setting[a67]';
\$post_jifen='$setting[a68]';
\$reg_money='$setting[a69]';
\$reg_allowed='$setting[a70]';
\$max_upload_post='$setting[a71]';
\$gvf='$setting[a72]';
\$sea_allowed='$setting[a73]';
\$reg_r_money='$setting[a75]';
\$openupusericon='$setting[a76]';
\$max_avatars_upload_size='$setting[a77]';
\$max_avatars_upload_post='$setting[a78]';
\$upload_avatars_type_available='$setting[a79]';
\$reauto='$setting[a81]';
\$send_pass='$setting[a82]';
\$regduan='$setting[a83]';
\$frdduan='$setting[a84]';
\$send_msg_max='$setting[a89]';
\$send_msg='$setting[a88]';
\$bbs_news='$setting[a90]';
\$use_group='$setting[a337]';
\$gzip_compress='$setting[a1256]';
\$scclose='$setting[a2221]';
\$reg_va='$setting[arr87]';
\$log_va='$setting[arr88]';
\$view_index_online='$setting[ba32]';
\$recclose='$setting[a2v21]';
\$todayb_show='$setting[tod1]';
\$openbbimg='$setting[g1]';
\$maxlogintry='$setting[mlt]';
\$cookie_d='$setting[cookie1]';
\$cookie_p='$setting[cookie2]';
\$fdattach='$setting[tt1]';
\$autolang='$setting[at1]';
\$sess_cust='$setting[sesscut]';
\$saveattbyym='$setting[am1]';
\$emotrand='$setting[em1]';
\$fastmanage='$setting[f56]';
\$choose_reason='$setting[dt]';
\$search_renums='$setting[sre1]';
\$reg_posting='$setting[sre2]';
\$fastlogin='$setting[fa1]';
\$cachejs='$setting[fa2]';
\$infopics='$setting[fa4]';
\$set_forinfo='$setting[i25]';
\$fjsjump='$setting[f2a2]';
\$rcordosbr='$setting[f3a2]';
\$tfdattach='$setting[tt2]';
\$max_regname_length='$setting[srl2]';
\$min_regname_length='$setting[srl1]';
\$max_fen_length='$setting[srl4]';
\$min_fen_length='$setting[srl3]';
\$max_money_length='$setting[srl6]';
\$min_money_length='$setting[srl5]';
\$listmmlist='$setting[mm4]';
\$cacheclick='$setting[sr7]';
\$invit_del_point='$setting[a119]';
\$reg_invit='$setting[a120]';
\$reg_oneemail='$setting[a121]';
\$invit_send_point='$setting[a122]';
\$ugactive='$setugactive';
\$emot_every='$setting[erl1]';
\$emot_lines='$setting[erl2]';
\$allow_ajax_reply='$setting[ajax_o]';
\$allow_ajax_browse='$setting[ajax_b]';
\$gd_auth='$setting[auen]';
\$bmfopt[showsubforum]='$setting[fa5]';
\$admin_idname='$setting[b1]';
\$bmfopt[invite_type]='$setting[invite_type]';
\$footer_copyright='$setting[ft]';
\$bmfopt[view_newpost]='$setting[view_newpost]';
\$bmfopt[view_ranking]='$setting[view_ranking]';
\$bmfopt[ip_address]='$setting[ip_address]';
\$bmfopt[return_opage]='$setting[return_opage]';
\$bmfopt[hot_tags]='$setting[hot_tags]';
\$bmfopt[tags_max_similar]='$setting[tags_max_similar]';
\$bmfopt['rewrite']='$setting[rewrite]';
\$bmfopt['hidebyug']='$setting[hidebyug]';
\$bmfopt['block_keywords']='$setting[block_keywords]';
\$bmfopt['watermark']='$setting[watermark]';
\$bmfopt['wmsize']='$setting[wmsize]';
\$bmfopt['wmtrans']='$setting[wmtrans]';
\$bmfopt['wmposition']='$setting[wmposition]';
\$bmfopt['wmpadding']='$setting[wmpadding]';
\$bmfopt['display_ftag']='$setting[display_ftag]';
\$bmfopt['text_wm']='$setting[text_wm]';
\$bmfopt['noswitchuser']='$setting[noswitchuser]';
\$bmfopt['invitesugroup']='$setting[aig]';
\$bmfopt['invite_past']='$setting[aip]';
\$bmfopt['inviteallow']='$inviteallow';
\$bmfopt['newpms']='$setting[newpms]';
\$bmfopt['advanced_headers']='$setting[advanced_headers]';
\$bmfopt['article_desc']='$setting[article_desc]';
\$bmfopt['hide_copyright']='$setting[hide_copyright]';
\$bmfopt['gravatar']='$setting[gravatar]';
\$bmfopt['gzip_attachment']='$setting[gzip_attachment]';
\$bmfopt['default_ep']='$setting[eil1]';
\$bmfopt['sitekey']='{$bmfopt['sitekey']}';
";

	$providerIdArr = array();
	
	if($oauthLang['provider']) {
		foreach($oauthLang['provider'] as $providerId => $providerName) {
			$providerIdArr[] = $providerId;
			
			$filecontent .= "
\$bmfopt['oauth']['$providerId']['appKey']='{$setting['oauth'][$providerId]['appKey']}';
\$bmfopt['oauth']['$providerId']['appSecret']='{$setting['oauth'][$providerId]['appSecret']}';";
		}
	}
	foreach($bmfopt['oauth'] as $orgProviderId => $orgProvider) {
		if(in_array($orgProviderId, $providerIdArr)) {
			continue;
		}
		$filecontent .= "
\$bmfopt['oauth']['$orgProviderId']['appKey']='{$bmfopt['oauth'][$orgProviderId]['appKey']}';
\$bmfopt['oauth']['$orgProviderId']['appSecret']='{$bmfopt['oauth'][$orgProviderId]['appSecret']}';";
	}
    if ($setting[a34] && !file_exists("upload")) mkdir("upload", 0777);
    writetofile("datafile/config.php", $filecontent);
    
	@include("datafile/cache/tags_topic.php");
	
	$add_line = "<?php\n";
	$add_line .= "\$tags_tlist['tags_solid'] = '{$setting['tags_solid']}';\n";
	
	if (is_array($tags_tlist)){
		foreach ($tags_tlist as $key=>$value) {
			if ($key != 'tags_solid') 
				$add_line.= "\$tags_tlist[$key]='$value';\n";
		}
	}
	
	writetofile("datafile/cache/tags_topic.php", $add_line);
    
$search_xml=
'<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName>'.$setting[a3].'</ShortName>
<Description>'.$setting[a3].'</Description>
<InputEncoding>UTF-8</InputEncoding><Url type="text/html" template="'.$setting[a4].'/search.php?keyword={searchTerms}&amp;method=or&amp;method1=1&amp;method2=120"/>
</OpenSearchDescription>
';
	writetofile("datafile/cache/search.xml", $search_xml);
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[183]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
} 
