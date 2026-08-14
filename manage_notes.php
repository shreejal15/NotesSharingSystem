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


/* Approve Note */

if (isset($_GET["approve"])) {

    $id = $_GET["approve"];

    $sql = "UPDATE notes
            SET status = 'Approved',
                approved_by = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION["user_id"], $id);
    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* Reject Note */

if (isset($_GET["reject"])) {

    $id = $_GET["reject"];

    $sql = "UPDATE notes
            SET status = 'Rejected'
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* Delete Note */

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM notes WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* Get Notes */

$sql = "SELECT notes.*,
               users.fullname
        FROM notes
        JOIN users ON notes.user_id = users.id
        ORDER BY notes.created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Notes</title>

    <link rel="stylesheet" href="manage_notes.css">

</head>

<body>

<div class="sidebar">

    <h2>The<br>Learning Hub</h2>

    <a href="admin.php">
        Dashboard
    </a>

    <a href="manage_notes.php" class="active">
        Manage Notes
    </a>

    <a href="manage_quiz.php">
        Manage Quiz
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="main">

    <div class="topbar">

        <h1>Manage Notes</h1>

    </div>


    <div class="notes-box">

        <table>

            <tr>

                <th>Student</th>

                <th>Title</th>

                <th>Subject</th>

                <th>Course</th>

                <th>Semester</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php if (mysqli_num_rows($result) > 0) { ?>

                <?php while ($note = mysqli_fetch_assoc($result)) { ?>

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
                            <?php echo htmlspecialchars($note["course"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["semester"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($note["status"]); ?>
                        </td>

                        <td>

                            <?php if ($note["status"] == "Pending") { ?>

                                <a href="manage_notes.php?approve=<?php echo $note['id']; ?>">
                                    Approve
                                </a>

                                <a href="manage_notes.php?reject=<?php echo $note['id']; ?>">
                                    Reject
                                </a>

                            <?php } ?>

                            <a href="manage_notes.php?delete=<?php echo $note['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this note?');">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="7">
                        No notes uploaded yet.
                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>

</html>