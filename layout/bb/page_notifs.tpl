<table class="outline margin">
<tr class="header1"><th>All notifications</th></tr>
					{$numnotifs=count($notifications)}
						{if $numnotifs}
							{foreach $notifications as $notif}
								<tr class="cell1"><td>{$notif.text}<br><small>{$notif.formattedDate}</small></td></tr>
							{/foreach}
						{else}
							<tr class="cell1"><td>No Notifications</td></tr>
						{/if}
</table>
