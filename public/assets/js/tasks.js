function Task(data) {
    this.id = data.id;
    this.title = ko.observable(data.title);
    this.due_date = ko.observable(data.due_date);
    this.status = ko.observable(Number(data.status));
}

function TaskViewModel() {
    const self = this;

    self.title = ko.observable("");
    self.due_date = ko.observable("");

    self.tasks = ko.observableArray(
        (window.initialTasks || []).map(function(task) {
            return new Task(task);
        })
    );

    self.incompleteTasks = ko.computed(function() {
        return self.tasks()
            .filter(function(task) {
                return task.status() === window.appConfig.statusIncomplete;
            })
            .sort(function(a, b) {
                return new Date(a.due_date()) - new Date(b.due_date());
            });
    });

    self.completedTasks = ko.computed(function() {
        return self.tasks().filter(function(task) {
            return task.status() === window.appConfig.statusComplete;
        });
    });

    self.addTask = async function() {
        const response = await fetch("/tasks/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                title: self.title(),
                due_date: self.due_date(),
            }),
        });

        const data = await response.json();

        if (data.success) {
            self.tasks.push(new Task(data.task));

            self.title("");
            self.due_date("");
        }
    };

    self.updateTask = async function(task) {

        const response = await fetch("/tasks/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                id: task.id,
                title: task.title(),
                due_date: task.due_date(),
            }),
        });

        const data = await response.json();

        if (data.success) {
            self.tasks.sort(function(a, b) {
                return new Date(a.due_date()) - new Date(b.due_date());
            });
        }
    };

    self.updateStatus = async function(task, status) {
        const response = await fetch("/tasks/update_status", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                id: task.id,
                status: status,
            }),
        });

        const data = await response.json();

        if (data.success) {
            task.status(status);
        }
    };

    self.deleteTask = async function(task) {
        const response = await fetch("/tasks/delete", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                id: task.id,
            }),
        });

        const data = await response.json();

        if (data.success) {
            self.tasks.remove(task);
        }
    };

    self.isUrgentTask = function(task) {
        const now = new Date();

        const dueDate = new Date(task.due_date());

        const diffMs = dueDate - now;

        const diffHours = diffMs / (1000 * 60 * 60);

        return diffHours <= (24 * window.appConfig.nearDeadlineDays);
    };
}

ko.applyBindings(new TaskViewModel());