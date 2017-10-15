<?php
//  AcmlmBoard XD - 404
//  Access: all
if (!defined('BLARG')) die();

// Some servers use one response, some use another. For safety, use both.
header('HTTP/1.1 404 Not Found');
header('Status: 404 Not Found');

$title = __("404 - Not found");

//echo $_SERVER['REQUEST_URI'].' -- '.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];

$tpl->assign('error', (string)$_SERVER['REQUEST_URI']);
$tpl->display('error.tpl');

//Kill(__('The page you are looking for was not found.').'<br /><br />
//	<a href=".">'.__('Return to the board index').'</a>', __("404 - Not found"));
?>
