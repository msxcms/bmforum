<?php
/* Base Lib */
include('datafile/config.php');
include('getskin.php');
/* Don't change the code below */
$dh = opendir("datafile/style");
while (false !== ($stylename = readdir($dh))) {
	if (($stylename != ".") && ($stylename != "..") && strpos($stylename, ".bs5") && $stylename != "bsd01.bs5") {
		$stylename = "datafile/style/".$stylename;
		include($stylename);

		$base_css = base_css($skinrealname, $styleidcode); //load base lib

		/* Get Data */
		if(strstr($tile_back, "/") || strstr($tile_back, "\/")){
			$base_css = str_replace("{tile_back}", "background-image: url('$tile_back');", $base_css);
		} else {
		    $base_css = str_replace("{tile_back}", "background-color: ".check_color_vaild($tile_back).";", $base_css);
		}
		if(strstr($tablebg, "/") || strstr($tablebg, "\/")){
			$base_css = str_replace("{tablebg}", "background-image: url('$tablebg');", $base_css);
		} else {
		    $base_css = str_replace("{tablebg}", "background-color: ".check_color_vaild($tablebg).";", $base_css);
		}
		$get_var['bmbnewstyle']['a'] = explode("bmbnewstyle", $cssinfo);
		$vars['bmbnewstyle'] = explode("}", $get_var['bmbnewstyle']['a'][1]);

		$array_css['old'] = array("{plugyescolor}", "{backgroundcolor}", "{titlefontcolor}", "{bordercolor}", "{titlecolor}", 
			"{list_color2}",
			"{list_color1}",
			"{article_color1}",
			"{article_color2}",
			"{jiazhongcolor}",
			"{forumcolortwo}",
			"{forumcolorone}",
			"{subcolor}",
			"{bmbnewstyle}",
			"{cautioncolor}",
			"{categoryfontcolor}",
			"{forumnamecolor}",
			"{forumdescolor}",
			"{tablewidth}"

			 );
		$array_css['new'] = array(check_color_vaild($plugyescolor), check_color_vaild($backgroundcolor), 
			check_color_vaild($titlefontcolor), 
			check_color_vaild($bordercolor), 
			check_color_vaild($titlecolor), 
			check_color_vaild($list_color2), 
			check_color_vaild($list_color1), 
			check_color_vaild($article_color1), 
			check_color_vaild($article_color2), 
			check_color_vaild($jiazhongcolor), 
			check_color_vaild($forumcolortwo), 
			check_color_vaild($forumcolorone), 
			check_color_vaild($subcolor), 
			str_replace("_old", "",str_replace("{", "", $vars['bmbnewstyle'][0])), 
			check_color_vaild($cautioncolor), 
			check_color_vaild($categoryfontcolor), 
			check_color_vaild($forumnamecolor), 
			check_color_vaild($forumdescolor), 
			$tablewidth,
		);
			
		$base_css = str_replace($array_css['old'], $array_css['new'], $base_css);


		$bs5file = readfromfile($stylename);
		$bs5file = str_replace("indexalign1 ", "indexalign1_old ", $bs5file);
		$bs5file = str_replace("indexalign2 ", "indexalign2_old ", $bs5file);
		$bs5file = str_replace("bmbnewstyle ", "bmbnewstyle_old ", $bs5file);
		writetofile($stylename, $bs5file);
		/* Done */
		$css_otherimages = $otherimages;
		$check_end = substr($css_otherimages, -1, 1);
		if ($check_end != "/" && $check_end != "\/") $css_otherimages.="/";
		$base_css = str_replace($css_otherimages, "", $base_css);
		
		writetofile($otherimages."/styles.css", $base_css);
		
		copy("images/bsd01/neweditor/", $otherimages."/neweditor");
		
		$headerfile = readfromfile("newtem/".$headername);
		$headerfile = str_replace("<head>", 
	"<head>
	<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"<?php echo \$otherimages;?>/neweditor/editor.css\" />
	<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"<?php echo \$otherimages;?>/styles.css\" />", $headerfile);
		writetofile("newtem/".$headername, $headerfile);
		
		echo "风格 $skinrealname ($styleidcode) 配置文件转换完成<br/>";
	}
}
closedir($dh);
echo "请勿重复执行本程序！！";
exit;
function check_color_vaild($colorcode){
	if(strstr($colorcode, "#")) return $colorcode;
	  else return "#".$colorcode;
}
function base_css($skinrealname, $styleidcode){
$tmp=<<<EOT
/*
BMForum CSS Stylesheet. 
 - Converted from $skinrealname ($styleidcode)
*/

/*
Base/Global Var
*/

.bmforum_background{
	background-color: {backgroundcolor};
}
.bmforum_footer_background{
	background-color: {backgroundcolor};
}

.bmbnewstyle {
{bmbnewstyle}
	width: {tablewidth};
}
.bmbnewstyle_withoutwidth {
{bmbnewstyle}
}
.bmforum_base_menu{
	border-left: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
	background-color: {titlecolor};
}
.nav_bar_bg {
	border: 1px solid {bordercolor};
	width: {tablewidth};
	background-color: {list_color1};
}
.tablebg {
{tablebg}
}
.pagenumber{
	width:18px;
	text-align:center;
	{tile_back}
	width: auto;
	color: {list_color2};
}
.indexalign1 {
	/* index forum name align */
	text-align :center;
	font-weight : bold;
{tile_back}
} 
.indexalign2 {
	text-align : center;
{tile_back}
	height: 18px;
} 
.pagenumber_1{
	width:18px;
	text-align:center;
	background-color: {list_color2};
}
.pagenumber_2{
	width:18px;
	text-align:center;
	background-color: {list_color2};
}
/* rip from BS5 file */
.announcement {
	{tile_back}
	white-space: nowrap;
}
.jiazhongcolor{ /* marked text */
	color: {jiazhongcolor};
}
.list_color1{
	background-color: {list_color1};
}
.list_color2{
	background-color: {list_color2};
}
.bordercolor{
	border-color: {bordercolor};
}
.bordercolor_right{
	border-right: 1px solid {bordercolor};
}
.bordercolor_left{
	border-left: 1px solid {bordercolor};
}
.bordercolor_top{
	border-top: 1px solid {bordercolor};
}
.bordercolor_all{
	border: 1px solid {bordercolor};
}
.importtopic_border{ /* Importance Threads Tips Table */
	border: 1px solid {bordercolor};
	border-top:none;
	width: {tablewidth};
}
.bordercolor_top_right{
	border-top: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
}
.tablewidth{
	width: {tablewidth};
}
.background_color{
	{tile_back}
}
.forumcolortwo {
	background-color: {forumcolortwo};
	text-align: center;
}
.tile_back_nowidth{
	{tile_back}
}
.tile_back_title{
	color: {titlefontcolor};
	{tile_back}
	width: auto;
}
.tile_back_tablewidth{
	width: {tablewidth};
	{tile_back}
}
.forumcolortwo_noalign{
	background-color: {forumcolortwo};
}
.bordercolor_background{
	background-color: {bordercolor};
}
.row_ads{
	background-color: {list_color1};
	text-align: center;
}
.forumcoloronecolor {
	background-color: {forumcolorone};
}
.forumcoloronecoloronly {
	background-color: {forumcolorone};
	width: {tablewidth};
}
.indexsummaryalgin {
	text-align: left;
}
.tile_back_color{
	{tile_back}
	width: auto;
}
.subcolor {
	background-color: {subcolor};
}
.cautioncolor {
	color: {cautioncolor};
}
.categoryfontcolor_font{
	color: {categoryfontcolor};
	font-weight : bold;
}
.categoryfontcolor_normal{
	color: {categoryfontcolor};
}
.titlecolor{
	background-color: {titlecolor};
}
.forumcolorone_align{
	background-color: {forumcolorone};
	text-align: left;
}
.titlefontcolor{
	color:{titlefontcolor};
}
.forumcolorone_ex{
	background-color: {forumcolorone};
	text-align: center;
	height: 42px;
}
.forum_page_links{
	font-size:7pt;
	font-family:verdana;
}
.forumnamecolor{
	font-family:verdana;
	color: {forumnamecolor};
}
/* Global.php CSS */
.msg_box_categoryfontcolor{
	color: {categoryfontcolor};
}
/* Online CSS*/
.tile_back_online{
	{tile_back}
	width: auto;
}
.tile_back_online_2{
	{tile_back}
	text-align: center;
	width: auto;
}
/* Forum Page */
.tile_back_rule{
	{tile_back}
	width: auto;
}
.tile_back_readpost{
	{tile_back}
	border-right: 1px solid {bordercolor};
	text-align: center;
	height: 22px;
	width: 20%;
}
.rule_title_font{
	color: {titlefontcolor};
}
.important_topic_split{
	color: {forumdescolor};
	font-weight : bold;
}
.tablebg_important_topic {
{tablebg};
	text-align: center;
}
.forum_border_1{
	background-color: {list_color1};
	border-left: 1px solid {bordercolor};
	border-bottom: 1px solid {bordercolor};
}
.forum_border_2{
	background-color: {list_color2};
	border-left: 1px solid {bordercolor};
	border-bottom: 1px solid {bordercolor};
}
.forum_border_3{
	background-color: {list_color2};
	border-left: 1px solid {bordercolor};
	border-bottom: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
}
.thread_list_border{
	border-left: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
	border-bottom: 1px solid {bordercolor};
}
.forumdescolor{
	color:{forumdescolor};
}
.titlefontcolor_row_forum{
	color:{titlefontcolor};
}
/* Topic Page */
.article_color1{
	background-color: {article_color1};
}
.article_color2{
	background-color: {article_color2};
}
.article_color1_memberinfo{
	background-color: {article_color1};
	border-right: 1px solid {bordercolor};
}
.article_color2_memberinfo{
	background-color: {article_color2};
	border-right: 1px solid {bordercolor};
}
.article_color1_timeline{
	background-color: {article_color1};
	border-top: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
}
.article_color2_timeline{
	background-color: {article_color2};
	border-top: 1px solid {bordercolor};
	border-right: 1px solid {bordercolor};
}
.article_color1_bottombar{
	background-color: {article_color1};
	border-top: 1px solid {bordercolor};
}
.article_color2_bottombar{
	background-color: {article_color2};
	border-top: 1px solid {bordercolor};
}
.article_color1_fast_reply{
	background-color: {article_color1};
	border-left: 1px solid {bordercolor};
}
.article_color2_fast_reply{
	background-color: {article_color2};
	border-left: 1px solid {bordercolor};
}
.article_color1_reply_content{
	background-color: {article_color1};
	border: 1px solid {bordercolor};
	border-bottom:none;
	border-right:none;
}
.article_color2_reply_content{
	background-color: {article_color2};
	border: 1px solid {bordercolor};
	border-bottom:none;
	border-right:none;
}
.topic_title{
	background-color: {backgroundcolor};
	table-layout: fixed;
	word-wrap: break-word;
	border: 1px solid {bordercolor};
}
.topic_bordercolor_px{
	border: 1px solid {bordercolor};
	border-top:none;
	background-color: {backgroundcolor};
}
.author_name{
	color: {jiazhongcolor};
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
	background-color:{article_color1};
}
.toolbox_thread_2 {
	height:16px;
	width:auto;
	text-align:center;
	background-color: {article_color2};
}
.quote_dialog {
	background-color: {article_color1};
	margin: 5px;
	padding: 8px;
	border: 1px solid {bordercolor};
}
/* article page */
.article_background{
	background-color: {backgroundcolor};
	width: {tablewidth};
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
	{tile_back}
	height: 28px;
	width: 100%;
	border: 1px solid {bordercolor};
}

/* Private Messages */
.tile_back_pm{
	{tile_back}
}
.pm_list_bgcolor{
	background-color: {list_color1};
}
.tile_back_menu_pm{
	background-color: {list_color1};
	height: 10px;
}
.status_pm{
	background-color: {list_color1};
	height: 40px;
}
.status_progress_pm{
	{tile_back}
}
.status_progress_blank_pm{
	background-color: {list_color1};
}
.title_color_pm{
	color: {list_color1};
}
.pm_status_border{
	border: 1px solid {list_color1};
	width: 95%;
}
/* JavaScript Menu */
.gray {
	cursor:pointer;
}
.menuskin {
	border: 1px solid {bordercolor};
	visibility: hidden;
	font: 12px verdana;
	position: absolute; 
	background-color:#ffffff;
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
	background-color: #FFFFFF;
	margin:2px;
	padding:1px;
	border:1px solid {bordercolor};
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
	border: 1px solid {bordercolor};
	border-top:none;
}
.fast_emot_selector{
	overflow: auto;
	left:595px;
	position:absolute;
	width:auto;
	height:110px;
	z-index:1111;
	background-color: {article_color2};
	border: 1px solid {bordercolor};
	visibility:hidden;
	padding-left:5px;
	padding-top:3px;
	padding-bottom:3px;
	padding-right:5px
}
.post_options {
	position:absolute;
	width:370px;
	height:auto;
	z-index:1;
	background-color: {article_color2};
	border: 1px solid {bordercolor};
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
	width: {tablewidth};
	background-color: {bordercolor};
	border: 0;
}
.faq_table_tr{
	height: 22px;
{tile_back}
}
EOT;
return $tmp;
}