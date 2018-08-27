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

$thisprog = "usertitle.php";

if ($useraccess != "1" || $admgroupdata[14] != "1") {
    adminlogin();
} 

if ($action != "process") {
    include_once("datafile/usertitle.php"); // Load User Title
    if ($level_score_mode == 1) $modechecked_a = "checked='checked'";
    elseif ($level_score_mode == 2) $modechecked_b = "checked='checked'";
    else $modechecked_z = "checked='checked'";
    
    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[202]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <strong>$arr_ad_lng[202]</strong>
  <form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
  </td></tr>


  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left colspan=2>
  $arr_ad_lng[675]
$table_start<strong>$arr_ad_lng[1001]</strong>
  </font></td>
  </tr>


  <tr>
  <td bgcolor=#F9FAFE colspan=2 valign=middle align=left>
  <input type=radio name=mode $modechecked_z value='0'>$arr_ad_lng[1002]<br />
  <input type=radio name=mode $modechecked_a value='1'>$arr_ad_lng[1003]<br />
  <fieldset><legend><input type=radio $modechecked_b name=mode value='2'>$arr_ad_lng[1004]
  </legend>
  $arr_ad_lng[1005] <input type=text size=65 name=expression value='$level_score_exp'>
  </fieldset>
  </td>
  </tr>
EOT;

    for($i = 0;$i < $countmtitle;$i++) {
        $thisnum = $i + 1;
        $level_name = urlencode($mtitle['a'.$i]);
        print <<<EOT
$table_start
<div><div style='color:#FFFFFF;display:inline;float:left;'><strong>$arr_ad_lng[677] $thisnum {$arr_ad_lng[679]}</strong></div>
  <div style='display:inline;float:right;'><a href="admin.php?bmod=editusergroup.php&levelname={$level_name}&level=1&id=$i" style='color:#FFFFFF;'>$arr_ad_lng[1176]</a></div></div>
  </td>
  </tr>


  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[677]{$arr_ad_lng[682]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="mpostmark[a{$i}]" value="{$mpostmark['a'.$i]}"></td>
  </tr>

  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[677]{$arr_ad_lng[680]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="mtitle[a{$i}]" value="{$mtitle['a'.$i]}"></td>
  </tr>
  
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[677]{$arr_ad_lng[681]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="mgraphic[a{$i}]" value="{$mgraphic['a'.$i]}"></td>
  </tr>
EOT;
    } 
    $thisnum = $i + 1;
    print <<<EOT
$table_start
<span id='addnewtitle'>
  <strong><a href="javascript:newtitles();" style='color:#FFFFFF;'>$arr_ad_lng[678]</a></strong>
</span>
</td></tr> 
<script>
function newtitles(){
document.getElementById("addnewtitle").innerHTML  = "<div style='width:100%;padding:5px;background-color:#F9FAFE;'><strong>$arr_ad_lng[677] $thisnum {$arr_ad_lng[679]}</strong>  <br /><strong>$arr_ad_lng[677]{$arr_ad_lng[682]}</strong> <input type=text size=40 name='mpostmark[a{$i}]' value='{$mpostmark['a'.$i]}'><br /><strong>$arr_ad_lng[677]{$arr_ad_lng[680]}</strong> <input type=text size=40 name='mtitle[a{$i}]' value='{$mtitle['a'.$i]}'><br /><strong>$arr_ad_lng[677]{$arr_ad_lng[681]}</strong> <input type=text size=40 name='mgraphic[a{$i}]' value='{$mgraphic['a'.$i]}'></div>";
}
</script>
EOT;
    print <<<EOT
$table_start
  <strong>$arr_ad_lng[692]</strong>
  </td>
  </tr>
  
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[693]{$arr_ad_lng[680]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="motitle" value="$motitle"><br /></td>
  </tr>
  
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[693]{$arr_ad_lng[681]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="modgraphic" value="$modgraphic"><br /><br /></td>
  </tr>
    <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[694]{$arr_ad_lng[680]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="supmotitle" value="$supmotitle"><br /></td>
  </tr>
  
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[694]{$arr_ad_lng[681]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="supmodgraphic" value="$supmodgraphic"><br /><br /></td>
  </tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[695]{$arr_ad_lng[680]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="admintitle" value="$admintitle"><br /></td>
  </tr>
  
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=left width=40%>
  <strong>$arr_ad_lng[695]{$arr_ad_lng[681]}</strong></td>
  <td bgcolor=#F9FAFE valign=middle align=left>
  <input type=text size=40 name="admingraphic" value="$admingraphic">
  
$table_start<input type=submit value="$arr_ad_lng[66]"> <input type=reset value="$arr_ad_lng[178]">
  </tr>
</form>
  </td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "process") {
    $countmtitle = count($_POST['mtitle']);
    $filecontent = "<?php";
    $thisi = 0; 
    // Foreach User Titles
    for($i = 0;$i < $countmtitle;$i++) {
        if ($_POST['mtitle']["a{$i}"]) {
            $filecontent .= "
\$mpostmark['a$thisi']	=	'" . $_POST['mpostmark']["a{$i}"] . "';
\$mtitle['a$thisi']	=	'" . $_POST['mtitle']["a{$i}"] . "';
\$mgraphic['a$thisi']	=	'" . $_POST['mgraphic']["a{$i}"] . "';
";
            $thisi++;
            $lastname = $_POST['mtitle']["a{$i}"];
        } else {
            unset($_POST['mtitle']["a{$i}"]);
        } 
    } 

    $old_ct = $countmtitle = count($_POST['mtitle']);
    
    $expred = str_replace(";", '', $_POST['expression']);
    $expred = str_replace("{post}", '$amount', $expred);
    $expred = str_replace("{score}", '$score', $expred);

    $filecontent .= "\$motitle	=	'{$_POST['motitle']}';
\$modgraphic	=	'{$_POST['modgraphic']}';
\$supmotitle	=	'{$_POST['supmotitle']}';
\$supmodgraphic	=	'{$_POST['supmodgraphic']}';
\$admintitle	=	'{$_POST['admintitle']}';
\$admingraphic	=	'{$_POST['admingraphic']}';
\$countmtitle	=	'{$countmtitle}';
\$level_score_mode	=	'{$_POST['mode']}';
\$level_score_exp	=	'{$_POST['expression']}';
\$level_score_php	=	'\$amount = $expred ;';
";

	include_once("datafile/usertitle.php");

	if ($old_ct > $countmtitle) {
		$get_array = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}levels WHERE `fid`=0 ORDER BY `id` DESC LIMIT 1"));
		$this_id = $old_ct-1;
		$tmp_maccess = explode("|", $get_array['maccess']);
		$tmp_maccess[0] = $lastname;
		$get_array['maccess'] = implode("|", $tmp_maccess);
		bmbdb_query("INSERT INTO {$database_up}levels (`id`,`fid`,`maccess`) VALUES ('$this_id','0','{$get_array['maccess']}')");
		
		$refresh = 1;

	} elseif ($old_ct < $countmtitle) {
		bmbdb_query("DELETE FROM {$database_up}levels WHERE `fid`=0 ORDER BY `id` DESC LIMIT ".($countmtitle-$old_ct));
		
		$refresh = 1;
	}

    writetofile("datafile/usertitle.php", $filecontent);
    
    if ($refresh == 1) {
	    $query = "SELECT * FROM {$database_up}levels WHERE `fid`='0' ORDER BY `id` ASC";
	    $result = bmbdb_query($query);
	    $ugsocount = "";
	    $wrting = "<?php ";
	    while (false !== ($line = bmbdb_fetch_array($result))) {
	        $line['maccess'] = str_replace('"', '\"', $line['maccess']);
	        $wrting .= "
\$levelgroupdata[0][{$line['id']}]=\"{$line['maccess']}\";
";
	    } 

	    writetofile("datafile/cache/levels/level_fid_0.php", $wrting);
	}
    
    
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[202]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=$thisprog">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
} 
