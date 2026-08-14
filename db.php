<?php
// Connect to database using 'thelearninghub'
$conn = mysqli_connect("localhost", "root", "", "thelearninghub");

// If connection fails, print error message instead of crashing PHP
if (!$conn) {
    die("<h3 style='color:red;'>Database Connection Error: " . mysqli_connect_error() . "</h3>");
}
?>