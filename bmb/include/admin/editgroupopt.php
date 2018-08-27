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

$thisprog = "editgroupopt.php";
include("datafile/usertitle.php");

if ($useraccess != "1" || $admgroupdata[13] != "1") {
    adminlogin();
} 

for ($i = 0; $i < count($usergroupdata); $i++)
{
	$thistype[$i] = explode("|", $usergroupdata[$i]);
}

if ($targetid != "") { // Forum Level Group
    $result = bmbdb_query("SELECT id,maccess FROM {$database_up}levels WHERE fid='$targetid'");
} else {
	$result = bmbdb_query("SELECT id,maccess FROM {$database_up}levels WHERE fid='0'");
}

while (false !== ($line = bmbdb_fetch_array($result))) {
	$levelusergroupdata = $line['maccess'];
	$a_l_usergroupdata["{$line['id']}"] = $line['maccess'];
	$thisleveltype["{$line['id']}"] = explode("|", $levelusergroupdata);
}

if ($targetid != "") { // Forum UserGroup
    $query = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid'";
    $result = bmbdb_query($query);
    $line = bmbdb_fetch_array($result);
    $usergroupdata = explode("\n", $line['usergroup']);
	for ($i = 0; $i < count($usergroupdata); $i++)
	{
		$thistype[$i] = explode("|", $usergroupdata[$i]);
	}
} 

if ($step != 2)
{
	if ($selectedopt && $selectedugr) 
	{
		
		foreach($selectedopt as $value)
		{
			$sqlselect .= $sqlselect ? ",$value" : $value;
		}
		$oquery = "SELECT * FROM {$database_up}ugoptlist where id in ($sqlselect)";
		$oresult = bmbdb_query($oquery);
  		$curl_check = function_exists("curl_setopt") ? $arr_ad_lng[94] : $arr_ad_lng[95];

echo<<<EOT
		<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[339]</strong>
		</tr>
  <tr>
  <td bgcolor=#FFFFFF valign=middle align=left colspan=2 style="border-bottom: #C47508 1px soild;">
  <font color=#333333><strong><a name="top">$arr_ad_lng[1246]</a><form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">
  </td></tr>

EOT;
  		foreach($selectedugr as $key=>$value)
  		{
  			$infos = explode ("|", $value);
  			if ($infos[0] == "l") {
  				$groupsedcache['level'][] = $infos[1];
  			} else {
  				$groupsedcache['group'][] = $infos[1];
  			}
  		}
		while (false !== ($oline = bmbdb_fetch_array($oresult))) {
			display_optionsug($oline);
			echo "<input type='hidden' name='makedopt[]' value='{$oline['id']}' />";
		}
    print <<<EOT
   	   $table_start <input type="hidden" name="targetid" value="$targetid" /><input type="hidden" name="step" value="2" />
   	    <input type="submit" value="$arr_ad_lng[66]" /> <input type="reset" value="$arr_ad_lng[178]" /></form>
  </tr></table></td></tr></table>
</td></tr></table></body></html>
EOT;

	} else {
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<strong>&nbsp;$arr_ad_lng[1248]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=usergroup.php&targetid=$targetid">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
	}
} else {
	$count = count($makedopt);
	
	if ($count >= 1) {
		foreach($makedopt as $value)
		{
			$sqlselect .= $sqlselect ? ",$value" : $value;
		}
		$oquery = "SELECT * FROM {$database_up}ugoptlist where id in ($sqlselect)";
		$oresult = bmbdb_query($oquery);
		while (false !== ($oline = bmbdb_fetch_array($oresult))) {
			$cacheinfo["{$oline['id']}"] = $oline;
		}
		$addsqls = "";
		for($i = 0; $i < $count; $i++)
		{
			foreach($setting["{$makedopt[$i]}"] as $key=>$value)
			{
				$parse = explode("|", $key);
				if ($parse[0] == "u")
				{
		            $de = $newlist["$parse[1]"] ? explode("|", $newlist["$parse[1]"]) : explode("|", $usergroupdata[$parse[1]]);
		            $cous = count($de);
		            for($ax = 0;$ax < $cous;$ax++) {
		                $de[$ax] = str_replace("\n", "", $de[$ax]);
		            } 
		            $ctid = $cacheinfo["{$makedopt[$i]}"]['optid'];
		            if ($cacheinfo["{$makedopt[$i]}"]['specmod'] == 1) 
		            {
		            	if ($value == 1) $addsqls["$parse[1]"] = "unshowit='0',";
		            		else $addsqls["$parse[1]"] = "unshowit='1',";
		            } else $value = specialmod($cacheinfo["{$makedopt[$i]}"]['specmod'], $value);
		            $de["$ctid"] = $value;
		            if ($cacheinfo["{$makedopt[$i]}"]['optidt']) {
		            	$cttd = $cacheinfo["{$makedopt[$i]}"]['optidt'];
		            	$de["$cttd"] = $twosetting["{$makedopt[$i]}"]["$key"]; 
		            }
		            $newlist["$parse[1]"] = implode("|", $de) . "\n";
		            $newlistexp[] = $parse[1];
				} else {
					if ($targetid != "") { // Forum Level Group
					    $result = bmbdb_query("SELECT id,maccess FROM {$database_up}levels WHERE fid='$targetid'");
					} else {
						$result = bmbdb_query("SELECT id,maccess FROM {$database_up}levels WHERE fid='0'");
					}
					while (false !== ($line = bmbdb_fetch_array($result))) {
						$levelusergroupdata = $l_newlist["$parse[1]"] ? $l_newlist["$parse[1]"] : $line['maccess'];
						$de = $l_newlist["$parse[1]"] ? explode("|", $l_newlist["$parse[1]"]) : $thisleveltype[$parse[1]];
			            $ctid = $cacheinfo["{$makedopt[$i]}"]['optid'];
			            if ($cacheinfo["{$makedopt[$i]}"]['specmod'] != 1 && !$runed["{$makedopt[$i]}"][$key]) {
			            	$runed["{$makedopt[$i]}"][$key] = 1;
			            	$value = specialmod($cacheinfo["{$makedopt[$i]}"]['specmod'], $value);
			            }
			            $de["$ctid"] = $value;
			            if ($cacheinfo["{$makedopt[$i]}"]['optidt']) {
			            	$cttd = $cacheinfo["{$makedopt[$i]}"]['optidt'];
			            	$de["$cttd"] = $twosetting["{$makedopt[$i]}"]["$key"]; 
			            }
			            $l_newlist["$parse[1]"] = implode("|", $de);
			            $l_newlistexp[] = $parse[1];
					}

				}
			}
			
	        if ($targetid)
	        {
				foreach($usergroupdata as $key=>$value)
				{
					$getit = $ugshoworder[$key];
					if (!@in_array($getit, $newlistexp)) $newlist["$getit"] = $usergroupdata[$getit];
				}
				ksort($newlist);
				$new = '';
				for ($si = 0; $si < count($newlist); $si++)
				{
					if (str_replace("\n", "", $newlist[$si])) $new .= str_replace("\n", "", $newlist[$si])."\n";
				}
		        $nquery = "UPDATE {$database_up}forumdata SET usergroup = '$new' WHERE id = '$targetid'";
		        $result = bmbdb_query($nquery);
				refresh_forumcach();
	        } else {
	        	if (count($newlistexp) >= 1)
	        	{
	        		foreach($newlistexp as $key=>$value)
	        		{
			            $nquery = "UPDATE {$database_up}usergroup SET {$addsqls[$value]}usersets = '".$newlist[$value]."' WHERE id = '$value'";
			            $result = bmbdb_query($nquery);
	        		}
	        	}
	        }
	        
        	if (count($l_newlistexp) >= 1)
        	{
        		foreach($l_newlistexp as $id)
        		{
					if ($targetid != "") { // Forum Level Group
					    $result = bmbdb_fetch_array(bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='$targetid'"));
					} else {
						$result = bmbdb_fetch_array(bmbdb_query("SELECT maccess FROM {$database_up}levels WHERE id='$id' and fid='0'"));
					}
					if ($result['maccess']) {
						bmbdb_query("UPDATE {$database_up}levels SET `maccess`='".$l_newlist[$id]."' WHERE `id`='$id' and `fid`='$targetid'");
					} else {
						bmbdb_query("INSERT INTO {$database_up}levels (`id`,`fid`,`maccess`) VALUES ('$id','$targetid','".$l_newlist[$id]."')");
					}
        		}
        	}
		}
		

	}
	if (!$targetid && count($newlistexp) >= 1) {
	    // Refresh Cache
	    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
	    $result = bmbdb_query($query);
	    $ugsocount = "";
	    $wrting = "<?php ";
	    while (false !== ($line = bmbdb_fetch_array($result))) {
	        $line['usersets'] = str_replace('"', '\"', $line['usersets']);
	        $wrting .= "
			\$usergroupdata[{$line['id']}]=\"{$line['usersets']}\";
			\$unshowit[{$line['id']}]=\"{$line['unshowit']}\";
			\$ugshoworder[]=\"{$line['id']}\";
			";
	        $ugsocount++;
	    } 
	    $wrting .= "\$ugsocount='$ugsocount';";
	    writetofile("datafile/cache/usergroup.php", $wrting);
    } 
	if (count($l_newlistexp) >= 1) {
		$targetids = $targetid ? $targetid : 0;
	    $query = "SELECT * FROM {$database_up}levels WHERE `fid`='$targetids' ORDER BY `id` ASC";
	    $result = bmbdb_query($query);
	    $ugsocount = "";
	    $wrting = "<?php ";
	    while (false !== ($line = bmbdb_fetch_array($result))) {
	        $line['maccess'] = str_replace('"', '\"', $line['maccess']);
	        $wrting .= "
\$levelgroupdata[{$targetids}][{$line['id']}]=\"{$line['maccess']}\";
";
	    } 

	    writetofile("datafile/cache/levels/level_fid_{$targetids}.php", $wrting);
    }
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FCFE>
		<strong>$arr_ad_lng[5]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href="admin.php?bmod=usergroup.php&targetid=$targetid">$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
}
function specialmod($specmod, $value)
{
	if ($specmod == 0) return $value;
	
	if ($specmod == 2) {
	    $bannedexist = array("php", "php4", "php3", "cgi", "pl", "asp", "aspx", "cfm", "shtml"); // Ban List
	    
	    $tmpexitscheck = explode(" ", $value);
	    $ccoun = count($tmpexitscheck);
	    for($c = 0;$c < $ccoun;$c++) {
	        if (in_array($tmpexitscheck[$c], $bannedexist)) unset($tmpexitscheck[$c]);
	    } 
	    $value = implode(" ", $tmpexitscheck); 
		return $value;
	} elseif ($specmod == 4) {
	    $value = $value * 10; 
		return $value;
	} else return $value;
}
function read_specialmod($specmod, $value)
{
	if ($specmod == 0) return $value;
	
	if ($specmod == 3) {
	    $value = $value * 1; 
		return $value;
	} elseif ($specmod == 4) {
	    $value = $value / 10; 
		return $value;
	} else return $value;
}
function display_optionsug($oline)
{
	global $arr_ad_lng, $targetid, $curl_check, $thistype, $thisleveltype, $groupsedcache, $countmtitle, $selectedugr, $usergroupdata, $ugshoworder, $ugsocount, $mtitle, $table_start;
	
	eval('$optname = "'.$oline['optname'].'";');
    
    

echo<<<EOT
	$table_start
	  <div><div style='color:#FFFFFF;display:inline;float:left;'>
	   <strong>$optname</strong>
	  </div>
  <div style='display:inline;float:right;'><a href="#top" style='color:#FFFFFF;'>$arr_ad_lng[975]</a></div></div>
  </td></tr>
EOT;

    for ($i = 0; $i < $ugsocount ; $i++) {
        // Print usergroup table
        if (!$targetid) $getit = $ugshoworder[$i]; else $getit = $i;
        $detail = explode("|", $usergroupdata[$getit]);
        if (!empty($detail[0]) && @in_array($getit, $groupsedcache['group'])) {
        	$thistype[$getit]["{$oline['optidt']}"] = read_specialmod($oline['specmod'], $thistype[$getit]["{$oline['optidt']}"]);
        	$thistype[$getit]["{$oline['optid']}"] = read_specialmod($oline['specmod'], $thistype[$getit]["{$oline['optid']}"]);
        	if ($getit == 6 && $oline['noguest'])  { noadapt($detail[0]); continue;}
        	if ($targetid && $oline['noforum'])  { noadapt($detail[0]); continue;}
        	if (!$targetid && $oline['mustforum'])  { noadapt($detail[0]); continue;}
		    $optionsdisplay = makeinput($oline['opttype'], $thistype[$getit]["{$oline['optid']}"], $oline['id'], "u|".$getit, $thistype[$getit]["{$oline['optidt']}"]);
echo<<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>{$detail[0]}</td>
   <td>$optionsdisplay</td>
  </tr>
EOT;
        } 
    } 
    for ($u = 0;$u < $countmtitle;$u++) {
		if (@in_array($u, $groupsedcache['level']) && !$oline['nolevel']) 
		{
			if ($targetid && $oline['noforum'])  { noadapt($mtitle["a$u"]); continue;}
        	if (!$targetid && $oline['mustforum']) { noadapt($mtitle["a$u"]); continue;}
        	$thisleveltype[$u]["{$oline['optid']}"] = read_specialmod($oline['specmod'], $thisleveltype[$u]["{$oline['optid']}"]);
        	$thisleveltype[$u]["{$oline['optidt']}"] = read_specialmod($oline['specmod'], $thisleveltype[$u]["{$oline['optidt']}"]);
			$optionsdisplay = makeinput($oline['opttype'], $thisleveltype[$u]["{$oline['optid']}"], $oline['id'], "l|".$u, $thisleveltype[$u]["{$oline['optidt']}"]);
echo<<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>{$mtitle["a$u"]}</td>
   <td>$optionsdisplay</td>
  </tr>
EOT;
		}
	}


}
function noadapt($groupname)
{
	global $arr_ad_lng;
echo<<<EOT
<tr bgcolor=#F9FCFE>
   <td width=50%>{$groupname}</td>
   <td>$arr_ad_lng[1249]</td>
  </tr>
EOT;
}
function makeinput($type, $defaultvalue, $item, $gid, $tdefaultvalue)
{
	global $arr_ad_lng;
	
	switch($type)
	{
		case 1:
			return "<input size='35' value='$defaultvalue' name='setting[$item][$gid]' />";
		case 2:
	        if ($defaultvalue) $switch_on = "checked='checked'";
	            else $switch_off = "checked='checked'";
			return "<input type='radio' $switch_on value='1' name='setting[$item][$gid]' /> $arr_ad_lng[94] <input $switch_off type='radio' value='0' name='setting[$item][$gid]' /> $arr_ad_lng[95]";
		case 3:
	        if ($defaultvalue) $switch_on = "checked='checked'";
	            else $switch_off = "checked='checked'";
			return "<input type='radio' $switch_on value='1' name='setting[$item][$gid]' /> $arr_ad_lng[94] <input $switch_off type='radio' value='0' name='setting[$item][$gid]' /> $arr_ad_lng[95] $arr_ad_lng[912] <input size='5' value='$tdefaultvalue' name='twosetting[$item][$gid]' />";
		case 4:
	        if ($defaultvalue == 'yes') $switch_on = "checked='checked'";
	            else $switch_off = "checked='checked'";
			return "<input type='radio' $switch_on value='yes' name='setting[$item][$gid]' /> $arr_ad_lng[94] <input $switch_off type='radio' value='no' name='setting[$item][$gid]' /> $arr_ad_lng[95]";
		case 5:
	        if (!$defaultvalue) $switch_on = "checked='checked'";
	            else $switch_off = "checked='checked'";
			return "<input type='radio' $switch_on value='0' name='setting[$item][$gid]' /> $arr_ad_lng[94] <input $switch_off type='radio' value='1' name='setting[$item][$gid]' /> $arr_ad_lng[95] $arr_ad_lng[912] <input size='5' value='$tdefaultvalue' name='twosetting[$item][$gid]' />";
		case 6:
			return "<input size='4' value='$defaultvalue' name='setting[$item][$gid]' /> - <input size='4' value='$tdefaultvalue' name='twosetting[$item][$gid]' />";
		case 7:
	        if ($defaultvalue) $switch_on = "checked='checked'";
	            else $switch_off = "checked='checked'";
	        if ($tdefaultvalue == 1) $sswitch_on = "checked='checked'";
			return "<input type='radio' $switch_on value='1' name='setting[$item][$gid]' /> $arr_ad_lng[94] <input $switch_off type='radio' value='0' name='setting[$item][$gid]' /> $arr_ad_lng[95]<br />
   					 <input type='checkbox' $sswitch_on value='1' name='twosetting[$item][$gid]' /> $arr_ad_lng[1212]";
			
	}
	
}