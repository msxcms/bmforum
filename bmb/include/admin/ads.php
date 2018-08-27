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

$thisprog = "ads.php";

if ($useraccess != "1" || $admgroupdata[1] != "1") {
    adminlogin();
} 
$announcement_file = "datafile/ads.php";
$ads2_file = "datafile/ads2.php";
$topfile = "datafile/topads.php";
$row_file = "datafile/row_text.php";

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[236]</strong>
		</td></tr>";
if ($action != "process") {
    if (file_exists("datafile/ipbans.php")) {
        $bannedips = readfromfile("datafile/ipbans.php");
        $bannedips = str_replace("\n", "\r\n", $bannedips);
    } else $bannedips = "";

    if (file_exists($announcement_file)) {
        include($announcement_file);
        $ads = $ads; 
        // $count=count($userarray);
    } else $ads = "";
    if (file_exists($topfile)) {
        include($topfile);
        $topads = $topads; 
        // $count=count($userarray);
    } else $topads = "";
    if (file_exists($ads2_file)) {
        include($ads2_file);
        $ads2 = $ads2; 
        // $count=count($userarray);
    } else $ads2 = "";
    
    if (@file_exists($row_file)) {
        @include($row_file);
        if ($row_show[showindex]) $javascript .= "<script type='text/javascript'>document.rowform.showindex.checked='checked';</script>";
        if ($row_show[showforum]) $javascript .= "<script type='text/javascript'>document.rowform.showforum.checked='checked';</script>";
        if ($row_show[showtopic]) $javascript .= "<script type='text/javascript'>document.rowform.showtopic.checked='checked';</script>";
    } 
    
    $ads_row = htmlspecialchars($ads_row);
    $ads = htmlspecialchars($ads);
    $ads2 = htmlspecialchars($ads2);
    $topads = htmlspecialchars($topads);

    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[237]</strong>		<form name='rowform' action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		<input type=hidden name="do" value="rowads">
		</td></tr>

$table_start
		$arr_ad_lng[960]
$table_stop
		<textarea cols=100 rows=15 name="announcement">$ads_row</textarea><br />$arr_ad_lng[961]<br />
	     $arr_ad_lng[962] <input type=text name='every_ads' value='$ads_every' size=5><br />

		<input type=submit value="$arr_ad_lng[66]"></td></form></tr>

		<form action="admin.php?bmod=$thisprog" method="post">
		<input type=hidden name="action" value="process">
		<input type=hidden name="do" value="ads11">
$table_start

		$arr_ad_lng[238]
$table_stop

		<textarea cols=100 rows=15 name="announcement">$ads</textarea><br />
		<input type=submit value="$arr_ad_lng[66]"></td></form></tr>
		<form action="admin.php?bmod=$thisprog" method="post">
		<input type=hidden name="action" value="process">
		<input type=hidden name="do" value="ads22">
$table_start
		$arr_ad_lng[239]
$table_stop			

		<textarea cols=100 rows=15 name="ads2">$ads2</textarea><br />

		<input type=submit value="$arr_ad_lng[66]"></td></form></tr>	

		<form action="admin.php?bmod=$thisprog" method="post">
		<input type=hidden name="action" value="process">
		<input type=hidden name="do" value="top">
$table_start
		$arr_ad_lng[240]
$table_stop				
		<textarea cols=100 rows=15 name="topads">$topads</textarea><br />

		<input type=submit value="$arr_ad_lng[66]"></td></form></tr>	

		<form action="admin.php?bmod=$thisprog" method="post">
		<input type=hidden name="action" value="process">
		<input type=hidden name="do" value="sm">
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#000000>

$javascript

		</table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    if ($do == "top") {
        writetofile("datafile/topads.php", '<?php 	$topads=\'' . str_replace("'", "\'", stripslashes($topads)) . '\';');
    } elseif ($do == "ads22") {
        writetofile("datafile/ads2.php", '<?php 	$ads2=\'' . str_replace("'", "\'", stripslashes($ads2)) . '\';');
    } elseif ($do == "ads11") {
        writetofile("datafile/ads.php", '<?php  $ads=\'' . str_replace("'", "\'", stripslashes($announcement)) . '\';');
    } elseif ($do == "rowads") {
    	// Row Text Ads
    	$row_new_text = row_ads_maker($announcement, $every_ads);
        
        writetofile ($row_file, $row_new_text);
    }
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[241]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a>
		
		</td></tr></table></body></html>
EOT;
    exit;
} 
