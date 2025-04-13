<?php
session_start();

// Include database connection
include 'database.php';

// Check if the form is submitted
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Get email from form

    // Query to fetch user details
    $q_select_user = "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND email = '$email'";
    $run_q_select_user = mysqli_query($conn, $q_select_user);

    if (mysqli_num_rows($run_q_select_user) > 0) {
        $_SESSION['user'] = $username;  // Set the session variable
        header("Location: index.php");  // Redirect to main page after successful login
        exit();
    } else {
        $error = "Invalid username, password, or email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('bagus.png') no-repeat;
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px;
        }
        .login-container {
            padding: 30px;
            color: #fff;
            text-align: center;
            background-color: #ffb6c1;
            background: transparent;
            backdrop-filter: blur(20px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        .input-control {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ff69b4;
            background-color: #ffe6f0;
        }
        button {
            padding: 10px 20px;
            background: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 105%;
        }
        button:hover {
            background: #ff3385;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form action="" method="POST">
        <input type="text" name="username" class="input-control" placeholder="Username" required>
        <input type="email" name="email" class="input-control" placeholder="Email" required>
        <input type="password" name="password" class="input-control" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

</body>
</html>
