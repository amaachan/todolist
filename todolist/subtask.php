<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'database.php';

// Check if task ID is passed
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$taskid = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch task details
$q_select_task = "SELECT * FROM tasks WHERE taskid = '$taskid'";
$run_q_select_task = mysqli_query($conn, $q_select_task);
$task = mysqli_fetch_array($run_q_select_task);

// Add subtask logic
if (isset($_POST['add_subtask'])) {
    $subtask = mysqli_real_escape_string($conn, $_POST['subtask']);
    if (!empty($subtask)) {
        $q_insert_subtask = "INSERT INTO subtasks (taskid, subtasklabel) VALUES ('$taskid', '$subtask')";
        $run_q_insert_subtask = mysqli_query($conn, $q_insert_subtask);
        if ($run_q_insert_subtask) {
            header("Location: subtask.php?id=$taskid");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Subtask cannot be empty!";
    }
}

// Delete subtask logic
if (isset($_GET['delete_subtask'])) {
    $subtaskid = mysqli_real_escape_string($conn, $_GET['delete_subtask']);

    $q_check_subtask = "SELECT * FROM subtasks WHERE subtaskid = '$subtaskid'";
    $run_q_check_subtask = mysqli_query($conn, $q_check_subtask);

    if (mysqli_num_rows($run_q_check_subtask) > 0) {
        $q_delete_subtask = "DELETE FROM subtasks WHERE subtaskid = '$subtaskid'";
        $run_q_delete_subtask = mysqli_query($conn, $q_delete_subtask);

        if ($run_q_delete_subtask) {
            header("Location: subtask.php?id=$taskid");
            exit();
        } else {
            echo "Gagal menghapus subtask: " . mysqli_error($conn);
        }
    } else {
        echo "Subtask tidak ditemukan.";
    }
}

// Mark subtask as done or open
if (isset($_GET['done_subtask']) && isset($_GET['status'])) {
    $subtaskid = mysqli_real_escape_string($conn, $_GET['done_subtask']);
    $current_status = $_GET['status'];
    $new_status = ($current_status == 'open') ? 'close' : 'open';

    $q_update_subtask = "UPDATE subtasks SET subtaskstatus = '$new_status' WHERE subtaskid = '$subtaskid'";
    $run_q_update_subtask = mysqli_query($conn, $q_update_subtask);

    if ($run_q_update_subtask) {
        header("Location: subtask.php?id=$taskid");
        exit();
    } else {
        echo "Error updating subtask status: " . mysqli_error($conn);
    }
}

// Fetch subtasks
$q_select_subtasks = "SELECT * FROM subtasks WHERE taskid = '$taskid' ORDER BY subtaskid DESC";
$run_q_select_subtasks = mysqli_query($conn, $q_select_subtasks);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subtasks - <?= htmlspecialchars($task['tasklabel']) ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
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
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #ff69b4; 
            font-size: 28px;
            font-weight: bold;
        }
        .title {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
            gap: 8px;
            color: #ff69b4; 
            font-size: 20px;
            font-weight: bold;
        }
        .title i {
            color: #ff69b4;
            font-size: 24px;
        }
        .title span {
            color: #ff69b4;
        }
        .card {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(247, 104, 192, 0.1);
            border-left: 5px solid #ff69b4;
            background: transparent;
            backdrop-filter: blur(20px);
        }
        .input-control {
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .input-control:focus {
            border-color: #ff69b4;
        }
        button {
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            cursor: pointer;
            background: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background: #ff3385;
        }
        .subtask-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .subtask-item.done span {
            text-decoration: line-through;
            color: #bbb;
        }
        .subtask-item .text-red {
            color: #ff3366;
            cursor: pointer;
        }
        .subtask-item .text-red:hover {
            color: #f44f8a;
        }
        .task-item {
            display: flex;
            justify-content: space-between;
        }
        .title {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
            gap: 8px;
            color: #ff69b4; 
            font-size: 20px;
            font-weight: bold;
        }
        .title i {
            color: #ff69b4; 
            font-size: 24px;
        }
        .title span {
             color: #ff69b4;
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
        <h2><?= htmlspecialchars($task['tasklabel']) ?></h2>
    </div>

    <div class="title">
        <a href="index.php"><i class='bx bx-chevron-left'></i></a>
        <span>Back</span>
    </div>

    <!-- Add Subtask Form -->
    <div class="card">
        <form action="" method="post">
            <input type="text" name="subtask" class="input-control" placeholder="Add Subtask" required>
            <button type="submit" name="add_subtask">Add Subtask</button>
        </form>
        <?php if (isset($error_message)) { echo "<div style='color:red;'>$error_message</div>"; } ?>
    </div>

    <!-- Display Subtasks -->
    <?php if (mysqli_num_rows($run_q_select_subtasks) > 0) { ?>
        <?php while ($subtask = mysqli_fetch_array($run_q_select_subtasks)) { ?>
            <div class="card">
                <div class="subtask-item <?= $subtask['subtaskstatus'] == 'close' ? 'done' : '' ?>">
                    <div>
                        <input type="checkbox"
                            onclick="window.location.href = '?id=<?= $taskid ?>&done_subtask=<?= $subtask['subtaskid'] ?>&status=<?= $subtask['subtaskstatus'] ?>'"
                            <?= $subtask['subtaskstatus'] == 'close' ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($subtask['subtasklabel']) ?></span>
                    </div>
                    <div>
                        <a href="?id=<?= $taskid ?>&delete_subtask=<?= $subtask['subtaskid'] ?>" class="text-red" title="Delete" onclick="return confirm('Are you sure you want to delete this subtask?')">
                            <i class="bx bx-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="card"><em>No subtasks available.</em></div>
    <?php } ?>
</div>

</body>
</html>
