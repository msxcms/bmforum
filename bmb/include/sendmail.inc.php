<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright infomation.
 A SourceForge Project - GNU Licence project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (!defined('INBMFORUM')) die("Access Denied");

include("datafile/mailconfig.php");

function BMMailer($to, $subject, $message, $headers = "", $from = "", $fromTitle = "", $fromMail = "")
{
	global $bbs_title,$silent,$mailcfg,$sendmethod,$admin_email;

	if($silent) {
		error_reporting(E_ALL ^ E_NOTICE);
	}
	$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	$message = base64_encode($message);
	if($fromTitle && $fromMail) {
		$headers = ($headers ? $headers."\r\n" : "")."From: =?UTF-8?B?" . base64_encode($fromTitle) . "?=<$fromMail>";
	}
	$headers = ($headers ? $headers."\r\n" : "")."Content-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64";

	if($sendmethod == 1 && function_exists('mail')) {
		@mail($to, $subject, $message, $headers);
		return 1;

	} elseif($sendmethod == 2) {
		
		$fp = fsockopen($mailcfg['server'], $mailcfg['port'], $errno, $errstr, 30);

		$from = $mailcfg['from'];
		if($mailcfg['auth']) {
			fputs($fp, "EHLO bmmailer\r\n");
			fputs($fp, "AUTH LOGIN\r\n");
			fputs($fp, base64_encode($mailcfg['auth_username'])." \r\n");
			fputs($fp, base64_encode($mailcfg['auth_password'])." \r\n");
		} else {
			fputs($fp, "HELO bmmailer\r\n");
		}
		fputs($fp, "MAIL FROM: $from\r\n");
		foreach(explode(',', $to) as $touser) {
			$touser = trim($touser);
			if($touser) {
				fputs($fp, "RCPT TO: $touser\r\n");
			}
		}
		fputs($fp, "DATA\r\n");
		$tosend = $headers ? $headers."\r\n" : "From: $from\r\n";
		$tosend .= "To: BMForum Members\r\n";
		$tosend .= 'Subject: '.$subject."\r\n\r\n$message\r\n.\r\n"; 
		fputs($fp, $tosend);
		fputs($fp, "QUIT\r\n");
		$response = fgets($fp,10000);
		$temp = fread($fp, 10000);
		$response .= fread($fp, 10000); 
		fclose($fp);
		return 1;

	} elseif($sendmethod == 3) {

		ini_set('SMTP', $mailcfg['server']);
		ini_set('smtp_port', $mailcfg['port']);
		ini_set('sendmail_from', $from);

		foreach(explode(',', $to) as $touser) {
			$touser = trim($touser);
			if($touser) {
				@mail($touser, $subject, $message, $headers);
			}
		}
		return 1;

	}
}