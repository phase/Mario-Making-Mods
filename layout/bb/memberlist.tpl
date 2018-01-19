	{if $pagelinks}<div class="smallFonts pages">Pages: {$pagelinks}</div>{/if}
	
	<table class="outline margin memberlist">
		<tr class="header1">
			<th style="width:30px;">#</th>
			<th>Avatar</th>
			<th>Name</th>
			<th style="width:75px;">User ID</th>
			<th style="width:75px;">Posts</th>
			<th style="width:75px;">Average</th>
			<th style="width:100px;">Birthday</th>
			<th style="width:150px;">Registered on</th>
			<th style="width:150px;">EZ Links</th>
		</tr>
		<tr class="cell2">
			<td colspan=9>Search results &mdash; {plural num=$numUsers what='user'} found</td>
		</tr>
		
		{foreach $users as $user}
		<tr class="cell{cycle values='0,1'}">
			<td class="cell2 center">{$user.num}</td>
			<td class="center" style="width:60px;">{$user.avatar}</td>
			<td>{$user.link}</td>
			<td class="center">{$user.id}</td>
			<td class="center">{$user.posts}</td>
			<td class="center">{$user.average}</td>
			<td class="center">{$user.birthday}</td>
			<td class="center">{$user.regdate}</td>
			<td class="center">{$user.listposts}{$user.listthreads}{$user.viewpm}{$user.sendpm}{$user.banuser}{$user.editprofile}{$user.editperms}</td>
		</tr>
		{/foreach}
		
	</table>
	
	{if $pagelinks}<div class="smallFonts pages">Pages: {$pagelinks}</div>{/if}
