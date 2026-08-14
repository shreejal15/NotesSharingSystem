<?php

session_start();
require_once "db.php";

/* Check login */

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

/* Check admin */

if ($_SESSION["role"] != "admin") {
    header("Location: student.php");
    exit();
}


/* Count Pending Notes */

$sql = "SELECT COUNT(*) AS total FROM notes WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$pending_notes = $row["total"];


/* Count Total Notes */

$sql = "SELECT COUNT(*) AS total FROM notes";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$total_notes = $row["total"];


/* Count Students */

$sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'student'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$total_students = $row["total"];


/* Get Recent Notes */

$sql = "SELECT notes.title,
               notes.subject,
               notes.semester,
               notes.status,
               users.fullname
        FROM notes
        JOIN users ON notes.user_id = users.id
        ORDER BY notes.created_at DESC
        LIMIT 5";

$recent_notes = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="admin.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <h2>
        The<br>Learning Hub
    </h2>

    <a href="admin.php" class="active">
        Dashboard
    </a>

    <a href="manage_notes.php">
        Manage Notes
    </a>


    <a href="manage_quiz.php">
        Manage Quiz
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<!-- MAIN -->

<div class="main">

    <!-- TOP BAR -->

    <div class="topbar">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </h1>

    </div>


    <!-- CARDS -->

    <div class="cards">

        <div class="card">

            <h3>Pending Notes</h3>

            <p>
                <?php echo $pending_notes; ?>
            </p>

        </div>


        <div class="card">

            <h3>Total Notes</h3>

            <p>
                <?php echo $total_notes; ?>
            </p>

        </div>


        <div class="card">

            <h3>Total Students</h3>

            <p>
                <?php echo $total_students; ?>
            </p>

        </div>

    </div>


    <!-- RECENT NOTES -->

    <div class="recent">

        <div class="recent-header">

            <h2>Recent Note Submissions</h2>

            <a href="manage_notes.php">
                Manage Notes
            </a>

        </div>


        <table>

            <tr>

                <th>Student</th>

                <th>Title</th>

                <th>Subject</th>

                <th>Semester</th>

                <th>Status</th>

            </tr>


            <?php if (mysqli_num_rows($recent_notes) > 0) { ?>

                <?php while ($note = mysqli_fetch_assoc($recent_notes)) { ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($note["fullname"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["title"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["subject"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["semester"]); ?>
                        </td>

                        <td>
                            <span class="status">
                                <?php echo htmlspecialchars($note["status"]); ?>
                            </span>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="5">
                        No notes uploaded yet.
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>