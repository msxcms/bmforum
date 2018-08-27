<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
function whosonline($checkstr = '', $checkVar = NULL)
{
    global $timestamp, $whois_online, $unshowit, $view_index_online, $forum_picie, $online_show, $t, $online_limit, $see_amuser, $usergroupdata, $po, $temfilename, $forumid, $online_info_show, $admin_list, $admin_name, $otherimages, $username, $id_unique;
    $onlinefile = "datafile/online.php";
    $guestno = 0;
    $guestfile = readfromfile("datafile/guest.php");
    $guestno = count($checkstr ? explode($checkstr, $guestfile) : explode("\n", $guestfile)) - ($checkstr ? 1 : 2);
    if(!$checkstr) {
    	$guestno++;
    }
    $usernoc = 0;
    $userno = 0;
    $usergroup = array();
    $online_user_real = $online_user = file($onlinefile);
    
    if ($unshowit[6] != 1) {
    	$guestfile = explode("\n", $guestfile);
    	$online_user = array_merge((array)$online_user,(array)$guestfile);
    }

    $count = count($online_user);
    if (($online_show == "" && $view_index_online == "1") || $online_show == show) {
        for ($i = 0; $i < $count; $i++) {
            $addproinfo = "";
            $online_user_info = explode("|", trim($online_user[$i]));
            if ($timestamp - $online_user_info[2] <= $online_limit) {
            	if ($checkVar['fid'] && $online_user_info[5] != $checkVar['fid']) continue;
            	if ($checkVar['tid'] && $online_user_info[9] != $checkVar['tid']) continue;
				if ($online_user_info[11] == "yes") $usernoc++;
                if ($online_user_info[11] != "yes" || ($online_user_info[11] == "yes" && $see_amuser == 1)) {
                    if (($online_show == "" && $view_index_online == "1") || $online_show == "show") {
		            	if ($unshowit[6] != 1 && ($now_guest == 1 || trim($online_user_info[10]) == "")) {
		            		if ($now_guest != 1) {
		                    	$usertype = explode("|", $usergroupdata[6]);
		            			$now_guest = 1;
		            		}
	                        $usergroup[] = array('icon' => $usertype[1], 'guest' => true, 'name' => $online_user_info[1]);
		            	} else {
		                    $usertype = explode("|", $usergroupdata[$online_user_info[10]]);
	                        $usergroup[] = array('icon' => $usertype[1], 'groupname' => $usertype[0], 'name' => $online_user_info[1], 'urlname' => urlencode($online_user_info[1]), 'anonymous' => $online_user_info[11]);
	                    }
                        
                    }
                    if ($now_guest != 1 && $online_user_info[11] != "yes") $userno++;
                } 
            } 
        } 
    } else {
        $online_user_content = implode("" ,$online_user_real);
        $userno = count($checkstr ? explode($checkstr, $online_user_content) : $online_user_real) - 1;
	    if(!$checkstr) {
	    	$userno++;
	    }
    }
    if (($online_show == "" && $view_index_online == "1") || $online_show == "show") {
    	$online_show_anti = "hide";
    } else {
    	$online_show_anti = "show";
    }
    $suma = $userno + $usernoc;
    $sum = $userno + $guestno + $usernoc;
    
    if (!$checkstr) {
		if (file_exists('datafile/zy.php')) $zyinfo = explode('|', readfromfile('datafile/zy.php'));
	    if ($zyinfo[0] <= $sum) {
	        writetofile('datafile/zy.php', $sum . "|" . $timestamp);
	        $zyinfo = explode('|', readfromfile('datafile/zy.php'));
	    }
	    $zy_info = getfulldate($zyinfo[1]);
	}
    
	$whois_online = array("zyinfo" => $zyinfo, "zy_info" => $zy_info, "sum" => $sum, "online_show_anti" => $online_show_anti, "suma" => $suma, "usernoc" => $usernoc, "guestno" => $guestno, "usergroup" => $usergroup);
	eval(load_hook('int_forums_whosonline'));

} 