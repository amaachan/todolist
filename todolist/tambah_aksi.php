<?php 
// koneksi database
include 'database.php';
 
// menangkap data yang di kirim dari form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
 
// menginput data ke database
mysqli_query($conn, "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')");
 
// mengalihkan halaman kembali ke login.php
header("location:login.php");
 
?>