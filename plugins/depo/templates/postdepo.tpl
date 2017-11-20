	<table class="outline margin newspost" style="width:400px; padding:5px 5px 5px 5px; flex: 1; flex-grow: 1;">
		<tr class="header1 center">
			<th>
				<span style='font-size:125%;'>
					{$post.title}
				</span>
			</th>
		</tr>
		{if $post.description}
		<tr class="header0">
			<th>
				{$post.description}
			</th>
		</tr>
		{/if}
		<tr class="cell0 center">
			<td style="padding:10px; max-height:200px !important;">
				{if $post.screenshots}{$post.screenshot}<br><br>{/if}
				<span style="font-weight:normal;font-size:97%;">
					Posted on {$post.formattedDate} by {$post.userlink}
				</span>
			</td>
		</tr>
		<tr class="cell1 center">
			<td>
				{$post.comments}. {$post.replylink}
			</td>
		</tr>
	</table>