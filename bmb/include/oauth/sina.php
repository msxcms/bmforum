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
		$this->callbackURL = $callbackURL;
	}
	
	public function oauthLogin() {
		header("Location: https://api.weibo.com/oauth2/authorize?client_id={$this->appKey}&response_type=code&redirect_uri=".urlencode($this->callbackURL));
	}
	
	public function getUniqueCode($code) {
		$accessToken = json_decode(http_post('https://api.weibo.com/oauth2/access_token', 'client_id='.$this->appKey.'&client_secret='.$this->appSecret.'&grant_type=authorization_code&redirect_uri='.urlencode($this->callbackURL).'&code='.$code), true);
		return $accessToken;
	}
}