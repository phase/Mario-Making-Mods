	<table class="outline margin form form_editthread">
		<tr class="header1">
			<th colspan=2>Edit thread</th>
		</tr>
		
		{if $canRename}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center" style="width: 20%;">
				Title
			</td>
			<td id="threadTitleContainer">
				{$fields.title}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center" style="width: 20%;">
				Description
			</td>
			<td id="threadTitleContainer">
				{$fields.description}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Thread Icon
			</td>
			<td class="threadIcons">
				{$fields.icon}
			</td>
		</tr>
		{/if}
		
		{if $canClose}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2"></td>
			<td>
				{$fields.closed}
			</td>
		</tr>
		{/if}
		
		{if $canStick}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2"></td>
			<td>
				{$fields.sticky}
			</td>
		</tr>
		{/if}
		
		{if $canMove}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Forum
			</td>
			<td>
				{$fields.forum}
			</td>
		</tr>
		{/if}

	</table>

	{if !$level && !$costume}
	{else}
	<table class="outline margin form form_editthread">
		<tr class="header1">
			<th colspan=4>Depot</th>
		</tr>
		{if $console}
		<tr class="header0">
			<th></th>
			<th style="width: 50%;">WiiU</th>
			<th style="width: 25%;">3DS</th>
		</tr>
		{/if}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Depot Screenshot
			</td>
			
			<td {if $console} colspan=2 {/if}>
				{$fields.screenshot}
			</td>
		</tr>
		{if $level}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Level Download
			</td>
			{if $console}
			<td class="center">
				{$fields.downloadlevelwiiu}<br><small>(If the level is hosted on Nintendo's Server, you may input just the Level ID)
			</td>
			<td>
				{$fields.downloadlevel3ds}
			</td>
			{else}
			<td>
				{$fields.downloadlevelpc}
			</td>
			{/if}
		</tr>
		{/if}
		{if $costume}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Theme Download
			</td>
			{if $console}
			<td>
				{$fields.downloadthemewiiu}
			</td>
			<td>
				{$fields.downloadtheme3ds}
			</td>
			{else}
			<td>
				{$fields.downloadthemepc}
			</td>
			{/if}
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Costume Download
			</td>
			{if $console}
			<td>
				{$fields.downloadcostumewiiu}
			</td>
			<td></td>
			{else}
			<td>
				{$fields.downloadcostumepc}
			</td>
			{/if}
		</tr>
		{/if}
		{if $console}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Game Style
			</td>
			<td colspan=2>
				{$fields.style}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Game Theme
			</td>
			<td colspan=2>
				{$fields.theme}
			</td>
		</tr>
		{/if}
	</table>
	{/if}

		<table class="outline margin form form_editthread">
		<tr class="cell2 center">
			<td>
				{$fields.btnEditThread}
			</td>
		</tr>
		
	</table>