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

$supportedSite = array(
	'sina' => array(
			'id' => 0, 
			'name' => $oauthLang['provider']['sina']
		),
	'qq' => array(
			'id' => 1,
			'name' => $oauthLang['provider']['qq']
		)
);

$supportedIdSite = array(
	0 => 'sina',
	1 => 'qq'
);

