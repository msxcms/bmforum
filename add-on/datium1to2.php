<?php
//-----------------------------------------------------------
// Blue Magic Board 蓝色魔法论坛 BMB (又名BMForum)
// 获取最新升级资讯： http://www.bmforum.com
// 版权所有 (C) 未经允许不得删除版权信息
// 官方站点： http://www.bmforum.com
//-----------------------------------------------------------
require("datafile/config.php");
require("include/db/db_{$sqltype}.php");


/*
设置 $everytime 来确定每次提取用户数量
设置 $everypost 来确定每次设置帖子作者ID数量
*/
$everytime = 5000;
$everypost = 50;
/*      */
set_time_limit (0);
ob_implicit_flush();
clearstatcache();

bmbdb_connect($db_server,$db_username,$db_password,$db_name);
if(!isset($_GET['step'])){
?>
<h2>欢迎进入 BMForum Datium! 1.0 升级到 2.0 程序</h2>
<br>
<br>在升级工作进行之前，请做好备份工作，确保已经执行了 SQL 语句；当点击进入下一步之后，请不要刷新浏览器，耐心等待。
<br>
<br><a href=datium1to2.php?step=2>点击这里进入下一步</a>
<p>
<br>如果您有任何疑问，请访问官方讨论区 <a href=http://www.bmforum.com/bmb>www.bmforum.com</A>
<?php }elseif($_GET['step']==2){ ?>
<h2>欢迎进入 BMForum Datium! 1.0 升级到 2.0 程序</h2>
<br>
<br>正在读取用户资料库
<?php
$query = "SELECT * FROM {$database_up}userlist";
$result = bmbdb_query($query);
while($row = bmbdb_fetch_array($result)) {
	$newline.="{$row['userid']}|{$row['username']}|\n";
}
echo "<br>正在创建用户资料库";
writetofile("d122.tmp",$newline);
echo "<br>创建用户资料库完成";
?>
<br>
<br><a href=datium1to2.php?step=3>点击这里进入下一步</a>
<?php 
}elseif($_GET['step']==3){
?>
<h2>欢迎进入 BMForum Datium! 1.0 升级到 2.0 程序</h2>
<br>
<br>开始读取用户资料库
<?php
$fileopen=file("d122.tmp");
$count=count($fileopen);
for($i=0;$i<$count;$i++){
	$detail=explode("|",$fileopen[$i]);
	$newarray["{$detail[1]}"]=$detail[0];
}
$filexopen=@file("d123.tmp");
$couxnt=count($filexopen);
for($ix=0;$ix<$couxnt;$ix++){
	$updated[]=str_replace("\n","",$filexopen[$ix]);
}

echo "<br>开始读取帖子资料库";
echo "<br>正在创建帖子资料库";
$lastend=$_GET['lastend'];
if(!isset($_GET['lastend'])) $lastend="0";
$query = "SELECT username FROM {$database_up}posts LIMIT $lastend,$everytime";
$result = bmbdb_query($query);
while($row = bmbdb_fetch_array($result)) {
	$lastend++;
	if(!@in_array($row['username'],$updated)){
		$updated[]=$row['username'];
		$thisid=$newarray["{$row['username']}"];
		$updatelines.="UPDATE {$database_up}posts SET usrid='$thisid' WHERE username='{$row['username']}'\n";
	}

}
writetofile("d124.tmp",$updatelines,"a");

if($updatelines==""){
	echo "<br>创建帖子资料库完成";
}else{
	writetofile("d123.tmp",implode("\n",$updated));
	die("<br><br><meta http-equiv=\"Refresh\" content=\"0; URL=datium1to2.php?step=3&lastend=$lastend\"><a href=datium1to2.php?step=3&lastend=$lastend>若没有跳转，点击这里进入下一步 TIMES:$lastend</a>");
}


?>
<br>
<br><a href=datium1to2.php?step=4>点击这里进入下一步</a>
<?php
}elseif($_GET['step']==4){
?>
<h2>欢迎进入 BMForum Datium! 1.0 升级到 2.0 程序</h2>
<br>
<br>开始读取帖子资料库
<?php
$fileopen=file("d124.tmp");
$count=count($fileopen);
echo "<br>开始写入帖子数据库";
$coutt=0;
if(!isset($_GET['lasti'])) $_GET['lasti']=0;
for($i=$_GET['lasti'];$i<$count;$i++){
	$coutt++;
	if($fileopen[$i]!=""){
		$result = bmbdb_query($fileopen[$i],1);
		if($coutt>=$everypost) {
			$thisi=$i;
			die("<br><br><meta http-equiv=\"Refresh\" content=\"0; URL=datium1to2.php?step=4&lasti=$thisi\"><a href=datium1to2.php?step=4&lasti=$thisi>若没有跳转，点击这里进入下一步 TIMESTAMP:".time()."</a>");
		}
	}
}
if(!@unlink("d122.tmp") || !@unlink("d123.tmp") || !@unlink("d124.tmp")){
	$tips="以及本程序的遗留文件 d122.tmp d123.tmp d124.tmp";
}
?>
<br><br>恭喜！升级工作已经全部完成！
<br>请删除本程序<?php echo $tips?>
<br>如果您有任何疑问，请访问官方讨论区 <a href=http://www.bmforum.com/bmb>www.bmforum.com</A>
<?php
}

function writetofile($file_name,$data,$method="w") {
	$filenum=fopen($file_name,$method);
	flock($filenum,LOCK_EX);
	$file_data=fwrite($filenum,$data);
	fclose($filenum);
	return $file_data;
}