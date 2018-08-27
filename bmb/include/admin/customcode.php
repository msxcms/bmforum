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
$thisprog = "customcode.php";

if ($useraccess != "1" || $admgroupdata[40] != "1") {
    adminlogin();
} 

if (!$action) { // Form Page

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=9><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1157]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=9>
  <font color=#333333><strong>$arr_ad_lng[1157]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>
               

               
$table_start
<strong>$arr_ad_lng[419]</strong>
$table_stop
  <font color=#000000>$arr_ad_lng[1158]</font>
$table_start
EOT;
    $query = "SELECT * FROM {$database_up}bmbcode ORDER BY `displayorder` ASC";
    $result = bmbdb_query($query);
	$i = 1;
    while (false !== ($tmpline = bmbdb_fetch_array($result))) {
    	$i++;
    	$bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
if ($i == 2) {
print<<<EOT
</td></tr>
 <tr bgcolor="#6DA6D1" valign="middle" style="text-align:center;font-weight:bold;">
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1159]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1147]</td>
 </tr>
EOT;
    	$i++;
    	$bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
}

print<<<EOT
 <tr bgcolor="$bgcolor" style="text-align:center;" valign=middle>
  <td bgcolor="$bgcolor"><strong>$tmpline[codetag]</strong></td>
  <td bgcolor="$bgcolor"><a href="admin.php?bmod=$thisprog&action=edit&id=$tmpline[id]">$arr_ad_lng[1146]</a>
     <a href="#here" onclick="javascript:cprocess('admin.php?bmod=$thisprog&action=process&type=del&id=$tmpline[id]');">$arr_ad_lng[1152]</a>
</td>
 </tr>
EOT;

	$items_select.= "<option value='$tmpline[id]'>$tmpline[codetag]</option>\n";

    }
    $i++;
    $bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
print<<<EOT
 <tr bgcolor="$bgcolor" valign=top>
  <td colspan="9" align="center"><strong><a href="admin.php?bmod=$thisprog&action=edit&type=add">$arr_ad_lng[1160]</a></strong></td>
 </tr>
</form>
</table></td></tr></table>
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

  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "modifyorder") {
	
    $count = count($forumorder);
    
    for ($i = 0; $i<$count; $i++) {
    	if ($forumorder[$i] == "") continue;
    	$order = $i + 1;
        $result = bmbdb_query("UPDATE {$database_up}bmbcode SET displayorder=$order WHERE id='{$forumorder[$i]}'");
    }
    
    refresh_forumcach("bmbcode", "tcode_item", "tcode_count", "displayorder");
	exit;

} elseif ($action == "process") { 
	if ($type == "del" ) {
		bmbdb_query("DELETE FROM {$database_up}bmbcode where id='$id'");
	}
	refresh_forumcach("bmbcode", "tcode_item", "tcode_count", "displayorder");
print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1157]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[1157]</strong>
  </td></tr>
	<tr>
	<td bgcolor=#F9FAFE valign=middle colspan=2>
	<strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
exit;
} elseif ($action == "edit") { 
	
	if ($step == 2) {
		$refrom	= base64_encode(stripslashes($refrom));
		$reto	= base64_encode(stripslashes($reto));
		
		if ($type != "add") {
			bmbdb_query("DELETE FROM {$database_up}bmbcode where id='$id'");
		}
		
		$enable = $enable ? 1 : 0;

		bmbdb_query("INSERT INTO {$database_up}bmbcode (`refrom`,`enable`,`reto`,`codetag`,`name`,`desc`,`example`,`nestings`,`displayorder`) 
		VALUES ('$refrom', $enable, '$reto', '$codetag', '$coname', '$desc', '$example', $nestings, $timestamp)");
		
		refresh_forumcach("bmbcode", "tcode_item", "tcode_count", "displayorder");
		
		
		
print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1157]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[1157]</strong>
  </td></tr>
	<tr>
	<td bgcolor=#F9FAFE valign=middle colspan=2>
	<strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;

		exit;
	}
	
	if ($type != "add") {
	    $query = "SELECT * FROM {$database_up}bmbcode where id='$id'";
	    $result = bmbdb_fetch_array(bmbdb_query($query));
	}
	
	$selected['enable'] = $result[enable] ? "checked = 'checked'" : "";
	
	if (is_array($result)) {
		foreach ($result as $key=>$value) {
			if ($key == "refrom" || $key == "reto") $value = base64_decode($value);
			$result[$key]=htmlspecialchars($value);
		}
	}
	
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1157]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2 style="border-bottom: #C47508 1px soild;">
  <font color=#333333><strong>$arr_ad_lng[1157]</strong>
  </td></tr>
               
  <form action="admin.php?bmod=$thisprog&action=edit&step=2" method="post">
  <input type="hidden" name="type" value="$type">
  <input type="hidden" name="id" value="$id">
 <tr bgcolor="#FFC96B" valign="middle">
  <td width="100%" colspan="2" style="border: #C47508 1px soild;">$arr_ad_lng[1166]</td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%">$arr_ad_lng[1161]</td>
  <td width="80%"><input type="text" value="$result[name]" name="coname" /> <input type="checkbox" value="1" name="enable" {$selected['enable']} />$arr_ad_lng[1155]</td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%">$arr_ad_lng[1162]</td>
  <td width="80%"><input type="text" value="$result[codetag]" name="codetag" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1163]</strong></td>
  <td width="80%"><input type="text" value="$result[desc]" name="desc" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1164]</strong></td>
  <td width="80%"><input type="text" value="$result[refrom]" name="refrom" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1165]</strong></td>
  <td width="80%"><input type="text" value="$result[reto]" name="reto" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%">$arr_ad_lng[1167]</td>
  <td width="80%"><input type="text" value="$result[example]" name="example" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1168]</strong></td>
  <td width="80%"><input type="text" value="$result[nestings]" name="nestings" /></td>
 </tr>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="100%" colspan="2"><input type="submit" value="$arr_ad_lng[774]" /> <input type="reset" /></td>
 </tr>
</form>
</table></td></tr></table>
  </td></tr></table></body></html>
EOT;
    exit;
} 
