<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="/assets/css/tasks.css?v=3">
</head>
<body>
    <div class="task-container">
        <h1>タスク管理アプリ</h1>

        <div class="task-add-box">
            <div class="task-add-title">タスク名追加</div>

            <form class="task-add-form" data-bind="submit: addTask">
                <input
                    type="text"
                    placeholder="タスク名"
                    data-bind="value: title"
                    required
                >

                <label>期限日：</label>
                <input
                    type="date"
                    data-bind="value: due_date"
                    required
                >

                <button type="submit" class="add-button">追加</button>
            </form>
        </div>

        <div class="task-columns">
            <div class="task-column">
                <h2>未完了</h2>

                <div data-bind="foreach: incompleteTasks">
                    <div
                        class="task-card"
                        data-bind="css: { 'urgent-task': $parent.isUrgentTask($data) }"
                    >
                        <form data-bind="submit: function() { $parent.updateTask($data); }">
                            <input type="hidden" name="id" data-bind="value: id">

                            <div class="task-card-top">
                                <input
                                    type="text"
                                    name="title"
                                    class="task-name-input"
                                    data-bind="value: title, valueUpdate: 'input'"
                                    required
                                >

                                <button
                                    type="button"
                                    class="complete-button"
                                    data-bind="click: function() { $parent.updateStatus($data, 1); }"
                                >
                                    完了
                                </button>
                            </div>

                            <p>
                                期限日：
                                <input
                                    type="date"
                                    name="due_date"
                                    class="due-date-input"
                                    data-bind="value: due_date, valueUpdate: 'input', event: { change: function() { $parent.updateTask($data); } }"
                                    required
                                >
                            </p>
                        </form>
                    </div>
                </div>
            </div>   

            <div class="task-column">
                <h2>完了</h2>

                <div data-bind="foreach: completedTasks">
                    <div class="task-card done-card">
                        <div class="task-card-top">
                            <span class="done-text" data-bind="text: title"></span>

                            <button
                                type="button"
                                class="delete-button"
                                data-bind="click: function() { $parent.deleteTask($data); }"
                            >
                                削除
                            </button>

                            <button
                                type="button"
                                class="incomplete-button"
                                data-bind="click: function() { $parent.updateStatus($data, 0); }"
                            >
                                未完了へ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.initialTasks = <?= json_encode($tasks) ?>;
    </script>

    <script>
        window.appConfig = <?= json_encode(array(
            'nearDeadlineDays' => Config::get('app.task.near_deadline_days'),
            'statusIncomplete' => Config::get('app.task.status_incomplete'),
            'statusComplete' => Config::get('app.task.status_complete'),
        )) ?>;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
    <script src="/assets/js/tasks.js"></script>
</body>
</html>