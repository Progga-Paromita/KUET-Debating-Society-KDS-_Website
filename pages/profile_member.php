<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "member") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) $_SESSION['user_id'];

/* =========================
   GET USER (SAFE)
========================= */
$userQuery = mysqli_prepare($conn, "SELECT * FROM member WHERE id=?");
mysqli_stmt_bind_param($userQuery, "i", $id);
mysqli_stmt_execute($userQuery);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($userQuery));

/* =========================
   UPDATE LAST LOGIN
========================= */
$updateLogin = mysqli_prepare($conn, "UPDATE member SET last_login=NOW() WHERE id=?");
mysqli_stmt_bind_param($updateLogin, "i", $id);
mysqli_stmt_execute($updateLogin);

/* =========================
   PROFILE IMAGE UPLOAD (SAFE)
========================= */
if (isset($_POST['upload_photo'])) {

    if (!empty($_FILES['profile_img']['name'])) {

        $file = $_FILES['profile_img'];

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($ext, $allowed)) {
            header("Location: index.php?page=profile_member&error=invalid_type");
            exit;
        }

        // Validate size (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            header("Location: index.php?page=profile_member&error=too_large");
            exit;
        }

        // Safe unique name
        $newName = "uploads/profile_" . $id . "_" . time() . "." . $ext;

        // Move file
        move_uploaded_file($file['tmp_name'], $newName);

        // Delete old image (optional cleanup)
        if (!empty($user['profile_img']) && file_exists($user['profile_img'])) {
            unlink($user['profile_img']);
        }

        // Update DB
        $up = mysqli_prepare($conn, "UPDATE member SET profile_img=? WHERE id=?");
        mysqli_stmt_bind_param($up, "si", $newName, $id);
        mysqli_stmt_execute($up);
    }

    header("Location: index.php?page=profile_member&success=photo_updated");
    exit;
}
/* =========================
   UPDATE PROFILE (SAFE)
========================= */
if (isset($_POST['update_profile'])) {

    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $bio   = trim($_POST['bio']);

    $up = mysqli_prepare($conn, "
        UPDATE member 
        SET name=?, phone=?, bio=? 
        WHERE id=?
    ");

    mysqli_stmt_bind_param($up, "sssi", $name, $phone, $bio, $id);
    mysqli_stmt_execute($up);

    header("Location: index.php?page=profile_member");
    exit;
}

/* =========================
   REAL STATS (SAFE)
========================= */
$email = $user['email'];

function getCount($conn, $sql, $type, $value) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $type, $value);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
}

$totalQueries = getCount(
    $conn,
    "SELECT COUNT(*) as total FROM queries WHERE email=?",
    "s",
    $email
);

$unreadQueries = getCount(
    $conn,
    "SELECT COUNT(*) as total FROM queries WHERE email=? AND status='unread'",
    "s",
    $email
);

$replies = getCount(
    $conn,
    "SELECT COUNT(*) as total FROM queries WHERE email=? AND status='read'",
    "s",
    $email
);

/* =========================
   RECENT ACTIVITY (FIXED)
========================= */
$recentStmt = mysqli_prepare(
    $conn,
    "SELECT * FROM queries WHERE email=? ORDER BY id DESC LIMIT 5"
);

mysqli_stmt_bind_param($recentStmt, "s", $email);
mysqli_stmt_execute($recentStmt);
$recent = mysqli_stmt_get_result($recentStmt);
?>

<!-- =========================
     UI
========================= -->
<!-- TOP SPACER -->
<section style="height: 300px;"></section>

<link rel="stylesheet" href="member_profile.css">

<div class="dashboard-wrapper">

<!-- LEFT -->
<div class="left-panel">

  <!-- PROFILE CARD -->
  <div class="profile-card">

    <?php if (!empty($user['profile_img'])) { ?>
        <img src="<?= $user['profile_img'] ?>" class="profile-avatar-img">
    <?php } else { ?>
        <div class="profile-avatar-fallback">
            <?= strtoupper(substr($user['name'],0,1)) ?>
        </div>
    <?php } ?>

    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p><?= htmlspecialchars($user['email']) ?></p>

    <span class="badge">👤 Member</span>

    <p style="margin-top:10px; color:#777;">
        <?= $user['bio'] ?? 'No bio added yet' ?>
    </p>

  </div>

  <!-- QUICK ACTIONS -->
  <div class="card">
    <h3>⚡ Quick Actions</h3>

    <form method="POST" enctype="multipart/form-data" class="upload-box">

    <label class="upload-label">
        📷 Choose Profile Image
    </label>

    <input type="file" name="profile_img" id="profileInput" accept="image/*" required>

    <!-- Preview -->
    <div class="preview-box">
        <img id="previewImg" src="#" alt="Preview" style="display:none;">
    </div>

    <button name="upload_photo">Upload Photo</button>

    <p class="hint">Allowed: JPG, PNG, WEBP | Max: 2MB</p>
</form>
    <form method="POST">
      <input type="text" name="name" placeholder="Name" value="<?= $user['name'] ?>">
      <input type="text" name="phone" placeholder="Phone" value="<?= $user['phone'] ?>">
      <textarea name="bio" placeholder="Bio"><?= $user['bio'] ?></textarea>
      <button name="update_profile">Update Profile</button>
    </form>

  </div>

</div>



  <!-- STATS -->
  <div class="stats">

    <div class="stat">
      <h4>Total Messages</h4>
      <p><?= $totalQueries ?></p>
    </div>

    <div class="stat">
      <h4>Unread</h4>
      <p><?= $unreadQueries ?></p>
    </div>

    <div class="stat">
      <h4>Replies</h4>
      <p><?= $replies ?></p>
    </div>

    <div class="stat">
      <h4>Last Login</h4>
      <p><?= $user['last_login'] ?? 'N/A' ?></p>
    </div>

  </div>

  <!-- GRID -->
  <div class="grid">

    <!-- RECENT MESSAGES -->
    <div class="card large">
      <h3>💬 Recent Messages</h3>

      <?php while ($r = mysqli_fetch_assoc($recent)) { ?>
        <div class="message">
          <span class="dot <?= $r['status']=='unread' ? 'red' : 'green' ?>"></span>
          <?= htmlspecialchars($r['question']) ?>
        </div>
      <?php } ?>

    </div>

    <!-- NOTIFICATIONS -->
    <div class="card large">
      <h3>🔔 Notifications</h3>

      <div class="notify">You have <?= $unreadQueries ?> unread messages</div>
      <div class="notify">Total replies received: <?= $replies ?></div>
      <div class="notify">Account active</div>

    </div>

  </div>

  <!-- ACTIVITY -->
  <div class="card">
    <h3>📌 Activity Timeline</h3>

    <?php while ($r = $recent) { ?>
      <div class="timeline">
        ✔ <?= htmlspecialchars($r['question']) ?>
      </div>
    <?php } ?>

  </div>

</div>
</div>


<script>
document.getElementById("profileInput").addEventListener("change", function (event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const preview = document.getElementById("previewImg");
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(file);
    }
});
</script>