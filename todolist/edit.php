<?php
    include 'database.php';

    // select data yang akan di edit
    $q_select = "select * from tasks where taskid = '".$_GET['id']."' ";
    $run_q_select = mysqli_query($conn, $q_select);
    $d = mysqli_fetch_object($run_q_select);

   // proses edit data
   if(isset($_POST['edit'])){
    
    $q_update = "update tasks set tasklabel = '".$_POST['task']."' where taskid = '".$_GET['id']."' ";
    $run_q_update = mysqli_query($conn, $q_update);

    header('Refresh:0; url=index.php');
   }

?>

<!DOCTYPE html>
<html>
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
                <a href="index.php"><i class='bx bx-chevron-left'></i></a>
                <span>Back</span>
        </div>

        <div class="description">
            <?= date("l, d M Y") ?>
        </div>

        <div class="content">
            <div class="card">
                <form action="" method="post">
                    <input type="text" name="task" class="input-control" placeholder="Edit task" value="<?= $d->tasklabel ?>">
                    <div class="text-right">
                        <button type="submit" name="edit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>