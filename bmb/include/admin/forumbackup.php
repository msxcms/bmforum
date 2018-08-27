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

$thisprog = "forumbackup.php";
$forumfile = "datafile/forumdata.php";

if ($useraccess != "1" || $admgroupdata[17] != "1") {
    adminlogin();
} 
@set_time_limit(300);

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
            <strong>$arr_ad_lng[320] $arr_ad_lng[342]</strong>
            </td></tr>";

if (file_exists($forumfile)) {
    $forumdata = file($forumfile);
    $count = count($forumdata);
} 

if (empty($action)) {
    $forumonly = "";
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] != "category") $forumonly .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
    } 
    $forumonly .= "</select>";

    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[261]</strong>
    </td>
    </tr>          
    <tr>
    <td bgcolor=#F9FAFE colspan=2>
    $arr_ad_lng[343]
$table_start<strong>$arr_ad_lng[344]</strong>
$table_stop	
	<form method="post" style="margin:0px;" action="admin.php?bmod=dumpforum.php">

    $arr_ad_lng[345] <select name="id">$forumonly <input type=submit value="$arr_ad_lng[66]">

    </form>
$table_start
		<strong>$arr_ad_lng[346]</strong>
<form method="post" style="margin:0px;" action="admin.php?bmod=$thisprog" enctype="multipart/form-data"><input type=hidden name="action" value="restore">
$table_stop	
    $arr_ad_lng[347]<br />
<input type="file" name="forum_file"> $arr_ad_lng[348]<select name="target">$forumonly <input type="submit" value="$arr_ad_lng[66]"><br />
    $arr_ad_lng[349]
    $tab_bottom
    </form>
    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} else {
    $check = 1;
    $forum_file = $_FILES['forum_file']['name'];
    if (empty($_FILES['forum_file']['tmp_name'])) {
        $check = 0;
        $reason = "$arr_ad_lng[350]";
    } 

    $forum_copy_names = "tmp/tmpfile$timestamp.bmb";
    $forum_copy = "tmp/tmpfile$timestamp.bmb";
	attach_upload($_FILES['forum_file']['tmp_name'], $forum_copy_names, $_FILES['forum_file']['size']);
	
    if ($check == 1) {
        $user_file = file($forum_copy_names);
        $count = count($user_file);
        $xcount = $count-7;
        for($i = 7;$i < $count;$i++) {
       		$result = bmbdb_query($user_file[$i], 1);
        } 

        print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE align=center colspan=2>
		<strong>$arr_ad_lng[352]</strong><br /><br />
		$tab_top
		$arr_ad_lng[353] <strong>$xcount</strong> <br /><br />
		$arr_ad_lng[354]<br />
		&gt;&gt; <a href="admin.php?bmod=forumfix.php&action=updatecount&target=$target">$arr_ad_lng[358]</a>
		$tab_bottom
		</td></tr>
		</td></tr></table></body></html>
EOT;
        @unlink($forum_copy_names);
        exit;
    } else {
        print <<<EOT
	<tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[359]</strong><br /><br />
	$tab_top
        $arr_ad_lng[360]<br /><br />
        $reason <br /><br /> 
        &gt;&gt; <a href="javascript:history.go(-1)">$arr_ad_lng[361]</a>
        $tab_bottom 
        </td></tr>
    </td></tr></table></body></html>
EOT;
        @unlink($forum_copy_names);
        exit;
    } 
} 
function attach_upload($attach, $source, $attach_size)
{
    if (@copy($attach, $source)) {
        $attach_saved = true;
    } elseif (function_exists("move_uploaded_file")) {
        if (@move_uploaded_file($attach, $source)) {
            $attach_saved = true;
        } 
    } 

    if (!$attach_saved && is_readable($attach)) {
        @$fp = fopen($attach, "rb");
        @flock($fp, 2);
        @$attachedfile = fread($fp, $attach_size);
        @fclose($fp);

        @$fp = fopen($source, "wb");
        @flock($fp, 3);
        if (@fwrite($fp, $attachedfile)) {
            $attach_saved = true;
        } 
        @fclose($fp);
    } 
} 