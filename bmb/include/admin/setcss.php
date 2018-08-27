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

$thisprog = "setcss.php";

if ($useraccess != "1" || $admgroupdata[6] != "1") {
    adminlogin();
} 
if ($step == 2) {
	$bmf_css = get_css();
	
	foreach ($style_info as $key=>$value) {
		if (strstr($value, ".")){
			$style_info[$key] = "url('$value')";
		}
	}
	
	$_replace = array("{headercolor}","{innerbordercolor}"
	,"{innerborderwidth}"
	,"{catcolor}"
	,"{cattext}"
	,"{tabletext}"
	,"{altbg1}"
	,"{altbg2}"
	,"{text}"
	,"{link}"
	,"{bordercolor}"
	,"{maintablecolor}"
	,"{smfontsize}"
	,"{font}"
	,"{headertext}"
	,"");

	$_target = array(
	$style_info["headercolor"],
	$style_info["innerbordercolor"],
	$style_info["innerborderwidth"],
	$style_info["catcolor"],
	$style_info["cattext"],
	$style_info["tabletext"],
	$style_info["altbg2"],
	$style_info["altbg1"],
	$style_info["text"],
	$style_info["link"],
	$style_info["bordercolor"],
	$style_info["maintablecolor"],
	$style_info["smfontsize"],
	$style_info["font"],
	$style_info["headertext"]
	);
	
	$bmf_css = str_replace($_replace, $_target, $bmf_css);
	
	$css_info = htmlspecialchars($bmf_css);

}
echo<<<EOT

  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[192]</strong> <form name='css_form' action="admin.php?bmod=$thisprog&step=2" method="post" style="margin:0px;">
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[192]</strong>
  </td></tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[524]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[headercolor]' value="$style_info[headercolor]"></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[1104]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[headertext]' value="$style_info[headertext]"></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[525]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[innerbordercolor]' value="$style_info[innerbordercolor]"></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[526]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[innerborderwidth]' value='$style_info[innerborderwidth]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[533]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[bordercolor]' value='$style_info[bordercolor]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[527]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[catcolor]' value='$style_info[catcolor]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[528]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[cattext]' value='$style_info[cattext]'></td>
   </tr>
   	   
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[529]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[tabletext]' value='$style_info[tabletext]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[535]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[smfontsize]' value='$style_info[smfontsize]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[1103]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[font]' value='$style_info[font]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[530]</strong></td>
    <td bgcolor=#F9FAFE width='60%'>1.<input type='text' name='style_info[altbg1]' value='$style_info[altbg1]'> 2.<input type='text' name='style_info[altbg2]' value='$style_info[altbg2]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[531]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[text]' value='$style_info[text]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[532]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[link]' value='$style_info[link]'></td>
   </tr>
   <tr>
    <td bgcolor=#F9FAFE width='40%'><strong>$arr_ad_lng[534]</strong></td>
    <td bgcolor=#F9FAFE width='60%'><input type='text' name='style_info[maintablecolor]' value='$style_info[maintablecolor]'></td>
   </tr>


   <tr>
   <td bgcolor=#F9FAFE colspan='2' align='center'><input type='submit' value='$arr_ad_lng[536]'><br /><br /><br /><center>
      CSS:<br /><textarea name='CSS' rows='10' cols='70' wrap='soft'>$css_info</textarea>
</center>
</form></td>
   </tr>
EOT;
function get_css() {
	global $timestamp;
	$dates = getfulldate($timestamp);

$info=<<<EOT
/*
BMForum CSS Stylesheet.
 - Copyright(C)Bluview Technology.
 - Built on $dates
*/

/*
Base/Global Var
*/
table{
	border-top: 0px;
	border-left: 0px;
	border-bottom: 2px
}
td{
	border-right: 1px;
	border-top: 0px;
}
a {
	text-decoration:none;
	color:{text};
}
a:hover {
	text-decoration:underline;
	color:{link};
}
body{
	padding:0px;
	margin:0px;
	background: {maintablecolor};
}
.bmforum_background{
	padding-left:8px;
	padding-right:8px;
	background: {maintablecolor};
}
.bmforum_footer_background{
	background: {maintablecolor};
}
p, body, textarea, input, select, option, td{
	font-size: 9pt;
	font-family: tahoma;
	color: {tabletext};
}
textarea, input, select, option {
	font: {smfontsize} {font}; 
	border: 1px solid {innerbordercolor};
	background: {altbg1};
}
.title { 
	color:{headertext};
}
.title a {
	text-decoration:none;
	color:{headertext};
}
.title a:hover {
	text-decoration:underline;
	color:{headertext};
}
.linetable { 
	border-top:none; 
}
.bmbnewstyle { 
	/* all table's border color, bmforum set a table as a border to contain tables. */
	border: 0px solid #444444;
	width: 100%;
	background: {innerbordercolor};
}
.bmbnewstyle_withoutwidth {
	/* no width value.same below.nowidth is same*/ 
	border: 0px solid #444444;
	background: {innerbordercolor};
}
.bmforum_menu_text{
	color:{tabletext};
}
.bmforum_menu_text a{
	color:{tabletext};
}
.bmforum_menu_text a:hover{
	color:{tabletext};
}
.bmforum_base_menu{
	/* navigation menu bar table */
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
	background: {altbg1};
}
.nav_bar_bg {
	/* navigation menu bar background */
	width: 100%;
}
.btable {
	background-image: url("middle2.gif");
	border: 1px solid {headercolor};
}
.indexalign1 {
	/* index forum name align */
	text-align :center;
	font-weight : bold;
	background: {headercolor};
} 
.tableborder {
	width: 100%;
	background: {innerbordercolor}; 
	border: {bordercolor} {innerborderwidth}px solid;
}
.tableborder_topic {
	width: 100%;
	background: {innerbordercolor}; 
	border: {bordercolor} {innerborderwidth}px solid;
	margin-bottom: 3px;
}
.tableborder_post {
	width: 100%;
	background: {innerbordercolor}; 
	border: {bordercolor} 1px solid;
	margin-bottom: 3px;
}
.tableborder_withoutwidth {
	background: {innerbordercolor}; 
	border: {bordercolor} {innerborderwidth}px solid;
}
.indexalign2 {
	text-align : center;
	background: {headercolor};
	height: 18px;
} 
.tablebg {
	background: {catcolor};
}
.forumalign1 {
	/* forum page's forum name align */
	text-align : center;
} 
.pagenumber{
	/* pages navigation */
	width:18px;
	text-align:center;
	background: {headercolor};
	width: auto;
	color: {headertext};
}
.pagenumber_1{
	width:18px;
	text-align:center;
	background: {altbg1};
}
.pagenumber_2{
	width:18px;
	cursor: pointer;
	text-align:center;
	background: {altbg2};
}
.btablelow {
	background-image: url("middle2.gif");
	border-top: 1px solid {headercolor};
	border-right: 1px solid {headercolor};
	border-bottom: 1px none;
	border-left: 1px solid {headercolor};
}
/* rip from BS5 file */
.announcement { /* announcement color */
	background: {headercolor};
	white-space: nowrap;
}
.jiazhongcolor{ /* bolder color */
	color: {text};
}
.jiazhongcolor a {
	text-decoration:none;
	color:{text};
}
.jiazhongcolor a:hover {
	text-decoration:underline;
	color:{link};
}
.list_color1{
/* threads list color 1 */
	background: {altbg1};
}
.list_color2{
/* threads list color 2 */
	background: {altbg1};
}
.bordercolor{
	border-color: {innerbordercolor};
}
.bordercolor_right{
	border-right: 1px solid {innerbordercolor};
}
.bordercolor_left{
	border-left: 1px solid {innerbordercolor};
}
.bordercolor_top{
	border-top: 1px solid {innerbordercolor};
}
.bordercolor_all{
	border: 1px solid {innerbordercolor};
}
.importtopic_border{ /* Importance Threads Tips Table */
	border: 1px solid {innerbordercolor};
	border-top:none;
	width: 100%;
}
.bordercolor_top_right{
	border-top: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
}
.tablewidth{
/* all tables width */
	width: 100%;
}
.background_color{
	background: {headercolor};
}
/* tile_back is table's background */
.tile_back_nowidth{
	background: {headercolor};
}
.tile_back_title{
	color: {headertext};
	background: {headercolor};
	width: auto;
}
.tile_back_tablewidth{
	width: 100%;
	background: {headercolor};
}
.tile_back_online{
	background: {headercolor};
	width: auto;
}
.tile_back_online_2{
	background: {headercolor};
	text-align: center;
	width: auto;
}
.tile_back_rule{
	background: {headercolor};
	width: auto;
}
.tile_back_readpost{
	background: {headercolor};
	border-right: 1px solid {innerbordercolor};
	text-align: center;
	height: 22px;
	width: 200px;
}
.tile_back_pm{
	background: {headercolor};
}
.tile_back_menu_pm{
	background: {altbg1};
	height: 10px;
}
.tile_back_menu_pm_2{
	background: {altbg1};
	height: 10px;
}
.forumcolortwo {
	background: {altbg2};
	text-align: center;
}
.forumcolortwo_noalign{
/* forum color 2 */
	background: {altbg2};
}
.forumcolor_onmouseover{
/* onmouseover forumcolor  */
	background: {altbg2};
}
.bordercolor_background{
	background: {innerbordercolor};
}
.topic_split_background{
	background: {innerbordercolor};
}
.row_ads{
/* ads */
	background: {altbg1};
	text-align: center;
}
.forumcoloronecolor {
/* forum display color 1*/
	background: {altbg1};
}
.forumcoloronecoloronly {
	background: {altbg1};
	width: 100%;
}
.forumcolorone_align{
	background: {altbg1};
	text-align: left;
}
.forumcolorone_ex{
	background: {altbg1};
	text-align: center;
	height: 42px;
}
.indexsummaryalgin {
/* index latest post */
	text-align: left;
}
.tile_back_color{
/* <tr><td> background for a title */
	background: {headercolor};
	width: auto;
}
.subcolor {
/* some table's background */
	background: {headercolor};
}
.cautioncolor {
/* text on subcolor's table */
	color: {headertext};
}
.cautioncolor a {
	text-decoration:none;
	color:{headertext};
}
.cautioncolor a:hover {
	text-decoration:underline;
	color:{link};
}
.categoryfontcolor_font{
/* category link font */
	color: {headertext};
	font-weight : bold;
}
.categoryfontcolor_font a {
	text-decoration:none;
	color:{headertext};
}
.categoryfontcolor_font a:hover {
	text-decoration:underline;
	color:{headertext};
}
.categoryfontcolor_normal{
/* no text-decoration */
	color: {headertext};
}
.categoryfontcolor_normal a {
	text-decoration:none;
	color:{headertext};
}
.categoryfontcolor_normal a:hover {
	text-decoration:underline;
	color:{headertext};
}
.titlecolor{
/* some table's background */
	background: {headercolor};
}
.titlefontcolor{
	color:{cattext};
}
.titlefontcolor a{
	text-decoration:none;
	color:{cattext};
}
.titlefontcolor a:hover {
	text-decoration:underline;
	color:{cattext};
}
/* thread list's mutil-page topic */
.forum_page_links{
	font-size:7pt;
	font-family:verdana;
}
.forum_page_links a {
	text-decoration:none;
	font-size:7pt;
	font-family:verdana;
}
.forum_page_links a:hover {
	text-decoration:underline;
	font-size:7pt;
	font-family:verdana;
}
/* forum description color */
.forumdescolor{
	color:{text};
}
.titlefontcolor_row_forum{
	color:{cattext};
}
/* forum name link */
.forumnamecolor{
	font-family:verdana;
	color: {text};
}
.forumnamelink a {
	text-decoration:none;
	color:{text};
}
.forumnamelink a:hover {
	text-decoration:underline;
	color:{link};
}
/* Global.php CSS */
.msg_box_categoryfontcolor{
/* message box text color */
	color: {headertext};
}
/* Online CSS*/
.rule_title_font{
	color: {headertext};
}
.important_topic_split{
	color: {text};
	font-weight : bold;
}
.tablebg_important_topic {
	background: {altbg1};
	text-align: center;
}
/* forums.php page thread list color */
.forum_border_one_1{
	background-color:{altbg1};
	border-bottom: 0px solid {innerbordercolor};
}
.forum_border_one_2{
	background-color:{altbg2};
	border-bottom: 0px solid {innerbordercolor};
}
.forum_border_one_3{
	border-bottom: 0px solid {innerbordercolor};
}
.forum_border_1{
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
}
.forum_border_2{
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
}
.forum_border_2_onmouseover{
	background: {altbg2};
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
}
.forum_border_3{
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
}
.thread_list_border{
	border-left: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
}

/* Topic Page */
.article_color1{
	background: {altbg1};
}
.article_color2{
	background: {altbg2};
}
.article_color1_memberinfo{
	background: {altbg1};
	border-right: 1px solid {innerbordercolor};
}
.article_color2_memberinfo{
	background: {altbg2};
	border-right: 1px solid {innerbordercolor};
}
.article_color1_timeline{
	background: {altbg1};
	border-top: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
}
.article_color2_timeline{
	background: {altbg2};
	border-top: 1px solid {innerbordercolor};
	border-right: 1px solid {innerbordercolor};
}
.article_color1_bottombar{
	background: {altbg1};
	border-top: 1px solid {innerbordercolor};
}
.article_color2_bottombar{
	background: {altbg2};
	border-top: 1px solid {innerbordercolor};
}
.article_color1_fast_reply{
	background: {altbg1};
	border-left: 1px solid {innerbordercolor};
}
.article_color2_fast_reply{
	background: {altbg2};
	border-left: 1px solid {innerbordercolor};
}
.article_color1_reply_content{
	background: {altbg1};
	border: 1px solid {innerbordercolor};
	border-bottom:none;
	border-right:none;
}
.article_color2_reply_content{
	background: {altbg2};
	border: 1px solid {innerbordercolor};
	border-bottom:none;
	border-right:none;
}
.topic_title{
	background: {tabletitle};
	table-layout: fixed;
	word-wrap: break-word;
	border: 1px solid {innerbordercolor};
}
.topic_bordercolor_px{
	border: 1px solid {innerbordercolor};
	background: #FFFFFF;
}
.author_name{
	color: {text};
	font-size: 14px;
	font-weight: bold;
}
.signature_div{
	max-height: 18em;
	overflow: hidden;
}
.toolbox_thread_1 {
	height:16px;
	width:auto;
	text-align:center;
	background:{altbg1};
}
.toolbox_thread_2 {
	height:16px;
	width:auto;
	text-align:center;
	background: {altbg2};
}
.quote_dialog {
	background: {altbg1};
	margin: 5px;
	padding: 8px;
	border: 1px solid {innerbordercolor};
}
/* article page */
.article_background{
	background: {maintablecolor};
	width: 100%;
}
.article_headline{
	font-size: 14px;
	font-weight: bold;
}
.article_information{
	font-size: 14px;
}
.article_content{
	font-size: 14px;
}
.article_toolbar_row{
	background: {headercolor};
	height: 28px;
	width: 100%;
	border: 1px solid {innerbordercolor};
}

/* Private Messages */

.pm_list_bgcolor{
	background: {altbg1};
}

.status_pm{
	background: {altbg1};
	height: 40px;
}
.status_progress_pm{
	background: {headercolor};
}
.status_progress_blank_pm{
	background: {altbg1};
}
.title_color_pm{
	color: {headertext};
}
.pm_status_border{
	border: 1px solid #FFFFFF;
	width: 95%;
}
/* JavaScript Menu */
.gray {
	cursor:pointer;
}
.menuskin {
	border: 1px solid {innerbordercolor};
	visibility: hidden;
	font: 12px verdana;
	position: absolute; 
	background:#ffffff;
	background-image:url("menubg.gif");
	background-repeat : repeat-y;
}
.menuskin a {
	padding-right: 10px;
	padding-left: 25px;
	color: black;
	text-decoration: none;
}
#mouseoverstyle {
	background: #FFFFFF;
	margin:2px;
	padding:1px;
	border:1px solid {innerbordercolor};
}
#mouseoverstyle a {
	color: #444444;
}
.menuitems{
	margin:2px;
	padding:2px;
	word-break:keep-all;
}
/* other pages */
table#fastreply {
	border: 1px solid {innerbordercolor};
	border-top:none;
}
.fast_emot_selector_article{
	width:110px;
	visibility:hidden;
	height:110px;
	overflow: auto;
	padding-left:5px;
	background: {altbg2};
	border: 1px solid {innerbordercolor};
	position:absolute;
	padding-right:5px;
	padding-top:3px;
	padding-bottom:3px;
}
.post_options {
	position:absolute;
	width:370px;
	height:auto;
	z-index:1;
	background: {altbg2};
	border: 1px solid {innerbordercolor};
	visibility:hidden;
	padding-left:10px;
	padding-top:10px;
	padding-bottom:10px;
	padding-right:10px;
}
.post_emot_selector{
	width:170px;
	height:110px;
	z-index:8;
	overflow: auto;
	clip: rect(50 0 0 50);
}
.faq_table{
	width: 100%;
	background: {innerbordercolor};
	border: 0;
}
.faq_table_tr{
	height: 22px;
	background:{headercolor};
}
#redirectwrap{
	margin: 150px auto 0 auto;
	text-align: left;
	width: 500px;
	align:center;
}
.content_div{
	overflow-x: auto;
}
.border_bottom_forum {
	border-bottom: 0px solid {innerbordercolor};
}
.comment_title_dialog {
	background-color: #FFFF77;
	font-weight:bold;
	margin-top: 5px;
	margin-right: 5px;
	margin-left: 5px;
	padding: 8px;
	border: 1px solid {innerbordercolor};
}
.comment_dialog {
	background-color: {altbg1};
	margin-right: 5px;
	margin-left: 5px;
	padding: 8px;
	border-right: 1px solid {innerbordercolor};
	border-left: 1px solid {innerbordercolor};
	border-bottom: 1px solid {innerbordercolor};
}
.bmf_space_threed{
	margin-top:0px;
}
#ajax_information {
    position:absolute;
    top:25%;
    right:25%;
    height:100px;
    left:25%;
    padding:25px;
    margin:25px;
	border: 1px solid #C0481C;
	visibility: hidden;
	font: 12px verdana;
	background-color:#F5E577;
	z-index:1000;
}
.ajax_informationbox {
    position:absolute;
    top:25%;
    right:25%;
    height:150px;
    left:25%;
    padding:25px;
    margin:25px;
	border: 1px solid #4A7BAD;
	visibility: hidden;
	font: 12px verdana;
	background-color:#F0F5FB;
	z-index:500;
}
.pagenumber_2_onmouseover
{
	width:18px;
	cursor: pointer;
	text-align:center;
	background-color: {altbg1};
}
.pagenumber_2_onmouseover a {
	text-decoration:none;
	color:{link};
}
.pagenumber_2_onmouseover a:hover {
	text-decoration:none;
	color:{link};
}
.post_table_fixed{
	width:100%;
	margin-bottom:3px;
}

EOT;
return $info;
}