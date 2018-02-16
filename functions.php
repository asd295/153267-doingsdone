<?php
function renderTemplate($template, $data) {
	$output = "";
	if(file_exists($template)) {
	ob_start('ob_gzhandler');
	extract($data);
	require_once($template);
	$output = ob_get_contents();
	ob_end_clean();
}
	return $output;
}
	
?>
