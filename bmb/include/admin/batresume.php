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

$thisprog = "batresume.php";

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
            <strong>$arr_ad_lng[308]</strong>
            </td></tr>";

if (empty($action)) { // Start Page
    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[261]</strong>
    </td>
    </tr>          
                
    <tr>
    <td bgcolor=#F9FAFE colspan=2>
    $arr_ad_lng[309]
$table_start
		$arr_ad_lng[274]</a>
$table_stop
    <form method="get" action="admin.php" style="margin:0px;"><input type=hidden name="bmod" value="$thisprog"><input type=hidden name="action" value="datafile"><input type=hidden name="id" value="get">

	<font face=verdana>
    $arr_ad_lng[310]<br />
	
   $arr_ad_lng[311]<INPUT size=35 value="backup" name="forum_file">
	   <br />$arr_ad_lng[312]
    </font><br /><br /><input type="submit" value="$arr_ad_lng[66]">

    </form>

    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "datafile") { // Start Resume
    if (!function_exists('gzopen') && file_exists("$forum_file/gzip")) {
        $arr_ad_lng[66] = $arr_ad_lng[873] . "\" disabled cns=\"";
    } 
    if (function_exists('gzopen') && file_exists("$forum_file/gzip")) $gzip = "yes";

    $baklist = @file("$forum_file/filelist.bs5");
    $count = count($baklist);
    $listtochoose = "";
    for($i = 0; $i < $count; $i++) {
        $detail = explode("|", $baklist[$i]);
        $listtochoose .= "<input type=checkbox checked value='$detail[0]' checked name=resume[]>$detail[1] ";
    } 

    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[313]</strong>
    </td>
    </tr>          
                
    <tr>
    <td bgcolor=#F9FAFE colspan=2><br />$tab_top
	$arr_ad_lng[314]
    $tab_bottom <br /><br />
    <strong>$arr_ad_lng[274]</a></strong><br /><br />
    <form method="get" action="admin.php" ><input type=hidden name="bmod" value="$thisprog"><input type=hidden name="gzip" value="$gzip"><input type=hidden name="action" value="forum"><input type=hidden name="forum_file" value="$forum_file"><input type=hidden name="id" value="get">
    <table width=92% align=center cellspacing=0 cellpadding=0 bgcolor=254394>
	    <tr><td>
	    <table width=100% cellspacing=1 cellpadding=3>
	    <tr><td bgcolor=FFFFFF>
	<font face=verdana>
    $arr_ad_lng[315]<br />$listtochoose

    </font><br /><br /><input type="submit" value="$arr_ad_lng[66]">
	</td></tr></table>
	</td></tr></table>
    </form>

    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "forum") {
    if ($id == "get") {
        $title = "$arr_ad_lng[281]";
        $ounts = count($resume);
        for($ou = 0;$ou < $ounts;$ou++) {
            $xxetail = explode(",", $resume[$ou]);
            $ountb = count($xxetail);
            for($xu = 0;$xu < $ountb;$xu++) {
                $xxetail[$xu] = str_replace(",", "", $xxetail[$xu]);
                $xxetail[$xu] = str_replace("\n", "", $xxetail[$xu]);
                if ($xxetail[$xu] != "shareforum.bmb.sql" && $xxetail[$xu] != "usergroup.bmb.sql") {
                	if ($xxetail[$xu] == "pin_thread.php") {
                		@copy("$forum_file/other/" . $xxetail[$xu], "datafile/cache/" . $xxetail[$xu]);
                	}else {
                		if (is_dir("$forum_file/other/" . $xxetail[$xu])){
                			bmf_dircpy("$forum_file/other/" . $xxetail[$xu], "datafile/" . $xxetail[$xu], true);
                		}else @copy("$forum_file/other/" . $xxetail[$xu], "datafile/" . $xxetail[$xu]);
                    }
                } else {
                    $user_file = readfromfile("$forum_file/other/" . $xxetail[$xu]);
                    $user_file = explode("\nINSERT", $user_file);
                    $count = count($user_file);
                    if ($xxetail[$xu] == "shareforum.bmb.sql") $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}shareforum`");
                    if ($xxetail[$xu] == "usergroup.bmb.sql") $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}usergroup`");
                    for($i = 1;$i < $count;$i++) {
                        $result = bmbdb_query("INSERT" . str_replace("\\'", "\'", $user_file[$i]), 1);
                    } 
                } 
                $status .= $xxetail[$xu] . "&nbsp;$arr_ad_lng[316]<br />";
            } 
        } 

        $user_file = readfromfile("$forum_file/forumdata.bmb.sql");
        $user_file = explode("\nINSERT", $user_file);
        $count = count($user_file);
        $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}lastest`");
        $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}forumdata`");
        $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}posts`");
        $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}polls`");
        $cleanit = bmbdb_query("TRUNCATE TABLE `{$database_up}threads`");
        
	    $tablesinfo	= explode("|",readfromfile($forum_file . "/moretables.tmp"));
	    $count_table = count($tablesinfo);
	    
	    for ($iss = 0; $iss < $count_table; $iss++){ 
	    	if ($tablesinfo[$iss] && !strstr($tablesinfo[$iss], "userlist")) 
	    		bmbdb_query("TRUNCATE TABLE `{$tablesinfo[$iss]}`");
	    }
        
        for($i = 1;$i < $count;$i++) {
            $result = bmbdb_query("INSERT" . str_replace("\\'", "\'", $user_file[$i]), 1);
        } 

        $gotourl = "admin.php?bmod=batresume.php&gzip=$gzip&action=forum&lasti=$i&id=&forum_file=$forum_file&last=-";
        $status .= "<br />$arr_ad_lng[293]<br /><a href=admin.php?bmod=batresume.php&gzip=$gzip&action=forum&lasti=$i&id=&forum_file=$forum_file&last=->$arr_ad_lng[294]</a>";
        print_info();
        echo $showerror;
    } else {
        $filelist = file($forum_file . "/filelist.bd5");
        $flistunt = count($filelist);
        if ($last == "-") $lastcheck = 0;
        else $lastcheck = $last + 1;

        $filenamethis = str_replace("\n", "", $filelist[$lastcheck]);
        if ($gzip == "yes") {
            $user_file_tmp = @gzfile($forum_file . "/" . $filenamethis . ".gz");
            $user_file = implode("", $user_file_tmp);
        } else $user_file = @readfromfile($forum_file . "/" . $filenamethis);
        $user_file = explode("\nINSERT", $user_file);
        $count = count($user_file);
        for($i = 1;$i < $count;$i++) {
            $result = bmbdb_query("INSERT" . str_replace("\\'", "\'", $user_file[$i]), 1);
        } 

        if (str_replace("\n", "", $filelist[$lastcheck + 1])) {
            $gotourl = "admin.php?bmod=batresume.php&gzip=$gzip&action=forum&lasti=$i&id=$id&forum_file=$forum_file&last=$lastcheck";
        } else {
            $gotourl = "admin.php?bmod=batresume.php&gzip=$gzip&action=user&forum_file=$forum_file";
        } 

        $title = "$arr_ad_lng[318]";
        $status = "$arr_ad_lng[318] {$id} {$filelist[$lastcheck+1]} @{$i} $arr_ad_lng[298]<br /><a href=$gotourl>$arr_ad_lng[299]</a>";
        print_info();
        echo $showerror;
    } 
} elseif ($action == "user") {

    copy("$forum_file/config.php", "datafile/config.php");
    refreshare_cache();
    clean_up_forum();
    update_count_forum();
    update_sum();
    
    // Refresh Javascript Cache
	refresh_jscache();    // end
    refresh_forumcach();
    // Refresh Usergroup Cache
    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
    $result = bmbdb_query($query);
    $ugsocount = "";
    $wrting = "<?php ";
    while (false !== ($line = bmbdb_fetch_array($result))) {
        $line['usersets'] = str_replace('"', '\"', $line['usersets']);
        $wrting .= "
		\$usergroupdata[{$line['id']}]=\"{$line['usersets']}\";
		\$unshowit[{$line['id']}]=\"{$line['unshowit']}\";
		\$ugshoworder[]=\"{$line['id']}\";
		";
        $ugsocount++;
    } 
    $wrting .= "\$ugsocount='$ugsocount';";
    writetofile("datafile/cache/usergroup.php", $wrting);
    // end
    
    
    $title = "$arr_ad_lng[316]";
    $status = "$arr_ad_lng[319]<br /><br /><a href='admin.php?bmod=batbackup.php&action=delfolder&dirname={$forum_file}'>$arr_ad_lng[869]</a>";
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
// recount


function clean_up_forum()
{
    global $method, $limitnum, $timestamp, $limitdate, $target, $t, $database_up;

    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] != "category") {
            // Lastest Reply == START
            $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$fourmrow['id']}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
            $cxresult = bmbdb_query($cxquery);
            $cxline = bmbdb_fetch_array($cxresult);

            $lastinfos = explode(",", $cxline['lastreply']);
            $nquery = "UPDATE {$database_up}forumdata SET  fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$fourmrow['id']}'";
            $result = bmbdb_query($nquery); 
            // Lastest Reply == END
        } 
    } 

    // function end
} 
function update_count_forum()
{
    global $database_up;
    // -----------Recount forum posts-----------
    $aaquery = "SELECT * FROM {$database_up}forumdata WHERE type!='category'";
    $aaresult = bmbdb_query($aaquery);
    while (false !== ($detail = bmbdb_fetch_array($aaresult))) {
        $tmpid = $detail['id'];
        $query = "SELECT COUNT(*) FROM {$database_up}posts WHERE forumid='$tmpid'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $amoutnum = $fcount['COUNT(*)'];
            
        $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE type>=3 AND forumid='$tmpid'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $pinnum = $fcount['COUNT(tid)'];

        $query = "SELECT COUNT(*) FROM {$database_up}threads WHERE forumid='$tmpid' AND ttrash!='1'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $topicnum = $fcount['COUNT(*)'];
            
        $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE islock !=1 AND islock!=0 AND forumid='$tmpid' AND ttrash!='1'"));
        $digestcount = $countrow['COUNT(tid)'];

        $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$tmpid' AND ttrash='1'"));
        $trashcount = $countrow['COUNT(tid)'];

        $replynum = $amoutnum - $topicnum;
        $nquery = "UPDATE {$database_up}forumdata SET digestcount=$digestcount,trashcount=$trashcount,pincount='$pinnum',topicnum='$topicnum',replysnum='$replynum' WHERE id='$tmpid'";
        $result = bmbdb_query($nquery);
    } 
} 
function update_sum()
{
    // ----------Update the sum------------
    global $database_up;
    $query = "SELECT COUNT(id) FROM {$database_up}posts";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $amoutnum = $fcount['COUNT(id)'];
    
    $query = "SELECT COUNT(userid) FROM {$database_up}userlist";
    $result = bmbdb_query($query, 0);
    $xcount = bmbdb_fetch_array($result);
    $count = $xcount['COUNT(userid)'];
    
    $query = "SELECT COUNT(id) FROM {$database_up}threads";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $threadnum = $fcount['COUNT(id)'];

    $nquery = "UPDATE {$database_up}lastest SET regednum = '$count',threadnum='$threadnum',postsnum='$amoutnum' WHERE pageid='index'";
    $result = bmbdb_query($nquery);
} 

