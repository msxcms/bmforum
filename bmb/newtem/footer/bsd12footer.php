<center><?php
if ($ads_select==1){//广告开关
?>
<table align="center">
	<tr>
		<td width="100%"><?php
	mt_srand($timestamp);
	$showads=mt_rand(1,2);
	if ($showads==1) {
		$announcement_file="datafile/ads.php";
		if (file_exists($announcement_file)) {
			include($announcement_file);
			if ($ads==""){
				$ads="";
			}
			$ads=$ads;
		} else $ads="";
		echo $ads;
	}
	if ($showads==2) {
		$ads2_file="datafile/ads2.php";
		if (file_exists($ads2_file)) {
			include($ads2_file);
			if ($ads2==""){
				$ads2="";
			}
			$ads2=$ads2;
		} else $ads2="";
		echo $ads2;
	}
?> </td>
	</tr>
</table>
<br />
<div id='appendArea'></div>
<script type="text/javascript">
(function() {
    if(!isIe6) {
	    var $backToTopTxt = "<?php echo $hefo[61];?>", $backToTopEle = $('<div class="backToTop"></div>').appendTo($("#totallayer"))
	        .html($backToTopTxt).css("left", $("#totallayer").offset().left+$("#totallayer").width()+10).click(function() {
	            $("html,body").animate({scrollTop: 0}, 500);
	    }), $backToTopFun = function() {
	        var st = $(document).scrollTop(), winh = $(window).height();
	        (st > 0)? $backToTopEle.show(): $backToTopEle.hide();    
	        if (!window.XMLHttpRequest) {
	            $backToTopEle.css("top", st + winh - 166);    
	        }
	    };
	    $(window).bind("scroll", $backToTopFun);
	    $(function() { $backToTopFun(); });
	}
	$('.navbar-inner').bind("dblclick", function(event) {
		$("html,body").animate({scrollTop: 0}, 500);
	});
})();
</script>
<?php
}//广告开关

if (file_exists("datafile/licenced")) {
	$licence_display = '<a target="_blank" href="http://www.bmforum.com/licence.php?site='.$_SERVER['HTTP_HOST'].'">[Licenced]</a>';
} else $licence_display = "";

?>
<table border="0" align="center" cellpadding="0" cellspacing="0" style="width:100%;">
<tr><td>		
	<table class="bmbnewstyle_withoutwidth" style="margin-top:3px;" cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
		<tr>
			<td style="background-color: #fff;">
			<table cellspacing="1" cellpadding="5" width="100%" border="0">
				<tr>
					<td class="bmforum_base_menu">
					<table cellspacing="0" cellpadding="1" width="100%">
						<tr>
		<td align="left" style="width:34px;">
		<a href="http://www.bmforum.com/" target="_blank"><img src='<?php echo $otherimages;?>/logosmall.gif' border='0' title='Powered by BMForum' alt='Powered by BMForum' /></a>
		</td>
		<td align="left">
		<?php echo $footer_copyright; ?>
		</td>
		<td align="right">Powered by <a href="http://www.bmforum.com/"><?php echo $verandproname;?></a> <?php echo $licence_display;?>
		<a href="rss.php?forumid=<?php echo $forumid; if ($urltagname) echo '&amp;tagname='.$urltagname;?>">
		<img alt="RSS Feed" border="0" src="<?php echo $otherimages;?>/xml_button.gif" /></a> 
		&nbsp;<br />
		<?php
//---是否显示运行时间
if ($showtime==1){
	$end_time = micro_time();
	$spend = substr($end_time - $begin_time, 0, 10);
	if(VERTYPE==0){
		echo $hefo[25].' '.$spend.' '.$hefo[26].' '.$readfilenum.' '.$hefo[27].' '.$writefilenum.' '.$hefo[28];
	}elseif(VERTYPE==1){
		echo 'Processed in '.$spend.' second(s),'.$querynum.' queries';
	}
}
?>&nbsp;&nbsp;
		</td>

						</tr>
					</table>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
</td></tr></table>


</center>
</div>
</div>
</body>
</html>
<?php
	if ($bmfopt['advanced_headers']) {
		$size	=	ob_get_length(); 
		header("Content-Length: $size"); 
		header("Accept-Ranges: none"); 
		if (!@defined("HEADERS_SENT")) {
			header("Last-Modified: ".date('r', $timestamp)); 
			header("ETag: ".md5($row['title'].$timestamp)); 
		}
	}
	ob_end_flush();
	exit;