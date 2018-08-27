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

class oauthClass {
	private $appKey;
	private $appSecret;
	private $callbackURL;
	
	public function __construct($oauthParams, $callbackURL) {
		$this->appKey = $oauthParams['appKey'];
		$this->appSecret = $oauthParams['appSecret'];
		$this->callbackURL = $callbackURL.'&type=qq';
	}
	
	public function oauthLogin() {
		header("Location: https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id={$this->appKey}&response_type=code&redirect_uri=".urlencode($this->callbackURL));
	}
	
	public function getUniqueCode($code) {
		$response = http_post('https://graph.qq.com/oauth2.0/token', 'client_id='.$this->appKey.'&client_secret='.$this->appSecret.'&grant_type=authorization_code&redirect_uri='.urlencode($this->callbackURL).'&code='.$code);
		if (strpos($response, "callback") !== false) {
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if(isset($msg->error)) {
				$accessToken['err'] = $msg->error;
				$accessToken['errmsg'] = $msg->error_description;
			}
		} else {
			$params = array();
			parse_str($response, $params);
			$graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$params['access_token'];
			$str = http_post($graph_url, '', 'GET');
			if (strpos($str, "callback") !== false) {
				$lpos = strpos($str, "(");
				$rpos = strrpos($str, ")");
				$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
			}
			$user = json_decode($str);
			if (isset($user->error)) {
				$accessToken['err'] = $user->error;
				$accessToken['errmsg'] = $user->error_description;
				$accessToken['access_token'] = '';
			} else {
				$accessToken['access_token'] = $params['access_token'];
				$accessToken['expires_in'] = $params['expires_in'];
				$accessToken['uid'] = $user->openid;
			}
		}

		return $accessToken;
	}
}