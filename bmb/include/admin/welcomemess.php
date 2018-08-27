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

$thisprog = "welcomemess.php";

if ($useraccess != "1" || $admgroupdata[23] != "1") {
    adminlogin();
} 

if (file_exists('datafile/welcome.php')) require("datafile/welcome.php"); 
// Load welcome messages
print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[214]</strong>
		</td></tr>";
if ($action != "process") {
    // Load welcome messages

    $welcome = htmlspecialchars($welcome);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[214]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

$table_start
		<strong>$arr_ad_lng[419]</strong>
$table_stop
		$arr_ad_lng[504]
$table_start
	
		<strong>$arr_ad_lng[665]</strong>$table_stop
		<textarea cols=60 rows=6 name="ewelcome">$welcome</textarea></center>
$table_start
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    writetofile("datafile/welcome.php", '<?php  $welcome=\'' . str_replace("\n", "<br />", stripslashes($ewelcome)) . '\';');
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[848]</strong><br /><br />
		$tab_top
		<strong>$ewelcome</strong><br />
		$tab_bottom<p align=center><a href=admin.php?bmod=$thisprog>$arr_ad_lng[849]</A>
		</td></tr></table></body></html>
EOT;
    exit;
} 
