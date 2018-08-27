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

$thisprog = "userbackup.php";

if ($useraccess != "1" || $admgroupdata[16] != "1") {
    adminlogin();
} 

@set_time_limit(300);
print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
            <strong>$arr_ad_lng[320] $arr_ad_lng[205]</strong>
            </td></tr>";

if (empty($action)) {
    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[261]</strong>
    </td>
    </tr>          
                
    <tr>
    <td bgcolor=#F9FAFE colspan=2>
    $arr_ad_lng[343]
$table_start
    <strong><a href="admin.php?bmod=dumpuser.php" style='color:#FFFFFF;'>$arr_ad_lng[808]</a></strong>
    $table_stop
    $arr_ad_lng[809]<br />
    <br />&gt;&gt;<a href="admin.php?bmod=dumpuser.php">$arr_ad_lng[810]</a>
$table_start
    <strong>$arr_ad_lng[811]</strong>$table_stop
<form method="post" action="admin.php?bmod=$thisprog&action=input" enctype="multipart/form-data" style="margin: 0px;">    

$arr_ad_lng[812]
    </form>
    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} else {
    // Load Backup File
    if ($user_file_type == "/userbak/" || $user_file_type == "") {
        $user_file_tmp = $_FILES['user_file']['tmp_name'];
        $user_file_name = $_FILES['user_file']['name'];
    } else {
        $user_file_tmp = $user_file_type;
        $user_file_name = $user_file_type;
    } 
    $check = 1; 
    // Load SQL File
    if (empty($_FILES['user_file']['tmp_name']) || $user_file == "none") {
        $check = 0;
        $reason = "$arr_ad_lng[813]";
    } 
    if ($user_file_type == "/userbak/" || $user_file_type == "") {
        $user_file_names = "tmp/tmpfile$timestamp.bmb.sql";
        attach_upload($_FILES['user_file']['tmp_name'], $user_file_names, $_FILES['user_file']['size']);
    } 

    if ($check == 1) { // Pass the check
        $user_file = file($user_file_names);
        $count = count($user_file);
        $xcount = $count-7;
        for($i = 7;$i < $count;$i++) { // the top 7 line is comment
        	if ($cover == "no") {
        		$result = bmbdb_query($user_file[$i], 1);
        	} else {
        		$result = bmbdb_query(str_replace("\nINSERT INTO", "\nREPLACE INTO", $user_file[$i]), 1);
        	}
        } 
        print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE align=center colspan=2>
		<strong>$arr_ad_lng[815]</strong><br /><br />
		$tab_top
		<br /><br />
		$arr_ad_lng[816] <strong>$xcount</strong> <br />
		$tab_bottom
		</td></tr>
		</td></tr></table></body></html>
EOT;
        @unlink($user_file_names);
        exit;
    } else {
        print <<<EOT
	<tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[819]</strong><br /><br />
	$tab_top
        $arr_ad_lng[820]<br /><br />
        $reason <br /><br /> 
        &gt;&gt; <a href="javascript:history.go(-1)">$arr_ad_lng[361]</a>
        $tab_bottom 
        </td></tr>
    </td></tr></table></body></html>
EOT;
        @unlink($user_file_names);
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