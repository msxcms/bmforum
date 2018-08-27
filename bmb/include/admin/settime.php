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

$thisprog = "settime.php";

if ($useraccess != "1" || $admgroupdata[22] != "1") {
    adminlogin();
} 

if ($action != "process") {
    include("datafile/time.php");
    if ($time_2) $time_2_a = "checked='checked'";
    else $time_2_b = "checked='checked'";

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=4><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[213]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>



<tr>
   <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[213]</strong>
   </td>
  </tr>

  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[696]</td>
   <td><INPUT size=35 value="$time_1" name=setting[a1]></td>
  </tr>		  
  	    <tr bgcolor=F9FAFE>
   <td><strong>$arr_ad_lng[697]</strong></td>
   <td><INPUT type=radio value=1 $time_2_a name=setting[a2]>$arr_ad_lng[698]<br /><INPUT type=radio value=0 $time_2_b name=setting[a2]>$arr_ad_lng[699]</td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td>$arr_ad_lng[1203]</td>
   <td><input size="35" value="$time_f" name="setting[af]" /></td>
  </tr>		  
  	    <tr bgcolor=F9FAFE>
   <td colspan=2 align=center ><input type=submit value="$arr_ad_lng[66]"> <input type=reset value="$arr_ad_lng[178]">
  </tr>
</form>
  </td></tr></table></body></html>
EOT;
    exit;
} 
$setting[30] *= 60;

$filecontent = "<?php
\$time_1	=	$setting[a1];
\$time_2	=	$setting[a2];
\$time_f	=	'$setting[af]';
";

writetofile("datafile/time.php", $filecontent);
print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[213]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center>
		</td></tr></table></body></html>
EOT;
exit;
