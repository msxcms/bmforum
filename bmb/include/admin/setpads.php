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

$thisprog = "setpads.php";

if ($useraccess != "1" || $admgroupdata[33] != "1") {
    adminlogin();
} 

if (file_exists('datafile/postads.php')) $postads = readfromfile("datafile/postads.php");
// Posts Adv. Loaded
print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[225]</strong>
		</td></tr>";
if ($action != "process") {
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[225]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

$table_start
		<strong>$arr_ad_lng[499]</strong>
		
$table_stop
	$arr_ad_lng[664]
$table_start
		<strong>$arr_ad_lng[665]</strong>
$table_stop 
		<textarea cols=120 rows=20 name="epostads">$postads</textarea><br />
		</td>
		</tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") { // Save
    $epostads = stripslashes($epostads);
    writetofile("datafile/postads.php", $epostads);
    $epostads = str_replace("\n", "<br />", $epostads);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[666]</strong><br /><br />
		$tab_top
		<strong>$epostads</strong><br />
		$tab_bottom<p align=center><a href=admin.php?bmod=$thisprog>$arr_ad_lng[667]</A>
		</td></tr></table></body></html>
EOT;
    exit;
} 
