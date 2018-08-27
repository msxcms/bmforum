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

$thisprog = "loginfo.php";

if ($useraccess != "1" || $admgroupdata[36] != "1") {
    adminlogin();
} 

if ($action == process) {
    $query = "TRUNCATE TABLE {$database_up}apclog";
    $result = bmbdb_query($query);
    $query = "INSERT into {$database_up}apclog (adminid,adminpwd,actionstatus,adminip,admintime,addtime) values ('$bmbadminid','$arr_ad_lng[32]','$arr_ad_lng[844]','$ip','$current_time','$timestamp')";
    $result = bmbdb_query($query);
    print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[229]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
		<font color=#333333><strong>$arr_ad_lng[845]</strong>
		</td></tr><tr bgcolor=#F9FAFE><td>$arr_ad_lng[846]<br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a></tr>";
    exit;
} 
print "    <tr><td bgcolor=#14568A valign=middle align=center colspan=5><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[229]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=5>
		<font color=#333333><strong>$arr_ad_lng[229]</strong>
		</td></tr>
		<tr bgcolor=#6DA6D1>$arr_ad_lng[847]</tr>
";

if (empty($page)) $page = 0;

$count = $page;
$query = "SELECT COUNT(*) FROM {$database_up}apclog";
$result = bmbdb_query($query);
$fcount = bmbdb_fetch_array($result);
$fcount = $fcount['COUNT(*)']-1;

$query = "SELECT * FROM {$database_up}apclog ORDER BY addtime DESC LIMIT $count,10";
$result = bmbdb_query($query);

while (false !== ($line = bmbdb_fetch_array($result))) {
    print "<tr bgcolor=#F9FAFE><td>{$line['adminid']}</td><td>{$line['adminpwd']}</td><td>{$line['adminip']}</td><td>{$line['actionstatus']}</td><td>{$line['admintime']}</td></tr>";
} 

$n = floor($fcount / 10) + 1;

for($i = 0; $i < $n; $i++) {
    $ia = $i * 10;
    $ib = $i + 1;
    $pagen .= "<a href=admin.php?bmod=$thisprog&page=$ia>$ib</a> ";
} 

print "
                <tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6>$arr_ad_lng[494] [ {$pagen}] $arr_ad_lng[495] [$arr_ad_lng[496] $n $arr_ad_lng[495]]<br />
		<font color=#333333><strong><a href=admin.php?bmod=$thisprog&action=process>$arr_ad_lng[845]</a></strong>
		</td></tr>
                </td></tr></table>
    
";

