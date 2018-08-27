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

$thisprog = "modforum.php";

if ($smodaccess == "1" && $useraccess != "1") {
    adminlogin();
} 

$newstring = $seusert = ""; // Filter Var

print "<tr><td bgcolor='#14568A' colspan=3><font color='#F9FAFE'>
    <strong>$arr_ad_lng[320] $arr_ad_lng[189]</strong>
    </td></tr>";
// Title Echo
$t = time();

if (empty($job) && empty($action)) {
    // ERROR INCLUDE
    exit;
} elseif ($job == "modify") {
    // Load Forum Data
    $nquery = "SELECT * FROM {$database_up}forumdata WHERE id='$target'";
    $nresult = bmbdb_query($nquery);
    $fourmrow = bmbdb_fetch_array($nresult);

    $admindata = explode("|", $fourmrow['adminlist']); // Explode Admin List by "|"
    $cosunt = count($admindata);
    $forumadminshow = "<select name=userdelid>";
    for($i = 0; $i < $cosunt; $i++) {
        if (!empty($admindata[$i])) {
            $forumadminshow .= "<option value=\"{$admindata[$i]}\">{$admindata[$i]}</option>";
        } 
    } 
    // UserGroup List
    $count = count($usergroupdata);
    foreach ($usergroupdata as $key=>$value) {
        $userac = explode("|", $value);
        $seusert .= "<option value=$key>$userac[0]</option>";
    } 
    // Admin List Created
    $forumadminshow .= "</select>";
    $forumonly	= "";
    $forumtype	= $fourmrow['type'];
    $forumname	= $fourmrow['bbsname'];
    $forumdes	= $fourmrow['cdes'];
    $jumpurl	= $fourmrow['jumpurl'];
    $targetid	= $fourmrow['id'];
    $forumsubid	= $fourmrow['blad'];
    $caterows	= $fourmrow['caterows'];
    $forumicon	= $fourmrow['forum_icon'];
    $forumstyle	= $fourmrow['filename'];
    $forumcid	= $fourmrow['forum_cid'];
    $guestpost	= $fourmrow['guestpost'];
    $forum_ford	= $fourmrow['forum_ford'];
    $aviewpost	= $fourmrow['naviewpost'];
    $postdontadd	= $fourmrow['postdontadd'];
    $spusergroup	= $fourmrow['spusergroup'];
    $forumpassword	= $fourmrow['forumpass'];
    // import var name End
    
    //Adv. in forum
    
	if (file_exists("datafile/rowads/{$target}.php")) {
		@include_once("datafile/rowads/{$target}.php");

    	$ads_row = htmlspecialchars($ads_row);
    } else $ads_every = 4;
    
    
    // Load Forum Rules
    @include("datafile/rules/$target.php");
    $ourrule = str_replace("<br />", "\n", $ourrule); 
    // Load Tags Topic
    @include("datafile/cache/tags_topic.php");
    $tags_tlist[$target] = htmlspecialchars($tags_tlist[$target]);
    // Parse Vars to HTML checkbox, etc.
    if ($caterows) $scaterows = "checked='checked'";
    $guestpost = explode("_", $guestpost);
    $ford = explode("_", $forum_ford);
    if ($ford[0] == 1) $sford = "checked='checked'";
    $ford[2] = $ford[2] / 10;
    if ($postdontadd == 1) $spostdontadd = "checked='checked'";
    if ($spusergroup == 1) $sspusergroup = "checked='checked'";
    if ($aviewpost == "openit") $saviewpost = "checked='checked'";
    if ($guestpost[0] == 1) $sguestpost = "checked='checked'";
    if ($guestpost[1] == "1") $snopostpic = "checked='checked'";
    if ($guestpost[2] == "1") $snoheldtop = "checked='checked'"; 
    // Forum and category select
    $catselect = "";
    $forumonly = "";
    $catselect .= "<option value=\"$forumcid\">$arr_ad_lng[444]</option>";
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] != "category") $forumonly .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
        if ($fourmrow['type'] == "category") $catselect .= "<option value=\"{$fourmrow['id']}\">{$fourmrow['bbsname']}</option>";
    } 
    $catselect .= "</select>";
    $forumonly .= "</select>";

    $urlencname = urlencode($forumname);
    
    $forumdes = htmlspecialchars($forumdes);

    print <<<EOT
$table_start
    <strong>$arr_ad_lng[438]</strong><form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="oldforumpassword" value="$forumpassword"><input type=hidden name="action" value="maction"><input type=hidden name="job" value="modify2"><input type=hidden name="targetid" value="$targetid">

	    <tr><td bgcolor=#FFFFFF colspan=2>
	  
    $arr_ad_lng[440] $forumname(ID:$targetid) | <a href="admin.php?bmod=$thisprog&action=maction&job=delete&targetid=$targetid&bbname=$urlencname">$arr_ad_lng[441]</a></td></tr><tr><td bgcolor=#FFFFFF>
$arr_ad_lng[50]</td><td bgcolor=#FFFFFF><input type=text value="$forumname" name="bbsname" size=50></td></tr><tr><td bgcolor=#FFFFFF>  $arr_ad_lng[51]</td><td bgcolor=#FFFFFF><textarea cols="50" rows="5" name="des">$forumdes</textarea></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[1080]</td><td bgcolor=#FFFFFF><input type="text" value="$jumpurl" name="jump" size="56" /> </td></tr><tr><td bgcolor=#FFFFFF>  $arr_ad_lng[42]</td><td bgcolor=#FFFFFF><select name="forumtype"><option value=$forumtype>$arr_ad_lng[442]</option><option value="category">$arr_ad_lng[43]</option><option value="forum">$arr_ad_lng[44]</option><option value="former">$arr_ad_lng[45]</option><option value="locked">$arr_ad_lng[46]</option><option value="forumhid">$arr_ad_lng[926]</option><option value="hidden">$arr_ad_lng[48]</option><option value="closed">$arr_ad_lng[47]</option><option value="selection">$arr_ad_lng[49]</option><option value="jump">$arr_ad_lng[1079]</option><option value="subforum">{$arr_ad_lng[443]}$arr_ad_lng[44]</option><option value="subformer">{$arr_ad_lng[443]}$arr_ad_lng[45]</option><option value="sublocked">{$arr_ad_lng[443]}$arr_ad_lng[46]</option><option value="subforumhid">{$arr_ad_lng[443]}$arr_ad_lng[926]</option><option value="subhidden">{$arr_ad_lng[443]}$arr_ad_lng[48]</option><option value="subclosed">{$arr_ad_lng[443]}$arr_ad_lng[47]</option><option value="subselection">{$arr_ad_lng[443]}$arr_ad_lng[49]</option><option value="subjump">{$arr_ad_lng[443]}$arr_ad_lng[1079]</option></select> 
		</td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[52]</td><td bgcolor=#FFFFFF>
		
EOT;

    echo "<select name='sfilename'>";
    echo "<option value='$forumstyle'>$arr_ad_lng[445]</option>";
    echo "<option value=''>$arr_ad_lng[53]</option>";

    $dh = file("datafile/stylelist.php");
    $cdh = count($dh);
    for($cid = 0;$cid < $cdh;$cid++) {
        $cdhtail = explode("|", $dh[$cid]);
        echo "<option value=\"$cdhtail[3]\">$cdhtail[2]</option>";
    } 

    print <<<EOT
	</select></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[54]</td><td bgcolor=#FFFFFF><input type=text value="$forumicon" name="forum_iconurl" size=30></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[55]</td><td bgcolor=#FFFFFF><input type=password name="newforumpass" value="$forumpassword" size=30></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[56]</td><td bgcolor=#FFFFFF><select name="new_forum_cid">$catselect</td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[447]</td><td bgcolor=#FFFFFF><select name="forumsubid"><option value="$forumsubid">$arr_ad_lng[448]</option><option value="">$arr_ad_lng[449]</option>$forumonly</td></tr><tr><td bgcolor=#FFFFFF colspan=2>
	<input type=checkbox value=1 $sguestpost name=nguestpost>$arr_ad_lng[57] <br /><input type=checkbox value=1 $snoheldtop name=nnoheldtop>$arr_ad_lng[850] <br /><input type=checkbox value=openit $saviewpost name=naviewpost>$arr_ad_lng[58]<br /><input type="checkbox" value="row" $scaterows name="ncaterows" />$arr_ad_lng[885] <input type='text' value='{$caterows}' name='rownum' /><br /><input type=checkbox value=1 $sford name=fordview>$arr_ad_lng[59]<input name=viewpost value='$ford[1]' type="text" size=1>$arr_ad_lng[60]<input name=viewpoice value='$ford[2]' type="text" size=1>$arr_ad_lng[61]<input name=viewmoney value='$ford[3]' type="text" size=1>$arr_ad_lng[62]<br /><input type=checkbox value=1 $spostdontadd name=npostdontadd>$arr_ad_lng[63]     <br /><input type=checkbox value=1 $sspusergroup name=nspusergroup>$arr_ad_lng[64]     </td></tr><tr><td bgcolor=#FFFFFF colspan=2>
	<input type=submit value="$arr_ad_lng[66]">
    </form>
 $table_start <strong>$arr_ad_lng[450]</strong>$table_stop<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="moderator">
    $arr_ad_lng[451]<br /><input type=hidden name="targetid" value="$target">
    $arr_ad_lng[452]<input type=radio checked='checked' name="job" value="add">$arr_ad_lng[453]<input type=text name="changeuserid" size=8>  <input type=radio  name="job" value="del">$arr_ad_lng[454]$forumadminshow &nbsp;&nbsp;
    <br /><input type=checkbox value='1' name='modmugnum'>$arr_ad_lng[963] <select name='modtugnum'>$seusert</select>
    <br /><input type=submit value="$arr_ad_lng[66]">
    </form>

$table_start<strong>$arr_ad_lng[886]</strong>$table_stop<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type=hidden name="action" value="rules">
    <input type=hidden name="targetid" value="$target">
    $arr_ad_lng[883]<br /><textarea cols=60 rows=6 name="forumrules">$ourrule</textarea>
      <br />
    <input type=submit value="$arr_ad_lng[66]">
   </form>
 
$table_start
			$arr_ad_lng[1066]
$table_stop
		$arr_ad_lng[1067]
		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type="hidden" name="action" value="f_ads" /><input type="hidden" name="target" value="$target" />
		<br />
		<textarea cols="100" rows="5" name="announcement">$ads_row</textarea><br />
	     $arr_ad_lng[962] <input type="text" name='every_ads' value='$ads_every' size="5" /><br />

		<input type=submit value="$arr_ad_lng[66]">
			</form>
$table_start
		<strong>5.</strong>$arr_ad_lng[1101]
$table_stop
		$arr_ad_lng[1102]
		<form action="admin.php?bmod=$thisprog" method="post" style="margin:0px;"><input type="hidden" name="action" value="tags" /><input type="hidden" name="target" value="$target" />
		<br />
		<textarea cols="100" rows="5" name="tags_solid">$tags_tlist[$target]</textarea><br />
<br />

		<input type=submit value="$arr_ad_lng[66]">
			</form>
    <br />
    	

    </td></tr></td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "maction") {
    // -------Delete and modify items-----------
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[455]</strong></td></tr>
<tr><td bgcolor=F9FAFE colspan=2>";
    $forum_ford = $fordview . "_" . $viewpost . "_" . ($viewpoice * 10) . "_" . $viewmoney;
    $newstring = "";
    $nguestpost = $nguestpost . "__" . $nnoheldtop;
    if (!empty($newforumpass) && $newforumpass != $oldforumpassword) {
        $xiuforumpass = md5($newforumpass);
    } 
    if ($xiuforumpass == "d41d8cd98f00b204e9800998ecf8427e") $xiuforumpass = "";

    if ($job == "modify2") { // Modify Forum
        if ($forumtype == "category") {
            $cateoptions = "blad='',";
        } else {
            $cateoptions = "blad='$forumsubid',";
        } 
        
        if ($forumsubid) $forumtype = "sub". str_replace("sub", "", $forumtype);
            else $forumtype = str_replace("sub", "", $forumtype);
        
	    $nquery = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid'";
	    $nresult = bmbdb_query($nquery);
	    $fourmrow = bmbdb_fetch_array($nresult);
	    
	    if ($fourmrow['type'] == "category" && $forumtype != "category") {
	    	bmbdb_query("UPDATE {$database_up}forumdata SET `forum_cid`='$new_forum_cid' WHERE `forum_cid`='$targetid'");
	    }

        
        if ($forumsubid && $forumsubid != $fourmrow['blad']) 
        {
		    $last_forum = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata WHERE id='$forumsubid'"));
        	$showorder	= $last_forum['showorder'];
        	bmbdb_query("UPDATE {$database_up}forumdata SET showorder=showorder+1 WHERE showorder>$showorder");
        	$neworder = "showorder=$showorder+1,";
        } elseif($forumtype != "category" && $new_forum_cid != $fourmrow['forum_cid']) {
		    $last_forum = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata WHERE id='$new_forum_cid'"));
        	$showorder	= $last_forum['showorder'];

        	bmbdb_query("UPDATE {$database_up}forumdata SET showorder=showorder+1 WHERE showorder>$showorder");
        	$neworder = "showorder=$showorder+1,";
        }
        
        $ncaterows = ($ncaterows == "row") ? $rownum : 0;
        
        $nquery = "UPDATE {$database_up}forumdata SET {$neworder}{$cateoptions}naviewpost='$naviewpost',jumpurl='$jump',caterows='$ncaterows',spusergroup='$nspusergroup',postdontadd='$npostdontadd',forum_ford='$forum_ford',guestpost ='$nguestpost',forum_cid='$new_forum_cid',forumpass='$xiuforumpass',filename='$sfilename',forum_icon='$forum_iconurl',cdes='$des',bbsname='$bbsname',type='$forumtype' WHERE id = '$targetid'";
        $result = bmbdb_query($nquery);
        
        
        if($forumtype != "category" && $new_forum_cid != $fourmrow['forum_cid']) {
		    $count_blad = bmbdb_fetch_array(bmbdb_query("SELECT COUNT(id) FROM {$database_up}forumdata WHERE blad='$targetid'"));
		    $neworder = bmbdb_fetch_array(bmbdb_query("SELECT showorder FROM {$database_up}forumdata WHERE id='$targetid'"));
			
			if ($count_blad['COUNT(id)'] > 0) {
	       		bmbdb_query("UPDATE {$database_up}forumdata SET `forum_cid`='$new_forum_cid',`showorder`=showorder-$fourmrow[showorder]+$neworder[showorder] WHERE blad='$targetid'");
	       		bmbdb_query("UPDATE {$database_up}forumdata SET `showorder`=showorder+1+{$count_blad['COUNT(id)']} WHERE `showorder`>'$neworder[showorder]' AND `blad`!='$targetid'");
	       	}
	    }
       	
        
        $order_reset = 1;
        	
	    $nresult = bmbdb_query("SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC ");
	    while (false !== ($tmp_order = bmbdb_fetch_array($nresult))) {
	    	bmbdb_query("UPDATE {$database_up}forumdata SET `showorder`='$order_reset' WHERE `id`='$tmp_order[id]'");
	    	$order_reset++;
	    }
        
    } elseif ($job == "delete") { // Delete forums
        if ($step != 2) {
            print "<br />$tab_top<strong>$arr_ad_lng[456] $bbname ?</strong><br /><ul><li><a href=admin.php?bmod=modforum.php&action=maction&job=delete&targetid=$targetid&step=2>$arr_ad_lng[457]</a></li><li><a href='admin.php?bmod=modforum.php&action=maction&job=delete&targetid=$targetid&step=2&delpost=1'>$arr_ad_lng[1054]</a></li><li><a href=admin.php?bmod=setforum.php>$arr_ad_lng[458]</a></li></ul>$tab_bottom</td></tr></table></body></html>";
            exit;
        } 
        
	    $order_a = bmbdb_fetch_array(bmbdb_query("SELECT showorder FROM {$database_up}forumdata WHERE id='$targetid'"));
	    $order = bmbdb_fetch_array(bmbdb_query("SELECT `id` FROM {$database_up}forumdata WHERE `showorder`<{$order_a['showorder']} and type='category' order by `showorder` DESC LIMIT 1"));
	    
	    $order[id] = $order[id] ? $order[id] : 0;
	    
	    bmbdb_query("UPDATE {$database_up}forumdata SET `forum_cid`='$order[id]' WHERE `forum_cid`='$targetid'");
        
        $nquery = "DELETE FROM {$database_up}forumdata WHERE id = '$targetid'";
        $result = bmbdb_query($nquery);
        if ($delpost == 1) {
	        bmbdb_query("DELETE FROM {$database_up}threads WHERE forumid = '$targetid'");
	        bmbdb_query("DELETE FROM {$database_up}posts WHERE forumid = '$targetid'");
	        bmbdb_query("DELETE FROM {$database_up}polls WHERE forumid = '$targetid'");
	    }
    } 
    // Refresh Javascript Cache
	refresh_jscache();
    // Forum Data Cache
	refresh_forumcach();


} elseif ($action == "moderator") {
    // -------Moderator Setting-----------
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[459]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";
	


    if ($modmugnum == 1) {
    	$tochangename = $changeuserid ? $changeuserid : $userdelid;
        $modquery = "UPDATE {$database_up}userlist SET ugnum='$modtugnum' WHERE username='$tochangename' LIMIT 1";
        $modresult = bmbdb_query($modquery);
    }

    if ($job == "add") {
    	if (!check_user_exi($changeuserid)) die("<br /><strong>&nbsp;$arr_ad_lng[1035]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=setforum.php>$arr_ad_lng[76]</a></td></tr></table></body></html>");
    	
        $xxquery = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid' LIMIT 0,1 ";
        $xxresult = bmbdb_query($xxquery);
        $line = bmbdb_fetch_array($xxresult);
        $newadminlist = $line['adminlist'] . "$changeuserid|";

        $nquery = "UPDATE {$database_up}forumdata SET adminlist='$newadminlist' WHERE id='$targetid'";
        $result = bmbdb_query($nquery);
    } else {
        $xxquery = "SELECT * FROM {$database_up}forumdata WHERE id='$targetid' LIMIT 0,1 ";
        $xxresult = bmbdb_query($xxquery);
        $line = bmbdb_fetch_array($xxresult);
        $newadminlist = str_replace("$userdelid|", "", $line['adminlist']);

        $nquery = "UPDATE {$database_up}forumdata SET adminlist='$newadminlist' WHERE id='$targetid'";
        $result = bmbdb_query($nquery);
    } 
    refresh_forumcach();
} elseif ($action == "rules") {
    // ------Forum Rules-----------
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[459]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";
    writetofile("datafile/rules/$targetid.php", '<?php  $ourrule=\'' . str_replace("\n", "<br />", str_replace("'", "\'", stripslashes($forumrules))) . '\';');
} elseif ($action == "tags") {
    // ------ Forum Tags Topic -----------
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[459]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";
	
	@include("datafile/cache/tags_topic.php");
	
	$tags_solid = strtolower(str_replace('\"', '"', $tags_solid));
	
	$add_line = "<?php\n";
	$add_line .= "\$tags_tlist[$target] = '$tags_solid';\n";
	
	if (is_array($tags_tlist)){
		foreach ($tags_tlist as $key=>$value) {
			if ($key != $target) 
				$add_line.= "\$tags_tlist[$key]='$value';\n";
		}
	}
	
	writetofile("datafile/cache/tags_topic.php", $add_line);

} elseif ($action == "f_ads") {
    // ------Forum Ads-----------
    @include("datafile/row_text.php");
    $showindex		= $row_show[showindex];
    $showforum		= $row_show[showforum];
    $showtopic		= $row_show[showtopic];
    $row_new_text	= row_ads_maker($announcement, $every_ads);
    $row_file		= "datafile/rowads/{$target}.php";
    
    writetofile ($row_file, $row_new_text);
    
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[459]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";
} 

print "<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=setforum.php>$arr_ad_lng[76]</a></td></tr></table></body></html>";
exit;
