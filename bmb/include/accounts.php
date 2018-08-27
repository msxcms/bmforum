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
include("include/bmbcodes.php");
include("include/template.php");
include("include/common.inc.php");
require("lang/$language/usercp.php");
require("lang/$language/bindaccounts.php");

$add_title = $b_a[0];

if (empty($job)) {
    $job = "list";
} 

if ($login_status == 0) {
	error_page($tip[1], $info[6], $programname, $error[1]);
} 
if ($bmfopt['noswitchuser']) {
	error_page("", $programname, $programname, $b_a[19]);
} 

if ($job == "delself") {
	if ($verify != $log_hash) die("Access Denied");
	
	if ($userddata['parusrid'])
		bmbdb_query("UPDATE {$database_up}userlist SET `parusrid`='' WHERE `userid`='{$userid}'");
	
	eval(load_hook('int_accounts_delpar'));
	
	jump_page("misc.php?p=accounts", $b_a[17],
                "<strong>$b_a[17]</strong><br />
			<br />$b_a[10] <a href='misc.php?p=accounts'>$programname</a> | <a href='index.php'>$b_a[11]</a>", 3);

} 
if ($job == "delete") {
	if ($verify != $log_hash) die("Access Denied");
	eval(load_hook('int_accounts_delete'));

	bmbdb_query("UPDATE {$database_up}userlist SET `parusrid`='' WHERE `userid`='{$t_userid}' AND `parusrid`='$userid'");
	
	jump_page("misc.php?p=accounts", $b_a[17], array('title' => $b_a[17], 'links' => array($programname => 'misc.php?p=accounts', $b_a[11] => 'index.php')), 3);
} 
if ($job == "add") {
	if ($verify != $log_hash) die("Access Denied");
	
	$authinput = strtoupper($authinput);
	
	if ($_SESSION["logintry"] > $maxlogintry-1 && isset($maxlogintry)) {
		error_page("", $programname, $programname, $error_3n);
	} 
	
    if ($loginuser && $loginpwd) {
        $loginpwd = md5(stripslashes($loginpwd));
        $check = $login_with == "id" ? checkpass("", $loginpwd, $loginuser) : checkpass($loginuser, $loginpwd);
    } 
    
    if ($user_check_id == $userid) {
    	error_page("", $programname, $programname, $b_a[18]);
    }
    
    if ($log_va && $_SESSION["checkauthnum"] != $authinput) {
    	$_SESSION["logintry"]++;
    	error_page("", $programname, $programname, $gl[440], "");
    } 
    $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
    $_SESSION[checkauthnum] = $authnum;
    
    if (!check_name_valid($loginuser)) {
        error_page($tip_2, $programname, $programname, $error_1n, "");
    } 
	eval(load_hook('int_accounts_add'));
    
    if ($check == 1) {
    	bmbdb_query("UPDATE {$database_up}userlist SET `parusrid`='$userid' WHERE `userid`='{$user_check_id}'");
		jump_page("misc.php?p=accounts", $b_a[9],
                "<strong>$b_a[9]</strong><br />
			<br />$b_a[10] <a href='misc.php?p=accounts'>$programname</a> | <a href='index.php'>$b_a[11]</a>", 3);

    } else {
    	$_SESSION["logintry"]++;
    	error_page("", $programname, $programname, $b_a[12], "");
    }
} 
if ($job == "list") {
    if ($log_va) {
        $authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
        $_SESSION[checkauthnum] = $authnum;
        $tmp23s = $gd_auth;
    } 
    
    $result = bmbdb_query("SELECT * FROM {$database_up}userlist WHERE `parusrid`='$userid'");
    while (false !== ($row = bmbdb_fetch_array($result))) {
    	$bindacc_arr[] = array("username" => $row['username'], "userid"=>$row['userid'], 'lastlogin' => get_date_chi($row['lastlogin']) . get_time($row['lastlogin']));
    } 
    
    if ($userddata['parusrid']) {
    	$target = get_user_info($userddata['parusrid'], "usrid");
    }
    
    include_once("include/oauthsites.php");
    
    $bindSites = array();
    $_Q_bindSites = bmbdb_query("SELECT * FROM {$database_up}oauth where `uid`='$userid'");
    while (false !== ($row = bmbdb_fetch_array($_Q_bindSites))) {
    	$bindSites[$supportedIdSite[$row['type']]] = $row;
    }

    $oauthProviderCounter  = 0;
    if($oauthLang['provider']) {
		foreach($oauthLang['provider'] as $providerId => $providerName) {
			if(!$bmfopt['oauth'][$providerId]['appKey']) {
				continue;
			}
			
			$oauthProviderCounter++;
		}
	}
		
	eval(load_hook('int_accounts_list'));
    include("header.php");
    navi_bar($tip[1], $info[6]);
	$lang_zone = array("oauthLang"=>$oauthLang, "mmssms"=>$mmssms, "b_a"=>$b_a, "show_form_lng"=>$show_form_lng, "navbarshow"=>$navbarshow, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	
	$currentMod['accounts'] = true;
	
	$template_name['usercp'] = newtemplate("usercp", $temfilename, $styleidcode, $lang_zone);
	$template_name['usercp_foot'] = newtemplate("usercp_foot", $temfilename, $styleidcode, $lang_zone);
	require($template_name['usercp']);
	$template_name['bindaccounts'] = newtemplate("bindaccounts", $temfilename, $styleidcode, $lang_zone);
	
	require($template_name['bindaccounts']);
	require($template_name['usercp_foot']);
	
	include("footer.php");
	exit;

} 

