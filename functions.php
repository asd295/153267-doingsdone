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
        if ($project_id === 0) {
            $count = count($tasks);
        }
        if ($task["project_id"] === $project_id) {
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


function filter_tasks ($tasks, $project_id, $show_complete_tasks) {
    $filtered_tasks = [];
     if ($show_complete_tasks && $project_id === 0) {
        $filtered_tasks = $tasks;
    }
    foreach ($tasks as $key => $task) {
        if ($show_complete_tasks) {
            if ($project_id === $task["project_id"]) {
                $filtered_tasks[] = $tasks[$key];
            }
        } else {
            if ($project === 0 && !$task["realized"]) {
                $filtered_tasks[] = $tasks[$key];
            }
            if ($project_id === $task["project_id"] && !$task["realized"]) {
                $filtered_tasks[] = $tasks[$key];
            }
        }
    }
    return $filtered_tasks;
}

function get_user_id ($db_connect, $email) {
    $sql_query = "SELECT `id` FROM `users` WHERE `email` = ?";
    $statement = mysqli_prepare($db_connect, $sql_query);
    mysqli_stmt_bind_param($statement, "s", $email);
    $execute = mysqli_stmt_execute($statement);
    if (!$execute) {
        print(mysqli_error($db_connect));
        exit;
    }
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_row($result)[0];
}

function get_projects ($db_connect, $user_id) {
    $projects = [
        [
            "id" => 0,
            "name" => "Все"
        ]
    ];
    $sql_query = "SELECT `id`, `name` FROM `projects` WHERE `user_id` = ?";
    $statement = mysqli_prepare($db_connect, $sql_query);
    mysqli_stmt_bind_param($statement, "i", $user_id);
    $execute = mysqli_stmt_execute($statement);
    if (!$execute) {
        print(mysqli_error($db_connect));
        exit;
    }
    $result = mysqli_stmt_get_result($statement);
    $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($fetch as $project) {
        $projects[] = $project;
    }
    return $projects;
}

function get_tasks ($db_connect, $user_id) {
    $sql_query = "SELECT `realized`, `name`, `image_url`, `end_date`, `project_id` FROM `tasks` WHERE `user_id` = ?";
    $statement = mysqli_prepare($db_connect, $sql_query);
    mysqli_stmt_bind_param($statement, "i", $user_id);
    $execute = mysqli_stmt_execute($statement);
    if (!$execute) {
        print(mysqli_error($db_connect));
        exit;
    }
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>