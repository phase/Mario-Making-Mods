<?php

  $rdmsg="";

$rURPG = Query("select * from {usersrpg} where id = {0}",$loguserid);
if(!NumRows($rURPG))
	Query("INSERT INTO {usersrpg} (id) VALUES ($loguserid)");

  $action = $_GET[action];
  if ($_POST[action] == "save" && HasPermission('admin.manage-shop-items')) {
	checknumeric($_GET[id]);
	$set = "";
	$id = $_GET['id'];
	$stype = "";
	if ($_GET['id'] != -1) {
		for($i=0;$i<9;$i++) {
			$stype.=(preg_match('/^x/', $_POST[$stat[$i]])?'m':'a');
			$set.="`s".$stat[$i]."`=".preg_replace('/[+x\.]/','',(strlen($_POST[$stat[$i]])?$_POST[$stat[$i]]:'0')).", ";
		}
		$set .= "`name`='$_POST[name]', `desc`='$_POST[desc]', `stype`='$stype', `coins`='$_POST[coins]', `coins2`='$_POST[coins2]', `cat`='$_POST[cat]', `hidden`=".($_POST['hidden'] ? "1" : "0");
		query("UPDATE items SET $set WHERE id='$_GET[id]'");
	} else {
		for($i=0;$i<9;$i++) {
			$stype .= (preg_match('/^x/', $_POST[$stat[$i]])?'m':'a');
			$set .= "`s$stat[$i]`=".preg_replace('/[x+-\.]/','',$_POST[$stat[$i]]).", ";
			$names .= "`s$stat[$i]`, ";
			$vals.="'".preg_replace('/[x+-\.]/','',$_POST[$stat[$i]])."', ";
		}
		$names.="`name`, `desc`, `stype`, `coins`, `coins2`, `cat`, `hidden`";
		$vals.="'$_POST[name]', '$_POST[desc]', '$stype', '$_POST[coins]', '$_POST[coins2]', '$_POST[cat]', ".($_POST['hidden'] ? "1" : "0")."";
		query("INSERT INTO items ($names) VALUES ($vals)");
				$id = insertid();
	}
	header("location: /shop?action=desc&id=$id");       
  }


  if(!$loguserid)
	  kill('You need to be logged on, in order to buy items from the shop.');

  $cat = $_GET[cat];
  checknumeric($cat);
$f = fopen("shop-ref.log","a");
fwrite($f,"[".date("m-d-y H:i:s")."] ".$ref."\n");
fclose($f);

  if(!HasPermission('user.editprofile')){
	$title = 'Item shop';
	kill("You have no permissions to do this!<br> <a href=./>Back to main</a>");
  } elseif (($_GET[action]=='edit' || $_GET[action]=='save' || $_GET[action]=='delete') && !HasPermission('admin.manage-shop-items')) { //Added (Sukasa)
	$title = 'Item shop';
	kill("You have no permissions to do this!<br> <a href=./>Back to main</a>");
  } else {
    $user = fetch(Query("SELECT u.name, u.posts, u.regdate, r.*
							FROM users u
							LEFT JOIN usersrpg r ON u.id=r.id
							WHERE u.id = '$loguserid'"));
	$p = $user['posts'];
	$d = (time() - $user['regdate'])/86400;
	$st = getstats($user);
	$GP = $st['GP'];
	
	Alert("Welcome to the Mario Making Mods item shop! Here is where you can buy items (such as weapons, Armor, and more) using your coins. In order to obtain coins, you'll need to post on the board.
			In the future, these items will allow you to battle another person with your items.");

	switch($action){
		case 'delete': //Added (Sukasa)
			checknumeric($_GET[id]);
			if ($_GET[id]) { //Can't delete nothing
				query("DELETE FROM items WHERE id='$_GET[id]'");
				for ($i=1;$i<7;$i++)
					query("UPDATE usersrpg SET `eq$i` = 0 WHERE `eq$i`='$_GET[id]'");
			}
		case '':
			$shops  =query('SELECT * FROM itemcateg ORDER BY corder');
			$eq     =fetch(query("SELECT * FROM usersrpg WHERE id=$loguser[id]"));
			$eqitems=query('SELECT * FROM items');

			while($item=fetch($eqitems))
				$items[$item[id]]=$item;

			while($shop=fetch($shops))
				$shoplist.=
					"  <tr>
							<td class=\"b cell2\">
								<a href=/shop?action=items&cat=$shop[id]#status>$shop[name]</a>
								<br><font class=sfont>$shop[description]</font>
							</td>
							<td class=\"b cell1\" align=\"center\"><a href=/shop?action=desc&id=".$eq["eq$shop[id]"].">".$items[$eq["eq$shop[id]"]][name]."</a></td>
";
			$title = 'Item shop';
			print "<img src=gfx/rpgstatus.php?u=$loguserid>";
			if($_COOKIE['pstbon'])
				print $rdmsg;
print       "<br>
".            "<table cellspacing=\"0\" class=\"outline margin\">
".            "  <tr class=\"header1\">
".            "    <th class=\"b h\">Shop</th>
".            "    <th class=\"b h\">Item equipped</th>
".            "$shoplist
".            "</table>
";
			break;
		case 'edit': //Added (Sukasa)
			checknumeric($_GET[id]);
			$item = fetch(query("SELECT * FROM items WHERE id='$_GET[id]' union select * from items where id='-1'"));
			$title = 'Item shop';
			print	"<style>
						.disabled {color:#888888}
						.higher   {color:#abaffe}
						.equal    {color:#ffea60}
						.lower    {color:#ca8765}
					</style>";

			$statlist = '';
			$catlist = "<option value='99'>Not Listed</option>";
			$shops = query('SELECT * FROM itemcateg ORDER BY corder');
			while($shop = fetch($shops)) {
				$catlist .= '<option value="'.$shop[id]."\"".(($shop[id]==$item[cat])||($item[cat]==99&&isset($_GET[cat])&&$shop[id]==$_GET[cat])?
					"selected='selected'":"").">".$shop[name]."</option>";
			}
			for($i = 0; $i < 9; $i++){
				$st = $item["s$stat[$i]"];
				if(substr($item[stype],$i,1) == 'm')
					$st = vsprintf('x%1.2f',$st/100);
				else {
					if($st > 0)
						$st = "+$st";
				}
				$itst = $item["s$stat[$i]"];
				$eqst = $eqitem["s$stat[$i]"];
				if(!$color) {
					if($itst > 0) $cl = 'higher';
					elseif($itst == 0) $cl = 'equal';
					elseif($itst< 0) $cl = 'lower';
				} else
					$cl = '';

				$statlist .= "	<td class=\"b cell2 align=\"center\"'><input type=\"text\" name='$stat[$i]' size='4' value='$st'></td>";
				$stathdr .= "		<td class=\"b cell1\" align=\"center\" width=6%>$stat[$i]</td>";
			}
			print	"<form action='/shop?action=save&id=$item[id]' method='post'><table cellspacing=\"0\" class=\"cell1 outline margin\">
						<td class=\"b cell1\" align=\"center\"><a href=/shop>Return to shop list</a>
					</table> <br>
					<img src=gfx/rpgstatus.php?u=$loguserid><br>
					<br>
					<table cellspacing=\"0\" class=\"c1 outline margin\" style=\"width:300px;\">
						<tr class=\"header1\" align=left>
							<th class=\"b h\" colspan=9><input type=\"text\" name='name' size='40' value=\"".str_replace("\"","&quot;",$item[name])."\"> <img src='img/coin.gif'> 
							<input type=\"text\" name='coins' size='7' value=\"".str_replace("\"","&quot;",$item[coins])."\"> <img src='img/coicell2.gif'> 
							<input type=\"text\" name='coins2' size='7' value=\"".str_replace("\"","&quot;",$item[coins2])."\"><input type=\"checkbox\" name='hidden' id='hidden' ".($item[hidden]?"checked":"")."><label for='hidden'>Hidden Item</label></td>
						<tr>
							$stathdr
						<tr>
							$statlist
						<tr>
							<td class=\"b cell2\" colspan=8><input type=\"text\" name='desc' size='40' value=\"".str_replace("\"","&quot;",$item[desc])."\">  
								<select name='cat' style='width: 115px'>$catlist</select>
							<td class=\"b cell2\"><input type=\"submit\" class=\"submit\" name='Save' value='Save'> </td>
					</table></form>";
			break;
		case 'desc':
			checknumeric($_GET[id]);
			$item = fetch(query("SELECT * FROM items WHERE id='$_GET[id]'"));
			$title = "Item Shop";
			print	"<style>
						.disabled	{color:#888888}
						.higher		{color:#abaffe}
						.equal		{color:#ffea60}
						.lower		{color:#ca8765}
					</style>";
			$statlist = '';
			for($i = 0; $i < 9; $i++){
				$st = $item["s$stat[$i]"];
				if(substr($item[stype],$i,1) == 'm'){
					$st = vsprintf('x%1.2f',$st/100);
					if($st == 100)
						$st='&nbsp;';
				} else {
					if($st > 0) $st = "+$st";
					if(!$st) $st='&nbsp;';
				}
				$itst=$item["s$stat[$i]"];
				$eqst=$eqitem["s$stat[$i]"];
				$edit="";
				if (HasPermission('admin.manage-shop-items')) //Added (Sukasa)
					$edit=" [<a href='/shop?action=edit&id=$item[id]'>Edit</a>] [<a href='shop.php?action=delete&id=$item[id]'>Delete</a>]";
				if(!$color){
					if($itst> 0) $cl='higher';
					elseif($itst==0) $cl='equal';
					elseif($itst< 0) $cl='lower';
				} else
					$cl = '';

				$statlist .= "	<td class=\"b cell2 align=\"center\" $cl'>$st</td>";
				$stathdr .= "	<td class=\"b cell1 align=\"center\" width=6%>$stat[$i]</td>";
			}
			print "	<table cellspacing=\"0\" class=\"margin outline\">
						<td class=\"b cell1\" align=\"center\"><a href=/shop>Return to shop list</a>
					</table> <br>
					<img src=gfx/rpgstatus.php?u=$loguserid><br>
					<br>
					<table cellspacing=\"0\" class=\"outline margin\" style=width:300px>
						<tr class=\"header1\" align=left>
							<th class=\"b h\" colspan=9>$item[name]$edit</th>
						<tr>
							$stathdr
						<tr>
							$statlist
						<tr>
							<td class=\"b cell2\" colspan=9><font class=sfont>$item[desc]</font></td>
					</table>";
			break;
		case 'items':
        
			$eq = fetch(query("SELECT eq$cat AS e FROM usersrpg WHERE id=$loguserid"));
			$eqitem = fetch(query("SELECT * FROM items WHERE id=$eq[e]"));

			$edit = '';
			if (HasPermission('admin.manage-shop-items'))
				$edit = " | <a href='/shop?action=edit&id=-1&cat=$cat'>Add new item</a>";

			$title = 'Item shop';
			MakeCrumbs(array(actionLink("shop") => __("Item Shop"), actionLink("shop", $cat) => __($cat)));
			print 	"<script>
						function preview(user,item,cat,name){
							document.getElementById('prev').src='gfx/rpgstatus.php?u='+user+'&it='+item+'&ct='+cat+'&'+Math.random();
							document.getElementById('pr').innerHTML='Equipped with<br>'+name+'<br>---------->';
						}
					</script>
					<style>
						.disabled {color:#888888}
						.higher   {color:#abaffe}
						.equal    {color:#ffea60}
						.lower    {color:#ca8765}
					</style>
					<table cellspacing=\"0\" class=\"c1 outline margin\">
						<td class=\"b cell1\" align=\"center\"><a href=/shop>Return to shop list</a> $edit
					</table>
					<br>
					<table cellspacing=\"0\" class=\"c1\" id=status>
						<td class=\"nb\" width=256><img src=gfx/rpgstatus.php?u=$loguserid></td>
						<td class=\"nb\" align=\"center\" width=150>
							<font class=fonts>
								<div id=pr></div>
							</font>
						</td>
						<td class=\"nb\">
							<img src=img/_.png id=prev>
					</table>
					<br>";
        $atrlist='';
        for($i=0;$i<9;$i++)
          $atrlist .= "    <th class=\"b h\" width=6%>$stat[$i]</th>";

        $seehidden = 0;
        if (HasPermission('admin.manage-shop-items'))
          $seehidden = 1;

        $items = query('SELECT * FROM items '
                          ."WHERE (cat=$cat OR cat=0) AND `hidden` <= $seehidden "
                          .'ORDER BY coins, name');

        print "<table cellspacing=\"0\" class=\"c1 outline margin\">
".            "  <tr class=\"header1\">
".            "    <th class=\"b h\" width=100>Commands</th>
".            "    <th class=\"b h\">Item</th>
".             $atrlist
."                <th class=\"b h\" width=6%><img src=img/coin.gif></th>
".            "    <th class=\"b h\" width=6%><img src=img/coin2.gif></th>
";

        while($item=fetch($items)){
          $buy = "<a href=/shop?action=buy&id=$item[id]>Buy</a>";
          $sell = "<a href=/shop?action=sell&cat=$cat>Sell</a>";
          $preview = "<a href='javascript:;' onclick=\"preview($loguserid,$item[id],$cat,'".addslashes($item[name])."')\">Preview <noscript>JavaScript Required</noscript></a>";

              if($item[id] && $item[id]==$eq[e])						$comm = $sell;
          elseif($item[id] && $item[coins]<=$GP && $item[coins2]<=0)	$comm = "$buy | $preview";
          elseif(!$item[id] && !$eq[e])									$comm = '-';
          else															$comm = $preview;

          if($item[id]==$eqitem[id] && $item[id] !== 0) $color = ' class=equal';
          elseif($item[coins]>$GP || $item[id] == 0)   $color = ' class=disabled';
          else                       $color = '';
          $atrlist = '';
          for($i=0;$i<9;$i++){
            $st = $item["s$stat[$i]"];
            if(substr($item[stype],$i,1) == 'm'){
              $st = vsprintf('x%1.2f',$st/100);
              if($st==100) $st = '&nbsp;';
            } else {
              if($st>0) $st="+$st";
              if(!$st) $st='&nbsp;';
            }
            $itst = $item["s$stat[$i]"];
            $eqst = $eqitem["s$stat[$i]"];

            if(!$color && substr($item[stype],$i,1)==substr($eqitem[stype],$i,1)){
                  if($itst> $eqst) $cl='higher';
              elseif($itst==$eqst) $cl='equal';
              elseif($itst< $eqst) $cl='lower';
            } else
              $cl='';

            $atrlist.= "
".            "    <td class=\"b cell2 align=\"center\" $cl'>$st</td>";
          }

          print
              "  <tr$color>
".            "    <td class=\"b cell2\" align=\"center\">$comm</td>
".            "    <td class=\"b cell1\"><b><a href=/shop?action=desc&id=$item[id]>$item[name]</a></b><br>$item[desc]</td>
".            "$atrlist
".            "    <td class=\"b cell1\" align=\"right\">$item[coins]</td>
".            "    <td class=\"b cell1\" align=\"right\">$item[coins2]</td>
";
        }
        print "</table>
";
      break;
      case 'buy':
        if(!strstr($_SERVER['HTTP_REFERER'],"/shop?action=items&cat=") || time()-$loguser[lastview]<1) die();

        $id=$_GET[id];
        checknumeric($id);
        $item=fetch(query("SELECT * FROM items WHERE id=$id"));

        if($item[coins] <= $GP && $item[coins2] <=0 && $item[cat]) { //FIXME
          $pitem=fetch(query("SELECT coins FROM items WHERE id=".$user['eq'.$item[cat]]));
          $pitem[coins] = intval($pitem[coins]); //fixes the problem if no prior item had been equipped/$pitem[coins] is empty for whatever reason [blackhole89]
          query("UPDATE usersrpg "
                     ."SET eq$item[cat]=$id, spent=spent-$pitem[coins]*0.6+$item[coins] "
                     ."WHERE id=$loguserid");

                  die(header("Location: ".actionLink("shop")));
        }
      break;
      case 'sell':
        $pitem = fetch(query("SELECT name, coins FROM items "
                           ."WHERE id=".$user['eq'.$cat]));
        query("UPDATE usersrpg "
                   ."SET eq$cat=0, spent=spent-$pitem[coins]*0.6 "
                   ."WHERE id=$loguserid");
                  die(header("Location: ".actionLink("shop")));
      break;
      default:
    }
  }