<?php
define("BLARG", "1");

$ajaxPage = true;
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

$user = Fetch(Query("select regdate from {users} where id = {0}", $u));

$vd = date("m-d-y", $user['regdate']);
$dd = mktime(0, 0, 0, substr($vd, 0, 2), substr($vd, 3, 2), substr($vd, 6, 2));
$dd2 = mktime(0, 0, 0, substr($vd, 0, 2), substr($vd, 3, 2) + 1, substr($vd, 6, 2));

$nn = Query("select from_unixtime(date, '%Y%m%d') ymd, floor(date / 86400) d, count(*) c, max(num) m from {posts} where user = {0} group by ymd order by ymd", $u);

while($n = Fetch($nn)) {
	$p[$n[$d]] = $n[c];
	$t[$n[$d]] = $n[m];
}

for($i = 0; $dd + $i * 86400 < time(); $i++){
	$ps = Query("select count(*),max(num) from {posts} where user = {3} and date >= {0} + {1} * 86400 and date < {2} + {1} * 86400", $dd, $i, $dd2, $u);
	$p[$i] = Result($ps, 0, 0);
	$t[$i] = Result($ps, 0, 1);
}

$days = floor((time() - $dd) / 86400);
	$m=max($p);
	$img=ImageCreate($days,$m);
	$c['bg']  = ImageColorAllocate($img,  0,  0,  0);
	$c['bg1'] = ImageColorAllocate($img,  0,  0, 80); // Month colors
	$c['bg2'] = ImageColorAllocate($img,  0,  0,130); //
	$c['bg3'] = ImageColorAllocate($img, 80, 80,250); // (New year)
	$c['mk1'] = ImageColorAllocate($img,110,110,160); // Horizontal Rulers
	$c['mk2'] = ImageColorAllocate($img, 70, 70,130); //
	$c['bar'] = ImageColorAllocate($img,240,190, 40); // Post count bar
	$c['pt1']  = ImageColorAllocate($img,250,250,250); // Average
	$c['pt2']  = ImageColorAllocate($img,240,230,220); // Average (over top of post bar)
	for($i=0;$i<$days; ++$i){
		$num=date('m',$dd+$i*86400)%2+1;
		if(date('m-d',$dd+$i*86400)=='01-01') $num=3;
		ImageLine($img,$i,$m,$i,0,$c["bg$num"]);
	}
	for($i=50, $ct=1; $i<=$m; $i+=50, ++$ct)
		ImageLine($img,0,$m-$i,$days,$m-$i,(($ct&1) ? $c['mk2'] : $c['mk1']));
	$pt=0;
	for($i=0;$i<$days;$i++) {
		if (isset($p[$i])) {
			ImageLine($img,$i,$m,$i,$m-$p[$i],$c['bar']);
			$pt += $p[$i];
		}
		$avg = $pt/($i+1);
		ImageSetPixel($img,$i,$m-$avg,(($p[$i] >= $avg) ? $c['pt2'] : $c['pt1']));
	}
	Header('Content-type:image/png');
	ImagePNG($img);
	ImageDestroy($img);
