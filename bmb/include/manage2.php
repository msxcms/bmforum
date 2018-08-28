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
include_once("lang/$language/topic.php");
include_once("include/common.inc.php");

if (empty($filename) || (($action == "recoverpost" || $action == "recyclepost" || $action == "del") && empty($article)) || ($action != "del" && $action != "btfront" && $action != "holdfront" && $action != "recyclepost" && $action != "recoverpost" && $action != "unhold")) {
    $content = "$gl[233]<br /><br />$gl[229]";
	error_page("$gl[230]", "MA Program", $gl[53], $content);

} 
// ---------check-----------
$check_user = 0;

$query = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$result = bmbdb_query($query);
$row = bmbdb_fetch_array($result);
$forumid = $row['forumid'];
$toptype = $row['toptype'];
if (!$row['tid']) exit;
tbuser();
if ($row['ttrash'] == 1) {
    $checktrash = "yes";
} 

get_forum_info("");

if ($login_status == 1 && $username == $admin_name) $check_user = 1;
// ######## 检测是否为管理员开始 ##########
$check_user = check_admin_permission($sxfourmrow, $forumscount, $forumid, $login_status, $check_user, $username);
// ######## 检测是否为管理员结束 ##########
$aquery = "SELECT * FROM {$database_up}posts WHERE id='$article' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$nline = bmbdb_fetch_array($xresult);
$author = $nline['username'];

if ($login_status == 1 && $author == $username && $del_self_post == 1 && $action == "del") $check_user = 1;
if ($usertype[22] == "1" || $usertype[21] == "1") $check_user = 1;
if ($uptop_true != "1" && $action == 'btfront') $check_user = 0;
if ($ttop_true != "1" && ($action == 'holdfront' || $action == 'unhold')) $check_user = 0;
if ($del_self_post != "1" && $action == 'del' && $nline['username'] != $username) $check_user = 0;
if ($usertype[128] != "1" && $action == "recyclepost") $check_user = 0;
if ($can_rec != "1" && $action == "recoverpost") $check_user = 0;
if (!$del_rec && $action == "del" && $nline['posttrash'] == '1') $check_user = 0;

if ($checktrash == "yes") {
    // ---to check if the user have got the permission to post-------
    $content = "$gl[233]<br /><br />$gl[310]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

}

if ($verify != $log_hash && $step == 2 && !$_POST['article']) $check_user = 0;

if ($check_user == 0) {
    $content = "$gl[233]<br /><br />$gl[217]";
	error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);

} 


if (empty($step)) {
    include("header.php");
    navi_bar($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>");
    print_confirm();
    include("footer.php");
    exit;
} 
if ($author == $username) $delusernum = "yes";
if ($step) {
    if ($action == "btfront") {
		$btfront_timestamp = ($acttype == "back") ?  "changetime-".($backdays*86400) : $timestamp;
		
        $query = "UPDATE {$database_up}threads SET changetime=$btfront_timestamp WHERE tid='$filename'";
        $result = bmbdb_query($query);
    } elseif ($action == "holdfront") {
    	if ($toptype > 1) $settoptype = ""; else $settoptype = ",toptype=1";
        $query = "UPDATE {$database_up}threads SET type=type+3{$settoptype} WHERE tid='$filename'";
        $result = bmbdb_query($query);

        $nquery = "UPDATE {$database_up}forumdata SET pincount=pincount+1 WHERE id='$forumid'";
        $result = bmbdb_query($nquery);
        refresh_forumcach(); 

    } elseif ($action == "recyclepost") {
        if ($article == "multi") {
        	if(is_array($delpost)){
        		$count_tp = count($delpost);
        	}
        	if ($count_tp > 0) {
				foreach($delpost as $key=>$value) {
					$ids_r .= ",'".substr($key, 1)."'";
				}
				$ids_r = substr($ids_r, 1);
				$r_tp = bmbdb_query("UPDATE {$database_up}posts SET posttrash='1' WHERE id in($ids_r) AND posttrash='0'");
			}
        } else {
	        bmbdb_query("UPDATE {$database_up}posts SET posttrash='1' WHERE id='$article'");
	    } 
    } elseif ($action == "recoverpost") {
        if ($article == "multi") {
        	if(is_array($delpost)){
        		$count_tp = count($delpost);
        	}
        	if ($count_tp > 0) {
				foreach($delpost as $key=>$value) {
					$ids_r .= ",'".substr($key, 1)."'";
				}
				$ids_r = substr($ids_r, 1);
				$r_tp = bmbdb_query("UPDATE {$database_up}posts SET posttrash='0' WHERE id in($ids_r) AND posttrash='1'");
			}
        } else {
	        bmbdb_query("UPDATE {$database_up}posts SET posttrash='0' WHERE id='$article'");
    	}
    } elseif ($action == "unhold") {
    	if ($toptype > 1) $settoptype = ""; else $settoptype = ",toptype=0";
        $query = "UPDATE {$database_up}threads SET type=type-3{$settoptype} WHERE tid='$filename' AND type>=3";
        $result = bmbdb_query($query);
        
        $nquery = "UPDATE {$database_up}forumdata SET pincount=pincount-1 WHERE id='$forumid'";
        $result = bmbdb_query($nquery);
        refresh_forumcach(); 
    } 

    if ($action == "del") {
        if ($article == "multi") {
        	if($delpost) {
	            while (false !== ($delyesno = @current($delpost))) {
	                $posti = $delyesno;
	                if ($posti == 1) {
	                	$thisaid = str_replace("n", "", key($delpost));
	                	if ($thisaid != $filename) {
		                    $aquery = "SELECT * FROM {$database_up}posts WHERE id='$thisaid'";
		                    $aresult = bmbdb_query($aquery);
		                    $xline = bmbdb_fetch_array($aresult);
		                    $thisistid = $xline['tid'];
		                    $deledpostnum++;
		                    $uploadfilename = $xline['other3'];
		                    if (strpos($uploadfilename, "×")) {
		                        $attachshow = explode("×", $uploadfilename);
		                        $countas = count($attachshow)-1;
		                    } else {
		                        $attachshow[0] = $uploadfilename;
		                        $countas = 1;
		                    } 
		                    $uploadfileshow = "";
		                    for ($ias = 0;$ias < $countas;$ias++) {
		                        $showdes = explode("◎", $attachshow[$ias]);
		                        @unlink("upload/{$showdes[0]}");
		                    } 
		                    if ($action == "del" && $delusernum == "yes") {
		                        bmfwwz($xline['username'], "-$delrmb", "-$deljifen", "-1", "", "1"); //--删回复贴后扣积分，发贴数--
		                    } 
		                    $query = "DELETE FROM {$database_up}posts WHERE id='$thisaid'";
		                    $result = bmbdb_query($query);
		                }
	                } 
	                next($delpost);
	            } 
	        }
            
            if (empty($deledpostnum)) {
        	
				$cxquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
				$cxresult = bmbdb_query($cxquery);
				$cxline = bmbdb_fetch_array($cxresult);
	        	$nowreplys = $cxline['replys'];
        	
				$aquery = "SELECT COUNT(*) FROM {$database_up}posts WHERE tid='$filename' AND id!='$filename'";
				$aresult = bmbdb_query($aquery);
				$line = bmbdb_fetch_array($aresult);
				$deledpostnum = $cxline['replys']-$line['COUNT(*)'];
				$thisistid=$filename;
            }
            
        } elseif ($article == "all") {
      	
            $aquery = "SELECT COUNT(*) FROM {$database_up}posts WHERE tid='$filename' AND id!='$filename'";
            $aresult = bmbdb_query($aquery);
            $line = bmbdb_fetch_array($aresult);
            $deledpostnum = $line['COUNT(*)'];
            $thisistid = $filename;

            $aquery = "DELETE FROM {$database_up}posts WHERE tid='$filename' AND id!='$filename'";
            $aresult = bmbdb_query($aquery);
        } else {
            $deledpostnum = 1;
            $aquery = "SELECT * FROM {$database_up}posts WHERE id='$article'";
            $aresult = bmbdb_query($aquery);
            $line = bmbdb_fetch_array($aresult);
            $thisistid = $line['tid'];
            $uploadfilename = $line['other3'];
            if (strpos($uploadfilename, "×")) {
                $attachshow = explode("×", $uploadfilename);
                $countas = count($attachshow)-1;
            } else {
                $attachshow[0] = $uploadfilename;
                $countas = 1;
            } 
            $uploadfileshow = "";
            for ($ias = 0;$ias < $countas;$ias++) {
                $showdes = explode("◎", $attachshow[$ias]);
                @unlink("upload/{$showdes[0]}");
            } 

            $query = "DELETE FROM {$database_up}posts WHERE id='$article'";
            $result = bmbdb_query($query);

            if ($action == "del" && $delusernum == "yes") {
                bmfwwz($line['username'], "-$delrmb", "-$deljifen", "-1", "", "1"); //--删回复贴后扣积分，发贴数--
            } 
        } 
        // Lastest Reply == START
        $aaquery = "SELECT articletitle,username,usrid,timestamp FROM {$database_up}posts WHERE tid='$thisistid' ORDER BY `changtime` DESC LIMIT 0,1";
        $aaresult = bmbdb_query($aaquery);
        
        $articletitle_reply = stripslashes(strreply("RE:", "RE:" . $row['title']));
        
        while (false !== ($aaline = bmbdb_fetch_array($aaresult))) {
            if (empty($addinfos)) $addinfos = ($aaline['articletitle'] ? $aaline['articletitle'] : $articletitle_reply) . "," . $aaline['username'] . "," . $aaline['timestamp'];
            if (empty($truechangtime)) $truechangtime = $aaline['timestamp'];
        } 
        
        if ($noheldtop != 1) $changadd = "changetime=$truechangtime,";

        $nquery = "UPDATE {$database_up}threads SET  replys= replys-$deledpostnum,{$changadd}lastreply='$addinfos' WHERE tid='$thisistid'";
        $result = bmbdb_query($nquery);

        $cxquery = "SELECT * FROM {$database_up}threads WHERE forumid='$forumid' AND ttrash!='1' ORDER BY `changetime` DESC LIMIT 0,1";
        $cxresult = bmbdb_query($cxquery);
        $cxline = bmbdb_fetch_array($cxresult);

        $lastinfos = explode(",", $cxline['lastreply']);
        $nquery = "UPDATE {$database_up}forumdata SET $pinsql replysnum = replysnum-$deledpostnum,fltitle = '{$lastinfos[0]}',flfname = '{$cxline['id']}',flposter = '{$lastinfos[1]}',flposttime = '{$lastinfos[2]}' WHERE id='$forumid'";
        $result = bmbdb_query($nquery);
        refresh_forumcach(); 
        // Lastest Reply == END
    } 
    // log this action
    $row['author'] = $author ? $author : $row['author'];
    
    if ($article != $filename) $row['title'] = "RE:".$row['title']." (ID:$article)";
    if ($beforeactionmess == "yes") {
        mtou($row['author'], $action, $row['title']);
    } 

    $showinfo = "{$row['title']}({$row['author']})";
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$showinfo','$username','$actionreason','$timestamp','{$row['forumid']}','m2$action')";
    $result = bmbdb_query($nquery);
    //finish
    jump_page("forums.php?forumid=$forumid", "$gl[2]",
        "<strong>$gl[2]</strong><br /><br />$gl[231] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?forumid=$forumid&filename=$filename'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
} 

function print_confirm()
{
    global $forumid, $filename, $log_hash, $author, $choose_reason, $username, $article, $action, $gl;
    $title = "$gl[173]";
    $chooser_t = explode("\n", $choose_reason);
    $cou = count($chooser_t);
    $chooser_c = "<select name='reasonselection' onchange='document.reasons.actionreason.value=document.reasons.reasonselection.options[document.reasons.reasonselection.selectedIndex].value;'>";
    for($i = 0;$i < $cou;$i++) {
        $chooser_c .= "<option value='{$chooser_t[$i]}'>{$chooser_t[$i]}</option>";
    } 
    $chooser_c .= "</select>";
    $content = "<script type=\"text/javascript\">
//<![CDATA[ 
function validate(theform) {
if (theform.actionreason.value==\"\" || theform.actionreason.value==\"\") {
alert(\"$gl[455]\");
return false; } }
function change(theoption) {
this.reasons.actionreason.value=theoption;
}
//]]>>
</script>
";
// process action
	if ($action == "btfront") {
		$btfront_content = "<input type='radio' checked='checked' name='acttype' value='front' />$gl[509]<br/>
		<input type='radio' name='acttype' value='back' />$gl[510]";
	}
	
    if ($action != "del") {
        $content .= "
<form name='reasons' onsubmit=\"return validate(this)\" action=\"misc.php?p=manage2\" method='post'>$gl[234]<br /><br />{$btfront_content}<br /><br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' /><br /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
    <input type='hidden' name='action' value='$action' />
    <input type='hidden' name='filename' value='$filename' />
    <input type='hidden' name='forumid' value='$forumid' />
    <input type='hidden' name='article' value='$article' />
    <input type='hidden' name='step' value='2' /><input type='hidden' name='verify' value='$log_hash' /></form>";
        
    } else {
        $content .= "<form name='reasons' onsubmit=\"return validate(this)\" action=\"misc.php?p=manage2\" method='post'>$gl[234]<br /><br />$gl[235]<br /><br /><input type='checkbox' name='beforeactionmess' value='yes' checked='checked' />$gl[425]<br />$gl[452] $chooser_c<input type='text' name='actionreason' />
";
        if ($author != $username && $article != "all") {
            $content .= "<br /><input type='checkbox' name='delusernum' value='yes' checked='checked' />$gl[450]";
        } 
        $content .= "<br /><br /><input type='submit' value='$gl[173]' class='btn btn-primary' /><br /><br />
<input type='hidden' name='action' value='$action' />
<input type='hidden' name='filename' value='$filename' />
<input type='hidden' name='forumid' value='$forumid' />
<input type='hidden' name='article' value='$article' />
<input type='hidden' name='step' value='2' /><input type='hidden' name='verify' value='$log_hash' /></form>";
    } 
    msg_box($title, $content);
} 

function mtou($ruser, $action, $topic)
{
    global $id_unique, $userid, $filename, $username, $actionreason, $database_up, $tfshow, $gl, $timestamp, $bbs_title, $short_msg_max;
    if ($action == "holdfront") {
        $actionshow = "$gl[471]";
    } elseif ($action == "unhold") {
        $actionshow = "$gl[472]";
    } elseif ($action == "btfront") {
        $actionshow = "$gl[473]";
    } elseif ($action == "recyclepost") {
        $actionshow = "$gl[453]";
    } elseif ($action == "recoverpost") {
        $actionshow = "$gl[490]";
    } elseif ($action == "del") {
        $actionshow = "$gl[474]";
    } 
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 
function strreply ($reprefix, $content, $length = 3 )
{
    if (substr($content, 0, $length * 2) == $reprefix.$reprefix) {
        return substr($content, $length);
    } else {
        return $content;
    }
}
