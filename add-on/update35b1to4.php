<?php
/* ---------------------------------------
   BMForum 3.5 Beta 1 TO 4.0 RC 1 Upgrade Program
   --------------------------------------- */
@header("Content-Type: text/html; charset=utf-8");
require("datafile/config.php");
require("db/db_{$sqltype}.php");
bmbdb_connect($db_server,$db_username,$db_password,$db_name);
@set_time_limit(600);
if($_GET['num']==2){
    $dh=opendir("favorites");
    while (false !== ($postfile = readdir($dh))) {
        if (($postfile!=".") && ($postfile!="..") && ($postfile!="")) {
            $content = @readfromfile("favorites/$postfile");
            if ($content) {
                writetofile("favorites/$postfile", str_replace("<?exit;?>", "<?php //", $content));
            }
        }
    }
    closedir($dh);
    
    $dh=opendir("friend");
    while (false !== ($postfile = readdir($dh))) {
        if (($postfile!=".") && ($postfile!="..") && ($postfile!="")) {
            $content = @readfromfile("friend/$postfile");
            if ($content) {
                writetofile("friend/$postfile", str_replace("<?exit;?>", "<?php //", $content));
            }
        }
    }
    closedir($dh);
    
    rename("badman", "datafile/badman");
    rename("friend", "datafile/friend");
    rename("favorites", "datafile/favorites");
    rename("neweditor", "editor/neweditor");
    rename("header", "newtem/header");
    rename("footer", "newtem/footer");
    rename("act", "images/act");
    rename("face", "images/face");
    rename("avatars", "images/avatars");

    
    mkdir("upload/usravatars", 0777);
	stepshow(2);
}elseif($_GET['num']==3){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `username` `username` CHAR( 60 ) NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` DROP INDEX `username` ,ADD INDEX `username` ( `username` )");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` ADD `digestmount` MEDIUMINT DEFAULT '0' NOT NULL");
    stepshow(3);
}elseif($_GET['num']==4){
    $result = bmbdb_query("ALTER TABLE `{$database_up}tags` CHANGE `threads` `threads` MEDIUMINT( 9 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` ADD `pincount` SMALLINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(4);
}elseif($_GET['num']==5){
    $result = bmbdb_query("ALTER TABLE `{$database_up}posts` ADD `replymail` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL ,ADD `replypm` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(5);
}elseif($_GET['num']==6){
    $result = bmbdb_query("ALTER TABLE `{$database_up}posts` ADD INDEX ( `replymail` , `replypm` )");
    stepshow(6);
}elseif($_GET['num']==7){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` DROP INDEX `ttagid`");
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` DROP INDEX `forumid`");
    stepshow(7);
}elseif($_GET['num']==8){
    $result = bmbdb_query("ALTER TABLE `{$database_up}posts` DROP INDEX `forumid`");
    stepshow(8);
}elseif($_GET['num']==9){
    $result = bmbdb_query("ALTER TABLE `{$database_up}actlogs` DROP INDEX `forumid`");
    $result = bmbdb_query("ALTER TABLE `{$database_up}invite` DROP INDEX `inviter`");
    $result = bmbdb_query("ALTER TABLE `{$database_up}invite` DROP INDEX `invitecode`");
    $result = bmbdb_query("ALTER TABLE `{$database_up}polls` DROP INDEX `forumid`");
    $result = bmbdb_query("ALTER TABLE `{$database_up}tags` DROP INDEX `tagname`");
    stepshow(9);
}elseif($_GET['num']==10){
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` ADD `trashcount` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,ADD `digestcount` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` CHANGE `id` `id` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` CHANGE `topicnum` `topicnum` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` CHANGE `replysnum` `replysnum` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` CHANGE `todayp` `todayp` INT( 7 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}forumdata` CHANGE `todaypt` `todaypt` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}lastest` CHANGE `regednum` `regednum` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}lastest` CHANGE `postsnum` `postsnum` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}lastest` CHANGE `todaynew` `todaynew` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}lastest` CHANGE `ydaynew` `ydaynew` MEDIUMINT( 9 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}lastest` CHANGE `maxnews` `maxnews` MEDIUMINT( 9 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(10);
}elseif($_GET['num']==11){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `ttrash` `ttrash` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `hits` `hits` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(11);
}elseif($_GET['num']==12){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `replys` `replys` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `type` `type` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(12);
}elseif($_GET['num']==13){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `newmess` `newmess` SMALLINT( 3 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `pwd` `pwd` VARCHAR( 32 ) NOT NULL");
    stepshow(13);
}elseif($_GET['num']==14){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `publicmail` `publicmail` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `mailtype` `mailtype` VARCHAR( 4 ) NOT NULL");
    stepshow(14);
}elseif($_GET['num']==15){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `qqmsnicq` `qqmsnicq` TINYTEXT NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `regdate` `regdate` TINYTEXT NOT NULL");
    stepshow(15);
}elseif($_GET['num']==16){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `homepage` `homepage` TINYTEXT NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `lastpost` `lastpost` INT DEFAULT '0' NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `fromwhere` `fromwhere` TINYTEXT NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `headtitle` `headtitle` TINYTEXT NOT NULL");
    stepshow(16);
}elseif($_GET['num']==17){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `pwdask` `pwdask` TINYTEXT NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `pwdanswer` `pwdanswer` VARCHAR( 32 ) NOT NULL");
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `birthday` `birthday` TINYTEXT NOT NULL ,CHANGE `team` `team` TINYTEXT NOT NULL ,CHANGE `sex` `sex` TINYTEXT NOT NULL ,CHANGE `national` `national` TINYTEXT NOT NULL");
    stepshow(17);

}elseif($_GET['num']==18){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `lastreply` `lastreply` TEXT NOT NULL ,CHANGE `topic` `topic` TINYTEXT NOT NULL ,CHANGE `itsre` `itsre` TINYTEXT NOT NULL ,CHANGE `islock` `islock` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL ,CHANGE `ip` `ip` VARCHAR( 20 ) NOT NULL ,CHANGE `face` `face` TINYTEXT NOT NULL");
    stepshow(18);
}elseif($_GET['num']==19){
    $result = bmbdb_query("ALTER TABLE `{$database_up}posts` CHANGE `ip` `ip` VARCHAR( 20 ) NOT NULL");
    stepshow(19);
}elseif($_GET['num']==20){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` ADD INDEX ( `islock` )");
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` CHANGE `toptype` `toptype` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(20);
}elseif($_GET['num']==21){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` ADD INDEX `topindex` ( `toptype` , `changetime` )");
    stepshow(21);
}elseif($_GET['num']==22){
    $result = bmbdb_query("UPDATE `{$database_up}threads` SET toptype=1 WHERE type>=3");
    stepshow(22);
}elseif($_GET['num']==23){
    $result = bmbdb_query("UPDATE `{$database_up}threads` SET toptype=0 WHERE toptype>1");
    stepshow(23);
}elseif($_GET['num']==24){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` ADD `ordertrash` TINYINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(24);
}elseif($_GET['num']==25){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` ADD INDEX ( `ordertrash` ) ");
    stepshow(25);
}elseif($_GET['num']==26){
    $result = bmbdb_query("UPDATE `{$database_up}threads` SET ordertrash=1 WHERE ttrash=1");
    stepshow(26);
}elseif($_GET['num']==27){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` DROP INDEX `hits`");
    stepshow(27);
}elseif($_GET['num']==28){
    $result = bmbdb_query("ALTER TABLE `{$database_up}threads` DROP INDEX `replys`");
    stepshow(28);
}elseif($_GET['num']==29){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `lastlogin` `lastlogin` INT DEFAULT '0' NOT NULL ,CHANGE `tlastvisit` `tlastvisit` INT DEFAULT '0' NOT NULL ,CHANGE `deltopic` `deltopic` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,CHANGE `delreply` `delreply` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,CHANGE `uploadfiletoday` `uploadfiletoday` MEDIUMINT UNSIGNED DEFAULT '0' NOT NULL ,CHANGE `lastupload` `lastupload` MEDIUMINT DEFAULT '0' NOT NULL ,CHANGE `foreuser` `foreuser` TINYTEXT NOT NULL ,CHANGE `hisipa` `hisipa` VARCHAR( 20 ) NOT NULL ,CHANGE `hisipb` `hisipb` VARCHAR( 20 ) NOT NULL ,CHANGE `hisipc` `hisipc` VARCHAR( 20 ) NOT NULL ,CHANGE `digestmount` `digestmount` MEDIUMINT( 9 ) UNSIGNED DEFAULT '0' NOT NULL");
    stepshow(29);
}elseif($_GET['num']==30){
    $result = bmbdb_query("ALTER TABLE `{$database_up}userlist` CHANGE `lastupload` `lastupload` INT( 11 ) DEFAULT '0' NOT NULL");
    stepshow(30);




}elseif($_GET['num']==31){
?>
<br><br>恭喜！升级工作已经全部完成！
<br><b><font color=red>请进入FTP删除本程序</font></b>
<br>如果您有任何疑问，请访问官方讨论区 <a href=http://www.bmforum.com/bmb/>www.bmforum.com</A>
<?php
    
}else{
    echo "<h2>BMForum Datium! 3.5 Beta 1 到 BMForum 2006 4.0 RC1 升级程序</h2><br/>此程序将升级数据表结构，升级前请必须关闭论坛并备份论坛数据，之后再进行下一步<br><a href=update35b1to4.php?num=2>下一步</a>";
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
    echo "第 $stepnum 步完成，如果没有跳转，<a href=update35b1to4.php?num=$next_stepnum>点击这里进入下一步</a><br/><br/>当出现错误时，请将错误信息<b>完整地</b>贴到您在官方论坛的技术支持请求主题中。";
    echo "<script type='text/javascript'>window.location='update35b1to4.php?num=$next_stepnum';</script>";
    exit;
}