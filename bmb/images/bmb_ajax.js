/**
BMForum AJAX Script
Copyright (C) Bluview Technology
*/
var userAgent = navigator.userAgent.toLowerCase();
var browserId = userAgent.match(/(firefox|chrome|safari|opera|msie)/)[1];
var browserVersion = (userAgent.match(new RegExp('.+(?:version)[\/: ]([\\d.]+)')) || userAgent.match(new RegExp('(?:'+browserId+')[\/: ]([\\d.]+)')) || [0,'0'])[1];
var isIe6 = (browserId + browserVersion == "msie6.0");
var ajaxed = new Array(); 
var ajaxinfo = new Array(); 
var ajaxtmp = new Array();
var tname = new Array();
ajaxtmp['times'] = Date.parse(new Date());
/**
Make a request of AJAX
*/
function makeRequest(url,data,funname,httptype) {
    http_request = false;
    
    if (!httptype) httptype = "GET";

    if (window.XMLHttpRequest) { // If IE7, Mozilla, Safari, etc: Use native object
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/xml');
        }
    } else if (window.ActiveXObject) { // ...otherwise, use the ActiveX control for IE5.x and IE6
        try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }

    if (!http_request) {
        alert('Cannot Create an XMLHttp request');
        return false;
    }
    http_request.onreadystatechange = funname;
    http_request.open(httptype, url, true);
    http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    http_request.send(data);

}
/**
Debug Alert Function
*/
function alertContents() {

    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            alert(http_request.responseText);
        } else {
            display_error_box(http_request.responseText);
        }
    }
}
/**
Modify Thread Title
*/
function bmb_ajax_modifytitle_back() {

    if (http_request.readyState == 4) {
        filename = ajaxtmp['filename'];
        if (http_request.status == 200) {
            document.getElementById("t"+filename).innerHTML = "<span id='t"+filename+"'><a id='forum' onfocus='this.blur()' href='"+filetopn+"?filename="+filename+"'>" +http_request.responseText+"</a></span>";
            tname[filename] = http_request.responseText;
        } else {
            display_error_box(http_request.responseText);
        }
        ajaxed[filename] = 0;
        ajaxtmp['filename'] = "";
    }
}
function bmb_ajax_tabletitle(threadtitle,filename,sublang,filetopn){
    if (ajaxed[filename] == 1) {
        document.getElementById("t"+filename).innerHTML = ajaxinfo[filename];
        ajaxed[filename] = 0;
    }else{
        ajaxed[filename] = 1;
        ajaxinfo[filename] = document.getElementById("t"+filename).innerHTML;
        document.getElementById("t"+filename).innerHTML = "<input onkeydown='javascript: if((event.ctrlKey && event.keyCode==13) || (event.altKey && event.keyCode == 83)) { document.getElementById(\"s"+filename+"\").click(); }' size='35' type='text' value='"+tname[filename]+"' id='title"+filename+"'>  <input onclick=\"ajaxtmp['filename']="+filename+";this.disabled='disabled';makeRequest('plugins.php?p=ajax&act=modifytitle&filetopn="+filetopn+"&filename="+filename+"&newtitle='+bmb_ajax_encode(document.getElementById('title"+filename+"').value),null,bmb_ajax_modifytitle_back,'');\" type='button' value='"+sublang+"' name='s"+filename+"' id='sub"+filename+"'>";
    }
}
/**
Turn floor
*/
function gotofloor(object,filename,replies,perpage,alertinfo) {
		object = document.getElementById(object);
        if (object.value >= 1 && (object.value-1) <= replies) {
            page = Math.floor((object.value-1)/perpage)+1;
            window.location = "topic.php?filename="+filename+"&page="+page+"#floor"+object.value;
        } else {
            display_error_box(alertinfo);
        }
}
function ajax_gotofloor(object,filename,replies,alertinfo,perpage) {
		object = document.getElementById(object);
        if (object.value >= 1 && (object.value-1) <= replies) {
            page = Math.floor((object.value-1)/perpage)+1;
            bmb_ajax_displayfloor(object.value,filename,page);
        } else {
            display_error_box(alertinfo);
        }
}
function bmb_ajax_displayfloor_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            document.getElementById("posts").innerHTML = http_request.responseText;
            document.getElementById("multi_page_bar").innerHTML = ajaxtmp['oldinner'];
        } else {
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_displayfloor(floornum,filename,page){
    ajaxtmp['times'] = ajaxtmp['times']+1;
    ajaxtmp['oldinner'] = document.getElementById("multi_page_bar").innerHTML;
    document.getElementById("multi_page_bar").innerHTML = exchanging;
    makeRequest("topic.php?ajax_display=1&rnd="+ajaxtmp['times']+"&filename="+filename+"&floor="+floornum,null,bmb_ajax_displayfloor_back,"GET");
    if (script_rewrite == "1") {
        document.getElementById("pageurladdress").value = script_pos+"/topic_"+filename+"_"+page+"#floor"+floornum;
    } else {
        document.getElementById("pageurladdress").value = script_pos+"/topic.php?filename="+filename+"&page="+page+"#floor"+floornum;
    }
}
/**
Turn pages
*/
function ajax_gotopages(eventobject,object,filename) {
    if(eventobject.keyCode == 13) {
        bmb_ajax_displaypost(object.value,filename);
    }
}
function bmb_ajax_displaypost_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText.split('<div class="bmbajaxpagebar"></div>');
            document.getElementById("posts").innerHTML = result[0];
            if (ajaxtmp['nopagebarchange']!=1) {
                document.getElementById("multi_page_bar").innerHTML = result[1];
                document.getElementById("multi_page_bar_2").innerHTML = result[1];
            } else {
                document.getElementById("multi_page_bar").innerHTML = "";
                ajaxtmp['nopagebarchange']=0;
            }
        } else {
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_displaypost(pagenum,filename){
    ajaxtmp['times'] = ajaxtmp['times']+1;
    document.getElementById("multi_page_bar").innerHTML = exchanging;
    makeRequest("topic.php?ajax_display=1&rnd="+ajaxtmp['times']+"&filename="+filename+"&page="+pagenum,null,bmb_ajax_displaypost_back,"GET");
    if (script_rewrite == "1") {
        document.getElementById("pageurladdress").value = script_pos+"/topic_"+filename+"_"+pagenum;
    } else {
        document.getElementById("pageurladdress").value = script_pos+"/topic.php?filename="+filename+"&page="+pagenum;
    }
}
/**
Reply Thread
*/
function bmb_ajax_replythread(formobj){
    document.getElementById("quicksubmit").disabled="disabled";
    submitstring = "ajax_reply=1";
    for (i = 0; i < formobj.elements.length; i++)
    {
        var obj = formobj.elements[i];
        if (obj.name && !obj.disabled)
        {
            if (obj.type != "checkbox") {
                submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            } else {
                if (obj.checked==1) submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            }
        }
    }
    ajaxtmp['filename'] = formobj.filename.value;
    makeRequest("post.php",submitstring,bmb_ajax_display_ajax,"POST");
}
function bmb_ajax_display_ajax(){
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText;
            makeRequest("topic.php?ajax_display=1&pid="+result+"&filename="+ajaxtmp['filename']+"&page=last",null,bmb_ajax_displaypost_reply_back,"GET");
            document.getElementById("resetform").click();
            clckcnt=0;
        } else {
            document.getElementById("quicksubmit").disabled="";
            display_error_box(http_request.responseText);
            clckcnt=0;
        }
    }
}
function bmb_ajax_displaypost_reply_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText;
            document.getElementById("posts").innerHTML += result;
            document.getElementById("quicksubmit").disabled="";
        } else {
            clckcnt=0;
            document.getElementById("quicksubmit").disabled="";
            display_error_box(http_request.responseText);
        }
    }
}
/**
AJAX Online List Refresh
*/
function bmb_ajax_online_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            document.body.style.cursor = '';
            result = http_request.responseText;
            document.getElementById("onlinelist").innerHTML = result;
        } else {
            document.body.style.cursor = '';
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_online(filename){
    document.body.style.cursor = 'wait';
    makeRequest(filename,null,bmb_ajax_online_back,"GET");
}
function bmb_ajax_preprocess(urlname,addlinks,allow_ajax_browse){
    ajaxtmp['times']=ajaxtmp['times']+1;
    if (allow_ajax_browse==1) {
        bmb_ajax_online(urlname+addlinks+"ajax_online=1&rnd="+ajaxtmp['times']+"&online_show="+online_show_anti);
    }else{
        window.location=urlname+addlinks+"online_show="+online_show_anti+"#onlinearea";
    }
    
    if (online_show_anti == "show") {
        online_show_anti = "hide";
    } else {
        online_show_anti = "show";
    }
}
/**
Modify Content
*/
function bmb_ajax_modifycontent(filename){
    document.body.style.cursor = 'wait';

    submitstring = "ajax_content=1";
    submitstring = "&newcontent="+bmb_ajax_encode(document.getElementById("c"+filename).value);
    
    makeRequest("plugins.php?p=ajax&act=modifycontent&filename="+filename,submitstring,bmb_ajax_modifycontent_back,"POST");
}
function bmb_ajax_modifycontent_back() {

    if (http_request.readyState == 4) {
        filename = ajaxtmp['filename'];
        if (http_request.status == 200) {
            document.body.style.cursor = '';
            document.getElementById('sub'+filename).disabled='';
            document.getElementById("text"+filename).innerHTML = http_request.responseText;
            ajaxed[filename] = 0;
            ajaxtmp['filename'] = "";
        } else {
            document.getElementById('sub'+filename).disabled='';
            ajaxtmp['times']++;
            document.body.style.cursor = '';
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_getcontent(filename){
    document.body.style.cursor = 'wait';
    makeRequest("plugins.php?p=ajax&times="+ajaxtmp['times']+"&act=getcontent&filename="+filename,null,bmb_ajax_getcontent_back,"GET");
}
function bmb_ajax_getcontent_back() {

    if (http_request.readyState == 4) {
        filename = ajaxtmp['filename'];
        if (http_request.status == 200) {
            document.body.style.cursor = '';
            document.getElementById("text"+filename).innerHTML = "<textarea onkeydown='javascript: if((event.ctrlKey && event.keyCode==13) || (event.altKey && event.keyCode == 83)) { document.getElementById(\"sub"+filename+"\").click(); }' cols='90' rows='20' id='c"+filename+"'>"+http_request.responseText+"</textarea><br /><input onclick=\"this.disabled='disabled';bmb_ajax_modifycontent("+filename+");\" type='button' value='"+ajaxtmp['sublang']+"' id='sub"+filename+"''>";
        } else {
            document.body.style.cursor = '';
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_tablecontent(filename,sublang){
    if (ajaxed[filename] == 1) {
        document.getElementById("text"+filename).innerHTML = ajaxinfo[filename];
        ajaxed[filename] = 0;
    }else{
        ajaxed[filename] = 1;
        ajaxtmp['filename'] = filename;
        ajaxtmp['times'] = ajaxtmp['times']+1;
        ajaxtmp['sublang'] = sublang;
        ajaxinfo[filename] = document.getElementById("text"+filename).innerHTML;
        bmb_ajax_getcontent(filename);
    }
}
/**
Make Poll
*/
function bmb_ajax_makepoll(){
    formobj = document.getElementById("pollform");
    document.getElementById("quickpoll").disabled="disabled";
    submitstring = "ajax_poll=1";
    for (i = 0; i < formobj.elements.length; i++)
    {
        var obj = formobj.elements[i];
        if (obj.name && !obj.disabled)
        {
            if (obj.type != "checkbox" && obj.type != "radio") {
                submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            } else {
                if (obj.checked==1) submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            }
        }
    }
    document.body.style.cursor = 'wait';
    ajaxtmp['filename'] = formobj.filename.value;
    makeRequest("vote.php",submitstring,bmb_ajax_makepoll_ajax,"POST");
}
function bmb_ajax_makepoll_ajax(){
    if (http_request.readyState == 4) {
        if (http_request.status == 200 && http_request.responseText == "1") {
            ajaxtmp['times']=ajaxtmp['times']+1;
            makeRequest("topic.php?display_poll=1&rnd="+ajaxtmp['times']+"&ajax_display=1&pid="+ajaxtmp['filename']+"&filename="+ajaxtmp['filename'],null,bmb_ajax_makepoll_reply_back,"GET");
        } else {
            document.getElementById("quickpoll").disabled="";
            document.body.style.cursor = '';
            display_error_box(http_request.responseText);
        }
    }
}
function bmb_ajax_makepoll_reply_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText;
            document.getElementById("poll_content").innerHTML = result;
        } else {
            document.getElementById("quickpoll").disabled="";
            display_error_box(http_request.responseText);
        }
    }
    document.body.style.cursor = '';
}
/**
AJAX Scores
*/
function display_score_box(b_page,b_forumid,b_filename,b_pid)
{
    ajaxtmp['score_page']=b_page;
    ajaxtmp['score_tid']=b_filename;
    document.body.style.cursor = 'wait';
    makeRequest("misc.php?p=byms&ajax_request=1&page="+b_page+"&forumid="+b_forumid+"&filename="+b_filename+"&article="+b_pid,"",display_score_box_back,"GET");
}
function display_score_box_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText;
            $("#ajax_scores_detail").html(result);
            $('#ajax_scores_div').modal('show')
            //document.getElementById("ajax_scores_div").style.top=( document.documentElement.scrollTop==0?document.body.scrollTop:document.documentElement.scrollTop)+"px";
            //document.getElementById("ajax_scores_div").style.visibility="visible";
            document.getElementById("quickbym").disabled="";
        } else {
            display_error_box(http_request.responseText);
        }
    }
    document.body.style.cursor = '';
}
function submit_score_box()
{
    document.body.style.cursor = 'wait';
    document.getElementById("quickbym").disabled="disabled";

    formobj = document.getElementById("bymform");
    submitstring = "ajax_request=1";

    for (i = 0; i < formobj.elements.length; i++)
    {
        var obj = formobj.elements[i];
        if (obj.name && !obj.disabled && obj.type != "select")
        {
            if (obj.type != "checkbox" && obj.type != "radio") {
                submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            } else {
                if (obj.checked==1) submitstring += '&' + obj.name + '=' + bmb_ajax_encode(obj.value);
            }
        }
    }
    makeRequest("misc.php?p=byms",submitstring,submit_score_box_back,"POST");
}
function submit_score_box_back() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            result = http_request.responseText;
            $('#ajax_scores_div').modal('hide');
            ajaxtmp['nopagebarchange'] = 1;
            bmb_ajax_displaypost(ajaxtmp['score_page'],ajaxtmp['score_tid']);
        } else {
            $('#ajax_scores_div').modal('hide');
            display_error_box(http_request.responseText);
        }
    }

    document.body.style.cursor = '';
}

function show_msg_content(id) {
    if (document.getElementById('BMF_message_content_'+id).style.display == 'block') {
        close_show_msg_content(id);
    } else {
        ajaxtmp['show_msg_id'] = id;
        makeRequest('messenger.php?job=ajax&msg='+id,null,handle_show_msg_content, 'GET');
    }
}

function handle_show_msg_content() {
    
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
		    var response = http_request.responseText;
            document.getElementById('BMF_message_content_'+ajaxtmp['show_msg_id']).innerHTML = response;
            document.getElementById('BMF_message_content_'+ajaxtmp['show_msg_id']).style.visibility = '';
            document.getElementById('BMF_message_content_'+ajaxtmp['show_msg_id']).style.display = 'block'; 
            document.getElementById('BMF_message_toolbar_'+ajaxtmp['show_msg_id']).style.visibility = '';
            document.getElementById('BMF_message_toolbar_'+ajaxtmp['show_msg_id']).style.display = 'block';
        } else {
            display_error_box(http_request.responseText);
        }
    }
}

function close_show_msg_content(id){
    document.getElementById('BMF_message_content_'+id).style.visibility = 'hidden';
    document.getElementById('BMF_message_content_'+id).style.display = 'none';  
    document.getElementById('BMF_message_toolbar_'+id).style.visibility = 'hidden';
    document.getElementById('BMF_message_toolbar_'+id).style.display = 'none';
}

function digg_thread(id,imgpath) {
	ajaxtmp['diggorg'] = document.getElementById("diggs").innerHTML;
	document.getElementById("diggs").innerHTML = "<img src='"+imgpath+"/digg/loading.gif' border='0' alt='' title='' />";
	makeRequest('misc.php?p=digg&action=digg&filename='+id,null,handle_digg_thread, 'GET');
}
function handle_digg_thread() {
    if (http_request.readyState == 4) {
        document.getElementById("diggs").innerHTML = ajaxtmp['diggorg'];
        if (http_request.status == 200) {
        	document.getElementById('diggs-strong').innerHTML = http_request.responseText;
        } else {
            display_error_box(http_request.responseText);
        }
    }
}
/**
AJAX Encode URL
*/
function bmb_ajax_encode (str) { //encode string
    str=encodeURIComponent(str);
    if (navigator.product == 'Gecko') str=str.replace(/%0A/g, "%0D%0A"); //In IE, a new line is encoded as rn, while in Mozilla it's n
    return str;
}
function display_error_box(errmsg)
{
    document.getElementById("ajax_error_detail").innerHTML=errmsg;
    $('#ajax_information').fadeIn();
    $("html,body").animate({scrollTop: $("#ajax_information").offset()}, 1000);
}
function checkbatch(object, batem) {
	o_batem = document.getElementById(batem);
	if(!o_batem.checked) {
		$(o_batem).attr("checked", true);
		$(object).attr("class", "bottom_button_batch");
	} else {
		$(o_batem).attr("checked", false);
		$(object).attr("class", "batquoteselect bottom_button");
	}
}
function init_checkMessage(messages, newpm, newnotify) {
	if(messages>0) {
		$(document).attr("title", '('+messages+') '+$(document).attr("fulltitle"));
		$("#head_totalnewmess").html('('+messages+')');
	} else {
		$(document).attr("title", $(document).attr("fulltitle"));
		$("#head_totalnewmess").html('');
	}
	if(newpm != -1) {
		$("#head_newpm").html(newpm > 0 ? '('+newpm+')': '');
		$("#head_newnotify").html(newnotify > 0 ? '('+newnotify+')' : '');
	}
	setTimeout("checkmessage();", 30000);
}
function checkmessage() {
	ajaxtmp['times'] = ajaxtmp['times']+1;
	makeRequest('plugins.php?p=ajax&act=checkmessages&rnd='+ajaxtmp['times'],null,handle_checkmessage, 'GET');
}
function handle_checkmessage() {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
        	data = eval( "(" + http_request.responseText + ")" );
        	if (data['succ']) {
        		totalMessage = data['notify'] + data['pm'];
        		init_checkMessage(totalMessage, data['pm'], data['notify']);
        	}
        } else {
            setTimeout("checkmessage();", 60000);
        }
    }
}
vCard = function($) {
	var mouseCheck = new Array();

	function getvCard(element, uid, username) {
		if(!uid && !username) {
			return;
		}
		if($("div[id='vcard_"+uid+"_"+username+"']").html()) {
			displayvCard(element, uid, username);
		} else {
			ajaxtmp['times'] = ajaxtmp['times']+1;
			makeRequest('plugins.php?p=ajax&act=vcard&uid='+uid+'&username='+bmb_ajax_encode(username)+'&rnd='+ajaxtmp['times'],null,function() {
				if (http_request.readyState == 4 && http_request.status == 200) {
					$(http_request.responseText).appendTo("#appendArea");
					displayvCard(element, uid, username);
				}
			}, 'GET');
		}
	}
	function displayvCard(element, uid, username) {
		sourcepos = $(element).position();
		sourceoffset = $(element).offset();
		cardpost = $(element).attr('cardpos') ? 1 : 0; // 0 左右结构 1 上下结构
		offtop = sourcepos.top;
		offleft = sourcepos.left;
		if(!cardpost) {
			$("div[id='vcard_"+uid+"_"+username+"']").css({'top': sourcepos.top, 'left': sourcepos.left + $(element).width() + 20});
		} else {
			if(sourcepos.top + $("div[id='vcard_"+uid+"_"+username+"']").height() > $(window).height() + $(window).scrollTop()) {
				offtop = sourcepos.top - $("div[id='vcard_"+uid+"_"+username+"']").height() - 10;
			} else {
				offtop = sourcepos.top + $(element).height() + 10;
			}
			if(sourcepos.left + $("div[id='vcard_"+uid+"_"+username+"']").width() > $(window).width() - 10) {
				offleft = sourcepos.left - $("div[id='vcard_"+uid+"_"+username+"']").width() + $(element).width();
			}
			$("div[id='vcard_"+uid+"_"+username+"']").css({'top': offtop, 'left': offleft});
		}
		clearTimeout(mouseCheck[uid+"_"+username]);
		mouseCheck[uid+"_"+username] = null;
		$(".vcard").each(function() {
			vuid = $(this).attr('uid') !== undefined ? $(this).attr('uid') : '';
			vusername = $(this).attr('username') !== undefined ? $(this).attr('username') : '';
			cardId = "vcard_"+uid+"_"+username;
			if($(this).attr('id') != cardId) {
				togglevCard(vuid, vusername);
			}
		});
		togglevCard(uid, username,1);
	}
	function togglevCard(uid, username, toggle)
	{
		if(toggle) {
			$("div[id='vcard_"+uid+"_"+username+"']").fadeIn("fast");
		} else {
			$("div[id='vcard_"+uid+"_"+username+"']").fadeOut("fast");
		}
	}
	/*
		Global 
	*/
	$(".userinfoarea").live({
	  mouseenter: function() {
		getvCard($(this), $(this).attr('uid') !== undefined ? $(this).attr('uid') : '', $(this).attr('username') !== undefined ? $(this).attr('username') : '');
	  }
	});
	$(".vcard,.userinfoarea").live({
		mouseenter: function(e) {
			uid = $(this).attr('uid') !== undefined ? $(this).attr('uid') : '';
			username = $(this).attr('username') !== undefined ? $(this).attr('username') : '';
			clearTimeout(mouseCheck[uid+"_"+username]);
			mouseCheck[uid+"_"+username] = null;
		},
		mouseleave: function(e) {
			uid = $(this).attr('uid') !== undefined ? $(this).attr('uid') : '';
			username = $(this).attr('username') !== undefined ? $(this).attr('username') : '';
			if(!mouseCheck[uid+"_"+username]) {
				//mouseCheck[uid] = setTimeout(togglevCard, 100, uid); //也可以
				mouseCheck[uid+"_"+username] = setTimeout(function (t,u){ return function() {togglevCard(t,u);}}(uid, username), 400);
			}
		}
	});
};
GlobalCard = new vCard(jQuery);