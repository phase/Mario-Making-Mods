<?php

function makeAdsense() {
	global $mobileLayout;
	if(HasPermission('forum.hideads')) return;

	if(!$mobileLayout)
		echo "
			<table class=\"post margin\">
				<tr>
					<td class=\"side userlink\">
						Advertisements
					</td>
					<td class=\"meta right\">
						<div style=\"float: left;\">
							Sponsored ads
						</div>
						If you want to hide ads, consider subscribing to our <a href=\"https://www.patreon.com/mariomods/\">Patreon</a>.
					</td>
				</tr>
				<tr>
					<td class=\"side\">
						<div class=\"smallFonts\">
							<br>
							Posts: &infin;/&infin;<br>
							Since: Dec 14th 2017
						</div>
					</td>
					<td class=\"post\">
						<div>
							".getAdsenseCode()."
						</div>
					</td>
				</tr>
			</table>";
	else
		echo '<table class="outline margin">
		<tr class="cell0">
			<td>
				Advertisements
			</td>
		</tr>
		<tr class="cell1">
			<td id="post_2623">
				'.getAdsenseCode().'
			</td>
		</tr>
				<tr class="cell0">
			<td class="right">
				If you want to hide ads, consider subscribing to our <a href="https://www.patreon.com/mariomods/">Patreon</a>.
			</td>
		</tr>
			</tbody></table>';
}

function getAdsenseCode()
{
	return '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MarioMods -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-5872356345317365"
     data-ad-slot="8662373751"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
}