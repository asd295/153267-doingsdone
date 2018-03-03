<?php
require ('functions.php');
require_once("database.php");
// показывать или нет выполненные задачи
$show_complete_tasks = 0;
$add_task = null;
$project_id = 0;
$PROJECT_ALL_TASKS = 0;


$user_id = (isset($_SESSION["user"])) ? get_user_id($connection, $_SESSION["user"]["email"]) : [];
$projects = (isset($_SESSION["user"])) ? get_projects($connection, $user_id) : [];
$tasks = (isset($_SESSION["user"])) ? get_tasks($connection, $user_id) : [];




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
    }
}

if (isset($_COOKIE["showcompl"])) {
    $show_complete_tasks = ($_COOKIE["showcompl"] == 1) ? 0 : 1;
}
if (isset($_GET["show_completed"])) {
    setcookie("showcompl", $show_complete_tasks, strtotime("+30 days"), "/");
    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

session_start();
if (isset($_SESSION["user"])) {
        if (isset($_GET["project_id"])) {
            $project_id = (int) $_GET["project_id"];
            $project_tasks = [];
            if ($project_id === $PROJECT_ALL_TASKS) {
                $project_tasks = filter_tasks($tasks, $projects[$PROJECT_ALL_TASKS]["id"], $show_complete_tasks);
            } elseif (isset($projects[$project_id])) {
                $project_tasks = filter_tasks($tasks, $projects[$project_id]["id"], $show_complete_tasks);
            } else {
                http_response_code(404);
                $message = "Проектов с таким id не найдено.";
            }
        } else {
            $project_tasks = filter_tasks($tasks, $projects[$PROJECT_ALL_TASKS]["id"], $show_complete_tasks);
        }
        if (isset($_GET["add_task"])) {
    $add_task = renderTemplate("templates/modal-task.php", [
        "projects" => array_slice($projects, 1)
    ]);
}

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
        $user = search_user_by_email($connection, $_POST["email"]);
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
if (isset($_GET["register"])) {
    $page = renderTemplate("templates/register.php", []);
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register"])) {
    require_once("register.php");
}
if (isset($_GET["logout"])) {
    require_once("logout.php");
}
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

