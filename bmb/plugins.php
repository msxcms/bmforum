<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
define("INBMFORUM", 1);

error_reporting(E_ALL ^ E_NOTICE); 
$pluginfile = "plugins/" . basename($_GET['p'] . ".php");
if (@file_exists($pluginfile) && basename($_GET['p']) != "") {
    if (!$_GET['admin']) {
        include_once("datafile/config.php");
        include_once("getskin.php");
        include_once($pluginfile);
    }
    else {
        include_once("include/adminglobal.php");
        include_once("datafile/config.php");
        include_once($pluginfile);
    }
}
else {
    echo "This plugin is not installed.";
}
?>