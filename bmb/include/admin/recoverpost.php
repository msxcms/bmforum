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

$thisprog = "recoverpost.php";

if ($useraccess != "1" || $can_rec != "1") {
    adminlogin();
} 

$allowfid = "";
$result = bmbdb_query("SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC");
while (false !== ($row = bmbdb_fetch_array($result))) {
	$forumname["$row[id]"] = $row['bbsname'];
	if (@in_array($row['id'], $check_catearray)) { $allowfid.=",".$row['id']; continue; }
	if ($row['adminlist']) {
		$lists = explode("|", $row['adminlist']);
		for ($i = 0; $i < count($lists); $i++){
			if (trim($lists[$i]) == $username) {
				if ($row['type'] == "category") {
					$check_catearray[]=$row['id'];
				} else $allowfid.=",".$row['id'];
			}
		}
	}
}

if ($action != "process") {
if (!$del_rec) $disable_this = "disabled='true'";

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1214] $arr_ad_lng[1215]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[1214] / $arr_ad_lng[1215]</strong>
  <form action="admin.php?bmod=$thisprog" method="post" name="fromsubmit" style="margin:0px;">
  <input type=hidden name="action" value="process">
  <input type=hidden name="type" value="$type">
  <input type=hidden name="job" value="r"><script language="JavaScript">
function clearam(jobtype){
if(confirm("$arr_ad_lng[391]", "$arr_ad_lng[391]")){
fromsubmit.job.value=jobtype;
fromsubmit.submit();
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
  </td></tr>





  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left colspan=2>
  
$table_start<strong>$arr_ad_lng[1217]</strong>
  </font></td>
  </tr>
  <tr>
  <td bgcolor=#F9FAFE colspan=2 valign=middle align=left>

  <input type="button" value="$arr_ad_lng[405]" onclick="javascript:CheckAll(fromsubmit);" />
  <input type="button" value="$arr_ad_lng[406]" onclick="javascript:FanAll(fromsubmit);" />
  <input type="reset" value="$arr_ad_lng[407]" />
  <input type="button" value="$arr_ad_lng[1218]" onclick="javascript:clearam('r')" />
  <input type="button" value="$arr_ad_lng[411]" onclick="javascript:clearam('d')" $disable_this />
  </td>
  </tr>

  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left colspan=2>
  
$table_start<strong>$arr_ad_lng[1216]</strong>
  </font></td>
  </tr>

  <tr>
  <td bgcolor=#F9FAFE colspan=2 valign=middle align=left>
  <div id="pages_1"></div>
  </td>
  </tr>

EOT;


if (empty($jumppage)) $jumppage = 1;
$limit_start = ($jumppage-1)*15;

if ($type == "t") {
	if ($allowfid) $allowfid = "AND forumid in (".substr($allowfid, 1).")";
	if ($usertype[22] == 1 || $usertype[21] == 1) $allowfid = "";
	$tmp["counts"] = bmbdb_query_fetch("SELECT COUNT(tid) FROM {$database_up}threads WHERE ordertrash > 0 {$allowfid}");
	$count = $tmp["counts"]['COUNT(tid)'];
	$pages = ceil($count / 15);

	$results_t = bmbdb_query("SELECT * FROM {$database_up}threads WHERE ordertrash > 0 {$allowfid} ORDER BY `changetime` DESC LIMIT {$limit_start},15");
    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
    	
    	$line_t[title] = stripslashes($line_t[title]);
    	$line_t[content] = substrfor(stripslashes(strip_tags($line_t[content])), 0, 50);
    	$date = getfulldate($line_t[time]);
    	$fname = $forumname["$line_t[forumid]"];
    	
echo<<<EOT
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type="checkbox" value="1" name="pct[$line_t[tid]]" /> <a href="topic.php?filename=$line_t[tid]" style="font-weight:bold;" target="_blank">$line_t[title]</a> <em>(<a href="forums.php?forumid=$line_t[forumid]" target="_blank">{$fname}</a>)</em>
  <br />$line_t[content]
  </td>
  <td bgcolor=#F9FAFE valign=middle align=left width=30%>
  <a href="profile.php?job=show&memberid=$line_t[authorid]" style="font-weight:bold;" target="_blank">$line_t[author]</a><br />$date
  </td>
  </tr>
EOT;

    }
} else {
	if ($allowfid) $allowfid = "AND t.forumid in (".substr($allowfid, 1).")";
	if ($usertype[22] == 1 || $usertype[21] == 1) $allowfid = "";

	$tmp["counts"] = bmbdb_query_fetch("SELECT COUNT(p.id) FROM {$database_up}posts p LEFT JOIN {$database_up}threads t ON t.tid=p.tid WHERE p.posttrash = '1' {$allowfid}");
	$count = $tmp["counts"]['COUNT(p.id)'];
	$pages = ceil($count / 15);

	$results_t = bmbdb_query("SELECT t.title,p.* FROM {$database_up}posts p LEFT JOIN {$database_up}threads t ON t.tid=p.tid WHERE p.posttrash = '1' {$allowfid} ORDER BY `changtime` DESC LIMIT {$limit_start},15");
    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
    	
    	$line_t[articletitle] = stripslashes($line_t[articletitle]);
    	if (!$line_t[articletitle]) $line_t[articletitle] = $arr_ad_lng[1219];
    	$line_t[title] = stripslashes($line_t[title]);
    	$line_t[articlecontent] = substrfor(stripslashes(strip_tags($line_t[articlecontent])), 0, 50);
    	$date = getfulldate($line_t[timestamp]);
    	$fname = $forumname["$line_t[forumid]"];
    	
echo<<<EOT
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type="checkbox" value="1" name="pct[$line_t[id]]" /> <a href="topic.php?ajax_display=1&pid=$line_t[id]&filename=$line_t[tid]&page=last" style="font-weight:bold;" target="_blank">$line_t[articletitle]</a> <a href="topic.php?filename=$line_t[tid]" style="font-weight:bold;" target="_blank">(RE:$line_t[title])</a> <em>(<a href="forums.php?forumid=$line_t[forumid]" target="_blank">{$fname}</a>)</em>
  <br />$line_t[articlecontent]
  </td>
  <td bgcolor=#F9FAFE valign=middle align=left width=30%>
  <a href="profile.php?job=show&memberid=$line_t[usrid]" style="font-weight:bold;" target="_blank">$line_t[username]</a><br />$date
  </td>
  </tr>
EOT;

    }
}
if (empty($jumppage)) $jumppage = 1;
$nextpage = $jumppage + 1;
$previouspage = $jumppage-1;
$maxpagenum = $jumppage + 4;
$minpagenum = $jumppage-4;

$pagenumber = "<strong>{$jumppage}/$pages&nbsp;&nbsp;</strong>";
$pagenumber .= "\n<a href=\"admin.php?bmod=recoverpost.php&amp;type=$type&amp;jumppage=\"><strong>&laquo;</strong></a>";
for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
    if ($i > 0 && $i <= $pages) {
        if ($i == $jumppage) {
            $pagenumber .= "<strong><u>$i</u></strong>\n";
        } else {
            $pagenumber .= "<a href=\"admin.php?bmod=recoverpost.php&amp;type=$type&amp;jumppage=$i\">$i</a>\n";
        } 
    } 
} 
$pagenumber .= "<a href=\"admin.php?bmod=recoverpost.php&amp;type=$type&amp;jumppage=$pages\"><strong>&raquo;</strong></a> &nbsp;&nbsp;$arr_ad_lng[773]<input type='text' value='' onkeydown='javascript:if(event.keyCode == 13) {location.href=\"admin.php?bmod=recoverpost.php&amp;type=$type&amp;verify=$admin_log_hash&amp;jumppage=\"+this.value+\"\"};' size='3'>";

print <<<EOT
  <tr>
  <td bgcolor=#F9FAFE colspan=2 valign=middle align=left>
  <div id="pages_2">$pagenumber</div>
  </td>
  </tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left colspan=2>
  
$table_start<strong>$arr_ad_lng[1217]</strong>
  </font></td>
  </tr>
  <tr>
  <td bgcolor=#F9FAFE colspan=2 valign=middle align=left>

  <input type="button" value="$arr_ad_lng[405]" onclick="javascript:CheckAll(fromsubmit);" />
  <input type="button" value="$arr_ad_lng[406]" onclick="javascript:FanAll(fromsubmit);" />
  <input type="reset" value="$arr_ad_lng[407]" />
  <input type="button" value="$arr_ad_lng[1218]" onclick="javascript:clearam('r')" />
  <input type="button" value="$arr_ad_lng[411]" onclick="javascript:clearam('d')" $disable_this />
  </td>
  </tr>
<script type="text/javascript">
document.getElementById("pages_1").innerHTML=document.getElementById("pages_2").innerHTML;
</script>



</form>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
	$process_ids = $refresh_reply = "";
	if ($allowfid) {
		$allowfid = "AND forumid in (".substr($allowfid, 1).")";
		$allowfid_p = "AND t.forumid in (".substr($allowfid, 1).")";
	}
	if ($usertype[22] == 1 || $usertype[21] == 1) $allowfid = $allowfid_p = "";
	foreach ($pct as $key=>$value) {
		if ($value == 1) $process_ids.=",".$key;
	}
	
	$process_ids = substr($process_ids, 1);
	
if ($type == "t") {
	
	if ($job == "r") {
		$results_t = bmbdb_query("SELECT * FROM {$database_up}threads WHERE ordertrash > 0 {$allowfid} AND tid in($process_ids)");
	    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
	    	$pincancel = $addalimit = "";
	    	
			$forumid = $line_t['forumid'];
			$topic_type = $line_t['type'];
			$topic_islock = $line_t['islock'];

		    if ($topic_type >= 3) $pincancel = "pincount=pincount+1,";
		    if ($topic_islock != 0 && $topic_islock != 1) $addalimit = "digestcount=digestcount+1,";

    		bmbdb_query("UPDATE {$database_up}forumdata SET {$pincancel} {$addalimit} trashcount=trashcount-1,topicnum = topicnum+1 WHERE id='{$forumid}'");
		}
		bmbdb_query("UPDATE {$database_up}threads SET ttrash=0,ordertrash=0 WHERE tid in($process_ids) {$allowfid}");
	} elseif($del_rec) {
		$results_t = bmbdb_query("SELECT * FROM {$database_up}threads WHERE ordertrash > 0 {$allowfid} AND tid in($process_ids)");
	    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
	        $uploadfilename = $line_t['other3'];
	        $forumid = $line_t['forumid'];
	        if (strpos($uploadfilename, "¡Á")) {
	            $attachshow = explode("¡Á", $uploadfilename);
	            $countas = count($attachshow)-1;
	        } else {
	            $attachshow[0] = $uploadfilename;
	            $countas = 1;
	        } 
	        $uploadfileshow = "";
	        for ($ias = 0;$ias < $countas;$ias++) {
	            $showdes = explode("¡ò", $attachshow[$ias]);
	            @unlink("upload/{$showdes[0]}");
	        } 
	        $oldtags = $line_t['ttagname'];
	        if ($oldtags) {
	        	$oldtags_sql = implode("' OR tagname='", explode(" ", $oldtags));
	        	$filename = $line_t['tid'];
	        	bmbdb_query("UPDATE {$database_up}tags SET threads=threads-1,filename=replace(filename,',$filename','') WHERE tagname='{$oldtags_sql}'");
	        }
	        bmbdb_query("UPDATE {$database_up}forumdata SET trashcount=trashcount-1 WHERE id='{$forumid}'");
	    }
		bmbdb_query("DELETE FROM {$database_up}threads WHERE tid in($process_ids) {$allowfid}");
		bmbdb_query("DELETE FROM {$database_up}posts WHERE tid in($process_ids) {$allowfid}");
		bmbdb_query("DELETE FROM {$database_up}polls WHERE id in($process_ids) {$allowfid}");
		bmbdb_query("DELETE FROM {$database_up}beg WHERE tid in($process_ids) {$allowfid}");
	}

} else {

	
	if ($job == "r") {
		bmbdb_query("UPDATE {$database_up}posts SET posttrash='0' WHERE posttrash='1' {$allowfid} AND id in($process_ids)");
	} elseif($del_rec) {
		$results_t = bmbdb_query("SELECT t.title,p.* FROM {$database_up}posts p LEFT JOIN {$database_up}threads t ON t.tid=p.tid WHERE p.posttrash='1' {$allowfid_p} AND p.id in($process_ids)");
	    while (false !== ($line = bmbdb_fetch_array($results_t))) {
			$article = $line['id'];
			$thisistid = $line['tid'];
            $uploadfilename = $line['other3'];
            if (strpos($uploadfilename, "¡Á")) {
                $attachshow = explode("¡Á", $uploadfilename);
                $countas = count($attachshow)-1;
            } else {
                $attachshow[0] = $uploadfilename;
                $countas = 1;
            } 
            $uploadfileshow = "";
            for ($ias = 0;$ias < $countas;$ias++) {
                $showdes = explode("¡ò", $attachshow[$ias]);
                @unlink("upload/{$showdes[0]}");
            } 

            bmbdb_query("DELETE FROM {$database_up}posts WHERE id='$article'");
            
            $refresh_reply["$line[tid]"] = $line['forumid'];
            $title_cache["$line[tid]"] = $line['title'];
            if (!$deledpostnum["$line[tid]"]) $deledpostnum["$line[tid]"] = 0;
            $deledpostnum["$line[tid]"]++;
	    }
	    
	    foreach($refresh_reply as $thisistid=>$forumid) {
	        $aaquery = "SELECT articletitle,username,usrid,timestamp FROM {$database_up}posts WHERE tid='$thisistid' ORDER BY `changtime` DESC LIMIT 0,1";
	        $aaresult = bmbdb_query($aaquery);
	        
	        $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $title_cache[$thisistid]));
	        
	        while (false !== ($aaline = bmbdb_fetch_array($aaresult))) {
	            if (empty($addinfos)) $addinfos = ($aaline['articletitle'] ? $aaline['articletitle'] : $articletitle_reply) . "," . $aaline['username'] . "," . $aaline['timestamp'];
	            if (empty($truechangtime)) $truechangtime = $aaline['timestamp'];
	        } 

	        $nquery = "UPDATE {$database_up}threads SET  replys= replys-{$deledpostnum[$thisistid]},{$changadd}lastreply='$addinfos' WHERE tid='$thisistid'";
	        $result = bmbdb_query($nquery);

	        $cxline = bmbdb_query_fetch("SELECT * FROM {$database_up}threads WHERE forumid='$forumid' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1");

	        $lastinfos = explode(",", $cxline['lastreply']);
	        bmbdb_query("UPDATE {$database_up}forumdata SET replysnum = replysnum-{$deledpostnum[$thisistid]},fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='$forumid'");
	        
        }
	}

}

print "<meta http-equiv=\"Refresh\" content=\"0; URL='admin.php?bmod=forumfix.php&action=cleanup&method=repairlate&target=all&verify=$admin_log_hash'\" /><a href='admin.php?bmod=forumfix.php&action=cleanup&method=repairlate&target=all'>Redirecting</a>";
exit;
} 
function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}