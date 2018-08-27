<?php
$mqgpc = get_magic_quotes_gpc();
$rggps = ini_get("register_globals");

if ($mqgpc == 0) {
    if ($rggps == 0) {
        @extract(addsd($_SESSION), EXTR_SKIP);
        @extract(addsd($_POST), EXTR_SKIP);
        @extract(addsd($_GET), EXTR_SKIP);
    } else {
        @extract(addsd($_SESSION), EXTR_OVERWRITE);
        @extract(addsd($_POST), EXTR_OVERWRITE);
        @extract(addsd($_GET), EXTR_OVERWRITE);
    } 
} else {
    @extract($_SESSION, EXTR_SKIP);
    @extract($_POST, EXTR_SKIP);
    @extract($_GET, EXTR_SKIP);
} 

error_reporting(E_ALL ^ E_NOTICE);
@header("Content-Type: text/html; charset=utf-8");
// functions
function addsd($array)
{ 
    // 安全过滤函数 18:52 2004-5-22
    foreach($array as $key => $value) {
        if (!is_array($array[$key])) {
            $array[$key] = addslashes($value);
        } else {
            $array[$key] = addsd($array[$key]);
        } 
    } 
    return $array;
} 
function refresh_language()
{
    // Refresh the language list
    $selectbox = "<br><br><form action=\"install.php\" method=\"GET\"><select name=\"language\">";
    $dh = opendir("install/languages");
    while (false !== ($download = readdir($dh))) {
        if (($download != ".") && ($download != "..")) {
            include("install/languages/" . $download);
            echo $li[0]. "&nbsp;&nbsp;&nbsp;";
            $language = basename($download, ".php");
            $selectbox .= "<option value=\"$language\">$li[1]</option>";
        } 
    } 
    closedir($dh);
    
    $selectbox .= "</select><br><br><input style=\"width:25%\" type=\"submit\"></form>";
    
    return $selectbox;
}
function writetofile($file_name, $data, $method = "w")
{
	global $li;
    $filenum = fopen($file_name, $method);
    flock($filenum, LOCK_EX);
    if (!($file_data = fwrite($filenum, $data))) {
    	echo "<font color='red' size='4'>$li[74] $file_name</font>";
    	exit;
    }
    fclose($filenum);
    return $file_data;
} 
function readfromfile ($path)
{
    // returns all data in $path, or nothing if it does not exist
    if (file_exists($path) == 0) {
        return "";
    } else {
        $filesize = filesize($path);
        $filenum = fopen($path, "r");
        flock($filenum, LOCK_SH);
        $filestuff = fread($filenum, $filesize);
        fclose($filenum);
        return $filestuff;
    } 
} 
function refresh_forumcach()
{
    global $database_up;
    $nquery = "SELECT * FROM {$database_up}forumdata ORDER BY `showorder` ASC";
    $nresult = bmbdb_query($nquery);
    while ($fourmrow = bmbdb_fetch_array($nresult)) {
        $xfourmrow[] = $fourmrow;
    } 
    $wrting = "<?php \n";
    $count = count($xfourmrow);
    for ($i = 0; $i < $count; $i++) {
        $tmp = $xfourmrow[$i];
        foreach ($tmp as $key => $value) {
            $wrting .= "\$sxfourmrow[$i]['{$key}']='$value'; \n";
        } 
        $forumscount++;
    } 

    $wrting .= "\$forumscount='$forumscount';";
    writetofile("datafile/cache/forumdata.php", $wrting);
} 
function refresh_ugcache()
{
	global $database_up, $li;
    // Refresh Cache
    $query = "SELECT * FROM {$database_up}usergroup ORDER BY `showsort` ASC";
    $result = bmbdb_query($query);
    $ugsocount = "";
    $wrting = "<?php ";
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
    
    writetofile("datafile/announcesys.php", "<?php die();?>\nBMForum|$li[62]|".time()."|$li[63]|");
}