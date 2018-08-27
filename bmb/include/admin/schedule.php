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
$thisprog = "schedule.php";

if ($useraccess != "1" || $admgroupdata[41] != "1") {
    adminlogin();
} 

if (!$action) { // Form Page

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=9><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1130]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=9>
  <font color=#333333><strong>$arr_ad_lng[1130]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>
               

               
$table_start
<strong>$arr_ad_lng[419]</strong>$table_stop
	
  <font color=#000000>$arr_ad_lng[1151]</font>
$table_start
EOT;
    $query = "SELECT * FROM {$database_up}schedule";
    $result = bmbdb_query($query);
	$i = 1;
    while (false !== ($tmpline = bmbdb_fetch_array($result))) {
    	$i++;
    	$bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
if ($i == 2) {
print<<<EOT

 <tr bgcolor="#6DA6D1" valign="middle" style="color:#FFFFFF;text-align:center;font-weight:bold;">
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1153]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1139]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1138]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1141]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1140]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1143]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1144]</td>
  <td bgcolor="#6DA6D1" style="color:#FFFFFF;">$arr_ad_lng[1147]</td>
 </tr>
EOT;
    	$i++;
    	$bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
}
	$tmpline[last] = getfulldate($tmpline[last]);
	$tmpline[next] = getfulldate($tmpline[next]);
	if ($tmpline['available'] != 1) $tmpline[next]= $arr_ad_lng[1156];
print<<<EOT
 <tr bgcolor="$bgcolor" style="text-align:center;" valign=middle>
  <td bgcolor="$bgcolor"><strong>$tmpline[taskname]</strong><br/>$tmpline[fname]</td>
  <td bgcolor="$bgcolor">$tmpline[day]</td>
  <td bgcolor="$bgcolor">$tmpline[week]</td>
  <td bgcolor="$bgcolor">$tmpline[min]</td>
  <td bgcolor="$bgcolor">$tmpline[hour]</td>
  <td bgcolor="$bgcolor">$tmpline[last]</td>
  <td bgcolor="$bgcolor">$tmpline[next]</td>
  <td bgcolor="$bgcolor"><a href="admin.php?bmod=$thisprog&action=edit&id=$tmpline[id]">$arr_ad_lng[1146]</a>
     <a href="#here" onclick="javascript:cprocess('admin.php?bmod=$thisprog&action=process&type=del&id=$tmpline[id]');">$arr_ad_lng[1152]</a>
     <a href="admin.php?bmod=$thisprog&action=process&type=run&id=$tmpline[id]">$arr_ad_lng[1145]</a></td>
 </tr>
EOT;

    }
    $i++;
    $bgcolor = ($i%2 == 0) ? "#F9FCFE" : "#F9FAFE";
print<<<EOT
 <tr bgcolor="$bgcolor" valign=top>
  <td colspan="9" align="center"><strong><a href="admin.php?bmod=$thisprog&action=edit&type=add">$arr_ad_lng[1150]</a></strong></td>
 </tr>
</form>
</table></td></tr></table>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") { 
	if ($type == "run" ) {
		define("ADMINCENTER", 1);
		require_once("include/schedule.php");
		runschedule($id);
	} elseif ($type == "del" ) {
		bmbdb_query("DELETE FROM {$database_up}schedule where id='$id'");
	}
	refresh_forumcach("schedule", "s_item", "s_count", "id");
print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1130]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[1130]</strong>
  </td></tr>
	<tr>
	<td bgcolor=#F9FAFE valign=middle colspan=2>
	<strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
exit;
} elseif ($action == "edit") { 
	
	if ($step == 2) {
		define("ADMINCENTER", 1);
		require_once("include/schedule.php");
		
		if ($type != "add") {
			bmbdb_query("DELETE FROM {$database_up}schedule where id='$id'");
		}
		
		$enable = $enable ? 1 : 0;
		if ($day < 1 || $day > 32) $day = -1;
		if ($week < 1 || $week > 7) $week = -1;
		if ($min < 0 || $min > 59) $min = -1;
		if ($hour < 0 || $hour > 23) $hour = -1;
		
		bmbdb_query("INSERT INTO {$database_up}schedule (`taskname`,`available`,`day`,`week`,`min`,`hour`,`last`,`next`,`fname`) 
		VALUES ('$taskname', $enable, $day, $week, '$min', $hour, 0, 0, '$fname')");
		
		refresh_forumcach("schedule", "s_item", "s_count", "id");
		
		
		
print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1130]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[1130]</strong>
  </td></tr>
	<tr>
	<td bgcolor=#F9FAFE valign=middle colspan=2>
	<strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;

		exit;
	}
	
	if ($type != "add") {
	    $query = "SELECT * FROM {$database_up}schedule where id='$id'";
	    $result = bmbdb_fetch_array(bmbdb_query($query));
	}
	
	$selected['week']["$result[week]"] = "selected = 'selected'";
	$selected['enable'] = $result[available] ? "checked = 'checked'" : "";
	
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1130]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2 style="border-bottom: #C47508 1px soild;">
  <font color=#333333><strong>$arr_ad_lng[1130]</strong>
  </td></tr>
               
  <form action="admin.php?bmod=$thisprog&action=edit&step=2" method="post">
  <input type="hidden" name="type" value="$type">
  <input type="hidden" name="id" value="$id">
               
 <tr bgcolor="#FFC96B" valign=middle>
  <td width="100%" colspan="2" style="border: #C47508 1px soild;"><strong>$arr_ad_lng[1148]</strong></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1153]</strong></td>
  <td width="80%"><input type="text" value="$result[taskname]" name="taskname" /> <input type="checkbox" value="1" name="enable" {$selected['enable']} />$arr_ad_lng[1155]</td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1138]</strong></td>
  <td width="80%"><select name="week">
     <option {$selected['week']['-1']} value="-1">*</option>
     <option {$selected['week']['1']} value="1">$arr_ad_lng[1131]</option>
     <option {$selected['week']['2']} value="2">$arr_ad_lng[1132]</option>
     <option {$selected['week']['3']} value="3">$arr_ad_lng[1133]</option>
     <option {$selected['week']['4']} value="4">$arr_ad_lng[1134]</option>
     <option {$selected['week']['5']} value="5">$arr_ad_lng[1135]</option>
     <option {$selected['week']['6']} value="6">$arr_ad_lng[1136]</option>
     <option {$selected['week']['0']} value="0">$arr_ad_lng[1137]</option>
     </select></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1139]</strong><br />$arr_ad_lng[1154] 1-31</td>
  <td width="80%"><input type="text" value="$result[day]" name="day" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1140]</strong><br />$arr_ad_lng[1154] 0-23</td>
  <td width="80%"><input type="text" value="$result[hour]" name="hour" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1141]</strong><br />$arr_ad_lng[1154] 0-59</td>
  <td width="80%"><input type="text" value="$result[min]" name="min" /></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%"><strong>$arr_ad_lng[1142]</strong><br />$arr_ad_lng[1149]</td>
  <td width="80%"><input type="text" value="$result[fname]" name="fname" /></td>
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
