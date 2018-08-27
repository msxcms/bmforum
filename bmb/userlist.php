<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
require("datafile/config.php");
require("getskin.php");
require("include/template.php");

if ($member_list == 0) {
	error_page($gl[139], $gl[138], $gl[138], $gl[277]);

} 
require("header.php");
navi_bar($gl[139], $gl[138], '', 'no');

if (!empty($jumppage)) $gotpage = $jumppage;
else $gotpage = $page;

$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("userlist", $temfilename, $styleidcode, $lang_zone);


$sqls = "";
if ($orderby == "group") {
    $sqls = "ORDER BY ugnum";
} elseif ($orderby == "name") {
    $sqls = "ORDER BY username";
} elseif ($orderby == "date") {
    $sqls = "ORDER BY regdate";
} elseif ($orderby == "amount") {
    $sqls = "ORDER BY postamount DESC";
} elseif ($orderby == "score") {
    $sqls = "ORDER BY point DESC";
} else {
    $orderby = "";
}



$query = "SELECT COUNT(*) FROM {$database_up}userlist";
$result = bmbdb_query($query, 0);
$fcount = bmbdb_fetch_array($result);
$fcount = $fcount['COUNT(*)'];

if ($type == "") {
    $pageshow = $page;
    if (empty($page)) {
        $page = 0;
        $pageshow = 1;
    } 
    if (!empty($jumppage)) {
        $page = $jumppage-1;
        $pageshow = $page + 1;
    } 
    $page = $page * $userperpage;
    $count = $page + $userperpage;
    $n = floor($fcount / $userperpage) + 1;
    $query = "SELECT * FROM {$database_up}userlist $sqls LIMIT $page,$userperpage";
	eval(load_hook('int_userlist_notype'));
    $result = bmbdb_query($query);
} else {
    $query = "SELECT COUNT(*) FROM {$database_up}userlist WHERE ugnum='$type' ";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $fcount = $fcount['COUNT(*)'];

    $fileopenitx = "";
    $pageshow = $page;
    if (empty($page)) {
        $page = 0;
        $pageshow = 1;
    } 
    if (!empty($jumppage)) {
        $page = $jumppage-1;
        $pageshow = $page + 1;
    } 
    $page = $page * $userperpage;

    $count = $page + $userperpage;
    $n = floor($fcount / $userperpage) + 1;
    $query = "SELECT * FROM {$database_up}userlist WHERE ugnum='$type' $sqls LIMIT $page,$userperpage";
	eval(load_hook('int_userlist_stype'));
    $result = bmbdb_query($query);
}
while (false !== ($row = bmbdb_fetch_array($result))) {
    if ($row['username'] != "") {
        $dsinfo = explode("|", $usergroupdata[$row['ugnum']]);
        $uname = $dsinfo[0];
        $regdate = getfulldate($row['regdate']);
        $scores = floor($row['point'] / 10);
		
		$bmf_userlist[]=array($uname, $row['userid'], $row['username'], $regdate, $row['postamount'], $scores);
    } 
} 


if (empty($gotpage)) $gotpage = 1;
$nextpage = $gotpage + 1;
$previouspage = $gotpage-1;
$maxpagenum = $gotpage + 4;
$minpagenum = $gotpage-4;

$pagenumber = "<table class=\"tableborder_withoutwidth\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$pageshow}/$n&nbsp;&nbsp;</strong></td>";
$pagenumber .= "\n<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=';\"><a href=\"userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=\"><strong>&laquo;</strong></a></td>";
for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
    if ($i > 0 && $i <= $n) {
        if ($i == $gotpage) {
            $pagenumber .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>\n";
        } else {
            $pagenumber .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=$i';\"><a href=\"userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=$i\">$i</a></td>\n";
        } 
    } 
} 
$pagenumber .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className='pagenumber_2 pagenumber_2_onmouseover';\" onmouseout=\"javascript:this.className='pagenumber_2';\" onclick=\"window.location='userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=$n';\"><a href=\"userlist.php?orderby=$orderby&amp;type=$type&amp;list=$list&amp;jumppage=$n\"><strong>&raquo;</strong></a></td>";
$uginfo_list = "";
foreach ($usergroupdata as $key=>$value) {
    $deinfo = explode("|", $value);
    $uginfo_list.= "<option value='$key'>$deinfo[0]</option>";
} 
eval(load_hook('int_userlist_output'));
require($template_name);

require("footer.php");
exit;
