<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 Function : Installing & Repair
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project - GNU Licence project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
@define("INSTALLER", 1);
@ini_set('display_error', 1);
@define("INBMFORUM", 1);
@set_time_limit(1000);
$verandproname = "BMForum 2007 5.5";
$kernel_build  = "20071001";
@include_once("../datafile/lastrunning_version.php");

require_once("func.inc.php");
require_once("../datafile/language.php");

if (!empty($_COOKIE["userlanguage"]) && file_exists("lang/" . basename($_COOKIE["userlanguage"]) . ".php")) $language = $_COOKIE["userlanguage"];
elseif (empty($_COOKIE["userlanguage"]) && $autolang) {
    $langlist = @file("datafile/langlist.php");
    $count_language = count($langlist);
    $useraccept = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $useraccept = $useraccept[0];
    for($i = 0;$i < $count_language;$i++) {
        $xlangshow = explode("|", $langlist[$i]);
        $langarray[$xlangshow[1]] = $xlangshow[2];
    } 
    $langkeyname = array_search_bit($useraccept, $langarray);
    if (!empty($langkeyname)) {
    	bmb_setcookie("userlanguage", $langkeyname);
        $_SESSION['userlanguage'] = $langkeyname;
        $language = $langkeyname;
    } 
} 

if(!include_once("languages/".basename($language).".php")) include_once("languages/eng.php")

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $read_alignment;?>" lang="<?php echo $html_lang;?>">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="http://www.bmforum.com/bmb/images/bsd07/styles.css" />
<title>BMForum Upgrade - powered by BMForum.com</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="generator" content="BMForum Installer" />
<meta name="keywords" content="php,bmb,bmf,蓝色,魔法,论坛,bbs,bmforum,text,蓝魔,免费" />
</head>

<body>
<div id="totallayer" style="position:relative; left:1.3%; top:0px; width:95%; height:auto; z-index:1; border: none; " class="bmforum_background">

<table class="tableborder" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td>		
	<table class="bmbnewstyle_withoutwidth" cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
		<tr>
			<td>
			<table cellspacing="0" cellpadding="5" width="100%" border="0">
				<tr>
					<td class="bmforum_base_menu">
					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td align="left">
							<span class="titlefontcolor">
							BMForum Upgrade - From <?php echo $verandproname; ?></span></td>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</td></tr></table>
    <style type="text/css">
    .t {font-family: Verdana, Arial, Sans-serif;font-size  : 12px;padding-left: 10px;font-weight: normal;line-height: 150%;color : #29338A;}
    .e {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #0000EE;}
    .w {font-family: Arial, Sans-serif;font-size  : 12px;font-weight: normal;line-height: 200%;color : #EE0000;}
    .h {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 16px;font-weight: bold;color : #29338A;}
    .i {font-family: Arial, Sans-serif;padding-top: 5px;padding-left: 10px;font-size  : 12px;font-weight: bold;color : #29338A;}
    </style>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font"><a name="top"></a>BMForum Forum System</span>
</td></tr>
<tr>
<td class="forumcoloronecolor" style="text-align:left;">
			<em><span style="font-size:12pt;font-weight:bold;"><?php echo $li[2]?></span></em>
			<br /><br /><b><?php echo $li[3]?></b>
			<br />
			<span class='e'><?php echo $li[4]?></span>
			<br />
			<span class='w'><?php echo $li[5]?></span>

		</td>
		</tr>
			
<?php
if (!$step) {
    $check = 1;
    $current_time = date("F j, Y, g:i:s a");
    $current_gmt_time = gmdate("F j, Y, g:i:s a");
    $phpver = PHP_VERSION;
    if ($phpver < "4.1.0") {
        $warningver = "<font class=w>$li[6]</font><br />";
    } 
    $phpos = PHP_OS;
    if (@isset($_COOKIE)) $testcookie = "<font color=#29338A>$li[7]</font>";
    else $testcookie = "<font class=w>$li[8]</font>";
    if (@isset($_GET)) $testget = "<font color=#29338A>$li[7]</font>";
    else $testget = "<font class=w>$li[8]</font>";
    if (@get_cfg_var("file_uploads")) $testupload = "<font color=#29338A>$li[7]</font> ($li[10] " . @get_cfg_var("upload_max_filesize") . ")";
    else $testupload = "<font color=990000>$li[9]</font>";
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[11]</span>
</td></tr>

  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>
$li[12] <font color=#29338A>$phpver</font>
<br />$li[13] <font color=#29338A>$phpos</font>
<br />$li[14] $testcookie
<br />$li[15] $testget
<br />$li[16] $testupload
<br />$li[17] <b><font face=verdana>$current_time ($li[18] $current_gmt_time)</font></b><br />$warningver

</td></tr>

</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[19]</span>
</td></tr>

    <tr><td class='forumcolortwo_noalign' style='text-align:center;' colspan="2"><INPUT style="height:30px;font-size:12pt;font-weight:bold;width: 50%; font-family: Verdana;" type="button" onclick="javascript:this.disabled='disabled';this.value='$li[22]';window.location='index.php?step=2';" value="$li[29]"></TD></TR>

</td></tr>
</table>
</td></tr></table>

EOT;


} else {
    include("../datafile/config.php");
    require("../include/db/db_{$sqltype}.php");
    bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

	$schemes = $ran = "";
	
	if ($mysqlchar == 1) $sqlcharset=" CHARSET=utf8";
	
	if ($handle = opendir('schemes/')) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != ".." && !is_dir($file)) {
	            $schemes[] = trim($file);
	        }
	    }
	    closedir($handle);
	    sort($schemes);
	    
	    foreach ($schemes as $value) {
		    if ($value > $kernel_build) {
		    	include_once('schemes/'.$value);
		    	$ran = 1;
		    }
		}
		
		if ($ran == 1) writetofile('../datafile/lastrunning_version.php', $version_file_info);
		$up_msg = $li[0];
	} else {
		$up_msg = $li[23];
	}

    
echo<<<EOT
</td></tr>
</table>
</td></tr></table>
<br/><table cellspacing="0" cellpadding="0" class="tableborder" align="center"> <tr><td>
<table cellspacing="1" cellpadding="5" class="faq_table" style='width:100%'>
<tr><td colspan="100" class="faq_table_tr"> 
<span class="categoryfontcolor_font">$li[1]</span>
</td></tr>

  <tr>
<td class='forumcoloronecolor' style='text-align:left;'>
<span style="$li[21]">$up_msg</span>
</td></tr>

</td></tr>
</table>
</td></tr></table>


EOT;
}

?>


<br />
    </body></html>
