//Examine the browser
var userAgent = navigator.userAgent.toLowerCase();
var is_opera  = (userAgent.indexOf('opera') != -1);
var is_saf    = ((userAgent.indexOf('applewebkit') != -1) || (navigator.vendor == 'Apple Computer, Inc.'));
var is_webtv  = (userAgent.indexOf('webtv') != -1);
var is_ie     = ((userAgent.indexOf('msie') != -1) && (!is_opera) && (!is_saf) && (!is_webtv));
var is_ie4    = ((is_ie) && (userAgent.indexOf('msie 4.') != -1));
var is_ie7    = ((is_ie) && (userAgent.indexOf('msie 7.') != -1));
var is_moz    = ((navigator.product == 'Gecko') && (!is_saf));
var is_kon    = (userAgent.indexOf('konqueror') != -1);
var is_ns     = ((userAgent.indexOf('compatible') == -1) && (userAgent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_saf));
var is_ns4    = ((is_ns) && (parseInt(navigator.appVersion) == 4));
var is_mac    = (userAgent.indexOf('mac') != -1);

function dropdownmenu(obj) {
	var dropmenuobj=document.getElementById("dropmenudiv");
	if (!dropmenuobj || dropmenuobj==null) return;
	dropmenuobj.x=getposOffset(obj, "left");
	dropmenuobj.y=getposOffset(obj, "top");
	dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px";
	dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px";
	dropmenuobj.style.visibility='visible';
}

function clearbrowseredge(obj, whichedge) {
	var dropmenuobj=document.getElementById("dropmenudiv");
	if (!dropmenuobj || dropmenuobj==null) return 0;
	var edgeoffset=0;
	if (whichedge=="rightedge") {
		var windowedge=(is_ie4) ? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15;
		dropmenuobj.contentmeasure=dropmenuobj.offsetWidth;
		if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure) edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth;
	}
	else {
		var topedge=(is_ie4) ? iecompattest().scrollTop : window.pageYOffset;
		var windowedge=(is_ie4) ? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18;
		dropmenuobj.contentmeasure=dropmenuobj.offsetHeight;
		if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure) { //move up?
			edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight;
			if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
			edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge;
		}
	}
	return edgeoffset;
}

function iecompattest() {
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function getposOffset(what, offsettype) {
	var totaloffset=(offsettype=="left") ? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null)	{
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}

function delayhidemenu() {
	//delayhide=setTimeout("hidemenu()", 8000);
}

function delayhidemenulong() {
	//delayhide=setTimeout("hidemenu()", 50000);
}

function hidemenu(e) {
	var dropmenuobj=document.getElementById("dropmenudiv");
	if (dropmenuobj && dropmenuobj!=null) dropmenuobj.style.visibility="hidden";
}