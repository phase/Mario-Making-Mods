<?php
if (!defined('BLARG')) die();

include('lib/diff/Diff.php');
include('lib/diff/Diff/Renderer/inline.php');

$pid=(int)$_GET['id'];
$r1=(int)$_GET['o'];
$r2=(int)$_GET['n'];

if(!$pid)
	die('set an id');

checknumeric($pid);
checknumeric($r1);
checknumeric($r2);

	$rPost = Query("
			SELECT
				p.id, p.date, p.num, p.deleted, p.deletedby, p.reason, p.options, p.mood, p.ip,
				pt.text, pt.revision, pt.user AS revuser, pt.date AS revdate,
				u.(_userfields), u.(rankset,title,picture,posts,postheader,signature,signsep,lastposttime,lastactivity,regdate,globalblock,fulllayout),
				ru.(_userfields),
				du.(_userfields),
				t.forum fid
			FROM
				{posts} p
				LEFT JOIN {posts_text} pt ON pt.pid = p.id AND pt.revision = p.currentrevision
				LEFT JOIN {users} u ON u.id = p.user
				LEFT JOIN {users} ru ON ru.id=pt.user
				LEFT JOIN {users} du ON du.id=p.deletedby
				LEFT JOIN {threads} t ON t.id=p.thread
			WHERE p.id={0} AND t.forum IN ({1c})", $pid, ForumsWithPermission('forum.viewforum'));

	if(!NumRows($rPost))
		die(format(__("Unknown post ID #{0} or revision missing."), $pid));

	if (!HasPermission('mod.editposts', $post['fid']))
		die('No.');

if(!$r1||!$r2) $r1=$r2=1;

$d1= fetch(query("SELECT text FROM posts_text WHERE pid=$pid AND revision=$r1"));
$d2= fetch(query("SELECT text FROM posts_text WHERE pid=$pid AND revision=$r2"));

echo "<table cellspacing=\"0\" class=\"cell1\" width=100% height=100><tr class=\"n1\"><td class=\"b n2\"><font face='courier new'>";

$diff = new Text_Diff("native",array(explode("\n",$d1[text]),explode("\n",$d2[text])));
?>
<style type=text/css>
del {
	text-decoration: none;
	background-color: #800000;
	border: 1px dashed #FF0000;
	color: #cfcfcf;
	margin-left:1px;
	padding-left:1px;
	padding-right:1px;
}
ins {
	text-decoration: none;
	background-color: #008000;
	border: 1px dashed #00FF00;
	color: #ffffff;
	margin-left:1px;
	padding-left:1px;
	padding-right:1px;
}
</style>
<?php
$renderer = new Text_Diff_Renderer_inline();

echo str_replace("\n","<br>",$renderer->render($diff));

echo "</table>";