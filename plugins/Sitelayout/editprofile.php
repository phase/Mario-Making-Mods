<?php
function makeLayoutList() {
	$layouts = [];
	$dir = @opendir("layout");
	while ($layout = readdir($dir)) {
		if(!endsWith($layout, ".php") && $layout != "." && $layout != "..") {
			$layouts[$layout] = @file_get_contents("./layout/".$layout."/info.txt");
		}
	}
	closedir($dir);
	ksort($layouts);
	return $layouts;
}

AddField('general', 'presentation', 'layout', __('Layout'), 'select', array('options'=>makeLayoutList()));
