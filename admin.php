<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location: student.php");
    exit();
}
?>xzAZXSDFERW3QA2   1aZQ

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="sidebar">

    <h2>The<br>Learning Hub</h2>

    <a href="#" class="active"> Dashboard</a>

    <a href="#"> Pending Notes</a>

    <a href="#">Approved Notes</a>

    <a href="#"> Manage Quiz</a>

    <a href="#"> Students</a>

    <a href="logout.php">Logout</a>

</div>

<div class="main">

    <div class="topbar">

        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

    </div>

    <div class="cards">

        <div class="card">
            <h3>Pending Notes</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Approved Notes</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Total Students</h3>
            <p>0</p>
        </div>

    </div>

    <div class="recent">

        <h2>Pending Notes</h2>

        <table>

            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Status</th>
            </tr>

            <tr>
                <td>No pending notes.</td>
                <td>-</td>
                <td>-</td>
            </tr>

        </table>

    </div>

</div>

</body>
</html>