<?php
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $error = "Email already registered. Please use a different email.";

    } else {

        $password = password_hash($password, PASSWORD_DEFAULT);
        $role = "student";

        $sql = "INSERT INTO users (fullname, email, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {

            header("Location: login.php?success=1");
            exit();

        } else {

            $error = "Please try again.";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>

<div class="logo">
    The <br> Learning Hub
</div>

<div class="container">

    <h1>Create Account</h1>

    <p class="subtitle">
        Sign up to get started
    </p>

    <?php if ($error != "") { ?>
        <p class="error">
            <?php echo $error; ?>
        </p>
    <?php } ?>

    <form action="signup.php" method="POST">

        <label>Username</label>
        <input type="text"
               name="username"
               placeholder="Enter your username"
               required>

        <label>Email</label>
        <input type="email"
               name="email"
               placeholder="someone@gmail.com"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Create your password"
               required>

        <button type="submit">
            Sign Up
        </button>

    </form>

    <p class="login">
        Already have an account?
        <a href="login.php">Login</a>
    </p>

    <br>

    <p class="login">
        <a href="homepage.php">
            (Go Back to Home Page)
        </a>
    </p>

</div>

</body>
</html>