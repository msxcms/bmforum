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

$thisprog = "setact.php";

if ($useraccess != "1" || $admgroupdata[31] != "1") {
    adminlogin();
} 

if ($action != "process") { // Start Page
    if (file_exists("datafile/actinfo.php")) $echobadwords = readfromfile("datafile/actinfo.php"); 
    // Actions Data Loaded
    $echobadwords = htmlspecialchars($echobadwords);
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[506]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[223]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>
               

               
$table_start
  <strong>$arr_ad_lng[499]</strong>
$table_stop
$arr_ad_lng[507]

$table_start
	<strong>$arr_ad_lng[508]</strong>$table_stop
<center>
  <textarea cols=80 rows=8 wrap="virtual" name="wordarray">$echobadwords</textarea>
</center>
  </td>
  </tr>
                
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <input type=submit name=submit value="$arr_ad_lng[66]"></td></tr></table></td></tr></table>
  </td></tr></table></body></html></form>
EOT;
    exit;
} elseif ($action == "process") { // Save Data
    writetofile("datafile/actinfo.php", stripslashes($wordarray));
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[506]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#333333><center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[509]</strong><br /><br />
		$tab_top
EOT;
    // Load Data and Preview
    $filedata = file("datafile/actinfo.php");
    $count = count($filedata);
    for ($i = 0;$i < $count;$i++) {
        list($act, $actinfo) = explode("|", $filedata[$i]);
        print ("$arr_ad_lng[510] {$act} $arr_ad_lng[511] <font color=red>$arr_ad_lng[512]</font> $arr_ad_lng[513] {$actinfo}<br />");
    } 

    print "
	$tab_bottom
	<br /><br /><br /><center><strong><a href=admin.php?bmod=setact.php>$arr_ad_lng[514]</a></strong></center>
	</td></tr></table></body></html>
	";

    exit;
} 
