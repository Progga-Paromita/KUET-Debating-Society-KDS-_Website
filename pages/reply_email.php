<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int)$_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM queries WHERE id=$id")
);

$email = $data['email'];

/* =========================
   SEND EMAIL REPLY
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = $_POST['message'];
    $subject = $_POST['subject'];

    // simple mail function (XAMPP supports only if configured)
    mail($email, $subject, $message);

    // optional: mark as replied
    mysqli_query($conn, "UPDATE queries SET status='replied' WHERE id=$id");

    echo "Email sent successfully!";
}
?>


<section style="height: 900px;"></section>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="reply_email.css">
<div class="reply-wrap">

  <h2>Reply to <?= htmlspecialchars($email) ?></h2>

  <form method="POST">

    <label>Subject</label>
    <input type="text" name="subject" required>

    <label>Message</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Send Email</button>

  </form>

</div>


