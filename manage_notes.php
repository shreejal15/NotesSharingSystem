<?php

session_start();

require_once "db.php";

/* CHECK LOGIN */

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


/* CHECK ADMIN */

if ($_SESSION["role"] != "admin") {
    header("Location: student.php");
    exit();
}


/* APPROVE NOTE */

if (isset($_GET["approve"])) {

    $id = $_GET["approve"];

    $sql = "UPDATE notes
            SET status = 'Approved',
                approved_by = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $_SESSION["user_id"],
        $id
    );

    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* REJECT NOTE */

if (isset($_GET["reject"])) {

    $id = $_GET["reject"];

    $sql = "UPDATE notes
            SET status = 'Rejected'
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* DELETE NOTE */

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM notes WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    header("Location: manage_notes.php");
    exit();
}


/* GET NOTES */

$sql = "SELECT notes.*,
               users.fullname
        FROM notes
        JOIN users ON notes.user_id = users.id
        ORDER BY notes.created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

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
                            <?php
                            echo htmlspecialchars(
                                $note["fullname"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $note["title"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $note["subject"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $note["course"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $note["semester"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $note["status"]
                            );
                            ?>
                        </td>


                        <td>

                            <!-- VIEW NOTE -->

                            <a
                                href="uploads/notes/<?php echo rawurlencode($note['file_name']); ?>"
                                target="_blank"
                            >
                                View
                            </a>


                            <?php if ($note["status"] == "Pending") { ?>

                                <!-- APPROVE -->

                                <a
                                    href="manage_notes.php?approve=<?php echo $note['id']; ?>"
                                    onclick="return confirm('Approve this note?');"
                                >
                                    Approve
                                </a>


                                <!-- REJECT -->

                                <a
                                    href="manage_notes.php?reject=<?php echo $note['id']; ?>"
                                    onclick="return confirm('Reject this note?');"
                                >
                                    Reject
                                </a>

                            <?php } ?>

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