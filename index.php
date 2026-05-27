<?php
session_start();

$pages = [
    "home" => "home.php",
    "role_select_signin" => "role_select_signin.php",
    "role_select_login" => "role_select_login.php",
    "signup_member" => "signup_member.php",
    "signup_admin" => "signup_admin.php",
    "calender" => "calender.php",
    "learn_more" => "learn_more.php"
];

$page = "home"; // default MUST be key

if (isset($_GET['page']) && array_key_exists($_GET['page'], $pages)) {
    $page = (string)$_GET['page'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['role'])) {
    if ($_POST['role'] === "admin") {
        $page = "signup_admin";
    } elseif ($_POST['role'] === "member") {
        $page = "signup_member";
    }
}

require_once __DIR__ . "/layout.php";

?>