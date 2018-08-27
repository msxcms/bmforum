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

$thisprog = "potloginfo.php";

if ($useraccess != "1" || $admgroupdata[37] != "1") {
    adminlogin();
} 
if ($action == process) {
    $query = "TRUNCATE TABLE {$database_up}potlog";
    $result = bmbdb_query($query);
    print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[230]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
		<font color=#333333><strong>$arr_ad_lng[491]</strong>
		</td></tr><tr bgcolor=#F9FAFE><td>$arr_ad_lng[492]<br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a>
</tr>";
    exit;
} 
print "    <tr><td bgcolor=#14568A valign=middle align=center colspan=6><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[230]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6>
		<font color=#333333><strong>$arr_ad_lng[230]</strong>
		</td></tr>
		<tr bgcolor=#6DA6D1>$arr_ad_lng[493]</tr>
";

if (empty($page)) $page = 0;

$count = $page;

$query = "SELECT COUNT(*) FROM {$database_up}potlog";
$result = bmbdb_query($query);
$fcount = bmbdb_fetch_array($result);
$fcount = $fcount['COUNT(*)']-1;

$query = "SELECT * FROM {$database_up}potlog ORDER BY ptimestamp DESC LIMIT $count,10";
$result = bmbdb_query($query);
while (false !== ($line = bmbdb_fetch_array($result))) {
    $timeinfo = get_date($line['ptimestamp']) . " " . get_time($line['ptimestamp']);
    print "<tr bgcolor=#F9FAFE><td>$line[pusername]</td><td>$line[pauthor]</td><td>$line[bymchange]</td><td><a href=forums.php?forumid=$line[pforumid]>进入($line[pforumid])</a></td><td><a href='topic.php?filename=$line[pfilename]'>$line[pfilename]($line[particle])</a></td><td>$timeinfo</td></tr>";
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
		<font color=#333333><strong><a href=admin.php?bmod=$thisprog&action=process>$arr_ad_lng[491]</a></strong>
		</td></tr>
                </td></tr></table>
    
";

