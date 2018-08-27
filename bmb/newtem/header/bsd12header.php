<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $read_alignment;?>" lang="<?php echo $html_lang;?>">
<head>
<?php
global $log_hash, $userid, $database_up, $bmfopt, $usertype, $userpoint, $language, $short_title;
include("lang/$language/hefo.php");
if ($login_status == 1) {
	global $gotNewMessage, $userid; 
	$new_tips = "";
	$totalNewMessage = $gotNewMessage + $userddata['unreadnote'];
}
?>
<link rel="shortcut icon" href="<?php echo $otherimages;?>/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo $otherimages;?>/lightbox/jquery.lightbox-0.5.css" media="screen" />
<link href="<?php echo $otherimages;?>/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo $otherimages;?>/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $otherimages;?>/neweditor/editor.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $otherimages;?>/styles.css" />
<link title="<?php echo $oldbbs_title ? $oldbbs_title : $bbs_title; ?>" rel="search" type="application/opensearchdescription+xml" href="datafile/cache/search.xml" />
<link rel="alternate" type="application/rss+xml" href="rss.php?forumid=<?php echo $forumid;?>&amp;tagname=<?php echo $urltagname;?>" title="<?php echo $bbs_title; if ($add_title) echo $add_title;?>" />
<link rel="alternate" type="application/baidu+xml" href="baidu.php?forumid=<?php echo $forumid;?>&amp;tagname=<?php echo $urltagname;?>" title="<?php echo $bbs_title; if ($add_title) echo $add_title;?>" />
<title><?php echo $bbs_title;if ($add_title) echo $add_title; ?> - powered by bmforum.com</title>
<?php 	if ($jumppagetrue=="yes")  { ?>
<meta http-equiv="refresh" content="4; url=<?php echo $url;?>" />
<?php }elseif ($reauto){ ?>
<meta http-equiv="refresh" content="<?php echo $reauto;?>; url=<?php echo $_SERVER['REQUEST_URI'];?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="generator" content="<?php echo $verandproname;?>" />
<meta name="keywords" content="<?php global $keyword_site; echo $keyword_site; ?>" />
<meta name="robots" content="index, follow" />
<meta name="googlebot" content="index, follow" />
<script type="text/javascript">
//<![CDATA[ 
function openScript(url, width, height){
	var win = window.open(url,"openscript",'width=' + width + ',height=' + height + ',resizable=1,scrollbars=yes,menubar=no,status=yes' );
}
function bbimg(o){
<?php if($openbbimg==1){ ?>
if (event.ctrlkey == true){
var zoom=parseint(o.style.zoom,10)||100;zoom+=event.wheeldelta/12;if (zoom>0) o.style.zoom=zoom+'%';return false;
}
<?php } ?>
}
//]]>>
</script>
<script src="images/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="images/jquery.lightbox-0.5.min.js"></script>
<script src="<?php echo $otherimages;?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="images/bmb_ajax.min.js" type="text/javascript"></script>
<script type="text/javascript" src="images/bsd12/sms.js"></script>
</head>

<body>
<script type="text/javascript">
if(isIe6) {
	document.write('<div class="alert"><strong>Please upgrade your browser! 本站已停止支持 IE6, 请升级你的浏览器! 若您正在使用双核浏览器, 请切换到"高速"模式!</strong></div>');
}
var login_status = '<?php echo $login_status;?>';
$(document).attr("fulltitle", $(document).attr("title"));
init_checkMessage('<?php echo $totalNewMessage;?>', -1, -1);
</script>
<?php

$dropdown_menu['message'] = '<ul class="dropdown-menu">';
$dropdown_menu['message'] .= '<li><a href="messenger.php?job=receivebox">'.$hefo[15].'<span id="head_newpm">'.($gotNewMessage ? '('.$gotNewMessage.')' : '').'</span></a></li>';
$dropdown_menu['message'] .= '<li><a href="misc.php?p=notification">'.$hefo[60].'<span id="head_newnotify">'.($userddata['unreadnote'] ? '('.$userddata['unreadnote'].')' : '').'</span></a></li>';
$dropdown_menu['message'] .= '</ul>';

$dropdown_menu['usercp'] = '<ul class="dropdown-menu">';
$dropdown_menu['usercp'] .= '<li class="nav-header">'.$username.'</li>';
$dropdown_menu['usercp'] .= '<li><a href="usercp.php">'.$hefo[14].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="profile.php">'.$hefo[39].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="viewfav.php">'.$hefo[40].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="friendlist.php">'.$hefo[41].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="usercp.php?act=active">'.$hefo[45].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="misc.php?p=accounts">'.$hefo[47].'</a></li>';
$dropdown_menu['usercp'] .= '<li class="divider"></li>';
$dropdown_menu['usercp'] .= "<li><a href='login.php?job=switch'".(($_COOKIE['privacybym']) ? '' : ' style="font-weight:bold;"').">$hefo[56]</a></li>";
$dropdown_menu['usercp'] .= "<li><a href='login.php?job=switch'".(($_COOKIE['privacybym']) ? ' style="font-weight:bold;"' : '').">$hefo[57]</a></li>";
$dropdown_menu['usercp'] .= '<li class="divider"></li>';
$dropdown_menu['usercp'] .= "<li><a href='login.php'>$hefo[7]</a></li>";
$dropdown_menu['usercp'] .= "<li><a href='login.php?job=quit&amp;verify=$log_hash'>$hefo[8]</a></li>";
$dropdown_menu['usercp'] .= '</ul>';

$dropdown_menu['skin'] = '<ul class="dropdown-menu">';
$dropdown_menu['skin'] .= '<li><a href="chskin.php?skinname=">'.$hefo[50].'(Default)</a></li>';
if ($fnew_skin == 1) {
	for($styi = 0;$styi < $stylecount;$styi++) {
		$cdhtail = explode("|", $styleopen[$styi]);
		$dropdown_menu['skin'] .= '<li><a href="chskin.php?skinname='.$cdhtail[1].'">'.$cdhtail[2].'</a></li>';
    } 
} 
$dropdown_menu['skin'] .= '</ul>';


$dropdown_menu['info'] = '<ul class="dropdown-menu">';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=viewnews">'.$hefo[9].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=viewtop">'.$hefo[10].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="userlist.php">'.$hefo[11].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="plugins.php?p=tags">'.$hefo[44].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=digg">'.$hefo[55].'</a></li>';
if ($login_status == 1){ 
	$dropdown_menu['info'] .= '<li><a href="search.php?keyword='.$o_username.'&amp;method=or&amp;method1=2&amp;method2=7">'.$hefo[48].'</a></li>';
	$dropdown_menu['info'] .= '<li><a href="search.php?keyword='.$o_username.'&amp;method=or&amp;method1=3&amp;method2=7">'.$hefo[49].'</a></li>';
}
$dropdown_menu['info'] .= '</ul>';
	
if ($count_language > 1) {
	$dropdown_menu['lang'] = '<ul class="dropdown-menu">';
	$langlist = @file("datafile/langlist.php");
	for($i = 0;$i < $count_language;$i++) {
		$cdhtail = explode("|", $langlist[$i]);
		if($cdhtail[1]) {
			$dropdown_menu['lang'] .= "<li><a href='chskin.php?langname=$cdhtail[1]'>$cdhtail[3]</a></li>";
		}
	} 
	 
	$dropdown_menu['lang'] .= '</ul>';
}

?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="nav-collapse">
        <ul class="nav">
    			<li class='active'><a href="./"><?php echo $short_title;?></a></li>
				<?php
        		if ($myplugin) {
        			echo '<li class="dropdown"><a href="javascript:;" class="dropdown-toggle">'.$hefo[58].' <b class="caret"></b></a>'.$myplugin.'</li>';
        		}
		?>
		  <li><li class="dropdown"><a href="javascript:;" class="dropdown-toggle"><?php echo $hefo[37];?> <b class="caret"></b></a><?php echo $dropdown_menu['info'];?></li>
          <li><a href="search.php?searchfid=<?php echo $forumid;?>"><?php echo $hefo[12];?></a></li>
		  <li><a href="faq.php"><?php echo $hefo[13];?></a></li>
		<?php
				if ($usertype[22]=="1" || $usertype[21]=="1") { 
					echo "<li><a href='admin.php'>$hefo[2]</a></li>";
				}
		?>
		  
        </ul>
        <ul class="nav pull-right">
        	<?php
        		if ($count_language > 1) {
        			echo '<li class="dropdown"><a href="javascript:;" class="dropdown-toggle">Languages <b class="caret"></b></a>'.$dropdown_menu['lang'].'</li>';
        		}
        		if ($fnew_skin==1 && $stylecount > 1) {
        			echo '<li class="dropdown"><a href="javascript:;" class="dropdown-toggle">'.$hefo[38].' <b class="caret"></b></a>'.$dropdown_menu['skin'].'</li>';
        		}
				if ($login_status == 1) {
					if ($totalNewMessage>0) $new_tips = "($totalNewMessage)";
					echo "<li class='dropdown'><a href='javascript:;' class='dropdown-toggle'>$hefo[59]<strong id='head_totalnewmess' style='color:orange;font-weight:bold;'>$new_tips</strong>".' <b class="caret"></b></a>'.$dropdown_menu['message'].'</li>';
					echo "<li class='dropdown'><a href='usercp.php' class='dropdown-toggle'><img src='misc.php?p=getavatar&uid=$userid' width='20' height='20' class='headeravatar'/></a>{$dropdown_menu['usercp']}</li>";
				}
				if ($login_status == 0) {
					echo "<li><a href='login.php'>$hefo[4]</a></li><li><a href='register.php'>$hefo[5]</a></li>"; 
				}

        	?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div align="center">
<div id="totallayer" style="text-align:left;width:970px; height:auto; z-index:1; border: none; " class="bmforum_background">
<table border="0" align="center" cellpadding="0" cellspacing="0" style="width:100%;">
<tr><td>		
	<table width="100%" class="background_color" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="169" height="70" align="left"><a href='./'><img alt="Create The World With Creativity" title="Create The World With Creativity" border="0" src="<?php echo $otherimages;?>/logo.png" /></a></td>
			<?php if ($login_status==0) { ?>
				<td width="*" height="70" align="right">
					<?php
					if($oauthLang['provider']) {
						foreach($oauthLang['provider'] as $providerId => $providerName) {
							if(!$bmfopt['oauth'][$providerId]['appKey']) {
								continue;
							}
					?>
						<a href='oauth.php?act=login&type=<?php echo $providerId;?>'><img src='images/oauth/<?php echo $oauthLang['providerImg'][$providerId];?>' border='0' style='padding:0px;margin:0px;' title='用<?php echo $providerName;?>登录' /></a>
						&nbsp;&nbsp;
					<?php
						}
					}
					?>
				</td>
			<?php
				 }
			 ?>
			<td width="*" align="right"><?php
if ($ads_select==1){//广告开关
$topfile="datafile/topads.php";
	if (file_exists($topfile)) {
		include($topfile);		
		}
}
?>
			<div align="right">
&nbsp; <?php echo $topads;?> </div>
			</td>
		</tr>
	</table>
</td></tr></table>
<div id="popmenu" class="menuskin" onmouseover="clearhidemenu();highlightmenu(event,'on')" onmouseout="highlightmenu(event,'off');dynamichide(event)" style="z-index:100;">
</div>
<div class="alert alert-block alert-error fade in" id="ajax_information" style="display:none;">
    <a class="close" href="javascript:;" onclick="javascript:$('#ajax_information').fadeOut('slow');">×</a>
    <h4 class="alert-heading"><?php echo $hefo[53];?></h4>
    <p><div id="ajax_error_detail"></div></p>
    <p>
      <a class="btn btn-danger" href="javascript:;" onclick="javascript:$('#ajax_information').fadeOut('slow');"><?php echo $gl[82];?></a>
    </p>
</div>
<?php 

//if ($login_status==0 && $page_regtips=="1" && $all_regtips!="1" && $cancel_guestfile!="reglog") echo "<div id='bmbsms' style=\"visibility: hidden;  position: absolute; top: 0px; width: 180px; height: 116px;\"  class='forumcoloronecolor'><table cellspacing='0' cellpadding='5' width='100%' class='forumcoloronecolor' border='0' onclick=\"	document.getelementbyid('bmbsms').style.visibility='hidden';\"><tr><td> {$hefo[29]}</td></tr><tr><td style='height:90px;' align='center'>{$hefo[30]}<br /><br /><strong><a href='register.php'>{$hefo[31]}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='login.php'>{$hefo[32]}</a></strong><br /><br />{$hefo[33]}<br /></td></tr></table></div>"; 

if (($usertype[118] == 1 || $userpoint < $usertype[119]) && $cancel_guestfile!="reglog") {
	navi_bar($hefo[17],$hefo[16]);
	msg_box($hefo[18],$hefo[19]); 
	require("footer.php");
	exit;
} 

if ($gotNewMessage > 0 && $_COOKIE['TNM'] != 1) {
?>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" id="smssound" width="1" height="1">
        <param name="movie" value="<?php echo $otherimages;?>/sms.swf">
        <param name="bgcolor" value="#FFFFFF">
        <param name="quality" value="high">
        <param name="seamlesstabbing" value="false">
        <param name="allowscriptaccess" value="samedomain">
        <embed type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" name="sms" width="1" height="1" src="<?php echo $otherimages;?>/sms.swf" bgcolor="#FFFFFF" quality="high" seamlesstabbing="false" allowscriptaccess="samedomain">
        </embed>
      </object>
<?php
	if ($bmfopt['newpms'] > 0) {
?>
<table cellpadding="5" cellspacing="1" border="0" class="tableborder" align="center">
	<tr>
		<td class="announcement" colspan="5">
		<span class="categoryfontcolor_normal" style="font-weight:bold;"><a href="messenger.php?job=receivebox"><?php echo $hefo[51];?>(<?php echo $gotNewMessage;?>)</a></span></td>
	</tr>
<?php
		$display_NewMessage = min($gotNewMessage, $bmfopt['newpms']);
		$display_NM_result = bmbdb_query("SELECT * FROM {$database_up}primsg WHERE stid='$userid' and `prread`=0 ORDER BY `prtime` DESC LIMIT 0,".$display_NewMessage, 1);
		while (false !== ($display_NM_row = bmbdb_fetch_array($display_NM_result))) {
			$display_NM_title = $display_NM_row['prtitle'];
			$display_NM_urlbelong = urlencode($display_NM_row['belong']);
			if (empty($display_NM_title)) $display_NM_title = "($hefo[52])";
			$display_NM_prcontent = substrfor(stripslashes(strip_tags($display_NM_row['prcontent'])), 0, 30)."...";
?>
<tr class='forum_border_one_1' onmouseover="javascript:this.className='forum_border_one_2';" onmouseout="javascript:this.className='forum_border_one_1';">
<td width="*" class='forum_border_one_3'><a href="javascript:show_msg_content('<?php echo $display_NM_row["id"];?>');" ><?php echo $display_NM_title;?>: <?php echo $display_NM_prcontent;?></a>
<div class="tableborder" style="padding:10px 0px 10px 20px;margin:5px 0px 0px 0px;visibility:hidden;display:none;width:90%;float:left;" id='BMF_message_content_<?php echo $display_NM_row["id"];?>'></div>
<div style="padding:5px 0px 5px 10px;visibility:hidden;display:none;width:90%;float:left;" id='BMF_message_toolbar_<?php echo $display_NM_row["id"];?>'>
[<a href='messenger.php?job=write&amp;target=<?php echo urlencode($display_NM_row[belong]) ?>&amp;timu=RE:<?php echo urlencode($display_NM_row[prtitle]) ?>'><?php echo $gl[79] ?></a>]  [<a href='messenger.php?job=write&amp;fwid=<?php echo urlencode($display_NM_row[id]) ?>&amp;timu=Fw:<?php echo urlencode($display_NM_row[prtitle]) ?>'><?php echo $gl[80] ?></a>]  [<a href='messenger.php?job=delone&amp;verify=<?php echo $log_hash ?>&amp;msg=<?php echo $display_NM_row[id] ?>'><?php echo $gl[81] ?></a>] [<a href='#' onclick="javascript:close_show_msg_content(<?php echo $display_NM_row[id] ?>)"><?php echo $gl[82] ?></a>]
</div>
</td>
<td width='15%' class='forum_border_one_3'><a href='messenger.php?job=write&memberid=<?php echo $display_NM_urlbelong;?>'><?php echo $display_NM_row['belong'];?></a></td>
<td width='15%' class='forum_border_one_3'><?php echo getfulldate($display_NM_row['prtime']);?></td>
</tr>
<?php
		}

?>
</table>
<br />
<?php
	}
}

?>