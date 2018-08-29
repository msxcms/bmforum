<?php
/*
 BMForum Forum Systems
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
function process_attach($showdes, $exts, $ias, $filename, $lineid, $read_post, $showdesa, $showdesb, $showdtim, $checkattachpic, $somepostinfo, $bcode_post, $loaded)
{
	$exts = basename($exts);
	if (file_exists("include/attachext/". $exts .".php")) {
		@include("include/attachext/". $exts .".php");
	} else {
		if ($loaded != 1) $pro_results = "<a target='_blank' href='attachment.php?am=$ias&amp;filename=$filename&amp;article={$lineid}'><img border='0' src='images/attach/attach.gif' alt='' /> $read_post[26] $showdesa$showdesb $showdtim</a><br />";
	}
   	eval(load_hook('int_topic_attachprocess'));

	return $pro_results;
}
function fast_reply ()
{
	global $emot_every, $DISABLEDAJAX, $max_post_title, $usertype, $reply_post, $bgcolor, $allow_ajax_reply, $html_codeinfo, $login_status, $username, $max_upload_size, $max_daily_upload_size, $uploadfiletoday, $min_post_length, $max_post_length, $emot_lines, $max_upload_post, $allow_upload, $upload_type_available, $forum_pos;

    $html_codeinfo = $usertype[26];

    // ------ Emoticons -------
    if ($DISABLEDAJAX == 1) $allow_ajax_reply = 0;

    if ($bgcolor == "article_color1") $dbgcolor = "article_color2";
    else $dbgcolor = "article_color1";

	$reply_post = array("max_post_title" => $max_post_title, "allow_ajax_reply" => $allow_ajax_reply, "wemotinfoshow" => $wemotinfoshow, "showinfoofforum" => $showinfoofforum, "min_post_length" => $min_post_length, "max_post_length" => $max_post_length, "dbgcolor" => $dbgcolor, "htmlcodeinfo" => $html_codeinfo, "codeinfoc" => $codeinfoc, "uploadinfoshow" => $uploadinfoshow, "leftuploadnum" => $leftuploadnum, "actshows" => $actshows, "showuploads" => $showuploads, "addinfoone" => $addinfoone);
   	eval(load_hook('int_topic_reply_post'));
}
function text_watermark($contentconverted) 
{
	global $script_pos, $bbs_title;
	
	$text_wm['tmp']		=	explode("<br />", $contentconverted);
	$text_wm['count']	=	count($text_wm['tmp']);
	for ($i = 0; $i < $text_wm['count']; $i++) {
		if ($i%3==0)
		$text_wm['tmp'][$i].= "<div style='display:none;'>[url=$script_pos]".getCode()."$bbs_title".getCode()."[/url]</div>";
	}
	
	$text_wm['tmp'] = implode("<br />", $text_wm['tmp']);
   	eval(load_hook('int_topic_text_watermark'));
	
	return $text_wm['tmp'];
}
function similar_threads($tid, $ttag_ex, $is_article = 0)
{
	global $database_up, $bmfopt, $sthread_list, $usertype, $login_status, $forum_admin, $detail, $forumscount, $sxfourmrow, $username;
	
	$max_similar_tags = $bmfopt["tags_max_similar"];
	
	if(is_array($ttag_ex)) $c_tags = count($ttag_ex);
	if ($c_tags > 0 && $max_similar_tags > 0) {
		
		for ($i = 0; $i < $c_tags; $i++){
			$ttags_name .= $ttags_name ? ",'$ttag_ex[$i]'" : "'$ttag_ex[$i]'";
		}
		
		$tags_real_count = 0;
		
	    $result = bmbdb_query("SELECT * FROM {$database_up}tags WHERE tagname in($ttags_name)");
	    while (false !== ($tag_row = bmbdb_fetch_array($result))) {
	    	$this_tags = substr($tag_row['filename'], 1);
	    	$tags_detail = explode(",", $this_tags);
	    	$count_tags	 = count($tags_detail);
	    	for ($ti = 0;$ti < $count_tags; $ti++){
	    		if (!@in_array($tags_detail[$ti], $tags_array) && $tags_detail[$ti] != $tid && $tags_detail[$ti]) {
	    			$tags_real_count++;
		    		$tags_array[]=$tags_detail[$ti];
		    		$th_tags .= $th_tags ? ",".$tags_detail[$ti] : $tags_detail[$ti]; 
		    	}
	    	}
	    	
	    }
	    
	    $limit_similar_tags = min($tags_real_count, $max_similar_tags);
	    
	    if ($limit_similar_tags < 1) return;

		
	    for($i = 0;$i < $forumscount;$i++) {
	        if (!(!check_forum_permission(0, 1, $sxfourmrow[$i])) && $sxfourmrow[$i][type] != "category" && check_permission($username, $sxfourmrow[$i][type]) && !$sxfourmrow[$i][forumpass] && $sxfourmrow[$i][forumpass] <> "d41d8cd98f00b204e9800998ecf8427e") {
	            $forumnum["{$sxfourmrow[$i][id]}"] = $sxfourmrow[$i][bbsname];
	        } else {
	            if ($sxfourmrow[$i][type] != "category" && $countbyself != 1) $countbyself = 1;
	            $add_sql .= $add_sql ? ",'{$sxfourmrow[$i][id]}'" : "'{$sxfourmrow[$i][id]}'";
	        } 
	    } 
	    if ($add_sql != "") $add_sql = "AND forumid not in($add_sql)";

		$result = bmbdb_query("SELECT * FROM {$database_up}threads WHERE tid in ($th_tags) AND `tid`!= $tid $add_sql ORDER BY `changetime` DESC LIMIT 0,$limit_similar_tags");
		while (false !== ($line = bmbdb_fetch_array($result))) {
			$tmp_forumid	= $line['forumid'];
			$urlauthor	= $line["author"];
			
		    $lmd	=	explode(",", $line['lastreply']);
		    $time_tmp	=	getfulldate($lmd[2]);
		    if ($time_2) {
		        $timetmp_a	=	$timestamp - $lmd[2];
		        $lmdtime	=	get_add_date($timetmp_a);
		        if ($lmdtime == "getfulldate") {
		            $lmdtime	=	$time_tmp;
		        } 
		    } else {
		        $lmdtime	=	$time_tmp;
		    } 
		    
		    if ($is_article == 1) {
		    	$tagstothread = "article.php?filename={$line['id']}";
		    } else $tagstothread = $bmfopt['rewrite'] ? "topic_{$line['id']}" :"topic.php?filename={$line['id']}";
		    $tagstoforums = $bmfopt['rewrite'] ? "forums_{$tmp_forumid}" :"forums.php?forumid=$tmp_forumid";
			
			$sthread_list[]= array("tagstoforums" => $tagstoforums, "tagstothread" => $tagstothread, "timetoshow" => $lmdtime, "tag_forumid" => $tmp_forumid, "hit" => $line['hits'], "reply" => $line['replys'], "urlauthor" => $urlauthor, "viewauthor" => $line["author"], "forum_name" => $forumnum["$tmp_forumid"], "tag_filename" => $line['id'], "title" => stripslashes($line['title']));
		}
		
	}
   	eval(load_hook('int_topic_similar_threads'));
}