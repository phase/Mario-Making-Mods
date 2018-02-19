{capture "breadcrumbs"}
{if $layout_crumbs || $layout_actionlinks}
	<table class="outline breadcrumbs"><tr class="header1">
		<th>
			{if $layout_actionlinks && count($layout_actionlinks)}
			<div style="float: right;">
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

<div id="body">
<div id="body-wrapper">
	<div id="main" style="padding:8px;">
		<div class="outline margin" id="header">
			<table class="outline margin">
				<tr>
					<td colspan="3" class="cell0 center">
						<table>
							<tr>
								<td style="border: 0px none;">
									<center><a href="{actionLink page='home'}"><img id="theme_banner" src="{$layout_logopic}" alt="{$boardname}" title="{$boardname}"></a></center>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="cell1">
					<td class="smallFonts" style="text-align: center; width: 10%;">
						{$layout_views}
					</td>
					<td class="smallFonts" style="text-align: center; width: 80%;">
									{foreach $sidelinks as $cat=>$links}
										{foreach $links as $url=>$text}
											<a href="{$url|escape}">{$text}</a> | 
										{/foreach}
									{/foreach}
									<a href="https://discord.gg/btQdJNw">Discord</a>
					</td>
					<td class="smallFonts" style="text-align: center; width: 10%;">
						{$layout_time}
					</td>
				</tr>
				<tr class="cell2">
					<td colspan="3" class="smallFonts" style="text-align: center">
					{if $loguserid}
						{$loguserlink}  
							{foreach $layout_userpanel as $url=>$text}
								| <a href="{$url|escape}">{$text}</a>
							{/foreach}
					{else}
						<a href="{actionLink page='register'}">Register</a>
						<a href="{actionLink page='login'}">Log in</a>
					{/if}
					</td>
				</tr>
				<tr class="cell2">
					<td colspan="3" class="smallFonts" style="text-align: center">
						{$layout_onlineusers}
					</td>
				</tr>
			</table>
		</div>

	<form action="{actionLink page='login'}" method="post" id="logout">
		<input type="hidden" name="action" value="logout" />
	</form>

	<div class="margin breadcrumbs_bar">
		{$smarty.capture.breadcrumbs}
	</div>
	{$layout_contents}
	<div class="margin breadcrumbs_bar">
		{$smarty.capture.breadcrumbs}
	</div>

	</div>
	<table class="cell2" id="footer" cellspacing="0">
		<td class="smallFonts" style="text-align: center; width: 50%;">
			<img src="/img/poweredbyblarg.png" style="margin-right: 3px;"><br>{$layout_credits}
		</td>
	</table>
	<br />
	<table class="cell2" id="footer" cellspacing="0">
		<td class="smallFonts" style="text-align: center; width: 10%;">
			{$mobileswitch}
		</td>
	</table>
	</div>
</div>
</div>
