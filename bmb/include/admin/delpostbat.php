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

$forumfile = "datafile/forumdata.php";
$thisprog = "delpostbat.php";

@set_time_limit(300);
if ($useraccess != "1" || $admgroupdata[11] != "1") {
    adminlogin();
} 

$today = getdate();
$month = $today['mon'];
$mday = $today['mday'];
$year = $today['year'];
$todayshow = "$year/$month/$mday";
print "<tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[321]</strong>
    </td></tr><tr><td bgcolor=#F9FAFE colspan=3>
";
$t = time();

if (empty($action)) {
    $forumselect = "";
    $forumonly = "";
    $catselect = "";
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] == "category") {
            $forumselect .= "<option value=\"分类{$fourmrow['id']}\">-=-{$fourmrow['bbsname']}-=-</option>";
        } else {
            $forumselect .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
        } 
    } 
    $forumselect .= "</select>";

    print <<<EOT
    $table_start
    <strong>$arr_ad_lng[322]</strong>
    $table_stop
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
    <input type="radio" value="" checked="checked" name="with" />$arr_ad_lng[323] <input type="radio" value="id" name="with" />$gl[512] &nbsp;&nbsp;<input type="text" name="usertodel" size="20" /><br />
    <input type=checkbox name="inforum" value="1" id="cd1"><label title="$arr_ad_lng[324]" for="cd1">$arr_ad_lng[325]</label><br /><select name="inforumselect">$forumselect<br />
    <input type=hidden name="action" value="delnow"><br />
   	<input type=checkbox name="alltime" value="before" id="cd2"><label for="cd2">$arr_ad_lng[326]<input type=text value="$todayshow" name=beforedate></label>
   	<input type=checkbox name="alltimet" checked value="after" id="cd3"><label for="cd3">$arr_ad_lng[327]<input type=text value="$todayshow" name=afterdate></label>
   		<br /><input type=checkbox checked name="delintopic" value="1" id="cd4"><label for="cd4">$arr_ad_lng[328]</label><input type=checkbox name="delinpost" value="1" id="cd5"><label title="$arr_ad_lng[329]" for="cd5">$arr_ad_lng[330]</label>
   		<p>
    <button type=submit>$arr_ad_lng[331]</button>
</form>

EOT;
    exit;
} elseif ($action == "delnow") {
	$usertodel_array = explode(",", $usertodel);
	$count = count($usertodel_array);
	
	for ($ssi = 0;$ssi < $count;$ssi++) {
		$usertodel = $usertodel_array[$ssi];
	
	    if ($alltime == "before") {
	        $tmpdate = explode("/", $beforedate);
	        $tmptimes = mktime (0, 0, 0, $tmpdate[1], $tmpdate[2], $tmpdate[0]);
		}
	    if ($alltimet == "after") {
	        $tmpdatet = explode("/", $afterdate);
	        $tmptimest = mktime (0, 0, 0, $tmpdatet[1], $tmpdatet[2], $tmpdatet[0]);
	    } 
	    if (strpos($inforumselect, "类")) {
	        $cidforumid = " AND ( forumid='XXXXX' ";
	        $inforumselect = str_replace("分类", "", $inforumselect);
	        $intype = "分类";
	        $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
	        $nresult = bmbdb_query($nquery);
	        while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
	            if ($fourmrow['forum_cid'] == $inforumselect) {
	                $cidforumid .= " OR forumid='{$fourmrow['id']}' ";
	            } 
	        } 
	        $cidforumid .= " ) ";
	    } 
	    if ($with == "id") {
	    	$line = get_user_info($usertodel, "usrid");
	    } else {
	    	$line = get_user_info($usertodel);
	    }
	    $selectby = ($with == "id") ? "usrid" : "username";
	    
	    $qutime = $tqutime = "";
	    
	    if ($alltime == "before") {
	        $qutime = " AND changtime<$tmptimes";
	        $tqutime = " AND changetime<$tmptimes";
	    }
	    if ($alltimet == "after") {
	        $qutime = " AND changtime>$tmptimest";
	        $tqutime = " AND changetime>$tmptimest";
	    } 
	    if ($inforum == 1) {
	        if ($intype != "分类") {
	            if ($delinpost == 1) {
			 		$results_t = bmbdb_query("SELECT tid,articletitle,forumid FROM {$database_up}posts WHERE {$selectby}='$usertodel' AND forumid='$inforumselect' $qutime");
				    while (false !== ($line = bmbdb_fetch_array($results_t))) {
				    	$refresh_tid[$line['tid']] = $line['forumid'];
				    	$refresh_tit[$line['tid']] = $line['articletitle'];
				    	if(!$deledpostnum[$line['tid']]) $deledpostnum[$line['tid']] = 0;
				    	$deledpostnum[$line['tid']]++;
				    }
	            	
	                bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' AND forumid='$inforumselect' $qutime");
	            } 
	            if ($delintopic == 1) {
	                bmbdb_query("DELETE FROM {$database_up}threads WHERE author='{$line['username']}' AND forumid='$inforumselect' $tqutime");
	                bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' AND tid=id AND forumid='$inforumselect' $qutime");
	            } 
	        } else {
	            if ($delinpost == 1) {
			 		$results_t = bmbdb_query("SELECT tid,articletitle,forumid FROM {$database_up}posts WHERE {$selectby}='$usertodel' $cidforumid $qutime");
				    while (false !== ($line = bmbdb_fetch_array($results_t))) {
				    	$refresh_tid[$line['tid']] = $line['forumid'];
				    	$refresh_tit[$line['tid']] = $line['articletitle'];
				    	if(!$deledpostnum[$line['tid']]) $deledpostnum[$line['tid']] = 0;
				    	$deledpostnum[$line['tid']]++;
				    }
	                bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' $cidforumid $qutime");
	            } 
	            if ($delintopic == 1) {
	                bmbdb_query("DELETE FROM {$database_up}threads WHERE author='{$line['username']}' $cidforumid $tqutime");
	                bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' AND tid=id AND $cidforumid $qutime");
	            } 
	        } 
	    } else {
	        if ($delinpost == 1) {
		 		$results_t = bmbdb_query("SELECT tid,articletitle,forumid FROM {$database_up}posts WHERE {$selectby}='$usertodel' $qutime");
			    while (false !== ($line = bmbdb_fetch_array($results_t))) {
			    	$refresh_tid[$line['tid']] = $line['forumid'];
			    	$refresh_tit[$line['tid']] = $line['articletitle'];
			    	if(!$deledpostnum[$line['tid']]) $deledpostnum[$line['tid']] = 0;
			    	$deledpostnum[$line['tid']]++;
			    }
	            bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' $qutime");
	        } 
	        if ($delintopic == 1) {
	            bmbdb_query("DELETE FROM {$database_up}threads WHERE author='{$line['username']}' $tqutime");
	            bmbdb_query("DELETE FROM {$database_up}posts WHERE {$selectby}='$usertodel' AND tid=id $qutime");
	        } 
	    } 
	    if (count($refresh_tid) > 0) {
	    	foreach($refresh_tid as $thisistid => $forumid) {
	    		$addinfos = $truechangtime = "";
		        // Lastest Reply == START
		        $aaquery = "SELECT articletitle,username,usrid,timestamp FROM {$database_up}posts WHERE tid='$thisistid' ORDER BY `changtime` DESC LIMIT 0,1";
		        $aaresult = bmbdb_query($aaquery);
		        
		        $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $refresh_tit[$thisistid]));
		        
		        while (false !== ($aaline = bmbdb_fetch_array($aaresult))) {
		            if (empty($addinfos)) $addinfos = ($aaline['articletitle'] ? $aaline['articletitle'] : $articletitle_reply) . "," . $aaline['username'] . "," . $aaline['timestamp'];
		            if (empty($truechangtime)) $truechangtime = $aaline['timestamp'];
		        } 

		        bmbdb_query("UPDATE {$database_up}threads SET  changetime='$truechangtime',replys= replys-{$deledpostnum[$thisistid]},{$changadd}lastreply='$addinfos' WHERE tid='$thisistid'");

		        $cxline = bmbdb_query_fetch("SELECT * FROM {$database_up}threads WHERE forumid='$forumid' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1");

		        $lastinfos = explode(",", $cxline['lastreply']);
		        bmbdb_query("UPDATE {$database_up}forumdata SET  replysnum = replysnum-{$deledpostnum[$thisistid]},fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='$forumid'");
	    	}
	    	refresh_forumcach(); 
	    }
	}
} 

print "<meta http-equiv=\"Refresh\" content=\"0; URL='admin.php?bmod=forumfix.php&action=cleanup&method=repairlate&target=all&verify=$admin_log_hash'\" /><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a></td></tr></table></body></html>";
exit;
function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}