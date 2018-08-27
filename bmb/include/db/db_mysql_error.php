<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/

$timestamp = time();
$errmsg = '';

$dberror = @bmbdb_error();
$dberrno = @bmbdb_errno();

if ($dberrno == 1114) {

    ?>
<html>
<head><title>Max onlines reached</title></head>
<body>
<table cellpadding="0" cellspacing="0" border="0" width="500" height="90%" align="center" style="font-family: Verdana, Tahoma;font-size: 9px;color: #000000">
<tr><td height="50%">&nbsp;</td></tr><tr><td valign="middle" align="center" bgcolor="#EAEAEA">
<br /><b style="font-size: 11px;">Forum onlines reached the upper limit</strong><br /><br /><br />Sorry, the number of online visitors has reached the upper limit.<br />Please wait for someone else going offline or visit us in idle hours.<br /><br /></td>
</tr><tr><td height="50%">&nbsp;</td></tr></table>
</body>
</html>
<?php

    exit;
} else {
    $errmsg = "<strong>BMForum MySQL Connector Message</strong>: $message\n\n";
    $errmsg .= "<strong>Time</strong>: " . gmdate("Y-n-j g:ia", $timestamp + ($_SERVER["timeoffset"] * 3600)) . "\n";
    $errmsg .= "<strong>Script</strong>: " . $_SERVER["PHP_SELF"] . "\n\n";
    if ($sql) {
        $errmsg .= "<strong>SQL</strong>: " . htmlspecialchars($sql) . "\n";
    } 
    $errmsg .= "<strong>Description</strong>:  $dberror\n";
    $errmsg .= "<strong>Error NO.</strong>:  $dberrno";

    echo "</table></table></table></table></table>\n";
    echo "<p style=\"font-family: Verdana, Tahoma; font-size: 11px; background: #FFFFFF;\">";
    echo nl2br($errmsg);

    echo '</p>';
    exit;
} 

?>