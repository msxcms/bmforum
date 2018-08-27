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
//  <script src="http://www.your.com/bmb/js_sviewnews.php?minv=显示最近X个帖子&length=标题最大长度" type="text/javascript" charset="utf-8"></script>
//-----------------------------------------------------------           
@header("Content-Type: text/html; charset=utf-8");
require("datafile/config.php"); 
require("getskin.php");

if($minv>"100") $minv=100;

$nquery = "SELECT * FROM {$database_up}forumdata";
$nresult = bmbdb_query($nquery);
$add_sql="";
while (false!==($nrow = bmbdb_fetch_array($nresult))) {
	if ($nrow[type]!="category" && ($nrow[type]=='subforum' || $nrow[type]=='subselection' || $nrow[type]=='selection' || $nrow[type]=='forum') && !$nrow[forumpass] && $nrow[forumpass]<>"d41d8cd98f00b204e9800998ecf8427e") {
		$forumnum[$nrow[id]]=$nrow[bbsname];
	}else{
		$add_sql.="&& forumid!='{$nrow[id]}'";
	}
}
if($add_sql!="") $add_sql="WHERE forumid!='xxxxx' ".$add_sql;
echo "document.write(\"<table cellspacing=1 cellpadding=0 width=$tablewidth border=0 align=center background=$tile_back>";
$query = "SELECT * FROM {$database_up}threads $add_sql ORDER BY `changetime` DESC LIMIT 0,$minv";
$result = bmbdb_query($query);

while (false!==($row=bmbdb_fetch_array($result))) {
	$multipage=''; 
//	list($forumid,$title,$author,$date,$des,$icon,$filename,$reply,$hit,$last_mod_data,$islock,$topic_type,$jihua)=explode("|",$key);
	get_forum_info("");
	$reply=$row['replys'];
	$topic_type = @trim($row['type']);
	$topic_islock = @trim($row['islock']);
//	if (file_exists("{$idpath}forum$forumid/$filename") && $filename!="") $articlelist=file("{$idpath}forum$forumid/$filename");
//	list($topic_name,$topic_author,$topic_content,$topic_date,$topic_area,$topic_icon,$topic_usesign,$topic_bym,$topic_bymuser,$topic_uploadfilename,$topic_editinfo,$sell)=explode("|",$articlelist[0]);
	$toplang=utf8_strlen($row['content']);
	if (utf8_strlen($row['author'])>=12) $viewauthor=substr($row['author'],0,9).'...';
		else $viewauthor=$row['author'];
	$icon=$row['face'];

	
    if (($icon == "ran" || $icon == "") && $emotrand == 1) {
        $icon = mt_rand(0, 52) . '.gif';
        $icon = "<a target='_blank' href='$script_pos/topic.php?filename={$row['tid']}'><img src='$script_pos/images/emotion/$icon' alt='$gl[160]' border='0' /></a>";
    } elseif (($icon == "ran" || $icon == "") && $emotrand != 1) {
        $icon = "&nbsp;";
    } else {
        $icon = "<a target='_blank' href='$script_pos/topic.php?filename={$row['tid']}'><img src='$script_pos/images/emotion/$icon' alt='$gl[160]' border='0' /></a>";
    } 


	if ($topic_type==1) {
		$stats="<img src='$script_pos/$otherimages/system/statistic.gif'>";
		if ($islock==1) $stats="<img src='$script_pos/$otherimages/system/closesta.gif'>";
	} elseif ($topic_type==2) {
		$stats="<img src='$script_pos/$otherimages/system/ucommend.gif'>";
		if ($islock==1) $stats="<img src='$script_pos/$otherimages/system/closeu.gif'>";
		if ($jihua==1) $stats="<img src='$script_pos/$otherimages/system/jihua.gif'>";
	} elseif ($topic_type>=3) {
		$stats="<img src='$script_pos/$otherimages/system/holdtopic.gif'>";
	} else {
		if ($username!=$author) {
			$stats="<img src='$script_pos/$otherimages/system/topicnew.gif'>";
			if ($reply>=10) $stats="<img src='$script_pos/$otherimages/system/topichot.gif'>";
			if ($islock==1) $stats="<img src='$script_pos/$otherimages/system/topiclocked.gif'>";
		} else {
			$stats="<img src='$script_pos/$otherimages/system/mytopicnew.gif'>";
			if ($reply>=10) $stats="<img src='$script_pos/$otherimages/system/mytopichot.gif'>";
			if ($islock==1) $stats="<img src='$script_pos/$otherimages/system/mytopiclocked.gif'>";
		}
	}
//-------if more than one page-----------
	if ($row['replys']+1>$read_perpage) {
		if (($reply+1)%$read_perpage==0) $maxpageno=($reply+1)/$read_perpage;
			else $maxpageno=floor(($reply+1)/$read_perpage)+1;

		$multipage="[ <b style='font-size:7pt;font-family:verdana;'>";
		for ($i=1; $i<=$maxpageno; $i++) {
			$multipage.=" <a target='_blank' style='color:505060' href='$script_pos/topic.php?filename={$row['tid']}&page=$i'>$i</a>";
			if ($i==5) {$multipage.=" . . . <a target='_blank' style='color:000066' href='$script_pos/topic.php?filename={$row['tid']}&page=last'>$maxpageno</a>"; break;}
		}
		$multipage.='</b> '.$gl[146].']';
	}
	$smalltitle = (utf8_strlen($row['title'])>$length) ? substrfor($row['title'], 0, $length)."..." : $row['title'];
	$title="<a target='_blank' title='{$row['title']}' href='$script_pos/topic.php?filename={$row['tid']}'>{$smalltitle}</a>";
	$lmd=explode(",",$row['lastreply']);
	$g=$timestamp-$lmd[2];
	if ($g<=3600) $title.="  <img src='".$script_pos."/".$otherimages."/system//newred.gif'>";
	elseif ($g<=86400) $title.="  <img src='".$script_pos."/".$otherimages."/system//newblue.gif'>";
	elseif ($g<=172800) $title.="  <img src='".$script_pos."/".$otherimages."/system//newgreen.gif'>";
	if ($lmd[2]==$date) $lmdauthor="------";
	else $lmdauthor="<a target='_blank' href='$script_pos/profile.php?job=show&target=".urlencode($lmd[1])."'>$lmd[1]</a>";
	
	$time_tmp=getfulldate($lmd[2]);
	if ($time_2){
		$timetmp_a=$timestamp-$lmd[2];
		$lmdtime=get_add_date($timetmp_a);
		if($lmdtime=="getfulldate"){
			$lmdtime=$time_tmp;
		}
	}else{
		$lmdtime=$time_tmp;
	}
	$forum_name=$forumnum["$row[forumid]"];
	echo " <tr height=22 bgcolor=$forumcolorone><td align=center width=24 bgcolor=$forumcolortwo>$stats</td><td  bgcolor=$forumcolorone>&nbsp;$icon&nbsp;{$title} $multipage </td> <td  align=center bgcolor=$forumcolortwo><a target='_blank' href=$script_pos/forums.php?forumid={$row['forumid']}>{$forum_name}</a></td> <td  align=center bgcolor=$forumcolorone><a target='_blank' href=$script_pos/profile.php?job=show&target=".urlencode($row[author]).">{$row['author']}</a></td><td align=center bgcolor=$forumcolortwo>{$row['replys']}</td><td align=center bgcolor=$forumcolortwo>{$row['hits']}</td><td  align=left bgcolor=$forumcolorone >&nbsp;<img src=$script_pos/$otherimages/system/icon_post.gif border=0>&nbsp;{$lmdtime}&nbsp;<font color=#000066>|</font>&nbsp;$lmdauthor</td></tr>"; 
}
echo "</table>\");";
?>
