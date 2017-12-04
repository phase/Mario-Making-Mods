<?php

RenderTemplate('form_welcome', array('fields' => $fields));

MakeCrumbs(array(pageLink("depot") => __('Depot')));
$title = __('Depot');
?>

<table class="outline margin form" style="width:50%;">
<tr class="header1 center"><th>Select your depot mode!</th></tr>
		<tr class="cell0 center">
			<td><a href="/depot/hax">Custom Super Mario Maker Projects</a></td>
		</tr>
		<tr class="cell1 center">
			<td><a href="/depot/level">Custom Super Mario Maker Levels</a></td>
		</tr>
		<tr class="cell0 center">
			<td><a href="/depot/remaker">Custom Super Mario ReMaker Content</a></td>
		</tr>
</table>