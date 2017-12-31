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
		
		<tr class="cell2">
			<td></td>
			<td>
				{$fields.btnEditThread}
			</td>
		</tr>
		
	</table>

	<table class="outline margin form form_editthread">
		<tr class="header1">
			<th colspan=4>Depot</th>
		</tr>
		<tr class="header0">
			<th style="width: 25%;"></th>
			<th style="width: 25%;">ReMaker</th>
			<th style="width: 25%;">WiiU</th>
			<th style="width: 25%;">3DS</th>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Depot Screenshot
			</td>
			<td colspan=3>
				{$fields.screenshot}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Level Download
			</td>
			<td>
				{$fields.downloadlevelpc}
			</td>
			<td>
				{$fields.downloadlevelwiiu}<br><small>(If the level is hosted on Nintendo's Server, you may input just the Level ID)
			</td>
			<td>
				{$fields.downloadlevel3ds}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Theme Download
			</td>
			<td>
				{$fields.downloadthemepc}
			</td>
			<td>
				{$fields.downloadthemewiiu}
			</td>
			<td>
				{$fields.downloadtheme3ds}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Costume Download
			</td>
			<td>
				{$fields.downloadcostumepc}
			</td>
			<td>
				{$fields.downloadcostumewiiu}
			</td>
			<td></td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Game Style
			</td>
			<td colspan=3>
				{$fields.style}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Game Theme
			</td>
			<td colspan=3>
				{$fields.theme}
			</td>
		</tr>
	</table>

		<table class="outline margin form form_editthread">
		<tr class="cell2">
			<td></td>
			<td>
				{$fields.btnEditThread}
			</td>
		</tr>
		
	</table>