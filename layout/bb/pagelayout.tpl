<table id="main" class="layout-table">
<tr>
<td id="main-header" colspan="2">
	<table id="header" class="outline">
		<tr>
			<td class="cell0 center">
					<a href="{actionLink page='home'}"><img id="theme_banner" src="{$layout_logopic}" alt="{$boardname}" title="{$boardname}"></a>
			</td>
		</tr>
		<tr class="header1">
			<th id="navBar">
				<div style="display:inline-block; float:right;">
					{if $loguserid}
					<div id="userMenuContainer" class="dropdownContainer">
						<div id="userMenuButton" class="navButton">
							{$loguserlink}
							<i class="icon-caret-down"></i>
						</div>
						<ul class="dropdownMenu">
							{foreach $layout_userpanel as $url=>$text}
								<li><a href="{$url|escape}">{$text}</a></li>
							{/foreach}
							<li><a href="#" onclick="$('#logout').submit(); return false;">Log out</a></li>
						</ul>
					</div>
					{$numnotifs=count($notifications)}
					<div id="notifMenuContainer" class="dropdownContainer {if $numnotifs}hasNotifs{else}noNotif{/if}" style="margin-right: 2px;">
						<div id="notifMenuButton" class="navButton">
							<a href="{actionLink page='notifications'}">Notifications</a>
							<span id="notifCount">{$numnotifs}</span>
							<i class="icon-caret-down"></i>
						</div>
						<ul id="notifList" class="dropdownMenu">
						{if $numnotifs}
							{foreach $notifications as $notif}
								<li>{$notif.text}<br><small>{$notif.formattedDate}</small>
							{/foreach}
						{/if}
						</ul>
					</div>
					{else}
					<div id="userMenuContainer" class="dropdownContainer" style="margin-right: 10px;">
						<div id="userMenuButton" class="navButton">
							Welcome Guest
							<i class="icon-caret-down"></i>
						</div>
						<ul class="dropdownMenu">
							<li><a href="{actionLink page='register'}">Register</a></li>
							<li><a href="{actionLink page='login'}">Log in</a></li>
						</ul>
					</div>
					{/if}
				</div>
				<div id="navMenuContainer">
					<span class="navButton"><a href="{actionLink page='home'}">Home</a></span>
					<div class="dropdownContainer">
						<div class="navButton">
							<a href="{actionLink page='board'}">Forums</a>
							<i class="icon-caret-down"></i>
						</div>
						<ul class="dropdownMenu">
							<li><a href="{actionLink page='board'}">Index</a></li>
							<li><a href="{actionLink page='lastposts'}">Latest posts</a></li>
							<li><a href="{actionLink page='faq/forum'}">FAQ/Rules</a></li>
							<li><a href="{actionLink page='search'}">Search</a></li>
							<li><a href="{actionLink page='memberlist'}">Member list</a></li>
							<li><a href="{actionLink page='ranks'}">Ranks</a></li>
							<li><a href="{actionLink page='online'}">Online users</a></li>
						</ul>
					</div>
					{foreach $headerlinks as $url=>$text}
						<span class="navButton"><a href="{$url|escape}">{$text}</a></span>
					{/foreach}
					{$chat}
				</div>
			</th>
		</tr>
		<tr class="cell0">
			<td class="smallFonts center" id="online-users">
				{$layout_onlineusers}{if $layout_birthdays}<br><br>{$layout_birthdays}{/if}
			</td>
		</tr>
		<tr class="header1"><th id="header-sep"></th></tr>
	</table>
</td>
</tr>

<tr>

{$sidebar}

{capture "breadcrumbs"}
{if $layout_crumbs || $layout_actionlinks}
		<table class="outline breadcrumbs"><tr class="header1">
			<th>
				{if $layout_actionlinks && count($layout_actionlinks)}
				<div class="actionlinks" style="float:right;">
					<ul class="pipemenu smallFonts">
					{foreach $layout_actionlinks as $alink}
						<li>{$alink}
					{/foreach}
					</ul>
				</div>
				{/if}
				{if $layout_crumbs && count($layout_crumbs)}
				<ul class="crumbLinks">
				{foreach $layout_crumbs as $url=>$text}
					<li><a href="{$url|escape}">{$text}</a>
				{/foreach}
				</ul>
				{/if}
			</th>
		</tr></table>
{/if}
{/capture}

<td id="main-page">
	<table id="page-container" class="layout-table">
	<tr><td class="crumb-container crumbContTop">
		{$smarty.capture.breadcrumbs}
	</td></tr>
	<tr><td class="contents-container">
		{$layout_contents}
	</td></tr>
	<tr><td class="crumb-container crumbContBottom">
		{$smarty.capture.breadcrumbs}
	</td></tr>
	</table>
</td>
</tr>

<tr>
<td id="main-footer" colspan="2">

	<table id="footer" class="outline">
	<tr>
	<td class="cell2">
		<table class="layout-table" style="line-height: 1.4em;">
			<tr>
			<td style="text-align: left;">
				{$layout_credits}
				<!-- {$perfdata} -->
			</td>
			<td style="text-align: right;">
				{$mobileswitch}
			</td>
		</table>
	</td>
	</tr>
	</table>

</td>
</tr>
</table>
