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

$thisprog = "reg_custom.php";

if ($useraccess != "1" || $admgroupdata[38] != "1") {
    adminlogin();
} 

if (file_exists('datafile/reg_custom.php')) $reg_c = file("datafile/reg_custom.php"); 
// Load messages
print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[1107]</strong>
		</td></tr>";
if (!$action) {
    // Load messages

    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[1107]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="add">
		</td></tr>

$table_start
		<strong>$arr_ad_lng[419]</strong>
$table_stop
		$arr_ad_lng[1108]
$table_start
	
		<strong>$arr_ad_lng[1109]</strong>
			
$table_stop
	$arr_ad_lng[1111] <input type="text" value="" name="f_name" /> 
	<input type=submit value="$arr_ad_lng[66]">
$tab_bottom	
	</form>
$table_start<strong>$arr_ad_lng[1110]</strong>$table_stop
			<ul>
			
EOT;

$count = count($reg_c);
for($i = 0;$i<$count; $i++) {
	$detail = explode("|", $reg_c[$i]);
	$items_select .= "<option value='$i'>$detail[1]</option>";
echo<<<EOT
<li>
	<a href="admin.php?bmod=$thisprog&action=edit&id=$detail[0]">$detail[1]</a> | <a href="javascript:cprocess('admin.php?bmod=$thisprog&action=remove&id=$i');">$arr_ad_lng[1152]</a> 
	<br/>
</li>
EOT;

}

print <<<EOT
	</ul>
    $table_start
    <strong>$arr_ad_lng[568]</strong>
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="modifyorder">
    $table_stop
<select multiple size=8 style="width: 50%;" name="list2" style="width: 120px">
$items_select </select>
<br />
<input type="button" value="$arr_ad_lng[1032]" onclick="Moveup(this.form.list2)" name="B3">
<input type="button" value="$arr_ad_lng[1033]" onclick="Movedown(this.form.list2)" name="B4">
<input type="button" onclick="GetOptions(this.form.list2, 'admin.php?bmod=$thisprog&action=modifyorder')" value="$arr_ad_lng[774]"> <input type=reset value="$arr_ad_lng[407]">
$arr_ad_lng[1034]<script src="images/bmb_ajax.js"></script>

		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		</td></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "modifyorder") {
	
	$count = count($forumorder);
	
	for ($i = 0;$i <$count;$i++) {
		$thisid = $forumorder[$i];
		$new_reg_c .= $reg_c[$thisid];
	}
	writetofile("datafile/reg_custom.php", $new_reg_c);
    
	exit;
} elseif ($action == "remove") {
	unset($reg_c[$id]);
	$newinfo = implode("", $reg_c);
	writetofile("datafile/reg_custom.php", $newinfo);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
	<p align=center><a href=admin.php?bmod=$thisprog>$arr_ad_lng[1107]</A>
		</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "edit") {

if ($step == 2){
	$count = count($reg_c);
	for($i = 0;$i<$count; $i++) {
		$detail = explode("|", $reg_c[$i]);
		if ($detail[0] == $id) {
			unset($reg_c[$i]);
			break;
		}
	}
	
	$options_menu = explode("\n", $menuitems);
	
	for($x=0;$x<count($options_menu);$x++){
		$menuitem_cache.= "<option value='$x'>$options_menu[$x]</option>\n";
	}
	$menuitem_cache = base64_encode($menuitem_cache);
	
	$menuitems = base64_encode(serialize($options_menu));
	
	$reg_c[]="$id|$f_name|$f_des|$f_type|$menuitems|$display_inpost|$display_require|$display_unedit|$display_max|$display_public|$max_length|$menuitem_cache|\n";
	
	$writeinfo = implode("", $reg_c);
	
	writetofile("datafile/reg_custom.php", $writeinfo);

    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
	<p align=center><a href=admin.php?bmod=$thisprog>$arr_ad_lng[1107]</A>
		</td></tr></table></body></html>
EOT;
    exit;

}

$detail = "";
$count = count($reg_c);
for($i = 0;$i<$count; $i++) {
	$detail = explode("|", $reg_c[$i]);
	if ($detail[0] == $id) break;
	
}

if (!$detail[0]) exit;
	

$count = count($detail);

for($i = 0;$i<$count; $i++) {
	if ($detail[$i] == 1) $selected[$i] = "checked=\"checked\"";
}
$s_o["$detail[3]"] = "selected=\"selected\"";
	
	$menuitems = @implode("\n",unserialize(base64_decode($detail[4])));
	
echo<<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[1107]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>

		<form action="admin.php?bmod=$thisprog&action=edit&step=2&id=$id" method="post">
	$arr_ad_lng[1111] <input type="text" value="$detail[1]" name="f_name" /> <br/>
	$arr_ad_lng[1112] <input type="text" value="$detail[2]" name="f_des" /><br/>
	$arr_ad_lng[1113] <select name="f_type" onchange="javascript:select_js(this.value)"/>
	<option value="1" {$s_o[1]}>$arr_ad_lng[1115]</option>
	<option value="2" {$s_o[2]}>$arr_ad_lng[1116]</option>
	<option value="3" {$s_o[3]}>$arr_ad_lng[1117]</option>
	</select><br/><br/>
	<div id="menu" style="display:none;">
	$arr_ad_lng[1122]<br/>
	<textarea cols="60" rows="6" name="menuitems">$menuitems</textarea>
	<br/><br/>
	</div>
	<input type="checkbox" {$selected[5]} value="1" name="display_inpost" / >$arr_ad_lng[1114]<br/>
	<input type="checkbox" {$selected[6]} value="1" name="display_require" / >$arr_ad_lng[1118]<br/>
	<input type="checkbox" {$selected[7]} value="1" name="display_unedit" / >$arr_ad_lng[1119]<br/>
	<input type="checkbox" {$selected[8]} value="1" name="display_max" / >$arr_ad_lng[1120] <input type="text" value="$detail[10]" name="max_length"/><br/>
	<input type="checkbox" {$selected[9]} value="1" name="display_public" / >$arr_ad_lng[1121]<br/>
	<br/><br/>
	<input type="submit" value="$arr_ad_lng[66]" />
	<br/></form>
<script type="text/javascript">
function select_js(itemn){
	if (itemn == 3) {
		document.getElementById("menu").style.display="";
	} else {
		document.getElementById("menu").style.display="none";
	}
}
select_js($detail[3]);
</script>
EOT;
exit;
} elseif ($action == "add") {
	
	writetofile("datafile/reg_custom.php", "$timestamp|$f_name|||||||||||\n", "a");

    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
	<p align=center><a href=admin.php?bmod=$thisprog>$arr_ad_lng[1107]</A>
		</td></tr></table></body></html>
EOT;
    exit;
} 
