<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

require("datafile/pluginheader.php");
global $headername, $bmfopt;
$headername = "newtem/header/" . basename($headername);
$add_title = strip_tags($add_title);

@require($headername);

?>