<html>
<head>
<style type="text/css">
body,input,select{font-size:9pt; color:333333; FONT-FAMILY: 宋体}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor=EEEEEE  leftmargin=0 background="background.gif" topmargin=0 scroll=no>
<script language="JavaScript1.2">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
  var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
  if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
		
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
		
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
  d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
		
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
  if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function email() {
	if (parent.helpstat) {
		alert("Email 标记\n插入 Email 超级链接\n用法1: [email]yourname\@yoursite.com[/email]\n用法2: [email=yourname\@yoursite.com]yourword[/email]");
	} else if (parent.basic) {
		AddTxt="[email][/email]";
		parent.AddText(AddTxt);
	} else {
		txt2=prompt("链接显示的文字.\n如果为空，那么将只显示你的 Email 地址","");
		if (txt2!=null) {
			txt=prompt("Email 地址.","name\@domain.com");      
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[email]"+txt+"[/email]";
				} else {
					AddTxt="[email="+txt+"]"+txt2;
					parent.AddText(AddTxt);
					AddTxt="[/email]";
				}
				parent.AddText(AddTxt);
			}
		}
	}
}

function bold() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[b]" + range.text + "[/b]";
} 
else {
	if (parent.helpstat) {
		alert("加粗标记\n使文本加粗.\n用法: [b]这是加粗的文字[/b]");
	} else if (parent.basic) {
		AddTxt="[b][/b]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("文字将被变粗.","文字");
		if (txt!=null) {
			AddTxt="[b]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/b]";
			parent.AddText(AddTxt);
		}
	}
}
}

function italicize() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[i]" + range.text + "[/i]";
} else{
	if (parent.helpstat) {
		alert("斜体标记\n使文本字体变为斜体.\n用法: [i]这是斜体字[/i]");
	} else if (parent.basic) {
		AddTxt="[i][/i]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("文字将变斜体","文字");
		if (txt!=null) {
			AddTxt="[i]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/i]";
			parent.AddText(AddTxt);
		}
	}
}
}


function quoteme() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[quote]" + range.text + "[/quote]";
} else {
	if (parent.helpstat){
		alert("引用标记\n引用一些文字.\n用法: [quote]引用内容[/quote]");
	} else if (parent.basic) {
		AddTxt="[quote][/quote]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("被引用的文字","文字");
		if(txt!=null) {
			AddTxt="[quote]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/quote]";
			parent.AddText(AddTxt);
		}
	}
}
}


function setfly() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[fly]" + range.text + "[/fly]";
} else {
 	if (parent.helpstat){
		alert("飞行标记\n使文字飞行.\n用法: [fly]文字为这样文字[/fly]");
	} else if (parent.basic) {
		AddTxt="[fly][/fly]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("飞行文字","文字");
		if (txt!=null) {
			AddTxt="[fly]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/fly]";
			parent.AddText(AddTxt);
		}
	}
}
}

function movesign() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[move]" + range.text + "[/move]";
} else {
	if (parent.helpstat) {
		alert("移动标记\n使文字产生移动效果.\n用法: [move]要产生移动效果的文字[/move]");
	} else if (parent.basic) {
		AddTxt="[move][/move]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("要产生移动效果的文字","文字");
		if (txt!=null) {
			AddTxt="[move]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/move]";
			parent.AddText(AddTxt);
		}
	}
}
}


function shadow() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[shadow=255, #ed1c24, 1]" + range.text + "[/shadow]";
} else {
	if (parent.helpstat) {
alert("阴影标记\n使文字产生阴影效果.\n用法: [SHADOW=宽度, 颜色, 边界]要产生阴影效果的文字[/SHADOW]");
	} else if (parent.basic) {
		AddTxt="[SHADOW=255,#ed1c24,1][/SHADOW]";
		parent.AddText(AddTxt);
	} else {
		txt2=prompt("文字的长度、颜色和边界大小","255,#ed1c24,1");
		if (txt2!=null) {
			txt=prompt("要产生阴影效果的文字","文字");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[shadow=255, #ed1c24, 1]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/shadow]";
					parent.AddText(AddTxt);
				} else {
					AddTxt="[shadow="+txt2+"]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/shadow]";
					parent.AddText(AddTxt);
				}
			}
		}
	}
}
}

function glow() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[glow=255,#ed1c24, 2]" + range.text + "[/glow]";
} else {
	if (parent.helpstat) {
		alert("光晕标记\n使文字产生光晕效果.\n用法: [GLOW=宽度, 颜色, 边界]要产生光晕效果的文字[/GLOW]");
	} else if (parent.basic) {
		AddTxt="[glow=255,#ed1c24,2][/glow]";
		parent.AddText(AddTxt);
	} else {
		txt2=prompt("文字的长度、颜色和边界大小","255,#ed1c24,2");
		if (txt2!=null) {
			txt=prompt("要产生光晕效果的文字.","文字");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[glow=255,#ed1c24,2]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/glow]";
					parent.AddText(AddTxt);
				} else {
					AddTxt="[glow="+txt2+"]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/glow]";
					parent.AddText(AddTxt);
				}
			}
		}
	}
}
}


function center() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		txt2=prompt("对齐样式\n输入 'center' 表示居中, 'left' 表示左对齐, 'right' 表示右对齐.","center");
		while ((txt2!="") && (txt2!="center") && (txt2!="left") && (txt2!="right") && (txt2!=null)) {
			txt2=prompt("错误!\n类型只能输入 'center' 、 'left' 或者 'right'.","");
		}
		var range = parent.document.selection.createRange();
		range.text = "\r[align="+txt2+"]"+ range.text + "[/align]";
} else {
 	if (parent.helpstat) {
		alert("对齐标记\n使用这个标记, 可以使文本左对齐、居中、右对齐.\n用法: [align=center|left|right]要对齐的文本[/align]");
	} else if (parent.basic) {
		AddTxt="[align=center|left|right][/align]";
		parent.AddText(AddTxt);
	} else {
		txt2=prompt("对齐样式\n输入 'center' 表示居中, 'left' 表示左对齐, 'right' 表示右对齐.","center");
		while ((txt2!="") && (txt2!="center") && (txt2!="left") && (txt2!="right") && (txt2!=null)) {
			txt2=prompt("错误!\n类型只能输入 'center' 、 'left' 或者 'right'.","");
		}
		txt=prompt("要对齐的文本","文本");
		if (txt!=null) {
			AddTxt="\r[align="+txt2+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/align]";
			parent.AddText(AddTxt);
		}
	}
}
}



function hyperlink() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		txt=prompt("为选中文字添加超级链接.","http://");
		range.text = "[url=" + txt + "]" + range.text + "[/url]";
} else {
	if (parent.helpstat) {
		alert("超级链接标记\n插入一个超级链接标记\n使用方法: [url]http://phpiscool.yeah.net[/url]\nUSE: [url=http://phpiscool.yeah.net]链接文字[/url]");
	} else if (parent.basic) {
		AddTxt="[url][/url]";
		parent.AddText(AddTxt);
	} else {
		txt2=prompt("链接文本显示.\n如果不想使用, 可以为空, 将只显示超级链接地址. ","");
		if (txt2!=null) {
			txt=prompt("超级链接.","http://");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[url]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/url]";
					parent.AddText(AddTxt);
				} else {
					AddTxt="[url="+txt+"]"+txt2;
					parent.AddText(AddTxt);
					AddTxt="[/url]";
					parent.AddText(AddTxt);
				}
			}
		}
	}
}
}


function image() {
	if (parent.helpstat){
		alert("图片标记\n插入图片\n用法： [img]http:\/\/www.php.net\/images\/php.gif[/img]");
	} else if (parent.basic) {
		AddTxt="[img][/img]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("图片的 URL","http://");
		if(txt!=null) {
			AddTxt="\r[img]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/img]";
			parent.AddText(AddTxt);
		}
	}
}

function wmv() {
	if (parent.helpstat){
		alert("Windows Media 标记\n插入 Windows 媒体\n用法： [asf=宽度,高度]http:\/\/www.php.net\/WMV\/php.WMV[/asf]");
	} else if (parent.basic) {
		AddTxt="[asf=500,350][/asf]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("Windows Media 的 URL","http://");
		width=prompt("Windows Media 的宽度","500");
		height=prompt("Windows Media 的高度","300");
		if(txt!=null) {
			AddTxt="\r[asf="+width+","+height+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/asf]";
			parent.AddText(AddTxt);
		}
	}
}

function sound() {
	if (parent.helpstat){
		alert("Real Media 标记\n插入 Real 媒体\n用法： [rm=宽度,高度]http:\/\/www.php.net\/WMV\/php.mp3[/rm]");
	} else if (parent.basic) {
		AddTxt="[rm=500,350][/rm]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("Real Media 的 URL","http://");
		width=prompt("Real Media 的宽度","500");
		height=prompt("Real Media 的高度","300");
		if(txt!=null) {
			AddTxt="\r[rm="+width+","+height+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/rm]";
			parent.AddText(AddTxt);
		}
	}
}

function showcode() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "\r[code]" + range.text + "[/code]";
} else {
	if (parent.helpstat) {
		alert("代码标记\n使用代码标记，可以使你的程序代码里面的 html 等标志不会被破坏.\n使用方法:\n [code]这里是代码文字[/code]");
	} else if (parent.basic) {
		AddTxt="\r[code]\r[/code]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("输入代码","");
		if (txt!=null) { 
			AddTxt="\r[code]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/code]";
			parent.AddText(AddTxt);
		}
	}
}
}

function list() {
	if (parent.helpstat) {
		alert("列表标记\n建造一个文字或则数字列表.\nUSE: [list]\n[*]item1\n[*]item2\n[*]item3\n[/list]");
	} else if (parent.basic) {
		AddTxt="\r[list]\r[*]\r[*]\r[*]\r[/list]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("列表类型\n输入 'A' 表示有序列表, '1' 表示无序列表, 留空表示无序列表.","");
		while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
			txt=prompt("错误!\n类型只能输入 'A' 、 '1' 或者留空.","");
		}
		if (txt!=null) {
			if ((txt=="") || (txt=="1")) {
				AddTxt="\r[list]\r\n";
			} else {
				AddTxt="\r[olist]\r\n";
			}
			ltxt="1";
			while ((ltxt!="") && (ltxt!=null)) {
				ltxt=prompt("列表项\n空白表示结束列表","");
				if (ltxt!="") {
					AddTxt+="[*]"+ltxt+"\r";
				}
			}
			if ((txt=="") || (txt=="1")) {
				AddTxt+="[/list]\r\n";
			} else {
				AddTxt+="[/olist]\r\n";
			} 
			parent.AddText(AddTxt);
		}
	}
}


function underline() {
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[u]" + range.text + "[/u]";
} else {
  	if (parent.helpstat) {
		alert("下划线标记\n给文字加下划线.\n用法: [u]要加下划线的文字[/u]");
	} else if (parent.basic) {
		AddTxt="[u][/u]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("下划线文字.","文字");
		if (txt!=null) {
			AddTxt="[u]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/u]";
			parent.AddText(AddTxt);
		}
	}
}
}


function setswf() {
 	if (parent.helpstat){
		alert("Flash 动画\n插入 Flash 动画.\n用法: [flash=宽度,高度]Flash 文件的地址[/flash]");
	} else if (parent.basic) {
		AddTxt="[flash=400,300][/flash]";
		parent.AddText(AddTxt);
	} else {
			txt2=prompt("宽度,高度","400,300");
		if (txt2!=null) {
			txt=prompt("Flash 文件的地址","http://");
			if (txt!=null) {
				if (txt2=="") {
					AddTxt="[flash=400,300]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/flash]";
					parent.AddText(AddTxt);
				} else {
					AddTxt="[flash="+txt2+"]"+txt;
					parent.AddText(AddTxt);
					AddTxt="[/flash]";
					parent.AddText(AddTxt);
				}
			}
		}
	}
}

</script>
<IMG id=m1 onmouseover="MM_swapImage('m1','','bold_d.gif',1)" onclick=bold() onmouseout=MM_swapImgRestore() height=22 alt=粗体字 src="bold.gif" width=23 border=0 name=m1>
<IMG id=m2 onmouseover="MM_swapImage('m2','','italicize_d.gif',1)" onclick=italicize() onmouseout=MM_swapImgRestore() height=22 alt=斜体字 src="italicize.gif" width=23 border=0 name=m2>
<IMG id=m3 onmouseover="MM_swapImage('m3','','underline_d.gif',1)" onclick=underline() onmouseout=MM_swapImgRestore() height=22 alt=下划线 src="underline.gif" width=23 border=0 name=m3> <IMG id=m4 onmouseover="MM_swapImage('m4','','center_d.gif',1)" onclick=center() onmouseout=MM_swapImgRestore() height=22 alt=居中 src="center.gif" width=23 border=0 name=m4>
<IMG id=m5 onmouseover="MM_swapImage('m5','','url_d.gif',1)" onclick=hyperlink() onmouseout=MM_swapImgRestore() height=22 alt=插入超级链接 src="url.gif" width=23 border=0 name=m5> <IMG id=m6 onmouseover="MM_swapImage('m6','','email_d.gif',1)" onclick=email() onmouseout=MM_swapImgRestore() height=22 alt=插入邮件地址 src="email.gif" width=23 border=0 name=m6> <IMG id=m7 onmouseover="MM_swapImage('m7','','image_d.gif',1)" onclick=image() onmouseout=MM_swapImgRestore() height=22 alt=插入图片 src="image.gif" width=23 border=0 name=m7>
<IMG id=m8 onmouseover="MM_swapImage('m8','','swf_d.gif',1)" onclick=setswf() onmouseout=MM_swapImgRestore() height=22 alt="插入 Flash 动画" src="swf.gif" width=23 border=0 name=m8> <IMG id=m9 onmouseover="MM_swapImage('m9','','mpeg_d.gif',1)" onclick=wmv() onmouseout=MM_swapImgRestore() height=22 alt="插入 Windows Media" src="mpeg.gif" width=23 border=0 name=m9> <IMG id=ma onmouseover="MM_swapImage('ma','','sound_d.gif',1)" onclick=sound() onmouseout=MM_swapImgRestore() height=22 alt="插入 Real Media" src="sound.gif" width=23 border=0 name=ma> <IMG id=mb onmouseover="MM_swapImage('mb','','code_d.gif',1)" onclick=showcode() onmouseout=MM_swapImgRestore() height=22 alt=插入代码 src="code.gif" width=23 border=0 name=mb>
<IMG id=mc onmouseover="MM_swapImage('mc','','quote_d.gif',1)" onclick=quoteme() onmouseout=MM_swapImgRestore() height=22 alt=插入引用 src="quote.gif" width=23 border=0 name=mc oSrc="quote.gif">
<IMG id=md onmouseover="MM_swapImage('md','','list_d.gif',1)" onclick=list() onmouseout=MM_swapImgRestore() height=22 alt=插入列表 src="list.gif" width=23 border=0 name=md> <IMG id=me onmouseover="MM_swapImage('me','','fly_d.gif',1)" onclick=setfly() onmouseout=MM_swapImgRestore() height=22 alt=飞行字 src="fly.gif" width=23 border=0 name=me>
<IMG id=mf onmouseover="MM_swapImage('mf','','move_d.gif',1)" onclick=movesign() onmouseout=MM_swapImgRestore() height=22 alt=移动字 src="move.gif" width=23 border=0 name=mf> <IMG id=mg onmouseover="MM_swapImage('mg','','glow_d.gif',1)" onclick=glow() onmouseout=MM_swapImgRestore() height=22 alt=发光字 src="glow.gif" width=23 border=0 name=mg>
<IMG id=mh onmouseover="MM_swapImage('mh','','shadow_d.gif',1)" onclick=shadow() onmouseout=MM_swapImgRestore() height=22 alt=阴影字 src="shadow.gif" width=23 border=0 name=mh> 
</body>
</html>