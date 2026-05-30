<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

/* =========================
   GET MEMBER DATA
========================= */
$stmt = $conn->prepare("SELECT * FROM member WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
    echo "<p style='color:red;'>Member not found</p>";
    exit;
}

/* =========================
   UPDATE MEMBER
========================= */
if (isset($_POST['update'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {

        $stmt = $conn->prepare("
            UPDATE member 
            SET name=?, email=? 
            WHERE id=?
        ");

        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();

        // REDIRECT WITH SUCCESS FLAG
        header("Location: index.php?page=profile_admin&updated=1");
        exit;
    }
}
?>


<section style="height: 300px;"></section>
<h2>Edit Member</h2>

<form method="POST">
    <input type="text" name="name" value="<?= htmlspecialchars($member['name']) ?>" required><br><br>

    <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required><br><br>

    <button type="submit" name="update">Update</button>
</form>