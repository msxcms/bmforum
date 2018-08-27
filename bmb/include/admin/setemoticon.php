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

$thisprog = "setemoticon.php";

if ($useraccess != "1" || $admgroupdata[32] != "1") {
    adminlogin();
} 


if (!$action) {
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[224]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=3>
  <strong>$arr_ad_lng[224]</strong>
  </td></tr>



  <tr>
  <td bgcolor=#F9FCFE valign=middle align=left colspan=3>
    　$arr_ad_lng[537]
  </td>
  </tr>

$table_start
<strong style="color:#FFFFFF;"><a style="color:#FFFFFF;font-weight:bold;" href="admin.php?bmod=$thisprog&action=refresh">$arr_ad_lng[1081]</a></strong>
$table_start
 	  <tr bgcolor="#6DA6D1">$arr_ad_lng[539]
</tr>
  <script language="JavaScript" type="text/javascript">
function DelEmot(num){
if(confirm("$arr_ad_lng[540]", "$arr_ad_lng[540]")){
window.location="admin.php?bmod=setemoticon.php&action=del&verify=$admin_log_hash&num="+num;
}
}
</script>
	
EOT;

    @include("datafile/cache/emoticons.php");
    @include("datafile/cache/eplist.php");
    for ($i = 0;$i < $cemotcount;$i++) {
    	if ($cachedemot[$i]['emotpack'] != $cachedpackname) {
echo <<<EOT
$table_start
<strong style="color:#FFFFFF;">{$cachedemot[$i]['packname']}</strong>
EOT;
    	}
    	$keyid = $i;
    	$cachedpackname = $cachedemot[$i]['emotpack'];
    	$thumbfile = $cachedemot[$i]['thumb'] ? "(<img src='images/face/thumb/{$cachedemot[$i]['emotpack']}/{$cachedemot[$i]['emotname']}' />)" : '';
        print <<<EOT
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong><img src="images/face/emotpacks/{$cachedemot[$i]['emotpack']}/{$cachedemot[$i]['emotname']}" border="0" /> $thumbfile {$cachedemot[$i]['emotcode']}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=center>
  <input type="text" value="{$cachedemot[$i]['emotcode']}" /></td>
  <td bgcolor=#F9FAFE valign=middle align=center>
  <a href="admin.php?bmod=$thisprog&action=edit&num={$keyid}">$arr_ad_lng[541]</a> <a href="javascript:DelEmot({$keyid});">$arr_ad_lng[542]</a></td>
  </tr>

EOT;
    } 

    print <<<EOT
  
  <tr bgcolor=E2E8D0>
   <td colspan=3 align=center >
  </tr>

  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "refresh") {
	$newemot = "";
	$writelist = "<?php \n";
	@include("datafile/cache/emoticons.php");
	for($i = 0;$i < $cemotcount; $i++)
	{
		if (!file_exists("images/face/emotpacks/{$cachedemot[$i]['emotpack']}/{$cachedemot[$i]['emotname']}")) {
			bmbdb_query("DELETE FROM {$database_up}emoticons WHERE id={$cachedemot[$i]['id']}");
		} else {
			$emotlist["{$cachedemot[$i]['emotpack']}"][] = $cachedemot[$i]['emotname'];
		}
	}

    $dh = opendir("images/face/emotpacks");
    while (false !== ($imagefile = readdir($dh))) {
        if (filetype("images/face/emotpacks/".$imagefile) == "dir" && $imagefile != "." && $imagefile != ".." && $imagefile != "") {
		    $pdh = opendir("images/face/emotpacks/".$imagefile);
	       	$getcontent = @readfromfile("images/face/emotpacks/$imagefile/config.txt");
	       	$getfilename =  $getcontent ? $getcontent : $imagefile;
		    while (false !== ($pimagefile = readdir($pdh))) {
		        if (!@in_array($pimagefile, $emotlist[$imagefile]) && $pimagefile != "config.txt" && $pimagefile != "." && $pimagefile != ".." && $pimagefile != "") {
		        	$thumbfile	= 0;
		        	$emotcode	= explode(".", $pimagefile);
		        	if (!@in_array($imagefile, $emotpacklist)) {
		        		$emotpacklist[]="$imagefile";
		        	}
		        	if (file_exists("images/face/thumb/$imagefile/$pimagefile")) $thumbfile = 1;
		        	bmbdb_query("INSERT INTO {$database_up}emoticons (emotcode,emotpack,packname,emotname,thumb) VALUES (':$emotcode[0]:','$imagefile','$getcontent','$pimagefile',$thumbfile)");
		        }
		    } 
	       	$writelist .= "\$eplist[]=\"$imagefile\";\n";
	       	$writelist .= "\$enlist['$imagefile']=\"$getfilename\";\n";
		    closedir($pdh);
		}
    } 
    closedir($dh);
	
	writetofile("datafile/cache/eplist.php", $writelist);
	
    refresh_forumcach("emoticons", "cachedemot", "cemotcount", "emotpack");
	refresh_count_emot();

print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[224]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[545]</strong>
  </td></tr>

			<tr><td bgcolor=#F9FAFE valign=middle align=left colspan=2>$arr_ad_lng[546]<br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr>
EOT;


} elseif ($action == "del") {
    @include("datafile/cache/emoticons.php");

	bmbdb_query("DELETE FROM {$database_up}emoticons WHERE id={$cachedemot[$num]['id']}");

    refresh_forumcach("emoticons", "cachedemot", "cemotcount", "emotpack");
	refresh_count_emot();


    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[224]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[547]</strong></td></tr><tr>
 <td bgcolor=#F9FAFE valign=middle align=left colspan=2><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
  </td></tr>

EOT;
} elseif ($action == "edit") {
    if ($step != "2") {
	    @include("datafile/cache/emoticons.php");


        print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[224]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[224] - {$cachedemot[$num]['packname']}({$cachedemot[$num]['emotpack']})</strong>
  </td></tr>

<form action=admin.php?bmod=$thisprog method="post">
<input type=hidden name="action" value="edit"> 
<input type=hidden name="step" value="2"> 
<input type=hidden name="num" value="$num"> 
 	  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center width=40%>
  <strong>$arr_ad_lng[543]{$cachedemot[$num]['emotpack']}</strong><br /><img id='preview' src="images/face/emotpacks/{$cachedemot[$num]['emotpack']}/{$cachedemot[$num]['emotname']}" /></td>
  <td bgcolor=#F9FAFE valign=middle align=center>
  <input type="text" name="emotpic" value="{$cachedemot[$num]['emotname']}" onblur='javascript:document.getElementById("preview").src="images/face/emotpacks/{$cachedemot[$num]['emotpack']}/"+this.value;'></td></tr>
 	  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center width=40%>
  <strong>$arr_ad_lng[544]</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=center>
  <input type="text" name="emotcode" value="{$cachedemot[$num]['emotcode']}"></td></tr>
  		  <tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><input type=submit value="$arr_ad_lng[66]"></form></td></tr>
EOT;
    } else {
	    @include("datafile/cache/emoticons.php");
    	$thumbfile = (file_exists("images/face/thumb/{$cachedemot[$num]['emotpack']}/$emotpic")) ? 1 : 0;

    	bmbdb_query("UPDATE {$database_up}emoticons SET emotcode='$emotcode',emotname='$emotpic',thumb='$thumbfile'  WHERE id={$cachedemot[$num]['id']}");

	    refresh_forumcach("emoticons", "cachedemot", "cemotcount", "emotpack");
		refresh_count_emot();

        print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[224]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[548]</strong>
  </td></tr>

			<tr><td bgcolor=#F9FAFE valign=middle align=left colspan=2>$arr_ad_lng[549]<br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a></td></tr>
EOT;
    } 
} 

function refresh_count_emot()
{
	global $database_up;
	@include("datafile/cache/eplist.php");
	$simlist = $wrtlist = "<?php \n";
	foreach($eplist as $value)
	{
		$result = bmbdb_query_fetch("SELECT COUNT(*) FROM {$database_up}emoticons where emotpack='$value'");
		$wrtlist .= "\$epcounts['$value']={$result['COUNT(*)']};\n";
	}

    @include("datafile/cache/emoticons.php");
    for($i =0;$i<$cemotcount;$i++){
    	$wrtlist .= "\$bynameep['{$cachedemot[$i]['emotpack']}']['emotname'][]='{$cachedemot[$i]['emotname']}';\n";
    	$wrtlist .= "\$bynameep['{$cachedemot[$i]['emotpack']}']['thumb'][]='{$cachedemot[$i]['thumb']}';\n";
    	$wrtlist .= "\$bynameep['{$cachedemot[$i]['emotpack']}']['emotcode'][]='{$cachedemot[$i]['emotcode']}';\n";
    	$simlist .= "\$simlist['{$cachedemot[$i]['emotcode']}']='{$cachedemot[$i]['emotpack']}/{$cachedemot[$i]['emotname']}';\n";
    }
	writetofile("datafile/cache/epcount.php", $wrtlist);
	writetofile("datafile/cache/epsiplist.php", $simlist);
}