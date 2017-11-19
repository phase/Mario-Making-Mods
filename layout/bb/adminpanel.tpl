	<table class="outline margin adminlinks">
		<tr class="header1">
			<th>
				Administration tools
			</th>
		</tr>
		<tr class="cell1" style="position: center;">
			<td>
		{foreach $adminLinks as $link}
				<button>{$link}</button>
		{/foreach}
			</td>
		</tr>
	</table>

	<table class="outline margin admininfo">
		<tr class="header1">
			<th colspan="2">
				Information
			</th>
		</tr>
		{foreach $adminInfo as $label=>$contents}
		<tr class="cell{cycle name='admininfo' values='0,1'}">
			<td class="cell2 center">
				{$label}
			</td>
			<td>
				{$contents}
			</td>
		</tr>
		{/foreach}
	</table>