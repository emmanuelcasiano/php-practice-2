<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/src/Note.php';
require_once __DIR__ . '/src/NoteManager.php';
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
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description"></textarea>
                <select name="priority">
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                </select>
                <button type="submit" name="add_task">Add</button>
            </form>
        </div>
        <div class="section">
            <h2>Notes</h2>

            <div class="notes">
                <div class="note">
                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequuntur placeat nulla repellendus, reiciendis animi nam officiis aut, ducimus qui esse ea! Ipsum dolorum recusandae dicta. Sapiente excepturi laudantium modi nam.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Title</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Mollitia nihil impedit explicabo? Labore quisquam, aspernatur provident dignissimos, nam eaque culpa obcaecati at quibusdam fuga dicta earum veritatis ducimus necessitatibus! Dolor aspernatur suscipit sequi dolores! Sapiente minima natus veritatis unde impedit totam debitis soluta. Corporis aperiam optio possimus eos porro totam!</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Title</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequuntur placeat nulla repellendus, reiciendis animi nam officiis aut, ducimus qui esse ea! Ipsum dolorum recusandae dicta. Sapiente excepturi laudantium modi nam.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Title</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequuntur placeat nulla repellendus, reiciendis animi nam officiis aut, ducimus qui esse ea! Ipsum dolorum recusandae dicta. Sapiente excepturi laudantium modi nam.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Title</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequuntur placeat nulla repellendus, reiciendis animi nam officiis aut, ducimus qui esse ea! Ipsum dolorum recusandae dicta. Sapiente excepturi laudantium modi nam.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Title</h3>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consequuntur placeat nulla repellendus, reiciendis animi nam officiis aut, ducimus qui esse ea! Ipsum dolorum recusandae dicta. Sapiente excepturi laudantium modi nam.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Lorem, ipsum dolor.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus dolores voluptatum dolor sed eius atque nisi corporis recusandae praesentium! Odit cum, esse nulla iusto similique ducimus non quos molestias sit, atque iure nobis modi repudiandae reprehenderit incidunt aspernatur nostrum illo asperiores, harum porro sunt ut placeat! Veniam, velit quam distinctio autem, fugiat qui hic eaque consectetur nam obcaecati perspiciatis alias.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
                <div class="note">
                    <h3>Lorem ipsum dolor sit.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab molestias numquam saepe quidem nemo iste libero necessitatibus, ducimus tempore sunt quasi, ipsum aspernatur omnis dolores.</p>
                    <div class="actions">
                        <button class="complete">Complete</button>
                        <button class="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Form - Add Note -->
        <div class="modal" id="addNoteModal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add Note</h2>
                <form method="POST">
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="description" placeholder="Description"></textarea>
                    <select name="priority">
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                    <button type="submit" name="add_task">Add</button>
                </form>
            </div>
        </div>
</body>

</html>