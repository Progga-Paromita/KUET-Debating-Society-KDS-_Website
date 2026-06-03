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

<div class="member-register-wrap">
  <div class="member-register-card">
    <div class="member-register-header">
      <div class="member-register-badge">
        👤 Member Profile
      </div>
      <h2><?= htmlspecialchars($user['name']) ?></h2>
      <div class="member-register-subtitle">
        Account details
      </div>
    </div>

    <div class="member-register-grid" style="grid-template-columns: 1fr; gap: 14px; margin-top: 0;">
      <div class="member-register-alert" style="background: rgba(143,174,156,0.14); border-color: rgba(143,174,156,0.25);">
        <i style="color: var(--green-dark);">🪪</i>
        <div>
          <div class="member-register-alert-title">Name</div>
          <div style="font-weight:700; color: var(--text-dark);"><?= htmlspecialchars($user['name']) ?></div>
        </div>
      </div>

      <div class="member-register-alert" style="background: rgba(143,174,156,0.14); border-color: rgba(143,174,156,0.25);">
        <i style="color: var(--green-dark);">✉️</i>
        <div>
          <div class="member-register-alert-title">Email</div>
          <div style="font-weight:700; color: var(--text-dark); word-break: break-all;"><?= htmlspecialchars($user['email']) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
