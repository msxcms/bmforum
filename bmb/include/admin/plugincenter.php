<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
/*
==================================
Plugin Administration Center for Blue Magic Board
Version：2.0
Bob Shen All Rights Reserved.
Last Modified: YutingPC
==================================
*/
if (!defined('INBMFORUM')) die("Access Denied");

$thisprog = "plugincenter.php";

if ($useraccess != "1" || $admgroupdata[39] != "1") {
	adminlogin();
} 

$pf = "datafile/pluginlist.php";
$pdf = "datafile/pluginheader.php";
$pcf = "datafile/clubitem.php";

if (file_exists("plugins/club.php") || file_exists("club.php")) {
	$addopt = "<OPTION value=\"yes\">$arr_ad_lng[928]</OPTION><OPTION value=\"club\">$arr_ad_lng[929]</OPTION><OPTION value=\"both\">$arr_ad_lng[930]</OPTION>";
} else {
	$addopt = "<OPTION value=\"yes\">$arr_ad_lng[481]</OPTION>";
} 

$id	= $_REQUEST['id'];
$sendto	= $_POST['sendto'];
$step	= $_REQUEST['step'];
$action	= $_REQUEST['action'];
$plugname	= $_POST['plugname'];
$plugadmin	= $_POST['plugadmin'];
$plugurl	= $_POST['plugurl'];
$codename	= $_POST['codename'];
$ifmprogram	= $_POST['ifmprogram'];
$ifmtemplate	= $_POST['ifmtemplate'];
$plugdisplay	= $_POST['plugdisplay'];

if (empty($action)) $action = "center";

$groupselect = "";

print <<<eot
  <tr><td align=left bgcolor=#14568A valign=middle align=center colspan=6><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[231]</strong>
    </td></tr>

eot;
			

if ($action == 'center') {
	print <<<eot
 <tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[231]</strong> 
 </td></tr> 
eot;

	if (!file_exists($pf)) {
		echo "<tr><td bgcolor=#F9FAFE colspan=2><strong>$arr_ad_lng[461]</strong></td></tr>";
	} else {
		$plist = @file($pf);
		$m = count($plist);
		if ($m != 0) {
			for ($i = 0; $i < $m; $i++) {
				
				if ($i % 2 == 0) $thiscolor = "#F9FAFE";
					else $thiscolor = "#F9FAFE";
				$de = explode("|", $plist[$i]);
				
				$groupselect .= "<option value='$i'>$de[1]</option>\n";
				
				echo " <tr><td bgcolor='$thiscolor'>
				<a href='$de[2]'><strong>$de[1]</strong></a>
		       </td>
				<td bgcolor='$thiscolor' align=center>
				<a href='$de[2]'>$arr_ad_lng[462]</a>  
				<a href=\"javascript:cprocess('admin.php?bmod=$thisprog&action=delplug&id=$de[3]');\">$arr_ad_lng[463]</a> 
				<a href='admin.php?bmod=$thisprog&action=modplug&id=$de[3]'>$arr_ad_lng[464]</a> 
				</td></tr>";
			} 
		} else {
			echo "<tr><td bgcolor=#F9FAFE colspan=2><strong>$arr_ad_lng[461]</strong></td></tr>";
		} 
	} 

	if ($thiscolor == "#F9FAFE") $thiscolor = "#F9FAFE";
		else $thiscolor = "#F9FAFE";

echo<<<EOT
				<script src="images/bmb_ajax.js"></script>
					<tr>
					<td bgcolor="$thiscolor" valign="middle" colspan="2"><strong>$arr_ad_lng[568]</strong><br />
				    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
			    $tab_top
		<select multiple size="4" style="width: 50%;" name="list2" style="width: 120px">
		$groupselect
		</select>
		<br />
		<input type="button" value="$arr_ad_lng[1032]" onclick="Moveup(this.form.list2)" name="B3">
		<input type="button" value="$arr_ad_lng[1033]" onclick="Movedown(this.form.list2)" name="B4">
		<input type="button" onclick="GetOptions(this.form.list2, 'admin.php?bmod=$thisprog&action=order')" value="$arr_ad_lng[774]"> <input type="reset" value="$arr_ad_lng[407]">
		$arr_ad_lng[1034]

			    $tab_bottom</form>
					</td>
					</tr>
					</tr>
	</table>
		
<table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class">
  <tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong><a href="admin.php?bmod=$thisprog&action=add">$arr_ad_lng[465]</a> | <a href=admin.php?bmod=$thisprog&action=update>$arr_ad_lng[932]</a></strong> 
 </td></tr>
 <tr><td bgcolor='#14568A' colspan=2 align=right><font color='#F9FAFE'> 
 <strong>Plugins-Center  Version 2.0  Program by: Bob Shen, Last Modify: YutingPC</strong> 
 </td></tr> 
</table>
EOT;
} 
if ($action == "order") {
	$newlines	= "";
	$changefile	= file($pf);
	
	$count = count($forumorder);
	for ($i = 0; $i < $count; $i++) {
		$this_id   = $forumorder[$i];
		$newlines .= $changefile[$this_id];
	}
	
	writetofile($pf, $newlines);
	
	update();
	
	exit;
}

if ($action == "addplug") {
	if (empty($plugname)) {
		errbar("$arr_ad_lng[467]");
	} 
	if (!empty($plugurl) && empty($plugdisplay)) errbar("$arr_ad_lng[468]");
	if (($ifdis == "yes") && (empty($plugurl) || empty($plugdisplay)))errbar("$arr_ad_lng[469]");

	if (empty($plugadmin)) {
		$plugadmin = "#";
	} 

	$id = time();
	$tmp = @readfromfile($pf);
	$tmp = "<?php //|" . "$plugname|$plugadmin|$id|$plugurl|$plugdisplay|$ifdis|\n" . $tmp;
	writetofile($pf, $tmp);
	update();
	errbar ("$arr_ad_lng[470]");
} 

if ($action == "modplug") {
	$plist = @file($pf);
	$m = count($plist);
	for ($i = 0; $i < $m; $i++) {
		$de = explode("|", $plist[$i]);
		if ($de[3] == $id) break;
	} 
	if ($de[6] == "yes") $tmp1 = "selected";
	elseif ($de[6] == "no") $tmp2 = "selected";
	elseif ($de[6] == "club") $tmp3 = "selected";
	else $tmp4 = "selected";
	if ($de[7]) {
		if (safepath_check("plugins/plugins/{$de[7]}.php")) {
			require("plugins/plugins/{$de[7]}.php");
		} else {
			if (safepath_check("plugins/plugins/{$de[7]}.bp3")) {
				require("plugins/plugins/{$de[7]}.bp3");
			} 
		} 
		$addopt2 = "";
		if ($plugins_firstinstall) {
			$addopt2 .= "<a href=admin.php?bmod=$thisprog&action=runpart&id=$id&step=1>$arr_ad_lng[947]</a><br />";
		} 
		if ($plugins_mprogram) {
			$addopt2 .= "<a href=admin.php?bmod=$thisprog&action=runpart&id=$id&step=2>$arr_ad_lng[948]</a><br />";
		} 
		if ($plugins_mtemplate) {
			$addopt2 .= "<a href=admin.php?bmod=$thisprog&action=runpart&id=$id&step=3>$arr_ad_lng[949]</a><br />";
		} 
		$de[5] = htmlspecialchars(stripslashes($de[5]));
	} 
	if ($addopt2) $addopt2 = "<strong>$arr_ad_lng[950]</strong><br />" . $addopt2;
	print <<<eot
<table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class">
<tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[471]</strong> 
 </td></tr> 
<tr> <form action=admin.php?bmod=$thisprog method=post><td bgcolor='#F9FAFE'>
 <input type=hidden name="action" value="modify">  <input type=hidden name="id" value="$de[3]"> 

$arr_ad_lng[472]<input type="text"  value="$de[1]" name="plugname"> $arr_ad_lng[477]<br />
$arr_ad_lng[473]<input type="text"  value="$de[2]"  name="plugadmin"> $arr_ad_lng[478]<br />
$arr_ad_lng[474]<input type="text"  value="$de[4]" name="plugurl"> $arr_ad_lng[479]<br />
$arr_ad_lng[475]<input type="text"  value="$de[5]" name="plugdisplay"> $arr_ad_lng[480]<br />
$arr_ad_lng[476]<SELECT name="ifdis">
$addopt
  <OPTION $tmp2 value="no">$arr_ad_lng[482]</OPTION></SELECT> $arr_ad_lng[483]<br />
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$arr_ad_lng[484]
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  value="$arr_ad_lng[485]">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset"  value="$arr_ad_lng[486]"><br />$addopt2
</td></form></tr>
<tr><td align=center bgcolor=#F9FAFE><a href=admin.php?bmod=$thisprog>$arr_ad_lng[487]</a></td></tr>
</table>
eot;
} 

if ($action == "modify") {
	if (empty($plugname)) {
		errbar("$arr_ad_lng[467]");
	} 
	if (!empty($plugurl) && empty($plugdisplay)) errbar("$arr_ad_lng[468]");
	if (($ifdis <> "no") && (empty($plugurl) || empty($plugdisplay)))errbar("$arr_ad_lng[469]");

	if (empty($plugadmin)) {
		$plugadmin = "#";
	} 

	$plist = @file($pf);
	$m = count($plist);
	for ($i = 0; $i < $m; $i++) {
		$de = explode("|", $plist[$i]);
		if ($de[3] == $id) {
			$de[7] = str_replace("\n", "", $de[7]);
			$plist[$i] = "<?php //|$plugname|$plugadmin|$id|$plugurl|$plugdisplay|$ifdis|$de[7]|\n";
			break;
		} 
	} 
	$tmp = implode("", $plist);
	writetofile($pf, $tmp);
	update();
	errbar ("$arr_ad_lng[470]");
} 

if ($action == "delplug") {
	$plist = @file($pf);
	$m = count($plist);
	for ($i = 0; $i < $m; $i++) {
		$de = explode("|", $plist[$i]);
		if ($de[3] == $id) {
			$plist[$i] = "";
			break;
		} 
	} 
	$tmp = implode("", $plist);
	writetofile($pf, $tmp);
	update();
	errbar ("$arr_ad_lng[488]");
} 

if ($action == "runpart") {
	if ($step == 1) {
		$tofirstinstall = 1;
	} 
	if ($step == 2) {
		$tomprogram = 1;
	} 
	if ($step == 3) {
		$tomtemplate = 1;
	} 
	$plist = @file($pf);
	$m = count($plist);
	for ($i = 0; $i < $m; $i++) {
		$de = explode("|", $plist[$i]);
		if ($de[3] == $id) break;
	} 
	if ($de[7]) {
		if (safepath_check("plugins/plugins/{$de[7]}.php")) {
			require("plugins/plugins/{$de[7]}.php");
		} else {
			if (safepath_check("plugins/plugins/{$de[7]}.bp3")) {
				require("plugins/plugins/{$de[7]}.bp3");
			} 
		} 
	} 
	print <<<eot
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class"><tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[471]</strong> 
 </td></tr> 
<tr><td bgcolor='#F9FAFE'>
	$output <br />
	<strong>$arr_ad_lng[946]</strong><br />
</td></tr>
<tr><td align=center bgcolor=#F9FAFE><a href=admin.php?bmod=$thisprog>$arr_ad_lng[487]</a></td></tr>
</table>
eot;
} 

if ($action == "autoinstall") {
	$codename = basename($codename);
	if (safepath_check("plugins/plugins/$codename.php")) {
		require("plugins/plugins/$codename.php");
	} else {
		if (safepath_check("plugins/plugins/$codename.bp3")) {
			require("plugins/plugins/$codename.bp3");
		} else {
			errbar($arr_ad_lng[936]);
		} 
	} 
	if ($plugins_codename != $codename) {
		errbar($arr_ad_lng[936]);
	} 
	if (!$step) {
		$addopt = "";
		if ($plugins_mprogram) {
			$addopt .= "$arr_ad_lng[940] <input type=\"radio\"  name=\"ifmprogram\" value='1'> $arr_ad_lng[942] <input type=\"radio\"  name=\"mprogram\" value='0'> $arr_ad_lng[943]<br />";
		} 
		if ($plugins_mtemplate) {
			$addopt .= "$arr_ad_lng[941] <input type=\"radio\"  name=\"ifmtemplate\" value='1'> $arr_ad_lng[942] <input type=\"radio\"  name=\"mprogram\" value='0'> $arr_ad_lng[943]";
		} 
		print <<<eot
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class"><tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[933]</strong> 
 </td></tr> 
<tr><form action=admin.php?bmod=$thisprog method=post><td bgcolor='#F9FAFE'>
	<strong>$arr_ad_lng[937]</strong> $plugins_name<br />
	$plugins_detail<br />
	<strong>$arr_ad_lng[938]</strong> $plugins_instruction<br />
	<strong>$arr_ad_lng[939]</strong> $plugins_author<br />
    <input type=hidden name="action" value="autoinstall"> 
    <input type=hidden name="step" value="1"> 
	<input type=hidden name="codename" value="$codename"> 
	$addopt
	<input type="submit"  value="$arr_ad_lng[944]">
</td></form></tr>
<tr><td align=center bgcolor=#F9FAFE><a href=admin.php?bmod=$thisprog>$arr_ad_lng[487]</a></td></tr>
</table>
eot;
	} 
	if ($step == 1) {
		if (empty($plugins_admin)) {
			$plugins_admin = "#";
		} 
		if($plugins_display != "yes") $plugins_display = "no";
		$id = time();
		$tmp = @readfromfile($pf);
		$tmp = "<?php //|" . "$plugins_name|$plugins_admin|$id|$plugins_entrance|$plugins_entrance_name|$plugins_display|$plugins_codename|\n" . $tmp;
		writetofile($pf, $tmp);
		if ($plugins_firstinstall) {
			$tofirstinstall = 1;
		} 
		if ($ifmprogram) {
			$tomprogram = 1;
		} 
		if ($ifmtemplate) {
			$tomtemplate = 1;
		} 
		if (safepath_check("plugins/plugins/$codename.php")) {
			require("plugins/plugins/$codename.php");
		} else {
			if (safepath_check("plugins/plugins/$codename.bp3")) {
				require("plugins/plugins/$codename.bp3");
			} else {
				errbar($arr_ad_lng[936]);
			} 
		} 
		print <<<eot
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class"><tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[933]</strong> 
 </td></tr> 
<tr><form action=admin.php?bmod=$thisprog method=post><td bgcolor='#F9FAFE'>
	$output <br />
	<strong>$arr_ad_lng[945]</strong>
    <input type=hidden name="action" value="modplug"> 
    <input type=hidden name="id" value="$id"> 
	<input type="submit"  value="$arr_ad_lng[944]"><br />
</td></form></tr>
<tr><td align=center bgcolor=#F9FAFE><a href=admin.php?bmod=$thisprog>$arr_ad_lng[487]</a></td></tr>
</table>
eot;
	} 
} 
if ($action == "update") {
	if (!$step) {
		update();
		print <<<eot
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class"><tr> 
 <td bgcolor='#F9FAFE' valign='middle' align='center' colspan=2> 
 <strong>$arr_ad_lng[932]</strong> 
 </td></tr> 
<tr><td bgcolor='#F9FAFE'>
	<strong>$arr_ad_lng[931]</strong><br />
	<a href=admin.php?bmod=$thisprog&action=update&step=1>$arr_ad_lng[948]</a><br />
	<a href=admin.php?bmod=$thisprog&action=update&step=2>$arr_ad_lng[949]</a><br />
</td></tr>
<tr><td align=center bgcolor=#F9FAFE><a href=admin.php?bmod=$thisprog>$arr_ad_lng[487]</a></td></tr>
</table>
eot;
	} 
	if ($step == 1) {
		$plist = @file($pf);
		$m = count($plist);
		$tomprogram = 1;
		for ($i = 0; $i < $m; $i++) {
			$de = explode("|", $plist[$i]);
			if ($de[7]) {
				if (safepath_check("plugins/plugins/{$de[7]}.php")) {
					require("plugins/plugins/{$de[7]}.php");
				} else {
					if (safepath_check("plugins/plugins/{$de[7]}.bp3")) {
						require("plugins/plugins/{$de[7]}.bp3");
					} 
				} 
			} 
		} 
		errbar($arr_ad_lng[946]);
	} 
	if ($step == 2) {
		$plist = @file($pf);
		$m = count($plist);
		$tomtemplate = 1;
		for ($i = 0; $i < $m; $i++) {
			$de = explode("|", $plist[$i]);
			if ($de[7]) {
				if (safepath_check("plugins/plugins/{$de[7]}.php")) {
					require("plugins/plugins/{$de[7]}.php");
				} else {
					if (safepath_check("plugins/plugins/{$de[7]}.bp3")) {
						require("plugins/plugins/{$de[7]}.bp3");
					} 
				} 
			} 
		} 
		errbar($arr_ad_lng[946]);
	} 
} 

if ($action == "add") {
	print <<<eot
$table_start
 <strong>$arr_ad_lng[933]</strong> 
 $table_stop
 <form action=admin.php?bmod=$thisprog method="post" style="margin:0px;"><td bgcolor='#F9FAFE'>
 <input type=hidden name="action" value="autoinstall"> 
 $arr_ad_lng[934]<input type="text"  name="codename"> &nbsp;&nbsp;&nbsp;<input type="submit"  value="$gl[42]">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset"  value="$gl[43]">
</form>
$table_start
 <strong>$arr_ad_lng[935]</strong> 
$table_stop <form action=admin.php?bmod=$thisprog method=post style="margin:0px;"><td bgcolor='#F9FAFE'>
 <input type=hidden name="action" value="addplug"> 
$arr_ad_lng[472]<input type="text"  name="plugname"> $arr_ad_lng[477]<br />
$arr_ad_lng[473]<input type="text"  name="plugadmin"> $arr_ad_lng[478]<br />
$arr_ad_lng[474]<input type="text"  name="plugurl"> $arr_ad_lng[479]<br />
$arr_ad_lng[475]<input type="text"  name="plugdisplay"> $arr_ad_lng[480]<br />
$arr_ad_lng[476]<SELECT name="ifdis">
$addopt
  <OPTION value="no">$arr_ad_lng[482]</OPTION></SELECT> $arr_ad_lng[483]<br />
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$arr_ad_lng[484]
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  value="$gl[42]">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset"  value="$gl[43]">
</form>$table_start<a style="color:#FFFFFF;" href="admin.php?bmod=$thisprog">$arr_ad_lng[487]</a></td></tr>
</table>
eot;
} 

function errbar($info)
{
	global $thisprog, $arr_ad_lng;
	echo "
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[489]</strong>
    </td>
    </tr>          
<tr><td bgcolor=#FFFFFF align=center>$info<br /><br /><a href='javascript:history.back(1)'>$arr_ad_lng[361]</a><br /><a href=admin.php?bmod=" . $thisprog . ">$arr_ad_lng[490]</a><br /><br /></td></tr></table>";
	exit;
} 

function update()
{
	global $pf, $pdf, $pcf;
	if (file_exists($pf)) {
		$all = "<?php\n\$myplugin=\"";
		$all2 = "<?php\nif(INBMFORUM!=1) exit; // 插件引入 Plugin Include\n";
		$plist = @file($pf);
		$m = count($plist);
		for ($i = 0; $i < $m; $i++) {
			$de = explode("|", $plist[$i]);
			if ($de[6] == "yes" || $de[6] == "both") {
				/*if ($de[4] == "club.php" || $de[4] == "plugins.php?p=club")
				/	$all .= " | <a href='$de[4]' class='titlefontcolor' onmouseover=\\\"showmenu(event,linkset[6],1)\\\" onmouseout=\\\"delayhidemenu()\\\">$de[5]</a>";
				else*/
				if(!$havePlugins) {
					$all .= "<ul class='dropdown-menu'>";
					$havePlugins = true;
				}
				
				$all .= "<li><a href='$de[4]'>$de[5]</a></li>";
			} 
			if ($de[6] == "club" || $de[6] == "both") $club .= "$de[4]|$de[5]|\n";
			$plugins_include = "";
			if ($de[7]) {
				if (safepath_check("plugins/plugins/{$de[7]}.php")) {
					require("plugins/plugins/{$de[7]}.php");
				} else {
					if (safepath_check("plugins/plugins/{$de[7]}.bp3")) {
						require("plugins/plugins/{$de[7]}.bp3");
					} 
				} 
			} 
			if ($plugins_include && safepath_check($plugins_include)) {
				$all2 .= "@include(\"$plugins_include\");";
			} 
		} 
	} 
	if($havePlugins) {
		$all .= '</ul>';
	}
	$all .= "\";";
	writetofile($pdf, $all);
	writetofile($pcf, $club);
	writetofile("datafile/pluginclude.php", $all2);
} 
function safepath_check($filename)
{
	if (file_exists($filename) && !strstr($filename, "..")) {
		return 1;
	} else {
		return 0;
	} 
} 

?>