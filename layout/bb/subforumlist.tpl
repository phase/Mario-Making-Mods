{foreach $categories as $cat}
  {foreach $cat.forums as $forum}
  {if $forum.ignored}{else}<tr class="cell1">
    <td class="newMarker" style="border-right-width: 0px;">{$forum.new}</td>
    <td style="padding-left: 2em;">
      <h4>{$forum.link}</h4>
      <span class="smallFonts">
        {$forum.description}
        {if $forum.localmods}<br>Moderated by: {$forum.localmods}{/if}
      </span>
    </td>
    <td class="center cell2"><span>{$forum.threads}</span></td>
    <td class="center cell2"><span>{$forum.posts}</span></td>
    <td class="center smallFonts">
      <span>
      {if $forum.lastpostdate}
        {$forum.lastpostdate}<br>
        by {$forum.lastpostuser} <a href="{$forum.lastpostlink}">&raquo;</a>
      {else}
        &mdash;
      {/if}
      </span>
    </td>
  </tr>{/if}
  {/foreach}
{/foreach}