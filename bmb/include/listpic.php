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

require("datafile/style.php");
@header("Content-Type: text/html; charset=utf-8");

$dir = basename($_GET[dir]);
if (empty($dir)) exit;
if ($dir == "face") {
    $dir = "images/avatars";
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="UTF-8">
<head>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $otherimages;?>/styles.css" />
<title><?php echo $gl[124];?></title>
</head>
<body>
<script type="text/javascript">
//<![CDATA[ 
function changeavarts(url){
opener.creator.sysusericon.value=url+"\n";
opener.showimage();
}
//]]>>
</script>
<?php
echo "
<table class=\"tableborder\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" border=\"0\"><tr><td>
<table class=\"bmbnewstyle\" cellspacing=\"1\" cellpadding=\"5\" align=\"center\" border=\"0\"><tr>
<td colspan='5'><span class='titlefontcolor'>$gl[126]</span></td></tr><tr class='subcolor'>";

$openavfile = @file("datafile/avatar.dat");
$count = count($openavfile) + 1;
for($i = 1;$i < $count;$i++) {
	$echoed = 0;
    $ia = $i-1;
    echo "<td align='center'><a href='javascript:changeavarts(\"$openavfile[$ia]\");'><img border='0' src=\"$dir/$openavfile[$ia]\" alt='' /><br />$openavfile[$ia]</a></td>\n";
    if ($i % 4 == 0) { echo "</tr><tr class='subcolor'>\n"; $echoed=1;}
} 
if (!$echoed) echo "</tr>\n";
echo "</table>";
?>
</td></tr></table>
</body>
</html>