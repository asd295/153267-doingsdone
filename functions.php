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

function number_of_tasks ($tasks, $project) {
    $count = 0;
    foreach ($tasks as $task) {
        if ($project === "Все") {
            $count = count($tasks);
        }
        if ($task["category"] === $project) {
            $count++;
        }
    }
    return $count;
}
function calc_time ($date) {
    $current_timestamp = time();
    $task_timestamp = strtotime($date);
    $seconds_in_day = 86400;
    $difference = floor(($task_timestamp - $current_timestamp) / $seconds_in_day);
    if ($difference < 1) {
        return true;
    }
    return false;
}

function search_user_by_email($db_connect, $email) {
    $sql_query = "SELECT `email`, `password`, `name` FROM `users` WHERE `email` = ?";
    $statement = mysqli_prepare($db_connect, $sql_query);
    mysqli_stmt_bind_param($statement, "s", $email);
    $execute = mysqli_stmt_execute($statement);
    if (!$execute) {
        print(mysqli_error($db_connect));
        exit;
    }
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_assoc($result);
}


function filter_tasks ($tasks, $project, $show_complete_tasks) {
    $filtered_tasks = [];
    if ($show_complete_tasks && $project === "Все") {
        $filtered_tasks = $tasks;
    }
    foreach ($tasks as $key => $task) {
        if ($show_complete_tasks) {
            if ($project === $task["category"]) {
                $filtered_tasks[] = $tasks[$key];
            }
        } else {
            if ($project === "Все" && !$task["realized"]) {
                $filtered_tasks[] = $tasks[$key];
            }
            if ($project === $task["category"] && !$task["realized"]) {
                $filtered_tasks[] = $tasks[$key];
            }
        }
    }
    return $filtered_tasks;
}

?>