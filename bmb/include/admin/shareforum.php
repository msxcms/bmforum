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

$thisprog = "shareforum.php";

if ($useraccess != "1" || $admgroupdata[7] != "1") {
    adminlogin();
} 

print "    <tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[193]</strong>
    </td></tr>
";

if (empty($action)) {
    echo<<<EOT
<script language=Javascript>
function CountrySKU()

{

   var ctrycode = document.changeit.target.options[document.changeit.target.options.selectedIndex].value;


         

EOT;
    // Select Box
    $shareforumselect = "";
    $nquery = "SELECT * FROM {$database_up}shareforum ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        $shareforumselect .= "<option value=\"{$fourmrow['name']}\">{$fourmrow['name']}</option>";
        $fourmrow[url] = str_replace("'", "\'", $fourmrow[url]);
        $fourmrow[name] = str_replace("'", "\'", $fourmrow[name]);
        $fourmrow[des] = str_replace("'", "\'", $fourmrow[des]);
        $fourmrow[gif] = str_replace("'", "\'", $fourmrow[gif]);
        echo "if(ctrycode=='{$fourmrow[name]}'){
	  	  document.changeit.name.value='{$fourmrow[name]}';
	  	  document.changeit.url.value='{$fourmrow[url]}';
	  	  document.changeit.pic.value='{$fourmrow[gif]}';
	  	  document.changeit.info.value='{$fourmrow[des]}';
		";
        if ($fourmrow[type] == "pic") echo "document.changeit.type.checked=true;";
		else echo "document.changeit.type.checked=false;";
		echo"	  	  } ";
    } 

    $shareforumselect .= "</select>";

    print <<<EOT

}
 </script>
<script src="images/bmb_ajax.js"></script>

    $table_start
    <strong>$arr_ad_lng[777]</strong>
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="create">
    $table_stop
    <input type=checkbox name="type" value="pic" size=30>$arr_ad_lng[778]<br />
    $arr_ad_lng[779]<input type=text name="name" size=30><br />
    $arr_ad_lng[780]<input type=text name="url" size=30><br />
    $arr_ad_lng[781]<input type=text name="pic" size=30><br />
    $arr_ad_lng[782]<input type=text name="info" size=30><br />
    <input type=submit value="$arr_ad_lng[66]">
    </form>
    $table_start<strong>$arr_ad_lng[784]</strong>
    <form name="changeit" action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="modify">
    $table_stop
    <strong><input type=checkbox name="type" value="pic" size=30>$arr_ad_lng[778]</strong><br />
    $arr_ad_lng[440]<select onchange="CountrySKU()" name="target"><option value="">$arr_ad_lng[785]</option>$shareforumselect <input type=radio checked name="job" value="modify">$arr_ad_lng[786] <input type=radio name="job" value="delete">$arr_ad_lng[787]<br />
    $arr_ad_lng[779]<input type=text name="name" size=30><br />
    $arr_ad_lng[780]<input type=text name="url" size=30><br />
    $arr_ad_lng[781]<input type=text name="pic" size=30><br />
    $arr_ad_lng[782]<input type=text name="info" size=30><br />
    <input type=submit value="$arr_ad_lng[66]">
    </form>
    $table_start
    <strong>3.$arr_ad_lng[909]</strong>
    <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="modifyorder">
    $table_stop
<select multiple size=8 style="width: 50%;" name="list2" style="width: 120px">
$shareforumselect
<br />
<input type="button" value="$arr_ad_lng[1032]" onclick="Moveup(this.form.list2)" name="B3">
<input type="button" value="$arr_ad_lng[1033]" onclick="Movedown(this.form.list2)" name="B4">
<input type="button" onclick="GetOptions(this.form.list2, 'admin.php?bmod=$thisprog&action=modifyorder')" value="$arr_ad_lng[774]"> <input type=reset value="$arr_ad_lng[407]">
$arr_ad_lng[1034]
    $tab_bottom</form>
    </td></tr></td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "modifyorder") {
    // -------Change the order-----------
    $count = count($forumorder);
    
    for ($i = 0; $i<$count; $i++) {
    	if ($forumorder[$i] == "") continue;
    	$order = $i + 1;
        $result = bmbdb_query("UPDATE {$database_up}shareforum SET showorder=$order WHERE name='{$forumorder[$i]}'");
    }

    refreshare_cache();
    exit;
} elseif ($action == "create") {
    // -------New item-----------
    $newstring = "";
    print "<tr><td bgcolor=#DDDDDD valign=middle align=center colspan=2><strong>$arr_ad_lng[788]</strong></td></tr>
	<tr><td bgcolor=FEFEFF colspan=2>";

    if (!empty($name) && !empty($url)) {
        $xxquery = "SELECT * FROM {$database_up}shareforum ORDER BY `showorder` DESC  LIMIT 0,1 ";
        $xxresult = bmbdb_query($xxquery);
        $lineyy = bmbdb_fetch_array($xxresult);
        $showorder = $lineyy['showorder'] + 1;
        
        $name = str_replace('"', "'", $name);

        $nquery = "insert into {$database_up}shareforum (name,url,gif,des,type,showorder) values ('$name','$url','$pic','$info','$type','$showorder')";
        $result = bmbdb_query($nquery);

        echo "<br /><br />$arr_ad_lng[789]<strong></strong><br />";
    } else echo "<br /><br />$arr_ad_lng[791]<strong></strong><br />";
    refreshare_cache();
} elseif ($action == "modify") {
    // Modify
    print "<tr><td bgcolor=#DDDDDD valign=middle align=center colspan=2><strong>$arr_ad_lng[792]</strong></td></tr>
	<tr><td bgcolor=FEFEFF colspan=2>";

    if ($job == "delete") {
        $nquery = "DELETE FROM {$database_up}shareforum WHERE name='$target'";
        $result = bmbdb_query($nquery);
    } elseif ($job == "modify") {
        $nquery = "UPDATE {$database_up}shareforum SET type='$type',des='$info',gif='$pic',url='$url',name='$name' WHERE name='$target'";
        $result = bmbdb_query($nquery);
    } 
    refreshare_cache();
} 
print "<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a></td></tr></table></body></html>";
exit;
