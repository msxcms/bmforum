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
require("include/common.inc.php");
get_forum_info("");

$check_user = 0;
if ($login_status == 1 && $usertype[22] == "1") $check_user = 1;
$query = "SELECT * FROM {$database_up}forumdata WHERE id='$forumid' OR id='$forum_cid' OR id='$forum_upid'";
$result = bmbdb_query($query);
while ($adline = bmbdb_fetch_array($result)) {
    $adminlist .= $adline['adminlist'];
} 
if ($login_status == 1 && $check_user == 0 && $adminlist != "") {
    $arrayal = explode("|", $adminlist);
    $admincount = count($arrayal);
    for ($i = 0; $i < $admincount; $i++) {
        if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|") $check_user = 1;
    } 
} 

if ($usertype[22] == "1") $check_user = 1;
if ($usertype[21] == "1") $check_user = 1;
if (!$modban_true) $check_user = 0;
// ----让增加的管理员有权管理！----------
if ($check_user == 0 || ($verify != $log_hash && $action == "process")) {
    $content = "$gl[233]<br />
<br />$gl[217]<br /><ul>
<li><a href='javascript:history.back(1)'>$gl[15]</a></li>
</ul>";
    error_page($gl[230], "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>", $gl[53], $content);
    

} 

get_forum_info("");
$thisprog = "misc.php?p=badman";
$badmanfile = "datafile/badman/" . $forumid . ".php";
if ($action != "process") {
    if (file_exists($badmanfile)) {
        include($badmanfile);
        $badman = $badman ? implode("\r\n", $badman) : "";
    } else $badman = "";
    include("header.php");
    navi_bar($gl[230],
        "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>");
    print <<<EOT
<form style="margin:0px;" action="$thisprog" method="post"><input type='hidden' name='verify' value='$log_hash' />
<table border="0" cellspacing="0" cellpadding="0" align="center" class="tableborder">
 <tr>
 <td>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
   <tr class="tile_back_nowidth"> 
    <td class="tile_back_title"><strong>$gl[236]</strong></td>
   </tr>
   <tr class="article_color1"> 
		<td class="article_color1" valign="middle" colspan="2">
			<input type="hidden" name="action" value="process" />
	        <input type="hidden" name="forumid" value="$forumid" />

			<strong>$gl[237]</strong><br /><br />
			$gl[238]<br /><br /><br />
		</td>
	</tr>
	<tr>
		<td class="article_color1" valign="middle" colspan="2" style="text-align:center;">
			<strong>$gl[239]</strong><br /><br /><textarea cols="60" rows="6" name="userarray">$badman</textarea><br />
		</td>
	</tr>
	<tr>
		<td class="article_color2" valign="middle" align="center" colspan="2">
		<input type="submit" value="$gl[85]" /></td>
   </tr>
   <tr align="left" class="tile_back_nowidth"> 
    <td class="tile_back_nowidth">&nbsp;</td>
   </tr>
  </table>
 </td>
 </tr>
</table>

</form>


		
EOT;
    include("footer.php");
    exit;
} elseif ($action == "process") {

    $badman = "<?php\n";
    $userarray = str_replace("\n", "", $userarray);
    $userarray = explode("\r", $userarray);
    $count = count($userarray);
    for ($i = 0; $i < $count; $i++) {
    	if (check_name_valid($userarray[$i]))
    	{
        	$badman .= "\$badman[$i]='$userarray[$i]';\n";
        }
    } 
    writetofile($badmanfile, $badman);

    $userarray = implode("<br />", $userarray);
    include("header.php");
    navi_bar($gl[230],
        "<a href=\"forums.php?forumid=$forumid\">$forum_name</a>");
    
$info=<<<EOT
		<strong>$gl[241]</strong><br /><br />
		<strong>$userarray</strong><br /><a href="misc.php?p=badman&forumid=$forumid">$gl[242]</a>
EOT;

    msg_box($gl[236] ,$info);
    // Log
    $nquery = "insert into {$database_up}actlogs (actdetail,acter,actreason,acttime,forumid,actioncode) values ('$gl[487]','$username','','$timestamp','{$forumid}','badman')";
    $result = bmbdb_query($nquery);
    // =========
    include("footer.php");
    exit;
} 
