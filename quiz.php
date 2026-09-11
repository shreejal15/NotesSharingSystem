<?php

session_start();

/* CHECK LOGIN */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Please login to continue");
    exit();
}


/* CHECK STUDENT ROLE */

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


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>The Learning Hub</h2>


    <a href="student.php">
        Dashboard
    </a>


    <a href="my_notes.php">
        My Notes
    </a>


    <a href="upload_notes.php">
        Upload Notes
    </a>


    <a href="quiz.php" class="active">
        Take Quiz
    </a>


    <a href="quiz_results.php">
        Quiz Results
    </a>


    <a href="logout.php">
        Logout
    </a>

</div>



<!-- MAIN CONTENT -->

<div class="main">


    <div class="quiz-container">


        <h1>
            Take a Quiz
        </h1>


        <p class="subtitle">
            Choose a subject to test your knowledge.
        </p>



        <!-- COMPUTER FUNDAMENTALS -->

        <div class="subject-box">

            <div>

                <h2>
                    Computer Fundamentals and Applications
                </h2>

                <p>
                    Test your knowledge of computer fundamentals.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=1">
                Start Quiz
            </a>

        </div>



        <!-- SOCIETY AND TECHNOLOGY -->

        <div class="subject-box">

            <div>

                <h2>
                    Society and Technology
                </h2>

                <p>
                    Test your knowledge of society and technology.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=2">
                Start Quiz
            </a>

        </div>



        <!-- C PROGRAMMING -->

        <div class="subject-box">

            <div>

                <h2>
                    C Programming
                </h2>

                <p>
                    Test your knowledge of C programming.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=3">
                Start Quiz
            </a>

        </div>



        <!-- DIGITAL LOGIC -->

        <div class="subject-box">

            <div>

                <h2>
                    Digital Logic
                </h2>

                <p>
                    Test your knowledge of digital logic.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=4">
                Start Quiz
            </a>

        </div>



        <!-- MATHEMATICS -->

        <div class="subject-box">

            <div>

                <h2>
                    Mathematics I
                </h2>

                <p>
                    Test your knowledge of Mathematics I.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=5">
                Start Quiz
            </a>

        </div>



        <!-- MICROPROCESSOR -->

        <div class="subject-box">

            <div>

                <h2>
                    Microprocessor
                </h2>

                <p>
                    Test your knowledge of Microprocessor.
                </p>

            </div>


            <a href="take_quiz.php?quiz_id=6">
                Start Quiz
            </a>

        </div>



        <!-- LEADERBOARD -->

        <div class="leaderboard">

            <h2>
                 Quiz Leaderboard
            </h2>


            <p>
                See how you rank against other students.
            </p>


            <a href="leaderboard.php">
                View Leaderboard
            </a>

        </div>


    </div>

</div>


</body>

</html>