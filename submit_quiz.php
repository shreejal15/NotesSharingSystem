<?php
session_start();
require_once "db.php";

// 1. Check if user is logged in and form was submitted
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: quiz.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$quiz_id = isset($_POST['quiz_id']) ? (int)$_POST['quiz_id'] : 0;
$answers = $_POST['answers'] ?? [];

if ($quiz_id === 0 || empty($answers)) {
    header("Location: quiz.php");
    exit();
}

// 2. Fetch correct answers for this quiz
$stmt = $conn->prepare("SELECT id, correct_answer FROM questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

$score = 0;
$total_questions = $result->num_rows;

// 3. Compare student answers with correct answers
while ($row = $result->fetch_assoc()) {
    $question_id = $row['id'];
    $correct_answer = strtoupper(trim($row['correct_answer']));

    if (isset($answers[$question_id])) {
        $student_answer = strtoupper(trim($answers[$question_id]));
        if ($student_answer === $correct_answer) {
            $score++;
        }
    }
}

// 4. Save result into quiz_results table
$insert = $conn->prepare("INSERT INTO quiz_results (user_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)");
$insert->bind_param("iiii", $user_id, $quiz_id, $score, $total_questions);
$insert->execute();

$insert->close();
$stmt->close();

// 5. Redirect to results page
header("Location: quiz_results.php");
exit();
?>