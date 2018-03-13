<?php
$layoutinfofile = "layout/".$user['layout']."/info.txt";
if(file_exists($layoutinfofile))
{
	$layoutinfo = file_get_contents($layoutinfofile);
	$layoutinfo = explode("\n", $layoutinfo, 2);
	
	$layoutname = htmlspecialchars(trim($layoutinfo[0]));
} else
	$layoutname = htmlspecialchars($user['layout']);

$profileParts[__('Presentation')][__('Layout')] = $layoutname;
