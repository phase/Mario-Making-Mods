<?php
if (!defined('BLARG')) die();

echo 'Page Boot UP!';

$payload = json_decode($_POST['payload']);

if ($payload === false || !isset($payload->commits))
    die('Hacking attempt...');

echo 'Made it so far!<br>';

if ($_POST['payload']) {
	if($payload->repository->name == "Forum-software") {
		$tid = 367;
		$fid = 2;
	} elseif($payload->repository->name == "PointlessMaker") {
		$fid = 18;
		$tid = 378;
	} else {
		die('Hacking attempt...');
	}

	$rUsers = Query("update {users} set posts=posts+1, lastposttime={0} where id={1} limit 1",
		time(), 197);

	$rPosts = Query("insert into {posts} (thread, user, date, ip, num, options, mood) values ({0},{1},{2},{3},{4}, {5}, {6})",
		$tid, 197, time(), $_SERVER['REMOTE_ADDR'], 0, '', '');

	$pid = InsertId();

	foreach ($payload->commits as $commit){
		$author = $commit->author->username;
		$message = $commit->message;
		$commit = $commit->url;
	}

	$post = $author.' has made a new commit: '.$message.'<br><br>'.$commit;

	$rPostsText = Query("insert into {posts_text} (pid,text,revision,user,date) values ({0}, {1}, {2}, {3}, {4})", $pid, $post, 0, 197, time());

	$rFora = Query("update {forums} set numposts=numposts+1, lastpostdate={0}, lastpostuser={1}, lastpostid={2} where id={3} limit 1",
		time(), 197, $pid, $fid);

	$rThreads = Query("update {threads} set lastposter={0}, lastpostdate={1}, replies=replies+1, lastpostid={2} where id={3} limit 1",
		197, time(), $pid, $tid);
	
	echo 'Succes!';
} else
	echo 'Error';