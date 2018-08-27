<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright infomation.
 A SourceForge Project - GNU Licence project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
// 调用方法：
//  <script src="http://www.yourweb.com/xxx/js_viewnew.php?forumid=论坛ID&length=标题最大长度&num=显示新帖数" type="text/javascript" charset="utf-8"></script>
//-----------------------------------------------------------
@header("Content-Type: text/html; charset=utf-8");
require("datafile/config.php");
if (!defined('CONFIGLOADED')) die("Access Denied");
require("include/db/db_{$sqltype}.php");
bmbdb_connect($db_server,$db_username,$db_password,$db_name);

$length = $_GET['length'];
$forumid = $_GET['forumid'];
$num = $_GET['num'];

$forumnum=$forumid;

include_once("datafile/cache/forumdata.php");

for($i=0;$i<$forumscount;$i++) {
	if($sxfourmrow[$i]['id']==$forumid) {
		$detail=$sxfourmrow[$i];
	}
}
$forum_name=$detail['bbsname'];
$detail[0]=$detail['type'];
if($detail[0]=='subforum' || $detail[0]=='subselection' || $detail[0]=='selection' || $detail[0]=='forum') $right=1;
	else exit;

echo "document.write(\"<table border=0 cellpadding=1 cellspacing=0 width=98% align=center><tbody><tr><td align=center height=22 ><b>{$forum_name} 共有<font color=red><b> $num </b></font>&nbsp;  篇</B></td><table border=0 cellpadding=1 cellspacing=0 width=98% align=center>";
echo "</tr><tr><td><table bgcolor=#0099cc border=0 cellpadding=1 cellspacing=0 width=100%><tbody><tr><td><table border=0 cellpadding=5 cellspacing=0 width=100%><tbody><tr bgcolor=#FFFFFF class=c><td><p align=center></p>";
$query = "SELECT * FROM {$database_up}threads WHERE forumid='$forumid' ORDER BY 'changetime' DESC LIMIT 0,$num";
$xresult = bmbdb_query($query);
	
while(false!==($row = bmbdb_fetch_array($xresult))){
	$smalltitle = (utf8_strlen($row['title'])>$length) ? (substrfor($row['title'], 0, $length)."...") : $row['title'];
	$row['title'] = str_replace("%a%","",$row['title']);
	echo "<a target='_blank' href='{$script_pos}/topic.php?filename={$row['id']}' title='{$row['title']}'>$smalltitle</a><br><br>";
} 
echo "<p align=center>";
echo "<a target='_blank' href='{$script_pos}/forums.php?forumid=$forumid'>更多……";
echo "</a></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>\");";
function utf8_strlen($str) {
 return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $dummy);
}
// UTF-8 substr
function substrfor($str, $start, $end)
{
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", bmb_unhtmlentities($str), $info);
    return join("", array_slice($info[0], $start, $end));
} 
function bmb_unhtmlentities($string) 
{
	if (@function_exists("html_entity_decode")) {
        return html_entity_decode($string);
	} else {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        return strtr($string, $trans_tbl);
    }
}