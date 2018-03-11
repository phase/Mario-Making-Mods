	<table class="outline margin">
		<tr class="header1"><th>
			<strong>{$action}</strong>
		</th></tr>
		<tr class="cell2 center"><td>
			{$redirectText}
			<div class="pollbarContainer" style="margin: 4px auto; width: 25%; display: none;">
				<div class="pollbar" id="theBar" style="background: silver; width: 1%;">&nbsp;</div>
			</div>
		</td></tr>
	</table>
	<meta http-equiv="REFRESH" content="5;URL={$redirectLink}" />
	<script type="text/javascript">
		var barWidth = 1;
		var target = "{$redirectLink}";
		
		function doBar()
		{
			barWidth += 2; //use 2 here for smoother animation
			if (barWidth > 101)
			{
				document.location = target;
			}
			else
			{
				if(barWidth > 100)
					theBar.style['width'] = "100%";
				else
					theBar.style['width'] = barWidth + "%";
				setTimeout("doBar()", 20); //use 20 here for smoother animation
			}
		}
		
		function startBar()
		{
			theBar = document.getElementById("theBar");
			theBar.parentNode.style['display'] = "block";
			doBar();
		}
		
		window.addEventListener("load",  startBar, false);
	</script>
