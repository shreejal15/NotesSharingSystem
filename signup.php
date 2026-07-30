<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>

<div class="logo"> The <br> Learning Hub</div>

<div class="container">

    <h1>Create Account</h1>
    <p class="subtitle">Sign up to get started</p>

    <form action="" method="POST">

        <label>Username</label>
        <input type="text" name="username" placeholder="Enter your username">

        <label>Email</label>
        <input type="email" name="email" placeholder="someone@gmail.com">

        <label>Password</label>
        <input type="password" name="password" placeholder="Create your password">

        <button type="submit" name="signup">Sign Up</button>

    </form>

    <p class="login">
    Already have an account?
    <a href="login.php">Login</a> 
</p> <br>

<p class="login">
    <a href="homepage.php">(Back to Home Page)</a>
</p>

</div>
</body>
</html>

<?php
include("connection.php");

if(isset($_POST['signup'])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('Email already exists!');</script>";
    }
    else{

        // Encrypt password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(fullname, email, password)
                VALUES('$username', '$email', '$hashedPassword')";

        if(mysqli_query($conn, $sql)){
            echo "<script>
                    alert('Account created successfully!');
                    window.location='login.php';
                  </script>";
        }
        else{
            echo "<script>alert('Something went wrong!');</script>";
        }
    }
}
?>