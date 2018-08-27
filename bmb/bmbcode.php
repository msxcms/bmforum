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
require("lang/$language/faq.php");
require("include/template.php");
$add_title = " &gt; $faq_l[0]";
require("header.php");

$lang_zone = array("faq_l"=>$faq_l, "bm_skin"=>$bm_skin, "otherimages"=>$otherimages);
$template_name = newtemplate("faq_bmbcode", $temfilename, $styleidcode, $lang_zone);

if (empty($page)) $page = 1;
navi_bar('', "<a href='faq.php'>$faq_l[0]</a> &gt;&gt; $faq_l[40]");

@include_once("datafile/cache/epsiplist.php");
if (count($simlist) >= 1) {
	foreach($simlist as $emotcode=>$emotname)
	{
		$emots.= "<div style='float:left;width:25%;text-align:center;'>$faq_l[76] <u>$emotcode</u> $faq_l[77] <img src=\"images/face/emotpacks/$emotname\" border='0' alt='' /></div>";
	}
}

if (@include_once('datafile/cache/bmbcode.php')) {
	if ($tcode_count > 0) {
		foreach ($tcode_item as $key=>$value) {
			if ($value['enable'] == 1) {
				$value['refrom'] = base64_decode($value['refrom']);
				$value['reto'] = base64_decode($value['reto']);
				$tcode_item[$key]['eg'] = preg_replace($value['refrom'], $value['reto'], $value['example']);
			} else unset($tcode_item[$key]);
		}
	}
}

eval(load_hook('int_bmbcode_help'));
require($template_name);

require("footer.php");
exit;

?>
