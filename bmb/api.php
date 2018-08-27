<?php
/*
 BMForum Bulletin Board Systems
 Version : 16:31 2007/9/5
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

require("datafile/config.php");
require("getskin.php");
require("include/encrypt.php");
include("datafile/api/list.php");
include("datafile/api/key.php");

eval(load_hook('int_api_system'));

if ($_POST['ApiName'] && $api_key_list[$_POST['ApiName']]) 
{
	$bvuApiSend = @unserialize(@bluview_decrypt($_POST['ApiParse'], $api_key_list[$_POST['ApiName']]));
	if ($bvuApiSend['Decrypt'] === true)
	{
		if ($bvuApiSend['Method'] == "Cookies")
		{
			process_cookie($bvuApiSend);
		} else {
			$basename_ApiName = basename($_POST['ApiName']);
			if (!@include("include/api/".$basename_ApiName."/".$basename_ApiName.".php"))
			{
				error_msg('500 No Api Included', $api_key_list);
			}
			if (!@include("include/api/".$basename_ApiName."/".$basename_ApiName."_config.php"))
			{
				error_msg('500 No Api Config Included', $api_key_list);
			}
		}
	}
} else {
	error_msg('400 Error Request', $api_key_list);
}


function process_cookie($bvuApiSend)
{
	global $cookie_p, $cookie_d;
	
	if ($bvuApiSend['cookies']) {
		foreach($bvuApiSend['cookies'] as $key=>$value)
		{
			setcookie($key, $value, $bvuApiSend['cookies_life_time'][$key], $cookie_p, $cookie_d);
		}
	}
	if ($bvuApiSend['session']) {
		foreach($bvuApiSend['session'] as $key=>$value)
		{
			$_SESSION[$key] = $value;
		}
	}
	eval(load_hook('int_api_cookie_processor'));

	header("Location: ".$bvuApiSend['returnToUrl']);
	exit;
}
function error_msg($status, $api_key_list)
{
	$bvuApiTo['Decrypt']=true;
	$bvuApiTo['Error']=true;
	$bvuApiTo['Status']=$status;
	$encrypt = process_data($bvuApiTo, $api_key_list);
	eval(load_hook('int_api_errmsg'));
	echo $encrypt;
	exit;
}
function process_data($toencrypt, $api_key_list)
{
	eval(load_hook('int_api_process_data'));
	return bluview_encrypt(serialize($toencrypt), $api_key_list[$_POST['ApiName']]);
}