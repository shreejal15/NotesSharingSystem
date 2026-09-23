<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT quizzes.subject, quiz_results.score, quiz_results.total_questions, quiz_results.taken_at 
    FROM quiz_results 
    JOIN quizzes ON quiz_results.quiz_id = quizzes.id 
    WHERE quiz_results.user_id = ? 
    ORDER BY quiz_results.taken_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results - The Learning Hub</title>
    <link rel="stylesheet" href="student.css">
</head>
<body>
<div class="sidebar">
    <a href="student.php" class="logo">The Learning Hub</a>

    <a href="student.php">Dashboard</a>
    <a href="my_notes.php">My Notes</a>
    <a href="upload_notes.php">Upload Notes</a>
    <a href="quiz_results.php" class="active">Quiz Results</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h1>My Quiz Scores</h1>
    </div>

    <div class="recent">
        <table>
            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Date Taken</th>
            </tr>
            <?php if ($results && $results->num_rows > 0): ?>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <?php 
                        $percentage = round(($row['score'] / $row['total_questions']) * 100);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?></td>
                        <td><strong><?php echo $percentage; ?>%</strong></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($row['taken_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No quiz results found yet. Take a quiz to get started!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>