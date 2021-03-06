<h2 class="content__main-heading">Список задач</h2>
<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">
    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>
<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>
    <label class="checkbox">
        <a href="<?= "?show_completed" ?>">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input
                class="checkbox__input visually-hidden"
                type="checkbox"
                <?= ($show_complete_tasks) ? "checked" : ""; ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </a>
    </label>
</div>
<table class="tasks">
    <?php foreach ($project_tasks as $task): ?>
        <tr
            class="
                tasks__item task
                <?= ($task["realized"]) ? "task--completed" : ""; ?>
                <?= calc_time($task["end_date"]) ? "task--important" : ""; ?>
            "
        >
            <td class="task__select">
                <label class="checkbox task__checkbox">
                    <input
                        class="checkbox__input visually-hidden"
                        type="checkbox"
                        <?= ($task["realized"]) ? "checked" : ""; ?>
                    >
                    <span class="checkbox__text">
                        <?= htmlspecialchars($task["name"]); ?>
                    </span>
                </label>
            </td>
            <td class="task__file">
                <?php if (!empty($task["file"])): ?>
                    <a class="download-link" href="<?= $task["file"]; ?>"><?= $task["file"]; ?></a>
                <?php endif; ?>
            </td>
            <td class="task__date">
                <?= ($task["end_date"]) ? date("d.m.y", strtotime($task["end_date"])) : ""; ?>
            </td>
            <td class="task__controls"></td>
        </tr>
    <?php endforeach; ?>
</table>
