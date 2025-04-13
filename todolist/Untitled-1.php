<?php
    session_start();

    // Logout logic
    if (isset($_GET['logout'])) {
        session_destroy(); // Destroy all session data
        header('Location: login.php'); // Redirect to login page
        exit();
    }

    include 'database.php';

    // Add task to database
    if(isset($_POST['add'])){
        $task = $_POST['task'];
        $priority = $_POST['prioritas']; // Get the priority value
        
        // validasi task
        if (empty($task)) {
            echo "<script>alert('Task cannot be empty!');</script>";
        } else {
            $q_insert = "INSERT INTO tasks (tasklabel, taskstatus, prioritas, taskdate) VALUES (
                '$task',
                'open',
                '$priority',
                CURDATE()
            )";
            $run_q_insert = mysqli_query($conn, $q_insert);
        
            if($run_q_insert){
                header('Refresh:0; url=index.php');
            }
        }
    }

    // Fetch tasks and subtasks
    $q_select_tasks = "SELECT * FROM tasks ORDER BY taskid DESC";
    $run_q_select_tasks = mysqli_query($conn, $q_select_tasks);

    // Delete task
    if(isset($_GET['delete'])){
        $q_delete_task = "DELETE FROM tasks WHERE taskid = '".$_GET['delete']."'";
        $run_q_delete_task = mysqli_query($conn, $q_delete_task);
        header('Refresh:0; url=index.php');
    }

    // Mark task as done or open
    if(isset($_GET['done'])){
        $status = ($_GET['status'] == 'open') ? 'close' : 'open';
        $q_update_task = "UPDATE tasks SET taskstatus = '".$status."' WHERE taskid = '".$_GET['done']."'";
        $run_q_update_task = mysqli_query($conn, $q_update_task);
        header('Refresh:0; url=index.php');
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
        /* Your existing styles here */
        
        .logout a {
            color: #f44f8a; 
            font-weight: bold;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .logout a:hover {
            color: #ff3366; 
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
                <a href="subtask.php?id=<?= $task['taskid'] ?>" class="text-pink" title="Edit"><i class='bx bx-list-plus'></i></a>
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
