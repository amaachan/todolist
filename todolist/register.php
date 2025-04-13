<?php
// Include database connection
include 'database.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if the username or email already exists
        $q_check_user = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
        $run_q_check_user = mysqli_query($conn, $q_check_user);
        
        if (mysqli_num_rows($run_q_check_user) > 0) {
            $error = "Username or email already exists!";
        } else {
            // Insert new user into the database
            $q_insert_user = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
            if (mysqli_query($conn, $q_insert_user)) {
                header("Location: login.php"); // Redirect to login page after successful registration
                exit();
            } else {
                $error = "Error registering user!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('bagus.png') no-repeat;
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px;
        }
        .register-container {
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

<div class="register-container">
    <h2>Register</h2>

    <?php if (isset($error)) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form action="" method="POST">
        <input type="text" name="username" class="input-control" placeholder="Username" required>
        <input type="email" name="email" class="input-control" placeholder="Email" required>
        <input type="password" name="password" class="input-control" placeholder="Password" required>
        <input type="password" name="confirm_password" class="input-control" placeholder="Confirm Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
