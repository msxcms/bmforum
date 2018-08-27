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

$thisprog = "userdel.php";

if ($useraccess != "1" || $admgroupdata[15] != "1") {
    adminlogin();
} 

@set_time_limit(300);

?>
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong><?php echo $arr_ad_lng[320];?> <?php echo $arr_ad_lng[204];?></strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong><?php echo $arr_ad_lng[204];?></strong>   <FORM METHOD=POST ACTION="admin.php?bmod=userdel.php" style="margin:0px;">
   	  <input type="hidden" name="step" value="2"><input type="hidden" name="dir" value="$id_unique">
  </td></tr>
    <?php if (!$step) {
    ?>

  <tr bgcolor=F9FAFE>
   <td><input type=checkbox name=eareg value=1><?php echo $arr_ad_lng[793];?> </td>
   <td>
    <INPUT TYPE="text" NAME="date" value=2002/01/01>
   </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td><input type=checkbox name=postls value=1><?php echo $arr_ad_lng[794];?></td>
   <td>
    <INPUT TYPE="text" NAME="num" value=5>
   </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td><input type=checkbox name=scorels value=1><?php echo $arr_ad_lng[927];?></td>
   <td>
    <INPUT TYPE="text" NAME="score" value=5>
   </td>
  </tr>
  <tr bgcolor=F9FAFE>
   <td><input type=checkbox name=ealg value=1><?php echo $arr_ad_lng[795];?></td>
   <td>
    <INPUT TYPE="text" NAME="lastlg" value=2002/01/01>
   </td>
  </tr>
<tr bgcolor=F9FAFE>
   <td colspan=2 align=center ><input type=radio checked name=typesd value=or> <?php echo $arr_ad_lng[796];?> <input type=radio name=typesd value=and> <?php echo $arr_ad_lng[797];?><br /><input type=submit value="<?php echo $arr_ad_lng[798];?>"> <input type=reset value="<?php echo $arr_ad_lng[178];?>">
  </tr>

</TABLE>
</FORM><br />
<?php
} else {
	$score = $score * 10;
    if (!empty($num) && !empty($score) && !empty($date) && !empty($lastlg)) { // Start to delete
        $tmplastlg = explode("/", $lastlg);
        $lastlg = mktime (0, 0, 0, $tmplastlg[1], $tmplastlg[2], $tmplastlg[0]);
        $tmpldate = explode("/", $date);
        $date = mktime (0, 0, 0, $tmpldate[1], $tmpldate[2], $tmpldate[0]);

        $addquery = "";
        if ($typesd == "or") { // or mode
            if ($postls == 1) $addquery .= " OR postamount<'$num' ";
            if ($scorels == 1) $addquery .= " OR point<'$score' ";
            if ($eareg == 1) $addquery .= " OR regdate<'$date' ";
            if ($ealg == 1) $addquery .= " OR lastlogin<'$lastlg' ";
            $nquery = "DELETE FROM {$database_up}userlist WHERE pwd='XXXXX' $addquery";
            $result = bmbdb_query($nquery);
        } elseif ($typesd == "and") { // AND mode
            if ($postls == 1) $addquery .= " AND postamount<'$num' ";
            if ($scorels == 1) $addquery .= " AND point<'$score' ";
            if ($eareg == 1) $addquery .= " AND regdate<'$date' ";
            if ($ealg == 1) $addquery .= " AND lastlogin<'$lastlg' ";
            $nquery = "DELETE FROM {$database_up}userlist WHERE pwd!='XXXXX' $addquery";
            $result = bmbdb_query($nquery);
        } 
    } 
    echo '<tr bgcolor=F9FAFE><td><font color=green>' . $arr_ad_lng[804] . '</font></td><td><font color=green>' . $arr_ad_lng[805] . '</font></td></tr>';
} 

?>