<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
require("datafile/config.php");
require("getskin.php");
@bmb_setcookie('lastpath', "abcsad.php");
if ($fnew_skin == 1 && isset($_GET[skinname])) bmb_setcookie("bmbskin", $_GET[skinname]);
if (isset($_GET[langname])) bmb_setcookie("userlanguage", $_GET[langname]);

if ($_SERVER['HTTP_REFERER'] == "" || $_GET['goto'] == "demo") $_SERVER['HTTP_REFERER'] = "index.php";
eval(load_hook('int_chskin_select'));

?>
<meta http-equiv="Refresh" content="0; URL=<?php echo $_SERVER['HTTP_REFERER'];?>">