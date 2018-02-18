<?php
require ('functions.php');
require_once("userdata.php");
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$add_task = null;
$project_id = 0;

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




if (isset($_GET["add_task"])) {
    $add_task = renderTemplate("templates/modal-task.php", [
        "projects" => array_slice($projects, 1)
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['add_task'])) {
        $errors = [];
        $required_fields = [
            "name",
            "project"
        ];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = "Поле обязательно для заполнения";
            }
        }
        if (count($errors)) {
            $add_task = renderTemplate("templates/modal-task.php", [
                "errors" => $errors,
                "projects" => array_slice($projects, 1)
            ]);
        }
        if (empty($_POST["date"])) {
            $format_date = null;
        } else {
            $format_date = date("d.m.Y", strtotime($_POST["date"]));
        }
        array_unshift($tasks, [
            "task" => $_POST["name"],
            "date" => $format_date,
            "category" => $_POST["project"],
            "file_name" => $_FILES["preview"]["name"],
            "file_url" => upload_file($_FILES["preview"]),
            "realized" => false
        ]);
    }
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



if (isset($_COOKIE["showcompl"])) {
    $show_complete_tasks = ($_COOKIE["showcompl"] == 1) ? 0 : 1;
}
if (isset($_GET["show_completed"])) {
    setcookie("showcompl", $show_complete_tasks, strtotime("+30 days"), "/");
    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

function search_user_by_email($users, $email) {
    $found_user = null;
    foreach ($users as $user) {
        if ($user["email"] === $email) {
            $found_user = $user;
            break;
        }
    }
    return $found_user;
}

<<<<<<< HEAD
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
<<<<<<< HEAD

=======
>>>>>>> b5a93e5c08dde5b30f19c1652bb03345b710ced9
]);
=======
session_start();
if (isset($_SESSION["user"])) {
    if (http_response_code() === 404) {
        $page = renderTemplate("templates/404.php", [
            "message" => $message
        ]);
    } else {
        $page = renderTemplate("templates/index.php", [
        "show_complete_tasks" => $show_complete_tasks,
        "tasks" => $tasks,
        "project_tasks" => $project_tasks
        ]);
    }
} else {
    $page = renderTemplate("templates/guest.php", []);
    if (isset($_GET["login"])) {
        $add_task = renderTemplate("templates/modal-auth.php", []);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
        $errors = [];
        $required_fields = [
            "email",
            "password"
        ];
        $user = search_user_by_email($users, $_POST["email"]);
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = "Поле обязательно для заполнения";
            }
        }
        if (!empty($_POST["email"]) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "E-mail введён некорректно";
        } elseif (!empty($_POST["email"]) && !$user) {
            $errors["email"] = "Пользователь не найден";
        }
        if (!empty($_POST["password"]) && !password_verify($_POST["password"], $user["password"])) {
            $errors["password"] = "Пароль введён неверно";
        }
        if (count($errors)) {
            $add_task = renderTemplate("templates/modal-auth.php", [
                "errors" => $errors
            ]);
        } else {
            $_SESSION["user"] = $user;
            $page = renderTemplate("templates/index.php", [
                "project_tasks" => $project_tasks,
                "show_complete_tasks" => $show_complete_tasks
            ]);
        }
    }
}



if (isset($_GET["logout"])) {
    require_once("logout.php");
}



>>>>>>> 64decc933e4c99dbc78e594faaef3c2549e39c0e
$layout = renderTemplate("templates/layout.php", [
    "title" => "Дела в порядке",
    "content" => $page,
    "add_task" => $add_task,
    "projects" => $projects,
    "tasks" => $tasks,
    "project_id" => $project_id
]);

print($layout);
?>

