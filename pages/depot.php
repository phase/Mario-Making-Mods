<?php

RenderTemplate('form_welcome', array('fields' => $fields));

if (isset($_GET['3ds'])) {
	$console = '3ds';
	$command = " AND t.downloadtheme3ds <> '' ";
} elseif (isset($_GET['wiiu'])){
	$console = 'wiiu';
	$command = " AND (t.downloadthemewiiu <> '' OR t.downloadcostumewiiu <> '') ";
} else {
	$console = '';
	$command = '';
}

$rFora = Query("select * from {forums} where id = {0} ", 3);
if(NumRows($rFora))
{
	$forum = Fetch($rFora);
	if(!HasPermission('forum.viewforum', $forum['id'])){
		Kill('You May not view the Depot!');
		return;
	}
} else
	return;

$sidebarshow = true;
$showconsoles = true;
$depoturl = 'depot';


RenderTemplate('form_lvluserpanel', array('form_lvluserpanel' => $fields));
$fid = $forum['id'];

$total = $forum['numthreads'];

if(isset($_GET['from']))
	$from = (int)$_GET['from'];
else
	$from = 0;

$tpp = 6;

$rThreads = Query("	SELECT 
						t.id, t.icon, t.title, t.closed, t.replies, t.lastpostid, t.screenshot, t.description, t.downloadthemewiiu, t.downloadcostumewiiu, t.downloadtheme3ds,
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
					ORDER BY p.date DESC LIMIT {1u}, {2u}", $fid, $from, $tpp);

$numonpage = NumRows($rThreads);

$pagelinks = PageLinks(pageLink('depot', [], $console.'&from='), $tpp, $from, $total);

echo '<table><tr class="cell1" style="width: 90%; align: center;"><td><h2><center>';

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'top'));

echo '</center></h2></td></tr></table> <div style="max-width: 90%; display: flex; flex-flow: row wrap; justify-content: space-around;">';

while($thread = Fetch($rThreads))
{
	$pdata = array();

	$starter = getDataPrefix($thread, 'su_');
	$last = getDataPrefix($thread, 'lu_');

	$pdata['screenshots'] = $thread['screenshot'];

	if ((strpos($pdata['screenshots'], 'https://www.youtube.com/') !== false) || (strpos($pdata['screenshots'], 'https://youtu.be/') !== false))
		$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$pdata['screenshots'].'" frameborder="0" allowfullscreen></iframe>');
	elseif(!empty($pdata['screenshots']))
		$pdata['screenshot'] = parseBBCode('[imgs]'.$pdata['screenshots'].'[/imgs]');
	elseif(preg_match('~iframe.+src=(?:&quot;|[\'"])(?:https?)\:\/\/www\.(?:youtube|youtube\-nocookie)\.com\/embed\/(.*?)(?:&quot;|[\'"])~iu', $pdata['text']) === 1){
		$pdata['screenshots'] = '2';
		preg_match('(\[youtube\](.*?)\[\/youtube\])', $pdata['text'], $match);
		$pdata['screenshot'] = str_replace("/watch?v=","/embed/", '<iframe width="280" height="157" src="'.$match[1].'" frameborder="0" allowfullscreen></iframe>');
	}
	$pdata['description'] = $thread['description'];

	$tags = ParseThreadTags($thread['title']);

	$pdata['download'] = '';
	if($thread['downloadtheme3ds'] !== '')
		$pdata['download'] .= '<a href="'.$thread['downloadtheme3ds'].'">Download 3DS Theme</a>';
	if(($thread['downloadtheme3ds'] !== '') && ($thread['downloadthemewiiu'] !== ''))
		$pdata['download'] .= ' | ';
	if($thread['downloadthemewiiu'] !== '')
		$pdata['download'] .= '<a href="'.$thread['downloadthemewiiu'].'">Download WiiU Theme</a>';
	if($thread['downloadcostumewiiu'] !== '')
		$pdata['download'] .= '<a href="'.$thread['downloadcostumewiiu'].'">Download WiiU Costume</a>';
	
	$pdata['titles'] = actionLinkTag(__($tags[0]), "depotentry", $thread['id']);
	$pdata['title'] = '<img src="'.$thread['icon'].'">'.$pdata['titles'].'<br>'.$tags[1];

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

echo '</div> <br> <table><tr class="cell1"><td><h2><center>';

RenderTemplate('pagelinks', array('pagelinks' => $pagelinks, 'position' => 'bottom'));

echo '</center></h2></td></tr></table>';
?>