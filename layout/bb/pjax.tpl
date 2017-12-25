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