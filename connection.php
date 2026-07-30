<?php

$conn = mysqli_connect("localhost", "root", "", "thelearninghub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>