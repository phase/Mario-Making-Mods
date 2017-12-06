<?php
if (!defined('BLARG')) die();

$headerlinks = array
(
	actionLink('faq') => 'FAQ',
	pageLink('depot') => 'Depot',
	pageLink('wiki') => 'Wiki',
);

$sidelinks = array
(
	Settings::get('menuMainName') => array
	(
		pageLink('home') => 'Home page',
		actionLink('board') => 'Forums',
		pageLink('forumfaq') => 'FAQ',
		actionLink('memberlist') => 'Member list',
		actionLink('ranks') => 'Ranks',
		actionLink('online') => 'Online users',
		actionLink('lastposts') => 'Last posts',
		actionLink('search') => 'Search',
		actionLink('depot') => 'Depot',
		actionLink('wiki') => 'Wiki',
	),
);

?>
