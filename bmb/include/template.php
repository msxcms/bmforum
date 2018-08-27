<?php
/*
 BMForum Forums Systems
 Rev. 11:54 2006-6-17
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
 
 New Template Engine for BMForum
*/
function newtemplate($files, $temfilename, $styleidcode, $lang_zone, $nest = 4, $plugins = 0)
{
    global $template, $template_filename, $template_html_content, $_tmp, $cachedstyle, $template_content,  $language, $openstylereplace;
    $template_name_php = $plugins ? "datafile/cache/themes/$styleidcode/$files-p-$temfilename-$language-$openstylereplace.php" : "datafile/cache/themes/$styleidcode/$files-$language-$openstylereplace.php";
    
    $_tmp['langZone'] = $lang_zone;
    
    if($cachedstyle) {
    	$htmfile = $plugins ? "plugins/templates/$temfilename/$files.htm" : "newtem/$temfilename/$files.htm";
    	if(@filemtime($htmfile) > @filemtime($template_name_php)) {
    		$cachedstyle = 0;
    	}
    }
    
    if (!file_exists($template_name_php) || $cachedstyle == 0) {
    	$file = $plugins ? "plugins/templates/$temfilename/$files.htm" : "newtem/$temfilename/$files.htm";
    	if (!file_exists($file)) {
    		$file = $plugins ? "plugins/templates/bsd01/$files.htm" : "newtem/bsd01/$files.htm";
    	}
    	$cache = 0;
    } else {
    	$file = $template_name_php;
    	$cache = 1;
    }
    $template = readfromfile($file);
    if ($cache == 0) {
    	if (file_exists(str_replace("{language_pic}", $language."/", $lang_zone['npost']))) 
    	{
    		$lang_zone['npost'] = str_replace("{language_pic}", $language."/", $lang_zone['npost']);
    		$lang_zone['npollicon'] = str_replace("{language_pic}", $language."/", $lang_zone['npollicon']);
    		$lang_zone['replyicon'] = str_replace("{language_pic}", $language."/", $lang_zone['replyicon']);
    	} 
    	else 
    	{
    		$lang_zone['npost'] = str_replace("{language_pic}", "", $lang_zone['npost']);
    		$lang_zone['npollicon'] = str_replace("{language_pic}", "", $lang_zone['npollicon']);
    		$lang_zone['replyicon'] = str_replace("{language_pic}", "", $lang_zone['replyicon']);
    	}
    	
		$template_html_content = $template;
    } else {
        $template_content = readfromfile($file);
    } 
    $template_filename = $file;
	eval(load_hook('int_template_newtemplate'));
    if ($cache == 0){
	    $wrtingcaches = nsubst($lang_zone, $nest);
	    writetofile($template_name_php, $wrtingcaches);
    }
    
    return $template_name_php;
} 
function set_var($tvars, $template_content)
{
	foreach($tvars as $tkey => $tvalue) {
	    if (is_array($tvalue)) {
	        foreach ($tvalue as $key => $value) {
	            $template_content = str_replace("{" . $tkey . "[" . $key . "]}", $value, $template_content);
	        } 
	    } else {
	    	$template_content = str_replace("{" . $tkey ."}", $tvalue, $template_content);
	    }
    }
	eval(load_hook('int_template_set_var'));
    return $template_content;
} 
function i_template($template){
	global $temfilename, $styleidcode, $_tmp;
	if ($template == "header") return "header.php";
	if ($template == "footer") return "footer.php";
	eval(load_hook('int_template_i_template'));
    return newtemplate($template, $temfilename, $styleidcode, $_tmp['langZone']);
}
function nsubst($lang_zone, $nest)
{
    global $template_html_content, $template_content;
    
    $value = $template_html_content;
    
    if (is_array($lang_zone)) $value = set_var($lang_zone, $value);

	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";
	
	$value = preg_replace("/([\n\r]+)\t+/s", "\\1", $value);
	$value = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $value);
	$value = str_replace("{LF}", "<?php echo \"\\n\";?>", $value);

	$value = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?php echo \\1;?>", $value);
	$value = preg_replace_callback("/$var_regexp/s", function ($matches) { return addquote('<?php echo '.$matches[1].';?>'); }, $value);
	$value = preg_replace_callback("/\<\?php echo <\?php echo $var_regexp\;\?\>\;\?\>/s", function ($matches) { return addquote('<?php echo '.$matches[1].';?>'); }, $value);


	$value = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "<?php include i_template('\\1'); ?>", $value);
	$value = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", "<?php include i_template(\\1); ?>", $value);
	$value = preg_replace_callback("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php '.$matches[1].' ?>','');}, $value);
	$value = preg_replace_callback("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php echo '.$matches[1].'; ?>','');}, $value);
	$value = preg_replace_callback("/[\n\r\t]*\{elseif\s+(.+?)\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php } elseif('.$matches[1].') { ?>','');}, $value);
	$value = preg_replace("/[\n\r\t]*\{else\}[\n\r\t]*/is", "<?php } else { ?>", $value);

	for($i = 0; $i < $nest; $i++) {
		$value = preg_replace_callback("/[\n\r\t]*\{loop\s+\<\?php echo (\S+);\?\>\s+\<\?php echo (\S+);\?\>\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php if(is_array('.$matches[1].')) { foreach('.$matches[1].' as '.$matches[2].') { ?>',''.$matches[3].'<?php } } ?>'); }, $value);
		$value = preg_replace_callback("/[\n\r\t]*\{loop\s+\<\?php echo (\S+);\?\>\s+\<\?php echo (\S+);\?\>\s+\<\?php echo (\S+);\?\>\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php if(is_array('.$matches[1].')) { foreach('.$matches[1].' as '.$matches[2].' => '.$matches[3].') { ?>',''.$matches[4].'<?php } } ?>'); }, $value);
		$value = preg_replace_callback("/[\n\r\t]*\{if\s+(.+?)\}[\n\r]*(.+?)[\n\r]*\{\/if\}[\n\r\t]*/is", function ($matches) { return stripvtags('<?php if('.$matches[1].') { ?>',''.$matches[2].'<?php } ?>'); }, $value);
	}
	$value = preg_replace("/\{$const_regexp\}/s", "<?php echo \\1;?>", $value);
	$value = preg_replace("/ \?\>[\n\r]*\<\?php /s", " ", $value);
	
	$value = "<?php if (!defined('INBMFORUM')) die(\"Access Denied\"); ?>\n".$value;

    $template_content = $returns = $value;

	eval(load_hook('int_template_nsubst'));

    return $returns;
} 
function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}
function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?php echo (\\\$.+?);\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}