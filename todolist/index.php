<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'database.php';

// Add new task
if (isset($_POST['add'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $prioritas = mysqli_real_escape_string($conn, $_POST['prioritas']);
    $taskdate = date('Y-m-d'); // Set the current date for the task

    // Validate the task input
    if (!empty($task)) {
        // Insert the task into the database
        $q_insert_task = "INSERT INTO tasks (tasklabel, taskdate, taskstatus, prioritas) VALUES ('$task', '$taskdate', 'open', '$prioritas')";
        $run_q_insert_task = mysqli_query($conn, $q_insert_task);
        
        if ($run_q_insert_task) {
            // Redirect to refresh the page after adding a task
            header('Location: index.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Task cannot be empty!";
    }
}

// Fetch tasks and subtasks
$q_select_tasks = "SELECT * FROM tasks ORDER BY taskid DESC";
$run_q_select_tasks = mysqli_query($conn, $q_select_tasks);

// Delete task
if (isset($_GET['delete'])) {
    $q_delete_task = "DELETE FROM tasks WHERE taskid = '" . $_GET['delete'] . "'";
    $run_q_delete_task = mysqli_query($conn, $q_delete_task);
    header('Refresh:0; url=index.php');
}

// Mark task as done or open
if (isset($_GET['done'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $q_update_task = "UPDATE tasks SET taskstatus = '" . $status . "' WHERE taskid = '" . $_GET['done'] . "'";
    $run_q_update_task = mysqli_query($conn, $q_update_task);
    header('Refresh:0; url=index.php');
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: url('pemandangan.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }
        .container {
            width: 590px;
            height: 100vh;
            margin: 0 auto;
        }
        .header {
            padding: 15px;
            color: black;
            text-align: center;
            background-color: #ffb6c1;
            background: transparent;
            backdrop-filter: blur(20px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header .title {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 7px;
        }
        .header .title i {
            font-size: 28px;
            margin-right: 10px;
            color: #ff69b4; 
        }
        .header .title span {
            font-size: 24px;
            font-weight: bold;
            color: #ff69b4;
        }
        .header .description {
            font-size: 14px;
            color: #fff; 
        }
        .content {
            padding: 15px;
        }
        .card {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ff69b4;
            background: transparent;
            backdrop-filter: blur(20px);
        }
        .input-control {
            width: 100%;
            display: block;
            padding: 0.5rem;
            font-size: 1rem;
            margin-bottom: 10px;
            border: 1px solid #ffb6c1; 
            border-radius: 5px;
            outline: none;
            background-color: #ffe6f0;
        }
        .input-control:focus {
            border-color: #ff69b4;
        }
        .text-right {
            text-align: right;
        }
        button {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            background: #ff69b4;
            color: #fff;
            border: 1px solid #ff69b4;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff3385; 
        }
        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1rem;
        }
        .text-pink {
            color: #ff69b4; 
            transition: color 0.2s ease;
        }
        .text-pink:hover {
            color: #ff3385; 
        }
        .text-red {
            color: #f44f8a; 
            transition: color 0.2s ease;
        }
        .text-red:hover {
            color: #ff3366; 
        }
        .task-item.done span {
            text-decoration: line-through;
            color: #f8a7c7; 
        }
        .logout a {
            display: flex;
            color: #f44f8a; 
            font-weight: bold;
            text-decoration: none;
            transition: color 0.2s ease;
            justify-content: right;
        }
        .logout a:hover {
            color: #ff3366; 
        }
        @media (max-width: 768px) {
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
<div class="header">
        <div class="title">
            <i class='bx bx-sun'></i>
            <span>To Do List</span>
        </div>
        <div class="description"><?= date("l, d M Y") ?></div>
        <!-- Logout Button -->
        <div class="logout">
    <a href="?logout=true" class="text-red">Logout</a>
</div>
</div>

    <div class="content">
        <!-- Add Task Form -->
        <div class="card">
            <form action="" method="post">
                <input type="text" name="task" class="input-control" placeholder="Add Task">
                <select name="prioritas" class="input-control">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <div class="text-right">
                    <button type="submit" name="add">Add Task</button>
                </div>
            </form>
            <?php if (isset($error_message)) { echo "<div style='color:red;'>$error_message</div>"; } ?>
        </div>

        <!-- Display Tasks -->
        <?php
            if(mysqli_num_rows($run_q_select_tasks) > 0){
                while($task = mysqli_fetch_array($run_q_select_tasks)){
        ?>

        <div class="card">
            <div class="task-item <?= $task['taskstatus'] == 'close' ? 'done' : '' ?>">
                <div>
                    <input type="checkbox" onclick="window.location.href = '?done=<?= $task['taskid'] ?>&status=<?= $task['taskstatus'] ?>'" <?= $task['taskstatus'] == 'close' ? 'checked' : '' ?>>
                    <span><?= $task['tasklabel'] ?></span>
                    <span><?= ucfirst($task['prioritas']) ?></span> <!-- Display priority -->
                    <span> , <?= date('d M Y', strtotime($task['taskdate'])) ?></span> <!-- Show task date -->
                </div>
                <div>
                    <a href="subtask.php?id=<?= $task['taskid'] ?>" class="text-pink" title="Subtask"><i class='bx bx-list-plus'></i></a>
                    <a href="edit.php?id=<?= $task['taskid'] ?>" class="text-pink" title="Edit"><i class="bx bx-edit"></i></a>
                    <a href="?delete=<?= $task['taskid'] ?>" class="text-red" title="Delete" onclick="return confirm('Are you sure you want to delete this task?')"><i class="bx bx-trash"></i></a>
                </div>
            </div>
        </div>

        <?php }} else { ?>
            <div>Belum ada task woyy</div>
        <?php } ?>

    </div>
</div>

</body>
</html>
