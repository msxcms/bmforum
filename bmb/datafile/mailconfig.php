<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright infomation.
 A SourceForge Project - GNU Licence project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

/* Why modify this mail config in here ? For protect your privacy 
   为什么要在这里修改发送邮件设置？为了保证您的隐私权。
   為什么要在這里修改發送郵件設置？為了保證您的隱私權。 */

$silent = 1;		// No error reporting, 1=yes, 0=no
			    	// 屏蔽邮件发送中的全部错误提示, 1=是, 0=否
			    	// 屏蔽郵件發送中的全部錯誤提示, 1=是, 0=否
			    	
$sendmethod = 1;// Sending type	0=do not send any mails
				//		1=send via PHP mail() function and UNIX sendmail
				//		2=send via BMForum SMTP/ESMTP interface
				//		3=send via PHP mail() and SMTP(only for win32, do not support ESMTP)

				// 邮件发送方式	0=不发送任何邮件
				//		1=通过 PHP 函数及 UNIX sendmail 发送(推荐此方式)
				//		2=通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)
				//		3=通过 PHP 函数 SMTP 发送 Email(仅 win32 下有效, 不支持 ESMTP)

				// 郵件發送方式	0=不發送任何郵件
				//		1=通過 PHP 函數及 UNIX sendmail 發送(推薦此方式)
				//		2=通過 SOCKET 連接 SMTP 服務器發送(支持 ESMTP 驗證)
				//		3=通過 PHP 函數 SMTP 發送 Email(僅 win32 下有效, 不支持 ESMTP)

if($sendmethod == 1) {

	// Send via PHP mail() and UNIX sendmail(no extra configuration)
	// 通过 PHP 函数及 UNIX sendmail 发信(无需配置)
	// 通過 PHP 函數及 UNIX sendmail 發信(無需配置)

} elseif($sendmethod == 2) {
	// send via BMForum ESMTP function \ 通过 BMForum ESMTP 模块发信 \ 通過 BMForum ESMTP 模塊發信
	$mailcfg['server'] = 'smtp.163.com';			// SMTP host address \ SMTP 服务器 \ SMTP 伺服器
	$mailcfg['port'] = '25';	// SMTP port \ SMTP 端口 \ SMTP 端口		
	$mailcfg['auth'] = 1;		// require authentification? 1=yes, 0=no \ 是否需要登录验证, 1=是, 0=否 \ 是否需要登錄驗證, 1=是, 0=否
	$mailcfg['from'] = 'yourname@163.com';	
/* mail from (if authentification required, do use local email address of ESMTP server) 
发信人地址 (如果需要验证,必须为本服务器地址)
發信人地址 (如果需要驗證,必須為本伺服器地址) */


	$mailcfg['auth_username'] = 'yourname';		// Mailbox username \ 邮箱用户名 \ 郵箱用戶名
	$mailcfg['auth_password'] = 'password';			// Mailbox password \ 邮箱密码 \ 郵箱密碼

} elseif($sendmethod == 3) {
// send via PHP mail() and SMTP(only for win32, do not support ESMTP)
// 通过 PHP 函数及 SMTP 服务器发信
// 通過 PHP 函數及 SMTP 服務器發信

	$mailcfg['server'] = 'localhost';		/// SMTP host address \ SMTP 服务器 \ SMTP 伺服器
	$mailcfg['port'] = '25';		// SMTP port \ SMTP 端口 \ SMTP 端口	

}

?>