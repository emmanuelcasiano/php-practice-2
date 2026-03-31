<?php

declare(strict_types=1);

session_start();

// ── Task Entity ─────────────────────────────────────
class Task
{
    public int $id;
    public bool $completed = false;
    public string $createdAt;

    public function __construct(
        public string $title,
        public string $description = '',
        public string $priority = 'normal',
    ) {
        $this->id = 0;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function complete(): void
    {
        $this->completed = true;
    }
}

// ── Task Manager ─────────────────────────────────────
class TaskManager
{
    public array $tasks = [];

    public function __construct(array $tasks = [])
    {
        $this->tasks = $tasks;
    }

    public function add(Task $task): void
    {
        $task->setId(count($this->tasks) + 1);
        $this->tasks[$task->id] = $task;
    }

    public function delete(int $id): void
    {
        unset($this->tasks[$id]);
    }

    public function update(int $id, string $title, string $desc, string $priority): void
    {
        if (isset($this->tasks[$id])) {
            $this->tasks[$id]->title = $title;
            $this->tasks[$id]->description = $desc;
            $this->tasks[$id]->priority = $priority;
        }
    }

    public function complete(int $id): void
    {
        if (isset($this->tasks[$id])) {
            $this->tasks[$id]->complete();
        }
    }

    public function getTask(int $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    public function getPending(): array
    {
        return array_filter($this->tasks, fn($t) => !$t->completed);
    }

    public function getCompleted(): array
    {
        return array_filter($this->tasks, fn($t) => $t->completed);
    }

    public function filter(array $tasks, string $priority): array
    {
        if ($priority === 'all') return $tasks;
        return array_filter($tasks, fn($t) => $t->priority === $priority);
    }
}

// ── SESSION INIT ─────────────────────────────────────
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$manager = new TaskManager($_SESSION['tasks']);

// ── HANDLE POST ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD
    if (isset($_POST['add_task'])) {
        $manager->add(new Task(
            $_POST['title'],
            $_POST['description'],
            $_POST['priority']
        ));
    }

    // COMPLETE
    if (isset($_POST['complete_id'])) {
        $manager->complete((int)$_POST['complete_id']);
    }

    // DELETE
    if (isset($_POST['delete_id'])) {
        $manager->delete((int)$_POST['delete_id']);
    }

    // UPDATE
    if (isset($_POST['update_task'])) {
        $manager->update(
            (int)$_POST['id'],
            $_POST['title'],
            $_POST['description'],
            $_POST['priority']
        );
    }

    $_SESSION['tasks'] = $manager->tasks;

    header("Location: index.php");
    exit;
}

// ── EDIT MODE (GET) ─────────────────────────────────
$editTask = null;
if (isset($_GET['edit'])) {
    $editTask = $manager->getTask((int)$_GET['edit']);
}

// ── FILTER ──────────────────────────────────────────
$filter = $_GET['priority'] ?? 'all';
$pending = $manager->filter($manager->getPending(), $filter);
$completed = $manager->filter($manager->getCompleted(), $filter);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="container">
        <h1>📋 Task Manager</h1>

        <?php
        echo '<pre>';
        print_r($manager->tasks);
        echo '</pre>';
        ?>

        <!-- ADD / EDIT FORM -->
        <form method="POST" class="form">
            <input type="hidden" name="id" value="<?= $editTask->id ?? '' ?>">

            <input type="text" name="title"
                value="<?= $editTask->title ?? '' ?>"
                placeholder="Task title" required>

            <input type="text" name="description"
                value="<?= $editTask->description ?? '' ?>"
                placeholder="Description">

            <select name="priority">
                <?php $p = $editTask->priority ?? 'normal'; ?>
                <option value="low" <?= $p === 'low' ? 'selected' : '' ?>>Low</option>
                <option value="normal" <?= $p === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="high" <?= $p === 'high' ? 'selected' : '' ?>>High</option>
            </select>

            <?php if ($editTask): ?>
                <button name="update_task">Update</button>
                <a href="index.php">Cancel</a>
            <?php else: ?>
                <button name="add_task">Add</button>
            <?php endif; ?>
        </form>

        <!-- FILTER -->
        <form method="GET" class="filter">
            <select name="priority">
                <option value="all">All</option>
                <option value="low" <?= $filter === 'low' ? 'selected' : '' ?>>Low</option>
                <option value="normal" <?= $filter === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="high" <?= $filter === 'high' ? 'selected' : '' ?>>High</option>
            </select>
            <button>Filter</button>
        </form>

        <!-- PENDING -->
        <h2>Pending</h2>
        <?php foreach ($pending as $task): ?>
            <div class="task <?= $task->priority ?>">
                <strong>#<?= $task->id ?> <?= $task->title ?></strong>
                <p><?= $task->description ?></p>

                <div class="actions">
                    <form method="POST">
                        <input type="hidden" name="complete_id" value="<?= $task->id ?>">
                        <button>Done</button>
                    </form>

                    <a href="?edit=<?= $task->id ?>">Edit</a>

                    <form method="POST">
                        <input type="hidden" name="delete_id" value="<?= $task->id ?>">
                        <button>Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- COMPLETED -->
        <h2>Completed</h2>
        <?php foreach ($completed as $task): ?>
            <div class="task done">
                <strong>#<?= $task->id ?> <?= $task->title ?></strong>
                <p><?= $task->description ?></p>

                <div class="actions">
                    <form method="POST">
                        <input type="hidden" name="delete_id" value="<?= $task->id ?>">
                        <button>Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</body>

</html>