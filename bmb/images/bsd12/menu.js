var sPop = null;
var postSubmited = false;

document.write("<style type='text/css' id='defaultPopStyle'>");
document.write(".cPopText { font-family: Tahoma, Verdana; background-color: #E2EFFC; border: 1px #000000 solid; font-size: 12px; padding-right: 4px; padding-left: 4px; height: 20px; padding-top: 2px; padding-bottom: 2px; visibility: hidden; filter: Alpha(Opacity=80)}");

document.write("</style>");
document.write("<div id='popLayer' style='position:absolute;z-index:1000' class='cPopText'></div>");


function showPopupText() {
	var o=event.srcElement;
	MouseX=event.x;
	MouseY=event.y;
	if(o.alt!=null && o.alt!="") { o.pop=o.alt;o.alt="" }
        if(o.title!=null && o.title!=""){ o.pop=o.title;o.title="" }
	if(o.pop!=sPop) {
		sPop=o.pop;
		if(sPop==null || sPop=="") {
			popLayer.style.visibility="hidden";	
		} else {
			if(o.dyclass!=null) popStyle=o.dyclass 
			else popStyle="cPopText";
			popLayer.style.visibility="visible";
			showIt();
		}
	}
}

function showIt() {
	popLayer.className=popStyle;
	popLayer.innerHTML=sPop.replace(/<(.*)>/g,"&lt;$1&gt;").replace(/\n/g,"<br>");;
	popWidth=popLayer.clientWidth;
	popHeight=popLayer.clientHeight;
	if(MouseX+12+popWidth>document.body.clientWidth) popLeftAdjust=-popWidth-24
		else popLeftAdjust=0;
	if(MouseY+12+popHeight>document.body.clientHeight) popTopAdjust=-popHeight-24
		else popTopAdjust=0;
	popLayer.style.left=MouseX+12+document.body.scrollLeft+popLeftAdjust;
	popLayer.style.top=MouseY+12+document.body.scrollTop+popTopAdjust;
}

function ctlent(obj) {
	if(postSubmited == false && (event.ctrlKey && window.event.keyCode == 13) || (event.altKey && window.event.keyCode == 83)) {
		if(this.document.input.pmsubmit) {
			postSubmited = true;
			this.document.input.pmsubmit.disabled = true;
			this.document.input.submit();
		} else if(validate(this.document.input)) {
			postSubmited = true;
			if(this.document.input.topicsubmit) this.document.input.topicsubmit.disabled = true;
			if(this.document.input.replysubmit) this.document.input.replysubmit.disabled = true;
			if(this.document.input.editsubmit) this.document.input.editsubmit.disabled = true;
			this.document.input.submit();
		}
	}
}

function checkall(form) {
	for(var i = 0;i < form.elements.length; i++) {
		var e = form.elements[i];
		if (e.name != 'chkall') e.checked = form.chkall.checked;
	}
}

function findobj(n, d) {
	var p,i,x; if(!d) d=document;
	if((p=n.indexOf("?"))>0 && parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document;
		n=n.substring(0,p);
	}
	if(!(x=d[n])&&d.all) x=d.all[n];
	for(i=0;!x && i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x && d.layers&&i>d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	return x;
}

function copycode(obj) {
	var rng = document.body.createTextRange();
	rng.moveToElementText(obj);
	rng.scrollIntoView();
	rng.select();
	rng.execCommand("Copy");
	rng.collapse(false);
}

document.onmouseover=showPopupText;
