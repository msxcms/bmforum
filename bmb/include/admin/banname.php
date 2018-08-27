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

$thisprog = "banname.php";
$bannamefile = "datafile/bannames.php";

if ($useraccess != "1" || $admgroupdata[26] != "1") {
    adminlogin();
} 
if ($action != "process") {
    if (file_exists($bannamefile)) {
        include($bannamefile);
        $bannames = implode("\r\n", $bannames);
    } else $bannames = "";
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[247]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[248]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

$table_start
					<strong>$arr_ad_lng[249]</strong>
$table_stop
		$arr_ad_lng[250]
$table_start
		<strong>$arr_ad_lng[251]</strong>
$table_stop
	<center>
		<textarea cols=60 rows=6 name="userarray">$bannames</textarea></center><br />
		</td>
		</tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    $bannames = "<?php\n";
    $userarray = str_replace("\n", "", $userarray);
    $userarray = explode("\r", $userarray);
    $count = count($userarray);
    for ($i = 0; $i < $count; $i++) {
        $bannames .= "\$bannames[$i]='$userarray[$i]';\n";
    } 
    writetofile($bannamefile, $bannames);

    $userarray = implode("<br />", $userarray);
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[247]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#333333><center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[252]</strong><br /><br />
		$tab_top
		<strong>$userarray</strong><br />
		$tab_bottom
		<br /><br /><center><a href="admin.php?bmod=banname.php">$arr_ad_lng[253]</a></center>
		</td></tr></table></body></html>
EOT;
    exit;
} 
