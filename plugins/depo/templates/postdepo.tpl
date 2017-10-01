	<table class="outline margin newspost" style="width:400px; overflow-y: scroll; height:300px; padding:5px 5px 5px 5px; flex: 1; text-align: center; flex-grow: 1;">
		<tr class="header1">
			<th>
				<span style='font-size:125%;'>
					{$post.title}
				</span>
				<br>
				<span style="font-weight:normal;font-size:97%;">
					Posted on {$post.formattedDate} by {$post.userlink}
				</span>
			</th>
		</tr>
		<tr class="cell0">
			<td style="padding:10px; max-height:200px !important;">
				{$post.text}
			</td>
		</tr>
		<tr class="cell1">
			<td>
				{$post.comments}. {$post.replylink}
			</td>
		</tr>
	</table>