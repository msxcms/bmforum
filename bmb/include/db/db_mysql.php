<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

function bmbdb_connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0, $mysqlchar = 0)
{
	global $_DB_CONNECTING;
	
	$_DB_CONNECTING['dbHost'] = $dbhost;
	$_DB_CONNECTING['dbUser'] = $dbuser;
	$_DB_CONNECTING['dbPw'] = $dbpw;
	$_DB_CONNECTING['dbName'] = $dbname;
	$_DB_CONNECTING['pConnect'] = $pconnect;
	$_DB_CONNECTING['mySqlChar'] = $mysqlchar;
}
function bmbdb_CreateNewDb($withDb = true)
{
	global $_DB_CONNECTING;
	
	$_DB_CONNECTING['link'] = $link = new mysqli();
	if ($withDb) {
	    if (!@$link->real_connect($_DB_CONNECTING['dbHost'], $_DB_CONNECTING['dbUser'], $_DB_CONNECTING['dbPw'], $_DB_CONNECTING['dbName'])) {
	        bmbdb_halt('Can not connect to MySQL server');
	    } 
	} else {
	    if (!@$link->real_connect($_DB_CONNECTING['dbHost'], $_DB_CONNECTING['dbUser'], $_DB_CONNECTING['dbPw'])) {
	        bmbdb_halt('Can not connect to MySQL server');
	    } 
	}

    $_DB_CONNECTING['serverVersion'] = $link->server_version;
    
    if ($_DB_CONNECTING['mySqlChar'] == 1) {
		$link->set_charset('utf8mb4');
    } else {
    	$link->set_charset('latin1');
    }

    if ($_DB_CONNECTING['serverVersion'] >= 5.0) $link->query("SET @@sql_mode =  ''");

	$_DB_CONNECTING['connectStatus'] = 1;
} 

function bmbdb_fetch_array($query, $result_type = MYSQLI_ASSOC)
{
    $result = $query->fetch_array($result_type);
    return $result ?: false;
} 

function bmbdb_query_fetch($sql, $silence = 0, $result_type = MYSQLI_ASSOC)
{
    global $querynum;
	$query = bmbdb_query($sql, $silence);
    return bmbdb_fetch_array($query, $result_type);
} 

function bmbdb_query($sql, $silence = 0)
{
    global $querynum, $_DB_CONNECTING;
    if (!$_DB_CONNECTING['connectStatus']) bmbdb_CreateNewDb();  
    $query = $_DB_CONNECTING['link']->query($sql);
    if (!$query && !$silence) {
        bmbdb_halt('MySQL Query Error', $sql);
    }
    $querynum++;
    return $query;
} 

function bmbdb_unbuffered_query($sql, $silence = 0)
{
    global $querynum, $_DB_CONNECTING;
    if (!$_DB_CONNECTING['connectStatus']) bmbdb_CreateNewDb();  
    $query = $_DB_CONNECTING['link']->query($sql, MYSQLI_USE_RESULT);
    if (!$query && !$silence) {
        bmbdb_halt('MySQL Query Error', $sql);
    } 
    $querynum++;
    return $query;
} 

function bmbdb_affected_rows()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->affected_rows;
} 

function bmbdb_error()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->error;
} 

function bmbdb_errno()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->errno;
} 

function bmbdb_num_rows($query)
{
    return $query->num_rows;
} 

function bmbdb_num_fields($query)
{
    return $query->field_count;
} 

function bmbdb_free_result($query)
{
    return $query->free();
} 

function bmbdb_insert_id()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->insert_id;
} 

function bmbdb_fetch_row($query)
{
    $query = $query->fetch_row();
    return $query;
} 

function bmbdb_close()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->close();
} 

function bmbdb_server_info ()
{
	global $_DB_CONNECTING;
    return $_DB_CONNECTING['link']->server_info;
}

function bmbdb_select_db($dbName)
{
	global $_DB_CONNECTING;
    if (!$_DB_CONNECTING['connectStatus']) bmbdb_CreateNewDb(false);  
    return $_DB_CONNECTING['link']->select_db($dbName);
}

function bmbdb_fetch_fields($query)
{
    return $query->fetch_fields();
}
function bmbdb_halt($message = '', $sql = '')
{
    require 'db_mysql_error.php';
} 

