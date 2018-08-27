<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 Function : Installing & Repair
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project - GNU Licence project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
@define("INSTALLER", 1);
@ini_set('display_error', 1);
@define("INBMFORUM", 1);
require_once("include/version.php");
include_once("install/func.inc.php");
if ($language) {
	include_once("install/languages/". $language .".php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $read_alignment;?>" lang="<?php echo $html_lang;?>">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="images/bsd12/styles.css" />
<title>BMForum Setup - powered by BMForum.com</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="generator" content="BMForum Installer" />
<meta name="keywords" content="php,bmb,bmf,蓝色,魔法,论坛,bbs,bmforum,text,蓝魔,免费" />
</head>

<body>
<div id="totallayer" style="position:relative; left:1.3%; top:0px; width:95%; height:auto; z-index:1; border: none; " class="bmforum_background">

<table class="tableborder" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td>		
	<table class="bmbnewstyle_withoutwidth" cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
		<tr>
			<td>
			<table cellspacing="0" cellpadding="5" width="100%" border="0">
				<tr>
					<td class="bmforum_base_menu">
					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td align="left">
							<span class="titlefontcolor">
							BMForum Setup - <?php echo $verandproname; ?></span></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</td></tr></table>
    <style type="text/css">
    .t {font-family: Verdana, Arial, Sans-serif;font-size  : 12px;padding-left: 10px;font-weight: normal;line-height: 150%;color : #29338A;}
    .e {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #0000EE;}
    .w {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #EE0000;}
    .h {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 16px;font-weight: bold;color : #29338A;}
    .i {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 12px;font-weight: bold;color : #29338A;}
    </style>
<form action="install.php?tables=<?php echo $setdatabase[a8];?>" method="post" style="margin:0px;">
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font"><a name="top"></a>BMForum Forum System</span>
</td></tr>
<tr>
<td class="forumcoloronecolor" style="text-align:left;">

<?php
if (!$language) {
	$selectbox = refresh_language();
	echo $selectbox;
    $step = 999;
} else {
	// international version
	if ($international) writetofile("datafile/language.php", "<?php \$language='$forum_pack_name'; \$count_language=	'3';");
	
    if (!(copy("install/usertitle/usertitle.". $language .".php", "datafile/usertitle.php"))) {
    	echo "<font color='red' size='4'>$li[74] datafile/usertitle.php</font>";
    	exit;
    }
	
?>
			<?php echo $li[2]?>
			<br /><b><?php echo $li[3]?></b>
			<br />
			<span class='e'><?php echo $li[4]?></span>
			<br />
			<span class='w'><?php echo $li[5]?></span>
<?php
}
?>
		</td>
		</tr>
<?php
if (!$step) {
    $check = 1;
    $current_time = date("F j, Y, g:i:s a");
    $current_gmt_time = gmdate("F j, Y, g:i:s a");
    if (version_compare(PHP_VERSION, "5.3.0") < 0) {
        $warningver = "<font class=w>$li[6]</font><br />";
    } 
    if (!extension_loaded('mysqli')) {
        $warningver = "<font class=w>MySQLi Error</font><br />";
    } 
    $phpos = PHP_OS;
    if (@isset($_COOKIE)) $testcookie = "<font color=#29338A>$li[7]</font>";
    else $testcookie = "<font class=w>$li[8]</font>";
    if (@isset($_GET)) $testget = "<font color=#29338A>$li[7]</font>";
    else $testget = "<font class=w>$li[8]</font>";
    if (@get_cfg_var("file_uploads")) $testupload = "<font color=#29338A>$li[7]</font> ($li[10] " . @get_cfg_var("upload_max_filesize") . ")";
    else $testupload = "<font color=990000>$li[9]</font>";
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[11]<input type=hidden value=2 name=step><input type=hidden value='$language' name=language></span>
</td></tr>

  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>
$li[12] <font color=#29338A>$phpver</font>
<br />$li[13] <font color=#29338A>$phpos</font>
<br />$li[14] $testcookie
<br />$li[15] $testget
<br />$li[16] $testupload
<br />$li[67]&nbsp; &nbsp;<font color=#29338A>BMForum <span id='snews'></span></font>
<br />$li[66]&nbsp; &nbsp;<font color=#29338A>$verandproname</font>
<span id='vnews'><br/><strong style='color:red;'>$li[68]</strong></span>
<br />$li[17] <b><font face=verdana>$current_time ($li[18] $current_gmt_time)</font></b><br />$warningver
EOT;
echo<<<EOT
</td></tr>
EOT;

    include("datafile/config.php");

    if ($mysqlchar == 1) {
        $aschecked = "checked='checked'";
    } else {
        $bschecked = "checked='checked'";
    } 
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[19]</span>
</td></tr>

  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>
   $li[20]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT size=35 value='$database_up' name=setdatabase[a1]></td>
  </tr>
  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[21]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><select name=setdatabase[a2]><option checked value=mysql>MySQL</option></select></td>
  </tr>
  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[22]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT size=35 value='$db_name' name=setdatabase[a3]></td>
  </tr>
  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[23]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT size=35 value='$db_username' name=setdatabase[a4]></td>
  </tr>
  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[24]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT size=35 value='$db_password' type="password" name=setdatabase[a5]></td>
  </tr>
  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[25]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT size=35 value='$db_server' name=setdatabase[a6]></td>
  </tr>
 <tr>
<td class='forumcoloronecolor' style='text-align:left;'>$li[69]</td>
   <td class='forumcoloronecolor' style='text-align:left;'><INPUT type='radio' style="width:5%" value="1" name='setdatabase[a8]' />$li[70]<INPUT type='radio' checked='checked' style="width:5%" value="0" name='setdatabase[a8]' />$li[71]</td>
  </tr>
    <tr><td class='forumcolortwo_noalign' style='text-align:center;' colspan="2"><INPUT style="WIDTH: 50%; FONT-FAMILY: Verdana" type=submit value="$li[29]"></form></TD></TR>
EOT;
} elseif ($step == 2) {
    $check = 1;

    include("datafile/config.php");

echo<<<EOT

</td></tr>
</table>
</td></tr></table>

EOT;

    $filecontent = "<?php
define(\"CONFIGLOADED\", 1);
error_reporting(E_ALL ^ E_NOTICE);
\$database_up=	'$setdatabase[a1]';
\$sqltype=	'$setdatabase[a2]';
\$db_name=	'$setdatabase[a3]';
\$db_username=	'$setdatabase[a4]';
\$db_password=	'$setdatabase[a5]';
\$db_server=	'$setdatabase[a6]';
\$mysqlchar=	'1';
\$_remove_oldtables=	'$setdatabase[a8]';
\$bbs_is_open	=	'$bbs_is_open';
\$bbs_close_msg	=	'$bbs_close_msg';
\$bbs_title	=	'$bbs_title';
\$script_pos	=	'$script_pos';
\$site_name	=	'$site_name';
\$site_url	=	'$site_url';
\$keyword_site	=	'$keyword_site';
\$admin_email	=	'$admin_email';
\$max_sign_length=	'$max_sign_length';
\$sign_use_wdbcode=	'$sign_use_wdbcode';
\$wdbcode_sign['pic']=	'{$wdbcode_sign[pic]}';
\$wdbcode_sign['flash']='{$wdbcode_sign[flash]}';
\$wdbcode_sign['fontsize']='{$wdbcode_sign[fontsize]}';
\$perpage	=	'$perpage';
\$read_perpage	=	'$read_perpage';
\$send_mail	=	'$send_mail';
\$max_post_length=	'$max_post_length';
\$wdbcode_post['pic']=	'{$wdbcode_post[pic]}';
\$wdbcode_post['reply']=	'{$wdbcode_post[reply]}';
\$wdbcode_post['jifen']=	'{$wdbcode_post[jifen]}';
\$wdbcode_post['sell']=	'{$wdbcode_post[sell]}';
\$wdbcode_post['flash']='{$wdbcode_post[flash]}';
\$wdbcode_post['fontsize']='{$wdbcode_post[fontsize]}';
\$echo_reply	=	'$echo_reply';
\$wdbcode_echo['pic']=	'{$wdbcode_echo[pic]}';
\$wdbcode_echo['flash']='{$wdbcode_echo[flash]}';
\$wdbcode_echo['fontsize']='{$wdbcode_echo[fontsize]}';
\$flood_control	=	'$flood_control';
\$short_msg_max	=	'$short_msg_max';
\$use_honor	=	'$use_honor';
\$refresh_allowed=	'$refresh_allowed';
\$userperpage	=	'$userperpage';
\$online_limit	=	'$online_limit';
\$showtime	=	'$showtime';
\$view_forum_online=	'$view_forum_online';
\$use_own_portait=	'$use_own_portait';
\$allow_upload	=	'$allow_upload';
\$max_upload_size=	'$max_upload_size';
\$upload_type_available='$upload_type_available';
\$wdbcode_post['mpeg']='{$wdbcode_post[mpeg]}';
\$wdbcode_post['iframe']='{$wdbcode_post[iframe]}';
\$bbs_money=	'$bbs_money';
\$reg_stop=	'$reg_stop';
\$reg_close_msg	=	'$reg_close_msg';
\$ads_select='$ads_select';
\$frep_select='$frep_select';
\$fnew_select='$fnew_select';
\$fnew_skin='$fnew_skin';
\$max_poll='$max_poll';
\$max_zzd='$max_zzd';
\$max_qzd='$max_qzd';
\$delrmb='$delrmb';
\$post_money='$post_money';
\$page_regtips='$page_regtips';
\$index_regtips='$index_regtips';
\$all_regtips='$all_regtips';
\$cancel_guest='$cancel_guest';
\$maxwidth='$maxwidth';
\$maxheight='$maxheight';
\$opencutusericon='$opencutusericon';
\$postbinfo='$postbinfo';
\$bbs_des='$bbs_des';
\$bbslogo83='$bbslogo83';
\$usemarquee='$usemarquee';
\$postjumpurl='$postjumpurl';
\$deljifen='$deljifen';
\$post_jifen='$post_jifen';
\$reg_money='$reg_money';
\$reg_allowed='$reg_allowed';
\$max_upload_post='$max_upload_post';
\$gvf='$gvf';
\$sea_allowed='$sea_allowed';
\$svf='$svf';
\$reg_r_money='$reg_r_money';
\$openupusericon='$openupusericon';
\$max_avatars_upload_size='$max_avatars_upload_size';
\$max_avatars_upload_post='$max_avatars_upload_post';
\$upload_avatars_type_available='$upload_avatars_type_available';
\$swf='$swf';
\$reauto='$reauto';
\$send_pass='$send_pass';
\$regduan='$regduan';
\$frdduan='$frdduan';
\$send_msg_max='$send_msg_max';
\$send_msg='$send_msg';
\$bbs_news='$bbs_news';
\$use_group='$use_group';
\$gzip_compress='$gzip_compress';
\$scclose='$scclose';
\$reg_va='$reg_va';
\$log_va='$log_va';
\$view_index_online='$view_index_online';
\$recclose='$recclose';
\$todayb_show='$todayb_show';
\$openbbimg='$openbbimg';
\$maxlogintry='$maxlogintry';
\$cookie_d='$cookie_d';
\$cookie_p='$cookie_p';
\$fdattach='$fdattach';
\$autolang='$autolang';
\$sess_cust='$sess_cust';
\$saveattbyym='$saveattbyym';
\$refreshpostrank='$refreshpostrank';
\$emotrand='$emotrand';
\$fastmanage='$fastmanage';
\$choose_reason='$choose_reason';
\$search_renums='$search_renums';
\$reg_posting='$reg_posting';
\$fastlogin='$fastlogin';
\$cachejs='$cachejs';
\$infopics='$infopics';
\$set_forinfo='$set_forinfo';
\$fjsjump='$fjsjump';
\$rcordosbr='$rcordosbr';
\$tfdattach='$tfdattach';
\$max_regname_length='$max_regname_length';
\$min_regname_length='$min_regname_length';
\$listmmlist='$listmmlist';
\$cacheclick='$cacheclick';
\$invit_del_point='$invit_del_point';
\$reg_invit='$reg_invit';
\$reg_oneemail='$reg_oneemail';
\$invit_send_point='$invit_send_point';
\$ugactive='$ugactive';
\$emot_every='$emot_every';
\$emot_lines='$emot_lines';
\$allow_ajax_reply='$allow_ajax_reply';
\$allow_ajax_browse='$allow_ajax_browse';
\$gd_auth='$gd_auth';
\$admin_idname='{replace_admin_id_name_here}';
\$bmfopt[showsubforum]='$bmfopt[showsubforum]';
\$bmfopt[invite_type]='$bmfopt[invite_type]';
\$bmfopt[ip_address]='$bmfopt[ip_address]';
\$bmfopt[hot_tags]='$bmfopt[hot_tags]';
\$bmfopt[tags_max_similar]='$bmfopt[tags_max_similar]';
\$footer_copyright='$footer_copyright';
\$bmfopt[display_ftag]=1;
\$bmfopt['noswitchuser']='$bmfopt[noswitchuser]';
\$bmfopt['newpms']='$bmfopt[newpms]';
\$bmfopt['default_ep']='$bmfopt[default_ep]';
";
    writetofile("datafile/config.php", $filecontent);

    include("datafile/config.php");
    require("include/db/db_{$sqltype}.php");
    bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

    $sqlcharset= " DEFAULT CHARSET=utf8mb4";
    $dataEngine = " ENGINE=MyISAM";
    
    if (!bmbdb_select_db($db_name)) {
        bmbdb_query("CREATE DATABASE `$db_name`");
        if (bmbdb_error()) {
            $status.= "<li>$li[41] ($db_name)</li>";
            $check = 0;
        } else {
            bmbdb_select_db($db_name);
            $status.= "<li>$li[42] ($db_name)</li>";
        } 
    } 

   // Check charset
    $timestamp = time();
    bmbdb_query("CREATE TABLE `{$database_up}testtable{$timestamp}` ( `teststring` text NOT NULL){$dataEngine}{$sqlcharset}");
    bmbdb_query("INSERT INTO `{$database_up}testtable{$timestamp}` ( `teststring` ) VALUES ('请选择合适的语言請選擇合適的語言测试中文乱码')");
    $test_result = bmbdb_query("SELECT * FROM `{$database_up}testtable{$timestamp}`");
    $the_string = bmbdb_fetch_array($test_result);

    if ($the_string['teststring'] != '请选择合适的语言請選擇合適的語言测试中文乱码') {
    	$status.= "<li>$li[73]</li>";
    }
    bmbdb_query("DROP TABLE `{$database_up}testtable{$timestamp}`");

// end

    if ($check) {
echo<<<EOT
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
	<INPUT type=hidden value=3 name=step><INPUT type=hidden value='$language' name=language><span class="categoryfontcolor_font">$li[32]</span>
</td></tr>

<tr>
<td class="forumcolortwo_noalign" style="text-align:left;" colspan="2"><br /><ul><li>$li[31]</li>$status</ul></td>
        </tr><tr>
	 <td class="forumcoloronecolor" style="text-align:left;">&nbsp;&nbsp;$li[33]</td>
	 <td class="forumcoloronecolor" style="text-align:left;"><input type='text' name='MEMBER_NAME'></td>
	</tr><tr>
	 <td class="forumcoloronecolor" style="text-align:left;">&nbsp;&nbsp;$li[34]</td>
	 <td class="forumcoloronecolor" style="text-align:left;"><input type='password' name='MEMBER_PASS'></td>
	</tr><tr>
	 <td class="forumcoloronecolor" style="text-align:left;">&nbsp;&nbsp;$li[35]</td>
	 <td class="forumcoloronecolor" style="text-align:left;"><input type='password' name='MEMBER_PASS_TWO'></td>
	</tr><tr>
	 <td class="forumcoloronecolor" style="text-align:left;">&nbsp;&nbsp;$li[36]</td>
	 <td class="forumcoloronecolor" style="text-align:left;"><input type='text' name='EMAIL'></td>
	</tr><tr>
	 <td class="forumcoloronecolor" style="text-align:left;">&nbsp;&nbsp;$li[37]</td>
	 <td class="forumcoloronecolor" style="text-align:left;"><input type='text' name='EMAIL_TWO'></td>
	</tr><tr>
	 <td class="forumcoloronecolor" style="text-align:center;" colspan="2"><input type='submit' value='$li[29]' style='font-family:Verdana;width:50%'></td>
	<tr>
EOT;
    } 
} elseif ($step == 3) {
    include("datafile/config.php");
    require("include/db/db_{$sqltype}.php");

    
    bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

    $check = 1;
    
echo<<<EOT

</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[38]</span></td></tr>
</td></tr>
<tr>
<td class="forumcoloronecolor" style="text-align:left;">
EOT;
    if ($MEMBER_PASS != $MEMBER_PASS_TWO) {
        echo "<span class='w'>$li[39]</span><br/>";
        $check = 0;
    } 

    if ($EMAIL != $EMAIL_TWO) {
        echo "<span class='w'>$li[40]</span><br/>";
        $check = 0;
    } 
    
    $sqlcharset= " DEFAULT CHARSET=utf8mb4";
    $dataEngine = " ENGINE=MyISAM";

    if ($check) {
    	$sqldata = readfromfile('install/db.sql');
    	
    	$parsed = explode(';', $sqldata);
    	
    	for($i = 0; $i < count($parsed); $i++)
    	{
    		if(!trim($parsed[$i])) {
    			continue;
    		}
    		$tablename = explode('`PRENAME_', $parsed[$i]);
    		$tablename = explode('`', $tablename[1]);
    		$tablename = $tablename[0];
    		if($_remove_oldtables) {
    			bmbdb_query("DROP TABLE IF EXISTS `{$database_up}$tablename` ");
    		}
    		$parsed[$i] = str_replace('`PRENAME_', "`{$database_up}", $parsed[$i]);
    		bmbdb_query($parsed[$i]."{$dataEngine}{$sqlcharset}");
    		echo "<span class='i'>$li[43] {$database_up}$tablename</span><br />";
    	}

        $MEMBER_PASS_W = md5($MEMBER_PASS);

        $result = bmbdb_query("INSERT INTO `{$database_up}userlist` (`username`, `pwd`, `avarts`, `mailadd`, `qqmsnicq`, `regdate`, `signtext`, `homepage`, `fromwhere`, `desper`, `headtitle`, `lastpost`, `postamount`, `publicmail`, `mailtype`, `point`, `pwdask`, `pwdanswer`, `ugnum`, `money`, `birthday`, `team`, `sex`, `national`, `lastlogin`, `tlastvisit`, `deltopic`, `delreply`, `uploadfiletoday`, `foreuser`, `hisipa`, `hisipb`, `hisipc`, `newmess`, `baoliu1`, `baoliu2`, `personug`, `activestatus`) VALUES ('$MEMBER_NAME', '$MEMBER_PASS_W', '', '$EMAIL', '※※', '" . time() . "', '', '', '', '', '', 0, 0, '0', 'text', '0', '', '', '0', '0', '', '', '', '', 0, 0, '0', '0', '0', '', '', '', '', 0, '', '', '', '')");
        $result = bmbdb_query("INSERT INTO `{$database_up}lastest` (`pageid`, `lastreged`, `regednum`, `threadnum`, `postsnum`, `todaynew`, `lasttodaytime`, `ydaynew`, `maxnews`, `lastposts`, `lastposter`, `lastpostid`, `lastptime`, `zytime`, `zypeople`) VALUES ('index', '$MEMBER_NAME', 1, 0, 0, 0, 0,0,0,'', '', 0, 0, '', '')");
        $result = bmbdb_query("insert into {$database_up}shareforum (name,url,gif,des,type,showorder) values ('BMForum','http://www.bmforum.com/bmb/','http://www.bmforum.com/bmb/logo8.gif','BMForum 官方讨论区,最好的 PHP 论坛','pic',0)");
        $result = bmbdb_query("insert into {$database_up}shareforum (name,url,gif,des,type,showorder) values ('BMForum Official Site','http://www.bmforum.com/bmb/','','BMForum 官方讨论区,最好的 PHP 论坛','',1)");

        $filecontent = str_replace("{replace_admin_id_name_here}", "$MEMBER_NAME", readfromfile("datafile/config.php"));
        writetofile("datafile/config.php", $filecontent);
    
        echo "<span class='i'>$li[44]</span><br />";
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[45]</span>
</td></tr>
<tr>
<td class="forumcoloronecolor" style="text-align:left;">
<b>Name</b>: $MEMBER_NAME <br /><b>Password: $MEMBER_PASS </b>
<br /><br />
	<span style='color:#206FCA;'><a style='color:#206FCA;font-size:12pt;' href='install.php?step=4&language=$language'>$li[29]</a></span>

EOT;
    } else {
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr>
<td class="forumcoloronecolor" style="text-align:center;">
<input onclick='history.go(-1)' type='button' value='$li[61]' style='font-family:Verdana;width:50%'>

EOT;

	}
} elseif ($step == 4) {
    include("datafile/config.php");
    require("include/db/db_{$sqltype}.php");
    bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

    $check = 1;
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[46]</span>
</td></tr>
<tr>
<td class="forumcoloronecolor" style="text-align:left;">


EOT;
	$result = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(`id`) FROM `{$database_up}forumdata`"));
	if ($result['COUNT(`id`)'] == 0) {
    	$result = bmbdb_query("INSERT INTO `{$database_up}forumdata` (`type`, `bbsname`, `cdes`, `id`, `blad`, `forum_icon`, `filename`, `forumpass`, `forum_cid`, `guestpost`, `forum_ford`, `postdontadd`, `spusergroup`, `naviewpost`, `adminlist`, `topicnum`, `replysnum`, `fltitle`, `flposttime`, `flposter`, `flfname`, `showorder`, `usergroup`, `caterows`, `todayp`, `todaypt`, `jumpurl`) VALUES  ('category', '$li[47]', '', '1', '', '', '', '', '', '', '___', '', '', '', '', 0, 0, '', '', '', '', 1, '',0,0,0,'')");
    	$result = bmbdb_query("INSERT INTO `{$database_up}forumdata` (`type`, `bbsname`, `cdes`, `id`, `blad`, `forum_icon`, `filename`, `forumpass`, `forum_cid`, `guestpost`, `forum_ford`, `postdontadd`, `spusergroup`, `naviewpost`, `adminlist`, `topicnum`, `replysnum`, `fltitle`, `flposttime`, `flposter`, `flfname`, `showorder`, `usergroup`, `caterows`, `todayp`, `todaypt`, `jumpurl`) VALUES ('forum', '$li[48]', '$li[49]', '2', '', '', '', '', '1', '', '___', '', '', '', '', 0, 0, '', '', '', '', 2, '',0,0,0,'')");
	    echo "<span class=\"i\">$li[50]</span><br />";
	}
    // {$database_up}
	$result = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(`id`) FROM `{$database_up}usergroup`"));
    if ($result['COUNT(`id`)'] == 0) {
    
    $query = "INSERT INTO `{$database_up}usergroup` VALUES (0, '$li[51]|messages0.gif|1|1|1|1|1|999999|1|1|1|1|1|1|9999999|999|999|1|1|2048000|jpg gif zip rar png ace iso txt bmp xls|1|1|teamad.gif|1|10|yes|999|0|1000|1|1|1|10|5|2|2|1|0|1|1|500000|0|jpg gif png|180|180|1|1|1|1|1|1||1|1|1|1|1|1|1|1|1|1|1|1|2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|1|0|1|1|5|0|50|50|0|1||0|0|0|1|1|1|0|3|0|0|0|0|1|-10|10|-100|100|1|0|0|1|1|1', '1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1||1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|', '0', 1, '')
INSERT INTO `{$database_up}usergroup` VALUES (1, '$li[52]|messages7.gif|1|1|1|1|1|300|1|1|1|1|1|1|30000|300|200|1|0|512000|jpg gif zip rar png ace iso txt|1|1|teamsmo.gif|1|8|no|10|0|500|1|0|1|10|5|2|2|1|0|1|1|300000|0|jpg gif png|150|150|1|1|1|1|1|1||1|1|1|1|1|1|1|1|1|1|0|1|2|1|1|1|1|1|1|1|1|1|0|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|1||0|0|0|1|1|1|0|3|0|0|0|0|1|-10|10|-100|100|1|0|0|1|1|1', '0|0|0|0|1|0|1|0|1|0|0|0|0|0|1|0|0|0|0|0|0|0|0|1|1||0|0|0|0|1|1|1|0|0|0|0|0|0|0|', '0', 2, '')
INSERT INTO `{$database_up}usergroup` VALUES (2, '$li[53]|messages4.gif|1|1|1|1|1|250|1|1|1|1|1|1|20000|250|100|1|0|512000|jpg gif zip rar png ace iso|0|0|teammo.gif|1|7|no|6|0|300|0|0|1|10|5|2|2|1|0|1|1|250000|0|jpg gif png|140|140|1|1|1|1|1|1||0|0|1|1|1|0|1|0|1|1|0|1|2|1|1|1|1|1|1|1|1|1|0|1|1|1|1|1|1|1|1|1|1|0|1|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|1|1|1|0|3|0|0|0|0|0|-10|10|-100|100|1|0|0|1|1|1', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0||', '0', 4, '')
INSERT INTO `{$database_up}usergroup` VALUES (3, '$li[54]|messages6.gif|1|1|1|1|1|200|1|1|1|1|1|1|20000|200|50|1|0|512000|jpg gif zip rar png ace txt|0|0|teamrz.gif|0|5|no|5|0|200|0|0|0|10|5|2|2|1|0|1|1|200000|0|jpg gif png|130|130|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|1|2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|3|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|10|0|100|1|0|0|0|0|', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0||', '0', 5, '')
INSERT INTO `{$database_up}usergroup` VALUES (4, '$li[55]|messages1.gif|1|1|1|1|1|200|1|1|0|1|0|1|20000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|2|no|3|0|100|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|1|2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|3|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|1|0|50|1|0|0|0|0|', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0|', '0', 6, '1')
INSERT INTO `{$database_up}usergroup` VALUES (5, '$li[56]|messages1.gif|1|0|0|0|0|10|0|0|0|0|0|1|0|2|1|0|0|0||0|0||0|0|no|0|99999999999999|0|0|0|0|999|0|999|0|0||0|0||||||0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|1|0|0|0|0|0|0|0|1|1|0|0|', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0||', '1', 7, '')
INSERT INTO `{$database_up}usergroup` VALUES (6, '$li[57]|messages1.gif|1||||||0|0|0|0|0||20000|||||0|||||0|0|no|0|||0|0|0|0|0|0|0|0|9999||||||||1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|||||||0|0|0|0|0|0|1|0|2|50|50|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0||', '1', 8, '')
INSERT INTO `{$database_up}usergroup` VALUES (7, '$li[58]|messages8.gif|1|1|1|1|1|300|1|1|1|1|1|1|30000|300|150|1|0|819200|jpg gif zip rar png ace iso|0|0|teammo.gif|1|8|no|7|0|400|0|0|1|10|5|2|2|1|0|1|1|280000|0|jpg gif png|140|140|1|1|1|1|1|1||0|1|1|1|1|0|1|0|1|1|0|1|2|1|1|1|1|1|1|1|1|1|0|1|1|1|1|1|1|1|1|1|1|0|1||||||||||1|0|1|1|5|2|50|50|0|0||0|0|0|1|1|1|0|3|0|0|0|0|0|-10|10|-100|100|1|0|0|1|1|1', '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0|0|0|0||', '0', 3, '')
INSERT INTO `{$database_up}levels` VALUES (0, '1|messages1.gif|1|1|1|1|1|150|1|1|0|1|0|1|20000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|2|no|3|0|100|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|3|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|0|0|0|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (1, '2|messages1.gif|1|1|1|1|1|150|1|1|0|1|0|1|20000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|2|no|3|0|100|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|3|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|1|0|10|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (2, '3|messages1.gif|1|1|1|1|1|150|1|1|0|1|0|1|20000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|2|no|3|0|100|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|3|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|1|0|10|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (3, '4|messages1.gif|1|1|1|1|1|200|1|1|0|1|0|1|30000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|3|no|4|0|100|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|4|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|2|0|20|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (4, '5|messages1.gif|1|1|1|1|1|200|1|1|0|1|0|1|30000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|3|no|4|0|200|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|3|0|30|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (5, '5|messages1.gif|1|1|1|1|1|200|1|1|0|1|0|1|40000|200|5|1|0|512000|jpg gif zip rar png txt swf|0|0||0|4|no|5|0|300|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|3|0|30|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (6, '6|messages1.gif|1|1|1|1|1|300|1|1|0|1|0|1|40000|200|5|1|0|1024000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|500|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|120|120|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|0|0|0|0|4|0|0|0|0|0|0|4|0|40|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (7, '7|messages1.gif|1|1|1|1|1|500|1|1|0|1|0|1|40000|500|10|1|0|1024000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|500|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|180|180|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|4|0|40|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (8, '8|messages1.gif|1|1|1|1|1|500|1|1|0|1|0|1|40000|500|10|1|0|1024000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|500|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|180|180|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|5|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|5|0|50|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (9, '9|messages1.gif|1|1|1|1|1|500|1|1|0|1|0|1|40000|500|10|1|0|1024000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|500|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|180|180|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|6|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|5|0|50|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (10, '10|messages1.gif|1|1|1|1|1|500|1|1|0|1|0|1|40000|500|15|1|0|1024000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|1000|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|180|180|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|6|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|6|0|60|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}levels` VALUES (11, '11|messages1.gif|1|1|1|1|1|500|1|1|0|1|0|1|40000|500|20|1|0|2048000|jpg gif zip rar png txt swf|0|0||0|6|no|6|0|1000|0|0|0|10|5|2|2|1|0|1|1|100000|10|jpg gif|180|180|1|1|0|0|0|0||0|0|0|0|0|0|0|0|0|0|0||2|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|1|0|0|0|0|0|0|0|0|0|0|0|1|0|1|1|6|2|50|50|0|0||0|0|0|0|0|0|0|3|0|0|0|0|0|0|7|0|70|0|0|0|0|0|', 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (1, '\$arr_ad_lng[80]', 1, 1, 1, 0, 0, 0, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (2, '\$arr_ad_lng[81]', 1, 1, 1, 0, 0, 1, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (3, '\$arr_ad_lng[82]', 1, 1, 1, 0, 0, 23, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (4, '\$arr_ad_lng[83]', 2, 1, 1, 0, 0, 64, 0, 1)
INSERT INTO `{$database_up}ugoptlist` VALUES (5, '\$arr_ad_lng[86]', 3, 0, 0, 1, 0, 3, 88, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (6, '\$arr_ad_lng[89]', 3, 0, 0, 1, 0, 4, 89, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (7, '\$arr_ad_lng[90]', 3, 0, 0, 1, 0, 5, 90, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (8, '\$arr_ad_lng[91]', 3, 0, 0, 1, 0, 6, 91, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (9, '\$arr_ad_lng[121]', 1, 0, 0, 1, 0, 29, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (10, '\$arr_ad_lng[126]', 1, 0, 0, 1, 0, 28, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (11, '\$arr_ad_lng[1210]', 2, 0, 0, 0, 0, 126, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (12, '\$arr_ad_lng[1211]', 2, 0, 0, 0, 0, 127, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (13, '\$arr_ad_lng[1128]', 1, 0, 0, 0, 0, 116, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (14, '\$arr_ad_lng[1129]', 1, 0, 0, 0, 0, 117, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (15, '\$arr_ad_lng[956]', 1, 0, 0, 0, 0, 101, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (16, '\$arr_ad_lng[954]', 2, 0, 0, 0, 0, 99, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (17, '\$arr_ad_lng[1043]', 2, 0, 0, 0, 0, 109, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (18, '\$arr_ad_lng[1044]', 2, 0, 0, 0, 0, 110, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (19, '\$arr_ad_lng[127]', 1, 0, 0, 0, 0, 25, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (20, '\$arr_ad_lng[128]', 2, 0, 0, 0, 0, 37, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (21, '\$arr_ad_lng[1206] \$curl_check', 2, 0, 0, 0, 0, 120, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (22, '\$arr_ad_lng[129]', 1, 0, 0, 0, 0, 38, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (23, '\$arr_ad_lng[130]', 1, 0, 0, 0, 0, 20, 0, 2)
INSERT INTO `{$database_up}ugoptlist` VALUES (24, '\$arr_ad_lng[123]', 1, 0, 0, 0, 0, 19, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (25, '\$arr_ad_lng[124]', 1, 0, 0, 0, 0, 27, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (26, '\$arr_ad_lng[120]', 4, 0, 0, 0, 0, 26, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (27, '\$arr_ad_lng[125]', 2, 0, 0, 0, 0, 80, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (28, '\$arr_ad_lng[104]', 1, 0, 0, 0, 0, 14, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (29, '\$arr_ad_lng[1006]', 1, 0, 0, 0, 0, 102, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (30, '\$arr_ad_lng[1007]', 1, 0, 0, 0, 0, 103, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (31, '\$arr_ad_lng[1008]', 1, 0, 0, 0, 0, 104, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (32, '\$arr_ad_lng[105]', 2, 0, 0, 0, 0, 69, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (33, '\$arr_ad_lng[106]', 2, 0, 0, 0, 0, 70, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (34, '\$arr_ad_lng[107]', 2, 0, 0, 0, 0, 71, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (35, '\$arr_ad_lng[108]', 2, 0, 0, 0, 0, 77, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (36, '\$arr_ad_lng[109]', 2, 0, 0, 0, 0, 78, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (37, '\$arr_ad_lng[110]', 2, 0, 0, 0, 0, 72, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (38, '\$arr_ad_lng[111]', 2, 0, 0, 0, 0, 73, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (39, '\$arr_ad_lng[1127]', 1, 0, 0, 0, 0, 115, 0, 3)
INSERT INTO `{$database_up}ugoptlist` VALUES (40, '\$arr_ad_lng[112]', 2, 0, 0, 0, 0, 74, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (41, '\$arr_ad_lng[113]', 2, 0, 0, 0, 0, 75, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (42, '\$arr_ad_lng[114]', 2, 0, 0, 0, 0, 76, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (43, '\$arr_ad_lng[115]', 2, 1, 0, 0, 0, 79, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (44, '\$arr_ad_lng[92]', 1, 1, 0, 1, 0, 7, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (45, '\$arr_ad_lng[122]', 2, 1, 0, 1, 0, 18, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (46, '\$arr_ad_lng[118]', 2, 1, 0, 1, 0, 17, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (47, '\$arr_ad_lng[93]', 2, 0, 0, 0, 0, 8, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (48, '\$arr_ad_lng[96]', 2, 0, 0, 0, 0, 9, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (49, '\$arr_ad_lng[97]', 2, 0, 0, 0, 0, 10, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (50, '\$arr_ad_lng[98]', 2, 0, 0, 0, 0, 11, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (51, '\$arr_ad_lng[136]', 2, 1, 0, 1, 0, 39, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (52, '\$arr_ad_lng[137]', 2, 1, 0, 1, 0, 40, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (53, '\$arr_ad_lng[138]', 1, 1, 0, 1, 0, 41, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (54, '\$arr_ad_lng[139]', 1, 1, 0, 1, 0, 42, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (55, '\$arr_ad_lng[140]', 1, 1, 0, 1, 0, 43, 0, 2)
INSERT INTO `{$database_up}ugoptlist` VALUES (56, '\$arr_ad_lng[141]', 1, 1, 0, 1, 0, 44, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (57, '\$arr_ad_lng[142]', 1, 1, 0, 1, 0, 45, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (58, '\$arr_ad_lng[1173]', 5, 0, 0, 0, 0, 118, 119, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (59, '\$arr_ad_lng[101]', 3, 0, 0, 0, 0, 47, 94, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (60, '\$arr_ad_lng[102]', 3, 0, 0, 0, 0, 87, 95, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (61, '\$arr_ad_lng[103]', 3, 0, 0, 0, 0, 46, 96, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (62, '\$arr_ad_lng[955]', 2, 0, 0, 0, 0, 100, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (63, '\$arr_ad_lng[99]', 3, 1, 0, 0, 0, 12, 92, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (64, '\$arr_ad_lng[341]', 2, 0, 0, 0, 1, 12, 92, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (65, '\$arr_ad_lng[131]', 2, 1, 0, 0, 0, 81, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (66, '\$arr_ad_lng[132]', 2, 1, 0, 0, 0, 82, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (67, '\$arr_ad_lng[133]', 2, 1, 0, 0, 0, 83, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (68, '\$arr_ad_lng[134]', 2, 1, 0, 0, 0, 84, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (69, '\$arr_ad_lng[915]', 3, 0, 0, 0, 0, 97, 98, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (70, '\$arr_ad_lng[100]', 3, 1, 0, 1, 0, 13, 93, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (71, '\$arr_ad_lng[150]', 2, 0, 0, 0, 0, 67, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (72, '\$arr_ad_lng[1037]', 2, 0, 0, 0, 0, 106, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (73, '\$arr_ad_lng[151]', 2, 0, 0, 0, 0, 68, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (74, '\$arr_ad_lng[116]', 1, 1, 0, 1, 0, 15, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (75, '\$arr_ad_lng[117]', 1, 1, 0, 1, 0, 16, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (76, '\$arr_ad_lng[119]', 2, 1, 0, 1, 0, 86, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (77, '\$arr_ad_lng[144]', 1, 0, 0, 0, 0, 33, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (78, '\$arr_ad_lng[145]', 1, 0, 0, 0, 0, 34, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (79, '\$arr_ad_lng[146]', 1, 0, 0, 0, 0, 65, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (80, '\$arr_ad_lng[147]', 1, 0, 0, 0, 0, 35, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (81, '\$arr_ad_lng[148]', 1, 0, 0, 0, 0, 36, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (82, '\$arr_ad_lng[149]', 1, 0, 0, 0, 0, 66, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (83, '\$arr_ad_lng[1027]', 1, 0, 0, 0, 0, 105, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (84, '\$arr_ad_lng[1040]', 1, 0, 0, 0, 0, 108, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (85, '\$arr_ad_lng[1038]', 1, 0, 0, 0, 0, 107, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (86, '\$arr_ad_lng[1060]', 1, 0, 0, 0, 0, 114, 0, 4)
INSERT INTO `{$database_up}ugoptlist` VALUES (87, '\$arr_ad_lng[913]', 6, 0, 0, 0, 0, 121, 122, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (88, '\$arr_ad_lng[914]', 6, 0, 0, 0, 0, 123, 124, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (89, '\$arr_ad_lng[1207]', 7, 0, 0, 0, 0, 125, 130, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (90, '\$arr_ad_lng[155]', 2, 0, 0, 0, 0, 24, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (91, '\$arr_ad_lng[156]', 2, 1, 0, 1, 0, 21, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (92, '\$arr_ad_lng[157]', 2, 1, 0, 1, 0, 22, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (93, '\$arr_ad_lng[1047]', 2, 0, 0, 0, 0, 111, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (94, '\$arr_ad_lng[159]', 2, 0, 0, 0, 0, 48, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (95, '\$arr_ad_lng[160]', 2, 0, 0, 0, 0, 49, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (96, '\$arr_ad_lng[161]', 2, 0, 0, 0, 0, 50, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (97, '\$arr_ad_lng[162]', 2, 0, 0, 0, 0, 51, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (98, '\$arr_ad_lng[164]', 2, 0, 0, 0, 0, 53, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (99, '\$arr_ad_lng[165]', 2, 0, 0, 0, 0, 54, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (100, '\$arr_ad_lng[166]', 2, 0, 0, 0, 0, 59, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (101, '\$arr_ad_lng[167]', 2, 0, 0, 0, 0, 55, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (102, '\$arr_ad_lng[168]', 2, 0, 0, 0, 0, 56, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (103, '\$arr_ad_lng[169]', 2, 0, 0, 0, 0, 57, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (104, '\$arr_ad_lng[170]', 2, 0, 0, 0, 0, 58, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (105, '\$arr_ad_lng[171]', 2, 0, 0, 0, 0, 60, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (106, '\$arr_ad_lng[172]', 2, 0, 0, 0, 0, 61, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (107, '\$arr_ad_lng[173]', 2, 0, 0, 0, 0, 62, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (108, '\$arr_ad_lng[174]', 2, 0, 0, 0, 0, 30, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (109, '\$arr_ad_lng[175]', 2, 0, 0, 0, 0, 63, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (110, '\$arr_ad_lng[176]', 2, 0, 0, 0, 0, 31, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (111, '\$arr_ad_lng[177]', 2, 0, 0, 0, 0, 32, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (112, '\$arr_ad_lng[1049]', 2, 0, 0, 0, 0, 112, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (113, '\$arr_ad_lng[1208]', 2, 0, 0, 0, 0, 128, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (114, '\$arr_ad_lng[1209]', 2, 0, 0, 0, 0, 129, 0, 0)
INSERT INTO `{$database_up}ugoptlist` VALUES (115, '\$arr_ad_lng[1050]', 2, 0, 0, 0, 0, 113, 0, 0)";
    $arrayquery = explode("\n", $query);
    for($i = 0;$i < count($arrayquery);$i++) {
        $result = bmbdb_query($arrayquery[$i]);
    } 
    echo "<span class=\"i\">$li[59]</span><br />";
    }
    $result = bmbdb_query("INSERT INTO `{$database_up}emoticons` (`id`, `emotcode`, `emotpack`, `packname`, `emotname`, `thumb`) VALUES
(1, ':titter:', 'default', 'Default', '20.gif', 0),
(2, ':smoking:', 'default', 'Default', '29.gif', 0),
(3, ':blackman:', 'default', 'Default', '36.gif', 0),
(5, '[:o]', 'default', 'Default', 'icon1000.gif', 0),
(26, ':’( ', 'default', 'Default', 'icon1001.gif', 0),
(7, '[:S]', 'default', 'Default', 'icon1100.gif', 0),
(8, ':mad:', 'default', 'Default', 'icon1200.gif', 0),
(9, '[:P]', 'default', 'Default', 'icon1300.gif', 0),
(10, '[:D]', 'default', 'Default', 'icon1400.gif', 0),
(11, '[:)]', 'default', 'Default', 'icon1500.gif', 0),
(12, '[:(]', 'default', 'Default', 'icon1600.gif', 0),
(13, ':blush:', 'default', 'Default', 'icon1800.gif', 0),
(14, ':ninja:', 'default', 'Default', 'icon2000.gif', 0),
(15, ':excl:', 'default', 'Default', 'icon2001.gif', 0),
(16, ':glare:', 'default', 'Default', 'icon2100.gif', 0),
(17, ':lol:', 'default', 'Default', 'icon3000.gif', 0),
(18, ':wacko:', 'default', 'Default', 'icon4000.gif', 0),
(19, ':happy:', 'default', 'Default', 'icon4600.gif', 0),
(20, '[;)]', 'default', 'Default', 'icon5000.gif', 0),
(21, ':wub:', 'default', 'Default', 'icon7000.gif', 0),
(22, ':mellow:', 'default', 'Default', 'icon8000.gif', 0),
(23, ':sleeply:', 'default', 'Default', 'icon9000.gif', 0),
(24, ':disdainful:', 'default', 'Default', 'icon9100.gif', 0),
(25, ':knife:', 'default', 'Default', 'icon9200.gif', 0),
(27, ':dejecta:', 'default', 'Default', 'a.gif', 0)");

    refresh_forumcach();
    refresh_ugcache();

echo<<<EOT

	<span style='color:#206FCA;'><a style='font-size:12pt;color:#206FCA;' href='admin.php'>$li[60]</a></span>


EOT;

    @rename("install.php", "install.lock");
} 

?>
    </td>
</td></tr>
</table>
</td></tr></table>
</form>
<?php
if (!$step) {
echo<<<EOT
<script src="http://www.bmforum.com/down/vercheck.php?check_version_lang=$check_version_lang"></script>
<script>
document.getElementById("snews").innerHTML  = newver;
if($kernel_build>=kernel_build){ 
document.getElementById("vnews").innerHTML  = "<br/>$li[65]";
}else{ 
document.getElementById("vnews").innerHTML  = "<br/><a href='http://www.bmforum.com'>$li[64]</a><br/>"+notice;
if(enablealert!="") {
	alert(enablealert);
}
}
</script>
EOT;

}
?>
<br />
    </body></html>
