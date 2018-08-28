<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
if (@include_once('datafile/cache/bmbcode.php')) $loaded_bmbcode = 1; else $loaded_bmbcode = 0;

function phpcode($matches)
{
    global $gl, $qbgcolor, $html_codeinfo, $somepostinfo;
    $code = $matches[1];
    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") $code = stripslashes($code);
    $code_line = explode("<br />", $code);
    $count = count($code_line);
    $code = "<br /><div class='quote_dialog'><strong>$gl[354]</strong><br /><ol>";
    for ($i = 0; $i < $count; $i++){
    	if ($i == 0 && !$code_line[$i]) continue;
    	if ($i == ($count -1) && !$code_line[$i]) continue;
    	$code_line[$i] = str_replace("[", "&#91;", $code_line[$i]);
    	$code_line[$i] = str_replace("]", "&#93;", $code_line[$i]);
        $code .= "<li>".$code_line[$i]."</li>\n";
    }
    $code .= "</ol></div><br />";
	eval(load_hook('int_bmbcodes_phpcode'));
    return $code;
} 

function post($matches)
{
    global $username, $html_codeinfo, $somepostinfo, $DISABLEDAJAX,  $bcode_post, $userid, $replyerlist, $forumid, $username, $login_status, $author, $myusertype, $check_sup, $forum_admin1, $articlelist, $gl, $qbgcolor, $id_unique, $otherimages;
    $code = $matches[1];
    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") $code = stripslashes($code);
    if ($bcode_post['reply']) {
        if ($login_status == 1 && ($username == $author || $myusertype[22] == "1" || $myusertype[21] == "1" || $check_sup == 1 || ($forum_admin1 && in_array($username, $forum_admin1)) || ($replyerlist && in_array($userid, $replyerlist)))) $code1 = "<div class='quote_dialog'>$gl[355]<br/><br/>" . $code . "</div>";
        else {
        	$code1 = "<br /><br /><div class='quote_dialog'>$gl[356]</div><br /><br />";
        	$DISABLEDAJAX = 1;
        }
        $res = $code1;
    } else {
        $res = $code;
    } 
	eval(load_hook('int_bmbcodes_post'));
	return $res;
} 

function htmlcode($code)
{
    global $gl, $somepostinfo, $html_codeinfo;
    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") $code = stripslashes($code);
    $code_temp = explode("<br />", $code);
    $code = str_replace("<br />", "\r\n", $code);
    $code = str_replace("&nbsp;", " ", $code);
    $count_code = count($code_temp) + 1;
    $code = "<div class='quote_dialog'>
<script type='text/javascript'>
//<![CDATA[ 
function runEx(){
var winEx = window.open('', '', 'resizable,scrollbars'); 
winEx.document.open('text/html', 'replace');
winEx.document.write(unescape(event.srcElement.parentElement.children[2].value));
}
//]]>>
</script>$gl[357]<br />
<textares style='width:90%;' name='textfield' rows='$count_code'>$code</textares>
$gl[358]
</div>";

	eval(load_hook('int_bmbcodes_htmlcode'));
    return $code;
} 

function hiden($matches)
{
    global $html_codeinfo, $somepostinfo, $userbym, $bcode_post, $fourmid, $username, $qbgcolor, $id_unique, $myusertype, $login_status, $author, $check_sup, $forum_admin1, $otherimages, $gl;
    $code = $matches[1];
    $code3 = $matches[2];
    if ($bcode_post['jifen']) {
	    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") {
	    	$code = stripslashes($code);
	    	$code3 = stripslashes($code3);
	    }
        $code = trim($code); 
        // $code=intval($code);
        if (preg_match("/^[0-9]{1,}$/", $code)) {
            if ($login_status == 1) {
                $codeww = floor($userbym / 10);
                if ($codeww >= $code || $myusertype[21] == "1" || $myusertype[22] == "1" || $username == $author || ($forum_admin1 && in_array($username, $forum_admin1))) $code4 = "<br /><br /><div class='quote_dialog'>[ {$gl[361]} {$code} {$gl[362]} ]<br />{$codeww1}<br />$code3</div>";
                else $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[363] {$codeww1} {$code} $gl[365]</span></div><br /><br />";
            } else {
                $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[366]{$codeww1}</span></div><br />";
            } 
        } else {
            $code4 = "<br /><br /><div class='quote_dialog'>[{$gl[367]}]<br />$code3</div><br /><br />";
        } 
        $res = $code4;
    } else {
        $res = $code3;
    } 
	eval(load_hook('int_bmbcodes_hiden'));
	return $res;
} 
function hpost($code, $code3)
{
    global $postamount, $html_codeinfo, $somepostinfo, $bcode_post, $fourmid, $username, $qbgcolor, $id_unique, $myusertype, $login_status, $author, $check_sup, $forum_admin1, $otherimages, $gl;
    if ($bcode_post['hpost']) {
   	    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") {
   	    	$code = stripslashes($code);
   	    	$code3 = stripslashes($code3);
   	    }
        $code = trim($code); 
        // $code=intval($code);
        if (preg_match("/^[0-9]{1,}$/", $code)) {
            if ($login_status == 1) {
                $codeww = $postamount;
                if ($codeww >= $code || $myusertype[21] == "1" || $myusertype[22] == "1" || $username == $author || ($forum_admin1 && in_array($username, $forum_admin1))) $code4 = "<br /><br /><div class='quote_dialog'>[ {$gl[361]} {$code} {$gl[456]} ]<br />{$codeww1}<br />$code3</div><br /><br />";
                else $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[363] {$codeww1} {$code} $gl[458]</span></div><br /><br />";
            } else {
                $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[366]{$codeww1}</span></div><br /><br />";
            } 
        } else {
            $code4 = "<br /><br /><div class='quote_dialog'>[{$gl[367]}]<br />$code3</div><br /><br />";
        } 
        $res = $code4;
    } else {
        $res = $code3;
    } 
	eval(load_hook('int_bmbcodes_hpost'));
	return $res;
} 
function hmoney($code, $code3)
{
    global $usermoney, $html_codeinfo, $somepostinfo, $bcode_post, $fourmid, $username, $qbgcolor, $id_unique, $myusertype, $login_status, $author, $check_sup, $forum_admin1, $otherimages, $gl;
    if ($bcode_post['hmoney']) {
   	    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") {
   	    	$code = stripslashes($code);
   	    	$code3 = stripslashes($code3);
   	    }
        $code = trim($code); 
        // $code=intval($code);
        if (preg_match("/^[0-9]{1,}$/", $code)) {
            if ($login_status == 1) {
                $codeww = $usermoney;
                if ($codeww >= $code || $myusertype[21] == "1" || $myusertype[22] == "1" || $username == $author || ($forum_admin1 && in_array($username, $forum_admin1))) $code4 = "<br /><br /><div class='quote_dialog'>[ {$gl[361]} {$code} {$gl[457]} ]<br />{$codeww1}<br />$code3</div><br /><br />";
                else $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[363] {$codeww1} {$code} $gl[459]</span></div><br /><br />";
            } else {
                $code4 = "<br /><br /><div class='quote_dialog'><span class='jiazhongcolor'>$gl[366]{$codeww1}</span></div><br /><br />";
            } 
        } else {
            $code4 = "<br /><br /><div class='quote_dialog'>[{$gl[367]}]<br />$code3</div><br /><br />";
        } 
        $res = $code4;
    } else {
        $res = $code3;
    } 
	eval(load_hook('int_bmbcodes_hmoney'));
	return $res;
} 
function bmbconvert($post, $allow = array('pic' => 1, 'flash' => 1, 'fontsize' => 1, 'bmfimg' => 0, 'bmfcodes' => 0), $type = "post", $giey = "yyy")
{
    global $qbgcolor, $simlist, $role_time, $tcode_item, $tcode_count, $loaded_bmbcode, $topattachshow, $line, $row, $userid, $page, $outall, $countemot, $includedbadwords, $badwords, $includedemot, $emotfiledata, $html_codeinfo, $somepostinfo, $bcode_sign, $bcode_post, $bmbcode_allow, $author;

    if ($type == "sign") {
        $allow = $bcode_sign;
    } else {
        $allow = $bcode_post;
    } 

    if ($giey != "yyy") {
        $allow = $giey;
    } 
	eval(load_hook('int_bmbcodes_hp_custom'));
    if ($line['other3']) {
    	$post = preg_replace_callback("/\[upload\](.+?)\[\/upload\]/is",  function ($matches) use($line) {
			return diplay_attachment($matches[1], $matches[1], $line['id'], '0');
		}, $post);
	}

	if ($allow['table'] > 0 && strstr($post, "[table]")) {
		for ($role_time = 0; $role_time < $allow['table']; $role_time++){
			$post = preg_replace_callback("/\[table\]\s*(.*?)\s*\[\/table\]/is", "bmf_table_process", $post);
			$post = preg_replace_callback("/\[table=([#0-9a-z]{1,10})\]\s*(.*?)\s*\[\/table\]/is", "bmf_table_process", $post);
		}
	}
    $post = preg_replace_callback("/\[post\](.+?)\[\/post\]/is", "post", $post);
    $post = preg_replace_callback("/\[code\](.+?)\[\/code\]/is", "phpcode", $post);
    $post = preg_replace_callback("/\[hide=(.+?)\](.+?)\[\/hide\]/is", "hiden", $post);
    if ($allow['sell']) {
    	$post = preg_replace_callback("/\[pay=(.+?)\](.+?)\[\/pay\]/is", function ($matches) use($outall) { return sellit($matches[1], $matches[2], $outall, 'sell'); }, $post);
    	$post = preg_replace_callback("/\[gift=(.+?)\](.+?)\[\/gift\]/is", function ($matches) use($outall) { return sellit($matches[1], $matches[2], $outall, 'gift'); }, $post);
    	$post = preg_replace_callback("/\[beg\](.+?)\[\/beg\]/is", function ($matches) use($outall) { return sellit($matches[1], $matches[2], $outall, 'beg'); }, $post);
    }
    $post = preg_replace_callback("/\[hpost=(.+?)\](.+?)\[\/hpost\]/is", function ($matches){ return hpost($matches[1], $matches[2]);}, $post);
    $post = preg_replace_callback("/\[hmoney=(.+?)\](.+?)\[\/hmoney\]/is", function ($matches){ return hmoney($matches[1], $matches[2]);}, $post);
    $post = preg_replace_callback("/\[html\](.+?)\[\/html\]/is", function ($matches){ return htmlcode($matches[1]);}, $post);

    if ($allow['pic']) {
        $post = preg_replace_callback("/\[img\](.*?)\[\/img\]/is", function ($matches){ return showpic($matches[1]);}, $post);
        $post = preg_replace_callback("/(\[img=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/img\])/is", function ($matches){ return showpic($matches[6],'','',$matches[2],$matches[4]);}, $post);
        $post = preg_replace_callback("/\[img=(.*?)\](.*?)\[\/img\]/is", function ($matches){ return showpic($matches[2],'',$matches[1]);}, $post);
    } 

    if ($allow['bmfcodes'] != 1) {
    	
    	$bmf_code_replace['old'] = array("<p>", "<br />", "[u]", "[/u]",
    		"[STRIKE]", "[/STRIKE]", "[b]", "[/b]", "[quote]", "[/quote]", "[i]", 
    		"[/i]", "[br]", "[list]", "[/list]", "[olist]", "[/olist]", "[*]", 
    		"[hr]", "[sup]", "[/sup]", "[sub]", "[/sub]", '[url=&quot;', '&quot;]');
    	$bmf_code_replace['new'] = array("<br /><br />", " <br />", "<u>", "</u>", 
    		"<strike>", "</strike>", "<strong>", "</strong>", "<pre>", "</pre>", "<em>", 
    		"</em>", "<br />", "<ul>", "</ul>", "<ol>", "</ol>", "<li>", 
    		"<hr width='40%' align='left' />", "<sup>", "</sup>", "<sub>", "</sub>", '[url="', '"]');


        $post = str_replace($bmf_code_replace['old'], $bmf_code_replace['new'], $post);

        $pattern = array("/\[hidden=([^\[]*)\](.*?)\[\/hidden\]/is",
            "/\[title=([^\[]*)\](.*?)\[\/title\]/is",
            "/\[font=([^\[\<:;\(\)=&#\.\+\*\/]+?)\](.*?)\[\/font\]/is",
            "/\[color=([#0-9a-z]{1,10})\](.*?)\[\/color\]/is",
            "/\[email=([^\[]*)\](.*?)\[\/email\]/is", 
            // "/\[email=\"([^\[]*)\"\](.+?)\[\/email\]/is",
            "/\[email\]([^\[]*)\[\/email\]/is",
            "/\[url=([^\[]*)\](.*?)\[\/url\]/is",
            "/\[url\]www\.([^\[]*)\[\/url\]/is",
            "/\[url\]([^\[]*)\[\/url\]/is",
            "/\[ed=([^\[]*)\](.*?)\[\/ed\]/is",
            "/\[ed\]([^\[]*)\[\/ed\]/is",
            "/\[bt=([^\[]*)\](.*?)\[\/bt\]/is",
            "/\[bt\]([^\[]*)\[\/bt\]/is",
            "/(\[fly\])(.+?)(\[\/fly\])/is",
            "/(\[move\])(.+?)(\[\/move\])/is",
            "/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is",
            "/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is",
            "/(\[shadow=)(\d+)(\,)([#0-9a-z]{1,10})(\,)(\d+?)(\])(.+?)(\[\/shadow\])/is",
            "/(\[glow=)(\d+)(\,)([#0-9a-z]{1,10})(\,)(\d+?)(\])(.+?)(\[\/glow\])/is" ,
            "/\[quoted=([^\[]*)\](.+?)\[\/quoted\]/is",
            );

        $replacement = array("<label title=\"\\1\"><a onmouseover=\"toggleHidden(this,1);\" onmouseout=\"toggleHidden(this,0);\">&raquo;</a><a style=\"display: none;\">\\2&laquo;</a></label>",
            "<label title=\"\\1\">\\2</label>",
            "<span style=\"font-family:\\1;\">\\2</span>",
            "<span style=\"color:\\1;\">\\2</span>",
            "<img align='middle' src='images/email1.gif' alt='' border='0' /><a href=\"mailto:\\1\">\\2</a>", 
            // "<img align=absmiddle src=images/email1.gif><a href=\"mailto:\\1\">\\2</a>",
            "<img align='middle' src='images/email1.gif' alt='' border='0' /><a href=\"mailto:\\1\">\\1</a>",
            "<a href=\"\\1\" target='_blank'>\\2</a>",
            "<a href=\"http://www.\\1\" target='_blank'>www.\\1</a>",
            "<a href=\"\\1\" target='_blank'>\\1</a>",
            "<img align='middle' src='images/ed.gif' alt='' /><a href=\"\\1\" target='_blank'>\\2</a>",
            "<img align='middle' src='images/ed.gif' alt='' /><a href=\"\\1\" target='_blank'>\\1</a>",
            "<img align='middle' src='images/bt.gif' alt='' /><a href=\"\\1\" target='_blank'>\\2</a>",
            "<img align='middle' src='images/bt.gif' alt='' /><a href=\"\\1\" target='_blank'>\\1</a>",
            "<marquee width='90%' behavior='alternate' scrollamount='3'>\\2</marquee>",
            "<marquee scrollamount='3'>\\2</marquee>",
            "<div style='text-align:\\1;'>\\2</div>",
            "<div style='text-align:\\1;'>\\2</div>",
            "<table width='100%' style=\"word-break: break-all;filter:shadow(color=\\4, direction=\\6 ,strength=2)\"><tr><td>\\8</td></tr></table>",
            "<table width='100%' style=\"word-break: break-all;filter:glow(color=\\4, strength=\\6)\"><tr><td>\\8</td></tr></table>",
            "<fieldset><legend>\\1</legend>\\2</fieldset>",
            );

        $post = preg_replace($pattern, $replacement, $post);

        if ($allow['iframe']) {
            $post = preg_replace("/\[iframe\]\s*(\S+?)\s*\[\/iframe\]/is", "<iframe src='\\1' frameborder='0' allowtransparency='true' scrolling='yes' width='97%' height='600'></iframe>", $post);
        } 

        if ($allow['flash']) {
            global $gl, $otherimages;
            $post = preg_replace("/(\[flash=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/flash\])/is", "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width='\\2' height='\\4'><param name='movie' value='\\6'><param name='play' value='true'><param name='loop' value='true'><param name='quality' value='high'><embed src='\\6' width='\\2' height='\\4' play='true' loop='true' quality='high'></embed></object><table cellpadding='0' cellspacing='0' border='0' width='\\2' class='$qbgcolor'><tr><td><table width='100%' cellpadding='5' cellspacing='1' border='0'><tr><td class='$qbgcolor' align='center'><a href=\"\\6\" target='_blank'><strong>$gl[160]</strong></a></table></table>", $post);
            $post = preg_replace("/(\[swf=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/swf\])/is", "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width='\\2' height='\\4'><param name='movie' value='\\6'><param name='play' value='true'><param name='loop' value='true'><param name='quality' value='high'><embed src='\\6' width='\\2' height='\\4' play='true' loop='true' quality='high'></embed></object><table cellpadding='0 'cellspacing='0' border='0' width='\\2' class='$qbgcolor'><tr><td><table width='100%' cellpadding='5' cellspacing='1' border='0'><tr><td class='$qbgcolor' align='center'><a href=\"\\6\" target='_blank'><strong>$gl[160]</strong></a></table></table>", $post);
        } 

        if ($allow['mpeg']) {
            $post = preg_replace("/\[wmv\]\s*(\S+?)\s*\[\/wmv\]/is", "<embed src='\\1' height=\"350\" width=\"500\" autostart='0' ></embed>", $post);
            $post = preg_replace("/\[mid\]\s*(\S+?)\s*\[\/mid\]/is", "<embed src='\\1' height=\"350\" width=\"500\" autostart='0' ></embed>", $post);
            $post = preg_replace("/(\[asf=)(.+?)(\,)(.+?)(\])(.+?)(\[\/asf\])/is", "
<object classid=\"clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" id=\"MediaPlayer1\" width=\"\\2\" height=\"\\4\" Standby=\"\" type=\"application/x-oleobject\">
        <param name=\"FileName\" value=\"\\6\" />
        <param name=\"AutoStart\" value=\"0\" />
        <param name=\"ShowStatusBar\" value=\"1\" />
        <embed width='\\2' height='\\4' type='application/x-mplayer2' src='\\6'></embed>
        </object>", $post);

            $post = preg_replace("/\[ra\]\s*(\S+?)\s*\[\/ra\]/is", "<object classid=\"clsid:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA\" id=\"RAOCX\" width=\"253\" height=\"60\">
  <param name=\"autostart\" value=\"false\" />
  <param name=\"src\" value=\"\\1\" />
  <param name=\"console\" value=\"\\1\" /> 
  <param name=\"controls\" value=\"StatusBar,ControlPanel\" />
  <embed src=\"\\1\" width=\"253\" console=\"\\1\" type=\"audio/x-pn-realaudio-plugin\" autostart=\"true\" height=\"60\">
  </embed></object>", $post);

            $post = preg_replace("/(\[rm=)(.+?)(\,)(.+?)(\])(.+?)(\[\/rm\])/is", "<object classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' height='\\4' id='Player' width='\\2' viewastext='viewastext'>
        <param name=\"autostart\" value=\"false\" />
        <param name=\"controls\" value=\"ImageWindow\" />
        <param name=\"console\" value=\"\\6\" />
 		<embed src=\"\\6\" type=\"audio/x-pn-realaudio-plugin\" autostart=\"false\" console=\"\\6\" controls=\"Imagewindow\" width=\"\\2\" height=\"\\4\">
      </object><br />
      <object classid='clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA' height='32' id='Player' width='\\2' viewastext='viewastext'>
        <param name=\"autostart\" value=\"false\">
        <param name=\"controls\" value=\"controlpanel\">
        <param name=\"console\" value=\"\\6\">
        <param name=\"src\" value=\"\\6\">
        <embed src=\"\\6\" type=\"audio/x-pn-realaudio-plugin\" autostart=\"false\" console=\"\\6\" controls=\"ControlPanel\" width=\"\\2\" height=\"32\"> 

      </object>", $post);
        } 

        if ($allow['fontsize']) {
	    	$bmf_code_replace['font_old'] = array("[H1]", "[/H1]", "[H2]", "[/H2]", "[H3]", "[/H3]", "[H4]", "[/H4]", "[H5]", "[/H5]", "[H6]", "[/H6]");
	    	$bmf_code_replace['font_new'] = array("<h1>", "</h1>", "<h2>", "</h2>", "<h3>", "</h3>", "<h4>", "</h4>", "<h5>", "</h5>", "<h6>", "</h6>");

	        $post = str_replace($bmf_code_replace['font_old'], $bmf_code_replace['font_new'], $post);
        	
            $post = preg_replace_callback("/\[size=([^\[\<]+?)\]/i", "makefontsize", $post);
            $post = str_replace("[/size]", "</span>", $post);
        } 

        if ($allow['iframe']) {
            $post = preg_replace("/\[iframe\]\s*(\S+?)\s*\[\/iframe\]/is", "<iframe src='\\1' frameborder='0' allowtransparency='true' scrolling='yes' width='97%' height='340'></iframe>", $post);
        } 
        
        // Custom BMB Code
        if ($loaded_bmbcode == 1) {
	        if ($tcode_count > 0) {
	        	foreach ($tcode_item as $key=>$value) {
	        		if ($value['enable'] == 1) {
		      			$value['refrom'] = base64_decode($value['refrom']);
		       			$value['reto'] = base64_decode($value['reto']);
		        		for ($st = 0;$st < $value['nestings']; $st++){
		        			$post = preg_replace($value['refrom'], $value['reto'], $post);
		        		}
	        		}
	        	}
	        }
	    }
        // -
    } 
	eval(load_hook('int_bmbcodes_custom'));
	@include_once("datafile/cache/epsiplist.php");
    if ($allow['bmfimg'] != 1 && !$somepostinfo[2]) {
    if(is_array($simlist)){
    	$count = count($simlist);
    }
    	if ($count >= 1) {
    		foreach($simlist as $emotcode=>$emotname)
    		{
    			$post = str_replace($emotcode, "<img src='images/face/emotpacks/$emotname' bmbemotion='$emotcode' />", $post);
    		}
    	}
    } 
    $post = str_replace("│", "|", $post);
    
    return $post;
} 
function badwords($post, $forbid_check = 0)
{
    global $includedbadwords, $badwordscount, $badwords, $bmfopt;
    
    if (!defined('NOBADWORD')) {
        if ($includedbadwords != 1) {
            if (file_exists("datafile/badwords.php")) {
                include("datafile/badwords.php");
                $includedbadwords = 1;
            } 
        }
        
        $badwordscount = count($badwords);
        if ($badwordscount > 0) {
        	if ($forbid_check == 1 && $bmfopt['block_keywords'] > 0) {
        		$count_kws = 0;
        		$upper_post = strtoupper($post);
	            foreach ($badwords as $key => $value)
	                $count_kws+=substr_count($upper_post, strtoupper($key));
        	} else {
	            foreach ($badwords as $key => $value)
	                $post = str_ireplace($key, $value, $post);
	        }
            @reset($badwords);
        } else {
            define("NOBADWORD", 1);
        }
	}
	eval(load_hook('int_bmbcodes_badwords'));

	if ($forbid_check == 1 && $bmfopt['block_keywords'] > 0 && $count_kws >= $bmfopt['block_keywords']) {
		return FALSE;
	}

	return $post;
}
function showpic($url, $type = '', $align = '', $width = '', $height = '', $load = 1)
{
	if ($align) $align = "align='$align'";
	
	if (is_numeric($width)) $wh_info = "width='$width'";
	if (is_numeric($height)) $wh_info .= " height='$height'";
	
    if (strtolower(substr($url, 0, 4)) != 'http' && strtolower(substr($url, 0, 10)) != 'images/act') $url = 'http' . $url;
    elseif (strtolower(substr($url, 0, 10)) == 'images/act') $url = "images/act/" . basename($url);
    if ($load == 1) $load_check = "onclick=\"if(this.width>=700) window.open('$url');\" onload='if(this.width>700) { this.width=700;  this.style.cursor=\"pointer\";}' onmousewheel=\"return bbimg(this)\" ";
    $code = "<img src='$url' {$wh_info} $align border='0' style='max-width:700px;' {$load_check} alt='' />";
	eval(load_hook('int_bmbcodes_showpic'));
    return $code;
} 
function makefontsize ($matches) {
	$size = $matches[1];
	$sizeitem = array (8, 10, 12, 14, 16, 18, 24, 36); 
	$size = ($size > 8) ? 36 : $sizeitem[$size];
	eval(load_hook('int_bmbcodes_makefontsize'));
	return "<span style=\"font-size: {$size}px;\">";
}
function bmf_table_process($matches, $editor = 0) 
{
	global $html_codeinfo, $somepostinfo;

	if(count($matches) == 2) {
		$table_content = $matches[1];
	} elseif(count($matches) == 3) {
		$table_content = $matches[2];
		$bg_color = $matches[1];
	}

    if ($html_codeinfo == "yes" && $somepostinfo[7] != "checkbox") $table_content = stripslashes($table_content);

	$table_find = array("/\[td\]\s*(.*?)\s*\[\/td\]/is", "/\[td=([#0-9a-z]{1,10})\]\s*(.*?)\s*\[\/td\]/is", "/\[tr=([#0-9a-z]{1,10})\]\s*(.*?)\s*\[\/tr\]/is", "/\[tr\]\s*(.*?)\s*\[\/tr\]/is");
	if ($editor == 1) {
		$table_replace = array("<td class='list_color1'>\\1</td>", "<td bgcolor='\\1'>\\2</td>", "<tr bgcolor='\\1'>\\2</tr>", "<tr>\\1</tr>");
	} else {
		$table_replace = array("<td class='list_color1'>\\1</td>", "<td style='background-color:\\1;'>\\2</td>", "<tr style='background-color:\\1;'>\\2</tr>", "<tr>\\1</tr>");
	}
	
	$table_content = preg_replace($table_find, $table_replace, $table_content);
	
	if ($bg_color) $bg_color = "style=\"background-color:$bg_color;\"";
	
	$back_table = "<table class=\"tableborder\" {$bg_color} cellspacing=\"1\" cellpadding=\"5\" border=\"0\">";
	$back_table .= "$table_content";
	$back_table .= "</table>";
	eval(load_hook('int_bmbcodes_table_process'));

	return $back_table;
}
function wysiwyg_convert($post)
{
    global $qbgcolor, $usertype, $role_time, $tcode_item, $tcode_count, $loaded_bmbcode, $topattachshow, $line, $row, $userid, $page, $outall, $countemot, $includedbadwords, $badwords, $includedemot, $emotfiledata, $somepostinfo, $bcode_sign, $bcode_post, $bmbcode_allow, $author;

	for ($role_time = 0; $role_time < $usertype[115]; $role_time++){
		$post = preg_replace_callback("/\[table\]\s*(.*?)\s*\[\/table\]/is", function ($matches) { return bmf_table_process($matches, 1); }, $post);
		$post = preg_replace_callback("/\[table=([#0-9a-z]{1,10})\]\s*(.*?)\s*\[\/table\]/is", function ($matches) { return bmf_table_process($matches, 1); }, $post);
	}

	$post = preg_replace_callback("/\[img\](.*?)\[\/img\]/is", function ($matches) { return showpic($matches[1],'','','','',0); }, $post);
	$post = preg_replace_callback("/(\[img=)(\S+?)(\,)(\S+?)(\])(\S+?)(\[\/img\])/is", function ($matches) { return showpic($matches[6],'','',$matches[2],$matches[4],0); }, $post);
	$post = preg_replace_callback("/\[img=(.*?)\](.*?)\[\/img\]/is", function ($matches) { return showpic($matches[2],'',$matches[1],'','',0); }, $post);
	
	$bmf_code_replace['old'] = array("<p>", "<br />", "[u]", "[/u]",
		"[STRIKE]", "[/STRIKE]", "[b]", "[/b]", "[i]", 
		"[/i]", "[br]", "[list]", "[/list]", "[quote]", "[/quote]", "[olist]", "[/olist]", "[*]", 
		"[hr]", "[sup]", "[/sup]", "[sub]", "[/sub]", '[url=&quot;', '&quot;]');
	$bmf_code_replace['new'] = array("<br /><br />", " <br />", "<u>", "</u>", 
		"<strike>", "</strike>", "<strong>", "</strong>", "<em>", 
		"</em>", "<br />", "<ul>", "</ul>", "<pre>", "</pre>", "<ol>", "</ol>", "<li>", 
		"<hr width='40%' align='left' />", "<sup>", "</sup>", "<sub>", "</sub>", '[url="', '"]');


    $post = str_replace($bmf_code_replace['old'], $bmf_code_replace['new'], $post);

    $pattern = array(
        "/\[font=([^\[]*)\](.*?)\[\/font\]/is",
        "/\[color=([#0-9a-z]{1,10})\](.*?)\[\/color\]/is",
        "/\[url=([^\[]*)\](.*?)\[\/url\]/is",
        "/\[url\]www\.([^\[]*)\[\/url\]/is",
        "/\[url\]([^\[]*)\[\/url\]/is",
        "/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is",
        "/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is",
        );

    $replacement = array(
        "<font face=\"\\1\">\\2</font>",
        "<font color=\"\\1\">\\2</font>",
        "<a href=\"\\1\" target='_blank'>\\2</a>",
        "<a href=\"http://www.\\1\" target='_blank'>www.\\1</a>",
        "<a href=\"\\1\" target='_blank'>\\1</a>",
        "<p align='\\1'>\\2</p>",
        "<p align='\\1'>\\2</p>",
        );

    $post = preg_replace($pattern, $replacement, $post);


	$bmf_code_replace['font_old'] = array("[H1]", "[/H1]", "[H2]", "[/H2]", "[H3]", "[/H3]", "[H4]", "[/H4]", "[H5]", "[/H5]", "[H6]", "[/H6]");
	$bmf_code_replace['font_new'] = array("<h1>", "</h1>", "<h2>", "</h2>", "<h3>", "</h3>", "<h4>", "</h4>", "<h5>", "</h5>", "<h6>", "</h6>");

	$post = str_replace($bmf_code_replace['font_old'], $bmf_code_replace['font_new'], $post);
	
	$post = preg_replace("/\[size=([^\[\<]+?)\]/is", "<font size='\\1'>", $post);
	$post = str_replace("[/size]", "</font>", $post);

	eval(load_hook('int_bmbcodes_custom'));
	@include_once("datafile/cache/epsiplist.php");
    if (!$somepostinfo[2]) {
    	if (count($simlist) >= 1) {
    		foreach($simlist as $emotcode=>$emotname)
    		{
    			$post = str_replace($emotcode, "<img src=\"images/face/emotpacks/$emotname\" bmbemotion=\"$emotcode\" />", $post);
    		}
    	}
    }

	eval(load_hook('int_bmbcodes_wysiwyg_convert'));

    return $post;
} 
