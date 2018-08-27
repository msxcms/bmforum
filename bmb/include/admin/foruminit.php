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

$thisprog = "foruminit.php";

if ($useraccess != "1" || $admgroupdata[2] != "1") {
    adminlogin();
} 
@set_time_limit(300);
print "<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[185]</strong>
		</td></tr>";
if (!$action) {
    print <<<EOT

		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[185]</strong>		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
		<input type=hidden name="action" value="process">
		</td></tr>

$table_start
		<strong>$arr_ad_lng[185]</strong>
$table_stop

		$arr_ad_lng[374]

		<br/>
$tab_top
		$arr_ad_lng[1051]
$tab_bottom	
	$arr_ad_lng[851]
		</td>
		</tr>
</form></tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "7") {
    $query = "TRUNCATE TABLE `{$database_up}primsg`";
    $result = bmbdb_query($query);
    $nquery = "UPDATE {$database_up}userlist SET newmess='0'";
    $result = bmbdb_query($nquery);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[383]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} elseif ($action == "15") {
	$results_t = bmbdb_query("SHOW TABLE STATUS");
    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
    	if (strstr($line_t[Name],$database_up)) 
    	$table_selector .= $table_selector ? ",`$line_t[Name]`" : "`$line_t[Name]`";
    }
	
	
    $query = "REPAIR TABLE $table_selector";
    $result = bmbdb_query($query);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[852]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} elseif ($action == "16") {
    if (!$step) {
        print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle align=center colspan=2>
		<strong>$arr_ad_lng[853]</strong>
		</td></tr>
		<form action="admin.php?bmod=foruminit.php" method="post">
		<input type=hidden name="action" value="16">
		<input type=hidden name="step" value="2">
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		$tab_top
		$arr_ad_lng[854]<br />
		$tab_bottom
		<br />
		SQL <textarea rows="9" cols="60" name=sqlinput></textarea><br />
		&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit value="$arr_ad_lng[855]">
		</td></form>
		</tr>
EOT;
    } elseif ($step == 2) {
        $sqlinput = str_replace("{database_up}", $database_up, stripslashes(stripslashes($sqlinput)));
        $qinput = explode(";", $sqlinput);
        $count = count($qinput);
        for($i = 0;$i < $count;$i++) {
        	if (!trim(str_replace(" ",'',$qinput[$i]))) continue;
            $result = bmbdb_query($qinput[$i]);
            if (@bmbdb_num_rows($result)>0) {
            	$table_infos .= "<table align='center' bgcolor=#14568A width=\"97%\"><tr><td><font color=#FFFFFF>Result of SQL: {$qinput[$i]}</font></td></tr></table><table width=\"97%\" align='center' border=0>";
            	
                $table_infos .= "<tr bgcolor=#F9FAFE><td bgcolor=#F9FAFE>";
                $fields = bmbdb_fetch_fields($result);
                foreach ($fields as $field) {
                    $table_infos .=  $field->name. "</td><td>\n";
                } 
                $table_infos .= "</td></tr>";

                while (false !== ($tmpline = bmbdb_fetch_array($result))) {
                    $table_infos .= "<tr bgcolor=#F9FAFE ><td>". implode("</td><td>", $tmpline) ."</td></tr>";
                    
                }
                $table_infos .= "</table>";
            }
        } 
        

        print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[856]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
		<tr>
		<td bgcolor=#F9FAFE valign=top colspan=2>
		$table_infos
		</td></tr>
EOT;
    } 
} elseif ($action == "14") {
	$results_t = bmbdb_query("SHOW TABLE STATUS");
    while (false !== ($line_t = bmbdb_fetch_array($results_t))) {
    	if (strstr($line_t[Name],$database_up)) 
    	$table_selector .= $table_selector ? ",`$line_t[Name]`" : "`$line_t[Name]`";
    }
	
    $query = "OPTIMIZE TABLE $table_selector";
    $result = bmbdb_query($query);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[857]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} elseif ($action == "10") {
    $query = "TRUNCATE TABLE `{$database_up}search`";
    $result = bmbdb_query($query);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[858]</strong></center><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
} elseif ($action == "11") {
    $dh = opendir("images/avatars");
    while (false !== ($imagefile = readdir($dh))) {
        if (($imagefile != ".") && ($imagefile != "..") && ($imagefile != ""))
            $avatarsshow .= $imagefile . "\n";
    } 
    closedir($dh);
    writetofile("datafile/avatar.dat", $avatarsshow);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[859]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} elseif ($action == "12") {
    $query = "SELECT * FROM {$database_up}userlist";
    $result = bmbdb_query($query);

    while (false !== ($tmpline = bmbdb_fetch_array($result))) {
        if ($tmpline['birthday']) {
            $bornchenck = explode("-", $tmpline['birthday']);
            $bornchenck[1] = @floor($bornchenck[1]);
            $bornchenck[2] = @floor($bornchenck[2]);
            $month = $bornchenck[1];
            $bornwrite[$month][$bornchenck[2]] .= "{$tmpline['username']}|$bornchenck[2]|$bornchenck[0]|\n";
        } 
    } 

    for($i = 1;$i < 13;$i++) {
    	if (count($bornwrite[$i])>0) {
	    	foreach ($bornwrite[$i] as $key=>$value) {
				writetofile("datafile/birthday/{$i}_$key", $value);
			}
        }
    } 
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[860]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} elseif ($action == "13") {
    $avatarsshow = "";
    $dh = opendir("datafile/style");
    while (false !== ($stylelist = readdir($dh))) {
        if (($stylelist != ".") && ($stylelist != "..") && strpos($stylelist, ".bs5")) {
            include("datafile/style/$stylelist");
            $dir = "datafile/cache/themes/" . $styleidcode;
            $dirhandle = @opendir($dir);
            while (false !== ($file_name = @readdir($dirhandle))) {
                if ($file_name != "." && $file_name != "..") {
                    @unlink($dir . "/" . $file_name);
                } 
            } 
            @mkdir($dir, 0777);
            $skinsshow .= "<?php //|" . $styleidcode . "|" . $skinrealname . "|" . $stylelist . "|\n";
        } 
    } 
    closedir($dh);
    writetofile("datafile/stylelist.php", $skinsshow);
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[861]</strong></center>
		<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=foruminit.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;
} 
