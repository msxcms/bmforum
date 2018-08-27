<?php
$dropdown_menu['message'] = '<ul class="dropdown-menu">';
$dropdown_menu['message'] .= '<li><a href="messenger.php?job=receivebox">'.$hefo[15].($gotNewMessage ? '('.$gotNewMessage.')' : '').'</a></li>';
$dropdown_menu['message'] .= '<li><a href="misc.php?p=notification">'.$hefo[60].($userddata['unreadnote'] ? '('.$userddata['unreadnote'].')' : '').'</a></li>';
$dropdown_menu['message'] .= '</ul>';

$dropdown_menu['usercp'] = '<ul class="dropdown-menu">';
$dropdown_menu['usercp'] .= '<li><a href="usercp.php">'.$hefo[14].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="profile.php">'.$hefo[39].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="viewfav.php">'.$hefo[40].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="friendlist.php">'.$hefo[41].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="usercp.php?act=active">'.$hefo[45].'</a></li>';
$dropdown_menu['usercp'] .= '<li><a href="misc.php?p=accounts">'.$hefo[47].'</a></li>';
$dropdown_menu['usercp'] .= '</ul>';

$dropdown_menu['skin'] = '<ul class="dropdown-menu">';
$dropdown_menu['skin'] .= '<li><a href="chskin.php?skinname=">'.$hefo[50].'(Default)</a></li>';
if ($fnew_skin == 1) {
	for($styi = 0;$styi < $stylecount;$styi++) {
		$cdhtail = explode("|", $styleopen[$styi]);
		$dropdown_menu['skin'] .= '<li><a href="chskin.php?skinname='.$cdhtail[1].'">'.$cdhtail[2].'</a></li>';
    } 
} 
$dropdown_menu['skin'] .= '</ul>';


$dropdown_menu['info'] = '<ul class="dropdown-menu">';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=viewnews">'.$hefo[9].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=viewtop">'.$hefo[10].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="userlist.php">'.$hefo[11].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="plugins.php?p=tags">'.$hefo[44].'</a></li>';
$dropdown_menu['info'] .= '<li><a href="misc.php?p=digg">'.$hefo[55].'</a></li>';
if ($login_status == 1){ 
	$dropdown_menu['info'] .= '<li><a href="search.php?keyword='.$o_username.'&amp;method=or&amp;method1=2&amp;method2=7">'.$hefo[48].'</a></li>';
	$dropdown_menu['info'] .= '<li><a href="search.php?keyword='.$o_username.'&amp;method=or&amp;method1=3&amp;method2=7">'.$hefo[49].'</a></li>';
}
$dropdown_menu['info'] .= '</ul>';
	
if ($count_language > 1) {
	$dropdown_menu['lang'] = '<ul class="dropdown-menu">';
	$langlist = @file("datafile/langlist.php");
	for($i = 0;$i < $count_language;$i++) {
		$cdhtail = explode("|", $langlist[$i]);
		if($cdhtail[1]) {
			$dropdown_menu['lang'] .= "<li><a href='chskin.php?langname=$cdhtail[1]'>$cdhtail[3]</a></li>";
		}
	} 
	 
	$dropdown_menu['lang'] .= '</ul>';
}



$oauthProviderCounter  = 0;
if($oauthLang['provider']) {
	$dropdown_menu['oauthBind'] = '<ul class="dropdown-menu">';
	foreach($oauthLang['provider'] as $providerId => $providerName) {
		if(!$bmfopt['oauth'][$providerId]['appKey']) {
			continue;
		}
		
		$oauthProviderCounter++;

		$dropdown_menu['oauthBind'] .= '<li><a href="oauth.php?act=login&type='.$providerId.'">'.$providerName.' - '.$oauthLang['bindAccount'].'</a></li>';
		$dropdown_menu['oauthBind'] .= '<li><a href="oauth.php?act=unbind&type='.$providerId.'">'.$providerName.' - '.$oauthLang['unbindAccount'].'</a></li>';
	}
	$dropdown_menu['oauthBind'] .= '</ul>';
}
