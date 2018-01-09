<?php
if (!defined('BLARG')) die();

$title = __('Report post');

if (!$loguserid) Kill(__('You must be logged in to report posts.'));
CheckPermission('user.reportposts');

$pid = (int)$pageParams['id'];
$post = Fetch(Query("SELECT p.*, pt.text FROM {posts} p LEFT JOIN {posts_text} pt ON pt.pid=p.id AND pt.revision=p.currentrevision WHERE p.id={0}", $pid));
if (!$post) Kill(__('Invalid post ID.'));

if ($post['deleted']) Kill(__('This post is deleted.'));

if ($post['user'] == $loguserid && HasPermission('user.deleteownposts'))
	Alert(__('You are reporting your own posts. If you want your post deleted, you can do it yourself. You should do this only if you want to make sure the information is correct/allowed.'));
else if($post['user'] == $loguserid && !HasPermission('forum.deleteownposts', $forum))
	Alert(__('You are reporting your own posts. You should do this only if you want to make sure the information is correct/allowed or if you want the staff to delete your post for you.'));

$thread = Fetch(Query("SELECT * FROM {threads} WHERE id={0}", $post['thread']));
if (!$thread) Kill(__('Unknown thread.'));
$fid = $thread['forum'];

if (!HasPermission('forum.viewforum', $fid))
	Kill(__('You may not access this forum.'));

if (HasPermission('mod.deleteposts', $forum) && $post['user'] !== $loguserid)
	Alert(__('If you want the post deleted, you can do it yourself. You should do this only if you want to make sure the information is correct/allowed With your other staff members.'));

Alert(__('Thank you for reporting illicit posts. 
However please make sure that you only report posts that are in direct violation of our Forum Rules. 
Please do not report posts simply because you disagree with them or that you find them offensive. Remember that Mario Making Mods is a place where the exchange of opinions is important to everyone.
If you are not sure whether a post is breaking the rules, either read the rules again or simply do not report it.'));

$tags = ParseThreadTags($thread['title']);
$isHidden = !HasPermission('forum.viewforum', $fid, true);

if ($_POST['report'])
{
	if ($_POST['key'] !== $loguser['token'])
		Kill(__('No.'));

	Query("INSERT INTO {pmsgs_text} (title,text) VALUES ({0},{1})",
		"Post report (post #{$pid})", '');
	$pmid = InsertId();
	
	Query("INSERT INTO {pmsgs} (id,userto,userfrom,date,ip,msgread,deleted,drafting)
		VALUES ({0},{1},{2},{3},{4},0,0,0)",
		$pmid, -1, $loguserid, time(), $_SERVER['REMOTE_ADDR']);
	
	$report = "<strong>Post report</strong>\n\n<strong>Post:</strong> ".actionLinkTag($tags[0], 'post', $pid).
		" (post #".$pid.")\n\n<strong>Message:</strong>\n".$_POST['message']."\n\n".actionLinkTag('Mark issue as resolved', 'showprivate', $pmid, 'markread=1');
		
	Query("UPDATE {pmsgs_text} SET text={0} WHERE pid={1}", $report, $pmid);

	SendNotification('report', $pmid, -1);
	
	die(header('Location: '.actionLink('post', $pid)));
}

MakeCrumbs(forumCrumbs($forum) + array(actionLink("thread", $tid, '', $isHidden?'':$tags[0]) => $tags[0], '' => __("Report post")));

$user = Fetch(Query("SELECT * FROM {users} WHERE id={0}", $post['user']));
foreach($user as $key => $value)
	$post['u_'.$key] = $value;

MakePost($post, POST_SAMPLE);


$fields = array(
	'message' => '<textarea id="text" name="message" rows=10></textarea>',
	
	'btnSubmit' => '<input type="submit" name="report" value="'.__('Submit report').'">',
);

echo '
	<form action="'.pageLink('reportpost', ['id'=>$pid]).'" method="POST">';
	
RenderTemplate('form_reportpost', array('fields' => $fields));

echo '
		<input type="hidden" name="key" value="'.$loguser['token'].'">
	</form>';
	
?>