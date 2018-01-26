<?php
error_reporting(-1);
ini_set("display_errors", 1);

 define("BLARG", "1");

 require(__DIR__.'/lib/common.php');
 require(__DIR__.'/lib/rpg/rpg.php');

	$urlfont = $_GET['font'];
	if($font)
		$pickfont='2';
	else
		$pickfont='';

 $t = $_GET['t'];
 $u = $_GET['u'];
 if(!$n = $_GET[n]) $n=50;
 if(!$s = $_GET[s]) $s=1;

 $val='posts';
 if($t=='lv')  $val = 'pow('.sqlexpval().',2/7)*100';
 if($t=='ppd') $val = 'posts/('.ctime().'-regdate)*8640000';

 if($s<0){
   $u=-$s;
   $uval = fetch(Query("SELECT {0} val FROM users WHERE id = {1}", $val, $u));
   $rank = fetch(Query("SELECT count(*) FROM users WHERE {0} > {1} AND id != {2}", $val, $uval, $u))+1;
   $s=floor($rank-($n-1)/2);
   if($s<1) $s=1;
 }

$users = Query("SELECT id, name, displayname, $val val "
                   ."FROM users "
                   ."ORDER BY val DESC, (id='$u') DESC "
                   ."LIMIT ".($s-1).",$n");

 Header('Content-type:image/png');
 $img = ImageCreate(512,($n+2)*8);
 $c[bg]    =ImageColorAllocate($img, 40, 40, 90);
 $c[bxb0]  =ImageColorAllocate($img,  0,  0,  0);
 $c[bxb1]  =ImageColorAllocate($img,200,170,140);
 $c[bxb2]  =ImageColorAllocate($img,155,130,105);
 $c[bxb3]  =ImageColorAllocate($img,110, 90, 70);
 for($i=0;$i<100;$i++)
   $c[$i]  =ImageColorAllocate($img, 10, 16, 60+$i/2);
 $c[bar][1]=ImageColorAllocate($img,255,189,222);
 $c[bar][2]=ImageColorAllocate($img,231,  0, 90);
 $c[bar][3]=ImageColorAllocate($img,255,115,181);
 $c[bar][4]=ImageColorAllocate($img,255,115, 99);
 $c[bar][5]=ImageColorAllocate($img,255,156, 57);
 $c[bar][6]=ImageColorAllocate($img,255,231,165);
 $c[bar][7]=ImageColorAllocate($img,173,231,255);
 $c[hlit]  =ImageColorAllocate($img, 47, 63,191);
 ImageColorTransparent($img,0);

 box(0,0,64,$n+2);

 $fontY=fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
 $fontR=fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
 $fontG=fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
 $fontB=fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
 $fontW=fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);

 $sc[1]=   1;
 $sc[2]=   3;
 $sc[3]=  10;
 $sc[4]=  20;
 $sc[5]=  40;
 $sc[6]= 100;
 $sc[7]= 200;
 $sc[8]=99999999;

for($i=$s;$user=fetch($users);$i++) {
	if($user[val]!=$rval){
		$rank=$i;
		$rval=$user[val];
	}
	if($i==$s){
		$rank=fetchresult("SELECT count(*) FROM users WHERE $val>$user[val] AND id!=$user[id]")+1;
		for($sn=1;($user[val]/$sc[$sn])>320;$sn++);
		$div=$sc[$sn];
		if(!$div) $div=1;
	}
	$y=$i-$s+1;
	if($user[id]==$u){
		ImageFilledRectangle($img,8,$y*8,503,$y*8+7,$c[hlit]);
		$fontu=$fontY;
	} else
	$fontu=$fontB;
	twrite($fontW, 0,$y,4,$rank);
	twrite($fontu, 5,$y,0,substr(mb_convert_encoding($user[name], "ISO-8859-1"),0,12));
	twrite($fontY,16,$y,6,floor($user[val]));
	if(($sx=$user[val]/$div)>=1){
		ImageFilledRectangle($img,185,$y*8+1,184+$sx,$y*8+7,$c[bxb0]);
		ImageFilledRectangle($img,184,$y*8  ,183+$sx,$y*8+6,$c[bar][$sn]);
	}
}

 ImagePNG($img);
 ImageDestroy($img);