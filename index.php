<?php

$starttime = microtime(true);
define('BLARG', 1);

$sidebarshow = false;

// change this to change your board's default page (in common.php now)
//define('MAIN_PAGE', 'home');

$ajaxPage = false;
if(isset($_GET['ajax']))
	$ajaxPage = true;

require(__DIR__.'/lib/common.php');

$layout_crumbs = '';
$layout_actionlinks = '';

if (isset($_GET['forcelayout']))
{
	setcookie('forcelayout', (int)$_GET['forcelayout'], time()+365*24*3600, URL_ROOT, "", false, true);
	die(header('Location: '.$_SERVER['HTTP_REFERER']));
}

$layout_birthdays = getBirthdaysText();

$tpl->assign('logusername', htmlspecialchars($loguser['displayname'] ?: $loguser['name']));
$tpl->assign('loguserlink', UserLink($loguser));

$metaStuff = array(
	'description' => Settings::get('metaDescription'),
	'tags' => Settings::get('metaTags')
);


//=======================
// Do the page

$match = $router->match();

define('CURRENT_PAGE', $page);

ob_start();
$layout_crumbs = "";

$fakeerror = false;
if ($loguser['flags'] & 0x2)
{
	if (rand(0,100) <= 75)
	{
		Alert("Could not load requested page: failed to connect to the database. Try again later.", 'Error');
		$fakeerror = true;
	}
}

if (!$fakeerror) {
	try {
		// Throw the 404 page if we don't have a match already.
		if ($match === false)
			throw new Exception(404);
		else {
			// Set up the stuff for our page loader.
			$pageName = $match['target'];
			$pageParams = $match['params'];

			// MabiPro: Set this to Smarty for template purposes
			$tpl->assign('currentPage', $pageName);

			// Check first for plugin pages.
			if(array_key_exists($pageName, $pluginpages)) {
				// TODO: Make this cleaner than a hack.
				$plugin = $pluginpages[$pageName];
				$self = $plugins[$plugin];

				$page = __DIR__.'/plugins/' . $self['dir'] . '/pages/' . $pageName . '.php';
				if (file_exists($page))
					require_once($page);
				else
					throw new Exception(404);
			} else {
				// Check now for core pages.
				$page = __DIR__.'/pages/' . $pageName . '.php';

				if (file_exists($page))
					require_once($page);
				else
					throw new Exception(404);
			}
		}
	}
	catch(Exception $e) {
		// is this used at all?
		if ($e->getMessage() != 404) throw $e;
		require_once(__DIR__.'/pages/404.php');
	}
	catch(KillException $e)
	{
		// Nothing. Just ignore this exception.
	}
}

if($ajaxPage)
{
	ob_end_flush();
	die();
}

$layout_contents = ob_get_contents();
ob_end_clean();

//Do these things only if it's not an ajax page.
include(__DIR__."/lib/views.php");
setLastActivity();

//=======================
// Panels and footer

require(__DIR__.'/layout/userpanel.php');
require(__DIR__.'/layout/menus.php');

$mobileswitch = '';
if ($mobileLayout) $mobileswitch .= 'Mobile view - ';
if ($_COOKIE['forcelayout']) $mobileswitch .= '<a href="?forcelayout=0" rel="nofollow">Auto view</a>';
else if ($mobileLayout) $mobileswitch .= '<a href="?forcelayout=-1" rel="nofollow">Force normal view</a>';
else $mobileswitch .= '<a href="?forcelayout=1" rel="nofollow">Force mobile view</a><noscript>Javascript required</noscript>';


//=======================
// Notification bars

$notifications = getNotifications();

//=======================
// Misc stuff

$layout_time = formatdatenow();
$layout_onlineusers = getOnlineUsersText();
$layout_birthdays = getBirthdaysText();
$layout_views = '<span id="viewCount">'.number_format($misc['views']).'</span> '.__('views');

$layout_title = htmlspecialchars(Settings::get('boardname'));
if($title != '')
	$layout_title .= ' &raquo; '.$title;

$chat = '';
if (!HasPermission('admin.nolink'))
	$chat = '<span class="navButton"><a href="https://discord.gg/btQdJNw">Discord</a></span>';


//=======================
// Board logo and theme

$layout_logopic = 'img/logo.png';
if (!file_exists(__DIR__.'/'.$layout_logopic))
	$layout_logopic = 'img/logo.jpg';
$layout_logopic = resourceLink($layout_logopic);

if (!file_exists("themes/$theme")) { //Are we using some invalid theme?
	if ($loguserid) {
		$defaultTheme = Settings::get("defaultTheme");
		query("update {users} set theme='" . $defaultTheme . "' where id=".$loguserid);
		$theme = $defaultTheme;
	}
}

$favicon = resourceLink('img/favicon.ico');

$themefile = "themes/$theme/style.css";
if(!file_exists(__DIR__.'/'.$themefile))
	$themefile = "themes/$theme/style.php";

$layout_credits = 
'<img src="'.resourceLink('img/poweredbyblarg.png').'" style="float: left; margin-right: 3px;"> Mario Making Mods &middot; by [user=1], StapleButter [url=/credits]& others[/url]
Page rendered in '.sprintf('%.03f',microtime(true)-$starttime).' seconds (with '.$queries.' SQL queries and '.sprintf('%.03f',memory_get_usage() / 1024).'K of RAM).';

$sidebar = '';
if($sidebarshow == true) {
		$sidebar = '<td id="main-sidebar">
		<table id="sidebar" class="outline">
			<tr>
				<td class="cell1">
					<table class="outline margin">
						<tr class="header0"><th>Depot</th></tr>
								<tr class="cell0"><td><a href="/depot">Super Mario Maker Projects</a></td></tr>
								<tr class="cell1"><td><a href="/depot/level">Super Mario Maker Levels</a></td></tr>
								<tr class="cell0"><td><a href="/depot/remaker">Super Mario ReMaker</a></td></tr>
					</table>';
					if ($showconsoles == true) {
					$sidebar .= '<table class="outline margin">
						<tr class="header0"><th>Filter</th></tr>
							<form action="/'.$depoturl.'">
								<tr class="header1"><th>Console</th></tr>
								<tr class="cell0"><td><input type="radio" name="console" value="" checked> Both</td></tr>
								<tr class="cell0"><td><input type="radio" name="console" value="wiiu"><img src="https://cdn.discordapp.com/attachments/318888570691518465/394700847705227276/wii-u-games-tool.png">WiiU</a></td></tr>
								<tr class="cell1"><td><input type="radio" name="console" value="3ds"><img src="https://cdn.discordapp.com/attachments/260105243503624193/394725088735264773/600px-3DS_Icon.svg.png">3DS</a></td></tr>
								<tr class="header1"><th>Game Style</th></tr>
								<tr class="cell0"><td><input type="radio" name="style" value="" checked> All</td></tr>
								<tr class="cell0"><td><input type="radio" name="style" value="smb1"><img src="https://cdn.discordapp.com/emojis/364096385945174016.png" height="11" width="22"> SMB1</td></tr>
								<tr class="cell1"><td><input type="radio" name="style" value="smb3"><img src="https://cdn.discordapp.com/emojis/364096453091524610.png" height="11" width="22"> SMB3</td></tr>
								<tr class="cell0"><td><input type="radio" name="style" value="smw"><img src="https://cdn.discordapp.com/emojis/364096482539864075.png" height="11" width="22"> SMW</td></tr>
								<tr class="cell1"><td><input type="radio" name="style" value="nsmbu"><img src="https://cdn.discordapp.com/emojis/364096512654966784.png" height="11" width="22"> NSMBU</td></tr>
								<tr class="cell0"><td><input type="radio" name="style" value="custom">Custom</td></tr>
								<tr class="header1"><th>Themes</th></tr>
								<tr class="cell0"><td><input type="radio" name="theme" value="" checked> All</td></tr>
								<tr class="cell0"><td><input type="radio" name="theme" value="grass"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396187499724144640/Screenshot_2017-08-06_at_12.56.45_PM.png"> Grassland</td></tr>
								<tr class="cell1"><td><input type="radio" name="theme" value="under"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396188673634467841/Screenshot_2017-08-06_at_12.56.45_PM.png"> Underground</td></tr>
								<tr class="cell0"><td><input type="radio" name="theme" value="water"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396188394004283392/Screenshot_2017-08-06_at_12.56.45_PM.png"> Underwater</td></tr>
								<tr class="cell1"><td><input type="radio" name="theme" value="castle"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396189460754071553/Screenshot_2017-08-06_at_12.56.45_PM.png"> Castle</td></tr>
								<tr class="cell0"><td><input type="radio" name="theme" value="ghost"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396189134894399508/Screenshot_2017-08-06_at_12.56.45_PM.png"> Ghost House</td></tr>
								<tr class="cell0"><td><input type="radio" name="theme" value="airship"><img src="https://cdn.discordapp.com/attachments/346883750854131715/396188140353617920/Screenshot_2017-08-06_at_12.56.45_PM.png"> Airship</td></tr>
								<tr class="header1"><th><input type="submit" value="Submit"></th></tr>
							</form>
					</table>';
					}
			$sidebar .= '
				</td>
			</tr>
		</table>
	</td>';
}


$layout_contents = "<div id=\"page_contents\">$layout_contents</div>";

if($_SERVER["HTTP_X_PJAX"]) {
		RenderTemplate('pjax', array(
		'layout_contents' => $layout_contents,
		'layout_crumbs' => $layout_crumbs,
		'sidebar' => $sidebar,
		'layout_actionlinks' => $layout_actionlinks));
} else {
?>
<!doctype html>
<html>
<head>
	<title><?php print $layout_title; ?></title>

	<script src="<?php echo resourceLink("js/jquery.js");?>"></script>
	<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js' async></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js' async></script>
	<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
	
	<meta http-equiv="Content-Type" content="text/html; CHARSET=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="description" content="<?php echo $metaStuff['description']; ?>">
	<meta name="keywords" content="<?php echo $metaStuff['tags']; ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo getServerURLNoSlash($ishttps)?>" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="theme-color" content="#2196F3"/>
	<!-- Open Graph general (Facebook, Pinterest & Google+) -->
	<meta property="og:title" content="<?php echo $layout_title; ?>" />
	<meta name="og:title" content="<?php echo $layout_title; ?>">
	<meta name="og:description" content="<?php echo $metaStuff['description']; ?>">
	<meta name="og:url" content="https://mariomods.net/">
	<meta property="og:url" content="https://mariomods.net/" />
	<meta name="og:site_name" content="Mario Making Mods">
	<meta name="og:locale" content="en_US">
	<meta name="og:type" content="website">
	<meta property="og:image" content="https://mariomods.net/apple-touch-icon.png" />
	<!-- Twitter cards -->
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="<?php echo $layout_title; ?>" />
	<meta property="twitter:image" content="<?php echo $layout_logopic; ?>" />
	<meta name="twitter:description" content="<?php echo $metaStuff['description']; ?>">

	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $favicon;?>">
	<link rel="stylesheet" href="<?php echo resourceLink("css/common.css");?>">
	<link rel="stylesheet" id="theme_css" href="<?php echo resourceLink($themefile); ?>">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<link rel="manifest" href="/manifest.json">
	<noscript><link rel="stylesheet" href="https://use.fontawesome.com/a78963eb3c.css"></noscript>

	<script>
		(function(document,navigator,standalone) {
			// prevents links from apps from oppening in mobile safari
			// this javascript must be the first script in your <head>
			if ((standalone in navigator) && navigator[standalone]) {
				var curnode, location=document.location, stop=/^(a|html)$/i;
				document.addEventListener('click', function(e) {
					curnode=e.target;
					while (!(stop).test(curnode.nodeName)) {
						curnode=curnode.parentNode;
					}
					// Conditions to do this only on links to your own app
					// if you want all links, use if('href' in curnode) instead.
					if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
						e.preventDefault();
						location.href = curnode.href;
					}
				},false);
			}
		})(document,window.navigator,'standalone');
	</script>
	<script src="<?php print resourceLink("js/jquery-ui.js");?>"></script>
	<script src="<?php print resourceLink("js/tricks.js");?>"></script>
	<script src="<?php print resourceLink("js/jscolor.js");?>" async></script>
	<script src="<?php echo resourceLink("js/jquery.timeago.js");?>" async></script>
	<script>boardroot = <?php print json_encode(URL_ROOT); ?>;</script>
	<script src="https://use.fontawesome.com/8963bac2cd.js" async></script>
	<script>
  if ('serviceWorker' in navigator) {
    console.log("Will the service worker register?");
    navigator.serviceWorker.register('service-worker.js')
      .then(function(reg){
        console.log("Yes, it did.");
      }).catch(function(err) {
        console.log("No it didn't. This happened: ", err)
      });
  }
  if (window.webkitNotifications) {
    console.log('Your web browser does support notifications!');
	if (window.webkitNotifications.checkPermission() == 0) {} else {window.webkitNotifications.requestPermission(function(){});}
} else {
    console.log('Your web browser does not support notifications!');
}
</script>

	<?php $bucket = "pageHeader"; include(__DIR__."/lib/pluginloader.php"); ?>
	
	<?php if ($mobileLayout) { ?>
	<script type="text/javascript" src="<?php echo resourceLink('js/mobile.js'); ?>"></script>
	<?php if ($oldAndroid) { ?>
	<style type="text/css"> 
	#mobile-sidebar { height: auto!important; max-height: none!important; } 
	#realbody { max-height: none!important; max-width: none!important; overflow: scroll!important; } 
	</style>
	<?php }
	} ?>
</head>
<body style="width:100%; font-size: <?php echo $loguser['fontsize']; ?>%;">
<form action="<?php echo htmlentities(pageLink('login')); ?>" method="post" id="logout" style="display:none;"><input type="hidden" name="action" value="logout"></form>
<?php 
	if (Settings::get('maintenance'))
		echo '<div style="font-size:30px; font-weight:bold; color:red; background:black; padding:5px; border:2px solid red; position:absolute; top:30px; left:30px;">MAINTENANCE MODE</div>';

	RenderTemplate('pagelayout', array(
		'layout_contents' => $layout_contents,
		'layout_crumbs' => $layout_crumbs,
		'layout_actionlinks' => $layout_actionlinks,
		'headerlinks' => $headerlinks,
		'sidelinks' => $sidelinks,
		'layout_userpanel' => $layout_userpanel,
		'notifications' => $notifications,
		'boardname' => Settings::get('boardname'),
		'poratitle' => Settings::get('PoRATitle'),
		'poratext' => Settings::get('PoRAText'),
		'layout_logopic' => $layout_logopic,
		'layout_time' => $layout_time,
		'layout_views' => $layout_views,
		'layout_onlineusers' => $layout_onlineusers,
		'layout_birthdays' => $layout_birthdays,
		'layout_credits' => parseBBCode($layout_credits),
		'mobileswitch' => $mobileswitch,
		'sidebar' => $sidebar,
		'chat' => $chat));
?>
</body><script>$(function() {
    $(document).pjax('a', '#page-container', { 
        fragment: '#page-container', 
        timeout: 10 
    });
});
$(document).on('pjax:start', function() {NProgress.start();});
$(document).on('pjax:end', function() {NProgress.done();});</script>
</html>
<?php
}
$bucket = "finish"; include(__DIR__.'/lib/pluginloader.php');

?>

