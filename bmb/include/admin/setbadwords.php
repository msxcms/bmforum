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

$thisprog = "setbadwords.php";

if ($useraccess != "1" || $admgroupdata[24] != "1") {
    adminlogin();
} 
if ($action != "process") {
    if (file_exists("datafile/badwords.php")) {
        include("datafile/badwords.php");
        
        $count = count($badwords);

        if ($count > 0) {
            foreach ($badwords as $key => $value) 
            $echobadwords .= "$key=$value\n";
        }
    } else $echobadwords = "";
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[216]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[216]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>
               

$table_start
<strong>$arr_ad_lng[419]</strong>
$table_stop
  $arr_ad_lng[515]
$table_start  <strong>$arr_ad_lng[516]</strong>
	  
$table_stop
$arr_ad_lng[517]
$table_start
<strong>$arr_ad_lng[518]</strong>$table_stop
<center>
  <textarea cols=60 rows=6 wrap="virtual" name="wordarray">$echobadwords</textarea>
</center>
  </td>
  </tr>
                
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <input type=submit name=submit value="$arr_ad_lng[66]"></td></tr></table></td></tr></table>
  </td></tr></table></body></html></form>
EOT;
    exit;
} elseif ($action == "process") {
    $badwords = "<?php\n";
    $wordarray = str_replace("\n", "", $wordarray);
    $wordarray = explode("\r", $wordarray);
    $count = count($wordarray);
    for ($i = 0; $i < $count; $i++) {
        list($key, $value) = explode("=", $wordarray[$i]);
        if (empty($key)) continue;
        $badwords .= "\$badwords['$key']='$value';\n";
        $newbadwords[$key] = $value;
    } 
    writetofile("datafile/badwords.php", $badwords);
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[216]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<font color=#333333><center><strong>$arr_ad_lng[179]</strong></center><br /><br />
		<strong>$arr_ad_lng[519]</strong><br /><br />
		$tab_top
EOT;
	$count = count($newbadwords);
    
    if ($count > 0) {
    	foreach ($newbadwords as $key => $value) {
    	    print ("$arr_ad_lng[520] <strong>$key</strong> $arr_ad_lng[521] <strong>$value</strong> $arr_ad_lng[522]<br />");
    	}
    }
    print "
			$tab_bottom
			<br /><br /><br /><center><strong><a href=admin.php?bmod=setbadwords.php>$arr_ad_lng[523]</a></strong></center>
			</td></tr></table></body></html>
			";
    exit;
} 
