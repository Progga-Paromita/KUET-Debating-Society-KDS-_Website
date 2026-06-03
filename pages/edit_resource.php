<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

$result = mysqli_query($conn, "SELECT * FROM resources WHERE id=$id");
$res = mysqli_fetch_assoc($result);

if (!$res) {
    echo "Resource not found";
    exit;
}

if (isset($_POST['update'])) {
    $title = trim($_POST['title'] ?? '');
    $link = trim($_POST['link'] ?? '');

    mysqli_query($conn, "
        UPDATE resources
        SET title='$title', link='$link'
        WHERE id=$id
    ");

    header("Location: index.php?page=profile_admin&updated=1");
    exit;
}
?>

<!-- =========================
     UI PART
========================= -->

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="edit_resource.css">
<section style="height: 300px;"></section>

<div class="admin-page-wrap admin-page-centered" style="padding-top: 80px; padding-left: 18px; padding-right: 18px;">
  <div class="panel" style="margin:0; width:100%;">
    <div class="panel-header">
      <div class="panel-header-icon">📁</div>
      <h2 class="panel-title">Edit Resource</h2>
    </div>

    <div class="panel-body">
      <a class="admin-action-link" style="background: rgba(143,174,156,0.18); border:1px solid rgba(143,174,156,0.35); color: #406450;"
         href="index.php?page=profile_admin">⬅ Back to Dashboard</a>

      <form method="POST" style="margin-top:18px;">
        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
          <div class="field full">
            <label>Resource Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($res['title']) ?>" required>
          </div>

          <div class="field full">
            <label>Link (optional)</label>
            <input type="text" name="link" value="<?= htmlspecialchars($res['link']) ?>" placeholder="https://...">
          </div>
        </div>

        <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap; justify-content:flex-end;">
          <button class="btn btn-primary" type="submit" name="update">✅ Update Resource</button>
        </div>
      </form>
    </div>
  </div>
</div>
