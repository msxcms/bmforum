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
require("include/template.php");

$add_title = " &gt;$gl[16]";
require("header.php");

$des = "$gl[17]";
navi_bar($des, $gl[16]);

eval(load_hook('int_lmcode'));

$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("lmcode", $temfilename, $styleidcode, $lang_zone);
require($template_name);


require("footer.php");

