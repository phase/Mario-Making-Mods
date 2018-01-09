<?php

if ($isHidden) return;

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];
	
$link = getServerDomainNoSlash().'/'.actionLink("post", $pid);

if($question == true) {
	HelpReport("New Super Mario Maker Hacking Question posted by ".$thename.": ".$link);
	PostReport("New Super Mario Maker Hacking Question posted by ".$thename.": ".$link);
} else 
	PostReport("New reply by ".$thename.": ".$thread["title"]." (".$forum.$forum["title"].")"." -- ".$link);

if($fid == 18)
	DevReport("New post by ".$thename.": ".$thread["title"]." -- ".$link);