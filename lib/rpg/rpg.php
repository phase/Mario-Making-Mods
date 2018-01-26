<?php
if (!defined('BLARG')) die();

function calclvlexp($l) {
	if($l==1)
		return 0;
	return floor(pow($l,7/2));
}

function calclvl($e) {
	$l=floor(pow($e,2/7));
	if(!$l) $l=1;
	if($e==calclvlexp($l+1))
		$l++;
	return $l;
}

function calcexp($p,$d) {
	return floor($p*@pow($p*$d,0.5));
}
function calcexpleft($exp) {
	return calclvlexp(calclvl($exp)+1)-$exp;
}
function lvlexp($lvl) {
	return calclvlexp($lvl+1)-calclvlexp($lvl);
}
function calcexpgainpost($p,$d) {
	return floor(1.5*@pow($p*$d,0.5));
}
function calcexpgaintime($p,$d) {
	return sprintf('%01.3f',172800*@(@pow(@($d/$p),0.5)/$p));
}
function sqlexpval() {
	return "posts*pow(posts*(".time()."-regdate)/86400,1/2)";
}
function sqlexp() {
	return sqlexpval().' exp';
}

$stat=array('HP','MP','Atk','Def','Int','MDf','Dex','Lck','Spd');
function basestat($p,$d,$stat) {
	$p+=0;
	$e=calcexp($p,$d);
	$l=calclvl($e);
	if($l=='NAN') return 1;
	switch($stat) {
		case 0: return (pow($p,0.26) * pow($d,0.08) * pow($l,1.11) * 0.95) + 20; //HP
		case 1: return (pow($p,0.22) * pow($d,0.12) * pow($l,1.11) * 0.32) + 10; //MP
		case 2: return (pow($p,0.18) * pow($d,0.04) * pow($l,1.09) * 0.29) +2; //Str
		case 3: return (pow($p,0.16) * pow($d,0.07) * pow($l,1.09) * 0.28) +2; //Atk
		case 4: return (pow($p,0.15) * pow($d,0.09) * pow($l,1.09) * 0.29) +2; //Def
		case 5: return (pow($p,0.14) * pow($d,0.10) * pow($l,1.09) * 0.29) +1; //Shl
		case 6: return (pow($p,0.17) * pow($d,0.05) * pow($l,1.09) * 0.29) +2; //Lck
		case 7: return (pow($p,0.19) * pow($d,0.03) * pow($l,1.09) * 0.29) +1; //Int
		case 8: return (pow($p,0.21) * pow($d,0.02) * pow($l,1.09) * 0.25) +1; //Spd
	}
}

function getstats($u,$items=0) {
	global $stat;
	$p = $u['posts'];
	$d=(time()-$u['regdate'])/86400;
	for($i=0;$i<9;$i++) $m[$i]=1;
	for($i=1;$i<7;$i++) {
		$item=$items[$u['eq'.$i]];
		for($k=0;$k<9;$k++) {
			$is=$item['s'.$stat[$k]];
			if(substr($item['stype'],$k,1)=='m') $m[$k]*=$is/100;
			else $a[$k]+=$is;
		}
	}
	for($i=0;$i<9;$i++) {
		$stats[$stat[$i]]=max(1,floor(basestat($p,$d,$i)*$m[$i])+$a[$i]);
	}
	$stats['GP'] = coins($p,$d)-$u[spent];
	$stats['exp'] = calcexp($p,$d);
	$stats['lvl'] = calclvl($stats[exp]);
	$stats['gcoins'] = $u[gcoins];
	return $stats;
}

function coins($p,$d) {
	$p+=0;
	if($p<0 or $d<0) return 0;
	return floor(pow($p,1.3) * pow($d,0.4) + $p*10);
}

function getstats2($u) {
	$user=fetch(Query("SELECT u.name, u.posts, u.regdate, r.* "
						 ."FROM users u "
						 ."LEFT JOIN usersrpg r ON r.id=u.id "
						 ."WHERE u.id = {0}", $u));

	$p=$user['posts'];
	$d=(time()-$user['regdate'])/86400;

	$it="0";

	$eqitems = fetch(Query("SELECT * FROM items WHERE id='$user[eq1]' OR id='$user[eq2]' OR id='$user[eq3]' OR id='$user[eq4]' OR id='$user[eq5]' OR id='$user[eq6]' OR id='$it'"));

	while($item=$eqitems)
		$items[$item[id]] = $item;
	$ct=$_GET['ct'];
	if($ct) {
		$GPdif=floor($items[$user['eq'.$ct]][coins]*0.6)-$items[$it][coins];
		$user['eq'.$ct]=$it;
	}

	$st=getstats($user,$items);
	$st[GP]+=$GPdif;
	if($st[lvl]>0)
		$pct=1-calcexpleft($st[exp])/lvlexp($st[lvl]);

	return $st;
}

function twrite($font,$x,$y,$l,$text) {
	global $img;
	$x*=8;
	$y*=8;
	$text.='';
	if(strlen($text)<$l) $x+=($l-strlen($text))*8;
	for($i=0;$i<strlen($text);$i++)
		ImageCopy($img,$font,$i*8+$x,$y,(ord($text[$i])%16)*8,floor(ord($text[$i])/16)*8,8,8);
}

function fontc($r1,$g1,$b1,$r2,$g2,$b2,$r3,$g3,$b3) {
	$font = ImageCreateFromPNG(__DIR__.'/font.png');
	ImageColorTransparent($font,1);
	ImageColorSet($font,6,$r1,$g1,$b1);
	ImageColorSet($font,5,($r1*2+$r2)/3,($g1*2+$g2)/3,($b1*2+$b2)/3);
	ImageColorSet($font,4,($r1+$r2*2)/3,($g1+$g2*2)/3,($b1+$b2*2)/3);
	ImageColorSet($font,3,$r2,$g2,$b2);
	ImageColorSet($font,0,$r3,$g3,$b3);
	return $font;
}
function box($x,$y,$w,$h){
	global $img,$c;
	$x*=8;
	$y*=8;
	$w*=8;
	$h*=8;
	ImageRectangle($img,$x+0,$y+0,$x+$w-1,$y+$h-1,$c[bxb0]);
	ImageRectangle($img,$x+1,$y+1,$x+$w-2,$y+$h-2,$c[bxb3]);
	ImageRectangle($img,$x+2,$y+2,$x+$w-3,$y+$h-3,$c[bxb1]);
	ImageRectangle($img,$x+3,$y+3,$x+$w-4,$y+$h-4,$c[bxb2]);
	ImageRectangle($img,$x+4,$y+4,$x+$w-5,$y+$h-5,$c[bxb0]);
	for($i=5;$i<$h-5;$i++){
		$n=(1-$i/$h)*100;
		ImageLine($img,$x+5,$y+$i,$x+$w-6,$y+$i,$c[$n]);
	}
}
function bars(){
	global $st,$img,$c,$sc,$pct,$stat;

	for($s=1;@(max($st[HP],$st[MP])/$sc[$s])>113;$s++){}
	if(!$sc[$s]) $sc[$s]=1;
	ImageFilledRectangle($img,137,41,136+$st[HP]/$sc[$s],47,$c[bxb0]);
	ImageFilledRectangle($img,137,49,136+$st[MP]/$sc[$s],55,$c[bxb0]);
	ImageFilledRectangle($img,136,40,135+$st[HP]/$sc[$s],46,$c[bar1][$s]);
	ImageFilledRectangle($img,136,48,135+$st[MP]/$sc[$s],54,$c[bar1][$s]);

	for($i=2;$i<9;$i++) $st2[$i]=$st[$stat[$i]];
	for($s=1;@(max($st2)/$sc[$s])>161;$s++){}
	if(!$sc[$s]) $sc[$s]=1;
	for($i=2;$i<9;$i++){
		ImageFilledRectangle($img,89,65+$i*8,88+$st[$stat[$i]]/$sc[$s], 71+$i*8,$c[bxb0]);
		ImageFilledRectangle($img,88,64+$i*8,87+$st[$stat[$i]]/$sc[$s], 70+$i*8,$c[bar1][$s]);
	}

	$e1=72*$pct;
	ImageFilledRectangle($img,9,209,8+72,215,$c[bxb0]);
	ImageFilledRectangle($img,8,208,7+72,214,$c[barE2]);
	if($e1)
		ImageFilledRectangle($img,8,208,7+$e1,214,$c[barE1]);
}

