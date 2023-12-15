<?php

function connectDB() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "nuraga";

    $conn = mysqli_connect($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

global $conn;

$conn = connectDB();
?>