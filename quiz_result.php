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

$user_id = $_SESSION['user_id'];

/* GET STUDENT'S QUIZ RESULTS */

$stmt = $conn->prepare("
    SELECT 
        quiz_results.score,
        quiz_results.total_questions,
        quiz_results.taken_at,
        quizzes.subject
    FROM quiz_results
    INNER JOIN quizzes
        ON quiz_results.quiz_id = quizzes.id
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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz Results - The Learning Hub</title>

    <link rel="stylesheet" href="quiz.css">

</head>

<body>

<nav class="nav">

    <ul>

        <li>
            <a href="student.php">Home</a>
        </li>

        <li>
            <a href="quiz_results.php" class="active">Quiz Results</a>
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

    <h1>My Quiz Results</h1>

    <p class="subtitle">
        View your previous quiz attempts and scores.
    </p>


    <?php if ($results->num_rows > 0): ?>

        <table>

            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Total</th>
                <th>Percentage</th>
                <th>Date</th>
            </tr>


            <?php while ($row = $results->fetch_assoc()): ?>

                <?php

                $score = $row['score'];
                $total = $row['total_questions'];

                $percentage = $total > 0
                    ? round(($score / $total) * 100, 2)
                    : 0;

                ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($row['subject']); ?>
                    </td>

                    <td>
                        <?php echo $score; ?>
                    </td>

                    <td>
                        <?php echo $total; ?>
                    </td>

                    <td>
                        <?php echo $percentage; ?>%
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['taken_at']); ?>
                    </td>

                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <div class="subject-box">

            <h2>No Quiz Results Yet</h2>

            <p>
                You have not completed any quizzes yet.
            </p>

            <a href="quiz.php">
                Take Your First Quiz
            </a>

        </div>

    <?php endif; ?>


</div>

</body>

</html>