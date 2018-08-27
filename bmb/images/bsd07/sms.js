window.onload = getMsg;
window.onresize = resizeDiv;
window.onerror = function(){}
var sdivTop,sdivLeft,sdivWidth,sdivHeight,sdocHeight,sdocWidth,sobjTimer,i = 0;
function getMsg()
{
	try{
	sdivTop = parseInt(document.getElementById("bmbsms").style.top,10)
	sdivLeft = parseInt(document.getElementById("bmbsms").style.left,10)
	sdivHeight = parseInt(document.getElementById("bmbsms").offsetHeight,10)
	sdivWidth = parseInt(document.getElementById("bmbsms").offsetWidth,10)
	sdocWidth = document.documentElement.clientWidth - 20;
	sdocHeight = document.documentElement.clientHeight;
	document.getElementById("bmbsms").style.top = parseInt(document.documentElement.scrollTop,10) + sdocHeight + 10;//  divHeight
	document.getElementById("bmbsms").style.left = parseInt(document.documentElement.scrollLeft,10) + sdocWidth - sdivWidth
	document.getElementById("bmbsms").style.visibility="visible"
	sobjTimer = window.setInterval("moveDiv()",10)
	}
	catch(e){}
}

function resizeDiv()
{
	i+=1
	if(i>500) closeDiv()
	try{
	sdivHeight = parseInt(document.getElementById("bmbsms").offsetHeight,10)

	sdivWidth = parseInt(document.getElementById("bmbsms").offsetWidth,10)
	sdocWidth = document.documentElement.clientWidth;
	sdocHeight = document.documentElement.clientHeight;

	document.getElementById("bmbsms").style.top = sdocHeight - sdivHeight + parseInt(document.documentElement.scrollTop,10)

	document.getElementById("bmbsms").style.left = sdocWidth - sdivWidth + parseInt(document.documentElement.scrollLeft,10) -20
	}
	catch(e){}
}

function moveDiv()
{
	try
	{
	if(parseInt(document.getElementById("bmbsms").style.top,10) <= (sdocHeight - sdivHeight + parseInt(document.documentElement.scrollTop,10)))
	{
	window.clearInterval(sobjTimer)
	sobjTimer = window.setInterval("resizeDiv()",1)
	}
	sdivTop = parseInt(document.getElementById("bmbsms").style.top,10)
	document.getElementById("bmbsms").style.top = sdivTop - 1
	}
	catch(e){}
}
function closeDiv()
{
	document.getElementById('bmbsms').style.visibility='hidden';
	if(sobjTimer) window.clearInterval(sobjTimer)
}