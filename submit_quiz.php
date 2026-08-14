<?php

session_start();

require_once "db.php";

/* CHECK LOGIN */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please login to continue");
    exit();
}

/* CHECK STUDENT */

if (strtolower($_SESSION['role']) !== 'student') {
    header("Location: admin.php");
    exit();
}

/* CHECK QUIZ ID */

if (!isset($_POST['quiz_id'])) {
    header("Location: quiz.php");
    exit();
}

$quiz_id = (int) $_POST['quiz_id'];

$answers = $_POST['answers'] ?? array();

/* GET QUIZ */

$stmt = $conn->prepare("
    SELECT subject
    FROM quizzes
    WHERE id = ?
");

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$quiz_result = $stmt->get_result();

if ($quiz_result->num_rows === 0) {
    die("Quiz not found.");
}

$quiz = $quiz_result->fetch_assoc();

$subject = $quiz['subject'];

/* GET QUESTIONS AND CORRECT ANSWERS */

$stmt = $conn->prepare("
    SELECT id, correct_answer
    FROM questions
    WHERE quiz_id = ?
");

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$questions = $stmt->get_result();

$total_questions = $questions->num_rows;
$score = 0;

/* CHECK ANSWERS */

while ($question = $questions->fetch_assoc()) {

    $question_id = $question['id'];

    $correct_answer = strtoupper($question['correct_answer']);

    $student_answer = isset($answers[$question_id])
        ? strtoupper($answers[$question_id])
        : '';

    if ($student_answer === $correct_answer) {
        $score++;
    }
}

/* SAVE RESULT */

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    INSERT INTO quiz_results
    (user_id, quiz_id, score, total_questions)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param(
    "iiii",
    $user_id,
    $quiz_id,
    $score,
    $total_questions
);

$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz Result</title>

    <link rel="stylesheet" href="quiz.css">

</head>

<body>

<nav class="nav">

    <ul>

        <li>
            <a href="student.php">Home</a>
        </li>

        <li>
            <a href="leaderboard.php">Leaderboard</a>
        </li>

        <li>
            <a href="quiz.php">Take Quiz</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</nav>


<div class="quiz-container">

    <h1>Quiz Completed</h1>

    <p class="subtitle">
        <?php echo htmlspecialchars($subject); ?>
    </p>

    <div class="subject-box">

        <h2>Your Result</h2>

        <p>
            Score:
            <strong>
                <?php echo $score; ?> / <?php echo $total_questions; ?>
            </strong>
        </p>

        <p>
            Percentage:
            <strong>
                <?php
                echo $total_questions > 0
                    ? round(($score / $total_questions) * 100, 2)
                    : 0;
                ?>%
            </strong>
        </p>

    </div>

    <br>

    <a href="quiz.php">
        Take Another Quiz
    </a>

</div>

</body>

</html>