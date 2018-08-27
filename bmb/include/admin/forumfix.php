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

@set_time_limit(300);

$thisprog = "forumfix.php";
$forumfile = "datafile/forumdata.php";

if ($useraccess != "1" || $admgroupdata[4] != "1") {
    adminlogin();
} 

print "<tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[190]</strong>
    </td></tr>";

$t = time();

if (empty($action)) { // Start Page
    $forumonly = "";
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] != "category") $forumonly .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
    } 
    $forumonly .= "</select>";

    print <<<EOT
$table_start
    <strong>$arr_ad_lng[362]</strong>
    	<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="cleanup">
$table_stop
    $arr_ad_lng[363] $forumonly <input type=submit value="$arr_ad_lng[66]"><br /><br />
    $arr_ad_lng[364]
    $tab_bottom
    </form>
$table_start<strong>$arr_ad_lng[365]</strong>
<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="updatecount">
$table_stop
    $arr_ad_lng[366] $forumonly <input type=submit value="$arr_ad_lng[66]"><br />

    </form>
$table_start<strong>$arr_ad_lng[958]</strong>$table_stop
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="counttags">

    $arr_ad_lng[1026] <br /><input size=50 type=text name='tagsname'> <input type=submit value="$arr_ad_lng[66]">
    </form>
    </td></tr>
    </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "counttags") {
	counttags($tagsname);
} elseif ($action == "cleanup") {
    if (($method == "byamount" && empty($limitnum)) || ($method == "bydate" && empty($limitdate))) {
        print "<tr><td bgcolor=#F9FAFE colspan=3><strong>$arr_ad_lng[369]</strong></td></tr>
		<tr><td bgcolor=#F9FAFE colspan=3><br /><strong>$arr_ad_lng[370]</strong><br /><br />
		&gt;&gt; <a href='javascript:history.go(-1)'>$arr_ad_lng[361]</a></td></tr></td></tr></table></body></html>";
        exit;
    } 

    clean_up_forum($target);
    update_sum();
    refresh_forumcach();
} elseif ($action == "updatecount") {
    update_count_forum($target);
    update_sum();
    refresh_forumcach();
} 
print "<tr><td bgcolor=#F9FAFE colspan=3><strong>$arr_ad_lng[371]</strong></td></tr>
<tr><td bgcolor=#F9FAFE colspan=3><br /><strong>$arr_ad_lng[372]</strong><br /><br />
&gt;&gt; <a href='javascript:history.go(-1)'>$arr_ad_lng[361]</a></td></tr>
</td></tr></table></body></html>";
exit;

/*
Function:
counttags: Count Tags' threads
clean_up_forum: As its name
update_count_forum: Recount Forum threads
*/
function counttags($tagname)
{
    global $database_up;
    if (empty($tagname)) {
        $query = "SELECT filename,tagid FROM {$database_up}tags";
        $result = bmbdb_query($query);
        while (false !== ($row = bmbdb_fetch_array($result))) {
        	$tagthreads = $row['filename'];
        	$thread_list = substr($tagthreads, 1);
        	if ($thread_list) {
	        	$count_r = bmbdb_fetch_array(bmbdb_query("SELECT count(tid) FROM `{$database_up}threads` WHERE tid in({$thread_list})"));
	        	$count = $count_r['count(tid)'];
	        } else $count = 0;
        	$tagid = $row['tagid'];
        	bmbdb_query("UPDATE {$database_up}tags SET threads=$count WHERE tagid='$tagid'");
        }
    } else {
    	// part the tag list
    	$taglist = explode(" ", strtolower($tagname));
    	$counttags = count($taglist);
    	$query = "SELECT filename,tagid,tagname FROM {$database_up}tags WHERE ";
    	for ($i = 0; $i < $counttags; $i++) {
    		if ($i < $counttags-1) $orshow = "OR"; else $orshow = "";
		    $query .= "tagname='{$taglist[$i]}' $orshow ";
	    }
	    $result = bmbdb_query($query);
	    // process data
        while (false !== ($row = bmbdb_fetch_array($result))) {
	      	$tagthreads = $row['filename'];
        	$thread_list = substr($tagthreads, 1);
        	if ($thread_list) {
	        	$count_r = bmbdb_fetch_array(bmbdb_query("SELECT count(tid) FROM `{$database_up}threads` WHERE tid in({$thread_list})"));
	        	$count = $count_r['count(tid)'];
	        } else $count = 0;
			$tagid = $row['tagid'];
	       	bmbdb_query("UPDATE {$database_up}tags SET threads=$count WHERE tagid='$tagid'");
	    }


    }
}
function clean_up_forum($id)
{
    // --------Clean the old posts in a forum-----------
    global $method, $limitnum, $timestamp, $limitdate, $target, $t, $database_up;

    if (empty($id)) $id = "all";

    if ($id != "all") {
        $addquery = " AND forumid='$id'";
        $getquery = " WHERE forumid='$id'";
    } 

    if ($method == "byamount") {
        $query = "SELECT COUNT(*) FROM {$database_up}threads $getquery";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $limitnum = $fcount['COUNT(*)'] - $limitnum;
        if ($limitnum > 0) {
            $byquery = " tid<'$limitnum'";
            $query = "DELETE FROM {$database_up}threads $getquery ORDER BY `changetime` ASC LIMIT $limitnum";
            $result = bmbdb_query($query);
            $query = "DELETE FROM {$database_up}posts WHERE $byquery $addquery";
            $result = bmbdb_query($query);
        } 
    } elseif ($method == "bydate") {
        $limitdate = $timestamp - $limitdate * 86400;
        $byquery = " changtime<'$limitdate'";
        $tbyquery = " changetime<'$limitdate'";

        $query = "DELETE FROM {$database_up}threads WHERE $tbyquery $addquery";
        $result = bmbdb_query($query);
        $query = "DELETE FROM {$database_up}posts WHERE $byquery $addquery";
        $result = bmbdb_query($query);
    } 
    // ----12:52 2003-11-8 done
    if ($target != "all") {
        // Lastest Reply == START
        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='{$target}' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET  fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='{$target}'";
        $result = bmbdb_query($nquery); 
        // Lastest Reply == END
    } else {
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
    } 
    // function end
} 
function update_count_forum($id)
{
    global $database_up;
    // -----------Recount forum posts-----------
    if (empty($id)) $id = "all";
    if ($id != "all") {
        $query = "SELECT COUNT(id) FROM {$database_up}posts WHERE forumid='$id'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $amoutnum = $fcount['COUNT(id)'];
        
        $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE type>=3 AND forumid='$id'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $pinnum = $fcount['COUNT(tid)'];

        $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$id' AND ttrash!='1'";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $topicnum = $fcount['COUNT(tid)'];

        $replynum = $amoutnum - $topicnum;
        
        $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE islock !=1 AND islock!=0 AND forumid='$id' AND ttrash!='1'"));
        $digestcount = $countrow['COUNT(tid)'];

        $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$id' AND ttrash='1'"));
        $trashcount = $countrow['COUNT(tid)'];

        $nquery = "UPDATE {$database_up}forumdata SET digestcount=$digestcount,trashcount=$trashcount,pincount='$pinnum',topicnum='$topicnum',replysnum='$replynum' WHERE id='$id'";
        $result = bmbdb_query($nquery);
    } else {
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

            $query = "SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$tmpid' AND ttrash!='1'";
            $result = bmbdb_query($query, 0);
            $fcount = bmbdb_fetch_array($result);
            $topicnum = $fcount['COUNT(tid)'];
            
            $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE islock !=1 AND islock!=0 AND forumid='$tmpid' AND ttrash!='1'"));
            $digestcount = $countrow['COUNT(tid)'];

            $countrow = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(tid) FROM {$database_up}threads WHERE forumid='$tmpid' AND ttrash='1'"));
            $trashcount = $countrow['COUNT(tid)'];

            $replynum = $amoutnum - $topicnum;
            $nquery = "UPDATE {$database_up}forumdata SET digestcount=$digestcount,trashcount=$trashcount,pincount='$pinnum',topicnum='$topicnum',replysnum='$replynum' WHERE id='$tmpid'";
            $result = bmbdb_query($nquery);
        } 
    } 
    
    @include("datafile/cache/pin_thread.php");
    
    if (count($topthread) > 0)
    {
	    foreach ($topthread as $key=>$value) {
	    	$this_arr = substr($value, 0, -1);
	    	$new_count_pint["$key"] = 0;
	    	if ($this_arr) {
		    	$result = bmbdb_query("SELECT tid,toptype FROM {$database_up}threads WHERE `tid` in ($this_arr)");
		    	while (false !== ($detail = bmbdb_fetch_array($result))) {
		    		if (($detail['toptype'] == 8 && $key != "ALL") || ($detail['toptype'] == 9 && $key == "ALL")) {
		    			$new_topthread["$key"] .= $detail['tid'].",";
		    			$new_count_pint["$key"]++;
		    		}
		    	}
	    	}
	    }
	    $writetocache = "<?php \n";
	    
	    foreach ($new_topthread as $key=>$value) {
	    	$writetocache.= "\$topthread[\"$key\"] = '$value';\n";
	    	$writetocache.= "\$count_pint[\"$key\"] = ".$new_count_pint["$key"].";\n";
	    }
	}
    
    writetofile("datafile/cache/pin_thread.php", $writetocache);

} 
function update_sum()
{
    // ----------Update the sum------------
    global $database_up;
    $query = "SELECT COUNT(id) FROM {$database_up}posts";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $amoutnum = $fcount['COUNT(id)'];
    
    $query = "SELECT COUNT(id) FROM {$database_up}threads";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $threadnum = $fcount['COUNT(id)'];
    
    $nquery = "UPDATE {$database_up}lastest SET threadnum='{$threadnum}',postsnum='$amoutnum' WHERE pageid='index'";
    $result = bmbdb_query($nquery);
} 

