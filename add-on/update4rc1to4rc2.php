<?php
/* ---------------------------------------
   BMForum Upgrade Program
   --------------------------------------- */
@header("Content-Type: text/html; charset=utf-8");
@set_time_limit(600);
error_reporting(0);
if($_GET['num']==2){
	
	$readconfig = readfromfile("datafile/config.php");
	$readconfig = str_replace("error_reporting(E_ERROR | E_WARNING | E_PARSE);",
"define(\"CONFIGLOADED\", 1);
error_reporting(0);",$readconfig);
	writetofile("datafile/config.php", $readconfig);
	
	$readplugins = readfromfile("datafile/pluginclude.php");
	$readplugins = str_replace("IN_BMFORUM", "INBMFORUM", $readplugins);
	writetofile("datafile/pluginclude.php", $readplugins);

	stepshow(2);



}elseif($_GET['num']==3){
	require("datafile/config.php");
	require("include/db/db_{$sqltype}.php");
	bmbdb_connect($db_server,$db_username,$db_password,$db_name);

    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` DROP INDEX `topindex` ,ADD INDEX `topindex` ( `forumid` , `toptype` , `ttrash` , `changetime` )");
    $result = bmbdb_query("ALTER TABLE `{$database_up}posts` DROP INDEX `tid` ,ADD INDEX `globaltid` ( `tid` , `id` )");
    
    unlink("datafile/online.php");
    unlink("datafile/guest.php");

	stepshow(3);
	
}elseif($_GET['num']==4){
?>
<br><br>恭喜！升级工作已经全部完成！<font color="red">祝您狗年春节快乐！</font><br><br>如果您需要升级风格界面，请上传 add-on/bs5tocss.php 进行升级。升级风格前请确认 images,datafile\style,newtem目录和其子目录、文件可写(权限777)。<br>
<br><b><font color="red">请进入FTP删除本程序</font></b>
<br>如果您有任何疑问，请访问官方讨论区 <a href=http://www.bmforum.com/bmb/>www.bmforum.com</a>
<?php
    
}else{
    echo "<h2>BMForum Datium! 2006 4.0 RC1 到 BMForum 2006 4.0 RC2 升级程序</h2><br/>此程序将升级数据表结构，升级前请必须关闭论坛并备份论坛数据，之后再进行下一步<br><a href=update4rc1to4rc2.php?num=2>下一步</a>";
}
function writetofile($file_name,$data,$method="w") {
    $filenum=fopen($file_name,$method);
    flock($filenum,LOCK_EX);
    $file_data=fwrite($filenum,$data);
    fclose($filenum);
    return $file_data;
}
function readfromfile($file_name)
{
    if (file_exists($file_name)) {
        $filenum = fopen($file_name, "r");
        flock($filenum, LOCK_SH);
        $file_data = @fread($filenum, @filesize($file_name));
        fclose($filenum);
        return $file_data;
    } 
} 
function stepshow($stepnum){
	$next_stepnum = $stepnum + 1;
    echo "第 $stepnum 步完成，如果没有跳转，<a href=update4rc1to4rc2.php?num=$next_stepnum>点击这里进入下一步</a><br/><br/>当出现错误时，请将错误信息<b>完整地</b>贴到您在官方论坛的技术支持请求主题中。";
    echo "<script type='text/javascript'>window.location='update4rc1to4rc2.php?num=$next_stepnum';</script>";
    exit;
}