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
$thisprog = "setlang.php";
if ($useraccess != "1" || $admgroupdata[21] != "1") {
    adminlogin();
} 

if (!$action) {
    // Setting Form
    $langshow = "";
    $langlist = @file("datafile/langlist.php");
    $count = count($langlist);
    $showlist = "";
    for($i = 0;$i < $count;$i++) {
        $xlangshow = explode("|", $langlist[$i]);
        $showlist .= "$xlangshow[3]&nbsp;&nbsp;/&nbsp;&nbsp;$xlangshow[2]&nbsp;&nbsp;(lang/$xlangshow[1])<br />";
        $langshow .= "<option value=$xlangshow[1]>$xlangshow[1] - $xlangshow[3]</option>";
    } 
    if ($autolang) $sautolang = $arr_ad_lng[94];
    else $sautolang = $arr_ad_lng[95];
    @include("datafile/language.php");
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[212]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[212]</strong>
$table_start<strong>$arr_ad_lng[575] [ <span title="$arr_ad_lng[576]">$arr_ad_lng[577]: $sautolang</span> ]</strong>
	$table_stop
    $showlist

  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
    
 $table_start<strong>$arr_ad_lng[579]($arr_ad_lng[580] $language):</strong>
 	 $table_stop
 	 <select name='languagename'>$langshow</select><p><input type=submit name="mr" value="$arr_ad_lng[581]"> 
</form>
$table_start
	 $arr_ad_lng[888]
$table_stop
<strong><a href=admin.php?bmod=$thisprog&action=rebuild>$arr_ad_lng[578]</strong></a>
<br /><a href="http://www.bmforum.com/help/translate.htm">$arr_ad_lng[889]</a>

  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    if ($mr == "$arr_ad_lng[581]") { // Set the default language
    	$langlist = @file("datafile/langlist.php");
    	$count_language = count($langlist);
        $filecontent = "<?php
\$language=	'$languagename';
\$count_language=	'$count_language';
";
        writetofile("datafile/language.php", $filecontent);
        print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[212]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
        exit;
    } 
} elseif ($action == "rebuild") {
    // Refresh the language list
    $admin_language = $language;
    $filecontent = ""; // EMPTY VARS.
    $dh = opendir("lang");
    while (false !== ($download = readdir($dh))) {
        if (($download != ".") && ($download != "..")) {
            include("lang/" . $download . "/global.php");
            $filecontent .= "<?php //|$download|$lang_accept|$language_name|\n";
        } 
    } 
    closedir($dh);

    writetofile("datafile/langlist.php", $filecontent);
    
    @include("datafile/language.php");
    
    $langlist = @file("datafile/langlist.php");
   	$count_language = count($langlist);
    $filecontent = "<?php
\$language=	'$language';
\$count_language=	'$count_language';
";
    writetofile("datafile/language.php", $filecontent);
    
    include("lang/" . $admin_language . "/global.php");
    include("lang/" . $admin_language . "/admin.php");
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[212]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[582]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
} 
