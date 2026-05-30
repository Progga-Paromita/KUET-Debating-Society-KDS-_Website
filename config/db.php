<?php
session_start();   // ONLY ONCE HERE

$conn = mysqli_connect("localhost", "root", "", "role_system");

if (!$conn) {
    die("DB connection failed");
}
?>