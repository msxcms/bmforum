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

$thisprog = "batbackup.php";

if ($useraccess != "1" || $admgroupdata[19] != "1") {
    adminlogin();
} 
@set_time_limit(300);
include("include/admin/adminheader.php");
print <<<EOT
	<html>
    <head>
    <title>$arr_ad_lng[39]</title>
    
<style type="text/css">
body { color: #F9FCFE; font-family: verdana,arial; font-size: 9pt;margin-top:0;margin-left:0;
}
.bmf_table_border{
	width: 97%;
	background: #F8FCFF; 
}
.bmf_table_class{
	width: 97%;
	background-color: #F8FCFF;
	border-left:#448ABD 1px solid;
	border-right:#448ABD 1px solid;
}
.bmf_n_table_class{
	width: 100%;
	background-color: #F8FCFF;
	border-left:#448ABD 1px solid;
	border-right:#448ABD 1px solid;
	border-top:#448ABD 1px solid;
}
td {
	border-bottom:1px solid #448ABD;
}
a:link		{color: #333333; text-decoration: none;}
a:visited	{color: #333333; text-decoration: none;}
a:hover		{color: #254394;text-decoration: underline;}
a:active	{color: #254394;text-decoration: underline;}

.t     {	line-height: 1.4;}
td,select,textarea,div,form,option,p{
color:#333333; font-family: tahoma; font-size: 9pt;
}
input  {	font-family: tahoma; font-size: 9pt; height:22px;	}

</style>

    </head>
    <body bgcolor=#F9FCFE topmargin=5 leftmargin=5>

<br />
    </td><td width=70% valign=top bgcolor=#D6DFF7>
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class">
EOT;

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
            <strong>$arr_ad_lng[260]</strong>
            </td></tr>";
if ($action == "delfolder" && is_dir($dirname)) {
    kill_dir($dirname);
    @rmdir($dirname);
    $title = "$arr_ad_lng[802]";
    $status = "$arr_ad_lng[870]";
    print_info();
    echo $showerror;
    exit;
} 
if (empty($action)) {
	$table_selector = '<select multiple size=8 style="width: 50%;" name="selectname[]" style="width: 120px">';
	
	$select_array = array("{$database_up}userlist", "{$database_up}tags", "{$database_up}invite", "{$database_up}primsg", "{$database_up}polls", "{$database_up}threads", "{$database_up}posts", "{$database_up}polls", "{$database_up}usergroup", "{$database_up}forumdata", "{$database_up}lastest", "{$database_up}shareforum", );
	
	$results_t = bmbdb_query("SHOW TABLE STATUS");
    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
    	if (!in_array($line_t['Name'],$select_array) && strstr($line_t['Name'],$database_up)) 
    	$table_selector .= "<option selected='selected' value='$line_t[Name]'>$line_t[Name]</option>";
    }
	$table_selector .= "</select>";
	
    if (function_exists('gzopen')) {
        $isgzipopen = "<input type=checkbox checked='checked' name=gzip value=yes>$arr_ad_lng[872]";
    } 
    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[261]</strong>
    </td>
    </tr>          
	<form action=admin.php?bmod=batbackup.php&action=forum&id=get method=post>  
$table_start
    $arr_ad_lng[262]
$table_stop
		<input type=checkbox value=yes checked="checked" name=lianm>$arr_ad_lng[263] <input type=checkbox checked="checked" value=yes name=usergroup>$arr_ad_lng[265] <input type=checkbox checked="checked" value=yes name=emoticons>$arr_ad_lng[267] <input type=checkbox checked="checked" value=yes name=zxfz>$arr_ad_lng[268] <input type=checkbox checked="checked" value=yes name=welcomeinfor>$arr_ad_lng[269]<br /><input type=checkbox checked="checked" value=yes name=annms>$arr_ad_lng[270] &nbsp;&nbsp; <input type=checkbox checked="checked" value=yes name=fuser>$arr_ad_lng[271] <input type=checkbox checked="checked" value=yes name=adsinfo>$arr_ad_lng[272]<br/><br/>{$table_selector}<br /><br />$arr_ad_lng[273]
$table_start
    <strong>$arr_ad_lng[274]</a></strong>
$table_stop
    $isgzipopen<br />$arr_ad_lng[275] <input type=text name=everytis value=5000> <br />
    <input type=radio checked="checked" value='' name=bakdirtypes>$arr_ad_lng[276]<br />
    <input type=radio value='spec' name=bakdirtypes>$arr_ad_lng[277]<input type=text value='bak$timestamp' name='specdirname'><br /><strong>$arr_ad_lng[278]</strong><br /><br />
    $arr_ad_lng[279]<br />
    <br /><input type=submit value=$arr_ad_lng[280]></a>

</form>
    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "forum") {
    if ($id == "get") {
        $id = $detail[3];
        $title = "$arr_ad_lng[281]";
        if ($bakdirtypes != "spec") {
            $newdir = "bak$timestamp";
            @mkdir($newdir, 0777);
            @mkdir($newdir . "/other", 0777);
            @mkdir($newdir . "/other/rules", 0777);
            @mkdir($newdir . "/other/badman", 0777);
            @mkdir($newdir . "/other/rowads", 0777);
        } else {
            $newdir = $specdirname;
            if (!file_exists($newdir)) {
                @mkdir($newdir, 0777);
            } 
            if (!file_exists($newdir . "/other")) @mkdir($newdir . "/other", 0777);
            if (!file_exists($newdir . "/other/rules")) @mkdir($newdir . "/other/rules", 0777);
            if (!file_exists($newdir . "/other/rowads")) @mkdir($newdir . "/other/rowads", 0777);
            if (!file_exists($newdir . "/other/badman")) @mkdir($newdir . "/other/badman", 0777);
            
        } 
        $filelists = "";
        
        $selectname[]="{$database_up}userlist";
        writetofile($newdir . '/moretables.tmp', implode("|", $selectname));

        $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}forumdata \n\n\n";

        $query = "SELECT * FROM {$database_up}forumdata";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
            $writeninfo .= "INSERT INTO `{$database_up}forumdata` VALUES ('" . str_replace("INSERT", "insert", implode("','", $row)) . "')\n";
        } 
        $query = "SELECT * FROM {$database_up}lastest";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
            $writeninfo .= "INSERT INTO `{$database_up}lastest` VALUES ('" . str_replace("INSERT", "insert", implode("','", $row)) . "')\n";
        } 
        writetofile($newdir . '/forumdata.bmb.sql', $writeninfo);

        if ($lianm == "yes") {
            $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}shareforum \n\n\n";

            $query = "SELECT * FROM {$database_up}shareforum";
            $result = bmbdb_query($query);
            while (false !== ($row = bmbdb_fetch_array($result))) {
                $count = count($row);
                $row = array_values($row);
                for($i = 0;$i < $count;$i++) {
                    $row[$i] = str_replace("'", "\'", $row[$i]);
                } 
                $writeninfo .= "INSERT INTO `{$database_up}shareforum` VALUES ('" . str_replace("INSERT", "insert", implode("','", $row)) . "')\n";
            } 
            writetofile($newdir . '/other/shareforum.bmb.sql', $writeninfo);
            $filelists .= "shareforum.bmb.sql|$arr_ad_lng[263]|\n";
            $status = "$arr_ad_lng[282]<br />";
        } 
        if ($usergroup == "yes") {
            $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}usergroup \n\n\n";

            $query = "SELECT * FROM {$database_up}usergroup";
            $result = bmbdb_query($query);
            while (false !== ($row = bmbdb_fetch_array($result))) {
                $count = count($row);
                $row = array_values($row);
                for($i = 0;$i < $count;$i++) {
                    $row[$i] = str_replace("'", "\'", $row[$i]);
                } 
                $writeninfo .= "INSERT INTO `{$database_up}usergroup` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n";
            } 
            writetofile($newdir . '/other/usergroup.bmb.sql', $writeninfo);
            $filelists .= "usergroup.bmb.sql|$arr_ad_lng[265]|\n";
            $status .= "$arr_ad_lng[284]<br />";
        } 
        if ($emoticons == "yes") {
            @copy("datafile/emoticon.php", "$newdir/other/emoticon.php");
            $filelists .= "emoticon.php|$arr_ad_lng[267]|\n";
            $status .= "$arr_ad_lng[287]<br />";
        } 
        if ($zxfz == "yes") {
            @copy("datafile/zy.php", "$newdir/other/zy.php");
            $filelists .= "zy.php|$arr_ad_lng[268]|\n";
            $status .= "$arr_ad_lng[288]<br />";
        } 
        @copy("datafile/lastreply.php", "$newdir/other/lastreply.php");
        $filelists .= "lastreply.php|$arr_ad_lng[268]-2|\n";
        if ($annms == "yes") {
            @copy("datafile/announcesys.php", "$newdir/other/announcesys.php");
            
            $filelists .= "announcesys.php|$arr_ad_lng[270]|\n";
            
		    $dh = opendir("datafile/");
		    while (false !== ($announcesys = readdir($dh))) {
		        if (substr($announcesys, 0, 12) == "announcement") {
		        	@copy("datafile/".$announcesys, "$newdir/other/".$announcesys);
		        	$list_announce.=$list_announce ? ",$announcesys" : $announcesys;
		        }
		    } 
		    closedir($dh);
    	    $filelists .= "{$list_announce}|$arr_ad_lng[270]|\n";

            
            $status .= "$arr_ad_lng[289]<br />";
        } 
        @copy("datafile/cache/pin_thread.php", "$newdir/other/pin_thread.php");
        @copy("datafile/cache/tags_topic.php", "$newdir/other/tags_topic.php");
        $filelists .= "pin_thread.php,tags_topic.php|$arr_ad_lng[270]|\n";
        if ($welcomeinfor == "yes") {
            @copy("datafile/welcome.php", "$newdir/other/welcome.php");
            $filelists .= "welcome.php|$arr_ad_lng[269]|\n";
            $status .= "$arr_ad_lng[290]<br />";
        } 
        if ($fuser == "yes") {
			bmf_dircpy("datafile/badman", "$newdir/other/badman", true);
            @copy("datafile/idbans.php", "$newdir/other/idbans.php");
            @copy("datafile/ipbans.php", "$newdir/other/ipbans.php");
            @copy("datafile/bannames.php", "$newdir/other/bannames.php");
            @copy("datafile/regipbans.php", "$newdir/other/regipbans.php");
            @copy("datafile/banuserposts.php", "$newdir/other/banuserposts.php");
            @copy("datafile/postbans.php", "$newdir/other/postbans.php");
            $filelists .= "badman,idbans.php,ipbans.php,bannames.php,regipbans.php,banuserposts.php,postbans.php|$arr_ad_lng[271]|\n";
            $status .= "$arr_ad_lng[291]<br />";
        } 
        if ($adsinfo == "yes") {
            @copy("datafile/ads2.php", "$newdir/other/ads2.php");
            @copy("datafile/topads.php", "$newdir/other/topads.php");
            @copy("datafile/ads.php", "$newdir/other/ads.php");
            @copy("datafile/postads.php", "$newdir/other/postads.php");
            @copy("datafile/row_text.php", "$newdir/other/row_text.php");
            bmf_dircpy("datafile/rowads", "$newdir/other/rowads", true);
            bmf_dircpy("datafile/rules", "$newdir/other/rules", true);
            $filelists .= "ads2.php,row_text.php,topads.php,rowads,rules,ads.php,postads.php|$arr_ad_lng[272]|\n";
            $status .= "$arr_ad_lng[292]<br />";
        } 
        
        

        writetofile($newdir . "/filelist.bs5", $filelists);

        $gotourl = "admin.php?bmod=batbackup.php&action=forum&gzip=$gzip&lasti=$i&everytis=$everytis&id=$id&dir=$newdir";
        $status .= "$arr_ad_lng[293]<br /><a href=admin.php?bmod=batbackup.php&action=forum&gzip=$gzip&lasti=$i&everytis=$everytis&id=$id&dir=$newdir>$arr_ad_lng[294]</a>";
        print_info();
        echo $showerror;
    } else {
        if (!$ti) $ti = 0;

        $min = $ti * $everytis;

        $checkinfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Based on $verandproname \n\n\n";

        $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Based on $verandproname \n\n\n";
        $query = "SELECT * FROM {$database_up}polls ORDER BY 'id' ASC LIMIT $min,$everytis";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
            $count = count($row);
            $row = array_values($row);
            for($i = 0;$i < $count;$i++) {
                $row[$i] = addslashes($row[$i]);
            } 
            $writeninfo .= "INSERT INTO `{$database_up}polls` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n";
        } 

        if ($writeninfo != $checkinfo) {
            if ($gzip == "yes") {
                gzipbackup($dir . '/polls.bmb.' . $timestamp . '.sql', $writeninfo);
            } else writetofile($dir . '/polls.bmb.' . $timestamp . '.sql', $writeninfo);
            writetofile($dir . '/filelist.bd5', "polls.bmb.$timestamp.sql\n", "a");
        } 

        $bwriteninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Based on $verandproname \n\n\n";

        $query = "SELECT * FROM {$database_up}threads ORDER BY 'id' ASC LIMIT $min,$everytis";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
            $count = count($row);
            $row = array_values($row);
            for($i = 0;$i < $count;$i++) {
                $row[$i] = addslashes($row[$i]);
            } 
            $bwriteninfo .= str_replace("\n", "<br />", "INSERT INTO `{$database_up}threads` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')") . "\n";
        } 

        if ($bwriteninfo != $checkinfo) {
            if ($gzip == "yes") {
                gzipbackup($dir . '/threads.bmb.' . $timestamp . '.sql', $bwriteninfo);
            } else writetofile($dir . '/threads.bmb.' . $timestamp . '.sql', $bwriteninfo);
            writetofile($dir . '/filelist.bd5', "threads.bmb.$timestamp.sql\n", "a");
        } 

        $awriteninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Based on $verandproname \n\n\n";

        $query = "SELECT * FROM {$database_up}posts ORDER BY 'id' ASC LIMIT $min,$everytis";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
            $count = count($row);
            $row = array_values($row);
            for($i = 0;$i < $count;$i++) {
                $row[$i] = addslashes($row[$i]);
            } 
            $awriteninfo .= str_replace("\n", "<br />", "INSERT INTO `{$database_up}posts` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')") . "\n";
        } 

        if ($awriteninfo != $checkinfo) {
            if ($gzip == "yes") {
                gzipbackup($dir . '/posts.bmb.' . $timestamp . '.sql', $awriteninfo);
            } else writetofile($dir . '/posts.bmb.' . $timestamp . '.sql', $awriteninfo);
            writetofile($dir . '/filelist.bd5', "posts.bmb.$timestamp.sql\n", "a");
        } 

        $ti++;
        if ($writeninfo == $checkinfo && $awriteninfo == $checkinfo && $bwriteninfo == $checkinfo) $gotourl = "admin.php?bmod=batbackup.php&action=primsg&everytis=$everytis&gzip=$gzip&dir=$dir";
        else $gotourl = "admin.php?bmod=batbackup.php&action=forum&gzip=$gzip&ti={$ti}&everytis=$everytis&dir=$dir";

        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $status = "$arr_ad_lng[297] {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
        print_info();
        echo $showerror;
    } 
} elseif ($action == "primsg") {
    if (!$ti) $ti = 0;

    $min = $ti * $everytis;
    $max = ($ti + 1) * $everytis;
	
    $checkinfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}primsg \n\n\n";
    $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}primsg \n\n\n";

    $query = "SELECT * FROM {$database_up}primsg ORDER BY 'id' ASC LIMIT $min,$everytis";
    $result = bmbdb_query($query);
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $count = count($row);
        $row = array_values($row);
        for($i = 0;$i < $count;$i++) {
            $row[$i] = addslashes($row[$i]);
        } 
        $writeninfo .= str_replace("\n%", "%", "INSERT INTO `{$database_up}primsg` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n");
    } 
    
	if ($writeninfo != $checkinfo) {
	    if ($gzip == "yes") {
	        gzipbackup($dir . '/primsg.bmb.'. $timestamp .'.sql', $writeninfo);
	        writetofile($dir . '/gzip', "");
	    } else writetofile($dir . '/primsg.bmb.'. $timestamp .'.sql', $writeninfo);
	    writetofile($dir . '/filelist.bd5', "primsg.bmb.$timestamp.sql\n", "a");
	}


    $ti++;
    if ($writeninfo == $checkinfo){
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
    	
        $gotourl = "admin.php?bmod=batbackup.php&action=invite&everytis=$everytis&gzip=$gzip&dir=$dir";
        $status = "$arr_ad_lng[297] primsg {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    } else {
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
    	
        $gotourl = "admin.php?bmod=batbackup.php&action=primsg&gzip=$gzip&ti={$ti}&everytis=$everytis&dir=$dir";
        $status = "$arr_ad_lng[297] primsg {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    }

    print_info();
    echo $showerror;
} elseif ($action == "invite") {
    if (!$ti) $ti = 0;

    $min = $ti * $everytis;
    $max = ($ti + 1) * $everytis;
	
    $checkinfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}invite \n\n\n";
    $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}invite \n\n\n";

    $query = "SELECT * FROM {$database_up}invite ORDER BY 'id' ASC LIMIT $min,$everytis";
    $result = bmbdb_query($query);
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $count = count($row);
        $row = array_values($row);
        for($i = 0;$i < $count;$i++) {
            $row[$i] = addslashes($row[$i]);
        } 
        $writeninfo .= str_replace("\n%", "%", "INSERT INTO `{$database_up}invite` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n");
    } 
    
	if ($writeninfo != $checkinfo) {
	    if ($gzip == "yes") {
	        gzipbackup($dir . '/invite.bmb.'. $timestamp .'.sql', $writeninfo);
	        writetofile($dir . '/gzip', "");
	    } else writetofile($dir . '/invite.bmb.'. $timestamp .'.sql', $writeninfo);
	    writetofile($dir . '/filelist.bd5', "invite.bmb.$timestamp.sql\n", "a");
	}


    $ti++;
    if ($writeninfo == $checkinfo){
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $gotourl = "admin.php?bmod=batbackup.php&action=tags&everytis=$everytis&gzip=$gzip&dir=$dir";
        $status = "$arr_ad_lng[297] invite {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    	
    } else {
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $gotourl = "admin.php?bmod=batbackup.php&action=invite&gzip=$gzip&ti={$ti}&everytis=$everytis&dir=$dir";
        $status = "$arr_ad_lng[297] invite {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    	
    }

    print_info();
    echo $showerror;

} elseif ($action == "tags") {
    if (!$ti) $ti = 0;

    $min = $ti * $everytis;
    $max = ($ti + 1) * $everytis;
	
    $checkinfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}tags \n\n\n";
    $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: {$database_up}tags \n\n\n";

    $query = "SELECT * FROM {$database_up}tags ORDER BY 'tagid' ASC LIMIT $min,$everytis";
    $result = bmbdb_query($query);
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $count = count($row);
        $row = array_values($row);
        for($i = 0;$i < $count;$i++) {
            $row[$i] = addslashes($row[$i]);
        } 
        $writeninfo .= str_replace("\n%", "%", "INSERT INTO `{$database_up}tags` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n");
    } 
    
	if ($writeninfo != $checkinfo) {
	    if ($gzip == "yes") {
	        gzipbackup($dir . '/tags.bmb.'. $timestamp .'.sql', $writeninfo);
	        writetofile($dir . '/gzip', "");
	    } else writetofile($dir . '/tags.bmb.'. $timestamp .'.sql', $writeninfo);
	    writetofile($dir . '/filelist.bd5', "tags.bmb.$timestamp.sql\n", "a");
	}


    $ti++;
    if ($writeninfo == $checkinfo){
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $gotourl = "admin.php?bmod=batbackup.php&action=more&everytis=$everytis&gzip=$gzip&dir=$dir";
        $status = "$arr_ad_lng[297] tags {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    	
    } else {
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $gotourl = "admin.php?bmod=batbackup.php&action=tags&gzip=$gzip&ti={$ti}&everytis=$everytis&dir=$dir";
        $status = "$arr_ad_lng[297] tags {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    	
    }

    print_info();
    echo $showerror;
} elseif ($action == "more") {
    if (!$ti) $ti = 0;
    
    $tablesinfo	= explode("|",readfromfile($dir . "/moretables.tmp"));
    $count_table = count($tablesinfo) - 1;
    
    if (!$lasttable) $lasttable = 0;

    $thistablename = $tablesinfo[$lasttable];

    $min = $ti * $everytis;
    $max = ($ti + 1) * $everytis;
	
    $checkinfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: $thistablename \n\n\n";
    $writeninfo = "# BMForum Database Dump File (SQL)\n" . "# Dumper version 1.0 SQL\n\n" . "# Dump Timestamp:$timestamp\n" . "# Table name: $thistablename \n\n\n";

	if ($thistablename) {
	    $query = "SELECT * FROM $thistablename LIMIT $min,$everytis";
	    $result = bmbdb_query($query);
	    while (false !== ($row = bmbdb_fetch_array($result))) {
	        $count = count($row);
	        $row = array_values($row);
	        for($i = 0;$i < $count;$i++) {
	            $row[$i] = addslashes($row[$i]);
	        } 
	        $writeninfo .= str_replace("\n%", "%", "INSERT INTO `$thistablename` VALUES ('" . str_replace("INSERT", "insert", nl2br(implode("','", $row))) . "')\n");
	    } 
    }
    
	if ($writeninfo != $checkinfo) {
	    if ($gzip == "yes") {
	        gzipbackup($dir . "/{$thistablename}.bmb.". $timestamp .'.sql', $writeninfo);
	        writetofile($dir . '/gzip', "");
	    } else writetofile($dir . "/{$thistablename}.bmb.". $timestamp .'.sql', $writeninfo);
	    writetofile($dir . '/filelist.bd5', "{$thistablename}.bmb.$timestamp.sql\n", "a");
	}


    $ti++;

    if ($writeninfo == $checkinfo && $lasttable >= $count_table){
        $status = "$arr_ad_lng[297]<br />";
        $title = "$arr_ad_lng[297]";
        $gotourl = "admin.php?bmod=batbackup.php&action=user&everytis=$everytis&gzip=$gzip&dir=$dir";
        $status = "$arr_ad_lng[297] {$thistablename} {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
    	
    } else {
    	if ($writeninfo == $checkinfo) {
    		$lasttable++;
	        $status	= "$arr_ad_lng[297]<br />";
	        $title = "$arr_ad_lng[297]";
	        $gotourl = "admin.php?lasttable=$lasttable&bmod=batbackup.php&action=more&gzip=$gzip&everytis=$everytis&dir=$dir";
	        $status = "$arr_ad_lng[297] {$thistablename} {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
	    } else {
	        $status = "$arr_ad_lng[297]<br />";
	        $title = "$arr_ad_lng[297]";
	        $gotourl = "admin.php?lasttable=$lasttable&bmod=batbackup.php&action=more&gzip=$gzip&ti={$ti}&everytis=$everytis&dir=$dir";
	        $status = "$arr_ad_lng[297] {$thistablename} {$ti}@{$timestamp} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";

	    }
    	
    }

    print_info();
    echo $showerror;
} elseif ($action == "user") {

    $gotourl = "";

    $txtofrec .= "$arr_ad_lng[300]";

    $txtofrec .= "posts.bmb.*.sql $arr_ad_lng[301] - {$database_up}posts\n";
    $txtofrec .= "polls.bmb.*.sql $arr_ad_lng[301] - {$database_up}polls\n";
    $txtofrec .= "threads.bmb.*.sql $arr_ad_lng[301] - {$database_up}threads\n";

    $txtofrec .= "userlist.bmb.*.sql $arr_ad_lng[302] - {$database_up}userlist\n";
    $txtofrec .= "config.php & forumdata.bmb.sql ({$database_up}forumdata) $arr_ad_lng[303]";

    $title = "$arr_ad_lng[304]";
    $status = "$arr_ad_lng[305]<br />$arr_ad_lng[306]{$dir}$arr_ad_lng[307]<br /><br /><a href='admin.php?bmod=batbackup.php&action=delfolder&dirname={$dir}'>$arr_ad_lng[869]</a>";

    $txtfilename = "$dir/readme.txt";
    writetofile($txtfilename, $txtofrec);
   copy("datafile/config.php", "$dir/config.php");


    print_info();
    echo $showerror;
} 


if (!empty($gotourl)) {
    echo "<meta http-equiv=\"Refresh\" content=\"3; URL=" . $gotourl . "&verify=$admin_log_hash\">";
} else {
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
} 

function print_info()
{
    global $status, $title, $showerror, $tab_top;
    $showerror = "
<tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$title</strong>
    </td>
    </tr>       
        <tr>
    <td bgcolor=#F9FAFE colspan=2><br />$tab_top
    $status
    </td>
    </tr>
    </td></tr></table></body></html>";
    return $showerror;
} 
function kill_dir($dir)
{
    $dirhandle = opendir($dir);
    while (false !== ($file_name = readdir($dirhandle))) {
        if ($file_name != "." && $file_name != "..") {
            if (is_dir("$dir/$file_name")) {
                kill_dir($dir . "/" . $file_name);
                @rmdir("$dir/$file_name");
            } else {
                @unlink("$dir/$file_name");
            } 
        } 
    } 
    closedir($dirhandle);
} 
function gzipbackup($filename, $s)
{
    $filename = $filename . ".gz";
    $zp = gzopen($filename, "w9");
    gzwrite($zp, $s);
    gzclose($zp);
} 
