<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
include_once("datafile/config.php");
include_once("getskin.php");
include_once("include/bmbcodes.php");
include_once("include/template.php");
include_once("lang/$language/announcesys.php");
include_once("newtem/$temfilename/global.php");

$idm_unique = $data_path;
$announceadmin = 0;
$add_title = " > $ggl[3]";
$msgg_max = 100; #系统记录的最大公告数目
$pagesize = 10; #每页显示数目
if ($job == "") $job = show;
if (empty($forumid)) {
    if ($usertype[22] == "1" || $usertype[21] == "1") {
        $announceadmin = 1;
    } 
    $musia = 0;
    if ($announceadmin == 1) $musia = 1;
    if ($usertype[12] == "1") $musia = 1;
    if ($login_status != 1 || $musia != 1) $not_member = 1;
    $announcename = "datafile/announcesys.php";
    eval(load_hook('int_announce_main_announce'));
} else {
    get_forum_info("");
    $announceadmin = 0; 
    // ######## 检测是否为管理员开始 ##########
    $xfourmrow = $sxfourmrow;
    for($i = 0;$i < $forumscount;$i++) {
	    if ($xfourmrow[$i][id] == $forumid) $adminlist .= $xfourmrow[$i]['adminlist'];
	    if ($xfourmrow[$i][id] == $forum_cid) $adminlist .= $xfourmrow[$i]['adminlist'];
	    if ($xfourmrow[$i][id] == $forum_upid) $adminlist .= $xfourmrow[$i]['adminlist'];
    } 
    if ($login_status == 1 && $announceadmin == 0 && $adminlist != "") {
        $arrayal = explode("|", $adminlist);
        $admincount = count($arrayal);
        for ($i = 0; $i < $admincount; $i++) {
            if ($arrayal[$i] == $username && $arrayal[$i] != "" && $arrayal[$i] != "|" && $login_status == 1) $announceadmin = 1;
        } 
    } 
    // ######## 检测是否为管理员结束 ##########
    if ($usertype[22] == "1" || $usertype[21] == "1") {
        $announceadmin = 1;
    } 
    $musia = 0;
    if ($announceadmin == 1) $musia = 1;
    if ($usertype[12] == "1") $musia = 1;
    if ($login_status != 1 || $musia != 1) $not_member = 1;
    if (!$modano_true) $announceadmin = 0;
    $announcename = "datafile/announcement{$forumid}.php";
    eval(load_hook('int_announce_check_admin'));
} 

if (!isset($page)) $page = 1;

if ($verify != $log_hash && $step == 2) $announceadmin = 0;

$user = $username;
if ($job == "read") {
    if (file_exists($announcename)) {
    	if ($msg == 0) exit;
        $receivelist = file($announcename);
        list($author, $title, $time, $content, $member) = explode("|", $receivelist[$msg]);
        $ctime = getfulldate($time);
        if ($member == "1" && $not_member == "1") $content = "$ggl[4]";
        $userinfoget = get_user_info($author);
        $usertype = $userinfoget[18];
        list(, , , , , , , , , $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], , , , , , , , , , , , , , ,$html_codeinfo , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney']) = explode("|", $usergroupdata[$usertype]);
        $bcode_sign['table'] = $bcode_sign['table'] = $usertype[115];

		$lang_zone = array("ggl"=>$ggl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
		$template_name = newtemplate("announce_read", $temfilename, $styleidcode, $lang_zone);


        $content = bmbconvert($content, $bmfcode_post);
        
        eval(load_hook('int_announce_before_read'));
        require ("header.php");
        $navimode = newmode;
        if ($forumid) $snavi_bar[] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
        $snavi_bar[] = "<a href='announcesys.php?forumid=$forumid'>$ggl[12]</a>";
        $snavi_bar[] = $ggl[6];
        $des = $ggl[11];
        navi_bar();
        
       	require($template_name);
        require("footer.php");
    } 
} 
if ($job == "show") {
	$lang_zone = array("ggl"=>$ggl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("announce_list", $temfilename, $styleidcode, $lang_zone);

    if (file_exists($announcename)) {
        $receivelist = file($announcename);
        $count = count($receivelist);
        $sum = $count-1;
        $totalpage = ceil($sum / $pagesize);
        if ($page > $totalpage || $page <= 0) $page = 1;
        if ($totalpage == 0) {
            $totalpage = 1;
            $page = 1;
        } 
        $start = $pagesize * ($page-1)+1;
        $end = $start + $pagesize;
        if ($count <= 1 && empty($receivelist[0])) {
        	$announce_arr[] = array("content"=>$ggl[13], "author"=>"N/A", "time"=>"N/A");
        } else {
            for ($i = $start; $i < $end; $i++) {
                $content = "";
                if ($i > $sum) break;
                list($author, $title, $time, $content, $member) = explode("|", $receivelist[$i]);
                $userinfoget = get_user_info($author);
                $usertype = $userinfoget[18];
                list(, , , , , , , , , $bcode_sign['pic'], $bcode_sign['flash'], $bcode_sign['fontsize'], , , , , , , , , , , , , , ,$html_codeinfo , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , $bcode_post['pic'], $bcode_post['reply'], $bcode_post['jifen'], $bcode_post['sell'], $bcode_post['flash'], $bcode_post['mpeg'], $bcode_post['iframe'], $bcode_post['fontsize'], $bcode_post['hpost'], $bcode_post['hmoney']) = explode("|", $usergroupdata[$usertype]);
                $bcode_sign['table'] = $bcode_sign['table'] = $usertype[115];
                if ($announceadmin) $content .= "<br /><div align=center> [<a href='announcesys.php?forumid=$forumid&job=write&type=edit&msg=$i'>$ggl[47]</a>] [<a href='announcesys.php?forumid=$forumid&job=delone&verify=$log_hash&msg=$i'>$ggl[17]</a>]</div>";
                $realcontent = bmbconvert($content, $bcode_post);
                if ($member == "1" && $not_member == "1") $realcontent = "$ggl[4]";


	        	$announce_arr[] = array("title"=>$title, "content"=>$realcontent, "author"=>$author, "time"=>getfulldate($time));
            } 
        } 
    } 
	
	$sum = max(0, $sum-1);
	
	eval(load_hook('int_announce_before_dp'));
    require("header.php");
    $navimode = newmode;
    if ($forumid) $snavi_bar[] = "<a href='forums.php?forumid=$forumid'>$forum_name</a>";
    $snavi_bar[] = "<a href='announcesys.php?forumid=$forumid'>$ggl[12]</a>";
    $des = $ggl[11];
    navi_bar();
    pagenum();
    require($template_name);
    require("footer.php");
} 

if ($job == "write") {
    if ($announceadmin != 1) {
        require("header.php");
        echo "$ggl[18]";
        require("footer.php");
        exit;
    } 
    if (empty($step)) {
        if ($type == "edit") {
            if (file_exists($announcename)) {
                $receivelist = file($announcename);
                $count = count($receivelist);
                for ($i = 1; $i < $count; $i++) {
                    if ($i == $msg) {
                        $detail = explode("|", $receivelist[$i]);
                        $title = $detail[1];
                        $content = $detail[3];
                        break;
                    } 
                } 
            } 
        } 
        eval(load_hook('int_announce_editor_ui'));
        require("header.php");
        navi_bar($ggl[11], $ggl[15], '', 'no');
        print_form();
        require("footer.php");
    } elseif ($step == 2) {
        if (empty($content) || empty($title) || $announceadmin != 1) {
            echo "$ggl[18]";
            exit;
        } 
        if (file_exists($announcename)) $msg = file($announcename);
        else $msg[0] = "";
        $content = stripslashes(safe_convert($content));
        $title = stripslashes(safe_convert($title));
        $title = "" . $title;
        if ($type == "edit") {
            if (file_exists($announcename)) {
                $receivelist = file($announcename);
                $count = count($receivelist);
                for ($i = 1; $i < $count; $i++) {
                    if ($i == $msgnew) {
                        $new = "$user|". str_replace("|", "│", $title) ."|$timestamp|". str_replace("|", "│", $content) ."|$member\n";
                    } else {
                        $old .= $receivelist[$i];
                    } 
                } 
            } 
        } else {
            $new = "$user|". str_replace("|", "│", $title) ."|$timestamp|". str_replace("|", "│", $content) ."|$member\n";
            $oldcount = count($msg);
            if ($oldcount > $msgg_max+1) {
                for ($i = $msgg_max+1; $i < $oldcount; $i++) unset($msg);
            } 
            unset($msg[0]);
            $old = implode("", $msg);
        } 
        eval(load_hook('int_announce_editor_p'));
        writetofile($announcename, "<?php die();?>\n".$new . $old);
        jump_page("announcesys.php?forumid=$forumid", $ggl[12],
            "<strong>$ggl[19]: </strong><br /><br /><a href='announcesys.php?forumid=$forumid'>$ggl[3]</a>", 3);
    } 
} 
if ($job == "clear") {
    if ($announceadmin != 1) {
        require("header.php");
        echo "$ggl[18]";
        require("footer.php");
        exit;
    } 
    eval(load_hook('int_announce_clear'));
    require("header.php");
    navi_bar($ggl[11], $ggl[12], '', 'no');
    msg_box("$ggl[3]", "$ggl[20] <a href='announcesys.php?forumid=$forumid&job=yesclear&verify=$log_hash'>$ggl[39]</a>  <a href='javascript:history.back(1);'>$ggl[40]</a>");
    require("footer.php");
} 

if ($job == "yesclear" && $verify == $log_hash) {
    if ($announceadmin != 1) {
        require("header.php");
        echo "$ggl[18]";
        require("footer.php");
        exit;
    } 
    eval(load_hook('int_announce_clear_p'));
    if (file_exists($announcename)) unlink($announcename);
    jump_page("announcesys.php?forumid=$forumid", $ggl[12],
        "<strong>$ggl[21]: </strong><br /><br /><a href='announcesys.php?forumid=$forumid'>$ggl[3]</a>", 3);
    exit;
} 

if ($job == "delone" && $verify == $log_hash) {
    if ($announceadmin != 1) {
        require("header.php");
        echo "$ggl[18]";
        require("footer.php");
        exit;
    } 
    if (file_exists($announcename)) {
        $receivelist = file($announcename);
        $count = count($receivelist);
        eval(load_hook('int_announce_del_single'));
        if ($msg == 0) exit;
        $fp = fopen($announcename, "w");
        for ($i = 0; $i < $count; $i++) {
            if ($i != $msg) fputs($fp, $receivelist[$i]);
        } 
        fclose($fp);
        jump_page("announcesys.php?forumid=$forumid", $ggl[12],
            "<strong>$ggl[22]: </strong><br /><br /><a href='announcesys.php?forumid=$forumid'>$ggl[3]</a>", 3);
        exit;
    } 
} 

if ($job == "showzt") {
    if ($announceadmin != 1) {
        require("header.php");
        echo "$ggl[18]";
        require("footer.php");
        exit;
    } 
    if (file_exists($announcename)) {
        $receivelist = file($announcename);
        $count = count($receivelist)-1;
        $contl = ($count / $msgg_max) * 100;
        eval(load_hook('int_announce_status'));
        require("header.php");
        navi_bar($ggl[11], $ggl[12], '', 'no');
        msg_box("$ggl[3]", "$ggl[23]<span class='jiazhongcolor'>" . $count . "</span>$ggl[24]<br />$ggl[25]<span class='jiazhongcolor'>" . $msgg_max . "</span>$ggl[24]<br />$ggl[26]<span class='jiazhongcolor'>" . $contl . "%</span><br /><br /><br /><a href='announcesys.php?forumid=$forumid'>$ggl[27]</a>");
        require("footer.php");
    } 
} 
function sline()
{
    print ("</td>
</tr></table>
    </td>
  </tr>
</table>
<br />
<table cellpadding='0' cellspacing='0' border='0' class='tableborder' align='center'>
<tr><td><table cellpadding='5' cellspacing='1' border='0' width='100%'>");
} 

function pagenum()
{
    global $sum, $pagesize, $pageinfo, $gl, $ggl, $page, $totalpage, $admininfo;
    $sum1 = $sum + 1;
    $pageinfo = "$ggl[28] $sum1 $ggl[29] $totalpage $ggl[30],$ggl[31] $pagesize $ggl[32]  $page $ggl[30]&nbsp;&nbsp;&nbsp;";
    $back = $page-1;
    $forward = $page + 1;
    if ($page == 1) {
        $pageinfo .= "$ggl[33] $ggl[34] ";
    } else {
        $pageinfo .= "<a href=\"announcesys.php?forumid=$forumid&page=1\">$ggl[33]</a> <a href=\"announcesys.php?forumid=$forumid&page=$back\">$ggl[34]</a> ";
    } 
    if ($page == $totalpage) {
        $pageinfo .= "$ggl[35] $ggl[36]<br />";
    } else {
        $pageinfo .= "<a href=\"announcesys.php?forumid=$forumid&page=$forward\">$ggl[35]</a> <a href=\"announcesys.php?forumid=$forumid&page=$totalpage\">$ggl[36]</a><br />";
    } 
} 
function print_form()
{
    global $target, $gl, $ggl, $title, $msg, $content, $forumid, $log_hash, $type;
    $content = str_replace("<br />", "\n", $content);

    ?>
<form action="announcesys.php?forumid=<?php echo $forumid;?>" method="post" style="margin:0px;">
<input type="hidden" value="<?php echo $log_hash;?>" name="verify" />
<table cellpadding="0" cellspacing="0" border="0" class="tableborder" align="center">
<tr><td><table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr> 
  <td class="announcement" colspan="2"><strong><?php echo $ggl[37];?> </strong></td>
</tr>
<tr>
  <td class="article_color1" style="width:5%;"><?php echo $ggl[10];?></td>
   <td><input value='<?php echo $title;?>'  type="text" size="70" name="title" />
  </td>
</tr>
<tr>
  <td class="article_color1" style="width:5%;">
      <?php echo $ggl[5];?></td>
   <td><textarea name="content" cols="70" rows="10"><?php echo $content;?></textarea>
      
    </td>
  </tr>
<tr>
<td colspan="2" style="padding-left:5%;" class="form-actions">
<input type="submit" value="<?php echo $ggl[37];?>" class="btn btn-primary" />
<input type="hidden" name="job" value="write" />
<input type="hidden" name="step" value="2" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="msgnew" value="<?php echo $msg;?>" />
</td>
  </tr>
</table>
    </td>
  </tr>
</table>
</form>
<?php } 
