<html>
<head>
<style type="text/css">
body,input,select{font-size:9pt; color:333333; FONT-FAMILY: 宋体}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor=EEEEEE  leftmargin=0 background="background.gif" topmargin=0 scroll=no>
<script language=JavaScript1.2>
function showsize(size) {
if (size=="#define#") {
		size = prompt("请输入自定义的字体大小(阿拉伯数字)", "字体大小");
}
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[size=" +size+"]"+ range.text + "[/size]";
} else {
	if (parent.helpstat) {
		alert("文字大小标记\n设置文字大小.\n可变范围 1 - 6.\n 1 为最小 6 为最大.\n用法: [size="+size+"]这是 "+size+" 文字[/size]");
	} else if (parent.basic) {
		AddTxt="[size="+size+"][/size]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("大小 "+size,"文字");
		if (txt!=null) {
			AddTxt="[size="+size+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/size]";
			parent.AddText(AddTxt);
		}
	}
}
}

function showfont(font) {
if (font=="#define#") {
		font = prompt("请输入自定义的字体名", "字体名");
}
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[font=" +font+"]"+ range.text + "[/font]";
} else {
 	if (parent.helpstat){
		alert("字体标记\n给文字设置字体.\n用法: [font="+font+"]改变文字字体为"+font+"[/font]");
	} else if (parent.basic) {
		AddTxt="[font="+font+"][/font]";
		parent.AddText(AddTxt);
	} else {
		txt=prompt("要设置字体的文字"+font,"文字");
		if (txt!=null) {
			AddTxt="[font="+font+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/font]";
			parent.AddText(AddTxt);
		}
	}
}
}


function showcolor(color) {
if (color=="#define#") {
		color = prompt("请输入自定义颜色(可使用颜色英文名或颜色代码)", "颜色");
}
if (parent.document.selection && parent.document.selection.type == "Text") {
		var range = parent.document.selection.createRange();
		range.text = "[color=" +color+"]"+ range.text + "[/color]";
} else {
	if (parent.helpstat) {
		alert("颜色标记\n设置文本颜色.  任何颜色名都可以被使用.\n用法: [color="+color+"]颜色要改变为"+color+"的文字[/color]");
	} else if (parent.basic) {
		AddTxt="[color="+color+"][/color]";
		parent.AddText(AddTxt);
	} else {  
     	txt=prompt("选择的颜色是: "+color,"文字");
		if(txt!=null) {
			AddTxt="[color="+color+"]"+txt;
			parent.AddText(AddTxt);
			AddTxt="[/color]";
			parent.AddText(AddTxt);
		}
	}
}
}

</script>
字体： 
<select onChange=showfont(this.options[this.selectedIndex].value) name=font>
<option value="宋体" selected>宋体</option>
<option value="楷体_GB2312">楷体</option>
<option value="新宋体">新宋体</option>
<option value="黑体">黑体</option>
<option value="隶书">隶书</option>
<option value="仿宋体">仿宋体</option>
<option value=Arial>Arial</option>
<option value=Tahoma>Tahoma</option>
<option value=Verdana>Verdana</option>
<option value="Times New Roman">Times New Roman</option>
<option value="Bookman Old Style">Bookman Old Style</option>
<option value="Century Gothic">Century Gothic</option>
<option value="Comic Sans MS">Comic Sans MS</option>
<option value="Courier New">Courier New</option>
<option value="Wingdings">Wingdings</option>
<option value="#define#">自定义</option>

</select>
&nbsp;&nbsp;字号：
<select onChange=showsize(this.options[this.selectedIndex].value) name=size>
<option value=1>1</option>
<option value=2>2</option>
<option value=3 selected>3</option>
<option value=4>4</option>
<option value=5>5</option>
<option value=6>6</option>
<option value="#define#">自定义</option>
</select>
&nbsp;&nbsp;颜色： 
<select onChange=showcolor(this.options[this.selectedIndex].value) name=color>
<option value="#000000" selected='selected' style="background-color: Black; color: rgb(255, 255, 255);"></option><option value="#FFFFFF" style="background-color: #FFFFFF;"></option><option value="#464646" style="background-color: #464646;"></option><option value="#787878" style="background-color: #787878;"></option><option value="#B4B4B4" style="background-color: #B4B4B4;"></option><option value="#DCDCDC" style="background-color: #DCDCDC;"></option><option value="#990030" style="background-color: #990030;"></option><option value="#ED1C24" style="background-color: #ED1C24;"></option><option value="#FF7E00" style="background-color: #FF7E00;"></option><option value="#FFC20E" style="background-color: #FFC20E;"></option><option value="#FFF200" style="background-color: #FFF200;"></option><option value="#A8E61D" style="background-color: #A8E61D;"></option><option value="#22B14C" style="background-color: #22B14C;"></option><option value="#00B7EF" style="background-color: #00B7EF;"></option><option value="#4D6DF3" style="background-color: #4D6DF3;"></option><option value="#2F3699" style="background-color: #2F3699;"></option><option value="#6F3198" style="background-color: #6F3198;"></option><option value="#B5A5D5" style="background-color: #B5A5D5;"></option><option value="#546D8E" style="background-color: #546D8E;"></option><option value="#709AD1" style="background-color: #709AD1;"></option><option value="#99D9EA" style="background-color: #99D9EA;"></option><option value="#9DBB61" style="background-color: #9DBB61;"></option><option value="#D3F9BC" style="background-color: #D3F9BC;"></option><option value="#FFF9BD" style="background-color: #FFF9BD;"></option><option value="#F5E49C" style="background-color: #F5E49C;"></option><option value="#E5AA7A" style="background-color: #E5AA7A;"></option><option value="#F5E49C" style="background-color: #F5E49C;"></option><option value="#FFA3B1" style="background-color: #FFA3B1;"></option><option value="#9C5A3C" style="background-color: #9C5A3C;"></option>
<option value="#define#">自定义</option>
</select>
</body></html>