<?php
//  AcmlmBoard XD - User profile page
//  Access: all
if (!defined('BLARG')) die();

$id = $pageParams['id'];

if(!isset($id))
	Kill(__("Please Specify a userID."));

checknumeric($id);

$rUser = Query("select u.* from {users} u where u.id={0}",$id);
if(NumRows($rUser))
	$user = Fetch($rUser);
else
	Kill(__("Unknown user ID."));

$uname = $user['displayname'] ?: $user['name'];

$ugroup = $usergroups[$user['primarygroup']];
$usgroups = array();

$res = Query("SELECT groupid FROM {secondarygroups} WHERE userid={0}", $id);
while ($sg = Fetch($res)) $usgroups[] = $usergroups[$sg['groupid']];

if($id == $loguserid) {
	Query("update {users} set lastprofileview={1} where id={0}", $loguserid, time());
	DismissNotification('profilecomment', $loguserid, $loguserid);
}

$canDeleteComments = ($id == $loguserid && HasPermission('user.deleteownusercomments')) || HasPermission('admin.adminusercomments');
$canComment = (HasPermission('user.postusercomments') && $user['primarygroup'] != Settings::get('bannedGroup')) || HasPermission('admin.adminusercomments');

if($loguserid && $_REQUEST['token'] == $loguser['token']) {
	if($_GET['block']) {
		$rBlock = Query("select * from {blockedlayouts} where user={0} and blockee={1}", $id, $loguserid);
		$isBlocked = NumRows($rBlock);
		if($isBlocked)
			$rBlockAction = Query("delete from {blockedlayouts} where user={0} and blockee={1} limit 1", $id, $loguserid);
		else
			$rBlockAction = Query("insert into {blockedlayouts} (user, blockee) values ({0}, {1})", $id, $loguserid);
		die(header("Location: ".pageLink("profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			))));
	}

	if($http->get('action') == "delete") {
		$postedby = FetchResult("SELECT cid FROM {usercomments} WHERE uid={0} AND id={1}", $id, (int)$http->get('cid'));
		if ($canDeleteComments || ($postedby == $loguserid && HasPermission('user.deleteownusercomments'))) {
			Query("delete from {usercomments} where uid={0} and id={1}", $id, (int)$http->get('cid'));
			if ($loguserid != $id) {
				// dismiss any new comment notification that has been sent to that user, unless there are still new comments
				$lastcmt = FetchResult("SELECT date FROM {usercomments} WHERE uid={0} ORDER BY date DESC LIMIT 1", $id);
				if ($lastcmt < $user['lastprofileview'])
					DismissNotification('profilecomment', $id, $id);
			}
			die(header("Location: ".pageLink("profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			))));
		}
	}

	if(isset($_POST['actionpost']) && !empty($_POST['text']) && $canComment) {
		$text = utfmb4String($_POST['text']);
		$rComment = Query("insert into {usercomments} (uid, cid, date, text) values ({0}, {1}, {2}, {3})", $id, $loguserid, time(), $text);
		if($loguserid != $id)
			SendNotification('profilecomment', $id, $id);
		die(header("Location: ".pageLink("profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			))));
	}
}



if($loguserid)
{
	if (Settings::get('postLayoutType'))
	{
		$blocktext = __('Block layout');
		$unblocktext = __('Unblock layout');
	}
	else
	{
		$blocktext = __('Block signature');
		$unblocktext = __('Unblock signature');
	}
	
	$rBlock = Query("select * from {blockedlayouts} where user={0} and blockee={1}", $id, $loguserid);
	$isBlocked = NumRows($rBlock);
	if($isBlocked)
		$blockLayoutLink = pageLinkTag($unblocktext, "profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			), "block=0&token={$loguser['token']}");
	else
		$blockLayoutLink = pageLinkTag($blocktext, "profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			), "block=0&token={$loguser['token']}");
}

$daysKnown = (time()-$user['regdate'])/86400;
if (!$daysKnown) $daysKnown = 1;

$posts = FetchResult("select count(*) from {posts} where user={0}", $id);
$threads = FetchResult("select count(*) from {threads} where user={0}", $id);
$averagePosts = sprintf("%1.02f", $user['posts'] / $daysKnown);
$averageThreads = sprintf("%1.02f", $threads / $daysKnown);

$minipic = getMinipicTag($user);


if($user['rankset']) {
	$currentRank = GetRank($user["rankset"], $user["posts"]);
	$toNextRank = GetToNextRank($user["rankset"], $user["posts"]);
	if($toNextRank)
		$toNextRank = Plural($toNextRank, "post");
}
if($user['title'])
	$title = preg_replace('@<br.*?>\s*(\S)@i', ' &bull; $1', strip_tags(CleanUpPost($user['title'], "", true), "<b><strong><i><em><span><s><del><img><a><br><br/><small>"));

if($user['homepageurl']) {
	$nofollow = "";
	if(Settings::get("nofollow"))
		$nofollow = "rel=\"nofollow\"";
			
	if($user['homepagename'])
		$homepage = "<a $nofollow target=\"_blank\" href=\"".htmlspecialchars($user['homepageurl'])."\">".htmlspecialchars($user['homepagename'])."</a> - ".htmlspecialchars($user['homepageurl']);
	else
		$homepage = "<a $nofollow target=\"_blank\" href=\"".htmlspecialchars($user['homepageurl'])."\">".htmlspecialchars($user['url'])."</a>";
	$homepage = securityPostFilter($homepage);
}

$emailField = __("Private");
if(empty($user['email']))
	$emailField = __("None given");
else if ($user['showemail'])
	$emailField = "<span id=\"emailField\">".__("Public")." <button style=\"font-size: 0.7em;\" onclick=\"$(this.parentNode).load('".URL_ROOT."ajaxcallbacks.php?a=em&amp;id=".$id."');\">".__("Show")."</button></span>";
else if (HasPermission('admin.editusers'))
	$emailField = "<span id=\"emailField\">".__("Private")." <button style=\"font-size: 0.7em;\" onclick=\"$(this.parentNode).load('".URL_ROOT."ajaxcallbacks.php?a=em&amp;id=".$id."');\">".__("Snoop")."</button></span>";


$profileParts = array();

$temp = array();
$temp[__("Name")] = $minipic . htmlspecialchars($user['displayname'] ? $user['displayname'] : $user['name']) . ($user['displayname'] ? " (".htmlspecialchars($user['name']).")" : "");
if($title)
	$temp[__("Title")] = $title;
$temp[__("User ID")] = $id;
	
$glist = '<strong class="userlink" style="color: '.htmlspecialchars($ugroup['color_unspec']).';">'.htmlspecialchars($ugroup['name']).'</strong>';
foreach ($usgroups as $sgroup)
{
	if ($sgroup['display'] > -1)
		$glist .= ', '.htmlspecialchars($sgroup['name']);
}
$temp[__("Groups")] = $glist;

if($currentRank)
	$temp[__("Rank")] = $currentRank;
if($toNextRank)
	$temp[__("To next rank")] = $toNextRank;

$temp[__("Total posts")] = format("{0} ({1} per day)", $posts, $averagePosts);
$temp[__("Total threads")] = format("{0} ({1} per day)", $threads, $averageThreads);
$temp[__("Registered on")] = format("{0} ({1} ago)", formatdate($user['regdate']), TimeUnits($daysKnown*86400));

$lastPost = Fetch(Query("
	SELECT
		p.id as pid, p.date as date,
		{threads}.title AS ttit, {threads}.id AS tid,
		{forums}.title AS ftit, {forums}.id AS fid
	FROM {posts} p
		LEFT JOIN {users} u on u.id = p.user
		LEFT JOIN {threads} on {threads}.id = p.thread
		LEFT JOIN {forums} on {threads}.forum = {forums}.id
	WHERE p.user={0}
	ORDER BY p.date DESC
	LIMIT 0, 1", $user["id"]));

if($lastPost)
{
	$thread = array();
	$thread['title'] = $lastPost['ttit'];
	$thread['id'] = $lastPost['tid'];
	$thread['forum'] = $lastPost['fid'];
	$tags = ParseThreadTags($thread['title']);

	if(!HasPermission('forum.viewforum', $lastPost['fid']))
		$place = __("a restricted forum");
	else
	{
		$ispublic = HasPermission('forum.viewforum', $lastPost['fid'], true);
		$pid = $lastPost['pid'];
		$place = actionLinkTag($tags[0], 'post', $pid)." (".actionLinkTag($lastPost['ftit'], 'forum', $lastPost['fid'], '', $ispublic?$lastPost['ftit']:'').")";
	}
	$temp[__("Last post")] = format("{0} ({1} ago)", formatdate($lastPost['date']), TimeUnits(time() - $lastPost['date'])) .
								"<br>".__("in")." ".$place;
} else
	$temp[__("Last post")] = __("Never");

$temp[__("Last view")] = format("{0} ({1} ago)", formatdate($user['lastactivity']), TimeUnits(time() - $user['lastactivity'])).'<br> at: <a>'.$user['lasturl'].'</a>';

if(HasPermission('admin.viewips'))
{
	$temp[__("Last user agent")] = htmlspecialchars($user['lastknownbrowser']);
	$temp[__("Last IP address")] = formatIP($user['lastip']);
}

$profileParts[__("General information")] = $temp;

$temp = array();
$temp[__("Email address")] = $emailField;
if(isset($homepage))
	$temp[__("Homepage")] = $homepage;
$profileParts[__("Contact information")] = $temp;

$temp = array();
$infofile = "themes/".$user['theme']."/themeinfo.txt";

if(file_exists($infofile))
{
	$themeinfo = file_get_contents($infofile);
	$themeinfo = explode("\n", $themeinfo, 2);
	
	$themename = htmlspecialchars(trim($themeinfo[0]));
	$themeauthor = htmlspecialchars(trim($themeinfo[1]));
}
else
{
	$themename = htmlspecialchars($user['theme']);
	$themeauthor = '';
}
$temp[__("Theme")] = $themename.' &middot; '.$themeauthor;
$temp[__("Items per page")] = Plural($user['postsperpage'], __("post")) . ", " . Plural($user['threadsperpage'], __("thread"));
$profileParts[__("Presentation")] = $temp;

$temp = array();
if($user['realname'])
	$temp[__("Real name")] = htmlspecialchars($user['realname']);
if($user['location'])
	$temp[__("Location")] = htmlspecialchars($user['location']);
if($user['birthday'])
	$temp[__("Birthday")] = formatBirthday($user['birthday']);

if(count($temp))
	$profileParts[__("Personal information")] = $temp;

if ($user['bio'] && $mobileLayout)
	$profileParts[__('Bio')] = CleanUpPost($user['bio']);


$bucket = "profileTable"; include(BOARD_ROOT."lib/pluginloader.php");



$cpp = 15;
$total = FetchResult("SELECT
						count(*)
					FROM {usercomments}
					WHERE uid={0}", $id);

$from = (int)$http->get('from');
if($http->get('from') === null)
	$from = 0;
$realFrom = $total-$from-$cpp;
$realLen = $cpp;
if($realFrom < 0) {
	$realLen += $realFrom;
	$realFrom = 0;
}
$rComments = Query("SELECT
		u.(_userfields),
		uc.id, uc.cid, uc.text, uc.date
		FROM {usercomments} uc
		LEFT JOIN {users} u ON u.id = uc.cid
		WHERE uc.uid={0}
		ORDER BY uc.date ASC LIMIT {1u},{2u}", $id, $realFrom, $realLen);

$pagelinks = PageLinksInverted(pageLink("profile", array(
					'id' => $id,
					'name' => slugify($user['name'])
				), 'from=')
			, $cpp, $from, $total);

$comments = array();
while($comment = Fetch($rComments)) {
	$cmt = array();

	$deleteLink = '';
	if($canDeleteComments || ($comment['cid'] == $loguserid && HasPermission('user.deleteownusercomments')))
		$deleteLink = "<small style=\"float: right; margin: 0px 4px;\">".
			actionLinkTag("&#x2718;", "profile", $id, "action=delete&cid=".$comment['id']."&token={$loguser['token']}")."</small>";

	$cmt['deleteLink'] = $deleteLink;

	$cmt['userlink'] = UserLink(getDataPrefix($comment, 'u_'));
	$cmt['formattedDate'] = relativedate($comment['date']);
	$cmt['text'] = CleanUpPost($comment['text']);
	
	$comments[] = $cmt;
}

$commentField = '';
if($canComment)
{
	$commentField = "
		<form name=\"commentform\" method=\"post\" action=\"".htmlentities(pageLink("profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			)))."\">
			<input type=\"hidden\" name=\"id\" value=\"$id\">
			<input type=\"text\" name=\"text\" style=\"width: 80%;\" maxlength=\"255\">
			<input type=\"submit\" name=\"actionpost\" value=\"".__("Post")."\">
			<input type=\"hidden\" name=\"token\" value=\"{$loguser['token']}\">
		</form>";
}

if($theme == "yule")
	$imagecolor = 1;
else
	$imagecolor = 0;

$rpgstatus = resourceLink("gfx/rpgstatus.php")."?u=".$id."&color=".$imagecolor;

$eqitemsR = query("SELECT * FROM usersrpg WHERE id='$id'");
if(NumRows($eqitemsR)) {
	$equipitems = '<table class="outline margin"><tr class="header1"><th colspan=2>Equipped Items</th></tr>';

    $shops   = query('SELECT * FROM itemcateg ORDER BY corder');
	$eq      = fetch($eqitemsR);
    $eqitems = query("SELECT * FROM items WHERE id='$eq[eq1]' OR id='$eq[eq2]' OR id='$eq[eq3]' OR id='$eq[eq4]' OR id='$eq[eq5]' OR id='$eq[eq6]' OR id='$eq[eq7]' OR id='$eq[eq8]'");

   while($item = fetch($eqitems)){
   $items[$item[id]] = $item;
 }
     
   while($shop = fetch($shops)) {
     $equipitems .= "<tr class=\"cell2\"><td width=\"cell1\">".$shop['name']."</td><td><a href=\"".actionLink("shop")."?action=desc&id=".$eq['eq'.$shop['id']]."\">".$items[$eq['eq'.$shop['id']]]['name']."</a></td></tr>";
    }
	$equipitems .= '</table>';
}

$badgersR = Query("select * from {badges} where owner={0} order by color", $id);
if(NumRows($badgersR))
{
	$badgers = '<table class="outline margin"><tr class="header1"><th>User Badges</th></tr>';
	$colors = array("bronze", "silver", "gold", "platinum");
	while($badger = Fetch($badgersR)){
		$badgers .= '<tr class="cell1"><td>';
		if ($badger['color'] == '0' || $badger['color'] == '1' || $badger['color'] == '2' || $badger['color'] == '3' || $badger['color'] == '4')
			$badgers .= Format("<span class=\"badge {0}\">{1}</span> ", $colors[$badger['color']], $badger['name']);
		else
			$badgers .= Format("<img src=\"{0}\" alt=\"{1}\"> ", $badger['color'], $badger['name']);
		$badgers .= '</td></tr>';
	}
	$badgers .= '</table>';
}

RenderTemplate('profile', array(
	'username' => htmlspecialchars($uname), 
	'userlink' => UserLink($user),
	'profileParts' => $profileParts,
	'commentField' => $commentField,
	'comments' => $comments,
	'pagelinks' => $pagelinks,
	'rpgstatus' => $rpgstatus,
	'badgers' => $badgers,
	'equipitems' => $equipitems));	


if (!$mobileLayout) {
	if ($user['bio'])
		$previewPost['text'] = $user['bio'];
	else
		$previewPost['text'] = Settings::get("profilePreviewText");

	$previewPost['num'] = 0;
	$previewPost['id'] = 0;

	foreach($user as $key => $value)
		$previewPost['u_'.$key] = $value;

	MakePost($previewPost, POST_SAMPLE);
}


$links = array();

if (HasPermission('admin.banusers') && $loguserid != $id)
{
	if ($user['primarygroup'] != Settings::get('bannedGroup'))
		$links[] = actionLinkTag('Ban user', 'banhammer', $id);
	else
		$links[] = actionLinkTag('Unban user', 'banhammer', $id, 'unban=1');
}

if(HasPermission('user.editprofile') && $loguserid == $id)
	$links[] = actionLinkTag(__("Edit my profile"), "editprofile");
else if(HasPermission('admin.editusers'))
	$links[] = actionLinkTag(__("Edit user"), "editprofile", $id);

if(HasPermission('admin.editusers'))
	$links[] = actionLinkTag(__('Edit permissions'), 'editperms', '', 'uid='.$id);

if($loguserid == $id)
	$links[] = actionLinkTag(__("Show my PMs"), "private", "", "user=".$id);
elseif(HasPermission('admin.viewpms'))
	$links[] = actionLinkTag(__("Show PMs"), "private", "", "user=".$id);

if(HasPermission('user.sendpms'))
	$links[] = actionLinkTag(__("Send PM"), "sendprivate", "", "uid=".$id);

$links[] = actionLinkTag(__("Show posts"), "listposts", $id, "", $user['name']);
$links[] = actionLinkTag(__("Show threads"), "listthreads", $id, "", $user['name']);

if ($loguserid && isset($blockLayoutLink)) $links[] = $blockLayoutLink;

MakeCrumbs(array(pageLink("profile", array(
				'id' => $id,
				'name' => slugify($user['name'])
			)) => htmlspecialchars($uname)), $links);

$title = format(__("Profile for {0}"), htmlspecialchars($uname));

function IsReallyEmpty($subject)
{
	$trimmed = trim(preg_replace("/&.*;/", "", $subject));
	return strlen($trimmed) == 0;
}


?>
