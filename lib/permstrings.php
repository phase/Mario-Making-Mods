<?php
if (!defined('BLARG')) die();

$permCats = array
(
	'user' => __('User permissions'),
	'forum' => __('Forum permissions'),
	'mod' => __('Moderation permissions'),
	'admin' => __('Administration permissions'),
);

$permDescs = array
(
	'user' => array
	(
		'user.editprofile' => __('Edit own profile'),
		'user.editdisplayname' => __('Edit display name'),
		'user.edittitle' => __('Edit custom title'),
		'user.editpostlayout' => __('Edit post layout'),
		'user.editbio' => __('Edit bio'),
		'user.editavatars' => __('Edit avatars'),
		'user.havetitle' => __('Always have custom title'),
		'user.sendpms' => __('Send private messages'),
		'user.postusercomments' => __('Post user comments'),
		'user.deleteownusercomments' => __('Delete own user comments'),
		'user.snow' => __('See the raining Snow'),
		'user.viewhiddenforums' => __('View hidden forums'),
	),
	'forum' => array
	(
		'forum.viewforum' => __('View forum'),
		'forum.postthreads' => __('Post threads'),
		'forum.postreplies' => __('Reply to threads'),
		'forum.doublepsot' => __('Double Post'),
		'forum.deleteownposts' => __('Delete own posts'),
		'forum.unlistedthreads' => __('View Unlisted Threads'),
		'forum.hiddenthreads' => __('View Hidden Threads'),
		'forum.hideads' => __('Hide Advertisements'),
		'forum.reportposts' => __('Report posts'),
		'forum.editownposts' => __('Edit own posts'),
		'forum.renameownthreads' => __('Rename own threads'),
		'forum.votepolls' => __('Vote to polls'),
	),
	'mod' => array
	(
		'mod.editposts' => __('Edit posts'),
		'mod.editfirstpost' => __('Edit First Post'),
		'mod.deleteposts' => __('Delete posts'),
		'mod.closethreads' => __('Close/Open threads'),
		'mod.stickthreads' => __('Stick/Unstick threads'),
		'mod.trashthreads' => __('Trash threads'),
		'mod.deletethreads' => __('Delete threads'),
		'mod.movethreads' => __('Move threads'),
		'mod.renamethreads' => __('Rename threads'),
	),
	'admin' => array
	(
		'admin.viewips' => __('View IP addresses'),
		'admin.viewadminpanel' => __('View admin panel'),
		'admin.viewadminnotices' => __('View admin notices'),
		'admin.viewlog' => __('View board log'),
		'admin.viewpms' => __('View all PMs'),
		'admin.viewallranks' => __('View all ranks'),
		'admin.banusers' => __('Ban users'),
		'admin.editusers' => __('Edit users'),
		'admin.editgroups' => __('Edit groups'),
		'admin.editforums' => __('Edit forums'),
		'admin.editsettings' => __('Edit board/plugins settings'),
		'admin.editsmilies' => __('Edit smilies'),
		'admin.manageipbans' => __('Manage IP bans'),
		'admin.ipsearch' => __('Use IP search'),
		'admin.adminusercomments' => __('Administrate user comments'),
		'admin.viewstaffpms' => __('Receive staff PMs'),
		'admin.nolink' => __('No links'),
	),
);

$guestPerms = array('forum.viewforum');

$bucket = 'permStrings'; include(__DIR__."/pluginloader.php");

?>