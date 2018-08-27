<?php
if (!defined('INBMFORUM')) die("Access Denied");

function bluview_encrypt($e_string, $password)
{
	eval(load_hook('int_bluview_encrypt'));
	$password = base64_encode($password);
    $count_pwd = strlen("a".$password);
    for($i = 1;$i<$count_pwd;$i++) {
    	$pwd+=ord($password{$i});
    }
    
	$e_string = base64_encode($e_string);
    $count = strlen("a".$e_string);
    for($i = 0;$i<$count;$i++) {
    	$asciis.=(ord($e_string{$i})+$pwd)."|";
    }
    $asciis = base64_encode($asciis);
	return $asciis;
}

function bluview_decrypt($e_string, $password)
{
	eval(load_hook('int_bluview_decrypt'));
	$password = base64_encode($password);
    $count_pwd = strlen("a".$password);
    for($i = 1;$i<$count_pwd;$i++) {
    	$pwd+=ord($password{$i});
    }
    
    $e_string = base64_decode($e_string);
    $contents = explode("|",$e_string);
    $count = count($contents);
    for ($i=0;$i<$count;$i++){
    	$infos.=chr($contents[$i]-$pwd);
    }
    $asciis = base64_decode($infos);

	return $asciis;
}

