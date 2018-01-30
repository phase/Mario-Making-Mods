<?php
error_reporting(-1);
ini_set("display_errors", 1);

 define("BLARG", "1");

 require(__DIR__.'/lib/rpg/rpg.php');
 require(__DIR__.'/lib/mysql.php');

	if ($_GET['order'])
		$orderby = "`cnt`";
	else
		$orderby = "`u`.`posts`";

	$urlfont = $_GET['font'];
	if($urlfont){
		if($urlfont == '1')
			$pickfont = '1';
		else if($urlfont == '2')
			$pickfont = '2';
		else
			$pickfont = '';
	} else
		$pickfont='';

	$startdate	= floor((time() - (6 * 60 * 60)) / 86400) * 86400 + (6 * 60 * 60);
	$enddate		= $startdate + 86400;

	$users	= query("SELECT COUNT(*) as `cnt`, `u`.`name`, `u`.`displayname`, `u`.`posts` ".
							"FROM `posts` `p` ".
							"LEFT JOIN `users` `u` ON `p`.`user` = `u`.`id` ".
							"WHERE `p`.`date` >= '$startdate' AND `p`.`date` < '$enddate' ".
							"GROUP BY `p`.`user` ".
							"ORDER BY $orderby DESC ".
							"LIMIT 20");


 $img=ImageCreateTrueColor(512,192);
 $c[bg]    =ImageColorAllocate($img, 40, 40, 90);
 $c[bxb0]  =ImageColorAllocate($img,  0,  0,  0);
 $c[bxb1]  =ImageColorAllocate($img,200,170,140);
 $c[bxb2]  =ImageColorAllocate($img,155,130,105);
 $c[bxb3]  =ImageColorAllocate($img,110, 90, 70);
 for($i=0;$i<100;$i++)
   $c[$i]  =ImageColorAllocate($img, 65+$i/2, 16, 25+$i/4);
 $c[bar][1]=ImageColorAllocate($img,255,198,222);
 $c[bar][2]=ImageColorAllocate($img,255,115,181);
 $c[bar][3]=ImageColorAllocate($img,255,156, 57);
 $c[bar][4]=ImageColorAllocate($img,255,231,165);
 $c[bar][5]=ImageColorAllocate($img,173,231,255);
 $c[bar][6]=ImageColorAllocate($img, 57,189,255);
 $c[bar][7]=ImageColorAllocate($img, 75,222, 75);

   $c[gridline]  =ImageColorAllocateAlpha($img, 200,110,100, 100);
   $c[alternate]  =ImageColorAllocateAlpha($img,   0,  0,  0, 110);


 box(0,1,64,23); //44*8=352

 $fontB=fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
 $fontR=fontc(255,235,200, 255,210,160,  0, 0, 0, $pickfont);
 $fontW=fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);

 box(1,0,11,3); //44*8=352
 twrite($fontW,  2,  1, 0,"User", $pickfont);

 box(13,0,28,3); //44*8=352
 twrite($fontW, 14,  1, 0,"Total", $pickfont);

 box(42,0,21,3); //44*8=352
 twrite($fontW, 43,  1, 0,"Today", $pickfont);

 // more dramatic, better for lower post ranges
 // doubtful it'll ever go over 100 scale (you'd need 35.2k posts for that anyway)
 $sc[ 1]=       0.1;
 $sc[ 2]=       0.5;
 $sc[ 3]=       1;
 $sc[ 4]=       2;
 $sc[ 5]=       5;
 $sc[ 6]=      10;
 $sc[ 7]=      50;
 $sc[ 8]=99999999;

// for($s=1;($topposts/$sc[$s])>176;$s++);
// if(!$sc[$s]) $sc[$s]=1;

// imageline($img, 328, 0, 328, 255, $c[bar][7]);

 for ($i = 4; $i <= 23; $i += 2) {
	 imagefilledrectangle($img, 8, $i * 8, 504, $i * 8 + 7, $c[alternate]);
	}

 for ($i = 152; $i <= 332; $i += 10) {
	 imageline($img, $i, 3 * 8, $i, 23 * 8, $c[gridline]);
	}
	imageline($img, 152, 23*8, 332, 23*8, $c[gridline]);

for ($i = 384; $i <= 504; $i += 10) {
	imageline($img, $i, 3 * 8, $i, 23 * 8, $c[gridline]);
}
	imageline($img, 384, 23*8, 504, 23*8, $c[gridline]);

 for ($i = 0; $x = fetch($users); $i++) $userdat[$i] = $x;

 if (!$userdat) $userdat = array();

	$userdat2 = $userdat;
	foreach ($userdat2 as $key => $row) {
		$postcounts[$key]	= $row['posts'];
		$dailycounts[$key]	= $row['cnt'];
		$xxx++;
	}

	if ($xxx) {
		$maxp	= max($postcounts);
		$maxd	= max($dailycounts);
	}
	
		for($s=1;($maxp/$sc[$s])>176;$s++);
		if(!$sc[$s]) $sc[$s]=1;

		for($s2=1;($maxd/$sc[$s2])>120;$s2++);
		if(!$sc[$s2]) $sc[$s2]=1;

 $i	= -1;

 foreach($userdat as $i => $user) {
	$name	= mb_convert_encoding(htmlspecialchars($user['displayname'] ? $user['displayname'] : $user['name']), "ISO-8859-1");
	$posts	= $user['posts'];
	$daily	= $user['cnt'];
	$vline	= $i + 3;

	twrite($fontR,  1,$vline    , 0,substr($name,0,12), $pickfont);
	twrite($fontW, 13,$vline    , 5,$posts, $pickfont);

	twrite($fontW, 42,$vline, 5,$daily, $pickfont);

   
	ImageFilledRectangle($img,153,$vline*8+1,152+$posts/$sc[$s],$vline*8+7,$c[bxb0]);
	ImageFilledRectangle($img,152,$vline*8  ,151+$posts/$sc[$s],$vline*8+6,$c[bar][$s]);
	ImageFilledRectangle($img,385,$vline*8+1,385+$daily/$sc[$s2],$vline*8+7,$c[bxb0]);
	ImageFilledRectangle($img,384,$vline*8  ,384+$daily/$sc[$s2],$vline*8+6,$c[bar][$s2]);
 }

 Header('Content-type:image/png');
 ImagePNG($img);
 ImageDestroy($img);

 