//** BMForum - Builtin ToolbarItems Script ********************/
//   site  : www.bmforum.com
//   author: msxcms
//***********************************************/
function FTB_Bold(ftbName) { 
	FTB_Format(ftbName,'bold'); 
}
function FTB_BulletedList(ftbName) { 
	FTB_Format(ftbName,'insertunorderedlist'); 
}
function FTB_Copy(ftbName) { 
	try {
		FTB_Format(ftbName,'copy'); 
	} catch (e) {
		alert('Your security settings to not allow you to use this command.  Please visit http://www.mozilla.org/editor/midasdemo/securityprefs.html for more information.');
	}

}
function FTB_CreateLink(ftbName) { 
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	editor.focus();
	if (isIE_1) {
		editor.document.execCommand('createlink','1',null);
	} else {
		var url = prompt('Enter a URL:', 'http://');
		if ((url != null) && (url != ''))  editor.document.execCommand('createlink',false,url);
	}
}
function FTB_Cut(ftbName) { 
	try {
		FTB_Format(ftbName,'cut'); 
	} catch (e) {
		alert('Your security settings to not allow you to use this command.  Please visit http://www.mozilla.org/editor/midasdemo/securityprefs.html for more information.');
	}

}
function FTB_Delete(ftbName) { 
	editor = FTB_GetIFrame(ftbName);
	if (confirm('Do you want to delete all the HTML and text presently in the editor?')) {	
		editor.document.body.innerHTML = '';
		if (isIE_1) {			
			editor.document.body.innerText = '';
		}
	}
	editor.focus();
}
function FTB_Indent(ftbName) { 
	FTB_Format(ftbName,'indent'); 
}
function FTB_InsertDate(ftbName) { 
	var d = new Date();
	FTB_InsertText(ftbName,d.toLocaleDateString());
}
function FTB_InsertImage(ftbName) { 
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	editor.focus();
    editor.document.execCommand('insertimage',1,'');
}
function FTB_InsertRule(ftbName) { 
	FTB_Format(ftbName,'inserthorizontalrule');
}
function FTB_InsertTime(ftbName) { 
	var d = new Date();
	FTB_InsertText(ftbName,d.toLocaleTimeString());
}
function FTB_Italic(ftbName) { 
	FTB_Format(ftbName,'italic'); 
}
function FTB_JustifyRight(ftbName) { 
	FTB_Format(ftbName,'justifyright'); 
}
function FTB_JustifyCenter(ftbName) { 
	FTB_Format(ftbName,'justifycenter'); 
}
function FTB_JustifyFull(ftbName) { 
	FTB_Format(ftbName,'justifyfull'); 
}
function FTB_JustifyLeft(ftbName) { 
	FTB_Format(ftbName,'justifyleft'); 
}
function FTB_NumberedList(ftbName) { 
	FTB_Format(ftbName,'insertorderedlist'); 
}
function FTB_Outdent(ftbName) { 
	FTB_Format(ftbName,'outdent'); 
}
function FTB_Paste(ftbName) { 
	try {
		FTB_Format(ftbName,'paste'); 
	} catch (e) {
		alert('Your security settings to not allow you to use this command.  Please visit http://www.mozilla.org/editor/midasdemo/securityprefs.html for more information.');
	}
}
function FTB_Print(ftbName) { 
	if (isIE_1) {
		FTB_Format(ftbName,'print'); 
	} else {
		editor = FTB_GetIFrame(ftbName);
		editor.print();
	}
}
function FTB_Redo(ftbName) { 
	FTB_Format(ftbName,'undo'); 
}
function FTB_RemoveFormat(ftbName) { 
	FTB_Format(ftbName,'removeformat'); 
}
function FTB_Save(ftbName) { 
	FTB_CopyHtmlToHidden(ftbName); 
	__doPostBack(ftbName,'Save');
}
function FTB_StrikeThrough(ftbName) { 
	FTB_Format(ftbName,'strikethrough'); 
}
function FTB_SubScript(ftbName) { 
	FTB_Format(ftbName,'subscript'); 
}
function FTB_SuperScript(ftbName) { 
	FTB_Format(ftbName,'superscript'); 
}
function FTB_Underline(ftbName) { 
	FTB_Format(ftbName,'underline'); 
}
function FTB_Undo(ftbName) { 
	FTB_Format(ftbName,'undo'); 
}
function FTB_Unlink(ftbName) { 
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	editor.focus();
    editor.document.execCommand('unlink',false,null);
}
function FTB_SetFontBackColor(ftbName,name,value) {
	editor = FTB_GetIFrame(ftbName);
	
	if (FTB_IsHtmlMode(ftbName)) return;
	editor.focus();
	editor.document.execCommand('backcolor','',value);
}
function FTB_SetFontFace(ftbName,name,value) {
	editor = FTB_GetIFrame(ftbName);
	
	if (FTB_IsHtmlMode(ftbName)) return;
	editor.focus();
	editor.document.execCommand('fontname','',value);
}
function FTB_SetFontForeColor(ftbName,name,value) {
	editor = FTB_GetIFrame(ftbName);
	
	if (FTB_IsHtmlMode(ftbName)) return;
	editor.focus();
	editor.document.execCommand('forecolor','',value);
}
function FTB_SetFontSize(ftbName,name,value) {
	editor = FTB_GetIFrame(ftbName);
	
	if (FTB_IsHtmlMode(ftbName)) return;
	editor.focus();
	editor.document.execCommand('fontsize','',value);
}
function FTB_InsertHtmlMenu(ftbName,name,value) {
	FTB_InsertText(ftbName,value);
}
function FTB_SetParagraph(ftbName,name,value) {
	if (FTB_IsHtmlMode(ftbName)) return;
	editor = FTB_GetIFrame(ftbName);
	if (value == '<body>') {
		editor.document.execCommand('formatBlock','','Normal');
		editor.document.execCommand('removeFormat');
		return;
	}
	editor.document.execCommand('formatBlock','',value);
}
function FTB_SymbolsMenu(ftbName,name,value) {
	FTB_InsertText(ftbName,value);
}
function FTB_ieSpellCheck(ftbName) { 
    if (FTB_IsHtmlMode(ftbName)) return;
	if (!isIE_1) {
		alert('IE Spell is not supported in Mozilla');
		return;
	}
	try {
		var tspell = new ActiveXObject('ieSpell.ieSpellExtension');
		tspell.CheckAllLinkedDocuments(window.document);
	} catch (err){
		if (window.confirm('You need ieSpell to use spell check. Would you like to install it?')){window.open('http://www.iespell.com/download.php');};
	};

}
function FTB_NetSpell(ftbName) { 
    if (FTB_IsHtmlMode(ftbName)) return;
	try {
		checkSpellingById(ftbName + '_Editor');
	} catch(e) {
		alert('Netspell libraries not properly linked.');
	}
}
function FTB_SetStyle(ftbName,name,value) { 
	var className = value;
	editor = FTB_GetIFrame(ftbName);
	
	// retrieve parent element of the selection
	var parent = FTB_GetParentElement(ftbName);
	
	var surround = true;

	var isSpan = (parent && parent.tagName.toLowerCase() == "span");
	
	/*
	// remove class stuff??
	if (isSpan && index == 0 && !/\S/.test(parent.style.cssText)) {
		while (parent.firstChild) {
			parent.parentNode.insertBefore(parent.firstChild, parent);
		}
		parent.parentNode.removeChild(parent);
		editor.updateToolbar();
		return;
	}
	*/
	
	// if we're already in a SPAN
	if (isSpan) {
		if (parent.childNodes.length == 1) {
			parent.className = className;
			surround = false;
			FTB_SetToolbarItems(ftbName);
		}
	}

	if (surround) {
		FTB_SurroundText(ftbName,"<span class='" + className + "'>", "</span>");
	}
}
function quotedme() {
txt=prompt("Quoted","Input what you want");
	if(txt!=null) {
		AddTxt="[quote]"+txt;
		FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		AddTxt="[/quote]";
		FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	}
}

function setfly() {

txt=prompt("Flying Text","Fly");
if (txt!=null) {
	AddTxt="[fly]"+txt;
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	AddTxt="[/fly]";
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
}

}
function setswf() {
txt2=prompt("Width,Height","400,300");
if (txt2!=null) {
	txt=prompt("Flash URL","http://");
	if (txt!=null) {
		if (txt2=="") {
			AddTxt="[flash=400,300]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/flash]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		} else {
			AddTxt="[flash="+txt2+"]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/flash]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		}
	}
}

}
function showcode() {
txt=prompt("Input Code","");
if (txt!=null) { 
	AddTxt="\r[code]"+txt;
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	AddTxt="[/code]";
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
}

}

function sound() {
txt=prompt("Real Media's URL","http://");
width=prompt("Real Media's Width","500");
height=prompt("Real Media's Height","300");
if(txt!=null) {
	AddTxt="\r[rm="+width+","+height+"]"+txt;
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	AddTxt="[/rm]";
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
}

}

function wmv() {
txt=prompt("Windows Media's URL","http://");
width=prompt("Windows Media's width","500");
height=prompt("Windows Media's height","300");
if(txt!=null) {
	AddTxt="\r[asf="+width+","+height+"]"+txt;
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	AddTxt="[/asf]";
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
}

}

function glow() {
txt2=prompt("Text's long & color & size","255,#ed1c24,2");
if (txt2!=null) {
	txt=prompt("Text.","Text");
	if (txt!=null) {
		if (txt2=="") {
			AddTxt="[glow=255,#ed1c24,2]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/glow]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		} else {
			AddTxt="[glow="+txt2+"]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/glow]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		}
	}
}

}

function shadow() {

txt2=prompt("Shadow Text's long & color & size","255,#ed1c24,1");
if (txt2!=null) {
	txt=prompt("Shadow Text","Text");
	if (txt!=null) {
		if (txt2=="") {
			AddTxt="[shadow=255, #ed1c24, 1]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/shadow]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		} else {
			AddTxt="[shadow="+txt2+"]"+txt;
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
			AddTxt="[/shadow]";
			FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
		}
	}
}

}

function movesign() {
txt=prompt("Moving Text","Text");
if (txt!=null) {
	AddTxt="[move]"+txt;
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
	AddTxt="[/move]";
	FTB_InsertText('_bmb_MainContent_textbox',AddTxt);
}

}