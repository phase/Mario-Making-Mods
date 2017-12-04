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
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Depot Screenshot
			</td>
			<td>
				{$fields.screenshot}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				3DS Theme Download
			</td>
			<td>
				{$fields.downloadtheme3ds}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				3DS Level Download
			</td>
			<td>
				{$fields.downloadlevel3ds}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Wii U Theme Download
			</td>
			<td>
				{$fields.downloadthemewiiu}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Wii U Level Download
			</td>
			<td>
				{$fields.downloadlevelwiiu}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Wii U Costume Download
			</td>
			<td>
				{$fields.downloadcostumewiiu}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Remaker Theme Download
			</td>
			<td>
				{$fields.downloadthemepc}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Remaker Level Download
			</td>
			<td>
				{$fields.downloadlevelpc}
			</td>
		</tr>
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">
				Remaker Costume Download
			</td>
			<td>
				{$fields.downloadcostumepc}
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
