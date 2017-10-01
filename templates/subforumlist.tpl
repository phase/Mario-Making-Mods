{foreach $categories as $cat}
  {foreach $cat.forums as $forum}
  <tr class="cell1">
		<td {if $forum.ignored} class="ignored"{/if}>
			{$forum.new}<h4>{$forum.link}</h4>
			<span class="smallFonts">
				{$forum.description}
				{if $forum.localmods}<br>Moderated by: {$forum.localmods}{/if}<br><br>
				{if $forum.lastpostdate}{$forum.threads} {if $forum.threads == 1}thread{else}threads{/if}, {$forum.posts} {if $forum.posts == 1}post{else}posts{/if}<br><br>

				Last post: {$forum.lastpostdate}, by {$forum.lastpostuser} <a href="{$forum.lastpostlink}">&raquo;</a>{else}No posts have been made in this forum yet.{/if}
			</span>
		</td>
  </tr>
  {/foreach}
{/foreach}