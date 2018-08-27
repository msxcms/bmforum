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

$thisprog = "setforum.php";
$forumfile = "datafile/forumdata.php";

if ($useraccess != "1" || $admgroupdata[3] != "1") {
    adminlogin();
} 
$newstring = "";
print<<<EOT
<tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[189]</strong>
    </td></tr>
  <tr bgcolor=FFC96B>
   <td colspan=2 style="border: #C47508 1px soild;"><a name="top"></a><a href="#section1">$arr_ad_lng[550]</a> | <a href="#section2">$arr_ad_lng[561]</a> | <a href="#section3">$arr_ad_lng[563]</a> | <a href="#section4">$arr_ad_lng[564]</a> | <a href="#section5">$arr_ad_lng[958]</a></td>
  </tr>
EOT;

$t = time();

if (empty($action)) {
    print <<<EOT
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section1"></a>$arr_ad_lng[550]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
$table_stop
EOT;
    $old_tab_bottom = $tab_bottom;
    $forumselect = $forumonly = $catselect = $tab_bottom = "";
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] == "category") {
        	$forumselect .= "<option value=\"{$fourmrow['id']}\">== {$fourmrow['bbsname']} ==</option>";
            echo "{$tab_bottom}<br />{$tab_top}<strong>$arr_ad_lng[551]</strong> {$fourmrow['bbsname']} <a href=\"admin.php?bmod=modforum.php&job=modify&target={$fourmrow['id']}\">$arr_ad_lng[552]</a> | <a href=\"admin.php?bmod=modforum.php&action=maction&job=delete&targetid={$fourmrow['id']}&bbname=" . urlencode($fourmrow['bbsname']) . "\">$arr_ad_lng[553]</a> | <a href=\"admin.php?bmod=addforum.php&scateid={$fourmrow['id']}\">$arr_ad_lng[1058]</a><br />";
        } else {
            if ($fourmrow['blad'] == "") {
            	$forumselect .= "<option value=\"{$fourmrow['id']}\">&nbsp;&nbsp;{$fourmrow['bbsname']}</option>";
                echo "{$tab_bottom}{$tab_top}&nbsp;&nbsp;&nbsp;&nbsp;<strong>$arr_ad_lng[554]</strong> {$fourmrow['bbsname']}  <br />&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"admin.php?bmod=modforum.php&job=modify&target={$fourmrow['id']}\">$arr_ad_lng[555]</a> | <a href=\"admin.php?bmod=modforum.php&action=maction&job=delete&targetid={$fourmrow['id']}&bbname=" . urlencode($fourmrow['bbsname']) . "\">$arr_ad_lng[556]</a> | <a href=\"admin.php?bmod=addforum.php&scateid={$fourmrow['forum_cid']}&sparentid={$fourmrow['id']}\">$arr_ad_lng[1058]</a> | <a href=\"admin.php?bmod=usergroup.php&targetid={$fourmrow['id']}\">$arr_ad_lng[557]</a><br />";
            } else {
            	$forumselect .= "<option value=\"{$fourmrow['id']}\">&nbsp;&nbsp;&nbsp;&nbsp;{$fourmrow['bbsname']}</option>";
                echo "{$tab_top}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$arr_ad_lng[558]</strong> {$fourmrow['bbsname']}  <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"admin.php?bmod=modforum.php&job=modify&target={$fourmrow['id']}\">$arr_ad_lng[559]</a> | <a href=\"admin.php?bmod=modforum.php&action=maction&job=delete&targetid={$fourmrow['id']}&bbname=" . urlencode($fourmrow['bbsname']) . "\">$arr_ad_lng[560]</a> | <a href=\"admin.php?bmod=addforum.php&scateid={$fourmrow['forum_cid']}&sparentid={$fourmrow['id']}\">$arr_ad_lng[1058]</a> | <a href=\"admin.php?bmod=usergroup.php&targetid={$fourmrow['id']}\">$arr_ad_lng[557]</a>{$tab_bottom}<br />";
            } 
        } 
        $tab_bottom = $old_tab_bottom;
    } 
    if (empty($forumselect)) {
       echo "{$tab_top}<a href=\"admin.php?bmod=addforum.php\">$arr_ad_lng[563]</a>{$tab_bottom}<br />";
       $tab_bottom = $old_tab_bottom;
    }

    echo "{$tab_bottom}";

    $forumselect .= "</select>";

    print <<<EOT
<script src="images/bmb_ajax.js"></script>
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section2"></a>$arr_ad_lng[561]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
    $table_stop
    <a href=admin.php?bmod=setforum.php&action=ffindex>$arr_ad_lng[562]</a>
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section3"></a><a href="admin.php?bmod=addforum.php" style="color:#FFFFFF;">$arr_ad_lng[563]</A></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
</td></tr>
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section4"></a>$arr_ad_lng[564]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
$table_stop
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="modifyorder">
    
<select multiple size=8 style="width: 50%;" name="list2" style="width: 120px">
$forumselect
<br />
<input type="button" value="$arr_ad_lng[1032]" onclick="Moveup(this.form.list2)" name="B3">
<input type="button" value="$arr_ad_lng[1033]" onclick="Movedown(this.form.list2)" name="B4">
<input type="button" onclick="GetOptions(this.form.list2, 'admin.php?bmod=$thisprog&action=modifyorder')" value="$arr_ad_lng[774]"> <input type=reset value="$arr_ad_lng[407]">
$arr_ad_lng[1034]


    </form>
    $table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section5"></a>$arr_ad_lng[958]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
$table_stop
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="deltags">
    $arr_ad_lng[957] <br /><input size=50 type=text name='tagsname'> <input type=submit value="$arr_ad_lng[66]">
    $table_stop</form>

EOT;
    exit;
} elseif ($action == "deltags") {
    // Create OR Delete Tags
    $deltags_ex = explode(" ", strtolower($tagsname));
    $count = count($deltags_ex);
    if (!$tagsname) $count = 0;
    if ($act == "create") {
    	for ($i = 0; $i < $count; $i++) {
            $nquery = "REPLACE INTO {$database_up}tags (tagname,filename) VALUES ('$deltags_ex[$i]','')";
            $result = bmbdb_query($nquery);
        }
    } else {
        $sqldeltags = implode ("','", $deltags_ex);
        $nquery = "DELETE FROM {$database_up}tags WHERE tagname in('{$sqldeltags}') LIMIT $count";
        $result = bmbdb_query($nquery);
    }
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[959]</strong></td></tr>
    <tr><td bgcolor=F9FAFE colspan=2>";
} elseif ($action == "modifyorder") {
    // -------改变顺序-----------
    
    $count = count($forumorder);
    
    for ($i = 0; $i<$count; $i++) {
    	if ($forumorder[$i] == "") continue;
    	$order = $i + 1;
        $result = bmbdb_query("UPDATE {$database_up}forumdata SET showorder=$order WHERE id='{$forumorder[$i]}'");
    }

	refresh_jscache();
	refresh_forumcach();
    exit;
} elseif ($action == "ffindex") {
	refresh_jscache();
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[569]</strong></td></tr>
    <tr><td bgcolor=F9FAFE colspan=2>";
    refresh_forumcach();
    refresh_forumcach("schedule", "s_item", "s_count", "id");
    refresh_forumcach("bmbcode", "tcode_item", "tcode_count", "id");
} 

print "<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a></td></tr></table></body></html>";
exit;
