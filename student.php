<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["role"] != "student") {
    header("Location: admin.php");
    exit();
}

$user_id = $_SESSION["user_id"];


/* Uploaded notes */

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total FROM notes WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$total_notes = $row["total"];


/* Approved notes */

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM notes
     WHERE user_id = ? AND status = 'Approved'"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$approved_notes = $row["total"];


/* Pending notes */

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM notes
     WHERE user_id = ? AND status = 'Pending'"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$pending_notes = $row["total"];


/* Recent uploads */

$stmt = $conn->prepare(
    "SELECT title, subject, status
     FROM notes
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 5"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$recent_notes = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="student.css">

</head>

<body>

<div class="sidebar">

    <h2>The Learning Hub</h2>

    <a href="student.php" class="active">
        Dashboard
    </a>

    <a href="my_notes.php">
        My Notes
    </a>

    <a href="upload_notes.php">
        Upload Notes
    </a>

    <a href="quiz.php">
        Take Quiz
    </a>

    <a href="quiz_results.php">
        Quiz Results
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="main">

    <div class="topbar">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </h1>

        <a href="quiz.php" class="quiz-btn">
            Take Quiz
        </a>

    </div>


    <div class="cards">

        <div class="card">

            <h3>Uploaded Notes</h3>

            <p><?php echo $total_notes; ?></p>

        </div>


        <div class="card">

            <h3>Approved Notes</h3>

            <p><?php echo $approved_notes; ?></p>

        </div>


        <div class="card">

            <h3>Pending Notes</h3>

            <p><?php echo $pending_notes; ?></p>

        </div>

    </div>


    <div class="recent">

        <h2>Recent Uploads</h2>

        <table>

            <tr>

                <th>Title</th>

                <th>Subject</th>

                <th>Status</th>

            </tr>

            <?php if ($recent_notes->num_rows > 0) { ?>

                <?php while ($note = $recent_notes->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($note["title"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["subject"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["status"]); ?>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="3">
                        No uploads yet.
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>