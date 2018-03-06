<?php
session_start();

require_once("database.php");
require_once("functions.php");

$PROJECT_ALL_TASKS = 0;
$project_id = 0;
$show_complete_tasks = 0;

$page = renderTemplate("templates/guest.php", []);
$add_task = null;
$user_id = (isset($_SESSION["user"])) ? get_user_id($connection, $_SESSION["user"]["email"]) : [];
$projects = (isset($_SESSION["user"])) ? get_projects($connection, $user_id) : [];
$tasks = (isset($_SESSION["user"])) ? get_tasks($connection, $user_id) : [];



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_project"])) {
    $errors = [];
    $required_fields = [
        "name"
    ];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "Поле обязательно для заполнения";
        }
    }
     foreach ($projects as $project) {
        if (mb_strtolower(trim($_POST["name"]), "UTF-8") === mb_strtolower($project["name"], "UTF-8")) {
            $errors["name"] = "Проект с таким названием уже существует";
        }
    }
    if (count($errors)) {
        $add_task = renderTemplate("templates/modal-project.php", [
            "errors" => $errors
        ]);
    } else {
        add_project($connection, $user_id, htmlspecialchars (strip_tags($_POST["name"])));
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_task"])) {
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
    } else {
        $date = (!empty($_POST["date"])) ? $_POST["date"] : null;
        $file = (!empty($_FILES["preview"]["name"])) ? $_FILES["preview"] : null;
        add_task(
            $connection,
           htmlspecialchars(strip_tags($_POST["name"])),
            upload_file($file),
            $date,
            get_user_id($connection, $_SESSION["user"]["email"]),
            get_project_id($connection, htmlspecialchars (strip_tags($_POST["project"])))
        );
    }
}


if (isset($_COOKIE["showcompl"])) {
    $show_complete_tasks = ((int) $_COOKIE["showcompl"] === 1) ? 0 : 1;
}

if (isset($_GET["show_completed"])) {
    setcookie("showcompl", $show_complete_tasks, strtotime("+30 days"), "/");
    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

if (isset($_GET["filter"])) {
    setcookie("filter", $_GET["filter"], strtotime("+30 days"), "/");
    header("Location: " . $_SERVER["HTTP_REFERER"]);
} else {
    setcookie("filter", isset($_COOKIE["filter"]) ? $_COOKIE["filter"] : "all", strtotime("+30 days"), "/");
}

if (isset($_GET["login"])) {
    $add_task = renderTemplate("templates/modal-auth.php", []);
}

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
    if (isset($_GET["toggle_done"])) {
        $task_id = (int) $_GET["toggle_done"];
        toggle_done($connection, $task_id, $user_id);
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    if (isset($_COOKIE["filter"])) {
        $filter = $_COOKIE["filter"];
        $project_tasks = filter_tasks_advanced($project_tasks, $filter);
    }
    if (isset($_GET["add_project"])) {
        $add_task = renderTemplate("templates/modal-project.php", []);
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
            "project_tasks" => $project_tasks,
            "show_complete_tasks" => $show_complete_tasks
        ]);
    }
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
        header("Location: index.php");

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
     header("Location: index.php");
}


$layout = renderTemplate("templates/layout.php", [
    "title" => "Дела в порядке",
    "content" => $page,
    "add_task" => $add_task,
    "projects" => $projects,
    "project_id" => $project_id,
    "tasks" => $tasks,
]);

print($layout);
?>
