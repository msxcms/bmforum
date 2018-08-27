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

$thisprog = "attachment.php";

if ($useraccess != "1" || $admgroupdata[18] != "1") {
    adminlogin();
} 

@set_time_limit(300);
if ($action == "delone") { // Delete attachments by date
    if (!$step) {
        print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
		<font color=#333333><strong>$arr_ad_lng[390]</strong>
		</td></tr><tr align=center bgcolor=#F9FAFE><td><a href='admin.php?bmod=$thisprog&action=delone&step=2&sfilename=$sfilename&type=$type&month=$month'>$arr_ad_lng[391]</a></tr>";
        exit;
    } else {
        if ($type == "bymonth") {
            if ($month != "other") {
                $dh = opendir("upload/$month");
                while (false !== ($userskin = readdir($dh))) {
                    if (($userskin != ".") && ($userskin != "..")) {
                        @unlink("upload/$month/" . $userskin);
                    } 
                } 
                closedir($dh);
                @rmdir("upload/$month");
            } else {
                $dh = opendir("upload");
                while (false !== ($userskin = readdir($dh))) {
                    if (($userskin != ".") && ($userskin != "..") && !is_dir("upload/$userskin")) {
                        @unlink("upload/" . $userskin);
                    } 
                } 
                closedir($dh);
            } 
        } else {
            $coucc = count($xdelete);

            for($cc = 0;$cc < $coucc;$cc++) {
                $info = unlink("upload/{$xdelete[$cc]}");
            } 
        } 
        $showdone = "$arr_ad_lng[392]";

        print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
	    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
	    </td></tr>	<tr>
			<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
			<font color=#333333><strong>$arr_ad_lng[390]</strong>
			</td></tr><tr bgcolor=#F9FAFE><td>$showdone<br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=attachment.php\">$arr_ad_lng[76]</a></tr>";
        exit;
    } 
} 

if ($action == "process") { // Delete Attachment(s)
    if (!$step) {
        print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
		<font color=#333333><strong>$arr_ad_lng[393]</strong>
		</td></tr><tr align=center bgcolor=#F9FAFE><td><a href='admin.php?bmod=$thisprog&action=process&step=2'>$arr_ad_lng[391]</a></tr>";
        exit;
    } else {
        $dh = opendir("upload/");
        while (false !== ($userskin = readdir($dh))) {
            if (($userskin != ".") && ($userskin != "..")) {
                if (is_dir("upload/" . $userskin)) {
                    $dbmonth = opendir("upload/" . $userskin);
                    while (false !== ($newuserskin = readdir($dbmonth))) {
                        if (($newuserskin != ".") && ($newuserskin != "..")) {
                            $user_skin[] = $userskin . "/" . $newuserskin;
                        } 
                    } 
                    $dir_lists[] = $userskin;
                    closedir($dbmonth);
                } else {
                    $user_skin[] = $userskin;
                } 
            } 
        } 
        closedir($dh);

        $count = count($user_skin);

        for ($i = 0; $i < $count; $i++) {
            @unlink("upload/$user_skin[$i]");
        } 

        $count = count($dir_lists);

        for ($i = 0; $i < $count; $i++) {
            @rmdir("upload/$dir_lists[$i]");
        } 

        print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
	    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
	    </td></tr>	<tr>
			<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
			<font color=#333333><strong>$arr_ad_lng[393]</strong>
			</td></tr><tr bgcolor=#F9FAFE><td>$arr_ad_lng[394]<br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=attachment.php\">$arr_ad_lng[76]</a></tr>";
        exit;
    } 
} 
// No Delete req.
echo<<<EOT
<script language="JavaScript">
function clearam(){
if(confirm("$arr_ad_lng[395]", "$arr_ad_lng[395]")){
delones.submit();
}
}
function CheckAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
e.checked = true;
}
}
function FanAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
if (e.checked == true){ e.checked = false; }
else { e.checked = true;}
}}
</script>
EOT;

if (!$choose) {
    if ($saveattbyym == 1) $yesno = $arr_ad_lng[153];
    else $yesno = $arr_ad_lng[154]; // Show the mode
    print "    <tr><td bgcolor=#14568A valign=middle align=center colspan=5><font color=#F9FAFE>
	    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
	    </td></tr>	<tr>
			<td bgcolor=#F9FAFE valign=middle align=center colspan=5>
			<font color=#333333><strong>$arr_ad_lng[396]($arr_ad_lng[397] $yesno)</strong>
			</td></tr>
			<tr bgcolor=#F9FAFE><td><ul><li><a href=admin.php?bmod=attachment.php&choose=month>$arr_ad_lng[398]</a></li><li><a href=admin.php?bmod=attachment.php&choose=all>$arr_ad_lng[399]</a></li></ul></td></tr>
	";
} elseif ($choose == "all") {
    print "    <tr><td bgcolor=#14568A valign=middle align=center colspan=5><font color=#F9FAFE>
	    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
	    </td></tr><form action=admin.php?bmod=attachment.php&action=delone&step=2 method=POST>	<tr>
			<td bgcolor=#F9FAFE valign=middle align=center colspan=5>
			<font color=#333333><strong>$arr_ad_lng[400]</strong>
			</td></tr>
			<tr bgcolor=#F9FAFE>$arr_ad_lng[402]</tr>
	";
    $dh = opendir("upload/");
    while (false !== ($userskin = readdir($dh))) {
        if (($userskin != ".") && ($userskin != "..")) {
            if (is_dir("upload/" . $userskin)) {
                $dbmonth = opendir("upload/" . $userskin);
                while (false !== ($newuserskin = readdir($dbmonth))) {
                    if (($newuserskin != ".") && ($newuserskin != "..")) {
                        $user_skin[] = $userskin . "/" . $newuserskin;
                    } 
                } 
                closedir($dbmonth);
            } else {
                $user_skin[] = $userskin;
            } 
        } 
    } 
    closedir($dh);

    $count = count($user_skin);

    for ($i = 0; $i < $count; $i++) {
        $sfilename = "upload/$user_skin[$i]";
        $filesize = filesize($sfilename);
        $leijisize = $leijisize + $filesize;
        $filemtime = filemtime($sfilename);
        $filemtime = get_date($filemtime) . "/" . get_time($filemtime);
        $fileinfo = explode(".", $user_skin[$i]);
        $fileinfo = explode("_", $fileinfo[0]);
        $fileinfo[0] = str_replace("forum", "", $fileinfo[0]);
        $topicinto = "<a href=topic.php?forumid=$fileinfo[0]&filename=$fileinfo[1]_$fileinfo[2]>$arr_ad_lng[403]</a>";
        if (file_exists("{$idpath}forum$fileinfo[0]")) {
            if (!file_exists("{$idpath}forum$fileinfo[0]/$fileinfo[1]_$fileinfo[2]")) $topicinto = "";
        } else {
            $topicinto = "";
        } 
        echo "<tr bgcolor=#F9FAFE><td><a href='$sfilename'>$user_skin[$i] / $filesize bytes</a></td><td><a href=forums.php?forumid=$fileinfo[0]>$arr_ad_lng[403]</a></td><td>$topicinto</td><td>$filemtime</td><td><input type=checkbox name=xdelete[] value='$user_skin[$i]'></td></tr>";
    } 

    $leijisize = round($leijisize / 1024, 2);

    print "                <tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6><br />
		<font color=#333333><strong>$arr_ad_lng[404] $leijisize KB</strong>
		</td></tr>
                <tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6><br />
		<font color=#333333><input type='button' name=chkall value=$arr_ad_lng[405] onclick='CheckAll(this.form)'><input type='button' name=clear2 value=$arr_ad_lng[406] onclick='FanAll(this.form)'><input type='reset' value='$arr_ad_lng[407]'><input type=button onclick='clearam();' value=$arr_ad_lng[408]><input type=button onclick='window.location=\"admin.php?bmod=$thisprog&action=process&verify=$admin_log_hash\"' value=$arr_ad_lng[409]>
		</td></tr></form>
                </td></tr></table>";
} elseif ($choose == "month") { // by month
    print "    <tr><td bgcolor=#14568A valign=middle align=center colspan=5><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[207]</strong>
    </td></tr><form name=delones action=admin.php?bmod=attachment.php&action=delone&step=2 method=POST>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=5>
		<font color=#333333><strong>$arr_ad_lng[400]</strong>
		</td></tr>
		<tr bgcolor=#F9FAFE>$arr_ad_lng[402]</tr>
";
    $dh = opendir("upload/");
    while (false !== ($userskin = readdir($dh))) {
        if (($userskin != ".") && ($userskin != "..")) {
            if (is_dir("upload/" . $userskin)) {
                $dbmonth = opendir("upload/" . $userskin);
                while (false !== ($newuserskin = readdir($dbmonth))) {
                    if (($newuserskin != ".") && ($newuserskin != "..")) {
                        $user_skin_tmp[] = str_replace("month", "", $userskin) . "|" . $userskin . "/" . $newuserskin;
                    } 
                } 
                closedir($dbmonth);
            } else {
                $other_user_skin[] = $userskin;
            } 
        } 
    } 
    closedir($dh);

    for($xxx = 0;$xxx < 2;$xxx++) {
        if ($xxx == 0) {
            $count = count($user_skin_tmp);
        } 
        if ($xxx == 1) {
            $count = count($other_user_skin);
        } 

        for ($i = 0; $i < $count; $i++) {
            if ($xxx == 0) {
                $user_skin_t = explode("|", $user_skin_tmp[$i]);
                if ($user_skin_t[0] != $lastmonth) {
                    echo "<tr bgcolor=#14568A><td colspan=4><font color=white>$user_skin_t[0] $arr_ad_lng[410]</font></td><td colspan=1><a href='admin.php?bmod=attachment.php&action=delone&type=bymonth&month=month$user_skin_t[0]'><font color=white>$arr_ad_lng[411]</a></font></td></tr>";
                } 
                $lastmonth = $user_skin_t[0];
                $user_skin[$i] = $user_skin_t[1];
            } else {
                if ($echoedlast != 1) {
                    echo "<tr bgcolor=#14568A><td colspan=4><font color=white>$arr_ad_lng[412]</font></td><td colspan=1><a href='admin.php?bmod=attachment.php&action=delone&type=bymonth&month=other'><font color=white>$arr_ad_lng[411]</a></font></td></tr>";
                    $echoedlast = 1;
                } 
                $user_skin[$i] = $other_user_skin[$i];
            } 

            $sfilename = "upload/$user_skin[$i]";
            $filesize = filesize($sfilename);
            $leijisize = $leijisize + $filesize;
            $filemtime = filemtime($sfilename);
            $filemtime = get_date($filemtime) . "/" . get_time($filemtime);
            $fileinfo = explode(".", $user_skin[$i]);
            $fileinfo = explode("_", $fileinfo[0]);
            $fileinfo[0] = str_replace("month" . $user_skin_t[0] . "/", "", str_replace("forum", "", $fileinfo[0]));
            $topicinto = "<a href=topic.php?forumid=$fileinfo[0]&filename=$fileinfo[1]_$fileinfo[2]>$arr_ad_lng[403]</a>";

            if (file_exists("{$idpath}forum$fileinfo[0]")) {
                if (!file_exists("{$idpath}forum$fileinfo[0]/$fileinfo[1]_$fileinfo[2]")) {
                    $topicinto = "";
                } 
            } else {
                $topicinto = "";
            } 
            echo "<tr bgcolor=#F9FAFE><td><a href='$sfilename'>$user_skin[$i] / $filesize bytes</a></td><td><a href=forums.php?forumid=$fileinfo[0]>$arr_ad_lng[403]</a></td><td>$topicinto</td><td>$filemtime</td><td><input type=checkbox name=xdelete[] value='$user_skin[$i]'></td></tr>";
        } 
    } 

    $leijisize = round($leijisize / 1024, 2);

    print "                <tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6><br />
		<font color=#333333><strong>$arr_ad_lng[404] $leijisize KB</strong>
		</td></tr>
                <tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=6><br />
		<font color=#333333><input type='button' name=chkall value=$arr_ad_lng[405] onclick='CheckAll(this.form)'><input type='button' name=clear2 value=$arr_ad_lng[406] onclick='FanAll(this.form)'><input type='reset' value='$arr_ad_lng[407]'><input type=button onclick='clearam();' value=$arr_ad_lng[408]><input type=button onclick='window.location=\"$thisprog?action=process&verify=$admin_log_hash\"' value=$arr_ad_lng[409]>
		</td></tr></form>
                </td></tr></table>
    
";
} 
