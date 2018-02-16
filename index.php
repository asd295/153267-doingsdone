
<?php
require ('functions.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);


$projects = [
    "Все",
    "Входящие",
    "Учеба",
    "Работа",
    "Домашние дела",
    "Авто"
];

$tasks = [
    [
        "task" => "Собеседование в IT компании",
        "date" => "01.06.2018",
        "category" => $projects[3],
        "realized" => false
    ],
    [
        "task" => "Выполнить тестовое задание",
        "date" => "25.05.2018",
        "category" => $projects[3],
        "realized" => false
    ],
    [
        "task" => "Сделать задание первого раздела",
        "date" => "21.04.2018",
        "category" => $projects[2],
        "realized" => true
    ],
    [
        "task" => "Встреча с другом",
        "date" => "22.04.2018",
        "category" => $projects[1],
        "realized" => false
    ],
    [
        "task" => "Купить корм для кота",
        "date" => "",
        "category" => $projects[4],
        "realized" => false
    ],
    [
        "task" => "Заказать пиццу",
        "date" => "",
        "category" => $projects[4],
        "realized" => false
    ]
];

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

if (isset($_GET["id"])) {
    $project_tasks = [];
    $project_id = $_GET["id"];
    $projects_last_id = count($projects) - 1;
    if ($project_id === "0") {
        $project_tasks = $tasks;
    } elseif ($project_id > $projects_last_id) {
        http_response_code(404);
    } else {
        foreach ($tasks as $key => $task) {
            if ($projects[$project_id] === $task["category"]) {
                $project_tasks[] = $tasks[$key];
                
            }
        }
    }
} else {
    $project_tasks = $tasks;
}


$page = renderTemplate("templates/index.php", [
        "show_complete_tasks" => $show_complete_tasks,
        "tasks" => $tasks,
        "project_tasks" => $project_tasks

]);

$layout = renderTemplate("templates/layout.php", [
        "title" => "Дела в порядке",
        "content" => $page,
        "projects" => $projects,
        "tasks" => $tasks
]);


print($layout);
?>



