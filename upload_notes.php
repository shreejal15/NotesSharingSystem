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

$message = "";


/* BCA TU SUBJECTS - ONLY 4 SEMESTERS */

$subjects = [

    1 => [
        "Computer Fundamentals and Applications",
        "Society and Technology",
        "C Programming",
        "Digital Logic",
        "Mathematics I"
    ],

    2 => [
        "Discrete Structure",
        "Microprocessor and Computer Architecture",
        "Data Structure and Algorithm",
        "Mathematics II",
        "Web Technology I"
    ],

    3 => [
        "Object Oriented Programming",
        "Data Communication",
        "Computer Graphics",
        "Statistics",
        "Web Technology II"
    ],

    4 => [
        "Database Management System",
        "Operating System",
        "Software Engineering",
        "Numerical Methods",
        "Programming Language"
    ]

];


/* HANDLE FORM */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['title'] ?? '');
    $semester = trim($_POST['semester'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $course = trim($_POST['course'] ?? '');

    /* CHECK REQUIRED FIELDS */

    if (
        empty($title) ||
        empty($semester) ||
        empty($subject) ||
        empty($course) ||
        !isset($_FILES['note_file'])
    ) {

        $message = "Please fill in all fields and select a file.";

    } else {

        /* CHECK SEMESTER */

        if (!in_array($semester, ['1', '2', '3', '4'])) {

            $message = "Please select a valid semester.";

        } else {

            /* CHECK SUBJECT */

            if (
                !isset($subjects[(int)$semester]) ||
                !in_array($subject, $subjects[(int)$semester])
            ) {

                $message = "Please select a valid subject.";

            } else {

                $file = $_FILES['note_file'];

                /* CHECK UPLOAD ERROR */

                if ($file['error'] !== UPLOAD_ERR_OK) {

                    $message =
                        "There was an error uploading the file.";

                } else {

                    /* FILE EXTENSION */

                    $original_name = basename($file['name']);

                    $extension = strtolower(
                        pathinfo(
                            $original_name,
                            PATHINFO_EXTENSION
                        )
                    );


                    /* ALLOWED FILE TYPES */

                    $allowed_extensions = [
                        'pdf',
                        'doc',
                        'docx',
                        'ppt',
                        'pptx'
                    ];


                    if (!in_array(
                        $extension,
                        $allowed_extensions
                    )) {

                        $message =
                            "Only PDF, DOC, DOCX, PPT and PPTX files are allowed.";

                    } else {

                        /* UPLOAD DIRECTORY */

                        $upload_directory =
                            __DIR__ . "/uploads/notes/";


                        /* CREATE DIRECTORY */

                        if (!is_dir($upload_directory)) {

                            mkdir(
                                $upload_directory,
                                0777,
                                true
                            );
                        }


                        /* CHECK DIRECTORY */

                        if (!is_dir($upload_directory)) {

                            $message =
                                "Unable to create uploads folder.";

                        } elseif (
                            !is_writable($upload_directory)
                        ) {

                            $message =
                                "The uploads folder is not writable.";

                        } else {

                            /* UNIQUE FILE NAME */

                            $new_file_name =
                                time() . "_" .
                                uniqid() . "." .
                                $extension;


                            $file_path =
                                $upload_directory .
                                $new_file_name;


                            /* MOVE FILE */

                            if (
                                move_uploaded_file(
                                    $file['tmp_name'],
                                    $file_path
                                )
                            ) {

                                $status = "pending";


                                /* INSERT INTO DATABASE */

                                $stmt = $conn->prepare("
                                    INSERT INTO notes
                                    (
                                        user_id,
                                        title,
                                        subject,
                                        course,
                                        semester,
                                        file_name,
                                        status
                                    )
                                    VALUES (?, ?, ?, ?, ?, ?, ?)
                                ");


                                if (!$stmt) {

                                    $message =
                                        "Database error: " .
                                        $conn->error;

                                    unlink($file_path);

                                } else {

                                    $stmt->bind_param(
                                        "issssss",
                                        $user_id,
                                        $title,
                                        $subject,
                                        $course,
                                        $semester,
                                        $new_file_name,
                                        $status
                                    );


                                    if ($stmt->execute()) {

                                        $message =
                                            "Note uploaded successfully and is waiting for admin approval.";

                                    } else {

                                        $message =
                                            "File uploaded, but database record could not be saved.";

                                        unlink($file_path);
                                    }


                                    $stmt->close();
                                }

                            } else {

                                $message =
                                    "Unable to save the uploaded file.";
                            }
                        }
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Upload Notes - The Learning Hub</title>

    <link
        rel="stylesheet"
        href="upload_notes.css"
    >

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>
        The Learning Hub
    </h2>


    <a href="student.php">
        Dashboard
    </a>


    <a href="my_notes.php">
        My Notes
    </a>


    <a
        href="upload_notes.php"
        class="active"
    >
        Upload Notes
    </a>


    <a href="quiz.php">
        Take Quiz
    </a>


    <a href="quiz_results.php">
        Quiz Results
    </a>


    <a href="logout.php">
        Logout
    </a>

</div>


<!-- MAIN -->

<div class="main">


    <!-- TOP BAR -->

    <div class="topbar">

        <h1>
            Upload Notes
        </h1>

    </div>


    <!-- MESSAGE -->

    <?php if (!empty($message)): ?>

        <div class="message">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php endif; ?>


    <!-- UPLOAD BOX -->

    <div class="upload-card">

        <div class="heading">

            <h2>
                Upload Study Material
            </h2>

            <p>
                Share your notes with other students.
            </p>

        </div>


        <form
            action="upload_notes.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- TITLE -->

            <div class="form-group">

                <label>
                    Note Title
                </label>

                <input
                    type="text"
                    name="title"
                    placeholder="Enter note title"
                    required
                >

            </div>


            <!-- SEMESTER FIRST -->

            <div class="form-group">

                <label>
                    Semester
                </label>

                <select
                    name="semester"
                    id="semester"
                    required
                >

                    <option value="">
                        -- Select Semester --
                    </option>

                    <option value="1">
                        Semester 1
                    </option>

                    <option value="2">
                        Semester 2
                    </option>

                    <option value="3">
                        Semester 3
                    </option>

                    <option value="4">
                        Semester 4
                    </option>

                </select>

            </div>


            <!-- SUBJECT -->

            <div class="form-group">

                <label>
                    Subject
                </label>

                <select
                    name="subject"
                    id="subject"
                    required
                >

                    <option value="">
                        -- Select Semester First --
                    </option>

                </select>

            </div>


            <!-- COURSE -->

            <div class="form-group">

                <label>
                    Course
                </label>

                <input
                    type="text"
                    name="course"
                    value="BCA"
                    readonly
                >

            </div>


            <!-- FILE -->

            <div class="form-group">

                <label>
                    Note File
                </label>

                <input
                    type="file"
                    name="note_file"
                    accept=".pdf,.doc,.docx,.ppt,.pptx"
                    required
                >

                <small>
                    Accepted files: PDF, DOC, DOCX, PPT, PPTX
                </small>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                class="upload-btn"
            >
                Upload Note
            </button>


        </form>

    </div>

</div>


<!-- SEMESTER → SUBJECT JAVASCRIPT -->

<script>

const subjects = {

    1: [
        "Computer Fundamentals and Applications",
        "Society and Technology",
        "C Programming",
        "Digital Logic",
        "Mathematics I"
    ],

    2: [
        "Discrete Structure",
        "Microprocessor and Computer Architecture",
        "Data Structure and Algorithm",
        "Mathematics II",
        "Web Technology I"
    ],

    3: [
        "Object Oriented Programming",
        "Data Communication",
        "Computer Graphics",
        "Statistics",
        "Web Technology II"
    ],

    4: [
        "Database Management System",
        "Operating System",
        "Software Engineering",
        "Numerical Methods",
        "Programming Language"
    ]

};


const semester =
    document.getElementById("semester");

const subject =
    document.getElementById("subject");


semester.addEventListener(
    "change",
    function () {

        const selected =
            this.value;


        subject.innerHTML =
            '<option value="">-- Select Subject --</option>';


        if (selected && subjects[selected]) {

            subjects[selected].forEach(
                function (item) {

                    const option =
                        document.createElement("option");

                    option.value = item;

                    option.textContent = item;

                    subject.appendChild(option);

                }
            );

        }

    }
);

</script>


</body>

</html>