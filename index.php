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

$page = renderTemplate("templates/index.php", [
        "show_complete_tasks" => $show_complete_tasks,
        "tasks" => $tasks
]);

$layout = renderTemplate("templates/layout.php", [
        "title" => "Дела в порядке",
        "content" => $page,
        "projects" => $projects,
        "tasks" => $tasks
]);


print($layout);
?>



