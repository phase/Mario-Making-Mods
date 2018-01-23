<?php
if (!defined('BLARG')) die();

CheckPermission('admin.editusers');

if ($http->post('submit')) {
	if($http->post('option') == 'add') {
		if ($http->post('userid') && $http->post('groupid')) {
			Query("INSERT INTO {secondarygroups} (userid,groupid) VALUES ({0},{1})",
				$http->post('userid'), $http->post('groupid'));
			Report("[b]".$loguser['name']."[/] successfully added a secondary group (ID: ".$http->post('groupid').") to user ID #".$http->post('userid').".", false);
			Alert(__("Secondary group successfully added."), __("Notice"));
		} else if (!$http->post('userid') && $http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to add a secondary group (ID: ".$http->post('groupid').") to someone.", false);
			Alert(__("Please enter a User ID and try again."), __("Notice"));
		} else if ($http->post('userid') && !$http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to add a secondary group from user ID #".$http->post('userid').".", false);
			Alert(__("Please enter a Group ID and try again."), __("Notice"));
		} else if (!$http->post('userid') && !$http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to add a secondary group.", false);
			Alert(__("Please enter a Group ID and a User ID and try again."), __("Notice"));
		}
	} elseif($http->post('option') == 'remove') {
		if ($http->post('userid') && $http->post('groupid')) {
			Query("DELETE FROM {secondarygroups} (userid,groupid) VALUES ({0},{1})",
				$http->post('userid'), $http->post('groupid'));
			Report("[b]".$loguser['name']."[/] successfully removed a secondary group (ID: ".$http->post('groupid').") from user ID #".$http->post('userid')."", false);
			Alert(__("Secondary group successfully removed."), __("Notice"));
		} else if (!$http->post('userid') && $http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to remove a secondary group (ID: ".$http->post('groupid').") from someone.", false);
			Alert(__("Please enter a User ID and try again."), __("Notice"));
		} else if ($http->post('userid') && !$http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to remove a secondary group from user ID #".$http->post('userid').".", false);
			Alert(__("Please enter a Group ID and try again."), __("Notice"));
		} else if (!$http->post('userid') && !$http->post('groupid')) {
			Report("[b]".$loguser['name']."[/] tried to remove a secondary group.", false);
			Alert(__("Please enter a Group ID and a User ID and try again."), __("Notice"));
		}
	}
} else {
	Alert(__("Welcome to the Secondary groups page, where you can not only see all the current secondary groups, but you can also manage them."), __("Welcome"));
}

$rSecGroups = Query("select * from {secondarygroups} order by userid desc");

$secList = "";
while($sec = Fetch($rSecGroups))
{
	$cellClass = ($cellClass+1) % 2;
	$secList .= "
	<tr class=\"cell$cellClass\">
		<td>".htmlspecialchars($sec['userid'])."</td>
		<td>".htmlspecialchars($sec['groupid'])."</td>
	</tr>";
}

print "
<table class=\"outline margin\">
	<tr class=\"header1\">
		<th>".__("User ID")."</th>
		<th>".__("Group ID")."</th>
	</tr>
	$secList
</table>

<table class=\"outline\"><tr class=\"header1\"><th colspan=\"2\" class=\"center\">Secondary Groups Manager</th></tr>
<form action=\"".pagelink('secgroups')."\" method=\"POST\" onsubmit=\"submit.disabled = true; return true;\">
<tr class=\"cell2\"><td>User ID</td><td><input type=\"text\" name=\"userid\"></td></tr>
<tr class=\"cell1\"><td>Group ID</td><td><input type=\"text\" name=\"groupid\"></td></tr>
<tr class=\"cell2\"><td>Add/Remove</td><td><select name=\"option\">
    <option value=\"add\">Add</option>
    <option value=\"remove\">Remove</option>
  </select></td></tr>
<tr><td colspan=\"2\" class=\"cell2\"><input type=\"submit\" name=\"submit\" value=\"Add\"></td></tr>
</form>
</table>";