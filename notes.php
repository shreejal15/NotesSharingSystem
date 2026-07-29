<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes | The Learning Hub</title>
    <link rel="stylesheet" href="notes.css">
</head>
<body>

<!-- Navigation -->
<nav class="nav">
    <div class="logo">The Learning Hub</div>

    <ul class="links">
        <li><a href="homepage.html">Home</a></li>
        <li><a href="notes.php" class="active">Notes</a></li>
        <li><a href="quiz.html">Take Quiz</a></li>
        <li><a href="signup.html" class="btn btn-primary">Signup</a></li>
        <li><a href="login.html" class="btn btn-outline">Log In</a></li>
    </ul>
</nav>

<section class="notes-section">

    <h1>BCA (TU) Notes</h1>
    <p>Select a semester to view the subjects.</p>

    <div class="container">

        <!-- Left Panel -->
        <div class="left-panel">

            <button class="semester-btn active" onclick="showSemester(1,this)">
                1st Semester
            </button>

            <button class="semester-btn" onclick="showSemester(2,this)">
                2nd Semester
            </button>

            <button class="semester-btn" onclick="showSemester(3,this)">
                3rd Semester
            </button>

            <button class="semester-btn" onclick="showSemester(4,this)">
                4th Semester
            </button>

        </div>

        <!-- Right Panel -->

        <div class="right-panel">

            <h2 id="semesterTitle">1st Semester Subjects</h2>

            <ul id="subjectList"></ul>

        </div>

    </div>

</section>

<script>

const semesterData = {

1:[
"Computer Fundamentals and Applications",
"Society and Technology",
"English I",
"Mathematics I",
"Digital Logic",
"C Programming"
],

2:[
"Financial Accounting",
"Microprocessor and Computer Architecture",
"English II",
"Mathematics II",
"Data Structures and Algorithms",
"Object-Oriented Programming (Java)"
],

3:[
"Computer Graphics and Animation",
"Probability and Statistics",
"System Analysis and Design",
"Web Technology",
"Operating System",
"Numerical Methods"
],

4:[
"Software Engineering",
"Scripting Language",
"Database Management System",
"Management Information System",
"Computer Networks",
"Project I"
]

};

function showSemester(semester,element){

    document.getElementById("semesterTitle").innerHTML =
    semester + getSuffix(semester) + " Semester Subjects";

    let html="";

    semesterData[semester].forEach(function(subject){

        html += "<li>"+subject+"</li>";

    });

    document.getElementById("subjectList").innerHTML = html;

    let buttons=document.querySelectorAll(".semester-btn");

    buttons.forEach(function(btn){

        btn.classList.remove("active");

    });

    element.classList.add("active");

}

function getSuffix(num){

    if(num==1) return "st";
    if(num==2) return "nd";
    if(num==3) return "rd";

    return "th";

}

window.onload=function(){

    showSemester(1,document.querySelector(".semester-btn"));

}

</script>

</body>
</html>