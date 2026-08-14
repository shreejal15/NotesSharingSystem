<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != "admin") {
    header("Location: student.php");
    exit();
}

$message = "";

/* Add Question */
if (isset($_POST['add_question'])) {

    $quiz_id = $_POST['quiz_id'];
    $question = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_answer = $_POST['correct_answer'];

    $sql = "INSERT INTO questions 
            (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssss",
        $quiz_id,
        $question,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_answer
    );

    if ($stmt->execute()) {
        $message = "Question added successfully!";
    } else {
        $message = "Something went wrong!";
    }

    $stmt->close();
}


/* Delete Question */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Question deleted successfully!";
    }

    $stmt->close();
}


/* Get Quizzes */
$quiz_sql = "SELECT * FROM quizzes ORDER BY semester, subject";
$quiz_result = mysqli_query($conn, $quiz_sql);


/* Selected Quiz */
$selected_quiz = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : "";


/* Get Questions */
$questions = [];

if ($selected_quiz != "") {

    $stmt = $conn->prepare(
        "SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC"
    );

    $stmt->bind_param("i", $selected_quiz);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Quiz - The Learning Hub</title>

    <link rel="stylesheet" href="manage_quiz.css">

</head>

<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>The<br>Learning Hub</h2>

    <a href="admin.php">
        Dashboard
    </a>

    <a href="manage_notes.php">
        Manage Notes
    </a>

    <a href="manage_quiz.php" class="active">
        Manage Quiz
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<!-- MAIN -->

<div class="main">


    <div class="topbar">

        <h1>Manage Quiz</h1>

    </div>


    <?php if ($message != "") { ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <!-- SELECT QUIZ -->

    <div class="box">

        <h2>Select Quiz</h2>

        <form method="GET">

            <select name="quiz_id" onchange="this.form.submit()" required>

                <option value="">-- Select a Quiz --</option>

                <?php while ($quiz = mysqli_fetch_assoc($quiz_result)) { ?>

                    <option
                        value="<?php echo $quiz['id']; ?>"
                        <?php
                        if ($selected_quiz == $quiz['id']) {
                            echo "selected";
                        }
                        ?>
                    >

                        Semester <?php echo $quiz['semester']; ?>
                        -
                        <?php echo htmlspecialchars($quiz['subject']); ?>

                    </option>

                <?php } ?>

            </select>

        </form>

    </div>


    <?php if ($selected_quiz != "") { ?>


        <!-- ADD QUESTION -->

        <div class="box">

            <h2>Add Question</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="quiz_id"
                    value="<?php echo $selected_quiz; ?>"
                >


                <label>Question</label>

                <textarea
                    name="question"
                    placeholder="Enter question"
                    required
                ></textarea>


                <label>Option A</label>

                <input
                    type="text"
                    name="option_a"
                    placeholder="Enter option A"
                    required
                >


                <label>Option B</label>

                <input
                    type="text"
                    name="option_b"
                    placeholder="Enter option B"
                    required
                >


                <label>Option C</label>

                <input
                    type="text"
                    name="option_c"
                    placeholder="Enter option C"
                    required
                >


                <label>Option D</label>

                <input
                    type="text"
                    name="option_d"
                    placeholder="Enter option D"
                    required
                >


                <label>Correct Answer</label>

                <select name="correct_answer" required>

                    <option value="">-- Select Answer --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>

                </select>


                <button type="submit" name="add_question">
                    Add Question
                </button>

            </form>

        </div>


        <!-- QUESTIONS -->

        <div class="box">

            <h2>Existing Questions</h2>

            <?php if (count($questions) > 0) { ?>

                <?php foreach ($questions as $q) { ?>

                    <div class="question">

                        <h3>
                            <?php echo htmlspecialchars($q['question']); ?>
                        </h3>

                        <p>
                            <b>A:</b>
                            <?php echo htmlspecialchars($q['option_a']); ?>
                        </p>

                        <p>
                            <b>B:</b>
                            <?php echo htmlspecialchars($q['option_b']); ?>
                        </p>

                        <p>
                            <b>C:</b>
                            <?php echo htmlspecialchars($q['option_c']); ?>
                        </p>

                        <p>
                            <b>D:</b>
                            <?php echo htmlspecialchars($q['option_d']); ?>
                        </p>

                        <p class="correct">
                            Correct Answer:
                            <?php echo $q['correct_answer']; ?>
                        </p>


                        <a
                            class="delete"
                            href="manage_quiz.php?quiz_id=<?php echo $selected_quiz; ?>&delete=<?php echo $q['id']; ?>"
                            onclick="return confirm('Delete this question?');"
                        >
                            Delete
                        </a>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p class="no-question">
                    No questions added yet.
                </p>

            <?php } ?>

        </div>


    <?php } ?>


</div>

</body>

</html>