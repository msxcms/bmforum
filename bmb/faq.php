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
$add_title = " &gt; FAQ";
require("lang/$language/faq.php");
require("header.php");
require("include/template.php");
if (empty($page)) $page = 1;

eval(load_hook('int_faq'));

if ($page == 1) {
	$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("faq_page1", $temfilename, $styleidcode, $lang_zone);

    navi_bar('', $faq_l[0], '', 'no');
    require($template_name);
} 

if ($page == 2) {
	$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("faq_page2", $temfilename, $styleidcode, $lang_zone);

    navi_bar('', '<a href="faq.php">' . $faq_l[0] . '</a> &gt;&gt; ' . $faq_l[1]);
    require($template_name);

} 

if ($page == 3) {
	$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("faq_page3", $temfilename, $styleidcode, $lang_zone);

    navi_bar('', '<a href="faq.php">' . $faq_l[0] . '</a> &gt;&gt; ' . $faq_l[10]);
    require($template_name);
} 

if ($page == 4) {
	$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("faq_page4", $temfilename, $styleidcode, $lang_zone);

    navi_bar('', '<a href="faq.php">' . $faq_l[0] . '</a> &gt;&gt; ' . $faq_l[15]);
    require($template_name);
} 

if ($page == 5) {
	$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
	$template_name = newtemplate("faq_page5", $temfilename, $styleidcode, $lang_zone);

    navi_bar('', '<a href="faq.php">' . $faq_l[0] . '</a> &gt;&gt; ' . $faq_l[19]);

    require($template_name);

} 

require ("footer.php");
exit();
