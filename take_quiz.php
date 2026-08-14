<?php
session_start();
if (file_exists("connection.php")) {
    include("connection.php");
} else {
    include("db.php");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 1;

$quiz_res = mysqli_query($conn, "SELECT * FROM quizzes WHERE id='$quiz_id'");
$quiz = mysqli_fetch_assoc($quiz_res);

$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id='$quiz_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Quiz - The Learning Hub</title>
    <link rel="stylesheet" href="notes.css">
</head>
<body>

<nav class="nav">
    <ul>
        <li><a href="<?php echo ($_SESSION['role'] === 'admin') ? 'admin.php' : 'student.php'; ?>">Home</a></li>
        <li><a href="notes.php">Notes</a></li>
        <li><a href="quiz.php">Take Quiz</a></li>
        <li><a href="quiz_results.php">Quiz Results</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="content" style="margin: 40px auto; float: none; width: 700px;">
    <h2>Subject: <?php echo htmlspecialchars($quiz['subject'] ?? 'Quiz'); ?></h2>

    <form action="submit_quiz.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php if ($questions && mysqli_num_rows($questions) > 0): ?>
            <?php $count = 1; ?>
            <?php while ($q = mysqli_fetch_assoc($questions)): ?>
                <div class="note-item" style="display: block; margin-bottom: 20px;">
                    <h3>Q<?php echo $count++; ?>: <?php echo htmlspecialchars($q['question']); ?></h3>
                    <div style="margin-top: 10px;">
                        <label style="display:block; margin:6px 0; font-size:16px;">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="A" required> A) <?php echo htmlspecialchars($q['option_a']); ?>
                        </label>
                        <label style="display:block; margin:6px 0; font-size:16px;">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="B"> B) <?php echo htmlspecialchars($q['option_b']); ?>
                        </label>
                        <label style="display:block; margin:6px 0; font-size:16px;">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="C"> C) <?php echo htmlspecialchars($q['option_c']); ?>
                        </label>
                        <label style="display:block; margin:6px 0; font-size:16px;">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="D"> D) <?php echo htmlspecialchars($q['option_d']); ?>
                        </label>
                    </div>
                </div>
            <?php endwhile; ?>
            <button type="submit" style="background:#ba001c; color:white; border:none; padding:12px 24px; border-radius:8px; font-weight:bold; cursor:pointer; font-size:16px;">Submit Quiz Answers</button>
        <?php else: ?>
            <p>No questions found for this quiz!</p>
        <?php endif; ?>
    </form>
</div>

</body>
</html>