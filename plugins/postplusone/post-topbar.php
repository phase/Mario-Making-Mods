<?php
$plusOne = '<span class="postplusone">';

if($poster['id'] !== $loguserid && $loguserid != 0)
{
	$url = actionLink("plusone", $post["id"], "key=".$loguser["token"]);
	$url = htmlspecialchars($url);
	$vote = Fetch(Query("SELECT * FROM {postplusones} WHERE post = {0} AND user = {1}", $post['id'], $loguserid));
	if (!$vote)
		$starimg = 'https://i.imgur.com/zMAbCLC.png';
	else
		$starimg = 'https://i.imgur.com/gErHVso.png';

	$plusOne .= "<a href=\"\" onclick=\"$(this.parentElement).load('".$url."'); return false;\"><img src=\"".$starimg."\"></a>";		
} else
	$plusOne .= '<img src="https://i.imgur.com/zMAbCLC.png">';

$plusOne .= $post["postplusones"];
$plusOne .= '</span>';

$extraLinks[] = $plusOne;
