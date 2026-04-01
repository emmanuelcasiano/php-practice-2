<?php

declare(strict_types=1);

require_once __DIR__ . '/src/Note.php';
require_once __DIR__ . '/src/NoteManager.php'; // must ensure the class definition is available before you initialize the session_start()

session_start();

// ── SESSION INIT ─────────────────────────────────────
if (!isset($_SESSION['notes'])) {
    $_SESSION['notes'] = [];
}

$note = new NoteManager($_SESSION['notes']);

// ── HANDLE POST ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ADD
    if (isset($_POST['add_note'])) {
        $note->addNote(new Note(
            $_POST['title'],
            $_POST['content']
        ));
    }

    //DELETE
    if (isset($_POST['delete_id'])) {
        $note->deleteNote((int)$_POST['delete_id']);
    }

    //UPDATE
    if (isset($_POST['update_note'])) {
        $note->updateNote(
            (int)$_POST['id'],
            $_POST['title'],
            $_POST['content']
        );
    }

    $_SESSION['notes'] = $note->notes;

    header("Location: index.php");
    exit;
}

// ── EDIT MODE (GET) ─────────────────────────────────
$editNote = null;
if (isset($_GET['edit'])) {
    $editNote = $note->getNote((int)$_GET['edit']);
}

$notes = $note->getNotes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Notes Manager</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Simple Notes Manager</h1>
        <div class="section">
            <h2>Add Note</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editNote->id ?? '' ?>">

                <input type="text" name="title" value="<?= $editNote->title ?? '' ?>" placeholder="Title" required>

                <textarea name="content" placeholder="Content"><?= $editNote->content ?? '' ?></textarea>

                <?php if ($editNote): ?>
                    <button name="update_note">Update</button>
                    <a href="index.php">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_note">Add</button>
                <?php endif; ?>
            </form>
        </div>
        <div class="section">
            <h2>Notes</h2>

            <div class="notes">
                <?php
                foreach ($notes as $note) {
                ?>
                    <div class="note">
                        <h3><?= $note->title ?></h3>
                        <p><?= $note->content ?></p>
                        <div class="actions">
                            <a href="?edit=<?= $note->id ?>">Edit</a>
                            <form method="POST">
                                <input type="hidden" name="delete_id" value="<?= $note->id ?>">
                                <button>Delete</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>


        <!-- Modal Form - Add Note -->
        <div class="modal" id="addNoteModal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add Note</h2>
                <form method="POST">
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="content" placeholder="Content"></textarea>
                    <select name="priority">
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                    <button type="submit" name="add_note">Add</button>
                </form>
            </div>
        </div>
</body>

</html>