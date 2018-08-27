<?php
//新风格专用风格模板
//版权所有 Bob Shen 2003
//此处风格模板文件因故不换
global $otherimages,$bm_skin;

$tplPgoBackurl = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : 'javascript:history.go(-1);';

if(defined("IN_MSGBOX") && is_array($content)) {
	$_tmp_content = "<ul>";
	foreach($content as $_sC) {
		$_tmp_content .= "<li>$_sC</li>";
	}
	$_tmp_content .= "</ul>";
	$content = $_tmp_content;
	unset($_tmp_content);
}

$pgo[0]=<<<EOT
<div class="alert alert-block alert-info fade in" id="ajax_information">
    <h4 class="alert-heading">$msg_box_title</h4>
    <p>$content</p>
EOT;

if($nobutton === false) {
$pgo[0].=<<<EOT
    <p>
      <a class="btn btn-info" href="$tplPgoBackurl">$gl[15]</a>
    </p>
EOT;
}

$pgo[0].=<<<EOT
</div>
EOT;

$pgo[1]=<<<EOT
<div id="redirectwrap">
	<table class="tableborder_withoutwidth" cellspacing="1" cellpadding="4" style="width: 100%;" border="0">
		<tr class="forumcolortwo_noalign">
			<td class="tile_back_nowidth">
			<span class="title">$title</span></td>
		</tr>
		<tr class="forumcolortwo_noalign">
			<td><br />
			$content</td>
		</tr>
		<tr align="left" class="forumcolortwo_noalign">
			<td class="tile_back_nowidth">&nbsp;</td>
		</tr>
	</table>
</div>

EOT;
  
$pgo[2]=<<<EOT
<script language="JavaScript" type="text/javascript">
//<![CDATA[ 
function toggleHidden(h,f)
{
  if(f==1)
  {
    h.nextSibling.style.display='inline';
    //h.style.height=h.nextSibling.scrollHeight;
  }
  else
  {
    h.nextSibling.style.display='none';
    h.style.height='auto';
  }
}
//]]>>
</script>
EOT;

$pgo[3]=<<<EOT
<div id="redirectwrap">
	<table class="tableborder_withoutwidth" cellspacing="1" cellpadding="4" style="width:100%;" border="0">
		<tr class="forumcolortwo">
			<td><br />
			$content<br /><br /></td>
		</tr>
	</table>
</div>
EOT;

$pgo[4]=<<<EOT

<ul class="breadcrumb" style="height:18px;">
  <li style='width:auto;float:left;overflow:hidden;'>
	<a href="./"><i class='icon-home'></i></a>
EOT;

$pgo['breadcrumbDivider']=<<<EOT
 <span class="divider">/</span> 
EOT;

$pgo[5]=<<<EOT
  </li>
  <li style='width:auto;text-align:right;float:right;overflow:hidden;'>
EOT;

$pgo[6]=<<<EOT
  </li>
</ul>
EOT;


// Redirect Page Head
$pgo[7]=<<<EOT
<html><head>
<link rel="stylesheet" type="text/css" media="screen" href="$otherimages/styles.css" />
<title>$bbs_title $add_title - powered by BMForum.com</title>
<meta http-equiv="refresh" content='1; url=$URL'></head>
<body>
<style type="text/css">
<!--
body{ 
	text-align: center; 
}
-->
</style></body>
EOT;
