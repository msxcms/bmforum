<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
error_reporting(E_ALL ^ E_NOTICE);
$thisprog = "admin.php";
define("INBMFORUM", 1);

$nooutputarray = array("batresume.php", "batbackup.php", "luntanhb.php", "dumpuser.php", "dumpforum.php"); // Don't Output Titles' Pages

$bmodfile = "include/admin/" . basename($_GET['bmod']);
if (in_array(basename($_GET['bmod']), $nooutputarray)) $thisisout = "yes"; // Check and set var

$admin_log_hash = substr(md5($_COOKIE['bmbadminpwd'].$_COOKIE['bmbadminid']), 0, 6);

if (@file_exists($bmodfile) && basename($_GET['bmod']) != "") { // Load Admin Mod
	$check_from = explode("?", basename($_SERVER['HTTP_REFERER']));
	if ($_GET['verify'] != $admin_log_hash && $check_from[0] != "admin.php" && !is_array($_POST['forumorder'])) die("Access Denied. Please close your firewall first.");
    $thisprog = basename($_GET['bmod']); // Filename Record to Log
    include_once("include/adminglobal.php"); // Base Lib
    include_once("datafile/config.php");
    include_once($bmodfile);
    $included = 1; // Not Start Page
} 
if ($included != 1) { // Admin Start Page
    include_once("include/adminglobal.php");
    include_once("datafile/config.php");

    $current_time = date("F j, Y, g:i:s a");
    $current_gmt_time = gmdate("F j, Y, g:i:s a");

    $lastuser = 0;
    $totalusers = 0;
    $totalposts = 0;
    $start_topic_ratio = 0;

    $query = "SELECT * FROM {$database_up}lastest WHERE pageid='index'";
    $result = bmbdb_query($query);
    $line = bmbdb_fetch_array($result);

    $lastuser = $line['lastreged'];
    $totalusers = $line['regednum'];
    $totalposts = $line['postsnum'];

    $start_topic_ratio = floor($totalposts / $totalusers);

    $online_24_user = trim(readfromfile($bbstffile));
    $online_24_user = explode("\n", $online_24_user);
    $online_24user_count = count($online_24_user);

    for($i = 0; $i < $online_24user_count; $i++) {
        $online_24user_detail = explode("|", $online_24_user[$i]);
        $last_24time = getfulldate($online_24user_detail[2]);
        $tfwentad .= "<a href='javascript:alert(\"$arr_ad_lng[0]:$online_24user_detail[1] \t $arr_ad_lng[1]:$last_24time\")'>$online_24user_detail[0]</a> ";
    } 

    $phpver = PHP_VERSION;
    if ($phpver < "4.1.0") {
        $warningver = "<br /><font color=990000>$arr_ad_lng[2]</font>";
    } 
    $phpos = PHP_OS;
    if (isset($_COOKIE)) $testcookie = "<font color=333333>$arr_ad_lng[3]</font>";
    else $testcookie = "<font color=990000>$arr_ad_lng[4]</font>";
    
    writetofile("datafile/lastrunning_version.php", readfromfile("include/version.php"));

    print <<<EOT
<tr><td bgcolor=#14568A><font color=#D6DFF7>
<strong>$arr_ad_lng[5]</strong>
</td></tr>
<tr>
<td bgcolor=#F9FAFE valign=middle align=center>
<font color=#428EFF><strong>$arr_ad_lng[6] $bmbadminid</strong></font>
</td></tr>
</tr>
                
<tr>
<td bgcolor=#FFFFFF valign=middle align=left>
EOT;
    if ($_GET['action'] == "phpinfo") {
        phpinfo();
        echo "</td></tr></table>";
        exit;
    } 
    if ($_GET['tablestatus'] == "y") {
        $results_t = bmbdb_query("SHOW TABLE STATUS");
        $table_infos = "<table width=\"100%\"><tr>
			<td><strong>$arr_ad_lng[903]</strong></td>
			<td><strong>$arr_ad_lng[904]</strong></td>
			<td><strong>$arr_ad_lng[905]</strong></td>
			<td><strong>$arr_ad_lng[906]</strong></td>
			<td><strong>$arr_ad_lng[907]</strong></td>
		</tr>";
        while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
        	if (strstr($line_t[Name], $database_up) == $line_t[Name]) {
	            $table_infos .= "	<tr>
				<td>$line_t[Name]</td>
				<td>$line_t[Rows]</td>
				<td>" . round($line_t[Avg_row_length] / 1024, 2) . " kb</td>
				<td>" . round($line_t[Data_length] / 1024, 2) . " kb</td>
				<td>" . round($line_t[Index_length] / 1024, 2) . " kb</td>
				</tr>";
				$size_total[Rows] += $line_t[Rows];
				$size_total[Avg_row_length] += $line_t[Avg_row_length];
				$size_total[Data_length] += $line_t[Data_length];
				$size_total[Index_length] += $line_t[Index_length];
				$count++;
			}
        } 
	    $table_infos .= "	<tr>
	    <td>$arr_ad_lng[404] $count</td>
	    <td>". $size_total[Rows] ."</td>
	    <td>" . round($size_total[Avg_row_length] / 1024, 2) . " kb</td>
	    <td>" . round($size_total[Data_length] / 1024, 2) . " kb</td>
	    <td>" . round($size_total[Index_length] / 1024, 2) . " kb</td>
	    </tr>";

        $table_infos .= "</table>";
        die($table_infos);
    } 
    
    $mysqlver = bmbdb_server_info();

    print <<<EOT
<center>
$arr_ad_lng[7]<strong><font face=verdana>$current_time ($arr_ad_lng[8] $current_gmt_time)</strong><br />
</center>
$warning
$table_start
<font color=#FFFFFF>
<strong style='color:#FFFFFF;'>$arr_ad_lng[9]</strong>$table_stop
$arr_ad_lng[10] $totalusers
<br />$arr_ad_lng[11] $totalposts 
<br />$arr_ad_lng[12] $start_topic_ratio 
$table_start
<strong>$arr_ad_lng[13]</strong>
$table_stop
$tfwentad
$table_start
<strong>$arr_ad_lng[893]</strong>
$table_stop
$arr_ad_lng[894]&nbsp; &nbsp;<font color=#333333>BMForum <span id='snews'></span></font>
<br />$arr_ad_lng[895]&nbsp; &nbsp;<font color=#333333>$verandproname</font>
<br /><span id='vnews'><strong style='color:red;'>$arr_ad_lng[1062]</strong></span>
$table_start
<strong>$arr_ad_lng[14]</strong>$table_stop

$arr_ad_lng[15]&nbsp; &nbsp;<font color=#333333>$_SERVER[PHP_SELF]</font>
<br />$arr_ad_lng[16]&nbsp; &nbsp;<font color=#333333>$phpver</font>&nbsp; &nbsp;MySQL: <font color=#333333>$mysqlver</font>
<br />$arr_ad_lng[17] <font color=#333333>$phpos</font>
<br />$arr_ad_lng[18]&nbsp; &nbsp; $testcookie $warningver
<br /><a href=admin.php?action=phpinfo>$arr_ad_lng[19]</a>
<br /><a href=admin.php?tablestatus=y>$arr_ad_lng[902]</a>
$table_start
<strong>$arr_ad_lng[20]</strong>$table_stop

<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[21]</td><td bgcolor=FFFFFF width=50%><a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=4081">msxcms</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=1852">Bob Shen</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=3130">IDGnarn</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=21241">swampert</a></td></tr>
<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[22]</td><td bgcolor=FFFFFF width=50%><a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=8513">wueiz</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=11194">Kyle</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=3233">jeanswest</a></td></tr>
<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[23]</td><td bgcolor=FFFFFF width=50%><a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=4792">yutingpc</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=3130">IDGnarn</a></td></tr>
<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[24]</td><td bgcolor=FFFFFF width=50%><a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=1132">mahayu</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=1034">P-Fire</a> <a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=1852">Bob Shen</a></td></tr>
<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[25]</td><td bgcolor=FFFFFF width=50%><a href="http://www.bmforum.com/bmb/profile.php?job=show&memberid=2308">diefish</a></td></tr>
<tr><td bgcolor=FFFFFF width=50%>$arr_ad_lng[26]</td><td bgcolor=FFFFFF width=50%><a href=http://www.bmforum.com>BMForum.com</a></td></tr>

$table_start

<font color=#555555><strong style='color:#FFFFFF;'>Copyright &copy; Bluview Technology. Powered by BMForum, $yeartoday </strong></font>
</font>
</td></tr></table></td></tr></table></td></tr></table>
<script src="http://www.bmforum.com/down/vercheck.php?check_version_lang=$check_version_lang"></script>
<script>
document.getElementById("snews").innerHTML  = newver;
if($kernel_build>=kernel_build){ 
document.getElementById("vnews").innerHTML  = "$arr_ad_lng[896]"+adsnews;
}else{ 
document.getElementById("vnews").innerHTML  = "$arr_ad_lng[897]"+notice+adsnews;
if(enablealert!="") {
	alert(enablealert);
}
}

</script>
</body></html>
EOT;
} 

exit;
