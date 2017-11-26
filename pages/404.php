<?php
//  MarioMods - 404
if (!defined('BLARG')) die();

// Some servers use one response, some use another. For safety, use both.
header("HTTP/2.0 404 Not Found");
header('Status: 404 Not Found');

$title = __("404 - Not found");

$tpl->assign('error', (string)$_SERVER['REQUEST_URI']);
$tpl->display('error.tpl');