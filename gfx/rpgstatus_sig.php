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

	$user = fetch(Query("SELECT u.posts, u.regdate, r.* "
							."FROM users u "
							."LEFT JOIN usersrpg r ON r.id=u.id "
							."WHERE u.id = {0}", $u));

 $p = $user['posts'];
 $d = (time()-$user['regdate'])/86400;

 $it = $_GET['it'];
 checknumeric($it);

	$urlfont = $_GET['font'];
	if($urlfont){
		if($urlfont == '2')
			$pickfont = '2';
		else
			$pickfont = '';
	} else
		$pickfont='';


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
	$items[$item[id]] = $item;
 }
 $ct = $_GET['ct'];
 if($ct){
	$GPdif=floor($items[$user['eq'.$ct]][coins]*0.6)-$items[$it][coins];
	$user['eq'.$ct]=$it;
 }

 $st = getstats($user,$items);
 $st[GP]+=$GPdif;
 if($st[lvl]>0)
   $pct = 1 - calcexpleft($st[exp])/lvlexp($st[lvl]);

 Header('Content-type:image/png');
 $img=ImageCreate(520,88);
 $urlcolor = $_GET['color'];
	if($urlcolor){
		switch ($urlcolor) {
			case '1':
				imagesavealpha($img, true);
				imagealphablending($img, false);
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']=ImageColorAllocate($img, 200, 180, 225);
				$c['bxb2']=ImageColorAllocate($img, 160, 130, 190);
				$c['bxb3']=ImageColorAllocate($img,  90, 110, 130);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img,  15+$i/1.5,  8, 20+$i);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][ 1]	= ImageColorAllocate($img, 215,  91, 129);
				$c['bar'][ 2]	= ImageColorAllocate($img, 255, 136, 154);
				$c['bar'][ 3]	= ImageColorAllocate($img, 255, 139,  89);
				$c['bar'][ 4]	= ImageColorAllocate($img, 255, 251,  89);
				$c['bar'][ 5]	= ImageColorAllocate($img,  89, 255, 139);
				$c['bar'][ 6]	= ImageColorAllocate($img,  89, 213, 255);
				$c['bar'][ 7]	= ImageColorAllocate($img, 196,  33,  33);
				$c['bar'][ 8]	= ImageColorAllocate($img, 196,  66, 196);
				$c['bar'][ 9]	= ImageColorAllocate($img, 100,   0, 155);
				$c['bar'][10]	= ImageColorAllocate($img,  88,   0, 121);
				$c['bar'][11]	= ImageColorAllocate($img,   0, 174, 215);
				$c['bar'][12]	= ImageColorAllocate($img,   0,  99, 151);
				$c['bar'][13]	= ImageColorAllocate($img, 175, 175, 175);
				$c['bar'][14]	= ImageColorAllocate($img, 222, 222, 222);
				$c['bar'][15]	= ImageColorAllocate($img, 255, 255, 255);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'red':
				$c['bg']    =ImageColorAllocate($img, 40, 40, 90);
				$c['bxb0']  =ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']  =ImageColorAllocate($img,200,170,140);
				$c['bxb2']  =ImageColorAllocate($img,155,130,105);
				$c['bxb3']  =ImageColorAllocate($img,110, 90, 70);
				for($i=0;$i<100;$i++)
					$c[$i]  =ImageColorAllocate($img, 255, 0, 0);
				$c['barE1']	 = ImageColorAllocate($img,120,150,180);
				$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,255,198,222);
				$c['bar'][2]=ImageColorAllocate($img,255,115,181);
				$c['bar'][3]=ImageColorAllocate($img,255,156, 57);
				$c['bar'][4]=ImageColorAllocate($img,255,231,165);
				$c['bar'][5]=ImageColorAllocate($img,173,231,255);
				$c['bar'][6]=ImageColorAllocate($img, 57,189,255);
				$c['bar'][7]=ImageColorAllocate($img, 75,222, 75);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
				ImageColorTransparent($img,0);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontR = fontc(255,235,200, 255,210,160,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'green':
				$c['bg']    =ImageColorAllocate($img, 40, 40, 90);
				$c['bxb0']  =ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']  =ImageColorAllocate($img,200,170,140);
				$c['bxb2']  =ImageColorAllocate($img,155,130,105);
				$c['bxb3']  =ImageColorAllocate($img,110, 90, 70);
				for($i=0;$i<100;$i++)
					$c[$i]  =ImageColorAllocate($img, 0, 128, 0);
				$c['barE1']	 = ImageColorAllocate($img,120,150,180);
				$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,255,198,222);
				$c['bar'][2]=ImageColorAllocate($img,255,115,181);
				$c['bar'][3]=ImageColorAllocate($img,255,156, 57);
				$c['bar'][4]=ImageColorAllocate($img,255,231,165);
				$c['bar'][5]=ImageColorAllocate($img,173,231,255);
				$c['bar'][6]=ImageColorAllocate($img, 57,189,255);
				$c['bar'][7]=ImageColorAllocate($img, 75,222, 75);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
				ImageColorTransparent($img,0);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontR = fontc(255,235,200, 255,210,160,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'black':
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']=ImageColorAllocate($img, 0, 0, 0);
				$c['bxb2']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb3']=ImageColorAllocate($img, 255, 255, 255);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img,  0,  0, 0);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'white':
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb1']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb2']=ImageColorAllocate($img, 0, 0, 0);
				$c['bxb3']=ImageColorAllocate($img, 0, 0, 0);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img, 255, 255, 255);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);

                $fontY = fontc(127,127,127, 156,156,156,  0, 0, 0, $pickfont);
                $fontR = fontc(100,100,100, 160,160,160,  0, 0, 0, $pickfont);
                $fontG = fontc(127,127,127, 156,156,156,  0, 0, 0, $pickfont);
                $fontB = fontc(25,25,25, 50,50,50,  0, 0, 0, $pickfont);
                $fontW = fontc(214,214,214, 175,175,175,  0, 0, 0, $pickfont);
				break;


			//Gradient versions of themes
			case 'gradientgreen':
				$c['bg']    =ImageColorAllocate($img, 40, 40, 90);
				$c['bxb0']  =ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']  =ImageColorAllocate($img,200,170,140);
				$c['bxb2']  =ImageColorAllocate($img,155,130,105);
				$c['bxb3']  =ImageColorAllocate($img,110, 90, 70);
				for($i=0;$i<100;$i++)
					$c[$i]  =ImageColorAllocate($img, 0, 128+$i/2, 0);
				$c['barE1']	 = ImageColorAllocate($img,120,150,180);
				$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,255,198,222);
				$c['bar'][2]=ImageColorAllocate($img,255,115,181);
				$c['bar'][3]=ImageColorAllocate($img,255,156, 57);
				$c['bar'][4]=ImageColorAllocate($img,255,231,165);
				$c['bar'][5]=ImageColorAllocate($img,173,231,255);
				$c['bar'][6]=ImageColorAllocate($img, 57,189,255);
				$c['bar'][7]=ImageColorAllocate($img, 75,222, 75);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
				ImageColorTransparent($img,0);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontR = fontc(255,235,200, 255,210,160,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'gradientred':
				$c['bg']    =ImageColorAllocate($img, 40, 40, 90);
				$c['bxb0']  =ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']  =ImageColorAllocate($img,200,170,140);
				$c['bxb2']  =ImageColorAllocate($img,155,130,105);
				$c['bxb3']  =ImageColorAllocate($img,110, 90, 70);
				for($i=0;$i<100;$i++)
					$c[$i]  =ImageColorAllocate($img, 65+$i/2, 16, 25+$i/4);
				$c['barE1']	 = ImageColorAllocate($img,120,150,180);
				$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,255,198,222);
				$c['bar'][2]=ImageColorAllocate($img,255,115,181);
				$c['bar'][3]=ImageColorAllocate($img,255,156, 57);
				$c['bar'][4]=ImageColorAllocate($img,255,231,165);
				$c['bar'][5]=ImageColorAllocate($img,173,231,255);
				$c['bar'][6]=ImageColorAllocate($img, 57,189,255);
				$c['bar'][7]=ImageColorAllocate($img, 75,222, 75);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
				ImageColorTransparent($img,0);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontR = fontc(255,235,200, 255,210,160,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'gradientblack':
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']=ImageColorAllocate($img, 0, 0, 0);
				$c['bxb2']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb3']=ImageColorAllocate($img, 255, 255, 255);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img,  255+$i/2, 255+$i/2, 255+$i/2);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
			case 'gradientgrey':
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']=ImageColorAllocate($img, 0, 0, 0);
				$c['bxb2']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb3']=ImageColorAllocate($img, 255, 255, 255);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img,  355+$i/2, 355+$i/2, 355+$i/2);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
				break;
				
			case 'gradientlightgrey':
				$c['bg']=  ImageColorAllocatealpha($img, 40, 40, 90, 127);
				$c['bxb0']=ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']=ImageColorAllocate($img, 0, 0, 0);
				$c['bxb2']=ImageColorAllocate($img, 255, 255, 255);
				$c['bxb3']=ImageColorAllocate($img, 255, 255, 255);
				for($i=0;$i<100;$i++)
					$c[$i]=ImageColorAllocate($img,  128+$i/2, 128+$i/2, 128+$i/2);
				$c['barE1']=ImageColorAllocate($img,120,150,180);
				$c['barE2']=ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);

			default:
				$c['bg']	= ImageColorAllocate($img, 40, 40, 90);
				$c['bxb0']	= ImageColorAllocate($img,  0,  0,  0);
				$c['bxb1']	= ImageColorAllocate($img,200,170,140);
				$c['bxb2']	= ImageColorAllocate($img,155,130,105);
				$c['bxb3']	= ImageColorAllocate($img,110, 90, 70);
	
				for($i=0;$i<100;$i++)
					$c[$i]	= ImageColorAllocate($img, 10, 16, 60+$i/2);

				$c['barE1']	 = ImageColorAllocate($img,120,150,180);
				$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
				$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
				$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
				$c['bar'][3]=ImageColorAllocate($img,255,136,154);
				$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
				$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
				$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
				$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
				$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
				$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
				$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
				$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
				$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
				$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
				$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
				ImageColorTransparent($img,0);

				$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
				$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
				$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
				$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
				$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
		}
	} else {
		$c['bg']	= ImageColorAllocate($img, 40, 40, 90);
		$c['bxb0']	= ImageColorAllocate($img,  0,  0,  0);
		$c['bxb1']	= ImageColorAllocate($img,200,170,140);
		$c['bxb2']	= ImageColorAllocate($img,155,130,105);
		$c['bxb3']	= ImageColorAllocate($img,110, 90, 70);

		for($i=0;$i<100;$i++)
			$c[$i]	= ImageColorAllocate($img, 10, 16, 60+$i/2);

		$c['barE1']	 = ImageColorAllocate($img,120,150,180);
		$c['barE2']	 = ImageColorAllocate($img, 30, 60, 90);
		$c['bar'][1]=ImageColorAllocate($img,215, 91,129);
		$c['bar'][2]=ImageColorAllocate($img, 90, 22, 43);
		$c['bar'][3]=ImageColorAllocate($img,255,136,154);
		$c['bar'][4]=ImageColorAllocate($img,151,  0, 38);
		$c['bar'][5]=ImageColorAllocate($img,255,139, 89);
		$c['bar'][6]=ImageColorAllocate($img,125, 37,  0);
		$c['bar'][7]=ImageColorAllocate($img,255,251, 89);
		$c['bar'][8]=ImageColorAllocate($img, 83, 81,  0);
		$c['bar'][9]=ImageColorAllocate($img, 89,255,139);
		$c['bar'][10]=ImageColorAllocate($img,  0,100, 30);
		$c['bar'][11]=ImageColorAllocate($img, 89,213,255);
		$c['bar'][12]=ImageColorAllocate($img,  0, 66, 93);
		$c['bar'][13]=ImageColorAllocate($img,196, 33, 33);
		$c['bar'][14]=ImageColorAllocate($img, 70, 12, 12);
		ImageColorTransparent($img,0);

		$fontY = fontc(255,250,240, 255,240, 80,  0, 0, 0, $pickfont);
		$fontR = fontc(255,230,220, 240,160,150,  0, 0, 0, $pickfont);
		$fontG = fontc(190,255,190,  60,220, 60,  0, 0, 0, $pickfont);
		$fontB = fontc(160,240,255, 120,190,240,  0, 0, 0, $pickfont);
		$fontW = fontc(255,255,255, 210,210,210,  0, 0, 0, $pickfont);
	}


 box( 0, 0,32, 4, 0);
 box( 0, 5,18, 6, 0);
 box(19, 5,13, 6, 0);
 box(33, 0,32, 9, 0);

 twrite($fontB, 1, 1,0,'HP:      /', $pickfont);
 twrite($fontR, 3, 1,7,$st['HP'] - $user['hp'], $pickfont);
 twrite($fontY,11, 1,5,$st['HP'], $pickfont);
 twrite($fontB, 1, 2,0,'MP:      /', $pickfont);
 twrite($fontR, 3, 2,7,$st['MP'] - $st['mp'], $pickfont);
 twrite($fontY,11, 2,5,$st['MP'], $pickfont);

 for($i=2;$i<9;$i++){
   twrite($fontB, 34,-1+$i,0,$stat[$i].':', $pickfont);
   twrite($fontY, 37,-1+$i,6,$st[$stat[$i]], $pickfont);
 }

 twrite($fontB, 1,6,0,'Level', $pickfont);
 twrite($fontY, 13,6,4,$st['lvl'], $pickfont);
 twrite($fontB, 1,8,0,'EXP:', $pickfont);
 twrite($fontY, 8,8,9,$st['exp'], $pickfont);
 twrite($fontB, 1,9,0,'Next:', $pickfont);
 twrite($fontY, 8,9,9,calcexpleft($st['exp']), $pickfont);

 twrite($fontB,20,6,0,'Coins:', $pickfont);
 twrite($fontY,20,8,0,chr(0), $pickfont);
 twrite($fontG,20,9,0,chr(0), $pickfont);
 twrite($fontY,21,8,10,$st['GP'], $pickfont);
 twrite($fontG,21,9,10,$user['gcoins'], $pickfont);

	$sc[ 1]= 		    1;
	$sc[ 2]= 		    5;
	$sc[ 3]= 		   25;
	$sc[ 4]= 		  100;
	$sc[ 5]= 		  250;
	$sc[ 6]= 		  500;
	$sc[ 7]= 		 1000;
	$sc[ 8]= 		 2500;
	$sc[ 9]= 		 5000;
	$sc[10]= 		10000;
	$sc[11]= 	   100000;
	$sc[12]= 	  1000000;
	$sc[13]= 	 10000000;
	$sc[14]= 	100000000;
	$sc[15]=   1000000000;

 bars_sig();

 ImagePNG($img);
 ImageDestroy($img);
 
 function bars_sig(){
	global $st,$img,$c,$sc,$pct,$stat;

	for($s=1;@(max($st['HP'],$st['MP'])/$sc[$s])>113; $s++){}
	if(!$sc[$s]) $sc[$s]=1;
	ImageFilledRectangle($img,137,9,136+$st['HP']/$sc[$s],15,$c['bxb0']);
	ImageFilledRectangle($img,137,17,136+$st['MP']/$sc[$s],23,$c['bxb0']);
	ImageFilledRectangle($img,136,8,135+$st['HP']/$sc[$s],14,$c['bar'][$s]);
	ImageFilledRectangle($img,136,16,135+$st['MP']/$sc[$s],22,$c['bar'][$s]);

	for($i = 2; $i < 9; $i++) $st2[$i] = $st[$stat[$i]];
	for($s = 1;@(max($st2)/$sc[$s]) > 161; $s++) {}
	if(!$sc[$s]) $sc[$s] = 1;
	for($i = 2; $i < 9; $i++) {
		ImageFilledRectangle($img,361,-7+$i*8,360+$st[$stat[$i]]/$sc[$s], -1+$i*8,$c['bxb0']);
		ImageFilledRectangle($img,360,-8+$i*8,359+$st[$stat[$i]]/$sc[$s], -2+$i*8,$c['bar'][$s]);
	}

	$e1=128*$pct;
	ImageFilledRectangle($img,8,58,7+128,60,$c['bxb0']);
	ImageFilledRectangle($img,8,58,7+128,60,$c['barE2']);
	if($e1)
		ImageFilledRectangle($img,8,58,7+$e1,60,$c['barE1']);
}