<?php
//-----------------------------------------------------------
// Blue Magic Board 蓝色魔法论坛 BMB
// 获取最新升级资讯： http://www.bmforum.com
// 版权所有 (C) 未经允许不得删除版权信息
// 官方站点： http://www.bmforum.com
//-----------------------------------------------------------
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(0);@header("Content-Type: text/html; charset=utf-8");
include("datafile/config.php");
require("include/db/db_{$sqltype}.php");
// ------------------- 以上信息勿动 -------------------------
$pluspostdir="C:/Apache/database/post/"; // Plus! 版帖子目录
$plususerdir="C:/Apache/database/user/"; // Plus! 版用户目录
$plusdatadir="C:/Apache/htdocs/bmb/datafile/"; // Plus! 版 datafile 目录
$maxcdev=1000; //每次转换主题数
//-----------------------------------------------------------
if(empty($_GET[step])){
?>
欢迎使用 BMForum Plus! to 2006 转换程序<br><br>
注意，安装 BMForum 2006 的时候请注册一个不存在的用户名，并在安装后删除所有已经存在的版块，再来运行本程序<BR><BR>
<b>请确认一下信息正确（在 PHP 文件头修改）</b><BR>
Plus! 帖子目录：<?php echo $pluspostdir?><br>
Plus! 用户目录：<?php echo $plususerdir?><br>
Plus! datafile 目录：<?php echo $plusdatadir?><br><br>
<font color=red>使用前请注意：必须保证 BMForum 2006 没有任何帖子、版块信息数据</font><br><br>
注意，本程序请放置于 2006 版根目录。<br><br><a href=plus2datium.php?step=2>点击这里开始转换版块数据</a>
<?php
}elseif($_GET[step]==2){
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);
	$adminlists=file($plusdatadir."/admin.php");
	for($i=0;$i<count($adminlists);$i++){
		$details=explode("|",$adminlists[$i]);
		$adminrow["$details[0]"].="$details[1]|";
	}
    $result = bmbdb_query("TRUNCATE `{$database_up}forumdata`;");
    $forumdata=file($plusdatadir."/forumdata.php");
    $count=count($forumdata);
    for($i=0;$i<$count;$i++){
    	$thisgroup="";
    	$detail=explode("|",$forumdata[$i]);
    	$detail[0]=str_replace("<?exit;?>","",$detail[0]);
    	if(file_exists($plusdatadir."/usergroup/".$detail[3].".php")) $thisgroup=implode("",file($plusdatadir."/usergroup/".$detail[3].".php"));
    	$thisgroup=addslashes($thisgroup);
    	for($x=0;$x<15;$x++) { $detail[$x]=str_replace("\n","",$detail[$x]); $detail[$x]=addslashes($detail[$x]); }
    	$result = bmbdb_query("INSERT INTO `{$database_up}forumdata` (`type`, `bbsname`, `cdes`, `id`, `blad`, `forum_icon`, `filename`, `forumpass`, `forum_cid`, `guestpost`, `forum_ford`, `postdontadd`, `spusergroup`, `naviewpost`, `adminlist`, `topicnum`, `replysnum`, `fltitle`, `flposttime`, `flposter`, `flfname`, `showorder`, `usergroup`, `caterows`, `todayp`, `todaypt`) VALUES ('{$detail[0]}', '{$detail[1]}', '{$detail[2]}', '{$detail[3]}', '{$detail[4]}', '{$detail[5]}', '{$detail[6]}', '{$detail[7]}', '{$detail[8]}', '{$detail[9]}', '{$detail[10]}', '{$detail[11]}', '{$detail[12]}', '{$detail[13]}', '{$adminrow[$detail[3]]}', 0, 0, '', '', '', '', $i, '$thisgroup', '',0,0);");
    }
echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>版块数据转换完毕<br><br><a href=plus2datium.php?step=21>点击这里开始联盟论坛数据(转换期间请不要动浏览器)</a>";
}elseif($_GET[step]==21){
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);
	$result = bmbdb_query("TRUNCATE `{$database_up}shareforum`;");
    $forumdata=file($plusdatadir."/shareforum.php");
    $count=count($forumdata);
    for($i=0;$i<$count;$i++){
    	$detail=explode("|",$forumdata[$i]);
    	for($x=0;$x<5;$x++) { $detail[$x]=str_replace("\n","",$detail[$x]); $detail[$x]=addslashes($detail[$x]); }
    	$result = bmbdb_query("INSERT INTO `{$database_up}shareforum` VALUES ('{$detail[0]}', '{$detail[1]}', '{$detail[2]}', '{$detail[3]}', '{$detail[4]}','$i');");
    }
echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>联盟论坛数据转换完毕<br><br><a href=plus2datium.php?step=22>点击这里开始转换用户组数据(转换期间请不要动浏览器)</a>";
}elseif($_GET[step]==22){
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);
	$result = bmbdb_query("TRUNCATE `{$database_up}usergroup`;");
    $forumdata=file($plusdatadir."/usergroup.php");
    $admindata=file($plusdatadir."/admingroup.php");
    include($plusdatadir."/groupinfo.php");
    $count=count($forumdata);
    for($i=0;$i<$count;$i++){
    	$forumdata[$i]=addslashes($forumdata[$i]);
    	if($usergroupreg==$i) $thisreg=1; else $thisreg=0;
    	$result = bmbdb_query("INSERT INTO `{$database_up}usergroup` VALUES ('{$i}', '{$forumdata[$i]}', '{$admindata[$i]}', '', '','$thisreg');");
    }
echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>用户组数据转换完毕<br><br><a href=plus2datium.php?step=3>点击这里开始转换用户数据(转换期间请不要动浏览器)</a>";
}elseif($_GET[step]==3){
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);
	$result = bmbdb_query("TRUNCATE `{$database_up}userlist`;");
	$result = bmbdb_query("TRUNCATE `{$database_up}posts`;");
	$result = bmbdb_query("TRUNCATE `{$database_up}threads`;");
	$dh=opendir($plususerdir);
	$newlinewri = "<?php
 \n";
	while ($userfile=readdir($dh)) {
		if (($userfile!=".") && ($userfile!="..") && !strpos($userfile,".snd") && !strpos($userfile,".frd") && !strpos($userfile,".add") && !strpos($userfile,".rec")) {
			$d=explode("|",readfromfile($plususerdir.$userfile));
			$count=count($d);
			for($i=0;$i<$count;$i++) { $d[$i]=str_replace("\n","",$d[$i]); $d[$i]=addslashes($d[$i]); }
			// Write to database ==>\\
			$d[0]=str_replace("<?exit;?>","",$d[0]);
			$nquery = "insert into {$database_up}userlist (username,pwd,avarts,mailadd,qqmsnicq,regdate,signtext,homepage,fromwhere,desper,headtitle,lastpost,postamount,publicmail,mailtype,point,pwdask,pwdanswer,ugnum,money,birthday,team,sex,national) 
values ('{$d[0]}','{$d[1]}','{$d[2]}','{$d[3]}','{$d[4]}','{$d[5]}','{$d[6]}','{$d[7]}','{$d[8]}','{$d[9]}','{$d[10]}','{$d[11]}','{$d[12]}','{$d[13]}','{$d[14]}','{$d[15]}','{$d[16]}','{$d[17]}','{$d[18]}','{$d[19]}','{$d[20]}','{$d[21]}','{$d[22]}','{$d[23]}')";
			$result = bmbdb_query($nquery);
			$newlineno = bmbdb_insert_id();
			$newlinewri.="\$cacheusrid[\"".$d[0]."\"]=$newlineno ;\n";
		}elseif(strpos($userfile,".frd")){
			rename("$plususerdir/$userfile", "friend/".str_replace(".frd","",$userfile).".php");
		}
	}
	closedir($dh);
	writetofile("d122.php",$newlinewri);
echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>用户资料转换完毕<br><br><a href=plus2datium.php?step=4>点击这里开始转换版块数据(转换期间请不要动浏览器)</a>";
}elseif($_GET[step]==4){
	include("d122.php");
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);
	$id=$_GET['id'];
	$lastid=$id;
	if($id==""){
		$forumdata=file($plusdatadir."/forumdata.php");
	    $count=count($forumdata);
	    for($i=0;$i<$count;$i++){
	    	$detail=explode("|",$forumdata[$i]);
	    	$detail[0]=str_replace("<?exit;?>","",$detail[0]);
	    	if($detail[0]!="category"){
	    		$id=$detail[3];$new="yes";
	    		break;
	    	}
		}
	}
	if($id!=""){
		if($new!="yes"){
			$forumdata=file($plusdatadir."/forumdata.php");
		    $count=count($forumdata);
		    for($i=0;$i<$count;$i++){
		    	$detail=explode("|",$forumdata[$i]);
		    	$detail[0]=str_replace("<?exit;?>","",$detail[0]);
		    	if($detail[0]!="category" && $detail[3]==$id){
		    		$nd=explode("|",$forumdata[$i+1]);
		    		$nd[0]=str_replace("<?exit;?>","",$nd[0]);
		    		if($nd[0]!="category"){
		    			$id=$nd[3];
		    			break;
		    		}elseif($nd[0]=="category"){
		    			$xd=explode("|",$forumdata[$i+2]);
		    			$xd[0]=str_replace("<?exit;?>","",$xd[0]);
		    			if($xd[0]!="category"){
		    				$id=$xd[3];
		    				break;
		    			}
		    		}
		    	}
			}
		}
		
		$last=explode("|",$forumdata[$count]);
		$last=$last[3];
		if($last==$id && $new!="yes"){
			refresh_forumcach();
			@chmod("d122.php",0777);
			echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>全部转换完成<br><br><b>请注意登录新论坛，设置常规选项和用户组的新选项，否则将可能无法发帖或浏览。</b><br><br>若有问题，请访问官方论坛寻求答案或自行研究解决。";
			if(!@unlink("d122.php")) echo "<br>d122.php 文件删除失败，请利用FTP自主删除";
			exit;
		}
		
		$query = "SELECT * FROM {$database_up}posts ORDER BY `id` DESC  LIMIT 0,1 ";
		$result = bmbdb_query($query);
		$linexx = bmbdb_fetch_array($result);
		$newlineno=$linexx['id']+1; 
		
		bmbdb_connect($db_server,$db_username,$db_password,$db_name);
		$fileindex=file("$pluspostdir/forum{$id}/list.php");
		@rename("$pluspostdir/forum{$id}/announcement.php","datafile/announcement{$id}.php");
		$countindex=count($fileindex);
		$statcd=0;
		if($_GET['left']==1) { $ints=$_GET['lasti']; } else { $ints=0; }
		for($s=$ints;$s<$countindex;$s++){
			$details=explode("|",$fileindex[$s]);
			if (ereg("^f_[0-9]+$",$details[5])) {
				$postfile=$details[5];
				if($statcd==$maxcdev){
					$id=$lastid;
					$ifleft=1;
					break;
				}
				$statcd++;
				$pfile=$pluspostdir."forum$id/".$postfile;
				$d=@file($pfile);
				$count=count($d);
				$replys=$count-1;
				
				$tmp=explode("|",addslashes($d[$replys])); 
				
				$lastreply=$tmp[0].",".$tmp[1].",".$tmp[3];
				$changetime=$tmp[3];
					
				for($i=0;$i<$count;$i++){ 
					$tmp=explode("|",addslashes($d[$i]));
					$tmp[0] = str_replace("%a%", "", $tmp[0]);
					if($i==0){
						$nquery = "insert into {$database_up}threads (id,tid,toptype,ttrash,lastreply,topic,forumid,hits,replys,changetime,itsre,type,islock,title,newdesc,author,content,time,ip,face,options,other1,other2,other3,other4,other5,statdata,addinfo,alldata) values ('$newlineno','$newlineno','','','$lastreply','','$id',0,'$replys','$tmp[3]','','$details[10]','$details[9]','$tmp[0]','','$tmp[1]','$tmp[2]','$tmp[3]','$tmp[4]','$tmp[5]','$tmp[6]','$tmp[7]','$tmp[8]','$tmp[9]','$tmp[10]','$tmp[11]','','','')";
						$result = bmbdb_query($nquery);
						$nquery = "insert into {$database_up}posts (id,usrid,tid,articletitle,username,ip,usericon,options,other1,other2,other3,other4,other5,addin,articlecontent,timestamp,forumid,changtime) values ('$newlineno','".$cacheusrid["$tmp[1]"]."','$newlineno','$tmp[0]','$tmp[1]','$tmp[4]','$tmp[5]','$tmp[6]','$tmp[7]','$tmp[8]','$tmp[9]','$tmp[10]','$tmp[11]','','$tmp[2]','$tmp[3]','$id','$tmp[3]')";
						$result = bmbdb_query($nquery);
						$thisfilename=$newlineno;
					}else{
						$nquery = "insert into {$database_up}posts (id,usrid,tid,articletitle,username,ip,usericon,options,other1,other2,other3,other4,other5,addin,articlecontent,timestamp,forumid,changtime) values ('$newlineno','".$cacheusrid["$tmp[1]"]."','$thisfilename','$tmp[0]','$tmp[1]','$tmp[4]','$tmp[5]','$tmp[6]','$tmp[7]','$tmp[8]','$tmp[9]','$tmp[10]','$tmp[11]','','$tmp[2]','$tmp[3]','$id','$tmp[3]')";
						$result = bmbdb_query($nquery);
					}
					
					$newlineno++;
				}
			}
		}
		echo "欢迎使用 BMForum Plus! to 2006 转换程序<br><br>版块 ID:$id 转换完毕 ENDWITH:$s<br><br><a href=plus2datium.php?step=4&id=$id&lasti=$s&left=$ifleft>如果没有跳转,点击这里开始转换下一个版块数据(转换期间请不要动浏览器)</a><script>window.location='plus2datium.php?step=4&id=$id&lasti=$s&left=$ifleft';</script>";
		exit;
	}
}
function readfromfile($file_name) {
	if (file_exists($file_name)) {
		$filenum=fopen($file_name,"r");
		flock($filenum,LOCK_SH);
		$file_data=@fread($filenum,@filesize($file_name));
		fclose($filenum);
		return $file_data;
	}
}
function writetofile($file_name,$data,$method="w") {
	$filenum=fopen($file_name,$method);
	flock($filenum,LOCK_EX);
	$file_data=fwrite($filenum,$data);
	fclose($filenum);
	return $file_data;
}
function refresh_forumcach(){
global $database_up;
	$nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
	$nresult = bmbdb_query($nquery);
	while (false!==($fourmrow = bmbdb_fetch_array($nresult))) {
		$xfourmrow[]=$fourmrow;
	}
	$wrting="<?php
 \n";
	$count=count($xfourmrow);
	for ($i=0; $i<$count; $i++) {
		$tmp=$xfourmrow[$i];
		foreach ($tmp as $key => $value) {
	    	$wrting.="\$sxfourmrow[$i]['{$key}']='".str_replace("'","\'",$value)."'; \n";
		}
		$forumscount++;
	}
	$wrting.="\$forumscount='$forumscount';";
	writetofile("datafile/cache/forumdata.php",$wrting);
	
    // Refresh Cache
    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
    $result = bmbdb_query($query);
    $ugsocount = "";
    $wrting = "<?php
 ";
    while (false !== ($line = bmbdb_fetch_array($result))) {
        $line['usersets'] = str_replace('"', '\"', $line['usersets']);
        $wrting .= "
		\$usergroupdata[{$line['id']}]=\"{$line['usersets']}\";
		\$unshowit[{$line['id']}]=\"{$line['unshowit']}\";
		\$ugshoworder[]=\"{$line['id']}\";
		";
        $ugsocount++;
    } 
    $wrting .= "\$ugsocount='$ugsocount';";
    writetofile("datafile/cache/usergroup.php", $wrting);
    
    // Javascript cached forum links
    $nquery = "SELECT * FROM {$database_up}shareforum ORDER BY 'showorder' ASC";
    $nresult = bmbdb_query($nquery);
    while (false !== ($fourmrow = bmbdb_fetch_array($nresult))) {
        $fourmrow[url] = str_replace('"', '\"', $fourmrow[url]);
        $fourmrow[name] = str_replace('"', '\"', $fourmrow[name]);
        $fourmrow[des] = str_replace('"', '\"', $fourmrow[des]);
        $fourmrow[gif] = str_replace('"', '\"', $fourmrow[gif]);
        if ($fourmrow[type] == "pic") {
            $sharepic .= "document.write(\"<a href='$fourmrow[url]' target=_blank><img  width=88 height=31 alt='$fourmrow[name] - $fourmrow[des]' src='$fourmrow[gif]' border=0></a>&nbsp;            \");\n";
        } else {
            $sharetext .= "document.write(\"<a title='$fourmrow[name] - $fourmrow[des]' href='$fourmrow[url]' target=_blank>$fourmrow[name]</a>&nbsp;             \");\n";
        } 
    } 

    writetofile("datafile/cache/sharetext.js", $sharetext);
    writetofile("datafile/cache/sharepic.js", $sharepic);
}