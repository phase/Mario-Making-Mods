<?php

$header = __('Welcome to our depot');
$text = __('Welcome to the Mario Making Mods Depot. Here, you can find the latest and best hacks from our forums!');
$submissions = __('Specify the following in your submission.
				<ul><li>Name of Theme
				<li>Screenshots/Video
				<li>Download link</ul>');

RenderTemplate('form_welcome', array('header' => $header, 'text' => $text));

$getArgs = array();

if ($http->get('hackname')) {
	$command = " AND t.title like '%".htmlspecialchars($http->get('hackname'))."%' ";
	$countcommand = " AND title like '%".htmlspecialchars($http->get('hackname'))."%' ";
	$getArgs[] = 'hackname='.$http->get('hackname');
	$prevfield['hackname'] = $http->get('hackname'); 
} else {
	$command = '';
	$countcommand = '';
}

$rFora = Query("select * from {forums} where id = {0}", 32);
if(NumRows($rFora)) {
	if(!HasPermission('forum.viewforum', $forum['id']))
		Kill("You do not have the permission to view the depot.");
	else
		$forum = Fetch($rFora);
} else
	Kill("Whoops. Seems like there were no results for the fields you selected. Why not try different fields?");

$depoturl = 'depot/remaker';

$sidebarshow = true;
$showconsoles = false;


RenderTemplate('form_lvluserpanel', array('submission' => $submissions));
$fid = $forum['id'];

$total = $forum['numthreads'];

if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;

$tpp = 12;

$rThreads = Query("	SELECT 
						t.id, t.icon, t.title, t.depothide, t.closed, t.replies, t.lastpostid, t.screenshot, t.description, t.downloadlevelpc, t.downloadcostumepc, t.downloadthemepc,
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
					WHERE t.forum={0} AND p.deleted=0 AND t.depothide=0 $command 
					ORDER BY p.date DESC LIMIT {1u}, {2u}", $fid, $from, $tpp);

$numonpage = NumRows($rThreads);

$numThemes = FetchResult("select count(*) from threads where forum = 32 AND depothide=0 ".$countcommand);

$getArgs[] = 'from=';
$pagelinks = PageLinks(pageLink('remakerdepot', [], implode('&', $getArgs)), $tpp, $from, $numThemes);

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'top'));

$links = array();
if($loguserid) {
	if (HasPermission('forum.postthreads', $fid))
		$links[] = actionLinkTag(__("Post new submission"), "newthread", $fid, '', $urlname);
}

MakeCrumbs(array(pageLink('remakerdepot') => 'Super Mario ReMaker Depot'), $links);

echo '<div style="max-width: 90%; display: flex; flex-flow: row wrap; justify-content: space-around;">';

while($thread = Fetch($rThreads)) {
	$pdata = array();

	$starter = getDataPrefix($thread, 'su_');
	$last = getDataPrefix($thread, 'lu_');

	$pdata['screenshots'] = $thread['screenshot'];

	if ((strpos($pdata['screenshots'], 'https://www.youtube.com/') !== false) || (strpos($pdata['screenshots'], 'https://youtu.be/') !== false))
		$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$pdata['screenshots'].'" frameborder="0" allowfullscreen></iframe>');
	elseif (substr($pdata['screenshots'], -4) == '.mp4')
		$pdata['screenshot'] = '<video width="280" height="157" controls><source src="'.htmlspecialchars($pdata['screenshots']).'" type="video/mp4">Your browser does not support the video tag.</video>';
	elseif(!empty($pdata['screenshots']))
		$pdata['screenshot'] = parseBBCode('[imgs]'.$pdata['screenshots'].'[/imgs]');
	elseif(preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text']) === 1){
		$pdata['screenshots'] = '2';
		preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text'], $match);
		$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$match[1].'" frameborder="0" allowfullscreen></iframe>');
	}
	$pdata['description'] = $thread['description'];

	$tags = ParseThreadTags($thread['title']);

	$pdata['download'] = '';
	if(!empty($thread['downloadlevelpc']))
		$pdata['download'] .= '<a href="'.$thread['downloadlevel3ds'].'">Download Level</a>';
	if(!empty($thread['downloadlevelpc']) && !empty($thread['downloadthemepc']))
		$pdata['download'] .= ' | ';
	if(!empty($thread['downloadthemepc']))
		$pdata['download'] .= '<a href="'.$thread['downloadthemepc'].'">Download Theme</a>';
	if((!empty($thread['downloadcostumepc']) && !empty($thread['downloadthemepc'])) || (!empty($thread['downloadlevelpc']) && !empty($thread['downloadcostumepc'])))
		$pdata['download'] .= ' | ';
	if(!empty($thread['downloadcostumepc']))
		$pdata['download'] .= '<a href="'.$thread['downloadcostumepc'].'">Download Costume</a>';

	$pdata['title'] = '<img src="'.$thread['icon'].'"><a href="'.pageLink("entry", array(
				'id' => $thread['id'],
				'name' => slugify($tags[0])
			)).'">'.$tags[0].'</a><br>'.$tags[1];

	$pdata['formattedDate'] = formatdate($thread['date']);
	$pdata['userlink'] = UserLink($starter);
	$pdata['text'] = CleanUpPost($thread['text'],$starter['name'], false, false);

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

echo '</div>';

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'bottom'));
