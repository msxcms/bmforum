<?php
global $otherimages,$bm_skin;
$po[0]=<<<EOT
<tr class="forumcoloronecolor">
<td class="forum_border_one_3"><strong>$login_user[0]</strong></td>
<td class="forum_border_one_3" colspan="2"><input type="hidden" value="exlogin" name="Method_Type" />
<input maxlength="25" size="25" name="username" value="$anonymous" /> </td>
</tr>
<tr class="forumcolortwo_noalign">
<td class="forum_border_one_3"><strong>$login_user[1]</strong><br />
<a href="misc.php?p=sendpwd" target="_blank">$login_user[2]</a></td>
<td class="forum_border_one_3" colspan="2"><input maxlength="25" size="25" name="password" type="password" /> 
$anonymous_tips </td>
</tr>
EOT;

$po['v']=<<<EOT
<tr class="forumcoloronecolor">
<td class="forum_border_one_3"><strong>$gl[439]</strong></td>
<td class="forum_border_one_3" colspan="2"><input maxlength="25" size="25" name="authinput" type="text" /> 
$tmp23s </td>
</tr>
EOT;

$panelbar=<<<EOT
<table cellspacing="0" width="500" cellpadding="0" class="bmbnewstyle_withoutwidth">
	<tr>
		<td>
		<table cellspacing="1" cellpadding="3" width="100%">
			<tr>
				<td bgcolor="EEEEEE"><span class="cautioncolor"><strong>$tipc[0]</strong> [
				<a href="editor/panel1.php" target="fontpanel">
				$tipc[1]</a> -
				<a href="editor/panel2.php" target="fontpanel">
				$tipc[2]</a> -
				<a href="editor/panel3.php" target="fontpanel">
				$tipc[3]</a> ] - $tipc[4]</span><br />
				<hr size="1" />
				<iframe name="fontpanel" frameborder="0" src="editor/panel2.php" width="100%" height="22">
				</iframe><hr size="1" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<img src="" alt="" height="5" /><br />
EOT;
