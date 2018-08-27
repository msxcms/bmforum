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

$thisprog = "luntanhb.php";

if ($useraccess != "1" || $admgroupdata[5] != "1") {
    adminlogin();
} 
$forumonly = "";
$nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
$nresult = bmbdb_query($nquery);
while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
    if ($fourmrow['type'] != "category") $forumonly .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
} 
$forumonly .= "</select>";

include("include/admin/adminheader.php");
print <<<EOT
	<html>
    <head>
    <title>$arr_ad_lng[39]</title>
    
<style type="text/css">
body { color: #F9FCFE; font-family: verdana,arial; font-size: 9pt;margin-top:0;margin-left:0;
}
.bmf_table_border{
	width: 97%;
	background: #F8FCFF; 
}
.bmf_table_class{
	width: 97%;
	background-color: #F8FCFF;
	border-left:#448ABD 1px solid;
	border-right:#448ABD 1px solid;
}
.bmf_n_table_class{
	width: 100%;
	background-color: #F8FCFF;
	border-left:#448ABD 1px solid;
	border-right:#448ABD 1px solid;
	border-top:#448ABD 1px solid;
}
td {
	border-bottom:1px solid #448ABD;
}
a:link		{color: #333333; text-decoration: none;}
a:visited	{color: #333333; text-decoration: none;}
a:hover		{color: #254394;text-decoration: underline;}
a:active	{color: #254394;text-decoration: underline;}

.t     {	line-height: 1.4;}
td,select,textarea,div,form,option,p{
color:#333333; font-family: tahoma; font-size: 9pt;
}
input  {	font-family: tahoma; font-size: 9pt; height:22px;	}

</style>

    </head>
    <body bgcolor=#F9FCFE topmargin=5 leftmargin=5>
<br />


    </td><td width=70% valign=top bgcolor=#D6DFF7>
    <table align="center" cellpadding='6' cellspacing='0' class="bmf_table_class">
EOT;

print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
            <strong>$arr_ad_lng[320] $arr_ad_lng[191]</strong>
            </td></tr>";

if (empty($newbbs) && empty($oldbbs)) {
    print <<<EOT
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[401]</strong><form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
    </td>
    </tr>          
                
    <tr>
    <td bgcolor=#F9FAFE colspan=2>
    $arr_ad_lng[413]
$table_start
    <strong>$arr_ad_lng[401]</a></strong>$table_stop

    $arr_ad_lng[414]<br />
    <br />&gt;&gt;<select name="oldbbs">$forumonly<br /><br />
    $arr_ad_lng[415]<br />
    <br />&gt;&gt;<select name="newbbs">$forumonly<br /><br />
        <input type=submit value="$arr_ad_lng[66]">
    $tab_bottom
    		</form>

    </td>
    </tr>
    </td></tr></table></body></html>
EOT;
    exit;
} elseif (!empty($newbbs) && !empty($oldbbs)) {
    if ($newbbs == $oldbbs) {
        $title = "$arr_ad_lng[295]";
        $status = "$arr_ad_lng[416]";
        print_info();
        echo $showerror;
    } else {
        $newstring = "";

        $nquery = "UPDATE {$database_up}posts SET forumid='$newbbs' WHERE forumid = '$oldbbs'";
        $result = bmbdb_query($nquery);
        $nquery = "UPDATE {$database_up}threads SET forumid='$newbbs' WHERE forumid = '$oldbbs'";
        $result = bmbdb_query($nquery);
        $nquery = "DELETE FROM {$database_up}forumdata WHERE id = '$oldbbs'";
        $result = bmbdb_query($nquery);

		refresh_jscache();
	    refresh_forumcach();

        $title = "$arr_ad_lng[417]";
        $status = $arr_ad_lng[418] . "
		&gt;&gt; <a href=\"admin.php?bmod=forumfix.php&action=updatecount&target=$newbbs\">$arr_ad_lng[358]</a>
";
        print_info();
        echo $showerror;
    } 
} 

function print_info()
{
    global $status, $title, $showerror, $tab_top;
    $showerror = "
<tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$title</strong>
    </td>
    </tr>       
        <tr>
    <td bgcolor=#F9FAFE colspan=2><br />$tab_top
    $status
    </td>
    </tr>
    </td></tr></table></body></html>";
    return $showerror;
} 
