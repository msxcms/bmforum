<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
$thisprog = "oauth.php";
include_once("datafile/config.php");
include_once("getskin.php");
include_once("lang/$language/login.php");
require_once("include/common.inc.php");
include_once("include/oauthsites.php");

$gotourl = $_GET['goto'] ? $_GET['goto'] : $_SERVER['HTTP_REFERER'];
if (empty($gotourl) || basename($gotourl) == "login.php") {
	$gotourl = 'index.php';
}

$callbackURL = $script_pos.'/oauth.php?goto='.urlencode($gotourl);

//判断网站类型
if(!$_GET['type'] && $_SESSION['oauth']['bmf_oauth_type']) {
	$_GET['type'] = $_SESSION['oauth']['bmf_oauth_type'];
}

if(in_array($_GET['type'], array_keys($supportedSite))) {
	$type = $_GET['type'];
} else {
	$type = 'sina';
}
$typeId = $supportedSite[$type]['id'];
$typeChinese = $supportedSite[$type]['name'];

//导入网站 OAuth 库
require_once('include/oauth/'.$type.'.php');

$objOAuth = new oauthClass($bmfopt['oauth'][$type], $callbackURL);

if($_GET['act'] == 'login') {
	$objOAuth->oauthLogin();
} elseif($_GET['act'] == 'unbind') {
	if($login_status == 1) {
		bmbdb_query("DELETE FROM {$database_up}oauth where `uid`='$userid' and type='{$typeId}'");
		jump_page($gotourl, $typeChinese.$bindLang['unbindsucc'], "<strong>".$typeChinese.$bindLang['unbindsucc']."</strong><br /><br /><a href='$gotourl'>$tip_1</a>", 3);
	} else {
		error_page($tip_2, $programname, $programname,$bindLang['error'], "", 1);
	}
} elseif($_GET['code'] || is_array($_SESSION['oauth'])) {
	if($_GET['code']) {
		$accessToken = $objOAuth->getUniqueCode($_GET['code']);
	} else {
		$accessToken = $_SESSION['oauth'];
		$gotourl = $_SESSION['oauth']['bmf_oauth_gotourl'];
	}
	
	if($accessToken['access_token']) {
		if($login_status == 1) {
			$_SESSION['oauth'] = '';
			$accessToken['expires_in'] = $timestamp+$accessToken['expires_in'];
			bmbdb_query("DELETE FROM {$database_up}oauth where `uniqueid`='{$accessToken['uid']}' and type='{$typeId}'");
			bmbdb_query("REPLACE INTO {$database_up}oauth (`uid`, `access_token`, `uniqueid`, `expires_in`, `type`) VALUES ($userid, '{$accessToken['access_token']}', '{$accessToken['uid']}', '{$accessToken['expires_in']}', '{$typeId}')");
			jump_page($gotourl, $typeChinese.$bindLang['bindsucc'], "<strong>".$typeChinese.$bindLang['bindsucc']."</strong><br /><br /><a href='$gotourl'>$tip_1</a>", 3);
		} else {
			$oauthDbData = bmbdb_fetch_array(bmbdb_query("SELECT * FROM {$database_up}oauth WHERE `uniqueid`='{$accessToken['uid']}' AND `type`='{$typeId}'"));
			if($oauthDbData['uid']) {
				$_SESSION['oauth'] = '';
				$accessToken['expires_in'] = $timestamp+$accessToken['expires_in'];
				bmbdb_query("REPLACE INTO {$database_up}oauth (`uid`, `access_token`, `uniqueid`, `expires_in`, `type`) VALUES ('{$oauthDbData['uid']}', '{$accessToken['access_token']}', '{$accessToken['uid']}', '{$accessToken['expires_in']}', '{$typeId}')");
				$userInfo = bmbdb_fetch_array(bmbdb_query("SELECT pwd FROM {$database_up}userlist WHERE userid='{$oauthDbData['uid']}'"));
				
				$auth = makeauth($userInfo['salt'], $bmfopt['sitekey'], $userInfo['pwd']);
                bmb_setcookie("bmfUsrId", $oauthDbData['uid'], time()+86400*30);
                bmb_setcookie("bmfUsrAuth", $auth, time()+86400*30, true);
                bmb_setcookie("cookie_time_bmb", 86400*30, time()+86400*30);
       			jump_page($gotourl, $suc_2n, "<strong>$suc_3n</strong><br /><br /><a href='$gotourl'>$tip_1</a>", 3);
			} else {
				$_SESSION['oauth'] = $accessToken;
				$_SESSION['oauth']['bmf_oauth_type'] = $type;
				$_SESSION['oauth']['bmf_oauth_gotourl'] = $gotourl;
				error_page($tip_2, $programname, $programname, $bindLang['noaccount'], "", 1);
			}
		}
	} else {
		$_SESSION['oauth'] = '';
		error_page($tip_2, $programname, $programname, $bindLang['error'], "", 1);
	}
}

function http_post($url, $data, $method = 'POST') {
	$data_url = is_array($data) ? http_build_query ($data) : $data;
	$data_len = strlen ($data_url);
	
	return (file_get_contents ($url, false, stream_context_create (array ('http'=>array ('method'=>$method
			, 'header'=>"Connection: close\r\nContent-Length: $data_len\r\n"
			, 'content'=>$data_url
			)))));
}

 