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

$thisprog = "regtips.php";

if ($useraccess != "1" || $admgroupdata[10] != "1") {
    adminlogin();
} 
if (file_exists('datafile/reginfo.php')) require("datafile/reginfo.php");

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[198]</strong>
		</td></tr>";
if ($action != "process") {
    
    $reginfo = htmlspecialchars($reginfo);
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[198]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#000000>

		<strong>$arr_ad_lng[504]</strong><br /><br /><center>
		<textarea cols=60 rows=6  name="ereginfo">$reginfo</textarea></center><br />
		</td>
		</tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    writetofile("datafile/reginfo.php", '<?php  $reginfo=\'' . str_replace("'", "\'", stripslashes($ereginfo)) . '\';');

    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center>$arr_ad_lng[505]<br /><br />
		$tab_top
		<strong>$ereginfo</strong><br />
		$tab_bottom
		</td></tr></table></body></html>
EOT;
    exit;
} 
