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

require("datafile/config.php");
require("getskin.php");
require("lang/$language/admin.php");

$bbslogfile = "$idpath/bbslog.txt";
$bbstffile = "datafile/bbslog2.txt";
// theme
$table_start = "</tr></td></table></tr></td></table>
<br/>
<table align=\"center\" cellpadding='0' cellspacing='0' class='bmf_table_border'>
<tr><td>
<table align=\"center\" cellpadding='6' cellspacing='1' class=\"bmf_n_table_class\">
<tr><td bgcolor=\"#6DA6D1\" colspan='10' style='color:#FFFFFF;font-weight:bold;'>
";
$table_stop = "</td></tr><tr><td>";
////
if (file_exists("install.php")) die($arr_ad_lng[27]);
if (!empty($_POST['username'])) {
	$authinput = strtoupper($authinput);
	
	if ($_SESSION["checkauthnum"] != $authinput && $log_va) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
        $_POST[password] = "";
	}
	
    if ($_SESSION["logintry"] > $maxlogintry-1 && isset($maxlogintry)) {
        echo $arr_ad_lng[28];
    } else {
        $_SESSION["bmbadminid"] = $_POST['username'];
        $_SESSION["bmbadminpwd"] = md5(stripslashes($_POST['password']));
        echo "$arr_ad_lng[29]";
        echo "<form action=admin.php name=thispage id=thispage></form>
		<script>
		function submitit(){
		document.thispage.submit();
		}
		timer=setInterval('submitit();document.thispage.submit();',".($refresh_allowed*1000)."); 
		
		</script>";
    } 
    exit;
} 
if ($action == "exit") {
    $_SESSION["bmbadminid"] = "";
    $_SESSION["bmbadminpwd"] = "";
    echo "$arr_ad_lng[30]";
    echo "<form action=admin.php name=thispage id=thispage></form>
	<script>
	function submitit(){
	document.thispage.submit();
	}
	timer=setInterval('submitit();document.thispage.submit();',".($refresh_allowed*1000)."); 
	</script>";
    exit;
} 

$current_time = date("F j, Y, g:i:s a");
$yeartoday = get_year($timestamp);
// -------------check permission ---------
if ((!empty($_SESSION['bmbadminid']) && !empty($_SESSION['bmbadminpwd']) && checkpass($_SESSION['bmbadminid'], $_SESSION['bmbadminpwd']))) {
	$bmbadminid = $_SESSION['bmbadminid'];
	$bmbadminpwd = $_SESSION['bmbadminpwd'];

    $getui = get_user_info($bmbadminid);
    $pusera = explode("|", $usergroupdata[$getui['ugnum']]);
    get_usergroup_admin();
    $useraccess = $pusera[22];
    $smodaccess = $pusera[21];
    if ($useraccess != "1" && $smodaccess != "1") {
        if ($bmbadminpwd != "") {
        	$_SESSION["logintry"]++;
            $query = "INSERT into {$database_up}apclog (adminid,adminpwd,actionstatus,adminip,admintime,addtime) values ('$bmbadminid','$bmbadminpwd','Failed','$ip','$current_time','$timestamp')";
            $result = bmbdb_query($query);
        } 
        include("include/admin/adminheader.php");
        admintitle();
        adminlogin();
    } 
} else {
    if ($_SESSION['bmbadminpwd'] != "") {
		$bmbadminid = $_SESSION['bmbadminid'];
		$bmbadminpwd = $_SESSION['bmbadminpwd'];
        $_SESSION["logintry"]++;
        $query = "INSERT into {$database_up}apclog (adminid,adminpwd,actionstatus,adminip,admintime,addtime) values ('$bmbadminid','$bmbadminpwd','Failed','$ip','$current_time','$timestamp')";
        $result = bmbdb_query($query);
    } 
    include("include/admin/adminheader.php");
    admintitle();
    adminlogin();
} 
// 管理区安全日 
// 管理中心操作详细记录
$query = "INSERT into {$database_up}apclog (adminid,adminpwd,actionstatus,adminip,admintime,addtime) values ('$bmbadminid','$arr_ad_lng[32]','$thisprog','$ip','$current_time','$timestamp')";
$result = bmbdb_query($query);


// 过去 24 小时来过管理中心的，不可删除 [86400秒]
if (file_exists($bbstffile)) {
    $online_24_user = trim(readfromfile($bbstffile));
    $online_24_user = explode("\n", $online_24_user);
    $new_online_user = '';
    $online_24user_count = count($online_24_user);
    for($i = 0; $i < $online_24user_count; $i++) {
        $online_24user_detail = explode("|", $online_24_user[$i]);
        if ($timestamp - $online_24user_detail[2] <= "86400" && $online_24user_detail[0] != $bmbadminid && $online_24user_detail[1] != $ip) {
            $last_24_login .= $online_24_user[$i] . "\n";
            $count_24login++;
        } 
    } 
} 
$last_24_login .= "$bmbadminid|$ip|$timestamp|\n"; 
// 记录 IP、时间、用户名
writetofile($bbstffile, $last_24_login);
// 管理区安全日志结
// -------------------------------------------
$tab_top = "<table width=92% align=center cellspacing=0 cellpadding=0 bgcolor=#448ABD>
	    <tr><td>
	    <table width=100% cellspacing=1 cellpadding=3>
	    <tr><td bgcolor=#FFFFFF>
	<font face=verdana>";
$tab_bottom = "</font>
	</td></tr></table>
	</td></tr></table>";


if ($thisisout != "yes") { include("include/admin/adminheader.php"); admintitle(); }
// +-----------------------------------------------------------------------
// -----------------functions-------------------------
function adminlogin()
{
    global $thisprog, $table_start, $table_stop, $gl, $log_va, $gd_auth, $yeartoday, $arr_ad_lng, $adminnamea, $pwd, $usericon, $email, $oicq, $regdate, $sign, $homepage, $area, $comment, $honor, $lastpost, $postamount, $pe, $none, $bym, $passask, $passanswer, $usertype, $money, $born, $group, $sex, $national;
    
    if ($log_va) {
	    $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
	    $_SESSION[checkauthnum] = $authnum;
	    if ($gd_auth == 1) $tmp23s = "<img src='authimg.php?p=1' onclick='javascript:randtime=Date.parse(new Date());this.src=\"authimg.php?p=1&amp;reget=1&amp;timerand=\"+randtime;' title='$gl[529]' style='cursor: pointer;' />";
	        else $tmp23s = "<img src=authimg.php?p=1><img src=authimg.php?p=2><img src=authimg.php?p=3><img src=authimg.php?p=4><img src=authimg.php?p=5>";
    }
   
    print <<<EOT
    <tr><td bgcolor=#448ABD colspan=2><font color=#D6DFF7>
    <strong>$arr_ad_lng[5]</strong>
$table_start<strong>$arr_ad_lng[34]</strong></font></td></tr>
    <form action="admin.php?bmod=$thisprog" method="post">
    <tr>
    <font face=verdana>
    <td valign=middle width=40% align=right><font color=#555555>$arr_ad_lng[35]</font></td>
    <td valign=middle><input type="text" name="username" id="usernameinput" /></td></tr>
    <tr bgcolor=F9FAFE>
    <td valign=middle width=40% align=right><font color=#555555>$arr_ad_lng[36]<br /></font></td>
    <td valign=middle><input type=password name="password"></td></tr>
    </font>
    </tr>
	<script type="text/javascript">
	document.getElementById('usernameinput').focus();
	</script>
EOT;

if ($log_va) {
print<<<EOT
    <tr bgcolor=F9FAFE>
    <td valign=middle width=40% align=right><font color=#555555>$arr_ad_lng[1031]<br /></font></td>
    <td valign=middle><input type=text name="authinput" size=5> $tmp23s</td></tr>
    </font>
    </tr>
EOT;
}

print<<<EOT

</td></tr>
$table_start
	<div align="center"><input type=submit name="submit" value="$arr_ad_lng[37]"></div></td></tr></form>
$table_start
$table_stop
    $arr_ad_lng[38]</font>
$table_start
    <strong>Copyright &copy; Bluview Technology. Powered by BMForum, $yeartoday
</strong>
     </table></td></tr></table>
    </td></tr></table></body></html>
EOT;
    exit;
} 
function get_year($datetime)
{
    global $time_1;
    $datetime = $datetime + ($time_1 * 60 * 60);
    return gmdate("Y", $datetime);
} 
function admintitle()
{
    global $arr_ad_lng, $admin_log_hash;
    print <<<EOT
    <title>$arr_ad_lng[39]</title>

    </head>
    <body bgcolor=#F9FCFE>
<script type="text/javascript">
function Moveup(dbox) {
for(var i = 0; i < dbox.options.length; i++) {
if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[0]) {
var tmpval = dbox.options[i].value;
var tmpval2 = dbox.options[i].text;
dbox.options[i].value = dbox.options[i - 1].value;
dbox.options[i].text = dbox.options[i - 1].text
dbox.options[i-1].value = tmpval;
dbox.options[i-1].text = tmpval2;
dbox.options[i-1].selected='selected'; 
dbox.options[i].selected=''; 
      }
   }
}
function Movedown(dbox) {
var istart = dbox.options.length - 1;
for(var i = istart; i >= 0 ; i--) {
if (dbox.options[i].selected && dbox.options[i] != "" && dbox.options[i] != dbox.options[istart]) {
var tmpval = dbox.options[i].value;
var tmpval2 = dbox.options[i].text;
dbox.options[i].value = dbox.options[i + 1].value;
dbox.options[i].text = dbox.options[i + 1].text
dbox.options[i+1].value = tmpval;
dbox.options[i+1].text = tmpval2;
dbox.options[i+1].selected='selected'; 
dbox.options[i].selected=''; 
      }
   }
}
function GetOptions(ebox, urlnew) {
	var optionsout='';
	for(var i = 0; i < ebox.options.length; i++) {
		optionsout+="forumorder[]="+bmb_ajax_encode(ebox.options[i].value)+'&';
	}
	document.body.style.cursor = 'wait';
	makeRequest(urlnew,optionsout,bmb_ajax_GetOptions_back,"POST");
}
function bmb_ajax_GetOptions_back() {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			document.body.style.cursor = '';
			alert("$arr_ad_lng[75]");
		} else {
			document.body.style.cursor = '';
			alert("There was a problem with the request. Check your permission or contact with administrator.");
		}
	}
}
function cprocess(url) {
if(confirm("$arr_ad_lng[391]", "$arr_ad_lng[391]")){
window.location=url+"&verify=$admin_log_hash";
}
}
</script>
<br />
    </td><td width=70% valign=top bgcolor=#D6DFF7>
    <table align="center" cellpadding='6' style="margin-top:15px;" cellspacing='0' class="bmf_table_class">
EOT;
} 

function row_ads_maker($announcement, $every_ads){
	global $showindex, $showforum, $showtopic;
		
    $show_ads = '<table class="tableborder row_ads_border" cellspacing="1" cellpadding="5" align="center" border="0">';
    $ads_arr = explode("\n", stripslashes($announcement));
    $count = ceil(count($ads_arr) / $every_ads) * $every_ads;
    
    
    $every_width = floor(100 / $every_ads);
    $echotr = 1;
    for ($i = 0; $i < $count; $i ++) {
    	
    	if ($echotr == 1) {
    	    $show_ads .= "<tr>\n";
    	    $echotr = 0;
    	}
    	if (!$ads_arr[$i]) $ads_arr[$i] = "&nbsp;";
    	
    	$show_ads .= "<td class='row_ads' style='width:{$every_width}%;'>$ads_arr[$i]</td>\n";
    	
    	if (((floor(($i + 1) / $every_ads) * $every_ads) == ($i + 1) && $i != 0) || $every_ads == 1) {
    	    $show_ads .= "</tr>\n";
    	    $echotr = 1;
    	}
    }
    $show_ads .= "</table>";
    
    $row_new_text = '<?php
    $ads_row=\'' . str_replace("'", "\'", stripslashes($announcement)) . '\';
    $row_show[showindex]=\'' . str_replace("'", "\'", stripslashes($showindex)) . '\';
    $row_show[showforum]=\'' . str_replace("'", "\'", stripslashes($showforum)) . '\';
    $row_show[showtopic]=\'' . str_replace("'", "\'", stripslashes($showtopic)) . '\';
    $ads_every=\'' . str_replace("'", "\'", stripslashes($every_ads)) . '\';
$ads_showit=<<<EOT
<br />
' . $show_ads . '

EOT;

    ';
    
    return $row_new_text;

}
function refresh_jscache(){
global $database_up;
    // Refresh Javascript Cache
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        $xfourmrow[] = $fourmrow;
    } 

    $count = count($xfourmrow);
    for ($i = 0; $i <= $count; $i++) {
    	$tmpbbsname = str_replace('"', '\"', $xfourmrow[$i]['bbsname']);
        if ($xfourmrow[$i]['type'] == "category") $listsinfo .= "linkset[0]+=\"<div class='menuitems'><a href='index.php?cateid={$xfourmrow[$i]['id']}'>{$tmpbbsname}</a></div>\"\n";
        if ($xfourmrow[$i]['type'] == "forum" || $xfourmrow[$i]['type'] == "former" || $xfourmrow[$i]['type'] == "jump" || $xfourmrow[$i]['type'] == "selection" || $xfourmrow[$i]['type'] == "locked" || $xfourmrow[$i]['type'] == "closed") $listsinfo .= "linkset[0]+=\"<div class='menuitems'><a href='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|- {$tmpbbsname}</a></div>\"\n";
        if ($xfourmrow[$i]['type'] == "subforum" || $xfourmrow[$i]['type'] == "subformer" || $xfourmrow[$i]['type'] == "subjump" || $xfourmrow[$i]['type'] == "subselection" || $xfourmrow[$i]['type'] == "sublocked" || $xfourmrow[$i]['type'] == "subclosed") $listsinfo .= "linkset[0]+=\"<div class='menuitems'><a href='forums.php?forumid={$xfourmrow[$i]['id']}'>&nbsp;|-|- {$tmpbbsname}</a></div>\"\n";
    } 
    writetofile ("datafile/navigator.js", "var linkset=new Array() \n linkset[0]=''\n" . $listsinfo); 

}
function refreshare_cache()
{
    global $database_up;
    // Javascript cached forum links
    $nquery = "SELECT * FROM {$database_up}shareforum ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        $fourmrow[url] = str_replace('"', '\"', $fourmrow[url]);
        $fourmrow[name] = str_replace('"', '\"', $fourmrow[name]);
        $fourmrow[des] = str_replace('"', '\"', $fourmrow[des]);
        $fourmrow[gif] = str_replace('"', '\"', $fourmrow[gif]);
        if ($fourmrow[type] == "pic") {
            $sharepic .= "<a href='$fourmrow[url]' target='_blank'><img width='88' height='31' alt='$fourmrow[name] - $fourmrow[des]' src='$fourmrow[gif]' border='0' /></a>&nbsp;            ";
        } else {
            $sharetext .= "<a title='$fourmrow[name] - $fourmrow[des]' href='$fourmrow[url]' target='_blank'>$fourmrow[name]</a>&nbsp;             ";
        } 
    } 
    
    $sharepic = "document.getElementById('simgnow').innerHTML=\"$sharepic\";\n";
    $sharetext = "document.getElementById('stextnow').innerHTML=\"$sharetext\";\n";

    writetofile("datafile/cache/sharetext.js", $sharetext);
    writetofile("datafile/cache/sharepic.js", $sharepic);
} 
function bmf_dircpy($source, $dest, $overwrite = false)
{
	if($handle = opendir($source)){        // if the folder exploration is sucsessful, continue
		while(false !== ($file = readdir($handle))){ // as long as storing the next file to $file is successful, continue
			if($file != '.' && $file != '..'){
				$path = $source . '/' . $file;
				if(is_file($path)){
					if(!is_file($dest . '/' . $file) || $overwrite) {
						if(!@copy($path, $dest . '/' . $file)){
							echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>';
						}
					}
				} elseif(is_dir($path)){
					if(!is_dir($dest . '/' . $file)) mkdir($dest . '/' . $file, 0777); // make subdirectory before subdirectory is copied
					bmf_dircpy($path, $dest . '/' . $file, $overwrite); //recurse!
				}
			}
		}
		
		closedir($handle);
	}
} 
function get_usergroup_admin()
{
	global $database_up, $line, $getui, $admgroupdata;
	$query = "SELECT * FROM {$database_up}usergroup";
	$result = bmbdb_query($query);
	while (false !== ($line = bmbdb_fetch_array($result))) {
	    if ($line['id'] == $getui['ugnum'])
	        $admgroupdata = explode("|", $line['adminsets']);
	} 
}