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

$thisprog = "setuser.php";

if ($useraccess != "1" || $admgroupdata[9] != "1") {
    adminlogin();
} 

if (file_exists('datafile/reg_custom.php')) {
	$reg_c = file("datafile/reg_custom.php"); 

	if (is_array($reg_c)) {
		foreach ($reg_c as $key => $value){
			$reg_sc[]=explode("|", $value);
		}
	}
} else $reg_sc = "";

@set_time_limit(300);
print <<<EOT
	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
	<strong>$arr_ad_lng[320] $arr_ad_lng[197]</strong>
	</td></tr>
EOT;
if (empty($action)) setuser_index();
elseif ($action == "updatecount") update_list();
elseif ($action == "view") view_user();
elseif ($action == "edit") edit_user();
elseif ($action == "kill") kill_user();
elseif ($action == "register") register_user();
elseif ($action == "changeug") changeug();
elseif ($action == "cancelbinding") cancelbinding();
elseif ($action == "combine") combine();
exit;
function combine() 
{
	global $userlists, $arr_ad_lng, $database_up;
	
	$suserlists = explode("\n", $userlists);
	for ($i = 0;$i < count($suserlists);$i++) 
	{
		$thisname = strip_tags(trim($suserlists[$i]));
		if ($i==0) $firstname = $thisname;
		$newusers.= $newusers ? ",'$thisname'" : "'$thisname'";
	}
	
    $result = bmbdb_query("SELECT * FROM {$database_up}userlist WHERE username='$firstname'");
    $line = bmbdb_fetch_array($result);
    $firstuid=$line['userid'];
    
    $nquery = "SELECT * FROM {$database_up}userlist WHERE username in($newusers)";
    $result = bmbdb_query($nquery);
    while ($row = bmbdb_fetch_array($result)) {
    	$point+=$row['point'];
    	$digestmount+=$row['digestmount'];
    	$money+=$row['money'];
    	$postamount+=$row['postamount'];
    	$userid=$row['userid'];
    	if ($userid != $firstuid) $uidlist.= $uidlist ? ",'$userid'" : "'$userid'";
    }
    
    bmbdb_query("UPDATE {$database_up}userlist SET `point`='$point',`digestmount`='$digestmount',`money`='$money',`postamount`='$postamount' WHERE userid='$firstuid'");
    bmbdb_query("DELETE FROM {$database_up}userlist WHERE userid in($uidlist)");
    bmbdb_query("UPDATE {$database_up}posts SET `usrid`='$firstuid',`username`='$firstname' WHERE usrid in($uidlist)");
    bmbdb_query("UPDATE {$database_up}threads SET `authorid`='$firstuid',`author`='$firstname' WHERE authorid in($uidlist)");
    bmbdb_query("UPDATE {$database_up}primsg SET `belong`='$firstname',`blid`='$firstuid' WHERE blid in($uidlist)");
    bmbdb_query("UPDATE {$database_up}primsg SET `sendto`='$firstname',`stid`='$firstuid' WHERE stid in($uidlist)");
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>&nbsp;$arr_ad_lng[75]</strong></center><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=setuser.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;


    exit;
}
function changeug() 
{
	global $userlists, $arr_ad_lng, $memberusertype, $database_up;
	
	$suserlists = explode("\n", $userlists);
	for ($i = 0;$i < count($suserlists);$i++) 
	{
		$thisname = strip_tags(trim($suserlists[$i]));
		$newusers.= $newusers ? ",'$thisname'" : "'$thisname'";
	}
	
    $nquery = "UPDATE {$database_up}userlist SET ugnum='$memberusertype' WHERE username in($newusers)";
    $result = bmbdb_query($nquery);
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>&nbsp;$arr_ad_lng[75]</strong></center><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=setuser.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;


    exit;
}
function cancelbinding() 
{
	global $userlists, $mid, $arr_ad_lng, $memberusertype, $database_up;
	
	bmbdb_query("UPDATE {$database_up}userlist SET `parusrid`=0 WHERE `parusrid`='$mid'");
	bmbdb_query("UPDATE {$database_up}userlist SET `parusrid`=0 WHERE `userid`='$mid'");
    
    print <<<EOT
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>&nbsp;$arr_ad_lng[75]</strong></center><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=setuser.php">$arr_ad_lng[76]</a></td></tr></table></body></html>
EOT;


    exit;
}
function view_user()
{
    global $member, $with, $database_up, $table_start, $table_stop, $reg_c, $gl, $bmfopt, $arr_ad_lng, $tab_top, $usergroupdata, $tab_bottom, $smodaccess, $useraccess, $id_unique, $thisprog;
    echo "<tr>
		<td bgcolor=#F9FAFE align=center colspan=2>
		<strong>$arr_ad_lng[700]</strong><br />";
	if (!($with == "id" && $member && check_user_exi($member, 1))) {
	    if (empty($member) || $member == "." || $member == ".." || !check_user_exi($member)) {
	        print ("<br />$tab_top<strong>$arr_ad_lng[701]</strong>$tab_bottom</td></tr></table></body>$tab_bottom");
	        exit;
	    } 
    }

    $line = ($with == "id") ? get_user_info($member, "usrid") : get_user_info($member);
    extract($line, EXTR_PREFIX_SAME, "user");

    list($regdate, $regip) = explode("_", $regdate);
    $regdate = getfulldate($regdate);
    $bym = floor($point / 10);

    $userac = explode("|", $usergroupdata[$line['ugnum']]);

    if (($smodaccess == "1" && $useraccess != "1") && ($userac[21] == "1" || $userac[22] == "1" || $userac[24] == "1")) {
        echo "<br />$tab_top<strong>$arr_ad_lng[702]</strong>$tab_bottom</td></tr></table></body>$tab_bottom";
        exit;
    } 
    
	$count_rc = count($reg_c);
	
	for($i = 0;$i < $count_rc; $i++){
		$detail = explode("|", $reg_c[$i]);
		$reg_ssc["$detail[0]"] = $detail[1];
		$reg_type["$detail[0]"] = $detail[3];
		$reg_select["$detail[0]"] = $detail[3] == 3 ? unserialize(base64_decode($detail[4])) : "";
	}
    
    $custom = unserialize(base64_decode($line['baoliu2']));
    
	$get_par_usr = bmbdb_query("SELECT * FROM {$database_up}userlist WHERE `parusrid`='$line[userid]'");
	
	while (false !== ($tmprow = bmbdb_fetch_array($get_par_usr))) {
		$bind_list .= "$arr_ad_lng[1194]<a href='admin.php?bmod=setuser.php&action=view&with=id&member=$tmprow[userid]'>$tmprow[username]</a><br />";
	}
    
	if ($line['parusrid']) {
		$tmprow = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}userlist WHERE `userid`='$line[parusrid]'"));
		$bind_list .= "$arr_ad_lng[1193]<a href='admin.php?bmod=setuser.php&action=view&with=id&member=$tmprow[userid]'>$tmprow[username]</a><br />";
	}
	
	if ($bind_list) {
		$bind_list .= "<strong><a href='admin.php?bmod=setuser.php&action=cancelbinding&mid=$line[userid]'>$arr_ad_lng[1195]</a></strong>";
	} else {
		$bind_list = "$arr_ad_lng[1196]";
	}




    
    if ($bmfopt["ip_address"]) {
    	$regip = "<a href='{$bmfopt[ip_address]}$regip' target='_blank'>$regip</a>";
		$hisipa = "<a href='{$bmfopt[ip_address]}$hisipa' target='_blank'>$hisipa</a>";
		$hisipb = "<a href='{$bmfopt[ip_address]}$hisipb' target='_blank'>$hisipb</a>";
		$hisipc = "<a href='{$bmfopt[ip_address]}$hisipc' target='_blank'>$hisipc</a>";
	}

    print <<<EOT
$table_start
		<font face=verdana><strong>"$line[username]" ($gl[512] {$line['userid']})</strong> $arr_ad_lng[703] 　 [ <a style='color:#FFFFFF;' href="admin.php?bmod=$thisprog&action=edit&with=id&member=$line[userid]">$arr_ad_lng[704]</a> ] | [ <a style='color:#FFFFFF;' href="admin.php?bmod=$thisprog&action=kill&with=id&member=$line[userid]">$arr_ad_lng[705]</a> ]</font>
</td></tr><tr>
    <td bgcolor=#F9FAFE width=30%><strong>$arr_ad_lng[706]</strong></td>
    <td bgcolor=#F9FAFE>$regdate</td></tr>
    <tr>
    <td bgcolor=#F9FAFE width=30%><strong>$arr_ad_lng[707]</strong></td>
    <td bgcolor=#F9FAFE>$regip</td></tr>
    <tr>
    <td bgcolor=#F9FAFE width=30%><strong>$arr_ad_lng[708]</strong></td>
    <td bgcolor=#F9FAFE>$hisipa<br />$hisipb<br />$hisipc</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[709]</strong></td>
    <td bgcolor=#F9FAFE>$pwd</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[710]</strong></td>
    <td bgcolor=#F9FAFE>$mailadd</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[711]</strong></td>
    <td bgcolor=#F9FAFE>$qqmsnicq</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[712]</strong></td>
    <td bgcolor=#F9FAFE>$headtitle</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[713]</strong></td>
    <td bgcolor=#F9FAFE>{$userac[0]}</td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[714]</strong></td>
    <td bgcolor=#F9FAFE>$postamount <br /><br />
    </td></tr>
	<tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[1192]</strong></td>
    <td bgcolor=#F9FAFE>$bind_list <br />
    </td></tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[715]</strong></td>
    <td bgcolor=#F9FAFE>$bym <br /><br />
    </td></tr>
EOT;
	if (is_array($custom)) {
		foreach ($custom as $key => $value){
			$custom_herevalue = $reg_type["$key"] == 3 ? $reg_select["$key"]["$value"] : $value;
echo <<<EOT
    <tr>
    <td bgcolor=#F9FAFE><strong>{$reg_ssc[$key]}</strong></td>
    <td bgcolor=#F9FAFE>$custom_herevalue <br /><br />
    </td></tr>
EOT;

		}
	}
echo<<<EOT
    
    <tr>
    <td bgcolor=#F9FAFE colspan=2><strong>&nbsp;</strong>
    >> <a href="javascript:history.go(-1)">$arr_ad_lng[361]</a>
    </td></tr>
    </td></tr></table></body></html>
EOT;
} 

function edit_user()
{
    global $id_unique, $reg_sc, $table_start, $table_stop, $gl, $with, $language, $arr_ad_lng, $tab_top, $database_up, $smodaccess, $useraccess, $tab_bottom, $usergroupdata, $thisprog, $member, $checkaction;
    $today = getdate();
    $month = $today['mon'];
    $mday = $today['mday'];
    $year = $today['year'];

	require("lang/$language/usercp.php");


    $todayshow = "$year/$month/$mday";

    echo "<tr>
		<td bgcolor=#F9FAFE align=center colspan=2>
		<strong>$arr_ad_lng[716]</strong><br />";
	if (!($with == "id" && $member && check_user_exi($member, 1))) {
	    if (empty($member) || $member == "." || $member == ".." || !check_user_exi($member)) {
	        print ("<br />$tab_top<strong>$arr_ad_lng[701]</strong>$tab_bottom</td></tr></table></body>$tab_bottom");
	        exit;
	    } 
	}
	
    $line = ($with == "id") ? get_user_info($member, "usrid") : get_user_info($member);
    extract($line, EXTR_PREFIX_SAME, "user");

    list($openit, $eudate, $eugroup) = explode(",", $line['foreuser']);
    if ($openit == "yes") $euforopen = "checked='checked'";

    $custom = unserialize(base64_decode($line['baoliu2']));


    if ($eudate == "") {
        $eudate = $todayshow;
    } else {
        $today = getdate($eudate);
        $month = $today['mon'];
        $mday = $today['mday'];
        $year = $today['year'];
        $todayshow = "$year/$month/$mday";
        $eudate = $todayshow;
    } 
    $bym1 = floor($point / 10);
    
    if ($line['baoliu1'] && $line['baoliu1'] != ",") {
        $plusug = explode(",", $line['baoliu1']);
        $count = count($plusug);
        for ($i = 0; $i < $count; $i++){
            $selected["$plusug[$i]"] = "selected";
        }
    }
    
    $count = count($usergroupdata);
    foreach ($usergroupdata as $key=>$value) {
        $userac = explode("|", $value);
        $seusert .= "<option value=$key>$userac[0]</option>";
        $seusertug .= "<option value=\"$key\" {$selected[$key]}>$userac[0]</option>";
    } 

    $targerusertype = explode("|", $usergroupdata[$line['ugnum']]);
    if (($smodaccess == "1" && $useraccess != "1") && ($targerusertype[21] == "1" || $targerusertype[22] == "1" || $targerusertype[24] == "1")) {
        echo "<br />$tab_top<strong>$arr_ad_lng[702]</strong>$tab_bottom</td></tr></table></body>$tab_bottom";
        exit;
    } 
    
    $signtext = htmlspecialchars($signtext);
    
    $sexselected[$sex] = "selected='selected'";
    
    if ($checkaction != "yes") {
        print <<<EOT
    <form action="admin.php?bmod=$thisprog" method=post>
    <input type=hidden name="action" value="edit">
    <input type=hidden name="checkaction" value="yes">
    <input type=hidden name="with" value="id">
    <input type=hidden name="member" value="{$line['userid']}">
    <input type=hidden name="newhisipc" value="$hisipc"><input type=hidden name="newhisipb" value="$hisipb"><input type=hidden name="newhisipa" value="$hisipa">
    <script type="text/javascript">
    function cleandata(){
    	for (var i=0;i<document.getElementById('newaddug').options.length;i++){
    		document.getElementById('newaddug')[i].selected='';
    	}
    }
    </script>
$table_start<strong>$arr_ad_lng[717] </strong>$line[username] ($gl[512] $line[userid])</td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE>$arr_ad_lng[1009]</td>
    <td bgcolor=#F9FAFE><input type=text name="newusername" value=""></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[712]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="honor" value="$headtitle"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[718]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newgroup" value="$team"></td>
    </tr>
    	    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[719]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newmoney" value="$money"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[720]</strong></td>
    <td bgcolor=#F9FAFE><select name='newsex'><option value="Male" $sexselected[Male]>$show_form_lng[0]</option><option value="Female" $sexselected[Female]>$show_form_lng[1]</option><option value="Unknown">$show_form_lng[2]</option></select></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[721]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newborn" value="$birthday"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[722]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newnational" value="$national"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE>$arr_ad_lng[723]</td>
    <td bgcolor=#F9FAFE><select name="newusertype"><option value="{$line['ugnum']}" selected>$arr_ad_lng[724]</option>{$seusert}</select></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE>$arr_ad_lng[1036]<br /><input type="button" value="$arr_ad_lng[449]" onclick="javascript:cleandata();"></td>
    <td bgcolor=#F9FAFE><select multiple="multiple" size=4 style="width: 50%;" name="newaddug[]" id="newaddug" style="width: 120px">{$seusertug}</select></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[1011]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newdigestmount" value="$digestmount"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[714]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newpostamount" value="$postamount"></td>
    </tr>
<tr>    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[715]</strong></td>    <td bgcolor=#F9FAFE><input type=text name="newbym" value="$bym1"></td>    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[709]</strong></td>
    <td bgcolor=#F9FAFE><input type=password name="newuserpwd" value="$pwd"></td>
    </tr>
    <tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[710]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newuseremail" value="$mailadd"></td>
    </tr><tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[725]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newhomepage" value="$homepage"></td>
    </tr><tr>
    <td bgcolor=#F9FAFE><strong>QQ/MSN/ICQ:</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newusericq" value="$qqmsnicq"></td>
    </tr><tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[726]</strong></td>
    <td bgcolor=#F9FAFE><input type=checkbox name="newforeuser" $euforopen value="yes">$arr_ad_lng[727]<br />$arr_ad_lng[728]<input type=text name="neweudate" value="$eudate">$arr_ad_lng[729]<select name="neweutype"><option value="$eugroup" selected>$arr_ad_lng[724]</option>{$seusert}</select></td>
    </tr><tr>
	<td bgcolor=#F9FAFE><strong>$arr_ad_lng[730]</strong></td> 
	<td bgcolor=#F9FAFE><textarea cols=50 name="newsign" $style2 onMouseOver="this.style.backgroundColor = '#E5F0FF'" onMouseOut="this.style.backgroundColor = ''" rows="4">$signtext</textarea></td>
	</tr><tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[731]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newarea" value="$fromwhere">
    </td>
    </tr><tr>
    <td bgcolor=#F9FAFE><strong>$arr_ad_lng[732]</strong></td>
    <td bgcolor=#F9FAFE><input type=text name="newusericon" value="$avarts">
    </td>
    </tr>
EOT;

    if(is_array($reg_sc)) {
		if (is_array($custom)) {
	    	foreach ($custom as $key => $value) {
	    		$custom[$key] = htmlspecialchars(str_replace("<br />", "\n", $value));
	    	}
	    }
    	foreach($reg_sc as $key => $value) {
			if($value['3'] == 1) {
				$here = '<input type="text" size="20" name="custom_var['.$value['0'].']" value="'.$custom[$value['0']].'" />';
			} elseif($value['3'] == 2) { 
				$here = '<textarea cols="50" name="custom_var['.$value['0'].']" rows="4">'.$custom[$value['0']].'</textarea>';
			} elseif($value['3'] == 3) {
				$here = '<select name="custom_var['.$value['0'].']">
				<option value="'.$custom[$value['0']].'">不改变当前设置</option>'.base64_decode($value['11']).'</select>';
			} 
    		
echo<<<EOT
<tr>
    <td bgcolor=#F9FAFE><strong>$value[1]</strong><br/>$value[2]</td>
    <td bgcolor=#F9FAFE>$here
    </td>
    </tr>
EOT;
    	}
    }
    
echo<<<EOT
    $table_start
    <input type=submit value="$arr_ad_lng[66]" name=submit></td></tr>
    
    </tr></form>

EOT;
    } else {
        global $newdigestmount, $newuserpwd, $newusername, $database_up, $newusericon, $newuseremail,
        $newusericq, $arr_ad_lng, $reg_sc, $custom_var, $newhomepage, $newarea, $usertype,
        $honor, $newpostamount, $newbym, $newsign, $lastlogin, $tlastvisit, $deltopic, $delreply, $uploadfiletoday, $lastupload, $newusertype, $newmoney, $newborn, $newgroup, $newsex, $newnational, $newforeuser, $neweudate, $neweutype, $newhisipa, $newhisipb, $newhisipc;

        // member custom information.
        
        $custom_info = unserialize(base64_decode($line['baoliu2']));
        
        $new_custom = $new_custom_sql = "";
        
        if (is_array($reg_sc)) {
        	foreach ($reg_sc as $key => $value) {
        		if ($custom_var["$value[0]"] != "") {
       				$new_custom["$value[0]"] = stripslashes($custom_var["$value[0]"]);
       			}
        	}
        }
        

        if (!empty($_POST['newuserpwd']) && $pwd != $newuserpwd) $pwd = md5($newuserpwd);
        
        if (!empty($_POST['newusername'])) {
            if (!check_user_exi($newusername)) {
                $changename = " username='$newusername',";
            } else {
		        print"
		        <tr>
		        <td bgcolor=#F9FAFE align=center colspan=2>
		        <strong>$arr_ad_lng[1010]</strong>
		        </td></tr>";
		        echo "</td></tr></table></body></html>";
		        exit;
            }
        }

        $usericon = $_POST['newusericon'];
        $useremail = $_POST['newuseremail'];
        $usericq = $_POST['newusericq'];
        $homepage = $_POST['newhomepage'];
        $area = $_POST['newarea'];
        $postamount = $_POST['newpostamount'];
        $bym = 10 * $_POST['newbym'];
        $sign = $_POST['newsign'];
        
        $sqlnewaddug = @implode(",", $_POST['newaddug']);
        if ($sqlnewaddug == ",") $sqlnewaddug = "";
            else $sqlnewaddug .= ",";

        $tmpdate = explode("/", $_POST['neweudate']);
        $tmptimes = mktime (0, 0, 0, $tmpdate[1], $tmpdate[2], $tmpdate[0]);
        $newinformation = "$newforeuser,$tmptimes,$neweutype";
        
        if ($new_custom) {
        	$new_custom_sql = ",baoliu2='".base64_encode(serialize($new_custom))."'";
        }
        
        $newusertype = $_POST['newusertype'];
        
        $nquery = "UPDATE {$database_up}userlist SET {$changename} birthday='$newborn',baoliu1='$sqlnewaddug',national='$newnational',sex='$newsex',digestmount='{$_POST['newdigestmount']}',money='{$_POST['newmoney']}',foreuser='$newinformation',signtext='$sign',point='$bym',postamount='$postamount',headtitle='$honor',team='$newgroup',fromwhere='$area',ugnum='$newusertype',homepage='$homepage',qqmsnicq='$usericq',mailadd='$useremail',pwd='$pwd',avarts='$usericon'{$new_custom_sql} WHERE userid='{$line[userid]}'";
        $result = bmbdb_query($nquery);
        print"
        <tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[734]</strong><br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a>
        </td></tr>";
    } 
    echo "</td></tr></table></body></html>";
    exit;
} 

function kill_user()
{
    global $method, $id_unique, $gl, $arr_ad_lng, $step, $delname, $tab_top, $database_up, $tab_bottom, $usergroupdata, $smodaccess, $useraccess, $thisprog, $member, $with;
    echo "<tr>
		<td bgcolor=#F9FAFE align=center colspan=2>
		<strong>$arr_ad_lng[735]</strong><br />";
	if ($method == "batch" && $delname) {
	    $delsql = "userid='". implode("' or userid='", $delname) ."'";
	    
        $nquery = "DELETE FROM {$database_up}userlist WHERE $delsql";
        $result = bmbdb_query($nquery);

        print "
        <tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[737]</strong><br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a>
        </td></tr>";
        exit;
	}
	if (!($with == "id" && $member && check_user_exi($member, 1))) {
	    if (empty($member) || $member == "." || $member == ".." || !check_user_exi($member)) {
	        print ("<br />$tab_top<strong>$arr_ad_lng[701]</strong>$tab_bottom</td></tr></table></body>$tab_bottom");
	        exit;
	    } 
	}
    $line = ($with == "id") ? get_user_info($member, "usrid") : get_user_info($member);
    extract($line, EXTR_PREFIX_SAME, "user");
    
    if (!$step) {
        print ("<br />$tab_top<strong>$arr_ad_lng[736] $line[username] ($gl[512] $line[userid]) ?</strong><br /><ul><li><a href=admin.php?bmod=setuser.php&action=kill&with=id&member=" . $line['userid'] . "&step=2>$arr_ad_lng[457]</a></li><li><a href=admin.php?bmod=setuser.php>$arr_ad_lng[458]</a></li></ul>$tab_bottom</td></tr></table></body>$tab_bottom");
        exit;
    } else {

        $targerusertype = explode("|", $usergroupdata[$line['ugnum']]);
        if (($smodaccess == "1" && $useraccess != "1") && ($targerusertype[21] == "1" || $targerusertype[22] == "1" || $targerusertype[24] == "1")) {
            echo "<br />$tab_top<strong>$arr_ad_lng[702]</strong>$tab_bottom</td></tr></table></body>$tab_bottom";
            exit;
        } 

        $nquery = "DELETE FROM {$database_up}userlist WHERE userid='$line[userid]'";
        $result = bmbdb_query($nquery);

        print "
        <tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[737]</strong><br /><br />&nbsp;&gt;&gt; <a href=\"admin.php?bmod=$thisprog\">$arr_ad_lng[76]</a>
        </td></tr>";

        exit;
    } 
} 
function update_list()
{
    global $upcount, $arr_ad_lng, $uplist, $thisprog, $upadmin, $database_up, $tab_top, $tab_bottom, $id_unique;
    $query = "SELECT COUNT(*) FROM {$database_up}userlist";
    $result = bmbdb_query($query, 0);
    $xcount = bmbdb_fetch_array($result);
    $count = $xcount['COUNT(*)'];

    $nquery = "UPDATE {$database_up}lastest SET regednum = '$count' WHERE pageid='index'";
    $result = bmbdb_query($nquery);
    print ("<tr>
	<td bgcolor=#F9FAFE align=center colspan=2>
	<strong>$arr_ad_lng[739]</strong><br /><br />
	$tab_top
	$arr_ad_lng[740] $count $arr_ad_lng[741]
	$tab_bottom
	<br />");

    echo "<br />&gt;&gt; <a href=index.php>$arr_ad_lng[744]</a> <a href=admin.php?bmod=$thisprog>$arr_ad_lng[745]</a>
</td></tr></table></body></html>";
    exit;
} 
function register_user()
{
    global $member, $memberpass, $arr_ad_lng, $membermail, $database_up, $memberusertype, $timestamp, $thisprog, $tab_top, $tab_bottom, $reg_money, $id_unique;
    $memberpass = md5($memberpass);

    $member = strtolower($member);
    
    if (check_user_exi($member)) {
	    print"
	    <tr>
	    <td bgcolor=#F9FAFE align=center colspan=2>
	    <strong>$arr_ad_lng[1010]</strong>
	    </td></tr>";
	    echo "</td></tr></table></body></html>";
	    exit;
    }
    $newsalt = geneSalt();
    $nquery = "insert into {$database_up}userlist (username,pwd,mailadd,regdate,postamount,ugnum,money,avarts,qqmsnicq,signtext,homepage,fromwhere,desper,headtitle,pwdask,birthday,team,sex,national,foreuser,baoliu1,baoliu2,personug,activestatus,salt) values ('$member','$memberpass','$membermail','$timestamp','0','$memberusertype','$reg_money','','','','','','','','','','','','','','','','','','$newsalt')";
    $result = bmbdb_query($nquery);

    $nquery = "UPDATE {$database_up}lastest SET lastreged='$member',regednum = regednum+1 WHERE pageid='index'";
    $result = bmbdb_query($nquery);
    print ("<tr>
	<td bgcolor=#F9FAFE align=center colspan=2>
	<strong>$arr_ad_lng[746]</strong><br /><br />
	$tab_top
	$arr_ad_lng[747]
	$tab_bottom
	<br />");

    echo "<br />&gt;&gt; <a href=index.php>$arr_ad_lng[744]</a> <a href=admin.php?bmod=$thisprog>$arr_ad_lng[748]</a>
</td></tr></table></body></html>";
    exit;
} 

function setuser_index()
{
    global $search, $orderby, $admin_log_hash, $pm, $delsure, $table_start, $table_stop, $gl, $thisprog, $timestamp, $tab_bottom, $arr_ad_lng, $database_up, $userperpage, $jumppage, $page, $type, $list, $usergroupdata, $tab_top;
    
	$sqls = "";
	if ($orderby == "group") {
	    $sqls = "ORDER BY ugnum";
	} elseif ($orderby == "name") {
	    $sqls = "ORDER BY username";
	} elseif ($orderby == "date") {
	    $sqls = "ORDER BY regdate";
	} elseif ($orderby == "amount") {
	    $sqls = "ORDER BY postamount DESC";
	} elseif ($orderby == "score") {
	    $sqls = "ORDER BY point DESC";
	} else {
	    $orderby = "";
	}
    
    if (!empty($jumppage)) $gotpage = $jumppage;
    else $gotpage = $page;
    $count = count($usergroupdata);
    foreach ($usergroupdata as $key=>$value) {
        $userac = explode("|", $value);
        $seusert .= "<option value=$key>$userac[0]</option>";
    } 
    $echoinfolist .= "<tr bgcolor=#6DA6D1 width='100%'><form name='delones' action='admin.php?bmod=setuser.php&action=kill&method=batch' method='POST'>";
    $echoinfolist .= "<td align=center width=6%><strong style='color:#FFFFFF;'>$arr_ad_lng[411]</strong></td>";
    $echoinfolist .= "<td align=center width=15%><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list&orderby=group'>$arr_ad_lng[749]</a></strong></td>";
    $echoinfolist .= "<td align=center width=5%><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list'>$gl[512]</a></strong></td>";
    $echoinfolist .= "<td align=center width=25%><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list&orderby=name'>$arr_ad_lng[750]</a></strong></td>";
    $echoinfolist .= "<td align=center width=10%><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list&orderby=date'>$arr_ad_lng[751]</a></strong></td>";
    $echoinfolist .= "<td align=center><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list&orderby=amount'>$arr_ad_lng[752]</a></strong></td>";
    $echoinfolist .= "<td align=center><strong><a style='color:#FFFFFF;' href='admin.php?bmod=setuser.php&type=$type&list=$list&orderby=score'>$gl[511]</a></strong></td>";
    $echoinfolist .= "<td align=center><strong style='color:#FFFFFF;'>$arr_ad_lng[753]</strong></td></tr>";

    $query = "SELECT COUNT(*) FROM {$database_up}userlist";
    $result = bmbdb_query($query, 0);
    $fcount = bmbdb_fetch_array($result);
    $fcount = $fcount['COUNT(*)'];
    
    if ($type != "") {
        $addquerysql = "ugnum='$type'";
    }
    
    if ($search == 1) {
    	global $seatips, $seausertype, $searegdatelate, $searegdateago, $sealastlogin, $seapostamountbig, $seapostamountsmall, $seausername, $seamailadd, $searegips, $seapointsmall, $seapointbig;
    	if (empty($addquerysql)) $addquerysql = "postamount!='-111aaa'";
    	if ($seausername != "") {
    	    $addquerysql .= " AND username LIKE '". str_replace("*","%", $seausername)."' ";
    	    $nextpage = "&seausername=". urlencode($seausername);
    	}
    	
    	for ($ui = 0; $ui < count($seausertype); $ui++){
    	    $nextpage .= "&seausertype[]=". $seausertype[$ui];
    	    $ugnumsql.= ($ui != 0) ? ",$seausertype[$ui]" : "$seausertype[$ui]";
    	}
   	    if ($ugnumsql != "") $addquerysql .= " AND ugnum in($ugnumsql)";
   	    
    	if ($seamailadd != "") {
    	    $addquerysql .= " AND mailadd LIKE '". str_replace("*","%", $seamailadd)."' ";
    	    $nextpage .= "&seamailadd=". urlencode($seamailadd);
    	}
    	if ($searegips != "") {
    	    $addquerysql .= " AND regdate LIKE '_%". str_replace("*","%", $searegips)."' ";
    	    $nextpage .= "&searegips=". urlencode($searegips);
    	}
    	if ($seapointsmall != "") {
    		$seapointsmall = $seapointsmall * 10;
    	    $addquerysql .= " AND point<$seapointsmall";
    	    $nextpage .= "&seapointsmall=". urlencode($seapointsmall);
    	}
    	if ($seapointbig != "") {
    		$seapointbig = $seapointbig * 10;
    	    $addquerysql .= " AND point>=$seapointbig";
    	    $nextpage .= "&seapointbig=". urlencode($seapointbig);
    	}
    	if ($seapostamountsmall != "") {
    	    $addquerysql .= " AND postamount<$seapostamountsmall";
    	    $nextpage .= "&seapostamountsmall=". urlencode($seapostamountsmall);
    	}
    	if ($seapostamountbig != "") {
    		$nextpage .= "&seapostamountbig=". urlencode($seapostamountbig);
    	    $addquerysql .= " AND postamount>=$seapostamountbig";
    	}
    	if ($seatips != "") {
    		$nextpage .= "&seatips=". urlencode($seatips);
    	    $addquerysql .= " AND (hisipa LIKE '". str_replace("*","%", $seatips) ."' OR hisipb LIKE '". str_replace("*","%", $seatips) ."' OR hisipc LIKE '". str_replace("*","%", $seatips) ."')";
    	}
    	if ($sealastlogin != "") {
    		$nextpage .= "&sealastlogin=". urlencode($sealastlogin);
    	    $addquerysql .= " AND lastlogin<=". ($timestamp - $sealastlogin*86400);
    	}
    	if ($searegdateago != "") {
    		$nextpage .= "&searegdateago=". urlencode($searegdateago);
    		$regdateago = explode("-", $searegdateago);
    		$lastlg = mktime (0, 0, 0, $regdateago[1], $regdateago[2], $regdateago[0]);
    	    $addquerysql .= " AND regdate<'$lastlg' ";
    	}
    	if ($searegdatelate != "") {
    		$nextpage .= "&searegdatelate=". urlencode($searegdatelate);
    		$regdatelate = explode("-", $searegdatelate);
    		$lastlg = mktime (0, 0, 0, $regdatelate[1], $regdatelate[2], $regdatelate[0]);
    	    $addquerysql .= " AND regdate>='$lastlg' ";
    	}
    	if ($nextpage) $nextpage .= "&search=1";
    }
    if ($pm) {
    	$url_sql = urlencode($addquerysql);
	    switch ($pm) {
	    	case $arr_ad_lng[1123]:
	    		$jump_info = "admin.php?bmod=messageuser.php&specify_sql=".$url_sql;
	    		break;
	    	case $arr_ad_lng[1124]:
	    		$jump_info = "admin.php?bmod=mailuser.php&specify_sql=".$url_sql;
	    		break;
	    	case $arr_ad_lng[1125]:
	    		$jump_info = "admin.php?bmod=messageuser.php&award=1&specify_sql=".$url_sql;
	    		break;
	    }
	    echo "<meta http-equiv=\"Refresh\" content=\"0; URL=" . $jump_info . "&verify=$admin_log_hash\" />";
	    exit;
    }
    if ($delsure == 1) {
        $query = "DELETE FROM {$database_up}userlist WHERE $addquerysql";
        $result = bmbdb_query($query);
        print "
        <tr>
        <td bgcolor=#F9FAFE align=center colspan=2>
        <strong>$arr_ad_lng[737]</strong>
        </td></tr>";

        exit;
    }

    if ($type == "" && !$search) {
        $pageshow = $page;
        if (empty($page)) {
            $page = 0;
            $pageshow = 1;
        } 
        if (!empty($jumppage)) {
            $page = $jumppage-1;
            $pageshow = $page + 1;
        } 
        $page = $page * $userperpage;
        $n = floor($fcount / $userperpage) + 1;
        $count = $page + $userperpage;
        $query = "SELECT * FROM {$database_up}userlist $sqls LIMIT $page,$userperpage";
        $result = bmbdb_query($query);
    } else {
        $query = "SELECT COUNT(*) FROM {$database_up}userlist WHERE $addquerysql";
        $result = bmbdb_query($query, 0);
        $fcount = bmbdb_fetch_array($result);
        $fcount = $fcount['COUNT(*)'];
        $pageshow = $page;
        if (empty($page)) {
            $page = 0;
            $pageshow = 1;
        } 
        if (!empty($jumppage)) {
            $page = $jumppage-1;
            $pageshow = $page + 1;
        } 
        $page = $page * $userperpage;
        $n = floor($fcount / $userperpage) + 1;
        $count = $page + $userperpage;
        $query = "SELECT * FROM {$database_up}userlist WHERE $addquerysql $sqls LIMIT $page,$userperpage";
        $result = bmbdb_query($query);
    } while ($row = bmbdb_fetch_array($result)) {
        if ($row['username'] != "") {
            $dsinfo = explode("|", $usergroupdata[$row['ugnum']]);
            $uname = $dsinfo[0];
            $regdate = getfulldate($row['regdate']);
            
            $scores = floor($row['point'] / 10);

            $echoinfolist .= "<tr width='100%' height=*>";
            $echoinfolist .= "<td align=center width=5%><input type='checkbox' name='delname[]' value=\"{$row['userid']}\"></td>";
            $echoinfolist .= "<td align=center width=15%>$uname</td>";
            $echoinfolist .= "<td align=center width=5%>{$row['userid']}</td>";
            $echoinfolist .= "<td align=center width=25%><a href='profile.php?job=show&memberid=" . $row['userid'] . "'>{$row['username']}</a></td>";
            $echoinfolist .= "<td align=center width=20%>$regdate</td>";
            $echoinfolist .= "<td align=center>{$row['postamount']}</td>";
            $echoinfolist .= "<td align=center>{$scores}</td>";
            $echoinfolist .= "<td align=center><a href=admin.php?bmod=setuser.php&action=edit&with=id&member=" . $row['userid'] . ">$arr_ad_lng[754]</a> <a href=admin.php?bmod=setuser.php&with=id&action=kill&member=" . $row['userid'] . ">$arr_ad_lng[755]</a> <a href=admin.php?bmod=setuser.php&with=id&action=view&member=" . $row['userid'] . ">$arr_ad_lng[756]</a></td></tr>";
        } 
    } 
    $pagenextnow = $pageshow-1;
    $pageprenow = $pageshow + 1;
    $count = count($usergroupdata);
    foreach ($usergroupdata as $key=>$value) {
        $deinfo = explode("|", $value);
        $addinfotwo .= "<option value='$key'>$deinfo[0]</option>";
        $addinfoselect .= "<option selected='selected' value='$key'>$deinfo[0]</option>";
    } 
    if (empty($gotpage)) $gotpage = 1;
    $xnextpage = $gotpage + 1;
    $previouspage = $gotpage-1;
    $maxpagenum = $gotpage + 4;
    $minpagenum = $gotpage-4;

    $mutilpage .= "<a href=\"admin.php?bmod=setuser.php&type=$type&list=$list&jumppage={$nextpage}&orderby={$orderby}\"><strong>«</strong></a> ";

    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
        if ($i > 0 && $i <= $n) {
            if ($i == $gotpage) {
                $mutilpage .= " [$i] ";
            } else {
                $mutilpage .= " <a href=\"admin.php?bmod=setuser.php&type=$type&list=$list&jumppage=$i{$nextpage}&orderby={$orderby}\"><strong>$i</strong></a> ";
            } 
        } 
    } 
    $mutilpage .= " <a href=\"admin.php?bmod=setuser.php&type=$type&list=$list&jumppage=$n{$nextpage}&orderby={$orderby}\"><strong>»</strong></a>";

    print <<<EOT
	
    <tr>
    <td bgcolor=#F9FAFE align=center colspan=2>
    <strong>$arr_ad_lng[757]</strong>
    </td>
    </tr>     
  <tr bgcolor=FFC96B>
   <td colspan=2 style="border: #C47508 1px soild;"><a name="top"></a><a href="#section1">$arr_ad_lng[758]</a> | <a href="#section2">$arr_ad_lng[760]</a> | <a href="#section6">$arr_ad_lng[1012]</a> | <a href="#section3">$arr_ad_lng[767]</a> | <a href="#section4">$arr_ad_lng[1179]</a> | <a href="#combine">$arr_ad_lng[1181]</a> | <a href="#ssection5">$arr_ad_lng[762]</a></td>
  </tr>
$table_start
    <div><div style='color:#FFFFFF;display:inline;float:left;'><strong><a name="section1"></a><a href="admin.php?bmod=$thisprog&action=updatecount" style="color:#FFFFFF;">$arr_ad_lng[758]</a></strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

	<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
	$table_stop
    $arr_ad_lng[759]
<input type=hidden name="action" value="updatecount">
<input type=submit value="$arr_ad_lng[66]">
    </form>
    	$table_start
    	
    <div><div style='color:#FFFFFF;display:inline;float:left;'><a name="section2"></a><strong>$arr_ad_lng[760]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

    
	<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
	$table_stop
    $arr_ad_lng[761]<br /><br />
        <input type=text name="member" size=10 >
        <input type=submit value="$arr_ad_lng[66]"> 
        ---[
        <input type=radio checked='checked' name="action" value="view">$arr_ad_lng[756] <input type=radio name="action" value="edit">$arr_ad_lng[754] <input type=radio name="action" value="kill">$arr_ad_lng[755] ]
		[ <input type="radio" checked='checked' name="with" value="" />$arr_ad_lng[750] <input type="radio" name="with" value="id" />$gl[512] ]
	
    </form>$table_start
    <div><div style='color:#FFFFFF;display:inline;float:left;'><a name="section6"></a><strong>$arr_ad_lng[767]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
   	
	<form action="admin.php?bmod=setuser.php&type=$type" name="SearchForm" method="post" style="margin:0px;">
    $table_stop
	<strong>$arr_ad_lng[1012]</strong><br /><input type="hidden" name="search" value="1" /><input type="hidden" name="delsure" value="0" />
	<table width=100%>
    <tr><td width=40%>$arr_ad_lng[1013]</td><td width=60%><input type="text" name="seausername" value="$seausername"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1014]</td><td width=60%><input type="text" name="seamailadd" value="$seamailadd"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1015]</td><td width=60%><input type="text" name="searegdateago" value="$searegdateago"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1016]</td><td width=60%><input type="text" name="searegdatelate" value="$searegdatelate"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1017]</td><td width=60%><input type="text" name="seapointsmall" value="$seapointsmall"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1018]</td><td width=60%><input type="text" name="seapointbig" value="$seapointbig"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1019]</td><td width=60%><input type="text" name="seapostamountsmall" value="$seapostamountsmall"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1020]</td><td width=60%><input type="text" name="seapostamountbig" value="$seapostamountbig"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1021]</td><td width=60%><input type="text" name="sealastlogin" value="$sealastlogin"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1022]</td><td width=60%><input type="text" name="seatips" value="$seatips"></td></tr>
    <tr><td width=40%>$arr_ad_lng[1025]</td><td width=60%><input type="text" name="searegips" value="$searegips"></td></tr>
    <tr><td width=40%>$arr_ad_lng[766]</td><td width=60%><select name='seausertype[]' multiple="multiple" size="6" style="width: 224px;">$addinfoselect</select> </td></tr>
    <tr><td width="100%" colspan="2" algin="center"><input type=submit value="$arr_ad_lng[1012]"> <input type=button onclick="javascript:makesure();" value="$arr_ad_lng[1023]"> <input type="submit" name="pm" value="$arr_ad_lng[1123]"> <input type="submit" name="pm" value="$arr_ad_lng[1124]"> <input type="submit" name="pm" value="$arr_ad_lng[1125]"></td></tr>
    </table>

    </form>

	<script language="JavaScript">
function makesure(){
if(confirm("$arr_ad_lng[1024]", "$arr_ad_lng[1024]")){
document.SearchForm.delsure.value=1;
SearchForm.submit();
}
}
function clearam(){
if(confirm("$arr_ad_lng[1024]", "$arr_ad_lng[1024]")){
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
<a name="section3"></a>
$table_start 
	$echoinfolist
   	<tr><td colspan=8><input type='button' name=chkall value=$arr_ad_lng[405] onclick='CheckAll(this.form)'><input type='button' name=clear2 value=$arr_ad_lng[406] onclick='FanAll(this.form)'><input type='reset' value='$arr_ad_lng[407]'><input type=button onclick='clearam();' value=$arr_ad_lng[1023]></td></tr></form><form name=jump action="admin.php?bmod=setuser.php&type=$type&orderby={$orderby}&list=$list{$nextpage}" method=post style="margin:0px;">
  </td></tr><tr><td colspan=5>
$mutilpage $arr_ad_lng[770] <strong>$pageshow</strong>$arr_ad_lng[771] $arr_ad_lng[772]<strong> $n </strong> $arr_ad_lng[771] $arr_ad_lng[773]<input type='text' name='jumppage' size=4 style='background-color:#FFFFFF; color:#8888AA; border: 1 double #B4B4B4' >$arr_ad_lng[771]
<input type='submit' value='$arr_ad_lng[774]' name='submit'>    <strong>[ $userperpage /$arr_ad_lng[771] ]</strong>
</form></td><td colspan=3>
<form action=admin.php?bmod=setuser.php method=post style="margin:0px;">

	<center>$arr_ad_lng[776]<select name='type'><option value=''>$arr_ad_lng[775]</option>$addinfotwo</select> 
<input type='submit' value='$arr_ad_lng[774]' name='submit'></center>

	</form>

$table_start
    <div><div style='color:#FFFFFF;display:inline;float:left;'><a name="section4"></a><strong>$arr_ad_lng[1179]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
	
	
	<form action="admin.php?bmod=$thisprog"method="post" style="margin:0px;"><input type="hidden" name="action" value="changeug" />
	$table_stop
    $arr_ad_lng[1180]<br /><br />
        <textarea cols="50" name="userlists" rows="4"></textarea>
        <br/>$arr_ad_lng[766]<select name="memberusertype">{$seusert}</select>
        <input type=submit value="$arr_ad_lng[66]"> 

	    </form>
$table_start
    <div><div style='color:#FFFFFF;display:inline;float:left;'><a name="combine"></a><strong>$arr_ad_lng[1181]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
	
	
	<form action="admin.php?bmod=$thisprog"method="post" style="margin:0px;"><input type="hidden" name="action" value="combine" />
	$table_stop
    $arr_ad_lng[1182]<br /><br />
        <textarea cols="50" name="userlists" rows="4"></textarea><br />
        <input type=submit value="$arr_ad_lng[66]"> 

	    </form>
$table_start
    <div><div style='color:#FFFFFF;display:inline;float:left;'><a name="ssection5"></a><strong>$arr_ad_lng[762]</strong></div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>

	<form action="admin.php?bmod=$thisprog"method="post" style="margin:0px;"><input type=hidden name="action" value="register">
	$table_stop
    $arr_ad_lng[763]<br /><br />
        $arr_ad_lng[764]<input type=text name="member" size=10 > $arr_ad_lng[765]<input type=password name="memberpass" size=10> Email:<input type=text name="membermail" size=10 > $arr_ad_lng[766]<select name="memberusertype">{$seusert}</select>
        <input type=submit value="$arr_ad_lng[66]"> 

	    </form>

    </td>
    </tr></td></tr></table></body></html>
EOT;
    exit;
} 

