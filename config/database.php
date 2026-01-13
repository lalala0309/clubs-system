<?php
$conn = new mysqli("localhost", "root", "", "clubs-system");
if ($conn->connect_error) {
    die("Database connection failed");
}
?>