<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: index.php?page=login");
    exit;
}

$id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT * FROM member WHERE id=$id");
$user = mysqli_fetch_assoc($res);
?>
<section style="height: 300px;"></section>
<h2>Member Profile</h2>

<p>Name: <?= $user['name'] ?></p>
<p>Email: <?= $user['email'] ?></p>