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

$thisprog = "regipbans.php";

if ($useraccess != "1" || $admgroupdata[29] != "1") {
    adminlogin();
} 
if ($action != "process") {
    if (file_exists("datafile/regipbans.php")) {
        $bannedips = readfromfile("datafile/regipbans.php");
        $bannedips = str_replace("\n", "\r\n", $bannedips);
    } else $bannedips = "";
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[220]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[220]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

$table_start
		<strong>$arr_ad_lng[497]</strong>
$tab_stop
		$arr_ad_lng[498]
$table_start
		<strong>$arr_ad_lng[499]</strong>
$table_stop
			$arr_ad_lng[500]
$table_start
			<strong>$arr_ad_lng[501]</strong>
			
			$table_stop
			<center>
		<textarea cols=60 rows=6 name="wordarray">$bannedips</textarea></center><br />
		</td>
		</tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    $wordarray = str_replace("\n", "", $wordarray);
    $wordarray = str_replace("\r", "\n", $wordarray);
    writetofile("datafile/regipbans.php", $wordarray);
    $wordarray = str_replace("\n", "<br />", $wordarray);
    print <<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[220]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[502]</strong><br /><br />
		$tab_top
		<strong>$wordarray</strong><br />
		$tab_bottom
		<br /><br /><center><a href="admin.php?bmod=regipbans.php">$arr_ad_lng[503]</a></center>
		</td></tr></table></body></html>
EOT;
    exit;
} 
