//** BMForum - TextBox Main Script ********************/
//   site  : www.bmforum.com
//   author: msxcms
//***********************************************/

/**  开始检测浏览器 ********************/
_d=document;
_nv=navigator.appVersion.toLowerCase();
_f=false;_t=true;
ie4_1=(!_d.getElementById&&_d.all)?_t:_f;
ie5_1=(_nv.indexOf("msie 5.0")!=-1)?_t:_f;
ie55_1=(_nv.indexOf("msie 5.5")!=-1)?_t:_f;
ie6_1=(_nv.indexOf("msie 6.0")!=-1 || _nv.indexOf("msie 7.0")!=-1)?_t:_f;
isIE_1=(ie5_1||ie55_1||ie6_1)?_t:_f;
/** 结束 ********************/

/** 开始:主模块 ********************/
function FTB_InitializeAll() { //初始化全部
	for (var i=0; i<FTB_StartUpArray.length; i++)
	FTB_Initialize(FTB_StartUpArray[i]);
}

function FTB_Initialize(ftbName) { //初始化
	
	startMode = eval(ftbName + "_StartMode");
	readOnly = eval(ftbName + "_ReadOnly");
	designModeCss = eval(ftbName + "_DesignModeCss");
	htmlModeCss = eval(ftbName + "_HtmlModeCss");

	hiddenHtml = FTB_GetHiddenField(ftbName);
	editor = FTB_GetIFrame(ftbName);
	
	if (readOnly) {
		editor.document.designMode = 'Off';
	} else {
		editor.document.designMode = 'On';
	}
	
	editor.document.open();
	//if (!isIE_1) 
	//editor.document.write("<html><body><style type='text/css'>body { direction: rtl; }</style>");
	editor.document.write(hiddenHtml.value);
	//if (!isIE_1) 
	//editor.document.write("</body><html>");
	editor.document.close();
		
	if (isIE_1) {
		if (htmlModeCss != "" || designModeCss != "" ) {
			editor.document.createStyleSheet(designModeCss);
			editor.document.createStyleSheet(htmlModeCss);
			editor.document.styleSheets[1].disabled = true;
		}
	} else {
		// turn off <span style="font-weight:bold">, use <b>
		editor.document.execCommand("useCSS", false, true); 
	}
	
	if (readOnly) {
		editor.document.contentEditable = 'False';
	} else {
		editor.document.contentEditable = 'True';
	}
	
	editor.document.body.style.border = '0';
	if (FTB_GetTextDirection(ftbName) == "RightToLeft")
		editor.document.body.style.direction = 'rtl';
	
	
	if (startMode != "DesignMode" && FTB_HideToolbar(ftbName)) {
		toolbar = FTB_GetToolbar(ftbName);
		if (toolbar != null) toolbar.style.display = 'none';
	}
}

function FTB_GetFtbName(ftb) { // 获得 FTB 名
	ftbName = ftb.name;
	underscore = ftbName.lastIndexOf("_");
	return ftbName.substring(0,underscore); 
}

function FTB_ChangeMode(ftb,goToHtmlMode) { // 改变模式
	editor = ftb;
		
	ftbName = FTB_GetFtbName(ftb);
	var toolbar = FTB_GetToolbar(ftbName);
	var hideToolbar = FTB_HideToolbar(ftbName);
	var editorContent;
	var iframe = document.getElementById(ftbName + "_Editor");
	
	editor.focus();
	
	if (goToHtmlMode) {
		if (isIE_1) {			
			if (editor.document.styleSheets.length > 0) {
				editor.document.styleSheets[0].disabled = true;
				editor.document.styleSheets[1].disabled = false;				
			}
			if (FTB_HtmlModeDefaultsToMonoSpaceFont(ftbName) && editor.document.styleSheets.length < 2) {						
				editor.document.body.style.fontFamily = 'Courier New, Courier New';
				editor.document.body.style.fontSize = '10pt';				
			}
						
			editorContent = editor.document.body.innerHTML;			
			//alert(editorContent);
			editor.document.body.innerText = editorContent;
		
		} else {			

			editorContent = document.createTextNode(editor.document.body.innerHTML);
			editor.document.body.innerHTML = "";
			editor.document.body.appendChild(editorContent);	
			
		}
		
		if (toolbar != null && hideToolbar ) {
			if (!isIE_1) iframe.style.height = '50%';
			toolbar.style.display = 'none';
			if (!isIE_1) setTimeout(function() { iframe.style.height = '100%'; }, 0);				
		}		
		return true;
	} else {
		// go to Design Mode
		if (isIE_1) {
			editorContent = editor.document.body.innerText;
			
			if (FTB_HtmlModeDefaultsToMonoSpaceFont(ftbName) && editor.document.styleSheets.length < 2) {					
				editor.document.body.style.fontFamily = '';
				editor.document.body.style.fontSize = '';
			}					
			if (editor.document.styleSheets.length > 0) {
				editor.document.styleSheets[0].disabled = false;
				editor.document.styleSheets[1].disabled = true;
			}
			
			editor.document.body.innerHTML = editorContent;
		} else {
						
			editorContent = editor.document.body.ownerDocument.createRange();
			editorContent.selectNodeContents(editor.document.body);
			editor.document.body.innerHTML = editorContent.toString();
		}

		if (toolbar != null && hideToolbar ) {
			if (!isIE_1) iframe.style.height = '50%';
			toolbar.style.display = '';
			if (!isIE_1) setTimeout(function() { iframe.style.height = '100%'; editor.focus();}, 0);				
		}
		
		editor.focus(); 
		return true;
	}
}

function FTB_CopyHtmlToHidden(ftbName) { // 复制到隐藏域
	hiddenHtml = FTB_GetHiddenField(ftbName);
	editor = FTB_GetIFrame(ftbName);
	
	if (isIE_1) {
		if (FTB_IsHtmlMode(ftbName)) {
			hiddenHtml.value = editor.document.body.innerText;  
		} else {
			hiddenHtml.value = editor.document.body.innerHTML;  
		}		
	} else {
		if (FTB_IsHtmlMode(ftbName)) {
			editorContent = editor.document.body.ownerDocument.createRange();
			editorContent.selectNodeContents(editor.document.body);
			hiddenHtml.value = editorContent.toString();
		} else {
			hiddenHtml.value = editor.document.body.innerHTML;  
		}	
	}
	hiddenHtml.value = FTB_FilterScript(hiddenHtml.value); 
	if (hiddenHtml.value == '<P>&nbsp;</P>' || hiddenHtml.value == '<br>') {
		hiddenHtml.value = '';
	}
}


function FTB_Format(ftbName,commandName) { // 格式
	editor = FTB_GetIFrame(ftbName);

	if (FTB_IsHtmlMode(ftbName)) return;
	editor.focus();
	editor.document.execCommand(commandName,'',null);
	
}

function FTB_SurroundText(ftbName,start,end) { // 修改 16:00 2004-5-7
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	editor.focus();
	
	if (isIE_1) {
		var sel = editor.document.selection.createRange();
		html = start + sel.htmlText + end;
		sel.pasteHTML(html);		
	} else {
        selection = editor.window.getSelection();
        editor.focus();
        if (selection) {
            range = selection.getRangeAt(0);
        } else {
            range = editor.document.createRange();
        } 
        
        FTB_InsertText(ftbName, start + selection + end);
	}	

}

function FTB_InsertText(ftbName,insertion) { // 插入
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	editor.focus();
	if (isIE_1) {
		sel = editor.document.selection.createRange();
		sel.pasteHTML(insertion);
	} else {
        editor.focus();
        selection = editor.window.getSelection();
		if (selection) {
			range = selection.getRangeAt(0);
		} else {
			range = editor.document.createRange();
		} 

        var fragment = editor.document.createDocumentFragment();
        var div = editor.document.createElement("div");
        div.innerHTML = insertion;

        while (div.firstChild) {
            fragment.appendChild(div.firstChild);
        }

        selection.removeAllRanges();
        range.deleteContents();

        var node = range.startContainer;
        var pos = range.startOffset;

        switch (node.nodeType) {
            case 3:
                if (fragment.nodeType == 3) {
                    node.insertData(pos, fragment.data);
                    range.setEnd(node, pos + fragment.length);
                    range.setStart(node, pos + fragment.length);
                } else {
                    node = node.splitText(pos);
                    node.parentNode.insertBefore(fragment, node);
                    range.setEnd(node, pos + fragment.length);
                    range.setStart(node, pos + fragment.length);
                }
                break;

            case 1:
                node = node.childNodes[pos];
                node.parentNode.insertBefore(fragment, node);
                range.setEnd(node, pos + fragment.length);
                range.setStart(node, pos + fragment.length);
                break;
        }
        selection.addRange(range);	
	}
}
function FTB_CheckTag(item,tagName) { //检查标记
	if (item.tagName.search(tagName)!=-1) {
		return item;
	}
	if (item.tagName=='BODY') {
		return false;
	}
	item=item.parentElement;
	return FTB_CheckTag(item,tagName);
}
/** 主模块结束 ********************/

/** 属性部分 ********************/
function FTB_IsHtmlMode(ftbName) { 
	return (eval(ftbName + "_HtmlMode"));
}

function FTB_TabMode(ftbName) {
	return (eval(ftbName + "_TabMode"));
}

function FTB_BreakMode(ftbName) {
	return (eval(ftbName + "_BreakMode"));
}

function FTB_HtmlModeDefaultsToMonoSpaceFont(ftbName) {
	return (eval(ftbName + "_HtmlModeDefaultsToMonoSpaceFont"));
}

function FTB_HideToolbar(ftbName) {
	return (eval(ftbName + "_HideToolbar"));
}

function FTB_UpdateToolbar(ftbName) {
	return (eval(ftbName + "_UpdateToolbar"));
}

function FTB_GetHiddenField(ftbName) {
	return document.getElementById(ftbName);
}

function FTB_GetTextDirection(ftbName) {
	return (eval(ftbName + "_TextDirection"));
}

function FTB_GetIFrame(ftbName) {
	if (isIE_1) {
		return eval(ftbName + "_Editor");
		//return document.getElementById(ftbName + "_Editor");
	} else {
		return document.getElementById(ftbName + "_Editor").contentWindow;
	}
}

function FTB_GetToolbar(ftbName) {
	return document.getElementById(ftbName + "_Toolbar");
}
function FTB_GetToolbarArray(ftbName) {
	return eval(ftbName + "_ToolbarItems");
}
function FTB_GetCssID(ftbName) {
	cssID = ftbName;
	while (cssID.substring(0,1) == '_') {
		cssID = cssID.substring(1);
	}
	return cssID;
}
function FTB_SetButtonStyle(buttonTD,style,checkstyle) {
	if (buttonTD == null) return;
	if (buttonTD.className != checkstyle)
		buttonTD.className = style;
	
}
function FTB_GetClassSubName(className) {
	underscore = className.indexOf("_");
	if (underscore < 0) return className;
	return className.substring(underscore+1);
}

/** 结束属性 ********************/

/** 选项卡 ********************/
function FTB_SetActiveTab(theTD,ftbName) { //设置选项卡
	parentTR = theTD.parentElement;
	parentTR = document.getElementById(ftbName + "_TabRow");

	selectedTab = 1;
	totalButtons = parentTR.cells.length-1;
	for (var i=1;i< totalButtons;i++) {
		parentTR.cells[i].className = FTB_GetCssID(ftbName) + "_TabOffRight";
		if (theTD == parentTR.cells[i]) { selectedTab = i; }
	}

	if (selectedTab==1) {
		parentTR.cells[0].className = FTB_GetCssID(ftbName) + "_StartTabOn";
	} else {
		parentTR.cells[0].className = FTB_GetCssID(ftbName) + "_StartTabOff";
		parentTR.cells[selectedTab-1].className = FTB_GetCssID(ftbName) + "_TabOffLeft";
	}

	theTD.className = FTB_GetCssID(ftbName) + "_TabOn";
}
function FTB_TabOver() {
	document.body.style.cursor='default';
}
function FTB_TabOut() {
	document.body.style.cursor='auto';
}
/** 结束 CODE:0457 ********************/


function FTB_SetToolbarItems(ftbName) {
	editor = FTB_GetIFrame(ftbName);
	htmlMode = FTB_IsHtmlMode(ftbName);
	toolbarArray = 	FTB_GetToolbarArray(ftbName);
		
	//document.getElementById("Debug").value = "";
	
	if (toolbarArray) {
		for (var i=0; i<toolbarArray.length; i++) {
			toolbarItemID = toolbarArray[i][0];
			toolbarItem = document.getElementById(toolbarItemID);
			commandIdentifier = toolbarArray[i][1];

			state = "";
			try {
				if (toolbarItemID.indexOf("Button") > -1) {
					state = editor.document.queryCommandState(commandIdentifier);

					FTB_SetButtonState(toolbarItemID,ftbName,state);
				} else {
					state = editor.document.queryCommandValue(commandIdentifier);
					
					switch (commandIdentifier) {
						case "backcolor":
							if (isIE_1) {
								state = FTB_GetHexColor(state);
							} else {
								if (state == "") state = "#FFFFFF";
							}
							break;						
						case "forecolor":
							if (isIE_1) {
								state = FTB_GetHexColor(state);
							} else {
								if (state == "") state = "#000000";
							}
							break;
						case "formatBlock":
							//document.getElementById("Debug").value += "****: " + state + "\n";
							if (!isIE_1) {
								if (state == "p" || state == "" || state == "<x>") 
									state = "<body>";
								else 
									state = "<" + state + ">";							
							}
							break;					
					}
						
					//document.getElementById("Debug").value += commandIdentifier + ": " + state + "\n";
					
					FTB_SetDropDownListState(toolbarItemID,state);					
				}
			} catch(e) {
			}
		}
	}
}

function FTB_GetHexColor(intColor) {
	intColor = intColor.toString(16).toUpperCase();
	while (intColor.length < 6) {
		intColor = "0" + intColor;
	}
	return "#" + intColor.substring(4,6) + intColor.substring(2,4) + intColor.substring(0,2);
}

function FTB_SetDropDownListState(ddlName,value) {
	ddl = document.getElementById(ddlName);
	
	if (ddl) {
		for (var i=0; i<ddl.options.length; i++) {
			if (ddl.options[i].text == value || ddl.options[i].value == value) {
				ddl.options.selectedIndex = i;
				return;
			}	
		}
	}
}

function FTB_SetButtonState(buttonName,ftbName,value) {
	buttonTD = document.getElementById(buttonName);
	
	if (buttonTD) {
		if (value) {
			buttonTD.className = FTB_GetCssID(ftbName) + "_ButtonActive";
			//FTB_ButtonOver(buttonTD,ftbName,0,0);
		} else {
			buttonTD.className = FTB_GetCssID(ftbName) + "_ButtonNormal";
			//FTB_ButtonOut(buttonTD,ftbName,0,0);
		}
	}
}

// *******************************

function FTB_GetParentElement(ftbName) {
	editor = FTB_GetIFrame(ftbName);

	var sel = FTB_GetSelection(ftbName);
	var range = FTB_CreateRange(ftbName,sel);
	if (isIE_1) {
		switch (sel.type) {
		    case "Text":
		    case "None":
				// It seems that even for selection of type "None",
				// there _is_ a parent element and it's value is not
				// only correct, but very important to us.  MSIE is
				// certainly the buggiest browser in the world and I
				// wonder, God, how can Earth stand it?
				return range.parentElement();
		    case "Control":
				return range.item(0);
		    default:
				return editor.document.body;
		}
	} else try {
		var p = range.commonAncestorContainer;
		if (!range.collapsed && range.startContainer == range.endContainer &&
		    range.startOffset - range.endOffset <= 1 && range.startContainer.hasChildNodes())
			p = range.startContainer.childNodes[range.startOffset];
		/*
		alert(range.startContainer + ":" + range.startOffset + "\n" +
		      range.endContainer + ":" + range.endOffset);
		*/
		while (p.nodeType == 3) {
			p = p.parentNode;
		}
		return p;
	} catch (e) {
		return null;
	}
};

// returns the current selection object
function FTB_GetSelection(ftbName) {
	editor = FTB_GetIFrame(ftbName);
	if (isIE_1) {
		return editor.document.selection;
	} else {
		return editor.getSelection();
	}
}

// returns a range for the current selection
function FTB_CreateRange(ftbName,sel) {
	editor = FTB_GetIFrame(ftbName);
	if (isIE_1) {
		return sel.createRange();
	} else {
		//TODO: this.focusEditor();
		if (typeof sel != "undefined") {
			try {
				return sel.getRangeAt(0);
			} catch(e) {
				return editor.document.createRange();
			}
		} else {
			return editor.document.createRange();
		}
	}
}
function FTB_FilterScript(str) { // 代码转为 BMB Code 
	str = str.replace(/\n+/g,"");
	str = str.replace(/\r/g,"");

	if (html_codeinfo != "yes" || document.__bmbForm.openhtmlcode.checked == true) {
	str = str.replace(/<script[^>]*?>([\w\W]*?)<\/script>/ig,"");
	
	str = str.replace(/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/ig,"[url=$1]$2[/url]");

	str = str.replace(/<font([^>]+)color="([^ >]+)"([^>]*)>(.*?)<\/font>/ig,"[color=$2]<font$1$3>$4</font>[/color]");
	str = str.replace(/<font([^>]+)size="([^ >]+)"([^>]*)>(.*?)<\/font>/ig,"[size=$2]<font$1$3>$4</font>[/size]");
	str = str.replace(/<font[^>]+face="([^ >]+)"[^>]*>(.*?)<\/font>/ig,"[font=$1]$2[/font]");
	str = str.replace(/<font([^>]+)color=([^ >]+)([^>]*)>(.*?)<\/font>/ig,"[color=$2]<font$1$3>$4</font>[/color]");
	str = str.replace(/<font([^>]+)size=([^ >]+)([^>]*)>(.*?)<\/font>/ig,"[size=$2]<font$1$3>$4</font>[/size]");
	str = str.replace(/<font[^>]+face=([^ >]+)[^>]*>(.*?)<\/font>/ig,"[font=$1]$2[/font]");
	
	str = str.replace(/<object[^>]*?6BF52A52\-394A\-11d3\-B153\-00C04F79FAA6[^>]*?>.*<param[^>]+name\s*=\s*["](url|src)["][^>]+value=[" ]?([^"]+)[" ][^>]*>.*<\/object>/ig,"\n[asf=500,300]$2[/asf]\n"); 
	str = str.replace(/<object[^>]*?D27CDB6E\-AE6D\-11cf\-96B8\-444553540000[^>]*?>.*<param[^>]+name\s*=\s*["](url|src)["][^>]+value=[" ]?([^"]+)[" ][^>]*>.*<\/object>/ig,"\n[swf=400,300]$2[/swf]\n");
	str = str.replace(/<embed[^>]*type=["]?application\/x\-shockwave\-flash["]?[^>]*src=[" ]?([^"|^ ]+)[" ]?[^>]*>/ig,"\n[swf=400,300]$1[/swf]\n");
	str = str.replace(/<embed[^>]*src=["]?([^"|^ ]+)["]?[^>]*type=["]?application\/x\-shockwave\-flash["]?[^>]*>/ig,"\n[swf=400,300]$1[/swf]\n");
	str = str.replace(/<object[^>]*?CFCDAA03\-8BE4\-11cf\-B84B\-0020AFBBCCFA[^>]*?>.*<param[^>]+name\s*=\s*["](url|src)["][^>]+value=[" ]?([^"]+)[" ][^>]*>.*<\/object>/ig,"\n[rm=500,300]$2[/rm]\n"); 

	
	str = str.replace(/<img[^>]+src="([^"]+)"[^>]+align="([^ >]+)"[^>]*>/ig,"[img=$2]$1[/img]");
	str = str.replace(/<img[^>]+src="([^"]+)"[^>]+align=([^ >]+)[^>]*>/ig,"[img=$2]$1[/img]");
	str = str.replace(/<img[^>]+src="([^"]+)"[^>]*>/ig,"[img]$1[/img]");
	
	str = str.replace(/<p[^>]+align="([^ >]+)"[^>]*>(.*?)<\/p>/ig,"[align=$1]$2[/align]");
	str = str.replace(/<p[^>]+align=([^ >]+)[^>]*>(.*?)<\/p>/ig,"[align=$1]$2[/align]");
	str = str.replace(/<div[^>]+align="([^"]+)"[^>]*>(.*?)<\/div>/ig,"[align=$1]$2[/align]");
	str = str.replace(/<([\/]?)td[^>]+bgcolor="([^ >]+)"([^>]*)*>/ig,"[$1td=$2]");
	str = str.replace(/<([\/]?)tr[^>]+bgcolor="([^ >]+)"([^>]*)*>/ig,"[$1tr=$2]");
	str = str.replace(/<([\/]?)table[^>]+bgcolor="([^ >]+)"([^>]*)*>/ig,"[$1table=$2]");
	str = str.replace(/<([\/]?)td[^>]+bgcolor=([^ >]+)([^>]*)*>/ig,"[$1td=$2]");
	str = str.replace(/<([\/]?)tr[^>]+bgcolor=([^ >]+)([^>]*)*>/ig,"[$1tr=$2]");
	str = str.replace(/<([\/]?)table[^>]+bgcolor=([^ >]+)([^>]*)*>/ig,"[$1table=$2]");
	str = str.replace(/<([\/]?)td[^>]*>/ig,"[$1td]");
	str = str.replace(/<([\/]?)tr[^>]*>/ig,"[$1tr]");
	str = str.replace(/<([\/]?)table[^>]*>/ig,"[$1table]");
	
	str = str.replace(/<center>/ig,"[align=center]");
	str = str.replace(/<\/center>/ig,"[/align]");
	str = str.replace(/<([\/]?)H1>/ig,"[$1H1]");
	str = str.replace(/<([\/]?)H2>/ig,"[$1H2]");
	str = str.replace(/<([\/]?)H3>/ig,"[$1H3]");
	str = str.replace(/<([\/]?)H4>/ig,"[$1H4]");
	str = str.replace(/<([\/]?)H5>/ig,"[$1H5]");
	str = str.replace(/<([\/]?)H6>/ig,"[$1H6]");
	str = str.replace(/<([\/]?)b>/ig,"[$1b]");
	str = str.replace(/<([\/]?)strong>/ig,"[$1b]");
	str = str.replace(/<([\/]?)u>/ig,"[$1u]");
	str = str.replace(/<([\/]?)em>/ig,"[$1i]");
	str = str.replace(/<([\/]?)STRIKE>/ig,"[$1STRIKE]");
	str = str.replace(/<([\/]?)sub>/ig,"[$1sub]");
	str = str.replace(/<([\/]?)sup>/ig,"[$1sup]");
	str = str.replace(/<([\/]?)UL>/ig,"[$1list]");
	str = str.replace(/<([\/]?)OL>/ig,"[$1olist]");
	str = str.replace(/<li>/ig,"[*]");
	
//	str = str.replace(/&nbsp;/g," ");
//	str = str.replace(/&amp;/g,"&");
	str = str.replace(/&quot;/g,"\"");

	str = str.replace(/<\/\p>/ig,"\n\n");
	str = str.replace(/<br>/ig,"\n");
	str = str.replace(/<BR\/>/ig,"\n");
	str = str.replace(/<[^>]*?>/g,"");
	str = str.replace(/\[url=([^\]]+)\](\[img\]\1\[\/img\])\[\/url\]/g,"$2");
	}
	return str;
}
