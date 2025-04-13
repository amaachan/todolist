<?php
include 'database.php';

$subtask = null;
$error_message = "";

// --- Ambil data subtask berdasarkan ID dari URL ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $subtask_id = (int)$_GET['id'];

    $query = "SELECT * FROM subtasks WHERE subtaskid = $subtask_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $subtask = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Subtask dengan ID $subtask_id tidak ditemukan.";
    }
} else {
    $error_message = "ID subtask tidak valid atau tidak dikirim.";
}

// --- Proses update subtask ---
if (isset($_POST['update_subtask'])) {
    $subtask_id = (int)$_POST['subtask_id'];
    $new_label = mysqli_real_escape_string($conn, $_POST['subtask_label']);

    if (!empty($subtask_id) && !empty($new_label)) {
        $query_update = "UPDATE subtasks SET subtasklabel = '$new_label' WHERE subtaskid = $subtask_id";
        $update_result = mysqli_query($conn, $query_update);

        if ($update_result) {
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Gagal update subtask: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Form tidak boleh kosong.";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subtask</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #ffdde1, #ee9ca7);
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-control {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            background: linear-gradient(to right, #ffdde1, #ee9ca7);
            color: #fff;
            border: none;
            border-radius: 3px;
        }
        .error {
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Subtask</h2>

    <?php if ($subtask): ?>
        <form action="" method="POST">
            <input type="hidden" name="subtask_id" value="<?= htmlspecialchars($subtask['subtaskid']) ?>">
            <input type="text" name="subtask_label" class="input-control" value="<?= htmlspecialchars($subtask['subtasklabel']) ?>" placeholder="Edit subtask">
            <button type="submit" name="update_subtask">Update Subtask</button>
        </form>
    <?php else: ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>
</div>

</body>
</html>
