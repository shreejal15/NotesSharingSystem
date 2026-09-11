<?php
session_start();
include("db.php");

$error = "";
$success = "";

if (isset($_GET["success"])) {
    $success = "Account created successfully! You can now log in.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"]) || $password == $user["password"]) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["fullname"];
            $_SESSION["role"] = strtolower($user["role"]);

            if ($_SESSION["role"] == "admin") {
                header("Location: admin.php");
            } else {
                header("Location: student.php");
            }

            exit();

        } else {
            $error = "Incorrect password!";
        }

    } else {
        $error = "Email not found!";
    }
}
?>

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

    <p class="subtitle">
        Log in to continue learning
    </p>

    <?php if ($success != "") { ?>
        <p class="success">
            <?php echo $success; ?>
        </p>
    <?php } ?>

    <?php if ($error != "") { ?>
        <p class="error">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <form action="login.php" method="POST">

        <label>Email</label>
        <input type="email"
               name="email"
               placeholder="someone@gmail.com"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Enter your password"
               required>

        <button type="submit">
            Login
        </button>

    </form>

    <p class="login">
        Don't have an account?
        <a href="signup.php">Sign Up</a>
    </p>

    <p class="login">
        <a href="homepage.php">
            (Back to Home Page)
        </a>
    </p>

</div>

</body>
</html>