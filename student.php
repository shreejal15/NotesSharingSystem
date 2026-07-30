<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != "student"){
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student.css">
</head>

<body>

<div class="sidebar">

    <h2>The Learning Hub</h2>

    <a href="#" class="active"> Dashboard</a>

    <a href="#">My Notes</a>

    <a href="#"> Upload Notes</a>

    <a href="#"> Quiz Results</a>

    <a href="logout.php"> Logout</a>

</div>

<div class="main">

    <div class="topbar">

        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

        <a href="quiz.php" class="quiz-btn">Take Quiz</a>

    </div>

    <div class="cards">

        <div class="card">
            <h3>Uploaded Notes</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Approved Notes</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Pending Notes</h3>
            <p>0</p>
        </div>

    </div>

    <div class="recent">

        <h2>Recent Uploads</h2>

        <table>

            <tr>
                <th>Subject</th>
                <th>Status</th>
            </tr>

            <tr>
                <td>No uploads yet.</td>
                <td>-</td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>