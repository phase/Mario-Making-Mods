<?php

if ($isHidden) return;

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];
	
$link = getServerDomainNoSlash().'/'.actionLink("thread", $tid, "", $thread['title']);

PostReport("New thread by ".$thename.": ".$thread["title"]." (".$forum.$forum["title"].")"." -- ".$link);

if($fid == 18)
	DevReport("New thread by ".$thename.": ".$thread["title"]." -- ".$link);
