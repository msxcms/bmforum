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
$thisprog = "mailuser.php";

if ($useraccess != "1" || $admgroupdata[34] != "1") {
    adminlogin();
} 

$specify_sql = ($admgroupdata[9] == 1) ? stripslashes($specify_sql) : "";

$count = count($usergroupdata); // Count User Group

if ($action != "send") { // Form Page

    $specify_sql = htmlspecialchars($specify_sql);
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[227]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[227]</strong>  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>
               

               
$table_start
	<strong>$arr_ad_lng[419]</strong>
$table_stop
  <font color=#000000>$arr_ad_lng[420]
$table_start
	<strong>$arr_ad_lng[227]</strong>
$table_stop
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%" align=right><strong>$arr_ad_lng[421]</strong></td>
  <td width="80%"><input type="text" value=100 name="pertime"></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%" align=right><strong>$arr_ad_lng[422]</strong></td>
  <td width="80%"><input type="text" name="subject"></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=middle>
  <td width="20%" align=right><strong>$arr_ad_lng[423]</strong></td>
  <td width="80%"><input type="text" value="$admin_email" name="From"></td>
 </tr>
 <tr bgcolor="#F9FAFE" valign=top>
  <td align=right><strong>$arr_ad_lng[424]</strong></td>
  <td><textarea size=20 name="text" cols="60" rows="10">$arr_ad_lng[425]</textarea></td>
 </tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle colspan=2>
$tab_top
  <strong>$arr_ad_lng[426]</strong><br />
$tab_bottom
  </td>
  </tr>
 <tr> 
  <td colspan="2" align="center" width="100%" bgcolor=#F9FAFE>
<br />
    <input type="hidden" name="specify_sql" value="$specify_sql">
  <input type=hidden name="action" value="send">
  <input type="hidden" name="first" value="1">
   <input type="submit" value="$arr_ad_lng[66]">　<input type="reset" value="$arr_ad_lng[178]">
  </td>
 </tr>
</form>
</table></td></tr></table>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "send") { // Send Mailes
    print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[227]</strong>
		</td></tr>";

    if (empty($subject) || empty($text)) { // Empty Input
        print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[428]</strong></center><br /><br />
		<br /><br /><br /><center><strong><a href='javascript:history.go(-1)'>$arr_ad_lng[361]</a></strong></center>
		</td></tr></table></body></html>
EOT;
        exit;
    } 
    $text = ($first == 1) ? nl2br(stripslashes($text)) : base64_decode($text);

    
    if (empty($pertime)) $pertime = 100;
    if (empty($step)) $step = 1;


    $xcount = count($uchose);
    for($xi = 0;$xi < $xcount;$xi++) {
        $uchoosesql .= ($uchoosesql) ? "WHERE ugnum in($uchose[$xi]" : ",$uchose[$xi]";
    } 
    $uchoosesql .= ($uchoosesql) ? ")" : "";
    
    if ($specify_sql) $uchoosesql .= ($uchoosesql) ? " AND ($specify_sql)" : "WHERE $specify_sql";

    $query = "SELECT COUNT(*) FROM {$database_up}userlist $uchoosesql";
    $result = bmbdb_query($query);
    $xcount = bmbdb_fetch_array($result);
    $count = $xcount['COUNT(*)'];

    $min = ($step-1) * $pertime; 
    // Start to send mail
    $query = "SELECT * FROM {$database_up}userlist $uchoosesql LIMIT $min,$pertime";
    $result = bmbdb_query($query);
    include_once("include/sendmail.inc.php");
    $sendmessage = $text;
    $lastpage = 1;
    while (false !== ($row = bmbdb_fetch_array($result))) {
        $coui++; // sent count
        $lastpage = 0;
        $currentuseremail[] = $row['mailadd'];
    } 
    if ($currentuseremail) {
    	foreach($currentuseremail as $mail) {
    		BMMailer($mail, $subject, $sendmessage, '', '', $bbs_title, $admin_email);
    	}
    }

    $step++; // Step to next
    
    if ($lastpage) {
        echo "
		<tr>
		<td bgcolor=#F9FAFE colspan=2>
		<center><strong>$arr_ad_lng[429]</strong></center>
		</td>
		</tr>
		<tr bgcolor=F9FAFE>
		<td colspan=2>
		<br /><br /><br />
		$arr_ad_lng[430] $count $arr_ad_lng[431]
		</td></tr></table></body></html>";
    } else {
    	$text = base64_encode($text);
    	
    	$specify_sql = htmlspecialchars($specify_sql);
        $xcount = count($uchose);
        for($xi = 0;$xi < $xcount;$xi++) {
            $uchoseshow .= "<input type=hidden name='uchose[]' value=\"$uchose[$xi]\">";
        } 
        $arr_ad_lng[433] = str_replace("{count}", $count, $arr_ad_lng[433]);
        $arr_ad_lng[433] = str_replace("{i}", $coui, $arr_ad_lng[433]);
        $refresh_allowed = ($refresh_allowed+1)*1000;
        print <<<EOT
<tr>
<td bgcolor=#F9FAFE colspan=2>
<center><strong>$arr_ad_lng[432]</strong></center>
</td>
</tr>
<form action="admin.php?bmod=$thisprog" method=POST>
<tr bgcolor=F9FAFE>
<td colspan=2>
<input type=hidden name=step value="$step">
<input type="hidden" name="specify_sql" value="$specify_sql">
<input type=hidden name=text value="$text">
<input type=hidden name=pertime value="$pertime">
<input type=hidden name=coui value="$coui">
<input type=hidden name=subject value="$subject">
$uchoseshow
<input type=hidden name=From value="$From">
<input type=hidden name=action value="send">
$arr_ad_lng[433]<br /><br /><br />
<input type="submit" value="$arr_ad_lng[434]" id="sendbutton" />
</td></tr></form><script type='text/javascript'>setTimeout('document.getElementById("sendbutton").click();',$refresh_allowed);</script></table></body></html>
EOT;
        exit;
    } 
} 
