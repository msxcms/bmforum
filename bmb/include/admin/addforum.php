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

$thisprog	= "addforum.php";
$forumfile	= "datafile/forumdata.php";

if ($smodaccess == "1" && $useraccess != "1") {
    adminlogin();
} 

$newstring	= "";
print "<tr><td bgcolor=#14568A colspan=3><font color=#F9FAFE>
    <strong>$arr_ad_lng[40]</strong>
    </td></tr>
";

$t = time();
$postdontadd	=	$_POST['postdontadd'];
$guestpost		=	$_POST['guestpost'];
$spusergroup	=	$_POST['spusergroup'];

if (empty($action)) {
    // $forumselect="";
    $forumonly	= "";
    $forumselect = "";
    $catselect = "";
    
    $catselected[$scateid] = "selected='selected'";
    $pforumselected[$sparentid] = "selected='selected'";
    if ($scateid) $type_forum["forum"] = 'selected="selected"';
      else $type_forum["category"] = 'selected="selected"';
    if (!$sparentid) $type_action["newitem"] = 'selected="selected" ';
      else $type_action["newsubitem"] = 'selected="selected" ';

    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        if ($fourmrow['type'] != "category") $forumonly .= "<option value=\"{$fourmrow['id']}\" ".$pforumselected[$fourmrow['id']].">{$fourmrow['bbsname']}</option>";
        $forumselect .= "<option value=\"{$fourmrow['id']}\" ".$catselected[$fourmrow['id']].">{$fourmrow['bbsname']}</option>";
        if ($fourmrow['type'] == "category") $catselect .= "<option value=\"{$fourmrow['id']}\" ".$catselected[$fourmrow['id']].">{$fourmrow['bbsname']}</option>";
    } 
    // $forumselect.="</select>";
    $forumonly .= "</select>";
    $forumselect .= "</select>";
    $catselect .= "</select>";

    print <<<EOT
$table_start
    <strong>$arr_ad_lng[41]</strong><form  action="admin.php?bmod=$thisprog" method="post" style="margin:0px;">$table_stop
    

	    <tr><td bgcolor=#FFFFFF>
    $arr_ad_lng[42]</td><td bgcolor=#FFFFFF><select name="type"><option {$type_forum["category"]} value="category">$arr_ad_lng[43]</option><option {$type_forum["forum"]} value="forum">$arr_ad_lng[44] </option><option value="former">$arr_ad_lng[45]</option><option value="locked">$arr_ad_lng[46]</option><option value="closed">$arr_ad_lng[47]</option><option value="forumhid">$arr_ad_lng[926]</option><option value="hidden">$arr_ad_lng[48]</option><option value="selection">$arr_ad_lng[49]</option><option value="jump">$arr_ad_lng[1079]</option></select>
    <select name="action"><option {$type_action["newitem"]} value="newitem">$arr_ad_lng[69]</option><option {$type_action["newsubitem"]} value="newsubitem">$arr_ad_lng[67]</option></select>
    </td></tr><tr><td bgcolor=#FFFFFF>
    $arr_ad_lng[50]</td><td bgcolor=#FFFFFF><input type=text name="bbsname" size=50>  </td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[51]</td><td bgcolor=#FFFFFF><textarea cols="50" rows="5" name="des"></textarea> </td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[1080]</td><td bgcolor=#FFFFFF><input type="text" name="jump" size="56" /> </td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[52]</td><td bgcolor=#FFFFFF>
EOT;

    echo "<select name='sfilename'>";
    echo "<option value=''>$arr_ad_lng[53]</option>";

    $dh = file("datafile/stylelist.php");
    $cdh = count($dh);
    for($cid = 0;$cid < $cdh;$cid++) {
        $cdhtail = explode("|", $dh[$cid]);
        echo "<option value=\"$cdhtail[3]\">$cdhtail[2]</option>";
    } 
    print <<<EOT
	 </select></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[68]</td><td bgcolor=#FFFFFF><select name="target"><option value=''></option>$forumonly</td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[54]</td><td bgcolor=#FFFFFF><input type=text name="forum_icon" size=30></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[55]</td><td bgcolor=#FFFFFF><input type=password name="forumpass" size=30></td></tr><tr><td bgcolor=#FFFFFF>$arr_ad_lng[56]</td><td bgcolor=#FFFFFF><select name="forum_cid">$catselect</td></tr><tr><td bgcolor=#FFFFFF colspan=2><input type=checkbox value=1 name=guestpost>$arr_ad_lng[57]<br/> <input type=checkbox value=1 name=nnoheldtop>$arr_ad_lng[850] <br/><input type="checkbox" value="openit" name="naviewpost" />$arr_ad_lng[58] <br/><input type="checkbox" value="row" name="ncaterows" />$arr_ad_lng[885] <input type='text' value='4' name='rownum' /><br /><input type=checkbox value=1 name=fordview>$arr_ad_lng[59]<input name=viewpost type="text" size=1>$arr_ad_lng[60]<input name="viewpoice" type="text" size="1" />$arr_ad_lng[61]<input name="viewmoney" type="text" size="1" />$arr_ad_lng[62]<br /><input type="checkbox" value="1" name="postdontadd" />$arr_ad_lng[63] <br /><input type=checkbox value=1 name=spusergroup>$arr_ad_lng[64]     <br />
</td></tr><tr><td bgcolor=#FFFFFF colspan=2>
	<input type=submit value="$arr_ad_lng[66]">
    $tab_bottom</form>

    </td></tr></td></tr></table></body></html>
EOT;
    exit;
} elseif ($action == "newsubitem") {
    // -------新建项目-----------
    $newstring = "";
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[69]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";

    if (empty($bbsname)) exit;
    $type = "sub" . $type;
    if ($type == "subjump" || $type == "subselection" || $type == "subforum" || $type == "subforumhid" || $type == "subformer" || $type == "sublocked" || $type == "subclosed" || $type == "subhidden") {
        $forum_ford = $fordview . "_" . $viewpost . "_" . ($viewpoice * 10) . "_" . $viewmoney; 
        // -----create subforum------
        $des = str_replace("'", "\'", stripslashes($des));
        $bbsname = str_replace("'", "\'", stripslashes($bbsname));

        $linexx	= bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata ORDER BY `id` DESC  LIMIT 0,1 "));
        $newlineno = $linexx['id'] + 1;

        if (!empty($forumpass)) {
            $forumpass = md5($forumpass);
        } 

        $forum_cid = $_POST['forum_cid'];
        $guestpost = $guestpost . "__" . $nnoheldtop;

	    $lineyy = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata WHERE id='$target'"));
	    $showorder = $lineyy['showorder'] + 1;
        
	    $result = bmbdb_query("UPDATE {$database_up}forumdata SET showorder=showorder+1 WHERE showorder>=$showorder");

        
        $ncaterows = ($ncaterows == "row") ? $rownum : 0;

        $result = bmbdb_query("insert into {$database_up}forumdata (showorder,adminlist,fltitle,flposttime,flposter,usergroup,flfname,blad,type,bbsname,cdes,id,forum_icon,filename,forumpass,forum_cid,guestpost,forum_ford,postdontadd,spusergroup,naviewpost,caterows,jumpurl) values ('$showorder','','','','','','','$target','$type','$bbsname','$des','$newlineno','$forum_icon','$sfilename','$forumpass','$forum_cid','$guestpost','$forum_ford','$postdontadd','$spusergroup','$naviewpost','$ncaterows','$jump')");
    } 

	refresh_jscache();
	refresh_forumcach();


} elseif ($action == "newitem") {
    // -------新建项目-----------
    $newstring = "";
    print "<tr><td bgcolor=#F9FAFE valign=middle align=center colspan=2><strong>$arr_ad_lng[73]</strong></td></tr>
	<tr><td bgcolor=F9FAFE colspan=2>";

    if (empty($bbsname)) exit;
    
    $ncaterows = ($ncaterows == "row") ? $rownum : 0;

    if ($type == "category") {
        // -----增加分类------
        $bbsname = str_replace("'", "\'", stripslashes($bbsname));
        
        $linexx = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata ORDER BY `id` DESC  LIMIT 0,1 "));
        $newlineno = $linexx['id'] + 1;
        
        $newstring = "";

        $lineyy = bmbdb_fetch_array(bmbdb_query("SELECT showorder FROM {$database_up}forumdata ORDER BY `showorder` DESC  LIMIT 0,1 "));
        $showorder = $lineyy['showorder'] + 1;

        $result = bmbdb_query("insert into {$database_up}forumdata (type,forum_icon,adminlist,cdes,fltitle,flposttime,flposter,usergroup,flfname,bbsname,id,showorder,blad,filename,forumpass,forum_cid,guestpost,forum_ford,postdontadd,spusergroup,naviewpost,jumpurl) values ('category','','','','','','','','','$bbsname','$newlineno','$showorder','$ncaterows','','','','','','','','','')");

    } elseif ($type == "jump" || $type == "selection" || $type == "forum" || $type == "former" || $type == "forumhid" || $type == "locked" || $type == "closed" || $type == "hidden") {
        // -----增加版块------
        $linexx = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}forumdata ORDER BY `id` DESC  LIMIT 0,1 "));
        $newlineno = $linexx['id'] + 1;
        
        $guestpost = $guestpost . "__" . $nnoheldtop;

        $forum_cid = $_POST['forum_cid'];
        
        $linecate = bmbdb_fetch_array(bmbdb_query("SELECT bbsname,showorder FROM {$database_up}forumdata where id='$forum_cid'"));
        $linecate[showorder] = $linecate[showorder] ? $linecate[showorder] : 0;
        $lineyy = bmbdb_fetch_array(bmbdb_query("SELECT bbsname,showorder FROM {$database_up}forumdata where showorder>$linecate[showorder] and type='category' ORDER BY `showorder` asc  LIMIT 0,1 "));
        
        
        if ($lineyy['showorder']) {
        	$showorder = $lineyy['showorder'];
        	if (!$showorder) $showorder = 0;
	        $result = bmbdb_query("UPDATE {$database_up}forumdata SET showorder=showorder+1 WHERE showorder>=$showorder");
    	} else {
	        $query_last = bmbdb_fetch_array(bmbdb_query("SELECT bbsname,showorder FROM {$database_up}forumdata ORDER BY `showorder` DESC LIMIT 1"));
	        $showorder = $query_last['showorder'] + 1;
	        if (!$showorder) $showorder = 0;
	        $result = bmbdb_query("UPDATE {$database_up}forumdata SET showorder=showorder+1 WHERE showorder>$showorder");
	    }

        

        $forum_ford = $fordview . "_" . $viewpost . "_" . ($viewpoice * 10) . "_" . $viewmoney;

        $des = str_replace("'", "\'", stripslashes($des));
        $bbsname = str_replace("'", "\'", stripslashes($bbsname));
        
        if (!empty($forumpass)) $forumpass = md5($forumpass);

        $result = bmbdb_query("insert into {$database_up}forumdata (type,adminlist,blad,fltitle,flposttime,flposter,usergroup,flfname,bbsname,cdes,id,forum_icon,filename,forumpass,forum_cid,guestpost,forum_ford,postdontadd,spusergroup,naviewpost,showorder,caterows,jumpurl) values ('$type','','','','','','','','$bbsname','$des','$newlineno','$forum_icon','$sfilename','$forumpass','$forum_cid','$guestpost','$forum_ford','$postdontadd','$spusergroup','$naviewpost','$showorder','$ncaterows','$jump')");

    } 

	refresh_jscache();
	refresh_forumcach();

    echo "<br /><br />$arr_ad_lng[74]<strong></strong><br />";
} 

print "<br /><strong>&nbsp;$arr_ad_lng[75]</strong><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=setforum.php>$arr_ad_lng[76]</a></td></tr></table></body></html>";
exit;
