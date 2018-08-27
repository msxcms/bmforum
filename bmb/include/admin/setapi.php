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

$thisprog = "setapi.php";

if ($useraccess != "1" || $admgroupdata[42] == "1") {
    adminlogin();
} 

if (!$action) { // Start Page
	error_reporting(E_ALL ^ E_NOTICE);
	@include("datafile/api/list.php");
	@include("datafile/api/key.php");
	$output_api_list = "";
	if(count($api_list)>=1) {
		foreach($api_list as $key=>$value)
		{
			include("include/api/{$key}/{$key}_config.php");
			$url_get = ($apiInfoFile['ApiAdmin']) ? "<a href='admin.php?bmod=$thisprog&action=setapi&setapi={$key}'>&gt;&gt; {$apiInfoFile['ApiAdmin']}</a>" : $arr_ad_lng[1232];
			$output_api_list.="<tr><td><strong>$value {$apiInfoFile['ApiVer']}</strong><br />
			{$apiInfoFile['ApiDesc']}<br/><br />{$apiInfoFile['ApiAuthor']}
			</td><td><input type='text' name='keychange[$key]' size='70' value='{$api_key_list[$key]}' /></td><td>$url_get</td></tr>";
		}
	} else {
		$disable_modify = "disabled='disabled' ";
		$output_api_list.="<tr><td colspan='3'>$arr_ad_lng[1233]</td></tr>";
	}

	$output_hooks_list = "";
	if(count($hook_list)>=1) {
		foreach($hook_list as $key=>$value)
		{
			if (!$found_hook_class || !in_array($key, $found_hook_class)) $found_hook_class[] = $key;
		}
		
		for ($i = 0;$i < count($found_hook_class); $i++)
		{
			foreach($hook_list["{$found_hook_class[$i]}"] as $value)
			{
				$hookInfoFile['HookAdmin'] = $disablestatus = '';
				if (file_exists("datafile/hooks/.disabled.{$found_hook_class[$i]}.{$value}")) $disablestatus = $arr_ad_lng[1243];
				include("include/hooks/{$found_hook_class[$i]}.{$value}.config.php");
				$url_get = ($hookInfoFile['HookAdmin']) ? "<a href='admin.php?bmod=$thisprog&action=hookadmin&sethooks={$found_hook_class[$i]}&classhooks={$value}'>&gt;&gt; {$hookInfoFile['HookAdmin']}</a>" : $arr_ad_lng[1232];
				$output_hooks_list.="<tr><td><input type='checkbox' value='{$found_hook_class[$i]}.{$value}' name='selecthooks[]' /></td><td>{$found_hook_class[$i]}</td><td><strong>{$hookInfoFile['HookName']}($value) {$hookInfoFile['HookVer']}</strong>
				<br/>{$disablestatus}$arr_ad_lng[1241] {$hookInfoFile['HookPriority']}
				</td><td>
				{$hookInfoFile['HookDesc']}<br/>{$hookInfoFile['HookAuthor']}</td><td>$url_get</td></tr>";
			}
		}
		
	} else {
		$disable_h_modify = "disabled='disabled' ";
		$output_hooks_list.="<tr><td colspan='5'>$arr_ad_lng[1233]</td></tr>";
	}
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[1225]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[1225]</strong> 
  </td></tr>
               
<script language="JavaScript">
function disableorno(){
if(confirm("$arr_ad_lng[395]", "$arr_ad_lng[395]")){
delones.submit();
}
}
function CheckAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
e.checked = true;
}
}
function FanAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
if (e.checked == true){ e.checked = false; }
else { e.checked = true;}
}}
</script>
               
$table_start
  <strong>$arr_ad_lng[1226]</strong>
$table_stop
 <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type="hidden" name="action" value="modifykey">
<table width="100%" cellspacing="2" cellpadding="2">
	<tr><td>$arr_ad_lng[1230]</td><td>$arr_ad_lng[1229]</td><td>$arr_ad_lng[1231]</td></tr>
	$output_api_list
</table>

<br /><img src='images/bsd07/announce.gif' /> $arr_ad_lng[1236]<br />
<br />
<input type="button" value="$arr_ad_lng[1228]" onclick='javascript:window.location="admin.php?bmod={$thisprog}&action=rescanapi&verify=$admin_log_hash";' />  <input {$disable_modify}type='submit' name='submit' value="$arr_ad_lng[1234]" /> <input type='reset' name='reset' />
</form>
$table_start
	<strong>$arr_ad_lng[1227]</strong>$table_stop
 <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type="hidden" name="action" value="modifyhooks">
<table width="100%" cellspacing="2" cellpadding="2">
	<tr><td>$arr_ad_lng[1237]</td><td>$arr_ad_lng[1242]</td><td>$arr_ad_lng[1230]</td><td>$arr_ad_lng[1240]</td><td>$arr_ad_lng[1231]</td></tr>
	$output_hooks_list
</table>
<br />
<br />
<input type="button" value="$arr_ad_lng[1228]" onclick='javascript:window.location="admin.php?bmod={$thisprog}&action=rescanhooks&verify=$admin_log_hash";' />  <input {$disable_h_modify}type='button' name=chkall value=$arr_ad_lng[405] onclick='CheckAll(this.form)'> <input {$disable_h_modify}type='button' name=clear2 value=$arr_ad_lng[406] onclick='FanAll(this.form)'> <input {$disable_h_modify}type='submit' name='submit' value="$arr_ad_lng[1239]" /> <input {$disable_h_modify}type='submit' name='submit' value="$arr_ad_lng[1238]" /> <input type='reset' name='reset' />
</form>

  </td>
  </tr>
                
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
</td></tr></table></td></tr></table>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == 'modifyhooks') { 
	for($i = 0;$i < count($selecthooks); $i++)
	{
		if ($selecthooks[$i]) {
			if ($_POST['submit'] == $arr_ad_lng[1239]) {
				@unlink("datafile/hooks/.disabled.{$selecthooks[$i]}");
			} else {
				writetofile("datafile/hooks/.disabled.{$selecthooks[$i]}", "1");
			}
		}
	}
	
	rescanhooks();
	successful_box();
} elseif ($action == 'hookadmin') { 
	require("include/hooks/{$_GET['sethooks']}.{$_GET['classhooks']}.config.php");
	require("include/hooks/admin/".basename($hookInfoFile['HookAdmin']));
    exit;
} elseif ($action == 'setapi') { 
	require("include/api/{$_GET['setapi']}/{$_GET['setapi']}_config.php");
	require("include/api/{$_GET['setapi']}/".basename($apiInfoFile['ApiAdmin']));
    exit;
} elseif ($action == 'modifykey') { 

    $file_int = "<?php\n";
    
    foreach($keychange as $key=>$value)
    {
    	if (!$value) $value = getCode(15);
    	$file_int .= "\$api_key_list['$key'] = '$value';\n";
    }
    
    
	writetofile("datafile/api/key.php", $file_int);
	
	successful_box();
} elseif ($action == 'rescanhooks') { 
	rescanhooks();
	successful_box();
} elseif ($action == 'rescanapi') { 
    $file_int = "<?php\n";
    $dh = opendir("include/api/");
    while (false !== ($stylelist = readdir($dh))) {
        if (($stylelist != ".") && ($stylelist != "..") && filetype("include/api/".$stylelist) == "dir") {
			if (include("include/api/{$stylelist}/{$stylelist}_config.php")) {
				$file_int .= '$api_list["'.$stylelist.'"]="'.str_replace('"', '\"', $apiInfoFile['ApiName']).'";'."\n";
			}
        } 
    } 
	
	writetofile("datafile/api/list.php", $file_int);
	
	successful_box();
} elseif ($action == "process") { // Save Data
    writetofile("datafile/actinfo.php", stripslashes($wordarray));
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[506]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#333333><center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[509]</strong><br /><br />
		$tab_top
EOT;
    // Load Data and Preview
    $filedata = file("datafile/actinfo.php");
    $count = count($filedata);
    for ($i = 0;$i < $count;$i++) {
        list($act, $actinfo) = explode("|", $filedata[$i]);
        print ("$arr_ad_lng[510] {$act} $arr_ad_lng[511] <font color=red>$arr_ad_lng[512]</font> $arr_ad_lng[513] {$actinfo}<br />");
    } 

    print "
	$tab_bottom
	<br /><br /><br /><center><strong><a href=admin.php?bmod=setact.php>$arr_ad_lng[514]</a></strong></center>
	</td></tr></table></body></html>
	";

    exit;
} 
function successful_box($addinfo='')
{
	global $arr_ad_lng, $thisprog;
    print"<tr><td bgcolor=#14568A valign=middle align=center colspan=1><font color=#F9FAFE>
    <strong>$arr_ad_lng[320] $arr_ad_lng[1225]</strong>
    </td></tr>	<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=1>
		<font color=#333333><strong>$arr_ad_lng[1235]</strong>
		</td></tr><tr bgcolor=#F9FAFE><td>$arr_ad_lng[1235]{$addinfo}<br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a></tr>";
    exit;
}
function rescanhooks()
{
	$file_int = "<?php\n";
    $dh = opendir("include/hooks/");
    while (false !== ($stylelist = readdir($dh))) {
        if (($stylelist != ".") && ($stylelist != "..")  && ($stylelist != "admin") && !strstr($stylelist, ".config.")) {
        	$hook_read = explode(".", $stylelist);
			if (@include("include/hooks/{$hook_read[0]}.$hook_read[1].config.php")) {
				$cache_hook_list["$hook_read[0]"]["$hook_read[1]"] = $hookInfoFile['HookPriority'];
				$hookInfoFile['HookPriority'] = min(9, $hookInfoFile['HookPriority']);
				$hookInfoFile['HookPriority'] = max(1, $hookInfoFile['HookPriority']);
				if (!$found_hook_class || !in_array($hook_read[0], $found_hook_class)) $found_hook_class[] = $hook_read[0];
			}
        } 
    } 
    for ($i = 0;$i < count($found_hook_class); $i++)
    {
    	arsort($cache_hook_list["{$found_hook_class[$i]}"]);
	    
	    foreach($cache_hook_list["{$found_hook_class[$i]}"] as $key=>$value)
	    {
	    	if (file_exists("datafile/hooks/.disabled.{$found_hook_class[$i]}.{$key}")) 
				$file_int .= '$hook_list_disabled["'.basename($found_hook_class[$i]).'"]["'.str_replace('"', '\"', basename($key)).'"]="1";'."\n";
			
			$file_int .= '$hook_list["'.basename($found_hook_class[$i]).'"][]="'.str_replace('"', '\"', basename($key)).'";'."\n";
	    }
    }
	
	writetofile("datafile/hooks/list.php", $file_int);
}