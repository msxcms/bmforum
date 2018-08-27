<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

/* -----------------------------------------------------------
   Constants used inside the class.   This file is included
   by the class module and these constants an be freely
   used in scripts using MIME_mail class
 ----------------------------------------------------------- */

define('BASE64', 'base64');
define('BIT7', '7bit');
define('QP', 'quoted_printable');
define('NOSUBJECT', '(No Subject)');
define('WARNING', 'BMB MIME');
define('OCTET', 'application/octet-stream');
define('TEXT', 'text/plain');
define('HTML', 'text/html');
define('JPEG', 'image/jpg');
define('GIF', 'image/gif');
define('CRLF', "\r\n");
define('CHARSET', 'gb_2312');
define('INLINE', 'inline');
define('ATTACH', 'attachment');
define('BODY', CRLF . 'BODY' . CRLF);

?>
