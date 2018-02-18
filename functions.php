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

function upload_file ($file) {
    if (isset($file["name"])) {
        $file_name = $file["name"];
        $file_path = __DIR__ . "/uploads/";
        $file_url = $file_path . $file_name;
        move_uploaded_file($file["tmp_name"], $file_path . $file_name);
    }
     
    return $file_url;
}
	
?>