<?php

if ($isHidden) return;

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

$link = getServerDomainNoSlash().'/'.actionLink("post", $pid);

if ($tid == 73) { HeyReport("Post edited by "
	.$thename
	." in "
	.$thread["title"]
	.": "
	.$link
); }
	
?>