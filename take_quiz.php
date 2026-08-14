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

/* GET SUBJECT */

if (!isset($_GET['subject']) || empty($_GET['subject'])) {
    header("Location: quiz.php");
    exit();
}

$subject = $_GET['subject'];

/* FIND QUIZ */

$stmt = $conn->prepare("SELECT id, subject FROM quizzes WHERE subject = ?");
$stmt->bind_param("s", $subject);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Quiz not found.");
}

$quiz = $result->fetch_assoc();

$quiz_id = $quiz['id'];

/* GET QUESTIONS */

$stmt = $conn->prepare("
    SELECT id, question, option_a, option_b, option_c, option_d
    FROM questions
    WHERE quiz_id = ?
");

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$questions = $stmt->get_result();

if ($questions->num_rows === 0) {
    die("No questions have been added to this quiz yet.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($subject); ?> - Quiz</title>

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
            <a href="quiz.php" class="active">Take Quiz</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</nav>


<div class="quiz-container">

    <h1><?php echo htmlspecialchars($subject); ?></h1>

    <p class="subtitle">
        Answer all the questions and submit your quiz.
    </p>


    <form action="submit_quiz.php" method="POST">

        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">


        <?php

        $number = 1;

        while ($row = $questions->fetch_assoc()) {

        ?>

            <div class="subject-box">

                <h2>
                    Question <?php echo $number; ?>
                </h2>

                <p>
                    <?php echo htmlspecialchars($row['question']); ?>
                </p>


                <label>
                    <input
                        type="radio"
                        name="answers[<?php echo $row['id']; ?>]"
                        value="A"
                        required
                    >
                    <?php echo htmlspecialchars($row['option_a']); ?>
                </label>

                <br><br>

                <label>
                    <input
                        type="radio"
                        name="answers[<?php echo $row['id']; ?>]"
                        value="B"
                    >
                    <?php echo htmlspecialchars($row['option_b']); ?>
                </label>

                <br><br>

                <label>
                    <input
                        type="radio"
                        name="answers[<?php echo $row['id']; ?>]"
                        value="C"
                    >
                    <?php echo htmlspecialchars($row['option_c']); ?>
                </label>

                <br><br>

                <label>
                    <input
                        type="radio"
                        name="answers[<?php echo $row['id']; ?>]"
                        value="D"
                    >
                    <?php echo htmlspecialchars($row['option_d']); ?>
                </label>

            </div>

        <?php

            $number++;

        }

        ?>


        <button type="submit">
            Submit Quiz
        </button>

    </form>

</div>

</body>

</html>