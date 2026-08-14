<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT title, subject, course, semester, status, created_at, file_name FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_notes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notes - The Learning Hub</title>
    <link rel="stylesheet" href="student.css">
</head>
<body>

<div class="sidebar">
    <h2>The Learning Hub</h2>
    <a href="student.php">Dashboard</a>
    <a href="my_notes.php" class="active">My Notes</a>
    <a href="upload_notes.php">Upload Notes</a>
    <a href="quiz.php">Take Quiz</a>
    <a href="quiz_results.php">Quiz Results</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h1>My Uploaded Notes</h1>
    </div>

    <div class="recent">
        <table>
            <tr>
                <th>Title</th>
                <th>Subject</th>
                <th>Course & Sem</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php if ($my_notes && $my_notes->num_rows > 0): ?>
                <?php while ($note = $my_notes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['title']); ?></td>
                        <td><?php echo htmlspecialchars($note['subject']); ?></td>
                        <td><?php echo htmlspecialchars($note['course']); ?> (Sem <?php echo htmlspecialchars($note['semester']); ?>)</td>
                        <td><?php echo date('M d, Y', strtotime($note['created_at'])); ?></td>
                        <td>
                            <?php 
                                $status = strtolower($note['status']);
                                $badge_class = 'badge-pending';
                                if ($status === 'approved') $badge_class = 'badge-approved';
                                if ($status === 'rejected') $badge_class = 'badge-rejected';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">You have not uploaded any notes yet.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>