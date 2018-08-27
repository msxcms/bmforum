<?php
require("datafile/config.php");
require("include/db/db_mysql.php");

bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

bmbdb_query("CREATE TABLE `{$database_up}levels` (
  `id` mediumint(9) NOT NULL default '0',
  `maccess` text NOT NULL,
  `fid` int(11) NOT NULL default '0'
) TYPE=MyISAM", 1);
  
bmbdb_query("TRUNCATE TABLE `{$database_up}levels`");

include("datafile/usertitle.php");
include("datafile/cache/usergroup.php");

for ($i =0;$i<$countmtitle;$i++) {
	bmbdb_query("INSERT INTO `{$database_up}levels` VALUES ($i, '{$usergroupdata[4]}',0)");
}

$query = "SELECT * FROM {$database_up}levels WHERE `fid`='0' ORDER BY `id` ASC";
$result = bmbdb_query($query);
$ugsocount = "";
$wrting = "<?php ";
while (false !== ($line = bmbdb_fetch_array($result))) {
        $line['maccess'] = str_replace('"', '\"', $line['maccess']);
        $wrting .= "
\$levelgroupdata[0][{$line['id']}]=\"{$line['maccess']}\";
";
} 

writetofile("datafile/cache/levels/level_fid_0.php", $wrting);

echo "成功初始化用户等级组信息，请注意删除本文件！";

function writetofile($file_name, $data, $method = "w")
{
    $filenum = fopen($file_name, $method);
    flock($filenum, LOCK_EX);
    $file_data = fwrite($filenum, $data);
    fclose($filenum);
    return $file_data;
} 