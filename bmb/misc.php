<?php
/*
 BMForum Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
error_reporting(E_ALL ^ E_NOTICE); 

$nooutputarray = array("notification.php", "sendfriend.php", "report.php", "byms.php", "manage.php", "rtrash.php", "manage2.php", "manage3.php", "manage4.php"); // Don't Output Permission Pages

$incluefile = "include/" . basename($_GET['p'] . ".php");
if (@file_exists($incluefile) && basename($_GET['p']) != "") 
{
	include_once("datafile/config.php");
	if (in_array($_GET['p'], $nooutputarray)) 
	{
		define("canceltbauto", "yes");
	}
	include_once("getskin.php");
	include_once($incluefile);
} else {
    echo "This include is not existing.";
}
?>