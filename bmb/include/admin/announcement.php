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

$thisprog = "announcement.php";

if ($useraccess != "1" || $admgroupdata[8] != "1") {
    adminlogin();
} 
if (file_exists('datafile/announcement.php')) require("datafile/announcement.php");

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[242]</strong>
		</td></tr>";
if ($action != "process") {
    
    $announcement = htmlspecialchars($announcement);
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[243]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#000000>

		<strong>$arr_ad_lng[244]</strong><br /><br /><center>
		<textarea cols=60 rows=6 name="eannouncement">$announcement</textarea></center><br />
		</td>
		</tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    writetofile("datafile/announcement.php", '<?php  $announcement=\'' . str_replace("\n", " ", str_replace("\r", " ", str_replace("'", "\'", stripslashes($eannouncement))) . '\';'));
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[245]</strong></center><br /><br />
		<strong>$arr_ad_lng[246]</strong><br /><br />
		$tab_top
		<strong>$eannouncement</strong><br />
		$tab_bottom
			<br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
} 
