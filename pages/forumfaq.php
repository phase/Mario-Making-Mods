<?php
//  AcmlmBoard XD - Frequently Asked Questions page
//  Access: all
if (!defined('BLARG')) die();

$title = __("FAQ");
$links = array();
if(HasPermission('admin.editsettings'))
	$links[] = actionLinkTag(__("Edit the FAQ"), "editsettings", '', 'field=faqText');

MakeCrumbs(array(actionLink("faq") => __("FAQ")), $links);

makeThemeArrays();

$admin = Fetch(Query("select u.(_userfields) from {users} u where u.primarygroup={0}", Settings::get('rootGroup')));
$admin = userLink(getDataPrefix($admin, 'u_'));

$sexes = array(0=>__("Male"),1=>__("Female"),2=>__("N/A"));
$scolors = array(0 => 'color_male', 1 => 'color_female', 2 => 'color_unspec');

$gcolors = array();
$g = Query("SELECT title, color_male, color_female, color_unspec FROM {usergroups} WHERE type=0 ORDER BY rank");
while ($group = Fetch($g))
	$gcolors[] = $group;

$headers = "";
$colors = "";
foreach($sexes as $ss)
	$headers .= format(
"
	<th>
		{0}
	</th>
", $ss);
foreach($gcolors as $g)
{
	$cellClass = ($cellClass+1) % 2;
	$items = "";
	foreach($sexes as $sn => $ss)
		$items .= format(
"
	<td class=\"center\" style=\"padding:2px!important;\">
		<a href=\"javascript:void()\"><span style=\"color: {0};\">
			{1}
		</span></a>
	</td>
", htmlspecialchars($g[$scolors[$sn]]), htmlspecialchars($g['title']));
	$colors .= format(
"
<tr class=\"cell{0}\">
	{1}
</tr>
", $cellClass, $items);
}
$colortable = format("
<table class=\"outline\" style=\"margin-left: auto; margin-right: auto; width: 50%;\">
	<tr class=\"header1\">
		{0}
	</tr>
	{1}
</table>
", $headers, $colors);

//implode(", ", $themefiles)
$themelist = array();
foreach ($themefiles as $i=>$t)
	$themelist[$t] = $themes[$i];
ksort($themelist);

$finaltlist = '
<table class="outline" style="margin-left: auto; margin-right: auto; width: 75%;">
	<tbody>
		<tr class="header1"><th colspan="6" style="cursor:pointer;" onclick="$(\'#themelist\').toggle();">Themes (click to expand)</th></tr>
	</tbody>
	<tbody id="themelist" style="display:none;">
		<tr class="header0">
			<th style="width:16.67%;">$theme</th><th style="width:16.67%;">Name</th>
			<th style="width:16.67%;">$theme</th><th style="width:16.67%;">Name</th>
			<th style="width:16.67%;">$theme</th><th style="width:16.67%;">Name</th>
		</tr>';
	
$i = 0;
foreach ($themelist as $tid=>$tname)
{
	if (($i % 3) == 0)
		$finaltlist .= '
		<tr class="cell0">';
	
	$finaltlist .= '
			<td class="center"><code>'.htmlspecialchars($tid).'</code></td>
			<td class="cell1 center">'.htmlspecialchars($tname).'</td>';
	
	if (($i % 3) == 2)
		$finaltlist .= '
		</tr>';
	
	$i++;
}

if (($i % 3) != 0)
	$finaltlist .= '
			<td colspan="'.((3-($i%3))*2).'">&nbsp;</td>
		</tr>';

$finaltlist .= '
	</tbody>
</table>';

$faq = '<table class="outline"><tr class="header1"><th>
    The Mario Making Mods FAQ
</th></tr>
<tr class="cell0"><td>
    Listed here are the rules of Mario Making Mods. They should be followed by users at all times.<br>
    (Staff members are allowed to make exceptions to any rule, at any time, for any reason, or for no reason at all.)<br>
</td></tr></table>
<br>
<table class="outline"><tbody>
<tr class="header1"><th>
    General Disclaimer
</th></tr>

<tr class="cell0"><td>
    Mario Making Mods is a place for Super Mario Maker mods. This site does not support piracy in any way, shape, or form. Anyone attempting piracy here will be removed from the board on sight – no warnings, no questions asked.<br>
    This board is not accountable for anything said or done by users here. The views contained in posted content may not be representative of the views of the owners of this website.<br>
    We do not sell, distribute or otherwise disclose member information like IP addresses to any third party, unless it important.<br>
    If you have any questions about this site\'s policies or the information in this FAQ, please send a private message with your questions to <a href="/?page=memberlist&sort=&order=desc&group=staff&name=">a staff member</a>.
</td></tr>
<tr class="header0"><th>
    Cookies Disclaimer
</th></tr>

<tr class="cell0"><td>
    To make this site work properly, we sometimes place small data files called cookies on your device. Most big websites do this too.</td></tr>

<tr class="cell1"><td>
    <h4>What are cookies?</h4>
    A cookie is a small text file that a website saves on your computer or mobile device when you visit the site. It enables the website to remember your actions and preferences (such as login, language, font size and other display preferences) over a period of time, so you do not have to keep re-entering them whenever you come back to the site or browse from one page to another. </td></tr>

<tr class="cell0"><td>
    <h4>How do we use cookies?</h4>
    Blargboard uses a cookie to manage your login session. It stores a random, 16-character-long alphanumeric string in your browser to ensure that your login session will be connected to your browser only, and not to anyone else\'s.
    These are the only cookies our site uses.</td></tr>

<tr class="cell1"><td>
    <h4>How to control cookies</h4>
    You can control and/or delete cookies as you wish – for details, see <a href="http://aboutcookies.org/">aboutcookies.org</a>. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. If you do this, however, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.
</td></tr>
</tbody></table>

<br><table class="outline">
<tr>
<td class="cell1 center">
Welcome to the forums! We want this to be a friendly community, so some rules will have to be followed.<br></td>
</tr>
</table><br> 
<table class="outline margin">
    <tr class="header1"><th>
        Do I need to register to use the board?</th></tr>
        <tr class="cell0"><td>You can view the board without registering, but you need to register to post.
    </td></tr><tr class="header0"><th>
        <h4>Why should I register an account on the board?</h4></th></tr>
<tr class="cell1"><td>
        By registering a user account, you will be able to post on the board as well as use several features only accessible through registering, such as the ability to mark forums as read and private messaging. Unregistered users have guest access to the board, meaning they can view threads but not reply to them. They can also only see the board in whatever theme is the default.
    </td></tr></table><br><table class="outline margin">
    <tr class="header1"><th>
        <h4>What are all of these different layouts I see on posts?</h4></th></tr>
        <tr class="cell1"><td>They arere called post layouts. It is a customization option on the board for your posts to stand out. 
    </td></tr>
<tr class="header0"><th>
        <h4>How do I get a layout?</h4></th></tr>
        <tr class="cell1"><td>You must code one yourself. Sometimes there are others who might be willing to help you with your layout. If your layout is bad, you may find it deleted by a staff member. Make sure that when you design your layout, it isn\'t hard to read and does not stretch the tables.
    </td></tr>
<tr class="header0"><th>
        <h4>Cool! How do I make one?</h4></th></tr>
    <tr class="cell0"><td>
        You need to know the basics of CSS (style sheets) to make a post layout. Just make sure you abide by <a href="#post_layout_rules">the rules</a>, though.
    </td></tr></table><br><table class="outline margin">
</table><br><table class="outline margin">
<tr class="header1"><th>
        <h4>Can HTML be used?</h4></th></tr>
<tr class="cell0"><td>
        Yes, it can be used in posts, private messages, nearly everywhere except in things such as thread titles, usernames, etc.
    </td></tr>
<tr class="header1"><th>
        <h4>Is there some sort of replacement code for HTML?</h4></th></tr>
<tr class="cell1"><td>
        Yes, but it is a bit limited. Most of the possibilities are listed on the new thread/reply pages.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
        I just made a thread, where did it go?</th></tr>
        <tr class="cell0"><td>It was probably moved or deleted by a staff member. If it was deleted, please make sure your thread meets the criteria we have established. If it was moved, look into the other forums and consider why it was moved there. If you have any questions, PM a staff member.
    </td></tr>
<tr class="header1"><th>
        What can I do about my thread being either closed, trashed, or deleted?</th></tr>
<tr class="cell0"><td>
        First off, do not complain about this issue on the board about a thread being closed or deleted, and do not single out a certain mod or admin as being responsible. This will result in a ban. Learn from your mistakes and move on.<br><br>

If you feel you need clarification on why the thread was closed, or if you feel a mistake was made, contact a staff member, preferably a local moderator who iss in charge of the relevant forum, and politely explain the problem.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>How do I add my EXP and post counters to my signature or post?</h4></th></tr>
<tr class="cell0"><td>
Sorry, but you cannot add those features to your signature or post as this board software does not have things like Levels, experience or item shops, unlike the AcmlmBoard software it is based on.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>How do I get a custom title?</h4></th></tr>
<tr class="cell0"><td>
Custom titles are titles you can use in addition to, or in place of the ranks provided by the board. There are three ways to get them:<br><br>

1. After 100 posts, or if you have been around 2 months you will need 50.<br>
2. Being a member of staff.<br><br>

The custom title is a reward for being an active member of the community. Use of the custom title to impersonate staff, or to flame members/staff may result in the loss of custom title.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>What are avatars & mood avatars?</h4></th></tr>
<tr class="cell0"><td>
Avatars are a form of display picture which appears beside your posts and in your profile. Likewise, a mood avatar allows you to display a different picture as opposed to the one specified in your profile.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>Are private messages supported?</h4></th></tr>
<tr class="cell0"><td>
Yes. Your private message inbox is represented by the notifications tab. Likewise, you may send a user a message from here, or alternatively use "Send Private Message" from the user\'s profile.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>What is a Search Feature?</h4></th></tr>
<tr class="cell0"><td>
The search feature is used to search the forum posts and threads for whatever you may be looking for.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
    <h4>What is the calendar for?</h4></th></tr>
<tr class="cell0"><td>
The calendar lists user birthdays and special board events.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
        <h4>How do I edit a poll I made?</h4></th></tr>
<tr class="cell1"><td>
You unfortunately cannot edit a poll you made.
Poll editing is rather difficult to correctly implement and carries a risk of gross abuse.
Imagine if you will, that you make a poll about something, and you do not like the results. With poll editing, you could then go and switch the answers around.
    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
        <h4>What do the different username colors mean?</h4></th></tr>
<tr class="cell1"><td>
        Username colors indicate permissions levels. Use the following table for reference:<br>
        <br>
'.$colortable.'

<br><br>However, if you see a color that is not in this list, it is because staff can have a custom color nickname.

    </td></tr></table><br><table class="outline margin">
<tr class="header1"><th>
        <h4>I have a question about the site not answered here.</h4></th></tr>
<tr class="cell0"><td>
        Ask a staff member, they can help you. They aren\'t bad guys!
    </td></tr>
<br>
<tr class="header1"><th>
        <h4>I have a question about SMM modding.</h4></th></tr>
<tr class="cell1"><td>
        Post your question over <a href="/thread/73/">here</a> in order to not make the board look clustered.</td></tr></table>
<br>
<table class="outline margin">
<tr class="header1"><th>What are the Rules?</th></tr>
<tr class="header0"><th>
        <a name="post_layout_rules"></a>
        Post Layouts and Signatures
    </th></tr>
    <tr class="cell1"><td>
        While we allow very open and customizable layouts and sidebars, we have a few rules that will be strictly enforced. Please read them over and follow them. Loss of post layout privileges will be enacted for those who are repeat offenders. If in doubt ask a member of staff. The staff has discretion in deciding violations. This list is expected to be updated regularly, so please make sure to stay up to date.
    </td></tr>
    <tr class="cell0"><td>
        <h4>Do not make your layout difficult to read.</h4>
        Having similar text and background colors makes it difficult to read your posts.
    </td></tr><tr class="cell1"><td>
        <h4>Do not make your layout too wild.</h4>
        This includes spamming animations or crazy colors, or causing pages to lag.
    </td></tr><tr class="cell0"><td>
        <h4>Do not put visible text in your layout header. Do not put visible text in your signature without showing the signature separator.</h4>
        Putting visible text in the header is confusing because it looks like part of your posts. This applies to signatures, too, though it is okay there if you check the "Show signature separator" box.
    </td></tr><tr class="cell1"><td>
        <h4>The maximum allowed height for signatures is 150 pixels.</h4>
        Users with signatures larger than 150px will be contacted by staff and asked to edit their signature so it fits. If they do not fix the signature within a day of being notified, the signature may be deleted.
    </td></tr><tr class="cell0"><td>
        <h4>If a user persists into having a terrible layout despite staff continually removing it, they will be blocked from having a layout.</h4>
    </td></tr><tr class="cell1"><td>
        <h4>Layouts that mess with another user\'s name or posts, or are offensive in any other way, will be removed on sight.</h4>
    </td></tr><tr class="cell0"><td>
        <h4>It is also possible for users to block another user\'s layout.</h4>
This way, if a user has a layout you do not like but that layout does not break the rules, you will not see that layout anymore.
    </td></tr><tr class="cell1"><td>
        <h4>The board\'s staff is allowed to and has the ability to modify or remove any post layout or signature at any time and for any reason.</h4>
    </td></tr></table>';

$code1 = '<link rel="stylesheet" type="text/css" href="http://.../MyLayout_$theme.css">';
$code2 = '<link rel="stylesheet" type="text/css" href="http://.../MyLayout_'.$theme.'.css">';
$faq = str_replace("<themeexample1 />", DoGeshi($code1), $faq);
$faq = str_replace("<themeexample2 />", DoGeshi($code2), $faq);
$faq = str_replace("<themelist />", $finaltlist, $faq);
$faq = str_replace("<admin />", $admin, $faq);

echo $faq;

function DoGeshi($code)
{
	return "<code>".htmlspecialchars($code)."</code>";
}

?>