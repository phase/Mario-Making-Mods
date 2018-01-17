<?php

MakeCrumbs(array(pageLink('depot') => 'Depot'), $links);

$header = __('Welcome to our depot');
$text = __('Welcome to the Mario Making Mods Depot. Here, you can find the latest and best hacks from our forums!');
$submissions = __('Specify the following in your submission.
				<ul><li>Name of Theme
				<li>Platform (WiiU/3DS)
				<li>Game it replaces (SMB1/SMB3/SMW/NSMBU)
				<li>Screenshots/Video
				<li>Download link</ul>');

RenderTemplate('form_welcome', array('header' => $header, 'text' => $text));

$command = '';
$countcommand = '';
if ($http->get('console')) {
	if ($http->get('console') == '3ds') {
		$console = '3ds';
		$command .= " AND t.downloadtheme3ds <> '' ";
		$countcommand .= " AND downloadtheme3ds <> '' ";
	} elseif ($http->get('console') == 'wiiu') {
		$console = 'wiiu';
		$command .= " AND (t.downloadthemewiiu <> '' OR t.downloadcostumewiiu <> '') ";
		$countcommand .= " AND (downloadthemewiiu <> '' OR downloadcostumewiiu <> '') ";
	} else {
		$console = '';
		$command = '';
		$countcommand = '';
	}
} else {
	$console = '';
	$command = '';
	$countcommand = '';
}

if ($http->get('style')) {
	if ($http->get('style') == 'smb1') {
		$style = 'smb1';
		$command .= " AND t.style = 'smb1' ";
		$countcommand .= " AND style = 'smb1' ";
	} else if ($http->get('style') == 'smb3') {
		$style = 'smb3';
		$command .= " AND t.style = 'smb3' ";
		$countcommand .= " AND style = 'smb3' ";
	} else if ($http->get('style') == 'smw') {
		$style = 'smw';
		$command .= " AND t.style = 'smw' ";
		$countcommand .= " AND style = 'smw' ";
	} else if ($http->get('style') == 'nsmbu') {
		$style = 'nsmbu';
		$command .= " AND t.style = 'nsmbu' ";
		$countcommand .= " AND style = 'nsmbu' ";
	} else if ($http->get('style') == 'custom') {
		$style = 'custom';
		$command .= " AND t.style = 'custom' ";
		$countcommand .= " AND style = 'custom' ";
	} else {
		$style = '';
		$command .= '';
		$countcommand .= '';
	}
} else {
	$style = '';
	$command .= '';
	$countcommand .= '';
}

if ($http->get('theme')) {
	if ($http->get('theme') == 'grass') {
		$smmtheme = 'grass';
		$command .= " AND t.theme = 'grass' ";
		$countcommand .= " AND theme = 'grass' ";
	} else if ($http->get('theme') == 'under') {
		$smmtheme = 'under';
		$command .= " AND t.theme = 'under' ";
		$countcommand .= " AND theme = 'under' ";
	} else if ($http->get('theme') == 'water') {
		$smmtheme = 'water';
		$command .= " AND t.theme = 'water' ";
		$countcommand .= " AND theme = 'water' ";
	} else if ($http->get('theme') == 'castle') {
		$smmtheme = 'castle';
		$command .= " AND t.theme = 'castle' ";
		$countcommand .= " AND theme = 'water' ";
	} else if ($http->get('theme') == 'ghost') {
		$smmtheme = 'ghost';
		$command .= " AND t.theme = 'ghost' ";
		$countcommand .= " AND theme = 'ghost' ";
	} else if ($http->get('theme') == 'airship') {
		$smmtheme = 'airship';
		$command .= " AND t.theme = 'airship' ";
		$countcommand .= " AND theme = 'ghost' ";
	} else {
		$smmtheme = '';
		$command .= '';
		$countcommand .= '';
	}
} else {
	$smmtheme = '';
	$command .= '';
	$countcommand .= '';
}

$rFora = Query("select * from {forums} where id = {0}", 7);
if(NumRows($rFora))
{
	$forum = Fetch($rFora);
	if(!HasPermission('forum.viewforum', $forum['id']))
		return;
} else
	return;

$sidebarshow = true;
$showconsoles = true;
$depoturl = 'depot/level';

$numThemes = FetchResult("select count(*) from threads where forum = 7 ".$countcommand);

RenderTemplate('form_lvluserpanel', array('submission' => $submissions));
$fid = $forum['id'];

$total = $forum['numthreads'];

if(isset($_GET['depotpage']))
	$depotpage = (int)$_GET['depotpage'];
else
	$depotpage = 0;
$tpp = 12;

$depotpagelinks = 'console='.$console.'&style='.$style.'&theme='.$smmtheme.'&depotpage=';

$rThreads = Query("	SELECT 
						t.id, t.icon, t.title, t.closed, t.replies, t.lastpostid, t.screenshot, t.description, t.downloadlevelwiiu, t.downloadlevel3ds,
						p.id pid, p.date,
						pt.text,
						su.(_userfields),
						lu.(_userfields)
					FROM 
						{threads} t
						LEFT JOIN {posts} p ON p.thread=t.id AND p.id=t.firstpostid
						LEFT JOIN {posts_text} pt ON pt.pid=p.id AND pt.revision=p.currentrevision
						LEFT JOIN {users} su ON su.id=t.user
						LEFT JOIN {users} lu ON lu.id=t.lastposter
					WHERE t.forum={0} AND p.deleted=0 ".$command."
					ORDER BY p.date DESC LIMIT {1u}, {2u}", $fid, $depotpage, $tpp);

$numonpage = NumRows($rThreads);

$pagelinks = PageLinks(pageLink('depot', [], $depotpagelinks), $tpp, $depotpage, $numThemes);

echo '<table><tr class="cell1" style="width: 90%; align: center;"><td><h2><center>';

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'top'));

echo '</center></h2></td></tr></table> <div style="max-width: 90%; display: flex; flex-flow: row wrap; justify-content: space-around;">';

while($thread = Fetch($rThreads))
{
	$pdata = array();

	$starter = getDataPrefix($thread, 'su_');
	$last = getDataPrefix($thread, 'lu_');

	$pdata['text'] = $thread['text'];

	$pdata['screenshots'] = $thread['screenshot'];
	
	if ((strpos($pdata['screenshots'], 'https://www.youtube.com/') !== false) || (strpos($pdata['screenshots'], 'https://youtu.be/') !== false))
		$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$pdata['screenshots'].'" frameborder="0" allowfullscreen></iframe>');
	elseif(!empty($pdata['screenshots']))
		$pdata['screenshot'] = parseBBCode('[imgs]'.$pdata['screenshots'].'[/imgs]');
	elseif((preg_match('(\[img\](.*?)\[\/img\])', $pdata['text']) === 1) || (preg_match('(\[imgs\](.*?)\[\/imgs\])', $pdata['text']) === 1) || (preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text']) === 1)){
		$pdata['screenshots'] = '2';
		if(preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text']) === 1) {
			preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text'], $match);
			$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$pdata['screenshots'].'" frameborder="0" allowfullscreen></iframe>');
		} elseif (preg_match('(\[img\](.*?)\[\/img\])', $pdata['text']) === 1) {
			preg_match('(\[img\](.*?)\[\/img\])', $pdata['text'], $match);
			$pdata['screenshot'] = parseBBCode('[imgs]'.$match[1].'[/imgs]');
		} elseif (preg_match('(\[imgs\](.*?)\[\/imgs\])', $pdata['text']) === 1){
			preg_match('(\[imgs\](.*?)\[\/imgs\])', $pdata['text'], $match);
			$pdata['screenshot'] = parseBBCode('[imgs]'.$match[1].'[/imgs]');
		}
	}
	$pdata['description'] = $thread['description'];

	$tags = ParseThreadTags($thread['title']);
	
	$pdata['download'] = '';
	if(!empty($thread['downloadlevel3ds']))
		$pdata['download'] .= '<a href="'.$thread['downloadlevel3ds'].'">Download 3DS Level</a>';
	if(!empty($thread['downloadlevel3ds']) && !empty($thread['downloadlevelwiiu']))
		$pdata['download'] .= ' | ';
	if(!empty($thread['downloadlevelwiiu'])) {
		if (strpos($thread['downloadlevelwiiu'], '://') !== false)
			$pdata['download'] .= '<a href="'.$thread['downloadlevelwiiu'].'">Download WiiU Level</a>';
		else
			$pdata['download'] .= '<a href="https://supermariomakerbookmark.nintendo.net/courses/'.$thread['downloadlevelwiiu'].'">Super Mario Maker Bookmark URL</a>';
	}

	$pdata['titles'] = actionLinkTag(__($tags[0]), "depotentry", $thread['id']);
	$pdata['title'] = '<img src="'.$thread['icon'].'">'.$pdata['titles'].'<br>'.$tags[1];

	$pdata['formattedDate'] = formatdate($thread['date']);
	$pdata['userlink'] = UserLink($starter);

	if (!$thread['replies'])
		$comments = 'No comments yet';
	else if ($thread['replies'] < 2)
		$comments = actionLinkTag('1 comment', 'depost', $thread['lastpostid']).' (by '.UserLink($last).')';
	else
		$comments = actionLinkTag($thread['replies'].' comments', 'depost', $thread['lastpostid']).' (last by '.UserLink($last).')';
	$pdata['comments'] = $comments;

	if ($thread['closed'])
		$newreply = __('Comments closed.');
	else if (!$loguserid)
		$newreply = actionLinkTag(__('Log in'), 'login').__(' to post a comment.');
	else if (HasPermission('forum.postthreads', $forum['id']))
		$newreply = actionLinkTag(__("Post a comment"), "newcomment", $thread['id']);
	$pdata['replylink'] = $newreply;

	RenderTemplate('postdepo', array('post' => $pdata));
}

echo '</div> <br> <table><tr class="cell1"><td><h2><center>';

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'bottom'));

echo '</center></h2></td></tr></table>';
?>