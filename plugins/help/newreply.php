<?php

if ($isHidden) return;

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

$link = getServerDomainNoSlash().'/'.actionLink("post", $pid);

if ($question == true) { HeyReport("New question by "
	.$thename
	." in "
	.$thread["title"]
	.": "
	.$link
); }
	
?>
