<?php
 define("BLARG", "1");

 require __DIR__ . '/../lib/common.php';

if($_GET['id'])
	$u = (int)$_GET['id'];
else if($_GET['u'])
	$u = (int)$_GET['u'];
else {
	if(!$loguserid)
		die('No user specified! Please either specify a user, or log onto the board.');
	else
		$u = $loguserid;
}

 checknumeric($u);

	$user = fetch(Query("SELECT u.name, u.posts, u.regdate, u.displayname, r.* "
							."FROM users u "
							."LEFT JOIN usersrpg r ON r.id=u.id "
							."WHERE u.id = {0}", $u));

 $p = $user['posts'];
 $d = (time()-$user['regdate'])/86400;

 $it = $_GET['it'];
 checknumeric($it);


		 	$eqitems = Query("SELECT * FROM items WHERE    id='$user[eq1]'
														OR id='$user[eq2]'
														OR id='$user[eq3]'
														OR id='$user[eq4]'
														OR id='$user[eq5]'
														OR id='$user[eq6]'
														OR id='$it'
														OR id='$user[eq7]'
														OR id='$user[eq8]'");

 while($item = fetch($eqitems)){
   $items[$item['id']] = $item;
 }
 $ct = $_GET['ct'];
 if($ct){
   $GPdif=floor($items[$user['eq'.$ct]]['coins']*0.6)-$items[$it]['coins'];
   $user['eq'.$ct]=$it;
 }

 $st = getstats($user,$items);
 $st[GP]+=$GPdif;
 if($st[lvl]>0)
   $pct=1-calcexpleft($st['exp'])/lvlexp($st['lvl']);

header('Content-type: text/json');

$data = [
    'uid' => $u,
    'name' => htmlspecialchars($user['displayname'] ? $user['displayname'] : $user['name']),
    'hp' => $st['HP'],
    'mp' => $st['MP'],
    'level' => $st['lvl'],
    'exp' => $st['exp'],
    'next' => calcexpleft($st['exp']),
];

$jsondata = json_encode($data);

echo $jsondata;
