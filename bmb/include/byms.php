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
define("NOTRASHLIMIT", 1);
include_once("include/bmbcodes.php");
include_once("include/common.inc.php");

$check_user = $check_noadmin = $repeat_bym = 0;

$aquery = "SELECT * FROM {$database_up}threads WHERE tid='$filename' LIMIT 0,1";
$xresult = bmbdb_query($aquery);
$nline = bmbdb_fetch_array($xresult);
$forumid = $nline[forumid];
tbuser();
$xfourmrow = $sxfourmrow;
if (!$nline['tid']) die("Invaild Post");
// ######## Administrator Check ##########
for($i = 0;$i < $forumscount;$i++) {
    if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    if ($xfourmrow[$i][id] == $row[forumid]) $bbsname = $xfourmrow[$i][bbsname];
} 
get_forum_info($xfourmrow);

if ($login_status == 1 && $check_user == 0 && $adminlist != "") {
    $arrayal = explode("|", $adminlist);
    $admincount = count($arrayal);
    for ($i = 0; $i < $admincount; $i++) {
        if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|" && $usertype[128] == 1) $check_user = 1;
    } 
} 
// ######## Administrator Check ##########
if ($usertype[21] == "1" && $usertype[128] == 1) $check_user = 1;
if ($usertype[22] == "1" && $usertype[128] == 1) $check_user = 1;
if ($usertype[125] == "1") $check_user = $check_noadmin = 1; 

if ($check_user == 0 || ($verify != $log_hash && $step == 2)) {
	if ($ajax_request == 1) ajax_browse_error($gl[217]);
    error_page($gl[218], $gl[219], $errc[0], "$errc[9]<br /><br />$gl[217]<br />");
} 

$query = "SELECT * FROM {$database_up}posts WHERE id='$article'";
$result = bmbdb_query($query);
$line = bmbdb_fetch_array($result);
$articlecontent = stripslashes($line['articlecontent']);
$articletitle = $line['articletitle'];
if (empty($line['tid'])) die("Invaild Post");

$bymuser	= substr($line['other2'], 1);
$scores_ex	= explode("|", $bymuser);
for ($si=0;$si < count($scores_ex);$si++){
	$detail_si = explode("_", $scores_ex[$si]);
	if (strtolower($username) == strtolower($detail_si[0])) $repeat_bym = 1;
}

if ($usertype[130] == 0 && $check_noadmin == 1 && $repeat_bym == 1) {
	if ($ajax_request == 1) ajax_browse_error($gl[220]);
    error_page($gl[218], $gl[219], $errc[0], "$errc[9]<br /><br />$gl[220]<br />");
} 

if (strtolower($username) == strtolower($line['username']) && $usertype[22] != "1" && $usertype[21] != "1") {
	if ($ajax_request == 1) ajax_browse_error($gl[221]);
    error_page($gl[218], $gl[219], $errc[0], "$errc[9]<br /><br />$gl[221]<br />");
} 

$max_fen_length = $usertype[122];
$min_fen_length = $usertype[121];
$max_money_length = $usertype[124];
$min_money_length = $usertype[123];


if ($step != 2) {
	eval(load_hook('int_byms_step0'));

    $time = getfulldate($line['timestamp']);
    if (utf8_strlen($articlecontent) > 300) $articlecontent = substr($articlecontent, 0, 294) . "......";

    $chooser_t = explode("\n", $choose_reason);
    $cou = count($chooser_t);
    $chooser_c = "<select name='reasonselection' onchange='document.formsr.reasons.value=document.formsr.reasonselection.options[document.formsr.reasonselection.selectedIndex].value;'>";
    for($i = 0;$i < $cou;$i++) {
        $chooser_c .= "<option value='{$chooser_t[$i]}'>{$chooser_t[$i]}</option>";
    } 
    $chooser_c .= "</select>";
	$select_sps = $select_mps = "";
	for ($i = $min_fen_length; $i <= $max_fen_length; $i++) {
		$pochecked = "";
		if ($i == 0) $pochecked = "selected='selected'";
	    $select_sps.= "<option $pochecked value='$i'>$i</option>\n";
	}

	for ($i = $min_money_length; $i <= $max_money_length; $i += 10) {
		$pochecked = "";
		if ($i == 0) $pochecked = "selected='selected'";
	    $select_mps.=  "<option $pochecked value='$i'>$i</option>\n";
	}

$score_core=<<<EOT
$gl[223]
<select name='selectpoint' onchange="document.formsr.bym1.value=document.formsr.selectpoint.options[document.formsr.selectpoint.selectedIndex].value;">
$select_sps
</select>
<input name='bym1' type="text" value="0" />
$gl[224]($min_fen_length <strong>——</strong> $max_fen_length )<br />
$gl[449]
<select name='selectmoney' onchange="document.formsr.modmoneys.value=document.formsr.selectmoney.options[document.formsr.selectmoney.selectedIndex].value;">
$select_mps
</select>
<input type="text" size="3" maxlength="4" name='modmoneys' value="0" /> $bbs_money ($min_money_length <strong>——</strong> $max_money_length )
<br />
$gl[452] $chooser_c<input name='reasons' type="text" size="40" value='' />

<input type="hidden" name="filename" value="$filename" />
<input type="hidden" name="forumid" value="$forumid" />
<input type="hidden" name="article" value="$article" />
<input type="hidden" name="step" value='2' />
<input type='hidden' name='verify' value='$log_hash' />
EOT;

	eval(load_hook('int_byms_output'));

	if ($ajax_request != 1) {
	    include("header.php");
	    print_bar();
?>
<script type="text/javascript">
//<![CDATA[ 
function validate(theform) {
if (theform.reasons.value=="" || theform.reasons.value=="") {
alert("<?php echo $gl[455];?>");
return false; 
} 
}
function change(theoption,objectname) {
this.formsr.objectname.value=theoption;
}
//]]>>
</script>
<form action="misc.php?p=byms"  name='formsr' onsubmit="return validate(this)"  method="post" style="margin:0px;">

<table border="0" cellspacing="0" cellpadding="0" align="center" class="tableborder">
 <tr>
 <td>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
   <tr> 
    <td class="tile_back_title"><strong><?php echo $gl[219];?></strong></td>
   </tr>
   <tr class="article_color1"> 
    <td><br />
<?php echo $gl[222];?> <strong><?php echo $articletitle ;?></strong> <?php echo $gl[225];?><?php echo $line['username'] ;?>  <?php echo $gl[226];?><?php echo $time ;?><br />
<br />
<div class="quote_dialog"><?php echo $articlecontent ;?>
</div>
<br />
<?php echo $score_core;?>
<br /><br /><input type="submit" value='<?php echo $gl[173];?>' />
<input type="hidden" name="page" value='<?php echo $page;?>' />



</td>
   </tr>
   <tr class="tile_back_title"> 
    <td>&nbsp;</td>
   </tr>
  </table>
 </td>
 </tr>
</table>
</form>
<?php
	    include("footer.php");
	} else {
		echo $score_core;
	}
    exit;
} 
$max_fen_length = $max_fen_length * 10;
$min_fen_length = $min_fen_length * 10;
$bym1 = $bym1 * 10;
if ($bym1 > $max_fen_length) {
    $bym1 = $max_fen_length;
} elseif ($bym1 < $min_fen_length) {
    $bym1 = $min_fen_length;
} 
if ($modmoneys > $max_money_length) {
    $modmoneys = $max_money_length;
} elseif ($modmoneys < $min_money_length) {
    $modmoneys = $min_money_length;
} 
if ($reasons) {
    $reasons = strip_tags(safe_convert(str_replace("_", "", $reasons)));
}
$realscores = $line['other1']+$bym1;
$newreason = str_replace('"',"", str_replace("'", "", "|{$username}_{$reasons}_{$bym1}_{$modmoneys}_{$timestamp}".$line['other2']));
bmbdb_query("UPDATE {$database_up}posts SET other2='$newreason',other1='$realscores' WHERE id='$article'");

if ($article == $line['tid']) bmbdb_query("UPDATE {$database_up}threads SET other2='$newreason',other1='$realscores' WHERE id='$article'");

bmfwwz($line['username'], $modmoneys, $bym1, "0");
$bym1 = floor($bym1 / 10);
// 用户威望操作日志
$bymchange = "$bym1($modmoneys)";

$query = "INSERT into {$database_up}potlog (pusername,pauthor,bymchange,pforumid,pfilename,particle,ptimestamp) 
values ('$username','{$line[username]}','$bymchange $reasons','$forumid','$filename','$article','$timestamp')";
$result = bmbdb_query($query);
// 用户威望操作日志结束

eval(load_hook('int_byms_suc'));

if ($beforeactionmess == "yes") {
	$articletitle = $articletitle ? $articletitle : "RE:".$nline['title'];
	$actionreason = $reasons;
	mtou($line['username'], "{$bym1}/{$modmoneys}", $articletitle);
} 
if ($ajax_request == 1) {
	echo $gl[228];
	exit;
} else {
jump_page("forums.php?forumid=$forumid", "$gl[227]",
    "<strong>$gl[228]</strong><br /><br />
			$gl[248] <a href='forums.php?forumid=$forumid'>$gl[4]</a> | <a href='topic.php?page=$page&forumid=$forumid&filename=$filename#p$article'>$gl[8]</a> | <a href='index.php'>$gl[5]</a>", 3);
}

function print_bar()
{
    global $forum_name, $gl, $usertype, $forumid;
    navi_bar($gl[218], "$gl[219]");
} 
function mtou($ruser, $action, $topic)
{
    global $id_unique, $userid, $filename, $username, $actionreason, $gl, $database_up, $timestamp, $bbs_title, $short_msg_max;
    $actionshow = $gl[513].$action;
	eval(load_hook('int_byms_mtou'));
	announce_user($ruser, $actionshow, $gl[426], "", $gl[427], $topic);
} 