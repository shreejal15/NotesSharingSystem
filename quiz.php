<?php

session_start();

/* CHECK LOGIN */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please login to continue");
    exit();
}


/* CHECK USER ROLE */

if ($_SESSION['role'] !== 'student') {
    header("Location: admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Take Quiz - The Learning Hub</title>

    <link rel="stylesheet" href="quiz.css">

</head>

<body>

<!-- NAVIGATION BAR -->

<nav class="nav">

    <ul>

        <!-- Student Home -->
        <li>
            <a href="student.php">Home</a>
        </li>

        <!-- Notes -->
        <li>
            <a href="notes.php">Notes</a>
        </li>

        <!-- Quiz -->
        <li>
            <a href="quiz.php" class="active">Take Quiz</a>
        </li>

        <!-- Logout -->
        <li>
            <a href="logout.php">Logout</a>
        </li>

    </ul>

</nav>


<!-- MAIN QUIZ CONTENT -->

<div class="quiz-container">

    <h1>Take a Quiz</h1>

    <p class="subtitle">
        Choose a subject to test your knowledge.
    </p>


    <!-- SUBJECT 1 -->

    <div class="subject-box">

        <div>

            <h2>Computer Fundamentals and Applications</h2>

            <p>
                Test your knowledge of computer fundamentals.
            </p>

        </div>

        <a href="take_quiz.php?subject=Computer%20Fundamentals%20and%20Applications">
            Start Quiz
        </a>

    </div>


    <!-- SUBJECT 2 -->

    <div class="subject-box">

        <div>

            <h2>Society and Technology</h2>

            <p>
                Test your knowledge of society and technology.
            </p>

        </div>

        <a href="take_quiz.php?subject=Society%20and%20Technology">
            Start Quiz
        </a>

    </div>


    <!-- SUBJECT 3 -->

    <div class="subject-box">

        <div>

            <h2>C Programming</h2>

            <p>
                Test your knowledge of C programming.
            </p>

        </div>

        <a href="take_quiz.php?subject=C%20Programming">
            Start Quiz
        </a>

    </div>


    <!-- SUBJECT 4 -->

    <div class="subject-box">

        <div>

            <h2>Digital Logic</h2>

            <p>
                Test your knowledge of digital logic.
            </p>

        </div>

        <a href="take_quiz.php?subject=Digital%20Logic">
            Start Quiz
        </a>

    </div>


    <!-- SUBJECT 5 -->

    <div class="subject-box">

        <div>

            <h2>Mathematics I</h2>

            <p>
                Test your knowledge of Mathematics I.
            </p>

        </div>

        <a href="take_quiz.php?subject=Mathematics%20I">
            Start Quiz
        </a>

    </div>


    <!-- SUBJECT 6 -->

    <div class="subject-box">

        <div>

            <h2>Microprocessor</h2>

            <p>
                Test your knowledge of microprocessor concepts.
            </p>

        </div>

        <a href="take_quiz.php?subject=Microprocessor">
            Start Quiz
        </a>

    </div>


</div>

</body>

</html>