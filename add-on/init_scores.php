<?php
set_time_limit(900);
require("datafile/config.php");
require("include/db/db_mysql.php");

bmbdb_connect($db_server, $db_username, $db_password, $db_name, 0, $mysqlchar);

$query = "SELECT tid,other1 FROM {$database_up}posts WHERE `other1`!=0 AND tid=id";
$result = bmbdb_query($query);
while (false !== ($line = bmbdb_fetch_array($result))) {
	bmbdb_query("UPDATE {$database_up}threads SET `other1`=$line[other1] WHERE `tid`=$line[tid]");
} 

echo "完成升级积分显示。";