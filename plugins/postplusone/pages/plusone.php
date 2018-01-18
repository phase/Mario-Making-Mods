<?php
$ajaxPage = true;

if($_GET["key"] != $loguser["token"])
	die("Nope!");

CheckPermission('user.voteposts');

$pid = (int)$pageParams["id"];

$post = Fetch(Query("SELECT * FROM {posts} WHERE id = {0}", $pid));
if(!$post)
	die("Unknown post");
if(!$loguserid)
	die("You must be logged on in order to star posts!")
if($post["user"] == $loguserid)
	die("You may not star your own posts!");

$thread = Fetch(Query("SELECT * FROM {threads} WHERE id = {0}", $post["thread"]));
if(!$thread)
	die("Unknown thread");

if (!HasPermission('forum.viewforum', $thread['forum']))
	die('Nice try hacker kid, but no.');

$vote = Fetch(Query("SELECT * FROM {postplusones} WHERE post = {0} AND user = {1}", $pid, $loguserid));
if(!$vote) {
	Query("UPDATE {posts} SET postplusones = postplusones+1 WHERE id = {0} LIMIT 1", $pid);
	Query("UPDATE {users} SET postplusones = postplusones+1 WHERE id = {0} LIMIT 1", $post["user"]);
	Query("UPDATE {users} SET postplusonesgiven = postplusonesgiven+1 WHERE id = {0} LIMIT 1", $loguserid);
	Query("INSERT INTO {postplusones} (user, post) VALUES ({0}, {1})", $loguserid, $pid);
	$post["postplusones"]++;
	$starimg = 'https://i.imgur.com/gErHVso.png';
} else {
	Query("UPDATE {posts} SET postplusones = postplusones-1 WHERE id = {0} LIMIT 1", $pid);
	Query("UPDATE {users} SET postplusones = postplusones-1 WHERE id = {0} LIMIT 1", $post["user"]);
	Query("UPDATE {users} SET postplusonesgiven = postplusonesgiven-1 WHERE id = {0} LIMIT 1", $loguserid);
	Query("DELETE FROM {postplusones} WHERE user = {0} AND post = {1}", $loguserid, $pid);
	$post["postplusones"]--;
	$starimg = 'https://i.imgur.com/zMAbCLC.png';
}

$starurl = actionLink("plusone", $post["id"], "key=".$loguser["token"]);
$starurl = htmlspecialchars($starurl);

echo "<a href=\"\" onclick=\"$(this.parentElement).load('$starurl'); return false;\"><img src=\"$starimg\"></a>".$post["postplusones"];
