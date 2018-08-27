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

require("lang/$language/global.php");

list($admin_permission['enter_a'], $admin_permission['enter_b'], $admin_permission['enter_c'], $admin_permission['enter_d'], $admin_permission['enter_e'], $admin_permission['enter_f'], $admin_permission['enter_g'], $admin_permission['enter_h'], $admin_permission['enter_i'], $admin_permission['enter_j'], $admin_permission['enter_k'], $admin_permission['enter_l'], $admin_permission['enter_m'], $admin_permission['enter_n'], $admin_permission['enter_o'], $admin_permission['enter_p'], $admin_permission['enter_q'], $admin_permission['enter_r'], $admin_permission['enter_s'], $admin_permission['enter_t'], $admin_permission['enter_u'], $admin_permission['enter_v'], $admin_permission['enter_w'], $admin_permission['enter_x'], $admin_permission['enter_y'], $admin_permission['enter_z'], $admin_permission['enter_1a'], $admin_permission['enter_2a'], $admin_permission['enter_3a'], $admin_permission['enter_4a'], $admin_permission['enter_5a'], $admin_permission['enter_6a'], $admin_permission['enter_7a'], $admin_permission['enter_8a'], $admin_permission['enter_9a'], $admin_permission['enter_1b'], $admin_permission['enter_2b'], $admin_permission['enter_3b'], $admin_permission['enter_4b'], $admin_permission['enter_5b'], $admin_permission['enter_bc'], $admin_permission['enter_task'], $admin_permission['enter_api']) = $admgroupdata;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $read_alignment;?>" lang="<?php echo $html_lang;?>">

<script type="text/javascript" src="images/adminmenu.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="images/admin.css" />

</head>
<body>

<div id="adminheader">
<div id="adminheaderbar">
<ul>
<span class="ahb_active"><li class="firstitem" onmouseover="adminitemhover('main',this)"><a href="admin.php"><?php echo $arr_ad_lng[964];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('entry',this)"><a href="#"><?php echo $arr_ad_lng[182];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('category',this)"><a href="#"><?php echo $arr_ad_lng[188];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('link',this)"><a href="#"><?php echo $arr_ad_lng[1184];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('reply',this)"><a href="#"><?php echo $arr_ad_lng[1186];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('message',this)"><a href="#"><?php echo $arr_ad_lng[1189];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('user',this)"><a href="#"><?php echo $arr_ad_lng[196];?></a></li></span>
</ul>
<script type="text/javascript">
if (is_moz) {
	document.write('<p style="padding-top:12px;"/>');
} else {
	document.write('<p style="padding-top:18px;"/>');
}
</script>
<ul>
<span class="ahb"><li class="firstitem" onmouseover="adminitemhover('addon',this)"><a href="#"><?php echo $arr_ad_lng[1190];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('misc',this)"><a href="#"><?php echo $arr_ad_lng[203];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('carecenter',this)"><a href="#"><?php echo $arr_ad_lng[210];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('forbid',this)"><a href="#"><?php echo $arr_ad_lng[215];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('topics',this)"><a href="#"><?php echo $arr_ad_lng[222];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('emoticons',this)"><a href="#"><?php echo $arr_ad_lng[1185];?></a></li></span>
<span class="ahb"><li onmouseover="adminitemhover('system',this)"><a href="#"><?php echo $arr_ad_lng[226];?></a></li></span>
</ul>
</div>
</div>
<br />
<div id="dropmenudiv" class="dropmenudiv" style="position:absolute;top:-15px;visibility:hidden;" onmouseout="delayhidemenu(event)" onmouseover="delayhidemenulong(event)"></div>
<div id="hoveritem_main" style="display: none;"><ul>
<?php if ($admin_permission['enter_a']) {
    ?><li class="normal"><a href=admin.php?bmod=setoptions.php><?php echo $arr_ad_lng[965];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_d']) {
    ?><li class="normal"><a href=admin.php?bmod=addforum.php><?php echo $arr_ad_lng[969];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_d']) {
    ?><li class="normal"><a href=admin.php?bmod=setforum.php><?php echo $arr_ad_lng[970];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_n']) {
    ?><li class="normal"><a href=admin.php?bmod=usergroup.php><?php echo $arr_ad_lng[966];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_j']) {
    ?><li class="normal"><a href=admin.php?bmod=setuser.php><?php echo $arr_ad_lng[968];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_t']) {
    ?><li class="normal"><a href=admin.php?bmod=batbackup.php><?php echo $arr_ad_lng[971];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_l']) {
    ?><li class="normal"><a href=admin.php?bmod=delpostbat.php><?php echo $arr_ad_lng[980];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_c']) {
    ?><li class="normal"><a href=admin.php?bmod=foruminit.php><?php echo $arr_ad_lng[967];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_1b']) {
    ?><li class="normal"><a href=admin.php?bmod=messageuser.php><?php echo $arr_ad_lng[982];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_5b']) {
    ?><li class="normal"><a href=admin.php?bmod=plugincenter.php><?php echo $arr_ad_lng[973];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_u']) {
    ?><li class="normal"><a href=admin.php?bmod=setstyles.php><?php echo $arr_ad_lng[972];?></a></li><?php } 
?>
<li class="normal"><a href="admin.php?action=exit"><?php echo $arr_ad_lng[186];?></a></li>

<li class="normal"><a href="index.php" target="top"><?php echo $arr_ad_lng[981];?></a></li>
</ul></div>
<div id="hoveritem_category" style="display: none;"><ul><?php if ($admin_permission['enter_d']) {
    ?>
<li class="normal"><a href=admin.php?bmod=setforum.php><strong><?php echo $arr_ad_lng[189];?></strong></a></li>
<li class="normal"><a href="admin.php?bmod=setforum.php#section1"><?php echo $arr_ad_lng[550];?></a></li>
<li class="normal"><a href="admin.php?bmod=setforum.php#section2"><?php echo $arr_ad_lng[561];?></a></li>
<li class="normal"><a href="admin.php?bmod=setforum.php#section3"><?php echo $arr_ad_lng[563];?></a></li>
<li class="normal"><a href="admin.php?bmod=setforum.php#section4"><?php echo $arr_ad_lng[564];?></a></li>
<li class="normal"><a href="admin.php?bmod=setforum.php#section5"><?php echo $arr_ad_lng[958];?></a></li>
<?php } 
?>
<?php if ($admin_permission['enter_e']) {
    ?>
<li class="normal"><a href=admin.php?bmod=forumfix.php><strong><?php echo $arr_ad_lng[190];?></strong></a></li>
<li class="normal"><a href="admin.php?bmod=forumfix.php"><?php echo $arr_ad_lng[362];?></a></li>
<li class="normal"><a href="admin.php?bmod=forumfix.php"><?php echo $arr_ad_lng[365];?></a></li>
<li class="normal"><a href="admin.php?bmod=forumfix.php"><?php echo $arr_ad_lng[958];?></a></li>
<?php } 
?>
<?php if ($admin_permission['enter_f']) {
    ?><li class="normal"><a href=admin.php?bmod=luntanhb.php><strong><?php echo $arr_ad_lng[191];?></strong></a></li><?php } 
?></ul></div>
<div id="hoveritem_link" style="display: none;"><ul><?php if ($admin_permission['enter_b']) {
    ?><li class="normal"><a href=admin.php?bmod=ads.php><?php echo $arr_ad_lng[184];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_8a']) {
    ?><li class="normal"><a href=admin.php?bmod=setpads.php><?php echo $arr_ad_lng[225];?></a></li><?php } 
?></ul></div>
<div id="hoveritem_entry" style="display: none;"><ul><?php if ($admin_permission['enter_a']) {
    ?>
<li class="normal"><a href=admin.php?bmod=setoptions.php><strong><?php echo $arr_ad_lng[183];?></strong></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#openclose"><?php echo $arr_ad_lng[974];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#basicinfo"><?php echo $arr_ad_lng[976];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#habit"><?php echo $arr_ad_lng[977];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#register"><?php echo $arr_ad_lng[978];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#userper"><?php echo $arr_ad_lng[979];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#bbsper"><?php echo $arr_ad_lng[983];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#bbsreason"><?php echo $arr_ad_lng[984];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#tags"><?php echo $arr_ad_lng[1064];?></a></li>
<li class="normal"><a href="admin.php?bmod=setoptions.php#watermark"><?php echo $arr_ad_lng[1074];?></a></li>
<?php } 
?>
<?php if ($admin_permission['enter_w']) {
    ?><li class="normal"><a href=admin.php?bmod=settime.php><strong><?php echo $arr_ad_lng[213];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_v']) {
    ?><li class="normal"><a href=admin.php?bmod=setlang.php><strong><?php echo $arr_ad_lng[212];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_4b']) {
    ?><li class="normal"><a href="admin.php?bmod=reg_custom.php"><strong><?php echo $arr_ad_lng[1107];?></strong></a></li><?php } 
?></ul></div>
<div id="hoveritem_reply" style="display: none;"><ul><?php if ($admin_permission['enter_i']) {
    ?>
 <li class="normal"><a href="announcesys.php"><strong><?php echo $arr_ad_lng[195];?></strong></a></li>
 <li class="normal"><a href="announcesys.php"><?php echo $arr_ad_lng[1187];?></a></li>
 <li class="normal"><a href="announcesys.php?forumid=0&job=write"><?php echo $arr_ad_lng[1188];?></a></li>
 <li class="normal"><a href="admin.php?bmod=announcement.php"><strong><?php echo $arr_ad_lng[194];?></strong></a></li>
   
 <?php } 
?>
<?php if ($admin_permission['enter_k']) {
    ?><li class="normal"><a href=admin.php?bmod=regtips.php><strong><?php echo $arr_ad_lng[198];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_x']) {
    ?><li class="normal"><a href=admin.php?bmod=welcomemess.php><strong><?php echo $arr_ad_lng[214];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_9a']) {
    ?><li class="normal"><a href=admin.php?bmod=mailuser.php><strong><?php echo $arr_ad_lng[227];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_1b']) {
    ?><li class="normal"><a href=admin.php?bmod=messageuser.php><strong><?php echo $arr_ad_lng[228];?></strong></a></li><?php } 
?></ul></div>
<div id="hoveritem_message" style="display: none;"><ul><?php if ($admin_permission['enter_h']) {
    ?><li class="normal"><a href=admin.php?bmod=shareforum.php><?php echo $arr_ad_lng[193];?></a></li><?php } 
?>
</ul></div>
<div id="hoveritem_addon" style="display: none;"><ul>
<?php if ($admin_permission['enter_n']) {
?>
<li class="normal"><a href=admin.php?bmod=usergroup.php><strong><?php echo $arr_ad_lng[201];?></strong></a></li>
<li class="normal"><a href="admin.php?bmod=usergroup.php#section1"><?php echo $arr_ad_lng[838];?></a></li>
<li class="normal"><a href="admin.php?bmod=usergroup.php#section3"><?php echo $arr_ad_lng[1191];?></a></li>
<li class="normal"><a href="admin.php?bmod=usergroup.php#section4"><?php echo $arr_ad_lng[837];?></a></li>
<li class="normal"><a href="admin.php?bmod=usergroup.php#section5"><?php echo $arr_ad_lng[1174];?></a></li>
<li class="normal"><a href="admin.php?bmod=usergroup.php#section2"><?php echo $arr_ad_lng[828];?></a></li>
<?php } ?>
<?php if ($admin_permission['enter_m']) {?><li class="normal"><a href=admin.php?bmod=addusergroup.php><?php echo $arr_ad_lng[200];?></a></li><?php } ?>

<?php if ($admin_permission['enter_o']) {
    ?><li class="normal"><a href=admin.php?bmod=usertitle.php><strong><?php echo $arr_ad_lng[202];?></strong></a></li><?php } 
?></ul></div>
<div id="hoveritem_misc" style="display: none;"><ul><?php if ($admin_permission['enter_q']) {
    ?><li class="normal"><a href=admin.php?bmod=userbackup.php><?php echo $arr_ad_lng[205];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_r']) {
    ?><li class="normal"><a href=admin.php?bmod=forumbackup.php><?php echo $arr_ad_lng[206];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_t']) {
    ?><li class="normal"><a href=admin.php?bmod=batbackup.php><strong><?php echo $arr_ad_lng[208];?></strong></a></li>
   <li class="normal"><a href=admin.php?bmod=batresume.php><?php echo $arr_ad_lng[209];?></a></li><?php } 
?>
</ul></div>
<div id="hoveritem_user" style="display: none;"><ul><?php if ($admin_permission['enter_j']) {
    ?>
<li class="normal"><a href=admin.php?bmod=setuser.php><strong><?php echo $arr_ad_lng[197];?></strong></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#section1"><?php echo $arr_ad_lng[758];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#section2"><?php echo $arr_ad_lng[760];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#section6"><?php echo $arr_ad_lng[1012];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#section3"><?php echo $arr_ad_lng[767];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#section4"><?php echo $arr_ad_lng[1179];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#combine"><?php echo $arr_ad_lng[1181];?></a></li>
<li class="normal"><a href="admin.php?bmod=setuser.php#ssection5"><?php echo $arr_ad_lng[762];?></a></li>

<?php } 
?>


<?php if ($admin_permission['enter_p']) {
    ?><li class="normal"><a href=admin.php?bmod=userdel.php><strong><?php echo $arr_ad_lng[204];?></strong></a></li><?php } 
?>
</ul></div>
<div id="hoveritem_carecenter" style="display: none;"><ul><?php if ($admin_permission['enter_u']) {
    ?><li class="normal"><a href=admin.php?bmod=setstyles.php><strong><?php echo $arr_ad_lng[211];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_g']) {
    ?><li class="normal"><a href=admin.php?bmod=setcss.php><?php echo $arr_ad_lng[192];?></a></li><?php } 
?></ul></div>

<div id="hoveritem_forbid" style="display: none;"><ul><?php if ($admin_permission['enter_y']) {
    ?><li class="normal"><a href=admin.php?bmod=setbadwords.php><?php echo $arr_ad_lng[216];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_1a']) {
    ?><li class="normal"><a href=admin.php?bmod=banname.php><?php echo $arr_ad_lng[217];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_2a']) {
    ?><li class="normal"><a href=admin.php?bmod=banuserpost.php><strong><?php echo $arr_ad_lng[218];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_3a']) {
    ?><li class="normal"><a href=admin.php?bmod=setipbans.php><?php echo $arr_ad_lng[219];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_4a']) {
    ?><li class="normal"><a href=admin.php?bmod=regipbans.php><?php echo $arr_ad_lng[220];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_5a']) {
    ?><li class="normal"><a href=admin.php?bmod=setidbans.php><?php echo $arr_ad_lng[221];?></a></li><?php } 
?></ul></div>
<div id="hoveritem_topics" style="display: none;"><ul><?php if ($admin_permission['enter_l']) {
    ?><li class="normal"><a href=admin.php?bmod=delpostbat.php><?php echo $arr_ad_lng[199];?></a></li><?php } 
?>
<?php if ($can_rec) {
    ?><li class="normal"><a href="admin.php?bmod=recoverpost.php&type=t"><?php echo $arr_ad_lng[1214];?></a></li><li class="normal"><a href="admin.php?bmod=recoverpost.php&type=p"><?php echo $arr_ad_lng[1215];?></a></li><?php } 
?>
<li class="normal"><a href=forums.php?trash=trash><?php echo $arr_ad_lng[1041];?></a></li>
</ul></div>
<div id="hoveritem_emoticons" style="display: none;"><ul><?php if ($admin_permission['enter_bc']) {
    ?><li class="normal"><a href=admin.php?bmod=customcode.php><?php echo $arr_ad_lng[1157];?></a></li><?php } 
?>	
<?php if ($admin_permission['enter_6a']) {
    ?><li class="normal"><a href=admin.php?bmod=setact.php><?php echo $arr_ad_lng[223];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_7a']) {
    ?><li class="normal"><a href=admin.php?bmod=setemoticon.php><?php echo $arr_ad_lng[224];?></a></li><?php } 
?>

</ul></div>
<div id="hoveritem_system" style="display: none;"><ul><?php if ($admin_permission['enter_2b']) {
    ?><li class="normal"><a href=admin.php?bmod=loginfo.php><?php echo $arr_ad_lng[229];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_4b']) {
    ?><li class="normal"><a href=admin.php?bmod=potloginfo.php><?php echo $arr_ad_lng[230];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_task']) {
    ?><li class="normal"><a href=admin.php?bmod=schedule.php><?php echo $arr_ad_lng[1130];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_5b']) {
    ?><li class="normal"><a href=admin.php?bmod=plugincenter.php><strong><?php echo $arr_ad_lng[231];?></strong></a></li><?php } 
?>
<?php if (!$admin_permission['enter_api']) {
    ?><li class="normal"><a href='admin.php?bmod=setapi.php'><strong><?php echo $arr_ad_lng[1225];?></strong></a></li><?php } 
?>
<?php if ($admin_permission['enter_s']) {
    ?><li class="normal"><a href=admin.php?bmod=attachment.php><?php echo $arr_ad_lng[207];?></a></li><?php } 
?>
<?php if ($admin_permission['enter_c']) {
    ?><li class="normal"><a href=admin.php?bmod=foruminit.php><?php echo $arr_ad_lng[185];?></a></li><?php } 
?>

</ul></div>
<div id="hoverempty" style="display: none;"><ul>
<li class="normal"><a href="#"><?php echo $arr_ad_lng[1224];?></a></li>
</ul></div>

<script type="text/javascript">
function adminitemhover(hovername, obj) {
	if (document.getElementById('dropmenudiv') && document.getElementById('hoveritem_'+hovername)) {
		if(is_moz){
		    if (document.getElementById('hoveritem_'+hovername).innerHTML.indexOf("li") == -1) check_empty = ""; else check_empty = "321";
		} else{
			check_empty = document.getElementById('hoveritem_'+hovername).innerText.replace(" ","");
		}
		if (check_empty == "") { 
			document.getElementById('dropmenudiv').innerHTML=document.getElementById('hoverempty').innerHTML;
		} else {
			document.getElementById('dropmenudiv').innerHTML=document.getElementById('hoveritem_'+hovername).innerHTML;
		}
	}
	dropdownmenu(obj);
}
</script>
<script type="text/javascript">
if (is_ie7) {
	document.write('<div id="adminmain_before"></div>');
}
</script>
<div id="adminmain" onmouseover="hidemenu();">
