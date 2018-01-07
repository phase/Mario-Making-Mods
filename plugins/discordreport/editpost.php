<?php

if ($isHidden) return;

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];
	
$link = getServerDomainNoSlash().'/'.actionLink("post", $pid);

PostReport("Post edited by ".$thename.": ".$thread["title"]."(".$forum.$forum["title"].")"." -- ".$link);

if($fid == 18)
	DevReport("Post edited by ".$thename.": ".$thread["title"]." -- ".$link);