<?php
//-----------------------------------------------------------
// Blue Magic Board ��ɫħ����̳ BMB (����BMForum)
// ��ȡ����������Ѷ�� http://www.bmforum.com
// ��Ȩ���� (C) δ��������ɾ����Ȩ��Ϣ
// �ٷ�վ�㣺 http://www.bmforum.com
//-----------------------------------------------------------
require("datafile/config.php");
require("include/db/db_{$sqltype}.php");


/*
���� $everytime ��ȷ��ÿ����ȡ�û�����
���� $everypost ��ȷ��ÿ��������������ID����
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
<h2>��ӭ���� BMForum Datium! 1.0 ������ 2.0 ����</h2>
<br>
<br>��������������֮ǰ�������ñ��ݹ�����ȷ���Ѿ�ִ���� SQL ��䣻�����������һ��֮���벻Ҫˢ������������ĵȴ���
<br>
<br><a href=datium1to2.php?step=2>������������һ��</a>
<p>
<br>��������κ����ʣ�����ʹٷ������� <a href=http://www.bmforum.com/bmb>www.bmforum.com</A>
<?php }elseif($_GET['step']==2){ ?>
<h2>��ӭ���� BMForum Datium! 1.0 ������ 2.0 ����</h2>
<br>
<br>���ڶ�ȡ�û����Ͽ�
<?php
$query = "SELECT * FROM {$database_up}userlist";
$result = bmbdb_query($query);
while($row = bmbdb_fetch_array($result)) {
	$newline.="{$row['userid']}|{$row['username']}|\n";
}
echo "<br>���ڴ����û����Ͽ�";
writetofile("d122.tmp",$newline);
echo "<br>�����û����Ͽ����";
?>
<br>
<br><a href=datium1to2.php?step=3>������������һ��</a>
<?php 
}elseif($_GET['step']==3){
?>
<h2>��ӭ���� BMForum Datium! 1.0 ������ 2.0 ����</h2>
<br>
<br>��ʼ��ȡ�û����Ͽ�
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

echo "<br>��ʼ��ȡ�������Ͽ�";
echo "<br>���ڴ����������Ͽ�";
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
	echo "<br>�����������Ͽ����";
}else{
	writetofile("d123.tmp",implode("\n",$updated));
	die("<br><br><meta http-equiv=\"Refresh\" content=\"0; URL=datium1to2.php?step=3&lastend=$lastend\"><a href=datium1to2.php?step=3&lastend=$lastend>��û����ת��������������һ�� TIMES:$lastend</a>");
}


?>
<br>
<br><a href=datium1to2.php?step=4>������������һ��</a>
<?php
}elseif($_GET['step']==4){
?>
<h2>��ӭ���� BMForum Datium! 1.0 ������ 2.0 ����</h2>
<br>
<br>��ʼ��ȡ�������Ͽ�
<?php
$fileopen=file("d124.tmp");
$count=count($fileopen);
echo "<br>��ʼд���������ݿ�";
$coutt=0;
if(!isset($_GET['lasti'])) $_GET['lasti']=0;
for($i=$_GET['lasti'];$i<$count;$i++){
	$coutt++;
	if($fileopen[$i]!=""){
		$result = bmbdb_query($fileopen[$i],1);
		if($coutt>=$everypost) {
			$thisi=$i;
			die("<br><br><meta http-equiv=\"Refresh\" content=\"0; URL=datium1to2.php?step=4&lasti=$thisi\"><a href=datium1to2.php?step=4&lasti=$thisi>��û����ת��������������һ�� TIMESTAMP:".time()."</a>");
		}
	}
}
if(!@unlink("d122.tmp") || !@unlink("d123.tmp") || !@unlink("d124.tmp")){
	$tips="�Լ�������������ļ� d122.tmp d123.tmp d124.tmp";
}
?>
<br><br>��ϲ�����������Ѿ�ȫ����ɣ�
<br>��ɾ��������<?php echo $tips?>
<br>��������κ����ʣ�����ʹٷ������� <a href=http://www.bmforum.com/bmb>www.bmforum.com</A>
<?php
}

function writetofile($file_name,$data,$method="w") {
	$filenum=fopen($file_name,$method);
	flock($filenum,LOCK_EX);
	$file_data=fwrite($filenum,$data);
	fclose($filenum);
	return $file_data;
}