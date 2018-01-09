<?php

$thename = $loguser["name"];
if($loguser["displayname"])
	$thename = $loguser["displayname"];

$link = getServerDomainNoSlash().'/'.actionLink("wiki", $page['id'], '', '_');

if ($page['new'] == 2){
	PostReport("New wiki page: ".url2title($page['id'])." created by {$thename} -- {$link}");
	Report("Wiki page ".url2title($page['id'])." edited by {$thename} (rev. {$rev}) -- {$link}");
} else {
	PostReport("Wiki page ".url2title($page['id'])." edited by {$thename} (rev. {$rev}) -- {$link}");
	Report("Wiki page ".url2title($page['id'])." edited by {$thename} (rev. {$rev}) -- {$link}");
}