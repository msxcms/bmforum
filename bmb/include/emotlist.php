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

@include("datafile/cache/emoticons.php");
@include("datafile/cache/eplist.php");

$layer = $layer ? $layer : "Layer1";
$varReturn = $varReturn ? $varReturn : 0;
//
//if ($mode != 2)
//{
//	$s = 1;
//	$cachedjs = "document.getElementById('$layer').innerHTML='";
//	for ($i = 0;$i < ($emot_every * $emot_lines);$i++) {
//		if ($cachedemot[$i]['emotpack'] == $bmfopt['default_ep']) {
//			$br = "";
//			$ifthumb = $cachedemot[$i]['thumb'] ? 'thumb' : 'emotpacks';
//			if ((floor($s / $emot_every) * $emot_every) == $s) $br = "<br />";
//			$cachedjs .= "<img onclick=\"javascript:".(($jstype == 1) ? "FTB_InsertText(\'_bmb_MainContent_textbox\',\'{$cachedemot[$i]['emotcode']}\'); return false;" : "AddText(\'{$cachedemot[$i]['emotcode']}\');")."\" src=\"images/face/{$ifthumb}/{$cachedemot[$i]['emotpack']}/{$cachedemot[$i]['emotname']}\" alt=\"\" />$br";
//			$s++;
//		}
//	}
//	$cachedjs .= "<div align=\"left\" style=\"padding-top:3px;\"><a href=\"javascript:moreemots();\">$gl[537]</a></div>';";
//	eval(load_hook('int_emotlist_mode1'));
//	echo $cachedjs;
//} else {
	@include("datafile/cache/epcount.php");
    @include("datafile/cache/eplist.php");
	$packedname = $packname ? basename($packname) : $bmfopt['default_ep'];
	$s = 1;
	$page = $page ? $page : 1;
	$count = $epcounts[$packedname];
	$perpage = $emot_every * $emot_lines;
    $selectbox_ep = '';
    foreach($enlist as $key=>$value)
    {
    	$selected_ep = ($packedname == $key)  ? 'selected="selected"' : '';
    	$selectbox_ep .= "<option value=\'$key\' $selected_ep>$value</option>";
    }
	eval(load_hook('int_emotlist_mode2_beforepage'));
    if ($count % $perpage == 0) $maxpageno = $count / $perpage;
    else $maxpageno = floor($count / $perpage) + 1;
    if ($page == "last" || $page > $maxpageno) $page = $maxpageno;
    $pagemin = min(($page-1) * $perpage , $count-1);
    $pagemax = min($pagemin + $perpage-1, $count-1) + 1;
    $lastlimit = max(0, $perpage * ($page-1));
    
    $nextpage = $page + 1;
    $previouspage = $page-1;
    $maxpagenum = $page + 1;
    $minpagenum = $page-1;

    $pageinfos = "<table id=\"emotTable\" class=\"tableborder_withoutwidth\" cellspacing=\"1\" style=\"clear:both;\" cellpadding=\"2\" border=\"0\"><tr><td class=\"pagenumber\"><strong>&nbsp;&nbsp;{$page}/$maxpageno&nbsp;&nbsp;</strong></td><td class=\"pagenumber_2\" onmouseover=\"javascript:this.className=\'pagenumber_2 pagenumber_2_onmouseover\';\" onmouseout=\"javascript:this.className=\'pagenumber_2\';\" onclick=\"javascript:turntopage(\'$packedname\',1,\'$layer\',$varReturn);\"><a href=\"javascript:turntopage(\'$packedname\',1,\'$layer\',$varReturn);\"><strong>&laquo;</strong></a></td>";
    for ($i = $minpagenum; $i <= $maxpagenum; $i++) {
        if ($i > 0 && $i <= $maxpageno) {
            if ($i == $page) {
                $pageinfos .= "<td class=\"pagenumber_1\"><strong><u>$i</u></strong></td>";
            } else {
                $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className=\'pagenumber_2 pagenumber_2_onmouseover\';\" onmouseout=\"javascript:this.className=\'pagenumber_2\';\" onclick=\"javascript:turntopage(\'$packedname\',$i,\'$layer\',$varReturn);\"><a href=\"javascript:turntopage(\'$packedname\',$i,\'$layer\',$varReturn);\">$i</a></td>";
            } 
        } 
    } 
    $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className=\'pagenumber_2 pagenumber_2_onmouseover\';\" onmouseout=\"javascript:this.className=\'pagenumber_2\';\" onclick=\"javascript:turntopage(\'$packedname\',$maxpageno,\'$layer\',$varReturn);\"><a href=\"javascript:turntopage(\'$packedname\',$maxpageno,\'$layer\',$varReturn);\"><strong>&raquo;</strong></a></td>";
    $pageinfos .= "<td class=\"pagenumber_2\" onmouseover=\"javascript:this.className=\'pagenumber_2 pagenumber_2_onmouseover\';\" onmouseout=\"javascript:this.className=\'pagenumber_2\';\"><select onchange=\"javascript:turntopage(this.value,1,\'$layer\',$varReturn);\">$selectbox_ep</select></td></tr></table>";
	eval(load_hook('int_emotlist_mode2_afterpage'));

	if($varReturn) {
		$cachedjs = "var emotionList='$pageinfos";
	} else {
		$cachedjs = "document.getElementById('$layer').innerHTML='$pageinfos";
	}
	$everyWidth = floor(100/$emot_every);
	for ($i = $lastlimit;$i < ($lastlimit+$perpage);$i++) {
		if ($bynameep[$packedname]['emotname'][$i]) {
			$br = "";
			$ifthumb = $bynameep[$packedname]['thumb'][$i] ? 'thumb' : 'emotpacks';
			$cachedjs .= "<div style=\"float:left;width:{$everyWidth}%\"><img onclick=\"javascript:".($varReturn  ? "insertEmot(\'{$bynameep[$packedname]['emotcode'][$i]}\', \'images/face/emotpacks/{$packedname}/{$bynameep[$packedname]['emotname'][$i]}\');" : "AddText(\'{$bynameep[$packedname]['emotcode'][$i]}\');")." return false;\" src=\"images/face/{$ifthumb}/{$packedname}/{$bynameep[$packedname]['emotname'][$i]}\" alt=\"\" /></div>";
			$s++;
		}
	}
	$cachedjs .= "$pageinfos';";
	if($varReturn) {
		$cachedjs .= '$("#Layer2").html(emotionList);';
	}
	eval(load_hook('int_emotlist_mode2_bopt'));
	echo $cachedjs;
//}