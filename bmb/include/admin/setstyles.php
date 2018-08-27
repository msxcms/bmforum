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

if ($useraccess != "1" || $admgroupdata[20] != "1") {
    adminlogin();
} 

if ($action == "template") {
	if ($step == 2) {
		writetofile($sfilename, stripslashes($template_info));
		
		refresh_stylelist();
	
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[213]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
	} else {
		$f_c = readfromfile($sfilename);
		$template_read = htmlspecialchars($f_c);
		
	    print <<<EOT
	  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
	  <strong>$arr_ad_lng[320] $arr_ad_lng[211]</strong>
	  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[211]</strong>
<form action="admin.php?bmod=setstyles.php&action=template&step=2&sfilename=$sfilename" method="post" style="margin:0px;">

$table_start
 $sfilename
  $table_stop

<textarea rows="20" cols="130" name="template_info">{$template_read}</textarea></td>
$table_start
<input type="submit" value="$arr_ad_lng[66]" /> <input type="reset" value="$arr_ad_lng[178]" /></td>

$table_start
	$arr_ad_lng[1106]:
	$table_stop

EOT;
  	get_sourcecode_string($f_c, false,  true, 1, 'blue');

	    print <<<EOT
  </tr>
</from>
EOT;

	}
	exit;
}
if ($action == "edit") {
	if (!$sfilename) $sfilename = "bsd01.bs5";
	
	if ($step == 2)  {
		$stylename   = str_replace('"', "", $stylename);
		$styleidcode = str_replace('"', "", $_POST['styleid']);
		$styleheadername = "header/".basename($styleheadername);
		$stylefootername = "footer/".basename($stylefootername);
$bs5file_info=<<<EOT
<?php
\$skinrealname=		"$stylename";
\$styleidcode=		"$styleidcode";
\$cachedstyle=		$stylecachedstyle;
\$newpost=		'$stylenewpost';
\$onlyread=		'$styleonlyread';
\$nonewpost=		'$stylenonewpost';
\$npost=			'$stylenpost';
\$npollicon=		'$stylenpollicon';
\$replyicon=		'$stylenreplyicon';
\$otherimages=		'$styleotherimages';
\$logofile=		'$stylelogofile';
\$headername=		'$styleheadername';
\$temfilename=		'$styletemfilename';
\$footername=		'$stylefootername';
\$openstylereplace=	$styleopenstylereplace;
\$notext_button =	$stylenotext_button;
EOT;
	writetofile("datafile/style/".basename($sfilename), $bs5file_info);
	writetofile($styleotherimages."/styles.css", stripslashes($cssinfo));
	writetofile("newtem/".$styleheadername, stripslashes($header_info));
	writetofile("newtem/".$stylefootername, stripslashes($footer_info));
	
	refresh_stylelist();
	
    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[213]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$arr_ad_lng[179]</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;
	}
	
	include("datafile/style/".basename($sfilename));
	
	if ($openstylereplace) $checked['openstylereplace'] = "checked='checked'";	else $unchecked['openstylereplace'] = "checked='checked'";
	if ($notext_button) $checked['notext_button'] = "checked='checked'";	else $unchecked['notext_button'] = "checked='checked'";
	if ($cachedstyle) $checked['cachedstyle'] = "checked='checked'";	else $unchecked['cachedstyle'] = "checked='checked'";
	
	$css_read = htmlspecialchars(readfromfile($otherimages."/styles.css"));
	$head_read = htmlspecialchars(readfromfile("newtem/header/".basename($headername)));
	$foot_read = htmlspecialchars(readfromfile("newtem/footer/".basename($footername)));

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[211]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[211]</strong>
  </td></tr><form action="admin.php?bmod=setstyles.php&action=edit&step=2&sfilename=$sfilename" method="post">
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1082]</td>
   <td><input type="text" value="$skinrealname" name="stylename" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1083]</td>
   <td><input type="text" value="$styleidcode" name="styleid" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1084]</td>
   <td><input type="radio" value="1" name="stylecachedstyle" {$checked['cachedstyle']} />$arr_ad_lng[942] <input type="radio" value="0" name="stylecachedstyle" {$unchecked['cachedstyle']}/>$arr_ad_lng[943]</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1085]</td>
   <td><input type="text" value="$newpost" name="stylenewpost" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1099]</td>
   <td><input type="text" value="$onlyread" name="styleonlyread" size="50" /></td>
  </tr>

 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1086]</td>
   <td><input type="text" value="$nonewpost" name="stylenonewpost" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1087]</td>
   <td><input type="text" value="$npost" name="stylenpost" size="50" /></td>
  </tr>
  <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1088]</td>
   <td><input type="text" value="$replyicon" name="stylenreplyicon" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1089]</td>
   <td><input type="text" value="$npollicon" name="stylenpollicon" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1090]</td>
   <td><input type="text" value="$otherimages" name="styleotherimages" size="50" /></td>
  </tr>
  <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1091]</td>
   <td><input type="text" value="$logofile" name="stylelogofile" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1092]</td>
   <td><input type="text" value="$headername" name="styleheadername" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1093]</td>
   <td><input type="text" value="$footername" name="stylefootername" size="50" /></td>
  </tr>
  <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1094]</td>
   <td><input type="text" value="$temfilename" name="styletemfilename" size="50" /></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1095]</td>
   <td><input type="radio" value="1" name="styleopenstylereplace" {$checked['openstylereplace']} />$arr_ad_lng[942] <input type="radio" value="0" name="styleopenstylereplace" {$unchecked['openstylereplace']}/>$arr_ad_lng[943]</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td>$arr_ad_lng[1096]</td>
   <td><input type="radio" value="1" name="stylenotext_button" {$checked['notext_button']} />$arr_ad_lng[942] <input type="radio" value="0" name="stylenotext_button" {$unchecked['notext_button']}/>$arr_ad_lng[943]</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2">$arr_ad_lng[1097]</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2"><textarea rows="20" cols="90" name="cssinfo">{$css_read}</textarea></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2">Header</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2"><textarea rows="20" cols="90" name="header_info">{$head_read}</textarea></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2">Footer</td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2"><textarea rows="20" cols="90" name="footer_info">{$foot_read}</textarea></td>
  </tr>
 <tr bgcolor="#F9FAFE">
   <td colspan="2"><input type="submit" value="$arr_ad_lng[66]" /> <input type="reset" value="$arr_ad_lng[178]" /></td>
  </tr>
</from>
  </td></tr>
    </table></body></html>
EOT;

}elseif ($action != "process" && $action != "processmr") { // Start Page
    unset($skin);

    include("datafile/style.php"); // Load Default Style
    $so = file("datafile/stylelist.php"); // Load Styles List
    $sc = count($styleopen);
    for($syi = 0;$syi < $sc;$syi++) {
        $stydetail = explode("|", $so[$syi]);
        if ($stydetail[3] == $skin) {
            $skin_show = $stydetail[2];
        } 
    } 

    print <<<EOT
  <tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
  <strong>$arr_ad_lng[320] $arr_ad_lng[211]</strong>
  </td></tr>
  <tr>
  <td bgcolor=#F9FAFE valign=middle align=center colspan=2>
  <font color=#333333><strong>$arr_ad_lng[211]</strong>($arr_ad_lng[668] $skin_show)
$table_start 
	$arr_ad_lng[1098]
	$table_stop <form action="admin.php?bmod=setstyles.php" method="post" style="margin:0px;">
  <input type=hidden name="action" value="edit">
	 
EOT;
    // Select Styles
    echo "<select name='sfilename'>";
    for($syi = 0;$syi < $sc;$syi++) {
        $stydetail = explode("|", $so[$syi]);
        echo "<option value=\"$stydetail[3]\">$stydetail[2]</option>";
    } 

    print <<<EOT
	</select>
	<input type=submit value="$arr_ad_lng[66]">
</form>
$table_start
		$arr_ad_lng[669]
	$table_stop	
	<form action="admin.php?bmod=setstyles.php" method="post" style="margin:0px;">
  <input type=hidden name="action" value="processmr">
	 
EOT;
    // Select Styles
    echo "<select name='sfilename'>";
    for($syi = 0;$syi < $sc;$syi++) {
        $stydetail = explode("|", $so[$syi]);
        echo "<option value=\"$stydetail[3]\">$stydetail[2]</option>";
    } 

    print <<<EOT
	</select>
	<input type=submit value="$arr_ad_lng[66]">
</form>
$table_start
	$arr_ad_lng[1105] 
		  <form action="admin.php?bmod=setstyles.php" method="post" style="margin:0px;">
	$table_stop	  <input type=hidden name="action" value="template">
EOT;

    echo "<select name='sfilename'>";
    
	echo get_template_list("newtem");
	echo get_template_list("plugins/templates");

	

    print <<<EOT
	</select>
	<input type=submit value="$arr_ad_lng[66]">
</form>
$table_start
	$arr_ad_lng[670] $table_stop
		  <form action="admin.php?bmod=setstyles.php" method="post" style="margin:0px;">
  <input type=hidden name="action" value="process">
	<input type=hidden name="skins" value="$skin"> 
EOT;

    echo "<select name='sfilename'>";
    
    for($syi = 0;$syi < $sc;$syi++) {
        $stydetail = explode("|", $so[$syi]);
        echo "<option value=\"$stydetail[3]\">$stydetail[2]</option>";
    } 

    print <<<EOT
	</select>
	<input type="button" onclick="if(confirm('$arr_ad_lng[391]', '$arr_ad_lng[391]')){ document.getElementById('submithere').click(); }" value="$arr_ad_lng[66]">
	<div style="display:none"><input type="submit" value="submit" name="submithere" id="submithere" /></div>
</form>

$table_start
$arr_ad_lng[888]
$table_stop
<a href=admin.php?bmod=foruminit.php&action=13>$arr_ad_lng[887]</a>
<br /><a href=admin.php?bmod=setcss.php>$arr_ad_lng[192]</a>
<br /><a href="http://www.bmforum.com/help/style.htm">$arr_ad_lng[889]</a>
	</td>
  </tr>
  </td></tr>
    </table></body></html>
EOT;
} else {
    if (!empty($action) && empty($sfilename)) {
        $infotips = $arr_ad_lng[671]; // ERROR INCLUDE
    } 

    if (!$infotips && $action == "process") {
        if ($sfilename == $skins) {
            $infotips = $arr_ad_lng[672]; // Delete the default style
        } 
        if (!$infotips && file_exists("datafile/style/" . $sfilename)) {
            unlink("datafile/style/" . $sfilename); // Delete style
            $addinformation = "<a href=foruminit.php?action=13>$arr_ad_lng[673]</a>";
        } elseif (!$infotips) {
            $infotips = $arr_ad_lng[674];
        } 
    } 
    if (!$infotips && $action == "processmr") {
        // Set the skin as default
        $style = "<?php
		if (empty(\$skin)) \$skin='$sfilename';
		if (file_exists(\"datafile/style/\".basename(\$skin))) include(\"datafile/style/\".basename(\$skin));
		else include(\"datafile/style/$sfilename\");
		";
        writetofile("datafile/style.php", $style);
    } 
    
    refresh_stylelist();

	if (!$infotips) $infotips = $arr_ad_lng[179];

    print <<<EOT
  	<tr><td bgcolor=#14568A colspan=2><font color=#F9FAFE>
		<strong>$arr_ad_lng[320] $arr_ad_lng[213]</strong>
		</td></tr>
		<tr>
		<td bgcolor=#F9FAFE valign=middle colspan=2>
		<center><strong>$infotips</strong></center><br /><br />&nbsp;&gt;&gt; <a href=admin.php?bmod=$thisprog>$arr_ad_lng[76]</a>
		</td></tr></table></body></html>
EOT;
    exit;

	
}
function get_template_list($dir){
    $openstyle = get_dir_content($dir);
    if (is_array($openstyle)){
    	foreach ($openstyle as $value) {
    		$openstyle_s = get_dir_content($dir."/".$value);
    		if (is_array($openstyle_s)){
    			foreach ($openstyle_s as $svalue) {
    				$ops.= "<option value=\"$dir/$value/$svalue\">$dir - $value - $svalue</option>\n";
    			}
    		}
    	}
    }
    return $ops;
}
function get_dir_content($dir){
	if (!file_exists($dir)) return;
	$path = opendir($dir);
	while (false !== ($file = readdir($path))) {
		if($file!="." && $file!=".." && $file!="header" && $file!="footer") {
			$dir_array[] = $file;
		}
	}
	return $dir_array;
}
function refresh_stylelist(){
    $dh = opendir("datafile/style");
    while (false !== ($stylelist = readdir($dh))) {
        if (($stylelist != ".") && ($stylelist != "..") && strpos($stylelist, ".bs5")) {
            include("datafile/style/$stylelist");
            $dir = "datafile/cache/themes/" . $styleidcode;
            $dirhandle = @opendir($dir);
            while (false !== ($file_name = @readdir($dirhandle))) {
                if ($file_name != "." && $file_name != "..") {
                    @unlink($dir . "/" . $file_name);
                } 
            } 
            @mkdir($dir, 0777);
            $skinsshow .= "<?php //|" . $styleidcode . "|" . $skinrealname . "|" . $stylelist . "|\n";
        } 
    } 
    closedir($dh);
    writetofile("datafile/stylelist.php", $skinsshow);
}
function get_sourcecode_string($str, $return = false, $counting = true, $first_line_num = '1', $font_color = '#666'){
   $str = highlight_string($str, TRUE);
   $replace = array(
       '<font' => '<span',
       'color="' => 'style="color: ',
       '</font>' => '</span>',
       '<code>' => '',
       '</code>' => '',
       '<span style="color: #FF8000">' =>
           '<span style="color: '.$font_color.'">'
       );
   foreach ($replace as $html => $xhtml){
       $str = str_replace($html, $xhtml, $str);
   }
   // delete the first <span style="color:#000000;"> and the corresponding </span>
   $str = substr($str, 30, -9);
               
   $arr_html      = explode('<br />', $str);
   $total_lines  = count($arr_html);    
   $out          = '';
   $line_counter  = 0;
   $last_line_num = $first_line_num + $total_lines;
   
   foreach ($arr_html as $line){
       $line = str_replace(chr(13), '', $line);
       $current_line = $first_line_num + $line_counter;
       if ($counting){
           $out .= '<span style="color:'.$font_color.'">'
                 . str_repeat('&nbsp;', strlen($last_line_num) - strlen($current_line))
                 . $current_line
                 . ': </span>';
       }
       $out .= $line
             . '<br />';
       $line_counter++;
   }
   $out = '<code>'.$out.'</code>';

   if ($return){return $out;}
   else {echo $out;}
}
?>
