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



<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="edit_member.css">
<section style="height: 300px;"></section>

<div class="admin-page-wrap admin-page-centered" style="padding-top: 80px; padding-left: 18px; padding-right: 18px;">
  <div class="panel" style="margin:0; width:100%;">
    <div class="panel-header">
      <div class="panel-header-icon">👤</div>
      <h2 class="panel-title">Edit Member</h2>
    </div>

    <div class="panel-body">
      <a class="admin-action-link" style="background: rgba(143,174,156,0.18); border:1px solid rgba(143,174,156,0.35); color: #406450;"
         href="index.php?page=profile_admin">⬅ Back to Dashboard</a>

      <form method="POST" style="margin-top:18px;">
        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
          <div class="field full">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($member['name']) ?>" required>
          </div>

          <div class="field full">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>
          </div>
        </div>

        <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap; justify-content:flex-end;">
          <button class="btn btn-primary" type="submit" name="update">✅ Update Member</button>
        </div>
      </form>
    </div>
  </div>
</div>
