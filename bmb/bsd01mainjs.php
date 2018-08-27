<script type="text/javascript">
//<![CDATA[ 
var menuOffX=0	//菜单距连接文字最左端距离
var menuOffY=18	//菜单距连接文字顶端距离

var fo_shadows=new Array()

linkset[1]='<div class="menuitems"><a href="misc.php?p=viewnews"><?php echo $hefo[9];?></a></div>';
linkset[1]+='<div class="menuitems"><a href="misc.php?p=viewtop"><?php echo $hefo[10];?></a></div>'
linkset[1]+='<div class="menuitems"><a href="userlist.php"><?php echo $hefo[11];?></a></div>'
linkset[1]+='<div class="menuitems"><a href="plugins.php?p=tags"><?php echo $hefo[44];?></a></div>'
linkset[1]+='<div class="menuitems"><a href="misc.php?p=digg"><?php echo $hefo[55];?></a></div>'
<?php
if ($login_status == 1){ 
?>
linkset[1]+='<div class="menuitems"><a href="search.php?keyword=<?php echo $o_username;?>&amp;method=or&amp;method1=2&amp;method2=7"><?php echo $hefo[48];?></a></div>'
linkset[1]+='<div class="menuitems"><a href="search.php?keyword=<?php echo $o_username;?>&amp;method=or&amp;method1=3&amp;method2=7"><?php echo $hefo[49];?></a></div>'
<?php
}
?>

linkset[2]=''
linkset[2]+='<div class="menuitems"><a href="chskin.php?skinname="><?php echo $hefo[50];?></a></div>'
<?php
if ($fnew_skin == 1) {
for($styi = 0;$styi < $stylecount;$styi++) {
	$cdhtail = explode("|", $styleopen[$styi]);
?>
linkset[2]+='<div class="menuitems"><a href="chskin.php?skinname=<?php echo $cdhtail[1];?>"><?php echo $cdhtail[2];?></a></div>'
<?php
    } 
} 

?>

linkset[3]='<div class="menuitems"><a href="profile.php"><?php echo $hefo[39];?></a></div>'
linkset[3]+='<div class="menuitems"><a href="messenger.php?job=receivebox"><?php echo $hefo[15];?></a></div>'
linkset[3]+='<div class="menuitems"><a href="viewfav.php"><?php echo $hefo[40];?></a></div>'
linkset[3]+='<div class="menuitems"><a href="friendlist.php"><?php echo $hefo[41];?></a></div>'
linkset[3]+='<div class="menuitems"><a href="usercp.php?act=active"><?php echo $hefo[45];?></a></div>'
linkset[3]+='<div class="menuitems"><a href="misc.php?p=accounts"><?php echo $hefo[47];?></a></div>'


linkset[4]='<form action="search.php" method=post><div class="menuitems"> &nbsp; &nbsp; &nbsp; <?php echo $hefo[42];?><input type=text name=keyword  size=20> <input type=hidden name="method" value="or"><input type=hidden name=forumid value="all"><input type=hidden name=method1 value="1"><input type=hidden name=method2 value="60"><input type=hidden name=searchfid value="<?php echo forumid;?>"><input type="submit" value="<?php echo $hefo[12];?>"></form><div align=right><a href=search.php><?php echo $hefo[43];?></a></div></div>';
linkset[5]=''
linkset[5]+='<div class="menuitems"><a href="chskin.php?langname="><?php echo $hefo[50];?>(Default)</a></div>'
<?php
if ($count_language > 1) {
$langlist = @file("datafile/langlist.php");
for($i = 0;$i < $count_language;$i++) {
	$cdhtail = explode("|", $langlist[$i]);
?>
linkset[5]+='<div class="menuitems"><a href="chskin.php?langname=<?php echo $cdhtail[1];?>"><?php echo $cdhtail[3];?></a></div>'
<?php
    } 
} 
if (!$echoed_offset) {
	echo "var menuleft_offset = 20;\n";
	echo "var menutop_offset = 2;";
}
?>
////No need to edit beyond here
linkset[6]=''
<?php
$oauthProviderCounter  = 0;
if($oauthLang['provider']) {
	foreach($oauthLang['provider'] as $providerId => $providerName) {
		if(!$bmfopt['oauth'][$providerId]['appKey']) {
			continue;
		}
		
		$oauthProviderCounter++;

echo <<<EOT
linkset[6]+='<div class="menuitems"><a href="oauth.php?act=login&type={$providerId}">{$providerName} - {$oauthLang['bindAccount']}</a></div>';
linkset[6]+='<div class="menuitems"><a href="oauth.php?act=unbind&type={$providerId}">{$providerName} - {$oauthLang['unbindAccount']}</a></div>';

EOT;

	}
}
?>

var ie4=document.all;
var ns6=document.getElementById&&!document.all;
var ns4=document.layers;

function showmenu(e,vmenu,mod){
	if (!document.all&&!document.getElementById&&!document.layers)
		return;
	which=vmenu;
	clearhidemenu();
	ie_clearshadow();
	menuobj=ie4? document.all.popmenu : ns6? document.getElementById("popmenu") : ns4? document.popmenu : "";
	menuobj.thestyle=(ie4||ns6)? menuobj.style : menuobj;
	
	if (ie4||ns6)
		menuobj.innerHTML=which;
	else{
		menuobj.document.write('<layer name="gui" style="background-color:#E6E6E6;width:165px" onmouseover="clearhidemenu()" onmouseout="hidemenu()">'+which+'</layer>');
		menuobj.document.close();
	}
	menuobj.contentwidth=(ie4||ns6)? menuobj.offsetWidth : menuobj.document.gui.document.style.width;
	menuobj.contentheight=(ie4||ns6)? menuobj.offsetHeight : menuobj.document.gui.document.style.height;
	
	eventX=ie4? event.clientX : ns6? e.clientX : e.x;
	eventY=ie4? event.clientY : ns6? e.clientY : e.y;
	
	var rightedge=ie4? document.documentElement.clientWidth-eventX : window.innerWidth-eventX;
	var bottomedge=ie4? document.documentElement.clientHeight-eventY : window.innerHeight-eventY;

	if (rightedge<menuobj.contentwidth)
		menuleft=ie4? document.documentElement.scrollLeft+eventX-menuobj.contentwidth+menuOffX : ns6? window.pageXOffset+eventX-menuobj.contentwidth : eventX-menuobj.contentwidth;
	else menuleft=ie4? ie_x(event.srcElement)+menuOffX : ns6? window.pageXOffset+eventX : eventX;
	

	if (bottomedge<menuobj.contentheight&&mod!=0)
		menutop=ie4? document.documentElement.scrollTop+eventY-menuobj.contentheight-event.offsetY+menuOffY-23 : ns6? window.pageYOffset+eventY-menuobj.contentheight-10 : eventY-menuobj.contentheight;
	else menutop=ie4? ie_y(event.srcElement)+menuOffY : ns6? window.pageYOffset+eventY+10 : eventY;
	
	menuleft = menuleft-menuleft_offset;
	menutop = menutop-menutop_offset;
	
	menuobj.thestyle.left = menuleft+"px";
	menuobj.thestyle.top = menutop+"px";
			
	menuobj.thestyle.visibility="visible";
	ie_dropshadow(menuobj,"#999999",3);
	return false;
}

function ie_y(e){  
	var t=e.offsetTop;  
	while(e=e.offsetParent){  
		t+=e.offsetTop;  
	}  
	return t;  
}  
function ie_x(e){  
	var l=e.offsetLeft;  
	while(e=e.offsetParent){  
		l+=e.offsetLeft;  
	}  
	return l;  
}  
function ie_dropshadow(el, color, size)
{
	var i;
	for (i=size; i>0; i--)
	{
		var rect = document.createElement('div');
		var rs = rect.style
		rs.position = 'absolute';
		rs.left = (el.style.posLeft + i) + 'px';
		rs.top = (el.style.posTop + i) + 'px';
		rs.width = el.offsetWidth + 'px';
		rs.height = el.offsetHeight + 'px';
		rs.zIndex = el.style.zIndex - i;
		rs.backgroundColor = color;
		var opacity = 1 - i / (i + 1);
		rs.filter = 'alpha(opacity=' + (100 * opacity) + ')';
		//el.insertAdjacentElement('afterEnd', rect);
		fo_shadows[fo_shadows.length] = rect;
	}
}
function ie_clearshadow()
{
	for(var i=0;i<fo_shadows.length;i++)
	{
		if (fo_shadows[i])
			fo_shadows[i].style.display="none"
	}
	fo_shadows=new Array();
}


function contains_ns6(a, b) {
	while (b.parentNode)
		if ((b = b.parentNode) == a)
			return true;
	return false;
}

function hidemenu(){
	if (window.menuobj)
		menuobj.thestyle.visibility=(ie4||ns6)? "hidden" : "hide";
	ie_clearshadow();
}

function dynamichide(e){
	if (ie4&&!menuobj.contains(e.toElement))
		hidemenu();
	else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
		hidemenu();
}

function delayhidemenu(){
	if (ie4||ns6||ns4)
		delayhide=setTimeout("hidemenu()",500);
}

function clearhidemenu(){
	if (window.delayhide)
		clearTimeout(delayhide)
}

function highlightmenu(e,state){
	if (document.all)
		source_el=event.srcElement;
	else if (document.getElementById)
		source_el=e.target;
	if (source_el.className=="menuitems"){
		source_el.id=(state=="on")? "mouseoverstyle" : "";
	}
	else{
		while(source_el.id!="popmenu"){
			source_el=document.getElementById? source_el.parentNode : source_el.parentElement;
			if (source_el.className=="menuitems"){
				source_el.id=(state=="on")? "mouseoverstyle" : "";
			}
		}
	}
}

//if (ie4||ns6)
//document.onclick=hidemenu
//]]>>
</script>