<!DOCTYPE html>
<html>
<head>
    <title>Login - The Learning Hub</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>

<div class="logo">
    The <br> Learning Hub
</div>

<div class="container">

    <h1>Welcome Back</h1>
    <p class="subtitle">Log in to continue learning</p>

    <form action="" method="POST">

        <label>Email</label>
        <input type="email" name="email" placeholder="someone@gmail.com">

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password">

        <button type="submit">Log In</button>

    </form>

    <p class="login">
    Don't have an account?
    <a href="signup.php">Sign Up</a>
</p>

<p class="login">
    <a href="homepage.php">(Back to Home Page)</a>
</p>

</div>

</body>
</html>

<?php
session_start();
include("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['fullname'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] == "admin"){
                header("Location: admin.php");
                exit();
            }
            else{
                header("Location: student.php");
                exit();
            }

        }
        else{
            echo "<script>alert('Incorrect password!');</script>";
        }

    }
    else{
        echo "<script>alert('Email not found!');</script>";
    }

}
?>