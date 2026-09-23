<?php

session_start();
require_once "db.php";

/* Get only approved notes */

$approved_notes = [];

$sql = "SELECT * FROM notes
        WHERE status = 'Approved'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $semester = trim($row['semester']);
        $subject = trim($row['subject']);

        $approved_notes[$semester][$subject][] = $row;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Notes</title>

    <link rel="stylesheet" href="notes.css">

</head>

<body>


<!-- NAVIGATION -->

<nav class="nav">

    <a href="homepage.php" class="logo">The Learning Hub</a>

    <ul>
        <?php if (isset($_SESSION['user_id'])): ?>

            <li><a href="student.php">Home</a></li>
            <li><a href="notes.php">Notes</a></li>
            <li><a href="quiz.php">Take Quiz</a></li>
            <li><a href="quiz_results.php">Quiz Results</a></li>
            <li><a href="logout.php">Logout</a></li>

        <?php else: ?>

            <li><a href="homepage.php">Home</a></li>
            <li><a href="notes.php">Notes</a></li>
            <li><a href="quiz.php">Take Quiz</a></li>
            <li><a href="signup.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>

        <?php endif; ?>
    </ul>

</nav>


<!-- TITLE -->

<h1>BCA (TU) Notes</h1>

<p class="subtitle">
    Select a semester to view the subjects.
</p>


<!-- MAIN CONTAINER -->

<div class="container">


    <!-- LEFT SEMESTER SIDEBAR -->

    <div class="sidebar">

        <button
            class="active"
            onclick="showSemester(1,this)"
        >
            1st Semester
        </button>

        <button
            onclick="showSemester(2,this)"
        >
            2nd Semester
        </button>

        <button
            onclick="showSemester(3,this)"
        >
            3rd Semester
        </button>

        <button
            onclick="showSemester(4,this)"
        >
            4th Semester
        </button>

    </div>


    <!-- RIGHT CONTENT -->

    <div class="content">

        <h2 id="title">
            1st Semester Subjects
        </h2>


        <div id="subjects">


            <!-- SEMESTER 1 -->

            <div class="subject"
                 onclick="showNotes('1','Computer Fundamentals and Applications')">

                Computer Fundamentals and Applications

            </div>


            <div class="subject"
                 onclick="showNotes('1','Society and Technology')">

                Society and Technology

            </div>


            <div class="subject"
                 onclick="showNotes('1','English I')">

                English I

            </div>


            <div class="subject"
                 onclick="showNotes('1','Mathematics I')">

                Mathematics I

            </div>


            <div class="subject"
                 onclick="showNotes('1','Digital Logic')">

                Digital Logic

            </div>


            <div class="subject"
                 onclick="showNotes('1','C Programming')">

                C Programming

            </div>


        </div>

    </div>

</div>


<script>


/* APPROVED NOTES FROM PHP */

const approvedNotes = <?php echo json_encode($approved_notes); ?>;


/* SHOW SEMESTER */

function showSemester(semester, button) {


    document
        .querySelectorAll(".sidebar button")
        .forEach(function(btn) {

            btn.classList.remove("active");

        });


    button.classList.add("active");


    let title =
        document.getElementById("title");

    let subjects =
        document.getElementById("subjects");


    if (semester == 1) {


        title.innerHTML =
            "1st Semester Subjects";


        subjects.innerHTML = `

            <div class="subject"
                 onclick="showNotes('1','Computer Fundamentals and Applications')">
                Computer Fundamentals and Applications
            </div>

            <div class="subject"
                 onclick="showNotes('1','Society and Technology')">
                Society and Technology
            </div>

            <div class="subject"
                 onclick="showNotes('1','English I')">
                English I
            </div>

            <div class="subject"
                 onclick="showNotes('1','Mathematics I')">
                Mathematics I
            </div>

            <div class="subject"
                 onclick="showNotes('1','Digital Logic')">
                Digital Logic
            </div>

            <div class="subject"
                 onclick="showNotes('1','C Programming')">
                C Programming
            </div>

        `;

    }


    else if (semester == 2) {


        title.innerHTML =
            "2nd Semester Subjects";


        subjects.innerHTML = `

            <div class="subject"
                 onclick="showNotes('2','Discrete Structures')">
                Discrete Structures
            </div>

            <div class="subject"
                 onclick="showNotes('2','Data Structures and Algorithms')">
                Data Structures and Algorithms
            </div>

            <div class="subject"
                 onclick="showNotes('2','Microprocessor')">
                Microprocessor
            </div>

            <div class="subject"
                 onclick="showNotes('2','Mathematics II')">
                Mathematics II
            </div>

            <div class="subject"
                 onclick="showNotes('2','Financial Accounting')">
                Financial Accounting
            </div>

            <div class="subject"
                 onclick="showNotes('2','Object Oriented Programming')">
                Object Oriented Programming
            </div>

        `;

    }


    else if (semester == 3) {


        title.innerHTML =
            "3rd Semester Subjects";


        subjects.innerHTML = `

            <div class="subject"
                 onclick="showNotes('3','Computer Architecture')">
                Computer Architecture
            </div>

            <div class="subject"
                 onclick="showNotes('3','Java Programming')">
                Java Programming
            </div>

            <div class="subject"
                 onclick="showNotes('3','Computer Graphics')">
                Computer Graphics
            </div>

            <div class="subject"
                 onclick="showNotes('3','Numerical Methods')">
                Numerical Methods
            </div>

            <div class="subject"
                 onclick="showNotes('3','Statistics')">
                Statistics
            </div>

            <div class="subject"
                 onclick="showNotes('3','Data Communication')">
                Data Communication
            </div>

        `;

    }


    else {


        title.innerHTML =
            "4th Semester Subjects";


        subjects.innerHTML = `

            <div class="subject"
                 onclick="showNotes('4','Operating System')">
                Operating System
            </div>

            <div class="subject"
                 onclick="showNotes('4','Database Management System')">
                Database Management System
            </div>

            <div class="subject"
                 onclick="showNotes('4','Software Engineering')">
                Software Engineering
            </div>

            <div class="subject"
                 onclick="showNotes('4','Web Technology')">
                Web Technology
            </div>

            <div class="subject"
                 onclick="showNotes('4','Management Information System')">
                Management Information System
            </div>

            <div class="subject"
                 onclick="showNotes('4','Project Work')">
                Project Work
            </div>

        `;

    }

}


/* SHOW NOTES FOR SUBJECT */

function showNotes(semester, subject) {


    let title =
        document.getElementById("title");

    let subjects =
        document.getElementById("subjects");


    title.innerHTML =
        subject + " - Notes";


    let notes =
        approvedNotes[semester] &&
        approvedNotes[semester][subject]
            ? approvedNotes[semester][subject]
            : [];


    if (notes.length === 0) {


        subjects.innerHTML = `

            <p style="
                color:#555;
                font-size:18px;
                padding:15px;
            ">

                No approved notes available
                for this subject yet.

            </p>

            <button
                onclick="goBackToSubjects(${semester})"
                style="
                    background:#ba001c;
                    color:white;
                    border:none;
                    padding:10px 20px;
                    border-radius:8px;
                    cursor:pointer;
                    font-weight:bold;
                "
            >

                Back to Subjects

            </button>

        `;

        return;

    }


    let html = "";


    notes.forEach(function(note) {


        html += `

            <div class="note-item">

                <div>

                    <h3>
                        ${escapeHtml(note.title)}
                    </h3>

                    <p>
                        ${escapeHtml(note.subject)}
                        -
                        Semester ${escapeHtml(note.semester)}
                    </p>

                </div>


                <a
                    href="uploads/notes/${encodeURIComponent(note.file_name)}"
                    target="_blank"
                >

                    View Note

                </a>

            </div>

        `;

    });


    html += `

        <button
            onclick="goBackToSubjects(${semester})"
            style="
                background:#ba001c;
                color:white;
                border:none;
                padding:10px 20px;
                border-radius:8px;
                cursor:pointer;
                font-weight:bold;
                margin-top:10px;
            "
        >

            Back to Subjects

        </button>

    `;


    subjects.innerHTML = html;

}


/* GO BACK */

function goBackToSubjects(semester) {


    let button =
        document.querySelectorAll(".sidebar button")[semester - 1];


    showSemester(semester, button);

}


/* SECURITY */

function escapeHtml(text) {

    let div =
        document.createElement("div");

    div.textContent =
        text;

    return div.innerHTML;

}

</script>


</body>

</html>