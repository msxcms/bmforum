<?php
/*
 BMForum Plus! Bulletin Board Systems
 Version : Plus!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (!defined('INBMFORUM')) die("Access Denied");
require("include/template.php");
require("header.php");
include_once("datafile/usertitle.php");

navi_bar('', $gl[63], '', 'no');
$mtitle_array = "";


for($i = 0;$i < $countmtitle;$i++) {
    if (!empty($mtitle[a0]) || !empty($mgraphic[a0])) {
		$mtitle_array[]=array($mtitle['a'.$i], $mpostmark['a'.$i], $mgraphic['a'.$i]);
    } 
} 


$lang_zone = array("gl"=>$gl, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("sut", $temfilename, $styleidcode, $lang_zone);

eval(load_hook('int_sut_output'));

require($template_name);

include("footer.php");
