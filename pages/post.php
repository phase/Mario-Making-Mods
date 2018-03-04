<?php
if (!defined('BLARG')) die();

if (isset($_GET['id']))
	$rPost = Query("select id,date,thread from {posts} where id={0}", $_GET['id']);
else if (isset($_GET['tid']) && isset($_GET['time']))
	$rPost = Query("select id,date,thread from {posts} where thread={0} AND date>{1} ORDER BY date LIMIT 1",
		$_GET['tid'], $_GET['time']);
else
	Kill('Please put a post ID.');

if(NumRows($rPost))
	$post = Fetch($rPost);
else
	Kill(__("Unknown post ID."));

$pid = $post['id'];
$tid = $post['thread'];

checknumeric($pid);
checknumeric($tid);

$rThread = Query("select id,title,forum from {threads} where id={0}", $tid);

if(NumRows($rThread))
	$thread = Fetch($rThread);
else
	Kill(__("Unknown thread ID."));

$tags = ParseThreadTags($thread['title']);

$firstpost = FetchResult("SELECT pt.text FROM {posts} p LEFT JOIN {posts_text} pt ON pt.pid=p.id AND pt.revision=p.currentrevision WHERE p.deleted=0 AND p.id={0} ORDER BY p.date", $pid);
if ($firstpost && $firstpost != -1)
{
	$firstpost = strip_tags($firstpost);
	$firstpost = preg_replace('@\[.*?\]@s', '', $firstpost);
	$firstpost = preg_replace('@\s+@', ' ', $firstpost);

	$firstpost = explode(' ', $firstpost);
	if (count($firstpost) > 30)
	{
		$firstpost = array_slice($firstpost, 0, 30);
		$firstpost[29] .= '...';
	}
	$firstpost = implode(' ', $firstpost);

	$metaStuff['description'] = htmlspecialchars($firstpost);
}

$ppp = $loguser['postsperpage'];
if(!$ppp) $ppp = 20;
$from = (floor(FetchResult("SELECT COUNT(*) FROM {posts} WHERE thread={1} AND date<={2} AND id!={0}", $pid, $tid, $post['date']) / $ppp)) * $ppp;
$url = actionLink("thread", $thread['id'], $from?"from=$from":"", HasPermission('forum.viewforum', $thread['forum'], true)?$tags[0]:'')."#post".$pid;

header("HTTP/1.1 301 Moved Permanently");
header("Status: 301 Moved Permanently");
header("Location: /".$url);
die;

