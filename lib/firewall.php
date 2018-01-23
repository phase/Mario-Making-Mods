<?php
if (!defined('BLARG')) die();

function do403() {
	header('HTTP/2.0 403 Forbidden');
	header('Status: 403 Forbidden');
	die('403 Forbidden');
}

function do404() {
	header('HTTP/2.0 404 Not Found');
	header('Status: 404 Not Found');
	die('404 Not Found');
}

//Run some DRM checks. Make's sure that it's only on our server, and not any localhost.
if ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/4.0' // weird bots. Rumors say it's hacking bots, or the bots China uses to crawl the internet and censor it
	|| $dbserv !== 'localhost' //catch free hosts
	|| $dbuser == 'root' //catch most localhosts
	|| !empty($dbpref) //catch even the tinniest thing
	|| !isset($github_secret) //our secret github code wasn't defined anywhere.
	|| empty($github_secret) //it's empty
	|| $_SERVER["HTTP_HOST"] != "mariomods.net" //if the host isn't mariomods.net
	|| !$https) //best for last: check if it uses Secure HTTP. This, afaik, would mean that unless you tamper with the file, for this to work natively, you need to be on a valid VPS, have the host of mario mods, can't use root, and much more. Pretty much, we're safe.
	do403();

// spamdexing in referrals/useragents
if (isset($_SERVER['HTTP_REFERER'])) {
	if (stristr($_SERVER['HTTP_REFERER'], '<a href=') ||
		stristr($_SERVER['HTTP_USER_AGENT'], '<a href='))
		do403();
}

// spamrefreshing
if (isset($_SERVER['HTTP_REFERER'])) {
	if (stristr($_SERVER['HTTP_REFERER'], 'refreshthis.com'))
		do403();
}

if ($isBot)
{
	// keep SE bots out of certain pages that don't interest them anyway
	// TODO move that code to those individual pages
	$forbidden = array('register', 'login', 'online', 'referrals', 'records', 'lastknownbrowsers');
	if (in_array($_GET['page'], $forbidden))
		do403();
}

//Todo: Detect User Power Level Change, as well as other minor things such as if the MySQL database has some odd paramiters.

