 <!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <link rel="stylesheet" href="notes.css">
</head>

<body>

    <nav class="nav">
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="notes.php">Notes</a></li>
            <li><a href="quiz.php">Take Quiz</a></li>
            <li><a href="signup.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <h1>BCA (TU) Notes</h1>

    <p class="subtitle">
        Select a semester to view the subjects.
    </p>

    <div class="container">

        <div class="sidebar">

            <button class="active" onmouseover="showSemester(1,this)">
                1st Semester
            </button>

            <button onmouseover="showSemester(2,this)">
                2nd Semester
            </button>

            <button onmouseover="showSemester(3,this)">
                3rd Semester
            </button>

            <button onmouseover="showSemester(4,this)">
                4th Semester
            </button>

        </div>

        <div class="content">

            <h2 id="title">1st Semester Subjects</h2>

            <div id="subjects">

                <div class="subject">Computer Fundamentals and Applications</div>

                <div class="subject">Society and Technology</div>

                <div class="subject">English I</div>

                <div class="subject">Mathematics I</div>

                <div class="subject">Digital Logic</div>

                <div class="subject">C Programming</div>

            </div>

        </div>

    </div>

<script>

function showSemester(semester, button){

    document.querySelectorAll(".sidebar button").forEach(function(btn){
        btn.classList.remove("active");
    });

    button.classList.add("active");

    let title=document.getElementById("title");
    let subjects=document.getElementById("subjects");

    if(semester==1){

        title.innerHTML="1st Semester Subjects";

        subjects.innerHTML=`
        <div class="subject">Computer Fundamentals and Applications</div>
        <div class="subject">Society and Technology</div>
        <div class="subject">English I</div>
        <div class="subject">Mathematics I</div>
        <div class="subject">Digital Logic</div>
        <div class="subject">C Programming</div>`;
    }

    else if(semester==2){

        title.innerHTML="2nd Semester Subjects";

        subjects.innerHTML=`
        <div class="subject">Discrete Structures</div>
        <div class="subject">Data Structures and Algorithms</div>
        <div class="subject">Microprocessor</div>
        <div class="subject">Mathematics II</div>
        <div class="subject">Financial Accounting</div>
        <div class="subject">Object Oriented Programming</div>`;
    }

    else if(semester==3){

        title.innerHTML="3rd Semester Subjects";

        subjects.innerHTML=`
        <div class="subject">Computer Architecture</div>
        <div class="subject">Java Programming</div>
        <div class="subject">Computer Graphics</div>
        <div class="subject">Numerical Methods</div>
        <div class="subject">Statistics</div>
        <div class="subject">Data Communication</div>`;
    }

    else{

        title.innerHTML="4th Semester Subjects";

        subjects.innerHTML=`
        <div class="subject">Operating System</div>
        <div class="subject">Database Management System</div>
        <div class="subject">Software Engineering</div>
        <div class="subject">Web Technology</div>
        <div class="subject">Management Information System</div>
        <div class="subject">Project Work</div>`;
    }

}

</script>

</body>
</html>