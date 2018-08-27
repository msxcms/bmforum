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

if ($bmfopt['inviteallow'] != "" && $reg_invit) $inviteallow = explode(",", $bmfopt['inviteallow']);

if ($step == "v") {
	eval(load_hook('int_active_active'));

    if ($_GET['id'] == $activestatus && $activestatus && $_GET['id']) {
    	$query = "UPDATE {$database_up}userlist SET activestatus='', ugnum='$ugactive' WHERE userid='$userid'";
        $result = bmbdb_query($query);
        
        msg_box($r_a[0], $r_a[1]);
    } else {
        msg_box($r_a[0], $r_a[2]);
    }
    
} elseif ($step == "i" && @in_array($logonutnum, $inviteallow)) {
	eval(load_hook('int_active_invite'));
	if ($mod == "add" && $_POST['emailinv']){ 
		$check = 1;
        if ($bmfopt[invite_type] == 1) $userpoint = $usermoney;

		$last_invite = bmbdb_fetch_array(bmbdb_query("SELECT datetime FROM {$database_up}invite WHERE inviter ='$userid' ORDER BY `datetime` DESC LIMIT 1"));
		
		if ($invit_del_point != 0) $moresent = floor(($userpoint - $invit_send_point) / $invit_del_point); else $moresent = 9999;
		$moresent = (($timestamp - $last_invite['datetime']) < ($bmfopt['invite_past']*86400)) ? 0 : $moresent;

        if ($moresent > 0){
            if (!preg_match("/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,4}$/", $emailinv)) {
                $reason = $step2_error[1];
                $check = 0;
            } 
        } else {
            $reason = $r_a[10];
            $check = 0;
        }
        
        if ($check) {
            if ($bmfopt[invite_type] == 1) bmfwwz($userid, -$invit_del_point, 0, 0, 0, 0, 1);
                else bmfwwz($userid, 0, -$invit_del_point * 10, 0, 0, 0, 1);
            
            include_once("include/sendmail.inc.php");
            $sendmail = "";

            $title = $r_a[12];
            
            $atid = substr(md5(uniqid(rand(), true)), 0, 9);  // Active Code
           	eval(load_hook('int_active_invite_sql'));

            $query = "INSERT INTO {$database_up}invite (invitecode,inviter,targetmail,datetime) VALUES ('$atid','$userid','$emailinv','$timestamp')";
            $result = bmbdb_query($query);
            
            $ms = str_replace("{myname}", $username, $reg_invite);
            $ms = str_replace("{user}", $friendname, $ms);
            $ms = str_replace("{atid}", $atid, $ms);
        
            $frommail = $admin_email;
        
            @BMMailer($emailinv, $title, nl2br($ms), '', '', $bbs_title, $admin_email);
            
            msg_box($r_a[0], $r_a[13] . $atid);

        } else {
            msg_box($r_a[0], $reason);
        }
    }
} elseif ($step == "s") {
	eval(load_hook('int_active_sendatid'));
	if($send_pass == 2) {
		
        include_once("include/sendmail.inc.php");
        $sendmail = "";

        $title = $username . " $reglang[30] $bbs_title";
        
        $atid=rand(100000000,999999999); // Active Code
        
    	$query = "UPDATE {$database_up}userlist SET activestatus='$atid' WHERE userid='$userid'";
        $result = bmbdb_query($query);
        
        $ms = str_replace("{user}", $username, $reg_active);
        $ms = str_replace("{usrid}", $userid, $ms);
        $ms = str_replace("{atid}", $atid, $ms);
    
        $frommail = $admin_email;
    
        @BMMailer($userddata['mailadd'], $title, nl2br($ms), '', '', $bbs_title, $admin_email);

        msg_box($r_a[0], $r_a[4]);
	} else {
		msg_box($r_a[0], $r_a[5]);
	}

} else {
	if ($activestatus) { 
	    msg_box($r_a[0], $r_a[3]);
	} else {
		if ($bmfopt['inviteallow']) $inviteallow = explode(",", $bmfopt['inviteallow']);
		if (@in_array($logonutnum, $inviteallow)) {
            if ($bmfopt[invite_type] == 1) {
                $msg = $r_a[18];
                $userpoint = $usermoney;
            } else $msg = $r_a[7];
            
            $last_invite = bmbdb_fetch_array(bmbdb_query("SELECT datetime FROM {$database_up}invite WHERE inviter ='$userid' ORDER BY `datetime` DESC LIMIT 1"));
            $last_date = $last_invite['datetime'] ? getfulldate($last_invite['datetime']) : $r_a[22];
            
			if ($invit_del_point != 0) $moresent = floor(($userpoint - $invit_send_point) / $invit_del_point); else $moresent = 9999;
            if ((($userpoint - $invit_del_point) >= $invit_send_point) && (($timestamp - $last_invite['datetime']) >= ($bmfopt['invite_past']*86400))) {
            	$types = 1;
                $msg = str_replace("{sent}", $moresent, $msg);
                $msg = str_replace("{last_date}", $last_date, $msg);
            } else {
            	$types = 2;
                $msg = str_replace("{last_date}", $last_date, $msg);
                $msg = str_replace("{sent}", "0", $msg);
            }
            
            $query = "SELECT * FROM {$database_up}invite WHERE inviter ='$userid' ORDER BY `datetime` DESC ";
            $result = bmbdb_query($query);
            while (false !== ($rows = bmbdb_fetch_array($result))) {
               	$rows['c_status'] = ($rows['accepted'] == 1) ? $r_a[21].$rows['newmember'] : $r_a[20];
               	$rows['fulldate'] = getfulldate($rows['datetime']);
                $bmforumlist[] = $rows;
            }
			eval(load_hook('int_active_invite_page'));
            
            $lang_zone = array("r_a"=>$r_a, "gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
			$template_name['active'] = newtemplate("active", $temfilename, $styleidcode, $lang_zone);
			require($template_name['active']);
		} else {
			msg_box($r_a[0], $r_a[6]);
		}
	}
}