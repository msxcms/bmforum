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

$thisprog = "admingroup.php";

if ($useraccess != "1" || $admgroupdata[13] != "1") {
    adminlogin();
} 
$usertypelist = "";
$aaadata = $usergroupdata;

$query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
$result = bmbdb_query($query);
while (false !== ($line = bmbdb_fetch_array($result))) {
    $usergroupdata[$line['id']] = $line['adminsets'];
} 

list($enter_a, $enter_b, $enter_c, $enter_d, $enter_e, $enter_f, $enter_g, $enter_h, $enter_i, $enter_j, $enter_k, $enter_l, $enter_m, $enter_n, $enter_o, $enter_p, $enter_q, $enter_r, $enter_s, $enter_t, $enter_u, $enter_v, $enter_w, $enter_x, $enter_y, $enter_z, $enter_1a, $enter_2a, $enter_3a, $enter_4a, $enter_5a, $enter_6a, $enter_7a, $enter_8a, $enter_9a, $enter_1b, $enter_2b, $enter_3b, $enter_4b, $enter_5b, $enter_bc, $enter_task, $enter_api) = explode("|", $usergroupdata[$id]);
list($groupname) = explode("|", $aaadata[$id]);
if (!$action) {
    if ($enter_a) $open_enter_a = "checked='checked'";
    else $close_enter_a = "checked='checked'";
    if ($enter_b) $open_enter_b = "checked='checked'";
    else $close_enter_b = "checked='checked'";
    if ($enter_c) $open_enter_c = "checked='checked'";
    else $close_enter_c = "checked='checked'";
    if ($enter_d) $open_enter_d = "checked='checked'";
    else $close_enter_d = "checked='checked'";
    if ($enter_e) $open_enter_e = "checked='checked'";
    else $close_enter_e = "checked='checked'";
    if ($enter_f) $open_enter_f = "checked='checked'";
    else $close_enter_f = "checked='checked'";
    if ($enter_g) $open_enter_g = "checked='checked'";
    else $close_enter_g = "checked='checked'";
    if ($enter_h) $open_enter_h = "checked='checked'";
    else $close_enter_h = "checked='checked'";
    if ($enter_i) $open_enter_i = "checked='checked'";
    else $close_enter_i = "checked='checked'";
    if ($enter_j) $open_enter_j = "checked='checked'";
    else $close_enter_j = "checked='checked'";
    if ($enter_k) $open_enter_k = "checked='checked'";
    else $close_enter_k = "checked='checked'";
    if ($enter_l) $open_enter_l = "checked='checked'";
    else $close_enter_l = "checked='checked'";
    if ($enter_m) $open_enter_m = "checked='checked'";
    else $close_enter_m = "checked='checked'";
    if ($enter_n) $open_enter_n = "checked='checked'";
    else $close_enter_n = "checked='checked'";
    if ($enter_o) $open_enter_o = "checked='checked'";
    else $close_enter_o = "checked='checked'";
    if ($enter_p) $open_enter_p = "checked='checked'";
    else $close_enter_p = "checked='checked'";
    if ($enter_q) $open_enter_q = "checked='checked'";
    else $close_enter_q = "checked='checked'";
    if ($enter_r) $open_enter_r = "checked='checked'";
    else $close_enter_r = "checked='checked'";
    if ($enter_s) $open_enter_s = "checked='checked'";
    else $close_enter_s = "checked='checked'";
    if ($enter_t) $open_enter_t = "checked='checked'";
    else $close_enter_t = "checked='checked'";
    if ($enter_u) $open_enter_u = "checked='checked'";
    else $close_enter_u = "checked='checked'";
    if ($enter_v) $open_enter_v = "checked='checked'";
    else $close_enter_v = "checked='checked'";
    if ($enter_w) $open_enter_w = "checked='checked'";
    else $close_enter_w = "checked='checked'";
    if ($enter_x) $open_enter_x = "checked='checked'";
    else $close_enter_x = "checked='checked'";
    if ($enter_y) $open_enter_y = "checked='checked'";
    else $close_enter_y = "checked='checked'";
    if ($enter_z) $open_enter_z = "checked='checked'";
    else $close_enter_z = "checked='checked'";
    if ($enter_1a) $open_enter_1a = "checked='checked'";
    else $close_enter_1a = "checked='checked'";
    if ($enter_2a) $open_enter_2a = "checked='checked'";
    else $close_enter_2a = "checked='checked'";
    if ($enter_3a) $open_enter_3a = "checked='checked'";
    else $close_enter_3a = "checked='checked'";
    if ($enter_4a) $open_enter_4a = "checked='checked'";
    else $close_enter_4a = "checked='checked'";
    if ($enter_5a) $open_enter_5a = "checked='checked'";
    else $close_enter_5a = "checked='checked'";
    if ($enter_6a) $open_enter_6a = "checked='checked'";
    else $close_enter_6a = "checked='checked'";
    if ($enter_7a) $open_enter_7a = "checked='checked'";
    else $close_enter_7a = "checked='checked'";
    if ($enter_8a) $open_enter_8a = "checked='checked'";
    else $close_enter_8a = "checked='checked'";
    if ($enter_9a) $open_enter_9a = "checked='checked'";
    else $close_enter_9a = "checked='checked'";
    if ($enter_1b) $open_enter_1b = "checked='checked'";
    else $close_enter_1b = "checked='checked'";
    if ($enter_2b) $open_enter_2b = "checked='checked'";
    else $close_enter_2b = "checked='checked'";
    if ($enter_3b) $open_enter_3b = "checked='checked'";
    else $close_enter_3b = "checked='checked'";
    if ($enter_4b) $open_enter_4b = "checked='checked'";
    else $close_enter_4b = "checked='checked'";
    if ($enter_5b) $open_enter_5b = "checked='checked'";
    else $close_enter_5b = "checked='checked'";
    if ($enter_bc) $open_enter_bc = "checked='checked'";
    else $close_enter_bc = "checked='checked'";
    if ($enter_task) $open_enter_task = "checked='checked'";
    else $close_enter_task = "checked='checked'";
    if (!$enter_api) $open_enter_api = "checked='checked'";
    else $close_enter_api = "checked='checked'";

    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[233]</strong>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[234] [$groupname] <br />$arr_ad_lng[235]</strong>
		</td></tr>
$table_start
    <strong>$arr_ad_lng[182]</strong><form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="forumid" value="$forumid"><input type=hidden name="action" value="process"><input type=hidden name="id" value="$id">

   </td>
  </tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[183]</td>
	<td>
	<INPUT type=radio $open_enter_a value='1' name=setting[a1]> $arr_ad_lng[87] <INPUT $close_enter_a type=radio value='0' name=setting[a1]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[184]</td>
	<td>
	<INPUT type=radio $open_enter_b value='1' name=setting[a2]> $arr_ad_lng[87] <INPUT $close_enter_b type=radio value='0' name=setting[a2]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[194]/$arr_ad_lng[195]</td>
	<td>
	<INPUT type=radio $open_enter_i value='1' name=setting[a9]> $arr_ad_lng[87] <INPUT $close_enter_i type=radio value='0' name=setting[a9]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[213]</td>
	<td>
	<INPUT type=radio $open_enter_w value='1' name=setting[a23]> $arr_ad_lng[87] <INPUT $close_enter_w type=radio value='0' name=setting[a23]> $arr_ad_lng[88]
	</td>
</tr>
$table_start
	<strong>$arr_ad_lng[188] </strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[189]</td>
	<td>
	<INPUT type=radio $open_enter_d value='1' name=setting[a4]> $arr_ad_lng[87] <INPUT $close_enter_d type=radio value='0' name=setting[a4]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[190]</td>
	<td>
	<INPUT type=radio $open_enter_e value='1' name=setting[a5]> $arr_ad_lng[87] <INPUT $close_enter_e type=radio value='0' name=setting[a5]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[191]</td>
	<td>
	<INPUT type=radio $open_enter_f value='1' name=setting[a6]> $arr_ad_lng[87] <INPUT $close_enter_f type=radio value='0' name=setting[a6]> $arr_ad_lng[88]
	</td>
</tr>

<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[193]</td>
	<td>
	<INPUT type=radio $open_enter_h value='1' name=setting[a8]> $arr_ad_lng[87] <INPUT $close_enter_h type=radio value='0' name=setting[a8]> $arr_ad_lng[88]
	</td>
</tr>

$table_start
	<strong>$arr_ad_lng[196] </strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[197]</td>
	<td>
	<INPUT type=radio $open_enter_j value='1' name=setting[a10]> $arr_ad_lng[87] <INPUT $close_enter_j type=radio value='0' name=setting[a10]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[198]</td>
	<td>
	<INPUT type=radio $open_enter_k value='1' name=setting[a11]> $arr_ad_lng[87] <INPUT $close_enter_k type=radio value='0' name=setting[a11]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[214]</td>
	<td>
	<INPUT type=radio $open_enter_x value='1' name=setting[a24]> $arr_ad_lng[87] <INPUT $close_enter_x type=radio value='0' name=setting[a24]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[199]</td>
	<td>
	<INPUT type=radio $open_enter_l value='1' name=setting[a12]> $arr_ad_lng[87] <INPUT $close_enter_l type=radio value='0' name=setting[a12]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[200]</td>
	<td>
	<INPUT type=radio $open_enter_m value='1' name=setting[a13]> $arr_ad_lng[87] <INPUT $close_enter_m type=radio value='0' name=setting[a13]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[201]</td>
	<td>
	<INPUT type=radio $open_enter_n value='1' name=setting[a14]> $arr_ad_lng[87] <INPUT $close_enter_n type=radio value='0' name=setting[a14]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[202]</td>
	<td>
	<INPUT type=radio $open_enter_o value='1' name=setting[a15]> $arr_ad_lng[87] <INPUT $close_enter_o type=radio value='0' name=setting[a15]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[1107]</td>
	<td>
	<INPUT type=radio $open_enter_4b value='1' name=setting[a39]> $arr_ad_lng[87] <INPUT $close_enter_4b type=radio value='0' name=setting[a39]> $arr_ad_lng[88]
	</td>
</tr>
$table_start
	<strong>$arr_ad_lng[203]</strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[185]</td>
	<td>
	<INPUT type=radio $open_enter_c value='1' name=setting[a3]> $arr_ad_lng[87] <INPUT $close_enter_c type=radio value='0' name=setting[a3]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[204]</td>
	<td>
	<INPUT type=radio $open_enter_p value='1' name=setting[a16]> $arr_ad_lng[87] <INPUT $close_enter_p type=radio value='0' name=setting[a16]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[205]</td>
	<td>
	<INPUT type=radio $open_enter_q value='1' name=setting[a17]> $arr_ad_lng[87] <INPUT $close_enter_q type=radio value='0' name=setting[a17]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[206]</td>
	<td>
	<INPUT type=radio $open_enter_r value='1' name=setting[a18]> $arr_ad_lng[87] <INPUT $close_enter_r type=radio value='0' name=setting[a18]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[207]</td>
	<td>
	<INPUT type=radio $open_enter_s value='1' name=setting[a19]> $arr_ad_lng[87] <INPUT $close_enter_s type=radio value='0' name=setting[a19]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[208]/$arr_ad_lng[209]</td>
	<td>
	<INPUT type=radio $open_enter_t value='1' name=setting[a20]> $arr_ad_lng[87] <INPUT $close_enter_t type=radio value='0' name=setting[a20]> $arr_ad_lng[88]
	</td>
</tr>
$table_start
	<strong>$arr_ad_lng[210]</strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[211]</td>
	<td>
	<INPUT type=radio $open_enter_u value='1' name=setting[a21]> $arr_ad_lng[87] <INPUT $close_enter_u type=radio value='0' name=setting[a21]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[212]</td>
	<td>
	<INPUT type=radio $open_enter_v value='1' name=setting[a22]> $arr_ad_lng[87] <INPUT $close_enter_v type=radio value='0' name=setting[a22]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[192]</td>
	<td>
	<INPUT type=radio $open_enter_g value='1' name=setting[a7]> $arr_ad_lng[87] <INPUT $close_enter_g type=radio value='0' name=setting[a7]> $arr_ad_lng[88]
	</td>
</tr>


$table_start
	<strong>$arr_ad_lng[215]</strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[216]</td>
	<td>
	<INPUT type=radio $open_enter_y value='1' name=setting[a25]> $arr_ad_lng[87] <INPUT $close_enter_y type=radio value='0' name=setting[a25]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[217]</td>
	<td>
	<INPUT type=radio $open_enter_1a value='1' name=setting[a27]> $arr_ad_lng[87] <INPUT $close_enter_1a type=radio value='0' name=setting[a27]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[218]</td>
	<td>
	<INPUT type=radio $open_enter_2a value='1' name=setting[a28]> $arr_ad_lng[87] <INPUT $close_enter_2a type=radio value='0' name=setting[a28]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[219]</td>
	<td>
	<INPUT type=radio $open_enter_3a value='1' name=setting[a29]> $arr_ad_lng[87] <INPUT $close_enter_3a type=radio value='0' name=setting[a29]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[220]</td>
	<td>
	<INPUT type=radio $open_enter_4a value='1' name=setting[a30]> $arr_ad_lng[87] <INPUT $close_enter_4a type=radio value='0' name=setting[a30]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[221]</td>
	<td>
	<INPUT type=radio $open_enter_5a value='1' name=setting[a31]> $arr_ad_lng[87] <INPUT $close_enter_5a type=radio value='0' name=setting[a31]> $arr_ad_lng[88]
	</td>
</tr>
$table_start
	<strong>$arr_ad_lng[222]</strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[1157]</td>
	<td>
	<INPUT type=radio $open_enter_bc value='1' name=setting[bc]> $arr_ad_lng[87] <INPUT $close_enter_6a type=radio value='0' name=setting[bc]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[223]</td>
	<td>
	<INPUT type=radio $open_enter_6a value='1' name=setting[a32]> $arr_ad_lng[87] <INPUT $close_enter_6a type=radio value='0' name=setting[a32]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[224]</td>
	<td>
	<INPUT type=radio $open_enter_7a value='1' name=setting[a33]> $arr_ad_lng[87] <INPUT $close_enter_7a type=radio value='0' name=setting[a33]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[225]</td>
	<td>
	<INPUT type=radio $open_enter_8a value='1' name=setting[a34]> $arr_ad_lng[87] <INPUT $close_enter_8a type=radio value='0' name=setting[a34]> $arr_ad_lng[88]
	</td>
</tr>
$table_start
	<strong>$arr_ad_lng[226]</strong>
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[227]</td>
	<td>
	<INPUT type=radio $open_enter_9a value='1' name=setting[a35]> $arr_ad_lng[87] <INPUT $close_enter_9a type=radio value='0' name=setting[a35]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[228]</td>
	<td>
	<INPUT type=radio $open_enter_1b value='1' name=setting[a36]> $arr_ad_lng[87] <INPUT $close_enter_1b type=radio value='0' name=setting[a36]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[229]</td>
	<td>
	<INPUT type=radio $open_enter_2b value='1' name=setting[a37]> $arr_ad_lng[87] <INPUT $close_enter_2b type=radio value='0' name=setting[a37]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[230]</td>
	<td>
	<INPUT type=radio $open_enter_3b value='1' name=setting[a38]> $arr_ad_lng[87] <INPUT $close_enter_3b type=radio value='0' name=setting[a38]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[1130]</td>
	<td>
	<INPUT type=radio $open_enter_task value='1' name=setting[task]> $arr_ad_lng[87] <INPUT $close_enter_3b type=radio value='0' name=setting[task]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[1225]</td>
	<td>
	<INPUT type=radio $open_enter_5b value='0' name=setting[a40]> $arr_ad_lng[87] <INPUT $close_enter_5b type=radio value='1' name=setting[a40]> $arr_ad_lng[88]
	</td>
</tr>
<tr bgcolor=F9FAFE>
	<td width="50%">$arr_ad_lng[231]</td>
	<td>
	<INPUT type=radio $open_enter_api value='1' name=setting[a40]> $arr_ad_lng[87] <INPUT $close_enter_api type=radio value='0' name=setting[a40]> $arr_ad_lng[88]
	</td>
</tr>
	$table_start<input type=submit value="$arr_ad_lng[66]"> <input type=reset value="$arr_ad_lng[178]">
  </tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process" || $action == "delete") {
    $new = $setting[a1] . "|" . $setting[a2] . "|" . $setting[a3] . "|" . $setting[a4] . "|" . $setting[a5] . "|" . $setting[a6] . "|" . $setting[a7] . "|" . $setting[a8] . "|" . $setting[a9] . "|" . $setting[a10] . "|" . $setting[a11] . "|" . $setting[a12] . "|" . $setting[a13] . "|" . $setting[a14] . "|" . $setting[a15] . "|" . $setting[a16] . "|" . $setting[a17] . "|" . $setting[a18] . "|" . $setting[a19] . "|" . $setting[a20] . "|" . $setting[a21] . "|" . $setting[a22] . "|" . $setting[a23] . "|" . $setting[a24] . "|" . $setting[a25] . "|" . $setting[a26] . "|" . $setting[a27] . "|" . $setting[a28] . "|" . $setting[a29] . "|" . $setting[a30] . "|" . $setting[a31] . "|" . $setting[a32] . "|" . $setting[a33] . "|" . $setting[a34] . "|" . $setting[a35] . "|" . $setting[a36] . "|" . $setting[a37] . "|" . $setting[a38] . "|" . $setting[a39] . "|" . $setting[a40] . "|" . $setting[bc] . "|" . $setting[task] . "|";
    $nquery = "UPDATE {$database_up}usergroup SET adminsets = '$new' WHERE id = '$id'";
    $result = bmbdb_query($nquery);

    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center>
		</td></tr>	<tr>
	<td bgcolor=#F9FAFE valign=middle colspan=2>
	<strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr></table></body></html>

			</table></body></html>
EOT;
    exit;
} 
