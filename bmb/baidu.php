<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
error_reporting(E_ALL ^ E_NOTICE);
include_once("datafile/config.php");
require("include/db/db_{$sqltype}.php");
bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);
include("datafile/cache/forumdata.php");
$forumidban = explode(",", $forumidbanned);
@include_once("datafile/hooks/list.php");
include("datafile/cache/usergroup.php");
@define("INBMFORUM", 1);
include("include/version.php");

@set_time_limit(5);

$timestamp = time();

header("Content-type:application/xml; charset=utf-8");

require_once("datafile/time.php");
global_usergroup();
if (empty($_GET['forumid'])) $infoqtype_g = "all";
else $infoqtype_g = $_GET['forumid'];

$prefix_file = ($bmfopt['rewrite']) ? "topic_" : "topic.php?filename=";


$forumid = $infoqtype_g;
$qbgcolor = "";

$line = $sxfourmrow;
for($i = 0;$i < $forumscount;$i++) {
    if ($forumid == $line[$i]['id'] && ($line[$i]['type'] != 'subforum' && $line[$i]['type'] != 'subselection' && $line[$i]['type'] != 'selection' && $line[$i]['type'] != 'forum')) {
        $forumidban[] = $forumid;
    } 
    if ($forumid == $line[$i]['id']) {
        $forumname = $line[$i]['bbsname'];
        $forumtitle = "- " . $forumname;
    } 
} 

$add_tag_sql = "";
$tagname = $_GET['tagname'];
if ($see_a_tags != 0 && $tagname) {
    $query = "SELECT * FROM {$database_up}tags WHERE tagname='$tagname' ORDER BY 'tagid' DESC LIMIT 1";
    $result = bmbdb_query($query); 
    // Tags Row
    $tag_row = bmbdb_fetch_array($result);
    $th_tags = substr($tag_row['filename'], 1); 
    // Threads
    $th_tags_ex = implode("','", explode(",", $th_tags));
    $add_tag_sql = " AND tid in('$th_tags_ex')"; 
    $forumtitle .= "- " . $tagname;
}
$minv = $bmfopt[view_newpost] ? $bmfopt[view_newpost] : 25; // Show 25 New Topics default

if (@in_array($forumid, $forumidban)) {
    exit;
} else {
    $echoinfo = '';
    $request_url = htmlspecialchars($_SERVER['REQUEST_URI']);
    $forumtitle = htmlspecialchars($forumtitle);

?><?php
    if ($forumid == "all") {
        
    
        $add_sql = "";
        for($i = 0;$i < $forumscount;$i++) {
            if ($sxfourmrow[$i][type] != "category" && ($sxfourmrow[$i][type] == 'subforum' || $sxfourmrow[$i][type] == 'subselection' || $sxfourmrow[$i][type] == 'selection' || $sxfourmrow[$i][type] == 'forum') && !$sxfourmrow[$i][forumpass] && $sxfourmrow[$i][forumpass] <> "d41d8cd98f00b204e9800998ecf8427e") {
                $forumnum[$sxfourmrow[$i][id]] = $sxfourmrow[$i][bbsname];
            } else {
                $add_sql .= "AND forumid!='{$sxfourmrow[$i][id]}'";
            } 
        } 
        if ($add_sql != "") $add_sql = "AND (forumid!='xxxxx' " . $add_sql;

        $query = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}threads p LEFT JOIN {$database_up}userlist u ON u.userid=p.authorid WHERE p.id=p.tid $add_sql) $add_tag_sql ORDER BY `changetime` DESC LIMIT 0,$minv";
        $result = bmbdb_query($query);

        while (false !== ($row = bmbdb_fetch_array($result))) {
            $multipage = '';
            $row['title']	= stripslashes($row['title']);
            $row['content'] = stripslashes($row['content']);
            $lencontent = strlen($row['content']);
            $title = $row['title'];
            $topic_author = $row['author'];
            $topic_date = $row['time'];
            $aaa = $row['ip'];
            $bym = $row['other1'];
            $bymuser = $row['other2'];
            $uploadfilename = $row['other3'];
            $editinfo = $row['other4'];
            $sellmoney = $row['other5'];
            $forumid = $row['forumid'];
            $usesign = $row['options'];
            $filename = $row['tid'];
            get_forum_info("");
            if ($view_list == 0) continue;
            if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e") continue;
            if ($spusergroup == "1" && $enter_this_forum == "0") continue;

            $forum_name = $forumnum["$row[forumid]"];

            $tmpdate = getfulldate_s($topic_date);
            $cdate = getfulldate_s($row['changetime']);
            if (!$update_date) $update_date = $cdate;
            $title = htmlspecialchars(html_entity_decode(str_replace("&nbsp;", " ", $title)));
           	$pick = ($row['islock'] == 2 || $row['islock'] == 3) ? 1 : 0;
	    	eval(load_hook('int_baidu_beforemaker'));
            if ($title != "" && $filename != "") {
                $echoinfo .= <<< eot
\n<item>
<link>$script_pos/{$prefix_file}$filename</link>
<title>$title</title> 
<pubDate>$tmpdate</pubDate> 
 <bbs:lastDate>$cdate</bbs:lastDate> 
 <bbs:reply>{$row['replys']}</bbs:reply> 
 <bbs:hit>{$row['hits']}</bbs:hit> 
 <bbs:mainLen>$lencontent</bbs:mainLen> 
 <bbs:boardid>$forumid</bbs:boardid> 
 <bbs:pick>$pick</bbs:pick> 

</item>
eot;
            } 
        } 
    } else {
    	$limit_sql = "";
    	
        $page = 1;

        get_forum_info("");
        
        if (!$topicnum) exit;
        
        $limit_sql = ($topicnum>$minv) ? "0,$minv" : "0,$topicnum";

        $query = "SELECT p.*,u.ugnum,u.postamount,u.point FROM {$database_up}threads p LEFT JOIN {$database_up}userlist u ON u.userid=p.authorid WHERE forumid='$forumid' and p.id=p.tid $add_tag_sql ORDER BY 'changetime' DESC LIMIT $limit_sql";
        $xresult = bmbdb_query($query);
        

        while (false !== ($row = bmbdb_fetch_array($xresult))) {
            $title = stripslashes($row['title']);
            $author = $row['username'];
            $content = stripslashes($row['content']);
            $lencontent = strlen($row['content']);
            $date = $row['time'];
            $aaa = $row['ip'];
            $bym = $row['other1'];
            $bymuser = $row['other2'];
            $uploadfilename = $row['other3'];
            $editinfo = $row['other4'];
            $sellmoney = $row['other5'];
            $forumid = $row['forumid'];
            $usesign = $row['options'];
            $filename = $row['tid'];
            if ($view_list == 0) continue;
            if ($forum_pwd <> "" && $forum_pwd <> "d41d8cd98f00b204e9800998ecf8427e") continue;
            if ($spusergroup == "1" && $enter_this_forum == "0") continue;

            $forum_name = $forumnum["$row[forumid]"];


            $tmpdate = getfulldate_s($date);
            $cdate = getfulldate_s($row['changetime']);
            if (!$update_date) $update_date = $cdate;
            $title = htmlspecialchars(html_entity_decode(str_replace("&nbsp;", " ", $title)));
           	$pick = ($row['islock'] == 2 || $row['islock'] == 3) ? 1 : 0;
	    	eval(load_hook('int_baidu_beforemaker'));

            if ($title != "" && $filename != "") {
$echoinfo.= <<< eot
\n<item>
<link>$script_pos/{$prefix_file}$filename</link>
<title>$title</title> 
<pubDate>$tmpdate</pubDate> 
 <bbs:lastDate>$cdate</bbs:lastDate> 
 <bbs:reply>{$row['replys']}</bbs:reply> 
 <bbs:hit>{$row['hits']}</bbs:hit> 
 <bbs:mainLen>$lencontent</bbs:mainLen> 
 <bbs:boardid>$forumid</bbs:boardid> 
 <bbs:pick>$pick</bbs:pick> 

</item>
eot;
            } 
        } 
    } 
} 

?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo<<< eot
<document xmlns:bbs="http://www.baidu.com/search/bbs_sitemap.xsd">
 <version>$verandproname</version> 
  <webSite>$script_pos/index.php</webSite> 
  <webMaster>$admin_email</webMaster> 
  <updatePeri>1</updatePeri> 
  <updatetime>$update_date</updatetime> 
eot;

?>
<?php echo $echoinfo;?>
</document>

<?php
function get_date_chi($datetime)
{
    global $minoffset, $time_1;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("Y/m/d/", $datetime);
} 
function getfulldate_s($datetime)
{
    global $minoffset, $time_1, $time_f;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("$time_f H:i:s", $datetime);
} 
function getfulldate($datetime)
{
    global $minoffset, $time_1;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("Y/m/d/H:i", $datetime);
} 
function get_time($datetime)
{
    global $minoffset, $time_1;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("H:i", $datetime);
} 
function get_date($datetime)
{
    global $minoffset, $time_1;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("Y/m/d", $datetime);
} 
// UNIX 时间转换
function get_stamp($last_str)
{
    list($last_date, $last_time) = explode(" ", $last_str);
    list($y, $m, $d) = explode("-", $last_date);
    list($h, $minute) = explode(":", $last_time);
    return mktime($h, $minute, 0, $m, $d, $y);
} 
function readfromfile($file_name)
{
    if (file_exists($file_name)) {
        $filenum = fopen($file_name, "r");
        flock($filenum, LOCK_SH);
        $file_data = @fread($filenum, @filesize($file_name));
        fclose($filenum);
        return $file_data;
    } 
} 
function writetofile($file_name, $data, $method = "w")
{
    $filenum = fopen($file_name, $method);
    flock($filenum, LOCK_EX);
    $file_data = fwrite($filenum, $data);
    fclose($filenum);
    return $file_data;
} 
function get_user_info($user, $type = "username")
{
    global $database_up;
    if ($type == "username") {
        $query = "SELECT * FROM {$database_up}userlist WHERE username='$user'";
    } elseif ($type == "usrid") {
        $query = "SELECT * FROM {$database_up}userlist WHERE userid='$user'";
    } 
    $result = bmbdb_query($query, 0);
    $row = bmbdb_fetch_array($result);
    return $row;
} 
function getUserInfo()
{
} 
function global_usergroup()
{
    global $p_read_post, $see_a_tags, $database_up, $view_list, $usergroupdata;

    list($groupname, $groupimg, $systemg, $canpost, $canreply, $canpoll, $canvote, $max_sign_length, $sign_use_bmfcode, $bmfcode_sign['pic'], $bmfcode_sign['flash'], $bmfcode_sign['fontsize'], $enter_tb, $send_msg, $max_post_length, $short_msg_max, $send_msg_max, $use_own_portait, $swf, $max_upload_size, $upload_type_available, $supermod, $admin, $groupimg2, $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, $opencutusericon, $openupusericon, $max_avatars_upload_size, $max_avatars_upload_post, $upload_avatars_type_available, $maxwidth, $maxheight, $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, $showpic, $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post, $member_list, $search_fun, $nwpost_list, $porank_list, $gvf, $see_amuser, $view_recybin, $post_allow_ww, $re_allow_ww, $poll_allow_ww, $vote_allow_ww, $enter_allow_ww, $pri_allow_ww, $forum_allow_ww, $recy_allow_ww, $read_allow_ww, $down_attach, $down_attach_ww, $set_a_tags, $see_a_tags, $max_tags_num, $min_post_length, $max_post_title, $max_post_des, $browse_add_point) = explode("|", $usergroupdata[6]);
} 
function get_forum_info()
{
    global $forumid, $topicnum, $forumscount, $sxfourmrow, $forum_pwd, $database_up, $enter_this_forum, $spusergroup, $p_read_post, $view_list, $usergroupdata, $usergroupfile;
    for($i = 0;$i < $forumscount;$i++) {
        if ($sxfourmrow[$i]['id'] == $forumid) {
            $detail = $sxfourmrow[$i];
        } 
    } 

    $forum_pwd = $detail['forumpass'];
    $guestpost = explode("_", $detail['guestpost']);
    $spusergroup = $detail['spusergroup'];
    $topicnum = $detail['topicnum'];

    if ($spusergroup == "1") {
        global $usertype;
        $uginfo = 6;
        $usergroupdata = explode("\n", $detail['usergroup']);
        $usertype = explode("|", $usergroupdata[$uginfo]);
        list($groupname, , , $canpost, $canreply, $canpoll, $canvote, , , , , , $enter_this_forum, , $max_post_length, , , , , $max_upload_size, $upload_type_available, , , , $mod, $max_upload_num, $html_codeinfo, $max_daily_upload_size, $logon_post_second, $post_sell_max, $del_true, $del_rec, $can_rec, $delrmb, $post_money, $deljifen, $post_jifen, $allow_upload, $max_upload_post, , , , , , , , $p_read_post, $view_list, $lock_true, $del_reply_true, $edit_true, $move_true, $copy_true, $ztop_true, $ctop_true, $uptop_true, $bold_true, $sej_true, $autorip_true, $ttop_true, $modcenter_true, $modano_true, $modban_true, $clean_true, , $post_money_reply, $post_jifen_reply, $del_self_topic, $del_self_post, $bmfcode_post['pic'], $bmfcode_post['reply'], $bmfcode_post['jifen'], $bmfcode_post['sell'], $bmfcode_post['flash'], $bmfcode_post['mpeg'], $bmfcode_post['iframe'], $bmfcode_post['fontsize'], $bmfcode_post['hpost'], $bmfcode_post['hmoney'], $allow_forb_ub, $can_visual_post) = explode("|", $usergroupdata[$uginfo]);
    } 
} 

function substr_rss($str, $start, $end)
{
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
    return join("", array_slice($info[0], $start, $end));
} 
function load_hook($hookname)
{
	global $hook_list, $hook_list_disabled;
	$evalcode = "";
	if ($hook_list["{$hookname}"]) {
	    foreach($hook_list["{$hookname}"] as $value)
	    {
	    	if ($hook_list_disabled["{$hookname}"]["$value"] != 1) $evalcode .= "include('include/hooks/{$hookname}.{$value}.php');";
	    }
	}
	
	return $evalcode;
}