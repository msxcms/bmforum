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

$thisprog = "usergroup.php";

if ($useraccess != "1" || $admgroupdata[13] != "1") {
    adminlogin();
} 

$maingroupdata = $usergroupdata;

if ($targetid != "") { // Load Forum Usergroup
    $query = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid'";
    $result = bmbdb_query($query);
    $line = bmbdb_fetch_array($result);
    $usergroupdata = explode("\n", $line['usergroup']);
} 

$usertypelist = "";
$count = count($usergroupdata);

if (!$action) {
	if ($target == "") {
        $query = "SELECT * FROM {$database_up}usergroup WHERE regwith=1 LIMIT 0,1";
        $result = bmbdb_query($query);
        $line = bmbdb_fetch_array($result);
        $usergroupreg = $line['id'];
	}
    $usertypelist = "";
    for ($i = 0; $i < $ugsocount ; $i++) {
        // Print usergroup table
        $getit = $ugshoworder[$i];
        $detail = explode("|", $usergroupdata[$getit]);
        if (!empty($detail[0])) {
            if (floor($i / 2) != $i / 2) $bgc = "#F9FAFE";
            else $bgc = "#F9FAFE";
            $del = "";
            $systemname = "$arr_ad_lng[265]";
            if ($getit == $usergroupreg) $morengroupname = "$detail[0]";
            if ($detail[2] != 1) $del = "<a href=\"javascript:cprocess('admin.php?bmod=editusergroup.php&id=$getit&action=delete&targetid=$targetid');\">$arr_ad_lng[821][{$detail[0]}]</a>";
            else $systemname = "$arr_ad_lng[822]";
            if ($targetid == "") $setregshows = "<td bgcolor=$bgc width=25%><a href=admin.php?bmod=usergroup.php&id=$getit&action=setreg>$arr_ad_lng[823][{$detail[0]}]$arr_ad_lng[824]</a></td><td bgcolor=$bgc width=25%><a href=admin.php?bmod=admingroup.php&id=$getit>$arr_ad_lng[825][{$detail[0]}]$arr_ad_lng[826]</a></td>";
            $usertypelist .= "<tr width='100%'><td><a href=admin.php?bmod=editusergroup.php&id=$getit&targetid=$targetid>$arr_ad_lng[825]{$systemname}[{$detail[0]}]</a></td>{$setregshows}<td bgcolor=$bgc width=25%>$del</td></tr>";
			$ugrgroupselect .= "<option value='u|{$getit}' selected='selected'>".$detail[0]."</option>";
        } 
    } 
    // Levels Group
    
    include("datafile/usertitle.php");
    for ($u = 0;$u < $countmtitle;$u++) {
    	$levelstypelist .= "<tr width='100%'><td><a href='admin.php?bmod=editusergroup.php&levelname=".urlencode($mtitle['a'.$u])."&level=1&id=$u&targetid=$targetid'>$arr_ad_lng[825][{$mtitle['a'.$u]}]</a></td></tr>";
		$ugrgroupselect .= "<option value='l|{$u}' selected='selected'>".$mtitle['a'.$u]."</option>";
    }
	$oquery = ($targetid) ? "SELECT * FROM {$database_up}ugoptlist where noforum!=1" : "SELECT * FROM {$database_up}ugoptlist";
	$oresult = bmbdb_query($oquery);
	while (false !== ($oline = bmbdb_fetch_array($oresult))) {
		eval('$optname = "'.$oline['optname'].'";');
		$optgroupselect .= "<option value='{$oline['id']}'>".$optname."</option>";
	}
    // 
    $usertypelist .= ""; 
    // Select Box: Usergroup
    $groupselect = "";
    for ($i = 0; $i < $ugsocount ; $i++) {
        $getit = $ugshoworder[$i];
        $detail = explode("|", $usergroupdata[$getit]);
        $groupselect .= "<option value=\"$getit\">$detail[0]</option>";
    } 
    $groupselect .= "</select>";

    if ($targetid != "") {
        $stusergroup = "$table_start<a style='color:#FFFFFF;font-weight:bold;' href=admin.php?bmod=usergroup.php&targetid=$targetid&action=stb>$arr_ad_lng[827]</a>";
    } 

    if ($targetid == "") {
$showsortop=<<<EOT
		<script src="images/bmb_ajax.js"></script>
			$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section2"></a>$arr_ad_lng[828]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

		    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
	    $table_stop
<select multiple size=4 style="width: 50%;" name="list2" style="width: 120px">
$groupselect
<br />
<input type="button" value="$arr_ad_lng[1032]" onclick="Moveup(this.form.list2)" name="B3">
<input type="button" value="$arr_ad_lng[1033]" onclick="Movedown(this.form.list2)" name="B4">
<input type="button" onclick="GetOptions(this.form.list2, 'admin.php?bmod=$thisprog&action=modifyorder')" value="$arr_ad_lng[774]"> <input type=reset value="$arr_ad_lng[407]">
$arr_ad_lng[1034]

	    $tab_bottom</form>
			</td>
			</tr>
			</tr>
EOT;

		
        
		$addusergroupopenb="<br /><strong>$arr_ad_lng[835]</strong><br />$arr_ad_lng[836]$morengroupname";
        $addusergroupopened = "$table_start <a style='color:#FFFFFF;' href=\"admin.php?bmod=addusergroup.php&targetid=$targetid\">$arr_ad_lng[833]</a> $table_stop";
    } 
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[201]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[837]</strong>
		</td></tr>
  <tr bgcolor=FFC96B>
   <td colspan=2 style="border: #C47508 1px soild;"><a name="top"></a><a href="#section1">$arr_ad_lng[838]</a> | <a href="#section3">$arr_ad_lng[1191]</a> | <a href="#section4">$arr_ad_lng[837]</a> | <a href="#section5">$arr_ad_lng[1174]</a> | <a href="#section2">$arr_ad_lng[828]</a> | <a href="admin.php?bmod=addusergroup.php">$arr_ad_lng[200]</a></td>
  </tr>
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section1"></a>$arr_ad_lng[838]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
$table_stop
	$arr_ad_lng[839] $addusergroupopenb
			$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="sectionx"></a>$arr_ad_lng[1246]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

		    <form action="admin.php?bmod=editgroupopt.php&targetid=$targetid" method="post" style="margin:0px;">
	    $table_stop
<select multiple='multiple' size='8' style="width: 50%;" name="selectedopt[]" style="width: 120px">
$optgroupselect
</select>
<br /><br /><strong>$arr_ad_lng[1247]</strong><br />
<select multiple='multiple' size='8' style="width: 50%;" name="selectedugr[]" style="width: 120px">
$ugrgroupselect
</select>
<br />
<input type="submit" value="$arr_ad_lng[774]"> <input type=reset value="$arr_ad_lng[407]">


	    $tab_bottom</form>
			</td>
			</tr>
			</tr>

	$showsortop
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section3"></a>$arr_ad_lng[1068]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

$table_stop
	    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">

$arr_ad_lng[1069] <select name="copy_template">$groupselect <br />
$arr_ad_lng[1070] <input type="text" size="20" name="new_usergroup" value="$arr_ad_lng[1071]" />
<br /><input type="submit" value="$arr_ad_lng[774]" />
<input type="hidden" name="action" value="copy_usergroup" />

    </form>
$stusergroup
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section4"></a>$arr_ad_lng[837]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
		$usertypelist
		</td>
		</tr>
$table_stop
$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section5"></a>$arr_ad_lng[1174]</strong></div>
  <div style='display:inline;float:right;'><a href="javascript:cprocess('admin.php?bmod=usergroup.php&action=rebuild_level&targetid=$targetid');" style='color:#FFFFFF;'>$arr_ad_lng[1183]</a></div></div>
	
		$levelstypelist
	
$addusergroupopened
		
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		</tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "rebuild_level") {
	
	$targetid = $targetid ? $targetid : 0;
	
	bmbdb_query("DELETE FROM `{$database_up}levels` WHERE `fid`='$targetid'");

	include("datafile/usertitle.php");

	for ($i =0;$i<$countmtitle;$i++) {
		bmbdb_query("INSERT INTO `{$database_up}levels` VALUES ($i, '{$usergroupdata[4]}',$targetid)");
	}

	$query = "SELECT * FROM {$database_up}levels WHERE `fid`='{$targetid}' ORDER BY `id` ASC";
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

    print <<<EOT
<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[201]</strong>
		</td></tr>

		<tr><td bgcolor=F9FAFE colspan=2>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=usergroup.php&targetid=$targetid>$arr_ad_lng[76]</a>
		</td></tr>
		</table></body></html>
EOT;
exit;
} elseif ($action == "copy_usergroup") {
    $ug_result		= bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}usergroup WHERE `id`=$copy_template LIMIT 0,1 "));
	$ug_newname		= explode("|", $ug_result['usersets']);
	$ug_newname[0]	= $new_usergroup;
	$ug_newname[2]	= 0;
	$ug_result['usersets']	= implode("|", $ug_newname);
	
    $linexx = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}usergroup ORDER BY `id` DESC  LIMIT 0,1 "));
    $newlineno = $linexx['id'] + 1;
    
    bmbdb_query("insert into {$database_up}usergroup (id,unshowit,usersets,showsort,adminsets,regwith) values ($newlineno,'{$ug_result['unshowit']}','{$ug_result['usersets']}','$timestamp','{$ug_result['adminsets']}','')");


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
    print <<<EOT
<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[201]</strong>
		</td></tr>

		<tr><td bgcolor=F9FAFE colspan=2>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=usergroup.php>$arr_ad_lng[76]</a>
		</td></tr>
		</table></body></html>
EOT;
exit;
} elseif ($action == "setreg") {
    $nquery = "UPDATE {$database_up}usergroup SET regwith = '1' WHERE id = '$id'";
    $result = bmbdb_query($nquery);

    $nquery = "UPDATE {$database_up}usergroup SET regwith = '' WHERE id!='$id'";
    $result = bmbdb_query($nquery);

    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[201]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=usergroup.php&targetid=$targetid>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;

    exit;
} elseif ($action == "stb") {
    $query = "SELECT * FROM {$database_up}usergroup";
    $result = bmbdb_query($query);
    $new = "";
    while (false !== ($line = bmbdb_fetch_array($result))) {
    	$tmppermission = explode("|", $line['usersets']);
    	$tmppermission[12] = 1;
        $line['usersets'] = str_replace('"', '\"', implode("|", $tmppermission));
        $line['usersets'] = str_replace("\n", "", $line['usersets']);
        $new .= $line['usersets'] . "\n";
    } 
    $nquery = "UPDATE {$database_up}forumdata SET usergroup = '$new' WHERE id = '$targetid'";
    $result = bmbdb_query($nquery);
    print <<<EOT
<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[201]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[840]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=usergroup.php&targetid=$targetid>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
	
    $query = "SELECT * FROM {$database_up}levels WHERE `fid`='0' ORDER BY `id` ASC";
    $result = bmbdb_query($query);
    bmbdb_query("DELETE FROM {$database_up}levels WHERE `fid`='$targetid' ");
    $ugsocount = "";
    $wrting = "<?php ";
    while (false !== ($line = bmbdb_fetch_array($result))) {
    	$tmppermission = explode("|", $line['maccess']);
    	$tmppermission[12] = 1;
        $line['maccess'] = str_replace('"', '\"', implode("|", $tmppermission));
    	bmbdb_query("INSERT INTO {$database_up}levels (`id`,`fid`,`maccess`) VALUES ('$line[id]','$targetid','{$line['maccess']}')");
        $wrting .= "
\$levelgroupdata[{$targetid}][{$line['id']}]=\"{$line['maccess']}\";
";
    } 

    writetofile("datafile/cache/levels/level_fid_{$targetid}.php", $wrting);
    refresh_forumcach();
    exit;

	
} elseif ($action == "modifyorder") {
    // -------改变顺序-----------
    $count = count($forumorder);
    
    for ($i = 0; $i<$count; $i++) {
    	if ($forumorder[$i] == "") continue;
    	$order = $i + 1;
        $result = bmbdb_query("UPDATE {$database_up}usergroup SET showsort=$order WHERE id='{$forumorder[$i]}'");
    }

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
